# LiteGrabber

LiteGrabber is a simple website content scrapper that utilizing the default 
PHP DOMXPath class.

## Installation
You can install LiteGrabber using [Composer](https://getcomposer.org/download/).

`composer require aprillins/litegrabber:dev-master`

Then, update your package.

`composer update`

Don't forget to execute `composer dumpautoload` after the installation.

## Usage

Using LiteGrabber is tremendously easy. Scrapping can be done with three simple
step. First, create the LiteGrabber instance.

```php
$liteGrabber = new LiteGrabber($url);
```

Second, create the query for which element you want to scrap. For example, if 
you want to get a link from `a` tag inside `div` tag the query will be like 
this.

```php
$query = $liteGrabber->div([], true)->a()->atSrc()->getQuery();
```

OR Since 1.2 you can build the query simpler than before. The way it works is like this.

```php
$query = $liteGrabber->div()->a()->atSrc()->getQuery();
```

Third, let's get the result!

```php
$liteGrabber->getResult();
```

The result will be returned in a form of array. The result will be an empty 
array if your query compositions don't match with the actual element on a web 
page you want to scrap.

## Query Explanation
On the second step above, you see that `div([], true)` have to parameters. The 
first one is specification of tag attribute. If you want to scrap specifically
from `div` which has certain class attribute with certain value. You have to
set the array.

```php
div(['class' => 'post-wrapper home'], true)
```

Example above will set the query to `<div class="post-wrapper home">`. You MUST
NOT forget to put second argument to `true` for the first query. Whoops don't
worry since version 1.2 you MAY forget to put arguments for the first query.
The default is set to empty array for first argument and true for second argument. 

If you have done arranging the query, end it with `getQuery()` to make sure
that you reach the end of query and ready to process to the next step.

The LiteGrabber is tested with PHPUnit.