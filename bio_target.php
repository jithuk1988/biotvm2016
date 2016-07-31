<?php
 $PageSecurity = 80;
 include('includes/session.inc');
 $title = _('Target Marketing'); 
 $pagetype=3; 
 include('includes/header.inc');
 include('includes/sidemenu.php');
 echo '<center><font style="color: #333;
    background:#fff;
    font-weight:bold;
    letter-spacing:0.10em;
    font-size:16px;
    font-family:Georgia;
    text-shadow: 1px 1px 1px #666;">TARGET FOR MARKETING STAFF</font></center>';
    
    
  $office=$_SESSION['UserStockLocation'];    
    
 if($_GET['edit']!=""){  
$rid=$_GET['edit'];
$sql21="SELECT bio_target.team_id,
               bio_target.targetid,
               bio_target.task,
               bio_target.year,
               bio_target.month,
               bio_target.target,
               bio_target.enqid,
               bio_designation.desgid,
               bio_designation.designation,
               bio_task.taskid,
               m_sub_season.seasonname,
               bio_enquirytypes.enquirytype,
               bio_leadteams.teamname
         FROM  bio_target,
               bio_teammembers,
               bio_designation,
               bio_emp,
               m_sub_season,
               bio_task,
               bio_leadteams,
               bio_enquirytypes
        WHERE  bio_teammembers.empid=bio_emp.empid
           AND bio_target.team_id=bio_teammembers.teamid
           AND bio_target.month=m_sub_season.season_sub_id  
           AND bio_emp.designationid=bio_designation.desgid  
           AND bio_target.task=bio_task.taskid     
           AND bio_target.month=m_sub_season.season_sub_id
           AND bio_target.team_id=bio_leadteams.teamid
           AND bio_enquirytypes.enqtypeid=bio_target.enqid
           AND bio_target.targetid=$rid";
$result=DB_query($sql21,$db);
$myrow3=DB_fetch_array($result);

$task1=$myrow3['taskid']; 

$target=$myrow3['target'];

$year1=$myrow3['year'];

$month1=$myrow3['month'];         

$desg=$myrow3['desgid'];

$enquiry=$myrow3['enqid'];
$team=$myrow3['team_id'];         

$teamname1=$myrow3['teamname'];



$sql_class="select bio_officeclass.id 
                from bio_office,
                     bio_officeclass,
                     bio_teammembers,
                     bio_emp,
                     bio_target
               where bio_target.team_id=$team
               AND bio_target.team_id=bio_teammembers.teamid
               AND bio_teammembers.empid=bio_emp.empid
               AND bio_emp.offid=bio_office.id     
               AND bio_officeclass.id=bio_office.office_class";
                                
        $result_class1=DB_query($sql_class,$db); 
        $myrow4=DB_fetch_array($result_class1);  
        $officeclass=$myrow4['officeclass'];  
} 

$officeclass=$myrow4['id'];
 

                                                           
 if(!isset($_POST['submit'])){     
 $tempdrop="DROP TABLE IF EXISTS bio_targettemp";
 DB_query($tempdrop,$db);

 $temptable="CREATE TABLE bio_targettemp (
            temp_id INT NOT NULL AUTO_INCREMENT ,
            taskid VARCHAR(50) NULL ,
            actualvalue DECIMAL NULL ,
            PRIMARY KEY ( temp_id ))";
DB_query($temptable,$db);

$sql="ALTER TABLE `bio_targettemp` 
      ADD `status` INT 
                     NOT 
                   NULL 
                   DEFAULT 
                   '0'" ; 
DB_query($sql,$db);
}


if(isset($_POST['submit']))     {
    
    
       if ($_POST['SelectedType']!=""){
     $sql= "UPDATE bio_target
                    SET 
                task='".$_POST['task']."' ,target='".$_POST['Number']."'
            WHERE targetid =" .$_POST['SelectedType'];
     $result=DB_query($sql,$db);
 }
 
  else{            $allmonth=$_POST['From'];
      $designation=$_POST['Designation'];
      $office_class=$_POST['office'];
      if($allmonth==13)
      {
          echo "inset full month";
          for($k=1;$k<=12;$k++)
          {
              
             $sql_insert="INSERT INTO bio_target(team_id,
                                          year,month,enqid,task,target) 
                                 VALUES ('".$_POST['Team']."',
                                         '".$_POST['Year']."',
                                         '".$k."',
                                         '".$_POST['enq']."', 
                                           '".$_POST['task']."',
                                           '".$_POST['Number']."')";   
                                          DB_query($sql_insert,$db); 
          }
      }
      else{
       $sql_insert="INSERT INTO bio_target(team_id,
                                          year,month,enqid,task,target) 
                                 VALUES ('".$_POST['Team']."',
                                         '".$_POST['Year']."',
                                         '".$_POST['From']."',
                                         '".$_POST['enq']."', 
                                           '".$_POST['task']."',
                                           '".$_POST['Number']."')";   
                                          $result_sub1 = DB_query($sql_insert,$db); 
      } 
      
/*      $sql_class="SELECT bio_office.id FROM bio_office WHERE bio_office.office_class=".$office_class;
      $result_class=DB_query($sql_class,$db);
   while($row_class=DB_fetch_array($result_class))
    {    $office= $row_class['id'];
         $sql_team="SELECT bio_teammembers.teamid 
                      FROM bio_teammembers,bio_emp 
                     WHERE bio_teammembers.empid=bio_emp.empid 
                       AND bio_emp.designationid=".$designation."
                       AND bio_emp.offid=".$office;                     
         $result_team=DB_query($sql_team,$db); 
         
         while($row_team=DB_fetch_array($result_team)){
             $teamid=$row_team['teamid'];
             $sql_target="select * from bio_targettemp";  
             $result_target=DB_query($sql_target,$db);            
             while($row_target=DB_fetch_array($result_target)){
                $sql_insert="INSERT INTO bio_target(team_id,
                                          year,month,enqid,task,target) 
                                 VALUES ('".$teamid."',
                                         '".$_POST['Year']."',
                                         '".$_POST['From']."',
                                         '".$_POST['enq']."', 
                                           '".$row_target['taskid']."',
                                           '".$row_target['actualvalue']."')";   
                                          $result_sub1 = DB_query($sql_insert,$db);    
             }  
         } 
    }*/ 
 
 }
 
        $tempdrop="DROP TABLE IF EXISTS bio_targettemp";
        DB_query($tempdrop,$db);

        $temptable="CREATE TABLE bio_targettemp (
            temp_id INT NOT NULL AUTO_INCREMENT ,
            taskid VARCHAR(50) NULL ,
            actualvalue DECIMAL NULL,
            PRIMARY KEY ( temp_id ))";
        DB_query($temptable,$db);

        $sql="ALTER TABLE `bio_targettemp` 
            ADD `status` INT 
                     NOT 
                   NULL 
                   DEFAULT 
                   '0'" ; 
        DB_query($sql,$db);
  
        
        }

    
echo"<div id=fullbody>";
echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "?" . SID . "'>"; 
echo '<table><tr><td>';
echo"<div id=panel1>";
echo"<div id=panel>";
echo '<table><tr>';
 
//========================================== Right Panel Begins

echo'<td valign=top>';    
echo'<div id=right_panel_1>';
echo"<fieldset style='width:600px;height:150px'; overflow:auto;'>";
echo"<legend><h3>Select Team</h3></legend>";
echo"<table>";


$DateString = Date($_SESSION['DefaultDateFormat']); 


//----------------------------------------------
 
   
 echo '<tr id="showemp"><td>' . _('Designation*') . '</td>';
    echo '<td><select name="Designation" id="designation"  style="width:160px" onchange="showOffice(this.value)"">';
/*    $sql="select * from bio_designation";
    $result=DB_query($sql,$db);
    echo'<option value=0></option>';
    while($row=DB_fetch_array($result))
    {     
        if ($row['desgid']==$desg)
        {
    echo '<option selected value="';
        } else {
    echo '<option value="';
        }
    echo $row['desgid'].'">'.$row['designation'];
        echo '</option>';
        
    }*/
    echo'<option value=0></option>';
    echo'<option value=1>CCE</option>';
    echo'<option value=2>BDE</option>';
    echo'<option value=3>APO</option>';
    echo'</select></td>';
    echo'</tr>';   

 
/*    echo"<tr><td>Office Class*</td>";
    echo '<td><select name="Office" id="office"  style="width:160px" onchange="showOffice(this.value)"">';
    $sql="select * from bio_officeclass";
    $result=DB_query($sql,$db);
    echo'<option value=0></option>';
    while($row=DB_fetch_array($result))
    {  
        if ($row['id']==$officeclass)
        {
            echo '<option selected value="';
        } else {
            echo '<option value="';
        }
        echo $row['id'] . '">'.$row['officeclass'];
        echo '</option>';
    }
    echo'</select></td>';
    echo'</tr>';*/ 
    
        echo"<tr><td>Office*</td>";
    echo '<td><select name="office" id="office"  style="width:160px" onchange="showOffice(this.value)"">';
    $sql="select * from bio_office";
    $result=DB_query($sql,$db);
    echo'<option value=0></option>';
    while($row=DB_fetch_array($result))
    {  
        if ($row['id']==$officeclass)
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
    
      if($_GET['edit']!=''){
      echo"<tr><td>Team name</td>";
  echo"<td id=team><input type='hidden' name='Team' id='team' value='$teamname1'>$teamname1</td></tr>";
  } 
  else{
          echo"<tr><td>Team Name*</td>";
    echo '<td id=team><select name="Team" id="team"  style="width:160px" onclick="showalert()"">';
    $sql="select * from bio_leadteams";
    $result=DB_query($sql,$db);
    echo'<option value=0></option>';
    while($row=DB_fetch_array($result))
    {  
        if ($row['id']==$officeclass)
        {
            echo '<option selected value="';
        } else {
            echo '<option value="';
        }
        echo $row['teamid'] . '">'.$row['teamname'];
        echo '</option>';
    }
    echo'</select></td>';
    echo'</tr>'; 
  }  
    
    
    //echo '<tr id="showemp"><td>' . _('Designation*') . '</td>';
//    echo '<td><select name="Designation" id="designation"  style="width:150px">';
//    $sql="select * from bio_designation";
//    $result=DB_query($sql,$db);
//    echo'<option value=0></option>';
//    while($row=DB_fetch_array($result))
//    {
//        if ($row['desgid']==$_POST['designation'])
//        {
//    echo '<option selected value="';
//        } else {
//    echo '<option value="';
//        }
//    echo $row['desgid'].'">'.$row['designation'];
//        echo '</option>';
//    }
//    echo'</select></td>';
//    echo'</tr>';   
 
//echo"<tr><td>Start Date*</td>";
//echo"<td><input type='text' name='StartDate' id='startdate' class=date alt='".$_SESSION['DefaultDateFormat']. "' style='width:150px' value='".$DateString."'></td></tr>";

//echo"<tr><td>Due Date*</td>";
//echo"<td><input type='text' name='DueDate' id='duedate' class=date alt='".$_SESSION['DefaultDateFormat']. "' style='width:150px'></td></tr>";
  
   echo"<tr><td>Period*</td>
            <td>Year</td>
            <td>Month</td>
            
   </tr>";
  
  echo"<tr><td></td>";
  echo '<td><select name="Year" id="year"  style="width:90px">';  
   echo '<option value=0></option>'; 
 
  for($i=2012;$i<=2050;$i++)
  {
      
      
      if ($i==$year1)
        {
            echo '<option selected value="';
        } else {
            echo '<option value="';
        }
        echo $i . '">'.$i;
        echo '</option>';
     
   
  } echo'</select></td>';                              
   echo"";    
  echo '<td><select name="From" id="from"  style="width:100px" onchange="showMonth(this.value)">';
    $sql="select * from m_sub_season";
    $result=DB_query($sql,$db);
    echo'<option value=0></option>';
    while($row=DB_fetch_array($result))
    {
        if ($row['season_sub_id']==$month1)
        {
            echo '<option selected value="';
        } else {
            echo '<option value="';
        }
        echo $row['season_sub_id'] . '">'.$row['seasonname'];
        echo '</option>';
    } 
          if(!isset($_GET['edit']))
    {
          echo'<option  value=13>All</option>';   
    }
    echo'</select></td>';
  
//  echo '<td><select name="To" id="to"  style="width:100px" >';
//    $sql="select * from m_sub_season";
//    $result=DB_query($sql,$db);
//    echo'<option value=0></option>';
//    while($row=DB_fetch_array($result))
//    {
//        if ($row['season_sub_id']==$_POST['seasonname'])
//        {
//            echo '<option selected value="';
//        } else {
//            echo '<option value="';
//        }
//        echo $row['season_sub_id'] . '">'.$row['seasonname'];
//        echo '</option>';
//    }
//    echo'</select></td>';
    echo'</tr>';      
echo"</table>";
echo"<table id='modeamt'>";
echo"</table>"; 
echo"<table id='amt'>";
echo"</table>";
echo"</fieldset>";
echo'</div>';
echo"</td></tr></table>";
echo"</div>";
//========================================== 
echo"<table>";
echo'<tr><td colspan=2>';
echo'<div id="subassembly_select">';
echo "<fieldset style='width:600px' height:150px';>";   
echo "<legend><h3>Assign Task</h3>";
echo "</legend>"; 
echo "<table style='align:left' border=0>";
//echo "<tr><td>Enquiry Type<td>Task</td>";
//Feedstock
  echo '<td class="bycusttype">Enquiry Type<select name="enq" id="enq"  style="width:120px" onchange="showenquiry(this.value)">';
    echo '<option value=0></option>';   
    $sql1="select * from bio_enquirytypes";
    $result1=DB_query($sql1,$db);
    while($row1=DB_fetch_array($result1))
    {
    //  echo "<option value=$row1[enqtypeid]>$row1[enquirytype]</option>";
        if ($row1['enqtypeid']==$enquiry)
           {
             echo '<option selected value="';
           } else 
           { 
               echo '<option value="'; 
           }
           echo $row1['enqtypeid'] . '">'.$row1['enquirytype'];
           echo '</option>';  
    }
echo '</select></td>'; 
// echo '<td>Task<select name="Task" id="task" style="width:130px"></td>';    
// $sql1="SELECT * FROM bio_taskmaster";
//  $result1=DB_query($sql1, $db);
//  
//  $f=0;
//  while($myrow1=DB_fetch_array($result1))
//  {  
//  if ($myrow1['taskname_id']==$_POST['taskname']) 
//    {
//    echo '<option selected value="';
//    
//    } else {
//        if($f==0){echo '<option>';   }
//        echo '<option value="';
//    }
//    echo $myrow1['taskname_id'] . '">'.$myrow1['taskname']; 
//    echo '</option>' ;
//   $f++; 
//   }
// echo '</select>';
// echo "</td>";
// if(){
 echo '<td><input type="hidden" name="SelectedType" value='.$rid.'>&nbsp;</td>'; 
 
//  echo '<td id=taskid>Task<select name="task" id="task"  style="width:120px">';  
   // $sql="select * from bio_task";
//    $result=DB_query($sql,$db);
//    echo'<option value=0></option>';
//    while($row=DB_fetch_array($result))
//    {
//        if ($row['taskid']==$_POST['task'])
//        {
//            echo '<option selected value="';
//        } else {
//            echo '<option value="';
//        }
//        echo $row['taskid'] . '">'.$row['task'];
//        echo '</option>';
//    }
//    echo'</select></td>'; 



     
  echo '<td id=taskid>Task<select name="task" id="task"  style="width:120px" >';  
     $sql="select * from bio_task";
    $result=DB_query($sql,$db);
    echo'<option value=0></option>';
    while($row=DB_fetch_array($result))
    {
        
  
        if ($row['taskid']==$task1)
        {
            echo '<option selected value="';
        } else {
            echo '<option value="';
        }
        echo $row['taskid'] . '">'.$row['task'];
        echo '</option>';
    }
    echo'</select></td>';     

//    
// 
//
//    echo '<td>Value</td>';
//echo "<input type=text name='Number' id='number' style='width:90px' value=".$target."></td>";                     
//    
//}
// else{
//   echo '<td>Number of task</td>';
//echo "<input type=text name='Number' id='number' style='width:90px' value=".$target."></td>";                        
//     
// }
//   
    
 if($task1==18 OR $task1==27 OR $task1==3 OR $task1==2 OR $task1==5 OR $task1==0) {   
 echo "<td id='getvalue'>Number of task";
 }elseif($task1==28 OR $task1==20 OR $task1==26) {
     echo "<td id='getvalue'>Value";
 }else{
  echo "<td id='getvalue'>Number of task";    
     
 }
 
 
 echo "<input type=text name='Number' id='number' style='width:90px'  value=".$target."></td>";
  
  //echo "<td id='getvalue'></td>";
 //echo "<td><input type=text name='Number' id='number' style='width:110px'></td>";
  
  
  
 /*if($_GET['edit']==''){
     
    echo "<td>";
 echo '<input type="button" name="AddTask" id="addtask" value="Add" onclick="addSubAssemblies()">';
 echo "</td>"; 
 } */
    
 
 
 
 echo "</tr>";
  
 echo "</table>";
 echo"<div id='editfdstok'></div>";

echo"<div id='taskdiv'></div>";
echo"</fieldset>"; 
echo"</td></tr>"; 


echo'</div>';
echo'</td></tr>';
 
//========================================== Buttons 
echo'<table>';
echo'<tr><td colspan=2><p><div class="centre">
<input type=submit name=submit value="' . _('Assign') . '" onclick="if(log_in()==1)return false;">
<input type="Button" class=button_details_show name=details VALUE="' . _('Reports') . '">';
echo'</td></tr>';
echo'</div>'; 
echo"</td></tr></table>";
echo'</form>';
echo'</div>';  

//========================================== Buttons Ends
echo"<div id='selectiondetails'>";

echo"<fieldset style='width:500px; overflow:auto;'>";
echo"<legend><h3>Target Reports</h3></legend>";
echo '<table width="100%">
    <tr>
        <th width="50%">' . _('Reports') . '</th>
        <th width="50%">' . _('Reports') . '</th>
   
    </tr>';
echo"<tr><td  VALIGN=TOP >";
echo '<a  style=cursor:pointer; onclick="passid()">' . _('Teamwise Excel') . '</a><br>'; 
echo '<a href="bio_ptsexel_teamfull.php" style=cursor:pointer; >' . _('Teamwise Full Excel') . '</a><br>';   

echo"</td><td  VALIGN=TOP >";
echo '<a  style=cursor:pointer; onclick="passid1()">' . _('Officewise Excel') . '</a><br>';
echo '<a href="bio_ptsexel_officefull.php" style=cursor:pointer; >' . _('Officewise Full Excel') . '</a><br>'; 

echo"</td></tr>";
echo'</table>';
echo"</fieldset>";

echo "</div>";

//========================================== End of Details

echo'<div id="targetgrid">';
echo"<fieldset style='width:690px'><legend><h3>Target Details</h3></legend>"; 
echo "<div style='height:200px; width:100%; overflow:scroll;'>";   
 
echo"<table style='width:100%' border=0> ";   
echo"<tr><th>Slno</th>
<th>Team</th> 
<th>Designation</th>
<th>Task</th>
<th>Year</th>
<th>Month</th>
<th>Target</th> ";


 $sql8="SELECT bio_target.team_id,
               bio_target.targetid,
               bio_target.task,
               bio_target.year,
               bio_target.month,
               bio_target.target,
               bio_designation.designation,
               bio_dominst_task.inst_task,
               m_sub_season.seasonname,
               bio_leadteams.teamname
         FROM  bio_target,
               bio_teammembers,
               bio_designation,
               bio_emp,
               m_sub_season,
               bio_dominst_task,
               bio_leadteams
        WHERE  bio_teammembers.empid=bio_emp.empid
           AND bio_target.team_id=bio_teammembers.teamid
           AND bio_target.month=m_sub_season.season_sub_id  
           AND bio_emp.designationid=bio_designation.desgid  
           AND bio_target.task=bio_dominst_task.inst_id     
           AND bio_target.month=m_sub_season.season_sub_id
           AND bio_target.team_id=bio_leadteams.teamid";
 
 $resultl= DB_query($sql8,$db);
  
 
  $k=0 ;$slno=0;
  while($myrow=DB_fetch_array($resultl))
   {

       if ($k==1){
        echo '<tr class="EvenTableRows">';
        $k=0;
    } else {
        echo '<tr class="OddTableRows">';
        $k=1;
    }   $id=$myrow['targetid']; 
      $slno++;  
         echo'<td align=center>'.$slno.'</td>';
         echo'<td align=center>'.$myrow['teamname'].'</td>';
         echo'<td align=center>'.$myrow['designation'].'</td>';
         echo'<td align=center>'.$myrow['inst_task'].'</td>';  
         echo'<td align=center>'.$myrow['year'].'</td>';       
         echo'<td align=center>'.$myrow['seasonname'].'</td>';
         echo'<td align=center>'.$myrow['target'].'</td>'; 
         echo'<td><a href="#" id='.$id.' onclick="edit(this.id)">Edit</a></td>';  
   }
       
echo '<tbody>';
echo"</tr></tbody>

</table>"; 
 
echo"</div>";
echo"</fieldset>";  
echo'</div>';
echo"</td></tr></table>"; 
echo"</div>";


?>
<script type="text/javascript">


$(document).ready(function() {
    
     $("#selectiondetails").hide(); 
        
$('.button_details_show').click(function() {
  $('#selectiondetails').slideToggle('slow', function() {
    // Animation complete.
  });
}); 

//$("#error").fadeOut(3000);
 //   $("#warn").fadeOut(9000);
 //     $("#success").fadeOut(3000);
   //     $("#info").fadeOut(3000);
    //     $(".db_message").fadeOut(3200);
    
    
    
    $("#proposalgrid").hide();

    //$('#shwlead').click(function() {
//        $('#leadgrid').slideToggle('slow',function(){});
//        $('#proposalgrid').slideToggle('slow',function(){});
//    });

    $('#shwprint').click(function() {
        //$('#proposalgrid').slideToggle('slow',function(){});
       //$('#leadgrid').slideToggle('slow',function(){});
    });
    
    
    
});

 function showOffice(){
// 
    var desig=document.getElementById('designation').value;       
    var office=document.getElementById('office').value;/*alert(office);*/
   // var enquiry=document.getElementById('enquiry').value;   
    /*if(enquiry==0){
        alert("Select enquiry type"); 
        document.getElementById("enquiry").focus();  
        return false;
    }   */
 //alert(mobile); 
 //alert(enquiry);     
           
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
    
    document.getElementById("team").innerHTML=xmlhttp.responseText;  
    /*document.getElementById('loc').focus(); */
      
/*    var id=document.getElementById("office").value=xmlhttp.responseText;
    alert(id);
        if(id==0){
        /*alert("There is no office contain in the selected office class");*/
        document.getElementById("office").focus();  
         /*return false; 
      }
      else
      {
       document.getElementById("year").focus();   
      }*/
      
    }
    
  }
  xmlhttp.open("GET","bio_officechecking.php?desg=" + desig + "&office=" + office ,true);  
xmlhttp.send();   

 }
 
 function showalert()
 {
     var des=document.getElementById("designation").value;
     var off=document.getElementById("office").value;
     if(des==0){
         alert("Select designation");
         document.getElementById('designation').focus();
     }
         else if(off==0){
         alert("Select office");
         document.getElementById('office').focus();
     }
     
 }
 
 
 function showMonth(){
 
    var month=document.getElementById('from').value;
    var year=document.getElementById('year').value;  
    /*if(enquiry==0){
        alert("Select enquiry type"); 
        document.getElementById("enquiry").focus();  
        return false;
    }   */
//alert(month); 
//alert(year);     
           
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
    var id=document.getElementById("from").value=xmlhttp.responseText;
   // alert(id);
    if(id==0){
        alert("Please select current month or the next for target setting");
        document.getElementById("from").focus();  
         return false;  
      }
      
    }
    
  }
  xmlhttp.open("GET","bio_periodchecking.php?from=" + month + "&year=" + year,true);  
xmlhttp.send();   

 }
 
 
 
 
 function edit(str)
 {   // alert(str);
  // alert("yyyyyyy"); 
  
location.href="?edit=" +str;         
 
}




function log_in()
{
//    document.getElementById('phone').focus();
var f=0;
var p=0;
if(f==0){f=common_error('designation','Please select the designation');  if(f==1){return f; } }  
if(f==0){f=common_error('office','Please select the office');  if(f==1){return f; }  }  
if(f==0){f=common_error('team','Please select the team');  if(f==1){return f; }  }  
if(f==0){f=common_error('year','Please select the year');  if(f==1){return f; }  } 
if(f==0){f=common_error('from','select the month');  if(f==1){return f; }  }
//if(f==0){f=common_error('task','Please select the Task');  if(f==1){return f; }  }

           
if(f==0){f=common_error('enq','Please select the enquiry type');  if(f==1){return f; } }  
if(f==0){f=common_error('task','Please select the task');  if(f==1){return f; }  }           

  
if(f==0){var x=document.getElementById('number').value;  
            if(isNaN(x)||x.indexOf(" ")!=-1)
           {  f=1;
              alert("Please enter Numeric value for Number"); document.getElementById('number').focus();
              if(x=""){f=0;}
              return f; 
           }          
} 
}

function getUnit(str1){

str1=document.getElementById("subassembly").value;
//alert(str1) ;
if (str1=="")
  {
  document.getElementById("getunit").innerHTML="";
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
    document.getElementById("getunit").innerHTML=xmlhttp.responseText;  
    document.getElementById('loc').focus(); 
    }
  } 
xmlhttp.open("GET","bio_target_getunit.php?p=" + str1,true);
xmlhttp.send(); 
}

function addSubAssemblies()
{

    var str1=document.getElementById("task").value;
    var str2=document.getElementById("number").value;
    var enquiry=document.getElementById("enq").value;  
   //var str3=document.getElementById("number1").value;     
  // alert(str1);
//   alert(str2);
    //alert(str3);
if(str1=="")
{
alert("Select Sub Assembly"); document.getElementById("taskid").focus();  return false;  
}

if(str2=="")
{
alert("Select Quantity"); document.getElementById("number").focus();  return false;  
}

//if(str3=="")
//{
//alert("Select Quantity"); document.getElementById("number2").focus();  return false;  
//}


if (str1=="")
  {
  document.getElementById("taskdiv").innerHTML="";     //editleads
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
    document.getElementById("taskdiv").innerHTML=xmlhttp.responseText;
    document.getElementById('taskid').value="";       document.getElementById('number').value="";
    }
  } 
xmlhttp.open("GET","bio_task_target.php?taskid=" + str1  + "&number=" + str2 + "&enquiry=" + enquiry,true);
xmlhttp.send();    

}

function editsubassembly(str)
{
   //alert("hii");
//var str1=document.getElementById("hfeedstock").value;
//var str2=document.getElementById("hfeedweight").value;
//alert(str);
 var enquiry=document.getElementById("enq").value; 
if (str=="")
  {
  document.getElementById("editsub").innerHTML="";
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
    document.getElementById("editsub").innerHTML=xmlhttp.responseText;
//    document.getElementById('loc').focus(); 
    }
  } 
xmlhttp.open("GET","bio_task_target.php?tempid=" + str+ "&enquiry=" + enquiry,true);
xmlhttp.send();    

}

function doedit()
{
//   alert("hii");
//   alert(str);

var str=document.getElementById("subasstempid").value;    
var str1=document.getElementById("subassemblyid").value;
var str2=document.getElementById("subquantity").value;
 var enquiry=document.getElementById("enq").value; 
// alert(str); alert(str1);     alert(str2); 
if (str=="")
  {
  document.getElementById("taskdiv").innerHTML="";
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
    document.getElementById("taskdiv").innerHTML=xmlhttp.responseText;
    $('#subquantity').focus(); 
    }
  } 
xmlhttp.open("GET","bio_task_target.php?editid=" + str + "&editsub=" + str1 + "&editqty=" + str2+ "&enquiry=" + enquiry,true);
xmlhttp.send();    

}  

function deletsubassembly(str)
{
//   alert("hii");
//   alert(str);
// alert(str); alert(str1);     alert(str2); 
var enquiry=document.getElementById("enq").value; 
if (str=="")
  {
  document.getElementById("taskdiv").innerHTML="";
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
    document.getElementById("taskdiv").innerHTML=xmlhttp.responseText;

    }
  } 
xmlhttp.open("GET","bio_task_target.php?deletid=" + str+ "&enquiry=" + enquiry,true);
xmlhttp.send();    

}

 

function showSubassemblies(str){ //var a="#"+str;
//$(a).hide();

str=2;

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
    
    document.getElementById("subassembly_select").innerHTML=xmlhttp.responseText; 
     
    }
  }

xmlhttp.open("GET","bio_task_target.php" ,true);
xmlhttp.send();
}

function subname(str)
{
var subname=document.getElementById("task").value; 

if(subname!="no")       {
var newstr=subname + ',' + str;  
   
}else   {
    var newstr=str; ;
}
if (str=="")
  {
  document.getElementById("subassemblies").innerHTML="";
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
    document.getElementById("subassemblies").innerHTML=xmlhttp.responseText;

    }
  } 
xmlhttp.open("GET","bio_task_target.php?id=" + newstr,true);
xmlhttp.send();    

}  




//function displayTeams(str1)
//{
//alert(str1);
//if (str1=="")
//  {
//  document.getElementById("showemp").innerHTML="";     //editleads
//  return;
//  }
//if (window.XMLHttpRequest)
//  {// code for IE7+, Firefox, Chrome, Opera, Safari
//  xmlhttp=new XMLHttpRequest();
//  }
//else
//  {// code for IE6, IE5
//  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");                                                          
//  }
//xmlhttp.onreadystatechange=function()
//  {//alert(str1);
//  if (xmlhttp.readyState==4 && xmlhttp.status==200)
//    {     //alert("sss");
//    document.getElementById("showemp").innerHTML=xmlhttp.responseText;

//             $('#showmembers').show(); 

//    document.getElementById('loc').focus(); 
//    }
//  } 
//xmlhttp.open("GET","bio_showteam.php?teamid=" + str1,true);
//xmlhttp.send();    

//}   

function passid(str1,str2){
//    alert("WWWWWWWWW");
//alert(str1);
//if (str1=="")
//  {
//  document.getElementById("panel").innerHTML="";
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
    document.getElementById("panel1").innerHTML=xmlhttp.responseText;  
    document.getElementById('team').focus(); 
    }
  } 
xmlhttp.open("GET","bio_target_team.php",true);
xmlhttp.send(); 
}


function passid1(str1,str2){
//  {
//  document.getElementById("panel").innerHTML="";
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
    document.getElementById("panel1").innerHTML=xmlhttp.responseText;  
    document.getElementById('team').focus(); 
    }
  } 
xmlhttp.open("GET","bio_target_office.php",true);
xmlhttp.send(); 
}
//function viewexel()

  function showenquiry(str){
   
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
    document.getElementById("taskid").innerHTML=xmlhttp.responseText;

    }
  }   
      
  xmlhttp.open("GET","bio_target_enquirytype.php?enqid=" + str,true);
xmlhttp.send();    
          
  }

//function showValue(str)
//{ 
//    alert(str);
//  if(str==2){
// xmlhttp.open("GET","bio_target_value.php?enqid=" + str,true);
//xmlhttp.send();       
//         
//  }

//}







  function showValue(str){  
   
//alert(str);
if (str=="")
  {
  document.getElementById("getvalue").innerHTML="";
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
    document.getElementById("getvalue").innerHTML=xmlhttp.responseText;  
    }
  } 
xmlhttp.open("GET","bio_target_value.php?task=" + str,true);
xmlhttp.send(); 
}
 
</script>




                                  

