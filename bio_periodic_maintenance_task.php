<?php
$PageSecurity = 80;  
include('includes/session.inc'); 

        // echo"jjj";
if($_GET['maincatid']){    
//$planttype=$_GET['maincatid'];
$sql="SELECT stockmaster.stockid,stockmaster.description FROM stockmaster WHERE stockmaster.categoryid='".$_GET['maincatid']."'";
  $result=DB_query($sql,$db);
  echo'<td><select id="plantname" name="plantname" onchange=showschedule(this.value);>';//
    $f=0;
  while($myrow=DB_fetch_array($result))
  {  
      
  if ($myrow['stockid']==$planttype)  
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
  echo '</select></td>';


}
if($_GET['plantname']){
    
echo"<fieldset ><legend>Periodic Maintenance Tasks</legend>";
   echo'<table width=1000px><tr><th width=50px>Sl No.</th><th width=200px>Days to start from installation </th><th>Description</th></tr>';
    
   
   $sql_new="SELECT count(id) as count FROM bio_def_cstask where stockcode='".$_GET['plantname']."' AND cstype='".$_GET['cstype']."' ";
   $result_new=DB_query($sql_new,$db);
   $myrow_new=DB_fetch_array($result_new);

   if($myrow_new['count']==0)
   {
       for($i=1;$i<=4;$i++)
       {
       if($i%2==0)
       {
           echo '<tr class="EvenTableRows">';
           
       } else{echo '<tr class="OddTableRows">';
           
       }
      echo "<td>$i</td><td><input type=text name='new_days".$i."' id='new_days".$i."'></td><td><input type=text name='new_des".$i."' id='new_des".$i."' size='70px'></td></tr>"; }
      echo'<tr><td colspan=3><p><div class="centre">
         <input type=submit name=submit_new value="' . _('Submit') . '" onclick="if(validation()==1) return false;"></td></tr>';
   }
   else
   {
       $sql="SELECT * FROM bio_def_cstask where stockcode='".$_GET['plantname']."' AND cstype='".$_GET['cstype']."' ";
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
        echo'<td><a href="bio_periodic_maintenance.php?tno='.$myrow['taskno'].'&main1='.$_GET['main'].'&sub='.$_GET['plantname'].'&cstype='.$_GET['cstype'].'&delete=yes" onclick=\'return confirm("' .
                _('Are you sure you wish to delete this Office?') . '");\'>' . _('Delete') . '</td>';  
        $slno++;
    }
    echo'<input type="hidden" value='.$slno.' id="count" name="count">';
    echo"<tr><td>Enter here:</td>";
    echo"<td><input type=text name='perio_days' id='perio_days'></td>";
    echo"<td><input type=text size='70px' name='perio_des' id='perio_des'></dt>";
   echo'<td><input type="submit" name="submit" value="' . _('Add New') . '" onclick= "if(validate()==1)return false;"></td></tr>';
    

    
    echo"</fieldset>";
   }
   
}

if($_GET['plantname1']){    
$planttype=$_GET['plantname1'];
$sql="SELECT *FROM bio_def_cstask where stockcode='".$_GET['plantname1']."' ";
   $result=DB_query($sql,$db);
  echo'<td><select id="task2" name="task2" >';//
    $f=0;

  while($myrow=DB_fetch_array($result))
  {  
      
  if ($myrow['stockid']==$planttype)  
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
   
  echo '</select></td>';


}

?>
