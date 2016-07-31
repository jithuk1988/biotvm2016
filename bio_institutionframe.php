<?php
$PageSecurity = 40;
include('includes/session.inc');
$title = _('Institution Master') . ' / ' . _('Maintenance');
include('includes/header.inc'); 
echo '<p class="page_title_text"><img src="'.$rootpath.'/css/'.$theme.'/images/maintenance.png" title="' . _('Enquiry Types')
    . '" alt="" />' . _('institution frame Setup') . '</p>';  
echo '<a href="bio_institutionframe.php">Back to Home</a>'; 
if(isset($_GET['delete']))
  { 
$did=$_GET['delete'];    
$sql= "delete from bio_institution where bio_institution.inst_id= $did";
$result=DB_query($sql,$db); 
}
if(isset($_GET['edit'])){
$eid=$_GET['edit']; 
$sql5="SELECT * FROM bio_institution  WHERE bio_institution.inst_id= $eid";
$result5=DB_query($sql5,$db);
$myrow5=DB_fetch_array($result5);
$inst_name=$myrow5['institution_name']; 
}   
if (isset($_POST['submit'])){
 $inst_name=$_POST['institution_name'];       
unset($inst_name);    
echo $_POST['SelectedType'];
    if ($_POST['SelectedType']!=""){
     $sql = "UPDATE bio_institution
                    SET 
               institution_name='".$_POST['institution']."' WHERE inst_id =" .$_POST['SelectedType'];
     $result=DB_query($sql,$db);
 }
 else{    
 $sql = "INSERT INTO bio_institution(institution_name)  
                          VALUES ('" . $_POST['institution'] . "')";                      
 $result = DB_query($sql,$db);
}  } 
echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '">';
echo '<table style=width:25%><tr><td>';
echo '<div id="panel">';
echo '<fieldset style="height:250px">'; 
echo '<legend><b>Institution Master</b></legend>';
echo '<br><br><table class="selection">'; 
echo '<tr><td>Institution Type</td><td><input type="text" name="institution" id="Institution" value="'.$inst_name.'"></td></tr>'; 
echo '<tr><td><input type="hidden" name="SelectedType" value='.$eid.'>&nbsp;</td></tr>';  
echo'<tr><td></td><td> <input type="Submit" name="submit" value="' . _('Submit') . '" onclick=" if(validate()==1)return false"></td></tr>';   
echo '</table>';
echo '</form></fieldset>';  
echo '</div>';      
echo "<fieldset style='width:560px'>";
echo "<legend><h3>Institution Details</h3></legend>";
echo "<div style='overflow:scroll;height:150px'>";
echo "<table class='selection' style='width:100%'>";
echo '<tr><th>' . _('SL NO') . '</th>  
                <th>' . _('Institution Type') . '</th>
                          </tr>';  
  $sql1="SELECT * FROM bio_institution";
  $result1=DB_query($sql1, $db);  
  $k=0 ;$slno=0;   
   while($myrow=DB_fetch_array($result1) )    
  {
      if ($k==1){
        echo '<tr class="EvenTableRows">';
        $k=0;
    } else {
        echo '<tr class="OddTableRows">';
        $k=1;
    }     
   $cid=$myrow[0]; 
   $slno++;      
   $inst_name=$myrow[1];          
   echo"<tr style='background:#A8A4DB'><td>$slno</td>
                                    <td>$inst_name </td> 
                                    <td><a href='#' id='$cid' onclick='edit(this.id)'>Edit</a></td>
                                    
                                    <td><a href='#' id='$cid' onclick='dlt(this.id)'>Delete</a></td>      
                                    
                                    </tr>";        
      
  } 
  
   
?>
      
<script>

function dlt(str){
        //alert(str);
location.href="?delete=" +str;         
 
}

 function edit(str)
 {
  // alert("yyyyyyy");  
location.href="?edit=" +str;         
 
}
function validate()
{     
var f=0;
var p=0;
if(f==0){f=common_error('Institution','Please enter the Institution Name');  if(f==1){return f; }  }

}
</Script> 