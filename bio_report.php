<?php
  $PageSecurity = 80;
include('includes/session.inc');
$title = _('Lead Details');  
include('includes/header.inc');
 
 echo '<center><font style="color: #333;
                           background:#fff;
                           font-weight:bold;
                           letter-spacing:0.10em;
                           font-size:16px;
                           font-family:Georgia;
                           text-shadow: 1px 1px 1px #666;">Lead Details</font></center>';
 
  echo "<table style='width:90%'><tr><td>";
  echo "<fieldset style='width:97%;'>";     
  echo "<legend><h3>Showing all customer Leads</h3>";     
  echo "</legend>"; 

    echo '<form name="leadsfrom"  method="post" action="' . $_SERVER['PHP_SELF'] . '?' .SID .'">';
  
   echo "<table style='border:1px solid #F0F0F0;width:100%'>";
 
   echo '<tr><td>Date From</td><td>Date To</td><td>Name</td><td>Place</td><td>Team</td><td>Office</td><td>Lead Sourse</td><td>Enquiry type</td></tr>';             
   echo '<tr><td><input type="text" style="width:100px" id="datef" class=date alt='.$_SESSION['DefaultDateFormat']. ' name="datef" onChange="return isDate(this, this.value, '."'".$_SESSION['DefaultDateFormat']."'".')'.'"'.'></td>';    
   echo '<td><input type="text" style="width:100px" id="datet" class=date alt='.$_SESSION['DefaultDateFormat'].' name="datet" onChange="return isDate(this, this.value, '."'".$_SESSION['DefaultDateFormat']."'".')'.'"'.'></td>';   
   echo '<td><input type="text" name="byname" id="byplace" style="width:120px"></td>';
   echo '<td><input type="text" name="byplace" id="byplace" style="width:120px"></td>';
   echo '<td><select name="team" id="team" style="width:100px">';
   echo '<option value=0></option>'; 
   $sql1="select * from bio_leadteams";
  $result1=DB_query($sql1,$db);
  while($row1=DB_fetch_array($result1))
  {
      echo "<option value=$row1[teamid]>$row1[teamname]</option>";
  }
  echo '</select></td>';
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
  echo '<td><select name="enquiry" id="type" style="width:100px">';
  echo '<option value=0></option>';  
  $sql2="select * from bio_enquirytypes";
  $result2=DB_query($sql2,$db);
  while($row2=DB_fetch_array($result2))
  {
      echo "<option value=$row2[enqtypeid]>$row2[enquirytype]</option>";
  } 
                                                            
  echo '</select></td>';      
  echo '<td><input type="submit" name="filterbut" id="filterbut" value="search"></td></tr>';
  echo '</table>';
      
      echo "<div style='height:300px; overflow:auto;'>"; 
      echo "<table  style='width:100%;' id='leadreport'>"; 
      echo "<thead>
            <tr BGCOLOR =#800000><th>" . _('Sl no:') . "</th> 
            <th>" . _('Lead id') . "</th>
            <th>" . _('Name') . "</th>
            <th>" . _('Place') . "</th>
            <th>" . _('Date') . "</th>
            <th>" . _('Enquiry Type') . "</th>
            <th>" . _('Output Type') . "</th>
            <th>" . _('Edit') . "</th>
            </tr></thead>";

  $sql="SELECT bio_cust.cust_id AS custid,  
  bio_cust.custname AS custname,               
  bio_cust.houseno AS houseno,               
  bio_cust.housename AS housename,
  bio_cust.area1 AS place,
  bio_cust.custphone AS custphone,
  bio_cust.custmob AS custmob,
  bio_enquirytypes.enqtypeid AS enqtypeid,
  bio_enquirytypes.enquirytype AS enqtype,
  bio_leads.outputtypeid AS outputtypeid,
  bio_outputtypes.outputtypeid,
  bio_outputtypes.outputtype AS outputtype,
  bio_cust.district AS districtid,
  bio_leads.leadid AS leadid, 
  bio_leads.leaddate AS leaddate,
  bio_leadteams.teamname AS teamname,
  bio_leadsources.sourcename AS sourcename,
  bio_cust.custmail AS custmail,
  bio_leads.advanceamount AS advance,
  bio_cust.state AS state,
  bio_district.district AS district,
  bio_cust.area1 AS area1,
  bio_cust.area2 AS area2,
  bio_office.id AS officeid,
  bio_office.office AS office,
  bio_leads.remarks AS remarks
FROM bio_cust,
  bio_leads,
  bio_leadteams,
  bio_leadsources,
  bio_enquirytypes,
  bio_district,
  bio_office,
  bio_outputtypes   
WHERE bio_cust.cust_id=bio_leads.cust_id 
  AND bio_leadteams.teamid=bio_leads.teamid 
  AND bio_leads.enqtypeid=bio_enquirytypes.enqtypeid
  AND bio_leads.outputtypeid=bio_outputtypes.outputtypeid  
  AND bio_leadsources.id=bio_leads.sourceid  
  AND bio_district.did=bio_cust.district
  AND bio_district.stateid=bio_cust.state
  AND bio_district.cid=bio_cust.nationality
  AND bio_office.id=bio_leadsources.officeid 
";   
if(isset($_POST['filterbut']))
 {  
    if ((isset($_POST['datef'])) && (isset($_POST['datet'])))   {
    if (($_POST['datef']!="") && ($_POST['datet']!=""))  { 
    $sourcetypefrom=FormatDateForSQL($_POST['datef']);   
    $sourcetypeto=FormatDateForSQL($_POST['datet']);
    $sql.=" AND bio_leads.leaddate BETWEEN '".$sourcetypefrom."' AND '".$sourcetypeto."'";
    }  }
    $officeid=$_POST['off'];
  //  echo $officeid;
    if (isset($_POST['byname']))  {        
    if ($_POST['byname']!='')   
    $sql.= " AND bio_cust.custname LIKE '%".$_POST['byname']."%'";   
    }                                                                    
            
    if (isset($_POST['byplace'])) {
    if ($_POST['byplace']!='') 
    $sql.= " AND bio_cust.area1 LIKE '%".$_POST['byplace']."%'"; 
    }
    
    if (isset($_POST['team']))    {
    if (($_POST['team']!='')&&($_POST['team']!='0'))
    $sql.=" AND bio_leadteams.teamid='".$_POST['team']."'";    
    }
    
    if (isset($_POST['off']))    {
    if (($_POST['off']!='')&&($_POST['off']!='0'))
    $sql.=" AND bio_leadsources.officeid=".$officeid;    
    }
    
    if (isset($_POST['leadsrc'])) {
    if (($_POST['leadsrc']!='ALL') && ($_POST['leadsrc']!=0))
    $sql.=" AND bio_leads.sourceid='".$_POST['leadsrc']."'";
    }
    
   if (isset($_POST['enquiry'])) {
    if (($_POST['enquiry']!='ALL') && ($_POST['enquiry']!=0))
    $sql.=" AND bio_enquirytypes.enqtypeid='".$_POST['enquiry']."'";
    } 
        
 }
$sql.=" ORDER BY leaddate DESC";       
    function convertsqldatefordis($d) {
$array=explode('-',$d);
$dd="$array[2]/$array[1]/$array[0]";
return $dd;        
}   
    $result=DB_query($sql,$db);     
   echo '<tbody>';
    echo '<tr>';                                       
     
          $no=0;
          $k=0; 
          while($myrow=DB_fetch_array($result))
          {    
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
        $leadid=$myrow['leadid']; $enq=$myrow['enqtypeid']; 
          echo "<input type='hidden' id='leadid' name='leadid' value='$leadid'>";
          
printf("<td cellpading=2>%s</td>
        <td>%s</td>
        <td>%s</td>
        <td>%s</td>
        <td>%s</td>
        <td>%s</td>
        <td>%s</td> 
        <td><a  style='cursor:pointer;'  onclick=showCD2('$leadid','$enq')>" . _('View') . "</a></td>  
        </tr>",
        $no,
        $myrow['leadid'],
        $myrow['custname'],
        $myrow['place'],
        convertsqldatefordis($myrow['leaddate']),
        $myrow['enqtype'],
        $myrow['outputtype'],

        $_SERVER['PHP_SELF'] . '?' . SID,$myrow[0]);
        }                
                     
              
      echo '</tbody>';
   
      echo '</table>';  
      echo '</div>'; 
      echo '<input type="submit" name="View as excel" id="view" value="view as excel">'; 
      echo '</form>';
      echo "</fieldset>";
      echo "</td></tr></table>";             
//include('includes/footer.inc');
// if(isset($_POST['excelview'])){ 
//$filename="sdfsdfsdf.csv";
//  $header= "Slno".","."Name".","."Customer Name".","."Team".","."Lead Date".","."\n";"\n";
//    $data='';
//    $slno=1;



















 
?>
