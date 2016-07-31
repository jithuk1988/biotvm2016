<?php

$PageSecurity = 80;
include('includes/session.inc');
$title = _('Complaint Register');
include('includes/header.inc');
include('includes/sidemenu.php');

echo"<br />";
$collectedBy=$_SESSION['UserID'];  
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
     
     $sql5="SELECT userid FROM www_users WHERE empid IN ($employee_arr)";
     $result5=DB_query($sql5,$db);
     while($row5=DB_fetch_array($result5))
     {
    
    $userid[]="'".$row5['userid']."'";     
    
     }
     $user_array=join(",", $userid);      

   if(isset($_GET['id'])){
       $incident_id=$_GET['id'];
       $sql_mail="select bio_email.subject,
                    bio_email.from_name,
                    bio_email.date,
                    bio_email.message,
                    bio_email.from_address
              from  bio_email 
              where bio_email.id=".$incident_id;
       $result_mail=DB_query($sql_mail,$db); 
       $myrow_mail=DB_fetch_array($result_mail); 
       $title1=$myrow_mail['subject'];
       $description=$myrow_mail['message'].$myrow_mail['date'];
       $mailid=$myrow_mail['from_address']; 
       $custname2=$myrow_mail['from_name'];
       $custname1=explode('<',$custname2);
       $custname=$custname1[0];
   }
   $createdate=date("Y-m-d");
   if(isset($_GET['edit'])){
   $eid=$_GET['edit']; 
   $sql="SELECT 
           bio_incident_cust.cust_id ,
           bio_incident_cust.custname ,
           bio_incident_cust.custphone ,
            bio_incident_cust.landline,
           bio_incident_cust.custmail ,
           bio_incident_cust.houseno,
           bio_incident_cust.housename ,
           bio_incident_cust.area1 ,
           bio_incident_cust.area2 ,
           bio_incident_cust.pin ,
           bio_incident_cust.nationality,
           bio_incident_cust.state ,
           bio_incident_cust.district,
           bio_incident_cust.taluk,
           bio_incident_cust.LSG_type,
           bio_incident_cust.LSG_name,
           bio_incident_cust.block_name,
           bio_incident_cust.LSG_ward,
           bio_incident_cust.village,
           bio_incidents.cust_id,
           bio_incidents.type,
           bio_incidents.source,
           bio_incidents.title,
           bio_incidents.description,
           bio_incidents.priority,
           bio_incidents.status,
           bio_incidents.remarks,
           bio_incidents.enqtypeid
     FROM  bio_incidents,bio_incident_cust 
     WHERE bio_incidents.ticketno= $eid
     AND   bio_incidents.cust_id=bio_incident_cust.cust_id ";
     $result=DB_query($sql,$db);
     $myrow2=DB_fetch_array($result);
     
     
    $pieces=$myrow2['landline'];
      $phpieces = explode("-", $pieces,2);  
      $landno_std=$phpieces[0];  
              $landno_no=$phpieces[1];
     if($landno_no==0 || $landno_no=="")
     {
        $landno_no=$landno_std;
          $landno_std='';
               $landno_no='';
       //  echo "ffffffffff";
     }

 //   $landno_no=$phpieces[1];
    
     
     $cust_id=$myrow2['cust_id'];
     $custname=$myrow2['custname'];
     $phno=$myrow2['custphone'];
     $mailid=$myrow2['custmail'];
     $houseno=$myrow2['houseno'];
     $housename=$myrow2['housename'];
     $area1=$myrow2['area1'];
     $area2=$myrow2['area2'];
     $pin=$myrow2['pin'];                     
     $complaint=$myrow2['title']; 
     $description=$myrow2['description'];
     $priority=$myrow2['priority'];
     $type=$myrow2['type'];  
     $source=$myrow2['source']; 
     $nationality=$myrow2['nationality'];
     $state=$myrow2['state'];
     $district=$myrow2['district'];                                
     $status=$myrow2['status']; 
     $taluk=$myrow2['taluk'];
     $LSGtypeid=$myrow2['LSG_type'];
     
     if($LSGtypeid==1){$lsgtype="Corporation";}
     elseif($LSGtypeid==2){$lsgtype="Municipality";}
     elseif($LSGtypeid==3){$lsgtype="Panchayath";}
        
     $LSG_name=$myrow2['LSG_name'];
     $block_name=$myrow2['block_name'];
     $LSG_ward=$myrow2['LSG_ward'];
     $village=$myrow2['village'];    
     $remarks=$myrow2['remarks'];
     $cust_type=$myrow2['enqtypeid'];//
   }
   
   if(isset($_GET['selectLead'])){ 
       $lead=$_GET['selectLead'];  
       $sql=" SELECT   bio_leads.leadid,
                   bio_cust.custname,
                   bio_cust.custmob,
                   bio_cust.custmail,
                   bio_cust.cust_id,
                   bio_cust.houseno,
                   bio_cust.housename, 
                   bio_cust.area1, 
                   bio_cust.area2, 
                   bio_cust.pin, 
                   bio_cust.nationality, 
                   bio_cust.state, 
                   bio_cust.district 
         FROM      bio_leads,bio_cust
         WHERE     bio_leads.cust_id=bio_cust.cust_id 
         AND       bio_leads.leadid= $lead";
       $result=DB_query($sql,$db);
       $myrow2=DB_fetch_array($result);
       
       $custname=$myrow2['custname'];
       $phno=$myrow2['custmob'];
       $mailid=$myrow2['custmail'];
       $leadid=$myrow2['leadid']; 
       $houseno=$myrow2['houseno'];
       $housename=$myrow2['housename'];
       $area1=$myrow2['area1'];
       $area2=$myrow2['area2']; 
       $pin=$myrow2['pin'];
       $nationality=$myrow2['nationality'];
       $state=$myrow2['state'];
       $district=$myrow2['district'];
   }
   if(isset($_GET['selectOrder'])){
       $order=$_GET['selectOrder']; 
      $eid=$_GET['selectOrder'];  
       $sql="SELECT 
                   salesorders.orderno,
                   salesorders.debtorno,
                   debtorsmaster.debtorno,     
                   debtorsmaster.name,
                   debtorsmaster.address1,     
                   debtorsmaster.address2,
                   debtorsmaster.address3,     
                   debtorsmaster.taluk,
                   debtorsmaster.LSG_type,     
                   debtorsmaster.LSG_name,
                   debtorsmaster.block_name,
                   debtorsmaster.LSG_ward,
                   debtorsmaster.village,
                   debtorsmaster.cid,
                   debtorsmaster.stateid,     
                   debtorsmaster.did,
                   custbranch.debtorno,
                   custbranch.phoneno,
                   custbranch.faxno,
                   custbranch.email
             FROM  debtorsmaster,salesorders,custbranch 
             WHERE salesorders.debtorno=debtorsmaster.debtorno
             AND   debtorsmaster.debtorno=custbranch.debtorno
             AND   salesorders.branchcode=custbranch.branchcode
             AND   salesorders.orderno= $order";
       $result=DB_query($sql,$db);
       $myrow2=DB_fetch_array($result);
       $pieces=$myrow2['phoneno'];
      $phpieces = explode("-", $pieces,2);  
      $landno_std=$phpieces[0];  
              $landno_no=$phpieces[1];
       $custname=$myrow2['name'];
       $phno=$myrow2['faxno'];
       $mailid=$myrow2['email'];
       $orderid=$myrow2['orderno'];
       $houseno=$myrow2['houseno'];
       $housename=$myrow2['address1'];
       $area1=$myrow2['address2'];
       $area2=$myrow2['address3']; 
       $pin=$myrow2['pin'];
      // $nationality=$myrow2['address6'];
       //$state=$myrow2['address5'];
      // $district=$myrow2['address4'];    
       $nationality=$myrow2['cid'];
       $state=$myrow2['stateid'];
       $district=$myrow2['did'];
       $taluk=$myrow2['taluk'];
  $LSGtypeid=$myrow2['LSG_type'];
     
     if($LSGtypeid==1){$lsgtype="Corporation";}
     elseif($LSGtypeid==2){  $lsgtype="Municipality";}
     elseif($LSGtypeid==3){  $lsgtype="Panchayath";}
        
  $LSG_name=$myrow2['LSG_name'];
  $block_name=$myrow2['block_name'];
  $LSG_ward=$myrow2['LSG_ward'];
  $village=$myrow2['village'];    
 
   } 
   if(isset($_GET['debtorno'])){
        $debtorno=$_GET['debtorno'];
        $sql="SELECT 
                   debtorsmaster.debtorno,     
                   debtorsmaster.name,
                   debtorsmaster.address1,     
                   debtorsmaster.address2,
                   debtorsmaster.address3,
                   debtorsmaster.cid,     
                   debtorsmaster.stateid,
                   debtorsmaster.LSG_type,
                   debtorsmaster.LSG_name, 
                   debtorsmaster.block_name,
                   debtorsmaster.taluk,
                   debtorsmaster.LSG_ward,
                   custbranch.phoneno,
                   custbranch.email
             FROM  debtorsmaster,custbranch 
             WHERE custbranch.debtorno=debtorsmaster.debtorno
             AND   debtorsmaster.debtorno='$debtorno'";
        $result=DB_query($sql,$db);
        $myrow2=DB_fetch_array($result);
        
       $custname=$myrow2['name'];
       $phno=$myrow2['phoneno'];
       $mailid=$myrow2['email'];    
       $address1=$myrow2['address1'];
       $address1=explode(",",$address1);
       $houseno=$address1[0];
       $housename=$address1[1];
       $area1=$myrow2['address2'];
       $address3=$myrow2['address3']; 
       $address3=explode(",",$address3); 
       $area2=$address3[0];
       $pin=$address3[1];
        
       
   }
  

if (isset($_POST['update'])){  

    $landno=$_POST['landno_no'];
        if($_POST['landno_std']!=""){
     $landno=$_POST['landno_std']."-".$_POST['landno_no'];   
     }
    
    
   echo $sql_update_add="UPDATE bio_incident_cust SET 
                            custname='".$_POST['custname']."',
                            custmail='".$_POST['email']."', 
                            custphone='".$_POST['phno']."',
                            landline='".$landno."',
                            nationality='".$_POST['country']."',
                            state='".$_POST['State']."',
                            district='".$_POST['District']."',
                            LSG_type='".$_POST['lsgType']."',
                            LSG_name='".$_POST['lsgName']."',
                            block_name='".$_POST['gramaPanchayath']."',
                            area1='".$_POST['Area1']."',
                            houseno='".$_POST['Houseno']."',
                            housename='".$_POST['Housename']."',
                            area2='".$_POST['Area2']."',
                            pin='".$_POST['Pin']."',
                            LSG_ward='".$_POST['lsgWard']."',
                            taluk='".$_POST['taluk']."',
                            village='".$_POST['village']."'
                      WHERE cust_id='".$_POST['SelectedType2']."'";
    $result_update_add=DB_query($sql_update_add,$db);
    
    $sql_update_inc="UPDATE bio_incidents
                    SET  type='".$_POST['type']."',
                         enqtypeid='".$_POST['enqtypeid']."',
                         title='".$_POST['complaint']."',
                         description='".$_POST['description']."',
                         priority='".$_POST['priority']."',
                         status='".$_POST['status']."',
                         remarks='".$_POST['remarks']."'   
                   WHERE ticketno =" .$_POST['SelectedType1'];
    $result_update_inc=DB_query($sql_update_inc,$db);
    
    
    if($result_update_add & $result_update_inc){
        $msg1= 'Incident has been updated succesfully.';      
        prnMsg($msg1,'success');
        
    } 
}

if (isset($_POST['submit'])){
//orderid
if ($_POST['orderid']!="" )
{
  $sql = "INSERT INTO bio_incident_cust(custname,
                                              custphone,
                                              landline,
                                              custmail,
                                              houseno,
                                              housename,
                                              area1,
                                              area2,
                                              pin,
                                              nationality,
                                              state,
                                              district,
                                              debtorno,
                                              taluk,
                                              LSG_type, 
                                              LSG_name,
                                              block_name, 
                                              LSG_ward, 
                                              village)
                                  VALUES ('".$_POST['custname'] . "',
                                          '".$_POST['phno'] . "',
                                          '".$landno."', 
                                         '".$_POST['email'] . "',
                                         '".$_POST['Houseno'] . "',
                                         '".$_POST['HouseName'] . "',
                                         '".$_POST['Area1'] . "',
                                         '".$_POST['Area2'] . "',
                                         '".$_POST['Pin'] . "',
                                         '".$_POST['country'] . "',
                                         '".$_POST['State'] . "',
                                         '".$_POST['District'] . "',         
                                         '".$_POST['debtorno'] . "',
                                         '".$_POST['taluk']."',
                                         '".$_POST['lsgType']."',
                                         '".$_POST['lsgName']."', 
                                         '".$_POST['gramaPanchayath']."',
                                         '".$_POST['lsgWard']."',
                                         '".$_POST['village']."')";                                        
        $result = DB_query($sql,$db);
        $cust_id=DB_Last_Insert_ID($Conn,'bio_incident_cust','cust_id');
        $duedate='0000-00-00';
        
        $sql2="SELECT *  FROM 
              bio_incidenthandlingoffcr 
              WHERE bio_incidenthandlingoffcr.types=".$_POST['type']."
              AND   bio_incidenthandlingoffcr.nationality=".$_POST['country']."
              AND   bio_incidenthandlingoffcr.state=".$_POST['State']."
              AND   bio_incidenthandlingoffcr.district=".$_POST['District']."  ";
        $result2=DB_query($sql2, $db);
        $count=DB_num_rows($result2); 
        $row2=DB_fetch_array($result2) ;   
        $dept=$row2['department'];
        if($count>0){
            $officer=$row2['officer'];
            $dept=$row2['department']; 
        }else{
            $officer=1;   $dept=$row2['department']; 
        }               
        $dept=$row2['department']; 
        $sql2="SELECT bio_teammembers.empid,bio_teammembers.teamid,bio_emp.empid,bio_emp.email
            FROM   bio_teammembers,bio_emp
            WHERE  bio_teammembers.teamid=$officer";
        $result2=DB_query($sql2, $db);
        $row2=DB_fetch_array($result2) ;
        $emails=$row2['email'];   
        if(isset($emails))    {
            $title1=$_POST['title'];       
            $description=$_POST['description'];
            $to = $emails;
            $subject = $title1;
            $priority1=$_POST['priority'];
          
        } 
        if($_POST['leadid']==""){ $_POST['leadid']=0; }
        if($_POST['orderid']==""){ $_POST['orderid']=0; }  
        
       $sql1 = "INSERT INTO bio_incidents(cust_id,
                                           type,
                                           source,
                                           enqtypeid,
                                           title,
                                           description,
                                           priority,
                                           expected_duedate,
                                           handling_officer, 
                                           leadid,orderno,
                                           createdon,
                                           createdby,
                                           status,
                                           remarks,
                                           mainmailcategory,
                                           submailcategory)
                               VALUES ('". $cust_id . "',
                                       '". $_POST['type'] . "',
                                       '". $_POST['source'] . "',
                                       '". $_POST['enquiry'] . "',
                                       '". $_POST['complaint'] . "', 
                                       '". $_POST['description'] . "',
                                       '". $_POST['priority'] . "',
                                       '". $duedate . "',
                                       '". $officer ."',
                                        ". $_POST['leadid'] . ",
                                        ". $_POST['orderid'] .",
                                       '". $createdate . "',
                                       '". $collectedBy . "',
                                       1,
                                       '". $_POST['remarks'] . "',5,1)";
        $result1 = DB_query($sql1,$db); 
        $ticket=DB_Last_Insert_ID($Conn,'bio_incidents','ticketno'); 
        $msg1= 'Incident has been created succesfully. Your Ticket no is <b>'.$ticket.'</b>';      
        prnMsg($msg1,'success');  
        
      /*  unset($_POST['custname']);
        unset($_POST['email']);  
        unset($_POST['phno']);          
        unset($_POST['country']);  
        unset($_POST['State']);  
        unset($_POST['District']);
        unset($_POST['Houseno']);  
        unset($_POST['HouseName']);  
        unset($_POST['Area1']); 
        unset($_POST['Area2']); 
        unset($_POST['Pin']); 
        unset($_POST['source']); 
        unset($_POST['type']); 
        unset($_POST['title']);
        unset($_POST['description']);  
        unset($_POST['priority']);  
        unset($_POST['status']);            */
        
                $title1='';
                $description='';
          unset($_POST['custname']);
  unset($_POST['email']);  
  unset($_POST['phno']);          
  unset($_POST['country']);  
  unset($_POST['State']);  
  unset($_POST['District']);
  unset($_POST['Houseno']);  
  unset($_POST['HouseName']);  
  unset($_POST['Area1']); 
  unset($_POST['Area2']); 
  unset($_POST['Pin']); 
  unset($_POST['source']); 
  unset($_POST['type']); 
  unset($_POST['title']);
  unset($_POST['description']);  
  unset($_POST['priority']);  
  unset($_POST['status']); 
        
        if(isset($_POST['incident']) AND $_POST['incident']!="") {    
            $incident1=$_POST['incident'];
            $sql6="UPDATE bio_email SET status=1 where id=$incident1";  
            $result6=DB_query($sql6,$db);    
            
  //$sql5="SELECT *  FROM   bio_dept  WHERE  bio_dept.deptid=$dept";
//    
//  $result5=DB_query($sql5, $db);
//  $row5=DB_fetch_array($result5) ;
//  $deptname=$row5['deptname'];  
   
            $title1=$_POST['title']; 
            $email=$_POST['email']; 
            $to = $email;
            $subject1="[".$ticket."]".":";
            $subject2 = $title1;
            $subject=$subject1.$subject2;
            $priority1=$_POST['priority'];
    
            $sql6="SELECT *  FROM   bio_priority   WHERE  bio_priority .id=$priority1";
            $result6=DB_query($sql6, $db);
            $row6=DB_fetch_array($result6) ;
            $priority2=$row6['priority']; 
    
            
        }  



  $orderid =$_POST['orderid'];
$sql_com="UPDATE `biotech`.`bio_installation_status` SET `plant_status` = '0' WHERE `bio_installation_status`.`orderno`= $orderid " ; $result=DB_query($sql_com,$db); 
 
}
 
$landno=$_POST['landno_no'];

        if($_POST['landno_std']!="" && $_POST['landno_no']!=''){
     $landno=$_POST['landno_std']."-".$landno;
        
      }
    if ($_POST['SelectedType']!=""){     
        $_POST['SelectedType'];
        $createdate=date("d/m/Y"); 
           
        
        $description1=$_POST['description'].", "."$createdate " .$_POST['comment'];   
        $status1=$_POST['status'];   
        if($status1==3){
            $closeDate=date("Y-m-d"); }
        else{
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
        
        $sql ="UPDATE bio_incidents SET description='".$description1."',
                                        priority='".$_POST['priority']."' ,
                                        status='".$status1."',closeDate='".$closeDate."'     
                                  WHERE ticketno =" .$_POST['SelectedType'];
        $result=DB_query($sql,$db);
    }else if ($_POST['SelectedType1']!=""){
        $_POST['SelectedType1'];
        $sql = "UPDATE bio_incidents
                    SET  type='".$_POST['type']."',
                         enqtypeid='".$_POST['enqtypeid']."',
                         title='".$_POST['title']."',
                         description='".$_POST['description']."',
                         priority='".$_POST['priority']."',
                         status='".$_POST['status']."',
                         remarks='".$_POST['remarks']."'   
                   WHERE ticketno =" .$_POST['SelectedType1'];
        $result=DB_query($sql,$db);
    }else if ($_POST['SelectedType2']!="" AND $_POST['SelectedType1']!=""){
        $sql = "UPDATE bio_incident_cust
             SET  custname='".$_POST['custname']."',
                  custphone='".$_POST['phno']."',
                  custmail='".$_POST['email']."',
                  houseno='".$_POST['Houseno']."',      
                  housename='".$_POST['HouseName']."', 
                  area1='".$_POST['Area1']."',      
                  area2='".$_POST['Area2']."', 
                  pin='".$_POST['Pin']."', 
                  nationality='".$_POST['country']."',
                  state='".$_POST['State']."',
                  district='".$_POST['District']."'
             WHERE cust_id =" .$_POST['SelectedType2'];
        $result=DB_query($sql,$db);
    }else {
   $sql = "INSERT INTO bio_incident_cust(custname,
                                              custphone,
                                              landline,
                                              custmail,
                                              houseno,
                                              housename,
                                              area1,
                                              area2,
                                              pin,
                                              nationality,
                                              state,
                                              district,
                                              debtorno,
                                              taluk,
                                              LSG_type, 
                                              LSG_name,
                                              block_name, 
                                              LSG_ward, 
                                              village)
                                  VALUES ('".$_POST['custname'] . "',
                                          '".$_POST['phno'] . "',
                                          '".$landno."', 
                                         '".$_POST['email'] . "',
                                         '".$_POST['Houseno'] . "',
                                         '".$_POST['HouseName'] . "',
                                         '".$_POST['Area1'] . "',
                                         '".$_POST['Area2'] . "',
                                         '".$_POST['Pin'] . "',
                                         '".$_POST['country'] . "',
                                         '".$_POST['State'] . "',
                                         '".$_POST['District'] . "',         
                                         '".$_POST['debtorno'] . "',
                                         '".$_POST['taluk']."',
                                         '".$_POST['lsgType']."',
                                         '".$_POST['lsgName']."', 
                                         '".$_POST['gramaPanchayath']."',
                                         '".$_POST['lsgWard']."',
                                         '".$_POST['village']."')";                                        
        $result = DB_query($sql,$db);
        $cust_id=DB_Last_Insert_ID($Conn,'bio_incident_cust','cust_id');
        $duedate='0000-00-00';
        
        $sql2="SELECT *  FROM 
              bio_incidenthandlingoffcr 
              WHERE bio_incidenthandlingoffcr.types=".$_POST['type']."
              AND   bio_incidenthandlingoffcr.nationality=".$_POST['country']."
              AND   bio_incidenthandlingoffcr.state=".$_POST['State']."
              AND   bio_incidenthandlingoffcr.district=".$_POST['District']."  ";
        $result2=DB_query($sql2, $db);
        $count=DB_num_rows($result2); 
        $row2=DB_fetch_array($result2) ;   
        $dept=$row2['department'];
        if($count>0){
            $officer=$row2['officer'];
            $dept=$row2['department']; 
        }else{
            $officer=1;   $dept=$row2['department']; 
        }               
        $dept=$row2['department']; 
        $sql2="SELECT bio_teammembers.empid,bio_teammembers.teamid,bio_emp.empid,bio_emp.email
            FROM   bio_teammembers,bio_emp
            WHERE  bio_teammembers.teamid=$officer";
        $result2=DB_query($sql2, $db);
        $row2=DB_fetch_array($result2) ;
        $emails=$row2['email'];   
        if(isset($emails))    {
            $title1=$_POST['title'];       
            $description=$_POST['description'];
            $to = $emails;
            $subject = $title1;
            $priority1=$_POST['priority'];
            $message = "Respected Sir,

           An Incident is receieved with the following details.
                From: ".$email."
                Subject: ".$title1."
                Content:".$description.";
                Priority: ".$priority2."
Biotech Helpdesk"; 
            $from = " info@biotechin.org";
            $headers = "From:" . $from;
        } 
        if($_POST['leadid']==""){ $_POST['leadid']=0; }
        if($_POST['orderid']==""){ $_POST['orderid']=0; }  
       $sql1 = "INSERT INTO bio_incidents(cust_id,
                                           type,
                                           source,
                                           enqtypeid,
                                           title,
                                           description,
                                           priority,
                                           expected_duedate,
                                           handling_officer, 
                                           leadid,orderno,
                                           createdon,
                                           createdby,
                                           status,
                                           remarks,
                                           mainmailcategory,
                                           submailcategory)
                               VALUES ('". $cust_id . "',
                                       '". $_POST['type'] . "',
                                       '". $_POST['source'] . "',
                                       '". $_POST['enquiry'] . "',
                                       '". $_POST['complaint'] . "', 
                                       '". $_POST['description'] . "',
                                       '". $_POST['priority'] . "',
                                       '". $duedate . "',
                                       '". $officer ."',
                                        ". $_POST['leadid'] . ",
                                        ". $_POST['orderid'] .",
                                       '". $createdate . "',
                                       '". $collectedBy . "',
                                       1,
                                       '". $_POST['remarks'] . "',5,1)";
        $result1 = DB_query($sql1,$db); 
        $ticket=DB_Last_Insert_ID($Conn,'bio_incidents','ticketno'); 
        $msg1= 'Incident has been created succesfully. Your Ticket no is <b>'.$ticket.'</b>';      
        prnMsg($msg1,'success');  
        
      /*  unset($_POST['custname']);
        unset($_POST['email']);  
        unset($_POST['phno']);          
        unset($_POST['country']);  
        unset($_POST['State']);  
        unset($_POST['District']);
        unset($_POST['Houseno']);  
        unset($_POST['HouseName']);  
        unset($_POST['Area1']); 
        unset($_POST['Area2']); 
        unset($_POST['Pin']); 
        unset($_POST['source']); 
        unset($_POST['type']); 
        unset($_POST['title']);
        unset($_POST['description']);  
        unset($_POST['priority']);  
        unset($_POST['status']);            */
        
                $title1='';
                $description='';
          unset($_POST['custname']);
  unset($_POST['email']);  
  unset($_POST['phno']);          
  unset($_POST['country']);  
  unset($_POST['State']);  
  unset($_POST['District']);
  unset($_POST['Houseno']);  
  unset($_POST['HouseName']);  
  unset($_POST['Area1']); 
  unset($_POST['Area2']); 
  unset($_POST['Pin']); 
  unset($_POST['source']); 
  unset($_POST['type']); 
  unset($_POST['title']);
  unset($_POST['description']);  
  unset($_POST['priority']);  
  unset($_POST['status']); 
        
        if(isset($_POST['incident']) AND $_POST['incident']!="") {    
            $incident1=$_POST['incident'];
            $sql6="UPDATE bio_email SET status=1 where id=$incident1";  
            $result6=DB_query($sql6,$db);    
            
  //$sql5="SELECT *  FROM   bio_dept  WHERE  bio_dept.deptid=$dept";
//    
//  $result5=DB_query($sql5, $db);
//  $row5=DB_fetch_array($result5) ;
//  $deptname=$row5['deptname'];  
   
            $title1=$_POST['title']; 
            $email=$_POST['email']; 
            $to = $email;
            $subject1="[".$ticket."]".":";
            $subject2 = $title1;
            $subject=$subject1.$subject2;
            $priority1=$_POST['priority'];
    
            $sql6="SELECT *  FROM   bio_priority   WHERE  bio_priority .id=$priority1";
            $result6=DB_query($sql6, $db);
            $row6=DB_fetch_array($result6) ;
            $priority2=$row6['priority']; 
    
            $message = "Dear Customer,

            Your ticket has been received, one of our staff members will review it and reply accordingly. Listed below are details of this ticket, Please make sure the Ticket ID remains in the subject at all times.
            
                Ticket ID: ".$ticket."
                Subject: ".$title1."
                Priority: ".$priority2."
                Status: Awaiting Staff Response
                
 Biotech Helpdesk"; 
            $from = " info@biotechin.org";
            $headers = "From:" . $from;
        }  
    }
}

if(isset($_POST['Reset'])){ 

  unset($_POST['custname']);
  unset($_POST['email']);  
  unset($_POST['phno']);          
  unset($_POST['country']);  
  unset($_POST['State']);  
  unset($_POST['District']);
  unset($_POST['Houseno']);  
  unset($_POST['HouseName']);  
  unset($_POST['Area1']); 
  unset($_POST['Area2']); 
  unset($_POST['Pin']); 
  unset($_POST['source']); 
  unset($_POST['type']); 
  unset($_POST['title']);
  unset($_POST['description']);  
  unset($_POST['priority']);  
  unset($_POST['status']); 

}  

echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '">'; 
echo '<table style=width:40%><tr><td>';
echo '<div id="panel">';
echo '<fieldset style="width:500px; height:auto">';  
echo '<legend><b>Complaint Register</b></legend>';
   if(!isset($_GET['edit']) and !isset($_GET['selectOrder']) )
   {
     $landno_std='0471';
   }
     if(isset($_GET['selectOrder']))
     {
             //  $landno_std='';
     }
     if(isset($_GET['selectLead']))
     {
     $landno_std='';
     }
     echo"<table width=100% border=0>";
echo"<tr><td width=18%>Customer Name</td>";
echo"<td><input type='text' name='custname' id='custname' tabindex=1 value='".$custname."' style='width:220px'  onBlur='searchCustomer()'></td></tr>";

echo"<tr><td width=17%>Email id</td>";
echo"<td><input type='email' name='email' id='email' value='".$mailid."' tabindex=2 style='width:220px' onBlur='searchCustomer()'></td></tr>";
 if(isset($_GET['debtorno']))
 {
echo"<tr><td width=17%>Mobile No</td>";
echo"<td><input type='text' name='phno' id='phno' required style='width:220px' tabindex=3 value='".$phno."' onBlur='searchCustomer()'></td></tr>";
 }
 else
 {
      echo"<tr><td width=17%>Mobile No</td>";
echo"<td><input type='text' name='phno' id='phno' style='width:220px' tabindex=3 value='".$phno."' onBlur='searchCustomer()'></td></tr>";

 }
echo"<tr><td width=17%>Phone No</td>";
echo"<td><input type='text' name='landno_std' id='landno_std' style='width:60px' tabindex=3 value='".$landno_std."''>&nbsp;&nbsp;<input type='text' name='landno_no' id='landno_no' style='width:145px' tabindex=3 value='".$landno_no."''></td></tr>";


if($eid!="")  {   
    $sql="SELECT * FROM bio_country ORDER BY cid";
    $result=DB_query($sql,$db);
    
echo"<tr><td width=17%>Country</td><td>";
echo '<select name="country" id="country" onchange="showstate(this.value)" tabindex=4 style="width:222px">';
    $f=0;
    while($myrow1=DB_fetch_array($result))
    {  
        if ($myrow1['cid']==$nationality)  
        {         //echo $myrow1['cid'];     
            echo '<option selected value="';
        } else {
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
  echo '</select></td></tr>';
  


$sql="SELECT * FROM bio_state ORDER BY stateid";
    $result=DB_query($sql,$db);
 
 echo"<tr id='showstate' width=17%><td>State</td><td>";
 echo '<select name="State" id="state" style="width:222px" tabindex=5 onchange="showdistrict(this.value)">';
   $f=0;
  while($myrow1=DB_fetch_array($result))
  {
  if ($myrow1['stateid']==$state AND $myrow1['cid']==$nationality)
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
  echo'</tr>';
 
echo"<tr id='showdistrict' width=17%><td>District</td><td>";
   $sql="SELECT * FROM bio_district WHERE cid='$nationality'  AND stateid='$state' ORDER BY did";
   $result=DB_query($sql,$db);
    
 echo '<select  required name="District" id="Districts" style="width:222px" tabindex=6 onchange="showtaluk(this.value)">';
   $f=0;
  while($myrow1=DB_fetch_array($result))
  {
  if ( $myrow1['did']==$district )
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
echo'</tr>';
}else{
//---------country--------------//    
    
    echo"<tr ><td width=17%>Country</td><td>";
    echo '<select name="country" id="country" tabindex=4 onchange="showstate(this.value)" style="width:222px">';
    
    $sql="SELECT * FROM bio_country ORDER BY cid";     $result=DB_query($sql,$db);
    
    $f=0;
    while($myrow1=DB_fetch_array($result))
    {  
        if ($myrow1['cid']==1)  
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
        echo $myrow1['cid'] . '">'.$myrow1['country'];
        echo '</option>';
        $f++;
   } 
   echo '</select></td></tr>';
  
//--------------state-----------------//


     echo"<tr id='showstate' width=17%>";
     echo"<td>State</td><td>";
     echo '<select name="State" id="state" style="width:222px" tabindex=5 onchange="showdistrict(this.value)">';
     
     $sql="SELECT * FROM bio_state WHERE cid=1 ORDER BY stateid";        $result=DB_query($sql,$db);   
 
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
   echo '</select></td>'; 
   echo'</tr>';

//-------------District---------------//  

 
     echo"<tr id='showdistrict' width=17%>";
     echo"<td>District</td><td>";
     echo '<select name="District" id="Districts" style="width:222px" tabindex=6 onchange="showtaluk(this.value)">';          
     
     $sql="SELECT * FROM bio_district WHERE stateid=14 ORDER BY did";      $result=DB_query($sql,$db);   
 
     $f=0;
     while($myrow1=DB_fetch_array($result))
     {
        if ($myrow1['did']==$_POST['district'])
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
   echo '</select></td>';
   echo'</tr>'; 
}
if(isset($_GET['edit']) or isset($_GET['selectOrder'])){ 

//echo "testing";
    echo '<tr><td>' . _('LSG Type') . ':</td>';
    echo '<td><select name="lsgType" id="lsgType" style="width:222px" tabindex=7 onchange=showblock(this.value)>';             
    echo '<option value='.$LSGtypeid.'>'.$lsgtype.'</option>'; 
    if($LSGtypeid==1){   
    echo '<option value=2>Municipality</option>';
    echo '<option value=3>Panchayat</option>';     
    }elseif($LSGtypeid==2){
        echo '<option value=1>Corporation</option>';
        echo '<option value=3>Panchayat</option>';
    }elseif($LSGtypeid==3){
        echo '<option value=1>Corporation</option>';
        echo '<option value=2>Municipality</option>';
    }else{
        echo '<option value=1>Corporation</option>';
    echo '<option value=2>Muncipality</option>';
    echo '<option value=3>Panchayat</option>';
        
    }      
    echo '</select></td></tr>';
    echo'<tr><td align=left colspan=2>';
    echo'<div style="align:right" id=block>';    
    if($LSGtypeid==1){
        $sql="SELECT * FROM bio_corporation WHERE country=$nationality AND state=$state AND district=$district";
        $result=DB_query($sql,$db);
        $row=DB_fetch_array($result);
        $cid=$row['country'];
        $sid=$row['state'];
        $district=$row['district'];
        if($country==1 && $state==14)  
        {
              if($district==12){$distname='Thiruvananthapuram';}
              if($district==6){$distname='Kollam';} 
              if($district==2){$distname='Eranakulam';} 
              if($district==13){$distname='Thrissur';} 
              if($district==8){$distname='Kozhikode';} 
                    echo '<table align=left >';
                    echo'<tr><td>' . _('Corporation Name') . ':</td>';
                    echo '<td><select name="lsgName" readonly id="lsgName" tabindex=8 style="width:190px">';
                    echo "<option value='".$district."'>".$distname."</option>"; 
                    echo '</select></td></tr>';    
                    echo '</table>';      
        }
    }elseif($LSGtypeid==2){
            //echo"2222222";
        echo '<table align=left >';
        echo'<tr><td width=159px>' . _('Municipality Name') . ':</td>';    
        
        $sql="SELECT * FROM bio_municipality WHERE country=$nationality AND state=$state AND district=$district";
        $result=DB_query($sql,$db);
        
        echo '<td><select name="lsgName" tabindex=8 id="lsgName" style="width:222px">';
         $f=0;
         while($myrow1=DB_fetch_array($result))
         {
         if ($myrow1['id']==$LSG_name)
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
         echo $myrow1['id'] . '">'.$myrow1['municipality'];
         echo '</option>';
         $f++;
         }

      echo '</select></td></tr>';
      echo'</table>'; 
    }elseif($LSGtypeid==3){
        echo '<table align=left >';
        echo'<tr><td width=159px>' . _('Block Name') . ':</td>';    
        $sql="SELECT * FROM bio_block WHERE country=$nationality AND state=$state AND district=$district";
        $result=DB_query($sql,$db);
         
         echo '<td><select name="lsgName" id="lsgName" style="width:222px" tabindex=8>';
         $f=0;
         while($myrow1=DB_fetch_array($result))
         {
         if ($myrow1['id']==$LSG_name)
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
         echo $myrow1['id'] . '">'.$myrow1['block'];
         echo '</option>';
         $f++;
         }

      echo '</select></td>';
      echo'</tr>';
      
      echo '<tr><td width=159px>' . _('Panchayat Name') . ':</td>';         
         
         $sql="SELECT * FROM bio_panchayat WHERE country=$nationality AND state=$state AND district=$district AND id=$block_name";
         $result=DB_query($sql,$db);
         
         echo '<td><select name="gramaPanchayath" id="gramaPanchayath" style="width:222px" tabindex=9>';
         $f=0;
         while($myrow1=DB_fetch_array($result))
         {
         if ($myrow1['id']==$block_name)
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
         echo $myrow1['id'] . '">'.$myrow1['name'];
         echo '</option>';
         $f++;
         }

      echo '</select></td></tr>';                    
      echo'</table>';  
    }
    echo'</div>';
    echo'</td></tr>';     
    
      
}else{
     echo '<tr><td>' . _('LSG Type') . ':</td>';
    echo '<td><select name="lsgType" id="lsgType" tabindex=7 style="width:222px" onchange=showblock(this.value)>';
    echo '<option value=0></option>'; 
    echo '<option value=1>Corporation</option>';
    echo '<option value=2>Muncipality</option>';
    echo '<option value=3>Panchayat</option>';           
    echo '</select></td></tr>'; 
    echo '<tr><td align=left colspan=2>';
echo'<div style="align:right" id=block>';
echo'</div>';
echo'</td></tr>';
//echo"<tr id='showgramapanchayath'></tr>";
    
 
}


 
echo '<tr><td>' . _('LSG (Ward No/Ward Name)') . ':</td>
      <td><input  type="Text" name="lsgWard" tabindex="10" id="lsgWard" style=width:220px maxlength=15 value="'.$LSG_ward.'"></td></tr>';
if($eid!="" && $district!="")  {  
    echo"<tr><td>Taluk*</td><td>";
    $sql="SELECT * FROM bio_taluk WHERE country=$nationality AND state=$state AND district=$district ORDER BY bio_taluk.taluk ASC";
    $result=DB_query($sql,$db);
    echo '<select name="taluk" id="taluk" style="width:222px" tabindex=11  onchange="showvillage(this.value)">';
    $f=0;
    while($myrow1=DB_fetch_array($result))
    {
      if ($myrow1['id']==$taluk)
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
      echo $myrow1['id'] . '">'.$myrow1['taluk'];
      echo '</option>';
      $f++;
    }
    echo '</select>';
    echo'</td>';        
    echo"</tr>";  
}else{
    echo"<tr id='showtaluk'></tr>";  
}

if($eid!="")  {  
    echo"<tr id='showvillage'>";
        echo"<td>Village</td><td>";
    $sql="SELECT * FROM bio_village WHERE bio_village.country=$nationality AND bio_village.state=$state AND bio_village.district=$district";
    if($taluk!="" OR $taluk!=NULL){
        if($taluk!=0){
            $sql.=" AND bio_village.taluk=$taluk";
        }
        
    }
//    echo$sql;
    $result=DB_query($sql,$db);
  echo '<select name="village" id="village" tabindex=12 style="width:222px">';
   $f=0;
  while($myrow1=DB_fetch_array($result))
  {
  if ($myrow1['id']==$village)
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
    echo $myrow1['id'] . '">'.$myrow1['village'];
    echo '</option>';
    $f++;
   }

  echo '</select>';
  echo'</td>';
  echo"</tr>";  
  
}else{
    
   echo"<tr id='showvillage'></tr>";
}   
//echo '<tr><td>' . _('Village Name'). ':</td>
//          <td><input tabindex=9 type="Text" name="village" id="village" style=width:190px maxlength=15 value="'.$village.'"></td></tr>';  
   
echo"<tr><td ></td>";
echo"<td width=25%><input type='checkbox' name='address' id='address' value='1' tabindex=13 onclick='addAddress()'>Add More Details</td></tr>";
echo'</table>'; 

echo"<div id='addressdiv'>";
echo'<table style=width:100% border=0>'; 
echo"<tr><td width=33%>Houseno / Buildingno</td>";  
echo "<td><input type='text' name='Houseno' id='Houseno' tabindex=14 style='width:220px' value='".$houseno."'></td>";    
echo"<tr><td>HouseName / Org Street</td>"; 
echo "<td><input type='text' name='HouseName' id='HouseName' tabindex=15 style='width:220px' value='".$housename."' ></td>";
echo"<tr><td>Residential / Org Area</td>"; 
echo "<td><input type='text' name='Area1' id='Area1' value='".$area1."' tabindex=16 style='width:220px'></td>";
echo"<tr><td>Post office</td>"; 
echo "<td><input type='text' name='Area2' id='Area2' value='".$area2."' tabindex=17  style='width:220px'></td>";
echo"<tr><td>Pin</td>"; 
echo" <td><input type='text' name='Pin' id='Pin' value='".$pin."' tabindex=18 style='width:220px'></td></tr>";
echo'</table>';   
 echo"</div>";  

//echo"<tr><td width=36%></td>";
echo"<input type='hidden' name='leadid' id='leadid' value='".$leadid."'>";

//echo"<tr><td width=36%></td>";
echo"<input type='hidden' name='orderid' id='orderid' value='".$orderid."'>";
 
echo'<table style=width:100% border=0>'; 

echo"<tr><td width=33%>Complaint Source</td>";
echo '<td><select required name="source" id="source" style="width:222px" tabindex=19 >';
$sql1="select * from bio_incidentsource";
$result1=DB_query($sql1,$db);
 $f=0;    
while($row1=DB_fetch_array($result1))
{
    if ($row1['id']==$source) 
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
    echo $row1['id'].'">'.$row1['source'];
    echo '</option>';

}

echo '<tr><td width=33%>Complaint Type</td>';
echo '<td><select required name="type" id="type" style="width:222px" tabindex=20>';
$sql1="select * from bio_incidenttype";
$result1=DB_query($sql1,$db);
 $f=0;    
while($row1=DB_fetch_array($result1))
{
    if ($row1['id']==$type) 
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
    echo $row1['id'].'">'.$row1['type'];
    echo '</option>';

}  

echo '<tr><td style="width:33%">Customer Type</td>';
    echo  '<td>';
    echo '<select required name="enquiry" id="enquiry" style="width:222px" tabindex=21  onchange="showinstitute(this.value)">';
    $sql1="SELECT * FROM bio_enquirytypes"; 
    $result1=DB_query($sql1,$db);
    $f=0;
    while($myrow1=DB_fetch_array($result1))
    { 
    if ($myrow1['enqtypeid']==$cust_type) 
    {
       
    echo '<option selected value="';
    
    } else {
        if ($f==0) 
        {
        echo '<option>';
        }
        echo '<option value="';
        $f++;
    }
    echo $myrow1['enqtypeid'] . '">'.$myrow1['enquirytype'];
    echo '</option>';
    }
     
    echo '</select>';    
    echo '</td></tr>';
 
echo"<tr><td width=33%>Complaint On</td>";
echo"<td><select name='complaint' id='complaint' tabindex=22 style='width:219px' >";

            $sql55="SELECT * FROM bio_complainttypes order by complaint asc"; 
    $result15=DB_query($sql55,$db);
    $f=0;
    while($myrow15=DB_fetch_array($result15))
    { 
    if ($myrow15['id']==$complaint) 
    {
       
    echo '<option selected value="';
    
    } else {
        if ($f==0) 
        {
        echo '<option>';
        }
        echo '<option value="';
        $f++;
    }
    echo $myrow15['id'] . '">'.$myrow15['complaint'];
    echo '</option>';
    }


echo "</select></td></tr>"; 

echo '<tr><td width=33%>Complaint Description</td><td><textarea name="description" required   tabindex=23 id="description" rows="2" cols="31">'.$description.'</textarea> </td></tr>';
echo '<tr><td width=33%>Remarks</td><td><textarea name="remarks" id="remarks" tabindex=24  rows="2" cols="31">'.$remarks.'</textarea> </td></tr>';

 echo '<tr><td width=33%>Priority</td>'; 
echo '<td><select name="priority" id="priority" tabindex=25  style="width:222px" onchange="addPriority(this.value)">';
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
     
    echo'<tr id="prioritydiv"></tr>';
     
     
echo '<tr><td width=33%>Status</td>';
echo '<td><select name="status" id="status" style="width:222px">';
$sql1="select * from bio_incidentstatus";
$result1=DB_query($sql1,$db);
 $f=0;    
while($row1=DB_fetch_array($result1))
{
    if ($row1['id']==$status) 
    {
       
    echo '<option selected value="';
    
    } else {
        if ($f==0) 
        {
        echo '<option value="'; 
        }
        echo '<option value="';
               $f++;   
    }
    echo $row1['id'].'">'.$row1['status'];
    echo '</option>';

}    
     echo '</select></td></tr>';
        
     echo '<input type="hidden" name="SelectedType" value='.$ticketid.'>&nbsp;'; 
     echo '<input type="hidden" name="SelectedType1" value='.$eid.'> &nbsp;';
     echo '<input type="hidden" name="SelectedType2" value='.$cust_id.'> &nbsp;';   
     echo '<input type="hidden" name="mail" value='.$email.'> &nbsp;';    
     echo "<input type='hidden' name='incident' id='incident' value='".$incident_id."'>";      
     echo "<input type='hidden' name='debtorno' id='debtorno' value='".$_GET['debtorno']."'>";

if(!isset($_GET['edit']))     
{                                                             
echo'<tr><td></td><td> <input type="Submit" name="submit" tabindex=27  value="' . _('Add Complaint') . '" onclick=" if(validate()==1)return false">&nbsp;&nbsp;<input type="submit" tabindex=28  action="Reset" value="' . _('Clear') . '"></td></tr>'; 
}else{
echo'<tr><td></td><td> <input type="Submit" tabindex=27  name="update" value="' . _('Update') . '" onclick=updateincident()> </td></tr>';    
}       
echo"</table>";
echo"</fieldset></div>";  
 echo "<br /><div id='incidentdiv'></div>";    
  
 
 
      echo"<div id='incidentgrid'>";
      echo "<fieldset style='width:600px'>";
      echo "<legend><h3>Complaint Register</h3></legend>";
      echo "<div style='overflow:scroll;height:150px'>";
      echo "<table class='selection' style='width:100%'>";
      echo '<tr><th>' . _('Slno') . '</th>
                <th>' . _('Ticket No') . '</th>  
                <th>' . _('Name') . '</th>   
                <th>' . _('Incident Type') . '</th> 
                <th>' . _('Title') . '</th>   
                <th>' . _('Priority') . '</th>
                <th>' . _('Status') . '</th></tr>';
      $empid=$_SESSION['empid'];
     $sql_emp1="SELECT * FROM bio_emp
                WHERE empid=".$empid;
     $result_emp1=DB_query($sql_emp1,$db);
     $myrow_emp1=DB_fetch_array($result_emp1);
     $myrow_emp1['designationid'];
     $sql1="SELECT
    `bio_incidents`.`ticketno`
    , `bio_incidenttype`.`type`
    , `bio_incident_cust`.`custname`
    , `bio_incident_cust`.`custphone`
    , `bio_incidentstatus`.`status`
    , `bio_complainttypes`.`complaint`
    , `bio_priority`.`priority`
FROM
    `bio_incidents`
    LEFT JOIN `bio_incident_cust` 
        ON (`bio_incidents`.`cust_id` = `bio_incident_cust`.`cust_id`)
    LEFT JOIN `bio_complainttypes` 
        ON (`bio_incidents`.`title` = `bio_complainttypes`.`id`)
    LEFT JOIN `bio_incidenttype` 
        ON (`bio_incidents`.`type` = `bio_incidenttype`.`id`)
    LEFT JOIN `bio_incidentstatus` 
        ON (`bio_incidents`.`status` = `bio_incidentstatus`.`id`)
    LEFT JOIN `bio_priority` 
        ON (`bio_incidents`.`priority` = `bio_priority`.`id`) 
    WHERE  bio_incidents.mainmailcategory=5 
    AND   bio_incidents.submailcategory=1
	AND   bio_incidents.status!=3";
     if($myrow_emp1['designationid']==1 || $myrow_emp1['designationid']==4 || $myrow_emp1['designationid']==24){
         $sql1=$sql1;
     }else{     
         $sql1.=" AND bio_incidents.createdby IN ($user_array)";
     }
     $sql1.=" ORDER BY bio_incidents.ticketno DESC";
     $result1=DB_query($sql1, $db);  
     $k=0 ; $slno=0; 
     while($row1=DB_fetch_array($result1) ){ 
         if ($k==1){
             echo '<tr class="EvenTableRows">';
             $k=0;
         } else {
             echo '<tr class="OddTableRows">';
             $k=1;
         }
         $slno++;  
         $id=$row1['ticketno'];
         $leadid=$row1['leadid'];
         $orderid=$row1['orderno'];
         $cust_id=$row1['cust_id'];
         $custname=$row1['custname'];
         $type=$row1['type'];   
         $title=$row1['complaint'];
         $priority=$row1['priority'];
         $status=$row1['status'];  
         $incidentflag=1;
         $DebtorNo=$row1['debtorno'];
         echo"<tr style='background:#A8A4DB'>
                                    <td>$slno</td> 
                                    <td>$id</td>  
                                    <td>$custname</td>
                                    <td>$type </td> 
                                    <td>$title </td>
                                    <td>$priority </td>
                                    <td>$status</td>
                                    <td><a href='#' id='$row1[ticketno]' onclick='edit(this.id)'>Edit</a></td>";  
         if($orderid=='0' AND $leadid=='0' AND $DebtorNo==NULL){
             echo" <td><a style='cursor:pointer;' id='$row1[ticketno]' onclick='addCustomer(this.id,$incidentflag)'>Add as Customer</a></td>";   
         }
     }      
     echo"</tr></table></div></fieldset></div></form>";
     echo '</td>';
     echo "<td id='alert'></td>";  
     echo'</tr></table>';                      
//include('includes/footer.inc');   
?>
    

      
<script type="text/javascript">
//document.getElementById("custname").focus();
              // document.getElementById("lsgType").disabled=true;    
//$(document).ready(function() {
 // });  
 
 var addr=document.getElementById("address").checked;
 if(addr==false){
   $("#addressdiv").hide();  
 }

 
 
 function addCustomer(str1,str2){
//   alert(str2);
   window.location="Customers.php?ticketno=" + str1 +"&incident="+str2;      
 }

 /*function addPriority(str){
   // alert(str); 

if (str=="")
  {
  document.getElementById("prioritydiv").innerHTML="";
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
    document.getElementById("prioritydiv").innerHTML=xmlhttp.responseText;

    }
  }
xmlhttp.open("GET","bio_setPriority.php?pr=" + str,true);
xmlhttp.send();
}  */
       


 /*
 function addPriority(str){
     alert(str);
//var str=document.getElementById("address").value;
if (str=="")
  {
  document.getElementById("prioritydiv").innerHTML="";
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
    document.getElementById("prioritydiv").innerHTML=xmlhttp.responseText;

    }
  }
xmlhttp.open("GET","bio_setPriority.php?pr=" + str,true);
xmlhttp.send();
 } 
            */
  
 var mail=document.getElementById("email").value;
 if(mail!='') {
  $('#incidentdiv').hide(); 
  sendemail(mail);  
 }
 
 function addAddress(str) {
    addr=document.getElementById("address").checked;
    if(addr==true){
       $('#addressdiv').fadeIn(1000); 
    }else if(addr==false){
       $('#addressdiv').fadeOut(1000); 
    } 
 }

  function showinstitute(str)
  {
      
   if (str=="")
  {
  document.getElementById("complaint").innerHTML="";
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
    document.getElementById("complaint").innerHTML=xmlhttp.responseText;

    }
  }
xmlhttp.open("GET","bio_complt.php?enq=" + str,true);
xmlhttp.send();      
      
      
  }
 
 
function sendemail(str){
//var str=document.getElementById("address").value;
    
if (str=="")
  {
  document.getElementById("incidentdiv").innerHTML="";
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
    document.getElementById("incidentdiv").innerHTML=xmlhttp.responseText;

    }
  }
xmlhttp.open("GET","bio_sendmail.php?mail=" + str,true);
xmlhttp.send();   
} 
  
function dlt(str){
        //alert(str);
        //str1=document.getElementById("cust_id").value;
        location.href="?delete=" +str; 
}
function edit(str)
{
    location.href="?edit=" +str; 
}
function searchCustomer(){
    $('#incidentgrid').hide();    
    var str=document.getElementById("custname").value;
    var str1=document.getElementById("phno").value;
    var str2=document.getElementById("email").value;
     //ar str3=document.getElementById("orderid").value;
    var x=str1;  
    if(isNaN(x)||x.indexOf(" ")!=-1)
    {  
        f=1;
        alert("Please enter Numeric value for Mobile number"); document.getElementById('phno').focus();
        if(x=""){f=0;}
        return f; 
    }
    if (str!="")
    {
        var id="&name="+str;
        if(str1!=""){
            id+="&pno="+str1;  
        }if(str2!=""){
            id+="&email="+str2;  
        }
    }else if(str1!=""){
        id="&pno="+str1;
        if(str2!=""){
            id+="&email="+str2;  
        }  
    }else if(str2!=""){
        id="&email="+str2;  
    }else{
        document.getElementById("custname").innerHTML="";
        return; 
    }  
 // alert(id);
    var id1=0;
 /* var str1=document.getElementById("phno").value;       
if (str1=="")
  {
  document.getElementById("phno").innerHTML="";
  return;
  }
 else{
     var id+="&phno="+str1;
 }                            */
 
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
            document.getElementById("incidentdiv").innerHTML=xmlhttp.responseText;
        }
    } 
    xmlhttp.open("GET","bio_selectIncidents.php?id="+id1+id,true);
    xmlhttp.send();   
    
    
 if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp1=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp1=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp1.onreadystatechange=function()
  {
  if (xmlhttp1.readyState==4 && xmlhttp1.status==200)
    {
    var message=document.getElementById("alert").value=xmlhttp1.responseText;      
    if(message>0)
    {
        alert("Complaint already registered");
    }
    }
  }
//  alert(str);
xmlhttp1.open("GET","bio_selectIncidents_alert.php?alertmessage="+id1+id);
xmlhttp1.send();
     
}
      
      
    function selectIncident(ticketid){
              // alert(ticketid) ;
    if (ticketid=="")
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


    }
  }
xmlhttp.open("GET","bio_viewIncidents.php?ticketno=" + ticketid,true);
xmlhttp.send();
     
   }  
     function selectLead(lead){
   // alert(lead);
    location.href="?selectLead=" +lead; 
   }  
     function selectOrder(order){
 //  alert(order);
 location.href="?selectOrder=" +order; 
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
xmlhttp.open("GET","bio_showstate_int.php?country=" + str,true);
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
xmlhttp.open("GET","bio_showstate_int.php?state=" + str + "&country1=" + str1,true);
xmlhttp.send();

}
  
     

function validate()
{     
  
//    document.getElementById('phone').focus();
var f=0;

if(f==0){f=common_error('custname','Please enter your Name');  if(f==1){return f; }  }
if(f==0)
{
    var y=document.getElementById('phno').value; 
    var x=document.getElementById('email').value;    
    var z=document.getElementById('landno_no').value;  
    
    var lsgtype=document.getElementById('lsgType').value;  

    if(x=="" && y=="" && z=="" ){ alert("Please enter atleast one contact number");f=1;} 
    if(f==1) { document.getElementById('phno').focus();return f; } }

if(f==0){f=common_error('Districts','Please select the District');  if(f==1){return f; }  } 
if(f==0){f=common_error('lsgType','Please select a LSG Type');  if(f==1){return f; }  } 
 $sourc=document.getElementById('lsgName').value;alert(sourc); 
if(f==0){f=common_error('lsgName','Please select a LSG Name');  if(f==1){return f; }  }  
if(lsgtype==3){
 if(f==0){f=common_error('gramaPanchayath','Please select a Panchayath');  if(f==1){return f; }  }    
}


//if(f==0){f=common_error('taluk','Please select a taluk');  if(f==1){return f; }  } 
//if(f==0){f=common_error('village','Please select a Village');  if(f==1){return f; }  }  

   
if(f==0){f=common_error('source','Please enter the Incident Source');  if(f==1){return f; }  }
if(f==0){f=common_error('type','Please select the Incident Type');  if(f==1){return f; }  }
if(f==0){f=common_error('enquiry','Please select the Customer Type');  if(f==1){return f; }  } 
if(f==0){f=common_error('complaint','Please select a Compalint');  if(f==1){return f; }  } 
//if(f==0){f=common_error('title','Please enter the Incident Titile');  if(f==1){return f; }  }  
if(f==0){f=common_error('description','Please enter the Incident Description');  if(f==1){return f; }  } 
if(f==0){f=common_error('priority','Please select the Priority');  if(f==1){return f; }  }      
//if(f==0){f=common_error('status','Please select the Status');  if(f==1){return f; }  }  
     
}   
 function validate1() 
{
 
   var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
   var address = document.getElementById('email').value;
   if(reg.test(address) == false) {
     alert('Invalid Email Address');  
     document.getElementById('email').focus();  

                         
      return false;
   }
}

function isnumeric(stext)
{
var validchar="0123456789";
var v=true;
var i;
for(i=0;i<stext.length;i++)
{
var c=stext.charAt(i);
if(validchar.indexOf(c)==-1)
{
v=false;
break;
}
}
return v;
}

function numericnumber()
{
var num=document.getElementById('phno').value;      
 
 if(!isnumeric(num))

{
alert('Enter a valid number');
document.getElementById('phno').focus();
valid=false;
}
    
}

 function showtaluk(str){   
     str1=document.getElementById("country").value;
     str2=document.getElementById('state').value;
     str3=document.getElementById('Districts').value;
//alert(str1);   alert(str2);       alert(str3);
if (str1=="")
  {
  document.getElementById("showtaluk").innerHTML="";
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
xmlhttp.open("GET","bio_CustlsgSelection_int.php?taluk=" + str +"&country1="+ str1 +"&state2="+ str2 +"&district="+ str3,true);
xmlhttp.send(); 
} 

 function showblock(str){   
      document.getElementById("lsgType").disabled=false;    
     str1=document.getElementById("country").value;
     str2=document.getElementById('state').value;
     str3=document.getElementById('Districts').value;
     if(str==1 && (str3==1 || str3==3 || str3==4 || str3==5 || str3==7 || str3==9 || str3==10 || str3==10 || str3==14)){
         alert("No Corporation for this district");
         document.getElementById("block").innerHTML="";
         return;
     }
//alert(str1);   alert(str2);       alert(str3);
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
xmlhttp.open("GET","bio_CustlsgSelection_int.php?block=" + str +"&country1="+ str1 +"&state1="+ str2 +"&district="+ str3,true);
xmlhttp.send(); 
} 

  function showgramapanchayath(str){   
   //alert(str);
     str1=document.getElementById("country").value;
     str2=document.getElementById('state').value;
     str3=document.getElementById('Districts').value;
//alert(str1);   alert(str2);       alert(str3);
if (str=="")
  {
  document.getElementById("showgramapanchayath").innerHTML="";
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
     document.getElementById("showgramapanchayath").innerHTML=xmlhttp.responseText; 
       
    }
  } 
xmlhttp.open("GET","bio_CustlsgSelection_int.php?grama=" + str +"&country1="+ str1 +"&state2="+ str2 +"&district="+ str3,true);
xmlhttp.send(); 
} 

  function showvillage(str){   
  // alert(str);
     str1=document.getElementById("country").value;
     str2=document.getElementById('state').value;
     str3=document.getElementById('Districts').value;
//alert(str1);   alert(str2);       alert(str3);
if (str=="")
  {
  document.getElementById("showvillage").innerHTML="";
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
xmlhttp.open("GET","bio_CustlsgSelection_int.php?village=" + str +"&country1="+ str1 +"&state2="+ str2 +"&district="+ str3,true);
xmlhttp.send(); 
} 

</Script>
