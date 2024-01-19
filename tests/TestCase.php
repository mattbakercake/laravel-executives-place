<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Faker\Generator;
use Faker\Factory;
use Illuminate\Foundation\Testing\DatabaseTransactions;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use DatabaseTransactions; //DB tests run in transactions and rollback after each test

    private Generator $faker;


    public function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create(); //make faker available to each test
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * create magic getter method to easily access faker in tests
     */
    public function __get($key)
    {

        if ($key === 'faker')
        return $this->faker;
        throw new \Exception('Unknown Key Requested');
    }
}
