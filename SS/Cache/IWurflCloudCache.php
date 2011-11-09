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
interface IWurflCloudCache {
	/**
	 * Get the device capabilities for the given user agent from the cache provider
	 * @param string $key User Agent
	 * @return array|boolean Capabilities array or boolean false
	 */
	public function getDevice($key);
	/**
	 * Get the device capabilities for the given device ID from the cache provider
	 * @param string $key WURFL Device ID
	 * @return array|boolean Capabilities array or boolean false
	 */
	public function getDeviceFromID($key);
	
	/**
	 * Stores the given user agent with the given device capabilities in the cache provider for the given time period
	 * @param string $key User Agent
	 * @param array $value Capabilities
	 * @return boolean Success
	 */
	public function setDevice($key,$value);
	/**
	 * Stores the given user agent with the given device capabilities in the cache provider for the given time period
	 * @param string $key WURFL Device ID
	 * @param array $value Capabilities
	 * @return boolean Success
	 */
	public function setDeviceFromID($key,$value);
	/**
	 * Gets the last loaded WURFL timestamp from the cache provider - this is used to detect when a new WURFL has been loaded on the server 
	 * @return int Loaded WURFL unix timestamp
	 */
	public function getMtime();
	/**
	 * Sets the last loaded WURFL timestamp in the cache provider
	 * @param int $server_mtime Loaded WURFL unix timestamp
	 * @see IWurflCloudCache::getMtime()
	 */
	public function setMtime($server_mtime);
	/**
	 * Deletes all the cached devices and the mtime from the cache provider
	 */
	public function purge();
	/**
	 * Increments the count of cache hits
	 */
	public function incrementHit();
	/**
	 * Increments the count of cache misses
	 */
	public function incrementMiss();
	/**
	 * Increments the count of errors
	 */
	public function incrementError();
	/**
	 * Returns an array of all the counters
	 * @return array
	 */
	public function getCounters();
	/**
	 * Resets the counters to zero
	 */
	public function resetCounters();
	/**
	 * Returns the number of seconds since the counters report was last sent
	 * @return int
	 */
	public function getReportAge();
	/**
	 * Resets the report age to zero
	 */
	public function resetReportAge();
	/**
	 * Gets statistics from the cache provider like memory usage and number of cached devices
	 * @return array Cache statistics
	 */
	public function stats();
	/**
	 * Closes the connection to the cache provider
	 */
	public function close();
}