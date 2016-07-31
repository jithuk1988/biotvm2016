<?php
  $PageSecurity = 80;
  include('includes/session.inc');
  
  $leadid=$_GET['p'];
  $taskid=$_GET['q'];
  $sql="SELECT bio_leads.leadid,
                    bio_cust.custname,
                    bio_cust.contactperson,
                    bio_cust.custphone,
                    bio_cust.custmob,
                    bio_cust.custmail,
                    bio_cust.area1,
                    bio_cust.district,
                    bio_cust.state,
                    bio_cust.nationality,
                    bio_leadteams.teamname,
                    bio_leadsources.sourcename,
                    bio_leads.remarks,
                    bio_leads.created_by   
              FROM  bio_leads,bio_cust,bio_leadteams,bio_leadsources
              WHERE bio_leads.cust_id=bio_cust.cust_id
              AND bio_leadteams.teamid=bio_leads.teamid 
              AND bio_leadsources.id=bio_leads.sourceid
              AND bio_leads.leadid=".$leadid;
 $result=DB_query($sql,$db);

 $myrow=DB_fetch_array($result); 

 $no=0; 
 $k=0; 
 
 $cname=$myrow['custname']; 
 $cperson=$myrow['contactperson']; 
 $cph=$myrow['custphone']; 
 $cmob=$myrow['custmob']; 
 $team=$myrow['teamname'];
 $sourcename=$myrow['sourcename'];
 $remarks=$myrow['remarks'];  
  
 if($cph=='' OR $cph=='-'){
 		$cno=$cmob;
 }elseif($cmob==''){
 		$cno=$cph;
 }else{
 		$cno=$cph.",".$cmob;
 }				
 
 $email=$myrow['custmail']; 
 $place=$myrow['area1'];
 $dist=$myrow['district'];
 $ste=$myrow['state'];
 $ctry=$myrow['nationality']; 
 $createdby=$myrow['created_by'];
 $sql1="SELECT bio_district.district 
          FROM bio_district
          WHERE bio_district.stateid=".$ste."  
          AND bio_district.cid=".$ctry."
          AND bio_district.did=".$dist;     
 $result1=DB_query($sql1,$db);
 $myrow=DB_fetch_array($result1);
 $district=$myrow['district'];     
echo '<table><tr>';
echo'<td valign=top>';

echo"<div id=cus_details>";               
          
echo"<fieldset style='width:460px;height:270px'><legend>Customer Details</legend>";
echo"<table width=100%>";
if(($cname==$cperson) OR ($cperson==''))  {
echo"<tr><td width=50%>Customer Name:</td>";
echo"<td><input type='hidden' name='custname' id='custname' value='$cname'>$cname</td></tr>";
}else{
echo"<tr><td width=50%>Customer Name:</td>";
echo"<td><input type='hidden' name='cperson' id='cperson' value='$cperson'>$cperson</td></tr>"; 
echo"<tr></tr>";  
echo"<tr><td width=50%>Organization Name:</td>";
echo"<td><input type='hidden' name='custname' id='custname' value='$cname'>$cname</td></tr>";  
}
echo"<tr></tr>";
echo"<tr><td>Customer Contact:</td>";
echo"<td><input type='hidden' name='custph' id='custph' value='$cno'>$cno</td></tr>";
echo"<tr></tr>";
echo"<tr><td>Customer email</td>";
echo"<td><input type='hidden' name='email' id='email' value='$email'>$email</td></tr>";
echo"<tr></tr>";
echo"<tr><td>Customer Place:</td>";
echo"<td><input type='text' name='custplace' id='custplace' value='$place'></td></tr>";
echo"<tr></tr>";
echo"<tr><td>Customer District:</td>";
echo"<td><input type='text' name='custdist' id='custdist' value='$district'></td></tr>";
echo"<tr></tr>";
echo"<tr><td>Lead Source Team:</td>";
echo"<td><input type='hidden' name='team' value='$team'>$team</td></tr>";
echo"<tr></tr>";
echo"<tr><td>Lead Source:</td>";
echo"<td><input type='hidden' name='source' id='source' value='$sourcename'>$sourcename</td></tr>";
echo"<tr></tr>";
echo"<tr><td>Remarks:</td>"; 
echo"<td><textarea rows=2 cols=30 name=remarks style=resize:none; value='$remarks'>$remarks</textarea></td></tr>";
echo"<tr></tr>";
echo"<tr><td>Created By:</td>"; 
echo"<td><input type='hidden' name='createdby' id='createdby' value='$createdby'>$createdby</td></tr>";  
echo"<tr></tr>";
echo"</table>";
echo"</fieldset>"; 

echo'</div>';
echo"</td>"; 

echo'<td valign=top>';    
echo'<div id=right_panel_1>';
echo"<fieldset style='width:460px;height:270px; overflow:auto;'>";
echo"<legend>Assign Team</legend>";
echo"<table>";

  $cur_office=$_SESSION['officeid']; 
  $cur_level=$_SESSION['level'];
  
   $office_arr=array();
   $office_arr[]=$cur_office; 
  
        $sql2="SELECT reporting_off
            FROM bio_office
            WHERE id=$cur_office
            ";
       $result2=DB_query($sql2,$db);
       $myrow_count = DB_num_rows($result2);

     
     if($myrow_count>0){    
     while($row2=DB_fetch_array($result2)){
         
         $office_arr[]=$row2['reporting_off'];   
     if($row2['reporting_off']!=1){    
     $sql3="SELECT reporting_off 
                FROM bio_office
                WHERE id=".$row2['reporting_off']."";
        $result3=DB_query($sql3,$db);
        $myrow_count1 = DB_num_rows($result3);
     if($myrow_count1>0){
     while($row3=DB_fetch_array($result3)){
        $office_arr[]=$row3['reporting_off'];
                      
     if($row2['reporting_off']!=1){   
     $sql4="SELECT reporting_off 
                FROM bio_office
                WHERE id=".$row3['reporting_off']."";
        $result4=DB_query($sql4,$db);
        $myrow_count2 = DB_num_rows($result4);
     if($myrow_count2>0){
     while($row4=DB_fetch_array($result4)){
               $office_arr[]=$row4['reporting_off'];        
            
        }
        }  
        }   
     }
     }
     }
     }
     }
         $office_array=join(",", $office_arr); 
     
    
     $sql5="SELECT reporting_off,id
            FROM bio_office
            WHERE reporting_off=$cur_office
            ";
       $result5=DB_query($sql5,$db);
       $myrow_count5 = DB_num_rows($result5);

     
     if($myrow_count5>0){    
     while($row5=DB_fetch_array($result5)){
         
         $office_arr[]=$row5['id'];   
     if($row5['id']!=1){    
     $sql6="SELECT id,reporting_off 
                FROM bio_office
                WHERE reporting_off=".$row5['id']."";
        $result6=DB_query($sql6,$db);
        $myrow_count6 = DB_num_rows($result6);
     if($myrow_count6>0){
     while($row6=DB_fetch_array($result6)){
               $office_arr[]=$row6['id'];
                      
     if($row6['id']!=1){   
     $sql7="SELECT id,reporting_off 
                FROM bio_office
                WHERE reporting_off=".$row6['id']."";
        $result7=DB_query($sql7,$db);
        $myrow_count7 = DB_num_rows($result7);
     if($myrow_count7>0){
     while($row7=DB_fetch_array($result7)){
               $office_arr[]=$row7['id'];        

            
        }
        }  
        }   
     }
     }
     }
     }
     } 
         
     $office_array=join(",", $office_arr);

echo"<tr><td width=50%>Assign Office</td>";
echo"<td><select name='office' id='office' style='width:150px' onchange='transfer(this.value)'>";
$sql="SELECT * FROM bio_office WHERE id IN ($office_array)";
$result=DB_query($sql,$db);

    $f=0;
  while($myrow1=DB_fetch_array($result))
  {  
  if ($myrow1['id']==$_POST['office'])  
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
    echo $myrow1['id'] . '">'.$myrow1['office'];
    echo '</option>';
    $f++;
   }  
   

 echo"</tr></td>";
 echo"</table>";
  echo"<table id=transfer>";
echo"</table>";


echo"</fieldset>";
echo"</div>";
echo"</td></tr></table>";


echo"<input type='hidden' name='LeadID' id='leadid' value='$leadid'>";
echo"<input type='hidden' name='TaskID' id='taskid' value='$taskid'>";
?>
