<?php
   $PageSecurity = 80;
include('includes/session.inc');
include ('includes/SQL_CommonFunctions.inc');



  $sqld="Select orderno from salesorders where debtorno='".$_GET['order']."'";
   $resultord=DB_query($sqld,$db);
    $Countm = DB_num_rows($resultord);
        if($Countm==0)
        {
               $sqld2="Select orderno from bio_oldorders where debtorno='".$_GET['order']."'";
   $resultord2=DB_query($sqld2,$db);
    $Countm2 = DB_num_rows($resultord2);
          if($Countm2!=0)
        {
           while($myordo=DB_fetch_array($resultord2))
                 { 
                   $order= $myordo['orderno'];
                   $tp=0;   //Order Type: 1- New Order 0- Old Order
                }  
        }  }  
        else
        {
             while($myord=DB_fetch_array($resultord))
              $order= $myord['orderno'];
              $tp=1;   //Order Type: 1- New Order 0- Old Order
        }
        
        





if(isset($order))
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



if($tp==1)
{
    $sql1="select `custbranch`.`brname`  from custbranch 
INNER JOIN salesorders ON  `salesorders`.`debtorno`=custbranch.debtorno
WHERE `salesorders`.`orderno`='".$order."'"; 
}
else if($tp==0)
{
  $sql1="select `custbranch`.`brname`  from custbranch 
INNER JOIN bio_oldorders ON  `bio_oldorders`.`debtorno`=custbranch.debtorno
WHERE `bio_oldorders`.`orderno`='".$order."'";   
}
//ECHO $sql1;
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
