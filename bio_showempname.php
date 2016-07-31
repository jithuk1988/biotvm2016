<?php
  $PageSecurity = 80;  
include('includes/session.inc');
 $dept=$_GET['dept'];
//echo '<br />';
// $offc=$_GET['off'] ;
   $country=$_GET['country'] ;
   $state=$_GET['state'] ;
   $district=$_GET['district'] ;
   
   
   if($country==1 && $state==14)        //KERALA
   {                  
       if( $district==6 || $district==11 || $district==12 )    //KLM-PTA-TVM
       {
           $office=4;
       }
       elseif( $district==1 || $district==2 || $district==3 || $district==7 || $district==13 )  //ALP-EKM-IDK-KTM-TRS
       {
          
          $office=2;                     
       }
       elseif( $district==4 || $district==5 || $district==8 || $district==9 || $district==10 || $district==14 ) //KNR-KSR-KZH-MLP-PLK-WND
       {
           $office=3;                     
       }
   } 
   elseif($country==1 && $state!=14)     //OUTSIDE KERALA
   {
      $office=4;  
   }
  
         elseif($country!=1)     //OUTSIDE KERALA
   {
        $office=4;  
   }
  
   
   
   
 $sql="SELECT * FROM bio_emp,bio_leadteams,bio_teammembers 
        WHERE deptid=$dept 
        AND bio_leadteams.teamid=bio_teammembers.teamid
        AND bio_emp.empid=bio_teammembers.empid";
  if($offid=4){
     $sql.=" AND (offid=$office or offid=1)"; 
  }      
   else{
       
    $sql.=" AND offid=$office";   
   }   
        
     
    $result=DB_query($sql,$db);
    $count=DB_num_rows($result); 
       
   echo '<select name="empname" id="empname" style="width:170px">';    
  
    $f=0;
  if($count>0) { 
    
  while($myrow1=DB_fetch_array($result))
  {  
  if ($myrow1['teamid']==1)  
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
    echo $myrow1['teamid'] . '">'.$myrow1['teamname'];
    echo '</option>';
    $f++;
   } 
  
  }
  else{   echo '<option value=""></option>';
      echo '<option value="1">Director</option>';
      
  }
  
  echo'</select>';

?>
