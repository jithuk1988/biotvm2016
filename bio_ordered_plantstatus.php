<?php

$PageSecurity = 80;
include('includes/session.inc');
$title = _('Order Document List');  
include('includes/header.inc');

echo '<center><font style="color: #333;
    background:#fff;
    font-weight:bold;
    letter-spacing:0.10em;
    font-size:16px;
    font-family:Georgia;
    text-shadow: 1px 1px 1px #666;">Plant status</font></center>';
    
/*echo "<form action=" . $_SERVER['PHP_SELF'] . "?" . SID ." method=POST>";      
    
    echo"<fieldset style='width:90%;'>";
    echo"<legend>Plant status</legend>";
    
    
echo"<table style='border:1px solid #F0F0F0;width:80%'>";          
echo"<tr>";
echo "<td>CreatedON From</td><td>CreatedON To</td><td>Name</td><td>Contact No</td><td>Client From</td><td>Client To</td>";
echo"</tr>";
echo"<tr>"; 
echo "<td><input type=text id='createdfrm' class=date alt=".$_SESSION['DefaultDateFormat']." name='createdfrm' value='$_POST[createdfrm]' onChange='return isDate(this, this.value, ".$_SESSION['DefaultDateFormat'].")'></td>";
echo "<td><input type=text id='createdto' class=date alt=".$_SESSION['DefaultDateFormat']." name='createdto' value='$_POST[createdto]' onChange='return isDate(this, this.value, ".$_SESSION['DefaultDateFormat'].")'></td>";

echo "<td><input style=width:150px type='text' name='name' id='name' style='width:100px'></td>";  
echo "<td><input style=width:150px type='text' name='contno' id='contno' style='width:100px'></td>"; 

echo "<td><input type=text id='periodfrm' class=date alt=".$_SESSION['DefaultDateFormat']." name='periodfrm' value='$_POST[periodfrm]' onChange='return isDate(this, this.value, ".$_SESSION['DefaultDateFormat'].")'></td>";
echo "<td><input type=text id='periodto' class=date alt=".$_SESSION['DefaultDateFormat']." name='periodto' value='$_POST[periodto]' onChange='return isDate(this, this.value, ".$_SESSION['DefaultDateFormat'].")'></td>";
echo"</tr>";

echo"<tr>";    
echo"<td>Country<select name='country' id='country' onchange='showstate(this.value)' style='width:130px'>";
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


  echo '<td id="showstate">State&nbsp;&nbsp;&nbsp;&nbsp;<select name="state" id="state" onchange="showdistrict(this.value)" style="width:130px">';
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
  
 echo '<td id="showdistrict">District&nbsp;&nbsp;&nbsp;<select name="district" id="district" style="width:130px" onchange="showtaluk(this.value);">'; 
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
    

      echo '<td id=showtaluk>Taluk<select name="taluk" id="taluk" style="width:130px" tabindex=11 onchange="showVillage(this.value)">';
      $sql_taluk="SELECT * FROM bio_taluk ORDER BY bio_taluk.taluk ASC";
      $result_taluk=DB_query($sql_taluk,$db);
      $f=0;
      while($myrow7=DB_fetch_array($result_taluk))
      {
      if ($myrow7['id']==$_POST['taluk'])
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
      echo $myrow7['id'] . '">'.$myrow7['taluk'];
      echo '</option>';
      $f++;
      }
      echo '</select>';
      echo '</td>';
          
echo"<td id=showvillage>Village<select name='village' id='village' style='width:130px'>";      
   $sql_taluk="SELECT * FROM bio_village ORDER BY bio_village.village ASC";
      $result_taluk=DB_query($sql_taluk,$db);
      $f=0;
      while($myrow7=DB_fetch_array($result_taluk))
      {
      if ($myrow7['id']==$_POST['village'])
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
      echo $myrow7['id'] . '">'.$myrow7['village'];
      echo '</option>';
      $f++;
      }
      echo '</select>';
      echo'</td>';
      
    echo '<td>LSG Type<select name="lsgType" id="lsgType" style="width:130px" onchange="showblock(this.value)">';
    echo '<option value=0></option>'; 
    echo '<option value=1>Corporation</option>';
    echo '<option value=2>Muncipality</option>';
    echo '<option value=3>Panchayat</option>';           
    echo '</select></td>'; 
    
        echo '<td align=left colspan=2>';
        echo'<div style="align:right" id=block>';             
        echo'</div>';
        echo'</td>';      
  
echo"</tr>";

echo"<tr>";

   $sql1="SELECT distinct stockmaster.description,stockmaster.stockid from stockmaster,stockcategory,bio_maincat_subcat where stockmaster.categoryid in ('P-LSGD','PDO','OP','FRP-GO','GD','LD','MD','RCC-MS') order by stockmaster.longdescription asc";
      $result1=DB_query($sql1, $db);
      $desp=$myrow1['longdescription'];    
   echo '<tr><td>Plant'; 
   echo '<select name="plant" id="plant" style="width:130px" onchange="showdescription()">';
    $f=0;
    
    while($myrow1=DB_fetch_array($result1))
    { 
  if ($myrow1['stockid']==$_POST['plant'])  
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
    echo $myrow1['stockid'] . '">'.$myrow1['description'];
    echo '</option>';                            
    $f++;
    }
  echo '</select></td>'; 
 

    echo '<td>Customer Type<select name="enq" id="enq" style="width:150px" onchange=showdocs(this.value)>';
    echo '<option value=0></option>';   
    $sql1="select * from bio_enquirytypes";
    $result1=DB_query($sql1,$db);
    while($row1=DB_fetch_array($result1))
    {
    //  echo "<option value=$row1[enqtypeid]>$row1[enquirytype]</option>";
        if ($row1['enqtypeid']==$_POST['enq'])
           {
             echo '<option selected value="';
           } else 
           { 
               echo '<option value="'; 
           }
           echo $row1['enqtypeid'] . '">'.$row1['enquirytype'];
           echo '</option>';  
    }

echo '</select></td>';

echo"<td style=text-align:right; colspan=3;><input type='submit' name='filterbut' id='filterbut' value='search'></td>";   

echo"</tr>";
echo"</table>";
echo"</fieldset>";  

echo"</form>";          */

echo"<br />";


        
/*$sql="
        
SELECT id,plantstatus,SUM(count_old) AS old,SUM(count_new) AS new FROM(

    SELECT COUNT(*) AS count_old,0 AS count_new,
             bio_plantstatus.id,
             bio_plantstatus.plantstatus
        FROM bio_oldorders,bio_plantstatus
       WHERE bio_plantstatus.id=bio_oldorders.currentstatus
    GROUP BY bio_oldorders.currentstatus
    UNION 
    SELECT 0 AS count_old,COUNT(*) AS count_new,
           bio_plantstatus.id,
           bio_plantstatus.plantstatus 
        FROM  salesorders,bio_plantstatus
       WHERE bio_plantstatus.id= salesorders.currentstatus
    GROUP BY salesorders.currentstatus

)t1

GROUP BY plantstatus
ORDER BY plantstatus DESC
    
    ";                */
          
/*          $sql1="SELECT count(DISTINCT bio_oldorderdoclist.orderno) AS oldordercount1,0 AS neworders,
                  debtorsmaster.debtorno,
                  debtorsmaster.name,
                  bio_district.district,
                  debtorsmaster.clientsince,
                  custbranch.phoneno,
                  custbranch.faxno,
                  bio_oldorders.createdon  
 
           FROM   bio_oldorderdoclist,bio_oldorders,debtorsmaster,custbranch,bio_district 
           WHERE  bio_oldorders.orderno=bio_oldorderdoclist.orderno
           AND    debtorsmaster.debtorno=bio_oldorders.debtorno 
           AND    debtorsmaster.debtorno=custbranch.debtorno
           AND    bio_district.cid=debtorsmaster.cid
           AND    bio_district.stateid=debtorsmaster.stateid   
           AND    bio_district.did=debtorsmaster.did
           AND bio_oldorders.currentstatus=$plantstatus";              
                           
            $sql2="SELECT count(salesorders.orderno) AS salesorderscount,
                  debtorsmaster.debtorno,
                  debtorsmaster.name,
                  bio_district.district,
                  debtorsmaster.clientsince,
                  custbranch.phoneno,
                  custbranch.faxno,
                  salesorders.orddate  
 
           FROM   salesorders,salesorderdetails,bio_documentlist,debtorsmaster,custbranch,bio_district 
           WHERE  debtorsmaster.debtorno=salesorders.debtorno 
           AND    salesorderdetails.orderno=salesorders.orderno        
           AND    bio_documentlist.orderno=salesorders.orderno
           AND    debtorsmaster.debtorno=custbranch.debtorno
           AND    bio_district.cid=debtorsmaster.cid
           AND    bio_district.stateid=debtorsmaster.stateid   
           AND    bio_district.did=debtorsmaster.did
           AND salesorders.currentstatus=$plantstatus
           ";     */                     
        $sql="Select * from bio_plantstatus";
$result=DB_query($sql,$db);



echo "<div style='height:320px; overflow:scroll;'>";
echo"<table style='border:1px solid #F0F0F0;width:70%'>";


echo"<tr><th>Status</th><th>No: of Orders</th><th>View</th></tr>";  

$k=0;
$constotal=0;
while($myrow=DB_fetch_array($result))
{ 
    $plantstatus= $myrow['id'];
               $sql1="SELECT count(DISTINCT bio_oldorderdoclist.orderno) AS oldordercount1,0 AS neworders,
                  debtorsmaster.debtorno,
                  debtorsmaster.name,
                  bio_district.district,
                  debtorsmaster.clientsince,
                  custbranch.phoneno,
                  custbranch.faxno,
                  bio_oldorders.createdon  
 
           FROM   bio_oldorderdoclist,bio_oldorders,debtorsmaster,custbranch,bio_district 
           WHERE  bio_oldorders.orderno=bio_oldorderdoclist.orderno
           AND    debtorsmaster.debtorno=bio_oldorders.debtorno 
           AND    debtorsmaster.debtorno=custbranch.debtorno
           AND    bio_district.cid=debtorsmaster.cid
           AND    bio_district.stateid=debtorsmaster.stateid   
           AND    bio_district.did=debtorsmaster.did
           AND bio_oldorders.currentstatus=$plantstatus";              
                           
            $sql2="SELECT count( DISTINCT salesorders.orderno ) AS salesorderscount, debtorsmaster.debtorno, debtorsmaster.name, bio_district.district, debtorsmaster.clientsince, custbranch.phoneno, custbranch.faxno, salesorders.orddate
FROM salesorders, salesorderdetails, bio_documentlist, debtorsmaster, custbranch, bio_district
WHERE debtorsmaster.debtorno = salesorders.debtorno
AND salesorderdetails.orderno = salesorders.orderno
AND bio_documentlist.orderno = salesorders.orderno
AND debtorsmaster.debtorno = custbranch.debtorno
AND bio_district.cid = debtorsmaster.cid
AND bio_district.stateid = debtorsmaster.stateid
AND bio_district.did = debtorsmaster.did 
AND salesorders.currentstatus=$plantstatus
           ";    
             $result4=DB_query($sql1,$db);
             $result5=DB_query($sql2,$db);
              $myrow44= DB_fetch_array($result4);
               $myrow55=DB_fetch_array($result5);   // echo"<br>";
       // echo $myrow44['oldordercount1'];             echo"<br>";
     //   echo $myrow55['salesorderscount']."ddd";    
    $total=$myrow44['oldordercount1']+$myrow55['salesorderscount'];
                if($total!=0)
                {
          if ($k==1)
          {
            echo '<tr class="EvenTableRows">';
            $k=0;
          }else 
          {
            echo '<tr class="OddTableRows">';
            $k=1;     
          }
          
          
          
echo"<td>".$myrow['plantstatus']."</td><td>".$total."</td><td><a href id='".$plantstatus."' onclick='showdetails(this.id);return false;'>View</a></td>";       
      $constotal=$constotal+$total;
                }
// $total=0         
}
 echo "<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>";
 echo "<tr><td><b>Total</b></td><td><b>".$constotal."</b></td><td>&nbsp;</td></tr>";
echo"</table>";
echo"</div>";
   include('includes/footer.inc')
     
?>

<script type="text/javascript">  

function showdetails(str)
{          
    controlWindow=window.open("bio_orderDocuments.php?plantid="+str,"plantdetails","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=1200,height=600")   
}


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
xmlhttp.open("GET","bio_lsgFilter.php?country=" + str,true);
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
xmlhttp.open("GET","bio_lsgFilter.php?state=" + str + "&country1=" + str1,true);
xmlhttp.send();

}


function showtaluk(str){                                  
     str1=document.getElementById("country").value;       
     str2=document.getElementById('state').value;         
//     str3=document.getElementById('district').value;      
 if (str=="")
  {           
   alert("Please select the district");   
  document.getElementById('district').focus(); 
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
xmlhttp.open("GET","bio_lsgFilter.php?taluk=" + str +"&country1="+ str1 +"&state1="+ str2  ,true);
xmlhttp.send(); 
}



 function showblock(str){   
     str1=document.getElementById("country").value;
     str2=document.getElementById('state').value;
     str3=document.getElementById('district').value;
     if(str==1 && (str3==1 || str3==3 || str3==4 || str3==5 || str3==7 || str3==9 || str3==10 || str3==10 || str3==14)){
         alert("No Corporation for this district");
         document.getElementById("block").innerHTML="";
         return;
     }
//alert(str1);   alert(str2);       alert(str3);
 if (str3=="")
  {           
   alert("Please select the district");   
  document.getElementById('district').focus(); 
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
xmlhttp.open("GET","bio_lsgFilter.php?block=" + str +"&country1="+ str1 +"&state1="+ str2 +"&district="+ str3,true);
xmlhttp.send(); 
} 



   function showVillage(str){   
    str1=document.getElementById("country").value;
     str2=document.getElementById('state').value;
     str3=document.getElementById('district').value;
      //alert(str);  
 if (str=="")
  {  
      alert("Please select the district");   
  document.getElementById('district').focus(); 
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
xmlhttp.open("GET","bio_lsgFilter.php?village=" + str + "&country1="+ str1 +"&state1="+ str2 +"&district="+ str3,true);
xmlhttp.send(); 
}






 function showgramapanchayath(str){   
    str1=document.getElementById("country").value;
     str2=document.getElementById('state').value;
     str3=document.getElementById('district').value;
     // alert(str2);  
 if (str=="")
  {  
      alert("Please select the district");   
  document.getElementById('district').focus(); 
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
xmlhttp.open("GET","bio_lsgFilter.php?grama=" + str + "&country1="+ str1 +"&state2="+ str2 +"&district="+ str3,true);
xmlhttp.send(); 
}


</script>


