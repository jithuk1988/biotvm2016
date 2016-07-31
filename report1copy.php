<?php
 $PageSecurity = 80;
include('includes/session.inc');
$title = _('Leads Report');  
include('includes/header.inc');
  if($_POST['submit'])
  {
     $create=$_POST['caldate'];
     $createdate=FormatDateForSQL($create);
  
  }    else
  {
      $createdate=date("Y-m-d"); 
  }
  $a=array("1","2","3","8","15","7","14");  
 $b= array("4","2","3"); 
 $c=array("Domestic","Institutional","LSGD","Dealer","Distributor","JV","Others");
 
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
    <span class="style5">Report of leads and orders registered <?php echo $createdate; ?></span>
  </center></label></td>
 </tr> 
    <tr>
      <th width="17%" rowspan="2" scope="row"><p align="center"><strong>Lead </strong><strong>category</strong></p>      </th>
      <th colspan="5"><strong><center>
        <strong>Existing  Lead </strong>
      </center></strong></th>
      <th colspan="5"><strong><center>
        <strong>Lead  registered</strong>
      </center></strong></th>
      <th colspan="5"><strong><center>
        <strong>Cumulative  Lead</strong>
      </center></strong></th>
      <th colspan="5"><strong><center>
        <strong>Order  registered</strong>
      </center></strong></th>
    </tr>
    <tr>
      <th width="5%" height="25"><label><strong><center>Tvm </center></strong></label></th>
      <th width="5%"><label><strong><center>Ekm</center></strong></label></th>
      <th width="5%"><label><strong><center>Kok</center></strong></label></th>
         <th width="5%"><label><strong><center>Others</center></strong></label></th>
      <th width="5%"><label><strong><center>Total</center></strong></label></th>
      <th width="5%"><label><strong><center>Tvm</center></strong></label></th>
      <th width="5%"><label><strong><center>Ekm</center></strong></label></th>
      <th width="5%"><label><strong><center>Kok</center></strong></label></th>
       <th width="5%"><label><strong><center>Others</center></strong></label></th>
      <th width="5%"><label><strong><center>Total</center></strong></label></th>
      <th width="5%"><label><strong><center>Tvm</center></strong></label></th>
      <th width="5%"><label><strong><center>Ekm</center></strong></label></th>
      <th width="5%"><label><strong><center>Kok</center></strong></label></th>
       <th width="5%"><label><strong><center>Others</center></strong></label></th>
      <th width="6%"><label><strong><center>Total</center></strong></label></th>
      <th width="5%"><label><strong><center>Tvm</center></strong></label></th>
      <th width="5%"><label><strong><center>Ekm</center></strong></label></th>
      <th width="6%"><label><strong><center>Kok</center></strong></label></th>
       <th width="5%"><label><strong><center>Others</center></strong></label></th>
      <th width="6%"><label><strong><center>Total</center></strong></label></th>
    </tr>
    <?php
            $p=1;
    for($j=0;$j<7;$j++)
    {
          $sumex=0;
          $sumcr=0;
          $sumother=0;
          $sumord=0;
          $totalsum=0;
              if ($p==1)
             {
               echo '<tr class="EvenTableRows">';
                $p=0;
             }else 
              {
               echo '<tr bgcolor="#CC99CC">'; //
               $p=1;     
               }
        echo "<td>".$c[$j]."</td>";
        for($i=0;$i<3;$i++)
        {
            $sql="SELECT count( DISTINCT `bio_leads`.`leadid` ) AS count
            FROM `bio_leads`

            WHERE  `bio_leads`.`leadstatus` NOT IN ( 20, 21, 22, 23, 24 ) 
            AND bio_leads.enqtypeid =".$a[$j]." AND `bio_leads`.`created_by`
            IN (SELECT `userid` FROM `www_users` WHERE `empid`  in (SELECT `empid` FROM `bio_emp` WHERE `offid` =".$b[$i]."))
                AND `bio_leads`.`leaddate`!='".$createdate."'";
                    $result=DB_query($sql,$db);
                    $myrow=DB_fetch_array($result);
                    $count= $myrow['count'];
                    $k[$i]= $count;
                    if($count==0)
                    {
                        $count=='';
                    }
                    echo "<td>".$count."</td>";
                     $sumex=$sumex+$count;
        }
        $sql_other="SELECT count( DISTINCT `bio_leads`.`leadid` ) AS count
        FROM `bio_leads`

        WHERE  `bio_leads`.`leadstatus` NOT IN ( 20, 21, 22, 23, 24 ) 
        AND bio_leads.enqtypeid =".$a[$j]." AND `bio_leads`.`created_by`
        IN  (SELECT `userid` FROM `www_users` WHERE `empid`  in (SELECT `empid` FROM `bio_emp` WHERE `offid` not in (4,2,3)))
        AND `bio_leads`.`leaddate`!='".$createdate."'";
        $result_other=DB_query($sql_other,$db);
        $myrow_other=DB_fetch_array($result_other);
        $other=$myrow_other[0];
        $sumex= $sumex+$other;  
        if($other==0)
        {
           $other=''; 
        }
         echo "<td>".$other."</td>";
         if($sumex==0)
         {
             $sumex='';
         }
        echo "<td>".$sumex."</td>";
        
             for($i=0;$i<3;$i++)
        {
            $sql1="SELECT count( DISTINCT `bio_leads`.`leadid` ) AS count
            FROM `bio_leads`

            WHERE  `bio_leads`.`leadstatus` NOT IN ( 20, 21, 22, 23, 24 ) 
            AND bio_leads.enqtypeid =".$a[$j]." AND `bio_leads`.`created_by`
            IN  (SELECT `userid` FROM `www_users` WHERE `empid`  in (SELECT `empid` FROM `bio_emp` WHERE `offid` =".$b[$i]."))
            AND `bio_leads`.`leaddate`='".$createdate."'";
            $result1=DB_query($sql1,$db);
            $myrow1=DB_fetch_array($result1);
             $count2= $myrow1['count'];
             $q[$i]= $count2;
             if($count2==0)
             {
                 $count2='';
             }
            echo "<td>".$count2."</td>";
            $sumcr=$sumcr+$count2;
        }
        $sql_other2="SELECT count( DISTINCT `bio_leads`.`leadid` ) AS count
        FROM `bio_leads`

        WHERE  `bio_leads`.`leadstatus` NOT IN ( 20, 21, 22, 23, 24 ) 
        AND bio_leads.enqtypeid =".$a[$j]." AND `bio_leads`.`created_by`
        IN (SELECT `empid` FROM `bio_emp` WHERE `offid` not in (4,2,3))
        AND `bio_leads`.`leaddate`='".$createdate."'";
        $result_other2=DB_query($sql_other2,$db);
        $myrow_other2=DB_fetch_array($result_other2);
        $other2=$myrow_other2[0];
        $sumcr= $sumcr+$other2; 
             if($other2==0)
             {
                 $other2='';
             }
          echo "<td>".$other2."</td>";
            if($sumcr==0)
             {
                 $sumcr='';
             }
          
        echo "<td>".$sumcr."</td>"; 
             for($i=0;$i<3;$i++)
        {     
          $sum=0;
          $sum=$k[$i]+$q[$i];
          if($sum==0)
             {
                 $sum='';
             }
          
          echo "<td>$sum</td>";
          $totalsum= $totalsum+$sum;
        }
        $sumother= $other2+ $other;
          $totalsum=$totalsum+ $sumother;
           if($sumother==0)
             {
                 $sumother='';
             }
        echo "<td>$sumother</td>";
          if($totalsum==0)
             {
                 $totalsum='';
             }
          echo "<td>$totalsum</td>";
            for($i=0;$i<3;$i++)
        {
         $sqlord="SELECT count( DISTINCT `bio_leadtask`.`leadid` ) AS count
         FROM `bio_leadtask`
         INNER JOIN `bio_leads` ON `bio_leads`.`leadid` = `bio_leadtask`.`leadid`
         INNER JOIN `salesorders` ON  `salesorders`.`leadid`= `bio_leads`.`leadid` 
         WHERE `bio_leadtask`.`taskcompletedstatus` =1
         AND `bio_leadtask`.`taskid` = 5 AND  `salesorders`.`orddate` ='".$createdate."' 
         AND bio_leads.enqtypeid =".$a[$j]."  AND `bio_leads`.`created_by`
            IN  (SELECT `userid` FROM `www_users` WHERE `empid`  in (SELECT `empid` FROM `bio_emp` WHERE `offid` =".$b[$i]."))";
             $result_ord=DB_query($sqlord,$db);
        $myrow_ord=DB_fetch_array($result_ord);   
        $order= $myrow_ord[0];
              if($order==0)
             {
                 $order='';
             }
        echo "<td>".$order."</td>";
       $sumord=$sumord+$order;
        }
        $sql_ord2="SELECT count( DISTINCT `bio_leadtask`.`leadid` ) AS count
         FROM `bio_leadtask`
         INNER JOIN `bio_leads` ON `bio_leads`.`leadid` = `bio_leadtask`.`leadid`
         INNER JOIN `salesorders` ON  `salesorders`.`leadid`= `bio_leads`.`leadid` 
         WHERE `bio_leadtask`.`taskcompletedstatus` =1
         AND `bio_leadtask`.`taskid` = 5 AND  `salesorders`.`orddate` ='".$createdate."' 
         AND bio_leads.enqtypeid =".$a[$j]."  AND `bio_leads`.`created_by`
            IN (SELECT `empid` FROM `bio_emp` WHERE `offid` not in (4,2,3))";
               $result_ord2=DB_query($sql_ord2,$db);
        $myrow_ord2=DB_fetch_array($result_ord2);    
        $sumord=$sumord+ $myrow_ord2[0];
                    if($myrow_ord2[0]==0)
             {
                 $myrow_ord2[0]='';
             }
        echo "<td>".$myrow_ord2[0]."</td>";
                       if($sumord==0)
             {
                 $sumord='';
             }
         echo "<td>".$sumord."</td></tr>";
         
       
  
    }
        
    ?>
