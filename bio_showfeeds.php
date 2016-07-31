<?php
  $PageSecurity = 80;
  include('includes/session.inc');
  $sourceid=$_GET['p'];
  
  $sql1="SELECT bio_feedstocksources.id,
                bio_feedstocks.feedstocks 
           FROM bio_feedstocksources,bio_feedstocks
            WHERE feedstocksourcename='".$sourceid."'
            AND bio_feedstocksources.id=bio_feedstocks.id";
  $result1=DB_query($sql1, $db);
  echo '<select name="feedstock" id="feedstock" style="width:130px" onchange="showInputs(this.value)">';
  $f=0;
  while($myrow1=DB_fetch_array($result1))
  {  
  if ($myrow1['id']==$_POST['feedstock']) 
    {
    echo '<option selected value="';
    
    } else {
        if($f==0){echo '<option>';   }
        echo '<option value="';
    }
    echo $myrow1['id'] . '">'.$myrow1['feedstocks']; 
    echo '</option>' ;
   $f++; 
   }
  echo '</select>';
?>

