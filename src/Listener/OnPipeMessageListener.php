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
namespace FriendsOfHyperf\ConfigAnyway\Listener;

use FriendsOfHyperf\ConfigAnyway\PipeMessage;
use Hyperf\Contract\ConfigInterface;
use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\Event\Contract\ListenerInterface;
use Hyperf\Framework\Event\OnPipeMessage;
use Hyperf\Process\Event\PipeMessage as UserProcessPipMessage;
use Psr\Container\ContainerInterface;

class OnPipeMessageListener implements ListenerInterface
{
    /**
     * @var ConfigInterface
     */
    private $config;

    /**
     * @var StdoutLoggerInterface
     */
    private $logger;

    public function __construct(ContainerInterface $container)
    {
        $this->config = $container->get(ConfigInterface::class);
        $this->logger = $container->get(StdoutLoggerInterface::class);
    }

    /**
     * @return string[] returns the events that you want to listen
     */
    public function listen(): array
    {
        return [
            OnPipeMessage::class,
            UserProcessPipMessage::class,
        ];
    }

    /**
     * Handle the Event when the event is triggered, all listeners will
     * complete before the event is returned to the EventDispatcher.
     */
    public function process(object $event)
    {
        if (! $this->config->get('config_anyway.enable', false)) {
            return;
        }

        if (property_exists($event, 'data') && $event->data instanceof PipeMessage) {
            /** @var PipeMessage $data */
            $data               = $event->data;
            $configurations     = $data->configurations;
            $mapping            = $this->config->get('config_anyway.mapping');

            if (is_string($mapping)) {
                $this->config->set($mapping, $configurations);
                $this->logger->debug(sprintf('Config [%s] is updated', $mapping));
            } elseif (is_array($mapping)) {
                foreach ($mapping as $source => $key) {
                    $this->config->set((string) $key, data_get($configurations, $source));
                    $this->logger->debug(sprintf('Config [%s] is updated', $key));
                }
            }
        }
    }
}
