<?php
  $PageSecurity = 80; 
include('includes/session.inc'); 
$title = _('List of location');
include('includes/header.inc');  


echo"<br />";
    
echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "?" . SID . "'>"; 
   
echo"<fieldset style='width:900px;'; overflow:auto;'>";    
echo"<legend>Location Details</legend>";     
  
  
  $sql2="SELECT `fileno` , `loc` , `room` , `rack` , `bin`
FROM `bio_lsg_fileno`
WHERE `room` IS NOT NULL";

$result=DB_query($sql2,$db);

echo "<div style='height:400px; overflow:auto;'>"; 
echo"<table width=800px>";
echo"<tr><th width='25px'>Sl.No.</th><th>LSG</th><th>Location</th><th>Room</th><th>Rack</th><th>Bin</th>";

 echo "</tr>";
  $k=0;  //row colour counter*
$slno=0; 
while($myrow=DB_fetch_array($result))
{
   
 if ($k==1)
              {
                echo '<tr class="EvenTableRows">';
                $k=0;
              }else 
              {
                echo '<tr class="OddTableRows">';
                $k=1;     
              }
              $slno++;
              //For LSG 

 
 echo  "<td>$slno</td>";            
echo "<td>".$myrow['fileno']."</td>";
echo "<td>".$myrow['loc']."</td>";
echo "<td>".$myrow['room']."</td>";
echo "<td>".$myrow['rack']."</td>"; 
echo "<td>".$myrow['bin']."</td>"; 

}

echo"</table>"; 
echo"</div>";  

echo"</fieldset>";   

echo"</form>";
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