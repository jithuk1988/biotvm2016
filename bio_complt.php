<?php
  $PageSecurity = 80;  
include('includes/session.inc'); 
         
if($_GET['enq']){
    $enq=   $_GET['enq'];
        $sql="SELECT * FROM bio_complainttypes WHERE enqtypeid='$enq' ORDER BY complaint asc";
     $result=DB_query($sql,$db);
     while($myrow1=DB_fetch_array($result))
     {
          if ($myrow1['id']==$_POST['complaint'])
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
    echo $myrow1['id'] . '">'.$myrow1['complaint'];
    echo '</option>';
    $f++;
     }
    
}
?>
