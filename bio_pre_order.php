<?php
$PageSecurity = 80;
include('includes/session.inc');

$leadid=$_GET['p'];

echo "<fieldset style='float:center;width:950px;height:auto;'>";     
echo "<legend>Document Details</legend>";
echo "<table style='border:1px solid #F0F0F0;width:100%'><tr><td>";          

   $sql_cus="SELECT bio_leads.leadid,
                    bio_cust.custname,
                    bio_cust.contactperson
               FROM bio_leads,bio_cust
              WHERE bio_leads.cust_id=bio_cust.cust_id
                AND bio_leads.leadid=$leadid";
  $result_cus=DB_query($sql_cus,$db);
  $row_cus=DB_fetch_array($result_cus);
  $cname=$row_cus['custname'];  

echo"<tr>";
echo"<td><input type='hidden' name='Custname' id='custname' value='$cname'></td>Ogranisation Name:- $cname</tr>";

    echo"<tr><th>Slno</td>
             <th>Document Name</td>
             <th>Select</td>
             <th>Doc:/Ref: No</td>
             <th>Received Date</td>
             <th>Expiry Date</td> 
             <th>Value</td>
             <th>Remarks</th></tr>";   
             
$sql_cp="SELECT bio_document_master.doc_no, 
                bio_document_master.document_name ,
                bio_conceptproposal.cp_id 
           FROM bio_document_master,bio_conceptproposal 
          WHERE bio_document_master.document_type=4
            AND bio_document_master.enqtypeid=2
            AND bio_conceptproposal.lead_id=$leadid"; 
  
                        $result_cp=DB_query($sql_cp,$db);              
                        $i=0;
                        while($row_cp=DB_fetch_array($result_cp))
                        {
                        $i++;  
                        $documentno=$row_cp['doc_no'];                 
                        $cpid=$row_cp['cp_id']; 
                         
                                                                                             
echo"<tr id='editdocumentin' style='background:#A8A4DB'><td>$i</td>
     <td>".$row_cp['document_name']."</td>";   

 
echo"<td><input type=checkbox id='status".$i."' name='status".$i."'  onchange=idproof('$i'); > 
     <td><input type=text id='refno".$i."' name='refno".$i."' value='$refno'></td>
     <td><input type=text id='date".$i."' name='date".$i."' class=date alt=".$_SESSION['DefaultDateFormat']."  
          value='$date' onChange='return isDate(this, this.value, ".$_SESSION['DefaultDateFormat'].")'></td>
     <td><input type=text id='dateexp".$i."' name='dateexp".$i."' class=date alt=".$_SESSION['DefaultDateFormat']."  
          value='$dateexp' onChange='return isDate(this, this.value, ".$_SESSION['DefaultDateFormat'].")'></td>
     <td><input type='text' id='amount".$i."' name='amount".$i."' value='$docamount'></td>
     <td><input type='text' id='remark".$i."' name='remark".$i."' value='$remark'></td> 
     <td><input type=hidden id='documentno".$i."' name='documentno".$i."' value='$documentno'>
     <td><input type=hidden readonly id='cpid".$i."' name='cpid".$i."' value='$cpid'></td>";      


     
echo"</tr>";
  }    
$n=$i;

 echo "</table>";
 
echo'<input type=hidden name=no id=no value='.$n.'>'; 
echo'<input type=hidden name=leadid id=leadid value='.$leadid.'>'; 
  
 
echo "</fieldset>";

?>