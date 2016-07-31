<?php
$PageSecurity=80;
$pagetype=1;
include('includes/session.inc');
 $title = _('WO From Demand'); 
 include('includes/header.inc'); 
 include('includes/sidemenu1.php');
 include('includes/SQL_CommonFunctions.inc');
  //echo $date1dd=date("Y");
 $date1=date("Y/m/d");
 //$date1 = '2013/12/29';
$weekday = date('l', strtotime($date1)); // note: first arg to date() is lower-case L
//echo $weekday."<br>"; // SHOULD display Wednesday

  
//$ddate = "2012-10-10";
function get_weekno($date1){
$duedt = explode("/", $date1);
$date2  = mktime(0, 0, 0, $duedt[1], $duedt[2], $duedt[0]);
$week  = (int)date('W', $date2);

 return     $week;
//echo "Weeknummer: " . $week;

}

 
    // echo date('Y/m/d',$sdate);
////////////////////////////////////
echo '<p class="page_title_text"><img src="'.$rootpath.'/css/'.$theme.'/images/maintenance.png" title="' . _('Search') .
    '" alt="" />' . ' ' . _('Work Orders') . '</p>';

   
    
    
    
    
echo '<form action="' . $_SERVER['PHP_SELF'] . '" method="post">';


/*echo '<table class="selection"><tr>'; */
 if(!isset($_POST['search']))
{
echo '<table class="selection"><tr>'; 

/* echo '<td>Location:</td><td><select name="loc" id="loc" style="width:190px" onchange="showitem(this.value)" onblur="showitem1(this.value)"  >';    
 $sql="SELECT loccode,locationname FROM  locations
  ";

$rst=DB_query($sql,$db);

while($myrowc=DB_fetch_array($rst))
{
      if ($myrowc[loccode]==$location)
        {  
    echo '<option selected value="';
        }
        else {
    echo '<option value="';
        } 
    echo $myrowc[loccode].'">'.$myrowc[locationname].'</option>';
 }
  echo '</select></td>';
 echo'</td></tr>'; */
 if($_POST['Workcenter'])
{
   $location=$_POST['loc']; 
    $workcenter=$_POST['Workcenter']; 
}else{
    $location=$_SESSION['UserStockLocation'];
    //  $location=7;
    $Get_wc=DB_query("SELECT code FROM  workcentres WHERE location=".$location." ",$db);
    $Get_wc1=DB_fetch_array($Get_wc);
    $workcenter=$Get_wc1['code'];
}

echo '<tr><td>Work Center:</td><td id=locat><select name="Workcenter" id="Workcenter" style="width:190px" tabindex=1 onchange="showdate(this.value)" onblur="showdate(this.value)">';
   $sql="SELECT code,location,description,capacity FROM  workcentres
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
      if ($myrowc[code]==$workcenter)
        {  
    echo '<option selected value="';
        }
        else {
    echo '<option value="';
        } 
    echo $myrowc[code].'">'.$myrowc[description].'</option>';
 }
  echo '</select></td>';
 echo'</td>'; 
 
   $sql="SELECT DISTINCT duedate FROM mrpdemands WHERE  workcenter='".$workcenter."' order by duedate"; 
 echo '<td>Date:</td><td id=dtshow><select name="date1" id="date1" style="width:190px" onclick="showitem()">';
  /*location='".$location."' AND*/
if($_POST['date1'])
{
   $date1=$_POST['date1']; 
}else{
    $date1=date('Y-m-d');
}         
$rst=DB_query($sql,$db);

while($myrowc=DB_fetch_array($rst))
{
      if ($myrowc[duedate]==$date1)
        {  
    echo '<option selected value="';
        }
        else {
    echo '<option value="';
        } 
    echo $myrowc[duedate].'">'.ConvertSQLDate($myrowc[duedate]).'-'.date('l', strtotime($myrowc[duedate])).'</option>';
 }
  echo '</select></td>';
 echo'</td></tr>'; 
 
$year = date('Y');
// Maximum Weeks of an year
$week_count = date('W', strtotime($year . '-12-31'));

if ($week_count == '01')
{   
    $week_count = date('W', strtotime($year . '-12-24'));
}
echo"</table>"; 
  
    echo '<div class="centre"><br /><input type=submit name="search" value="' . _('View Demand For This Day') . '">';
}  

if(isset($_POST['submit']))
{
    $WOno=$_POST['WONo'];
    $totrows=$_POST['rowcount'];
    $LocCode=$_POST['loc'];
    for($i=0;$i<$totrows;$i++)
    {    
        if($_POST['Qty'.$i]>0)
        {
               $_POST['stock'.$i];
    
    //$_POST['WO'] = GetNextTransNo(40,$db);
    $sql = "INSERT INTO workorders (wo,
                             loccode,
                             requiredby,
                             startdate)
             VALUES ('" . $WOno . "',
                    '" . $LocCode . "',
                    '" . $_POST['wo_date'] . "',
                    '" . $_POST['wo_date'] . "')";
    $InsWOResult = DB_query($sql,$db); 
    // insert parent item info
        $sql1 = "INSERT INTO woitems (wo,
                                 stockid,
                                 qtyreqd
                                 )
             VALUES ( '" . $WOno . "',
                        '" . $_POST['stock'.$i] . "',
                        '" . $_POST['Qty'.$i] . "'
                        )"; 
                        
        $result = DB_query($sql1,$db,$ErrMsg);

        //Recursively insert real component requirements - see includes/SQL_CommonFunctions.in for function WoRealRequirements
        WoRealRequirements($db, $WOno, $LocCode, $_POST['stock'.$i]);
        $Sql_Getcomp="SELECT component,quantity FROM bom,stockmaster WHERE parent='".$_POST['stock'.$i]."' AND stockmaster.stockid=bom.component AND stockmaster.mbflag='M'
        AND bom.component!='".$_POST['stock'.$i]."'";
        $res_comp=DB_query($Sql_Getcomp,$db);
        if(DB_num_rows($res_comp)>0){
          while($row_comp=DB_fetch_array($res_comp))
        {
            $comp_qty=$_POST['Qty'.$i]*$row_comp['quantity'];
            $sql_comp = "INSERT INTO woitems (wo,
                                 stockid,
                                 qtyreqd
                                 )
             VALUES ( '" . $WOno . "',
                        '" . $row_comp['component'] . "',
                        '" . $comp_qty . "'
                        )"; 
                        
        $result = DB_query($sql_comp,$db,$ErrMsg);
        WoRealRequirements($db, $WOno, $LocCode, $row_comp['component'] );
            
        }  
        }
        $WOno=$WOno+1;
        }
       
        
}      $no=$WOno-$_POST['WONo'];
    echo "<div class=success>".$no." Work order created for ".ConvertSQLDate($_POST['wo_date'])."</div>";
    }
 


if(isset($_POST['search']))
{
  
    $workcenter=$_POST['Workcenter'];
    $Get_WC=DB_query("SELECT description FROM workcentres WHERE code='".$workcenter."'",$db);
    $Res_WC=DB_fetch_array($Get_WC);
    $WC_name=$Res_WC['description'];
    echo '<table class="selection"><tr>'; 
    echo "<td>Work Center:</td><td><input type=text readonly value=".$WC_name."></td><td>Date:</td><td><input type=text readonly value=".ConvertSQLDate($_POST['date1']).'-'.date('l', strtotime($_POST['date1']))."></td>";
    echo"</table>";
    echo "<input type=hidden name=wo_date id=wo_date value=".$_POST['date1'].">";
    
     $Get_lc=DB_query("SELECT location from  workcentres WHERE code='".$workcenter."'",$db);
      $Result_lc=DB_fetch_array($Get_lc);
      $location=$Result_lc['location'];
        //Check IF any WO For this day
    $sql_wo1=DB_query("SELECT count(*) from workorders where requiredby='".$_POST['date1']."' AND loccode=".$location."",$db);
    $Res_wo1=DB_fetch_array($sql_wo1);
    if($Res_wo1[0]>0)
    {
        $dt=explode("-",$_POST[date1]);
        echo '<br><div class=warn>Already some Work Orders for '.ConvertSQLDate($_POST[date1]).'. <a onclick="selectplant('.$dt[0].','.$dt[1].','.$dt[2].','.$location.')">Click Here</a> for the details.</div>';
    }
      
      echo "<input type=hidden name=loc id=loc value=".$location.">";
    
    echo '<br><a href onclick="bio_WOFromDemand.php"><b><font size=3>Select another Work Center or Date</b></font></a><br>';
    
    echo "<br>";
    
  $SQL="select mrpdemands.stockid,mrpdemands.quantity,stockmaster.description,
  bio_wo_stocklocation.capacity,bio_wo_stocklocation.cmcapacity 
  
  FROM  mrpdemands,stockmaster,bio_wo_stocklocation
   WHERE mrpdemands.duedate='".$_POST[date1]."' AND mrpdemands.location='".$location."' AND mrpdemands.workcenter='".$workcenter."' AND quantity>0 
 AND stockmaster.stockid=mrpdemands.stockid
 AND bio_wo_stocklocation.stockid=mrpdemands.stockid
 AND bio_wo_stocklocation.loccode=mrpdemands.location";
$Result=DB_query($SQL,$db);
if(DB_num_rows($Result)>0)
{
    

echo"<table style='border:1px solid #F0F0F0;width:70%'; >";   
echo"<tr>";
echo "<tr><th>Sl No.</th><th width=185px>Item</th><th>Pending SO</th><th>Todays Plan(All WC)</th><th>Pending WO</th><th>Capacity</th><th>Qty. For WO</th></tr>";


$k=0;
$i=1;
$j=0;
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
    $Get_SO=DB_query("SELECT SUM(salesorderdetails.quantity) FROM salesorderdetails,salesorders
WHERE salesorderdetails.orderno=salesorders.orderno
AND salesorders.orderno NOT IN (SELECT orderno FROM bio_installation_status)
AND salesorderdetails.stkcode='".$Row['stockid']."'",$db);
$Res_SO=DB_fetch_array($Get_SO);
    echo "<td>".$Res_SO[0]."</td>";
    $Get_WO=DB_query("SELECT SUM(quantity) FROM mrpdemands 
WHERE stockid='".$Row['stockid']."' AND duedate='".$_POST['date1']."'",$db);
$Res_WO=DB_fetch_array($Get_WO);
    echo "<td>".$Res_WO[0]."</td>";
    $Get_PO=DB_query("SELECT SUM(woitems.qtyreqd-woitems.qtyrecd)  FROM woitems,workorders WHERE woitems.stockid='".$Row['stockid']."' 
AND woitems.wo=workorders.wo
AND workorders.closed='0'",$db);
$Res_PO=DB_fetch_array($Get_PO);
    
  
    echo "<td>".$Res_PO[0]."</td>";
    if($Row['capacity']!=0)
    {
        $qty=$Row['capacity'];
        $unit="Nos.";
        echo "<input type=hidden name=capa_type".$j." id=capa_type".$j." value=1 >";
        echo "<input type=hidden name=capacity".$j." id=capacity".$j." value=".$qty." >";
    }else{
        $qty=$Row['cmcapacity'];
        $unit="Cub. Mtr.";
        

        
        // get plant capacity
        
        
        echo "<input type=hidden name=capa_type".$J." id=capa_type".$j." value=2 >";
        echo "<input type=hidden name=capacity".$j." id=capacity".$j." value=".$qty." >";
        
        
    }
    
    $Plant_cap=DB_query("SELECT value FROM  stockitemproperties WHERE stockid='".$Row['stockid']."'
        AND stkcatpropid=48",$db);
        $Pcap=DB_fetch_array($Plant_cap,$db);
    
    echo "<input type=hidden name=defined_cap".$j." id=defined_cap".$j." value=".$Pcap[0]." >";
   
   
    echo "<td>".$qty." ".$unit."</td>";
    echo "<td><input type=text name='Qty".$j."' id='Qty".$j."' value=".$Row['quantity']."></td></tr>";
    echo "<input type=hidden name='stock".$j."' value=".$Row['stockid'].">";
    $i++;
    $j++;
}
echo "</table>";
echo "<input type=hidden name=rowcount id=rowcount value=".$j.">";

    echo '<div class="centre"><br /><input type=submit name="submit" onclicK="if(validation()==1){return false;}" value="' . _('ADD WO') . '">';
        
     echo '&nbsp&nbsp<input type=button class=button_details_show name="" value="' . _('Report') . '">';
        /*echo '<input type=button name="" value="' . _('View Pending Order') . '">';
        echo '<input type=button name="" value="' . _('View Plan of all center') . '">';
        echo '<input type=button name="" value="' . _('View Material Stock') . '">';<input type=button name="" value="' . _('View MRP Shortage') . '">*/
        echo '</div>';

        }
        else{
            echo "<div class=warn>No Production Plan for ".ConvertSQLDate($_POST['date1'])."</div>";
        }
 echo"<div id='selectiondetails'>";

echo"<fieldset style='width:70%; overflow:auto;'>";
echo"<legend><h3>Reports</h3></legend>";
echo '<table width="100%">
    <tr>
        <th width="50%">' . _('Reports') . '</th>
        
   
    </tr>';
echo"<tr><td  VALIGN=TOP >";
echo '<a  class=button_details_show1 style=cursor:pointer; >' . _('View Pending Sale Order') . '</a><br>';
echo '<a class=button_details_show2 style=cursor:pointer; >' . _('View Plan of All Center('.ConvertSQLDate($_POST['date1']).')') . '</a><br>';
echo '<a style=cursor:pointer; >' . _('View Material Stock') . '</a><br>';
echo '<a  style=cursor:pointer; >' . _('View MRP Shortage') . '</a><br>';
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

 echo"<div id='selectiondetails1'>";

echo"<fieldset style='width:70%; overflow:auto;'>";
echo"<legend><h3>Report :Pending Sale Order List</h3></legend>";
echo '<table width="100%">
<tr><td></td><td></td><td><a class=button_details_show1 style=cursor:pointer; >' . _('close') . '</a></td></tr>
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



///// Todays plan

 echo"<div id='selectiondetails2'>";

echo"<fieldset style='width:70%; overflow:auto;'>";
echo"<legend><h3>Report :Plan For All Center (".ConvertSQLDate($_POST['date1']).")</h3></legend>";
echo '<table width="100%">
     <tr><td></td><td></td><td><a class=button_details_show2 style=cursor:pointer; >' . _('close') . '</a></td></tr>
    <tr>
   
        <th width="10%">' . _('Sl No.') . '</th>
        <th width="75%">' . _('Item') . '</th>
        <th width="15%">' . _('Quantity') . '</th>
   
    </tr>';
    $Sql_GetSO="SELECT stockmaster.description,SUM(mrpdemands.quantity) AS qty 
FROM mrpdemands,stockmaster
WHERE mrpdemands.stockid=stockmaster.stockid
AND mrpdemands.duedate='".$_POST['date1']."'
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



  

} // include('includes/footer.inc');
 
   
   //    GET Last Workorder
   
   $Get_maxwo=DB_query("SELECT MAX(wo) FROM workorders",$db);
   $Row_maxwo=DB_fetch_array($Get_maxwo);
   $NewWO=$Row_maxwo[0]+1;
   echo "<input type='hidden' name='WONo' value=".$NewWO.">";
       
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


  


?>
<script>

function selectplant(str1,str2,str3,str4){ //alert(str1);
controlWindow=window.open("bio_view_wo.php?y=" +str1 + "&m="+str2+ "&d="+str3 + "&loc=" +str4,"selplant","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=1000,height=600");
}


$("#selectiondetails").hide(); 
$("#selectiondetails1").hide(); 
$("#selectiondetails2").hide();
$('.button_details_show').click(function() {
  $('#selectiondetails').slideToggle('slow', function() {
    // Animation complete.
  });
});
$('.button_details_show1').click(function() {
  $('#selectiondetails1').slideToggle('slow', function() {
    // Animation complete.
  });
});
$('.button_details_show2').click(function() {
  $('#selectiondetails2').slideToggle('slow', function() {
    // Animation complete.
  });
});
function showdate(str)
{   //alert(str);
  //var str1=document.getElementById("year").value;  //  alert(str1);
        //var str2=document.getElementById("Workcenter").value;
       // var str3=document.getElementById("loc").value;
    //alert(str);
      if (str=="")
  {
  document.getElementById("dtshow").innerHTML="";
  return;
  }
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("dtshow").innerHTML=xmlhttp.responseText;

    }
  } 
xmlhttp.open("GET","bio_datefromweekno1.php?WC1=" + str,true);
xmlhttp.send();   
}
/*function showdate1(str3)  
{   
var str=document.getElementById("week").value; 
  var str1=document.getElementById("year").value;  //  alert(str1);
        var str2=document.getElementById("Workcenter").value;
        //var str3=document.getElementById("loc").value;
    //alert(str);
      if (str=="")
  {
  document.getElementById("itemgrid").innerHTML="";
  return;
  }
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("itemgrid").innerHTML=xmlhttp.responseText;

    }
  } 
xmlhttp.open("GET","bio_datefromweekno.php?weekno=" + str + "&year=" +str1 + "&lc=" + str3 + "&wc=" + str2,true);
xmlhttp.send();   
}*/



function showitem()
{   //alert(str);
  //var str1=document.getElementById("year").value;  //  alert(str1);
        var str=document.getElementById("Workcenter").value;
       // var str3=document.getElementById("loc").value;
    //alert(str);
      if (str=="")
  {
  document.getElementById("dtshow").innerHTML="";
  return;
  }
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("dtshow").innerHTML=xmlhttp.responseText;

    }
  } 
xmlhttp.open("GET","bio_datefromweekno1.php?WC1=" + str,true);
xmlhttp.send(); 
}

function validation()
{ 
    var num = document.getElementById("rowcount").value;
    //alert(num);
    for(var i=0; i<num; i++)
    {
         var type = document.getElementById("capa_type" +i).value; 
         var qty= document.getElementById("Qty" +i).value;
         var qty_planned= document.getElementById("capacity" +i).value;
         if(type==1)
         {//alert(qty_planned);//alert(qty);
         
         var diff = qty_planned-qty;
       //  alert(diff);
         if(diff<0)
         {alert("Quantity exceed capacity");
              document.getElementById("Qty" +i).focus(); 
                var f=1;
                 return f;
             
         }
             
         }
           else  if(type==2)
         {
             var qty_defined= document.getElementById("defined_cap" +i).value;  
             var totcum=qty_defined * qty; //alert(qty_defined);
             var diff = qty_planned- totcum;//alert(qty);
            // alert(diff);
         if(diff<0)
         {alert("Quantity exceed capacity");
              document.getElementById("Qty" +i).focus(); 
                var f=1;
                 return f;
             
         }
             /*else 
             {
                 alert("OK");
             }*/
             
         }
        
        
    }
    
}

</script>