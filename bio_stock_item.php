
<?php
$PageSecurity = 80;
include ('includes/session.inc');
$title = _('PRODUCTION LOCATION');
include ('includes/header.inc');
include ('includes/SQL_CommonFunctions.inc');
 echo '<div id="dup"></div>'; 
  echo"<input type='hidden' name='stop' id='stop' value='3'>"; 
if(isset($_GET['delete'])){ 
    $id=$_GET['delete'];
$sql= "DELETE FROM bio_wo_stocklocation WHERE bio_wo_stocklocation.serial_no=$id";
$result=DB_query($sql,$db); 
}
 if (isset($_POST['submit'])){
     $loc1=$_POST['loc'];     $itm1=$_POST['itm'];
     
  $sql="INSERT INTO bio_wo_stocklocation(bio_wo_stocklocation.stockid,loccode) VALUES('$itm1','$loc1')";
    $result=DB_query($sql,$db);
    
  }  
 
    echo '<center><font style="color: #333;
    background:#fff;
    font-weight:bold;
    letter-spacing:0.10em;
    font-size:16px;
    font-family:Georgia;
    text-shadow: 1px 1px 1px #666;">PRODUCTION LOCATION </font></center>';
    echo'<table width=98% ><tr><td>'; 
echo'<div >'; 
echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "'>";  
echo"<fieldset style='width:400px;height:170px'; overflow:auto;'>";
echo"<legend><h3>LOCATIONS</h3></legend>";
 
 
     $sql="SELECT * FROM locations where managed=1 ORDER BY locationname ";
    $result=DB_query($sql,$db);
   
    echo'<table ><tr><td>STOCK LOCATION:</td><td>';
  echo '<select name="loc" id="loc" style="width:190px">';

  while($myrow1=DB_fetch_array($result))
  {
        echo '<option value="';
    
    echo $myrow1['loccode'] . '">'.$myrow1['locationname'];
    echo '</option>';
   // $f++;
   }
    echo '</select></td></tr>'; 
    echo '<tr><td>MAIN CATEGORY</td><td id=main>' ;
    echo "<select name='main' id='main' style='width:190px;' onchange='show2(this.value)'>";
    $sql="SELECT
  maincatid,
  maincatname
FROM bio_maincategorymaster ";

$rst=DB_query($sql,$db);
echo '<option value=0></option>';
while($myrowc=DB_fetch_array($rst))
{
    

 echo '<option value='.$myrowc[maincatid].'>'.$myrowc[maincatname].'</option>';
 }
    echo '<tr><td>SUB CATEGORY</td><td id=sub>' ;
    echo '<select style="width:190px"></td></tr>';
    
   
    
   
    echo'<tr><td>ITEM:</td><td id=sub2> ';

  echo '<select name="itm" id="itm" style="width:190px">';

    echo '</select></td></tr>' ;
    echo '<tr><td></td></tr>';
  echo'<tr><td></td><td><input type=submit name=submit value="submit" onmouseover="test()" onclick="if(valid()==1)return false;"></td></tr>';
  echo "</table>";
   echo"</fieldset>";
   
    echo"<tr><td colspan='2'>";  
    
     echo"<fieldset  style='width:400px;'><legend>ITEM-LOCATION</legend>";
     echo ' <div style="height: 200px; width: 100%; overflow: scroll;">';
    echo "<table width='400px'>";echo"<tr style='background:#585858;color:white'>
    <td>SERIAL NO:</td><td>LOCATION</td><td>DESCRIPTION</td><td>DELETE</td></tr>"; //<td>CMCAPACITY</td><td>CAPACITY</td>

    $sql="select bio_wo_stocklocation.serial_no,capacity,cmcapacity,locations.locationname,stockmaster.description from bio_wo_stocklocation,stockmaster,locations where locations.loccode=bio_wo_stocklocation.loccode and stockmaster.stockid=bio_wo_stocklocation.stockid  ";
  $d=1;  
$result3=DB_query($sql,$db);
              while($myrow3=DB_fetch_array($result3))
          {    //echo $myrow[0];
          $id=$myrow3['serial_no'];
          $c=$myrow3['locationname'];
          $s=$myrow3['description'];
         // $g=$myrow3['capacity'];<td>$g</td>
        //  $h=$myrow3['cmcapacity'];<td>$h</td>
                   echo"<tr style='background:#A8A4DB'><td>$d</td><td>$c</td><td>$s</td><td><a href='#' id='$id' onclick='dlt(this.id)'>delete</a></td></tr>";
                   $d=$d+1;
                 }    
             echo"</table></div></fieldset>";
    echo"</td></tr>";
    echo "</form>" ; 
    ?>       
  <script>
function dlt(str){
location.href="?delete=" +str;         
}

function valid()
{
   var f=0;
   // str1=document.getElementById("capacity").value;
  //  str2=document.getElementById("cmcapacity").value;
     //alert("gh");
    /*    
    if(str1=="")
    { 
        alert("please enter capacity");
     return f=1;
 document.getElementById("capacity").focus();   }*/
           
  var y=document.getElementById('stop').value; 

if(y==2)
          {
          alert("ITEM ALREADY ENTERED IN THAT WORKCENTER  ") ;
              return f=1;
 }       
}
function test()
{
      str1=document.getElementById("loc").value;//alert(str1);
    str2=document.getElementById("itm").value;//
    if (str1=="")
  {
  document.getElementById("loc").innerHTML="";
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
     document.getElementById("dup").innerHTML=xmlhttp.responseText; 
       
    }
  } 
xmlhttp.open("GET","bio_stock_item_ajax.php?str1=" + str1 +"&str2="+ str2,true);//alert(str2);
xmlhttp.send(); 
} 
function show2(str1)
{
    

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
  { // alert (str1);
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
          
         // document.getElementById('showloc').style.display = "block";
     document.getElementById("sub").innerHTML=xmlhttp.responseText; 
     
    }            
  } 
xmlhttp.open("GET","bio_stock_item_ajax.php?combo2="+str1,true);//alert(str4);
xmlhttp.send();     //alert(str1);
}
function sub2(str2)
{
    

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
  { // alert (str1);
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
          
         // document.getElementById('showloc').style.display = "block";
     document.getElementById("sub2").innerHTML=xmlhttp.responseText; 
     
    }            
  } 
xmlhttp.open("GET","bio_stock_item_ajax.php?combo3="+str2,true);//alert(str4);
xmlhttp.send();    //  alert(str2);
}

</script>

  
       