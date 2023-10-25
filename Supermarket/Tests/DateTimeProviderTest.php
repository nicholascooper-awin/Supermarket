<?php
namespace Tests;

require_once('./vendor/autoload.php');

use PHPUnit\Framework\TestCase;
use DateTimeProvider;

class DateTimeProviderTest extends TestCase
{
    public function test_can_get_DateTimeProvider()
    {
        $dateTimeProvider = new DateTimeProvider();
        $this->assertInstanceOf(DateTimeProvider::class, $dateTimeProvider);
    }

    public function test_can_get_last_moment() {
        $dateTimeProvider = new DateTimeProvider();
        $this->assertEquals($dateTimeProvider->getLastMomentOfTheMonth('102023')->format('Y-m-d H:i:s'), '2023-10-31 23:59:59');
    }

}