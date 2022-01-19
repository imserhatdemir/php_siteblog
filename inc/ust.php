<?php
echo !defined('guvenlik') ? die() : null;
require_once 'sistem/fonksiyon.php';?>
<!DOCTYPE html>
<html lang="en">

 <head>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="author" content="<?php echo $arow->site_baslik; ?>" />
    <meta name="description" content="<?php echo $tit['aciklama'];?>">
     <meta name="keywords" content="<?php echo $tit['kelimeler'];?>">
    <!-- Document title -->
    <title><?php echo $tit['baslik'];?></title>
    <!-- Stylesheets & Fonts -->
    <link href="css/plugins.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/responsive.css" rel="stylesheet">
     <link href="js/sweetalert/sweetalert.css" rel="stylesheet">
     <link rel="icon" type="image/png" href="<?php echo $arow->site_url;?>/images/<?php echo $arow->site_favicon;?>">

         <meta name="google-site-verification" content="<?php echo $arow->site_dogrulama_kodu; ?>"/>
     <meta name="yandex-verification" content="<?php echo $arow->yandex_dogrulama_kodu; ?>"/>
     <meta name="msvalidate.01" content="<?php echo $arow->bing_dogrulama_kodu; ?>"/>
     <meta name="robots" content="index, follow"/>

 </head>

<body data-animation-in="fadeIn"  data-animation-out="fadeOut" data-icon="2" data-icon-color="#072a16" data-speed-in="1000" data-speed-out="1000">

    <!-- Body Inner -->
    <div class="body-inner">
