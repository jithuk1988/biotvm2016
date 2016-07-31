<?php
    $PageSecurity=80;
    $pagetype=1;
include('includes/session.inc');
 $title = _('Recieve Stock Items'); 
 include('includes/header.inc');
 include('includes/sidemenu1.php');
 
 echo '<p class="page_title_text"><img src="'.$rootpath.'/css/'.$theme.'/images/maintenance.png" title="' . _('Search') .
    '" alt="" />' . ' ' . _('Recieve Stock Items') . '</p>';
    
    echo '<form action="' . $_SERVER['PHP_SELF'] . '" method="post">';
    
      if($_POST['sel_item'])
    {
     
         for($i=0;$i<$_POST['totrow'];$i++)
         {
           // echo $_POST['sel'.$i];
         if($_POST['sel'.$i]=='on')
 {
  /*$sql_insert="INSERT INTO  bio_stocktransfer(stockid,fromloc,toloc,qty,serialno) values ('".$_POST['stock'.$i]."',".$_POST['loc1'].",
             ".$_POST['loc2'].",1,'".$_POST['serial'.$i]."')";*/
             
             $loc=$_SESSION['UserStockLocation'];
             //$loc=2;
             
            
             
             // UPDATE Stock Serial Items location
             
             $sql_stkserial="UPDATE stockserialitems set loccode='".$loc."' WHERE stockid='".$_POST['stock'.$i]."'
             AND serialno='".$_POST['serial'.$i]."' ";
             $res_insert3=DB_query($sql_stkserial,$db);
             
             // Reduce qty of from location
            // echo "SELECT quantity FROM locstock WHERE loccode='".$_POST['fromloc'.$i]."' AND stockid='".$_POST['stock'.$i]."'";
             $sql_qty=DB_query("SELECT quantity FROM locstock WHERE loccode='".$_POST['fromloc'.$i]."' AND stockid='".$_POST['stock'.$i]."'",$db);
             $Res=DB_fetch_row($sql_qty);
             $qty1=$Res[0]-1;
             
             $locstupdate="UPDATE locstock set quantity='".$qty1."' WHERE loccode='".$_POST['fromloc'.$i]."' AND stockid='".$_POST['stock'.$i]."'";
             $res_up=DB_query($locstupdate,$db);
             
             // Increase qty of recieved location
             
             $sql_qty1=DB_query("SELECT quantity FROM locstock WHERE loccode='".$loc."' AND stockid='".$_POST['stock'.$i]."'",$db);
             $Res1=DB_fetch_row($sql_qty1);
             $qty2=$Res1[0]+1;
             
             $locstupdate="UPDATE locstock set quantity='".$qty2."' WHERE loccode='".$loc."' AND stockid='".$_POST['stock'.$i]."'";
             $res_up=DB_query($locstupdate,$db);
             
             $recupdate="UPDATE bio_stocktransfer set recieved=1 WHERE stockid='".$_POST['stock'.$i]."' 
             AND fromloc='".$_POST['fromloc'.$i]."' AND toloc='".$loc."' AND serialno='".$_POST['serial'.$i]."' ";
             $recup_result=DB_query($recupdate,$db);
             
             //INSERT TO STOCK MOVES 
             $sql_stkmv1="INSERT INTO  stockmoves (stockid,type,transno,loccode,trandate,qty,newqoh) VALUES ('".$_POST['stock'.$i]."',16,'".$_POST['transno'.$i]."',
             '".$_POST['fromloc'.$i]."','".date('Y-m-d')."',-1,'".$qty1." ')";
             
             $res_insert1=DB_query($sql_stkmv1,$db);
             
             $sql_stkmv2="INSERT INTO  stockmoves (stockid,type,transno,loccode,trandate,qty,newqoh) VALUES ('".$_POST['stock'.$i]."',16,'".$_POST['transno'.$i]."',
             '".$loc."','".date('Y-m-d')."',1,'".$qty2."')";
             $res_insert2=DB_query($sql_stkmv2,$db);
             
             
             
}
 }      

    }
    
     echo"<table style='width:100%'><tr><td>";
 
// $_SESSION['UserStockLocation'];
     echo "<fieldset style='float:center;width:70%;'>";     
     echo "<legend><h3>Select Plants To Recieve</h3>";
     echo "</legend>";
     
          echo"<table style='border:1px solid #F0F0F0;width:80%'; >";   
     // echo "<fieldset style='float:center;width:97%;'>";     
   $loc=$_SESSION['UserStockLocation']; 
  //$loc=2; 
    $SQL="SELECT  bio_stocktransfer.serialno,stockmaster.description,locations.locationname,bio_stocktransfer.fromloc,stockmaster.stockid,bio_stocktransfer.id   
    FROM bio_stocktransfer,stockmaster,locations
    WHERE bio_stocktransfer.toloc='".$loc."'
    AND stockmaster.stockid=bio_stocktransfer.stockid
    AND locations.loccode=bio_stocktransfer.fromloc
    AND bio_stocktransfer.recieved=0 and type=10"; 
$Result=DB_query($SQL,$db);
    
echo"<tr>";
echo "<tr><th>Sl No.</th><th width=300px>Item</th><th>From</th><th>Plant Serial No.</th><th>Select</th></tr>";
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
    }
    echo "<td>".$i."</td>";
    echo "<td>".$Row['description']."</td>";
    echo "<td>".$Row['locationname']."</td>";
    echo "<td>".$Row['serialno']."</td>";
    echo "<td><input type=checkbox name='sel".$i."' ></tr>";
    
     echo "<input type=hidden name='stock".$i."' value=".$Row['stockid'].">";
    echo "<input type=hidden name='serial".$i."' value=".$Row['serialno'].">";
     echo "<input type=hidden name='fromloc".$i."' value=".$Row['fromloc'].">";
     echo "<input type=hidden name='transno".$i."' value=".$Row['id'].">";
    
    $i++;
}
echo "<input type=hidden name='totrow' value=".$i.">";

echo "</table>";
echo "<br><br><center><input type=submit name=sel_item value='Select Items To Recieve'></center>";

?>
