<?php

require("hits.php");

function getHitsByHour(string $urlFilter = null): array
{
    $hitsByHour = array();

    foreach (glob("../../logs/*.txt") as $filePath) {
        $lines = explode("\n", file_get_contents($filePath));
        foreach ($lines as $line) {
            $entry = new LogLine($line);

            if (!$entry->isValid)
                continue;

            if ($urlFilter) {
                if (strpos($entry->url, $urlFilter) === false) {
                    continue;
                }
            }

            if (array_key_exists($entry->hourCode, $hitsByHour)) {
                $hitsByHour[$entry->hourCode] += 1;
            } else {
                $hitsByHour[$entry->hourCode] = 1;
            }
        }
    }

    return $hitsByHour;
}

$hitsByHour = getHitsByHour($_GET["urlMatch"]);
echo json_encode($hitsByHour);
