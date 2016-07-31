<?php
$PageSecurity = 80;
include('includes/session.inc');



$title = _('Business Associate');
include('includes/header.inc');
    
 //----------------------------------------
  echo '<center><font style="color: #333;
    background:#fff;
    font-weight:bold;
    letter-spacing:0.10em;
    font-size:16px;
    font-family:Georgia;
    text-shadow: 1px 1px 1px #666;">Business Associate details</font></center>'; 
    echo '<form action=""  method="post">';
    
    
 //  echo'<table><tr><td>'; 
 
   
    echo"<table style='width:45%;height:150%;'><tr><td>";
     echo "<div id=editleads>";  

    
     
    echo "<table border=0 style='width:100%;height:150%;'>"; 
       
    echo "<tr><td style='width:100%'>";
 //---------------------------------------------------------------------------------   
    echo "<table>";
    echo'<tr><td></td>';
    echo'<td></td></tr>';
    
        echo'<tr><td> Name</td>';
    echo'<td><input type="text" id="" name=""> </td></tr>';
    
        echo'<tr><td> Business Type</td>';
    echo'<td><select id="business type" name="business type"> <option> </option></select></td></tr>';
    
        echo'<tr><td>House No:</td>';
     echo'<td><input type="text" id="houseno" name="House No"> </td></tr>'; 
    
        echo'<tr><td>House Name:</td>';
     echo'<td><input type="text" id="housename" name="House Name"> </td></tr>'; 
    
        echo'<tr><td>Residencial Area:</td>';
     echo'<td><input type="text" id="area" name="Area"> </td></tr>'; 
    
        echo'<tr><td>Pin:</td>';
     echo'<td><input type="text" id="pin" name="Pin"> </td></tr></table>'; 
    
    echo'</td><td>';
                echo '</table></td><td>' ;
    
     echo'<table>';
      
      //-----------------------------------------------------------------------------------------
       $sql="SELECT * FROM bio_country ORDER BY cid";
    $result=DB_query($sql,$db);
   
    echo"<tr><td style='width:75%'>Country</td><td>";

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
  

  echo"<tr id='showstate'><td>State</td><td><select /*disabled*/ style='width:100%'><option></option></select>";
 
  echo'</td>'; echo'</tr>'; 
  
  echo"<tr id='showdistrict'><td>District</td><td><select disabled style='width:100%'><option>District</option></select></td>";
  echo'</tr>';
 
      //-------------------------------------------------------------------------------------
 
     
     echo '<tr><td>Phone number</td><td style="width:50%"><input type=text name="code" id="code" style="width:40px"><input style="margin-left:5px" type="text" name="phone" id="phone"></td></tr>';
   // echo "<td><table><td><input type=text name='code' id='code' style='width:40px'></td><table><td><input style='width:100px' type='text' name='phone' id='phone'></td></tr>";
    
     echo'<tr><td>Mobile Number</td>';
        echo'<td><input type="text" id="mobile" name="Mobile"> </td></tr>'; 
    
     echo'<tr><td>Email id</td>';
         echo'<td><input type="text" id="email" name="Email"> </td></tr>'; 
                  echo'</table>';  
 
 //---------------------------------------------------------------------------------------------------------------------------------------------------------------------- 
 
 
 echo '<tr><td colspan=2><p><div class="centre"><input type=submit name=submit value="Submit"' . _('Submit') . '"></div>';    
 
 

 //----------------------------------------------------------------------------------------------------------------------------------------------------------------------- 
    echo'</td></tr></table></td></tr>';
              echo'</table>';       
                           echo'</table>';     
                           echo '</form>';
                           
?>

<script>
 function showstate(str){ 
//     alert(str);
if (str=="")
  {
  document.getElementById("showstate").innerHTML="";
  return;
  }
show_progressbar('showstate');               
//alert(str);

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

function replace_html(id, content) {
    document.getElementById(id).innerHTML = content;
}
var progress_bar = new Image();
progress_bar.src = '4.gif';
function show_progressbar(id) {
    replace_html(id, '<img src="4.gif" border="0" alt="Loading, please wait..." />Loading...');
}
</script>

