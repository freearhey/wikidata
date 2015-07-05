<?php namespace Wikidata\Search;

class SearchItemMatch {
	
	/**
	 * Class constructor
	 * @param object $match StdClass object with search item match
	 */
	public function __construct($match) {

		$this->type = $match->type;
		$this->language = $match->language;
		$this->text = $match->text;

	}

}