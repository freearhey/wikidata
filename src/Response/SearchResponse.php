<?php namespace Wikidata\Response;

use Wikidata\Search\SearchItem;

class SearchResponse {
	
	private $searchinfo,
			$search,
			$success;

	/**
	 * Class constructor
	 * @param json $data Wikidata json response
	 */
	public function __construct($data) {
		
		$response = json_decode($data);

		$this->searchinfo = $response->searchinfo;
		$this->search = array_map([$this, 'createSearchItem'], $response->search);
		$this->success = $response->success;
		
	}

	/**
	 * Get all search results
	 * @return array
	 */
	public function get() {

		return $this->search;

	}

	/**
	 * Get first finded item
	 * @return object /Search/SearchItem
	 */
	public function first() {

		return $this->search[0];

	}

	/**
	 * Check if no items in result
	 * @return boolean
	 */
	public function isEmpty() {

		return (count($this->search) < 1) ? true : false;

	}

	/**
	 * Creating search item object
	 * @param  object $item StdClass object with items
	 * @return object       /Search/SearchItem
	 */
	private function createSearchItem($item) {

		return new SearchItem($item);

	}


}