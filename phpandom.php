<?php

/**
 * Sets the ranges for the raffle participants if the params are
 * provided to the script.
 */
if (isset($argv[1])) {
	$start    = $argv[1];
	$end      = $argv[2];
	
	insertEntrantRange($start, $end);
}

/**
 * Retrieves the winner and removes him so he can't win again.
 */
if (!isset($argv[1]) && checkForEntrants()) {
	getWinner();
}

/**
 * Outputs a line to the console
 * 
 * @param  string      $line
 * @param  int|integer $delay
 * @return null
 */
function showLine(string $line, int $delay = 0)
{
	sleep($delay);
	echo $line . "\n\r";

	return null;
}

/**
 * This opens the range text file in the mode provided
 * 
 * @param  string $mode
 * @return resource
 */
function openRangeFile(string $mode)
{
	return fopen(__DIR__ . '/range.txt', $mode);
}

/**
 * Returns the data from the range file
 * 
 * @param  resource $handle
 * @return array
 */
function readRangeFile($handle): array
{
	$data = fread($handle, filesize(__DIR__ . '/range.txt'));

	return unserialize($data);
}

/**
 * Inserts the endtrants into an array for later use
 * 
 * @param  int    $start
 * @param  int    $end
 * @return null
 */
function insertEntrantRange(int $start, int $end)
{
	$handle   = openRangeFile('w+');
	$entrants = [];
	
	while ($start <= $end) {
		array_push($entrants, $start);
		$start++;
	}

	fwrite($handle, serialize($entrants));
	fclose($handle);

	showLine("\033[33mEntrants Registered");
	return null;
}

/**
 * Checks to make sure the entrants are set
 * 
 * @return bool
 */
function checkForEntrants(): bool
{
	if (!file_exists(__DIR__ . '/range.txt')) {
		showLine("\033[31mNo Entrants Available");
		return false;
	}

	return true;
}

/**
 * Gets the winner
 * 
 * @return null
 */
function getWinner()
{
	$handle   = openRangeFile('r+');
	$entrants = readRangeFile($handle);
	$count    = count($entrants);

	fclose($handle);

	if ($count > 0) {
		$winner = mt_rand(0, $count - 1);

		showLine("\033[34mAnd the winner is...\033[0m");
		showLine("\033[32mEntrant: " . $entrants[$winner] . "\033[0m", 2);
		unset($entrants[$winner]);

		$entrants = array_values($entrants);
		$handle   = openRangeFile('w+');

		fwrite($handle, serialize($entrants));
		fclose($handle);

		return null;
	}

	showLine("\033[36mNo Entrants Have Been Entered");
	return null;
}