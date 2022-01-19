function mesajgonder(){
    var deger=$("#iletisimformu").serialize();
    $.ajax({
        type:"POST",
        url: "inc/mesajgonder.php",
        data:deger,
        success:function (sonuc){
            if($.trim(sonuc)=="bos"){
                tatliUyari("Hata","Tamam!","error","lütfen boş bırakmayınız");
            }else if ($.trim(sonuc)=="format"){
                tatliUyari("Hata","Tamam!","error","e posta formatı yanlış");
            }
            else if ($.trim(sonuc)=="hata"){
                tatliUyari("Hata","Tamam!","error","sistem hatası oluştı");
            }
            else if ($.trim(sonuc)=="basarili"){
                tatliUyari("Başarılı","Tamam!","success","Mesajınız alınmıştır");
                $("input[name=ad]").val('');
                $("input[name=eposta]").val('');
                $("input[name=konu]").val('');
                $("textarea[name=mesaj]").val('');
            }
        }
    });
}
function sorusor(){
    var deger=$("#soruformu").serialize();
    $.ajax({
        type:"POST",
        url:  "inc/sorusor.php",
        data:deger,
        success:function (sonuc){
            if($.trim(sonuc)=="bos"){
                tatliUyari("Hata","Tamam!","error","lütfen boş bırakmayınız");
            }else if ($.trim(sonuc)=="format"){
                tatliUyari("Hata","Tamam!","error","e posta formatı yanlış");
            }
            else if ($.trim(sonuc)=="hata"){
                tatliUyari("Hata","Tamam!","error","sistem hatası oluştı");
            }
            else if ($.trim(sonuc)=="basarili"){
                tatliUyari("Başarılı","Tamam!","success","Soru Gönderilmiştir. Yönetici tarafından onaylandığında yansıtılacaktır");
                $("input[name=isim]").val('');
                $("input[name=eposta]").val('');
                $("textarea[name=icerik]").val('');
            }
        }
    });
    }

function tatliUyari(a,b,c,d){
    swal({
        title: a,
        icon: c,
        button: b,
        text: d,
    });
}
function yorumyap(){
var deger=$("#yorumformu").serialize();
$.ajax({
    type:"POST",
    url:  "inc/yorumyap.php",
    data:deger,
    success:function (sonuc){
        if($.trim(sonuc)=="bos"){
            tatliUyari("Hata","Tamam!","error","lütfen boş bırakmayınız");
        }else if ($.trim(sonuc)=="format"){
            tatliUyari("Hata","Tamam!","error","e posta formatı yanlış");
        }
        else if ($.trim(sonuc)=="hata"){
            tatliUyari("Hata","Tamam!","error","sistem hatası oluştı");
        }
        else if ($.trim(sonuc)=="basarili"){
            tatliUyari("Başarılı","Tamam!","success","Yorum Gönderilmiştir. Yönetici tarafından onaylandığında yansıtılacaktır");
            $("input[name=adsoyad]").val('');
            $("input[name=eposta]").val('');
            $("input[name=website]").val('');
            $("textarea[name=yorum]").val('');
        }
    }
});
}function tatliUyari(a,b,c,d){
    swal({
        title: a,
        icon: c,
        button: b,
        text: d,
    });
}function aboneol(){
    var deger=$("#aboneformu").serialize();
    $.ajax({
        type:"POST",
        url:  "inc/aboneol.php",
        data:deger,
        success:function (sonuc){
            if($.trim(sonuc)=="bos"){
                tatliUyari("Hata","Tamam!","error","lütfen boş bırakmayınız");
            }else if ($.trim(sonuc)=="format"){
                tatliUyari("Hata","Tamam!","error","e posta formatı yanlış");
            }
            else if ($.trim(sonuc)=="hata"){
                tatliUyari("Hata","Tamam!","error","sistem hatası oluştı");
            }
            else if ($.trim(sonuc)=="basarili"){
                tatliUyari("Başarılı","Tamam!","success","Abonemiz Olduğunuz İçin Teşekkürler");

                $("input[name=eposta]").val('');

            }else if ($.trim(sonuc)=="var"){
                tatliUyari("Hata","Tamam!","error","Zaten Abonemizsiniz");
            }
        }
    });
}function tatliUyari(a,b,c,d){
    swal({
        title: a,
        icon: c,
        button: b,
        text: d,
    });
}
