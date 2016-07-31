<?php
      $PageSecurity = 80;
include('includes/session.inc');
$title = _('Document Verfication');  
include('includes/header.inc');

echo '<center><font style="color: #333;
    background:#fff;
    font-weight:bold;
    letter-spacing:0.10em;
    font-size:16px;
    font-family:Georgia;
    text-shadow: 1px 1px 1px #666;">Document Verification</font></center>';
    
    
         if( $_POST['submit'] )
         {
             for ( $i=1;$i<=$_POST['no'];$i++ ) 
             {                                          
                   if($_POST['status'.$i]=='on')
                   {
                     $sql_update="UPDATE bio_documentlist SET status=2,                                              
                                        verifiedDate='".FormatDateForSQL($_POST['dateVer'.$i])."',
                                        verifiedBy='".$_SESSION['UserID']."' 
                                  WHERE docno='".$_POST['docno'.$i]."'
                                  AND   leadid='".$_POST['leadid']."'
                                  AND   orderno='".$_POST['orderno']."'";
                     DB_query($sql_update,$db);  
                   }
             } 
             
             $sql2="SELECT * FROM bio_documentlist WHERE status=1 AND leadid='".$_POST['leadid']."' AND orderno='".$_POST['orderno']."'";
             $result2=DB_query($sql2,$db);
             $count=DB_fetch_array($result2);
             
             if($count==0)
             {
                              
                        ?>
                             <script>
                                    window.close();     
                             </script>
                        <?php              
                   
             }       
         }    
         
         //unset($_SESSION['UserID']);
    
    
    
if($_GET['orderno'])
{ 
     $orderno=$_GET['orderno'];
     $_SESSION['OrderNO']=$orderno;           
}else
{
     $orderno=$_SESSION['OrderNO'];
}
    
    
    
            $sql1="SELECT bio_leads.enqtypeid,
                          bio_leads.leadid  
                   FROM   salesorders,bio_leads
                   WHERE  salesorders.leadid=bio_leads.leadid
                   AND    salesorders.orderno=$orderno";
            $result1=DB_query($sql1,$db);
            $row1=DB_fetch_array($result1);       
           
    
    
//    echo"<table style='border:1px solid #F0F0F0;width:100%'>";
//    echo"<tr><td>";
     

    echo "<form action=" . $_SERVER['PHP_SELF'] . "?" . SID ." method=POST>";    
    
    echo"<fieldset style='width:75%;'>";
    echo"<legend><h3>Documents for Verification</h3></legend>";
    
//    echo "<div style='height:340px; overflow:scroll;'>";
    echo "<table class='selection' style='width:95%;'>";  
     
    echo"<tr><th>Slno</td><th>Document Name</td><th>Select</td><th>Verification Date</td><th>Document No</td></tr>";
    
    
    $sql_docreceived="SELECT bio_document_master.document_name,
                             bio_documentlist.docno,
                             bio_documentlist.receivedDate,
                             bio_documentlist.refno
                      FROM   bio_documentlist,bio_document_master
                      WHERE  bio_document_master.doc_no=bio_documentlist.docno
                      AND    bio_documentlist.orderno=$orderno
                      AND    bio_document_master.enqtypeid='".$row1['enqtypeid']."'
                      AND    bio_documentlist.status=1";
    $result_docreceived=DB_query($sql_docreceived,$db);
    
               
    $i=0;
    while($row_docreceived=DB_fetch_array($result_docreceived)) 
    {
        $i++;
        echo"<tr style='background:#A8A4DB'><td>$i</td>
                                            <td>".$row_docreceived['document_name']."</td> 
                                            <td><input type=checkbox id='status".$i."' name='status".$i."'> 
                                            <input type=hidden id='docno".$i."' name='docno".$i."' value='".$row_docreceived['docno']."'></td> 
                                            <td><input type=text id='dateVer".$i."' class=date alt=".$_SESSION['DefaultDateFormat']." name='dateVer".$i."' value='".Date('d/m/Y')."' onChange='return isDate(this, this.value, ".$_SESSION['DefaultDateFormat'].")'></td>
                                            <td><input type='text' readonly id='autono".$i."' name='autono".$i."' value='".$row_docreceived['refno']."'></td>
                                        </tr>";
                                            
    }
     echo"<tr><td></td><td></td><td width=20%><input type=checkbox id=all name=all onclick=checkAll($i);>Check All</td></tr>";
    
     echo'<input type=hidden name=no id=no value='.$i.'>';
     echo'<input type=hidden name=orderno id=orderno value='.$orderno.'>';
     echo'<input type=hidden name=leadid id=leadid value='.$row1['leadid'].'>';
     
     
    echo"</table>";
    echo"<br />";
    echo"<input type=submit name=submit value=Verify>";
    echo"</fieldset>"; 

    echo"</form>";
    
    echo"</div>";
//    echo"</td></tr></table>";
    
    
    
    
    
      
                   
    
?>

<script type="text/javascript">  

function checkAll(str)
{                                       


        for (i = 1; i <= str; i++)
        {                                             
           var status='status'+i;     
           
           document.getElementById(status).checked=true;
            
        }
   
}
</script>