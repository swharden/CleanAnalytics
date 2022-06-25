<?php

require_once __DIR__ . '/../analytics/lib.php';

class LogEndpointTest extends \PHPUnit\Framework\TestCase
{

    public function testWritesInformationIntoLogfile(): void
    {
        $date = new DateTimeImmutable('2022-02-02T15:55:55');
        $testfile = tempnam(sys_get_temp_dir(), 'logtest');

        writeStatistics(
            $testfile,
            $date,
            '11.11.11.11',
            'https://foobar.com/baz',
            "https://test.com/",
            "Some-Useragent"
        );


        $this->assertFileContainsLineNumber($testfile, 1);
    }

    public function testDoesNotAddLinebreakIfLinebreakInData(): void
    {
        $date = new DateTimeImmutable('2022-02-02T15:55:55');
        $testfile = tempnam(sys_get_temp_dir(), 'logtest');

        writeStatistics(
            $testfile,
            $date,
            "11.11.11\n.11",
            "https://foobar.com/\nbaz",
            "https://test.com/\ntest",
            "Some-\nUseragent"
        );


        $this->assertFileContainsLineNumber($testfile, 1);
    }

    public function testStripsOutHtmlTagsInData(): void
    {
        $date = new DateTimeImmutable('2022-02-02T15:55:55');
        $testfile = tempnam(sys_get_temp_dir(), 'logtest');

        writeStatistics(
            $testfile,
            $date,
            '<script>11.11.11.11</script>',
            '<p>https://foobar.com/baz</p>',
            "<a href=\"https://test.com/\">https://test.com/</a>",
            "<i>Some-Useragent</i>"
        );


        $this->assertFileContainsLineNumber($testfile, 1);
        $filecontent = file_get_contents($testfile);
        $this->assertStringNotContainsString('<', $filecontent);
        $this->assertStringNotContainsString('<', $filecontent);
    }

    private function assertFileContainsLineNumber(string $file, int $expectedLineCount): void
    {
        $this->assertFileExists($file);
        $actualLineCount = count(explode("\n", file_get_contents($file))) - 1;

        $this->assertEquals($expectedLineCount, $actualLineCount);
    }
}