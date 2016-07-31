<?php
$PageSecurity = 80;
include('includes/session.inc');
$title = _('PRE-ORDER DOCUMENTS');  
include('includes/header.inc');

echo '<center><font style="color: #333;
          background:#fff;
          font-weight:bold;
          letter-spacing:0.10em;
          font-size:16px;
          font-family:Georgia;
          text-shadow: 1px 1px 1px #666;">PRE-ORDER DOCUMENTS</font></center>';
          
if(isset($_POST['submit']))  
 {
      
    for ($i=1;$i<=$_POST['no'];$i++)  {    
    
    if($_POST['status'.$i]=='on')
    {
       $_POST['documentno'.$i];
                       
       $sql2="SELECT status FROM bio_predocuments WHERE bio_predocuments.docno='".$_POST['documentno'.$i]."' AND bio_predocuments.leadid='".$_POST['leadid']."'";
       $result2=DB_query($sql2,$db);
       $row2=DB_fetch_array($result2);
       $status=$row2['status'];

      if($status==0) 
                {
                    
                   $sql5="UPDATE bio_conceptproposal SET tender_status=3 WHERE lead_id='".$_POST['leadid']."'";                               
                   $result5=DB_query($sql5,$db);          
                    
                   $_POST['documentno'.$i];  
                   $_POST['refno'.$i]; 
                   $_POST['date'.$i];                                       
                   $_POST['dateexp'.$i];
                   $_POST['amount'.$i];
                   $_POST['remark'.$i];
                   $_POST['closestatus'.$i];
                   $_POST['cpid'.$i];

                   
                   $sql_up="INSERT INTO bio_predocuments(leadid,docno,
                                                         refno,created_date,
                                                         expiry_date,pre_amount,
                                                         status,remark,
                                                         cpid,close_status)
                                       VALUES('".$_POST['leadid']."',
                                              '".$_POST['documentno'.$i]."',
                                              '".$_POST['refno'.$i]."',
                                              '".FormatDateForSQL($_POST['date'.$i])."',
                                              '".FormatDateForSQL($_POST['dateexp'.$i])."',
                                              '".$_POST['amount'.$i]."',1,
                                              '".$_POST['remark'.$i]."',
                                              '".$_POST['cpid'.$i]."',0)";
                                              

                
                
                }elseif($status==1){      
                    
                    $sql_up="UPDATE bio_predocuments SET refno='".$_POST['refno'.$i]."',
                                                         created_date='".FormatDateForSQL($_POST['date'.$i])."',
                                                         expiry_date='".FormatDateForSQL($_POST['dateexp'.$i])."',
                                                         pre_amount='".$_POST['amount'.$i]."',
                                                         remark='".$_POST['remark'.$i]."',
                                                         close_status='".$_POST['closestatus'.$i]."' 
                                                   WHERE docno='".$_POST['documentno'.$i]."'
                                                     AND leadid='".$_POST['leadid']."'";


         }
         
       $result_up=DB_query($sql_up,$db);           
      }          
    }            
 }//---Submit---

          
          
//=======================================================  
echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "?" . SID . "'>";     

echo "<table style='border:1px solid #F0F0F0;width:100%'><tr><td>";   
//=======================================================

echo"<div id=panel>"; 

echo "<fieldset style='float:center;width:950px;height:auto;'>";     
echo "<legend>Document Details</legend>";
echo "<table style='border:1px solid #F0F0F0;width:100%'><tr><td>";          

    echo"<tr><th>Slno</td>
             <th>Document Name</td>
             <th>Select</th>
             <th>Doc:/Ref: No</td> 
             <th>Received Date</td>
             <th>Expiry Date</td> 
             <th>Value</td>
             <th>Remarks</td>
             <th>Status</td></tr>";
             


                            $sql_cp="SELECT bio_document_master.document_name
                                       FROM bio_document_master 
                                      WHERE bio_document_master.document_type=4
                                        AND bio_document_master.enqtypeid=2"; 
  
                        $result_cp=DB_query($sql_cp,$db);              
                        $i=0;
                        while($row_cp=DB_fetch_array($result_cp))
                        {
                        $i++;  
                                         
                                                                                             
echo"<tr id='editdocumentin' style='background:#A8A4DB'><td>$i</td>
     <td>".$row_cp['document_name']."</td>";
      
 
echo"<td><input type=checkbox id='status".$i."' name='status".$i."'  onchange=idproof('$i'); > 
     <td><input type=text readonly id='refno".$i."' name='refno".$i."' value='$refno'></td>
     <td><input type=text readonly id='date".$i."' name='date".$i."' class=date alt=".$_SESSION['DefaultDateFormat']."  
          value='$date' onChange='return isDate(this, this.value, ".$_SESSION['DefaultDateFormat'].")'></td>
     <td><input type=text readonly id='dateexp".$i."' name='dateexp".$i."' class=date alt=".$_SESSION['DefaultDateFormat']."  
          value='$dateexp' onChange='return isDate(this, this.value, ".$_SESSION['DefaultDateFormat'].")'></td>
     <td><input type='text' readonly id='amount".$i."' name='amount".$i."' value='$docamount'></td>
     <td><input type='text' readonly id='remark".$i."' name='remark".$i."' value='$remark'></td>
     <td><select name='closestatus".$i."' id='closestatus".$i."'>
         <option value=0></option>;
         <option value=1>Close</option>;
         </select></td>
     <td><input type=hidden readonly id='documentno".$i."' name='documentno".$i."' value='$documentno'></td>"; 
     
     
echo"</tr>";
  }
    
echo "</table>";
echo "</fieldset>"; 
echo"</div>";


echo'<div class="centre"> 
     <input type=submit name=submit value="' . _('Save') . '">
     <input id="shwprint" type="button" name="shwprint" value="view/hide proposals" >';  
echo'</div>'; 

 //========================================================= concept proposal grid
echo'<div id="cpgrid">';
echo"<fieldset style='float:middle;width:950px;'><legend>Concept Proposal</legend>";  
 
echo"<table style='border:1px solid #F0F0F0;width:100%'>"; 

echo"<tr><td>Date From</td><td>Date To</td><td>Name</td><td>Place</td><td>Office</td><td>Lead Source</td></tr>"; 

echo"<tr>";
echo '<td><input type="text" style="width:100px" id="df2" class=date alt='.$_SESSION['DefaultDateFormat'].' name="df2" onChange="return isDate(this, this.value, '."'".$_SESSION['DefaultDateFormat']."'".')'.'"'.'></td>';  
echo '<td><input type="text" style="width:100px" id="dt2" class=date alt='.$_SESSION['DefaultDateFormat'].' name="dt2" onChange="return isDate(this, this.value, '."'".$_SESSION['DefaultDateFormat']."'".')'.'"'.'></td>';

echo"<td><input type='text' name='byname2' id='byname2'></td>";
echo"<td><input type='text' name='byplace2' id='byplace2'></td>";
echo '<td><select name="off2" id="off2" style="width:100px">';
echo '<option value=0></option>';

    $sql1="select * from bio_office";
    $result1=DB_query($sql1,$db);
    while($row1=DB_fetch_array($result1))
    {
  echo "<option value=$row1[id]>$row1[office]</option>";
    }
    
echo '</select></td>';
echo '<td><select name="leadsrc2" id="leadsrc2" style="width:100px">';
echo '<option value=0></option>';
echo '<option value="ALL">Select ALL</option>';

    $sql1="select * from bio_leadsources";
    $result1=DB_query($sql1,$db);
    while($row1=DB_fetch_array($result1))
    {
  echo "<option value=$row1[id]>$row1[sourcename]</option>";
  }                                                             
echo '</select></td>';      

echo"<td><input type='submit' name='filterbut2' id='filterbut2' value='search'></td>";
echo"</tr>";
echo"</table>";

echo "<div style='height:200px; width:100%; overflow:scroll;'>"; 
echo"<table style='width:100%' border=0> ";   
echo"<tr><th>Slno</th>
         <th>Name</th>
         <th>Created On</th>
         <th>Total Price</th>
         <th>Status</th></tr>";
         
$sql8="SELECT bio_conceptproposal.cp_id AS conceptproposalid,
              bio_conceptproposal.fsentry_id,
              bio_conceptproposal.total_price AS total_price,
              bio_conceptproposal.created_on AS proposaldate,
              bio_leads.cust_id,
              bio_cust.custname AS custname,
              bio_cust.area1 AS place,
              bio_leads.leaddate AS leaddate,
              bio_office.id AS officeid,
              bio_office.office AS office,
              bio_leadsources.sourcename AS sourcename,
              bio_proposal_status.status,
              bio_conceptproposal.status as cp_status,
              bio_conceptproposal.lead_id
         FROM bio_conceptproposal,
              bio_leads,
              bio_office,
              bio_leadsources,
              bio_cust,
              bio_proposal_status
        WHERE bio_conceptproposal.lead_id=bio_leads.leadid
          AND bio_office.id=bio_leadsources.officeid 
          AND bio_leads.cust_id=bio_cust.cust_id
          AND bio_leadsources.id=bio_leads.sourceid
          AND bio_proposal_status.statusid=bio_conceptproposal.status
          AND bio_conceptproposal.status!=5 
          AND bio_leads.leadstatus!=20
          AND bio_conceptproposal.tender_status=1";      
       

if(isset($_POST['filterbut2']))
{  
    if ((isset($_POST['df2'])) && (isset($_POST['dt2'])))   {
    if (($_POST['df2']!="") && ($_POST['dt2']!=""))  { 
    $sourcetypefrom=FormatDateForSQL($_POST['df2']);   
    $sourcetypeto=FormatDateForSQL($_POST['dt2']);
    $sql8.=" AND bio_conceptproposal.createdon BETWEEN '".$sourcetypefrom."' AND '".$sourcetypeto."'";
    }  }
    $officeid=$_POST['off2'];
    echo $officeid;
    if (isset($_POST['byname2']))  {        
    if ($_POST['byname2']!='')   
    $sql8 .= " AND bio_cust.custname LIKE '%".$_POST['byname2']."%'";   
    }                                                                    
            
    if (isset($_POST['byplace2'])) {
    if ($_POST['byplace2']!='') 
    $sql8 .= " AND bio_cust.area1 LIKE '%".$_POST['byplace2']."%'"; 
    }
    
    if (isset($_POST['off2']))    {
    if (($_POST['off2']!='')&&($_POST['off2']!='0'))
    $sql8.=" AND bio_leadsources.officeid=".$officeid;    
    }
    
    if (isset($_POST['leadsrc2'])) {
    if (($_POST['leadsrc2']!='ALL') && ($_POST['leadsrc2']!=0))
    $sql8.=" AND bio_leads.sourceid='".$_POST['leadsrc2']."'";
    }
 }   
 
 $result8=DB_query($sql8,$db);
 $k=0;
 $no=0;

 
while($myrow8=DB_fetch_array($result8))     {
    
 $cpid=$myrow8['conceptproposalid'];
 $leadid=$myrow8['lead_id'];    
 $plantid=$myrow8['plant'];
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

 

 $sql_reject="SELECT reason FROM bio_rejectedproposal WHERE leadid=$leadid"; 
 $result_reject=DB_query($sql_reject,$db);
 $row_reject=DB_fetch_array($result_reject);
 $reject=$row_reject['reason'];
 
 $rejectedreason="Rejected reason:".$reject;

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
           $no++;
           
           echo"<td cellpading=2>".$no."</td> 
                <td>".$myrow8['custname']."</td> 
                <td>".ConvertSQLDate($myrow8['proposaldate'])."</td>  
                <td>".$myrow8['total_price']."</td>";
   if($myrow8['status']=="Rejected by Biotech"){             
           //echo"<td>".$myrow8['status']."</td>";
           echo"<td><a title='$rejectedreason' href='' id='trigger'>".$myrow8['status']."</a></td>";
   }else{
         echo"<td>".$myrow8['status']."</td>";
   }
   
        echo"<td><a  style='cursor:pointer;'  id='$leadid' onclick='tender(this.id)'>" . _('Select') . "</a></td>
              </tr>";



}

echo '<tbody>';    
echo"</tr></tbody></table>";
                                                       


echo"</div>";
echo"</fieldset>";  
echo'</div>';
     

//=========================================================grid


echo'<div id="leadgrid">';
echo"<fieldset style='float:center;width:950px;'><legend>Document List</legend>"; 
echo "<form name=filter method='post'>";
echo"<table style='border:1px solid #F0F0F0;width:100%'>";


echo"<tr><td>Date From</td><td>Date To</td><td>Name</td><td>Place</td></tr>";
echo"<tr>";
echo '<td><input type="text" style="width:100px" id="df1" class=date alt='.$_SESSION['DefaultDateFormat'].' name="df1" onChange="return isDate(this, this.value, '."'".$_SESSION['DefaultDateFormat']."'".')'.'"'.'></td>';  
echo '<td><input type="text" style="width:100px" id="dt1" class=date alt='.$_SESSION['DefaultDateFormat'].' name="dt1" onChange="return isDate(this, this.value, '."'".$_SESSION['DefaultDateFormat']."'".')'.'"'.'></td>';
echo"<td><input type='text' name='byname1' id='byname1'></td>";
echo"<td><input type='text' name='byplace1' id='byplace1'></td>";
//echo '<td><select name="off1" id="off1" style="width:100px">';
//echo '<option value=0></option>'; 
//echo '</select></td>';

//echo '<td><select name="leadsrc1" id="leadsrc1" style="width:100px">';
//echo '<option value=0></option>';
//echo '<option value="ALL">Select ALL</option>';
//$sql1="select * from bio_leadsources";
//$result1=DB_query($sql1,$db);
//while($row1=DB_fetch_array($result1))
//{
//  echo "<option value=$row1[id]>$row1[sourcename]</option>";
//}                                                             
//echo '</select></td>';      

echo"<td><input type='submit' name='filterbut1' id='filterbut1' value='search'></td>";
echo"</tr>";
echo"</table>"; 

echo "<div style='height:auto; width:100%; overflow:scroll;'>";
echo"<table style='width:100%' border=0> ";   
echo"<tr><th>Slno</th><th>Name</th><th>District</th><th>Phone</th><th>Team</th></tr>";
echo '<tbody>';

$office=$_SESSION['UserStockLocation']; 
$empid=$_SESSION['empid'];
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
   
    $team=array();  
    $sql6="SELECT teamid FROM bio_teammembers WHERE empid IN ($employee_arr)";
    $result6=DB_query($sql6,$db);
   while($row6=DB_fetch_array($result6))
   {
        $team[]=$row6['teamid'];
   }
        $team_array=join(",", $team);
        
        
    $sql_usr="SELECT userid FROM www_users WHERE empid IN ($employee_arr)";
     $result_usr=DB_query($sql_usr,$db);
     while($row_usr=DB_fetch_array($result_usr))
     {
    
    $userid[]="'".$row_usr['userid']."'";     
    
     }
     $user_array=join(",", $userid);    
        
        


  $sql="SELECT bio_cust.cust_id AS custid,  
               bio_cust.custname AS custname,
               bio_cust.custmob AS custmob,               
               bio_cust.area1 AS place,
               bio_cust.district AS districtid,
               bio_district.district AS district,
               bio_enquirytypes.enquirytype AS enqtype,
               bio_leadtask.leadid AS leadid, 
               bio_leads.leaddate AS leaddate,
               bio_conceptproposal.created_on AS cpdate,
               bio_leadteams.teamname AS teamname,
                count(*) AS Expired
          FROM bio_cust,
               bio_leads,
               bio_leadteams,
               bio_enquirytypes,
               bio_district,
               bio_leadtask,
               bio_conceptproposal,
               bio_predocuments   
         WHERE bio_cust.cust_id=bio_leads.cust_id 
           AND bio_leads.enqtypeid=bio_enquirytypes.enqtypeid
           AND bio_cust.district=bio_district.did
           AND bio_district.stateid=bio_cust.state
           AND bio_district.cid=bio_cust.nationality
           AND bio_leads.enqtypeid=2
           AND bio_leadtask.leadid=bio_leads.leadid 
           AND bio_leadtask.taskid=4 
           AND bio_leadtask.viewstatus=1
           AND bio_leadteams.teamid=bio_leadtask.teamid
           AND bio_leads.leadid=bio_conceptproposal.lead_id
           AND bio_leads.leadid=bio_predocuments.leadid
           AND expiry_date >= now()  
           GROUP BY bio_cust.cust_id";   

 //echo $sql5;
 if(isset($_POST['filterbut1']))
 {  
    if ((isset($_POST['df1'])) && (isset($_POST['dt1'])))   {
    if (($_POST['df1']!="") && ($_POST['dt1']!=""))  { 
    $sourcetypefrom=FormatDateForSQL($_POST['df1']);   
    $sourcetypeto=FormatDateForSQL($_POST['dt1']);
    $sql.=" AND bio_leads.leaddate BETWEEN '".$sourcetypefrom."' AND '".$sourcetypeto."'";
    }  }
    $officeid=$_POST['off1'];
  //  echo $officeid;
    if (isset($_POST['byname1']))  {        
    if ($_POST['byname1']!='')   
    $sql .= " AND bio_cust.custname LIKE '%".$_POST['byname1']."%'";   
    }                                                                    
            
    if (isset($_POST['byplace1'])) {
    if ($_POST['byplace1']!='') 
    $sql .= " AND bio_district.district LIKE '%".$_POST['byplace1']."%'"; 
    }
    
    if (isset($_POST['off1']))    {
    if (($_POST['off1']!='')&&($_POST['off1']!='0'))
    $sql .=" AND bio_leadsources.officeid=".$officeid;    
    }
    
    if (isset($_POST['leadsrc1'])) {
    if (($_POST['leadsrc1']!='ALL') && ($_POST['leadsrc1']!=0))
    $sql .=" AND bio_leads.sourceid='".$_POST['leadsrc1']."'";
    }
 }      
  
$result=DB_query($sql,$db);
 $no=0; 

          $k=0; 
          while($myrow=DB_fetch_array($result))
          {    //echo $myrow[0];   
           
          $no++;
               if ($expirydate<=date("Y-m-d"))
                {
                    echo '<tr bgcolor=#C1F4B9>';
                    $k=0;
                    
                }
                 else 
                 {
                    echo '<tr class="OddTableRows">';
//                    $k=1;     
                 }
                    
                    
                    $leadid=$myrow['leadid'];
                    $status=$myrow['status'];
                    $expirydate=$myrow['expiry_date'];
                
                   
                    
                    
printf("<td cellpading=2>%s</td>
        <td>%s</td>
        <td>%s</td>
        <td>%s</td>
        <td>%s</td>



         
        <td><a  style='cursor:pointer;'  id='$leadid' onclick='passid(this.id)'>" . _('Select') . "</a></td>  
        </tr>",
        
        $no,
        $myrow['custname'],
        $myrow['district'],
        $myrow['custmob'], 
       // ConvertSQLDate($myrow['cpdate']),
        $myrow['teamname']);
        }



echo"</tr></tbody></table>";
echo"</div>";
echo"</fieldset>";  
echo'</div>'; 
echo"</form>";

//=========================================================grid


echo'<div id="leadgrid1">';
echo"<fieldset style='float:center;width:950px;'><legend>Expired Document List</legend>"; 
echo "<form name=filter method='post'>";
echo"<table style='border:1px solid #F0F0F0;width:100%'>";


echo"<tr><td>Date From</td><td>Date To</td><td>Name</td><td>Place</td></tr>";
echo"<tr>";
echo '<td><input type="text" style="width:100px" id="df1" class=date alt='.$_SESSION['DefaultDateFormat'].' name="df1" onChange="return isDate(this, this.value, '."'".$_SESSION['DefaultDateFormat']."'".')'.'"'.'></td>';  
echo '<td><input type="text" style="width:100px" id="dt1" class=date alt='.$_SESSION['DefaultDateFormat'].' name="dt1" onChange="return isDate(this, this.value, '."'".$_SESSION['DefaultDateFormat']."'".')'.'"'.'></td>';
echo"<td><input type='text' name='byname1' id='byname1'></td>";
echo"<td><input type='text' name='byplace1' id='byplace1'></td>";
//echo '<td><select name="off1" id="off1" style="width:100px">';
//echo '<option value=0></option>'; 
//echo '</select></td>';

//echo '<td><select name="leadsrc1" id="leadsrc1" style="width:100px">';
//echo '<option value=0></option>';
//echo '<option value="ALL">Select ALL</option>';
//$sql1="select * from bio_leadsources";
//$result1=DB_query($sql1,$db);
//while($row1=DB_fetch_array($result1))
//{
//  echo "<option value=$row1[id]>$row1[sourcename]</option>";
//}                                                             
//echo '</select></td>';      

echo"<td><input type='submit' name='filterbut1' id='filterbut1' value='search'></td>";
echo"</tr>";
echo"</table>"; 

echo "<div style='height:auto; width:100%; overflow:scroll;'>";
echo"<table style='width:100%' border=0> ";   
echo"<tr><th>Slno</th><th>Name</th><th>District</th><th>Phone</th><th>Team</th></tr>";
echo '<tbody>';

  $sql="SELECT bio_cust.cust_id AS custid,  
               bio_cust.custname AS custname,
               bio_cust.custmob AS custmob,               
               bio_cust.area1 AS place,
               bio_cust.district AS districtid,
               bio_district.district AS district,
               bio_enquirytypes.enquirytype AS enqtype,
               bio_leadtask.leadid AS leadid, 
               bio_leads.leaddate AS leaddate,
               bio_conceptproposal.created_on AS cpdate,
               bio_leadteams.teamname AS teamname,
               count(*) as Notexpired
          FROM bio_cust,
               bio_leads,
               bio_leadteams,
               bio_enquirytypes,
               bio_district,
               bio_leadtask,
               bio_conceptproposal,
               bio_predocuments   
         WHERE bio_cust.cust_id=bio_leads.cust_id 
           AND bio_leads.enqtypeid=bio_enquirytypes.enqtypeid
           AND bio_cust.district=bio_district.did
           AND bio_district.stateid=bio_cust.state
           AND bio_district.cid=bio_cust.nationality
           AND bio_leads.enqtypeid=2
           AND bio_leadtask.leadid=bio_leads.leadid 
           AND bio_leadtask.taskid=4 
           AND bio_leadtask.viewstatus=1
           AND bio_leadteams.teamid=bio_leadtask.teamid
           AND bio_leads.leadid=bio_conceptproposal.lead_id
           AND bio_leads.leadid=bio_predocuments.leadid
           AND expiry_date <= now()
           GROUP BY bio_cust.cust_id"; 
           
 if(isset($_POST['filterbut1']))
 {  
    if ((isset($_POST['df1'])) && (isset($_POST['dt1'])))   {
    if (($_POST['df1']!="") && ($_POST['dt1']!=""))  { 
    $sourcetypefrom=FormatDateForSQL($_POST['df1']);   
    $sourcetypeto=FormatDateForSQL($_POST['dt1']);
    $sql.=" AND bio_leads.leaddate BETWEEN '".$sourcetypefrom."' AND '".$sourcetypeto."'";
    }  }
    $officeid=$_POST['off1'];
  //  echo $officeid;
    if (isset($_POST['byname1']))  {        
    if ($_POST['byname1']!='')   
    $sql .= " AND bio_cust.custname LIKE '%".$_POST['byname1']."%'";   
    }                                                                    
            
    if (isset($_POST['byplace1'])) {
    if ($_POST['byplace1']!='') 
    $sql .= " AND bio_district.district LIKE '%".$_POST['byplace1']."%'"; 
    }
    
    if (isset($_POST['off1']))    {
    if (($_POST['off1']!='')&&($_POST['off1']!='0'))
    $sql .=" AND bio_leadsources.officeid=".$officeid;    
    }
    
    if (isset($_POST['leadsrc1'])) {
    if (($_POST['leadsrc1']!='ALL') && ($_POST['leadsrc1']!=0))
    $sql .=" AND bio_leads.sourceid='".$_POST['leadsrc1']."'";
    }
 }      
  
$result=DB_query($sql,$db);
 $no=0; 

          $k=0; 
          while($myrow=DB_fetch_array($result))
          {    //echo $myrow[0];   
           
          $no++;
               if ($expirydate<=date("Y-m-d"))
                {
                    echo '<tr bgcolor=#F6CAC1>';
                    $k=0;
                    
                }
                 else 
                 {
                    echo '<tr class="OddTableRows">';
//                    $k=1;     
                 }
                    
                    
                    $leadid=$myrow['leadid'];
                    $status=$myrow['status'];
                    $expirydate=$myrow['expiry_date'];

                   
                    
                    
printf("<td cellpading=2>%s</td>
        <td>%s</td>
        <td>%s</td>
        <td>%s</td>
        <td>%s</td>



         
        <td><a  style='cursor:pointer;'  id='$leadid' onclick='passid(this.id)'>" . _('Select') . "</a></td>  
        </tr>",
        
        $no,
        $myrow['custname'],
        $myrow['district'],
        $myrow['custmob'], 
        //ConvertSQLDate($myrow['cpdate']),
        $myrow['teamname']);
        }
        
 echo"</tr></tbody></table>";
echo"</div>";
echo"</fieldset>";  
echo'</div>'; 
echo"</form>";       
                      

                      
                      
                      

    
//echo'</div>';
echo"</form>";
?>
<script type="text/javascript">


  $(document).ready(function() {
  $('#district1').hide();
  $('#cpgrid').hide();
  $("#error").fadeOut(3000);                                    
  $("#warn").fadeOut(3000);
  $("#success").fadeOut(3000);
  $("#info").fadeOut(3000);
  $("#db_message").fadeOut(3000);  
  $('#sourcetype').change(function() { });                                                                                
  $('#shwprint').click(function() {
      
  $('#cpgrid').slideToggle('slow',function(){});     
  $('#leadgrid').slideToggle('slow',function(){});
  $('#leadgrid1').slideToggle('slow',function(){});
});    
}); 

 
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
   // fsid(str1);
    }
  } 
 xmlhttp.open("GET","bio_pre_view.php?p=" + str1,true);
 xmlhttp.send(); 
 }

 
function tender(str1){
alert(str1);

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
   // fsid(str1);
    }
  } 
 xmlhttp.open("GET","bio_pre_order.php?p=" + str1,true);
 xmlhttp.send(); 
 } 



</script>   