<?php
  $PageSecurity = 80;
include('includes/session.inc');
$title = _('Dealers Account Details');  
include('includes/header.inc');

echo '<center><font style="color: #333;
       background:#fff;
       font-weight:bold;
       letter-spacing:0.10em;
       font-size:16px;
       font-family:Georgia;
       text-shadow: 1px 1px 1px #666;">Dealers Account Details</font></center>';
    
    

echo "<form action=" . $_SERVER['PHP_SELF'] . "?" . SID ." method=POST>"; 
echo"<div id=grid>";
echo"<fieldset style='width:70%;'>";
echo"<legend><h3>Dealers Account Details</h3></legend>";
echo"<table style='border:1px solid #F0F0F0;width:90%'>"; 

echo"<tr>"; 


    echo '<td>Dealer Type <select name="sale" id="sale" style="width:140px">';
    echo '<option value=0></option>';   
    $sql1="select * from salesman where salesmancode not in (1,2,3) ";
    $result1=DB_query($sql1,$db);
    while($row1=DB_fetch_array($result1))
    {
    //  echo "<option value=$row1[enqtypeid]>$row1[enquirytype]</option>";
        if ($row1['salesmancode']==$_POST['sale'])
           {
             echo '<option selected value="';
           } else 
           { 
               echo '<option value="'; 
           }
           echo $row1['salesmancode'] . '">'.$row1['salesmanname'];
           echo '</option>';  
    }

    echo '</select></td>';


   echo"<td>Country<select name='country' id='country' onchange='showstate(this.value)' style='width:100px'>";
$sql="SELECT * FROM bio_country ORDER BY cid";
$result=DB_query($sql,$db);
 $f=0;
  while($myrow1=DB_fetch_array($result))
  {  
  if ($myrow1['cid']==1)  
    {         //echo $myrow1['cid'];     
    echo '<option selected value="';
    } else 
    {
    if ($f==0) 
        {
        echo '<option>';
        }
        echo '<option value="';
    }
    echo $myrow1['cid'] . '">'.$myrow1['country'];
    echo '</option>';
    $f++;
   }    
  echo '</select></td>';
  echo '<td id="showstate">State<select name="State" id="state" onchange="showdistrict(this.value)" style="width:100px">';
  $sql="SELECT * FROM bio_state ORDER BY stateid";
  $result=DB_query($sql,$db);
  $f=0;
  while($myrow1=DB_fetch_array($result))
  {
  if ($myrow1['stateid']==14)
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
    echo $myrow1['stateid'] . '">'.$myrow1['state'];
    echo '</option>';
    $f++;
   }
  echo '</select>';
  echo'</td>'; 
 echo '<td id="showdistrict">District<select name="District" id="Districts" style="width:100px"   onchange="showtaluk(this.value)">'; 
  $sql="SELECT * FROM bio_district WHERE stateid=14 ORDER BY did";
    $result=DB_query($sql,$db);
    $f=0;
  while($myrow1=DB_fetch_array($result))
  {
  if ($myrow1['did']==$_POST['District'])
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
      
      
    echo '<td>LSG Type<select name="lsgType" id="lsgType" style="width:100px" onchange="showblock(this.value)">';
    echo '<option value=0></option>'; 
    echo '<option value=1>Corporation</option>';
    echo '<option value=2>Muncipality</option>';
    echo '<option value=3>Panchayat</option>';           
    echo '</select></td>'; 
  
        echo '<td align=left colspan=2>';
        echo'<div style="align:right" id=block>';             
        echo'</div>';
        echo'</td>';
      
        echo'<td><div id=showpanchayath></div></td>';






//    echo "<td><input style=width:150px type='text' name='name' id='name' style='width:100px'></td>";  
//    echo "<td><input style=width:150px type='text' name='contno' id='contno' style='width:100px'></td>"; 
//    echo "<td><input style=width:150px type='text' name='place' id='place' style='width:100px'></td>";


    echo '<td><input type=submit name=filter value=Search></td>';   
    
echo"</tr>";


echo"</table>";
echo"<br />";

echo "<table class='selection' style='width:95%'>";

//  if($_POST['sale']!=0){    
//       $result1=DB_query("SELECT * FROM salesman WHERE salesmancode=".$_POST['sale'],$db);
//       $row1=DB_fetch_array($result1);
//       $title1.=' Office:<b><i>'.$row1['salesmanname'].',</i></b>';
//       }
  if($_POST['country']!=NULL){   
       $sql="SELECT * FROM bio_country  where cid=".$_POST['country']."";
       $result=DB_query($sql,$db);
       $myrow1=DB_fetch_array($result);
       $title1.=' : <i>'.$myrow1['country'].'</i>';
       }
  if($_POST['State']!=NULL){
       $sql="SELECT * FROM bio_state where stateid=".$_POST['State']."";
       $result=DB_query($sql,$db);
       $myrow1=DB_fetch_array($result);
       $title1.=' , <i>'.$myrow1['state'].'</i>';
       }
  if($_POST['District']!=NULL){
       $sql="SELECT * FROM bio_district WHERE stateid=14 AND  did=".$_POST['District'];
       $result=DB_query($sql,$db);
       $myrow1=DB_fetch_array($result);
       $title1.=' , <i>'.$myrow1['district'].',</i>';
       }  
       
 if($_POST['taluk']!=NULL){
      $sql="SELECT * FROM bio_taluk where id=".$_POST['taluk'];
      $result=DB_query($sql,$db);
      $myrow1=DB_fetch_array($result);
      $title1.=' , <i>'.$myrow1['taluk'].',</i>';
      }
    
 if($_POST['village']!=NULL){
      $sql="SELECT * FROM bio_village where id=".$_POST['village'];
      $result=DB_query($sql,$db);
      $myrow1=DB_fetch_array($result);
      $title1.=' , <i>'.$myrow1['village'].',</i>';
      }
      
      
  if($_POST['lsgType']==1){
       $result=DB_query("SELECT * FROM bio_corporation where country=".$_POST['country']." AND state=".$_POST['State']." AND district=".$_POST['District'],$db);
       $myrow1=DB_fetch_array($result);
       $title1.=' Corporation: <b><i>'.$myrow1['corporation'].',</i></b>';
       }

elseif($_POST['lsgType']==2){
       $result=DB_query("SELECT * FROM bio_municipality where country=".$_POST['country']." AND state=".$_POST['State']." AND district=".$_POST['District']." AND id=".$_POST['lsgName'],$db);
       $myrow1=DB_fetch_array($result);
       $title1.=' Municipality: <b><i>'.$myrow1['municipality'].',</i></b>';
       }
elseif($_POST['lsgType']==3){
       $result=DB_query("SELECT * FROM bio_block where country=".$_POST['country']." AND state=".$_POST['State']." AND district=".$_POST['District']." AND id=".$_POST['lsgName'],$db);
       $myrow1=DB_fetch_array($result);
       $title1.=' Block: <b><i>'.$myrow1['block'].',</i></b>';
  
  if($_POST['lsgType']!=""){
       $result=DB_query("SELECT * FROM bio_panchayat where country=".$_POST['country']." AND state=".$_POST['State']." AND district=".$_POST['District']." AND block=".$_POST['lsgName']." AND id=".$_POST['gramaPanchayath'],$db);
       $myrow1=DB_fetch_array($result);
       $title1.=' Panchayath: <b><i>'.$myrow1['name'].',</i></b>';   
       }
       }          
        
        
echo "<tr><td colspan='8'><font size='-1'>"."<b>Search Details :".$title1."</b></font></td></tr>";     
echo"</td></tr>"; 
        
        echo '<tr>  <th style=width:5%>' . _('Slno') . '</th>
                    <th style=width:22%>' . _('Customer Name & Address') . '</th>  
                    <th style=width:30%>' . _('Product Name') . '</th>
                    <th style=width:14%>' . _('Unit Price') . '</th>
                    <th style=width:10%>' . _('Quantity') . '</th>
                    <th style=width:14%>' . _('Total Price') . '</th> 
              </tr>';
              
            
              

$sql_so="SELECT debtorsmaster.debtorno, 
                debtorsmaster .name, 
                debtorsmaster .address1, 
                debtorsmaster .address2, 
                debtorsmaster .address3,
                debtorsmaster.cid,
                debtorsmaster.stateid,
                debtorsmaster.did,
                debtorsmaster.taluk,
                debtorsmaster.LSG_type,
                debtorsmaster.LSG_name,
                debtorsmaster.block_name,
                salesorders.orderno,
                custbranch.salesman                     
           FROM debtorsmaster 
                INNER JOIN salesorders ON debtorsmaster.debtorno=salesorders.debtorno
                LEFT JOIN custbranch ON debtorsmaster.debtorno=custbranch.debtorno  
           WHERE custbranch.salesman='".$_POST['sale']."' ";
      
         if(isset($_POST['filter']))
         {
         
     
    
    if (isset($_POST['country']))    {
     if($_POST['country']!=0)   {
     $sql_so .=" AND debtorsmaster.cid=".$_POST['country'];    }
     }
                                                                                
    if (isset($_POST['State']))    {
     if($_POST['State']!=0)   {
     $sql_so .=" AND debtorsmaster.stateid=".$_POST['State'];    }
     }
     
    if (isset($_POST['District']))    {
     if($_POST['District']!=0)   {
     $sql_so .=" AND debtorsmaster.did=".$_POST['District'];
      
    if (isset($_POST['lsgType']))    {
     if($_POST['lsgType']!=0)   {
       if($_POST['lsgType']!=NULL)   {  
         
     $sql_so .=" AND debtorsmaster.LSG_type=".$_POST['lsgType'];    
       } 
    if (isset($_POST['lsgName']) && $_POST['lsgName']!="")    {
     if($_POST['lsgType']==1 OR $_POST['lsgType']==2)   {
     $sql_so .=" AND debtorsmaster.LSG_name=".$_POST['lsgName'];    }
    
       elseif($_POST['lsgType']==3){
       $sql_so .=" AND debtorsmaster.LSG_name=".$_POST['lsgName'];    } 
              
       
    if (isset($_POST['gramaPanchayath']))    {  
      if($_POST['gramaPanchayath']!=0 OR $_POST['gramaPanchayath']!=NULL)   {
      $sql_so .=" AND debtorsmaster.block_name=".$_POST['gramaPanchayath'];    }       
     }
     }
     }
     }   
     }
             
     }    
     
     
 

     
$result_so=DB_query($sql_so,$db);

$k=0;
$slno=1;
while($row_so=DB_fetch_array($result_so))
{ 
    $leadid=$row_so['leadid'];
    $orderno=$row_so['orderno']; 
    
          if ($k==1)
          {
            echo '<tr class="EvenTableRows">';
            $k=0;
          }else 
          {
            echo '<tr class="OddTableRows">';
            $k=1;     
          }
    
    $sql_p="SELECT salesorderdetails.unitprice, 
                   salesorderdetails.quantity, 
                   salesorderdetails.stkcode, 
                   salesorderdetails.unitprice * salesorderdetails.quantity AS total, 
                   stockmaster.stockid, 
                   stockmaster.longdescription
                   FROM salesorderdetails,stockmaster,salesorders
                   WHERE salesorderdetails.orderno=salesorders.orderno
                   AND stockmaster.stockid=salesorderdetails.stkcode 
                   AND salesorderdetails.orderno=$orderno"; 
    $result_p=DB_query($sql_p,$db);
    
   echo"<td>$slno</td>";
   echo"<td>".$row_so['name']."<br>".$row_so['address1']."<br>".$row_so['address2']."<br>".$row_so['address3']."</br></td>";   
    while($row_p=DB_fetch_array($result_p))
    {
        
         echo"<td>".$row_p['longdescription']."</td>
         <td><center>".$row_p['unitprice']."</center></td>  
         <td><center>".$row_p['quantity']."</center></td>  
         <td><center>".$row_p['total']."</center></td>
         </tr>";
         echo"<td></td><td></td>";
        $grandtotal+=$row_p['total']; 
    }
    

                                
 $slno++;                               
    
}

echo "<center><b>Grand Total Amount:</b> <input type='hidden' name='tamount' id='tamount' value='$grandtotal'><b>$grandtotal</b></center>"; 

//echo "<td>Total Amount <input style=width:150px type='text' name='tamount' id='tamount' value='$amount'></td>";

    
} 

echo"</table>";

echo"</fieldset>";
echo"</div>";
echo"</form>";  
?>

<script type="text/javascript"> 

function showstate(str){
   // alert(str); 

if (str=="")
  {
  document.getElementById("showstate").innerHTML="";
  return;
  }
//show_progressbar('showstate');

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
    {                 //  alert(str); 
    document.getElementById("showstate").innerHTML=xmlhttp.responseText;

    }
  }
xmlhttp.open("GET","bio_showstate_incident.php?country=" + str,true);
xmlhttp.send();
}



function showdistrict(str){       
//    alert(str);
str1=document.getElementById("country").value;
//alert(str1);
if (str=="")
  {
  document.getElementById("showdistrict").innerHTML="";
  return;
  }
//show_progressbar('showdistrict');
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
    document.getElementById("showdistrict").innerHTML=xmlhttp.responseText;

    }
  }
xmlhttp.open("GET","bio_showstate_incident.php?state=" + str + "&country1=" + str1,true);
xmlhttp.send();

}

 function showblock(str){   
     str1=document.getElementById("country").value;
     str2=document.getElementById('state').value;
     str3=document.getElementById('Districts').value;
     if(str==1 && (str3==1 || str3==3 || str3==4 || str3==5 || str3==7 || str3==9 || str3==10 || str3==10 || str3==14)){
         alert("No Corporation for this district");
         document.getElementById("block").innerHTML="";
         return;
     }
//alert(str1);   alert(str2);       alert(str3);
 if (str3=="")
  {           
   alert("Please select the district");   
  document.getElementById('Districts').focus(); 
  return;
  }

if (str=="")
  {
  document.getElementById("block").innerHTML="";
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
     document.getElementById("block").innerHTML=xmlhttp.responseText; 
       
    }
  } 
xmlhttp.open("GET","bio_CustlsgSelection.php?block=" + str +"&country1="+ str1 +"&state1="+ str2 +"&district="+ str3,true);
xmlhttp.send(); 
} 
function showtaluk(str){   
     str1=document.getElementById("country").value;
     str2=document.getElementById('state').value;
     str3=document.getElementById('Districts').value;
 if (str3=="")
  {           
   alert("Please select the district");   
  document.getElementById('Districts').focus(); 
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
     document.getElementById("showtaluk").innerHTML=xmlhttp.responseText; 
       
    }
  } 
xmlhttp.open("GET","bio_CustlsgSelection_incident.php?taluk=" + str +"&country1="+ str1 +"&state1="+ str2 +"&district="+ str3 + "&taluk=" + str,true);
xmlhttp.send(); 
}





   function showVillage(str){   
    str1=document.getElementById("country").value;
     str2=document.getElementById('state').value;
     str3=document.getElementById('Districts').value;
      //alert(str);  
 if (str=="")
  {  
      alert("Please select the district");   
  document.getElementById('Districts').focus(); 
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
     document.getElementById("showvillage").innerHTML=xmlhttp.responseText; 
       
    }
  } 
xmlhttp.open("GET","bio_CustlsgSelection_incident.php?village=" + str + "&country1="+ str1 +"&state1="+ str2 +"&district="+ str3,true);
xmlhttp.send(); 
}






 function showgramapanchayath(str){   
    str1=document.getElementById("country").value;
     str2=document.getElementById('state').value;
     str3=document.getElementById('Districts').value;
     // alert(str2);  
 if (str=="")
  {  
      alert("Please select the district");   
  document.getElementById('Districts').focus(); 
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
     document.getElementById("showpanchayath").innerHTML=xmlhttp.responseText; 
       
    }
  } 
xmlhttp.open("GET","bio_CustlsgSelection.php?grama=" + str + "&country1="+ str1 +"&state2="+ str2 +"&district="+ str3,true);
xmlhttp.send(); 
}

</Script>     



