<?php

use core\Report;
use PHPUnit\Framework\TestCase;

class ReportTest extends TestCase
{
    public function testReportException() {
        $this->expectException(InvalidArgumentException::class);
        Report::report('test', InvalidArgumentException::class);
    }

    /**
     * @expectedException PHPUnit_Framework_Error
     */
    public function testReportFatal() {
        Report::reportFatal('test');
    }
}
