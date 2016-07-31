<?php

$PageSecurity = 80;
include('includes/session.inc');
$lead_id=$_GET['leadid'];
 


echo'<div id=feedstockdetails>';
echo "<fieldset style='width:835px'>";   
echo "<legend><h3>Feed Stock Details</h3>";
echo "</legend>"; 
    
echo "<table style='align:left' border=0>";

echo "<tr><td>&nbspInstitution Type</td>";
echo '<td><select name="inst" id="inst"  style="width:130px" onchange="showFeedstocksource(this.value)">';
    
    $sql="SELECT * FROM bio_institution ORDER BY institution_name";
    $result=DB_query($sql,$db);
    echo'<option value=0></option>';
    while($row=DB_fetch_array($result))
    {
        if ($row['inst_id']==$_POST['inst'])
        {
            echo '<option selected value="';
        } else {
            echo '<option value="';
        }
        echo $row['inst_id'] . '">'.$row['institution_name'];
        echo '</option>';
    }
    echo'</select></td>';


  
  echo "<td>&nbspFeed Stock Source</td>"; //Feedstock
  echo '<td id="showfeedsource">';

 $sql1="SELECT bio_fssources.id,
               bio_fssources.source
          FROM bio_feedstocksources,
               bio_fssources
         WHERE bio_feedstocksources.feedstocksourcename=bio_fssources.id
      GROUP BY feedstocksourcename 
      ORDER BY bio_fssources.source";
  
  $result1=DB_query($sql1, $db);
  
  echo '<select name="FeedSource" id="feedsource"  style="width:130px" onchange="showFeeds(this.value)">';
  $f=0;
  while($myrow1=DB_fetch_array($result1))
  {  
  if ($myrow1['id']==$_POST['FeedSource']) 
    {
    echo '<option selected value="';
    
    } else {
        if($f==0){echo '<option>';   }
        echo '<option value="';
    }
    echo $myrow1['id'] . '">'.$myrow1['source']; 
    echo '</option>' ;
   $f++; 
   }
  echo '</select>';
  echo "</td></tr>";
  
  echo "<tr><td>&nbspFeed Stock</td>"; 
  echo '<td id="feeds">';

  $sql1="SELECT * FROM bio_feedstocks ORDER BY feedstocks";
  $result1=DB_query($sql1, $db);
  
  echo '<select name="feedstock" id="feedstock" style="width:130px" onchange="showInputs(this.value)">';
  $f=0;
  while($myrow1=DB_fetch_array($result1))
  {  
  if ($myrow1['id']==$_POST['feedstock']) 
    {
    echo '<option selected value="';
    
    } else {
        if($f==0){echo '<option>';   }
        echo '<option value="';
    }
    echo $myrow1['id'] . '">'.$myrow1['feedstocks']; 
    echo '</option>' ;
   $f++; 
   }
  echo '</select>';
  echo "</td>";
  
  echo"<td colspan=2 id=showinputfields>";
  
  echo"</td>";
  
  
  /*
  echo "<td>&nbspNo: of sources</td>";
  echo "<td><input type=text name='NumSource' id='numsource' style='width:40px'></td>";
  
  echo "<td>&nbspWeight in Kg/Ltr</td>";
  echo "<td><input type=text name='Weight' id='weight' style='width:40px'></td>";
*/

  
//Feedstock
 
  
  //echo "<td>Weight in Kg/Ltr</td>";
//  echo "<td><input type=text name='weight' id='weight' style='width:35px'></td>";
  echo "<td>";
  
 echo '<input type="button" name="addfeedstock" id="addfeedstock" value="Add" onclick="showCD4()">';
//  echo '<input type="button" name="addfeedstock" id="addfeedstock" value=Add>';
  echo "</td>";
  
  echo "</tr>";
  
   
echo "</table>";



echo"<div id='editfdstok'>";
echo"</div>";

echo"<div id='feedstockdiv'>";

 $sql_dup="SELECT COUNT(*) FROM bio_feeddetailstemp";
 $result_dup=DB_query($sql_dup,$db);
 $myrow_dup=DB_fetch_array($result_dup);

 if($myrow_dup[0]>0){



$edeg=0;
$sdeg=0;
$vsdeg=0;
$nonedeg=0;
$swaste=0;
$wastewater=0;
$gasout=0;
$sql5="SELECT * FROM bio_feeddetailstemp";
$result5=DB_query($sql5,$db);
//echo"ccccc:".$count=DB_num_rows($result5);     
while($myrow5=DB_fetch_array($result5))     {
    $feedstock=$myrow5['feedstockid'];
    $feedstock_source=$myrow5['feedstocksourceid'];
    $no_of_source=$myrow5['number_source'];
    $weight=$myrow5['weight'];
    $gasout+=$myrow5['gas_out'];
    
    $sql_wtype="SELECT waste_category_id FROM bio_feedstocksources
                WHERE id=".$feedstock."
                AND feedstocksourcename=".$feedstock_source;
    $result_wtype=DB_query($sql_wtype,$db);
    $myrow_wtype=DB_fetch_array($result_wtype);
    $waste_type=$myrow_wtype['waste_category_id'];
    
    if($waste_type==1){
        $edeg=$edeg+$weight;
    }elseif($waste_type==2){
        $sdeg=$sdeg+$weight;
    }elseif($waste_type==3){
        $vsdeg=$vsdeg+$weight;
    }elseif($waste_type==4){
        $nonedeg=$nonedeg+$weight;
    }elseif($waste_type==5){
        $wastewater=$wastewater+$weight;
    }

    
}



echo"<table  style='width:65%;' border=0>";
echo"<tr style='background:#D50000;color:white'>";
echo"<td>Slno</td>
     <td>Feed Stock Source</td>
     <td>Feed Stock</td>
     <td>No of Sources</td>
     <td>Weight</td>
     <td>Gas Out</td></tr>";

$sql="SELECT bio_feedstocks.feedstocks,
             bio_feeddetailstemp.temp_id,
             bio_feeddetailstemp.weight,
             bio_feeddetailstemp.number_source,
             bio_feeddetailstemp.gas_out,
             bio_fssources.source 
        FROM bio_feeddetailstemp,bio_feedstocks,bio_fssources 
       WHERE bio_feedstocks.id=bio_feeddetailstemp.feedstockid
         AND bio_feeddetailstemp.feedstocksourceid=bio_fssources.id";
$result1=DB_query($sql, $db);    
$n=1;
while($myrow=DB_fetch_array($result1)){ //echo$myrow[1];
  echo "<tr style='background:#000080;color:white'>
        <td>$n</td>
        <td>$myrow[5]<input type='hidden' id='hfeedsource' value='$myrow[5]'></td>
        <td>$myrow[0]<input type='hidden' id='hfeedstock' value='$myrow[0]'></td>
        <td>$myrow[3]<input type='hidden' id='hfeedsourceno' value='$myrow[3]'></td>
        <td>$myrow[2]<input type='hidden' id='hfeedweight' value='$myrow[2]'></td>
        <td>$myrow[4]<input type='hidden' id='hfeedgasout' value='$myrow[4]'></td>
        <td><a style='cursor:pointer;color:white;' id='$myrow[1]' onclick='editfeedstok(this.id)'>Edit</a ></td>
        <td><a style='cursor:pointer;color:white' id='$myrow[1]' onclick='deletfeedstok(this.id)'>Delete</a></td></tr>";
 $n++; 
  
}
echo"</table>";   
echo "<table  style='width:65%;'><tr style='background:#000080;color:white' id='editfeed'></tr></table>";




echo"</table>";
}
echo"<br />";
echo"<br />";
echo"<table width=100% border=1>";
echo"<tr align=center>";
echo"<td width=20%>Easily Degradable</td>";
echo"<td width=20%>Slowly Degradable</td>";
echo"<td width=20%>Very slow Degradable</td>";
echo"<td width=20%>Waste Water</td>";
echo"<td width=20%>Total Gas out</td>";
echo"</tr>";

echo"<tr>";
echo"<td width=20%>Actual&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp:<input type='text' name='edegradable' id='edegradable' value='".$edeg."' style=width:75px readonly>&nbspKg</td>";
echo"<td width=20%>Actual&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp:<input type='text' name='sdegradable' id='sedegradable' value='".$sdeg."' style=width:75px readonly>&nbspKg</td>";
echo"<td width=20%>Actual&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp:<input type='text' name='vsdegradable' id='vsedegradable' value='".$vsdeg."' style=width:75px readonly>&nbspKg</td>";
echo"<input type='hidden' name='nondegradable' id='nonedegradable' value='".$nonedeg."' style=width:75px>";
echo"<td width=20%>Actual&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp:<input type='text' name='WasteWater' id='wastewater' value='".$wastewater."' style=width:75px readonly>&nbspLtr</td>";
echo"<td width=20%>Actual&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp:<input type='text' name='TotalGasout' id='totalgasout' value='".$gasout."' style=width:69px readonly>Cum</td>";
echo"<input type='hidden' name='SolidWaste' id='solidwaste' value='".$swaste."' style=width:165px>";

echo"</tr>";

echo"<tr>";
echo"<td width=20%>Projected:<input type='text' name='prjedegradable' id='prjedegradable'  style=width:75px>&nbspKg</td>";
echo"<td width=20%>Projected:<input type='text' name='prjsdegradable' id='prjsedegradable'  style=width:75px>&nbspKg</td>";
echo"<td width=20%>Projected:<input type='text' name='prjvsdegradable' id='prjvsedegradable'  style=width:75px>&nbspKg</td>";
echo"<input type='hidden' name='prjnondegradable' id='prjnonedegradable'  style=width:75px>";
echo"<td width=20%>Projected:<input type='text' name='prjWasteWater' id='prjwastewater'  style=width:75px>&nbspLtr</td>";
echo"<td width=20%>Projected:<input type='text' name='prjTotalGasout' id='prjtotalgasout'  style=width:70px>Cum</td>";
echo"<input type='hidden' name='prjSolidWaste' id='prjsolidwaste'  style=width:165px>";

echo"</tr>";

echo"</table>";

echo"</div>";
echo"<div id='feedstockdiv'></div>"; 
echo"<br />"; 

echo"</fieldset>";
 
?>
