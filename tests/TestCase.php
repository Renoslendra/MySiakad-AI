<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

/**
 * @property \App\Models\User $admin
 */
abstract class TestCase extends BaseTestCase
{
    public $admin;
}
