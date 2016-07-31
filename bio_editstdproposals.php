<?php
  $PageSecurity = 80;
  include('includes/session.inc');
  
  $lead_ID=$_GET['lead'];
  $proposal_ID=$_GET['propid'];
  $_SESSION['ProposalID']=$proposal_ID;
  if($lead_ID!="" AND $proposal_ID!=""){
      $flag=1;
      $sql_qtn="SELECT bio_proposal.propid,
                 bio_proposal.propdate,
                 bio_proposal.leadid,
                 bio_proposal.totprice,
                 bio_cust.cust_id AS custid,
                 bio_cust.area1,
                 bio_cust.custmob,
                 bio_cust.cust_id,
                 bio_cust.custname,
                 bio_leads.leaddate
            FROM bio_proposal,bio_cust,bio_leads
            WHERE bio_cust.cust_id=bio_leads.cust_id
            AND bio_proposal.leadid=bio_leads.leadid
            AND bio_proposal.propid=".$proposal_ID;
      $result_qtn=DB_query($sql_qtn,$db);
  $myrow_qtn=DB_fetch_array($result_qtn);
  
  $custid=$myrow_qtn['custid'];
  $name=$myrow_qtn['custname'];
  $mob=$myrow_qtn['custmob'];
  $place=$myrow_qtn['area1'];
  $date=ConvertSQLDate($myrow_qtn['leaddate']);
  
  
  
  $sql1="SELECT bio_feedstocks.feedstocks,
                bio_leadfeedstocks.feedstockid,
                bio_leadfeedstocks.weight
          FROM
                bio_feedstocks,
                bio_leadfeedstocks
          WHERE bio_leadfeedstocks.feedstockid=bio_feedstocks.id
            AND bio_leadfeedstocks.leadid=$lead_ID";
  $result1=DB_query($sql1,$db);
  $myrow1=DB_fetch_array($result1);
  
  $sql_proptmp="DELETE FROM bio_temppropitems WHERE leadid=".$lead_ID;
  $result_proptmp=DB_query($sql_proptmp,$db);
  
  
  $sql_prop="SELECT * FROM bio_proposaldetails
             WHERE propid=".$proposal_ID;
  $result_prop=DB_query($sql_prop,$db);
  while($myrow_prop=DB_fetch_array($result_prop)){
      $sql_sub="SELECT SUM(amount) FROM bio_propsubsidy
                WHERE propid=$proposal_ID
                AND leadid=$lead_ID
                AND stockid='".$myrow_prop['stockid']."'";
      $result_sub=DB_query($sql_sub,$db);
      $myrow_sub=DB_fetch_array($result_sub);
      if($myrow_sub[0]>0){
          
      $subsidy=$myrow_sub[0];
      $netprice=$myrow_prop['price']-$subsidy;
      }else{
          $subsidy=0;
          $netprice=$myrow_prop['price'];
          
      }
      
      
      
      $sql3="INSERT INTO bio_temppropitems (stockid,
                                            description,
                                            qty,
                                            price,
                                            tprice,
                                            leadid,
                                            subsidy,
                                            netprice) 
                                 VALUES ('".$myrow_prop['stockid']."',
                                         '".$myrow_prop['description']."',
                                         '".$myrow_prop['qty']."',
                                         '".$myrow_prop['price']."',
                                         '".$myrow_prop['tprice']."',
                                          ".$lead_ID.",
                                          ".$subsidy.",
                                          ".$netprice.")";
      $result3=DB_query($sql3,$db);
  }
  }
  
  
  
  echo"<table>";
echo"<tr><td style='width:50%'>";
echo"<fieldset style='height:170px;width:376px'><legend>Customer</legend>";
echo"<table >";
echo"<tr><td style='width:50%'>Lead id :</td><td><input type='text' name='leadid' value='$lead_ID' id='leadid'></td></tr>";
echo"<tr><td style='width:50%'>Cust id :</td><td><input type='text' name='custid' value='$custid' id='custid'></td></tr>";
echo"<tr><td>Name :</td><td><input type='text' name='custnam' value='$name' id='custnam'></td></tr>";
 echo"<tr><td>Mobile :</td><td><input type='text' name='mob' value='$mob' id='mob'></td></tr>";
echo"<tr><td>Place :</td><td><input type='text' name='Place' value='$place' id='Place'></td></tr>";
echo"<tr><td>Date :</td><td><input type='text' name='Date' value='$date' id='Date'></td></tr>";
echo"</table>";
echo"</fieldset>";
echo"</td>";
echo"<td style='width:50%;height:140px' valign=top>";
echo"<fieldset  style='height:170px;width:376px'><legend>Feadstock</legend>";
echo"<table id='fead' style='width:100%'>";

if ($lead_ID!="")
{
echo"<tr style='background:#969696;color:white'><td>Feadstock</td><td>Weight in kg</td></tr>";

          while($myrow1=DB_fetch_array($result1))
          {
            echo"<tr id='$myrow1[1]' onclick='plantselect(this.id)' style='background:#C0C0FF;cursor:pointer'><td>$myrow1[0]</td><td>$myrow1[2]</td></tr>";
                 }
              // if($flag==1){  echo"</tr><td><input type='button' id='$leadid' name='showselpropitems' value='Select Items' onclick='selectpropitems(this.id)'></td></tr>";  }
              if($flag==1){  echo"</tr><tr><td><input type='button' id='$lead_ID' name='showselpropitems' value='Select Items' onclick='selectcategory(this.id,1);'></td></tr>";  }
}
echo "<div id=\"refr\" style=\"display:none\">";
echo "<tr><td>&nbsp;</td></tr>";
echo "<tr><td><input type=\"button\" value=\"Refresh this page\" onClick='window.location=\"bio_proposal.php\";'></td></tr>";
echo "</div>";

echo "</table></fieldset>";
echo "</td></tr>";
echo "</table>";

$sql4="SELECT stockid,description,qty,price,tprice,subsidy,netprice FROM bio_temppropitems where leadid=".$lead_ID;
$result4=DB_query($sql4,$db);
//$count=DB_fetch_row($result4);
echo "<table width=700px>";
echo "<tr><td colspan='2'>";
echo "<div id='sellist' style='background: #D6DEF7;'>";
echo "<input type=\"hidden\" name=\"lead\" id=\"lead\" value='".$lead_ID."'>";
echo "<table style='width:800px' border='1'>";
echo "<tr><th width=50>Stock Id</th><th width=200>Item Description</th><th>qty</th><th>Price</th><th>Total Price</th><th></th></tr><tbody>";
$k=0;

while ($myrow4=DB_fetch_array($result4))   {
  $tprice=$myrow4['qty']*$myrow4['price'];
$k++;
$stock[$k]=$myrow4['stockid'];
$item=$myrow4['stockid'];
//$item=urlencode($myrow4['stockid']);
   echo "<tr><td>".$stock[$k]."</td><td>".$myrow4['description']."</td>
       <td><input size=3 style=\"text-align: right\" type=\"text\" name=\"qty".$k."\" id=\"qty".$k."\" value=".$myrow4['qty']." onchange=updatetotalitemprice(".$k.",".$lead_ID.",\"$stock[$k]\")></td>
       <td class=number><input size=9 style=\"text-align: right\" type=\"text\" name=\"price".$k."\" id=\"price".$k."\" value=".$myrow4['price']." onchange=updatetotalitemprice(".$k.",".$lead_ID.",\"$stock[$k]\")></td>
       <td class=number><input size=9 style=\"text-align: right\" type=\"text\" name=\"tprice".$k."\" id=\"tprice".$k."\" value=".$tprice."></td>
       <td class=number><input size=9 style=\"text-align: right\" type=\"text\" name=\"subsidy".$k."\" id=\"subsidy".$k."\" value=".$myrow4['subsidy']." readonly></td>
       <td class=number><input size=9 style=\"text-align: right\" type=\"text\" name=\"netprice".$k."\" id=\"netprice".$k."\" value=".$myrow4['netprice']." readonly></td>
       <td align=center><a  style='cursor:pointer;'  id='$lead_ID' onclick='addSubsidy($lead_ID,\"$item\",1,2,1)'>" . _('Manage Subsidy') . "</a></td>

       </tr>";
}
echo "<tr><td colspan=2>
          <input type='button' id=\"".$lead_ID."\" value='Add Item'  onclick='selectcategory(this.id,2);'>
       </td>";
echo "<td colspan=3>
          <input type='button' id=\"".$lead_ID."\" value='Save this proposal' onclick='modifyproposal(this.id,$proposal_ID);' >
       </td></tr>";

 echo "</tbody></table>";
 
 echo "</div>";
echo "</td></tr>";
echo"<table>";
  
  
  
  
?>
