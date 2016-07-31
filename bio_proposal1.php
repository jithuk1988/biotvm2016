<?php
$PageSecurity = 80;
include('includes/session.inc');
$title = _('Proposal');
include('includes/header.inc'); 
$leadid= $_GET['lead'];    
if(isset($_POST['choose']))
 {   $lead=$_SESSION['lead'];
  $plantid='';
  $scheme=$_POST['schm'];  
                            foreach($scheme as $id)  {
                             $plantid=$plantid.$id.",";

                                }

   $plantid=split(",", $plantid);
                            //$plantid[0];
     $count=count($plantid);   $count1=$count-2;
    $sql="SELECT stockcatproperties.stkcatpropid from stockcatproperties WHERE stockcatproperties.label='Price'";
    $result=DB_query($sql,$db);   
    $stkcatpropid=$myrow=DB_fetch_array($result);  
   for($i=0; $i<=$count1; $i++){       $i;
  $sql="SELECT stockitemproperties.stockid,
 stockitemproperties.value 
 FROM stockitemproperties 
 WHERE stockitemproperties.stockid='".$plantid[$i]."' 
 AND stockitemproperties.stkcatpropid=$stkcatpropid[0] 
 ORDER BY stkcatpropid";
$result=DB_query($sql,$db);
$myrow=DB_fetch_array($result);                       
$propid=$myrow[0];
 $propvalue=$myrow[1];    
$sql="INSERT INTO bio_proposals VALUES($lead,'0','".$propvalue."','".$propid."')";
$result=DB_query($sql,$db);                  
  


                              
  
   
   
   
   }
                     
$sql="UPDATE bio_leads SET leadstatus = '1' WHERE bio_leads.leadid =$lead";                              
$result=DB_query($sql,$db); 
 }

//$leadid= $_GET['lead'];
if($_GET['lead']!=''){     $flag=1;
  $sql="SELECT 
bio_leads.leadid,
date_format(bio_leads.leaddate,'%d/%m/%Y'),
bio_cust.area1,
bio_cust.custmob,
bio_cust.cust_id, 
bio_cust.custname,
bio_outputtypes.outputtype,
bio_enquirytypes.enquirytype,bio_leadteams.teamname      
FROM bio_leads,
bio_cust,bio_outputtypes,
bio_enquirytypes,
bio_leadteams 
WHERE 
bio_leads.cust_id=bio_cust.cust_id
AND bio_leads.outputtypeid=bio_outputtypes.outputtypeid 
AND bio_leads.enqtypeid=bio_enquirytypes.enqtypeid
AND bio_leads.teamid=bio_leadteams.teamid
AND bio_leads.leadid=$leadid";
      $result=DB_query($sql,$db);
     // $count=DB_fetch_row($result); 
     //print_r($count);
                                     
    $no=0; 

          $k=0; 
          while($myrow=DB_fetch_array($result))
          {    //echo $myrow[0]; 
          $custid=$myrow[4];    
            $name=$myrow[5];
            $mob=$myrow[3];
            $place=$myrow[2];
            $date=$myrow[1];     
                 }      

//  echo  $leadid;
  $sql1="SELECT 
bio_feedstocks.feedstocks,
bio_leadfeedstocks.feedstockid,
bio_leadfeedstocks.weight
FROM 
bio_feedstocks,
bio_leadfeedstocks
WHERE
bio_leadfeedstocks.feedstockid=bio_feedstocks.id
AND bio_leadfeedstocks.leadid=$leadid
AND bio_leadfeedstocks.status=0  
";             $result1=DB_query($sql1,$db); 






} 
      
echo "<table style='width:500px'>"; 
echo"<tr><td style='width:50%'>";
echo"<fieldset><legend>Customer</legend>";
echo"<table >";   
echo"<tr><td style='width:50%'>Cust id :</td><td><input type='text' name='custid' value='$custid' id='custid'></td></tr>"; 
echo"<tr><td>Name :</td><td><input type='text' name='custnam' value='$name' id='custnam'></td></tr>";
 echo"<tr><td>Mobile :</td><td><input type='text' name='mob' value='$mob' id='mob'></td></tr>";   
echo"<tr><td>Place :</td><td><input type='text' name='Place' value='$place' id='Place'></td></tr>";     
echo"<tr><td>Date :</td><td><input type='text' name='Date' value='$date' id='Date'></td></tr>";  echo"</fieldset>";



echo"</table>";
echo"</td>";
echo"<td style='width:50%;height:140px' valign=top>";
echo"<fieldset style='height:100%'><legend>Feadstock</legend>";
echo"<table id='fead' style='width:100%'>";if ($leadid!=""){
echo"<tr style='background:#969696;color:white'><td>Feadstock</td><td>Weight in kg</td></tr>";                 
       
          while($myrow1=DB_fetch_array($result1))
          {    
            echo"<tr id='$myrow1[1]' onclick='plantselect(this.id)' style='background:#C0C0FF;cursor:pointer'><td>$myrow1[0]</td><td>$myrow1[2]</td></tr>";  
                 } 
               if($flag==1){  echo"</tr><td><input type='button' id='$leadid' name='showproposals' value='Select' onclick='showproposals(this.id)'></td></tr>";  }}
                 echo"</table></fieldset>";
echo"</td></tr>";

echo"<tr><td colspan='2'>";
 echo "<form action=" . $_SERVER['PHP_SELF'] . "?" . SID ." method=post>"; 
 echo"<div id='grid'>";     
echo"<fieldset><legend>Live Leads</legend>";
echo"<div style='height:100px;overflow:scroll;'>";  

echo"<table> ";
echo"<tr><th>Slno</th><th>Name</th><th>Date</th><th>Output</th><th>Cust type</th><th>Team</th></tr>";
$sql="SELECT 
bio_leads.leadid,
bio_leads.leaddate,     
bio_leads.enqtypeid,
bio_leads.outputtypeid,
bio_leads.teamid,
bio_cust.custname,
bio_outputtypes.outputtype,
bio_enquirytypes.enquirytype,bio_leadteams.teamname      
FROM bio_leads,
bio_cust,bio_outputtypes,
bio_enquirytypes,
bio_leadteams 
WHERE 
bio_leads.cust_id=bio_cust.cust_id
AND bio_leads.outputtypeid=bio_outputtypes.outputtypeid 
AND bio_leads.enqtypeid=bio_enquirytypes.enqtypeid
AND bio_leads.leadstatus=0
AND bio_leads.teamid=bio_leadteams.teamid";
      $result=DB_query($sql,$db);
     // $count=DB_fetch_row($result); 
     //print_r($count);
    echo '<tbody>';
    echo '<tr>';                                       
    $no=0; 

          $k=0; 
          while($myrow=DB_fetch_array($result))
          {    //echo $myrow[0];    
          $no++;
               if ($k==1)
                {
                    echo '<tr class="EvenTableRows">';
                    $k=0;
                    
                }
                 else 
                 {
                    echo '<tr class="OddTableRows">';
                    $k=1;     
                 }
                    $leadid=$myrow[0];
                 printf("<td cellpading=2>%s</td>
        <td>%s</td>
        <td>%s</td>
        <td>%s</td>
        <td>%s</td>
         <td>%s</td>

         
        <td><a  style='cursor:pointer;'  id='$leadid' onclick='passid(this.id)'>" . _('Select') . "</a></td>  

        </tr>",
        
        $no,
        $myrow['custname'],
        ConvertSQLDate($myrow['leaddate']),
        $myrow[6],
        $myrow[7],
        $myrow[8],
        $myrow[4],
        $myrow[0]);
                 
                 
                 
          }
echo"</table>";

echo"</div></fieldset></div>";
echo"</td></tr>";
echo"</form>";  
echo"</table>"; 
?> 
<script>
function passid(str){
    
location.href="?lead=" +str;  
}

function showproposals(str){ //var a="#"+str;
//$(a).hide();
// alert(str); 
if (str=="")
  {
  document.getElementById("grid").innerHTML="";
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
    document.getElementById("grid").innerHTML=xmlhttp.responseText;

    }
  }
xmlhttp.open("GET","bio_showproposals.php?ledid=" + str,true);
xmlhttp.send();
}



//function countChecked(str) {
// var multipleValues =""; 
// multipleValues=multipleValues + "," + str;  
//alert(multipleValues);
//}
//countChecked();
//$(":checkbox").click(countChecked);
</script>
