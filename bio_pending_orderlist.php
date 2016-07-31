<?php
  $PageSecurity = 80; 
include('includes/session.inc'); 
$title = _('List of payment');include('includes/header.inc'); ?>
<style type="text/css">
 
        div.content
        {
            border: #000000 1px solid;
            height: 400px;
            overflow-y: auto;
            width: 800px;
        }

      
        .fixedHeader tr 
        {
            height: auto;
            position: relative;
        }

        .fixedHeader tr td 
        {
            background-color: #778899;
            border: #000000 1px solid;
            text-align: center;
        }

        tbody.scrollContent 
        {
            overflow-x: hidden;
            overflow-y: scroll;
            height: auto;
        }

        .scrollContent tr td 
        {
           
           
            padding-right: 22px;
            vertical-align: top;
        }
</style>
<?php
 



echo"<br />";
    
echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "?" . SID . "'>"; 
   $dt="SELECT DATE_FORMAT(CURDATE() - INTERVAL 90 DAY,'%d/%m/%Y') AS 'pdate',DATE_FORMAT(CURDATE(),'%d/%m/%Y') AS 'cdate'";
   $rt=DB_query($dt,$db);
   $mr=DB_fetch_array($rt);
echo"<fieldset style='width:900px;'; overflow:auto;'>";    
echo"<legend>Customer Details</legend>"; 
/*echo "<div style='height:75px; overflow:auto;'>"; */
echo"<table width=800px>";
echo"<tr><td width='28%'>Order Status: <select name='status' id='status'>";
 
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
echo '<option selected value=1>Pending Order</option>';
    echo '<option value=2>Installed</option>';
    echo '<option  value=3>ALL</option>';
}
echo"</select></td>";



 echo "<td width='25%'>Payment Status: <select name='pay_status' id='pay_status'>";
 
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
    echo '<option selected value=1>Full Paid</option>';
    echo '<option  value=3>Part Paid</option>';  
 }
 echo"</select></td>"; 
   if($_POST['df1'] && $_POST['dt1'])
  {
   echo '<td>From date:&nbsp<input type="text" style="width:100px" value="'.$_POST['df1'].'" id="df1"class=date alt='.$_SESSION['DefaultDateFormat'].' name="df1" onChange="return isDate(this, this.value, '."'".$_SESSION['DefaultDateFormat']."'".')'.'"'.'></td>';  
echo '<td>To date:&nbsp<input type="text" style="width:100px" value="'.$_POST['dt1'].'" id="dt1" class=date alt='.$_SESSION['DefaultDateFormat'].' name="dt1" onChange="return isDate(this, this.value, '."'".$_SESSION['DefaultDateFormat']."'".')'.'"'.'></td>';   
  }
  else{
      echo '<td>From date:&nbsp<input type="text" style="width:100px"  value="'.$mr[0].'"  id="df1" class=date alt='.$_SESSION['DefaultDateFormat'].'  name="df1" onChange="return isDate(this, this.value, '."'".$_SESSION['DefaultDateFormat']."'".')'.'"'.'></td>';  
echo '<td>To date:&nbsp<input type="text" style="width:100px"   value="'.$mr[1].'" id="dt1" class=date alt='.$_SESSION['DefaultDateFormat'].' name="dt1" onChange="return isDate(this, this.value, '."'".$_SESSION['DefaultDateFormat']."'".')'.'"'.'></td>'; 
  } 
echo "<tr>";

echo"<td>Office&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp:&nbsp<select name='office' id='office' onchange='' style='width:100px'>";
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
  echo '</select></td>';

/*    echo"<td>&nbsp&nbsp&nbsp&nbspCountry:&nbsp<select name='country' id='country' onchange='showstate(this.value)' style='width:100px'>";
$sql3="SELECT * FROM bio_country ORDER BY cid";
$result3=DB_query($sql3,$db);
 $f=0;
  while($myrow3=DB_fetch_array($result3))
  {  
  if ($myrow3['cid']==1)  
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
    echo $myrow3['cid'] . '">'.$myrow3['country'];
    echo '</option>';
    $f++;
   }    
  echo '</select></td>';
   echo '<td id="showstate">&nbsp&nbsp&nbspState:&nbsp<select name="State" id="state" onchange="showdistrict(this.value)" style="width:100px">';
  $sql3="SELECT * FROM bio_state where cid=1 ORDER BY stateid";
  $result3=DB_query($sql3,$db);
  $f=0;
  while($myrow3=DB_fetch_array($result3))
  {
  if ($myrow3['stateid']==14)
    {
    echo '<option selected value="';
    } else
    {
    if ($f==0)
        {
        echo '<option>';
        }
        echo '<option value="';
    }
    echo $myrow3['stateid'] . '">'.$myrow3['state'];
    echo '</option>';
    $f++;
   }
  echo '</select>';
  echo'</td>'; 
    echo '<td id="showdistrict">District:&nbsp<select name="District" id="Districts" style="width:100px"   onchange="showtaluk(this.value)">'; 
  $sql3="SELECT * FROM bio_district WHERE stateid=14 ORDER BY did";
    $result3=DB_query($sql3,$db);
    $f=0;
  while($myrow3=DB_fetch_array($result3))
  {
  if ($myrow3['did']==$_POST['District'])
    {
    echo '<option selected value="';
    } else
    {
    if ($f==0)
        {
        echo '<option>';
        }
        echo '<option value="';
    }
    echo $myrow3['did'] . '">'.$myrow3['district'];
    echo '</option>';
    $f++;
   } 
  echo '</select>';
  echo'</td>';*/
  
echo"<td><input type='submit'  id='submit' value='Submit' name='submit'></td></tr>"; 
echo "</table>";

                                                    
       
if(isset($_POST['submit']))
{
                       
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
      
/*echo $sql2."<br />";
   echo $_POST['office']."aa";  */
   // echo $sql2;
$result=DB_query($sql2,$db);

echo "<div class='content'>"; 
echo"<table style='align:center' width=900px height=400px>";
echo"<thead class='fixedHeader'><tr><th width='25px'>Sl.No.</th>"; 
echo "<th width='39px'>Order No</th><th width='39px'>Code</th>";
echo "<th>Customer Name</th><th>Contact No.</th><th>District</th><th>LSG</th>";
if($_POST['status']==1 or $_POST['status']==3){echo "<th>Order Date</th>";}
if($_POST['status']==2){echo "<th>Installation Date</th>";}
 echo"<th width=80px>Plant Model</th>";
  echo"<th>Net payable</th>";
echo "<th>Amount Paid</th>";
  echo"<th>Balance</th>";//
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
}
if ($_POST['status']==2)
{
printf('<tr class="%s"><td>%s</td><td><a href="%s">%s</a></td>
			<td>%s</td><td>%s</td>
			<td>%s</td>
            <td >%s</td><td>%s</td>
            <td>%s</td>  
            <td><label style="width:50px" title="%s">%s</label></td>
			
			<td>%s</td>   <td>%s</td>
			<td>%s</td>  
			</tr>',$e,$slno,
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

    printf('<tr class="%s"><td>%s</td><td><a href="%s" target="_blank">%s</a></td>
            <td><a href="%s" target="_blank" >%s</a></td><td>%s</td>
            <td>%s</td>
            <td >%s</td><td>%s</td>  
            <td>%s</td>  
            <td><label style="width:50px" title="%s">%s</label></td>
            
            <td>%s</td>   <td>%s</td>
            <td>%s</td>  
            </tr>',$e,$slno,
            $ViewPage,
            $myrow[orderno], 
            $domPage,
            $myrow[debtorno], 
            $myrow[brname],  
            $myrow[phoneno],$myrow[district],$LSG,convertSQLDate($myrow[orddate]),$myrow1[description],$myrow[stockid],$myrow[netpayable],$myrow[paid],$myrow[balance]
            );
/*printf('<tr class="%s"><td>%s</td>			 
                        <td>%s</td>
                        <td>%s</td> 
                        <td>%s</td>
                        <td >%s</td>
                        <td>%s</td>
                        <td>%s</td>
                        </tr>',$e,$slno,$myrow[brname],  
			$myrow[phoneno],$myrow[district],$LSG,$myrow1[orddate],abs($myrow[paid])			);*/



}


}
echo"</tbody></table>"; 
echo"</div>";  

echo"</fieldset>";   

echo"</form>";
}
?>


<script type="text/javascript">  

function selectyear(str)
{
    location.href="?year=" + str;
}
function Get_customerdetails(str)
{
      location.href="?status=" + str;
}

function showstate(str){
   // alert(str); 

if (str=="")
  {
  document.getElementById("showstate").innerHTML="";
  return;
  }
//show_progressbar('showstate');

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
    {                 //  alert(str); 
    document.getElementById("showstate").innerHTML=xmlhttp.responseText;

    }
  }
xmlhttp.open("GET","bio_lsgFilter.php?country=" + str,true);
xmlhttp.send();
}

function showdistrict(str){       
//    alert(str);
str1=document.getElementById("country").value;
//alert(str1);
if (str=="")
  {
  document.getElementById("showdistrict").innerHTML="";
  return;
  }
//show_progressbar('showdistrict');
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
    document.getElementById("showdistrict").innerHTML=xmlhttp.responseText;

    }
  }
xmlhttp.open("GET","bio_lsgFilter.php?state=" + str + "&country1=" + str1,true);
xmlhttp.send();
}
</script>