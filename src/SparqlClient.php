<?php

namespace Wikidata;

use Exception;
use GuzzleHttp\Client;

class SparqlClient {

  /**
   * Limit on how long can be the query to be sent by GET.
   */
  const MAX_GET_SIZE = 2048;

  /**
   * SPARQL endpoint URL
   * @var string
   */
  const SPARQL_ENDPOINT = 'https://query.wikidata.org/sparql';

  /**
   * Query timeout (seconds)
   * @var int
   */
  private $timeout = 30;

  /**
   * Request method
   * @var array
   */
  private $method = 'GET';

  /**
   * Query SPARQL endpoint
   *
   * @param string $query
   * @param bool $rawData Whether to return only values or full data objects
   *
   * @return array List of results, one row per array element
   *               Each row will contain fields indexed by variable name.
   * @throws SparqlException
   */
  public function execute( $query, $rawData = false ) {

    if ( strlen( $query ) > self::MAX_GET_SIZE ) {
      // big requests go to POST
      $this->method = 'POST';
    }

    $client = new Client();

    $response = $client->request( $this->method, self::SPARQL_ENDPOINT, [
        'query' => [
          "query" => $query,
          "format" => "json",
          "maxQueryTimeMillis" => $this->timeout * 1000
        ]
    ]);

    $status = $response->getStatusCode();

    if ( $status != '200' ) {
      throw new Exception( 'HTTP Error' );
    }

    $result = $response->getBody();

    $data = json_decode( $result, true );

    if ( $data === null || $data === false ) {
      throw new Exception( "HTTP request failed, response:\n" .
        substr( $result, 1024 ) );
    }

    return $this->extractData( $data, $rawData );
  }

  /**
   * Extract data from SPARQL response format.
   * The response must be in format described in:
   * https://www.w3.org/TR/sparql11-results-json/
   *
   * @param array $data SPARQL result
   * @param bool  $rawData Whether to return only values or full data objects
   *
   * @return array List of results, one row per element.
   */
  private function extractData( $data, $rawData = false ) {
    $result = [];
    if ( $data && !empty( $data['results'] ) ) {
      $vars = $data['head']['vars'];
      $resrow = [];
      foreach ( $data['results']['bindings'] as $row ) {
        foreach ( $vars as $var ) {
          if ( !isset( $row[$var] ) ) {
            $resrow[$var] = null;
            continue;
          }
          if ( $rawData ) {
            $resrow[$var] = $row[$var];
          } else {
            $resrow[$var] = $row[$var]['value'];
          }
        }
        $result[] = $resrow;
      }
    }
    return $result;
  }
}
