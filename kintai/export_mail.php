<?php
session_start();
include_once ("lib/ini.setting.php");
include_once ("ini.config.php");
include_once ("lib/ini.functions.php");
include_once ("ini.dbstring.php");

include_once ("mod.select.php");
include_once ("mod.calendar.php");
include_once ("mod.attendance.php");
include_once ("ctrl.checklogin.php");
include_once ("ctrl.calendar.php");
include_once ("ctrl.attendance.php");

	
	mysql_query ("set character_set_client='utf8'"); 
	mysql_query ("set character_set_results='utf8'"); 
	mysql_query ("set collation_connection='utf8_general_ci'");
?>
<html>

<head>

<title>ThaiCreate.Com PHP(COM) Excel.Application Tutorial</title>

</head>

<body>

<?
sec_session_start();
$userid=$_SESSION['sess_user_id'];
$todaydate = explode("-", date("Y-n-j"));
$getCurrentMonth = getCurrentMonth($todaydate, $userid, $db);
$getname=getusername($userid,$db);
foreach ($getname as $result) {
						$fname=$result['user_name'];
					}
if($getCurrentMonth)
		{			
				//*** Get Document Path ***//
				$strPath = realpath(basename(getenv($_SERVER["SCRIPT_NAME"])))."/download"; // C:/AppServ/www/myphp

				//*** Excel Document Root ***//
				$strFileName = "attendance.xls";

				//*** Connect to Excel.Application ***//
				$xlApp = new COM("Excel.Application");
				$xlBook = $xlApp->Workbooks->Add();


				//*** Create Sheet 1 ***//
				$xlBook->Worksheets(1)->Name = "My attendance";							
				$xlBook->Worksheets(1)->Select;

				//*** Width & Height (A1:A1) ***//
				$xlApp->ActiveSheet->Range("A1:A1")->ColumnWidth = 10.0;
				$xlApp->ActiveSheet->Range("B1:B1")->ColumnWidth = 13.0;
				$xlApp->ActiveSheet->Range("C1:C1")->ColumnWidth = 23.0;
				$xlApp->ActiveSheet->Range("D1:D1")->ColumnWidth = 12.0;
				$xlApp->ActiveSheet->Range("E1:E1")->ColumnWidth = 13.0;
				$xlApp->ActiveSheet->Range("F1:F1")->ColumnWidth = 12.0;

				//*** Report Title ***//
				$xlApp->ActiveSheet->Range("A1:F1")->BORDERS->Weight = 1;
				$xlApp->ActiveSheet->Range("A1:F1")->MergeCells = True;
				$xlApp->ActiveSheet->Range("A1:F1")->Font->Bold = True;
				$xlApp->ActiveSheet->Range("A1:F1")->Font->Size = 20;
				$xlApp->ActiveSheet->Range("A1:F1")->HorizontalAlignment = -4108;				
				$xlApp->ActiveSheet->Cells(1,1)->Value = "Customer Report";

				//*** Header ***//				
				$xlApp->ActiveSheet->Cells(3,1)->Value = "日付";
				$xlApp->ActiveSheet->Cells(3,1)->Font->Bold = True;
				$xlApp->ActiveSheet->Cells(3,1)->VerticalAlignment = -4108; 
				$xlApp->ActiveSheet->Cells(3,1)->HorizontalAlignment = -4108; 
				$xlApp->ActiveSheet->Cells(3,1)->BORDERS->Weight = 1;
				
				$xlApp->ActiveSheet->Cells(3,2)->Value = "曜日";
				$xlApp->ActiveSheet->Cells(3,2)->Font->Bold = True;
				$xlApp->ActiveSheet->Cells(3,2)->VerticalAlignment = -4108; 
				$xlApp->ActiveSheet->Cells(3,2)->HorizontalAlignment = -4108;
				$xlApp->ActiveSheet->Cells(3,2)->BORDERS->Weight = 1;
					
				$xlApp->ActiveSheet->Cells(3,3)->Value = "出社時間";
				$xlApp->ActiveSheet->Cells(3,3)->Font->Bold = True;
				$xlApp->ActiveSheet->Cells(3,3)->VerticalAlignment = -4108;
				$xlApp->ActiveSheet->Cells(3,3)->HorizontalAlignment = -4108; 
				$xlApp->ActiveSheet->Cells(3,3)->BORDERS->Weight = 1;
				
				$xlApp->ActiveSheet->Cells(3,4)->Value = "遅刻";
				$xlApp->ActiveSheet->Cells(3,4)->Font->Bold = True;
				$xlApp->ActiveSheet->Cells(3,4)->VerticalAlignment = -4108; 
				$xlApp->ActiveSheet->Cells(3,4)->HorizontalAlignment = -4108; 
				$xlApp->ActiveSheet->Cells(3,4)->BORDERS->Weight = 1;
				
				$xlApp->ActiveSheet->Cells(3,5)->Value = "退社時間";
				$xlApp->ActiveSheet->Cells(3,5)->Font->Bold = True;
				$xlApp->ActiveSheet->Cells(3,5)->VerticalAlignment = -4108; 
				$xlApp->ActiveSheet->Cells(3,5)->HorizontalAlignment = -4108; 
				$xlApp->ActiveSheet->Cells(3,5)->BORDERS->Weight = 1;
				
				$xlApp->ActiveSheet->Cells(3,6)->Value = "早退";
				$xlApp->ActiveSheet->Cells(3,6)->Font->Bold = True;
				$xlApp->ActiveSheet->Cells(3,6)->VerticalAlignment = -4108; 
				$xlApp->ActiveSheet->Cells(3,6)->HorizontalAlignment = -4108;
				$xlApp->ActiveSheet->Cells(3,6)->BORDERS->Weight = 1;

				$xlApp->ActiveSheet->Cells(3,7)->Value = "作業時間";
				$xlApp->ActiveSheet->Cells(3,7)->Font->Bold = True;
				$xlApp->ActiveSheet->Cells(3,7)->VerticalAlignment = -4108; 
				$xlApp->ActiveSheet->Cells(3,7)->HorizontalAlignment = -4108;
				$xlApp->ActiveSheet->Cells(3,7)->BORDERS->Weight = 1;

				$xlApp->ActiveSheet->Cells(3,8)->Value = "残業時間";
				$xlApp->ActiveSheet->Cells(3,8)->Font->Bold = True;
				$xlApp->ActiveSheet->Cells(3,8)->VerticalAlignment = -4108; 
				$xlApp->ActiveSheet->Cells(3,8)->HorizontalAlignment = -4108;
				$xlApp->ActiveSheet->Cells(3,8)->BORDERS->Weight = 1;

				$xlApp->ActiveSheet->Cells(3,9)->Value = "統計時間";
				$xlApp->ActiveSheet->Cells(3,9)->Font->Bold = True;
				$xlApp->ActiveSheet->Cells(3,9)->VerticalAlignment = -4108; 
				$xlApp->ActiveSheet->Cells(3,9)->HorizontalAlignment = -4108;
				$xlApp->ActiveSheet->Cells(3,9)->BORDERS->Weight = 1;

				//***********//
			
				$intRows = 4;
				foreach ($getCurrentMonth as $row) 
				{
				
				$latetimediff = date_diff(date_create($row["attd_in_time"]), date_create("09:30"));
	$latetime = $latetimediff -> format("%H:%I");
	if ($row["attd_in_time"] != "") {
									$intime = date("H:i", strtotime($row["attd_in_time"]));
								} else {
									$intime = "-";
								}
			//echo $latetime;
	if ($row["attd_out_time"] != "") {
									$outtime = date("H:i", strtotime($row["attd_out_time"]));
								} else {
									$outtime = "-";
								}
	if ($row["attd_out_time"] != "" && $row["attd_in_time"] != "") {
									$worktimediff = date_diff(date_create($row["attd_out_time"]), date_create($row["attd_in_time"]));
									$worktime = $worktimediff -> format("%H:%I");
								} else {
									$worktime = "-";
								}
	if ($row["attd_out_time"] != "" && strtotime($row["attd_out_time"]) > strtotime("18:30")) {
									$overtimediff = date_diff(date_create($row["attd_out_time"]), date_create("18:30"));
									$overtime = $overtimediff -> format("%H:%I");
								} else {
									$overtime = "-";
								}
	if ($row["attd_out_time"] != "" && strtotime($row["attd_out_time"]) < strtotime("18:30")) {
									$earlytimediff = date_diff(date_create($row["attd_out_time"]), date_create("18:30"));
									$earlytime = $earlytimediff -> format("%H:%I");

								} else {
									$earlytime = "-";
								}
	if ($worktime != "-" && $overtime != "-") {
									$worktimecal = strtotime($worktime);
									$overtimecal = strtotime($overtime);
									$min = date("i", $overtimecal);
									$sec = date("s", $overtimecal);
									$hr = date("H", $overtimecal);

									$convert = strtotime("+$min minutes", $worktimecal);
									//$convert = strtotime("+$sec seconds", $convert);
									$convert = strtotime("+$hr hours", $convert);

									$totaltime = date("H:i", $convert);
								} else {
									$totaltime = "-";
								}
				//*** Detail ***//
				$xlApp->ActiveSheet->Cells($intRows,1)->Value = substr($row["calendar_date"], -2);
				$xlApp->ActiveSheet->Cells($intRows,1)->BORDERS->Weight = 1;
				$xlApp->ActiveSheet->Cells($intRows,1)->HorizontalAlignment = -4108;
				
				$xlApp->ActiveSheet->Cells($intRows,2)->Value = strtoupper($row["calendar_day"]);
				$xlApp->ActiveSheet->Cells($intRows,2)->BORDERS->Weight = 1;
				
				$xlApp->ActiveSheet->Cells($intRows,3)->Value = $intime;
				$xlApp->ActiveSheet->Cells($intRows,3)->BORDERS->Weight = 1;
				
				$xlApp->ActiveSheet->Cells($intRows,4)->Value = $latetime;
				$xlApp->ActiveSheet->Cells($intRows,4)->HorizontalAlignment = -4108;
				$xlApp->ActiveSheet->Cells($intRows,4)->BORDERS->Weight = 1;
				
				$xlApp->ActiveSheet->Cells($intRows,5)->Value = $outtime;
				$xlApp->ActiveSheet->Cells($intRows,5)->BORDERS->Weight = 1;
				$xlApp->ActiveSheet->Cells($intRows,5)->NumberFormat = "$#,##0.00";
				
				$xlApp->ActiveSheet->Cells($intRows,6)->Value = $earlytime;
				$xlApp->ActiveSheet->Cells($intRows,6)->BORDERS->Weight = 1;

				$xlApp->ActiveSheet->Cells($intRows,6)->Value = $worktime;
				$xlApp->ActiveSheet->Cells($intRows,6)->BORDERS->Weight = 1;

				$xlApp->ActiveSheet->Cells($intRows,6)->Value = $overtime;
				$xlApp->ActiveSheet->Cells($intRows,6)->BORDERS->Weight = 1;

				$xlApp->ActiveSheet->Cells($intRows,6)->Value = $totaltime;
				$xlApp->ActiveSheet->Cells($intRows,6)->BORDERS->Weight = 1;

				$intRows++;
				}				
								
				@unlink($strFileName); //*** Delete old files ***//	

				$xlBook->SaveAs($strPath."/".$strFileName); //*** Save to Path ***//

				//*** Close & Quit ***//
				$xlApp->Application->Quit();
				$xlApp = null;
				$xlBook = null;
				$xlSheet1 = null;

		}

			


		//*************** Send Email ***************//
	/*$mailto="zinmonthet@rubbersoul.co.jp";
	$my_subject = "This is a mail with attachment.";
	$filename="attendance.xls";
	$file = "download/attendance.xls";
    $file_size = filesize($file);
    $handle = fopen($file, "r");
    $content = fread($handle, $file_size);
    fclose($handle);
    $content = chunk_split(base64_encode(file_get_contents($content)));
    $uid = md5(uniqid(time()));
    $name = basename($file);
    $header = "From: ".$from_name." <".$from_mail.">\r\n";
    $header .= "Reply-To: ".$replyto."\r\n";
    $header .= "MIME-Version: 1.0\r\n";
    $header .= "Content-Type: multipart/mixed; boundary=\"".$uid."\"\r\n\r\n";
    $header .= "This is a multi-part message in MIME format.\r\n";
    $header .= "--".$uid."\r\n";
    $header .= "Content-type:text/plain; charset=iso-8859-1\r\n";
    $header .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
    $header .= $message."\r\n\r\n";
    $header .= "--".$uid."\r\n";
    $header .= "Content-Type: application/octet-stream; name=\"".$filename."\"\r\n"; // use different content types here
    $header .= "Content-Transfer-Encoding: base64\r\n";
    $header .= "Content-Disposition: attachment; filename=\"".$filename."\"\r\n\r\n";
    $header .= $content."\r\n\r\n";
    $header .= "--".$uid."--";
    if (mail($mailto, $subject, "attendance", $header)) {
        echo "mail send ... OK"; // or use booleans here
    } else {
        echo "mail send ... ERROR!";
    }*/
	$message="sending attachment file";
	$path="download/";
	$filename="attendance.xls";
	$file = $path.$filename;
    $file_size = filesize($file);
    $handle = fopen($file, "r");
    $content = fread($handle, $file_size);
    fclose($handle);
    $content = chunk_split(base64_encode(file_get_contents($content)));
    $uid = md5(uniqid(time()));
    $name = basename($file);
    $header = "From: "."user@gmail.com"." <"."user@gmail.com".">\r\n";
    $header .= "Reply-To: "."user@gmail.com"."\r\n";
    $header .= "MIME-Version: 1.0\r\n";
    $header .= "Content-Type: multipart/mixed; boundary=\"".$uid."\"\r\n\r\n";
    $header .= "This is a multi-part message in MIME format.\r\n";
    $header .= "--".$uid."\r\n";
    $header .= "Content-type:text/plain; charset=iso-8859-1\r\n";
    $header .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
    $header .= $message."\r\n\r\n";
    $header .= "--".$uid."\r\n";
    $header .= "Content-Type: application/octet-stream; name=\"".$filename."\"\r\n"; // use different content types here
    $header .= "Content-Transfer-Encoding: base64\r\n";
    $header .= "Content-Disposition: attachment; filename=\"".$filename."\"\r\n\r\n";
    $header .= $content."\r\n\r\n";
    $header .= "--".$uid."--";
    if (mail('zinmonthet@rubbersoul.co.jp', $subject, "", $header)) {
        echo "mail send ... OK"; // or use booleans here
    } else {
        echo "mail send ... ERROR!";
    }
?>

</body>

</html>