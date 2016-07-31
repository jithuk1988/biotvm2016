<?php


/* $Id leadsourcetypes.php  2011-07-08 16:39:20Z tcv $ */
$PageSecurity = 40;
include('includes/session.inc');
$title = _('Lead Teams') . ' / ' . _('Maintenance');
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

echo '<p class="page_title_text"><img src="'.$rootpath.'/css/'.$theme.'/images/maintenance.png" title="' . _('Lead Team')
    . '" alt="" />' . _('Lead Team Setup') . '</p>';
echo '<div class="page_help_text">' . _('Add / edit / delete Lead Teams') . '</div><br />';

?>
 <script type="text/javascript">

function showCD5()
{

var str1=document.getElementById("office").value;
var str2=document.getElementById("members").value;
var str3=document.getElementById("memcredit").value;
   //alert(str1);
//   alert(str2);
           $('#addmembers').show(); 
           $('#displayteam').hide();   
           $('#addmembers').show();   
           $('#updatemembersdetails').show(); 
if(str1==0){
alert("Select Member"); document.getElementById("members").focus();  return false;  }
else if(str2==""){
alert("Enter Members Credit"); document.getElementById("memcredit").focus();  return false;  }

if (str1=="")
  {
  document.getElementById("addmembers").innerHTML="";     //editleads
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
    document.getElementById("addmembers").innerHTML=xmlhttp.responseText;
//    document.getElementById('loc').focus(); 
    }
  } 
xmlhttp.open("GET","bio_getpropertyvalue.php?members=" + str2  + "&memcredit=" + str3 ,true);
xmlhttp.send();    

}
function showCD6()                      
{
              $('#displayteam').show();   
              $('#addmembers').show();   
              $('#updatemembersdetails').show(); 
    alert("hiiupdate");
var str1=document.getElementById("updatemembers").value;
var str2=document.getElementById("updatememcredit").value;
var str3=document.getElementById("updatememberid").value;
   //   $('#updatemembersdetails').show();
   //   $('#editmembers').show();
    //  $('#displayteam').show();
   alert(str1);
   alert(str2);
//   alert(str);
   alert(str3);
//if(str1==""){
//alert("select a Feedstock"); document.getElementById("feedstock").focus();  return false;  }
if (str1=="")
  {
  document.getElementById("updatemembersdetails").innerHTML="";     //editleads
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
    document.getElementById("updatemembersdetails").innerHTML=xmlhttp.responseText;
//    document.getElementById('loc').focus(); 
    }
  } 
xmlhttp.open("GET","bio_getpropertyvalue.php?updatemembers1=" + str1  + "&updatememcredit1=" + str2 + "&updatememberid1="+ str3 ,true);
xmlhttp.send();    

}

function showCD7()                      
{
              $('#displayteam').show();   
              $('#addmembers').show();   
              $('#updatemembersdetails').show(); 
    alert("hiiupdateteamcreated mem");
var str1=document.getElementById("updateempid").value;
var str2=document.getElementById("updatememteamid").value;
var str3=document.getElementById("updatecredit").value;
var str4=document.getElementById("updateemp").value;
   //   $('#updatemembersdetails').show();
   //   $('#editmembers').show();
    //  $('#displayteam').show();

if (str1=="")
  {
  document.getElementById("updatemembersdetails").innerHTML="";     //editleads
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
    document.getElementById("updatemembersdetails").innerHTML=xmlhttp.responseText;
//    document.getElementById('loc').focus(); 
    }
       alert(str1);
   alert(str2);
   alert(str3);
   alert(str4);
  } 
xmlhttp.open("GET","bio_getpropertyvalue.php?updateempid=" + str1  + "&updatememteamid=" + str2 + "&updatecredit="+ str3 + "&updateemp="+ str4 ,true);
xmlhttp.send();    

}
function showCD2(str1)
{ 
    $('#members').show();$('#addmembers').show();
                  $('#displayteam').show();   
              $('#addmembers').show();   
              $('#updatemembersdetails').show(); 
    
//var str1=document.getElementById("members").value;
//var str2=document.getElementById("memcredit").value;
   alert("hii");
   alert(str1);
        //      $('#updatemembersdetails').show();
//   alert(str2);
//if(str1==""){
//alert("select a Feedstock"); document.getElementById("feedstock").focus();  return false;  }
if (str1=="")
  {
  document.getElementById("editmembers").innerHTML="";     //editleads
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
    document.getElementById("editmembers").innerHTML=xmlhttp.responseText;
//    document.getElementById('loc').focus(); 
    }
  } 
xmlhttp.open("GET","bio_getpropertyvalue.php?editmemberid=" + str1,true);
xmlhttp.send();    

}

function showCD4(str1,str2)
{ 
    $('#members').show();$('#addmembers').show();
                  $('#displayteam').show();   
              $('#addmembers').show();   
              $('#updatemembersdetails').show(); 
    
//var str1=document.getElementById("members").value;
//var str2=document.getElementById("memcredit").value;
//   alert("hii");

        //      $('#updatemembersdetails').show();
//   alert(str2);
//if(str1==""){
//alert("select a Feedstock"); document.getElementById("feedstock").focus();  return false;  }
if (str1=="")
  {
  document.getElementById("editmembers").innerHTML="";     //editleads
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
    document.getElementById("editmembers").innerHTML=xmlhttp.responseText;
//    document.getElementById('loc').focus(); 
    }   //alert(str1);
//   alert(str2);
  } 
xmlhttp.open("GET","bio_getpropertyvalue.php?editmemberid=" + str1 + "&editteammemid=" + str2, true);
xmlhttp.send();    

}

function ShowMembers(str1)
{
//var str1=document.getElementById("members").value;
//var str2=document.getElementById("memcredit").value;
//   alert("hii");
//   alert(str1);  

                      $('#displayteam').hide();   
              $('#addmembers').hide();   
              $('#updatemembersdetails').hide();   
              $('#members').hide();             
                  
//   alert(str2);
//if(str1==""){
//alert("select a Feedstock"); document.getElementById("feedstock").focus();  return false;  }
if (str1=="")
  {
  document.getElementById("showmembers").innerHTML="";     //editleads
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
  {//alert(str1);
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {     //alert("sss");
    document.getElementById("showmembers").innerHTML=xmlhttp.responseText;

//             $('#showmembers').show(); 

//    document.getElementById('loc').focus(); 
    }
  } 
xmlhttp.open("GET","bio_getpropertyvalue.php?displaymembers=" + str1,true);
xmlhttp.send();    

}


function displayEmployees(str1)
{
//alert(str1);
if (str1=="")
  {
  document.getElementById("showemp").innerHTML="";     //editleads
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
  {//alert(str1);
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {     //alert("sss");
    document.getElementById("showemp").innerHTML=xmlhttp.responseText;

//             $('#showmembers').show(); 

//    document.getElementById('loc').focus(); 
    }
  } 
xmlhttp.open("GET","bio_showemp.php?offid=" + str1,true);
xmlhttp.send();    

}


function deletemem(str1)
{ 
    $('#members').show();
    $('#addmembers').show();
     $('#displayteam').show();   
     $('#addmembers').show();   
     $('#updatemembersdetails').show(); 

   alert("hii");
   
if (str1=="")
  {
  document.getElementById("deletemembers").innerHTML="";     //editleads
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
    {alert(str1);
    document.getElementById("deletemembers").innerHTML=xmlhttp.responseText;
//    document.getElementById('loc').focus(); 
    }
  } 
xmlhttp.open("GET","bio_getpropertyvalue.php?deletememberid=" + str1,true);
xmlhttp.send();    

}
</script>
<?php
if (!isset($_POST['submit'])) {
    $tempdrop="DROP TABLE IF EXISTS bio_memberstemp";
DB_query($tempdrop,$db); 

$temptable="CREATE TABLE `bio_memberstemp` (
  `temp_id` int(11) NOT NULL AUTO_INCREMENT,
  `members` varchar(25) DEFAULT NULL,
  `credit` int(3) DEFAULT NULL,
  PRIMARY KEY (`temp_id`)
)";
DB_query($temptable,$db);
}


if (isset($_POST['submit'])) {
//echo $_POST['teamname'];
//echo $_POST['teamid'];
//echo $_POST['remarks'];
    //initialise no input errors assumed initially before we test
    $InputError = 0;

    /* actions to take once the user has clicked the submit button
    ie the page has called itself with some user input */

    //first off validate inputs sensible
    $i=1;
    //if (strlen($_POST['teamname']) >50) {
//        $InputError = 1;
//        echo prnMsg(_('The Lead Team Name must be 50 characters or less long'),'error');
//        $Errors[$i] = 'TeamName';
//        $i++;
//    }

//    if (strlen($_POST['teamname'])==0) {
//        $InputError = 1;
//        echo prnMsg(_('The Lead Team Name must contain at least one character'),'error');
//        $Errors[$i] = 'TeamName';
//        $i++;
//    }

    $checksql = "SELECT count(*)
             FROM bio_leadteams
             WHERE teamname = '" . $_POST['teamname'] . "'";
    $checkresult=DB_query($checksql, $db);
    $checkrow=DB_fetch_row($checkresult);
    if ($checkrow[0]>0 and (!isset($SelectedType))) {
        $InputError = 1;
        echo prnMsg(_('You already have a Lead Team Name called').' '.$_POST['teamname'],'warn');
        $Errors[$i] = 'TeamName';
        $i++;
    }

    if (isset($SelectedType) AND $InputError !=1) {

        $sql = "UPDATE bio_leadteams
                    SET teamname ='" . $_POST['teamname'] ."',
                        remarks='".$_POST['remarks']."',
                        office_id='".$_POST['Office']."' 
             WHERE teamid= '" . $SelectedType . "'";
        $msg = _('The Lead Team Name')  . ' ' . $SelectedType . ' ' .  _('has been updated');
        
    } elseif ( $InputError !=1 ) {

        // First check the type is not being duplicated

        $checkSql = "SELECT count(*)
                 FROM bio_leadteams
                 WHERE teamname = '" . $_POST['teamname'] . "'";

        $checkresult = DB_query($checkSql,$db);
        $checkrow = DB_fetch_row($checkresult);

        if ( $checkrow[0] > 0 ) {
            $InputError = 1;
            prnMsg( _('The Lead Team Name ') . $_POST['teamname'] . _(' already exist.'),'error');
        } else {

            // Add new record on submit
//        echo "insert hii";
            $sql = "INSERT INTO bio_leadteams(teamname,
                                              remarks,
                                              office_id)
                                VALUES (' " . $_POST['teamname'] . " ',
                                           '".$_POST['remarks']."',
                                           '".$_POST['Office']."'
                                           )";
            $result=DB_query($sql, $db);
            $sql1="SELECT teamid FROM bio_leadteams ORDER BY teamid DESC";
            $result1=DB_query($sql1,$db);
            $myrow1=DB_fetch_array($result1);
            $teamid=$myrow1[0];//DB_Last_Insert_ID(&$Conn,'bio_leadteams','teamid');
            //$sql1="INSERT INTO bio_teammembers (teamid,creditpercentage) VALUES (".$teamid.",".$_POST['memcredit'].")";
            //$result1=DB_query($sql1,$db);
            $sql="INSERT INTO bio_teammembers(SELECT $teamid,bio_memberstemp.members,bio_memberstemp.credit FROM bio_memberstemp)";
            $result=DB_query($sql,$db);
//=======================================================
 $tempdrop="DROP TABLE IF EXISTS bio_memberstemp";
DB_query($tempdrop,$db); 

$temptable="CREATE TABLE `bio_memberstemp` (
  `temp_id` int(11) NOT NULL AUTO_INCREMENT,
  `members` varchar(25) DEFAULT NULL,
  `credit` int(3) DEFAULT NULL,
  PRIMARY KEY (`temp_id`)
) ";
DB_query($temptable,$db);  
            $msg = _('Lead Team Name') . ' ' . $_POST['teamname'] .  ' ' . _('has been created');
            $checkSql = "SELECT count(teamid)
                 FROM bio_leadteams";
				 
            $result = DB_query($checkSql, $db);
            $row = DB_fetch_row($result);
unset($_POST['teamname']);
unset($_POST['remarks']);
        }
    }

    if ( $InputError !=1) {
    //run the SQL from either of the above possibilites
        $result = DB_query($sql,$db);



        prnMsg($msg,'success');

        unset($SelectedType);
        unset($_POST['teamid']);
        unset($_POST['teamname']);
       // unset($_POST['SourceProperty']);
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

    
    
        $sql="DELETE FROM bio_leadteams WHERE teamid='" . $SelectedType . "'";
        $ErrMsg = _('The Team record could not be deleted because');
   $result=DB_query($sql,$db);     
        $sql1="DELETE FROM bio_teammembers WHERE teamid='" . $SelectedType . "'";
              $result1 = DB_query($sql1,$db,$ErrMsg);                                 
        prnMsg(_('Lead Team') . $SelectedType  . ' ' . _('has been deleted') ,'success');

        unset ($SelectedType);
        unset($_GET['delete']);

    /*}*/
}
else if(isset($SelectedMem))
{



	$sql="DELETE FROM bio_memberstemp WHERE temp_id=".$Numdel;
	$result = DB_query($sql,$db,$ErrMsg);
	prnMsg(_('Lead Team Member') . $Numdel  . ' ' . _('has been deleted') ,'success');
 
}
	




//end of ifs and buts!
if (isset($SelectedType)) {

    echo '<div class="centre"><p><a href="' . $_SERVER['PHP_SELF'] . '">' . _('Create New Team') . '</a></div><p>';
} 
echo "<a href='index.php'>Back to Home</a>"  ;
echo "<table border=0 style='width:55%';><tr><td>";  
echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '">';
echo "<fieldset style='width:300px;height:150px'>";
echo "<legend><h3>Team Master</h3></legend>";
if (! isset($_GET['delete'])) {
    

    
    echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';
//    echo '<br />';   


    echo '<table class="selection">'; //Main table
    // The user wish to EDIT an existing type

    if ( isset($SelectedType) AND $SelectedType!='' ) 
    {

        $sql = "SELECT teamid,
                   teamname,remarks,office_id
                FROM bio_leadteams
                        WHERE bio_leadteams.teamid='" . $SelectedType . "'";

        $result = DB_query($sql, $db);
        $myrow = DB_fetch_array($result);

                $_POST['teamid'] = $myrow['teamid'];
                $_POST['teamname'] = $myrow['teamname'];
        $_POST['Office']  = $myrow['office_id'];
        $_POST['remarks']  = $myrow['remarks'];

        echo '<input type="hidden" name="SelectedType" value="' . $SelectedType . '">';
        echo '<input type="hidden" name="teamid" value="' . $_POST['teamid'] . '">';

        // We dont allow the user to change an existing type code
    }

    if (!isset($_POST['teamname'])) {
        $_POST['teamname']='';
    }
    if (!isset($_POST['remarks'])) {
        $_POST['remarks']='';
    }
    if (!isset($_POST['Office'])) {
        $_POST['Office']='';
    }
    //echo '<tr><td>' . _('Team ID') . ':</td>
//                    <!-- <td>' . $_POST['teamid'] . '</td> -->
//            <td><input type="text" name="teamid" id="teamid" value="' . $_POST['teamid'] . '" readonly></td>
//        </tr>';
        
    echo '<tr><td>' . _('Office') . ':</td>';
    echo '<td><select name="Office" id="office"  style="width:100%" onchange="displayEmployees(this.value)">';
    $sql="select * from bio_office";
    $result=DB_query($sql,$db);
    echo'<option value=0></option>';
    while($row=DB_fetch_array($result))
    {
        if ($row['id']==$_POST['Office'])
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
    
    
    echo '<tr><td>' . _('LeadSource Team') . ':</td>
              <td><input type="text" name="teamname" id="leadteam" value="'. $_POST['teamname'] .'">';
    echo '</td></tr>';
    echo '<tr><td>' . _('Remarks') . ':</td>
              <td><input type="text" name="remarks" id="remark" value="' . $_POST['remarks'] . '"></td>
          </tr>';
       

   // echo '<tr><td colspan=2><p><div class="centre"><input type=submit name=submit value="' . _('Accept') . '"></div>';

       echo '</td></tr>'; // close main table

echo '</table>';
//echo '</form>';
} 
// end if user wish to delete
//ADD MEMBERS........................................ADD MEMBERS.....................ADD MEMBERS
echo '</fieldset>'; 
//echo "</td></tr>";
echo "</td>";
//echo "<tr>";
echo "<td >";
echo "<fieldset style='width:300px;height:150px'>";
echo "<legend><h3>Member Details</h3></legend>";
//echo "<div id=updatemembersdetails>";
 // echo "<div id=showmembers style='overflow:scroll;height:150px'>";
 echo "<div id=editmembers>";

                            
 
  //echo "<div id=members>"; 
     addmembers($db);
  echo "</div>";     

   // echo "</div>";
   //  echo "<div id=showmembers style='overflow:scroll;height:150px'>";      echo "</div>";
    // addmembers($db);
  // echo "</div>";
    //echo "</div>";
    echo '</fieldset>';
	
    //....../...........................................................
echo "</td>";
echo "</tr>";
echo "<tr><td>";
echo '<tr><td colspan=2><p><div class="centre"><input type=submit name=submit value="' . _('Accept') . '" onclick="if(log_in()==1)return false;">';
echo'</div>';
echo "</td></tr>";
echo '</form>';
echo "<tr><td colspan=2>";
echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '">';
    echo "<fieldset style='width:670px'>";
   echo "<legend><h3>Team Details</h3></legend>";
  function addmembers($db)
  {
       echo '<table class="selection">';
       
            

    echo '<tr id="showemp"><td>' . _('Members') . ':</td>';
    echo '<td><select name="members" id="members"  style="width:100%">';
    $sql="select * from bio_emp";
    $result=DB_query($sql,$db);
    echo'<option value=0></option>';
    while($row=DB_fetch_array($result))
    {
        if ($row['empid']==$_POST['members'])
        {
            echo '<option selected value="';
        } else {
            echo '<option value="';
        }
        echo $row['empid'] . '">'.$row['empname'];
        echo '</option>';
    }
    echo'</select></td>';
    echo'</tr>';

    //echo '<td>';
//    echo "<input type='text' name='members' id='members'>";
//    echo '</td></tr>';

    echo '<tr><td>' . _('Credit percentage') . ':</td>';
    echo '<td>';
    echo "<input type='text' class=number name='memcredit' id='memcredit'>";
    echo '</td></tr>';
        echo '<tr><td></td><td colspan=2><input type=button name=addmem id="addmem" value="Add" onclick="showCD5()"></td></tr>';
        
    echo "</table>"; 
  }
if (!isset($SelectedType)){

/* It could still be the second time the page has been run and a record has been selected for modification - SelectedType will
 *  exist because it was sent with the new call. If its the first time the page has been displayed with no parameters then
 * none of the above are true and the list of source property will be displayed with links to delete or edit each. These will call
 * the same page again and allow update/input or deletion of the records
 */
 
// echo "<div id='showmembers'>"; 
//display($db);
//echo "</div>";
}
//else
//{
echo "<div id='showmembers'>"; 
echo "<div id='updatemembersdetails'>";          

//echo "<div id='deletemembers'>"; 
echo "<div id='addmembers'>"; 
echo "<div id='displayteam'>" ;   
display($db);  
    echo "</div>";
    echo "</div>";
    echo "</div>";  
    echo "</div>";  
  // echo "</div>";
            
//}


echo '</fieldset>';
echo '</form>';
echo "</table>";
function display($db)
{
    echo "<div style='overflow:scroll;height:150px'>";
    echo "<table class='selection' style='width:100%'>";
    echo '<tr>
          <th>' . _('Sl No') . '</th>
          <th>' . _('Team Name') . '</th>
          <th>' . _('Office') . '</th>
          <th>' . _('Remarks') . '</th>
          </tr>';
        $sql = "SELECT bio_leadteams.teamid, 
                   bio_leadteams.teamname, 
                   bio_leadteams.remarks,
                   bio_office.office
            FROM bio_leadteams,bio_office
            WHERE bio_office.id=bio_leadteams.office_id";
    $result = DB_query($sql,$db);


$k=0; //row colour counter
$sl=0;
while ($myrow = DB_fetch_row($result)) {
    if ($k==1){
        echo '<tr class="EvenTableRows">';
        $k=0;
    } else 
    {
        echo '<tr class="OddTableRows">';
        $k=1;
    }
    $sl++;
    $teamid=$myrow[0];
    printf("<td>%s</td>                    
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td><a  style='cursor:pointer;'  onclick=ShowMembers('$teamid')>" . _('View Members') . "</a></td>
            <td><a href='%sSelectedType=%s' onclick=ShowMembers('$teamid')>" . _('Edit Team') . "</td>
            <td><a href='%sSelectedType=%s&delete=yes' onclick=\'return confirm('" .
                _('Are you sure you wish to delete this Lead Source Team?') . "');\'>" . _('Delete') . "</td>
        </tr>",
        $sl,
        $myrow[1],
        $myrow[3],
        $myrow[2],
        $_SERVER['PHP_SELF'] . '?',
        $myrow[0],
        $_SERVER['PHP_SELF'] . '?',
        $myrow[0],
        $_SERVER['PHP_SELF'] . '?',
        $myrow[0]);
    }
    
    //END WHILE LIST LOOP
    echo '</table>';
    echo '</div>';
}
include('includes/footer.inc');

?>

<script type="text/javascript">
  document.getElementById('office').focus();
  $(document).ready(function() {
      $('#members').show();                 
      $("#notice").fadeOut(3000);
      $("#error").fadeOut(3000);
      $("#warn").fadeOut(8000);
      $("#success").fadeOut(3000);
      $("#info").fadeOut(3000);
      $(".db_message").fadeOut(3200);
  }); 
 
  
  function log_in()
{
   
//    document.getElementById('phone').focus();
var f=0;
var p=0;
if(f==0){f=common_error('office','Please select an Office ');  if(f==1){return f; }  }
if(f==0){f=common_error('leadteam','Please enter a team ');  if(f==1){return f; }  }


}


  </script>