<?php

use core\Report;

/** @noinspection LongInheritanceChainInspection */
class ReportTest extends PHPUnit_Framework_TestCase
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
