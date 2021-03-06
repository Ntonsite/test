<?php
require './roots.php';
require $root_path . 'include/inc_environment_global.php';
require $root_path . 'include/inc_front_chain_lang.php';
require $root_path . 'language/en/lang_en_aufnahme.php';

/**
 * CARE2X Integrated Hospital Information System beta 2.0.0 - 2004-05-16
 * GNU General Public License
 * Copyright 2002,2003,2004 Elpidio Latorilla
 * elpidio@care2x.org, elpidio@care2x.net
 *
 * See the file "copy_notice.txt" for the licence notice
 */
$lang_tables[] = 'emr.php';
$lang_tables[] = 'person.php';
$lang_tables[] = 'date_time.php';
define('LANG_FILE', 'aufnahme.php');
define('NO_CHAIN', 1);

$local_user = 'aufnahme_user';
$db->debug = false;

$thisfile = basename($_SERVER['PHP_SELF']);
$default_filebreak = $root_path . 'modules/news/start_page.php' . URL_APPEND;

if (empty($_SESSION['sess_path_referer']) || !file_exists($root_path . $_SESSION['sess_path_referer'])) {
    $breakfile = $default_filebreak;
} else {
    $breakfile = $root_path . $_SESSION['sess_path_referer'] . URL_APPEND;
}

if (empty($_SESSION['sess_pid'])) {
}
$_SESSION['sess_pid'] = $_SESSION['sess_pid'] ?? 0;

if (!isset($insurance_show)) {
    $insurance_show = true;
}

$newdata = 1;
$target = 'entry';

# Start buffering the text above  the search block

ob_start();
require './gui_bridge/default/gui_std_tags.php';

echo StdHeader();
echo setCharSet();

?>

<TITLE><?php echo $LDPatientRegister ?></TITLE>

<?php
require $root_path . 'include/inc_js_gethelp.php';
require $root_path . 'include/inc_css_a_hilitebu.php';

require_once $root_path . 'main_theme/head.inc.php';
require_once $root_path . 'main_theme/header.inc.php';
require_once $root_path . 'main_theme/topHeader.inc.php';

?>

</HEAD>

<BODY bgcolor="<?php echo $cfg['bot_bgcolor']; ?>" topmargin=0 leftmargin=0 marginwidth=0 marginheight=0 onLoad="if (window.focus)
            window.focus();" <?php
                                if (!$cfg['dhtml']) {
                                    echo 'link=' . $cfg['body_txtcolor'] . ' alink=' . $cfg['body_alink'] . ' vlink=' . $cfg['body_txtcolor'];
                                }
                                ?>>

    <table width=100% border=0 cellspacing="0" cellpadding=0>
        <tr>
            <td bgcolor="<?php echo $cfg['top_bgcolor']; ?>">
                <FONT COLOR="<?php echo $cfg['top_txtcolor']; ?>" SIZE=+2 FACE="Arial"><STRONG> &nbsp;<?php echo $LDPatientRegister ?></STRONG></FONT>
            </td>
            <td bgcolor="<?php echo $cfg['top_bgcolor']; ?>" align="right">
                <a href="javascript:gethelp('registration_overview.php','Person Registration :: Overview','<?php echo isset($error_person_exists) ? $error_person_exists : ""; ?>')"><img <?php echo createLDImgSrc($root_path, 'hilfe-r.gif', '0') ?> <?php if ($cfg['dhtml']) {
                                                                                                                                                                                                                                                            echo 'style=filter:alpha(opacity=70) onMouseover=hilite(this,1) onMouseOut=hilite(this,0)>';
                                                                                                                                                                                                                                                        }
                                                                                                                                                                                                                                                        ?> </a> <a href="<?php echo $breakfile; ?>"><img <?php echo createLDImgSrc($root_path, 'close2.gif', '0') ?> alt="<?php echo $LDCloseWin ?>" <?php if ($cfg['dhtml']) {
                                                                                                                                                                                                                                                                                                                                                                                                            echo 'style=filter:alpha(opacity=70) onMouseover=hilite(this,1) onMouseOut=hilite(this,0)';
                                                                                                                                                                                                                                                                                                                                                                                                        }
                                                                                                                                                                                                                                                                                                                                                                                                        ?>></a>
            </td>
        </tr>

        <?php
        /* Create the tabs */
        $tab_bot_line = '#66ee66';
        require './gui_bridge/default/gui_tz_tabs_patreg.php';
        ?>

        <tr>
            <td colspan=3 bgcolor="<?php echo $cfg['body_bgcolor']; ?>">
                <ul>

                    <?php
                    $sTemp = ob_get_contents();
                    ob_end_clean();

                    chdir(dirname($_SERVER['SCRIPT_FILENAME']));

                    //require_once($root_path.'include/care_api_classes/class_gui_input_person.php');

                    require $root_path . 'include/care_api_classes/class_gui_input_person.php';

                    $inperson = new GuiInputPerson;

                    isset($pid) ? $inperson->setPID($pid) : '';

                    $inperson->pretext = $sTemp;
                    $inperson->setDisplayFile('tz_patient_register_show.php');
                    $inperson->Display();
                    ?>
                </ul>

                </FONT>
                <p>
            </td>
        </tr>
    </table>
    <p>
        <ul>
            <FONT SIZE=2 FACE="Arial">
                <img <?php echo createComIcon($root_path, 'varrow.gif', '0') ?>> <a href="patient_register_search.php<?php echo URL_APPEND; ?>"><?php echo $LDPatientSearch ?></a><br>
                <img <?php echo createComIcon($root_path, 'varrow.gif', '0') ?>> <a href="patient_register_archive.php<?php echo URL_APPEND; ?>&newdata=1&from=entry"><?php echo $LDArchive ?></a><br>

                <p>
                    <a href="
            <?php
            if ($_COOKIE['ck_login_logged' . $sid]) {
                echo $breakfile;
            } else {
                echo 'aufnahme_pass.php';
            }

            echo URL_APPEND;
            ?>
               "><img <?php echo createLDImgSrc($root_path, 'cancel.gif', '0') ?> alt="<?php echo $LDCancelClose ?>"></a>
        </ul>
        <p>

            <?php
            require $root_path . 'include/inc_load_copyrite.php';
            ?>
            </FONT>
            <?php
            echo StdFooter();
            require_once $root_path . 'main_theme/footer.inc.php';
            ?>

            <script>
                $('.fundSubCategory').hide();

                var insuranceId = document.getElementById("insurance_ID").value;
                $.get(
                    "insuranceSubCategories.php", {
                        insuranceId: insuranceId
                    },
                    function(result) {
                        $("#sub_insurance_id").empty();
                        $("#sub_insurance_id").append('<option value="0">-- Select --</option>');
                        if (result.insuranceSubCategories.length > 1) {
                            $('.fundSubCategory').show();
                            window.showFundSub = true
                            for (var i = 0, len = result.insuranceSubCategories.length; i < len; i++) {
                                $("#sub_insurance_id").append(
                                    '<option value="' +
                                    result.insuranceSubCategories[i]["id"] +
                                    '">' +
                                    result.insuranceSubCategories[i]["name"] +
                                    "</option>"
                                );
                            }
                        } else {
                            window.showFundSub = false;
                            $('.fundSubCategory').hide();
                        }
                    },
                    "json"
                );


                $("#nhifcardnr").keyup(function() {
                    var membershipvalue = $("#nhifcardnr").val();
                    if (membershipvalue.length > 8) {
                        $('#membershipbtn').attr("disabled", false);
                    } else {
                        $('#membershipbtn').attr("disabled", true);
                    }

                });

                function verify_card() {

                    var uVisitType = 1;
                    var referralNo = "";
                    var cardno = $("#nhifcardnr").val();

                    $.ajax("getNHIFMemberByCardNo.php?cardno=" + cardno, {
                        type: "GET",
                    }).done(function(data) {
                        var pid = data.patient.pid;
                        if (pid) {
                            var url = "<?php echo $root_path . 'modules/registration_admission/tz_patient_register_show.php' . URL_APPEND ?>&pid=" + pid;
                            $("#nhifcardnr").val('');
                            window.location.href = url;
                            return;
                        } else {

                            $('#membershipbtn').attr("disabled", true);
                            $('#membershipbtn').html("Getting card details. Please wait");

                            var accessToken = null;

                            var logindata = {
                                "grant_type": "password",
                                "username": "<?php echo $nhif_user; ?>",
                                "password": "<?php echo $nhif_pwd; ?>"
                            };

                            var url = "<?php echo $nhif_base; ?>/Token";
                            $.ajax(url, {
                                type: "POST",
                                data: logindata,
                                timeout: 10000
                            }).done(function(data) {

                                accessToken = data.access_token;
                                var visitType = uVisitType;
                                console.log(JSON.stringify(data));

                                authorize_card(cardno, visitType, referralNo, accessToken);
                            }).fail(function(data) {
                                $('#membershipbtn').attr("disabled", false);
                                $('#membershipbtn').html("Get NHIF Card Details");

                                if (data.status === 400) {
                                    alert("Error Login in to NHIF Server!\n" + JSON.stringify(data.responseJSON.error_description));
                                } else {
                                    alert("Error Login in to NHIF Server!\n\nPlease check your network connection\nor contact your administrator!");
                                }

                            });

                        }
                    });
                }

                function authorize_card(cardno, visitType, referralNo, accessToken) {

                    $.ajax("<?php echo $nhif_base; ?>/breeze/Verification/AuthorizeCard?CardNo=" + cardno + "&VisitTypeID=" + visitType + "&ReferralNo=" + referralNo, {
                        headers: {
                            "Authorization": "Bearer " + accessToken
                        },
                        xhrFields: {
                            withCredentials: true
                        }
                    }).done(function(data) {

                        console.log(JSON.stringify(data));

                        if (data.CardStatus === 'Invalid') {
                            alert(data.Remarks);
                        } else {
                            $("#FirstName").val(data.FirstName);
                            $("#MiddleName").val(data.MiddleName);
                            $("#LastName").val(data.LastName);
                            $("#DateOfBirth").val(formatDate(new Date(data.DateOfBirth)));
                            $("#nhif_patient_fullname").text(data.FullName);
                            $("#nhif_patient_cardstatus").text(data.CardStatus);
                            $("#nhif_patient_authorization").text(data.AuthorizationStatus);
                            $("#nhif_patient_authorizationnumber").text(data.AuthorizationNo);
                            $("#nhif_patient_remarks").text(data.Remarks);

                            // console.log(data)
                            var patientGender = data.Gender;
                            if (patientGender == "Male") {
                                $("#maleGender").prop("checked", true);
                            }
                            if (patientGender == "Female") {
                                $("#femaleGender").prop("checked", true);
                            }
                            $("#insurance_ID option:contains(NHIF)").attr('selected', 'selected');
                        }
                        $('#membershipbtn').attr("disabled", false);
                        $('#membershipbtn').html("Get NHIF Card Details");
                        $('#membershipnr').val($("#nhifcardnr").val());
                        $('#membershipnr').prop("readonly",true);

                    }).fail(function(data) {
                        $('#membershipbtn').attr("disabled", false);
                        $('#membershipbtn').html("Get NHIF Card Details");
                        if (data.status === 0) {
                            alert("Error Connecting to NHIF Server!\n\nPlease check your network connection!");
                        } else {
                            if (data.status === 404) {
                                alert(JSON.stringify(data.responseText));
                            } else {
                                alert(JSON.stringify(data.responseText));
                            }

                        }
                    });

                    $.ajax('<?php echo $nhif_base; ?>/api/Account/Logout', {
                        type: "POST",
                        headers: {
                            "Authorization": "Bearer " + accessToken
                        }
                    });
                }


                function formatDate(date) {

                    var day = date.getDate();
                    var monthIndex = date.getMonth();
                    var month = parseInt(monthIndex) + 1;

                    var year = date.getFullYear();

                    return day + '/' + month + '/' + year;
                }

                $(document).ready(function() {
                    var regionid = $("#regionid").find(":selected").val();
                    <?php
                    $districtid = @$_POST['district'] ? $_POST['district'] : 0;
                    $wardid = @$_POST['ward'] ? $_POST['ward'] : 0;
                    ?>
                    if (regionid) {
                        xtarget = 'dstr';
                        navigate('&id=' + regionid + '&dir=district&districtid=<?php echo $districtid ?>&wardid=<?php echo $wardid ?>', '<?php echo $root_path; ?>modules/registration_admission/get_params.php');
                        clearSelect('ward');

                        <?php

                        global $db;

                        $wardsql = "SELECT DISTINCT ward_id,ward_name FROM `care_tz_ward`  WHERE care_tz_ward.is_additional=" . $districtid . " ORDER BY `ward_name` ASC";

                        $wardResult = $db->Execute($wardsql);

                        $wardopts = "<option>--Select Ward --</option>";
                        if (@$wardResult->RecordCount()) {

                            while ($wardRow = $wardResult->FetchRow()) {
                                $selectedWard = "";
                                if ($wardid == $wardRow[0]) {
                                    $selectedWard = " selected ";
                                }
                                $wardopts .= '<option ' . $selectedWard . ' value="' . $wardRow[0] . '" id="-1">' . $wardRow[1] . '</option>';
                            }
                        }

                        ?>
                        appendThis('ward', '<?php print_r($wardopts) ?>');

                    }
                })
            </script>