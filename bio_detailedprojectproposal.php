<?php
 $PageSecurity = 80;
include('includes/session.inc');
$title = _('Detailed Project Proposal'); 
$pagetype=3; 
include('includes/header.inc');
include('includes/sidemenu.php');
echo '<center><font style="color: #333;
    background:#fff;
    font-weight:bold;
    letter-spacing:0.10em;
    font-size:16px;
    font-family:Georgia;
    text-shadow: 1px 1px 1px #666;">DETAILED PROJECT PROPOSAL</font></center>';
    

$office=$_SESSION['UserStockLocation'];


if(!isset($_POST['submit'])){ 

$tempdrop="DROP TABLE IF EXISTS bio_subassemblytemp";

DB_query($tempdrop,$db);


$temptable="CREATE TABLE bio_subassemblytemp (
temp_id INT NOT NULL AUTO_INCREMENT ,
stockid VARCHAR(50) NULL ,
quantity DECIMAL NULL ,
price DECIMAL NULL ,
PRIMARY KEY ( temp_id )
)";
DB_query($temptable,$db);

$sql="ALTER TABLE `bio_subassemblytemp` 
      ADD `status` INT 
                     NOT 
                   NULL 
                   DEFAULT 
                   '0'" ; 
DB_query($sql,$db);
}


if(isset($_POST['submit']))     {
    
    if(!isset($_POST['LeadID'])){
      prnMsg('Select lead details from the grid and then assign the team','warn');  
        
    }
    else{
        $subTotPrice="";
        $sql_temp="SELECT *
                        FROM bio_subassemblytemp";
        $result_temp=DB_query($sql_temp,$db);
        while($myrow_temp=DB_fetch_array($result_temp))     {
            $sassembly1=$myrow_temp['stockid'];
            $qty1=$myrow_temp['quantity'];
            $sql_price="SELECT price
                          FROM prices
                          WHERE stockid='".$sassembly1."'";
            $result_price=DB_query($sql_price,$db);
            $myrow_price=DB_fetch_array($result_price);
            $subPrice=$myrow_price['price'];
            $subTotPrice=$subTotPrice+($qty1*$subPrice);
        }
        
        $wages_operation=$_POST['Wages'];
        $AMCExpense=$_POST['AMCExpense'];
        $depreciation=$_POST['Depreciation'];
        if($_POST['Wages']==""){
            $wages_operation=0;
        }
        if($_POST['AMCExpense']==""){
            $AMCExpense=0;
        }
        if($_POST['Depreciation']==""){
            $depreciation=0;
        }
        $completiondate=FormatDateForSQL($_POST['CompletionDate']);
        
        $lead_status=$_POST['Status'];
        
        
        $currentdate=Date($_SESSION['DefaultDateFormat']);
        $proposal_date=FormatDateForSQL($currentdate);
        $cp_id=$_POST['CPID'];
        $lead_ID=$_POST['LeadID'];
        $plant_selected=$_POST['Plant'];
        $plant_price=$_POST['PlantsPrice'];
        $team_ID=$_POST['TeamID'];
        $dpr_charge=$_POST['AdvanceAmount'];
        $totalcost=$subTotPrice+$plant_price+$wages_operation+$AMCExpense;
    
        $user_ID=$_SESSION['UserID'];
        $office=$_SESSION['UserStockLocation'];
        $approved_by="";
        $signatory_by="";
    
    
        $payment_mode=$_POST['mode'];
        $receipt_no=$_POST['amtno'];
        $payment_bank=$_POST['amtbank'];
        $payment_date=$_POST['amtdate'];
        $payment_date1=FormatDateForSQL($payment_date);
        $InputError=0;
        $reference=$receipt_no.",".$payment_date.",".$payment_bank;
        
    
        $sql4="INSERT INTO bio_dpr(conceptproposal_id,
                                   leadid,
                                   plant,
                                   dpr_charge,
                                   wages,
                                   amcexpense,
                                   depreciation,
                                   totalcost,
                                   createdon,
                                   completiondate,
                                   team_id,
                                   created_by,
                                   approved_by,
                                   signatory_by,
                                   dpr_status)
                          VALUES(".$cp_id.",
                                 ".$lead_ID.",
                                 '".$plant_selected."',
                                 '".$dpr_charge."',
                                 '".$wages_operation."',
                                 '".$AMCExpense."',
                                 '".$depreciation."',
                                 '".$totalcost."',
                                 '".$proposal_date."',
                                 '".$completiondate."',
                                 '".$team_ID."',
                                 '".$user_ID."',
                                 '".$approved_by."',
                                 '".$signatory_by."',
                                 1)";
        

        $result4=DB_query($sql4,$db);
        $dpr_id=DB_Last_Insert_ID($Conn,'bio_dpr','dpr_id');
    
        $sql5="SELECT *
                FROM bio_subassemblytemp";
        $result5=DB_query($sql5,$db);
    
        while($myrow5=DB_fetch_array($result5))     {
            $sassembly=$myrow5['stockid'];
            $qty=$myrow5['quantity'];
            $price=$myrow5['price'];
            $sql6="INSERT INTO bio_dprsubassemblies(dpr_id,
                                                    subassembly,
                                                    quantity,
                                                    price)
                                           VALUES(".$dpr_id.",
                                                 '".$sassembly."',
                                                 ".$qty.",
                                                 ".$price.")";
            $result6=DB_query($sql6,$db);
        }
    
        if($payment_mode==1){
            $sql_payment="INSERT INTO `bio_advance` (`leadid`,
                                                     `mode_id`, 
                                                     `date`, 
                                                     `serialnum`,
                                                     `bankname`,
                                                     `amount`,
                                                     `officid`) 
                                             VALUES ('$lead_ID',
                                                   '".$payment_mode."',
                                                   '".$proposal_date."',
                                                   '',
                                                   '',
                                                   '".$dpr_charge."',
                                                   '".$office."')";
            $reference="";
        }elseif($payment_mode==2){
            $sql_payment="INSERT INTO `bio_advance` (`leadid`,
                                                     `mode_id`, 
                                                     `date`, 
                                                     `serialnum`,
                                                     `bankname`,
                                                     `amount`,
                                                     `officid`) 
                                             VALUES ('$lead_ID',
                                                   '".$payment_mode."',
                                                   '".$payment_date1."',
                                                   '".$receipt_no."',
                                                   '".$payment_bank."',
                                                   '".$dpr_charge."',
                                                   '".$office."')";     
        }elseif($payment_mode==3){
            $sql_payment="INSERT INTO `bio_advance` (`leadid`,
                                                     `mode_id`, 
                                                     `date`, 
                                                     `serialnum`,
                                                     `bankname`,
                                                     `amount`,
                                                     `officid`) 
                                             VALUES ('$lead_ID',
                                                   '".$payment_mode."',
                                                   '".$payment_date1."',
                                                   '".$receipt_no."',
                                                   '".$payment_bank."',
                                                   '".$dpr_charge."',
                                                   '".$office."')";
        }
        $result1_payment=DB_query($sql_payment, $db);
        /*
        $sql_cashbook="INSERT INTO bio_cashbook (date,
                                        head_id,
                                        leadid,
                                        amount,
                                        mode,
                                        reference)  
                            VALUES ('$proposal_date',
                                     2,
                                    '$lead_ID',
                                    '$FS_charge',
                                    '$payment_mode',
                                    '$reference') "; 
                            
         $result_cashbook = DB_query($sql_cashbook,$db);
       */
     
        $tempdrop="DROP TABLE IF EXISTS bio_subassemblytemp";
        DB_query($tempdrop,$db); 

        

        $temptable="CREATE TABLE bio_subassemblytemp (
                        temp_id INT NOT NULL AUTO_INCREMENT ,
                        stockid VARCHAR(50) NULL ,
                        quantity DECIMAL NULL ,
                        price DECIMAL NULL ,
                        PRIMARY KEY ( temp_id ))";
        DB_query($temptable,$db);

        $sql="ALTER TABLE `bio_subassemblytemp` 
                    ADD `status` INT 
                            NOT 
                            NULL 
                            DEFAULT 
                            '0'" ; 
        DB_query($sql,$db);
        $_SESSION['dprid']=$dpr_id;
        
       if($lead_status==4) 
    {
          $sql9="UPDATE bio_leads
                    SET leadstatus=13
                    WHERE leadid=".$lead_ID."";
        $result9=DB_query($sql9,$db);
        
        prnMsg('This Detailed Project Proposal is send to higher authority for approval','warn');
        print'<script>
          myRef = window.open("bio_dpr_approvalby.php","dprapproval","toolbar=yes,location=no,directories=no,status=yes,menubar=yes,scrollbars=yes,resizable=no,width=600,height=400");
          </script> ';
        
        
        // print'<script>
//            var answer = confirm("Do you want to PRINT the Detailed Project proposal report?")
//            if (answer){
//            myRef = window.open("bio_dpr_coveringletter.php");
//               
//            }
//        </script> ';
    }
      else{
      $sql4="UPDATE bio_leads SET leadstatus=5 where leadid=".$lead_ID;
      $result6=DB_query($sql4,$db);  
      }
    }
}



echo"<div id=fullbody>";
echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "?" . SID . "'>"; 
echo '<table><tr><td>';
echo"<div id=panel>";
echo '<table><tr>';

//========================================== Left Panel Begins

echo'<td>';
echo"<div id=CPdetails>";
echo"<fieldset style='width:335px;height:225px'>"; 
echo"<legend>Customer Details</legend>";
echo"</legend>";
echo"<table width=100%>";

echo"<tr><td width=50%>Customer Name</td>";
echo"<td><input type='hidden' name='custname' id='custname' value='$cname'></td></tr>";
echo"<tr></tr>";
echo"<tr><td>Assigned Team:</td>";
echo"<td><input type='hidden' name='asgnteam' id='asgnteam' value='$asgnteam'></td></tr>";
echo"<tr></tr>";
echo"<tr><td>Output Type:</td>";
echo"<td><input type='hidden' name='OutputType' id='outputtype' value='$outputtype'></td></tr>";
echo"<tr></tr>";
echo"<tr><td width=50%>Plants:</td>";
echo"<td><input type='hidden' name='Plant' id='plant' value='$plants'></td></tr>";
echo"<tr></tr>";
echo"<tr><td width=50%>Plants Price</td>";
echo"<td><input type='hidden' name='PlantsPrice' id='plantsprice' value='$plantprice'></td></tr>";
echo"<tr></tr>";
echo"</table>";
echo"</fieldset>";
echo'</div>';
echo"</td>";
//========================================== Left Panel Ends 

//========================================== Right Panel Begins
echo'<td valign=top>';    
echo'<div id=right_panel_1>';
echo"<fieldset style='width:335px;height:225px; overflow:auto;'>";
echo"<legend>Add Subassemblies</legend>";
echo"<table>";

//echo"<tr><td width=50%>Subassemblies*</td>";
//echo"<td id='subassemblies'><input type='hidden' name='SubAssemblyName' id='subassemblyname' value='no'>
//  <a onclick='showSubassemblies(this.id)'>Select</a></td></tr>";
$DateString = Date($_SESSION['DefaultDateFormat']); 

//echo"<tr><td>Start date*</td>";
//echo"<td><input type='text' name='StartDate' id='startdate' class=date alt='".$_SESSION['DefaultDateFormat']. "' style='width:150px'></td></tr>";

//echo"<tr><td>End date*</td>";
//echo"<td><input type='text' name='EndDate' id='enddate' class=date alt='".$_SESSION['DefaultDateFormat']. "' style='width:150px'></td></tr>";

echo"<tr><td width=50%>DPR Team</td>";
echo"<td><input type='hidden' name='fteam' id='fteam' style='width:154px'></td></tr>";

//echo"<tr><td>Wages for Operation*</td>";
echo"<td><input type='hidden' name='Wages' id='wages' style='width:150px'></td></tr>";
//echo"<tr><td>AMC Expenses*</td>";
echo"<td><input type='hidden' name='AMCExpense' id='amcexpense' style='width:150px'></td></tr>";
//echo"<tr><td>Depreciation*</td>";
echo"<td><input type='hidden' name='Depreciation' id='depreciation' style='width:150px'></td></tr>";
echo"<tr><td>Completion Date*</td>";
echo"<td><input type='text' name='CompletionDate' id='completiondate' class=date alt='".$_SESSION['DefaultDateFormat']. "' style='width:150px'></td></tr>";

echo"<tr><td>Advance Amount*</td>";
echo"<td><input type='text' name='AdvanceAmount' id='advanceamount' style='width:150px'></td></tr>";

echo'<tr>';
echo'<td width=50%>Mode of payment*</td>';
echo'<td><select name="mode" id="paymentmode" style="width:150px" onchange="advdetail(this.value)">';
$sql1="SELECT * FROM bio_paymentmodes";
$result1=DB_query($sql1, $db); 
$f=0;
while($myrow1=DB_fetch_array($result1))
{
    if ($myrow1['id']==$_POST['modes'])
    {
        echo '<option selected value="';
    } else 
    {
        if ($f==0) 
        {
            echo '<option>';
        }
        echo '<option value="';
        $f++;
    }
    echo $myrow1['id'] . '">'.$myrow1['modes'];  
    echo '</option>';
  }
echo'</select></td></tr>';


echo"</table>";
echo"<table id='modeamt'>";
echo"</table>"; 
echo"<table id='amt'>";
echo"</table>";
echo"</fieldset>";
echo'</div>';
echo"</td></tr></table>";
echo"</div>"; 

//========================================== 


echo"<table>";
echo'<tr><td colspan=2>';
echo'<div id="subassembly_select">';

echo "<fieldset style='width:690px'>";   
   echo "<legend><h3>Sub Assembly Details</h3>";
    echo "</legend>"; 
  echo "<table style='align:left' border=0>";
  echo "<tr><td>Sub Assemblies</td>";
//Feedstock
    echo '<td>';
  $sql_cat="SELECT subcatid FROM bio_maincat_subcat WHERE maincatid=10";
$result_cat=DB_query($sql_cat,$db);
$cat_arr=array();
while($row_cat=DB_fetch_array($result_cat)) 
{  
  $cat_arr[]="'".$row_cat['subcatid']."'";
  $optitem_array=join(",", $cat_arr); 
}  
    
$sql1="SELECT * 
      FROM stockmaster
WHERE stockmaster.categoryid IN ($optitem_array)";     
  
  $result1=DB_query($sql1, $db);
  echo '<select name="SubAssembly" id="subid1" style="width:175px">';
  $f=0;
  while($myrow1=DB_fetch_array($result1))
  {  
  if ($myrow1['stockid']==$_POST['SubAssembly']) 
    {
    echo '<option selected value="';
    
    } else {
        if($f==0){echo '<option>';   }
        echo '<option value="';
    }
    echo $myrow1['stockid'] . '">'.$myrow1['description']; 
    echo '</option>' ;
   $f++; 
   }
  echo '</select>';
  echo "</td>";
  echo "<td>Quantity</td>";
  echo "<td><input type=text name='Quantity' id='quantity1' style='width:95%'></td>";
  
  echo "<td>Price</td>";
  echo "<td><input type=text name='Price' id='price1' style='width:95%'></td>";
  echo "<td>";
 echo '<input type="button" name="AddSubAssembly" id="addsubassembly" value="Add" onclick="addSubAssemblies()">';
  echo "</td>";
  
  echo "</tr>";
  
   
echo "</table>";

echo"<div id='editfdstok'></div>";

echo"<div id='subassemblydiv'></div>";
echo"</fieldset>"; 
echo"</td></tr>"; 


echo'</div>';
echo'</td></tr>';

//========================================== 


//========================================== Buttons 
echo'<table>';
echo'<tr><td colspan=2><p><div class="centre">
<input type=submit name=submit value="' . _('Assign') . '" onclick="if(log_in()==1)return false;">';
echo '<input name="clear" type="submit" value=Clear ><input id="shwprint" type="button" name="shwprint" value="view proposals" >';
//<input id="shwlead" type="button" name="shwlead" value="view leads" >

echo'</td></tr>';

echo'</div>'; 
echo"</td></tr></table>";
echo'</form>';

//========================================== Buttons Ends




//========================================== Grid for pending feasibilities
echo'<div id="leadgrid">';
echo"<fieldset style='width:690px'><legend>Lead Details</legend>";
echo "<div style='height:200px; overflow:scroll;'>";   
echo"<table style='width:100%'> ";
echo"<tr><th>Slno</th><th>Name</th><th>Date</th><th>Output</th><th>Cust type</th><th>Team</th></tr>";
$office=$_SESSION['UserStockLocation'];
 $empid=$_SESSION['empid'];
  
        $sql_team="SELECT teamid FROM bio_teammembers WHERE empid=".$empid;
        $result_team=DB_query($sql_team,$db);
        $row_team=DB_fetch_array($result_team);
        $assignedfrm=$row_team['teamid'];
   
 $sql="SELECT bio_leads.leadid, 
             bio_leads.leaddate, 
             bio_enquirytypes.enquirytype, 
             bio_outputtypes.outputtype, 
             bio_leadteams.teamname, 
             bio_cust.custname,
            bio_office.id AS officeid,
  bio_office.office AS office 
        FROM bio_cust, 
             bio_leads, 
             bio_leadteams, 
             bio_leadsources, 
             bio_enquirytypes, 
             bio_outputtypes, 
             bio_conceptproposal,
             bio_office
       WHERE bio_cust.cust_id = bio_leads.cust_id
         AND bio_leadteams.teamid = ".$assignedfrm."
         AND bio_leads.enqtypeid = bio_enquirytypes.enqtypeid
         AND bio_leadsources.id = bio_leads.sourceid
         AND bio_leads.enqtypeid =2
         AND bio_outputtypes.outputtypeid = bio_leads.outputtypeid
         AND bio_leadsources.officeid =bio_office.id
         AND bio_leads.leadid = bio_conceptproposal.lead_id
         AND (bio_leads.leadstatus = 4 OR  bio_leads.leadstatus = 25)";  
            
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
//                    $k=1;     
    }
    $leadid=$myrow[0];
    printf("<td cellpading=2>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td><a  style='cursor:pointer;'  id='$leadid' onclick='passid(this.id)'>" . _('Select') . "</a></td></tr>",
            $no,
            $myrow['custname'],
            ConvertSQLDate($myrow['leaddate']),
            $myrow[3],
            $myrow[2],
            $myrow[4],
            $myrow[4],
            $myrow[0]);
}
echo"</td>";
echo"</tr></tbody></table>";
echo"</div>";
echo"</fieldset>";
echo"</div>";
//========================================== Grid for Proposal Details

echo'<div id="proposalgrid">';
echo"<fieldset style='width:690px'><legend>Proposal Details</legend>"; 
echo "<div style='height:200px; width:100%; overflow:scroll;'>";   
 
echo"<table style='width:100%' border=0> ";   
echo"<tr><th>Slno</th>
<th>Name</th>
<th>Leadid</th>
<th>Plants</th>
<th>Date</th>
<th>Status</th></tr>";

//$sql8="SELECT bio_feasibilitystudy.feasibilitystudy_id,
//              bio_feasibilitystudy.leadid,
//              bio_feasibilitystudy.teamid,
//              bio_feasibilitystudy.feasibilitystudy_charge,
//              bio_feasibilitystudy.feasibilitystudy_startdate,
//              bio_feasibilitystudy.feasibilitystudy_enddate,
//              bio_leads.cust_id,
//              bio_cust.custname,
//              bio_leadteams.teamname
//         FROM bio_feasibilitystudy,bio_leads,bio_cust,bio_leadteams
//         WHERE bio_feasibilitystudy.leadid=bio_leads.leadid
//         AND bio_leads.cust_id=bio_cust.cust_id
//         AND bio_leadteams.teamid=bio_leads.teamid";  

echo $sql8="SELECT bio_dpr.leadid,
              bio_dpr.dpr_id,
              bio_dpr.totalcost,
              bio_dpr.createdon,
              bio_conceptproposal.plant,
              bio_dpr.createdon,
              bio_leads.cust_id,
              bio_cust.custname,
              bio_proposal_status.status,
              bio_dpr.dpr_status
         FROM bio_dpr,
              bio_conceptproposal,
              bio_leads,
              bio_cust,
              bio_proposal_status
        WHERE bio_dpr.leadid=bio_leads.leadid    
          AND bio_leads.cust_id=bio_cust.cust_id
          AND bio_proposal_status.statusid=bio_dpr.dpr_status
          AND bio_conceptproposal.lead_id = bio_dpr.leadid";
          
$result8=DB_query($sql8,$db);
$k=0;
$no=0;
while($myrow8=DB_fetch_array($result8))     {
    $fsid=$myrow8['feasibility_id'];
    $cpid=$myrow8['conceptproposal_id'];    
    $plantid=$myrow8['plant'];
    $dprID=$myrow8['dpr_id'];
    $created_on=ConvertSQLDate($myrow8['createdon']);
    
    $plantid2=explode(',',$plantid);
    $n=sizeof($plantid2);
    $plants="";

    for($i=0;$i<$n;$i++)        {
        $sql="SELECT description
                FROM stockmaster
                WHERE stockid='$plantid2[$i]'";
        $result=DB_query($sql,$db);
        $myrow=DB_fetch_array($result);
        $plants=$myrow[0].",".$plants;
    }
    $plants = substr($plants,0,-1);     
    
    $sql_subas="SELECT subassembly
                  FROM bio_dprsubassemblies
                  WHERE dpr_id='$dprID'";
    $result_subas=DB_query($sql_subas,$db);
    $subas="";
    while($myrow_subas=DB_fetch_array($result_subas)){
        $subassembly1=$myrow_subas['subassembly'];
        $subassembly2=explode(',',$subassembly1);
        $n=sizeof($subassembly2);
        
        for($i=0;$i<$n;$i++)        {
            $sql="SELECT description
                    FROM stockmaster
                    WHERE stockid='$subassembly2[$i]'";
            $result=DB_query($sql,$db);
            $myrow=DB_fetch_array($result);
            $subas=$myrow[0].",".$subas;
        }
    }
    $subas = substr($subas,0,-1);
    if ($k==1){
        echo '<tr class="EvenTableRows">';
        $k=0;
    }else {
        echo '<tr class="OddTableRows">';
        $k=1;
    } 
    $no++;

//======================================================================================      
$sql_size="SELECT stockmaster.stockid, 
                   stockmaster.description, 
                   stockcatproperties.label, 
                   stockitemproperties.value
              FROM stockmaster, stockcatproperties, stockitemproperties
             WHERE stockmaster.stockid = stockitemproperties.stockid
               AND stockmaster.categoryid = stockcatproperties.categoryid
               AND stockcatproperties.stkcatpropid = stockitemproperties.stkcatpropid
               AND stockmaster.stockid = '".$plantid2[0]."'
               AND stockcatproperties.label='Size(Cubic meter)'";
  $result_size=DB_query($sql_size,$db);
  $myrow_size=DB_fetch_array($result_size);
  $plant_size=$myrow_size['value'];

//======================================================================================  
    
echo"<td cellpading=2>".$no."</td>
         <td>".$myrow8['custname']."</td>
         <td>".$myrow8['leadid']."</td>
         <td>".$plants."</td>
         <td>".ConvertSQLDate($myrow8['createdon'])."</td>
         <td>".$myrow8['status']."</td>";
         
    if($myrow8['dpr_status']==1){     
        echo"<td></td>
             <td><a  style='cursor:pointer;'  onclick=DprEdit('$dprID','$leadid')>" . _('Edit') . "</a></td></tr>";
   }
   elseif($myrow8['dpr_status']==2){
       echo"<td></td>
             <td><a  style='cursor:pointer;'  onclick=DprEdit('$dprID','$leadid')>" . _('Select') . "</a></td></tr>";
   }
   elseif($myrow8['dpr_status']==3){
       echo"<td></td>
             <td><a  style='cursor:pointer;'  onclick=DprEdit('$dprID','$leadid')>" . _('Edit') . "</a></td></tr>";
   }
   elseif($myrow8['dpr_status']==4){
       echo"<td><a  style='cursor:pointer;'  onclick=DprPrint('$dprID','$leadid','$plant_size')>" . _('Print') . "</a></td>
            <td><a  style='cursor:pointer;'  onclick=DprEdit('$dprID','$leadid')>" . _('Edit') . "</a></td></tr>";


}
}   
 //printf("<td cellpading=2>%s</td>
//            <td>%s</td>
//            <td>%s</td>
//            <td>%s</td>
//            <td>%s</td>
//            <td>%s</td>
//            <td>%s</td>
//            <td><a  style='cursor:pointer;'  onclick=DPREdit('$dprID','$cpid','$fsid')>" . _('Edit') . "</a></td></tr>",
//            $no,
//            $myrow8['custname'],
//            $myrow8['leadid'],
//            $plants,
//            $subas,
//            $myrow8['totalcost'],
//            $created_on);


echo '<tbody>';
echo"</tr></tbody>
</table>";
echo"</div>";
echo"</fieldset>";  
echo'</div>';
echo"</td></tr></table>"; 
echo"</div>";

?>
<script type="text/javascript">

$(document).ready(function() {
    $("#proposalgrid").hide();
    
    $('#shwprint').click(function() {
        $('#proposalgrid').slideToggle('slow',function(){});
       $('#leadgrid').slideToggle('slow',function(){});
    });
    //$('#shwlead').click(function() {
//        $('#leadgrid').slideToggle('slow',function(){});
//        $('#cpgrid').slideToggle('slow',function(){});
//    });
}); 


function log_in()
{
//    document.getElementById('phone').focus();
var f=0;
var p=0;
//if(f==0){f=common_error('wages','Please enter Wages for operation');  if(f==1){return f; } }  
//if(f==0){f=common_error('amcexpense','Please enter AMC Expenses');  if(f==1){return f; }  }  
//if(f==0){f=common_error('depreciation','Please enter Depreciation');  if(f==1){return f; }  }
if(f==0){f=common_error('completiondate','Please enter Completion date');  if(f==1){return f; }  }
if(f==0){f=common_error('advanceamount','Please enter Advance amount');  if(f==1){return f; }  }

if(f==0){var x=document.getElementById('advanceamount').value;  
            if(isNaN(x)||x.indexOf(" ")!=-1)
           {  f=1;
              alert("Please enter Numeric value for Advance amount"); document.getElementById('advanceamount').focus();
              if(x=""){f=0;}
              return f; 
           }
} 
if(f==0){f=common_error('paymentmode','Please select Payment Mode');  if(f==1){return f; }  }
if(f==0){f=common_error('amtdate','Please enter Payment Date');  if(f==1){return f; }  }
if(f==0){f=common_error('amtno','Please enter Payment Number');  if(f==1){return f; }  }
if(f==0){f=common_error('amtbank','Please enter Payment Bank');  if(f==1){return f; }  }

}

function passid(str1){
//alert(str1);
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
    document.getElementById('loc').focus(); 
    }
  } 
xmlhttp.open("GET","bio_CPdetailsforDPR.php?p=" + str1,true);
xmlhttp.send(); 
}

function advdetail(str){
if (str=="")
  {
  document.getElementById("amt").innerHTML="";
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
    document.getElementById("amt").innerHTML=xmlhttp.responseText;

    }
  } 
xmlhttp.open("GET","bio_fsamountdetails.php?adv=" + str,true);
xmlhttp.send();    
}




function getUnit(str1){

str1=document.getElementById("subassembly").value;
//alert(str1) ;
if (str1=="")
  {
  document.getElementById("getunit").innerHTML="";
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
    document.getElementById("getunit").innerHTML=xmlhttp.responseText;  
    document.getElementById('loc').focus(); 
    }
  } 
xmlhttp.open("GET","bio_dpr_get unit.php?p=" + str1,true);
xmlhttp.send(); 
}

function addSubAssemblies(str1,str2,str3)
{
    
    if(!str1){
              
        //alert("Select Sub Assembly"); document.getElementById("subassembly").focus();  return false;  
         str1=document.getElementById("subid1").value;
    }
    if(!str2){
//        alert("Select Quantity"); document.getElementById("quantity").focus();  return false;
          str2=document.getElementById("quantity1").value;
    }
    if(!str3){
//        alert("Select Quantity"); document.getElementById("quantity").focus();  return false;
          str3=document.getElementById("price1").value;
    }

    //var str1=document.getElementById("subassembly").value;
//    var str2=document.getElementById("quantity").value;
//    var str3=document.getElementById("price").value;
  //alert(str1);
//   alert(str2);
//   alert(str3);

if (str1=="")
  {
  document.getElementById("subassemblydiv").innerHTML="";     //editleads
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
    document.getElementById("subassemblydiv").innerHTML=xmlhttp.responseText;
    document.getElementById('subassembly').value="";       document.getElementById('quantity').value="";
    }
  } 
xmlhttp.open("GET","bio_dpr_subassemblydetails.php?subassembly=" + str1  + "&quantity=" + str2 +"&price="+str3,true);
xmlhttp.send();    

}

function editsubassembly(str)
{
   //alert("hii");
//var str1=document.getElementById("hfeedstock").value;
//var str2=document.getElementById("hfeedweight").value;
//alert(str);

if (str=="")
  {
  document.getElementById("editsub").innerHTML="";
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
    document.getElementById("editsub").innerHTML=xmlhttp.responseText;
//    document.getElementById('loc').focus(); 
    }
  } 
xmlhttp.open("GET","bio_dpr_subassemblydetails.php?tempid=" + str,true);
xmlhttp.send();    

}

function doedit()
{
//   alert("hii");
//   alert(str);

var str=document.getElementById("subasstempid").value;    
var str1=document.getElementById("subassemblyid").value;
var str2=document.getElementById("subquantity").value;
// alert(str); alert(str1);     alert(str2); 
if (str=="")
  {
  document.getElementById("subassemblydiv").innerHTML="";
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
    document.getElementById("subassemblydiv").innerHTML=xmlhttp.responseText;
    $('#subquantity').focus(); 
    }
  } 
xmlhttp.open("GET","bio_dpr_subassemblydetails.php?editid=" + str + "&editsub=" + str1 + "&editqty=" + str2,true);
xmlhttp.send();    

}  

function deletsubassembly(str)
{
//   alert("hii");
//   alert(str);
// alert(str); alert(str1);     alert(str2); 
if (str=="")
  {
  document.getElementById("subassemblydiv").innerHTML="";
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
    document.getElementById("subassemblydiv").innerHTML=xmlhttp.responseText;

    }
  } 
xmlhttp.open("GET","bio_dpr_subassemblydetails.php?deletid=" + str,true);
xmlhttp.send();    

}




function showSubassemblies(str){ //var a="#"+str;
//$(a).hide();

str=2;

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
    
    document.getElementById("subassembly_select").innerHTML=xmlhttp.responseText; 
     
    }
  }

xmlhttp.open("GET","bio_dpr_subassbdetails.php" ,true);
xmlhttp.send();
}

function subname(str)
{
var subname=document.getElementById("subassemblyname").value; 
if(subname!="no")       {
var newstr=subname + ',' + str;  
   
}else   {
    var newstr=str; ;
}
if (str=="")
  {
  document.getElementById("subassemblies").innerHTML="";
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
    document.getElementById("subassemblies").innerHTML=xmlhttp.responseText;

    }
  } 
xmlhttp.open("GET","bio_dpr_subassbname.php?id=" + newstr,true);
xmlhttp.send();    

}  



function DprPrint(str1,str2,str3)

{
    //alert(str1);
//    alert(str2);
//    alert(str3);
window.location="bio_dprIndex.php?dprid=" + str1 + "&leadid=" + str2 +"&plsize="+ str3;
}

</script>