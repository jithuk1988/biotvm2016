<?php
  
 $PageSecurity = 80;
include('includes/session.inc');
$title = _('Concept Proposal Letter Details');
$pagetype=1;
include('includes/header.inc'); 
 $c_id=$_GET['cpid'];
  
  
  $lead=$_GET['leadid'];
  
  
  if (isset($_POST['submit'])){
      
   $date=FormatDateForSQL($_POST['LetterDate']);   
   $sql="UPDATE bio_conceptproposal SET letter_no ='".$_POST['LetterNo']."',
                                  letter_date ='".$date."'
                                       where  bio_conceptproposal.cp_id=".$_POST['cpID'];
                                                
   $result = DB_query($sql,$db);
  // $_SESSION['LeadID']=$_POST['LeadID'];
   $_SESSION['ConceptProposalID']=$_POST['cpID'];
   $_SESSION['LeadID']=$_POST['LeadID']; 
  ?>     
    <script>
  controlWindow=window.open("bio_cpIndex.php","selectconceptproposalpdf","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=700,height=400");
//   window.opener.location='bio_selectstdproposalprint.php';
    
    window.close();
    
//         window.location="bio_proposal_coveringletter.php"; 
          </script>
          <?php 
      
   } 
     
      
      
// $sql = "INSERT INTO bio_proposal(letterno,
//                                       letterdate)  
//                          VALUES ('" . $_POST['number'] . "',
//                                  '" . $_POST['date'] . "')";                      
//        $result = DB_query($sql,$db);

  
  
  
  
  
  
  
  
  
  echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '">';
echo '<table style=width:60%><tr><td>';
echo '<div id="panel">';
echo '<fieldset style="height:170px">'; 

 echo '<legend><b>Letter Details</b></legend>';
echo '<br><br><table class="selection">'; 

echo '<tr><td>Letter No</td><td><input type="text" name="LetterNo" id="letterno"></td></tr>';
echo '<tr><td>Letter Date</td><td><input type="text" name="LetterDate" id="letterdate"></td></tr>';

echo '<input type="hidden" name="cpID" id="cpid" value='.$c_id.'>';
echo '<input type="hidden" name="LeadID" id="leadid" value='.$lead.'>';
echo'<tr><td></td><td> <input type="Submit" name="submit" value="' . _('Submit') . '" onclick=" if(validate()==1)return false"></td></tr>';   
 echo '</table>';
echo '</form></fieldset>'; 

  
?>
 <script>
 
  function validate()
{     
  
    document.getElementById('letterno').focus();
var f=0;
var p=0;
if(f==0){f=common_error('letterno','Please enter Letter number');  if(f==1){return f; }  }

  if(f==0){f=common_error('letterdate','Please enter Letter date');  if(f==1){return f; }  } 
  /*
  if(f==0){
      var x=document.getElementById('letterno').value;  
   if(isNaN(x)||x.indexOf(" ")!=-1)
           {  f=1;
              alert("Please enter Numeric value f"); document.getElementById('number').focus();
              if(x=""){f=0;}
              return f; 
           }
}  
*/
 }
 </script>
 
 