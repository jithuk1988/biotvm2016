<?php 
$PageSecurity=80;
include('includes/session.inc');
$debtorno=0;

if($_GET['phone'])
{
    
$sqlphone=" SELECT `debtorno` FROM `custbranch` WHERE 
`faxno` LIKE '".$_GET['phone']."' or 
phoneno like '".$_GET['phone']."'";//
     $resultphone=DB_query($sqlphone,$db);
     
      $ListCount2 = DB_num_rows($resultphone);
      $tp=8;
if($ListCount2!=0)
{
       while($myphone=DB_fetch_array($resultphone))
    { 
    
  $sqld="Select orderno from salesorders where debtorno='".$myphone['debtorno']."'";
   $resultord=DB_query($sqld,$db);
    $Countm = DB_num_rows($resultord);
        if($Countm==0)
        {
               $sqld2="Select orderno from bio_oldorders where debtorno='".$myphone['debtorno']."'";
   $resultord2=DB_query($sqld2,$db);
    $Countm2 = DB_num_rows($resultord2);
          if($Countm2!=0)
        {
           while($myordo=DB_fetch_array($resultord2))
                 { 
                   $_GET['orderno']= $myordo['orderno'];
                   $tp=0;   //Order Type: 1- New Order 0- Old Order
                }  
        }  }  
        else
        {
             while($myord=DB_fetch_array($resultord))
              $_GET['orderno']= $myord['orderno'];
              $tp=1;   //Order Type: 1- New Order 0- Old Order
        }
        
        
    }
    
//    echo  $_GET['orderno']."<br>";
   // echo   $tp;
    $sql="SELECT `ticketno` FROM `bio_incidents` WHERE `orderno`= '".$_GET['orderno']."'";
$Sele=DB_query($sql,$db);

while($Sele=DB_fetch_array($Sele))
{
   $tickid=$Sele['ticketno']; 
} 
}
else
{
   $sqll=" SELECT `cust_id` FROM `bio_incident_cust` WHERE `custphone` like '".$_GET['phone']."' or `landline` like '".$_GET['phone']."' ";
   $resultphone=DB_query($sqll,$db);
   $ListCo = DB_num_rows($resultphone);
   if($ListCo!=0)
   {
      while($myphone=DB_fetch_array($resultphone))
    { 
   $cust_id=$myphone['cust_id']; 
    }
    
    

    $sql="SELECT `ticketno` FROM `bio_incidents` WHERE `cust_id`= $cust_id";
$Sele=DB_query($sql,$db);

while($Sele=DB_fetch_array($Sele))
{
   $_GET['ticket']=$Sele['ticketno']; 
} 



   }else{
        $msg1= 'Error Phone Number is  <b>'.$_GET['phone'].'</b>';      
        prnMsg($msg1,'error');      
    
   }
}     
    
        


    
           
}
if($_GET['custid'])
{

 $sqlcustid=" SELECT `debtorno` FROM `custbranch` WHERE 
`debtorno` LIKE '".$_GET['custid']."'";//
     $result=DB_query($sqlcustid,$db);
     
      $ListCount2 = DB_num_rows($result);
      $tp=8;
if($ListCount2!=0)
{
       while($mycustid=DB_fetch_array($result))
    { 
    
  $sqld="Select orderno from salesorders where debtorno='".$mycustid['debtorno']."'";
   $resultord=DB_query($sqld,$db);
    $Countm = DB_num_rows($resultord);
        if($Countm==0)
        {
               $sqld2="Select orderno from bio_oldorders where debtorno='".$mycustid['debtorno']."'";
   $resultord2=DB_query($sqld2,$db);
    $Countm2 = DB_num_rows($resultord2);
          if($Countm2!=0)
        {
           while($myordo=DB_fetch_array($resultord2))
                 { 
                   $_GET['orderno']= $myordo['orderno'];
                   $tp=0;   //Order Type: 1- New Order 0- Old Order
                }  
        }  }  
        else
        {
             while($myord=DB_fetch_array($resultord))
              $_GET['orderno']= $myord['orderno'];
              $tp=1;   //Order Type: 1- New Order 0- Old Order
        }
        
        
    }
    
//      $_GET['orderno']."<br>";
   //    $tp;
 $sql="SELECT `ticketno` FROM `bio_incidents` WHERE `orderno`= '".$_GET['orderno']."' order by ticketno asc";
$Sele=DB_query($sql,$db);
//echo $Counorn = DB_num_rows($Sele);
 //while($mytkt=DB_fetch_array($Sele))
 //echo $tickid=$mytkt['ticketno'][0]; 
 /*if($Counorn==0)
 {
 echo  $sql="SELECT `ticketno` FROM `bio_incidents`  WHERE `debtorno` LIKE '".$mycustid['debtorno']."'";
   $Seles=DB_query($sql,$db);
   $tickid=$Seles['ticketno']; 
 }*/
 
while($Seles=DB_fetch_array($Sele))
{
   $tickid=$Seles['ticketno']; 
}
$_GET['ticket']= $tickid; 
}
else
{
  
        $msg1= 'Error Customer ID is  <b>'.$_GET['custid'].'</b>';      
        prnMsg($msg1,'error');      
    
  
}     

    
/*    
$sqlphone=" SELECT `orderno`
FROM `salesorders`
WHERE `debtorno` like '".$_GET['custid']."'";//
     $resultphone=DB_query($sqlphone,$db);
     
 $ListCount3 = DB_num_rows($resultphone);
if($ListCount3!=0)
{
        while($myphone=DB_fetch_array($resultphone))
    { 
   $order=$myphone['orderno']; 
  $_GET['orderno']=$myphone['orderno']; 
    }
$sql="SELECT `ticketno` FROM `bio_incidents` WHERE `orderno`= '".$_GET['orderno']."'";
$Sele=DB_query($sql,$db);

while($Sele=DB_fetch_array($Sele))
{
   $tickid=$Sele['ticketno']; 
} 
}else
{
      $msg1= 'Error Customer is  <b>'.$_GET['custid'].'</b>';      
        prnMsg($msg1,'error');      
    
}*/
  

    
           
}


if($_GET['ticket']!="")
{

 $sql="SELECT `orderno` FROM `bio_incidents` WHERE `ticketno`= '".$_GET['ticket']."'";
$Sele=DB_query($sql,$db);

while($Sele=DB_fetch_array($Sele))
{
   $order=$Sele['orderno']; 
} 
if($order==0 or $order!=0)
{
 $sqlticket=" SELECT 
bio_incidents.`ticketno`,
bio_incidents.`cust_id`,
bio_incidents.`description`,
bio_incidents.`type`,
bio_incidents.`orderno`,
bio_incidents.`createdon`,     
bio_incident_cust.custname,
bio_incident_cust.custphone,
bio_incident_cust.landline,
bio_incident_cust.custmail,
bio_incident_cust.houseno,
bio_incident_cust.housename,
bio_incident_cust.area1,
bio_incident_cust.area2,
bio_incident_cust.pin,
bio_incident_cust.nationality,
bio_incident_cust.state,
bio_incident_cust.district,
bio_incident_cust.debtorno,
bio_incident_cust.taluk,
bio_incident_cust.LSG_type, 
bio_incident_cust.LSG_name,
bio_incident_cust.block_name, 
bio_incident_cust.LSG_ward, 
bio_incident_cust.village,

bio_complainttypes.complaint,
bio_incidenttype.description as typeofcom,


bio_incidentstatus.status,
bio_incidents.status as statusno,
stockmaster.description as plant_name,


bio_corporation.corporation,bio_municipality.municipality,bio_panchayat.name


FROM `bio_incidents`
left join `bio_incident_cust` on bio_incident_cust.cust_id=bio_incidents.cust_id
left join salesorderdetails on salesorderdetails.orderno=bio_incidents.`orderno`
left join stockmaster on stockmaster.stockid=salesorderdetails.stkcode
left join salesorders on salesorders.orderno=bio_incidents.`orderno`
left join bio_incidentstatus on bio_incidentstatus.id=bio_incidents.status
left join bio_incidenttype on bio_incidenttype.`id`=bio_incidents.type
left join bio_complainttypes on bio_complainttypes.id=bio_incidents.title




LEFT JOIN bio_corporation ON bio_corporation.district = bio_incident_cust.LSG_name AND bio_corporation.district = bio_incident_cust.district AND bio_corporation.state = bio_incident_cust.state AND bio_corporation.country = bio_incident_cust.nationality
LEFT JOIN bio_municipality ON bio_municipality.id = bio_incident_cust.LSG_name AND bio_municipality.district = bio_incident_cust.district AND bio_municipality.state = bio_incident_cust.state AND bio_municipality.country = bio_incident_cust.nationality
LEFT JOIN bio_panchayat ON bio_panchayat.id = bio_incident_cust.block_name AND bio_panchayat.block = bio_incident_cust.LSG_name AND bio_panchayat.district = bio_incident_cust.district AND bio_panchayat.state = bio_incident_cust.state AND bio_panchayat.country = bio_incident_cust.nationality


WHERE bio_incidents.`ticketno`= '".$_GET['ticket']."' order by bio_incidents.`ticketno` asc";//




$resulticket=DB_query($sqlticket,$db);
  $ListCount = DB_num_rows($resulticket);
while($lastcomp=DB_fetch_array($resulticket))
{ 
$orderno=$lastcomp['orderno'];
 $custid=$lastcomp['cust_id'];
  $last_tickno=$lastcomp['ticketno'];
   $last_descri=$lastcomp['description'];
    $last_creat=$lastcomp['createdon'];
     $last_remarks=$lastcomp['remarks'];
     $typeofcom=$lastcomp['typeofcom'];
     $title=$lastcomp['complaint'];
     $custname=$lastcomp['custname'];
     $phone=$lastcomp['custphone'];
    
    $plantname=$lastcomp['plant_name'];
    $comstatus=$lastcomp['status'];
     $statusno=$lastcomp['statusno'];
     $landline=$lastcomp['landline'];
     $custmail=$lastcomp['custmail'];
     $houseno=$lastcomp['houseno'];
     $housename=$lastcomp['housename'];
     $area1=$lastcomp['area1'];
     $area2=$lastcomp['area2'];
     $pin=$lastcomp['pin'];
     $nationality=$lastcomp['nationality'];
     $state=$lastcomp['state'];
     $district=$lastcomp['district'];
     $debtorno=$lastcomp['debtorno'];
     $taluk=$lastcomp['taluk'];
     $LSG_type=$lastcomp['LSG_type'];
     $LSG_name=$lastcomp['LSG_name'];
     $block_name=$lastcomp['block_name'];
     $LSG_ward=$lastcomp['LSG_ward'];
     $village=$lastcomp['village'];
     
     
       if($lastcomp['LSG_type']==1){
         $lsgd=$lastcomp['corporation']."(C)";
     }elseif($lastcomp['LSG_type']==2){
         $lsgd=$lastcomp['municipality']."(M)";
     }elseif($lastcomp['LSG_type']==3){
         if($lastcomp['block_name']!=0 || $lastcomp['LSG_name']!=0){
         $lsgd=$lastcomp['name']."(P)";
         }
     }elseif($lastcomp['LSG_type']==0){
         $lsgd="";
     }
     

 }
   echo'  <input type=hidden name="cust_id" id="cust_id" value="'.$custid.'">';
    
 echo'  <input type=hidden name="landno4" id="landno4" value="'.$landline.'">';
echo'  <input type=hidden name="email4" id="email4" value="'.$custmail.'">';
echo'  <input type=hidden name="Houseno4" id="Houseno4" value="'.$houseno.'">';
echo'  <input type=hidden name="HouseName4" id="HouseName4" value="'.$housename.'">';
echo'  <input type=hidden name="Area14" id="Area14" value="'.$area1.'">';
echo'  <input type=hidden name="Area24" id="Area24" value="'.$area2.'">';
echo'  <input type=hidden name="Pin4" id="Pin4" value="'.$pin.'">';
echo'  <input type=hidden name="country4" id="country4" value="'.$nationality.'">';
echo'  <input type=hidden name="State4" id="State4" value="'.$state.'">';
echo'  <input type=hidden name="District4" id="District4" value="'.$district.'">';
echo'  <input type=hidden name="debtorno4" id="debtorno4" value="'.$debtorno.'">';
echo'  <input type=hidden name="taluk4" id="taluk4" value="'.$taluk.'">';
echo'  <input type=hidden name="lsgType4" id="lsgType4" value="'.$LSG_type.'">';
echo'  <input type=hidden name="lsgName4" id="lsgName4" value="'.$LSG_name.'">';
echo'  <input type=hidden name="gramaPanchayath4" id="gramaPanchayath4" value="'.$block_name.'">';
echo'  <input type=hidden name="lsgWard4" id="lsgWard4" value="'.$LSG_ward.'">';
echo'  <input type=hidden name="village4" id="village4" value="'.$village.'">';

    
}
 if($order>0) { 
     $_GET['orderno']=$order; 
     
 
 }

}



if(isset($_GET['orderno']) ) 
{
    if($tp==1)
    {
 $sql="SELECT 
 salesorders.orderno,
 salesorders.debtorno,
 custbranch.brname,
 debtorsmaster.`cid`,
 debtorsmaster.`stateid`,
 debtorsmaster.`did`,
concat(custbranch.phoneno,'/',custbranch.faxno) as phone,
debtorsmaster.LSG_type,
stockmaster.description,
bio_installation_status.plant_status,



bio_corporation.corporation,bio_municipality.municipality,bio_panchayat.name



FROM `custbranch`
inner join salesorders on salesorders.orderno='".$_GET['orderno']."'
left join debtorsmaster on custbranch.debtorno=  debtorsmaster.debtorno
left join salesorderdetails on salesorderdetails.orderno=salesorders.orderno
left join stockmaster on salesorderdetails.stkcode=stockmaster.stockid 
left join bio_installation_status on  bio_installation_status.orderno=salesorders.orderno



LEFT JOIN bio_corporation ON bio_corporation.district = debtorsmaster.LSG_name AND bio_corporation.district = debtorsmaster.did AND bio_corporation.state = debtorsmaster.stateid AND bio_corporation.country = debtorsmaster.cid
LEFT JOIN bio_municipality ON bio_municipality.id = debtorsmaster.LSG_name AND bio_municipality.district = debtorsmaster.did AND bio_municipality.state = debtorsmaster.stateid AND bio_municipality.country = debtorsmaster.cid
LEFT JOIN bio_panchayat ON bio_panchayat.id = debtorsmaster.block_name AND bio_panchayat.block = debtorsmaster.LSG_name AND bio_panchayat.district = debtorsmaster.did AND bio_panchayat.state = debtorsmaster.stateid AND bio_panchayat.country = debtorsmaster.cid






WHERE debtorsmaster.`debtorno`=salesorders.debtorno ";

    }
    else if (tp==0)
    {
        $sql="SELECT 
 bio_oldorders.orderno,
 bio_oldorders.debtorno,
 custbranch.brname,
 debtorsmaster.`cid`,
 debtorsmaster.`stateid`,
 debtorsmaster.`did`,
concat(custbranch.phoneno,'/',custbranch.faxno) as phone,
debtorsmaster.LSG_type,
stockmaster.description,



bio_corporation.corporation,bio_municipality.municipality,bio_panchayat.name



FROM `custbranch`
inner join bio_oldorders on bio_oldorders.orderno='".$_GET['orderno']."'
left join debtorsmaster on custbranch.debtorno=  debtorsmaster.debtorno
left join stockmaster on bio_oldorders.plantid=stockmaster.stockid 
LEFT JOIN bio_corporation ON bio_corporation.district = debtorsmaster.LSG_name AND bio_corporation.district = debtorsmaster.did AND bio_corporation.state = debtorsmaster.stateid AND bio_corporation.country = debtorsmaster.cid
LEFT JOIN bio_municipality ON bio_municipality.id = debtorsmaster.LSG_name AND bio_municipality.district = debtorsmaster.did AND bio_municipality.state = debtorsmaster.stateid AND bio_municipality.country = debtorsmaster.cid
LEFT JOIN bio_panchayat ON bio_panchayat.id = debtorsmaster.block_name AND bio_panchayat.block = debtorsmaster.LSG_name AND bio_panchayat.district = debtorsmaster.did AND bio_panchayat.state = debtorsmaster.stateid AND bio_panchayat.country = debtorsmaster.cid






WHERE debtorsmaster.`debtorno`=bio_oldorders.debtorno " ;
    }
   // echo $sql;
 $result=DB_query($sql,$db);

 //$myrow2=DB_fetch_row($result);
 while($myrow1=DB_fetch_array($result))
    { 
$custname=$myrow1['brname'];
$phone=$myrow1['phone'];
// $lsgd=$myrow1['LSG_type'];
 $plantname=$myrow1['description'];
 $status=$myrow1['plant_status'];
 $debtorno=$myrow1['debtorno'];
 $cid=$myrow1['cid'];
 $stateid=$myrow1['stateid'];
 $did=$myrow1['did'];
 $orderno=$myrow1['orderno'];
 
      if($myrow1['LSG_type']==1){
         $lsgd=$myrow1['corporation']."(C)";
     }elseif($myrow1['LSG_type']==2){
         $lsgd=$myrow1['municipality']."(M)";
     }elseif($myrow1['LSG_type']==3){
         
         $lsgd=$myrow1['name']."(P)";
        
     }elseif($lastcomp['LSG_type']==0){
         $lsgd="";
     }
     
    }
  echo'  <input type=hidden name="cid" id="cid" value="'.$cid.'">';
  echo'  <input type=hidden name="stateid" id="stateid" value="'.$stateid.'">';
  echo'  <input type=hidden name="did" id="did" value="'.$did.'">';
  echo'  <input type=hidden name="orderno" id="orderno" value="'.$orderno.'">';
  echo'  <input type=hidden name="debtorno4" id="debtorno4" value="'.$debtorno.'">';
  
  $sql="SELECT `ticketno` FROM `bio_incidents` WHERE `orderno`= '".$_GET['orderno']."' order by ticketno desc";
$Sele=DB_query($sql,$db);

while($Sele=DB_fetch_array($Sele))
{
   $tickid=$Sele['ticketno']; 
} 

    

}
if($tickid!="")
{
    $tickdetails=" SELECT 
bio_incidents.`ticketno`,
bio_incidents.`cust_id`,
bio_incidents.`description`,
bio_incidents.`type`,
bio_incidents.`orderno`,
bio_incidents.`createdon`,
bio_complainttypes.complaint,
bio_incidentstatus.status,
bio_incidenttype.description as typeofcom,
bio_incidents.status as statusno    
FROM `bio_incidents`
left join bio_incidenttype on bio_incidenttype.`id`=bio_incidents.type
left join bio_incidentstatus on bio_incidentstatus.id=bio_incidents.status
left join bio_complainttypes on bio_complainttypes.id=bio_incidents.title
WHERE bio_incidents.`ticketno`= $tickid  " ;

    
 $retick=DB_query($tickdetails,$db);
 
 
  $ListCount = DB_num_rows($retick); 
while($lastcomp=DB_fetch_array($retick))
    { 


  $last_tickno=$lastcomp['ticketno'];
   $last_descri=$lastcomp['description'];
    $last_creat=$lastcomp['createdon'];
     $last_remarks=$lastcomp['remarks'];
     $typeofcom=$lastcomp['typeofcom'];
     $title=$lastcomp['complaint'];
     $comstatus=$lastcomp['status'];
     $statusno=$lastcomp['statusno'];
    }
}


if($custname!="")
{
echo'<table width="380px"  >';
 echo'<input type="hidden" name="ticketno" id="ticketno" value='.$last_tickno.' >';
//echo''.$debtorno.'';
if($debtorno!='0' AND $debtorno!="" )
{
 //echo '<input hidden type="text" name="deno" id="deno" value="'.$debtorno.'" >'; 
echo '<tr ><td colspan=2><a style=cursor:pointer; onclick=Customer_Maintenance("'.$debtorno.'")>Modify Customer Details</a></td></tr>';
echo '<tr ><td colspan=2><a style=cursor:pointer; onclick=history("'.$debtorno.'")><blink><div style="color:red;font-weight:bold">View Detailed History</div></blink></a></td></tr>';
}
Echo'<tr><td><b>Customer Name :</td><td>
<input type="hidden" name="custname" id="custname" style="width:230px" value="'.$custname.'">'.$custname.'</td></tr>
<tr><td><b>Phone No :</td><td>
<input type="hidden"  name="phno" id="phno" value="'.$phone.'" style="width:230px" >'.$phone.'</td></tr>
<tr><td><b>LSGD </td><td><b>: '.$lsgd.'</b></td></tr>
<tr><td><b>Plant name </td><td><b>: '.$plantname.'</b></td></tr>
<tr><td><b>Plant Status </td>';

if($orderno=='0' or $orderno=='' )
{
   echo'<td><b>:No Order</b></td></tr>';   
}
else if($status==1)
{
  echo'<td><b>: Working</b></td></tr>';  
}
else if(isset($orderno) and $orderno =="0")
{
 echo'<td><b>: Not working</b></td></tr>';   
}
 

/*echo'
<tr><td><b>Category :</td><td>
<input type="text" name="" id="" value="'.$cater.'"></td>

</tr>';
*/
//echo'<tr><td>';



if(isset($orderno) and $orderno!="0" )
 { 
$sqldoc="SELECT count( bio_documentlist.status ) , bio_document_master.document_name
FROM bio_documentlist, bio_document_master
WHERE bio_documentlist.orderno =". $orderno ."
AND bio_documentlist.docno = bio_document_master.doc_no
AND bio_documentlist.status <1";
//echo "<input type='hidden' id=order value='".$orderno."'>";
$result_doc=DB_query($sqldoc,$db);
$row=DB_fetch_array($result_doc);
$num=$row[0];

if($num>0)
{
    echo '<tr ><td colspan=2><blink><a style=cursor:pointer; onclick=docview("'.$debtorno.'")>' . _('Document pending:') .''.$num.'</a></blink></td></tr>'; 
}
if($tp==1)
{
 $sql_bal=" SELECT
    `salesorders`.`orderno`
    , `custbranch`.`brname`
    , `orderamount`.`debtorno`
    , `orderamount`.`ordervalue`
    ,bio_installation_status.installed_date
    , `orderamount`.`orddate`
    , `debtorpaid`.`paid`
    , `orderplant`.`stkcode` as 'stockid'
    , IFNULL(orderamount.ordervalue- ifnull (`debtorpaid`.`paid`,0)-ifnull(ordersubsidy.totsubsidy,0),0) AS 'balance'
    , `salesorders`.`orderno`
    , `salesorders`.`orderno`
    , `salesorders`.`orderno`
   ,  `orderamount`.`ordervalue`- ifnull(ordersubsidy.totsubsidy,0) as 'netpayable'    ,  
  `salesorders`.`contactphone` , 
  `orderamount`.`debtorno` 
FROM
    `salesorders`
    INNER JOIN `custbranch` 
        ON (`salesorders`.`debtorno` = `custbranch`.`debtorno`)
        left JOIN debtortrans ON (custbranch.debtorno=debtortrans.debtorno)
    LEFT JOIN `ordersubsidy` 
        ON (`salesorders`.`orderno` = `ordersubsidy`.`orderno`)
    inner JOIN `orderamount` 
        ON (`salesorders`.`orderno` = `orderamount`.`orderno`)
    left JOIN `debtorpaid` 
        ON (`salesorders`.`debtorno` = `debtorpaid`.`debtorno`)
    inner JOIN `orderplant` 
        ON (`salesorders`.`orderno` = `orderplant`.`orderno`)
        left JOIN `bio_installation_status` 
        ON (`salesorders`.`orderno` = `bio_installation_status`.`orderno`)
                WHERE  orderamount.debtorno like 'D%' AND `salesorders`.`orderno`='".$orderno."' group by `salesorders`.`orderno`"; 
   $result_bal=DB_query($sql_bal,$db);
$row=DB_fetch_array($result_bal);
$balance=$row['balance'];
if($balance>0)
{
     echo '<td><blink><span class="style1"><b>' . _('Payment pending:') .''.$balance.'</b></span><blink></td></tr>';  
}
    
 }
 }
     
}
if(isset($orderno) and $orderno!="0" )
 {    
  $ord=$orderno;
     if(isset($ord))
     {       
  $sql="select bio_amc.start_date,bio_amc.end_date
 FROM bio_amc
 LEFT JOIN salesorders ON bio_amc.debtorno=salesorders.debtorno           
   WHERE salesorders.orderno like $ord " ; 
          //  LEFT JOIN salesorders ON bio_amc.debtorno=salesorders.debtorno  
     
  
$result=DB_query($sql,$db);
 $myrow=DB_fetch_array($result);
    $ListCount = DB_num_rows($result);
    
      if($ListCount >0)
  { 
/*    if($start_date!=NULL AND $start_date!='0000-00-00')
  {  */ 
 $start_date=$myrow['start_date'];  
 $end_date=$myrow['end_date'];  

 $valid = "AMC ";
 $flage=2;
 $start_date= ConvertSQLDate($start_date);  
         $end_date= ConvertSQLDate($end_date);

   echo"<tr><td width=40%>AMC Start Date</td>";
   echo"<td><input type='text' readonly name='custname' id='custname' tabindex=1 value='".$start_date."' style='width:120px' ></td></tr>";
      
   echo"<tr><td width=40%>AMC Expiry Date</td>";
echo"<td><input type='' name='custname' readonly id='custname' tabindex=1 value='".$end_date."' style='width:120px' ></td></tr>";
//  echo"<tr><td width=40%>AMC Status</td>";
//echo"<td><input type='text' readonly name='custname' id='custname' tabindex=1 value='".$valid."' style='width:120px' ></td></tr>";   
  //  echo"</table>"  ;    
  //}
  }
  else
  {
      
   if($tp==1)
   {
$sql="select bio_installation_status.installed_date FROM bio_installation_status WHERE  bio_installation_status.orderno like $ord  and bio_installation_status.orderno not in (SELECT `new_orderno` FROM `bio_amc`)  " ; 
   }
   else if ($tp==0)
   {
   $sql="select bio_oldorders.installationdate FROM bio_oldorders WHERE orderno=".$ord ; 
    
   }

 $result=DB_query($sql,$db);
// echo $sql;
 $myroq=DB_fetch_array($result);
   $installed_date=$myroq['0'];
   
   // if($installed_date!=NULL AND $installed_date!='0000-00-00')
 // {  
  $date = strtotime(date("Y-m-d", strtotime($installed_date)) . " +1 year");
   $closedate=date('Y-m-d',$date);
  $today=date("Y-m-d"); 
  
  $installed_date= ConvertSQLDate($installed_date); 
   $closedate= ConvertSQLDate($closedate); 
     echo"<tr><td width=40%>Installed Date</td>";
echo"<td><input type='text' readonly name='custname' id='custname' tabindex=1 value='".$installed_date."' style='width:120px' ></td></tr>";
echo"<tr><td width=40%>Warranty Expiry Date</td>";
echo"<td><input type='' name='custname' readonly id='custname' tabindex=1 value='".$closedate."' style='width:120px' ></td></tr>";
 // }
  }
  
  
    }

/*     
   
  $ord=$orderno;
  if(isset($ord))
  {
      
      if($tp==1)
      {
 $sql="select bio_installation_status.installed_date as installed_date  FROM bio_installation_status WHERE bio_installation_status.orderno like $ord  " ;
      }
      else if($tp==0)
      {
        $sql="  SELECT `installationdate` as installed_date FROM `bio_oldorders` WHERE `orderno` like $ord  ";
      }
  }
 //echo $sql;
 $result=DB_query($sql,$db);
// echo $sql;
 $myroq=DB_fetch_array($result);
   $installed_date=$myroq['installed_date'];
  
  $date = strtotime(date("Y-m-d", strtotime($installed_date)) . " +1 year");
   $closedate=date('Y-m-d',$date);
  $today=date("Y-m-d"); 
  
  
  
  if($installed_date!=NULL AND $installed_date!='0000-00-00')
  {  
       $installed_date= ConvertSQLDate($installed_date);   
  //   echo "<table style='border:0px solid ;width:90%;'>";
     echo"<tr><td width=40%>Installed Date</td>";
echo"<td><input type='text' readonly name='custname' id='custname' tabindex=1 value='".$installed_date."' style='width:120px' ></td></tr>";
 if ($closedate > $today) 
 { $valid = "Warranty";
 $flage=0;
 $closedate= ConvertSQLDate($closedate); 
    echo"<tr><td width=40%>Warranty Expiry Date</td>";
echo"<td><input type='' name='custname' readonly id='custname' tabindex=1 value='".$closedate."' style='width:120px' ></td></tr>";
  // echo"<tr><td width=40%>Status</td>";
//echo"<td><input type='text' readonly name='custname' id='custname' tabindex=1 value='".$valid."' style='width:120px' ></td></tr>";   
//  echo"</table>"  ;
 } 
 else 
 { 
// $flage=1;
        
      // echo "<table style='border:0px solid ;width:90%;'>";
     if(isset($ord))
     {       
  $sql="select bio_amc.start_date,bio_amc.end_date
 FROM bio_amc
 LEFT JOIN salesorders ON bio_amc.debtorno=salesorders.debtorno           
   WHERE salesorders.orderno like $ord " ; 
          //  LEFT JOIN salesorders ON bio_amc.debtorno=salesorders.debtorno  
     }
     else     if(isset($_GET['custid']))
          {
               $sql="select bio_amc.start_date,bio_amc.end_date
 FROM bio_amc
 LEFT JOIN salesorders ON bio_amc.debtorno=salesorders.debtorno           
   WHERE salesorders.orderno in (SELECT `orderno` FROM `salesorders` where `debtorno` LIKE '".$_GET['custid']."') " ;  
          }
$result=DB_query($sql,$db);
 $myrow=DB_fetch_array($result);
 $start_date=$myrow['start_date'];  
 $end_date=$myrow['end_date'];  
  
 if ($end_date > $today) 
 { $valid = "AMC ";
 $flage=2;
 $start_date= ConvertSQLDate($start_date);  
         $end_date= ConvertSQLDate($end_date);
   
    // echo"<tr><td width=40%>AMC Installed Date</td>";
//echo"<td><input type='text' readonly name='custname' id='custname' tabindex=1 value='".$start_date."' style='width:120px' ></td></tr>";
      
   echo"<tr><td width=40%>AMC Expiry Date</td>";
echo"<td><input type='' name='custname' readonly id='custname' tabindex=1 value='".$end_date."' style='width:120px' ></td></tr>";
//  echo"<tr><td width=40%>AMC Status</td>";
//echo"<td><input type='text' readonly name='custname' id='custname' tabindex=1 value='".$valid."' style='width:120px' ></td></tr>";   
  //  echo"</table>"  ;    
 } 
   else
   {
    // echo"</table>" ;  
   }
    }
    
  }*/
  
 }


    if($ListCount!=5){
echo'<tr><td colspan="2">';
echo '<fieldset style="width:350px; height:auto">';  
echo '<legend><b>Last call </b></legend>';
echo'<table>
<tr><td>Call Date</td><td>: '.$last_creat.'</td></tr>
<tr><td>Call Status</td><td>: '.$comstatus.'</td></tr>
<tr><td>Complaint Type</td><td>: '.$typeofcom.'</td></tr>
<tr><td>Complaint Title</td><td>: '.$title.'</td></tr>
<tr><td>Complaint Description</td><td><textarea  name="oldremark" readonly cols="25" rows="3">'.$last_descri.'</textarea></td></tr>
<tr><td>Complaint Remarks</td><td><textarea name="Compremark" id="Compremark" cols="25" rows="2">'.$last_remarks.'</textarea></td></tr>';
echo '<tr><td width=33%>Priority</td>'; 
echo '<td><select name="up_priority" id="up_priority" tabindex=25  style="width:222px" onchange="addPriority(this.value)">';


$sql1="select * from bio_priority";
$result1=DB_query($sql1,$db);
 $f=0;    
while($row1=DB_fetch_array($result1))
{
    if ($row1['id']==$priority) 
    {
       
    echo '<option selected value="';
    
    } else {
        if ($f==0) 
        {
        echo '<option value="0">--SELECT--</option>';
        }
        echo '<option value="';
               $f++;   
    }
    echo $row1['id'].'">'.$row1['priority'];
    echo '</option>';
}

   
echo '</select></td></tr>';

echo '</table>';

if($statusno==3)
{
     $_SESSION["close_compl"]= $statusno;
    echo'<center><input type="submit" name="reload" value="Reload"><input type=reset value="Clear"></center>';    //<input type=submit name="reopen" id="reopen" value="Reopen"  onclick=" if(validate_reopen()==1)return false">
   
}
else{
    $_SESSION["close_compl"]="";
     echo'<center><input type="submit" name="reload" value="Reload"><input type=submit name="update" id="update" value="Update" onclick=" if(validate_update()==1)return false"><input type=button name="newcom" id="newcom" value="New complaint" onclick="changebutten(7) "><input type=reset value="Clear"></center>';
}

echo'</fieldset>';

echo'</td></tr>';
    }else{
        echo'<tr><td colspan="2">';
        echo'<b>NO historys</b>';
         $_SESSION["close_compl"]=3;
        echo'</td></tr>';
    }
//////////////--------left(details) table End-----------
echo'</td></tr></table>';





?>

