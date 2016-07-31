<?php
  $PageSecurity = 80;
include('includes/session.inc');
$title = _('AMC Task Assign');  
include('includes/header.inc');

echo '<center><font style="color: #333;
    background:#fff;
    font-weight:bold;
    letter-spacing:0.10em;
    font-size:16px;
    font-family:Georgia;
    text-shadow: 1px 1px 1px #666;">AMC Task Assign</font></center>';
    
    
      echo "<form action=" . $_SERVER['PHP_SELF'] . "?" . SID ." method=POST>"; 
       echo"<fieldset style='width:70%;'>"; 
        echo"<legend><h3>Select a customer to schedule maintenance</h3></legend>";  
          echo"<table style='width:90%;'><tr><td>Name:</td><td><input type='text' name='custname'></td>";
    echo"<td>AMC Start From:</td><td><input type='text' name='fromdate' class=date alt=".$_SESSION['DefaultDateFormat']."></td>"; 
    echo"<td>AMC Start To:</td><td><input type='text' name='todate' class=date alt=".$_SESSION['DefaultDateFormat']."></td>";  
    echo"<td><input type='submit' name='submit' id='submit' value=search></td></tr></table>";
      
      
echo"<div id=grid>";

           

        echo "<table class='selection' style='width:90%'>";
        echo '<tr>  <th>' . _('Slno-Order No.') . '</th>
                    <th>' . _('Customer Name') . '</th>
                    <th>' . _('Customer type') . '</th>
                    <th>' . _(' Invoice No.-Date') . '</th> 
                    <th>' . _(' AMC Start Date') . '</th>
                    <th>' . _ ('AMC End Date') . '</th>  
                    <th>' . _ ('Plant Model') . '</th> 
                    <th>' . _ ('AMC TYpe') . '</th>  
              </tr>';
            /*  $sql_inst="SELECT bio_installationstatus.orderno,bio_installationstatus.despatch_id,bio_installationstatus.installation_date,
              custbranch.brname,
              custbranch.phoneno
              FROM custbranch,bio_installationstatus,salesorders
              WHERE bio_installationstatus.orderno=salesorders.orderno
              AND salesorders.debtorno=custbranch.debtorno
              AND bio_installationstatus.installation_date!='0000-00-00'
              AND CURDATE() BETWEEN bio_installationstatus.installation_date AND date_add(bio_installationstatus.installation_date, INTERVAL 1 year)
              ";
if($_SESSION['officeid']==2){                                                                                                  //  AND salesorders.inst_completed=1  salesorderdetails.stkcode,stockmaster.description 
       $sql_inst .=" AND custbranch.did IN (1,2,3,7,13) ";       //   AND CURDATE() BETWEEN bio_installationstatus.installation_date AND date_add(bio_installationstatus.installation_date, INTERVAL 1 year)
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
                     $sql_inst.=" AND salesorders.inst_date LIKE '".FormatDateForSQL($_POST['insdate'])."%'";   
                  }
                   if($_POST['ordno']!=NULL){
                     $sql_inst.=" AND salesorderdetails.orderno LIKE '".$_POST['ordno']."%'";   
                  }
                  
              }
                  
                                       $sql_inst.=" GROUP BY bio_installationstatus.despatch_id";  */
                     //  echo $sql_inst;
 
 
 
 
           //                                                                                    
 
        $current_date=date('Y-m-d');
        $sql1="SELECT bio_amc.new_orderno,
        bio_amc.debtorno,
        bio_amc.amc_no,
        bio_amc.cust_name,
        bio_amc.cust_type,
        bio_amc.amc_type,
        bio_amc.end_date,
        bio_amc.start_date,
        bio_amc.invoice_no,
        bio_amc.invoice_date,  
        stockmaster.description 
        FROM bio_amc,stockmaster
        WHERE bio_amc.plant=stockmaster.stockid
        AND bio_amc.new_orderno!=0
        AND bio_amc.end_date >= '".$current_date."'
        ";  //  AND bio_amc.new_orderno!=NULL
        if(isset($_POST['custname']))
        {
             $sql1.=" AND bio_amc.cust_name='".$_POST['custname']."'";
        }
        if(isset($_POST['fromdate']) && isset($_POST['todate']) )
        {
              $sql1.=" AND bio_amc.start_date BETWEEN '".FormatDateForSQL($_POST['fromdate'])."' AND '".FormatDateForSQL($_POST['fromdate'])."' ";
        }
     $result1=DB_query($sql1, $db);  
     $k=0 ; $slno=0; 
     while($row1=DB_fetch_array($result1) ){ 
         if ($k==1){
             echo '<tr class="EvenTableRows">';
             $k=0;
         } else {
             echo '<tr class="OddTableRows">';
             $k=1;
         }
         
          $sql_getinvoice="SELECT transno,trandate FROM debtortrans WHERE debtorno='".$row1['debtorno']."' AND order_='".$row1['new_orderno']."'   ";
          $result_getinvoice=DB_query($sql_getinvoice,$db);
          $row_getinvoice=DB_fetch_array($result_getinvoice);     
         
           
         if($row1['invoice_no']=="" || $row1['invoice_no']==NULL)
            {
         if($row_getinvoice['transno']!="")
         {
              echo  $sql_invoice="UPDATE bio_amc SET invoice_no='".$row_getinvoice['transno']."' , invoice_date='".$row_getinvoice['transno']."'
         WHERE amc_no='".$row1['amc_no']."'";
         $result_invoice=DB_query($sql_invoice,$db);
         
              $invoiced=$row_getinvoice['transno']."-".$row_getinvoice['transno'];
         
         }else{
                $invoiced="Not paid"; 
         }   
                
            }
            else
            {       $invoiced=$row1['invoice_no']."-".ConvertSQLDate($row1['invoice_date']); 
                /* $invoiced=$row1['invoice_no']."-".$row1['invoice_date'] ;   */
              
            }
         
         $slno++;
         $name=$row1['cust_name'];
         if($row1['cust_type']=1){$custtype="BIOTECH";}   
         elseif($row1['cust_type']=2){$custtype="NON-BIOTECH";} 
         $plant=$row1['description'];
      //   $amc_date=ConvertSQLDate($row1['amc_date']);
         $startdate=ConvertSQLDate($row1['start_date']);
         $enddate=ConvertSQLDate($row1['end_date']);
        if($row1['amc_type']=1){$type="AMC Type1";} 
         echo"<tr style='background:#A8A4DB'>
                                    <td>$slno-".$row1['new_orderno']."</td> 
                                    <td>$name</td>  
                                    <td>$custtype</td>
                                    <td>$invoiced</td>
                                    <td>$startdate </td> 
                                    <td> $enddate</td>
                                    <td>$plant</td>
                                     <td> $type</td>
                                    <td><a href='#' id='$row1[new_orderno]' onclick='viewdocs(this.id,".$row1[amc_no].")'>Select</a></td>";  
                                      

     }      
 
 
 
 
 
 
 
 
 
 
 
 
 
 
    
/*$result_so=DB_query($sql_inst,$db);
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
                              
    
}*/                                // <td>".$row_so['description']."</td>  
 


echo"</table>";

echo"</fieldset>";
echo"</div>";
echo"</form>";
      
      


?>


<script type="text/javascript">  

function viewdocs(str1,str2)
{                 //  alert(str1);              alert(str2);   
    //myRef = window.open("bio_lsgstatuschange.php?leadid=" + str1 + "&id=" + str2,"droplead","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=700,height=400");
 //alert(str);
location.href="bio_amc_scheduleassign.php?orderno=" + str1 + "&amcno=" + str2;   

}
    // + "&desid=" + str




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