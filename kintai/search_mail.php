<?php
include_once ("lib/ini.setting.php");
include_once ("ini.config.php");
include_once ("lib/ini.functions.php");
include_once ("ini.dbstring.php");

include_once ("mod.select.php");
include_once ("mod.calendar.php");
include_once ("mod.attendance.php");
include_once ("ctrl.calendar.php");
include_once ("ctrl.attendance.php");
//error_reporting(E_ALL);
//ini_set( 'display_errors','1');
sec_session_start();
$yr['0']=$_SESSION['year'];
$yr['1']=$_SESSION['month'];
//echo $yr['1'];
$out_limit= $_SESSION['sess_user_outtime'];
$in_limit=$_SESSION['sess_user_intime'];
$to=$_SESSION['sess_user_id'];
$rsesult=getmail($to,$db);
foreach ($rsesult as $row) {
	$tosend=$row['email'];
	//echo $tosend;

}
$getname=getusername($_SESSION['sess_user_id'],$db);
foreach ($getname as $result) {
						$fname=$result['user_name'];
						$eid=$result['user_eid'];
						//echo $eid;
					}
					$getCurrentMonth = getCurrentMonth($yr,$_SESSION['sess_user_id'], $db);
                    $to_mail= implode(", ", MailList(getHeaders(1, $db), 'to', $db));

                    $to_cc= implode(", ", MailList(getHeaders(1, $db), 'cc', $db));

                    $to_bcc= implode(", ", MailList(getHeaders(1, $db), 'bcc', $db));
					$body='<html>
							<head><title></title>
							<style>
								td{text-align:center; border-bottom: 1px dotted #514F4F;}
								table{width:900px;}
								span{color:red;}
								th{border-top: 1px solid #514F4F;border-bottom: 1px solid #514F4F;background: #514F4F;color: #E1E1E1;}
							</style>
							</head>
								<body>';
					$body.='User Name: '.$fname.'<br>';
					$body.='User Id: '.$eid.'<br>';
					$body.='<table>
									<tr>
										<th>日付</th>
										<th>曜日</th>
										<th>出社時間</th>
										<th>退社時間</th>
										<th>遅刻</th>
										<th>早退</th>
										<th>作業時間</th>
										<th>残業時間</th>
										<th>統計時間</th>
									</tr>';
								
							for ($r = 0; $r < count($getCurrentMonth); $r++) {
								// extract only current logged in user's data
								$overtime = "";
                                $worktime = "";
								$totaltime = "";
								$intime = "";
								$outtime = "";
								$latetime = "";
//								$in=date("H:i:s", strtotime($getCurrentMonth[$r]["attd_in_time"]));
//								$out=date("H:i:s", strtotime($getCurrentMonth[$r]["attd_out_time"]));
                                $in=$getCurrentMonth[$r]["attd_in_time"];
                                $out=$getCurrentMonth[$r]["attd_out_time"];
								if ($getCurrentMonth[$r]["attd_in_time"] != "") {
									//$intime = date("H:i:s", strtotime($getCurrentMonth[$r]["attd_in_time"]));
                                    $intime =$getCurrentMonth[$r]["attd_in_time"];
								} else {
									$intime = "-";
								}
								
								if ($getCurrentMonth[$r]["attd_out_time"] != "" && $getCurrentMonth[$r]["attd_out_time"] != "00:00:00") {
									//$outtime = date("H:i:s", strtotime($getCurrentMonth[$r]["attd_out_time"]));
                                    $outtime = $getCurrentMonth[$r]["attd_out_time"];
								} else {
									$outtime = "-";
								}

								if ($getCurrentMonth[$r]["calendar_status"] == 0 && $getCurrentMonth[$r]["calendar_day"] == "sun") {
									$class = "offSun";
								} else if ($getCurrentMonth[$r]["calendar_status"] == 0 && $getCurrentMonth[$r]["calendar_day"] == "sat") {
									$class = "offSat";
								} else if ($getCurrentMonth[$r]["calendar_status"] == 0) {
									$class = "offHoliday";
								} else {
									$class = "";
								}

								// calculate worktime
                                if ($getCurrentMonth[$r]["attd_out_time"] != ""
                                    && $getCurrentMonth[$r]["attd_in_time"] != ""
                                    && $getCurrentMonth[$r]["attd_out_time"] != "00:00:00") {
                                    list($hours, $minutes, $sec) = explode(':', $in);
                                    $startTimestamp = mktime($hours, $minutes, $sec);

                                    list($hours, $minutes, $sec) = explode(':', $out);
                                    $endTimestamp = mktime($hours, $minutes, $sec);

                                    $seconds = $endTimestamp - $startTimestamp;
                                    $hours = floor($seconds / 3600);
                                    $minutes = floor(($seconds / 60) % 60);
                                    $seconds = $seconds % 60;

                                    $worktime = $hours.":".$minutes.":".$seconds;
                                } else {
                                    $worktime = "-";
                                }

								// calculate overtime
                                if ($getCurrentMonth[$r]["attd_out_time"] != ""
                                    && $getCurrentMonth[$r]["attd_out_time"] > $out_limit
                                    && $getCurrentMonth[$r]["attd_out_time"] != "00:00:00") {
                                    $limit_ot = $out_limit;

                                    list($hours, $minutes, $sec) = explode(':', $out);
                                    $startTimestamp = mktime($hours, $minutes, $sec);

                                    list($hours, $minutes, $sec) = explode(':', $limit_ot);
                                    $endTimestamp = mktime($hours, $minutes, $sec);

                                    $seconds = $startTimestamp - $endTimestamp;
//                            $sec = $seconds % 60;
//                            $minutes = ($seconds / 60) % 60;
//                            $hours = round($seconds / (60 * 60));
                                    $hours = floor($seconds / 3600);
                                    $minutes = floor(($seconds / 60) % 60);
                                    $seconds = $seconds % 60;

                                    $overtime = $hours . ":" . $minutes . ":" . $seconds;
                                } else {
                                    $overtime = "-";
                                }
								
								// calculate late time
                                if ($getCurrentMonth[$r]["attd_in_time"] != "" && strtotime($getCurrentMonth[$r]["attd_in_time"]) >= strtotime($in_limit)) {
                                    $limit = $in_limit;

                                    list($hours, $minutes, $sec) = explode(':', $in);
                                    $startTimestamp = mktime($hours, $minutes, $sec);

                                    list($hours, $minutes, $sec) = explode(':', $limit);
                                    $endTimestamp = mktime($hours, $minutes, $sec);

                                    $seconds = $startTimestamp - $endTimestamp;
                                    // echo $seconds;
                                    $hours = floor($seconds / 3600);
                                    $minutes = floor(($seconds / 60) % 60);
                                    $seconds = $seconds % 60;
                                    //echo $hours . ":" . $minutes . ":" . $seconds;
//                            $sec = $seconds % 60;
//                            $minutes = ($seconds / 60) % 60;
//                            $hours = round($seconds / (60 * 60));
                                    $latetime = $hours . ":" . $minutes . ":" . $seconds;

                                } else {
                                    $latetime = "-";
                                }


                                // calculate early time
                                if ($getCurrentMonth[$r]["attd_out_time"] != "" && $getCurrentMonth[$r]["attd_out_time"] < $out_limit && $getCurrentMonth[$r]["attd_out_time"] != "00:00:00") {
                                    $limit = $out_limit;
                                    list($hours, $minutes, $sec) = explode(':', $out);
                                    $startTimestamp = mktime($hours, $minutes, $sec);

                                    list($hours, $minutes, $sec) = explode(':', $limit);
                                    $endTimestamp = mktime($hours, $minutes, $sec);

                                    $seconds = $endTimestamp - $startTimestamp;
                                    $hours = floor($seconds / 3600);
                                    $minutes = floor(($seconds / 60) % 60);
                                    $seconds = $seconds % 60;
                                    $earlytime = $hours . ":" . $minutes . ":" . $seconds;
                                } else {
                                    $earlytime = "-";
                                }

                                // calculate totaltime
                                if ($worktime != "-" || $overtime != "-") {
                                    $totaltime = $worktime;
                                } else {
                                    $totaltime = "-";
                                }

                                // calculate total worktime
                                if($worktime != "-") {
                                    list($h, $m, $s) = explode(':', $worktime);
                                    $ht += $h * 3600;
                                    $mt += $m * 60;
                                    $st += $s;
                                }

                                // calculate total overtime
                                if($overtime != "-") {
                                    list($ho, $mo, $so) = explode(':', $overtime);
                                    $hto += $ho * 3600;
                                    $mto += $mo * 60;
                                    $sto += $so;
                                }

                                $body.='<tr>';
								$body.='<td>'.substr($getCurrentMonth[$r]["calendar_date"], -2).'</td>';
								$body.='<td>'.strtoupper($getCurrentMonth[$r]["calendar_day"]).'</td>';
								$body.='<td>'.$intime.'</td>';
                                $body.='<td>'.$outtime.'</td>';
                                $body.='<td><span>'.$latetime.'</span></td>';
                                $body.='<td><span>'.$earlytime.'</span></td>';
                                $body.='<td>'.$worktime.'</td>';
                                $body.='<td>'.$overtime.'</td>';
                                $body.='<td>'.$totaltime.'</td>';
								$body.='</tr>';

                                $ttimestamp = $ht + $mt + $st;
                                $th = floor($ttimestamp / 3600);
                                $tm = floor(($ttimestamp / 60) % 60);
                                $ts = $ttimestamp % 60;

                                $ottimestamp = $hto + $mto + $sto;
                                $oth = floor($ottimestamp / 3600);
                                $otm = floor(($ottimestamp / 60) % 60);
                                $ots = $ottimestamp % 60;
							
							}
                                $body.='<tr>
                                                    <td colspan="5"></td>
                                                    <td>統計時間</td>
                                                    <td>';
                                $body.=$th . ":" . $tm . ":" . $ts."<br/>";
                                $body.='<span>残業時間含み</span>';
                                $body.='</td>
                                                    <td>';
                                $body.=$oth . ":" . $otm . ":" . $ots.'<br/>';
                                $body.='<span>残業時間</span>';
                                $body.='</td>
                                                    <td></td>
                                                </tr>';
								$body.='</table>
								</body>
							</html>';

$from_name=$tosend;

$headers = "MIME-Version: 1.0 \n";
$headers .= "TO: ".$to_mail."\r\n";
$headers .= 'From:' . $from_name . "\r\n";
if($to_cc!="" or $to_bcc!=""){
    $headers .= "CC: ".$to_cc."\r\n";
    $headers .= "BCC: ".$to_bcc."\r\n";
}
$headers .= "Content-type: text/html;charset=ISO-2022-JP \n";

    
/* Convert body to same encoding as stated 
in Content-Type header above */
    
$body = mb_convert_encoding($body, "ISO-2022-JP","AUTO");
    
/* Mail, optional paramiters. */
$subject="Attendance of ".$fname;
    
mb_language("ja");
$subject = mb_convert_encoding($subject, "ISO-2022-JP","AUTO");
$subject = mb_encode_mimeheader($subject);

if (mail($to_mail, $subject, $body, $headers))
	{
        echo "<script>window.location.href='history.php?msg=1';</script>";
		//header("Location:history.php?msg=1");
	}
					
?>