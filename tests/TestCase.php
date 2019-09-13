<?php 

namespace Wikidata\Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
  protected $dummy = [
    [
        "item" => "http://www.wikidata.org/entity/Q49546",
        "itemLabel" => "acetone",
        "itemDescription" => "chemical compound",
        "itemAltLabel" => "2-propanone, beta-ketopropane, dimethyl ketone, dimethylketone, ketone propane, methyl ketone, propanone",
        "prop" => "http://www.wikidata.org/prop/P4952",
        "propertyLabel" => "safety classification and labelling",
        "statement" => "http://www.wikidata.org/entity/statement/Q49546-68675f20-4644-b8be-6280-f8cec5399f36",
        "propertyValue" => "http://www.wikidata.org/entity/Q2005334",
        "propertyValueLabel" => "Regulation (EC) No. 1272/2008",
        "qualifier" => "http://www.wikidata.org/entity/P5041",
        "qualifierLabel" => "GHS hazard statement",
        "qualifierValue" => "http://www.wikidata.org/entity/Q51844420",
        "qualifierValueLabel" => "H336"
    ],
    [
        "item" => "http://www.wikidata.org/entity/Q49546",
        "itemLabel" => "acetone",
        "itemDescription" => "chemical compound",
        "itemAltLabel" => "2-propanone, beta-ketopropane, dimethyl ketone, dimethylketone, ketone propane, methyl ketone, propanone",
        "prop" => "http://www.wikidata.org/prop/P4952",
        "propertyLabel" => "safety classification and labelling",
        "statement" => "http://www.wikidata.org/entity/statement/Q49546-68675f20-4644-b8be-6280-f8cec5399f36",
        "propertyValue" => "http://www.wikidata.org/entity/Q2005334",
        "propertyValueLabel" => "Regulation (EC) No. 1272/2008",
        "qualifier" => "http://www.wikidata.org/entity/P5041",
        "qualifierLabel" => "GHS hazard statement",
        "qualifierValue" => "http://www.wikidata.org/entity/Q54876503",
        "qualifierValueLabel" => "EUH066"
    ]
  ];
}
