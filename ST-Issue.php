<?php
$PageSecurity = 11;
include('includes/session.inc');
$title = _('Stock Request Issue ');
include('includes/header.inc');
$pagetype=2;
include('includes/sidemenu.php');
include('includes/SQL_CommonFunctions.inc');
?>
<script type="text/javascript">
$(document).ready(function(){
    
  $("#error").fadeOut(3000);
    $("#warn").fadeOut(3000);
      $("#success").fadeOut(3000);
        $("#info").fadeOut(3000);
         $(".db_message").fadeOut(3200);  
        
      $(".skip").hide();       
  $(".selectiondetails").hide(); 


        
$('.button_details_show').click(function() {
  $('.selectiondetails').slideToggle('slow', function() {
    // Animation complete.
  });
}); 

         
});
function popWindow(wName){
    
    features = 'width=400,height=400,scrollbars=yes';
    pop = window.open('',wName,features);
    if(pop.focus){ pop.focus(); }
    return true;
}
</script>

<?php

$frmstore=$_POST['id1'];
$tostore=$_POST['id2'];
$itemcode=$_POST['itemcode'];

       if (isset($_POST['batch'])){
           
 include('ST-Issue-final-submit.php');
 exit;           
}//--------final submit

$Maximum_Number_Of_Parts_To_Show=50;    

//--------------------display 
$panel_id='panel_stissue';
$leg_l1='Store Transfer Details';
$leg_r1="Item's details";
//..........................

    echo "<form method='post' action='ST-Issue.php'>";  
    
    echo "<div id='fullbody_stissue'>";


//    echo "<input type='hidden' name='New' VALUE='Yes'>";
 //-------------------------------------------------------------Start of Panels
    echo"<div class='panels' id='$panel_id'>";
    echo '<table width=100% ><tr><td width=50% valign="top" height=270px >';   
    
//--------------------------------------------------------------Start of Left Panel1  
    echo "<fieldset  class='left_panel_1'>";     
    echo"<legend><h3>$leg_l1</h3>";
    echo"</legend>"; 
    echo'<div style="height:230px;overflow:auto;">';
    echo'<table id="left_panel_1_STIssue" width=100%>'; 
    include('ST-Issue-leftpanel1.php');
    echo'</table>'; 
    echo'</div>';
    echo "</fieldset>"; 
    echo'</td>';
    
//--------------------------------------------------------------End of Left Panel1

//--------------------------------------------------------------Start of right Panel1  
    echo'<td valign="top" height="270px" id="STIssue-right_panel_1">';
    
//    echo "<fieldset class='right_panel_1'>";     
//    echo"<legend><h3>$leg_r1</h3>";
//    echo"</legend>"; 
//    echo '<table width=100% class=sortable>';
//    include('STIssue-rightpanel1.php');
//    echo"</table>";
//    echo "</fieldset>"; 
    

    
    echo"</td></tr></table>";
    echo"</div>";  
//--------------------------------------------------------------End of Panels

    echo"<div class=buttons>";
    echo "<table width=50%><tr>";
    echo "<td><input type='Submit' name='save' VALUE='" . _('Save') . "' onclick='if(log_in()==1)return false;'></td>";
    echo "<td><input type='Submit' name='clear' VALUE='" . _('Clear') . "'></td>";
    echo "<td><input type='Button' class='button_details_show' name='details' VALUE='" . _('Details') . "'></td>";
//    echo "<td><input type='Submit' name='search' VALUE='" . _('Search') . "' onclick='if(log_in()==1)return false;'></td>";
    echo "</tr></table>";  
    echo"</div>";
    
//--------------------------------------------------------------End of Buttons       

    
    echo"<div class='selectiondetails'>"; 
    selectiondetails($rootpath,$db);
    echo"</div>";
    function selectiondetails($rootpath,$db)     {  

    echo '<table width=100% colspan=2 border=0 cellpadding=4>';
    echo "<tr>
        <th width=33%>" . _('Inquiries') . "</th>
        <th width=33%>". _('Transactions') . "</th>
        <th width=33%>" . _('Maintenance') . "</th>
    </tr>";
    echo '<tr><td VALIGN=TOP class="menu_group_items">';    /* Inquiry Options */
    echo "<a style='cursor:pointer;' id='1' onclick='showdetails(this.id)'>" . _('') . '</a><br>';
    echo'<td VALIGN=TOP class="menu_group_items"></td>';
    echo '<td VALIGN=TOP class="menu_group_items">';

    echo '</td></tr>';
//    echo'<tr><td></td><td></td><td>';
//    echo "<input type='Button' class='button_details_hide' VALUE='" . _('Hide Details') . "'>"; 
//    echo'</td></tr>';
    echo'</table>';
    
    }
//-----------------------------------------------------------------------End of Details   
      

//-----------------------------------------------------------------------Data Grid     
    echo"<div class='Datagrid' id=Datagrid_wostoresrequest>";
    include('ST-Issue-datagrid.php');  
    echo"</div>";
//-----------------------------------------------------------------------End of Data Grid    
 
    echo '</form>';        
    echo "</div>";   
    echo "<br>";
  
  include('includes/smenufooter.inc'); 
?>  
<script language="javascript">
 document.getElementById('custid').focus(); 
  $(document).ready(function() {
    $("#notice").fadeOut(3000);

 $('#sourcetype').change(function() {
  $('#dinhide').hide();
}); 
   }); 
//    $('#leads').click(function() { 

//    f=validateemail('email','Please Enter valid email');  if(f==1) { return false; }   
//});
function caps1()
{
//   alert("sss");
UCWords('custid','Name should be begin with capital letter');
}


function log_in()
{  //  alert("sss"); alert(mail);
var f=0;       

var from=document.getElementById("fromstoreloc").value;
var to=document.getElementById("storelocto").value;

f=common_error('fromstoreloc','SR quantity cannot be left blank');  if(f==1) { return f;} 
if(f==0){f=common_error('storelocto','Enter the Duedate');  if(f==1) {return f; }}
if(f==0){
if(from==to)        {
    f=1;
    alert("select different store locations");
}
  if(f==1) {return f; }} 
                   
if(f==0){f=common_error('deliverydate','Enter the delivery date');  if(f==1) { document.forms[0].skip.focus(); return f; }}   
 

}   
function hidegrid(){
  $(".selectiondetails").hide(); 
  $('.button_details_show').click(function() {
  $('.selectiondetails').slideToggle('slow', function() {

  });
}); 
 }   
</script>  

