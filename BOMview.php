<?php

$PageSecurity = 2;
$PricesSecurity = 9;

include('includes/session.inc');

$title = _('BOM view');
  $Select=$_GET['p'];  
 $_SESSION['StockID1']=$_GET['p']; 
    
if (isset($Select)) { //Parent Stock Item selected so display BOM or edit Component
    $SelectedParent = $Select;
    unset($Select);// = NULL;


    if(isset($_GET['ReSelect'])) {
        $SelectedParent = $_GET['ReSelect'];
    }

    //DisplayBOMItems($SelectedParent, $db);
    $sql = "SELECT stockmaster.description,
            stockmaster.mbflag
        FROM stockmaster
        WHERE stockmaster.stockid='" . $SelectedParent . "'";

    $ErrMsg = _('Could not retrieve the description of the parent part because');
    $DbgMsg = _('The SQL used to retrieve description of the parent part was');
    $result=DB_query($sql,$db,$ErrMsg,$DbgMsg);

    $myrow=DB_fetch_row($result);

    $ParentMBflag = $myrow[1];

    switch ($ParentMBflag){
        case 'A':
            $MBdesc = _('Assembly');
            break;
        case 'B':
            $MBdesc = _('Purchased');
            break;
        case 'M':
            $MBdesc = _('Manufactured');
            break;
        case 'K':
            $MBdesc = _('Kit Set');
            break;
        case 'G':
            $MBdesc = _('Phantom');
            break;
    }

    // Display Manufatured Parent Items
$sql = "SELECT bom.parent,
            stockmaster.description,
            stockmaster.mbflag
        FROM bom, stockmaster
        WHERE bom.component='".$SelectedParent."'
        AND stockmaster.stockid=bom.parent
        AND stockmaster.mbflag='M'";

    $ErrMsg = _('Could not retrieve the description of the parent part because');
    $DbgMsg = _('The SQL used to retrieve description of the parent part was');
    $result=DB_query($sql,$db,$ErrMsg,$DbgMsg);
    $ix = 0;
    $reqnl = 0;
    if( DB_num_rows($result) > 0 ) {
//     echo '<div class="centre">'._('Manufactured parent items').' : ';
     while ($myrow = DB_fetch_array($result)){
//            echo (($ix)?', ':'').'<a href="'.$_SERVER['PHP_SELF'] . '?' . SID . 'Select='.$myrow['parent'].'">'.
//            $myrow['description'].'&nbsp;('.$myrow['parent'].')</a>';
            $ix++;
     } //end while loop
//     echo '</div>';
     $reqnl = $ix;
    }
    // Display Assembly Parent Items
    $sql = "SELECT bom.parent, stockmaster.description, stockmaster.mbflag
        FROM bom, stockmaster
        WHERE bom.component='".$SelectedParent."'
        AND stockmaster.stockid=bom.parent
        AND stockmaster.mbflag='A'";

    $ErrMsg = _('Could not retrieve the description of the parent part because');
    $DbgMsg = _('The SQL used to retrieve description of the parent part was');
    $result=DB_query($sql,$db,$ErrMsg,$DbgMsg);
    if( DB_num_rows($result) > 0 ) {
        echo (($reqnl)?'<br>':'').'<div class="centre">'._('Assembly parent items').' : ';
         $ix = 0;
         while ($myrow = DB_fetch_array($result)){
            echo (($ix)?', ':'').'<a href="'.$_SERVER['PHP_SELF'] . '?' . SID . 'Select='.$myrow['parent'].'">'.
            $myrow['description'].'&nbsp;('.$myrow['parent'].')</a>';
            $ix++;
         } //end while loop
         echo '</div>';
    }
    // Display Kit Sets
    $sql = "SELECT bom.parent, stockmaster.description, stockmaster.mbflag
        FROM bom, stockmaster
        WHERE bom.component='".$SelectedParent."'
        AND stockmaster.stockid=bom.parent
        AND stockmaster.mbflag='K'";

    $ErrMsg = _('Could not retrieve the description of the parent part because');
    $DbgMsg = _('The SQL used to retrieve description of the parent part was');
    $result=DB_query($sql,$db,$ErrMsg,$DbgMsg);
    if( DB_num_rows($result) > 0 ) {
        echo (($reqnl)?'<br>':'').'<div class="centre">'._('Kit sets').' : ';
         $ix = 0;
         while ($myrow = DB_fetch_array($result)){
            echo (($ix)?', ':'').'<a href="'.$_SERVER['PHP_SELF'] . '?' . SID . 'Select='.$myrow['parent'].'">'.
            $myrow['description'].'&nbsp;('.$myrow['parent'].')</a>';
            $ix++;
         } //end while loop
         echo '</div>';
    }
    // Display Phantom/Ghosts
    $sql = "SELECT bom.parent, stockmaster.description, stockmaster.mbflag
        FROM bom, stockmaster
        WHERE bom.component='".$SelectedParent."'
        AND stockmaster.stockid=bom.parent
        AND stockmaster.mbflag='G'";

    $ErrMsg = _('Could not retrieve the description of the parent part because');
    $DbgMsg = _('The SQL used to retrieve description of the parent part was');
    $result=DB_query($sql,$db,$ErrMsg,$DbgMsg);
    if( DB_num_rows($result) > 0 ) {
        echo (($reqnl)?'<br>':'').'<div class="centre">'._('Phantom').' : ';
         $ix = 0;
         while ($myrow = DB_fetch_array($result)){
            echo (($ix)?', ':'').'<a href="'.$_SERVER['PHP_SELF'] . '?' . SID . 'Select='.$myrow['parent'].'">'.
            $myrow['description'].'&nbsp;('.$myrow['parent'].')</a>';
            $ix++;
         } //end while loop
         echo '</div>';
    }
    echo "<table align='center' width=100% border=0>";
    $sql2 = "SELECT *
FROM `stockmaster`
WHERE `stockid` ='".$SelectedParent."'";
        $result2=DB_query($sql2,$db); 
          while($row2=DB_fetch_array($result2))
    {
       echo "<tr>&nbsp</tr><tr>&nbsp</tr><tr><td align='center'><font size='5'><b> ".$row2['description']."</b></font></td></tr><tr><th>&nbsp</th></tr>";
    } 
    echo"</table>";

    echo "<table align='center' width=80% border=1>";
    // *** POPAD&T
    $BOMTree = array();
    //BOMTree is a 2 dimensional array with three elements for each item in the array - Level, Parent, Component
    //display children populates the BOM_Tree from the selected parent
    $i =0;  
    display_children($SelectedParent, 1, $BOMTree);
    
        $TableHeader =  '<tr>
            <th>' . _('Level') . '</th>
            <th>' . _('Component') . '</th>
            <th>' . _('Location') . '</th>
            <th>' . _('Quantity') . '</th>
            <th>' . _('Unit') . '</th>
            </tr>';
    echo $TableHeader;


    if(count($BOMTree) == 0) {
        echo '<tr class="OddTableRows"><td colspan="8">'._('No materials found.').'</td></tr>';
    } else {
        $UltimateParent = $SelectedParent;
        $k = 0;
        $RowCounter = 1;
        $slno=0;
        foreach($BOMTree as $BOMItem){
            $Level = $BOMItem['Level'];
            $Parent = $BOMItem['Parent'];
            $Component = $BOMItem['Component'];
            

                        if ($k==1){
                            
                echo '<tr class="EvenTableRows" id="'.$Component.'" onclick=showdetails(this.id,"'.$Parent.'")>';
                $k=0;
            }else {
                echo '<tr class="OddTableRows" id="'.$Component.'" onclick=showdetails(this.id,"'.$Parent.'")>';
                $k++;
            }

            DisplayBOMItems($UltimateParent, $Parent, $Component, $Level, $db);
            $slno++;
        }
    }
    // *** end POPAD&T
    echo '</tbody>';
    echo '<tfoot><tr>';
    echo '<td colspan=7>No: of records :'.$slno.'</td>';
    echo '</tr></tfoot>';
    echo '</table><br>';



    // end of BOM maintenance code - look at the parent selection form if not relevant
// ----------------------------------------------------------------------------------

}    
function display_children($parent, $level, $BOMTree) {

    global $db;
    global $i;

    // retrive all children of parent
    $c_result = DB_query("SELECT parent,
                    component
                FROM bom WHERE parent='" . $parent. "'"
                 ,$db);
    if (DB_num_rows($c_result) > 0) {
        //echo ("<UL>\n");


        while ($row = DB_fetch_array($c_result)) {
            //echo '<br>Parent: ' . $parent . ' Level: ' . $level . ' row[component]: ' . $row['component'] .'<br>';
            if ($parent != $row['component']) {
                // indent and display the title of this child
                $BOMTree[$i]['Level'] = $level;         // Level
                if ($level > 15) {
                    prnMsg(_('A maximum of 15 levels of bill of materials only can be displayed'),'error');
                    exit;
                }
                $BOMTree[$i]['Parent'] = $parent;        // Assemble
                $BOMTree[$i]['Component'] = $row['component'];    // Component
                // call this function again to display this
                // child's children
                $i++;
                display_children($row['component'], $level + 1, $BOMTree);
            }
        }
    }
} 

function DisplayBOMItems($UltimateParent, $Parent, $Component,$Level, $db) {

        global $ParentMBflag;
        // Modified by POPAD&T
        $sql = "SELECT bom.component,
                stockmaster.description,
                stockmaster.units,
                locations.locationname,
                
                workcentres.description,
                bom.quantity,
                bom.effectiveafter,
                bom.effectiveto,
                stockmaster.mbflag,
                bom.autoissue,
                stockmaster.controlled,
                locstock.quantity AS qoh,
                stockmaster.decimalplaces
            FROM bom,
                stockmaster,
                locations,
                workcentres,
                locstock
            WHERE bom.component='$Component'
            AND bom.parent = '$Parent'
            AND bom.component=stockmaster.stockid
            AND bom.loccode = locations.loccode
            AND locstock.loccode=bom.loccode
            AND bom.component = locstock.stockid
            AND bom.workcentreadded=workcentres.code
            AND stockmaster.stockid=bom.component";

        $ErrMsg = _('Could not retrieve the BOM components because');
        $DbgMsg = _('The SQL used to retrieve the components was');
        $result = DB_query($sql,$db,$ErrMsg,$DbgMsg);

        //echo $TableHeader;
        $RowCounter =0;

        while ($myrow=DB_fetch_row($result)) {


            $Level1 = str_repeat('-&nbsp;',$Level-1).$Level;
            if( $myrow[7]=='B' OR $myrow[7]=='K' OR $myrow[7]=='D') {
                $DrillText = '%s%s';
                $DrillLink = '<div class="centre"></div>';
                $DrillID='';
            } else {
                $DrillText = '<a href="%s&Select=%s">' . _('Drill Down');
                $DrillLink = $_SERVER['PHP_SELF'] . '?' . SID;
                $DrillID=$myrow[0];
            }
            if ($ParentMBflag!='M' AND $ParentMBflag!='G'){
                $AutoIssue = _('N/A');
            } elseif ($myrow[9]==0 AND $myrow[8]==1){//autoissue and not controlled
                $AutoIssue = _('Yes');
            } elseif ($myrow[9]==0) {
                $AutoIssue = _('No');
            } else {
                $AutoIssue = _('N/A');
            }

            if ($myrow[7]=='D' OR $myrow[7]=='K' OR $myrow[7]=='A' OR $myrow[7]=='G'){
                $QuantityOnHand = _('N/A');
            } else {
                $QuantityOnHand = number_format($myrow[10],$myrow[11]);
            }
            printf("<td align='center' >%s</td>

                <td align='center'  >%s</td>
                <td align='center' >%s</td>
               <td align='center' >%s</td>
                <td  align='center' >%s</td>


             

           

                 </tr>",
                $Level1,

                $myrow[1],
                $myrow[3],
                $myrow[5],
                 $myrow[2]
                 );

        } //END WHILE LIST LOOP
} //end of function DisplayBOMItems 

?>
