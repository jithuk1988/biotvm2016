<?php
   $PageSecurity = 80;
include('includes/session.inc');
$title = _('Proposal Template');  
include('includes/header.inc');
include('includes/sidemenu.php');
$sql="SELECT bio_leads.leadid,bio_cust.cust_id,debtorsmaster.debtorno,debtorsmaster.name, 
CONCAT( debtorsmaster.address1, '-', debtorsmaster.address2 ) AS 'address', 
CONCAT( bio_cust.custphone, '<br>', bio_cust.custmob ) AS 'mobile'
FROM `debtorsmaster`
INNER JOIN bio_cust ON debtorsmaster.debtorno = bio_cust.`debtorno`
INNER join bio_leads ON bio_leads.cust_id=bio_cust.cust_id
AND  bio_leads.enqtypeid=8
AND debtorsmaster.debtorno LIKE 'DL%'";
    $result=DB_query($sql,$db); 
    $slno=0;   
    $k=1;
    echo "<fieldset>";
    echo "<legend>DEALERS LIST</legend>";
    echo "<table><tr>";
    echo "<th>SLNO</th><th>DEALER NAME</th><th>ADDRESS</th><th>CONTACT NO</th><th>ADD CUSTOMER <BR> LEADS</th><th>View</th><th>ADD PROPOSAL</th>";
    
  while($myrow=DB_fetch_array($result))  
  {
          if ($k==1){
        echo '<tr class="EvenTableRows">';
        $k=0;
    } else {
        echo '<tr class="OddTableRows">';
        $k=1;
    }
     $leadid= $myrow['leadid'];
    $slno++;
     echo "<td>".$slno."</td><td>".$myrow['name']."</td><td>".$myrow['address']."</td><td>".$myrow['mobile']."</td> 
      <td><a  style='cursor:pointer;'  id='$leadid' onclick='passid2(this.id)'>" . _('Add beneficiary') . "</a></td>
      <td width='90px'><a  style='cursor:pointer;'  id='$leadid' onclick='passid3(this.id)'>" . _('View') . "</a></td>  
     <td><a  style='cursor:pointer;'  id='$leadid' onclick='passid4($leadid)'>" . _('Add Proposal') . "</a></td>
    </tr>" ;
    
  }
       
?>
<script type="text/javascript">
 function passid2(str1)
    {
             myRef = window.open("dealercustomernew.php?leadid=" + str1,"droplead","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=900,height=700");
    } 
      function passid4(str1,str2)
    {
             myRef = window.open("dealerproposal.php?leadid=" + str1 );       
    }  
     function passid3(str1)
    {                    //alert(str1); alert(str2);
             myRef = window.open("bio_viewdlben.php?leadid=" + str1 ,"droplead","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=1200,height=700");    
    }  
</script>