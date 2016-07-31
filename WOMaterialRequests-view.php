<?php
/* $Revision: 1.25 $ */

$PageSecurity = 11;

include('includes/session.inc');
$title = _('View SRs against a WO');
include('includes/header.inc');
include('includes/SQL_CommonFunctions.inc');

echo '<a href="'. $rootpath . '/index.php?' . SID . '&Application='.'manuf'.'">' . _('Back to Manufacturing page'). '</a><br>';


echo '<form action="' . $_SERVER['PHP_SELF'] . '?' . SID . '" method=post>';        
if(isset($_POST['status']))     {
$count=$_POST['count'] ; 
for ($i=0;$i<$count;$i++){ 
    
//echo"<br>".$_POST['StatusID'.$i];

//echo"...............".$_POST['reqno'.$i];     
 $sql4="SELECT statusid
        FROM womaterialrequest
        WHERE reqno='".$_POST['reqno'.$i]."'"; 
        
 $result4=DB_query($sql4,$db); 
 $myrow4=DB_fetch_array($result4);  

 if($myrow4[0]!=$_POST['StatusID'.$i])   {
//     echo "<br>".$_POST['reqno'.$i];
//     echo "<br>".$myrow4[0];
//     echo"<br>".$_POST['StatusID'.$i];

    $sql5="UPDATE womaterialrequest
           SET statusid='".$_POST['StatusID'.$i]."'
           WHERE reqno='".$_POST['reqno'.$i]."'";
    $result5=DB_query($sql5,$db);       
 }    
}
    
}
if (isset($_POST['Request'])){

//echo"<br>".$_POST['WO'];
//echo"<br>".$_POST['StockID'];
$count=$_POST['count'] ; 
             $ReqID=0;
            $sql1="SELECT reqno
                   FROM womaterialrequestdetails";
            $result1 = DB_query($sql1, $db); 
            while($myrow1= DB_fetch_array($result1))    {
                
                $ReqID=$myrow1[0];
            }      
               $ReqID++;
               
                  $sql2="INSERT INTO womaterialrequest (reqno,
                  wono) VALUES ( ".$ReqID.",
                  ".$_POST['WO']."
                  )"; 
                  
                  $result2 = DB_query($sql2, $db);  
                 
for ($i=0;$i<$count;$i++){

//                  echo"<br>".$_POST['IssueItem'.$i];

//echo"...............".$_POST['StockID'.$i]; 
//break;
   
                  $sql3="INSERT INTO womaterialrequestdetails (reqno,
                  stockid,
                  qtyrequest) VALUES ( ".$ReqID.",
                  '".$_POST['StockID'.$i]."',
                  ".$_POST['IssueItem'.$i]."       
                  
                  )";
                  
              $result3 = DB_query($sql3, $db);    
            }
//    exit;
}

if (!isset($_REQUEST['WO']) OR !isset($_REQUEST['StockID'])) {
    /* This page can only be called with a purchase order number for invoicing*/
    echo '<div class="centre"><strong>'.
        _('Select an SR to view its details').'</strong></div>';
display($db,$Wono); 
    include ('includes/footer.inc');
    exit;
} else {
    echo '<input type="hidden" name="WO" value=' .$_REQUEST['WO'] . '>';
    $_POST['WO']=$_REQUEST['WO'];                                          
    echo '<input type="hidden" name="StockID" value=' .$_REQUEST['StockID'] . '>';
    $_POST['StockID']=$_REQUEST['StockID']; 
}
if (isset($_GET['IssueItem'])){
    $_POST['IssueItem']=$_GET['IssueItem'];
}
if (isset($_GET['FromLocation'])){
    $_POST['FromLocation'] =$_GET['FromLocation'];
}

 

/*User hit the search button looking for an item to issue to the WO */

 

/* Always display quantities received and recalc balance for all items on the order */

$ErrMsg = _('Could not retrieve the details of the selected work order item');
$WOResult = DB_query("SELECT workorders.loccode,
             locations.locationname,
             workorders.requiredby,
             workorders.startdate,
             workorders.closed,
             stockmaster.description,
              stockmaster.decimalplaces,
             stockmaster.units,
             woitems.qtyreqd,
             woitems.qtyrecd
            FROM workorders INNER JOIN locations
            ON workorders.loccode=locations.loccode
            INNER JOIN woitems
            ON workorders.wo=woitems.wo
            INNER JOIN stockmaster
            ON woitems.stockid=stockmaster.stockid
            WHERE woitems.stockid='" . $_POST['StockID'] . "'
            AND woitems.wo =" . $_POST['WO'],
            $db,
            $ErrMsg);

if (DB_num_rows($WOResult)==0){
    prnMsg(_('The selected work order item cannot be retrieved from the database'),'info');
    include('includes/footer.inc');
    exit;
}
$WORow = DB_fetch_array($WOResult);

if ($WORow['closed']==1){
    prnMsg(_('The selected work order has been closed and variances calculated and posted. No more issues of materials and components can be made against this work order.'),'info');
    include('includes/footer.inc');
    exit;
}


echo '<table cellpadding=2 border=0>
    <tr><td class="label">' . _('Issue to work order') . ':</td><td>' . $_POST['WO'] .'</td><td class="label">' . _('Item') . ':</td><td>' . $_POST['StockID'] . ' - ' . $WORow['description'] . '</td></tr>
     <tr><td class="label">' . _('Manufactured at') . ':</td><td>' . $WORow['locationname'] . '</td><td class="label">' . _('Required By') . ':</td><td>' . ConvertSQLDate($WORow['requiredby']) . '</td></tr>
     <tr><td class="label">' . _('Quantity Ordered') . ':</td><td align=right>' . number_format($WORow['qtyreqd'],$WORow['decimalplaces']) . '</td><td colspan=2>' . $WORow['units'] . '</td></tr>
     <tr><td class="label">' . _('Already Received') . ':</td><td align=right>' . number_format($WORow['qtyrecd'],$WORow['decimalplaces']) . '</td><td colspan=2>' . $WORow['units'] . '</td></tr>
    <tr><td colspan=4><hr></td></tr>
     <tr><td class="label">' . _('Date Material Issued') . ':</td><td>' . Date($_SESSION['DefaultDateFormat']) . '</td>
    <td class="label">' . _('Issued From') . ':</td><td>';


echo '</td></tr>
    </table>
    <table>';



echo '</table>';

function display($db,$Wono)  {
    

  
      $where = " ";
    if ($Wono) {
        $where = ' WHERE wono =' . "'"  .  $Wono . "'";
    }
    
    
  echo'<table width=100% valign=top><tr><td valign=top>';  
  
  echo'<table width=50%>'; 
    echo '<tr><th colspan=6>' . _('Pending SRs For this Work Order') . '</th></tr>';
    echo '<tr><th colspan=2>' . _('SR No:') . '</th>
        <th>' . _('WOno') . '</th>
        <th>' . _('SR Date') . '</th>
        <th>' . _('Location') . '</th>
        <th>' . _('Status') . '</th></tr>';
        
                  
        $sql4="SELECT womaterialrequest.reqno,
                      womaterialrequest.wono,
                      womaterialrequest.statusid,
                      womaterialrequest.loccode,
                      womaterialrequest.reqdate
               FROM womaterialrequest
               $where";
        $result4 = DB_query($sql4, $db);
         $i=0;
        while ($myrow4 = DB_fetch_array($result4)){ 
                 
                                if ($k==1){
                echo '<tr class="EvenTableRows">';
                $k=0;
            } else {
                echo '<tr class="OddTableRows">';
                $k++;
            }
        
    $sql5="SELECT status
           FROM status
           WHERE statusid=$myrow4[2]";
    $result5 = DB_query($sql5, $db);    
    $myrow5 = DB_fetch_array($result5) ;
    
         echo'<input type=hidden name="reqno' . $i .'" value='.$myrow4[0].'></td>';    
        echo'<td colspan=2> <input type=submit name=requestdetails value='.$myrow4[0].'></td>';
        echo'<td>'.$myrow4[1].'</td>';
        echo'<td>'.$myrow4[4].'</td>'; 
        echo'<td>'.$myrow4[3].'</td>'; 
        echo'<td align=right>';
          echo'<select name="StatusID' . $i .'" >';

    $sql = "SELECT statusid,status FROM status ORDER BY statusid";
    $SuppCoResult = DB_query($sql,$db);
    
    while ( $SuppCoRow=DB_fetch_array($SuppCoResult)){
        if ($SuppCoRow['statusid'] == $myrow4[2]) {
            echo "<option selected value='" . $SuppCoRow['statusid'] . "'>" . $SuppCoRow['status'];
        } else {
            echo "<option value='" . $SuppCoRow['statusid'] . "'>" . $SuppCoRow['status'];
        }
    }

    echo '</select> ';
        
        echo'</td></tr>'; 
         $i++;    
           
        } 
        echo"<input type=hidden name='count' value=$i>";  
        echo'<tr><td><input type=submit name=status value=update></td></tr>';    
           echo'</table>';  
           
 // end of table showing SRs for the selected WO         
    
    
      echo' </td><td valign=top>';
      
        if(isset($_POST['requestdetails'])) {
        $reqno=$_POST['requestdetails'];    
        displaydetails($db,$reqno);
        
        }
 
      
       
      echo '</td></tr></table>';          
}

function displaydetails($db,$reqno)  {  
    
       echo'<table width=50% align=left>';
             echo '<tr><th colspan=6>' . _('SRs Details for SR No: ') .$reqno.'</th></tr>';
    echo '<tr><th colspan=2>' . _('Item') . '</th>
        <th>' . _('Request Qty') . '</th>
        <th>' . _('WO requirement') . '</th>
        <th>' . _('Qty Issued') . '</th>
        <th>' . _('Qty On Hand') . '</th></tr>';
        

 
            
    $sql3="SELECT stockid,
                  qtyrequest
           FROM womaterialrequestdetails
           WHERE reqno=$reqno";
    $result3 = DB_query($sql3, $db);  
    while ($myrow3 = DB_fetch_array($result3)){
        
                    if ($k==1){
                echo '<tr class="EvenTableRows">';
                $k=0;
            } else {
                echo '<tr class="OddTableRows">';
                $k++;
            }   
        echo'<td colspan=2>'.$myrow3[0].'</td>';
        echo'<td>'.$myrow3[1].'</td></tr>'; 
    } 
  

      echo '</table>';    
    
}
  
echo '</form>';

include('includes/footer.inc');
?>