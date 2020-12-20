[![wikidata](https://raw.githubusercontent.com/maxlath/wikidata-cli/master/assets/wikidata_logo_alone.jpg)](https://wikidata.org)

# Wikidata [![Build Status](https://travis-ci.org/freearhey/wikidata.svg?branch=master)](https://travis-ci.org/freearhey/wikidata)

Wikidata provides a API for searching and retrieving data from [wikidata.org](https://www.wikidata.org).

## Installation

```sh
composer require freearhey/wikidata
```

## Usage

First we need to create an instance of `Wikidata` class and save it to some variable, like this:

```php
require_once('vendor/autoload.php');

use Wikidata\Wikidata;

$wikidata = new Wikidata();
```

after that we can use one of the available methods to access the Wikidata database.

## Available Methods

### `search()`

The `search()` method give you a way to find Wikidata entity by it label.

```php
$results = $wikidata->search($query, $lang, $limit);
```

Arguments:

- `$query`: term to search (required)
- `$lang`: specify the results language (default: 'en')
- `$limit`: set a custom limit (default: 10)

Example:

```php
$results = $wikidata->search('car', 'fr', 5);

/*
  Collection {
    #items: array:5 [
      0 => SearchResult {
        id: "Q1759802"
        lang: "fr"
        label: "autocar"
        wiki_url: "https://fr.wikipedia.org/wiki/autocar"
        description: "transport routier pouvant accueillir plusieurs voyageurs pour de longues distances"
        aliases: array:1 [
          0 => "car"
        ]
      }
      1 => SearchResult {
        id: "Q224743"
        lang: "fr"
        label: "Car"
        wiki_url: "https://fr.wikipedia.org/wiki/Car"
        description: "page d'homonymie d'un projet Wikimédia"
        aliases: []
      }
      ...
    ]
  }
*/
```

The `search()` method always returns `Illuminate\Support\Collection` class with results. This means you can use all the [methods available](https://laravel.com/docs/5.6/collections#available-methods) in Laravel's Collections.

### `searchBy()`

The `searchBy` help you to find Wikidata entities by it properties value.

```php
$results = $wikidata->searchBy($propId, $entityId, $lang, $limit);
```

Arguments:

- `$propId`: id of the property by which to search (required)
- `$entityId`: id of the entity (required)
- `$lang`: specify the results language (default: 'en')
- `$limit`: set a custom limit (default: 10)

Example:

```php
// List of people who born in city Pomona, US
$results = $wikidata->searchBy('P19', 'Q486868');

/*
  Collection {
    #items: array:10 [
      0 => SearchResult {
        id: "Q92638"
        lang: "en"
        label: "Robert Tarjan"
        wiki_url: "https://en.wikipedia.org/wiki/Robert_Tarjan"
        description: "American computer scientist"
        aliases: array:2 [
          0 => "Robert E. Tarjan"
          1 => "Robert Endre Tarjan"
        ]
      }
      1 => SearchResult {
        id: "Q184805"
        lang: "en"
        label: "Tom Waits"
        wiki_url: "https://en.wikipedia.org/wiki/Tom_Waits"
        description: "American singer-songwriter and actor"
        aliases: []
      }
      ...
    ]
  }
*/
```

The `searchBy()` method always returns `Illuminate\Support\Collection` class with results. This means you can use all the [methods available](https://laravel.com/docs/5.6/collections#available-methods) in Laravel's Collections.

### `get()`

The `get()` returns Wikidata entity by specified ID.

```php
$entity = $wikidata->get($entityId, $lang);
```

Arguments:

- `$entityId`: id of the entity (required)
- `$lang`: specify the results language (default: 'en')

Example:

```php
// Get all data about Steve Jobs
$entity = $wikidata->get('Q19837');

/*
  Entity {
    id: "Q19837"
    lang: "en"
    label: "Steve Jobs"
    wiki_url: "https://en.wikipedia.org/wiki/Steve_Jobs"
    aliases: array:2 [
      0 => "Steven Jobs",
      1 => "Steven Paul Jobs"
    ]
    description: "American entrepreneur and co-founder of Apple Inc."
    properties: Collection { ... }
  }
*/


// List of all properties as an array
$properties = $entity->properties->toArray();

/*
  [
    "P1006" => Property {
      id: "P1006"
      label: "NTA ID"
      values: Collection {
        #items: array:6 [
          0 => Value {
            id: "Q5593916"
            label: "Grammy Trustees Award"
            qualifiers: Collection {
              #items: array:1 [
                0 => Qualifier {
                  id: "P585"
                  label: "point in time"
                  value: "2012-01-01T00:00:00Z"
                }
              ]
            }
          },
          ...
        ]
      }
    },
    ...
  ]
 */
```

### Upgrade Guide

#### Upgrade to 3.2._ from 3.1._

The main changes in the new version have occurred in the way property values are stored. Now each property can have more than one value. In this regard, the attribute `value` in the `Property` object was replaced with the attribute `values`.

Also, values are now presented not as a string, but as an `Value` object. This allowed us to save along with each value not only its string representation but also a list of its `qualifiers`. Detailed information on what `qualifiers` is can be found [here](https://www.wikidata.org/wiki/Help:Qualifiers).

### Testing

```sh
vendor/bin/phpunit
```

### Contribution

If you find a bug or want to contribute to the code or documentation, you can help by submitting an [issue](https://github.com/freearhey/wikidata/issues) or a [pull request](https://github.com/freearhey/wikidata/pulls).

### License

Wikidata is licensed under the [MIT license](http://opensource.org/licenses/MIT).
