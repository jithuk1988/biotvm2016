  <?php
$PageSecurity = 80;
 include('includes/session.inc');//
 $title = _('Achievement Lead Sources'); 
 include('includes/header.inc');
 include('includes/sidemenu.php');
 echo '<center><font style="color: #333;
    background:#fff;
    font-weight:bold;
    letter-spacing:0.10em;
    font-size:16px;
    font-family:Georgia;
    text-shadow: 1px 1px 1px #666;">ACHIEVEMENT POLICY FOR LEAD SOURCES  </font></center>';
    //test begin
  $arra= array("","Non marketing staffs", "Dealer", "Network Group", "Promotion Order","Promotion Register","Customer References");
//var_dump($array);
     //echo $arra[0];
     //test  end
 if($_GET['edit']!="")
  {
            $rid=$_GET['edit'];
      $sql4="SELECT * from  bio_achievement_outpolicy where  bio_achievement_outpolicy.id=$rid";  
      $result4=DB_query($sql4,$db); 
       $myrow4=DB_fetch_array($result4);
        $leadso=$myrow4['leadsource'] ; 
        $enqq=$myrow4['enq_type'] ; 
         $ff=$myrow4['from'] ;
         $tt=$myrow4['to'] ;
         $incen=$myrow4['incentive'] ;      
         $edate=$myrow4['e_date'] ; 
  }
   
 if(isset($_POST['submit']))
 {   $actualdate=FormatDateForSQL($_POST['caldate']);  
   
   
    $inta=' INSERT INTO `bio_achievement_outpolicy`(`leadsource`, `enq_type`, `from`, `to`, `incentive`, `e_date`) VALUES ('.$_POST['leadsou'].','.$_POST['enq_type'].','.$_POST['f'].','.$_POST['t'].','.$_POST['incen'].',"'.$actualdate.'")';
    DB_query($inta,$db);
   // echo "<div class=success> SUCCRSSFULL </div>"  ;   
   // print "inset sucessfull"  ;
 } 
 if(isset($_POST['edit']))
 {   $actualdate=FormatDateForSQL($_POST['caldate']); 
 //$sql = "UPDATE `biotech`.`bio_achievement_outpolicy` SET `leadsource` = \'1\', `enq_type` = \'1\', `from` = \'125\', `to` = \'150\', `incentive` = \'1.02\', `e_date` = \'2013-03-01\' WHERE `bio_achievement_outpolicy`.`id` = 4;";                                                                                   
        $upta='UPDATE `bio_achievement_outpolicy` SET `leadsource`='.$_POST['leadsou'].',`enq_type`='.$_POST['enq_type'].',`from`='.$_POST['f'].',`to`='.$_POST['t'].',`incentive`='.$_POST['incen'].',`e_date`="'.$actualdate.'" WHERE bio_achievement_outpolicy.id='.$_POST['eid'].'';
       DB_query($upta,$db); 
     //  echo "<div class=success> SUCCRSSFULL </div>"  ;  
 }
  echo'<table width=98% ><tr><td>'; 
     echo'<div >'; 
echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "'>";  
       
 echo"<fieldset style='width:600px;height:200px'; overflow:auto;'>";
echo"<legend><h3>Achievement Select</h3></legend>";      //  <option  value="'.$leadso.'">'.$lsgnam.'</option>';     
 echo'<table ><tr><td>Lead sources :</td>';             //    echo'  <option  value="'.$leadso.'">'.$arra[$leadso].'</option>';  
echo '<td><select name="leadsou" id="leadsou"  style="width:160px" > ';
$f=0;
       for($i=0;$i<=6;$i++)
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
 echo'<tr><td>Achievement Range</td><td><input type="text" name="f" id="f" value="'.$ff.'" size="8" > to <input type="text" name="t" id="t" value="'.$tt.'" size=8></tr>';
echo'<tr><td>Incentive </td><td><input type="text" name="incen" id="incen" value="'.$incen.'"></td></tr>';
if($rid==null)
{
  echo'<tr><td>Effective Date </td><td><input type="text" name="caldate" id="caldate" class=date alt="'.$_SESSION['DefaultDateFormat']. '" ></td></tr>';  
}
  else
  {
     echo'<tr><td>Date </td><td><input type="text" name="caldate" id="caldate" class=date alt="'.$_SESSION['DefaultDateFormat']. '"  value="'.convertSQLDate($edate).'"></td></tr>';                                                                                                          
      
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
echo"</div></td></tr><tr><td> ";

echo"<div >";
echo"<fieldset style='width:70%'><legend><h3>Achievement List</h3></legend>"; 
echo "<div style='height:200px; width:100%; overflow:scroll;'>"; 
echo"<table style='width:100%'>";
 echo"<tr><th>Slno</th><th>Lead sources</th><th>Enquiry Type</th><th>From</th><th>To</th><th>Incentive </th><th>Effective Date</th><th>Edit</th></tr>";  
$sql3="SELECT bio_achievement_outpolicy.id, bio_enquirytypes.enquirytype, bio_achievement_outpolicy.leadsource, bio_achievement_outpolicy.from, bio_achievement_outpolicy.to, bio_achievement_outpolicy.incentive,bio_achievement_outpolicy.e_date
  FROM bio_achievement_outpolicy
   INNER JOIN `bio_enquirytypes` ON ( bio_enquirytypes.enqtypeid = bio_achievement_outpolicy.enq_type ) LIMIT 0 , 300  ";
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
    else if($leads==5){$leadsources="Promotion Register" ;  }  */   
echo"<td align=center>$slno</td>
<td align=center>".$arra[$leads]."</td> 
<td align=center>".$row3[enquirytype]."</td>
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
{
var from=document.getElementById('f').value;      
var f=0;
if(f==0){f=common_error('leadsou','Please select the Lead Sources');  if(f==1){return f; } } 
if(f==0){f=common_error('enq_type','Please select the Enquiry Type');  if(f==1){return f; } } 
if(from=="")
        {  f=common_error('f','Please enter the Achievement Range From');  if(f==1){return f; } 
        } 
//if(f==0){alert(from); f=common_error('f','Please enter the Achievement Range From');  if(f==1){return f; } }   
if(f==0){f=common_error('t','Please enter the Achievement Range To');  if(f==1){return f; } }  
if(f==0){f=common_error('incen','Please enter the Incentive');  if(f==1){return f; } }  
if(f==0){f=common_error('caldate','Please select the Effective Date ');  if(f==1){return f; } }    
}


</script>
