<?php

function getProductIdSuffix($row)
{
	$vidpid = $row['vendorid'];

	// todo: database product table...
	$known = array(
		// ASUS 960
		'1043:8520' => 'Strix',
		// ASUS 970
		'1043:8508' => 'Strix',
		// Gigabyte 750 ti
		'1458:362d' => 'OC',
		'1458:3649' => 'Black',
		// Gigabyte 960
		'1458:36ae' => '4GB',
		// MSI 960
		'1462:3202' => 'Gaming 2G',
		// MSI 970
		'1462:3160' => 'Gaming',
		// EVGA 740
		'3842:2744' => 'SC DDR3',
		// EVGA 750 Ti
		'3842:3753' => 'SC',
		'3842:3757' => 'FTW',
		// EVGA 950
		'3842:2957' => 'SSC',
		'3842:2966' => 'SSC 4GB',
		// EVGA 960
		'3842:2962' => 'SC',
		'3842:3966' => 'SSC 4GB',
		// EVGA 970
		'3842:2974' => 'SC',
		'3842:2978' => 'FTW',
		'3842:3975' => 'SSC',
		// EVGA 980
		'3842:2983' => 'SC',
		'3842:2986' => 'FTW',
		'3842:2989' => 'Hydro',
		// EVGA 980 Ti
		'3842:1996' => 'Hybrid',
	);

	if (isset($known[$vidpid])) {
		return ' '.$known[$vidpid];
	}
	return '';
}

function formatCudaArch($arch)
{
	if (is_numeric($arch)) {
		$a = intval($arch);
		return 'SM '.floor($a / 100).'.'.(($a % 100)/10);
	} else if (strpos($arch, '@')) {
		$p = explode('@', $arch);
		$a = intval($p[0]);
		$b = intval($p[1]);
		$hard = floor($a / 100).'.'.(($a % 100)/10);
		$real = floor($b / 100).'.'.(($b % 100)/10);
		return "SM {$hard}@{$real}";
	}
	return $arch;
}

function formatCPU($row)
{
	$device = $row['device'];
	if (strpos($device, '(R)')) {
		// from /proc/cpuinfo
		$device = str_replace('(R)', '', $device);
		$device = str_replace('(TM)','', $device);
		$device = str_replace(' CPU','', $device);
		$device = str_replace(' V2',' v2', $device);
		$device = str_replace(' V3',' v3', $device);
		$device = str_replace(' V4',' v4', $device);
	} else {
		// from windows env PROCESSOR_IDENTIFIER (to reduce the len)
		$device = str_replace(' Family', '', $device);
		$device = str_replace(' Stepping ', '.', $device);
		$device = str_replace(' GenuineIntel', ' Intel', $device);
		$device = str_replace(' AuthenticAMD', ' AMD', $device);
		// Clean up
		if (strpos($device, 'Intel64') !== false && strpos($device, ' Intel')) {
			$device = str_replace(' Intel','', $device);
			$device = str_replace('Intel64','Intel', $device);
		}
		if (strpos($device, 'AMD64') !== false && strpos($device, ' AMD')) {
			$device = str_replace(' AMD','', $device);
			$device = str_replace('AMD64','AMD', $device);
		}
		$device = rtrim($device, ',');
		// todo, clean the vid and use linux names/vid from the db (in the db)
		$parts = explode(':', $row['vendorid']);
		$cores = (int) arraySafeVal($parts, 2);
		switch ($device) {
		case 'Intel 6 Model 60.3':
			return ($cores == 4 ? 'Intel Core i5-4xxx' : 'Intel Core i7-4xxx');
		case 'Intel 6 Model 71.1':
			return ($cores == 4 ? 'Intel Core i5-5675C' : 'Intel Core i7-5775C');
		}
	}
	return $device;
}

function formatClientName($version)
{
	return $version;
}