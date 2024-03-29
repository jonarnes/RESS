<?php
include dirname(__FILE__).'/IWurflCloudCache.php';
include dirname(__FILE__).'/WurflCloudCache_File.php';
include dirname(__FILE__).'/WurflCloudCache_APC.php';
include dirname(__FILE__).'/WurflCloudCache_Memcache.php';
include dirname(__FILE__).'/WurflCloudCache_Memcached.php';
include dirname(__FILE__).'/WurflCloudCache_Null.php';

// Setup your cache object here with the settings you want to test
$cache = new WurflCloudCache_File();

// Set this to the number of tests you want to run
$max = 10000;

echo "Testing ".get_class($cache)." Performance\n";

echo "Testing $max Cache Writes...\n";
$start = microtime(true);
for ($i=0;$i<$max;$i++) {
	$ok = $cache->setDevice("Mozilla/$i Foobar", array('id'=>'foobar_ver1','model_name'=>'foobar'));
}
$time = microtime(true) - $start;
$avg = round(($time / $max) * 1000, 4)." ms";
$nice = round($time, 2)." sec";
echo "Time: $nice\nAvg: $avg\n";

echo "Waiting 5 seconds for I/O to settle";
sleep(1);echo '.';sleep(1);echo '.';sleep(1);echo '.';sleep(1);echo '.';sleep(1);echo ".\n";

echo "Testing $max Random Cache Reads\n";
$start = microtime(true);
for ($i=0;$i<$max;$i++) {
	$a = $max - 1 - $i;
	$cache->getDevice("Mozilla/$a Foobar");
}
$time = microtime(true) - $start;
$avg = round(($time / $max) * 1000, 4)." ms";
$nice = round($time, 2)." sec";
echo "Time: $nice\nAvg: $avg\n";

echo "Waiting 5 seconds for I/O to settle";
sleep(1);echo '.';sleep(1);echo '.';sleep(1);echo '.';sleep(1);echo '.';sleep(1);echo ".\n";

echo "Testing $max Sequential Cache Reads\n";
$start = microtime(true);
for ($i=0;$i<$max;$i++) {
	$cache->getDevice("Mozilla/$i Foobar");
}
$time = microtime(true) - $start;
$avg = round(($time / $max) * 1000, 4)." ms";
$nice = round($time, 2)." sec";
echo "Time: $nice\nAvg: $avg\n";
echo "\n\nDone.\n";

$cache = null;
