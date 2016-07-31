<?php
  $PageSecurity = 80;
include('includes/session.inc');
$title = _('Add Funding Agency');
include('includes/header.inc');  
global $lead;
global $stockid;


if ($_GET['leadid']!='' AND $_GET['stockid']!='') {
 $lead=$_GET['leadid'];
 $_SESSION['leadid']=$lead;
 $stockid=$_GET['stockid'];
 $_SESSION['stockid']=$stockid;  
   $_SESSION['flag']=$_GET['flag'];       
}

echo"<br />";
echo"<br />";


 if (isset($_POST['SelectedType'])){
    $SelectedType = strtoupper($_POST['SelectedType']);
} elseif (isset($_GET['SelectedType'])){
    $SelectedType = strtoupper($_GET['SelectedType']);
}

if (isset($Errors)) {
    unset($Errors);
}

  


if(isset($_GET['delete']) AND $_GET['delete']=='yes'){
    $sub_amount=$_GET['amount'];
   $scheme=$_GET['scheme'];
   $lead=$_SESSION['leadid'];
   $stockid=$_SESSION['stockid'];
   
  echo $flag= $_SESSION['flag'];  
    
    
    $sql_temp="SELECT subsidy,netprice,tprice
                FROM bio_temppropitems
                WHERE stockid='".$_SESSION['stockid']."'
                AND leadid ='".$_SESSION['leadid']."'";
    $result_temp=DB_query($sql_temp,$db);
    $myrow_temp=DB_fetch_array($result_temp);
    $subsidy=$myrow_temp['subsidy']-$sub_amount;
    $netprice=$myrow_temp['netprice']+$sub_amount;
      
    $sql_delete="DELETE FROM bio_temppropsubsidy
                   WHERE bio_temppropsubsidy.scheme='$scheme'
                   AND bio_temppropsubsidy.leadid=".$lead."
                   ";
    
    $result_delete = DB_query($sql_delete,$db);
    
    if($result_delete){
        $sql_price="UPDATE bio_temppropitems SET subsidy=".$subsidy.",
                                               netprice=".$netprice." 
                                         WHERE leadid=".$_SESSION['leadid']." 
                                         AND stockid='".$_SESSION['stockid']."'";
      $result_price=DB_query($sql_price,$db);
        
    }
    
    
    ?> 
  <script type="text/javascript">
  
  var id=<?php echo $lead;?>;
  var stock='<?php echo $stockid; ?>';

   window.opener.location='bio_lsgproposal.php?FAchange='+<?php echo $lead; ?>+'&flag='+<?php echo $flag;?>;

//  window.close();
  </script>
  <?php
    
}


if (isset($_POST['submit'])) 
{
    $fund=$_POST['fund'];
    $amount=$_POST['Amount'];
    
    $lead=$_SESSION['leadid'];
 $stockid=$_SESSION['stockid'];  
 //$scheme=$_POST['Scheme'];
 
  $flag=$_SESSION['flag'];


       
 if ( isset($_POST[SelectedType])){

        $sql = "UPDATE bio_temppropsubsidy 
                   SET stockid='".$_SESSION['stockid']."',
                       scheme='".$_POST['fund']."',
                       amount='" . $_POST['Amount'] ."'                        
                   WHERE scheme='" . $SelectedType . "'";
                   
        $msg = _('The funding agency') . ' ' . $SelectedType . ' ' .  _(' has been updated');
        
 }                      
 else
 
 {
      $checkSql = "SELECT count(*)
      
                 FROM bio_temppropsubsidy
                 WHERE 
                  scheme= '" . $_POST['fund'] . "'";

        $checkresult = DB_query($checkSql,$db);
        $checkrow = DB_fetch_row($checkresult);

        if ( $checkrow[0] <= 0 ) {
            
           
        
   $sql = "INSERT INTO bio_temppropsubsidy(leadid,stockid,scheme,amount)
                    VALUES ( '".$_SESSION['leadid']."', 
                             '".$_SESSION['stockid'] . "',
                            '" . $_POST['fund'] . "',
                            '" . $_POST['Amount'] . "')";
   prnMsg( _('The Funding Agency ') . $_POST['fund'] . _(' has been created successfully.'),'success');
  
    }
    else
 {
   prnMsg( _('The Scheme ') . $_POST['fund'] . _(' already exist.'),'warn');   
 }
  
 }
 $result=DB_query($sql,$db);
 

 
/* $flag=$_POST['Flag'];
 $first=$_POST['First'];
 $add=$_POST['Add'];           
 $approval=$_SESSION['approval'];
 //echo"<input type=text name=approval id=approval value=$approval>";
    
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
    
    
    else{  */
    
       /*$sql_sub="INSERT INTO bio_temppropsubsidy(leadid,
                                              stockid,
                                              scheme,
                                              amount) 
                                       VALUES(".$_POST['LeadID'].",
                                              '".$_SESSION['stockid']."',
                                              '".$fund."',
                                              '".$amount."')";
    $result_sub=DB_query($sql_sub,$db);  */
    
/*    if($result_sub){
          
      
      $sql_price="UPDATE bio_temppropitems SET subsidy=".$subsidy.",
                                               netprice=".$netprice." 
                                         WHERE leadid=".$_SESSION['leadid']." 
                                         AND stockid='".$_SESSION['stockid']."'";
      $result_price=DB_query($sql_price,$db);
      }      */
    
    
    ?>
  <script type="text/javascript">
  
  var id=<?php echo $lead;?>;
  var stock='<?php echo $stockid; ?>';

  window.opener.location='bio_lsgproposal.php?Echange='+<?php echo $lead;  ?>+'&flag='+<?php echo $flag;?>;

//  window.close();
  </script>  
 <?php   
 }
 
echo "<table style='width:70%'><tr><td>";
echo "<fieldset style='width:80%;height=300px'>";     
echo "<legend><h3>Add Funding Agency</h3>";
echo "</legend>";
//if (! isset($_GET['delete'])) {   
echo '<form name="activeLeadsForm"  method="post" action="' . $_SERVER['PHP_SELF'] . '?' .SID .'">';


//echo '<input type="text" name="flag" id="flag" value="'.$flag.'" />';        
echo '<table width=80%>';


    if ($_GET['scheme']!='') {
        
        $sql_edit = "SELECT * FROM bio_temppropsubsidy 
                 WHERE bio_temppropsubsidy.scheme='".$_GET['scheme']."'
                   AND bio_temppropsubsidy.leadid=".$_SESSION['leadid']."
                   ";  
                              // AND bio_temppropsubsidy.stockid='".$_SESSION['stockid']."'
        $result_edit = DB_query($sql_edit,$db);
        $myrow_edit=DB_fetch_array($result_edit);
        
        $_POST['Scheme']=$myrow_edit['scheme'];
        $_POST['Amount']=$myrow_edit['amount'];
     echo '<input type="hidden" name="SelectedType" value="' . $_GET['scheme'] . '">';     
    }
    
    
 /*if ( isset($SelectedType) AND $SelectedType!='' )
  {
  $sql = "SELECT stockid,scheme,amount,                       
                FROM bio_temppropsubsidy
                WHERE stockidid='" . $SelectedType . "'";

        $result = DB_query($sql, $db);
        $myrow = DB_fetch_array($result);

        //$_POST['stockid'] = $myrow['stockid'];         
        $_POST['Scheme'] = $myrow['scheme'];
        $_POST['Amount'] = $myrow['amount'];

        echo '<input type="hidden" name="SelectedType" value="' . $SelectedType . '">';
        echo '<input type="hidden" name="stockid" value="' . $_POST['stockid'] . '">';
}   
    if (!isset($_POST['Scheme'])) {
        $_POST['Scheme']='';
    }
    if (!isset($_POST['Amount'])) {
        $_POST['Amount']='';
    }                        */
 

/* $sql_item = "SELECT stockmaster.longdescription
                  FROM stockmaster
                  WHERE stockmaster.stockid='".$_GET['stockid']."'";               
    $result_item = DB_query($sql_item,$db);
    $myrow_item=DB_fetch_array($result_item); */
    //echo'<tr><td>' . _('Item') . ':</td>';
    //echo'<td><input type="hidden" name="StockID" id="stockid" value="' .$_SESSION['stockid']  . '" style="width:200px">'.$myrow_item[0].'</td></tr>';                                                                                                               
    

    $sql_fund ="SELECT debtorno,name FROM debtorsmaster WHERE typeid=9";
    $result_fund=DB_query($sql_fund, $db);       
 
    echo '<tr><td>' . _('Funding Agency') . ':</td>'; 
    echo '<td><select name="fund" id="fund" style="width:205px">';
    $f=0;
    while($myrow1=DB_fetch_array($result_fund))
    { 
    if ($myrow1['debtorno']==$_POST['Scheme']) 
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
    echo $myrow1['debtorno'] . '">'.$myrow1['name'];
    echo '</option>';
    }
    echo '</select></td></tr>';  
    
    
    
    echo '<tr><td>' . _('Amount') . ':</td>
            <td><input type="text" name="Amount" id="Amount" value="' . $_POST['Amount'] . '" style="width:200px"></td>
        </tr>';
        
    
      
    
    echo '<tr><td colspan=2><p><div class="centre"><input type=submit name=submit value="' . _('Add') . '" style="width:100px" onclick="if(log_in()==1)return false;"></div>';

    echo'<input type="hidden" name="LeadID" id="leadid" value="' . $_SESSION['leadid'] . '" style="width:200px">'; 


echo '</table>';

echo'</form>';

echo "</fieldset></td></tr>";

echo "<tr><td>";
echo "<fieldset style='width:80%;height=300px'>";     
echo "<legend><h3>Funding Agency Details</h3>";
echo "</legend>";

/*$sql_check="SELECT * FROM bio_temppropsubsidy WHERE leadid=".$_SESSION['leadid']." AND stockid='".$_SESSION['stockid']."'";
$result_check=DB_query($sql_check,$db);
$count=DB_num_rows($result_check);

if($count<=0)
{
    $sql_select="SELECT * FROM bio_cpsubsidy WHERE leadid=".$_SESSION['leadid']." AND stockid='".$_SESSION['stockid']."'";
    $result_select=DB_query($sql_select,$db);
    
    while($row_select=DB_fetch_array($result_select))
    {
        
       echo $sql_insert="INSERT INTO bio_temppropsubsidy (leadid,stockid,scheme,amount)
                                               VALUES(".$row_select['leadid'].",'".$row_select['stockid']."','".$row_select['scheme']."','".$row_select['amount']."')";
        //DB_query($sql_insert,$db);                                       
    }
}     */
/*
        $sql = "SELECT bio_temppropsubsidy.stockid,        
                       stockmaster.longdescription,
                       bio_temppropsubsidy.scheme,
                       
                       bio_temppropsubsidy.amount 
                  FROM stockmaster,
                       bio_schemes,
                       bio_temppropsubsidy 
                 WHERE stockmaster.stockid=bio_temppropsubsidy.stockid
                   AND bio_schemes.debtorcode=bio_temppropsubsidy.scheme
                   AND bio_temppropsubsidy.leadid=".$_SESSION['leadid']."
                   AND bio_temppropsubsidy.stockid='".$_SESSION['stockid']."'";    
                                                                                */
                   
                           $sql = "SELECT bio_temppropsubsidy.stockid,
                           bio_temppropsubsidy.scheme,          
                        bio_temppropsubsidy.amount 
                 FROM   bio_temppropsubsidy 
                 LEFT JOIN bio_schemes ON  bio_schemes.debtorcode=bio_temppropsubsidy.scheme";   
                   
    $result = DB_query($sql,$db);
echo "<div style='overflow:scroll;height:150px'>";
    echo '<table class="selection" width="100%">';
    echo '<tr>  
        <th>' . _('SlNo') . '</th>
        <th>' . _('Funding Agency') . '</th>
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
            <td><a href="%sSelectedType=%s&scheme=%s">' . _('Edit') . '</td>
            <td><a href="%sSelectedType=%s&scheme=%s&delete=yes&amount=%s" onclick=\'return confirm("' .
                _('Are you sure you wish to delete this Subsidy Amount?') . '");\'>' . _('Delete') . '</td>
        </tr>',
        $slno,
        
        $myrow[1],
        $myrow[2], 
        $_SERVER['PHP_SELF'] . '?', 
        $myrow[0],$myrow[1],
        $_SERVER['PHP_SELF'] . '?', 
        $myrow[0],$myrow[1],$myrow[2]);
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

