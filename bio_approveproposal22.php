<?php
$PageSecurity = 80;
include('includes/session.inc');
$title = _('Proposal Approve');
include('includes/header.inc'); 
   
   echo '<center><font style="color: #333;
    background:#fff;
    font-weight:bold;
    letter-spacing:0.10em;
    font-size:16px;
    font-family:Georgia;
    text-shadow: 1px 1px 1px #666;">Proposal Approval</font></center>'; 
echo"<table border=0>";
if(isset($_POST['updateprc']))
{$st="proposal approved";
    $sql="SELECT bio_status.statusid FROM bio_status WHERE bio_status.biostatus='$st'";
    $result=DB_query($sql,$db);
 $myrow=DB_fetch_array($result);
 $status=$myrow[0];     
$nme=$_POST['prc'];   
 for($i=0;$i<$nme;$i++){
$price = $_POST['price'.$i];  $lead=$_POST['hleads']; 
$productid = $_POST['prid'.$i];
$sql="UPDATE bio_proposals SET price = '$price' WHERE bio_proposals.productid='$productid'
AND bio_proposals.leadid=$lead";                              
$result=DB_query($sql,$db);     
     
     
 }  
    
$sql="UPDATE bio_leads SET leadstatus = $status WHERE bio_leads.leadid =$lead";                              
$result=DB_query($sql,$db);       
    
  
    
}
echo"<tr>";
if($_GET['lead']!=''){     $flag=1;
$leadid=$_GET['lead'];
  $sql="SELECT 
bio_leads.leadid,
date_format(bio_leads.leaddate,'%d/%m/%Y'),
bio_cust.area1,
bio_cust.custmob,
bio_cust.cust_id, 
bio_cust.custname,
bio_outputtypes.outputtype,
bio_enquirytypes.enquirytype,bio_leadteams.teamname      
FROM bio_leads,
bio_cust,bio_outputtypes,
bio_enquirytypes,
bio_leadteams 
WHERE 
bio_leads.cust_id=bio_cust.cust_id
AND bio_leads.outputtypeid=bio_outputtypes.outputtypeid 
AND bio_leads.enqtypeid=bio_enquirytypes.enqtypeid
AND bio_leads.teamid=bio_leadteams.teamid
AND bio_leads.leadid=$leadid";
      $result=DB_query($sql,$db);
     // $count=DB_fetch_row($result); 
     //print_r($count);
                                     
    $no=0; 

          $k=0; 
          while($myrow=DB_fetch_array($result))
          {    //echo $myrow[0]; 
          $custid=$myrow[4];    
           $name=$myrow[5];
            $mob=$myrow[3];
          $place=$myrow[2];
            $date=$myrow[1];     
                 }  }
echo "<form action=" . $_SERVER['PHP_SELF'] . "?" . SID ." method=post>"; 
echo"<input type='hidden' name='hleads' id='hleads' value='$leadid'>";
echo"<td style='width:50%'>"; 
echo"<fieldset style='height:100%'><legend>Customer Details</legend>"; 
echo"<table><tr><td>";
echo"Customer Id";
echo"</td>";
echo"<td>";
echo"<input type='text' id='custid' name='custid' value='$custid'>";
echo"</td></tr>";
                 
echo"<tr><td>";
echo"Customer Name";
echo"</td>";
echo"<td>";
echo"<input type='text' id='cust' name='cust' value='$name'>";
echo"</td></tr>";

echo"<tr><td>";
echo"Customer Mobile";
echo"</td>";
echo"<td>";
echo"<input type='text' id='mob' name='mob' value='$mob'>";
echo"</td></tr>";

echo"<tr><td>";
echo"Recidential Area";
echo"</td>";
echo"<td>";
echo"<input type='text' id='res' name='res' value='$place'>";
echo"</td></tr>";

echo"<tr><td>";
echo"Date";
echo"</td>";
echo"<td>";
echo"<input type='text' id='date' name='date' value='$date'>";
echo"</td></tr></table></fieldset></td>";

echo"<td valign=top>";
echo"<fieldset style='height:162px'><legend>Proposed Products</legend>";
echo "<table style='width:100%'>";
echo"<tr><th>Pid</th><th>Name</th><th>Weight</th><th>Price</th></tr>";
if($leadid!=""){
 $sql="SELECT 
bio_proposals.productid,
bio_proposals.price,
stockmaster.description,
stockmaster.kgs
FROM 
bio_proposals,stockmaster 
WHERE 
bio_proposals.productid=stockmaster.stockid
AND bio_proposals.leadid=$leadid";
$result=DB_query($sql,$db);
 $i=0;
          while($myrow=DB_fetch_array($result))
          {    //echo $myrow[0]; 
          $productid=$myrow[0];    
          $price=$myrow[1];
          $description=$myrow[2];
           $weight=$myrow[3];
//            $date=$myrow[1];     
                 
          echo'<tr><td>'.$productid.'</td><td>'.$description.'</td><td>'.$weight.'
          </td><td><input id="price'.$i.'" style="width:50px" name="price'.$i.'" value="'.$price.'"></td></tr>';
          echo '<input  type="hidden" id="prid'.$i.'" name="prid'.$i.'" value="'.$productid.'">';
          
          $i++;
          }
}
echo"<input type='hidden' name='prc' value='$i'>";
if($i!=0){
echo"<tr><td colspan=2><input type='submit' name='updateprc' value='Approve'></td></tr>";}
echo"</table></fieldset>";
echo"</form>";
echo"</td>";

echo"</tr>";
echo"<tr>";
echo"<td colspan=2>";
echo"<fieldset><legend>Proposals for Approval</legend>";

echo"<div style='height:100px;overflow:scroll'>";

echo"<table style='width:100%'> ";
echo"<tr><th>Slno</th><th>Name</th><th>Date</th><th>Output</th><th>Cust type</th><th>Team</th></tr>";
 $sql="SELECT 
bio_leads.leadid,
bio_leads.leaddate,     
bio_leads.enqtypeid,
bio_leads.outputtypeid,
bio_leads.teamid,
bio_cust.custname,
bio_outputtypes.outputtype,
bio_enquirytypes.enquirytype,bio_leadteams.teamname      
FROM bio_leads,
bio_cust,bio_outputtypes,
bio_enquirytypes,
bio_leadteams,bio_leadfeedstocks  
WHERE 
bio_leads.cust_id=bio_cust.cust_id
AND bio_leads.outputtypeid=bio_outputtypes.outputtypeid 
AND bio_leads.enqtypeid=bio_enquirytypes.enqtypeid
AND bio_leads.leadstatus=1
AND bio_leads.leadid=bio_leadfeedstocks.leadid
AND bio_leadfeedstocks.status=1
AND bio_leads.teamid=bio_leadteams.teamid";
      $result=DB_query($sql,$db);


    echo '<tbody>';
    echo '<tr>';                                       
    $no=0; 

          $k=0; 
          while($myrow=DB_fetch_array($result))
          {    //echo $myrow[0];    
          $no++;
               if ($k==1)
                {
                    echo '<tr class="EvenTableRows">';
                    $k=0;
                    
                }
                 else 
                 {
                    echo '<tr class="OddTableRows">';
                    $k=1;     
                 }
                    $leadid=$myrow[0];
                 printf("<td cellpading=2>%s</td>
        <td>%s</td>
        <td>%s</td>
        <td>%s</td>
        <td>%s</td>
         <td>%s</td>

         
        <td><a  style='cursor:pointer;'  id='$leadid' onclick='passid(this.id)'>" . _('Select') . "</a></td>  

        </tr>",
        
        $no,
        $myrow['custname'],
        ConvertSQLDate($myrow['leaddate']),
        $myrow[6],
        $myrow[7],
        $myrow[8],
        $myrow[4],
        $myrow[0]);
                 
                 
                 
          }

echo"</td>";

echo"</tr>";
echo"</fieldset>";
echo"</table>";
echo"</div>";
echo"</td>";
echo"</tr>";
echo"</table>";
?>
<script>
function passid(str){
location.href="?lead=" +str;  
} 
</script>