<?php
include_once("../lib/ini.setting.php");
include_once("ini.dbstring.php");
include_once("ini.functions.php");

include_once("mod.login.php");
include_once("mod.user.php");
include_once("../Classes/PHPExcel/IOFactory.php");

ob_start();
sec_session_start();

// Import and insert
if (isset($_POST['cmd']) && $_POST['cmd'] == "import") {

	// Store variables
	$inputFileName = $_FILES['uploadFile']['tmp_name'];
	$op_file_type = $_FILES['uploadFile']['type'];
	$array = Array();

	// Clear session first
	$_SESSION['cmd'] = "";
	$_SESSION['cmd']['err'] = "";

	// Validate
	($inputFileName == "" || $op_file_type != "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet") ? $_SESSION['cmd']['err']['op_filename'] = 1 : $_SESSION['cmd']['data']['op_filename'] = $inputFileName;

	// Has errors and redirect with errors
	if (!empty($_SESSION['cmd']['err'])) {
		$_SESSION['cmd']['err'] = "error";
		header("location: " . ROOT . "admin/user_add.php");
		exit;
	}

	try {
		$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
		$objReader = PHPExcel_IOFactory::createReader($inputFileType);
		$objPHPExcel = $objReader->load($inputFileName);
		$objReader->setReadDataOnly(true);
	} catch (Exception $e) {
		die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME) . '": ' . $e->getMessage());
	}

	$objWorkSheet = $objPHPExcel->getActiveSheet();
	$objWorkSheet = $objPHPExcel->setActiveSheetIndex(0);

	// Iterate through rows
	foreach ($objWorkSheet->getRowIterator() as $row) {
		$cellIterator = $row->getCellIterator();
		$cellIterator->setIterateOnlyExistingCells(false);
		$rowIndex = $row->getRowIndex();

		// Skip first row of titles
		if ($rowIndex == 1) {
			continue;
		}

		//$rowIndex = $rowIndex - 1;
		$rowIndex = $rowIndex - 2;

		// Iterate through cells
		foreach ($cellIterator as $cell) {
			// Break the loop if cell is Empty or Null
			if (is_null($cell->getCalculatedValue()) || $cell->getCalculatedValue() == "") {
				break;
			}
			if ('A' == $cell->getColumn()) {
				$array[$rowIndex]['user_name'] = $cell->getCalculatedValue();
			} else if ('B' == $cell->getColumn()) {
				$branch = $cell->getCalculatedValue();
				$generate_id = uniqid(rand());
				if ($branch == 1) {
					$id_generate = "JP" . substr($generate_id, -6);
					$id_generate = strtoupper($id_generate);
				} else if ($branch == 2) {
					$id_generate = "CH" . substr($generate_id, -6);
					$id_generate = strtoupper($id_generate);
				} else if ($branch == 3) {
					$id_generate = "VN" . substr($generate_id, -6);
					$id_generate = strtoupper($id_generate);
				} else if ($branch == 4) {
					$id_generate = "MM" . substr($generate_id, -6);
					$id_generate = strtoupper($id_generate);
				}
				$array[$rowIndex]['user_eid'] = $id_generate;

			} else if ('C' == $cell->getColumn()) {
				$array[$rowIndex]['email'] = $cell->getCalculatedValue();
			} else if ('D' == $cell->getColumn()) {
				$array[$rowIndex]['department'] = $cell->getCalculatedValue();
			} else if ('E' == $cell->getColumn()) {
                $array[$rowIndex]['user_role'] = $cell->getCalculatedValue();
            } else if ('F' == $cell->getColumn()) {
                $array[$rowIndex]['group_id'] = $cell->getCalculatedValue();
            }

			$password = "em123";
			$password = hash("sha256", $password);
		//	define("MAX_LENGTH", 6);
			$intermediateSalt = md5(uniqid(rand(), true));
			$salt = substr($intermediateSalt, 0, MAX_LENGTH);
			$user_password = hash("sha256", $password . $salt);
			$array[$rowIndex]['user_password'] = $user_password;
			$array[$rowIndex]['user_salt'] = $salt;
		}
	}

	// Data insert successful or not
	if (insertUsersBatch($array, $db)) {
		$_SESSION['cmd']['err'] = "success";
		header("location: " . ROOT . "admin/user_add.php");
		exit;
	} else {
		$_SESSION['cmd']['err'] = "error";
		header("location: " . ROOT . "admin/user_add.php");
		exit;
	}
}