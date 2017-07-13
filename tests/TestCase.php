<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, DisableExceptionHandling, DatabaseSetup;

    public function setUp()
    {
        parent::setUp();

        $this->setupDatabase();
        $this->disableExceptionHandling();
    }

    public function from($uri)
    {
        session()->setPreviousUrl(url($uri));

        return $this;
    }
}
