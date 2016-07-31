<?php
$PageSecurity = 80;

include('includes/session.inc');

$title = _('Bills Of Materials');//

include('includes/header.inc');

$pagetype=3;
include('includes/sidemenu1.php');
include('includes/SQL_CommonFunctions.inc');
include('includes/formload.inc');
?>
<script type="text/javascript">


function Bomview(str1)
{

var str1=document.getElementById('fgitem').value;      
myRef = window.open('BOMview.php?p='+ str1,'estr1');   

}
</script>
<?php
function display_children($parent, $level, &$BOMTree) {

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
                $DrillLink = '<div class="centre">----</div>';
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
            printf("<td>%s</td>

                <td>%s</td>
                <td>%s</td>
                <td>%s</td>
                <td class=number>%s</td>


                <td class=number>%s</td>

                <td>".$DrillText."</a></td>

                 </tr>",
                $Level1,

                $myrow[1],
                $myrow[2],
                $myrow[3],
                $myrow[4],


                $QuantityOnHand,

                $DrillLink,
                $DrillID
                );

        } //END WHILE LIST LOOP

} //end of function DisplayBOMItems    
function CheckForRecursiveBOM ($UltimateParent, $ComponentToCheck, $db) {

/* returns true ie 1 if the BOM contains the parent part as a component
ie the BOM is recursive otherwise false ie 0 */

    $sql = "SELECT component FROM bom WHERE parent='$ComponentToCheck'";
    $ErrMsg = _('An error occurred in retrieving the components of the BOM during the check for recursion');
    $DbgMsg = _('The SQL that was used to retrieve the components of the BOM and that failed in the process was');
    $result = DB_query($sql,$db,$ErrMsg,$DbgMsg);

    if (DB_num_rows($result)!=0) {
        while ($myrow=DB_fetch_row($result)){
            if ($myrow[0]==$UltimateParent){
                return 1;
            }
            if (CheckForRecursiveBOM($UltimateParent, $myrow[0],$db)){
                return 1;
            }
        } //(while loop)
    } //end if $result is true

    return 0;

} //end of function CheckForRecursiveBOM 
?>
<script type="text/javascript"> 
$(document).ready(function(){  
    
  $("#error").fadeOut(3000);
    $("#warn").fadeOut(3000);
      $("#success").fadeOut(3000);
        $("#info").fadeOut(3000);
         $(".db_message").fadeOut(3200);  
        
        
  $("#selectiondetails").hide(); 
        
$('.button_details_show').click(function() {
  $('#selectiondetails').slideToggle('slow', function() {

  });
}); 

         
});

 function showdetails(str1,str2,str3,str4,str5,str6,str7,str8)
{
//alert(str2);
//alert(str1); 
if (str1=="")
  {
  document.getElementById("panel_boms").innerHTML="";
  return;
  }
//show_progressbar('loader');
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
      $("#loader").hide(); 
    document.getElementById("panel_boms").innerHTML=xmlhttp.responseText;
    document.forms[0].taskname.focus(); 
    }
  }

xmlhttp.open("GET","BillsOfMaterials-panels.php?p=" + str1 + "&q=" + str2 + "&r=" + str3 + "&s=" + str4 + "&t=" + str5 + "&u=" + str6 + "&v=" + str7 + "&w=" + str8,true);     
xmlhttp.send(); 
}  
   function datagridload(str1,str2,str3,str4,str5,str6,str7,str8)
{

if (str1=="")
  {
  document.getElementById("Datagrid").innerHTML="";
  return;
  }
//  show_progressbar('Datagrid');
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {

    document.getElementById("Datagrid").innerHTML=xmlhttp.responseText;
    document.forms[0].taskname.focus(); 
    }
  }

xmlhttp.open("GET","BillsOfMaterials-datagrid-load1.php?p=" + str1 + "&q=" + str2 + "&r=" + str3 + "&s=" + str4 + "&t=" + str5 + "&u=" + str6 + "&v=" + str7 + "&w=" + str8,true);     
xmlhttp.send(); 
}  
</script>
<?php

//--------------------display 
$panel_id='panel_boms';
$leg_l1='Add BOMs';
$leg_r1='';
//..........................


//echo $fieldid=get_fieldid('supplierid','suppliers',$db);

if (isset($_GET['SupplierID'])){
    $SupplierID = strtoupper($_GET['SupplierID']);
} elseif (isset($_POST['SupplierID'])){
   $SupplierID = strtoupper($_POST['SupplierID']);
} else {
    unset($SupplierID);
}

// This is aleady linked from this page
//echo "<a href='" . $rootpath . '/SelectSupplier.php?' . SID . "'>" . _('Back to Suppliers') . '</a><br>';
echo '<p class="page_title_text">' . ' ' . _('Bills Of Materials') . '';

$InputError = 0;

if (isset($Errors)) {
    unset($Errors);
}

$Errors=Array();
if(isset($_POST['Editbom']))     {        
        $SelectedParent=$_POST['Fgitem'];
        $SelectedComponent=$_POST['Component'];
        
}else       {
    
    
     $SelectedParent=$_POST['Fgitem']; 
     unset($SelectedComponent);
}
if(isset($_POST['clear']))      {
    
    unset($_SESSION['StockID1']);
    unset($SelectedParent);
    unset($SelectedComponent);
}
If (isset($_POST['save'])) {


        //editing a component need to do some validation of inputs

        $i = 1;


        if (!is_numeric($_POST['Quantity'])) {
            $InputError = 1;
            prnMsg(_('The quantity entered must be numeric'),'error');
            $Errors[$i] = 'Quantity';
            $i++;
        }
        if ($_POST['Quantity']==0) {
            $InputError = 1;
            prnMsg(_('The quantity entered cannot be zero'),'error');
            $Errors[$i] = 'Quantity';
            $i++;
        }



if (isset($SelectedParent) AND isset($SelectedComponent) AND $InputError != 1) {
        

          $sql = "UPDATE bom 
                 SET    workcentreadded='" . $_POST['WorkCentreAdded'] . "',
                        loccode='" . $_POST['LocCode'] . "',
                        quantity= " . $_POST['Quantity'] . ",
                        autoissue=" . $_POST['AutoIssue'] . "
                 WHERE  bom.parent='" . $SelectedParent . "'
                 AND    bom.component='" . $SelectedComponent . "'";

//            $ErrMsg =  _('Could not update this BOM component because');
//            $DbgMsg =  _('The SQL used to update the component was');

            $result = DB_query($sql,$db);

//            $msg = _('Details for') . ' - ' . $SelectedComponent . ' ' . _('have been updated') . '.';
            UpdateCost($db,$SelectedComponent);

        } elseIf ($InputError !=1 AND ! isset($SelectedComponent) AND isset($SelectedParent)) {

        /*Selected component is null cos no item selected on first time round so must be                adding a record must be Submitting new entries in the new component form */

        //need to check not recursive BOM component of itself!

            If (!CheckForRecursiveBOM ($SelectedParent, $_POST['Component'], $db)) {

                /*Now check to see that the component is not already on the BOM */
                $sql = "SELECT component
                        FROM bom
                    WHERE parent='$SelectedParent'
                    AND component='" . $_POST['Component'] . "'
                    AND workcentreadded='" . $_POST['WorkCentreAdded'] . "'
                    AND loccode='" . $_POST['LocCode'] . "'" ;

                $ErrMsg =  _('An error occurred in checking the component is not already on the BOM');
                $DbgMsg =  _('The SQL that was used to check the component was not already on the BOM and that failed in the process was');

                $result = DB_query($sql,$db,$ErrMsg,$DbgMsg);

                if (DB_num_rows($result)==0) {

                    $sql = "INSERT INTO bom (parent,
                                component,
                                workcentreadded,
                                loccode,
                                quantity,
                                autoissue)
                            VALUES ('$SelectedParent',
                                '" . $_POST['Component'] . "',
                                '" . $_POST['WorkCentreAdded'] . "',
                                '" . $_POST['LocCode'] . "',
                                " . $_POST['Quantity'] . ",
                                '1')";


                    $ErrMsg = _('Could not insert the BOM component because');
                    $DbgMsg = _('The SQL used to insert the component was');

                    $result = DB_query($sql,$db,$ErrMsg,$DbgMsg);

                    UpdateCost($db, $_POST['Component']);
                    $msg = _('A new component part') . ' ' . $_POST['Component'] . ' ' . _('has been added to the bill of material for part') . ' - ' . $SelectedParent . '.';


                } else {
                    
                    echo '<script>alert("Item already exists as BOM .Select from the Data grid to make quantity updates");</script>';

                /*The component must already be on the BOM */

//                    prnMsg( _('The component') . ' ' . $_POST['Component'] . ' ' . _('is already recorded as a component of') . ' ' . $SelectedParent . '.' . '<br>' . _('Whilst the quantity of the component required can be modified it is inappropriate for a component to appear more than once in a bill of material'),'error');
//                    $Errors[$i]='ComponentCode';
                }


            } //end of if its not a recursive BOM

        } //end of if no input errors

        if ($msg != '') {prnMsg($msg,'success');}

    } //--------------------------------------save
    else if(isset($_POST['delete']))       {
        
        if(isset($_POST['delete']) AND isset($SelectedComponent) AND isset($SelectedParent)) {

    //the link to delete a selected record was clicked instead of the Submit button
        
        $sql="DELETE FROM bom WHERE parent='$SelectedParent' AND component='$SelectedComponent'";

        $ErrMsg = _('Could not delete this BOM components because');
        $DbgMsg = _('The SQL used to delete the BOM was');
        $result = DB_query($sql,$db,$ErrMsg,$DbgMsg);

        $ComponentSQL = 'SELECT component from bom where parent="' . $SelectedParent .'"';
        $ComponentResult = DB_query($ComponentSQL,$db);
        $ComponentArray = DB_fetch_row($ComponentResult);
        if($ComponentArray[0]!='')      {
        UpdateCost($db, $ComponentArray[0]);
        }

        prnMsg(_('The component part') . ' - ' . $SelectedComponent . ' - ' . _('has been deleted from this BOM'),'success');
        // Now reselect

    }
    }  //----------------------delete

  echo "<div id=fullbody>";
    echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "?" . SID . "'>";
echo '<div id="loader"></div>';
//    echo "<input type='hidden' name='New' VALUE='Yes'>";
 //-------------------------------------------------------------Start of Panels
    echo"<div class=panels id=$panel_id>";
    echo '<table width=100%><tr><td width=50% valign="top" height=270px>';   
    
//--------------------------------------------------------------Start of Left Panel1  
  echo "<fieldset class='left_panel_1'>";     
  echo"<legend><h3>$leg_l1</h3>";
  echo"</legend>"; 
  echo'<table>'; 
  include('BillsOfMaterials-leftpanel1.php');
  echo'</table>'; 
  echo "</fieldset>"; 
  echo'</td>';
    
//--------------------------------------------------------------End of Left Panel1

//--------------------------------------------------------------Start of right Panel1  
    echo'<td valign="top" height=270px>';
    
//  echo "<fieldset class='right_panel_1'>";     
//  echo"<legend><h3>$leg_r1</h3>";
//  echo"</legend>";
//  echo'<table>';
//  include('BillsOfMaterials-rightpanel1.php');
//  echo"</table>";
//  echo "</fieldset>"; 
    

    
    echo"</td></tr></table>";
    echo"</div>";  
//--------------------------------------------------------------End of Panels

    echo"<div class=buttons>";
    echo "<table width=50%><tr>";
    echo "<td><input type='Submit' name='save' VALUE='" . _('Save') . "' onclick='if(log_in()==1)return false;'></td>";
    echo "<td><input type='Submit' name='clear' VALUE='" . _('Clear') . "'></td>";
    echo "<td><input type='Submit' name='delete' VALUE='" . _('Delete') . "'></td>";
    echo "<td><input type='Button' class='button_details_show' name='details' VALUE='" . _('Details') . "'></td>";
    echo "</tr></table>";  
    echo"</div>";
    
//--------------------------------------------------------------End of Buttons       
    echo '</form>'; 
    
    echo"<div id='selectiondetails'>"; 
    selectiondetails();
    echo"</div>";

//-----------------------------------------------------------------------End of Details    
      
      echo "</div>";

  echo"<div id='Datagrid' class='Datagrid'>";
  datagrid($Select,$db);
  echo"</div>";
//-------------------------------------------------------------End of Data Grid  
  
  function selectiondetails($db)     {  

    echo '<table width=100% colspan=2 border=0 cellpadding=4>';
    echo "<tr>
        <th width=33%>" . _('Inquiries') . "</th>
        <th width=33%>". _('Transactions') . "</th>
        <th width=33%>" . _('Maintenance') . "</th>
    </tr>";
    echo '<tr><td VALIGN=TOP class="menu_group_items">';    /* Inquiry Options */
    echo "<a style='cursor:pointer;' onclick=Bomview()>" . _('BOM_view') . '</a><br>';
    //echo '<br>';

    echo '</td><td VALIGN=TOP class="menu_group_items">'; /* Supplier Maintenance */
//        echo '<a href="' . $rootpath . '/Suppliers.php?">' . _('Add a New Supplier') . '</a><br>';
    

    
    echo '</td><td VALIGN=TOP class="menu_group_items">'; /* Supplier Maintenance */
//        echo '<a href="' . $rootpath . '/Suppliers.php?">' . _('Add a New Supplier') . '</a><br>';
    
    echo '</td></tr>';
//    echo'<tr><td></td><td></td><td>';
//    echo "<input type='Button' class='button_details_hide' VALUE='" . _('Hide Details') . "'>"; 
//    echo'</td></tr>';
    echo'</table>';
    
    }
  function datagrid($Select,$db)    {

   if(isset($_SESSION['StockID1']))     {
 $Select=$_SESSION['StockID1'];    
}else       {
    
    $Select='';
}    
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
        WHERE stockmaster.stockid='" . $SelectedParent . "' ORDER BY stockmaster.description ASC";

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
        AND stockmaster.mbflag='M' ORDER BY stockmaster.description ASC";

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
        AND stockmaster.mbflag='A' ORDER BY stockmaster.description ASC";

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
    echo "<table width=100%>";

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
            <th>' . _('Work Centre') . '</th>
            <th>' . _('Quantity') . '</th>
            <th>' . _('Qty On Hand') . '</th>
            </tr><tbody>';
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
 
  }
 
echo "<br>";
  include('includes/smenufooter.inc'); 
  






  
?>
<script language="javascript">
 document.getElementById('custid').focus(); 
  $(document).ready(function() {
    $("#notice").fadeOut(3000);

 $('#sourcetype').change(function() {
  $('#dinhide').hide();
}); 
   }); 
//    $('#leads').click(function() { 

//    f=validateemail('email','Please Enter valid email');  if(f==1) { return false; }   
//});
function caps1()
{
//  alert("sss");
UCWords('custid','Name should be begin with capital letter');
}
 function log_in()
{   alert(mail);
var f=0;       


f=common_error('BOMquantity','Please Enter The BOM Quantity');  if(f==1) { return f;} 
             

//if(f==0){f=common_error('contactname','Please Enter contact name');  if(f==1) { return f; }}                  

} 
</script>