<?php
  $PageSecurity = 80;
include ('includes/session.inc');
$title = _('COMPLAINT MASTER');
include ('includes/header.inc');
include ('includes/SQL_CommonFunctions.inc');
 echo '<center><font style="color: #333;
    background:#fff;
    font-weight:bold;
    letter-spacing:0.10em;
    font-size:16px;
    font-family:Georgia;
    text-shadow: 1px 1px 1px #666;">PRODUCTION LOCATION </font></center>';

 
    
       
     /*else
      {     if (isset($_POST['submit']))
         {
     $loc1=$_POST['loc1'];     $itm1=$_POST['com']; 
 echo  $sql="INSERT INTO `bio_complainttypes`(enqtypeid, `complaint`) VALUES('$loc1','$itm1')";
    $result=DB_query($sql,$db);
         }
     }*/
  
    echo'<table width=98% ><tr><td>'; 
echo'<div >'; 
echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "'>";  
  if (isset($_POST['submit']))
         {
             if($_POST['stop'])
             {
             $jo=$_POST['stop'];
               $loc1=$_POST['loc1'];     $itm1=$_POST['com']; 
        $sql="UPDATE `bio_complainttypes` SET enqtypeid='$loc1',complaint='$itm1' WHERE `id`=$jo";
    $result=DB_query($sql,$db);  
             }
         else
         {
          $loc1=$_POST['loc1'];     $itm1=$_POST['com']; 
                    
   $sql_max="select max(id) from bio_complainttypes ";
    $result_max=DB_query($sql_max,$db); 
    $row_max=DB_fetch_array($result_max);  
       $idnew=$row_max[0];   
       $id= $idnew+1;
   $sql="INSERT INTO `bio_complainttypes`(id,enqtypeid, `complaint`) VALUES('$id','$loc1','$itm1')";
    $result=DB_query($sql,$db);   
         }}
    if(isset($_GET['delete'])){ 
    $id=$_GET['delete'];
$sql= "DELETE FROM `bio_complainttypes` WHERE `id`=$id";
$result=DB_query($sql,$db); 
}
 if(isset($_GET['select'])){ 
    $id=$_GET['select'];
 $sql= "SELECT `enqtypeid`, `complaint` FROM `bio_complainttypes` WHERE `id`=$id";

$result=DB_query($sql,$db); 
  echo"<input type='hidden' name='stop' id='stop' value='".$id."'>"; 
//echo "<input type='hidden' name='alway' value='".$id."'>";
$row=DB_fetch_array($result);
$enq=$row[0];
$com=$row[1];

}

echo"<fieldset style='width:400px;height:170px'; overflow:auto;'>";
echo"<legend><h3>Complaint types</h3></legend>";
  echo'<table ><tr><td>Enquiry types:</td><td>';
  echo '<select name="loc1" id="loc1" style="width:190px">';
  
   $sql="SELECT `enqtypeid`,`enquirytype` FROM `bio_enquirytypes`  ";

$rst=DB_query($sql,$db);
echo '<option value=0></option>';
while($myrowc=DB_fetch_array($rst))
{
    if($myrowc[enqtypeid]==$enq)
    {
         echo '<option selected value='.$myrowc[enqtypeid].'>'.$myrowc[enquirytype].'</option>';
    }
    else
 echo '<option value='.$myrowc[enqtypeid].'>'.$myrowc[enquirytype].'</option>';
 }
 echo "</td></tr>";
 echo "<tr><td>Complaint type:</td>";
 echo '<td><input type="text" name="com" id="com" value="'.$com.'"></tr>';
 echo '<tr><td></td><td><input type="submit" name="submit" onclick="if(valid()==1){return false;}"></td></tr></table></fieldset>';
  
     echo"<fieldset  style='width:400px;'><legend></legend>";
     echo ' <div style="height: 200px; width: 100%; overflow: scroll;">';
    echo "<table width='400px'>";echo"<tr style='background:#585858;color:white'>
    <td>SERIAL NO:</td><td>Enquirytype</td><td>Complaint type</td><td>Select</td><td>Delete</td></tr>"; //<td>CMCAPACITY</td><td>CAPACITY</td>

    $sql="SELECT bio_complainttypes.id,bio_complainttypes.complaint,bio_enquirytypes.enquirytype FROM `bio_complainttypes` inner join bio_enquirytypes on  bio_complainttypes.enqtypeid=bio_enquirytypes.enqtypeid  ";
  $d=1;  
$result3=DB_query($sql,$db);
              while($myrow3=DB_fetch_array($result3))
          {    //echo $myrow[0];
     
          $c=$myrow3['complaint'];
          $s=$myrow3['enquirytype'];
          $id=$myrow3['id'];
         // $g=$myrow3['capacity'];<td>$g</td>
        //  $h=$myrow3['cmcapacity'];<td>$h</td>
                   echo"<tr style='background:#A8A4DB'><td>$d</td><td>$c</td><td>$s</td><td><a href='#' id='$id' onclick='slt(this.id)'>select</a></td><td><a href='#' id='$id' onclick='dlt(this.id)'>delete</a></td></tr>";
                   $d=$d+1;
                 }    
             echo"</table></div></fieldset>";
    echo"</td></tr>";
    echo "</form>" ; 
 
?>
<script type="text/javascript">
function dlt(str){
location.href="?delete=" +str;         
}
function slt(str){
location.href="?select=" +str;         
}
function valid()
{
 str1=document.getElementById("loc1").value;   
  str2=document.getElementById("com").value;
  if(str1=="")
  {
      alert("please enter enquiry type");
      return 1;
  }
   if(str2=="")
  {
      alert("please enter complaint type");
      return 1;
  }
}
</script>