<?php



/* $Id leadsourcetypes.php  2011-07-08 16:39:20Z tcv $ */
$PageSecurity = 40;
include('includes/session.inc');
$title = _('Cost Centres') . ' / ' . _('Maintenance');
include('includes/header.inc');

if (isset($_POST['SelectedType'])){
    $SelectedType = strtoupper($_POST['SelectedType']);
} elseif (isset($_GET['SelectedType'])){
    $SelectedType = strtoupper($_GET['SelectedType']);
}

if (isset($Errors)) {
    unset($Errors);
}

$Errors = array();

echo '<p class="page_title_text"><img src="'.$rootpath.'/css/'.$theme.'/images/maintenance.png" title="' . _('CostCentre')
    . '" alt="" />' . _('Cost centre Setup') . '</p>';
echo '<div class="page_help_text">' . _('Add / edit / delete CostCentre') . '</div><br />';

if (isset($_POST['submit'])) {
//echo $_POST['costcentre'];
//echo $_POST['costcentreid'];
//echo $_POST['remarks'];
    //initialise no input errors assumed initially before we test
    $InputError = 0;

    /* actions to take once the user has clicked the submit button
    ie the page has called itself with some user input */

    //first off validate inputs sensible
    $i=1;
    if (strlen($_POST['costcentre']) >50) {
        $InputError = 1;
        echo prnMsg(_('The CostCentre must be 50 characters or less long'),'error');
        $Errors[$i] = 'CostCentre';
        $i++;
    }

    if (strlen($_POST['costcentre'])==0) {
        $InputError = 1;
        echo prnMsg(_('The CostCentre must contain at least one character'),'error');
        $Errors[$i] = 'CostCentre';
        $i++;
    }

    $checksql = "SELECT count(*)
             FROM bio_costcentres
             WHERE costcentre = '" . $_POST['costcentre'] . "'";
    $checkresult=DB_query($checksql, $db);
    $checkrow=DB_fetch_row($checkresult);
    if ($checkrow[0]>0) {
        $InputError = 1;
        echo prnMsg(_('You already have a CostCentre called').' '.$_POST['costcentre'],'error');
        $Errors[$i] = 'CostCentre';
        $i++;
    }

    if (isset($SelectedType) AND $InputError !=1) {

        $sql = "UPDATE bio_costcentres
            SET costcentre = '" . $_POST['costcentre'] .
             "',sourcetypeid='".$_POST['SourceTypeID']."' 
             WHERE costcentreid= '" . $SelectedType . "'";
        $msg = _('The CostCentre Name')  . ' ' . $SelectedType . ' ' .  _('has been updated');
    } elseif ( $InputError !=1 ) {

        // First check the type is not being duplicated

        $checkSql = "SELECT count(*)
                 FROM bio_costcentres
                 WHERE costcentreid = '" . $_POST['costcentreid'] . "'";

        $checkresult = DB_query($checkSql,$db);
        $checkrow = DB_fetch_row($checkresult);

        if ( $checkrow[0] > 0 ) {
            $InputError = 1;
            prnMsg( _('The Cost Centre Name ') . $_POST['costcentreid'] . _(' already exist.'),'error');
        } else {

            // Add new record on submit

            $sql = "INSERT INTO bio_costcentres
                        (costcentre,sourcetypeid)
                    VALUES (' " . $_POST['costcentre'] . " ','".$_POST['SourceTypeID']."')";


            $msg = _('Cost Centre Name') . ' ' . $_POST['costcentre'] .  ' ' . _('has been created');
            $checkSql = "SELECT count(costcentreid)
                 FROM bio_costcentres";
            $result = DB_query($checkSql, $db);
            $row = DB_fetch_row($result);

        }
    }

    if ( $InputError !=1) {
    //run the SQL from either of the above possibilites
        $result = DB_query($sql,$db);



        prnMsg($msg,'success');

        unset($SelectedType);
        unset($_POST['costcentreid']);
        unset($_POST['costcentre']);
       // unset($_POST['SourceProperty']);
    }

} elseif ( isset($_GET['delete']) ) {

    /* $sql = "SELECT COUNT(*) FROM suppliers WHERE supptype='" . $SelectedType . "'";

    $ErrMsg = _('The number of suppliers using this Type record could not be retrieved because');
    $result = DB_query($sql,$db,$ErrMsg);
    $myrow = DB_fetch_row($result);
    if ($myrow[0]>0) {
        prnMsg (_('Cannot delete this type because suppliers are currently set up to use this type') . '<br />' .
            _('There are') . ' ' . $myrow[0] . ' ' . _('suppliers with this type code'));
    } else { */

        $sql="DELETE FROM bio_costcentres WHERE costcentreid='" . $SelectedType . "'";
        $ErrMsg = _('The Cost Centre record could not be deleted because');
        $result = DB_query($sql,$db,$ErrMsg);
        prnMsg(_('Cost Centre') . $SelectedType  . ' ' . _('has been deleted') ,'success');

        unset ($SelectedType);
        unset($_GET['delete']);

    /*}*/
}



//end of ifs and buts!
//if (isset($SelectedType)) {

//    echo '<div class="centre"><p><a href="' . $_SERVER['PHP_SELF'] . '">' . _('Show All Types Defined') . '</a></div><p>';
//} 
echo "<a href='index.php'>Back to Home</a>"  ;
echo "<table border=0 style='width:30%';><tr><td>";  
  echo "<fieldset style='width:340px;height:200px'>";
   echo "<legend><h3>Cost Centre Master</h3></legend>";
if (! isset($_GET['delete'])) {
    

    echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '">';
    echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';
    echo '<br />';   


    echo '<table class="selection">'; //Main table
    // The user wish to EDIT an existing type

    if ( isset($SelectedType) AND $SelectedType!='' ) 
    {

        $sql = "SELECT costcentreid,costcentre,sourcetypeid
                FROM bio_costcentres
                        WHERE bio_costcentres.costcentreid='" . $SelectedType . "'";

        $result = DB_query($sql, $db);
        $myrow = DB_fetch_array($result);

                $_POST['costcentreid'] = $myrow['costcentreid'];
                $_POST['costcentre'] = $myrow['costcentre'];
                $_POST['sourcetypeid'] = $myrow['sourcetypeid'];



        echo '<input type="hidden" name="SelectedType" value="' . $SelectedType . '">';
        echo '<input type="hidden" name="costcentreid" value="' . $_POST['costcentreid'] . '">';

        // We dont allow the user to change an existing type code
    }

    if (!isset($_POST['costcentre'])) {
        $_POST['costcentre']='';
    }

  //  echo '<tr><td>' . _('Cost Centre ID') . ':</td>
       //             <!-- <td>' . $_POST['costcentreid'] . '</td> -->
       //   <td><input type="text" style="width:98%" name="costcentreid" id="costcentreid" value="' . $_POST['costcentreid'] . '"></td>
//        </tr>';
        echo '<tr><td>' . _('Cost Centre Name') . ':</td>';

    //echo $c;
    echo '<td>';
    echo "<input type='text' style='width:98%' name='costcentre' value='" . $_POST['costcentre'] . "'>";
    echo '</td></tr>';
//    echo '<tr><td>' . _('Remarks') . ':</td>
//            <td><input type="text" name="remarks" value="' . $_POST['remarks'] . '"></td>
//        </tr>';
        echo '<tr><td>' . _('Lead Source Type') . ':</td><td>';
        echo '<select name="SourceTypeID" style="width:192px">';
        $sql="SELECT * FROM `bio_leadsourcetypes`";
        $result=DB_query($sql,$db);
        echo $count=DB_fetch_row($sql,$db);
$f=0;
         while ($myrow = DB_fetch_array($result)) 
         {

     if ($myrow['id']==$_POST['sourcetypeid'])
    {
    echo '<option selected value="';
    } else
    {
        if ($f==0) 
        {
        echo '<option>';
        }
        echo '<option value="';
    }
    echo $myrow['id'] . '">'.$myrow['leadsourcetype'];
   echo '</option>';
   $f++;
    }

    echo '<tr><td colspan=2><p><div class="centre"><input type=submit name=submit value="' . _('Accept') . '">
    <input type=reset name=reset value="' . _('Clear') . '"></div>';

       echo '</td></tr>'; // close main table

echo '</table>';
echo '</form>';
} 
// end if user wish to delete

echo '</fieldset>'; 
echo "</td></tr>";
echo "<tr><td>";
    echo "<fieldset style='width:340px'>";
   echo "<legend><h3>Cost Centre Master Created</h3></legend>";

if (!isset($SelectedType)){

/* It could still be the second time the page has been run and a record has been selected for modification - SelectedType will
 *  exist because it was sent with the new call. If its the first time the page has been displayed with no parameters then
 * none of the above are true and the list of source property will be displayed with links to delete or edit each. These will call
 * the same page again and allow update/input or deletion of the records
 */
display($db);

}
else
{
  display($db);  
}

echo '</fieldset>';
echo "</td></tr></table>";
function display($db)
{
        $sql = "SELECT bio_costcentres.costcentreid, bio_costcentres.costcentre, bio_costcentres.sourcetypeid,bio_leadsourcetypes.leadsourcetype
            FROM bio_costcentres,bio_leadsourcetypes WHERE bio_leadsourcetypes.id=bio_costcentres.sourcetypeid";
    $result = DB_query($sql,$db);
echo "<div style='overflow:scroll;height:150px'>";
    echo '<table class="selection">';
    echo '<thead><tr>
                <th>' . _('Sl No') . '</th>
        <th>' . _('Lead Source Type') . '</th>

        <th>' . _('Cost Centre Name') . '</th>
        </thead></tr>';
echo "<tbody overflow:scroll;>";
$k=0; //row colour counter
  $sl=0;
while ($myrow = DB_fetch_row($result)) {
    if ($k==1){
        echo '<tr class="EvenTableRows">';
        $k=0;
    } else {
        echo '<tr class="OddTableRows">';
        $k=1;
    }     $sl++;
    printf('<td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td><a href="%sSelectedType=%s">' . _('Edit') . '</td>
            <td><a href="%sSelectedType=%s&delete=yes" onclick=\'return confirm("' .
                _('Are you sure you wish to delete this Lead Source Team?') . '");\'>' . _('Delete') . '</td>
        </tr>',
        $sl,
        $myrow[3],
        $myrow[1],
        $_SERVER['PHP_SELF'] . '?',
        $myrow[0],
        $_SERVER['PHP_SELF'] . '?',
        $myrow[0]);
    }
    echo "</tbody>";
    //END WHILE LIST LOOP
    echo '</table>';
    echo "</div>";
}
include('includes/footer.inc');

?>
<script language="javascript">
  document.getElementById('costcentreid').focus();
  
    $(document).ready(function() {
    $("#notice").fadeOut(3000);
});
  </script>