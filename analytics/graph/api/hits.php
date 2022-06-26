<?php

class LogLine
{
    public int $time;
    public string $hourCode;
    public string $dayCode;
    public string $ip;
    public string $url;
    public string $ref;
    public string $agent;
    public bool $isValid;

    function __construct(string $line)
    {
        $parts = explode(" ", $line, 5);
        if (count($parts) != 5) {
            $this->time = 0;
            $this->hourCode = "";
            $this->dayCode = "";
            $this->isValid = false;
            return;
        }

        $this->time = strtotime($parts[0]);
        $this->hourCode = substr($parts[0], 0, 13);
        $this->dayCode = substr($parts[0], 0, 10);
        $this->ip = $parts[1];
        $this->url = $parts[2];
        $this->ref = $parts[3];
        $this->agent = $parts[4];
        $this->isValid = true;
    }
}
