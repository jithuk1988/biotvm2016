<?php
$PageSecurity = 80;
 include('includes/session.inc');
 $title = _('Target Office'); 
 include('includes/header.inc');
 include('includes/sidemenu.php');
 echo '<center><font style="color: #333;
    background:#fff;
    font-weight:bold;
    letter-spacing:0.10em;
    font-size:16px;
    font-family:Georgia;
    text-shadow: 1px 1px 1px #666;">TARGET FOR OFFICE  </font></center>';
     if($_GET['edit']!="")
  {
            $rid=$_GET['edit'];
      $sql4="SELECT * from  bio_target_office where  bio_target_office.id=$rid";  
      $result4=DB_query($sql4,$db); 
       $myrow4=DB_fetch_array($result4);
       $offid=$myrow4['office'] ; 
       $yy=$myrow4['year'] ;
       $mm=$myrow4['month'] ;
       $targ=$myrow4['target'] ;
           
  }
 if(isset($_POST['submit']))
 {  
      
        //insert month slection
     
       $clmonth=$_POST['closemo']  ;
     if($clmonth==13)
     {    
       // echo "All month are insert" ;
            for($j=1;$j<=12;$j++)
            {      //update table structure add  
                 $inta='INSERT INTO `bio_target_office`( `office`, `year`,`month`, `target`) VALUES ('.$_POST['office'].','.$_POST['year'].','.$j.','.$_POST['tar'].') ';
       DB_query($inta,$db); 
            }
     }
     else
     {
                  $inta='INSERT INTO `bio_target_office`( `office`, `year`,`month`, `target`) VALUES ('.$_POST['office'].','.$_POST['year'].','.$_POST['closemo'].','.$_POST['tar'].') ';
        DB_query($inta,$db); 
         
     }    
            
/*         $inta='INSERT INTO `bio_target_office`( `office`, `year`, `target`) VALUES ('.$_POST['office'].','.$_POST['year'].','.$_POST['tar'].') ';
        DB_query($inta,$db); */  
 }
 if(isset($_POST['edit']))
 {
     $upta='UPDATE `bio_target_office` SET `office`='.$_POST['office'].',`year`='.$_POST['year'].',`month`='.$_POST['closemo'].',`target`='.$_POST['tar'].' WHERE bio_target_office.id='.$_POST['eid'].'';
DB_query($upta,$db);  
 }
 echo'<table width=98% ><tr><td>'; 
echo'<div >'; 
echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "'>";  
echo"<fieldset style='width:600px;height:200px'; overflow:auto;'>";
echo"<legend><h3>Target Select</h3></legend>";
 echo'<table ><tr><td>Office :</td>';
 echo"<td><select name='office' id='office' style='width:120px' onchange='showinrow()'> ";
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
echo '</select></td>';
echo"<tr><td>Year :</td>";
  echo '<td><select name="year" id="year"  style="width:80px">';  
   echo '<option value=0></option>'; 
 
  for($i=2009;$i<=2030;$i++)
  {
      
      
      if ($i==$yy)
        {
            echo '<option selected value="';
        } else {
            echo '<option value="';
        }
        echo $i . '">'.$i;
        echo '</option>';
     
   
  } echo'</select></td>';

  //-------Month  selection---------
  echo'<tr><td>Closing Month*</td>';
echo '<td><select name="closemo" id="closemo" class style="width:80px" >';
$sql="select * from m_sub_season";
    $result=DB_query($sql,$db);
    echo'<option selected value=0></option>'; 

    while($row=DB_fetch_array($result))
    {
        if ($row['season_sub_id']==$mm)
        {
            echo '<option selected value="';
        } else {
            echo '<option value="';
        }
        echo $row['season_sub_id'] . '">'.$row['seasonname'];
        echo '</option>';
    }
      if(!isset($_GET['edit']))
    {
          echo'<option  value=13>all</option>';   
    }
    echo'</select></td></tr>';
                                                    
 echo'<tr><td>Target:</td>';                                    
 echo"<td><input type='text' name='tar' id='tar'  value='$targ' style=width:120px></td></tr>";
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
echo"</div></td></tr><tr><td> ";
echo"<div >";
echo"<fieldset style='width:70%'><legend><h3>Target Office Details</h3></legend>"; 
echo "<div style='height:300px; width:100%; overflow:scroll;'>"; 
echo"<table style='width:100%'>";
 echo"<tr><th>Slno</th><th>Office</th><th>Year</th><th>Month</th><th>Target</th><th>Edit</th></tr>"; 
  $sql3="SELECT bio_target_office.id,bio_office.office,year,month,target
  FROM bio_target_office
   INNER JOIN `bio_office` ON ( bio_target_office.office = bio_office.id )  ";
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
<td align=center>".$row3[year]."</td> 
<td align=center>".date("F",mktime(0,0,0,$row3[month],10))."</td>  
<td align=center>".$row3[target]."</td> ";
  
echo'<td align=center><a href="#" id='.$id.' onclick="edit(this.id)">Edit</a></td>'; 
$slno++;

    }

 echo '<tbody>';
echo"</tr></tbody></table>";
echo"</div></div>"; 
 echo"</td></tr>";   
echo"</table>";
  
?>
<script>
 function edit(str)
 {   //alert(str);
 location.href="?edit=" +str;         
 
}
function log_in()
{            
var f=0;
if(f==0){f=common_error('office','Please select the Office');  if(f==1){return f; } } 
if(f==0){f=common_error('year','Please select the Year');  if(f==1){return f; } } 
if(f==0){f=common_error('closemo','Please select the Month');  if(f==1){return f; } }  
 if(f==0){f=common_error('tar','Please enter the Target');  if(f==1){return f; } }  
  
}
</script>