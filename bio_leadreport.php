<?php
$PageSecurity = 81;
include('includes/session.inc');
$title = _('Lead Report');
$user_id=$_SESSION['UserID'];
    $sql_emp="SELECT  bio_emp.empname
        FROM  bio_emp,www_users
        WHERE www_users.empid = bio_emp.empid
        AND www_users.userid='".$user_id."'";
      $result_emp=DB_query($sql_emp,$db);
      $myrow_emp=DB_fetch_array($result_emp);
      $empname=$myrow_emp['empname'];
if (!headers_sent()){
        header('Content-type: text/html; charset=' . _('utf-8'));
    }                                                                                                                  
    echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
            "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">';


    echo '<html xmlns="http://www.w3.org/1999/xhtml"><head><title>' . $title . '</title>';
    echo '<link rel="shortcut icon" href="'. $rootpath.'/favicon.ico" />';
    echo '<link rel="icon" href="' . $rootpath.'/favicon.ico" />';
    echo '<meta http-equiv="Content-Type" content="text/html; charset=' . _('utf-8') . '" />';
    echo '<link href="'.$rootpath. '/css/'. $_SESSION['Theme'] .'/default.css" rel="stylesheet" type="text/css" />';   
    echo '<script type="text/javascript" src = "'.$rootpath.'/javascripts/MiscFunctions.js"></script>'; 
    echo '<script type="text/javascript" src = "'.$rootpath.'/javascripts/jquery.js"></script>';
    
    echo '</head>';

    echo '<body bgcolor="#FFFFFF" width="600px">';
   
    echo'<br />';
    
    echo '<font size=5><p align=center>LEAD LIST</p></font>';
    
    
    $sql="SELECT bio_cust.cust_id AS custid,  
  bio_cust.custname AS custname,               
  bio_cust.houseno AS houseno,               
  bio_cust.housename AS housename,
  bio_cust.area1 AS place,
  bio_cust.custphone AS custphone,
  bio_cust.custmob AS custmob,
  bio_leads.enqtypeid AS enqtypeid,
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
  bio_cust.contactperson,
  bio_office.id AS officeid,
  bio_office.office AS office,
  bio_leads.remarks AS remarks,
  bio_status.biostatus
FROM bio_cust,
bio_leads,
bio_leadteams,
bio_leadsources,
bio_enquirytypes,
bio_district,
bio_office,
bio_outputtypes,
bio_status  
WHERE bio_cust.cust_id=bio_leads.cust_id 
AND bio_leadteams.teamid=bio_leads.teamid 
AND bio_leads.enqtypeid=bio_enquirytypes.enqtypeid
AND bio_leads.outputtypeid=bio_outputtypes.outputtypeid  
AND bio_leadsources.id=bio_leads.sourceid  
AND bio_district.did=bio_cust.district
AND bio_district.stateid=bio_cust.state
AND bio_district.cid=bio_cust.nationality
AND bio_office.id=bio_leadsources.officeid
AND bio_status.statusid=bio_leads.leadstatus 
";

if(isset($_POST['filterbut']))
 {
 echo '<table style="background: #FFFFFF"><tr>';
    if ((isset($_POST['datef'])) && (isset($_POST['datet'])))   {
    if (($_POST['datef']!="") && ($_POST['datet']!=""))  { 
    $sourcetypefrom=FormatDateForSQL($_POST['datef']);   
    $sourcetypeto=FormatDateForSQL($_POST['datet']);
    $sql.=" AND bio_leads.leaddate BETWEEN '".$sourcetypefrom."' AND '".$sourcetypeto."'";
    $datef=$_POST['datef'];
    $datet=$_POST['datet'];
    echo"<td>Leads From : $datef  To : $datet</td>";
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
    
    if (isset($_POST['offic']))    {
    if (($_POST['offic']!='')&&($_POST['offic']!='0')){
    $sql.=" AND bio_leadsources.officeid=".$_POST['offic'];
    echo"<td>Office : ".$_POST['offic']."</td>";
    }
    }
    
    if (isset($_POST['enquiry1'])) {
        
    if (($_POST['enquiry1']!='ALL') && ($_POST['enquiry1']!=0)){
    $sql.=" AND bio_enquirytypes.enqtypeid='".$_POST['enquiry1']."'";
    $sql_enq="SELECT enquirytype 
          FROM bio_enquirytypes
          WHERE enqtypeid=".$_POST['enquiry1'];
 $result_enq=DB_query($sql_enq,$db);
 $myrow_enq=DB_fetch_array($result_enq);
 $enq=$myrow_enq['enquirytype'];
    
    echo"<td>Enquiry Type : ".$enq."</td>";
    }
    } 
    
    if (isset($_POST['leadsrc'])) {
    if (($_POST['leadsrc']!='ALL') && ($_POST['leadsrc']!=0))
    $sql.=" AND bio_leads.sourceid='".$_POST['leadsrc']."'";
    }
    if (isset($_POST['LeadStatus'])) {
    if (($_POST['LeadStatus']!=''))
    $sql.=" AND bio_leads.leadstatus='".$_POST['LeadStatus']."'";
    }
    if (isset($_POST['country']) AND ($_POST['country']!='')) {
        if (isset($_POST['State']) AND ($_POST['State']!='')){
            if (isset($_POST['District']) AND ($_POST['District']!='')){
                $sql.=" AND bio_district.stateid=".$_POST['State']."  
                        AND bio_district.cid=".$_POST['country']."
                        AND bio_district.did=".$_POST['District'];
            }
        }
    }
    
    if (isset($_POST['Created'])) {   
    if ($_POST['Created']!=''){
       $sql.=" AND bio_leads.created_by='".$_POST['Created']."'";
    }
    
    }
    
    if (isset($_POST['team']))    {
    if (($_POST['team']!='')&&($_POST['team']!='0'))
    $sql.=" AND bio_leadteams.teamid='".$_POST['team']."'";    
    }
    
    echo"</tr></table>"; 
}
       $sql.=" ORDER by leadid desc";

   echo '<hr>';
    echo '<table style="background: #FFFFFF" width=650px BORDER=1>';
    $tableheader = "<tr style='background:#000000;color:#FFFFFF'>
        <th>" . _('Sl No') . "</th>                                                                   
        <th>" . _('Customer Name/<br>Institution Name') . "</th>
        <th>" . _('Contact Person') . "</th>
        <th>" . _('Contact No') . "</th>
        <th>" . _('Lead Date') . "</th>
        <th>" . _('District') . "</th>
        <th>" . _('Current Status') . "</th>  
        <th width=100px>" . _('Remarks') . "</th>
        </tr>";

    echo $tableheader;
   $slno=1;
   $leadsno=1; 
   
 $result=DB_query($sql,$db);
    while($myrow=DB_fetch_array($result)){
        $custname=$myrow['custname'];
        $contact_person=$custname;
        $cust_mob=$myrow['custmob'];
        $cust_phone=$myrow['custphone'];
        $leaddate=ConvertSQLDate($myrow['leaddate']);
        $district=$myrow['district'];
        $leadstatus=$myrow['biostatus'];
        $remarks=$myrow['remarks'];
        
        $phoneno=$cust_mob; 
        if($cust_phone==""){
            $phoneno=$cust_mob;
            }
        elseif($cust_mob==""){
            $phoneno=$cust_phone;
        }
        elseif($cust_phone=="" AND $cust_mob==""){
            $phoneno==0;
        }
        
        if($myrow['enqtypeid']==2){
           // $custname=$myrow['housename'];
            $contact_person=$myrow['contactperson'];
        }
 /*   
    $rmdailykg=$myrow['rmdailykg'];
      $advanceamount=$myrow['advanceamount'];
      $productservicesid=$myrow['productservicesid'];
      $enqtypeid=$myrow['enqtypeid']; 
      $leadstatus=$myrow['leadstatus']; 
      $leaddate=$myrow['leaddate']; 
      $outputtypeid=$myrow['outputtypeid']; 
      $outputtypeid=$myrow['outputtypeid']; 
      $schemeid=$myrow['schemeid'];
       $schemeid=split(',',$schemeid);
       $count99=count($schemeid);
//      for($i=0;$i<=$count-1;$i++){
//            $sch=$schemeid[$i];
//      $sql99="SELECT scheme FROM bio_schemes WHERE schemeid=$sch";
//      $result99=DB_query($sql99,$db);  
//      while($myrow99=DB_fetch_array($result99)){
//          echo $a[$i]=$myrow99[0].",";
//      }
//       
//          
//      }
//      $schemeid=$myrow['schemeid'];              
      $teamid=$myrow['teamid'];
      $sourceid=$myrow['sourceid'];
      $investmentsize=$myrow['investmentsize'];
      $remarks=$myrow['remarks'];
      $status=$myrow['status']; 
      */
     /* 
              echo "<input type='hidden' name='customerid' value='".$myrow['cust_id']."'>";      
          $sqledt="SELECT * FROM bio_cust WHERE bio_cust.cust_id=".$myrow['cust_id'];
     $result1=DB_query($sqledt,$db);
     $myrow1=DB_fetch_array($result1); 
      $custname=$myrow1['custname'];    
      $homno=$myrow1['houseno'];
      $housename=$myrow1['housename']; 
      $area1=$myrow1['area1'];
      $area2=$myrow1['area2'];       //echo "ssssssssss".$area2."sssssssssssnn";    
      $pin=$myrow1['pin'];
      $nationality=$myrow1['nationality']; 
      $state=$myrow1['state'];
      $district=$myrow1['district'];   
      //echo $custaddress;  $custphone
      $sql1="SELECT bio_district.district 
          FROM bio_district
          WHERE bio_district.stateid=".$state."  
          AND bio_district.cid=".$nationality."
          AND bio_district.did=".$district;     
 $result1=DB_query($sql1,$db);
 $myrow=DB_fetch_array($result1);
 $district=$myrow[0];
 
 $sql_status="SELECT biostatus 
          FROM bio_status
          WHERE statusid=".$leadstatus;
 $result_status=DB_query($sql_status,$db);
 $myrow_status=DB_fetch_array($result_status);
 $leadstatus=$myrow_status['biostatus'];
      
    $pieces=$myrow1['custphone'];  $phpieces = split("-", $pieces,2);  $custcode=$phpieces[0];if($custcode==0 || $custcode==""){$custcode=0;}  
    $custphone=$phpieces[1];
    $phoneno=$custphone;
    $custmobile=$myrow1['custmob'];
    $phoneno=$custmobile;
    if($custphone==""){
        $phoneno=$custmobile;
    }
    elseif($custmobile==""){
        $phoneno=$custphone;
    }
    elseif($custphone=="" AND $custmobile==""){
        $phoneno==0;
    }
    $custemail=$myrow1['custmail'];
//--------------------------------------------------------
if($custphone==""){$custphone=0;}if($custmobile==""){$custmobile=0;} if($custemail==""){$custemail=0;}
 
 */   
    
    printf("<td align=left>%s</td>
        <td align=left>%s</td>
        <td align=left>%s</td>
        <td align=left>%s</td>
        <td align=left>%s</td>
        <td align=left>%s</td>
        <td align=left>%s</td>
        <td align=left>%s</td>
        </tr>",
        $slno,
        $custname,
        $contact_person,
        $phoneno,
        $leaddate,
        $district,
        $leadstatus,
        $remarks);
       $slno++; 
    $leadsno++;
    if($leadsno==17){
      $leadsno=0;  
/*       echo '</table>';

echo'<table width=90% style="background: #FFFFFF">';
echo'<tr>&nbsp;</tr>';
echo'<tr>&nbsp;</tr>';
echo'<tr>&nbsp;</tr>';
echo'<tr border=0>
<td width=35%></td>
<td width=35%></td>
<td width=35%>Signature of</td>
</tr>';


echo'<tr border=0><td><br /><br /></td>
<td><br /><br /></td>
<td><br /><br />'.$empname.'</td>
</tr>';
echo'</table>';
echo '<br>';
echo '<br>';
echo '<br>';
 
 
echo '<font size=5><p align=center>LEAD LIST</p></font>';
    echo '<hr>';
    
    echo '<table style="background: #FFFFFF" width=90% BORDER=1>';

        
       echo $tableheader; */
    }    
    
    }
  
echo '</table>';

echo'<table style="background: #FFFFFF">';
echo'<tr>&nbsp;</tr>';
echo'<tr>&nbsp;</tr>';
echo'<tr>&nbsp;</tr>';
echo'<tr border=0>
<td></td>
<td></td>
<td>Signature of</td>
</tr>';


echo'<tr border=0><td><br /><br /></td>
<td><br /><br /></td>
<td><br /><br />'.$empname.'</td>
</tr>';
echo'</table>';
    
    
   
    echo'</body>';
    echo'</html>';
    
    
?>
