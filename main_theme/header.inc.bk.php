<!-- <?php if ($themeName == "blue"): ?>
  <link href="<?php echo $root_path ?>css/themes/care_md/blue-theme.css" rel="stylesheet"/>
  <?php elseif($themeName == "green"): ?>
    <link href="<?php echo $root_path ?>css/themes/care_md/green-theme.css" rel="stylesheet"/>
  <?php elseif($themeName == "red"): ?>
    <link href="<?php echo $root_path ?>css/themes/care_md/red-theme.css" rel="stylesheet"/>
<?php endif ?>
 -->
<body class="">
  <div class="wrapper ">

  
  <?php if (@$themeName): ?>
    <div class="sidebar" data-color="<?php echo $themeName ?>" data-background-color="white">
    <?php else: ?>
  <?php endif ?>