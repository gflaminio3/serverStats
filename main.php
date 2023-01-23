<?php

function getCpuLoad() {
	// Get load average from PHP
	$rLoad = sys_getloadavg();

	// Calculate CPU Load by dividing load and cpu cores
	return floatval($rLoad[0]) / intval(shell_exec('grep -P \'^processor\' /proc/cpuinfo|wc -l'));
}

function getRAM() {
	// Execute free on CLI
	$free = explode("\n", trim(shell_exec('free')));

	// Parse data from output command
	$rMemory = preg_split('/[\\s]+/', $free[1]);
	
	// Organize Data into arrays
	$ram['used'] = intval($rMemory[2]);
	$ram['total'] = intval($rMemory[1]);
	$ram['percent'] = shell_exec('free -t | awk \'FNR == 2 {printf("%.2f"), $3/$2*100}\'');

	//Return Data
	return $ram;
}

$cpu = getCpuLoad();
$ram = getRAM();
$freeSpace =  disk_free_space('/'); // will output the value in bytes for precision
echo json_encode(['cpu' => $cpu, 'memory_used' => $ram['used'], 'memory_total' => $ram['total'], 'memory_percent' => $ram['percent'], 'disk_free' => $freeSpace]);