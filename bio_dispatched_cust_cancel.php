<?php
  $PageSecurity = 80;
include('includes/session.inc');
$title = _('Cancel Dispatch Clearence');  
include('includes/header.inc');

echo '<center><font style="color: #333;
    background:#fff;
    font-weight:bold;
    letter-spacing:0.10em;
    font-size:16px;
    font-family:Georgia;
    text-shadow: 1px 1px 1px #666;">Cancel Dispatch Clearence</font></center>';
    
    
      echo "<form action=" . $_SERVER['PHP_SELF'] . "?" . SID ." method=POST>";
       
        if(isset($_GET['orderno']))
        {
            $sql_delete="DELETE FROM bio_despatchClearence WHERE orderno=".$_GET['orderno']." 
             AND despatch_id=".$_GET['id']."";
            $result_delete=DB_query($sql_delete,$db);
            $sql_delete_dn="DELETE FROM bio_deliverynote WHERE orderno=".$_GET['orderno']." 
                            AND despatch_id=".$_GET['id']."";
            $result_delete_dn=DB_query($sql_delete_dn,$db); 

        }
echo"<div id=grid>";
echo"<fieldset style='width:70%;'>";
echo"<legend><h3>Dispatch Cleared Items</h3></legend>";
    echo"<table style='width:90%;'><tr><td>Name:</td><td><input type='text' name='custname'></td>";
    echo"<td>Sched. Inst. Date:</td><td><input type='text' name='insdate' class=date alt=".$_SESSION['DefaultDateFormat']."></td>"; 
    echo"<td>Odrer No.:</td><td><input type='text' name='ordno'></td>"; 
    echo"<td><input type='submit' name='submit' id='submit' value=search></td></tr></table>";
    
    
    
                      /*  SELECT salesorderdetails.orderno,bio_despatchclearence.id,
              custbranch.brname,
              custbranch.phoneno,
              salesorderdetails.stkcode,
              stockmaster.description,bio_despatchclearence.sched_instlln_date
              FROM salesorderdetails,custbranch,salesorders,stockmaster,bio_despatchclearence
              WHERE salesorderdetails.orderno=salesorders.orderno
              AND salesorders.debtorno=custbranch.debtorno
              AND stockmaster.stockid=salesorderdetails.stkcode
              AND salesorders.inst_completed=0
              AND bio_despatchclearence.orderno=salesorderdetails.orderno
              AND salesorderdetails.orderno NOT IN (SELECT orderno FROM bio_deliverynote WHERE bio_deliverynote.ack_recieved=1
              AND bio_deliverynote.despatch_id=bio_despatchclearence.id)          */
    
     
                              /*   // ,stockcategory,bio_maincat_subcat
                              //AND stockmaster.categoryid=stockcategory.categoryid
              AND stockcategory.categoryid=bio_maincat_subcat.subcatid
              AND bio_maincat_subcat.maincatid=1 */
        echo "<table class='selection' style='width:90%'>";
        echo '<tr>  <th>' . _('Slno') . '</th>
                    <th>' . _('Schedulled Installation') . '</th>
                    <th>' . _('Orderno') . '</th>
                    <th>' . _('Customer Name') . '</th>  
                    <th>' . _ ('Contact No.') . '</th>  
                    <th>' . _ ('Item') . '</th> 
              </tr>';
             
              $sql_inst="
              SELECT salesorderdetails.orderno,bio_despatchClearence.id,bio_despatchClearence.despatch_id,
              custbranch.brname,
              custbranch.phoneno,custbranch.did,custbranch.stateid,custbranch.cid,bio_district.district,     
              salesorderdetails.stkcode,
              stockmaster.description,bio_despatchClearence.sched_instlln_date
              FROM salesorderdetails,custbranch,salesorders,stockmaster,bio_despatchClearence,bio_district
              WHERE salesorderdetails.orderno=salesorders.orderno
              AND salesorders.debtorno=custbranch.debtorno
              AND stockmaster.stockid=salesorderdetails.stkcode
              AND    bio_district.did=custbranch.did AND bio_district.stateid=custbranch.stateid AND bio_district.cid=custbranch.cid
              AND bio_despatchClearence.orderno=salesorderdetails.orderno
              AND salesorderdetails.orderno NOT IN (SELECT orderno FROM bio_deliverynote WHERE bio_deliverynote.ack_recieved=1
              AND bio_deliverynote.despatch_id=bio_despatchClearence.despatch_id)
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
                     $sql_inst.=" AND bio_despatchClearence.sched_instlln_date LIKE '".FormatDateForSQL($_POST['insdate'])."%'";   
                  }
                   if($_POST['ordno']!=NULL){
                     $sql_inst.=" AND salesorderdetails.orderno LIKE '".$_POST['ordno']."%'";   
                  }
                  
              } 
                  $sql_inst.=" GROUP BY bio_despatchClearence.despatch_id"; 
               //echo $sql_inst;
                //    AND custbranch.brname LIKE 'ku%'
                  /*  
                        AND salesorderdetails.orderno=bio_deliverynote.orderno       bio_deliverynote
              AND salesorderdetails.stkcode=bio_deliverynote.stockcode
               */
              
              
                            //  AND salesorderdetails.despatch=1 
/*if($_SESSION['officeid']==2){                     AND bio_deliverynote.orderno=salesorderdetails.orderno     ,bio_deliverynote  
              AND bio_deliverynote.stockcode=salesorderdetails.stkcode     AND bio_deliverynote.ack_recieved=0
       $sql_inst .=" AND custbranch.did IN (1,2,3,7,13) ";
    }
     elseif($_SESSION['officeid']==3){
       $sql_inst .=" AND custbranch.did IN (4,5,8,9,10,14) ";
    }
         elseif($_SESSION['officeid']==4){
       $sql_inst .=" AND custbranch.did IN (6,11,12)  ";
    }
     */
    
$result_so=DB_query($sql_inst,$db);
$i=0;
$k=0;
$slno=1;
while($row_so=DB_fetch_array($result_so))
{     
       $sql_checkrec="SELECT count(id) as count from bio_deliverynote WHERE bio_deliverynote.orderno=".$row_so['orderno']."  AND bio_deliverynote.despatch_id='".$row_so['despatch_id']."'"; 
         $result_checkrec=DB_query($sql_checkrec,$db);
         $row_checkrec=DB_fetch_array($result_checkrec);
    $orderno=$row_so['orderno']; 
    $plant=$row_so['stkcode'];
         if ($row_checkrec['count']==0)
          {
            echo '<tr bgcolor="#FFC285">';
            $k=0;
          }else 
          {
            echo '<tr class="OddTableRows">';
            $k=1;     
          }                                $id=$row_so['despatch_id'];
          
          

                       echo"<td>$slno</td>
                       <td>".convertSQLDate($row_so['sched_instlln_date'])."</td> 
                                     <td>".$row_so['orderno']."</td> 
                                     <td>".$row_so['brname']."</td>                                   
                                     <td>".$row_so['phoneno']."</td> 
                                     <td>".$row_so['description']."</td> 
                                     <td width='75px'><a style='cursor:pointer;' id=$id onclick='viewdocs(this.id,$orderno);'>" . _('Cancel DC') . "</a></td>"; 
                    
                                echo"</tr>";
                                
 $slno++; 
                              
    
}
 


echo"</table>";

echo"</fieldset>";
echo"</div>";
echo"</form>";
      
      


?>


<script type="text/javascript">  

function viewdocs(str,str1)
{ 
    var r=confirm("Do you want to delete?");
if (r==true)
  {
location.href="?orderno=" + str1 + "&id=" +str; 
  }


          //alert(str2);     // 
    //myRef = window.open("bio_lsgstatuschange.php?leadid=" + str1 + "&id=" + str2,"droplead","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=700,height=400");
 //alert(str);    
  

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