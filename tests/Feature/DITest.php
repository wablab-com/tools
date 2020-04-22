<?php
namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use WabLab\Tools\DI;

class DITest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_datetime_object()
    {
        $di = new DI();
        $obj = $di->make(\DateTime::class);

        $this->assertTrue($obj instanceof \DateTime);
    }

}
