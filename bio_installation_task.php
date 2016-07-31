<?php
$PageSecurity = 40;
include('includes/session.inc');
$title = _('Plant installation scheduling');
include('includes/header.inc'); 
echo '<p class="page_title_text"><img src="'.$rootpath.'/css/'.$theme.'/images/customer.png" title="' . _('Plant installation scheduling')
	. '" alt="" />' . _('Task Scheduling') . '</p>';
    if( $_POST['submit'] ){
       $taskno=$_POST['count'];
      //echo $_POST['prev'];
      if($_POST['task2']==NULL){$prev=$_POST['task2']=0;}
      
         //echo"<br /> ".$_POST['plantname'];
         $sql="insert into bio_def_cstask (stockcode,cstype,taskno,taskdescription,daystocomplete,prevtask) values 
        ('".$_POST['plantname']."',1,'$taskno','".$_POST['des']."',".$_POST['comdays']." ,".$_POST['task2']." )";
        DB_query($sql,$db);
        $plant=$_POST['plantname'];
        $planttype=$_POST['MainCategoryID'];
        $prev_task=$_POST['task2'];
    }
    if($_POST['edit'])
    {
          if($_POST['task2']==NULL){$prev=$_POST['task2']=0;}
         $sql_taskedit="UPDATE bio_def_cstask SET taskdescription='".$_POST['des']."' , daystocomplete=".$_POST['comdays']." ,
         prevtask=".$_POST['task2']." WHERE taskno=".$_POST['tno']." AND cstype=1 AND stockcode='".$_POST['sub']."' " ;
          DB_query($sql_taskedit,$db);
         
         $plant=$_POST['plantname'];
        $planttype=$_POST['MainCategoryID'];
        $prev_task=$_POST['task2'];
        
    }
    echo "<form action=" . $_SERVER['PHP_SELF'] . "?" . SID ." method=post>";
    if($_GET['main'])
{
    $plant=$_GET['sub'];
    $planttype=$_GET['main'];
    echo"<input type=hidden name=tno value='".$_GET['tno']."'>";
    echo"<input type=hidden name=sub value='".$_GET['sub']."'>";
}

//echo $plant;
//echo $prev_task;
//echo $planttype;
 echo"<table width='1000px'><tr><td>";
 echo"<fieldset><legend>Select Plant Catogery</legend>";
 
 
  echo"<table>";
 $sql="SELECT  categoryid,categorydescription,maincatid from stockcategory,
             bio_maincat_subcat
             WHERE stockcategory.categoryid= bio_maincat_subcat.subcatid
             AND bio_maincat_subcat.maincatid =1
             order by stockcategory.categorydescription asc";
      $result=DB_query($sql,$db);
echo'<tr><td>Category :</td><td><select name="MainCategoryID" id="maincatid" onchange="Filtercategory()">';
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
//echo""; <td></td></td><td>
if($_GET['main'])
{
    $sql_edit="SELECT * FROM bio_def_cstask WHERE cstype=1 AND stockcode='".$_GET['sub']."' 
    AND taskno='".$_GET['tno']."' ";
    $result_edit=DB_query($sql_edit,$db);
    $row_edit=DB_fetch_array($result_edit);
    $des1=$row_edit['taskdescription'];
    $days1=$row_edit['daystocomplete'];
    echo"<tr><td>Task :</td><td><input type=text name=des id=des value='$des1'></td>
   <td>Days to complete:</td><td><input type=text name=comdays id=comdays value=$days1></td>";
    
}else{
    echo'<tr><td>Task :</td><td><input type="text" name="des" id="des"></td>
   <td>Days to complete:</td><td><input type="text" name="comdays" id="comdays"></td>';
}
   
   
   
   //echo $plant;
   if($_GET['main']){
       $sql_preved="SELECT prevtask from bio_def_cstask where cstype=1 AND taskno= '".$_GET['tno']."' 
       AND stockcode='".$_GET['sub']."'";
       $result_preved=DB_query($sql_preved,$db);
       $row_preved=DB_fetch_array($result_preved);
        $prev_task=$row_preved['prevtask'];
   }
   
   echo'<td>Previous Task:</td><td id=task1>';
   if($plant!=NULL){
   $sql="SELECT * FROM bio_def_cstask where stockcode='".$plant."' AND cstype=1";
   $result=DB_query($sql,$db);
  echo'<select id="task2" name="task2" >';//
    $f=0;

  while($myrow=DB_fetch_array($result))
  {  
      
  if ($myrow['taskno']==$prev_task)  
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
    echo $myrow['taskno'] . '">'.$myrow['taskdescription'];
    echo '</option>';
    $f++;
   } 
   
  echo '</select>';
   }else{echo"<input type=text>";}
   
   echo'</td>';
   if($_GET['main']){
        echo'<td><input type="submit" name="edit" value="' . _('Edit Task') . '" onclick= "if(validate()==1)return false;"></td></tr>';
   }
   else{
      echo'<td><input type="submit" name="submit" value="' . _('Add Task') . '" onclick= "if(validate()==1)return false;"></td></tr>'; 
   }

   echo'</table>';    
    echo'</table>'; 
    
      echo'<table ><td id="schedule">';
      if($plant!=NULL){
          echo"<fieldset ><legend>Tasks</legend>";
        echo'<table width=1000px><tr><th>Sl No.</th><th>Task</th><th>Days to complete</th><th>Pevious Task</th></tr>';
   $sql="SELECT * FROM bio_def_cstask where stockcode='".$plant."' AND cstype=1";
   $result=DB_query($sql,$db);
   $k=0;
    $slno=1;
    while($myrow=DB_fetch_array($result)){
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
        
        echo'<td>'.$myrow['taskdescription'].'</td>';
        echo'<td>'.$myrow['daystocomplete'].'</td>';
        
        
        if($myrow['prevtask']==0){   
        echo" <td>No Previous Task</td>";
        }else{
          $sql_prevtask="SELECT taskdescription from bio_def_cstask where cstype=1 
        AND stockcode='$plant' AND taskno='".$myrow['prevtask']."'";
        $result_prevtask=DB_query($sql_prevtask,$db);
        $row_prevtask=DB_fetch_array($result_prevtask);
        echo" <td>".$row_prevtask['taskdescription']."</td>";
        }
        
      echo" <td width='50px'><a style='cursor:pointer;' id='".$myrow['taskno']."' onclick=edit_task(this.id);>" . _('Edit') . "</a></td>";
      $slno++;
    }
    echo'<input type="hidden" value='.$slno.' id="count" name="count">';
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
    //alert(str1);alert(str2);
    location.href="?main="+ str1 + "&sub=" + str2 + "&tno=" + str;
    
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
xmlhttp.open("GET","bio_installation_taskplant.php?maincatid="+str,true);
xmlhttp.send(); 
}

function showschedule(){
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
xmlhttp.open("GET","bio_installation_taskplant.php?plantname="+str,true);
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
xmlhttp.open("GET","bio_installation_taskplant.php?plantname1="+str,true);
xmlhttp.send();
    
}


function validate()
{ 
 var f=0;
 if(f==0){f=common_error('maincatid','Please select category ');  if(f==1) { return f; }}
 if(f==0){f=common_error('plantname','Please select plant ');  if(f==1) { return f; }} 
if(f==0){f=common_error('des','Please Enter Task ');  if(f==1) { return f; }}
//if(f==0){f=common_error('prevno','Please Enter Previous task');  if(f==1) { return f; }} 
if(f==0)
{ 
    var x=document.getElementById('comdays').value;
    
    if(x==""){f=1;
              alert("Enter number of days to complete this task"); document.getElementById('comdays').focus();
              return f; }
    else{
       var l=x.length;
            if(isNaN(x))
           { 
                f=1;
              alert("Enter a numeric value"); document.getElementById('comdays').focus();
              if(x=""){ f=0;}
              return f; 
           }
             
    } 
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
