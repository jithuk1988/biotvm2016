<?php
$PageSecurity = 80;
include('includes/session.inc');
//$title = _('');  
include('includes/header.inc');
$debtorno=$_GET['debtorno'];

echo "<br><h3><center>CLIENT HISTORY</center></h3></br>";
 $sqld="Select orderno from salesorders where debtorno='".$debtorno."'";
   $resultord=DB_query($sqld,$db);
    $Countm = DB_num_rows($resultord);
        if($Countm==0)
        {
               $sqld2="Select orderno from bio_oldorders where debtorno='".$debtorno."'";
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

    if($tp==1)
    {
 $sql="SELECT 
 salesorders.orderno,
 salesorders.debtorno,
 custbranch.brname,
 custbranch.braddress1,
 custbranch.braddress2,
 custbranch.braddress3,
 debtorsmaster.`cid`,
 debtorsmaster.`stateid`,
 debtorsmaster.`did`,
concat(custbranch.phoneno,'/',custbranch.faxno) as phone,
debtorsmaster.LSG_type,
stockmaster.description,
bio_installation_status.plant_status,
bio_plant_status.p_status,
bio_district.district,



bio_corporation.corporation,bio_municipality.municipality,bio_panchayat.name



FROM `custbranch`
inner join salesorders on salesorders.orderno='".$_GET['orderno']."'
left join debtorsmaster on custbranch.debtorno=  debtorsmaster.debtorno
left join salesorderdetails on salesorderdetails.orderno=salesorders.orderno
left join stockmaster on salesorderdetails.stkcode=stockmaster.stockid 
left join bio_installation_status on  bio_installation_status.orderno=salesorders.orderno
left join bio_plant_status on bio_installation_status.plant_status=bio_plant_status.p_id
 LEFT JOIN `bio_district` 
        ON (`debtorsmaster`.`cid` = `bio_district`.`cid`) AND (`debtorsmaster`.`stateid` = `bio_district`.`stateid`) AND (`debtorsmaster`.`did` = `bio_district`.`did`)
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
  custbranch.braddress1,
 custbranch.braddress2,
 custbranch.braddress3,
 debtorsmaster.`cid`,
 debtorsmaster.`stateid`,
 debtorsmaster.`did`,
concat(custbranch.phoneno,'/',custbranch.faxno) as phone,
debtorsmaster.LSG_type,
stockmaster.description,
bio_plant_status.p_status,
bio_district.district,



bio_corporation.corporation,bio_municipality.municipality,bio_panchayat.name



FROM `custbranch`
inner join bio_oldorders on bio_oldorders.orderno='".$_GET['orderno']."'
left join debtorsmaster on custbranch.debtorno=  debtorsmaster.debtorno
left join stockmaster on bio_oldorders.plantid=stockmaster.stockid 
left join bio_plant_status on bio_oldorders.currentstatus=bio_plant_status.p_id
 LEFT JOIN `bio_district` 
        ON (`debtorsmaster`.`cid` = `bio_district`.`cid`) AND (`debtorsmaster`.`stateid` = `bio_district`.`stateid`) AND (`debtorsmaster`.`did` = `bio_district`.`did`)
LEFT JOIN bio_corporation ON bio_corporation.district = debtorsmaster.LSG_name AND bio_corporation.district = debtorsmaster.did AND bio_corporation.state = debtorsmaster.stateid AND bio_corporation.country = debtorsmaster.cid
LEFT JOIN bio_municipality ON bio_municipality.id = debtorsmaster.LSG_name AND bio_municipality.district = debtorsmaster.did AND bio_municipality.state = debtorsmaster.stateid AND bio_municipality.country = debtorsmaster.cid
LEFT JOIN bio_panchayat ON bio_panchayat.id = debtorsmaster.block_name AND bio_panchayat.block = debtorsmaster.LSG_name AND bio_panchayat.district = debtorsmaster.did AND bio_panchayat.state = debtorsmaster.stateid AND bio_panchayat.country = debtorsmaster.cid






WHERE debtorsmaster.`debtorno`=bio_oldorders.debtorno " ;
    }

 $result=DB_query($sql,$db);
if(isset($orderno) and $orderno!="0" )
 {    
    if($tp==1)
   {
$sql="select DATE_FORMAT(bio_installation_status.installed_date,'%d-%m-%Y') FROM bio_installation_status WHERE  bio_installation_status.orderno='".$_GET['orderno']."'  and bio_installation_status.orderno not in (SELECT `new_orderno` FROM `bio_amc`)  " ; 
   }
   else if ($tp==0)
   {
   $sql="select DATE_FORMAT(bio_oldorders.installationdate,'%d-%m-%Y') FROM bio_oldorders WHERE orderno='".$_GET['orderno']."'"; 
    
   }
   
   
 $result=DB_query($sql,$db);
// echo $sql;
 $myroq=DB_fetch_array($result);
   $installed_date=$myroq['0'];
   
}
 
 
 //$myrow2=DB_fetch_row($result);
 while($myrow1=DB_fetch_array($result))
    { 
$custname=$myrow1['brname'];
$phone=$myrow1['phone'];
// $lsgd=$myrow1['LSG_type'];
 $plantname=$myrow1['description'];
 $add1=$myrow1['braddress1'];
 $add2=$myrow1['braddress2'];
 $add3=$myrow1['braddress3'];
 $status=$myrow1['p_status'];
 $debtorno=$myrow1['debtorno'];
 $cid=$myrow1['cid'];
 $stateid=$myrow1['stateid'];
 $did=$myrow1['did'];
 $orderno=$myrow1['orderno'];
 $district=$myrow1['district'];
 
     
    }














echo"<div>"; 
echo"<table border='1' cellpadding='1' cellspacing='1' style='width: 556px;'>
	<tbody>
		<thead><tr>
			<th colspan='4' style='text-align: center;'>CUSTOMER ID : <strong>".$debtorno." </strong><strong></strong></td>
		</th></thead>
		<tr>
			<td>Name</td>
			<td>".$custname."</td>
			<td colspan='1' rowspan='3'>Address</td>
			<td>".$add1."</td>
		</tr>
		<tr>
			<td>Contact Numbers</td>
			<td>".$phone."</td>
			<td>".$add2."</td>
		</tr>
		<tr>
			<td>District</td>
			<td>".$district."</td>
			<td>".$add3."</td>
		</tr>
	</tbody>
</table>";
 $sql="SELECT
  ticketno,
  DATE_FORMAT(createdon,'%d-%m-%Y') AS 'DateCreated',
  bio_incidenttype.type AS 'Type',
  bio_incidentstatus.status AS 'status',
   bio_incidents.description AS 'description',
   DATE_FORMAT(closeDate,'%d-%m-%Y') AS 'DateClosed'
FROM bio_incidents
  LEFT JOIN bio_incidenttype
    ON bio_incidenttype.id = bio_incidents.type
    LEFT JOIN bio_incidentstatus ON bio_incidentstatus.id = bio_incidents.status 
WHERE debtorno = '".$debtorno."'";

echo'</div>'; 
echo "<br><br>";

	echo "	<table border='1' cellpadding='1' cellspacing='1' height='103' width='556px'>
			<thead>
				<tr>
					<th colspan='2' scope='col' style='text-align: center;'>
						<strong>Plant Details</strong></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>
						Plant Name</td>
					<td>
						".$plantname."</td>
				</tr>
				<tr>
					<td>
						Plant Status</td>
					<td>
						".$status."</td>
				</tr>
				<tr>
					<td>
						Installed Date</td>
					<td>
						".$installed_date."</td>
				</tr>
			</tbody>
		</table>";


echo "<br><br>";
 $ticketdetails=DB_query($sql, $db); 
 $tc=0;
 $tc=DB_num_rows($ticketdetails);
 $n=1;
if($tc>0)
{
echo "<table border='1' cellpadding='1' cellspacing='1' height='178' style='border-style: hidden; background-color: rgb(230, 230, 250);' width='556px'>
			<thead>
				<tr>
					<th colspan='9' scope='col'>
						<u><strong>Total No of Tickets Registered:".$tc."</strong></u></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td style='text-align: center;'>
						<strong>Sl No</strong></td>
					<td style='text-align: center;'>
						<strong>Ticket No</strong></td>
					<td style='text-align: center;'>
						<strong>Date</strong></td>
					<td style='text-align: center;'>
						<strong>Category</strong></td>
					<td colspan='3' rowspan='1' style='text-align: center;'>
						<strong>Ticket Datails</strong></td>
					<td style='text-align: center;'>
						<strong>Status</strong></td>
						<td style='text-align: center;'>
						<strong>Date Closed</strong></td>
				</tr>";
				 while($myrow=DB_fetch_array($ticketdetails)){
    echo "
				<tr>
					<td>
						".$n."</td>
					<td>
						".$myrow[ticketno]."</td>
					<td>
						".$myrow[DateCreated]."</td>
					<td>
						".$myrow[Type]."</td>
					<td colspan='3' rowspan='1'>
						".$myrow[description]."</td>
					<td>
						".$myrow[status]."</td>
							<td>
						".$myrow[DateClosed]."</td>
				</tr>
			
        ";
 $n++; 
}
			echo"</tbody>
		</table>";
		}
?>