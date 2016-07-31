<?php

/* Grid view will change when changing the month in the combo box and corresponding to the year in the text box */

$PageSecurity = 40;
include('includes/session.inc'); 
 echo '<table width=100%>
    <tr>
        <th width="10%">' . _('Sl.No') . '</th>
        <th width="33%">' . _('Item') . '</th>
        <th width="33%">' . _('Target') . '</th>
        
        
    </tr>';
    
 
                 
                  /**************************** grid view ******************************/
                  
                   $sql_list='SELECT seasondemands.seasondemandid,
                      seasondemands.itemcode,
                      seasondemands.demandquantity,
                      seasondemands.producedquantity
                 FROM seasondemands
                 WHERE seasondemands.year='.$_GET['year'].'
                 AND seasondemands.seasonid='.$_GET['month'].' order by seasondemandid desc';
                  
                  /**********************************************************/
                 
                 
                 
   $result_list = DB_query($sql_list,$db);
    
    $k=0; //row colour counter 
    $slno=0;
    
    while ($myrow = DB_fetch_array($result_list)) {
        if ($k==1){
            echo '<tr class="EvenTableRows" id="'.$myrow['seasondemandid'].'" onclick=showSeasonDemand(this.id)>';
            $k=0;
        } else {
            echo '<tr class="OddTableRows" id="'.$myrow['seasondemandid'].'" onclick=showSeasonDemand(this.id)>';
            $k=1;
        }
        
        $sql2="SELECT description FROM stockmaster WHERE stockid='".$myrow['itemcode']."'";
        $ErrMsg = _('The SQL to find the parts selected failed with the message');
        $result2 = DB_query($sql2,$db,$ErrMsg);
        $myrow2=DB_fetch_array($result2);
    
        $slno++;
    
        printf("<td>%s</td>
            <td>%s</td>
            <td>%s</td>
            
            </tr>",
            $slno,
            $myrow2['description'],
            $myrow['demandquantity']);
            //,$myrow['']);
    }
echo'<tfoot><tr>';
echo'<td colspan=10>Number of records:'.$slno.'</td>';
echo'</tr></tfoot>';    
echo'</table>'; 
?>
