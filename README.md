# Diff tool for Laravel 5

This is **Laravel 5** package for comparison strings and show changes.

## Table of content

* [Features](#features)
* [Installation](#installation)
* [Configuration](#configuration)
* [Usage](#usage)

---
[Back to top](#diff-tool-for-laravel-5)

## Features

* compare **strings**
* compare **files**
* group string differences into **hunk groups**

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
// Compare string line by line
$diff = Diff::compare("hello\na", "hello\nasd\na");
// Outputs span, ins, del HTML tags, depend if entry
// is unmodified, inserted or deleted
echo $diff->toHTML();
```

Compare two file:

```php
// Compare files line by line
$diff = Diff::compareFiles("a.txt", "b.txt");
echo $diff->toHTML();
```

You can customize output by getting raw data:

```php

$options = [
    // Compare by line or by characters
    'compareCharacters' => false,
    // Offset size in hunk groups
    'offset'            => 2,
];

$diff = Diff::compare("hello\na", "hello\nasd\na", $options);
$groups = $diff->getGroups();

foreach($groups as $i => $group)
{
    // Output: Hunk 1 : Lines 2 - 6
    echo 'Hunk ' . $i . ' : Lines ' 
         . $group->getFirstPosition() . ' - ' . $group->getLastPosition(); 
    
    // Output changed lines (entries)
    foreach($group->getEntries() as $entry)
    {
        // Output old position of line
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
