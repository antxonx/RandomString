# Random String Generator fro PHP

A simple tool to create semi-random (and mostly unique for lengths > 12) strings.

It is NOT and implementation of UUID, it relies on microtime, shifting and replaces to create the final result.

## Installation

```
composer require antxonx/random-string
```

## usage example
```php
<?php

use Antxonx\RandomString\RandomString;

$random = new RandomString(32);

echo $random->gen(); /* Prints a 32 length string with 'random' characters*/

```