<?php
require_once '../sistem/fonksiyon.php';
if($_POST){
    $ad=post('isim');
    $eposta=post('eposta');
    $konu=post('icerik');
    

    if(!$ad||!$eposta||!$konu){
        echo"bos";
    }else {
        if(!filter_var($eposta,FILTER_VALIDATE_EMAIL)){
            echo"format";
        }else{
            $kaydet =$db->prepare("INSERT INTO forum SET isim=:i,eposta=:e,icerik=:k");
            $kaydet->execute(
                [
                    ':i'=>$ad,
                    ':k'=>$konu,
                    ':e'=>$eposta,
                    
                ]
            );
            if($kaydet){
                echo "basarili";
            }else{
                echo "hata";
            }
        }
    }
}



?>