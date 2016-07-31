<?php

$PageSecurity = 80;  
include('includes/session.inc');
 $title = _('Incentive date Calculation');  

include('includes/header.inc');
echo '<center><font style="color: #333;
    background:#EBEBEB;
    font-weight:bold;
    letter-spacing:0.10em;
    font-size:16px;
    font-family:Georgia;
    text-shadow: 1px 1px 1px #666;">Incentive Calculation</font></center>';
      $closedate=FormatDateForSQL($_POST['closedate']);     
if(isset($_POST['submit'])){
    $sql="INSERT INTO 
                            bio_closingdate(closedate) 
                            VALUES ('".$closedate."') ";
    
    $result=DB_query($sql,$db);
    
}
  if(isset($_POST['edit'])){
                        $cldate=$_POST['editdate'];  
                       $sql4="UPDATE  bio_closingdate 
                                                         SET closedate='".$cldate."'                                                                                              WHERE id='".$_POST['id2']."'";                     
                                                         $result4=DB_query($sql4,$db); 
                                                         prnMsg(_('Update') ,'success');        
                           } 

if(isset($_GET['delete'])){
 $sql3= "DELETE FROM bio_closingdate WHERE id=".$_GET['delete']." ";
$result3=DB_query($sql3,$db); 
prnmsg('deleted','success');
}
 $sql1="SELECT MAX(closedate) as closedate  from bio_closingdate";
                                     $result1=DB_query($sql1,$db);
                                     $myrow = DB_fetch_array($result1); 
                                     $prevdate=$myrow['closedate'];
                                     $crntdate=strtotime(date("y-m-d",strtotime($prevdate))."+1months");
                                                                                                                          echo '<form action="' . $_SERVER['PHP_SELF'] . '" method="post" style="background:#EBEBEB;">';
echo '<table class="selection" style="background:#EBEBEB;width:500px;">'; 
echo '<tr id="tbl" name="tbl"><td>Closing Date*</td><td><input type="text" name="closedate" id="closedate" class=date alt="'.$_SESSION['DefaultDateFormat']. '" style="width:146px" value='.date('d/m/Y', $crntdate).'></td><td><input type="submit" name="submit" id="submit" value="Add"></td></tr>';
echo '</table>';
echo '</form>';
echo "<fieldset style='float:center;width:50%;background:#EBEBEB'>";     
     echo "<legend><h3>Incentive Closing Date</h3>";    
     echo "</legend>";
     echo "<table style='border:1px solid #F0F0F0;width:800px;background:#EBEBEB'>";

     echo '<tr><td style="border-bottom:1px solid #000000">DATE</td>
                </tr>';
$sql2="SELECT id,closedate from bio_closingdate ORDER BY closedate ASC";
                                     $result2=DB_query($sql2,$db);
             while($myrow2 = DB_fetch_array($result2)){ 
              $id1=$myrow2['id'];  
                printf("<tr style='background:#A8A4DB'>
            <td cellpading=2 width='150px'>%s</td>            
            <td width='30px'> <a style='cursor:pointer;' onclick=edit('$myrow2[id]')>" . _('Edit') . "</a> </td>
            <td width='30px'> <a style='cursor:pointer;' id=$id1 name=$id1 onclick=dlt(this.id)>". _('Delete') . "</a></td>
                </tr>",        
        $myrow2[closedate]); 
             }   
  echo "</table>";     
 echo "</fieldset>";          
           
?>
<script>
function edit(str)
{
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("tbl").innerHTML=xmlhttp.responseText;
//if(str==2){getservice(str);}
    }
  }
xmlhttp.open("GET","bio_incentivedatecalculationedit.php?id=" + str,true);
xmlhttp.send();
}





function dlt(str){
location.href="?delete=" +str;         
 
}
</script>