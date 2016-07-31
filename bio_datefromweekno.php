<?php
$PageSecurity=80;
include('includes/session.inc');
  //echo $_GET['weekno'].",".$_GET['year'];
  
    function getStartAndEndDate($week, $year)
{

    $time = strtotime("1 January $year", time());
    $day = date('w', $time);
    $time += ((7*$week)+1-$day)*24*3600;
    $return[0] = date('Y-m-d', $time);
    $time += 6*24*3600;
    $return[1] = date('Y-m-d', $time);
    return $return;
}
 if($_GET['weekno']!=""){
  /*$newdate=getStartAndEndDate($_GET['weekno']-1,$_GET['year']);
 // ConvertSQLDate($newdate[0]);
 echo'<td><input type=text value="'.ConvertSQLDate($newdate[0])."-".ConvertSQLDate($newdate[1]).'"></td>';  */
 
       
      $workcenter=$_GET['wc'];
     //echo "SELECT location from  workcentres WHERE code='".$workcenter."'";
      $Get_lc=DB_query("SELECT location from  workcentres WHERE code='".$workcenter."'",$db);
      $Result_lc=DB_fetch_array($Get_lc);
      $location=$Result_lc['location'];
 
  echo"<table style='border:1px solid #F0F0F0;width:98%'; >";   
echo"<tr>";
$formtodate=getStartAndEndDate($_GET['weekno']-1,$_GET['year']);
   $from=$formtodate[0]; 
  $to=$formtodate[1]; 
 /* if($_POST['fromto']){
      $fromto=$_POST['fromto'];
  }   else{   */
          $fromto=ConvertSQLDate($from)."-".ConvertSQLDate($to);
          $fromto1=ConvertSQLDate($from)." To ".ConvertSQLDate($to);
/*  }   */
  $Getloc=DB_query("SELECT locationname FROM locations WHERE loccode=".$location."",$db);
  $Loc=DB_fetch_array($Getloc);
  $LocName=$Loc['locationname'];
  
  $Getwc=DB_query("SELECT description FROM  workcentres WHERE code='".$workcenter."'",$db);
  $Wc=DB_fetch_array($Getwc);
  $WcName=$Wc['description'];
 /* <td><b>Location:</td><td><b>'.$LocName.'</b></td>*/
echo '<tr><td colspan=10><b><font size="4">Date:</font><font size="4" color="blue"><b> '.$fromto1.' </b></font><b><font size="4">Work Center:</font><b><font size="4" color="blue"> '.$WcName.'</b></font></td></tr>';
echo "<tr><th>Sl No.</th><th width=200px>Item</th><th>Pending SO</th><th>Pending WO</th><th width=50px>Mon</th><th>Tue</th><th>Wed</th><th>Thu</th><th>Fri</th><th>Sat</th></tr>";
 $sql="SELECT  stockmaster.stockid,stockmaster.description FROM stockmaster,bio_wo_stocklocation WHERE bio_wo_stocklocation.stockid=stockmaster.stockid
AND bio_wo_stocklocation.loccode=".$location."";
$result=DB_query($sql,$db);
$i=1;
$k=0;
$j=0;
while($myrow=DB_fetch_array($result))
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
    echo "<td>".$myrow['description']."</td>";
      $Get_SO=DB_query("SELECT SUM(salesorderdetails.quantity) FROM salesorderdetails,salesorders
WHERE salesorderdetails.orderno=salesorders.orderno
AND salesorders.orderno NOT IN (SELECT orderno FROM bio_installation_status)
AND salesorderdetails.stkcode='".$myrow['stockid']."'",$db);
$Res_SO=DB_fetch_array($Get_SO);
    echo "<td>".$Res_SO[0]."</td>";     // Pending SO
    $Get_PO=DB_query("SELECT SUM(woitems.qtyreqd-woitems.qtyrecd)  FROM woitems,workorders WHERE woitems.stockid='".$myrow['stockid']."' 
AND woitems.wo=workorders.wo
AND workorders.closed='0'",$db);
$Res_PO=DB_fetch_array($Get_PO);
    echo "<td>".$Res_PO[0]."</td>"; // Pending WO
        //Monday
         $Get_value=DB_query("SELECT quantity FROM mrpdemands WHERE location=".$location." AND workcenter='".$workcenter."' AND duedate='".$from."' AND stockid='".$myrow['stockid']."' ",$db);
          $Row_value=DB_fetch_array($Get_value);
    echo "<td><input type=text size='5' name=mon".$j." value=".$Row_value['quantity']."></td>";
                echo "<input type=hidden name=day1".$j."  value=".$from.">" ;
         //Tuesday
          $tue = strtotime($from."+ 1 days");
       
            
    $Get_value=DB_query("SELECT quantity FROM mrpdemands WHERE location=".$location." AND workcenter='".$workcenter."' AND duedate='".date('Y-m-d',$tue)."' AND stockid='".$myrow['stockid']."' ",$db);
     $Row_value=DB_fetch_array($Get_value);      
    echo "<td><input type=text size='5' name=tue".$j." value=".$Row_value['quantity']."></td>";
                echo "<input type=hidden name=day2".$j."  value=".date('Y-m-d',$tue).">" ;
            
            //Wednesday
            $wed = strtotime($from."+ 2 days"); 
            $Get_value=DB_query("SELECT quantity FROM mrpdemands WHERE location=".$location." AND workcenter='".$workcenter."' AND duedate='".date('Y-m-d',$wed)."' AND stockid='".$myrow['stockid']."'  ",$db);
          $Row_value=DB_fetch_array($Get_value);    
    echo "<td><input type=text size='5' name=wed".$j." value=".$Row_value['quantity']."></td>";
                echo "<input type=hidden name=day3".$j."  value=".date('Y-m-d',$wed).">" ;
     
      //Thersday
     $the = strtotime($from."+ 3 days"); 
     $Get_value=DB_query("SELECT quantity FROM mrpdemands WHERE location=".$location." AND workcenter='".$workcenter."' AND duedate='".date('Y-m-d',$the)."' AND stockid='".$myrow['stockid']."'   ",$db);
     $Row_value=DB_fetch_array($Get_value);  
    echo "<td><input type=text size='5' name=the".$j." value=".$Row_value['quantity']."></td>";
                echo "<input type=hidden name=day4".$j."  value=".date('Y-m-d',$the).">" ;
    
    //friday
    $fri = strtotime($from."+ 4 days");
    $Get_value=DB_query("SELECT quantity FROM mrpdemands WHERE location=".$location." AND workcenter='".$workcenter."' AND duedate='".date('Y-m-d',$fri)."' AND stockid='".$myrow['stockid']."'   ",$db);
    $Row_value=DB_fetch_array($Get_value);   
    echo "<td><input type=text size='5' name=fri".$j." value=".$Row_value['quantity']."></td>";
                echo "<input type=hidden name=day5".$j."  value=".date('Y-m-d',$fri).">" ;
    
    //Saturday
    $sat = strtotime($from."+ 5 days");
    $Get_value=DB_query("SELECT quantity FROM mrpdemands WHERE location=".$location." AND workcenter='".$workcenter."' AND duedate='".date('Y-m-d',$sat)."' AND stockid='".$myrow['stockid']."'   ",$db);
    $Row_value=DB_fetch_array($Get_value);   
    echo "<td><input type=text size='5' name=sat".$j." value=".$Row_value['quantity']."></td>";
                 echo "<input type=hidden name=day6".$j."  value=".date('Y-m-d',$sat).">" ;
                 echo "<input type=hidden name=stock".$j." value='".$myrow['stockid']."'>";
    $i++;
     $j++;
}

            echo "<input type=hidden name=totrows value=".$j.">";
                     
            
   echo '</table>';
     $sql_count="SELECT count(*) FROM  mrpdemands WHERE duedate='".$from."' AND location=".$location." AND    workcenter='".$workcenter."' ";
     $result_count=DB_query($sql_count,$db);
     $row_count=DB_fetch_array($result_count);
     if($row_count[0]==0){
               echo '<div class="centre"><br /><input type=submit name="submit" value="' . _('Submit') . '">';
     }
     else{
                    echo '<div class="centre"><br /><input type=submit name="update" value="' . _('Update') . '">';
                    echo '<input type=submit name="WO_WEEK" value="' . _('WO For Week') . '">';
     }
     
          /*echo '<input type=submit name="" value="' . _('Approve') . '">';*/
        echo '&nbsp&nbsp<input type=button class=button_details_show_aj name="" value="' . _('Report') . '">';
        /*echo '<input type=submit name="" value="' . _('View Pending Order') . '">';
        echo '<input type=submit name="" value="' . _('View Pending Work Order') . '">';
        echo '<input type=submit name="" value="' . _('View Plan of all center') . '">';*/
   
   
   echo "</div>"; 
   ?> <script>
 $("#selectiondetails_aj").hide(); 
 </script>
 <?php
       echo"<div id='selectiondetails_aj'>";

echo"<fieldset style='width:98%; overflow:auto;'>";
echo"<legend><h3>Reports</h3></legend>";
echo '<table width="100%">
    <tr>
        <th width="50%">' . _('Reports') . '</th>
        
   
    </tr>';
echo"<tr><td  VALIGN=TOP >";
echo '<a  class=button_details_show11 style=cursor:pointer; >' . _('View Pending Sale Order') . '</a><br>';
echo '<a class=button_details_show21 style=cursor:pointer; >' . _('View Plan of All Center('.$fromto.')') . '</a><br>';
echo '<a style=cursor:pointer; >' . _('View Material Stock') . '</a><br>';
echo '<a class=button_details_show41 style=cursor:pointer; style=cursor:pointer; >' . _('View MRP Shortage') . '</a><br>';
/*echo '<a style=cursor:pointer; onclick=showProdPeriod()>' . _('Cutomer Ledger') . '</a><br>';
echo '<a style=cursor:pointer; onclick=showProdPeriod()>' . _('Cash Book') . '</a><br>';*/


/*echo"</td><td  VALIGN=TOP >";
echo '<a href="bio_dprint_A5p.php" style=cursor:pointer;>' . _('Print Advance Reciept') . '</a><br>';
//echo '<a style=cursor:pointer; onclick=showProdPeriod()>' . _('Print Covering Letter') . '</a><br>';
//echo '<a style=cursor:pointer; onclick=addNewSeasonName()>' . _('Add New Season Name') . '</a><br>';
echo"</td></tr>";*/
echo'</table>';
echo"</fieldset>";

echo "</div>"; 

///// Pending SO RePORT
 echo"<div id='selectiondetails11'>";

echo"<fieldset style='width:98%; overflow:auto;'>";
echo"<legend><h3>Report :Pending Sale Order List</h3></legend>";
echo '<table width="100%">
<tr><td></td><td></td><td><a class=button_details_show11 style=cursor:pointer; >' . _('close') . '</a></td></tr>
    <tr>
        <th width="10%">' . _('Sl No.') . '</th>
        <th width="75%">' . _('Item') . '</th>
        <th width="15%">' . _('Quantity') . '</th>
   
    </tr>';
    
  $Sql_GetSO="SELECT bio_wo_stocklocation.stockid,SUM(quantity),stockmaster.description
FROM salesorderdetails,bio_wo_stocklocation,stockmaster,salesorders
WHERE bio_wo_stocklocation.stockid=salesorderdetails.stkcode   
AND stockmaster.stockid=bio_wo_stocklocation.stockid
AND salesorders.orderno=salesorderdetails.orderno
AND salesorders.orderno NOT IN (SELECT orderno FROM bio_installation_status)
GROUP BY bio_wo_stocklocation.stockid";
$Result_SO=DB_query($Sql_GetSO,$db);
$k=0;
$i=1;
while($Row_SO=DB_fetch_array($Result_SO))
{ if ($k==1)
    {
    echo '<tr class="EvenTableRows">';
    $k=0;
    }else 
    {
    echo '<tr class="OddTableRows">';
    $k=1;     
    }
    
    echo "<td>".$i."</td>";
    echo "<td>".$Row_SO['description']."</td>";
    echo "<td>".$Row_SO[1]."</td></tr>";
    $i++;
}

echo'</table>';
echo"</fieldset>";

echo "</div>";  


///// Weekly  plan

 echo"<div id='selectiondetails21'>";

echo"<fieldset style='width:98%; overflow:auto;'>";
echo"<legend><h3>Report :Plan For All Center (".$fromto.")</h3></legend>";
echo '<table width="100%">
     <tr><td></td><td></td><td><a class=button_details_show21 style=cursor:pointer; >' . _('close') . '</a></td></tr>
    <tr>
   
        <th width="10%">' . _('Sl No.') . '</th>
        <th width="75%">' . _('Item') . '</th>
        <th width="15%">' . _('Quantity') . '</th>
   
    </tr>';
    $sd=explode("-",$fromto);
    $a=$sd[0];
    $b=$sd[1];
    $Sql_GetSO="SELECT stockmaster.description,SUM(mrpdemands.quantity) AS qty 
FROM mrpdemands,stockmaster
WHERE mrpdemands.stockid=stockmaster.stockid
AND mrpdemands.duedate between '".FormatDateForSQL($a)."'  and '".FormatDateForSQL($b)."'
GROUP BY mrpdemands.stockid HAVING(qty>0)";
$Result_SO=DB_query($Sql_GetSO,$db);
$k=0;
$i=1;
while($Row_SO=DB_fetch_array($Result_SO))
{ if ($k==1)
    {
    echo '<tr class="EvenTableRows">';
    $k=0;
    }else 
    {
    echo '<tr class="OddTableRows">';
    $k=1;     
    }
    
    echo "<td>".$i."</td>";
    echo "<td>".$Row_SO['description']."</td>";
    echo "<td>".$Row_SO[1]."</td></tr>";
    $i++;
}

echo'</table>';
echo"</fieldset>";

echo "</div>"; 

echo"<div id='selectiondetails41'>";

echo"<fieldset style='width:98%; overflow:auto;'>";
echo"<legend><h3>Report :MRP Shortage (".$fromto.")</h3></legend>";
echo '<table width="100%">
     <tr><td></td><td></td><td></td><td></td><td><a class=button_details_show41 style=cursor:pointer; >' . _('close') . '</a></td></tr>
    <tr>
   
        <th >' . _('Sl No.') . '</th>
        <th >' . _('Item') . '</th>
        <th >' . _('Plan Quantity ') . '</th>
        <th >' . _('Stock Quty ') . '</th>
        <th >' . _('Stortage Quty ') . '</th>
   
    </tr>';
    $sd=explode("-",$fromto);
    $a=$sd[0];
    $b=$sd[1];
    $Sql_GetSO="select stockmaster.stockid,stockmaster.description,sum(mrpdemands.quantity) * sum(bom.quantity) as plan_quty
From mrpdemands
inner join bom on mrpdemands.stockid=bom.parent
inner join stockmaster on bom.component=stockmaster.stockid

where mrpdemands.duedate between '".FormatDateForSQL($a)."'  and '".FormatDateForSQL($b)."'
And mrpdemands.location='".$location."'
GROUP BY bom.component ";
$Result_SO=DB_query($Sql_GetSO,$db);
$k=0;
$i=1;
while($Row_SO=DB_fetch_array($Result_SO))
{ 
    $sqll="SELECT locstock.stockid,locstock.`quantity`  FROM `locstock` WHERE `stockid`='".$Row_SO['stockid']." '
            and locstock.loccode='".$location."'";
    $Result_Sin=DB_query($sqll,$db);
    $Row_lo=DB_fetch_array($Result_Sin);
$stortage=$Row_SO['plan_quty']-$Row_lo['quantity'];
if($stortage>0)
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
    echo "<td>".$Row_SO['description']."</td>";
    echo "<td><center>".number_format($Row_SO['plan_quty'],0)."</center></td>";
    echo "<td><center>".number_format($Row_lo['quantity'],0)."</td>";
    echo "<td><center>".number_format($stortage,0)."</td></tr>";
    $i++;
}
}

echo'</table>';
echo"</fieldset>";

echo "</div>"; 
 
  echo "<input type=hidden name=year id=year value=".date('Y').">";
   echo "<input type=hidden id=loc name=loc value=".$location.">";

 }
 
 if($_GET['loc']!="")
 {

   
      $Get_wc=DB_query("SELECT code FROM  workcentres WHERE location='".$_GET['loc']."' ",$db);
    $Get_wc1=DB_fetch_array($Get_wc);
    $workcenter=$Get_wc1['code'];
     if($workcenter==""){
         echo "<div class=warn>No workcenter for this location";
     }
      else{  echo' <select name="Workcenter" id="Workcenter" style="width:190px">';
   $sql="SELECT code,location,description,capacity FROM  workcentres
  ";

$rst=DB_query($sql,$db);

while($myrowc=DB_fetch_array($rst))
{
      if ($myrowc[code]==$workcenter)
        {  
    echo '<option selected value="';
        }
        else {
    echo '<option value="';
        } 
    echo $myrowc[code].'">'.$myrowc[description].'</option>';
 }
  echo '</select>';
      
      }     

 }
 
?>
