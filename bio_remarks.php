<?php
  /* $Id leadsourcetypes.php  2011-07-08 16:39:20Z tcv $ */
$PageSecurity = 80;//
include('includes/session.inc');
$title = _('Remark') . ' / ' . _('Maintenance');
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

echo '<p class="page_title_text"><img src="'.$rootpath.'/css/'.$theme.'/images/maintenance.png" title="' . _('Remarkss')
    . '" alt="" />' . _('Remark Setup') . '</p>';
echo '<div class="page_help_text">' . _('Add/edit/delete Remarks') . '</div><br />';

if (isset($_POST['submit'])) {

    //initialise no input errors assumed initially before we test
    $InputError = 0;

    /* actions to take once the user has clicked the submit button
    ie the page has called itself with some user input */

    //first off validate inputs sensible
    $i=1;
    /*
    if (strlen($_POST['Remarks']) >100) {
        $InputError = 1;
        echo prnMsg(_('The Remarks name description must be 100 characters or less long'),'error');
        $Errors[$i] = 'LeadSourceType';
        $i++;
    }

    if (strlen($_POST['Remarks'])==0) {
        $InputError = 1;
        echo prnMsg(_('The Remarks name description must contain at least one character'),'error');
        $Errors[$i] = 'LeadSourceType';
        $i++;
    }
*/
    $checksql = "SELECT count(*)
             FROM bio_remarks
             WHERE remark = '" . $_POST['Remarks'] . "'";
    $checkresult=DB_query($checksql, $db);
    $checkrow=DB_fetch_row($checkresult);
    if ($checkrow[0]>0 AND !isset($SelectedType)) {
        $InputError = 1;
        echo prnMsg(_('You already have a Remarks called').' '.$_POST['Remarks'],'warn');
        $Errors[$i] = 'EnquiryName';
        $i++;
    }

    if (isset($SelectedType) AND $InputError !=1) {

        $sql = "UPDATE bio_remarks
            SET remark = '" . $_POST['Remarks'] . "' ,SET remark = '" . $_POST['enquiry'] . "'
            WHERE remark_id = '" . $SelectedType . "'";

        $msg = _('The Remarks') . ' ' . $SelectedType . ' ' .  _('has been updated');
    } elseif ( $InputError !=1 ) {

        // First check the type is not being duplicated

        $checkSql = "SELECT count(*)
                 FROM bio_remarks
                 WHERE remark_id = '" . $_POST['TypeID'] . "' ";

        $checkresult = DB_query($checkSql,$db);
        $checkrow = DB_fetch_row($checkresult);

        if ( $checkrow[0] > 0 ) {
            $InputError = 1;
            prnMsg( _('The Remarks ') . $_POST['TypeID'] . _(' already exist.'),'error');
        } else {

            // Add new record on submit

            $sql = "INSERT INTO bio_remarks
                        (enqtypeid,remark)
                    VALUES ('" . $_POST['enquiry'] . "','" . $_POST['Remarks'] . "')";


            $msg = _('Remarks') . ' ' . $_POST['Remarks'] .  ' ' . _('has been created');
            $checkSql = "SELECT count(remark_id)
                 FROM bio_remarks";
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
        unset($_POST['Remarks']);
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

        $sql="DELETE FROM bio_remarks WHERE remark_id='" . $SelectedType . "'";
        $ErrMsg = _('The Type record could not be deleted because');
        $result = DB_query($sql,$db,$ErrMsg);
        prnMsg(_('Remark') . $SelectedType  . ' ' . _('has been deleted') ,'success');

        unset ($SelectedType);
        unset($_GET['delete']);

    /*}*/
}
function display($db)
{
    $sql = "SELECT  a.`remark_id`,b.enquirytype, a.`remark`FROM `bio_remarks` a  ,bio_enquirytypes b where a.`enqtypeid`=b.enqtypeid";
    $result = DB_query($sql,$db);
    echo "<div style='overflow:scroll;height:150px'>";
    echo '<table class="selection" style="width:350px">';
    echo '<tr>
        <th>' . _('Sl No') . '</th>
        <th>' . _('Customer Type') . '</th>
        <th>' . _('Remarks') . '</th>
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
            <td>%s</td>
            <td><a href="%sSelectedType=%s">' . _('Edit') . '</td>
            <td><a href="%sSelectedType=%s&delete=yes" onclick=\'return confirm("' .
                _('Are you sure you wish to delete this Remark?') . '");\'>' . _('Delete') . '</td>
        </tr>',
        $slno,
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



//end of ifs and buts!
if (isset($SelectedType)) {
    echo '<div class="centre"><p><a href="' . $_SERVER['PHP_SELF'] . '">' . _('Show All Types Defined') . '</a></div><p>';
}

echo "<a href='index.php'>Back to Home</a>"  ;
echo "<table border=0 style='width:30%';><tr><td>";  
echo "<fieldset style='width:340px;height:200px'>";
echo "<legend><h3>Remark Master</h3></legend>";
if (! isset($_GET['delete'])) {
    echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '">';
    echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';
    echo '<br /><table class="selection">'; //Main table
    // The user wish to EDIT an existing type
    if ( isset($SelectedType) AND $SelectedType!='' ) {

        $sql = "SELECT *
                FROM bio_remarks
                WHERE remark_id='" . $SelectedType . "'";

        $result = DB_query($sql, $db);
        $myrow = DB_fetch_array($result);

        $_POST['TypeID'] = $myrow['remark_id'];
        $_POST['Remarks']  = $myrow['remark'];

        echo '<input type="hidden" name="SelectedType" value="' . $SelectedType . '">';
        echo '<input type="hidden" name="TypeID" value="' . $_POST['TypeID'] . '">';

        // We dont allow the user to change an existing type code

        //echo '<tr><td>' ._('Remarks ID') . ': </td>
//                <td>' . $_POST['TypeID'] . '</td>
//            </tr>';
    }

    if (!isset($_POST['Remarks'])) {
        $_POST['Remarks']='';
    }
    echo '<tr><td style="width:45%">Customer Type*</td>';
    echo  '<td style="text-align:left;width:55%">';
    echo '<select name="enquiry" id="enquiry" style="width:164px" tabindex=1  onchange="showinstitute(this.value)">';
          
          $sql1="SELECT * FROM bio_enquirytypes where enqtypeid in (1,2,3,8,7,11)"; 
          $result1=DB_query($sql1,$db);
          $f=0;
   
    while($myrow1=DB_fetch_array($result1))
    { 
    if ($myrow1['enqtypeid']==$_POST['enquiry']) 
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
    echo $myrow1['enqtypeid'] . '">'.$myrow1['enquirytype'];
    echo '</option>';
    }  
 /*   echo '<option value=""></option>';
    echo '<option value="1">Domestic</option>';
    echo '<option value="2">Institution</option>';
    echo '<option value="3">LSGD</option>';
    echo '<option value="8">Dealer</option>'; 
    echo '<option value="7">Joint Venture</option>';
     echo '<option value="11">Others</option>';*/
    echo '</select>';    
    echo '</td></tr>';
    echo '<tr><td>' . _('Remark') . ':</td>
            <td><textarea name="Remarks" id="remarks" value="' . $_POST['remarks'] . '">' . $_POST['Remarks'] . '</textarea></td>
        </tr>';

    echo '<tr><td colspan=2><p><div class="centre"><input type=submit name=submit value="' . _('Accept') . '" onclick="if(log_in()==1)return false;"></div>';

       echo '</td></tr></table>'; // close main table

    echo '</form>';

} // end if user wish to delete
echo '</fieldset>'; 
echo "</td></tr>";
echo "<tr><td>";
echo "<fieldset style='width:340px'>";
echo "<legend><h3>Remarks</h3></legend>";

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

<script type="text/javascript">

 document.getElementById('remarks').focus();
 
 $(document).ready(function() {
     $("#error").fadeOut(3000);
    $("#warn").fadeOut(8000);
      $("#success").fadeOut(3000);
        $("#info").fadeOut(3000);
         $(".db_message").fadeOut(3200);
     
 });    
 
 
 
 function log_in(){
//     alert("eeeeeeeeeeeeee");
    var f=0;
var p=0;
if(f==0){f=common_error('remarks','Please enter Remark');  if(f==1){return f; }  }
}
</script>