<?php
$PageSecurity = 80;    
include('includes/session.inc');    


include('includes/header.inc');
//echo '<div style="height:150px; overflow:auto;">';
 $fs_entryid=$_GET['fsid'];
 
 echo "<div style='height:200px; overflow:scroll;' >";
    
echo"<fieldset style='width:768px'><legend>Plant Details</legend>";
 
 $sql_fswaste="SELECT * FROM bio_fs_entrydetails
                  WHERE fsentry_id ='$fs_entryid'";
 $result_fswaste=DB_query($sql_fswaste,$db);
 $myrow_fswaste=DB_fetch_array($result_fswaste);
 $totalgas=$myrow_fswaste['total_gas'];
 $plants_id=array();
 
 $sql_size="SELECT stockmaster.stockid, 
                   stockmaster.description, 
                   stockcatproperties.label, 
                   stockitemproperties.value
              FROM stockmaster, stockcatproperties, stockitemproperties
             WHERE stockmaster.stockid = stockitemproperties.stockid
               AND stockmaster.categoryid = stockcatproperties.categoryid
               AND stockcatproperties.stkcatpropid = stockitemproperties.stkcatpropid
               AND stockcatproperties.label='Size(Cubic meter)'
               AND stockitemproperties.value>=$totalgas";
  $result_size=DB_query($sql_size,$db);
  $count_plant=DB_num_rows($result_size);
  if($count_plant>0){
      while($myrow_size=DB_fetch_array($result_size)){
      $plants_id[]="'".$myrow_size['stockid']."'";
  }
  $plants_list=join(",", $plants_id);
 
//Feedstock
 
   


//echo "<table><tr>";

echo"<table style='width:100%' border=0> "; 
echo"<tr><th>Slno</th><th>Name</th><th>Min Capacity</th><th>Max Capacity</th><th>Output Type</th><th>Price</th></tr>";

$sql_cat="SELECT subcatid FROM bio_maincat_subcat WHERE maincatid=1";
$result_cat=DB_query($sql_cat,$db);
$cat_arr=array();
while($row_cat=DB_fetch_array($result_cat)) 
{  
  $cat_arr[]="'".$row_cat['subcatid']."'";
  $plant_array=join(",", $cat_arr); 
}

 
$sql="SELECT * 
      FROM stockmaster
WHERE stockmaster.categoryid IN ($plant_array)
AND stockmaster.stockid IN ($plants_list)";     
  
      $result=DB_query($sql,$db);


    echo '<tbody>';
    echo '<tr>';                                       
    $no=0; 

          $k=0; 
          while($myrow=DB_fetch_array($result))          
          {    //echo $myrow[0];    
          $StockID=$myrow[0];
          
$sql2="SELECT *
FROM stockcatproperties,stockitemproperties
WHERE stockcatproperties.categoryid='INST' AND
      stockcatproperties.stkcatpropid=stockitemproperties.stkcatpropid  AND
      stockitemproperties.stockid='$StockID'";

$result2=DB_query($sql2,$db);
while($myrow2=DB_fetch_array($result2))     {
   
if($myrow2[0]==20)        {
    
$min=$myrow2[11];    
}  

if($myrow2[0]==21)        {
    
$max=$myrow2[11];    
} 

if($myrow2[0]==37)        {
    
$outputtype=$myrow2[11];    
}

if($myrow2[0]==38)        {
    
$price=$myrow2[11];    
}   
    
}
      
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
                    //$leadid=$myrow[0];
                    $plantid=$myrow[0];
                 printf("<td cellpading=2>%s</td>
        <td>%s</td>
        <td>%s</td>
        <td>%s</td>                                              
        <td>%s</td>
         <td>%s</td>

         
        <td><a  style='cursor:pointer;'  id='$plantid' onclick='plantname(this.id)'>" . _('Select') . "</a></td>  

        </tr>",
        
        $no,
        $myrow[3],
        $min,
        $max,
        $outputtype,
        $price);
                 
                 
        $outputtype='';      
        $min='';
        $max='';
        $price='';
          }
echo"</table>";
      
  }
  
  
  
echo"</fieldset>";
//echo"</td></tr></table>"; 


echo"</div>";

 $sql1="SELECT * FROM stockmaster where categoryid='INST'";
  $result1=DB_query($sql1, $db);

  
  while($myrow1=DB_fetch_array($result1))
  {  

   }
//  echo "</table>";      
//  echo "</div>";    
?>
