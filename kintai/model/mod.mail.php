<?php
// *** get all mail list ***
function getMailListAll($mysqli)
{
	$query = "SELECT user_id, user_name, email FROM user";

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

// *** get mail list for checking empty mail list ***
function getMailList($mysqli)
{
	$query = "SELECT mail_id, mail_inuse, create_date FROM mail";

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

// *** get to list ***
function getMailListTO($mailid, $mysqli)
{
	$query = "SELECT mail_to FROM mail WHERE mail_id = " . $mailid;

	$stmt = $mysqli->prepare($query);
	$stmt->execute();
	$stmt->store_result();
	$stmt->bind_result($to);
	$stmt->fetch();

	return $to;
}

// *** get cc list ***
function getMailListCC($mailid, $mysqli)
{
	$query = "SELECT mail_cc FROM mail WHERE mail_id = " . $mailid;

	$stmt = $mysqli->prepare($query);
	$stmt->execute();
	$stmt->store_result();
	$stmt->bind_result($cc);
	$stmt->fetch();

	return $cc;
}

// *** get bcc list ***
function getMailListBCC($mailid, $mysqli)
{
	$query = "SELECT mail_bcc FROM mail WHERE mail_id = " . $mailid;

	$stmt = $mysqli->prepare($query);
	$stmt->execute();
	$stmt->store_result();
	$stmt->bind_result($bcc);
	$stmt->fetch();

	return $bcc;
}

// *** create new mail ***
function insertMail($array, $mysqli)
{
	$query = "INSERT INTO mail(mail_to, mail_cc, mail_bcc, create_date, delete_flag) ";
	$query .= "VALUES('" . $array['mailto'] . "', '" . $array['mailcc'] . "', '" . $array['mailbcc'] . "', NOW(), 0)";

	$stmt = $mysqli->prepare($query);
	if ($stmt->execute()) {
		return true;
	}

	return false;
}

// *** update mail ***
function updateMail($array, $mysqli)
{
	$query = "UPDATE mail SET mail_to = '" . $array['mailto'] . "', mail_cc = '" . $array['mailcc'] . "', mail_bcc = '" . $array['mailbcc'] . "'";
	$query .= " WHERE mail_id = " . $array['mailid'];

	$stmt = $mysqli->prepare($query);
	if ($stmt->execute()) {
		return true;
	}

	return false;
}

// *** update use mail ***
function updateUseMail($array, $mysqli){
	$query = "UPDATE mail SET mail_inuse = 0 WHERE mail_inuse = 1";

	$stmt = $mysqli ->prepare($query);
	if($stmt -> execute()) {
		$query2 = "UPDATE mail SET mail_inuse = 1 WHERE mail_id = " . $array['mailid'];
		$stmt2 = $mysqli -> prepare($query2);
		if($stmt2 -> execute()) {
			return true;
		}
		return false;
	}
	return false;
}