<?php
    $PageSecurity = 80;
include('includes/session.inc');
$title = _('AMC Task Scheduling');  
include('includes/header.inc');
echo '<center><font style="color: #333;
    background:#fff;
    font-weight:bold;
    letter-spacing:0.10em;
    font-size:16px;
    font-family:Georgia;
    text-shadow: 1px 1px 1px #666;">AMC Task Scheduling</font></center>';
  echo'<a href="bio_amc_cust_list.php"><h4>BACK TO LIST</h4></a>';  
    if( $_POST['newtask'] )
    {
     
      $taskno=$_POST['no']+1;
    $orderno=$_POST['orderno'];
    $desid=$_POST['amcno'];
    $des=$_POST['des'];
    if($_POST['team']==NULL)
    {
        $team=0;
    }  else{
            $team=$_POST['team']; 
    }
   
     $days=$_POST['comdays'];
    
    $newdate1=$_POST['new_year'];
    $newdate2 = strtotime ( '+'.$days.' day' , strtotime ( $newdate1 ) );
    $newdate2 = date ( 'Y-m-d' , $newdate2 );

    $sql_add="INSERT INTO bio_cstask(cstype,orderno,despatch_id,taskno,taskdescription,daystocomplete,prevtask,scheduleddate,team,status,cid,stateid,did,LSG_type,LSG_name,block_name)
                    values (3,'$orderno','$desid',$taskno,'$des','$days','".$_POST['no']."','$newdate2',$team,0,'".$_POST['cid']."',
                    '".$_POST['stateid']."' , '".$_POST['did']."', '".$_POST['LSG_type']."' , '".$_POST['LSG_name']."' , '".$_POST['block_name']."')";
                    $result_add=DB_query($sql_add,$db);                                 
       
      }
      if($_POST['submit']){
          
          for($i=1;$i<=$_POST['no'];$i++){
          $orderno=$_POST['orderno'];
   // $plant=$_POST['plant'];
    $des=$_POST['des'.$i];
    $days=$_POST['comdays'.$i];
     $prev=$_POST['prev'.$i];
    $start=FormatDateForSQL($_POST['start'.$i]);
    $team=$_POST['team'.$i];
    
    $sql_add="INSERT INTO bio_cstask(cstype,orderno,despatch_id,taskno,taskdescription,daystocomplete,prevtask,scheduleddate,team,status,cid,stateid,did,LSG_type,LSG_name,block_name)
                    values (3,'$orderno','".$_POST['amcno']."',$i,'$des','$days','$prev','$start','$team',0,'".$_POST['cid']."',
                    '".$_POST['stateid']."' , '".$_POST['did']."', '".$_POST['LSG_type']."' , '".$_POST['LSG_name']."' , '".$_POST['block_name']."')";
          
                    $result_add=DB_query($sql_add,$db);
                    
                 
                    
                    
          }
            if($_POST['amcproposal']=='on')
          {
              $prop_tno=$_POST['no']+1;
              $newdate1=$_POST['new_year'];
    $newdate2 = strtotime ( '+335 day' , strtotime ( $newdate1 ) );
    $newdate2 = date ( 'Y-m-d' , $newdate2 );
               $sql_add1="INSERT INTO bio_cstask(cstype,orderno,despatch_id,taskno,taskdescription,daystocomplete,prevtask,scheduleddate,team,status,cid,stateid,did,LSG_type,LSG_name,block_name)
                    values (3,'$orderno','".$_POST['amcno']."',$prop_tno,'AMC Proposal',335,'".$_POST['no']."','$newdate2',''$team'',0,'".$_POST['cid']."',
                    '".$_POST['stateid']."' , '".$_POST['did']."', '".$_POST['LSG_type']."' , '".$_POST['LSG_name']."' , '".$_POST['block_name']."')";
                    $result_add1=DB_query($sql_add1,$db); 
          }
           
          
      }
            if($_POST['edit']){
         for($i=1;$i<=$_POST['no'];$i++){
          $orderno=$_POST['orderno'];
    $desid=$_POST['amcno'];
    $des=$_POST['des'.$i];
    $days=$_POST['comdays'.$i];
     $prev=$_POST['prev'.$i];
    $start=FormatDateForSQL($_POST['start'.$i]);
    $team=$_POST['team'.$i];
          
          $sql_edit="UPDATE bio_cstask SET  taskdescription='".$des."' , daystocomplete='".$days."' ,
           scheduleddate='".$start."' , team='".$team."' WHERE cstype=3 AND despatch_id='".$desid."' 
          AND orderno='".$orderno."' AND taskno='".$_POST['task_no'.$i]."' ";
                   $result_adit=DB_query($sql_edit,$db);
         
         
         
         }
                   
           if($_POST['amcproposal']=='on')
          {
              $prop_tno=$_POST['no']+1;
              $newdate1=$_POST['new_year'];
    $newdate2 = strtotime ( '+335 day' , strtotime ( $newdate1 ) );
    $newdate2 = date ( 'Y-m-d' , $newdate2 );
               $sql_add1="INSERT INTO bio_cstask(cstype,orderno,despatch_id,taskno,taskdescription,daystocomplete,prevtask,scheduleddate,team,status,cid,stateid,did,LSG_type,LSG_name,block_name)
                    values (3,'$orderno','".$_POST['amcno']."',$prop_tno,'AMC Proposal',335,'".$_POST['no']."','$newdate2','$team',0,'".$_POST['cid']."',
                    '".$_POST['stateid']."' , '".$_POST['did']."', '".$_POST['LSG_type']."' , '".$_POST['LSG_name']."' , '".$_POST['block_name']."')";
                    $result_add1=DB_query($sql_add1,$db); 
          }       
                
            }
      
      
    /*function get_instl_taskdate($type,$stkcode,$taskno,$newdate,$db){
        
    $sql="select prevtask from bio_def_cstask where cstype='$type' AND stockcode='$stkcode' AND taskno='$taskno'";
    $result=DB_query($sql,$db);
    $myrow=DB_fetch_row($result);
    if($myrow[0]!=0){
    $sql_days="SELECT daystocomplete from bio_def_cstask WHERE cstype='$type' AND stockcode='$stkcode' AND taskno='$myrow[0]'";
    $result_days=DB_query($sql_days,$db);
    $myrow_days=DB_fetch_array($result_days);
      $days=$myrow_days[0];
    
    $newdate = strtotime ( '+'.$days.' day' , strtotime ( $newdate ) );
     // echo $newdate = date ( 'Y-m-d' , $newdate );
   return $newdate = date ( 'Y-m-d' , $newdate );
    }else{
        return $newdate;
    }
      
 
}*/
      
   
    echo "<form action=" . $_SERVER['PHP_SELF'] . "?" . SID ." method=POST>";  
    echo"<div id=plant1></div>";

    if($_GET['orderno']==NULL){
        $orderno=$_POST['orderno'];
        $amcno=$_POST['amcno'];
    }else{
     $orderno=$_GET['orderno'];
     $amcno=$_GET['amcno'];
 }
    

    /*if($_GET['plant']==NULL){
        $planttype=$_POST['plant'];
    }
    else{
     $planttype=$_GET['plant'];
 }   */
                        /*$sql_deliverydate="SELECT 
              salesorderdetails.stkcode,
              salesorderdetails.actualdispatchdate
              FROM salesorderdetails,salesorders,stockmaster,stockcategory,bio_maincat_subcat
              WHERE salesorderdetails.orderno=salesorders.orderno
             
              AND stockmaster.stockid=salesorderdetails.stkcode
              AND stockmaster.categoryid=stockcategory.categoryid
              AND stockcategory.categoryid=bio_maincat_subcat.subcatid
              AND bio_maincat_subcat.maincatid=1
              AND salesorderdetails.completed=1
              AND salesorderdetails.orderno=".$orderno;
              $result_deliverydate=DB_query($sql_deliverydate,$db);
     $row_deliverydate=DB_fetch_array($result_deliverydate,$db);
     $deldat=explode(" ",$row_deliverydate['actualdispatchdate']);
     $deliverydate=$deldat[0];
     $sql_next="SELECT DATE_ADD('$deliverydate', INTERVAL 1 DAY) as nextdate";
     $result_next=DB_query($sql_next,$db);
     $row_next=DB_fetch_array($result_next,$db);
     $row_next['nextdate'];*/
 
               
 if($orderno!='')
 {
     
       
    $sql1="SELECT custbranch.brname,
              custbranch.phoneno,custbranch.did,custbranch.stateid,custbranch.cid,bio_district.district,custbranch.LSG_type,custbranch.LSG_name,custbranch.block_name,
              bio_amc.start_date,bio_amc.end_date,bio_amc.plant 
              from custbranch,salesorders,bio_district,bio_amc 
              where 
              salesorders.debtorno=custbranch.debtorno
              AND salesorders.orderno=bio_amc.new_orderno
              AND bio_district.did=custbranch.did
              AND    bio_district.stateid=custbranch.stateid
              AND    bio_district.cid=custbranch.cid
              AND bio_amc.new_orderno=".$orderno;
     $result1=DB_query($sql1,$db);
     $row1=DB_fetch_array($result1,$db);
     
     
     
     
     
     
    $expare = strtotime ( '+ 365 day' , strtotime ( $row1['start_date'] ));
    $expare = date ( 'Y-m-d' , $expare );
           
      
    
echo"<fieldset style='width:90%;'>";
echo"<legend><h3>Task Assign </h3></legend>";

echo '<table class="selection">
            <tr>
                <th> ' . _('Customer Name') . ' :<b> ' . $row1['brname'].'</b></th>
                <th>' . _('District') . ' :<b> ' . $row1['district']. '</b></th>
                <th>' . _('Contact No') . ' :<b> ' . $row1['phoneno']. '</b></th>
            </tr>
            <tr>
                <th colspan ="3"><b>' . _('AMC Start date:') . ' ' . convertSQLDate($row1['start_date']) . '</b></th>
            </tr>
            <tr>
                <th colspan ="3"><b>' . _('AMC expiry date:') . ' ' . convertSQLDate($row1['end_date']) . '</b></th>
            </tr>
            </table>
            <br />';
            
            
            
             $planttype=$row1['plant']; 
            
            $sql_new="SELECT count(id) as count from bio_cstask where orderno=$orderno AND despatch_id='$amcno' AND cstype=3";
            $result_new=DB_query($sql_new,$db);
            $row_new=DB_fetch_array($result_new);
                                                    
            /*$sql_getplant="SELECT bio_despatchClearence.stockid from bio_despatchClearence,bio_def_cstask WHERE orderno=$orderno AND despatch_id='$desid' 
            AND bio_despatchClearence.stockid=bio_def_cstask.stockcode";
             $result_getplant=DB_query($sql_getplant,$db);
               $row_getplant=DB_fetch_array($result_getplant); 
               
               $planttype=$row_getplant['stockid'];       */ 
                
            
            $sql_new1="SELECT count(id) as count1 from bio_def_cstask where stockcode='$planttype' AND cstype=3";
            $result_new1=DB_query($sql_new1,$db);
            $row_new1=DB_fetch_array($result_new1);
            
            
            /*$sql_min="SELECT min(prevtask) as recent_task from bio_cstask where orderno='$orderno' AND stockcode='$planttype' 
            AND status=0 AND cstype=2";
            $result_min=DB_query($sql_min,$db);
            $row_min=DB_fetch_array($result_min);*/
           //echo $row_min['recent_task'];
    
      //  echo $row_new['count'];
      
      //--------Select team-----//
      
                    
//---------------------------------------------------------------------//     
 echo"<table style='border:1px solid #F0F0F0;width:100%'>";
 echo"<tr><td>";
 
 //-------------- Add new Tasks-----------------------
 /*echo"<fieldset style='width:95%;'>";
    echo"<legend><h3>Add new task</h3></legend>";
    echo "<table class='selection' style='width:98%;'>";
    echo'<tr><td>Task :</td><td><input type="text" name="des" id="des"></td><td>Days to complete:</td><td><input type="text" name="comdays" id="comdays"></td><td>Previous Task No:</td><td><input type="text" name="prevno" id="prevno"></td><td><input type=submit name=newtask value="' . _('Add Task') . '" onclick= "if(validate()==1)return false;"></td></tr>';
     echo "</table>";
echo "</fieldset>";            */

                                    /* $sql_team="SELECT
    `bio_leadteams`.`teamname`,`bio_leadteams`.`teamid`
        FROM `bio_teammembers`
        INNER JOIN `bio_emp` ON (`bio_teammembers`.`empid` = `bio_emp`.`empid`)
        INNER JOIN `bio_leadteams` ON (`bio_leadteams`.`teamid` = `bio_teammembers`.`teamid`)
        WHERE bio_emp.offid=(SELECT officeid from bio_csmteams where did=".$row1['did'].")
        AND bio_emp.deptid IN (5,6)";*/


 
//-------------- Viwe Assigned Tasks-----------------------
    echo"<fieldset style='width:95%;'>";
    echo"<legend><h3>Task for Periodic Maintenance</h3></legend>";
    echo "<table class='selection' style='width:98%;'>";
    
    echo"<tr><th>Slno</td>
             <th>Task Description</td>
             <th>Scheduled Date</td>
             <th>Days From Installation</td>
             <th>Completed Date</td>
             <th>Team</td> 
             <th>Status</td></tr>";   
            //----For a new entry, task add to the table 
         if($row_new['count']==0){
             //echo"first"; 
             $sql_assigned="SELECT * FROM bio_def_cstask 
                                                    where bio_def_cstask.stockcode='$planttype' AND cstype=3
                                                    ORDER BY bio_def_cstask.daystocomplete";
                        $result_assigned=DB_query($sql_assigned,$db);              
                        $k=0;
                        $slno=1;
                         $newdate=$row1['start_date'];
                        
                        //---Creating temp Table---//
                          $sql_create_instl="CREATE TEMPORARY TABLE IF NOT EXISTS bio_tempcsschedule (orderno int,stockcode varchar(10),taskno int,taskdescription varchar(80),scheduleddate date,daystocomplete int,prevtask int)";
                          $result_create_instl=DB_query($sql_create_instl,$db);
                          $sql_create_instl1="DELETE FROM bio_tempcsschedule WHERE orderno=$orderno AND stockcode='$planttype'";
                          $result_create_instl=DB_query($sql_create_instl1,$db);
                        while($row_assigned=DB_fetch_array($result_assigned))
                        {
                          $days=$row_assigned['daystocomplete'];
                                    $newdate2 = strtotime ( '+'.$days.' day' , strtotime ($row1['start_date']) );
     // echo $newdate = date ( 'Y-m-d' , $newdate );
                                    $newdate3 = date ( 'Y-m-d' , $newdate2 );
                           
                           $sql_tempinsert="INSERT INTO bio_tempcsschedule (orderno,stockcode,taskno,taskdescription,scheduleddate,daystocomplete,prevtask) VALUES ($orderno,'$planttype','".$row_assigned['taskno']."',
                           '".$row_assigned['taskdescription']."','".$newdate3."','".$row_assigned['daystocomplete']."','".$row_assigned['prevtask']."')";
                         $result_tempinsert=DB_query($sql_tempinsert,$db);
                             
                         
                        }
                       
                        
                        $sql_temp="SELECT * FROM bio_tempcsschedule WHERE orderno=$orderno AND stockcode='$planttype'
                        ORDER BY daystocomplete";
                        $result_temp=DB_query($sql_temp,$db);
                        $k=0;
                        $slno=1;
                         while($row_temp=DB_fetch_array($result_temp))
                         {
                           
                          // $newdate=get_instl_taskdate($row_assigned['cstype'],$row_assigned['stockcode'],$row_assigned['taskno'],$newdate,$db);
                           $i++; 
                            //$a=$row_assigned['id'];
                            
                            if ($k==1)
                            {
                            echo '<tr class="EvenTableRows" id="link">';
                            $k=0;
                            }else 
                            {
                             echo '<tr class="OddTableRows" id="link">';
                             $k=1;     
                             }
                             echo"<td>$slno</td>
                             <td><input type=text readonly value='".$row_temp['taskdescription']."' name='des".$i."' id='des".$i."'></td>  
                             
                             <td><input type='text' id='start".$i."' value=".convertSQLDate($row_temp['scheduleddate'])." class=date alt=".$_SESSION['DefaultDateFormat']." name='start".$i."'></td>
                             
                              <td><input type=text readonly value=".$row_temp['daystocomplete']." name='comdays".$i."'  id='comdays".$i."'></td>
                              <td><input type='text' id='complete".$i."' class=date alt=".$_SESSION['DefaultDateFormat']." name='complete".$i."'></td>
                              
                              <td><select  name=team".$i." id='team".$i."' onclick=showteams1(this.id);>";
                                     
                                     $sql_team="SELECT
    `bio_leadteams`.`teamname`,`bio_leadteams`.`teamid`
        FROM `bio_teammembers`
        INNER JOIN `bio_emp` ON (`bio_teammembers`.`empid` = `bio_emp`.`empid`)
        INNER JOIN `bio_leadteams` ON (`bio_leadteams`.`teamid` = `bio_teammembers`.`teamid`)
        WHERE bio_emp.offid=(SELECT officeid from bio_csmteams where did=".$row1['did']." AND stateid=".$row1['stateid']." 
        AND cid=".$row1['cid'].")
        AND bio_emp.deptid IN (5,6)";
        $result_team=DB_query($sql_team,$db);
    //        $row_team=DB_fetch_array($result_team);u
                                    
                                    $f=0;
                                  while($row_team=DB_fetch_array($result_team)){
                                      
                                  if ($row_team['teamid']==$row_temp['team'])  
                                    {         //echo $myrow1['cid'];     
                                    echo '<option selected value="';
                                    } else 
                                    {
                                    if ($f==0) 
                                    {
                                    echo '<option>';
                                    }
                                    echo '<option value="';
                                    }
                                    echo $row_team['teamid'].'">'.$row_team['teamname'];
                                    echo '</option>';
                                    $f++;
                                      
                                  }
                                     echo"</select></td>";
                                     
                                    /* if($row_temp['prevtask']==0){   
                                    echo"<td><input type=text readonly value='No Previous Task'></td>";
                                  }else{
                                      $sql_prevtask="SELECT taskdescription from bio_tempcsschedule where  
                                      stockcode='$planttype' AND taskno='".$row_temp['prevtask']."'";
                                       $result_prevtask=DB_query($sql_prevtask,$db);
                                      $row_prevtask=DB_fetch_array($result_prevtask);
                                      echo" <td><input type=text readonly value='".$row_prevtask['taskdescription']."'></td>";
                                     
                                  }*/
                                  
                                   echo"<input type='hidden' name='prev".$i."' id='prev".$i."' value='".$row_temp['prevtask']."'>
                                    
                                     <td>Not Started</td>
                              
                            </tr>
                             ";
                             $slno++;
                         }                       
             $no=$i;
         //----------For existing entry, to update status of table
         
         }else{       //echo"second";                 
                                                    $sql_assigned="SELECT * FROM bio_cstask 
                                                    where bio_cstask.despatch_id='$amcno' 
                                                    AND orderno='$orderno' AND cstype=3
                                                    ORDER BY daystocomplete";
                        $result_assigned=DB_query($sql_assigned,$db);              
                        $k=0;
                        $slno=1;
                        $i=0;
                        while($row_assigned=DB_fetch_array($result_assigned))
                        {
                            $i++; 
                            $b=$row_assigned['id'];
                            if ($k==1)
                            {
                            echo '<tr class="EvenTableRows" id="link">';
                            $k=0;
                            }else 
                            {
                             echo '<tr class="OddTableRows" id="link">';
                             $k=1;     
                             }
                                             echo"<td>$slno</td> 
                                             
                                             
                                     <td><input type=text readonly value='".$row_assigned['taskdescription']."' name='des".$i."' id='des".$i."'></td>  ";                             
                                   
                                    echo" <td><input type='text' readonly id='start".$i."' value=".convertSQLDate($row_assigned['scheduleddate'])." class=date alt=".$_SESSION['DefaultDateFormat']." name='start".$i."'></td>";
                                    
                                     
                                     echo"<td><input type=text readonly value=".$row_assigned['daystocomplete']." name='comdays".$i."'  id='comdays".$i."'></td>";
                              if($row_assigned['status']==1)       
                                  { 
                                      echo"<td><input type='text' readonly id='complete".$i."' class=date alt=".$_SESSION['DefaultDateFormat']." name='complete".$i."' id='complete".$i."' value=".convertSQLDate($row_assigned['completeddate'])."></td>";
                                      
                                  }   else
                                  {
                                       echo"<td><input type='text' id='complete".$i."' class=date alt=".$_SESSION['DefaultDateFormat']." name='complete".$i."' id='complete".$i."'></td>";
                                  }
                                    
                                     
                                     
                                     
                                      echo"<td><select  name=team".$i." id='team".$i."' onclick=showteams1(this.id);>";
                                     
                                     $sql_team="SELECT
    `bio_leadteams`.`teamname`,`bio_leadteams`.`teamid`
        FROM `bio_teammembers`
        INNER JOIN `bio_emp` ON (`bio_teammembers`.`empid` = `bio_emp`.`empid`)
        INNER JOIN `bio_leadteams` ON (`bio_leadteams`.`teamid` = `bio_teammembers`.`teamid`)
        WHERE bio_emp.offid=(SELECT officeid from bio_csmteams where did=".$row1['did']." AND stateid=".$row1['stateid']."
        AND cid=".$row1['cid']." )
        AND bio_emp.deptid IN (5,6)";
        $result_team=DB_query($sql_team,$db);
    //        $row_team=DB_fetch_array($result_team);u
                                        
                                        $f=0;
                                   
                                  while($row_team=DB_fetch_array($result_team)){
                                  
                                    if ($row_team['teamid']==$row_assigned['team'])  
                                    {         //echo $myrow1['cid'];     
                                    echo '<option selected value="';
                                    } else 
                                    {
                                    if ($f==0) 
                                    {
                                    echo '<option>';
                                    }
                                    echo '<option value="';
                                    }
                                    echo $row_team['teamid'].'">'.$row_team['teamname'];
                                    echo '</option>';
                                    $f++;
                                      

                                  }
                                     echo"</select></td>";
                                     
                                  /*if($row_assigned['prevtask']==0){   
                                    echo" <td><input type=text readonly value='No Previous Task' name='prev".$i."' id='prev".$i."'></td>";
                                  }else{
                                      $sql_prevtask="SELECT taskdescription from bio_cstask where cstype=1 AND orderno='$orderno' 
                                      AND stockcode='$planttype' AND taskno='".$row_assigned['prevtask']."'";
                                       $result_prevtask=DB_query($sql_prevtask,$db);
                                      $row_prevtask=DB_fetch_array($result_prevtask);
                                      echo" <td><input type=text readonly value='".$row_prevtask['taskdescription']."' name='prev".$i."' id='prev".$i."'></td>";
                                  }*/
                                     
                               //if($row_min['recent_task']==$row_assigned['prevtask']&&$row_assigned['status']==0){      
    echo"<td><select name='statuschange' id='".$row_assigned['taskno']."' onchange=changestatus(this.id,$orderno,'$amcno',$i,this.value);>";
                                     if($row_assigned['status']==0){
                                       echo"<option selected value=0>Pending</option>
                                            <option value=1>Completed</option>
                                                    </select></td></tr>";  
                                     }else{
                                         echo"<option value=0>Pending</option>
                                     <option selected value=1>Completed</option>
                                     </select></td></tr>";
                                         
                                     }
                                           
 $slno++;  
                echo'<input type=hidden name="task_no'.$i.'" id=task_no value='.$row_assigned['taskno'].'>';            
                        }
                        $no=$i;
                        //////////////
                        
                        //////////////
        
          
         }            
         
           
    
         


 echo "</table>";
echo "</fieldset>";
//------------------------------------------------------  
   
echo"</td><td>";

//------------------------------------------------------ 
                                
 echo'<input type=hidden name=amcno id=amcno value='.$amcno.'>';  
echo'<input type=hidden name=new_year id=new_year value='.$row1['start_date'].'>';
echo'<input type=hidden name=no id=no value='.$no.'>';
echo'<input type=hidden name=orderno id=orderno value='.$orderno.'>';
echo'<input type=hidden name=plant id=plant value='.$planttype.'>';
echo'<input type=hidden name=custid id=custid value='.$row1['cust_id'].'>';
echo'<input type=hidden name=no id=no value='.$no.'>';




 echo'<input type=hidden name=did id=did value='.$row1['did'].'>';    
echo'<input type=hidden name=stateid id=stateid value='.$row1['stateid'].'>';
echo'<input type=hidden name=cid id=cid value='.$row1['cid'].'>'; 
echo'<input type=hidden name=LSG_type id=LSG_type value='.$row1['LSG_type'].'>';   
echo'<input type=hidden name=LSG_name id=LSG_name value='.$row1['LSG_name'].'>';   
echo'<input type=hidden name=block_name id=block_name value='.$row1['block_name'].'>';  
  
   $sql_checkbox="SELECT count(id) as count from bio_cstask where despatch_id='$amcno' AND orderno=$orderno AND taskdescription='AMC Proposal' 
AND cstype=3";
   
   $result_checkbox=DB_query($sql_checkbox,$db);
         $row_checkbox=DB_fetch_array($result_checkbox);
//echo$a;
if($row_new['count']==0 && $row_new1['count1']!=0){
    if($row_checkbox['count']==0){
        echo"<tr><td><input type=checkbox name=amcproposal>Add AMC Proposal<td></tr>";
    }  
echo'<tr><td colspan=2><p><div class="centre">
         <input type=submit name=submit value="' . _('Submit') . '" onclick="if(validation()==1) return false;">';
}elseif($row_new['count']!=0){
    if($row_checkbox['count']==0){
        echo"<tr><td><input type=checkbox name=amcproposal>Add AMC Proposal<td></tr>";
    } 
    
    echo'<tr><td colspan=2><p><div class="centre">
         <input type=submit name=edit value="' . _('Edit') . '" onclick="if(validation()==1) return false;">';
}

echo"</td></tr>";
echo"</table>";
//------Add new task---------------------------------------------------//   
if( $row_new['count']!=0 || $row_new1['count1']==0  ){
 echo"<fieldset style='width:95%;'>";
    echo"<legend><h3>Add new task</h3></legend>";
    echo "<table class='selection' style='width:98%;'>";
    echo'<tr><td>Task :</td><td><input type="text" name="des" id="des"></td><td>Days From Installation:</td><td><input type="text" name="comdays" id="comdays"></td>';
    
    
/*$sql="SELECT *FROM bio_cstask where stockcode='".$planttype."' AND cstype=2 AND orderno='".$orderno."' ";
   $result=DB_query($sql,$db);
  echo'<td>Previous Task:</td><td><select id="prevno" name="prevno" >';//
    $f=0;
echo'<option value=0>No previous Task</option>';
  while($myrow=DB_fetch_array($result))
  {  
      
  if ($myrow['stockid']==$_GET['plant'])  
    {         //echo $myrow1['cid'];     
    echo '<option selected value="';
    } else 
    {
    if ($f==0) 
        {
        echo '<option>';
        }
        echo '<option value="';
    }
    echo $myrow['taskno'] . '">'.$myrow['taskdescription'];
    echo '</option>';
    $f++;
   } */
   
  echo '<td>Team: </td>';
  
  
   $sql_team="SELECT
    `bio_leadteams`.`teamname`,`bio_leadteams`.`teamid`
        FROM `bio_teammembers`
        INNER JOIN `bio_emp` ON (`bio_teammembers`.`empid` = `bio_emp`.`empid`)
        INNER JOIN `bio_leadteams` ON (`bio_leadteams`.`teamid` = `bio_teammembers`.`teamid`)
        WHERE bio_emp.offid=(SELECT officeid from bio_csmteams where did=".$row1['did']." AND stateid=".$row1['stateid']." 
        AND cid=".$row1['cid']."  )
        AND bio_emp.deptid IN (5,6)";
        $result_team=DB_query($sql_team,$db);
    //        $row_team=DB_fetch_array($result_team);u
                                        echo'<td><select id="team" name="team" >';
                                        $f=0;
                                   
                                  while($row_team=DB_fetch_array($result_team)){
                                  
                                    if ($row_team['teamid']==$row_assigned['team'])  
                                    {         //echo $myrow1['cid'];     
                                    echo '<option selected value="';
                                    } else 
                                    {
                                    if ($f==0) 
                                    {
                                    echo '<option>';
                                    }
                                    echo '<option value="';
                                    }
                                    echo $row_team['teamid'].'">'.$row_team['teamname'];
                                    echo '</option>';
                                    $f++;
                                      

                                  }
                                     echo"</select></td>";

    
    
    echo'<td><input type=submit name=newtask value="' . _('Add Task') . '" onclick= "if(validate()==1)return false;"></td></tr>';
     echo "</table>";
echo "</fieldset>";}
//----------------------------------------------------------------------//

echo''; 
 
 echo"</fieldset>";
 echo"</form>"; 
 

 }     
    
    
?>


<script type="text/javascript">  

function validation()
{
    var f=0; 
    var no=document.getElementById("no").value;      
    for( i=1; i<= no; i++)
    {
       var status='status'+i;                         
       if(document.getElementById(status).checked==true)
       {                        
           var date='date'+i;    
           if(f==0){f=common_error(date,'Please Enter a date');  if(f==1) { return f; }} 
           var autono='autono'+i;
           if(f==0){f=common_error(autono,'Please enter Document No');  if(f==1) { return f; }} 
       }
    }                                                                                           
}

function changestatus(str,str1,str5,str3,str6)
{
//alert(str);      alert(str2);alert(str3);
//alert(str5);
var f=0;
    var str2='complete'+str3;
     var str4=document.getElementById(str2).value;
 //  if(f==0){f=common_error(str4,'Select completed task');  if(f==1) { return f; }}
    //alert(str4);
    if (str=="")
  {
  document.getElementById("maincatid").innerHTML="";
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
        
    document.getElementById("plant1").innerHTML=xmlhttp.responseText;
//      $('#dinhide').show(); 
    }
  } 
  //alert(str);
xmlhttp.open("GET","bio_amc_statuschange.php?taskno="+str+"&orderno="+str1+"&amcno="+str5+"&no="+str3+ "&date=" + str4 + "&status=" + str6,true);
xmlhttp.send();
//alert(str);
}
function validate()
{
 var f=0; 
if(f==0){f=common_error('des','Please Enter task description');  if(f==1) { return f; }} 
if(f==0)
{ 
    var x=document.getElementById('comdays').value;
    
    if(x==""){f=1;
              alert("Enter number of days to start after installation"); document.getElementById('comdays').focus();
              return f; }
    else{
       var l=x.length;
            if(isNaN(x))
           { 
                f=1;
              alert("Enter a numeric value"); document.getElementById('comdays').focus();
              if(x=""){ f=0;}
              return f; 
           }                         
} }  }


</script>
