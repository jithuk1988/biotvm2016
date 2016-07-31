<?php
    $PageSecurity = 80;
  include('includes/session.inc');
  $title = _('Dealers Area'); 
  include('includes/header.inc'); 
  
  $area=$_GET['Area'];
  
  echo'<br>';  
  
  echo"<table style='border:1px solid #F0F0F0;width:100%'><tr><td>";

      echo"<fieldset style='width:450px'><legend>Dealers</legend>";  
      
      $sql="SELECT bio_businessassodetails_enq.custname, 
                   bio_businessassodetails_enq.custphone, 
                   bio_businessassodetails_enq.custmob,
                   bio_businessassodetails_enq.custmail, 
                   bio_businessassociates_enq.advance
            FROM   bio_dealerterritory,bio_businessassociates_enq,bio_businessassodetails_enq 
            WHERE  bio_dealerterritory.buss_id=bio_businessassociates_enq.buss_id
            AND    bio_businessassociates_enq.cust_id=bio_businessassodetails_enq.cust_id 
            AND    bio_dealerterritory.areacode='$area'";
      $result=DB_query($sql,$db);
      
            echo"<table><tr>";
                     
              echo '<th>Slno</th>';
              echo '<th>Dealer Name</th>';
              echo '<th>Contact No:</th>'; 
              echo '<th>Mail</th>';
              echo '<th>Advance Paid</th>'; 
              echo '</tr>';
              
      $k=0;    $slno=1;    
      while($myrow=DB_fetch_array($result))
      {
          
                      if ($k==1)
                      {
                        echo '<tr class="EvenTableRows">';
                        $k=0;
                      }else 
                      {
                        echo '<tr class="OddTableRows">';
                        $k=1;     
                      }
                      
    printf("<td cellpading=2 >%s</td> 
            <td >%s</td>
            <td >%s</td>             
            <td >%s</td>
            <td >%s</td> 
            </tr> ",                                                                                                                                                                                                                                                   
            $slno++,
            $myrow['custname'], 
            $myrow['custphone']."<br />".$myrow['custmob'],
            $myrow['custmail'],   
            $myrow['advance']
            );
                      
      }      
      echo"</table>";
      
      echo"</fieldset>";
      
      
  echo"</td></tr></table>";     
?>
