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
  
  
 $sql="SELECT count( DISTINCT `bio_leads`.`leadid` ) AS count
FROM `bio_leads`

WHERE  `bio_leads`.`leadstatus` NOT IN ( 20, 21, 22, 23, 24 )"; 

 //--------------------------------------------    
 //-------------------domestic-----------------  
 $sql1=$sql."AND bio_leads.enqtypeid =1 AND `bio_leads`.`teamid`
IN (SELECT `teamid`
FROM `bio_leadteams`
WHERE `office_id` =4
)
AND `bio_leads`.`leaddate`!='".$createdate."'
";
    $result1=DB_query($sql1,$db);
    $row1=DB_fetch_array($result1);
    $ex1=$row1['0'];    
//-----------------------------------------------
   $sql2=$sql."AND bio_leads.enqtypeid =1 AND `bio_leads`.`teamid`
IN (SELECT `teamid`
FROM `bio_leadteams`
WHERE `office_id` =2
)
AND `bio_leads`.`leaddate`!='".$createdate."'
";
    $result2=DB_query($sql2,$db);
    $row2=DB_fetch_array($result2);
    $ex2=$row2['0'];   
 //----------------------------------------------   
       $sql3=$sql."AND bio_leads.enqtypeid =1 AND `bio_leads`.`teamid`
IN (SELECT `teamid`
FROM `bio_leadteams`
WHERE `office_id` =3
)
AND `bio_leads`.`leaddate`!='".$createdate."'
";
    $result3=DB_query($sql3,$db);
    $row3=DB_fetch_array($result3);
    $ex3=$row3['0'];
 //----------------------------------------------                        

   $sql4=$sql."AND bio_leads.enqtypeid =1 AND `bio_leads`.`teamid`
IN (SELECT `teamid`
FROM `bio_leadteams`
WHERE `office_id` =4
)
AND `bio_leads`.`leaddate`='".$createdate."'
";
    $result4=DB_query($sql4,$db);
    $row4=DB_fetch_array($result4);
    $ex4=$row4['0'];  
 //----------------------------------------------
   $sql5=$sql."AND bio_leads.enqtypeid =1 AND `bio_leads`.`teamid`
IN (SELECT `teamid`
FROM `bio_leadteams`
WHERE `office_id` =2
)
AND `bio_leads`.`leaddate`='".$createdate."'
";
    $result5=DB_query($sql5,$db);
    $row5=DB_fetch_array($result5);
    $ex5=$row5['0'];  
  //---------------------------------------------- 
   $sql6=$sql."AND bio_leads.enqtypeid =1 AND `bio_leads`.`teamid`
IN (SELECT `teamid`
FROM `bio_leadteams`
WHERE `office_id` =3
)
AND `bio_leads`.`leaddate`='".$createdate."'
";
    $result6=DB_query($sql6,$db);
    $row6=DB_fetch_array($result6);
    $ex6=$row6['0'];  
   //----------------------------------------------  
   //-------------------institution-------------------
      $sql10=$sql."AND bio_leads.enqtypeid =2 AND `bio_leads`.`teamid`
IN (SELECT `teamid`
FROM `bio_leadteams`
WHERE `office_id` =4
)
AND `bio_leads`.`leaddate`!='".$createdate."'
";
    $result10=DB_query($sql10,$db);
    $row10=DB_fetch_array($result10);
    $ex10=$row10['0'];  
   //----------------------------------------------      
        $sql11=$sql."AND bio_leads.enqtypeid =2 AND `bio_leads`.`teamid`
IN (SELECT `teamid`
FROM `bio_leadteams`
WHERE `office_id` =2
)
AND `bio_leads`.`leaddate`!='".$createdate."'
";
    $result11=DB_query($sql11,$db);
    $row11=DB_fetch_array($result11);
    $ex11=$row11['0'];  
   //----------------------------------------------      
        $sql12=$sql."AND bio_leads.enqtypeid =2 AND `bio_leads`.`teamid`
IN (SELECT `teamid`
FROM `bio_leadteams`
WHERE `office_id` =3
)
AND `bio_leads`.`leaddate`!='".$createdate."'
";
    $result12=DB_query($sql12,$db);
    $row12=DB_fetch_array($result12);
    $ex12=$row12['0'];  
   //----------------------------------------------    
        $sql13=$sql."AND bio_leads.enqtypeid =2 AND `bio_leads`.`teamid`
IN (SELECT `teamid`
FROM `bio_leadteams`
WHERE `office_id` =4
)
AND `bio_leads`.`leaddate`='".$createdate."'
";
    $result13=DB_query($sql13,$db);
    $row13=DB_fetch_array($result13);
    $ex13=$row13['0'];  
   //----------------------------------------------   
        $sql14=$sql."AND bio_leads.enqtypeid =2 AND `bio_leads`.`teamid`
IN (SELECT `teamid`
FROM `bio_leadteams`
WHERE `office_id` =2
)
AND `bio_leads`.`leaddate`='".$createdate."'
";
    $result14=DB_query($sql14,$db);
    $row14=DB_fetch_array($result14);
    $ex14=$row14['0'];  
   //----------------------------------------------    
        $sql_15=$sql."AND bio_leads.enqtypeid =2 AND `bio_leads`.`teamid`
IN (SELECT `teamid`
FROM `bio_leadteams`
WHERE `office_id` =3
)
AND `bio_leads`.`leaddate`='".$createdate."'
";
    $result15=DB_query($sql_15,$db);
    $row15=DB_fetch_array($result15);
    $ex15=$row15['0'];  
   //----------------------------------------------    
 //--------------------LSGD----------------------------  
      $sql16=$sql."AND bio_leads.enqtypeid =3 AND `bio_leads`.`teamid`
IN (SELECT `teamid`
FROM `bio_leadteams`
WHERE `office_id` =4
)
AND `bio_leads`.`leaddate`!='".$createdate."'
";
    $result16=DB_query($sql16,$db);
    $row16=DB_fetch_array($result16);
    $ex16=$row16['0'];  
   //----------------------------------------------      
        $sql17=$sql."AND bio_leads.enqtypeid =3 AND `bio_leads`.`teamid`
IN (SELECT `teamid`
FROM `bio_leadteams`
WHERE `office_id` =2
)
AND `bio_leads`.`leaddate`!='".$createdate."'
";
    $result17=DB_query($sql17,$db);
    $row17=DB_fetch_array($result17);
    $ex17=$row17['0'];  
   //----------------------------------------------      
        $sql18=$sql."AND bio_leads.enqtypeid =3 AND `bio_leads`.`teamid`
IN (SELECT `teamid`
FROM `bio_leadteams`
WHERE `office_id` =3
)
AND `bio_leads`.`leaddate`!='".$createdate."'
";
    $result18=DB_query($sql18,$db);
    $row18=DB_fetch_array($result18);
    $ex18=$row18['0'];  
   //----------------------------------------------    
        $sql19=$sql."AND bio_leads.enqtypeid =3 AND `bio_leads`.`teamid`
IN (SELECT `teamid`
FROM `bio_leadteams`
WHERE `office_id` =4
)
AND `bio_leads`.`leaddate`='".$createdate."'
";
    $result19=DB_query($sql19,$db);
    $row19=DB_fetch_array($result19);
    $ex19=$row19['0'];  
   //----------------------------------------------   
        $sql20=$sql."AND bio_leads.enqtypeid =3 AND `bio_leads`.`teamid`
IN (SELECT `teamid`
FROM `bio_leadteams`
WHERE `office_id` =2
)
AND `bio_leads`.`leaddate`='".$createdate."'
";
    $result20=DB_query($sql20,$db);
    $row20=DB_fetch_array($result20);
    $ex20=$row20['0'];  
   //----------------------------------------------    
        $sql21=$sql."AND bio_leads.enqtypeid =3 AND `bio_leads`.`teamid`
IN (SELECT `teamid`
FROM `bio_leadteams`
WHERE `office_id` =3
)
AND `bio_leads`.`leaddate`='".$createdate."'
";
    $result21=DB_query($sql21,$db);
    $row21=DB_fetch_array($result21);
    $ex21=$row21['0'];  
   //----------------------------------------------   
   //--------------------Dealer----------------------------  
      $sql22=$sql."AND bio_leads.enqtypeid =8 AND `bio_leads`.`teamid`
IN (SELECT `teamid`
FROM `bio_leadteams`
WHERE `office_id` =4
)
AND `bio_leads`.`leaddate`!='".$createdate."'
";
    $result22=DB_query($sql22,$db);
    $row22=DB_fetch_array($result22);
    $ex22=$row22['0'];  
   //----------------------------------------------      
        $sql23=$sql."AND bio_leads.enqtypeid =8 AND `bio_leads`.`teamid`
IN (SELECT `teamid`
FROM `bio_leadteams`
WHERE `office_id` =2
)
AND `bio_leads`.`leaddate`!='".$createdate."'
";
    $result23=DB_query($sql23,$db);
    $row23=DB_fetch_array($result23);
    $ex23=$row23['0'];  
   //----------------------------------------------      
        $sql24=$sql."AND bio_leads.enqtypeid =8 AND `bio_leads`.`teamid`
IN (SELECT `teamid`
FROM `bio_leadteams`
WHERE `office_id` =3
)
AND `bio_leads`.`leaddate`!='".$createdate."'
";
    $result24=DB_query($sql24,$db);
    $row24=DB_fetch_array($result24);
    $ex24=$row24['0'];  
   //----------------------------------------------    
        $sql25=$sql."AND bio_leads.enqtypeid =8 AND `bio_leads`.`teamid`
IN (SELECT `teamid`
FROM `bio_leadteams`
WHERE `office_id` =4
)
AND `bio_leads`.`leaddate`='".$createdate."'
";
    $result25=DB_query($sql25,$db);
    $row25=DB_fetch_array($result25);
    $ex25=$row25['0'];  
   //----------------------------------------------   
        $sql26=$sql."AND bio_leads.enqtypeid =8 AND `bio_leads`.`teamid`
IN (SELECT `teamid`
FROM `bio_leadteams`
WHERE `office_id` =2
)
AND `bio_leads`.`leaddate`='".$createdate."'
";
    $result26=DB_query($sql26,$db);
    $row26=DB_fetch_array($result26);
    $ex26=$row26['0'];  
   //----------------------------------------------    
        $sql27=$sql."AND bio_leads.enqtypeid =8 AND `bio_leads`.`teamid`
IN (SELECT `teamid`
FROM `bio_leadteams`
WHERE `office_id` =3
)
AND `bio_leads`.`leaddate`='".$createdate."'
";
    $result27=DB_query($sql27,$db);
    $row27=DB_fetch_array($result27);
    $ex27=$row27['0'];  
   //----------------------------------------------     
 
 
 
 
 
 
 
 
   
   //----------------order register--------------------------
   $sqlord="SELECT count( DISTINCT `bio_leadtask`.`leadid` ) AS count
FROM `bio_leadtask`
INNER JOIN `bio_leads` ON `bio_leads`.`leadid` = `bio_leadtask`.`leadid`
INNER JOIN `salesorders` ON  `salesorders`.`leadid`= `bio_leads`.`leadid` 
WHERE `bio_leadtask`.`taskcompletedstatus` =1
AND `bio_leadtask`.`taskid` = 5 AND  `salesorders`.`orddate` ='".$createdate."'";    

 //------------------Domestic--------------------
 //--------------------------------------------  
  $sqlord1=$sqlord."AND bio_leads.enqtypeid =1 AND `bio_leads`.`teamid`
IN (SELECT `teamid`
FROM `bio_leadteams`
WHERE `office_id` =4
)
";
    $resultord1=DB_query($sqlord1,$db);
    $roword1=DB_fetch_array($resultord1);
    $ag1=$roword1['0'];           
 
 //--------------------------------------------  
  $sqlord2=$sqlord."AND bio_leads.enqtypeid =1 AND `bio_leads`.`teamid`
IN (SELECT `teamid`
FROM `bio_leadteams`
WHERE `office_id` =2
)
";
    $resultord2=DB_query($sqlord2,$db);
    $roword2=DB_fetch_array($resultord2);
    $ag2=$roword2['0'];           
          
//--------------------------------------------  
  $sqlord3=$sqlord."AND bio_leads.enqtypeid =1 AND `bio_leads`.`teamid`
IN (SELECT `teamid`
FROM `bio_leadteams`
WHERE `office_id` =3
)
";
       $resultord3=DB_query($sqlord3,$db);
    $roword3=DB_fetch_array($resultord3);
    $ag3=$roword3['0'];           
                   
  //--------------------------INSTITUTION--------------
 //--------------------------------------------  
  $sqlord4=$sqlord."AND bio_leads.enqtypeid =2 AND `bio_leads`.`teamid`
IN (SELECT `teamid`
FROM `bio_leadteams`
WHERE `office_id` =4
)
";
    $resultord4=DB_query($sqlord4,$db);
    $roword4=DB_fetch_array($resultord4);
    $ag4=$roword4['0'];           
 
 //--------------------------------------------  
  $sql_agn5=$sqlord."AND bio_leads.enqtypeid =2 AND `bio_leads`.`teamid`
IN (SELECT `teamid`
FROM `bio_leadteams`
WHERE `office_id` =2
)
";
    $result_agn5=DB_query($sql_agn5,$db);
    $row_agn5=DB_fetch_array($result_agn5);
    $ag5=$row_agn5['0'];    
    //--------------------------------------------  
  $sql_agn6=$sqlord."AND bio_leads.enqtypeid =2 AND `bio_leads`.`teamid`
IN (SELECT `teamid`
FROM `bio_leadteams`
WHERE `office_id` =3
)
";
    $result_agn6=DB_query($sql_agn6,$db);
    $row_agn6=DB_fetch_array($result_agn6);
    $ag6=$row_agn6['0'];    
           
     //----------------------LSGD----------------------------------   
    //------------------------------------------------------------
  $sqlord7=$sqlord."AND bio_leads.enqtypeid =3 AND `bio_leads`.`teamid`
IN (SELECT `teamid`
FROM `bio_leadteams`
WHERE `office_id` =4
)
";
    $resultord7=DB_query($sqlord7,$db);
    $roword7=DB_fetch_array($resultord7);
    $ag7=$roword7['0'];           
 
 //--------------------------------------------  
  $sqlord8=$sqlord."AND bio_leads.enqtypeid =3 AND `bio_leads`.`teamid`
IN (SELECT `teamid`
FROM `bio_leadteams`
WHERE `office_id` =2
)
";
    $resultord8=DB_query($sqlord8,$db);
    $roword8=DB_fetch_array($resultord8);
    $ag8=$roword8['0'];           
          
//--------------------------------------------  
  $sqlord9=$sqlord."AND bio_leads.enqtypeid =3 AND `bio_leads`.`teamid`
IN (SELECT `teamid`
FROM `bio_leadteams`
WHERE `office_id` =3
)
";
       $resultord9=DB_query($sqlord9,$db);
    $roword9=DB_fetch_array($resultord9);
    $ag9=$roword9['0'];           
                   
 
 //--------------------------------------------
 //-------------------Dealer------------------  
  $sqlord10=$sqlord."AND bio_leads.enqtypeid =8 AND `bio_leads`.`teamid`
IN (SELECT `teamid`
FROM `bio_leadteams`
WHERE `office_id` =4
)
";
    $resultord10=DB_query($sqlord10,$db);
    $roword10=DB_fetch_array($resultord10);
    $ag10=$roword10['0'];           
 
 //--------------------------------------------  
  $sql_agn11=$sqlord."AND bio_leads.enqtypeid =8 AND `bio_leads`.`teamid`
IN (SELECT `teamid`
FROM `bio_leadteams`
WHERE `office_id` =2
)
";
    $result_agn11=DB_query($sql_agn11,$db);
    $row_agn11=DB_fetch_array($result_agn11);
    $ag11=$row_agn11['0'];    
    //--------------------------------------------  
  $sql_agn12=$sqlord."AND bio_leads.enqtypeid =8 AND `bio_leads`.`teamid`
IN (SELECT `teamid`
FROM `bio_leadteams`
WHERE `office_id` =3
)
";
    $result_agn12=DB_query($sql_agn12,$db);
    $row_agn12=DB_fetch_array($result_agn12);
    $ag12=$row_agn12['0'];           
        
    //------------------------------------------------------------   
    //------------------------------------------------------------
    //------------------------------------------------------------
        
 
 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<style type="text/css">
<!--
.style5 {font-weight: bold; font-size: 24px;}
-->
</style>
</head>

<body>
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
    <span class="style5">Report of leads and orders registered <?php echo $createdate; ?></span>
  </center></label></td>
 </tr> 
    <tr>
      <th width="17%" rowspan="2" scope="row"><p align="center"><strong>Lead </strong><strong>category</strong></p>      </th>
      <th colspan="4"><strong><center>
        <strong>Existing  Lead </strong>
      </center></strong></th>
      <th colspan="4"><strong><center>
        <strong>Lead  registered</strong>
      </center></strong></th>
      <th colspan="4"><strong><center>
        <strong>Cumulative  Lead</strong>
      </center></strong></th>
      <th colspan="4"><strong><center>
        <strong>Order  registered</strong>
      </center></strong></th>
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
      <td><div align="center"><?php echo $ex1; ?></div></td>
      <td><div align="center"><?php echo $ex2; ?></div></td>
      <td><div align="center"><?php echo $ex3; ?></div></td>
      <td><div align="center"><?php echo $ex1+$ex2+$ex3; ?></div></td>
      <td><div align="center"><?php echo $ex4; ?></div></td>
      <td><div align="center"><?php echo $ex5; ?></div></td>                                                                           
      <td><div align="center"><?php echo $ex6; ?></div></td>
      <td><div align="center"><?php echo $ex4+$ex5+$ex6; ?></div></td>
      <td><div align="center"><?php echo $ex1+$ex4; ?></div></td>
      <td><div align="center"><?php echo $ex2+$ex5; ?></div></td>
      <td><div align="center"><?php echo $ex3+$ex6; ?></div></td>
      <td><div align="center"><?php echo $ex1+$ex4+$ex2+$ex5+$ex3+$ex6; ?></div></td>
      <td><div align="center"><?php echo $ag1; ?></div></td>
      <td><div align="center"><?php echo $ag2; ?></div></td>
      <td><div align="center"><?php echo $ag3; ?></div></td>
      <td><div align="center"><?php echo $ag1+$ag2+$ag3; ?></div></td>
    </tr>
    <tr>
      <th ><strong>Institutional</strong></th>
      <td bgcolor="#CC99CC"><div align="center"><?php echo $ex10; ?></div></td>
      <td bgcolor="#CC99CC"><div align="center"><?php echo $ex11; ?></div></td>
      <td bgcolor="#CC99CC"><div align="center"><?php echo $ex12; ?></div></td>
      <td bgcolor="#CC99CC"><div align="center"><?php echo $ex10+$ex11+$ex12; ?></div></td>
      <td bgcolor="#CC99CC"><div align="center"><?php echo $ex13; ?></div></td>
      <td bgcolor="#CC99CC"><div align="center"><?php echo $ex14; ?></div></td>
      <td bgcolor="#CC99CC"><div align="center"><?php echo $ex15; ?></div></td>
      <td bgcolor="#CC99CC"><div align="center"><?php echo $ex13+$ex14+$ex15; ?></div></td>
      <td bgcolor="#CC99CC"><div align="center"><?php echo $ex10+$ex13; ?></div></td>
      <td bgcolor="#CC99CC"><div align="center"><?php echo $ex11+$ex14; ?></div></td>
      <td bgcolor="#CC99CC"><div align="center"><?php echo $ex12+$ex15; ?></div></td>
      <td bgcolor="#CC99CC"><div align="center"><?php echo $ex10+$ex13+$ex11+$ex14+$ex12+$ex15; ?></div></td>
      <td bgcolor="#CC99CC"><div align="center"><?php echo $ag4; ?></div></td>
      <td bgcolor="#CC99CC"><div align="center"><?php echo $ag5; ?></div></td>
      <td bgcolor="#CC99CC"><div align="center"><?php echo $ag6; ?></div></td>
      <td bgcolor="#CC99CC"><div align="center"><?php echo $ag4+$ag5+$ag6; ?></div></td>
    </tr>
    <tr>
      <th ><strong>LSGD</strong></th>
      <td><div align="center"><?php echo $ex16; ?></div></td>
      <td><div align="center"><?php echo $ex17; ?></div></td>
      <td><div align="center"><?php echo $ex18; ?></div></td>
      <td><div align="center"><?php echo $ex16+$ex17+$ex18; ?></div></td>
      <td><div align="center"><?php echo $ex19; ?></div></td>
      <td><div align="center"><?php echo $ex20; ?></div></td>
      <td><div align="center"><?php echo $ex21; ?></div></td>
      <td><div align="center"><?php echo $ex20+$ex21+$ex19; ?></div></td>
      <td><div align="center"><?php echo $ex16+$ex19; ?></div></td>
      <td><div align="center"><?php echo $ex17+$ex20; ?></div></td>
      <td><div align="center"><?php echo $ex18+$ex21; ?></div></td>
      <td><div align="center"><?php echo $ex18+$ex21+$ex16+$ex17+$ex19+$ex20; ?></div></td>
      <td><div align="center"><?php echo $ag7; ?></div></td>
      <td><div align="center"><?php echo $ag8; ?></div></td>
      <td><div align="center"><?php echo $ag9; ?></div></td>
      <td><div align="center"><?php echo $ag7+$ag8+$ag9; ?></div></td>
    </tr>
    <tr>
      <th><strong>Dealer</strong></th>
      <td bgcolor="#CC99CC"><div align="center"><?php echo $ex22; ?></div></td>
      <td bgcolor="#CC99CC"><div align="center"><?php echo $ex23; ?></div></td>
      <td bgcolor="#CC99CC"><div align="center"><?php echo $ex24; ?></div></td>
      <td bgcolor="#CC99CC"><div align="center"><?php echo $ex22+$ex23+$ex24; ?></div></td>
      <td bgcolor="#CC99CC"><div align="center"><?php echo $ex25; ?></div></td>
      <td bgcolor="#CC99CC"><div align="center"><?php echo $ex26; ?></div></td>
      <td bgcolor="#CC99CC"><div align="center"><?php echo $ex27; ?></div></td>
      <td bgcolor="#CC99CC"><div align="center"><?php echo $ex25+$ex26+$ex27; ?></div></td>
      <td bgcolor="#CC99CC"><div align="center"><?php echo $ex22+$ex25; ?></div></td>
      <td bgcolor="#CC99CC"><div align="center"><?php echo $ex23+$ex26; ?></div></td>
      <td bgcolor="#CC99CC"><div align="center"><?php echo $ex24+$ex27; ?></div></td>
      <td bgcolor="#CC99CC"><div align="center"><?php echo $ex22+$ex23+$ex24+$ex25+$ex26+$ex27; ?></div></td>
      <td bgcolor="#CC99CC"><div align="center"><?php echo $ag10; ?></div></td>
      <td bgcolor="#CC99CC"><div align="center"><?php echo $ag11; ?></div></td>
      <td bgcolor="#CC99CC"><div align="center"><?php echo $ag12; ?></div></td>
      <td bgcolor="#CC99CC"><div align="center"><?php echo $ag10+$ag11+$ag12; ?></div></td>
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
  </table>

  <label></label>
</form>
</body>
</html>
