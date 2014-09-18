<?php
if(isset($_REQUEST['location']))
{
$location=$_REQUEST['location'];
if($location=='Myanmar')
	{
		date_default_timezone_set("Asia/Rangoon");
		echo date("Y/m/d H:i:s");
	}
	if($location=='Japan')
	{
		date_default_timezone_set("Asia/Tokyo");
		echo date("Y/m/d H:i:s");
	}
	if($location=='China')
	{
		echo "China";
	}
	if($location=='Vietnum')
	{
		echo "Vietnum";
	}
}
?>