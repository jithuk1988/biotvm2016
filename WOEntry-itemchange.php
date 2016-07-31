<?php

 $PageSecurity = 80;

 include('includes/session.inc');
 
  $_POST['StockID']=$_GET['p'];  
 if($_GET['p']!='')     {
     
  $_SESSION['StockID']=$_GET['p'];     
 }
//--------------------display 
$panel_id='panel_woentry';
$leg_l1='Enter WO details';
$leg_r1="Item's details";
//..........................
      echo"<div class=panels id=$panel_id>";
    echo '<table width=100%><tr><td width=50% valign="top" height=270px>';   
    
//--------------------------------------------------------------Start of Left Panel1  
  echo "<fieldset class='left_panel_1'>";     
  echo"<legend><h3>$leg_l1</h3>";
  echo"</legend>"; 
  echo'<table>'; 
  
  
  
 // include('WOEntry-leftpanel1.php');
  
  ////////////
              $currentyear=2012;

//if(isset($_SESSION['StockID']))     {
//    
//$StockID=$_SESSION['StockID'];    
//}

$sql="SELECT m_season.season_id,
             m_season.season_sub_id
      FROM m_season
      WHERE m_season.is_current=1";
$result=DB_query($sql,$db);
$myrow=DB_fetch_array($result);
$SeasonnameID=$myrow[1];
$SeasonID=$myrow[0];


echo'<input type="hidden" name="SeasonID" value='.$SeasonID.'>';
echo "<tr><input type='hidden' id='' name='' value=$currentyear></tr>"; 
echo '<tr><td>' . _('Season') . "*</td><td><select name='Season'>";

       $sql1 = 'SELECT m_sub_season.seasonname,
                     season_sub_id
                FROM m_sub_season';
        $result1 = DB_query($sql1,$db);
        
        
        
  $Year=2012; 
        $f=0;
        while ($myrow1 = DB_fetch_array($result1)) {
            
            if ( $myrow1['season_sub_id']==$SeasonnameID) {
                echo "<option selected value='" . $myrow1['season_sub_id'] . "'>" . $myrow1['seasonname']; 
            } else  if ($f==0){
                         
        echo '<option>';

            }     echo "<option value='" . $myrow1['season_sub_id'] . "'>" . $myrow1['seasonname']; 
           
        $f++;    
        } //end while loop
        echo '</select>'; //--------------</select season>
echo"</td></tr>";
echo '<tr><td>' . _('Location') . "*</td><td><select name='Loccode'>";

        $sql = "SELECT locations.loccode,     
                       locations.locationname                                        
                FROM locations";
            $result = DB_query($sql,$db); 
$f=0;            
while ($myrow= DB_fetch_array($result)) {
            if ( $myrow['loccode']==$_SESSION['UserStockLocation']) {
                echo "<option selected value='" . $myrow['loccode'] . "'>" . $myrow['locationname']; 
            } else if ($f==0){
                         
        echo '<option>';
        }
 echo "<option value='" . $myrow['loccode'] . "'>" . $myrow['locationname']; 
          
        
        $f++;    
        } //end while loop
            
        echo '</select>';  //--------------</select Location>
echo"</td></tr>";

$StockID='';
echo '<tr><td>' . _('Item') . "*</td><td><select name='StockID' id='woentrystockid' onchange='datagridload(this.value,".$SeasonID.")'>";
//$_SESSION['UserStockID']
            $sql = "SELECT stockmaster.stockid,
                    stockmaster.description
                                        
                FROM stockmaster
                WHERE stockmaster.mbflag='M' AND stockmaster.categoryid !=13
                ORDER BY stockmaster.stockid";                                                            
            $result = DB_query($sql,$db); 
$f=0;            
while ($myrow= DB_fetch_array($result)) {
            if ( $myrow['stockid']==$_SESSION['StockID']) {
                echo "<option selected value='" . $myrow['stockid'] . "'>" . $myrow['description']; 
            } else if ($f==0){
                         
        echo '<option>';
        }
 echo "<option value='" . $myrow['stockid'] . "'>" . $myrow['description']; 
          
    
        $f++;    
        } //end while loop
            
        echo '</select>';  //--------------</select Item>
echo"</td></tr>";
if(!isset($_GET['p']))      {
    
$sql8="SELECT duedate,
              demandid,
              quantity     
       FROM mrpdemands
       WHERE stockid='".$_SESSION['StockID']."'     AND
             statusid=4
       ORDER BY duedate
       DESC LIMIT 1";
$result8=DB_query($sql8,$db);
$numrow=DB_num_rows($result8);
$myrow8=DB_fetch_array($result8);
if($numrow>0)      {
$_POST['Duedate']=ConvertSQLDate($myrow8['duedate']);
$_POST['demandno']=$myrow8['demandid'];
$_POST['DemandQuantity']=$myrow8['quantity'];
}
$sql9="SELECT serialno     
       FROM woserialnos
       WHERE stockid='".$_SESSION['StockID']."'
       ORDER BY serialno
       DESC LIMIT 1";
$result9=DB_query($sql9,$db);
$myrow9=DB_fetch_array($result9);
$_POST['Batch']=$myrow9['serialno'];
}

echo '<tr>';
echo"<input type='hidden' class=date alt=".$_SESSION['DefaultDateFormat']." name='Reqd' value='".$_POST['Duedate']."' id='reqd' size=25
            maxlength=12 value=".$_POST['Duedate'].">";
echo '<tr><td>' . _('Required Date') . "*</td>";
echo"<td><input type='hidden' class=date alt=".$_SESSION['DefaultDateFormat']." name='Duedate' value='".$_POST['Duedate']."' id='duedate' size=25
            maxlength=12>".$_POST['Duedate']."</td>"; 
echo '<tr><td>' . _('Demand Quantity') . "</td><td><input type='hidden' id='demandquantity' name='DemandQuantity' value='".$_POST['DemandQuantity']."' size=25 maxlength=40>
".$_POST['DemandQuantity']."</td></tr>";
echo '<tr><td>' . _('WO Quantity') . "*</td><td><input type='text' id='quantity' name='Quantity' value='".$_POST['Quantity']."' size=25 maxlength=40></td></tr>";
echo '<tr><td>' . _('Batch') . "*</td><td><input type='text' id='batch' name='Batch' value='".$_POST['Batch']."' size=25 maxlength=40></td></tr>"; 
if($wono!='')       {
    

echo '<tr><td>' . _('Status') . "*</td><td><select name='Wostatus'>";

        $sql = "SELECT status,
                       statusid                                        
                FROM status";
            $result = DB_query($sql,$db); 
            
while ($myrow= DB_fetch_array($result)) {
            if ( $myrow['statusid']==$_POST['Wostatus']) {
                echo "<option selected value='" . $myrow['statusid'] . "'>" . $myrow['status']; 
            } 
 echo "<option value='" . $myrow['statusid'] . "'>" . $myrow['status']; 
   
        } //end while loop
            
        echo '</select>';  //--------------</select Location>
echo"</td></tr>";   
    
}
echo '<input type="hidden" name=demandno value="'.$_POST['demandno'].'">';
  
  
  ////////////
  echo'</table>'; 
  echo "</fieldset>"; 
  echo'</td>';
    
//--------------------------------------------------------------End of Left Panel1

//--------------------------------------------------------------Start of right Panel1  
    echo'<td valign="top" height=270px>';
    
  echo "<fieldset class='right_panel_1'>";     
  echo"<legend><h3>$leg_r1</h3>";
  echo"</legend>";
  echo'<table>';
 // include('WOEntry-rightpanel1.php'); //----------M
  echo"</table>";
  echo "</fieldset>"; 
    

    
    echo"</td></tr></table>";
    echo"</div>";  
//--------------------------------------------------------------End of Panels

    echo"<div class=buttons>";
    echo "<table width=50%><tr>";
    echo "<td><input type='Submit' name='save' VALUE='" . _('Save') . "' onclick='if(log_in()==1)return false;'></td>";
    echo "<td><input type='Submit' name='skip' VALUE='" . _('Skip') . "' onclick='if(log_in()==1)return false;'></td>";
    echo "<td><input type='Submit' name='clear' VALUE='" . _('Clear') . "'></td>";
    echo "<td><input type='Submit' name='delete' VALUE='" . _('Delete') . "'></td>";
    echo "<td><input type='Button' class='button_details_show' name='details' VALUE='" . _('Details') . "'></td>";
    echo "</tr></table>";  
    echo"</div>";
    
//--------------------------------------------------------------End of Buttons   

    echo"<div class='selectiondetails'>"; 
    selectiondetails($rootpath,$db);
    echo"</div>";
    function selectiondetails($rootpath,$db)     {  

    echo '<table width=100% colspan=2 border=0 cellpadding=4>';
    echo "<tr>
        <th width=33%>" . _('Inquiries') . "</th>
        <th width=33%>". _('Transactions') . "</th>
        <th width=33%>" . _('Maintenance') . "</th>
    </tr>";
    echo '<tr><td VALIGN=TOP class="menu_group_items">';    /* Inquiry Options */
    echo "<a>" . _('') . '</a>';
    echo'<br>'; 
    echo'<td VALIGN=TOP class="menu_group_items">dfg</td>';
    echo '<td VALIGN=TOP class="menu_group_items">dfg';
    echo '</td></tr>';
//    echo'<tr><td></td><td></td><td>';
//    echo "<input type='Button' class='button_details_hide' VALUE='" . _('Hide Details') . "'>"; 
//    echo'</td></tr>';
    echo'</table>';
    
    }
//-----------------------------------------------------------------------End of Details   
      
      echo "</div>";
  echo"<div class='Datagrid' id=Datagrid_masterschedule>";
//include('WOEntry-datagrid.php');  
//////////////////////
                      if((isset($_GET['p'])) AND (($_GET['p']!="") OR ($_GET['p']!="undefined")))       {

  $_POST['StockID ']=$_GET['p'];    
  $_SESSION['StockID']=$_GET['p'];  
  $_SESSION['SeasonID']=$_GET['q'];   
 }
 
    $part=$_SESSION['StockID'];
    $SeasonID=$_SESSION['SeasonID'];  
    $where = "";
    if ($part) {
        $where = ' WHERE ((mrpdemands.statusid=4) OR (mrpdemands.statusid=5)) AND mrpdemands.stockid =' . "'"  .  $part . "' AND mrpdemands.season_id=$SeasonID";
    
    // If part is entered, it overrides demandtype

  

    $sql = 'SELECT mrpdemands.demandid,
                   mrpdemands.stockid,
                   mrpdemands.quantity,
                   mrpdemands.statusid,
                   mrpdemands.duedate,                 
                   stockmaster.description,
                   dev_mrpdemandstatus.status,
                   workorders.wo
        FROM mrpdemands
        LEFT JOIN stockmaster on mrpdemands.stockid = stockmaster.stockid
        LEFT JOIN dev_mrpdemandstatus on mrpdemands.statusid = dev_mrpdemandstatus.statusid
        LEFT JOIN workorders on mrpdemands.demandid = workorders.demandid' .
        $where    . ' ORDER BY mrpdemands.stockid, mrpdemands.duedate';      
        $result=DB_query($sql,$db);
 }
      echo '<table width=100% class=sortable>';
      $tableheader = "<thead><tr>
        <th colspan=5 text-align=left>Item selected is: ".$part."</th></tr><tr>
        <th>" . _('Sl no:') . "</th>
        <th>" . _('Demand no:') . "</th>
        <th>" . _('Date') . "</th>
        <th>" . _('WO status') . "</th>
        <th>" . _('WO qty') . "</th>
        </tr></thead>";
      echo $tableheader;
      $j = 1;
      $RowIndex = 0;

      $k=0;
      $slno=0;
      echo"<tbody>";
      while ($myrow=DB_fetch_array($result))   {
        
        if($myrow['wo']!='')        {
            
            $sql3="SELECT qtyreqd
                   FROM woitems
                   WHERE wo=".$myrow['wo'];
            $result3=DB_query($sql3,$db);
            $myrow3=DB_fetch_array($result3);
            $woqty=$myrow3[0];
            
            $sql4="SELECT wostatus.statusid,
                          status.status
                   FROM wostatus,status
                   WHERE wono=".$myrow['wo']."    AND
                         wostatus.statusid=status.statusid";
            $result4=DB_query($sql4,$db);
            $myrow4=DB_fetch_array($result4);
            $wostatus=$myrow4[1];
            
            
        }else   {
        
        $woqty="";
        $wostatus="no work orders created";
        }

        $duedate=ConvertSQLDate($myrow['duedate']);
        
        $datagridid=$myrow['demandid']."/".$myrow['wo'];
                        if ($k==1)
        {
            echo '<tr class="EvenTableRows"  id="'.$datagridid.'" onclick=showdetails(this.id)>';
            $k=0;
        } else 
        {
            echo '<tr class="OddTableRows"  id="'.$datagridid.'" onclick=showdetails(this.id)>';
            $k++;
        }  
        
       $slno++; 
        printf("<td>$slno</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            </tr>",
            $myrow['demandid'],
            $duedate,
            $wostatus,
            $woqty
            );

    $RowIndex = $RowIndex + 1;
//end of page full new headings if
    }
//end of while loop
    echo'<tfoot><tr>';
    echo'<td colspan=7>Number of records:'.$slno.'</td>';
    echo'</tr></tfoot>';
    echo'</tbody>';
    echo '</table>';

//////////////////////
  echo"</div>";
?>
