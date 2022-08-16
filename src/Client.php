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

use Hyperf\Contract\ConfigInterface;
use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\Utils\Parallel;
use Throwable;

class Client implements ClientInterface
{
    public function __construct(protected ConfigInterface $config, protected StdoutLoggerInterface $logger)
    {
    }

    public function pull(): array
    {
        $mapping = $this->config->get('config_center.drivers.anyway.mapping', []);
        $parallel = new Parallel();

        foreach ($mapping as $key => $callback) {
            $parallel->add(function () use ($key, $callback) {
                try {
                    return call($callback);
                } catch (Throwable $e) {
                    $this->logger->error(sprintf(
                        "Config[%s] fetch failed, %s %s:%s\n%s",
                        $key,
                        $e->getMessage(),
                        $e->getFile(),
                        $e->getLine(),
                        $e->getTraceAsString()
                    ));
                    return null;
                }
            }, $key);
        }

        return $parallel->wait();
    }
}
