<?php
  $PageSecurity = 80;
include('includes/session.inc');
$title = _('Payment Details');
 

  include('includes/header.inc');     
  
 // echo"<div>";
  echo "<table style='width:70%'><tr><td>";
  echo "<fieldset style='width:20%;'>";     
  echo "<legend><h3>Search By</h3>";     
  echo "</legend>"; 

    echo '<form name="leadsfrom"  method="post" action="' . $_SERVER['PHP_SELF'] . '?' .SID .'">';
     echo "<table style='border:1px solid #F0F0F0;width:100%'>";
  
  echo '<tr><td>From Date</td></tr>';             
   echo '<tr><td><input type="text" style="width:120px" id="datef" class=date alt='.$_SESSION['DefaultDateFormat']. ' name="datef" onChange="return isDate(this, this.value, '."'".$_SESSION['DefaultDateFormat']."'".')'.'"'.' value='.$datef.'></td></tr>';  
   echo '<tr><td>To Date</td></tr>';  
   echo '<tr><td><input type="text" style="width:120px" id="datet" class=date alt='.$_SESSION['DefaultDateFormat'].' name="datet" onChange="return isDate(this, this.value, '."'".$_SESSION['DefaultDateFormat']."'".')'.'"'.' value='.$datet.'></td></tr>';   
   echo '<tr><td>Name</td></tr>';
   echo '<tr><td><input type="text" name="byname" id="byname" style="width:120px"></td></tr>';
   echo '<tr><td>Place</td></tr>';
   echo '<tr><td><input type="text" name="byplace" id="byplace" style="width:120px"></td></tr>';
   echo '<tr><td>Enquiry Type</td></tr>';
   echo '<tr><td><select name="enquiry" id="enquiry" style="width:120px">';
   echo '<option value=0></option>';  
   $sql2="select * from bio_enquirytypes";
   $result2=DB_query($sql2,$db);
   while($row2=DB_fetch_array($result2))
   {
       if ($row2['enqtypeid']==$_POST['enquiry'])
       {
         echo '<option selected value="';
       } else 
       { 
           echo '<option value="'; 
       }
       echo $row2['enqtypeid'] . '">'.$row2['enquirytype'];
       echo '</option>';
   } 
                                                            
   echo '</select></td></tr>';      
   echo '<tr><td>Team</td></tr>';
   echo '<tr><td><select name="team" id="team" style="width:120px">';
   echo '<option value=0></option>'; 
   $sql1="select * from bio_leadteams";
   $result1=DB_query($sql1,$db);
   while($row1=DB_fetch_array($result1))
   {
       if ($row1['teamid']==$_POST['team'])
       {
         echo '<option selected value="';
       } else 
       { 
           echo '<option value="'; 
       }
       echo $row1['teamid'] . '">'.$row1['teamname'];
       echo '</option>';
   }
   echo '</select></td></tr>';
   echo '<tr><td>Office</td></tr>';
   echo '<tr><td><select name="off" id="off" style="width:120px">';
   echo '<option value=0></option>'; 
   $sql1="select * from bio_office";
   $result1=DB_query($sql1,$db);
   while($row1=DB_fetch_array($result1))
   {
       if ($row1['id']==$_POST['off'])
       {
         echo '<option selected value="';
       } else 
       { 
           echo '<option value="'; 
       }
       echo $row1['id'] . '">'.$row1['office'];
       echo '</option>';
   }
   echo '</select></td></tr>';  
   echo '<tr><td>Lead Sourse</td></tr>';
   echo '<tr><td><select name="leadsrc" id="leadsrc" style="width:120px">';
   echo '<option value=0></option>';
   echo '<option value="ALL">Select ALL</option>';
   $sql1="select * from bio_leadsources";
   $result1=DB_query($sql1,$db);
   while($row1=DB_fetch_array($result1))
   {
       if ($row1['id']==$_POST['leadsrc'])
       {
         echo '<option selected value="';
       } else 
       { 
           echo '<option value="'; 
       }
       echo $row1['id'] . '">'.$row1['sourcename'];
       echo '</option>';
   }                                                             
   echo '</select></td></tr>';      
   echo '<td><input type="submit" name="filterbut" id="filterbut" value="search"></td></tr>';
   echo '</table>';
   echo '</fiieldset>';
   echo '</td>';          
  

  
 $sql="SELECT bio_leads.leadid,
                    bio_cust.custname,
                    bio_cust.area1,
                    bio_leads.leaddate,
                    bio_advance.amount,
                    bio_cashheads.heads,
                    bio_advance.date,
                    bio_advance.serialnum,
                    bio_advance.bankname,
                    bio_advance.adv_id,
                    bio_advance.ledgerstatus,
                    debtorsmaster.debtorno
           FROM     bio_leads,
                    bio_cust,
                    bio_advance,
                    bio_cashheads,
                    debtorsmaster
          WHERE     bio_advance.leadid=bio_leads.leadid
           AND      bio_advance.head_id=bio_cashheads.head_id     
           AND      bio_leads.cust_id=bio_cust.cust_id
           AND      bio_cust.debtorno=debtorsmaster.debtorno
           AND bio_advance.amount>0";

$result=DB_query($sql,$db);  
                         
if(isset($_POST['filterbut']))
 {    
     
    if ((isset($_POST['datef'])) && (isset($_POST['datet'])))   {
    if (($_POST['datef']!="") && ($_POST['datet']!=""))  { 
    $sourcetypefrom=FormatDateForSQL($_POST['datef']);   
    $sourcetypeto=FormatDateForSQL($_POST['datet']);
    $sql.=" AND bio_leads.leaddate BETWEEN '".$sourcetypefrom."' AND '".$sourcetypeto."'";
    $datef=$_POST['datef'];
    $datet=$_POST['datet'];
    }  }
    $officeid=$_POST['off'];
    echo $officeid;
    if (isset($_POST['byname']))  {        
    if ($_POST['byname']!='')   
    $sql.= " AND bio_cust.custname LIKE '%".$_POST['byname']."%'";   
    }                                                                    
            
    if (isset($_POST['byplace'])) {
    if ($_POST['byplace']!='') 
    $sql.= " AND bio_cust.area1 LIKE '%".$_POST['byplace']."%'"; 
    }
    
//    if (isset($_POST['team']))    {
//    if (($_POST['team']!='')&&($_POST['team']!='0'))
//    $sql.=" AND bio_leadteams.teamid='".$_POST['team']."'";    
//    }
//    
//    if (isset($_POST['off']))    {
//    if (($_POST['off']!='')&&($_POST['off']!='0'))
//    $sql.=" AND bio_leadsources.officeid=".$_POST['off'];
//    }
//    
//    if (isset($_POST['enquiry'])) {
//    if (($_POST['enquiry']!='ALL') && ($_POST['enquiry']!=0))
//    $sql.=" AND bio_enquirytypes.enqtypeid='".$_POST['enquiry']."'";
//    } 
//    
//    if (isset($_POST['leadsrc'])) {
//    if (($_POST['leadsrc']!='ALL') && ($_POST['leadsrc']!=0))
//    $sql.=" AND bio_leads.sourceid='".$_POST['leadsrc']."'";
//    }
}
       
$sql.=" ORDER by leadid asc";

 echo "<td>"; 
//      echo "<div id='printandshow' style='margin:auto;'>";
      echo "<fieldset style='width:95%;'>";     
  echo "<legend><h3>Lead Details</h3>";     
  echo "</legend>";
      echo "<div style='height:350px; overflow:auto;'>"; 
      echo "<table  style='width:100%;' id='leadreport'>"; 
      echo "<thead>
        <tr BGCOLOR =#800000><th>" . _('Sl no:') . "</th> 
         <th>" . _('Lead id') . "</th>
         <th>" . _('Customer') . "</th>
         <th>" . _('Place') . "</th>
         <th>" . _('Date') . "</th>
          <th>" . _('Advance amount') . "</th>
         <th>" . _('Description') . "</th>
       
        </tr></thead>";

 //$result=DB_query($sql,$db);  
   
      
   echo '<tbody>';
   echo '<tr>';                                       
     
      $slno=0;
      $k=0; 
      while($myrow=DB_fetch_array($result))
      {    
          $lead_ID=$myrow['leadid'];
          $ledgerstatus=$myrow['ledgerstatus'];
          $advanceid=$myrow['adv_id'];
          $debtor_no=$myrow['debtorno'];
          
          
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
      $slno++;

//printf("<td cellpading=2>%s</td>
//        <td>%s</td>
//        <td>%s</td>
//        <td>%s</td>
//        <td>%s</td>
//        <td>%s</td>
//        <td>%s</td>

//          </tr>",
//        $no,
//        $myrow['leadid'],
//        $myrow['custname'],
//        $myrow['area1'],
//       convertSQLDate($myrow['leaddate']),
//        $myrow['amount'],
//        $myrow['heads'],
//        $_SERVER['PHP_SELF'] . '?' . SID,$myrow[0]);
//        
        
        
        
         echo'<td>'.$slno.'</td>';
//    echo'<td>'.$myrow3['reqno'].'</td>';
    echo'<td>'.$myrow['leadid'].'</td>';
    echo'<td>'.$myrow['custname'].'</td>';
    echo'<td>'.$myrow['area1'].'</td>';
    echo'<td>'.ConvertSQLDate($myrow['leaddate']).'</td>';
    echo'<td>'.$myrow['amount'].'</td>';
    echo'<td>'.$myrow['heads'].'</td>';
    

        if($ledgerstatus==0){
        echo "<td><a style=cursor:pointer; id=".$myrow['leadid']." onclick=viewDetails('$lead_ID','$advanceid','$debtor_no')>Tranfer to ledger</a></td>";
    }elseif($ledgerstatus==1){
        echo "<td>Payment Tranferred</td>";
    }
    

        }                
 echo '</tbody>';
   
      echo '</table>';  
      echo '</div>'; 
//      echo '<input type="submit" name="excel" value="View as Excel">'; 
      echo "</fieldset>";
      echo '</div>';
      echo '</form>';
      
      echo "</td></tr></table>"; 
      echo "</div>";
   include('includes/footer.inc'); 
 
?>
?>  
<script type="text/javascript">
    function viewDetails(str1,str2,str3){
        //alert(str3);   
        window.location="bio_custpaymenttransfer.php?lead=" + str1 + "&advance=" + str2 + "&id=" + str3;
    }
    </script>  