<?php
  $PageSecurity = 80;
include('includes/session.inc');
$title = _('Document Creation');
include('includes/header.inc');

$orderno=$_SESSION['OrderNo'];
$collectedBy=$_SESSION['UserID'];
unset($_SESSION['OrderNo']); 



  if (isset($_POST['submit'])){
  //doctype= 1 -> client,doctype= 2 -> scheme      
$doc1=$_POST['clntdoc_name'];
$n1=sizeof($doc1);

$doc2=$_POST['schemedoc_name'];
$n2=sizeof($doc2);
  
$orderno=$_POST['OrderNo'];  
                 for($i=0;$i<$n1;$i++)   {
                     
                    $doctype=1; ;$status=0; $receivedDate='0000-00-00'; 
$sql = "INSERT INTO bio_documentlist(orderno,docno,doctype,status,receivedDate,collectedBy)
                                  VALUES ('" . $orderno. "',
                                          '" . $doc1[$i] . "',  '" . $doctype . "', 
                                          '" . $status . "','" . $receivedDate . "','" . $collectedBy . "')";                                           
        $result = DB_query($sql,$db);
}  

       for($i=0;$i<$n2;$i++)   {
                     
                    $doctype=2; ;$status=0; $receivedDate='0000-00-00';
$sql = "INSERT INTO bio_documentlist(orderno,docno,doctype,status,receivedDate,collectedBy)
                                  VALUES ('" . $orderno. "',
                                          '" . $doc2[$i] . "',  '" . $doctype . "', 
                                          '" . $status . "','" . $receivedDate . "','" . $collectedBy . "')";                                           
$result = DB_query($sql,$db);
}  
 }
 
echo '<table style=width:35%><tr><td>';
echo '<fieldset style="height:250px">';
echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '">'; 
echo "<table>";     
echo '<legend><b>Document Creation</b></legend>';  
echo "</legend>";
echo "<table border=0 style='width:100%'>";
  
echo '<input type="hidden" name="OrderNo" value="'.$orderno.'"';      
$sql_out="SELECT bio_leads.enqtypeid 
        FROM    bio_leads, salesorders
        WHERE   salesorders.leadid=bio_leads.leadid
        AND     salesorders.orderno=".$orderno;

      $result_out=DB_query($sql_out, $db);
       while($mysql=DB_fetch_array($result_out)){  
       
            $enqtypeid=$mysql['enqtypeid'];   
          }  
          
           
$sql_out="SELECT * FROM bio_clientdocuments WHERE client_id=".$enqtypeid;
            $result_out=DB_query($sql_out, $db);

            while($mysql_out=DB_fetch_array($result_out)){
                
                 $client_id=$mysql_out['client_id'];              
              }

$sql2="SELECT * FROM bio_enquirytypes WHERE enqtypeid=".$client_id;
            $result2=DB_query($sql2, $db);

            while($mysql2=DB_fetch_array($result2)){
                     echo '<br />';
                  $enquirytype=$mysql2['enquirytype'];    
                    echo '<tr><th>Client</th></tr>'; 
$sql3="SELECT * FROM bio_clientdocuments WHERE client_id=".$client_id;
            $result3=DB_query($sql3, $db);

            while($mysql3=DB_fetch_array($result3)){
                
                 $doc_id=$mysql3['doc_id'];              
            
            
$sql4="SELECT * FROM bio_documents WHERE doc_no=".$doc_id;
            $result4=DB_query($sql4, $db);

            while($mysql4=DB_fetch_array($result4)) {
                                     
               //  echo  $doc_name=$mysql4['doc_name']; 
            echo'<tr><td><input type="checkbox" id="clntdoc_name"'.$j.' name="clntdoc_name[]" value='.$doc_id.'>'.$mysql4[1].'</td>';
                    $j++;
                     if($j>=2){
                      echo'</tr><tr><td></td></tr>';
        }                     
                      }
            }     
              }
              
       echo'<tr><td></td></tr>';    echo'<tr><td></td></tr>';
  echo '<tr><th>Scheme</th></tr>';

$sql5="SELECT * FROM bio_schemedocuments";
    $result5=DB_query($sql5, $db);
        while($mysql5=DB_fetch_array($result5)) {    
              $doc_id=$mysql5['doc_id'];
              
              
$sql6="SELECT * FROM bio_documents WHERE doc_no=".$doc_id;
    $result6=DB_query($sql6, $db);     
    
      $j=1;
    while($mysql6=DB_fetch_array($result6)){
        echo'<tr><td><input type="checkbox" id="schemedoc_name"'.$j.' name="schemedoc_name[]" value='.$doc_id.'>'.$mysql6[1].'</td>';
        $j++;
        if($j>=2){
           
        }         
    } 
             echo'<tr><td></td></tr>';    echo'<tr><td></td></tr>';   
        }  echo'<tr><td> <input type="submit" name="submit"  value="Save"></td></tr>'; 
           echo "</table>"; 
           echo '</form></fieldset>';  
           echo "</table>";

?>
