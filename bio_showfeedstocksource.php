<?php
  $PageSecurity = 80;
  include('includes/session.inc');
  $institutionid=$_GET['p'];
  
  $sql1="SELECT bio_fssources.id,
               bio_fssources.source
          FROM bio_feedstocksources,
               bio_fssources,bio_instfeedsource
         WHERE bio_feedstocksources.feedstocksourcename=bio_fssources.id
         AND bio_instfeedsource.sourceid=bio_fssources.id
         AND bio_instfeedsource.instid=$institutionid
      GROUP BY feedstocksourcename";
  $result1=DB_query($sql1, $db);
  echo '<select name="FeedSource" id="feedsource"  style="width:130px" onchange="showFeeds(this.value)">';
  $f=0;
  while($myrow1=DB_fetch_array($result1))
  {  
  if ($myrow1['id']==$_POST['FeedSource']) 
    {
    echo '<option selected value="';
    
    } else {
        if($f==0){echo '<option>';   }
        echo '<option value="';
    }
    echo $myrow1['id'] . '">'.$myrow1['source']; 
    echo '</option>' ;
   $f++; 
   }
  echo '</select>';
?>

