<?php

  $PageSecurity = 80;

 include('includes/session.inc');
              
  $season=  $_GET['month'];
 //echo "<br/>";
  $itemcod=  $_GET['item'];
 //echo "<br/>";  
  $yr=  $_GET['year'];
  
  
             $sqlr="SELECT seasondemands.demandquantity FROM seasondemands where itemcode='" . $itemcod . "' 
             and seasonid=$season and year=$yr";
             $resultr=DB_query($sqlr,$db);
             $myrowr=DB_fetch_array($resultr);  
             $dem=$myrowr['demandquantity'];
             $sql_mrp="Select sum(quantity) from mrpdemands where stockid='". $itemcod ."' and season_id=$season and year=$yr";
             $rst=DB_query($sql_mrp,$db);
             $mrow=DB_fetch_array($rst);
             $sumitem=$mrow[0];
             $rem= $dem-$sumitem;
         echo "<input type='text' readonly id='tqantity' name='tQuantity' value='".$rem."' size=25 maxlength=40>";     
  
  
   
   
/*

echo $sql_season = "SELECT stockmaster.stockid,stockmaster.description FROM stockmaster WHERE stockmaster.stockid in 
(SELECT itemcode from seasondemands where seasonid=$season and year=$yr)";
$result = DB_query($sql,$db);  ;
$result_season = DB_query($sql_season,$db);
while ($myrow = DB_fetch_array($result_season)) 
{if ($myrow['stockid']==$itemcod){echo '<option selected value="';} else {if ($f==0) {echo '<option>';} 
echo '<option value="';
//$f++;}
echo $myrow['stockid'] . '">'.$myrow['description'];  
echo '</option>';}

*/
/* 

$sql_qty='SELECT seasondemands.demandquantity FROM seasondemands WHERE seasondemands.year='.$_GET['t'].'AND 
seasondemands.seasonid='.$_GET['r'].' AND seasondemands.itemcode='.$_GET['s'].'';
$result_qty = DB_query($sql_qty,$db); 
if($myrowq = DB_fetch_array($result_qty)    

*/        
  
  /*
   $_POST['StockID ']=$_GET['item'];  
  
         
  
  
 if($_GET['item']!='')     {
     
  $_SESSION['StockID']=$_GET['item'];    
   
 }
  $leg_l1='Daily production plans'; 
 $leg_r1="Past season's details";
 $panel_id='panel_dailysche';
 
      echo"<div class=panels id=$panel_id>";
    echo '<table width=100%><tr><td width=50% valign="top" height=270px>';   
    
//--------------------------------------------------------------Start of Left Panel1  
  echo "<fieldset class='left_panel_1'>";     
  echo"<legend><h3>$leg_l1</h3>";
  echo"</legend>"; 
  echo'<table>'; 
  include('MasterSchedule-leftpanel1.php');
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
 // include('MasterSchedule-rightpanel1.php'); //----------M
  echo"</table>";
  echo "</fieldset>"; 
    

    
    echo"</td></tr></table>";
    echo"</div>";  
//--------------------------------------------------------------End of Panels

    echo"<div class=buttons>";
    echo "<table width=50%><tr>";
    echo "<td><input type='Submit' name='save' VALUE='" . _('Save') . "' onclick='if(log_in()==1)return false;'></td>";
    echo "<td><input type='Submit' name='skip' VALUE='" . _('Skip') . "' ></td>";
    echo "<td><input type='Submit' name='clear' VALUE='" . _('Clear') . "'></td>";

    echo "<td><input type='Button' class='button_details_show' name='details' VALUE='" . _('Details') . "'></td>";
    echo "</tr></table>";  
    echo"</div>";
    
//--------------------------------------------------------------End of Buttons   

    echo"<div id='selectiondetails'>"; 
    selectiondetails($rootpath,$db);
    echo"</div>";
    function selectiondetails($rootpath,$db)     {  

    echo '<table width=100% colspan=2 border=0 cellpadding=4>';
    echo "<tr>
        <th width=33%>" . _('Inquiries') . "</th>
        <th width=33%>". _('Transactions') . "</th>
        <th width=33%>" . _('Maintenance') . "</th>
    </tr>";
    echo '<tr><td VALIGN=TOP class="menu_group_items">';   
    //echo "<a>" . _('') . '</a>';
    
    
    echo'<br>'; 
    echo'<td VALIGN=TOP class="menu_group_items"></td>';
    echo '<td VALIGN=TOP class="menu_group_items">';
    echo "<a style='cursor:pointer;' id='2' onclick='selection(this.id)'>" . _('View Daily Schedules') . '</a><br>';
    echo'<br>';
    echo '</td></tr>';
    
    
//    echo'<tr><td></td><td></td><td>';
//    echo "<input type='Button' class='button_details_hide' VALUE='" . _('Hide Details') . "'>"; 
//    echo'</td></tr>';


    echo'</table>';
    
    }
//-----------------------------------------------------------------------End of Details    
                   
      echo "</div>";
      
  echo"<div class='Datagrid' id=Datagrid_masterschedule>";
include('Masterschedule-datagrid.php');  
  echo"</div>";       */    
?>
