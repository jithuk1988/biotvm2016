<?php
 
$PageSecurity = 80;
include('includes/session.inc');
$title = _('Change Signatory/Approval Authority ') . ' / ' . _('Maintenance');
include('includes/header.inc');
 
//if(isset($_GET['delete'])){ $natid=$_GET['delete'];
//$sql= "DELETE FROM bio_changepolicy WHERE bio_changepolicy.policyid = $natid";
//$result=DB_query($sql,$db); 
//}
 
echo '<p class="page_title_text"><img src="'.$rootpath.'/css/'.$theme.'/images/maintenance.png" title="' . _('Enquiry Types')
    . '" alt="" />' . _('Signatory/Approval Authority Setup') . '</p>';
//echo '<div class="page_help_text">' . _('Add/edit/delete Change Policy') . '</div><br />';
   

echo '<a href="index.php">Back to Home</a>'; 
  
 if (isset($_POST['submit'])){
 $pro_type=$_POST['PolicyType'];
 if($pro_type==1){
    $policy_cat=$_POST['PolicyType'];
    $office_ID=$_POST['Office'];
    $approval_by=$_POST['Approvalby'];
    $signatory_by="";
 }
 else if($pro_type==2){
    $policy_cat=$_POST['Plants'];
    $office_ID=$_POST['Office'];
    $approval_by=$_POST['Approvalby'];
    $signatory_by=$_POST['Signatoryby'];
 }   
     
 
 $sql_fs = "INSERT INTO bio_proposal_permissions(proposal_type,
                                              catagory,
                                              office,
                                              signatory_by,
                                              approved_by)  
                                 VALUES ('" . $pro_type . "',
                                         '" . $policy_cat . "',
                                         '" . $office_ID . "',
                                         '" . $approval_by . "',
                                         '" . $signatory_by . "')";
                     
 $result_fs = DB_query($sql_fs,$db,_('The update/addition  failed because'));
 $msg = _('Feasibility Study Proposal is updated');
 prnMsg($msg,'success');
} 
 
echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '">';
echo '<table style=width:25%><tr><td>';
echo '<div id="panel">';
echo '<fieldset style="height:250px">'; 
echo '<legend><b>Change Signatory/Approval Authority</b></legend>';
echo '<br><br><table width=50%>'; 
echo '<tr><td width=50%>Policy Type</td>';
echo"<td><select name='PolicyType' id='policytype' style='width:150px' onchange='showCatagory(this.value)'>";
$sql="SELECT * FROM bio_policytype";
$result=DB_query($sql,$db);

   echo'<option value=0></option>';
        while($row=DB_fetch_array($result))
        {       
        if ($row['policytype_id']==$_POST['PolicyType'])
        {
         echo '<option selected value="';
        } else {
         echo '<option value="';
        }
        echo $row['policytype_id'] . '">'.$row['policytype_name'];
        echo '</option>';
        }
echo"</select></td></tr>";
echo '</table>';
echo"<table id='catagory' width=50%>";
echo"</table>";

echo"<table id='proplant' width=50%>";
echo"</table>";

echo"<table id='emps' width=50%>";
echo"</table>";

echo"<table>";
//echo '<tr><td>Value</td><td><input type="text" name="value" id="pvalue"></td></tr>'; 
echo'<tr><td></td><td> <input type="Submit" name="submit" value="' . _('Submit') . '" onclick=" if(validate()==1)return false"></td></tr>';   
echo '</table>';
echo '</form></fieldset>'; 
  
 echo '</div>';      
   echo "<fieldset style='width:560px'>";
      echo "<legend><h3>Signatory/Approval Authority</h3></legend>";
      echo "<div style='overflow:scroll;height:150px'>";
      echo "<table class='selection' style='width:100%'>";
      echo '<tr><th>' . _('SL NO') . '</th>  
                <th>' . _('Proposals/Plant') . '</th>
                <th>' . _('Approved By') . '</th>
                <th>' . _('Signatory By') . '</th>
 
           </tr>';
  /*$sql1="SELECT * 
        FROM bio_proposal_permissions,
             bio_policytype,
             bio_policycatagory_proposals,
             bio_policycatagory_plants,
             bio_emp";
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
  }            
*/
 echo '</td></tr></table></div></fieldset>'; 
  
//include('includes/footer.inc');   
?>

<script>
document.getElementById('policytype').focus();
  function dlt(str){
location.href="?delete=" +str;         
 
}

 function validate()
{     
  

var f=0;
var p=0;
if(f==0){f=common_error('policytype','Please select a Policy Type');  if(f==1){return f; }  }
/*
  if(f==0){f=common_error('pvalue','Please enter the value');  if(f==1){return f; }  } 
  if(f==0){
      var x=document.getElementById('pvalue').value;  
   if(isNaN(x)||x.indexOf(" ")!=-1)
           {  f=1;
              alert("Please enter Numeric valuethe value"); document.getElementById('pvalue').focus();
              if(x=""){f=0;}
              return f; 
           }
   }   
*/
}
function showCatagory(str){
    if (str=="")
  {
  document.getElementById("catagory").innerHTML="";
  return;
  }
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
    {
    document.getElementById("catagory").innerHTML=xmlhttp.responseText;

    }
  } 
xmlhttp.open("GET","bio_policycatagory.php?type=" + str,true);
xmlhttp.send();
    
}

function showProposal(str){
    if (str=="")
  {
  document.getElementById("proplant").innerHTML="";
  return;
  }
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
    {
    document.getElementById("proplant").innerHTML=xmlhttp.responseText;

    }
  } 
xmlhttp.open("GET","bio_proposalapproval.php?cat=" + str,true);
xmlhttp.send();
    
}

function showPlants(str){
    if (str=="")
  {
  document.getElementById("proplant").innerHTML="";
  return;
  }
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
    {
    document.getElementById("proplant").innerHTML=xmlhttp.responseText;

    }
  } 
xmlhttp.open("GET","bio_plantapproval.php?plant=" + str,true);
xmlhttp.send();
    
}


function displayEmployees(str1)
{
//alert(str1);
if (str1=="")
  {
  document.getElementById("emps").innerHTML="";     //editleads
  return;
  }
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");                                                          
  }
xmlhttp.onreadystatechange=function()
  {//alert(str1);
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {     //alert("sss");
    document.getElementById("emps").innerHTML=xmlhttp.responseText;

//             $('#showmembers').show(); 

//    document.getElementById('loc').focus(); 
    }
  } 
xmlhttp.open("GET","bio_proposalapproval.php?offid=" + str1,true);
xmlhttp.send();    

}

function displaydes(str1,str2)
{
//alert(str1);
//alert(str2);
if (str1=="")
  {
  document.getElementById("emps").innerHTML="";     //editleads
  return;
  }
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");                                                          
  }
xmlhttp.onreadystatechange=function()
  {//alert(str1);
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {     //alert("sss");
    document.getElementById("emps").innerHTML=xmlhttp.responseText;

//             $('#showmembers').show(); 

//    document.getElementById('loc').focus(); 
    }
  } 
xmlhttp.open("GET","bio_plantapproval.php?offid=" + str1 + "&plant1=" + str2,true);
xmlhttp.send();    

}


 </script>