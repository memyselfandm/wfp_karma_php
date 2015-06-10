<?php
class Karma
{
    function Karma(){
		$this->stations = FALSE;	
	}
	
	function loadStations(){
		exec("iw dev wlan0 station dump > stadump"); //dump all stations connected

		$this->stations = array(); //reset this
		
		$stations = array();
		
		exec('cat stadump | grep -e "Station" | cut -f 2 -d" "', $stations);
		
		$station_data = array();
		
		foreach($stations as $sta){
			$station_data[$sta] = array(
               "ip" => $this->_get_ip($sta),
               "ssid" => $this->_get_ssid($sta),
               "hostname" => $this->_get_hostname($sta)
	    	);
	    }
		
	    $this->stations = $station_data;
	}
	
	function getStations(){
		if(empty($this->stations)){
			$this->loadStations();
		}
		
		return $this->stations;
	}
	
	function get_ssid_by_ip($ip){
		$stations = $this->getStations();
		
		foreach($stations as $sta){
			if($sta['ip'] == $ip){
				return $sta['ssid'];
			}
		}
		
		return FALSE;
	}
	
	function _get_ip($sta){
		//grab the IP corresponding with the bssid in the DHCP lease file
		$cmd = "cat /tmp/dhcp.leases | grep \"{$sta}\" | awk '{print $3}'";
		//print($cmd . "\n");
		$ip = exec($cmd);
		//print("ip: {$ip} \n"); 
		
		return $ip;
	}
	
	
	function _get_ssid($sta){
		//grab the Karma'd SSID corresponding to the bssid in the karma log
		$cmd = "cat /tmp/karma.log | grep \"{$sta}\" | grep SSID | tail -1 | awk '{print $8}'";
		//print($cmd . "\n");
		$ssid = exec($cmd);
		//print("ssid: {$ssid} \n");
		
		return $ssid;
	}
	
	function _get_hostname($sta){
		//grap the hostname corresponding with the bssid in the DHCP lease file
		$cmd = "cat /tmp/dhcp.leases | grep \"{$sta}\" | awk '{print $4}'";
		//print($cmd . "\n");
		$hostname = exec($cmd);
		//print("hostname: {$hostname} \n");
		
		return $hostname;
	}		
}	
