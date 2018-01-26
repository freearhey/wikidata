<?php 

namespace Wikidata;

use Sparql\QueryBuilder;
use Sparql\QueryExecuter;
use Exception;
use GuzzleHttp\Client;
use Wikidata\Entity;
use Wikidata\Result;

class Wikidata {
	
    const SPARQL_ENDPOINT = 'https://query.wikidata.org/sparql';
    const API_ENDPOINT = 'https://www.wikidata.org/w/api.php';

    /**
     * Wikidata prefixes for query
     * @var string[]
     */
    private $prefixes = array(
        'wd' => 'http://www.wikidata.org/entity/',
        'wdv' => 'http://www.wikidata.org/value/',
        'wdt' => 'http://www.wikidata.org/prop/direct/',
        'wikibase' => 'http://wikiba.se/ontology#',
        'p' => 'http://www.wikidata.org/prop/',
        'ps' => 'http://www.wikidata.org/prop/statement/',
        'pq' => 'http://www.wikidata.org/prop/qualifier/',
        'rdfs' => 'http://www.w3.org/2000/01/rdf-schema#',
        'bd' => 'http://www.bigdata.com/rdf#',
    );

    /**
     * Format of results
     * @var string
     */
    public $format = 'json';

    /**
     * Language of results
     * @var string
     */
    public $language;

    /**
     * @param string $language
     */
    public function __construct($language = 'en') 
    {
        $this->language = $language;
    }

    /**
     * Search entities by term
     * 
     * @param string $term Search term
     * 
     * @return \Illuminate\Support\Collection Return collection of \Wikidata\Result
     */
    public function search($term) 
    {    
        $client = new Client();    

        $response = $client->get(self::API_ENDPOINT, [
            'query' => [
                'action' => 'wbsearchentities',
                'format' => $this->format,
                'language' => $this->language,
                'search' => $term,
            ]
        ]);

        $results = json_decode($response->getBody());

        $data = $this->formatSearchResults($results);

        return $data;
    }

    /**
     * Search entities by property and value
     * 
     * @param string $property Wikidata ID of property (e.g.: P646)
     * @param string $value String value of property or Wikidata entity ID (e.g.: Q11696)
     * 
     * @return \Illuminate\Support\Collection Return collection of \Wikidata\Result
     */
    public function searchBy($property, $value) 
    {
        if(!$this->is_pid($property)) {
            throw new Exception("First argument in searchBy() must by a valid Wikidata property ID (e.g.: P646).", 1);
        }

        $query = $this->is_qid($value) ? 'wd:'.$value : '"'.$value.'"';

        $queryBuilder = new QueryBuilder($this->prefixes);

        $queryBuilder
             ->select('?item', '?itemLabel', '?itemAltLabel', '?itemDescription')
             ->where('?item', 'wdt:'.$property, $query)
             ->service('wikibase:label', 'bd:serviceParam', 'wikibase:language', '"'. $this->language .'"');

        $queryBuilder->format();

        $queryExecuter = new QueryExecuter(self::SPARQL_ENDPOINT);

        $results = $queryExecuter->execute( $queryBuilder->getSPARQL() ); 

        $data = $this->formatSearchByResults($results);

        return $data;
    }

    /**
     * Get entity by ID
     * 
     * @param string $entityId Wikidata entity ID (e.g.: Q11696)
     * 
     * @return \Wikidata\Entity Return entity
     */
    public function get($entityId) 
    {
        $subject = 'wd:'.$entityId;

        $queryBuilder = new QueryBuilder($this->prefixes);

        $queryBuilder
            ->select('?property', '?valueLabel')
            ->where('?prop', 'wikibase:directClaim', '?claim')
            ->where($subject, '?claim', '?value')
            ->where('?prop', 'rdfs:label', '?property')
            ->service('wikibase:label', 'bd:serviceParam', 'wikibase:language', '"'. $this->language .'"')
            ->filter('LANG(?property) = "en"');

        $queryExecuter = new QueryExecuter(self::SPARQL_ENDPOINT);   

        $results = $queryExecuter->execute($queryBuilder->getSPARQL()); 

        $data = $this->formatProps($results);

        $snippet = $this->getEntitySnippet($entityId);

        return new Entity($snippet->id, $snippet->label, $snippet->aliases, $snippet->description, $data);
    }

    /**
     * Get entity snippet by ID
     * 
     * @param string $entityId Wikidata entity ID (e.g.: Q11696)
     * 
     * @return \Wikidata\Result|null Return entity snippet, including id, label, aliases and description
     */
    private function getEntitySnippet($entityId)
    {
        $client = new Client();    

        $response = $client->get(self::API_ENDPOINT, [
            'query' => [
                'action' => 'wbgetentities',
                'format' => $this->format,
                'languages' => $this->language,
                'props' => 'labels|aliases|descriptions',
                'ids' => $entityId,
            ]
        ]);

        $results = json_decode($response->getBody());

        return $this->formatGetEntitySnippet($results);
    }

    /**
     * Convert array of getEntitySnippet() results to \Wikidata\Result
     * 
     * @param array $results
     * 
     * @return \Wikidata\Result
     */
    private function formatGetEntitySnippet($results)
    {
        $snippet = [
            'id' => null,
            'label' => null,
            'aliases' => [],
            'description' => null,
        ];

        if(!property_exists($results, 'error')) {  

            $collection = collect($results->entities);

            $item = $collection->first();

            if($item) {

                $lang = $this->language;

                $aliases = [];

                if(property_exists($item, 'aliases') && property_exists($item->aliases, $lang)) {
                    $aliases = array_map(function($alias) {
                        return $alias->value;
                    }, $item->aliases->$lang);
                }

                $snippet['id'] = property_exists($item, 'id') ? $item->id : null;
                $snippet['label'] = property_exists($item, 'labels') && property_exists($item->labels, $lang) ? $item->labels->$lang->value : null;
                $snippet['aliases'] = $aliases;
                $snippet['description'] = property_exists($item, 'descriptions') && property_exists($item->descriptions, $lang) ? $item->descriptions->$lang->value : null;

            }
        }

        return new Result(collect($snippet));
    }

    /**
     * Convert array of search() results to collection of \Wikidata\Results
     * 
     * @param array $results
     * 
     * @return \Illuminate\Support\Collection
     */
    private function formatSearchResults($results) 
    {
        $collection = collect($results->search);

        $collection = $collection->map(function($item) {
            return new Result(collect([
                'id' => property_exists($item, 'id') ? $item->id : null,
                'label' => property_exists($item, 'label') ? $item->label : null,
                'aliases' => property_exists($item, 'aliases') ? $item->aliases : null,
                'description' => property_exists($item, 'description') ? $item->description : null,
            ]));
        });

        return $collection;
    }

    /**
     * Convert array of searchBy() results to collection of \Wikidata\Results
     * 
     * @param array $results
     * 
     * @return \Illuminate\Support\Collection
     */
    private function formatSearchByResults($results) 
    {
        $collection = collect($results['bindings']);

        $collection = $collection->map(function($item) {
            return new Result(collect([
                'id' => isset($item['item']) ? str_replace("http://www.wikidata.org/entity/", "", $item['item']['value']) : null,
                'label' => isset($item['itemLabel']) ? $item['itemLabel']['value'] : null,
                'aliases' => isset($item['itemAltLabel']) ? explode(', ', $item['itemAltLabel']['value']) : [],
                'description' => isset($item['itemDescription']) ? $item['itemDescription']['value'] : null
            ]));
        });

        return $collection;
    }

    /**
     * Convert array of properties to collection
     * 
     * @param array $results
     * 
     * @return \Illuminate\Support\Collection
     */
    private function formatProps($results) 
    {
        $collection = collect($results['bindings']);

        $collection = $collection->groupBy(function ($item, $key) {
            return $this->slug($item['property']['value']);
        });

        $collection = $collection->map(function($item) {
            return $item->pluck('valueLabel.value');
        });

        return $collection;
    }

    /**
     * Check if given string is valid Wikidata entity ID
     * 
     * @param string $value
     * 
     * @return bool Return true if string is valid or false
     */
    private function is_qid($value) 
    {
        return preg_match("/^Q[0-9]+/", $value);
    }

    /**
     * Check if given string is valid Wikidata property ID
     * 
     * @param string $value
     * 
     * @return bool Return true if string is valid or false
     */
    private function is_pid($value) 
    {
        return preg_match("/^P[0-9]+/", $value);
    }

    /**
     * Convert name of property to slug
     * 
     * @param string $string
     * 
     * @return string Return slug name of property
     */
    private function slug($string) 
    {
      $separator = '_';
      $flip = '-';
      $string = preg_replace('!['.preg_quote($flip).']+!u', $separator, $string);
      $string = preg_replace('![^'.preg_quote($separator).'\pL\pN\s]+!u', '', mb_strtolower($string));
      $string = preg_replace('!['.preg_quote($separator).'\s]+!u', $separator, $string);

      return trim($string, $separator);
    }
}