<?php

ini_set("memory_limit", "-1");
set_time_limit(0);

require './roots.php';

require $root_path . 'include/inc_init_main.php';
$conn = new mysqli($dbhost, $dbusername, $dbpassword, $dbname);
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

$sql = "SHOW TABLES FROM $dbname";
$stmt = $conn->prepare($sql);
$stmt->execute();
$reqResult = $stmt->get_result();

while ($row = $reqResult->fetch_array()) {
	$tableNames[] = $row[0];
}

$stmt->close();

foreach ($tableNames as &$name) {
	$tbSQL = "ALTER TABLE $name ENGINE=INNODB";
	$stmt = $conn->prepare($tbSQL);
	$stmt->Execute();
	$stmt->close();

	$colSQL = "SELECT * FROM INFORMATION_SCHEMA.COLUMNS where TABLE_NAME= '$name' ";
	$stmt = $conn->prepare($colSQL);
	$stmt->Execute();
	$reqResult = $stmt->get_result();
	$stmt->close();
	$columns = array();

	while ($table = $reqResult->fetch_array()) {
		$column = array(
			'column_name' => $table['COLUMN_NAME'],
			'data_type' => $table['DATA_TYPE'],
		);
		$columns[] = $column;
	}

	foreach ($columns as $colm) {

		$colname = $colm['column_name'];

		if ($colm['data_type'] == 'char' || $colm['data_type'] == 'varchar' || $colm['data_type'] == 'text' || $colm['data_type'] == 'tinytext' || $colm['data_type'] == 'blob') {
			if ($colm['data_type'] == 'varchar') {
				$colTtype = "VARCHAR(255)";
			}

			if ($colm['data_type'] == 'char') {
				$colTtype = "CHAR(50)";
			}
			if ($colm['data_type'] == 'text' || $colm['data_type'] == 'tinytext' || $colm['data_type'] == 'blob') {
				$colTtype = "TEXT";
			}
			$colSQL = "ALTER TABLE $name CHANGE `$colname` `$colname` $colTtype CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT '' ";

			$stmt = $conn->prepare($colSQL);
			if ($stmt) {
				$stmt->Execute();
				$stmt->close();
			}

		}

		if ($colm['data_type'] == 'int' || $colm['data_type'] == 'mediumint' || $colm['data_type'] == 'bigint' || $colm['data_type'] == 'tinyint' || $colm['data_type'] == 'float' || $colm['data_type'] == 'decimal') {

			$colSQL = "ALTER TABLE $name ALTER COLUMN $colname SET DEFAULT 0 ";
			$stmt = $conn->prepare($colSQL);
			if ($stmt) {
				$stmt->Execute();
				$stmt->close();
			}

		}

		if ($colm['data_type'] == 'timestamp' || $colm['data_type'] == 'datetime') {

			$colSQL = "ALTER TABLE $name MODIFY COLUMN $colname TIMESTAMP NULL DEFAULT NULL";
			if ($colname == 'modify_time') {
				$colSQL = "ALTER TABLE $name MODIFY COLUMN $colname TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP";
			}

			$stmt = $conn->prepare($colSQL);
			if ($stmt) {
				$stmt->Execute();
				$stmt->close();
			}

			 $updateSQL = "UPDATE $name SET $colname = '1980-01-01 00:00:00' WHERE $colname = '0000-00-00 00:00:00' ";
			 $stmt = $conn->prepare($updateSQL);
			 if ($stmt) {
			 	$stmt->Execute();
			 	$stmt->close();
			 }

		}

		 if ($colm['data_type'] == 'date') {
		 	$colSQL = "ALTER TABLE $name ALTER COLUMN $colname SET DEFAULT '1980-01-01' ";
		 	$stmt = $conn->prepare($colSQL);
		 	if ($stmt) {
				$stmt->Execute();
				$stmt->close();
			}

		 }
	}

}

$conn->close();

echo "<h1>DB was Successfully Updated. Completed at</h1>".date("Y/m/d H:i:s");
