<?php
/**
 * WURFL Cloud Client - Simple example
 */
// Include the MyWurfl.php file
require_once dirname(__FILE__).'/MyWurfl.php';

// Check if the device is mobile

$start = microtime(true);
try {
	$mobile = MyWurfl::get('is_wireless_device');
} catch(Exception $e) {
	echo 'Error: '.$e->getMessage();
}
$time = microtime(true) - $start;
echo "Result: ".MyWurfl::get('id')." <br/>\n";
if ($mobile) {
	echo 'This is a mobile device. <br/>';
	echo 'Device: '.MyWurfl::get('brand_name').' '.MyWurfl::get('model_name')." <br/>\n";
} else {
	echo "This is a desktop browser <br/>\n";
}

// Get other information about this request
$client = &MyWurfl::getInstance();
echo "Source: ".$client->getSource()."<br/>\n";
echo "Server: ".$client->getCloudServer()."<br/>\n";
echo "Query time: $time <br/>\n";