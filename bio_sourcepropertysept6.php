<?php

/* $Id leadsourcetypes.php  2011-07-08 16:39:20Z tcv $ */
$PageSecurity = 80;
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

echo '<p class="page_title_text"><img src="'.$rootpath.'/css/'.$theme.'/images/maintenance.png" title="' . _('Lead Source Property')
	. '" alt="" />' . _('Lead Source Property Setup') . '</p>';
echo '<div class="page_help_text">' . _('Add / edit / delete Lead Source Property') . '</div><br />';

if (isset($_POST['submit'])) {

	//initialise no input errors assumed initially before we test
	$InputError = 0;

	/* actions to take once the user has clicked the submit button
	ie the page has called itself with some user input */

	//first off validate inputs sensible
	$i=1;
	if (strlen($_POST['SourceProperty']) >50) {
		$InputError = 1;
		echo prnMsg(_('The Lead Source Property must be 50 characters or less long'),'error');
		$Errors[$i] = 'LeadSourceType';
		$i++;
	}

	if (strlen($_POST['SourceProperty'])==0) {
		$InputError = 1;
		echo prnMsg(_('The Lead Source Property must contain at least one character'),'error');
		$Errors[$i] = 'LeadSourceProperty';
		$i++;
	}

	$checksql = "SELECT count(*)
		     FROM bio_sourceproperty
		     WHERE property = '" . $_POST['SourceProperty'] . "'";
	$checkresult=DB_query($checksql, $db);
	$checkrow=DB_fetch_row($checkresult);
	if ($checkrow[0]>0) {
		$InputError = 1;
		echo prnMsg(_('You already have a Lead Source Property called').' '.$_POST['SourceProperty'],'error');
		$Errors[$i] = 'SourceProperty';
		$i++;
	}

	if (isset($SelectedType) AND $InputError !=1) {

		$sql = "UPDATE bio_sourceproperty
			SET property = '" . $_POST['SourceProperty'] .
                        "',sourcetypeid = '" . $_POST['SourceTypeID'] .
			"' WHERE sourcepropertyid = '" . $SelectedType . "'";
		$msg = _('The Lead Source Property') . ' ' . $SelectedType . ' ' .  _('has been updated');
	} elseif ( $InputError !=1 ) {

		// First check the type is not being duplicated

		$checkSql = "SELECT count(*)
			     FROM bio_sourceproperty
			     WHERE sourcepropertyid = '" . $_POST['PropertyID'] . "'";

		$checkresult = DB_query($checkSql,$db);
		$checkrow = DB_fetch_row($checkresult);

		if ( $checkrow[0] > 0 ) {
			$InputError = 1;
			prnMsg( _('The Lead Source Property ') . $_POST['PropertyID'] . _(' already exist.'),'error');
		} else {

			// Add new record on submit

			$sql = "INSERT INTO bio_sourceproperty
						(sourcepropertyid, sourcetypeid, property)
					VALUES (" .$_POST['PropertyID'] . ", " . $_POST['SourceTypeID'] . ", '" . $_POST['SourceProperty'] . "')";


			$msg = _('Lead Source Property') . ' ' . $_POST['SourceProperty'] .  ' ' . _('has been created');
			$checkSql = "SELECT count(sourcepropertyid)
			     FROM bio_sourceproperty";
			$result = DB_query($checkSql, $db);
			$row = DB_fetch_row($result);

		}
	}

	if ( $InputError !=1) {
	//run the SQL from either of the above possibilites
		$result = DB_query($sql,$db);



		prnMsg($msg,'success');

		unset($SelectedType);
		unset($_POST['PropertyID']);
		unset($_POST['SourceTypeID']);
		unset($_POST['SourceProperty']);
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

		$sql="DELETE FROM bio_sourceproperty WHERE sourcepropertyid='" . $SelectedType . "'";
		$ErrMsg = _('The Property record could not be deleted because');
		$result = DB_query($sql,$db,$ErrMsg);
		prnMsg(_('Lead Source Property') . $SelectedType  . ' ' . _('has been deleted') ,'success');

		unset ($SelectedType);
		unset($_GET['delete']);

	/*}*/
}
function display($db)
{
        $sql = "SELECT sourcepropertyid, sourcetypeid, leadsourcetype, property FROM bio_sourceproperty, bio_leadsourcetypes
                 where bio_sourceproperty.sourcetypeid = bio_leadsourcetypes.id ";
    $result = DB_query($sql,$db);
echo "<div style='overflow:scroll;height:150px'>";
    echo '<table class="selection">';
    echo '<tr>
                <th>' . _('Source Property ID') . '</th>
        <th>' . _('Source Type') . '</th>
        <th>' . _('Lead Source Property') . '</th>
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
            <td>%s</td><td>%s</td>
            <td><a href="%sSelectedType=%s">' . _('Edit') . '</td>
            <td><a href="%sSelectedType=%s&delete=yes" onclick=\'return confirm("' .
                _('Are you sure you wish to delete this Lead Source Property?') . '");\'>' . _('Delete') . '</td>
        </tr>',
        $myrow[0],
        $myrow[2],
        $myrow[3],
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
   echo "<legend><h3>Source Property Master</h3></legend>";
if (! isset($_GET['delete'])) {

	echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '">';
	echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';
	echo '<br /><table class="selection">'; //Main table
	// The user wish to EDIT an existing type
	if ( isset($SelectedType) AND $SelectedType!='' ) {

		$sql = "SELECT sourcepropertyid,
			       sourcetypeid, property
		        FROM bio_sourceproperty
                        WHERE sourcepropertyid='" . $SelectedType . "'";

		$result = DB_query($sql, $db);
		$myrow = DB_fetch_array($result);

                $_POST['PropertyID'] = $myrow['sourcepropertyid'];
                $_POST['SourceTypeID'] = $myrow['sourcetypeid'];
		$_POST['SourceProperty']  = $myrow['property'];

		echo '<input type="hidden" name="SelectedType" value="' . $SelectedType . '">';
		echo '<input type="hidden" name="PropertyID" value="' . $_POST['PropertyID'] . '">';

		// We dont allow the user to change an existing type code
	}

	if (!isset($_POST['SourceProperty'])) {
		$_POST['SourceProperty']='';
	}
	echo '<tr><td>' . _('Lead Source Property ID') . ':</td>
	                <!-- <td>' . $_POST['PropertyID'] . '</td> -->
			<td><input type="text" name="PropertyID" id="propertyid" style="width:97%" value="' . $_POST['PropertyID'] . '"></td>
		</tr>';
        echo '<tr><td>' . _('Lead Source Type') . ':</td><td>';
        echo '<select name="SourceTypeID" style="width:192px">';
        $sql="SELECT * FROM `bio_leadsourcetypes`";
        $result=DB_query($sql,$db);
        echo $count=DB_fetch_row($sql,$db);

         while ($myrow = DB_fetch_array($result)) 
         {
         //$c=0;
     if ($myrow['id']==$_POST['SourceTypeID'])
    {
    echo '<option selected value="';
    } else {

        echo '<option value="';
    }
    echo $myrow['id'] . '">'.$myrow['leadsourcetype'];
   echo '</option>';
   //$c++;
    }
    //echo $c;
    echo '</select></td></tr>';
	echo '<tr><td>' . _('Lead Source Property') . ':</td>
			<td><input type="text" name="SourceProperty"  style="width:97%" value="' . $_POST['SourceProperty'] . '"></td>
		</tr>';

	echo '<tr><td colspan=2><p><div class="centre"><input type=submit name=submit value="' . _('Accept') . '"></div>';

   	echo '</td></tr></table>'; // close main table

	echo '</form>';

} // end if user wish to delete

echo '</fieldset>'; 
echo "</td></tr>";
echo "<tr><td>";
    echo "<fieldset style='width:340px'>";
   echo "<legend><h3>Source Property  Created</h3></legend>";

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
include('includes/footer.inc');
?>
<script language="javascript">
  document.getElementById('propertyid').focus();
  
    $(document).ready(function() {
    $("#notice").fadeOut(3000);
});
  </script>