<?php
  $PageSecurity = 80;
include('includes/session.inc');
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
            AND bio_proposal_status.statusid=bio_proposal.status
            and bio_proposal.propdate>'2012-11-01'";
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
?>
