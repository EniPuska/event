<?php
/* ----------------------------------------------------- */
/*
	VERSION			: 1.0
	Created By	: Μιχάλης Αλεξανδρόπουλος
	Copyright		: 2014-2017
	INFO				: Κλάση για τη σύνδεση με τη Βάση Δεδομένων (Data Base)
	All Rights Reserved

	Η παρακάτω κλάση συνδέεται είτε με MySQL είτε με SQLite Βάσεις Δεδομένων.
	Αν δεν μπορεί να συνδεθεί επιστρέφει το κωδικό λάθους και τελειώνει το κώδικά εκεί (die).

	Αν συνδεθεί εκτελεί την SQL εντολή που του έχουμε περάσει.
	Αν η εντολή δεν μπορεί να εκτελεστεί επιστρέφει το μήνυμα λάθους.

	Για να διαβάσουμε τις εγγραφές χρησιμοποιούμε
	MySQL:	$DATASET['Data']->fetch_assoc()
	SQLite:	$DATASET['Data']->fetchArray()

	Για MySQL χρησιμοποιούμε το παρακάτω παράδειγμα
		$Data = new DBCON();
		$DATASET = $Data->RunQuery('SELECT * FROM tbl_test','MySQL');
		//ΣΗΜΕΙΩΣΗ: Η δεύτερη μεταβλητή μπορεί να μην γραφεί οπότε και δέχεται τη αρχική ΣΤΑΘΕΡΆ που έχουμε δηλώσει στο SQL_ENGINE.
		//$DATASET = $Data->RunQuery('SELECT * FROM tbl_test');

		if (!$DATASET['Error']) {
			while ($DATA_ROW = $DATASET['Data']->fetch_assoc()){
				echo $DATA_ROW['Code'].' ---> '.$DATA_ROW['Perig'].'<br>';
			}
			unset($DATASET);
		} else {
			echo $DATASET['Data'].'<br>';
		}


	Για SQLite χρησιμοποιούμε το παρακάτω παράδειγμα
		$Data = new DBCON();
		$DATASET = $Data->RunQuery('SELECT * FROM tbl_test','SQLite');
		//ΣΗΜΕΙΩΣΗ: Η δεύτερη μεταβλητή μπορεί να μην γραφεί οπότε και δέχεται τη αρχική ΣΤΑΘΕΡΆ που έχουμε δηλώσει στο SQL_ENGINE.
		//$DATASET = $Data->RunQuery('SELECT * FROM tbl_test');

		if (!$DATASET['Error']) {
			while ($DATA_ROW = $DATASET['Data']->fetchArray()){
				echo $DATA_ROW['Code'].' ---> '.$DATA_ROW['Perig'].'<br>';
			}
			unset($DATASET);
		} else {
			echo $DATASET['Data'].'<br>';
		}
*/
/* ----------------------------------------------------- */
class DBCON {
	/* Σύνδεση σε MySQL Βάση Δεδομένων */
	private function MySQLConnect(){
		$MySQL_DB = new mysqli(DB_HOSTNAME,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
		if (mysqli_connect_errno()) {
			echo 'Κωδικός Λάθους : '.mysqli_connect_errno().'<br>';
			echo 'Μήνυμα Λάθους : '.mysqli_error().'<br>';
			echo 'Επικοινωνία με τη Βάση Δεδομένων ΔΕΝ ήταν εφικτή<br>';
			die;
		}
		if (!$MySQL_DB->set_charset('utf8')) {
			echo 'Βάση Δεδομένων ΔΕΝ μπόρεσε να γίνει utf8<br>'.$MySQL_DB->error;
			die;
		}
		return $MySQL_DB;
	}
	/* Σύνδεση σε SQLite Βάση Δεδομένων */
	private function SQLiteConnect(){
		if(!class_exists('SQLite3')) {
			echo 'SQLite3 Δεν φαίνεται να είναι ενεργή<br>';
			echo 'Παρακαλούμε δείτε την εγκατάσταση της PHP<br>';
			die;
		}
		if (!$SQLiteDB = new SQLite3(DIR_DATABASE)){
			echo 'Πρόβλημα στο άνοιγμα της Βάσης Δεδομένων: '.DIR_DATABASE.'<br>';
			echo 'Δεν μπορέσαμε να βρούμε το αρχείο<br>';
			die;
		}
		return $SQLiteDB;
	}
	/* Εκτέλεση SQL εντολής */
	function RunQuery($Query,$SQL=SQL_ENGINE){
		$DATASET = array( 'Error' => false, 'Data' => '');

		switch($SQL) {
			case 'MySQL':
				$MySQLDB = $this->MySQLConnect();
				$DATASET['Data'] = $MySQLDB->query($Query);
				if (!$DATASET['Data']){
					$DATASET['Error'] = true;
					$DATASET['Data'] = 'Κατά την Εκτέλεση : '.$Query.'<br>';
					$DATASET['Data'] .= 'Κωδικός Λάθους : '.$MySQLDB->errno.'<br>';
					$DATASET['Data'] .= 'Μήνυμα Λάθους : '.$MySQLDB->error.'<br>';
				}
				$MySQLDB->close();
				break;
			case 'SQLite':
				$SQLiteDB = $this->SQLiteConnect();
				if (substr($Query,0,6) == 'SELECT') {
					$DATASET['Data'] = $SQLiteDB->query($Query);
				} else {
					$DATASET['Data'] = $SQLiteDB->exec($Query);
				}
				if (!$DATASET['Data']){
					$DATASET['Error'] = true;
					$DATASET['Data'] = 'Κατά την Εκτέλεση : '.$Query.'<br>';
					$DATASET['Data'] .= 'Μήνυμα Λάθους : '.$SQLiteDB->lastErrorMsg().'<br>';
				}
				break;
			default:
				$DATASET['Error'] = true;
				$DATASET['Data'] = 'Μη αποδεκτή τιμή SQL<br>';
				break;
		}
		return $DATASET;
	}
}
?>
