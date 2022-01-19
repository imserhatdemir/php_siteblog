<?php


require_once 'inc/ust.php';

?>

    <!-- Sidebar menu-->
    <?php require_once 'inc/sol.php'; ?>


    <main class="app-content">
      <div class="app-title">
        <div>
          <h1><i class="fa fa-dashboard"></i> Yönetim Paneli</h1>
          <p>Blog Sitesi | Yönetim Paneli</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item"><a href="#">Ana Sayfa</a></li>
        </ul>
      </div>
      <div class="row">
        <div class="col-md-6 col-lg-3">
          <div class="widget-small primary coloured-icon"><i class="icon fa fa-users fa-3x"></i>
            <div class="info">
              <h4>FORUM</h4>
              <p><b><?php echo say('forum') ;?> Forum</b></p>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-lg-3">
          <div class="widget-small info coloured-icon"><i class="icon fa fa-comment fa-3x"></i>
            <div class="info">
              <h4>Yorumlar</h4>
              <p><b><?php echo say('yorumlar') ;?> Yorum</b></p>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-lg-3">
          <div class="widget-small warning coloured-icon"><i class="icon fa fa-files-o fa-3x"></i>
            <div class="info">
              <h4>Yazılar</h4>
              <p><b><?php echo say('yazilar'); ?> Yazı</b></p>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-lg-3">
          <div class="widget-small danger coloured-icon"><i class="icon fa fa-envelope fa-3x"></i>
            <div class="info">
              <h4>Mesajlar</h4>
              <p><b><?php echo say('mesajlar'); ?> Mesaj</b></p>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="tile">
            <h3 class="tile-title">Okunmamış Mesajlar</h3>
            <?php
            $sonmesaj=$db->prepare("SELECT * FROM mesajlar WHERE durum=:d ORDER BY id DESC LIMIT :lim ");
            $sonmesaj->bindValue(':d',(int) 2,PDO::PARAM_INT);
            $sonmesaj->bindValue(':lim',(int) 10,PDO::PARAM_INT);
            $sonmesaj->execute();
            if($sonmesaj->rowCount()){

                ?>
                <div class="table-responsive table-hover">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>#ID</th>
                            <th>İSİM</th>
                            <th>KONU</th>
                            <th>TARİH</th>
                            <th>İŞLEMLER</th>
                        </tr>
                        </thead>
                        <tbody>
                    <?php foreach ($sonmesaj as $son){?>
                    <tr>
                        <td><?php echo $son['id']; ?></td>
                        <td><?php echo $son['isim']; ?></td>
                        <td><?php echo $son['konu']; ?></td>
                        <td><?php echo date('d.m.y',strtotime($son['tarih'])); ?></td>
                        <td><a href="<?php echo $yonetim;?>/islemler.php?islem=mesajoku&id=<?php echo $son['id']; ?>"><i class="fa fa-eye"></i></a></td>
                    </tr>
<?php }?>
                        </tbody>
                    </table>
                </div>


                    <?php
            }else{
                echo "<div class='alert alert-danger'>Okunmamış Mesaj Bulunmuyor</div>";
            }




            ?>
          </div>
        </div>

              <div class="col-md-6">
                  <div class="tile">
                      <h3 class="tile-title">Onaylanmamış Yorumlar</h3>
                    <?php
                      $sonyorum=$db->prepare("SELECT * FROM yorumlar
                    INNER JOIN yazilar ON yazilar.yazi_id=yorumlar.yorum_yazi_id
                    WHERE yorum_durum=:d ORDER BY id DESC LIMIT :lim ");
                      $sonyorum->bindValue(':d',(int) 2,PDO::PARAM_INT);
                      $sonyorum->bindValue(':lim',(int) 10,PDO::PARAM_INT);
                      $sonyorum->execute();
                      if($sonyorum->rowCount()){
                    ?>

                          <div class="table-responsive table-hover">
                              <table class="table">
                                  <thead>
                                  <tr>
                                      <th>#ID</th>
                                      <th>İSİM</th>
                                      <th>YAZI</th>
                                      <th>TARİH</th>
                                      <th>İŞLEMLER</th>
                                  </tr>
                                  </thead>
                                  <tbody>
                                  <?php foreach ($sonyorum as $son){?>
                                      <tr>
                                          <td><?php echo $son['id']; ?></td>
                                          <td><?php echo $son['yorum_isim']; ?></td>
                                          <td><?php echo $son['yazi_baslik']; ?></td>
                                          <td><?php echo date('d.m.y',strtotime($son['yorum_tarih'])); ?></td>
                                          <td><a href="<?php echo $yonetim;?>/islemler.php?islem=yorumoku&id=<?php echo $son['id']; ?>"><i class="fa fa-eye"></i></a></td>
                                      </tr>
                                  <?php }?>
                                  </tbody>
                              </table>
                          </div>


                          <?php
                      }else{
                          echo "<div class='alert alert-danger'>Onaylanmamış Yorum Bulunmuyor</div>";
                      }




                      ?>
                  </div>
              </div>

      </div>
    </main>


<?php require_once 'inc/alt.php'; ?>