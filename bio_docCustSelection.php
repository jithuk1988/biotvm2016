<?php
  $PageSecurity = 80;  
include('includes/session.inc'); 

echo'<div id=conso>';
if($_GET['enqid'])
{

    echo '<tr class=conso><td>From Beneficiary <select name="docname_frm" id="docname_frm" style="width:150px" >';
    echo '<option value=0></option>';   
    $sql1="SELECT * FROM bio_document_master WHERE enqtypeid='".$_GET['enqid']."' AND document_type=1";
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

echo '</select><td>';


    echo '<td class=conso>To Beneficiary <select name="docname_to" id="docname_to" style="width:150px" >';
    echo '<option value=0></option>';   
    $sql1="SELECT * FROM bio_document_master WHERE enqtypeid='".$_GET['enqid']."' AND document_type=2";
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
                              


if($_GET['enqid']==3)
{
       echo '<td class=conso>To LSGD <select name="docname_LSG" id="docname_LSG" style="width:150px" >';
    echo '<option value=0></option>';   
    $sql1="SELECT * FROM bio_document_master WHERE enqtypeid='".$_GET['enqid']."' AND document_type=3";
    $result1=DB_query($sql1,$db);
    while($row1=DB_fetch_array($result1))
    {
    //  echo "<option value=$row1[enqtypeid]>$row1[enquirytype]</option>";
        if ($row1['doc_no']==$_POST['docname_LSG'])
           {
             echo '<option selected value="';
           } else 
           { 
               echo '<option value="'; 
           }
           echo $row1['doc_no'] . '">'.$row1['document_name'];
           echo '</option>';  
    }
}
echo"</tr>";
}

echo'</div>';

 if($_GET['verify']) 
 {
     $orderno=$_GET['verify'];
     if($_GET['p']==1){
         
     
     
                   $sql_update="UPDATE bio_documentlist SET status=2,                                              
                                        verifiedDate='".FormatDateForSQL(Date('d/m/Y'))."',
                                        verifiedBy='".$_SESSION['UserID']."' 
                                  WHERE status=1
                                  AND   orderno='".$orderno."'";
                   DB_query($sql_update,$db); 
    echo "<script type=\"text/javascript\">
         window.setTimeout('window.top.location.href = \"bio_documentVerification.php\"; ',999); 
        
        location.reload(true);
          </script>";                 
     }

 }

 
?>



