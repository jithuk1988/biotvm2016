<?php
    $PageSecurity = 80;
include('includes/session.inc');
$title = _('LSG Clients');
if($_GET['leadid']!="")
  {
      /*  echo"<table><tr BGCOLOR =#800000>
                <th width='50px'>" . _('SL No') . "</th>
                <th width='100px'>" . _('Customer Name:') . "</th>
                <th width='170px'>" . _('Contact Number') . "</th>
                <th width='150px'>" . _('Area') . "</th>
                 <th width='85px'>" . _('Remarks') . "</th>                     
                </tr></thead>";       */
                echo"</br></br></br>";
    echo "</table>";            
        $parent_leadid=$_GET['leadid'];
        $sql="SELECT bio_cust.custname,concat(bio_cust.custmob,'-</br>',bio_cust.custphone) as 'cont',bio_cust.area1,bio_leads.remarks 
         FROM   bio_cust,bio_leads
         WHERE  bio_cust.cust_id=bio_leads.cust_id        
         AND    bio_leads.leadid IN (SELECT leadid from bio_leads where parent_leadid=$parent_leadid)";
          $result=DB_query($sql,$db);   
                                                                       echo"<table border=1>";
                                                                       echo "<tr style='background-color:#A8A4DB'><td>SL No</td><td>Name</td><td>Contact No</td><td>Area</td><td>Remarks</td></tr>";
                                                                       $sl=1;
                                                                      while ($myrow = DB_fetch_array($result))
                                                                      {
                                                                           echo "<tr><td>".$sl."</td><td>".$myrow[0]."</td><td>".$myrow[1]."</td><td>".$myrow[2]."</td><td>".$myrow[3]."</td></tr>";
                                                                            $sl++;
                                                                      }
  }
                                      
?>
