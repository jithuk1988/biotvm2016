<?php
   $PageSecurity=80;
include('includes/session.inc');
 $title = _('Recieve Raw material Items'); 
 include('includes/header.inc');
 $pagetype=3;
include('includes/sidemenu1.php');
 
 echo '<p class="page_title_text"><img src="'.$rootpath.'/css/'.$theme.'/images/maintenance.png"  alt="" />' . ' ' . _('Recieve Raw material Items') . '</p>';
    
    echo '<form action="' . $_SERVER['PHP_SELF'] . '" method="post">';
       if($_POST['sel_item'])
    {
     
         for($i=0;$i<$_POST['totrow'];$i++)
         {
            if($_POST['sel'.$i]!="")
                {
                    $loc=$_SESSION['UserStockLocation'];
                      $sql_qty=DB_query("SELECT quantity FROM locstock WHERE loccode='".$_POST['fromloc'.$i]."' AND stockid='".$_POST['stock'.$i]."'",$db);
                      $Res=DB_fetch_row($sql_qty);
                      $qty1=$Res[0]-($_POST['sel'.$i]);
             
                      $locstupdate="UPDATE locstock set quantity='".$qty1."' WHERE loccode='".$_POST['fromloc'.$i]."' AND stockid='".$_POST['stock'.$i]."'";
                       $res_up=DB_query($locstupdate,$db);
                       
                       
                        $sql_qty1=DB_query("SELECT quantity FROM locstock WHERE loccode='".$loc."' AND stockid='".$_POST['stock'.$i]."'",$db);
                       $Res1=DB_fetch_row($sql_qty1);
                        $qty2=$Res1[0]+$_POST['sel'.$i];
                        
                         $locstupdate="UPDATE locstock set quantity='".$qty2."' WHERE loccode='".$loc."' AND stockid='".$_POST['stock'.$i]."'";
                        $res_up=DB_query($locstupdate,$db);
                        
                        $a=$_POST['sel'.$i]+$_POST['recieved'.$i];
                        $recupdate="UPDATE bio_stocktransfer set recieved='".$a."'  WHERE stockid='".$_POST['stock'.$i]."' 
                         AND fromloc='".$_POST['fromloc'.$i]."' AND toloc='".$loc."' AND id='".$_POST['transno'.$i]."'";
                         $recup_result=DB_query($recupdate,$db);
                         
                         $sql_stkmv2="INSERT INTO  stockmoves 
                          (stockid,type,transno,loccode,trandate,qty,newqoh) VALUES ('".$_POST['stock'.$i]."',16,'".$_POST['transno'.$i]."',
                          '".$_POST['fromloc'.$i]."','".date('Y-m-d')."',-('".$_POST['sel'.$i]."'),'".$qty1."')";
                            $res_insert2=DB_query($sql_stkmv2,$db);
                         
                         $sql_stkmv2="INSERT INTO  stockmoves 
                          (stockid,type,transno,loccode,trandate,qty,newqoh) VALUES ('".$_POST['stock'.$i]."',16,'".$_POST['transno'.$i]."',
                          '".$loc."','".date('Y-m-d')."','".$_POST['sel'.$i]."','".$qty2."')";
                            $res_insert2=DB_query($sql_stkmv2,$db);
                }
         }
    }  
     //qty1//$_POST['fromloc'.$i]$a
     echo"<table style='width:100%'><tr><td>";
 
// $_SESSION['UserStockLocation'];
     echo "<fieldset style='float:center;width:70%;'>";     
     echo "<legend><h3>Select Raw material To Recieve</h3>";
     echo "</legend>";
     
          echo"<table style='border:1px solid #F0F0F0;width:80%'; >";   
     // echo "<fieldset style='float:center;width:97%;'>";     
   $loc=$_SESSION['UserStockLocation']; 
  //$loc=2; 
     $SQL="SELECT bio_stocktransfer.qty,bio_stocktransfer.recieved,stockmaster.description,locations.locationname,bio_stocktransfer.fromloc,stockmaster.stockid,bio_stocktransfer.id   
    FROM bio_stocktransfer,stockmaster,locations
    WHERE bio_stocktransfer.toloc='".$loc."'
    AND stockmaster.stockid=bio_stocktransfer.stockid
    AND locations.loccode=bio_stocktransfer.fromloc
 AND bio_stocktransfer.type=5
    AND (bio_stocktransfer.qty > bio_stocktransfer.recieved)"; 
$Result=DB_query($SQL,$db);
    
echo"<tr>";
echo "<tr><th>Sl No.</th><th width=300px>Item</th><th>From</th><th>quantity</th><th>Select</th></tr>";
$k=0;
$i=1;
while($Row=DB_fetch_array($Result))
{
    
    if ($k==1)
    {
    echo '<tr class="EvenTableRows">';
    $k=0;
    }else 
    {
    echo '<tr class="OddTableRows">';
    $k=1;     
    }$a=$Row['qty'];
    $b=$Row['recieved'];
    $e=$a-$b;
    echo "<td>".$i."</td>";
    echo "<td>".$Row['description']."</td>";
    echo "<td>".$Row['locationname']."</td>";
     echo "<td>".$e."</td>";
echo "<td><input type=text name='sel".$i."' id='sel".$i."' ></tr>";
    echo "<input type=hidden name='recieved".$i."' value=".$Row['recieved'].">";
     echo "<input type=hidden name='stock".$i."' value=".$Row['stockid'].">";
     echo "<input type=hidden name='fromloc".$i."' value=".$Row['fromloc'].">";
         echo "<input type=hidden name='qty".$i."' id='qty".$i."' value=".$e.">";
     echo "<input type=hidden name='transno".$i."' value=".$Row['id'].">";
    $i++;
}
echo "<input type=hidden name='totrow' id='totrow' value=".$i.">";

echo "</table>";
echo "<br><br><center><input type=submit name=sel_item value='Select Items To Recieve' onclick='if(valid()==1)return false;'></center>";   
?>
<script>
function valid()
{
      var f=0;
    totrow=document.getElementById("totrow").value;
    for(i=1;i<totrow;i++)
    {
       
     var   qty=document.getElementById("qty"+i).value;
    var   sel=document.getElementById("sel"+i).value;
  var a=qty-sel;//alert(a);
        if(a<0)
        {   // alert(sel);   
            alert("Transfer quantity exeeded than available quantity");
            document.getElementById("sel"+i).focus();
            f=1;
     }
     if(sel<=0 && sel!="")
        {
            alert("Transfer quantity is too low");
              document.getElementById("sel"+i).focus();
            f=1;
        }
    }return f;
   // str2=document.getElementById("cmcapacity").value;
    
}
</script>