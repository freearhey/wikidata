<?php

namespace Wikidata;

use Exception;
use Wikidata\ApiClient;
use Wikidata\Entity;
use Wikidata\SearchResult;
use Wikidata\SparqlClient;

class Wikidata
{
  /**
   * Search entities by term
   *
   * @param string $query
   * @param string $lang Language (default: en)
   * @param string $limit Max count of returning items (default: 10)
   *
   * @return \Illuminate\Support\Collection Return collection of \Wikidata\SearchResult
   */
  public function search($query, $lang = 'en', $limit = 10)
  {
    $client = new ApiClient();

    $collection = $client->searchEntities($query, $lang, $limit);

    $ids = $collection->pluck('id')->toArray();

    $entities = $client->getEntities($ids, $lang, ['sitelinks/urls']);

    $output = $collection->map(function ($item) use ($entities, $lang) {
      $entity = $entities->get($item['id']);
      if (isset($entity)) {
        $site = $lang . 'wiki';
        $item['wiki_url'] = isset($entity['sitelinks'][$site]) ? $entity['sitelinks'][$site]['url'] : null;
      }

      return new SearchResult($item, $lang);
    });

    return $output;
  }

  /**
   * Search entities by property ID and it value
   *
   * @param string $property Wikidata ID of property (e.g.: P646)
   * @param string $value String value of property or Wikidata entity ID (e.g.: Q11696)
   * @param string $lang Language (default: en)
   * @param string $limit Max count of returning items (default: 10)
   *
   * @return \Illuminate\Support\Collection Return collection of \Wikidata\SearchResult
   */
  public function searchBy($property, $value = null, $lang = 'en', $limit = 10)
  {
    if (!is_pid($property)) {
      throw new Exception("First argument in searchBy() must be a valid Wikidata property ID (e.g.: P646).", 1);
    }

    if (!$value) {
      throw new Exception("Second argument in searchBy() must be a string or a valid Wikidata entity ID (e.g.: Q646).", 1);
    }

    $subject = is_qid($value) ? 'wd:' . $value : '"' . $value . '"';

    $query = '
            SELECT ?item ?itemLabel ?itemAltLabel ?itemDescription WHERE {
                ?item wdt:' . $property . ' ' . $subject . '.
                SERVICE wikibase:label {
                    bd:serviceParam wikibase:language "' . $lang . '".
                }
            } LIMIT ' . $limit . '
        ';

    $client = new SparqlClient();

    $data = $client->execute($query);

    $collection = collect($data);

    $collection = $collection->map(function ($data) {
      $data['id'] = str_replace("http://www.wikidata.org/entity/", "", $data['item']);

      return $data;
    });

    $client = new ApiClient();

    $ids = $collection->pluck('id')->toArray();

    $entities = $client->getEntities($ids, $lang, ['sitelinks/urls']);

    $output = $collection->map(function ($data) use ($entities, $lang) {
      $item = [
        'id' => $data['id'],
        'label' => $data['itemLabel'],
        'aliases' => explode(', ', $data['itemAltLabel']),
        'description' => $data['itemDescription'],
      ];

      $entity = $entities->get($item['id']);
      if (isset($entity)) {
        $site = $lang . 'wiki';
        $item['wiki_url'] = isset($entity['sitelinks'][$site]) ? $entity['sitelinks'][$site]['url'] : null;
      }

      return new SearchResult($item, $lang);
    });

    return $output;
  }

  /**
   * Get entity by ID
   *
   * @param string $entityId Wikidata entity ID (e.g.: Q11696)
   * @param string $lang Language
   *
   * @return \Wikidata\Entity Return entity
   */
  public function get($entityId, $lang = 'en')
  {
    if (!is_qid($entityId)) {
      throw new Exception("First argument in get() must by a valid Wikidata entity ID (e.g.: Q646).", 1);
    }

    $query = 'SELECT ?item ?itemLabel ?itemDescription ?itemAltLabel ?prop ?propertyLabel ?statement ?propertyValue ?propertyValueLabel ?qualifier ?qualifierLabel ?qualifierValue ?qualifierValueLabel
        {
            VALUES (?item) {(wd:' . $entityId . ')}
            ?item ?prop ?statement .
            ?statement ?ps ?propertyValue .
            ?property wikibase:claim ?prop .
            ?property wikibase:statementProperty ?ps .
            OPTIONAL { ?statement ?pq ?qualifierValue . ?qualifier wikibase:qualifier ?pq . }
            SERVICE wikibase:label { bd:serviceParam wikibase:language "' . $lang . ',en" }
        }';

    $client = new SparqlClient();

    $data = $client->execute($query);
    if (!$data) {
      var_dump($data);
      var_dump($query);
      return null;
    }

    $entity = new Entity($data, $lang);

    $api = new ApiClient();

    $apiEntity = $api->getEntities($entityId, $lang, ['sitelinks/urls'])->first();

    if (isset($apiEntity)) {
      $site = $lang . 'wiki';
      $entity->wiki_url = isset($apiEntity['sitelinks'][$site]) ? $apiEntity['sitelinks'][$site]['url'] : null;
    }

    return $entity;
  }
}
