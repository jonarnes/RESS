<?php
/**
 * An example of using a single class to easily get WURFL Capabilities
 * Make sure you edit the $api_key and $capabilities properties below.
 * 
 * You can use this class in your scripts like this:
 * $wireless = MyWurfl::get('is_wireless_device');
 */
class MyWurfl {
	/**
	 * Enter your API Key here
	 * @var string
	 */
	private static $api_key = '867488:MDKTrcCk4uhwEmpsjPaWbn3exQlYR67y';
	/**
	 * List the WURFL capabilities that you need here
	 * @var array
	 */
	private static $capabilities = array(
		'is_wireless_device',
		'brand_name',
		'model_name',
		'max_image_width',
		'max_image_height',
        'resolution_width',
		'resolution_height',
        'pointing_method',
        'has_qwerty_keyboard',
        'xhtml_supports_iframe',
        'css_spriting'
	);
	/**
	 * Initialize static instance
	 */
	private static function init() {
		self::loadClasses();
		// Additional configuration options can be used here
		$config = new WurflCloudClientConfig();
		$config->api_key = self::$api_key;
		
		/* Cache options can be set here */
		
		// Use APC:
		//$cache = new WurflCloudCache_APC();

		// Use Memcache:
		//$cache = new WurflCloudCache_Memcache();
		//$cache->addServer('localhost');

		// Use Filesystem
		$cache = new WurflCloudCache_File();
		
		// These two lines setup the WurflCloudClient and do the device detection
		self::$instance = new WurflCloudClient($config, $cache);
		self::$instance->detectDevice($_SERVER, self::$capabilities);
	}
	/**
	 * Returns the value of the requested capability
	 * @param string $capability_name
	 * @return mixed Value of the requested capability
	 */
	public static function get($capability_name) {
		if (self::$instance === null) self::init();
		return self::$instance->getDeviceCapability($capability_name);
	}
	public static function &getInstance() {
		return self::$instance;
	}
	public static function loadClasses() {
		if(!class_exists('WurflCloudClient', false)) include dirname(__FILE__).'/Client/WurflCloudClient.php';
		if(!class_exists('WurflCloudClientConfig', false)) include dirname(__FILE__).'/Client/WurflCloudClientConfig.php';
		if(!class_exists('IWurflCloudCache', false)) include dirname(__FILE__).'/Cache/IWurflCloudCache.php';
	}
	/**
	 * @var WurflCloudClient
	 */
	private static $instance;	
}