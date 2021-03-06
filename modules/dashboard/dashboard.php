<?php

error_reporting(E_COMPILE_ERROR | E_ERROR | E_CORE_ERROR);
require_once('./roots.php');
require_once($root_path . 'include/inc_environment_global.php');
/**
 * CARE2X Integrated Hospital Information System Deployment 2.1 - 2004-10-02
 * GNU General Public License
 * Copyright 2002,2003,2004,2005 Elpidio Latorilla
 * elpidio@care2x.org,
 *
 * See the file "copy_notice.txt" for the licence notice
 */
define('LANG_FILE', 'startframe.php');
define('NO_CHAIN', 1);

require_once($root_path . 'include/inc_front_chain_lang.php');

$thisfile = basename($_SERVER['PHP_SELF']);
$default_filebreak = $root_path . 'modules/news/start_page.php' . URL_APPEND;

if (empty($_SESSION['sess_path_referer']) || !file_exists($root_path . $_SESSION['sess_path_referer'])) {
    $breakfile = $default_filebreak;
} else {
    $breakfile = $root_path . $_SESSION['sess_path_referer'] . URL_APPEND;
}

if ($_COOKIE['ck_login_logged' . $sid])
    $breakfile = $root_path . 'modules/news/start_page.php' . URL_APPEND;
else
    $breakfile = 'aufnahme_pass.php' . URL_APPEND . '&target=entry';

$dept_nr = 1; # 1 = press relations
# Get the maximum number of headlines to be displayed
$config_type = 'news_headline_max_display';
include($root_path . 'include/inc_get_global_config.php');

if (!isset($news_headline_max_display) || !$news_headline_max_display)
    $news_num_stop = 3;# default is 3
else
    $news_num_stop = $news_headline_max_display;# The maximum number of news article to be displayed
//include($root_path.'include/inc_news_get.php'); // now get the current news
$thisfile = basename($_SERVER['PHP_SELF']);
require_once($root_path . 'include/care_api_classes/class_news.php');
$newsobj = new News;
$news = $newsobj->getHeadlinesPreview($dept_nr, $news_num_stop);



$readerpath = 'headline-read.php' . URL_APPEND;
# Load the news display configs
require_once($root_path . 'include/inc_news_display_config.php');

# Start Smarty templating here
/**
 * LOAD Smarty
 */
# Note: it is advisable to load this after the inc_front_chain_lang.php so
# that the smarty script can use the user configured template theme

require_once($root_path . 'gui/smarty_template/smarty_care.class.php');
$smarty = new smarty_care('common');

# Hide the title bar
$smarty->assign('bHideTitleBar', TRUE);

# Window title
$smarty->assign('title', isset($LDPageTitle));

$smarty->assign('news_normal_display_width', $news_normal_display_width);

# Headline title
$smarty->assign('LDHeadline', $LDHeadline);

$smarty->assign('sOnLoadJs', 'onLoad="window.parent.STARTPAGE.location.href=\'' . $root_path . 'main/indexframe.php?sid=' . $sid . '&lang=' . $lang . '\';"');


#Collect html code

/**
 * Routine to display the headlines
 */





# Collect html for the submenu blocks

ob_start();

include($root_path . 'include/inc_rightcolumn_menu.php');

# Stop buffering, get contents

$sTemp = ob_get_contents();

ob_end_clean();

# assign contents to subframe

$smarty->assign('sSubMenuBlock', $sTemp);

$pageName = "Home";

?>


<?php 
require_once($root_path . 'main_theme/head.inc.php');
require_once($root_path . 'main_theme/header.inc.php');

 ?>

<style>
  .mobileNav {
    margin-left: 400px;
  }
  .showMobileBar {
    display: none;
  }
  @media only screen and (max-width: 600px) {
  .mobileNav {
    margin-left: 0;
  }
  .showMobileBar {
    display: block;
  }
}
</style>
 <div class="main-panel mobileNav" style="" >
      <nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top ">
        <div class="container-fluid">
          <div class="navbar-wrapper">
            <a class="navbar-brand" href="#">Dashboard</a>
          </div>
          <button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
            <span class="sr-only">Toggle navigation</span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
          </button>
          <div class="collapse navbar-collapse justify-content-end">
            <form class="navbar-form">
              <div class="input-group no-border">
                <input type="text" value="" class="form-control" placeholder="Search...">
                <button type="submit" class="btn btn-white btn-round btn-just-icon">
                  <i class="material-icons">search</i>
                  <div class="ripple-container"></div>
                </button>
              </div>
            </form>
            <ul class="navbar-nav">
              <li class="nav-item">
                <a class="nav-link" href="#pablo">
                  <i class="material-icons">dashboard</i>
                  <p class="d-lg-none d-md-block">
                    Stats
                  </p>
                </a>
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="material-icons">notifications</i>
                  <span class="notification">0</span>
                  <p class="d-lg-none d-md-block">
                    Some Actions
                  </p>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                  <a class="dropdown-item" href="#">No Notifications</a>
                </div>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#pablo">
                  <i class="material-icons">person</i>
                  <p class="d-lg-none d-md-block">
                    Account
                  </p>
                </a>
              </li>
            </ul>
          </div>
        </div>
      </nav>

      <div class="content" style="background-color: white; margin: 10px;">
        <div class="container-fluid">
          <div>
            
          </div>
          <div class="row">

            <div class="col-md-12">
              <div class="card card-chart">
                <div class="card-header">
                  <div class="row">
                    <div class="col-md-5 d-flex justify-content-start">
                      <h4 class="card-title">Top Diseases</h4> 
                    </div>
                    
                    <div class="col-md-7 d-flex justify-content-end">

                      <div class="row">
                        <div class="col-md-5">
                          <select class="custom-select input-sm col-md-12" id="topdiseaseperiod">
                            <option >Select View</option>
                            <option value="ThisWeek" selected>This Week</option>
                            <option value="ThisMonth">This Month</option>
                            <option value="ThisYear" >This Year</option>
                            <option value="LastWeek">Last Week</option>
                            <option value="LastMonth">Last Month</option>
                            <option value="LastYear">Last Year</option>
                          </select>
                        </div>
                        
                        <div class="col-md-4">
                          <select class="custom-select input-sm col-md-12" id="topdiseasesselect">
                            <option >Select View</option>
                            <option value="5">5</option>
                            <option selected value="10">10</option>
                            <option value="15">15</option>
                            <option value="20">20</option>
                            <option value="25">25</option>
                          </select>
                        </div>

                        <div class="col-md-3">
                          <button onclick="showTopDiseases()" class="btn btn-success btn-primary btn-sm">Show</button>
                        </div>
                        
                      </div>
                    </div>
                  </div>
                </div>
                <div class="card-body topdiseases" style="min-height: 500px;">
                  <canvas id="topdiseases"></canvas>
                </div>
                <div class="card-footer">
                  <div class="stats">
                  </div>
                </div>
              </div>
            </div>

            <div class="col-md-12">
              <div class="card card-chart">

                <div class="card-header">
                  <div class="row">
                    <div class="col-md-5 d-flex justify-content-start">
                      <h4 class="card-title">Patient TrendsPatient Trends</h4> 
                    </div>
                    
                    <div class="col-md-7 d-flex justify-content-end">

                      <div class="row">
                        
                        <div class="col-md-8">
                          <select class="custom-select input-sm col-md-12" id="patienttrendselect">
                            <option >Select View</option>
                            <option selected value="ThisWeek">This Week</option>
                            <option value="ThisMonth">This Month</option>
                            <option value="ThisYear">This Year</option>
                            <option  value="LastWeek">Last Week</option>
                            <option value="LastMonth">Last Month</option>
                            <option value="LastYear">Last Year</option>
                          </select>
                        </div>

                        <div class="col-md-3" style="margin-right: 20px;">
                          <button onclick="showPatientTrends()" class="btn btn-success btn-primary btn-sm">Show</button>
                        </div>
                        
                      </div>
                    </div>
                  </div>
                </div>

                <div class="card-body patienttrends" style="min-height: 500px;">
                  <canvas class="" id="patienttrends"></canvas>

                  <p class="card-category"></p>
                </div>
                <div class="card-footer">
                </div>
              </div>
            </div>

            <div class="col-md-12">
              <div class="card card-chart">
                <div class="card-header ">
                   <div class="row">
                    <div class="col-md-6 d-flex justify-content-start">
                      <h4 class="card-title">Served & Unserved Patients</h4> 
                    </div>
                    <div class="col-md-6 d-flex  justify-content-end">
                        <div class="row">
                          <div class="col-md-8">
                              <select class="custom-select input-sm col-md-12" id="servedpatientselect">
                                <option >Select View</option>
                                <option selected value="ThisWeek">This Week</option>
                                <option value="ThisMonth">This Month</option>
                                <option value="ThisYear">This Year</option>
                                <option  value="LastWeek">Last Week</option>
                                <option value="LastMonth">Last Month</option>
                                <option value="LastYear">Last Year</option>
                              </select>
                            </div>
                            <div class="col-md-3" style="margin-right: 20px;">
                              <button onclick="showServedPatients()" class="btn btn-success btn-primary btn-sm">Show</button>
                            </div>
                          </div>
                        </div>
                    </div>
    
                </div>
                <div class="card-body servedpatients" style="min-height: 500px;">
                  <canvas class="" id="servedpatients" style="max-height: 400px;min-height: 300px;"></canvas>

                  <p class="card-category">
                </div>
                <div class="card-footer">
                </div>
              </div>
            </div>

            <div class="col-md-12">
              <div class="card card-chart">
                <div class="card-header">
                   <div class="row">
                    <div class="col-md-6 d-flex justify-content-start">
                      <h4 class="card-title">Frequently used drugs</h4> 
                    </div>

                    <div class="col-md-6 d-flex justify-content-end">
                     <div class="row">
                        <div class="col-md-5">
                          <select class="custom-select input-sm col-md-12" id="frequentdrugsperiod">
                            <option >Select View</option>
                            <option value="ThisWeek" selected>This Week</option>
                            <option value="ThisMonth">This Month</option>
                            <option value="ThisYear">This Year</option>
                            <option value="LastWeek">Last Week</option>
                            <option value="LastMonth">Last Month</option>
                            <option value="LastYear">Last Year</option>
                          </select>
                        </div>
                        
                        <div class="col-md-4">
                          <select class="custom-select input-sm col-md-12" id="frequentdrugsselect">
                            <option >Select View</option>
                            <option value="5">5</option>
                            <option selected value="10">10</option>
                            <option value="15">15</option>
                            <option value="20">20</option>
                            <option value="25">25</option>
                          </select>
                        </div>

                        <div class="col-md-3">
                          <button onclick="showFrequentDrugs()" class="btn btn-success btn-primary btn-sm">Show</button>
                        </div>
                        
                        
                      </div>
                      
                    </div>
                  </div>
                </div>
                <div class="card-body frequentdrugs" style="min-height: 500px;">
                  <canvas id="frequentdrugs"></canvas>
                </div>
                <div class="card-footer">
                  <div class="stats">
                  </div>
                </div>
              </div>
            </div>

          </div>
      
        </div>
      </div>


 <?php require_once($root_path . 'main_theme/footer.inc.php'); ?>
 <?php require_once($root_path . 'js/care_md/dashboard_chart_js.php'); ?>
