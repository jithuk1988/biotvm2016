<?php
    $PageSecurity = 80;
  include('includes/session.inc');
//  include('includes/SQL_CommonFunctions.inc');  

$name=$_GET['name'];
if($_GET['name'])
{
   $sql_dupName="SELECT * FROM bio_cust WHERE custname LIKE '$name'"; 
   $result_dupName=DB_query($sql_dupName,$db);
   
  echo "<fieldset style='float:center;width:97%;'>";  
  echo "<legend><h3>Dupicate Names</h3>";
  echo "</legend>";
  echo "<div style='height:80px; overflow:scroll;'>";
  echo "<table><tr><td>";
  
   $k=0;
   while($row_dupName=DB_fetch_array($result_dupName))
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

                $dupnames=$row_dupName['custname'];
 
                $custmob=$row_dupName['custmob'];
                if($custmob=="" OR $custmob==0){
                    $custmob=$row_dupName['custphone'];
                if($custmob=="" OR $custmob==0){
                    $custmob="";
                    }
                }  
                
                $sql_dupdist="SELECT bio_district.district 
                                FROM bio_cust,bio_district 
                               WHERE bio_cust.nationality=".$row_dupName['nationality']." AND bio_cust.state=".$row_dupName['state']." AND bio_cust.district=".$row_dupName['district']." 
                                 AND bio_cust.nationality=bio_district.cid
                                 AND bio_cust.state=bio_district.stateid 
                                 AND bio_cust.district=bio_district.did
                                 ";
                $result_dupdist=DB_query($sql_dupdist,$db);
                $row_dupdist=DB_fetch_array($result_dupdist);
                $district=$row_dupdist['district'];   
                
                $sql_dupleadid="SELECT * FROM bio_leads WHERE cust_id=".$row_dupName['cust_id']."";
                $result_dupleadid=DB_query($sql_dupleadid,$db);
                $row__dupleadid=DB_fetch_array($result_dupleadid);
                $leadid=$row__dupleadid['leadid'];
                $enq=$row__dupleadid['enqtypeid'];
                               
                if($leadid!='')
                {
                
                $sql_dupaction="SELECT * FROM bio_leadtask,bio_task
                                 WHERE bio_leadtask.leadid=".$leadid."
                                   AND bio_leadtask.taskcompletedstatus=0
                                   AND bio_task.taskid=bio_leadtask.taskid
                              ORDER BY bio_leadtask.assigneddate ASC
                              ";
               $result_dupaction=DB_query($sql_dupaction,$db);
               $row_dupaction=DB_fetch_array($result_dupaction);
               $next_action=$row_dupaction['task']; 
               $action_date=$row_dupaction['assigneddate'];
              
               if($action_date!="" AND $next_action!==""){
                 $action_date=ConvertSQLDate($action_date);
               }else{
                 $next_action='Not assigned';
                 $action_date='Not assigned'; 
               }   
               
               $sql_dupstatus="SELECT bio_status.biostatus FROM bio_leads,bio_status WHERE bio_leads.leadstatus=bio_status.statusid AND bio_leads.leadid=$leadid";
               $result_dupstatus=DB_query($sql_dupstatus,$db);
               $row_dupstatus=DB_fetch_array($result_dupstatus);
               $biostatus=$row_dupstatus['biostatus'];           
                }
   
   printf("<td cellpading=2 width='150px'>%s</td>
        <td width='100px'>%s</td>
        <td width='170px'>%s</td>
        <td width='160px'>%s</td>
        <td width='85px'>%s</td> 
        <td width='85px'>%s</td> 
        <td width='50px'><a  style='cursor:pointer;'  onclick=duplicate('$leadid','$enq')>" . _('Edit') . "</a></td>  
        </tr>",
        $dupnames,
        $custmob,
        $district,
        $next_action,
        $action_date,
        $biostatus,
        $advance_amount,
        $_SERVER['PHP_SELF'] . '?' . SID,$myrow[0]);
   }
echo "</td></tr></table>"; 
echo "</fieldset>";  
}
?>
