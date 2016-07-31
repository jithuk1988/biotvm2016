<?php
  $PageSecurity = 80;   
include('includes/session.inc');

$title = _('Dealer Report');

include('includes/header.inc');

echo '<p class="page_title_text"><img src="'.$rootpath.'/css/'.$theme.'/images/magnifier.png" title="' . _('Search') .
    '" alt="" />' . ' ' . _('Dealers Report') . '</p>';

echo '<form action="' . $_SERVER['PHP_SELF'] . '" method="POST">';


echo '<table class="selection">'; 
echo '<tr>';
  
 echo '<td >District<select name="district" id="district" style="width:130px" onchange="dealersarea();">'; 
  $sql="SELECT * FROM bio_district WHERE stateid=14 ORDER BY did";
    $result=DB_query($sql,$db);
    $f=0;
  while($myrow1=DB_fetch_array($result))
  {
  if ($myrow1['did']==$_POST['district'])
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
    echo $myrow1['did'] . '">'.$myrow1['district'];
    echo '</option>';
    $f++;
   } 
  echo '</select>';
  echo'</td>';
  
  
  $sql_dealers=" SELECT debtorsmaster.debtorno,debtorsmaster.name
                 FROM   debtorsmaster
                 WHERE  debtorno LIKE 'DL%' LIMIT 10";
                   
  $result_dealers=DB_query($sql_dealers,$db);
   
  echo '<td id=showdealer>Dealer <select name="dealer" id="dealer" style="width:200px" onchange=dealerdetails(this.value)>';
//  echo '<option value="">Select Dealer</option>';
   $f=0;
  while($myrow_dealers=DB_fetch_array($result_dealers))
  {         
  if ($myrow_dealers['debtorno']==$dealercode) 
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
    echo $myrow_dealers['debtorno'] . '">'.$myrow_dealers['debtorno']." - ".$myrow_dealers['name'];
    echo '</option>';
    $f++;
   }
  echo '</select>'; 
   
  echo "</td>"; 
  
  
  

        echo '<td align=right><input type=submit name=filter value=Search ></td>';   
  
echo '</tr>';
echo '</table>';

echo '</form>';
echo"<br />";


if(isset($_POST['filter']))
{    
                 
    $sql="SELECT salesorderdetails.quantity,
                 salesorders.orderno,
                 salesorders.orddate,
                 salesorderdetails.stkcode, 
                 salesorderdetails.qtyinvoiced,
                 stockmaster.description
          FROM   salesorders,salesorderdetails,stockmaster 
          WHERE  salesorders.orderno=salesorderdetails.orderno
          AND    salesorderdetails.stkcode=stockmaster.stockid         
          AND    salesorders.debtorno='".$_POST['dealer']."'
        GROUP BY salesorders.orddate,salesorderdetails.stkcode
        ORDER BY salesorders.orddate DESC";
    $result=DB_query($sql,$db);
    
    
    $sql_debtor="SELECT name FROM debtorsmaster WHERE debtorno='".$_POST['dealer']."'";
    $result_debtor=DB_query($sql_debtor,$db);
    $myrow_debtor=DB_fetch_array($result_debtor);
    
    
echo"<table style='border:1px solid #F0F0F0;width:90%' ; >"; 
echo"<tr><td colspan=2>DEALER NAME:&nbsp;&nbsp;&nbsp;<b><i>".$myrow_debtor['name']."</i></b></td></tr>";  
echo"<tr>";
echo"<th>Order No & Date</th><th>Plant</th><th></th><th>Ordered</th><th>Supplied</th><th>Installed</th><th>Inspected</th><th>Document Collected</th>";  

$f=0;
while($myrow=DB_fetch_array($result))
{
    
    $sql_installed="SELECT SUM(bio_childcustomerdetails.quantity) AS sumqty 
           FROM   bio_childcustomer, bio_childcustomerdetails 
           WHERE  bio_childcustomer.id=bio_childcustomerdetails.childid
           AND    bio_childcustomer.dealercode='".$_POST['dealer']."'
           AND    bio_childcustomer.orderno=".$myrow['orderno']."
           AND    bio_childcustomer.stockid='".$myrow['stkcode']."'" ;
    $result_installed=DB_query($sql_installed,$db);
    $myrow_installed=DB_fetch_array($result_installed);
    
    $sql_inspection="SELECT SUM(bio_childcustomerdetails.quantity) AS sumqty 
           FROM   bio_childcustomer, bio_childcustomerdetails 
           WHERE  bio_childcustomer.id=bio_childcustomerdetails.childid
           AND    bio_childcustomer.dealercode='".$_POST['dealer']."'
           AND    bio_childcustomer.orderno=".$myrow['orderno']."
           AND    bio_childcustomer.stockid='".$myrow['stkcode']."'
           AND    bio_childcustomer.inspectionStatus=1" ;
    $result_inspection=DB_query($sql_inspection,$db);
    $myrow_inspection=DB_fetch_array($result_inspection);
    
    $sql_document="SELECT SUM(bio_childcustomerdetails.quantity) AS sumqty 
           FROM   bio_childcustomer, bio_childcustomerdetails 
           WHERE  bio_childcustomer.id=bio_childcustomerdetails.childid
           AND    bio_childcustomer.dealercode='".$_POST['dealer']."'
           AND    bio_childcustomer.orderno=".$myrow['orderno']."
           AND    bio_childcustomer.stockid='".$myrow['stkcode']."'
           AND    bio_childcustomer.documentStatus=1" ;
    $result_document=DB_query($sql_document,$db);
    $myrow_document=DB_fetch_array($result_document);
    
    
    $dealercode=$_POST['dealer'];   
    $orderno=$myrow['orderno'];
    $orddate=$myrow['orddate']; 
           
if($f==0){
    $temp_orderno=$orderno;
    $temp_orderdt=$orddate;  
    echo"<tr><td cellpading=2 width=150px ><a href id=$orderno onclick='viewcustomer(this.id);return false;'>".$myrow['orderno']." - ".ConvertSQLDate($myrow['orddate'])." </a></td></tr>";
    ++$f;
}else{
          
if($orderno!= $temp_orderno && $orddate!= $temp_orderdt) {
    echo"<tr><td cellpading=2 width=150px ><a href id=$orderno onclick='viewcustomer(this.id);return false;'>".$myrow['orderno']." - ".ConvertSQLDate($myrow['orddate'])." </a></td></tr>"; 
}

$temp_orderno=$orderno;
$temp_orderdt=$orddate;

}                                                                                         
            
                      if ($k==1)
                      {
                        echo '<tr class="EvenTableRows">';
                        $k=0;
                      }else 
                      {
                        echo '<tr class="OddTableRows">';
                        $k=1;     
                      }              
           
                      
      printf("          <td></td>   
                        <td >%s</td>
                        <td ></td>
                        <td >%s</td> 
                        <td >%s</td> 
                        <td >%s</td>  
                        <td >%s</td>  
                        <td >%s</td>    
                        </tr>",                                                                                                                                                                                                                                                   
                        $myrow['description'],
                        $myrow['quantity'], 
                        $myrow['qtyinvoiced'], 
                        $myrow_installed['sumqty'],
                        $myrow_inspection['sumqty'],
                        $myrow_document['sumqty'] 
                        );
                        
}



echo"</tr>";
echo"</table>";    
}



?>


 <script type="text/javascript">     

function dealersarea(){   
                                                               
//     cid=document.getElementById("country").value;           
//     sid=document.getElementById('state').value;             
     did=document.getElementById('district').value;         // alert(did);
//     lsg=document.getElementById('lsgType').value;            
//     nam=document.getElementById('lsgName').value;               
//     
//     lsgdetail="&lsg=" + lsg + "&nam=" +nam;
//     if(lsg==3 && nam!=""){        
//     blc=document.getElementById('gramaPanchayath').value; 
//     lsgdetail+="&blc=" +blc;      
//     }
     
if (did=="")
  {
  document.getElementById("showdealer").innerHTML="";
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
    document.getElementById("showdealer").innerHTML=xmlhttp.responseText; 
       
    }
  } 
xmlhttp.open("GET","bio_dealerdetails.php?cid=1&did=" + did,true);
xmlhttp.send(); 
}   


 function viewcustomer(str1)
 {                                  //  alert(str1); 
     controlWindow=window.open("bio_dealers_enduser.php?orderno="+str1,"enduser","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=1200,height=600");
 }

</script>