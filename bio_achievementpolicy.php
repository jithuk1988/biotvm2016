<?php
$PageSecurity = 80;
include('includes/session.inc');


$title = _('Achievement policy');  
include('includes/header.inc');
echo '<center><font style="color: #333;
    background:#EBEBEB;
    font-weight:bold;
    letter-spacing:0.10em;
    font-size:16px;
    font-family:Georgia;
    text-shadow: 1px 1px 1px #666;">Achievement Policy</font></center>';
 $sql="SELECT * FROM bio_designation WHERE desgcode IN('BH','BDE','BDM')";
 $result=DB_query($sql,$db);

  if(isset($_POST['submit'])){ 
  $effdate=FormatDateForSQL($_POST['effectivedate']);     
    for($i=0;$i<8;$i++){
 $sql1="INSERT INTO 
                            bio_achievementpolicy (effectivedate,designation,taskid,rangefrom,incentive) 
                            VALUES ('".$effdate."',
                            '".$_POST[designation]."',
                            '".$_POST[task]."',
                            '".$_POST['rangefrom'.$i]."',                            
                            '".$_POST['incentive'.$i]."') ";
                            
   $result1=DB_query($sql1,$db); 
  
    } prnMsg(_('new row added') ,'success');       
 }
 
 if(isset($_POST['update'])){ 
     $effdate=FormatDateForSQL($_POST['effectivedate']);  
     if($effdate=='--'){$effdate=$_POST['date1'];}   
    for($i=0;$i<8;$i++){
                        $sql1="UPDATE  bio_achievementpolicy 
                                                         SET effectivedate='".$effdate."',
                                                         rangefrom=".$_POST['rangefrom'.$i].",
                                                         incentive= ".$_POST['incentive'.$i]."                         
                                                         WHERE id='".$_POST['id'.$i]."'";
                                                                                          
                                                                
   $result1=DB_query($sql1,$db);  
    } prnMsg(_('Update') ,'success');       
 }
     
   if(isset($_POST['Newcreate'])){ 
  $effdate=FormatDateForSQL($_POST['effectivedate']);     
    for($i=0;$i<8;$i++){
 $sql1="INSERT INTO 
                            bio_achievementpolicy (effectivedate,designation,taskid,rangefrom,incentive) 
                            VALUES ('".$effdate."',
                            '".$_POST[designation]."',
                            '".$_POST[task]."',
                            '".$_POST['rangefrom'.$i]."',                            
                            '".$_POST['incentive'.$i]."') ";
                            
   $result1=DB_query($sql1,$db); 
    
    }prnMsg(_('new row added') ,'success');     
 }    
 
echo '<form action="' . $_SERVER['PHP_SELF'] . '" method="post" style="background:#EBEBEB;">';

 echo '<table class="selection" style="background:#EBEBEB;width:500px;">'; 
 
 echo '<tr>
 <td style="width:14%">Effective Date*</td>
 <td style="width:50%">Designation</td>
 
 <td style="width:50%">Task</td></tr>';
 echo '<tr>';  
echo"<td style='width:14%'><input type='text' name='effectivedate' id='effectivedate' class=date alt='".$_SESSION['DefaultDateFormat']. "' style='width:146px' ></td>";
 echo '<td style="width:14%">';
 echo '<select name="designation" id="designation" style="width:75%" >';
 echo '<option id=0></option>';
 while($row=DB_fetch_array($result)){
 echo '<option value='.$row['desgid'].'>'.$row['designation'].'</option>';
 }
 echo '</select>';
 echo '</td>';
 
 
 echo '<td style="width:14%">';
 echo '<select name="task" id="task" style="width:75%" onchange="viewgrid(this.value)">';
  echo '<option id=0></option>'; 
 echo '<option value=1>Sale Order Value</option>';
 echo '<option value=2>Feasibility Charge</option>';
 echo '</select>';
 echo '</td>';
 
 echo '</tr>';
  
 echo '</table>';
 


echo '<div style="background-color:#EBEBEB" id="achievedetails" name="achievedetails">';
    
echo '<div>';


echo '</form>';
?>
<script type="text/javascript">
function viewgrid(str){ 
var des=document.getElementById("designation").value;
var date=document.getElementById("effectivedate").value;
if (str=="")
  {
  document.getElementById("startrange").innerHTML="";
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
    {                 //  alert(str); 
    document.getElementById("achievedetails").innerHTML=xmlhttp.responseText;     
    }
  }
xmlhttp.open("GET","bio_achievementpolicyview.php?task=" + str + "&designation=" + des + "&date=" + date,true);
xmlhttp.send();
}

function add(str){



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
    document.getElementById("achievedetails").innerHTML=xmlhttp.responseText;
//if(str==2){getservice(str);}
    }
  }
xmlhttp.open("GET","bio_achievementpolicyedit.php?number=" + str,true);
xmlhttp.send();


}


function edit(str,str1,str2)
{
   
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
    document.getElementById("achievedetails").innerHTML=xmlhttp.responseText;
//if(str==2){getservice(str);}
    }
  }
xmlhttp.open("GET","bio_achievementpolicyedit.php?task=" + str + "&designation=" + str1 + "&date=" + str2,true);
xmlhttp.send();
}

function dlt(str,str1,str2)
{
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
    document.getElementById("achievedetails").innerHTML=xmlhttp.responseText;
//if(str==2){getservice(str);}
    }
  }
xmlhttp.open("GET","bio_achievementdelete.php?task=" + str + "&designation=" + str1 + "&date=" + str2,true);
xmlhttp.send();
}
function log_in()
{  //  alert("sss"); alert(mail);
var f=0;                                                  //State
//var hlol=document.getElementById('hlol').value;
// alert(hlol);
if(f==0){f=common_error('effectivedate','Please Select the date');  if(f==1) { return f; }}
}
</script>