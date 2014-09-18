<?php
if(isset($_POST['newmail'])) {
	if(empty($_POST['mailTO'])) {
		header("location: " . ROOT . "admin/mail_create.php?st=noto");
		exit;
	}

	$mailTO = empty($_POST['mailTO'])?"":implode(":", $_POST['mailTO']);
	$mailCC = empty($_POST['mailCC'])?"":implode(":", $_POST['mailCC']);
	$mailBCC = empty($_POST['mailBCC'])?"":implode(":", $_POST['mailBCC']);

	$data['mailto'] = $mailTO;
	$data['mailcc'] = $mailCC;
	$data['mailbcc'] = $mailBCC;

	if(!insertMail($data, $db)) {
		header("location: " . ROOT . "error.html");
		exit;
	}
}

// *** save use mail template ***
if(isset($_POST['use'])) {
	if(isset($_POST['use'])) {
		$data['mailid'] = $_POST['maillist'];

		if(updateUseMail($data, $db)) {
			header("location: " . ROOT . "admin/mail_create.php");
			exit;
		}
	}
}

if(isset($_POST['mailsetup'])) {
	if(empty($_POST['mailTO'])) {
		header("location: " . ROOT . "admin/mail_setup.php");
		exit;
	}

	$mailTO = empty($_POST['mailTO'])?"":implode(":", $_POST['mailTO']);
	$mailCC = empty($_POST['mailCC'])?"":implode(":", $_POST['mailCC']);
	$mailBCC = empty($_POST['mailBCC'])?"":implode(":", $_POST['mailBCC']);

	$data['mailid'] = trim($_POST['mailid']);
	$data['mailto'] = $mailTO;
	$data['mailcc'] = $mailCC;
	$data['mailbcc'] = $mailBCC;

	if(!updateMail($data, $db)) {
		header("location: " . ROOT . "error.html");
		exit;
	}
}

$getMailList = getMailList($db);
$getMailListAll = getMailListAll($db);