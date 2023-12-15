<?php

namespace Tests\Mother;

use Tests\Container;
use Tests\Doubles\ThrowingConfigurationProvider;

final readonly class ContainerMother
{
    public static function basic(): Container
    {
        return new Container();
    }

    public static function withThrowingConfigurationProvider(): Container
    {
        return new Container(new ThrowingConfigurationProvider());
    }
}