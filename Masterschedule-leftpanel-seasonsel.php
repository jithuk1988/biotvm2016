<?php
  $PageSecurity = 11;
    // echo 'ghfghfgh' ;
 include('includes/session.inc');
echo '<td>' . _('Season') . "*</td><td>";
  $Year=2012; 
  $SeasonID=$_GET['p'];
  $sql="SELECT m_season.season_id,
             m_season.season_sub_id,
             m_sub_season.seasonname
      FROM m_season,m_sub_season 
      WHERE m_season.season_id=$SeasonID    AND
              m_season.season_sub_id=m_sub_season.season_sub_id";
$result=DB_query($sql,$db);
$myrow=DB_fetch_array($result);
$SeasonnameID=$myrow[1];
$SeasonID=$myrow[0];
$Seasonname=$myrow[2];
  
                     $sql1 = 'SELECT m_sub_season.seasonname,
                     season_sub_id
                FROM m_sub_season';
        $result1 = DB_query($sql1,$db);
        
        
        

        $f=0;
        echo"<select name='Season' id='masterschedule_selsea'>";
        while ($myrow1 = DB_fetch_array($result1)) {
            
            if ( $myrow1['season_sub_id']==$SeasonnameID) {
                echo "<option selected value='" . $myrow1['season_sub_id'] . "'>" . $myrow1['seasonname']; 
            } else  if ($f==0){
                         
        echo '<option>';

            }else       {     
                echo "<option value='" . $myrow1['season_sub_id'] . "'>" . $myrow1['seasonname']; 
                
            }
           
        $f++;    
        } //end while loop
        echo '</select>'; 
echo"</td>";  
?>
