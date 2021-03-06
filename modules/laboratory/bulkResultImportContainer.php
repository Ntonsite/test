<?php
require './roots.php';
require $root_path . 'include/inc_environment_global.php';
require $root_path . 'include/care_api_classes/class_labmachine.php';
$labMachine = new LabMachine;

require_once $root_path . 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

require_once $root_path . 'main_theme/head.inc.php';
require_once $root_path . 'main_theme/header.inc.php';
require_once $root_path . 'main_theme/topHeader.inc.php';

$uploadURL = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$importedResults = [];

?>
<style type="text/css">
    body {
        background: #fff;
    }
</style>
<table width=100% border=0 cellspacing=0 >
    <tr>
        <td valign="top" height="35">
            <table width="100%" cellspacing="0" class="titlebar" border=0>
                <tr valign=middle class="titlebar" height="40">
                    <td bgcolor="#99ccff">
                        <font color="#330066">Import Multiple Results</font>
                    </td>
                    <td align=right bgcolor="#99ccff">
                        <a href="javascript: history.back();"><img src="../../gui/img/control/default/en/en_back2.gif" border=0 width="110" height="24" alt="" style="filter:alpha(opacity=70)" onMouseover="hilite(this,1)" onMouseOut="hilite(this,0)"></a>
                        <a href="javascript:gethelp('reporting_overview.php','Reporting :: Overview')"><img src="../../gui/img/control/default/en/en_hilfe-r.gif" border=0 width="75" height="24" alt="" style="filter:alpha(opacity=70)" onMouseover="hilite(this,1)" onMouseOut="hilite(this,0)"></a>
                        <a href="javascript:history.back()"><img src="../../gui/img/control/default/en/en_close2.gif" border=0 width="103" height="24" alt="" style="filter:alpha(opacity=70)" onMouseover="hilite(this,1)" onMouseOut="hilite(this,0)"></a>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<div style="min-height: 80%; height: 80%; min-width: 100%; padding-top: 30px; padding-left: 30px;">
	<h5>Choose an excel file and click upload</h5><hr>
	<form action="<?php echo $uploadURL ?>" method="post"  enctype="multipart/form-data">
        <table>
            <tbody>
                <tr>
                   <div class="form-group row">
                        <label  class="col-md-2 col-form-label">Lab Machine</label>
                        <div class="col-md-3">
                            <select required="" name="machine_name" class="custom-select ">
                                <option value="">--Select Machine--</option>
                                <option  <?php if (@$_POST['machine_name'] && $_POST['machine_name'] == 'dh76') {
	echo "selected";}?>
                                value="dh76">DH 76</option>
                                <option
                                <?php if (@$_POST['machine_name'] && $_POST['machine_name'] == 'accent220s') {
	echo "selected";}?>
                                value="accent220s">Accent 220s</option>
                            </select>
                        </div>
                    </div>
                </tr>
                <tr>
                    <div class="row" style="margin-top: 20px; margin-left: 3px;">
                        <label  class="col-md-2 col-form-label">Excel File</label>
                        <div class="col-md-3">
                            <input type="file" required name="resultfile">
                        </div>
                    </div>
                </tr>
                <tr>
                   <div class="row ">
                        <div class="col-md-5 ">
                            <button class="btn btn-primary btn-sm float-right" type="submit">Upload</button>
                        </div>
                    </div>
                </tr>
            </tbody>
        </table>
    </form>

<?php

if (!empty($_FILES)) {
	$excelErrors = array();
	$file_name = $_FILES['resultfile']['name'];
	$file_size = $_FILES['resultfile']['size'];
	$file_tmp = $_FILES['resultfile']['tmp_name'];
	$file_type = $_FILES['resultfile']['type'];
	$file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
	$machine_name = $_POST['machine_name'];

	$excelExtensions = array("xls", "xlsx", "csv", 'xlsm', 'xlsb', 'xltx', 'xltm', 'xlt', 'xml', 'xlam', 'xla', 'xlw', 'xlr');

	if (in_array($file_ext, $excelExtensions) === false) {
		$excelErrors[] = "The Selected file isn't an excel file. Please select excel file.";
	}

	if (empty($excelErrors) == true) {

		$uploadDirectory = "uploads/";
		if (!file_exists($uploadDirectory)) {
			$oldmask = umask(0);
			mkdir($uploadDirectory, 0777, true);
			umask($oldmask);
		}

		$filePath = $uploadDirectory . $file_name;
		move_uploaded_file($file_tmp, $filePath);

		$inputFileType = IOFactory::identify($filePath);

		$reader = IOFactory::createReader($inputFileType);
		$spreadsheet = $reader->load($filePath);

		if ($machine_name == "dh76") {
			require_once $root_path . 'modules/laboratory/dh76MulitpleResultMachine.php';
		}

		if ($machine_name == "accent220s") {
			require_once $root_path . 'modules/laboratory/accent220sMultipleResultMachine.php';
		}

		if (file_exists($filePath)) {
			unlink($filePath);
		}
	} else {
		echo "<h4>Can't upload file. Please try again</h4>";
	}

}
?>

<?php if (!empty($importedResults)): ?>
    <h4 style="padding-top: 60px;">Uploaded Results</h4>
    <table class="datatable table table-condensed table-hover table-bordered table-striped" width="100%" s>
        <thead>
            <th>SN</th>
            <th>Patient Name</th>
            <th>Gender</th>
            <th>Batch No</th>
            <th>Delivery Time</th>
            <th>Inserted</th>
        </thead>
        <tbody>
            <?php foreach ($importedResults as $key => $impResult): ?>
                <tr>
                    <td><?php echo ++$key ?></td>
                    <td><?php echo $impResult['first_name'] . " " . $impResult['last_name'] ?></td>
                    <td><?php echo $impResult['gender'] ?></td>
                    <td><?php echo $impResult['med_rec_no'] ?></td>
                    <td><?php echo $impResult['delivery_date'] . " " . $impResult['delivery_time'] ?></td>
                    <td><?php echo $impResult['uploaded'] ?></td>
                </tr>
            <?php endforeach?>
        </tbody>
    </table>
<?php endif?>

</div>
<?php
require_once $root_path . 'main_theme/footer.inc.php';
