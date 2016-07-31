<?php

$PageSecurity = 80;   
  include('includes/session.inc');
$title = _('Create Dealers Customer');  
include('includes/header.inc');

include('includes/sidemenu.php');



echo '<center><font style="color: #333;
          background:#fff;
          font-weight:bold;
          letter-spacing:0.10em;
          font-size:16px;
          font-family:Georgia;
          text-shadow: 1px 1px 1px #666;">DEALER SELECTION</font></center>';
          
echo "<form action=" . $_SERVER['PHP_SELF'] . "?" . SID ." method=post name='form'>";            
//    echo"<table style='width:100%;height:100%;><tr><td>";   
    echo "<fieldset style='float:left;width:90%;height:auto'>";     
    echo "<legend><h3>Select Dealer</h3>";
    echo "</legend>";         
    
    echo "<table style='height:100%'>"; 
    
    
        $sql="SELECT * FROM bio_country ORDER BY cid";
        $result=DB_query($sql,$db);
    
    echo"<tr><td>Country*</td><td>";
    echo '<select name="country" id="country" tabindex=1  onchange="showstate(this.value)" style="width:200px">';
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
  echo '</select></td></tr>';
 
  
  
  
         //State
         

   $sql="SELECT * FROM bio_state WHERE cid=1 ORDER BY stateid";
    $result=DB_query($sql,$db);
    
 
 echo"<tr id='showstate'><td>State*</td><td>";
 echo '<select name="state" id="state" style="width:200px" tabindex=2  onchange="showdistrict(this.value)">';
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
  echo'</tr>';
  
  
  
            //District
            
            
     
  $sql="SELECT * FROM bio_district WHERE stateid=14 ORDER BY did";
  $result=DB_query($sql,$db);
 
 echo"<tr id='showdistrict'><td>District*</td><td>";
 echo '<select name="District" id="District" style="width:200px" tabindex=3  onchange="onblur=dealersarea();">';
   $f=0;
  while($myrow1=DB_fetch_array($result))
  {
  if ($myrow1['did']==$district)
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
  echo'</td></tr>'; 
  
   echo "<tr id=showdealer></tr>"; 
  
  echo '<tr><td></td><td></td><td align=right><input type=button name=click id=click value="Select Dealer" onclick=gotodealer();></td></tr>';
    
  echo "</table>";
  
    
    echo "</fieldset>";
//    echo "</td></tr></table>";    

echo"</form>";                   
          
?>


 <script type="text/javascript">   
 
 function showstate(str){ 

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
    document.getElementById("state").focus();
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
           document.getElementById('District').focus();

    }
  }
xmlhttp.open("GET","bio_showstate.php?state=" + str + "&country1=" + str1,true);
xmlhttp.send();

}  


function dealersarea(){   
    
     cid=document.getElementById("country").value;           
     sid=document.getElementById('state').value;             
     did=document.getElementById('District').value;          
//     lsg=document.getElementById('lsgType').value;            
//     nam=document.getElementById('lsgName').value;               
//     
//     lsgdetail="&lsg=" + lsg + "&nam=" +nam;
//     if(lsg==3 && nam!=""){        
//     blc=document.getElementById('gramaPanchayath').value; 
//     lsgdetail+="&blc=" +blc;      
//     }
     
if (cid=="")
  {
  document.getElementById("showdealer").innerHTML="";
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
    document.getElementById("showdealer").innerHTML=xmlhttp.responseText; 
       
    }
  } 
xmlhttp.open("GET","bio_dealerdetails1.php?cid=" + cid + "&sid=" + sid + "&did=" + did,true);
xmlhttp.send(); 
}   


 
function gotodealer(){
    str=document.getElementById('dealer').value;
    
location.href='bio_childCustomer2.php?dealerdebtor='+str;
} 

 
 </script>
