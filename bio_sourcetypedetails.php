<?php
$PageSecurity = 80;  
include('includes/session.inc'); 
 if(isset($_GET['q'])){
  $sourceid=$_GET['q'] ;
    $_SESSION['sourceid']=$sourceid;

//echo  $sourceid;
if($_GET['sd']==14)
{
    echo "<td><label style:color=red><font color='green'>Staff selected successfully</font> </label></td>";
}  
elseif($_GET['sd']==15)
{ echo "<td><label style:color=red><font color='green'>Dealer selected successfully</font> </label></td>";
} 
elseif($_GET['sd']==16)
{

    echo "<table border=0 style='width:100%' id=hidetable><tr>";
    $sql_getnw="SELECT bio_network_cust.cust_name,bio_network_cust.phoneno,
    bio_network_cust.mobileno,bio_district.district  FROM bio_network_cust,bio_district
    WHERE bio_district.cid=bio_network_cust.nationality     
    AND bio_district.stateid=bio_network_cust.state     
    AND bio_district.did=bio_network_cust.district
    AND bio_network_cust.id='".$sourceid."'";
    $result_get_nw=DB_query($sql_getnw,$db);
    $row_nw=DB_fetch_array($result_get_nw);

echo '<tr><td>Name</td>';
    echo '<td>';
        echo "<input type=text  readonly style='width:85%' value='".$row_nw['cust_name']."'>";
    echo '</td></tr>';
    echo '<tr><td>Contact No.</td>';
    echo '<td>';
        echo "<input type=text  readonly style='width:85%' value='".$row_nw['phoneno']."'>";
    echo '</td></tr>';
    echo '<tr><td>District</td>';
    echo '<td>';
        echo "<input type=text  readonly style='width:85%' value='".$row_nw['district']."'>";
    echo '</td></tr>';
    echo '</td></tr>' ;
echo '</table>';
echo '</tr>' ; 
/* echo "<td><label style:color=red><font color='green'>Network Staff selected successfully</font> </label></td>";*/
} 
else{
    echo '<tr>';

   echo "<table border=0 style='width:100%' id=hidetable><tr>";

   echo "<td>office</td>";
   
   $sql="SELECT bio_leadsources.officeid, bio_leadsources.teamid, bio_leadsources.costcentreid
   FROM `bio_leadsources`
   WHERE bio_leadsources.id =".$sourceid;
   $result=DB_query($sql,$db) ;
   $myrow = DB_fetch_array($result);
   $flag=1;
  // if($flag==1)
  // {
      $_SESSION['teamid']=$myrow['teamid']; 
   //}
   
   $count = DB_fetch_row($result);
   $sql2="SELECT office FROM bio_office where bio_office.id=".$myrow['officeid'];
    $result2=DB_query($sql2,$db);
    $myrow2=DB_fetch_array($result2);
    echo "<td style='width:70%'>";
    echo "<input type=text name=office style='width:85%' readonly value='".$myrow2['office']."'>";
    echo '</td></tr>';
    echo '<tr><td>Team</td>';
    echo '<td>';
    $sql2="SELECT teamname FROM bio_leadteams where bio_leadteams.teamid='".$myrow['teamid']."'";
    $result2=DB_query($sql2,$db);
    $myrow2=DB_fetch_array($result2);
    echo "<input type=text name=team readonly style='width:85%' value='".$myrow2['teamname']."'>";
    echo '</td></tr>';
    echo '<tr><td>Cost Centers</td>';
    echo '<td>';
    $sql2="SELECT costcentre FROM bio_costcentres where bio_costcentres.costcentreid='".$myrow['costcentreid']."'";
    $result2=DB_query($sql2,$db);
    $myrow2=DB_fetch_array($result2);
    echo "<input type=text name=costcentre readonly style='width:85%' value='".$myrow2['costcentre']."'>";
    echo '</td></tr>';
    $sql5="SELECT bio_leadsources.id, bio_sourcedetails.propertyvalue, bio_sourceproperty.property, bio_leadsourcetypes.id
            FROM `bio_leadsources` , bio_sourcedetails, bio_sourceproperty, bio_leadsourcetypes
            WHERE bio_leadsources.id = bio_leadsourcetypes.id
            AND bio_sourceproperty.sourcetypeid = bio_leadsources.id
            AND bio_sourceproperty.sourcepropertyid = bio_sourcedetails.sourcepropertyid
            AND bio_sourcedetails.sourceid =".$sourceid;
  $result5=DB_query($sql5,$db) ;
   while($myrow5=DB_fetch_array($result5))
   {
       echo "<tr><td>".$myrow5[2]."</td>";
       echo "<td>".$myrow5[1]."</td>";
       echo '</tr>';
   }

echo '</td></tr>' ;
echo '</table>';
echo '</tr>' ;  
} 
 }





if(isset($_GET['feedstock'])){  //exit;
        //echo $_GET['feedstock'];
//        echo "aaaaaaa";

if($_GET['feedstock']==""){$_GET['feedstock']=0;}
if($_GET['weight']==""){$_GET['weight']=0;}   
$sql="INSERT INTO bio_feedtemp(bio_feedtemp.feedstockid,bio_feedtemp.weight
) VALUES ('".$_GET['feedstock']."','".$_GET['weight']."')";
  //$result=DB_query($sql, $db);  
//  exit;
             $ErrMsg =  _('An error occurred inserting the new Sales Leads record because');
           $Dbgmsg =  _('The SQL used to insert the Sales Leads record was');
           $result = DB_query($sql,$db,$ErrMsg,$DbgMsg);
//  prnMsg( _('The Feedstock record has been added'),'success');  
   $tempflg=DB_Last_Insert_ID($Conn,'bio_feedtemp','temp_id');        
echo"<input type='hidden' id='hidenfeedstock' value='".$tempflg."'>";

echo"<table  style='width:65%;' border=0><tr style='background:#D50000;color:white'><td>Slno</td><td>Feed Stock</td><td>Weight</td></tr>";
$sql="SELECT bio_feedstocks.feedstocks,bio_feedtemp.weight,bio_feedtemp.temp_id 
FROM bio_feedtemp,bio_feedstocks WHERE bio_feedstocks.id=bio_feedtemp.feedstockid";
$result1=DB_query($sql, $db);    
$n=1;
while($myrow=DB_fetch_array($result1)){ //echo$myrow[1];
  echo "<tr style='background:#000080;color:white'>
  <td>$n</td><td>$myrow[0]<input type='hidden' id='hfeedstock' value='$myrow[0]'></td>
  <td>$myrow[1]<input type='hidden' id='hfeedweight' value='$myrow[1]'></td>
  <td><a style='cursor:pointer;color:white;' id='$myrow[2]' onclick='editfeedstok(this.id)'>Edit</a ></td>
  <td><a style='cursor:pointer;color:white' id='$myrow[2]' onclick='deletfeedstok(this.id)'>Delete</a></td></tr>";
 $n++; 
  
}echo"</table>";   echo "<table  style='width:65%;'><tr style='background:#000080;color:white' id='editfeed'></tr></table>";    
}



if(isset($_GET['upfeedstockid'])|| $_GET['upfeedstockid']!=""){ 
//echo "bbbbbbb";
 $feedstockid=$_GET['upfeedstockid'];
$sql="SELECT bio_feedstocks.feedstocks,bio_feedtemp.weight,bio_feedtemp.temp_id,bio_feedtemp.feedstockid  
FROM bio_feedtemp,bio_feedstocks WHERE bio_feedstocks.id=bio_feedtemp.feedstockid AND temp_id=$feedstockid"; 
$result1=DB_query($sql, $db);
 while($myrow=DB_fetch_array($result1)){
 echo" <td>Edit</td><td colspan='2'><input type='hidden' id='h1feedstock' name='h1feedstock'value='".$myrow[0]."'>$myrow[0]</td>
  <td><input type='text' id='h1feedweight' style='width:90px' name='h1feedweight' value=$myrow[1]>
  </td><input type='hidden' id='fdstk' name='editfeedstockid'value='".$myrow[2]."'> 
  <td><input type='button' id='updatefeeds' name='upfeedstcks1' value='edit' onclick='doedit()'></td>
";
 }
//  if(isset($_POST['upfeedstcks1'])){ echo"sssssssssssssssssssss".$_POST['h1feedstock'];exit;}         
}




if(isset($_GET['edid'])){ 
 $weight=$_GET['edwt']; $eid=$_GET['edid'];
$sql="UPDATE bio_feedtemp SET weight=$weight WHERE temp_id=$eid";
$result1=DB_query($sql, $db); 

echo"<table  style='width:65%;' border=0><tr style='background:#D50000;color:white'><td>Slno</td><td>Feed Stock</td><td>Weight</td></tr>";
$sql="SELECT bio_feedstocks.feedstocks,bio_feedtemp.weight,bio_feedtemp.temp_id 
FROM bio_feedtemp,bio_feedstocks WHERE bio_feedstocks.id=bio_feedtemp.feedstockid";
$result1=DB_query($sql, $db);    
$n=1;
while($myrow=DB_fetch_array($result1)){ //echo$myrow[1];
   echo "<tr style='background:#000080;color:white'>    
  <td>$n</td><td>$myrow[0]<input type='hidden' id='hfeedstock' value='$myrow[0]'></td>
  <td>$myrow[1]<input type='hidden' id='hfeedweight' value='$myrow[1]'></td>
  <td><a style='cursor:pointer;color:white' id='$myrow[2]' onclick='editfeedstok(this.id)'>Edit</a ></td>
  <td><a style='cursor:pointer;color:white' id='$myrow[2]' onclick='deletfeedstok(this.id)'>Delete</a></td></tr>";
 $n++; 
  
}echo"</table>"; echo "<table  style='width:65%;'><tr style='background:#000080;color:white' id='editfeed'></tr></table>";

}



if(isset($_GET['delet'])){ 
//echo "bbbbbbb";
 $feedstockid=$_GET['delet'];
$sql="DELETE FROM `bio_feedtemp` WHERE `bio_feedtemp`.`temp_id` = $feedstockid ";
$result1=DB_query($sql, $db); 
echo"<table  style='width:65%;' border=0><tr style='background:#D50000;color:white'><td>Slno</td><td>Feed Stock</td><td>Weight</td></tr>";
$sql="SELECT bio_feedstocks.feedstocks,bio_feedtemp.weight,bio_feedtemp.temp_id 
FROM bio_feedtemp,bio_feedstocks WHERE bio_feedstocks.id=bio_feedtemp.feedstockid";
$result1=DB_query($sql, $db);    
$n=1;
while($myrow=DB_fetch_array($result1)){ //echo$myrow[1];
  echo "<tr style='background:#000080;color:white'>
  <td>$n</td><td>$myrow[0]<input type='hidden' id='hfeedstock' value='$myrow[0]'></td>
  <td>$myrow[1]<input type='hidden' id='hfeedweight' value='$myrow[1]'></td>
  <td><a style='cursor:pointer;color:white;' id='$myrow[2]' onclick='editfeedstok(this.id)'>Edit</a ></td>
  <td><a style='cursor:pointer;color:white' id='$myrow[2]' onclick='deletfeedstok(this.id)'>Delete</a></td></tr>";
 $n++; 
  
}echo"</table>"; 
}   

//---------------------------------------------------------------------------------------------------------------------------------
if(isset($_GET['ledid'])){ $feed=$_GET['fed'];
$lead=$_GET['ledid'];
$sql="SELECT bio_feedstocks.feedstocks,bio_leadfeedstocks.weight,bio_leadfeedstocks.leadid,bio_leadfeedstocks.feedstockid
FROM bio_leadfeedstocks,bio_feedstocks WHERE bio_feedstocks.id=bio_leadfeedstocks.feedstockid AND leadid=$lead AND feedstockid=$feed";
$result1=DB_query($sql, $db);
 while($myrow=DB_fetch_array($result1)){
 echo" <tr style='background:#000080;color:white'><td>Edit</td><td><input type='hidden' id='feedleadid' name='feedleadid'value='".$myrow[2]."'>$myrow[0]</td>
  <td><input type='text' id='fedwt' style='width:90px' name='h1feedweight' value=$myrow[1]>
  </td> <input type='hidden' id='biofeedstockid' name='biofeedstockid' value='".$myrow[3]."'>
  <td><input type='button' id='updatefeeds' name='upfeedstcks1' value='edit' onclick='dofeeedit()'></td></tr>";
 }
//  if(isset($_POST['upfeedstcks1'])){ echo"sssssssssssssssssssss".$_POST['h1feedstock'];exit;}
}


if(isset($_GET['ediled'])){
 $weight=$_GET['fedwt']; $eid=$_GET['fedidedi'];$ediled=$_GET['ediled'];
 $sql="UPDATE bio_leadfeedstocks SET weight=$weight WHERE leadid=$ediled AND feedstockid=$eid";
$result1=DB_query($sql, $db);
echo"<tr style='background:#D50000;color:white'><td>Slno:</td><td>Feedstock</td><td>Weight</td><td>Update</td></tr>";

$sql="SELECT bio_feedstocks.feedstocks,bio_leadfeedstocks.weight,bio_leadfeedstocks.feedstockid,bio_leadfeedstocks.leadid
FROM bio_leadfeedstocks,bio_feedstocks WHERE bio_feedstocks.id=bio_leadfeedstocks.feedstockid AND bio_leadfeedstocks.leadid=$ediled";
$result1=DB_query($sql, $db);
$n=1;
while($myrow=DB_fetch_array($result1)){ //echo$myrow[1];
   echo "
  <tr style='background:#000080;color:white'><td>$n</td>
  <td>$myrow[0]<input type='hidden' id='hfeedstock' value='$myrow[0]'></td>
  <td>$myrow[1]<input type='hidden' id='hfeedweight' value='$myrow[1]'></td>
  <td><a style='cursor:pointer;color:white' id='$myrow[2]' name='$myrow[3]' onclick='feedupdte1(this.id,this.name)'>Edit</a ></td></tr>
";   //echo "UPDATE bio_leadfeedstocks SET weight= WHERE leadid=5 AND feedstockid=2";


 $n++;

}
echo"<tr id='edittedsho'></tr>";
}

?>


