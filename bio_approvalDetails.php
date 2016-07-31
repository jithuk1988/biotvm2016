<?php
  $PageSecurity = 80;
  include('includes/session.inc');
 include('includes/bio_GetPrice.inc'); 
  $leadid=$_GET['p'];
  $id=$_GET['q'];
  $sql="SELECT
    `bio_cust`.`custname`
    , `bio_cust`.`custmob`
    , `bio_cust`.`custphone`
    , `bio_cust`.`area1`
    , `bio_cust`.`custmail`
    , `bio_status`.`biostatus`
    , `bio_district`.`district`
    , `bio_leads`.`remarks`
FROM
    `bio_cust`
    LEFT JOIN `bio_district` 
        ON (`bio_cust`.`nationality` = `bio_district`.`cid`) AND (`bio_cust`.`state` = `bio_district`.`stateid`) AND (`bio_cust`.`district` = `bio_district`.`did`)
    LEFT JOIN `bio_leads` 
        ON (`bio_leads`.`cust_id` = `bio_cust`.`cust_id`)
    LEFT JOIN `bio_status` 
        ON (`bio_leads`.`status` = `bio_status`.`statusid`) Where bio_leads.leadid=".$leadid;
 $result=DB_query($sql,$db);
 $myrow=DB_fetch_array($result); 
 $no=0; 
 $k=0; 
 $cname=$myrow['custname'];  
 if($myrow['custphone']!='-'){
     $cph=$myrow['custphone']; 
 }else{
     $cph=$myrow['custmob']; 
 }
 $email=$myrow['custmail']; 
 $place=$myrow['area1'];
 $dist=$myrow['district'];
 $ste=$myrow['state'];
 $ctry=$myrow['nationality']; 
 $status=$myrow['leadstatus'];
 $remark=$myrow['remarks'];        
 


      
 /*$sql1="SELECT bio_district.district 
          FROM bio_district
          WHERE bio_district.stateid=".$ste."  
          AND bio_district.cid=".$ctry."
          AND bio_district.did=".$dist;     
 $result1=DB_query($sql1,$db);
 $myrow=DB_fetch_array($result1);
 $district=$myrow[0];    */ 
echo '<table><tr>';
echo'<td valign=top>';
echo"<div id=cus_details>";                       
echo"<fieldset style='width:800px;height:auto'><legend>Customer Details</legend>";
echo"<table width=100%>";
echo"<tr><td width=50%>Customer Name:</td>";
echo"<td><input type='text' name='custname' id='custname' value='$cname'></td></tr>";
echo"<tr></tr>";
echo"<tr><td>Customer Phone:</td>";
echo"<td><input type='text' name='custph' id='custph' value='$cph'></td></tr>";
echo"<tr></tr>";
echo"<tr><td>Customer email</td>";
echo"<td><input type='text' name='email' id='email' value='$email'></td></tr>";
echo"<tr></tr>";
echo"<tr><td>Customer Place:</td>";
echo"<td><input type='text' name='custplace' id='custplace' value='$place'></td></tr>";
echo"<tr></tr>";
echo"<tr><td>Customer District:</td>";
echo"<td><input type='text' name='custdist' id='custdist' value='$district'></td></tr>";
echo"<tr></tr>";


//echo"<tr><td>Remark:</td>";
//echo"<td><input type='text' name='Remark' id='remark' value='$remark'></td></tr>";
//echo"<tr></tr>";



echo"</table>";
echo"</fieldset>"; 
echo"<input type='hidden' name='status' id='status' value='$status'>";  
echo'</div>';

echo"</td>"; 
 
echo"</tr><tr>";

echo'<td>';    
echo'<div id=right_panel_1 >';
echo"<fieldset style='width:785px; overflow:auto;'>";
echo"<legend>Proposal Details</legend>";
//echo'<div style="height:140px;overflow:scroll">';
echo"<table style='width:85%'>";  
//------------------------------------------------------------------------------    
/*echo"<tr style='background:#D50000;color:white'>";
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
  //<td><a style='cursor:pointer;color:white;' id='$myrow[1]' onclick='editfeedstok(this.id)'>Edit</a ></td>
//        <td><a style='cursor:pointer;color:white' id='$myrow[1]' onclick='deletfeedstok(this.id)'>Delete</a></td></tr>
}
echo"</table>";

//echo"</td><tr><td>";
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
echo"<tr></tr>";              */
//echo"</td></tr></table>";
 
            $sql="SELECT taskid,proposal_no FROM bio_approval WHERE tid =$id";
            $result=DB_query($sql,$db);
            $row=DB_fetch_array($result);
            $task=$row['taskid'];
            $proposalno=$row['proposal_no'];
        if($task==6)
        {
               $sql1="SELECT * FROM bio_proposaldetails WHERE propid=$proposalno";
               $result1=DB_query($sql1,$db);
               echo"<tr><td colspan=2>";
                   echo"<table border=1>";
                   echo"<tr><th>Slno</th><th>Item</th><th>New Price</th><th>Std Price</th></tr>";
                   $slno=1;
               while($row1=DB_fetch_array($result1)){
                   $std_price=GetPrice($row1['stockid'],$db);
                   
                   echo"<tr><td>".$slno."</td>";
                   echo"<td>".$row1['description']."</td>";
                   echo"<td>".$row1['price']."</td>";
                   echo"<td>".$std_price."</td>";
                   $slno++;
               }
               echo"</table>";
               
               echo'<br>';  
                 
              echo"<legend>Subsidy Description</legend>";
               echo'<br>';
              echo'<div>';      
              echo"<table width=100%>"; 
              echo"<tr><th>Slno</th><th>Item name</th><th>Scheme</th><th>Amount</th></tr>";     
               
                 
             $sql_item="SELECT bio_propsubsidy.scheme,
                               bio_propsubsidy.amount,
                               stockmaster.longdescription,
                               bio_schemes.scheme
                          FROM bio_propsubsidy,
                               stockmaster,
                               bio_schemes 
                         WHERE bio_propsubsidy.scheme=bio_schemes.schemeid     
                          AND  bio_propsubsidy.stockid=stockmaster.stockid
                          AND  bio_propsubsidy.propid=$proposalno
                          AND bio_propsubsidy.leadid=".$leadid;
               
                 $result_item=DB_query($sql_item,$db); 
                        $slno=1;
             while($myrow=DB_fetch_array($result_item,$db)) { 
             
                 $scheme=$myrow['scheme'];  
             
             
                   echo"<tr><td align=center>".$slno."</td>";
                   echo"<td align=center>".$myrow['longdescription']."</td>";
                   echo"<td align=center>".$scheme."</td>";
                   echo"<td align=center>".$myrow['amount']."</td>";
                        
                      $slno++;
               }    
               
               echo"</table>";   
               
               
               
               echo"</tr></td>";      
                
        }
        elseif($task==7)
        {
             $sql1="SELECT feasibilitystudy_charge FROM bio_feasibilitystudy WHERE leadid=$leadid";
             $result1=DB_query($sql1,$db);
             $row1=DB_fetch_array($result1);
             echo"<tr><td>Amount</td><td><input type='hidden' name='amount' id='amount' value=".$row1['feasibilitystudy_charge'].">".$row1['feasibilitystudy_charge']."</td></tr>";        
        }
        elseif($task==9)
        {
             $sql1="SELECT cp_charge FROM bio_conceptproposal WHERE lead_id=".$leadid;
             $result1=DB_query($sql1,$db);
             $row1=DB_fetch_array($result1);
             echo"<tr><td>Amount</td><td><input type='text' name='amount' id='amount' value=".$row1['cp_charge']."></td></tr>";        
        }
        elseif($task==10)
        {
            
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
  //<td><a style='cursor:pointer;color:white;' id='$myrow[1]' onclick='editfeedstok(this.id)'>Edit</a ></td>
//        <td><a style='cursor:pointer;color:white' id='$myrow[1]' onclick='deletfeedstok(this.id)'>Delete</a></td></tr>
}
//echo"</table>";

//echo"</td><tr><td>";
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
echo"<td>:<input type='text' name='TotalGas' id='totalgas' value='".$myrow_fsedit['total_gas']."' onchange=edit_gasout(this.value,$leadid);></td></tr>";
echo"</table>";
echo"<tr></tr>";              
            
              $sql_proptmp="DELETE FROM bio_temppropitems WHERE leadid=".$leadid;
              DB_query($sql_proptmp,$db);
            
              $sql_select="SELECT * FROM bio_conceptproposaldetails WHERE cp_id =$proposalno";
              $result_select=DB_query($sql_select,$db);
              while($row_select=DB_fetch_array($result_select))                     
              {
               $sql_sub="SELECT SUM(amount) FROM bio_cpsubsidy WHERE cp_id='".$row_select['cp_id']."'
                                                                 AND leadid=$leadid
                                                                 AND stockid='".$row_select['stockid']."'";
               $result_sub=DB_query($sql_sub,$db);
               $myrow_sub=DB_fetch_array($result_sub);
               if($myrow_sub[0]>0){
          
                  $subsidy=$myrow_sub[0];
                  $netprice=$row_select['tprice']-$subsidy;
                  }else{
                  $subsidy=0;
                  $netprice=$row_select['tprice'];
                      
                  }
                $sql_insert="INSERT INTO bio_temppropitems (stockid,
                                                            description,
                                                            qty,
                                                            price,
                                                            tprice,
                                                            leadid,
                                                            subsidy,
                                                            netprice) 
                                                    VALUES ('".$row_select['stockid']."',
                                                            '".$row_select['description']."',
                                                            '".$row_select['qty']."',
                                                            '".$row_select['price']."',
                                                            '".$row_select['tprice']."',
                                                            ".$leadid.",
                                                            ".$subsidy.",
                                                            ".$netprice.")";
                                                            
               DB_query($sql_insert,$db);                                             
              }
              
              $sql_temp="SELECT * FROM bio_temppropitems WHERE leadid =$leadid";
              $result_temp=DB_query($sql_temp,$db);
              $sql_nettotal="select sum(netprice) from bio_temppropitems where leadid=$leadid";
              $result_nettotal=DB_query($sql_nettotal,$db);
              $row_nettotal=DB_fetch_array($result_nettotal);
              $nettotal=$row_nettotal[0];
              echo"<tr><td colspan=2>";
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
                   <td><input size=3 style=\"text-align: right\" type=\"text\" name=\"qty".$k."\" id=\"qty".$k."\" value=".$myrow_temp['qty']." onchange=updatetotalitemprice(".$k.",".$leadid.",\"$stock[$k]\") readonly></td>
                   <td class=number><input size=9 style=\"text-align: right\" type=\"text\" name=\"price".$k."\" id=\"price".$k."\" value=".$myrow_temp['price']." onchange=updatetotalitemprice(".$k.",".$leadid.",\"$stock[$k]\") readonly></td>
                   <td class=number><input size=9 style=\"text-align: right\" type=\"text\" name=\"tprice".$k."\" id=\"tprice".$k."\" value=".$tprice." readonly></td>
                   <td class=number><input size=9 style=\"text-align: right\" type=\"text\" name=\"subsidy".$k."\" id=\"subsidy".$k."\" value=".$myrow_temp['subsidy']." readonly></td>
                   <td class=number><input size=9 style=\"text-align: right\" type=\"text\" name=\"netprice".$k."\" id=\"netprice".$k."\" value=".$myrow_temp['netprice']." readonly></td>
                   
                   
                   </tr>";      
                //  <td align=center><a  style='cursor:pointer;'  id='$leadid' onclick='addSubsidy($leadid,\"$item[$k]\",2,1)'>" . _('Manage Subsidy') . "</a></td>
                //  <td align=center><a  style='cursor:pointer;'  id='$leadid' onclick='removeitm($leadid,\"$item[$k]\",2)'>" . _('Remove') . "</a></td>  
               }
               echo"<tr><td colspan=2>Total Amount</td><td align='right' colspan=5><b>$nettotal</b></td></tr>";
               echo "<tr><td colspan=2>
                     <input type='button' id=\"".$leadid."\" value='Modify'  onclick='modify(this.id,2,$proposalno,$task);'>
                     </td></tr>";
               echo"</table>"; 
               echo"</td></tr>";
               
$sql_spdisc="SELECT amount FROM bio_cpsubsidy WHERE leadid=$leadid AND cp_id=$proposalno AND scheme=3 ";
$result_spdisc=DB_query($sql_spdisc,$db);
$row_spdisc=DB_fetch_array($result_spdisc);
if($row_spdisc['amount']!=''){               
               echo"<tr><td><b>Special Discount Given</b>: ".$row_spdisc['amount']."</td></tr>";
}

 

            }
            elseif($task==11)
            {
                     $sql1="SELECT plant FROM bio_conceptproposal WHERE lead_id=$leadid";
                     $result1=DB_query($sql1,$db);
                     $row1=DB_fetch_array($result1);
                            $plantid=$row1['plant'];
                    $plantid2=explode(',',$plantid);
                    $n=sizeof($plantid2);  
                    echo"<tr><td>Plant</td>";
                    for($i=0;$i<$n;$i++)        { 
                    $sql2="SELECT longdescription FROM stockmaster WHERE stockid = '".$plantid2[$i]."'";
                    $result2=DB_query($sql2,$db);
                    $row2=DB_fetch_array($result2);
                    $description=$row2['longdescription'];   
                    echo"<td><input type='hidden' name='plant' id='plant' value=".$description.">$description</td></tr>";  
                    echo"<td></td>";
            }         
            }
            elseif($task==16)
            {
                     $sqlr="SELECT value
                            FROM   bio_changepolicy
                             WHERE  policyname ='Institution FS Charge'";    
                                $resulte=DB_query($sqlr, $db);     
                                $mr=DB_fetch_array($resulte);          
                      $fs_amount=$mr[0];      
                     echo"<tr><td>Actual FS charge</td><td>:<input type='hidden' name='actamount' id='actamount' value=".$fs_amount.">".$fs_amount."</td></tr>";   
                     $sql1="SELECT fp_amount FROM bio_fsproposal WHERE leadid=$leadid AND fs_propono=$proposalno";
                     $result1=DB_query($sql1,$db);
                     $row1=DB_fetch_array($result1);
                     echo"<tr><td>New FS charge</td><td>:<input type='hidden' name='amount' id='amount' value=".$row1['fp_amount'].">".$row1['fp_amount']."</td></tr>";           
            }


//---------------------------------------------------------------------------------------------------------------
echo"<tr></tr>";
echo"<tr></tr>";
echo"<tr></tr>";

echo"<tr><td width='300px'>Change Status</td>";
echo"<td><select name='app_status' id='app_status' style='width:190px' onchange='enterreason(this.value)' >";
$sql="SELECT * FROM bio_approvalstatus";
$result=DB_query($sql,$db);
$f=0;
  while($myrow1=DB_fetch_array($result))
  {  
  if ($myrow1['app_statusid']==$_POST['app_status'])  
    {   
    echo '<option selected value="';
    } else 
    {
    if ($f==0) 
        {
        echo '<option>';
        }
        echo '<option value="';
    }
    echo $myrow1['app_statusid'] . '">'.$myrow1['app_status'];
    echo '</option>';
    $f++;
   }   
echo"</select></td>";
//echo"<td><input type='button' value='Modify' onclick='modify();'></td>";
echo"</tr>";

echo '<tr id=reject><tr>';
echo"</table>";
//echo"</div>";
echo"</fieldset>";
echo"</div>";
echo"</td></tr></table>";
echo"<input type='hidden' name='TaskID' id='taskid' value='$task'>";  
echo"<input type='hidden' name='LeadID' id='leadid' value='$leadid'>";
echo"<input type='hidden' name='ProposalID' id='proposalid' value='$proposalno'>";

?>
