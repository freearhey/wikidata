<?php

namespace Wikidata\Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
  protected $dummy = [
    [
      "type" => "item",
      "id" => "Q197",
      "labels" => [
        "es" => [
          "language" => "es",
          "value" => "avión",
        ],
      ],
      "descriptions" => [
        "es" => [
          "language" => "es",
          "value" => "estructura, máquina o vehículo designado para ser soportado por el aire, sea por la acción dinámica del aire sobre las superficies de la estructura u objeto o por su propia flotabilidad",
        ],
      ],
      "aliases" => [
        "es" => [
          [
            "language" => "es",
            "value" => "aeroplano",
          ],
          [
            "language" => "es",
            "value" => "avion",
          ],
        ],
      ],
      "sitelinks" => [
        "eswiki" => [
          "site" => "eswiki",
          "title" => "Avión",
          "badges" => [],
          "url" => "https://es.wikipedia.org/wiki/Avi%C3%B3n",
        ],
      ],
    ],
    [
      "type" => "item",
      "id" => "Q89433348",
      "labels" => [
        "en" => [
          "language" => "en",
          "value" => "Anna Nightingale",
        ],
      ],
      "descriptions" => [
        "en" => [
          "language" => "en",
          "value" => "British actress",
        ],
      ],
      "aliases" => [],
      "sitelinks" => [
        "enwiki" => [
          "site" => "enwiki",
          "title" => "Anna Nightingale",
          "badges" => [],
          "url" => "https://en.wikipedia.org/wiki/Anna_Nightingale",
        ],
      ],
    ],
  ];

  protected $dummyProperties = [
    [
      "item" => "http://www.wikidata.org/entity/Q49546",
      "prop" => "http://www.wikidata.org/prop/P4952",
      "propertyLabel" => "safety classification and labelling",
      "statement" => "http://www.wikidata.org/entity/statement/Q49546-68675f20-4644-b8be-6280-f8cec5399f36",
      "propertyValue" => "http://www.wikidata.org/entity/Q2005334",
      "propertyValueLabel" => "Regulation (EC) No. 1272/2008",
      "qualifier" => "http://www.wikidata.org/entity/P5041",
      "qualifierLabel" => "GHS hazard statement",
      "qualifierValue" => "http://www.wikidata.org/entity/Q51844420",
      "qualifierValueLabel" => "H336",
    ],
    [
      "item" => "http://www.wikidata.org/entity/Q49546",
      "prop" => "http://www.wikidata.org/prop/P4952",
      "propertyLabel" => "safety classification and labelling",
      "statement" => "http://www.wikidata.org/entity/statement/Q49546-68675f20-4644-b8be-6280-f8cec5399f36",
      "propertyValue" => "http://www.wikidata.org/entity/Q2005334",
      "propertyValueLabel" => "Regulation (EC) No. 1272/2008",
      "qualifier" => "http://www.wikidata.org/entity/P5041",
      "qualifierLabel" => "GHS hazard statement",
      "qualifierValue" => "http://www.wikidata.org/entity/Q54876503",
      "qualifierValueLabel" => "EUH066",
    ],
  ];
}
