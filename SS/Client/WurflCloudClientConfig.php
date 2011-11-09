<?php
/*************************************************************************
 * 
 * SCIENTIAMOBILE CONFIDENTIAL
 * __________________
 * 
 *  2011 ScientiaMobile Incorporated 
 *  All Rights Reserved.
 * 
 * NOTICE:  All information contained herein is, and remains
 * the property of ScientiaMobile Incorporated and its suppliers,
 * if any.  The intellectual and technical concepts contained
 * herein are proprietary to ScientiaMobile Incorporated
 * and its suppliers and may be covered by U.S. and Foreign Patents,
 * patents in process, and are protected by trade secret or copyright law.
 * Dissemination of this information or reproduction of this material
 * is strictly forbidden unless prior written permission is obtained
 * from ScientiaMobile Incorporated.
 */
class WurflCloudClientConfig {
	/**
	 * The WURFL Cloud Service API type
	 * @var string
	 */
	const API_HTTP = 'http';
	/**
	 * Reserved for future use
	 * @var string
	 */
	const API_TCP = 'tcp';
	/**
	 * Use the PHP 'curl' extension
	 * @var int
	 */
	const METHOD_CURL = 1;
	/**
	 * Use straight PHP TCP calls using fsockopen().  This is the default method.
	 * @var int
	 */
	const METHOD_FSOCK = 2;
	
	/**
	 * The timeout in milliseconds to wait for the WURFL Cloud request to complete
	 * @var int
	 */
	public $http_timeout = 1000;
	/**
	 * Enables or disables the use of compression in the WURFL Cloud response.  Using compression
	 * can increase CPU usage in very high traffic environments, but will decrease network traffic
	 * and latency.
	 * @var boolean
	 */
	public $compression = true;
	/**
	 * Force a given HTTP method
	 * @var int
	 * @see METHOD_CURL, METHOD_FSOCK
	 */
	public $http_method = 2;
	/**
	 * If true, the API will throw an exception if there is a communications problem.  If false,
	 * the API will make a reasonable guess as to the capabilities of the device.
	 * @var boolean
	 */
	public $throw_exceptions = false;
	/**
	 * If true, the entire cache (e.g. memcache, APC) will be cleared if the WURFL Cloud Service has
	 * been updated.  This option should not be enabled for production use since it will result in a
	 * massive cache purge, which will result in higher latency lookups.
	 * @var boolean
	 */
	public $auto_purge = false;
	/**
	 * The interval in seconds that after which API will report its performance 
	 * @var int
	 */
	public $report_interval = 60;
	/**
	 * The WURFL Cloud API Type to be used.  Currently, only WurflCloudClientConfig::API_HTTP is supported.
	 * @var string
	 * @see API_HTTP
	 */
	public $api_type = 'http';
	
	/**
	 * The API Key is used to authenticate with the WURFL Cloud Service.  It can be found at in your account
	 * at http://www.scientiamobile.com/myaccount
	 * The API Key is 39 characters in with the format: nnnnnn:xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	 * where 'n' is a number and 'x' is a letter or number
	 * @var string
	 */
	public $api_key = null;
	/**
	 * If you try to use a capability that has not been retrieved yet and this is set to true,
	 * it will generate another request to the webservice and retrieve this capability automatically.
	 * NOTE: for performance reasons, it is best to lookup all the capabilities that you need for your application
	 * since it only requires a single query to the WURFL Cloud
	 * @var boolean
	 */
	public $autolookup = false;
	/**
	 * WURFL Cloud servers to use for uncached requests.  The "weight" field can contain any positive number,
	 * the weights are relative to each other.  
	 * @var array WURFL Cloud Servers
	 */
	public $wcloud_servers = array(
	//  'nickname'		=> array(host, weight),
		'wurfl_cloud' 	=> array('api.wurflcloud.com', 80),
	);
	
	/**
	 * The WURFL Cloud Server that is currently in use, formatted like:
	 * 'server_nickname' => array('url', 'weight')
	 * @var array
	 */
	protected $current_server = array();
	
	/**
	 * Adds the specified WURFL Cloud Server
	 * @param string $nickname Unique identifier for this server
	 * @param string $url URL to this server's API
	 * @param int $weight Specifies the chances that this server will be chosen over
	 * the other servers in the pool.  This number is relative to the other servers' weights.
	 */
	public function addCloudServer($nickname, $url, $weight=100) {
		$this->wcloud_servers[$nickname] = array($url, $weight);
	}
	/**
	 * Removes the WURFL Cloud Servers
	 */
	public function clearServers() {
		$this->wcloud_servers = array();
	}
	/**
	 * Determines the WURFL Cloud Server that will be used and returns its URL.
	 * @return string WURFL Cloud Server URL
	 */
	public function getCloudHost() {
		$server = $this->getWeightedServer();
		return $server[0];
	}
	/**
	 * Uses a weighted-random algorithm to chose a server from the pool
	 * @return array Server in the form array('host', 'weight')
	 */
	public function getWeightedServer() {
		if (count($this->current_server) === 1) {
			return $this->current_server;
		}
		if (count($this->wcloud_servers) === 1) {
			return $this->wcloud_servers[key($this->wcloud_servers)];
		}
		$max = $rcount = 0;
		foreach ($this->wcloud_servers as $k=>$v) {
			$max += $v[1];
		}
		$wrand = mt_rand(0,$max);
		foreach ($this->wcloud_servers as $k=>$v) {
			if ($wrand <= ($rcount += $v[1])) {
				break;
			}
		}
		$this->current_server = $this->wcloud_servers[$k];
		return $this->current_server;
	}
}