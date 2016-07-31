<?php
$PageSecurity=80;
$pagetype=1;
include('includes/session.inc');
 $title = _('Weekly Demand'); 
 include('includes/header.inc');
                              include('includes/sidemenu1.php');
 include('includes/SQL_CommonFunctions.inc');
  //echo $date1dd=date("Y");
 $date1=date("Y/m/d");
 //$date1 = '2013/12/29';
$weekday = date('l', strtotime($date1)); // note: first arg to date() is lower-case L
//echo $weekday."<br>"; // SHOULD display Wednesday

  
//$ddate = "2012-10-10";
function get_weekno($date1){
$duedt = explode("/", $date1);
$date2  = mktime(0, 0, 0, $duedt[1], $duedt[2], $duedt[0]);
$week  = (int)date('W', $date2);

 return     $week;
//echo "Weeknummer: " . $week;

}

  //echo get_weekno($date1);
if($weekday=='Monday'){$dayno=1;}
elseif($weekday=='Tuesday'){$dayno=2;}
elseif($weekday=='Wednesday'){$dayno=3;}
elseif($weekday=='Thursday'){$dayno=4;}
elseif($weekday=='Friday'){$dayno=5;}
elseif($weekday=='Saturday'){$dayno=6;}
else{$dayno=7;}
/////////////////////////////////////

if($dayno==1){
$sdate = date("Y-m-d");
//increment 5 days
$edate = strtotime($sdate."+ 5 days");
}
elseif($dayno==2){
$thisday = date("Y-m-d");
 //decrement 1 days
$sdate = strtotime($thisday."- 1 days");
//increment 4 days
$edate = strtotime($thisday."+ 4 days");
}
elseif($dayno==3){
$thisday = date("Y-m-d");
 //decrement 2 days
$sdate = strtotime($thisday."- 2 days");
//increment 3 days
$edate = strtotime($thisday."+ 3 days");
}
elseif($dayno==4){
$thisday = date("Y-m-d");
 //decrement 3 days
$sdate = strtotime($thisday."- 3 days");
//increment 2 days
$edate = strtotime($thisday."+ 2 days");
}
elseif($dayno==5){
$thisday = date("Y-m-d");
 //decrement 4 days
$sdate = strtotime($thisday."- 4 days");
//increment 1 days
$edate = strtotime($thisday."+ 1 days");
}
elseif($dayno==6){
$thisday = date("Y-m-d");
 //decrement 5 days
$sdate = strtotime($thisday."- 5 days");

$edate = date("Y-m-d");
}
else{
    $thisday = date("Y-m-d");
    $sdate = strtotime($thisday."+ 1 days");
    $edate = strtotime($thisday."+ 6 days");
    
}
    // echo date('Y/m/d',$sdate);
////////////////////////////////////
echo '<p class="page_title_text"><img src="'.$rootpath.'/css/'.$theme.'/images/maintenance.png" title="' . _('Search') .
    '" alt="" />' . ' ' . _('Production Planning') . '</p>';

    if(isset($_POST['submit']))
    {
        $totrow=$_POST['totrows'];
        for($i=0;$i<$totrow;$i++)
        { /* echo $i;   echo $_POST['mon'.$i]."<br>";
        echo $i;   echo $_POST['tue'.$i]."<br>";   */
     $sql_insert="INSERT INTO mrpdemands (stockid,quantity,duedate,location,workcenter) VALUES ('".$_POST['stock'.$i]."','".$_POST['mon'.$i]."','".$_POST['day1'.$i]."','".$_POST['loc']."','".$_POST['Workcenter']."')";
    $Result_insert=DB_query($sql_insert,$db);   
       
   $sql_insert1="INSERT INTO mrpdemands (stockid,quantity,duedate,location,workcenter) VALUES ('".$_POST['stock'.$i]."','".$_POST['tue'.$i]."','".$_POST['day2'.$i]."','".$_POST['loc']."','".$_POST['Workcenter']."')";
    $Result_insert1=DB_query($sql_insert1,$db);
    $sql_insert2="INSERT INTO mrpdemands (stockid,quantity,duedate,location,workcenter) VALUES ('".$_POST['stock'.$i]."','".$_POST['wed'.$i]."','".$_POST['day3'.$i]."','".$_POST['loc']."','".$_POST['Workcenter']."')";
    $Result_insert2=DB_query($sql_insert2,$db);
     $sql_insert3="INSERT INTO mrpdemands (stockid,quantity,duedate,location,workcenter) VALUES ('".$_POST['stock'.$i]."','".$_POST['the'.$i]."','".$_POST['day4'.$i]."','".$_POST['loc']."','".$_POST['Workcenter']."')";
    $Result_insert3=DB_query($sql_insert3,$db); 
    $sql_insert4="INSERT INTO mrpdemands (stockid,quantity,duedate,location,workcenter) VALUES ('".$_POST['stock'.$i]."','".$_POST['fri'.$i]."','".$_POST['day5'.$i]."','".$_POST['loc']."','".$_POST['Workcenter']."')";
    $Result_insert4=DB_query($sql_insert4,$db);
    $sql_insert5="INSERT INTO mrpdemands (stockid,quantity,duedate,location,workcenter) VALUES ('".$_POST['stock'.$i]."','".$_POST['sat'.$i]."','".$_POST['day6'.$i]."','".$_POST['loc']."','".$_POST['Workcenter']."')";
    $Result_insert5=DB_query($sql_insert5,$db);         
                                       
         }
    }
    
    if(isset($_POST['update']))
    {
        $totrow=$_POST['totrows'];
        for($i=0;$i<$totrow;$i++)
        { /* echo $i;   echo $_POST['mon'.$i]."<br>";
        echo $i;   echo $_POST['tue'.$i]."<br>";   */
     $sql_insert="UPDATE mrpdemands SET quantity='".$_POST['mon'.$i]."' WHERE stockid='".$_POST['stock'.$i]."' AND duedate='".$_POST['day1'.$i]."' AND location='".$_POST['loc']."' AND workcenter='".$_POST['Workcenter']."' ";
    $Result_insert=DB_query($sql_insert,$db);      
   $sql_insert1="UPDATE mrpdemands SET quantity='".$_POST['tue'.$i]."' WHERE stockid='".$_POST['stock'.$i]."' AND duedate='".$_POST['day2'.$i]."' AND location='".$_POST['loc']."' AND workcenter='".$_POST['Workcenter']."' ";
    $Result_insert1=DB_query($sql_insert1,$db);
    $sql_insert2="UPDATE mrpdemands SET quantity='".$_POST['wed'.$i]."' WHERE stockid='".$_POST['stock'.$i]."' AND duedate='".$_POST['day3'.$i]."' AND location='".$_POST['loc']."' AND workcenter='".$_POST['Workcenter']."' ";
    $Result_insert2=DB_query($sql_insert2,$db);
     $sql_insert3="UPDATE mrpdemands SET quantity='".$_POST['the'.$i]."' WHERE stockid='".$_POST['stock'.$i]."' AND duedate='".$_POST['day4'.$i]."' AND location='".$_POST['loc']."' AND workcenter='".$_POST['Workcenter']."' ";
    $Result_insert3=DB_query($sql_insert3,$db); 
    $sql_insert4="UPDATE mrpdemands SET quantity='".$_POST['fri'.$i]."' WHERE stockid='".$_POST['stock'.$i]."' AND duedate='".$_POST['day5'.$i]."' AND location='".$_POST['loc']."' AND workcenter='".$_POST['Workcenter']."' ";
    $Result_insert4=DB_query($sql_insert4,$db);
    $sql_insert5="UPDATE mrpdemands SET quantity='".$_POST['sat'.$i]."' WHERE stockid='".$_POST['stock'.$i]."' AND duedate='".$_POST['day6'.$i]."' AND location='".$_POST['loc']."' AND workcenter='".$_POST['Workcenter']."' ";
    $Result_insert5=DB_query($sql_insert5,$db);         
                                       
         } 
    }
    
    if(isset($_POST['WO_WEEK']))
    {
        $Get_WOno=DB_query("SELECT max(wo) from workorders",$db);
        $Res_WOno=DB_fetch_array($Get_WOno);
        $WOno=$Res_WOno[0];
        $totrow=$_POST['totrows'];
        for($i=0;$i<$totrow;$i++)
        {  if(is_numeric($_POST['mon'.$i])){
              if($_POST['mon'.$i]>0){  $WOno=$WOno+1; wo_forweek($db,$WOno,$_POST['stock'.$i],$_POST['mon'.$i],$_POST['loc'],$_POST['day1'.$i]); } 
              }   
           
            if(is_numeric($_POST['tue'.$i])){
             if($_POST['tue'.$i]>0){  $WOno=$WOno+1; wo_forweek($db,$WOno,$_POST['stock'.$i],$_POST['tue'.$i],$_POST['loc'],$_POST['day2'.$i]); }
             }
             if(is_numeric($_POST['wed'.$i])){
              if($_POST['wed'.$i]>0){  $WOno=$WOno+1; wo_forweek($db,$WOno,$_POST['stock'.$i],$_POST['wed'.$i],$_POST['loc'],$_POST['day3'.$i]); } 
                        }
              if(is_numeric($_POST['the'.$i])){          
               if($_POST['the'.$i]>0){  $WOno=$WOno+1;   wo_forweek($db,$WOno,$_POST['stock'.$i],$_POST['the'.$i],$_POST['loc'],$_POST['day4'.$i]);}
               }
               if(is_numeric($_POST['fri'.$i])){
                if($_POST['fri'.$i]>0){  $WOno=$WOno+1; wo_forweek($db,$WOno,$_POST['stock'.$i],$_POST['fri'.$i],$_POST['loc'],$_POST['day5'.$i]); } 
               }
               if(is_numeric($_POST['sat'.$i])){
                 if($_POST['sat'.$i]>0){  $WOno=$WOno+1; wo_forweek($db,$WOno,$_POST['stock'.$i],$_POST['sat'.$i],$_POST['loc'],$_POST['day6'.$i]); }        
               }  
                 $WC_Created=$WOno-$Res_WOno[0];              
         }
                $sql_weekwo_warn="INSERT INTO bio_weeklywo (weekno,year,location) values('".$_POST['week']."','".date('Y')."','".$_POST['loc']."') ";
                $res_weekno_warn=DB_query($sql_weekwo_warn,$db);
         
            echo "<div class=success>".$WC_Created." Work Orders created successfully.</div>";   
    }
    function wo_forweek($db,$WOno,$stock,$qty,$LocCode,$date)
    {
         //$_POST['WO'] = GetNextTransNo(40,$db);
    $sql = "INSERT INTO workorders (wo,
                             loccode,
                             requiredby,
                             startdate)
             VALUES ('" . $WOno . "',
                    '" . $LocCode . "',
                    '" . $date. "',
                    '" . $date. "')";
    $InsWOResult = DB_query($sql,$db); 
    // insert parent item info
        $sql1 = "INSERT INTO woitems (wo,
                                 stockid,
                                 qtyreqd
                                 )
             VALUES ( '" . $WOno . "',
                        '" . $stock . "',
                        '" . $qty . "'
                        )"; 
                        
        $result = DB_query($sql1,$db,$ErrMsg);

        //Recursively insert real component requirements - see includes/SQL_CommonFunctions.in for function WoRealRequirements
        WoRealRequirements($db, $WOno, $LocCode, $stock);
        $Sql_Getcomp="SELECT component,quantity FROM bom,stockmaster WHERE parent='".$stock."' AND stockmaster.stockid=bom.component AND stockmaster.mbflag='M'
        AND bom.component!='".$stock."'";
        $res_comp=DB_query($Sql_Getcomp,$db);
        if(DB_num_rows($res_comp)>0){
          while($row_comp=DB_fetch_array($res_comp))
        {
            $comp_qty=$qty*$row_comp['quantity'];
            $sql_comp = "INSERT INTO woitems (wo,
                                 stockid,
                                 qtyreqd
                                 )
             VALUES ( '" . $WOno . "',
                        '" . $row_comp['component'] . "',
                        '" . $comp_qty . "'
                        )"; 
                        
        $result = DB_query($sql_comp,$db,$ErrMsg);
        WoRealRequirements($db, $WOno, $LocCode, $row_comp['component'] );
            
        }  
        }
    
    }
    
    
echo '<form action="' . $_SERVER['PHP_SELF'] . '" method="post">';


/*echo '<table class="selection"><tr>'; */

echo '<table class="selection"><tr>'; 

 /*echo '<td>Location:</td><td><select name="loc" id="loc" style="width:190px" onchange="showitem(this.value)" onblur="showdate1(this.value)" >';  
 $sql="SELECT loccode,locationname FROM  locations
  ";

$rst=DB_query($sql,$db);

while($myrowc=DB_fetch_array($rst))
{
      if ($myrowc[loccode]==$location)
        {  
    echo '<option selected value="';
        }
        else {
    echo '<option value="';
        } 
    echo $myrowc[loccode].'">'.$myrowc[locationname].'</option>';
 }
  echo '</select></td>';
 echo'</td></tr>';   */
 if($_POST['Workcenter'])
{
   
    $workcenter=$_POST['Workcenter'];
     $location=$_POST['loc']; 
}else{
        $location=$_SESSION['UserStockLocation'];
    if($location!=7 && $location!=8){
           $location=7;
    }
    // $location=7;
    $Get_wc=DB_query("SELECT code FROM  workcentres WHERE location=".$location." ",$db);
    $Get_wc1=DB_fetch_array($Get_wc);
    $workcenter=$Get_wc1['code'];
}
echo '<tr><td>Work Center:</td><td id=locat><select name="Workcenter" id="Workcenter" style="width:190px" onchange="showdate(this.value)" >';
   $sql="SELECT code,location,description,capacity FROM  workcentres
  ";
/*if($_POST['Workcenter'])
{
   $workcenter=$_POST['Workcenter']; 
}else{
    $workcenter=$_SESSION['UserStockLocation'];
}  */
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
  echo '</select></td>';
 echo'</td>'; 
 
$year = date('Y');
// Maximum Weeks of an year
$week_count = date('W', strtotime($year . '-12-31'));

if ($week_count == '01')
{   
    $week_count = date('W', strtotime($year . '-12-24'));
}


  /*echo '<tr><td>Year:</td><td><select name="year" id="year" style="width:190px">';
//echo $week_count;
if($_POST['week'])
{
   $year=$_POST['year']; 
}

else{
     $year=date('Y');
}
for($k=2010;$k<=2050;$k++)
{
      if ($k==$year)
        {  
    echo '<option selected value="';
        }
        else {
    echo '<option value="';
        } 
    echo $k.'">'.$k.'</option>';
 }
  echo '</select></td>';
 echo'</td></tr>';    */


  echo '<td>Period:</td><td><select name="week" id="week" style="width:190px" tabindex=11 onchange="showdate1(this.value)">';
//echo $week_count;
if($_POST['week'])
{
   $weekno=$_POST['week']; 
}

else{
     $weekno=get_weekno($date1);
}
for($i=1;$i<=$week_count;$i++)
{
      if ($i==$weekno)
        {  
    echo '<option selected value="';
        }
        else {
    echo '<option value="';
        } 
        $a=getStartAndEndDate($i-1,$year);
        $month=explode("-",$a[0]);
        if(getWeeks($a[0],"monday")==1){
              $sup="st";
        }
         elseif(getWeeks($a[0],"monday")==2){
              $sup="nd";
        }
        elseif(getWeeks($a[0],"monday")==3){
              $sup="rd";
        }
        else{
              $sup="th";
        }  
    echo $i.'">'.date('F', mktime(0,0,0,$month[1],1))."</sup>-".getWeeks($a[0],"monday")."<sup>".$sup.' Week</option>';
 }
  echo '</select></td>';
 echo'</td></tr>'; 
   /*$formtodate=getStartAndEndDate($weekno-1,2013);
   $from=$formtodate[0]; 
  $to=$formtodate[1]; 
  if($_POST['fromto']){
      $fromto=$_POST['fromto'];
  }   else{
          $fromto=ConvertSQLDate($from)."-".ConvertSQLDate($to);
  }
 echo '<tr><td>Date:</td><td id=fromtodate><input type=text name=fromto id=fromto value="'.$fromto.'" style="width:190px"></td></tr>';        */
 

echo"</table>";   
        //  echo $workcenter;
        //  echo  "SELECT code FROM  workcentres WHERE location=".$location."" ;
echo '<div id=itemgrid>';

$sql_weeklywo="SELECT count(*) FROM  bio_weeklywo WHERE weekno='".$weekno."' AND year='".date('Y')."' AND location='".$location."'";
$result_weeklywo=DB_query($sql_weeklywo,$db);
$row_weeklywo=DB_fetch_array($result_weeklywo);

if($row_weeklywo[0]>0)
{
    echo "<div class=warn>Work Order for this week created already</div>";
}


echo"<table style='border:1px solid #F0F0F0;width:98%'; >";   
echo"<tr>";
$formtodate=getStartAndEndDate($weekno-1,$year);
   $from=$formtodate[0]; 
  $to=$formtodate[1]; 
  if($_POST['fromto']){
      $fromto=$_POST['fromto'];
  }   else{
          $fromto=ConvertSQLDate($from)."-".ConvertSQLDate($to);
           $fromto1=ConvertSQLDate($from)." To ".ConvertSQLDate($to);
  }
  $Getloc=DB_query("SELECT locationname FROM locations WHERE loccode=".$location."",$db);
  $Loc=DB_fetch_array($Getloc);
  $LocName=$Loc['locationname'];
  
  $Getwc=DB_query("SELECT description FROM  workcentres WHERE code='".$workcenter."'",$db);
  $Wc=DB_fetch_array($Getwc);
  $WcName=$Wc['description'];
     /*  <td><b>Location:</td><td><b>'.$LocName.'</b></td>     */
echo '<tr><td colspan=10><b><font size="4">Date:</font><font size="4" color="blue"><b> '.$fromto1.' </b></font><b><font size="4">Work Center:</font><b><font size="4" color="blue"> '.$WcName.'</b></font></td></tr>';
echo "<tr><th>Sl No.</th><th width=200px>Item</th><th width=50px>Pending SO</th><th width=50px>Pending WO</th><th th width=50px>Dialy capacity</th><th width=50px>Mon</th><th>Tue</th><th>Wed</th><th>Thu</th><th>Fri</th><th>Sat</th></tr>"; 
$sql="SELECT  stockmaster.stockid,stockmaster.description,bio_wo_stocklocation.capacity,bio_wo_stocklocation.cmcapacity
FROM stockmaster,bio_wo_stocklocation 
WHERE bio_wo_stocklocation.stockid=stockmaster.stockid
AND bio_wo_stocklocation.loccode=".$location."";
$result=DB_query($sql,$db);
$i=1;
$k=0;
$j=0;
$sd=explode("-",$fromto);
    $a=$sd[0];
    $b=$sd[1];
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
    $Get_SO=DB_query("SELECT SUM(salesorderdetails.quantity) FROM salesorderdetails,salesorders
WHERE salesorderdetails.orderno=salesorders.orderno
AND salesorders.orderno NOT IN (SELECT orderno FROM bio_installation_status)
AND salesorderdetails.stkcode='".$myrow['stockid']."'",$db);
$Res_SO=DB_fetch_array($Get_SO);
    echo "<td>".$Res_SO[0]."</td>";     // Pending SO
    $Get_PO=DB_query("SELECT SUM(woitems.qtyreqd-woitems.qtyrecd)  FROM woitems,workorders WHERE woitems.stockid='".$myrow['stockid']."' 
AND woitems.wo=workorders.wo
AND workorders.closed='0'",$db);
$Res_PO=DB_fetch_array($Get_PO);
    echo "<td>".$Res_PO[0]."</td>"; // Pending WO
        if($myrow['capacity']!=0)
    {
        $qty=$myrow['capacity'];
        $unit="Nos.";
        /*echo "<input type=hidden name=capa_type".$j." id=capa_type".$j." value=1 >";
        echo "<input type=hidden name=capacity".$j." id=capacity".$j." value=".$qty." >";*/
    }else{
        $qty=$myrow['cmcapacity'];
        $unit="Cub. Mtr.";
        

        
        // get plant capacity
        $Plant_cap=DB_query("SELECT value FROM  stockitemproperties WHERE stockid='".$Row['stockid']."'
        AND stkcatpropid=48",$db);
        $Pcap=DB_fetch_array($Plant_cap,$db);
        
       /* echo "<input type=hidden name=capa_type".$J." id=capa_type".$j." value=2 >";
        echo "<input type=hidden name=capacity".$j." id=capacity".$j." value=".$qty." >";
        echo "<input type=hidden name=defined_cap".$j." id=defined_cap".$j." value=".$Pcap[0]." >";    */
        
    }
    
     echo "<td>".$qty." ".$unit."</td>";
    
        //Monday
         $Get_value=DB_query("SELECT quantity FROM mrpdemands WHERE location=".$location." AND workcenter='".$workcenter."' AND duedate='".$from."' AND stockid='".$myrow['stockid']."' ",$db);
          $Row_value=DB_fetch_array($Get_value);
    echo "<td><input type=text size='5' name=mon".$j." value=".$Row_value['quantity']."></td>";
                echo "<input type=hidden name=day1".$j."  value=".$from.">" ;
         //Tuesday
          $tue = strtotime($from."+ 1 days");
        // echo "SELECT quantity FROM mrpdemands WHERE location=".$location." AND workcenter='".$workcenter."' AND duedate='".date('Y-m-d',$tue)."'";  
            
            $Get_value=DB_query("SELECT quantity FROM mrpdemands WHERE location=".$location." AND workcenter='".$workcenter."' AND duedate='".date('Y-m-d',$tue)."' AND stockid='".$myrow['stockid']."' ",$db);
          $Row_value=DB_fetch_array($Get_value);      
    echo "<td><input type=text size='5' name=tue".$j." value=".$Row_value['quantity']."></td>";
                echo "<input type=hidden name=day2".$j."  value=".date('Y-m-d',$tue).">" ;
            
            //Wednesday
            $wed = strtotime($from."+ 2 days"); 
            $Get_value=DB_query("SELECT quantity FROM mrpdemands WHERE location=".$location." AND workcenter='".$workcenter."' AND duedate='".date('Y-m-d',$wed)."' AND stockid='".$myrow['stockid']."'  ",$db);
          $Row_value=DB_fetch_array($Get_value);    
    echo "<td><input size='5' type=text name=wed".$j." value=".$Row_value['quantity']."></td>";
                echo "<input type=hidden name=day3".$j."  value=".date('Y-m-d',$wed).">" ;
     
      //Thersday
     $the = strtotime($from."+ 3 days"); 
     $Get_value=DB_query("SELECT quantity FROM mrpdemands WHERE location=".$location." AND workcenter='".$workcenter."' AND duedate='".date('Y-m-d',$the)."' AND stockid='".$myrow['stockid']."'   ",$db);
     $Row_value=DB_fetch_array($Get_value);  
    echo "<td><input type=text size='5' name=the".$j." value=".$Row_value['quantity']."></td>";
                echo "<input type=hidden name=day4".$j."  value=".date('Y-m-d',$the).">" ;
    
    //friday
    $fri = strtotime($from."+ 4 days");
    $Get_value=DB_query("SELECT quantity FROM mrpdemands WHERE location=".$location." AND workcenter='".$workcenter."' AND duedate='".date('Y-m-d',$fri)."' AND stockid='".$myrow['stockid']."'   ",$db);
    $Row_value=DB_fetch_array($Get_value);   
    echo "<td><input type=text size='5' name=fri".$j." value=".$Row_value['quantity']."></td>";
                echo "<input type=hidden name=day5".$j."  value=".date('Y-m-d',$fri).">" ;
    
    //Saturday
    $sat = strtotime($from."+ 5 days");
    $Get_value=DB_query("SELECT quantity FROM mrpdemands WHERE location=".$location." AND workcenter='".$workcenter."' AND duedate='".date('Y-m-d',$sat)."' AND stockid='".$myrow['stockid']."'   ",$db);
    $Row_value=DB_fetch_array($Get_value);   
    echo "<td><input type=text size='5' name=sat".$j." value=".$Row_value['quantity']."></td>";
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
                    echo '<input type=submit name="WO_WEEK" value="' . _('WO For Week') . '">';
     }
    
   /*    echo '<input type=submit name="" value="' . _('Approve') . '">';  */
         echo '&nbsp&nbsp<input type=button class=button_details_show name="" value="' . _('Report') . '">';
/*        echo '<input type=submit name="" value="' . _('View Pending Order') . '">';
        echo '<input type=submit name="" value="' . _('View Pending Work Order') . '">';
        echo '<input type=submit name="" value="' . _('View Plan of all center') . '">';  */
   
   echo "</div>"; 
    echo"<div id='selectiondetails'>";

echo"<fieldset style='width:98%; overflow:auto;'>";
echo"<legend><h3>Reports</h3></legend>";
echo '<table width="100%">
    <tr>
        <th width="50%">' . _('Reports') . '</th>
        
   
    </tr>';
echo"<tr><td  VALIGN=TOP >";
echo '<a  class=button_details_show1 style=cursor:pointer; >' . _('View Pending Sale Order') . '</a><br>';
echo '<a class=button_details_show2 style=cursor:pointer; >' . _('View Plan of All Center('.$fromto.')') . '</a><br>';
echo '<a style=cursor:pointer; >' . _('View Material Stock') . '</a><br>';
echo '<a  style=cursor:pointer; >' . _('View MRP Shortage') . '</a><br>';
/*echo '<a style=cursor:pointer; onclick=showProdPeriod()>' . _('Cutomer Ledger') . '</a><br>';
echo '<a style=cursor:pointer; onclick=showProdPeriod()>' . _('Cash Book') . '</a><br>';*/


/*echo"</td><td  VALIGN=TOP >";
echo '<a href="bio_dprint_A5p.php" style=cursor:pointer;>' . _('Print Advance Reciept') . '</a><br>';
//echo '<a style=cursor:pointer; onclick=showProdPeriod()>' . _('Print Covering Letter') . '</a><br>';
//echo '<a style=cursor:pointer; onclick=addNewSeasonName()>' . _('Add New Season Name') . '</a><br>';
echo"</td></tr>";*/
echo'</table>';
echo"</fieldset>";

echo "</div>"; 

///// Pending SO RePORT
 echo"<div id='selectiondetails1'>";

echo"<fieldset style='width:98%; overflow:auto;'>";
echo"<legend><h3>Report :Pending Sale Order List</h3></legend>";
echo '<table width="100%">
<tr><td></td><td></td><td><a class=button_details_show1 style=cursor:pointer; >' . _('close') . '</a></td></tr>
    <tr>
        <th width="10%">' . _('Sl No.') . '</th>
        <th width="75%">' . _('Item') . '</th>
        <th width="15%">' . _('Quantity') . '</th>
   
    </tr>';
    
    $Sql_GetSO="SELECT bio_wo_stocklocation.stockid,SUM(salesorderdetails.quantity),stockmaster.description
FROM salesorderdetails,bio_wo_stocklocation,stockmaster,salesorders
WHERE bio_wo_stocklocation.stockid=salesorderdetails.stkcode   
AND stockmaster.stockid=bio_wo_stocklocation.stockid
AND salesorders.orderno=salesorderdetails.orderno
AND salesorders.orderno NOT IN (SELECT orderno FROM bio_installation_status)
GROUP BY bio_wo_stocklocation.stockid";
$Result_SO=DB_query($Sql_GetSO,$db);
$k=0;
$i=1;
while($Row_SO=DB_fetch_array($Result_SO))
{ if ($k==1)
    {
    echo '<tr class="EvenTableRows">';
    $k=0;
    }else 
    {
    echo '<tr class="OddTableRows">';
    $k=1;     
    }
    
    echo "<td>".$i."</td>";
    echo "<td>".$Row_SO['description']."</td>";
    echo "<td>".$Row_SO[1]."</td></tr>";
    $i++;
}

echo'</table>';
echo"</fieldset>";

echo "</div>";  


///// Weekly  plan

 echo"<div id='selectiondetails2'>";

echo"<fieldset style='width:98%; overflow:auto;'>";
echo"<legend><h3>Report :Plan For All Center (".$fromto.")</h3></legend>";
echo '<table width="100%">
     <tr><td></td><td></td><td><a class=button_details_show2 style=cursor:pointer; >' . _('close') . '</a></td></tr>
    <tr>
   
        <th width="10%">' . _('Sl No.') . '</th>
        <th width="75%">' . _('Item') . '</th>
        <th width="15%">' . _('Quantity') . '</th>
   
    </tr>';
    $sd=explode("-",$fromto);
    $a=$sd[0];
    $b=$sd[1];
    $Sql_GetSO="SELECT stockmaster.description,SUM(mrpdemands.quantity) AS qty 
FROM mrpdemands,stockmaster
WHERE mrpdemands.stockid=stockmaster.stockid
AND mrpdemands.duedate between '".FormatDateForSQL($a)."'  and '".FormatDateForSQL($b)."'
GROUP BY mrpdemands.stockid HAVING(qty>0)";
$Result_SO=DB_query($Sql_GetSO,$db);
$k=0;
$i=1;
while($Row_SO=DB_fetch_array($Result_SO))
{ if ($k==1)
    {
    echo '<tr class="EvenTableRows">';
    $k=0;
    }else 
    {
    echo '<tr class="OddTableRows">';
    $k=1;     
    }
    
    echo "<td>".$i."</td>";
    echo "<td>".$Row_SO['description']."</td>";
    echo "<td>".$Row_SO[1]."</td></tr>";
    $i++;
}

echo'</table>';
echo"</fieldset>";

echo "</div>"; 

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

   function getWeeks($date, $rollover)
    {
        $cut = substr($date, 0, 8);
        $daylen = 86400;

        $timestamp = strtotime($date);
        $first = strtotime($cut . "00");
        $elapsed = ($timestamp - $first) / $daylen;

        $i = 1;
        $weeks = 1;

        for($i; $i<=$elapsed; $i++)
        {
            $dayfind = $cut . (strlen($i) < 2 ? '0' . $i : $i);
            $daytimestamp = strtotime($dayfind);

            $day = strtolower(date("l", $daytimestamp));

            if($day == strtolower($rollover))  $weeks ++;
        }

        return $weeks;
    }
  
    echo "<input type=hidden name=year id=year value=".date('Y').">";
    echo "<input type=hidden id=loc name=loc value=".$location.">";
    echo "</form>";
 //include('includes/footer.inc');

?>
<script>
$("#selectiondetails").hide(); 

$("#selectiondetails1").hide(); 
$("#selectiondetails2").hide();
$('.button_details_show').click(function() {
  $('#selectiondetails').slideToggle('slow', function() {
    // Animation complete.
  });
});
$('.button_details_show1').click(function() {
  $('#selectiondetails1').slideToggle('slow', function() {
    // Animation complete.
  });
});
$('.button_details_show2').click(function() {
  $('#selectiondetails2').slideToggle('slow', function() {
    // Animation complete.
  });
});


function showdate(str)
{    var str2=document.getElementById("week").value; 
  var str1=document.getElementById("year").value;  //  alert(str1);
      //  var str2=document.getElementById("Workcenter").value;
        //var str3=document.getElementById("loc").value;
    //alert(str);
      if (str=="")
  {
  document.getElementById("itemgrid").innerHTML="";
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
    document.getElementById("itemgrid").innerHTML=xmlhttp.responseText;
      $("#selectiondetails_aj").hide(); 
      $("#selectiondetails11").hide(); 
      $("#selectiondetails21").hide();
      $('.button_details_show_aj').click(function() {
  $('#selectiondetails_aj').slideToggle('slow', function() {
    // Animation complete.
  });
});
    }
  } 
xmlhttp.open("GET","bio_datefromweekno.php?weekno=" + str2 + "&year=" +str1 + "&wc=" + str ,true);
xmlhttp.send();   
}
function showdate1(str)  
{   
var str=document.getElementById("week").value; 
  var str1=document.getElementById("year").value;  //  alert(str1);
        var str2=document.getElementById("Workcenter").value;
        //var str3=document.getElementById("loc").value;
    //alert(str);
      if (str=="")
  {
  document.getElementById("itemgrid").innerHTML="";
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
    document.getElementById("itemgrid").innerHTML=xmlhttp.responseText;
      $("#selectiondetails_aj").hide();
      $("#selectiondetails11").hide(); 
      $("#selectiondetails21").hide();
      $('.button_details_show_aj').click(function() {
  $('#selectiondetails_aj').slideToggle('slow', function() {
    // Animation complete.
  });
});

     $('.button_details_show11').click(function() {
  $('#selectiondetails11').slideToggle('slow', function() {
    // Animation complete.
  });
});

     $('.button_details_show21').click(function() {
  $('#selectiondetails21').slideToggle('slow', function() {
    // Animation complete.
  });
});

    }
  } 
xmlhttp.open("GET","bio_datefromweekno.php?weekno=" + str + "&year=" +str1 + "&wc=" + str2 ,true);
xmlhttp.send();   
}



function showitem(str)
{    // var str1=document.getElementById("year").value;  //  alert(str1);
    //alert(str);
      if (str=="")
  {
  document.getElementById("locat").innerHTML="";
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
    document.getElementById("locat").innerHTML=xmlhttp.responseText;

    }
  } 
xmlhttp.open("GET","bio_datefromweekno.php?loc=" + str ,true);
xmlhttp.send();   
}


</script>