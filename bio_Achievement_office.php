<?php
$PageSecurity = 80;
 include('includes/session.inc');
 $title = _('Achievement Office'); 
 include('includes/header.inc');
 include('includes/sidemenu.php');
 echo '<center><font style="color: #333;
    background:#fff;
    font-weight:bold;
    letter-spacing:0.10em;
    font-size:16px;
    font-family:Georgia;
    text-shadow: 1px 1px 1px #666;">ACHIEVEMENT FOR OFFICE  </font></center>';
 if($_GET['edit']!="")
  {   
      $rid=$_GET['edit']; 
      $sql4="SELECT * from  bio_achievement_office where  bio_achievement_office.id=$rid";
     $result4=DB_query($sql4,$db); 
      $myrow4=DB_fetch_array($result4); 
      $offid=$myrow4['office'] ; 
      $ff=$myrow4['from'] ; 
      $tt=$myrow4['to'] ;
      $incen=$myrow4['incentive'] ;          
      $edate=$myrow4['e_date'] ;         
      
  }
if(isset($_POST['submit']))
 {   $actualdate=FormatDateForSQL($_POST['caldate']);  
   $inta='insert into bio_achievement_office(`office`,`from`,`to`,`incentive`,`e_date`) values ('.$_POST['office'].','.$_POST['f'].','.$_POST['t'].','.$_POST['incen'].',"'.$actualdate.'")'; 
  DB_query($inta,$db);
   // print "inset sucessfull"  ;
 } 
  if(isset($_POST['edit']))
 {   $actualdate=FormatDateForSQL($_POST['caldate']); 
         $upta='UPDATE `bio_achievement_office` SET `office`='.$_POST['office'].',`from`='.$_POST['f'].',`to`='.$_POST['t'].',`incentive`='.$_POST['incen'].',`e_date`="'.$actualdate.'" WHERE bio_achievement_office.id='.$_POST['eid'].'';
       DB_query($upta,$db); 
 }
 echo'<table width=98% ><tr><td>'; 
echo'<div >'; 
echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "'>";  
echo"<fieldset style='width:600px;height:170px'; overflow:auto;'>";
echo"<legend><h3>Achievement Select</h3></legend>";
 echo'<table ><tr><td>Office :</td>';
 echo"<td><select name='office' id='office' style='width:150px' onchange='showinrow()'> ";
echo '<option value=0></option>';   
    $sql1="select * from bio_office";
    $result1=DB_query($sql1,$db);
    while($row1=DB_fetch_array($result1))
    {
       
    if ($row1['id']==$offid)
        {  
    echo '<option selected value="';
        } else {
    echo '<option value="';
        } 
        echo $row1['id'] . '">'.$row1['office'];
        echo '</option>'; 
  
    }
    echo '</select></td></tr>';    
echo'<tr><td>Achievement Range</td><td><input type="text" name="f" id="f" value="'.$ff.'" size="8" > to <input type="text" name="t" id="t" value="'.$tt.'" size=8></tr>';
echo'<tr><td>Incentive </td><td><input type="text" name="incen" id="incen" value="'.$incen.'"  style="width:150px"></td></tr>';
if($rid==null)
{
  echo'<tr><td>Effective Date </td><td><input type="text" name="caldate" id="caldate" class=date alt="'.$_SESSION['DefaultDateFormat']. '"  style="width:150px"  ></td></tr>';  
}
  else
  {
     echo'<tr><td>Date </td><td><input type="text" name="caldate" id="caldate" class=date alt="'.$_SESSION['DefaultDateFormat']. '"    value="'.convertSQLDate($edate).'" style="width:150px"></td></tr>';                                                                                                          
      
  }
if($rid==null)
{ 
echo'<tr><td></td><td><input type=submit name=submit value="submit" onclick="if(log_in()==1)return false;"></td></tr>';
}
else
{
echo'<tr><td></td><td><input type=submit name=edit value="Edit" onclick="if(log_in()==1)return false;" ></td></tr>';       
}
  echo "<input type='hidden' name='eid' id='eid' value=$rid >";     
echo"</table>";  
echo"</fieldset>";         
echo"</form>";
echo"</div></td></tr><tr><td>";
echo"<div >";
echo"<fieldset style='width:70%'><legend><h3>Achievement Details</h3></legend>"; 
echo "<div style='height:200px; width:100%; overflow:scroll;'>"; 
echo"<table style='width:100%'>";
 echo"<tr><th>Slno</th><th>Office</th><th>From</th><th>To</th><th>Incentive </th><th>Effective Date</th><th>Edit</th></tr>";  
$sql3="select 
bio_achievement_office.id,
bio_office.office, 
bio_achievement_office.from,
bio_achievement_office.to,
bio_achievement_office.incentive,
bio_achievement_office.e_date
from bio_achievement_office,bio_office
where bio_achievement_office.office=bio_office.id
";
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
<td align=center>".$row3[office]."</td>
<td align=center>".$row3[from]."</td>   
<td align=center>".$row3[to]."</td>
<td align=center>".$row3[incentive]."</td> 
<td align=center>".convertSQLDate($row3[e_date])."</td> ";    
echo'<td align=center><a href="#" id='.$id.' onclick="edit(this.id)">Edit</a></td>'; 
$slno++;

    }
echo '<tbody>';
echo"</tr></tbody></table>";
echo"</td></tr>";   
echo"</table>";  
  

?>
<script>
 function edit(str)
 {   //alert(str);
 location.href="?edit=" +str;         
 
}
function log_in()
{   //alert("hello");
var from=document.getElementById('f').value;  
var f=0;
if(f==0){f=common_error('office','Please select the Office');  if(f==1){return f; } } 
if(from=="")
        {f=common_error('f','Please enter the Achievement Range From');  if(f==1){return f; }}
//if(f==null){f=common_error('f','Please enter the Achievement Range From');  if(f==1){return f;  
if(f==0){f=common_error('t','Please enter the Achievement Range To');  if(f==1){return f; } }  
if(f==0){f=common_error('incen','Please enter the Incentive');  if(f==1){return f; } }  
if(f==0){f=common_error('caldate','Please select the Effective Date ');  if(f==1){return f; } }    
}
</script>  