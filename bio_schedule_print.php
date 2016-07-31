<?php
$PageSecurity = 80;
include('includes/session.inc');

$national=$_GET['national'];
$states=$_GET['states'];
$dist=$_GET['dist'];
 $LSG_t=$_GET['LSG_t'];
$LSG_n=$_GET['LSG_n'];
 $block_n=$_GET['block_n'];
 $schdate=$_GET['schdate'];
 $team=$_GET['team'];
     
echo " </br><table width='100' border='0' align='center'><tr>";
echo "</tr><tr><th><b>Date<th>:</th></b></th><th>".$schdate."</th><th>&nbsp;</th>";
$sql="SELECT bio_leadteams.teamname FROM bio_leadteams WHERE bio_leadteams.teamid='$team'";
 $result=DB_query($sql,$db);
  $myrowq=DB_fetch_array($result);
          echo "<th><center><b>Team<th>:</th></b></th><th>".$myrowq['teamname']."</th></tr>";
       
        echo "</table>";
        echo " <table width='100' border='0' align='center'><tr>";
        

if($LSG_t==1)
{
    $sql="SELECT corporation FROM bio_corporation WHERE district='".$LSG_n."'";
 $resultt=DB_query($sql,$db);
  $myrowt=DB_fetch_array($resultt);
  echo "<tr><th><center><b>Corporation<th>:</th></b></th><th>".$myrowt['corporation']."</br></th>";
}
else if($LSG_t==2)
{
    $sql="SELECT municipality FROM bio_municipality WHERE id='".$LSG_n."'";
 $resultt=DB_query($sql,$db);
  $myrowt=DB_fetch_array($resultt);
  echo "<th><center><b>Municipality<th>:</th></b></th><th>".$myrowt['municipality']."</br></th>";
}
else if($LSG_t==3)
{
    $sql="SELECT name FROM bio_panchayat WHERE id='".$block_n."' AND block='".$LSG_n."' ";
 $resultt=DB_query($sql,$db);
  $myrowt=DB_fetch_array($resultt);
  echo "<th><center><b>Panchayat<th>:</th></b></th><th>".$myrowt['name']."</br></th>";
}
 
$sql="SELECT bio_district.district,bio_state.state FROM bio_district,bio_state WHERE bio_district.cid='$national' AND bio_district.stateid='$states' AND bio_district.did='$dist' AND bio_state.cid='$national' AND bio_state.stateid='$states' ";
 $result=DB_query($sql,$db);
  $myrow1=DB_fetch_array($result);
  
echo "<th>&nbsp;</th><th><b>District<th>:</th></b></th><th>".$myrow1['district']."</th>
       <th>&nbsp;</th> <th><b> State<th>:</th></b></th><th>".$myrow1['state']."</th>";

        
echo "</table>";
 echo '<table align="center"><th colspan ="4"><b> <input type="button" id="print2" value="Print" onclick="hide_button();" align="center" ></b></th></table>';           
echo" 
   </br>
   <div id='tt'>
   <table width='800' border='1' align='center'>";

echo "
   <tr ><th>Slno</th>
   <th>Customer Name</th>
   <th>Description</th>
   <th>Contact No</th>
   <th>Task Type</th>
   <th>Customer Type</th>
   <th>Warranty</th>
   <th>Chargable</th>
      <th>Chargable</th>

   <th>Signature of customer</th>

   </tr>
   ";
 
  $sql="SELECT bio_cstask.orderno,bio_cstask.taskdescription,bio_cstask.cstype,bio_cstask.ticketno,bio_incidents.cust_typ,bio_incidents.warr_type,bio_incidents.chargable 
 FROM bio_cstask 
 LEFT JOIN bio_incidents
 ON (bio_incidents.ticketno=bio_cstask.ticketno)
 WHERE bio_cstask.cid='".$national."'
  AND bio_cstask.stateid='".$states."'
   AND bio_cstask.did='".$dist."' 
   AND bio_cstask.LSG_type='".$LSG_t."' 
   AND bio_cstask.LSG_name='".$LSG_n."' 
   AND bio_cstask.block_name='".$block_n."'
    AND bio_cstask.scheduleddate='".FormatDateForSQL($schdate)."'
     AND bio_cstask.team='".$team."'
     ";
 $result=DB_query($sql,$db);
  $slno=0;
   while($myrow2=DB_fetch_array($result))
   {    $j++;
       $slno++;
       $cst=$myrow2['cstype'];
     if($cst==1)
     {
         $cs_typ='Installation';
     }
     else if($cst==2)
     {
         $cs_typ='warrenty';
     }
     else if($cst==3)
     {
         $cs_typ='AMC';
     }
     else if($cst==4)
     {
         $cs_typ='Complaint';
     }
     
  
  echo" <tr align='center'><td>$slno</td>";
  
  if($myrow2['orderno']==0)
  {
     
      $sqll="select bio_incident_cust.custname,bio_incident_cust.custphone,bio_incident_cust.landline from bio_incident_cust,bio_incidents 
      where bio_incidents.cust_id=bio_incident_cust.cust_id AND bio_incidents.ticketno=".$myrow2['ticketno'];
      $resultl=DB_query($sqll,$db);
      $myro=DB_fetch_array($resultl);
      $conno=$myro['custphone']."<br>".$myro['landline'];
       echo"<td>".$myro['custname']."</td>";
  }else
  {
     
      $sqll="select custbranch.brname,custbranch.phoneno,custbranch.faxno from custbranch,salesorders where custbranch.debtorno=salesorders.debtorno AND salesorders.orderno=".$myrow2['orderno'];
      $resultl=DB_query($sqll,$db);
      $myro=DB_fetch_array($resultl);
      $conno=$myro['phoneno']."<br>".$myro['faxno'];
      echo"<td>".$myro['brname']."</td>";
      //AND salesorders.orderno=bio_cstask.orderno custbranch.brnamecustbranch.phoneno
//AND salesorders.debtorno=custbranch.debtorno
  }
  
   echo"<td>".$myrow2['taskdescription']."</td>";
   echo"<td>".$conno."</td>";
    echo"<td>".$cs_typ."</td>";
   
    if($myrow2['cust_typ']==0){echo"<td>&nbsp;</td>";}
      else if($myrow2['cust_typ']==1){echo"<td>Biotech</td>";}
        else {echo"<td>Non Biotech</td>";}
        //
      if($myrow2['warr_type']==0){echo"<td>&nbsp;</td>";}
      else if($myrow2['warr_type']==1){echo"<td>Warranty</td>";}
        else if($myrow2['warr_type']==2) {echo"<td>AMC</td>"; }
        else {echo"<td>Other</td>";}
//
   if($myrow2['cust_typ']==0){echo"<td>&nbsp;</td>";echo"<td>&nbsp;</td>";}
   else if($myrow2['chargable']==1){echo"<td>YES</td>";echo"<td>&nbsp;</td>";}
   else{echo"<td>NO</td>";echo"<td>----</td>";}
 //  echo"<td>&nbsp;</td>";
   echo"<td>&nbsp;</td></tr>";
 
 //echo"  <td>".ConvertSQLDate($schdate)."</td></tr>";
    }

?>

<script>
function hide_button(){
  document.getElementById('print2').style.visibility = "hidden"; 
  window.print();
}
</script>