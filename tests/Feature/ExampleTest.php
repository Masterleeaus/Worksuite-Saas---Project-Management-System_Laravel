<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class ExampleTest extends TestCase
{

    public function testBasicTest()
    {
        $this->assertTrue(Route::has('login'));
    }

}
