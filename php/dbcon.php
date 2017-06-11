<?php

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
