<?php
/* $Id feedstocksourcenames.php  2011-07-08 16:39:20Z tcv $ */
$PageSecurity = 80;
include('includes/session.inc');
$title = _('Feedstock Source Types') . ' / ' . _('Maintenance');
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

echo '<p class="page_title_text"><img src="'.$rootpath.'/css/'.$theme.'/images/maintenance.png" title="' . _('Feed Stock Source Type')
    . '" alt="" />' . _('Feed Stock Source Type Setup') . '</p>';
echo '<div class="page_help_text">' . _('Add/edit/delete Feed Stock Source Type') . '</div><br />';

if (isset($_POST['submit'])) {

    //initialise no input errors assumed initially before we test
    $InputError = 0;

    /* actions to take once the user has clicked the submit button
    ie the page has called itself with some user input */

    //first off validate inputs sensible
    $i=1;
    if (strlen($_POST['TypeName']) >100) {
        $InputError = 1;
        echo prnMsg(_('The source type name description must be 100 characters or less long'),'error');
        $Errors[$i] = 'feedstocksourcename';
        $i++;
    }

    if (strlen($_POST['TypeName'])==0) {
        $InputError = 1;
        echo prnMsg(_('The source type name description must contain at least one character'),'error');
        $Errors[$i] = 'feedstocksourcename';
        $i++;
    }
   

    if (isset($SelectedType) AND $InputError !=1) {

        $sql = "UPDATE bio_feedstocksources SET 
                feedstocksourcename = '" . $_POST['TypeName'] ."',
                generatingamount='".$_POST['Amount']."',
                id='".$_POST['Feedstocks']."',
                quantitativedetails='".$_POST['Quantitativedetails']."',
                gasout='".$_POST['Gasout']."',
                waste_category_id='".$_POST['Category']."',
                feedstock_unit='".$_POST['FeedstockUnit']."'
            WHERE feedstocksourceid = '" . $SelectedType . "'
            AND id='".$_POST['Feedstocks']."'";

        $msg = _('The source type') . ' ' . $SelectedType . ' ' .  _('has been updated');
       prnMsg($msg,'success'); 
    } elseif ( $InputError !=1 ) {

        // First check the type is not being duplicated

        if (!isset($SelectedType))     {
    $checksql = "SELECT count(*)
             FROM bio_feedstocksources
             WHERE feedstocksourcename = '" . $_POST['TypeName'] . "'
             AND id='".$_POST['Feedstocks']."'";
    $checkresult=DB_query($checksql, $db);
    $checkrow=DB_fetch_row($checkresult);
    if ($checkrow[0]>0) {
        $InputError = 1;
        echo prnMsg(_('You already have a source type called').' '.$_POST['TypeName'],'error');
        $Errors[$i] = 'OutputName';
        $i++;
    }
    else {

            // Add new record on submit

            $sql = "INSERT INTO bio_feedstocksources
                        (feedstocksourcename,
                        generatingamount,
                        id,
                        quantitativedetails,
                        gasout,
                        waste_category_id,
                        feedstock_unit)
                    VALUES ('" . $_POST['TypeName'] . "',
                    '" . $_POST['Amount'] . "',
                    '".$_POST['Feedstocks']."',
                    '".$_POST['Quantitativedetails']."',
                    '".$_POST['Gasout']."',
                    '".$_POST['Category']."',
                    '".$_POST['FeedstockUnit']."')";


            $msg = _('source type') . ' ' . $_POST['TypeName'] .  ' ' . _('has been created');
            $checkSql = "SELECT count(feedstocksourceid)
                 FROM bio_feedstocksources";
            $result = DB_query($checkSql, $db);
            $row = DB_fetch_row($result);
prnMsg($msg,'success');
        }
        }
    }

    if ( $InputError !=1) {
    //run the SQL from either of the above possibilites
        $result = DB_query($sql,$db);



        

        unset($SelectedType);
        unset($_POST['TypeID']);
        unset($_POST['TypeName']);
		unset($_POST['Amount']);
        unset($_POST['Feedstocks']);  
        unset($_POST['Quantitativedetails']);
        unset($_POST['Gasout']);
        unset($_POST['Category']);
        unset($_POST['FeedstockUnit']);
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

        $sql="DELETE FROM bio_feedstocksources WHERE feedstocksourceid='" . $SelectedType . "'";
        $ErrMsg = _('The Type record could not be deleted because');
        $result = DB_query($sql,$db,$ErrMsg);
        prnMsg(_('Source type') . $SelectedType  . ' ' . _('has been deleted') ,'success');

        unset ($SelectedType);
        unset($_GET['delete']);

    /*}*/
}


//end of ifs and buts!


if (isset($SelectedType)) {

    echo '<div class="centre"><p><a href="' . $_SERVER['PHP_SELF'] . '">' . _('Show All Types Defined') . '</a></div><p>';
}

echo "<a href='index.php'>Back to Home</a>"  ;
echo "<table border=0 style='width:45%';><tr><td>";  
  echo "<fieldset style='width:440px;height:270px'>";
   echo "<legend><h3>Feed Stock Source Type Master</h3></legend>";
if (! isset($_GET['delete'])) {

    echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '">';
    echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';
    echo '<br /><table class="selection">'; //Main table
    // The user wish to EDIT an existing type
    if ( isset($SelectedType) AND $SelectedType!='' ) {

        //$sql = "SELECT feedstocksourceid,
//                   feedstocksourcename,generatingamount,id
//                FROM bio_feedstocksources
//                WHERE feedstocksourceid='" . $SelectedType . "'";

         $sql = "SELECT bio_feedstocksources.feedstocksourceid, 
                       bio_feedstocksources.feedstocksourcename,
                       bio_feedstocksources.generatingamount,
                       bio_feedstocksources.id,
                       bio_feedstocks.feedstocks,
                       bio_feedstocksources.quantitativedetails,
                       bio_feedstocksources.gasout,
                       bio_feedstocksources.waste_category_id,
                       bio_feedstocksources.feedstock_unit 
                  FROM bio_feedstocksources,bio_feedstocks
                 WHERE bio_feedstocksources.id=bio_feedstocks.id
                   AND feedstocksourceid='" . $SelectedType . "'";
        
        
        $result = DB_query($sql, $db);
        $myrow = DB_fetch_array($result);

        $_POST['TypeID'] = $myrow['feedstocksourceid'];
        $_POST['TypeName']  = $myrow['feedstocksourcename'];
		$_POST['Amount']  = $myrow['generatingamount'];
        $_POST['Feedstocks']  = $myrow['feedstocks'];
        $_POST['Quantitativedetails']= $myrow['quantitativedetails']; 
        $_POST['Gasout']= $myrow['gasout'];
        $_POST['Category']= $myrow['waste_category_id'];
        $_POST['FeedstockUnit']= $myrow['feedstock_unit'];
        echo '<input type="hidden" name="SelectedType" value="' . $SelectedType . '">';
        echo '<input type="hidden" name="TypeID" value="' . $_POST['TypeID'] . '">';

        // We dont allow the user to change an existing type code

       // echo '<tr><td>' ._('Source Type ID') . ': </td>
//                <td>' . $_POST['TypeID'] . '</td>
//            </tr>';
    }

    if (!isset($_POST['TypeName'])) {
        $_POST['TypeName']='';
    }
	if (!isset($_POST['Amount'])) {
        $_POST['Amount']='';
    }
    if (!isset($_POST['Feedstocks'])) {
        $_POST['Feedstocks']='';
    }
    if (!isset($_POST['Quantitativedetails'])) {
        $_POST['Quantitativedetails']='';
    }
    if (!isset($_POST['Gasout'])) {
        $_POST['Gasout']='';
    }
    if (!isset($_POST['Category'])) {
        $_POST['Category']='';
    }
    if (!isset($_POST['FeedstockUnit'])) {
        $_POST['FeedstockUnit']='';
    }

//===================================================================================
    echo '<tr><td>' . _('Source Type') . ':</td>';
                  $sql="SELECT * FROM bio_fssources";
      $result=DB_query($sql,$db);
      echo '<td><select name="TypeName" id="fssource" style="width:180px">';
      $f=0;
      while($myrow1=DB_fetch_array($result))
      {
      if ($myrow1['id']==$_POST['TypeName'])
      {
      echo '<option selected value="';
      } else
      {
      if ($f==0)
        {
        echo '<option>';
        }
        echo '<option value="';
        }
      echo $myrow1['id'] . '">'.$myrow1['source'];
      echo '</option>';
      $f++;
      }
      echo '</select>';
      echo'</td>';        
      echo'</tr>';
		
	
        
//=====================================================================================

 $sql1="SELECT * FROM bio_feedstocks";
  $result1=DB_query($sql1, $db);
 
            echo '<tr><td>' . _('Feedstock') . ':</td>'; 
         echo '<td><select name="Feedstocks" id="feedstock" style="width:180px">';
  $f=0;
    while($myrow1=DB_fetch_array($result1))
    { 
    if ($myrow1['id']==$myrow['id']) 
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
    echo $myrow1['id'] . '">'.$myrow1['feedstocks'];
    echo '</option>';
    }
     
    echo '</select></td></tr>';
    
    echo '<tr><td>' . _('Feedstock Type') . ':</td>';
    $sql1="SELECT * FROM bio_wastecategory";
  $result1=DB_query($sql1, $db);
  echo '<td><select name="Category" id="Category" style="width:180px">';
  $f=0;
    while($myrow1=DB_fetch_array($result1))
    { 
    if ($myrow1['waste_category_id']==$myrow['waste_category_id']) 
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
    
    echo '<tr><td>' . _('Feedstock Unit') . ':</td>';
    $sql1="SELECT * FROM bio_feedstockunits";
  $result1=DB_query($sql1, $db);
  echo '<td><select name="FeedstockUnit" id="feedstockunit" style="width:180px" onchange="showPerUnit(this.value)">';
  $f=0;
    while($myrow1=DB_fetch_array($result1))
    { 
    if ($myrow1['id']==$myrow['feedstock_unit']) 
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
    echo $myrow1['id'] . '">'.$myrow1['unit'];
    echo '</option>';
    }
     
    echo '</select></td></tr>';
    
       
    if($_POST['FeedstockUnit']==3){
    echo '<tr id="showperunit">';
    echo '<td>' . _('Feedstock Production per unit') . ':</td>
            <td><input type="text" name="Amount" id="amount" style="width:180px" value="' . $_POST['Amount'] . '"></td>';
    echo'</tr>';
    }else{
        echo '<tr id="showperunit">';
        echo'</tr>';
    }    
    echo '<tr><td>' . _('Quantitative Details') . ':</td>
            <td><input type="text" name="Quantitativedetails" id="quantitativedetails" style="width:180px" value="' . $_POST['Quantitativedetails'] . '"></td>
        </tr>';
        
    echo '<tr><td>' . _('Gas Out') . ':</td>
            <td><input type="text" name="Gasout" id="gasout" style="width:180px" value="' . $_POST['Gasout'] . '"></td>
        </tr>';
    
//=====================================================================================

    echo '<tr><td colspan=2><p><div class="centre"><input type=submit name=submit value="' . _('Save') . '" onclick="if(log_in()==1)return false;"></div>';

       echo '</td></tr></table>'; // close main table

    echo '</form>';

} // end if user wish to delete

echo '</fieldset>'; 
echo "</td></tr>";
echo "<tr><td>";
    echo "<fieldset style='width:440px'>";
   echo "<legend><h3>Feedstock Source Type Details</h3></legend>";

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
        $sql = "SELECT bio_feedstocksources.feedstocksourceid, 
                       bio_feedstocksources.feedstocksourcename,
                       bio_wastecategory.waste_category,
                       bio_feedstocksources.id,
                       bio_feedstocks.feedstocks,
                       bio_feedstocksources.quantitativedetails,
                       bio_feedstocksources.gasout,
                       bio_fssources.source 
                  FROM bio_feedstocksources,bio_feedstocks,bio_fssources,bio_wastecategory
                 WHERE bio_feedstocksources.id=bio_feedstocks.id
                   AND bio_fssources.id=bio_feedstocksources.feedstocksourcename
                   AND bio_wastecategory.waste_category_id=bio_feedstocksources.waste_category_id";
    $result = DB_query($sql,$db);
echo "<div style='overflow:scroll;height:150px'>";
    echo '<table class="selection">';
    echo '<tr>
        <th>' . _('SlNo') . '</th>
        <th>' . _('Source Type') . '</th>
		<th>' . _('Feedstock') . '</th>
        <th>' . _('Feedstock Type') . '</th>
        <th>' . _('Quantitative Details') . '</th>
        <th>' . _('Gas Out') . '</th>
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
            <td>%s</td>
            <td>%s</td>
            <td>%s</td> 
            <td><a href="%sSelectedType=%s">' . _('Edit') . '</td>
            <td><a href="%sSelectedType=%s&delete=yes" onclick=\'return confirm("' .
                _('Are you sure you wish to delete this Source Type?') . '");\'>' . _('Delete') . '</td>
        </tr>',
        $slno,
        $myrow[7],
		$myrow[4],
        $myrow[2],
        $myrow[5],
        $myrow[6],  
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

document.getElementById('fssource').focus();
 
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
if(f==0){f=common_error('sourcetype','Please enter Source Type');  if(f==1){return f; }  }
if(f==0){f=common_error('amount','Please enter the Quantity');  if(f==1){return f; }  } 
if(f==0){f=common_error('feedstock','Please select a Feedstock');  if(f==1){return f; }  }   
}



function showPerUnit(str){
    //alert(str);
if (str!=3)
  {
  document.getElementById("showperunit").innerHTML="";
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
    document.getElementById("showperunit").innerHTML=xmlhttp.responseText;  

    }
  } 
xmlhttp.open("GET","bio_showprodperunit.php?unit="+ str,true);
xmlhttp.send();

}


</script>
