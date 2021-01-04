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

## Define source

~~~php
namespace App\Source;

use FriendsOfHyperf\ConfigAnyway\SourceInterface;
use Hyperf\DB\DB;

class DBSource implements SourceInterface
{
    public function toArray(): array
    {
        return DB::query('SELECT * FROM `config`;');
    }
}
~~~

## Set config

~~~php
// config/autoload/config_array.php
return [
    // ...
    'source' => App\Source\DBSource::class,
    // ...
    'mapping' => 'setting', // using as config('setting')
    // or
    'mapping' => [
        'setting_key' => 'setting.key', // using as config('setting.key')
    ],
];
~~~
