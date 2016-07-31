<?php
    $PageSecurity = 80;
include('includes/session.inc');
$title = _('Call Status Document List');  
include('includes/header.inc');

echo '<center><font style="color: #333;
    background:#fff;
    font-weight:bold;
    letter-spacing:0.10em;
    font-size:16px;
    font-family:Georgia;
    text-shadow: 1px 1px 1px #666;">Call Status Document List</font></center>';
    
    
 
 
    echo"<fieldset style='width:90%;'>";
    echo"<legend>Call Status</legend>";
      
   echo "<form action=" . $_SERVER['PHP_SELF'] . "?" . SID ." method=POST>";   
    
    echo"<table style='border:1px solid #F0F0F0;width:80%'>"; 
 
     

echo"<tr><td>Installed From</td><td>Installed To</td><td>Register Type</td><td>Office</td></tr>";
echo"<tr>"; 
echo "<td><input type=text id='createdfrm' class=date alt=".$_SESSION['DefaultDateFormat']." name='createdfrm' value='$_POST[createdfrm]' )'></td>";
echo "<td><input type=text id='createdto' class=date alt=".$_SESSION['DefaultDateFormat']." name='createdto' value='$_POST[createdto]' )'></td>";

   // echo "<td><input style=width:150px type='text' name='name' id='name' style='width:100px'></td>";  
//    echo "<td><input style=width:150px type='text' name='contno' id='contno' style='width:100px'></td>"; 



/*     if((isset($_POST['regtype'])) AND ($_POST['regtype']!=0))
 {
 echo'<td><select name=regtype>';
     if($_POST['regtype']==1){
        echo'<option value=1>Consolidated</option>';
        echo'<option value=2>Detailed</option>';
     }elseif($_POST['regtype']==2){  
        echo'<option value=2>Detailed</option>';
        echo'<option value=1>Consolidated</option>';  
     }
 echo'</select></td>';     
 }else{ */    
 
// } 
echo'<td><select name="regtype" id="regtype" onchange=consolidated(this.value);>
              <option value=0></option>               
              <option value=2>Detailed</option>
              <option value=1>Consolidated</option>
      </select></td>';


    
echo'<td><select name="office" id="office" style="width:150px">';   
echo'<option value=0></option>';
    $sql="SELECT * FROM bio_office";
    $result=DB_query($sql,$db);
    
    while($row=DB_fetch_array($result))
    {
        if ($row['id']==$_POST['office'] )
        {
            echo '<option selected value="';
        } else {
            echo '<option value="';
        }
        echo $row['id'] . '">'.$row['office'];
        echo '</option>';
    }
    echo'</select></td>';   
  
    echo "</tr>";   
  
  
    

echo"<td>Country<select name='country' id='country' onchange='showstate(this.value)' style='width:130px'>";
$sql="SELECT * FROM bio_country ORDER BY cid";
$result=DB_query($sql,$db);
 $f=0;
  while($myrow1=DB_fetch_array($result))
  {  
  if ($myrow1['cid']==1)  
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
    echo $myrow1['cid'] . '">'.$myrow1['country'];
    echo '</option>';
    $f++;
   }    
  echo '</select></td>'; 
  
  echo '<td id="showstate">State<select name="state" id="state" onchange="showdistrict(this.value)" style="width:130px">';
  $sql="SELECT * FROM bio_state ORDER BY stateid";
  $result=DB_query($sql,$db);
  $f=0;      
  
  while($myrow1=DB_fetch_array($result))
  {
  if ($myrow1['stateid']==14)
    {
    echo '<option selected value="';
    } else
    {
    if ($f==0)
        {
        echo '<option>';
        }
        echo '<option value="';
    }
    echo $myrow1['stateid'] . '">'.$myrow1['state'];
    echo '</option>';
    $f++;
   }
  echo '</select>';
  echo'</td>'; 
  
 echo '<td id="showdistrict">District<select name="district" id="district" style="width:130px" onchange="showtaluk(this.value);">'; 
  $sql="SELECT * FROM bio_district WHERE stateid=14 ORDER BY did";
    $result=DB_query($sql,$db);
    $f=0;
  while($myrow1=DB_fetch_array($result))
  {
  if ($myrow1['did']==$_POST['district'])
    {
    echo '<option selected value="';
    } else
    {
    if ($f==0)
        {
        echo '<option>';
        }
        echo '<option value="';
    }
    echo $myrow1['did'] . '">'.$myrow1['district'];
    echo '</option>';
    $f++;
   } 
  echo '</select>';
  echo'</td>';
    

     // echo '<td id=showtaluk>Taluk<select name="taluk" id="taluk" style="width:130px" tabindex=11 onchange="showVillage(this.value)">';
//      $sql_taluk="SELECT * FROM bio_taluk ORDER BY bio_taluk.taluk ASC";
//      $result_taluk=DB_query($sql_taluk,$db);
//      $f=0;
//      while($myrow7=DB_fetch_array($result_taluk))
//      {
//      if ($myrow7['id']==$_POST['taluk'])
//      {
//      echo '<option selected value="';
//      } else
//      {
//      if ($f==0)
//        {
//        echo '<option>';
//        }
//        echo '<option value="';
//      }
//      echo $myrow7['id'] . '">'.$myrow7['taluk'];
//      echo '</option>';
//      $f++;
//      }
//      echo '</select>';
//      echo '</td>';
//          
//echo"<td id=showvillage>Village<select name='village' id='village' style='width:130px'>";      
//   $sql_taluk="SELECT * FROM bio_village ORDER BY bio_village.village ASC";
//      $result_taluk=DB_query($sql_taluk,$db);
//      $f=0;
//      while($myrow7=DB_fetch_array($result_taluk))
//      {
//      if ($myrow7['id']==$_POST['village'])
//      {
//      echo '<option selected value="';
//      } else
//      {
//      if ($f==0)
//        {
//        echo '<option>';
//        }
//        echo '<option value="';
//        }
//      echo $myrow7['id'] . '">'.$myrow7['village'];
//      echo '</option>';
//      $f++;
//      }
//      echo '</select>';
//      echo'</td>';
      
    echo '<td>LSG Type<select name="lsgType" id="lsgType" style="width:130px" onchange="showblock(this.value)">';
    echo '<option value=0></option>'; 
    echo '<option value=1>Corporation</option>';
    echo '<option value=2>Muncipality</option>';
    echo '<option value=3>Panchayat</option>';           
    echo '</select></td>'; 
  
        echo '<td align=left colspan=2>';
        echo'<div style="align:right" id=block>';             
        echo'</div>';
        echo'</td>';
      
        echo'<td><div id=showpanchayath></div></td>';

  
  
  

    echo '<td align=right><input type=submit name=filter class=filter value=Search onclick="checkregtype()"></td>';

//echo"</tr>";
echo"</table>";

        
echo"</table>";
echo"</form>"; 
echo"<br />";   

echo"</fieldset>"; 
//$sql_doc="select  ";
//grid for all show start
//echo '<div class="all_grid" align="center" style="width:90%;height:500px;overflow:scroll;margin-left:5%" >';
//echo "<table style='border:1 solid #F0F0F0;'>";

//$sql_selall="SELECT *  FROM bio_installation_status" ;  
//$result_allsel=DB_query($sql_selall,$db);
//$i=1;
// $crrdate=date('Y-m-d');
// while($result_tb=DB_fetch_array($result_allsel)) 
// {
// $sql_cal="SELECT callno  FROM bio_calllog WHERE orderno=".$result_tb['orderno'];  
//$result_cal=DB_query($sql_cal,$db); 

//$sql_name="SELECT deliverto  FROM salesorders WHERE orderno=".$result_tb['orderno'];  
//$result_name=DB_query($sql_name,$db); 
// 
//while($result_cal_name=DB_fetch_array($result_name)){
//     echo "<tr>";


//  echo '<td style=width:3%>'.$i.'</td>';
//  echo '<td style=width:3%>'.$result_cal_name["deliverto"].'</td>';
//  echo '<td style=width:3%>'.$result_tb["installed_date"].'</td>';
// while($result_cal_log=DB_fetch_array($result_cal)){ 
//  if($result_cal_log['callno']==1){$callno="First Call";echo '<td style=width:3%>'.$callno.'</td>';}
//  elseif($result_cal_log['callno']==2){$callno="Second Call";echo '<td style=width:3%>'.$callno.'</td>';}
//  elseif($result_cal_log['callno']==3){$callno="Third Call";echo '<td style=width:3%>'.$callno.'</td>';} 
// 
//  }  $i++; 
//   echo "</tr>" ;
//   

//}
// }
//echo "</table>";

//echo "</div>";
//grid for all show end    

//grid for flter start

   $sql_doc="SELECT `bio_installation_status`.`orderno` AS orderno, 
                    `bio_installation_status`.`installed_date` AS orderdate, 
                    `debtorsmaster`.`debtorno`, 
                    `debtorsmaster`.`name`, 
                    `debtorsmaster`.`clientsince`, 
                    `custbranch`.`phoneno`, 
                    `custbranch`.`faxno`, 
                    `bio_district`.`district`, 
                    `bio_panchayat`.`name` AS panchayath, 
                    `bio_corporation`.`corporation`, 
                    `bio_municipality`.`municipality`, 
                    `custbranch`.`LSG_type`
               FROM `bio_installation_status`     
         INNER JOIN `salesorders` 
                ON (`salesorders`.`orderno` = `bio_installation_status`.`orderno`)
          INNER JOIN debtorsmaster 
                ON (`salesorders`.`debtorno` = `debtorsmaster`.`debtorno`)
         INNER JOIN `custbranch` 
                ON (`debtorsmaster`.`debtorno` = `custbranch`.`debtorno`)
          LEFT JOIN `bio_district` 
                ON (`custbranch`.`did` = `bio_district`.`did`) 
               AND (`bio_district`.`cid` = `custbranch`.`cid`) 
               AND (`custbranch`.`stateid` = `bio_district`.`stateid`)
          LEFT JOIN `bio_panchayat` 
                ON (`custbranch`.`did` = `bio_panchayat`.`district`) 
               AND (`custbranch`.`block_name` = `bio_panchayat`.`id`) 
               AND (`custbranch`.`stateid` = `bio_panchayat`.`state`) 
               AND (`custbranch`.`LSG_name` = `bio_panchayat`.`block`) 
               AND (`custbranch`.`cid` = `bio_panchayat`.`country`)
          LEFT JOIN `bio_corporation` 
                ON (`bio_corporation`.`country` = `custbranch`.`cid`) 
               AND (`bio_corporation`.`state` = `custbranch`.`stateid`) 
               AND (`bio_corporation`.`district` = `custbranch`.`LSG_name`) 
               AND (`bio_corporation`.`district` = `custbranch`.`did`)
          LEFT JOIN `bio_municipality` 
                ON (`bio_municipality`.`country` = `custbranch`.`cid`) 
               AND (`bio_municipality`.`state` = `custbranch`.`stateid`) 
               AND (`bio_municipality`.`district` = `custbranch`.`did`) 
               AND (`bio_municipality`.`id` = `custbranch`.`LSG_name`) 
               WHERE bio_installation_status.close_status!=2"; 
        //UNION 
// SELECT `bio_installation_status`.`orderno` AS orderno

//    , `bio_installation_status`.`installed_date` AS orderdate
//    , `debtorsmaster`.`debtorno`
//    , `debtorsmaster`.`name`
//    , `debtorsmaster`.`clientsince`
//    , `custbranch`.`phoneno`
//    , `custbranch`.`faxno`
//    , `bio_district`.`district`
//    , `bio_panchayat`.`name` AS panchayath
//    , `bio_corporation`.`corporation`
//    , `bio_municipality`.`municipality`
//    , `debtorsmaster`.`LSG_type`
//FROM
//    `bio_installation_status`     
//    INNER JOIN `bio_oldorders` 
//        ON (`bio_oldorders`.`orderno` = `bio_installation_status`.`orderno`)
//    INNER JOIN debtorsmaster 
//        ON (`bio_oldorders`.`debtorno` = `debtorsmaster`.`debtorno`)

//    INNER JOIN `custbranch` 
//        ON (`debtorsmaster`.`debtorno` = `custbranch`.`debtorno`)
//    LEFT JOIN `bio_district` 
//        ON (`debtorsmaster`.`did` = `bio_district`.`did`) AND (`bio_district`.`cid` = `debtorsmaster`.`cid`) AND (`debtorsmaster`.`stateid` = `bio_district`.`stateid`)
//    LEFT JOIN `bio_panchayat` 
//        ON (`debtorsmaster`.`did` = `bio_panchayat`.`district`) AND (`debtorsmaster`.`block_name` = `bio_panchayat`.`id`) AND (`debtorsmaster`.`stateid` = `bio_panchayat`.`state`) AND (`debtorsmaster`.`LSG_name` = `bio_panchayat`.`block`) AND (`debtorsmaster`.`cid` = `bio_panchayat`.`country`)
//    LEFT JOIN `bio_corporation` 
//        ON (`bio_corporation`.`country` = `debtorsmaster`.`cid`) AND (`bio_corporation`.`state` = `debtorsmaster`.`stateid`) AND (`bio_corporation`.`district` = `debtorsmaster`.`LSG_name`) AND (`bio_corporation`.`district` = `debtorsmaster`.`did`)
//    LEFT JOIN `bio_municipality` 
//        ON (`bio_municipality`.`country` = `debtorsmaster`.`cid`) AND (`bio_municipality`.`state` = `debtorsmaster`.`stateid`) AND (`bio_municipality`.`district` = `debtorsmaster`.`did`) AND (`bio_municipality`.`id` = `debtorsmaster`.`LSG_name`) 

//consoldated report start 

if($_POST['regtype']==1)
{
    echo '<div class="filter_grid" align="center" style="width:90%;height:90%;overflow:scroll;margin-left:5%" >';
$tilte="Call status Consolidated report";
$call1_pending="SELECT count(*) from bio_installation_status 
where orderno IN (SELECT a.orderno FROM `bio_installation_status` a 
INNER JOIN salesorders c on c.orderno =a.orderno
INNER JOIN custbranch d on  d.debtorno=c.debtorno 
INNER JOIN debtorsmaster  on  d.debtorno=debtorsmaster.debtorno
WHERE actual_date1='0000-00-00' and `due_date1` < now()";
$call1_done="SELECT count(*) from bio_installation_status 
where orderno IN (SELECT a.orderno FROM `bio_installation_status` a  
INNER JOIN salesorders c on c.orderno =a.orderno 
INNER JOIN debtorsmaster 
        ON (c.`debtorno` = `debtorsmaster`.`debtorno`) 
INNER JOIN custbranch d on  d.debtorno=c.debtorno 
WHERE actual_date1!='0000-00-00' and `due_date1` < now()";
$call2_pending="SELECT count(*) from bio_installation_status 
where orderno IN (SELECT a.orderno FROM `bio_installation_status` a  
  
INNER JOIN salesorders c on c.orderno =a.orderno 
INNER JOIN custbranch d on  d.debtorno=c.debtorno 
INNER JOIN debtorsmaster  on  d.debtorno=debtorsmaster.debtorno
WHERE actual_date2='0000-00-00' and `due_date2` < now()";
$call2_done="SELECT count(*) from bio_installation_status 
where orderno IN (SELECT a.orderno FROM `bio_installation_status` a  
  
INNER JOIN salesorders c on c.orderno =a.orderno 
INNER JOIN custbranch d on  d.debtorno=c.debtorno 
INNER JOIN debtorsmaster  on  d.debtorno=debtorsmaster.debtorno
WHERE actual_date2!='0000-00-00' and `due_date2` < now()";
$call3_pending="SELECT count(*) from bio_installation_status 
where orderno IN (SELECT a.orderno FROM `bio_installation_status` a  
  
INNER JOIN salesorders c on c.orderno =a.orderno 
INNER JOIN custbranch d on  d.debtorno=c.debtorno 
INNER JOIN debtorsmaster  on  d.debtorno=debtorsmaster.debtorno
WHERE actual_date3='0000-00-00' and `due_date3` < now()";
$call3_done="SELECT count(*) from bio_installation_status 
where orderno IN (SELECT a.orderno FROM `bio_installation_status` a  
  
INNER JOIN salesorders c on c.orderno =a.orderno 
INNER JOIN custbranch d on  d.debtorno=c.debtorno 
INNER JOIN debtorsmaster  on  d.debtorno=debtorsmaster.debtorno
WHERE actual_date3!='0000-00-00' and `due_date3` < now()";
if(isset($_POST['filter']))
           { 
               
if($_POST['createdfrm']!=null && $_POST['createdto']!=null){
    
    $call1_pending.= " AND bio_installation_status.installed_date BETWEEN '".FormatDateForSQL($_POST['createdfrm'])."' AND '".FormatDateForSQL($_POST['createdto'])."'";
   $call1_done.= " AND bio_installation_status.installed_date BETWEEN '".FormatDateForSQL($_POST['createdfrm'])."' AND '".FormatDateForSQL($_POST['createdto'])."'"; 
    $call2_pending.= " AND bio_installation_status.installed_date BETWEEN '".FormatDateForSQL($_POST['createdfrm'])."' AND '".FormatDateForSQL($_POST['createdto'])."'";
   $call2_done.= " AND bio_installation_status.installed_date BETWEEN '".FormatDateForSQL($_POST['createdfrm'])."' AND '".FormatDateForSQL($_POST['createdto'])."'";
    $call3_pending.= " AND bio_installation_status.installed_date BETWEEN '".FormatDateForSQL($_POST['createdfrm'])."' AND '".FormatDateForSQL($_POST['createdto'])."'";
   $call3_done.= " AND bio_installation_status.installed_date BETWEEN '".FormatDateForSQL($_POST['createdfrm'])."' AND '".FormatDateForSQL($_POST['createdto'])."'";
     $tilte.=' from '.$_POST['createdfrm'].' to '.$_POST['createdto'];
}
      if (isset($_POST['country']))    {
     if($_POST['country']!=0)   {
       $call1_pending.=" AND d.cid=".$_POST['country'];      
 $call1_done.=" AND d.cid=".$_POST['country'];      
    $call2_pending.=" AND d.cid=".$_POST['country'];      
   $call2_done.=" AND d.cid=".$_POST['country'];      
    $call3_pending.=" AND d.cid=".$_POST['country'];      
   $call3_done.=" AND d.cid=".$_POST['country'];       
     }
     }
                                                                                
    if (isset($_POST['state']))    {
     if($_POST['State']!=0)   {
         
     $call1_pending.=" AND d.stateid=".$_POST['state'];
    $call1_done.=" AND d.stateid=".$_POST['state']; 
    $call2_pending.=" AND d.stateid=".$_POST['state'];
    $call2_done.=" AND d.stateid=".$_POST['state'];
    $call3_pending.=" AND d.stateid=".$_POST['state'];
    $call3_done.=" AND d.stateid=".$_POST['state'];     
     }
     }
              if (isset($_POST['district']))    {
     if($_POST['district']!=0)   {
         $call1_pending.=" AND d.did=".$_POST['district'];     
         $call1_done.=" AND d.did=".$_POST['district'];     
         $call2_pending.=" AND d.did=".$_POST['district'];     
         $call2_done.=" AND d.did=".$_POST['district'];     
         $call3_pending.=" AND d.did=".$_POST['district'];     
         $call3_done.=" AND d.did=".$_POST['district'];     
     
     $sql_distr="SELECT district FROM bio_district WHERE did=".$_POST['district']." AND stateid=".$_POST['state'];
      $result_distr=DB_query($sql_distr,$db);  
     $result_grdall=DB_fetch_array($result_distr); 
      $tilte.=' of District '.$result_grdall['district'];
     if (isset($_POST['lsgType']))    {
     if($_POST['lsgType']!=0)   {
       if($_POST['lsgType']!=NULL)   {           
         $call1_pending.=" AND d.LSG_type=".$_POST['lsgType'];     
         $call1_done.=" AND d.LSG_type=".$_POST['lsgType'];
         $call2_pending.=" AND d.LSG_type=".$_POST['lsgType'];
         $call2_done.=" AND d.LSG_type=".$_POST['lsgType'];     
         $call3_pending.=" AND d.LSG_type=".$_POST['lsgType'];     
         $call3_done.=" AND d.LSG_type=".$_POST['lsgType'];  
         $lsg=$_POST['lsgType'];
         if($lsg==1){
             $sql_cor="SELECT * FROM bio_corporation WHERE country=".$_POST['country']." AND state=".$_POST['state']." AND district=".$_POST['district'];
             $result_cor=DB_query($sql_cor,$db);
             $result_corporation=DB_fetch_array($result_cor);
          $tilte.=' and  Corporation '.$result_corporation['corporation'];    
         }elseif($lsg==2){
            $tilte.=' and  muncipality'; 
         } elseif($lsg==3){
           $tilte.=' and  panchayath';   
         }
     if (isset($_POST['lsgName']))    {
     if($_POST['lsgName']==1 OR $_POST['lsgName']==2)   {
          $sql_doc .=" AND debtorsmaster.LSG_name=".$_POST['lsgName']; 
     $sql_blockname="SELECT * FROM bio_municipality WHERE country=".$_POST['country']." AND state=".$_POST['state']." AND district=".$_POST['district']." AND id=".$_POST['lsgName'];
     $result_block=DB_query($sql_blockname,$db);
     $result_blockvalue=DB_fetch_array($result_block);
 $tilte.= $result_blockvalue['municipality'];
         $call1_pending.=" AND d.LSG_name=".$_POST['lsgName'];     
         $call1_done.=" AND d.LSG_name=".$_POST['lsgName'];
         $call2_pending.=" AND d.LSG_name=".$_POST['lsgName'];
         $call2_done.=" AND d.LSG_name=".$_POST['lsgName'];
         $call3_pending.=" AND d.LSG_name=".$_POST['lsgName'];
         $call3_done.=" AND d.LSG_name=".$_POST['lsgName'];   
        }
    
       elseif($_POST['lsgName']==3){
         $call1_pending.=" AND d.LSG_name=".$_POST['lsgName'];     
         $call1_done.=" AND d.LSG_name=".$_POST['lsgName'];
         $call2_pending.=" AND d.LSG_name=".$_POST['lsgName'];
         $call2_done.=" AND d.LSG_name=".$_POST['lsgName'];
         $call3_pending.=" AND d.LSG_name=".$_POST['lsgName'];
         $call3_done.=" AND d.LSG_name=".$_POST['lsgName'];
           } 
              
       }
       
       if (isset($_POST['gramaPanchayath']))    {  
      if($_POST['gramaPanchayath']!=0 OR $_POST['gramaPanchayath']!=NULL)   {
         $call1_pending.=" AND d.block_name=".$_POST['gramaPanchayath'];
         $call1_done.=" AND d.block_name=".$_POST['gramaPanchayath'];
         $call2_pending.=" AND d.block_name=".$_POST['gramaPanchayath'];
         $call2_done.=" AND d.block_name=".$_POST['gramaPanchayath'];
         $call3_pending.=" AND d.block_name=".$_POST['gramaPanchayath'];
         $call3_done.=" AND d.block_name=".$_POST['gramaPanchayath'];
         $sql_blockname="SELECT * FROM bio_panchayat WHERE country=".$_POST['country']." AND state=".$_POST['state']." AND district=".$_POST['district']."  AND id=".$_POST['gramaPanchayath'];
     $result_block=DB_query($sql_blockname,$db);
     $result_blockvalue=DB_fetch_array($result_block);
 $tilte.= "\t".$result_blockvalue['name'];
    }       
     }
     }
     }
     }   
     }
     
    if ( $_POST['office']!=0)
   {  
   if($_POST['country']==1 AND $_POST['state']==14)  
   {
   if ($_POST['office']==1){                                   
       $call1_pending .=" AND debtorsmaster.did IN (6,11,12)"; 
       $call1_done .=" AND debtorsmaster.did IN (6,11,12)";
       $call2_pending .=" AND debtorsmaster.did IN (6,11,12)";
       $call2_done .=" AND debtorsmaster.did IN (6,11,12)"; 
       $call3_pending .=" AND debtorsmaster.did IN (6,11,12)";
       $call3_done .=" AND debtorsmaster.did IN (6,11,12)";
   }else if ($_POST['office']==2){                                   
             $call1_pending .=" AND debtorsmaster.did IN (1,2,3,7,13)";  
             $call1_done .=" AND debtorsmaster.did IN (1,2,3,7,13)"; 
             $call2_pending .=" AND debtorsmaster.did IN (1,2,3,7,13)";  
             $call2_done .=" AND debtorsmaster.did IN (1,2,3,7,13)"; 
             $call3_pending .=" AND debtorsmaster.did IN (1,2,3,7,13)";  
             $call3_done .=" AND debtorsmaster.did IN (1,2,3,7,13)";                                           
   }else if ($_POST['office']==3){ 
             $call1_pending .=" AND debtorsmaster.did IN (4,5,8,9,10,14)"; 
             $call1_done .=" AND debtorsmaster.did IN (4,5,8,9,10,14)";
             $call2_pending .=" AND debtorsmaster.did IN (4,5,8,9,10,14)"; 
             $call2_done .=" AND debtorsmaster.did IN (4,5,8,9,10,14)";
             $call3_pending .=" AND debtorsmaster.did IN (4,5,8,9,10,14)"; 
             $call3_done .=" AND debtorsmaster.did IN (4,5,8,9,10,14)";                
   }else if ($_POST['office']==4){ 
             $call1_pending .=" AND debtorsmaster.did IN (6,11,12)"; 
             $call1_done .=" AND debtorsmaster.did IN (6,11,12)";
             $call2_pending .=" AND debtorsmaster.did IN (6,11,12)"; 
             $call2_done .=" AND debtorsmaster.did IN (6,11,12)";                      
             $call3_pending .=" AND debtorsmaster.did IN (6,11,12)"; 
             $call3_done .=" AND debtorsmaster.did IN (6,11,12)";                                            
         }      
       }
     } 
     
     
     
   // if (isset($_POST['taluk']))    {
//     if($_POST['taluk']!=0 OR $_POST['taluk']!=NULL)   {
//         $sql_taluk="SELECT taluk FROM bio_taluk WHERE country=".$_POST['country']." AND district=".$_POST['district']." AND state=".$_POST['state'];
//         $result_taluk=DB_query($sql_taluk,$db);  
//     $result_tal=DB_fetch_array($result_taluk);
//     $tilte.=' and  Taluk '.$result_tal['taluk'];      
//         $call1_pending.=" AND d.taluk=".$_POST['taluk'];
//         $call1_done.=" AND d.taluk=".$_POST['taluk'];
//         $call2_pending.=" AND d.taluk=".$_POST['taluk'];
//         $call2_done.=" AND d.taluk=".$_POST['taluk'];
//         $call3_pending.=" AND d.taluk=".$_POST['taluk'];
//         $call3_done.=" AND d.taluk=".$_POST['taluk'];
//        }
//     } 
     //if (isset($_POST['village']))    {
//     if($_POST['village']!='' OR $_POST['village']!=NULL)   {
//         
//          $call1_pending.="  AND d.village LIKE '%".$_POST['village']."%'";
//         $call1_done.="  AND d.village LIKE '%".$_POST['village']."%'";
//         $call2_pending.="  AND d.village LIKE '%".$_POST['village']."%'";
//         $call2_done.="  AND d.village LIKE '%".$_POST['village']."%'";
//         $call3_pending.="  AND d.village LIKE '%".$_POST['village']."%'";
//         $call3_done.="  AND d.village LIKE '%".$_POST['village']."%'";
//         
//  }
//     }
     
          
     }                                                       
   
         $call1_pending.=")";
         $call1_done.=")";
         $call2_pending.=")";
         $call2_done.=")";
         $call3_pending.=")";
         $call3_done.=")";
        $call1_pendingSQL=DB_query($call1_pending,$db);  
        $result_call1pending=DB_fetch_array($call1_pendingSQL);
        $call1_doneSQL=DB_query($call1_done,$db);  
        $result_call1done=DB_fetch_array($call1_doneSQL); 
        $call1_total=$result_call1done[0]+$result_call1pending[0];
        
        $call2_pendingSQL=DB_query($call2_pending,$db);  
        $result_call2pending=DB_fetch_array($call2_pendingSQL);
        $call2_doneSQL=DB_query($call2_done,$db);  
        $result_call2done=DB_fetch_array($call2_doneSQL); 
        $call2_total=$result_call2done[0]+$result_call2pending[0];
        
        $call3_pendingSQL=DB_query($call3_pending,$db);  
        $result_call3pending=DB_fetch_array($call3_pendingSQL);
        $call3_doneSQL=DB_query($call3_done,$db);  
        $result_call3done=DB_fetch_array($call3_doneSQL); 
        $call3_total=$result_call3done[0]+$result_call3pending[0];
           }
           
echo "<table  border='1px'  style='border:1 solid #F0F0F0;width:90%' ;' cellpadding='4%'>";
echo "<thead><font size='+1' color='#333333'> ".$tilte."</font></thead>"; 
echo "<tr >";
echo "<td style=width:3%>Calls</td><td style=width:3%>Done</td><td style=width:3%>pending</td><td style=width:3%>Total</td>";  
echo "</tr>";
echo "<tr><td>Call 1<td>".$result_call1done[0]."<td>".$result_call1pending[0]."</td><td>".$call1_total."</td></tr>";     echo "<tr><td>Call 2<td>".$result_call2done[0]."<td>".$result_call2pending[0]."</td><td>".$call2_total."</td></tr>";
echo "<tr><td>Call 3<td>".$result_call3done[0]."<td>".$result_call3pending[0]."</td><td>".$call3_total."</td></tr>";
 $done_alltotal=$result_call1done[0]+$result_call2done[0]+$result_call3done[0];
 $pending_alltotal=$result_call1pending[0]+$result_call2pending[0]+$result_call3pending[0];
 $total=$done_alltotal+$pending_alltotal;
echo "<tr><td>total<td>".$done_alltotal."<td>".$pending_alltotal."</td><td>".$total."</td></tr>";     
echo "</table>";
echo '</div>';
    
}       
//consoldated report end
if($_POST['regtype']==2 || $_POST['regtype']==0){          
echo '<div class="filter_grid" align="center" style="width:90%;height:90%;overflow:scroll;margin-left:5%" >';
function title($a) 
{
echo "<font size='-1' style='margin-left:1%'><b>:Total Count(".$a.")<b/></font>"; 
} 
$tilte="Call status Detailed report";
if(isset($_POST['filter']))
           { 
              
echo "<table  border='1px'  style='border:1 solid #F0F0F0;width:90%' ;' cellpadding='4%'>";

if($_POST['createdfrm']!=null && $_POST['createdto']!=null){
    $sql_doc.= " AND bio_installation_status.installed_date BETWEEN '".FormatDateForSQL($_POST['createdfrm'])."' AND '".FormatDateForSQL($_POST['createdto'])."'";
  $tilte.=' from '.$_POST['createdfrm'].' to '.$_POST['createdto']; 
}
//if($_POST['name']!="")
//                 {
//                     $sql_doc .= " AND debtorsmaster.name LIKE '".$_POST['name']."%'";
                 //$tilte.=' of '.$_POST['name'];     
//                 }
//if($_POST['contno']!="")
//                 {
//                     $sql_doc.= " AND custbranch.faxno LIKE '".$_POST['contno']."%'"; 
                // $tilte.=' and phone number is '.$_POST['contno'];    
//                 }
                 if (isset($_POST['country']))    {
     if($_POST['country']!=0)   {
     $sql_doc .=" AND debtorsmaster.cid=".$_POST['country'];      
     }
     }
                                                                                
    if (isset($_POST['state']))    {
     if($_POST['State']!=0)   {
     $sql_doc .=" AND debtorsmaster.stateid=".$_POST['state'];      
     }
     }
              if (isset($_POST['district']))    {
     if($_POST['district']!=0)   {
     $sql_doc .=" AND debtorsmaster.did=".$_POST['district'];     
     $sql_distr="SELECT district FROM bio_district WHERE did=".$_POST['district']." AND stateid=".$_POST['state'];
      $result_distr=DB_query($sql_distr,$db);  
     $result_grdall=DB_fetch_array($result_distr); 
      $tilte.=' of District '.$result_grdall['district'];
     if (isset($_POST['lsgType']))    {
     if($_POST['lsgType']!=0)   {
       if($_POST['lsgType']!=NULL)   {  
         
     $sql_doc .=" AND debtorsmaster.LSG_type=".$_POST['lsgType'];    
     $lsg=$_POST['lsgType'];
         if($lsg==1){
             $sql_cor="SELECT * FROM bio_corporation WHERE country=".$_POST['country']." AND state=".$_POST['state']." AND district=".$_POST['district'];
             $result_cor=DB_query($sql_cor,$db);
             $result_corporation=DB_fetch_array($result_cor);
          $tilte.=' and  Corporation '.$result_corporation['corporation'];    
             
         }elseif($lsg==2){
            $tilte.=' and  muncipality'; 
         } elseif($lsg==3){
           $tilte.=' and  panchayath';   
         }
     if (isset($_POST['lsgName']))    {
     if($_POST['lsgName']==1 OR $_POST['lsgName']==2)   {
     $sql_doc .=" AND debtorsmaster.LSG_name=".$_POST['lsgName']; 
     $sql_blockname="SELECT * FROM bio_municipality where country=".$_POST['country']." AND state=".$_POST['state']." AND district=".$_POST['district']." AND id=".$_POST['lsgName'];
     $result_block=DB_query($sql_blockname,$db);
     $result_blockvalue=DB_fetch_array($result_block);
 $tilte.= $result_blockvalue['municipality'];
     }
    
       elseif($_POST['lsgName']==3){
       $sql_doc .=" AND debtorsmaster.LSG_name=".$_POST['lsgName'];   
       
       } 
              
       }
       
       if (isset($_POST['gramaPanchayath']))    {  
      if($_POST['gramaPanchayath']!=0 OR $_POST['gramaPanchayath']!=NULL)   {
$sql_doc .=" AND debtorsmaster.block_name=".$_POST['gramaPanchayath'];   
      $sql_blockname="SELECT * FROM bio_panchayat WHERE country=".$_POST['country']." AND state=".$_POST['state']." AND district=".$_POST['district']."  AND id=".$_POST['gramaPanchayath'];
     $result_block=DB_query($sql_blockname,$db);
     $result_blockvalue=DB_fetch_array($result_block);
 $tilte.= "\t".$result_blockvalue['name'];
      
      }       
     }
     }
     }
     }   
     }
     
     
     if ( $_POST['office']!=0)
   {  
   if($_POST['country']==1 AND $_POST['state']==14)  
   {
   if ($_POST['office']==1){                                   
       $sql_doc .=" AND debtorsmaster.did IN (6,11,12)";  
   }else if ($_POST['office']==2){                                   
             $sql_doc .=" AND debtorsmaster.did IN (1,2,3,7,13)";                
   }else if ($_POST['office']==3){ 
             $sql_doc .=" AND debtorsmaster.did IN (4,5,8,9,10,14)";                 
   }else if ($_POST['office']==4){ 
             $sql_doc .=" AND debtorsmaster.did IN (6,11,12)";                       
         }      
       }
     } 
     
     
     
   // if (isset($_POST['taluk']))    {
//     if($_POST['taluk']!=0 OR $_POST['taluk']!=NULL)   {
//     $sql_doc .=" AND debtorsmaster.taluk=".$_POST['taluk'];  
//     $sql_taluk="SELECT taluk FROM bio_taluk WHERE country=".$_POST['country']." AND district=".$_POST['district']." AND state=".$_POST['state'];
//         $result_taluk=DB_query($sql_taluk,$db);  
//     $result_tal=DB_fetch_array($result_taluk);
//     $tilte.=' and  Taluk '.$result_tal['taluk'];
//     }
//     } 
     //if (isset($_POST['village']))    {
//     if($_POST['village']!='' OR $_POST['village']!=NULL)   {
//$sql_doc .="  AND debtorsmaster.village LIKE '%".$_POST['village']."%'";  }
//     }
//     
          
     }
          //$sql_doc.=" ORDER BY orderno";
          $result_grd=DB_query($sql_doc,$db);  
          //echo "<thead>".$tilte."</thead>";                   
           }else{
               
  echo "<table  border=1px    style='border:1 solid #F0F0F0;width:90%' ;' cellpadding='4%'>"; 
  //$sql_doc.=" ORDER BY orderno";  
  $result_grd=DB_query($sql_doc,$db);          
           }
echo "<thead><font size='+1' color='#333333'> ".$tilte."</font></thead>"; 
echo "<tr >";
echo "<td style=width:3%>Sl No</td><td style=width:3%>Name</td><td style=width:3%>District</td><td style=width:3%>LSG</td><td style=width:3%>Phone no</td><td style=width:3%>Call_1</td><td style=width:3%>Call_2</td><td style=width:3%>Call_3</td>";   

echo "</tr>";$i=1;
$slno;
while($result_grdall=DB_fetch_array($result_grd)) { 
     // if ($k==1)
//          {
//            echo '<tr class="EvenTableRows">';
//            $k=0;
//          }else 
//          {
//            echo '<tr class="OddTableRows">';
//            $k=1;     
//          }
    if($result_grdall['LSG_type']==1){
         $LSG_name=$result_grdall['corporation']."(C)";
     }elseif($result_grdall['LSG_type']==2){
         $LSG_name=$result_grdall['municipality']."(M)";
     }elseif($result_grdall['LSG_type']==3){         
         $LSG_name=$result_grdall['panchayath']."(P)";
         }
     elseif($result_grdall['LSG_type']==0){
         $LSG_name="";
     }         
echo "<tr/>";
echo "<td>".$i."</td>";
echo "<td>".$result_grdall['name']."</td>";
echo "<td>".$result_grdall['district']."</td>";
echo "<td>".$LSG_name."</td>";  
echo "<td>".$result_grdall['phoneno']."\n".$result_grdall['faxno']."</td>"; 
$sql_cal="SELECT actual_date1,actual_date2,actual_date3  FROM bio_installation_status WHERE orderno=".$result_grdall['orderno'];  
$result_cal=DB_query($sql_cal,$db); 
$p=0;
while($result_cal_log=DB_fetch_array($result_cal)){    
   if($result_cal_log['actual_date1']!='0000-00-00')
   {
   $p=1;
   }
   if($result_cal_log['actual_date2']!='0000-00-00')
   {
       $p=2;  
   }
   if($result_cal_log['actual_date3']!='0000-00-00')
   {
  $p=3;  
   }    
}
if($p==0){
    echo '<td ><font color="#FF0000">No</font></td>';
    echo '<td ><font color="#000000">NA</font></td>';
    echo '<td ><font color="#000000">NA</font></td>';     
}
if($p==1){
   echo '<td ><font color="#0000FF">Yes</font></td>';
   echo '<td ><font color="#FF0000">No</font></td>';
   echo '<td ><font color="#000000">NA</font></td>'; 
}
if($p==2){
   echo '<td ><font color="#0000FF">Yes</font></td>';
   echo '<td ><font color="#0000FF">Yes</font></td>';
   echo '<td ><font color="#FF0000">No</font></td>'; 
}
if($p==3){
   echo '<td ><font color="#0000FF">Yes</font></td>';
   echo '<td ><font color="#0000FF">Yes</font></td>';
   echo '<td ><font color="#0000FF">Yes</font></td>'; 
}

echo "</tr>"; 
$i++;  
$slno++;  
}
title($slno);
echo "</table>";
echo "</div>";
}
//grid for flter end

          
?>


<script type="text/javascript">  
//$(".filter_grid").hide(); 
// $('.filter').click(function() { 
// $(".all_grid").hide();    
// $('.filter_grid').slideToggle('slow', function() { 
// $(".filter_grid").show();
    // Animation complete.
//  });
//});       

function viewdocuments(str)
{      
//return false;                        // alert(str);
    var split = str.split('-');  
    var split1=split[0];             
    var split2=split[1]; 
    var split3=split[2];
    
    if(split1==1)
    {      
       controlWindow=window.open("bio_OLDdocumentmanagement.php?orderno="+split2+"&enq="+split3,"docdetails","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=1200,height=600");     
   // return false;
    }
    else if(split1==2)
    {     
       controlWindow=window.open("bio_documentmanagement.php?orderno="+split2,"plantdetails","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=1200,height=600") 
  //  return false;
    }            
}
 function viewcustomer(str4)
 {
     controlWindow=window.open("Customers.php?DebtorNo="+str4,"customer","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=1200,height=600");
 }

function consolidated(str)
{
    
    if(str==1) {
    $(".conso").hide();  
    }else{
    $(".conso").show();     
    }    
}


function showdocs(str1){   

//alert(str1);       



if (str1=="")
  {
  document.getElementById("showdocument").innerHTML="";
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
if(document.getElementById('regtype').value!=1)
{
         document.getElementById("showdocument").innerHTML=xmlhttp.responseText;      }
    }
  } 
xmlhttp.open("GET","bio_docCustSelection.php?enqid=" + str1,true);
xmlhttp.send(); 
} 




function showstate(str){
   // alert(str); 

if (str=="")
  {
  document.getElementById("showstate").innerHTML="";
  return;
  }
//show_progressbar('showstate');

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
    {                 //  alert(str); 
    document.getElementById("showstate").innerHTML=xmlhttp.responseText;

    }
  }
xmlhttp.open("GET","bio_lsgFilter.php?country=" + str,true);
xmlhttp.send();
}



function showdistrict(str){       
//    alert(str);
str1=document.getElementById("country").value;
//alert(str1);
if (str=="")
  {
  document.getElementById("showdistrict").innerHTML="";
  return;
  }
//show_progressbar('showdistrict');
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
    document.getElementById("showdistrict").innerHTML=xmlhttp.responseText;

    }
  }
xmlhttp.open("GET","bio_lsgFilter.php?state=" + str + "&country1=" + str1,true);
xmlhttp.send();

}


function showtaluk(str){                                  
     str1=document.getElementById("country").value;       
     str2=document.getElementById('state').value;         
//     str3=document.getElementById('district').value;      
 if (str=="")
  {           
   alert("Please select the district");   
  document.getElementById('district').focus(); 
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
     document.getElementById("showtaluk").innerHTML=xmlhttp.responseText; 
       
    }
  } 
xmlhttp.open("GET","bio_lsgFilter.php?taluk=" + str +"&country1="+ str1 +"&state1="+ str2  ,true);
xmlhttp.send(); 
}



 function showblock(str){   
     str1=document.getElementById("country").value;
     str2=document.getElementById('state').value;
     str3=document.getElementById('district').value;
     if(str==1 && (str3==1 || str3==3 || str3==4 || str3==5 || str3==7 || str3==9 || str3==10 || str3==10 || str3==14)){
         alert("No Corporation for this district");
         document.getElementById("block").innerHTML="";
         return;
     }
//alert(str1);   alert(str2);       alert(str3);
 if (str3=="")
  {           
   alert("Please select the district");   
  document.getElementById('district').focus(); 
  return;
  }

if (str=="")
  {
  document.getElementById("block").innerHTML="";
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
     document.getElementById("block").innerHTML=xmlhttp.responseText; 
       
    }
  } 
xmlhttp.open("GET","bio_lsgFilter.php?block=" + str +"&country1="+ str1 +"&state1="+ str2 +"&district="+ str3,true);
xmlhttp.send(); 
} 



   function showVillage(str){   
    str1=document.getElementById("country").value;
     str2=document.getElementById('state').value;
     str3=document.getElementById('district').value;
      //alert(str);  
 if (str=="")
  {  
      alert("Please select the district");   
  document.getElementById('district').focus(); 
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
     document.getElementById("showvillage").innerHTML=xmlhttp.responseText; 
       
    }
  } 
xmlhttp.open("GET","bio_lsgFilter.php?village=" + str + "&country1="+ str1 +"&state1="+ str2 +"&district="+ str3,true);
xmlhttp.send(); 
}






 function showgramapanchayath(str){   
    str1=document.getElementById("country").value;
     str2=document.getElementById('state').value;
     str3=document.getElementById('district').value;
     // alert(str2);  
 if (str=="")
  {  
      alert("Please select the district");   
  document.getElementById('district').focus(); 
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
     document.getElementById("showpanchayath").innerHTML=xmlhttp.responseText; 
       
    }
  } 
xmlhttp.open("GET","bio_lsgFilter.php?grama=" + str + "&country1="+ str1 +"&state2="+ str2 +"&district="+ str3,true);
xmlhttp.send(); 
}


function checkregtype()
{
 var str=document.getElementById("regtype").value; 
  if(str==0){   
  alert("please select register type"); 
  document.getElementById('regtype').focus(); 
  //return;
  }
}


</script>
