<?php
    $PageSecurity = 80;
include('includes/session.inc');
$title = _('Document Collection');  
include('includes/header.inc');

echo '<center><font style="color: #333;
    background:#fff;
    font-weight:bold;
    letter-spacing:0.10em;
    font-size:16px;
    font-family:Georgia;
    text-shadow: 1px 1px 1px #666;">Old Documents Management</font></center>';
    
    
  
    if( $_POST['submit'] )
    {                                 
                                     
        for ( $i=1;$i<=$_POST['no'];$i++ ) 
        {                                          
               if($_POST['status'.$i]=='on')
               {
                   $_POST['date'.$i];                                       
                   $_POST['autono'.$i];
                   $_POST['doc'.$i];            
                   
                   $_POST['docno1'.$i]; 
                   

                   
                   $sql2="SELECT status FROM bio_oldorderdoclist WHERE docno='".$_POST['doc'.$i]."'
                                                                 
                                                                 AND   orderno='".$_POST['orderno']."'";
                   $result2=DB_query($sql2,$db);
                   $row2=DB_fetch_array($result2);
                   $status=$row2['status'];
                   

                   if($status==0) 
                   {

                           $sql_update="UPDATE bio_oldorderdoclist SET letterno='".$_POST['autono'.$i]."',
                                                                    status=1,
                                                                    receivedDate='".FormatDateForSQL($_POST['date'.$i])."',
                                                                    receivedBy='".$_SESSION['UserID']."' 
                                                              WHERE docno='".$_POST['doc'.$i]."'
                                                              
                                                              AND   orderno='".$_POST['orderno']."'";
                      
                   }elseif( $_POST['docno1'.$i]!=""){
                       
                           $sql_update="UPDATE bio_oldorderdoclist SET letterno='".$_POST['autono'.$i]."',
                                                                    receivedDate='".FormatDateForSQL($_POST['date'.$i])."',
                                                                    receivedBy='".$_SESSION['UserID']."' 
                                                              WHERE docno='".$_POST['doc'.$i]."'
                                                              
                                                              AND   orderno='".$_POST['orderno']."'";
                       
                   }
                   DB_query($sql_update,$db);                                                 
               }              
          }       
      }
      
      
      

      
    
    echo "<form action=" . $_SERVER['PHP_SELF'] . "?" . SID ." method=POST>";  

               
 if($_GET['orderno']!='')
 {
     $enqid=$_GET['enq'];
     

                    
//---------------------------------------------------------------------//     
 echo"<table style='border:1px solid #F0F0F0;width:100%'>";
 echo"<tr><td>";
 
                 
 
//-------------- Inward documents-----------------------
    echo"<fieldset style='width:95%;'>";
    echo"<legend><h3>Inward Documents</h3></legend>";
    echo "<table class='selection' style='width:98%;'>";
    
    echo"<tr><th>Slno</td><th>Document Name</td><th>Select</td><th>Received Date</td><th>Document No</td></tr>";   
    
                        $sql_inwards="SELECT bio_oldorderdoclist.orderno,
                                             bio_oldorderdoclist.docno,
                                             bio_oldorderdoclist.status,
                                             bio_oldorderdoclist.receivedDate,
                                             bio_oldorders.debtorno, 
                                             bio_document_master.document_name 
                                      FROM   bio_oldorderdoclist,bio_document_master,bio_oldorders 
                                      WHERE  bio_oldorderdoclist.orderno =".$_GET['orderno']." 
                                      AND    bio_oldorderdoclist.docno=bio_document_master.doc_no
                                      AND    bio_oldorderdoclist.orderno=bio_oldorders.orderno 
                                      AND    bio_document_master.document_type=1 
                                      AND    bio_document_master.enqtypeid=$enqid"; 
    

                        $result_inwards=DB_query($sql_inwards,$db);              
                        $i=0;
                        while($row_inwards=DB_fetch_array($result_inwards))
                        {
                        $i++;   
                        
                        if($row_inwards['receivedDate'] != NULL ){
                           $datein=convertSQLDate($row_inwards['receivedDate']); 
                        }else{                          
                              $datein="";
                        }  
                        
                        $debtorno=$row_inwards['debtorno'];
                        $autono=$row_inwards['debtorno'].'/'.date('Y').'/'.$row_inwards['docno'];
                        
                                                                                             
                             echo"<tr id='editdocumentin".$i."' style='background:#A8A4DB'>
                                                            <td>$i</td>
                                                            <td>".$row_inwards['document_name']."</td>"; 
                                           if($row_inwards['status']>=1)  {
                                                        echo"<td><input type=checkbox checked id='status".$i."' name='status".$i."'>
                                                                 <input type=hidden id='doc".$i."' name='doc".$i."' value='".$row_inwards['docno']."'></td>  
                                                             <td><input type=text readonly id='date".$i."' name='date".$i."' value='$datein' ></td>
                                                             <td><input type='text' readonly id='autono".$i."' name='autono".$i."' value='$autono'></td>";
                                                        if($row_inwards['status']==2){
                                                                 echo"<td>Verified</td>";
                                                        }else{
                                                                 echo"<td width='35px'><a style='cursor:pointer;' id='$i' onclick='editdocin(this.id);'>" . _('Edit ') . "</a></td>";
                                                        }
                                           }else{
                                                        echo"<td><input type=checkbox id='status".$i."' name='status".$i."'  onchange=idproof('$i'); >
                                                                 <input type=hidden id='doc".$i."' name='doc".$i."' value='".$row_inwards['docno']."'></td> 
                                                             <td><input type=text id='date".$i."' class=date alt=".$_SESSION['DefaultDateFormat']." name='date".$i."' value='$datein' ></td>     
                                                             <td><input type='text' readonly id='autono".$i."' name='autono".$i."' value='$autono'></td>";                                                                                                                                                                         
                                           }          
                             echo"</tr>";
                                
                             
                        }
                        
                        $isum=$i;

    echo "</table>";

    echo "</fieldset>";
//------------------------------------------------------  

echo"</td><td>";

//------------- Outward documents-----------------------
    echo"<fieldset style='width:95%;height:255px'>";
    echo"<legend><h3>Outward Documents</h3></legend>";
    echo "<table class='selection' style='width:98%'>";
    
    echo"<tr><th>Slno</td><th>Document Name</td><th>Select</td><th>Issued Date</td><th>Document No</td></tr>";  
    
    
                       $sql_outwards="SELECT bio_oldorderdoclist.orderno,
                                             bio_oldorderdoclist.docno,
                                             bio_oldorderdoclist.status,
                                             bio_oldorderdoclist.letterno,
                                             bio_oldorderdoclist.receivedDate,   
                                             bio_document_master.document_name 
                                      FROM   bio_oldorderdoclist,bio_document_master 
                                      WHERE  bio_oldorderdoclist.orderno =".$_GET['orderno']." 
                                      AND    bio_oldorderdoclist.docno=bio_document_master.doc_no 
                                      AND    bio_document_master.document_type=2 
                                      AND    bio_document_master.enqtypeid=$enqid";
                            $result_outwards=DB_query($sql_outwards,$db);                        
                            $j=$isum;
                            while($row_outwards=DB_fetch_array($result_outwards))
                            {
                                $j++; 
                                if($row_outwards['receivedDate'] != NULL ){
                                $dateout=convertSQLDate($row_outwards['receivedDate']); 
                                }else{                          
                                $dateout="";
                                } 
                                
                                
                                 echo"<tr id='editdocumentout".$j."' style='background:#A8A4DB'><td>$j</td>
                                                            <td>".$row_outwards['document_name']."</td>"; 
                                                     if($row_outwards['status']>=1)  {
                                                             echo"<td><input type=checkbox checked id='status".$j."' name='status".$j."'>
                                                                      <input type=hidden id='doc".$j."' name='doc".$j."' value='".$row_outwards['docno']."'></td>   
                                                                  <td><input type=text readonly id='date".$j."'  name='date".$j."' value='$dateout' ></td>
                                                                  <td><input type='text' readonly id='autono".$j."' name='autono".$j."' value='".$row_outwards['letterno']."'></td>"; 
                                                             if($row_outwards['status']==2){
                                                                    echo"<td>Verified</td>";
                                                             }else{     
                                                                    echo"<td width='35px'><a style='cursor:pointer;' id='$j' onclick='editdocout(this.id);'>" . _('Edit ') . "</a></td>";
                                                             }
                                                     }else{
                                                              echo"<td><input type=checkbox id='status".$j."' name='status".$j."'>
                                                                       <input type=hidden id='doc".$j."' name='doc".$j."' value='".$row_outwards['docno']."'></td>  
                                                                   <td><input type=text id='date".$j."' class=date alt=".$_SESSION['DefaultDateFormat']." name='date".$j."' value='$dateout'></td>
                                                                   <td><input type='text' id='autono".$j."' name='autono".$j."' value='".$row_outwards['letterno']."'></td>";     
                                                     }
                                 echo"</tr>";
                                  
                                
                            }
                            $no=$j;
                            
    
    echo "</table>";

    echo "</fieldset>";
//------------------------------------------------------   
                                                                          

echo'<input type=hidden name=orderno id=orderno value='.$_GET['orderno'].'>';
echo'<input type=hidden name=debtorno id=debtorno value='.$debtorno.'>';
echo'<input type=hidden name=custid id=custid value='.$row1['cust_id'].'>';
echo'<input type=hidden name=no id=no value='.$no.'>';


echo'<tr><td colspan=2><p><div class="centre">
         <input type=submit name=submit value="' . _('Received/Issued') . '" onclick="if(validation()==1) return false;">
         <input type="Button" class=button_details_show name=details VALUE="' . _('Print') . '">';  
echo"</td></tr>";
echo"</table>";   

//----------------------------------------------------------------------//

echo''; 
 
 echo"</fieldset>";
 
 
 }  
 
 
 
    echo"<fieldset style='width:90%;'>";
    echo"<legend>Old Customers</legend>";
    echo"<table style='border:1px solid #F0F0F0;width:90%'>"; 

echo"<tr><td>Name</td><td>Contact No</td><td>District</td><td>Date From</td><td>Date To</td></tr>";

echo"<tr>"; 


    echo "<td><input style=width:150px type='text' name='name' id='name' style='width:100px'></td>";  
    echo "<td><input style=width:150px type='text' name='contno' id='contno' style='width:100px'></td>"; 
    echo "<td><input style=width:150px type='text' name='district' id='district' style='width:100px'></td>";
      


    echo "<td><input type=text id='periodfrm' class=date alt=".$_SESSION['DefaultDateFormat']." name='periodfrm' value='$_POST[periodfrm]' onChange='return isDate(this, this.value, ".$_SESSION['DefaultDateFormat'].")'></td>";
    echo "<td><input type=text id='periodto' class=date alt=".$_SESSION['DefaultDateFormat']." name='periodto' value='$_POST[periodto]' onChange='return isDate(this, this.value, ".$_SESSION['DefaultDateFormat'].")'></td>";

    echo '<td></td><td><input type=submit name=filter value=Search></td>';
    
echo"</tr>"; 


echo"</table>";
echo"<br />";
    
    echo "<div style='height:320px; overflow:scroll;'>";
    echo"<table style='border:1px solid #F0F0F0;width:90%'>";

echo"<tr><th>SL No</th><th>Order No</th><th>Customer Name</th><th>Contact No</th><th>Client Since</th><th>District</th><th>Customer Type</th></tr>";    
    
    

 $sql_old="SELECT DISTINCT bio_oldorderdoclist.orderno,
                  debtorsmaster.debtorno,
                  debtorsmaster.name,
                  bio_district.district,debtorsmaster.clientsince,custbranch.phoneno,custbranch.faxno  
 
           FROM   bio_oldorderdoclist,bio_oldorders,debtorsmaster,custbranch,bio_district 
           WHERE  bio_oldorders.orderno=bio_oldorderdoclist.orderno
           AND    debtorsmaster.debtorno=bio_oldorders.debtorno 
           AND    debtorsmaster.debtorno=custbranch.branchcode
           AND    bio_district.cid=debtorsmaster.cid
           AND    bio_district.stateid=debtorsmaster.stateid   
           AND    bio_district.did=debtorsmaster.did 
           AND    bio_oldorderdoclist.status=0";
           
           if(isset($_POST['filter']))
           {           
                 
                 if($_POST['name']!="")
                 {
                     $sql_old .= " AND debtorsmaster.name LIKE '".$_POST['name']."%'"; 
                 }
                 if($_POST['contno']!="")
                 {
                     $sql_old .= " AND custbranch.faxno LIKE '".$_POST['contno']."%'"; 
                 }
                 if($_POST['district']!="")
                 {
                     $sql_old .= " AND bio_district.district LIKE '".$_POST['district']."%'"; 
                 }
                 if($_POST['periodfrm']!="" && $_POST['periodto']!="")
                 {
                     $sql_old .= " AND debtorsmaster.clientsince BETWEEN '".FormatDateForSQL($_POST['periodfrm'])."' AND '".FormatDateForSQL($_POST['periodto'])."'"; 
                 }
                 
           }
 //          echo$sql_old; 
 $sql_old.=" order by debtorsmaster.clientsince asc ";
$result_old=DB_query($sql_old,$db);
                           $k=0;
$slno=1;
while($row_old=DB_fetch_array($result_old))
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
    
    
      $orderno=$row_old['orderno'];
    
      $debtorno=$row_old['debtorno'];  
      $first_letter=$debtorno[0];
      if($first_letter=='D'){
          $enq=1;
          $sql3="SELECT enquirytype FROM bio_enquirytypes WHERE enqtypeid=1";
      }elseif($first_letter=='C'){
          $enq=2;
          $sql3="SELECT enquirytype FROM bio_enquirytypes WHERE enqtypeid=2";
      }
      $result3=DB_query($sql3,$db);
      $row3=DB_fetch_array($result3);
     $dat= ConvertSQLDate  ($row_old['clientsince']);
     $ph=$row_old['faxno'];
     if($row_old['faxno']=='')
     {
         $ph= $row_old['phoneno'];
     }
      
      echo"
                <td>".$slno."</td>
               <td>".$row_old['orderno']."</td> 
               <td>".$row_old['name']."</td>
                <td>".$ph."</td> 
               <td>".$dat."</td> 
               <td>".$row_old['district']."</td> 
               <td>".$row3['enquirytype']."</td>
               <td><a style='cursor:pointer;' id='$orderno' onclick='viewolddocs(this.id,$enq);'>" . _('View ') . "</a></td> 
          ";
          $slno++;
    
} 

echo"</table>";
echo"</div>";
echo"</fieldset>";
echo"</form>";      
    
    
?>


<script type="text/javascript">  

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


</script>
