<?php
$PageSecurity = 80;
include('includes/session.inc');
$title = _('REPAY');  
include('includes/header.inc');
echo '<center><font style="color: #333;
    background:#fff;
    font-weight:bold;
    letter-spacing:0.10em;
    font-size:16px;
    font-family:Georgia;
    text-shadow: 1px 1px 1px #666;">Repayment</font></center>';
   echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "?" . REPAY. "'>"; 
   
   if($_POST['submit'])
   {      
  
       
       $lead_ID=$_POST['LeadID'];
           $head_ID=$_POST['head'];
       $mode_ID=$_POST['mode'];
       $payment_date=FormatDateForSQL($_POST['amtdate']);
       $receipt_no=$_POST['amtno']; 
       $payment_bank=$_POST['amtbank'];
       $DateString = Date($_SESSION['DefaultDateFormat']);
       $DateString=FormatDateForSQL($DateString);
       $amount=$_POST['advanceamount'];
       $status=$_POST['status'];
       $firstamount=$_POST['firstamount'];
           if($amount>$firstamount){
     print'<script>
      alert("Repay amount is greater than already recieved amount");
   </script> ';
     } 
       if($_POST['mode']=="")  
     {
        print'<script>
      alert("Please select a mode");
   </script> '; 
     }
     if($_POST['advanceamount']=="")  
     {
        print'<script>
      alert("Please select amount");
   </script> '; 
     }    
    $sql="INSERT INTO bio_advance( leadid,
                                   head_id,
                                   mode_id,
                                   date,
                                   serialnum,
                                   bankname,
                                   paydate,
                                   amount,
                                   officid,
                                   status,
                                   collected_by) 
                            VALUES ('".$lead_ID."',
                                    '".$head_ID."',
                                    '".$mode_ID."',
                                    '".$payment_date."',
                                    '".$receipt_no."',
                                    '".$payment_bank."',
                                    '".$DateString."',
                                    '".-$amount."',
                                    '".$_SESSION['officeid']."',
                                    '".$status."',
                                    '".$_SESSION[UserID]."')";
    $result=DB_query($sql,$db);    
 $sqladv="SELECT max( `adv_id` ) as count
FROM `bio_advance` " ;
            $result=DB_query($sqladv,$db); 
              $rowadv=DB_fetch_array($result);
            $adv=$rowadv[0];                                                        
                       
       echo '<center><br /><h3><b><a href=bio_repaypdf.php?adv_id='.$adv.'>'. _('Print this payment').'</a></b></h3><br /><br /></center>';  
       
   }
   //----------------------------------------------------------------------------------------------------------
    $office=$_SESSION['UserStockLocation'];
    echo"<table><tr id='panel'><td>";
    echo'<td>';
   echo"<input type='hidden' name='stop' id='stop' value='3'>";  
echo"<fieldset style='width:380px;height:150px'>"; 
echo"<legend>Customer Details</legend>";
echo"</legend>";
echo"<table width=100%>";
    echo "<table><tr><td width=50%>Customer Name</td>";
echo"<td><input type='hidden' name='custname' id='custname' value='$cname'>$cname</td></tr>";
echo"<tr></tr>";
echo"<tr><td>Customer Phone</td>";
echo"<td><input type='hidden' name='custph' id='custph' value='$cph'>$cph</td></tr>";
echo"<tr></tr>";
echo"<tr><td>Customer email</td>";
echo"<td><input type='hidden' name='email' id='email' value='$email'>$email</td></tr>";
echo"<tr></tr>";
echo"<tr><td>Customer Place</td>";
echo"<td><input type='hidden' name='custplace' id='custplace' value='$place'>$place</td></tr>";
echo"<tr></tr>";
echo"<tr><td>Customer District</td>";
echo"<td><input type='hidden' name='custdist' id='custdist' value='$district'>$district</td></tr>";
echo"<tr></tr>";
echo"<tr><td>Advance amount</td>";
echo"<td><input type='hidden' name='custplace' id='custplace' value='$place'>$place</td></tr>";
echo"<tr></tr>";
echo"</table>";
echo"</fieldset>";
echo'</div>';
echo"</td>";
    echo'<td valign=top>';    
echo'<div id=right_panel_1>';
echo"<fieldset style='width:380px;height:150px; overflow:auto;'>";
echo"<legend>Payment Details</legend>";
echo"<table>";
echo"<tr><td>Repay Amount*</td>";
echo"<td><input type='text' name='advanceamount' id='advanceamount' style='width:150px'></td></tr>";

echo'<tr>';
echo'<td width=50%>Mode of payment:</td>';
echo'<td><select name="mode" id="paymentmode" style="width:150px" onchange="advdetail(this.value)">';
$sql1="SELECT * FROM bio_paymentmodes";
$result1=DB_query($sql1, $db); 
$f=0;
while($myrow1=DB_fetch_array($result1))
{
    if ($myrow1['id']==$_POST['modes'])
    {
        echo '<option selected value="';
    } else 
    {
        if ($f==0) 
        {
            echo '<option>';
        }
        echo '<option value="';
        $f++;
    }
    echo $myrow1['id'] . '">'.$myrow1['modes'];  
    echo '</option>';
  }
echo'</select></td></tr><tr>';
echo"<table id='modeamt'>";
echo"</table>"; 
echo"<table id='amt'>";
echo"</table>"; 

echo"</table>";

 echo '<center><input type="submit" name="submit" value="submit" onclick="if(valid()==1){return false;}"></center>';
  

echo'<div id="leadgrid">';
echo"<fieldset style='width:760px'><legend>Lead Details</legend>";

echo"<table style='border:1px solid #F0F0F0;width:100%'>";
echo"<tr><td>Date From</td><td>Date To</td><td>Name</td><td>Place</td><td>Office</td><td>Lead Source</td></tr>"; 
echo"<tr>";
echo '<td><input type="text" style="width:100px" id="df1" class=date alt='.$_SESSION['DefaultDateFormat'].' name="df1" onChange="return isDate(this, this.value, '."'".$_SESSION['DefaultDateFormat']."'".')'.'"'.'></td>';  
echo '<td><input type="text" style="width:100px" id="dt1" class=date alt='.$_SESSION['DefaultDateFormat'].' name="dt1" onChange="return isDate(this, this.value, '."'".$_SESSION['DefaultDateFormat']."'".')'.'"'.'></td>';
echo"<td><input type='text' name='byname1' id='byname1'></td>";
echo"<td><input type='text' name='byplace1' id='byplace1'></td>";
echo '<td><select name="off1" id="off1" style="width:100px">';
echo '<option value=0></option>'; 
$sql1="select * from bio_office";
$result1=DB_query($sql1,$db);
while($row1=DB_fetch_array($result1))
{
  echo "<option value=$row1[id]>$row1[office]</option>";
}
echo '</select></td>';
echo '<td><select name="leadsrc1" id="leadsrc1" style="width:100px">';
echo '<option value=0></option>';
echo '<option value="ALL">Select ALL</option>';
$sql1="select * from bio_leadsources";
$result1=DB_query($sql1,$db);
while($row1=DB_fetch_array($result1))
{
  echo "<option value=$row1[id]>$row1[sourcename]</option>";
}                                                             
echo '</select></td>';      

echo"<td><input type='submit' name='filterbut1' id='filterbut1' value='search' onclick='if(log_in()==1)return false'></td>";
echo"</tr>";
echo"</table>";


echo "<div style='height:200px; overflow:scroll;'>"; 
echo"<table style='width:100%'> ";

echo"<tr><th>Slno</th><th>Leadid</th><th>Name</th><th>Place</th><th>Date</th><th>Enquiry Type</th><th>Status</th><th>Team</th></tr>";
$office=$_SESSION['UserStockLocation'];   
//---------------------------------------------------------------------------------------------------------------------------------------------------------
    $s_userid=$_SESSION['UserID'];
    $s_offid=$_SESSION['officeid'];
    
            $office_arr=array();
        $office_arr[]=$s_offid;
           
     $sql2="SELECT reporting_off,id
            FROM bio_office
            WHERE reporting_off=$s_offid
            ";
              
     $result2=DB_query($sql2,$db);
     $myrow_count = DB_num_rows($result2);
     
     if($myrow_count>0){
     while($row2=DB_fetch_array($result2)){
         $office_arr[]=$row2['id'];   
        
     $sql3="SELECT id,reporting_off 
                FROM bio_office
                WHERE reporting_off=".$row2['id']."";
        $result3=DB_query($sql3,$db);
        $myrow_count1 = DB_num_rows($result3);
     if($myrow_count1>0){
     while($row3=DB_fetch_array($result3)){
               $office_arr[]=$row3['id'];       
       
     $sql4="SELECT id,reporting_off 
                FROM bio_office
                WHERE reporting_off=".$row3['id']."";
        $result4=DB_query($sql4,$db);
        $myrow_count2 = DB_num_rows($result4);
     if($myrow_count2>0){
     while($row4=DB_fetch_array($result4)){
               $office_arr[]=$row4['id'];        
//         echo $row3['id'];
            
        }
        }  
        }   
     }
     }
     }
     
     $office_array=join(",", $office_arr);
     $sql5="SELECT *  
                FROM bio_emp
                WHERE offid IN ($office_array)";
     $result5=DB_query($sql5,$db);
     while($row5=DB_fetch_array($result5)){
//         $row5['empid'];
    
    $sql6="SELECT userid FROM www_users WHERE empid=".$row5['empid'];
    $result6=DB_query($sql6,$db);
    $row6=DB_fetch_array($result6);
    $userid[]="'".$row6[0]."'";     
    $user_array=join(",", $userid); 
               
     }                      
      
   $sql="SELECT bio_leads.leadid AS leadid,bio_advance.amount,
  bio_cust.cust_id AS custid,  
  bio_cust.custname AS custname,               
  bio_cust.area1 AS place,
  bio_leads.leaddate AS leaddate,
                      bio_status.biostatus,  


  bio_enquirytypes.enqtypeid AS enqtypeid,
  bio_enquirytypes.enquirytype AS enqtype, 
  bio_leadteams.teamname AS teamname,
  bio_leadsources.sourcename AS sourcename,
  bio_office.id AS officeid,
  bio_office.office AS office
FROM bio_cust,
bio_leads,
bio_leadteams,
bio_status,
bio_enquirytypes,
bio_leadsources,
bio_office,bio_advance  
WHERE bio_cust.cust_id=bio_leads.cust_id 
AND bio_leadteams.teamid=bio_leads.teamid 
AND bio_leads.enqtypeid=bio_enquirytypes.enqtypeid

              AND bio_status.statusid=bio_leads.leadstatus   AND   bio_leads.leadstatus not in(6,39)
AND bio_leadsources.id=bio_leads.sourceid
AND bio_office.id=bio_leadsources.officeid
and bio_leads.leadid=`bio_advance`.leadid  and   bio_advance.amount>0";

 if(isset($_POST['filterbut1']))
 {  
    if ((isset($_POST['df1'])) && (isset($_POST['dt1'])))   {
    if (($_POST['df1']!="") && ($_POST['dt1']!=""))  { 
    $sourcetypefrom=FormatDateForSQL($_POST['df1']);   
    $sourcetypeto=FormatDateForSQL($_POST['dt1']);
    $sql.=" AND bio_leads.leaddate BETWEEN '".$sourcetypefrom."' AND '".$sourcetypeto."'";
    }  }
    $officeid=$_POST['off1'];
  //  echo $officeid;
    if (isset($_POST['byname1']))  {        
    if ($_POST['byname1']!='')   
    $sql .= " AND bio_cust.custname LIKE '%".$_POST['byname1']."%'";   
    }                                                                    
            
    if (isset($_POST['byplace1'])) {
    if ($_POST['byplace1']!='') 
    $sql .= " AND bio_cust.area1 LIKE '%".$_POST['byplace1']."%'"; 
    }
    
    if (isset($_POST['off1']))    {
    if (($_POST['off1']!='')&&($_POST['off1']!='0'))
    $sql .=" AND bio_leadsources.officeid=".$officeid;    
    }
    
    if (isset($_POST['leadsrc1'])) {
    if (($_POST['leadsrc1']!='ALL') && ($_POST['leadsrc1']!=0))
    $sql .=" AND bio_leads.sourceid='".$_POST['leadsrc1']."'";
    }
 }
 $sql .=" ORDER BY leadid ASC";      
$result=DB_query($sql,$db);
echo '<tbody>';
echo '<tr>';                                       
$no=0; 
$k=0; 
while($myrow=DB_fetch_array($result))
{
    
    $no++;
    if ($k==1)
    {
        echo '<tr class="EvenTableRows">';
        $k=0;
    }else 
    {
        echo '<tr class="OddTableRows">';
//                    $k=1;     
    }
    $leadid=$myrow['leadid'];
    printf("<td cellpading=2>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td> 
            <td>%s</td> 
            <td><a  style='cursor:pointer;'  id='$leadid' onclick='passid(this.id)'>" . _('Select') . "</a></td></tr>",
            $no,
            $leadid,
            $myrow['custname'],
            $myrow['place'],
            ConvertSQLDate($myrow['leaddate']),
            $myrow['enqtype'],
            $myrow['biostatus'],
            $myrow['teamname']);
           
}
echo"</tr></tbody></table>";
echo"</div>";
echo"</fieldset>";  
echo'</div>';


?>
<script type="text/javascript">
function advdetail(str){
if (str=="")
  {
  document.getElementById("amt").innerHTML="";
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
    document.getElementById("amt").innerHTML=xmlhttp.responseText;

    }
  } 
xmlhttp.open("GET","bio_fsamountdetails.php?adv=" + str,true);
xmlhttp.send();    
}
function passid(str1,str2){
//alert(str1);
if (str1=="")
  {
  document.getElementById("panel").innerHTML="";
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
    document.getElementById("panel").innerHTML=xmlhttp.responseText;  
    }
  } 
xmlhttp.open("GET","bio_repayajax.php?lead=" + str1,true);
xmlhttp.send(); 
}
function advdetail(str){
if (str=="")
  {
  document.getElementById("amt").innerHTML="";
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
    document.getElementById("amt").innerHTML=xmlhttp.responseText;

    }
  } 
xmlhttp.open("GET","bio_fsamountdetails.php?adv=" + str,true);
xmlhttp.send();    
}

function valid()
{
         var y=document.getElementById('stop').value; 
         if(y==3)
          {
          alert("Please select a lead") ;
              return 1;     
          } 
  var  str1=document.getElementById('advanceamount').value ;
    str2=document.getElementById('firstamount').value;
    str3=document.getElementById('paymentmode').value;

        h=str1-str2;
    if(str1=="" || str1==null)
    {
        alert ("please enter amount");
        document.getElementById('advanceamount').focus();
        return 1;
    }
       if(str3=="" || str3==null)
    {
        alert ("please enter mode of payment");
        document.getElementById('paymentmode').focus();
        return 1;
    
    }
             if(h>0)
    {
        alert ("Repay amount is greater than already recieved amount");
                 document.getElementById('advanceamount').focus();
        return 1;
        
    }
  

}
</script>