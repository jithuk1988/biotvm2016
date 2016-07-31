<?php
$PageSecurity = 11; 
include('includes/session.inc');   

$leg_l1="SR details";

    echo '<table width=100% ><tr><td width=50% valign="top" height=270px >';   
    
//--------------------------------------------------------------Start of Left Panel1  
    echo "<fieldset  class='left_panel_1'>";     
    echo"<legend><h3>$leg_l1</h3>";
    echo"</legend>"; 
    echo'<div style="height:230px;overflow:auto;">';
    echo'<table id="left_panel_1_WOMaterialissuefromstores" width=100%>'; 
// include('WOMaterialissuefromstores-leftpanel1.php');
   
   ////////
   
   $_POST['Reqno']=$_GET['p']; 

    $sql3="SELECT womaterialrequest.reqno,     
                  womaterialrequest.wono,
                  womaterialrequest.reqty,
                  dev_srstatus.srstatus,
                  woitems.stockid,
                  stockmaster.description
           FROM   womaterialrequest,woitems,stockmaster,dev_srstatus
           WHERE  womaterialrequest.reqno=".$_POST['Reqno']."    AND
                  womaterialrequest.wono=woitems.wo              AND
                  woitems.stockid=stockmaster.stockid            AND
                  womaterialrequest.statusid=dev_srstatus.srstatusid";

    $result3=DB_query($sql3,$db);
    $myrow3=DB_fetch_array($result3); 
    
echo '<tr><td width=50%>Item:&nbsp;</td><td><input type="hidden" name="Stockname" size=25 maxlength=40 id="item" value='.$myrow3['description'].'>
'.$myrow3['description'].'</td></tr>';      
echo '<tr><td width=50%>WO no:&nbsp;</td><td><input type="hidden" class="number" name="Wono" size=25 maxlength=40 id="wono" value='.$myrow3['wono'].'>
'.$myrow3['wono'].'</td></tr>';
echo '<tr><td width=50%>SR no:&nbsp;</td><td><input type="hidden" class="number" name="Srno" size=25 maxlength=40 id="srno" value='.$myrow3['reqno'].'>
'.$myrow3['reqno'].'</td></tr>';
echo '<tr><td width=50%>SR qty:&nbsp;</td><td><input type="hidden" class="number" name="Srqty" size=25 maxlength=40 id="srqty" value='.$myrow3['reqty'].'>
'.$myrow3['reqty'].'</td></tr>';
echo'<tr><td>SR Status</td><td>'.$myrow3['srstatus'].'</td></tr>';
echo "<tr><td></td><td><a style='cursor:pointer;' id='1' onclick='viewselection2(this.id)'>" . _('View Line items') . '</a><br><td></tr>';      
   
   
   ///////////
    echo'</table>'; 
    echo'</div>';
    echo "</fieldset>"; 
    echo'</td>';
    
//--------------------------------------------------------------End of Left Panel1

//--------------------------------------------------------------Start of right Panel1  
      echo'<td valign="top" height=270px id="WOMaterialissuefromstores-right_panel_1">';
      
//    echo "<fieldset class='right_panel_1'>";     
//    echo"<legend><h3>$leg_r1</h3>";
//    echo"</legend>";
//    echo '<table width=100% class=sortable>';
//    include('WOMaterialissuefromstores-rightpanel1.php');
//    echo"</table>";
//    echo "</fieldset>"; 
   
    echo"</td></tr></table>";
?>
