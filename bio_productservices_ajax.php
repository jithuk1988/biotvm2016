<?php
  $PageSecurity = 80;  
include('includes/session.inc'); 
 
$enqid=$_GET['enqid'];

if(isset($enqid)) {

        echo '<tr id="showfeasibility"><td>Product Services*</td>';
    echo'<td>';
    if($enqid==1)
    {
        $sql1="SELECT * FROM bio_productservices where enquiry_type=1";
    }else if($enqid==2)
    {
        $sql1="SELECT * FROM bio_productservices where enquiry_type=2";
    }
    else if($enqid==3)
    {
        $sql1="SELECT * FROM bio_productservices where enquiry_type=3";
    }
    else if($enqid==8)
    {
        $sql1="SELECT * FROM bio_productservices where enquiry_type=8";
    }
    else if($enqid==7)
    {
        $sql1="SELECT * FROM bio_productservices where enquiry_type=7";
    }
    else if($enqid==11)
    {
        $sql1="SELECT * FROM bio_productservices where enquiry_type=11";
    }
        
        $result1=DB_query($sql1, $db);
  echo '<select name="productservices" id="productservices" style="width:190px" tabindex=30>';
  $f=0;
  while($myrow1=DB_fetch_array($result1))
  {  
  if ($myrow1['id']==$_POST['productservices'])  
    { 
    echo '<option selected value="';
    } else 
    {
    if ($f==0) 
        {
        echo '<option>';
        }
        echo '<option value="';
    }
    echo $myrow1['id'] . '">'.$myrow1['productservices'];
    echo '</option>';
    $f++;   } 
  echo '</select>'; 
  echo '</td>'; 
  echo '</tr>';
}
?>
