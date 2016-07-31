<?php


/* $Id EnquiryTypes.php  2011-07-08 16:39:20Z tcv $ */
$PageSecurity = 80;
include('includes/session.inc');
$title = _('Lead Sources') . ' / ' . _('Maintenance');
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

echo '<p class="page_title_text"><img src="'.$rootpath.'/css/'.$theme.'/images/maintenance.png" title="' . _('Lead Sources')
    . '" alt="" />' . _('Lead Source Setup') . '</p>';
echo '<div class="page_help_text">' . _('Add/edit/delete Lead Sources') . '</div><br />';



?>

<script type="text/javascript">
document.getElementById('stype').focus();
function showCD1(str)
{
//     alert("hi");
  // $("# sourcedetails").show(); 
// $("# hidetr").show();
  $("#displaypropertyvalue").show(); 
if (str=="")
  {
  document.getElementById("propertyvalue").innerHTML="";
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
        
    document.getElementById("propertyvalue").innerHTML=xmlhttp.responseText;
//      $('#dinhide').show(); 
    }
  } 
//  alert(str);
xmlhttp.open("GET","bio_getpropertyvalue.php?q="+str, true);
xmlhttp.send(); 
 
}
function showproperty(str1)
{
        $("# editproperty").show(); 
//var str1=document.getElementById("members").value;
//var str2=document.getElementById("memcredit").value;
//   alert("hii");
  // exit;
//   alert(str1);
//   alert(str2);
//if(str1==""){
//alert("select a Feedstock"); document.getElementById("feedstock").focus();  return false;  }
if (str1=="")
  {
  document.getElementById("editproperty").innerHTML="";     //editleads
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
    document.getElementById("editproperty").innerHTML=xmlhttp.responseText;
//    document.getElementById('loc').focus(); 
    }
  } 
xmlhttp.open("GET","bio_getpropertyvalue.php?editproperty=" + str1, true);
xmlhttp.send();    

}

</script>
<?php

if (isset($SelectedType)) {

    echo '<div class="centre"><p><a href="' . $_SERVER['PHP_SELF'] . '">' . _('Create New Source') . '</a></div><p>';
}

//////////////////

if (isset($_POST['submit'])) {

    //initialise no input errors assumed initially before we test
    $InputError = 0;

    /* actions to take once the user has clicked the submit button
    ie the page has called itself with some user input */

    //first off validate inputs sensible
    $i=1;
    if (strlen($_POST['sourcename']) >100) {
        $InputError = 1;
        echo prnMsg(_('The Lead Source name description must be 100 characters or less long'),'error');
        $Errors[$i] = 'LeadSource';
        $i++;
    }

    if (strlen($_POST['sourcename'])==0) {
        $InputError = 1;
        echo prnMsg(_('The Lead Source name description must contain at least one character'),'error');
        $Errors[$i] = 'LeadSource';
        $i++;
    }

    $checksql = "SELECT count(*)
             FROM bio_leadsources
             WHERE sourcename = '" . $_POST['sourcename'] . "'";
    $checkresult=DB_query($checksql, $db);
    $checkrow=DB_fetch_row($checkresult);
    if ($checkrow[0]>0) {
        $InputError = 1;
        echo prnMsg(_('You already have a Source Name called').' '.$_POST['sourcename'],'error');
        $Errors[$i] = 'LeadSource';
        $i++;
    }
    
    
 

    if (isset($SelectedType) AND $InputError !=1) {
         
        $sql = "UPDATE bio_leadsources  
            SET sourcename = '" . $_POST['sourcename'] . "',
            "."sourcetypeid=".$_POST['sourcetype1'].",
            officeid=".$_POST['office1'].",
            teamid=".$_POST['team1'].",
            costcentreid=".$_POST['costcentre1'].",
            remarks='".$_POST['remarks1']."'
            WHERE id = '" . $SelectedType . "'";
            
            
        foreach ($_POST as $key => $value) 
                {  
                 if(substr($key, 0, 17)=='editpropertyvalue')
                 { 
                  //   echo "<br>".substr($key, 0, 17)."<br>";
                  $ItemCode=substr($key, 17, strlen($key)-17);
                 $Quantity=$value; 
  
             $sql1="UPDATE `bio_sourcedetails` SET 
                         `propertyvalue` = '".$Quantity."' WHERE sourcepropertyid=".$ItemCode;

                  $result1 = DB_query($sql1,$db);
                 }
                 }

        $msg = _('The Source') . ' ' . $SelectedType . ' ' .  _('has been updated');
    } elseif ( $InputError !=1 ) {

        // First check the type is not being duplicated

        $checkSql = "SELECT count(*)
                 FROM bio_leadsources
                 WHERE id = '" . $_POST['id'] . "'";

        $checkresult = DB_query($checkSql,$db);
        $checkrow = DB_fetch_row($checkresult);

        
        

        if ( $checkrow[0] > 0 ) {
            $InputError = 1;
            prnMsg( _('The Source ') . $_POST['id'] . _(' already exist.'),'error');
        } else {

            // Add new record on submit
            
            $benoffice = $_POST['multipleoffice'];
          //$benoffice=$_POST['benofficename'];
        //  print_r($benoffice);
$array = explode(',', $benoffice);
//Print_r($array);
$s='';
foreach ($array as $key => $value) 
{
//    echo $key;
//    echo $value;
    $sql1="SELECT id FROM bio_office WHERE office='".$value."'";
    $result1 = DB_query($sql1,$db);
     $myrow1=DB_fetch_array($result1);
     $s=$myrow1[0].",".$s;
}
//echo "<br />myrow".$s;
//            $sql = "INSERT INTO bio_leadsources 
//                        (sourcetypeid,sourcename,officeid,benofficeid,costcentreid,teamid,remarks)
//                    VALUES (" . $_POST['sourcetype1'] . ",
//                    '".$_POST['sourcename']."',".$_POST['office1'].",'".$_POST['benofficename']."',".$_POST['costcentre1'].",".$_POST['team1'].",'".$_POST['remarks']."')";
            $sql = "INSERT INTO bio_leadsources 
                        (sourcetypeid,sourcename,officeid,benofficeid,costcentreid,remarks)
                    VALUES (" . $_POST['sourcetype1'] . ",
                    '".$_POST['sourcename']."',".$_POST['office1'].",'".$s."',".$_POST['costcentre1'].",'".$_POST['remarks']."')";
                    //$result = DB_query($sql,$db);
             //$sid=DB_insert_id($sql);
             $sql1="SELECT id
                    FROM bio_leadsources
                    ORDER BY id DESC";
             $result1 = DB_query($sql1,$db);
             $myrow1=DB_fetch_array($result1);
             $sid=$myrow1[0];
            // echo $sid;
             
             $stypeid1=$_SESSION['stypeid']; 
//             $sql="SELECT sourcepropertyid FROM bio_sourceproperty WHERE sourcetypeid=".$stypeid1;
//            $result = DB_query($sql,$db);
          //  $myrow=DB_fetch_array($result);
            //


                foreach ($_POST as $key => $value) 
                {  //echo substr($key, 0, 13)."<br>";
                 if(substr($key, 0, 13)=='propertyvalue')
                 { 
                  $ItemCode=substr($key, 13, strlen($key)-13);
                  $Quantity=$value; 
                  // echo $sid;
        //  echo $myrow['sourcepropertyid'];
                $Quantity."<br>......".$ItemCode."<br>";     


                 $sql1="INSERT INTO bio_sourcedetails (sourceid,sourcepropertyid,propertyvalue) values(".$sid.",".$ItemCode.",'".$Quantity."')";
                 // echo $ItemCode.$Quantity;
                  $result1 = DB_query($sql1,$db);
                 }
                 }

           
            $msg = _('Source Name') . ' ' . $_POST['sourcename'] .  ' ' . _('has been created');
            $checkSql = "SELECT count(id)
                 FROM bio_leadsources";
            $result = DB_query($checkSql, $db);
            $row = DB_fetch_row($result);

        }
    }

    if ( $InputError !=1) {
    //run the SQL from either of the above possibilites
        $result = DB_query($sql,$db);



        prnMsg($msg,'success');

        unset($SelectedType);
        unset($_POST['id']);
        unset($_POST['sourcename']);
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

        $sql="DELETE FROM bio_leadsources WHERE id='" . $SelectedType . "'";
        $result = DB_query($sql,$db);
        $sql="DELETE FROM bio_sourcedetails WHERE sourceid='" . $SelectedType . "'";
        
        $ErrMsg = _('The Type record could not be deleted because');
        $result = DB_query($sql,$db,$ErrMsg);
        
        prnMsg(_('Source Name') . $SelectedType  . ' ' . _('has been deleted') ,'success');

        unset ($SelectedType);
        unset($_GET['delete']);

 
}


//echo "<a href='index.php'>Back to Home</a>"  ;
//echo "<table border=1 style='width:70%;';><tr><td style='align:left;>";  
//  echo "<fieldset style='width:380px;height:390px;align:left;'>";
//   echo "<legend><h3>Team Master</h3></legend>";
   
   echo "<a href='index.php'>Back to Home</a>"  ;
echo "<table border=1 style='width:64%;';><tr><td style='align:left;width:50%;'>";  
    echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '">';
  echo "<fieldset style='width:90%;height:390px;align:left;'>";
   echo "<legend><h3>Team Master</h3></legend>";

if (! isset($_GET['delete'])) 
{

   // echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '">';
    echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';
    echo '<br />';

    echo '<table class="selection" border=0>'; //Main table
    // The user wish to EDIT an existing type

    if ( isset($SelectedType) AND $SelectedType!='' ) {

        $sql = "SELECT id,sourcetypeid,sourcename,officeid,teamid,costcentreid,remarks
                FROM bio_leadsources
                WHERE id='" . $SelectedType . "'";

        $result = DB_query($sql, $db);
        $myrow = DB_fetch_array($result);
        //echo "hiiii".$myrow['id'];
        $_POST['id'] = $myrow['id'];
        $_POST['sourcename']  = $myrow['sourcename'];
 $_POST['sourcetype']  = $myrow['sourcetypeid'];
        $_POST['office']  = $myrow['officeid'];
        $_POST['team']  = $myrow['teamid'];
        $_POST['costcentre']  = $myrow['costcentreid'];
        $_POST['remarks']  = $myrow['remarks'];
        
        $sql1="SELECT bio_leadsourcetypes.leadsourcetype,
            bio_office.office,
            bio_leadteams.teamname,
            bio_costcentres.costcentre
                 FROM bio_leadsourcetypes,
                 bio_office,
                 bio_leadteams,
                 bio_costcentres
                 WHERE bio_leadsourcetypes.id=".$_POST['sourcetype']." 
                 AND bio_office.id=".$myrow[3]." 
                 AND bio_leadteams.teamid=".$myrow[4]." 
                 AND bio_costcentres.costcentreid=".$myrow[5];
        $result1 = DB_query($sql1,$db);
        $myrow1 = DB_fetch_row($result1);
        




        echo '<input type="hidden" name="SelectedType" value="' . $SelectedType . '">';
        echo '<input type="hidden" name="sourceid" value="' . $_POST['id'] . '">';

}
        // We dont allow the user to change an existing type code

//        echo '<tr><td>' ._('Source ID') . ': </td>
//                <td>' . $_POST['id'] . '</td>
//            </tr>';
        echo '<tr><td>' . _('Source ID') . ':</td>
                    <!-- <td>' . $_POST['id'] . '</td> -->
            <td><input type="text" style="width:93%" name="sourceid"  readonly value="' . $_POST['id'] . '"></td>
        </tr>';

    if (!isset($_POST['sourcename'])) {
        $_POST['sourcename']='';
    }
    if (!isset($_POST['remarks'])) {
        $_POST['remarks']='';
    }      //////////

    echo '<tr><td>' . _('Source Name') . ':</td>
      <td><input type="text" name="sourcename" style="width:93%"  id="source" value="' . $_POST['sourcename'] . '"></td>
    </tr>';
    
    echo '<tr><td>Lead Source Type</td>';
    echo  '<td>'; //echo $_POST['sourcetype'];
    echo '<select name="sourcetype1" id="stype" style="width:190px" onchange=showCD1(this.value) onclick=showCD1(this.value)>';
    $sql2="SELECT * FROM bio_leadsourcetypes"; 
    $result2=DB_query($sql2,$db);
    $f=0;
    while($myrow2=DB_fetch_array($result2))
    { 
     if ($myrow2['id']==$_POST['sourcetype'])
    {       
    echo '<option selected value="';
    } else 
    { 
    if($f==0) 
        {
        echo '<option>';
        }     
        echo '<option value="';
    }
    echo $myrow2['id'] . '">'.$myrow2['leadsourcetype'];
    echo '</option>';
             $f++;
    }

    echo '</select>';    
    echo '</td></tr>';
    /////////////////
    
echo "<tr><td colspan=2>";

        
 echo "<div id='propertyvalue'>";
  echo "</div>" ;
 
  if(isset($SelectedType))
  {  echo "<div id='editproperty'>";
                      echo "<table border=0 style='width:100%'>";
        $sql2="SELECT sourcetypeid,id FROM bio_leadsources WHERE id=".$SelectedType;
        $result2=DB_query($sql2,$db);
        $myrow2=DB_fetch_array($result2);
   $sql3="SELECT property, propertyvalue,bio_sourceproperty.sourcepropertyid
            FROM bio_sourceproperty, bio_sourcedetails
            WHERE bio_sourceproperty.sourcetypeid =".$myrow2[0]."
            AND bio_sourcedetails.sourceid =".$SelectedType."
            AND bio_sourceproperty.sourcepropertyid = bio_sourcedetails.sourcepropertyid";
    $result3=DB_query($sql3,$db);    
    while($myrow3=DB_fetch_array($result3))
    {

      echo "<tr style='width:100%'>";
//       echo "<td>$myrow3[0]</td>";

//        echo "<td><input type=text name='propertyvalue'".$myrow3[0]." value='$myrow3[1]'></td>";
//        echo "</tr>";

        
        
       printf("<td style='width:93px'>%s</td>
                     <td><input  type='text' name='editpropertyvalue%s' value='$myrow3[1]' style='width:170px'></td>                                               
                      ",
                $myrow3[0],
                $myrow3[2]);  

                echo "</tr>";
    }
    echo "</table>";
        echo "</div>" ;
  }
      
    echo "</td></tr>";

       echo '</table>';
// close main table                      




echo '</fieldset>'; 
echo "</td>";


echo "<td>";
echo "<div>";
echo "<fieldset style='width:90%;height:380px;align:left;'>";
echo '<legend>Source Details</legend>';
echo "<table>";

        //////////
            echo '<tr><td>Source Office Name</td>';
    echo  '<td>';
    echo '<select name="office1" style="width:190px">';
    $sql2="SELECT * FROM bio_office"; 
    $result2=DB_query($sql2,$db);
    $f=0;
    while($myrow2=DB_fetch_array($result2))
    { 
    if ($myrow2['id']==$_POST['office']) 
    {
       
    echo '<option selected value="';
    
    } else {
            if($f==0) 
        {
        echo '<option>';
        }
        echo '<option value="';
    }
    echo $myrow2['id'] . '">'.$myrow2['office'];
    echo '</option>';
    $f++;
    }
     
    echo '</select>';    
    echo '</td></tr>';
 //////
     echo '<tr><td>Beneficial Offices</td>';
    echo  '<td>';
    echo '<select name="benofficename" style="width:190px" id="benoffice" multiple size=5>';
    /* onblur=showoffice(this.value)*/
    $sql2="SELECT * FROM bio_office"; 
    $result2=DB_query($sql2,$db);
    $f=0;
    while($myrow2=DB_fetch_array($result2))
    { 
    if ($myrow2['office']==$_POST['office']) 
    {
       
    echo '<option selected value="';
    
    } else {
            if($f==0) 
        {
        echo '<option>';
        }
        echo '<option value="';
    }
    echo $myrow2['office'] . '">'.$myrow2['office'];
    echo '</option>';
    $f++;
    }
     
    echo '</select>';    
    echo '</td></tr>';
    echo "<div id=getoffice>";
    
    echo "<tr><td>Beneficial Offices for the Source</td>";
    echo "<td><input type=text name='multipleoffice' id='multipleoff' style='width:93%'></td>";
    echo "</tr>";  echo "</div>";
             ///////////////

    echo '<tr><td>Lead Source Teams</td>';
    echo  '<td>';
    echo '<select name="team1" style="width:190px" id="multeam" multiple size=5>';
    $sql2="SELECT * FROM bio_leadteams"; 
    $result2=DB_query($sql2,$db);
    $f=0;
    while($myrow2=DB_fetch_array($result2))
    { 
    if ($myrow2['teamname']==$_POST['team']) 
    {
    echo '<option selected value="';
    
    } 
    else 
    {
            if($f==0) 
        {
        echo '<option>';
        }
        echo '<option value="';
    }
    echo $myrow2['teamname'] . '">'.$myrow2['teamname'];
    echo '</option>';
    $f++;
    }
    echo '</select>';    
    echo '</td></tr>';
    
    echo "<tr><td>Teams for the Source</td>";
    echo "<td><input type=text name='multipleteam' id='multipleteam' style='width:93%'></td>";
    echo "</tr>";
    ////////////////////

    
        echo '<tr><td>CostCentre Name</td>';
    echo  '<td>';
    echo '<select name="costcentre1" style="width:190px">';
    $sql2="SELECT * FROM bio_costcentres"; 
    $result2=DB_query($sql2,$db);
    $f=0;
    while($myrow2=DB_fetch_array($result2))
    { 
    if ($myrow2['costcentreid']==$_POST['costcentre']) 
    {
       
    echo '<option selected value="';
    
    } else {
            if($f==0) 
        {
        echo '<option>';
        }
        echo '<option value="';
    }
    echo $myrow2['costcentreid'] . '">'.$myrow2['costcentre'];
    echo '</option>';
    $f++;
    }
     
    echo '</select>';    
    echo '</td></tr>';

    ////////////////////
    //echo "sourcetype".$_POST['sourcetype'];
    if (!isset($_POST['remarks'])) {
        $_POST['remarks']='';
    }
   
        echo "<tr><td>" . _('Remarks') . ":</td>
            <td><textarea name=remarks rows=4 cols=25 style=resize:none;>".$_POST['remarks']."</textarea></td>
        </tr>";
echo "</table>";
echo '</fieldset>';
echo "<div>";
echo "</td>";

echo "</tr>";


echo '<tr><td colspan=2><p><div class="centre"><input type=submit name=submit value="' . _('Accept') . '"></div></td></tr>';
echo "<tr><td colspan=2>";
echo '</form>';
echo "</table>";
}
echo "<table style='width:64%;';>";
echo "<tr><td colspan=2>";
    echo "<fieldset style='width:95%'>";
   echo "<legend><h3>Team Master Created</h3></legend>";

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
//if (isset($SelectedType)) {
// end if user wish to delete
function display($db)
{
        $sql = "SELECT id, sourcename,Sourcetypeid,officeid,teamid,costcentreid,remarks FROM bio_leadsources";
    $result = DB_query($sql,$db);
echo "<div style='overflow:scroll;height:150px'>";
    echo '<table class="selection" style="width:100%;align:left;">';
    echo '<tr>
        <th>' . _('Source ID') . '</th>
        <th>' . _('Source Name') . '</th>
        <th>' . _('Lead Source Type') . '</th>
        <th>' . _('Office Name') . '</th>
        <th>' . _('Team Name') . '</th>
        <th>' . _('CostCentre Name') . '</th>
        <th>' . _('Remarks') . '</th>
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
        $sql1="SELECT bio_leadsourcetypes.leadsourcetype,
            bio_office.office,
            bio_leadteams.teamname,
            bio_costcentres.costcentre
                 FROM bio_leadsourcetypes,
                 bio_office,
                 bio_leadteams,
                 bio_costcentres
                 WHERE bio_leadsourcetypes.id=".$myrow[2]." 
                 AND bio_office.id=".$myrow[3]." 
                 AND bio_leadteams.teamid=".$myrow[3]." 
                 AND bio_costcentres.costcentreid=".$myrow[4];
                 //echo $sql1;
        $result1 = DB_query($sql1,$db);
        $myrow1 = DB_fetch_row($result1);
        $sidedit=$myrow[2]; 
    printf("<td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td><a href='%sSelectedType=%s' onclick=showproperty('$sidedit')>" . _('Edit') . "</td>
            <td><a href='%sSelectedType=%s&delete=yes' onclick=\'return confirm('" .
                _("Are you sure you wish to delete this Source?") . "');\'>" . _('Delete') . "</td>
        </tr>",
        $myrow[0],
        $myrow[1],
        $myrow1[0],
        $myrow1[1],
        $myrow1[2],
        $myrow1[3],
        $myrow[6],
        $_SERVER['PHP_SELF'] . '?', 
        $myrow[0],
        $_SERVER['PHP_SELF'] . '?', 
        $myrow[0]);
    }
    echo "</div>";
    //END WHILE LIST LOOP
    echo '</table>';
        
}

include('includes/footer.inc');

?>
<script language="javascript">
  document.getElementById('source').focus();

$(document).ready(function(){  
 
  $("#displaypropertyvalue").hide(); 
        
});
 function displayVals() {
//      var singleValues = $("#single").val();
      var multipleValues = $("#benoffice").val() || [];
      
        var multipleValues=multipleValues.join(","); document.getElementById("multipleoff").value=multipleValues;
    }

    $("#benoffice").change(displayVals);
    displayVals();

    

</script>
<script language="javascript">
 function displayVals() {
//      var singleValues = $("#single").val();
      var multipleValues = $("#multeam").val() || [];
      
        var multipleValues=multipleValues.join(","); 
        document.getElementById("multipleteam").value=multipleValues;
    }

    $("#multeam").change(displayVals);
    displayVals();
    </script>

