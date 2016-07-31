<?php
/* $Revision: 1.25 $ */

//$PageSecurity = 11;

//include('includes/session.inc');
//$title = _('Material Requistion for Work Order');
//include('includes/header.inc');
include('includes/SQL_CommonFunctions.inc');
//
//echo '<a href="'. $rootpath . '/SelectWorkOrder.php?' . SID . '">' . _('Back to Work Orders'). '</a><br>';
//echo '<a href="'. $rootpath . '/WorkOrderCosting.php?' . SID . '&WO=' .  $_REQUEST['WO'] . '">' . _('Back to Costing'). '</a><br>';

//echo '<form action="' . $_SERVER['PHP_SELF'] . '?' . SID . '" method=post>';        

if (isset($_POST['Request'])){


$Reqdate=FormatDateForSQL($_POST['Reqdate']);
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
                  wono,loccode,reqty,reqdate) VALUES ( ".$ReqID.",
                  ".$_POST['WO'].",
                  '".$_POST['FromLocation']."',
                  '".$_POST['FG']."',
                  '".$Reqdate."'
                  )"; 
                  
                  $result2 = DB_query($sql2, $db);  
                 
for ($i=0;$i<$count;$i++){


   
                  $sql3="INSERT INTO womaterialrequestdetails (reqno,
                  stockid,
                  qtyrequest) VALUES ( ".$ReqID.",
                  '".$_POST['StockID'.$i]."',
                  ".$_POST['IssueItem'.$i]."       
                  
                  )";
                  
              $result3 = DB_query($sql3, $db);    
            }

}


if (!isset($_REQUEST['WO']) OR !isset($_REQUEST['StockID'])) {
    /* This page can only be called with a purchase order number for invoicing*/
    echo '<div class="centre"><a href="' . $rootpath . '/SelectWorkOrder.php?' . SID . '">'.
        _('Select a work order to issue materials to').'</a></div>';
    prnMsg(_('This page can only be opened if a work order has been selected. Please select a work order to issue materials to first'),'info');
//    include ('includes/footer.inc');
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
//    include('includes/footer.inc');
    exit;
}
$WORow = DB_fetch_array($WOResult);

if ($WORow['closed']==1){
    prnMsg(_('The selected work order has been closed and variances calculated and posted. No more issues of materials and components can be made against this work order.'),'info');
//    include('includes/footer.inc');
    exit;
}

if (!isset($_POST['IssuedDate'])){
    $_POST['IssuedDate'] = Date($_SESSION['DefaultDateFormat']);
}
    

if (!isset($_POST['IssueItem'])){
    $LocResult = DB_query('SELECT loccode, locationname FROM locations',$db);

//    echo '<select name="FromLocation">';


//    if (!isset($_POST['FromLocation'])){
//        $_POST['FromLocation']=$WORow['loccode'];
//    }

//    while ($LocRow = DB_fetch_array($LocResult)){
//        if ($_POST['FromLocation'] ==$LocRow['loccode']){
//            echo '<option selected value="' . $LocRow['loccode'] .'">' . $LocRow['locationname'];
//        } else {
//            echo '<option value="' . $LocRow['loccode'] .'">' . $LocRow['locationname'];
//        }
//    }
//    echo '</select>';
} else {
    $LocResult = DB_query("SELECT loccode, locationname
                FROM locations
                WHERE loccode='" . $_POST['FromLocation'] . "'",
                $db);
    $LocRow = DB_fetch_array($LocResult);
    echo '<input type="hidden" name="FromLocation" value="' . $_POST['FromLocation'] . '">';
    echo $LocRow['locationname'];
}
echo '</td></tr> 
    
    </table>
    <table cellpadding=1>';
//echo'<tr><td>Calculate</td></tr>';
//echo'<tr><td><input type="text" name=FGqty></td>';
//echo'<td><input type="submit" name=FGcal value=calculate></td>';
//echo'</tr>';

if (!isset($_POST['IssueItem'])){ //no item selected to issue yet
    //set up options for selection of the item to be issued to the WO 
    if(isset($_POST['FGcal']))    {
        
    $Fgqty=$_POST['FGqty'];    
    }else {
        
        $Fgqty= $WORow['qtyreqd'];
    }  
    
//    echo '<tr><th colspan=7 height=30px><strong>' . _('Material Requirements For this Work Order based on FG item quantity - ') .$Fgqty. '</strong></th></tr>';
    echo '<tr><th>' . _('Item') . '</th>
        
        <th>' . _('Qty (recommended)') . '</th></tr>';

    $RequirmentsResult = DB_query("SELECT worequirements.stockid,
                        stockmaster.description,
                        stockmaster.decimalplaces,
                        autoissue,
                        qtypu
                    FROM worequirements INNER JOIN stockmaster
                    ON worequirements.stockid=stockmaster.stockid
                    WHERE wo=" . $_POST['WO'],
                    $db); 
                      
     $i=0; 
     $sql8="SELECT sum(reqty)
            FROM womaterialrequest
            WHERE wono=".$_POST['WO']; 
     $result8=DB_query($sql8,$db);
     $myrow8=DB_fetch_array($result8);                    
    while ($RequirementsRow = DB_fetch_array($RequirmentsResult)){
        
                    $QOHResult = DB_query("SELECT sum(quantity)
                        FROM locstock
                        WHERE stockid = '" . $RequirementsRow['stockid'] . "'",
                                        $db);
            $QOHRow = DB_fetch_row($QOHResult);
            $QOH = $QOHRow[0];
            
            
        if ($RequirementsRow['autoissue']==0){

                echo '<tr>';

            echo '<td>'. $RequirementsRow['description'] . '</td>';
        } else {

                echo '<tr>';

            echo '<td class="notavailable">' . _('Auto Issue') . '<td class="notavailable">' .$RequirementsRow['stockid'] . ' - ' . $RequirementsRow['description'] .'</td>';
        }
        $IssuedAlreadyResult = DB_query("SELECT SUM(-qty) FROM stockmoves
                            WHERE stockmoves.type=28
                            AND stockid='" . $RequirementsRow['stockid'] . "'
                            AND reference='" . $_POST['WO'] . "'",
                        $db);
        $IssuedAlreadyRow = DB_fetch_row($IssuedAlreadyResult);
        $Qtyleft=($WORow['qtyreqd']*$RequirementsRow['qtypu']) - ($IssuedAlreadyRow[0]);
        $WORequired= $WORow['qtyreqd']*$RequirementsRow['qtypu'];  
        if(isset($_POST['FGcal']))    {
            
        $Qtyleft=($_POST['FGqty']*$RequirementsRow['qtypu']) - ($IssuedAlreadyRow[0]);
        $WORequired= $_POST['FGqty']*$RequirementsRow['qtypu'];    
//echo"<br>dfgdfgdfgd".$_POST['FGqty'];
        }
             
//      echo '<td align="right">WO req' . number_format($WORow['qtyreqd']*$RequirementsRow['qtypu'],$RequirementsRow['decimalplaces']) . '</td>
//            <td align="right">Qty Iss' . number_format($IssuedAlreadyRow[0],$RequirementsRow['decimalplaces']) . '</td>
//            <td align="right">Qty On' . number_format($QOHRow[0],$myrow['decimalplaces']) . '</td>
//            <td align="right">Qty for ' . $myrow8[0]*$RequirementsRow['qtypu'] . '</td>'; 
            
            if($Qtyleft!=0) { 
                
        if($IssuedAlreadyRow[0]>=$WORequired)      {
            
         echo'<td bgcolor=green align =right>Fully issued<td></tr>'; 
         echo'<input type=hidden name= "IssueItem' . $i .'" value=0></tr>';  
        }else if ($QOH < $Qtyleft)  {
            if($QOH!=0)
echo'<td bgcolor=red align =right><input type="text" name= "IssueItem' . $i .'" value="' .$QOH. '"><td></tr>';             
            
        }else {
        echo'<td align=right><input type="text" name= "IssueItem' . $i .'" value="' .$Qtyleft. '"><td></tr>';             
            
        }
             echo'<td><input type="hidden" name= "StockID' . $i .'" value="' .$RequirementsRow['stockid']. '"><td></tr>';  
            }

            
            $i++;
    }
    
    echo"<input type=hidden name='WO' value='".$_POST['WO']."'>";   
    

    echo"<input type=hidden name='count' value=$i>";
    echo '</table>';
    echo"<input type=hidden name='FG' value='".$Fgqty."'>"; 
    echo"<input type=hidden name='Reqdate' value='".Date($_SESSION['DefaultDateFormat'])."'>";                    


    echo '</div>';

    if (isset($SearchResult)) {

        if (DB_num_rows($SearchResult)>1){

            echo '<table cellpadding=2 colspan=7 BORDER=1>';
            $TableHeader = '<tr><th>' . _('Code') . '</th>
                        <th>' . _('Description') . '</th>
                        <th>' . _('Units') . '</th></tr>';
            echo $TableHeader;
            $j = 1;
            $k=0; //row colour counter
            $ItemCodes = array();
            for ($i=1;$i<=$NumberOfOutputs;$i++){
                $ItemCodes[] =$_POST['OutputItem'.$i];
            }

            while ($myrow=DB_fetch_array($SearchResult)) {

                if (!in_array($myrow['stockid'],$ItemCodes)){
                    if (function_exists('imagecreatefrompng') ){
                        $ImageSource = '<IMG SRC="GetStockImage.php?SID&automake=1&textcolor=FFFFFF&bgcolor=CCCCCC&StockID=' . urlencode($myrow['stockid']). '&text=&width=64&height=64">';
                    } else {
                        if(file_exists($_SERVER['DOCUMENT_ROOT'] . $rootpath. '/' . $_SESSION['part_pics_dir'] . '/' . $myrow['stockid'] . '.jpg')) {
                            $ImageSource = '<IMG SRC="' .$_SERVER['DOCUMENT_ROOT'] . $rootpath . '/' . $_SESSION['part_pics_dir'] . '/' . $myrow['stockid'] . '.jpg">';
                        } else {
                            $ImageSource = _('No Image');
                        }
                    }


                        echo '<tr>';


                    $IssueLink = $_SERVER['PHP_SELF'] . '?' . SID . '&WO=' . $_POST['WO'] . '&StockID=' . $_POST['StockID'] . '&IssueItem=' . $myrow['stockid'] . '&FromLocation=' . $_POST['FromLocation'];
                    printf("<td><font size=1>%s</font></td>
                            <td><font size=1>%s</font></td>
                            <td><font size=1>%s</font></td>
                            <td>%s</td>
                            <td><font size=1><a href='%s'>"
                            . _('Add to Work Order') . '</a></font></td>
                            </tr>',
                            $myrow['stockid'],
                            $myrow['description'],
                            $myrow['units'],
                            $ImageSource,
                            $IssueLink);

                    $j++;
                    If ($j == 25){
                        $j=1;
                        echo $TableHeader;
                    } //end of page full new headings if
                } //end if not already on work order
            }//end of while loop
        } //end if more than 1 row to show
        echo '</table>';
    }#end if SearchResults to show
    $Wono=$_POST['WO'];
    //display($db,$Wono);  
} 
echo '</table>';

function display($db,$Wono)  {
    

  echo'<table width=100% valign=top><tr><td>';  
  
  echo'<table width=30%  valign=top>'; 
  
    echo '<tr><th colspan=5>' . _('Pending SRs For this Work Order') . '</th></tr>';
    echo '<tr><th colspan=2>' . _('SR No:') . '</th>
        <th>' . _('WOno') . '</th>
        <th>' . _('FG quantity') . '</th>
        <th>' . _('Status') . '</th></tr>';
        
                  
        $sql4="SELECT *
               FROM womaterialrequest
               WHERE wono=$Wono";
        $result4 = DB_query($sql4, $db);
        
        while ($myrow4 = DB_fetch_array($result4)){ 
            

                echo '<tr>';

        
    $sql5="SELECT status
           FROM status
           WHERE statusid=$myrow4[2]";
    $result5 = DB_query($sql5, $db);    
    $myrow5 = DB_fetch_array($result5) ;
    
            
        echo'<td colspan=2><input type=submit name=requestdetails value='.$myrow4[0].'></td>';
        echo'<td>'.$myrow4[1].'</td>';
        echo'<td align=right>';
        echo $myrow4[4];
        
        echo'</td>';
        echo'<td align=right>'.$myrow5[0].'</td></tr>'; 
            
            
        }   
        echo'<tr><td><input type=submit name=status value=update></td></tr>';    
           echo'</table>';  
           
 // end of table showing SRs for the selected WO         
    
    
      echo' </td><td>';
      
        if(isset($_POST['requestdetails'])) {
        $reqno=$_POST['requestdetails'];    
        displaydetails($db,$reqno);
        
        }
 
      
       
      echo '</td></tr></table>';          
}

function displaydetails($db,$reqno)  {  
    
       echo'<table width=30% align=left>';
             echo '<tr><th colspan=5>' . _('SRs Details') . '</th></tr>';
    echo '<tr><th colspan=2>' . _('SR No:') . '</th>
         <th>' . _('Item') . '</th>
        <th>' . _('Request Qty') . '</th></tr>';

 
            
    $sql3="SELECT stockid,
                  qtyrequest
           FROM womaterialrequestdetails
           WHERE reqno=$reqno";
    $result3 = DB_query($sql3, $db);  
    while ($myrow3 = DB_fetch_array($result3)){
        

                echo '<tr>';

        echo'<td>'.$reqno.'</td>';    
        echo'<td colspan=2>'.$myrow3[0].'</td>';
        echo'<td>'.$myrow3[1].'</td></tr>'; 
    } 
  

      echo '</table>';    
    
}
  
//echo '</form>';

//include('includes/footer.inc');
?>

