<?php
$PageSecurity = 80;
include('includes/session.inc');



$title = _('Leads');
include('includes/header.inc');

 ?>
 <script type="text/javascript">

function showCD(str)
{
   //alert("hiii");
      //   alert(str);  
//$(document).ready(function(){  
 
if (str=="")
  {
  document.getElementById("showsource").innerHTML="";
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
    document.getElementById("showsource").innerHTML=xmlhttp.responseText;
    }
  } 
  //alert(str);
xmlhttp.open("GET","Getsource.php?q="+str,true);
xmlhttp.send(); 
$("#hidetable").hide();  
}


function showCD1(str)
{
   //  alert(str);
  // $("# sourcedetails").show(); 
// $("# hidetr").show();

if (str=="")
  {
  document.getElementById("sourcedetails").innerHTML="";
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
        
    document.getElementById("sourcedetails").innerHTML=xmlhttp.responseText;
      $('#dinhide').show(); 
    }
  } 
  //alert(str);
xmlhttp.open("GET","Sourcedetails.php?q="+str,true);
xmlhttp.send(); 
 
}

function showCD2(str1)
{
   //alert("hii");
   //alert(str1);
if (str1=="")
  {
  document.getElementById("editleads").innerHTML="";
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
    document.getElementById("editleads").innerHTML=xmlhttp.responseText;
    document.getElementById('loc').focus(); 
    }
  } 
xmlhttp.open("GET","editleads.php?q=" + str1,true);
xmlhttp.send();    

}

function showCD4()
{var str1=document.getElementById("feedstock").value;
var str2=document.getElementById("weight").value;
//   alert("hii");
//   alert(str1);
if(str1==""){
alert("select a Feedstock"); document.getElementById("feedstock").focus();  return false;  }
if (str1=="")
  {
  document.getElementById("editleads").innerHTML="";
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
    document.getElementById("feedstockdiv").innerHTML=xmlhttp.responseText;
//    document.getElementById('loc').focus(); 
    }
  } 
xmlhttp.open("GET","Sourcedetails.php?feedstock=" + str1  + "&weight=" + str2 ,true);
xmlhttp.send();    

}

function editfeedstok(str)
{
   //alert("hii");
//  alert("val="+ str);


//var str1=document.getElementById("hfeedstock").value;
//var str2=document.getElementById("hfeedweight").value;

if (str=="")
  {
  document.getElementById("editfeed").innerHTML="";
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
    document.getElementById("editfeed").innerHTML=xmlhttp.responseText;
//    document.getElementById('loc').focus(); 
    }
  } 
xmlhttp.open("GET","Sourcedetails.php?upfeedstockid=" + str,true);
xmlhttp.send();    

}


function doedit()
{
//   alert("hii");
//   alert(str);

var str=document.getElementById("fdstk").value;    
var str1=document.getElementById("h1feedstock").value;
var str2=document.getElementById("h1feedweight").value;
// alert(str); alert(str1);     alert(str2); 
if (str=="")
  {
  document.getElementById("feedstockdiv").innerHTML="";
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
    document.getElementById("feedstockdiv").innerHTML=xmlhttp.responseText;
    $('#h1feedweight').focus(); 
    }
  } 
xmlhttp.open("GET","Sourcedetails.php?edid=" + str + "&edfd=" + str1 + "&edwt=" + str2,true);
xmlhttp.send();    

}    
function deletfeedstok(str)
{
//   alert("hii");
//   alert(str);


// alert(str); alert(str1);     alert(str2); 
if (str=="")
  {
  document.getElementById("feedstockdiv").innerHTML="";
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
    document.getElementById("feedstockdiv").innerHTML=xmlhttp.responseText;

    }
  } 
xmlhttp.open("GET","Sourcedetails.php?delet=" + str,true);
xmlhttp.send();    

}
</script>
<?php  

if(!isset($_POST['submit'])){  
$tempdrop="DROP TABLE IF EXISTS bio_feedtemp";
DB_query($tempdrop,$db);   
$temptable="CREATE TABLE bio_feedtemp (
temp_id INT NOT NULL AUTO_INCREMENT ,
feedstockid INT NULL ,
weight DECIMAL NULL ,
PRIMARY KEY ( temp_id )
)";
DB_query($temptable,$db);  
 }
 
 if(isset($_POST['submit']))
 {
    //exit;
      unset($delleadid); 
      $date=date("Y-m-d");
      $custname=$_POST['custname'];
      $address=$_POST['address'];
      $phone=$_POST['phone'];
      $mobile=$_POST['mobile'];  
      $email=$_POST['email'];
      $teamid=$_SESSION['teamid'];
      $productservicesid=$_POST['productservices'];
      $enquiryid=$_POST['enquiry'];
      $investmentsize=$_POST['investmentsize']; 
      $schemeid=$_POST['scheme'];
      $feedstockid=$_POST['feedstock'];
      $rmkg=$_POST['rmkg'];  
      $outputtypeid=$_POST['outputtype'];
      $sourcetype=$_POST['sourcetype'];
      $sourceid=$_SESSION['sourceid'];
//      echo $POST_['Houseno'];
//      exit;
      if($_POST['advanceamt']=='')
      {
          $advanceamt=0; 
      }
      else
      {
          $advanceamt=$_POST['advanceamt'];
      }
      if($_POST['Houseno']==""){$_POST['Houseno']=0;}    
      if($_POST['HouseName']==""){$_POST['HouseName']=0;}
      if($_POST['Area1']==""){$_POST['Area1']=0;} 
      if($_POST['Area2']==""){$_POST['Area2']=0;} 
      if($_POST['Pin']==""){$_POST['Pin']=0;}
      if($_POST['Nationality']==""){$_POST['Nationality']=0;}    
      if($_POST['State']==""){$_POST['State']=0;}
      if($_POST['District']==""){$_POST['District']=0;}  
            if($phone==""){$_POST['phone']=0;}      
                  if($mobile==""){$_POST['mobile']=0;}      
                        if($email==""){$_POST['email']=0;}                     
      $status=$_POST['status'] ;
      $remarks=$_POST['remarks'];
       //echo "sssssssssss".$_POST['Area2']."nnnnnnnnnnnnnnnn";
     $sqlcust="INSERT INTO `bio_cust` (
    `custname`, 
    `custphone`, 
    `custmob`, 
    `custmail`, 
    `houseno`,
    `housename`,
    `area1`,
    `area2`,
    `pin`,                                                                                                                                                                                                                             
    `nationality`,
    `state`,
    `district`) 
    VALUES ('$custname','$phone','$mobile','$email','".$_POST['Houseno']."','".$_POST['HouseName']."',
   '".$_POST['Area1']."','".$_POST['Area2']."',".$_POST['Pin'].",'".$_POST['Nationality']."','".$_POST['State']."','".$_POST['District']."')";
  //$result=DB_query($sql, $db);  
//  exit;
             $ErrMsg =  _('An error occurred inserting the new Sales Leads record because');
           $Dbgmsg =  _('The SQL used to insert the Sales Leads record was');
           $result = DB_query($sqlcust,$db,$ErrMsg,$DbgMsg);
  prnMsg( _('The Sales Leads record has been added'),'success');                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         
     //exit;
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------     
 $custid=DB_Last_Insert_ID(&$Conn,'bio_feedtemp','temp_id'); 
//---------------------------------------------------------------------------------------------------------------------------------------------------------------------
      $sql="INSERT INTO `bio_leads` (
    `leaddate`, 
    `teamid`,
    `sourceid`, 
    `productservicesid`, 
    `enqtypeid`, 
    `investmentsize`, 
    `schemeid`, 

    `rmdailykg`, 
    `outputtypeid`, 
    `advanceamount`, 
    `remarks`, 
    `status`,`cust_id`) 
    VALUES ('$date',".$teamid.",".$sourceid.",".$productservicesid.",".$enquiryid.",'$investmentsize',".$schemeid.",'$rmkg',".$outputtypeid.",".$advanceamt.",'$remarks','$status',$custid)";
  //$result=DB_query($sql, $db);  
//  exit;
             $ErrMsg =  _('An error occurred inserting the new Sales Leads record because');
           $Dbgmsg =  _('The SQL used to insert the Sales Leads record was');
           $result = DB_query($sql,$db,$ErrMsg,$DbgMsg);
  prnMsg( _('The Sales Leads record has been added'),'success');                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         
     //exit;                                                                                                  
 $custid=DB_Last_Insert_ID(&$Conn,'bio_feedtemp','temp_id');      
$sql="INSERT INTO bio_leadfeedstocks(SELECT $custid,bio_feedtemp.feedstockid,bio_feedtemp.weight FROM bio_feedtemp)";
$result1=DB_query($sql, $db);   
 $tempdrop="DROP TABLE IF EXISTS bio_feedtemp";
DB_query($tempdrop,$db); 

$temptable="CREATE TABLE bio_feedtemp (
temp_id INT NOT NULL AUTO_INCREMENT ,
feedstockid INT NULL ,
weight DECIMAL NULL ,
PRIMARY KEY ( temp_id )
)";
DB_query($temptable,$db);     
 }
 if(isset($_GET['Delete']))
 {
 $delleadid=$_GET['Num'];
 
//if(  $_POST['leadid1'])
//{      echo "hii";
//    echo   $leadid;
//}
    
      $sql="DELETE FROM bio_leads WHERE leadid=".$delleadid;
// $result=DB_query($sql,$db);
              $ErrMsg =  _('An error occurred inserting the new Sales Leads record because');
           $Dbgmsg =  _('The SQL used to insert the Sales Leads record was');
           $result = DB_query($sql,$db,$ErrMsg,$DbgMsg);
  prnMsg( _('The Sales Leads record has been Deleted'),'success');
  //unset($delleadid); 
      //display($db);
 }
 if(isset($_POST['edit']) and isset($_POST['leadid']))
 {
    $sourcetype=$_POST['sourcetype'];
   $leadid= $_POST['leadid']; 
 $_POST['customerid'];   
    // echo "hi";

  //echo $_POST['advanceamt'];

$sql="UPDATE `bio_cust` SET`custname` = '".$_POST['custname']."',
        `houseno` = '".$_POST['Houseno']."',      
        `housename` ='".$_POST['HouseName']."',
        `area1` = '".$_POST['Area1']."',      
        `area2` ='".$_POST['Area2']."',
        `pin` = '".$_POST['Pin']."',      
        `nationality` ='".$_POST['Nationality']."',
        `state` ='".$_POST['State']."',
        `district` = '".$_POST['District']."',
        `custphone` = ".$_POST['phone'].",     
        `custmob` = ".$_POST['mobile'].",
        `custmail` = '".$_POST['email']."' WHERE `bio_cust`.`cust_id` ='".$_POST['customerid']."'";          
           $result = DB_query($sql,$db,$ErrMsg,$DbgMsg);


$sql="UPDATE `bio_leads` SET  
        `sourceid` = ".$_POST['source'].",
        `productservicesid` =".$_POST['productservices'].",
        `enqtypeid` = ".$_POST['enquiry'].",

        `outputtypeid` = ".$_POST['outputtype'].",
        `rmdailykg` = '".$_POST['rmkg']."',
        `advanceamount` = ".$_POST['advanceamt'].",
        `investmentsize` = '".$_POST['investmentsize']."',
        `remarks` = '".$_POST['remarks']."',
        `status` = '".$_POST['status']."' WHERE `bio_leads`.`leadid` =$leadid";
      //  $result=DB_query($sql,$db);
                    $ErrMsg =  _('An error occurred inserting the new Sales Leads record because');
           $Dbgmsg =  _('The SQL used to insert the Sales Leads record was');
           $result = DB_query($sql,$db,$ErrMsg,$DbgMsg);
  prnMsg( _('The Sales Leads record has been Updated'),'success');
      // display($db);
 } //=======================================================================================================25-7
//  if(isset($_POST['upfeedstcks1'])){ echo"sssssssssssssssssssss".$_POST['h1feedstock'];exit;}    
 
 function display($db)  
 {      //style='float:left;width:95%;height:415px'
     echo "<fieldset style='float:right;width:95%;'>";     
    echo "<legend><h3>Created Leads Details</h3>";
    echo "</legend>";
      
      
      
      echo "<div class='sortable' border=0 width=100%>"; 
      echo "<div style='height:100px; overflow:auto;'>";
      echo "<table  style='width:100%;'>";
             
   echo "<thead>
                <tr BGCOLOR =#800000><th>" . _('Sl no:') . "</th>
                <th>" . _('Customer Name') . "</th>
                <th>" . _('Date') . "</th>
                <th>" . _('Enquiry Type') . "</th>
                <th>" . _('Output Type') . "</th>
                <th>" . _('Feed Stock') . "</th>                 
                <th>" . _('Team Name') . "</th></tr></thead>";
            //echo '<td></td>' ;
       //echo $count;
       $sql3="SELECT count(*) FROM bio_leads";
       $result3=DB_query($sql3,$db);
      $count=DB_fetch_row($result3);
      
 $sql="SELECT bio_leads.leadid,bio_leads.leaddate,bio_leads.enqtypeid,bio_leads.outputtypeid,bio_leads.teamid,bio_cust.custname FROM bio_leads,bio_cust WHERE bio_leads.cust_id=bio_cust.cust_id ";
      $result=DB_query($sql,$db);
     // $count=DB_fetch_row($result); 
     //print_r($count);
    echo '<tbody>';
    echo '<tr>';                                       
    $no=0; 
      if($count>0)
      {
          $k=0; 
          while($myrow=DB_fetch_array($result))
          {    //echo $myrow[0];
               if ($k==1)
                {
                    echo '<tr class="EvenTableRows">';
                    $k=0;
                    
                }
                 else 
                 {
                    echo '<tr class="OddTableRows">';
                    $k=1;     
                 }
              $no++;
                
                 
                
                $sql1="SELECT enquirytype FROM bio_enquirytypes WHERE enqtypeid=".$myrow['enqtypeid'];
                $result1=DB_query($sql1, $db);
                $myrow1=DB_fetch_array($result1);                
                $sql2="SELECT outputtype FROM bio_outputtypes WHERE outputtypeid=".$myrow['outputtypeid'];
                $result2=DB_query($sql2, $db);
                $myrow2=DB_fetch_array($result2);                
                $sql3="SELECT teamname FROM bio_leadteams WHERE teamid=".$myrow['teamid'];
                $result3=DB_query($sql3, $db);
                $myrow3=DB_fetch_array($result3);
//                $sql4="SELECT feedstocks FROM bio_feedstocks WHERE id=".$myrow['feedstockid'];
//                $result4=DB_query($sql4, $db);
//                $myrow4=DB_fetch_array($result4);
                
                 //echo  $myrow[0];
//                echo '<td>'.$no.'</td>';             
//                echo "<td>".$myrow['custname']."</td>";
//                echo '<td>'.$myrow['leaddate'].'</td>';
//                echo '<td>'.$myrow1['enquirytype'].'</td>'; 
//                echo '<td>'.$myrow2['outputtype'].'</td>';  
//                echo '<td>'.$myrow3['teamname'].'</td>';
//                echo "<td><a  style='cursor:pointer;' id=editleads>" . _('Edit') . "</a></td>";  
//                echo "<td><a href='onclick=delete('$myrow[0]')'>" . _('Delete') . "</a></td>";
//                echo '</tr>';
                  $leadid=$myrow[0]; 
          echo "<input type=hidden name=leadid value='$leadid'>";
          
printf("<td cellpading=2>%s</td>
        <td>%s</td>
        <td>%s</td>
        <td>%s</td>
        <td>%s</td>
         <td>%s</td>

         
        <td><a  style='cursor:pointer;'  onclick=showCD2('$leadid')>" . _('Edit') . "</a></td>  
        <td><a href='%sNum=%s&Delete=1'>" . _('Delete') . '</td>
        </tr>',
        
        $no,
        $myrow['custname'],
        $myrow['leaddate'],
        $myrow1['enquirytype'],
        $myrow2['outputtype'],

        $myrow3['teamname'], 
        $_SERVER['PHP_SELF'] . '?' . SID,
        $myrow[0],
        $_SERVER['PHP_SELF'] . '?' . SID,$myrow[0]);
          }
          
      }
      echo '</tbody>';
      
      echo '</table>';  
      echo '</div>';  
      echo '</div>';
      echo "</fieldset>";
 }
    //echo " <div style='width:100%'>";
   
//if(isset($_POST['weight']))    
//function addfeedstock()
//   {
//       echo "hi";
//       $sql1="SELECT leadid FROM bio_leads ORDER BY id DESC";
//       $result1=DB_query($sql1, $db);
//       $myrow1=DB_fetch_array($result1);
//       $lid=$myrow1[0];
//       echo $lid;
//   }    
   // echo "<div style='width:110%;height:230%;'>";           
   echo '<center><font style="color: #333;
    background:#fff;
    font-weight:bold;
    letter-spacing:0.10em;
    font-size:16px;
    font-family:Georgia;
    text-shadow: 1px 1px 1px #666;">LEADS</font></center>'; 
    echo "<form action=" . $_SERVER['PHP_SELF'] . "?" . SID ." method=post>";
     echo "<div id=editleads>";  

    
     
    echo "<table border=0 style='width:55%;height:150%;'>"; 
       
    echo "<tr><td style='width:50%'>";
    //Customer Details Fieldset.............................Customer Details Fieldset...............................Customer Details Fieldset 
    
    //echo "<div>";  
    echo "<fieldset style='float:left;width:95%;height:463px'>";     
    echo "<legend><h3>Customer Details</h3>";
    echo "</legend>";     
    echo "<table>";  
    //Customer Details
    echo "<tr><td>Customer Name</td>";
    echo "<td><input type='text' name='custname' id='custid' onkeyup='caps1()' style='width:99%'></td>";
    
        echo "<tr><td>House No:</td><td><input type='text' name='Houseno' id='Houseno' onkeyup='' style='width:99%'></td></tr>";    
        echo "<tr><td>House Name</td><td><input type='text' name='HouseName' id='HouseName' onkeyup='' style='width:99%'></td></tr>";    
        echo "<tr><td>Area 1:</td><td><input type='text' name='Area1' id='Area1' onkeyup='' style='width:99%'></td></tr>";    
        echo "<tr><td>Area 2:</td><td><input type='text' name='Area2' id='Area2' onkeyup='' style='width:99%'></td></tr>";    
        echo" <tr><td>Pin:</td><td><input type='text' name='Pin' id='Pin' onkeyup='' style='width:99%'></td></tr>";    
        echo "<tr><td>Nationality:</td><td><select name='Nationality' id='Nationality' style='width:99%'><option value='INDIA'>INDIA</option></select></td></tr>" ;
//        echo"<input type='text'  id='Nationality' onkeyup='' style='width:99%'></td></tr>";
            
//        echo "<tr><td>State:</td><td><input type='text' name='State' id='State' onkeyup='' style='width:99%'></td></tr>"; 
        echo "<tr><td>State:</td><td><select name='State' id=' State'style='width:99%'><option value='Kerala' >Kerala</option></select></td></tr>" ;    
//        echo "<tr><td>District:</td><td><input type='text' name='District' id='District' onkeyup='' style='width:99%'></td>"; 
echo "<tr><td>District:</td><td><select name='District' id=' Districts'style='width:99%'>
<option value='Thiruvananthapuram' >Thiruvananthapuram</option><option value='Kollam' >Kollam</option></select></td></tr>" ;         
    echo '</tr>';
    echo '<tr><td>Phone number</td>';
    echo "<td><input type=text name=phone id=phone style='width:98%'></td></tr>";
    echo '<tr><td>Mobile Number</td>';
    echo "<td><input type=text name=mobile id=mobile style='width:98%'></td></tr>";
    echo '<tr><td>Email id</td>';
    echo "<td><input type=text name='email' id='email' style='width:98%'></td></tr>";
    //Product Sevices


      echo '<tr><td>Product Services</td><td>';
  $sql1="SELECT * FROM bio_productservices";
  $result1=DB_query($sql1, $db);
  echo '<select name=productservices style="width:190px">';
  while($myrow1=DB_fetch_array($result1))
  {  
  if ($myrow1['id']==$_POST['productservices'])  
    { 
    echo '<option selected value="';
    } else 
    {
        echo '<option value="';
    }
    echo $myrow1['id'] . '">'.$myrow1['productservices'];
    echo '</option>';
   } 
  echo '</select>'; 
  echo '</td>'; 
  echo '</tr>';

   
  //Enquiry type
    echo '<tr><td>Enquiry Type</td>';
    echo  '<td>';
    echo '<select name="enquiry" style="width:190px">';
    $sql1="SELECT * FROM bio_enquirytypes"; 
    $result1=DB_query($sql1,$db);
    while($myrow1=DB_fetch_array($result1))
    { 
    if ($myrow1['enqtypeid']==$_POST['enquiry']) 
    {
       
    echo '<option selected value="';
    
    } else {
        echo '<option value="';
    }
    echo $myrow1['enqtypeid'] . '">'.$myrow1['enquirytype'];
    echo '</option>';
    }
     
    echo '</select>';    
    echo '</td></tr>';

    
    
      //Feedstock
//    echo '<tr><td>Feed Stock</td><td>';
//     
//  $sql1="SELECT * FROM bio_feedstocks";
//  $result1=DB_query($sql1, $db);
//  echo '<select name=feedstock style="width:190px">';
//  while($myrow1=DB_fetch_array($result1))
//  {  
//  if ($myrow1['id']==$_POST['feedstock']) 
//    {
//    echo '<option selected value="';
//    
//    } else {
//        echo '<option value="';
//    }
//    echo $myrow1['id'] . '">'.$myrow1['feedstocks']; 
//    echo '</option>' ;
//   }
//  echo '</select></td>';
//  echo '</tr>';

   //output types
  echo '<tr><td>Output Type</td><td>';
  $sql1="SELECT * FROM bio_outputtypes";
  $result1=DB_query($sql1, $db);
  echo '<select name=outputtype style="width:190px">';
  while($myrow1=DB_fetch_array($result1))
  {  
  if ($myrow1['outputtypeid']==$_POST['outputtype']) 
    {
    echo '<option selected value="';
    
    } else 
    {
        echo '<option value="';
    }
    echo $myrow1['outputtypeid'] . '">'.$myrow1['outputtype'];  
    echo '</option>';
  }
  echo '</select></td>';
  echo '</tr>';

    //Raw Materials
  echo '<tr><td>Raw Materials(Kg.)</td>';
  echo "<td><input type=text name=rmkg style='width:98%'></td></tr>"; 
  //Advance amount
  echo '<tr><td>Advance Amount</td>';
    echo "<td><input type=text name=advanceamt id=advance style='width:98%'></td></tr>";
  echo "</table>";  
  echo "</fieldset>";   
 // echo '</div>';
  echo "</td>";
  
  
   //Leads details fieldset .................................Leads details fieldset.....................Leads details fieldset 
   
   
  echo "<td style='width:55%'>";
// echo "<div>";
  echo "<fieldset style='float:right;width:94%;height:463px'>";       
  echo "<legend><h3>Leads Details</h3>";
  echo "</legend>";
  
  echo "<table border=0 style='width:100%'>";
   echo '<tr><td>LeadSource Type</td>';
  echo '<td>'; 

  echo '<select name=sourcetype id="sourcetype" style="width:192px" onkeyup=showCD(this.value) onchange=showCD(this.value) onclick=showCD(this.value)>';
  $sql="SELECT * FROM `bio_leadsourcetypes`";
  $result=DB_query($sql,$db); 
  echo $count=DB_fetch_row($sql,$db);
  
  while ($myrow = DB_fetch_array($result)) {
     $c=0;
    if ($myrow['id']==$_POST['sourcetype']) 
    {
    echo '<option selected value="';
    } else {
        echo '<option value="';
    }
    echo $myrow['id'] . '">'.$myrow['leadsourcetype'];     
   echo '</option>';
   $c++;
    }         
    echo $c;
    echo '</select></td></tr>';

   $d=9; 
   
//   echo '<tr><td colspan=2><div  id=hidetr>' ;
   echo '<tr id=showsource>';     

   
   
   
   echo '</tr>';
   echo "<tr><td colspan=2 style='width:44%;align=left;'>";
   

   echo '<div id="dinhide">';
   
   echo '<div id=sourcedetails class=sourcedetails>';    
   echo '</div>'; 
   
   echo '</div>'; 
//   echo '</td></tr>';  
    echo '</tr>';
     
    // echo '</div></tr>' ;
   // echo '</tr>' ; 
   // echo '<td>';
     


    echo '<tr><td>Investment Size</td>';
    echo "<td><input type=text name=investmentsize id=invest style='width:80%'></td>";
    echo '</tr>';
    
     //Scheme
    
    echo '<tr><td>Scheme</td><td>';
$sql="SELECT * FROM bio_schemes";
  $result=DB_query($sql, $db);
  echo '<select name=scheme style="width:192px">';
  
  while($myrow=DB_fetch_array($result))
  {  
  if ($myrow['schemeid']==$_POST['scheme'])
    {
        echo '<option selected value="';
    }
  else
   {       
        echo '<option value="';
   }
     echo $myrow['schemeid'] . '">'.$myrow['scheme'];     
     echo '</option>';
  }
    echo '</select></td>';
    echo '</tr>'; 
  
      //Status 
    echo '<tr><td>Status</td>';
    echo "<td><input type=text name=status style='width:80%'></td>";
    echo '</tr>';
    //Remarks
    
     echo '<tr><td>Select a Remark</td>';
    echo'<td>';
    echo '<select name="RemarkList" id="remarklist" style="width:192px">';
        echo '<option value="" selected></option>';
  $sql="SELECT * FROM bio_remarks";
  $result=DB_query($sql,$db); 
  echo $count=DB_fetch_row($sql,$db);
      
  while ($myrow = DB_fetch_array($result)) {

    echo '<option value="';
    echo $myrow['remark'] . '">'.$myrow['remark'];     
    echo '</option>';

    }         
    echo '</select>';
    
    echo'</td>';
    echo'</tr>';
    echo'<tr><td>Remarks</td>';
    echo "<td><textarea name=remarks id=remarks rows=4 cols=26 style=resize:none;></textarea></td>";     echo "</table>"; 
    echo "</fieldset>"; 
  //echo '</form>'; 
//echo '</td></tr>';
    //Feedstock   

 
 

echo "<tr><td colspan='5'>";
  echo "<fieldset style='width:700px'>";   
   echo "<legend><h3>Feed Stock Details</h3>";
    echo "</legend>"; 
  echo "<table style='align:left' border=0>";
  echo "<tr><td>Feed Stock</td>";
//Feedstock
    echo '<td>';

 $sql1="SELECT * FROM bio_feedstocks";
  $result1=DB_query($sql1, $db);
  echo '<select name=feedstock id="feedstock" style="width:190px">';
  $f=0;
  while($myrow1=DB_fetch_array($result1))
  {  
  if ($myrow1['id']==$_POST['feedstock']) 
    {
    echo '<option selected value="';
    
    } else {
        if($f==0){echo '<option>';   }
        echo '<option value="';
    }
    echo $myrow1['id'] . '">'.$myrow1['feedstocks']; 
    echo '</option>' ;
   $f++; 
   }
  echo '</select>';
  echo "</td>";
  echo "<td>Weight in Kg</td>";
  echo "<td><input type=text name='weight' id='weight' style='width:83%'></td>";
  echo "<td>";
 echo '<input type="button" name="addfeedstock" id="addfeedstock" value="Add" onclick="showCD4()">';
//  echo '<input type="button" name="addfeedstock" id="addfeedstock" value=Add>';
  echo "</td>";
  
  echo "</tr>";
  
  

echo "</table>";
echo"<div id='editfdstok'></div>";
echo"<div id='feedstockdiv'></div>";
//==================================================


//=====================================================
//    echo $_POST['h1feedstock'];
  echo "</fieldset>";
echo "</td></tr>";




  echo '</div>';   
echo '<tr><td colspan=3><center>';  
echo '<input type=submit name="submit" id="leads" value=Submit  onclick="if(log_in()==1)return false;">';
echo '<a href="Createleads.php"><input type=reset value=Clear ></a>';     
  echo '</center></td>';    
  echo '</tr>';


   echo '<tr>'; 
  echo '<td colspan=3>';
        
  echo '</td>';  
  echo '</tr>'; 
  echo '</table>';
//  echo "</div>";
   echo '</div>'; 
   echo "<table style='width:55%;height:150%;' border=0><tr><td>";  
   display($db);
   
   
   
   
   echo "</td></tr></table>";
   echo '</form>';         
 // echo '</div>'; 

 include('includes/footer.inc'); 
?>    
<script language="javascript">
 document.getElementById('custid').focus(); 
  $(document).ready(function() {


 $('#sourcetype').change(function() {
  $('#dinhide').hide();
}); 
   }); 
//    $('#leads').click(function() { 

//    f=validateemail('email','Please Enter valid email');  if(f==1) { return false; }   
//});

function displayVals() {
//     alert("sss");
      var multipleValues = document.getElementById("remarklist").value;
//      alert(multipleValues);
        document.getElementById("remarks").value=multipleValues;

    }
   
    
  $("#remarklist").change(displayVals);
    displayVals();


function caps1()
{
//   alert("sss");
UCWords('custid','Name should be begin with capital letter');
}
 function log_in()
{  //  alert("sss"); alert(mail);
var f=0;       


f=common_error('custid','Please Enter Customer Name');  if(f==1) { return f; }
f=common_error('Houseno','Please Enter Address');  if(f==1) { return f; }
f=common_error('phone','Please Enter Phone Number');  if(f==1) { return f; }
if(f==0){f=nmbr_chek('phone','Please enter Numbers only');  if(f==1){return f; }  }



}
//function log_in()
//{ var mail=document.getElementById('email').value; //  alert("sss"); alert(mail);
//var f=0;       
//var v=document.getElementById('custid').value;   
 //alert(v);
//if(v=="" || v=="select" || v==0)
//{
//alert("Please Enter Customer Name");if(f==1) { return f;};
//document.getElementById('custid').focus();

//}
//var v=document.getElementById('adres').value;   
 //alert(v);
//if(v=="" || v=="select" || v==0)
//{
//alert("Please Enter Address");
//document.getElementById('adres').focus();
//return false;
//}
//if(f==0){ f=Addresup('adres','Please');  if(f==1) { return false; }    }      

//var v=document.getElementById('phone').value;   
 //alert(v);
//if(v=="" || v=="select" || v==0)
//{
//alert("Please Enter Phone Number");
//document.getElementById('phone').focus();
//return false;
//}
//   
//var c=document.getElementById('phone').value;
//if(isNaN(c)==true)
//{
//    alert("Please Enter Numbers in the Phone Number Field"); 
//    document.getElementById('phone').value="";
//    document.getElementById('phone').focus();
//    return false; 
//}

//alert(mail);
//if(mail!=''){ var f=0;
//if(f==0){ f=validateemail('email','Please Enter valid email');  if(f==1) { return false; }    }   }

//  $('#addfeedstock').click(function() { var f=0;
//  var f=document.getElementById('phone').value; 
//  alert(f);
//   f=common_error('feedstock','Please Select the Feedstock');  if(f==1) { return false; }       
//}); 




//////////////////////
//if()
//var c=document.getElementById('mobile').value;  
//if(isNaN(c)==true)
//{
//    alert("Please Enter Numbers in the Mobile Number Field"); 
//    document.getElementById('mobile').value="";
//    document.getElementById('mobile').focus();
//    return false; 
//}




//var c=document.getElementById('rmkg').value;  
//if(isNaN(c)==true)
//{
//    alert("Please Enter Raw materials in kg(Numbers)"); 
//    document.getElementById('rmkg').value="";
//    document.getElementById('rmkg').focus();
//    return false; 
//}
//var c=document.getElementById('advance').value;  
//if(isNaN(c)==true)
//{
//    alert("Advance amount should be a number"); 
//    document.getElementById('advance').value="";
//    document.getElementById('advance').focus();
//    return false; 
//}
//var c=document.getElementById('invest').value;  
//if(isNaN(c)==true)
//{
//    alert("Investment size should be a number"); 
//    document.getElementById('invest').value="";
//    document.getElementById('invest').focus();
//    return false; 
//}

//alert(m);
//return m;

//f=common_error('custid','Please Enter Customer Name');  if(f==1) { return f; }
//f=common_error('adres','Please Enter Address');  if(f==1) { return f; }
//f=common_error('phone','Please Enter Phone Number');  if(f==1) { return f; }
//if(f==0){f=nmbr_chek('phone','Please enter Numbers only');  if(f==1){return f; }  }
//f=common_error('mobile','Please Enter Mobile Number');  if(f==1) { return f; }
//f=common_error('email','Please Enter Email Id');  if(f==1) { return f; }
//if(f==0){f=nonvalid('date1','Please avoid special characters');  if(f==1){return f; }  }  



</script>