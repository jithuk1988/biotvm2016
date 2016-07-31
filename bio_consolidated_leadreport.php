<?php
  $PageSecurity = 80;
include('includes/session.inc');
$title = _('Lead report');  
include('includes/header.inc');

echo '<center><font style="color: #333;
                background:#fff;
                font-weight:bold;
                letter-spacing:0.10em;
                font-size:16px;
                font-family:Georgia;
                text-shadow: 1px 1px 1px #666;">Lead Report</font></center>';


echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "?" . SID . "'>"; 
     $empid=$_SESSION['empid'];
     $sql_emp1="SELECT * FROM bio_emp
                WHERE empid=".$empid;
     $result_emp1=DB_query($sql_emp1,$db);
     $myrow_emp1=DB_fetch_array($result_emp1);
     
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
    while($row6=DB_fetch_array($result6)){
        $team_arr[]=$row6['teamid'];
    }
         
    $team_array=join(",", $team_arr);

$date=date("Y-m-d");
$sql="SELECT bio_leadtask.teamid,
             bio_leadteams.teamname
        FROM bio_leadtask,bio_leadteams
       WHERE bio_leadteams.teamid=bio_leadtask.teamid          
         AND bio_leadtask.teamid IN ($team_array)
         AND bio_leadtask.teamid not in (6,24,25,27,1,35,36,37,38,39,40,41,42,43,44,47,61,8,9)
    GROUP BY bio_leadtask.teamid order by office_id asc
         ";
         
$result=DB_query($sql,$db);
       

echo '<table style=width:1000px><tr><td>';  
echo '<fieldset style="height:700px; width:985px">';
echo '<legend><b>Institutional Leads Status</b></legend>';

/*echo '<table style=width:95%>';
echo'<tr><td>Customer type &nbsp&nbsp';
 echo '<select name="Customer" id="customer">';
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
                                                            
   echo '</select>&nbsp&nbsp&nbsp&nbsp';
 echo"<input type='submit' name='filterbut1' id='filterbut1' value='search'></td></tr>";    
   
 echo'</table>';   */
 echo "<font size='+1' style='margin-left:25%'>Consolidated Lead status report on\t".date('Y-F j')." </font> ";
 echo'<br>';
 
if(isset($_POST['filterbut1'])){
    
if ($_POST['Customer']==1){
         
echo "<table style='width:950px' border=1>";  
echo "<tr><th>Team</th>
      <th>Registered Without Registration Fee</th>
      <th>Registered With Registration Fee</th>
      <th>Partial Payment</th>
      <th>Full Payment</th>
      </tr>";
      
  $task_array=array(0,15,14,3,4,5);
$task_count=sizeof($task_count);
 //$sum_enquiry_A=0;
$sum_enquiry_D=0;
//$sum_enquiry_P=0;
//$sum_enquiry_A=array();
$sum_enquiry_D=array();
//$sum_enquiry_P=array();
while($row=DB_fetch_array($result))
{
    
   
    $with_regfee=0;
    $without_regfee=0;
    $partialpayment=0;
    $full_payment=0;
    $teamid=$row['teamid'];
    echo'<tr onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)">';
    echo'<td width=100px>'.$row['teamname'].'</td>';
   
 $sql_domestic="SELECT bio_leadtask.leadid
                      FROM bio_leadtask, 
                           bio_leads 
                     WHERE bio_leads.leadid=bio_leadtask.leadid
                       AND bio_leadtask.taskcompletedstatus=0 
                       AND bio_leadtask.viewstatus=1
                       AND bio_leadtask.teamid=$teamid
                       AND bio_leads.enqtypeid=1
                     GROUP BY bio_leadtask.leadid
                     ORDER by bio_leadtask.assigneddate ASC"; 
      
  $result_domestic=DB_query($sql_domestic,$db);                                                                                                                                                                  
  while($row3=DB_fetch_array($result_domestic))   {
  $leadid=$row3['leadid'];
  //custtype
  //reg fee fro policy
  //plant price from proposal
 $sql_type="SELECT bio_cust.custtype
              FROM bio_cust,
                   bio_leads 
             WHERE bio_cust.cust_id=bio_leads.cust_id
               AND bio_leads.leadid=".$leadid; 
 $result_type=DB_query($sql_type,$db);
 $row4=DB_fetch_array($result_type);
 $custtype=$row4['custtype'];    
     
 $sql_value="SELECT bio_changepolicy.value FROM bio_changepolicy WHERE bio_changepolicy.enquirytype=".$custtype;    
 $result_value=DB_query($sql_value,$db);
 $myrow1=DB_fetch_array($result_value);     
 $reg_fee=$myrow1['value']; 
 
 $sql_price="SELECT bio_proposal.totprice FROM bio_proposal WHERE bio_proposal.leadid=".$leadid;
 $result_price=DB_query($sql_price,$db);
 $myrow2=DB_fetch_array($result_price);     
$plant_price=$myrow2['totprice'];
if($plant_price==''){
   $plant_price=0; 
}

//=========================================================================================//  
//$sql3="select bio_advance.amount from bio_advance where bio_advance.amount>0 AND bio_advance.leadid=".$leadid;  
//  
//   $result3=DB_query($sql3,$db); 
   
//=========================================================================================// 
     
 $sql_advance="SELECT SUM(bio_advance.amount) as amount     
                  FROM bio_advance,
                       bio_leads  
                 WHERE bio_advance.leadid=bio_leads.leadid
                   AND bio_leads.enqtypeid=1 
                   AND bio_advance.leadid=".$leadid;
  $result_advance=DB_query($sql_advance,$db);
  $row_advance=DB_fetch_array($result_advance);
  $advance_amount=$row_advance['amount'];
  
  //$reg_amount=$row_advance['value']; 
  
//echo"Reg_fee".$reg_fee;  
//echo"Plant price".$plant_price;  
//echo"Advance amount ".$advance_amount;
      
  
  if($advance_amount>0){
      if($advance_amount==$reg_fee){
          $with_regfee++;
      }else if($advance_amount>$reg_fee){
           if($plant_price>0){
          if($advance_amount==$plant_price){
              $full_payment++; 
          }elseif($advance_amount<$plant_price){
              $partialpayment++;
        }  }
        elseif($plant_price==0){
         $partialpayment++;     
        }
               
      }else{
         $without_regfee++; 
      }                               
  }else{
      $without_regfee++;
  }
  
                                                                                                                                                                           
  }  

  echo'<td width=40px bgcolor="#D9D7FF" align=center>'.$without_regfee.'</td>';
  echo'<td width=40px bgcolor="#D9D7FF" align=center>'.$with_regfee.'</td>';
  echo'<td width=40px bgcolor="#D9D7FF" align=center>'.$partialpayment.'</td>';
  echo'<td width=40px bgcolor="#D9D7FF" align=center>'.$full_payment.'</td>';  
    

    echo'</tr>';
}  
//echo "</table>";

echo "<tr><th width=100px>Total</th>";
 for($i=0;$i<6;$i++){
     echo"<th width=40px><b>".$sum_enquiry_D[$i]."</b></th>";
}
 
 echo"</tr>";

}
    
 
 
 if ($_POST['Customer']==2){         
    
 echo "<table style='width:950px' border=1>"; 
echo "<tr><th>Team</th>
      <th>Primary Status</th>
      <th>FS Proposal Generated</th>
      <th>FS Registered</th>
      <th>Concept Proposal Generated</th>
      <th>DPR Generated</th>
      <th>SOR</th></tr>";
//echo "<tr><th></th><th width=40px><b>Done</th>
//              <th><th width=40px><b>Done</th>
//              <th><th width=40px><b>Done</th>
//              <th><th width=40px><b>Done</th>
//               <th><th width=40px><b>Done</th>
//              </tr>";
//echo "</table>";

//echo "<div style='height:330px; overflow:scroll;'>"; 
//echo "<table style='width:935px' border=1 >";
$task_array=array(0,15,14,3,4,5);
$task_count=sizeof($task_count);
 //$sum_enquiry_A=0;
$sum_enquiry_D=0;
//$sum_enquiry_P=0;
//$sum_enquiry_A=array();
$sum_enquiry_D=array();
//$sum_enquiry_P=array();
while($row=DB_fetch_array($result))
{
    
    $teamid=$row['teamid'];
    echo'<tr onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)">';
    echo'<td width=100px>'.$row['teamname'].'</td>';
   
    
    for($i=0;$i<6;$i++){
     if($task_array[$i]==0){
       //  $sql_enquiry_TC="SELECT COUNT(*) AS count FROM bio_leadtask,bio_leads WHERE bio_leadtask.teamid=$teamid AND taskid=".$task_array[$i]." AND taskcompletedstatus=0 AND bio_leads.leadid=bio_leadtask.leadid AND bio_leads.enqtypeid=2 AND bio_leadtask.viewstatus=1";

       $sql_enquiry_TC="SELECT COUNT(*) AS count
                          FROM  bio_leadtask,
                                bio_leads
                          WHERE bio_leadtask.teamid=$teamid
                            AND taskcompletedstatus=0 
                            AND bio_leads.leadid=bio_leadtask.leadid 
                            AND bio_leads.enqtypeid=2 
                            AND bio_leadtask.viewstatus=1";               
//        $result_TT=DB_query($sql_TT,$db);
//        $row_TT=DB_fetch_array($result_TT);   

 

     }else{
        $sql_enquiry_TC="SELECT COUNT(*) AS count
                          FROM  bio_leadtask,                     
                                bio_leads
                          WHERE bio_leadtask.teamid=$teamid
                           AND  taskid=".$task_array[$i]."
                           AND taskcompletedstatus=1 
                           AND bio_leads.leadid=bio_leadtask.leadid 
                           AND bio_leads.enqtypeid=2 
                           AND bio_leadtask.viewstatus=1";     
     }   
                                                                                                                                                                   
        
         
    $result_enquiry_TT=DB_query($sql_enquiry_TC,$db);                                                                                                             
                                                                                                                             
    $row_enquiry_TT=DB_fetch_array($result_enquiry_TT);
    
   
    if($i%2==0){
      echo'<td width=40px bgcolor="#D9D7FF" align=center>'.$row_enquiry_TT['count'].'</td>';    
  
    }else{
      echo'<td width=40px align=center>'.$row_enquiry_TT['count'].'</td>';    
    
    }
                $sum_enquiry_D[$i]+=$row_enquiry_TT['count'];        
    
    }
    echo "<td width=40px><a style=cursor:pointer;  onclick=showLeads('$teamid')>View</a></td>";

    echo'</tr>';


}  
//echo "</table>";

echo "<tr><th width=100px>Total</th>";
 for($i=0;$i<6;$i++){
     echo"<th width=40px><b>".$sum_enquiry_D[$i]."</b></th>";
}
 
 echo"</tr>";

}


 
echo "</fieldset>"; 
echo "</td></tr></table>";

echo "</div>";
echo "</form>";      
include('includes/footer.inc');
             
}    
else
{     

echo "<table style='width:950px' border=1>"; 
echo "<tr><th>Team</th>
      <th>Primary Status</th>
      <th>FS Proposal Submission</th>
      <th>FS Registered</th>
      <th>Concept Proposal Generated</th>
      <th>DPR Generated</th>
      <th>SOR</th>
      <th>Total Leads</th>
      <th>Dropped</th>
      </tr>";

$task_array=array(1,2,3,4,5,6,7,8);     
$task_count=sizeof($task_array);



$sum_count=0;
$sum_count=array();

while($row=DB_fetch_array($result))
{
    
    $teamid=$row['teamid'];
    echo'<tr onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)">';
    echo'<td width=100px>'.$row['teamname'].'</td>';
   
    
for($i=0;$i<=7;$i++){
        
if($task_array[$i]==1){                                                    // Lead Registered             
                       
$sql_count="SELECT COUNT(DISTINCT bio_leadtask.leadid) AS count
FROM bio_leadtask,bio_leads 
WHERE bio_leadtask.teamid=$teamid 
AND bio_leadtask.viewstatus=1 
AND bio_leads.enqtypeid=2    
AND bio_leads.leadstatus IN (0,2,7,45,46,47,15)
AND bio_leadtask.taskcompletedstatus=0    
AND bio_leads.leadid=bio_leadtask.leadid";             

     }elseif($task_array[$i]==2)                                           // FS Proposal Submission
     {
       
$sql_count="SELECT COUNT(DISTINCT bio_leadtask.leadid) AS count
FROM bio_leadtask,bio_leads 
WHERE bio_leadtask.teamid=$teamid 
AND bio_leadtask.viewstatus=1 
AND bio_leads.enqtypeid=2 

AND bio_leads.leadstatus IN (26,3)   
AND bio_leads.leadid=bio_leadtask.leadid";     
                                                                                                                                                                                                        
     }elseif($task_array[$i]==3)                                            // FS Registered
     {
         
$sql_count="SELECT COUNT(DISTINCT bio_leadtask.leadid) AS count
FROM bio_leadtask,bio_leads 
WHERE bio_leadtask.teamid=$teamid 
AND bio_leadtask.viewstatus=1 
AND bio_leads.enqtypeid=2      
AND bio_leads.leadstatus IN (10,11,30,17)    
AND bio_leads.leadid=bio_leadtask.leadid";  
                                              
     }elseif($task_array[$i]==4)                                             // Concept Proposal
     {
         
$sql_count="SELECT COUNT(DISTINCT bio_leadtask.leadid) AS count
FROM bio_leadtask,bio_leads 
WHERE bio_leadtask.teamid=$teamid 
AND bio_leadtask.viewstatus=1 
AND bio_leads.enqtypeid=2    
AND bio_leads.leadstatus IN (4,13,27,31,1,16,18,25)     
AND bio_leads.leadid=bio_leadtask.leadid";    
                                              
     }elseif($task_array[$i]==5)                                             // Detailed Project Report
     {
         
$sql_count="SELECT COUNT(DISTINCT bio_leadtask.leadid) AS count
FROM bio_leadtask,bio_leads 
WHERE bio_leadtask.teamid=$teamid 
AND bio_leadtask.viewstatus=1 
AND bio_leads.enqtypeid=2   
AND bio_leads.leadstatus IN (5,28,19)      
AND bio_leads.leadid=bio_leadtask.leadid";     
                                              
     }elseif($task_array[$i]==6)                                             // Sale Order
     {
         
$sql_count="SELECT COUNT(DISTINCT bio_leadtask.leadid) AS count
FROM bio_leadtask,bio_leads 
WHERE bio_leadtask.teamid=$teamid 
AND bio_leadtask.viewstatus=1 
AND bio_leads.enqtypeid=2  
AND bio_leads.leadstatus IN (6,29,34,35,36,37,38,39,40,42,43)   
AND bio_leads.leadid=bio_leadtask.leadid";   
                                             
     }elseif($task_array[$i]==7)                                             // Dropped Leads
     {     
         
$sql_count="SELECT COUNT(DISTINCT bio_leadtask.leadid) AS count
FROM bio_leadtask,bio_leads 
WHERE bio_leadtask.teamid=$teamid 
AND bio_leadtask.viewstatus=1
AND bio_leads.enqtypeid=2 
AND bio_leads.leadstatus in (0,2,7,45,46,47,15,26,3,10,11,30,17,4,13,27,31,1,16,18,25,5,28,19,6,29,34,35,36,37,38,39,40,42,43)
AND bio_leads.leadid=bio_leadtask.leadid";       

     }elseif($task_array[$i]==8)                                              // Total Leads
     {
                                
$sql_count="SELECT COUNT(DISTINCT leadid) AS count
FROM bio_leadtask 
WHERE teamid=$teamid 
AND viewstatus=2 ";          
                       
     }               
            
    
    $result_count=DB_query($sql_count,$db);                                                                                                                             
    $row_count=DB_fetch_array($result_count);
    
   
    if($i%2==0){
      echo'<td width=40px bgcolor="#D9D7FF" align=center>'.$row_count['count'].'</td>';    
  
    }else{
      echo'<td width=40px align=center>'.$row_count['count'].'</td>';    
    
    }
                $sum_count[$i]+=$row_count['count'];        
    
    }
    echo "<td width=40px><a style=cursor:pointer;  onclick=showLeads('$teamid')>View</a></td>";

    echo'</tr>';


}  


echo "<tr><th width=100px>Total</th>";
 for($i=0;$i<=7;$i++){
     echo"<th width=40px><b>".$sum_count[$i]."</b></th>";
}
 
 echo"</tr>";

}
echo "</fieldset>"; 
echo "</td></tr></table>";

echo "</div>";
echo "</form>";      
include('includes/footer.inc');          
?>

<script type="text/javascript">   

function ChangeBackgroundColor(row) { row.style.backgroundColor = "#6A5ACD"; }
function RestoreBackgroundColor(row) { row.style.backgroundColor = "#ffffff"; }

function changecolor(str)
{
    alert(str);
}
function showLeads(str1){
       
//         window.location="bio_viewleaddetails.php?q=" + str1 + "&en=" + str2;
         myRef = window.open("bio_viewleads.php?team=" + str1+ "&enq=2");
    }

</script>
