<HTML>
<head>
<style type="text/css" media="print">


    body {
        writing-mode: tb-rl;
               
    }
    



</style>

</head>

<body>
<?php

$PageSecurity = 11; 
include('includes/session.inc');



$FontSize=12;
$PageNumber=1;
$line_height=12;

$mino=$_GET['id']; 

$sql7="SELECT m_season.start_malyear,
              m_sub_season.seasonname     
       FROM m_season,m_sub_season
       WHERE m_season.is_current=1      AND
             m_season.season_sub_id=m_sub_season.season_sub_id";
$result7=DB_query($sql7,$db);
$myrow7=DB_fetch_array($result7);
$malyear=$myrow7[0];
$seasonname=$myrow7[1];

$sql9="SELECT dev_materialissue.srno,
              dev_materialissue.date,
              womaterialrequest.wono,
              womaterialrequest.reqdate,
              woitems.stockid,
              woitems.qtyreqd,
              stockmaster.longdescription
       FROM dev_materialissue,womaterialrequest,woitems,stockmaster
       WHERE dev_materialissue.mino=$mino       AND
             dev_materialissue.srno=womaterialrequest.reqno      AND
             womaterialrequest.wono=woitems.wo      AND
             woitems.stockid=stockmaster.stockid";
$result9=DB_query($sql9,$db);
$myrow9=DB_fetch_array($result9);
$srno=$myrow9[0];
$kootu=$myrow9[5];
$FGitem=$myrow9[4];

$sql3="SELECT longdescription
       FROM stockmaster
       WHERE stockid='$FGitem'";
$result3=DB_query($sql3,$db);
$myrow3=DB_fetch_array($result3);

$FGname=$myrow3[0];

$item=$myrow9[5];

$issuedate=split('/',$myrow9[1]); 
$month=$issuedate[1];
$day=$issuedate[2];
$year=$issuedate[0];

$issuedate=$day.'-'.$month.'-'.$year;

$indentdate=split('-',$myrow9[3]);
$month2=$indentdate[1];
$day2=$indentdate[2];
$year2=$indentdate[0];

$indentdate=$day2.'-'.$month2.'-'.$year2;



 

$sql="SELECT dev_materialissuedetails.stockid,     
             dev_materialissuedetails.quantity,
             stockmoves.trandate,
             stockmaster.description,
             stockmaster.longdescription,
             stockmaster.units,
             locations.locationname 
      FROM dev_materialissuedetails,stockmoves,stockmaster,locations
      WHERE dev_materialissuedetails.mino=$mino      AND
            dev_materialissuedetails.stockmoveno=stockmoves.stkmoveno   AND
            dev_materialissuedetails.stockid=stockmaster.stockid        AND
            stockmoves.loccode=locations.loccode";
$result=DB_query($sql,$db);

$slno=0;
$counter=0;



$Ypos+10;

$d='<div style="position:relative; top:3cm; left:-0.5cm;  margin-left:0cm; ">

<table style="width:34.3cm;">
   <tr>
   <tr>
   <td><table style="width:11.5cm;"><tr>
      
      <td style="width:2.3cm; height:0.3cm;"></td>
      <td colspan=4 style="text-align:center;">STORES ISSUE NOTE</td>
      </tr></table></td>
      
      <td><table style="width:10.5cm;"><tr>
      
      <td style="width:2.3cm; height:0.3cm;"></td>
      <td colspan=4 style="text-align:center;">STORES ISSUE NOTE</td>
      </tr></table></td>
      
      <td><table style="width:10.5cm;"><tr>
      
      <td style="width:2.3cm; height:0.3cm;"></td>
      <td colspan=4 style="text-align:center;">STORES ISSUE NOTE</td>
      </tr></table></td>
   </tr>
   <td><table style="width:11.5cm;"><tr>
      
      <td style="width:2.3cm; height:0.3cm;"></td>
      <td style="width:0.8cm;"></td>
      <td style="width:4.9cm;"></td>
      <td style="width:1cm;"></td>
      <td style="width:2.5cm; text-align:right;"><font size=2>'.$indentdate.'</font></td>
      </tr></table></td>
      
      <td><table style="width:10.5cm;"><tr>
      
      <td style="width:1.6cm;"></td>
      <td style="width:0.8cm;"></td>
      <td style="width:4.9cm;"></td>
      <td style="width:1cm;"></td>
      <td style="width:2.5cm; text-align:right;"><font size=2>'.$indentdate.'</font></td>
      </tr></table></td>
      
      <td><table style="width:10.5cm;"><tr>
      
      <td style="width:1.6cm;"></td>
      <td style="width:0.8cm;"></td>
      <td style="width:4.9cm;"></td>
      <td style="width:1cm;"></td>
      <td style="width:2.5cm; text-align:right;"><font size=2>'.$indentdate.'</font></td>
      </tr></table></td>
   
   </tr>
   <tr>&nbsp;</tr>
   <tr>
        
      <td><table style="width:11.5cm;"><tr>
      
      <td style="width:1.6cm;"></td>
      <td style="width:0.8cm;"></td>
      <td colspan=2 style="text-align:center"><font size=3>'.$srno.'(&nbsp;SO&nbsp;'.$FGname.')&nbsp;'.$kootu.'&nbsp;koottu</font></td>
      </tr></table></td>
      
      <td><table style="width:10.5cm;"><tr>
      
      <td style="width:1.6cm;"></td>
      <td style="width:0.8cm;"></td>
      <td colspan=2 style="text-align:center"><font size=3>'.$srno.'(&nbsp;SO&nbsp;'.$FGname.')&nbsp;'.$kootu.'&nbsp;koottu</font></td>
      </tr></table></td>
      
      <td><table style="width:10.5cm;"><tr>
      
      <td style="width:1.6cm;"></td>
      <td style="width:0.8cm;"></td>
      <td colspan=2 style="text-align:center"><font size=3>'.$srno.'(&nbsp;SO&nbsp;'.$FGname.')&nbsp;'.$kootu.'&nbsp;koottu</font></td>
      </tr></table></td>
      
   </tr>
</table></div>';

echo $d;
while($myrow=DB_fetch_array($result))       {
$slno++; 

    $Itemname=$myrow['description'];   
    
    //$Itemname=mb_convert_encoding($Itemname,'utf-8','ISO-8859-1').'. ';                                                                             
    $Quantity=$myrow['quantity'];
    $Units=$myrow['units']; 
    
    

$t='<div style="position:relative; top:4.6cm; left:-0.5cm;  margin-left:0cm; ">

<table style="width:34.3cm;">
   <tr>
        
      <td><table style="width:11.5cm;"><tr>
      
      <td style="width:2.3cm; height:0.3cm; "></td>
      <td style="width:0.8cm;"><font size=2>'.$slno.'</font></td>
      <td style="width:4.9cm;"><font size=2>'.$Itemname.'</font></td>
      <td style="width:1cm; text-align:center;"><font size=2>'.$Units.'</font></td>
      <td style="width:2.5cm; text-align:right;"><font size=2>'.$Quantity.'</font></td>
      </tr></table></td>
      
      <td><table style="width:10.5cm;"><tr>
      
      <td style="width:1.6cm;"></td>
      <td style="width:0.8cm;"><font size=2>'.$slno.'</font></td>
      <td style="width:4.9cm;"><font size=2>'.$Itemname.'</font></td>
      <td style="width:1cm; text-align:center;"><font size=2>'.$Units.'</font></td>
      <td style="width:2.5cm; text-align:right;"><font size=2>'.$Quantity.'</font></td>
      </tr></table></td>
      
      <td><table style="width:10.5cm;"><tr>
      
      <td style="width:1.6cm;"></td>
      <td style="width:0.8cm;"><font size=2>'.$slno.'</font></td>
      <td style="width:4.9cm;"><font size=2>'.$Itemname.'</font></td>
      <td style="width:1cm; text-align:center;"><font size=2>'.$Units.'</font></td>
      <td style="width:2.5cm; text-align:right;"><font size=2>'.$Quantity.'</font></td>
      </tr></table></td>
      
   </tr>
</table></div>';
    
$data=$data."\n".$t;
    

    
    $counter = $counter + 1.5;

}




  
//$filename="Usage.doc";
//header("Content-type: application/x-msdownload"); 
//header("Content-Disposition: attachment; filename=$filename"); 

//header("Pragma: no-cache"); 
//header("Expires: 0");  
echo "$header\n$data";   

$c='<div style="position:absolute; top:18cm; left:-0.5cm;  margin-left:0cm; ">

<table style="width:34.3cm;">
   <tr>
        
      <td><table style="width:11.5cm;"><tr>
      
      <td style="width:2.3cm; height:0.3cm; "></td>
      <td style="width:0.8cm; text-align:left;" colspan=3><font size=2>Store supt.</font></td>
      </tr></table></td>
      
      <td><table style="width:10.5cm;"><tr>
      
      <td style="width:1.6cm;"></td>
      <td style="width:0.8cm; text-align:left;" colspan=3><font size=2>Store supt.</font></td>
      </tr></table></td>
      
      <td><table style="width:10.5cm;"><tr>
      
      <td style="width:1.6cm;"></td>
      <td style="width:0.8cm; text-align:left;" colspan=3><font size=2>Store supt.</font></td>
      </tr></table></td>
      
   </tr>
</table></div>';

echo $c;
?>
</body>
</HTML>