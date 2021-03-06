<html lang="en">
    <head>
        <title>
            <?php $_COOKIE['PageName']=@$_COOKIE['PageName'] ?? "";?>
            <?php if ($_COOKIE['PageName'] == "Reporting"): ?>
                <?php if (@$_COOKIE['report_sub']): ?>
                    <?php echo $_COOKIE['report_sub'] ?>
                <?php else: ?>
                CAREMD REPORTS
                <?php endif ?>
            <?php elseif ($_COOKIE['PageName'] == "NHIFPrice"): ?>
                NHIF Prices
            <?php else: ?>
                <?php echo @($_COOKIE['PageName'])?$_COOKIE['PageName']:"" ?> - CareMd
            <?php endif ?>
        </title>
        <meta charset="utf-8"/>
        <link href="img/apple-icon.png" rel="apple-touch-icon" sizes="76x76">
            <link href="img/favicon.png" rel="icon" type="image/png">
            <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible"/>
            <meta content="Hospital and Healthcare Integrated Information System - CAREMD" name="Description">
            <meta content="Rayton Kiwelu" name="Author">
            <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no" name="viewport"/>
            <!--     Fonts and icons     -->
            <link href="<?php echo $root_path ?>css/themes/care_md/material-font.css" rel="stylesheet"/>
            <!-- CSS Files -->


            <link href="<?php echo $root_path ?>css/themes/care_md/material-dashboard.css?v=2.1.0" rel="stylesheet"/>
            <link href="<?php echo $root_path ?>css/themes/care_md/font-awesome.min.css" rel="stylesheet"/>
            <link href="<?php echo $root_path ?>css/themes/care_md/jquery.Wload.css" rel="stylesheet"/>
            <link href="<?php echo $root_path ?>css/themes/care_md/main.css" rel="stylesheet"/>
            <link href="<?php echo $root_path ?>css/themes/care_md/buttons.dataTables.min.css" rel="stylesheet"/>

            <!-- <link rel="stylesheet" href="<?php echo $root_path; ?>assets/bootstrap/css/bootstrap.min.css" > -->
            <link href="<?php echo $root_path; ?>assets/datatables/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css"/>
            <link href="<?php echo $root_path; ?>assets/datatables/plugins/fixedHeader/css/fixedHeader.dataTables.min.css" rel="stylesheet" type="text/css"/>
            <!-- <link href="<?php echo $root_path; ?>assets/datatables/plugins/responsive/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css"/> -->
                

    </head>

    <noframes>
        <body bgcolor="white">
            {{$LDNoFrame}}
            <br>
                <a href="contents.htm">
                    OK
                </a>
            </br>
        </body>
    </noframes>
</html>
