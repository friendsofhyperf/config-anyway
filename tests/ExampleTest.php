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

use Hyperf\Testing\Client;

/**
 * @internal
 * @coversNothing
 */
class ExampleTest extends TestCase
{
    /**
     * @var Client
     */
    private $client;

    public function __construct()
    {
        $this->client = make(Client::class);
    }

    public function testExample()
    {
        $response = $this->client->get('/');

        $this->assertSame(date('Y-m-d'), $response);
    }
}
