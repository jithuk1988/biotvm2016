<?php
  $PageSecurity = 80;
include('includes/session.inc');
$title = _('Add Subsidy');
include('includes/header.inc');

global $lead;
global $stockid;
global $flag;
global $first;
global $add;

if ($_GET['ledid']!='' AND $_GET['item']!='') {
 $lead=$_GET['ledid'];
 $_SESSION['leadid']=$lead;
 $stockid=$_GET['item'];
 $_SESSION['stockid']=$stockid;
 $flag=$_GET['flag'];
 $_SESSION['flag']=$flag;
 $first=$_GET['first'];
 $add=$_GET['add'];
 
 
}

echo"<br />";
echo"<br />";


if (isset($_POST['submit'])) {
    
    $scheme=$_POST['Scheme'];
    $amount=$_POST['Amount'];
    
    $lead=$_SESSION['leadid'];
 $stockid=$_SESSION['stockid'];
 $flag=$_POST['Flag'];
 $first=$_POST['First'];
 $add=$_POST['Add'];

    
    $sql_temp="SELECT subsidy,netprice,tprice
                FROM bio_temppropitems
                WHERE stockid='".$_SESSION['stockid']."'
                AND leadid ='".$_SESSION['leadid']."'";
      $result_temp=DB_query($sql_temp,$db);
      $myrow_temp=DB_fetch_array($result_temp);
      $myrow_temp['subsidy'];
      
      $subsidy=$myrow_temp['subsidy']+$amount;
      $netprice=$myrow_temp['netprice']-$amount;
      $tprce=$myrow_temp['tprice'];
    
    
    
    $sql_check = "SELECT COUNT(*) FROM bio_temppropsubsidy 
                 WHERE bio_temppropsubsidy.scheme=".$_POST['Scheme']."
                   AND bio_temppropsubsidy.leadid=".$_SESSION['leadid']."
                   AND bio_temppropsubsidy.stockid='".$_SESSION['stockid']."'";  
    $result_check = DB_query($sql_check,$db);
    $myrow_check=DB_fetch_array($result_check);
    if($myrow_check[0]>0){
        $sql_sub="UPDATE bio_temppropsubsidy SET amount=".$_POST['Amount']." 
                                       WHERE bio_temppropsubsidy.scheme=".$_POST['Scheme']."
                                       AND bio_temppropsubsidy.leadid=".$_SESSION['leadid']."
                                       AND bio_temppropsubsidy.stockid='".$_SESSION['stockid']."'";
      $result_sub=DB_query($sql_sub,$db);
      if($result_sub){
       $sql_sub_amount="SELECT SUM(amount) FROM bio_temppropsubsidy
            WHERE bio_temppropsubsidy.leadid=".$_SESSION['leadid']."
            AND bio_temppropsubsidy.stockid='".$_SESSION['stockid']."'";
       $result_sub_amount=DB_query($sql_sub_amount,$db);
       $myrow_sub_amount=DB_fetch_array($result_sub_amount);
       $subsidy=$myrow_sub_amount[0];   
       $netprice=$tprce-$subsidy;   
          
      
      $sql_price="UPDATE bio_temppropitems SET subsidy=".$subsidy.",
                                               netprice=".$netprice." 
                                         WHERE leadid=".$_SESSION['leadid']." 
                                         AND stockid='".$_SESSION['stockid']."'";
      $result_price=DB_query($sql_price,$db);
      }
      
      
    }
    
    
    else{
        $sql_sub="INSERT INTO bio_temppropsubsidy(leadid,
                                              stockid,
                                              scheme,
                                              amount) 
                                       VALUES(".$_POST['LeadID'].",
                                              '".$_POST['StockID']."',
                                              '".$scheme."',
                                              '".$amount."')";
    $result_sub=DB_query($sql_sub,$db);
    if($result_sub){
          
      
      $sql_price="UPDATE bio_temppropitems SET subsidy=".$subsidy.",
                                               netprice=".$netprice." 
                                         WHERE leadid=".$_SESSION['leadid']." 
                                         AND stockid='".$_SESSION['stockid']."'";
      $result_price=DB_query($sql_price,$db);
      }
    
    }
    
     
   
   
  unset($_POST['Scheme']);  
  unset($_POST['Amount']);
  
  ?> 
  <script type="text/javascript">
  
  var id=<?php echo $lead;?>;
  var stock='<?php echo $stockid; ?>';
  window.opener.location='bio_proposal.php?add=1&lead='+<?php echo $lead;?>+'&flag2='+<?php echo $flag;?>+'&first=3'+'&stockid='+stock;

  
//  window.close();
  </script>
  <?php
      
} elseif ( isset($_GET['delete']) ) {
    
        $SelectedType=$_GET['SelectedType'];
//        $sub_amount=$_GET['amount'];
        $lead=$_SESSION['leadid'];
        $stockid=$_SESSION['stockid'];
        $flag=$_POST['Flag'];
        
        $sql1="SELECT amount 
               FROM   bio_temppropsubsidy 
               WHERE  leadid='" . $_GET['lead'] . "'
               AND    stockid='" . $_GET['SelectedType'] . "'
               AND    scheme='" . $_GET['scheme'] . "'";
        $result1=DB_query($sql1,$db);
        $row1=DB_fetch_array($result1);
        $amount1=$row1['amount'];       
        
    $sql_temp="SELECT subsidy,netprice,tprice
                FROM bio_temppropitems
                WHERE stockid='".$_SESSION['stockid']."'
                AND leadid ='".$_SESSION['leadid']."'";
    $result_temp=DB_query($sql_temp,$db);
    $myrow_temp=DB_fetch_array($result_temp);
    $subsidy=$myrow_temp['subsidy']-$amount1;
    $netprice=$myrow_temp['netprice']+$amount1;

        $sql="DELETE FROM bio_temppropsubsidy 
                WHERE leadid='" . $_GET['lead'] . "' 
                AND stockid='" . $_GET['SelectedType'] . "' 
                AND scheme='" . $_GET['scheme'] . "'";
        $ErrMsg = _('The Subsidy could not be deleted because');
        $result = DB_query($sql,$db,$ErrMsg);
        prnMsg(_('Subsidy for ') . $SelectedType  . ' ' . _('has been deleted') ,'success');

        unset ($SelectedType);
        unset($_GET['delete']);
        
        if($result){
        $sql_price="UPDATE bio_temppropitems SET subsidy=".$subsidy.",
                                               netprice=".$netprice." 
                                         WHERE leadid=".$_SESSION['leadid']." 
                                         AND stockid='".$_SESSION['stockid']."'";
        $result_price=DB_query($sql_price,$db);
        
    }
    
      ?> 
  <script type="text/javascript">
                 // alert('gggg');
  var id=<?php echo $lead;?>;
  var stock='<?php echo $stockid; ?>';
  var flag='<?php echo $_SESSION['flag']; ?>';
  window.opener.location='bio_proposal.php?add=1&lead='+<?php echo $lead;?>+'&flag2='+flag+'&first=3'+'&stockid='+stock;

  
//  window.close();
  </script>
  <?php

}





echo "<table style='width:70%'><tr><td>";
echo "<fieldset style='width:80%;height=300px'>";     
echo "<legend><h3>Add Subsidy</h3>";
echo "</legend>";
if (! isset($_GET['delete'])) {   
echo '<form name="activeLeadsForm"  method="post" action="' . $_SERVER['PHP_SELF'] . '?' .SID .'">';

echo '<table width=80%>';


    if ($_GET['scheme']!='') {
        
        $sql_edit = "SELECT * FROM bio_temppropsubsidy 
                 WHERE bio_temppropsubsidy.scheme=".$_GET['scheme']."
                   AND bio_temppropsubsidy.leadid=".$_SESSION['leadid']."
                   AND bio_temppropsubsidy.stockid='".$_SESSION['stockid']."'";  
                   
        $result_edit = DB_query($sql_edit,$db);
        $myrow_edit=DB_fetch_array($result_edit);
        
        $_POST['Scheme']=$myrow_edit['scheme'];
        $_POST['Amount']=$myrow_edit['amount'];
        
    }
       


     $sql_item = "SELECT     stockmaster.longdescription
                  FROM stockmaster
                 WHERE stockmaster.stockid='".$_SESSION['stockid']."'";               
    $result_item = DB_query($sql_item,$db);
    $myrow_item=DB_fetch_array($result_item);
    echo'<tr><td>' . _('Item') . ':</td>';
    echo'<td><input type="hidden" name="StockID" id="stockid" value="' .$_SESSION['stockid']  . '" style="width:200px">'.$myrow_item[0].'</td></tr>';
    
    $sql1="SELECT * FROM bio_schemes";
    $result1=DB_query($sql1, $db);
 
    echo '<tr><td>' . _('Scheme') . ':</td>'; 
    echo '<td><select name="Scheme" id="Scheme" style="width:205px">';
    $f=0;
    while($myrow1=DB_fetch_array($result1))
    { 
    if ($myrow1['schemeid']==$_POST['Scheme']) 
    {
    echo '<option selected value="';
    
    } else {
        if ($f==0) 
        {
        echo '<option>';
        }
        echo '<option value="';
        $f++;
    }
    echo $myrow1['schemeid'] . '">'.$myrow1['scheme'];
    echo '</option>';
    }
    echo '</select></td></tr>';
    
    echo '<tr><td>' . _('Amount') . ':</td>
            <td><input type="text" name="Amount" id="Amount" value="' . $_POST['Amount'] . '" style="width:200px"></td>
        </tr>';
    echo'<input type="hidden" name="LeadID" id="leadid" value="' . $_SESSION['leadid'] . '" style="width:200px">';
    echo'<input type="hidden" name="Flag" id="flag" value="' . $_SESSION['flag'] . '" style="width:200px">';
    echo'<input type="hidden" name="First" id="first" value="' . $first . '" style="width:200px">';
    echo'<input type="hidden" name="Add" id="add" value="' . $add . '" style="width:200px">';
    
     echo '<tr><td colspan=2><p><div class="centre"><input type=submit name=submit value="' . _('Add') . '" style="width:100px" onclick="if(log_in()==1)return false;"></div>';




echo '</table>';

echo'</form>';
}
echo "</fieldset></td></tr>";

echo "<tr><td>";
echo "<fieldset style='width:80%;height=300px'>";     
echo "<legend><h3>Subsidy Details</h3>";
echo "</legend>";

$sql = "SELECT bio_temppropsubsidy.stockid,        
                       stockmaster.longdescription,
                       bio_temppropsubsidy.scheme,
                       bio_schemes.scheme,
                       bio_temppropsubsidy.amount 
                  FROM stockmaster,
                       bio_schemes,
                       bio_temppropsubsidy 
                 WHERE stockmaster.stockid=bio_temppropsubsidy.stockid
                   AND bio_schemes.schemeid=bio_temppropsubsidy.scheme
                   AND bio_temppropsubsidy.leadid=".$_SESSION['leadid']."
                   AND bio_temppropsubsidy.stockid='".$_SESSION['stockid']."'";  
                   
    $result = DB_query($sql,$db);
echo "<div style='overflow:scroll;height:150px'>";
    echo '<table class="selection" width="100%">';
    echo '<tr>  
        <th>' . _('SlNo') . '</th>
        <th>' . _('Item') . '</th>
        <th>' . _('Scheme') . '</th>
        <th>' . _('Amount') . '</th> 
        </tr>';

$k=0; //row colour counter
$slno=0;
while ($myrow = DB_fetch_row($result)) {
    if ($k==1){
        echo '<tr class="EvenTableRows">';
        $k=0;
    } else {
        echo '<tr class="OddTableRows">';
        $k=1;
    }
    $slno++;
    printf('<td>%s</td>
            <td>%s</td>  
            <td>%s</td>
            <td>%s</td>
            <td><a href="%sSelectedType=%s&scheme=%s">' . _('Edit') . '</td>
            <td><a href="%sSelectedType=%s&scheme=%s&lead=%s&amount=%s&delete=yes" onclick=\'return confirm("' .
                _('Are you sure you wish to delete this Subsidy Amount?') . '");\'>' . _('Delete') . '</td>
        </tr>',
        $slno,
        $myrow[1],
        $myrow[3],
        $myrow[4], 
        $_SERVER['PHP_SELF'] . '?', 
        $myrow[0],$myrow[2],
        $_SERVER['PHP_SELF'] . '?', 
        $myrow[0],$myrow[2],$_SESSION['leadid'],$myrow[4]);
    }
    //END WHILE LIST LOOP
    echo '</table>';
    echo "</div>";






echo "</fieldset></td></tr>";
echo"</table>";
//include('includes/footer.inc');
?>
<script type="text/javascript">
    document.getElementById('Scheme').focus();
function log_in()
{

var f=0;
var p=0;
if(f==0){f=common_error('Scheme','Please select a Scheme');  if(f==1){return f; }  }
if(f==0){f=common_error('Amount','Please enter Amount');  if(f==1){return f; }  }

if(f==0){var x=document.getElementById('Amount').value;  
            if(isNaN(x)||x.indexOf(" ")!=-1)
           {  f=1;
              alert("Please enter Numeric value for Amount"); document.getElementById('Amount').focus();
              if(x=""){f=0;}
              return f; 
           }
}

}

</script>