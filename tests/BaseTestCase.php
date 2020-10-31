<?php

namespace Tests;

class BaseTestCase extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate:refresh --seed');
    }
}