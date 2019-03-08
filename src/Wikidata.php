<?php 

namespace Wikidata;

use Exception;
use Sparql\QueryBuilder;
use Sparql\QueryExecuter;
use GuzzleHttp\Client;
use Wikidata\Entity;
use Wikidata\SearchResult;

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
     * Search entities by term
     * 
     * @param string $term Search term
     * 
     * @return \Illuminate\Support\Collection Return collection of \Wikidata\Result
     */
    public function search($query, $lang = 'en', $limit = 50) 
    {    
        $client = new Client(); 

        $response = $client->get(self::API_ENDPOINT, [
            'query' => [
                'action' => 'wbsearchentities',
                'format' => 'json',
                'strictlanguage' => true,
                'language' => $lang,
                'uselang' => $lang,
                'search' => $query,
                'limit' => $limit,
                'props' => ''
            ]
        ]);

        $results = json_decode($response->getBody(), true);

        $collection = collect($results['search']);

        $output = $collection->map(function($item) use ($lang) {
            return new SearchResult($item, $lang);
        });

        return $output;
    }

    /**
     * Search entities by property and value
     * 
     * @param string $property Wikidata ID of property (e.g.: P646)
     * @param string $value String value of property or Wikidata entity ID (e.g.: Q11696)
     * 
     * @return \Illuminate\Support\Collection Return collection of \Wikidata\Result
     */
    public function searchBy($property, $value, $lang = 'en', $limit = 50) 
    {
        if(!is_pid($property)) {
            throw new Exception("First argument in searchBy() must by a valid Wikidata property ID (e.g.: P646).", 1);
        }

        $query = is_qid($value) ? 'wd:'.$value : '"'.$value.'"';

        $queryBuilder = new QueryBuilder($this->prefixes);

        $queryBuilder
             ->select('?item', '?itemLabel', '?itemAltLabel', '?itemDescription')
             ->where('?item', 'wdt:'.$property, $query)
             ->service('wikibase:label', [
                ['bd:serviceParam', 'wikibase:language', '"'. $lang .'"']
            ])
             ->limit($limit);

        $queryBuilder->format();

        $queryExecuter = new QueryExecuter(self::SPARQL_ENDPOINT);

        $results = $queryExecuter->execute( $queryBuilder->getSPARQL() ); 

        $collection = collect($results['bindings']);

        $output = $collection->map(function($item) use ($lang) {
            return new SearchResult($this->formatData($item), $lang);
        });

        return $output;
    }

    /**
     * Get entity by ID
     * 
     * @param string $entityId Wikidata entity ID (e.g.: Q11696)
     * 
     * @return \Wikidata\Entity Return entity
     */
    public function get($entityId, $lang = 'en') 
    {
        $subject = 'wd:'.$entityId;

        $queryBuilder = new QueryBuilder($this->prefixes);

        $queryBuilder
            ->select('?item','?itemLabel','?itemDescription','?itemAltLabel','?prop','?propLabel','(GROUP_CONCAT(DISTINCT ?valueLabel;separator=", ") AS ?propValues)')
            ->bind($subject, '?item')
            ->where('?prop', 'wikibase:directClaim', '?claim')
            ->where('?item', '?claim', '?value')
            ->service('wikibase:label', [
                ['bd:serviceParam', 'wikibase:language', '"'. $lang .'"'],
                ['?value', 'rdfs:label', '?valueLabel'],
                ['?prop', 'rdfs:label', '?propLabel'],
                ['?item', 'rdfs:label', '?itemLabel'],
                ['?item', 'skos:altLabel', '?itemAltLabel'],
                ['?item', 'schema:description', '?itemDescription']
            ])
            ->groupBy('?item','?itemLabel','?itemDescription','?itemAltLabel','?prop','?propLabel');

        $queryExecuter = new QueryExecuter(self::SPARQL_ENDPOINT);   

        $results = $queryExecuter->execute($queryBuilder->getSPARQL()); 

        $bindings = $results['bindings'];

        if(!$bindings) {
            return null;
        }

        $entity = new Entity($bindings, $lang);

        return $entity;
    }

    private function formatData($data) {
        $id = isset($data['item']) ? str_replace("http://www.wikidata.org/entity/", "", $data['item']['value']) : null;
        $label = isset($data['itemLabel']) ? $data['itemLabel']['value'] : null;
        $aliases = isset($data['itemAltLabel']) ? explode(', ', $data['itemAltLabel']['value']) : [];
        $description = isset($data['itemDescription']) ? $data['itemDescription']['value'] : null;

        return [
            'id' => $id,
            'label' => $label,
            'aliases' => $aliases,
            'description' => $description
        ];
    }
}