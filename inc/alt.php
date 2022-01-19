<?php
echo !defined('guvenlik') ? die() : null;

?>
<footer id="footer">

    <div class="copyright-content">
        <div class="container">
            <div class="copyright-text pull-left">2022</div>
            
            <div class="copyright-text pull-right">
               <?php
               $sosyalmedya = $db->prepare("SELECT * FROM sosyalmedya WHERE durum=:d");
               $sosyalmedya->execute([':d'=>1]);
               if($sosyalmedya->rowCount()){
                   foreach ($sosyalmedya as $item){
                       ?>
                       <a href="<?php echo $item ['link']?>" target="_blank" >   <i class="fa fa-<?php echo $item ['ikon']?> fa-lg"></i></a>
                <?php
                   }
               }

               ?>


            </div>
        </div>

    </div>
</footer>
<!-- end: Footer -->

</div>
<!-- end: Body Inner -->

<!-- Scroll top -->
<a id="scrollTop"><i class="icon-chevron-up1"></i><i class="icon-chevron-up1"></i></a>

<!--Plugins-->
<script src="js/jquery.js"></script>
<script src="js/plugins.js"></script>
<script src="js/ajax.js"></script>

<!--Template functions-->
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="js/ajax.js?v=1234"></script>
<script src="https://use.fontawesome.com/24eacb6277.js"></script>
<script src="js/functions.js"></script>


</body>

</html>