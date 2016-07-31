<?php
 
 $PageSecurity = 80;
include('includes/session.inc');
$title = _('List Pending Team');
//include('includes/header.inc');


if(isset($_POST['team']))
{
    $team= $_POST['select1'];
   
    $sql1="select * from bio_feasibilitystudy where teamid=$team";
    $result1=DB_query($sql1,$db);
    $row1=DB_fetch_array($result1); 
    
    $sql2="select * from bio_leadteams where teamid=$team";
    $result2=DB_query($sql2,$db);
    $row2=DB_fetch_array($result2);
    $slno=1;
    
       
    if(!isset($_POST['xl']))
    {
   
        echo '<form name="teamx1"  method="post" action="' . $_SERVER['PHP_SELF'] . '?' .SID .'">';
    echo'<table width="80%">';
    echo'<tr><td colspan=6>Showing all Selected Pending Team Leads</td>';
    echo'<tr><td class="viewheader">Slno</td>';
    echo'<td class="viewheader">Team Name</td>';
    echo'<td class="viewheader">Feasibilitystudy_charge</td>';
    echo'<td class="viewheader">Feasibilitystudy Startdate </td>';
    echo'</tr>';
      
    echo'<tr><td>'.$slno.'</td>';
    echo'<td>'.$row2['teamname'].'</td>';
    echo'<td>'.$row1['feasibilitystudy_charge'].'</td>';
    echo'<td>'.$row1['feasibilitystudy_startdate'].'</td></tr>'; 
    
    echo'<tr><td><input type="submit" name="xl" value="View as Excel"></tr>';
    echo'</table>';
        echo'</form>';
    }
    
    
    if(isset($_POST['xl']))
    {
              
        $filename="sdfsdfsdf.csv";
        $header="Slno".","."Team Name".","."Feasibilitystudy_charge".","."Feasibilitystudy Startdate".","."\n";"\n";
        
        $sql1="select * from bio_feasibilitystudy where teamid=$team";
    $result1=DB_query($sql1,$db);
    $row1=DB_fetch_array($result1); 
        
        $data= $data.$slno.",".$row2['teamname'].",".$row1['feasibilitystudy_charge'].",".$row1['feasibilitystudy_startdate']."\n";    
        
        header("Content-type: application/x-msdownload"); 
        header("Content-Disposition: attachment; filename=$filename"); 
        header("Pragma: no-cache"); 
        header("Expires: 0");  
        echo "$header\n$data";  
        
    } 
    
}
  
?>
