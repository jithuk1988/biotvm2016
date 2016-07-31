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
    
echo"<fieldset ><legend>Tasks</legend>";
   

    
   echo'<table width=1000px><tr><th>Sl No.</th><th>Task</th><th>Days to complete</th><th>Pevious Task</th></tr>';
   $sql="SELECT *FROM bio_def_cstask where stockcode='".$_GET['plantname']."' AND cstype=1";
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
         $sql_prevtask="SELECT taskdescription from bio_def_cstask where
        stockcode='".$_GET['plantname']."' AND taskno='".$myrow['prevtask']."'
        AND cstype=1";
        $result_prevtask=DB_query($sql_prevtask,$db);
        $row_prevtask=DB_fetch_array($result_prevtask);
        echo" <td>".$row_prevtask['taskdescription']."</td>";
        }
        //$SelectedType=$_GET['plantname']&$myrow['taskno'];
        echo" <td width='50px'><a style='cursor:pointer;' id='".$myrow['taskno']."' onclick=edit_task(this.id);>" . _('Edit') . "</a></td>";
        $slno++;
    }
    echo'<input type="hidden" value='.$slno.' id="count" name="count">';

    
    echo"</fieldset>";
}

if($_GET['plantname1']){    
$planttype=$_GET['plantname1'];
$sql="SELECT *FROM bio_def_cstask where stockcode='".$_GET['plantname1']."'  AND cstype=1";
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
