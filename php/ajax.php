<?php
	require_once 'lib.php';			// The Application

	$ReturnData = '';
	switch ($_POST['FUNCTION']) {
		case 'FindPhones':
			$ReturnData = FindPelatisPhones($_POST['DATASTRING']);
			break;
	}

	echo $ReturnData;
?>
