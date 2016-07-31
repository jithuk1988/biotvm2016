<?php
 
  $PageSecurity = 40;
include('includes/session.inc');
$title = _('Change Policy') . ' / ' . _('Maintenance');
include('includes/header.inc');
 
//if(isset($_GET['delete'])){ $natid=$_GET['delete'];
//$sql= "DELETE FROM bio_changepolicy WHERE bio_changepolicy.policyid = $natid";
//$result=DB_query($sql,$db); 
//}
 
echo '<p class="page_title_text"><img src="'.$rootpath.'/css/'.$theme.'/images/maintenance.png" title="' . _('Enquiry Types')
    . '" alt="" />' . _('Policy Change Setup') . '</p>';
//echo '<div class="page_help_text">' . _('Add/edit/delete Change Policy') . '</div><br />';
   

echo '<a href="index.php">Back to Home</a>'; 
  
 if (isset($_POST['submit'])){
 $sql = "INSERT INTO bio_changepolicy(policyname,
                                       value)  
                          VALUES ('" . $_POST['policy'] . "',
                                  '" . $_POST['value'] . "')";                      
        $result = DB_query($sql,$db);
} 
  
 echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '">';
echo '<table style=width:25%><tr><td>';
echo '<div id="panel">';
echo '<fieldset style="height:250px">'; 
 echo '<legend><b>Change Policy Master</b></legend>';
echo '<br><br><table class="selection">'; 
 echo '<tr><td>Policy name</td><td><input type="text" name="policy" id="policy"></td></tr>';
echo '<tr><td>Value</td><td><input type="text" name="value" id="pvalue"></td></tr>'; 
echo'<tr><td></td><td> <input type="Submit" name="submit" value="' . _('Submit') . '" onclick=" if(validate()==1)return false"></td></tr>';   
 echo '</table>';
echo '</form></fieldset>'; 
  
 echo '</div>';      
   echo "<fieldset style='width:560px'>";
      echo "<legend><h3>Change Policy Details</h3></legend>";
      echo "<div style='overflow:scroll;height:150px'>";
      echo "<table class='selection' style='width:100%'>";
      echo '<tr><th>' . _('SL NO') . '</th>  
                <th>' . _('Policy name') . '</th>
                <th>' . _('Value') . '</th>
                                <th>' . _('Alter') . '</th>

                          </tr>';
    $sql1="SELECT * FROM bio_changepolicy";
  $result1=DB_query($sql1, $db);  
 $k=0 ;$slno=0; 
  while($myrow=DB_fetch_array($result1) )
  
  {  $cid=$myrow[0]; 
  $slno++;
          $pname=$myrow[1];    
                $pvalue=$myrow[2];
                                   echo"<tr style='background:#A8A4DB'><td>$slno</td><td>$pname</td><td>$pvalue</td><td><a href='#' id='$cid' onclick='edit(this.id)'>Edit</a></td></tr>";      

  // printf('<td>%s</td>
//          <td>%s</td>
//          <td>%s</td>
//          <td><a style=cursor:pointer; id='.$row1[policyid].' onclick=editpolicy(this.id)>' . _('Edit'). '</td>             
//          <td> <a style=cursor:pointer; id='.$row1[policyid].' onclick=deletepolicy(this.id)>' . _('Delete').'</td>',
//         
//          $slno,
//          $row1['policyname'],
//          $row1['value']);
  }            ?>
                      <script>
  function dlt(str){
location.href="?delete=" +str;         
 
}

 function validate()
{     
  
//    document.getElementById('phone').focus();
var f=0;
var p=0;
if(f==0){f=common_error('policy','Please enter the policy');  if(f==1){return f; }  }

  if(f==0){f=common_error('pvalue','Please enter the value');  if(f==1){return f; }  } 
  if(f==0){
      var x=document.getElementById('pvalue').value;  
   if(isNaN(x)||x.indexOf(" ")!=-1)
           {  f=1;
              alert("Please enter Numeric valuethe value"); document.getElementById('pvalue').focus();
              if(x=""){f=0;}
              return f; 
           }
}   }
 </script>
                              <?php
 echo '</td></tr></table>'; 
  
//include('includes/footer.inc');   
?>
