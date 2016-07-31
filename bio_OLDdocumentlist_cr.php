<?php
 $PageSecurity = 81;  

include('includes/session.inc');
$title = _('Document Collection');  
//include('includes/header.inc');

/*echo '<center><font style="color: #333;
    background:#fff;
    font-weight:bold;
    letter-spacing:0.10em;
    font-size:16px;
    font-family:Georgia;
    text-shadow: 1px 1px 1px #666;"></font></center>';    */
    
    

 
   echo "<a href='cc/index.php'>GOTO HOME</a>";    
    echo"<center><div style='background-color: #D5D5DA;'><fieldset style='width:90%;'>";
    echo"<legend><b>Biotech Clients</b></legend>";
    echo"<table style='border:1px solid #F0F0F0;width:80%'>"; 
    
    echo "<form action=" . $_SERVER['PHP_SELF'] . "?" . SID ." method=POST>";

echo"<tr><td>Date From</td><td>Date To</td><td>Name</td><td>Contact No</td><td>Customer Type</td></tr>";
echo"<tr>"; 
//echo "<td><input type=text id='createdfrm' class=date alt=".$_SESSION['DefaultDateFormat']." name='createdfrm' value='$_POST[createdfrm]' onChange='return isDate(this, this.value, ".$_SESSION['DefaultDateFormat'].")'></td>";
//echo "<td><input type=text id='createdto' class=date alt=".$_SESSION['DefaultDateFormat']." name='createdto' value='$_POST[createdto]' onChange='return isDate(this, this.value, ".$_SESSION['DefaultDateFormat'].")'></td>";

    // echo "<td><input style=width:150px type='text' name='district' id='district' style='width:100px'></td>";

    echo "<td><input type=text placeholder='DD/MM/YYYY' id='periodfrm' class=date alt=".$_SESSION['DefaultDateFormat']." name='periodfrm' value='$_POST[periodfrm]' onChange='return isDate(this, this.value, ".$_SESSION['DefaultDateFormat'].")'></td>";
    echo "<td><input type=text placeholder='DD/MM/YYYY' id='periodto' class=date alt=".$_SESSION['DefaultDateFormat']." name='periodto' value='$_POST[periodto]' onChange='return isDate(this, this.value, ".$_SESSION['DefaultDateFormat'].")'></td>";
     echo "<td><input style=width:150px type='text' name='name' id='name' style='width:100px'></td>";  
    echo "<td><input style=width:150px type='text' name='contno' id='contno' style='width:100px'></td>"; 
      echo '<td><select name="enq" id="enq" style="width:150px" onchange=showdocs(this.value)>';
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
    
    /*     if((isset($_POST['regtype'])) AND ($_POST['regtype']!=0))
 {
 echo'<td><select name=regtype>';
     if($_POST['regtype']==1){
        echo'<option value=1>Consolidated</option>';
        echo'<option value=2>Detailed</option>'; 
     }elseif($_POST['regtype']==2){  
        echo'<option value=2>Detailed</option>';
        echo'<option value=1>Consolidated</option>';  
     }
 echo'</select></td>';     
 }else{     
 echo'<td><select name=regtype>
              <option value=2>Detailed</option>
              <option value=1>Consolidated</option>
      </select></td>';
 }  */
    echo "</tr>";
  echo"<tr><td>Country</td><td>State</td><td>District</td><td>Plant</td></tr>";
  


echo"<tr><td><select name='country' id='country' onchange='showstate(this.value)' style='width:150px'>";
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
  echo '<td id="showstate"><select name="State" id="state" onchange="showdistrict(this.value)" style="width:150px">';
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
 echo '<td id="showdistrict"><select name="District" id="Districts" style="width:150px"   onchange="showtaluk(this.value)">'; 
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
    

  /*    echo '<td id=showtaluk>Taluk<select name="Taluk" id="taluk" style="width:130px" tabindex=11 onchange="showVillage(this.value)">';
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
           
echo"<td id=showvillage>Village<select name='Village' id='village' style='width:130px'>";      
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
      
        echo'<td><div id=showpanchayath></div></td>';
                                                               */

   $sql1="SELECT distinct stockmaster.description,stockmaster.stockid from stockmaster,stockcategory,bio_maincat_subcat where stockmaster.categoryid in ('P-LSGD','PDO','OP','FRP-GO','GD','LD','MD','RCC-MS') order by stockmaster.longdescription asc";
      $result1=DB_query($sql1, $db);
      $desp=$myrow1['longdescription'];    
   echo '<td>'; 
   echo '<select name="plant" id="plant" style="width:150px" onchange="showdescription()">';
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

/*  echo"<td id=showPlantstatus><select name='Plantstatus' id='plantstatus' style='width:150px'>";      
  
  $sql="SELECT * FROM bio_plantstatus ORDER BY id";
$result=DB_query($sql,$db);
//echo"<option value=0></option>";
 $f=0;
  while($myrow1=DB_fetch_array($result))
  {  
  if ($myrow1['id']==$_POST['Plantstatus'])  
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
    echo $myrow1['id'] . '">'.$myrow1['plantstatus'];
    echo '</option>';
    $f++;
   }   */ 
   
echo'</td></tr>';   
 // echo'</tr>';
  //echo"<tr>";

  


    echo"<td colspan=2>";
/*    echo '<table id=showdocument>';

 echo'<div id=conso>';   
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

echo'</tr></table></td>';     */

   echo '</td>' ;
/*echo '<td>Status<select name="docstatus" id="docstatus" style="width:100px">';
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
echo '</select></td>';    */
 
//echo"<td style=text-align:right; colspan=2;><input type='submit' name='filterbut' id='filterbut' value='search'></td>";                          
echo '</div>';
 
    echo '<td><input type=submit name=filter value=Search></td>';

//echo"</tr>";
echo"</table>";

  
  
  
  
       
echo"</table>";
echo"<br />";
   echo "<table width=1012px id='leadreport' style='border:2px solid #F0F0F0;'>";      //grid head
      echo "<thead>
         <tr BGCOLOR =#EBEBEB>
         <th width='47px'>" . _('Sl no') . "</th>  
         <th width='300px'>" . _('Customer Name') . "</th>
         <th width='300px'>" . _('Plant') . "</th>
         <th width='170px'>" . _('Contact No') . "</th>   
         <th width='138px'>" . _('Client Since') . "</th>
         <th width='164px'>" . _('District') . "</th>  
         <th width='140px'>" . _('Customer Type') . "</th>
</tr></thead></table>";
/*         <th width='140px'>" . _('Created On') . "</th> */
    
echo "<div style='height:290px; overflow:scroll;'>";
echo"<table  width=1024px style='border:1px solid #F0F0F0;'>";
/*if($_POST['regtype']!=1)   {
echo"<tr style='background-color: #EE0000;'><th>SL No</th><th>Customer Name</th><th>Contact No</th><th>Client Since</th><th>District</th><th>Customer Type</th><th>Created On</th></tr>";    
}else{
echo"<tr><th>No:of orders</th><th>Document Name</th><th>Recieved Documents</th><th>Pending Documents</th></tr>";     
}     */
    

                        $sql_old="SELECT custbranch.brname,custbranch.debtorno,
                                debtorplant_old.plantid as plant,
                                custbranch.phoneno,
                                custbranch.faxno,
                                debtorsmaster.clientsince
                                ,bio_district.district 
                                FROM debtorplant_old,custbranch,debtorsmaster,bio_district
                                WHERE debtorplant_old.debtorno=custbranch.debtorno
                                AND debtorplant_old.debtorno=debtorsmaster.debtorno
                                AND bio_district.did=custbranch.did
                                AND  bio_district.stateid=custbranch.stateid
                                AND  bio_district.cid=custbranch.cid
                                ";
           // AND    bio_oldorderdoclist.status=0
           if(isset($_POST['filter']))
           {           
                
                 if($_POST['name']!="")
                 {
                     $sql_old .= " AND custbranch.name LIKE '".$_POST['name']."%'"; 
                 }
                 if($_POST['contno']!="")
                 {
                     $sql_old .= " AND custbranch.faxno LIKE '".$_POST['contno']."%'"; 
                 }
                 if($_POST['periodfrm']!="" && $_POST['periodto']!="")
                 {
                     $sql_old .= " AND debtorsmaster.clientsince BETWEEN '".FormatDateForSQL($_POST['periodfrm'])."' AND '".FormatDateForSQL($_POST['periodto'])."'"; 
                 }
    if (isset($_POST['country']))    {
     if($_POST['country']!=0)   {
     $sql_old .=" AND custbranch.cid=".$_POST['country'];    }
     }
                                                                                
    if (isset($_POST['State']))    {
     if($_POST['State']!=0)   {
     $sql_old .=" AND custbranch.stateid=".$_POST['State'];    }
     }
       if (isset($_POST['District']))    {
     if($_POST['District']!=0)   {
     $sql_old .=" AND custbranch.did=".$_POST['District'];
      
   
     } 
     }  
     
     if (isset($_POST['plant']))    {
          
     if($_POST['plant']!="")   {        
     $sql_old .=" AND debtorplant_old.plantid='".$_POST['plant']."'";    }
     }  

     
                   if ( $_POST['enq']!=0)
                   {  
                     if ( $_POST['enq']==1){                                   
                       $sql_old .= " AND custbranch.debtorno LIKE 'D%'";  
                     }else if ( $_POST['enq']==2){                                   
                       $sql_old .= " AND custbranch.debtorno LIKE 'C%'";                 
                   }
                   else if ( $_POST['enq']==3){                                   
                       $sql_old .= " AND custbranch.debtorno LIKE 'L%'";                 
                   }
                   }  

           }
           
//$sql_old .=" order by debtorsmaster.clientsince asc ";

         // echo $sql_old;

                    $sql_so="SELECT custbranch.brname,
                                custbranch.debtorno,debtorplant_new.stkcode as plant,
                                custbranch.phoneno,
                                custbranch.faxno,
                                debtorsmaster.clientsince,
                                bio_district.district
                                FROM debtorplant_new,custbranch,debtorsmaster,bio_district
                                WHERE debtorplant_new.debtorno=custbranch.debtorno
                                AND debtorplant_new.debtorno=debtorsmaster.debtorno
                                AND bio_district.did=custbranch.did
                                AND  bio_district.stateid=custbranch.stateid
                                AND  bio_district.cid=custbranch.cid
                                ";
           // AND    bio_oldorderdoclist.status=0
           if(isset($_POST['filter']))
           {           
                
                 if($_POST['name']!="")
                 {
                     $sql_so .= " AND custbranch.name LIKE '".$_POST['name']."%'"; 
                 }
                 if($_POST['contno']!="")
                 {
                     $sql_so .= " AND custbranch.faxno LIKE '".$_POST['contno']."%'"; 
                 }
                 if($_POST['periodfrm']!="" && $_POST['periodto']!="")
                 {
                     $sql_so .= " AND debtorsmaster.clientsince BETWEEN '".FormatDateForSQL($_POST['periodfrm'])."' AND '".FormatDateForSQL($_POST['periodto'])."'"; 
                 }

    if (isset($_POST['country']))    {
     if($_POST['country']!=0)   {
     $sql_so .=" AND custbranch.cid=".$_POST['country'];    }
     }
                                                                                
    if (isset($_POST['State']))    {
     if($_POST['State']!=0)   {
     $sql_so .=" AND custbranch.stateid=".$_POST['State'];    }
     }
       if (isset($_POST['District']))    {
     if($_POST['District']!=0)   {
     $sql_so .=" AND custbranch.did=".$_POST['District'];

     }      
     }  
     
     if (isset($_POST['plant']))    {
          
     if($_POST['plant']!="")   {        
     $sql_so .=" AND debtorplant_new.stkcode='".$_POST['plant']."'";    }
     }  
     
/*      if (isset($_POST['Plantstatus']))    {
     if($_POST['Plantstatus']!=0)   {
     $sql_so .=" AND bio_oldorders.currentstatus=".$_POST['Plantstatus'];    }
     }       */
     
                   if ( $_POST['enq']!=0)
                   {  
                     if ( $_POST['enq']==1){                                   
                       $sql_so .= " AND custbranch.debtorno LIKE 'D%'";  
                     }else if ( $_POST['enq']==2){                                   
                       $sql_so .= " AND custbranch.debtorno LIKE 'C%'";                 
                   }
                   else if ( $_POST['enq']==3){                                   
                       $sql_so .= " AND custbranch.debtorno LIKE 'L%'";                 
                   }
                   } 
                     

           }  
           
          $sql=   $sql_old." UNION ".$sql_so;      


      //     echo $sql;
$result_old=DB_query($sql,$db);

//$count_pending=array();
//$count_received=array();
$k=0;
$slno=0;

while($row_old=DB_fetch_array($result_old))
{       
    
      $debtorno=$row_old['debtorno'];  
      $first_letter=$debtorno[0];
      if($first_letter=='D'){
          $enq=1;
          $sql3="SELECT enquirytype FROM bio_enquirytypes WHERE enqtypeid=1";
      }elseif($first_letter=='C'){
          $enq=2;
          $sql3="SELECT enquirytype FROM bio_enquirytypes WHERE enqtypeid=2";
      }
      elseif($first_letter=='L'){
          $enq=3;
          $sql3="SELECT enquirytype FROM bio_enquirytypes WHERE enqtypeid=3";
      }
      $result3=DB_query($sql3,$db);
      $row3=DB_fetch_array($result3);   
                              
   
$slno++;   
$sql_plant="select stockmaster.description From stockmaster WHERE stockid='".$row_old['plant']."' ";
$res_pl=DB_query($sql_plant,$db);
$row_plant=DB_fetch_array($res_pl);

          if ($k==1)
          {
echo '<tr style="background-color: #EEEEEE;">';  
            $k=0;
          }else 
          {
                    echo '<tr style="background-color: #CCCCCC;">';
            $k=1;     
          }
                                              
      echo"
               <td style='width=50px'>".$slno."</td>
               <td style='width=200px'>".$row_old['brname']."</td>
               <td style='width=200px' title='".$row_plant['description']."'>".$row_old['plant']."</td>
               <td style='width=138px'>".$row_old['phoneno']."<br />".$row_old['faxno']."</td> 
               <td style='width=138px'>".ConvertSQLDate($row_old['clientsince'])."</td> 
               <td style='width=164px'>".$row_old['district']."</td> 
               <td style='width=140px'>".$row3['enquirytype']."</td>
               </tr>
          ";
          

  
 
}

echo"</table>";
echo"</div>";
echo"</fieldset></div></center>";
echo"</form>";    
      echo '<BR><hr><center><B>Goldengate ERP v4.04.4 2012</B></center>';     
     echo '<center><a href="http://tsunamisoftware.co.in"><img src="css/tsunami_logo.gif" width="30" height="25" border="0" alt="Tsunami Software" /></a></center>';                                   
     
// echo "<div id='bottom'></div>";   
    
?>


<script type="text/javascript">  




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

function viewolddocs(str,str1)
{    // alert(str);alert(str1);
    location.href="?orderno="+str+"&enq="+str1;
}

function validation()
{
    var f=0; 
    var no=document.getElementById("no").value;      
    for( i=1; i<= no; i++)
    {
       var status='status'+i;                         
       if(document.getElementById(status).checked==true)
       {                        
           var date='date'+i;    
           if(f==0){f=common_error2(date,'Please Enter a date');  if(f==1) { return f; }} 
           var autono='autono'+i;
           if(f==0){f=common_error2(autono,'Please enter Document No');  if(f==1) { return f; }} 
       }
    }                                                                                           
}

function idproof(str)
{

     var docin='doc'+str;      
     var str1=document.getElementById(docin).value;    //alert(str1); 
     
     if(str1==7 || str1==22 || str1==35) 
     {
                        
         var str2=document.getElementById('debtorno').value;
         
         var status='status'+str;        //alert(status);
        // var str2=document.getElementById(status).checked;     //alert(document.getElementById(status).checked);
         //alert str2;
         if(document.getElementById(status).checked==true)
         {
             controlWindow=window.open("bio_idproof.php?debtorno=" + str2 ,"idproof","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,width=700,height=400");     
         } 
     }                         
}


function editdocin(str){
    
    var id='editdocumentin'+str;
//    alert(id);
    var docid='doc'+str;
    var docno=document.getElementById(docid).value; 
    var rdateid='date'+str;
    var rdate=document.getElementById(rdateid).value;
//    alert(rdate);
    var autoid='autono'+str;
    var autono=document.getElementById(autoid).value;
//    alert(docno);  
//    alert(str);
//    alert(lead); alert(task);
if (str=="")
  {
  document.getElementById(id).innerHTML="";
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
    document.getElementById(id).innerHTML=xmlhttp.responseText;

    }
  } 

xmlhttp.open("GET","bio_documentedit.php?rdate=" + rdate + "&docno=" + docno + "&i="+ str + "&autono=" +autono,true);
xmlhttp.send();    
}



function editdocout(str){
    
    var id='editdocumentout'+str;
//    alert(id);
    var docid='doc'+str;
    var docno=document.getElementById(docid).value; 
    var rdateid='date'+str;
    var rdate=document.getElementById(rdateid).value;
//    alert(rdate);
    var letterid='autono'+str;       
    var letterno=document.getElementById(letterid).value;   
//    alert(letterno); 
//    alert(docno);  
    

if (str=="")
  {
  document.getElementById(id).innerHTML="";
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
    document.getElementById(id).innerHTML=xmlhttp.responseText;

    }
  } 

xmlhttp.open("GET","bio_documentedit.php?rdate=" + rdate + "&docno=" + docno + "&j="+ str + "&letterno=" +letterno,true);
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
/*function showtaluk(str){   
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
}    */





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
xmlhttp.open("GET","bio_Custlsg_Selection.php?grama=" + str + "&country1="+ str1 +"&state2="+ str2 +"&district="+ str3,true);
xmlhttp.send(); 
}







</script>
