<?php


       ob_start();



try{
    $db=new PDO( "mysql:host=localhost;dbname=blog;charset=utf8;" ,"root","");
    $db->query("SET CHARACTER SET UTF8");
    $db->query("SET NAMES UTF8");

} catch(PDOException $hata){
    echo $hata->getMessage();
}


##ayarlar tablosuna bağlanma
$ayarlar = $db ->prepare("SELECT * FROM ayarlar");
$ayarlar->execute();
$arow = $ayarlar->fetch(PDO::FETCH_OBJ);
$site =$arow->site_url;
$sitebaslik= $arow->site_baslik;
$sitekeyw= $arow->site_keyw;
$sitedesc= $arow->site_desc;
$logo =$arow->site_logo;


if($arow->site_durum!=1){
    header('location:bakimmodu.php');
}


?>