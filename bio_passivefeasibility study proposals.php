<?php
     
    $PageSecurity = 80;
include('includes/session.inc');
$title = _('directPrint');
echo $sql1="select * from bio_feasibilitystudy where feasibility_status=1";
$result=DB_query($sql1,$db);
         if(!isset($_POST['excelview'])){
             
echo '<form name="pendingfeasibility detailsForm"  method="post" action="' . $_SERVER['PHP_SELF'] . '?' .SID .'">';
    echo'<table width="80%">';
    echo'<tr><td colspan=6><b>Showing all pending feasibilitystudy details</td></tr>';
    echo '<tr><td class="viewheader">serial no</td>';
   echo'<td class="viewheader">feasibilitystudy id</td>';
    echo'<td class="viewheader">feasibilitystudy charge</td>';
    echo'<td class="viewheader">feasibilitystudy startdate </td>';
    echo'<td class="viewheader">team </td>';
    echo '</tr>';
     
                      $slno=1; 
           while($myrow3=DB_fetch_array($result,$db))     {            
  $date1=$myrow3['feasibilitystudy_startdate'];
      $date2=date("Y-m-d");

      $datearr1 = split("-",$date1); 
      $datearr2 = split("-",$date2); 
      
      echo $date_diff= gregoriantojd($datearr2[1], $datearr2[2], $datearr2[0]) - gregoriantojd($datearr1[1], $datearr1[2], $datearr1[0]); 
      if($date_diff>10){  
     $sql2="SELECT teamname FROM bio_leadteams
            WHERE teamid=".$myrow3['teamid'];
     $result2=DB_query($sql2,$db);   
     $myrow2=DB_fetch_array($result2,$db);
    //    
//    $sql4="SELECT custname FROM bio_cust
//        WHERE cust_id=".$myrow3['cust_id'];
//     $result4=DB_query($sql4,$db);   
//     $myrow4=DB_fetch_array($result4,$db);   
    echo'<tr>';
    echo'<td>'.$slno.'</td>';
    echo'<td>'.$myrow3['feasibilitystudy_id'].'</td>';
    echo'<td>'.$myrow3['feasibilitystudy_charge'].'</td>';
    echo'<td>'.$myrow3['feasibilitystudy_startdate'].'</td>';
    echo'<td>'.$myrow2['teamname'].'</td>';
    echo'</tr>';    
    $slno++;   
      
      }
    
    }
//    echo'<tr><td><a href="bio_pendingleads_A5p?item='.$itemcode.' &view=1 &id=1 &season='.$season.'">view all WOs against this item</a></td></tr>';
    echo'</table>';

     if($_SESSION['ExcelEnabled']==0){
    echo'<input type="submit" name="excelview" id=2 value="view as excel" disabled="disabled">';
	}
	else{
	echo'<input type="submit" name="excelview" id=2 value="view as excel">';
	}
    echo'</form>';
}

   
  if(isset($_POST['excelview'])){ 
$filename="sdfsdfsdf.csv";

    $header= "slno".","."feasibilitystudy id".","."feasibilitystudy charge".","."Team".","."feasibilitystudy startdate".","."\n";"\n";
    $data='';
    $slno=1;
    
        while($myrow3=DB_fetch_array($result,$db))     {
            
            $date1=$myrow3['feasibilitystudy_startdate'];
      $date2=date("Y-m-d");

      $datearr1 = split("-",$date1); 
      $datearr2 = split("-",$date2); 
      
      $date_diff= gregoriantojd($datearr2[1], $datearr2[2], $datearr2[0]) - gregoriantojd($datearr1[1], $datearr1[2], $datearr1[0]); 
      if($date_diff>10){
            
            
            $sql2="SELECT teamname FROM bio_leadteams
            WHERE teamid=".$myrow3['teamid'];
     $result2=DB_query($sql2,$db);   
     $myrow2=DB_fetch_array($result2,$db);
        
   // $sql4="SELECT custname FROM bio_cust
//        WHERE cust_id=".$myrow3['cust_id'];
//     $result4=DB_query($sql4,$db);   
//     $myrow4=DB_fetch_array($result4,$db);
//    
   $data= $data.$slno.",".$myrow3['feasibilitystudy_id'].",".$myrow3['feasibilitystudy_charge'].",".$myrow2['teamname'].",".$myrow3['feasibilitystudy_startdate']."\n";    
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
