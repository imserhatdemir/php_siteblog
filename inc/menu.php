<?php
echo !defined('guvenlik') ? die() : null;

?>
<header id="header">
    <div class="header-inner" style="border-bottom:2px solid #cccccc;" >
        <div class="container">
            <!--Logo-->
            <div id="logo">
                <a href="<?php echo $arow->site_url; ?>" class="logo" data-src-dark="<?php echo $arow->site_logo; ?>"> <img width="250" height="100" style="padding: 10px" src="<?php echo $arow->site_url; ?>/images/<?php echo $arow->site_logo; ?>" alt="" > </a>
            </div>
            <!--End: Logo-->

            <!-- Search -->
            <div id="search">
                <div id="search-logo"><img style="height:75px;width: 250px" src="<?php echo $arow->site_url; ?>/images/<?php echo $arow->site_logo; ?>" ></div>
                <button id="btn-search-close" class="btn-search-close" aria-label="Close search form"><i
                            class="icon-x"></i></button>
                <form class="search-form" action="ara.php" method="get">
                    <input class="form-control" name="q" type="search" placeholder="Yazi Arayın..."
                           autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" />
                    <span class="text-muted">Yazı Başlığı Belirterek Arama Yapabilirsiniz ya da "ESC" ile Çıkış Yapabilirsiniz.</span>
                </form>

                <div class="search-suggestion-wrapper">
                <?php
                $populer=$db->prepare("SELECT * FROM yazilar  WHERE yazi_durum=:d ORDER BY yazi_goruntulenme DESC LIMIT :lim");

                $populer->bindValue(':d',(int)1,PDO::PARAM_INT);
                $populer->bindValue(':lim',(int)3,PDO::PARAM_INT);
                $populer->execute();

                if($populer->rowCount()) {
                    foreach ($populer as $item) {
                ?>

                        <div class="search-suggestion">
                            <h3><?php echo $item['yazi_baslik']; ?></h3>
                            <p><?php echo mb_substr($item['yazi_icerik'],0,250,'utf8'); ?></p>
                            <p><a href="<?php echo $arow->site_url;?>/yazidetay.php?yazi_sef=<?php echo $item['yazi_sef'];?>&id=<?php echo $item['yazi_id']; ?>">Devamını Oku</a></p>
                        </div>


                        <?php
                    }
                }
                ?>



                </div>
            </div>
            <!-- end: search -->

            <!--Header Extras-->
            <div class="header-extras">
                <ul>
                    <li>
                        <!--search icon-->
                        <a id="btn-search" href="#"> <i class="icon-search1"></i></a>
                        <!--end: search icon-->
                    </li>

                </ul>
            </div>
            <!--end: Header Extras-->
            <!--Navigation Resposnive Trigger-->
            <div id="mainMenu-trigger">
                <button class="lines-button x"> <span class="lines"></span> </button>
            </div>
            <!--end: Navigation Resposnive Trigger-->

            <!--Navigation-->
            <div id="mainMenu">
                <div class="container">
                    <nav>
                        <ul>
                            <li><a href="#"><i class ="fa fa-home"></i>Anasayfa</a></li>
                            <li><a href="<?php echo $arow->site_url; ?>"><i class ="fa fa-book"></i>Blog</a></li>
                            <li class="dropdown"> <a href="#"><i class ="fa fa-list"></i>Kategoriler</a>
                                <ul class="dropdown-menu">

                                   <?php
                                   $kategoriler=$db->prepare("SELECT * FROM kategoriler");
                                   $kategoriler->execute();
                                   if($kategoriler->rowCount()){
                                       foreach ($kategoriler as $row){
                                           $yazilaribul=$db->prepare("SELECT yaz_kat_id ,yazi_durum FROM yazilar WHERE yaz_kat_id=:id");
                                           $yazilaribul->execute([':id'=>$row['id']]);


                                           echo ' <li><a href="'.$arow->site_url.'/kategoriler.php?kat_sef='.$row['kat_sef'].'">'.$row['kat_adi'].'</a></li>';

                                       }
                                   }



                                   ?>
                                </ul>
                            </li>
                            <li><a href="<?php echo $arow->site_url.'/iletisim.php' ?>"><i class ="fa fa-envelope"></i>İletişim</a></li>
                            <li><a href="<?php echo $arow->site_url.'/forum.php' ?>"><i class ="fa fa-pencil"></i>Forum</a></li>
                            
                        </ul>
                    </nav>
                </div>
            </div>
            <!--END: NAVIGATION-->
        </div>
    </div>
</header>