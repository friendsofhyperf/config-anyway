# Hyperf config-array

[![Latest Test](https://github.com/friendsofhyperf/config-anyway/workflows/tests/badge.svg)](https://github.com/friendsofhyperf/config-anyway/actions)
[![Latest Stable Version](https://poser.pugx.org/friendsofhyperf/config-anyway/version.png)](https://packagist.org/packages/friendsofhyperf/config-anyway)
[![Total Downloads](https://poser.pugx.org/friendsofhyperf/config-anyway/d/total.png)](https://packagist.org/packages/friendsofhyperf/config-anyway)
[![GitHub license](https://img.shields.io/github/license/friendsofhyperf/config-anyway)](https://github.com/friendsofhyperf/config-anyway)

## Installation

~~~base
composer require friendsofhyperf/config-anyway
~~~

## Publish

~~~bash
php bin/hyperf.php vendor:publish friendsofhyperf/config-anyway
~~~

## Configure

~~~php
// config/autoload/config_center.php
return [
    'drivers' => [
        'driver' => FriendsOfHyperf\ConfigAnyway\AnywayDriver::class,
        'mapping' => [
            'key1' => function() { return []; },
            'key2' => App\Source\ArrayHandler::class, // need __invoke()
            'key3' => [App\Source\ArrayHandler::class, '__invoke'],
        ],
    ]
];
~~~
