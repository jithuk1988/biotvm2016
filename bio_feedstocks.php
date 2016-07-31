<?php

/* $Id Feedstocks.php  2011-07-08 16:39:20Z tcv $ */
$PageSecurity = 40;
include('includes/session.inc');
$title = _('Feedstocks ') . ' / ' . _('Maintenance');
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

echo '<p class="page_title_text"><img src="'.$rootpath.'/css/'.$theme.'/images/maintenance.png" title="' . _('Feedstocks')
	. '" alt="" />' . _('Feedstocks Setup') . '</p>';
echo '<div class="page_help_text">' . _('Add / edit / delete Feedstocks') . '</div><br />';

if (isset($_POST['submit'])) {

	//initialise no input errors assumed initially before we test
	$InputError = 0;

	/* actions to take once the user has clicked the submit button
	ie the page has called itself with some user input */

	//first off validate inputs sensible
	$i=1;
	if (strlen($_POST['Feedstocks']) >100) {
		$InputError = 1;
		echo prnMsg(_('The feedstocks name description must be 100 characters or less long'),'error');
		$Errors[$i] = 'Feedstocks';
		$i++;
	}

	if (strlen($_POST['Feedstocks'])==0) {
		$InputError = 1;
		echo prnMsg(_('The Feedstocks name description must contain at least one character'),'error');
		$Errors[$i] = 'Feedstocks';
		$i++;
	}

	$checksql = "SELECT count(*)
		     FROM bio_feedstocks
		     WHERE feedstocks = '" . $_POST['Feedstocks'] . "'";
	$checkresult=DB_query($checksql, $db);
	$checkrow=DB_fetch_row($checkresult);
	//if ($checkrow[0]>0) {
//		$InputError = 1;
//		echo prnMsg(_('You already have a Feedstocks called').' '.$_POST['Feedstocks'],'error');
//		$Errors[$i] = 'feedstocks';
//		$i++;
//	}

	if (isset($SelectedType) AND $InputError !=1) {

		$sql = "UPDATE bio_feedstocks
			       SET feedstocks='".$_POST['Feedstocks']."'
			       WHERE id = '" . $SelectedType . "'";

		$msg = _('The Feedstocks') . ' ' . $SelectedType . ' ' .  _(' has been updated');
	} elseif ( $InputError !=1 ) {

		// First check the feedstock is not being duplicated

		$checkSql = "SELECT count(*)
			     FROM bio_feedstocks
			     WHERE feedstocks = '" . $_POST['Feedstocks'] . "'";

		$checkresult = DB_query($checkSql,$db);
		$checkrow = DB_fetch_row($checkresult);

		if ( $checkrow[0] > 0 ) {
			$InputError = 1;
			prnMsg( _('The Feedstocks ') . $_POST['FeedstockID'] . _(' already exist.'),'warn');
		} else {

			// Add new record on submit

			 $sql = "INSERT INTO bio_feedstocks(feedstocks)
					VALUES ('" . $_POST['Feedstocks'] . "')";


			$msg = _('Feedstocks') . ' ' . $_POST['Feedstocks'] .  ' ' . _('has been created');
			$checkSql = "SELECT count(id)
			     FROM bio_feedstocks";
			$result = DB_query($checkSql, $db);
			$row = DB_fetch_row($result);

		}
	}

	if ( $InputError !=1) {
	//run the SQL from either of the above possibilites
		$result = DB_query($sql,$db);



		prnMsg($msg,'success');

		unset($SelectedType);
		unset($_POST['FeedstockID']);
		unset($_POST['Feedstocks']);
        unset($_POST['Category']);
        unset($_POST['Type']);
	}

} elseif ( isset($_GET['delete']) ) {

	/* $sql = "SELECT COUNT(*) FROM suppliers WHERE supptype='" . $SelectedType . "'";

	$ErrMsg = _('The number of suppliers using this Feedstock record could not be retrieved because');
	$result = DB_query($sql,$db,$ErrMsg);
	$myrow = DB_fetch_row($result);
	if ($myrow[0]>0) {
		prnMsg (_('Cannot delete this feedstock because suppliers are currently set up to use this feedstock') . '<br />' .
			_('There are') . ' ' . $myrow[0] . ' ' . _('suppliers with this feedstock code'));
	} else { */

		$sql="DELETE FROM bio_feedstocks WHERE id='" . $SelectedType . "'";
		$ErrMsg = _('The Feedstock record could not be deleted because');
		$result = DB_query($sql,$db,$ErrMsg);
		prnMsg(_('Feedstocks ') . $SelectedType  . ' ' . _('has been deleted') ,'success');

		unset ($SelectedType);
		unset($_GET['delete']);

	/*}*/
} 


//==============================================================================

 if (isset($SelectedType)) {

    echo '<div class="centre"><p><a href="' . $_SERVER['PHP_SELF'] . '">' . _('Show All Feedstocks Defined') . '</a></div><p>';
}
echo "<a href='index.php'>Back to Home</a>"  ;
echo "<table border=0 style='width:50%';><tr><td>";  
  echo "<fieldset style='width:550px;height:200px'>";
   echo "<legend><h3>Feedstocks Master</h3></legend>";
if (! isset($_GET['delete'])) {

    echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '">';
    echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';
    echo '<br /><table class="selection">'; //Main table
    // The user wish to EDIT an existing feedstock
    if ( isset($SelectedType) AND $SelectedType!='' ) {

        $sql = "SELECT id,
                       feedstocks
                FROM bio_feedstocks
                WHERE id='" . $SelectedType . "'";

        $result = DB_query($sql, $db);
        $myrow = DB_fetch_array($result);

        $_POST['FeedstockID'] = $myrow['id'];
        $_POST['Feedstocks']  = $myrow['feedstocks'];

        echo '<input type="hidden" name="SelectedType" value="' . $SelectedType . '">';
        echo '<input type="hidden" name="FeedstockID" value="' . $_POST['FeedstockID'] . '">';

        // We dont allow the user to change an existing feedstock code

        //echo '<tr><td>' ._('Feedstocks ID') . ': </td>
//                <td>' . $_POST['FeedstockID'] . '</td>
//            </tr>';
    }

    if (!isset($_POST['Feedstocks'])) {
        $_POST['Feedstocks']='';
    }
    if (!isset($_POST['Category'])) {
        $_POST['Category']='';
    }
    echo '<tr><td>' . _('Feedstocks') . ':</td>
            <td><input type="text" name="Feedstocks" id="feedstock" value="' . $_POST['Feedstocks'] . '" style="width:190px"></td>
        </tr>';
        
   
/*          
      
 $sql1="SELECT * FROM bio_wastecategory";
  $result1=DB_query($sql1, $db);
 
            echo '<tr><td>' . _('Category') . ':</td>
         '; 
         echo '<td><select name="Category" id="Category" style="width:193px">';
  $f=0;
    while($myrow1=DB_fetch_array($result1))
    { 
    if ($myrow1['waste_category_id']==$myrow['waste_category']) 
    {
       
    echo '<option selected value="';
    
    } else {
        if ($f==0) 
        {
        echo '<option>';
        }
        echo '<option value="';
        $f++;
    }
    echo $myrow1['waste_category_id'] . '">'.$myrow1['waste_category'];
    echo '</option>';
    }
     
    echo '</select></td></tr>';    
    
    
     $sql1="SELECT * FROM bio_wastetype";
  $result1=DB_query($sql1, $db);
 
            echo '<tr><td>' . _('Type') . ':</td>
         '; 
         echo '<td><select name="Type" id="Type" style="width:193px">';
  $f=0;
    while($myrow1=DB_fetch_array($result1))
    { 
    if ($myrow1['waste_type_id']==$myrow['waste_type']) 
    {
       
    echo '<option selected value="';
    
    } else {
        if ($f==0) 
        {
        echo '<option>';
        }
        echo '<option value="';
        $f++;
    }
    echo $myrow1['waste_type_id'] . '">'.$myrow1['waste_type'];
    echo '</option>';
    }
     
    echo '</select></td></tr>'; */   

    echo '<tr><td colspan=2><p><div class="centre"><input type=submit name=submit value="' . _('Save') . '" onclick="if(log_in()==1)return false;"></div>';

       echo '</td></tr></table>'; // close main table

    echo '</form>';

} 




//===============================================================================

echo '</fieldset>'; 
echo "</td></tr>";
echo "<tr><td>";
    echo "<fieldset style='width:550px'>";
   echo "<legend><h3>Feedstocks Master Created</h3></legend>";
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




//====================================================================================
function display($db)
{
        $sql = "SELECT bio_feedstocks.id, 
                       bio_feedstocks.feedstocks
                  FROM bio_feedstocks";  
    $result = DB_query($sql,$db);
echo "<div style='overflow:scroll;height:150px'>";
    echo '<table class="selection" width="100%">';
    echo '<tr>  
        <th>' . _('SlNo') . '</th>
        <th>' . _('Feedstocks') . '</th>
        </tr>';

$k=0; //row colour counter
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
            
            <td><a href="%sSelectedType=%s">' . _('Edit') . '</td>
            <td><a href="%sSelectedType=%s&delete=yes" onclick=\'return confirm("' .
                _('Are you sure you wish to delete this Feedstocks?') . '");\'>' . _('Delete') . '</td>
        </tr>',
        $slno,
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
// end if user wish to delete

include('includes/footer.inc');
?>

<script type="text/javascript"> 

document.getElementById('feedstock').focus();
 
 $(document).ready(function() {
     $("#error").fadeOut(3000);
    $("#warn").fadeOut(8000);
      $("#success").fadeOut(3000);
        $("#info").fadeOut(3000);
         $(".db_message").fadeOut(3200);
     
 });    


function log_in()
{   
var f=0;
var p=0;
if(f==0){f=common_error('feedstock','Please enter Feedstock');  if(f==1){return f; }  }
if(f==0){f=common_error('Category','Please select a Category');  if(f==1){return f; }  } 
}

</script>