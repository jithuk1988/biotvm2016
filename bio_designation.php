<?php
/* $Id offices.php  2011-07-08 16:39:20Z tcv $ */
$PageSecurity = 15;
include('includes/session.inc');
$title = _('Designation ') . ' - ' . _('Master');
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

echo '<p class="page_title_text"><img src="'.$rootpath.'/css/'.$theme.'/images/maintenance.png" title="' . _('Offices')
	. '" alt="" />' . _('Designation Master') . '</p>';
//echo '<div class="page_help_text">' . _('Add / edit / delete Offices') . '</div><br />';

if (isset($SelectedType)) {

	echo '<div class="centre"><p><a href="' . $_SERVER['PHP_SELF'] . '">' . _('Show All Offices Defined') . '</a></div><p>';
}
 // end if user wish to delete

if (isset($_POST['submit'])) {

	//initialise no input errors assumed initially before we test
	$InputError = 0;

	/* actions to take once the user has clicked the submit button
	ie the page has called itself with some user input */

	//first off validate inputs sensible
	$i=1;
	if (strlen($_POST['desgname']) >100) {
		$InputError = 1;
		echo prnMsg(_('The Office name description must be 100 characters or less long'),'error');
		$Errors[$i] = 'Designation';
		$i++;
	}

	if (strlen($_POST['desgname'])==0) {
		$InputError = 1;
		echo prnMsg(_('The Office name description must contain at least one character'),'error');
		$Errors[$i] = 'Designation';
		$i++;
	}

//	$checksql = "SELECT count(*)
//		     FROM bio_designation
//		     WHERE designation = '" . $_POST['desgname'] . "'";
//	$checkresult=DB_query($checksql, $db);
//	$checkrow=DB_fetch_row($checkresult);
//	if ($checkrow[0]>0) {
//		$InputError = 1;
//		echo prnMsg(_('You already have a Designation called').' '.$_POST['desgname'],'error');
//		$Errors[$i] = 'Designation';
//		$i++;
//	}

	if (isset($SelectedType)) {

		$sql = "UPDATE bio_designation
			SET designation = '" . $_POST['desgname'] . "',desgcode = '" . $_POST['desgcode'] . "', level = '" . $_POST['level'] . "', role =  '" . $_POST['role'] . "'
			WHERE desgid = '" . $SelectedType . "'";

		$msg = _('The Designation') . ' ' . $SelectedType . ' ' .  _('has been updated');
	} elseif ( $InputError !=1 ) {

		// First check the type is not being duplicated

		$checkSql = "SELECT count(*)
			     FROM bio_designation
			     WHERE desgid = '" . $_POST['desgid'] . "'";

		$checkresult = DB_query($checkSql,$db);
		$checkrow = DB_fetch_row($checkresult);

		if ( $checkrow[0] > 0 ) {
			$InputError = 1;
			prnMsg( _('The Office ') . $_POST['desgid'] . _(' already exist.'),'error');
		} else {

			// Add new record on submit

			$sql = "INSERT INTO bio_designation
						(desgcode,designation,role,level)
					VALUES ('" . $_POST['desgname'] . "','". $_POST['desgcode'] ."','".$_POST['role']."','".$_POST['level']."')";


			$msg = _('Office') . ' ' . $_POST['desgname'] .  ' ' . _('has been created');
			$checkSql = "SELECT count(desgid)
			     FROM bio_designation";
			$result = DB_query($checkSql, $db);
			$row = DB_fetch_row($result);

		}
	}

	if ( $InputError !=1) {
	//run the SQL from either of the above possibilites
		$result = DB_query($sql,$db);



		prnMsg($msg,'success');

		unset($SelectedType);
		unset($_POST['desgid']);
		unset($_POST['desgname']);
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

		$sql="DELETE FROM bio_designation WHERE desgid='" . $SelectedType . "'";
		$ErrMsg = _('The Designation record could not be deleted because');
		$result = DB_query($sql,$db,$ErrMsg);
		prnMsg(_('Designation ') . $SelectedType  . ' ' . _('has been deleted') ,'success');

		unset ($SelectedType);
		unset($_GET['delete']);

	/*}*/
   }
   
   
    echo "<a href='index.php'>Back to Home</a>"  ;
    echo "<table border=0 style='width:50%';><tr><td>";  
    echo "<fieldset style='width:60%;height:230px'>";
    echo "<legend><h3>Designation Master</h3></legend>";

if (! isset($_GET['delete'])) {

    echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '">';
    echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';
   
    echo '<table>'; 
    // The user wish to EDIT an existing type
    if ( isset($SelectedType) AND $SelectedType!='' ) {
                $sql = "SELECT *
                FROM bio_designation
                WHERE desgid='" . $SelectedType . "'";

        $result = DB_query($sql, $db);
        $myrow = DB_fetch_array($result);

        $_POST['desgid'] = $myrow['desgid'];
        $_POST['desgname']  = $myrow['designation'];
        $_POST['desgcode'] = $myrow['desgcode']; 
        $_POST['level']  = $myrow['level'];
        $_POST['role']  = $myrow['role'];
        
        echo '<input type="hidden" name="SelectedType" value="' . $SelectedType . '">';
        echo '<input type="hidden" name="OfficeID" value="' . $_POST['OfficeID'] . '">';

        // We dont allow the user to change an existing type code
           
        echo '<tr><td>' ._('Designation id') . ': </td>
                <td>' . $_POST['desgid'] . '</td>
            </tr>';
    }

    if (!isset($_POST['desgname'])) {
        $_POST['desgname']='';
    } 
// echo '<tr><td>' . _('Department Name') . ':</td>
//            <td><select name=dept>';
//            $sql="select * from bio_dept";
//             $result=DB_query($sql,$db);
//             echo'<option value=0></option>';
//             while($row=DB_fetch_array($result))
//             {       
//                 if ($row['deptid']==$_POST['deptname'])
//                 {
//                 echo '<option selected value="';
//                 } else {

//                 echo '<option value="';
//                 }
//                 echo $row['id'] . '">'.$row['deptname'];
//                 echo '</option>';
//             }
//            echo'</select></td>';
//        echo'</tr>';
        
        echo '<tr><td>' . _('Designation Name') . ':</td>
            <td><input type="text" name="desgname" value="'. $_POST['desgname'] .'"></td>
        </tr>';
        
        echo '<tr><td>' . _('Designation Code') . ':</td>
            <td><input type="text" name="desgcode" value="'. $_POST['desgcode'] .'"></td>
        </tr>';
         
        echo '<tr><td>' . _('Level') . ':</td>
            <td><select name=level style="width:100%">';
            echo'<option value=0></option>';
            for($i=5;$i<100;$i=$i+5)
            {
            echo'<option>'.$i.'</option>';
            }
            echo'</select></td>';
         echo'</tr>'; 
         
          echo '<tr><td>' . _('Security Role') . ':</td>'; 
          echo '<td><select name="role">';
            $sql="select * from securityroles";
             $result=DB_query($sql,$db);
             echo'<option value=0></option>';
             while($row=DB_fetch_array($result))
             {       
                 if ($row['secroleid']==$_POST['role'])
                 {
                 echo '<option selected value="';
                 } else {

                 echo '<option value="';
                 }
                 echo $row['secroleid'] . '">'.$row['secrolename'];
                 echo '</option>';
             }
            echo'</select></td>';
        echo'</tr>';
        echo'</tr>';   
      
       echo '</table></td></tr>'; 
       echo'<tr><td colspan=2><p><div class="centre"><input type=submit name=submit value="' . _('Accept') . '"></div></td></tr>'; // close main table  
       echo '</fieldset>';

    echo '</form>';
        echo '<br />';
}

echo '</fieldset>'; 
echo "<tr><td colspan=2>";
    echo "<fieldset style='width:90%'>";
   echo "<legend><h3>Designation Master Created</h3></legend>";

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
//end of ifs and buts!

function display($db)
{
        $sql = "SELECT desgid, desgcode, designation, level
         FROM bio_designation";
    $result = DB_query($sql,$db);
echo "<div style='overflow:scroll;height:150px'>";
    echo '<table class="selection">';
    echo '<tr>
        <th>' . _('Designation ID') . '</th>
        <th>' . _('Designation Code') . '</th> 
        <th>' . _('Designation') . '</th>
        <th>' . _('Level') . '</th>
      </tr>';

$k=0; //row colour counter

while ($myrow = DB_fetch_array($result)) {
    
//       $did=$myrow['dept_id'];
//       $above_desg=$myrow['above_desg'];
//       $below_desg=$myrow['below_desg'];      
//       
//       $sql1="select * from bio_dept where deptid=$did"; 
//       $result1=DB_query($sql1,$db);
//       $row1=DB_fetch_array($result1);
//       
//   
//    if($above_desg==1 || $above_desg==2 || $above_desg==3 || $above_desg==4 || $above_desg==5)
//    {
//        $sql2="select desg_code from bio_designation where designationid=".$above_desg;
//        $result2=DB_query($sql2,$db);
//        $row2=DB_fetch_array($result2);
//    }

//    $desgcode1=$row2['desg_code'];
//    
//        if($below_desg==1 || $below_desg==2 || $below_desg==3 || $below_desg==4 || $below_desg==5 || $below_desg==6)
//    {
//        $sql3="select desg_code from bio_designation where designationid=".$below_desg;
//        $result3=DB_query($sql3,$db);
//        $row3=DB_fetch_array($result3);
//    }

//    $desgcode2=$row3['desg_code'];
    
    if ($k==1){
        echo '<tr class="EvenTableRows">';
        $k=0;
    } else {
        echo '<tr class="OddTableRows">';
        $k=1;
    }

    printf('<td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td><a href="%sSelectedType=%s">' . _('Edit') . '</td>
            <td><a href="%sSelectedType=%s&delete=yes" onclick=\'return confirm("' .
                _('Are you sure you wish to delete this Designation?') . '");\'>' . _('Delete') . '</td>
        </tr>',
        $myrow['desgid'],
        $myrow['desgcode'],
        $myrow['designation'],
//        $desgcode1,
//        $desgcode2,
//        $myrow['below_desg'],
        $myrow['level'],    
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