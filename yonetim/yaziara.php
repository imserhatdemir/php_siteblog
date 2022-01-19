<?php

require_once 'inc/ust.php'; ?>
    <!-- Sidebar menu-->
<?php require_once 'inc/sol.php'; ?>

    <main class="app-content">
      <div class="app-title">
        <div>
          <h1><i class="app-menu__icon fa fa-laptop"></i> YAZI ARAMA SONUÇLARI </h1>
          <p>YAZI ARAMA SONUÇLARI</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item">Yazı Listesi</li>
          <li class="breadcrumb-item active"><a href="#">YAZI ARAMA SONUÇLARI</a></li>
        </ul>
      </div>
      <div class="row">

          <div class="col-md-12">
              <form action="<?php echo $yonetim; ?>/yaziara.php" method="GET">
                  <input type="text" name="q" class="form-control" placeholder="Yazı Başlığı Giriniz..."/>
              </form>
              </br>
          </div>


        <div class="clearfix"></div>
        <div class="col-md-12">
          <div class="tile">
              <?php
              $s    =   @intval(get('s'));
              if(!$s){ $s = 1; }
              $q=get('q');
              if(!$q){header('location'.$yonetim.'/konular.php');}

              $sorgu=$db->prepare("SELECT * FROM yazilar INNER JOIN kategoriler ON kategoriler.id = yazilar.yaz_kat_id 
 WHERE yazi_baslik LIKE :baslik   ORDER BY yazi_id DESC");
              $sorgu->execute([':baslik'=>"%".$q."%"]);

              $toplam   =$sorgu->rowCount();
              $lim      = 10 ;
              $goster   = $s * $lim - $lim;


              $sorgu=$db->prepare("SELECT * FROM yazilar INNER JOIN kategoriler ON kategoriler.id = yazilar.yaz_kat_id 
 WHERE yazi_baslik LIKE :baslik  ORDER BY yazi_id DESC LIMIT :goster,:lim");

              $sorgu->bindValue(':baslik',"%".$q."%",PDO::PARAM_STR);
              $sorgu->bindValue(':goster',( int ) $goster,PDO::PARAM_INT);
              $sorgu->bindValue(':lim',( int ) $lim,PDO::PARAM_INT);
              $sorgu->execute();
              if($s > ceil($toplam/$lim)){
                  $s=1;
              }
              if($sorgu->rowCount()){

              ?>
            <h3 class="tile-title">Yazı Listesi (<?php echo $toplam; ?>)</h3>
            <div class="table-responsive table-hover">
              <table class="table">
                <thead>
                  <tr>
                    <th>#</th>
                      <th>RESİM</th>
                    <th>BAŞLIK</th>
                      <th>KATEGORİ</th>
                      <th>TARİH</th>
                      <th>DURUM</th>
                    <th>İŞLEMLER</th>
                  </tr>
                </thead>
                <tbody>
             <?php foreach ($sorgu as $row){?>
                      <tr>
                          <td><?php echo $row['id'] ;?></td>
                          <td><img src="../images/ <?php echo $row['yazi_resim'] ;?>" width="100" height="100" class="img-responsive"/></td>
                          <td><?php echo $row['yazi_baslik'] ;?></td>
                          <td><?php echo $row['kat_adi'] ;?></td>
                          <td><?php echo date('d.m.y' ,strtotime( $row['yazi_tarih'])) ;?></td>
                          <td><?php echo $row['yazi_durum'] == 1 ? '<div style="color:green;font-weight:bold">Aktif</div>' : '<div style="color:darkred;font-weight:bold">Pasif</div>';?></td>
                          <td><a href="<?php echo $yonetim."/islemler.php?islem=yaziduzenle&id=".$row['yazi_id']; ?>"><i class="fa fa-edit"></i></a> | <a onclick="return confirm('silme işlemini onaylıyor musunuz ?');" href="<?php echo $yonetim."/islemler.php?islem=yazisil&id=".$row['yazi_id']; ?>"><i class="fa fa-eraser"></i></a></td>
                      </tr>


             <?php }?>

                </tbody>
              </table>
            </div>

                      <ul class="pagination">
                          <?php
                          if($toplam>$lim){
                              pagination($s,ceil($toplam/$lim), 'yaziara.php?q='.$q.'s=');
                          }
                          ?>
                      </ul>
              <?php }else{
                  echo "<div class='alert alert-danger'>Aranan Yazı Bulunmuyor</div>";
              }
              ?>


          </div>
        </div>
      </div>
    </main>
<?php require_once 'inc/alt.php'; ?>