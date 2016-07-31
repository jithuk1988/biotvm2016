<?php
  $PageSecurity = 80;
include('includes/session.inc');
$title = _('Leads');  
include('includes/header.inc');
$sql="    SELECT `subcategoryid`,`subcategorydescription` FROM substockcategory WHERE substockcategory.maincatid =1";
   $result=DB_query($sql,$db);
      
echo "<table border=0 style='align:left'>";
echo "<tr><td>Catagory</td>";
echo '<td><select name="caty" id="caty" style="width:200px"  onchange="view(this.value)">';
      echo '<option value=0>Select category</option>';
      while ( $myrow = DB_fetch_array ($result) ) {
          echo "<option value=".$myrow[subcategoryid].">".$myrow[subcategorydescription]."</option>";
      }
      echo '</select></td>';
      echo '<td><select name="caty2" id="caty2" style="width:200px" onchange="view2(this.value)" onblur="view2(this.value)"></select></td>';
     $sql="SELECT stockid,longdescription from stockmaster";
     $result=DB_query($sql,$db);
      echo '<td id="items" ><select name="Item" id="item" style="width:200px">';
      echo '<option value=0>Select Item</option>';
   while ( $myrow = DB_fetch_array ($result) ) {
          echo "<option value=".$myrow[stockid].">".$myrow[longdescription]."</option>";
      }  
      echo '</select></td>'; 
?>
<script>
  function view(str1)
{    
  if (str1=="")
  {
  document.getElementById("caty").focus();
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
     document.getElementById("caty2").innerHTML=xmlhttp.responseText; 
     
    }            
  } 
xmlhttp.open("GET","bio_selectplantforleadsan.php?subcatid="+str1,true);//alert(str1);
xmlhttp.send();        
}
function view2(str1)
{    
    
   /*  var str2=document.getElementById("item").value;
     alert(str2) ;
     if((str2)!=0 )
     {
        document.getElementById("item").innerHTML="";   
     }  */
//alert("hhh"+str1);
if (str1=="")
  {
  document.getElementById("caty").focus();
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
  document.getElementById("item").innerHTML=xmlhttp.responseText;         //xmlhttp.responseText
     
    }            
  } 
xmlhttp.open("GET","bio_selectplantforleadsan.php?subsubcatid="+str1,true);//alert(str1);
xmlhttp.send();     
}
</script>