<?php namespace Wikidata;

use Wikidata\Exception\HttpRequestException;
use Wikidata\Exception\InvalidArgumentException;
use Wikidata\Response\SearchResponse;
use Wikidata\Response\EntityResponse;
use Wikidata\Response\PropertyResponse;

class Wikidata {
	
    const SPARQL_API_BASE_ENDPOINT = 'https://query.wikidata.org/sparql';
	const API_BASE_ENDPOINT = 'https://www.wikidata.org/w/api.php';

	const SEARCH_API_ACTION = 'wbsearchentities';
	const ENTITY_API_ACTION = 'wbgetentities';

    /**
     * Use wbsearchentities method to search on wikidata
     * @return  object /Response/SearchResponse
     */
	public function search($query, $params = [ 'language' => 'en', 'limit' => 1 ]) {

        if(empty($params['language']) || empty($params['limit']))
            throw new InvalidArgumentException("Second argument search() must contain language and limit properties");
            

		$url = sprintf('%s?action=%s&format=json&type=item&search=%s&%s', self::API_BASE_ENDPOINT, self::SEARCH_API_ACTION, urlencode($query), http_build_query($params));

		$response = $this->doRequest($url);

        return new SearchResponse($response);

	}

    /**
     * Use SPARQL endpoint to search on property entity
     * @param  string $propertyId       Property id (e.g. P268)
     * @param  string $entityId         Entity id (e.g. Q5)
     * @param  array  $params           Search Parameters
     * @return object /Response/SearchResponse
     */
    public function searchByPropertyEntity($propertyId, $entityId, $params = [ 'language' => 'en', 'limit' => 10, 'offset' => 0 ])
    {
        if(empty($params['language']) || empty($params['limit']))
            throw new InvalidArgumentException("Third argument searchByPropertyEntity() must contain language and limit properties");

        $queryString = 
        'SELECT ?item ?itemLabel ?itemDescription ?itemAltLabel WHERE 
        {
            ?item wdt:%s wd:%s .
            SERVICE wikibase:label 
            {
                bd:serviceParam wikibase:language "%s" . 
            }
        } 
        OFFSET %s LIMIT %s';

        $query = sprintf( $queryString, $propertyId, $entityId, $params[ 'language' ], $params[ 'offset' ], $params[ 'limit' ] );

        $url = sprintf('%s?format=json&query=%s', self::SPARQL_API_BASE_ENDPOINT, urlencode($query));

        $response = $this->doRequest($url);       

        return new SearchResponse( $this->sparqlToMediaWiki( $response, $entityId, $params[ 'language' ] ) );
    }

    /**
     * Use SPARQL endpoint to search on property value
     * @param  string $propertyId       Property id (e.g. P268)
     * @param  string $value            Value to search on 
     * @param  array  $params           Search Parameters
     * @return object /Response/SearchResponse
     */
    public function searchByPropertyValue($propertyId, $value, $params = [ 'language' => 'en', 'limit' => 10, 'offset' => 0 ])
    {
        if(empty($params['language']) || empty($params['limit']))
            throw new InvalidArgumentException("Third argument searchByPropertyValue() must contain language and limit properties");

        $queryString = 
        'SELECT ?item ?itemLabel ?itemDescription ?itemAltLabel WHERE 
        {
            ?item wdt:%s "%s" .
            SERVICE wikibase:label 
            { 
                bd:serviceParam wikibase:language "%s" . 
            }
        } 
        OFFSET %s LIMIT %s';

        $query = sprintf( $queryString, $propertyId, $value, $params[ 'language' ], $params[ 'offset' ], $params[ 'limit' ] );

        $url = sprintf('%s?format=json&query=%s', self::SPARQL_API_BASE_ENDPOINT, urlencode($query));

        $response = $this->doRequest($url);       

        return new SearchResponse( $this->sparqlToMediaWiki( $response, $value, $params[ 'language' ] ) );
    }

    /**
     * Process SPARQL api response to the same format as the MediaWiki api
     * @param  string $response       SPARQL api response
     * @param  string $searchValue    Property id (like Q43)
     * @param  string $language       
     * @return json                   MediaWiki format response
     */
    private function sparqlToMediaWiki( $response, $searchValue, $language )
    {
        $response = json_decode( $response, true );

        $processedResponse = array_map(function( $thisMatch ) use ( $searchValue, $language )
        {
            $thisMatchId = str_replace('http://www.wikidata.org/entity/', '', $thisMatch['item']['value']);

            return 
            [
                'id' => $thisMatchId,
                'concepturi' => $thisMatch['item']['value'],
                'url' => '//www.wikidata.org/wiki/' . $thisMatchId,
                'title' => $thisMatchId,
                'pageid' => null,
                'label' => $thisMatch['itemLabel']['value'],
                'description' => isset($thisMatch['itemDescription']['value']) ? $thisMatch['itemDescription']['value'] : null,
                'match' => 
                [
                    'type' => 'property',
                    'language' => $language,
                    'text' => $searchValue
                ],
                'aliases' => isset( $thisMatch['itemAltLabel'] ) ? explode( ', ', $thisMatch[ 'itemAltLabel' ][ 'value' ] ) : null
            ];  

        }, $response['results']['bindings']);

        return json_encode( [ 'searchinfo' => $searchValue, 'search' => $processedResponse, 'success' => 1 ] );
    }

    /**
     * Use wbgetentities method to get entity from wikidata
     * @return  object /Response/EntityResponse
     */
    public function entities($id, $languages = 'en') {

        $url = sprintf('%s?action=%s&format=json&ids=%s&languages=%s', self::API_BASE_ENDPOINT, self::ENTITY_API_ACTION, urlencode($id), $languages);

        $response = $this->doRequest($url);

        return new EntityResponse($response);

    }

    /**
     * Use wbgetentities method to get label of property from Wikidata
     * @param  string $id       Property id (like Q43)
     * @param  string $language Language
     * @return object          /Response/PropertyResponse
     */
    public function property($id, $language = 'en') {

        $url = sprintf('%s?action=%s&format=json&props=labels&ids=%s&languages=%s', self::API_BASE_ENDPOINT, self::ENTITY_API_ACTION, urlencode($id), $language);

        $response = $this->doRequest($url);

        return new PropertyResponse($response, $language);

    }

    /**
     * Request to wikidata
     * @param  string $url Url with all parameters
     * @return json      Wikidata json response
     */
	public function doRequest($url) {

		$data = @file_get_contents($url);
        if (!$data) {
            $error = error_get_last();
            throw new HttpRequestException($error['message']);
        }

        return $data;

	}

}