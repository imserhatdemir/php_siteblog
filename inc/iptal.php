fsd fdgshfgh nmöjçömcvb dg dfjhg hnmf dfhjdfgvfsdzgbgfvzx
<div class="post-navigation">
    <?php if($oncekikonubul->rowCount()){ ?>


        <a href="<?php echo $arow->site_url; ?>/yazidetay.php?yazi_sef=<?php echo $oncekikonurow->yazi_sef; ?>&id=<?php echo $oncekikonurow->yazi_id; ?>" class="post-prev">
            <div class="post-prev-title"><span>Önceki Konu</span><?php echo $oncekikonurow->yazi_baslik; ?></div>
        </a>
    <?php }else{?>

        <a href="" class="post-prev">
            <div class="post-prev-title"><span>Önceki Konu</span>Önceki Konu Bulunmuyor</div>
        </a>

    <?php }?>

    <a href="#" class="post-all">
        <i class="icon-grid">                </i>
    </a>
    <?php if($sonrakikonubul->rowCount()){ ?>
        <a href="<?php echo $arow->site_url; ?>/yazidetay.php?yazi_sef=<?php echo $sonrakikonurow->yazi_sef; ?>&id=<?php echo $sonrakikonurow->yazi_id; ?>" class="post-next">
            <div class="post-next-title"><span>Sonraki Konu</span><?php echo $sonrakikonurow->yazi_baslik; ?></div>
        </a>
    <?php } else{
        ?>

        <a href="" class="post-prev">
            <div class="post-prev-title"><span>Sonraki Konu</span>Sonraki Konu Bulunmuyor</div>
        </a>

    <?php }?>
</div>