<?php
/**
 * Created by PhpStorm.
 * User: Gambler
 * Date: 2/8/2018
 * Time: 10:50
 */
?>
<!DOCTYPE html>
<html class="fixed" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="UTF-8">
    <title>BenePicture Api</title>
    <!-- Mobile Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="description" content="Fx Trainer Api Document Page"/>

    <!--header_link-->
    <?php require_once 'link_admin_header.php'; ?>

    <style>
        html {
            background-color: white !important;
        }
    </style>

</head>
<body>
<section class="body" style="background-color: white">

    <div class="inner-wrapper">
        <section role="main" class="content-body" style="padding: 20px" id="main_content">
            <!-- start: page -->
            <?php echo $main?>
            <!-- end: page -->
        </section>
    </div>
</section>
<!--footer_link-->
<?php require_once 'link_admin_footer.php'; ?>
</body>
</html>