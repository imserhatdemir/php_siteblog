<?php

require_once 'inc/ust.php'; ?>
    <!-- Sidebar menu-->
<?php require_once 'inc/sol.php'; ?>

    <main class="app-content">
      <div class="app-title">
        <div>
          <h1><i class="fa fa-th-list"></i> İşlem</h1>
          <p>İşlem Listesi</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item">İşlemler</li>
          <li class="breadcrumb-item active"><a href="#">İşlem Listesi</a></li>
        </ul>
      </div>
      <div class="row">

        <div class="clearfix"></div>
        <div class="col-md-12">
          <div class="tile">
              <h3 class="tile-title"><?php echo get('islem'); ?></h3>

              <?php
              if(@$_SESSION['oturum'] == sha1(md5(@$yid.IP())) ) {

                  $islem = @get('islem');
                  if (!$islem) {
                      header('Location:' . $yonetim);
                  }
              }

              switch ($islem){

                  case 'yaziduzenle';
                  $id=get('id');
                  if(!$id){
                      header('location:'.$yonetim.'/konular.php');
                  }
                  $yazibul=$db->prepare("SELECT * FROM yazilar WHERE yazi_id=:id ");
                  $yazibul->execute([':id'=>$id]);
                  if($yazibul->rowCount()){
                      $yazirow=$yazibul->fetch(PDO::FETCH_OBJ);
                      if(isset($_POST['yaziguncel'])){
                          require_once 'inc/class.upload.php';

                          $baslik=post('baslik');
                          $sefbaslik=sef_link($baslik);
                          $kategori=post('kategori');
                          $icerik = $_POST['icerik'];
                          $etiketler=post('etiketler');
                          $durum=post('durum');
                          if(!$baslik || !$kategori || !$icerik || !$etiketler || !$durum){
                              echo '<div class="alert alert-danger" >Boş Alan Bırakmayınız</div>';
                          }else{
                              ##etiketleri seflink yapmaya çalışıyo
                              $sefyap = explode(',',$etiketler);
                              $dizi =array();
                              foreach ($sefyap as $par){
                                  $dizi[] = sef_link($par);
                              }
                              $deger =implode(',',$dizi);

                              $image= new Upload($_FILES['resim']);
                              if($image->uploaded){
                                  $foto = md5(uniqid());
                                  $image->allowed=array("image/*");
                                  $image->image_convert="webp";
                                  $image->image_text="Elçi Hukuk";
                                  $image->image_text_position="BR";
                                  $image->process("../images");
                                  if($image->processed){
                                    $foto = $image->file_dst_name;
                                      $konuguncelle=$db->prepare("UPDATE yazilar SET 
                                            yazi_baslik=:b,
                                            yazi_sef=:s,
                                            yaz_kat_id=:k,
                                            yazi_resim=:r,
                                            yazi_icerik=:i,
                                            yazi_etiketler=:e,
                                            yazi_sef_etiketler=:se,
                                            yazi_durum=:du WHERE yazi_id=:id");

                                      $konuguncelle->execute([
                                          ':b'=>$baslik,
                                          ':s'=>$sefbaslik,
                                          ':k'=>$kategori,
                                          ':r'=>$foto,
                                          ':i'=>$icerik,
                                          ':e'=>$etiketler,
                                          ':se'=>$deger,
                                          ':du'=>$durum,
                                          ':id'=>$id
                                      ]);
                                      if ($konuguncelle->rowCount()){

                                          echo '<div class="alert alert-success" >Yazı Başarılı Bir Biçimde Güncellendi</div>';
                                          header('refresh:2;url='.$_SERVER['HTTP_REFERER']);

                                      }else{
                                          echo '<div class="alert alert-danger" >Yazi Güncellenirken Bir Hata Oluştu</div>';
                                      }

                                  }else{
                                      echo '<div class="alert alert-danger" >Resim Yüklenemedi</div>';
                                  }

                              }else {
                                  $konuguncelle2=$db->prepare("UPDATE yazilar SET 
                                            yazi_baslik=:b,
                                            yazi_sef=:s,
                                            yaz_kat_id=:k,
                                            yazi_icerik=:i,
                                            yazi_etiketler=:e,
                                            yazi_sef_etiketler=:se,
                                            yazi_durum=:du WHERE yazi_id=:id");

                                  $konuguncelle2->execute([
                                      ':b'=>$baslik,
                                      ':s'=>$sefbaslik,
                                      ':k'=>$kategori,
                                      ':i'=>$icerik,
                                      ':e'=>$etiketler,
                                      ':se'=>$deger,
                                      ':du'=>$durum,
                                      ':id'=>$id
                                  ]);
                                  if ($konuguncelle2->rowCount()){

                                      echo '<div class="alert alert-success" >Yazı Başarılı Bir Biçimde Güncellendi Resim Değiştirilmedi</div>';
                                      header('refresh:2;url='.$_SERVER['HTTP_REFERER']);

                                  }else{
                                      echo '<div class="alert alert-danger" >Yazi Güncellenirken Bir Hata Oluştu</div>';
                                  }
                              }

                          }
                      }


                      ?>
                      <form class="form-horizontal" action="" method="POST" enctype="multipart/form-data">
                          <div class="tile-body">

                              <div class="form-group row">
                                  <label class="control-label col-md-3">Yazı Başlık</label>
                                  <div class="col-md-8">
                                      <input value="<?php echo $yazirow->yazi_baslik ;?>" class="form-control" type="text" name="baslik" placeholder="Yazı Başlık">
                                  </div>
                              </div>
                              <div class="form-group row">
                                  <label class="control-label col-md-3">Yazı Kategori</label>
                                  <div class="col-md-8">
                                      <select name="kategori" class="form-control">
                                          <?php
                                          $kategoriler = $db->prepare("SELECT * FROM kategoriler ");
                                          $kategoriler->execute();
                                          if($kategoriler->rowCount()){
                                              foreach ($kategoriler as $row){
                                                  echo '<option value="'.$row['id'].'"';
                                                      echo $yazirow->yaz_kat_id == $row['id'] ? 'selected' : null;
                                                     echo' >'.$row['kat_adi'].'</option>';
                                              }
                                          }

                                          ?>
                                      </select>
                                  </div>
                              </div>
                              <div class="form-group row">
                                  <label class="control-label col-md-3">Yazı Resim</label>
                                  <div class="col-md-8">
                                      <img src="<?php echo $arow->site_url ;?>/images/<?php echo $yazirow->yazi_resim ;?>" width="100" height="100"/>
                                      <input class="form-control" type="file" name="resim" >
                                  </div>
                              </div>
                              <div class="form-group row">
                                  <label class="control-label col-md-3">Yazı İçerik</label>
                                  <div class="col-md-8">
                                      <textarea name="icerik" class="ckeditor"><?php echo $yazirow->yazi_icerik ;?></textarea>
                                  </div>
                              </div>
                              <div class="form-group row">
                                  <label class="control-label col-md-3">Yazı Etiketler</label>
                                  <div class="col-md-8">
                                      <input value="<?php echo $yazirow->yazi_etiketler ;?>" class="form-control" type="text" name="etiketler" placeholder="virgüllü şekilde giriniz" >
                                  </div>
                              </div>
                              <div class="form-group row">
                                  <label class="control-label col-md-3">Yazı Durumu</label>
                                  <div class="col-md-8">
                                      <select name="durum" class="form-control">
                                          <option value="1" <?php echo $yazirow->yazi_durum ==1 ? 'selected':null ; ?>>Aktif</option>
                                          <option value="2"<?php echo $yazirow->yazi_durum ==2 ? 'selected':null ; ?>>Pasif</option>

                                      </select>
                                  </div>
                              </div>

                          </div>
                          <div class="tile-footer">
                              <div class="row">
                                  <div class="col-md-8 col-md-offset-3">
                                      <button class="btn btn-primary" type="submit" name="yaziguncel"><i class="fa fa-fw fa-lg fa-check-circle"></i>Güncelle</button>&nbsp;&nbsp;&nbsp;<a class="btn btn-secondary" href="<?php echo $yonetim; ?>/konular.php"><i class="fa fa-fw fa-lg fa-arrow-left"></i>Listeye Dön</a>
                                  </div>
                              </div>
                          </div>
                      </form>


                          <?php
                  }else{
                      header('location:'.$yonetim.'/konular.php');
                  }
                  break;

                  ## profil işlemleri

                  case'cikis':
                      session_destroy();
                      header('location:giris.php');
                  break;

                  case 'profil':
                      if(isset($_POST['profilguncelle'])){
                          $kadi=post('kadi');
                          $posta=post('eposta');

                          if(!$kadi || !$posta){
                              echo '<div class="alert alert-danger">Boş Alan Bırakmayınız...</div>';
                          }else{
                              if(!filter_var($posta,FILTER_VALIDATE_EMAIL)){
                                  echo '<div class="alert alert-danger">E-posta formatı yanlış...</div>';
                              }else{
                                  $guncelle=$db->prepare("UPDATE yoneticiler SET kadi=:k, eposta=:e WHERE id=:id");
                                  $guncelle->execute([':k'=>$kadi,':e'=>$posta,':id'=>$yid]);
                                  if($guncelle){
                                      echo '<div class="alert alert-success">Profiliniz Güncellendi</div>';
                                      header('refresh:2;url='.$_SERVER['HTTP_REFERER']);
                                  }else{
                                      echo '<div class="alert alert-danger">HATA OLUŞTU</div>';
                                  }
                              }
                          }
                      }
                      ?>
                      <form class="form-horizontal" action="" method="POST">
                          <div class="tile-body">

                              <div class="form-group row">
                                  <label class="control-label col-md-3">Kullanıcı Adı</label>
                                  <div class="col-md-8">
                                      <input value="<?php echo $ykadi ; ?>" class="form-control" type="text" name="kadi" placeholder="Kullanıcı Adı">
                                  </div>
                              </div>
                              <div class="form-group row">
                                  <label class="control-label col-md-3">E-posta</label>
                                  <div class="col-md-8">
                                      <input  value="<?php echo $yposta ; ?>" class="form-control" type="text" name="eposta" placeholder="E-posta">
                                  </div>
                              </div>



                          </div>
                          <div class="tile-footer">
                              <div class="row">
                                  <div class="col-md-8 col-md-offset-3">
                                      <button class="btn btn-primary" type="submit" name="profilguncelle"><i class="fa fa-fw fa-lg fa-check-circle"></i>Güncelle</button>&nbsp;&nbsp;&nbsp;<a class="btn btn-secondary" href="<?php echo $yonetim; ?>"><i class="fa fa-fw fa-lg fa-arrow-left"></i>Listeye Dön</a>
                                  </div>
                              </div>
                          </div>
                      </form>
                      <?php
                      break;

                  case 'sifredegistir':
                      if(isset($_POST['sifreguncelle'])){
                          $sifre1=post('sifre1');
                          $sifre2=post('sifre2');
                          $sifrele = sha1(md5($sifre1));

                          if(!$sifre1 || !$sifre2){
                              echo '<div class="alert alert-danger">Boş Alan Bırakmayınız...</div>';
                          }else{
                              if($sifre1 != $sifre2){
                                  echo '<div class="alert alert-danger">Şifreler Uyuşmadı</div>';
                              }else{
                                  $guncelle=$db->prepare("UPDATE yoneticiler SET sifre=:s WHERE id=:id");
                                  $guncelle->execute([':s'=>$sifrele,':id'=>$yid]);
                                  if($guncelle){
                                      echo '<div class="alert alert-success">Şifreniz Güncellendi</div>';
                                      header('refresh:2;url='.$_SERVER['HTTP_REFERER']);
                                  }else{
                                      echo '<div class="alert alert-danger">HATA OLUŞTU</div>';
                                  }
                              }
                          }
                      }
                      ?>
                      <form class="form-horizontal" action="" method="POST">
                          <div class="tile-body">

                              <div class="form-group row">
                                  <label class="control-label col-md-3">Yeni Şifre</label>
                                  <div class="col-md-8">
                                      <input class="form-control" type="password" name="sifre1" placeholder="Şifre">
                                  </div>
                              </div>
                              <div class="form-group row">
                                  <label class="control-label col-md-3">Yeni şifre tekrar</label>
                                  <div class="col-md-8">
                                      <input   class="form-control" type="password" name="sifre2" placeholder="Şifre Tekrar">
                                  </div>
                              </div>



                          </div>
                          <div class="tile-footer">
                              <div class="row">
                                  <div class="col-md-8 col-md-offset-3">
                                      <button class="btn btn-primary" type="submit" name="sifreguncelle"><i class="fa fa-fw fa-lg fa-check-circle"></i>Güncelle</button>&nbsp;&nbsp;&nbsp;<a class="btn btn-secondary" href="<?php echo $yonetim; ?>"><i class="fa fa-fw fa-lg fa-arrow-left"></i>Listeye Dön</a>
                                  </div>
                              </div>
                          </div>
                      </form>
                      <?php
                      break;

                      ##profil son
                  ### silme işlemleri başla
                  case 'kategorisil':
                       $id = get('id');
                       if(!$id){    header('Location:' . $yonetim."/kategoriler.php");}
                       $kategorisil =$db->prepare("DELETE FROM kategoriler WHERE id=:id");
                       $kategorisil->execute([':id'=>$id]);
                       if ($kategorisil){

                           $yazipasif=$db->prepare("UPDATE yazilar SET yazi_durum=:d WHERE yaz_kat_id=:id");
                           $yazipasif->execute([':d'=>2,':id'=>$id]);
                           echo '<div class="alert alert-success">KATEGORİ BAŞARIYLA SİLİNDİ VE BU KATEGORİYE AİT YAZILAR PASİF HALE GETİRİLDİ</div>';
                           header('Refresh:2;url='.$yonetim."/kategoriler.php");
                       }else{
                           echo '<div class="alert alert-danger">HATA OLUŞTU</div>';
                       }
                       break;
                  case 'mesajsil':
                      $id = get('id');
                      if(!$id){    header('Location:' . $yonetim."/okunmusmesajlar.php");}
                      $mesajsil =$db->prepare("DELETE FROM mesajlar WHERE id=:id");
                      $mesajsil->execute([':id'=>$id]);
                      if ($mesajsil){

                          echo '<div class="alert alert-success">MESAJ BAŞARIYLA SİLİNDİ </div>';
                          header('Refresh:2;url='.$_SERVER['HTTP_REFERER']);
                      }else{
                          echo '<div class="alert alert-danger">HATA OLUŞTU</div>';
                      }
                      break;
                  case 'yorumsil':
                      $id = get('id');
                      if(!$id){    header('Location:' . $yonetim."/onaylıyorumlar.php");}
                      $yorumsil =$db->prepare("DELETE FROM yorumlar WHERE id=:id");
                      $yorumsil->execute([':id'=>$id]);
                      if ($yorumsil){

                          echo '<div class="alert alert-success">YORUM BAŞARIYLA SİLİNDİ </div>';
                          header('Refresh:2;url='.$_SERVER['HTTP_REFERER']);
                      }else{
                          echo '<div class="alert alert-danger">HATA OLUŞTU</div>';
                      }
                      break;
                  case 'sosyalmedyasil':
                      $id = get('id');
                      if(!$id){    header('Location:' . $yonetim."/sosyalmedya.php");}
                      $sosyalmedyasil =$db->prepare("DELETE FROM sosyalmedya WHERE id=:id");
                      $sosyalmedyasil->execute([':id'=>$id]);
                      if ($sosyalmedyasil){

                          echo '<div class="alert alert-success">SOSYAL MEDYA ADRESİ BAŞARIYLA SİLİNDİ </div>';
                          header('Refresh:2;url='.$_SERVER['HTTP_REFERER']);
                      }else{
                          echo '<div class="alert alert-danger">HATA OLUŞTU</div>';
                      }
                      break;
                  case 'yazisil':
                      $id = @get('id');
                      if(!$id){    header('Location:' . $yonetim."/konular.php");}

                      $yazibul=$db->prepare("SELECT * FROM yazilar WHERE yazi_id=:id");
                      $yazibul->execute([':id'=>$id]);
                    if($yazibul->rowCount()){
                        $yazirow=$yazibul->fetch(PDO::FETCH_OBJ);
                        $yazisil =$db->prepare("DELETE FROM yazilar WHERE yazi_id=:id");
                        $yazisil->execute([':id'=>$id]);
                        if ($yazisil){
                            $yorumlarısil= $db->prepare("DELETE * FROM yorumlar WHERE yorum_yazi_id:id");
                            $yorumlarısil->execute([':id'=>$id]);
                            unlink("../images/".$yazirow->yazi_resim);
                            echo '<div class="alert alert-success">YAZI BAŞARIYLA SİLİNDİ </div>';
                            header('Refresh:2;url='.$_SERVER['HTTP_REFERER']);
                        }else{
                            echo '<div class="alert alert-danger">HATA OLUŞTU</div>';
                        }
                    }
                      break;
                  case 'editsil':
                      $id = get('id');
                      if(!$id){    header('Location:' . $yonetim."/aboneler.php");}
                      $abonesil =$db->prepare("DELETE FROM editor WHERE id=:id");
                      $abonesil->execute([':id'=>$id]);
                      if ($abonesil){

                          echo '<div class="alert alert-success">EDİTOR BAŞARIYLA SİLİNDİ </div>';
                          header('Refresh:2;url='.$_SERVER['HTTP_REFERER']);
                      }else{
                          echo '<div class="alert alert-danger">HATA OLUŞTU</div>';
                      }
                      break;
                      ##silme işlemleri son
                        case 'abonesil':
                      $id = get('id');
                      if(!$id){    header('Location:' . $yonetim."/abone.php");}
                      $abonesil =$db->prepare("DELETE FROM aboneler WHERE id=:id");
                      $abonesil->execute([':id'=>$id]);
                      if ($abonesil){

                          echo '<div class="alert alert-success">ÜYE BAŞARIYLA SİLİNDİ </div>';
                          header('Refresh:2;url='.$_SERVER['HTTP_REFERER']);
                      }else{
                          echo '<div class="alert alert-danger">HATA OLUŞTU</div>';
                      }
                      break;
                      ##silme işlemleri son



                  ##ekleme işlemleri
                  case 'yenikategori':
                      if(isset($_POST['kategoriekle'])){

                          $katadi   =   post('katadi');
                          $katsef   =   sef_link($katadi);
                          $katkeyw  =   post('katkeyw');
                          $katdesc  =   post('katdesc');


                          if (!$katadi || !$katkeyw || !$katdesc ){
                              echo '<div class="alert alert-danger"> Boş Alan Bırakmayınız</div>';
                          }else{


                              $varmi = $db->prepare("SELECT * FROM kategoriler WHERE kat_sef=:s");
                              $varmi->execute([':s' => $katsef]);
                              if($varmi->rowCount()){
                                  echo '<div class="alert alert-danger">Bu Kategori Zaten Kayıtlı</div>';
                              }else{

                                  $katguncel = $db->prepare("INSERT INTO kategoriler SET 
                            kat_adi=:adi,
                            kat_sef=:sef,
                            kat_keyw=:keyw,
                            kat_desc=:a");
                                  $katguncel->execute([':adi'=>$katadi,':sef'=>$katsef,':keyw'=>$katkeyw,':a'=>$katdesc]);
                                  if($katguncel->rowCount()){
                                      echo '<div class="alert alert-success">Kategori Eklendi</div>';
                                      header('refresh:2;url='.$yonetim."/kategoriler.php");
                                  }else{
                                      echo '<div class="alert alert-danger">HATA OLUŞTU...</div>';
                                  }
                              }
                          }

                      }
                        ?>
                      <form class="form-horizontal" action="" method="POST">
            <div class="tile-body">

                <div class="form-group row">
                  <label class="control-label col-md-3">Kategori Adı</label>
                  <div class="col-md-8">
                    <input class="form-control" type="text" name="katadi" placeholder="Kategori Adı">
                  </div>
                </div>
                  <div class="form-group row">
                      <label class="control-label col-md-3">Kategori Anahtar Kelimeler</label>
                      <div class="col-md-8">
                          <input class="form-control" type="text" name="katkeyw" placeholder="Kategori Anahtar Kelimeler">
                      </div>
                  </div>
                  <div class="form-group row">
                      <label class="control-label col-md-3">Kategori Açıklama</label>
                      <div class="col-md-8">
                          <input class="form-control" type="text" name="katdesc" placeholder="Kategori Açıklama">
                      </div>
                  </div>


            </div>
            <div class="tile-footer">
              <div class="row">
                <div class="col-md-8 col-md-offset-3">
                  <button class="btn btn-primary" type="submit" name="kategoriekle"><i class="fa fa-fw fa-lg fa-check-circle"></i>Kategori Ekle</button>&nbsp;&nbsp;&nbsp;<a class="btn btn-secondary" href="<?php echo $yonetim; ?>/kategoriler.php"><i class="fa fa-fw fa-lg fa-arrow-left"></i>Listeye Dön</a>
                </div>
              </div>
            </div>
                      </form>
                          <?php
                      break;


                  case 'yenisosyalmedya':
                      if(isset($_POST['sosyalmedyaekle'])){

                          $ikon   =   post('ikon');
                          $link  =   post('link');



                          if (!$ikon || !$link){
                              echo '<div class="alert alert-danger"> Boş Alan Bırakmayınız</div>';
                          }else{


                                  $sosyalguncelle = $db->prepare("INSERT INTO sosyalmedya SET 
                            ikon=:ik, link=:lk
                            ");
                                  $sosyalguncelle->execute([':ik'=>$ikon,':lk'=>$link]);
                                  if($sosyalguncelle->rowCount()){
                                      echo '<div class="alert alert-success">Sosyal Medya Hesabı Eklendi</div>';
                                      header('refresh:2;url='.$yonetim."/sosyalmedya.php");
                                  }else{
                                      echo '<div class="alert alert-danger">HATA OLUŞTU...</div>';
                                  }
                              }
                          }


                      ?>
                      <form class="form-horizontal" action="" method="POST">
                          <div class="tile-body">

                              <div class="form-group row">
                                  <label class="control-label col-md-3">Sosyal Medya İkon</label>
                                  <div class="col-md-8">
                                      <input class="form-control" type="text" name="ikon" placeholder="ikon">
                                  </div>
                              </div>
                              <div class="form-group row">
                                  <label class="control-label col-md-3">Sosyal Medya Link</label>
                                  <div class="col-md-8">
                                      <input class="form-control" type="text" name="link" placeholder="Sosyal medya link">
                                  </div>
                              </div>



                          </div>
                          <div class="tile-footer">
                              <div class="row">
                                  <div class="col-md-8 col-md-offset-3">
                                      <button class="btn btn-primary" type="submit" name="sosyalmedyaekle"><i class="fa fa-fw fa-lg fa-check-circle"></i>Sosyal Medya Ekle</button>&nbsp;&nbsp;&nbsp;<a class="btn btn-secondary" href="<?php echo $yonetim; ?>/sosyalmedya.php"><i class="fa fa-fw fa-lg fa-arrow-left"></i>Listeye Dön</a>
                                  </div>
                              </div>
                          </div>
                      </form>
                      <?php
                      break;


                  case 'yenikonu':

                      if(isset($_POST['yeniyaziekle'])){
                          require_once 'inc/class.upload.php';
                          require_once 'inc/mail/class.phpmailer.php';

                              $baslik=post('baslik');
                              $sefbaslik=sef_link($baslik);
                              $kategori=post('kategori');
                              $icerik = $_POST['icerik'];
                              $etiketler=post('etiketler');
                          if(!$baslik||!$kategori||!$icerik||!$etiketler){
                              echo '<div class="alert alert-danger" >Boş Alan Bırakmayınız</div>';
                          }else{
                              ##etiketleri seflink yapmaya çalışıyo
                              $sefyap = explode(',',$etiketler);
                              $dizi =array();
                              foreach ($sefyap as $par){
                                  $dizi[] = sef_link($par);
                              }
                              $deger =implode(',',$dizi);

                                $image= new Upload($_FILES['resim']);
                                if($image->uploaded){
                                    $foto = md5(uniqid());
                                    $image->allowed=array("image/*");
                                    $image->image_convert="webp";
                                    
                                    $image->image_text="Elçi Hukuk";
                                    $image->image_text_position="BR";
                                    $image->process("../images");
                                    if($image->processed){
                                        $foto = $image->file_dst_name;
                                        $konuekle=$db->prepare("INSERT INTO yazilar SET 
                                            yazi_baslik=:b,
                                            yazi_sef=:s,
                                            yaz_kat_id=:k,
                                            yazi_resim=:r,
                                            yazi_icerik=:i,
                                            yazi_etiketler=:e,
                                            yazi_sef_etiketler=:se");

                                            $konuekle->execute([
                                                    ':b'=>$baslik,
                                                    ':s'=>$sefbaslik,
                                                    ':k'=>$kategori,
                                                    ':r'=>$foto,
                                                    ':i'=>$icerik,
                                                    ':e'=>$etiketler,
                                                    ':se'=>$deger
                                            ]);
                                            if ($konuekle->rowCount()){
                                                $sonid=$db->lastInsertId();

                                                $gonderen="";
                                                $parola="";
                                                $mail = new PHPMailer();
                                                $mail->Host="smtp.gmail.com.";
                                                $mail->Port=587;//SSL için 465
                                                $mail->SMTPSecure="tls";//SSL için ssl
                                                $mail->SMTPAuth=true;
                                                $mail->Username=$gonderen;
                                                $mail->Password=$parola;
                                                $mail->IsSMTP();

                                                $mail->From =$gonderen;
                                                $mail->FromName="Elçi Hukuk | Yeni Konu Eklendi";
                                                $mail->CharSet="UTF-8";
                                                $mail->Subject="Yeni Konu Eklendi";

                                                $aboneler =$db ->prepare("SELECT * FROM aboneler");
                                                $aboneler->execute();
                                                if($aboneler ->rowCount()){
                                                    foreach ($aboneler as $abone){
                                                        $mail->AddBCC($abone['abone_mail']);
                                                    }
                                                }


                                                $konubul=$db->prepare("SELECT * FROM yazilar WHERE yazi_id=:id");
                                                $konubul->execute([':id'=>$sonid]);
                                                $konurow = $konubul->fetchAll(PDO::FETCH_ASSOC);
                                                ##$yazilink=$arow->site_url."/yazidetay.php?yazi_sef=".$konurow->yazi_sef."&id=".$konurow->yazi_id;
                                                ##$mailicerik = "Konu Başlığı: ".$konurow->yazi_baslik."<br>".$yazilink;
                                                ##$mail->MsgHTML($mailicerik);
                                                if($konubul->rowCount()){
                                                    echo '<div class="alert alert-success" >Yazı Başarılı Bir Biçimde Eklendi</div>';
                                                    header('refresh:2;url='.$yonetim."/konular.php");

                                                }else{
                                                    echo '<div class="alert alert-danger" >Yazı Eklenmedi</div>';
                                                }


                                            }else{
                                                echo '<div class="alert alert-danger" >Yazi Eklenirken Bir Hata Oluştu</div>';
                                            }

                                    }else{
                                        echo '<div class="alert alert-danger" >Resim Yüklenemedi</div>';
                                    }

                                }else {
                                    echo '<div class="alert alert-danger" >Resim Seçmediniz</div>';
                                }

                          }
                      }



                      ?>
                      <form class="form-horizontal" action="" method="POST" enctype="multipart/form-data">
                          <div class="tile-body">

                              <div class="form-group row">
                                  <label class="control-label col-md-3">Yazı Başlık</label>
                                  <div class="col-md-8">
                                      <input class="form-control" type="text" name="baslik" placeholder="Yazı Başlık">
                                  </div>
                              </div>
                              <div class="form-group row">
                                  <label class="control-label col-md-3">Yazı Kategori</label>
                                  <div class="col-md-8">
                                   <select name="kategori" class="form-control">
                                       <?php
                                       $kategoriler = $db->prepare("SELECT * FROM kategoriler ");
                                       $kategoriler->execute();
                                       if($kategoriler->rowCount()){
                                           foreach ($kategoriler as $row){
                                               echo '<option value="'.$row['id'].'">'.$row['kat_adi'].'</option>';
                                           }
                                       }

                                       ?>
                                   </select>
                                  </div>
                              </div>
                              <div class="form-group row">
                                  <label class="control-label col-md-3">Yazı Resim</label>
                                  <div class="col-md-8">
                                      <input class="form-control" type="file" name="resim" >
                                  </div>
                              </div>
                              <div class="form-group row">
                                  <label class="control-label col-md-3">Yazı İçerik</label>
                                  <div class="col-md-8">
                                      <textarea name="icerik" class="ckeditor">YAZAR ADI= <?php echo $ykadi; ?></textarea>
                                  </div>
                              </div>
                              <div class="form-group row">
                                  <label class="control-label col-md-3">Yazı Etiketler</label>
                                  <div class="col-md-8">
                                      <input class="form-control" type="text" name="etiketler" placeholder="virgüllü şekilde giriniz" >
                                  </div>
                              </div>
                          </div>
                          <div class="tile-footer">
                              <div class="row">
                                  <div class="col-md-8 col-md-offset-3">
                                      <button class="btn btn-primary" type="submit" name="yeniyaziekle"><i class="fa fa-fw fa-lg fa-check-circle"></i>Ekle</button>&nbsp;&nbsp;&nbsp;<a class="btn btn-secondary" href="<?php echo $yonetim; ?>/konular.php"><i class="fa fa-fw fa-lg fa-arrow-left"></i>Listeye Dön</a>
                                  </div>
                              </div>
                          </div>
                      </form>
                      <?php
                      break;

                  ##ekleme işlemleri son

                  ##düzenleme ve okuma işlemleri

                  case 'kategoriduzenle':
                      $id = get('id');
                      if(!$id){
                          header('Location:'.$yonetim."/kategoriler.php");
                      }
                      $kategoribul =$db->prepare("SELECT * FROM kategoriler WHERE id=:id");
                      $kategoribul->execute([':id'=>$id]);
                      if ($kategoribul->rowCount()){
                          $row = $kategoribul->fetch(PDO::FETCH_OBJ);
                          if(isset($_POST['kategoriduzenle'])){

                              $katadi   =   post('katadi');
                              $katsef   =   sef_link($katadi);
                              $katkeyw  =   post('katkeyw');
                              $katdesc  =   post('katdesc');


                              if (!$katadi || !$katkeyw || !$katdesc ){
                                  echo '<div class="alert alert-danger"> Boş Alan Bırakmayınız</div>';
                              }else{


                                  $varmi = $db->prepare("SELECT * FROM kategoriler WHERE kat_sef=:s AND id!=:id");
                                  $varmi->execute([':s' => $katsef,':id'=>$id]);
                                  if($varmi->rowCount()){
                                      echo '<div class="alert alert-danger">Bu Kategori Zaten Kayıtlı</div>';
                                  }else{

                                      $katguncel = $db->prepare("UPDATE kategoriler SET 
                            kat_adi=:adi,
                            kat_sef=:sef,
                            kat_keyw=:keyw,
                            kat_desc=:a WHERE id=:id ");
                                      $katguncel->execute([':adi'=>$katadi,':sef'=>$katsef,':keyw'=>$katkeyw,':a'=>$katdesc,':id'=>$id]);
                                      if($katguncel){
                                          echo '<div class="alert alert-success">Kategori Başarılı Bir Biçimde Güncellendi</div>';
                                          header('refresh:2;url='.$yonetim."/kategoriler.php");
                                      }else{
                                          echo '<div class="alert alert-danger">HATA OLUŞTU...</div>';
                                      }
                                  }
                              }

                          }

                          ?>
                          <form class="form-horizontal" action="" method="POST">
                              <div class="tile-body">

                                  <div class="form-group row">
                                      <label class="control-label col-md-3">Kategori Adı</label>
                                      <div class="col-md-8">
                                          <input class="form-control" value="<?php echo $row->kat_adi; ?>" type="text" name="katadi" placeholder="Kategori Adı">
                                      </div>
                                  </div>
                                  <div class="form-group row">
                                      <label class="control-label col-md-3">Kategori Anahtar Kelimeler</label>
                                      <div class="col-md-8">
                                          <input class="form-control" type="text" value="<?php echo $row->kat_keyw; ?>" name="katkeyw" placeholder="Kategori Anahtar Kelimeler">
                                      </div>
                                  </div>
                                  <div class="form-group row">
                                      <label class="control-label col-md-3">Kategori Açıklama</label>
                                      <div class="col-md-8">
                                          <input class="form-control" type="text" value="<?php echo $row->kat_desc; ?>" name="katdesc" placeholder="Kategori Açıklama">
                                      </div>
                                  </div>


                              </div>
                              <div class="tile-footer">
                                  <div class="row">
                                      <div class="col-md-8 col-md-offset-3">
                                          <button class="btn btn-primary" type="submit" name="kategoriduzenle"><i class="fa fa-fw fa-lg fa-check-circle"></i>Kategori Düzenle</button>&nbsp;&nbsp;&nbsp;<a class="btn btn-secondary" href="<?php echo $yonetim; ?>/kategoriler.php"><i class="fa fa-fw fa-lg fa-arrow-left"></i>Listeye Dön</a>
                                      </div>
                                  </div>
                              </div>
                          </form>
                              <?php
                      }else{
                          header('Location:'.$yonetim."/kategoriler.php");
                      }

                      break;

                  case'mesajoku':

                      $id = get('id');
                      if(!$id){
                          header('Location:'.$yonetim."/yenimesajlar.php");
                      }
                      $editbul = $db->prepare("SELECT * FROM mesajlar WHERE id=:id");
                      $editbul->execute([':id'=>$id]);
                      if($editbul->rowCount()){
                          $row = $editbul->fetch(PDO::FETCH_OBJ);

                          $guncelle=$db->prepare("UPDATE mesajlar SET durum=:d WHERE id=:id");
                          $guncelle->execute([':d'=>1,':id'=>$id]);
                          echo "<b> İSİM: </b>".$row->isim."<br/>";
                          echo "<b> E-POSTA: </b>".$row->eposta."<br/>";
                          echo "<b> KONU: </b>".$row->konu."<br/>";
                          echo "<b> MESAJ: </b>".$row->mesaj."<br/>";

                          echo "<hr />";
                          echo '<div class="alert alert-info">Bu mesaj<b> '.date('d.m.y H:i',strtotime($row->tarih)).'</b>inde<b> '.$row->ip.'</b> ip adresinden gönderilmiştir....</div>';

                          echo '<a href="'.$yonetim.'/yenimesajlar.php" class="btn btn-secondary"><i class="fa fa-arrow-left"></i>Listeye Dön</a>';
                      }else{
                          header('Location:'.$yonetim."/yenimesajlar.php");
                      }

                      break;

                  case'yorumoku':

                      $id = @get('id');
                      if(!$id){
                          header('Location:'.$yonetim."/bekleyenyorumlar.php");
                      }
                      $editbul = $db->prepare("SELECT * FROM yorumlar INNER JOIN yazilar ON yazilar.yazi_id=yorumlar.yorum_yazi_id WHERE id=:id");
                      $editbul->execute([':id'=>$id]);
                      if($editbul->rowCount()){
                          $row = $editbul->fetch(PDO::FETCH_OBJ);


                          echo "<b> İSİM: </b>".$row->yorum_isim."<br/>";
                          echo "<b> E-POSTA: </b>".$row->yorum_eposta."<br/>";
                          echo "<b> YAZI: </b><a href='".$arow->site_url."/yazidetay.php?yazi_sef=".$row->yazi_sef."&id=".$row->yazi_id."' target='_blank'> ".$row->yazi_baslik."</a><br/>";
                          echo "<b> MESAJ: </b>".$row->yorum_icerik."<br/>";
                          echo "<b> WEB SİTE: </b>".$row->yorum_website."<br/>";

                          echo "<hr />";
                          echo '<div class="alert alert-info">Bu yorum<b> '.date('d.m.y H:i',strtotime($row->yorum_tarih)).'</b>inde<b> '.$row->yorum_ip.'</b> ip adresinden yapılmıştır....</div>';
                          if($row ->yorum_durum == 1){?>
                              <a class="btn btn-danger" onclick="return confirm('Onaylıyor musunuz?');" href="<?php echo $yonetim."/islemler.php?islem=yorumsil&id=".$row->id; ?>"><i class="fa fa-eraser"></i>Yorumu Sil</a>

                          <?php }else{?>
                              <a class="btn btn-success" onclick="return confirm('Onaylıyor musunuz?');" href="<?php echo $yonetim."/islemler.php?islem=yorumonayla&id=".$row->id; ?>"><i class="fa fa-check"></i>Yorumu Onayla</a>

                          <?php    }
                          echo '<a href="'.$yonetim.'/bekleyenyorumlar.php" class="btn btn-secondary"><i class="fa fa-arrow-left"></i>Listeye Dön</a>';
                      }else{
                          header('Location:'.$yonetim."/bekleyenyorumlar.php");
                      }

                      break;
                      case'soru':

                        $id = @get('id');
                        if(!$id){
                            header('Location:'.$yonetim."/bekleyensoru.php");
                        }
                        $editbul = $db->prepare("SELECT * FROM forum WHERE id=:id");
                        $editbul->execute([':id'=>$id]);
                        if($editbul->rowCount()){
                            $row = $editbul->fetch(PDO::FETCH_OBJ);
  
  
                            echo "<b> İSİM: </b>".$row->isim."<br/>";
                            echo "<b> E-POSTA: </b>".$row->eposta."<br/>";
                            echo "<b> MESAJ: </b>".$row->icerik."<br/>";
                            echo "<b> CEVAP: </b>".$row->cevap."<br/>";
                            
  
                            echo "<hr />";
                            echo '<div class="alert alert-info">Bu yorum<b> '.date('d.m.y H:i',strtotime($row->tarih)).' ip adresinden yapılmıştır....</div>';
                            if($row ->durum == 1){?>
                            
                                <a class="btn btn-danger" onclick="return confirm('Onaylıyor musunuz?');" href="<?php echo $yonetim."/islemler.php?islem=sorusil&id=".$row->id; ?>"><i class="fa fa-eraser"></i>Soruyu Sil</a>
  
                            <?php }else{?>
                                
                                <a class="btn btn-success" onclick="return confirm('Onaylıyor musunuz?');" href="<?php echo $yonetim."/islemler.php?islem=soruonayla&id=".$row->id; ?>"><i class="fa fa-check"></i>Soruyu Onayla</a>
  
                            <?php    }
                            echo '<a href="'.$yonetim.'/bekleyensoru.php" class="btn btn-secondary"><i class="fa fa-arrow-left"></i>Listeye Dön</a>';
                        }else{
                            header('Location:'.$yonetim."/bekleyensoru.php");
                        }?>
                        
                            <?php
                        break;
                        case'soruonayla':

                            $id = @get('id');
                            if(!$id){
                                header('Location:'.$yonetim."/bekleyensoru.php");
                            }
                            $onayla = $db->prepare("UPDATE forum SET durum=:d WHERE id=:id");
                            $onayla->execute([':d'=>1,':id'=>$id]);
                            if($onayla){
                                echo '<div class="alert alert-success">Yorum Onaylanmıştır</div>';
                                header('Refresh:2;url='.$_SERVER['HTTP_REFERER']);
                            }else{
                                echo '<div class="alert alert-danger">Hata Oluştu</div>';
                            }
                            break;
                        case 'sorusil':
                            $id = get('id');
                            if(!$id){    header('Location:' . $yonetim."/onaylisoru.php");}
                            $yorumsil =$db->prepare("DELETE FROM forum WHERE id=:id");
                            $yorumsil->execute([':id'=>$id]);
                            if ($yorumsil){
      
                                echo '<div class="alert alert-success">YORUM BAŞARIYLA SİLİNDİ </div>';
                                header('Refresh:2;url='.$_SERVER['HTTP_REFERER']);
                            }else{
                                echo '<div class="alert alert-danger">HATA OLUŞTU</div>';
                            }
                            
                            break;
                  case'edit':

                      $id = @get('id');
                      if(!$id){
                          header('Location:'.$yonetim."/aboneler.php");
                      }
                      $editbul = $db->prepare("SELECT * FROM editor  WHERE id=:id");
                      $editbul->execute([':id'=>$id]);
                      if($editbul->rowCount()){
                          $row = $editbul->fetch(PDO::FETCH_OBJ);


                          echo "<b> İSİM: </b>".$row->kadi."<br/>";
                          echo "<b> E-POSTA: </b>".$row->mail."<br/>";



                          echo "<hr />";
                          echo '<div class="alert alert-info">Bu Editör<b> '.date('d.m.y H:i',strtotime($row->sontarih)).'</b>inde<b> '.$row->sonip.'</b> ip adresinden Kaydolmuştur....</div>';
                          if($row ->durum == 1){?>
                              <a class="btn btn-danger" onclick="return confirm('Onaylıyor musunuz?');" href="<?php echo $yonetim."/islemler.php?islem=editsil&id=".$row->id; ?>"><i class="fa fa-eraser"></i>Editörü Sil</a>

                          <?php }else{?>
                              <a class="btn btn-success" onclick="return confirm('Onaylıyor musunuz?');" href="<?php echo $yonetim."/islemler.php?islem=editonayla&id=".$row->id; ?>"><i class="fa fa-check"></i>Editörü Onayla</a>

                          <?php    }
                          echo '<a href="'.$yonetim.'/aboneler.php" class="btn btn-secondary"><i class="fa fa-arrow-left"></i>Listeye Dön</a>';
                      }else{
                          header('Location:'.$yonetim."/aboneler.php");
                      }

                      break;
                      case'abone':

                      $id = @get('id');
                      if(!$id){
                          header('Location:'.$yonetim."/abone.php");
                      }
                      $editbul = $db->prepare("SELECT * FROM aboneler  WHERE id=:id");
                      $editbul->execute([':id'=>$id]);
                      if($editbul->rowCount()){
                          $row = $editbul->fetch(PDO::FETCH_OBJ);


                          
                          echo "<b> E-POSTA: </b>".$row->abone_mail."<br/>";



                          echo "<hr />";
                          echo '<div class="alert alert-info">Bu Üye<b> '.date('d.m.y H:i',strtotime($row->abone_tarih)).'</b>inde<b> '.$row->abone_ip.'</b> ip adresinden Kaydolmuştur....</div>';
                          if($row ->durum == 1){?>
                              <a class="btn btn-danger" onclick="return confirm('Onaylıyor musunuz?');" href="<?php echo $yonetim."/islemler.php?islem=abonesil&id=".$row->id; ?>"><i class="fa fa-eraser"></i>Üyeyi Sil</a>

                          <?php }else{?>
                              <a class="btn btn-success" onclick="return confirm('Onaylıyor musunuz?');" href="<?php echo $yonetim."/islemler.php?islem=aboneonayla&id=".$row->id; ?>"><i class="fa fa-check"></i>Üyeyi Onayla</a>

                          <?php    }
                          echo '<a href="'.$yonetim.'/abone.php" class="btn btn-secondary"><i class="fa fa-arrow-left"></i>Listeye Dön</a>';
                      }else{
                          header('Location:'.$yonetim."/abone.php");
                      }

                      break;
                       case'aboneonayla':

                      $id = @get('id');
                      if(!$id){
                          header('Location:'.$yonetim."/abone.php");
                      }
                      $onayla = $db->prepare("UPDATE aboneler SET durum=:d WHERE id=:id");
                      $onayla->execute([':d'=>1,':id'=>$id]);
                      if($onayla){
                          echo '<div class="alert alert-success">Üye Onaylanmıştır</div>';
                          header('Refresh:2;url='.$_SERVER['HTTP_REFERER']);
                      }else{
                          echo '<div class="alert alert-danger">Hata Oluştu</div>';
                      }
                      break;
                  case'editonayla':

                      $id = @get('id');
                      if(!$id){
                          header('Location:'.$yonetim."/aboneler.php");
                      }
                      $onayla = $db->prepare("UPDATE editor SET durum=:d WHERE id=:id");
                      $onayla->execute([':d'=>1,':id'=>$id]);
                      if($onayla){
                          echo '<div class="alert alert-success">Editör Onaylanmıştır</div>';
                          header('Refresh:2;url='.$_SERVER['HTTP_REFERER']);
                      }else{
                          echo '<div class="alert alert-danger">Hata Oluştu</div>';
                      }
                      break;



                  case'yorumonayla':

                      $id = @get('id');
                      if(!$id){
                          header('Location:'.$yonetim."/bekleyenyorumlar.php");
                      }
                      $onayla = $db->prepare("UPDATE yorumlar SET yorum_durum=:d WHERE id=:id");
                      $onayla->execute([':d'=>1,':id'=>$id]);
                      if($onayla){
                          echo '<div class="alert alert-success">Yorum Onaylanmıştır</div>';
                          header('Refresh:2;url='.$_SERVER['HTTP_REFERER']);
                      }else{
                          echo '<div class="alert alert-danger">Hata Oluştu</div>';
                      }
                      break;

                  case 'sosyalmedyaduzenle':
                      $id = get('id');
                      if(!$id){
                          header('Location:'.$yonetim."/sosyalmedya.php");
                      }
                      $medyabul =$db->prepare("SELECT * FROM sosyalmedya WHERE id=:id");
                      $medyabul->execute([':id'=>$id]);
                      if ($medyabul->rowCount()) {
                          $row = $medyabul->fetch(PDO::FETCH_OBJ);

                          if(isset($_POST['sosyalmedyaduzenle'])){

                              $ikon   =   post('ikon');
                              $link  =   post('link');
                              $durum  =   post('durum');




                              if (!$ikon || !$link || !$durum){
                                  echo '<div class="alert alert-danger"> Boş Alan Bırakmayınız</div>';
                              }else{


                                  $sosyalguncelle = $db->prepare("UPDATE sosyalmedya SET 
                            ikon=:ik, link=:lk,durum=:d WHERE id=:id
                            ");
                                  $sosyalguncelle->execute([':ik'=>$ikon,':lk'=>$link,':d'=>$durum,':id'=>$id]);
                                  if($sosyalguncelle->rowCount()){
                                      echo '<div class="alert alert-success">Sosyal Medya Hesabı Başarıyla Güncellendi</div>';
                                      header('refresh:2;url='.$yonetim."/sosyalmedya.php");
                                  }else{
                                      echo '<div class="alert alert-danger">HATA OLUŞTU...</div>';
                                  }
                              }
                          }
                          ?>
                          <form class="form-horizontal" action="" method="POST">
                              <div class="tile-body">

                                  <div class="form-group row">
                                      <label class="control-label col-md-3">Sosyal Medya İkon</label>
                                      <div class="col-md-8">
                                          <input class="form-control" value="<?php echo $row->ikon; ?>" type="text" name="ikon" placeholder="ikon">
                                      </div>
                                  </div>
                                  <div class="form-group row">
                                      <label class="control-label col-md-3">Sosyal Medya Link</label>
                                      <div class="col-md-8">
                                          <input class="form-control" value="<?php echo $row->link; ?>" type="text" name="link" placeholder="Sosyal medya link">
                                      </div>
                                  </div>

                                  <div class="form-group row">
                                      <label class="control-label col-md-3">Sosyal Medya Durumu</label>
                                      <div class="col-md-8">
                                          <select name="durum" class="form-control">
                                              <option value="1" <?php echo $row->durum ==1 ? 'selected':null ; ?>>Aktif</option>
                                              <option value="2"<?php echo $row->durum ==2 ? 'selected':null ; ?>>Pasif</option>

                                          </select>
                                      </div>
                                  </div>




                              </div>
                              <div class="tile-footer">
                                  <div class="row">
                                      <div class="col-md-8 col-md-offset-3">
                                          <button class="btn btn-primary" type="submit" name="sosyalmedyaduzenle"><i class="fa fa-fw fa-lg fa-check-circle"></i>Sosyal Medya Düzenle</button>&nbsp;&nbsp;&nbsp;<a class="btn btn-secondary" href="<?php echo $yonetim; ?>/sosyalmedya.php"><i class="fa fa-fw fa-lg fa-arrow-left"></i>Listeye Dön</a>
                                      </div>
                                  </div>
                              </div>
                          </form>

                              <?php
                      }
                      break;

                  case'genel':

                      if (isset($_POST['genelguncel'])){
                          $url = post('url');
                          $baslik = post('baslik');
                          $keyw = post('anahtar');
                          $aciklama = post('aciklama');
                          $durum = post('durum');

                          if(!$url || !$baslik || !$keyw || !$aciklama || !$durum){
                              echo '<div class="alert alert-danger">Boş Alan Bırakmayınız</div>';
                          }else {
                              $guncelle =$db ->prepare("UPDATE ayarlar SET 
site_url=:u, site_baslik=:b, site_keyw=:k, site_desc=:d,site_durum=:dd
 WHERE id=:id");
                              $guncelle->execute([':u'=>$url,':b'=>$baslik,':k'=>$keyw,':d'=>$aciklama,':dd'=>$durum,':id'=>1]);
                              if($guncelle){
                                  echo '<div class="alert alert-success">Genel Ayarlar Güncellendi...</div>';
                                  header('refresh:2;url='.$_SERVER['HTTP_REFERER']);

                              }else{
                                  echo '<div class="alert alert-danger">Hata Oluştu</div>';
                              }
                          }
                      }

                      ?>
                      <form class="form-horizontal" action="" method="POST">
                          <div class="tile-body">

                              <div class="form-group row">
                                  <label class="control-label col-md-3">Site URL</label>
                                  <div class="col-md-8">
                                      <input class="form-control" value="<?php echo $arow->site_url ;?>" type="text" name="url" placeholder="URL">
                                  </div>
                              </div>
                              <div class="form-group row">
                                  <label class="control-label col-md-3">Site Başlık</label>
                                  <div class="col-md-8">
                                      <input class="form-control" value="<?php echo $arow->site_baslik ;?>" type="text" name="baslik" placeholder="Başlık">
                                  </div>
                              </div>
                              <div class="form-group row">
                                  <label class="control-label col-md-3">Site Anahtar Kelimeler</label>
                                  <div class="col-md-8">
                                      <input class="form-control" value="<?php echo $arow->site_keyw ;?>" type="text" name="anahtar" placeholder="Anahtar kelimeler">
                                  </div>
                              </div>
                              <div class="form-group row">
                                  <label class="control-label col-md-3">Site Açıklaması</label>
                                  <div class="col-md-8">
                                      <input class="form-control" value="<?php echo $arow->site_desc ;?>" type="text" name="aciklama" placeholder="Site açıklaması">
                                  </div>
                              </div>
                              <div class="form-group row">
                                  <label class="control-label col-md-3">Site Durumu</label>
                                  <div class="col-md-8">
                                      <select name="durum" class="form-control">
                                          <option value="1" <?php echo $arow->site_durum ==1 ? 'selected':null ; ?>>Aktif</option>
                                          <option value="2"<?php echo $arow->site_durum ==2 ? 'selected':null ; ?>>Pasif</option>

                                      </select>
                                  </div>
                              </div>


                          </div>
                          <div class="tile-footer">
                              <div class="row">
                                  <div class="col-md-8 col-md-offset-3">
                                      <button class="btn btn-primary" type="submit" name="genelguncel"><i class="fa fa-fw fa-lg fa-check-circle"></i>Genel Ayarları Güncelle</button>&nbsp;&nbsp;&nbsp;<a class="btn btn-secondary" href="<?php echo $yonetim; ?>"><i class="fa fa-fw fa-lg fa-arrow-left"></i>Geri Dön</a>
                                  </div>
                              </div>
                          </div>
                      </form>

                  <?php

                      break;


                  case'iletisim':

                      if (isset($_POST['iletisimguncel'])){
                          $mail = post('mail');
                          $harita = post('harita');
                          $haritadurum = post('haritadurum');


                          if(!$mail || !$harita || !$haritadurum ){
                              echo '<div class="alert alert-danger">Boş Alan Bırakmayınız</div>';
                          }else {
                              $guncelle =$db ->prepare("UPDATE ayarlar SET 
site_mail=:u, site_harita=:b, harita_durum=:k
 WHERE id=:id");
                              $guncelle->execute([':u'=>$mail,':b'=>$harita,':k'=>$haritadurum,':id'=>1]);
                              if($guncelle){
                                  echo '<div class="alert alert-success">İletişim Ayarları Güncellendi...</div>';
                                  header('refresh:2;url='.$_SERVER['HTTP_REFERER']);

                              }else{
                                  echo '<div class="alert alert-danger">Hata Oluştu</div>';
                              }
                          }
                      }

                      ?>
                      <form class="form-horizontal" action="" method="POST">
                          <div class="tile-body">

                              <div class="form-group row">
                                  <label class="control-label col-md-3">Site Mail</label>
                                  <div class="col-md-8">
                                      <input class="form-control" value="<?php echo $arow->site_mail ;?>" type="text" name="mail" placeholder="Mil">
                                  </div>
                              </div>
                              <div class="form-group row">
                                  <label class="control-label col-md-3">Site Harita</label>
                                  <div class="col-md-8">
                                      <input class="form-control" value="<?php echo $arow->site_harita ;?>" type="text" name="harita" placeholder="Harita">
                                  </div>
                              </div>

                              <div class="form-group row">
                                  <label class="control-label col-md-3">Harita Durumu</label>
                                  <div class="col-md-8">
                                      <select name="haritadurum" class="form-control">
                                          <option value="1" <?php echo $arow->harita_durum ==1 ? 'selected':null ; ?>>Aktif</option>
                                          <option value="2"<?php echo $arow->harita_durum ==2 ? 'selected':null ; ?>>Pasif</option>

                                      </select>
                                  </div>
                              </div>


                          </div>
                          <div class="tile-footer">
                              <div class="row">
                                  <div class="col-md-8 col-md-offset-3">
                                      <button class="btn btn-primary" type="submit" name="iletisimguncel"><i class="fa fa-fw fa-lg fa-check-circle"></i>İletişim Ayarlarını Güncelle</button>&nbsp;&nbsp;&nbsp;<a class="btn btn-secondary" href="<?php echo $yonetim; ?>"><i class="fa fa-fw fa-lg fa-arrow-left"></i>Geri Dön</a>
                                  </div>
                              </div>
                          </div>
                      </form>

                      <?php

                      break;

                  case 'logo':
                   
                      if(isset($_POST['logoduzenle'])){
                          require_once 'inc/class.upload.php';
                        //   echo 'adsadsasdasd';
                        //   print_r($_FILES);
                        //         die;
                          $image= new Upload($_FILES['logo']);
                          if($image->uploaded) {
                              $rname = md5(uniqid());
                              $image->allowed = array("image/*");
                              $image->image_convert = "webp";
                              $image->file_new_name_body = $rname;

                              $image->process("../images");
                              if ($image->processed) {
                                    $foto = $image->file_dst_name;
                                  $guncelle =$db->prepare("UPDATE ayarlar SET site_logo=:l WHERE id=:id");
                                  $guncelle->execute([':l'=>$foto,':id'=>1]);
                                  if($guncelle){
                                      echo '<div class="alert alert-success">Logo Güncellendi</div>';
                                      header('refresh:2;url='.$_SERVER['HTTP_REFERER']);

                                  }else{
                                      echo '<div class="alert alert-danger">Hata Oluştu</div>';
                                  }

                              }else{
                                  echo '<div class="alert alert-danger">Resim Taşınmadı</div>';
                              }

                          }else{
                              echo '<div class="alert alert-danger">Resim Seçmediniz</div>';
                          }
                      }
                      ?>
                      <form class="form-horizontal" action="<?php echo $yonetim ?>/islemler.php?islem=logo" target="_blank" method="POST" enctype="multipart/form-data">
                          <div class="tile-body">

                              <div class="form-group row">
                                  <label class="control-label col-m
                                  d-3">LOGO</label>
                                  <input type="hidden" name="logoduzenle" value="1">
                                  <div class="col-md-8">
                                      <img src="<?php echo $arow->site_url;?>/images/<?php echo $arow->site_logo ; ?>" width="250" height="250"/>
                                      <input class="form-control" type="file" name="new-logo" >
                                  </div>
                              </div>

                          </div>
                          <div class="tile-footer">
                              <div class="row">
                                  <div class="col-md-8 col-md-offset-3">
                                      <button class="btn btn-primary" type="submit" name="logoduzenle"><i class="fa fa-fw fa-lg fa-check-circle"></i>Logo Düzenle</button>&nbsp;&nbsp;&nbsp;<a class="btn btn-secondary" href="<?php echo $yonetim; ?>"><i class="fa fa-fw fa-lg fa-arrow-left"></i>Listeye Dön</a>
                                  </div>
                              </div>
                          </div>
                      </form>
                  <?php

                      break;

                  case 'favicon':
                      if(isset($_POST['favduzenle'])){
                          require_once 'inc/class.upload.php';
                          
                          $image= new Upload($_FILES['logo']);
                          if($image->uploaded) {
                              $rname = md5(uniqid());
                              $image->allowed = array("image/*");
                              $image->image_convert = "webp";
                              $image->file_new_name_body = $rname;

                              $image->process("../images");
                              if ($image->processed) {
                                $foto = $image->file_dst_name;
                                  $guncelle =$db->prepare("UPDATE ayarlar SET site_favicon=:l WHERE id=:id");
                                  $guncelle->execute([':l'=>$foto, ':id'=>1]);
                                  if($guncelle){
                                      echo '<div class="alert alert-success">Favicon Güncellendi</div>';
                                      header('refresh:2;url='.$_SERVER['HTTP_REFERER']);

                                  }else{
                                      echo '<div class="alert alert-danger">Hata Oluştu</div>';
                                  }

                              }else{
                                  echo '<div class="alert alert-danger">Resim Taşınmadı</div>';
                              }

                          }else{
                              echo '<div class="alert alert-danger">Resim Seçmediniz</div>';
                          }
                      }
                      ?>
                      <form class="form-horizontal" action="" method="POST" enctype="multipart/form-data">
                          <div class="tile-body">

                              <div class="form-group row">
                                  <label class="control-label col-md-3">FAVİCON</label>
                                  <div class="col-md-8">
                                      <img src="<?php echo $arow->site_url;?>/images/<?php echo $arow->site_favicon ; ?>" width="64" height="64"/>
                                      <input class="form-control" type="file" name="logo" >
                                  </div>
                              </div>

                          </div>
                          <div class="tile-footer">
                              <div class="row">
                                  <div class="col-md-8 col-md-offset-3">
                                      <button class="btn btn-primary" type="submit" name="favduzenle"><i class="fa fa-fw fa-lg fa-check-circle"></i>Favicon Düzenle</button>&nbsp;&nbsp;&nbsp;<a class="btn btn-secondary" href="<?php echo $yonetim; ?>"><i class="fa fa-fw fa-lg fa-arrow-left"></i>Listeye Dön</a>
                                  </div>
                              </div>
                          </div>
                      </form>

                      <?php

                      break;

                  case'dogrulama':

                      if (isset($_POST['dogrulamaguncel'])){
                          $google = post('google');
                          $bing = post('bing');
                          $yandex = post('yandex');
                          $analic = post('analic');




                          if(!$google || !$bing || !$yandex ||!$analic ){
                              echo '<div class="alert alert-danger">Boş Alan Bırakmayınız</div>';
                          }else {
                              $guncelle =$db ->prepare("UPDATE ayarlar SET 
	site_dogrulama_kodu=:u, yandex_dogrulama_kodu=:b, bing_dogrulama_kodu=:k, analiytcs_kodu=:t
 WHERE id=:id");
                              $guncelle->execute([':u'=>$google,':b'=>$yandex,':k'=>$bing,':t'=>$analic,':id'=>1]);
                              if($guncelle){
                                  echo '<div class="alert alert-success">Webmaster Araçları Doğrulama Ayarları Güncellendi...</div>';
                                  header('refresh:2;url='.$_SERVER['HTTP_REFERER']);

                              }else{
                                  echo '<div class="alert alert-danger">Hata Oluştu</div>';
                              }
                          }
                      }

                      ?>
                      <form class="form-horizontal" action="" method="POST">
                          <div class="tile-body">

                              <div class="form-group row">
                                  <label class="control-label col-md-3">Google Doğrulama Kodu</label>
                                  <div class="col-md-8">
                                      <input class="form-control" value="<?php echo $arow->	site_dogrulama_kodu ;?>" type="text" name="google" >
                                  </div>
                              </div>
                              <div class="form-group row">
                                  <label class="control-label col-md-3">Bing Doğrulama Kodu</label>
                                  <div class="col-md-8">
                                      <input class="form-control" value="<?php echo $arow->	bing_dogrulama_kodu ;?>" type="text" name="bing" >
                                  </div>
                              </div>
                              <div class="form-group row">
                                  <label class="control-label col-md-3">Yandex Doğrulama Kodu</label>
                                  <div class="col-md-8">
                                      <input class="form-control" value="<?php echo $arow->	yandex_dogrulama_kodu ;?>" type="text" name="yandex" >
                                  </div>
                              </div>
                              <div class="form-group row">
                                  <label class="control-label col-md-3">Google Analytics Doğrulama Kodu</label>
                                  <div class="col-md-8">
                                      <input class="form-control" value="<?php echo $arow->analiytcs_kodu ;?>" type="text" name="analic" >
                                  </div>
                              </div>



                          </div>
                          <div class="tile-footer">
                              <div class="row">
                                  <div class="col-md-8 col-md-offset-3">
                                      <button class="btn btn-primary" type="submit" name="dogrulamaguncel"><i class="fa fa-fw fa-lg fa-check-circle"></i>Doğrulama Ayarlarını Güncelle</button>&nbsp;&nbsp;&nbsp;<a class="btn btn-secondary" href="<?php echo $yonetim; ?>"><i class="fa fa-fw fa-lg fa-arrow-left"></i>Geri Dön</a>
                                  </div>
                              </div>
                          </div>
                      </form>

                      <?php

                      break;
                  ##düzenleme ve okuma işlemleri sonu
              }


              ?>


          </div>
        </div>
      </div>
    </main>
<?php require_once 'inc/alt.php'; ?>