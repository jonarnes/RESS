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
/**
 * Registers the class autoloader
 */
spl_autoload_register(array('WurflCloudClient', 'loadClass'));
/**
 * WURFL Cloud Client for PHP.
 * @package WurflCloudClient
 */
class WurflCloudClient {
	/**
	 * @var integer Configuration error
	 */
	const ERROR_CONFIG = 1;
	/**
	 * @var integer Unable to contact server or Invalid server address
	 */
	const ERROR_NO_SERVER = 2;
	/**
	 * @var integer Timed out while contacting server
	 */
	const ERROR_TIMEOUT = 4;
	/**
	 * @var integer Unable to parse response
	 */
	const ERROR_BAD_RESPONSE = 8;
	/**
	 * @var integer API Authentication failed
	 */
	const ERROR_AUTH = 16;
	/**
	 * @var integer API Key is disabled or revoked
	 */
	const ERROR_KEY_DISABLED = 32;
	/**
	 * Device was found in cache
	 * @var boolean
	 */
	public $found_in_cache = false;
	/**
	 * Flat capabilities array containing 'key'=>'value' pairs.
	 * Since it is 'flattened', there are no groups in this array, just individual capabilities.
	 * @var array
	 */
	public $capabilities = array();
	/**
	 * Errors that were encountered while processing the request and/or response.
	 * @var array
	 */
	protected $fatal_errors = array();
	/**
	 * Errors that were returned in the response body
	 * @var array
	 */
	protected $errors = array();
	/**
	 * The capabilities that will be searched for
	 * @var array
	 */
	protected $search_capabilities = array();
	/**
	 * The HTTP Headers that will be examined to find the best User Agent, if one is not specified
	 * @var array
	 */
	protected $user_agent_headers = array(
		'HTTP_X_DEVICE_USER_AGENT',
		'HTTP_X_ORIGINAL_USER_AGENT',
		'HTTP_X_OPERAMINI_PHONE_UA',
		'HTTP_X_SKYFIRE_PHONE',
		'HTTP_X_BOLT_PHONE_UA',
		'HTTP_USER_AGENT'
	);
	/**
	 * The HTTP User-Agent that is being evaluated
	 * @var string
	 */
	protected $user_agent;
	/**
	 * The HTTP Request that is being evaluated
	 * @var string
	 */
	protected $http_request;
	/**
	 * The WURFL Cloud Server that will be used to request device information (e.x. 'api.scientiamobile.com') 
	 * @var string
	 */
	protected $wcloud_host;
	/**
	 * The request path to the WURFL Cloud Server (e.x. '/v1/json/search:(is_wireless_device)' )
	 * @var string Request path (must begin with '/')
	 */
	protected $request_path;
	/**
	 * The raw json response from the server
	 * @var string
	 */
	protected $json;
	/**
	 * The HTTP Headers that will be used to query the WURFL Cloud Server in 'key'=>'value' format
	 * @var array
	 */
	protected $request_headers = array();
	/**
	 * Storage for report data (cache hits, misses, errors)
	 * @var array
	 */
	protected $report_data = array();
	/**
	 * The version of this client
	 * @var string
	 */
	protected $clientVersion = '0.9.0';
	/**
	 * The version of the WURFL Cloud Server
	 * @var string
	 */
	protected $api_version;
	/**
	 * The API Username
	 * @var integer 6-digit API Username
	 */
	protected $api_username;
	/**
	 * The API Password
	 * @var string 32-character API Password
	 */
	protected $api_password;
	/**
	 * The date that the WURFL Cloud Server's data was updated
	 * @var int
	 */
	protected $loaded_date;
	/**
	 * Client configuration object
	 * @var WurflCloudClientConfig
	 */
	protected $config;
	/**
	 * Client cache object
	 * @var IWurflCloudCache
	 */
	protected $cache;
	/**
	 * The source of the last detection
	 * @var string
	 */
	protected $source;
	
	/**
	 * Creates a new WurflCloudClient instance
	 * @param WurflCloudClientConfig $config Client configuration object
	 * @param IWurflCloudCache $cache Client caching object
	 * @throws WurflCloudClientConfigException Invalid configuration 
	 */
	public function __construct(WurflCloudClientConfig &$config, IWurflCloudCache &$cache) {
		$this->config = &$config;
		$this->cache = &$cache;
		$this->wcloud_host = $this->config->getCloudHost();
	}
	/**
	 * Get the requested capabilities from the WURFL Cloud for the given user agent
	 * @param string $user_agent HTTP User Agent of the device being detected
	 * @param array $search_capabilities Array of capabilities that you would like to retrieve
	 * @see detectDevice()
	 */
	public function getDeviceCapabilitiesFromAgent($user_agent, Array $search_capabilities) {
		$http_request = array('HTTP_USER_AGENT' => $user_agent);
		$this->detectDevice($http_request, $search_capabilities);
	}
	/**
	 * Get the requested capabilities from the WURFL Cloud for the given HTTP Request (normally $_SERVER)
	 * @param string $http_request HTTP Request of the device being detected
	 * @param array $search_capabilities Array of capabilities that you would like to retrieve
	 */
	public function detectDevice(Array $http_request, Array $search_capabilities) {
		$this->source = null;
		$this->search_capabilities = $search_capabilities;
		$this->found_in_cache = false;
		if (count($http_request) === 0) {
			$this->http_request = $_SERVER;
		} else {
			$this->http_request = $http_request;
		}
		$this->user_agent = $this->getUserAgent($http_request);
		if (strlen($this->user_agent) > 255) {
			$this->user_agent = substr($this->user_agent,0,255);
		}
		$result = $this->cache->getDevice($this->user_agent);
		if (!$result) {
			$this->source = 'cloud';
			$this->getDeviceCapabilitiesFromWurflCloud();
			$this->validateCache();
			if ($this->source == 'cloud') {
				$this->cache->setDevice($this->user_agent, $this->capabilities);
			}
		} else {
			$this->source = 'cache';
			$this->capabilities = $result;
			// The user requested capabilities that don't exist in the cached copy.  Retrieve and cache the missing capabilities
			if (!$this->allCapabilitiesPresent()) {
				$this->source = 'cloud';
				$initial_capabilities = $this->capabilities;
				$this->getDeviceCapabilitiesFromWurflCloud();
				$this->capabilities = array_merge($this->capabilities, $initial_capabilities);
				if ($this->source == 'cloud') {
					$this->cache->setDevice($this->user_agent, $this->capabilities);
				}
			}
			$this->found_in_cache = true;
		}
	}
	/**
	 * Gets the source of the result.  Possible values:
	 *  - cache:  from local cache
	 *  - cloud:  from WURFL Cloud Service
	 *  - client: from detection logic in the client
	 *  - null:   no detection was performed
	 *  @return string 'cache', 'cloud', 'client' or null
	 */
	public function getSource() {
		return $this->source;
	}
	/**
	 * Get the requested capabilities from the WURFL Cloud
	 */
	protected function getDeviceCapabilitiesFromWurflCloud() {
		$this->initializeRequest();
		if ($this->config->throw_exceptions === true) {
			$this->callWurflCloud();
			@$this->processResponse();
		} else {
			try {
				$this->callWurflCloud();
				@$this->processResponse();
			} catch(Exception $e) {
				// An exception was thrown, so the communications to the server failed.
				$this->recoveryCapabilities();
				return;
			}
		}
	}
	/**
	 * Initializes the WURFL Cloud request
	 */
	protected function initializeRequest() {
		$this->splitApiKey();
		$this->request_headers = array();
		
		// If the reportInterval is enabled and past the report age, include the report data in the next request
		if ($this->config->report_interval > 0 && $this->cache->getReportAge() >= $this->config->report_interval) {
			$this->addReportDataToRequest();
			$this->cache->resetReportAge();
			$this->cache->resetCounters();
		}
		if ($this->config->api_type === WurflCloudClientConfig::API_TCP) {
			return $this->getCapsRawTCP();
		}
		
		// Add HTTP Headers to pending request
		$this->addRequestHeader('User-Agent', $this->user_agent);
		$this->addRequestHeader('X-Cloud-Client', 'WurflCloudClient/PHP_'.$this->clientVersion);
		// We use 'X-Accept' so it doesn't stomp on our deflate/gzip header
		$this->addRequestHeaderIfExists('HTTP_ACCEPT', 'X-Accept');
		if (!$this->addRequestHeaderIfExists('HTTP_X_WAP_PROFILE', 'X-Wap-Profile')) {
			$this->addRequestHeaderIfExists('HTTP_PROFILE', 'X-Wap-Profile');
		}
		$this->request_path = '/v1/json/search:('.implode(',', $this->search_capabilities).')';
	}
	/**
	 * Get the date that the WURFL Cloud Server was last updated.  This will be null if there
	 * has not been a recent query to the server, or if the cached value was pushed out of memory  
	 * @return int UNIX timestamp (seconds since Epoch)
	 */
	public function getLoadedDate() {
		if ($this->loaded_date === null){
			$this->loaded_date = $this->cache->getMtime();
		}
		return $this->loaded_date;
	}
	
	/**
	 * Returns true if all of the search_capabilities are present in the capabilities
	 * array that was returned from the WURFL Cloud Server
	 * @return boolean
	 * @see WurflCloudClient::capabilities
	 */
	protected function allCapabilitiesPresent() {
		foreach ($this->search_capabilities as $key) {
			if (!array_key_exists($key, $this->capabilities)) {
				return false;
			}
		}
		return true;
	}
	/**
	 * Retrieves the report data from the cache provider and adds it to the request
	 * parameters to be included with the next request.
	 */
	protected function addReportDataToRequest() {
		$this->report_data = $this->cache->getCounters();
		$counters = array();
		foreach ($this->report_data as $key => $value) {
			$counters[] = "$key:$value";
		}
		$this->addRequestHeader('X-Cloud-Counters', implode(',', $counters));
		$this->cache->resetCounters();
	}
	/**
	 * Checks if local cache is still valid based on the date that the WURFL Cloud Server
	 * was last updated.  If auto_purge is enabled, this method will clear the cache provider
	 * if the cache is outdated.
	 * @see WurflCloudClientConfig::auto_purge
	 */
	protected function validateCache() {
		$cache_mtime = $this->cache->getMtime();
		if (!$cache_mtime || $cache_mtime != $this->loaded_date) {
			$this->cache->setMtime($this->loaded_date);
			if ($this->config->auto_purge) {
				$this->cache->purge();
			}
		}
	}
	/**
	 * Adds the HTTP header to the pending request
	 * @param string $key
	 * @param string $value
	 */
	protected function addRequestHeader($key, $value) {
		$this->request_headers[$key] = $value;
	}
	/**
	 * Get the HTTP Basic Authentication password for the API
	 * @return string
	 */
	protected function getSignature() {
		$sig_string = $this->request_path.$this->user_agent.$this->api_password;
		//echo "\n$sig_string\n";
		return md5($sig_string);
	}
	/**
	 * Adds the HTTP Header specified by $source_name (if found) in the pending request
	 * under $dest_name.  Example: addRequestHeaderIfExists('HTTP_USER_AGENT', 'User-Agent');
	 * @param string $source_name
	 * @param string $dest_name
	 * @return boolean true if the header was found and added, otherwise false
	 */
	protected function addRequestHeaderIfExists($source_name, $dest_name) {
		if (array_key_exists($source_name, $this->http_request)) {
			$this->request_headers[$dest_name] = $this->http_request[$source_name];
			return true;
		}
		return false;
	}
	
	/**
	 * Queries the WURFL Cloud Server using a raw TCP Socket
	 * @throws WurflCloudClientCommunicationsException Unable to connect to server
	 * @deprecated
	 */
	protected function getCapsRawTCP() {
		list($host,$port) = explode(':',$this->wcloud_host);
		if (!$fp = fsockopen ($host, $port, $errno, $errstr)) {
			throw new WurflCloudClientCommunicationsException("WURFL Cloud Client Error: Could not connect to WURFL Cloud server at $host:$port", self::ERROR_NO_SERVER);
		}
		if (count($this->search_capabilities) > 0) {
			fputs($fp,$this->user_agent."\0".implode(',',$this->search_capabilities)."\n");
		} else {
			fputs($fp,$this->user_agent."\n");
		}
		while (!feof($fp)) {
			$this->json .= fgets($fp);
		}
		fclose($fp);
		$this->json = json_decode(trim($this->json),true);
		$this->api_version = $this->json['apiVersion'];
		$this->loaded_date = $this->json['mtime'];
		$this->capabilities = $this->json['capabilities'];
	}
	/**
	 * Returns the value of the requested capability.  If the capability does not exist, returns null.
	 * @param string $capability The WURFL capability (e.g. "is_wireless_device")
	 * @return mixed Value of requested $capability or null if not found
	 */
	public function getDeviceCapability($capability) {
		$capability = strtolower($capability);
		if (array_key_exists($capability, $this->capabilities)) {
			return $this->capabilities[$capability];
		} else {
			if ($this->config->autolookup) {
				$this->getDeviceCapabilitiesFromAgent($this->user_agent, array($capability));
				if (array_key_exists($capability, $this->capabilities)) {
					return $this->capabilities[$capability];
				} else {
					return null;
				}
			} else {
				return null;
			}
		}
	}
	/**
	 * Get the version of the WURFL Cloud Client (this file)
	 * @return string
	 */
	public function getClientVersion() {
		return $this->clientVersion;
	}
	/**
	 * Get the version of the WURFL Cloud Server.  This is only available
	 * after a query has been made since it is returned in the response.
	 * @return string
	 */
	public function getAPIVersion() {
		return $this->api_version;
	}
	/**
	 * Returns the Cloud server that was used
	 */
	public function getCloudServer() {
		return $this->wcloud_host;
	}
	/**
	 * Make the webservice call to the server using the GET method and load the response.
	 * @throws WurflCloudClientCommunicationsException Unable to process server response
	 */
	protected function callWurflCloud() {
		// Determine the HTTP method to use and grab the response from the server
		switch ($this->config->http_method) {
			default:
			case WurflCloudClientConfig::METHOD_FSOCK:
				$data = $this->getDataFromWebserviceFsock();
				break;
			case WurflCloudClientConfig::METHOD_CURL:
				$data = $this->getDataFromWebserviceCurl();
				break;
				
		}
		
		$this->json = @json_decode($data, true);
		if(is_null($this->json)){
			$msg = 'Unable to parse JSON response from server.';
			$this->fatal_errors[] = $msg;
			throw new WurflCloudClientCommunicationsException($msg, self::ERROR_BAD_RESPONSE);
		}
		unset($data);
	}
	/**
	 * Returns the response body using the PHP cURL Extension
	 * @return string Response
	 * @throws WurflCloudClientCommunicationsException Unable to query server
	 */
	protected function getDataFromWebserviceCurl() {
		// CURLOPT_TIMEOUT_MS was introduced in libcurl version 7.16.2, PHP version 5.2.3
		// 7.16.2 converted to a 24-bit number (7 << 16 | 16 << 8 | 2) == 462850
		$version_info = curl_version();
		$supports_ms = ($version_info['version_number'] >= 462850 && version_compare(PHP_VERSION, '5.2.3') >= 0);
		// Introduced in curl 7.10.0 (461312)
		$supports_encoding = ($version_info['version_number'] >= 461312);
		
		$ch = curl_init('http://'.$this->wcloud_host.$this->request_path);
		$headers = array();
		foreach ($this->request_headers as $key => $value) {
			$headers[] = "$key: $value";
		}
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FORBID_REUSE, true);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_USERPWD, $this->api_username.':'.$this->getSignature());
		if ($this->config->compression === true && $supports_encoding === true) {
			curl_setopt($ch, CURLOPT_ENCODING, '');
		}
		if ($supports_ms) {
			// Required for CURLOPT_TIMEOUT_MS to play nice on most Unix/Linux systems
			// http://www.php.net/manual/en/function.curl-setopt.php#104597
			curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
			curl_setopt($ch, CURLOPT_TIMEOUT_MS, $this->config->http_timeout);
		} else {
			$timeout = ($this->config->http_timeout < 1000)? 1000: $this->config->http_timeout;
			curl_setopt($ch, CURLOPT_TIMEOUT, ($timeout / 1000));
		}
		$data = curl_exec($ch);
		$curl_errno = curl_errno($ch);
		$curl_error = curl_error($ch);
		$response_code = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		if ($curl_errno !== 0) {
			$msg = "Unable to contact server: cURL Error: $curl_error";
			$this->fatal_errors[] = $msg;
			throw new WurflCloudClientCommunicationsException($msg, self::ERROR_NO_SERVER);
		}
		if ($response_code < 400) {
			return $data;
		} else if ($response_code == 401) {
			$msg = "API Authentication error, check your API Key";
			$this->fatal_errors[] = $msg;
			throw new WurflCloudClientAuthException($msg, self::ERROR_AUTH);
		} else if ($response_code == 403) {
			$msg = "API Authorization error, your account or API Key is disabled";
			$this->fatal_errors[] = $msg;
			throw new WurflCloudClientAuthException($msg, self::ERROR_KEY_DISABLED);
		}
		$msg = "Unable to contact server: HTTP Error $response_code";
		$this->fatal_errors[] = $msg;
		throw new WurflCloudClientCommunicationsException($msg, self::ERROR_BAD_RESPONSE);
	}
	/**
	 * Returns the response body using fsockopen()
	 * @return string Response
	 * @throws WurflCloudClientCommunicationsException Unable to query server
	 * @author skamerman
	 */
	protected function getDataFromWebserviceFsock() {
		if (strpos(':', $this->wcloud_host) !== false) {
			list($host, $port) = explode(':', $this->wcloud_host);
		} else {
			$host = $this->wcloud_host;
			$port = '80';
		}
		// Open connection
		$fh = @fsockopen($host, (int)$port, $errno, $error, ($this->config->http_timeout / 1000));
		if (!$fh) {
			$msg = "Unable to contact server: fsock Error: $error";
			$this->fatal_errors[] = $msg;
			throw new WurflCloudClientCommunicationsException($msg, self::ERROR_NO_SERVER);
		}
		
		// Setup HTTP Request headers
		$http_header = "GET ".$this->request_path." HTTP/1.1\r\n";
		$http_header.= "Host: $host\r\n";
		if ($this->config->compression === true) {
			// Maybe I'll add deflate, but for now it's just gzip
			$http_header.= "Accept-Encoding: gzip\r\n";
		}
		$http_header.= "Accept: */*\r\n";
		$http_header.= "Authorization: Basic ".base64_encode($this->api_username.':'.$this->getSignature())."\r\n";
		foreach ($this->request_headers as $key => $value) {
			$http_header .= "$key: $value\r\n";
		}
		$http_header.= "Connection: Close\r\n";
		$http_header.= "\r\n";
		// Setup timeout
		stream_set_timeout($fh, 0, $this->config->http_timeout * 1000);
		
		// Send Request headers
		fwrite($fh, $http_header);
		
		// Get Response
		$http_response = '';
		while ($line = fgets($fh)) {
			$http_response .= $line;
		}
		$stream_info = stream_get_meta_data($fh);
		fclose($fh);
		
		// Check for Timeout
		if ($stream_info['timed_out']) {
			$msg = "HTTP Request timed out.";
			$this->fatal_errors[] = $msg;
			throw new WurflCloudClientCommunicationsException($msg, self::ERROR_TIMEOUT);
		}
		
		// Separate Header from Body
		list($raw_response_headers, $body) = explode("\r\n\r\n", $http_response, 2);

		// Parse Response headers
		$response_headers = explode("\r\n", $raw_response_headers);
		
		$response_string = array_shift($response_headers);
		if (!preg_match('#HTTP/1\.[01] ([0-9]{3}) #', $response_string, $match)) {
			$msg = "Unable to parse response headers.";
			$this->fatal_errors[] = $msg;
			throw new WurflCloudClientCommunicationsException($msg, self::ERROR_BAD_RESPONSE);
		}
		
		// Check HTTP Response code
		$response_code = (int)$match[1];
		if ($response_code >= 400 ) {
			if ($response_code == 401 || $response_code == 402) {
				$msg = "API Authentication error, check your API Key";
				$this->fatal_errors[] = $msg;
				throw new WurflCloudClientAuthException($msg, self::ERROR_AUTH);
			} else if ($response_code == 403) {
				$msg = "API Authentication error, your account or API Key is disabled";
				$this->fatal_errors[] = $msg;
				throw new WurflCloudClientAuthException($msg, self::ERROR_KEY_DISABLED);
			} else {
				$msg = "Unable to contact server: HTTP Error $response_code";
				$this->fatal_errors[] = $msg;
				throw new WurflCloudClientCommunicationsException($msg, self::ERROR_BAD_RESPONSE);
			}
		}
		
		// Decompress if necessary
		$compressed = false;
		foreach ($response_headers as $header) {
			if (stripos($header, 'Content-Encoding: gzip') !== false) {
				$compressed = true;
				break;
			}
		}
		if ($compressed === true) {
			$data = false;
			$data = @gzinflate(substr($body, 10));
			if (!is_string($data)) {
				$msg = "Received data (HTTP $response_code), but unable to uncompress the response: ".$body;
				$this->fatal_errors[] = $msg;
				throw new WurflCloudClientCommunicationsException($msg, self::ERROR_BAD_RESPONSE);
			}
			return $data;
		}
		return $body;
	}
	/**
	 * Parses the response into the capabilities array
	 */
	protected function processResponse() {
		$this->errors = $this->json['errors'];
		$this->api_version = $this->json['apiVersion'];
		$this->loaded_date = $this->json['mtime'];
		$this->capabilities['id'] = $this->json['id'];
		$this->capabilities = array_merge($this->capabilities,$this->json['capabilities']);
	}
	/**
	 * Casts strings into proper variable types, i.e. 'true' into true
	 * @param string $value
	 * @return string|int|boolean|float
	 */
	protected static function niceCast($value) {
		// Clean Boolean values
		if ($value === 'true') {
			$value = true;
		} else if ($value === 'false') {
			$value = false;
		} else {
			// Clean Numeric values by loosely comparing the (float) to the (string)
			$numval = (float)$value;
			if(strcmp($value,$numval)==0)$value=$numval;
		}
		return $value;
	}
	/**
	 * Is the given $dest a valid WURFL Cloud Server TCP destination
	 * @param string $dest
	 * @return boolean
	 */
	protected static function validTCPDest($dest) {
		$parts = explode(':',$dest);
		if (count($parts) == 2 && is_numeric($dest[1])) {
			return true;
		}
		return false;
	}
	/**
	 * Return the requesting client's User Agent
	 * @param $source
	 * @return string
	 */
	protected function getUserAgent($source=null) {
		if (is_null($source) || !is_array($source)) {
			$source = $_SERVER;
		}
		$user_agent = '';
		if (isset($_GET['UA'])) {
			$user_agent = $_GET['UA'];
		} else {
			foreach ($this->user_agent_headers as $header) {
				if (array_key_exists($header, $source) && $source[$header]) {
					$user_agent = $source[$header];
					break;
				}
			}
		}
		return $user_agent;
	}
	
	/**
	 * Splits the API Key into a username and password
	 * @return boolean success
	 */
	private function splitApiKey() {
		if (empty($this->config->api_key)) {
			// No API key was specified - use demo credentials
			$this->api_username = 100000;
			$this->api_password = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx';
			return true;
		}
		if (strlen($this->config->api_key) !== 39 || strpos($this->config->api_key, ':') !== 6) return false;
		$s_user = substr($this->config->api_key, 0, 6);
		$this->api_username = (int)$s_user; 
		// Cast back to string to see if the number is the same (string)(int)00001 === '1', not '00001'
		if((string)$this->api_username !== $s_user) return false;
		$this->api_password = substr($this->config->api_key, 7);
		return true;
	}
	/**
	 * Headers only found in mobile devices
	 * @var array
	 */
	private $mobile_headers = array(
		'HTTP_X_DEVICE_USER_AGENT',
		'HTTP_X_OPERAMINI_PHONE_UA',
		'HTTP_X_SKYFIRE_PHONE',
		'HTTP_X_BOLT_PHONE_UA',
		'HTTP_X_WAP_MSISDN',
		'HTTP_X_NETWORK_INFO',
		'HTTP_X_NOKIA_MSISDN',
		'HTTP_X_NOKIA_GATEWAY_ID',
		'HTTP_X_NOKIA_CONNECTION_MODE',
		'HTTP_X_NOKIA_BEARER',
		'HTTP_X_UP_CALLING_LINE_ID',
		'HTTP_X_UP_UPLINK',
		'HTTP_X_UP_DEVCAP_ISCOLOR',
		'HTTP_X_UP_SUBNO',
	);
	/**
	 * Keywords only found in mobile user agents
	 * @var array
	 */
	private $mobile_keywords = array(
		'up.browser', 'cldc', 'symbian', 'midp', 'j2me', 'mobile', 'wireless', 'palm', 'phone', 'pocket pc', 'pocketpc',
		'netfront', 'bolt', 'iris', 'brew', 'openwave', 'windows ce', 'wap2.', 'android', 'opera mini',
		'opera mobi', 'maemo', 'fennec', 'blazer', 'vodafone', 'wp7', 'armv',
	);
	/**
	 * Opera Mini keywords and associated WURFL IDs
	 * @var array
	 */
	private $ids_opera = array(
		'Opera Mini/1' => 'uabait_opera_mini_v10_op95',
		'Opera Mini/2' => 'uabait_opera_mini_v20_op80',
		'Opera Mini/3' => 'uabait_opera_mini_v30_op80',
		'Opera Mini/4' => 'uabait_opera_mini_v40_op95',
		'Opera Mini/5' => 'uabait_opera_mini_v50_op95',
	);
	/**
	 * Android keywords and associated WURFL IDs 
	 * @var array
	 */
	private $ids_android = array(
		'generic_android',
		'generic_android_ver1_5',
		'generic_android_ver1_6',
		'generic_android_ver2',
		'generic_android_ver2_1',
		'generic_android_ver2_2',
		'generic_android_ver2_3',
		'generic_android_ver3_0',
	);
	/**
	 * Used to stored the JSON-decoded recovery detection data
	 * @var array
	 */
	private $recovery_devices;
	/**
	 * Provides a failover mechanism if the API servers cannot be contacted for
	 * detecting basic device capabilities
	 * @return boolean success
	 */
	protected function recoveryCapabilities() {
		$this->source = 'client';

		if ($this->recovery_devices === null) {
			$data = @file_get_contents(dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'recovery.json');
			if ($data === false) {
				return false;
			}
			$this->recovery_devices = @json_decode($data, true);
			if ($this->recovery_devices === null || !is_array($this->recovery_devices) || !array_key_exists('generic', $this->recovery_devices)) {
				$this->recovery_devices = array();
				return false;
			}
		}
		$device_id = $this->getRecoveryID();
		if (!array_key_exists($device_id, $this->recovery_devices)) {
			$device_id = 'generic';
		}
		// Grab the full 'generic' capabilities and merge the overridden capabilities on top
		$this->capabilities = $this->recovery_devices['generic'];
		foreach($this->recovery_devices[$device_id] as $key => $val){
			$this->capabilities[$key] = $val;
		}
	}
	/**
	 * Gets the WURFL ID of the visiting device by using string comparison
	 * @return string WURFL ID
	 */
	protected function getRecoveryID() {
		// Apple
		if (strpos($this->user_agent, 'iPod') !== false) return 'apple_ipod_touch_ver1';
		if (strpos($this->user_agent, 'iPad') !== false) return 'apple_ipad_ver1';
		if (strpos($this->user_agent, 'iPhone') !== false) return 'apple_iphone_ver3';
		// Android
		if (strpos($this->user_agent, 'Android') !== false) {
			if (preg_match('#Android[\s/](\d)\.(\d)#', $this->user_agent, $matches)) {
				$version = 'generic_android_ver'.$matches[1].'_'.$matches[2];
				if ($version == 'generic_android_ver2_0') $version = 'generic_android_ver2';
				if (in_array($version, $this->ids_android)) {
					return $version;
				}
			}
			return 'generic_android';
		}
		// BlackBerry
		if (stripos($this->user_agent, 'BlackBerry') !== false) {
			if (preg_match('#Black[Bb]erry[^/\s]+/(\d).\d#',$ua,$matches)) {
				$version = $matches[1];
				switch ($version) {
					default:
					case '2':
						return 'blackberry_generic_ver2';
						break;
					case '4':
						return 'blackberry_generic_ver4';
						break;
					case '5':
						return 'blackberry_generic_ver5';
						break;
					case '6':
						return 'blackberry_generic_ver6';
						break;
				}
			}
		}
		// Nokia / Symbian
		if (strpos($this->user_agent, 'Symbian/3;') !== false) return 'nokia_generic_series60_symbian3';
		if (strpos($this->user_agent, 'Series60') !== false) return 'nokia_generic_series60';
		if (strpos($this->user_agent, 'Series80') !== false) return 'nokia_generic_series80';
		// Opera Mini / Mobi
		if (strpos($this->user_agent, 'Opera') !== false) {
			foreach ($this->ids_opera as $keyword => $device_id) {
				if (strpos($ua, $keyword) !== false) {
					return $device_id;
				}
			}
			if (strpos($this->user_agent, 'Opera Mobi') !== false) {
				return 'uabait_opera_mini_v40_op95';
			}
		}
		// Desktop Browsers
		if ($this->isMobile()) {
			return 'generic';
		} else {
			return 'generic_web_browser';
		}
	}
	/**
	 * Examines HTTP Headers and UA Keywords to determine if device is mobile
	 * @return boolean Visiting device is mobile
	 */
	protected function isMobile() {
		foreach ($this->mobile_headers as $key) {
			if (isset($this->request_headers[$key])) {
				return true;
			}
		}
		$ua_lower = strtolower($this->user_agent);
		if ($this->haystackContainsAnyNeedle($this->mobile_keywords, $ua_lower) === true) {
			return true;
		}
		return false;
	}
	/**
	 * Returns true if the $haystack contains any of the $needles
	 * @param array $needles
	 * @param string $haystack
	 */
	protected function haystackContainsAnyNeedle(Array $needles, $haystack) {
		foreach ($needles as $needle) {
			if (strpos($haystack, $needle) !== false) {
				return true;
			}
		}
		return false;
	}

	/**
	 * @var string The directory that this file is in.  Used by loadClass()
	 */
	private static $base_path;
	/**
	 * Class autoloader
	 * @param string $class_name
	 */
	public static function loadClass($class_name) {
		if (self::$base_path === null) {
			self::$base_path = dirname(__FILE__);
		}
		if ($class_name == 'WurflCloudClientConfig') {
			include self::$base_path.'/WurflCloudClientConfig.php';
		}
		if (strpos($class_name, 'WurflCloudCache') !== false) {
			include self::$base_path.'/../Cache/'.$class_name.'.php';
			return;
		}
	}
}
/**
 * Configuration Exception
 */
class WurflCloudClientConfigException extends Exception {}
/**
 * Communications Exception
 */
class WurflCloudClientCommunicationsException extends Exception {}
/**
 * Authentication Exception
 */
class WurflCloudClientAuthException extends Exception {}
