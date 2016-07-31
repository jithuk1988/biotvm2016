<?php
  $PageSecurity = 80;
include ('includes/session.inc');//
$title = _('Supply Item Select');
include ('includes/header.inc');
include ('includes/SQL_CommonFunctions.inc');

if(isset($_POST['submit']))
 {  
      $sup_id=$_POST['supplier_id'];
  $stype=$_POST['MBFlag'];
      $sitem=$_POST['com3'];
    
 $inta='INSERT INTO `bio_supplieritems`(`supplierid`, `type`, `itemid`) VALUES ("'.$sup_id.'","'.$stype.'","'.$sitem.'") ';
        DB_query($inta,$db); 
         
 }    

 if($_GET['supplierid'] || $_POST['supplier_id'] )
 {
     
       if($_GET['supplierid'] )
{
    $supp_id=$_GET['supplierid'];
}else{$supp_id=$_POST['supplier_id'];} 
 


echo '<p class="page_title_text"><img src="'.$rootpath.'/css/'.$theme.'/images/maintenance.png"  alt="" />' . ' ' . _(' Supply Item Select ') . '</p>';
    
    echo '<form action="' . $_SERVER['PHP_SELF'] . '" method="post">';
  
echo '<table>';
 echo '<tr><td>' . _('Component Type') . ':</td><td>';
            echo "<select name='MBFlag' id='com1' style='width:180px;' onchange='view(this.value)'>";
 echo "<option Selected value=0".'>';
 echo '<option value="M">' . _('Manufactured') . '</option>';
   
    echo '<option value="A">' . _('Assembly') . '</option>';


    echo '<option value="K">' . _('Kit') . '</option>';

    echo '<option value="G">' . _('Phantom') . '</option>';

    echo '<option value="B">' . _('Purchased') . '</option>';

    echo '<option value="D">' . _('Service/Labour') . '</option>';
             echo "</select></td></tr>";
            echo "<tr><td>" . _('Item Type') . ":</td><td><div id='comp1'><select name='com2' id='com2' style='width:180px;' onchange='view2(this.value)'>";
            echo "</select></div></td></tr>";
             echo "<input type=hidden name='SelectedParent' VALUE='$SelectedParent'>";
           echo "<tr><td>". _('Item ') . ":</td><td><div id='comp2'><select name='com3' id='com3' name='Component' style='width:180px;'>";


            echo '</select></div></td></tr>';
            echo '</table>';
              echo'<input type=hidden name="supplier_id" id="supplier_id" value="'.$supp_id.'">';
  echo '<br><center><input type="submit" name="submit"></center></br>';
echo'</form>';
if($_GET['supplierid'] || $_POST['supplier_id'])
{
   if($_GET['supplierid'] )
{
    $sup_id=$_GET['supplierid'];
}else{$sup_id=$_POST['supplier_id'];} 
  echo'<br><table><tr><th>Slno</th><th>Type</th><th>Item Name</th></tr>';
  $sql3="SELECT bio_supplieritems.supplierid,bio_supplieritems.type,stockmaster.description
  FROM bio_supplieritems
  INNER JOIN stockmaster ON bio_supplieritems.itemid=stockmaster.stockid WHERE `supplierid` LIKE '$sup_id' ";
$result3=DB_query($sql3,$db);  
$slno=1; $k=1;
    while($row3=DB_fetch_array($result3))
    {
        if ($k==1){
        echo '<tr class="EvenTableRows">';
        $k=0;
    } else {
        echo '<tr class="OddTableRows">';
        $k=1;
    }    $id=$row3[id];
$gettype=$row3[type];

    if($gettype=='M')
    {
        $type='Manufactured';
    }else  if($gettype=='A')
    {
        $type='Assembly';
    }else if($gettype=='K')
    {
        $type='Kit';
    }else if($gettype=='G')
    {
        $type='Phantom';
    }else if($gettype=='B')
    {
        $type='Purchased';
    }else  if($gettype=='D')
    {
        $type='Service/Labour';
    }
echo"<td align=center>$slno</td>
<td align=center>".$type."</td> 
<td align=center>".$row3[description]."</td> ";
  

$slno++;

    }

 echo '<tbody>';
echo"</tr></tbody></table></br>";
}
 }
?>     
<script>
function view(str3)
{

  if (str3=="")
  {
  document.getElementById("com1").focus();
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
     document.getElementById("comp1").innerHTML=xmlhttp.responseText; 
     
    }            
  } 
xmlhttp.open("GET","bio_supplier_ajax.php?first="+str3,true);//alert(str1);
xmlhttp.send();        
}
function view2(str4)
{
    var  str5=document.getElementById('com1').value;
  if (str4=="")
  {
  document.getElementById("com3").focus();
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
     document.getElementById("comp2").innerHTML=xmlhttp.responseText; 
     
    }            
  } 
xmlhttp.open("GET","bio_supplier_ajax.php?second="+str4+"&first="+str5,true);//alert(str4);
xmlhttp.send();        
}
</script>