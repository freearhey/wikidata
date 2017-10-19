Wikidata [![Build Status](https://travis-ci.org/freearhey/wikidata.svg?branch=master)](https://travis-ci.org/freearhey/wikidata)
========

Wikidata provides a API for searching and retrieving data from [wikidata.org](https://www.wikidata.org).

### Installation
```sh
composer require freearhey/wikidata
```

### Usage

```php
$wikidata = new Wikidata();
```

#### Search

Search by entity label:
```php
$results = $wikidata->search('London');
```

Search by Wikidata property ID and string value:
```php
$results = $wikidata->searchBy('P238', 'LON');
```

Search by Wikidata property ID and entity ID:
```php
$results = $wikidata->searchBy('P17', 'Q146');
```

Check if no search results
```php
if($results->isEmpty()) {
  echo 'no results';
  die();
}
```

#### Result

Retrieve first result data
```php
$singleResult = $results->first();
```

Get result ID
```php
$resultId = $singleResult->id; // Q84
```

Get result label
```php
$resultLabel = $singleResult->label; // London
```

Get result aliases
```php
$resultAliases = $singleResult->aliases; // [ 'London, UK', 'London, United Kingdom', 'London, England' ]
```

Get result description
```php
$resultDescription = $singleResult->description; // capital of England and the United Kingdom
```

#### Entity

Get single entity by ID:
```php
$entity = $wikidata->get('Q26');
```

Get list of all available properties for specific entity
```php
$properties = $entity->properties; // array(1) { [0]=> string(11) "instance_of", ... }
```

Get value specific property
```php
$official_language = $entity->get('official_language'); // array(1) { [0]=> string(7) "English" }
```

That's all.

### License
Wikidata is licensed under the [MIT license](http://opensource.org/licenses/MIT).