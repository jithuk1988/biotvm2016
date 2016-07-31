<?php
$PageSecurity = 40;
include('includes/session.inc');
$title = _('List of customers with file number');
include('includes/header.inc'); 
echo '<p class="page_title_text"><img src="'.$rootpath.'/css/'.$theme.'/images/customer.png" title="' . _('List of customers with file number')
	. '" alt="" />' . _('List of customers with file number') . '</p>';
//echo '<div class="page_help_text">' . _('Enter file location for each LSG') . '</div><br />';

/*if(isset($_GET['delete'])){ $id=$_GET['delete'];
$sql="DELETE FROM bio_district WHERE id = $id";
$result=DB_query($sql,$db);
}*/
    echo "<form action=" . $_SERVER['PHP_SELF'] . "?" . SID ." method=post>";

 echo"<table width='700px'><tr><td>";
 echo"<fieldset><legend>Select District</legend>";
  echo"<table>";


    
 
 echo"<tr id='showdistrict'><td>District*</td><td>";
 echo '<select name="District" id="Districts" style="width:190px" tabindex=11 onchange="showtaluk(this.value)">';
 echo '<option value="TV">Trivandrum</option>
<option value="KL">Kollam</option>
<option value="PT">Pathanamthitta</option>
<option value="KT">Kottayam</option>
<option value="AL">Alappuzha</option>
<option value="ER">Ernakulam</option>
<option value="TS">Thrissur</option>
<option value="MA">Malappuam</option>
<option value="PL">Palakkadu</option>
<option value="KZ">Kozhikode</option>
<option value="ID">Idukki</option>
<option value="WA">Wayanadu</option>
<option value="KS">Kasargode</option>
<option value="KN">Kannur</option>  
<option value="0">ALL</option> 
';
  echo '</select>';
  echo'</td>'; 
  echo'</tr>';
     echo '<tr>';
      echo '<td width=200px> Financial year:</td><td><select name="year" id="year" style="width:190px" tabindex=11 onchange="showfilelocation(this.value)">';
      echo '<option value= ></option>';
      
      echo '<option value="2000-01">2000-01</option>';
      echo '<option value="2001-02">2001-02</option>';
      echo '<option value="2002-03">2002-03</option>';
      echo '<option value="2003-04">2003-04</option>';
      echo '<option value="2004-05">2004-05</option>';
      echo '<option value="2005-06">2005-06</option>';
      echo '<option value="2006-07">2006-07</option>';
      echo '<option value="2007-08">2007-08</option>';      
      echo '<option value="2008-09">2008-09</option>';
      echo '<option value="2009-10">2009-10</option>';
      echo '<option value="2010-11">2010-11</option>';
      echo '<option value="2011-12">2011-12</option>';
      echo '<option value="2012-13">2012-13</option>';
      echo '<option value="2013-14">2013-14</option>';
      echo '<option value="2014-15">2014-15</option>';
      echo '<option value="2015-16">2015-16</option>';
      echo '<option value="2016-17">2016-17</option>';
      echo '<option value="2017-18">2017-18</option>';
      echo '<option value="2018-19">2018-19</option>';
      echo '<option value="2019-20">2019-20</option>';
      echo '<option value="2020-21">2020-21</option>';
      echo '<option value="2021-22">2021-22</option>';
      echo '<option value="2022-23">2022-23</option>';
      echo '<option value="2023-24">2023-24</option>';
      echo '<option value="ALL">ALL</option>';
       echo '</select></td>';
  
  echo"</tr></table>";
        
         
        
 

//                                                if(!isset($_POST['natio'])){$f=1;}
//              echo  $nat=$_POST['natio'];

  //if (!isset($_POST['country'])) {$cid=1;};
  //else {$cid=0;};
  //echo 'cid='.$cid;



   /* echo"<tr id='showstate'><td>State</td><td>";
            $sql="SELECT * FROM bio_state where cid=$cid ORDER BY stateid";*/
   // $result=DB_query($sql,$db);
//  echo '<select name="state" id="state" style="width:190px">';
//   $f=0;
//  while($myrow1=DB_fetch_array($result))
//  {
//  if ($myrow1['stateid']==$_POST['state'])
//    {
//    echo '<option selected value="';
//    } else
//    {
//    if ($f==0)
//        {
//        echo '<option>';
//        }
//        echo '<option value="';
//    }
//    echo $myrow1['stateid'] . '">'.$myrow1['state'];
//    echo '</option>';
//    $f++;
//   }
//  echo '</select>';
 

  
  if($_GET['lsg'])
  {
      echo"<table>";
      echo"<legend>File Location</legend>";
        echo"<tr><th>Room</th><th>Rack</th><th>Bin</th></tr>";
      echo"<tr><td><input type='text' name='room1' id='room1' tabindex=4  onkeyup=''></td>";
            echo"<td><input type='text' name='rack1' id='rack1' tabindex=4 onkeyup='' ></td>";
            echo"<td><input type='text' name='bin1' id='bin1' tabindex=4 onkeyup='' ></td>";
   //echo"<td><input type='text' name='lastfileno1' id='lastfileno1' tabindex=4 value=0 onkeyup='' ></td>";
   echo"<td><input type='submit' name='submit1' id='submit1' value='submit' onclick=' if(validate()==1)return false;'></td></tr>";
      echo"</table>";
      
  }else{
      echo"<table id='showlocation1'>";
  
      echo"</table>";
  }
  
        
      echo"</fieldset>";

      
      echo"<table id='showloc' width='800'>";
      echo"</table>";
      
      
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
function validate()
{
     
 var f=0; 
if(f==0){f=common_error('room1','Please Enter room');  if(f==1) { return f; }}     
if(f==0){f=common_error('rack1','Please Enter rack');  if(f==1) { return f; }}          
if(f==0){f=common_error('bin1','Please Enter bin');  if(f==1) { return f; }}
if(f==0)
{ 
    var x=document.getElementById('lastfileno1').value;
    if(x!=""){
       var l=x.length;
            if(isNaN(x))
           {  f=1;
              alert("Enter a numeric value"); document.getElementById('lastfileno1').focus();
              if(x=""){f=0;}
              return f; 
           }  
    }     
}        
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
    controlWindow=window.open("bio_custfilename_print.php?lsgtype=" + str1 + "&lsgname1=" + str2 + "&dist=" + str3 + "&year1=" +str4 + "&location=" + str5 + "&grama2=" + str6 ,"idproof","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=900,height=400"); 
}

function replace_html(id, content) {
	document.getElementById(id).innerHTML = content;
}

var progress_bar = new Image();
progress_bar.src = '4.gif';

function show_progressbar(id) {
	replace_html(id, '<img src="4.gif" border="0" alt="Loading, please wait..." />Loading...');
}


function showstate(str){ 

if (str=="")
  {
  document.getElementById("showstate").innerHTML="";
  return;
  }
show_progressbar('showstate');

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
    document.getElementById("showstate").innerHTML=xmlhttp.responseText;
    document.getElementById("state").focus();
    }
  }
xmlhttp.open("GET","customerfilestate.php?country=" + str,true);
xmlhttp.send();
}



function showdistrict(str){       //alert(str);
str1=document.getElementById("country").value;
//alert(str1);
if (str=="")
  {
  document.getElementById("showdistrict").innerHTML="";
  return;
  }
show_progressbar('showdistrict');
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
    document.getElementById("showdistrict").innerHTML=xmlhttp.responseText;
           document.getElementById('Districts').focus();

    }
  }
xmlhttp.open("GET","customerfilestate.php?state=" + str + "&country1=" + str1,true);
xmlhttp.send();

}
function showblock(str){
   
     str1=document.getElementById("country").value;
     str2=document.getElementById('state').value;
     str3=document.getElementById('Districts').value;
     if (str3=="")
     {
     alert("Please select a district");    
     document.getElementById("Districts").focus();
     document.getElementById("lsgType").value=0;
     return;
     }
     

     if(str==1 && (str3==1 || str3==3 || str3==4 || str3==5 || str3==7 || str3==9 || str3==10 || str3==10 || str3==14)){
         
         alert("No Corporation for this district");
         document.getElementById("block").innerHTML="";
         return;
     }
//alert(str1);   alert(str2);       alert(str3);
if (str=="")
  {
  document.getElementById("block").innerHTML="";
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
     document.getElementById("block").innerHTML=xmlhttp.responseText; 
       
    }
  } 
xmlhttp.open("GET","customerlsgfile.php?block=" + str +"&country1="+ str1 +"&state1="+ str2 +"&district="+ str3,true);
xmlhttp.send(); 
} 

 function showgramapanchayath(str){   
   //alert(str);
     str1=document.getElementById("country").value;
     str2=document.getElementById('state').value;
     str3=document.getElementById('Districts').value;
//alert(str1);   alert(str2);       alert(str3);
if (str=="")
  {
  document.getElementById("showgramapanchayath").innerHTML="";
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
     document.getElementById("showgramapanchayath").innerHTML=xmlhttp.responseText; 
       
    }
  } 
xmlhttp.open("GET","customerlsgfile.php?grama=" + str +"&country1="+ str1 +"&state2="+ str2 +"&district="+ str3,true);
xmlhttp.send(); 
}

 function showlocation(){
     
     str1=document.getElementById("lsgType").value;
     str2=document.getElementById("lsgName").value;
   if(str1==3)  {str3=document.getElementById("gramaPanchayath").value;}
     str4=document.getElementById("country").value;
     str5=document.getElementById('state').value;
     str6=document.getElementById('Districts').value; 
     
   //alert("str");        
if (str1=="")
  {
  document.getElementById("showlocation1").innerHTML="";
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
     document.getElementById("showlocation1").innerHTML=xmlhttp.responseText; 
       
    }
  } 
xmlhttp.open("GET","customerlocfile.php?lsg="+ str1 +"&lsgname1="+ str2 +"&grama1="+ str3 + "&country1="+ str4 + "&state1="+ str5 + "&dist="+ str6,true);
xmlhttp.send(); 
}
function showfilelocation(str){
 
     str6=document.getElementById('Districts').value;
 
   //alert(str);  
   //alert(str7);       
if (str6=="")
  {
  document.getElementById("showloc").innerHTML="";
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
     document.getElementById("showloc").innerHTML=xmlhttp.responseText; 
                 
    }             
  } 
xmlhttp.open("GET","customerlocfile1.php?year=" + str + "&dist=" + str6,true);
xmlhttp.send(); 
}


</script>
