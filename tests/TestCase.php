<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, DisableExceptionHandling, DatabaseSetup;

    protected function setUp()
    {
        parent::setUp();

        $this->setupDatabase();
        $this->disableExceptionHandling();
    }
}
