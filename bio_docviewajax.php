<?php
   $PageSecurity = 80;
include('includes/session.inc');
include ('includes/SQL_CommonFunctions.inc');
$order=$_GET['order'];
{
   $sql="SELECT bio_documentlist.status, bio_document_master.document_name
FROM bio_documentlist, bio_document_master
WHERE bio_documentlist.orderno ='".$order."'
AND bio_documentlist.docno = bio_document_master.doc_no" ;
  $result_doc= DB_query($sql,$db);
  echo '<fieldset>';
 echo '<legend>Document details</legend>';
echo '<table>';
echo '<tr><th>slno</th><th>Name</th>';//

/*while($row_doc=DB_fetch_array($result_doc))
{
    echo '<th>DOC'.$i.'</th>';
    $i++;
} */ 
$sql1="select `debtorsmaster`.`name` from debtorsmaster where `debtorsmaster`.`debtorno` in 
(select `salesorders`.`debtorno` from `salesorders` where `salesorders`.`orderno`='".$order."')";
  $result=DB_query($sql1,$db);
$row1= DB_fetch_array($result);
$name=$row1[0];

$sqldoc="SELECT count( bio_documentlist.status ) , bio_document_master.document_name
FROM bio_documentlist, bio_document_master
WHERE bio_documentlist.orderno =". $order ."
AND bio_documentlist.docno = bio_document_master.doc_no";
$resultdoc=DB_query($sqldoc,$db);
$row=DB_fetch_array($resultdoc);
$num=$row[0];
for($i=1;$i<=$num;$i++)
       {
           echo '<th>DOC'.$i.'</th>';
       } echo '</tr>';  
        echo "<tr><td>1</td><td>".$name."</td>";
while($row_doc=DB_fetch_array($result_doc))
{   
  
if($row_doc['status']<1) {
    $status="No";
    $fontcolor='red';  

}else{
    $status="Yes";
    $fontcolor='blue';
}     

      echo"<td title='".$row_doc['document_name']."'> <font color='$fontcolor'>".$status."</font></td>";    
               

}
echo '</tr></table>';
echo '</fieldset>';
}
?>
