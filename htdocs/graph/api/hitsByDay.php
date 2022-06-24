<?php

require("hits.php");

function getHitsByDay(string $urlFilter = null): array
{
    $hitsByDay = array();

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

            if (array_key_exists($entry->dayCode, $hitsByDay)) {
                $hitsByDay[$entry->dayCode] += 1;
            } else {
                $hitsByDay[$entry->dayCode] = 1;
            }
        }
    }

    return $hitsByDay;
}

$hitsByDay = getHitsByDay($_GET["urlMatch"]);
echo json_encode($hitsByDay);
