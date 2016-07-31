<?php
    $PageSecurity = 80;
    include ('includes/session.inc');
$title = _('Change Installation Date');
include ('includes/header.inc'); 
 echo "<form method='POST' name=form1 action='" . $_SERVER['PHP_SELF'] . "'>"; 
 if($_POST['submit'])
 {
      $stockid=$_POST['combo2'];
   $debtor=$_POST['debtor'];
               $sqll="Update bio_oldorders set plantid='".$stockid."' where debtorno='".$debtor."'";
          $result13 = DB_query($sqll,$db);
          ?>
<script type="text/javascript">
window.close();
</script>
<?php
 }
  echo '<input type="hidden" name="debtor" value="'.$_GET['DebtorNo'].'">';
   echo"<fieldset style='width:90%;'>";
       echo"<legend><h3>PLant model</h3></legend><table>";
       echo "<tr><td>Main Category</td>";
         echo '<td><select name="CategoryID" style="width:200px" onchange="view2(this.value)" >';
 /* if($_POST['CategoryID'])
   {*/
  $sql = "SELECT
  `maincatid`,
  `maincatname`
FROM `biotech`.`bio_maincategorymaster`
WHERE maincatid IN (SELECT maincatid FROM substockcategory GROUP BY maincatid)";
   $ErrMsg = _('The stock categories could not be retrieved because');
   $DbgMsg = _('The SQL used to retrieve stock categories and failed was');
   $result = DB_query($sql,$db,$ErrMsg,$DbgMsg);
   echo '<option value=0></option>';
while ($myrow=DB_fetch_array($result)){
    {
        echo '<option value="'. $myrow['maincatid'] . '">' . $myrow['maincatname'].'</option>';
    }
   
}
echo "</select></td>";
 echo '</tr><tr><td>Sub Category</td><td><select name="combo2" id="combo2"  onchange="view3(this.value)" style="width:200px">';
   /* if($_POST['CategoryID'])
    {
   $sql="SELECT stockitemproperty.`stockid` , stockmaster.description
FROM `stockitemproperty` , stockmaster
WHERE stockitemproperty.stockid = stockmaster.stockid
AND model LIKE='".$_POST['CategoryID']."'";    
   $ErrMsg = _('The stock categories could not be retrieved because');
   $DbgMsg = _('The SQL used to retrieve stock categories and failed was');
   $result = DB_query($sql,$db,$ErrMsg,$DbgMsg);
    }*/
     /*  echo '<option></option>';
   while ($myrow=DB_fetch_array($result))
        {
          if($myrow['stockid']==$_POST['combo2'] || $myrow['stockid']==$_GET['select'])
            {
             echo '<option selected value="'. $myrow['stockid'] . '">' . $myrow['description'].'</option>';
            }
            else
             {
              echo '<option value="'. $myrow['stockid'] . '">' . $myrow['description'].'</option>';
              }
    
        }   */
      echo "</select></td></tr>";  
       
          echo '</tr><tr><td>Category</td><td><select name="combo3" id="combo3"  onchange="view4(this.value)" style="width:200px">';
               echo "</select></td></tr>";  
                echo '</tr><tr><td>Capacity</td><td><select name="combo4" id="combo4"  style="width:200px">';
               echo "</select></td></tr>"; 
       echo "<tr><td><input type='submit' name='submit'></td></tr>";
       echo '</fieldset>';
 if($_GET['DebtorNo'])
 {                         
     $sql="select * from bio_oldorders where debtorno= '".$_GET['DebtorNo']."'";
     $result=DB_query($sql,$db);
$mr1=DB_fetch_array($result);
if($mr1[0]==null)
{
 ?>

 <?php    
}
 }
?>
<script type="text/javascript">
function view2(str1)
{

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
     document.getElementById("combo2").innerHTML=xmlhttp.responseText; 
     
    }            
  } 
xmlhttp.open("GET","modelajax.php?main="+str1,true);
xmlhttp.send();        
}
   function view3(str1)
{

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
     document.getElementById("combo3").innerHTML=xmlhttp.responseText; 
     
    }            
  } 
xmlhttp.open("GET","modelajax.php?sub="+str1,true);
xmlhttp.send();        
}
  function view4(str1)
{

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
     document.getElementById("combo4").innerHTML=xmlhttp.responseText; 
     
    }            
  } 
xmlhttp.open("GET","modelajax.php?cat="+str1,true);
xmlhttp.send();        
}
</script>