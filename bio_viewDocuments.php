<?php
    $PageSecurity = 80;
  include('includes/session.inc');
  $title = _('View Documents'); 
  include('includes/header.inc'); 
  
//  $orderno=$_GET['orderno'];
    $leadid=$_GET['lead'];
    

    
if($_GET['letter']!='')
{
    
    $sql_update="UPDATE bio_documentlist SET letterno='".$_GET['letter']."',
                                             recievedDate='".FormatDateForSQL($_GET['date'])."',
                                             status='".$_GET['status']."'
                                             
                                       WHERE leadid='".$_GET['lead']."'
                                       AND   docno='".$_GET['docno']."'";
    DB_query($sql_update,$db);
}    


$sql="SELECT COUNT(*) FROM bio_documentlist WHERE leadid=".$_GET['lead'] ;
$result=DB_query($sql,$db);
$row=DB_fetch_array($result);


  
    echo '<br>';      
    echo '<table style=width:80%><tr><td>';
    echo '<fieldset style=width:95%><br />';
    echo '<legend><b>View Documents</b></legend>';
  


        echo '<form method="POST" action="' . $_SERVER['PHP_SELF'] . '">'; 
        echo "<table class='selection' style='width:100%'>";
        echo '<tr>  
                  <th>' . _('Slno') . '</th>
                  <th>' . _('Document Type') . '</th> 
                  <th>' . _('Document Name') . '</th>
                  <th>' . _('Document No') . '</th>  
                  <th>' . _('Receiving Date') . '</th>  
                  <th>' . _('Status') . '</th> 
              </tr>';
                     
               

           
       $sql_docs="SELECT bio_documentlist.docno,
                         bio_document_type_master.doc_type_name,
                         bio_document_master.document_name,
                         bio_documentlist.letterno,
                         bio_documentlist.recievedDate,
                         bio_documentlist.status
                         
                  FROM   bio_document_master,bio_document_type_master,bio_documentlist
                  WHERE  bio_document_type_master.document_type=bio_document_master.document_type
                  AND    bio_documentlist.docno=bio_document_master.doc_no
                  AND    bio_documentlist.leadid=".$leadid;    
  
       $result_docs=DB_query($sql_docs,$db);
       
       
    $k=0 ;     $slno=1;     $i=1;   
 while($row_docs=DB_fetch_array($result_docs) )      {
 
    $doc_no=$row_docs['docno'];  
    $type=$row_docs['doc_type_name']; 
    $docname=$row_docs['document_name']; 
    $status=$row_docs['status'];
    
    
    echo"<tr style='background:#A8A4DB'>
    
                            <td>$slno</td> 
                            <td>$type</td> 
                            <td>$docname</td>
                            <td><input type=text size=15px name='letter".$i."' id='letter".$i."' value='".$row_docs['letterno']."'></td>                                
                            <td><input type=text id='datefrm".$i."' class=date alt=".$_SESSION['DefaultDateFormat']." name='datefrm".$i."' value='".$row_docs['recievedDate']."' onChange='return isDate(this, this.value, ".$_SESSION['DefaultDateFormat'].")'></td>                                   
                         ";

                       echo"<td> <select name='status".$i."' id='status".$i."'>";
                       
                         
                                    $sql1="select * from bio_docstatus";
                                    $result1=DB_query($sql1,$db);
                                    while($row1=DB_fetch_array($result1))
                                    {
                                        if ($row1['id']==$status) 
                                        {
                                       
                                            echo '<option selected value="';
                                    
                                        } else {
                                        
                                            echo '<option value="';
                                        }
                                    echo $row1['id'] . '">'.$row1['status'];
                                    echo '</option>';
                                    }           
                                                                                              
                        echo" </select></td>";                              
                        echo" <td><a style='cursor:pointer;' id='$doc_no' onclick=update(this.id,'$i','$leadid')>Change Status</a></td>";
                                
                                
    echo"</tr>"; 
  
  $slno++;      
  $i++;
 }

 echo '</table></form>';   
 echo '</fieldset></table>';                           
    
    
    
 /*  
   $rdate=ConvertSQLDate($myrow3['receivedDate']);
   
        if ($k==1){
        echo '<tr class="EvenTableRows">';
        $k=0;
    } else {
        echo '<tr class="OddTableRows">';
        $k=1;
    }  

 //$orderno=$row1['orderno'];
 
if ($doctype==1){ $doctype1='Client';
      
          $query="SELECT  bio_documents.doc_name
          FROM bio_documentlist,bio_clientdocuments,bio_documents
          WHERE  bio_clientdocuments.doc_id=bio_documents.doc_no
          AND bio_clientdocuments.doc_id=".$docno;
$qresult=DB_query($query,$db);
 $qrow=DB_fetch_array($qresult); {
             $name =$qrow['doc_name'];
 } 
      
      
      }    else if ($doctype==2){ $doctype1='Scheme';
      
                   $query="SELECT  bio_documents.doc_name
          FROM bio_documentlist,bio_schemedocuments,bio_documents
          WHERE  bio_schemedocuments.doc_id=bio_documents.doc_no
          AND bio_schemedocuments.doc_id=".$docno;
$qresult=DB_query($query,$db);
$qrow=DB_fetch_array($qresult); {
             $name =$qrow['doc_name'];
 } 
   
      }    */
 

 //echo '<td><input type="text"   name="Rdate'.$i.'" id="rdate'.$i.'" value="'.$rdate.'"></td>';
 //echo '<td> <select name="status'.$i.'" id="status'.$i.'">';



  
 
?>

<script>

   function update(str1,str2,str3){
    //    alert(str1);   alert(str3);
        
          var l='letter'+str2;                   
          var d='datefrm'+str2;
          var s='status'+str2;
         
         var letter=document.getElementById(l).value;                // alert(letter);            
         var date="&date="+document.getElementById(d).value;         //alert(date); 
         var status="&status="+document.getElementById(s).value;     // alert(status);   
         var lead="&lead="+str3;
         var docno="&docno="+str1;
    
location.href="?letter="+letter+date+status+lead+docno;         
 
}

</script>