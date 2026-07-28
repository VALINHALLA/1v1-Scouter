<?php
declare(strict_types=1);

include('pokemontypes.php');
header("content-type: text/plain");

// Setup Tracking Arrays
$allpokemon = [];
$allteams = [];
$winners = [];
$opponents = [];
$addedopponents = [];
$scoutedalts = [];
$failedReplays = [];

// Clean Mon string defensively
function cleanmon(string $string): string {
    $commaPos = strpos(substr($string, 1, 20), ',');
    if ($commaPos !== false) { 
        return trim(substr($string, 3, $commaPos - 2));
    } 
    
    $string2 = substr($string, 3, -1);
    $pipePos = strpos($string2, '|');
    if ($pipePos !== false) {
        return trim(substr($string2, 0, $pipePos));
    }

    return trim($string);
}

// Generate URL friendly mon name
function urlmon(string $string): string {
    $string = str_replace('-*', '', $string);
    $string = str_replace(' ', '-', $string);
    $string = str_replace(':', '', $string);
    return strtolower($string);
}

// Process Username Inputs
if (isset($_POST['fname']) && !empty($_POST['fname'])) {
    $namebox = explode(',', (string)$_POST['fname']);
    foreach ($namebox as $value) {
        $scoutedalts[] = strtolower((string)preg_replace("/[^a-zA-Z0-9]+/", "", $value));
    }
} else {
    die("Error: No usernames provided.");
}

$verifiedreplays = [];
$box = htmlspecialchars((string)($_POST['statsbox'] ?? ''));
if (strlen($box) > 2) {
    $submitreplays = explode('https://', $box);
    foreach ($submitreplays as $value) {
        $value = trim($value);
        if (strlen($value) > 10) {
            $clean_url = "https://" . preg_replace('/\.log.*/', '', $value) . ".log";
            
            if (strlen($clean_url) > 70 && !str_contains($clean_url, 'pw.log')) {
                $clean_url = str_replace('.log', 'pw.log', $clean_url);
            }
            
            if (!in_array($clean_url, $verifiedreplays, true)) {
                $verifiedreplays[] = $clean_url;
            }
        }
    }
} else {
    die("Error: You didn't provide any replays.");
}

// Suggest Sheet Size
$replayCount = count($verifiedreplays);
if ($replayCount < 24) {
    echo "Team/Replay Count: {$replayCount}. Small sheet recommended.\n\n";
} elseif ($replayCount < 46) {
    echo "Team/Replay Count: {$replayCount}. Main sheet recommended.\n\n";
} else {
    echo "Team/Replay Count: {$replayCount}. Bigger sheet recommended.\n\n";
}

// Stream context for SSRF Prevention and Timeouts
$context = stream_context_create([
    'http' => [
        'timeout' => 4, // 4 second hard timeout per request
        'user_agent' => 'GoliadScouter/2.0'
    ]
]);

$allowedHosts = [
    'replay.pokemonshowdown.com',
    'showdown.ali3.me'
];

// Parse Replays
foreach ($verifiedreplays as $replay) {
    // Domain Check (SSRF Protection)
    $parsedUrl = parse_url($replay);
    if (!isset($parsedUrl['host']) || !in_array($parsedUrl['host'], $allowedHosts, true)) {
        $failedReplays[] = $replay . " (Untrusted/Blocked Domain)";
        continue;
    }

    // Safely Fetch Content
    $htmlbeta = @file_get_contents($replay, false, $context);
    if (!$htmlbeta) {
        $failedReplays[] = $replay . " (Unreachable/Timeout)";
        continue;
    }
    
    $html = htmlspecialchars($htmlbeta);
    $dar = explode("|poke|", $html);
    if (count($dar) < 2) {
        $failedReplays[] = $replay . " (Invalid replay structure)";
        continue;
    }
    
    $frfr = explode("|player|", $dar[0]);
    if (!isset($frfr[1]) || !isset($frfr[2])) {
        $failedReplays[] = $replay . " (Missing player headers)";
        continue;
    }

    $p1_raw = substr($frfr[1], 3, (int)strpos(substr($frfr[1], 3), '|'));
    $p2_raw = substr($frfr[2], 3, (int)strpos(substr($frfr[2], 3), '|'));
    
    $playerone = strtolower((string)preg_replace("/[^a-zA-Z0-9]+/", "", $p1_raw));
    $playertwo = strtolower((string)preg_replace("/[^a-zA-Z0-9]+/", "", $p2_raw));

    // Get Winner
    $chickendinner = explode('|win|', $html);
    if (isset($chickendinner[1])) {
        $winners[] = strtolower((string)preg_replace("/[^a-zA-Z0-9]+/", "", explode('|', $chickendinner[1])[0]));
    }

    // Determine Format
    $is1v1 = str_contains($replay, '1v1');
    $p1_team = $is1v1 ? [cleanmon($dar[1] ?? ''), cleanmon($dar[2] ?? ''), cleanmon($dar[3] ?? '')] : [cleanmon($dar[1] ?? ''), cleanmon($dar[2] ?? ''), cleanmon($dar[3] ?? ''), cleanmon($dar[4] ?? ''), cleanmon($dar[5] ?? ''), cleanmon($dar[6] ?? '')];
    $p2_team = $is1v1 ? [cleanmon($dar[4] ?? ''), cleanmon($dar[5] ?? ''), cleanmon($dar[6] ?? '')] : [cleanmon($dar[7] ?? ''), cleanmon($dar[8] ?? ''), cleanmon($dar[9] ?? ''), cleanmon($dar[10] ?? ''), cleanmon($dar[11] ?? ''), cleanmon($dar[12] ?? '')];

    $target_team = null;
    if (in_array($playerone, $scoutedalts, true)) {
        $opponents[] = $playertwo;
        $target_team = $p1_team;
    } elseif (in_array($playertwo, $scoutedalts, true)) {
        $opponents[] = $playerone;
        $target_team = $p2_team;
    }

    if ($target_team) {
        $allteams[] = $target_team;
        foreach ($target_team as $mon) {
            $allpokemon[] = $mon;
        }
    }
}

// Generate Sheet Code
echo "#TEAMS#\n";
$suffix1 = ['-Unbound', '-Therian', '-Alola', '-Galar', '-Hisui', '-Black', '-Antique', '-Low-Key', '-Dada'];
$suffix2 = ['-U', '-T', '-A', '-G', '-H', '-B', '', '', ''];

$teamcount = 0;
foreach ($allteams as $key => $value) {
    if (count($value) >= 3) {
        $replayLink = substr($verifiedreplays[$key] ?? '', 0, -4);
        $winStatus = (isset($winners[$key]) && in_array($winners[$key], $scoutedalts, true)) ? "W" : "L";
        
        $oppName = (isset($opponents[$teamcount]) && in_array($opponents[$teamcount], $addedopponents, true)) ? ' ' : ucfirst($opponents[$teamcount] ?? 'Unknown');
        if (isset($opponents[$teamcount])) {
            $addedopponents[] = $opponents[$teamcount];
        }

        echo '={"' . $oppName . '",';
        foreach ($value as $mon) {
            echo 'IMAGE("https://www.smogon.com/forums/media/minisprites/' . urlmon($mon) . '.png"), "' . str_replace($suffix1, $suffix2, $mon) . '",';
        }
        echo 'HYPERLINK("' . $replayLink . '", "' . $winStatus . '")}' . "\n";
        
        $teamcount++;
    }
}

// Usage Aggregation
echo (isset($_POST['altusagebox'])) ? "\n#POKEMON USAGE (TAB 1)#\n" : "\n#POKEMON USAGE#\n";

$countingfreq = array_count_values($allpokemon);
arsort($countingfreq);
$totalTeams = count($allteams) ?: 1;
$pkmnusagecount = 0;

foreach ($countingfreq as $key => $value) {
    $finalMon = urlmon((string)$key);
    $cleanMonName = str_replace('"', '""', str_replace($suffix1, $suffix2, (string)$key));
    $percentage = substr((string)($value / $totalTeams * 100), 0, 5) . '%';
    
    // Formatted as Google Sheets Array Formula: ={IMAGE(...), "Name", "Percentage", Count}
    $outputLine = '={IMAGE("https://www.smogon.com/forums/media/minisprites/' . $finalMon . '.png"), "' . $cleanMonName . '", "' . $percentage . '", ' . $value . '}' . "\n";

    if (!isset($_POST['altusagebox']) || $pkmnusagecount % 2 == 0) {
        echo $outputLine;
    }
    $pkmnusagecount++;
}

if (isset($_POST['altusagebox'])) {
    echo "\n#TAB2#\n";
    $pkmnusagecount2 = 0;
    foreach ($countingfreq as $key => $value) {
        if ($pkmnusagecount2 % 2 != 0) {
            $finalMon = urlmon((string)$key);
            $cleanMonName = str_replace('"', '""', str_replace($suffix1, $suffix2, (string)$key));
            $percentage = substr((string)($value / $totalTeams * 100), 0, 5) . '%';
            
            echo '={IMAGE("https://www.smogon.com/forums/media/minisprites/' . $finalMon . '.png"), "' . $cleanMonName . '", "' . $percentage . '", ' . $value . '}' . "\n";
        }
        $pkmnusagecount2++;
    }
}

// Failed Replays Section Output
if (!empty($failedReplays)) {
    echo "\n# FAILED OR UNREACHABLE REPLAYS #\n";
    foreach ($failedReplays as $failed) {
        echo "# " . $failed . "\n";
    }
}
?>
