<?php
  $PageSecurity=80;
/* $Id: WorkOrderEntry.php 4557 2011-04-28 10:39:25Z daintree $*/
$pagetype=1;
include('includes/session.inc');
$title = _('Credit Work Order');
include('includes/header.inc');
include('includes/sidemenu1.php');
include('includes/SQL_CommonFunctions.inc');
include('bio_workordercredit_include.php');
 
if(isset($_POST['crediting']))
{
  //echo "testing "; 
  $select_rows=$_POST[rows];
  if($_POST['type']==2) //Sub Assemblies crediting
  {   
      for($i=0;$i<$select_rows;$i++)
      {  
  if(is_numeric($_POST['cr_qut'.$i]))
  { 
     recieve_sub($db,$_POST['stockcode'.$i],$_POST['wo_no'.$i],$_POST['cr_qut'.$i],$_POST[StockLocation],1);
     
  }
      }      prnMsg(_('Sub Assemblies are credited '),'info');
  } 
  else if($_POST['type']==1)     //plant crediting
  {
      for($i=0;$i<$select_rows;$i++)
       {
if(is_numeric($_POST['cr_qut'.$i]))
  {  //select plant 
$sql_plant="SELECT qtyrecd,qtyreqd FROM woitems WHERE wo='".$_POST['wo_no'.$i]."' AND stockid='".$_POST['stockcode'.$i]."' ";
$select_ro=DB_query($sql_plant,$db);
while($row_1=DB_fetch_array($select_ro))
{ 
      $plant_rc_tab= $row_1['qtyrecd'];
      $plant_rq_tab= $row_1['qtyreqd'];
}    
     //select sub ass
$sql_getwoitems="SELECT stockid,qtyreqd,qtyrecd FROM woitems WHERE wo='".$_POST['wo_no'.$i]."' AND stockid!='".$_POST['stockcode'.$i]."' ";
$result_getwoitems=DB_query($sql_getwoitems,$db);
while($row_getwo=DB_fetch_array($result_getwoitems))
{    $plant_rc_tab ;
   $plant_rq_tab;
  $enter_credit= $_POST['cr_qut'.$i]; 
    $plant_cre_no=$plant_rc_tab+ $enter_credit;
    $pr=$plant_cre_no*($row_getwo[qtyreqd]/$plant_rq_tab) ;
    $sr=$row_getwo[qtyrecd];
         if($pr>=$sr)
          {
          $con=$pr-$sr;   
          }
recieve_sub($db,$row_getwo['stockid'],$_POST['wo_no'.$i],$con,$_POST[StockLocation],1);
}
 recieve_sub($db,$_POST['stockcode'.$i],$_POST['wo_no'.$i],$_POST['cr_qut'.$i],$_POST[StockLocation],2);

} 
  }  prnMsg(_('Plant and Sub Assemblies are credited '),'success');
} 
  

}
echo '<p class="page_title_text"><img src="'.$rootpath.'/css/'.$theme.'/images/group_add.png" title="' .
    _('Search') . '" alt="" />' . ' ' . $title.'</p>';
echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '" name="search">';
echo '<table class=selection>';


echo'<tr><td>Work Center</td>';

echo "<td><select name='StockLocation' id='StockLocation' onchange='showitem()'  > ";

        $sql = 'SELECT loccode, locationname FROM locations where managed=1';

        $resultStkLocs = DB_query($sql,$db);

        while ($myrow=DB_fetch_array($resultStkLocs))
        {
            if ($myrow['loccode']==$_SESSION['UserStockLocation']|| $myrow['loccode']==$_POST['StockLocation']){
                 echo '<option selected value="' . $myrow['loccode'] . '">' . $myrow['locationname'] . '</option>';
            } else {
                 echo '<option Value="' . $myrow['loccode'] . '">' . $myrow['locationname'] . '</option>';
            }
        }

        echo '</select></td>';
        
echo'   <td>Item Crediting</td>';
        echo "<td><select name='type' id='type' onchange='showitem(this.value)' > ";
        if ($_POST['type']==2 )
        {
           echo '<option selected value=2>Sub Assemblies</option>'; 
           echo '<option Value=1>Plant </option>';
        }
        else {echo '<option selected value=1>Plant</option>';
        echo '<option Value=2>Sub Assemblies </option>';}
echo '</select></td>';


echo'<td>Work Order Date</td>';

echo "<td id='order_date'>";
echo"<select name='orderno'> ";
 $sql = 'SELECT workorders.wo,startdate
FROM workorders
INNER JOIN woitems ON workorders.wo=woitems.wo
INNER JOIN stockmaster ON woitems.stockid=stockmaster.stockid
WHERE workorders.closed="0"
AND (woitems.qtyreqd-woitems.qtyrecd !=0)';

if($_POST['type']==1)
{
  $sql .='AND workorders.loccode="'.$_POST[StockLocation].'"
GROUP BY workorders.startdate';  
}
else if($_POST['type']==2)
{
 $sql .='AND workorders.loccode="'.$_POST[StockLocation].'"
GROUP BY workorders.wo'; 
}
else{
$sql .='AND workorders.loccode=7
GROUP BY workorders.startdate';   
}

        $resultStkLocs = DB_query($sql,$db);

        while ($myrow=DB_fetch_array($resultStkLocs))
        {
                
if ($_POST['orderno']==$myrow['wo'] )
{echo '<option selected value="';}
else
{echo '<option value="';}     
echo $myrow['wo'] . '">'.ConvertSQLDate($myrow['startdate']).'-'.date('l', strtotime($myrow['startdate'])).'('.$myrow['wo'].')</option>'; 
        }

        echo '</select></td></tr>';


echo'</table>';

echo'<br><center><input type=submit name=submit value="select work order"></center></br>';

if (isset($_POST['submit']) || isset($_POST['crediting']))
{
   if ($_POST['type']==1)
{

 $sql_select="SELECT workorders.wo,
                    woitems.stockid,
                    stockmaster.description,
                    woitems.qtyreqd,
                    woitems.qtyrecd,
                    workorders.requiredby
                    FROM workorders
                    INNER JOIN woitems ON workorders.wo=woitems.wo
                    INNER JOIN stockmaster ON woitems.stockid=stockmaster.stockid
                    WHERE workorders.closed='0'
                    AND (woitems.qtyreqd-woitems.qtyrecd !=0)
                    AND workorders.loccode='" . $_POST['StockLocation'] . "'
                    AND workorders.wo 
                        IN (SELECT `wo` FROM `workorders` 
                                WHERE `startdate` = (SELECT `startdate` FROM `workorders` 
                                                        WHERE `wo`='" . $_POST['orderno'] . "'))
                    AND  woitems.stockid 
                         IN(SELECT stockid FROM stockmaster WHERE categoryid
                                 IN (SELECT subcatid FROM bio_maincat_subcat WHERE maincatid =1)) ";
}
else
{
   $sql_select="SELECT workorders.wo,
                    woitems.stockid,
                    stockmaster.description,
                    woitems.qtyreqd,
                    woitems.qtyrecd,
                    workorders.requiredby
                    FROM workorders
                    INNER JOIN woitems ON workorders.wo=woitems.wo
                    INNER JOIN stockmaster ON woitems.stockid=stockmaster.stockid
                    WHERE workorders.closed='0'
                    AND (woitems.qtyreqd-woitems.qtyrecd !=0)
                    AND workorders.loccode='" . $_POST['StockLocation'] . "'
                    AND workorders.wo='" . $_POST['orderno'] . "'
                    AND  woitems.stockid 
                        NOT IN(SELECT stockid FROM stockmaster WHERE categoryid
                                 IN (SELECT subcatid FROM bio_maincat_subcat WHERE maincatid =1)) 
               "; 
}
echo'<center><div id="mag"></div></center>';
echo '<table id="credit_show" border="0" ><tr >';
echo'<th>Slno</th>';
if ($_POST['type']==1)
{ echo'<th>Plant</th>';}else if($_POST['type']==2){echo'<th>Sub Assemblies</th>';}
echo'<th>Pending Work Qty</th><th>Crediting Work Qty</th><td bgcolor="white" border="0"</td></tr>';
 $result_order = DB_query($sql_select,$db);
 $k=1;
$s=0;
$slno=1;
        while ($myrow1=DB_fetch_array($result_order))
        {
             if ($k==1){
        echo '<tr class="EvenTableRows">';
        $k=0;
    } else {
        echo '<tr class="OddTableRows">';
        $k=1;
    } 
           $pwq= $myrow1[qtyreqd]-$myrow1[qtyrecd];
           echo'<td><center>'.$slno.'</center></td>';
            echo'<td>'.$myrow1[stockid].' '.$myrow1[description].'</td>';
           echo'<td><center>'.$pwq.'</center></td>';
             echo'<td><input type="text" name="cr_qut'.$s.'" id="'.$s.'"  value=""   onchange="checkval(this.name,this.id,this.value)" onkeypress="return (event.charCode >= 48 &&event.charCode <= 57 )||(event.charCode <=10 )" ></td>'; //        onkeyup="checkval(this.value,this.id)"                          onchange="checkval(this.value,this.id)"
              echo'<td id="err'.$s.'" bgcolor="white" border="0"></td>';
            echo'</tr>';
            echo'<input type="hidden" name="pwq'.$s.'" id="pwq'.$s.'"  value="'.$pwq.'">';
             echo'<input type="hidden" name="wo_no'.$s.'" id="wo_no'.$s.'" value="'.$myrow1[wo].'">';
             echo'<input type="hidden" name="stockcode'.$s.'" id="stockcode'.$s.'" value="'.$myrow1[stockid].'">';
            $s++;  $slno++;
            
        }
          echo'<tr><td><input type="hidden" name="rows" id="rows" value='.$s.' ></td></tr>';
          echo'<input type="hidden" name="sss" id="sss"  value=0>';
          echo'<input type="hidden" name="aaa" id="aaa"  value=0>';
echo'</table>';                                                               // onclick="if(log_in()==1)return false;" 
echo'<br><center><input type="submit" name="crediting" value="Credit" onclick="if(log_in()==1)return false;" >
                <input id="shwprint" type="button" name="shwprint" value="Report" >
        </center></br>';

}
 

echo'</form>';
 echo"<div id='printgrid' style='margin:auto;'>";
/*  echo'<center><input id="Re_view1" type="button" name="Re_view1" value="Pending Work Orders" >
                <input id="Re_view2" type="button" name="Re_view2" value="Pending Sales Orders" >
                <input id="Re_view3" type="button" name="Re_view3" value="Plant Stock" >
                <input id="Re_view4" type="button" name="Re_view4" value="Sub Assemblies Stock" >
                </center></div>'; */
 echo'<a id="Re_view1"  name="Re_view1" >Pending Work Orders</a><br>';
   echo"<div id='views1' style='margin:auto;'>";
//  echo'<center><b>Pending Work Orders</b></center>';
   echo "<fieldset >";     
     echo "<legend><h3>Pending Work Orders</h3>";
     echo "</legend>";
  echo'<table width="100%" ><tr><th>Slno</th><th>Plant</th><th>Pending Quty</th></tr>';
  $sql_select="SELECT workorders.wo,
                    woitems.stockid,
                    stockmaster.description,
                     sum(woitems.qtyreqd) as qq,
                     sum(woitems.qtyrecd) as qc
                    FROM workorders
                    INNER JOIN woitems ON workorders.wo=woitems.wo
                    INNER JOIN stockmaster ON woitems.stockid=stockmaster.stockid
                    WHERE workorders.closed='0'
                    AND (woitems.qtyreqd-woitems.qtyrecd !=0)
                    AND workorders.loccode='" . $_POST['StockLocation'] . "'
                    AND  woitems.stockid 
                         IN(SELECT stockid FROM stockmaster WHERE categoryid
                                 IN (SELECT subcatid FROM bio_maincat_subcat WHERE maincatid =1)) 
                    Group BY woitems.stockid
";
$result_wo = DB_query($sql_select,$db);
 $k=1;

$slno=1;
        while ($myrow1=DB_fetch_array($result_wo))
        {
             if ($k==1){
        echo '<tr class="EvenTableRows">';
        $k=0;
    } else {
        echo '<tr class="OddTableRows">';
        $k=1;
    } 
           $pwq= $myrow1[qq]-$myrow1[qc];
           echo'<td><center>'.$slno.'</center></td>';
            echo'<td>'.$myrow1[stockid].' '.$myrow1[description].'</td>';
           echo'<td><center>'.$pwq.'</center></td>';
            
            echo'</tr>';
      
             $slno++;
            
        }
                         
echo'</table></fieldset></div>';
             echo'    <a id="Re_view2"  name="Re_view2"  >Pending Sales Orders</a></br>'; 
              echo"<div id='views2' style='margin:auto;'>";

   echo "<fieldset >";     
     echo "<legend><h3>Sub Assemblies work Orders</h3>";
     echo "</legend>";
  echo'<table width="100%" ><tr><th>Slno</th><th>Sub Assemblies</th><th>Pending Quty</th></tr>';
  $sql_select=" SELECT bio_wo_stocklocation.stockid,SUM(salesorderdetails.quantity) as qq,stockmaster.description
FROM salesorderdetails,bio_wo_stocklocation,stockmaster,salesorders
WHERE bio_wo_stocklocation.stockid=salesorderdetails.stkcode   
AND stockmaster.stockid=bio_wo_stocklocation.stockid
AND salesorders.orderno=salesorderdetails.orderno
AND salesorders.orderno NOT IN (SELECT orderno FROM bio_installation_status)
GROUP BY bio_wo_stocklocation.stockid";

$result_wo = DB_query($sql_select,$db);
 $k=1;

$slno=1;
        while ($myrow1=DB_fetch_array($result_wo))
        {
             if ($k==1){
        echo '<tr class="EvenTableRows">';
        $k=0;
    } else {
        echo '<tr class="OddTableRows">';
        $k=1;
    } 
           $pwq= $myrow1[qq];
           echo'<td><center>'.$slno.'</center></td>';
            echo'<td>'.$myrow1[stockid].' '.$myrow1[description].'</td>';
           echo'<td><center>'.$pwq.'</center></td>';
            
            echo'</tr>';
      
             $slno++;
            
        }
                         
echo'</table></fieldset></div>';
            echo'     <a id="Re_view3"  name="Re_view3"  >Plant Stock</a></br>';
             echo"<div id='views3' style='margin:auto;'>";
 echo "<fieldset >";     
     echo "<legend><h3>Plant Stock </h3>";
     echo "</legend>";
  echo'<table width="100%" ><tr><th>Slno</th><th>Sub Assemblies</th><th>Stock Quty</th></tr>';
  $sql_select="SELECT locstock.`stockid`,stockmaster.description,sum(locstock.`quantity`  )as qq
  FROM `locstock`
  INNER JOIN stockmaster ON locstock.stockid=stockmaster.stockid
   WHERE  `loccode`='" . $_POST['StockLocation'] . "' AND locstock.`quantity`>'0'
    AND  locstock.stockid IN(SELECT stockid FROM stockmaster WHERE categoryid
                                 IN (SELECT subcatid FROM bio_maincat_subcat WHERE maincatid =1)) 
    Group BY locstock.stockid
                                 ";

$result_wo = DB_query($sql_select,$db);
 $k=1;

$slno=1;
        while ($myrow1=DB_fetch_array($result_wo))
        {
             if ($k==1){
        echo '<tr class="EvenTableRows">';
        $k=0;
    } else {
        echo '<tr class="OddTableRows">';
        $k=1;
    } 
           $pwq= $myrow1[qq];
           echo'<td><center>'.$slno.'</center></td>';
            echo'<td>'.$myrow1[stockid].' '.$myrow1[description].'</td>';
           echo'<td><center>'.$pwq.'</center></td>';
            
            echo'</tr>';
      
             $slno++;
            
        }
                         
echo'</table></fieldset></div>';
           echo'      <a id="Re_view4"  name="Re_view4" >Sub Assemblies Stock</a></br>';
          echo'       </div>';
                
                

                  

  
 echo"<div id='views4' style='margin:auto;'>";
echo "<fieldset >";     
     echo "<legend><h3>Sub Assemblies Stock</h3>";
     echo "</legend>";
  echo'<table width="100%" ><tr><th>Slno</th><th>Sub Assemblies</th><th>Stock Quty</th></tr>';
  $sql_select="SELECT locstock.`stockid`,stockmaster.description,sum(locstock.`quantity`  )as qq
  FROM `locstock`
  INNER JOIN stockmaster ON locstock.stockid=stockmaster.stockid
   WHERE  `loccode`='" . $_POST['StockLocation'] . "' 
   AND locstock.`quantity`>'0'
    AND  locstock.stockid  NOT IN(SELECT stockid FROM stockmaster WHERE categoryid
                                 IN (SELECT subcatid FROM bio_maincat_subcat WHERE maincatid =1)) 
    Group BY locstock.stockid
                                 ";

$result_wo = DB_query($sql_select,$db);
 $k=1;

$slno=1;
        while ($myrow1=DB_fetch_array($result_wo))
        {
             if ($k==1){
        echo '<tr class="EvenTableRows">';
        $k=0;
    } else {
        echo '<tr class="OddTableRows">';
        $k=1;
    } 
           $pwq= $myrow1[qq];
           echo'<td><center>'.$slno.'</center></td>';
            echo'<td>'.$myrow1[stockid].' '.$myrow1[description].'</td>';
           echo'<td><center>'.$pwq.'</center></td>';
            
            echo'</tr>';
      
             $slno++;
            
        }
                         
echo'</table></fieldset></div>';

?> 
<script > 

$('#printgrid').hide();
$('#views1').hide(); 
$('#views2').hide(); 
$('#views3').hide(); 
$('#views4').hide();
 $('#shwprint').click(function() {
$('#views1').hide(); 
$('#views2').hide(); 
$('#views3').hide(); 
$('#views4').hide();
$('#printgrid').slideToggle('slow',function(){});
//$('#printgrid').hide();
}); 
 $('#Re_view1').click(function() {  
/*$('#views2').hide(); 
$('#views3').hide(); 
$('#views4').hide(); */
  $('#views1').slideToggle('slow',function(){});
});
 $('#Re_view2').click(function() { 
/*$('#views1').hide(); 
$('#views3').hide(); 
$('#views4').hide(); */
  $('#views2').slideToggle('slow',function(){});
});
 $('#Re_view3').click(function() { 
/*$('#views1').hide(); 
$('#views2').hide(); 
$('#views4').hide(); */
  $('#views3').slideToggle('slow',function(){});
});
 $('#Re_view4').click(function() {
/* $('#views1').hide(); 
$('#views2').hide(); 
$('#views3').hide(); */
$('#views4').slideToggle('slow',function(){});
});

function checkval(str1,str2,str3)
{         /* var ss=str2;// alert(ss) ;  */  
         var credit=str3; //alert(credit);                     
        var pwq=document.getElementById('pwq'+str2).value;     
           var qq=pwq-credit;//alert(qq);
if(credit!=""  )
{
        if(qq<0)
         {
            
             document.getElementById('err'+str2).innerHTML='<?php echo'<img src="'.$rootpath.'/images/b_drop.png" alt="" />'; echo'<input type="hidden" name="sss" id="sss"  value=1>'; ?>';
             document.getElementById('mag').innerHTML='<?php prnMsg(_('Quantity Entered is not currect '),'error'); ?>';        
          /*      
             document.getElementById(cr_qut0).focus(); 
           var xxx =document.getElementByName('cr_qut'+str2);  
            xxx.focus();  */
         }
         else{ document.getElementById('err'+str2).innerHTML='<?php echo'<img src="'.$rootpath.'/images/s_okay.png" alt="" />';echo'<input type="hidden" name="sss" id="sss"  value=2>'; ?>'; 
          document.getElementById('mag').innerHTML='';}
      
}
else{ document.getElementById('err'+str2).innerHTML='';
document.getElementById('mag').innerHTML='';}
}
function log_in()
{    
var rows=document.getElementById('rows').value;                       
var sss=document.getElementById('sss').value;
//var f=document.getElementById('aaa').value;                           
if(sss==1)
{
alert("Qty Entered is not currect  ");
return 1;
}
if(sss==0) //sss input hidden basic value is 0
{
alert("Enter Credit Quty  ");
return 1;
}                
/*for(i=0;i<rows;i++)
{                                         alert(rows);
   var credit=document.getElementById('cr_qut'+i).value;         
    if(credit!="")
    {
        var f=1;   alert("fhgfd");
       // break;
    }
   
}  alert(f); 
  
if(f==1)
{
alert(" Enter Credit Quty  ");
return 1;
}    */
 
/*var f=0;
if(f==0){f=common_error('leadsou','Please select the Lead Sources');  if(f==1){return f; } }    */
 
}
function showitem(str)
{
   //alert(str);
    var locc=document.getElementById('StockLocation').value;// alert(locc);
    var typee=document.getElementById('type').value; //alert(typee);
     
if (str=="")
  {
  document.getElementById("StockLocation").innerHTML="";
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
    document.getElementById("order_date").innerHTML=xmlhttp.responseText;

    }
  } 
xmlhttp.open("GET","bio_credit_ajax.php?loc=" + locc + "&type=" +typee  ,true);
xmlhttp.send();
}

</script>