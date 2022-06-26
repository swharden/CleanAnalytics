<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

/**
 * Contains information about a single page request event.
 */
class PageRecord
{
    public DateTimeImmutable $timestamp;
    public string $ip;
    public string $url;
    public string $referrer;
    public string $agent;

    function __construct(
        string $ip,
        string $url,
        string $referrer,
        string $agent,
        DateTimeImmutable $timestamp
    ) {
        $this->ip = $ip;
        $this->timestamp = $timestamp;
        $this->url =  $url;
        $this->referrer = $referrer;
        $this->agent = $agent;
    }
}

/**
 * Create a sanitized PageRecord from one potentially malicious strings
 */
function getSanitizedRecord(PageRecord $record): PageRecord
{
    return new PageRecord(
        getSanitizedString($record->ip),
        getSanitizedString($record->url),
        getSanitizedString($record->referrer),
        getSanitizedString($record->agent, true),
        $record->timestamp
    );
}

function getSanitizedString(string $text, bool $whitespaceAllowed = false): string
{
    $text = strip_tags($text);
    $text = str_replace("\r", "", $text);
    $text = str_replace("\n", "", $text);
    if ($whitespaceAllowed == false) {
        $text = str_replace(" ", "", $text);
    }
    return $text;
}
