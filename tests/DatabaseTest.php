<?php

require_once __DIR__ . '/../analytics/lib.php';
require_once __DIR__ . '/../analytics/database.php';

class DatabaseTest extends \PHPUnit\Framework\TestCase
{
    public function testWritesInformationIntoLogfile(): void
    {
        $ip = '11.22.33.44';
        $url = 'https://foobar.com/baz';
        $ref = "https://example.com/";
        $agent = "some user agent";
        $timestamp = new DateTimeImmutable('2022-02-02T15:55:55');
        $record = new PageRecord($ip, $url, $ref, $agent, $timestamp);

        $logFilePath = tempnam(sys_get_temp_dir(), 'logTest');
        writeToLogFile($record, $logFilePath);

        $this->_assertFileLineCount($logFilePath, 1);
    }

    private function _assertFileLineCount(string $file, int $expectedLineCount): void
    {
        $this->assertFileExists($file);
        $actualLineCount = count(explode("\n", file_get_contents($file))) - 1;

        $this->assertEquals($expectedLineCount, $actualLineCount);
    }
}
