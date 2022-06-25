<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/**
 * Contains information about a single page request event.
 * URL and Referrer may be user-reported and not sanitized.
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
        DateTimeImmutable $timestamp,
        bool $sanitize = false
    ) {
        $this->ip = $ip;

        $this->timestamp = $timestamp;

        $this->url = $sanitize
            ? $this->_sanitize($url)
            : $url;

        $this->referrer = $sanitize
            ? $this->_sanitize($referrer)
            : $referrer;

        $this->agent = $sanitize
            ? $this->_sanitize($agent, true)
            : $agent;
    }

    private function _sanitize($text, bool $whitespaceAllowed = false): string
    {
        $text = strip_tags($text);
        $text = str_replace("\r", "", $text);
        $text = str_replace("\n", "", $text);
        if ($whitespaceAllowed == false) {
            $text = str_replace(" ", "", $text);
        }
        return $text;
    }
}
