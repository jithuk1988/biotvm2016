<?php
$PageSecurity = 80;
 include('includes/session.inc');
 $title = _('Target'); 
   include('includes/header.inc');
 include('includes/sidemenu.php');
  echo '<center><font style="color: #333;
    background:#fff;
    font-weight:bold;
    letter-spacing:0.10em;
    font-size:16px;
    font-family:Georgia;
    text-shadow: 1px 1px 1px #666;">TARGET FOR LEAD SOURCES </font></center>'; 
 $arra= array("","Non marketing staffs", "Dealer", "Network Group", "Promotion Order","Promotion Register");  
   if($_GET['edit']!="")
  {   
      $rid=$_GET['edit'];
      $sql4="SELECT * from  bio_target_leadsources where  bio_target_leadsources.id=$rid";  
      $result4=DB_query($sql4,$db); 
       $myrow4=DB_fetch_array($result4);  
       $leadso=$myrow4['leadsource'] ;
/*       if($leadso==1){$lsgnam="Non marketing staffs";}
        elseif($leadso==2){$lsgnam="Dealer";}
        elseif($leadso==3){$lsgnam="Network Group";}
        elseif($leadso==4){$lsgnam="Promotion Order";} 
        elseif($leadso==5){$lsgnam="Promotion Register";}  */
       $enqq=$myrow4['enq_type'] ; 
       $mm=$myrow4['month'] ; 
       $yy=$myrow4['year'] ; 
       $tar=$myrow4['target'] ; 
         
  }
  if(isset($_POST['submit']))
 {   $clmonth=$_POST['closemo']  ;
     if($clmonth==13)
     {    
       // echo "All month are insert" ;
            for($j=1;$j<=12;$j++)
            {
         $inta='INSERT INTO `bio_target_leadsources`( `leadsource`, `enq_type`, `month`, `year`, `target`) VALUES ('.$_POST['leadsou'].','.$_POST['enq_type'].','.$j.','.$_POST['year'].','.$_POST['target'].')';
       DB_query($inta,$db); 
            }
     }
     else
     {
         
     
    $inta='INSERT INTO `bio_target_leadsources`( `leadsource`, `enq_type`, `month`, `year`, `target`) VALUES ('.$_POST['leadsou'].','.$_POST['enq_type'].','.$_POST['closemo'].','.$_POST['year'].','.$_POST['target'].')';
     DB_query($inta,$db);
     }
 } 
   if(isset($_POST['edit']))
 { 
        $upta='UPDATE `bio_target_leadsources` SET `leadsource`="'.$_POST['leadsou'].'",`enq_type`="'.$_POST['enq_type'].'",`month`="'.$_POST['closemo'].'",`year`="'.$_POST['year'].'",`target`="'.$_POST['target'].'" WHERE `id`="'.$_POST['eid'].'"';
        DB_query($upta,$db); 
 }
 echo'<table width=90% ><tr><td>'; 
     echo'<div >'; 
echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "'>";  
       
 echo"<fieldset style='width:600px;height:200px'; overflow:auto;'>";
echo"<legend><h3>Select</h3></legend>";

   echo'<table ><tr><td>Lead sources :</td>';
echo '<td><select name="leadsou" id="leadsou"  style="width:160px" >';
$f=0;
       for($i=0;$i<=5;$i++)
       {
if ($i==$leadso)  
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
    echo $i . '">'.$arra[$i];
    echo '</option>';
    $f++;                      //  echo'  <option  value="'.$i.'">'.$arra[$i].'</option>';              
      
       }   
     /* echo'  <option  value="'.$leadso.'">'.$lsgnam.'</option>'; 
if($leadso==1){
            echo'<option  value="2">Dealer </option>
        <option  value="3">Network Group</option>
        <option  value="4">Promotion Order</option>
        <option  value="5">Promotion Register</option>'; 
        } 
else if($leadso==2){
echo'<option value="1">Non marketing staffs </option> 
        <option  value="3">Network Group</option>
                <option  value="4">Promotion Order</option>
        <option  value="5">Promotion Register</option>'; 
        }
else if($leadso==3){
echo'<option value="1">Non marketing staffs </option> 
        <option  value="2">Dealer </option> 
        <option  value="4">Promotion Order</option>
        <option  value="5">Promotion Register</option>'; 
        }
else if($leadso==4){
echo'<option value="1">Non marketing staffs </option> 
        <option  value="2">Dealer </option> 
        <option  value="3">Network Group</option>
        <option  value="5">Promotion Register</option>'; 
        }
else if($leadso==5){
echo'<option value="1">Non marketing staffs </option> 
        <option  value="2">Dealer </option>
        <option  value="3">Network Group</option> 
        <option  value="4">Promotion Order</option>'; 
        }
else{ 
    echo' <option value="1">Non marketing staffs </option>
        <option  value="2">Dealer </option> 
        <option  value="3">Network Group</option>
        <option  value="4">Promotion Order</option>
        <option  value="5">Promotion Register</option>'; 
        }
    echo '</select></td></tr>';              */
           
 //echo'<tr id=inrow></tr>';
  
   $sql="SELECT * FROM bio_enquirytypes where bio_enquirytypes.enqtypeid in(1,2,3)";
    $result=DB_query($sql,$db);
    
    echo"<tr><td style='width:50%'>Enquiry Type</td><td>";
    echo '<select name="enq_type" id="enq_type"  style="width:160px">';
    $f=0;
  while($myrow1=DB_fetch_array($result))
  {  
  if ($myrow1['enqtypeid']==$enqq)  
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
    echo $myrow1['enqtypeid'] . '">'.$myrow1['enquirytype'];
    echo '</option>';
    $f++;
   } 
  echo '</select></td></tr>';          
       
   //  <td><div ></div></td>


 echo"<tr><td>Close Year</td>";
  echo '<td><select name="year" id="year"  style="width:90px">';  
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
     echo'<tr><td>Closing Month*</td>';
echo '<td><select name="closemo" id="closemo" class style="width:146px" >';
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
echo'<tr><td>Target*</td>';
echo'<td><input type="text" name="target" id="target" value="'.$tar.'" ></td></tr>';
if(isset($_GET['edit']))
 {
 echo'<tr><td></td><td><input type="submit" name="edit"  value="Edit" onclick="if(log_in()==1)return false;" ></td></tr>'; 
} 
else
{echo'<tr><td></td><td><input type="submit" name="submit"  value="submit" onclick="if(log_in()==1)return false;" ></td></tr>'; }  
 echo'</table>'; 
  echo "<input type='hidden' name='eid' id='eid' value=$rid >";     
  echo'</form>';
 echo'</div>';
 echo'<div id="Achievement">';
echo"<fieldset style='width:78%'><legend><h3>Achievement List</h3></legend>"; 
echo "<div style='height:200px; width:100%; overflow:scroll;'>";  
echo"<table style='width:100%' border=0> ";  
echo"<tr><th>Slno</th><th>Lead sources</th><th>Enquiry Type</th><th>Closing Month</th><th>Closing Year</th><th>Target</th></tr>";  
  $sql3="SELECT bio_target_leadsources.id, bio_enquirytypes.enquirytype, bio_target_leadsources.leadsource, bio_target_leadsources.month, bio_target_leadsources.year, bio_target_leadsources.target
  FROM bio_target_leadsources
   INNER JOIN `bio_enquirytypes` ON ( bio_enquirytypes.enqtypeid = bio_target_leadsources.enq_type ) LIMIT 0 , 300  ";
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
    $leads=$row3[leadsource];
/*    if($leads==1){$leadsources="Non marketing staffs" ;}
    else if($leads==2){$leadsources="Dealer " ;  }
    else if($leads==3){$leadsources="Network Group " ;  }
    else if($leads==4){$leadsources="Promotion Order" ;  }     
    else if($leads==5){$leadsources="Promotion Register" ;  } */ 
echo"<td>$slno</td>";
//echo"<td>$leadsources</td> ";
echo"<td align=center>".$arra[$leads]."</td> 
<td>".$row3[enquirytype]."</td>
<td>".date("F",mktime(0,0,0,$row3[month],10))."</td>   
<td>".$row3[year]."</td>
<td>".$row3[target]."</td> ";
echo'<td><a href="#" id="'.$id.'" onclick="edit(this.id)">Edit</a></td>'; 
$slno++;

    }
echo '<tbody>';
echo"</tr></tbody></table>";
echo"</div>";
echo"</fieldset>"; 
echo"</div>";  
 echo'</td></tr></table>';
 
?>
<script>
 function edit(str)
 {   //alert(str);
 location.href="?edit=" +str;         
 
}
function log_in()
{
var f=0;
if(f==0){f=common_error('leadsou','Please select the Lead Sources');  if(f==1){return f; } } 
if(f==0){f=common_error('enq_type','Please select the Enquiry Type');  if(f==1){return f; } } 
if(f==0){f=common_error('closemo','Please select the Close Month');  if(f==1){return f; } }   
if(f==0){f=common_error('year','Please enter the Close Year');  if(f==1){return f; } }  
if(f==0){f=common_error('target','Please enter the Target value');  if(f==1){return f; } }  
}



</script>
