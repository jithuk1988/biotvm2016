<?php
   $PageSecurity = 80;
include('includes/session.inc');


if($_GET['autono']){
    

    $i=$_GET['i'];
    $docno=$_GET['docno'];
    $datein=$_GET['rdate'];
    $autono=$_GET['autono'];

$sql="SELECT document_name FROM bio_document_master WHERE doc_no=$docno";
$result=DB_query($sql,$db);
$row=DB_fetch_array($result);




echo"<td>$i</td>
     <td>".$row['document_name']."</td>
     <td><input type=checkbox checked id='status".$i."' name='status".$i."'>
     <input type=hidden id='doc".$i."' name='doc".$i."' value='".$docno."'></td>  
     <td><input type=text id='date".$i."' name='date".$i."' value='$datein' ></td>
     <td><input type='text' id='autono".$i."' name='autono".$i."' value='$autono'></td>
     <td width='35px'><a style='cursor:pointer;' id='$i' onclick='editdocs(this.id);'>" . _('Edit ') . "</a></td>";
     
     echo "<input type=hidden name='docno1".$i."' id='docno1".$i."' value='$docno'>"; 

 }
 
 
 
 if($_GET['letterno']){
    

    $j=$_GET['j'];
    $docno=$_GET['docno'];
    $dateout=$_GET['rdate'];
    $letterno=$_GET['letterno'];

$sql="SELECT document_name FROM bio_document_master WHERE doc_no=$docno";
$result=DB_query($sql,$db);
$row=DB_fetch_array($result);




echo"<td>$j</td>
     <td>".$row['document_name']."</td>
     <td><input type=checkbox checked id='status".$j."' name='status".$j."'>
     <input type=hidden id='doc".$j."' name='doc".$j."' value='".$docno."'></td>  
     <td><input type=text id='date".$j."' name='date".$j."' value='$dateout' ></td>
     <td><input type='text' id='autono".$j."' name='autono".$j."' value='$letterno'></td>
     <td width='35px'><a style='cursor:pointer;' id='$j' onclick='editdocs(this.id);'>" . _('Edit ') . "</a></td>";
     
     echo "<input type=hidden name='docno1".$j."' id='docno1".$j."' value='$docno'>";

 }
 
 
 
 
 
?>
