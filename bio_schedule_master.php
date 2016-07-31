<?php
  

/* $Id schemes.php  2011-07-08 16:39:20Z tcv $ */
$PageSecurity = 80;
include('includes/session.inc');
$title = _('Schedule Master');
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

echo '<p class="page_title_text"><img src="'.$rootpath.'/css/'.$theme.'/images/maintenance.png" title="' . _('Schedules')
    . '" alt="" />' . _('Schedule Setup') . '</p>';
//echo '<div class="page_help_text">' . _('Add/edit/delete Schemes') . '</div><br />';

if (isset($_POST['submit'])) {

    //initialise no input errors assumed initially before we test
    $InputError = 0;

    /* actions to take once the user has clicked the submit button
    ie the page has called itself with some user input */

    //first off validate inputs sensible
    $i=1;
    if (strlen($_POST['Schedule']) >100) {
        $InputError = 1;
        echo prnMsg(_('The Schedule type name description must be 100 characters or less long'),'error');
        $Errors[$i] = 'Schedule';
        $i++;
    }

    if (strlen($_POST['Schedule'])==0) {
        $InputError = 1;
        echo prnMsg(_('The schedule name description must contain at least one character'),'error');
        $Errors[$i] = 'schedule';
        $i++;
    }
   if (!isset($SelectedType))     {
    $checksql = "SELECT count(*)
             FROM bio_schedule_master
             WHERE schedule = '" . $_POST['Schedule'] . "'";
    $checkresult=DB_query($checksql, $db);
    $checkrow=DB_fetch_row($checkresult);
    if ($checkrow[0]>0) {
        $InputError = 1;
        echo prnMsg(_('You already have a schedule called').' '.$_POST['Schedule'],'error');
        $Errors[$i] = 'OutputName';
        $i++;
    }
   }

    if (isset($SelectedType) AND $InputError !=1) {

        $sql = "UPDATE bio_schedule_master
            SET schedule = '" . $_POST['Schedule'] .
            "',schedule_days='".$_POST['Days']."'
            WHERE master_schedule_id = '" . $SelectedType . "'";

        $msg = _('The schedule') . ' ' . $SelectedType . ' ' .  _('has been updated');
    } elseif ( $InputError !=1 ) {

        // First check the type is not being duplicated

        $checkSql = "SELECT count(*)
                 FROM bio_schedule_master
                 WHERE master_schedule_id = '" . $_POST['Schedule ID'] . "'";

        $checkresult = DB_query($checkSql,$db);
        $checkrow = DB_fetch_row($checkresult);

        if ( $checkrow[0] > 0 ) {
            $InputError = 1;
            prnMsg( _('The schedule ') . $_POST['Schedule ID'] . _(' already exist.'),'error');
        } else {

            // Add new record on submit

            $sql = "INSERT INTO bio_schedule_master
                        (schedule,schedule_days)
                    VALUES ('" . $_POST['Schedule'] . "','" . $_POST['Days'] . "')";


            $msg = _('Schedule') . ' ' . $_POST['Schedule'] .  ' ' . _('has been created');
            $checkSql = "SELECT count(master_schedule_id)
                 FROM bio_schedule_master";
            $result = DB_query($checkSql, $db);
            $row = DB_fetch_row($result);

        }
    }

    if ( $InputError !=1) {
    //run the SQL from either of the above possibilites
        $result = DB_query($sql,$db);



        prnMsg($msg,'success');

        unset($SelectedType);
        unset($_POST['Schedule ID']);
        unset($_POST['Schedule']);
        unset($_POST['Days']);
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

        $sql="DELETE FROM bio_schedule_master WHERE master_schedule_id='" . $SelectedType . "'";
        $ErrMsg = _('The Type record could not be deleted because');
        $result = DB_query($sql,$db,$ErrMsg);
        prnMsg(_('Schedule') . $SelectedType  . ' ' . _('has been deleted') ,'success');

        unset ($SelectedType);
        unset($_GET['delete']);

    /*}*/
}


//end of ifs and buts!


if (isset($SelectedType)) {

    echo '<div class="centre"><p><a href="' . $_SERVER['PHP_SELF'] . '">' . _('Show All Types Defined') . '</a></div><p>';
}

echo "<a href='index.php'>Back to Home</a>"  ;
echo "<table border=0 style='width:30%';><tr><td>";  
  echo "<fieldset style='width:340px;height:200px'>";
   echo "<legend><h3>Schedule Master</h3></legend>";
if (! isset($_GET['delete'])) {

    echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '">';
    echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';
    echo '<br /><table class="selection">'; //Main table
    // The user wish to EDIT an existing type
    if ( isset($SelectedType) AND $SelectedType!='' ) {

        $sql = "SELECT master_schedule_id,
                   schedule,schedule_days
                FROM bio_schedule_master
                WHERE master_schedule_id='" . $SelectedType . "'";

        $result = DB_query($sql, $db);
        $myrow = DB_fetch_array($result);

        $_POST['Schedule ID'] = $myrow['master_schedule_id'];
        $_POST['Schedule']  = $myrow['schedule'];
        $_POST['Days']  = $myrow['schedule_days'];

        echo '<input type="hidden" name="SelectedType" value="' . $SelectedType . '">';
        echo '<input type="hidden" name="Schedule ID" value="' . $_POST['Schedule ID'] . '">';

        // We dont allow the user to change an existing type code

        echo '<tr><td>' ._('Schedule ID') . ': </td>
                <td>' . $_POST['Schedule ID'] . '</td>
            </tr>';
    }

    if (!isset($_POST['Schedule'])) {
        $_POST['Schedule']='';
    }
    if (!isset($_POST['Days'])) {
        $_POST['Days']='';
    }
    echo '<tr><td>' . _('Schedule Name') . ':</td>
            <td><input type="text" name="Schedule" id="Schedule" value="' . $_POST['Schedule'] . '"></td>
        </tr>';
        
    echo '<tr><td>' . _('Number Of Days') . ':</td>
            <td><input type="text" name="Days" id="Days" value="' . $_POST['Days'] . '"></td>
        </tr>';

    echo '<tr><td colspan=2><p><div class="centre"><input type=submit name=submit value="' . _('Accept') . ' " onclick="if(log_in()==1)return false;"></div>';

       echo '</td></tr></table>'; // close main table

    echo '</form>';

} // end if user wish to delete

echo '</fieldset>'; 
echo "</td></tr>";
echo "<tr><td>";
    echo "<fieldset style='width:340px'>";
   echo "<legend><h3>Schedule Created</h3></legend>";

   if (!isset($SelectedType)){

/* It could still be the second time the page has been run and a record has been selected for modification - SelectedType will
 *  exist because it was sent with the new call. If its the first time the page has been displayed with no parameters then
 * none of the above are true and the list of sales types will be displayed with links to delete or edit each. These will call
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
        $sql = "SELECT master_schedule_id,schedule,schedule_days FROM bio_schedule_master";
    $result = DB_query($sql,$db);
    echo "<div style='overflow:scroll;height:150px'>";
    echo '<table class="selection">';
    echo '<tr>
        <th>' . _('Slno') . '</th>
        <th>' . _('Schedule Name') . '</th>
        <th>' . _('Days') . '</th>
        </tr>';

$k=0;  //row colour counter      
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
            <td><a href="%sSelectedType=%s">' . _('Edit') . '</td>
            <td><a href="%sSelectedType=%s&delete=yes" onclick=\'return confirm("' .
                _('Are you sure you wish to delete this Schedule?') . '");\'>' . _('Delete') . '</td>
        </tr>',
        $myrow[0],
        $myrow[1],
        $myrow[2],
        $_SERVER['PHP_SELF'] . '?', 
        $myrow[0],
        $_SERVER['PHP_SELF'] . '?', 
        $myrow[0]);
    }
    //END WHILE LIST LOOP
    echo '</table>';
    echo "</div>";
}





include('includes/footer.inc');

?>

<script>

document.getElementById('Schedule').focus();

function log_in()
{   
var f=0;
var p=0;
if(f==0){f=common_error('Schedule','Please enter the Schedule name');  if(f==1){return f; }  }
if(f==0){f=common_error('Days','Please enter Number of days');  if(f==1){return f; }  } 
}
</script>