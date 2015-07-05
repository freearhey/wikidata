<?php namespace Wikidata;

use Wikidata\Exception\HttpRequestException;
use Wikidata\Exception\InvalidArgumentException;
use Wikidata\Response\SearchResponse;
use Wikidata\Response\EntityResponse;
use Wikidata\Response\PropertyResponse;

class Wikidata {
	
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