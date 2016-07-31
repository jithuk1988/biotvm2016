<?php

$PageSecurity = 80;   
include('includes/session.inc');

$title = _('End User');

include('includes/header.inc');

echo '<p class="page_title_text"><img src="'.$rootpath.'/css/'.$theme.'/images/magnifier.png" title="' . _('Search') .
    '" alt="" />' . ' ' . _('End User') . '</p>';

echo '<form action="' . $_SERVER['PHP_SELF'] . '" method="POST">';


echo '<table class="selection">'; 
echo '<tr>';

 echo"<table style='border:1px solid #F0F0F0;width:90%' ; >";   
 
 $sql1="SELECT debtorsmaster.name 
        FROM   bio_childcustomer,debtorsmaster 
        WHERE  bio_childcustomer.dealercode=debtorsmaster.debtorno
        AND    bio_childcustomer.orderno=".$_GET['orderno']."";
 $result1=DB_query($sql1,$db);
 $myrow1=DB_fetch_array($result1);
 
 echo"<tr><td><b>DEALER NAME:&nbsp;&nbsp;<i>".$myrow1['name']."</td></tr>";       
 
 
 $sql="SELECT bio_childcustomer.name,bio_childcustomer.mobileno,bio_childcustomer.phoneno,bio_childcustomer.area1,
              stockmaster.description,
              bio_district.district
       FROM   bio_childcustomer,stockmaster,bio_district 
       WHERE  stockmaster.stockid=bio_childcustomer.stockid
       AND    bio_district.cid=bio_childcustomer.cid AND bio_district.stateid=bio_childcustomer.stateid AND bio_district.did=bio_childcustomer.did
       AND    bio_childcustomer.orderno='".$_GET['orderno']."'";
       
 $result=DB_query($sql,$db);
       
 
 echo"<tr>";
 echo"<th>Customer Name</th><th>Contact No</th><th>Area</th><th>District</th><th>Plant</th>"; 
 
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
                      
                      
                             
                printf("<td >%s</td>
                        <td >%s</td> 
                        <td >%s</td> 
                        <td >%s</td>  
                        <td >%s</td> 
                        </tr>",                                                                                                                                                                                                                                                   
                        $myrow['name'],
                        $myrow['phoneno'],
                        $myrow['area1'], 
                        $myrow['district'],
                        $myrow['description']
                        );
 
 }
 
 echo"</tr></table>";
?>
