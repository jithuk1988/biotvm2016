<?php
$PageSecurity = 40;
include('includes/session.inc');
$title = _('Periodic maintanance scheduling');
include('includes/header.inc'); 
echo '<p class="page_title_text"><img src="'.$rootpath.'/css/'.$theme.'/images/customer.png" title="' . _('Periodic maintanance scheduling')
	. '" alt="" />' . _('Periodic Maintenance') . '</p>';
    if( $_POST['submit_new'] ){
        
        for($i=1;$i<=4;$i++){
            $prev=$i-1;//$_POST['new_days'.$i]!=NULL &&
            if(is_numeric($_POST['new_days'.$i])){
            if( $_POST['new_des'.$i]!=NULL && $_POST['new_days'.$i]!=NULL){
        
                $sql_new_pm="insert into bio_def_cstask (cstype,stockcode,taskno,taskdescription,daystocomplete,prevtask)
            VALUES ('".$_POST['cstype']."','".$_POST['plantname']."' ,'".$i."' ,  '".$_POST['new_des'.$i]."' , '".$_POST['new_days'.$i]."' , '".$prev."' )";
             DB_query($sql_new_pm,$db);
            }
            else{
                ?>
                <script>
                alert("Please enter day and description ");
                </script>
                <?php
            }
            }elseif($_POST['new_days'.$i]!=NULL){
                
                ?>
                <script>
                alert("Please enter intiger value ");
                </script>
                <?php
            }

            
        }
       $plant=$_POST['plantname'];
        $planttype=$_POST['MainCategoryID'];
        $prev_task=$_POST['task2'];
     $cstype=$_POST['cstype'];
    }
    if($_POST['submit'])
    {
          $taskno=$_POST['count'];
         $prev=$taskno-1;
         $sql_new_pm="insert into bio_def_cstask (cstype,stockcode,taskno,taskdescription,daystocomplete,prevtask) 
         VALUES ('".$_POST['cstype']."','".$_POST['plantname']."' , '".$taskno."' , '".$_POST['perio_des']."' , '".$_POST['perio_days']."', '".$prev."' )" ;
         DB_query($sql_new_pm,$db);
         
         $plant=$_POST['plantname'];
        $planttype=$_POST['MainCategoryID'];
        $prev_task=$_POST['task2'];
       $cstype=$_POST['cstype'];
        
    }
    
     if($_POST['edit'])
    {
          $taskno=$_POST['tno'];
         $sql_edit_pm="UPDATE bio_def_cstask SET  taskdescription='".$_POST['perio_des']."' , 
         daystocomplete='".$_POST['perio_days']."' WHERE cstype='".$_POST['cstype']."' AND stockcode='".$_POST['plantname']."' AND taskno= '".$_POST['tno']."' ";
         DB_query($sql_edit_pm,$db);
         
         $plant=$_POST['plantname'];
        $planttype=$_POST['MainCategoryID'];
        $prev_task=$_POST['task2'];
         $cstype=$_POST['cstype'];  
    }
    
    
    echo "<form action=" . $_SERVER['PHP_SELF'] . "?" . SID ." method=post>";
    if(isset($_GET['main'])||isset($_GET['main1']))
{
    $plant=$_GET['sub'];
    if($_GET['main']){$planttype=$_GET['main'];}
    if($_GET['main1']){$planttype=$_GET['main1'];} 
    $cstype=$_GET['cstype'];
    echo"<input type=hidden name=tno value='".$_GET['tno']."'>";
    echo"<input type=hidden name=sub value='".$_GET['sub']."'>";
    echo"<input type=hidden name=cstype value='".$_GET['cstype']."'>";
    if(isset($_GET['delete']))
    {
        $sql_delete="DELETE FROM bio_def_cstask WHERE stockcode='".$plant."' AND cstype='".$cstype."' AND taskno='".$_GET['tno']."' ";
         $result_delete=DB_query($sql_delete,$db);
    }
}

//echo $plant;
//echo $prev_task;
//echo $planttype;
 echo"<table width='1000px'><tr><td>";
 echo"<fieldset><legend>Select Plant Catogery</legend>";
 
 
  echo"<table>";
  
  echo'<tr><td>Select catagory:</td>';
  echo'<td><select name=cstype id=cstype onchange=showschedule(this.value);>';
  if($cstype==3){
    
echo'<option value=2>Warrenty</option>
<option selected value=3>AMC</option>
</select></td>';  
  }else{
     echo'<option selected value=2>Warrenty</option>
<option value=3>AMC</option>
</select></td>';  
  }

  
  
 $sql="SELECT  categoryid,categorydescription,maincatid from stockcategory,
             bio_maincat_subcat
             WHERE stockcategory.categoryid= bio_maincat_subcat.subcatid
             AND bio_maincat_subcat.maincatid =1
             order by stockcategory.categorydescription asc";
      $result=DB_query($sql,$db);
echo'<td>Category :</td><td><select name="MainCategoryID" id="maincatid" onchange="Filtercategory()">';
//      echo '<option value=0>Select category</option>';   

    
    $f=0;
  while($myrow=DB_fetch_array($result))
  {  //echo$myrow['count_id'];
      
  if ($myrow['categoryid']==$planttype)  
    {         //echo $myrow1['cid'];     
    echo '<option selected value="';
    } else 
    {
    if ($f==0) 
        {
        echo '<option>';
        }
        echo '<option value="';
    }
    echo $myrow['categoryid'] . '">'.$myrow['categorydescription'];
    echo '</option>';
    $f++;
   } 
  echo '</select></td>
  
  
  <td>Plant :</td><td id=plant >';
  if($plant!=NULL){
  $sql="SELECT stockmaster.stockid,stockmaster.description FROM stockmaster WHERE stockmaster.categoryid='".$planttype."'";
  $result=DB_query($sql,$db);
  echo'<select id="plantname" name="plantname" onchange=showschedule(this.value);>';//
    $f=0;
  while($myrow=DB_fetch_array($result))
  {  
      
  if ($myrow['stockid']==$plant)  
    {         //echo $myrow1['cid'];     
    echo '<option selected value="';
    } else 
    {
    if ($f==0) 
        {
        echo '<option>';
        }
        echo '<option value="';
    }
    echo $myrow['stockid'] . '">'.$myrow['description'];
    echo '</option>';
    $f++;
   } 
  echo '</select>';

  }
  else{
     echo'<input type="text">'; 
  }
  
  echo'</td>';


   echo'</table>';    
    echo'</table>'; 
    
      echo'<table ><td id="schedule">';
      if($plant!=NULL){
          echo"<fieldset ><legend>Periodic Maintenance Tasks</legend>";
   echo'<table width=1000px><tr><th>Sl No.</th><th>Days to start from installation </th><th>Description</th></tr>';

   $sql="SELECT *FROM bio_def_cstask where stockcode='".$plant."' AND cstype='".$cstype."' ";
   $result=DB_query($sql,$db);
   $k=0;
    $slno=1;
    $i=0;
    
    while($myrow=DB_fetch_array($result)){
        $i++;
          if ($k==1)
          {
            echo '<tr class="EvenTableRows">';
            $k=0;
          }else 
          {
            echo '<tr class="OddTableRows">';
            $k=1;     
          }
          echo'<td>'.$slno.'</td>';
          
        echo"<td><input type='text' id='comdays".$i."' value=".$myrow['daystocomplete']." name='comdays".$i."'></td>";
        echo"<td><input type='text' size='70px' id='des".$i."' value='".$myrow['taskdescription']."' name='des".$i."'></td>";
        //$SelectedType=$_GET['plantname']&$myrow['taskno'];
        echo" <td width='50px'><a style='cursor:pointer;' id='".$myrow['taskno']."' onclick=edit_task(this.id);>" . _('Edit') . "</a></td>";
                echo'<td><a href="bio_periodic_maintenance.php?tno='.$myrow['taskno'].'&main1='.$planttype.'&sub='.$plant.'&cstype='.$cstype.'&delete=yes" onclick=\'return confirm("' .
                _('Are you sure you wish to delete this Office?') . '");\'>' . _('Delete') . '</td>';             
        $slno++;
    }
echo'<input type="hidden" value='.$slno.' id="count" name="count">';
   
    if($_GET['main']){
        $sql_editvalue="SELECT taskdescription, daystocomplete from bio_def_cstask where cstype='".$cstype."' AND stockcode='".$_GET['sub']."'  AND taskno='".$_GET['tno']."'";
        $result_editvalue=DB_query($sql_editvalue,$db);
        $row_editvalue=DB_fetch_array($result_editvalue);
         echo"<tr><td>Edit this:</td>";
    echo"<td><input type=text name='perio_days' id='perio_days' value='".$row_editvalue['daystocomplete']."'></td>";
    echo"<td><input type=text size='70px' name='perio_des' id='perio_des' value='".$row_editvalue['taskdescription']."'></dt>";
         echo'<td><input type="submit" name="edit" value="' . _('Edit') . '" onclick= "if(validate()==1)return false;"></td></tr>';
    }
    else{
         echo"<tr><td>Enter here:</td>";
    echo"<td><input type=text name='perio_days' id='perio_days'></td>";
    echo"<td><input type=text size='70px' name='perio_des' id='perio_des'></dt>";
    echo'<td><input type="submit" name="submit" value="' . _('Add New') . '" onclick= "if(validate()==1)return false;"></td></tr>';
    }
  
    
    
    echo'</tr>
        </table>';
      echo"</table>";
      echo"</fieldset>";
      }
 echo"</table></form>";

/*function getnextid($table,$field,$condition)   {
$sql2="select max($field)+1 as nextid from $table $condition";
//echo $sql;
$result=DB_query($sql2,$db);
$myrow=DB_fetch_array($result);
$nextid=$myrow[0];
echo 'nnnn'.$nextid;
return $nextid;
}*/
?>
<script>
function edit_task(str)
{
    
    var str1= document.getElementById("maincatid").value;
    var str2= document.getElementById("plantname").value;
    var str10= document.getElementById("cstype").value;   
    //alert(str1);alert(str2);
    location.href="?main="+ str1 + "&sub=" + str2 + "&tno=" + str + "&cstype=" + str10;
    
}

function Filtercategory()       {

var str= document.getElementById("maincatid").value;
    //alert(str);
if (str=="")
  {
  document.getElementById("maincatid").innerHTML="";
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
        
    document.getElementById("plant").innerHTML=xmlhttp.responseText;
//      $('#dinhide').show(); 
    }
  } 
  //alert(str);
xmlhttp.open("GET","bio_periodic_maintenance_task.php?maincatid="+str,true);
xmlhttp.send(); 
}

function showschedule(){
    var str11= document.getElementById("maincatid").value; 
    var str10= document.getElementById("cstype").value;
    var str= document.getElementById("plantname").value;
    //alert(str);
if (str=="")
  {
  document.getElementById("schedule").innerHTML="";
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
        
    document.getElementById("schedule").innerHTML=xmlhttp.responseText;
//      $('#dinhide').show(); 
showschedule1(str); 
    }
  } 
  //alert(str);
xmlhttp.open("GET","bio_periodic_maintenance_task.php?plantname="+str+ "&cstype="+ str10 + "&main=" + str11,true);
xmlhttp.send();   


}


function showschedule1(str)
{ //alert(str);
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
        
    document.getElementById("task1").innerHTML=xmlhttp.responseText;
//      $('#dinhide').show(); 
    }
  } 
  //alert(str);
xmlhttp.open("GET","bio_periodic_maintenance_task.php?plantname1="+str,true);
xmlhttp.send();
    
}


function validate()
{ 
 var f=0;
//if(f==0){f=common_error('prevno','Please Enter Previous task');  if(f==1) { return f; }} 
if(f==0)
{ 
    var x=document.getElementById('perio_days').value;
    
    if(x==""){f=1;
              alert("Enter no. of days to start this action from pravious"); document.getElementById('perio_days').focus();
              return f; }
    else{
       var l=x.length;
            if(isNaN(x))
           { 
                f=1;
              alert("Enter a numeric value"); document.getElementById('perio_days').focus();
              if(x=""){ f=0;}
              return f; 
           }
             
    } 
    if(f==0){f=common_error('perio_des','Please enter description');  if(f==1) { return f; }}
}         
//if(f==0){f=common_error('task2','Please select Previous task ');  if(f==1) { return f; }}
         
}
function editdocin(str){
    //alert("kkjk");
    var id='editdocumentin'+str;
    alert(str);
    //var docid='doc'+str;
   // var docno=document.getElementById(docid).value; 
   // var rdateid='date'+str;
    //var rdate=document.getElementById(rdateid).value;
//    alert(rdate);
   // var autoid='autono'+str;
   // var autono=document.getElementById(autoid).value;

    //alert(autono);
    //    alert(docno); 
 
//    alert(str);
//    alert(lead); alert(task);
if (str=="")
  {
  document.getElementById("status").innerHTML="";
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
    document.getElementById(id).innerHTML=xmlhttp.responseText;

    }
  } 

xmlhttp.open("GET","bio_documentedit.php?rdate=" + str ,true);
xmlhttp.send();    
}
function dlt(str){
location.href="?delete=" +str;
}
function custfilename_print(){
    str1=document.getElementById("lsgType").value;
    str2=document.getElementById("lsgName").value;
    str3=document.getElementById('Districts').value;
    str4=document.getElementById('year').value;
    str5=document.getElementById('loc').value;
    if(str1==3)  {str6=document.getElementById("gramaPanchayath").value;}
    controlWindow=window.open("bio_custfilename_print.php?lsgtype=" + str1 + "&lsgname1=" + str2 + "&dist=" + str3 + "&year1=" +str4 + "&location=" + str5 + "&grama2=" + str6 ,"idproof","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,width=900,height=400"); 
}


</script>
