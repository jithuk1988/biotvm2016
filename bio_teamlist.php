<?php
  $PageSecurity = 80;
  include('includes/session.inc');
  
  $officeid=$_GET['off'];
  
  echo"<select name='fteam' id='fteam' style='width:245px'>";
      $sql="SELECT bio_leadteams.teamid,bio_leadteams.teamname FROM bio_leadteams 
                WHERE office_id=".$officeid;
      $result=DB_query($sql,$db);

      $f=0;
      while($myrow1=DB_fetch_array($result))
      {  
        if ($myrow1['teamid']==$_POST['fteam'])  
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
    echo $myrow1['teamid'] . '">'.$myrow1['teamname'];
    echo '</option>';
    $f++;
   }   
   echo"</select>";
  
?>
