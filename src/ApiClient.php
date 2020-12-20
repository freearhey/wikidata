<?php

namespace Wikidata;

use GuzzleHttp\Client;

class ApiClient
{
  const API_ENDPOINT = 'https://www.wikidata.org/w/api.php';

  /**
   * @var string Value Id
   */
  private $client;

  /**
   * @param array $data
   */
  public function __construct()
  {
    $this->client = new Client();
  }

  /**
   * Get all entities by their ids from wikidata api
   *
   * @param string $ids The IDs of the entities to get the data from (eg.: Q2)
   * @param string $lang Language (default: en)
   * @param string $props Array of the properties to get back from each entity (supported: aliases, claims, datatype, descriptions, info, labels, sitelinks, sitelinks/urls)
   *
   * @return \Illuminate\Support\Collection
   */
  public function getEntities($ids, $lang = 'en', $props = [])
  {
    $ids = is_array($ids) ? implode('|', $ids) : $ids;

    $props = $props ? implode('|', $props) : null;

    $response = $this->client->get(self::API_ENDPOINT, [
      'query' => [
        'action' => 'wbgetentities',
        'format' => 'json',
        'languages' => $lang,
        'ids' => $ids,
        'sitefilter' => $lang . 'wiki',
        'props' => $props,
      ],
    ]);

    $results = json_decode($response->getBody(), true);

    $data = isset($results['entities']) ? $results['entities'] : [];

    return collect($data);
  }

  /**
   * Searches for entities using labels and aliases
   *
   * @param string $query
   * @param string $lang Language (default: en)
   * @param string $limit Max count of returning items (default: 10)
   *
   * @return \Illuminate\Support\Collection
   */
  public function searchEntities($query, $lang = 'en', $limit = 10)
  {
    $response = $this->client->get(self::API_ENDPOINT, [
      'query' => [
        'action' => 'wbsearchentities',
        'format' => 'json',
        'strictlanguage' => true,
        'language' => $lang,
        'uselang' => $lang,
        'search' => $query,
        'limit' => $limit,
        'props' => '',
      ],
    ]);

    $results = json_decode($response->getBody(), true);

    $data = isset($results['search']) ? $results['search'] : [];

    return collect($data);
  }
}
