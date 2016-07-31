<?php
  $PageSecurity = 80; 
include('includes/session.inc'); 

$title = _('Inspection');    
include('includes/header.inc');  


if(isset($_POST['submit']))
{    
    if($_POST['plant']==1){
        $confirmation_plant=1;
    }else{
        $confirmation_plant=0;
    }
    if($_POST['document']==1){
        $confirmation_doc=1;
    }else{
        $confirmation_doc=0;
    }
   $sql="UPDATE bio_childcustomer SET inspectedBy='".$_POST['inspection']."',
                                      inspectionStatus='".$confirmation_plant."',documentStatus='".$confirmation_doc."'
                                 WHERE id='".$_POST['childid']."'";
    DB_query($sql,$db);                             
}
  


echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '">';
echo '<table style=width:75%><tr><td>';
echo '<div id="panel">';
echo '<fieldset style="height:250px">'; 

echo '<legend><b>Inspection Details</b></legend>';
echo '<br><br><table class="selection">'; 

$sql="SELECT bio_childcustomer.dealercode,
             debtorsmaster.name,
             bio_childcustomer.name AS dealerscustomer,
             stockmaster.description 
        FROM bio_childcustomer,debtorsmaster,stockmaster 
       WHERE debtorsmaster.debtorno=bio_childcustomer.dealercode
         AND stockmaster.stockid=bio_childcustomer.stockid
         AND bio_childcustomer.id='".$_GET['childid']."'";
$result=DB_query($sql,$db);
$myrow=DB_fetch_array($result);         


echo '<tr><td>Dealer Name</td><td><input type="text" size="40px" name="dealername" id="dealername" value="'.$myrow['name'].'"></td></tr>';
echo '<tr><td>Customer Name</td><td><input type="text" size="40px" name="dealerscust" id="dealerscust" value="'.$myrow['dealerscustomer'].'"></td></tr>'; 
echo '<tr><td>Plant</td><td><input type="text" size="40px" name="plant" id="plant" value="'.$myrow['description'].'"></td></tr>';
echo '<tr><td>Inspected By</td><td><input type="text" size="40px" name="inspection" id="inspection"></td></tr>';
echo '<tr></tr>';

$sql="SELECT inspectionStatus,documentStatus FROM bio_childcustomer WHERE id='".$_GET['childid']."' ";
$result=DB_query($sql,$db);
$myrow=DB_fetch_array($result);

  
echo '<tr><td>                   </td>  <td colspan=2><b>Confirm         &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;            Not Confirmed</b>    </tr>'; 
if($myrow['inspectionStatus']==0){               
echo '<tr><td>Installation Status</td>  <td colspan=2>&nbsp;&nbsp;&nbsp;<input type="radio" name="plant" id="plant" value=1>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                        <input type="radio" checked name="plant" id="plant" value=0></td>   </tr>';
}else{
echo '<tr><td>Installation Status</td>  <td colspan=2>&nbsp;&nbsp;&nbsp;<input checked type="radio" name="plant" id="plant" value=1>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                        <input type="radio" name="plant" id="plant" value=0></td>   </tr>';    
}

if($myrow['documentStatus']==0){  
echo '<tr><td>Document Status   </td>   <td colspan=2>&nbsp;&nbsp;&nbsp;<input type="radio" name="document" id="document" value=1>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   
                                                                        <input checked type="radio" name="document" id="document" value=0></td></tr>'; 
}else{
echo '<tr><td>Document Status   </td>   <td colspan=2>&nbsp;&nbsp;&nbsp;<input checked type="radio" name="document" id="document" value=1>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   
                                                                        <input type="radio" name="document" id="document" value=0></td></tr>';     
}
 

echo '<input type="hidden" name="childid" id="childid" value='.$_GET['childid'].'>';  

echo '<tr><td></td><td> <input type="Submit" name="submit" value="' . _('Submit') . '" onclick=" if(validate()==1)return false"></td></tr>';   
echo '</table>';
echo '</fieldset></form>'; 

?>
