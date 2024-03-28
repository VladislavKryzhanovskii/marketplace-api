<?php

namespace App\Tests\Util;

use Faker\Factory;
use Faker\Generator;

trait FakerProvider
{
    private function getFaker(): Generator
    {
        return Factory::create();
    }
}