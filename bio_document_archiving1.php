<?php
    $PageSecurity = 80;
include('includes/session.inc');
$title = _('Document Archiving');  
//if($_POST['regtype']!=3) { 
include('includes/header.inc');
//}
echo '<center><font style="color: #333;
    background:#fff;
    font-weight:bold;
    letter-spacing:0.10em;
    font-size:16px;
    font-family:Georgia;
    text-shadow: 1px 1px 1px #666;">Document Archiving</font></center>';
    
    
 
 
    echo"<fieldset style='width:90%;'>";
    echo"<legend>Document Archiving</legend>";
      
   echo "<form action=" . $_SERVER['PHP_SELF'] . "?" . SID ." method=POST>";         

   echo "<table style='border:1px solid #F0F0F0;width:80%'>";         
    //echo "<tr><td>Name<td><input style=width:150px type='text' name='name' id='name' style='width:100px'></td></tr>";  
echo"<tr><td width=40%>Country</td><td width=60%><select name='country' id='country' onchange='showstate(this.value)' style='width:130px'>";
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
  echo '</select></td></tr>'; 
  
  echo '<tr><td id="showstate">State</td><td><select name="state" id="state" onchange="showdistrict(this.value)" style="width:130px">';
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
  echo'</td></tr>'; 
  
 echo '<tr><td id="showdistrict">District</td><td><select name="district" id="district" style="width:130px" onchange="showtaluk(this.value);">'; 
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
  echo'</td></tr>';
    

          

      
    echo '<tr><td>LSG Type</td><td><select name="lsgType" id="lsgType" style="width:130px" onchange="showblock(this.value)">';
    echo '<option value=0></option>';  
    echo '<option value=1>Corporation</option>';
    echo '<option value=2>Municipality</option>';   
    echo '<option value=3>Panchayat</option>';           
    echo '</select></td></tr>'; 
  
        echo '<tr><td align=left colspan=2>';
        echo'<div style="align:right" id=block>';             
        echo'</div>';
        echo'</td></tr>';
      
        echo'<tr><td><div id=showpanchayath></div></td><tr>';
echo"<tr><td>Year<td><input style=width:125px type='text' name='year' id='year' style='width:100px' value=".date('Y')."></td></tr>";
        echo "<tr><td>Document Location<td><input style=width:125px type='text' name='docloc' id='docloc' style='width:100px'></td></tr>";
        
 // echo'</tr>';
  //echo"<tr>";


echo '</select></td></tr>';


   
 
//echo"<td style=text-align:right; colspan=2;><input type='submit' name='filterbut' id='filterbut' value='search'></td>";                          
echo '</div>';
 
    echo '<tr><td></td><td><input type="submit" name="submit" id="submit" value="submit"></td></tr>';

//echo"</tr>";
echo"</table>";        
echo"</table>";
//}
echo"</form>"; 
if(isset($_POST['submit'])){
    $sql="SELECT a.debtorno FROM debtorsmaster a                                                                       WHERE  a.cid=".$_POST['country']."";
 if (isset($_POST['year']))    {
   $sql.=" AND year(a.clientsince)=".$_POST['year'];    
 }
 
  if (isset($_POST['country']))    {
     if($_POST['country']!=0)   {
     $sql.=" AND a.cid=".$_POST['country'];    }
     }
                                                                                
    if (isset($_POST['state']))    {
     if($_POST['State']!=0)   {
     $sql.=" AND a.stateid=".$_POST['state'];    }
     }
       if (isset($_POST['district']))    {
     if($_POST['district']!=0)   {
     $sql.=" AND a.did=".$_POST['district'];
      
     if (isset($_POST['lsgType']))    {
     if($_POST['lsgType']!=0)   {
       if($_POST['lsgType']!=NULL)   {  
         
     $sql.=" AND a.LSG_type=".$_POST['lsgType'];    
     
     if (isset($_POST['lsgName']))    {
     if($_POST['lsgName']==1 OR $_POST['lsgName']==2)   {
$sql.=" AND a.LSG_name=".$_POST['lsgName'];    }
    
       elseif($_POST['lsgName']==3){
       $sql.=" AND a.LSG_name=".$_POST['lsgName'];    } 
              
       }
       
       if (isset($_POST['gramaPanchayath']))    {  
      if($_POST['gramaPanchayath']!=0 OR $_POST['gramaPanchayath']!=NULL)   {
$sql.=" AND a.block_name=".$_POST['gramaPanchayath'];    }       
     }
     }
     }
     }   
     }        
     } 

$result=DB_query($sql,$db);
while($row=DB_fetch_array($result)){
   
$sql1="UPDATE debtorsmaster SET docloc='".$_POST['docloc']."' WHERE debtorsmaster.debtorno='".$row['debtorno']."'";
$result1=DB_query($sql1,$db);   

if (isset($_POST['lsgType']))    {
     if($_POST['lsgType']!=0)   {
       if($_POST['lsgType']!=NULL)   {  
         
  
     
     if (isset($_POST['lsgName']))    {         
     if($_POST['lsgType']==1){
 $sql1="UPDATE bio_corporation SET docloc='".$_POST['docloc']."' WHERE bio_corporation.country=".$_POST['country']."
                    AND bio_corporation.state=".$_POST['state']." AND bio_corporation.district=".$_POST['district'];
$result1=DB_query($sql1,$db);         
     }
     if($_POST['lsgType']==2)   {
 $sql1="UPDATE bio_municipality SET docloc='".$_POST['docloc']."' WHERE bio_municipality.country=".$_POST['country']."
                    AND bio_municipality.state=".$_POST['state']." AND bio_municipality.district=".$_POST['district']."
                    AND bio_municipality.id=".$_POST['lsgName'];
$result1=DB_query($sql1,$db);       }
    
       elseif($_POST['lsgType']==3){
 echo$sql1="UPDATE bio_panchayat SET docloc='".$_POST['docloc']."' WHERE bio_panchayat.country=".$_POST['country']."
                    AND bio_panchayat.state=".$_POST['state']." AND bio_panchayat.district=".$_POST['district']."
                    AND bio_panchayat.block=".$_POST['gramaPanchayath']." 
                    AND bio_panchayat.id=".$_POST['lsgName'];
   $result1=DB_query($sql1,$db);       } 
              
       }      

     }
     }
     }
    
     } prnMsg( _('document location has been added'),'success');
              
     } 
   
    
?>


<script type="text/javascript">  

        

function viewdocuments(str)
{      
//return false;                        // alert(str);
    var split = str.split('-');  
    var split1=split[0];             
    var split2=split[1]; 
    var split3=split[2];
    
    if(split1==1)
    {      
       controlWindow=window.open("bio_OLDdocumentmanagement.php?orderno="+split2+"&enq="+split3,"docdetails","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=1200,height=600");     
   // return false;
    }
    else if(split1==2)
    {     
       controlWindow=window.open("bio_documentmanagement.php?orderno="+split2,"plantdetails","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=1200,height=600") 
  //  return false;
    }            
}
 function viewcustomer(str4)
 {
     controlWindow=window.open("Customers.php?DebtorNo="+str4,"customer","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=1200,height=600");
 }

function consolidated(str)
{
    
    if(str==1) {
    $(".conso").hide();  
    }else{
    $(".conso").show();     
    }    
}


function showdocs(str1){   

//alert(str1);       



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
if(document.getElementById('regtype').value!=1)
{
         document.getElementById("showdocument").innerHTML=xmlhttp.responseText;      }
    }
  } 
xmlhttp.open("GET","bio_docCustSelection.php?enqid=" + str1,true);
xmlhttp.send(); 
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
xmlhttp.open("GET","bio_lsgArc.php?country=" + str,true);
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
xmlhttp.open("GET","bio_lsgArc.php?state=" + str + "&country1=" + str1,true);
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
xmlhttp.open("GET","bio_lsgArc.php?taluk=" + str +"&country1="+ str1 +"&state1="+ str2  ,true);
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
xmlhttp.open("GET","bio_lsgArc.php?block=" + str +"&country1="+ str1 +"&state1="+ str2 +"&district="+ str3,true);
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
xmlhttp.open("GET","bio_lsgArc.php?village=" + str + "&country1="+ str1 +"&state1="+ str2 +"&district="+ str3,true);
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
     document.getElementById("showgramapanchayath").innerHTML=xmlhttp.responseText; 
       
    }
  } 
xmlhttp.open("GET","bio_lsgArc.php?grama=" + str + "&country1="+ str1 +"&state2="+ str2 +"&district="+ str3,true);
xmlhttp.send(); 
}




</script>
