<?php
  $PageSecurity=80;
  $pagetype=1;
include('includes/session.inc');
 $title = _('Stock Transfer'); 
 include('includes/header.inc');
  include('includes/sidemenu1.php');
 //include('includes/SQL_CommonFunctions.inc');
 
 ////////////////////////////////////
echo '<p class="page_title_text"><img src="'.$rootpath.'/css/'.$theme.'/images/maintenance.png" title="' . _('Search') .
    '" alt="" />' . ' ' . _('Stock Transfer') . '</p>';
    
    echo '<form action="' . $_SERVER['PHP_SELF'] . '" method="post">';
    
      if($_POST['loc1'])
{
   $floc=$_POST['loc1'];
   $tloc=$_POST['loc2'];
}else{
     $floc=$_SESSION['UserStockLocation'];
    $tloc=$_SESSION['UserStockLocation'];
}  
 
      echo"<table class=selection style='border:1px solid #F0F0F0;width:70%'; >";   
echo"<tr>";


echo '<tr><td>Transfer From:</td><td id=locat><select name="loc1" id="loc1" style="width:190px" tabindex=1 onchange="showdate(this.value)" onblur="showdate(this.value)">';
   $sql="SELECT loccode,locationname FROM  locations
  ";

$rst=DB_query($sql,$db);

while($myrowc=DB_fetch_array($rst))
{
      if ($myrowc[loccode]==$floc)
        {  
    echo '<option selected value="';
        }
        else {
    echo '<option value="';
        } 
    echo $myrowc[loccode].'">'.$myrowc[locationname].'</option>';
 }
  echo '</select></td>';
 echo'</td>'; 
 echo '<td>Transfer To:</td><td id=locat><select name="loc2" id="loc2" style="width:190px" tabindex=1 onchange="showdate(this.value)" onblur="showdate(this.value)">';
   $sql="SELECT loccode,locationname FROM  locations
  ";
/*if($_POST['Workcenter'])
{
   $workcenter=$_POST['Workcenter']; 
}else{
    $workcenter=$_SESSION['UserStockLocation'];
}  */

$rst=DB_query($sql,$db);

while($myrowc=DB_fetch_array($rst))
{
      if ($myrowc[loccode]==$tloc)
        {  
    echo '<option selected value="';
        }
        else {
    echo '<option value="';
        } 
    echo $myrowc[loccode].'">'.$myrowc[locationname].'</option>';
 }
  echo '</select></td>';
 echo'</td>'; 
 /*echo "<td>Type :</td><td>";
 echo "<select name=type>";
 echo "<option value=1>plant</option>";
  echo "<option value=2>Purchased Items</option>";
  echo "<select>";*/
 echo "<td><input type=submit name=select value=select></td>";
 
    
    if($_POST['sel_item'])
    {
       /*$sql_temp= "CREATE TEMPORARY TABLE IF NOT EXISTS `temp_trans_table` (
         
          `fromloc` int(11),
          `toloc` int(11),
          `stockid` varchar(30) )
         ";
         $result=DB_query($sql_temp,$db);*/
         for($i=0;$i<$_POST['totrow'];$i++)
         {
           // echo $_POST['sel'.$i];
         if($_POST['sel'.$i]=='on')
 {
  $sql_insert="INSERT INTO  bio_stocktransfer(stockid,fromloc,toloc,qty,serialno) values ('".$_POST['stock'.$i]."',".$_POST['loc1'].",
             ".$_POST['loc2'].",1,'".$_POST['serial'.$i]."')";
             $res_insert=DB_query($sql_insert,$db);
}
 }      

    }
    
 
 
  if($_POST['loc1']!=""  AND $_POST['loc2'])  
 { 
     if($_POST['loc1']==$_POST['loc2']){
     echo "<div class=warn>Choose defferent locations to transfer</div>";
     
 }else{
     
    /* if($_POST['type']==1)
     {*///echo $tloc=$_POST['loc2'];
        $SQL="SELECT
           `stockmaster`.`stockid`,
    `stockmaster`.`description`
    , `locstock`.`quantity`,
     stockserialitems.serialno
FROM
    locations
    INNER JOIN `locstock` 
        ON (`locations`.`loccode` = `locstock`.`loccode`)
    INNER JOIN `stockmaster` 
        ON (`locstock`.`stockid` = `stockmaster`.`stockid`)
        INNER JOIN stockserialitems 
        ON (`locstock`.`stockid`=stockserialitems.stockid)
        WHERE stockserialitems.loccode=".$_POST['loc1']."
        AND locstock.stockid IN (SELECT stockid FROM stockmaster WHERE categoryid IN (SELECT subcatid FROM bio_maincat_subcat WHERE maincatid='1')) 
        AND locstock.quantity>0
       AND stockserialitems.serialno not in (select serialno from bio_stocktransfer)
        GROUP BY  stockserialitems.serialno"; 
     //echo "<table style='border:1px solid #F0F0F0;width:800px'>";
     $Result=DB_query($SQL,$db);
     
     echo"<table style='width:100%'><tr><td>";
 
// $_SESSION['UserStockLocation'];
     echo "<fieldset style='float:center;width:70%;'>";     
     echo "<legend><h3>Select Plants To Transfer</h3>";
     echo "</legend>";
     echo"<table style='border:1px solid #F0F0F0;width:70%'; >";   
     // echo "<fieldset style='float:center;width:97%;'>";     
     
     

    
echo"<tr>";
echo "<tr><th>Sl No.</th><th width=300px>Item</th><th>Plant Serial No.</th><th>Select</th></tr>";
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
    /*echo "<td>".$Row['quantity']."</td>";*/
    echo "<td>".$Row['serialno']."</td>";
    echo "<td><input type=checkbox name='sel".$i."' ></tr>";
    echo "<input type=hidden name='stock".$i."' value=".$Row['stockid'].">";
    echo "<input type=hidden name='serial".$i."' value=".$Row['serialno'].">";
     
     
$i++;

}
   echo "<input type=hidden name='totrow' value=".$i.">";
/*echo "<tr align=center><input type=submit name=sel_item value='Select Items'></tr>";*/
echo "</table>";
echo "<br>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
<input type=submit name=sel_item value='Select Items To Transfer'><br>";
     
 }
    
    
  echo "</fieldset>";  
  echo "</table>";
    $SQL1="select sum(bio_stocktransfer.qty),stockmaster.description FROM  bio_stocktransfer,stockmaster
 where bio_stocktransfer.fromloc='".$_POST['loc1']."' AND bio_stocktransfer.toloc='".$_POST['loc2']."' 
AND stockmaster.stockid=bio_stocktransfer.stockid
 AND bio_stocktransfer.recieved=0
group by bio_stocktransfer.stockid"; 
$Result1=DB_query($SQL1,$db);

echo "<fieldset style='float:center;width:70%;'>";     
     echo "<legend><h3>Pending Transfer</h3>";
     echo "</legend>";
     echo"<table style='border:1px solid #F0F0F0;width:70%'; >"; 
    echo"<table style='border:1px solid #F0F0F0;width:70%'; >";   
     // echo "<fieldset style='float:center;width:97%;'>";     
     
     

    
echo"<tr>";
echo "<tr><th>Sl No.</th><th width=300px>Item</th><th>Quantity</th></tr>";
$k=0;
$i=1;
while($Row1=DB_fetch_array($Result1))
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
    echo "<td>".$Row1['description']."</td>";
    echo "<td>".$Row1[0]."</td>";
    $i++;
}
 }
 
?>
