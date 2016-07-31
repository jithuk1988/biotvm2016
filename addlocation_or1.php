<?php
$PageSecurity = 40;
include('includes/session.inc');
$title = _('Document Archiving');
include('includes/header.inc'); 
echo '<p class="page_title_text"><img src="'.$rootpath.'/css/'.$theme.'/images/maintenance.png" title="' . _('Document Archiving')
	. '" alt="" />' . _('Document Archiving') . '</p>';
echo '<div class="page_help_text">' . _('Enter file location for each LSG') . '</div><br />';

/*if(isset($_GET['delete'])){ $id=$_GET['delete'];
$sql="DELETE FROM bio_district WHERE id = $id";
$result=DB_query($sql,$db);
}*/
if (isset($_POST['newloc'] )){
    $lastfno=$_POST['lastfileno1'];
        if($lastfno=="")
        {
            $lastfno=0;
        }
    
    $cid=$_POST['country'];
     $stateid=$_POST['State'];
     $did=$_POST['District'];
     $lsgname=$_POST['lsgName'];
     $grama=$_POST['gramaPanchayath'];
     $blockname=$_POST['gramaPanchayath'];
      if($_POST['lsgType']==3){$lsgname=$blockname;}
if($_POST['country']==1 && $_POST['State']==14){
    
    if($_POST['lsgType']==1){ 
        $sql11="select max(id) as maxid from bio_lsg_fileno where LSG_type='".$_POST['lsgType']."' AND LSG_id=(SELECT id FROM bio_corporation WHERE country=$cid AND state=$stateid AND district=$did)";
          $result11=DB_query($sql11,$db);
         $row11=DB_fetch_array($result11,$db);
        $sql12="SELECT * FROM bio_lsg_fileno where LSG_type='".$_POST['lsgType']."' AND LSG_id=(SELECT id FROM bio_corporation WHERE country=$cid AND state=$stateid AND district=$did) AND id=".$row11['maxid']."";
        $result12=DB_query($sql12,$db);
         $row12=DB_fetch_array($result12,$db);
    
    //$row12['fileno'];
    //echo $row12['room'];
    $fileno=explode("/",$row12['fileno']);
    //echo ($fileno);
    
    //echo $_POST['room1'];
    
    if(sizeof($fileno)==5){ $fileno=$row12['fileno']."/1";
    $loc="L2";
    }
    elseif(sizeof($fileno)==6){
        $lastindex=$fileno[5]+1;
         $fileno=$fileno[0]."/".$fileno[1]."/".$fileno[2]."/".$fileno[3]."/".$fileno[4]."/".$lastindex;
         $loc1=$lastindex+1;
         $loc="L".$loc1;
        }
    $sql13="INSERT into bio_lsg_fileno(LSG_type,LSG_id,fileno,loc,
    room,
    rack,
    bin
    )
    VALUES('".$_POST['lsgType']."',
    '".$row12['LSG_id']."',
    '$fileno','$loc',
    '".$_POST['room1']."',
    '".$_POST['rack1']."',
'".$_POST['bin1']."'
    )";
    
    }
    else{

        $sql11="select max(id) as maxid from bio_lsg_fileno where LSG_type='".$_POST['lsgType']."' AND LSG_id=$lsgname";
          $result11=DB_query($sql11,$db);
         $row11=DB_fetch_array($result11,$db);
        $sql12="SELECT * FROM bio_lsg_fileno where LSG_type='".$_POST['lsgType']."' AND LSG_id=$lsgname AND id=".$row11['maxid']."";
        $result12=DB_query($sql12,$db);
         $row12=DB_fetch_array($result12,$db);
    
    //$row12['fileno'];
    //echo $row12['room'];
    $fileno=explode("/",$row12['fileno']);
    //echo ($fileno);
    
    
    if(sizeof($fileno)==5){ 
        $fileno=$row12['fileno']."/1";
     $loc="L2";   
    
    }
    elseif(sizeof($fileno)==6){
        $lastindex=$fileno[5]+1;
         $fileno=$fileno[0]."/".$fileno[1]."/".$fileno[2]."/".$fileno[3]."/".$fileno[4]."/".$lastindex;
         $loc1=$lastindex+1;
         $loc="L".$loc1;
         
        }
    $sql13="INSERT into bio_lsg_fileno(LSG_type,LSG_id,fileno,loc,
    room,
    rack,
    bin)
    VALUES('".$_POST['lsgType']."',
    '".$row12['LSG_id']."',
    '$fileno','$loc',
    '".$_POST['room1']."',
    '".$_POST['rack1']."',
'".$_POST['bin1']."')";
    
    }
    //echo $sql13;
    
 $result=DB_query($sql13,$db);
}

}
if (isset($_POST['submit1'] )){
  
    if($_POST['country']==1 && $_POST['State']==14){
       /*$lastfno=$_POST['lastfileno1'];
        if($lastfno=="")
        {
            $lastfno=0;
        }*/
     $cid=$_POST['country'];
     $stateid=$_POST['State'];
     $did=$_POST['District'];
     $lsgname=$_POST['lsgName'];
     $grama=$_POST['gramaPanchayath'];
     $room=$_POST['room1'];
     $rack=$_POST['rack1'];
     $bin=$_POST['bin1'];
      $lsgtype=$_POST['lsgType'];
     if($_POST['lsgType']==3){$lsgname=$grama;}     
     if($_POST['lsgType']==1)
     {
         //    select max(id) as maxid from bio_lsg_fileno where LSG_type=1 AND LSG_id=(SELECT id FROM bio_corporation WHERE country=1 AND state=14 AND district=2)
         $sql2="SELECT MAX(id) as maxid , LSG_type ,LSG_id FROM bio_lsg_fileno WHERE LSG_type=$lsgtype AND LSG_id=(SELECT id FROM bio_corporation WHERE country=$cid AND state=$stateid AND district=$lsgname)";
        // echo $sql2;
         $result2=DB_query($sql2,$db);
         $row2=DB_fetch_array($result2,$db);
        // echo $row2['maxid'];
       
         //echo'<table><tr><td>Inserted</td></tr></table>';
     $sql="UPDATE bio_lsg_fileno SET room='".$_POST['room1']."' , rack='".$_POST['rack1']."' , bin='".$_POST['bin1']."' WHERE LSG_type=$lsgtype AND LSG_id=(SELECT id FROM bio_corporation WHERE country=$cid AND state=$stateid AND district=$lsgname) AND id='".$row2['maxid']."' ";
         
         
         }
     else 
     {
         $sql2="SELECT MAX(id) as maxid , LSG_type ,LSG_id FROM bio_lsg_fileno WHERE LSG_type=$lsgtype AND LSG_id=$lsgname";
         //echo $sql2;
         $result2=DB_query($sql2,$db);
         $row2=DB_fetch_array($result2,$db);
          $sql="UPDATE bio_lsg_fileno SET room='".$_POST['room1']."' , rack='".$_POST['rack1']."', bin='".$_POST['bin1']."' WHERE LSG_type=$lsgtype AND LSG_id=$lsgname AND id='".$row2['maxid']."' ";
     }
      
   
  //echo $sql;
    $result=DB_query($sql,$db);
unset($_POST['country']); 
}

}

    echo "<form action=" . $_SERVER['PHP_SELF'] . "?" . SID ." method=post>";

 echo"<table width='700px'><tr><td>";
 echo"<fieldset><legend>Select LSG</legend>";
  echo"<table>";



     $sql="SELECT * FROM bio_country ORDER BY cid";
    $result=DB_query($sql,$db);
    
    echo"<tr><td style='width:50%'>Country*</td><td>";
    echo '<select name="country" id="country" tabindex=9 onchange="showstate(this.value)" style="width:190px">';
    $f=0;
  while($myrow1=DB_fetch_array($result))
  {  
  if ($myrow1['cid']==1)  
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
    echo $myrow1['cid'] . '">'.$myrow1['country'];
    echo '</option>';
    $f++;
   } 
  echo '</select></td></tr>';
  


    $sql="SELECT * FROM bio_state WHERE cid=1 ORDER BY stateid";
    $result=DB_query($sql,$db);
 
 echo"<tr id='showstate'><td>State*</td><td>";
 echo '<select name="State" id="state" style="width:190px" tabindex=10 onchange="showdistrict(this.value)">';
   $f=0;
  while($myrow1=DB_fetch_array($result))
  {
  if ($myrow1['stateid']==14)
    {
    echo '<option selected value="';
    } else
    {
    if ($f==0)
        {
        echo '<option>';
        }
        echo '<option value="';
    }
    echo $myrow1['stateid'] . '">'.$myrow1['state'];
    echo '</option>';
    $f++;
   }
  echo '</select>';
  echo'</td>'; 
  echo'</tr>';
  if($_GET['Dist']){
      $did=$_GET['Dist'];
  }
  elseif($_POST['district'])
  {
      $did=$_POST['district'];
  }

  $sql="SELECT * FROM bio_district WHERE stateid=14 ORDER BY did";
  $result=DB_query($sql,$db);
 
 echo"<tr id='showdistrict'><td>District*</td><td>";
 echo '<select name="District" id="Districts" style="width:190px" tabindex=11 onchange="showtaluk(this.value)">';
   $f=0;
  while($myrow1=DB_fetch_array($result))
  {
  if ($myrow1['did']==$did)
    {
    echo '<option selected value="';
    } else
    {
    if ($f==0)
        {
        echo '<option>';
        }
        echo '<option value="';
    }
    echo $myrow1['did'] . '">'.$myrow1['district'];
    echo '</option>';
    $f++;
   }
  echo '</select>';
  echo'</td>'; 
  echo'</tr>';
      echo '<tr><td>' . _('LSG Type') . ':</td>';
    echo '<td><select name="lsgType" id="lsgType" style="width:190px" onchange=showblock(this.value)>';
  if($_GET['lsg']==1)
  {
      echo '<option selected value=1>Corporation</option>';
      
  }
   elseif($_GET['lsg']==2)
  {
      echo '<option selected value=2>Muncipality</option>';
      
  }
   elseif($_GET['lsg']==3)
  {
      echo '<option selected value=3>Panchayat</option>';
      
  }else{

    echo '<option value=0></option>'; 
    echo '<option value=1>Corporation</option>';
    echo '<option value=2>Muncipality</option>';
    echo '<option value=3>Panchayat</option>';              
  }
  echo '</select></td></tr>';
  if($_GET['lsg']==1){
      $sql="select corporation from bio_corporation where district='".$_GET['Dist']."'";
      $result=DB_query($sql,$db);
         $row=DB_fetch_array($result,$db);
         echo '<tr><td>' . _('Corporation Name') . ':</td>';
         echo '<td><select name="lsgName" id="lsgName" style="width:190px">';
            echo '<option selected value='.$_GET['Dist'].'>'.$row['corporation'].'</option>';
           echo '</select></td></tr>';
      
  }
  elseif($_GET['lsg']==2)
  {
      $sql="select municipality from bio_municipality where id='".$_GET['lsgname']."'";
      $result=DB_query($sql,$db);
         $row=DB_fetch_array($result,$db);
          echo '<tr><td>' . _('Municipality Name') . ':</td>';
           echo '<td><select name="lsgName" id="lsgName" style="width:190px">';
            echo '<option selected value='.$_GET['lsgname'].'>'.$row['municipality'].'</option>';
           echo '</select></td></tr>';
  }
  elseif($_GET['lsg']==3)
  {
      $sql="select name,block from bio_panchayat where id='".$_GET['lsgname']."'";
      $result=DB_query($sql,$db);
         $row=DB_fetch_array($result,$db);
         $sql1="select block from bio_block where id='".$row['block']."'";
      $result1=DB_query($sql1,$db);
         $row1=DB_fetch_array($result1,$db);
          echo '<tr><td>' . _('Block Name') . ':</td>';
          echo '<td><select name="lsgName" id="lsgName" style="width:190px">';
         echo '<option selected value='.$row['block'].'>'.$row1['block'].'</option>';
           echo '</select></td></tr>';
           echo '<tr><td>' . _('Panchayat Name') . ':</td>';
          echo '<td><select name="gramaPanchayath" id="gramaPanchayath" style="width:190px">';
         echo '<option selected value='.$_GET['lsgname'].'>'.$row['name'].'</option>';
           echo '</select></td></tr>';
  }else{
  
        echo '<tr><td align=left colspan=2>';
        echo'<div style="align:left" id=block>';
                    
        echo'</div>';
        echo'</td></tr>';
        
        echo"<tr id='showgramapanchayath'></tr>";
        
        echo'</td>'; echo'</tr>';

  }
  
  echo"</td></tr></table>";
        
         
        
 

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
 echo"</table></form>";

function getnextid($table,$field,$condition)   {
$sql2="select max($field)+1 as nextid from $table $condition";
//echo $sql;
$result=DB_query($sql2,$db);
$myrow=DB_fetch_array($result);
$nextid=$myrow[0];
echo 'nnnn'.$nextid;
return $nextid;
}
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
xmlhttp.open("GET","bio_showstatearchiving.php?country=" + str,true);
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
xmlhttp.open("GET","bio_showstatearchiving.php?state=" + str + "&country1=" + str1,true);
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
xmlhttp.open("GET","bio_showlsgarchiving.php?block=" + str +"&country1="+ str1 +"&state1="+ str2 +"&district="+ str3,true);
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
xmlhttp.open("GET","bio_showlsgarchiving.php?grama=" + str +"&country1="+ str1 +"&state2="+ str2 +"&district="+ str3,true);
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
xmlhttp.open("GET","bio_archloc.php?lsg="+ str1 +"&lsgname1="+ str2 +"&grama1="+ str3 + "&country1="+ str4 + "&state1="+ str5 + "&dist="+ str6,true);
xmlhttp.send(); 
}
</script>
