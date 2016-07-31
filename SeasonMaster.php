<?php
$PageSecurity = 2;
$PricesSecurity = 9;

include('includes/session.inc');

$title = _('Season Master');

include('includes/header.inc');
$pagetype=3;
include('includes/sidemenu1.php');
include('includes/SQL_CommonFunctions.inc');
include('includes/formload.inc');

?>
<script type="text/javascript">

$(document).ready(function(){  
 
  $("#selectiondetails").hide(); 
        
$('.button_details_show').click(function() {
  $('#selectiondetails').slideToggle('slow', function() {
    // Animation complete.
  });
}); 

});

   
function showSeasonDetails(str1)
{
//alert(str1);
if (str1=="")
  {
  document.getElementById("panel_seasmas").innerHTML="";
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
    document.getElementById("panel_seasmas").innerHTML=xmlhttp.responseText;  
    document.getElementById('loc').focus(); 
    }
  } 
xmlhttp.open("GET","editseasonmaster.php?p=" + str1,true);
xmlhttp.send();    

}

function showProdPeriod()
{
//alert("haii");
var id=document.getElementById("seasonid").value;
//alert(id);
//$("#malayalamdate").hide();
if (id=="")
  {
  document.getElementById("malayalamdate").innerHTML="";
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
    document.getElementById("malayalamdate").innerHTML=xmlhttp.responseText;  
    document.getElementById('loc').focus(); 
    }
  } 
xmlhttp.open("GET","ProductionPeriod.php?p=" + id,true);
xmlhttp.send();    

}

/*function showMalayalamDate(){
   //alert("haii");
var id=document.getElementById("seasonid").value;
//alert(id);
//$("#malayalamdate").hide();
if (id=="")
  {
  document.getElementById("malayalamdate").innerHTML="";
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
    document.getElementById("malayalamdate").innerHTML=xmlhttp.responseText;  
    document.getElementById('loc').focus(); 
    }
  } 
xmlhttp.open("GET","MalayalamDate.php?p=" + id,true);
xmlhttp.send();    
}     */

function addNewSeasonName()
{
//alert("haii");
//var id=document.getElementById("seasonid").value;
//alert(id);
//$("#malayalamdate").hide();
//if (id=="")
//  {
//  document.getElementById("panel_seasmas").innerHTML="";
//  return;
//  }
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
    document.getElementById("panel_seasmas").innerHTML=xmlhttp.responseText;  
    document.getElementById('seasonsubname').focus(); 
    }
  } 
xmlhttp.open("GET","NewSeasonName.php",true);
xmlhttp.send();    

}


</script>
 <?php
 
 $fieldid=get_fieldid('season_id','m_season',$db);
 

echo '<p class="page_title_text">' . ' ' . _('Season') . '';

//echo date("Y");
$DateString = Date($_SESSION['DefaultDateFormat']);
$createdon=FormatDateForSQL($DateString);
if(isset($_POST['addseason'])){
        $InputError=0;
        $id=$_POST['SeasonNameID'];
        $sname=$_POST['SeasonSubName'];
        
        
        $sql_seasonnameexist="SELECT COUNT(*)
                              FROM m_sub_season
                              WHERE seasonname='".$sname."'";
        $result_seasonnameexist= DB_query($sql_seasonnameexist, $db);
        $myrow_seasonnameexist = DB_fetch_row($result_seasonnameexist);
        
        if ($myrow_seasonnameexist[0]>0) {
            $InputError=1;
            prnMsg('The Season name is Already exists');
        }
        elseif($InputError==0){
            $sql2="INSERT INTO m_sub_season(season_sub_id,
                                            seasonname,
                                            createdby,
                                            createdon,
                                            rowstatus,
                                            updatedby) 
                                    VALUES(".$id.",
                                          '".$sname."',
                                          '".$_SESSION['UserID']."',
                                          '".$createdon."',
                                          0,
                                          '".$_SESSION['UserID']."')";
        $msg = _('A new Season Name has been added to the database'); 
        $result2 = DB_query($sql2,$db,_('The update/addition of the season record failed because'));
        
        prnMsg($msg,'success');
            
        }
        unset ($_POST['SeasonNameID']);
        unset ($_POST['SeasonSubName']);
    }


if (isset($_POST['submit'])) {
    
//    echo $current_date=date("d/m/Y");
    
    $FormatedDuedate1 = FormatDateForSQL($_POST['StartDate']);
    $FormatedDuedate2 = FormatDateForSQL($_POST['EndDate']); 
    $InputError = 0;
    
    list($year1, $month1, $day1) = explode('[/]', $FormatedDuedate1); 
    list($year2, $month2, $day2) = explode('[/]', $FormatedDuedate2);
    $month_name1 = date( 'F', mktime(0, 0, 0, $month1) );
    $month_name2 = date( 'F', mktime(0, 0, 0, $month2) );
    $start_english_year=$year1;
    
    $sql_prod="SELECT * FROM productionperiod WHERE seasonid=".$_POST['SeasonID'];
    $result_prod = DB_query($sql_prod,$db);
    $myrow_count = DB_num_rows($result_prod);
    $myrow_prod=DB_fetch_array($result_prod);
    
    
    
    
    
    
    //
//    if(isset($_POST['SID']) AND ($myrow_count>0 OR isset($_POST['ProdSID']))){
//        echo"haii";
//        
//        if($_POST['CurrentSeason']==1){
//            $sql_curseason="UPDATE m_season SET
//                                       is_current=0
//                            WHERE is_current=1";
//            $result_curseason = DB_query($sql_curseason,$db);
//        }   
//        $startdate=FormatDateForSQL($_POST['ProStartDate']);
//        $enddate=FormatDateForSQL($_POST['ProEndDate']);
//        $InputError = 1;
//        $sql2= "UPDATE m_season SET
//                            startdate='" . $FormatedDuedate1 . "',     
//                            enddate= '" . $FormatedDuedate2 . "',
//                            start_eng_day='" . $day1 . "',
//                            start_eng_month='" . $month_name1 . "',
//                            start_eng_year='".$year1."',
//                            end_eng_day='" . $day2 . "',
//                            end_eng_month='" . $month_name2 . "',
//                            start_malday='".$_POST['StartDay']."',
//                            start_malmonth='".$_POST['StartMonth']."',
//                            start_malyear='".$_POST['StartYear']."',
//                            end_malday='".$_POST['EndDay']."',
//                            end_malmonth='".$_POST['EndMonth']."',
//                            end_malyear='".$_POST['EndYear']."',
//                            is_current=".$_POST['CurrentSeason']."
//             WHERE season_id=".$_POST['SID'];
//             echo $sql2;
//       $msg = _('The season record has been updated for') . ' ' . $_POST['SeasonName'];      
//       $result2 = DB_query($sql2,$db,_('The update/addition of the season record failed because'));
//       $sql3="UPDATE productionperiod SET
//                            startdate='".$startdate."',
//                            enddate='".$enddate."'
//              WHERE seasonid=".$_POST['SID'];
//       $result3 = DB_query($sql3,$db,_('The update/addition of the season record failed because'));       
//       prnMsg($msg,'success');
//    }else
    //if(isset($_POST['SID']) AND (isset($_POST['ProStartDate']))){
//        echo"hellow";
//        $InputError = 1;
//       $id=$_POST['SID'];
//       $startdate=FormatDateForSQL($_POST['ProStartDate']);
//       $enddate=FormatDateForSQL($_POST['ProEndDate']);
//       
//       $sql2="INSERT INTO productionperiod(seasonid,startdate,enddate) VALUES(".$id.",'".$startdate."','".$enddate."')";
//       $msg = _('Production Period has been added to the database for') . ' ' . $_POST['SeasonName']; 
//       $result2 = DB_query($sql2,$db,_('The update/addition of the season record failed because'));
//       prnMsg($msg,'success');
//        
//    }else

if($InputError==0 AND !isset($_POST['SID'])){
        if($_POST['CurrentSeason']==1){
            $sql_curseason="UPDATE m_season SET
                                       is_current=0
                            WHERE is_current=1";
            $result_curseason = DB_query($sql_curseason,$db);
        }   
    $sql_seasonexist="SELECT COUNT(*) FROM m_season 
                      WHERE season_sub_id='".$_POST['SeasonName']."'
                       AND start_eng_year=".$year1;
    $result_seasonexist = DB_query($sql_seasonexist, $db);
    $myrow_seasonexist = DB_fetch_row($result_seasonexist);
    if ($myrow_seasonexist[0]>0) {
        prnMsg('The  Season is already exist for the selected year');
    }
    else{       
    
        $sql2 = "INSERT INTO m_season (
                            season_sub_id,    
                            startdate,     
                            enddate,
                            start_eng_day,
                            start_eng_month,
                            start_eng_year,
                            end_eng_day,
                            end_eng_month,
                            start_malday,
                            start_malmonth,
                            start_malyear,
                            end_malday,
                            end_malmonth,
                            end_malyear,
                            is_current,
                            createdby,
                            createdon,
                            rowstatus,
                            updatedby)
                        VALUES ('" .$_POST['SeasonName']. "',
                        '" . $FormatedDuedate1 . "',
                        '" . $FormatedDuedate2 . "',
                        '" . $day1 . "',
                        '" . $month_name1 . "',
                        '" . $start_english_year . "',
                        '" . $day2 . "',
                        '" . $month_name2 . "',
                        '".$_POST['StartDay']."',
                        '".$_POST['StartMonth']."',
                        '".$_POST['StartYear']."',
                        '".$_POST['EndDay']."',
                        '".$_POST['EndMonth']."',
                        '".$_POST['EndYear']."',
                        '".$_POST['CurrentSeason']."',
                        '".$_SESSION['UserID']."',
                        '".$createdon."',
                        0,
                        '".$_SESSION['UserID']."'                        
                        )";  
//             echo $sql2; 
            $msg = _('A new season record has been added to the database for') . ' ' . $_POST['SeasonName']; 
            $result2 = DB_query($sql2,$db,_('The update/addition of the season record failed because'));
            prnMsg($msg,'success');
            if($myrow_count==0){
                prnMsg('Production Period is not specified for'. $_POST['SeasonName'],'warn');
            }
    }
    }elseif($myrow_count>0 AND isset($_POST['ProdSID'])){
        
        $startdate=FormatDateForSQL($_POST['ProStartDate']);
        $enddate=FormatDateForSQL($_POST['ProEndDate']);
        $sql3="UPDATE productionperiod SET
                            startdate='".$startdate."',
                            enddate='".$enddate."'
              WHERE seasonid=".$_POST['SID'];
        $result3 = DB_query($sql3,$db,_('The update/addition of the season record failed because'));
        $msg=('Production Period is updated for'.' '. $_POST['SeasonName']);       
        prnMsg($msg,'success');
        
        
    }elseif($myrow_count==0 AND isset($_POST['ProdSID'])){
        
        $id=$_POST['SeasonID'];
        $startdate=FormatDateForSQL($_POST['ProStartDate']);
        $enddate=FormatDateForSQL($_POST['ProEndDate']);
        $sql2="INSERT INTO productionperiod(seasonid,
                                            startdate,
                                            enddate) 
                                    VALUES(".$id.",
                                          '".$startdate."',
                                          '".$enddate."')";
        $msg = _('Production Period has been added to the database for') . ' ' . $_POST['SeasonName']; 
        $result2 = DB_query($sql2,$db,_('The update/addition of the season record failed because'));
        
        prnMsg($msg,'success');
        
    }elseif(isset($_POST['SID'])){
        if($_POST['CurrentSeason']==1){
            $sql_curseason="UPDATE m_season SET
                                       is_current=0
                            WHERE is_current=1";
            $result_curseason = DB_query($sql_curseason,$db);
        }
        $InputError = 1;
        $sql2= "UPDATE m_season SET
                            startdate='" . $FormatedDuedate1 . "',     
                            enddate= '" . $FormatedDuedate2 . "',
                            start_eng_day='" . $day1 . "',
                            start_eng_month='" . $month_name1 . "',
                            start_eng_year='".$year1."',
                            end_eng_day='" . $day2 . "',
                            end_eng_month='" . $month_name2 . "',
                            start_malday='".$_POST['StartDay']."',
                            start_malmonth='".$_POST['StartMonth']."',
                            start_malyear='".$_POST['StartYear']."',
                            end_malday='".$_POST['EndDay']."',
                            end_malmonth='".$_POST['EndMonth']."',
                            end_malyear='".$_POST['EndYear']."',
                            is_current=".$_POST['CurrentSeason']."
             WHERE season_id=".$_POST['SID'];
//             echo $sql2;
       $msg = _('The season record has been updated for') . ' ' . $_POST['SeasonName'];      
       $result2 = DB_query($sql2,$db,_('The update/addition of the season record failed because'));
    }
    
    
    //elseif(isset($_POST['ProdSID'])){

//        $id=$_POST['ProdSID'];
//        $startdate=FormatDateForSQL($_POST['ProStartDate']);
//        $enddate=FormatDateForSQL($_POST['ProEndDate']);
//        $InputError = 1;
//        $sql2="INSERT INTO productionperiod(seasonid,startdate,enddate) VALUES(".$id.",'".$startdate."','".$enddate."')";
//        $msg = _('Production Period has been added to the database for') . ' ' . $_POST['SeasonName']; 
//        $result2 = DB_query($sql2,$db,_('The update/addition of the season record failed because'));
//        prnMsg($msg,'success');
//        
//        }
        unset ($_POST['']);
        unset ($_POST['SeasonName']);
        unset ($FormatedDuedate1);
        unset ($FormatedDuedate2);
        unset ($day1);
        unset ($month_name1);
        unset ($start_english_year);
        unset ($day2);
        unset ($month_name2);
        unset ($_POST['StartDay']);
        unset ($_POST['StartMonth']);
        unset ($_POST['StartYear']);
        unset ($_POST['EndDay']); 
        unset ($_POST['EndMonth']); 
        unset ($_POST['EndYear']);
}

if (isset($_POST['delete'])) {
   if(isset($_POST['SID'])){
       $season_ID=$_POST['SID'];
       $CancelDelete = 0;
   }elseif(!isset($_POST['SID'])){
       prnMsg(_('Select an item from the list and then click to delete'),'warn');
   } 
}    

echo"<div id=fullbody>";
echo '<form name="SeasonForm" enctype="multipart/form-data" method="post" action="' . $_SERVER['PHP_SELF'] . '?' .SID .'">';
echo"<div class=panels id=panel_seasmas>";
panel($db,$fieldid); 
echo"</div>";

echo"<p><div class='centre' id='buttons'>";

buttons($db);

echo '</div></form>';

echo"<div id='selectiondetails'>";

selectiondetails($db,$fieldid);

echo "</div>";

echo"<div id='Datagrid' style='height:100px; overflow:auto;'>";

datagrid($db);

echo "</div>";


echo"</div>";

function panel($db,$fieldid){
    
//--------------------------------------------------------------Start of Left Panel1
echo '<table width=100%><tr><td width=50%>'; 
echo "<fieldset id='left_panel_1' style='height:155px;'>"; 
echo"<legend><h3>Season Details</h3>";
echo"</legend>";
echo"<table>";

echo"<tr>
    <td>". _('Season ID') .":</td>
    <td><input type='hidden' name='SeasonID' id='seasonid' value='$fieldid'>$fieldid</td>
    </tr>";

echo"<tr>
    <td>". _('Season Name') .":</td>
    <td><select name='SeasonName' id='seasonname'>";

$sql_season = 'SELECT m_sub_season.season_sub_id,     
                m_sub_season.seasonname
  FROM m_sub_season';
$result_season = DB_query($sql_season,$db);
while ($myrow = DB_fetch_array($result_season)) {
    if (isset($_GET['SeasonName']) and $myrow['seasonname']==$_GET['SeasonName']) {
         echo "<option selected value='" .$myrow['season_sub_id'] . "'>" . $myrow['seasonname'];
    } else {                                                                                                             
        echo "<option value='" . $myrow['season_sub_id'] . "'>" . $myrow['seasonname'];
    }
    
} //end while loop
    
echo"</select>
    </td>
    </tr>";
    
echo"<tr>
    <td>". _('Start Date') .":</td>
    <td><input type='Text' name='StartDate' id='startdate' class=date alt='".$_SESSION['DefaultDateFormat']. "' style='width:173px;'></td>
    </tr>";
    
echo"<tr>
    <td>". _('End Date') .":</td>
    <td><input type='Text' name='EndDate' id='enddate' class=date alt='".$_SESSION['DefaultDateFormat']. "' style='width:173px;'></td>
    </tr>";
    
 echo"<tr>
    <td>". _('Current Season') .":</td>
    <td>
    <input type='radio' name='CurrentSeason' id='currentseason' value='1' >Yes
    <input type='radio' name='CurrentSeason' id='currentseason' value='0' >No
    </td>
    </tr>";

echo"</table>";
echo"</fieldset>";
echo"</td>";


echo'<td>'; 
echo"<div id='malayalamdate'>";
/*echo "<fieldset id='right_panel_1' style='height:155px;'>";     
echo"<legend><h3>Malayalam Date</h3>";
echo"</legend>";
echo'<table>';

echo"<tr>
    <td></td>
    <td>&nbsp&nbspDay &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp Month &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp Year</td>
    </tr>";

echo"<tr>
    <td>". _('Start Date') .":</td>
    <td>
    <input type='Text' name='StartDay' id='startday' size=2%>
    <select name='StartMonth' id='startmonth'>";
$sql_malmonth = 'SELECT month_id,     
                malmonthname
  FROM m_sub_malmonth';
$result_malmonth = DB_query($sql_malmonth,$db);
while ($myrow = DB_fetch_array($result_malmonth)) {
    if (isset($_GET['StartMonth']) and $myrow['seasonname']==$_GET['StartMonth']) {
         echo "<option selected value='" .$myrow['month_id'] . "'>" . $myrow['malmonthname'];
    } else {                                                                                                             
        echo "<option value='" . $myrow['month_id'] . "'>" . $myrow['malmonthname'];
    }
    
} //end while loop    
    


echo"</select>";
echo"<input type='Text' name='StartYear' id='startyear' size=2%>";
echo"</td> 
    </tr>";

echo"<tr>
    <td>". _('End Date') .":</td>
    <td>
    <input type='Text' name='EndDay' id='endday' size=2%>
    <select name='EndMonth' id='endmonth'>";
$sql_malmonth = 'SELECT month_id,     
                malmonthname
  FROM m_sub_malmonth';
$result_malmonth = DB_query($sql_malmonth,$db);
while ($myrow = DB_fetch_array($result_malmonth)) {
    if (isset($_GET['EndMonth']) and $myrow['seasonname']==$_GET['EndMonth']) {
         echo "<option selected value='" .$myrow['month_id'] . "'>" . $myrow['malmonthname'];
    } else {                                                                                                             
        echo "<option value='" . $myrow['month_id'] . "'>" . $myrow['malmonthname'];
    }
    
} //end while loop
echo"</select>";
echo"<input type='Text' name='EndYear' id='endyear' size=2%>";
echo"</td> 
    </tr>";    
    

echo "</table>";
echo "</fieldset>"; */ 
echo"</div>";    
echo"</td></tr></table>";

}

function buttons($db){
    
    echo "<table ><tr>";
    echo "<td><input type='Submit' name='submit' VALUE='" . _('Save') . "' onclick='if(log_in()==1)return false'></td>";
    echo "<td><input type='Submit' name='clear' VALUE='" . _('Clear') . "'></td>";
//    echo "<td><input type='Submit' name='delete' VALUE='" . _('Delete') . "'></td>";
    echo "<td><input type='Button' class='button_details_show' name='details' VALUE='" . _('Details') . "'></td>";
    echo "</tr></table>";
    
}

function selectiondetails($db,$fieldid){
    
     echo '<table width=100% colspan=2 border=2 cellpadding=4>
    <tr>
        <th width="33%">' . _('Inquiries') . '</th>
        <th width="33%">' . _('Transactions') . '</th>
        <th width="33%">' . _('Maintenance') . '</th>
    </tr>';
echo"<tr><td  VALIGN=TOP class='menu_group_items'>";


echo"</td><td  VALIGN=TOP class='menu_group_items'>";

echo"</td><td  VALIGN=TOP class='menu_group_items'>";
//echo '<a style=cursor:pointer; onclick=()>' . _('Malayalam Date') . '</a><br>';
echo '<a style=cursor:pointer; onclick=showProdPeriod()>' . _('Production Period') . '</a><br>';
//echo '<a style=cursor:pointer; onclick=addNewSeasonName()>' . _('Add New Season Name') . '</a><br>';

echo"</td></tr>";



echo'</table>';
}


function datagrid($db){

    echo '<table>
    <tr>
        <th width="10%">' . _('SlNo.') . '</th>
        <th width="33%">' . _('Season') . '</th>
        <th width="33%">' . _('Start Date') . '</th>
        <th width="33%">' . _('End Date') . '</th>
        <th width="33%">' . _('Current Season') . '</th>
        
        
    </tr>';
    
    $sql = 'SELECT m_sub_season.season_sub_id,
                   m_sub_season.seasonname,
                   m_season.season_id,
                   m_season.startdate,     
                   m_season.enddate,
                   m_season.is_current                 
            FROM m_sub_season,m_season
            WHERE m_season.season_sub_id=m_sub_season.season_sub_id';
            
//    echo "<br/>sql is $sql<br/>";
    $ErrMsg = _('The SQL to find the parts selected failed with the message');
    $result = DB_query($sql,$db,$ErrMsg);
    $k=0; //row colour counter 
    $slno=0;
    while ($myrow = DB_fetch_array($result)){
        
        if ($k==1){
        echo '<tr class="EvenTableRows" id="'.$myrow['season_id'].'" onclick=showSeasonDetails(this.id)>';
        $k=0;
    } else {
        echo '<tr class="OddTableRows" id="'.$myrow['season_id'].'" onclick=showSeasonDetails(this.id)>';
        $k=1;
    }
  //  $sql2="SELECT malmonthname FROM m_sub_malmonth WHERE month_id=".$myrow['start_malmonth'];
    //$ErrMsg = _('The SQL to find the parts selected failed with the message');
  //  $result2 = DB_query($sql2,$db,$ErrMsg);
  //  $myrow2=DB_fetch_array($result2);
  //  $sql3="SELECT malmonthname FROM m_sub_malmonth WHERE month_id=".$myrow['end_malmonth'];
   // $ErrMsg = _('The SQL to find the parts selected failed with the message');
 //   $result3 = DB_query($sql3,$db,$ErrMsg);
 //   $myrow3=DB_fetch_array($result3);
    $sdate=ConvertSQLDate($myrow['startdate']);
    $ddate=ConvertSQLDate($myrow['enddate']);
    
    
       
    $slno++;
    printf("<td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            </tr>",
            $slno,
            $myrow['seasonname'],
            $sdate,
            $ddate,
            $myrow['is_current']);
            
    }
    
    echo'<tfoot><tr>';
echo'<td colspan=10>Number of records:'.$slno.'</td>';
echo'</tr></tfoot>';    
echo'</table>'; 
}
?>
<script>
document.getElementById('seasonname').focus();   
//document.forms[0].itemcode.focus();  
function seasonName(){
    document.getElementById('seasonsubname').focus();
    f=common_error('seasonsubname','Please enter the season name');  if(f==1) { return f; }
}


function log_in(){
    
var f=0;
var p=0;
f=common_error('seasonname','Please enter the season name');  if(f==1) { return f; }
if(f==0){f=common_error('startdate','Please enter the start date');  if(f==1){return f; }  } 
if(f==0){f=common_error('enddate','Please enter the end date');  if(f==1){return f; }  }  

if(f==0){f=common_error('startday','Please enter the start day(malayalam)');  if(f==1){return f; }  }  
if(f==0){f=common_error('startmonth','Please enter the start year(malayalam)');  if(f==1){return f; }  }  
if(f==0){f=common_error('startyear','Please enter the start year(malayalam)');  if(f==1){return f; }  }
if(f==0){f=common_error('endday','Please enter the end day(malayalam)');  if(f==1){return f; }  }  
if(f==0){f=common_error('endmonth','Please select a Category');  if(f==1){return f; }  }  

if(f==0){f=common_error('endyear','Please enter the end year(malayalam)');  if(f==1){return f; }  }

document.forms[0].itemcode.focus();           

}

</script>