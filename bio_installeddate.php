<?php
$PageSecurity = 80;   
include('includes/session.inc');

$title = _('Plant Date');

include('includes/header.inc');

echo '<p class="page_title_text"><img src="'.$rootpath.'/css/'.$theme.'/images/magnifier.png" title="' . _('Search') .
    '" alt="" />' . ' ' . _('Installation Report') . '</p>';

echo '<form action="' . $_SERVER['PHP_SELF'] . '" method="post">';


echo '<table class="selection" width=850px;>'; 
echo '<tr>';

echo"<td>Country<select name='country' id='country' onchange='showstate(this.value)' style='width:130px'>";
$sql="SELECT * FROM bio_country ORDER BY cid";
$result=DB_query($sql,$db);
 $f=0;
  while($myrow1=DB_fetch_array($result))
  {  
  if ($myrow1['cid']==1)  
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
    echo $myrow1['cid'] . '">'.$myrow1['country'];
    echo '</option>';
    $f++;
   }    
  echo '</select></td>'; 
  
    echo '<td id="showstate">State<select name="state" id="state" onchange="showdistrict(this.value)" style="width:130px">';
  $sql="SELECT * FROM bio_state ORDER BY stateid";
  $result=DB_query($sql,$db);
  $f=0;      
  
  while($myrow1=DB_fetch_array($result))
  {
  if ($myrow1['stateid']==14)
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
    echo $myrow1['stateid'] . '">'.$myrow1['state'];
    echo '</option>';
    $f++;
   }
  echo '</select>';
  echo'</td>'; 

 echo '<td id="showdistrict">District<select name="district" id="district" style="width:130px"">'; 
  $sql="SELECT * FROM bio_district WHERE stateid=14 ORDER BY did";
    $result=DB_query($sql,$db);
    $f=0;
  while($myrow1=DB_fetch_array($result))
  {
  if ($myrow1['did']==$_POST['district'])
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
    echo $myrow1['did'] . '">'.$myrow1['district'];
    echo '</option>';
    $f++;
   } 
  echo '</select>';
  echo'</td>';
  
echo"<td>Select<select name='selection' id='selection' style='width:130px'>";

echo"<option value=1>Saleorder</option>"; 
echo"<option value=2>Installed</option>"; 
echo"<option value=3>Followup calls completed</option>";
echo"</select></td>"; 


echo'<td>Office<select name="office" id="office" style="width:150px">';   
echo'<option value=0></option>';
    $sql="SELECT * FROM bio_office";
    $result=DB_query($sql,$db);
    
    while($row=DB_fetch_array($result))
    {
        if ($row['id']==$_POST['office'] )
        {
            echo '<option selected value="';
        } else {
            echo '<option value="';
        }
        echo $row['id'] . '">'.$row['office'];
        echo '</option>';
    }
    echo'</select></td>';   
  
 


  
  echo '<td align=right><input type=submit name=filter value=Search></td>'; 

echo '</tr></table>';
echo '<br />';

if(isset($_POST['filter']))
{

if($_POST['selection']==1){
   $sql="SELECT debtorsmaster.name,
                custbranch.phoneno,
                custbranch.faxno,
                salesorders.orddate
           FROM salesorders
      LEFT JOIN debtorsmaster 
             ON debtorsmaster.debtorno=salesorders.debtorno
      LEFT JOIN custbranch ON debtorsmaster.debtorno=custbranch.debtorno
      LEFT JOIN bio_installation_status 
             ON bio_installation_status.orderno=salesorders.orderno
      LEFT JOIN bio_district 
           ON (`debtorsmaster`.`did` = `bio_district`.`did`) 
          AND (`bio_district`.`cid` = `debtorsmaster`.`cid`) 
          AND (`debtorsmaster`.`stateid` = `bio_district`.`stateid`)
          WHERE salesorders.currentstatus!=5"; 
       
//    if($_POST['district']!=""){
//       
 //      $sql.=" WHERE (debtorsmaster.cid=".$_POST['country'].") AND (debtorsmaster.stateid=".$_POST['state'].")  AND (debtorsmaster.did=".$_POST['district'].")";       
//   }   // WHERE     salesorders.orderno NOT IN (SELECT orderno FROM bio_installation_status)    

}elseif($_POST['selection']==2){
$sql="SELECT debtorsmaster.name,custbranch.phoneno,custbranch.faxno,salesorders.orddate,bio_installation_status.installed_date 
        FROM bio_installation_status  
  INNER JOIN salesorders ON bio_installation_status.orderno=salesorders.orderno  
  INNER JOIN debtorsmaster ON debtorsmaster.debtorno=salesorders.debtorno 
  INNER JOIN custbranch ON debtorsmaster.debtorno=custbranch.debtorno       
   LEFT JOIN bio_district ON (`debtorsmaster`.`did` = `bio_district`.`did`) AND (`bio_district`.`cid` = `debtorsmaster`.`cid`) AND (`debtorsmaster`.`stateid` = `bio_district`.`stateid`)
   WHERE salesorders.currentstatus!=5";   
//   if($_POST['district']!=""){
//$sql.=" WHERE debtorsmaster.cid=".$_POST['country']." AND debtorsmaster.stateid=".$_POST['state']."  AND debtorsmaster.did=".$_POST['district']."";       
//   }
}elseif($_POST['selection']==3){
$sql="SELECT debtorsmaster.name,custbranch.phoneno,custbranch.faxno,salesorders.orddate,bio_installation_status.installed_date,bio_calllog.call_date 
        FROM bio_calllog     
   LEFT JOIN bio_installation_status ON bio_calllog.orderno=bio_installation_status.orderno
   LEFT JOIN salesorders ON bio_installation_status.orderno=salesorders.orderno
  INNER JOIN debtorsmaster ON debtorsmaster.debtorno=salesorders.debtorno  
  INNER JOIN custbranch ON debtorsmaster.debtorno=custbranch.debtorno   
   LEFT JOIN bio_district ON (`debtorsmaster`.`did` = `bio_district`.`did`) AND (`bio_district`.`cid` = `debtorsmaster`.`cid`) AND (`debtorsmaster`.`stateid` = `bio_district`.`stateid`)
   WHERE salesorders.currentstatus!=5";    
//   if($_POST['district']!=""){
//$sql.=" WHERE debtorsmaster.cid=".$_POST['country']." AND debtorsmaster.stateid=".$_POST['state']."  AND debtorsmaster.did=".$_POST['district']."";       
//   }
} 
//echo$sql;

   if ( $_POST['office']!=0)
   {  
   if($_POST['country']==1 AND $_POST['state']==14)  
   {
   if ($_POST['office']==1){                                   
         $sql .=" AND debtorsmaster.did IN (6,11,12)";  
   }else if ($_POST['office']==2){                                   
         $sql .=" AND debtorsmaster.did IN (1,2,3,7,13)";                
   }else if ($_POST['office']==3){ 
         $sql .=" AND debtorsmaster.did IN (4,5,8,9,10,14)";                 
   }else if ($_POST['office']==4){ 
         $sql .=" AND debtorsmaster.did IN (6,11,12)";                       
         }      
       }
     }

    

$result=DB_query($sql,$db);

echo"<table width=800px;><tr>";
function tilte($tl)
{
echo "<font size='-1' style='margin-left:20%'><b>Total Count(".$tl.")<b/></font>";     
}
echo"<th>Slno</th><th>Customer Name</th><th>Contact No</th><th>Order date</th>";  
if($_POST['selection']!=1){
        echo"<th>Installation Date</th>";
} 
if($_POST['selection']==3){
        echo"<th>Call Date</th>";
} 
//echo"<th>Staus</th>"; 

$slno=0;
$slno1=0;
while($myrow=DB_fetch_array($result))
{
$slno++;
    
    if($myrow['faxno']==""){
       $contactno=$myrow['phoneno']; 
    }else{
       $contactno=$myrow['faxno']; 
    }
    if($myrow['orddate']!="")  {  
    $orderdate=ConvertSQLDate($myrow['orddate']);
    }
    if($myrow['installed_date']!="")  {
    $installeddate=ConvertSQLDate($myrow['installed_date']);
    }
    if($myrow['call_date']!="")  {      
    $calldate=ConvertSQLDate($myrow['call_date']); 
    }
                      if ($k==1)
                      {
                        echo '<tr class="EvenTableRows">';
                        $k=0;
                      }else 
                      {
                        echo '<tr class="OddTableRows">';
                        $k=1;     
                      }
                      
    echo"<td>$slno</td><td>$myrow[name]</td><td>$contactno</td><td>$orderdate</td><td>$installeddate</td>";   
    if($_POST['selection']==3){
        echo"<td>$calldate</td>";
    }$slno1++;
} 
tilte($slno1);      
}


?>


<script type="text/javascript"> 

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