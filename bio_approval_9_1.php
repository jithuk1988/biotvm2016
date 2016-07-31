<?php
$PageSecurity = 30;
include('includes/session.inc');
$title = _('Approval');  
include('includes/header.inc');
include('includes/bio_GetPrice.inc'); 

echo '<center><font style="color: #333;
    background:#fff;
    font-weight:bold;
    letter-spacing:0.10em;
    font-size:16px;
    font-family:Georgia;
    text-shadow: 1px 1px 1px #666;">Pending Approvals</font></center>';
    $office=$_SESSION['UserStockLocation'];
    

    
    
if (isset($_POST['submit'])) {
    if(!isset($_POST['LeadID'])){   
      prnMsg('Select approval details from the grid','warn');  
    }
    else{
    $lead_ID=$_POST['LeadID'];
    $app_status=$_POST['app_status'];
    $amount=$_POST['advanceamount'];
    $team_ID=$_POST['team'];
    if($_SESSION['task']!=''){
       $task_ID=$_SESSION['task']; 
    }else{
       $task_ID=$_POST['TaskID']; 
    }
     
    if($_POST['ProposalID']!='') {
        $proposal_ID=$_POST['ProposalID'];
    }else{
        $proposal_ID=$_SESSION['proposal'];
    }
    
if($app_status==1 || $app_status==''){

$sql="UPDATE bio_approval SET taskcompletedstatus =1,taskcompleteddate='".date("Y-m-d")."' 
                                     WHERE leadid =$lead_ID
                                     AND proposal_no=".$proposal_ID;                             
                                     
}
elseif($app_status==2){
    
$sql="UPDATE bio_approval SET taskcompletedstatus = 2 
                                     WHERE leadid =$lead_ID
                                     AND proposal_no=$proposal_ID";
                                       
}
//$result=DB_query($sql,$db);




if(($app_status==1) AND ($task_ID==6)){
$sql1="UPDATE bio_proposal SET status =2 
                         WHERE leadid =$lead_ID
                         AND propid=$proposal_ID";
$sql_lead="UPDATE bio_leads
                        SET leadstatus=1
                        WHERE leadid='$lead_ID'";
$result_lead= DB_query($sql_lead,$db);
}

elseif(($app_status==1) AND ($task_ID==7)){
$sql1="UPDATE bio_feasibilitystudy SET feasibility_status = 4 WHERE leadid =$lead_ID";
$sql_lead="UPDATE bio_leads
                        SET leadstatus=3
                        WHERE leadid='$lead_ID'";
$result_lead= DB_query($sql_lead,$db);
}
elseif(($app_status==2) AND ($task_ID==7)){
$sql1="UPDATE bio_feasibilitystudy SET feasibility_status = 3 WHERE leadid =$lead_ID";
}



if(($app_status==1) AND ( $task_ID==10)){  

         $sql_item_delt="DELETE FROM bio_conceptproposaldetails WHERE cp_id=".$proposal_ID;  
         $result_item_delt=DB_query($sql_item_delt,$db);

    
$sql_select="SELECT stockid,description,qty,price,tprice 
             FROM   bio_temppropitems 
             WHERE  leadid=".$lead_ID;
$result_select=DB_query($sql_select,$db);

$i=0;
while($row_select=DB_fetch_array($result_select))
{
    $i++;
               $sql4="INSERT INTO bio_conceptproposaldetails (cp_id,
                                                        slno,
                                                        stockid,
                                                        description,
                                                        qty,
                                                        price,
                                                        tprice) 
                                              VALUES (".$proposal_ID.",
                                                      ".$i.",
                                                     '".$row_select['stockid']."',
                                                     '".$row_select['description']."',
                                                      ".$row_select['qty'].",
                                                      ".$row_select['price'].",
                                                      ".$row_select['tprice'].")";
                DB_query($sql4,$db);
}

$sql1="SELECT SUM(tprice) FROM bio_temppropitems WHERE leadid=".$lead_ID;
$result1=DB_query($sql1,$db);
$row1=DB_fetch_array($result1);
$sum=$row1[0];

            $sql1="UPDATE bio_conceptproposal SET total_price=$sum,status = 4 WHERE lead_id =$lead_ID AND cp_id=".$proposal_ID;
            $sql_lead="UPDATE bio_leads
                                    SET leadstatus=4
                                    WHERE leadid='$lead_ID'";
            $result_lead= DB_query($sql_lead,$db);

}elseif(($app_status==2) AND ($task_ID==10)){

            $sql1="UPDATE bio_conceptproposal SET status = 3 WHERE lead_id =$lead_ID AND cp_id=".$proposal_ID;     // status->3  previous cp(modified)
}elseif(($app_status=='') AND ($task_ID==10)){

                      
    $sql_select="SELECT * FROM bio_conceptproposal WHERE lead_id=".$lead_ID." AND cp_id=".$proposal_ID;
    $result_select=DB_query($sql_select,$db);
    $row_select=DB_fetch_array($result_select);
    
    $sql_sum="SELECT SUM(tprice) FROM bio_temppropitems WHERE leadid=$lead_ID";
    $result_sum=DB_query($sql_sum,$db);
    $row_sum=DB_fetch_array($result_sum);
    
           $sql_update="UPDATE bio_conceptproposal SET status=3 WHERE lead_id=".$lead_ID." AND cp_id=".$proposal_ID;     
           DB_query($sql_update,$db);
           
           $sql_insert="INSERT INTO bio_conceptproposal  (lead_id,fsentry_id,total_price,created_on,created_by,approved_by,signatory_by,status)
                                                 VALUES  (".$lead_ID.",
                                                          ".$row_select['fsentry_id'].",
                                                          ".$row_sum[0].", 
                                                          '".date('Y-m-d')."',
                                                          '".$row_select['created_by']."',
                                                          '".$_SESSION['UserID']."',
                                                          '".$row_select['signatory_by']."',
                                                          6)";                                                           // status->6  modified cp 
          DB_query($sql_insert,$db);
          
          $cpid=DB_Last_Insert_ID($Conn,'bio_conceptproposal','cp_id');    
          
          
           
           $sql_select="SELECT stockid,description,qty,price,tprice FROM bio_temppropitems WHERE leadid=".$lead_ID;
           $result_select=DB_query($sql_select,$db);
           
           
           
           $i=0;
while($row_select=DB_fetch_array($result_select))
{
    $i++;
    
               $sql4="INSERT INTO bio_conceptproposaldetails (cp_id,
                                                                slno,
                                                                stockid,
                                                                description,
                                                                qty,
                                                                price,
                                                                tprice) 
                                                          VALUES (".$cpid.",
                                                                  ".$i.",
                                                                 '".$row_select['stockid']."',
                                                                 '".$row_select['description']."',
                                                                  ".$row_select['qty'].",
                                                                  ".$row_select['price'].",
                                                                  ".$row_select['tprice'].")";
                 DB_query($sql4,$db);
                 
                 
      
                 
                 $sql_sub="SELECT * FROM bio_subsidymaster
                           WHERE subsidy_plant_id='".$row_select['stockid']."'";
                 $result_sub=DB_query($sql_sub,$db);
                 $subsidy_count=DB_num_rows($result_sub);

if($subsidy_count>0){
             
    DB_query("DELETE FROM bio_cpsubsidy WHERE leadid=$lead_ID",$db);
    while($myrow_sub=DB_fetch_array($result_sub)){
        
        
        
    $scheme=$myrow_sub['subsidy_scheme_id'];
    $amount=$myrow_sub['subsidy_amount'];
    
$sql_subsidy="INSERT INTO bio_cpsubsidy(cp_id,
                                          leadid,
                                          stockid,
                                          scheme,
                                          amount)
                                   VALUES( $cpid,
                                          $lead_ID,
                                          '".$row_select['stockid']."',
                                          $scheme,
                                          $amount)";
        
  $result_subsidy=DB_query($sql_subsidy,$db,$Errmsg);
}

}

}

$sql_delete1=DB_query("DELETE FROM bio_temppropsubsidy WHERE leadid=$lead_ID",$db);
$sql_delete2=DB_query("DELETE FROM bio_temppropitems WHERE leadid=$lead_ID",$db); 
}

      
        if(($app_status==1) AND ($task_ID==9)){  
        $sql1="UPDATE bio_conceptproposal SET status = 4 WHERE lead_id =$lead_ID AND cp_id=$cpid";
        $sql_lead="UPDATE bio_leads
                                SET leadstatus=4
                                WHERE leadid='$lead_ID'";
        $result_lead= DB_query($sql_lead,$db);
        }
        elseif(($app_status==2) AND ($task_ID==9)){
        $sql1="UPDATE bio_conceptproposal SET status = 3 WHERE lead_id = $lead_ID AND cp_id=$cpid";
        }  



if(($app_status==1) AND ($task_ID==11)){
$sql1="UPDATE bio_dpr SET dpr_status = 4 WHERE leadid =$lead_ID";
$sql_lead="UPDATE bio_leads 
                        SET leadstatus=5
                        WHERE leadid='$lead_ID'";
$result_lead= DB_query($sql_lead,$db);
}
elseif(($app_status==2) AND ($task_ID==11)){
$sql1="UPDATE bio_dpr SET dpr_status = 3 WHERE leadid =$lead_ID";
}
if(($app_status==1) AND ($task_ID==16)){
$sql1="UPDATE bio_fsproposal SET fp_approvalstatus = 2, status=2 WHERE leadid =$lead_ID and fs_propono=$proposal_ID";
DB_query($sql1,$db);
$sql_cur_st="SELECT leadstatus FROM bio_leads
                WHERE leadid=$lead_ID";
$result_cur_st= DB_query($sql_cur_st,$db);
$myrow_cur_st=DB_fetch_array($result_cur_st);
$current_st=$myrow_cur_st['leadstatus'];

if($current_st==47){
    $sql_lead="UPDATE bio_leads
                        SET leadstatus=26
                        WHERE leadid=$lead_ID";
$result_lead= DB_query($sql_lead,$db);
}
}
elseif(($app_status==2) AND ($task_ID==16)){
$sql1="UPDATE bio_fsproposal SET fp_approvalstatus = 3,status=3 WHERE leadid =$lead_ID and fs_propono=$proposal_ID";
$result1=DB_query($sql1,$db);
}
$result1=DB_query($sql1,$db);  
    unset($_POST['team']);
    unset($_POST['StartDate']);
    unset($_POST['EndDate']);
 }  
   
if(isset($_POST['Reason']))
{
$reason=$_POST['Reason'];   
$sql_reject="INSERT INTO bio_rejectedproposal(leadid,
                                               task,
                                               reason)
                                  VALUES ('".$lead_ID."',
                                          '".$task_ID."',
                                          '".$reason."')";                                           
        $result = DB_query($sql_reject,$db);    
}
}   

echo"<div id=fullbody>";
echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "?" . SID . "'>"; 
echo '<table style="width:65%"><tr><td>';
echo"<div id=panel>";
echo '<table><tr>';
//========================================== Left Panel Begins

echo'<td>';
echo"<div id=cus_details>";
echo"<fieldset style='width:800px;height:150px'>"; 
echo"<legend>Customer Details</legend>";
echo"</legend>";
echo"<table width=100%>";
if($_GET['lead']!=''){
    $leadid=$_GET['lead'];    
    $sql="SELECT bio_leads.leadid,
                    bio_cust.custname,
                    bio_cust.custphone,
                    bio_cust.custmob,
                    bio_cust.custmail,
                    bio_cust.area1,
                    bio_cust.district,
                    bio_cust.state,
                    bio_cust.nationality  
              FROM  bio_leads,bio_cust
              WHERE bio_leads.cust_id=bio_cust.cust_id
              AND bio_leads.leadid=".$leadid;
 $result=DB_query($sql,$db);
 $myrow=DB_fetch_array($result); 
 $no=0; 
 $k=0; 
 $cname=$myrow[1];  
 if($myrow[2]!='-'){
     $cph=$myrow[2]; 
 }else{
     $cph=$myrow[3]; 
 }
 $email=$myrow['custmail'];  
 $place=$myrow[5];
 $dist=$myrow[6];
 $ste=$myrow[7];
 $ctry=$myrow[8]; 
 $sql1="SELECT bio_district.district 
          FROM bio_district
          WHERE bio_district.stateid=".$ste."  
          AND bio_district.cid=".$ctry."
          AND bio_district.did=".$dist;     
 $result1=DB_query($sql1,$db);
 $myrow=DB_fetch_array($result1);
 $district=$myrow[0];
 
 $sql2="SELECT taskid FROM bio_approval WHERE leadid=$leadid";
 $result2=DB_query($sql2,$db);
 $row2=DB_fetch_array($result2);
 $taskid=$row2['taskid'];       
}
echo"<tr><td width=50%>Customer Name</td>";
echo"<td><input type='text' name='custname' id='custname' value='$cname'></td></tr>";
echo"<tr></tr>";
echo"<tr><td>Customer Phone</td>";
echo"<td><input type='text' name='custph' id='custph' value='$cph'></td></tr>";
echo"<tr></tr>";
echo"<tr><td>Customer email</td>";
echo"<td><input type='text' name='email' id='email' value='$email'></td></tr>";
echo"<tr></tr>";
echo"<tr><td>Customer Place</td>";
echo"<td><input type='text' name='custplace' id='custplace' value='$place'></td></tr>";
echo"<tr></tr>";
echo"<tr><td>Customer District</td>";
echo"<td><input type='text' name='custdist' id='custdist' value='$district'></td></tr>";
echo"<tr></tr>";
echo"</table>";
echo"</fieldset>";
echo"<input type='hidden' name='LeadID' id='leadid' value='$leadid'>"; 
echo"<input type='hidden' name='TaskID' id='taskid' value='$taskid'>";
echo'</div>';
echo"</td>";
//========================================== Top Panel Ends 

    //========================================== Second Panel Begins

    
    echo"</tr></table>";    
    echo"</div>";         

function checkduplicateentries($stockid,$lead,$db) {
  $sql4="select COUNT(*) FROM bio_temppropitems where leadid=".$lead ." AND stockid='".$stockid."'";
  $result4=DB_query($sql4,$db);
  $myrow4=DB_fetch_array($result4);
  if ($myrow4[0]>0) {
    return 1;
  }
  else {return 0;};
 }    
    
function modify($leadid,$db)
{ 
                
    
echo"<fieldset style='width:785px; '>";
echo"<legend>Proposal Details</legend>";
echo"<table style='width:85%'>";

echo"<tr style='background:#D50000;color:white'>";
echo"<td>Slno</td>
     <td>Feed Stock</td>
     <td>Feed Stock Source</td>
     <td>No of Sources</td>
     <td>Weight</td></tr>";
 $n=1;
 $sql_fstock="SELECT bio_feedstocks.feedstocks,
                     bio_fs_feedstockdetails.gasout,
                     bio_fs_feedstockdetails.weight,
                     bio_fs_feedstockdetails.number_source,
                     bio_feedstocksources.feedstocksourcename,
                     bio_fssources.source 
                FROM bio_fs_feedstockdetails,
                     bio_feedstocks,
                     bio_feedstocksources,
                     bio_fssources
               WHERE bio_feedstocks.id=bio_fs_feedstockdetails.feedstockid
                 AND bio_fs_feedstockdetails.feedstocksourceid=bio_feedstocksources.feedstocksourceid
                 AND bio_fssources.id=bio_fs_feedstockdetails.feedstocksourceid
                 AND bio_fs_feedstockdetails.leadid=".$leadid;
                 
 $result_fstock=DB_query($sql_fstock, $db);    
 while($myrow=DB_fetch_array($result_fstock)){
    echo "<tr style='background:#000080;color:white'>
        <td>$n</td>
        <td>$myrow[0]<input type='hidden' id='hfeedstock' value='$myrow[0]'></td>
        <td>$myrow[5]<input type='hidden' id='hfeedsource' value='$myrow[5]'></td>
        <td>$myrow[3]<input type='hidden' id='hfeedsourceno' value='$myrow[3]'></td>
        <td>$myrow[2]<input type='hidden' id='hfeedweight' value='$myrow[2]'></td>
        </tr>
        ";
 $n++; 
 }
 $sql_fsedit="SELECT * FROM bio_fs_entrydetails
                        WHERE leadid=".$leadid;
$result_fsedit=DB_query($sql_fsedit,$db);
$myrow_fsedit=DB_fetch_array($result_fsedit);
$fsentr_ID=$myrow_fsedit['fsentry_id'];  

echo"<table style='width:80%;' border=0>";
echo"<tr><td colspan='2'>";
echo"<table border=0 style='width:80%;'>";
echo"<tr><td>Easily Degradable</td>";
echo"<td>:<input type='hidden' name='edegradable' id='edegradable' value='".$myrow_fsedit['edegradable']."'>".$myrow_fsedit['edegradable']." Kg</td></tr>";
echo"<tr></tr>";

echo"<tr><td>Slow Degradable</td>";
echo"<td>:<input type='hidden' name='sdegradable' id='sedegradable' value='".$myrow_fsedit['sdegradable']."'>".$myrow_fsedit['sdegradable']." Kg</td></tr>";
echo"<tr></tr>";

echo"<tr><td>Very Slow Degradable</td>";
echo"<td>:<input type='hidden' name='vsdegradable' id='vsedegradable' value='".$myrow_fsedit['vsdegradable']."'>".$myrow_fsedit['vsdegradable']." Kg</td></tr>";
echo"<tr></tr>";

echo"<tr><td>Non Degradable</td>";
echo"<td>:<input type='hidden' name='nondegradable' id='nonedegradable' value='".$myrow_fsedit['ndegradable']."'>".$myrow_fsedit['ndegradable']." Kg</td></tr>";
echo"<tr></tr>";

echo"<tr><td>Total Feedstocks</td>";
echo"<td>:<input type='hidden' name='actual' id='actual' value='".$myrow_fsedit['actual_feedstock']."'>".$myrow_fsedit['actual_feedstock']." Kg</td></tr>";
echo"<tr></tr>";

echo"<tr><td>Total Gas</td>";
echo"<td>:<input type='hidden' name='TotalGas' id='totalgas' value='".$myrow_fsedit['total_gas']."'>".$myrow_fsedit['total_gas']."</td></tr>";
echo"</table>";

              $sql_temp="SELECT * FROM bio_temppropitems WHERE leadid =$leadid";
              $result_temp=DB_query($sql_temp,$db);
              echo "<tr><td colspan=2>";
              echo "<table style='width:800px' border='1'>";
              echo "<tr><th width=50>Stock Id</th><th width=200>Item Description</th><th>qty</th><th>Price</th><th>Total Price</th><th>Subsidy</th><th>Net Price</th></tr>";
                   
              $k=0;
              while($myrow_temp=DB_fetch_array($result_temp)){  
                   $k++;
                   $std_amount=GetPrice($myrow_temp['stockid'],$db);
                   $stock[$k]=$myrow_temp['stockid'];
                   $quantity=$myrow_temp['qty'];
                   $price=$myrow_temp['price'];
                   $tprice=$quantity*$price;
                   $item[$k]=$myrow_temp['stockid'];
                   
                   echo "<tr><td>".$stock[$k]."</td><td>".$myrow_temp['description']."</td>
                   <td><input size=3 style=\"text-align: right\" type=\"text\" name=\"qty".$k."\" id=\"qty".$k."\" value=".$myrow_temp['qty']." onchange=updatetotalitemprice(".$k.",".$leadid.",\"$stock[$k]\") ></td>
                   <td class=number><input size=9 style=\"text-align: right\" type=\"text\" name=\"price".$k."\" id=\"price".$k."\" value=".$myrow_temp['price']." onchange=updatetotalitemprice(".$k.",".$leadid.",\"$stock[$k]\")></td>
                   <td class=number><input size=9 style=\"text-align: right\" type=\"text\" name=\"tprice".$k."\" id=\"tprice".$k."\" value=".$tprice." ></td>
                   <td class=number><input size=9 style=\"text-align: right\" type=\"text\" name=\"subsidy".$k."\" id=\"subsidy".$k."\" value=".$myrow_temp['subsidy']." readonly></td>
                   <td class=number><input size=9 style=\"text-align: right\" type=\"text\" name=\"netprice".$k."\" id=\"netprice".$k."\" value=".$myrow_temp['netprice']." readonly></td>
                   <td align=center><a  style='cursor:pointer;'  id='$leadid' onclick='addSubsidy($leadid,\"$item[$k]\")'>" . _('Manage Subsidy') . "</a></td> 
                   <td align=center><a  style='cursor:pointer;'  id='$leadid' onclick='removeitm($leadid,\"$item[$k]\",2)'>" . _('Remove') . "</a></td>
                   
                   </tr>";    
              } 
              echo "<tr><td colspan=2>
          <input type='button' id=\"".$leadid."\" value='Add Item'  onclick='selectplant(this.id);'>
       </td>";
 

echo"</tr></table>";
echo"</td></tr></table>";
echo"</fieldset>"; 

}

//-----------------------select plant----------------------//

if($_GET['modify']==2)
{   
    $leadid=$_GET['lead'];
    $_SESSION['proposal']=$_GET['proposal'];     
    $_SESSION['task']=$_GET['task']; 
    modify($leadid,$db);   
}

if(isset($_GET['remove']))      
{

$stockid=$_GET['stockid1'];    
$leadid=$_GET['lead'];
    
$sql="DELETE
      FROM bio_temppropitems
      WHERE leadid= $leadid AND
            stockid='$stockid'";    
$result=DB_query($sql,$db);

$sql_delete="DELETE FROM bio_temppropsubsidy
                   WHERE bio_temppropsubsidy.leadid=".$leadid."
                   AND bio_temppropsubsidy.stockid='".$stockid."'";
$result_delete = DB_query($sql_delete,$db);
modify($leadid,$db);    
} 

if((isset($_GET['stockid'])) && ($_GET['stockid']!='')) 
{ 
    
    $stockid=$_GET['stockid'];
    $leadid=$_GET['lead'];
    $price=GetPrice($stockid,$db);
    
//    if(checkduplicateentries($stockid,$leadid,$db)==1) 

     
//$sql_des="SELECT longdescription FROM stockmaster
//          WHERE stockid='".$stockid."'";
//$result_des=DB_query($sql_des,$db);
//$myrow_des=DB_fetch_array($result_des);
//$longdes=$myrow_des['longdescription'];

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
                                       VALUES(".$leadid.",
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
    
$sql="INSERT INTO bio_temppropitems (stockid,description,qty,price,tprice,leadid,subsidy,netprice) VALUES ('".$_GET['stockid']."','".$longdes."',1,".number_format($price,2,'.','').",".number_format($price,2,'.','').",".$leadid.",'".$subsidy."',".$netprice.")";
DB_query($sql,$db);
modify($leadid,$db);    
}

if(isset($_GET['subsidy'])) 
{
   $leadid=$_GET['lead']; 
   modify($leadid,$db);   
}
 
     
//========================================== Buttons 
echo"<table>";
echo'<tr><td colspan=2><p><div class="centre"><input type=submit name=submit value="' . _('Submit') . '" onclick="if(log_in()==1)return false;">';
echo '<input name="clear" type="submit" value=Clear >';
echo'</div>';
echo'</td></tr>';
echo'</table>';
//========================================== Buttons Ends   
 
echo"</td></tr></table>";       
echo'</form>';
echo'</div>';
  
//========================================== Grid - from leads table
echo'<div id="leadgrid">';
echo"<fieldset style='width:830px'><legend>Pending for Approval</legend>";
echo'<div style="height:200px;overflow:scroll">';
echo"<table style='width:100%'> ";
echo"<tr><th>Slno</th><th>Leadid</th><th>Name</th><th>Place</th><th>Date</th><th>Task</th><th>Submitted By</th></tr>";
$office=$_SESSION['UserStockLocation'];   
//====================================================================================================================================================//
    $s_userid=$_SESSION['UserID'];
    $s_offid=$_SESSION['officeid'];
        $office_arr=array();
        $office_arr[]=$s_offid;  
     $sql2="SELECT reporting_off,id
            FROM bio_office
            WHERE reporting_off=$s_offid
            ";     
     $result2=DB_query($sql2,$db);
     $myrow_count = DB_num_rows($result2);
     if($myrow_count>0){
     while($row2=DB_fetch_array($result2)){
         $office_arr[]=$row2['id'];   
     $sql3="SELECT id,reporting_off 
                FROM bio_office
                WHERE reporting_off=".$row2['id']."";
        $result3=DB_query($sql3,$db);
        $myrow_count1 = DB_num_rows($result3);
     if($myrow_count1>0){
     while($row3=DB_fetch_array($result3)){
               $office_arr[]=$row3['id'];       
     $sql4="SELECT id,reporting_off 
                FROM bio_office
                WHERE reporting_off=".$row3['id']."";
        $result4=DB_query($sql4,$db);
        $myrow_count2 = DB_num_rows($result4);
     if($myrow_count2>0){
     while($row4=DB_fetch_array($result4)){
               $office_arr[]=$row4['id'];         
        }
        }  
        }   
     }
     }
     }
     $office_array=join(",", $office_arr);
     $sql5="SELECT *  
                FROM bio_emp
                WHERE offid IN ($office_array)";
     $result5=DB_query($sql5,$db);
     while($row5=DB_fetch_array($result5)){
//         $row5['empid'];
    $sql6="SELECT userid FROM www_users WHERE empid=".$row5['empid'];
    $result6=DB_query($sql6,$db);
    $row6=DB_fetch_array($result6);
    $userid[]="'".$row6[0]."'";     
    $user_array=join(",", $userid);            
     }                         
  $sql="SELECT bio_leads.leadid AS leadid,  
  bio_cust.cust_id AS custid,  
  bio_cust.custname AS custname,               
  bio_cust.area1 AS place,
  bio_approval.assigneddate AS assigneddate,
  bio_approval.submitted_user AS submittedby,
  bio_task.task AS task,  
  bio_task.task AS taskid,
  bio_approval.taskcompletedstatus AS taskstatus,
  bio_approval.tid,
   bio_approval.proposal_no
FROM bio_cust,
bio_leads,
bio_approval,
bio_task
WHERE bio_approval.approval_user='$_SESSION[UserID]'
AND bio_leads.leadid=bio_approval.leadid
AND bio_approval.taskcompletedstatus=0 
AND bio_task.taskid=bio_approval.taskid 
AND bio_cust.cust_id=bio_leads.cust_id  
ORDER BY assigneddate ASC
";                                   
$result=DB_query($sql,$db);
echo '<tbody>';
echo '<tr>';                                       
$no=0; 
$k=0; 
while($myrow=DB_fetch_array($result))
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
    $leadid=$myrow['leadid'];
    $id=$myrow['tid'];
    $submitted_by=$myrow['submittedby'];
    $sql_emp="SELECT  bio_emp.empname
        FROM  bio_emp,www_users
        WHERE www_users.empid = bio_emp.empid
        AND www_users.userid='".$submitted_by."'";
      $result_emp=DB_query($sql_emp,$db);
      $myrow_emp=DB_fetch_array($result_emp);
      $empname=$myrow_emp['empname']; 
    printf("<td cellpading=2>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td> 
            <td><a  style='cursor:pointer;'  id='$id' onclick='passid($leadid,$id)'>" . _('select') . "</a></td></tr>",
            $no,
            $leadid,
            $myrow['custname'],
            $myrow['place'],
            ConvertSQLDate($myrow['assigneddate']),
            $myrow['task'],
            $empname);          
}
echo"</tr></tbody></table>";
echo"</div>";
//echo"</fieldset>";  
echo'</div>';
echo"</td></tr></table>"; 
echo"</div>";
?>
<script type="text/javascript">     
function log_in()
{
//    document.getElementById('phone').focus();
var f=0;
var p=0;
if(f==0){f=common_error('custname','Please select a lead');  if(f==1){return f; }  }  
if(f==0){f=common_error('team','Please select a team');  if(f==1){return f; }  } 
if(f==0){f=common_error('startdate','Please enter start date');  if(f==1){return f; }  }  
if(f==0){f=common_error('enddate','Please enter end date');  if(f==1){return f; }  } 
}
function passid(str1,str2){
//alert(str2);
if (str1=="")
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
xmlhttp.open("GET","bio_approvalDetails.php?p=" + str1 + "&q=" + str2,true);
xmlhttp.send(); 
}
function enterreason(str1){  
//var f=0;
//var p=0; 
//    
// if(f==0)  {
// f=common_error('reason','Please enter the reason');  if(f==1){return f; } 
// }     
//alert(str1);
if (str1!=2)
  {
  document.getElementById("reject").innerHTML="";
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
    document.getElementById("reject").innerHTML=xmlhttp.responseText;  
    }
  } 
xmlhttp.open("GET","bio_rejectedtask.php?p=" + str1,true);
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

function modify(lead,modify,proposal,task)  {
//      alert(lead);     alert(modify);      alert(proposal);
//document.getElementById('leadgrid').style.display="none";
location.href = "bio_approval.php?lead=" + lead + "&modify=" + modify + "&proposal=" + proposal + "&task=" +task;
}

function removeitm(lead,item,remove)  {
 //   alert(first);

location.href = "bio_approval.php?stockid1=" + item + "&lead=" + lead + "&remove=" + remove;  
}

function selectplant(str){
    var str2=5;             // if from approval page  - first=5
controlWindow=window.open("bio_selectplantfor_cp.php?lead="+str+"&first="+str2 ,"selplant","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=700,height=400");
}

function addSubsidy(lead,item)  {
    var str3=3;
  controlWindow=window.open("bio_biocpaddsubsidy.php?ledid="+lead+"&item="+item+"&approval="+str3,"addsubsidy","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=700,height=400");
}

</script>