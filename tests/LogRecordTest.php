<?php

require_once __DIR__ . '/../analytics/lib.php';

class LogEndpointTest extends \PHPUnit\Framework\TestCase
{
    public function testUrlSanitationRemovesLineBreaks(): void
    {
        $ip = "11.22.33.44";
        $url = "https://foo\nbar\r.com/baz";
        $ref = "https://exa\nmple\r.com/";
        $agent = "some user agent";
        $timestamp = new DateTimeImmutable('2022-02-02T15:55:55');
        $record = new PageRecord($ip, $url, $ref, $agent, $timestamp);
        $record = getSanitizedRecord($record);

        $this->assertStringNotContainsString("\n", $record->url);
        $this->assertStringNotContainsString("\n", $record->referrer);

        $this->assertStringNotContainsString("\r", $record->url);
        $this->assertStringNotContainsString("\r", $record->referrer);

        $this->assertEquals("https://foobar.com/baz", $record->url);
        $this->assertEquals("https://example.com/", $record->referrer);
    }

    public function testUrlSanitationRemovesSpaces(): void
    {
        $ip = "11.22.33.44";
        $url = "https://foo bar .com/baz";
        $ref = "https://exa mple .com/";
        $agent = "some user agent";
        $timestamp = new DateTimeImmutable('2022-02-02T15:55:55');
        $record = new PageRecord($ip, $url, $ref, $agent, $timestamp);
        $record = getSanitizedRecord($record);

        $this->assertStringNotContainsString(" ", $record->url);
        $this->assertStringNotContainsString(" ", $record->referrer);

        $this->assertEquals("https://foobar.com/baz", $record->url);
        $this->assertEquals("https://example.com/", $record->referrer);
    }

    public function testUrlSanitationRemovesHtml(): void
    {
        $ip = "11.22.33.44";
        $url = "https://foo<b>HTML</b>bar<b>HTML</b>.com/baz";
        $ref = "https://exa<b>HTML</b>mple<b>HTML</b>.com/";
        $agent = "some user agent";
        $timestamp = new DateTimeImmutable('2022-02-02T15:55:55');
        $record = new PageRecord($ip, $url, $ref, $agent, $timestamp);
        $record = getSanitizedRecord($record);

        $this->assertStringNotContainsString("<", $record->url);
        $this->assertStringNotContainsString(">", $record->url);
        $this->assertStringNotContainsString("<", $record->referrer);
        $this->assertStringNotContainsString(">", $record->referrer);
    }

    public function testAgentSanitationRemovesLineBreaks(): void
    {
        $ip = "11.22.33.44";
        $url = "https://foobar.com/baz";
        $ref = "https://example.com/";
        $agent = "some \nuser\r agent";
        $timestamp = new DateTimeImmutable('2022-02-02T15:55:55');
        $record = new PageRecord($ip, $url, $ref, $agent, $timestamp);
        $record = getSanitizedRecord($record);

        $this->assertStringNotContainsString("\n", $record->agent);
        $this->assertStringNotContainsString("\r", $record->agent);
        $this->assertEquals("some user agent", $record->agent);
    }

    public function testAgentSanitationRemovesHtml(): void
    {
        $ip = "11.22.33.44";
        $url = "https://foobar.com/baz";
        $ref = "https://example.com/";
        $agent = "some user<b>asdf</b> agent";
        $timestamp = new DateTimeImmutable('2022-02-02T15:55:55');
        $record = new PageRecord($ip, $url, $ref, $agent, $timestamp);
        $record = getSanitizedRecord($record);

        $this->assertStringNotContainsString("<", $record->agent);
        $this->assertStringNotContainsString(">", $record->agent);
        $this->assertEquals("some userasdf agent", $record->agent);
    }
}
