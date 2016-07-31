<?php
$PageSecurity = 80;
 include('includes/session.inc');
 $title = _('Achievement Marketing'); 
 include('includes/header.inc');
 include('includes/sidemenu.php');
 echo '<center><font style="color: #333;
    background:#fff;
    font-weight:bold;
    letter-spacing:0.10em;
    font-size:16px;
    font-family:Georgia;
    text-shadow: 1px 1px 1px #666;">ACHIEVEMENT POLICY FOR MARKETING STAFF </font></center>'; 
    /*echo "<div id='ach'>" ; */
  if($_GET['edit']!="")
  {   
      $rid=$_GET['edit']; 
      $sql4="SELECT * from  bio_achievementpolicy where  bio_achievementpolicy.id=$rid";
     $result4=DB_query($sql4,$db); 
      $myrow4=DB_fetch_array($result4); 
      $desg=$myrow4['designation'] ; 
      $enqtypee=$myrow4['enquiry_type'] ;  
      $task=$myrow4['taskid'] ;  
      $ff=$myrow4['rangefrom'] ; 
      $tt=$myrow4['rangeto'] ;
      $incen=$myrow4['incentive'] ;          
      $edate=$myrow4['effectivedate'] ;         
      
  }
 if(isset($_POST['submit']))
 {
/*  $_POST['enq_type'];
  $_POST['design']; 
  $_POST['f']; 
  $_POST['t'];       
   $_POST['incen']; 
       $_POST['task'];*/
   $actualdate=FormatDateForSQL($_POST['caldate']); 
  
 $inta='INSERT INTO `bio_achievementpolicy`( `effectivedate`, `designation`, `taskid`, `rangefrom`, `rangeto`, `incentive`,`enquiry_type`) VALUES ("'.$actualdate.'",'.$_POST['design'].','.$_POST['task'].','.$_POST['f'].','.$_POST['t'].','.$_POST['incen'].','.$_POST['enq_type'].')';
     DB_query($inta,$db);
 }
  if(isset($_POST['edit']))
 {    $actualdate=FormatDateForSQL($_POST['caldate']); 
     
  $upsql='UPDATE `bio_achievementpolicy` SET `effectivedate`="'.$actualdate.'",`designation`='.$_POST['design'].',`taskid`='.$_POST['task'].',`rangefrom`='.$_POST['f'].',`rangeto`='.$_POST['t'].',`incentive`='.$_POST['incen'].',`enquiry_type`='.$_POST['enq_type'].' WHERE id='.$_POST['eid'].'  ';
     DB_query($upsql,$db); 
 }
  echo'<table ><tr><td>'; 
echo'<div >'; 
echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "'>"; 
echo"<fieldset style='width:700px; overflow:auto;'>";  
echo"<legend><h3>Achievement select </h3></legend>";
echo"<table >"; 
echo"<tr><td>Enquiry Type</td>";
echo"<td><select name='enq_type' id='enq_type' style='width:150px' onchange='showinrow()'> ";
echo '<option value=0></option>';   
    $sql1="select * from bio_enquirytypes";
    $result1=DB_query($sql1,$db);
    while($row1=DB_fetch_array($result1))
    {
       
    if ($row1['enqtypeid']==$enqtypee)
        {  
    echo '<option selected value="';
        } else {
    echo '<option value="';
        } 
        echo $row1['enqtypeid'] . '">'.$row1['enquirytype'];
        echo '</option>'; 
  
    }
echo '</select></td>'; 
 echo '<tr><td id=taskid>Task</td><td><select name="task" id="task"  style="width:150px" >';  
     $sql="select * from bio_dominst_task";
    $result=DB_query($sql,$db);
    echo'<option value=0></option>';
    while($row=DB_fetch_array($result))
    {

    if ($row['inst_id']==$task)
        {
    echo '<option selected value="';
        } else {
    echo '<option value="';
        }
            echo $row['inst_id'] . '">'.$row['inst_task'];
        echo '</option>';
        
    }
echo'</select></td></tr>'; 
if($rid==null)
{  
echo"<tr id='inrow3'></tr>"; 
}
else
{  
echo"<tr id='inrow3'><td>Designation</td>";
echo"<td><select name='design' id='design' style='width:150px'> ";
echo '<option value=0></option>';   
      $sql2="select * from bio_designation  ";  
    $result2=DB_query($sql2,$db);
    while($row=DB_fetch_array($result2))
    {

    if ($row['desgid']==$desg)
        {
    echo '<option selected value="';
        } else {
    echo '<option value="';
        }
    echo $row['desgid'].'">'.$row['designation'];
        echo '</option>';
    }
echo '</select></td></tr>';      
}
echo'<tr><td>Achievement Range</td><td><input type="text" name="f" id="f" value="'.$ff.'" size="8" > to <input type="text" name="t" id="t" value="'.$tt.'" size=8></tr>';
echo'<tr><td>Incentive </td><td><input type="text" name="incen" id="incen" value="'.$incen.'" style=width:150px></td></tr>';
if($rid==null)
{
  echo'<tr><td>Effective Date </td><td><input type="text" name="caldate" id="caldate" class=date alt="'.$_SESSION['DefaultDateFormat']. '"   style=width:150px></td></tr>';  
}
  else
  {
      echo'<tr><td>Date </td><td><input type="text" name="caldate" id="caldate" class=date alt="'.$_SESSION['DefaultDateFormat']. '"   value="'.convertSQLDate($edate).'" style=width:150px></td></tr>';                                                                                                          
      
  }
if($rid==null)
{ 
echo'<tr><td></td><td><input type=submit name=submit value="submit" onclick="return test();" ></td></tr>';
}
else
{
echo'<tr><td></td><td><input type=submit name=edit value="Edit" onclick="return test();" ></td></tr>';       
}
  echo "<input type='hidden' name='eid' id='eid' value=$rid >";     
         
echo"</table>";
echo"</fieldset>";         
echo"</form>";
echo'</div >'; 
echo'<div id="Achievement">';
echo"<fieldset style='width:85%'><legend><h3>Achievement List</h3></legend>"; 
echo "<div style='height:200px; width:100%; overflow:scroll;'>";  
echo"<table style='width:100%' border=0> ";  
echo"<tr><th>Slno</th><th>Enquiry Type</th><th>Task</th><th>Designation</th><th>From</th><th>To</th><th>Incentive</th><th>Date</th><th>Edit</th></tr>";  
 $sql3="SELECT bio_achievementpolicy.id, bio_enquirytypes.enquirytype, bio_dominst_task.inst_task, bio_designation.designation, bio_achievementpolicy.rangefrom, bio_achievementpolicy.rangeto, bio_achievementpolicy.incentive, bio_achievementpolicy.effectivedate
FROM bio_achievementpolicy
INNER JOIN `bio_enquirytypes` ON ( bio_enquirytypes.enqtypeid = bio_achievementpolicy.enquiry_type )
INNER JOIN `bio_dominst_task` ON ( bio_achievementpolicy.taskid = bio_dominst_task.inst_id )
INNER JOIN `bio_designation` ON ( bio_achievementpolicy.designation = bio_designation.desgid )
LIMIT 0 , 300  ";
$result3=DB_query($sql3,$db);  
$slno=1; $k=1;
    while($row3=DB_fetch_array($result3))
    {
        if ($k==1){
        echo '<tr class="EvenTableRows">';
        $k=0;
    } else {
        echo '<tr class="OddTableRows">';
        $k=1;
    }    $id=$row3[id];
echo"<td align=center>$slno</td>
<td align=center>".$row3[enquirytype]."</td>
<td align=center>".$row3[inst_task]."</td>
<td align=center>".$row3[designation]."</td> 
<td align=center>".$row3[rangefrom]."</td>
<td align=center>".$row3[rangeto]."</td>
<td align=center>".$row3[incentive]."</td>
<td align=center>".convertSQLDate($row3[effectivedate])."</td>";
echo'<td align=center><a href="#" id='.$id.' onclick="edit(this.id)">Edit</a></td>'; 
$slno++;

    }
echo '<tbody>';
echo"</tr></tbody></table>";
echo"</div>";
echo"</fieldset>";  
echo'</div>';


?>

<script>
 function edit(str)
 {   //alert(str);
 location.href="?edit=" +str;         
 
}
function showinrow(){   
    
 var   str=document.getElementById("enq_type").value; //  alert(str);
 // var   desgg=document.getElementById("des").value;   alert(desgg);
//alert(str1);   alert(str2);       alert(str3);
if (str=="")
  {
  document.getElementById("inrow3").innerHTML="";
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
     document.getElementById("inrow3").innerHTML=xmlhttp.responseText; 
       
    }
  } 
xmlhttp.open("GET","bio_ajax_ar.php?en_type=" + str ,true);
xmlhttp.send(); 
} 

function test()
{
        var etype=document.getElementById('enq_type').value; 
                if(etype==0)
        {   alert("select Enquiry Type ");
                document.getElementById("enq_type").focus();  
        return false; 
        }     //alert(etype) ;   
   
var task=document.getElementById('task').value;    //  alert(task) ;   
        if(task==0)
        {
          alert("select Task type");
           document.getElementById("task").focus(); 
        return false;   
        }         var design=document.getElementById('design').value;
 if(design==0)
        {
          alert("select Designation  type");
           document.getElementById("design").focus(); 
        return false;   
        }          var from=document.getElementById('f').value; 
 if(from=="")
        {
          alert("Enter the From value ");
           document.getElementById("f").focus();        
        return false;   
        }             var to=document.getElementById('t').value;
 if(to==0)
        {
          alert("Enter the TO value");
           document.getElementById("t").focus();        
        return false;   
        }                var incen=document.getElementById('incen').value;    
 if(incen==0)
        {
          alert("Enter Incentive Amount");
           document.getElementById("incen").focus();        
        return false;   
        }   var caldate=document.getElementById('caldate').value;  
        if(caldate==0)
        {
            alert("Select Effective Date") ;
            document.getElementById("caldate").focus();
            return false;
        } 
                      
        
        
                         
          
}
</script>
