<?php
require_once 'inc/ust.php'; ?>
    <!-- Sidebar menu-->
<?php require_once 'inc/sol.php'; ?>

    <main class="app-content">
      <div class="app-title">
        <div>
          <h1><i class="app-menu__icon fa fa-pencil"></i> Editör</h1>
          <p>Editör Listesi</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item">Editör</li>
          <li class="breadcrumb-item active"><a href="#">Editör Listesi</a></li>
        </ul>
      </div>
      <div class="row">

        <div class="clearfix"></div>
        <div class="col-md-12">
          <div class="tile">
              <?php
              $s    =   @intval(get('s'));
              if(!$s){ $s = 1; }

              $toplam   =say('editor');
              $lim      =10;
              $goster   = $s * $lim - $lim;


              $sorgu=$db->prepare("SELECT * FROM editor ORDER BY id DESC LIMIT :goster,:lim");

              $sorgu->bindValue(':goster',( int ) $goster,PDO::PARAM_INT);
              $sorgu->bindValue(':lim',( int ) $lim,PDO::PARAM_INT);
              $sorgu->execute();
              if($s > ceil($toplam/$lim)){
                  $s=1;
              }
              if($sorgu->rowCount()){

              ?>
            <h3 class="tile-title">Editör Listesi (<?php echo $toplam; ?>)</h3>
            <div class="table-responsive table-hover">
              <table class="table">
                <thead>
                  <tr>
                    <th>#</th>
                      <th>KULLANICI ADI</th>
                    <th>E-POSTA</th>
                      <th>TARİH</th>
                      <th>DURUM</th>

                    <th>İŞLEMLER</th>
                  </tr>
                </thead>
                <tbody>
             <?php foreach ($sorgu as $row){?>
                      <tr>
                          <td><?php echo $row['id'] ?></td>
                          <td><?php echo $row['kadi'] ?></td>
                          <td><?php echo $row['mail'] ?></td>
                          <td><?php echo date('d.m.y' ,strtotime( $row['sontarih'])) ;?></td>
                          <td><?php echo $row['durum'] == 1 ? '<div style="color:green;font-weight:bold">Aktif</div>' : '<div style="color:darkred;font-weight:bold">Pasif</div>';?></td>

                          <td><a href="<?php echo $yonetim."/islemler.php?islem=edit&id=".$row['id']; ?>"><i class="fa fa-eye"></i></a>|<a onclick="return confirm('Editor silme işlemini onaylıyor musunuz ?');" href="<?php echo $yonetim."/islemler.php?islem=editsil&id=".$row['id']; ?>"><i class="fa fa-eraser"></i></a></td>
                      </tr>


             <?php }?>

                </tbody>
              </table>
            </div>

                      <ul class="pagination">
                          <?php
                          if($toplam>$lim){
                              pagination($s,ceil($toplam/$lim), 'aboneler.php?s=');
                          }
                          ?>
                      </ul>
              <?php }else{
                  echo "<div class='alert alert-danger'>Editor Bulunmuyor</div>";
              }
              ?>


          </div>
        </div>
      </div>
    </main>
<?php require_once 'inc/alt.php'; ?>