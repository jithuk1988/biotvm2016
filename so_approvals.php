<?php
   $PageSecurity = 80;
include('includes/session.inc');
$title = _('Sale Order Approval');
include('includes/header.inc');     
  

echo '<center><font style="color: #333;
    background:#fff;
    font-weight:bold;
    letter-spacing:0.10em;
    font-size:16px;
    font-family:Georgia;
    text-shadow: 1px 1px 1px #666;">ORDER APPROVAL</font></center>';



//========================================== Grid for pending SO Approval
echo'<div id="leadgrid">';
echo"<fieldset style='width:760px'><legend>Search by</legend>";

 echo '<form name="leadsfrom"  method="post" action="' . $_SERVER['PHP_SELF'] . '?' .SID .'">';
echo"<table style='border:1px solid #F0F0F0;width:100%'>";
echo"<tr><td>Order No</td><td>Customer Name</td><td>Place</td><td>Submitted by</td></tr>"; 

echo"<tr>";


echo"<td><input type='text' name='order' id='order'></td>";
echo"<td><input type='text' name='custname' id='custname'></td>";
echo"<td><input type='text' name='place' id='place'></td>";
echo"<td><input type='text' name='submitted' id='submitted'></td>";


   echo '<td><input type="submit" name="filterbut" id="filterbut" value="search"></td></tr>';
echo"</tr>";
echo"</table>";
echo '</form>';

echo "<div style='height:200px; overflow:scroll;'>"; 
echo"<table style='width:100%'> ";

echo"<tr><th>Slno</th><th>Order No</th><th>Customer Name</th><th>Place</th><th>Date</th><th>Submitted by</th></tr>";


  $sql_bio_orderapproval="SELECT bio_orderapproval.orderno,
                                 debtorsmaster.name,
                                 debtorsmaster.address1,
                                 salesorders.orddate, 
                                 bio_orderapproval.submitted_by    
                            FROM bio_orderapproval,salesorders,debtorsmaster 
                           WHERE bio_orderapproval.orderno=salesorders.orderno
                             AND salesorders.debtorno=debtorsmaster.debtorno  
                             AND bio_orderapproval.taskcomplted_status=0";  
                            
     
    if(isset($_POST['filterbut']))
 {   
     if (isset($_POST['order']))  {        
    if ($_POST['order']!='')   
    $sql .= " AND salesorders.orderno='".$_POST['order']."'";   
    }                                                                    
            
    if (isset($_POST['custname'])) {
    if ($_POST['custname']!='') 
    $sql .= " AND debtorsmaster.name LIKE '%".$_POST['custname']."%'"; 
    }
    
    if (isset($_POST['place'])) {
    if ($_POST['place']!='') 
    $sql .= " AND debtorsmaster.address2 LIKE '%".$_POST['place']."%'"; 
    }
     
     if (isset($_POST['submitted'])) {
    if ($_POST['submitted']!='') 
    $sql .= " AND salesorders.created_by LIKE '%".$_POST['submitted']."%'"; 
    }
 }
    $result_bio_orderapproval=DB_query($sql_bio_orderapproval,$db);     
   

      $no=0;
      $k=0; 
      while($myrow=DB_fetch_array($result_bio_orderapproval))
      {    
               if ($k==1)
                {
                    echo '<tr class="EvenTableRows">';
                    $k=0;                   
                }
                 else 
                {
                    echo '<tr class="OddTableRows">';
                    $k=1;     
                }
      $no++;
     $orderno=$myrow['orderno'];
     
          
printf("<td cellpading=2>%s</td>
        <td>%s</td>
        <td>%s</td>
        <td>%s</td>
        <td>%s</td>
        <td>%s</td>
        <td><a  style='cursor:pointer;' onclick=selectForOrder('$orderno')>" . _('Select') . "</a></td>  
        </tr>",
        $no,
        $myrow['orderno'],
        $myrow['name'],
        $myrow['address1'],
        $myrow['orddate'],
       $myrow['submitted_by']);
      
        }                
                     
echo"</td>";
echo"</tr></tbody></table>";
echo"</div>";
echo"</fieldset>";
echo"</div>";
   include('includes/footer.inc'); 
   
?>

<script type="text/javascript">


function selectForOrder(str1)
{

   window.location="bio_orderapprovaldetails.php?order=" + str1;      
   
}


</script>