<?php
require_once 'inc/ust.php'; ?>
    <!-- Sidebar menu-->
<?php require_once 'inc/sol.php'; ?>

    <main class="app-content">
      <div class="app-title">
        <div>
          <h1><i class="app-menu__icon fa fa-comment"></i> Onaylı Sorular</h1>
          <p>Onaylı Sorular</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item">Sorular</li>
          <li class="breadcrumb-item active"><a href="#">Onaylı Sorular</a></li>
        </ul>
      </div>
      <div class="row">

        <div class="clearfix"></div>
        <div class="col-md-12">
          <div class="tile">
              <?php
              $s    =   @intval(get('s'));
              if(!$s){ $s = 1; }

              $toplam   =say('forum','durum',1);
              $lim      =10;
              $goster   = $s * $lim - $lim;


              $sorgu=$db->prepare("SELECT * FROM forum
 WHERE durum=:d  ORDER BY id DESC LIMIT :goster,:lim");

              $sorgu->bindValue(':goster',( int ) $goster,PDO::PARAM_INT);
              $sorgu->bindValue(':lim',( int ) $lim,PDO::PARAM_INT);
              $sorgu->bindValue(':d',( int ) 1,PDO::PARAM_INT);


              $sorgu->execute();
              if($s > ceil($toplam/$lim)){
                  $s=1;
              }
              if($sorgu->rowCount()){

              ?>
            <h3 class="tile-title">ONAYLI SORULAR (<?php echo $toplam; ?>)</h3>
            <div class="table-responsive table-hover">
              <table class="table">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>İSİM</th>
                      <th>SORU</th>
                      <th>E-POSTA</th>
                      <th>TARİH</th>
                      <th>CEVAP</th>
                    <th>İŞLEMLER</th>
                  </tr>
                </thead>
                <tbody>
             <?php foreach ($sorgu as $row){?>
                      <tr>
                          <td><?php echo $row['id']; ?></td>
                          <td><?php echo $row['isim']; ?></td>
                          <td><?php echo $row['icerik']; ?></td>
                          <td><?php echo $row['eposta']; ?></td>
                          <td><?php echo date('d.m.y', strtotime( $row['tarih'])) ;?></td>
                          <td><?php echo $row['cevap']; ?></td>
                          <td><a href="<?php echo $yonetim."/islemler.php?islem=soru&id=".$row['id']; ?>"><i class="fa fa-eye"></i></a> | <a onclick="return confirm('Onaylıyor musunuz?');" href="<?php echo $yonetim."/islemler.php?islem=sorusil&id=".$row['id']; ?>"><i class="fa fa-eraser"></i></a></td>
                      </tr>


             <?php }?>

                </tbody>
              </table>
            </div>

                      <ul class="pagination">
                          <?php
                          if($toplam>$lim){
                              pagination($s,ceil($toplam/$lim), 'onaylıyorumlar.php?s=');
                          }
                          ?>
                      </ul>
              <?php }else{
                  echo "<div class='alert alert-danger'>Onaylı Yorum Bulunmuyor</div>";
              }
              ?>


          </div>
        </div>
      </div>
    </main>
<?php require_once 'inc/alt.php'; ?>