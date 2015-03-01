# Diff tool for Laravel 5

This is **Laravel 5** package for comparison strings and show changes.

## Table of content

* [Features](#features)
* [Installation](#installation)
* [Configuration](#configuration)
* [Usage](#usage)

---
[Back to top](#diff-tool-for-laravel-5)

## Installation

Via `composer`:

```bash
composer require vi-kon/laravel-diff
```

---
[Back to top](#diff-tool-for-laravel-5)

## Usage

Simple usage:

```php
$diff = Diff::compare("hello\na", "hello\nasd\na");
echo $diff->toHTML();
```

You can customize output by getting raw data:

```php

$options = [
    // Compare by line or characters
    'compareCharacters' => false,
    // Offset size in groups
    'offset'            => 2,
];

$diff = Diff::compare("hello\na", "hello\nasd\na", $options);
$groups = $diff->getGroups();

foreach($groups as $i => $group)
{
    // Output: Hunk 1 : 2 - 6
    echo 'Hunk ' . $i . ' : Lines ' . $group->getFirstPosition() . ' - ' . $group->getLastPosition(); 
    
    // Output changed lines (entries)
    foreach($group->getEntries() as $entry)
    {
        // Output old positon of line
        echo $entry instanceof \ViKon\Diff\Entry\InsertedEntry 
            ? '-'
            : $entry->getOldPosition() + 1;

        echo ' | ';

        // Output new position of line
        echo $entry instanceof \ViKon\Diff\Entry\DeletedEntry 
            ? '-'
            : $entry->getNewPosition() + 1;
        
        echo ' - ';        

        // Output line (entry)
        echo $entry;
    }
}

```

---
[Back to top](#diff-tool-for-laravel-5)

## License

This package is licensed under the MIT License

---
[Back to top](#diff-tool-for-laravel-5)
