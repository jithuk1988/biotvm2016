<?php
$PageSecurity = 80;
include('includes/session.inc');
$title = _('Installation Status');  
include('includes/header.inc');
?>
<style type="text/css">
#hd
{
background: rgb(219,219,219); /* Old browsers */
background: -moz-linear-gradient(top,  rgba(219,219,219,1) 6%, rgba(226,226,226,1) 9%, rgba(219,219,219,1) 42%, rgba(209,209,209,1) 80%, rgba(254,254,254,1) 100%); /* FF3.6+ */
background: -webkit-gradient(linear, left top, left bottom, color-stop(6%,rgba(219,219,219,1)), color-stop(9%,rgba(226,226,226,1)), color-stop(42%,rgba(219,219,219,1)), color-stop(80%,rgba(209,209,209,1)), color-stop(100%,rgba(254,254,254,1))); /* Chrome,Safari4+ */
background: -webkit-linear-gradient(top,  rgba(219,219,219,1) 6%,rgba(226,226,226,1) 9%,rgba(219,219,219,1) 42%,rgba(209,209,209,1) 80%,rgba(254,254,254,1) 100%); /* Chrome10+,Safari5.1+ */
background: -o-linear-gradient(top,  rgba(219,219,219,1) 6%,rgba(226,226,226,1) 9%,rgba(219,219,219,1) 42%,rgba(209,209,209,1) 80%,rgba(254,254,254,1) 100%); /* Opera 11.10+ */
background: -ms-linear-gradient(top,  rgba(219,219,219,1) 6%,rgba(226,226,226,1) 9%,rgba(219,219,219,1) 42%,rgba(209,209,209,1) 80%,rgba(254,254,254,1) 100%); /* IE10+ */
background: linear-gradient(to bottom,  rgba(219,219,219,1) 6%,rgba(226,226,226,1) 9%,rgba(219,219,219,1) 42%,rgba(209,209,209,1) 80%,rgba(254,254,254,1) 100%); /* W3C */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#dbdbdb', endColorstr='#fefefe',GradientType=0 ); /* IE6-9 */

}
#sl1{

height:30px;
background: rgb(252,255,244); /* Old browsers */
background: -moz-linear-gradient(top,  rgba(252,255,244,1) 0%, rgba(223,229,215,1) 40%, rgba(179,190,173,1) 100%); /* FF3.6+ */
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(252,255,244,1)), color-stop(40%,rgba(223,229,215,1)), color-stop(100%,rgba(179,190,173,1))); /* Chrome,Safari4+ */
background: -webkit-linear-gradient(top,  rgba(252,255,244,1) 0%,rgba(223,229,215,1) 40%,rgba(179,190,173,1) 100%); /* Chrome10+,Safari5.1+ */
background: -o-linear-gradient(top,  rgba(252,255,244,1) 0%,rgba(223,229,215,1) 40%,rgba(179,190,173,1) 100%); /* Opera 11.10+ */
background: -ms-linear-gradient(top,  rgba(252,255,244,1) 0%,rgba(223,229,215,1) 40%,rgba(179,190,173,1) 100%); /* IE10+ */
background: linear-gradient(to bottom,  rgba(252,255,244,1) 0%,rgba(223,229,215,1) 40%,rgba(179,190,173,1) 100%); /* W3C */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#fcfff4', endColorstr='#b3bead',GradientType=0 ); /* IE6-9 */
}
#sl2{
height:30px;
background: rgb(247,251,252); /* Old browsers */
background: -moz-linear-gradient(top,  rgba(247,251,252,1) 0%, rgba(217,237,242,1) 40%, rgba(173,217,228,1) 100%); /* FF3.6+ */
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(247,251,252,1)), color-stop(40%,rgba(217,237,242,1)), color-stop(100%,rgba(173,217,228,1))); /* Chrome,Safari4+ */
background: -webkit-linear-gradient(top,  rgba(247,251,252,1) 0%,rgba(217,237,242,1) 40%,rgba(173,217,228,1) 100%); /* Chrome10+,Safari5.1+ */
background: -o-linear-gradient(top,  rgba(247,251,252,1) 0%,rgba(217,237,242,1) 40%,rgba(173,217,228,1) 100%); /* Opera 11.10+ */
background: -ms-linear-gradient(top,  rgba(247,251,252,1) 0%,rgba(217,237,242,1) 40%,rgba(173,217,228,1) 100%); /* IE10+ */
background: linear-gradient(to bottom,  rgba(247,251,252,1) 0%,rgba(217,237,242,1) 40%,rgba(173,217,228,1) 100%); /* W3C */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#f7fbfc', endColorstr='#add9e4',GradientType=0 ); /* IE6-9 */

}
</style>
<?php
$off=$_SESSION['officeid'];
if ($off==1)
{
    
}         
$ord=$_GET['ordno'];
    
if($ord!=null){
    $sql_sel="SELECT *  FROM bio_installation_status WHERE orderno=".$ord;                                                   $result_all=DB_query($sql_sel,$db);
    $main=DB_fetch_array($result_all); 
 
}
if(isset($_POST['submit'])){
    $sql_sel="SELECT * FROM bio_installation_status WHERE orderno=".$_POST['orderno'];                                      $result_all=DB_query($sql_sel,$db);
    $main_tb=DB_num_rows($result_all); 
    $main_tbl=DB_fetch_array($result_all);       
    $installeddate=FormatDateForSQL($_POST['installed_date']);
    $duedate=$_POST['duedate1'];
     
  if($main_tb>0){ 
                
       $actualdate2=FormatDateForSQL($_POST['actualdate2']);
       $actualdate3=FormatDateForSQL($_POST['actualdate3']);
       $actualdate=FormatDateForSQL($_POST['actualdate1']);
      
       if($main_tbl['actual_date1']==$_POST['actualdate1']){
        $actualdate=$_POST['actualdate1'];   
       } 
      if($main_tbl['actual_date2']==$_POST['actualdate2'])
      {
        $actualdate2=$_POST['actualdate2'];   
      }
      if($main_tbl['actual_date3']==$_POST['actualdate3'])
      {
        $actualdate3=$_POST['actualdate3'];   
      }
      
      
      if($actualdate!='0000-00-00'){
           $date = strtotime(date("Y-m-d", strtotime($actualdate)) . " +7 day");
           $_POST['duedate2']=date('Y-m-d',$date);
      }
      if($actualdate2!='0000-00-00'){
           $date = strtotime(date("Y-m-d", strtotime($actualdate2)) . " +11 day");
           $_POST['duedate3']=date('Y-m-d',$date);
      }
      
     $sql4="UPDATE  bio_installation_status 
                                          SET actual_date1='".$actualdate."',
                                          actual_date2='".$actualdate2."',
                                          due_date2='".$_POST['duedate2']."',
                                          actual_date3='".$actualdate3."',
                                          due_date3='".$_POST['duedate3']."',
                                          plant_status='".$_POST['plantstatus']."',
                                          close_status='".$_POST['closestatus']."',
                                          remarks='".$_POST['remarks']."' 
                                          WHERE orderno=".$_POST['orderno'];                     
                                          $result4=DB_query($sql4,$db); 
                                          prnMsg(_('Updated') ,'success'); 
//  }else{
//      
//       $actualdate=FormatDateForSQL($_POST['actualdate1']); 
//       $due=$duedate;
//       $date = strtotime(date("Y-m-d", strtotime($due)) . " +7 day");
//       $date1=date('Y-m-d',$date);      
//       $sql="INSERT INTO bio_installation_status (orderno,installed_date,actual_date1,due_date1,due_date2,plant_status,remarks) 
//                            VALUES (".$_POST['orderno'].",
//                            '".$installeddate."',
//                            '".$actualdate."',
//                            '".$duedate."',
//                            '".$date1."',
//                            ".$_POST['plantstatus'].",
//                            '".$_POST['remarks']."')";
//                            $result=DB_query($sql,$db);   
//                            prnMsg(_('inserted') ,'success');
//                                   
//  } 
  }
}          


echo"<div id=fullbody>";

echo '<form action="' . $_SERVER['PHP_SELF'] . '" method="post" style="background:#EBEBEB;">';

echo '<table class="selection" style="width:70%;">';
echo '<th colspan="2"><b><font size="+1" color="#000000">Installation Status</font></b></th>' ;
echo '<tr><td align="left">';
echo"<div id=insatal>";
echo"<fieldset style='width:100%;height:360px '>"; 
echo"<legend>Installation Status</legend>";
echo"<table width=100%>";



echo '<tr id="ordno"><td style="width:14%" >OrderNo*</td>
<td style="width:14%" ><input type="text" name="orderno" id="orderno" value="'.$ord.'"></td></tr>';
if($ord!=NULL){
           $sql="SELECT deliverydate FROM salesorders 
                                                     WHERE orderno=".$ord;                                                          $result=DB_query($sql,$db);
           $delivrdate=DB_fetch_array($result);
          $sql1="SELECT COUNT(*) as orderno FROM bio_calllog WHERE orderno=".$ord." AND status=1";                                  $result1=DB_query($sql1,$db);
           $calltime=DB_fetch_array($result1);                                                       
}

echo'<tr><td style="width:25%">delivery date*</td>
<td style="width:14%"><input type="text" readonly value="'.$delivrdate['deliverydate'].'"></tr>';
echo'<tr><td style="width:25%">Installation date*</td>
<td style="width:14%">';
if($main['installed_date']!=NULL){
   echo '<input type="text" readonly name="installed_date" id="installed_date" value="'.$main['installed_date'].'">'; 
}else{
echo '<input type="text" name="installed_date" id="installed_date"  onblur="duedate();return false" class=date alt="'.$_SESSION['DefaultDateFormat']. '">';
}
echo '</tr>';
echo '<tr><td style="width:4%">Due Date1*</td>
<td  id="duedate">';
if($main['due_date1']!=NULL){
echo '<input type="text" readonly name="duedate1" id="duedate1" value="'.$main['due_date1'].'">';
}else{
 echo '<input type="text" name="duedate1" id="duedate1" value="">';   
     }
echo '</td></tr>';



//$sql="SELECT count(*),call_date FROM bio_calllog WHERE orderno=$ord AND callno=1";
//$result=DB_query($sql,$db);
//$myrow=DB_fetch_array($result);echo$myrow[0];
//if($myrow[0]!=0)
//{
echo "<div>"    ;
echo '<tr><td style="width:4%" >Actual date1*</td>
<td id=actualdate style="width:4%">';
echo '<input readonly type="text" name="actualdate1" id="actualdate1" value="'.$main['actual_date1'].'">'; 
echo '<td></tr>'; 
//if($main['actual_date1']!=NULL){
//   
//}else{
//echo '<input hidden type="text" name="actualdate1" id="actualdate1" >';
//     }

if($main['actual_date1']!=0){ 
        $act1=$main['actual_date1'];
        $date = strtotime(date("Y-m-d", strtotime($act1)) . " +7 day");
        $date1=date('Y-m-d',$date);
echo '<tr ><td style="width:4%">Due Date2*</td>
<td style="width:4%"><input readonly type="text" name="duedate2" id="duedate2" value="'.$date1.'"></td></tr>';
}else{
echo '<tr ><td style="width:4%">Due Date2*</td>
<td style="width:4%"><input readonly type="text" name="duedate2" id="duedate2" value="'.$main['due_date2'].'"></td></tr>';    
}


if($main['installed_date']!='0000-00-00'){
echo '<tr><td style="width:4%">Actual date2*</td>';
echo '<td style="width:4%" id=actualdatetd2><input readonly type="text" name="actualdate2" id="actualdate2" value="'.$main['actual_date2'].'"><td></tr>';
}       
//}     
echo "</div>";


if($main['actual_date2']!=NULL && $main['actual_date2']!='0000-00-00'){
        $due=$main['actual_date2'];
        $date = strtotime(date("Y-m-d", strtotime($due)) . " +11 day");
        $date1=date('Y-m-d',$date);
echo '<tr><td style="width:4%">Due Date3*</td>
<td style="width:4%"><input readonly type="text" name="duedate3" id="duedate3" value="'.$date1.'"></td></tr>';
}
if($main['actual_date2']!=NULL && $main['actual_date2']!=0){
echo '<tr><td style="width:4%">Actual date3*</td>
<td style="width:4%" id=actualdatetd3><input readonly type="text" name="actualdate3" id="actualdate3" value="'.$main['actual_date3'].'"></td></tr>';
}



echo '<tr><td style="width:4%">Plant Status*</td>
<td style="width:4%"><select name="plantstatus" id="plantstatus" style="width:75%">
<option value=0></option>';
if($main['plant_status']==1){
echo '<option value=1 selected="selected">Working</option>';
}else{echo '<option value=1 selected="selected">Working</option>';}
if($main['plant_status']==2){
echo '<option value=2 selected="selected">Problem</option>';
}else{echo '<option value=2 >Problem</option>';}
echo '</select></td></tr>';
if($main['actual_date2']!=NULL && $main['actual_date2']!=0){
echo '<tr><td style="width:4%">Close Status*</td>
<td style="width:4%"><select name="closestatus" id="closestatus" style="width:75%">
<option value=0 selected="selected">No</option>
<option value=1>Yes</option>
</select></td></tr>';
}
echo '<tr><td style="width:4%">Remarks*</td>
<td style="width:4%"><textarea name="remarks" id="remarks"></textarea></td></tr>';

echo '<td colspan="2" align="right"><input type="submit" name="submit" id="submit" value="submit" onclick="if(check_remark()==1)return false;"></td>';
//echo '<tr ><td colspan=2><a style=cursor:pointer; onclick=installationview(0)>' . _('Back') . '</a></td></tr>';
echo '</fieldset>';
echo "</tr><tr></tr><tr></tr>";
echo '<tr ><td colspan=2><a style=cursor:pointer; onclick=installationview(1)>' . _('Instatallation status view') . '</a></td></tr>';
echo '<tr ><td colspan=2><a style=cursor:pointer; onclick=installationview(0)>' . _('Calls Due Today') . '</a></td></tr>';
echo '</table>';
echo '</div>';
$sql_ph="SELECT brname,braddress2,bio_district.district,faxno,phoneno 
                  FROM  custbranch,bio_district 
                  WHERE (custbranch.cid=bio_district.cid AND custbranch.stateid=bio_district.stateid AND custbranch.did=bio_district.did)
                  AND   debtorno=(SELECT debtorno FROM salesorders WHERE orderno='".$_GET["ordno"]."')";
                  $result_ph=DB_query($sql_ph,$db); 
                  $row_ph=DB_fetch_array($result_ph);
                  if($row_ph['phoneno']!="" && $row_ph['faxno']!=""){
                   $contactno=$row_ph['phoneno'].",".$row_ph['faxno'];
                  }elseif($row_ph['phoneno']!=""){
                   $contactno=$row_ph['phoneno'];   
                  }elseif($row_ph['faxno']!=""){
                   $contactno=$row_ph['faxno'];  
                  }
                  
                  
echo '<td>';
if($_GET[ordno]!=null){
    
    echo '<font color="#400040" size="+1" style="margin-left:30%">'.$row_ph['brname'].'</font><br />';
    echo '<font color="#400040" size="+1" style="margin-left:30%">'.$row_ph['braddress2'].'</font><br />';
    echo '<font color="#400040" size="+1" style="margin-left:30%">'.$row_ph['district'].'</font><br />';   
    echo '<font color="#400040" size="+1" style="margin-left:30%">'.$contactno.'</font><br />';   
}

echo '<div id="call_log"></div>';
echo"<div id=calllog>";
echo"<fieldset style='width:85%;height:200px'>"; 
echo"<legend>Call Log</legend>";
echo"</legend>";
echo"<br />";
echo"<table width=100% id='cal_lgtb'>";

echo '<tr><td style="width:4%">OrderNo*</td>
<td style="width:14%"><input type="text" name="ordernum" id="ordernum" value="'.$ord.'" ></td></tr>';
   $caltime=$calltime['orderno'];                                                   
echo '<tr><td style="width:4%">Call NO*</td>
<td style="width:4%"><select name="fbcall" id="fbcall" style="width:75%">
<option value=0></option>';
if($caltime==0){
echo '<option value=1 selected="selected">First Call</option>';
}
if($caltime==1){echo '<option value=2 selected="selected">Second Call</option>';}

if($caltime>1){echo '<option value=3 selected="selected">Third Call</option>';}

echo '</select></td></tr>';
echo '<tr><td style="width:4%">Call Date*</td>
<td style="width:4%"><input type="text" name="caldate" id="caldate" class=date alt="'.$_SESSION['DefaultDateFormat']. '" value='.date("d/m/Y").'><td></tr>';
echo '<tr><td style="width:4%">Remarks*</td>
<td style="width:4%"><textarea name="remarks1" id="remarks1"></textarea></td></tr>';
echo '<tr><td style="width:4%">Status*</td>
<td style="width:4%" ><select name="status" id="status" style="width:75%" onchange="changestatus(this.value);return false">
<option value=0></option>
<option value=1>Success</option>
<option value=2>Customer Busy</option>
<option value=3>Customer Not picking</option>
<option value=4>Line Busy</option>
<option value=4>Switch off</option>
</select></td></tr>';

echo '</table>';
echo '<td ></td><td></td><td><a style=cursor:pointer; onclick=showcalstatus(1)>' . _('CALL STATUS') . '</a></td></tr>'; 
echo '<td></td></tr>';

echo '</table>';
echo '</form>';
echo '</div>';
//for show installation view
 if($_GET['status']==1){
          $heading="INSTALLATION STATUS VIEW";
 
echo '<center><font style="color: #333;
                           background:#fff;
                           font-weight:bold;
                           letter-spacing:0.10em;
                           font-size:16px;
                           font-family:Georgia;
                           text-shadow: 1px 1px 1px #666;">'.$heading.'</font></center>';    
 
echo "<table style='width:90%'><tr><td>";        
//echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "?" . SID . "'>";

echo '<br><input type="hidden" name="Status" value='.$_GET['status'].' >';


echo "<div style='height:400px; overflow:auto;'>";
echo "<table style='border:1 solid #F0F0F0;width:100%'>";
echo "<tr><th style=width:5%><b>ORDER No</b></th>
<th style=width:10%><b>INSTALLED DATE</b></th> 
<th style=width:10%><b>DUE DATE1</b></th>    
<th class='task' style=width:10%><b>ACTUAL DATE1</b></th>
<th class='team' style=width:10%><b>DUE DATE2</b></th>
<th style=width:10%><b>ACTUAL DATE2</b></th>
<th style=width:10%><b>DUE DATE3</b></th>
<th style=width:10%><b>ACTUAL DATE3</b></th>
<th style=width:10%><b>PLANT STATUS</b></th>
<th style=width:10%><b>CLOSE STATUS</b></th>
<th style=width:10%><b>REMARKS</b></th></tr>";
//$sql_date="SELECT max(actual_date3) as actual_date3  FROM bio_installation_status" ;  
//$result_date=DB_query($sql_date,$db);
//while($result_date1=DB_fetch_array($result_date)) 
// {
     $due=date('Y-m-d');
     $date = strtotime(date("Y-m-d", strtotime($due)) . " -15 day");
     $date1=date('Y-m-d',$date);

 //}
 $sql_selall="SELECT *  FROM bio_installation_status 
                                               WHERE actual_date3>".$date1; 
                                                
             $result_allsel=DB_query($sql_selall,$db);

 while($result_tb=DB_fetch_array($result_allsel)) 
 {
     echo "<tr>";
  echo '<td><a id="'.$result_tb['orderno'].'"  href="" onclick="ordernumber(this.id);return false" >'.$result_tb["orderno"].'</a></td>';
  echo '<td>'.$result_tb["installed_date"].'</td>';
  echo '<td>'.ConvertSQLDate($result_tb["due_date1"]).'</td>';  
  echo '<td>'.$result_tb["actual_date1"].'</td>';
  echo '<td>'.$result_tb["due_date2"].'</td>';
  echo '<td>'.$result_tb["actual_date2"].'</td>';
  echo '<td>'.$result_tb["due_date3"].'</td>';
  echo '<td>'.$result_tb["actual_date3"].'</td>';
  if($result_tb["plant_status"]==1){$plantstatus="Working";}else{$plantstatus="Problem";}
  echo '<td>'.$plantstatus.'</td>';
    if($result_tb["close_status"]==0){$closestatus="No";}else{$closestatus="Yes";}
  echo '<td>'.$closestatus.'</td>';
   echo '<td>'.substr($result_tb["remarks"],0,20).'</td>';
   echo "</tr>" ;
 }

echo "</table>";
echo "</div>";
//echo "</form>";
echo "<td></tr></table>";
echo "</div>";
}
// }
//for show installation view end
//for Calls Due Today
if($_GET['status']==0){
          $heading="Calls Due Today";
                                       
echo '<center><font style="color: #333;
                           background:#fff;
                           font-weight:bold;
                           letter-spacing:0.10em;
                           font-size:16px;
                           font-family:Georgia;
                           text-shadow: 1px 1px 1px #666;">'.$heading.'</font></center>';    
 
echo "<table style='width:90%'><tr><td>";        
//echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "?" . SID . "'>";

echo '<br><input type="hidden" name="Status" value='.$_GET['status'].' >';


echo "<div style='height:400px; overflow:auto;'>";
echo "<table style='border:1 solid #000000;width:100%'>";
echo "<tr id='hd' class='hd'><th id='hd' class='hd'><b>ORDER No</b></th>
<th id='hd' class='hd'><b>Name</b></th> 
<th id='hd' class='hd'><b>INSTALLED DATE</b></th> 
<th id='hd' class='hd'><b>DUE DATE1</b></th>    
<th id='hd' class='hd'><b>ACTUAL DATE1</b></th>
<th id='hd' class='hd'><b>DUE DATE2</b></th>
<th id='hd' class='hd'><b>ACTUAL DATE2</b></th>
<th id='hd' class='hd'><b>DUE DATE3</b></th>
<th  id='hd' class='hd'><b>ACTUAL DATE3</b></th>
<th  id='hd' class='hd'><b>PLANT STATUS</b></th>
<th  id='hd' class='hd'><b>CLOSE STATUS</b></th>
<th  id='hd' class='hd'><b>REMARKS</b></th></tr>";

//$sql_selall="SELECT *  FROM bio_installation_status" ;  

$sql_selall="SELECT * FROM bio_installation_status,salesorders,debtorsmaster 
             WHERE salesorders.orderno=bio_installation_status.orderno
             AND debtorsmaster.debtorno=salesorders.debtorno";
            // AND debtorsmaster.did IN($dist)";
             
             if($_SESSION['officeid']==1) 
             {
                 $sql_selall=$sql_selall;
             }
             //|| ($_SESSION['officeid']==4)){
// $dist='6,11,12';   
elseif($_SESSION['officeid']==4){
    $dist='6,11,12';   
    $sql_selall.=" and debtorsmaster.stateid=14 AND debtorsmaster.did IN($dist)";}
    elseif($_SESSION['officeid']==2){
         $dist='1,2,3,7,13'; 
         $sql_selall.=" and debtorsmaster.stateid=14 AND debtorsmaster.did IN($dist)";}
  elseif($_SESSION['officeid']==3){
 $dist='4,5,8,9,10,14';  
    $sql_selall.=" and debtorsmaster.stateid=14 AND debtorsmaster.did IN($dist)";} 
elseif($_SESSION['officeid']==5){
    $sql_selall.=" and debtorsmaster.cid=1 and debtorsmaster.stateid!=14";} 

elseif ($_SESSION['officeid']==6){
$sql_selall.=" and debtorsmaster.cid!=1";
}
 $crrdate=date('Y-m-d');
$result_allsel=DB_query($sql_selall,$db);
$slno=0;
 while($result_tb=DB_fetch_array($result_allsel)) 
 {
 
 
$sql_cal="SELECT MAX(callno) as callno  FROM bio_calllog WHERE orderno=".$result_tb['orderno'];  
$result_cal=DB_query($sql_cal,$db); 
$result_cal_log=DB_fetch_array($result_cal);
$sql_name="SELECT deliverto FROM salesorders WHERE orderno=".$result_tb['orderno'];  
$result_name=DB_query($sql_name,$db); 
$result_cal_name=DB_fetch_array($result_name);
   
if(($result_tb["due_date1"]<= $crrdate && $result_tb["actual_date1"]== 0)  || ($result_tb["due_date2"]<=$crrdate && $result_tb["actual_date2"]== 0) || ($result_tb["due_date3"]!=0 && $result_tb["due_date3"]<=$crrdate && $result_tb["close_status"]==0)) {
 $slno++;     
  if($slno%2==0)
	 {
	 echo "<tr class='sl1' id='sl1'>";
}
else
{
 echo "<tr class='sl2' id='sl2'>";
}
//if(($result_tb["due_date1"] <= $crrdate && $result_tb["actual_date1"]== '0000-00-00')  || ($result_tb["due_date2"] <= $crrdate && $result_tb["actual_date2"]== '0000-00-00') || ($result_tb["actual_date3"]=='0000-00-00' && $result_tb["due_date3"]<=$crrdate && $result_tb["close_status"]==0)) {
  echo '<td><b><a style="font-weight:bold" id="'.$result_tb['orderno'].'"  href="" onclick="ordernumber(this.id);return false" >'.$result_tb["orderno"].'</a></b></td>';
  echo '<td>'.$result_cal_name["deliverto"].'</td>';
  echo '<td>'.ConvertSQLDate($result_tb["installed_date"]).'</td>';
  if($result_tb["actual_date1"]==0){$actal1="---";}else{$actal1=ConvertSQLDate($result_tb["actual_date1"]); }
  if($result_tb["actual_date2"]==0){$actal2="---";}else{$actal2=ConvertSQLDate($result_tb["actual_date2"]);}
  if($result_tb["actual_date3"]==0){$actal3="---";}else{$actal3=ConvertSQLDate($result_tb["actual_date3"]);}
  if($result_tb["due_date2"]==0){$due2="---";}else{$due2=ConvertSQLDate($result_tb["due_date2"]);}
  if($result_tb["due_date3"]==0){$due3="---";}else{$due3=ConvertSQLDate($result_tb["due_date3"]);}
  if($actal1=="---" && $actal2=="---" && $actal3=="---")
  {
  echo '<td style="background-image: -moz-linear-gradient(top, #FFFFFF 0%, #EF5698 60%);">'.ConvertSQLDate($result_tb["due_date1"]).'</td>';
 }
 else
 {
 echo '<td >'.ConvertSQLDate($result_tb["due_date1"]).'</td>';
 }
  echo '<td>'.$actal1.'</td>';
  if($actal2=="---" && $actal3=="---" && $actal1!="---")
  {
  echo '<td style="background-image: -moz-linear-gradient(top, #FFFFFF 0%, #73A9EF 60%);">'.$due2.'</td>';
  }
  else{
   echo '<td>'.$due2.'</td>';
  }
  echo '<td>'.$actal2.'</td>';
    if($actal3=="---" && $actal2!="---" && $actal1!="---")
  {
  echo '<td style="background-image: -moz-linear-gradient(top, #FFFFFF 0%, #BAEFA7 60%);">'.$due3.'</td>';
  }
  else{
  echo '<td>'.$due3.'</td>';
  }echo '<td>'.$actal3.'</td>';
  if($result_tb["plant_status"]==1){$plantstatus="Working";}else{$plantstatus="Problem";}
  echo '<td>'.$plantstatus.'</td>';
    if($result_tb["close_status"]==0){$closestatus="No";}else{$closestatus="Yes";}
  echo '<td>'.$closestatus.'</td>';
   echo '<td>'.substr($result_tb["remarks"],0,20).'</td>';
   echo "</tr>" ;
 }
 }
echo "</table>";
echo "</div>";
//echo "</form>";
echo "<td></tr></table>";
echo "</div>";
}
//for calls Due today end
//if($_GET['status']==0){
//echo "<table style='width:60%'><tr><td>";  
//echo "<div style='height:400px; overflow:auto;'>"; 
//echo "<fieldset style='width:90%;'>";     
//     echo "<legend><h3>Sale order Registered</h3>";
//     echo "</legend>";         
//     echo "<table style='border:1 solid #F0F0F0;width:100%'>";
//     echo '<tr>
//     <td style="border-bottom:1px solid #000000">OrderNo</td> 
//                <td style="border-bottom:1px solid #000000">Customer Name</td>  
//                <td style="border-bottom:1px solid #000000">Contact No</td>
//                <td style="border-bottom:1px solid #000000">Delivery Date</td>
//                <td style="border-bottom:1px solid #000000">Customer Code</td>
//                </tr>';
//      $result="SELECT `custbranch`.`brname` , `custbranch`.`phoneno` , `custbranch`.`faxno` , `salesorders`.`orderno` , salesorders.branchcode, `salesorders`.`deliverydate`
//FROM `salesorders`
//INNER JOIN `custbranch` ON ( `salesorders`.`branchcode` = `custbranch`.`branchcode` )
//LEFT JOIN `bio_installation_status` ON ( `bio_installation_status`.`orderno` = `salesorders`.`orderno` )
//WHERE COALESCE( bio_installation_status.close_status, 0 ) =0
//AND COALESCE( bio_installation_status.installed_date, 0 ) =0
//ORDER BY salesorders.deliverydate ASC";
//                                                                                                                                
//   $result_saleorder=DB_query($result,$db); 
// while($row=DB_fetch_array($result_saleorder)){
//     $ph=$row[phoneno]."-<br>".$row[faxno];
//                printf("<tr style='background:#A8A4DB'>                
//            <td width='150px'><a id=".$row['orderno']."  href='' onclick='ordernumber(this.id);return false'>%s<a></td>
//            <td width='100px'>%s</td>
//            <td width='100px'>%s</td>
//            <td width='100px'>%s</td>
//            <td width='100px'>%s</td>
//                                   </tr>",
//        $row['orderno'],
//        $row['brname'],
//         $ph,
//        $row['deliverydate'],
//         $row['branchcode']      
//        ); 

//           }
//   echo "</table>";            

// 
// echo "</fieldset>";
//  echo "</div>";   
// echo "</td></tr></table>";
//}        
?>
<script type="text/javascript">

function ordernumber(str)
{   //alert(str1);

window.location="bio_installationstatus.php?ordno=" + str ;
}

function duedate()
{ 
 var str1=document.getElementById('installed_date').value;  

if (str1=="")
  {
  document.getElementById("duedate").innerHTML="";
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
    document.getElementById("duedate").innerHTML=xmlhttp.responseText;
    document.getElementById("duedate1").focus();
    }
  }
xmlhttp.open("GET","installationstatus.php?date=" + str1,true); 
xmlhttp.send();
}

function changestatus(str)
{ 

 var ordno=document.getElementById('ordernum').value; 
 var feedback=document.getElementById('fbcall').value; 
 var callno=document.getElementById('caldate').value;    
var remark=document.getElementById('remarks1').value;    
if (str=="")
  {
  document.getElementById("call_log").innerHTML="";
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
   if(str==1 && feedback==1)
   {     
    document.getElementById("actualdate").innerHTML=xmlhttp.responseText;    
   } 
   if(str==1 && feedback==2) 
   {
   document.getElementById("actualdatetd2").innerHTML=xmlhttp.responseText;    
   }
   if(str==1 && feedback==3) 
   {
   document.getElementById("actualdatetd3").innerHTML=xmlhttp.responseText;    
   }
    }
  }
xmlhttp.open("GET","call_log.php?status=" +str + "&order=" +ordno+ "&fdcal=" +feedback+ "&calno=" +callno+"&remark="+remark ,true);
xmlhttp.send();
}
function showcalstatus(str)
{
    
   var ordno=document.getElementById('ordernum').value; 
   // window.open("call_log.php");
  controlWindow=window.open("viewcall_log.php?type="+str+"&ordno="+ordno,"viewlog", "toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=800,height=600");    
}
function installationview(str)
{
    window.location="bio_installationstatus.php?status=" + str;
}


</script>