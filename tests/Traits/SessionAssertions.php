<?php

namespace Tests\Traits;

trait SessionAssertions
{
    public function assertSessionContainErrors($errors)
    {
        if(is_string($errors)) {
            $errors = [$errors];
        }

        foreach ($errors as $field => $error) {
            is_int($field) ?
                $this->assertSessionContainError($error) :
                $this->assertSessionContainError($field, $error);
        }
    }

    public function assertSessionContainError($error, $message = null)
    {
        $this->assertTrue(session()->has('errors'), 'Session does not contains any error.');

        $this->assertTrue(array_has(session('errors')->toArray(), $error), "Error field '{$error}' is not present in session.");

        if(! is_null($message)){
            $this->assertEquals($message, session('errors')->first($error));
        }

    }
}
