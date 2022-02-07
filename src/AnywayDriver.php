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
namespace FriendsOfHyperf\ConfigAnyway;

use Hyperf\ConfigCenter\AbstractDriver;
use Psr\Container\ContainerInterface;

class AnywayDriver extends AbstractDriver
{
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        $this->client = $container->get(ClientInterface::class);
    }

    protected function updateConfig(array $config): void
    {
        foreach ($config as $key => $value) {
            $this->config->set($key, $value);
            $this->logger->debug(sprintf('Config [%s] is updated', $key));
        }
    }
}
