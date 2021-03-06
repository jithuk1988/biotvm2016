<?php

/* $Id: Areas.php 4572 2011-05-23 10:14:06Z daintree $*/

include('includes/session.inc');

$title = _('Sales Area Maintenance');

include('includes/header.inc');

//echo $_SESSION['bussid']=$_GET['bussid'];
if($_GET['bussid']!=null)
{
$_SESSION['bussid']=$_GET['bussid'];
 $bussid=$_GET['bussid'];   
}
else if($_SESSION['bussid']!=null) 
{
       $bussid=$_SESSION['bussid'];
}
   
if (isset($_GET['SelectedArea'])){
	$SelectedArea = strtoupper($_GET['SelectedArea']);
} elseif (isset($_POST['SelectedArea'])){
	$SelectedArea = strtoupper($_POST['SelectedArea']);
}

if (isset($Errors)) {
	unset($Errors);
}
$Errors = array();

if (isset($_POST['submit'])) {
                           // echo $_SESSION['bussid'];
                           
	//initialise no input errors assumed initially before we test
	$InputError = 0;
	$i=1;

	/* actions to take once the user has clicked the submit button
	ie the page has called itself with some user input */

	//first off validate inputs sensible
	$_POST['AreaCode'] = strtoupper($_POST['AreaCode']);
	$sql = "SELECT count(areacode) from areas WHERE areacode='".$_POST['AreaCode']."'";
	$result = DB_query($sql, $db);
	$myrow = DB_fetch_row($result);
	// mod to handle 3 char area codes
	if (strlen($_POST['AreaCode']) > 3) {
		$InputError = 1;
		prnMsg(_('The area code must be three characters or less long'),'error');
		$Errors[$i] = 'AreaCode';
		$i++;
	} elseif ($myrow[0]>0 and !isset($SelectedArea)){
		$InputError = 1;
		prnMsg(_('The area code entered already exists'),'error');
		$Errors[$i] = 'AreaCode';
		$i++;
	} elseif (strlen($_POST['AreaDescription']) >25) {
		$InputError = 1;
		prnMsg(_('The area description must be twenty five characters or less long'),'error');
		$Errors[$i] = 'AreaDescription';
		$i++;
	} elseif ( trim($_POST['AreaCode']) == '' ) {
		$InputError = 1;
		prnMsg(_('The area code may not be empty'),'error');
		$Errors[$i] = 'AreaCode';
		$i++;
	} elseif ( trim($_POST['AreaDescription']) == '' ) {
		$InputError = 1;
		prnMsg(_('The area description may not be empty'),'error');
		$Errors[$i] = 'AreaDescription';
		$i++;
	}                                                          
        echo $sid=$_POST['bsID'];
                                                        
	if (isset($SelectedArea) AND $InputError !=1) {

		/*SelectedArea could also exist if submit had not been clicked this code would not run in this case cos submit is false of course  see the delete code below*/

		$sql = "UPDATE areas SET
				areadescription='" . $_POST['AreaDescription'] . "'
			WHERE areacode = '$SelectedArea'";

            echo $sqltr = "UPDATE bio_dealerterritory SET
                areadescription='" . $_POST['AreaDescription'] . "'
            WHERE areacode = '$SelectedArea' and buss_id='".$_SESSION['bussid']."'";
            
		$msg = _('Area code') . ' ' . $SelectedArea  . ' ' . _('has been updated');
     /*   $sql1="UPDATE bio_leadtask SET taskcompletedstatus=1,taskcompleteddate='$crdt' WHERE leadid=$leadid AND taskid=$taskid ";      DB_query($sql1,$db); 
            ?> <script>
    window.close();   
      </script> <?php    */

	} elseif ($InputError !=1) {

	/*Selectedarea is null cos no item selected on first time round so must be adding a record must be submitting new entries in the new area form */

		$sql = "INSERT INTO areas (areacode,
						areadescription)
				VALUES (
					'" . $_POST['AreaCode'] . "',
					'" . $_POST['AreaDescription'] . "'
					)";
   /*if(isset($sid) and $sid!=null)
   {                     
    $sqltr="INSERT INTO bio_dealerterritory (buss_id,areacode)
                VALUES ($sid,
                    '" . $_POST['AreaCode'] . "'
                    )";
   }  */
		$SelectedArea =$_POST['AreaCode'];
		$msg = _('New area code') . ' ' . $_POST['AreaCode'] . ' ' . _('has been inserted');
       /* $sql1="UPDATE bio_leadtask SET taskcompletedstatus=1,taskcompleteddate='$crdt' WHERE leadid=$leadid AND taskid=$taskid ";      DB_query($sql1,$db);  
            ?> <script>
    window.close();   
      </script> <?php    */
        
	} else {
		$msg='';
	}

	//run the SQL from either of the above possibilites
	if ($InputError !=1) {
		$ErrMsg = _('The area could not be added or updated because');
		$DbgMsg = _('The SQL that failed was');
		$result = DB_query($sql, $db, $ErrMsg, $DbgMsg);
       // $result = DB_query($sqltr, $db);  
		unset($SelectedArea);
	//	unset($_POST['AreaCode']);
		//unset($_POST['AreaDescription']);
		prnMsg($msg,'success');
	}
       // echo $sid=$_POST['bsID'];
       
   /*123     if(isset($sid) and $sid!=null)
   { 
       if($_POST['AreaCode']!=null && $_POST['AreaDescription']!=null)
       {      
            echo '<meta http-equiv="Refresh" content="0; url=' . $rootpath .'/Locations.php?bsid='.$sid.'&areacode='.$_POST['AreaCode'].'">';  
       } 
        //else
        //{
            //prnMsg("Territory Code and Territory Name should not be blank",'warn');
        //}                 
    //echo '<meta http-equiv="Refresh" content="0; url=' . $rootpath .'/SalesPeople.php?bsid='.$sid.'">';
   }  123*/

} elseif (isset($_GET['delete'])) {
//the link to delete a selected record was clicked instead of the submit button

	$CancelDelete = 0;

// PREVENT DELETES IF DEPENDENT RECORDS IN 'DebtorsMaster'

	$sql= "SELECT COUNT(*) FROM custbranch WHERE custbranch.area='$SelectedArea'";
	$result = DB_query($sql,$db);
	$myrow = DB_fetch_row($result);
	if ($myrow[0]>0) {
		$CancelDelete = 1;
		prnMsg( _('Cannot delete this area because customer branches have been created using this area'),'warn');
		echo '<br>' . _('There are') . ' ' . $myrow[0] . ' ' . _('branches using this area code');

	} else {
		$sql= "SELECT COUNT(*) FROM salesanalysis WHERE salesanalysis.area ='$SelectedArea'";
		$result = DB_query($sql,$db);
		$myrow = DB_fetch_row($result);
		if ($myrow[0]>0) {
			$CancelDelete = 1;
			prnMsg( _('Cannot delete this area because sales analysis records exist that use this area'),'warn');
			echo '<br>' . _('There are') . ' ' . $myrow[0] . ' ' . _('sales analysis records referring this area code');
		}
	}

	if ($CancelDelete==0) {
		$sql="DELETE FROM areas WHERE areacode='" . $SelectedArea . "'";
		$result = DB_query($sql,$db);
		prnMsg(_('Area Code') . ' ' . $SelectedArea . ' ' . _('has been deleted') .' !','success');
	} //end if Delete area
	unset($SelectedArea);
	unset($_GET['delete']);
}

if (!isset($SelectedArea)) {
                        // $_SESSION['bussid']=$_GET['bussid'];
	$sql = "SELECT * FROM areas";
	$result = DB_query($sql,$db);

	echo '<p class="page_title_text"><img src="'.$rootpath.'/css/'.$theme.'/images/maintenance.png" title="' .
		_('Search') . '" alt="" />' . ' ' . $title.'</p><br />';

	echo '<table>
		<tr>';
        if(isset($bussid))
        {
          echo '<th>' . _('select') . '</th>';
        }  
		echo '<th>' . _('Area Code') . '</th>';
		echo '<th>' . _('Area Name') . '</th>';
		echo '</tr>';

	$k=0; //row colour counter

	while ($myrow = DB_fetch_row($result)) {
		if ($k==1){
			echo '<tr class="EvenTableRows">';
			$k=0;
		} else {
			echo '<tr class="OddTableRows">';
			$k++;
		}
        if(isset($bussid))
        {
            echo "<td><input type=radio name=rad id=rad onClick=selection('$myrow[0]','$bussid');></td>"; 
        }
		echo '<td>' . $myrow[0] . '</td>
			<td>' . $myrow[1] . '</td>';
		echo '<td><a href="' . $_SERVER['PHP_SELF'] . '?SelectedArea=' . $myrow[0] . '">' . _('Edit') . '</a></td>';
		echo '<td><a href="' . $_SERVER['PHP_SELF'] . '?SelectedArea=' . $myrow[0] . '&delete=yes" onclick="return confirm(\'' . _('Are you sure you wish to delete this sales area?') . '\');">' . _('Delete') . '</a></td>';
	    echo '<td><a style="cursor:pointer;" id='.$myrow[0].' onclick=dealersarea(this.id)>' . _('View dealers from this Area') . '</a></td>'; 
    //	echo '<td><a href="SelectCustomer.php'. '?Area=' . $myrow[0] . '">' . _('View Customers from this Area') . '</a></td>';
	}
	//END WHILE LIST LOOP
	echo '</table>';
}

//end of ifs and buts!

if (isset($SelectedArea)) {
	echo '<div class="centre"><a href="' . $_SERVER['PHP_SELF'] . '?' . SID . '">' . _('Review Areas Defined') . '</a></div>';
}


if (!isset($_GET['delete'])) {
 //   $_SESSION['bussid']=$_GET['bussid'];
	echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '?' . SID . '"><br>';
  
    echo '<input type="hidden" name="bsID" value="' . $bussid . '" />';       
	echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

	if (isset($SelectedArea)) {
		//editing an existing area

		$sql = "SELECT areacode,
				areadescription
			FROM areas
			WHERE areacode='$SelectedArea'";

		$result = DB_query($sql, $db);
		$myrow = DB_fetch_array($result);

		$_POST['AreaCode'] = $myrow['areacode'];
		$_POST['AreaDescription']  = $myrow['areadescription'];

		echo '<input type=hidden name=SelectedArea VALUE=' . $SelectedArea . '>';
		echo '<input type=hidden name=AreaCode VALUE=' .$_POST['AreaCode'] . '>';
//        echo '<input type=hidden name=busid VALUE=' .$_GET['bussid'] . '>';
		echo '<table class=selection><tr><td>' . _('Area Code') . ':</td><td>' . $_POST['AreaCode'] . '</td></tr>';

	} else {
		if (!isset($_POST['AreaCode'])) {
			$_POST['AreaCode'] = '';
		}
		if (!isset($_POST['AreaDescription'])) {
			$_POST['AreaDescription'] = '';
		}
		echo '<table class=selection>
			<tr>
				<td>' . _('Area Code') . ':</td>
				<td><input tabindex="1" ' . (in_array('AreaCode',$Errors) ?  'class="inputerror"' : '' ) .'   type="Text" name="AreaCode" value="' . $_POST['AreaCode'] . '" size=3 maxlength=3></td>
			</tr>';
	}

	echo '<tr><td>' . _('Area Name') . ':</td>
		<td><input tabindex="2" ' . (in_array('AreaDescription',$Errors) ?  'class="inputerror"' : '' ) .'  type="text" name="AreaDescription" value="' . $_POST['AreaDescription'] .'" size=26 maxlength=25></td>
		</tr>';

	echo '<tr><td colspan=2><div class="centre"><input tabindex="3" type="Submit" name="submit" value=' . _('EnterInformation') .'></div></td></tr>';
	echo '</table>';
    echo '</form>';

 } //end if record deleted no point displaying form to add record

include('includes/footer.inc');
?>
<script>

  function selection(str1,str2)
  {
      //alert(str1);
       //alert(str2);   
       location.href="Locations.php?areacode="+str1+" &bsid="+str2;
      
  }
  
  function dealersarea(str)
  {
   controlWindow=window.open("bio_dealersArea.php?Area=" + str,"DealesAreaView","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,width=700,height=500");   
  }
</script>