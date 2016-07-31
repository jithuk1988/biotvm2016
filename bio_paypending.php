<?php
   $PageSecurity = 80;
    include ('includes/session.inc');
$title = _('PAYMENT PENDING');
include ('includes/header.inc');
echo '<head><link href="menu_assets/styles.css" rel="stylesheet" type="text/css"></head>';
include ('includes/SQL_CommonFunctions.inc');
echo "<div id='cssmenu'>
<ul>
     <li > <a href='bio_nwinstallationstatuses.php'><span>Post installation</span></a></li>
   <li ><a href='bio_marketingtask.php'><span>Marketing task</span></a></li>
   <li><a href='bio_warranty_amc.php'><span>AMC / Warrenty</span></a></li>
   <li class='active '><a href='bio_paypending.php'><span>Payment pending</span></a></li>
   <li><a href='bio_cdmsurvey.php'><span>CDM survey</span></a></li>
   <li><a href='bio_complaintfollow.php'><span>Complaint followup</span></a></li>
</ul>
</div>";
 echo '<center><font style="color: #333;
    background:#fff;
    font-weight:bold;
    letter-spacing:0.10em;
    font-size:16px;
    font-family:Georgia;
    text-shadow: 1px 1px 1px #666;"><br>PAYMENT PENDING</br></font></center>';
     echo'<table width=98% ><tr><td>'; 
    echo'<div >'; 
    
    echo "<form method='POST' name=form1 action='" . $_SERVER['PHP_SELF'] . "'>"; 
   
    echo '<table class="selection" style="width:70%;">';  
      echo '<th colspan="2"><b><font size="+1" color="#000000">CUSTOMER DETAILS</font></b></th>' ;  
          echo '<tr id=first><td>';
    echo"<fieldset style='width:440px;height:220px'; overflow:auto;'>";
    echo"<legend>Payment details</legend>";
       echo"<table  width=100%>";  
         echo'</table>';
         echo '</fieldset>';
         echo '</td>';  
         
           echo'</tr><tr><td><table><tr><td>Next preferred Date</td><td colspan="5"><input type="text" style="width:240px" id="date" class=date alt='.$_SESSION['DefaultDateFormat'].' name="date" onChange="return isDate(this, this.value, '."'".$_SESSION['DefaultDateFormat']."'".')'.'"'.' ></td>
           <td>REMARKS</td><td><textarea name="remark" cols="30"  rows="3"></textarea></td>
           <td><input type=submit name="submit1" value="save" onclick="if(valid()==1)return false; "></td></tr></table></td></tr><table>
           </tr></table>';    
            echo"<fieldset style='width:900px;'; overflow:auto;'>"; 
               if($_POST['submit1'])
     {
         $date1=date("d/m/Y");
            $date2=FormatDateForSQL($date1) ;
             $date3=$_POST['date'];
               $remarks="payment pending-".$_POST['remark'];
                    $order=$_POST['orderno'];
                 $sql="INSERT INTO `bio_calllog`(`orderno`, `call_date`,`remark`) VALUES ($order,'$date2','$remarks')";
                     $result=DB_query($sql,$db);
     }   
echo"<legend>Search option</legend>"; 
/*echo "<div style='height:75px; overflow:auto;'>"; */
echo"<table width=800px>";
echo"<tr><td>Order Status:</td><td><select name='status' id='status'>";
  
if($_POST['status']==1){
    echo '<option selected value=1>Pending Order</option>';
    echo '<option value=2>Installed</option>';
    echo '<option  value=3>ALL</option>';
}
else if($_POST['status']==2){
    echo '<option value=1>Pending Order</option>';
    echo '<option selected value=2>Installed</option>';
    echo '<option  value=3>ALL</option>';
}
else if($_POST['status']==3){
    echo '<option value=1>Pending Order</option>';
    echo '<option  value=2>Installed</option>';     
         echo '<option selected value=3>ALL</option>';
}
else
{
echo '<option  value=1>Pending Order</option>';
    echo '<option value=2>Installed</option>';
    echo '<option selected value=3>ALL</option>';
}
echo"</select></td>";



 echo "<td>Payment Status:</td><td><select name='pay_status' id='pay_status'>";
 
if($_POST['pay_status']==1){
   echo '<option  value=2>All</option>';
    echo '<option selected value=1>Full Paid</option>';
    echo '<option  value=3>Part Paid</option>';  
 
 }
 else if($_POST['pay_status']==2)
 {
  echo '<option selected  value=2>All</option>';
    echo '<option  value=1>Full Paid</option>';
    echo '<option  value=3>Part Paid</option>'; 
 }
 else if($_POST['pay_status']==3)
 {
  echo '<option  value=2>All</option>';
    echo '<option  value=1>Full Paid</option>';
    echo '<option  selected value=3>Part Paid</option>'; 
 }
 else
 {
  echo '<option  value=2>All</option>';
    echo '<option  value=1>Full Paid</option>';
    echo '<option selected value=3>Part Paid</option>';  
 }
 echo"</select></td>"; 
   if($_POST['df1'] && $_POST['dt1'])
  {
   echo '<td>From date:</td><td><input type="text" style="width:100px" value="'.$_POST['df1'].'" id="df1"class=date alt='.$_SESSION['DefaultDateFormat'].' name="df1" onChange="return isDate(this, this.value, '."'".$_SESSION['DefaultDateFormat']."'".')'.'"'.'></td>';  
echo '<td>To date:</td><td><input type="text" style="width:100px" value="'.$_POST['dt1'].'" id="dt1" class=date alt='.$_SESSION['DefaultDateFormat'].' name="dt1" onChange="return isDate(this, this.value, '."'".$_SESSION['DefaultDateFormat']."'".')'.'"'.'></td>';   
  }
  else{
      echo '<td>From date:</td><td><input type="text" style="width:100px"  value="'.$mr[0].'"  id="df1" class=date alt='.$_SESSION['DefaultDateFormat'].'  name="df1" onChange="return isDate(this, this.value, '."'".$_SESSION['DefaultDateFormat']."'".')'.'"'.'></td>';  
echo '<td>To date:</td><td><input type="text" style="width:100px"   value="'.$mr[1].'" id="dt1" class=date alt='.$_SESSION['DefaultDateFormat'].' name="dt1" onChange="return isDate(this, this.value, '."'".$_SESSION['DefaultDateFormat']."'".')'.'"'.'></td>'; 
  } 
//echo "<tr>";

echo"<td>Office:</td><td><select name='office' id='office' onchange='' style='width:100px'>";
$sql3="SELECT * FROM bio_office ORDER BY id";
$result3=DB_query($sql3,$db);
 $f=0;
  while($myrow3=DB_fetch_array($result3))
  {  
  if ($myrow3['id']==$_POST['office'])  
    {         //echo $myrow1['cid'];     
    echo '<option selected value="';
    } else 
    {
    if ($f==0) 
        {
        echo '<option>';
        }
        echo '<option value="';
    }
    echo $myrow3['id'] . '">'.$myrow3['office'];
    echo '</option>';
    $f++;
   }    
   echo"</td></tr><tr></tr><tr><td></td><td></td><td></td><td></td><td><input type='submit'  id='submit' value='Submit' name='submit'></td></tr>"; 
echo "</table>";
 echo"<input type='hidden' name='stop' id='stop' value='3'>"; 
  echo '</select></td>';

    echo'</table>';                     
  $sql2="SELECT
    `salesorders`.`orderno`
    , `custbranch`.`brname`
    , `orderamount`.`debtorno`
    , `orderamount`.`ordervalue`
    ,bio_installation_status.installed_date
    , `orderamount`.`orddate`
    , `debtorpaid`.`paid`
    , `orderplant`.`stkcode` as 'stockid'
    , IFNULL(orderamount.ordervalue- ifnull (`debtorpaid`.`paid`,0)-ifnull(ordersubsidy.totsubsidy,0),0) AS 'balance'
    , `salesorders`.`orderno`
    , `salesorders`.`orderno`
    , `salesorders`.`orderno`
    , concat(`custbranch`.`phoneno`,'<br>',`custbranch`.`faxno`) as 'phoneno'
    , `custbranch`.`LSG_type`,
      `orderamount`.`ordervalue`- ifnull(ordersubsidy.totsubsidy,0) as 'netpayable'    ,
     `bio_corporation`.`corporation`,
  `bio_municipality`.`municipality`,
  `bio_panchayat`.`name` AS panchayat,  
  `salesorders`.`contactphone` ,
  `custbranch`.`did` ,  
  `orderamount`.`debtorno` ,
  `bio_district`.`district`
FROM
    `salesorders`
    INNER JOIN `custbranch` 
        ON (`salesorders`.`debtorno` = `custbranch`.`debtorno`)
        left JOIN debtortrans ON (custbranch.debtorno=debtortrans.debtorno)
    LEFT JOIN `bio_panchayat` 
        ON (`custbranch`.`block_name` = `bio_panchayat`.`id`) AND (`custbranch`.`cid` = `bio_panchayat`.`country`) AND (`custbranch`.`LSG_name` = `bio_panchayat`.`block`) AND (`custbranch`.`stateid` = `bio_panchayat`.`state`) AND (`custbranch`.`did` = `bio_panchayat`.`district`)
    LEFT JOIN `bio_municipality` 
        ON (`custbranch`.`did` = `bio_municipality`.`district`) AND (`custbranch`.`stateid` = `bio_municipality`.`state`) AND (`custbranch`.`cid` = `bio_municipality`.`country`) AND (`custbranch`.`LSG_name` = `bio_municipality`.`id`)
    LEFT JOIN `bio_corporation` 
        ON (`custbranch`.`LSG_name` = `bio_corporation`.`district`) AND (`custbranch`.`stateid` = `bio_corporation`.`state`) AND (`custbranch`.`did` = `bio_corporation`.`district`) AND (`custbranch`.`cid` = `bio_corporation`.`country`)
    LEFT JOIN `bio_district` 
        ON (`custbranch`.`cid` = `bio_district`.`cid`) AND (`custbranch`.`did` = `bio_district`.`did`) AND (`custbranch`.`stateid` = `bio_district`.`stateid`)
    LEFT JOIN `ordersubsidy` 
        ON (`salesorders`.`orderno` = `ordersubsidy`.`orderno`)
    inner JOIN `orderamount` 
        ON (`salesorders`.`orderno` = `orderamount`.`orderno`)
    left JOIN `debtorpaid` 
        ON (`salesorders`.`debtorno` = `debtorpaid`.`debtorno`)
    inner JOIN `orderplant` 
        ON (`salesorders`.`orderno` = `orderplant`.`orderno`)
        left JOIN `bio_installation_status` 
        ON (`salesorders`.`orderno` = `bio_installation_status`.`orderno`)
                WHERE  orderamount.debtorno like 'D%'"; 
                
    if($_POST['office']==2){
       $sql2 .=" AND custbranch.did IN (1,2,3,7,13) ";
    }
     if($_POST['office']==3){
       $sql2 .=" AND custbranch.did IN (4,5,8,9,10,14) ";
    }
         if($_POST['office']==4){
       $sql2 .=" AND custbranch.did IN (6,11,12)  ";
    }                              

       
           
     if($_POST['status']==1)
{
     $sql2.=" AND salesorders.orderno NOT IN (SELECT orderno FROM bio_installation_status)";
           
    if($_POST['country']!=""){
         $sql2.=" AND custbranch.cid='".$_POST['country']."'";
     }
     if($_POST['State']!=""){
         $sql2.=" AND custbranch.stateid='".$_POST['State']."'";
     }                     
     if($_POST['District']!=""){
         $sql2.=" AND custbranch.did='".$_POST['District']."'";
     }
      if ((isset($_POST['df1'])) && (isset($_POST['dt1'])))   {
    if (($_POST['df1']!="") && ($_POST['dt1']!=""))  { 
    $dfrom=FormatDateForSQL($_POST['df1']);   
    $dto=FormatDateForSQL($_POST['dt1']);
  
    
    $sql2.=" AND salesorders.orddate BETWEEN '".$dfrom."' AND '".$dto."'";
    }
    }
             if($_POST['pay_status']==1) {
               $sql2.=" GROUP BY orderamount.orderno having balance<=0 order by salesorders.orddate asc";  
           }
           if($_POST['pay_status']==2)
          {
            $sql2.=" GROUP BY orderamount.orderno";
     } 
      if($_POST['pay_status']==3)
     {
                    $sql2.=" GROUP BY orderamount.orderno having balance>0 order by salesorders.orddate asc ";

     }
    
    }

   elseif($_POST['status']==2)
   { 
    $sql2.=" AND salesorders.orderno IN (SELECT orderno FROM bio_installation_status)";
     if($_POST['country']!=""){
         $sql2.=" AND custbranch.cid='".$_POST['country']."'";
     }
     if($_POST['State']!=""){
         $sql2.=" AND custbranch.stateid='".$_POST['State']."'";
     }                     
     if($_POST['District']!=""){
         $sql2.=" AND custbranch.did='".$_POST['District']."'";
     }
      if ((isset($_POST['df1'])) && (isset($_POST['dt1'])))   {
    if (($_POST['df1']!="") && ($_POST['dt1']!=""))  { 
    $dfrom=FormatDateForSQL($_POST['df1']);   
    $dto=FormatDateForSQL($_POST['dt1']);
    $sql2.=" AND bio_installation_status.installed_date BETWEEN '".$dfrom."' AND '".$dto."'";
   }
    
    }
  
     if($_POST['pay_status']==1) {
               $sql2.=" GROUP BY orderamount.orderno having balance<=0 order by salesorders.orderno asc";  
           }
           if($_POST['pay_status']==2)
          {
            $sql2.=" GROUP BY orderamount.orderno order by salesorders.orderno asc";
     } 
      if($_POST['pay_status']==3)
     {
                    $sql2.=" GROUP BY orderamount.orderno having balance>0 order by salesorders.orderno asc ";

     }
    
      }
      
       elseif($_POST['status']==3)
   { 
//    $sql2.=" AND salesorders.orderno";
     if($_POST['country']!=""){
         $sql2.=" AND custbranch.cid='".$_POST['country']."'";
     }
     if($_POST['State']!=""){
         $sql2.=" AND custbranch.stateid='".$_POST['State']."'";
     }                     
     if($_POST['District']!=""){
         $sql2.=" AND custbranch.did='".$_POST['District']."'";
     }
      if ((isset($_POST['df1'])) && (isset($_POST['dt1'])))   {
    if (($_POST['df1']!="") && ($_POST['dt1']!=""))  { 
    $dfrom=FormatDateForSQL($_POST['df1']);   
    $dto=FormatDateForSQL($_POST['dt1']);
    $sql2.=" AND salesorders.orddate BETWEEN '".$dfrom."' AND '".$dto."'";
   }
    
    }
  
     if($_POST['pay_status']==1) {
               $sql2.=" GROUP BY orderamount.orderno having balance<=0 order by salesorders.orderno asc";  
           }
           if($_POST['pay_status']==2)
          {
            $sql2.=" GROUP BY orderamount.orderno order by salesorders.orderno asc";
     } 
      if($_POST['pay_status']==3)
     {
                    $sql2.=" GROUP BY orderamount.orderno having balance>0 order by salesorders.orderno asc ";

     }
    
      }
     if((!isset($_POST['pay_status'])) && (!isset($_POST['office'])) && (!isset($_POST['status'])) && (!isset($_POST['df1'])) && (!isset($_POST['dt1'])))
    {
           $sql2.="GROUP BY orderamount.orderno having balance>0 order by salesorders.orderno asc";
    }  
/*echo $sql2."<br />";
   echo $_POST['office']."aa";  */
   // $sql2;
$result=DB_query($sql2,$db);
 echo  $count=DB_num_rows($result);
 if($count>0)
 {
      
   
echo "<div class='content' style='height:400px; overflow:auto;'>"; 
echo"<table style='align:center' width=90% height=400px>";
echo"<thead class='fixedHeader'><tr><th width='25px'>Sl.No.</th>"; 
echo "<th width='39px'>Order No</th><th width='39px'>Debtor No</th>"; 
echo "<th>Customer Name</th><th>Contact No.</th><th>District</th><th>LSG</th>";
if($_POST['status']==2){echo "<th>Installation Date</th>";}
else{echo "<th>Order Date</th>";}

 echo"<th width=80px>Plant Model</th>";
  echo"<th>Net payable</th>";
echo "<th>Amount Paid</th>";
  echo"<th>Balance</th>";
   echo"<th>select</th>";//
 echo "</tr></thead><tbody class='scrollContent'>";
  $k=0;  //row colour counter*
$slno=0; 
       
while($myrow=DB_fetch_array($result))
{
$sql="select `stockmaster`.`description` from `stockmaster` where `stockmaster`.`stockid`='".$myrow['stockid']."'";
$result1=DB_query($sql,$db);
$myrow1=DB_fetch_array($result1);
$ViewPage = $rootpath . '/OrderDetails.php?OrderNumber=' . $myrow['orderno'];
    if($myrow['LSG_type']==0)
    {
        $LSG="---";
    }
    else  if($myrow['LSG_type']==1)
    {
        $LSG=$myrow['corporation']."(C)";
    }
    elseif($myrow['LSG_type']==2)
    {
        $LSG=$myrow['municipality']."(M)";
    }elseif($myrow['LSG_type']==3)
    {
        $LSG=$myrow['panchayat']."(P)";
    }$slno++;
if($slno%2==0)
{

$e='EvenTableRow';
}
else
{

$e='OddTableRows';
}   $orderno=$myrow['orderno'];
if ($_POST['status']==2)
{
printf("<tr class='%s'><td>%s</td><td><a href='%s'>%s</a></td>
            <td>%s</td><td>%s</td>
            <td>%s</td>
            <td >%s</td><td>%s</td>
            <td>%s</td>  
            <td><label style='width:50px' title='%s'>%s</label></td>
            
            <td>%s</td>   <td>%s</td>
            <td>%s</td>    <td><input type='button'  value='select' onclick='dlt($orderno )'></td>   
            </tr>",$e,$slno,
            $ViewPage,
            $myrow[orderno], 
            $myrow[debtorno],
            $myrow[brname],  
            $myrow[phoneno],$myrow[district],$LSG,convertSQLDate($myrow[installed_date]),$myrow1[description],$myrow[stockid],$myrow[netpayable],$myrow[paid],$myrow[balance]
            );

}
else if ($_POST['status']==1 or $_POST['status']==3)

{
    $ld=DB_query("select ifnull(leadid,0) from salesorders where orderno=".$myrow[orderno]."",$db);
    $mr=DB_fetch_array($ld);
    $leadid=$mr[0];
    $domPage = $rootpath . '/bio_domTaskview.php?lead=' . $mr[0];

    printf("<tr class='%s'><td>%s</td><td><a href='%s' target='_blank'>%s</a></td>
            <td><a href='%s' target='_blank' >%s</a></td><td>%s</td>
            <td>%s</td>
            <td >%s</td><td>%s</td>  
            <td>%s</td>  
            <td><label style='width:50px' title='%s'>%s</label></td>
            
            <td>%s</td>   <td>%s</td>
            <td>%s</td>
            <td><input type='button'  value='select' onclick='dlt($orderno )'></td> 
            </tr>",$e,$slno,
            $ViewPage,
            $myrow[orderno], 
            $domPage,
            $myrow[debtorno], 
            $myrow[brname],  
            $myrow[phoneno],$myrow[district],$LSG,convertSQLDate($myrow[orddate]),$myrow1[description],$myrow[stockid],$myrow[netpayable],$myrow[paid],$myrow[balance]
            );
}
else
{
  $ld=DB_query("select ifnull(leadid,0) from salesorders where orderno=".$myrow[orderno]."",$db);
    $mr=DB_fetch_array($ld);
    $leadid=$mr[0];
    $domPage = $rootpath . '/bio_domTaskview.php?lead=' . $mr[0];

    printf("<tr class='%s'><td>%s</td><td><a href='%s' target='_blank'>%s</a></td>
            <td><a href='%s' target='_blank' >%s</a></td><td>%s</td>
            <td>%s</td>
            <td >%s</td><td>%s</td>  
            <td>%s</td>  
            <td><label style='width:50px' title='%s'>%s</label></td>
            
            <td>%s</td>   <td>%s</td>
            <td>%s</td>
            <td><input type='button'  value='select' onclick='dlt($orderno )'></td> 
            </tr>",$e,$slno,
            $ViewPage,
            $myrow[orderno], 
            $domPage,
            $myrow[debtorno], 
            $myrow[brname],  
            $myrow[phoneno],$myrow[district],$LSG,convertSQLDate($myrow[orddate]),$myrow1[description],$myrow[stockid],$myrow[netpayable],$myrow[paid],$myrow[balance]
            );  
}

}   }   
  //  }
echo"</tbody></table>"; 
echo"</div>";  
echo"</fieldset>";  
echo"</form>";                                                                        
?>
<script>
function dlt(str){
  //  alert("kjkjk");

   // location.href="?leadid1="+str;    
  if (str=="")
  {
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
  { // alert (str1);
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
          
         // document.getElementById('showloc').style.display = "block";
     document.getElementById("first").innerHTML=xmlhttp.responseText; 
     
    }            
  } 
xmlhttp.open("GET","bio_pendingajax.php?orderno="+str,true); //alert(str);
xmlhttp.send();        
  
}   
function valid()
{         
 var y=document.getElementById('stop').value; //alert("gh");   
          if(y==3)
          {
          alert("Please select a customer") ;
          //document.getElementById('Address1').focus();
              return 1;
             
             
          } else
          {
          str=document.getElementById('date').value; 
          if(str=="") 
          {
                 alert("Please select a date") ;
          //document.getElementById('Address1').focus();
              return 1;
          }
          }
}
</script>
