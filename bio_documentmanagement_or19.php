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
    text-shadow: 1px 1px 1px #666;">Document Management</font></center>';
    
    
    

    
    
  
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
                   

                   
                   $sql2="SELECT status FROM bio_documentlist WHERE docno='".$_POST['doc'.$i]."'
                                                              AND   leadid='".$_POST['leadid']."'
                                                              AND   orderno='".$_POST['orderno']."'";
                   $result2=DB_query($sql2,$db);
                   $row2=DB_fetch_array($result2);
                   $status=$row2['status'];
                   

                   if($status==0) 
                   {

                           $sql_update="UPDATE bio_documentlist SET refno='".$_POST['autono'.$i]."',
                                                                    status=1,
                                                                    receivedDate='".FormatDateForSQL($_POST['date'.$i])."',
                                                                    receivedBy='".$_SESSION['UserID']."' 
                                                              WHERE docno='".$_POST['doc'.$i]."'
                                                              AND   leadid='".$_POST['leadid']."'
                                                              AND   orderno='".$_POST['orderno']."'";
                      
                   }elseif( $_POST['docno1'.$i]!=""){
                       
                           $sql_update="UPDATE bio_documentlist SET refno='".$_POST['autono'.$i]."',
                                                                    receivedDate='".FormatDateForSQL($_POST['date'.$i])."',
                                                                    receivedBy='".$_SESSION['UserID']."' 
                                                              WHERE docno='".$_POST['doc'.$i]."'
                                                              AND   leadid='".$_POST['leadid']."'
                                                              AND   orderno='".$_POST['orderno']."'";
                       
                   }
                   DB_query($sql_update,$db);                                                 
               }              
          }       
      }
      
    //  unset($_SESSION['UserID']); 
      
/*      
    if( $_POST['issued'] )
    {                               
                          
        for ( $i=1;$i<=$_POST['noj'];$i++ ) 
        {
               if($_POST['statusout'.$i]=='on')
               {                 
                   $_POST['dateout'.$i];                                       
                   $_POST['letterno'.$i];
                   $_POST['docout'.$i];
                   
                   $sql_update="UPDATE bio_documentlist SET letterno='".$_POST['letterno'.$i]."',
                                                            status=1,
                                                            recievedDate='".FormatDateForSQL($_POST['dateout'.$i])."' 
                                                      WHERE docno='".$_POST['docout'.$i]."'
                                                      AND   leadid='".$_POST['leadid']."'
                                                      AND   orderno='".$_POST['orderno']."'";
                   DB_query($sql_update,$db);                                                    
               }              
          }       
      }
                     */
      
    
    echo "<form action=" . $_SERVER['PHP_SELF'] . "?" . SID ." method=POST>";  
    

    

    
    if($_GET['orderno'])
    { 
         $orderno=$_GET['orderno'];
         $_SESSION['OrderNO']=$orderno;           
    }
    elseif($_GET['leadid'])
    {
        $sql2="SELECT orderno FROM salesorders WHERE leadid='".$_GET['leadid']."'";
        $result2=DB_query($sql2,$db);
        $row2=DB_fetch_array($result2);
        $count=DB_num_rows($result2);
        
        if($count<1)
        {
            $msg1= 'Sale Order not registered. Register saleOrder';      
            prnMsg($msg1,'failed'); 
            
        }
        
        $orderno=$row2['orderno'];
        $_SESSION['OrderNO']=$orderno;
    }elseif($_GET['lead'])
    {
        $sql2="SELECT orderno FROM salesorders WHERE leadid='".$_GET['lead']."'";
        $result2=DB_query($sql2,$db);
        $row2=DB_fetch_array($result2);
        $count=DB_num_rows($result2);
                if($count<1)
        {
            $msg1= 'Sale Order not registered. Register saleOrder';      
            prnMsg($msg1,'failed'); 
            
        }
        
        $orderno=$row2['orderno'];
        $_SESSION['OrderNO']=$orderno;
    }
    else
    {
             $orderno=$_SESSION['OrderNO']; 
    }        
     //unset($_SESSION['OrderNO']); 

               
 if($orderno!='')
 {
        
    $sql1="SELECT salesorders.leadid,
                  salesorders.debtorno,  
                  bio_cust.cust_id,
                  bio_cust.custname,
                  bio_cust.custmob,
                  bio_leads.enqtypeid,
                  bio_enquirytypes.enquirytype,
                  bio_district.district
           FROM   salesorders,bio_leads,bio_cust,bio_enquirytypes,bio_district 
           WHERE  salesorders.leadid=bio_leads.leadid
           AND    bio_enquirytypes.enqtypeid=bio_leads.enqtypeid      
           AND    bio_cust.cust_id=bio_leads.cust_id 
           AND    bio_district.did=bio_cust.district
           AND    bio_district.stateid=bio_cust.state
           AND    bio_district.cid=bio_cust.nationality 
           AND    salesorders.orderno=".$orderno;
     $result1=DB_query($sql1,$db);
     $row1=DB_fetch_array($result1,$db);
     
     $enqid=$row1['enqtypeid'];
     
          
    
echo"<fieldset style='width:90%;'>";
echo"<legend><h3>Documents view</h3></legend>";

echo '<table class="selection">
            <tr>
                <th> ' . _('Customer Name') . ' :<b> ' . $row1['custname'].'</b></th>
                <th>' . _('District') . ' :<b> ' . $row1['district']. '</b></th>
                <th>' . _('Contact No') . ' :<b> ' . $row1['custmob']. '</b></th>
            </tr>
            <tr>
                <th colspan ="3"><b>' . _('Customer Type:') . ' ' . $row1['enquirytype'] . '</b></th>
            </tr>
            </table>
            <br />';
    
         
//                    echo "<b>Customer Name: </b>".$row1['custname']."<b>District: </b>".$row1['district']."<b>  Contact No: </b>".$row1['custmob']." <b>  Customer Type: </b>".$row1['enquirytype'];
                    
//---------------------------------------------------------------------//     
 echo"<table style='border:1px solid #F0F0F0;width:100%'>";
 echo"<tr><td>";
 
                 
 
//-------------- Inward documents-----------------------
    echo"<fieldset style='width:95%;height:270px'>";
    echo"<legend><h3>Inward Documents</h3></legend>";
    echo "<table class='selection' style='width:98%;'>";
    
    echo"<tr><th>Slno</td><th>Document Name</td><th>Select</td><th>Received Date</td><th>Document No</td></tr>";   
    
                         
    
                        $sql_inwards="SELECT bio_documentlist.docno,
                                             bio_documentlist.refno,   
                                             bio_documentlist.status,
                                             bio_documentlist.receivedDate,
                                             bio_document_master.document_name
                                              
                                      FROM   bio_documentlist,bio_document_master,bio_leads 
                                      WHERE  bio_document_master.doc_no=bio_documentlist.docno
                                      AND    bio_leads.leadid=bio_documentlist.leadid
                                      AND    bio_documentlist.orderno=".$orderno."
                                      AND    bio_document_master.document_type=1
                                      AND    bio_leads.enqtypeid=$enqid";
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
                        
                        
                        $autono=$row1['debtorno'].'/'.date('Y').'/'.$row_inwards['docno'];
                        
                                                                                             
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
                                                             <td><input type=text id='date".$i."' class=date alt=".$_SESSION['DefaultDateFormat']." name='date".$i."' value='$datein' onChange='return isDate(this, this.value, ".$_SESSION['DefaultDateFormat'].")'></td>     
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
    echo"<fieldset style='width:95%;height:270px'>";
    echo"<legend><h3>Outward Documents</h3></legend>";
    echo "<table class='selection' style='width:98%'>";
    
    echo"<tr><th>Slno</td><th>Document Name</td><th>Select</td><th>Issued Date</td><th>Document No</td></tr>";  
    
    
                            $sql_outwards="SELECT bio_documentlist.docno,
                                             bio_documentlist.refno,
                                             bio_documentlist.status,
                                             bio_documentlist.receivedDate,
                                             bio_document_master.document_name
                                              
                                      FROM   bio_documentlist,bio_document_master,bio_leads 
                                      WHERE  bio_document_master.doc_no=bio_documentlist.docno
                                      AND    bio_leads.leadid=bio_documentlist.leadid
                                      AND    bio_documentlist.orderno=".$orderno."
                                      AND    bio_document_master.document_type=2
                                      AND    bio_leads.enqtypeid=$enqid";
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
                                
     $autono1=$row1['debtorno'].'/'.date('Y').'/'.$row_outwards['docno'];  
                
                                 echo"<tr id='editdocumentout".$j."' style='background:#A8A4DB'><td>$j</td>
                                                            <td>".$row_outwards['document_name']."</td>"; 
                                                     if($row_outwards['status']>=1)  {
                                                             echo"<td><input type=checkbox checked id='status".$j."' name='status".$j."'>
                                                                      <input type=hidden id='doc".$j."' name='doc".$j."' value='".$row_outwards['docno']."'></td>   
                                                                  <td><input type=text readonly id='date".$j."'  name='date".$j."' value='$dateout' ></td>
                                                                  <td><input type='text' readonly id='autono".$j."' name='autono".$j."' value='".$row_outwards['refno']."'></td>"; 
                                                             if($row_outwards['status']==2){
                                                                    echo"<td>Verified</td>";
                                                             }else{     
                                                                    echo"<td width='35px'><a style='cursor:pointer;' id='$j' onclick='editdocout(this.id);'>" . _('Edit ') . "</a></td>";
                                                             }
                                                     }else{
                                                              echo"<td><input type=checkbox id='status".$j."' name='status".$j."'>
                                                                       <input type=hidden id='doc".$j."' name='doc".$j."' value='".$row_outwards['docno']."'></td>  
                                                                   <td><input type=text id='date".$j."' class=date alt=".$_SESSION['DefaultDateFormat']." name='date".$j."' value='$dateout' onChange='return isDate(this, this.value, ".$_SESSION['DefaultDateFormat'].")'></td>";
                                                              if($row_outwards['docno']!=11 && $row_outwards['docno']!=27 && $row_outwards['docno']!=39)  {
                                                              echo"<td><input type='text' readonly id='autono".$j."' name='autono".$j."' value=$autono1></td>"; 
                                                              }else{
                                                              echo"<td><input type='text' id='autono".$j."' name='autono".$j."' ></td>";    
                                                              }       
                                                     }
                                 echo"</tr>";
                                     
                            }
                            
     //$k=$j;
            if($enqid==3)
            {
             echo"<tr><td colspan=5><b>LSG Documents</b></td></tr>";
             
                       $sql_lsg="SELECT bio_documentlist.docno,
                                             bio_documentlist.refno,
                                             bio_documentlist.status,
                                             bio_documentlist.receivedDate,
                                             bio_document_master.document_name
                                              
                                      FROM   bio_documentlist,bio_document_master,bio_leads 
                                      WHERE  bio_document_master.doc_no=bio_documentlist.docno
                                      AND    bio_leads.leadid=bio_documentlist.leadid
                                      AND    bio_documentlist.orderno=".$orderno."
                                      AND    bio_document_master.document_type=3
                                      AND    bio_leads.enqtypeid=$enqid";
                      $result_lsg=DB_query($sql_lsg,$db);   
                      while($row_lsg=DB_fetch_array($result_lsg))
                      {
                        $j++; 
                                if($row_lsg['receivedDate'] != NULL ){
                                $datelsg=convertSQLDate($row_lsg['receivedDate']); 
                                }else{                          
                                $datelsg="";
                                } 
                                
                                $autono2=$row1['debtorno'].'/'.date('Y').'/'.$row_lsg['docno'];   
                                
                                    echo"<tr id='editdocumentout".$j."' style='background:#A8A4DB'><td>$j</td>
                                                            <td>".$row_lsg['document_name']."</td>"; 
                                                     if($row_lsg['status']>=1)  {
                                                             echo"<td><input type=checkbox checked id='status".$j."' name='status".$j."'>
                                                                      <input type=hidden id='doc".$j."' name='doc".$j."' value='".$row_lsg['docno']."'></td>   
                                                                  <td><input type=text readonly id='date".$j."'  name='date".$j."' value='$datelsg' ></td>
                                                                  <td><input type='text' readonly id='autono".$j."' name='autono".$j."' value='".$row_lsg['refno']."'></td>"; 
                                                             if($row_lsg['status']==2){
                                                                    echo"<td>Verified</td>";
                                                             }else{     
                                                                    echo"<td width='35px'><a style='cursor:pointer;' id='$j' onclick='editdocout(this.id);'>" . _('Edit ') . "</a></td>";
                                                             }
                                                     }else{
                                                              echo"<td><input type=checkbox id='status".$j."' name='status".$j."'>
                                                                       <input type=hidden id='doc".$j."' name='doc".$j."' value='".$row_lsg['docno']."'></td>  
                                                                   <td><input type=text id='date".$j."' class=date alt=".$_SESSION['DefaultDateFormat']." name='date".$j."' value='$datelsg' onChange='return isDate(this, this.value, ".$_SESSION['DefaultDateFormat'].")'></td>";

                                                              echo"<td><input type='text' readonly id='autono".$j."' name='autono".$j."' value=$autono2></td>"; 

                                                     }
                                 echo"</tr>";
                                  
                      }
               
            }                
                          
                            
                            
                            $no=$j;
                            
    
    echo "</table>";

    echo "</fieldset>";
//------------------------------------------------------   





echo'<input type=hidden name=orderno id=orderno value='.$orderno.'>';
echo'<input type=hidden name=leadid id=leadid value='.$row1['leadid'].'>';
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
 echo"</form>"; 
 
 }  
       
    
    
?>


<script type="text/javascript">  

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
           if(f==0){f=common_error(date,'Please Enter a date');  if(f==1) { return f; }} 
           var autono='autono'+i;
           if(f==0){f=common_error(autono,'Please enter Document No');  if(f==1) { return f; }} 
       }
    }                                                                                           
}

function idproof(str)
{
    
     var docin='doc'+str;      
     var str1=document.getElementById(docin).value;    //alert(str1); 
     
     if(str1==7 || str1==22 || str1==35) 
     {
         
         var str2=document.getElementById('leadid').value;
         
         var status='status'+str;        //alert(status);
        // var str2=document.getElementById(status).checked;     //alert(document.getElementById(status).checked);
         //alert str2;
         if(document.getElementById(status).checked==true)
         {
             controlWindow=window.open("bio_idproof.php?leadid=" + str2 ,"idproof","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,width=700,height=400");     
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
