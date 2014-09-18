<?php
## Website configuration file
//web root
define("PRJ_NAME", "kintai"); //project root name
define("ROOT", "/" . PRJ_NAME . "/");
define("CSS", "/" . PRJ_NAME . "/css");
define("IMG", "/" . PRJ_NAME . "/images");
define("JS", "/" . PRJ_NAME . "/js");
define("INC", "/" . PRJ_NAME . "/include");
define("MOD", "/" . PRJ_NAME . "/model/");
define("INI", "/" . PRJ_NAME . "/config");
define("CTRL", "/" . PRJ_NAME . "/controller/");

//Testing server variables, remove comments
define("DBHOST", "localhost");
define("DBNAME", "kintai");
define("DBUSER", "root");
define("DBPASS", "");

//Hosting server variables, remove comments
#define("DBHOST", "host");
#define("DBNAME", "dbname");
#define("DBUSER", "dbusername");
#define("DBPASS", "dbpassword");

$mm = array("1.4:Independence Day", 
				"2.12:Union Day", 
				"3.2:Peasant Days", 
				"3.27:Full Moon of Tabaung", 
				"4.13:Thingyan Festival", 
				"4.14:Thingyan Festival", 
				"4.15:Thingyan Festival", 
				"4.16:Thingyan Festival", 
				"4.17:Burmese New Year", 
				"5.1:Labour Day", 
				"5.24:Full Moon of Kason", 
				"7.19:Martyrs\'s Day", 
				"7.22:Start of Buddhist Lent", 
				"10.19:End of Buddhist Lent", 
				"11.17:Full Moon of Tazaungmon", 
				"11.27:National Day", 
				"12.25:Chrismas Day");
				
$month = 12;
	
$dayspermonth = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
	
$weekdaytype = array("weekend", 
					"weekday");
						
$daysname = array("1" => "sat", 
					"2" => "sun", 
					"3" => "mon", 
					"4" => "tue", 
					"5" => "wed", 
					"6" => "thu", 
					"7" => "fri");
					
$monthsname = array("January", 
					"February", 
					"March", 
					"April", 
					"May", 
					"Jun", 
					"July", 
					"August", 
					"September", 
					"October", 
					"November", 
					"December");

$monthsnameshort = array("jan", 
						"feb", 
						"mar", 
						"apr", 
						"may", 
						"jun", 
						"jul", 
						"aug", 
						"sep", 
						"oct", 
						"nov", 
						"dec");

$monthsbyno = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12);