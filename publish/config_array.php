<?php

declare(strict_types=1);
/**
 * This file is part of hyperf-config-array.
 *
 * @link     https://github.com/friendofhyperf/config-anyway
 * @document https://github.com/friendofhyperf/config-anyway/blob/main/README.md
 * @contact  huangdijia@gmail.com
 * @license  https://github.com/friendofhyperf/config-anyway/blob/main/LICENSE
 */
return [
    'enable'                 => env('CONFIG_ARRAY_ENABLE', true),
    'interval'               => env('CONFIG_ARRAY_INTERVAL', 5),
    'use_standalone_process' => env('CONFIG_ARRAY_USE_STANDALONE_PROCESS', true),
    'source'                 => \FriendsOfHyperf\ConfigAnyway\Source\DemoSource::class,
    'mapping'                => [
        // source => target
        'bar.foo' => 'bar.foo',
    ],
];
