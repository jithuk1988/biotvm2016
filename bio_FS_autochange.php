<?php
$PageSecurity = 80;
include('includes/session.inc');
$title = _('Inactive FS'); 
include('includes/header.inc'); 

echo '<center><font style="color: #333;
                           background:#fff;
                           font-weight:bold;
                           letter-spacing:0.10em;
                           font-size:16px;
                           font-family:Georgia;
                           text-shadow: 1px 1px 1px #666;">INACTIVE FS</font></center>';  



 if(isset($_POST['submit']))
     
 {
   $check=$_POST['autocheck'];
//   print_r($check);
   foreach($check as $id)  {
       $autocheck.=$id.",";
   } 
 $checkid=substr($autocheck,0,-1) ;
 $sql6="UPDATE bio_leads SET leadstatus=17 where leadid=".$_POST['LeadID'];
  
  $sql5="UPDATE bio_leads SET leadstatus=17
                WHERE leadid IN ($checkid)";
     $result5=DB_query($sql5,$db);

                         ?>
           <script language="JavaScript">

/* opener.document.stock.sid.value=choose; */
window.close();

</script>


<?php
    
 }

   echo "<table style='width:60%'><tr><td>";
   echo "<fieldset style='width:97%;'>";     
   echo "<legend><h3>Showing all Actve Leads</h3>";
   echo "</legend>";   
        
   echo '<form name="activeLeadsForm"  method="post" action="' . $_SERVER['PHP_SELF'] . '?' .SID .'">';
   echo "<div style='height:350px; overflow:auto;'>";
   echo "<table style='border:0px solid #F0F0F0;width:100%'>";
//  echo'<tr><td colspan=6>Showing all Pending Leads</td>';
    echo'<tr><th class="viewheader">slno</th>';
    echo'<th class="viewheader">Lead no</th>';
    echo'<th class="viewheader">Customer Name</th>';
    echo'<th class="viewheader">Team</th>';
    echo'<th class="viewheader">Lead Date</th>';
    echo'<th class="viewheader">Phone No </th>';
    echo'<th class="viewheader">Email</th>'; 
    
    echo'<th class="viewheader">Lead Status</th>';
    echo'<th class="viewheader">Change Status</th>';  
    echo'</tr>';
    
    
 $sql34="Select value from bio_changepolicy where policyname='No of Days to became FS inactive' ";
                          $result34=DB_query($sql34,$db);   
     $myrow34=DB_fetch_array($result34,$db);
                                    $noday=$myrow34[0];

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
                                    
$sql1="SELECT bio_feasibilitystudy.leadid, bio_feasibilitystudy.proposal_date, bio_leads.cust_id, bio_cust.custname, bio_status.biostatus, bio_feasibilitystudy.teamid, bio_leadteams.teamname,
 bio_cust.custmob,
     bio_cust.custmail
FROM bio_status, bio_leads, bio_cust, bio_leadteams, bio_feasibilitystudy
WHERE bio_feasibilitystudy.leadid = bio_leads.leadid
AND bio_feasibilitystudy.proposal_date > ( CURDATE( ) - INTERVAL $noday
DAY )
AND bio_leadteams.teamid = bio_feasibilitystudy.teamid
AND bio_leads.leadstatus =3
AND bio_status.statusid = bio_leads.leadstatus
AND bio_cust.cust_id = bio_leads.cust_id
AND bio_leads.created_by IN ($user_array)
";   
    
$result1=DB_query($sql1,$db);
$slno=1;
    $k=0;
    

    while($myrow3=DB_fetch_array($result1,$db)) {
    
      //$date1=$myrow3['leaddate'];
//      $date2=date("Y-m-d");

//      $datearr1 = split("-",$date1); 
//      $datearr2 = split("-",$date2); 
//      
//      $date_diff= gregoriantojd($datearr2[1], $datearr2[2], $datearr2[0]) - gregoriantojd($datearr1[1], $datearr1[2], $datearr1[0]); 
 //if($date_diff>10){  
//     
    // $sql2="SELECT teamname FROM bio_leadteams
//            WHERE teamid=".$myrow3['teamid'];
//     $result2=DB_query($sql2,$db);   
//     $myrow2=DB_fetch_array($result2,$db);
//        
//     $sql4="SELECT custname FROM bio_cust
//        WHERE cust_id=".$myrow3['cust_id'];
//     $result4=DB_query($sql4,$db);   
//     $myrow4=DB_fetch_array($result4,$db);
//     
//     $sql5="SELECT biostatus FROM bio_status
//        WHERE statusid=".$myrow3['leadstatus'];
//     $result5=DB_query($sql5,$db);   
//     $myrow5=DB_fetch_array($result5,$db);
//    
//echo '<tr><td>Category</td>';

     
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
  
  echo'<td><input type="checkbox" id="check"'.$slno.' name="autocheck[]" value='.$myrow3['leadid'].'></td>';
//    echo'<td>'.$myrow3['reqno'].'</td>';
    echo'<td>'.$myrow3['leadid'].'</td>';
    echo'<td>'.$myrow3['custname'].'</td>';
    echo'<td>'.$myrow3['teamname'].'</td>';
    echo'<td>'.ConvertSQLDate($myrow3['proposal_date']).'</td>';
    echo'<td>'.$myrow3['custmob'].'</td>';
    echo'<td>'.$myrow3['custmail'].'</td>';   
    echo'<td align="center">'.$myrow3['biostatus'].'</td>';
 // echo'<td>'; 
  //  echo '<td><a style=cursor:pointer; id='.$lead.' onclick=showactive(this.id)>' . _('Select') . '</td></a>';
           
//    echo '<option value="0"></option>';
//    $sql12="select * from bio_changestatus";
//    $result12=DB_query($sql12,$db);
//    while($row12=DB_fetch_array($result12)){
//    echo '<option value='.$row12['statusid'].'>'.$row12['status'].'</option>'; 
//    }      
//    echo"</select>";
    echo '</tr>';

    $slno++;   
    //  }
    }
//    echo'<tr><td><a href="bio_pendingleads_A5p?item='.$itemcode.' &view=1 &id=1 &season='.$season.'">view all WOs against this item</a></td></tr>';
     echo"</div>";
     if ($slno==1)
  {
    echo'<td><input type=submit disabled name=submit value="' . _('ADD TO PASSIVE LIST') . '" ></td>';
  }
  else
         {
                 echo'<td><input type=submit  name=submit value="' . _('ADD TO PASSIVE LIST') . '" ></td>';

         }
    
    
    echo'</table>';

     
    echo'</form>';  
?>
