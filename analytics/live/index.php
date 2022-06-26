<?php

function humanTiming(int $time): string
{

    $time = time() - $time; // to get the time since that moment
    $time = ($time < 1) ? 1 : $time;
    $tokens = [
        31536000 => 'year',
        2592000 => 'month',
        604800 => 'week',
        86400 => 'day',
        3600 => 'hr',
        60 => 'min',
        1 => 'sec'
    ];

    foreach ($tokens as $unit => $text) {
        if ($time < $unit) continue;
        $numberOfUnits = floor($time / $unit);
        return $numberOfUnits . ' ' . $text;
    }

    return '0';
}

function getTableRow(string $line): string
{
    $parts = explode(" ", $line, 5);
    if (count($parts) != 5) {
        return '';
    }

    $entryTime = strtotime($parts[0]);
    $ageSec = time() - $entryTime;
    $age = humanTiming($entryTime);
    $ip = $parts[1];
    $url = $parts[2];
    $ref = $parts[3];
    $agent = $parts[4];

    $tableClass = $ageSec < 60 ? "newRow" : "";

    $html = "<tr class='$tableClass'>";
    $html .= "<td>$age</td>";
    $html .= "<td><a href='https://www.ip2location.com/$ip' class='text-dark'>$ip</a></td>";
    $html .= "<td><a href='$url'>$url</a></td>";
    $html .= "<td><a href='$ref' class='text-dark'>$ref</a></td>";
    $html .= "<td>$agent</td>";
    $html .= "</tr>";

    return $html;
}

function getTableRows(int $linesToShow = 20): string
{
    $logFilePaths = glob("../logs/*.txt");
    sort($logFilePaths);

    $mostRecentLogFilePath = $logFilePaths[count($logFilePaths) - 1];
    $logFileContents = file_get_contents($mostRecentLogFilePath);
    $lines = explode("\n", $logFileContents);
    $lines = array_reverse($lines);

    if (count($lines) > $linesToShow) {
        $lines = array_slice($lines, 0, $linesToShow);
    }

    $html = "";
    foreach ($lines as $line) {
        $html .= getTableRow($line);
    }
    return $html;
}

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Live Analytics</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">

    <meta http-equiv="refresh" content="10">

    <style>
        a {
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .breakable {
            word-break: break-all;
        }

        .unbreakable {
            white-space: nowrap;
        }

        .newRow {
            background-color: #FFFF0022;
        }
    </style>
</head>

<body>

    <div class="m-3">
        <h1>Live Analytics Dashboard</h1>
        <div>Updated <?php echo date('h:i:s A'); ?></div>
    </div>

    <table class="table table-hover unbreakable" style="font-size: .8em;">
        <thead>
            <tr>
                <th scope="col">Age</th>
                <th scope="col">IP</th>
                <th scope="col">URL</th>
                <th scope="col">Referrer</th>
                <th scope="col">Agent</th>
            </tr>
        </thead>
        <tbody>
            <?php echo getTableRows(); ?>
        </tbody>
    </table>


</body>

</html>