<?php 
$PageSecurity = 11; 
include('includes/session.inc');

$srno=$_GET['p']; 
$sql="SELECT mino,
             stausid,
             date
      FROM dev_materialissue
      WHERE srno=$srno";
$result=DB_query($sql,$db);

echo'<table width="100%">';
echo'<thead>';
echo'<tr><td colspan=4>Showing Material issue notes generated for SR no  : &nbsp;'.$srno.'</td></tr>';
echo'<th>slno</th>';
echo'<th>Issue note no</th>';
echo'<th>Issued Date</th>'; 
echo'<th>Issued From</th>'; 
echo'</tr>';
echo'</thead>';    
$slno=0;
echo'<tbody>';
while($myrow=DB_fetch_array($result))       {
$slno++;   

                        if ($k==1)
        {
            echo '<tr class="EvenTableRows"  id="'.$myrow['mino'].'" onclick=datagridload_rpanel(this.id)>';
            $k=0;
        } else 
        {
            echo '<tr class="OddTableRows"  id="'.$myrow['mino'].'" onclick=datagridload_rpanel(this.id)>';
            $k++;
        }  
        
echo'<td>
         '.$slno.'
      </td>
      <td>
         '.$myrow['mino'].'
      </td>
      <td>
         '.$myrow['date'].'
      </td>
      <td>
         
      </td>

   </tr>';
}
echo'</tbody>';
echo'<tfoot>';
echo'<tr><td colspan=4>No: of records  : &nbsp;'.$slno.'</td></tr>';
echo'</tfoot>';
echo'</table>';    
?>             
