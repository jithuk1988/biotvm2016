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
$ab="SELECT count( distinct bio_leads.`leadid`)as contact ,bio_office.id,bio_leads.enqtypeid 
FROM `bio_leadtask` 
inner join bio_leads on ( bio_leads.`leadid`=bio_leadtask.leadid ) 
inner join bio_leadteams on (bio_leadteams.teamid=bio_leadtask.`teamid`) 
inner join bio_office on (bio_leadteams.office_id=bio_office.id  )
WHERE bio_leadtask.duedate <= '$createdate ' 
AND bio_leadtask.taskid in (18,27)
AND bio_leadtask.taskcompletedstatus =0 
AND bio_leadtask.viewstatus =1 And bio_leads.leadstatus !=20 
AND bio_office.id in(2,3,4)
 group by  bio_leads.enqtypeid , bio_office.id  "; // AND bio_leadtask.taskid =18 

$result=DB_query($ab, $db); 
     while(   $myrow1=DB_fetch_row($result))
     {
    $ltc1=$myrow1[0];
        $enqtypeid=$myrow1[2];
     $office=$myrow1[1];
$c[$enqtypeid][$office]= $ltc1;
     }



//////Lead contacted
 //"AND dateCreated BETWEEN '"&fromDate&"' AND '"&toDate&"' "
 $ab="SELECT count( distinct bio_leads.`leadid`)as contact ,bio_office.id,bio_leads.enqtypeid 
 FROM `bio_leadtask`
inner join bio_leads on ( bio_leads.`leadid`=bio_leadtask.leadid ) 
inner join bio_leadteams on (bio_leadteams.teamid=bio_leadtask.`teamid`) 
inner join bio_office on (bio_leadteams.office_id=bio_office.id  )
 WHERE bio_leadtask.taskcompleteddate like '$createdate %'
  AND bio_leadtask.taskid in (18,27)
 AND bio_leadtask.taskcompletedstatus in (1)
AND bio_office.id in(2,3,4)
 group by  bio_leads.enqtypeid , bio_office.id   "; //taskcompleteddate = format is date time

$result=DB_query($ab, $db); 
     while(   $myrow1=DB_fetch_row($result))
     {
    $ltc1=$myrow1[0];
        $enqtypeid=$myrow1[2];
     $office=$myrow1[1];
$d[$enqtypeid][$office]= $ltc1;
     }




// Status changed
 
$ab="SELECT count( distinct bio_leads.`leadid`)as contact ,bio_office.id,bio_leads.enqtypeid 
 FROM `bio_leadtask`
 inner join bio_leads on ( bio_leads.`leadid`=bio_leadtask.leadid ) 
inner join bio_leadteams on (bio_leadteams.teamid=bio_leadtask.`teamid`) 
inner join bio_office on (bio_leadteams.office_id=bio_office.id  )
 WHERE bio_leadtask.taskcompleteddate like '$createdate %' 
AND bio_leadtask.taskid in (18,27)
AND bio_office.id in(2,3,4)
group by  bio_leads.enqtypeid , bio_office.id "; 
$result=DB_query($ab, $db); 
     while(   $myrow1=DB_fetch_row($result))
     {
    $ltc1=$myrow1[0];
        $enqtypeid=$myrow1[2];
     $office=$myrow1[1];
$f[$enqtypeid][$office]= $ltc1;
     }






//Lead Dropped

$ab=" SELECT count( distinct bio_leads.`leadid`)as contact ,bio_office.id,bio_leads.enqtypeid 
 FROM `bio_leadtask`
inner join bio_leads on ( bio_leads.`leadid`=bio_leadtask.leadid ) 
inner join bio_leadteams on (bio_leadteams.teamid=bio_leadtask.`teamid`) 
inner join bio_office on (bio_leadteams.office_id=bio_office.id  )
 WHERE bio_leadtask.taskcompleteddate like  '$createdate %' 
AND bio_leadtask.taskid in (18,27)
AND bio_leadtask.taskcompletedstatus =1 
AND bio_leadtask.viewstatus =1
And  bio_leads.leadstatus =20
AND bio_office.id in(2,3,4)
group by  bio_leads.enqtypeid , bio_office.id "; 

$result=DB_query($ab, $db); 
    while($myrow1=DB_fetch_row($result))
     {
    $ltc1=$myrow1[0];
        $enqtypeid=$myrow1[2];
     $office=$myrow1[1];
$e[$enqtypeid][$office]= $ltc1;
     }




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
      <td><div align="center"><strong><?php echo $c[1][4];?></strong></div></td>
      <td><div align="center"><?php echo $c[1][2];?></div></td>
      <td><div align="center"><?php echo $c[1][3];?></div></td>
      <td><div align="center"><?php echo $c[1][4]+$c[1][2]+$c[1][3];?></div></td>
      
      <td><div align="center"><strong><?php echo $d[1][4];?></strong></div></td>
      <td><div align="center"><?php echo $d[1][2];?></div></td>
      <td><div align="center"><?php echo $d[1][3];?></div></td>
      <td><div align="center"><?php echo $d[1][4]+$d[1][2]+$d[1][3];?></div></td>
      
 
      
      <td><div align="center"><strong><?php echo $f[1][4];?></strong></div></td>
      <td><div align="center"><?php echo $f[1][2];?></div></td>
      <td><div align="center"><?php echo $f[1][3];?></div></td>
      <td><div align="center"><?php echo $f[1][4]+$f[1][2]+$f[1][3];?></div></td>
      
<td><div align="center"><strong><?php echo $e[1][4];?></strong></div></td>
      <td><div align="center"><?php echo $e[1][2];?></div></td>
      <td><div align="center"><?php echo $e[1][3];?></div></td>
      <td><div align="center"><?php echo $e[1][4]+$e[1][2]+$e[1][3];?></div></td>
    </tr>
    <tr>
      <th ><strong>Institutional</strong></th>
      <td bgcolor="#CC99CC"><div align="center"><strong><?php echo $c[2][4];?></strong></div></td>
      <td bgcolor="#CC99CC"><div align="center"><?php echo $c[2][2];?></div></td>
      <td bgcolor="#CC99CC"><div align="center"><?php echo $c[2][3];?></div></td>
      <td bgcolor="#CC99CC"><div align="center"><?php echo $c[2][4]+$c[2][2]+$c[2][3];?></div></td>
      
          <td bgcolor="#CC99CC"><div align="center"><strong><?php echo $d[2][4];?></strong></div></td>
      <td bgcolor="#CC99CC"><div align="center"><?php echo $d[2][2];?></div></td>
      <td bgcolor="#CC99CC"><div align="center"><?php echo $d[2][3];?></div></td>
      <td bgcolor="#CC99CC"><div align="center"><?php echo $d[2][4]+$d[2][2]+$d[2][3];?></div></td>
      
         <td bgcolor="#CC99CC"><div align="center"><strong><?php echo $f[2][4];?></strong></div></td>
      <td bgcolor="#CC99CC"><div align="center"><?php echo $f[2][2];?></div></td>
      <td bgcolor="#CC99CC"><div align="center"><?php echo $f[2][3];?></div></td>
      <td bgcolor="#CC99CC"><div align="center"><?php echo $f[2][4]+$f[2][2]+$f[2][3];?></div></td>
      
          <td bgcolor="#CC99CC"><div align="center"><strong><?php echo $e[2][4];?></strong></div></td>
      <td bgcolor="#CC99CC"><div align="center"><?php echo $e[2][2];?></div></td>
      <td bgcolor="#CC99CC"><div align="center"><?php echo $e[2][3];?></div></td>
      <td bgcolor="#CC99CC"><div align="center"><?php echo $e[2][4]+$e[2][2]+$e[2][3];?></div></td>
    </tr>
    <tr>
      <th ><strong>LSGD</strong></th>
      <td><div align="center"><strong><?php echo $c[3][4];?></strong></div></td>
      <td><div align="center"><?php echo $c[3][2];?></div></td>
      <td><div align="center"><?php echo $c[3][3];?></div></td>
      <td><div align="center"><?php echo $c[3][4]+$c[3][2]+$c[3][3];?></div></td>
      
        <td><div align="center"><strong><?php echo $d[3][4];?></strong></div></td>
      <td><div align="center"><?php echo $d[3][2];?></div></td>
      <td><div align="center"><?php echo $d[3][3];?></div></td>
      <td><div align="center"><?php echo $d[3][4]+$d[3][2]+$d[3][3];?></div></td>
      
      <td><div align="center"><strong><?php echo $f[3][4];?></strong></div></td>
      <td><div align="center"><?php echo $f[3][2];?></div></td>
      <td><div align="center"><?php echo $f[3][3];?></div></td>
      <td><div align="center"><?php echo $f[3][4]+$f[3][2]+$f[3][3];?></div></td>
      
      <td><div align="center"><strong><?php echo $e[3][4];?></strong></div></td>
      <td><div align="center"><?php echo $e[3][2];?></div></td>
      <td><div align="center"><?php echo $e[3][3];?></div></td>
      <td><div align="center"><?php echo $e[3][4]+$e[3][2]+$e[3][3];?></div></td>
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


