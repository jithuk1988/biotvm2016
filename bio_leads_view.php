<?php
  $PageSecurity = 80;
include('includes/session.inc');
$title = _('Lead Details');
include('includes/header.inc');
include('includes/sidemenu.php');
/*echo '<center><font style="color: #333;
    background:#fff;
    font-weight:bold;
    letter-spacing:0.10em;
    font-size:16px;
    font-family:Georgia;
    text-shadow: 1px 1px 1px #666;">LEADS DETAILS</font></center>';*/
if($_GET['edit']!="")
  {
      
            $rid=$_GET['edit'];
                $sql="SELECT bio_cust.nationality,bio_cust.state,bio_cust.district
                     
                FROM bio_leads
                inner join bio_cust on (bio_cust.cust_id=bio_leads.cust_id)
               WHERE bio_leads.leadid=$rid";
                  $result=DB_query($sql,$db);
   $row=DB_fetch_array($result);
  $con=$row['nationality']; 
   $sta=$row['state'];
  $dist=$row['district'];
      
           
    $sql_cce="SELECT www_users.empid,
                     bio_teammembers.teamid 
                FROM www_users,bio_teammembers 
               WHERE bio_teammembers.empid=www_users.empid";
               
    
    
               
               
if($con==1 && $sta==14)        //KERALA
   {                  
       if( $dist==6 || $dist==11 || $dist==12 )    //KLM-PTA-TVM
       {
           if($_SESSION['UserID']=='ccetvm2')
           {
              $sql_cce.=" AND www_users.userid='".$_SESSION['UserID']."'"; 
           }
           
           else if($_SESSION['UserID']=='ccetvm3')
           {
                  $sql_cce.=" AND www_users.userid='".$_SESSION['UserID']."'";  
           }
           else{
              $sql_cce.=" AND www_users.userid='".ccetvm1."'";
           }
       }
       elseif( $dist==1 || $dist==2 || $dist==3 || $dist==7 || $dist==13 ) //ALP-EKM-IDK-KTM-TRS
       {
           $sql_cce.=" AND www_users.userid='".cceeklm2."'";                    
       }
       elseif( $dist==4 || $dist==5 || $dist==8 || $dist==9 || $dist==10 || $dist==14 ) //KNR-KSR-KZH-MLP-PLK-WND
       {
           $sql_cce.=" AND www_users.userid='".ccekoz1."'";
       }
   } 
   elseif($con==1 && $Sta!=14)     //OUTSIDE KERALA
   {
       $sql_cce.=" AND www_users.userid='".bdm_national."'";
   }elseif($con!=1){                           //OUTSIDE INDIA
       $sql_cce.=" AND www_users.userid='".bdm_international."'";
       
   }
   
   $result_cce=DB_query($sql_cce,$db);
   $row_cce=DB_fetch_array($result_cce);
$teamid=$row_cce['teamid'];   //j+
  // $teamid=1;
   $assigned_date=date("Y-m-d");

    

    $sql_schedule1="SELECT task_master_id,
                           actual_task_day 
                      FROM bio_schedule 
                     WHERE schedule_master_id=17 
                 ";  
    $result_schedule1=DB_query($sql_schedule1,$db);
    $emp_ID=$_SESSION['empid'];
    
    $sql_team="SELECT teamid FROM bio_teammembers WHERE empid=".$emp_ID;
    $result_team=DB_query($sql_team,$db);
    $row_team=DB_fetch_array($result_team);
    $assignedfrm=$row_team['teamid'];
    
   while($row_schedule1=DB_fetch_array($result_schedule1))
        {       
                $taskid=$row_schedule1['task_master_id'];
                $date_interval+=$row_schedule1['actual_task_day'];
                
              
                
          $sql_leadTask="INSERT INTO bio_leadtask (taskid,
                                                         leadid,
                                                         teamid,
                                                         assigneddate,
                                                         duedate,
                                                         assigned_from,
                                                         viewstatus)
                                                  VALUES('".$taskid."',
                                                         '".$rid."',
                                                         '".$teamid."',
                                                         '".$assigned_date."',
                                                  '".date("Y-m-d",mktime(0,0,0,date("m"),date("d")+$date_interval,date("y")))."',
                                                         '".$assignedfrm."',
                                                         1)";
               $result_leadTask=DB_query($sql_leadTask,$db); 
               $assigned_date=date("Y-m-d",mktime(0,0,0,date("m"),date("d")+$date_interval+1,date("y"))); 
               $date_interval+=1; 
                }                                 
            
            
            
          
      $sql4="UPDATE bio_leads SET `leadstatus` = '0' WHERE `bio_leads`.`leadid` =$rid";  
DB_query($sql4,$db); 
   
  }
?>

<table><tr><td><b><center><font style="color: #333";
   
    font-weight:bold;
    letter-spacing:0.10em;
    font-size:24px;
    font-family:Georgia;
    text-shadow: 1px 1px 1px #666;">LEADS DETAILS</font></center><b> </td> </tr><tr><td>
<?php echo'  <form method="post" action="' . $_SERVER['PHP_SELF'].'"> ';?>         
<fieldset style='width:80%;height:auto;'>
<legend>Search </legend>
<table >
<tr>
<td>Customer Name</td><td><input type="text" id="cname" name="cname" style="width:100px"></td>

<?php
echo '<td style="width:33%">Customer Type</td>';
    echo  '<td>';
 echo '<select required name="enquiry" id="enquiry" style="width:100px">';
    $sql1="SELECT * FROM bio_enquirytypes"; 
    $result1=DB_query($sql1,$db);
    echo '<option  value=0></option>';
    while($myrow1=DB_fetch_array($result1))
    { 
        echo '<option  value="'.$myrow1['enqtypeid'] . '">'.$myrow1['enquirytype'];
         echo '</option>';
    }
echo ' <td>Date From</td><td><input type="text" id="fdate" name="fdate" class="date"  alt="'.$_SESSION['DefaultDateFormat'].'" style="width:100px"></td>
<td>Date To</td><td><input type="text" id="tdate" name="tdate" class="date" alt="'.$_SESSION['DefaultDateFormat'].'" style="width:100px" ></td> ';                                                                                                                     
?>
<td>District</td><td><input type="text" name="ldist" id="ldist" style="width:100px"></td>
<td><input type="submit" value="Search" name="submit" id="submit"></td>
</tr>
</table>
</fieldset>
<?php echo '</form>
</td></tr>';
if(isset($_POST['submit']))
 {
echo'
<tr><td><fieldset style="width:80%;height:auto;">

<legend>Lead Details</legend>

<table width="100%" >
<tr><th width="5%">SL No</th><th width="13%">Customer Name</th><th width="10%">Contact No</th><th width="9%">Lead Date</th><th width="13%">Place</th><th width="13%">District</th>
<th width="60%">Drop Reason</th><th width="5%">Restore</th></tr><tr>
</table>
<div style="height:400px;width:850px;  overflow:auto;">
<table  style="table-layout:fixed;" width="830px" >'  ;   ?>
<?php
     $sql= "SELECT bio_leads.leadid,bio_leads.enqtypeid,bio_leads.cust_id,leaddate,remarks,bio_cust.custname,bio_cust.area1,bio_district.district,concat(bio_cust.custphone,'-</br>',bio_cust.custmob) as 'cont'
      from bio_cust 
      INNER JOIN `bio_leads` ON (`bio_leads`.`cust_id` = `bio_cust`.`cust_id` )
      LEFT JOIN bio_district ON bio_district.did = bio_cust.district
            AND bio_district.stateid = bio_cust.state
            AND bio_district.cid = bio_cust.nationality
     where
   
   bio_leads.leadstatus='20'  ";//enquiry
                               

   if($_POST['cname'] ){$sql.=" AND bio_cust.custname  LIKE '%".$_POST['cname']."%'"; }
   if($_POST['enquiry']){$sql.=" AND bio_leads.enqtypeid  =".$_POST['enquiry'];}
   if ((isset($_POST['fdate'])) && (isset($_POST['tdate'])))   {
    if (($_POST['fdate']!="") && ($_POST['tdate']!=""))  { 
    $sourcetypefrom=FormatDateForSQL($_POST['fdate']);   
    $sourcetypeto=FormatDateForSQL($_POST['tdate']);
    $sql.=" AND bio_leads.leaddate BETWEEN '".$sourcetypefrom."' AND '".$sourcetypeto."'";
    }  }
    if( $_POST['ldist'] ){
        $sql.=" AND bio_cust.district IN (SELECT did From bio_district where bio_district.district LIKE '".$_POST['ldist']."%'  )                   AND bio_cust.state IN (SELECT stateid From bio_district where bio_district.district LIKE '".$_POST['ldist']."%'  )
                    AND bio_cust.nationality IN (SELECT cid From bio_district where bio_district.district LIKE '".$_POST['ldist']."%'  )  "; }
    
 }
 
      // echo $sql;
        $result=DB_query($sql,$db);  
        $sl=1;
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
           
          $id= $myrow[leadid];  echo "<td  width='5%'  style=' overflow: auto;' >".$sl."</td> ";
            echo "<td   width='15%' style=' overflow: auto;' >".$myrow[custname]."</td> "; //    
             echo "<td  width='15%' style=' overflow: hidden;' >".$myrow[cont]."</td> ";
               echo "<td width='10%' style=' overflow: hidden;'>".ConvertSQLDate($myrow[leaddate])."</td> ";
           echo "<td  width='15%' style=' overflow: hidden;'>".$myrow[area1]."</td> ";
            echo "<td  width='18%'style=' overflow: hidden;' >".$myrow[district]."</td> ";
            echo "<td width='40%'  style=' overflow: hidden;'>".$myrow[remarks]."</td> ";
            echo "<td  width='5%'  style=' overflow: hidden;'><a href='#' id='$id' onclick='edit(this.id)'>Restore</a></td> ";//
            $sl++;
      }
 ?>
</tr>
</table>
</div>
</fieldset>
</td></tr>
</table>

<script >
 function edit(str)
 {  
 location.href="?edit=" +str;         
 
}
</script>
