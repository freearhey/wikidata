<?php 

namespace Wikidata;

use Asparagus\QueryBuilder;
use Asparagus\QueryExecuter;
use Exception;
use GuzzleHttp\Client;
use Wikidata\Entity;
use Wikidata\Result;

class Wikidata {
	
    const SPARQL_ENDPOINT = 'https://query.wikidata.org/sparql';
    const API_ENDPOINT = 'https://www.wikidata.org/w/api.php';

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

    public $format = 'json';

    public $language;

    public function __construct($language = 'en') {

        $this->language = $language;

    }

    public function search($term) {
        
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

    public function searchBy($property, $value) {

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

    public function get($entityId) {

        $subject = 'wd:'.$entityId;

        $queryBuilder = new QueryBuilder($this->prefixes);

        $queryBuilder
            ->select('?property', '?valueLabel')
            ->where('?prop', 'wikibase:directClaim', '?claim')
            ->where($subject, '?claim', '?value')
            ->where('?prop', 'rdfs:label', '?property')
            ->service('wikibase:label', 'bd:serviceParam', 'wikibase:language', '"'. $this->language .'"')
            ->filter('LANG(?property) = "en"');

        // dd($queryBuilder->format());

        $queryExecuter = new QueryExecuter(self::SPARQL_ENDPOINT);   

        $results = $queryExecuter->execute($queryBuilder->getSPARQL()); 

        $data = $this->formatProps($results);

        return new Entity($data);

    }

    private function formatSearchResults($results) {

        $collection = collect($results->search);

        $collection = $collection->map(function($item) {
            return new Result([
                'id' => property_exists($item, 'id') ? $item->id : null,
                'label' => property_exists($item, 'label') ? $item->label : null,
                'aliases' => property_exists($item, 'aliases') ? $item->aliases : null,
                'description' => property_exists($item, 'description') ? $item->description : null,
            ]);
        });

        return $collection;

    }

    private function formatSearchByResults($results) {

        $collection = collect($results['bindings']);

        $collection = $collection->map(function($item) {
            return new Result([
                'id' => isset($item['item']) ? str_replace("http://www.wikidata.org/entity/", "", $item['item']['value']) : null,
                'label' => isset($item['itemLabel']) ? $item['itemLabel']['value'] : null,
                'aliases' => isset($item['itemAltLabel']) ? explode(', ', $item['itemAltLabel']['value']) : [],
                'description' => isset($item['itemDescription']) ? $item['itemDescription']['value'] : null
            ]);
        });

        return $collection;

    }

    private function formatProps($results) {

        $collection = collect($results['bindings']);

        $collection = $collection->groupBy(function ($item, $key) {
            return str_slug($item['property']['value']);
        });

        $collection = $collection->map(function($item) {
            return $item->pluck('valueLabel.value');
        });

        return $collection;

    }

    private function is_qid($value) {

        return preg_match("/^Q[0-9]+/", $value);

    }

    private function is_pid($value) {

        return preg_match("/^P[0-9]+/", $value);

    }
}