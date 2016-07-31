<?php
  $PageSecurity = 80;
include('includes/session.inc');
$title = _('Pending Document List');  
include('includes/header.inc');



  echo "<form action=" . $_SERVER['PHP_SELF'] . "?" . SID ." method=post>";  
  
 
echo"<fieldset style='width:65%;'>";
echo"<legend><h3>Search Document List</h3></legend>";



echo"<table style='border:1px solid #F0F0F0; width:80%'>";

//echo"<tr><td>Customer Type</td><td>Document Name</td><td>Document Type</td></tr>";

 echo'<tr>';
 
echo'<td>Period From'; 
echo'<input type="text" name="periodFrom" id="periodFrom" class=date alt='.$_SESSION['DefaultDateFormat'].' style="width:145px"></td>'; 
echo'<td>Period To&nbsp';
echo'<input type="text" name="periodTo" id="periodTo" class=date alt='.$_SESSION['DefaultDateFormat'].' style="width:145px"></td>';       
  
echo'<td>Office';
echo'<select name="office" id="office" onchange="officeteam(this.value)" style="width:100%">';   
echo'<option value=0></option>';
    $sql="SELECT * FROM bio_office";
    $result=DB_query($sql,$db);
    
    while($row=DB_fetch_array($result))
    {
        if ($row['id']==$_POST['office'] )
        {
            echo '<option selected value="';
        } else {
            echo '<option value="';
        }
        echo $row['id'] . '">'.$row['office'];
        echo '</option>';
    }
    echo'</select></td>';    
    
    
echo'<td id=leadteam1>Team';
echo'<select name="teamname" id="leadteam" style="width:100%">';
    $sql="select * from bio_leadteams";
    $result=DB_query($sql,$db);
echo'<option value=0></option>';
    while($row=DB_fetch_array($result))
    {
        if ($row['teamid']==$_POST['teamname'])
        {
    echo '<option selected value="';
        } else {
    echo '<option value="';
        }
    echo $row['teamid'].'">'.$row['teamname'];
        echo '</option>';
    }
    echo'</select></td>';   
echo'</tr>';



echo"<tr>"; 
echo"<td>Country<select name='country' id='country' onchange='showstate(this.value)' style='width:150px'>";
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
  
    echo '<td id="showstate">State<select name="State" id="state" onchange="showdistrict(this.value)" style="width:150px">';
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
 echo '<td id="showdistrict">District<select name="District" id="Districts" style="width:150px"   onchange="showtaluk(this.value)">'; 
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
        
 echo'</tr>';
 
 
 
 
 echo"<tr>";

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


    echo"<td colspan=2>";
    echo '<table id=showdocument><tr>';

    
    echo'<td>From Beneficiary <select name="docname_frm" id="docname_frm" style="width:150px" >';
    echo '<option value=0></option>';   
    $sql1="select * from bio_document_master WHERE document_type=1";
    $result1=DB_query($sql1,$db);
    while($row1=DB_fetch_array($result1))
    {
    //  echo "<option value=$row1[enqtypeid]>$row1[enquirytype]</option>";
        if ($row1['doc_no']==$_POST['docname_frm'])
           {
             echo '<option selected value="';
           } else 
           { 
               echo '<option value="'; 
           }
           echo $row1['doc_no'] . '">'.$row1['document_name'];
           echo '</option>';  
    }

echo '</select></td>';

    echo'<td>To Beneficiary <select name="docname_to" id="docname_to" style="width:150px" >';
    echo '<option value=0></option>';   
    $sql1="select * from bio_document_master WHERE document_type=2";
    $result1=DB_query($sql1,$db);
    while($row1=DB_fetch_array($result1))
    {
    //  echo "<option value=$row1[enqtypeid]>$row1[enquirytype]</option>";
        if ($row1['doc_no']==$_POST['docname_to'])
           {
             echo '<option selected value="';
           } else 
           { 
               echo '<option value="'; 
           }
           echo $row1['doc_no'] . '">'.$row1['document_name'];
           echo '</option>';  
    }

echo '</select></td>';

echo'</tr></table></td>';


echo '<td>Status<select name="docstatus" id="docstatus" style="width:100px">';
echo '<option value=0></option>'; 
$sql1="select * from bio_docstatus";
$result1=DB_query($sql1,$db);
    while($row1=DB_fetch_array($result1))
    {
    //  echo "<option value=$row1[enqtypeid]>$row1[enquirytype]</option>";
        if ($row1['id']==$_POST['docstatus'])
           {
             echo '<option selected value="';
           } else 
           { 
               echo '<option value="'; 
           }
           echo $row1['id'] . '">'.$row1['status'];
           echo '</option>';  
    }
echo '</select></td>';
 
echo"<td style=text-align:right; colspan=2;><input type='submit' name='filterbut' id='filterbut' value='search'></td>";                          

 


echo"</tr>";
echo"</table>";

        echo "<div ><br />";
        echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '">'; 
        echo "<div style='height:400px; overflow:scroll;'>"; 
        echo "<table class='selection' style='width:95%'>";
      
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
         
  if($_POST['periodFrom']!=NULL){
      $title1.='  From: <i>'.$_POST['periodFrom'].',</i>';
      }
  if($_POST['periodTo']!=NULL){
       $title1.='  To: <i>'.$_POST['periodTo'].',</i>';   
       } 
       
if($_POST['office']!=0){    
       $result=DB_query("SELECT * FROM bio_office WHERE id=".$_POST['office'],$db);
       $myrow1=DB_fetch_array($result);
       $title1.=' Office:<b><i>'.$myrow1['office'].',</i></b>';
}
if($_POST['teamname']!=0){    
       $result=DB_query("SELECT * FROM bio_leadteams WHERE teamid=".$_POST['teamname'],$db);
       $myrow1=DB_fetch_array($result);
       $title1.=' Team:<b><i>'.$myrow1['teamname'].',</i></b>';
}              

  if($_POST['enq']!=0){
       $sql="SELECT * FROM bio_enquirytypes where enqtypeid=".$_POST['enq'];
       $result=DB_query($sql,$db);
       $myrow1=DB_fetch_array($result);
       $title1.='  Customer Type: <i>'.$myrow1['enquirytype'].',</i>';
       }   
  if($_POST['docstatus']!=NULL){
       $sql="SELECT * FROM bio_docstatus where id=".$_POST['docstatus'];
       $result=DB_query($sql,$db);
       $myrow1=DB_fetch_array($result);
       $title1.='  Status: <i>'.$myrow1['status'].',</i>';
       }   
  if($_POST['docname_frm']!=0){
       $sql="SELECT * FROM bio_document_master where doc_no=".$_POST['docname_frm'];
       $result=DB_query($sql,$db);
       $myrow1=DB_fetch_array($result);
       $title1.='  From Beneficiary: <i>'.$myrow1['document_name'].',</i>';
       }   
  if($_POST['docname_to']!=0){
       $sql="SELECT * FROM bio_document_master where doc_no=".$_POST['docname_to'];
       $result=DB_query($sql,$db);
       $myrow1=DB_fetch_array($result);
       $title1.='  To Beneficiary: <i>'.$myrow1['document_name'].',</i>';
       }         
       
       
        
        
echo "<tr><td colspan='8'><font size='-1'>"."<b>Search Details".$title1."</b></font></td></tr>";     
    echo"</td></tr>";         
        
        
        echo '<tr>  <th>' . _('Slno') . '</th>
                    <th>' . _('Orderno') . '</th>
                    <th>' . _('Customer') . '</th>
                    <th>' . _('Document Name') . '</th>     
                    <th >' . _('SaleOrder Date') . '</th>  
                    <th class="office">' . _('Office') . '</th> 
                    <th class="team">' . _('Team') . '</th>
              </tr>';
              
              
              
       $sql="SELECT bio_documentlist.id,
                    bio_cust.custname,
                    bio_documentlist.orderno,
                    salesorders.orddate,
                    bio_leadteams.teamname,
                    bio_office.office,
                    bio_document_master.document_name 
              FROM  bio_documentlist,
                    bio_leads,
                    bio_office,
                    bio_cust,
                    salesorders, 
                    bio_leadtask,
                    bio_leadteams,
                    bio_document_master 
              WHERE bio_documentlist.leadid=bio_leads.leadid
                AND bio_document_master.doc_no=bio_documentlist.docno 
                AND bio_leads.cust_id=bio_cust.cust_id 
                AND bio_documentlist.orderno=salesorders.orderno 
                AND bio_leadtask.leadid=bio_documentlist.leadid 
                AND bio_leadtask.taskid=24 
                AND bio_leadtask.viewstatus=1 
                AND bio_leadteams.teamid=bio_leadtask.teamid 
                AND bio_office.id=bio_leadteams.office_id
                AND bio_leadtask.taskcompletedstatus=0";                                       
               
               if(isset($_POST['filterbut']))
               {  
                                     
                   if ( $_POST['enq']!=0)
                   {                                       
                       $sql .= " AND bio_leads.enqtypeid='".$_POST['enq']."'";                   
                   } 
                   
                   if ( $_POST['docname_frm']!=0)
                   {                                       
                       $sql .= " AND bio_documentlist.docno='".$_POST['docname_frm']."'";                   
                   }     
                   
                   if ( $_POST['docname_to']!=0)
                   {                                       
                       $sql .= " AND bio_documentlist.docno='".$_POST['docname_to']."'";                   
                   }     

                   if ($_POST['docstatus']!='' || $_POST['docstatus']!=0)
                   {        
                       $sql .= " AND bio_documentlist.status=".$_POST['docstatus']."";
                   } 
                   
    if (($_POST['office']!='') && ($_POST['office']!='0'))  {
    $sql .=" AND bio_leadteams.office_id='".$_POST['office']."'";
    echo'<input type="hidden" name="off" value='.$_POST['office'].' >'; 
    }

    if (($_POST['teamname']!='') && ($_POST['teamname']!='0'))  {
    $sql .=" AND bio_leadtask.teamid='".$_POST['teamname']."'"; 
    echo'<input type="hidden" name="teamname1" value='.$_POST['teamname'].' >';    
    }
                   
                     if (isset($_POST['country']))    {
                     if($_POST['country']!=0)   {
                     $sql .=" AND bio_cust.nationality=".$_POST['country'];    }
                     }
                   
                     if (isset($_POST['State']))    {
                     if($_POST['State']!=0)   {
                     $sql .=" AND bio_cust.state=".$_POST['State'];    }
                     }
                 
                     if (isset($_POST['District']))    {
                     if($_POST['District']!=0)   {
                     $sql .=" AND bio_cust.district=".$_POST['District'];
                     
                     if (isset($_POST['lsgType']))    {
                     if($_POST['lsgType']!=0 OR $_POST['lsgType']!=NULL)   {                       
                     $sql .=" AND bio_cust.LSG_type=".$_POST['lsgType'];    
                     
                     if (isset($_POST['lsgName']))    {
                     if($_POST['lsgName']!=0 OR $_POST['lsgName']!=NULL)   {
                     $sql .=" AND bio_cust.LSG_name=".$_POST['lsgName'];   }
                     }
                    
                       
                     if (isset($_POST['gramaPanchayath']))    {  
                     if($_POST['gramaPanchayath']!=0 OR $_POST['gramaPanchayath']!=NULL)   {
                     $sql .=" AND bio_cust.block_name=".$_POST['gramaPanchayath'];    }       
                     }      
                                   
                     }
                     }
                          
                     }
                     }
                     
                    
                    if (($_POST['periodFrom']!="") && ($_POST['periodTo']!=""))  { 
                    $periodfrom=FormatDateForSQL($_POST['periodFrom']);   
                    $periodto=FormatDateForSQL($_POST['periodTo']);
                    $sql.=" AND salesorders.orddate BETWEEN '".$periodfrom."' AND '".$periodto."'";
                    }   
                   
                   
               }   
               else
               {
                   $sql .= " AND bio_documentlist.status=0";  
               }
               //echo $sql;

               $result=DB_query($sql,$db);
$slno=1;
 while($row=DB_fetch_array($result) )      {    

 $orddate=ConvertSQLDate($row['orddate']);               
                                     
 echo'<tr style="background:#A8A4DB"><td>$slno</td>
                                     <td>'.$row["orderno"].'</td> 
                                     <td>'.$row["custname"].'</td>
                                     <td>'.$row["document_name"].'</td>     
                                     <td>'.$orddate.'</td> 
                                     <td class="office">'.$row['office'].'</td> 
                                     <td class="team">'.$row['teamname'].'</td> 
                                     </tr>';                                       
                                     
                                     
                                     
                                     
                                     
 $slno++;

 }                                    

 echo '</table></form></div></fieldset>';
 echo "</form>";   
 
?>

<script type="text/javascript"> 

var office=document.getElementById('office').value;
var team=document.getElementById('leadteam').value;
   
if(office!=0)
{
    $(".office").hide(); 
} 

if(team!=0)
{
    $(".team").hide(); 
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
         document.getElementById("showdocument").innerHTML=xmlhttp.responseText; 
    }
  } 
xmlhttp.open("GET","bio_docCustSelection.php?enqid=" + str1,true);
xmlhttp.send(); 
} 

function showstate(str){ 
  //alert(str);
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
    document.getElementById("state").focus();
    }
  }
xmlhttp.open("GET","bio_CustlsgSelection.php?country=" + str,true);
xmlhttp.send();
}

function showdistrict(str){       //alert(str);
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
           document.getElementById('Districts').focus();

    }
  }
xmlhttp.open("GET","bio_CustlsgSelection.php?state=" + str + "&country1=" + str1,true);
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
xmlhttp.open("GET","bio_CustlsgSelection_incident.php?block=" + str +"&country1="+ str1 +"&state1="+ str2 +"&district="+ str3,true);
xmlhttp.send(); 
} 

function officeteam(str)
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
    {     // alert("ddd");
    document.getElementById("leadteam1").innerHTML=xmlhttp.responseText;

    }
  }
xmlhttp.open("GET","bio_pendingDoclist_officeteam.php?officeid=" + str,true);
xmlhttp.send();

}

</script>