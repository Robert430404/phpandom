<?php

/**
 * Sets the ranges for the next portion of the script
 */
if (isset($argv[1])) {
    $handle   = fopen(__DIR__ . '/range.txt', 'w+');
    $start    = $argv[2];
    $end      = $argv[3];
    $entrants = [];
    
    while ($start < $end) {
        $entrants[] = $start;
        $start++;
    }

    fwrite($handle, serialize($entrants));
    fclose($handle);

    showLine("\033[33mEntrants Registered");

    die();
}

/**
 * Retrieves the winner and logs him so he can't win again.
 */
if (!isset($argv[1])) {
    $handle   = fopen(__DIR__ . '/range.txt', 'r+');
    $range    = fread($handle, filesize(__DIR__ . '/range.txt'));
    $entrants = unserialize($range);

    fclose($handle);

    if (count($entrants) > 0) {
        $winner   = mt_rand(0, count($entrants) - 1);

        showLine("\033[34mAnd the winner is...\033[0m");
        showLine("\033[32mEntrant: " . $entrants[$winner] . "\033[0m", 2);
        unset($entrants[$winner]);

        $entrants = array_values($entrants);
        $handle   = fopen(__DIR__ . '/range.txt', 'w+');

        fwrite($handle, serialize($entrants));
        fclose($handle);
        die();
    }

    showLine("\033[31mNo Entrants Available");

    die();
}

/**
 * Outputs the line to the console
 */
function showLine($line, $delay = 0)
{
    sleep($delay);
    echo $line . "\n\r";
}
