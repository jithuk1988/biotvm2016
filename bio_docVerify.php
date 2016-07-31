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
            $leadid=$row1['leadid'];
    
    
//    echo"<table style='border:1px solid #F0F0F0;width:100%'>";
//    echo"<tr><td>";
     

    echo "<form action=" . $_SERVER['PHP_SELF'] . "?" . SID ." method=POST>";    
    
    echo"<fieldset style='width:75%;'>";
    echo"<legend><h3>Documents for Verification</h3></legend>";
    
//    echo "<div style='height:340px; overflow:scroll;'>";
    echo "<table class='selection' style='width:95%;'>";  
     
    echo"<tr><th>Slno</td><th>Document Name</td><th>Select</td><th>Verification Date</td><th>Document Ref No</td></tr>";
    
    
    $sql_docreceived="SELECT bio_document_master.document_name,
                             bio_documentlist.id,
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
        
        echo"<tr style='background:#A8A4DB'><td>$i</td>";
                                           // <td>".$row_docreceived['document_name']."";
                                            echo "<td>";
                                            
                                                echo $row_docreceived['document_name']; 
                                                if($row_docreceived['document_name']=='ID Proof ')  
                                                {
                                                    $documntno=$row_docreceived['docno'];                                                    echo "<a style='cursor:pointer;color:red;text-decoration:none' id='$leadid' onClick='idproof(this.id);'><b>EDIT</b></a>";
                                                }                                             
                                           echo "</td>";
                                            //echo "</td> 
                                            echo "<td><input type=checkbox id='status".$i."' name='status".$i."'> 
                                            <input type=hidden id='docno".$i."' name='docno".$i."' value='".$row_docreceived['docno']."'></td> 
                                            <td><input type=text id='dateVer".$i."' class=date alt=".$_SESSION['DefaultDateFormat']." name='dateVer".$i."' value='".Date('d/m/Y')."' onChange='return isDate(this, this.value, ".$_SESSION['DefaultDateFormat'].")'></td>
                                            <td>"; 
        echo " <input type=hidden id='$leadid' name='$leadid' value=''>";
                                           
if(($row_docreceived['document_name']=='Installation Certificate') || ($row_docreceived['document_name']=='Payment Receipt') || ($row_docreceived['document_name']=='Users Manuel') || ($row_docreceived['document_name']=='Project Completion Report')) 
                                            {
 echo "<input type='text'  id='docrefno".$i."' name='docrefno".$i."' value='".$row_docreceived['refno']."' onchange=updatedoc(this.value,'$leadid','$i');></td>";                                                                    
                                            }
                                             else{
        echo "<input type='text' readonly id='docrefno".$i."' name='docrefno".$i."' value='".$row_docreceived['refno']."'></td>";                      echo " <input type=hidden id='docno".$i."' name='docno".$i."' value='".$row_docreceived['docno']."'>"; 
                                                }
                                        echo "</tr>";
                                            
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
    
   echo "<div id='docupdt'>";
   
   echo "</div>";
    
    
    
      
                   
    
?>

<script type="text/javascript">  
document.getElementById('enquiry').focus(); 
  $(document).ready(function() {
  $('#district1').hide();
      $('#printgrid').hide();
  $("#error").fadeOut(3000);
    $("#warn").fadeOut(3500);
      $("#success").fadeOut(3000);
        $("#info").fadeOut(3000);
                $("#db_message").fadeOut(3000);});
function checkAll(str)
{                                       


        for (i = 1; i <= str; i++)
        {                                             
           var status='status'+i;     
           
           document.getElementById(status).checked=true;
            
        }
   
}

function idproof(str)
{
    //alert(str);
controlWindow1=window.open("bio_docIDVerify.php?idpf=" + str ,"docVerify1","toolbar=no,location=no,directories=yes,status=no,menubar=no,scrollbars=no,resizable=no,width=800,height=300");
//location.href="bio_docVerify.php?orderno=" + str;   

}
 
function updatedoc(refno,leadid,i)
{   
    
  var docno='docno'+i;  //alert(docno);
alert("updated");
var documentnum=document.getElementById(docno).value;//alert(str);
    //    
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
    {     // alert("ddd");
    document.getElementById("docrefno").innerHTML=xmlhttp.responseText;

    }
  }
xmlhttp.open("GET","bio_docverifyupdate.php?ref=" + refno + "&leadid=" + leadid + "&docno=" + documentnum,true);
xmlhttp.send();

}
</script>