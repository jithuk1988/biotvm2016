<?php
  $PageSecurity = 80;
include('includes/session.inc');
include ('includes/SQL_CommonFunctions.inc');
   if($_GET['orderno'])
          {
              $orderno=$_GET['orderno'];  
              echo"<input type='hidden' name='stop' id='stop' value='2'>"; 
              echo "<input type=hidden name=orderno value='".$orderno."'>";
           $sql="SELECT
    `salesorders`.`orderno`
    , `custbranch`.`brname`
    , `orderamount`.`debtorno`
    , `orderamount`.`ordervalue`
    ,bio_installation_status.installed_date
    , `orderamount`.`orddate`
    , `debtorpaid`.`paid`
    , `orderplant`.`stkcode` as 'stockid'
    , IFNULL(orderamount.ordervalue- ifnull (`debtorpaid`.`paid`,0)-ifnull(ordersubsidy.totsubsidy,0),0) AS 'balance'
    , `salesorders`.`orderno`
    , `salesorders`.`orderno`
    , `salesorders`.`orderno`
   , concat(`custbranch`.`phoneno`,'<br>',`custbranch`.`faxno`) as 'phoneno'
    , `custbranch`.`LSG_type`,
      `orderamount`.`ordervalue`- ifnull(ordersubsidy.totsubsidy,0) as 'netpayable'    ,
     `bio_corporation`.`corporation`,
  `bio_municipality`.`municipality`,
  `bio_panchayat`.`name` AS panchayat,  
  `salesorders`.`contactphone` ,
  `custbranch`.`did` ,  
  `orderamount`.`debtorno` ,
  `bio_district`.`district`
FROM
    `salesorders`
    INNER JOIN `custbranch` 
        ON (`salesorders`.`debtorno` = `custbranch`.`debtorno`)
        left JOIN debtortrans ON (custbranch.debtorno=debtortrans.debtorno)
    LEFT JOIN `bio_panchayat` 
        ON (`custbranch`.`block_name` = `bio_panchayat`.`id`) AND (`custbranch`.`cid` = `bio_panchayat`.`country`) AND (`custbranch`.`LSG_name` = `bio_panchayat`.`block`) AND (`custbranch`.`stateid` = `bio_panchayat`.`state`) AND (`custbranch`.`did` = `bio_panchayat`.`district`)
    LEFT JOIN `bio_municipality` 
        ON (`custbranch`.`did` = `bio_municipality`.`district`) AND (`custbranch`.`stateid` = `bio_municipality`.`state`) AND (`custbranch`.`cid` = `bio_municipality`.`country`) AND (`custbranch`.`LSG_name` = `bio_municipality`.`id`)
    LEFT JOIN `bio_corporation` 
        ON (`custbranch`.`LSG_name` = `bio_corporation`.`district`) AND (`custbranch`.`stateid` = `bio_corporation`.`state`) AND (`custbranch`.`did` = `bio_corporation`.`district`) AND (`custbranch`.`cid` = `bio_corporation`.`country`)
    LEFT JOIN `bio_district` 
        ON (`custbranch`.`cid` = `bio_district`.`cid`) AND (`custbranch`.`did` = `bio_district`.`did`) AND (`custbranch`.`stateid` = `bio_district`.`stateid`)
    LEFT JOIN `ordersubsidy` 
        ON (`salesorders`.`orderno` = `ordersubsidy`.`orderno`)
    inner JOIN `orderamount` 
        ON (`salesorders`.`orderno` = `orderamount`.`orderno`)
    left JOIN `debtorpaid` 
        ON (`salesorders`.`debtorno` = `debtorpaid`.`debtorno`)
    inner JOIN `orderplant` 
        ON (`salesorders`.`orderno` = `orderplant`.`orderno`)
        left JOIN `bio_installation_status` 
        ON (`salesorders`.`orderno` = `bio_installation_status`.`orderno`)
                WHERE  orderamount.debtorno like 'D%' AND `salesorders`.`orderno`= $orderno
                group by `salesorders`.`orderno`";
                $result=DB_query($sql,$db);
                $myrow=DB_fetch_array($result); 
                $sql1="select `stockmaster`.`description` from `stockmaster` where `stockmaster`.`stockid`='".$myrow['stockid']."'";
$result1=DB_query($sql1,$db);
$myrow1=DB_fetch_array($result1);
 if($myrow['LSG_type']==0)
    {
        $LSG="---";
    }
    else  if($myrow['LSG_type']==1)
    {
        $LSG=$myrow['corporation']."(C)";
    }
    elseif($myrow['LSG_type']==2)
    {
        $LSG=$myrow['municipality']."(M)";
    }elseif($myrow['LSG_type']==3)
    {
        $LSG=$myrow['panchayat']."(P)";
    }
               
                $cname=$myrow['brname'];  
                /*if($myrow['phoneno']!='' && $myrow['phoneno']!='-'){
                 $cph=$myrow['phoneno']; 
                  }else{
                 $cph=$myrow['faxno']; 
                 }       */
                $debtor=$myrow['debtorno'] ;
                 $cph=$myrow['phoneno']; 
                 $district=$myrow['district'];
                 
                //$amount=$myrow['amount']; 
                 $full=$myrow['netpayable']; 
                 $paid=$myrow['paid'];
                 $balance=$myrow['balance'];
          
          echo '<td align="left" style="width:470px">';
             echo"<fieldset style='width:440px;height:220px '>"; 
          echo"<legend>Customer Details</legend>";
          
           echo"<table width=100%>";
           echo "<tr><td><b>Debtor no</b></td><td><b>:</b></td><td><b>".$debtor."</b></td></tr>";
            echo "<tr><td><b>Customer name</b></td><td><b>:</b></td><td><b>".$cname."</b></td></tr>";
            echo "<tr><td><b>Customer contact</b></td><td><b>:</b></td><td><b>".$cph."</b></td></tr>"; 
               echo "<tr><td><b>Plant name</b></td><td><b>:</b></td><td><b>".$myrow1[0]."</b></td></tr>";          
            echo "<tr><td><b>Customer district</b></td><td><b>:</b></td><td><b>".$district."</b></td></tr>";
            echo "<tr><td><b>Customer LSG</b></td><td><b>:</b></td><td><b>".$LSG."</b></td></tr>";    
            echo "<tr><td><b>Net amount</b></td><td><b>:</b></td><td><b>".$full."</b></td></tr>";
    
            echo "<tr><td><b>Paid amount</b></td><td><b>:</b></td><td><b>".$paid."</b></td></tr>";   
            echo "<tr><td><b>Balance</b></td><td><b>:</b></td><td><b>".$balance." </b></td></tr>";   
           // echo "<tr><td><b>Remarks</b></td><td><b>:</b></td><td><b><textarea name='' cols='30'  rows='3' readonly >".$remarks."</textarea></b></td></tr>";    
         echo'</table>';
          echo '</fieldset>';
        /*echo '<td>';  
         echo"<fieldset style='width:440px;height:280px'>"; 
         echo"<legend>Call Details</legend>";*/
         echo"<br />";  
 
      echo"<table><tr>";
 
          } 
?>
