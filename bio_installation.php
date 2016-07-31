<?php
  $PageSecurity = 80;
include('includes/session.inc');
$title = _('Installation Task Assign');  
include('includes/header.inc');

echo '<center><font style="color: #333;
    background:#fff;
    font-weight:bold;
    letter-spacing:0.10em;
    font-size:16px;
    font-family:Georgia;
    text-shadow: 1px 1px 1px #666;">Installation Schedule Assign</font></center>';
    
      if(isset($_GET['cancel']))
 {
     $sql_cancel_inst="UPDATE bio_installationstatus SET bio_installationstatus.schedule=1 WHERE bio_installationstatus.orderno='".$_GET['orderno']."' AND bio_installationstatus.despatch_id='".$_GET['des']."' ";
    $rsult_cancel_inst=DB_query($sql_cancel_inst,$db);
 }
    
      echo "<form action=" . $_SERVER['PHP_SELF'] . "?" . SID ." method=POST>"; 
    echo"<fieldset style='width:70%;'>";   
          echo"<table style='width:90%;'><tr><td>Name:</td><td><input type='text' name='custname'></td>";
    echo"<td>Delivery Date:</td><td><input type='text' name='insdate' class=date alt=".$_SESSION['DefaultDateFormat']."></td>"; 
    echo"<td>Odrer No.:</td><td><input type='text' name='ordno'></td>"; 
    echo"<td><input type='submit' name='submit' id='submit' value=search></td></tr></table>";
      


echo"<legend><h3>Select a customer to schedule plant installation</h3></legend>";
   echo"<div id=grid>"; 

        echo "<table class='selection' style='width:90%'>";
        echo '<tr>  <th>' . _('Slno') . '</th>
                    <th>' . _('Delivery Date') . '</th>
                    <th>' . _('Orderno') . '</th>
                    <th>' . _('Customer Name') . '</th>  
                    <th>' . _ ('Contact No.') . '</th>  
                    <th>' . _ ('Items') . '</th> 
              </tr>';
             $sql_inst="SELECT bio_deliverynote.recieve_date,bio_deliverynote.despatch_id,bio_deliverynote.orderno,custbranch.brname,custbranch.phoneno
             FROM bio_deliverynote,custbranch,salesorderdetails,salesorders,bio_installationstatus
             WHERE salesorderdetails.orderno=salesorders.orderno
              AND salesorders.debtorno=custbranch.debtorno
              AND bio_deliverynote.orderno=salesorderdetails.orderno 
             AND bio_deliverynote.ack_recieved=1
             AND bio_installationstatus.orderno=bio_deliverynote.orderno
             AND bio_installationstatus.despatch_id=bio_deliverynote.despatch_id 
             AND bio_installationstatus.installation_date='0000-00-00'
             AND  bio_installationstatus.schedule!=1 
             GROUP BY bio_deliverynote.despatch_id";
             
             if($_SESSION['officeid']==2){
       $sql_inst .=" AND custbranch.did IN (1,2,3,7,13) ";
    }
     elseif($_SESSION['officeid']==3){
       $sql_inst .=" AND custbranch.did IN (4,5,8,9,10,14) ";
    }
         elseif($_SESSION['officeid']==4){
       $sql_inst .=" AND custbranch.did IN (6,11,12)  ";
    }
                  if($_POST['submit'])
              {
                  if($_POST['custname']!=NULL) {
                     $sql_inst.=" AND custbranch.brname LIKE '".$_POST['custname']."%'";   
                  } if($_POST['insdate']!=NULL){
                     $sql_inst.=" AND bio_deliverynote.recieve_date LIKE '".FormatDateForSQL($_POST['insdate'])."%'";   
                  }
                   if($_POST['ordno']!=NULL){
                     $sql_inst.=" AND salesorderdetails.orderno LIKE '".$_POST['ordno']."%'";   
                  }
                  
              } 
                  
           /* echo  $sql_inst="SELECT salesorderdetails.orderno,
              custbranch.brname,
              custbranch.phoneno,
              salesorderdetails.stkcode,
              stockmaster.description,bio_deliverynote.recieve_date,bio_deliverynote.despatch_id   
              FROM salesorderdetails,custbranch,salesorders,stockmaster,stockcategory,bio_deliverynote
              WHERE salesorderdetails.orderno=salesorders.orderno
              AND salesorders.debtorno=custbranch.debtorno
             
              AND bio_deliverynote.orderno=salesorderdetails.orderno
              AND bio_deliverynote.stockcode=salesorderdetails.stkcode
              AND bio_deliverynote.ack_recieved=1
              AND salesorderdetails.orderno NOT IN (SELECT bio_installationstatus.orderno FROM bio_installationstatus WHERE bio_installationstatus.installation_date!='0000-00-00')";
if($_SESSION['officeid']==2){
       $sql_inst .=" AND custbranch.did IN (1,2,3,7,13) ";
    }
     elseif($_SESSION['officeid']==3){
       $sql_inst .=" AND custbranch.did IN (4,5,8,9,10,14) ";
    }
         elseif($_SESSION['officeid']==4){
       $sql_inst .=" AND custbranch.did IN (6,11,12)  ";
    }
                  if($_POST['submit'])
              {
                  if($_POST['custname']!=NULL) {
                     $sql_inst.=" AND custbranch.brname LIKE '".$_POST['custname']."%'";   
                  } if($_POST['insdate']!=NULL){
                     $sql_inst.=" AND bio_deliverynote.recieve_date LIKE '".FormatDateForSQL($_POST['insdate'])."%'";   
                  }
                   if($_POST['ordno']!=NULL){
                     $sql_inst.=" AND salesorderdetails.orderno LIKE '".$_POST['ordno']."%'";   
                  }
                  
              }                                 */
              
                                         /*  AND stockmaster.stockid=salesorderdetails.stkcode
              AND stockmaster.categoryid=stockcategory.categoryid
              AND stockcategory.categoryid=bio_maincat_subcat.subcatid
              AND bio_maincat_subcat.maincatid=1   */
    
$result_so=DB_query($sql_inst,$db);
$i=0;
$k=0;
$slno=1;
while($row_so=DB_fetch_array($result_so))
{
    $sql_assigned="SELECT count(id) as count from bio_cstask where orderno='".$row_so['orderno']."'";
    $result_assigned=DB_query($sql_assigned,$db);
    $row_assigned=DB_fetch_array($result_assigned);
    
    $leadid=$row_so['leadid'];
    $orderno=$row_so['orderno']; 
    $plant=$row_so['stkcode'];
     $despatch_id=$row_so['despatch_id'];
          if ($row_assigned['count']==0)
          {
            echo '<tr  id="link" bgcolor="#FFC285" >';
            $k=0;
          }else 
          {
            echo '<tr class="OddTableRows" id="link">';
            $k=1;     
          }
               $sql_getitems="SELECT  bio_deliverynote.stockcode,stockmaster.description FROM bio_deliverynote,stockmaster 
               WHERE stockmaster.stockid=bio_deliverynote.stockcode
               AND bio_deliverynote.orderno=".$row_so['orderno']." AND bio_deliverynote.despatch_id=".$row_so['despatch_id']."";
                $result_getitems=DB_query($sql_getitems,$db);  
                       echo"<td>$slno</td>
                       <td>".convertSQLDate($row_so['recieve_date'])."</td> 
                                     <td>".$row_so['orderno']."</td> 
                                     <td>".$row_so['brname']."</td>                                   
                                     <td>".$row_so['phoneno']."</td>";
                                        echo"<td>";
                                     while($row_getitems=DB_fetch_array($result_getitems))
                                     {     //echo"<table>"; 
                                         echo$row_getitems['description']."</br>";
                                       //echo"</table>";    
                                     }   
                                     echo"</td>";  
                                     echo"<td width='50px'><a style='cursor:pointer;' id=$orderno onclick='viewdocs($orderno,$despatch_id);'>" . _('Select ') . "</a></td>";
                                     echo"<td width='50px'><a style='cursor:pointer;' id=$orderno onclick='delete_item($orderno,$despatch_id,1);'>" . _('Cancel ') . "</a></td>     
                                </tr>";
                                
 $slno++; 
                              
    
}
 


echo"</table>";

echo"</fieldset>";
echo"</div>";
echo"</form>";
      
      


?>


<script type="text/javascript">  

function viewdocs(str1,str2,str)
{      //alert(str2); //  alert(str1);    
    //myRef = window.open("bio_lsgstatuschange.php?leadid=" + str1 + "&id=" + str2,"droplead","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=700,height=400");
                       // alert(str2);
location.href="bio_installationscheduleassign.php?orderno=" + str1 +  "&des="+ str2;   

}

   function delete_item(str1,str2,str)
{      //alert(str2); //  alert(str1);    
    //myRef = window.open("bio_lsgstatuschange.php?leadid=" + str1 + "&id=" + str2,"droplead","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=700,height=400");
                       // alert(str2);
                       alert("If you cancel this, can't set installation schedule for these items.");
 var r=confirm("Are you sure to want to cancel installation schedule for this delivery.......?");
if (r==true)
  {
  location.href="bio_installation.php?orderno=" + str1 +  "&des="+ str2 +"&cancel="+ str;
  }
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