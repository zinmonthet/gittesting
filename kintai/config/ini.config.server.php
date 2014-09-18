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
define(DBHOST, "10.1.11.190");
define(DBNAME, "db280315_db_test");
define(DBUSER, "u280315_db_test");
define(DBPASS, "ibial333+homam");
define(LIMITYEARS,10);
define(MAX_LENGTH, 6);

$ymd = array(
	0 => array(
		"mnamelong" => "january",
		"mnameshort" => "jan",
		"days" => 31,
		"mindex" => 1
	),
	1 => array(
		"mnamelong" => "february",
		"mnameshort" => "feb",
		"days" => 28,
		"mindex" => 2
	),
	2 => array(
		"mnamelong" => "march",
		"mnameshort" => "mar",
		"days" => 31,
		"mindex" => 3
	),
	3 => array(
		"mnamelong" => "april",
		"mnameshort" => "apr",
		"days" => 30,
		"mindex" => 4
	),
	4 => array(
		"mnamelong" => "may",
		"mnameshort" => "may",
		"days" => 31,
		"mindex" => 5
	),
	5 => array(
		"mnamelong" => "june",
		"mnameshort" => "jun",
		"days" => 30,
		"mindex" => 6
	),
	6 => array(
		"mnamelong" => "july",
		"mnameshort" => "jul",
		"days" => 31,
		"mindex" => 7
	),
	7 => array(
		"mnamelong" => "august",
		"mnameshort" => "aug",
		"days" => 31,
		"mindex" => 8
	),
	8 => array(
		"mnamelong" => "september",
		"mnameshort" => "sep",
		"days" => 30,
		"mindex" => 9
	),
	9 => array(
		"mnamelong" => "october",
		"mnameshort" => "oct",
		"days" => 31,
		"mindex" => 10
	),
	10 => array(
		"mnamelong" => "november",
		"mnameshort" => "nov",
		"days" => 30,
		"mindex" => 11
	),
	11 => array(
		"mnamelong" => "december",
		"mnameshort" => "dec",
		"days" => 31,
		"mindex" => 12
	)
);

/*$mm = array("1.4:Independence Day",
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
				"12.25:Chrismas Day");*/
				
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

$daysnamejp = array("1" => "土",
    "2" => "日",
    "3" => "月",
    "4" => "火",
    "5" => "水",
    "6" => "目",
    "7" => "金");
					
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