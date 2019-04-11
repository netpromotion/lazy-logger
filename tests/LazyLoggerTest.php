<?php

namespace Netpromotion\LazyLogger\Test;

use Netpromotion\LazyLogger\LazyLogger;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class LazyLoggerTest extends TestCase
{
    public function testIsLazy()
    {
        $logger = new LazyLogger(function () {
            throw new \Exception();
        });

        $this->expectException(\Exception::class);
        $logger->debug('message');
    }

    /**
     * @dataProvider dataCallsRealMethods
     * @param string $method
     * @param array $expectedArgs
     * @param array $callArgs
     */
    public function testCallsRealMethods($method, $expectedArgs, $callArgs)
    {
        $mock = $this->getMockBuilder(LoggerInterface::class)->getMock();

        $mock->expects($this->once())->method('log')->with(...$expectedArgs);

        call_user_func_array([new LazyLogger(function () use ($mock) {
            return $mock;
        }), $method], $callArgs);
    }

    public function dataCallsRealMethods()
    {
        return array_map(function ($method) {
            $expectedArgs = $callArgs = [
                'level',
                'message',
                [
                    'context' => 'context'
                ],
            ];
            if ('log' !== $method) {
                $expectedArgs[0] = $method;
                array_shift($callArgs);
            }
            return [$method, $expectedArgs, $callArgs];
        }, get_class_methods(LoggerInterface::class));
    }
}
