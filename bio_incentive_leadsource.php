<?php

$PageSecurity = 80;  
include('includes/session.inc');
$title = _('Incentive Calculation');  
include('includes/header.inc'); 


//////////////////CREATING TTEMPORARY TABLE/////////////////////////////IF NOT EXISTS        
/*$sql_tmptable="CREATE  TABLE IF NOT EXISTS bio_temptarget_office(teamid int,task int,month int,year int,ordervalue double,target double,achivement_p int,eligible_p double,incentive double,level int)";
                          $result_tmptable=DB_query($sql_tmptable,$db);
                          $sql_create_instl1="DELETE FROM bio_temptarget_office";
                          $result_create_instl=DB_query($sql_create_instl1,$db);


           
*/
echo '<form action="' . $_SERVER['PHP_SELF'] . '" method="POST" style="background:#EBEBEB;">';           
           
  echo "<fieldset style='float:center;width:50%;overflow:scroll;'>";   
       
       echo '<table class="selection" style="background:#EBEBEB;width:500px;">'; 
echo '<tr id="tabl" name="tabl"><td>Leadsource*</td>';
echo '<td><select name="leadsource" id="leadsource" class style="width:146px" >';
 echo '<option value=""></option>';
 echo '<option value="1">Biotech Staff</option>'; 
echo '<option value="2">Dealer</option>';
echo '<option value="3">Network Group</option>';
echo '<option value="4">Promotion</option>';
echo '<option value="6">Customer References</option>';
echo '</select></td>';
 echo '<td>Enquiry type*</td>';
echo '<td><select name="enq" id="enq" class style="width:146px" >';
 echo '<option value=""></option>'; 
echo '<option value="1">Domestic</option>';
echo '<option value="2">Institution</option>';
echo '<option value="3">LSGD</option>';       
echo '</select></td>';
echo '<td><input type="button" name="search" id="search" value="View" onclick=change_page()></td></tr>';
echo '</table>';
echo '</form>' ;

  
     echo "<legend><h3>Lead Source Incentives</h3>";
     echo "</legend>";
     echo "<table style='border:1px solid #F0F0F0;width:800px'>";


           
           
           
?>
<script>
function change_page()
{
    var str1=document.getElementById("leadsource").value;
    var str2=document.getElementById("enq").value;
    if(str1==2)
    {
        if(str2==1 || str2==2 ||str2==3){
             location.href="bio_incentive_dealer.php?ls="+str1+"&enq="+str2;
        }
        else
    {
        alert("Invalid selection");
    }
       
    }
    else if(str1==1)
    {
        if(str2==1 || str2==2 ||str2==3){
             location.href="bio_incentive_staff.php?ls="+str1+"&enq="+str2;
        }
        else
    {
        alert("Invalid selection");
    }
       
    }
        else if(str1==3)
    {
        if(str2==1 || str2==2 ||str2==3){
             location.href="bio_incentive_nws.php?ls="+str1+"&enq="+str2;
        }
        else
    {
        alert("Invalid selection");
    }
       
    }
    else if(str1==4)
    {
        if(str2==1 || str2==2 ||str2==3){
             location.href="bio_incentive_promotion.php?ls="+str1+"&enq="+str2;
        }
        else
    {
        alert("Invalid selection");
    }
       
    }
    else if(str1==6)
    {
        if(str2==1 || str2==2 ||str2==3){
             location.href="bio_incentive_cust.php?ls="+str1+"&enq="+str2;
        }
        else
    {
        alert("Invalid selection");
    }
       
    }
    else
    {
        alert("Invalid selection");
    }
    
}
</script>