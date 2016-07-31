<?php
$PageSecurity = 80;   

include('includes/session.inc');

$title = _('Location Master');

include('includes/header.inc');

$pagetype=3;
include('includes/sidemenu1.php');  
include('includes/formload.inc');
?>

<script type="text/javascript"> 
$(document).ready(function(){  
    
  //$("#error").fadeOut(3000);
//    $("#warn").fadeOut(3000);
//      $("#success").fadeOut(3000);
//        $("#info").fadeOut(3000);
//         $(".db_message").fadeOut(3200); 
 
  $("#selectiondetails").hide(); 
        
$(".button_details").click(function(){
  $("#selectiondetails").show();
  }); 
        
});
 function showdetails(str1,str2,str3,str4,str5,str6,str7,str8)
{
//alert(str1);
if (str1=="")
  {
  document.getElementById("panel_locmas").innerHTML="";
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

    document.getElementById("panel_locmas").innerHTML=xmlhttp.responseText;
    document.forms[0].taskname.focus(); 
    }
  }

xmlhttp.open("GET","Locationmaster-panels.php?p=" + str1 + "&q=" + str2 + "&r=" + str3 + "&s=" + str4 + "&t=" + str5 + "&u=" + str6 + "&v=" + str7 + "&w=" + str8,true);     
xmlhttp.send(); 
}
</script>
<?php


 //-------------returns type's code


echo '<p class="page_title_text">' . ' ' . _('Locations') . '';

if (isset($_GET['SelectedLocation'])){
    $SelectedLocation = $_GET['SelectedLocation'];
} elseif (isset($_POST['SelectedLocation'])){
    $SelectedLocation = $_POST['SelectedLocation'];
}
if(isset($_POST['clear']))      {

 clear();  

} //--------------------clear


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

        /*SelectedLocation is null cos no item selected on first time round so must be adding a    record must be submitting new entries in the new Location form */

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

        prnMsg( _('The new location record has been added'),'success');
        

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

//        echo '<br>........ ' . _('and new stock locations inserted for all existing stock items for the new location');

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


}    //--------------------submit
if (isset($_POST['delete']))    {
     
     $SelectedLocation = $_POST['LocCode'];                                                                 
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
//            echo '<br>' . _('There are') . ' ' . $myrow[0] . ' ' . _('stock movements with this Location code');

        } else {
            $sql= "SELECT COUNT(*) FROM locstock WHERE locstock.loccode='$SelectedLocation' AND locstock.quantity !=0";
            $result = DB_query($sql,$db);
            $myrow = DB_fetch_row($result);
            if ($myrow[0]>0) {
                $CancelDelete = 1;
                prnMsg(_('Cannot delete this location because location stock records exist that use this location and have a quantity on hand not equal to 0'),'warn');
//                echo '<br> ' . _('There are') . ' ' . $myrow[0] . ' ' . _('stock items with stock on hand at this location code');
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
    
 clear();   
    unset($SelectedLocation);
} //--------------------delete

    echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . '?' . SID . "'>";  

$fieldid=get_fieldid('loccode','locations',$db);
    
        if (!isset($_POST['LocCode'])) {
            $_POST['LocCode'] = '';
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
    }    //--------------------------------setting null values for form fields
    
                                                                                                      
    echo '<div class = "panels" id= "panel_locmas">'; 
    panels($fieldid,$db);
    function panels($fieldid,$db)    {
    ?>
    
    <table width=100%><tr><td width="50%" valign="top"> 
    <fieldset id='left_panel_1'>     
    <legend><h3>General Details</h3>
    </legend> 
    <table><tr>
    <td><?php echo _('Location Code') . ':'; ?></td>
    <td><input type="hidden" name="LocCode" value="<?php echo $fieldid; ?>"><?php echo $fieldid; ?></td></tr>
    <tr><td><?php echo _('Location Name') . ':'; ?></td>
    <td><input type="Text" name="LocationName" id="locationname" value="<?php echo $_POST['LocationName']; ?>" size=25 maxlength=50></td></tr>
    <tr><td><?php echo _('Contact for deliveries') . ':'; ?></td>
    <td><input type="Text" name="Contact" value="<?php echo $_POST['Contact']; ?>" size=25 maxlength=30></td></tr>
    <tr><td><?php echo _('Delivery Address 1') . ':'; ?></td>
    <td><input type="Text" name="DelAdd1" value="<?php echo $_POST['DelAdd1']; ?>" size=25 maxlength=40></td></tr>
    <tr><td><?php echo _('Delivery Address 2') . ':'; ?></td>
    <td><input type="Text" name="DelAdd2" value="<?php echo $_POST['DelAdd2']; ?>" size=25 maxlength=40></td></tr>
    <tr><td><?php echo _('Delivery Address 3') . ':'; ?></td>
    <td><input type="Text" name="DelAdd3" value="<?php echo $_POST['DelAdd3']; ?>" size=25 maxlength=40></td></tr>
    <tr><td><?php echo _('Delivery Address 4') . ':'; ?></td>
    <td><input type="Text" name="DelAdd4" value="<?php echo $_POST['DelAdd4']; ?>" size=25 maxlength=40></td></tr>
    </table>
    </fieldset>
    </td>
    <td valign="top">
    <fieldset id='right_panel_1'>     
    <legend><h3>General Details</h3>
    </legend>
    <table>
    <tr><td><?php echo _('Delivery Address 5') . ':'; ?></td>
    <td><input type="Text" name="DelAdd5" value="<?php echo $_POST['DelAdd5']; ?>" size=25 maxlength=20></td></tr>
    <tr><td><?php echo _('Delivery Address 6') . ':'; ?></td>
    <td><input type="Text" name="DelAdd6" value="<?php echo $_POST['DelAdd6']; ?>" size=25 maxlength=15></td></tr>
    <tr><td><?php echo _('Telephone No') . ':'; ?></td>
    <td><input type="Text" name="Tel" value="<?php echo $_POST['Tel']; ?>" size=25 maxlength=30></td></tr>
    <tr><td><?php echo _('Facsimile No') . ':'; ?></td>
    <td><input type="Text" name="Fax" value="<?php echo $_POST['Fax']; ?>" size=25 maxlength=30></td></tr>
    <tr><td><?php echo _('Email') . ':'; ?></td>
    <td><input type="Text" name="Email" value="<?php echo $_POST['Email']; ?>" size=25 maxlength=55></td></tr>
    <tr><td><?php echo _('Priority') . ':'; ?></td> 
    <td><input type="Text" name="priority" id="prio" value="<?php echo $_POST['priority']; ?>" size=25 maxlength=15></td></tr>
    <td><?php echo _('Tax Province') . ':'; ?></td><td><select name='TaxProvince' style="width:180px;">

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
<!--    <tr><td><?php echo _('Enable Warehouse Management') . ':'; ?></td>
    <td><input type='checkbox' name='Managed'<?php if($_POST['Managed'] == 1) echo ' checked';?>></td></tr>-->
    </table>
    </fieldset>
    </td></tr></table>
    
    <?php
    }
    ?>

    </div>
    <div class=buttons>
    <table ><tr>
    <td><input type='Submit' name='submit' VALUE="Save" <?php echo "onclick='if(log_in()==1)return false;'"?>></td>
    <td><input type='Submit' name='clear' VALUE="Clear"></td>
    <td><input type='Submit' name='delete' VALUE="Delete"></td>
    <td></td>
    </tr></table>  
    </div>

    </form>
<?php

  echo"<div class='Datagrid'>";
  datagrid($db);
  echo"</div>";
  
  function datagrid($db)    {
      
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
//        prnMsg (_('There are no locations that match up with a tax province record to display. Check that tax provinces are set up for all dispatch locations'),'error');
    }

    echo '<table width=100% class=sortable>';  
    echo '<thead><tr><th>' . _('Sl no') . '</th>
            <th>' . _('Location Name') . '</th>
            <th>' . _('Tax Province') . '</th>
            <th>' . _('Priority') . '</th>
        </tr></thead>';
    echo"<tbody>";
$slno=0;
$k=0; //row colour counter
while ($myrow = DB_fetch_array($result)) {
    if ($k==1){
        echo '<tr class="EvenTableRows" id="'.$myrow['loccode'].'" onclick=showdetails(this.id)>';
        $k=0;
    } else {
        echo '<tr class="OddTableRows" id="'.$myrow['loccode'].'" onclick=showdetails(this.id)>';
        $k=1;
    }
    
    $slno++;
   
    if($myrow['managed'] == 1) {
        $myrow['managed'] = _('Yes');
    }  else {
        $myrow['managed'] = _('No');
    }

    printf("<td>%s</td>
        <td>%s</td>
        <td>%s</td>
        <td>%s</td>
        </tr>",
        $slno,
        $myrow['locationname'],
        $myrow['description'],
        $myrow[4]);

    }
    //END WHILE LIST LOOP
    echo'<tfoot><tr>';
    echo'<td colspan=4>Number of records:'.$slno.'</td>';
    echo'</tr></tfoot>';
    echo'</tbody>';
    echo '</table>';
  }
  function clear ()   {
 
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
    
} 
echo '<br>';
  include('includes/smenufooter.inc'); 
?>
<script language="javascript">
 document.getElementById('custid').focus(); 
  $(document).ready(function() {
//    $("#notice").fadeOut(3000);

 $('#sourcetype').change(function() {
  $('#dinhide').hide();
}); 
   }); 
//    $('#leads').click(function() { 

//    f=validateemail('email','Please Enter valid email');  if(f==1) { return false; }   
//});
function caps1()
{
//   alert("sss");
UCWords('custid','Name should be begin with capital letter');
}
 function log_in()
{  //  alert("sss"); alert(mail);
var f=0;       


f=common_error('locationname','Please Enter Location name');  if(f==1) { return f;} 
             

if(f==0){f=common_error('prio','Please fill the priority fields');  if(f==1) { return f; }}
if(f==0){f=common_error('contactname','Please Enter contact name');  if(f==1) { return f; }}                  
if(f==0){f=common_error('suppliername','Please Enter company name');  if(f==1) { return f; }}     
if(f==0){f=common_error('outputtype','Please Select an Output Type');  if(f==1) { return f; }} 

if(f==0){f=common_error('sourcetype','Please Select a LeadSource Type');  if(f==1) { return f; }}
if(f==0){f=common_error('source','Please Select a LeadSource');  if(f==1) { return f; }} 
if(f==0){f=common_error('Scheme','Please Select a Scheme');  if(f==1) { return f; }}     
if(f==0){f=common_error('feedstock','Please Select a Fead Stock');  if(f==1) { return f; }}     }
    
</script>   
    
