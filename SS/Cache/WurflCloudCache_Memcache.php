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
class WurflCloudCache_Memcache implements IWurflCloudCache {
	
	const MEMCACHE_COMPRESSED = 2;
	/**
	 * Number of seconds to keep device cached in memory.  Default: 0 - forever.
	 * Note: the device will eventually be pushed out of memory if the memcached
	 * process runs out of memory.
	 * @var int Seconds to cache the device in memory
	 */
	public $cache_expiration = 86400;
	/**
	 * Used to add randomness to the cache expiration.  If this value is 0, no 
	 * randomness will be added, if it's above 0, a random value between 0..value
	 * will be added to the cache_expiration to prevent massive simultaneous expiry
	 * @var int
	 */
	public $cache_expiration_rand_max = 0;
	/**
	 * @var Memcache
	 */
	protected $memcache;
	protected $prefix = 'dbapi_';
	public $compression = false;
	
	public function __construct(){
		if (!class_exists('Memcache', false)) {
			throw new Exception("The class 'Memcache' does not exist.  Please verify the extension is loaded");
		}
		$this->memcache = new Memcache();
	}
	public function addServer($host, $port = 11211, $weight = 1, $persistent = true) {
		$this->memcache->addServer($host, $port, $persistent, $weight);
	}
	public function addServerUnixSocket($socket_path, $weight = 1, $persistent = true) {
		$this->memcache->addServer('unix://'.$socket_path, 0, $persistent, $weight);
	}
	public function getDevice($user_agent){
		$device_id = $this->memcache->get(md5($user_agent));
		if ($device_id !== false) {
			$caps = $this->memcache->get($device_id);
			if ($caps !== false) {
				$this->incrementHit();
				return $caps;
			}
		}
		$this->incrementMiss();
		return false;
	}
	public function getDeviceFromID($device_id) {
		return $this->memcache->get($device_id);
	}
	public function setDevice($user_agent, $capabilities){
		$ttl = $this->cache_expiration;
		if ($this->cache_expiration_rand_max !== 0) {
			$ttl += mt_rand(0, $this->cache_expiration_rand_max);
		}
		$compress = ($this->compression)? self::MEMCACHE_COMPRESSED: null;
		// Set user_agent => device_id
		$this->memcache->add(md5($user_agent), $capabilities['id'], null, $ttl);
		// Set device_id => (array)capabilities
		$this->memcache->add($capabilities['id'], $capabilities, $compress, $ttl);
		return true;
	}
	public function setDeviceFromID($device_id, $capabilities){
		$ttl = $this->cache_expiration;
		if ($this->cache_expiration_rand_max !== 0) {
			$ttl += mt_rand(0, $this->cache_expiration_rand_max);
		}
		$compress = ($this->compression)? self::MEMCACHE_COMPRESSED: null;
		$this->memcache->add($device_id, $capabilities, $compress, $ttl);
		return true;
	}
	public function getMtime(){
		return (int)$this->memcache->get($this->prefix.'mtime');
	}
	public function setMtime($server_mtime){
		return $this->memcache->set($this->prefix.'mtime',$server_mtime,null,0);
	}
	public function purge(){
		return $this->memcache->flush();
	}
	public function incrementHit() {
		// Using Memcache::add() to prevent race if it was pushed out of memory
		$this->memcache->add($this->prefix.'hit', 0);
		$this->memcache->increment($this->prefix.'hit', 1);
	}
	public function incrementMiss() {
		// Using Memcache::add() to prevent race if it was pushed out of memory
		$this->memcache->add($this->prefix.'miss', 0);
		$this->memcache->increment($this->prefix.'miss', 1);
	}
	public function incrementError() {
		// Using Memcache::add() to prevent race if it was pushed out of memory
		$this->memcache->add($this->prefix.'error', 0);
		$this->memcache->increment($this->prefix.'error', 1);
	}
	public function setCachePrefix($prefix) {
		$this->prefix = $prefix.'_';
	}
	public function getCounters() {
		$counters = array();
		$result = $this->memcache->get(array($this->prefix.'hit', $this->prefix.'miss', $this->prefix.'error'));
		$counters['hit'] = array_key_exists($this->prefix.'hit', $result)? $result[$this->prefix.'hit']: 0;
		$counters['miss'] = array_key_exists($this->prefix.'miss', $result)? $result[$this->prefix.'miss']: 0;
		$counters['error'] = array_key_exists($this->prefix.'error', $result)? $result[$this->prefix.'error']: 0;
		$counters['age'] = $this->getReportAge();
		return $counters;
	}
	public function resetCounters() {
		$this->memcache->set($this->prefix.'hit', 0);
		$this->memcache->set($this->prefix.'miss', 0);
		$this->memcache->set($this->prefix.'error', 0);
	}
	public function resetReportAge() {
		$this->memcache->set($this->prefix.'reportTime', time());
	}
	public function getReportAge() {
		if ($last_time = $this->memcache->get($this->prefix.'reportTime')) {
			return time() - $last_time;
		}
		return 0;
	}
	public function stats(){
		// TODO: convert stats to standard format
		return $this->memcache->getStats();
	}
	public function close(){
		$this->memcache->close();
		$this->memcache = null;
	}
	public function &getMemcache() {
		return $this->memcache;
	}
}
