<?php

declare(strict_types=1);
/**
 * This file is part of config-anyway.
 *
 * @link     https://github.com/friendofhyperf/config-anyway
 * @document https://github.com/friendofhyperf/config-anyway/blob/main/README.md
 * @contact  huangdijia@gmail.com
 * @license  https://github.com/friendofhyperf/config-anyway/blob/main/LICENSE
 */
namespace FriendsOfHyperf\ConfigAnyway\Tests;

/**
 * @internal
 * @coversNothing
 */
class ExampleTest extends TestCase
{
    public function testExample()
    {
        $url = 'http://127.0.0.1:9501';
        $content = file_get_contents($url);

        $this->assertSame(date('Y-m-d'), $content);
    }
}
