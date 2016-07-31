<?php
$PageSecurity = 80;
include('includes/session.inc');
$title = _('FS Proposal Print Option');
$pagetype=1;
include('includes/header.inc');



if(isset($_POST['submit']))
{                                  
    
    $leadid=$_POST['LeadID'];
    $printtype=$_POST['printtype'];
    $letterdate=FormatDateForSQL($_POST['printdate']);
    

        
    $sql_update="UPDATE bio_fsproposal SET fp_refno='".$_POST['refno']."', printtype='".$_POST['printtype']."',printdate='".Date('Y-m-d')."' WHERE leadid='".$_POST['LeadID']."' AND fs_propono='".$_POST['FSID']."'";
    DB_query($sql_update,$db);
    $sql_cust="UPDATE bio_cust SET bio_cust.custname='".$_POST['orgname']."', bio_cust.contactperson='".$_POST['cont']."',bio_cust.area1='".$_POST['area1']."',bio_cust.headdesig='".$_POST['desg']."' WHERE cust_id='".$_POST['custid']."'";
    DB_query($sql_cust,$db);     
  if($_POST['PdfFormat']==1){
        ?>
     <script>  
     var id=<?php echo $leadid;?>;            //alert(id);
     var printtype=<?php echo $printtype;?>; 
                      window.opener.location='bio_feasibility_print.php?leadid=' + id + '&printtype=' + printtype;    
     </script>
<?php  
        

    }elseif($_POST['PdfFormat']==2){
        ?>
             <script>  
     var id=<?php echo $leadid;?>;            //alert(id);
     var printtype=<?php echo $printtype;?>;
                      window.opener.location='bio_feasibility_print1.php?leadid=' + id + '&printtype=' + printtype;    
                      
     </script>
        
<?php
    }
   
}

 
     $lead_ID=$_GET['leadid'];
     $fsid=$_GET['fs_propono'];
     
     $sql7="Select bio_cust.cust_id,bio_cust.custname, bio_cust.contactperson, bio_cust.area1,bio_cust.headdesig from bio_cust,bio_leads where bio_leads.cust_id=bio_cust.cust_id and bio_leads.leadid=".$lead_ID;
       $result_cust=DB_query($sql7,$db);
       $row_cust=DB_fetch_array($result_cust);
       $custid=    $row_cust['cust_id']; 
        $cont =     $row_cust['contactperson'];
        $orgname   =  $row_cust ['custname'];
        $area1  =       $row_cust['area1'];
        $desg  =       $row_cust['headdesig'];  
     $sql_letdate="SELECT printdate,fp_amount FROM bio_fsproposal WHERE fs_propono=$fsid";  
     $result_letdate=DB_query($sql_letdate,$db);
     $row_letdate=DB_fetch_array($result_letdate);
     $fp_amount=$row_letdate['fp_amount'];
     if(($row_letdate['printdate']!='0000-00-00') && ($row_letdate['printdate']!= ''))
//    if($row_letdate['letterdate']!= '0000-00-00')
     {
     $letterdate=ConvertSQLDate($row_letdate['printdate']); 
     }else{
     $letterdate="";
     }
     
echo "<table border=0 style='width:70%';><tr><td>";  
echo "<fieldset style='width:60%;height:310px'>";
echo "<legend><h3>Select feasibility Proposal Format</h3></legend>";   
echo '<form method="post" name="prop">';
   echo '<table>';
        
        echo'<tr><td>Organization:</td><td><input style="width:170px" required type=text name=orgname id=orgname value="'.$orgname.'"></td></tr>';
        echo'<tr><td>Designation:</td><td><input style="width:170px" required type=text name=desg id=desg value="'.$desg.'"></td></tr>'; 
        echo'<tr><td>Kind Attention:</td><td><input style="width:170px" type=text required name=cont id=cont value="'.$cont.'"></td></tr>';
   echo'<tr><td>Area:</td><td><input style="width:170px" required type=text name=area1 id=area1 value="'.$area1.'"></td></tr>';
      

    echo'<tr><td>Letter of interest:</td><td><input type="text" style="width:100px" id="letterdate" class=date alt='.$_SESSION['DefaultDateFormat'].' name="letterdate" value='.$letterdate.' ></td> </td></tr>';                                               

$ref="BTR/WTP/".date("y")."/". $fsid;       
   echo'<tr><td>Reference No:</td><td><input style="width:170px" type=text name=refno id=refno value="'.$ref.'"></td></tr>';
   echo'<tr><td>FSR Amount:</td><td><b>'.number_format($fp_amount,2).'</b></td></tr>';
  
 echo'<tr><td>Select:</td><td><select name=printtype style="width:170px">';
  // echo'<option value=0></option>'; 
  if($fp_amount<=0)
  {
    echo'<option value=3>FSR with Conveyance and Accommodation Only</option>';   
  }else{     
   echo"<option value=1>FSR with Conveyance</option>";
   echo'<option value=2>FSR with Conveyance and Accommodation</option>';   
   }  
//   echo'<option value=4>On line study</option>';  
   echo'</select></td></tr>'; 
   echo'<tr></tr><tr></tr>';
   echo"<tr><td colspan=2 style='background:#8080FF;color:white;'><input type='radio' checked name='PdfFormat' id='pdfformat' value='1' >Feasibility Request with  header</td></tr>";         
   echo"<tr><td colspan=2 style='background:#8080FF;color:white;'><input type='radio' name='PdfFormat' id='pdfformat' value='2' >Feasibility Request without header</td></tr>";



   echo"<tr></tr>";



  echo "<tr><td><td align=center>";
   echo '<button type="submit" style="border: 0; background: transparent" name="submit" id="submit"><img src="css/images/55.jpg" width="100" height="30" alt="submit" /></button></td>';           //   onclick="viewPdf('.$lead_ID.','.$fsid.')"
 //  echo '</td><td><input type="button" style="border: 0; background: transparent" name="email" id="email"><img src="css/images/EmailButton.jpg" width="108" height="42" alt="email" /></td>';    
   echo '</table>';
   echo"<input type='hidden' name='LeadID' id='leadid' value='$lead_ID'>"; 
   echo"<input type='hidden' name='custid' id='custid' value='$custid'>";  
   echo"<input type='hidden' name='FSID' id='fsid' value='$fsid'>";
   
   
  echo '</form>'; 

echo "</fieldset>";
echo "</td></tr></table>";

include('includes/footer.inc');

?>
<script language="JavaScript">


function viewPdf(str1,str2){
    //alert(str1);
  //  alert(str2);
 var count=document.prop.PdfFormat.length;            //alert(count);
 
 for (var i=0; i < count; i++)
   {
   if (document.prop.PdfFormat[i].checked)
      {
       var printtype = document.prop.PdfFormat[i].value;        
      }
   }
  // alert(printtype);
if(printtype==1){
    window.opener.location='bio_feasibility_print.php?leadid=' + str1;

//    controlWindow=window.open("bio_feasibility_print.php?leadid=" + str1,"stdqtnpdf","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=700,height=400");
    window.close(); 
}else if(printtype==2){
  window.opener.location='bio_feasibility_print1.php?leadid=' + str1;   
    window.close(); 
    
}
    else{
   // alert("Select a Format");
    document.getElementById('pdfformat').focus(); 
}


}

</script>