<?php

use Psr\Log\LoggerInterface;

class ExampleLogger implements LoggerInterface
{
    public function emergency( $message, array $context = [] )
    {
    }

    public function alert( $message, array $context = [] )
    {
    }

    public function critical( $message, array $context = [] )
    {
    }

    public function error( $message, array $context = [] )
    {
    }

    public function warning( $message, array $context = [] )
    {
    }

    public function notice( $message, array $context = [] )
    {
    }

    public function info( $message, array $context = [] )
    {
    }

    public function debug( $message, array $context = [] )
    {
        echo 'DEBUG:', $message, PHP_EOL;
        print_r( $context );

        echo PHP_EOL, PHP_EOL, PHP_EOL;
    }

    public function log( $level, $message, array $context = [] )
    {
    }
}
