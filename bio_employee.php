<?php
/* $Id offices.php  2011-07-08 16:39:20Z tcv $ */
$PageSecurity = 20;
include('includes/session.inc');
$title = _('Employee ') . ' - ' . _('Master');
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

echo '<p class="page_title_text"><img src="'.$rootpath.'/css/'.$theme.'/images/maintenance.png" title="' . _('Employee')
	. '" alt="" />' . _('Employee Master') . '</p>';
//echo '<div class="page_help_text">' . _('Add / edit / delete Employees') . '</div><br />';

if (isset($SelectedType)) {

	echo '<div class="centre"><p><a href="' . $_SERVER['PHP_SELF'] . '">' . _('Show All Employees Defined') . '</a></div><p>';
}
 // end if user wish to delete

if (isset($_POST['submit'])) {

	//initialise no input errors assumed initially before we test
	$InputError = 0;

	/* actions to take once the user has clicked the submit button
	ie the page has called itself with some user input */

	//first off validate inputs sensible
	$i=1;
	if (strlen($_POST['empName']) >100) {
		$InputError = 1;
		echo prnMsg(_('The Employee name description must be 100 characters or less long'),'error');
		$Errors[$i] = 'Employee';
		$i++;
	}

	if (strlen($_POST['empName'])==0) {
		$InputError = 1;
		echo prnMsg(_('The Employee name description must contain at least one character'),'error');
		$Errors[$i] = 'Employee';
		$i++;
	}

//	$checksql = "SELECT count(*)
//		     FROM bio_emp
//		     WHERE empname = '" . $_POST['empName'] . "'";
//	$checkresult=DB_query($checksql, $db);
//	$checkrow=DB_fetch_row($checkresult);
//	if ($checkrow[0]>0) {
//		$InputError = 1;
//		echo prnMsg(_('You already have an Employee called').' '.$_POST['empName'],'error');
//		$Errors[$i] = 'Employee';
//		$i++;
//	}

	if (isset($SelectedType)) {

		$sql = "UPDATE bio_emp
			SET empname = '" . $_POST['empName'] . "'
			WHERE empid = '" . $SelectedType . "'";

		$msg = _('The Employee') . ' ' . $SelectedType . ' ' .  _('has been updated');
	} elseif ( $InputError !=1 ) {

		// First check the type is not being duplicated

		$checkSql = "SELECT count(*)
			     FROM bio_emp
			     WHERE empid = '" . $_POST['empid'] . "'";

		$checkresult = DB_query($checkSql,$db);
		$checkrow = DB_fetch_row($checkresult);

		if ( $checkrow[0] > 0 ) {
			$InputError = 1;
			prnMsg( _('The Employee ') . $_POST['empid'] . _(' already exist.'),'error');
		} else {

			// Add new record on submit
             $jdate=FormatDateForSQL($_POST['df1']);
             
             if($_POST['Repoff']==''){
                $_POST['Repoff']=$_POST['Assistant_to']; 
             }
             
			$sql = "INSERT INTO bio_emp
						(empname, designationid, offid, deptid, joineddate, creditpoints, c_number, email, reportto,assistant_to)
					VALUES ('" . $_POST['empName'] . "','". $_POST['desg'] ."','".$_POST['off']."','".$_POST['dept']."','".$jdate."','".$_POST['point']."','".$_POST['cNumber']."','" . $_POST['email'] . "','" . $_POST['Repoff'] . "','" . $_POST['Assistant_to'] . "' )";


			$msg = _('Employee') . ' ' . $_POST['empname'] .  ' ' . _('has been created');
			$checkSql = "SELECT count(empid)
			     FROM bio_emp";
			$result = DB_query($checkSql, $db);
			$row = DB_fetch_row($result);

		}
	}

	if ( $InputError !=1) {
	//run the SQL from either of the above possibilites
		$result = DB_query($sql,$db);



		prnMsg($msg,'success');

		unset($SelectedType);
		unset($_POST['empid']);
		unset($_POST['empname']);
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

		$sql="DELETE FROM bio_emp WHERE empid='" . $SelectedType . "'";
		$ErrMsg = _('The Employee record could not be deleted because');
		$result = DB_query($sql,$db,$ErrMsg);
		prnMsg(_('Employee ') . $SelectedType  . ' ' . _('has been deleted') ,'success');

		unset ($SelectedType);
		unset($_GET['delete']);

	/*}*/
}
echo "<a href='index.php'>Back to Home</a>"  ;
echo "<table border=0 style='width:60%';><tr><td>";  
  echo "<fieldset style='width:90%;height:215px'>";
   echo "<legend><h3>Employee Master</h3></legend>";

if (! isset($_GET['delete'])) {

    echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '">';
    echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';
   
    echo '<table>'; //Main table
    // The user wish to EDIT an existing type
    if ( isset($SelectedType) AND $SelectedType!='' ) {
                $sql = "SELECT *
                FROM bio_emp
                WHERE empid='" . $SelectedType . "'";

        $result = DB_query($sql, $db);
        $myrow = DB_fetch_array($result);

        $_POST['empid'] = $myrow['empid'];
        $_POST['empName']  = $myrow['empname'];
        $_POST['dept']  = $myrow['deptid'];
        $_POST['point']  = $myrow['creditpoints'];
        $_POST['df1']  = $myrow['joineddate'];
        $_POST['desg']  = $myrow['designationid'];
        $_POST['off']  = $myrow['offid'];
//        $_POST['add']  = $myrow['address']; 
//        $_POST['cPerson']  = $myrow['c_person'];
        $_POST['cNumber']  = $myrow['c_number'];  
        $_POST['email']  = $myrow['email']; 
        $_POST['Repoff']  = $row4['empid']; 
        

        echo '<input type="hidden" name="SelectedType" value="' . $SelectedType . '">';
        echo '<input type="hidden" name="OfficeID" value="' . $_POST['OfficeID'] . '">';

        // We dont allow the user to change an existing type code
           
    echo '<tr><td>' ._('Employee ID') . ': </td>
                <td>' . $_POST['empid'] . '</td>
            </tr>';
    }

    if (!isset($_POST['offName'])) {
        $_POST['offName']='';
    } 
    
        echo '<tr><td>' . _('Employee Name') . ':</td>
            <td><input type="text" name="empName" style="width:98%" value="' . $_POST['empName'] . '"></td>
        </tr>';  
        
        echo '<tr><td>' . _('Office') . ':</td>';
         echo '<td><select name=off id=office style="width:100%">';
            $sql="select * from bio_office";
             $result=DB_query($sql,$db);
             echo'<option value=0></option>';
             while($row=DB_fetch_array($result))
             {       
                 if ($row['id']==$_POST['off'])
                 {
                 echo '<option selected value="';
                 } else {

                 echo '<option value="';
                 }
                 echo $row['id'] . '">'.$row['office'];
                 echo '</option>';
             }
            echo'</select></td>';
         echo'</tr>';
         
         echo '<tr><td>' . _('Designation') . ':</td>';
         echo '<td><select name=desg id=desg onchange="desgn(this.value)">';
            $sql="select * from bio_designation";
             $result=DB_query($sql,$db);
             echo'<option value=0></option>';
             while($row=DB_fetch_array($result))
             {       
                 if ($row['desgid']==$_POST['desg'])
                 {
                 echo '<option selected value="';
                 } else {

                 echo '<option value="';
                 }
                 echo $row['desgid'] . '">'.$row['designation'];
                 echo '</option>';
             }
            echo'</select></td>';
        echo '</tr>';
        
     
         echo '<tr id=repofficer>';
        
         echo'</tr>';
     
     
     
         
        // echo '<tr id=repofficer><td>' . _('Reporting Officer') . ':</td>';
//         echo '<td ><select name=Repoff style="width:100%">';
//             $result=DB_query($sql,$db);
//             echo'<option value=0></option>';
//             while($row=DB_fetch_array($result))
//             {       
//                 if ($row['id']==$_POST['off'])
//                 {
//                 echo '<option selected value="';
//                 } else {

//                 echo '<option value="';
//                 }
//                 echo $row['id'] . '">'.$row['office'];
//                 echo '</option>';
//             }
//            echo'</select></td>';
//         echo'</tr>';
        
        echo '<tr><td>' . _('Department') . ':</td>';
         echo '<td><select name=dept style="width:100%">';
            $sql="select * from bio_dept";
             $result=DB_query($sql,$db);
             echo'<option value=0></option>';
             while($row=DB_fetch_array($result))
             {       
                 if ($row['deptid']==$_POST['dept'])
                 {
                 echo '<option selected value="';
                 } else {

                 echo '<option value="';
                 }
                 echo $row['deptid'] . '">'.$row['deptname'];
                 echo '</option>';
             }
            echo'</select></td>';
        echo'</tr>';       
        
               echo '<tr><td>' . _('Joined Date') . ':</td>';
               echo '<td><input type="text" style="width:66%" id="df1" class=date alt='.$_SESSION['DefaultDateFormat'].' value="'. $_POST['df1'] .'" name="df1" onChange="return isDate(this, this.value, '."'".$_SESSION['DefaultDateFormat']."'".')'.'"'.'></td>';
               echo '</tr>';
               
               echo '<tr><td>' . _('Credit Points') . ':</td>
                     <td><input type="text" name="point" value="' . $_POST['point'] . '"></td>
                     </tr>';
               
       echo '</table></fieldset></td><td>'; 
       
         echo "<fieldset style='width:90%;height:215px'>";
         echo "<legend><h3>Employee Details</h3></legend>";
       
       echo '<table>';
//       echo '<tr><td>' . _('Address') . ':</td>
//            <td><textarea rows=3 cols=25 name=add value="'. $_POST['add'] .'"></textarea></td>
//            </tr>';
//              
//       echo '<tr><td>' . _('Contact Person') . ':</td>
//                <td><input type="text" name="cPerson" value="' . $_POST['cPerson'] . '"></td>
//                </tr>';
                
          echo '<tr><td>' . _('Contact No') . ':</td>
            <td><input type="text" name="cNumber" value="' . $_POST['cNumber'] . '"></td>
        </tr>'; 
         echo '<tr><td>' . _('E-mail') . ':</td>
            <td><input type="text" name="email" value="' . $_POST['email'] . '"></td>
        </tr>'; 
//         echo '<tr><td>' . _('Fax') . ':</td>
//            <td><input type="text" name="fax" value="' . $_POST['fax'] . '"></td>
//        </tr>'; 

     
        echo'</table></td></tr><tr><td colspan=2><p><div class="centre"><input type=submit name=submit value="' . _('Accept') . '"></div></td></tr>'; // close main table

    echo '</form>';
        echo '<br />';
}

echo '</fieldset>'; 
echo "<tr><td colspan=2>";
    echo "<fieldset style='width:95%'>";
   echo "<legend><h3>Employee Master Created</h3></legend>";

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
    $sql = "SELECT bio_emp.empid AS empid,
                bio_emp.empname AS empname,
                bio_emp.designationid,
                bio_designation.designation AS designation,
                bio_emp.deptid,
                bio_dept.deptname AS deptname,
                bio_emp.offid,
                bio_office.office AS office,
                bio_emp.c_number AS contnum, 
                bio_emp.joineddate AS jdate,
                bio_emp.creditpoints AS points
            FROM bio_emp,bio_designation,bio_office,bio_dept
            where bio_emp.designationid=bio_designation.desgid
            AND bio_emp.offid=bio_office.id
            AND bio_emp.deptid=bio_dept.deptid 
            ORDER BY bio_emp.empid ASC
            ";

    $result = DB_query($sql,$db);
echo "<div style='overflow:scroll;height:150px'>";
    echo '<table class="selection">';
    echo '<tr>
        <th>' . _('EmpID') . '</th>
        <th>' . _('Name') . '</th>
        <th>' . _('Designation') . '</th>
        <th>' . _('Department') . '</th> 
        <th>' . _('Office') . '</th>
        <th>' . _('Phone') . '</th>
        <th>' . _('Joined Date') . '</th>
        <th>' . _('Points') . '</th>
        </tr>';

$k=0; //row colour counter

while ($myrow = DB_fetch_array($result)) {
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
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td><a href="%sSelectedType=%s">' . _('Edit') . '</td>
            <td><a href="%sSelectedType=%s&delete=yes" onclick=\'return confirm("' .
                _('Are you sure you wish to delete this Employee?') . '");\'>' . _('Delete') . '</td>
        </tr>',
        $myrow['empid'],
        $myrow['empname'],
        $myrow['designation'],
        $myrow['deptname'],
        $myrow['office'],
        $myrow['contnum'],
        ConvertSQLDate($myrow['jdate']),
        $myrow['points'],
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


<script type="text/javascript">  

function desgn(str1){
    
//    alert(str1);
if(str1==1){
    alert("No reporting officer for MD");
    document.getElementById('desg').focus();
    return false;
}
    var office=document.getElementById('office').value;
//    alert(office);
if (str1=="")
  {
  document.getElementById("repofficer").innerHTML="";
  return;
  }
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
    document.getElementById("repofficer").innerHTML=xmlhttp.responseText;

    }
  } 
xmlhttp.open("GET","bio_reportingofficer.php?desgid=" + str1 + "&offid=" + office, true);
xmlhttp.send();    
}

</script>