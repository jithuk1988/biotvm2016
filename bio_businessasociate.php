<?php
$PageSecurity = 80;
include('includes/session.inc');



$title = _('Business Asssociate Master');
include('includes/header.inc');
 ?>
 <script type="text/javascript">

function feedupdte1(str,str1)
{
//  alert("hii"); 

//alert(str); 
//alert(str1);

//var str1=document.getElementById("hfeedstock").value;
//var str2=document.getElementById("hfeedweight").value;

if (str1=="")
  {
  document.getElementById("edittedsho").innerHTML="";
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
    {     // alert("ddd");
    document.getElementById("edittedsho").innerHTML=xmlhttp.responseText;
//    document.getElementById('loc').focus();
    }
  }
xmlhttp.open("GET","bio_sourcetypedetails.php?ledid=" + str1 + "&fed=" + str,true);
xmlhttp.send();

}
function dofeeedit(str1,str2,str3)
{
var str1=document.getElementById("feedleadid").value;
var str2=document.getElementById("biofeedstockid").value;
var str3=document.getElementById("fedwt").value;
// alert(str1);       alert(str2);        alert(str3);
if (str1=="")
  {

  document.getElementById("editact").innerHTML="";
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
    {      //alert("ddd");
    document.getElementById("editact").innerHTML=xmlhttp.responseText;
//    document.getElementById('loc').focus();
    }
  }
xmlhttp.open("GET","bio_sourcetypedetails.php?ediled=" + str1 + "&fedidedi=" + str2 + "&fedwt=" + str3,true);
xmlhttp.send();

}

function replace_html(id, content) {
    document.getElementById(id).innerHTML = content;
}
var progress_bar = new Image();
progress_bar.src = '4.gif';
function show_progressbar(id) {
    replace_html(id, '<img src="4.gif" border="0" alt="Loading, please wait..." />Loading...');
}
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
show_progressbar('showsource');
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

xmlhttp.open("GET","bio_getsource.php?q="+str,true);
xmlhttp.send(); 
$("#hidetable").hide();  
}


function showCD1(str)
{
   //  alert(str);
  // $("# sourcedetails").show(); 
// $("# hidetr").show();
$('#dinhide').show();
if (str=="")
  {
  document.getElementById("sourcedetails").innerHTML="";
  return;
  }
show_progressbar('sourcedetails');
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
      //$('#dinhide').show(); 
    }
  } 
  //alert(str);
xmlhttp.open("GET","bio_sourcetypedetails.php?q="+str,true);
xmlhttp.send(); 
 
}

function showCD2(str1,str2)
{
   //alert("hii");
//   alert(str2);   alert(str1);
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
xmlhttp.open("GET","bio_editleads.php?q=" + str1 + "&en=" + str2,true);
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
  document.getElementById("feedstockdiv").innerHTML="";     //editleads
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
    document.getElementById('feedstock').value="";       document.getElementById('weight').value="";
    }
  } 
xmlhttp.open("GET","bio_sourcetypedetails.php?feedstock=" + str1  + "&weight=" + str2 ,true);
xmlhttp.send();    

}

function editfeedstok(str)
{
   //alert("hii");



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
xmlhttp.open("GET","bio_sourcetypedetails.php?upfeedstockid=" + str,true);
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
xmlhttp.open("GET","bio_sourcetypedetails.php?edid=" + str + "&edfd=" + str1 + "&edwt=" + str2,true);
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
xmlhttp.open("GET","bio_sourcetypedetails.php?delet=" + str,true);
xmlhttp.send();    

}
</script>
<?php  
if(isset($_POST['clear'])){   
unset($_POST['feedstock']); 
unset($_POST['enquiry']); 
unset($_POST['outputtype']); 
unset($_POST['sourcetype']); 
unset($_POST['printsource']); 
unset($_POST['feedstock']); 
//unset($_POST['enquiry']); 


}


if(!isset($_POST['submit'])){  
$tempdrop="DROP TABLE IF EXISTS bio_feedtemp";
DB_query($tempdrop,$db);   
$temptable="CREATE TABLE bio_feedtemp (
temp_id INT NOT NULL AUTO_INCREMENT ,
feedstockid INT NULL ,
weight DECIMAL NULL ,
PRIMARY KEY ( temp_id )
)";
DB_query($temptable,$db);  $sql="ALTER TABLE `bio_feedtemp` ADD `status` INT NOT NULL DEFAULT '0'" ; DB_query($sql,$db);
 }
$did=DB_Last_Insert_ID($Conn,'bio_district','did');
$did=$did+1;
 if(($_POST['Districts'])!=""){  $dt=$_POST['Districts'];
 $st=$_POST['State'];
     $sql="INSERT INTO bio_district (bio_district.stateid,bio_district.did,bio_district.district) VALUES($st,$did,'$dt')";
    $result=DB_query($sql,$db);
 $_POST['District']=DB_Last_Insert_ID($Conn,'bio_district','did');


    }
 if(isset($_POST['submit']))
 {      
         $scheme=$_POST['schm'];
                            foreach($scheme as $id)  {
                              $sourcescheme.=$id.",";
                               } 
 $schemeid=substr($sourcescheme,0,-1) ;             
      unset($delleadid); 
      $date=date("Y-m-d");
      $custname=$_POST['custname'];
      $address=$_POST['address'];
      $phone=$_POST['code']."-".$_POST['phone'];
      $mobile=$_POST['mobile'];  
      $email=$_POST['email'];
      $teamid=$_SESSION['teamid'];
      $productservicesid=$_POST['productservices'];
      $enquiryid=$_POST['enquiry'];
      $investmentsize=$_POST['investmentsize']; $schemeid;
//      $schemeid=$_POST['scheme'];
      $feedstockid=$_POST['feedstock'];
//      $rmkg=$_POST['rmkg'];  
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
      if($_POST['country']==""){$_POST['country']=0;}    
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
   '".$_POST['Area1']."','".$_POST['Area2']."',".$_POST['Pin'].",'".$_POST['country']."','".$_POST['State']."','".$_POST['District']."')";
  //$result=DB_query($sql, $db);  
//  exit;
             $ErrMsg =  _('An error occurred inserting the new Sales Leads record because');
           $Dbgmsg =  _('The SQL used to insert the Sales Leads record was');
           $result = DB_query($sqlcust,$db,$ErrMsg,$DbgMsg);
//  prnMsg( _('The Sales Leads record has been added'),'success');                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         
     //exit;
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------     
 $custid=DB_Last_Insert_ID($Conn,'bio_feedtemp','temp_id');
//---------------------------------------------------------------------------------------------------------------------------------------------------------------------
      $sql="INSERT INTO `bio_leads` (
    `leaddate`, 
    `teamid`,
    `sourceid`, 
    `productservicesid`, 
    `enqtypeid`, 
    `investmentsize`, 
    `schemeid`, 
    `outputtypeid`, 
    `advanceamount`, 
    `remarks`, 
    `status`,`cust_id`) 
    VALUES ('$date','".$teamid."',".$sourceid.",'".$productservicesid."',".$enquiryid.",'$investmentsize','$schemeid',".$outputtypeid.",".$advanceamt.",'$remarks','$status',$custid)";        // exit;
  //$result=DB_query($sql, $db);  
//  exit;
             $ErrMsg =  _('An error occurred inserting the new Sales Leads record because');
           $Dbgmsg =  _('The SQL used to insert the Sales Leads record was');
           $result = DB_query($sql,$db,$ErrMsg,$DbgMsg);
  prnMsg( _('The Sales Leads record has been added'),'success');                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         
     //exit;                                                                                                  
 $custid=DB_Last_Insert_ID($Conn,'bio_feedtemp','temp_id');      
$sql="INSERT INTO bio_leadfeedstocks(SELECT $custid,bio_feedtemp.feedstockid,bio_feedtemp.weight,bio_feedtemp.status FROM bio_feedtemp)";
$result1=DB_query($sql, $db);   
 $tempdrop="DROP TABLE IF EXISTS bio_feedtemp";
DB_query($tempdrop,$db); 

$temptable="CREATE TABLE bio_feedtemp (
temp_id INT NOT NULL AUTO_INCREMENT ,
feedstockid INT NULL ,
weight DECIMAL NULL ,
PRIMARY KEY ( temp_id )
)";
DB_query($temptable,$db);  $sql="ALTER TABLE `bio_feedtemp` ADD `status` INT NOT NULL DEFAULT '0'" ; DB_query($sql,$db);    

       unset($_POST['feedstock']);
unset($_POST['enquiry']);
unset($_POST['outputtype']);
unset($_POST['sourcetype']);
unset($_POST['printsource']);
unset($_POST['feedstock']);
unset($_POST['productservices']);
unset($_POST['District']); unset($_POST['country']);
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
 {     $_POST['code'];
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
        `custphone` = '".$_POST['code']."-".$_POST['phone']."',     
        `custmob` = ".$_POST['mobile'].",
        `custmail` = '".$_POST['email']."' WHERE `bio_cust`.`cust_id` ='".$_POST['customerid']."'";         
           $result = DB_query($sql,$db,$ErrMsg,$DbgMsg);


$sql="UPDATE `bio_leads` SET  
        `sourceid` = ".$_POST['source'].",
        `productservicesid` =".$_POST['productservices'].",
        `outputtypeid` = ".$_POST['outputtype'].",
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
       unset($_POST['feedstock']);
unset($_POST['enquiry']);
unset($_POST['outputtype']);
unset($_POST['sourcetype']);
unset($_POST['printsource']);
unset($_POST['feedstock']);     unset($_POST['productservices']);             unset($_POST['District']);
 } //=======================================================================================================25-7
//  if(isset($_POST['upfeedstcks1'])){ echo"sssssssssssssssssssss".$_POST['h1feedstock'];exit;}    
 
 function display($db)  
 {      //style='float:left;width:95%;height:415px'
 echo"<table style='width:100%'><tr><td>";
     echo "<fieldset style='float:right;width:97%;'>";     
    echo "<legend><h3>Created Leads Details</h3>";
    echo "</legend>";
      
      
      
      echo "<div class='sortable' border=0 width=100%>"; 
      echo"<table style='border:1px solid #F0F0F0;width:100%'>";
      echo"<tr><td>Search By:-</td><td>Customer name:";
      
      echo"</td>";
      echo"<td><input type='text' name='byname' id='byname'></td>";

      
            echo"<td>Customer place:</td>";
      

      echo"<td><input type='text' name='byplace' id='byplace'></td>";
      echo"<td><input type='submit' name='filterbut' id='filterbut' value='search'></td>";
      echo"</tr>";
      
      echo"</table>";
      echo "<div style='height:100px; overflow:auto;'>";
      echo "<table  style='width:100%;' id='leaddetails'>";
             
   echo "<thead>
                <tr BGCOLOR =#800000><th>" . _('Sl no:') . "</th>
                <th>" . _('Customer Name') . "</th>
                <th>" . _('Date') . "</th>
                <th>" . _('Business Ass: Type') . "</th>   
                <th>" . _('BA Category Code') . "</th>
                <th>" . _('Enquiry Type') . "</th>
                <th>" . _('Output Type') . "</th>
                </tr></thead>";
            //echo '<td></td>' ;
       //echo $count;
       $sql3="SELECT count(*) FROM bio_leads";
       $result3=DB_query($sql3,$db);
      $count=DB_fetch_row($result3);
 
 
    
      
 $sql="SELECT bio_leads.leadid,bio_leads.leaddate,bio_leads.enqtypeid,bio_leads.outputtypeid,bio_leads.teamid,bio_cust.custname FROM bio_leads,bio_cust WHERE bio_leads.cust_id=bio_cust.cust_id ";
 if(isset($_POST['filterbut'])){  
            if (isset($_POST['byname']))  {        
              if ($_POST['byname']!='')   
              $sql .= " AND bio_cust.custname LIKE '%".$_POST['byname']."%'";   
           }           
            if (isset($_POST['byplace'])) {
              if ($_POST['byplace']!='') { //echo $_POST['srchnam'];
               $a=1;
              $sql .= " AND bio_cust.area1 LIKE '%".$_POST['byplace']."%'"; }
           }}
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
                $sql3="SELECT teamname FROM bio_leadteams WHERE teamid='".$myrow['teamid']."'";
//                $result3=DB_query($sql3, $db);
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
                  $leadid=$myrow[0]; $enq=$myrow[2]; 
          echo "<input type=hidden name=leadid value='$leadid'>";
          
printf("<td cellpading=2>%s</td>
        <td>%s</td>
        <td>%s</td>
        <td>%s</td>
        <td>%s</td>


         
        <td><a  style='cursor:pointer;'  onclick=showCD2('$leadid','$enq')>" . _('Edit') . "</a></td>  

        </tr>",
        
        $no,
        $myrow['custname'],
        $myrow['leaddate'],
        $myrow1['enquirytype'],
        $myrow2['outputtype'],


        $_SERVER['PHP_SELF'] . '?' . SID,
        $myrow[0]);
          }
          //$_SERVER['PHP_SELF'] . '?' . SID,$myrow[0]         <td><a href='%sNum=%s&Delete=1'>" . _('Delete') . '</td>
      }
      echo '</tbody>';
      
      echo '</table>';  
      echo '</div>';  
      echo '</div>';
      echo "</fieldset></td></tr><tr><td><table>";
       
      
      echo"</table>";
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
    text-shadow: 1px 1px 1px #666;">BUSSINESS ASSOCIATE LEADS</font></center>'; 
    echo "<form action=" . $_SERVER['PHP_SELF'] . "?" . SID ." method=post>";
     
     echo"<table><tr><td>";
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

     echo "<tr><td>BA Category Code:</td><td><input type='text' name='BA Category Code' id='BA category code' onkeyup='' style='width:99%'></td></tr>";    
      echo "<tr><td>BA Type</td><td><input type='text' name='BA type' id='BA type' onkeyup='' style='width:99%'></td></tr>";    
     echo '<tr><td style="width:50%">Enquiry Type</td>';
    echo  '<td>';
    echo '<select name="enquiry" id="enquiry" style="width:190px" onchange="showinstitute(this.value)">';
    $sql1="SELECT * FROM bio_enquirytypes"; 
    $result1=DB_query($sql1,$db);
    $f=0;
    while($myrow1=DB_fetch_array($result1))
    { 
    if ($myrow1['enqtypeid']==$_POST['enquiry']) 
    {
       
    echo '<option selected value="';
    
    } else {
        if ($f==0) 
        {
        echo '<option>';
        }
        echo '<option value="';
        $f++;
    }
    echo $myrow1['enqtypeid'] . '">'.$myrow1['enquirytype'];
    echo '</option>';
    }
     
    echo '</select>';    
    echo '</td></tr></table>';
    
    
    echo"<table border=0 id='instdom'>";
    echo "<tr><td style='width:50%'>Customer Name</td>";
    echo "<td><input type='text' name='custname' id='custid' onkeyup='caps1()' style='width:190px'></td>";
    
        echo "<tr><td>House No:</td><td><input type='text' name='Houseno' id='Houseno' onkeyup='' style='width:99%'></td></tr>";    
        echo "<tr><td>House / Organizational Name</td><td><input type='text' name='HouseName' id='HouseName' onkeyup='' style='width:99%'></td></tr>";
        echo "<tr><td>Residencial Area:</td><td><input type='text' name='Area1' id='Area1' onkeyup='' style='width:99%'></td></tr>";
        echo "<tr><td>Post Box:</td><td><input type='text' name='Area2' id='Area2' onkeyup='' style='width:99%'></td></tr>";
        echo" <tr><td>Pin:</td><td><input type='text' name='Pin' id='Pin' onkeyup='' style='width:99%'></td></tr>";    
        echo"</table>";
        echo"<table>";
       // echo "<tr><td>Nationality:</td><td><select name='Nationality' id='Nationality' style='width:99%'><option value='INDIA'>INDIA</option></select></td></tr>" ;
            $sql="SELECT * FROM bio_country ORDER BY cid";
    $result=DB_query($sql,$db);
   
    echo"<tr><td style='width:50%'>Country</td><td>";

  echo '<select name="country" id="country" onchange="showstate(this.value)" style="width:190px">';

  $f=0;
  while($myrow1=DB_fetch_array($result))
  {  
  if ($myrow1['cid']==$_POST['country'])  
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
  echo '</select></td></tr>';
  
//        echo"<input type='text'  id='Nationality' onkeyup='' style='width:99%'></td></tr>";
            
//        echo "<tr><td>State:</td><td><input type='text' name='State' id='State' onkeyup='' style='width:99%'></td></tr>"; 
// =======       echo "<tr><td>State:</td><td><select name='State' id=' State'style='width:99%'><option value='Kerala' >Kerala</option></select></td></tr>" ;    
            echo"<tr id='showstate'><td>State</td><td><select /*disabled*/ style='width:100%'><option></option></select>";

  echo'</td>'; echo'</tr>'; 
  
  
  
  echo"<tr id='showdistrict'><td>District</td><td><select disabled style='width:100%'><option>District</option></select></td>";
  echo'</tr>';
  echo"<tr id='district1'><td>Districts:</td><td><input type='text' name='Districts' style='width:100%' id='District'></td>";
  echo'</tr>';
  
  
//        echo "<tr><td>District:</td><td><input type='text' name='District' id='District' onkeyup='' style='width:99%'></td>";       =========
//echo "<tr><td>District:</td><td><select name='District' id=' Districts'style='width:99%'>
//<option value='Thiruvananthapuram' >Thiruvananthapuram</option><option value='Kollam' >Kollam</option></select></td></tr>" ;         
    echo '</tr>';
    echo '<tr><td>Phone number</td>';
    echo "<td><table><td><input type=text name='code' id='code' style='width:50px'></td><td><input type=text name=phone id=phone style='width:100%'></td></table></td></tr>";
    echo '<tr><td>Mobile Number</td>';
    echo "<td><input type=text name=mobile id=mobile style='width:98%'></td></tr>";
    echo '<tr><td>Email id</td>';
    echo "<td><input type=text name='email' id='email' style='width:98%'></td></tr>";
    //Product Sevices


      echo '<tr id="showfeasibility"><td>Product Services</td>';
      echo'<td>';
  $sql1="SELECT * FROM stockcategory";
  $result1=DB_query($sql1, $db);
  echo '<select name="productservices" id="productservices" style="width:190px">';
  $f=0;
  while($myrow1=DB_fetch_array($result1))
  {  
  if ($myrow1['categoryid']==$_POST['productservices'])  
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
    echo $myrow1['categoryid'] . '">'.$myrow1['categorydescription'];
    echo '</option>';
    $f++;
   } 
  echo '</select>'; 
  echo '</td>'; 
  echo '</tr>';

   
  //Enquiry type
//    echo '<tr><td>Enquiry Type</td>';
//    echo  '<td>';
//    echo '<select name="enquiry" id="enquiry" style="width:190px">';
//    $sql1="SELECT * FROM bio_enquirytypes"; 
//    $result1=DB_query($sql1,$db);
//    $f=0;
//    while($myrow1=DB_fetch_array($result1))
//    { 
//    if ($myrow1['enqtypeid']==$_POST['enquiry']) 
//    {
//       
//    echo '<option selected value="';
//    
//    } else {
//        if ($f==0) 
//        {
//        echo '<option>';
//        }
//        echo '<option value="';
//        $f++;
//    }
//    echo $myrow1['enqtypeid'] . '">'.$myrow1['enquirytype'];
//    echo '</option>';
//    }
//     
//    echo '</select>';    
//    echo '</td></tr>';

    
    
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
  echo '<tr id="showoutputtype"><td>Output Type</td>';
  echo'<td>';
  $sql1="SELECT * FROM bio_outputtypes";
  $result1=DB_query($sql1, $db);
  echo '<select name="outputtype" id="outputtype" style="width:190px">';
  $f=0;
  while($myrow1=DB_fetch_array($result1))
  {  
  if ($myrow1['outputtypeid']==$_POST['outputtype']) 
    {
    echo '<option selected value="';
    
    } else 
    {
        if ($f==0) 
        {
        echo '<option>';
        }
        echo '<option value="';
        $f++;
    }
    echo $myrow1['outputtypeid'] . '">'.$myrow1['outputtype'];  
    echo '</option>';
  }
  echo '</select></td>';
  echo '</tr>';

    //Raw Materials
//  echo '<tr><td>Raw Materials(Kg.)</td>';
//  echo "<td><input type=text name=rmkg style='width:98%'></td></tr>"; 
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

  echo '<select name="sourcetype" id="sourcetype" style="width:192px" onkeyup=showCD(this.value) onchange=showCD(this.value) onclick=showCD(this.value)>';
  $sql="SELECT * FROM `bio_leadsourcetypes`";
  $result=DB_query($sql,$db); 
  echo $count=DB_fetch_row($sql,$db);
    $c=0;  
  while ($myrow = DB_fetch_array($result)) {
     
    if ($myrow['id']==$_POST['sourcetype']) 
    {
    echo '<option selected value="';
    } else if($c==0){echo '<option>';  }
        echo '<option value="';
    
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
//$sql="SELECT * FROM bio_schemes";
//  $result=DB_query($sql, $db);
//  echo '<select name=scheme id="Scheme" style="width:192px">';
//  $f=0;
//  while($myrow=DB_fetch_array($result))
//  {  
//  if ($myrow['schemeid']==$_POST['scheme'])
//    {
//        echo '<option selected value="';
//    }
//  else
//   {   
//       if ($f==0) 
//        {
//        echo '<option>';
//        }    
//        echo '<option value="';
//   }
//     echo $myrow['schemeid'] . '">'.$myrow['scheme'];     
//     echo '</option>';
//     $f++;
//  }
//    echo '</select></td>';
//    echo '</tr>'; 
  
  echo'<tr><td colspan="2"><table border=0><tr>';
    $sql1="SELECT * FROM bio_schemes";
                $result1=DB_query($sql1, $db); $j=1;
                 while ($taskdetails=DB_fetch_array($result1)){
                        printf(' <td style="background:#8080FF;color:white;">%s</td>
                <td style="background:#000080;color:white"><input type="checkbox" name="schm[]" value="%s">%s</td>',
$j,                  
               
                  $taskdetails[0],
                  $taskdetails[1]
               

                );             $j++;         } 
                
                
                

                
                
                
                
                echo"</tr></table></td></tr>";
  
  
  
      //Status 
    echo '<tr><td>Status</td>';
    echo "<td><input type=text name=status style='width:80%'></td>";
    echo '</tr>';
    //Remarks
    echo '<tr><td>Remarks</td>'; 
    echo "<td><textarea name=remarks rows=4 cols=26 style=resize:none;></textarea></td>"; 
    echo '</tr>';
    echo "</table>"; 
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
  echo '<select name="feedstock" id="feedstock" style="width:190px">';
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
 
echo '<tr><td colspan=3><center>        
<div id="show_sub_categories" align="center">
<img src="loader.gif" style="margin-top:8px; float:left" id="loader" alt="" />
</div>';  
echo '<input type=submit name="submit" id="leads" value=Submit  onclick="if(log_in()==1)return false;">';
echo '<input name="clear" type="submit" value=Clear ><input id="shwprint" type="button" name="shwprint" value="Report" >';     
  echo '</center></td>';    
  echo '</tr>';
  echo"<tr><td colspan='2'>"; 
  echo"<div id='printgrid' style='margin:auto;'>";
  echo"<table><tr><td>Date From";
echo'<input type="text"  id="datefrm" class=date alt='.$_SESSION['DefaultDateFormat']. ' name="datefrm" onChange="return isDate(this, this.value, '."'".$_SESSION['DefaultDateFormat']."'".')'.'"'.'>';    
 echo"</td><td>To Date ";
 echo'<input type="text"  id="dateto" class=date alt='.$_SESSION['DefaultDateFormat']. ' name="dateto" onChange="return isDate(this, this.value, '."'".$_SESSION['DefaultDateFormat']."'".')'.'"'.'>';    
 echo"</td>";
 echo"<td>Office";
     echo '<select name="offic" id="offic">';
   $sql1="SELECT bio_office.id,bio_office.office
FROM bio_office";
   $result1=DB_query($sql1,$db) ;
   $a=0;
      while ($myrow1 = DB_fetch_array($result1)) {
    if ($myrow1['id']==$_POST['office']) 
    {
    echo '<option  selected value="';
} else if($a==0){echo"<option>";  }


        echo '<option value="';
        echo $myrow1['id'] .'">'.$myrow1['office'];
        echo  '</option>';
        $a++;
    }  
     echo"</select>";    
     echo"</td>";
 echo"
 <td colspan=''>";
   echo 'LeadSource'; 
   
   echo '<select name="printsource" id="printnshow" style="width:192px" onchange="printshow()">';

   $sql1="SELECT id,sourcename, sourcetypeid
FROM bio_leadsources";
   $result1=DB_query($sql1,$db) ;
       $a=0;
   while ($myrow1 = DB_fetch_array($result1)) {
    if ($myrow1['id']==$_POST['source']) 
    {
    echo '<option  selected value="';
} else if($a==0){echo"<option>"; echo'<option value="ALL">---Select ALL---</option>';  }


        echo '<option value="';
        echo $myrow1['id'] .'">'.$myrow1['sourcename'];
        echo  '</option>';
        $a++;
    }  //echo '<option value="ALL">---Select ALL---</option>'; 
    echo '</select>';    
//     echo"</div></td><td><input type='button' value='Print' onclick='printshow()'></td></tr>";   

     
     
     echo"<tr><td colspan='2'><div id='printandshow' style='margin:auto;'>";  
    echo"</div></td></tr></table>"; echo '</div>';  
    echo"</td></tr>";



   


   echo '<tr>'; 
  echo '<td colspan=3>';
        
  echo '</td>';  
  echo '</tr>'; 
  echo '</table></td></tr><tr><td>';
//  echo "</div>";
   echo '</div>'; 
   echo "<table style='width:100%;height:auto;' border=0><tr><td>";  
   display($db);
   
   
   
   
   echo "</td></tr></table></td></td></table>";
   echo '</form>';         
 // echo '</div>'; 

 include('includes/footer.inc'); 
?>    
<script type="text/javascript">
 document.getElementById('enquiry').focus(); 
  $(document).ready(function() {
  $('#district1').hide();
      $('#printgrid').hide();
  $("#error").fadeOut(3000);
    $("#warn").fadeOut(3000);
      $("#success").fadeOut(3000);
        $("#info").fadeOut(3000);
                $("#db_message").fadeOut(3000);  
         
 $('#sourcetype').change(function() {
  $('#dinhide').hide();
}); 
 $('#shwprint').click(function() {
  $('#printgrid').slideToggle('slow',function(){});
});




 
   }); 
//    $('#leads').click(function() { 

//    f=validateemail('email','Please Enter valid email');  if(f==1) { return false; }   
//});
function caps1()
{
//   alert("sss");
UCWords('custid','Name should be begin with capital letter');
}
 function log_in()
{  //  alert("sss"); alert(mail);
var f=0;                                                  //State


f=common_error('custid','Please Enter Customer Name');  if(f==1) { return f;} 

if(f==0){f=common_error('Nationality','Please Select a Country');  if(f==1) { return f; }}
if(f==0){f=common_error('State','Please Select a State');  if(f==1) { return f; }}
if(f==0){f=common_error('Districts','Please Select a District');  if(f==1) { return f; }}

if(f==0){var y=document.getElementById('phone').value; var x=document.getElementById('mobile').value;
    if(x=="" && y==""){ alert("Please enter atleast one contact number");f=1;} 
    if(f==1) { document.getElementById('phone').focus();return f; } }
if(f==0){var x=document.getElementById('phone').value;  
            if(isNaN(x)||x.indexOf(" ")!=-1)
           {  f=1;
              alert("Enter a numeric value"); document.getElementById('phone').focus();
              if(x=""){f=0;}
              return f; 
           }
           if (x.length>8)
           {  f=1;  
              alert("Enter upto 8 characters"); document.getElementById('phone').value=""; 
              document.getElementById('phone').focus();
              return f;
           }
}
                  


if(f==0){f=common_error('productservices','Please Select a Product services');  if(f==1) { return f; }}                  
if(f==0){f=common_error('enquiry','Please Select an Enquiry Type');  if(f==1) { return f; }}     
if(f==0){f=common_error('outputtype','Please Select an Output Type');  if(f==1) { return f; }} 

if(f==0){f=common_error('sourcetype','Please Select a LeadSource Type');  if(f==1) { return f; }}
if(f==0){f=common_error('source','Please Select a LeadSource');  if(f==1) { return f; }} 
if(f==0){f=common_error('Scheme','Please Select a Scheme');  if(f==1) { return f; }}     
if(f==0){f=common_error('feedstock','Please Select a Fead Stock');  if(f==1) { return f; }}
 if(f==0){f=common_error('feedstockad','Please Select a Fead Stock');  if(f==1) { return f; }}     }

    

  function printshow()
{                               
 var str=document.getElementById('printnshow').value;   
 var str1=document.getElementById('datefrm').value;        
 var str2=document.getElementById('dateto').value;   
  var str3=document.getElementById('offic').value;   
if (str=="")
  {
  document.getElementById("printandshow").innerHTML="";
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
  {           //alert(str);   
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {

    document.getElementById("printandshow").innerHTML=xmlhttp.responseText;
//      $('#dinhide').show(); 
    }
  } 
  //alert(str);
xmlhttp.open("GET","bio_PrintLeadSource.php?id=" + str +  "&from=" + str1 + "&to=" + str2 + "&offic=" + str3,true);
xmlhttp.send(); 
                                        
}
function showCD9(){                           
 var str=document.getElementById('feedstockad').value;   
 var str1=document.getElementById('weightad').value;        
 var str2=document.getElementById('leadid').value;   
   if(str==""){alert("please select a feadstock");document.getElementById("feedstockad").focus(); return false;}
//alert(str);alert(str1);alert(str2);
if (str2=="")
  {
  document.getElementById("shadd").innerHTML="";
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
    {    function message(){alert("sss");}     

    document.getElementById("shadd").innerHTML=xmlhttp.responseText;
//      $('#dinhide').show(); 
    }
  } 
  //alert(str);
xmlhttp.open("GET","bio_add.php?feedstok=" + str +  "&weight=" + str1 + "&lead=" + str2,true);
xmlhttp.send(); 
 }
 function showstate(str){ 

if (str=="")
  {
  document.getElementById("showstate").innerHTML="";
  return;
  }
show_progressbar('showstate');

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
xmlhttp.open("GET","bio_showstate.php?country=" + str,true);
xmlhttp.send();
}



function showdistrict(str){       //alert(str);
str1=document.getElementById("country").value;
//alert(str1);
if (str=="")
  {
  document.getElementById("showdistrict").innerHTML="";
  return;
  }
show_progressbar('showdistrict');
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
xmlhttp.open("GET","bio_showstate.php?state=" + str + "&country1=" + str1,true);
xmlhttp.send();

}
function addnew(str){ if(str=="New"){
$('#showdistrict').hide();
$('#district1').show();
  document.getElementById('District').focus();


  }
}



$(document).ready(function() {

    $('#loader').hide();
    $('#show_heading').hide();
    
    $('#Nationality').change(function(){
        $('#show_sub_categories').fadeOut();
        $('#loader').show();
        $.post("get_chid_categories.php", {
            parent_id: $('#Nationality').val(),
        }, function(response){
            
            setTimeout("finishAjax('show_sub_categories', '"+escape(response)+"')", 400);
        });
        return false;
    });
});

function finishAjax(id, response){
  $('#loader').hide();
  $('#show_heading').show();
  $('#'+id).html(unescape(response));
  $('#'+id).fadeIn();
} 

function alert_id()
{
    if($('#State').val() == '')
    alert('Please select a sub category.');
    else
    alert($('#State').val());
    return false;
}

function showinstitute(str){    // alert(str);
if (str=="")
  {
  document.getElementById("instdom").innerHTML="";
  return;
  }
  show_progressbar("instdom");
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
    document.getElementById("instdom").innerHTML=xmlhttp.responseText;
getgrid(str);
    }
  }
xmlhttp.open("GET","bio_showdom.php?dom=" + str,true);
xmlhttp.send();
//
}
function getgrid(){
//    alert(str);
str=document.getElementById("enquiry").value;
    if (str=="")
  {
  document.getElementById("leaddetails").innerHTML="";
  return;
  }
  show_progressbar("leaddetails");
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
    document.getElementById("leaddetails").innerHTML=xmlhttp.responseText;
getservice(str);
    }
  }
xmlhttp.open("GET","bio_showgrid.php?enggrid=" + str,true);
xmlhttp.send();


}

function getservice(str){
//    alert(str);
//str=document.getElementById("enquiry").value;
    if (str=="")
  {
  document.getElementById("showfeasibility").innerHTML="";
  return;
  }
  show_progressbar("showfeasibility");
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
    document.getElementById("showfeasibility").innerHTML=xmlhttp.responseText;
//if(str==2){getservice(str);}
    }
  }
xmlhttp.open("GET","bio_showgrid.php?service=" + str,true);
xmlhttp.send();


}

function output(str1){
//    alert(str);
str=document.getElementById("enquiry").value;
    if (str=="")
  {
  document.getElementById("showoutputtype").innerHTML="";
  return;
  }
  show_progressbar("showoutputtype");
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
    document.getElementById("showoutputtype").innerHTML=xmlhttp.responseText;
//if(str==2){getservice(str);}
    }
  }
xmlhttp.open("GET","bio_showgrid.php?output=" + str + "&plant=" + str1,true);
xmlhttp.send();


}
</script>
