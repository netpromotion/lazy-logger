<?php

namespace Netpromotion\LazyLogger;

use Psr\Log\AbstractLogger;
use Psr\Log\LoggerInterface;

class LazyLogger extends AbstractLogger
{
    /**
     * @var callable
     */
    private $factory;

    /**
     * @var LoggerInterface
     */
    private $realLogger;

    public function __construct(callable $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @return LoggerInterface
     */
    private function getRealLogger()
    {
        if ( ! $this->realLogger) {
            $this->realLogger = call_user_func($this->factory);
        }

        return $this->realLogger;
    }

    /**
     * @inheritdoc
     */
    public function log($level, $message, array $context = [])
    {
        $this->getRealLogger()->log(...func_get_args());
    }
}
