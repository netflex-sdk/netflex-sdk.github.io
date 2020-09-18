# Models

Models is an abstraction of Structures in Netflexapp. It provides an ORM-like way of working with structured data.

It is implemented with an API that is mostly compatible with [Laravels Eloquent ORM](https://laravel.com/docs/7.x/eloquent).

> [!NOTE]
> In its default state, a model will never retrieve unpublished entries when performing a query or search.<br>
> This can be configured per model.

## Working with models

> [!TIP]
> Since Structure models implements most of **Eloquents** API, you can use **Laravels** documentation as a reference if something isn't documentet here.<br>
> This document only outlines the basics, and Netflex specific quirks and features that don't apply to regular Eloquent models.

### Retrieving entries and performing queries

For the following examples, lets assume that we have defined a model named `App\Article`.

**Fetching a single entry**
```php
<?php

use App\Article;

// If not found, it returns NULL
Article::find(10000); // 10000 refers to the entry ID
```

**Attempt to fetch a single entry or fail**

> [!NOTE]
> If a ModelNotFoundException is left uncaught, Laravel automatically converts it to a 404 response

```php
<?php

use App\Article;

// If not found, throw a ModelNotFoundException
Article::findOrFail(10000);
```

**Fetching a random entry**
```php
<?php

use App\Article;

$randomArticle = Article::random();
```

**Fetching multiple random entries**
```php
<?php

use App\Article;

$randomArticles = Article::random(10); // Fetches up to 10 random articles
```

**Performing a query**

```php
<?php

use App\Article;

// Returns a \Illuminate\Support\Collection with \App\Article
$articles = Article::where('category', '=', 'news')
  ->orWhere('category', '=', 'archived')
  ->where('author', '=', 'Ola Nordmann')
  ->where('excludeFromSearch', '!=', true)
  ->get();
```

> [!TIP]
> An invalid query will throw a QueryException. This could also happen if ElasticSearch is misconfigured. (See [FAQ](/docs/faq.md?id=queryexception-invalid-query-when-using-models))

## Defining models

Models are usually located in the `app/` directory of your project. You are however free to put them wherever you want, just remember that you need to update the namespaces accordingly.

> [!WARNING]
> The default `App\User` model is special, as it integrates with the authentication system. You can move it, but make sure to configure your apps [authentication system](/docs/authentication.md) accordingly.

### Automatically

You can use the [Artisan cli](/docs/cli.md) to automatically generate a Model class for your Structure.

```
php artisan make:model Article
```

### Manually

To manually create a model, you simply extend the `Netflex\Structure\Model` class, and provide it with your structures ID. A structures ID is equivalent to a database table name in a traditional database backend.

```php
<?php

use Netflex\Structure\Model;

class Article extends Model
{
  protected $relationId = 10000; // The Structure ID that this Model belongs to
}
```

## Configuring models

The model has a lot of settings that can be modified by changing the approriate variable.

### Structure ID

This variable determines which Structure that this model is associated with

```php
/**
 * The directory_id associated with the model.
 *
 * @var int
 */
protected $relationId;
```

### Model resolving

This variable determines which field in the Structure that should be used for lookups when resolving a model, either through the `Model::resolve` method, or when using [Route Model Binding](https://laravel.com/docs/5.0/routing#route-model-binding)

```php
/**
 * The resolvable field associated with the model.
 *
 * @var string
 */
protected $resolvableField = 'url';
```

### Auto publishing

This determines if a model should automatically get published when performing a save.

```php
/**
 * Indicates if we should automatically publish the model on save.
 *
 * @var bool
 */
protected $autoPublishes = true;
```

### Publishing status

This determines if the models publishing status should be taken into consideration when performing queries. The default is that unpublished entries are excluded.

```php
/**
 * Indicates if we should respect the models publishing status when retrieving it.
 *
 * @var bool
 */
protected $respectPublishingStatus = true;
```

### Chunking

When performing a `Model::all()` query, or when paginating a Model, sometimes that Structure can have large quantities of data. To prevent a timeout, these queries are chuncked using a [LazyCollection](https://laravel.com/docs/7.x/collections#lazy-collections).

This variable determines the default page size that is used when performing the results are chunked.

```php
/**
 * The number of models to return for pagination. Also determines chunk size for LazyCollection
 *
 * @var int
 */
protected $perPage = 100;
```

### Hiding default fields

A standard Structure have a lot of default fields that is not part of its configuration. Some are legacy fields from the legacy Netflex SDK v1.3, others are internal data that we don't usually need to expose when building a typical aplication.

This setting determines if we should hide thise default fields.

```php
/**
 * Indicates if the model should hide default fields
 *
 * @var bool
 */
protected $hidesDefaultFields = true;
```

### Defining default fields

This setting allows you to override what is considered "default" fields.

```php
/**
 * Indicates which fields are considered default fields
 *
 * @var string[]
 */
protected $defaultFields = [
  'directory_id',
  'title',
  'revision',
  'published',
  'userid',
  'use_time',
  'start',
  'stop',
  'tags',
  'public',
  'authgroups',
  'variants',
];
```

### Casting date fields to Date objects

This settings determines what fields in the Model should be cast to Date objects

```php
/**
 * The attributes that should be mutated to dates.
 *
 * @var array
 */
protected $dates = [
  'created',
  'updated',
  'start',
  'stop'
];
```

### Hiding fields when JSON encoding

This setting determines which fields should be hidden when outputting the model when it gets encoded to JSON.

```php
/**
 * The attributes that should be hidden for arrays.
 *
 * @var array
 */
protected $hidden = [];
```

## Registering models

## Defining relationships

Netflex models does not support the traditional Eloquent relationships, as Structures are not a relational database.

You can however model relationships through [accessor methods](https://laravel.com/docs/7.x/eloquent-mutators#defining-an-accessor)

> [!WARNING]
> Since this method of implementing relationships can be "expensive" in terms of API roundtrips, you should use them with moderation.<br>
> Consider hiding fields if not needed in your JSON output when using relationships.

**Example**

```php
<?php

namespace App;

use Netflex\Structure\Model;

use App\Category;

/**
 * @property Category|null $category
 */
class Article extends Model
{
  /**
   * @return Category|null
   */
  public function getCategoryAttribute ($categoryId)
  {
    return Category::find($categoryId);
  }
}
```

You may then use the defined accessor like it was a normal member of the Model.
The accessor will get invoked, and if the Model has a field named the same as the accessor method, that field value is automatically passed in as an argument to the method.

```php
$article = App\Article::find(10000);
echo $article->category->name;
```

