<?php
$PageSecurity = 80;
include('includes/session.inc');
$title = _('Task Scheduling');  
include('includes/header.inc');
  if(isset($_POST['submit']))
{                       
     $tick=$_POST['tick'];
     $description=$_POST['Descriptio'];
     $schdate=$_POST['scdate'];
     $team=$_POST['team'];
     $national=$_POST['national'];
     $states=$_POST['states'];
     $dist=$_POST['dist'];
     $LSG_t=$_POST['LSG_t'];
     $LSG_n=$_POST['LSG_n'];
     $block_n=$_POST['block_n'];

    for($i=1;$i<=$_POST['no'];$i++)
   {
     if($_POST['selection'.$i]=='on')
           {
           $cs_id=$_POST['val'.$i];

            $sql_update="UPDATE bio_cstask SET scheduleddate='".FormatDateForSQL($schdate)."',team='$team' WHERE id=$cs_id "; 
            DB_query($sql_update,$db); 
           }
   }
            for($i=1;$i<=$_POST['num'];$i++)
   {
        
          if($_POST['selection2'.$i]=='on')
           {
            $cs_id=$_POST['val2'.$i];
            $tick=$_POST['val3'.$i];
            $description=$_POST['val4'.$i];
            
    $sql_inste="INSERT INTO bio_cstask(cstype,orderno,ticketno,taskdescription,scheduleddate,team,status,cid,stateid,did,LSG_type,LSG_name,block_name) 
    VALUES (4,'".$cs_id."','".$tick."','".$description."','".FormatDateForSQL($schdate)."','".$team."',0,'".$national."','".$states."','".$dist."','".$LSG_t."','".$LSG_n."','".$block_n."')";
      DB_query($sql_inste,$db);   
           }
   }
    
}  
 if($_GET['ticketno']==null)
 { 
   $tick=$_POST['ticketno'];
 }
 else
 {
     $tick=$_GET['ticketno'];

 }
 $sql="SELECT cust_id,description FROM bio_incidents WHERE ticketno='".$tick."'";
 $result=DB_query($sql,$db);
  $myrow=DB_fetch_array($result);
 $cus_id=$myrow['cust_id'];
 $description=$myrow['description'];

 
  $sql="SELECT * FROM bio_incident_cust WHERE cust_id='".$cus_id."'";
 $result=DB_query($sql,$db);
  $myrow1=DB_fetch_array($result);
 $cus_id=$myrow1['cust_id'];
  $national=$myrow1['nationality'];
 $states=$myrow1['state'];
 $dist=$myrow1['district'];
$talu=$myrow1['taluk'];
 $LSG_t=$myrow1['LSG_type'];
  $LSG_n=$myrow1['LSG_name'];
 $block_n=$myrow1['block_name'];
$LSG_w=$myrow1['LSG_ward'];

    
echo " <div></br><table width=300px border='0' >";
if($LSG_t==1)
{
    $sql="SELECT corporation FROM bio_corporation WHERE district='".$LSG_n."'";
 $resultt=DB_query($sql,$db);
  $myrowt=DB_fetch_array($resultt);
  echo "<tr><td><center><b>Corporation:</b></td><td>".$myrowt['corporation']."</br></td>";
}
else if($LSG_t==2)
{
    $sql="SELECT municipality FROM bio_municipality WHERE id='".$LSG_n."'";
 $resultt=DB_query($sql,$db);
  $myrowt=DB_fetch_array($resultt);
  echo "<tr><td><center><b>Municipality:</b></td><td>".$myrowt['municipality']."</br></td>";
}
else if($LSG_t==3)
{
    $sql="SELECT name FROM bio_panchayat WHERE id='".$block_n."' AND block='".$LSG_n."' ";
 $resultt=DB_query($sql,$db);
  $myrowt=DB_fetch_array($resultt);
  echo "<tr><td><center><b>Panchayat:</b></td><td>".$myrowt['name']."</br></td>";
}

$sql="SELECT bio_district.district,bio_state.state FROM bio_district,bio_state WHERE bio_district.cid='$national' AND bio_district.stateid='$states' AND bio_district.did='$dist' AND bio_state.cid='$national' AND bio_state.stateid='$states' ";
 $result=DB_query($sql,$db);
  $myrow1=DB_fetch_array($result);
  
echo "<td><center><b>District:</b></td><td>".$myrow1['district']."
        <td><center><b>State:</b></td><td>".$myrow1['state']."</td></tr>";
echo "</table>";
echo "</div>";
echo '<form action="' . $_SERVER['PHP_SELF'] . '" method="post">';
 echo "</br>"; 
$sql= "SELECT
    `bio_leadteams`.`teamname`,`bio_leadteams`.`teamid`
        FROM `bio_teammembers`
        INNER JOIN `bio_emp` ON (`bio_teammembers`.`empid` = `bio_emp`.`empid`)
        INNER JOIN `bio_leadteams` ON (`bio_leadteams`.`teamid` = `bio_teammembers`.`teamid`)
        WHERE bio_emp.offid=(SELECT officeid from bio_csmteams where did='$dist' AND stateid='$states' 
        AND cid='$national')
        AND bio_emp.deptid IN (5,6)";
        $result2=DB_query($sql,$db);
         echo"   
    
    <table ><tr>
    
    <td>Date</td><td><input type='text' name='scdate' id='scdate' class=date alt=".$_SESSION['DefaultDateFormat']." value=".date('d/m/Y')."></td>
    <td>&nbsp;</td><td>Team</td><td><select name='team' id='team' >";
    
      while($myrow5=DB_fetch_array($result2))
      {
          echo '<option value='.$myrow5['teamid'].'>'.$myrow5['teamname'].'</option>';

      }      
        echo "</select></td>
      <td>&nbsp;</td> <td>&nbsp;</td> <td><input type='submit' name='submit' id='submit' value='Add Schedul'></td>
     <td>&nbsp;</td> <td>&nbsp;</td>  <td><input type='button' value='Print' onclick='Schedule_print();'></td>
    </tr></table>

    
      ";
   
echo" 
   </br><fieldset>
   <legend><b>Scheduled task/complanit</b></legend>
   <div id='tt' style='overflow:scroll;height:300px'>
   
   <table style='border:1 class=selection width:80%' >
   <tr><th>Slno</th>
   <th>Customer Name</th>
   <th>Description</th>
   <th>Contact No</th>
   <th>Task Type</th>
   <th>Customer Type</th>
   <th>Warranty</th>
   <th>Chargable</th>
   <th>Schedule Date</th>
      <th>Assign Team</th>
   <th>select</th>
   </tr>

   ";
   //<input type='hidden' name='schdate' id='schdate' class=date alt='".$_SESSION['DefaultDateFormat']. "' value=".date('d/m/Y').">
$resultdate=DB_query("select date_add(CURDATE(), INTERVAL 30 day) as date",$db);
$rowdate=DB_fetch_array($resultdate); 
 $sql="SELECT bio_cstask.orderno,bio_cstask.id,bio_cstask.taskdescription,bio_cstask.cstype,bio_cstask.scheduleddate,bio_cstask.ticketno,bio_incidents.cust_typ,bio_incidents.warr_type,bio_incidents.chargable,bio_cstask.team
 FROM bio_cstask
 LEFT JOIN bio_incidents
 ON (bio_incidents.ticketno=bio_cstask.ticketno)
WHERE bio_cstask.cid='".$national."' 
AND bio_cstask.stateid='".$states."' 
AND bio_cstask.did='".$dist."' 
AND bio_cstask.LSG_type='".$LSG_t."' 
AND bio_cstask.LSG_name='".$LSG_n."' 
AND bio_cstask.block_name='".$block_n."'
AND bio_cstask.status=0
AND bio_cstask.scheduleddate <= '".$rowdate['date']."' 
";
 $result=DB_query($sql,$db);

 $j=0;
 $slno=0;
   while($myrow2=DB_fetch_array($result))
   {
       $j++;
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
       //test Customer type
            $cst=$myrow2['cstype'];
     if($cst==1)
     {
         $cs_typ='Installation';
     }
     else if($cst==2)
     {
         $cs_typ='warrenty';
     }
    if($slno%2==0)
     {
     echo "<tr class='OddTableRows' >";
}
else
{
 echo "<tr class='EvenTableRows' >";
} 
  

  echo" <td align='center'>$slno</td>";
  if($myrow2['orderno']==0)
  {
       $sqll="select bio_incident_cust.custname,bio_incident_cust.custphone from bio_incident_cust,bio_incidents where bio_incidents.cust_id=bio_incident_cust.cust_id AND bio_incidents.ticketno=".$myrow2['ticketno'];
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
   echo"<td>$cs_typ</td>";

      if($myrow2['cust_typ']==0){echo"<td></td>";}
      else if($myrow2['cust_typ']==1){echo"<td align='center'>Biotech</td>";}
        else {echo"<td align='center'>Non Biotech</td>";}
        //
      if($myrow2['warr_type']==0){echo"<td></td>";}
      else if($myrow2['warr_type']==1){echo"<td align='center'>Warranty</td>";}
        else if($myrow2['warr_type']==2) {echo"<td align='center'>AMC</td>"; }
        else {echo"<td align='center'>Other</td>";}
//
   if($myrow2['cust_typ']==0){echo"<td></td>";}
   else if($myrow2['chargable']==1){echo"<td align='center'>YES</td>";}
   else{echo"<td align='center'>NO</td>";}
            

 echo"  <td>".ConvertSQLDate($myrow2['scheduleddate'])."</td>";
 $sss="select teamname from bio_leadteams where bio_leadteams.teamid=".$myrow2['team'].""  ;
   $res=DB_query($sss,$db);
      $myroq=DB_fetch_array($res);
   echo"<td align=center>".$myroq['teamname']."</td>";
          echo" <td align=center><input type=checkbox name='selection".$j."' id='selection".$j."' >
                    <input type=hidden name='val".$j."' id='val".$j."' value='".$myrow2['id']."'>

                    
   </td></tr>";
                       

    }
  
   

  
 echo"  
   </table>
   </fieldset>
   </div>";
echo'<input type="hidden" name="no" id="no" value='.$j.'>';
 echo'
    <input type="hidden" name="national" id="national" value='.$national.'>
    <input type="hidden" name="states" id="states" value='.$states.'>
    <input type="hidden" name="dist" id="dist" value='.$dist.'>
    <input type="hidden" name="LSG_t" id="LSG_t" value='.$LSG_t.'>
    <input type="hidden" name="LSG_n" id="LSG_n" value='.$LSG_n.'>
    <input type="hidden" name="tick" id="tick" value='.$tick.'>
    <input type="hidden" name="block_n" id="block_n" value='.$block_n.'> ';
echo "<input type='hidden' name='ticketno' id='ticketno' value='".$tick."'>";
echo "</br>   <fieldset>   <legend><b>Unschedule Complaints</b></legend>
   <div style='overflow:scroll;height:300px'>


   <table style='border:1 class=selection width:80%'>
   <tr><th>Slno</th>
   <th>Customer Name</th>
   <th>Description</th>
   <th>Customer Type</th>
   <th>Warranty</th>
   <th>Chargable</th>
   <th>Contact No</th>
   <th>select</th>
   </tr>
   "; 
   
  $sqlr="SELECT bio_incident_cust.custname,bio_incident_cust.custphone,bio_incidents.description,bio_incidents.orderno,bio_incidents.ticketno,bio_incidents.cust_typ,bio_incidents.warr_type,bio_incidents.chargable
   FROM bio_incident_cust,bio_incidents
   WHERE bio_incident_cust.nationality=".$national." 
   AND bio_incident_cust.state=".$states." 
   AND bio_incident_cust.district=".$dist." 

   AND bio_incidents.cust_id=bio_incident_cust.cust_id
   AND bio_incidents.ticketno NOT IN (SELECT ifnull(ticketno,0) FROM bio_cstask  )        
   AND bio_incident_cust.LSG_type=".$LSG_t." 
   AND bio_incident_cust.LSG_name=".$LSG_n."           
   AND bio_incident_cust.block_name=".$block_n."  
     ";//bio_cstask.ticketno//,bio_cstask
   $resul=DB_query($sqlr,$db);
   $slnum=0;//AND bio_incidents.ticketno=bio_cstask.ticketno
   $k=0;
                                                                                /*                             */     
  
   while($myroww=DB_fetch_array($resul))
   {
       $slnum++;
       $k++;
       if($tick==$myroww['ticketno']  )
       {       
           echo "<tr style='background-color:#FF6666' >";
       }
   else if($slnum%2==0 )
     {
     echo "<tr class='OddTableRows'>";   
} 
else
{
 echo "<tr  class='EvenTableRows' >";   
}     
  echo" <td>$slnum</td>"; 
   echo"<td>".$myroww['custname']."</td>";
    echo"<td>".$myroww['description']."</td>";
      if($myroww['cust_typ']==0){echo"<td></td>";}
      else if($myroww['cust_typ']==1){echo"<td>Biotech</td>";}
        else {echo"<td>Non Biotech</td>";}
        //
      if($myroww['warr_type']==0){echo"<td></td>";}
      else if($myroww['warr_type']==1){echo"<td>Warranty</td>";}
        else if($myroww['warr_type']==2) {echo"<td>AMC</td>"; }
        else {echo"<td>Other</td>";}
//
   if($myroww['cust_typ']==0){echo"<td></td>";}
   else if($myroww['chargable']==1){echo"<td>YES</td>";}
   else{echo"<td>NO</td>";}
            
   echo"<td>".$myroww['custphone']."</td>";
  echo" <td align=center><input type=checkbox name='selection2".$k."' id='selection2".$k."' >
                    <input type=hidden name='val2".$k."' id='val2".$k."' value='".$myroww['orderno']."'>
                    <input type=hidden name='val3".$k."' id='val3".$k."' value='".$myroww['ticketno']."'>
                    <input type=hidden name='val4".$k."' id='val4".$k."' value='".$myroww['description']."'>
   </td></tr>";
   }
   echo'<input type="hidden" name="num" id="num" value='.$k.'>';
   echo"  
   </table>
   </fieldset>
   </div>";
   //<th>Tick No</th><td><input type='text' name='tem' id='tem' value='$tick'></td> 
   //<th>Description</th><td><input type='text' name='Descriptio' id='Descriptio' value='".$myrow['description']."'></td> 
   
            echo"</form>"; 
 
   ?>
 <script>
 function Schedule_print(){
     
     
/*     complaint=document.getElementById("num").value;
 var array1 = new Array();
     for(i=1;i<=complaint;i++)
     {
        var selection2='selection2'+i;
         if(document.getElementById(selection2).checked==true)
         {
             
              array1=document.getElementById('val2'+i).value;
          }
       $schdate=$_POST['schdate'];
     $team=$_POST['team'];
       }
    alert(array1);   */   
    str1=document.getElementById("national").value;
    str2=document.getElementById("states").value;
    str3=document.getElementById('dist').value;
    str4=document.getElementById('LSG_t').value;
    str5=document.getElementById('LSG_n').value;
    str6=document.getElementById('block_n').value;
    str7=document.getElementById('scdate').value;
    str8=document.getElementById('team').value;
   
   controlWindow=window.open("bio_schedule_print.php?national=" + str1 + "&states=" + str2 + "&dist=" + str3 + "&LSG_t=" +str4 + "&LSG_n=" + str5 + "&block_n=" + str6 + "&schdate=" + str7 + "&team=" + str8 ,"_new");  
    
}
</script>

 