<?php
  
$PageSecurity = 80;  
include('includes/session.inc'); 
//echo $_GET['sd'];
// echo "hii"; 
 
 if(isset($_GET['q'])){
  $sourceid=$_GET['q'] ;
    $_SESSION['sourceid']=$sourceid;
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
?>
