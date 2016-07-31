<?php
$PageSecurity=80;
include('includes/session.inc');
  //echo $_GET['weekno'].",".$_GET['year'];
  
    function getStartAndEndDate($week, $year)
{

    $time = strtotime("1 January $year", time());
    $day = date('w', $time);
    $time += ((7*$week)+1-$day)*24*3600;
    $return[0] = date('Y-m-d', $time);
    $time += 6*24*3600;
    $return[1] = date('Y-m-d', $time);
    return $return;
}
/* if($_GET['weekno']!=""){
  
 
       $location=$_GET['lc'];
      $workcenter=$_GET['wc'];
 
 
  echo"<table style='border:1px solid #F0F0F0;width:98%'; >";   
echo"<tr>";
$formtodate=getStartAndEndDate($_GET['weekno']-1,$_GET['year']);
   $from=$formtodate[0]; 
  $to=$formtodate[1]; 
 /* if($_POST['fromto']){
      $fromto=$_POST['fromto'];
  }   else{   */
         // $fromto=ConvertSQLDate($from)."-".ConvertSQLDate($to);
/*  }   */  /*
  $Getloc=DB_query("SELECT locationname FROM locations WHERE loccode=".$location."",$db);
  $Loc=DB_fetch_array($Getloc);
  $LocName=$Loc['locationname'];
  
  $Getwc=DB_query("SELECT description FROM  workcentres WHERE code='".$workcenter."'",$db);
  $Wc=DB_fetch_array($Getwc);
  $WcName=$Wc['description'];
  
 echo '<tr><td colspan=2><b>Date:</td><td colspan=2><b>'.$fromto.'</b></td><td><b>Location:</td><td><b>'.$LocName.'</b></td><td><b>Work Center:</td><td><b>'.$WcName.'</b></td></tr>';
echo "<tr><th>Sl No.</th><th width=125px>Item</th><th>Pending SO</th><th>Pending WO</th><th>Monday</th><th>Tuesday</th><th>Wednesday</th><th>Thursday</th><th>Friday</th><th>Saturday</th></tr>"; 
$sql="SELECT  stockmaster.stockid,stockmaster.description FROM stockmaster,bio_wo_stocklocation WHERE bio_wo_stocklocation.stockid=stockmaster.stockid";
$result=DB_query($sql,$db);
$i=1;
$k=0;
$j=0;
while($myrow=DB_fetch_array($result))
{
    if ($k==1)
    {
    echo '<tr class="EvenTableRows">';
    $k=0;
    }else 
    {
    echo '<tr class="OddTableRows">';
    $k=1;     
    }
    echo "<td>".$i."</td>";
    echo "<td>".$myrow['description']."</td>";
    echo "<td></td>";
    echo "<td></td>";
        //Monday
         $Get_value=DB_query("SELECT quantity FROM mrpdemands WHERE location=".$location." AND workcenter='".$workcenter."' AND duedate='".$from."' AND stockid='".$myrow['stockid']."' ",$db);
          $Row_value=DB_fetch_array($Get_value);
    echo "<td><input type=text name=mon".$j." value=".$Row_value['quantity']."></td>";
                echo "<input type=hidden name=day1".$j."  value=".$from.">" ;
         //Tuesday
          $tue = strtotime($from."+ 1 days");
       
            
    $Get_value=DB_query("SELECT quantity FROM mrpdemands WHERE location=".$location." AND workcenter='".$workcenter."' AND duedate='".date('Y-m-d',$tue)."' AND stockid='".$myrow['stockid']."' ",$db);
     $Row_value=DB_fetch_array($Get_value);      
    echo "<td><input type=text name=tue".$j." value=".$Row_value['quantity']."></td>";
                echo "<input type=hidden name=day2".$j."  value=".date('Y-m-d',$tue).">" ;
            
            //Wednesday
            $wed = strtotime($from."+ 2 days"); 
            $Get_value=DB_query("SELECT quantity FROM mrpdemands WHERE location=".$location." AND workcenter='".$workcenter."' AND duedate='".date('Y-m-d',$wed)."' AND stockid='".$myrow['stockid']."'  ",$db);
          $Row_value=DB_fetch_array($Get_value);    
    echo "<td><input type=text name=wed".$j." value=".$Row_value['quantity']."></td>";
                echo "<input type=hidden name=day3".$j."  value=".date('Y-m-d',$wed).">" ;
     
      //Thersday
     $the = strtotime($from."+ 3 days"); 
     $Get_value=DB_query("SELECT quantity FROM mrpdemands WHERE location=".$location." AND workcenter='".$workcenter."' AND duedate='".date('Y-m-d',$the)."' AND stockid='".$myrow['stockid']."'   ",$db);
     $Row_value=DB_fetch_array($Get_value);  
    echo "<td><input type=text name=the".$j." value=".$Row_value['quantity']."></td>";
                echo "<input type=hidden name=day4".$j."  value=".date('Y-m-d',$the).">" ;
    
    //friday
    $fri = strtotime($from."+ 4 days");
    $Get_value=DB_query("SELECT quantity FROM mrpdemands WHERE location=".$location." AND workcenter='".$workcenter."' AND duedate='".date('Y-m-d',$fri)."' AND stockid='".$myrow['stockid']."'   ",$db);
    $Row_value=DB_fetch_array($Get_value);   
    echo "<td><input type=text name=fri".$j." value=".$Row_value['quantity']."></td>";
                echo "<input type=hidden name=day5".$j."  value=".date('Y-m-d',$fri).">" ;
    
    //Saturday
    $sat = strtotime($from."+ 5 days");
    $Get_value=DB_query("SELECT quantity FROM mrpdemands WHERE location=".$location." AND workcenter='".$workcenter."' AND duedate='".date('Y-m-d',$sat)."' AND stockid='".$myrow['stockid']."'   ",$db);
    $Row_value=DB_fetch_array($Get_value);   
    echo "<td><input type=text name=sat".$j." value=".$Row_value['quantity']."></td>";
                 echo "<input type=hidden name=day6".$j."  value=".date('Y-m-d',$sat).">" ;
                 echo "<input type=hidden name=stock".$j." value='".$myrow['stockid']."'>";
    $i++;
     $j++;
}

            echo "<input type=hidden name=totrows value=".$j.">";
                     
            
   echo '</table>';
     $sql_count="SELECT count(*) FROM  mrpdemands WHERE duedate='".$from."' AND location=".$location." AND    workcenter='".$workcenter."' ";
     $result_count=DB_query($sql_count,$db);
     $row_count=DB_fetch_array($result_count);
     if($row_count[0]==0){
               echo '<div class="centre"><br /><input type=submit name="submit" value="' . _('Submit') . '">';
     }
     else{
                    echo '<div class="centre"><br /><input type=submit name="update" value="' . _('Update') . '">';
     }
     
          echo '<input type=submit name="" value="' . _('Approve') . '">';
        echo '<input type=submit name="" value="' . _('Report') . '">';
        echo '<input type=submit name="" value="' . _('View Pending Order') . '">';
        echo '<input type=submit name="" value="' . _('View Pending Work Order') . '">';
        echo '<input type=submit name="" value="' . _('View Plan of all center') . '">';
   
   
   echo "</div>"; 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 


 }*/
 
  if($_GET['WC1']!="")
 {
     
     $workcenter=$_GET['WC1'];
       $sql="SELECT DISTINCT duedate FROM mrpdemands WHERE  workcenter='".$workcenter."' order by duedate"; 
 echo '<select name="date1" id="date1" style="width:190px">';
  /*location='".$location."' AND*/
if($_POST['date1'])
{
   $date1=$_POST['date1']; 
}else{
    $date1=date('Y-m-d');
}         
$rst=DB_query($sql,$db);

while($myrowc=DB_fetch_array($rst))
{
      if ($myrowc[duedate]==$date1)
        {  
    echo '<option selected value="';
        }
        else {
    echo '<option value="';
        } 
    echo $myrowc[duedate].'">'.ConvertSQLDate($myrowc[duedate]).'-'.date('l', strtotime($myrowc[duedate])).'</option>';
 }
  echo '</select>';   
 }
 
/* 
 if($_GET['loc']!="")
 {

   
      $Get_wc=DB_query("SELECT code FROM  workcentres WHERE location='".$_GET['loc']."' ",$db);
    $Get_wc1=DB_fetch_array($Get_wc);
    $workcenter=$Get_wc1['code'];
     if($workcenter==""){
         echo "<div class=warn>No workcenter for this location";
     }
      else{  echo' <select name="Workcenter" id="Workcenter" style="width:190px">';
   $sql="SELECT code,location,description,capacity FROM  workcentres
  ";

$rst=DB_query($sql,$db);

while($myrowc=DB_fetch_array($rst))
{
      if ($myrowc[code]==$workcenter)
        {  
    echo '<option selected value="';
        }
        else {
    echo '<option value="';
        } 
    echo $myrowc[code].'">'.$myrowc[description].'</option>';
 }
  echo '</select>';
      
      }     

 }
    */
 if($_GET['loc1']!="")
 {

   
 echo '<select name="date1" id="date1" style="width:190px">';
   $sql="SELECT DISTINCT duedate FROM mrpdemands WHERE location='".$_GET['loc1']."' AND workcenter='".$_GET['wc1']."' order by duedate";
if($_POST['date1'])
{
   $date1=$_POST['date1']; 
}else{
    $date1=date('Y-m-d');
}         
$rst=DB_query($sql,$db);

while($myrowc=DB_fetch_array($rst))
{
      if ($myrowc[duedate]==$date1)
        {  
    echo '<option selected value="';
        }
        else {
    echo '<option value="';
        } 
    echo $myrowc[duedate].'">'.ConvertSQLDate($myrowc[duedate]).'-'.date('l', strtotime($myrowc[duedate])).'</option>';
 }
  echo '</select>';  

 }
?>
