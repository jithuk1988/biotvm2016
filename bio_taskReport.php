<?php
 $PageSecurity = 80;
 include('includes/session.inc');
 $title = _('Task Report'); 
 $pagetype=3; 
 include('includes/header.inc');
 include('includes/sidemenu.php');
 echo '<center><font style="color: #333;
    background:#fff;
    font-weight:bold;
    letter-spacing:0.10em;
    font-size:16px;
    font-family:Georgia;
    text-shadow: 1px 1px 1px #666;">TASK REPORT</font></center>';
      
//==========================================================================  
    
echo "<div id=fullbody>";
echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "?" . SID . "'>"; 
echo '<table><tr>';
 
echo'<td valign=top>';    
echo"<fieldset style='width:700px;height:150px'; overflow:auto;'>";
echo"<legend><h3>Select Task</h3></legend>";
echo"<table>";

echo"<tr><td>Escalated</td><td>Date From</td><td>Date To</td><td>Enquiry Type</td><td>Task</td><td>Office</td><td>Team</td></tr>"; 
echo"<tr>";
echo'<td><input type="radio" name="escalated" id="escalated" style="width:60px" onclick=check()></td>'; 

   echo'<td><input type="text" id="datefrm" class=date alt='.$_SESSION['DefaultDateFormat']. ' name="datefrm" onChange="return isDate(this, this.value, '."'".$_SESSION['DefaultDateFormat']."'".')'.'"'.' value='.date("d/m/Y").'></td>';
   echo'<td><input type="text" id="dateto" class=date alt='.$_SESSION['DefaultDateFormat']. ' name="dateto" onChange="return isDate(this, this.value, '."'".$_SESSION['DefaultDateFormat']."'".')'.'"'.' value='.date("d/m/Y").'></td>';

   echo '<td class="bycusttype"><select name="enq" id="enq" style="width:100px" onchange=taskselection(this.value)>';
    echo '<option value=0></option>';   
    $sql1="select * from bio_enquirytypes";
    $result1=DB_query($sql1,$db);
    while($row1=DB_fetch_array($result1))
    {
    //  echo "<option value=$row1[enqtypeid]>$row1[enquirytype]</option>";
        if ($row1['enqtypeid']==$_POST['enq'])
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

    echo '<td id=respectivetask><select name="task" id="task"  style="width:100%">';  
    $sql="select * from bio_task";
    $result=DB_query($sql,$db);
    echo'<option value=0></option>';
    while($row=DB_fetch_array($result))
    {
        if ($row['taskid']==$_POST['task'])
        {
            echo '<option selected value="';
        } else {
            echo '<option value="';
        }
        echo $row['taskid'] . '">'.$row['task'];
        echo '</option>';
    }
    echo'</select></td>';  
     
    echo '<td><select name="office" id="office"  onchange="officeteam(this.value)" style="width:100%">';  
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

    
    echo '<td id="leadteam1"><select name="teamname" id="leadteam"  style="width:100%">';
    $sql="select * from bio_leadteams";
    $result=DB_query($sql,$db);
    echo'<option value=0></option>';
    while($row=DB_fetch_array($result))
    {
        if ($row['teamid']==$_POST['teamname'])
        {
    echo '<option selected value="';
        } else {
    echo '<option value="';
        }
    echo $row['teamid'].'">'.$row['teamname'];
        echo '</option>';
    }
    echo'</select></td>';

echo '</tr>'; 
echo"</table>";


echo"<table>"; 
echo '<tr>';
echo '<td><a style=cursor:pointer; onclick=passid(1)>' . _('Task Completed') . '</a></td></tr>';  
echo '<tr><td><a style=cursor:pointer; onclick=passid(0)>' . _('Task Pending') . '</a></td>';
echo'</tr>';
echo"</table>";
echo"</fieldset>"; 



?>

<script type="text/javascript">

function check()
{

 var str=document.getElementById('leaddate').checked;
 if(str==true)
 {
       
   document.getElementById('datefrm').disabled=true;
   document.getElementById('dateto').disabled=true;

 }
 }

function officeteam(str)
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
    document.getElementById("leadteam1").innerHTML=xmlhttp.responseText;

    }
  }
xmlhttp.open("GET","bio_taskExport_officeteam.php?officeid=" + str,true);
xmlhttp.send();

}

function taskselection(str)
{
    //document.getElementById("enqid").value=str;  
    //alert(str);
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
    {     // alert("ddd");
    document.getElementById("respectivetask").innerHTML=xmlhttp.responseText;

    }
  }
xmlhttp.open("GET","bio_taskExport_officeteam.php?enqid=" + str,true);
xmlhttp.send();
}

function passid(str){
        
 var str1=document.getElementById('escalated').checked;     
  if(str1==true)
 {
     str1=1;   //alert(str1);
 }else{
     str1=0;   //alert(str1);
 }      
  
 var str2=document.getElementById('task').value;   
 var str3=document.getElementById('office').value; 
 var str4=document.getElementById('leadteam').value;  
 var str5=document.getElementById('enq').value;
 var str6=document.getElementById('datefrm').value; 
 var str7=document.getElementById('dateto').value;  
 
window.location="bio_taskExport.php?status=" + str + "&escalated=" + str1 + "&task=" + str2 + "&office=" + str3 + "&teamname=" + str4 + "&enq=" +str5 + "&datefrm=" + str6 + "&dateto=" +str7; 
}

</script>

                                  

