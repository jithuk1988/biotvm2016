<?php
  $PageSecurity = 20;
include('includes/session.inc');
$title = _('PassiveLeads');

       $sql34="Select value from bio_changepolicy where policyname='No of Days to became a lead passive' ";
                          $result34=DB_query($sql34,$db);   
     $myrow34=DB_fetch_array($result34,$db);
                                    $noday=$myrow34[0];
                                    
  $empid=$_SESSION['empid'];
 $employee_arr=array();   
     $sql_drop="DROP TABLE `emptable`";
     $result_drop=DB_query($sql_drop,$db);
 
                      $sql_create="CREATE TABLE emptable (empid int)";
                      $result_create=DB_query($sql_create,$db);   
                       
                  function showemp($empid,$db)         
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

                      showemp($empid,$db);                           
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
        showemp($empid,$db);
         
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
  


 $sql1="SELECT 
     bio_leads.leadid, 
     bio_leads.leaddate,
     bio_leads.cust_id, 
     bio_cust.custname, 
     bio_status.biostatus, 
     bio_leads.teamid,
     bio_leadteams.teamname,
     bio_cust.custmob,
     bio_cust.custmail,
     bio_leads.enqtypeid
     FROM 
     bio_status,
     bio_leads, 
     bio_cust, 
     bio_leadteams 
     WHERE 
 bio_cust.cust_id=bio_leads.cust_id 
     AND bio_leadteams.teamid=bio_leads.teamid 
     AND bio_leads.leadstatus=15
     AND bio_status.statusid=bio_leads.leadstatus
     AND bio_leads.created_by IN ($user_array) 
     ";
$result1=DB_query($sql1,$db);

        //       bio_leads.leaddate > (CURDATE() - INTERVAL $noday DAY) 


if(!isset($_POST['excelview'])){
    
                 include('includes/header.inc'); 

print'<script>
          myRef = window.open("bio_lead_autochange.php","autochange","toolbar=no,location=no,directories=no,status=yes,menubar=no,scrollbars=yes,resizable=no,width=600,height=400");
          </script> ';
  include('includes/sidemenu.php'); 
  echo '<center><font style="color: #333;
                           background:#fff;
                           font-weight:bold;
                           letter-spacing:0.10em;
                           font-size:16px;
                           font-family:Georgia;
                           text-shadow: 1px 1px 1px #666;">PASSIVE LEADS</font></center>';   
    
 
echo "<table style='width:60%'><tr><td>";
echo "<fieldset style='width:97%;'>";     
echo "<legend><h3>Showing all Passive Leads</h3>";
echo "</legend>";   


    echo '<form name="activeLeadsForm"  method="post" action="' . $_SERVER['PHP_SELF'] . '?' .SID .'">';
    echo "<div>";
    echo "<div style='height:350px; overflow:auto;'>";
    echo "<table style='border:0px solid #F0F0F0;width:100%'>";
    
    echo'<tr><th class="viewheader">slno</th>';
    echo'<th class="viewheader">Lead no</th>';
    echo'<th class="viewheader">Customer Name</th>';
    echo'<th class="viewheader">Team</th>';
    echo'<th class="viewheader">Lead Date</th>';
    echo'<th class="viewheader">Phone No </th>';
    echo'<th class="viewheader">Email</th>';
             echo'<th class="viewheader">Status</th>';

   // echo'</tr>';
    
    $slno=1;
    $k=0;
    while($myrow3=DB_fetch_array($result1,$db))     {
    
      
   //   $lead_ID=$myrow3['leadid'];
     $enqtype=$myrow3['enqtypeid'];  
//      $date1=$myrow3['leaddate'];
//      $date2=date("Y-m-d");

//      $datearr1 = split("-",$date1); 
//      $datearr2 = split("-",$date2); 
//      
//      $date_diff= gregoriantojd($datearr2[1], $datearr2[2], $datearr2[0]) - gregoriantojd($datearr1[1], $datearr1[2], $datearr1[0]); 
    // if($date_diff>20){  
//     
//     $sql2="SELECT teamname FROM bio_leadteams
//            WHERE teamid=".$myrow3['teamid'];
//     $result2=DB_query($sql2,$db);   
//     $myrow2=DB_fetch_array($result2,$db);
//        
//    $sql4="SELECT custname FROM bio_cust
//        WHERE cust_id=".$myrow3['cust_id'];
//     $result4=DB_query($sql4,$db);   
//     $myrow4=DB_fetch_array($result4,$db);
        
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
    echo'<td>'.$slno.'</td>';
//    echo'<td>'.$myrow3['reqno'].'</td>';
    echo'<td>'.$myrow3['leadid'].'</td>';
    echo'<td>'.$myrow3['custname'].'</td>';
    echo'<td>'.$myrow3['teamname'].'</td>';
    echo'<td>'.ConvertSQLDate($myrow3['leaddate']).'</td>';
    echo'<td>'.$myrow3['custmob'].'</td>';
    echo'<td>'.$myrow3['custmail'].'</td>';
    
    
  echo'<td>'.$myrow3['status'].'</td>';
    if($enqtype==1){
        echo "<td><a style=cursor:pointer; id=".$myrow3['leadid']." onclick=viewDetails('$myrow3[leadid]','$enqtype')>Create Proposal</a></td>";
    }elseif($enqtype==2){
        echo "<td><a style=cursor:pointer; id=".$myrow3['leadid']." onclick=viewDetails('$myrow3[leadid]','$enqtype')>Feasibility Study</a></td>";
    }
    
    
    
    echo'</tr>';    
    $slno++;   
   // }
    
    }
    

    
    echo "</td></tr></fieldset>"; 
echo'</table>';
    
//    echo'<tr><td><a href="bio_pendingleads_A5p?item='.$itemcode.' &view=1 &id=1 &season='.$season.'">view all WOs against this item</a></td></tr>';
      echo"</div>";

    
     
  if($slno==1){
    echo'<input type="submit" disabled name="excelview" id=2 value="view as excel">';
    
    }
    else
    {
              echo'<input type="submit" name="excelview" id=2 value="view as excel">';

    } 
    
  
  echo"</div>";
      
    
   //   echo "</table>";
   
    
   
    
   
    echo'</form>';
  ?>  
  <script>
    function viewDetails(str1,str2){
         
        if(str2==1){
            window.location="bio_proposal.php?lead=" + str1;
        }
        else if(str2==2){
            window.location="bio_feasibilitystudy.php?lead=" + str1;
        }
    }
    </script>  
    
    
<?php      
//  include('includes/footer.inc');
}

   
    
    
 if(isset($_POST['excelview'])){ 
$filename="passiveleads.csv";

   $header= "Slno".","."Lead No".","."Customer Name".","."Team".","."Lead Date".","."Phone no".","."Email."."\n";"\n";
    $data='';
    $slno=1;
       
        
    $sql4="SELECT 
     bio_leads.leadid, 
     bio_leads.leaddate,
     bio_leads.cust_id, 
     bio_cust.custname, 
     bio_status.biostatus, 
     bio_leads.teamid,
     bio_leadteams.teamname,
     bio_cust.custmob,
     bio_cust.custmail 
     FROM 
     bio_status,
     bio_leads, 
     bio_cust, 
     bio_leadteams 
     WHERE 
 bio_cust.cust_id=bio_leads.cust_id 
     AND bio_leadteams.teamid=bio_leads.teamid 
     AND bio_leads.leadstatus=15
     AND bio_status.statusid=bio_leads.leadstatus";
     $result4=DB_query($sql4,$db);   
   while(  $myrow4=DB_fetch_array($result4,$db))
{
    $data= $data.$slno.",".$myrow4['leadid'].",".$myrow4['custname'].",".$myrow4['teamname'].",".ConvertSQLDate($myrow4['leaddate']).",".$myrow4['custmob'].",".$myrow4['custmail']."\n";    
    $slno++;    
      
}
    
header("Content-type: application/x-msdownload"); 
header("Content-Disposition: attachment; filename=$filename"); 
header("Pragma: no-cache"); 
header("Expires: 0");  
echo "$header\n$data";  
 }    
         
?>

