<?php
  $PageSecurity = 11;

  include('includes/session.inc');

  $itemcode=$_GET['item'];  
  $season=$_GET['season'];
 
 $currentyear=date("Y");
  
  $sql="SELECT seasonname
      FROM m_sub_season
      WHERE season_sub_id=".$season;
  $result=DB_query($sql,$db);
  $myrow=DB_fetch_array($result);
  $seasonname=$myrow[0];
  
  
  $sql="SELECT m_season.season_id,
             m_season.season_sub_id
      FROM m_season
      WHERE m_season.start_eng_year='".$currentyear."'
      AND m_season.season_sub_id=".$season;
  $result=DB_query($sql,$db);
  $myrow=DB_fetch_array($result);
  $SeasonnameID=$myrow[1];
  $SeasonID=$myrow[0];

  
$sql4="SELECT description,
              units
       FROM stockmaster 
       WHERE stockid='".$itemcode."'";
$result4=DB_query($sql4,$db);
$myrow4=DB_fetch_array($result4);




$sql_wo="SELECT workorders.wo,
                workorders.startdate,
                workorders.requiredby,
                woitems.qtyreqd,
                status.status
        FROM workorders, woitems,wostatus,mrpdemands,status
        WHERE workorders.wo = woitems.wo
        AND woitems.stockid = '".$itemcode."'
        AND workorders.wo = wostatus.wono
        AND workorders.demandid=mrpdemands.demandid
        AND mrpdemands.season_id='".$SeasonID."'
        AND wostatus.statusid=status.statusid" ;
$result_wo=DB_query($sql_wo,$db);


//if(isset($_GET['view']))        {
//    
//if($_GET['view']==1)        {
//$where="WHERE womaterialrequest.wono=woitems.wo     AND
//              woitems.stockid='".$itemcode."'       AND
//              womaterialrequest.statusid=dev_srstatus.srstatusid";    
//    
//}    
//}else       {
//$where="WHERE womaterialrequest.statusid!=4         AND
//              womaterialrequest.wono=woitems.wo     AND
//              woitems.stockid='".$itemcode."'       AND
//              womaterialrequest.statusid=dev_srstatus.srstatusid";
//}

//$sql3="SELECT * 
//      FROM womaterialrequest,woitems,dev_srstatus
//      ".$where."";  
//$result3=DB_query($sql3,$db);






if($_GET['id']==1)           {
    
        if (!headers_sent()){
        header('Content-type: text/html; charset=' . _('utf-8'));
    }                                                                                                                  
    echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
            "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">';


    echo '<html xmlns="http://www.w3.org/1999/xhtml"><head><title>' . $title . '</title>';
    echo '<link rel="shortcut icon" href="'. $rootpath.'/favicon.ico" />';
    echo '<link rel="icon" href="' . $rootpath.'/favicon.ico" />';
    echo '<meta http-equiv="Content-Type" content="text/html; charset=' . _('utf-8') . '" />';
    echo '<link href="'.$rootpath. '/css/'. $_SESSION['Theme'] .'/default.css" rel="stylesheet" type="text/css" />';   
    echo '<script type="text/javascript" src = "'.$rootpath.'/javascripts/MiscFunctions.js"></script>'; 
    echo '<script type="text/javascript" src = "'.$rootpath.'/javascripts/jquery.js"></script>';
    
    echo '</head>';

    echo '<body>';
    
echo'<br />';

echo '<font size=5><p align=center>WORK ORDERS REPORT</p></font>';
echo '<h3 align=center>' . '<b>Item Name : '.$myrow4[0].'</b>( ' . _($myrow4[1]) . ' )</h3>';

//echo '<h3 align=center>' . '<b>Special Officer : '.$myrowso[0].'</b></h3>';
echo '<table width=90% BORDER=1>';
$tableheader = "<tr>
        <th>" . _('Sl No') . "</th>                                                                           
        <th>" . _('WO no') . "</th>
        <th>" . _('WO quantity') . "</th>
        <th>" . _('date issued') . "</th>
        <th>" . _('Signature') . "</th>
        <th>" . _('Remarks') . "</th>
        </tr>";

echo $tableheader;
    $slno=1;
    
    $j = 1;
$k=0; //row colour counter
$slno2=0;
$value=0;


    while($myrow3=DB_fetch_array($result_wo,$db))     {
        
if($slno2==0)       {
    
$startmoveno=$myrow[2];    
}  

if($value==20)       {
    echo'</table>';
    echo '<table width=90% BORDER=0>';
echo'<tr>&nbsp;</tr>';
echo'<tr>&nbsp;</tr>';
echo'<tr>&nbsp;</tr>';
echo'<tr border=0>
<td width=35%>Signature of</td>
<td width=35%>Signature of</td>
<td width=35%>Signature of</td>
</tr>';


echo'<tr border=0><td><br /><br />Store Supt</td>
<td><br /><br />AEO</td>
<td><br /><br />EO</td>

</tr>';
echo'</table>';

echo '<font size=5><p align=center>WORK ORDERS REPORT</p></font>';
echo '<h3 align=center>' . '<b>Item Name : '.$myrow4[0].'</b>( ' . _($myrow4[1]) . ' )</h3>';

echo '<table width=90% BORDER=1 >';
$tableheader = "<tr>
        <th>" . _('Sl No') . "</th>                                                                           
        <th>" . _('WO no') . "</th>
        <th>" . _('WO quantity') . "</th>
        <th>" . _('date issued') . "</th>
        <th>" . _('Signature') . "</th>
        <th>" . _('Remarks') . "</th>
        </tr>";

echo $tableheader;
    $value=0;
}
$value++; 
    $slno2++;
    
    $wono=$myrow3['wo'];
    $quantity=$myrow3['qtyreqd'];
    $date=$myrow3['requiredby'];

    
        printf("<td align=center>%s</td>
        <td align=center>%s</td>
        <td align=center>%s</td>
        <td align=center>%s</td>
        <td align=center>%s</td>
        <td align=center>%s</td>
        </tr>",
        $slno2,
        $wono,
        $quantity,
        $date,
        $signature,
        $remarks);    
    
$wono=$myrow3[0];

 
    }
    echo'<tr><td><a href="WOEntry-reports-sritem.php?item='.$itemcode.' &view=1 &id=1 &season='.$season.'">view all WOs against this item</a></td></tr>';
    echo'</table>';

}else       {
    
$filename="sdfsdfsdf.csv";

    $header= "Slno".","."Item".","."Season".","."WO no".","."Quantity".","."WO date".","."Status"."\n";"\n";
    $data='';
    $slno=1;
    
        while($myrow3=DB_fetch_array($result_wo,$db))     {
    
    $data= $data.$slno.",".$myrow4[0].",".$seasonname.",".$myrow3['wo'].",".$myrow3['qtyreqd'].",".$myrow3['startdate'].",".$myrow3['status']."\n";    
    $slno++;    
    } 
    
header("Content-type: application/x-msdownload"); 
header("Content-Disposition: attachment; filename=$filename"); 
header("Pragma: no-cache"); 
header("Expires: 0");  
echo "$header\n$data";      
}
?>
