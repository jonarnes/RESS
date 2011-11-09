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
class WurflCloudCache_Null implements IWurflCloudCache {

	public $cache_expiration = 0;
	public $cache_expiration_rand_max = 0;
	public function getDevice($user_agent) {
		return false;
	}
	public function getDeviceFromID($device_id) {
		return false;
	}
	public function setDevice($user_agent, $capabilities) {
		return true;
	}
	public function setDeviceFromID($device_id, $capabilities) {
                return true;
        }
	public function getMtime() {
		return 0;
	}
	public function setMtime($server_mtime) {
		return true;
	}
	public function purge(){
		return true;
	}
	public function incrementHit() {}
	public function incrementMiss() {}
	public function incrementError() {}
	public function getCounters() {
		$counters = array(
			'hit' => 0,
			'miss' => 0,
			'error' => 0,
			'age' => 0,
		);
		return $counters;
	}
	public function resetCounters() {}
	public function resetReportAge() {}
	public function getReportAge() {
		return 0;
	}
	public function stats() {
		// TODO: convert stats to standard format
		return array();
	}
	public function close(){}
}
