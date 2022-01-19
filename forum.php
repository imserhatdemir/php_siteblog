<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
define('guvenlik',true);
require_once 'inc/ust.php';?>

    <!-- Header -->
<?php require_once 'inc/menu.php';?>
    <!-- end: Header -->

    <!-- Page Content -->
    <section id="page-content" class="sidebar-right">
        <div class="container">
            <div class="row">
                <!-- content -->
                <div class="content col-lg-9">
                    <!-- Blog -->


                    

                        <div id="blog" class="single-post">
                            <!-- Post single item-->
                            <div class="post-item">
                                <div class="post-item-wrap">
                                   
                                   
                                    

                                    <!-- Comments -->
                                   <?php
                                   $yorumlar=$db->prepare("SELECT * FROM forum WHERE durum=:d");
                                   $yorumlar->execute([':d'=>1]);
                                   if($yorumlar->rowCount()){
                                       ?>
                                       <div class="comments" id="comments">
                                           <div class="comment_number">
                                               Forum <span>(<?php echo $yorumlar->rowCount(); ?>)</span>
                                           </div>
                                           <div class="comment-list">
                                               <!-- Comment -->

                                               <!-- Comment -->
                                               <?php foreach ($yorumlar as $yor){?>
                                                   <div class="comment" id="comment-2">
                                                       <div class="image"><img alt="" src="<?php echo $arow->site_url;?>/images/yorum.png" class="avatar"></div>
                                                       <div class="text">
                                                           <h5 class="name"><a href="#" target="_blank"><?php echo $yor['isim'];?></a></h5>
                                                           <span class="comment_date"><?php echo date('d.m.y', strtotime( $yor['tarih']));?></span>

                                                           <div class="text_holder">
                                                               <p >SORU: <?php echo $yor['icerik']; ?></p>
                                                           </div>
                                                           <div class="text_holder">
                                                               <p >CEVAP: <?php echo $yor['cevap']; ?></p>
                                                           </div>
                                                       </div>
                                                   </div><hr>

                                               <?php }?>
                                               <!-- end: Comment -->
                                           </div>
                                       </div>

                                    <?php

                                   }else{
                                       echo '<div class="alert alert-danger">Bu Foruma Henüz Yorum Yapılmamıştır</div>';
                                   }

                                   ?>
                                    <!-- end: Comments -->
                                    <div class="respond-form" id="respond">
                                        <div class="respond-comment">
                                            Soru Yapın</div>
                                        <form id="soruformu" class="form-gray-fields" action="" method="POST" onsubmit="return false;">
                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        <label class="upper" for="name">Ad Soyad</label>
                                                        <input class="form-control required" name="isim" placeholder="Ad Soyad" id="name" aria-required="true" type="text">
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        <label class="upper" for="email">E-posta (Yayınlanmayacaktır)</label>
                                                        <input class="form-control required email" name="eposta" placeholder="E-posta" id="email" aria-required="true" type="email">
                                                    </div>
                                                </div>
                                                
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="form-group">
                                                        <label class="upper" for="comment">Yorumunuz</label>
                                                        <textarea class="form-control required" name="icerik" rows="9" placeholder="Yorum Giriniz" id="comment" aria-required="true"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="form-group text-center">
                                                        
                                                        <button class="btn" type="submit" onclick="sorusor();">Yorum Yap</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- end: Post single item-->
                        </div>



                       

                </div>
                <!-- end: content -->

                <!-- Sidebar-->
                <?php require_once 'inc/fsag.php';?>
                <!-- end: Sidebar-->
            </div>
        </div>
    </section>
    <!-- end: Page Content -->
    <!-- Footer -->
<?php require_once 'inc/alt.php';?>