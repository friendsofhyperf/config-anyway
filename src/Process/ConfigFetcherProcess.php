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
namespace FriendsOfHyperf\ConfigAnyway\Process;

use FriendsOfHyperf\ConfigAnyway\PipeMessage;
use FriendsOfHyperf\ConfigAnyway\SourceInterface;
use Hyperf\Contract\ConfigInterface;
use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\Process\AbstractProcess;
use Hyperf\Process\ProcessCollector;
use Psr\Container\ContainerInterface;
use Swoole\Server;

class ConfigFetcherProcess extends AbstractProcess
{
    public $name = 'config-any-fetcher';

    /**
     * @var Server
     */
    private $server;

    /**
     * @var SourceInterface
     */
    private $source;

    /**
     * @var ConfigInterface
     */
    private $config;

    /**
     * @var array
     */
    private $cacheConfig;

    /**
     * @var StdoutLoggerInterface
     */
    private $logger;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);

        $this->config = $container->get(ConfigInterface::class);
        $this->logger = $container->get(StdoutLoggerInterface::class);

        if ($this->config->get('config_anyway.source') && class_exists($this->config->get('config_anyway.source'))) {
            $this->source = make($this->config->get('config_anyway.source'));
        }
    }

    public function bind($server): void
    {
        $this->server = $server;
        parent::bind($server);
    }

    public function isEnable($server): bool
    {
        return $server instanceof Server
            && $this->config->get('config_anyway.enable', false)
            && $this->config->get('config_anyway.use_standalone_process', true)
            && in_array(SourceInterface::class, class_implements($this->config->get('config_anyway.source')));
    }

    public function handle(): void
    {
        while (true) {
            $config = $this->source->toArray();

            if ($config !== $this->cacheConfig) {
                $this->cacheConfig = $config;

                $workerCount = $this->server->setting['worker_num'] + $this->server->setting['task_worker_num'] - 1;
                $pipeMessage = new PipeMessage($this->format($config));

                for ($workerId = 0; $workerId <= $workerCount; ++$workerId) {
                    $this->server->sendMessage($pipeMessage, $workerId);
                }

                $string    = serialize($pipeMessage);
                $processes = ProcessCollector::all();

                /** @var \Swoole\Process $process */
                foreach ($processes as $process) {
                    $result = $process->exportSocket()->send($string, 10);

                    if ($result === false) {
                        $this->logger->error('Configuration synchronization failed. Please restart the server.');
                    }
                }
            }

            sleep((int) $this->config->get('config_anyway.interval', 5));
        }
    }

    /**
     * Format kv configurations.
     */
    protected function format(array $config): array
    {
        return $config;
    }
}
