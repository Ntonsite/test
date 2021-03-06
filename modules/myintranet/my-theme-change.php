<?php
error_reporting(E_COMPILE_ERROR | E_ERROR | E_CORE_ERROR);
require('./roots.php');
require($root_path . 'include/inc_environment_global.php');
$pageName = "Special Tools";
/**
 * CARE2X Integrated Hospital Information System Deployment 2.1 - 2004-10-02
 * GNU General Public License
 * Copyright 2002,2003,2004,2005 Elpidio Latorilla
 * elpidio@care2x.org,
 *
 * See the file "copy_notice.txt" for the licence notice
 */
$lang_tables[] = 'stdpass.php';
define('LANG_FILE', 'specials.php');
require_once($root_path . 'include/inc_front_chain_lang.php');

$breakfile = $root_path . 'main/spediens.php' . URL_APPEND;
$thisfile = basename($_SERVER['PHP_SELF']);




if (!isset($userid))
    $userid = $_SESSION['sess_user_name'];

# Start Smarty templating here
/**
 * LOAD Smarty
 */
# Note: it is advisable to load this after the inc_front_chain_lang.php so
# that the smarty script can use the user configured template theme

require_once($root_path . 'gui/smarty_template/smarty_care.class.php');
$smarty = new smarty_care('system_admin');

# Title in toolbar
$smarty->assign('sToolbarTitle', "Change Theme");

# href for return button
$smarty->assign('pbBack', $breakfile);

# href for help button
$smarty->assign('pbHelp', "javascript:gethelp('pw_change.php')");

# href for close button
$smarty->assign('breakfile', $breakfile);

# Window bar title
$smarty->assign('sWindowTitle', "Change Theme");


ob_start();
?>

<?php 
    if ($_POST['theme_name']) {

         $sql = "UPDATE care_users SET   theme_name='" . $_POST['theme_name'] ."' WHERE login_id='$userid'";

        if ($db->Execute($sql)) {
            // echo "ok";
            header("location:$thisfile?sid=$sid&lang=$lang&mode=themechange");
            exit;
        } else {
            echo "$sql<br>$LDDbNoSave";
        }
        
    }

 ?>

<script language=javascript>

</script>

<?php
$sTemp = ob_get_contents();
ob_end_clean();

$smarty->append('JavaScript', $sTemp);

# Buffer page output

ob_start();


?>

<P>

    <?php if ($n_error) : ?><font class="warnprompt">
        <img <?php echo createMascot($root_path, 'mascot1_r.gif', '0', 'bottom') ?> align="absmiddle"> <?php echo $LDNewPwDiffer ?>
        </font>
    <?php endif; ?>
<ul>

    <?php if ($mode == 'themechange') : ?>
        <font face="verdana,arial" size=3 color="#009900">
        <img <?php echo createMascot($root_path, 'mascot1_r.gif', '0', 'bottom') ?> align="absmiddle"><b><?php echo "Your Theme has been changed." ?></b></font>
    <?php else : ?>

        <?php
        if (($pass == 'check') && $passtag) {
            echo '<FONT  class="warnprompt">';

            $errbuf = $title;

            switch ($passtag) {
                case 1:$errbuf = "$errbuf $LDWrongEntry";
                    print '<img ' . createMascot($root_path, 'mascot3_r.gif', '0') . '> ' . " $LDWrongEntry <font size=2 color=\"#000000\">$LDPlsTryAgain</font>";
                    //echo '<img '.createLDImgSrc($root_path,'cat-fe.gif','0','left').'>';
                    break;
                case 2:$errbuf = "$errbuf $LDNoAuth";
                    print '<img ' . createMascot($root_path, 'mascot3_r.gif', '0') . '>' . "$LDNoAuth  <font size=2 color=\"#000000\">$LDPlsContactEDP</font>";
                    //echo '<img '.createLDImgSrc($root_path,'cat-noacc.gif','0','left').'>';
                    break;
                default:$errbuf = "$errbuf $LDAuthLocked";
                    print '<img ' . createMascot($root_path, 'mascot3_r.gif', '0') . '>' . "$LDAuthLocked  <font size=2 color=\"#000000\">$LDPlsContactEDP</font>";
                //echo '<img '.createLDImgSrc($root_path,'cat-sperr.gif','0','left').'>';
            }


            logentry($userid, $keyword, $errbuf, $thisfile, $fileforward);

            echo '</FONT><p>';
        }
        ?>

        <br>
        <form method=post action=<?php echo $thisfile; ?>>
              <table  border=0 cellpadding="0" cellspacing=1 bgcolor=#666666>
                <tr>
                    <td >

                        <table border="0" cellpadding="20" cellspacing="0" bgcolor=#ffffdd>
                            <tr>
                                <td><font color=#800000>
                                   
                                    <p>
                                        <b>Please select your theme you want to use</b><p></font>
                                        <font color=#000080>Theme Name:&nbsp; &nbsp;

                                    <select name="theme_name" id="" class="form-control" width="200">
                                        <option value="">Default</option>
                                        <option value="blue">Blue</option>
                                        <option value="red">Red</option>
                                        <option value="green">Green</option>
                                    </select>
                                      
                                </td>
                            </tr>

                            <tr><td>
                                    <input type="hidden" name="sid" value="<?php echo $sid; ?>">
                                    <input type="hidden" name="lang" value="<?php echo $lang; ?>">
                                    <input type="hidden" name="mode" value="change">
                                    <input type="hidden" name="pass" value="check">
                                    <input type="submit" value="Change Theme"><p>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </form>
<?php endif; ?>

    <p>

        <a href="<?php echo $breakfile; ?>"><img <?php if ($mode == 'themechange') echo createLDImgSrc($root_path, 'close2.gif', '0');
else echo createLDImgSrc($root_path, 'cancel.gif', '0'); ?>>
        </a>

</ul>
<?php
$sTemp = ob_get_contents();
ob_end_clean();

# Assign page output to the mainframe template

$smarty->assign('sMainFrameBlockData', $sTemp);
/**
 * show Template
 */
require_once($root_path . 'main_theme/head.inc.php');
require_once($root_path . 'main_theme/header.inc.php');
require_once($root_path . 'main_theme/topHeader.inc.php');

$smarty->display('common/mainframe.tpl');


require_once($root_path . 'main_theme/footer.inc.php');

?>
