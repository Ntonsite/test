<style>
  
  .showMobileBar {
    display: none;
  }
  .responsivePadding {
    margin-top: -20px !important;   
  }
  .main-panel > .navbar {
    display: none;
  }
  @media only screen and (max-width: 991px) {
  .showMobileBar {
    display: block;
    font-size: 20px;
    line-height: 2rem;
    margin-right: 25rem;
  }

  .responsivePadding {
    margin-top: 20px !important;
  }
  .main-panel > .navbar {
    display: block;
    min-width: 600px;
  }

.sidebar{
  width: 260px;
}
.sidebar .sidebar-wrapper{
  width: 260px;
}

}
</style>

<div class="main-panel">
      <nav class="navbar navbar-expand-lg navbar-absolute fixed-top ">
        <div class="container-fluid">
          <!-- <div class="navbar-wrapper">
            <a class="navbar-brand" href="#pablo"><?php echo @($pageName)?$pageName: ""; ?></a>
          </div> -->
          <button class="navbar-toggler float-left" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
            <span class="sr-only">Toggle navigation</span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
          </button>
            <!-- <h4 class="showMobileBar">CareMD</h4> -->
          
          <div class="collapse navbar-collapse justify-content-start">
           
           <!--  <ul class="navbar-nav">
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
            </ul> -->
          </div>
        </div>
      </nav>

  <div class="content responsivePadding">

<!-- 
<div class="" style="margin-left: 270px; mar">
  
<div></div>
 -->