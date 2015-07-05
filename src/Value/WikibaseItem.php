<?php namespace Wikidata\Value;

use Wikidata\Wikidata;

class WikibaseItem {
	
	/**
	 * Class constructor
	 * @param object $value StdClass object with wikibase item
	 */
	public function __construct($value) {

		$this->{'entity-type'} = $value->{'entity-type'};
		$this->{'numeric-id'} = $value->{'numeric-id'};

	}

	/**
	 * Call wikidata api and get only label of wikibase item
	 * @return string $value
	 */
	public function getValue($lang) {

		$wikidata = new Wikidata();

		$id = sprintf('Q%s', $this->{'numeric-id'});

		return $wikidata->property($id, $lang)->getLabel();

	}

}