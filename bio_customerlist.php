<?php
  $PageSecurity = 80; 
include('includes/session.inc'); 
$title = _('List of payment');
include('includes/header.inc');  


echo"<br />";
    
echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "?" . SID . "'>"; 
   
echo"<fieldset style='width:900px;'; overflow:auto;'>";    
echo"<legend>Customer Details</legend>"; 
/*echo "<div style='height:75px; overflow:auto;'>"; */
echo"<table width=800px>";
echo"<tr><td colspan=3>Select Type of customers:&nbsp<select name='status' id='status'>";
if($_POST['status']==1){
    echo '<option></option>';
    echo '<option selected value=1>Without order</option>';
    echo '<option value=2>With order</option>';
}
else if($_POST['status']==2){
    echo '<option></option>';
    echo '<option value=1>Without order</option>';
    echo '<option selected value=2>With order</option>';
}
else{
    echo '<option selected></option>';
    echo '<option value=1>Without order</option>';
    echo '<option value=2>With order</option>';
}
echo"</select></td></tr>";

/*echo  "<h4>$heading</h4>";
echo "</table>";
echo "</div>";  

echo "<div  overflow:auto;'>"; 
echo"<table width=800px>";*/
echo "<tr>";
    echo"<td>&nbsp&nbsp&nbsp&nbspCountry:&nbsp<select name='country' id='country' onchange='showstate(this.value)' style='width:100px'>";
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
  echo'</td></tr>';
 echo '<tr><td>From date:&nbsp<input type="text" style="width:100px" id="df1" class=date alt='.$_SESSION['DefaultDateFormat'].' name="df1" onChange="return isDate(this, this.value, '."'".$_SESSION['DefaultDateFormat']."'".')'.'"'.' value='.date("d/m/Y").'></td>';  
echo '<td>To date:&nbsp<input type="text" style="width:100px" id="dt1" class=date alt='.$_SESSION['DefaultDateFormat'].' name="dt1" onChange="return isDate(this, this.value, '."'".$_SESSION['DefaultDateFormat']."'".')'.'"'.' value='.date("d/m/Y").'></td>'; 
    
echo"<td>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<input type='submit'  id='submit' value='Submit' name='submit'></td></tr>"; 
echo "</table>";
//echo "</div>";

/*$sql="SELECT debtorsmaster.name,custbranch.phoneno,custbranch.faxno,salesorders.orddate AS orderdate,bio_installation_status.installed_date,bio_plantstatus.plantstatus 
      FROM   debtorsmaster,custbranch,salesorders,bio_installation_status,bio_plantstatus
      WHERE  bio_installation_status.orderno=salesorders.orderno
      AND    salesorders.debtorno= debtorsmaster.debtorno
      AND    custbranch.debtorno= debtorsmaster.debtorno 
      AND    salesorders.currentstatus= bio_plantstatus.id";  */
/*if(isset($_GET['status']=="paid")){
/*$sql.=" AND salesorders.orddate BETWEEN '$_GET[year]-01-01' AND '$_GET[year]-12-31' ";  */
/*$sql="SELECT name FROM debtorsmaster,debtortrans WHERE debtorsmaster.debtorno=debtortrans.debtorno";
}      
      
 
      
      SELECT debtorsmaster.name,custbranch.phoneno,custbranch.faxno,bio_oldorders.createdon AS orderdate,bio_installation_status.installed_date,bio_plantstatus.plantstatus 
      FROM   debtorsmaster,custbranch,bio_oldorders,bio_installation_status,bio_plantstatus
      WHERE  bio_installation_status.orderno=bio_oldorders.orderno
      AND    bio_oldorders.debtorno= debtorsmaster.debtorno
      AND    custbranch.debtorno= debtorsmaster.debtorno
      AND    bio_oldorders.currentstatus= bio_plantstatus.id";  //*/
      ////////////////////////////////////////////////////////////////
         /* $sql="SELECT *FROM debtorsmaster,debtortrans,salesorders WHERE debtorsmaster.debtorno=debtortrans.debtorno AND salesorders.debtorno=debtortrans.debtorno";*/  
       
   ////////////////////////////////////////////////////////////////////    
        /*$sql2="SELECT   `debtorsmaster`.`name`,
                        `salesorders`.`contactphone`,
                          `debtorsmaster`.`did` ,
                          `bio_district`.`district`,
                          `custbranch`.`phoneno`,
                          `debtorsmaster`.`LSG_type`,
                          debtortrans.ovamount as paid    
        FROM debtorsmaster,debtortrans,salesorders,bio_district,custbranch,bio_corporation,bio_municipality,bio_panchayat,bio_block
                          WHERE debtorsmaster.debtorno=debtortrans.debtorno 
                           AND debtortrans.debtorno NOT IN(SELECT debtorno FROM salesorders)
                          AND  bio_district.did=debtorsmaster.did
                          AND  bio_district.stateid=debtorsmaster.stateid
                           AND  bio_district.cid=debtorsmaster.cid
                           AND custbranch.debtorno=debtortrans.debtorno     
                           AND `bio_corporation`.`country` = `debtorsmaster`.`cid` 
                           AND `bio_corporation`.`state` = `debtorsmaster`.`stateid` 
                           AND `bio_corporation`.`district` = `debtorsmaster`.`LSG_name` 
                           AND `bio_corporation`.`district` = `debtorsmaster`.`did`
                           AND `bio_municipality`.`country` = `debtorsmaster`.`cid` 
                           AND `bio_municipality`.`state` = `debtorsmaster`.`stateid` 
                           AND `bio_municipality`.`district` = `debtorsmaster`.`did` 
                           AND `bio_municipality`.`id` = `debtorsmaster`.`LSG_name`
                           AND `debtorsmaster`.`LSG_name` = `bio_block`.`id` AND `debtorsmaster`.`did` = `bio_block`.`district` 
                           AND `debtorsmaster`.`stateid` = `bio_block`.`state` 
                           AND `debtorsmaster`.`cid` = `bio_block`.`country`
                           AND `debtorsmaster`.`did` = `bio_panchayat`.`district` 
                           AND `debtorsmaster`.`block_name` = `bio_panchayat`.`id` 
                           AND `debtorsmaster`.`stateid` = `bio_panchayat`.`state` 
                           AND `debtorsmaster`.`LSG_name` = `bio_panchayat`.`block` 
                           AND `debtorsmaster`.`cid` = `bio_panchayat`.`country`
                          GROUP BY debtortrans.debtorno";  */


   /*            
    FROM debtorsmaster
    INNER JOIN `bio_cust` ON ( `bio_leads`.`cust_id` = `bio_cust`.`cust_id` )  
    LEFT JOIN bio_district ON bio_district.did = bio_cust.district
    AND bio_district.stateid = bio_cust.state
    AND bio_district.cid = bio_cust.nationality
    WHERE bio_cust.cust_id = bio_leads.cust_id
    AND bio_leads.parent_leadid =".$parent_leadid ;            */ 
                                                    
       
if(isset($_POST['submit']))
{
    
    

if($_POST['status']==1){
  
  $sql2="SELECT `custbranch`.`brname`,
    concat(custbranch.phoneno,'<br/>',custbranch.faxno) as 'phoneno',
  `custbranch`.`LSG_type`,
  `bio_corporation`.`corporation`,
  `bio_municipality`.`municipality`,
  `bio_panchayat`.`name` as panchayat,
  `custbranch`.`did`,
  `bio_district`.`district`,
    SUM(debtortrans.ovamount) as paid    
  FROM debtortrans
LEFT JOIN salesorders ON (debtortrans.debtorno=salesorders.debtorno)
INNER JOIN debtorsmaster ON (debtorsmaster.debtorno=debtortrans.debtorno)
INNER JOIN custbranch ON (debtorsmaster.debtorno=custbranch.debtorno)      
LEFT JOIN bio_district ON (`debtorsmaster`.`did` = `bio_district`.`did`) AND (`bio_district`.`cid` = `debtorsmaster`.`cid`) AND (`debtorsmaster`.`stateid` = `bio_district`.`stateid`)
LEFT JOIN bio_corporation ON (`bio_corporation`.`country` = `custbranch`.`cid`) AND (`bio_corporation`.`state` = `custbranch`.`stateid`) AND (`bio_corporation`.`district` = `custbranch`.`LSG_name`) AND (`bio_corporation`.`district` = `custbranch`.`did`) 
LEFT JOIN bio_municipality ON (`bio_municipality`.`country` = `custbranch`.`cid`) AND (`bio_municipality`.`state` = `custbranch`.`stateid`) AND (`bio_municipality`.`district` = `custbranch`.`did`) AND (`bio_municipality`.`id` = `custbranch`.`LSG_name`) 
LEFT JOIN bio_block ON (`custbranch`.`LSG_name` = `bio_block`.`id`) AND (`custbranch`.`did` = `bio_block`.`district`) AND (`debtorsmaster`.`stateid` = `bio_block`.`state`) AND (`debtorsmaster`.`cid` = `bio_block`.`country`) 
LEFT JOIN bio_panchayat ON (`custbranch`.`did` = `bio_panchayat`.`district`) AND (`custbranch`.`block_name` = `bio_panchayat`.`id`) AND (`custbranch`.`stateid` = `bio_panchayat`.`state`) AND (`custbranch`.`LSG_name` = `bio_panchayat`.`block`) AND (`custbranch`.`cid` = `bio_panchayat`.`country`)
                          WHERE debtortrans.debtorno NOT IN(SELECT debtorno FROM salesorders)";
    

           $heading="Payment recieved customer's list";                        

}
else
{
    
   // $sql4="CREATE OR REPLACE VIEW `orderamount` AS (select `salesorders`.`orderno` AS `orderno`,`salesorders`.`debtorno` AS `debtorno`,`salesorders`.`orddate` AS `orddate`,sum(((`salesorderdetails`.`unitprice` * `salesorderdetails`.`quantity`) * (1 - `salesorderdetails`.`discountpercent`))) AS `ordervalue` from (((`salesorders` join `salesorderdetails`) join `debtorsmaster`) join `custbranch`) where ((`salesorders`.`orderno` = `salesorderdetails`.`orderno`) and (`salesorders`.`debtorno` = `debtorsmaster`.`debtorno`) and (`salesorders`.`branchcode` = `custbranch`.`branchcode`) and (`debtorsmaster`.`debtorno` = `custbranch`.`debtorno`) and (`salesorders`.`debtorno` like 'D%')) group by `salesorders`.`orderno`)";
    
   // $sql5="CREATE OR REPLACE VIEW `debtorpaid` AS (select `debtortrans`.`debtorno` AS `debtorno`,abs(sum(`debtortrans`.`ovamount`)) AS `paid` from `debtortrans` where (`debtortrans`.`debtorno` like 'D%') group by `debtortrans`.`debtorno` having (`paid` > 0))";
    
    
   // $sql6="CREATE OR REPLACE VIEW `ordersubsidy` AS (select `salesorders`.`orderno` AS `orderno`,`salesorders`.`debtorno` AS `debtorno`,sum(`bio_propsubsidy`.`amount`) AS `totsubsidy` from (((`salesorders` join `bio_propsubsidy`) join `debtorsmaster`) join `custbranch`) where ((`salesorders`.`debtorno` = `debtorsmaster`.`debtorno`) and (`salesorders`.`branchcode` = `custbranch`.`branchcode`) and (`debtorsmaster`.`debtorno` = `custbranch`.`debtorno`) and (`salesorders`.`leadid` = `bio_propsubsidy`.`leadid`) and (`salesorders`.`debtorno` like 'D%')) group by `salesorders`.`orderno`)";
    
    
  //  $sql7="CREATE OR REPLACE VIEW `ordernetpayable` AS (select `ordersubsidy`.`orderno` AS `orderno`,`ordersubsidy`.`debtorno` AS `debtorno`,(`orderamount`.`ordervalue` - `ordersubsidy`.`totsubsidy`) AS `netpayable` from (`ordersubsidy` join `orderamount`) where (`orderamount`.`orderno` = `ordersubsidy`.`orderno`))";
   // $result4=DB_query($sql4,$db);
  //  $result5=DB_query($sql5,$db);
  //  $result6=DB_query($sql6,$db);
   // $result7=DB_query($sql7,$db);
 $sql2="SELECT `orderamount`.`orderno`, 
  `orderamount`.`debtorno`,
  `custbranch`.`brname`,
  concat(custbranch.phoneno,'<br/>',custbranch.faxno) as 'phoneno',
  `custbranch`.`LSG_type`,
  `bio_corporation`.`corporation`,
  `bio_municipality`.`municipality`,
  `bio_panchayat`.`name` as panchayat,  
  `salesorders`.`contactphone` ,
  `custbranch`.`did` ,  
  `orderamount`.`debtorno` ,
  `bio_district`.`district`,
  `debtorpaid`.`paid` as paid,
  `ordersubsidy`.`totsubsidy`,
  `ordernetpayable`.`netpayable` as netpayable,(netpayable-COALESCE(paid,0)) as balance           
  FROM salesorders
 
  INNER JOIN custbranch ON (salesorders.debtorno=custbranch.debtorno)
  INNER JOIN debtorsmaster ON (debtorsmaster.debtorno=salesorders.debtorno)
  INNER JOIN orderamount ON (salesorders.debtorno=orderamount.debtorno)
  INNER JOIN ordersubsidy ON (salesorders.debtorno=ordersubsidy.debtorno)
  LEFT JOIN debtortrans ON (salesorders.debtorno=debtortrans.debtorno)   
 LEFT JOIN ordernetpayable ON (salesorders.debtorno=ordernetpayable.debtorno) 
LEFT JOIN debtorpaid ON (debtorpaid.debtorno=salesorders.debtorno)
LEFT JOIN bio_district ON (`custbranch`.`did` = `bio_district`.`did`) AND (`bio_district`.`cid` = `custbranch`.`cid`) AND (`custbranch`.`stateid` = `bio_district`.`stateid`)
LEFT JOIN bio_corporation ON (`bio_corporation`.`country` = `custbranch`.`cid`) AND (`bio_corporation`.`state` = `custbranch`.`stateid`) AND (`bio_corporation`.`district` = `custbranch`.`LSG_name`) AND (`bio_corporation`.`district` = `custbranch`.`did`) 
LEFT JOIN bio_municipality ON (`bio_municipality`.`country` = `custbranch`.`cid`) AND (`bio_municipality`.`state` = `custbranch`.`stateid`) AND (`bio_municipality`.`district` = `custbranch`.`did`) AND (`bio_municipality`.`id` = `custbranch`.`LSG_name`) 
LEFT JOIN bio_block ON (`custbranch`.`LSG_name` = `bio_block`.`id`) AND (`custbranch`.`did` = `bio_block`.`district`) AND (`custbranch`.`stateid` = `bio_block`.`state`) AND (`custbranch`.`cid` = `bio_block`.`country`) 
LEFT JOIN bio_panchayat ON (`custbranch`.`did` = `bio_panchayat`.`district`) AND (`custbranch`.`block_name` = `bio_panchayat`.`id`) AND (`custbranch`.`stateid` = `bio_panchayat`.`state`) AND (`custbranch`.`LSG_name` = `bio_panchayat`.`block`) AND (`custbranch`.`cid` = `bio_panchayat`.`country`) WHERE salesorders.debtorno=orderamount.debtorno ";

          
                          
           $heading="Payment not recieved customer's list";
           
           
           
}
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
    $sql2.=" AND debtortrans.trandate BETWEEN '".$dfrom."' AND '".$dto."'";
    }  }
    

    
                                                    
if($_POST['status']==1){
    $sql2.=" AND debtortrans.debtorno not in ('BCC','MNRE') GROUP BY debtortrans.debtorno";
}
else
{
   $sql2.=" AND orderamount.debtorno not in ('BCC','MNRE') GROUP BY orderamount.orderno"; $sql2.=" ORDER BY orderamount.orderno desc";
}


//}                
//  `salesorderdetails`.`stkcode`{LEFT JOIN salesorderdetails ON (orderamount.orderno=salesorderdetails.orderno) }
                          
/*                     AND  (`bio_municipality`.`country` = `debtorsmaster`.`cid`) AND (`bio_municipality`.`state` = `debtorsmaster`.`stateid`) AND (`bio_municipality`.`district` = `debtorsmaster`.`did`) AND (`bio_municipality`.`id` = `debtorsmaster`.`LSG_name`) 
                          AND (`debtorsmaster`.`LSG_name` = `bio_block`.`id`) AND (`debtorsmaster`.`did` = `bio_block`.`district`) AND (`debtorsmaster`.`stateid` = `bio_block`.`state`) AND (`debtorsmaster`.`cid` = `bio_block`.`country`) 
                          AND  (`debtorsmaster`.`did` = `bio_panchayat`.`district`) AND (`debtorsmaster`.`block_name` = `bio_panchayat`.`id`) AND (`debtorsmaster`.`stateid` = `bio_panchayat`.`state`) AND (`debtorsmaster`.`LSG_name` = `bio_panchayat`.`block`) AND (`debtorsmaster`.`cid` = `bio_panchayat`.`country`)      
                        
                          ,bio_municipality,bio_block,bio_panchayat    `bio_municipality`.`municipality`,
  `bio_block`.`block`,
  `bio_panchayat`.`name` as panchayat, */                                                                                                                                                     
                                
//echo $sql; 

    
$result=DB_query($sql2,$db);

echo "<div style='height:400px; overflow:auto;'>"; 
echo"<table width=800px>";
echo"<tr><th width='25px'>Sl.No.</th>";
if($_POST['status']==2){ 
echo "<th width='39px'>Order No</th>";
}
echo "<th>Customer Name</th><th>Contact No.</th><th>District</th><th>LSG</th>";

if($_POST['status']==1)
{ 
echo "<th>Amount Paid</th>";
 }
else
{ 
 echo"<th width=80px>Plant Model</th>";
  echo"<th>Net payable</th>";
echo "<th>Amount Paid</th>";
  echo"<th>Balance</th>";
}
 echo "</tr>";
  $k=0;  //row colour counter*
$slno=0; 
while($myrow=DB_fetch_array($result))
{
$sql="select `orderamount`.`orderno`,`stockmaster`.`stockid`,`stockmaster`.`description` from `stockmaster`,`salesorderdetails`,`orderamount`,`stockcategory`
where `orderamount`.`orderno`=`salesorderdetails`.`orderno` AND orderamount.orderno='".$myrow['orderno']."'
AND `salesorderdetails`.`stkcode`=`stockmaster`.`stockid`
AND `stockmaster`.`categoryid`='PDO'";
$result1=DB_query($sql,$db);
$myrow1=DB_fetch_array($result1);
$ViewPage = $rootpath . '/OrderDetails.php?OrderNumber=' . $myrow1['orderno'];
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
			<td>%s</td>
			<td>%s</td>
            <td >%s</td><td>%s</td><td><label style="width:50px" title="%s">%s</label></td>
			
			<td>%s</td>   <td>%s</td>
			<td>%s</td>  
			</tr>',$e,$slno,
			$ViewPage,
			$myrow1[orderno], 
            $myrow[brname],  
			$myrow[phoneno],$myrow[district],$LSG,$myrow1[description],$myrow1[stockid],$myrow[netpayable],$myrow[paid],$myrow[balance]
			);

}
else if ($_POST['status']==1)

{
printf('<tr class="%s"><td>%s</td>			<td>%s</td>
			<td>%s</td>            <td >%s</td>
			<td>%s</td>
			<td>%s</td>  			</tr>',$e,$slno,
						            $myrow[brname],  
			$myrow[phoneno],$myrow[district],$LSG,abs($myrow[paid])			);



}

   }


echo"</table>"; 
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