<?php
$PageSecurity = 80;
include('includes/session.inc');
$title = _('Leads Report');  
include('includes/header.inc');
//$office=$_SESSION['officeid'];  
 if(isset($_POST['caldate']))
{
 $create=$_POST['caldate'];
    $createdate=FormatDateForSQL($create);
     $date = strtotime(date("Y-m-d", strtotime($createdate)) . " +1 day");
 $date1=date('Y-m-d',$date);
}
else{
    $createdate=date("Y-m-d");
     $date = strtotime(date("Y-m-d", strtotime($createdate)) . " +1 day");
 $date1=date('Y-m-d',$date);
}  
    
    $a=array("4","2","3");//office_id
   // $b=array("1","2","3","8","7","13","12");//enqtypeid
    $b=array("1","2");//enqtypeid
    $c=array();
    $d=array();
    $e=array();
    $f=array();
//  echo  $a[1];
/////Lead to contact
for($i=0;$i<=2;$i++)
{
for($j=0;$j<=1;$j++)
{  
$ab=" SELECT count( distinct bio_leads.`leadid`)
 FROM `bio_leadtask` inner join bio_leads on ( bio_leads.`leadid`=bio_leadtask.leadid )
 WHERE bio_leadtask.duedate <= '$createdate ' 
AND bio_leadtask.taskid in (18,27)
 AND bio_leadtask.taskcompletedstatus =0
  AND bio_leadtask.viewstatus =1

 AND bio_leadtask.`teamid` in(SELECT `teamid` FROM `bio_leadteams` WHERE `office_id` = $a[$i]) 
And  bio_leads.leadstatus !=20 AND bio_leads.enqtypeid =$b[$j] "; // AND bio_leadtask.taskid =18 

   $ab;
$result=DB_query($ab, $db); 
        $myrow1=DB_fetch_row($result);
    $ltc1=$myrow1[0];
 // $results=$ltc1;
 //echo "</br> $a[$i] $b[$j] ".   $c[$j][$i]= $ltc1;
$c[$j][$i]= $ltc1;
}
}


//////Lead contacted
for($i=0;$i<=2;$i++)
{
for($j=0;$j<=1;$j++)
{  //"AND dateCreated BETWEEN '"&fromDate&"' AND '"&toDate&"' "
$ab=" SELECT count( distinct bio_leads.`leadid`)
 FROM `bio_leadtask` inner join bio_leads on ( bio_leads.`leadid`=bio_leadtask.leadid )
 WHERE bio_leadtask.taskcompleteddate like '$createdate %' 
AND bio_leadtask.taskid in (18,27)
 AND bio_leadtask.taskcompletedstatus in (1)

 
 AND bio_leadtask.`teamid` in(SELECT `teamid` FROM `bio_leadteams` WHERE `office_id` = $a[$i]) 
 AND bio_leads.enqtypeid =$b[$j] "; //taskcompleteddate = format is date time
 $ab;
$result=DB_query($ab, $db); 
        $myrow1=DB_fetch_row($result);
    $ltc1=$myrow1[0];
 // $results=$ltc1;
    $d[$j][$i]= $ltc1;

}
}


// Status changed
for($i=0;$i<=2;$i++)
{
for($j=0;$j<=1;$j++)
{  
$ab=" SELECT count( distinct bio_leads.`leadid`)
 FROM `bio_leadtask` inner join bio_leads on ( bio_leads.`leadid`=bio_leadtask.leadid )
 WHERE bio_leadtask.taskcompleteddate like '$createdate %' 
AND bio_leadtask.taskid in (18,27)

 AND bio_leadtask.`teamid` in(SELECT `teamid` FROM `bio_leadteams` WHERE `office_id` = $a[$i]) 
 AND bio_leads.enqtypeid =$b[$j] "; 
  $ab;
$result=DB_query($ab, $db); 
        $myrow1=DB_fetch_row($result);
     $ltc1=$myrow1[0];
 // $results=$ltc1;
    $f[$j][$i]= $ltc1;

}
}



//Lead Dropped
for($i=0;$i<=2;$i++)
{
for($j=0;$j<=1;$j++)
{  
$ab=" SELECT count( distinct bio_leads.`leadid`)
 FROM `bio_leadtask` inner join bio_leads on ( bio_leads.`leadid`=bio_leadtask.leadid )
 WHERE bio_leadtask.taskcompleteddate like  '$createdate %' 
AND bio_leadtask.taskid in (18,27)
AND bio_leadtask.taskcompletedstatus =1 
AND bio_leadtask.viewstatus =1
 AND bio_leadtask.`teamid` in(SELECT `teamid` FROM `bio_leadteams` WHERE `office_id` = $a[$i]) 
And  bio_leads.leadstatus =20 AND bio_leads.enqtypeid =$b[$j] "; 
 $ab;
$result=DB_query($ab, $db); 
        $myrow1=DB_fetch_row($result);
    $ltc1=$myrow1[0];
 // $results=$ltc1;
    $e[$j][$i]= $ltc1;

}
}




    
 /*$sql1="SELECT count( distinct bio_leadtask.`leadid`) 
FROM `bio_leadtask`,bio_leads
WHERE `duedate` <= '$createdate'
AND bio_leadtask.taskid =18
AND bio_leadtask.taskcompletedstatus =0
AND bio_leadtask.viewstatus =1
AND bio_leads.leadstatus !=20";

$sql2=$sql1."
AND bio_leads.enqtypeid =1 
AND bio_leadtask.`teamid` in(SELECT  `teamid` FROM `bio_leadteams` WHERE `office_id` = 4) ";
//echo $sql2;
 $result1=DB_query($sql2, $db); 
        $myrow11 = DB_fetch_row($result1);
        $ltc1=$myrow11[0];
//------------------------------------------------------        
  $sql3=$sql1."
 AND bio_leads.enqtypeid =1 
AND bio_leadtask.`teamid` in(SELECT  `teamid` FROM `bio_leadteams` WHERE `office_id` = 2) ";
 $result1=DB_query($sql3, $db); 
        $myrow12 = DB_fetch_row($result1);
        $ltc2=$myrow12[0];
 
 //------------------------------------------------------ 
   $sql4=$sql1."
AND bio_leads.enqtypeid =1 
AND bio_leadtask.`teamid` in(SELECT  `teamid` FROM `bio_leadteams` WHERE `office_id` = 3) ";
 $result1=DB_query($sql4, $db); 
 $myrow13 = DB_fetch_row($result1);
 $ltc3=$myrow13[0];
 //------------------------------------------------------ 
 
 $sql7=$sql1."
AND bio_leads.enqtypeid =2
AND bio_leadtask.`teamid` in(SELECT  `teamid` FROM `bio_leadteams` WHERE `office_id` = 4) ";
 $result1=DB_query($sql7, $db); 
 //echo $sql7;
        $myrow21 = DB_fetch_row($result1);
        $ltc4=$myrow21[0];
  //------------------------------------------------------ 
        
  $sql8=$sql1."
 AND bio_leads.enqtypeid =2 
AND bio_leadtask.`teamid` in(SELECT  `teamid` FROM `bio_leadteams` WHERE `office_id` = 2) ";
 $result1=DB_query($sql8, $db); 
        $myrow22 = DB_fetch_row($result1);
        $ltc5=$myrow22[0];
 //---------------------------------------------------------        
   $sql19=$sql1."
AND bio_leads.enqtypeid =2 
AND bio_leadtask.`teamid` in(SELECT  `teamid` FROM `bio_leadteams` WHERE `office_id` = 3) ";

 $result1=DB_query($sql19, $db); 
 $myrow23 = DB_fetch_row($result1);
 $ltc6=$myrow23[0];
    
//-------------------------------------------------------------      */
  
 
 ?>
 



<?php
echo '<form id="form1" name="form1" method="post" action="' . $_SERVER['PHP_SELF'] . '">';
 ?>
  <fieldset><legend>Date searching</legend><table>
<tr><td><b>Enter date</b></td><td>

<?php
if($_POST['submit'])
{
    echo '<input  style="width:170px" type="text" name="caldate" id="caldate" class=date alt="'.$_SESSION['DefaultDateFormat']. '" value='.$_POST['caldate'].'>'; 
}     else
{
 echo '<input  style="width:170px" type="text" name="caldate" id="caldate" class=date alt="'.$_SESSION['DefaultDateFormat']. '" value='.date("d/m/Y").'>'; }?>
                 </td><td><input type="submit" name="submit"/></td> </table> </fieldset>
 
  <table width="94%" height="416" border="1">
  <tr>
  <td colspan="17"><label><center>
    <h2>Report of leads processed on <?php echo $createdate; ?></h2>
  </center></label></td>
 </tr>
    <tr>
      <th width="17%" rowspan="2" scope="row"><p align="center"><strong>Lead </strong><strong>category</strong></p>      </th>
      <th colspan="4"><strong><center>Lead  to contact</center></strong></th>
      <th colspan="4"><strong><center>Lead  contacted</center></strong></th>
      <th colspan="4"><strong><center>Status  changed</center></strong></th>
      <th colspan="4"><strong><center>Lead  Dropped</center></strong></th>
    </tr>
    <tr>
      <th width="5%" height="25"><label><strong><center>Tvm </center></strong></label></th>
      <th width="5%"><label><strong><center>Ekm</center></strong></label></th>
      <th width="5%"><label><strong><center>Kok</center></strong></label></th>
      <th width="5%"><label><strong><center>Total</center></strong></label></th>
      <th width="5%"><label><strong><center>Tvm</center></strong></label></th>
      <th width="5%"><label><strong><center>Ekm</center></strong></label></th>
      <th width="5%"><label><strong><center>Kok</center></strong></label></th>
      <th width="5%"><label><strong><center>Total</center></strong></label></th>
      <th width="5%"><label><strong><center>Tvm</center></strong></label></th>
      <th width="5%"><label><strong><center>Ekm</center></strong></label></th>
      <th width="5%"><label><strong><center>Kok</center></strong></label></th>
      <th width="6%"><label><strong><center>Total</center></strong></label></th>
      <th width="5%"><label><strong><center>Tvm</center></strong></label></th>
      <th width="5%"><label><strong><center>Ekm</center></strong></label></th>
      <th width="6%"><label><strong><center>Kok</center></strong></label></th>
      <th width="6%"><label><strong><center>Total</center></strong></label></th>
    </tr>
    <tr>
      <th ><strong>Domestic</strong></th>
      <td><div align="center"><strong><?php echo $c[0][0];?></strong></div></td>
      <td><div align="center"><?php echo $c[0][1];?></div></td>
      <td><div align="center"><?php echo $c[0][2];?></div></td>
      <td><div align="center"><?php echo $c[0][0]+$c[0][1]+$c[0][2];?></div></td>
      
      <td><div align="center"><strong><?php echo $d[0][0];?></strong></div></td>
      <td><div align="center"><?php echo $d[0][1];?></div></td>
      <td><div align="center"><?php echo $d[0][2];?></div></td>
      <td><div align="center"><?php echo $d[0][0]+$d[0][1]+$d[0][2];?></div></td>
      
      <td><div align="center"><strong><?php echo $f[0][0];?></strong></div></td>
      <td><div align="center"><?php echo $f[0][1];?></div></td>
      <td><div align="center"><?php echo $f[0][2];?></div></td>
      <td><div align="center"><?php echo $f[0][0]+$f[0][1]+$f[0][2];?></div></td>
      
      <td><div align="center"><strong><?php echo $e[0][0];?></strong></div></td>
      <td><div align="center"><?php echo $e[0][1];?></div></td>
      <td><div align="center"><?php echo $e[0][2];?></div></td>
      <td><div align="center"><?php echo $e[0][0]+$e[0][1]+$e[0][2];?></div></td>
      
    </tr>
    <tr>
      <th ><strong>Institutional</strong></th>
      <td bgcolor="#CC99CC"><div align="center"><strong><?php echo $c[1][0];?></strong></div></td>
      <td bgcolor="#CC99CC"><div align="center"><?php echo $c[1][1];?></div></td>
      <td bgcolor="#CC99CC"><div align="center"><?php echo $c[1][2];?></div></td>
      <td bgcolor="#CC99CC"><div align="center"><?php echo $c[1][0]+$c[1][1]+$c[1][2];?></div></td>
      
            <td bgcolor="#CC99CC"><div align="center"><strong><?php echo $d[1][0];?></strong></div></td>
      <td bgcolor="#CC99CC"><div align="center"><?php echo $d[1][1];?></div></td>
      <td bgcolor="#CC99CC"><div align="center"><?php echo $d[1][2];?></div></td>
      <td bgcolor="#CC99CC"><div align="center"><?php echo $d[1][0]+$d[1][1]+$d[1][2];?></div></td>
      
         <td bgcolor="#CC99CC"><div align="center"><strong><?php echo $f[1][0];?></strong></div></td>
      <td bgcolor="#CC99CC"><div align="center"><?php echo $f[1][1];?></div></td>
      <td bgcolor="#CC99CC"><div align="center"><?php echo $f[1][2];?></div></td>
      <td bgcolor="#CC99CC"><div align="center"><?php echo $f[1][0]+$f[1][1]+$f[1][2];?></div></td>
      
            <td bgcolor="#CC99CC"><div align="center"><strong><?php echo $e[1][0];?></strong></div></td>
      <td bgcolor="#CC99CC"><div align="center"><?php echo $e[1][1];?></div></td>
      <td bgcolor="#CC99CC"><div align="center"><?php echo $e[1][2];?></div></td>
      <td bgcolor="#CC99CC"><div align="center"><?php echo $e[1][0]+$e[1][1]+$e[1][2];?></div></td>
    
    </tr>
    <tr>
      <th ><strong>LSGD</strong></th>
      <td><div align="center"><strong><?php echo $c[2][0];?></strong></div></td>
      <td><div align="center"><?php echo $c[2][1];?></div></td>
      <td><div align="center"><?php echo $c[2][2];?></div></td>
      <td><div align="center"><?php echo $c[2][0]+$c[2][1]+$c[2][2];?></div></td>
      
      <td><div align="center"><strong><?php echo $d[2][0];?></strong></div></td>
      <td><div align="center"><?php echo $d[2][1];?></div></td>
      <td><div align="center"><?php echo $d[2][2];?></div></td>
      <td><div align="center"><?php echo $d[2][0]+$d[2][1]+$d[2][2];?></div></td>
      
      <td><div align="center"><strong><?php echo $f[2][0];?></strong></div></td>
      <td><div align="center"><?php echo $f[2][1];?></div></td>
      <td><div align="center"><?php echo $f[2][2];?></div></td>
      <td><div align="center"><?php echo $f[2][0]+$f[2][1]+$f[2][2];?></div></td>
      
       <td><div align="center"><strong><?php echo $e[2][0];?></strong></div></td>
      <td><div align="center"><?php echo $e[2][1];?></div></td>
      <td><div align="center"><?php echo $e[2][2];?></div></td>
      <td><div align="center"><?php echo $e[2][0]+$e[2][1]+$e[2][2];?></div></td>
    </tr>
    <tr>
      <th><strong>Dealer</strong></th>
      <td bgcolor="#CC99CC"><div align="center"></div></td>
      <td bgcolor="#CC99CC"><div align="center"></div></td>
      <td bgcolor="#CC99CC"><div align="center"></div></td>
      <td bgcolor="#CC99CC"><div align="center"></div></td>
      <td bgcolor="#CC99CC"><div align="center"></div></td>
      <td bgcolor="#CC99CC"><div align="center"></div></td>
      <td bgcolor="#CC99CC"><div align="center"></div></td>
      <td bgcolor="#CC99CC"><div align="center"></div></td>
      <td bgcolor="#CC99CC"><div align="center"></div></td>
      <td bgcolor="#CC99CC"><div align="center"></div></td>
      <td bgcolor="#CC99CC"><div align="center"></div></td>
      <td bgcolor="#CC99CC"><div align="center"></div></td>
      <td bgcolor="#CC99CC"><div align="center"></div></td>
      <td bgcolor="#CC99CC"><div align="center"></div></td>
      <td bgcolor="#CC99CC"><div align="center"></div></td>
      <td bgcolor="#CC99CC"><div align="center"></div></td>
    </tr>
    <tr>
      <th ><strong>Distributor</strong></th>
      <td><div align="center"></div></td>
      <td><div align="center"></div></td>
      <td><div align="center"></div></td>
      <td><div align="center"></div></td>
      <td><div align="center"></div></td>
      <td><div align="center"></div></td>
      <td><div align="center"></div></td>
      <td><div align="center"></div></td>
      <td><div align="center"></div></td>
      <td><div align="center"></div></td>
      <td><div align="center"></div></td>
      <td><div align="center"></div></td>
      <td><div align="center"></div></td>
      <td><div align="center"></div></td>
      <td><div align="center"></div></td>
      <td><div align="center"></div></td>
    </tr>
    <tr>
      <th ><strong>JV</strong></th>
      <td bgcolor="#CC99CC"><div align="center"></div></td>
      <td bgcolor="#CC99CC"><div align="center"></div></td>
      <td bgcolor="#CC99CC"><div align="center"></div></td>
      <td bgcolor="#CC99CC"><div align="center"></div></td>
      <td bgcolor="#CC99CC"><div align="center"></div></td>
      <td bgcolor="#CC99CC"><div align="center"></div></td>
      <td bgcolor="#CC99CC"><div align="center"></div></td>
      <td bgcolor="#CC99CC"><div align="center"></div></td>
      <td bgcolor="#CC99CC"><div align="center"></div></td>
      <td bgcolor="#CC99CC"><div align="center"></div></td>
      <td bgcolor="#CC99CC"><div align="center"></div></td>
      <td bgcolor="#CC99CC"><div align="center"></div></td>
      <td bgcolor="#CC99CC"><div align="center"></div></td>
      <td bgcolor="#CC99CC"><div align="center"></div></td>
      <td bgcolor="#CC99CC"><div align="center"></div></td>
      <td bgcolor="#CC99CC"><div align="center"></div></td>
    </tr>
    <tr>
      <th ><strong>Others</strong></th>
      <td><div align="center"><strong></strong></div></td>
      <td><div align="center"></div></td>
      <td><div align="center"></div></td>
      <td><div align="center"></div></td>
      <td><div align="center"></div></td>
      <td><div align="center"></div></td>
      <td><div align="center"></div></td>
      <td><div align="center"></div></td>
      <td><div align="center"></div></td>
      <td><div align="center"></div></td>
      <td><div align="center"></div></td>
      <td><div align="center"></div></td>
      <td><div align="center"></div></td>
      <td><div align="center"></div></td>
      <td><div align="center"></div></td>
      <td><div align="center"></div></td>
    </tr>
  </table>

  <label></label>
</form>


