<?php
$PageSecurity = 80;
include('includes/session.inc');



$title = _('Concept Proposal');  
include('includes/header.inc');
echo '<p class="page_title_text"><img src="'.$rootpath.'/css/'.$theme.'/images/maintenance.png" title="' . _('Concept Proposal')
    . '" alt="" />' . _('Concept Proposal Setup') . '</p>';

//=====================================================================================================================================
if($_GET['lead']!=''){
$leadid=$_GET['lead'];  
  $sql="SELECT 
bio_leads.leadid,
bio_cust.custname,
bio_cust.custphone,
bio_cust.custmob,
bio_cust.area1,
bio_cust.district,
bio_cust.state,
bio_cust.nationality  
FROM  bio_leads,
bio_cust
WHERE 
bio_leads.cust_id=bio_cust.cust_id
AND bio_leads.leadid=$leadid";
 $result=DB_query($sql,$db);
     
                                     
    $no=0; 

          $k=0; 
          while($myrow=DB_fetch_array($result))
          {    
          $cname=$myrow[1];  
          if($myrow[2]!='-'){  
          $cph=$myrow[2]; }
          else{
          $cph=$myrow[3]; } 
          $place=$myrow[4];
          $ste=$myrow[6];
          $ctry=$myrow[7]; 
          $sql1="SELECT bio_district.district FROM bio_district
          WHERE bio_district.stateid=$ste  
          AND bio_district.cid=$ctry";     
          $result1=DB_query($sql1,$db);         }
          while($myrow=DB_fetch_array($result1)){
          $district=$myrow[0];     
              
          } 
          

 }


//=====================================================================================================================================
echo"<table><tr><td>"; 
echo"<table><tr><td>";

echo"<fieldset><legend>Feasilblity Report Details</legend>";
echo"<table>";

echo"<tr><td>Customer Name</td>";
echo"<td><input type='text' name='custname' id='custname' value='$cname'></td></tr>";

echo"<tr><td>Assigned Team</td>";
echo"<td><input type='text' name='asgnteam' id='asgnteam' value='$asgnteam'></td></tr>";

                                                                                            
echo '<tr><td>Feed Stock Type</td>'; 
echo "<td><textarea name=Feedstock rows=1 cols=17 style=resize:none;></textarea></td>";

echo"<tr><td>Output Type</td>";
echo"<td><input type='text' name='OutputType' id='outputtype'></td></tr>";
                                                                                                                    
echo"<tr><td>Feed Stock Generating Source</td>";
echo"<td><input type='text' name='FeedStockGeneratingSource' id='generatingsource'></td></tr>";
                                                                                 
echo"<tr><td>Budget</td>";
echo"<td><input type='text' name='Budget' id='budget'></td></tr>";

//echo"<tr><td>Customer Phone</td>";
//echo"<td><input type='text' name='custph' id='custph' value='$cph'></td></tr>";

//echo"<tr><td>Customer Place</td>";
//echo"<td><input type='text' name='custplace' id='custplace' value='$place'></td></tr>";

//echo"<tr><td>Customer District</td>";
//echo"<td><input type='text' name='custdist' id='custdist' value='$district'></td></tr>";


echo"</table>";
echo"</fieldset>";  



echo"</td><td valign=top>"; 

//=====================================================================================================================================

echo"<fieldset><legend>Assigned Plant</legend>";
echo"<table>";

echo"<tr><td>Plant Type</td>";
echo"<td><input type='text' name='PlantType' id='ptype'></td></tr>";


echo '<tr><td colspan=2><p><div class="centre"><input type=submit name=submit value="' . _('Assign') . '" onclick="if(log_in()==1)return false;"></div>';

echo"</table>";
echo"</fieldset>";

echo"</td></tr></table>";  

echo"</td></tr><tr><td>"; 

//=====================================================================================================================================

echo"<fieldset><legend>Lead Details</legend>";
echo "<div style='height:200px; overflow:scroll;'>";   



echo"<table style='width:100%' border=1> ";
echo"<tr><th>Slno</th><th>Name</th><th>Date</th><th>Output</th><th>Cust type</th><th>Team</th></tr>";
 $office=$_SESSION['UserStockLocation'];   
$sql="SELECT 
bio_leads.leadid,
bio_leads.leaddate,
bio_enquirytypes.enquirytype,         
bio_outputtypes.outputtype,                
bio_leadteams.teamname,
bio_cust.custname
FROM bio_cust,
bio_leads,
bio_leadteams,
bio_leadsources,bio_enquirytypes,bio_outputtypes
WHERE bio_cust.cust_id=bio_leads.cust_id AND bio_leadteams.teamid=bio_leads.teamid 
AND bio_leadteams.teamid=bio_leads.teamid
AND bio_leads.enqtypeid=bio_enquirytypes.enqtypeid 
AND bio_leadsources.id=bio_leads.sourceid
AND bio_leads.sourceid=bio_leadsources.id
AND bio_enquirytypes.enqtypeid=bio_leads.enqtypeid  
AND bio_leads.enqtypeid=2    
AND bio_outputtypes.outputtypeid=bio_leads.outputtypeid  
AND bio_leadsources.officeid=$office 
AND bio_leads.leadstatus=0
";     
  
      $result=DB_query($sql,$db);


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
//                    $k=1;     
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
        $myrow[3],
        $myrow[2],
        $myrow[4],
        $myrow[4],
        $myrow[0]);
                 
                 
                 
          }

echo"</td>";

echo"</tr></tbody></table>";

echo"</div>";

echo"</fieldset>";


echo"</td></tr></table>"; 
?>
<script>
function log_in()
{
//    document.getElementById('phone').focus();
}

function passid(str){
location.href="?lead=" +str;   
    
    
}

</script>