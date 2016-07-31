<?php
  $PageSecurity = 80;
  include('includes/session.inc');
 include('includes/bio_GetPrice.inc'); 
  $leadid=$_GET['p'];
  $id=$_GET['q'];
  $sql="SELECT  bio_cust.custname,
                    bio_cust.custphone,
                    bio_cust.custmob,
                    bio_cust.custmail,
                    bio_cust.area1,
                    bio_leads.remarks,
                    bio_cust.district,
                    bio_cust.state,
                    bio_cust.nationality,
                    bio_leads.leadstatus  
              FROM  bio_leads,bio_cust
              WHERE bio_leads.cust_id=bio_cust.cust_id
              AND bio_leads.leadid=".$leadid;
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
 $sql1="SELECT bio_district.district 
          FROM bio_district
          WHERE bio_district.stateid=".$ste."  
          AND bio_district.cid=".$ctry."
          AND bio_district.did=".$dist;     
 $result1=DB_query($sql1,$db);
 $myrow=DB_fetch_array($result1);
 $district=$myrow[0];     
echo '<table><tr>';
echo'<td valign=top>';
echo"<div id=cus_details>";                       
echo"<fieldset style='width:400px;height:150px'><legend>Customer Details</legend>";
echo"<table width=100%>";
echo"<tr><td width=50%>Customer Name:</td>";
echo"<td><input type='hidden' name='custname' id='custname' value='$cname'>$cname</td></tr>";
echo"<tr></tr>";
echo"<tr><td>Customer Phone:</td>";
echo"<td><input type='hidden' name='custph' id='custph' value='$cph'>$cph</td></tr>";
echo"<tr></tr>";
echo"<tr><td>Customer email</td>";
echo"<td><input type='hidden' name='email' id='email' value='$email'>$email</td></tr>";
echo"<tr></tr>";
echo"<tr><td>Customer Place:</td>";
echo"<td><input type='text' name='custplace' id='custplace' value='$place'></td></tr>";
echo"<tr></tr>";
echo"<tr><td>Customer District:</td>";
echo"<td><input type='text' name='custdist' id='custdist' value='$district'></td></tr>";
echo"<tr></tr>";
echo"<tr><td>Remark:</td>";
echo"<td><input type='hidden' name='Remark' id='remark' value='$remark'></td></tr>";
echo"<tr></tr>";



echo"</table>";
echo"</fieldset>"; 
echo"<input type='hidden' name='status' id='status' value='$status'>";  
echo'</div>';
echo"</td>";  
echo'<td valign=top>';    
echo'<div id=right_panel_1 >';
echo"<fieldset style='width:400px;height:150px; overflow:auto;'>";
echo"<legend>Proposal Details</legend>";
echo'<div style="height:140px;overflow:scroll">';
echo"<table style='width:380px'>";
//------------------------------------------------------------------------------
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
  $sql7="SELECT * FROM bio_conceptproposaldetails WHERE cp_id =$proposalno";
  $result7=DB_query($sql7,$db);
echo"<tr><td colspan=2>";
echo"<table border=1>";
       echo"<tr><th>Slno</th><th>Item</th><th>New Price</th><th>Std Price</th></tr>";
       $slno=1;
   while($row5=DB_fetch_array($result7)){
       $std_amount=GetPrice($row5['stockid'],$db);
       
       echo"<tr><td>".$slno."</td>";
       echo"<td>".$row5['description']."</td>";
       echo"<td>".$row5['price']."</td>";
       echo"<td>".$std_amount."</td>";
       $slno++;
   }
   echo"</table>";
   
   echo'<br>';  

echo"<legend>Subsidy Description</legend>";
echo'<br>';
echo"<tr><td colspan=2>";
echo"<table border=1>";
echo"<tr><th>Slno</th><th>Item name</th><th>Scheme</th><th>Amount</th></tr>";     
$sql_item="SELECT bio_cpsubsidy.scheme     ,
                   bio_cpsubsidy.amount,
                   stockmaster.longdescription,
                   bio_schemes.scheme
              FROM bio_cpsubsidy,
                   stockmaster,
                   bio_schemes 
             WHERE bio_cpsubsidy.scheme=bio_schemes.schemeid     
              AND  bio_cpsubsidy.stockid=stockmaster.stockid
              AND  bio_cpsubsidy.cp_id=$proposalno
              AND bio_cpsubsidy.leadid=".$leadid;
   
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
 $sql1="SELECT fp_amount FROM bio_fsproposal WHERE leadid=$leadid";
 $result1=DB_query($sql1,$db);
 $row1=DB_fetch_array($result1);
 echo"<tr><td>New FS charge</td><td>:<input type='hidden' name='amount' id='amount' value=".$row1['fp_amount'].">".$row1['fp_amount']."</td></tr>";           
}


//---------------------------------------------------------------------------------------------------------------
echo"<tr></tr>";
echo"<tr></tr>";
echo"<tr></tr>";
echo"<tr></tr>";
echo"<tr><td>Change Status</td>";
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
echo"</select></td></tr>";
echo '<tr id=reject><tr>';
echo"</table>";
echo"</div>";
echo"</fieldset>";
echo"</div>";
echo"</td></tr></table>";
echo"<input type='hidden' name='TaskID' id='taskid' value='$task'>";  
echo"<input type='hidden' name='LeadID' id='leadid' value='$leadid'>";
echo"<input type='hidden' name='ProposalID' id='proposalid' value='$proposalno'>";
?>
