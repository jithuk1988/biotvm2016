<?php
  $PageSecurity = 11;

  include('includes/session.inc');  
      $sql3="SELECT mrpdemands.demandid,     
                    mrpdemands.mrpdemandtype,     
                    mrpdemands.quantity,     
                    mrpdemands.duedate,     
                    mrpdemands.statusid,
                    dev_mrpdemandstatus.status
           FROM mrpdemands,dev_mrpdemandstatus
           WHERE mrpdemands.stockid='".$_GET['id']."'    AND
                 mrpdemands.statusid=dev_mrpdemandstatus.statusid";
    $result3=DB_query($sql3,$db);
    
    $sql4="SELECT description,
                  units 
           FROM stockmaster
           WHERE stockid='".$_GET['id']."'";
    $result4=DB_query($sql4,$db);
    $myrow4=DB_fetch_array($result4);
    
    $sql5="SELECT m_season.season_sub_id,
                  m_season.start_eng_year,
                  m_sub_season.seasonname     
           FROM m_season,m_sub_season
           WHERE m_season.season_id='".$_GET['season']."'       AND
                 m_season.season_sub_id=m_sub_season.season_sub_id";
    $result5=DB_query($sql5,$db);
    $myrow5=DB_fetch_array($result5);
   
   //echo $myrow5[1];
    echo "<h2 align='center'><font size=3>" . _('DAILY PRODUCTION SCHEDULES') . '</h3></font>';
    echo "<h2 align='center'><font size=3>" .$myrow5[2] .'-'.$myrow5[1]. '</h4></font>';
     
    echo "<br><font color=BLUE size=3><b>$myrow4[0] </b>  (" . _('in units of') . ' ' . $myrow4[1] . ')</font>';
    //echo "<br><font color=BLUE size=3>" . _('Season') . ' ' . $myrow5[1] . '</font>';
    echo'<table width="100%" border="1">';
    //echo'<tr><td class="viewheader"><b>'.$myrow4[0].'</b></td></tr>';
    //echo'<tr><td class="viewheader"><b>Season: '.$myrow4[0].' </b></td>';
    echo'<tr bgcolor=#E0E0E0><td class="viewheader">slno</td>';
    echo'<td class="viewheader">Demand date</td>';
    echo'<td class="viewheader">Demand qty</td>';
    echo'<td class="viewheader">Unit</td>';
    echo'<td class="viewheader">Demand status</td>';
    echo'</tr>';
    $slno=1;
    $k=0;
    while($myrow3=DB_fetch_array($result3,$db))     {
        
                    if ($k==1){
                    echo '<tr class="EvenTableRows">';
                    $k=0;
                } else {
                    echo '<tr class="OddTableRows">';;
                    $k=1;
                }
                
                
    echo'<td>'.$slno.'</td>';
    echo'<td>'.$myrow3['duedate'].'</td>';
    echo'<td>'.$myrow3['quantity'].'</td>';
    echo'<td>'.$myrow4['units'].'</td>';
    echo'<td>'.$myrow3['status'].'</td>';
    echo'</tr>';    
    $slno++;   
    }
    echo'</table>';
?>
