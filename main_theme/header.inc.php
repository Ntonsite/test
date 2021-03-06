<script>


  function SetSelectedPage(pageName) {
      createCookie("PageName", '', "-1");
      createCookie("PageName", pageName, "10");
  }

  function createCookie(name, value, days) {
    var expires;
    if (days) {
      var date = new Date();
      date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
      expires = "; expires=" + date.toGMTString();
    } else {
     expires = "";
    }
    document.cookie = escape(name) + "=" + escape(value) + expires + "; path=/";
  }

</script>

<style>

.blink {
  cursor: pointer;
  animation: blinker 1s linear infinite;
}

.no-blink {
  animation: none;
}

@keyframes blinker {
  50% { opacity: 0; }
}

@media only screen and (max-width: 1000px) {
  .main-panel {
    width: 100%;
  }
  html, body {
    overflow-x: auto;
  }
}

.nav {
  overflow-x: hidden;
}

.nav:hover {
  overflow-x: scroll;
}

.hoverTable:hover { background: #BDBDBD !important; z-index: 999999999999; }
.hoverTable:hover td { background: transparent !important; }
</style>

<?php

require './roots.php';
$PageName = $_COOKIE['PageName'];
require_once $root_path . 'vendor/autoload.php';
require_once $root_path . 'generated-conf/config.php';

use CareMd\CareMd\CareUserRolesQuery;
use CareMd\CareMd\CareUsersQuery;



if (empty($_SESSION['sess_login_userid'])) {
	header("location: " . $root_path . "?is_logged_out=1");
	exit;
}

function array_insert(&$array, $position, $insert) {
	if (is_int($position)) {
		array_splice($array, $position, 0, $insert);
	} else {
		$pos = array_search($position, array_keys($array));
		$array = array_merge(
			array_slice($array, 0, $pos),
			$insert,
			array_slice($array, $pos)
		);
	}
}

$menuSql = "SELECT nr,sort_nr,name,LD_var AS \"LD_var\",url,is_visible FROM care_menu_main WHERE is_visible=1 OR LD_var='LDEDP' OR LD_var='LDLogin' ORDER by sort_nr";



$resultMenus = $db->Execute($menuSql);
$navigationMenus = [];
$userNavigationMenus = [];

if ($resultMenus) {

	include_once $root_path . 'main/menu/big_icon/tags.php';

	while ($menu = $resultMenus->FetchRow()) {

		if (preg_match('/LDLogin/i', $menu['LD_var'])) {
			if ($_COOKIE['ck_login_logged' . $sid] == 'true') {
				$menu['url'] = 'main/logout_confirm.php';
				$menu['LD_var'] = 'LDLogout';
			}
		}

		if (@($$menu['LD_var']) && !empty($$menu['LD_var'])) {
			if (is_string($$menu['LD_var'])) {
				$name = $$menu['LD_var'];
			}
		} else {
			$name = $menu['name'];
		}

		$menu = array(
			'name' => $name,
			'url' => $root_path . $menu['url'] . URL_APPEND,
		);

		if ($menu['name'] == "Appointments") {
		}

		if ($menu['name'] != "Admission") {
			array_push($navigationMenus, $menu);
		}

		array_push($navigationMenus, $menu);

	}

}

$userId = $_SESSION['sess_login_userid'];

$user = CareUsersQuery::create()->filterByLoginId($userId)->findOne()->toArray();
$roleId = $user['RoleId'];
// $roleId = 3;

$userRole = CareUserRolesQuery::create()->filterByRoleId($roleId)->findOne()->toArray();
$themeName = $user['ThemeName'];

$userPermissions = explode(" ", $userRole['Permission']);

$userPermissions = str_replace('_a_1_', '', $userPermissions);
$userPermissions = str_replace('_a_2_', '', $userPermissions);
$userPermissions = str_replace('_a_3_', '', $userPermissions);
$userPermissions = str_replace('_a_4_', '', $userPermissions);

//echo "<pre>"; print_r($userPermissions); echo "</pre>";die;

$_SESSION['transfer_nhif_patients'] = 0;

foreach ($userPermissions as $userPermission) {
	if ($userPermission == 'transfer_nhif_patients') {
		$_SESSION['transfer_nhif_patients'] = 1;
	}
}

//echo "<pre>"; print_r($navigationMenus); echo "</pre>";die;

if ($userPermissions[0] == "System_Admin" || $userPermissions[0] == "_a_0_all " || $userPermissions[0] == "_a_0_all") {
	$userNavigationMenus = $navigationMenus;
	$_SESSION['transfer_nhif_patients'] = 1;
	foreach ($userNavigationMenus as $arrKey => $navMenu) {
		if ($navMenu['name'] == "Appointments") {
			array_insert($userNavigationMenus, $arrKey, array($arrKey => array('name' => 'Discharge', 'url' => $root_path . "modules/ambulatory/amb_clinic_patients_discharge.php" . URL_APPEND)));
		}
	}

} else {

	foreach ($navigationMenus as $navigationMenu) {
		if ($navigationMenu['name'] == "Home") {
			array_push($userNavigationMenus, $navigationMenu);
		}

		if ($navigationMenu['name'] == "Special Tools") {
			array_push($userNavigationMenus, $navigationMenu);
		}

		if ($navigationMenu['name'] == "Registration" || $navigationMenu['name'] == "Patient") {
			foreach ($userPermissions as $userPermission) {
				if ($userPermission == "admissionwrite" || $userPermission == "admissionread" || $userPermission == "archiveread" || $userPermission == "aarreadwrite") {
					array_push($userNavigationMenus, $navigationMenu);
				}
			}
		}

		if ($navigationMenu['name'] == "Appointments") {
			foreach ($userPermissions as $userPermission) {
				if ($userPermission == "appointment") {
					array_push($userNavigationMenus, $navigationMenu);
				}
			}
		}

		if ($navigationMenu['name'] == "Ambulatory") {
			foreach ($userPermissions as $userPermission) {
				if ($userPermission == "outpwrite" || $userPermission == "outpread") {
					array_push($userNavigationMenus, $navigationMenu);
				}
			}
		}

		if ($navigationMenu['name'] == "Nursing") {
			foreach ($userPermissions as $userPermission) {
				if ($userPermission == "nursingstationallwrite" || $userPermission == "nursingstationallread" || $userPermission == "nursingdutyplanwrite" || $userPermission == "nursingdutyplanread") {
					array_push($userNavigationMenus, $navigationMenu);
				}
			}
		}


		if ($navigationMenu['name'] == "OR") {
			foreach ($userPermissions as $userPermission) {
				if ($userPermission == "opdoctorallwrite" || $userPermission == "opnurseallwrite" || $userPermission == "opnurseallread" || $userPermission == "opnursedutyplanwrite" || $userPermission=="opnursedutyplanread" ) {
					array_push($userNavigationMenus, $navigationMenu);
				}
			}
		}


		if ($navigationMenu['name'] == "Inpatient") {
			foreach ($userPermissions as $userPermission) {
				if ($userPermission == "diagnosticsresultwrite" || $userPermission == "diagnosticsresultread" || $userPermission == "diagnosticsreceptionwrite" || $userPermission == "diagnosticsrequest") {
					array_push($userNavigationMenus, $navigationMenu);
				}
			}
		}

		if ($navigationMenu['name'] == "Doctors") {
			foreach ($userPermissions as $userPermission) {
				if ($userPermission == "medocswrite" || $userPermission == "medocsread" || $userPermission == "meddepotdbadmin" || $userPermission == "meddepotreception" || $userPermission == "meddepotorder" || $userPermission == "meddepotread" || $userPermission == "doctorsdutyplanwrite" || $userPermission == "doctorsdutyplanread") {
					array_push($userNavigationMenus, $navigationMenu);
				}
			}
		}

		if ($navigationMenu['name'] == "Radiology") {
			foreach ($userPermissions as $userPermission) {
				if ($userPermission == "photowrite" || $userPermission == "photoread" || $userPermission == "diagnosticsreceptionwrite" || $userPermission == "diagnosticsrequest" || $userPermission == "radioread" || $userPermission == "radiowrite") {
					array_push($userNavigationMenus, $navigationMenu);
				}
			}
		}

		if ($navigationMenu['name'] == "Billing") {
			foreach ($userPermissions as $userPermission) {
				if ($userPermission == "billallwrite" || $userPermission == "billallread" || $userPermission == "billquotations" || $userPermission == "billquotationsoutp" || $userPermission == "billquotationsinp" || $userPermission == "billquotationsden" || $userPermission == "billreports") {
					array_push($userNavigationMenus, $navigationMenu);
				}
			}
		}

		if ($navigationMenu['name'] == "Pharmacy") {
			foreach ($userPermissions as $userPermission) {
				if ($userPermission == "pharmadbadmin" || $userPermission == "pharmareception" || $userPermission == "pharmaorder" || $userPermission == "pharmaread") {
					array_push($userNavigationMenus, $navigationMenu);
				}
			}
		}

		if ($navigationMenu['name'] == "Laboratories") {
			foreach ($userPermissions as $userPermission) {
				if ($userPermission == "labresultsreadwrite" || $userPermission == "labrequest" || $userPermission == "labresultswrite" || $userPermission == "labresultsread" || $userPermission == "labbloodwrite" || $userPermission == "labbloodread") {
					array_push($userNavigationMenus, $navigationMenu);
				}
			}
		}

		if ($navigationMenu['name'] == "NHIF Claims") {
			foreach ($userPermissions as $userPermission) {
				if ($userPermission == "nhif_claims") {
					array_push($userNavigationMenus, $navigationMenu);
				}
			}
		}

		if ($navigationMenu['name'] == "Reporting") {
			foreach ($userPermissions as $userPermission) {
				if ($userPermission == "billreports" || $userPermission == "allreportingread" || $userPermission == "reportingread" || $userPermission == "clinicreportingread" || $userPermission == "financialreportingread" || $userPermission == "systemreportingread") {
					array_push($userNavigationMenus, $navigationMenu);
				}
			}
		}

		if ($navigationMenu['name'] == "Special Tools") {
			foreach ($userPermissions as $userPermission) {
				if ($userPermission == "newsallwrite" || $userPermission == "newscafewrite" || $userPermission == "newsallmoderatedwrite" || $userPermission == "clinicreportingread" || $userPermission == "financialreportingread" || $userPermission == "systemreportingread") {
					array_push($userNavigationMenus, $navigationMenu);
				}
			}
		}

		if ($navigationMenu['name'] == "Intranet Email") {
			foreach ($userPermissions as $userPermission) {
				if ($userPermission == "hxpserver") {
					array_push($userNavigationMenus, $navigationMenu);
				}
			}
		}

		if ($navigationMenu['name'] == "Directory") {
			foreach ($userPermissions as $userPermission) {
				if ($userPermission == "teldirwrite") {
					array_push($userNavigationMenus, $navigationMenu);
				}
			}
		}

		if ($navigationMenu['name'] == "Logout") {
			array_push($userNavigationMenus, $navigationMenu);
		}

	}

	foreach ($userPermissions as $userPermission) {
		if ($userPermission == "discharge") {
			array_insert($userNavigationMenus, 3, array(3 => array('name' => 'Discharge', 'url' => $root_path . "modules/ambulatory/amb_clinic_patients_discharge.php" . URL_APPEND)));
		}
	}

}

// echo "<pre>"; print_r($navigationMenus); echo "</pre>";die;

array_push($userNavigationMenus, array('name' => 'Accounting & Stock', 'url' => "http://localhost/weberp"));

// echo "<pre>"; print_r($userPermissions);echo "</pre>";

// die();

$userNavigations = array_unique($userNavigationMenus, SORT_REGULAR);
?>



    <div class="sidebar" data-color="azure" data-background-color="white">

      <!--
        Tip 1: You can change the color of the sidebar using: data-color="purple | azure | green | orange | danger"

        Tip 2: you can also add an image using data-image tag
    -->
      <div class="logo" style="background-color: white;">
        <a href="#" class="simple-text logo-normal" style="text-transform: none;">
          <img style="height: 38px; "src="<?php echo $root_path ?>img/caremd.png" alt="CareMd">

        </a>
      </div>
      <div class="sidebar-wrapper nav-scroll backgroundOne" style="background-color: white;" >
        <ul class="nav" >

          <?php foreach ($userNavigations as $menu): ?>
            <?php if ($menu['name'] == "Home"): ?>
            <li class="nav-item colorOne <?php if ($PageName == "Home") {
	echo 'active';
}
?> " >
              <a class="nav-link  " onclick="SetSelectedPage('Home')" href="<?php echo $root_path ?>modules/dashboard/dashboard.php<?php echo URL_APPEND ?>">
                <p class="colorOne" > <i class="fa fa-home fa-fw"></i><?php echo $menu['name'] ?></p>
              </a>
            </li>

            <?php elseif ($menu['name'] == "Registration" || $menu['name'] == "Patient" || $menu['name'] == "Patient/ID"): ?>
            <li class="nav-item colorOne <?php if ($PageName == "Registration" || $PageName == "Patient" || $PageName == "Patient/ID") {
	echo 'active';
}
?> " >
              <a class="nav-link " onclick="SetSelectedPage('Registration')" href="<?php echo $menu['url'] ?>" >
                 <i class="fa fa-user fa-fw"></i>
                <p class="colorOne">Registration</p>
              </a>
            </li>

            <?php elseif ($menu['name'] == "Discharge"): ?>
            <li class="nav-item  <?php if ($PageName == "Discharge") {
	echo 'active';
}
?> ">
              <a class="nav-link" href="<?php echo $menu['url'] ?>" onclick="SetSelectedPage('Discharge')">
                <i class="material-icons colorOne">check_circle_outline</i>
                <p class="colorOne"><?php echo $menu['name'] ?></p>
              </a>
            </li>


            <?php elseif ($menu['name'] == "Appointments"): ?>
            <li class="nav-item <?php if ($PageName == "Appointments") {
	echo 'active';
}
?> ">
              <a class="nav-link" href="<?php echo $menu['url'] ?>" onclick="SetSelectedPage('Appointments')">
                <i class="material-icons colorOne">calendar_today</i>
                <p class="colorOne"><?php echo $menu['name'] ?></p>
              </a>
            </li>

            <?php elseif ($menu['name'] == "Ambulatory"): ?>
            <li class="nav-item <?php if ($PageName == "Ambulatory") {
	echo 'active';
}
?> ">
              <a class="nav-link" href="<?php echo $menu['url'] ?>" onclick="SetSelectedPage('Ambulatory')">
                <i class="material-icons colorOne">departure_board</i>
                <p class="colorOne">Outpatient</p>
              </a>
            </li>

            <?php elseif ($menu['name'] == "Inpatient"): ?>
            <li class="nav-item <?php if ($PageName == "Inpatient") {
	echo 'active';
}
?> ">
              <a class="nav-link" href="<?php echo $menu['url'] ?>" onclick="SetSelectedPage('Inpatient')">
                <i class="material-icons colorOne">airline_seat_flat</i>
                <p class="colorOne"><?php echo $menu['name'] ?></p>
              </a>
            </li>

            <?php elseif ($menu['name'] == "Doctors"): ?>
            <li class="nav-item <?php if ($PageName == "Doctors") {
	echo 'active';
}
?> ">
              <a class="nav-link" href="<?php echo $menu['url'] ?>" onclick="SetSelectedPage('Doctors')">
                <i class="material-icons colorOne">streetview</i>
                <p class="colorOne"><?php echo $menu['name'] ?></p>
              </a>
            </li>

            <?php elseif ($menu['name'] == "Nursing"): ?>
            <li class="nav-item <?php if ($PageName == "Nursing") {
	echo 'active';
}
?> ">
              <a class="nav-link" href="<?php echo $menu['url'] ?>" onclick="SetSelectedPage('Nursing')">
                <i class="material-icons colorOne">exposure</i>
                <p class="colorOne"><?php echo $menu['name'] ?></p>
              </a>
            </li>

            <?php elseif ($menu['name'] == "OR"): ?>
            <li class="nav-item <?php if ($PageName == "OR") {
	echo 'active';
}
?> ">
              <a class="nav-link" href="<?php echo $menu['url'] ?>" onclick="SetSelectedPage('OR')">
                <i class="material-icons colorOne">account_balance</i>
                <p class="colorOne">OP Room</p>
              </a>
            </li>

            <?php elseif ($menu['name'] == "Laboratories"): ?>
            <li class="nav-item <?php if ($PageName == "Laboratories") {
	echo 'active';
}
?> ">
              <a class="nav-link" href="<?php echo $menu['url'] ?>" onclick="SetSelectedPage('Laboratories')">
                <i class="material-icons colorOne">gavel</i>
                <p class="colorOne"><?php echo $menu['name'] ?></p>
              </a>
            </li>

            <?php elseif ($menu['name'] == "Radiology"): ?>
            <li class="nav-item <?php if ($PageName == "Radiology") {
	echo 'active';
}
?> ">
              <a class="nav-link" href="<?php echo $menu['url'] ?>" onclick="SetSelectedPage('Radiology')">
                <i class="material-icons colorOne">picture_in_picture</i>
                <p class="colorOne"><?php echo $menu['name'] ?></p>
              </a>
            </li>

            <?php elseif ($menu['name'] == "Pharmacy"): ?>
            <li class="nav-item <?php if ($PageName == "Pharmacy") {
	echo 'active';
}
?> ">
              <a class="nav-link" href="<?php echo $menu['url'] ?>" onclick="SetSelectedPage('Pharmacy')">
                <i class="material-icons colorOne">enhanced_encryption</i>
                <p class="colorOne"><?php echo $menu['name'] ?></p>
              </a>
            </li>

            <?php elseif ($menu['name'] == "Billing"): ?>
            <li class="nav-item <?php if ($PageName == "Billing") {
	echo 'active';
}
?> ">
              <a class="nav-link" href="<?php echo $menu['url'] ?>" onclick="SetSelectedPage('Billing')">
                <i class="material-icons colorOne">art_track</i>
                <p class="colorOne"><?php echo $menu['name'] ?></p>
              </a>
            </li>

            <?php elseif ($menu['name'] == "NHIF Claims"): ?>
            <li class="nav-item <?php if ($PageName == "NHIF Claims") {
	echo 'active';
}
?> ">
              <a class="nav-link" href="<?php echo $menu['url'] ?>" onclick="SetSelectedPage('NHIF Claims')">
                <i class="fa fa-microchip"></i>
                <p class="colorOne"><?php echo $menu['name'] ?></p>
              </a>
            </li>

            <?php elseif ($menu['name'] == "Reporting"): ?>
            <li class="nav-item <?php if ($PageName == "Reporting") {
	echo 'active';
}
?> ">
              <a class="nav-link" href="<?php echo $menu['url'] ?>" onclick="SetSelectedPage('Reporting')">
                <i class="material-icons colorOne">file_copy</i>
                <p class="colorOne"><?php echo $menu['name'] ?></p>
              </a>
            </li>

            <?php elseif ($menu['name'] == "Directory"): ?>
            <li class="nav-item <?php if ($PageName == "Directory") {
	echo 'active';
}
?> ">
              <a class="nav-link" href="<?php echo $menu['url'] ?>" onclick="SetSelectedPage('Directory')">
                <i class="material-icons colorOne">list</i>
                <p class="colorOne"><?php echo $menu['name'] ?></p>
              </a>
            </li>

            <?php elseif ($menu['name'] == "System Admin"): ?>
            <li class="nav-item <?php if ($PageName == "System Admin") {
	echo 'active';
}
?> ">
              <a class="nav-link" target="_blank" href="<?php echo $menu['url'] ?>" onclick="SetSelectedPage('System Admin')">
                <i class="material-icons colorOne">settings</i>
                <p class="colorOne"><?php echo $menu['name'] ?></p>
              </a>
            </li>

            <?php elseif ($menu['name'] == "Intranet Email"): ?>
            <li class="nav-item <?php if ($PageName == "Intranet Email") {
	echo 'active';
}
?> ">
              <a class="nav-link" href="<?php echo $menu['url'] ?>" onclick="SetSelectedPage('Intranet Email')">
                <i class="material-icons colorOne">email</i>
                <p class="colorOne"><?php echo $menu['name'] ?></p>
              </a>
            </li>

            <?php elseif ($menu['name'] == "Special Tools"): ?>
            <li class="nav-item <?php if ($PageName == "Special Tools") {
	echo 'active';
}
?> ">
              <a class="nav-link" href="<?php echo $menu['url'] ?>" onclick="SetSelectedPage('Special Tools')">
                <i class="material-icons colorOne">perm_data_setting</i>
                <p class="colorOne"><?php echo $menu['name'] ?></p>
              </a>
            </li>


            <?php elseif ($menu['name'] == "Referrer Notes"): ?>
            <li class="nav-item <?php if ($PageName == "Referrer Notes") {
	echo 'active';
}
?> ">
              <a class="nav-link"  href="<?php echo $menu['url'] ?>" onclick="SetSelectedPage('Referrer Notes')">
                <i class="material-icons colorOne">perm_data_setting</i>
                <p class="colorOne"><?php echo $menu['name'] ?></p>
              </a>
            </li>

            <?php elseif ($menu['name'] == "Accounting & Stock"): ?>
            <li class="nav-item <?php if ($PageName == "Accounting & Stock") {
	echo 'active';
}
?> ">
              <a class="nav-link" target="_blank" href="<?php echo $menu['url'] ?>" onclick="SetSelectedPage('Accounting & Stock')">
                <i class="material-icons colorOne">shop_two</i>
                <p class="colorOne"><?php echo $menu['name'] ?></p>
              </a>
            </li>

            <?php elseif ($menu['name'] == "Login"): ?>
            <li class="nav-item <?php if ($PageName == "Login") {
	echo 'active';
}
?> ">
              <a class="nav-link" href="<?php echo $menu['url'] ?>" onclick="SetSelectedPage('Login')">
                <i class="material-icons colorOne">lock</i>
                <p class="colorOne"><?php echo $menu['name'] ?></p>
              </a>
            </li>

            <?php elseif ($menu['name'] == "Logout"): ?>
            <li class="nav-item <?php if ($PageName == "Logout") {
	echo 'active';
}
?> ">
              <a class="nav-link" href="<?php echo $menu['url'] ?>" onclick="SetSelectedPage('Logout')">
                <i class="material-icons colorOne">exit_to_app</i>
                <p class="colorOne"><?php echo $menu['name'] ?></p>
              </a>
            </li>

            <?php else: ?>
            <li class="nav-item ">
            <a class="nav-link"  href="<?php echo $menu['url'] ?>" onclick="SetSelectedPage('Home')">
              <i class="material-icons colorOne">dashboard</i>
              <p class="colorOne"><?php echo $menu['name'] ?></p>
            </a>
            </li>
          <?php endif?>
          <?php endforeach?>


        <li class="nav-item ">
            <a class="nav-link"  href="">
              <i class="material-icons colorOne">dashboard</i>
              <p class="colorOne">User: <?php echo $_SESSION['sess_login_userid'] ?></p>
            </a>
            </li>

        </ul>
      </div>
    </div>

