<?php
$PageSecurity = 80;
include('includes/session.inc');
$title = _('Proposal');
$pagetype=1;
include('includes/header.inc');
include('includes/sidemenu.php');
include('includes/bio_GetPrice.inc');

// if ($_GET['updated']==1)       {
//   header("bio_proposal.php");
// }


/*function removetempitm($lead,$stock,$db){
    
     $sql_tempdel="DELETE FROM bio_temppropitems where leadid=".$lead." AND stockid=".$stock;
// echo $sql4;
  echo   $sql_tempdel;
 $result_tempdel=DB_query($sql_tempdel,$db);
 echo $sql_tempsubdel="DELETE FROM bio_temppropsubsidy where leadid=".$lead." AND stockid=".$stock;
// echo $sql4;
 $result_tempsubdel=DB_query($sql_tempsubdel,$db);
}      */
function delleadfromtempprod($lead,$db) {
  $sql4="DELETE FROM bio_temppropitems where leadid=".$lead;
// echo $sql4;
 $result4=DB_query($sql4,$db);
 $sql_4="DELETE FROM bio_temppropsubsidy where leadid=".$lead;
// echo $sql4;
 $result_4=DB_query($sql_4,$db);
  $sql_44="DELETE FROM bio_propsubsidy where leadid=".$lead;
// echo $sql4;
 $result_44=DB_query($sql_44,$db);
 
}

function checkduplicateentries($stockid,$lead,$db) {
  $sql4="select COUNT(*) FROM bio_temppropitems where leadid=".$lead ." AND stockid='".$stockid."'";
  $result4=DB_query($sql4,$db);
  $myrow4=DB_fetch_array($result4);
  if ($myrow4[0]>0) {
    return 1;
  }
  else {return 0;};
}

function findstockitemdetails($stockid,$db) {
  global $stockid;
  global $itemdesc;
  global $price;
    $sqlw="SELECT stockmaster.description as description,
                 stockcatproperties.label as label,
                 stockitemproperties.value as value FROM stockmaster
                 inner join  stockcategory on stockmaster.categoryid=stockcategory.categoryid
                 left outer join stockitemproperties on stockmaster.stockid=stockitemproperties.stockid
                 left outer join stockcatproperties on stockcatproperties.stkcatpropid=stockitemproperties.stkcatpropid
                 WHERE  stockmaster.stockid='$stockid'";
//   echo $sqlw;
   $ErrMsg =  _('There is a problem in retrieving item description of') . ' ' . $stockid  .  _('the error message returned by the SQL server was');
   $resultw=DB_query($sqlw,$db,$ErrMsg);
   $itemdesc='';
$a=0;
while ($myroww=DB_fetch_array($resultw))   {
  if ($a==0) {$itemdesc.=$myroww['description'].':</br> '; $a=1;}
 $itemdesc.=$myroww['label'].': '.$myroww['value'].'</br> ';
}

}

function edititem($stockid,$lead_ID,$proposal_ID,$db) { 
       // echo  $stockid;    echo  $proposal_ID;
        if($stockid==NULL) {
            delleadfromtempprod($lead_ID,$db);  
        }
    
    
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
  
  /*$sql_proptmp="DELETE FROM bio_temppropitems WHERE leadid=".$lead_ID;
  $result_proptmp=DB_query($sql_proptmp,$db);   */ 
                                                
  
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
      
      
   if($stockid==NULL) {    
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
      $result3=DB_query($sql3,$db);  }
  }                     
  
  
          echo '<center><font style="color: #333;
    background:#fff;
    font-weight:bold;
    letter-spacing:0.10em;
    font-size:16px;
    font-family:Georgia;
    text-shadow: 1px 1px 1px #666;">EDIT PROPOSALS</font></center>';
     echo"<br /><br /><br />";
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
              if($flag==1){  echo"</tr><tr><td><input type='button' id='$lead_ID' name='showselpropitems' value='Select Items' onclick='selectcategory1(this.id,1,$proposal_ID);'></td></tr>";  }
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
       <td align=center><a  style='cursor:pointer;'  id='$lead_ID' onclick='addSubsidy1($lead_ID,\"$item\",1,2,1,$proposal_ID)'>" . _('Manage Subsidy') . "</a></td>
        <td align=center><a  style='cursor:pointer;'  id='$lead' onclick='removeitm($lead_ID,\"$item\",$proposal_ID,1)'>" . _('Remove') . "</a></td>
       </tr>";
}
echo "<tr><td colspan=2>
          <input type='button' id=\"".$lead_ID."\" value='Add Item'  onclick='selectcategory1(this.id,2,$proposal_ID);'>
       </td>";
echo "<td colspan=3>
          <input type='button' id=\"".$lead_ID."\" value='Save this proposal' onclick='modifyproposal(this.id,$proposal_ID);' >
       </td></tr>";

 echo "</tbody></table>";
 
 echo "</div>";
echo "</td></tr>";
echo"<table>";
    
}






   function addpropitem1($stockid,$lead,$flag2,$db){
       global $stockid;
global $itemdesc;
global $price;
global $lead;
$_SESSION['leid']=$lead;
//echo $_SESSION['leid'];
$price=GetPrice($stockid,$db);
findstockitemdetails($stockid,$db);
$subsidy=0;
$netprice=0;
$sql_des="SELECT longdescription FROM stockmaster
          WHERE stockid='".$stockid."'";
$result_des=DB_query($sql_des,$db);
$myrow_des=DB_fetch_array($result_des);
$longdes=$myrow_des['longdescription'];

$sql_sub="SELECT * FROM bio_subsidymaster
            WHERE subsidy_plant_id='$stockid'";
$result_sub=DB_query($sql_sub,$db);
$subsidy_count=DB_num_rows($result_sub);

if($subsidy_count>0){
    while($myrow_sub=DB_fetch_array($result_sub)){
    $scheme=$myrow_sub['subsidy_scheme_id'];
    $amount=$myrow_sub['subsidy_amount'];
    $sql_sub1="INSERT INTO bio_temppropsubsidy(leadid,
                                              stockid,
                                              scheme,
                                              amount) 
                                       VALUES(".$lead.",
                                              '".$stockid."',
                                              '".$scheme."',
                                              '".$amount."')";
    $result_sub1=DB_query($sql_sub1,$db);
}

$sql_sub_amount="SELECT SUM(subsidy_amount) FROM bio_subsidymaster
            WHERE subsidy_plant_id='$stockid'";
$result_sub_amount=DB_query($sql_sub_amount,$db);
$myrow_sub_amount=DB_fetch_array($result_sub_amount);
$subsidy=$myrow_sub_amount[0];
$netprice=$price-$subsidy;
}
else{
  $subsidy="";
  $netprice=$price;  
}

 $sql3="INSERT INTO bio_temppropitems (stockid,description,qty,price,tprice,leadid,subsidy,netprice) VALUES ('".$stockid."','".$longdes."',1,".number_format($price,2,'.','').",".number_format($price,2,'.','').",".$lead.",'".$subsidy."',".$netprice.")";
$result3=DB_query($sql3,$db);
   }







function addpropitem($stockid,$lead,$flag2,$db) {
global $stockid;
global $itemdesc;
global $price;
global $lead;
$_SESSION['leid']=$lead;
//echo $_SESSION['leid'];
$price=GetPrice($stockid,$db);
findstockitemdetails($stockid,$db);
$subsidy=0;
$netprice=0;
$sql_des="SELECT longdescription FROM stockmaster
          WHERE stockid='".$stockid."'";
$result_des=DB_query($sql_des,$db);
$myrow_des=DB_fetch_array($result_des);
$longdes=$myrow_des['longdescription'];

$sql_sub="SELECT * FROM bio_subsidymaster
            WHERE subsidy_plant_id='$stockid'";
$result_sub=DB_query($sql_sub,$db);
$subsidy_count=DB_num_rows($result_sub);

if($subsidy_count>0){
    while($myrow_sub=DB_fetch_array($result_sub)){
    $scheme=$myrow_sub['subsidy_scheme_id'];
    $amount=$myrow_sub['subsidy_amount'];
    $sql_sub1="INSERT INTO bio_temppropsubsidy(leadid,
                                              stockid,
                                              scheme,
                                              amount) 
                                       VALUES(".$lead.",
                                              '".$stockid."',
                                              '".$scheme."',
                                              '".$amount."')";
    $result_sub1=DB_query($sql_sub1,$db);
}

$sql_sub_amount="SELECT SUM(subsidy_amount) FROM bio_subsidymaster
            WHERE subsidy_plant_id='$stockid'";
$result_sub_amount=DB_query($sql_sub_amount,$db);
$myrow_sub_amount=DB_fetch_array($result_sub_amount);
$subsidy=$myrow_sub_amount[0];
$netprice=$price-$subsidy;
}
else{
  $subsidy="";
  $netprice=$price;  
}

echo "<form name=\"prop\" >";
$sql3="INSERT INTO bio_temppropitems (stockid,description,qty,price,tprice,leadid,subsidy,netprice) VALUES ('".$stockid."','".$longdes."',1,".number_format($price,2,'.','').",".number_format($price,2,'.','').",".$lead.",'".$subsidy."',".$netprice.")";
$result3=DB_query($sql3,$db);
$sql4="SELECT stockid,description,qty,price,tprice,subsidy,netprice FROM bio_temppropitems where leadid=".$lead;
$result4=DB_query($sql4,$db);
//$count=DB_fetch_row($result4);
echo "<input type=\"hidden\" name=\"stock".$k."\" id=\"stock".$k."\" value='".$stockid."'>";
echo "<input type=\"hidden\" name=\"lead\" id=\"lead\" value='".$lead."'>";
echo "<table style='width:800px' border='1'>";
echo "<tr><th width=50>Stock Id</th><th width=200>Item Description</th><th>qty</th><th>Unit Price</th><th>Gross Price</th><th>Subsidy</th><th>Net Price</th></tr><tbody>";
$k=0;

while ($myrow4=DB_fetch_array($result4))   {
  $tprice=$myrow4['qty']*$myrow4['price'];
$k++;
$stock[$k]=$myrow4['stockid'];
$item=$myrow4['stockid'];
//$item=urlencode($myrow4['stockid']);
   echo "<tr><td>".$stock[$k]."</td><td>".$myrow4['description']."</td>
       <td><input size=3 style=\"text-align: right\" type=\"text\" name=\"qty".$k."\" id=\"qty".$k."\" value=".$myrow4['qty']." onchange=updatetotalitemprice(".$k.",".$lead.",'".$stock[$k]."')></td>
       <td class=number><input size=9 style=\"text-align: right\" type=\"text\" name=\"price".$k."\" id=\"price".$k."\" value=".$myrow4['price']." onchange=updatetotalitemprice(".$k.",".$lead.",'".$stockid."')></td>
       <td class=number><input size=9 style=\"text-align: right\" type=\"text\" name=\"tprice".$k."\" id=\"tprice".$k."\" value=".$tprice."></td>
       <td class=number><input size=9 style=\"text-align: right\" type=\"text\" name=\"subsidy".$k."\" id=\"subsidy".$k."\" value=".$myrow4['subsidy']." readonly></td>
       <td class=number><input size=9 style=\"text-align: right\" type=\"text\" name=\"netprice".$k."\" id=\"netprice".$k."\" value=".$myrow4['netprice']." readonly></td>
       <td align=center><a  style='cursor:pointer;'  id='$lead' onclick='addSubsidy($lead,\"$item\",$flag2,0,1)'>" . _('Manage Subsidy') . "</a></td>

       </tr>";
}
echo "<tr><td colspan=2>
          <input type='button' id=\"".$lead."\" value='Add Item'  onclick='selectcategory(this.id,0,$flag2);'>
       </td>";
echo "<td colspan=3>
          <input type='button' id=\"".$lead."\" value='Save this proposal' onclick='saveproposal(this.id,$flag2);' >
       </td></tr>";

 echo "</tbody></table>";
 echo "</form>";
}


function dispropsubsidy($db) {
    echo "<table style='width:750px'>";
echo "<tr><th width=50>Sl No.</th><th>Stock Id</th><th width=200>Item Description</th><th>Scheme</th><th>Amount</th></tr><tbody>";
$k=0;
$slno=1;
$sql_1="SELECT bio_temppropsubsidy.stockid,
               bio_temppropsubsidy.amount,
               bio_schemes.scheme,
               stockmaster.longdescription
         FROM bio_temppropsubsidy,bio_schemes,stockmaster
         WHERE bio_temppropsubsidy.scheme=bio_schemes.schemeid
         AND stockmaster.stockid=bio_temppropsubsidy.stockid";
$result_1=DB_query($sql_1,$db);
while($myrow_1=DB_fetch_array($result_1)){
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
echo"<tr><td align=center>".$slno."</td><td align=center>".$myrow_1['stockid']."</td><td>".$myrow_1['longdescription']."</td><td align=center>".$myrow_1['scheme']."</td><td align=center>".$myrow_1['amount']."</td>";
$slno++;
}

echo "</tbody></table>";
    
}




 








function disppropitem($lead,$flag2,$db) {
//global $stockid;
//global $itemdesc;
//global $price;
global $lead;
$_SESSION['leid']=$lead;
//echo $_SESSION['leid'];
//$price=GetPrice($stockid,$db);
//findstockitemdetails($stockid,$db);

echo "<form name=\"prop\" >";
// $sql3="INSERT INTO bio_temppropitems (stockid,description,qty,price,tprice,leadid) VALUES ('".$stockid."','".$itemdesc."',1,".number_format($price,2,'.','').",".number_format($price,2,'.','').",".$lead.")";
// $result3=DB_query($sql3,$db);
$sql4="SELECT stockid,description,qty,price,tprice,subsidy,netprice FROM bio_temppropitems where leadid=".$lead;
$result4=DB_query($sql4,$db);
//$count=DB_fetch_row($result4);
//echo "<input type=\"hidden\" name=\"stock".$k."\" id=\"stock".$k."\" value='".$stockid."'>";
echo "<input type=\"hidden\" name=\"lead\" id=\"lead\" value='".$lead."'>";
echo "<table style='width:800px' border='1'>";
echo "<tr><th width=50>Stock Id</th><th width=200>Item Description</th><th>qty</th><th>Unit Price</th><th>Gross Price</th><th>Subsidy</th><th>Net Price</th></tr><tbody>";
$k=0;

while ($myrow4=DB_fetch_array($result4))   {
  $tprice=$myrow4['qty']*$myrow4['price'];
$k++;
$stock[$k]=$myrow4['stockid'];
$item=$stock[$k];
   echo "<tr><td>".$stock[$k]."</td><td>".$myrow4['description']."</td>
       <td><input size=3 style=\"text-align: right\" type=\"text\" name=\"qty".$k."\" id=\"qty".$k."\" value=".$myrow4['qty']." onchange=updatetotalitemprice(".$k.",".$lead.",'".$stock[$k]."')></td>
       <td class=number><input size=9 style=\"text-align: right\" type=\"text\" name=\"price".$k."\" id=\"price".$k."\" value=".$myrow4['price']." onchange=updatetotalitemprice(".$k.",".$lead.",'".$stockid."')></td>
       <td class=number><input size=9 style=\"text-align: right\" type=\"text\" name=\"tprice".$k."\" id=\"tprice".$k."\" value=".$tprice."></td>
       <td class=number><input size=9 style=\"text-align: right\" type=\"text\" name=\"subsidy".$k."\" id=\"subsidy".$k."\" value=".$myrow4['subsidy']." readonly></td>
       <td class=number><input size=9 style=\"text-align: right\" type=\"text\" name=\"netprice".$k."\" id=\"netprice".$k."\" value=".$myrow4['netprice']." readonly></td>
       <td align=center><a  style='cursor:pointer;'  id='$lead' onclick='addSubsidy($lead,\"$item\",$flag2,0,1)'>" . _('Manage Subsidy') . "</a></td>
       

       </tr>";
}
echo "<tr><td colspan=2>
          <input type='button' id=\"".$lead."\" value='Add Item'  onclick='selectcategory(this.id,0,$flag2);'>
       </td>";
echo "<td colspan=3>
          <input type='button' id=\"".$lead."\" value='Save this proposal' onclick='saveproposal(this.id,$flag2);' >
          
       </td></tr>";

 echo "</tbody></table>";
 echo "</form>";
}

if($_SESSION['lead']!="") {
  $_GET['lead']=$_SESSION['lead'];
  $lead=$_SESSION['lead'];
  unset($_SESSION['lead']);
}
$leadid= $_GET['lead'];

if($_GET['taskid']!=""){
$flag2= $_GET['taskid'];
}else{
$flag2=6;
}
// if(isset($_POST['choose']))
//  {
//   $plantid='';
//   $scheme=$_POST['schm'];
//                             foreach($scheme as $id)  {
//                              $plantid=$plantid.$id.",";
// 
//                                 }
// 
//    $plantid=explode(",", $plantid);
//                             //$plantid[0];
//      $count=count($plantid);   $count1=$count-2;
//    $sql="SELECT stockcatproperties.stkcatpropid from stockcatproperties WHERE stockcatproperties.label='Price'";
//    $result=DB_query($sql,$db);
//    $stkcatpropid=$myrow=DB_fetch_array($result);
//    for($i=0; $i<=$count1; $i++){       $i;
//   $sql="SELECT stockitemproperties.stockid,
//  stockitemproperties.value
//  FROM stockitemproperties
//  WHERE stockitemproperties.stockid='".$plantid[$i]."'";
//  // AND stockitemproperties.stkcatpropid=$stkcatpropid[0]
// //  ORDER BY stkcatpropid
// $result=DB_query($sql,$db);
// $myrow=DB_fetch_array($result);
// $prodid=$myrow[0];
// $propvalue=$myrow[1];
//$sql="INSERT INTO bio_proposals VALUES($lead,'0','".$propvalue."','".$prodid."')";
//$result=DB_query($sql,$db);
//echo $sql;

 //    unset($_GET['lead']);     unset($leadid);
//   }

// $sql="UPDATE bio_leads SET leadstatus = '1' WHERE bio_leads.leadid =$lead";                              
// $result=DB_query($sql,$db); 
// }

//$leadid= $_GET['lead'];
if($_GET['lead']!=''){     $flag=1;

       $sql="SELECT bio_leads.leadid,
                    bio_leads.investmentsize, 
                    bio_leads.enqtypeid,
                    bio_cust.custname,    
                    bio_cust.contactperson,
                    bio_cust.custphone,
                    bio_cust.custmob,
                    bio_cust.custmail,
                    bio_cust.area1,
                    bio_district.district,
                    bio_leadteams.teamname,
                    bio_leadsources.sourcename,
                    bio_emp.empname,
                    bio_cust.cust_id,
                    bio_leads.enqtypeid,
                    bio_leads.remarks,
                    SUM( bio_advance.amount) AS amount 
              FROM  bio_leads,bio_cust,bio_leadteams,bio_leadsources,bio_district,bio_emp,www_users,bio_advance
              WHERE bio_leads.cust_id=bio_cust.cust_id
                AND bio_leadteams.teamid=bio_leads.teamid
                AND bio_district.did=bio_cust.district
                AND bio_district.stateid=bio_cust.state
                AND bio_district.cid=bio_cust.nationality
                AND bio_leadsources.id=bio_leads.sourceid
                AND bio_leads.leadid=bio_advance.leadid
                AND bio_leads.created_by=www_users.userid
                AND www_users.empid=bio_emp.empid
                AND bio_leads.leadid=".$leadid;
                $result=DB_query($sql,$db);
                                                                                     // $count=DB_fetch_row($result);
                                                                                     //print_r($count);

          $no=0;
          $k=0;
          while($myrow=DB_fetch_array($result))
          {    
             $enqtype=$myrow['enqtypeid'];
             
             if($enqtype==2){
             $cperson=$myrow['contactperson']; 
             $cname=$myrow['custname'];
             }else{
             $cname=$myrow['custname']; 
             }
                         
             if($myrow['custmob']!=''){
             $cph=$myrow['custmob']; 
             }else{
             $cph=$myrow['custphone']; 
             }
             
            $email=$myrow['custmail'];
            $place=$myrow['area1'];
            $district=$myrow['district'];
            $team=$myrow['teamname'];
            $sourcename=$myrow['sourcename']; 
            $amount=$myrow['amount']; 
            $investmentsize=$myrow['investmentsize'];
            $createdby=$myrow['empname']; 
          }

//  echo  $leadid;
$sql1="SELECT
bio_feedstocks.feedstocks,
bio_leadfeedstocks.feedstockid,
bio_leadfeedstocks.weight
FROM
bio_feedstocks,
bio_leadfeedstocks
WHERE
bio_leadfeedstocks.feedstockid=bio_feedstocks.id
AND bio_leadfeedstocks.leadid=$leadid
";
$result1=DB_query($sql1,$db);
}

    
   

     if ($_GET['editlead']) {
         $stockid=$_GET['stockid1'];
         $lead=$_GET['editlead'];
         $proposal_ID=$_GET['prop_id'];
         
         if($_GET['stockid1'])
         {
             if($_GET['remove']==1){
                $sql4="DELETE FROM bio_temppropitems where leadid=".$lead." AND stockid='".$stockid."' ";
                    // echo $sql4;
                    $result4=DB_query($sql4,$db);
                     $sql_4="DELETE FROM bio_temppropsubsidy where leadid=".$lead." AND stockid='".$stockid."'  " ;
                     // echo $sql4;
                     $result_4=DB_query($sql_4,$db); 
                   //  $sql_422="DELETE FROM bio_propsubsidy where leadid=".$lead." stockid='".$stockid."' " ;
                     // echo $sql4;
                    // $result_422=DB_query($sql_422,$db); 
             }elseif($_GET['remove']!=1){
                 
                 if (checkduplicateentries($stockid,$lead,$db)==0) {
         addpropitem1($stockid,$lead,$flag2,$db);
            }  else {
                if($_GET['subsidy']!=1){
                           echo "Item is already selected.";   
                }
            
            // echo "<script type=\"JavaScript\">
            //  msg='Item is already selected.';
            //  displayAlertMessage(msg);
            //  selectcategory(".$lead.",0);
            //  </script>";
            //disppropitem($lead,$flag2,$db);
            }
                 
             }
         
         }
         
  //delleadfromtempprod($lead,$db);
  edititem($stockid,$lead,$proposal_ID,$db);
}else{
    
    
      echo '<center><font style="color: #333;
    background:#fff;
    font-weight:bold;
    letter-spacing:0.10em;
    font-size:16px;
    font-family:Georgia;
    text-shadow: 1px 1px 1px #666;">CREATE PROPOSALS</font></center>';
    
    echo "<table style='width:700px'>";
echo"<tr><td>";
echo"<div id=panel>"; 
       echo"<table>";
echo"<tr><td style='width:50%'>";
echo"<fieldset style='height:230px;width:376px'><legend>Customer Details</legend>";
echo"<table style='width:90%'>";
echo"<tr>";
if($enqtype==2){
echo"<tr><td width=50%>Contact Person:</td>";
echo"<td><input type='hidden' name='custperson' id='custperson' value='$cperson'>$cperson</td></tr>";
echo"<tr></tr>";    
}
echo"<tr><td width=50%>Customer Name:</td>";
echo"<td><input type='hidden' name='custname' id='custname' value='$cname'>$cname</td></tr>";
echo"<tr></tr>";
echo"<tr><td>Customer Contact:</td>";
echo"<td><input type='hidden' name='custph' id='custph' value='$cph'>$cph</td></tr>";
echo"<tr></tr>";
echo"<tr><td>Customer email</td>";
echo"<td><input type='hidden' name='email' id='email' value='$email'>$email</td></tr>";
echo"<tr></tr>";
echo"<tr><td>Customer Place:</td>";
echo"<td><input type='hidden' name='custplace' id='custplace' value='$place'>$place</td></tr>";
echo"<tr></tr>";
echo"<tr><td>Customer District:</td>";
echo"<td><input type='hidden' name='custdist' id='custdist' value='$district'>$district</td></tr>";
echo"<tr></tr>";
echo"<tr><td>Lead Source Team:</td>";
echo"<td><input type='hidden' name='team' id='team' value='$team'>$team</td></tr>";
echo"<tr></tr>";
echo"<tr><td>Lead Source:</td>";
echo"<td><input type='hidden' name='source' id='source' value='$sourcename'>$sourcename</td></tr>";
echo"<tr></tr>";
echo"<tr><td>Total Amount Paid:</td>";
echo"<td><input type='hidden' name='amount' id='amount' value='$amount'>$amount</td></tr>";
echo"<tr></tr>";
echo"<tr><td>Investment Size:</td>"; 
echo"<td><input type='hidden' name='createdby' id='createdby' value='$investmentsize'>$investmentsize</td></tr>";  
echo"<tr></tr>";
echo"<tr><td>Created By:</td>"; 
echo"<td><input type='hidden' name='createdby' id='createdby' value='$createdby'>$createdby</td></tr>";  
echo"<tr></tr>";
echo"</table>";
echo"</fieldset>";
echo"</td>";
echo"<td style='width:50%;height:140px' valign=top>";
echo"<fieldset  style='height:230px;width:376px'><legend>Feadstock</legend>";
echo"<table id='fead' style='width:100%'>";
}




if ($leadid!="")
{
echo"<tr style='background:#969696;color:white'><td>Feadstock</td><td>Weight in kg</td></tr>";

          while($myrow1=DB_fetch_array($result1))
          {
            echo"<tr id='$myrow1[1]' onclick='plantselect(this.id)' style='background:#C0C0FF;cursor:pointer'><td>$myrow1[0]</td><td>$myrow1[2]</td></tr>";
                 }
              // if($flag==1){  echo"</tr><td><input type='button' id='$leadid' name='showselpropitems' value='Select Items' onclick='selectpropitems(this.id)'></td></tr>";  }
              if($flag==1){  echo"</tr><tr><td><input type='button' id='$leadid' name='showselpropitems' value='Select Items' onclick='selectcategory(this.id,1,$flag2);'></td></tr>";  }
}
echo "<div id=\"refr\" style=\"display:none\">";
echo "<tr><td>&nbsp;</td></tr>";
echo "<tr><td><input type=\"button\" value=\"Refresh this page\" onClick='window.location=\"bio_proposal.php\";'></td></tr>";
echo "</div>";

echo "</table></fieldset>";
echo "</td></tr>";
echo "</table>";

//echo "<input type=\"text\" id=\"sid\" name=\"sid\" value='' onchange=location.href=\"?stockid=this.value\";>";

$flag2=$_GET['flag2']; 

if ($_GET['stockid']) {
  $stockid=$_GET['stockid'];
if ($_GET['lead']) {
  $lead=$_GET['lead'];
}
  if ($stockid) {
    echo '<script type="JavaScript">$("#grid").hide();</script>';
  }
echo "<table width=700px>";
echo "<tr><td colspan='2'>";
echo "<div id='sellist' style='background: #D6DEF7;'>";
echo "<div id='messageBox'>&nbsp;</div>";
if ($_GET['first']==1) {
  delleadfromtempprod($lead,$db);
  addpropitem($stockid,$lead,$flag2,$db);
}elseif($_GET['first']==2){
  disppropitem($lead,$flag2,$db);  
    
}
 else {
if (checkduplicateentries($stockid,$lead,$db)==0) {
    addpropitem($stockid,$lead,$flag2,$db);
}  else {
  echo "Item is already selected.";
// echo "<script type=\"JavaScript\">
//  msg='Item is already selected.';
//  displayAlertMessage(msg);
//  selectcategory(".$lead.",0);
//  </script>";
disppropitem($lead,$flag2,$db);
}

}
/*elseif($_GET['first']==2){
  disppropitem($lead,$flag2,$db);  
    
} */
echo "</div>";
echo "</td></tr>";
}
echo "</div>";
/*
$sql_1="SELECT * FROM bio_temppropsubsidy";
$result_1=DB_query($sql_1,$db);
$row_count=DB_num_rows($result_1);
$myrow_1=DB_fetch_array($result_1);
if($row_count>0){
    echo "<tr><td>";
echo "<fieldset style='width:780px'><legend>Subsidy Details</legend>";
echo "<div id='sellist' style='background: #D6DEF7;'>";
echo "<div id='messageBox'>&nbsp;</div>";
    dispropsubsidy($db);
  echo "</div>";
echo "</fieldset>";
echo "</td></tr>";  
    
}
*/


echo"<table>";
echo'<tr><td colspan=2><p><div class="centre">';
echo '<input id="shwprint" type="button" name="shwprint" value="view/hide Quatations">';
echo '<td colspan=2><p><div class="centre">';
//echo"<input type=\"button\" value=\"Show Grid\" onClick='window.location=\"bio_proposal.php\";'></td>";
//echo'</div>';
echo'</td></tr>';

echo'</div>'; 
echo"</td></tr></table>";




if ($_GET['categoryid']) {
  $categoryid=$_GET['categoryid'];
  if ($categoryid) {
    echo '<script>$("#grid").hide();</script>';
  }
// echo "<tr><td colspan='2'>";
// echo "<div id='sellist' style='background: #D6DEF7;'>";
// addpropitem($stockid,$lead,$db);
// echo "</div>";
// echo "</td></tr>";
}

//-----------------------------------------------------------------------------------------

if(!isset($_GET['lead']))
{
     $empid=$_SESSION['empid'];
 
      $sql_emp1="SELECT * FROM bio_emp
                WHERE empid=".$empid;
     $result_emp1=DB_query($sql_emp1,$db);
     $myrow_emp1=DB_fetch_array($result_emp1);
     
     $employee_arr=array();   
     $sql_drop="DROP TABLE `emptable`";
     $result_drop=DB_query($sql_drop,$db);
 
                      $sql_create="CREATE TABLE emptable (empid int)";
                      $result_create=DB_query($sql_create,$db);   
                       
                  function showemp($empid,$db,$y)         
                  {  
                      $sql3="SELECT empid FROM bio_emp WHERE reportto='".$empid."'";
                      $result3=DB_query($sql3,$db);
                      

                      
                      $employee_arr=array();
                      while($row3=DB_fetch_array($result3))
                      {
                      $empid=$row3['empid'];
                      $sql_insert="INSERT INTO emptable (empid) VALUES ($empid)";
                      $result_insert=DB_query($sql_insert,$db);
                      
                      $employee_arr[]=$empid;

                      showemp($empid,$db,$y);    
                                            
                      } 
                                      
                  } 
                      
                      $sql_insert1="INSERT INTO emptable (empid) VALUES ($_SESSION[empid])";
                      $result_insert2=DB_query($sql_insert1,$db);
     
     $sql2="SELECT empid,reportto FROM bio_emp WHERE reportto=$empid";
     $result2=DB_query($sql2,$db);   
  
     while($row2=DB_fetch_array($result2))
     { 

        $empid=$row2['empid'];
        $employee_arr[]=$empid;
        $y=$empid;
        showemp($empid,$db,$y);
         
     } 
       

     $sql_select="SELECT empid FROM emptable";
     $result_select=DB_query($sql_select,$db);
     
     while($row_select=DB_fetch_array($result_select))
     {
          $employee_arr[]=$row_select['empid'];
     }
     
     $employee_arr=join(",", $employee_arr);
   
   $team_arr=array();
   $sql6="SELECT teamid FROM bio_teammembers WHERE empid IN ($employee_arr)";
    $result6=DB_query($sql6,$db);
    while($row6=DB_fetch_array($result6)){
        $team_arr[]=$row6['teamid'];
    }
         
    $team_array=join(",", $team_arr);
    
$date=date("Y-m-d");
if(!isset($_GET['editlead']))
{
$sql="SELECT bio_cust.cust_id AS custid,
             bio_cust.custname AS custname,
             bio_cust.contactperson AS contactperson,
             bio_cust.custphone AS custphone,
             bio_cust.custmob AS custmob,
             bio_district.district AS district,
             bio_leadtask.leadid AS leadid,
             bio_leadtask.assigneddate AS assigneddate,
             bio_leadtask.duedate AS duedate,
             bio_leadteams.teamname AS teamname,
             bio_leads.enqtypeid AS enqtypeid              
        FROM bio_cust,bio_district,bio_leadtask,bio_leadteams,bio_leads
       WHERE bio_cust.cust_id=bio_leads.cust_id
         AND bio_district.did=bio_cust.district
         AND bio_district.stateid=bio_cust.state
         AND bio_district.cid=bio_cust.nationality      
         AND bio_leadtask.leadid=bio_leads.leadid 
         AND bio_leadteams.teamid=bio_leadtask.teamid 
         AND bio_leadtask.teamid IN ($team_array)
         AND bio_leadtask.taskcompletedstatus=0
         AND bio_leads.leadstatus in (0,6,25,29) and bio_leads.leadid not in (Select leadid from bio_proposal)
      
                     
         ";
         
if(isset($_POST['filterbut']))
{   
   // $sql.=" AND bio_leads.enqtypeid=1 AND bio_leadtask.taskid=1 ";  
    
    if (isset($_POST['Actiondate']))  {         
    if ($_POST['Actiondate']!='')   {  
     
    if ($_POST['Actiondate']==1) {
        
    $sql.=" AND bio_leadtask.duedate = '".$date."'";    
    }   

    if ($_POST['Actiondate']==2) { 
           
    $date=explode("-",$date);
    $startdate2=$date[1]."/".$date[2]."/".$date[0];
  
    $Tommorrow1 = strtotime($startdate2 . " +1 day");
    $Tommorrow=date("d/m/Y",$Tommorrow1);

    $Tommorrow2=FormatDateForSQL($Tommorrow);    
        
    $sql.=" AND bio_leadtask.duedate = '".$Tommorrow2."'";        
    } 
            
    if ($_POST['Actiondate']==3) {
        
    $date=explode("-",$date);
    $startdate2=$date[1]."/".$date[2]."/".$date[0];
  
    $lastWeek1 = strtotime($startdate2 . " -7 day");
    $lastWeek=date("d/m/Y",$lastWeek1);

    $lastWeek2=FormatDateForSQL($lastWeek);    
    $date=date("Y-m-d");     
    $sql.=" AND bio_leadtask.duedate BETWEEN '".$lastWeek2."' AND '".$date."'";                 
    }           
 
    if ($_POST['Actiondate']==4) {
        
 
         
    }   
    }
    }                                                     
    
    if (isset($_POST['byname']))  {
    if ($_POST['byname']!='')
    $sql .= " AND bio_cust.custname LIKE '%".$_POST['byname']."%'";
    }
    
    if (isset($_POST['phone']))  {        
    if ($_POST['phone']!='')   
    $sql .=" AND bio_cust.custmob LIKE '%".$_POST['phone']."%'";
    } 

    if (isset($_POST['byplace'])) {
    if ($_POST['byplace']!='')
    $sql.=" AND bio_district.district LIKE '%".$_POST['byplace']."%'";
    }
    
    $officeid=$_POST['off']; 
   
   

 } else{  
     
     $sql.=" AND bio_leadtask.duedate <= '$date' and bio_leadtask.taskid=1 and bio_leadtask.viewstatus=1";
     $_POST['Actiondate']=1;
     $actiondatedesc="Today";
 } 
 
     if(($myrow_emp1['designationid']!=1) && ($myrow_emp1['designationid']!=4)){
       $sql.=" AND bio_leadtask.taskid=1";
    }

     $sql .=" GROUP BY bio_leadtask.leadid ORDER BY bio_leads.leadid DESC";    // echo $sql;
     $result=DB_query($sql,$db);   
    
       
echo "<div id='grid' style='background: #D6DEF7;'>";
echo "<table>";
echo"<tr><td colspan=2>";

echo "<form action=" . $_SERVER['PHP_SELF'] . "?" . SID ." method=post>";
echo "<fieldset><legend>Live Leads</legend>";

echo"<table style='border:1px solid #F0F0F0;width:100%'>";

echo"<tr><td>Action for</td><td>Name</td><td>Phone</td><td>District</td><td>Office</td><td>Lead Source</td></tr>";
echo"<tr>";

echo '<td><select name="Actiondate" id="actiondate" style="width:100px">';
echo '<option value=0></option>';
echo '<option value="1">Today</option>';
echo '<option value="2">Tommorrow</option>';
echo '<option value="3">Last Week</option>';
echo '<option value="4">ALL</option>'; 
echo '<option selected='.$_POST['Actiondate'].'>'.$actiondatedesc.'</option>';                                                            
echo '</select></td>';    

echo"<td><input type='text' name='byname' id='byname'></td>";
echo"<td><input type='text' name='phone' id='phone'></td>"; 
echo"<td><input type='text' name='byplace' id='byplace'></td>";
echo '<td><select name="off" id="off" style="width:100px">';
echo '<option value=0></option>';
$sql1="select * from bio_office";
$result1=DB_query($sql1,$db);
while($row1=DB_fetch_array($result1))
{
  echo "<option value=$row1[id]>$row1[office]</option>";
}
echo '</select></td>';
echo '<td><select name="leadsrc" id="leadsrc" style="width:100px">';
echo '<option value=0></option>';
echo '<option value="ALL">Select ALL</option>';
$sql1="select * from bio_leadsources";
$result1=DB_query($sql1,$db);
while($row1=DB_fetch_array($result1))
{
  echo "<option value=$row1[id]>$row1[sourcename]</option>";
}
echo '</select></td>';
echo"<td><input type='submit' name='filterbut' id='filterbut' value='search'></td>";
echo"</tr>";
echo"</table>";
 

echo "<div style='height:200px; overflow:scroll;'>";
echo"<table style='width:100%'> ";
echo"<tr><th>Institution Name/<br>Customer Name</th><th>Contact No</th><th>District</th><th>Action Date</th><th>Team</th></tr>";    

$no=0;
$k=0;       
 while($myrow=DB_fetch_array($result))
 {
  
  $no++;
  $leadid=$myrow['leadid'];
  $duedate=ConvertSQLDate($myrow['duedate']);
  
  
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
          
          if($myrow['enqtypeid']==2){
          if($myrow['contactperson']!="" OR $myrow['contactperson']!=0){
             $custname=$myrow['custname']."<br /> - ".$myrow['contactperson']; 
          }
          }elseif($myrow['enqtypeid']==1){
             $custname=$myrow['custname']; 
             
                       $sql1="SELECT duedate FROM bio_leadtask WHERE bio_leadtask.leadid=$leadid AND taskid=1 AND bio_leadtask.taskcompletedstatus=0";
                       $result1=DB_query($sql1,$db);
                       $row1=DB_fetch_array($result1,$db);   
                       $duedate=ConvertSQLDate($row1['duedate']);               
          }
        
//        echo"<td>".$no."</td>";
//        echo"<td>".$leadid."</td>";  
        echo"<td>".$custname."</td>";
        echo"<td>".$myrow['custmob']."</td>";
        echo"<td>".$myrow['district']."</td>"; 
        echo"<td>".ConvertSQLDate($myrow['duedate'])."</td>"; 
        echo"<td>".$myrow['teamname']."</td>";
        echo"<td><a style='cursor:pointer;' id='$leadid' onclick='passid(this.id)'>" . _('Select') . "</a></td>"; 
  }     }
          
echo"</table>";
echo"</div>";
echo"</fieldset>";
echo"</form>";
echo "</td></tr>";
echo"</div>";
echo "</td></tr>";
echo"</table>";

echo'<div id="proposalgrid">';
echo"<fieldset style='width:750px'><legend>Quatation Details</legend>";
echo "<div style='height:200px; overflow:scroll;'>";
echo"<table style='width:763px'> ";
echo"<tr><th>Slno</th><th>Leadid</th><th>Name</th><th>Date</th><th>Items</th><th>Total Price</th><th>Status</th></tr>";

$sql_qtn="SELECT bio_proposal.propid,
                 bio_proposal.propdate,
                 bio_proposal.leadid,
                 bio_proposal.totprice,
                 bio_proposal.status AS statusid,
                 bio_cust.cust_id AS custid,
                 bio_cust.custname AS custname,
                 bio_proposal_status.status
            FROM bio_proposal,bio_cust,bio_leads,bio_proposal_status
            WHERE bio_cust.cust_id=bio_leads.cust_id
            AND bio_proposal.leadid=bio_leads.leadid
            AND bio_proposal_status.statusid=bio_proposal.status";
$result_qtn=DB_query($sql_qtn,$db);
echo '<tbody>';
echo '<tr>';                                       
$no=0; 
$k=0; 
while($myrow_qtn=DB_fetch_array($result_qtn))
{
    $no++;
    if ($k==1)
    {
        echo '<tr class="EvenTableRows">';
        $k=0;
    }else 
    {
        echo '<tr class="OddTableRows">';
                    $k=1;     
    }
    $leadid=$myrow_qtn['leadid'];
    $proposal_no=$myrow_qtn['propid'];
    
    /*if($myrow['assigned_from']=='' OR $myrow['assigned_from']==0){
        $assigned_frm='';
    }else{
        $sql_t="SELECT teamname FROM bio_leadteams
                WHERE teamid=".$myrow['assigned_from'];
        $result_t=DB_query($sql_t,$db);
        $myrow_t=DB_fetch_array($result_t);
        $assigned_frm=$myrow_t['teamname'];
    }*/
    printf("<td cellpading=2>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td><a style='cursor:pointer;' id='$leadid' onclick='viewProposal(this.id,$proposal_no)'>" . _('Edit') . "</a></td>",
            $no,
            $leadid,
            $myrow_qtn['custname'],
            ConvertSQLDate($myrow_qtn['propdate']),
            $item,
            $myrow_qtn['totprice'],
            $myrow_qtn['status']);
            if($myrow_qtn['statusid']==4){
                echo"<td><a style='cursor:pointer;' id='$leadid' onclick='printProposal(this.id,$proposal_no)'>" . _('Print') . "</a></td></tr>";
            }
            
    }
echo"</td>";
echo"</tr></tbody>";
echo"</table>";
echo"</div>";
echo"</fieldset>";  
echo'</div>';
}


echo "</td></tr>";
echo"</table>";
echo"</div>";



if(isset($_GET['propid'])){
 $id=$_GET['propid'];
 $lead=$_GET['leadid'];
 echo"<input type='hidden' id='propid1' name='propid' value='$id'>";
 echo"<input type='hidden' id='leadid1' name='leadid1' value='$lead'>";
  
 $sql5="SELECT letterno,letterdate FROM bio_proposal where propid=".$id; 
 $result=DB_query($sql5,$db);
 $myrow=DB_fetch_array($result);
 if(($myrow['letterno']==0 OR $myrow['letterno']=="") AND ($myrow['letterdate']==0 OR $myrow['letterdate']=="")){
 ?>
 <script>
 var str1=document.getElementById('propid1').value;
 var str2=document.getElementById('leadid1').value;
 controlWindow=window.open("bio_prop_letterdetails.php?propid=" + str1 + "&leadid=" + str2,"propletterdetails","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=700,height=400"); 
 </script> 
 <?php
 } else{
 ?>     
 <script>
 var str1=document.getElementById('leadid1').value;
 var str2=document.getElementById('propid1').value;
 controlWindow=window.open("bio_selectstdproposalprint.php?leadid=" + str1 +"&propid=" + str2,"selectproposalpdf","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=700,height=400");
  
// controlWindow=window.open("bio_proposal_coveringletter.php?leadid=" + str1 +"&propid=" + str2);
 </script>
 <?php    
  }
}


                              


 //include('includes/footer1.inc');

?>
<script>
$(document).ready(function() {
$("#proposalgrid").hide();
$('#shwprint').click(function() {
        $('#proposalgrid').slideToggle('slow',function(){});
       $('#leadgrid').slideToggle('slow',function(){});
    });

});

function passid(str){

location.href="?lead=" +str;
}

function saveproposal(lead,flag2){ //var a="#"+str;
//$(a).hide();
// alert(str);

//$("#grid").hide();
if (lead=="")
  {
  document.getElementById("sellist").innerHTML="";
  return;
  }
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("sellist").innerHTML=xmlhttp.responseText;
   
    window.opener.location='bio_domTaskview.php?lead=' +lead;             
    window.close();

    }
  }
xmlhttp.open("GET","bio_saveproposal.php?lead=" + lead +"&flag2="+flag2);
xmlhttp.send();

}


function modifyproposal(lead,propid){
//alert(lead);     alert(propid);
    var answer = confirm("Do you want to Save this as a new proposal?");
    if (answer){
        var proposal=0;
    }else{
        var proposal=propid;
    }
    
if (lead=="")
  {
  document.getElementById("sellist").innerHTML="";
  return;
  }
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("sellist").innerHTML=xmlhttp.responseText;

    }
  }
xmlhttp.open("GET","bio_modifyproposal.php?lead=" + lead + "&propid=" + proposal);
xmlhttp.send();

}




function updatetotalitemprice(k,lead,stock){ //var a="#"+str;
//$(a).hide();
// alert(str);
//$("#grid").hide();
if (stock=="")
  {
  document.getElementById("tprice").value="";
  return;
  }
   var s=stock;
 var q=document.getElementById('qty'+k).value;
 var p=document.getElementById('price'+k).value;
 var t=document.getElementById('tprice'+k).value=q*p;
 var sub1=document.getElementById('subsidy'+k).value;
 var sub=q*sub1;
 var n=document.getElementById('netprice'+k).value=t-sub;


if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("tprice").value=xmlhttp.responseText;
    }
  }
xmlhttp.open("GET","bio_updateproptempprice.php?stock="+s+"&qty="+q+"&price="+p+"&tprice="+t+"&lead="+lead+"&subsidy="+sub+"&nprice="+n);
xmlhttp.send();

if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp2=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp2=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp2.onreadystatechange=function()
  {
  if (xmlhttp2.readyState==4 && xmlhttp2.status==200)
    {
    document.getElementById("subsidy").value=xmlhttp2.responseText;
    }
  }
xmlhttp2.open("GET","bio_updateproptempsubsidy.php?stock="+s+"&qty="+q+"&price="+p+"&tprice="+t+"&lead="+lead+"&subsidy="+sub+"&nprice="+n);
xmlhttp2.send();


if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp1=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp1=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp1.onreadystatechange=function()
  {
  if (xmlhttp1.readyState==4 && xmlhttp1.status==200)
    {
    document.getElementById("netprice").value=xmlhttp1.responseText;
    }
  }
xmlhttp1.open("GET","bio_updateproptempnetprice.php?stock="+s+"&qty="+q+"&price="+p+"&tprice="+t+"&lead="+lead+"&subsidy="+sub+"&nprice="+n);
xmlhttp1.send();

}

function selectcategory1(lead,first,flag2)  {
  controlWindow=window.open("bio_biopropadditem.php?ledid="+lead+"&first="+first+"&flag2="+flag2,"selcaty","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=700,height=400");
}

/*function selectcategory1(lead,first,prop)  {
  //  alert(prop);
  controlWindow=window.open("bio_biopropedititem.php?ledid="+lead+"&first="+first + "&prop=" + prop,"selcaty","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=700,height=400");
}
*/
function selectpropitems(str) {
 controlWindow=window.open("bio_showselpropitems1.php?ledid="+str,"proplist","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=700,height=600");
}

function saveproposal2(lead) {
 controlWindow=window.open("bio_saveproposa2.php?lead="+lead,"saveprop","toolbar=yes,location=no,directories=no,status=no,menubar=yes,scrollbars=no,resizable=no,width=700,height=500");
 //window.location="bio_saveproposal.php?lead="+lead;
}

function addSubsidy(lead,item,flag,first,add)  {
  controlWindow=window.open("bio_biopropaddsubsidy.php?ledid="+lead+"&item="+item+"&flag="+flag+"&first="+first+"&add="+add,"addsubsidy","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=700,height=400");
}

 function addSubsidy1(lead,item,flag,first,add,prop)  {
    // alert(prop);
  controlWindow=window.open("bio_biopropeditsubsidy.php?ledid="+lead+"&item="+item+"&flag="+flag+"&first="+first+"&add="+add+ "&prop=" + prop,"addsubsidy","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=700,height=400");
}
function viewProposal(str1,str2){ 
  //  alert(str1);      alert(str2);
/*if (str1=="")
  {
  document.getElementById("panel").innerHTML="";
  return;
  }
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("panel").innerHTML=xmlhttp.responseText;

    }
  }
xmlhttp.open("GET","bio_editstdproposals.php?lead=" + str1 + "&propid=" + str2);
xmlhttp.send();                                                                */
   location.href="?editlead=" +str1 + "&prop_id=" + str2; 
}



function printProposal(str1,str2){
location.href="?leadid=" + str1 + "&propid=" +str2; 
} 




function displayAlertMessage(message) {
    var timeOut = 100
    jQuery('#messageBox').text(message).fadeIn()
    jQuery('#messageBox').css("display", "block")
    setTimeout(function() {
    jQuery('#messageBox').fadeOut()
    jQuery('#messageBox').css("display", "none")
    }, timeOut * 1000);
}

function removeitm(lead,item,propid,remove)  {
   // alert(lead);   alert(item);    alert(propid);
    
  var x;
var r=confirm("Are you sure to want to delete ?");
if (r==true)
  {
  remove=1;
  }
else
  {
  remove=0;
  }
  if(remove==1)
  {
       location.href = "?stockid1=" + item + "&first=" + 2 +  "&editlead=" + lead + "&prop_id=" + propid + "&remove=" + remove; 
  }

//
}


</script>

