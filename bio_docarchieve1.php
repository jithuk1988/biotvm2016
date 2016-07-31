<?php
$PageSecurity = 40;
include('includes/session.inc');

/*    $sql6="UPDATE bio_lsg_fileno set lastfileno='1'  where LSG_type=$lsgtype  AND id='".$_GET['maxid']."'";   
        DB_query($sql6,$db);*/  
       
       $sql3="INSERT INTO bio_fileno (debtorno,orderno,fileno) VALUES ('".$_GET['debtor']."' , '".$_GET['order']."' , '".$_GET['fileno']."')";                    
                  DB_query($sql3,$db);

?>
<script>
location.redirect("OLDbio_documentmanagement.php?orderno="<?php echo $_GET['order'];?>)
   /*href.location='bio_documentmanagement.php?orderno=' echo $_GET['order'];?>;    */
</script>
