<?php

$PageSecurity = 80;
include('includes/session.inc');
$title = _('Complaints');     
include('includes/header.inc'); 
include('includes/removespecialcharacters.php');
echo '<a href="index.php">Back to Home</a>';    

     $empid=$_SESSION['empid'];
     $sql_emp1="SELECT * FROM bio_emp WHERE empid=".$empid;
     $result_emp1=DB_query($sql_emp1,$db);
     $myrow_emp1=DB_fetch_array($result_emp1);
     $myrow_emp1['designationid'];   
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
   
   $team_arr=array();
   $sql6="SELECT teamid FROM bio_teammembers WHERE empid IN ($employee_arr)";
    $result6=DB_query($sql6,$db);
    while($row6=DB_fetch_array($result6))
    {
        $team_arr[]=$row6['teamid'];
    }
         
    $team_array=join(",", $team_arr);
     
    
    
    
     if (isset($_POST['submit1'])){  
     
  $ticketno1=$_POST['ticketno1'];   
  $id1=$_POST['ticket_id'];  

  
     if (isset($ticketno1)){  
      
      $sql2="SELECT    bio_incidents.description
             FROM      bio_incidents
             WHERE     bio_incidents.ticketno=$ticketno1";
         
  $result2=DB_query($sql2, $db);  
  $row2=DB_fetch_array($result2) ;     
  $description2=$row2['description']; 

     $sql2 = "UPDATE bio_incidents  SET   status=6  WHERE ticketno =" .$ticketno1;
     $result=DB_query($sql2,$db);

            }
            
     if (isset($id1)){  
                   $createdate=date("d/m/Y");  
         
                $sql1="SELECT  bio_incidents.description
         FROM      bio_incidents
         WHERE     bio_incidents.ticketno=$id1";
         
  $result1=DB_query($sql1, $db);  
  $row1=DB_fetch_array($result1) ;     
   $data=" (Merged with ticket no ".$ticketno1.")";
 $description1=$row1['description'];
    
  //$description=$description1.". ".$description2;  
  $description=$description1.". ".$createdate.$data." ".$description2; 
  
     $sql1 = "UPDATE bio_incidents  SET   description='".$description."'   WHERE ticketno =" .$id1;         
     $result=DB_query($sql1,$db);
     }
 }
 
    
    
    
  if (isset($_POST['submit'])){
      $createdate=date("d/m/Y"); 
     
 $description1=$_POST['description'].", "."$createdate " .$_POST['comment'];   
     $status1=$_POST['status'];   $id1=$_POST['ticket_id']; 
            if($status1==3)
            {
                $closeDate=date("Y-m-d"); 
            }
               else
            {
                $closeDate='0000-00-00'; 
            }
                
 if($status1==5){
           
     $sql="SELECT * FROM bio_incidents WHERE ticketno =" .$_POST['SelectedType'];     
     $result=DB_query($sql,$db); 
     $myrow=DB_fetch_array($result); 
     $flag=$myrow['status_count'];      
      
      $flag++;    
      
       $sql = "UPDATE bio_incidents SET status_count=$flag  WHERE ticketno =" .$_POST['SelectedType'];     
        $result=DB_query($sql,$db);    
       } 
                
 $sql = "UPDATE bio_incidents
             SET 
                  
                  description='".$description1."',
                  priority='".$_POST['priority']."' ,
                                 status='".$status1."',closeDate='".$closeDate."'     
             WHERE ticketno =" .$_POST['SelectedType'];
             
     $result=DB_query($sql,$db);
     
     
if($status1==3)
{     
/* 
 $sql_debtorno="SELECT bio_incident_cust.debtorno 
               FROM   bio_incident_cust,bio_incidents 
               WHERE  bio_incident_cust.cust_id=bio_incidents.cust_id 
               AND    bio_incidents.ticketno=".$_POST['SelectedType']; */ 
 
 $sql_debtorno="SELECT bio_incidents.debtorno 
               FROM  bio_incidents 
               WHERE bio_incidents.ticketno=".$_POST['SelectedType'];  
$result_debtorno=DB_query($sql_debtorno,$db);
$row_debtorno=DB_fetch_array($result_debtorno);



$sql_oldorder="SELECT debtorno FROM bio_oldorders WHERE debtorno='".$row_debtorno['debtorno']."'";  
$result_oldorder=DB_query($sql_oldorder,$db);



if(DB_num_rows($result_oldorder)>0)
{
    $row_oldorder=DB_fetch_array($result_oldorder); 
    
    $sql_update="UPDATE bio_oldorders SET currentstatus=1 WHERE debtorno='".$row_oldorder['debtorno']."'";
    DB_query($sql_update,$db);
    
}             
                 
}    
     
 } 
 
 
 //$sql="SELECT bio_incident_cust.cust_id,
//                    bio_incident_cust.custname,
//                    bio_incident_cust.custphone,
//                    bio_incident_cust.custmail,
//                    bio_incident_cust.district,
//                    bio_incident_cust.nationality,
//                    bio_incident_cust.LSG_type,
//                    bio_incident_cust.village,
//                    bio_incident_cust.LSG_name,
//                    bio_incident_cust.block_name,
//                    bio_incidents.ticketno,
//                    bio_incident_cust.taluk,  
//                    bio_incidents.type, 
//                    bio_incidents.title,
//                    bio_incidents.description,
//                    bio_incidents.priority,
//                    bio_priority.id,
//                    bio_priority.priority,
//                    bio_incidenttype.id,
//                    bio_incidenttype.type AS intype,
//                    bio_incidentstatus.id,
//                    bio_incidentstatus.status,
//                    bio_district.district,
//                    bio_leadteams.teamname,
//                    bio_incidents.createdon,
//                    bio_incidentsource.source                       
//              FROM  bio_incident_cust,bio_incidents,bio_priority,bio_incidenttype,bio_incidentstatus,bio_district,bio_leadteams,bio_incidentsource 
//              WHERE bio_incident_cust.cust_id=bio_incidents.cust_id
//                AND bio_district.did=bio_incident_cust.district
//                AND bio_district.stateid=bio_incident_cust.state
//                AND bio_district.cid=bio_incident_cust.nationality
//                AND bio_incidents.priority=bio_priority.id
//                AND bio_incidents.type=bio_incidenttype.id
//                
//                AND bio_incidents.handling_officer=bio_leadteams.teamid
//                AND bio_incidents.status=bio_incidentstatus.id
//    
//                AND bio_incidents.source=bio_incidentsource.id";
// 
   $enquiry=0;          
/*
$sql="SELECT 
                    bio_incident_cust.custname,
                    bio_incident_cust.custphone,
                    bio_incidents.ticketno,
                    bio_priority.priority,
                    bio_incidenttype.type AS intype,
                    bio_incidentstatus.status,
                    bio_district.district,
                    bio_leadteams.teamname,
                    bio_incidents.createdon,
                    bio_incidents.closeDate,
                    bio_incidentsource.source                       
            
              FROM  
           
           
              bio_incident_cust,
              bio_incidents,
              bio_priority,
              bio_enquirytypes,
              stockmaster,
              bio_incidenttype,
              bio_incidentstatus,
              bio_district,
              bio_leadteams,
              bio_incidentsource
		
		LEFT JOIN              

		bio_oldorders ON bio_oldorders.debtorno = bio_incident_cust.debtorno
             
              WHERE 
              
                bio_incident_cust.cust_id=bio_incidents.cust_id
                AND bio_district.did=bio_incident_cust.district
                AND bio_district.stateid=bio_incident_cust.state
                AND bio_district.cid=bio_incident_cust.nationality
                AND bio_incidents.priority=bio_priority.id
                AND bio_incidents.type=bio_incidenttype.id
                AND bio_incidents.mainmailcategory=5 
                AND bio_incidents.submailcategory=1
                AND bio_incidents.handling_officer=bio_leadteams.teamid
                AND bio_incidents.status=bio_incidentstatus.id
                AND bio_incidents.source=bio_incidentsource.id
                AND bio_enquirytypes.enqtypeid = bio_incidents.enqtypeid
                and bio_oldorders.plantid = stockmaster.stockid
";  */

// $sql = "SELECT bio_incidents.ticketno
//      , bio_incidenttype.type
//      , bio_priority.priority
//      , bio_incident_cust.custname
//      , bio_incident_cust.custphone
//      , bio_district.district
//      , bio_incidentsource.`source`
//      , bio_leadteams.teamname
//      , bio_incidentstatus.status
//      , stockmaster.description
//      , bio_incidents.createdon
//      , bio_enquirytypes.enquirytype
// FROM
//   bio_incidents
// INNER JOIN bio_incident_cust
// ON bio_incidents.cust_id = bio_incident_cust.cust_id
// INNER JOIN bio_priority
// ON bio_incidents.priority = bio_priority.id
// INNER JOIN bio_incidenttype
// ON bio_incidents.type = bio_incidenttype.id
// INNER JOIN bio_incidentstatus
// ON bio_incidentstatus.id = bio_incidents.status
// INNER JOIN bio_leadteams
// ON bio_leadteams.teamid = bio_incidents.handling_officer
// INNER JOIN bio_incidentsource
// ON bio_incidents.`source` = bio_incidentsource.id
// INNER JOIN bio_district
// ON bio_district.did = bio_incident_cust.district AND bio_incident_cust.state = bio_district.stateid AND bio_incident_cust.nationality = bio_district.cid
// INNER JOIN bio_enquirytypes
// ON bio_enquirytypes.enqtypeid = bio_incidents.enqtypeid
// LEFT JOIN bio_oldorders
// ON bio_oldorders.debtorno = bio_incident_cust.debtorno
// LEFT JOIN stockmaster
// ON bio_oldorders.plantid = stockmaster.stockid
// 
// WHERE
//   bio_incidents.mainmailcategory = 5
//   AND bio_incidents.submailcategory = 1
//   AND bio_incidents.source!=2";

$sql = "SELECT distinct custbranch.debtorno, bio_incidents.ticketno ,
www_users.realname,
bio_complainttypes.complaint ,

CASE WHEN bio_incidents.debtorno not like '0' then custbranch.braddress1 
 WHEN bio_incidents.debtorno =0 then bio_incident_cust.area1
end as 'area1', 
CASE WHEN bio_incidents.debtorno not like '0' then custbranch.faxno
 WHEN bio_incidents.debtorno =0 then bio_incident_cust.landline
end as 'landline', 
CASE WHEN bio_incidents.debtorno not like '0' then custbranch.LSG_type
 WHEN bio_incidents.debtorno =0 then bio_incident_cust.LSG_type
end as 'LSG_type', 
CASE WHEN bio_incidents.debtorno not like '0' then custbranch.block_name
 WHEN bio_incidents.debtorno =0 then bio_incident_cust.block_name
end as 'block_name', 
CASE WHEN bio_incidents.debtorno not like '0' then custbranch.brname 
 WHEN bio_incidents.debtorno =0 then bio_incident_cust.custname 
end as 'custname', 
CASE WHEN bio_incidents.debtorno not like '0' then custbranch.phoneno 
 WHEN bio_incidents.debtorno =0 then bio_incident_cust.custphone 
end as 'custphone',


 bio_incidenttype.type , 
bio_priority.priority , 
 bio_district.district ,
 bio_incidentsource.`source` , 
bio_leadteams.teamname ,
 bio_incidentstatus.status , 
stockmaster.description , 
bio_incidents.createdon , 
bio_enquirytypes.enquirytype, 
bio_corporation.corporation,
bio_municipality.municipality,
bio_panchayat.name
FROM bio_incidents
LEFT JOIN complaint_all ON complaint_all.ticketno = bio_incidents.ticketno
LEFT JOIN custbranch ON custbranch.debtorno=bio_incidents.debtorno
LEFT JOIN bio_incident_cust ON bio_incidents.cust_id = bio_incident_cust.cust_id 
LEFT JOIN bio_district ON bio_district.did = complaint_all.district 
AND complaint_all.state = bio_district.stateid
AND complaint_all.nationality = bio_district.cid 
LEFT JOIN bio_enquirytypes ON bio_enquirytypes.enqtypeid = bio_incidents.enqtypeid 

LEFT JOIN bio_corporation ON bio_corporation.district = complaint_all.LSG_name
 AND bio_corporation.district = complaint_all.district 
AND bio_corporation.state = complaint_all.state
 AND bio_corporation.country = complaint_all.nationality
LEFT JOIN bio_municipality ON bio_municipality.id = complaint_all.LSG_name 
AND bio_municipality.district = complaint_all.district 
AND bio_municipality.state = complaint_all.state 
AND bio_municipality.country = complaint_all.nationality
LEFT JOIN bio_panchayat ON bio_panchayat.id = complaint_all.block_name 
AND bio_panchayat.block = bio_incident_cust.LSG_name 
AND bio_panchayat.district = complaint_all.district 
AND bio_panchayat.state = complaint_all.state 
AND bio_panchayat.country = complaint_all.nationality

LEFT JOIN  www_users on www_users.userid=bio_incidents.createdby
LEFT JOIN bio_complainttypes ON bio_complainttypes.id = bio_incidents.title   
LEFT JOIN bio_oldorders ON bio_oldorders.debtorno = bio_incidents.debtorno 
LEFT JOIN stockmaster ON bio_oldorders.plantid = stockmaster.stockid 

LEFT JOIN bio_leadteams ON bio_leadteams.teamid = bio_incidents.handling_officer 
LEFT JOIN bio_incidentsource ON bio_incidents.source = bio_incidentsource.id
LEFT JOIN bio_incidentstatus ON bio_incidentstatus.id = bio_incidents.status 
LEFT JOIN bio_incidenttype ON bio_incidents.type = bio_incidenttype.id 
LEFT JOIN bio_priority ON bio_incidents.priority = bio_priority.id 
WHERE bio_incidents.mainmailcategory = 5 
AND bio_incidents.submailcategory = 1 
AND bio_incidents.source!=2
 ";//AND bio_incidents.ticketno NOT IN ()

   if ($_GET['ticketno']!='') 
    { 
    $sql.= " AND bio_incidents.ticketno= ".$_GET['ticketno']."";  
    }
    
     
if(isset($_POST['filterbut']))
{         
         
    if (isset($_POST['ticketno']))  {        
    if ($_POST['ticketno']!='')  { 
    $sql.= " AND bio_incidents.ticketno= ".$_POST['ticketno']."";   }
    } 
  
    
   if (isset($_POST['date']))  {        
    if ($_POST['date']!='') {  
    $date=FormatDateForSQL($_POST['date']); 
    $sql.=" AND bio_incidents.createdon= '$date'";   }
    }

    
    if (isset($_POST['enquiry'])) {
    if (($_POST['enquiry']!='ALL') && ($_POST['enquiry']!=0))
    $sql.=" AND bio_incidents.enqtypeid='".$_POST['enquiry']."'";
    $enquiry=1;
    } 

    
    
  if (isset($_POST['caty']))  {        
   if ($_POST['caty']!='0') {  
        
    $sql.=" AND bio_oldorders.plantid='".$_POST['caty']."'";    }
    }   
        
 
    if ((isset($_POST['createdFromDate'])) && (isset($_POST['createdToDate'])))   {
    if (($_POST['createdFromDate']!="") && ($_POST['createdToDate']!=""))  { 
    $sourcetypefrom=FormatDateForSQL($_POST['createdFromDate']);   
    $sourcetypeto=FormatDateForSQL($_POST['createdToDate']);
    $sql.=" AND bio_incidents.createdon BETWEEN '".$sourcetypefrom."' AND '".$sourcetypeto."'";
    }  }
    

    if ((isset($_POST['ClosedFromDate'])) && (isset($_POST['ClosedToDate'])))   {
    if (($_POST['ClosedFromDate']!=NULL) && ($_POST['ClosedToDate']!=NULL))  { 
    $sourcetypefrom=FormatDateForSQL($_POST['ClosedFromDate']);   
    $sourcetypeto=FormatDateForSQL($_POST['ClosedToDate']);
    $sql.=" AND bio_incidents.closeDate BETWEEN '".$sourcetypefrom."' AND '".$sourcetypeto."'";
    }  } 
                                                                       
    if (isset($_POST['byname']))  {        
    if ($_POST['byname']!='')  {
    $name=removeCharacters($_POST['byname']); 
    $sql.=" AND (bio_incident_cust.custname LIKE '%".$_POST['byname']."%' 
    OR bio_incident_cust.custname SOUNDS LIKE '%".$_POST['byname']."%'
    OR bio_incident_cust.custname LIKE '%".$name."%')";   }
    }  
    
        
    if (isset($_POST['byoffice']))    {
    if ($_POST['byoffice']!='') {
    if ($_POST['byoffice']==1 OR $_POST['byoffice']==4) {
    $sql.=" AND (bio_incident_cust.district=6 OR bio_incident_cust.district=11 OR bio_incident_cust.district=12)" ;
    } 
     else if($_POST['byoffice']==2)   {
      $sql.=" AND (bio_incident_cust.district=1 OR bio_incident_cust.district=2 OR bio_incident_cust.district=3 OR bio_incident_cust.district=7 OR bio_incident_cust.district=13)" ;      
     }
     else if($_POST['byoffice']==3){
     $sql.=" AND (bio_incident_cust.district=4 OR bio_incident_cust.district=5 OR bio_incident_cust.district=8 OR bio_incident_cust.district=9 OR bio_incident_cust.district=10 OR bio_incident_cust.district=14)" ;         
     }
    } 
    }
     
    if (isset($_POST['source']))    {
    if ($_POST['source']!=0)   {
    $sql.=" AND bio_incidents.source=".$_POST['source'];   } 
    }                                    
       
     if (isset($_POST['type']))    {
     if ($_POST['type']!=0)   {
     $sql .=" AND bio_incidents.type=".$_POST['type'];  }   
     }                                                              
         
     if (isset($_POST['status1']))    {
     if($_POST['status1']!='ALL')   {
     $sql .=" AND bio_incidents.status=".$_POST['status1'];    }
     }
   
     if (isset($_POST['country']))    {
     if($_POST['country']!=0)   {
     $sql .=" AND complaint_all.nationality=".$_POST['country'];    }
     }
   
    if (isset($_POST['State']))    {
     if($_POST['State']!=0)   {
     $sql .=" AND complaint_all.state=".$_POST['State'];    }
     }
 
    if (isset($_POST['Priority']))    {
     if($_POST['Priority']!=0)   {
     $sql .=" AND bio_incidents.priority=".$_POST['Priority'];    }
    }
       if (isset($_POST['District']))    {
     if($_POST['District']!=0)   {
     $sql .=" AND bio_incident_cust.district=".$_POST['District'];
      
     if (isset($_POST['lsgType']))    {
     if($_POST['lsgType']!=0)   {
       if($_POST['lsgType']!=NULL)   {  
         
     $sql .=" AND bio_incident_cust.LSG_type=".$_POST['lsgType'];    
     
     if (isset($_POST['lsgName']))    {
     if($_POST['lsgName']==1 OR $_POST['lsgName']==2)   {
     $sql .=" AND bio_incident_cust.LSG_name=".$_POST['lsgName'];    }
    
       elseif($_POST['lsgName']==3){
       $sql .=" AND bio_incident_cust.LSG_name=".$_POST['lsgName'];    } 
              
       }
       
       if (isset($_POST['gramaPanchayath']))    {  
      if($_POST['gramaPanchayath']!=0 OR $_POST['gramaPanchayath']!=NULL)   {
     $sql .=" AND bio_incident_cust.block_name=".$_POST['gramaPanchayath'];    }       
     }
     }
     }
     }   
     }
     
    if (isset($_POST['taluk']))    {
     if($_POST['taluk']!=0 OR $_POST['taluk']!=NULL)   {
     $sql .=" AND bio_incident_cust.taluk=".$_POST['taluk'];    }
     } 
     if (isset($_POST['village']))    {
     if($_POST['village']!='' OR $_POST['village']!=NULL)   {
     $sql .="  AND bio_incident_cust.village LIKE '%".$_POST['village']."%'";  }
     }      
     } 
     
  }   else
{
 $sql.=" AND bio_incidentstatus.id in(1,4,5)";     
}

  if($myrow_emp1['designationid']==1 || $myrow_emp1['designationid']==4 || $myrow_emp1['designationid']==24)
  {
  
  } 
  else if($myrow_emp1['designationid']==19 )           
  {
      
      if($_SESSION['UserID']=='ccetvm1')
      {
                       $sql.="  AND (bio_incident_cust.district=6 OR bio_incident_cust.district=11 OR bio_incident_cust.district=12) "; 

      }
       else if($_SESSION['UserID']=='cceeklm1')
      {
                           $sql.=" AND (bio_incident_cust.district=1 OR bio_incident_cust.district=2 OR bio_incident_cust.district=3 OR bio_incident_cust.district=7 OR bio_incident_cust.district=13) "; 

      }
       else if($_SESSION['UserID']=='ccekoz1')
      {
                         $sql.=" AND (bio_incident_cust.district=4 OR bio_incident_cust.district=5 OR bio_incident_cust.district=8 OR bio_incident_cust.district=9 OR bio_incident_cust.district=10 OR bio_incident_cust.district=14) "; 

      }
      
      
  }
  else
  {     
  $sql.=" AND bio_incidents.handling_officer IN ($team_array) ";
  }  
    if($_POST['custno'])
    {
         $sql.= " AND custbranch.debtorno= '".$_POST['custno']."'";     
    }
 $sql.=" ORDER BY bio_incidents.ticketno desc";
// echo $sql; 
$result_inc=DB_query($sql,$db);
 $_SESSION[$inc_sql]=$sql;        
                                             
echo "<form action=" . $_SERVER['PHP_SELF'] . "?" . SID ." method=post>";
echo '<div id="panel"></div>';  
echo"<fieldset style='width:70%;'>";
echo"<legend><h3>Search Complaints</h3></legend>";
echo"<table style='border:1px solid #F0F0F0;width:90%'>";
echo"<tr>";
echo"<td>Customer No<input type='text' name='custno' id='custno' style='width:100px'></td>"; 
echo"<td>Ticket No<input type='text' name='ticketno' id='ticketno' style='width:100px'></td>"; 
echo'<td>Create date'; 
echo'<input type="text" name="createdFromDate" id="createddateFrom" class=date alt='.$_SESSION['DefaultDateFormat'].' value="'.$_POST['createdFromDate'].'" style="width:100px"></td>';
echo"<td>To<input type='text' name='createdToDate' class=date alt=".$_SESSION['DefaultDateFormat']." id='createddateTo' value='".$_POST['createdToDate']."' style='width:100px'></td>";
echo'<td>Closed date'; 
echo"<input type='text' name='ClosedFromDate' class=date alt=".$_SESSION['DefaultDateFormat']." id='CloseddateFrom' value='".$_POST['ClosedFromDate']."' style='width:100px'></td>"; 
echo"<td>To<input type='text' name='ClosedToDate' class=date alt=".$_SESSION['DefaultDateFormat']." id='CloseddateTo' value='".$_POST['ClosedToDate']."' style='width:100px'></td>";  
echo"<td>Name<input type='text' name='byname' id='byname' style='width:100px'></td>";
echo '<td>Office<select name="byoffice" id="byoffice" style="width:100px">';
      echo '<option value=0></option>'; 
      echo '<option value=1>HO</option>';
      echo '<option value=2>Ernakulam Zone</option>'; 
      echo '<option value=3>Kozhikode Zone</option>'; 
      echo '<option value=4>Trivandrum Zone</option>';  
      echo '</select></td>'; 
      
      


echo '<td>Source<select name="source" id="source" style="width:100px">';
//echo '<option value=0></option>';  
 $sql1="select * from bio_incidentsource"; 
 $result1=DB_query($sql1,$db); 
 $f=0;
while($row1=DB_fetch_array($result1))
{
 if ($row1['id']==$_POST['source']) 
    {
       
    echo '<option selected value="';
    
    }  else  {     if ($f==0) 
        {
        echo '<option value="0"></option>';
        }
        echo '<option value="';
    }        $f++;
    
    
  echo $row1['id'].'">'.$row1['source'];
  echo'</option>';
}
echo '</select></td>';


echo '<td>Type<select name="type" id="type" style="width:100px">';
//echo '<option value=0></option>';  
 $sql1="select * from bio_incidenttype";  
 $result1=DB_query($sql1,$db); 
 $f=0;
while($row1=DB_fetch_array($result1))
{
 if ($row1['id']==$_POST['type']) 
    {
       
    echo '<option selected value="';
    
    }  else  {     if ($f==0) 
        {
        echo '<option value="0"></option>';
        }
        echo '<option value="';
    }        $f++;
    
    
  echo $row1['id'].'">'.$row1['type'];
  echo'</option>';
}
echo '</select></td>';
  
$sql_plant="SELECT distinct stockmaster.description,stockmaster.stockid from stockmaster,stockcategory,bio_maincat_subcat where stockmaster.categoryid in (SELECT subcatid from bio_maincat_subcat where maincatid=1) order by stockmaster.description asc";
$result_plant=DB_query($sql_plant,$db);

  echo '<td>Plant Category<select name="caty" id="caty" style="width:200px">'; 
echo '<option value=0></option>';
      // echo '<option value=0>Select category</option>'; 
       
  while ( $myrow_palnt=DB_fetch_array($result_plant)) {
         $catid=$myrow_palnt['categoryid'];
          if($myrow_palnt['maincatid']==1){
          echo "<option selected value=".$myrow_palnt['stockid'].">".$myrow_palnt['description']."</option>"; 
          }else{
             echo "<option value=".$myrow_palnt['stockid'].">".$myrow_palnt['description']."</option>"; 
          }
          
      }      
           echo '</select></td>';    

echo"<tr>";


if(!isset($_POST['status1']))
{
$_POST['status1']=1; 
}

echo '<td>Status<select name="status1" id="status" style="width:100px">';
echo '<option value=0></option>';
echo '<option value="ALL">Select ALL</option>';
$sql1="select * from bio_incidentstatus";
$result1=DB_query($sql1,$db);
 $f=0;

while($row1=DB_fetch_array($result1))
{
 if ($row1['id']==$_POST['status1']) 
    {
       
    echo '<option selected value="';
    
    }  else  {     if ($f==0) 
        {
        echo '<option value="0">--SELECT--</option>';
        }
        echo '<option value="';
    }        $f++;
    
    
  echo $row1['id'].'">'.$row1['status'];
  echo'</option>';
}
echo '</select></td>'; 
echo '<td>Priority<select name="Priority" id="priority" style="width:100px">';    
echo '<option value="0">--SELECT--</option>'; 
$sql1="select * from bio_priority";
$result1=DB_query($sql1,$db);
 $f=0;    
while($row1=DB_fetch_array($result1))
{                                     
 if ($row1['id']==$_POST['Priority']) 
    {
       
    echo '<option selected value="';
    
    } else {

        echo '<option value="';
               $f++;   
    }
    echo $row1['id'].'">'.$row1['priority'];
    echo '</option>';

}    
     echo '</select></td>';                  
     echo '<td>Customer Type<select name="enquiry" id="enquiry" style="width:120px">'; 
     
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
                                                            
   echo '</select></td>'; 
     
     
     
   
echo"<td>Country<select name='country' id='country' onchange='showstate(this.value)' style='width:100px'>";
$sql="SELECT * FROM bio_country ORDER BY cid";
$result=DB_query($sql,$db);
 $f=0;
  while($myrow1=DB_fetch_array($result))
  {  
  if ($myrow1['cid']==1)  
    {         //echo $myrow1['cid'];     
    echo '<option selected value="';
    } else 
    {
    if ($f==0) 
        {
        echo '<option>';
        }
        echo '<option value="';
    }
    echo $myrow1['cid'] . '">'.$myrow1['country'];
    echo '</option>';
    $f++;
   }    
  echo '</select></td>';
  echo '<td id="showstate">State<select name="State" id="state" onchange="showdistrict(this.value)" style="width:100px">';
  $sql="SELECT * FROM bio_state ORDER BY stateid";
  $result=DB_query($sql,$db);
  $f=0;
  while($myrow1=DB_fetch_array($result))
  {
  if ($myrow1['stateid']==14)
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
    echo $myrow1['stateid'] . '">'.$myrow1['state'];
    echo '</option>';
    $f++;
   }
  echo '</select>';
  echo'</td>'; 
 echo '<td id="showdistrict">District<select name="District" id="Districts" style="width:100px"   onchange="showtaluk(this.value)">'; 
  $sql="SELECT * FROM bio_district WHERE stateid=14 ORDER BY did";
    $result=DB_query($sql,$db);
    $f=0;
  while($myrow1=DB_fetch_array($result))
  {
  if ($myrow1['did']==$_POST['District'])
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
    echo $myrow1['did'] . '">'.$myrow1['district'];
    echo '</option>';
    $f++;
   } 
  echo '</select>';
  echo'</td>';
    
   
      echo '<td id=showtaluk>Taluk<select name="Taluk" id="taluk" style="width:100px" tabindex=11 onchange="showVillage(this.value)">';
      $sql_taluk="SELECT * FROM bio_taluk ORDER BY bio_taluk.taluk ASC";
      $result_taluk=DB_query($sql_taluk,$db);
      $f=0;
      while($myrow7=DB_fetch_array($result_taluk))
      {
      if ($myrow7['id']==$_POST['taluk'])
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
      echo $myrow7['id'] . '">'.$myrow7['taluk'];
      echo '</option>';
      $f++;
      }
      echo '</select>';
      echo'</td>';     
echo"<td id=showvillage>Village<select name='Village' id='village' style='width:100px'>";      
   $sql_taluk="SELECT * FROM bio_village ORDER BY bio_village.village ASC";
      $result_taluk=DB_query($sql_taluk,$db);
      $f=0;
      while($myrow7=DB_fetch_array($result_taluk))
      {
      if ($myrow7['id']==$_POST['village'])
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
      echo $myrow7['id'] . '">'.$myrow7['village'];
      echo '</option>';
      $f++;
      }
      echo '</select>';
      echo'</td>';
      echo '<td>LSG Type<select name="lsgType" id="lsgType" style="width:100px" onchange="showblock(this.value)">';
    echo '<option value=0></option>'; 
    echo '<option value=1>Corporation</option>';
    echo '<option value=2>Muncipality</option>';
    echo '<option value=3>Panchayat</option>';           
    echo '</select></td>'; 
  
        echo '<td align=left colspan=2>';
        echo'<div style="align:right" id=block>';             
        echo'</div>';
        echo'</td>';
      
        echo'<td><div id=showpanchayath></div></td>';
                 
echo"<td><input type='submit' name='filterbut' id='filterbut' value='search'></td>";
echo"</tr>";
echo"</table>";

        echo "<div ><br />";
        echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '">'; 
        echo "<table class='selection' style='width:90%'>";
        echo '<tr><th>' . _('Slno') . '</th> <th>' . _('Ticket No') . '</th> <th>' . _('Customer Name') . '</th>  <th>' . _('Contact No') . '</th>
                    <th>' . _('Place') . '</th><th>' . _('LSG') . '</th><th>' . _('District') . '</th><th>' . _('Complaint') . '</th><th>' . _('Created date') . '</th><th class="type">' . _ ('Type') . '</th>   
                    <th class="priority">' . _ ('Priority') . '</th> <th>' . _ ('Registered By') . '</th>
                    <th class="status">' . _('Status') . '</th>
                    <th>' . _('View') . '</th> <th>' . _('Merge') . '</th><th>' . _('schedule') . '</th>  
              </tr>';
 $k=0 ; $slno=0; 
 
   $sql_ticket="SELECT ticketno FROM bio_cstask ";
      $result_ticket=DB_query($sql_ticket,$db); 

 while( $myrow3=DB_fetch_array($result_inc) )   {


     if($myrow3['LSG_type']==1){
         $LSG_name=$myrow3['corporation']."(C)";
     }elseif($myrow3['LSG_type']==2){
         $LSG_name=$myrow3['municipality']."(M)";
     }elseif($myrow3['LSG_type']==3){
         if($myrow3['block_name']!=0 || $myrow3['LSG_name']!=0){
         $LSG_name=$myrow3['name']."(P)";
         }
     }elseif($myrow3['LSG_type']==0){
         $LSG_name="";
     }
     
        $ph= $myrow3['custphone'] ;
             if($ph=='' ||$ph==' ')
             {
                 $ph=$myrow3['landline'] ;
             }
     
//  $result1=DB_query($sql1, $db);           
  $dat= ConvertSQLDate($myrow3['createdon']);
  if($myrow3['closeDate']!=NULL){
      $date1=ConvertSQLDate($myrow3['closeDate']);
  }
  else{
      $date1="";    
  }     
  $ticketno=$myrow3['ticketno'];
  $debtor=$myrow3['debtorno'];
  $cust_id=$myrow3['cust_id'];
  $custname=$myrow3['custname'];
  $place=$myrow3['area1']; 
  $district=$myrow3['district'];   
  $phno=$myrow3['custphone'];
  $complaint=$myrow3['complaint'];
  $priority=$myrow3['priority'];
  $type1=$myrow3['intype'];  
  $status=$myrow3['status'];  
  $team1=$myrow3['realname'];
  $source=$myrow3['source'];
    $slno++; 
    
     
  if ($k==1){
        echo '<tr class="EvenTableRows">';
        $k=0;
  } else {
        echo '<tr class="OddTableRows">';
        $k=1;
  }  
               echo"<td>$slno</td>";
               if($debtor!="" or $debtor!=0)
               {
                
                   echo '<td><a id='.$debtor.' onclick="viewcustomer(this.id);return false;" href="">'.$ticketno.'</a></td>';    
               }else
               {
                   echo '<td><a id='.$ticketno.' onclick="viewcustomerticket(this.id);return false;" href="">'.$ticketno.'</a></td>';    
               }
                
               echo "<td>$custname</td><td>$ph</td><td>$place</td><td>$LSG_name</td><td>$district</td><td>$complaint</td><td>$dat</td><td class='type'>$myrow3[type]</td>  
               <td class='priority'>$priority</td><td>$team1</td><td class='status'>$status</td> 
               <td><a style='cursor:pointer;' id='$ticketno' onclick='viewIncident(this.id)'>View</a></td>
               <td><a style='cursor:pointer;' id='$ticketno' onclick='mergeIncident(this.id)'>Merge</a></td>";
     $views=0; 
  $sql_ticket="SELECT ticketno FROM bio_cstask ";
      $result_ticket=DB_query($sql_ticket,$db);     
 while($rows=DB_fetch_array($result_ticket))
      {if($ticketno==$rows['ticketno']) {
      echo "      <td style='background-color:#FFFFFF'><a style='cursor:pointer;' id='$ticketno' onclick='viewschedule(this.id)'>View schedule</a></td>";$views=1;  }
      }
       if($views==0){echo "<td ><a style='cursor:pointer;' id='$ticketno' onclick='addes(this.id)'>schedule</a></td>";}

          echo'</tr>'; 
 // $slno++;      
      // echo "<input type='hidden' name='ticket_id' id='ticket_id' value='".$ticketno."'>";   
      }
    
         
      //echo$sql;
 
 echo '</table>'; 
 echo'</div>';
 echo "</form>"; 
 echo "<form action='bio_incidentdepartment_excel.php' method=POST>"; 

 echo '<br /><input type=submit name=excel id=excel value="View as Excel" >';
 echo "</form>";           
 echo'</fieldset>';                                    
  ?>

 <Script>   
 
 var source=document.getElementById("source").value;   
 var type=document.getElementById("type").value;          
 var status=document.getElementById("status").value;     
 var priority=document.getElementById("priority").value;
 var district=document.getElementById("Districts").value;       // alert(status);
 
 if(source!=0)  {     $(".source").hide();    }
 if(type!=0)    {     $(".type").hide();      }   
 if(status!=1)  {     $(".status").hide();    } 
 if(priority!=0){     $(".priority").hide();  } 
 if(district!=0){     $(".district").hide();  }   
 
 
function viewIncident(str){
//alert(str);
if (str=="")
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
    //document.getElementById('loc').focus(); 
    }
  } 
xmlhttp.open("GET","bio_viewIncidents.php?ticketno=" + str,true);
xmlhttp.send(); 
}
function mergeIncident(str){
//alert(str);
if (str=="")
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
    //document.getElementById('loc').focus(); 
    }
  } 
xmlhttp.open("GET","bio_mergeIncidents.php?ticketno=" + str,true);
xmlhttp.send(); 
}

function showstate(str){
   // alert(str); 

if (str=="")
  {
  document.getElementById("showstate").innerHTML="";
  return;
  }
//show_progressbar('showstate');

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
    {                 //  alert(str); 
    document.getElementById("showstate").innerHTML=xmlhttp.responseText;

    }
  }
xmlhttp.open("GET","bio_showstate_incident.php?country=" + str,true);
xmlhttp.send();
}



function showdistrict(str){       
//    alert(str);
str1=document.getElementById("country").value;
//alert(str1);
if (str=="")
  {
  document.getElementById("showdistrict").innerHTML="";
  return;
  }
//show_progressbar('showdistrict');
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
    document.getElementById("showdistrict").innerHTML=xmlhttp.responseText;

    }
  }
xmlhttp.open("GET","bio_showstate_incident.php?state=" + str + "&country1=" + str1,true);
xmlhttp.send();

}

 function showblock(str){   
     str1=document.getElementById("country").value;
     str2=document.getElementById('state').value;
     str3=document.getElementById('Districts').value;
     if(str==1 && (str3==1 || str3==3 || str3==4 || str3==5 || str3==7 || str3==9 || str3==10 || str3==10 || str3==14)){
         alert("No Corporation for this district");
         document.getElementById("block").innerHTML="";
         return;
     }
//alert(str1);   alert(str2);       alert(str3);
 if (str3=="")
  {           
   alert("Please select the district");   
  document.getElementById('Districts').focus(); 
  return;
  }

if (str=="")
  {
  document.getElementById("block").innerHTML="";
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
     document.getElementById("block").innerHTML=xmlhttp.responseText; 
       
    }
  } 
xmlhttp.open("GET","bio_CustlsgSelection.php?block=" + str +"&country1="+ str1 +"&state1="+ str2 +"&district="+ str3,true);
xmlhttp.send(); 
} 
function showtaluk(str){   
     str1=document.getElementById("country").value;
     str2=document.getElementById('state').value;
     str3=document.getElementById('Districts').value;
 if (str3=="")
  {           
   alert("Please select the district");   
  document.getElementById('Districts').focus(); 
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
     document.getElementById("showtaluk").innerHTML=xmlhttp.responseText; 
       
    }
  } 
xmlhttp.open("GET","bio_CustlsgSelection_incident.php?taluk=" + str +"&country1="+ str1 +"&state1="+ str2 +"&district="+ str3 + "&taluk=" + str,true);
xmlhttp.send(); 
}





   function showVillage(str){   
    str1=document.getElementById("country").value;
     str2=document.getElementById('state').value;
     str3=document.getElementById('Districts').value;
      //alert(str);  
 if (str=="")
  {  
      alert("Please select the district");   
  document.getElementById('Districts').focus(); 
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
     document.getElementById("showvillage").innerHTML=xmlhttp.responseText; 
       
    }
  } 
xmlhttp.open("GET","bio_CustlsgSelection_incident.php?village=" + str + "&country1="+ str1 +"&state1="+ str2 +"&district="+ str3,true);
xmlhttp.send(); 
}






 function showgramapanchayath(str){   
    str1=document.getElementById("country").value;
     str2=document.getElementById('state').value;
     str3=document.getElementById('Districts').value;
     // alert(str2);  
 if (str=="")
  {  
      alert("Please select the district");   
  document.getElementById('Districts').focus(); 
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
     document.getElementById("showpanchayath").innerHTML=xmlhttp.responseText; 
       
    }
  } 
xmlhttp.open("GET","bio_Custlsg_Selection.php?grama=" + str + "&country1="+ str1 +"&state2="+ str2 +"&district="+ str3,true);
xmlhttp.send(); 
}
function addes(str)
{
   
   window.location="bio_installationscheduleadd.php?ticketno=" + str;
}
function viewschedule(str)
{  
  
    controlWindow=window.open("bio_view_schedule.php?ticketno=" + str,"viewlog", 
                "toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=800,height=600");  
}
function viewcustomer(str4)
{
controlWindow=window.open("Customers.php?DebtorNo="+str4,"customer","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=1200,height=600");
} 

function viewcustomerticket(str5)
{
controlWindow=window.open("Customers.php?ticketno="+str5,"customer","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=1200,height=600");
} 

</Script>   
  
 


