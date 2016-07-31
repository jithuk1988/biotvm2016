<?php

/* $Revision: 1.25 $ */

$PageSecurity = 11;

include('includes/session.inc');

$title = _('Location Maintenance');

include('includes/header.inc');



if($_GET['bsid']!=null)
{
$_SESSION['bsid']=$_GET['bsid'];
$bsid=$_GET['bsid'];   
}
else if($_SESSION['bsid']!=null) 
{
$bsid=$_SESSION['bsid'];
}





if (isset($_GET['SelectedLocation'])){
	$SelectedLocation = $_GET['SelectedLocation'];
} elseif (isset($_POST['SelectedLocation'])){
	$SelectedLocation = $_POST['SelectedLocation'];
}

if (isset($_POST['submit'])) {

	//initialise no input errors assumed initially before we test
	$InputError = 0;

	/* actions to take once the user has clicked the submit button
	ie the page has called itself with some user input */

	$_POST['LocCode']=strtoupper($_POST['LocCode']);
	if( trim($_POST['LocCode']) == '' ) {
		$InputError = 1;
		prnMsg( _('The location code may not be empty'), 'error');
	}

	if (isset($SelectedLocation) AND $InputError !=1) {

		/* Set the managed field to 1 if it is checked, otherwise 0 */
		if(isset($_POST['Managed']) and $_POST['Managed'] == 'on'){
			$_POST['Managed'] = 1;
		} else {
			$_POST['Managed'] = 0;
		}

		$sql = "UPDATE locations SET
				loccode='" . $_POST['LocCode'] . "',
				locationname='" . $_POST['LocationName'] . "',
				deladd1='" . $_POST['DelAdd1'] . "',
				deladd2='" . $_POST['DelAdd2'] . "',
				deladd3='" . $_POST['DelAdd3'] . "',
				deladd4='" . $_POST['DelAdd4'] . "',
				deladd5='" . $_POST['DelAdd5'] . "',
				deladd6='" . $_POST['DelAdd6'] . "',
				tel='" . $_POST['Tel'] . "',
				fax='" . $_POST['Fax'] . "',
				email='" . $_POST['Email'] . "',
				contact='" . $_POST['Contact'] . "',
				taxprovinceid = " . $_POST['TaxProvince'] . ",
				managed = " . $_POST['Managed'] . "
			WHERE loccode = '$SelectedLocation'";

		$ErrMsg = _('An error occurred updating the') . ' ' . $SelectedLocation . ' ' . _('location record because');
		$DbgMsg = _('The SQL used to update the location record was');

		$result = DB_query($sql,$db,$ErrMsg,$DbgMsg);

		prnMsg( _('The location record has been updated'),'success');
		
		
		$sql = "UPDATE storespriority SET
				loccode='" . $_POST['LocCode'] . "',
				priority='" . $_POST['priority'] . "'
				
			WHERE loccode = '$SelectedLocation'";

		$ErrMsg = _('An error occurred updating the') . ' ' . $SelectedLocation . ' ' . _('location record because');
		$DbgMsg = _('The SQL used to update the location record was');

		$result = DB_query($sql,$db,$ErrMsg,$DbgMsg);

		//prnMsg( _('The location record has been updated'),'success');
		
		
		
		unset($_POST['LocCode']);
		unset($_POST['LocationName']);
		unset($_POST['DelAdd1']);
		unset($_POST['DelAdd2']);
		unset($_POST['DelAdd3']);
		unset($_POST['DelAdd4']);
		unset($_POST['DelAdd5']);
		unset($_POST['DelAdd6']);
		unset($_POST['Tel']);
		unset($_POST['Fax']);
		unset($_POST['Email']);
		unset($_POST['priority']); 
		unset($_POST['TaxProvince']);
		unset($_POST['Managed']);
		unset($SelectedLocation);
		unset($_POST['Contact']);


	} elseif ($InputError !=1) {

		/* Set the managed field to 1 if it is checked, otherwise 0 */
		if($_POST['Managed'] == 'on') {
			$_POST['Managed'] = 1;
		} else {
			$_POST['Managed'] = 0;
		}

		/*SelectedLocation is null cos no item selected on first time round so must be adding a	record must be submitting new entries in the new Location form */

		$sql = "INSERT INTO locations (
					loccode,
					locationname,
					deladd1,
					deladd2,
					deladd3,
					deladd4,
					deladd5,
					deladd6,
					tel,
					fax,
					email,
					contact,
					taxprovinceid,
					managed
					)
			VALUES (
				'" . $_POST['LocCode'] . "',
				'" . $_POST['LocationName'] . "',
				'" . $_POST['DelAdd1'] ."',
				'" . $_POST['DelAdd2'] ."',
				'" . $_POST['DelAdd3'] . "',
				'" . $_POST['DelAdd4'] . "',
				'" . $_POST['DelAdd5'] . "',
				'" . $_POST['DelAdd6'] . "',
				'" . $_POST['Tel'] . "',
				'" . $_POST['Fax'] . "',
				'" . $_POST['Email'] . "',
				'" . $_POST['Contact'] . "',
				" . $_POST['TaxProvince'] . ",
				" . $_POST['Managed'] . "
			)";

		$ErrMsg =  _('An error occurred inserting the new location record because');
		$Dbgmsg =  _('The SQL used to insert the location record was');
		$result = DB_query($sql,$db,$ErrMsg,$DbgMsg);

		prnMsg( _('The new location record has been added'),'success');
		
		
		$sql = "INSERT INTO storespriority (
					loccode,
					priority
					)
			VALUES (
				'" . $_POST['LocCode'] . "',
				'" . $_POST['priority'] . "'
				)";

		$ErrMsg =  _('An error occurred inserting the new location record because');
		$Dbgmsg =  _('The SQL used to insert the location record was');
		$result = DB_query($sql,$db,$ErrMsg,$DbgMsg);

		//prnMsg( _('The new location record has been added'),'success');
		

	/* Also need to add LocStock records for all existing stock items */

		$sql = "INSERT INTO locstock (
					loccode,
					stockid,
					quantity,
					reorderlevel)
			SELECT '" . $_POST['LocCode'] . "',
				stockmaster.stockid,
				0,
				0
			FROM stockmaster";

		$ErrMsg =  _('An error occurred inserting the new location stock records for all pre-existing parts because');
		$DbgMsg =  _('The SQL used to insert the new stock location records was');
		$result = DB_query($sql,$db,$ErrMsg, $DbgMsg);

		echo '<br>........ ' . _('and new stock locations inserted for all existing stock items for the new location');
		//unset($_POST['LocCode']);
		unset($_POST['LocationName']);
		unset($_POST['DelAdd1']);
		unset($_POST['DelAdd2']);
		unset($_POST['DelAdd3']);
		unset($_POST['DelAdd4']);
		unset($_POST['DelAdd5']);
		unset($_POST['DelAdd6']);
		unset($_POST['Tel']);
		unset($_POST['Fax']);
		unset($_POST['Email']);
		unset($_POST['priority']);
		unset($_POST['TaxProvince']);
		unset($_POST['Managed']);
		unset($SelectedLocation);
		unset($_POST['Contact']);

	}


	/* Go through the tax authorities for all Locations deleting or adding TaxAuthRates records as necessary */

	$result = DB_query('SELECT COUNT(taxid) FROM taxauthorities',$db);
	$NoTaxAuths =DB_fetch_row($result);

	$DispTaxProvincesResult = DB_query('SELECT taxprovinceid FROM locations',$db);
	$TaxCatsResult = DB_query('SELECT taxcatid FROM taxcategories',$db);
	if (DB_num_rows($TaxCatsResult) > 0 ) { // This will only work if there are levels else we get an error on seek.

		while ($myrow=DB_fetch_row($DispTaxProvincesResult)){
			/*Check to see there are TaxAuthRates records set up for this TaxProvince */
			$NoTaxRates = DB_query('SELECT taxauthority FROM taxauthrates WHERE dispatchtaxprovince=' . $myrow[0], $db);

			if (DB_num_rows($NoTaxRates) < $NoTaxAuths[0]){

				/*First off delete any tax authoritylevels already existing */
				$DelTaxAuths = DB_query('DELETE FROM taxauthrates WHERE dispatchtaxprovince=' . $myrow[0],$db);

				/*Now add the new TaxAuthRates required */
				while ($CatRow = DB_fetch_row($TaxCatsResult)){
					$sql = 'INSERT INTO taxauthrates (taxauthority,
										dispatchtaxprovince,
										taxcatid)
							SELECT taxid,
								' . $myrow[0] . ',
								' . $CatRow[0] . '
							FROM taxauthorities';

					$InsTaxAuthRates = DB_query($sql,$db);
				}
				DB_data_seek($TaxCatsResult,0);
			}
		}
	}         
            if(isset($bsid) and $bsid!=null)
   {                          
       if($_POST['LocCode']!=null && $_POST['bsid']!=null)
       {             
            echo '<meta http-equiv="Refresh" content="0; url=' . $rootpath .'/Customers.php?bussid='.$bsid.'&loccode='.$_POST['LocCode'].'">'; 
       } 
       else
        {
            prnMsg("Location Code and Location Name should not be blank",'warn');
        }                  
    //echo '<meta http-equiv="Refresh" content="0; url=' . $rootpath .'/SalesPeople.php?bsid='.$sid.'">';
   }       
    
    

} elseif (isset($_GET['delete'])) {
//the link to delete a selected record was clicked instead of the submit button

	$CancelDelete = 0;

// PREVENT DELETES IF DEPENDENT RECORDS
	$sql= "SELECT COUNT(*) FROM salesorders WHERE fromstkloc='$SelectedLocation'";
	$result = DB_query($sql,$db);
	$myrow = DB_fetch_row($result);
	if ($myrow[0]>0) {
		$CancelDelete = 1;
		prnMsg( _('Cannot delete this location because sales orders have been created delivering from this location'),'warn');
		echo  _('There are') . ' ' . $myrow[0] . ' ' . _('sales orders with this Location code');
	} else {
		$sql= "SELECT COUNT(*) FROM stockmoves WHERE stockmoves.loccode='$SelectedLocation'";
		$result = DB_query($sql,$db);
		$myrow = DB_fetch_row($result);
		if ($myrow[0]>0) {
			$CancelDelete = 1;
			prnMsg( _('Cannot delete this location because stock movements have been created using this location'),'warn');
			echo '<br>' . _('There are') . ' ' . $myrow[0] . ' ' . _('stock movements with this Location code');

		} else {
			$sql= "SELECT COUNT(*) FROM locstock WHERE locstock.loccode='$SelectedLocation' AND locstock.quantity !=0";
			$result = DB_query($sql,$db);
			$myrow = DB_fetch_row($result);
			if ($myrow[0]>0) {
				$CancelDelete = 1;
				prnMsg(_('Cannot delete this location because location stock records exist that use this location and have a quantity on hand not equal to 0'),'warn');
				echo '<br> ' . _('There are') . ' ' . $myrow[0] . ' ' . _('stock items with stock on hand at this location code');
			} else {
				$sql= "SELECT COUNT(*) FROM www_users WHERE www_users.defaultlocation='$SelectedLocation'";
				$result = DB_query($sql,$db);
				$myrow = DB_fetch_row($result);
				if ($myrow[0]>0) {
					$CancelDelete = 1;
					prnMsg(_('Cannot delete this location because it is the default location for a user') . '. ' . _('The user record must be modified first'),'warn');
					echo '<br> ' . _('There are') . ' ' . $myrow[0] . ' ' . _('users using this location as their default location');
				} else {
					$sql= "SELECT COUNT(*) FROM bom WHERE bom.loccode='$SelectedLocation'";
					$result = DB_query($sql,$db);
					$myrow = DB_fetch_row($result);
					if ($myrow[0]>0) {
						$CancelDelete = 1;
						prnMsg(_('Cannot delete this location because it is the default location for a bill of material') . '. ' . _('The bill of materials must be modified first'),'warn');
						echo '<br> ' . _('There are') . ' ' . $myrow[0] . ' ' . _('bom components using this location');
					} else {
						$sql= "SELECT COUNT(*) FROM workcentres WHERE workcentres.location='$SelectedLocation'";
						$result = DB_query($sql,$db);
						$myrow = DB_fetch_row($result);
						if ($myrow[0]>0) {
							$CancelDelete = 1;
							prnMsg( _('Cannot delete this location because it is used by some work centre records'),'warn');
							echo '<br>' . _('There are') . ' ' . $myrow[0] . ' ' . _('works centres using this location');
						} else {
							$sql= "SELECT COUNT(*) FROM workorders WHERE workorders.loccode='$SelectedLocation'";
							$result = DB_query($sql,$db);
							$myrow = DB_fetch_row($result);
							if ($myrow[0]>0) {
								$CancelDelete = 1;
								prnMsg( _('Cannot delete this location because it is used by some work order records'),'warn');
								echo '<br>' . _('There are') . ' ' . $myrow[0] . ' ' . _('work orders using this location');
							}else {
								$sql= "SELECT COUNT(*) FROM custbranch WHERE custbranch.defaultlocation='$SelectedLocation'";
								$result = DB_query($sql,$db);
								$myrow = DB_fetch_row($result);
								if ($myrow[0]>0) {
									$CancelDelete = 1;
									prnMsg(_('Cannot delete this location because it is used by some branch records as the default location to deliver from'),'warn');
									echo '<br> ' . _('There are') . ' ' . $myrow[0] . ' ' . _('branches set up to use this location by default');
								}
							}
						}
					}
				}
			}
		}
	}
	if (! $CancelDelete) {

		/* need to figure out if this location is the only one in the same tax province */
		$result = DB_query("SELECT taxprovinceid FROM locations WHERE loccode='" . $SelectedLocation . "'",$db);
		$TaxProvinceRow = DB_fetch_row($result);
		$result = DB_query("SELECT COUNT(taxprovinceid) FROM locations WHERE taxprovinceid=" .$TaxProvinceRow[0],$db);
		$TaxProvinceCount = DB_fetch_row($result);
		if ($TaxProvinceCount[0]==1){
		/* if its the only location in this tax authority then delete the appropriate records in TaxAuthLevels */
			$result = DB_query('DELETE FROM taxauthrates WHERE dispatchtaxprovince=' . $TaxProvinceRow[0],$db);
		}

		$result= DB_query("DELETE FROM locstock WHERE loccode ='" . $SelectedLocation . "'",$db);
		$result = DB_query("DELETE FROM locations WHERE loccode='" . $SelectedLocation . "'",$db);
		$result = DB_query("DELETE FROM storespriority WHERE loccode='" . $SelectedLocation . "'",$db);

		prnMsg( _('Location') . ' ' . $SelectedLocation . ' ' . _('has been deleted') . '!', 'success');
		unset ($SelectedLocation);
	} //end if Delete Location
	unset($SelectedLocation);
	unset($_GET['delete']);
}

if (!isset($SelectedLocation)) {
    
if($_GET['bsid']!=null and $_GET['areacode']!=null)   
{ 
$areacode=$_GET['areacode'];
$bsid=$_GET['bsid'];

 $sqltr="INSERT INTO bio_dealerterritory (buss_id,areacode)
                VALUES ($bsid,'$areacode')";
$result = DB_query($sqltr, $db); 
}     
/* It could still be the second time the page has been run and a record has been selected for modification - SelectedLocation will exist because it was sent with the new call. If its the first time the page has been displayed with no parameters
then none of the above are true and the list of Locations will be displayed with
links to delete or edit each. These will call the same page again and allow update/input
or deletion of the records*/

	$sql = "SELECT locations.loccode,
			locations.locationname,
			taxprovinces.taxprovincename as description,
			managed,
			storespriority.priority
			FROM locations INNER JOIN taxprovinces ON locations.taxprovinceid=taxprovinces.taxprovinceid,storespriority
			WHERE   storespriority.loccode= locations.loccode
			";
		
	$result = DB_query($sql,$db);

	if (DB_num_rows($result)==0){
		prnMsg (_('There are no locations that match up with a tax province record to display. Check that tax provinces are set up for all dispatch locations'),'error');
	}
      
	echo '<table border=1>
	<tr>';

    if($_GET['bsid']!=null and $_GET['areacode']!=null)
    {
         echo '<th>' . _('select') . '</th>';
    }  
    
    echo '<th>' . _('Location Code') . '</th>';
	echo '<th>' . _('Location Name') . '</th>';
	echo '<th>' . _('Tax Province') . '</th>';
	echo '<th>' . _('Managed') . '</th>';
	echo '<th>' . _('priority') . '</th>';
	echo '</tr>';

$k=0; //row colour counter
while ($myrow = DB_fetch_array($result)) {
	if ($k==1){
		echo '<tr class="EvenTableRows">';
		$k=0;
	} else {
		echo '<tr class="OddTableRows">';
		$k=1;
	}

	if($myrow['managed'] == 1) {
		$myrow['managed'] = _('Yes');
	}  else {
		$myrow['managed'] = _('No');
	}
    
    if($_GET['bsid']!=null and $_GET['areacode']!=null)
        {                                                                                        
            echo "<td><input type=radio name=rad id=rad onClick=selection($_GET[bsid],'$myrow[0]');></td>"; 
        }
        echo '<td>' . $myrow[0] . '</td>
              <td>' . $myrow[1] . '</td>
              <td>' . $myrow[2] . '</td>
              <td>' . $myrow[3] . '</td>
              <td>' . $myrow[4] . '</td>';        
        echo '<td><a href="' . $_SERVER['PHP_SELF'] . '?SelectedLocation=' . $myrow[0] . '">' . _('Edit') . '</a></td>';
        echo '<td><a href="' . $_SERVER['PHP_SELF'] . '?SelectedLocation=' . $myrow[0] . '&delete=1" onclick="return confirm(\'' . _('Are you sure you wish to delete this sales area?') . '\');">' . _('Delete') . '</a></td>';
   //     echo '<td><a href="SelectCustomer.php'. '?Area=' . $myrow[0] . '">' . _('View Customers from this Area') . '</a></td>';

	/*printf("<td> </td><td>%s</td>
		<td>%s</td>
		<td>%s</td>
		<td>%s</td>
		 <td>%s</td> 
		<td><a href='%sSelectedLocation=%s'>" . _('Edit') . "</td>
		<td><a href='%sSelectedLocation=%s&delete=1'>" . _('Delete') . '</td>
		</tr>',
		$myrow['loccode'],
		$myrow['locationname'],
		$myrow['description'],
		$myrow['managed'],
		$myrow[4],  
		$_SERVER['PHP_SELF'] . '?' . SID . '&',
		$myrow['loccode'],
		$_SERVER['PHP_SELF'] . '?' . SID . '&',
		$myrow['loccode']); */
        //echo

	}
	//END WHILE LIST LOOP
	echo '</table>';
}

//end of ifs and buts!

?>

<p>
<?php
if (isset($SelectedLocation)) {  ?>
	<div class="centre"><a href="<?php echo $_SERVER['PHP_SELF'];?>"><?php echo _('REVIEW RECORDS'); ?></a></div>
<?php } ?>

<p>


<?php



if (!isset($_GET['delete'])) {

	echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . '?' . SID . "'>";

	if (isset($SelectedLocation)) {
		//editing an existing Location

		$sql = "SELECT locations.loccode,
				locations.locationname,
				locations.deladd1,
				locations.deladd2,
				locations.deladd3,
				locations.deladd4,
				locations.deladd5,
				locations.deladd6,
				locations.contact,
				locations.fax,
				locations.tel,
				locations.email,
				locations.taxprovinceid,
				locations.managed,
				storespriority.priority
			FROM locations,storespriority
			WHERE locations.loccode='$SelectedLocation'
			AND storespriority.loccode=locations.loccode";

		$result = DB_query($sql, $db);
		$myrow = DB_fetch_array($result);

		$_POST['LocCode'] = $myrow[0];
		$_POST['LocationName']  = $myrow[1];
		$_POST['DelAdd1'] = $myrow[2];
		$_POST['DelAdd2'] = $myrow[3];
		$_POST['DelAdd3'] = $myrow[4];
		$_POST['DelAdd4'] = $myrow[5];
		$_POST['DelAdd5'] = $myrow[6];
		$_POST['DelAdd6'] = $myrow[7];
		$_POST['Contact'] = $myrow[8];
		$_POST['Tel'] = $myrow[9];
		$_POST['Fax'] = $myrow[10];
		$_POST['Email'] = $myrow[11];
		$_POST['TaxProvince'] = $myrow[12];
		$_POST['Managed'] = $myrow[13];
		$_POST['priority'] = $myrow[14];


		echo "<input type=hidden name=SelectedLocation VALUE=" . $SelectedLocation . '>';
		echo "<input type=hidden name=LocCode VALUE=" . $_POST['LocCode'] . '>';
             
		echo '<table> <tr><td>' . _('Location Code') . ':</td><td>';
		echo $_POST['LocCode'] . '</td></tr>';
	} else {
    echo '<input type="hidden" name="bsid" value="' . $bsid . '" />';  
     //end of if $SelectedLocation only do the else when a new record is being entered
		if (!isset($_POST['LocCode'])) {
			$_POST['LocCode'] = '';
		}
		echo '<table><tr><td>' . _('Location Code') . ':</td><td><input type="Text" name="LocCode" value="' . $_POST['LocCode'] . '" size=5 maxlength=5></td></tr>';
	}
	if (!isset($_POST['LocationName'])) {
		$_POST['LocationName'] = '';
	}
	if (!isset($_POST['Contact'])) {
		$_POST['Contact'] = '';
	}
	if (!isset($_POST['DelAdd1'])) {
		$_POST['DelAdd1'] = ' ';
	}
	if (!isset($_POST['DelAdd2'])) {
		$_POST['DelAdd2'] = '';
	}
	if (!isset($_POST['DelAdd3'])) {
		$_POST['DelAdd3'] = '';
	}
	if (!isset($_POST['DelAdd4'])) {
		$_POST['DelAdd4'] = '';
	}
	if (!isset($_POST['DelAdd5'])) {
		$_POST['DelAdd5'] = '';
	}
	if (!isset($_POST['DelAdd6'])) {
		$_POST['DelAdd6'] = '';
	}
	if (!isset($_POST['Tel'])) {
		$_POST['Tel'] = '';
	}
	if (!isset($_POST['Fax'])) {
		$_POST['Fax'] = '';
	}
	if (!isset($_POST['Email'])) {
		$_POST['Email'] = '';
	}
	if (!isset($_POST['Managed'])) {
		$_POST['Managed'] = 0;
	}
	if (!isset($_POST['priority'])) {
		$_POST['priority'] = '';
	}
	?>

	<tr><td><?php echo _('Location Name') . ':'; ?></td>
	<td><input type="Text" name="LocationName" value="<?php echo $_POST['LocationName']; ?>" size=51 maxlength=50></td></tr>
	<tr><td><?php echo _('Contact for deliveries') . ':'; ?></td>
	<td><input type="Text" name="Contact" value="<?php echo $_POST['Contact']; ?>" size=31 maxlength=30></td></tr>
	<tr><td><?php echo _('Delivery Address 1') . ':'; ?></td>
	<td><input type="Text" name="DelAdd1" value="<?php echo $_POST['DelAdd1']; ?>" size=41 maxlength=40></td></tr>
	<tr><td><?php echo _('Delivery Address 2') . ':'; ?></td>
	<td><input type="Text" name="DelAdd2" value="<?php echo $_POST['DelAdd2']; ?>" size=41 maxlength=40></td></tr>
	<tr><td><?php echo _('Delivery Address 3') . ':'; ?></td>
	<td><input type="Text" name="DelAdd3" value="<?php echo $_POST['DelAdd3']; ?>" size=41 maxlength=40></td></tr>
	<tr><td><?php echo _('Delivery Address 4') . ':'; ?></td>
	<td><input type="Text" name="DelAdd4" value="<?php echo $_POST['DelAdd4']; ?>" size=41 maxlength=40></td></tr>
	<tr><td><?php echo _('Delivery Address 5') . ':'; ?></td>
	<td><input type="Text" name="DelAdd5" value="<?php echo $_POST['DelAdd5']; ?>" size=21 maxlength=20></td></tr>
	<tr><td><?php echo _('Delivery Address 6') . ':'; ?></td>
	<td><input type="Text" name="DelAdd6" value="<?php echo $_POST['DelAdd6']; ?>" size=16 maxlength=15></td></tr>
	<tr><td><?php echo _('Telephone No') . ':'; ?></td>
	<td><input type="Text" name="Tel" value="<?php echo $_POST['Tel']; ?>" size=31 maxlength=30></td></tr>
	<tr><td><?php echo _('Facsimile No') . ':'; ?></td>
	<td><input type="Text" name="Fax" value="<?php echo $_POST['Fax']; ?>" size=31 maxlength=30></td></tr>
	<tr><td><?php echo _('Email') . ':'; ?></td>
	<td><input type="Text" name="Email" value="<?php echo $_POST['Email']; ?>" size=31 maxlength=55></td></tr>
	<tr><td><?php echo _('Priority') . ':'; ?></td> 
    <td><input type="Text" name="priority" value="<?php echo $_POST['priority']; ?>" size=10 maxlength=15></td></tr>
	<td><?php echo _('Tax Province') . ':'; ?></td><td><select name='TaxProvince'>

	<?php
	$TaxProvinceResult = DB_query('SELECT taxprovinceid, taxprovincename FROM taxprovinces',$db);
	while ($myrow=DB_fetch_array($TaxProvinceResult)){
		if ($_POST['TaxProvince']==$myrow['taxprovinceid']){
			echo '<option selected VALUE=' . $myrow['taxprovinceid'] . '>' . $myrow['taxprovincename'];
		} else {
			echo '<option VALUE=' . $myrow['taxprovinceid'] . '>' . $myrow['taxprovincename'];
		}
	}

	?>
	</select></td></tr>
	<tr><td><?php echo _('Enable Warehouse Management') . ':'; ?></td>
	<td><input type='checkbox' name='Managed'<?php if($_POST['Managed'] == 1) echo ' checked';?>></td></tr>
	</table>

	<div class="centre"><input type="Submit" name="submit" value="<?php echo _('Enter Information'); ?>"></div>

	</form>

<?php } //end if record deleted no point displaying form to add record

include('includes/footer.inc');


?>



<script>

  function selection(str1,str2)
  {

       location.href="Customers.php?bussid="+str1+" &loccode="+str2;
      
  }
  
</script> 