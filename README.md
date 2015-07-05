Wikidata
========

Wikidata provides a API for searching and retrieving data from [wikidata.org](https://www.wikidata.org).

### Installation
```sh
composer require freearhey/wikidata
```

### Usage

```php
$wikidata = new Wikidata;
```

#### Search

Search by entity title:
```php
$result = $wikidata->search('steve jobs');
```

Check if no search results
```php
if($result->isEmpty()) {
	echo 'no results';
	die();
}
```

Retrieve first entity in result list
```php
$singleResult = $result->first();
```

Retrieve all results
```php
$allResults = $result->get();
```

Get entity id
```php
$entityId = $singleResult->getEntityId(); // Q26
```

#### Entities

Get single entity by id:
```php
$entities = $wikidata->entities('Q26');
```

Get single entity with preset language (default: en)
```php
$entities = $wikidata->entities('Q26', 'fr');
```

Get few entities by id and more languages
```php
$entities = $wikidata->entities('Q26|Q106', 'en|fr|ch');
```

Retrieve first entity
```php
$entity = $entities->first();
```

Get all entities
```php
$entity = $entities->get();
```

Get single entity by id
```php
$entity = $entities->get('Q26');
```

Get entity label and description (default language: en)
```php
$label = $entity->getLabel(); // Steve Jobs
$description = $entity->getDescription('de'); // US-amerikanischer Unternehmer, Mitbegründer von Apple Computer
```

Get entity property values by property id (e.g. P21) if it exists. All list properties you can find [here](https://www.wikidata.org/wiki/Wikidata:List_of_properties) 
```php
$gender = $entity->getPropertyValues('P21'); // array(1) { [0]=> string(4) "male" }
```

And with language property (default: en)
```php
$childs = $entity->getPropertyValues('P40', 'ru'); // array(1) { [0]=> string(35) "Бреннан-Джобс, Лиза" }
```

That's all.

### License
Wikidata is licensed under the [MIT license](https://github.com/freearhey/wikidata/blob/master/LICENSE)