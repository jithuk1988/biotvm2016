<?php
$PageSecurity = 40;
include('includes/session.inc');
$title = _('Item Category');
include('includes/header.inc'); 
$pagetype=1;
include('includes/sidemenu1.php');


echo "<form action=" . $_SERVER['PHP_SELF'] . "?" . SID ." method=post>";
//echo"<div id='showloc' width='800px'></div>";
 echo"<table width='700px'><tr><td>";
 echo"<fieldset><legend>Select Stock Category</legend>";
  echo"<table>";

  
  echo"<tr id='showsub'><td>Main Category</td><td>";
 echo '<select name="main" id="main" style="width:190px" tabindex=11 onchange="showsub(this.value)">';
 $sql="SELECT
  maincatid,
  maincatname
FROM bio_maincategorymaster";

$rst=DB_query($sql,$db);
echo '<option value=0></option>';
while($myrowc=DB_fetch_array($rst))
{
    

 echo '<option value='.$myrowc[maincatid].'>'.$myrowc[maincatname].'</option>';
 }
  echo '</select>';
  echo'</td></tr>'; 
echo' </table>';
 

        
      echo"</fieldset>";

      echo"<div id='display' ></div>";
      
      
     
      
      
      
 echo"</table></form>";
 
 ?>
 <script>

function showsub(str1)
{
 
  // var  main=document.getElementById('main').value;            

    if (str1=="")
  {
  document.getElementById("main").focus();
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
         //alert (str1); 
         // document.getElementById('showloc').style.display = "block";
     document.getElementById("showsub").innerHTML=xmlhttp.responseText; 
     
    }            
  } 
 


xmlhttp.open("GET","bio_category_view_ajax.php?sub="+str1,true);//
xmlhttp.send();    
    
  
}
function showitem(str2)
{                           
var  main=document.getElementById('main').value;//alert(main)  ;
    if (str2=="")
  {
      
  document.getElementById("sub").focus();
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
        
         // document.getElementById('showloc').style.display = "block";
     document.getElementById("showsub").innerHTML=xmlhttp.responseText; 
     
    }            
  } 
 


xmlhttp.open("GET","bio_category_view_ajax.php?item="+str2+"&sub="+main,true);
xmlhttp.send();    
    
  
}
function display(str2)
{                           
var  main=document.getElementById('main').value;
var  sub=document.getElementById('sub').value;
//alert(main)  ;
    if (str2=="")
  {
      
  document.getElementById("sub").focus();
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
        
         // document.getElementById('showloc').style.display = "block";
     document.getElementById("display").innerHTML=xmlhttp.responseText; 
     
    }            
  } 
 


xmlhttp.open("GET","bio_category_view_ajax.php?item="+str2+"&sub="+main,true);
xmlhttp.send();    
    
  
}
function qty_in_loc(str)
{  //   bio_qtyinlocation.php?location=1&stock=PW-2
//alert(str);
    window.open("bio_qtyinlocation.php?stock="+str,"qtyinloc","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=1000,height=800");
}
 </script>