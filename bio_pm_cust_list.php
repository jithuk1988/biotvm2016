<?php
  $PageSecurity = 80;
include('includes/session.inc');
$title = _('Periodic Maintenance Task Assign');  
include('includes/header.inc');

echo '<center><font style="color: #333;
    background:#fff;
    font-weight:bold;
    letter-spacing:0.10em;
    font-size:16px;
    font-family:Georgia;
    text-shadow: 1px 1px 1px #666;">Periodic Maintenance Task Assign</font></center>';
    
    
      echo "<form action=" . $_SERVER['PHP_SELF'] . "?" . SID ." method=POST>"; 
       echo"<fieldset style='width:70%;'>"; 
        echo"<legend><h3>Select a customer to schedule maintenance</h3></legend>";  
          echo"<table style='width:90%;'><tr><td>Name:</td><td><input type='text' name='custname'></td>";
    echo"<td>Plant Inst. Date:</td><td><input type='text' name='insdate' class=date alt=".$_SESSION['DefaultDateFormat']."></td>"; 
    echo"<td>Odrer No.:</td><td><input type='text' name='ordno'></td>"; 
    echo"<td><input type='submit' name='submit' id='submit' value=search></td></tr></table>";
      
      
echo"<div id=grid>";




        echo "<table class='selection' style='width:90%'>";
        echo '<tr>  <th>' . _('Slno') . '</th>
                    <th>' . _('Plant Installed Date') . '</th>
                    <th>' . _('Orderno') . '</th>
                    <th>' . _('Customer Name') . '</th>  
                    <th>' . _ ('Contact No.') . '</th>  
                    <th>' . _ ('Plant Model') . '</th> 
              </tr>';
              $sql_inst="SELECT bio_installationstatus.orderno,bio_installationstatus.despatch_id,bio_installationstatus.installation_date,
              custbranch.brname,
              custbranch.phoneno
              FROM custbranch,bio_installationstatus,salesorders
              WHERE bio_installationstatus.orderno=salesorders.orderno
              AND salesorders.debtorno=custbranch.debtorno
              AND bio_installationstatus.installation_date!='0000-00-00'
              AND CURDATE() < date_add(bio_installationstatus.installation_date, INTERVAL 1 year)
              ";
if($_SESSION['officeid']==2){                                                                                                  //  AND salesorders.inst_completed=1  salesorderdetails.stkcode,stockmaster.description 
       $sql_inst .=" AND custbranch.did IN (1,2,3,7,13) ";       //   AND CURDATE() BETWEEN bio_installationstatus.installation_date AND date_add(bio_installationstatus.installation_date, INTERVAL 1 year)
    }
     elseif($_SESSION['officeid']==3){
       $sql_inst .=" AND custbranch.did IN (4,5,8,9,10,14) ";                            /*  BETWEEN bio_installationstatus.installation_date AND  */
    }
         elseif($_SESSION['officeid']==4){
       $sql_inst .=" AND custbranch.did IN (6,11,12)  ";
    }
                  if($_POST['submit'])
              {
                  if($_POST['custname']!=NULL) {
                     $sql_inst.=" AND custbranch.brname LIKE '".$_POST['custname']."%'";   
                  } if($_POST['insdate']!=NULL){
                     $sql_inst.=" AND salesorders.inst_date LIKE '".FormatDateForSQL($_POST['insdate'])."%'";   
                  }
                   if($_POST['ordno']!=NULL){
                     $sql_inst.=" AND salesorderdetails.orderno LIKE '".$_POST['ordno']."%'";   
                  }
                  
              }
                  
                                       $sql_inst.=" GROUP BY bio_installationstatus.despatch_id";  
                     //  echo $sql_inst;
    
$result_so=DB_query($sql_inst,$db);
$i=0;
$k=0;
$slno=1;
while($row_so=DB_fetch_array($result_so))
{
    $sql_assigned="SELECT count(id) as count from bio_cstask where orderno='".$row_so['orderno']."' AND cstype=2 AND despatch_id='".$row_so['despatch_id']."'";
    $result_assigned=DB_query($sql_assigned,$db);
    $row_assigned=DB_fetch_array($result_assigned);
    
    $leadid=$row_so['leadid'];
    $orderno=$row_so['orderno']; 
    $plant=$row_so['stkcode'];
          if ($row_assigned['count']==0)
          {
            echo '<tr  id="link" bgcolor="#FFC285" >';
            $k=0;
          }else 
          {
            echo '<tr class="OddTableRows" id="link">';
            $k=1;     
          }
          
    
                       echo"<td>$slno</td>
                       <td>".convertSQLDate($row_so['installation_date'])."</td> 
                                     <td>".$row_so['orderno']."</td> 
                                     <td>".$row_so['brname']."</td>                                   
                                     <td>".$row_so['phoneno']."</td> 
                                     
                                     <td width='50px'><a style='cursor:pointer;' id=".$row_so['despatch_id']." onclick='viewdocs(this.id,$orderno);'>" . _('Select ') . "</a></td>  
                                </tr>";
                                
 $slno++; 
                              
    
}                                // <td>".$row_so['description']."</td>  
 


echo"</table>";

echo"</fieldset>";
echo"</div>";
echo"</form>";
      
      


?>


<script type="text/javascript">  

function viewdocs(str,str1,str2)
{
    //myRef = window.open("bio_lsgstatuschange.php?leadid=" + str1 + "&id=" + str2,"droplead","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=700,height=400");
 //alert(str);alert(str1);
location.href="bio_pmscheduleassign.php?orderno=" + str1 + "&desid=" + str;   

}





function showdocs(){   


if (str1=="")
  {
  document.getElementById("showdocument").innerHTML="";
  return;
  }
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
         document.getElementById("showdocument").innerHTML=xmlhttp.responseText; 
    }
  } 
xmlhttp.open("GET","bio_docCustSelection.php?enqid=" + str1,true);
xmlhttp.send(); 
} 

</script>