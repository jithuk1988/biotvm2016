<?php
$PageSecurity = 80;  
include('includes/session.inc'); 
             $desg=$_GET['enquiry'] ;      
if($_GET['en_type']==1)
{     $sql2="select * from bio_designation where bio_designation.desgid IN(19,16,4) "; 
}   
else if($_GET['en_type']==2)
{
     $sql2="select * from bio_designation where bio_designation.desgid IN(9,5,4) ";  
}
else if($_GET['en_type']==3)
{
    // $sql2="select * from bio_designation where bio_designation.desgid IN(9,5,4) ";   
}
echo"<td>Designation</td>";
echo"<td><select name='design' id='design' style='width:150px'> ";
echo '<option value=0></option>';   

    $result2=DB_query($sql2,$db);
    while($row=DB_fetch_array($result2))
    {

    if ($row['desgid']==$desg)
        {
    echo '<option selected value="';
        } else {
    echo '<option value="';
        }
    echo $row['desgid'].'">'.$row['designation'];
        echo '</option>';
    }
echo '</select></td></tr>'; 
?>