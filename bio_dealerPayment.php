<?php
$PageSecurity = 80;
include('includes/session.inc');
$title = _('Dealers Payment');

include('includes/header.inc');



if($_POST['submit'])
{
    if($_POST['mode']==1)
    {
        
    $sql="UPDATE bio_businessassociates_enq SET advance='".$_POST['advanceamt']."',mode='".$_POST['mode']."'";
    
    }else
    {
    $sql="UPDATE bio_businessassociates_enq SET advance='".$_POST['advanceamt']."',
                                                   mode='".$_POST['mode']."',
                                                   chdddate='".ConvertSQLDate($_POST['amtdate'])."',
                                                   no='".$_POST['amtno']."',
                                                   bank='".$_POST['amtbank']."'
                                           WHERE buss_id='".$_POST['busid']."'";   
    }
    DB_query($sql,$db);
}




 $busid=$_GET['busid'];

echo "<table border=0 style='width:70%';><tr><td>";  
echo "<fieldset style='width:80%;height:250px'>";
echo "<legend><h3>Payment</h3></legend>";  
 
echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "?" . SID . "'>";    

echo "<input type=hidden name=busid value=$busid>";
   echo '<table>';
   echo '<tr><td width=50%>Advance Amount</td>';
   echo "<td><input type=text name=advanceamt id='advance' style='width:90%'></td></tr>";
   
   echo'<tr>';
echo'<td>Mode of payment</td>';
echo'<td><select name="mode" id="paymentmode" style="width:130px" tabindex=19 onchange="advdetail(this.value)">';
$sql1="SELECT * FROM bio_paymentmodes";
$result1=DB_query($sql1, $db); 
$f=0;
while($myrow1=DB_fetch_array($result1))
{
    if ($myrow1['id']==$_POST['modes'])
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
    echo $myrow1['id'] . '">'.$myrow1['modes'];  
    echo '</option>';
  }
echo'</select></td></tr>';  
         
echo"<tr><td colspan=2><table style='width:100%' id='amt'>";


echo"</table></td></tr>";    

 echo"<tr><td></td><td><br /><input type=submit name=submit value=Submit></td></tr>";   

?>


<script>

function advdetail(str){
if (str=="")
  {
  document.getElementById("amt").innerHTML="";
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
    document.getElementById("amt").innerHTML=xmlhttp.responseText;
      document.getElementById("amtdate").focus();
    }
  } 
xmlhttp.open("GET","bio_amountdetailsdeal.php?adv=" + str,true);
xmlhttp.send();    
}

</script>