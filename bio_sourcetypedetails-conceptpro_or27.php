<?php
  
$PageSecurity = 80;  
include('includes/session.inc');

if(isset($_GET['feedsource'])){  
       
if($_GET['feedsource']==""){$_GET['feedsource']=0;}
if($_GET['numsource']==""){$_GET['numsource']=0;}
if($_GET['feedstock']==""){$_GET['feedstock']=0;}
if($_GET['weight']==""){$_GET['weight']=0;}
if($_GET['unit']==""){$_GET['unit']=0;}

$fstock_source=$_GET['feedsource'];
$no_source=$_GET['numsource'];
$fstock=$_GET['feedstock'];
$feedstock_weight=$_GET['weight'];
$unit=$_GET['unit'];

$sql_dup="SELECT COUNT(*) FROM bio_feeddetailstemp
            WHERE feedstockid=$fstock
            AND feedstocksourceid=$fstock_source";
$result_dup=DB_query($sql_dup,$db);
$myrow_dup=DB_fetch_array($result_dup);
if($myrow_dup[0]==0){
$sql9="SELECT * FROM bio_feedstocksources
                 WHERE feedstocksourcename='".$fstock_source."'
                 AND id=$fstock"; 
$result9=DB_query($sql9,$db);
$myrow9=DB_fetch_array($result9);


if($unit==1){
    $weight=$feedstock_weight;
    $gasout=ceil($weight/$myrow9['quantitativedetails']); 
}elseif($unit==2){
    $weight=$feedstock_weight;
    $gasout=ceil($weight/$myrow9['quantitativedetails']); 
}else{
    $weight=$no_source*$myrow9['generatingamount'];
    $gasout=ceil($no_source/$myrow9['quantitativedetails']);
    
}

$sql="INSERT INTO bio_feeddetailstemp(feedstockid,
                               weight,
                               feedstocksourceid,
                               number_source,
                               gas_out) 
                    VALUES ('".$fstock."',
                            '".$weight."',
                            '".$fstock_source."',
                            '".$no_source."',
                            '".$gasout."')";
  
$ErrMsg =  _('An error occurred inserting the new Sales Leads record because');
$Dbgmsg =  _('The SQL used to insert the Sales Leads record was');
$result = DB_query($sql,$db,$ErrMsg,$DbgMsg);
//  prnMsg( _('The Feedstock record has been added'),'success');  
$tempflg=DB_Last_Insert_ID($Conn,'bio_feeddetailstemp','temp_id');        
echo"<input type='hidden' id='hidenfeedstock' value='".$tempflg."'>"; 
}

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
  
}echo"</table>";   echo "<table  style='width:65%;'><tr style='background:#000080;color:white' id='editfeed'></tr></table>";
echo"<br />";
echo"<br />";
echo"<table width=100% border=1>";
echo"<tr align=center>";
echo"<td width=20%>Easily Degradable</td>";
echo"<td width=20%>Slowly Degradable</td>";
echo"<td width=20%>Non Degradable</td>";
echo"<td width=20%>Waste Water</td>";
echo"<td width=20%>Total Gas out</td>";
echo"</tr>";

/*echo"<tr>";
echo"<td width=20%><input type='text' name='edegradable' id='edegradable' value='".$edeg."' style=width:100px>&nbspKg</td>";
echo"<td width=20%><input type='text' name='sdegradable' id='sedegradable' value='".$sdeg."' style=width:100px>&nbspKg</td>";
echo"<td width=20%><input type='text' name='vsdegradable' id='vsedegradable' value='".$vsdeg."' style=width:100px>&nbspKg</td>";
echo"<td width=20%><input type='text' name='nondegradable' id='nonedegradable' value='".$nonedeg."' style=width:100px>&nbspKg</td>";
echo"<td width=20%><input type='text' name='WasteWater' id='wastewater' value='".$wastewater."' style=width:100px>&nbspLtr</td>";
echo"<input type='hidden' name='SolidWaste' id='solidwaste' value='".$swaste."' style=width:165px>";

echo"</tr>";*/


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



//echo"<br />";    
}

if(isset($_GET['upfeedstockid'])|| $_GET['upfeedstockid']!=""){ 

 $feedstockid=$_GET['upfeedstockid'];
$sql="SELECT bio_feedstocks.feedstocks,bio_feeddetailstemp.weight,bio_feeddetailstemp.temp_id,bio_feeddetailstemp.feedstockid  
FROM bio_feeddetailstemp,bio_feedstocks WHERE bio_feedstocks.id=bio_feeddetailstemp.feedstockid AND temp_id=$feedstockid";

/*$sql="SELECT bio_feedstocks.feedstocks,
             bio_feeddetailstemp.temp_id,
             bio_feeddetailstemp.weight,
             bio_feeddetailstemp.number_source,
             bio_feedstocksources.feedstocksourcename,
             bio_feedstocksources.feedstocksourceid,
             bio_feeddetailstemp.gas_out,
             bio_fssources.source,
             bio_feedstocksources.feedstock_unit   
        FROM bio_feeddetailstemp,bio_feedstocks,bio_feedstocksources,bio_fssources 
        WHERE bio_feedstocks.id=bio_feeddetailstemp.feedstockid
         AND bio_feeddetailstemp.feedstocksourceid=bio_feedstocksources.feedstocksourcename
         AND bio_feeddetailstemp.temp_id=$feedstockid
         AND bio_feeddetailstemp.feedstocksourceid=bio_fssources.id";*/
$sql="SELECT bio_feedstocks.feedstocks, 
             bio_feeddetailstemp.weight, 
             bio_fssources.source, 
             bio_feeddetailstemp.number_source, 
             bio_feedstocksources.feedstock_unit,
             bio_feeddetailstemp.feedstocksourceid,
             bio_feeddetailstemp.feedstockid
        FROM bio_feeddetailstemp, bio_feedstocks, bio_fssources, bio_feedstocksources
       WHERE bio_feeddetailstemp.temp_id =$feedstockid
         AND bio_feeddetailstemp.feedstockid = bio_feedstocks.id
         AND bio_fssources.id = bio_feeddetailstemp.feedstocksourceid
         AND bio_feedstocksources.id = bio_feeddetailstemp.feedstockid
         AND bio_feedstocksources.feedstocksourcename = bio_feeddetailstemp.feedstocksourceid";
 
$result1=DB_query($sql,$db);
 while($myrow=DB_fetch_array($result1)){
 echo"<td colspan=2><input type='hidden' id='sourceid' name='Sourceid' value='".$myrow['feedstocksourceid']."'>".$myrow['source']."</td>
      <td><input type='hidden' id='h1feedstock' name='h1feedstock' value='".$myrow['feedstockid']."'>".$myrow['feedstocks']."
      <input type='hidden' id='tempid' name='editfeedstockid' value='".$feedstockid."'>
      <input type='hidden' id='unitid' name='feedstockunit' value='".$myrow['feedstock_unit']."'></td>";
      if($myrow['feedstock_unit']==1 OR $myrow['feedstock_unit']==2){
         echo"<td><input type='text' id='h1feedswt' style='width:90px' name='h1feedswt' value=".$myrow['weight'].">&nbspKg/Ltr</td>"; 
      }else{
         echo"<td><input type='text' id='h1feedsno' style='width:90px' name='h1feedsno' value=".$myrow['number_source'].">&nbspNos</td>"; 
      }
      
      echo"<td><input type='button' id='updatefeeds' name='upfeedstcks1' value='edit' onclick='doedit()'></td>
";
 }
       
}


if(isset($_GET['edid'])){
 $eid=$_GET['edid'];
 $source=$_GET['source'];
 $feedstock=$_GET['feedstock'];
 $feedunit=$_GET['feedunit'];
 $feedstock_weight=$_GET['weight'];
 $numsource=$_GET['numsource'];
 
 $sql9="SELECT * FROM bio_feedstocksources
                 WHERE feedstocksourcename='".$source."'
                 AND id=$feedstock"; 
$result9=DB_query($sql9,$db);
$myrow9=DB_fetch_array($result9);


if($feedunit==1){
    $weight=$feedstock_weight;
    $gasout=ceil($weight/$myrow9['quantitativedetails']);
    $numsource=0; 
}elseif($feedunit==2){
    $weight=$feedstock_weight;
    $gasout=ceil($weight/$myrow9['quantitativedetails']); 
    $numsource=0;
}else{
    $weight=$numsource*$myrow9['generatingamount'];
    $gasout=ceil($numsource/$myrow9['quantitativedetails']);
    
}
 
 $sql="UPDATE bio_feeddetailstemp SET weight=$weight,
                                      number_source=$numsource,
                                      gas_out=$gasout 
                                WHERE temp_id=$eid";
 $result1=DB_query($sql, $db); 
 
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
  
}echo"</table>";   echo "<table  style='width:65%;'><tr style='background:#000080;color:white' id='editfeed'></tr></table>";
echo"<br />";
echo"<br />";
echo"<table width=100% border=1>";
echo"<tr align=center>";
echo"<td width=20%>Easily Degradable</td>";
echo"<td width=20%>Slowly Degradable</td>";
echo"<td width=20%>Non Degradable</td>";
echo"<td width=20%>Waste Water</td>";
echo"<td width=20%>Total Gas out</td>";
echo"</tr>";

/*echo"<tr>";
echo"<td width=20%><input type='text' name='edegradable' id='edegradable' value='".$edeg."' style=width:100px>&nbspKg</td>";
echo"<td width=20%><input type='text' name='sdegradable' id='sedegradable' value='".$sdeg."' style=width:100px>&nbspKg</td>";
echo"<td width=20%><input type='text' name='vsdegradable' id='vsedegradable' value='".$vsdeg."' style=width:100px>&nbspKg</td>";
echo"<td width=20%><input type='text' name='nondegradable' id='nonedegradable' value='".$nonedeg."' style=width:100px>&nbspKg</td>";
echo"<td width=20%><input type='text' name='WasteWater' id='wastewater' value='".$wastewater."' style=width:100px>&nbspLtr</td>";
echo"<input type='hidden' name='SolidWaste' id='solidwaste' value='".$swaste."' style=width:165px>";

echo"</tr>";*/


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




}

if(isset($_GET['delet'])){
    $feedstockid=$_GET['delet'];
    $sql="DELETE FROM `bio_feeddetailstemp` WHERE `bio_feeddetailstemp`.`temp_id` = $feedstockid ";
    $result1=DB_query($sql, $db); 
    
    
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
  
}echo"</table>";   echo "<table  style='width:65%;'><tr style='background:#000080;color:white' id='editfeed'></tr></table>";
echo"<br />";
echo"<br />";
echo"<table width=100% border=1>";
echo"<tr align=center>";
echo"<td width=20%>Easily Degradable</td>";
echo"<td width=20%>Slowly Degradable</td>";
echo"<td width=20%>Non Degradable</td>";
echo"<td width=20%>Waste Water</td>";
echo"<td width=20%>Total Gas out</td>";
echo"</tr>";

/*echo"<tr>";
echo"<td width=20%><input type='text' name='edegradable' id='edegradable' value='".$edeg."' style=width:100px>&nbspKg</td>";
echo"<td width=20%><input type='text' name='sdegradable' id='sedegradable' value='".$sdeg."' style=width:100px>&nbspKg</td>";
echo"<td width=20%><input type='text' name='vsdegradable' id='vsedegradable' value='".$vsdeg."' style=width:100px>&nbspKg</td>";
echo"<td width=20%><input type='text' name='nondegradable' id='nonedegradable' value='".$nonedeg."' style=width:100px>&nbspKg</td>";
echo"<td width=20%><input type='text' name='WasteWater' id='wastewater' value='".$wastewater."' style=width:100px>&nbspLtr</td>";
echo"<input type='hidden' name='SolidWaste' id='solidwaste' value='".$swaste."' style=width:165px>";

echo"</tr>";*/


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
    
    
    
} 


?>