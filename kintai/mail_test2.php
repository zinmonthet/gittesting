<?php
include_once("lib/ini.setting.php");
include_once("ini.config.php");
include_once("ini.dbstring.php");
include_once("ini.functions.php");

function getHeaders($mailid, $mysqli)
{
	$query = "SELECT mail_to, mail_cc, mail_bcc FROM mail WHERE mail_id = $mailid LIMIT 0,1";

	if ($stmt = $mysqli->query($query)) {
		if ($stmt->num_rows > 0) {
			while ($result = $stmt->fetch_assoc()) {
				$data[] = $result;
			}
		} else {
			$data = "";
		}
	}
	return $data;
}

function getMailEach($userid, $mysqli)
{
	$query = "SELECT email FROM user WHERE user_id = " . $userid;

	$stmt = $mysqli->prepare($query);
	$stmt->execute();
	$stmt->store_result();
	$stmt->bind_result($m);
	$stmt->fetch();

	return $m;
}

function getMail($to, $ext = 'to', $mysqli)
{
	if ($ext == 'to') {
		$ml = explode(":", $to[0]['mail_to']);
	} else if ($ext == 'cc') {
		$ml = explode(":", $to[0]['mail_cc']);
	} else if ($ext == 'bcc') {
		$ml = explode(":", $to[0]['mail_bcc']);
	}

	$mlarr = array();
	for ($i = 0; $i < count($ml); $i++) {
		$mlarr[] = getMailEach($ml[$i], $mysqli);
	}

	return $mlarr;
}

echo "TO: " . implode(", ", getMail(getHeaders(8, $db), 'to', $db));
echo "<br/>";
echo "CC: " . implode(", ", getMail(getHeaders(8, $db), 'cc', $db));
echo "<br/>";
echo "BCC: " . implode(", ", getMail(getHeaders(8, $db), 'bcc', $db));




