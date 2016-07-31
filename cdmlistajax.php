<?php
  $PageSecurity = 80;
 include ('includes/session.inc');
      $user=$_SESSION['UserID'];
 $sql="SELECT `realname` FROM `www_users` WHERE `userid` LIKE '".$user."'";
 $res=DB_query( $sql,$db);
 $row=DB_fetch_array($res);
 $username=$row[0];
     $debtorno=$_GET['str']; 
      $sql_test="select status from cdmlist where debtorno='".$_GET["str"]."'";
         $result_test=DB_query($sql_test,$db); 
                 $row_test=DB_fetch_array($result_test);
        if($row_test[0]==0)
              {
                 $sql_ph="SELECT brname,braddress1,braddress2,bio_district.district,faxno,phoneno,state 
                  FROM  custbranch,bio_district,bio_state 
                  WHERE (custbranch.cid=bio_district.cid AND custbranch.stateid=bio_district.stateid AND custbranch.did=bio_district.did AND custbranch.stateid=bio_state.stateid )
                  AND   debtorno='".$_GET["str"]."'";
                 $result_ph=DB_query($sql_ph,$db); 
                 $row_ph=DB_fetch_array($result_ph);
                 $sql_ord="SELECT orderno
                  FROM salesorders
                  WHERE debtorno = '".$_GET["str"]."'";
                   $result_ord=DB_query($sql_ord,$db); 
                 $row_ord=DB_fetch_array($result_ord);
              $sqlpnt="select stockmaster.description,orderplant.branchcode,date_format(bio_installation_status.installed_date,'%d %b %Y') as 'ins_date' from bio_installation_status,stockmaster,orderplant where stockmaster.stockid=orderplant.stkcode and bio_installation_status.orderno=orderplant.orderno and orderplant.orderno='".$row_ord[0]."'"; 
            $result_pt=DB_query($sqlpnt,$db); 
                         $row_pt=DB_fetch_array($result_pt);

              echo '<table class="selection" style="width:70%;">'; 
        
          echo '<th colspan="2"><b><font size="+1" color="#000000">CUSTOMER DETAILS</font></b></th>' ; 
          
          echo '<tr><td align="left" id=first style="width:470px">';
           
          echo"<fieldset style='width:440px;height:400px '>"; 
          echo"<legend>Customer Details</legend>";
             echo"<table  width=100%>";
             echo '<tr><td>VPA Number</td><td><input type="text" name="vpa" id="vpa"></td></tr>';
            echo '<tr><td>Name</td><td><input type="text" name="name" value="'.$row_ph['brname'].'"</td></tr>';
               echo '<tr><td>House name</td><td><input type="text" name="house" value="'.$row_ph['braddress1'].'"></td></tr>';
                echo '<tr><td>Street</td><td><input type="text" name="street" value="'.$row_ph['braddress2'].'"></td></tr>';
                 echo '<tr><td>mobile number</td><td><input type="text" name="phone" value="'.$row_ph['faxno'].'" ></td></tr>';
               echo '<tr><td>Phone number</td><td><input type="text" name="mobile" value="'.$row_ph['phoneno'].'" ></td></tr>';
                 echo '<tr><td>District</td><td><input type="text" name="district" value="'.$row_ph['district'].'" readonly></td></tr>';
                  echo '<tr><td>State</td><td><input type="text" name="state" value="'.$row_ph['state'].'" readonly></td></tr>';
                   echo '<tr><td>Date of installation</td><td><input type="text" name="insdate" value="'.$row_pt['ins_date'].'" readonly></td></tr>';
                    echo '<tr><td>Plant</td><td><input type="text" name="plant" value="'.$row_pt['description'].'" readonly></td></tr>';
                    echo "<input type='hidden' name='debtorn' value='".$_GET["str"]."'>";
   /* echo '<tr ><td><a>1.Total amount of waste disposal avoided</a></td></tr>';           */
      
         echo'</table>';
         echo '</fieldset>';
         echo "</td><td>";
       echo"<fieldset style='width:440px;height:400px '>"; 
          echo"<legend>Fuel used before the installation</legend>";
             echo"<table  width=100%>";
             echo '<tr><td>Fire wood</td><td><input type="text" size="10" name="fire" id="fire"> kg</td></tr>';
              echo '<tr><td>LPG</td><td><input type="text" size="10" name="lpg" id="lpg"> kg</td></tr>';
               echo '<tr><td>Grid power</td><td><input type="text" size="10" name="grid" id="grid"> kwh</td></tr>';
                echo '<tr><td>Survey Date</td><td><input type="text" size="10" name="surdate" id="surdate">';
           echo '</td></tr><tr><td>Invoice number</td><td><input type="text" size="10" name="invoice" id="invoice"></td></tr>';
          echo ' <tr><td>Invoice Date</td><td>';
           echo '<input type="text" size="10" name="date" id="date">';
            echo '</td></tr><tr><td>Accepted by</td><td><input type="text" size="10" name="accept" id="accept" value="'.$username.'"></td></tr>';
          echo '<tr><td>Type of waste</td></tr>';
                 $sql_wast="SELECT * FROM `bio_waste_cat`";
              $result_wast=DB_query($sql_wast,$db);
              $j=1;
         
            while($row_wast=DB_fetch_array($result_wast))
            {
                
                echo '</tr><td></td><td><input type="checkbox" name=cat'.$j.' id=cat'.$j.' value="'.$row_wast['catid'].'">'.$row_wast['waste_description'].'</td></tr>'; 
                $j++;
            }
            echo "<input type='hidden' name='totalrow '  value='$j' >";    
            
           /*  echo '</tr><td></td><td><input type="checkbox" name="cat1" id="cat1">Wood, wood products, pulp and paper</td></tr>';   
                echo '<tr><td></td><td><input type="checkbox" name="cat2" id="cat2">Garden, Yard and park wastes</td></tr>'; 
                 echo '<tr><td></td><td><input type="checkbox" name="cat3" id="cat3">Food, food-waste, beverage, sewage sludge</td></tr>';     */
         echo "</table></td></tr>";
         
         echo '<td></td><td><input type="submit"  name="submit1" size="300" value="submit" onclick="if(validation()==1){return false;}"></td><td></td>';
         echo "</table>" ;
} 
 else
   {
                      $sql_ph="SELECT brname,braddress1,braddress2,bio_district.district,faxno,phoneno,state 
                  FROM  custbranch,bio_district,bio_state 
                  WHERE (custbranch.cid=bio_district.cid AND custbranch.stateid=bio_district.stateid AND custbranch.did=bio_district.did AND custbranch.stateid=bio_state.stateid )
                  AND   debtorno='".$_GET["str"]."'";
                 $result_ph=DB_query($sql_ph,$db); 
                 $row_ph=DB_fetch_array($result_ph);
                 $sql_ord="SELECT orderno
                  FROM salesorders
                  WHERE debtorno = '".$_GET["str"]."'";
                   $result_ord=DB_query($sql_ord,$db); 
                 $row_ord=DB_fetch_array($result_ord);
              $sqlpnt="select stockmaster.description,orderplant.branchcode,date_format(bio_installation_status.installed_date,'%d %b %Y') as 'ins_date' from bio_installation_status,stockmaster,orderplant where stockmaster.stockid=orderplant.stkcode and bio_installation_status.orderno=orderplant.orderno and orderplant.orderno='".$row_ord[0]."'"; 
            $result_pt=DB_query($sqlpnt,$db); 
                         $row_pt=DB_fetch_array($result_pt);
         
         $sql_old="select * from bio_past_fuel where debtorno='".$_GET["str"]."'" ;
                  $result_old=DB_query($sql_old,$db); 
                 $row_old=DB_fetch_array($result_old);
                  $firewood=$row_old['firewood'];
                  $lpg= $row_old['lpg'];
                  $grid=$row_old['grid'];
                  $invoiceno=$row_old['invoiceno'];
                  $invdate=$row_old['invoicedate'];
                  $accept=$row_old['acceptedby'];

              echo '<table class="selection" style="width:70%;">'; 
        
          echo '<th colspan="2"><b><font size="+1" color="#000000">CUSTOMER DETAILS</font></b></th>' ; 
          
          echo '<tr><td align="left" id=first style="width:470px">';
           
          echo"<fieldset style='width:440px;height:400px '>"; 
          echo"<legend>Customer Details</legend>";
             echo"<table  width=100%>";
             echo '<tr><td>VPA Number</td><td><input type="text" name="vpa" id="vpa" value="'.$row_old['vpano'].'" readonly></td></tr>';
            echo '<tr><td>Name</td><td><input type="text" name="name" value="'.$row_ph['brname'].'" readonly></td></tr>';
               echo '<tr><td>House name</td><td><input type="text" name="house" value="'.$row_ph['braddress1'].'" readonly></td></tr>';
                echo '<tr><td>Street</td><td><input type="text" name="street" value="'.$row_ph['braddress2'].'" readonly></td></tr>';
                 echo '<tr><td>mobile number</td><td><input type="text" name="phone" value="'.$row_ph['faxno'].'" readonly></td></tr>';
               echo '<tr><td>Phone number</td><td><input type="text" name="mobile" value="'.$row_ph['phoneno'].'" readonly></td></tr>';
                 echo '<tr><td>District</td><td><input type="text" name="district" value="'.$row_ph['district'].'" readonly></td></tr>';
                  echo '<tr><td>State</td><td><input type="text" name="state" value="'.$row_ph['state'].'" readonly></td></tr>';
                   echo '<tr><td>Date of installation</td><td><input type="text" name="insdate" value="'.$row_pt['ins_date'].'" readonly></td></tr>';
                    echo '<tr><td>Plant</td><td><input type="text" name="plant" value="'.$row_pt['description'].'" readonly></td></tr>';
                    echo "<input type='hidden' name='debtorn' value='".$_GET["str"]."'>";
   /* echo '<tr ><td><a>1.Total amount of waste disposal avoided</a></td></tr>'; 
   */
   /* if($row_test[0]==2)
    {
        echo "<tr ><td><a style='cursor:pointer;'  id='$_GET[str]' onclick='selectTask(this.id)'>Survey History</a></td></tr>";  
    }  */   
         //style='cursor:pointer;' id='$debtorno' onclick='selectTask(this.id,$debtorno) 
         echo'</table>';
         $sql="SELECT bio_present_fuel.surveyno, DATE_FORMAT( bio_present_fuel.`surdate` , '%d-%m-%Y' ) AS 'srdate', bio_present_fuel.`vpano` , bio_present_fuel.`firewood` , bio_present_fuel.`lpg` , bio_present_fuel.`grid` , bio_present_fuel.`invoiceno` , DATE_FORMAT( `invoicedate` , '%d-%m-%Y' ) AS 'invoicedate', bio_waste_survey.qty1
FROM bio_present_fuel
INNER JOIN bio_waste_survey ON bio_present_fuel.debtorno = bio_waste_survey.debtorno
AND bio_present_fuel.surveyno = bio_waste_survey.surveyno
WHERE bio_present_fuel.debtorno = 'D3014'
ORDER BY bio_present_fuel.surveyno DESC ";
$res=DB_query($sql,$db);
echo "<table><tr><th>Survey No</th><th>Survey Date</th><th>Firewood</th><th>LPG</th><th>Grid Power</th><th>Invoice No</th><th>Invoice Date</th><th>Feed Qty</th></tr>";
while($mr=DB_fetch_array($res))
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
    echo "<td>".$mr[0]."</td><td>".$mr[1]."</td><td>".$mr[3]."</td><td>".$mr[4]."</td><td>".$mr[5]."</td><td>".$mr[6]."</td><td>".$mr[7]."</td><td>".$mr[8]."</td>";
echo "</tr>";
}
echo"</table>";
         echo '</fieldset>';
         echo "</td><td>";
       echo"<fieldset style='width:490px;height:400px'>"; 
          echo"<legend>Fuel used after the installation of plant</legend>";
          
             echo"<table  width=100%>";
             echo "<th>Type of fuel</th><th>Fuel used before the plant</th><th>Fuel used after the plant</th>";
             if($firewood!="" and $firewood!=0)
             {
                echo '<tr><td>Fire wood</td><td><input type="text" size="10" name="fireold" value="'.$firewood.'" readonly></td><td><input type="text" size="10" name="fire" id="fire"> kg</td></tr>'; 
             }
             if($lpg!="" and $lpg!=0)
             {
                echo '<tr><td>LPG old</td><td><input type="text" size="10" name="lpgold" value="'.$lpg.'" readonly></td><td><input type="text" size="10" name="lpg" id="lpg"> kg</td></tr>';  
             }
             if($grid!="" and $grid!=0)
             {
                echo '<tr><td>Grid power old</td><td><input type="text" size="10" name="gridold" value="'.$grid.'" readonly></td><td><input type="text" size="10" name="grid" id="grid">kwh</td></tr>';  
             }
             if($invoiceno!="" and $invoiceno!=0)
             {
                      echo '<tr><td>Invoice number old</td><td><input type="text" size="10" name="invoiceold" value="'.$invoiceno.'" readonly></td></tr>';
             }
             if($invdate!="" and $invdate!=0)
             {
                 echo '<tr><td>Invoice Date old</td><td><input type="text" size="10" name="dateold" value="'.$invdate.'" readonly></td></tr>'; 
             }
             if($accept!="" and  $accept!=0)
             {
                   echo '<tr><td>Accepted by</td><td><input type="text" size="10"  name="acceptold" value="'.$accept.'" readonly></td></tr>';   
             }
            
            echo '<tr><td>Survey Date</td><td>';    
            echo '<input type="text"  placeholder="DD/MM/YYYY" size="10"  name="surdate" id="surdate">';
         echo'</td></tr>';
           echo '<tr><td>Invoice number</td><td><input type="text"size="10" name="invoice" id="invoice"></td></tr>';
           echo '<tr><td>Invoice Date</td><td><input type="text" placeholder="DD/MM/YYYY" size="10"  name="invdate" id="invdate">';
          echo'</td></tr>';
          
           echo '<tr><td>Accepted by</td><td><input type="text" size="10"  name="accept" id="accept" value="'.$username.'"></td></tr>';
           
          echo '<tr><td>---------------------------------</td></tr>';
          $sql_mw="SELECT `wastecatid` FROM `bio_past_fuel` WHERE `debtorno`= '".$_GET["str"]."'";
                 $result_mw=DB_query($sql_mw,$db); 
                   $row_mw=DB_fetch_array($result_mw);
                   $soni= $row_mw[0];
               $sql_wast="SELECT * FROM `bio_waste_cat` where  `catid` in($soni)";
               
               echo "<input type='hidden' name='soni' value='".$soni."'>";
              $result_wast=DB_query($sql_wast,$db); 
              $i=0;
            while($row_wast=DB_fetch_array($result_wast))
            {
                   echo '<tr><td>'.$row_wast['waste_description'].'</td><td><input type="text" size="10" name="caty'.$i.'"></td></tr>';
               // echo '<option value="'.$row_wast['catid'].'">'.$row_wast['waste_description'].'';
               $i++;
            }
                                 
         echo "</table></td></tr>";
         echo '<td></td><td><input type="submit"  name="submit2" size="300" value="submit" onclick="if(validation()==1){return false;}"></td><td></td>';
         echo "</table>";   
        
     }

      
?>        
