<?php
$PageSecurity = 80;
include('includes/session.inc'); 

             
if($_GET['officeid']!="")
{
           
    
           ///////////   JK
           
                               $empid=$_SESSION['empid'];   
  

$sql_emp1="SELECT * FROM bio_emp WHERE empid=".$empid;
     $result_emp1=DB_query($sql_emp1,$db);
     $myrow_emp1=DB_fetch_array($result_emp1);
     
       
     
 $employee_arr=array();   
     $sql_drop="DROP TABLE if exists `emptable`";
     $result_drop=DB_query($sql_drop,$db);
 
                      $sql_create="CREATE TABLE emptable (empid int)";
                      $result_create=DB_query($sql_create,$db);   
                       
                  function showemp($empid,$db,$y)         
                  {  
                      $sql3="SELECT empid FROM bio_emp WHERE reportto='".$empid."' and deptid=2 and rowstatus!=2";
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
                      
                      $sql_insert1="INSERT INTO emptable (empid) VALUES ($empid)";
                      $result_insert2=DB_query($sql_insert1,$db);
     
     $sql2="SELECT empid,reportto FROM bio_emp WHERE reportto=$empid and deptid=2 and rowstatus!=2";
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
    while($row6=DB_fetch_array($result6))
    {
        $team_arr[]=$row6['teamid'];
    }
         
    $team_a=join(",", $team_arr);
           
           
           //////////////
  
   echo '<select name="teamname" id="leadteam" style="width:100%">';      
   echo'<option value=0></option>';
   $sql="SELECT * FROM bio_leadteams WHERE teamid in (".$team_a.") and office_id=".$_GET['officeid'];
    $result=DB_query($sql,$db);
    
    while($row=DB_fetch_array($result))
    {
        if ($row['teamid']==$_GET['teamname'])
        {
    echo '<option selected value="';
        } else {
    echo '<option value="';
        }
    echo $row['teamid'].'">'.$row['teamname'];
        echo '</option>';
    }
    echo'</select>';  
}    
    
    
if($_GET['enqid']!="")
{    
    $enqid=$_GET['enqid'];
               
    echo '<select name="task" id="task" style="width:100%">';    
    echo'<option value=0></option>'; 
    if($enqid==1){
    $sql="select * from bio_task WHERE taskid IN (18,20,21,1,5,24,25,26) ORDER BY task ASC";
    }elseif($enqid==2){
    $sql="select * from bio_task WHERE taskid IN (27,15,28,2,14,3,29,4,21,5,24,25,26) ORDER BY task ASC";     
    }
    $result=DB_query($sql,$db);
    
    while($row=DB_fetch_array($result))
    {    
        if ($row['taskid']==$_GET['task'])
        {
            echo '<option selected value="';
        } else {
            echo '<option value="';
        }
        echo $row['taskid'] . '">'.$row['task'];
        echo '</option>';
    }
    echo'</select>';  
    
}

?>
