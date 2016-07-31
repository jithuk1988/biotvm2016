<?php
    $PageSecurity = 80;
include('includes/session.inc');
$title = _('ActiveFS');

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

$sql1="SELECT bio_feasibilitystudy.feasibilitystudy_id, 
              bio_feasibilitystudy.feasibilitystudy_charge, 
              bio_feasibilitystudy.feasibilitystudy_startdate, 
              bio_feasibilitystudy.teamid
         FROM bio_feasibilitystudy, bio_leads
        WHERE bio_leads.leadid = bio_feasibilitystudy.leadid
          AND bio_leads.leadstatus =3
          AND bio_leads.created_by IN ($user_array)
          ";
$result=DB_query($sql1,$db);
         if(!isset($_POST['excelview'])){
          include('includes/header.inc'); 
          include('includes/sidemenu.php');
          
          echo '<center><font style="color: #333;
                           background:#fff;
                           font-weight:bold;
                           letter-spacing:0.10em;
                           font-size:16px;
                           font-family:Georgia;
                           text-shadow: 1px 1px 1px #666;">ACTIVE FS</font></center>';   
    
 
echo "<table style='width:60%'><tr><td>";
echo "<fieldset style='width:97%;'>";     
echo "<legend><h3>Showing all Active Feasibility Study Proposals</h3>";
echo "</legend>";                                        
echo '<form name="pendingfeasibility detailsForm"  method="post" action="' . $_SERVER['PHP_SELF'] . '?' .SID .'">';
     echo "<div style='height:350px; overflow:auto;'>";
echo "<table style='border:0px solid #F0F0F0;width:100%'>";



    //echo'<table width="80%">';
    //echo'<tr><td colspan=6><b>Showing all pending feasibilitystudy details</td></tr>';
    echo '<tr><th class="viewheader">serial no</td>';
     echo'<th class="viewheader">feasibilitystudy id</td>';
    echo'<th class="viewheader">feasibilitystudy charge</td>';
    echo'<th class="viewheader">feasibilitystudy startdate </td>';
    echo'<th class="viewheader">team </td>';
    echo '</tr>';
     
                      $slno=1; 
                      $k=0;
           while($myrow3=DB_fetch_array($result,$db))     {            
      $date1=$myrow3['feasibilitystudy_startdate'];
      $date2=date("Y-m-d");

      $datearr1 = explode("-",$date1); 
      $datearr2 = explode("-",$date2); 
      
      $date_diff= gregoriantojd($datearr2[1], $datearr2[2], $datearr2[0]) - gregoriantojd($datearr1[1], $datearr1[2], $datearr1[0]); 
      if($date_diff<10){  
     $sql2="SELECT teamname FROM bio_leadteams
            WHERE teamid=".$myrow3['teamid'];
     $result2=DB_query($sql2,$db);   
     $myrow2=DB_fetch_array($result2,$db);
     
     
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
     
     
    //    
//    $sql4="SELECT custname FROM bio_cust
//        WHERE cust_id=".$myrow3['cust_id'];
//     $result4=DB_query($sql4,$db);   
//     $myrow4=DB_fetch_array($result4,$db);   

    echo'<td>'.$slno.'</td>';
    echo'<td>'.$myrow3['feasibilitystudy_id'].'</td>';
    echo'<td>'.$myrow3['feasibilitystudy_charge'].'</td>';
    echo'<td>'.ConvertSQLDate($myrow3['feasibilitystudy_startdate']).'</td>';
    echo'<td>'.$myrow2['teamname'].'</td>';
    //echo'</tr>';  
     echo "<td><a style=cursor:pointer; id=".$myrow3['feasibilitystudy_id']." onclick=viewDetails(this.id)>Create Concept Proposal</a></td>";
    echo "</tr>";
    
    
      
    $slno++;   
      
      }
    
    }
    
    
    
//    echo'<tr><td><a href="bio_pendingleads_A5p?item='.$itemcode.' &view=1 &id=1 &season='.$season.'">view all WOs against this item</a></td></tr>';
    echo"</div>";
    echo'</table>';
    echo'<input type="submit" name="excelview" id=2 value="view as excel">';
    echo'</form>';
    echo "</td></tr></fieldset></table>";
    
     ?>  
  <script>
    function viewDetails(str1,str2){
        window.location="bio_conceptproposal.php?leadid=" + str1;
    }
    </script>  
    
    
<?php 
    
    
    
//     include('includes/footer.inc');  
}

   
  if(isset($_POST['excelview'])){ 
$filename="sdfsdfsdf.csv";

    $header= "slno".","."feasibilitystudy id".","."feasibilitystudy charge".","."Team".","."feasibilitystudy startdate".","."\n";"\n"; 
    $data='';
    $slno=1;
    
        while($myrow3=DB_fetch_array($result,$db))     {
            
            $date1=$myrow3['feasibilitystudy_startdate'];
      $date2=date("Y-m-d");

      $datearr1 = explode("-",$date1); 
      $datearr2 = explode("-",$date2); 
      
      $date_diff= gregoriantojd($datearr2[1], $datearr2[2], $datearr2[0]) - gregoriantojd($datearr1[1], $datearr1[2], $datearr1[0]); 
      if($date_diff<10){
            
            
            $sql2="SELECT teamname FROM bio_leadteams
            WHERE teamid=".$myrow3['teamid'];
     $result2=DB_query($sql2,$db);   
     $myrow2=DB_fetch_array($result2,$db);
        
   // $sql4="SELECT custname FROM bio_cust
//        WHERE cust_id=".$myrow3['cust_id'];
//     $result4=DB_query($sql4,$db);   
//     $myrow4=DB_fetch_array($result4,$db);
//    
   $data= $data.$slno.",".$myrow3['feasibilitystudy_id'].",".$myrow3['feasibilitystudy_charge'].",".$myrow2['teamname'].",".ConvertSQLDate($myrow3['feasibilitystudy_startdate'])."\n";
    $slno++;    
      }
        } 
    
header("Content-type: application/x-msdownload"); 
header("Content-Disposition: attachment; filename=$filename"); 
header("Pragma: no-cache"); 
header("Expires: 0");  
echo "$header\n$data";  
 }  
 
?>
