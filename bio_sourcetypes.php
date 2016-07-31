<?php

/* $Id leadsourcetypes.php  2011-07-08 16:39:20Z tcv $ */
$PageSecurity = 40;
include('includes/session.inc');
$title = _('Lead Source Types') . ' / ' . _('Maintenance');
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

echo '<p class="page_title_text"><img src="'.$rootpath.'/css/'.$theme.'/images/maintenance.png" title="' . _('Lead Source Types')
	. '" alt="" />' . _('Lead Source Type Setup') . '</p>';
echo '<div class="page_help_text">' . _('Add/edit/delete Lead Source Types') . '</div><br />';

if (isset($_POST['submit'])) {

	//initialise no input errors assumed initially before we test
	$InputError = 0;

	/* actions to take once the user has clicked the submit button
	ie the page has called itself with some user input */

	//first off validate inputs sensible
	$i=1;
	if (strlen($_POST['SourceTypeName']) >100) {
		$InputError = 1;
		echo prnMsg(_('The Lead Source type name description must be 100 characters or less long'),'error');
		$Errors[$i] = 'LeadSourceType';
		$i++;
	}

	if (strlen($_POST['SourceTypeName'])==0) {
		$InputError = 1;
		echo prnMsg(_('The Lead Source type name description must contain at least one character'),'error');
		$Errors[$i] = 'LeadSourceType';
		$i++;
	}

	$checksql = "SELECT count(*)
		     FROM bio_leadsourcetypes
		     WHERE leadsourcetype = '" . $_POST['SourceTypeName'] . "'";
	$checkresult=DB_query($checksql, $db);
	$checkrow=DB_fetch_row($checkresult);
	if ($checkrow[0]>0) {
		$InputError = 1;
		echo prnMsg(_('You already have a Lead Source type called').' '.$_POST['SourceTypeName'],'error');
		$Errors[$i] = 'EnquiryName';
		$i++;
	}

	if (isset($SelectedType) AND $InputError !=1) {

		$sql = "UPDATE bio_leadsourcetypes
			SET leadsourcetype = '" . $_POST['SourceTypeName'] . "'
			WHERE id = '" . $SelectedType . "'";

		$msg = _('The Lead Source type') . ' ' . $SelectedType . ' ' .  _('has been updated');
	} elseif ( $InputError !=1 ) {

		// First check the type is not being duplicated

		$checkSql = "SELECT count(*)
			     FROM bio_leadsourcetypes
			     WHERE id = '" . $_POST['TypeID'] . "'";

		$checkresult = DB_query($checkSql,$db);
		$checkrow = DB_fetch_row($checkresult);

		if ( $checkrow[0] > 0 ) {
			$InputError = 1;
			prnMsg( _('The Lead Source type ') . $_POST['TypeID'] . _(' already exist.'),'error');
		} else {

			// Add new record on submit

			$sql = "INSERT INTO bio_leadsourcetypes
						(leadsourcetype)
					VALUES ('" . $_POST['SourceTypeName'] . "')";


			$msg = _('Lead Source type') . ' ' . $_POST['SourceTypeName'] .  ' ' . _('has been created');
			$checkSql = "SELECT count(id)
			     FROM bio_leadsourcetypes";
			$result = DB_query($checkSql, $db);
			$row = DB_fetch_row($result);

		}
	}

	if ( $InputError !=1) {
	//run the SQL from either of the above possibilites
		$result = DB_query($sql,$db);



		prnMsg($msg,'success');

		unset($SelectedType);
		unset($_POST['TypeID']);
		unset($_POST['SourceTypeName']);
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

		$sql="DELETE FROM bio_leadsourcetypes WHERE id='" . $SelectedType . "'";
		$ErrMsg = _('The Type record could not be deleted because');
		$result = DB_query($sql,$db,$ErrMsg);
		prnMsg(_('Lead Source type') . $SelectedType  . ' ' . _('has been deleted') ,'success');

		unset ($SelectedType);
		unset($_GET['delete']);

	/*}*/
}
function display($db)
{
        $sql = "SELECT id, leadsourcetype FROM bio_leadsourcetypes";
    $result = DB_query($sql,$db);
echo "<div style='overflow:scroll;height:150px'>";
    echo '<table class="selection">';
    echo '<tr>
        <th>' . _('Source Type ID') . '</th>
        <th>' . _('Lead Source Type') . '</th>
        </tr>';

$k=0; //row colour counter

while ($myrow = DB_fetch_row($result)) {
    if ($k==1){
        echo '<tr class="EvenTableRows">';
        $k=0;
    } else {
        echo '<tr class="OddTableRows">';
        $k=1;
    }

    printf('<td>%s</td>
            <td>%s</td>
            <td><a href="%sSelectedType=%s">' . _('Edit') . '</td>
            <td><a href="%sSelectedType=%s&delete=yes" onclick=\'return confirm("' .
                _('Are you sure you wish to delete this Lead Source Type?') . '");\'>' . _('Delete') . '</td>
        </tr>',
        $myrow[0],
        $myrow[1],
        $_SERVER['PHP_SELF'] . '?', 
        $myrow[0],
        $_SERVER['PHP_SELF'] . '?', 
        $myrow[0]);
    }
    //END WHILE LIST LOOP
    echo '</table>';
    echo "</div>";
}



//end of ifs and buts!
if (isset($SelectedType)) {

	echo '<div class="centre"><p><a href="' . $_SERVER['PHP_SELF'] . '">' . _('Show All Types Defined') . '</a></div><p>';
}

echo "<a href='index.php'>Back to Home</a>"  ;
echo "<table border=0 style='width:30%';><tr><td>";  
  echo "<fieldset style='width:340px;height:200px'>";
   echo "<legend><h3>Lead Source Type Master</h3></legend>";
if (! isset($_GET['delete'])) {

	echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '">';
	echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';
	echo '<br /><table class="selection">'; //Main table
	// The user wish to EDIT an existing type
	if ( isset($SelectedType) AND $SelectedType!='' ) {

		$sql = "SELECT id,
			       leadsourcetype
		        FROM bio_leadsourcetypes
		        WHERE id='" . $SelectedType . "'";

		$result = DB_query($sql, $db);
		$myrow = DB_fetch_array($result);

		$_POST['TypeID'] = $myrow['id'];
		$_POST['SourceTypeName']  = $myrow['leadsourcetype'];

		echo '<input type="hidden" name="SelectedType" value="' . $SelectedType . '">';
		echo '<input type="hidden" name="TypeID" value="' . $_POST['TypeID'] . '">';

		// We dont allow the user to change an existing type code

		echo '<tr><td>' ._('Lead Source Type ID') . ': </td>
				<td>' . $_POST['TypeID'] . '</td>
			</tr>';
	}

	if (!isset($_POST['SourceTypeName'])) {
		$_POST['SourceTypeName']='';
	}
	echo '<tr><td>' . _('Lead Source Type') . ':</td>
			<td><input type="text" name="SourceTypeName" id="typeid" value="' . $_POST['SourceTypeName'] . '"></td>
		</tr>';

	echo '<tr><td colspan=2><p><div class="centre"><input type=submit name=submit value="' . _('Accept') . '"></div>';

   	echo '</td></tr></table>'; // close main table

	echo '</form>';

} // end if user wish to delete
echo '</fieldset>'; 
echo "</td></tr>";
echo "<tr><td>";
    echo "<fieldset style='width:340px'>";
   echo "<legend><h3>Lead Source Type Master Created</h3></legend>";

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
include('includes/footer.inc');
?>
<script language="javascript">
  document.getElementById('typeid').focus();
  
    $(document).ready(function() {
    $("#notice").fadeOut(3000);
});
  </script>