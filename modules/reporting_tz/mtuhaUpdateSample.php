<?php 

	$fileName= "ICD DOTTO NEW.xlsx";

    header('Content-Description: File Transfer');
    header('Content-Type: application/force-download');
    header("Content-Disposition: attachment; filename=\"" . basename($fileName) . "\";");
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($fileName));
    ob_clean();
    flush();
    readfile($fileName);
    exit;
 ?>