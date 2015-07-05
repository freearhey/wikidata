<?php namespace Wikidata\Search;

class SearchItem {
	
	private $id,
			$url,
			$label,
			$description,
			$match,
			$aliases;

	/**
	 * Class constructor
	 * @param object $item StdClass object with item
	 */
	public function __construct($item) {

		$this->id = $item->id;
		$this->url = $item->url;
		$this->label = $item->label;
		$this->description = $item->description;
		$this->match = new SearchItemMatch($item->match);
		$this->aliases = $item->aliases;

	}

	/**
	 * Get only entity id
	 * @return string
	 */
	public function getEntityId() {

		return $this->id;

	}

}