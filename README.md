# Activity

A simple activity feed handler using Predis which stores/pushes JSON objects to friends/followers.
Using this for personal projects not ment to be used for production stuff.

## Fetch

The recommended way to install Backlight is [through composer](http://packagist.org).

Just create a composer.json file for your project:

```JSON
{
    "minimum-stability" : "dev",
    "require": {
        "tyler-king/activity": "dev-master"
    }
}
```

And run these two commands to install it:

    $ curl -s http://getcomposer.org/installer | php
    $ php composer.phar install

Now you can add the autoloader, and you will have access to the library:

```php
<?php
require 'vendor/autoload.php';
```

## Requirements

- [Redis Server](http://redis.io/) (RDB or AOF)
- [PHP](http://php.net) 5.4.x
- [Predis](https://github.com/nrk/predis) Library

## Usage

See `examples/` for code usage.