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
        //$obj = $di->make(Test1::class);

        $this->assertTrue($obj instanceof \DateTime);
    }

}



