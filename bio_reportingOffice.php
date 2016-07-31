<?php
   $PageSecurity = 15;  
include('includes/session.inc'); 
if(isset($_GET['rept'])){

$office_type= $_GET['rept'];

if($office_type!=5){
if($office_type==2){
    $sql = "SELECT * FROM bio_office
            WHERE officetype<".$office_type;
    $result = DB_query($sql,$db);
    $myrow=DB_fetch_array($result);
    
     $_POST['reportOff']=$myrow['id'];
}
elseif($office_type==3){
   $sql = "SELECT * FROM bio_office
            WHERE officetype<".$office_type;
    $result = DB_query($sql,$db);
    $myrow=DB_fetch_array($result);
    
     $_POST['reportOff']=$myrow['id']; 
}
elseif($office_type==4){
   $sql = "SELECT * FROM bio_office
            WHERE officetype=3";
    $result = DB_query($sql,$db);
    $myrow=DB_fetch_array($result);
    
     $_POST['reportOff']=$myrow['id']; 
}
        

            echo'<td>' . _('Reporting Office') . ':</td>';
            echo '<td><select name="reportOff" style="width:100%">';
            $result=DB_query($sql,$db);
            echo'<option value=0 selected></option>';
            while($row=DB_fetch_array($result))
            {       
                if ($row['id']==$_POST['reportOff'])
                 {
                 echo '<option selected value="';
                 } else {

                 echo '<option value="';
                 }
                 echo $row['id'] . '">'.$row['office'];
                 echo '</option>';
            }
            echo'</select></td>';
            
            
}           
 else{
   $sql = "SELECT * FROM bio_office
            WHERE officetype=3";
    $result = DB_query($sql,$db);
    $myrow=DB_fetch_array($result);
    
     $_POST['zonalOff']=$myrow['id']; 
     echo'<td>' . _('Zonal Office') . ':</td>';
            echo '<td><select name="zonalOff" id="zonalOff" style="width:100%" onchange="reportingOffice1(this.value)">';
            $result=DB_query($sql,$db);
            echo'<option value=0 selected ></option>';
            while($row=DB_fetch_array($result))
            {       
                if ($row['id']==$_POST['zonalOff'])
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
     echo'<tr id="reptoff2">';
     echo'</tr>';       
}   
}

?>

<!--<script type="text/javascript">  

function reportingOffice1(str){
if (str=="")
  {
  document.getElementById("reptoff2").innerHTML="";
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
    document.getElementById("reptoff2").innerHTML=xmlhttp.responseText;

    }
  } 
xmlhttp.open("GET","bio_reportingOffice2.php?rept=" + str,true);
xmlhttp.send();    
}


</script>        -->   

