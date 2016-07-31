<?php
  $PageSecurity = 80;
include('includes/session.inc');
$title = _('Std Proposal Print Option');
$pagetype=1;
include('includes/header.inc');

/*$lead_ID=$_GET['leadid'];
$proposalid=$_GET['propid'];*/

 if($_GET['leadid']!="" AND $_GET['propid']!=""){
     $lead_ID=$_GET['leadid'];
     $proposalid=$_GET['propid'];
      
  }elseif($_SESSION['LeadID']!="" AND $_SESSION['ProposalID']!=""){
      
     $lead_ID=$_SESSION['LeadID'];
     $proposalid=$_SESSION['ProposalID'];
     unset($_SESSION['LeadID']);
     unset($_SESSION['ProposalID']);
  }
  


echo "<table border=0 style='width:70%';><tr><td>";  
echo "<fieldset style='width:70%;height:200px'>";
echo "<legend><h3>Select Quatation Format</h3></legend>";   
echo '<form method="post" name="prop">';
   echo '<table>';
   echo'<tr><td style="background:#8080FF;color:white;">';
   echo"<input type='radio' name='PdfFormat' id='pdfformat' value='1' >Print Proposal with header"; 
   echo '</td></tr><tr><td style="background:#8080FF;color:white;">';
   echo"<input type='radio' name='PdfFormat' id='pdfformat' value='2' >Print Proposal without header";
   echo '</td></tr><tr><td style="background:#8080FF;color:white;">';
   echo"<input type='radio' name='PdfFormat' id='pdfformat' value='3' >Print Quatation with header";
   echo '</td></tr><tr><td style="background:#8080FF;color:white;">';
   echo"<input type='radio' name='PdfFormat' id='pdfformat' value='4' >Print Quatation without header";
   
   echo"<tr></tr>";
   echo"<tr></tr>";
   echo"<tr></tr>";
   echo"<tr></tr>";
   echo "<tr><td align=center>";
   echo '<input type="button" name="Print" id="print" value="Print" onclick="viewPdf('.$lead_ID.','.$proposalid.')">';  
   echo "</td>";    
   echo '</table>';
   echo"<input type='hidden' name='LeadID' id='leadid' value='$lead_ID'>"; 
   echo"<input type='hidden' name='PID' id='dprid' value='$proposalid'>";
   
  echo '</form>'; 

echo "</fieldset>";
echo "</td></tr></table>";

include('includes/footer.inc');
 
?>

<script language="JavaScript">


function viewPdf(str1,str2){
 var count=document.prop.PdfFormat.length;   
 
 for (var i=0; i < count; i++)
   {
   if (document.prop.PdfFormat[i].checked)
      {
       var printtype = document.prop.PdfFormat[i].value;
      }
   }

if(printtype==1){

    controlWindow=window.open("bio_proposal_coveringletter1.php?leadid=" + str1 +"&propid=" + str2,"stdqtnpdf","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=700,height=400");
    window.close(); 
}else if(printtype==2){
    controlWindow=window.open("bio_proposal_coveringletter2.php?leadid=" + str1 +"&propid=" + str2,"stdqtnpdf","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=700,height=400");
    window.close(); 
}else if(printtype==3){
    controlWindow=window.open("bio_proposal_coveringletter3.php?leadid=" + str1 +"&propid=" + str2,"stdqtnpdf","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=700,height=400");
    window.close(); 
}else if(printtype==4){
    controlWindow=window.open("bio_proposal_coveringletter4.php?leadid=" + str1 +"&propid=" + str2,"stdqtnpdf","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=700,height=400");
    window.close(); 
}else{
    alert("Select a Format");
    document.getElementById('pdfformat').focus(); 
}


}

</script>