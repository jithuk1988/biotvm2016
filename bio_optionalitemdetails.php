<?php

$PageSecurity = 80;
include('includes/session.inc');
$title = _('OptionalItemdetails');
include('includes/header.inc');

echo '<p class="page_title_text">' . ' ' . _('Optional Item ') . '';    

      echo "<form action=" . $_SERVER['PHP_SELF'] . "?" . SID ." method=post>";  
        echo"<table><tr><td>";
   echo "<div id=editleads>";  
   echo "<table border=0 style='width:55%;height:150%;'>"; 
   echo "<tr><td style='width:50%' valign=top>";
//Customer Details Fieldset.............................Customer Details Fieldset...............................Customer Details Fieldset 
//echo "<div>";  

   echo "<fieldset style='float:left;width:95%;height:auto'>";     
   echo "<legend><h3>Select Plant</h3>";
   echo "</legend>";     
   echo "<table>";  
        
        echo '<tr>';
             echo '<td style=width:140%>Selecttype Service:-</td>';
             echo '<td><input type="radio" name="item" value="1">Optionalitem
             <input type="radio" name="item" value="2">Service</td>';  
        echo '</tr>';              
      echo'<tr>
      
      <td style=width:80%>Category</td>'; 
      echo '<td><select name="plant" id="plant"  onchange="Filtercategory()" style="width:190px" >';
      
              $sql = "SELECT subsubcatid,categoryid, 
               categorydescription
        FROM bio_maincat_subcat,stockcategory
        WHERE stockcategory.categoryid= bio_maincat_subcat.subsubcatid
             AND bio_maincat_subcat.subcatid =11
             order by stockcategory.categorydescription asc";
             
$ErrMsg = _('The stock categories could not be retrieved because');
$DbgMsg = _('The SQL used to retrieve stock categories and failed was');
$result = DB_query($sql,$db,$ErrMsg,$DbgMsg);

while ($myrow=DB_fetch_array($result))
{
          
  if ($myrow['subcatid']==11)  
    {
             echo "<option selected value=".$myrow['categoryid'].">".$myrow['categorydescription']."</option>"; 
          }
          else
          {
             echo "<option value=".$myrow['categoryid'].">".$myrow['categorydescription']."</option>"; 
          }
          
    $plant=$myrow['subcatid'];
     echo '</option>';    
}
if (!isset($_POST['plant'])) 
{
    $_POST['plant']=$plant;
}
      
       echo'</select></td>';
   

       $sql="SELECT stockid,description
        FROM stockmaster
        INNER JOIN     stockcategory ON  stockmaster.categoryid=stockcategory.categoryid
        INNER JOIN   bio_maincat_subcat ON            bio_maincat_subcat.subsubcatid= stockcategory.categoryid
             WHERE bio_maincat_subcat.subcatid=11
             AND stockmaster.categoryid='".$_GET['plant']."'
             ORDER BY stockmaster.description asc";  
             $result=DB_query($sql,$db);             
      echo'<td style=width:80% > Plant</td>'; 
      echo '<td id="subcat"><select name="plantoption" id="plantoption"  style="width:190px">';
                   while ( $myrow = DB_fetch_array ($result) ) 
      {
          if($myrow['stockid']==$_POST['stockid'])
          {
             echo "<option selected value=".$myrow['stockid'].">".$myrow['description']."</option>"; 
          }
          else
          {
             echo "<option value=".$myrow['stockid'].">".$myrow['description']."</option>"; 
          }
          
      }
      
            echo'<select></td>';
      echo '</tr>' ; echo '</table>';   
      
       echo"<tr><td colspan=''><center> ";
     echo "<fieldset style='width:95%'>";   
   echo "<legend><h3>Select optinal Item</h3>";
   echo "</legend>";
 
        
          echo "<table>";  
                      
      echo'<tr><td style=width:80%>Category</td>';            
              echo '<td><select name="plants" id="plants"  onchange="Filitem()" style="width:190px" >';  
                   $sql = "SELECT subsubcatid,categoryid, 
               categorydescription
        FROM bio_maincat_subcat,stockcategory
        WHERE stockcategory.categoryid= bio_maincat_subcat.subsubcatid
             AND bio_maincat_subcat.subcatid !=11
             order by stockcategory.categorydescription asc";
             
$ErrMsg = _('The stock categories could not be retrieved because');
$DbgMsg = _('The SQL used to retrieve stock categories and failed was');
$result = DB_query($sql,$db,$ErrMsg,$DbgMsg);

while ($myrow=DB_fetch_array($result))
{
          
  if ($myrow['subcatid']!=11)  
    {
             echo "<option selected value=".$myrow['categoryid'].">".$myrow['categorydescription']."</option>"; 
          }
          else
          {
             echo "<option value=".$myrow['categoryid'].">".$myrow['categorydescription']."</option>"; 
          }
          
   
     echo '</option>';    
}

      
       echo'</select></td>';
   
                  $sql="SELECT stockid,description
        FROM stockmaster
        INNER JOIN     stockcategory ON  stockmaster.categoryid=stockcategory.categoryid
        INNER JOIN   bio_maincat_subcat ON            bio_maincat_subcat.subsubcatid= stockcategory.categoryid
             WHERE bio_maincat_subcat.subcatid !=11
             AND stockmaster.categoryid='".$_GET['item']."'
             ORDER BY stockmaster.description asc";  
             $result=DB_query($sql,$db);             
      echo'<td style=width:80% > Item</td>'; 
      echo '<td id="option"><select name="optionalitem" id="optionalitem"  style="width:190px">';
                   while ( $myrow = DB_fetch_array ($result) ) 
      {
          if($myrow['stockid']==$_POST['stockid'])
          {
             echo "<option selected value=".$myrow['stockid'].">".$myrow['description']."</option>"; 
          }
          else
          {
             echo "<option value=".$myrow['stockid'].">".$myrow['description']."</option>"; 
          }
          
      }
      
            echo'<select></td>';
      echo '</tr>' ; 
  echo '<tr><td>Quantity</td><td><input type="text" name="quantity" id="quantity"/></td><td><input type="button" name="add" value="add" onclick="showCD()"/></td></tr>';
      echo '</table>';   
      
echo "<tr><td>";

    echo "<fieldset style='width:550px'>";
   echo "<legend><h3>Optional Items</h3></legend>";
        
         echo "<div id=display class=selection>";
      
          echo "</div>";
      echo'</table>';
      
      
       echo'<table>';      
      echo '<tr>';
      echo '<td><input type="submit" name="submit" value="submit" /></td>';
           
      echo '</tr>';  
       echo'</table>';    
         
     
     
     if(isset($_POST['submit']))
     {
         $item=$_POST['item'];
         
        $sql="INSERT INTO bio_optionalitemdetails(prnt_stockid,opt_stockid,typeid,opt_qty) 
           SELECT bio_temp.plantid AS plantid,bio_temp.optionalid AS optionalid , $item,bio_temp.quantity AS quantity
              FROM bio_temp "; 
         $result=DB_query($sql,$db);
          $tempdrop="TRUNCATE TABLE bio_temp";
DB_query($tempdrop,$db); 
         
       //  $item=$_POST['item'];
       //  $sqlprt="SELECT id FROM bio_optionalitemdetails ORDER BY id DES";
        // $result1(DB_query($sqlprt,$db));
         //$myrow(DB_fetch_array($result1));
        // $teamid=$myrow[0];                              
     }
     
     
     
//include "includes/footer.inc";        
?>
<script language="JavaScript"> 


    

function Filtercategory()      
 {

var str= document.getElementById("plant").value;
    
    if (str=="")
  {
  document.getElementById("subcat").innerHTML="";
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
        
    document.getElementById("subcat").innerHTML=xmlhttp.responseText;
//      $('#dinhide').show(); 
    }
  } 
  //alert(str);
xmlhttp.open("GET","bio_maincatsubcatcp1.php?plant="+str,true);
xmlhttp.send(); 
 }

          
       function showCD()
       {
        var str1=document.getElementById('plantoption').value;
        var str2=document.getElementById('optionalitem').value;
        var str3=document.getElementById('quantity').value;       
        //var er="bio_getoptionalitem.php?plant="+str1+ "&optional=" +str2+ "&quantity="+str3;
        if(str1=="")
        {
            document.getElementById("display").innerHTML="";
            return false;
        }
      if (window.XMLHttpRequest) 
        {
            xmlhttp=new XMLHttpRequest();        
        }
        else
        {
            xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");          
        }
        
        xmlhttp.onreadystatechange=function()           
         {
            if(xmlhttp.readyState==4 && xmlhttp.status==200) 
            {
                 document.getElementById("display").innerHTML=xmlhttp.responseText; 
            }
         }
         xmlhttp.open("GET","bio_getoptionalitem.php?plant="+str1+ "&optional=" +str2+ "&quantity="+str3,true);
           xmlhttp.send();
         
       }
       
   

function Filitem()      
 {

var str= document.getElementById("plants").value;
    
    if (str=="")
  {
  document.getElementById("option").innerHTML="";
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
        
    document.getElementById("option").innerHTML=xmlhttp.responseText;
//      $('#dinhide').show(); 
    }
  } 
  //alert(str);
xmlhttp.open("GET","bio_maincatsubcatcp1.php?item="+str,true);
xmlhttp.send(); 
 }
 
</script>