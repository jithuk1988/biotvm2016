<?php
$PageSecurity = 5;

include('includes/session.inc');

$title = _('Materials issue');

include('includes/header.inc');

$pagetype=2;
include('includes/sidemenu.php');
include('includes/SQL_CommonFunctions.inc');
include('includes/formload.inc');
?>
<script type="text/javascript"> 
$(document).ready(function(){
document.forms[0].Wono.focus();  
    
  $("#error").fadeOut(3000);
    $("#warn").fadeOut(3000);
      $("#success").fadeOut(3000);
        $("#info").fadeOut(3000);
         $(".db_message").fadeOut(3200);  
        
        
  $(".selectiondetails").hide(); 
        
$('.button_details_show').click(function() {
  $('.selectiondetails').slideToggle('slow', function() {
    // Animation complete.
  });
}); 

         
});
 function wosearch(str1,str2,str3,str4,str5,str6,str7,str8)
{

var item=document.getElementById("itemcode_mi").value;
var wono=document.getElementById("wono").value;
var srno=document.getElementById("srno").value;

str1="";
str2="";
str3="";
if(typeof(item) != "undefined"){
    
var str1=item; 

}
if(typeof(wono) != "undefined"){
    
var str2=wono; 

}
if(typeof(srno) != "undefined"){
    
var str3=srno; 

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
                                                                                     
    document.getElementById("Datagrid_womaterialissuefromstores").innerHTML=xmlhttp.responseText;
    document.forms[0].Srqty.focus(); 
    }
  }

xmlhttp.open("GET","WOMaterialissuefromstores-search.php?p=" + str1 + "&q=" + str2 + "&r=" + str3 + "&s=" + str4 + "&t=" + str5 + "&u=" + str6 + "&v=" + str7 + "&w=" + str8,true);     
xmlhttp.send();
}
 function showdetails(str1,str2,str3,str4,str5,str6,str7,str8)
{
//alert(str1); 
if (str1=="")
  {
  document.getElementById("left_panel_1_WOStoresrequest").innerHTML="";
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
                                                                                     
    document.getElementById("left_panel_1_WOStoresrequest").innerHTML=xmlhttp.responseText;
    document.forms[0].Srqty.focus(); 
    }
  }

xmlhttp.open("GET","WOStoresrequest-leftpanel1.php?p=" + str1 + "&q=" + str2 + "&r=" + str3 + "&s=" + str4 + "&t=" + str5 + "&u=" + str6 + "&v=" + str7 + "&w=" + str8,true);     
xmlhttp.send();
}

 function viewselection2(str1,str2,str3,str4,str5,str6,str7,str8)
{
str1=document.getElementById("srno").value;                    




myRef = window.open('WOMaterialissuefromstores-picklist.php?id='+ str1,'estr1',
'left=20,top=20,width=700,height=500,toolbar=1,scrollbars=1,dependent=yes');
}
 function viewselection1(str1,str2,str3,str4,str5,str6,str7,str8)
{

str2=document.getElementById("srno").value;

if (str2=="")
  {
  alert("Select an SR to view its line items");
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
                                                           
    document.getElementById("WOMaterialissuefromstores-right_panel_1").innerHTML=xmlhttp.responseText;
    document.forms[0].Srqty.focus(); 
         
    }
  }
if(str1=1) {

xmlhttp.open("GET","WOMaterialissuefromstores-sel_lineitems.php?p=" + str1 + "&q=" + str2 + "&r=" + str3 + "&s=" + str4 + "&t=" + str5 + "&u=" + str6 + "&v=" + str7 + "&w=" + str8,true);     

}    
xmlhttp.send();
}
 function datagridload(str1,str2,str3,str4,str5,str6,str7,str8)
{
//alert(str1);
if (str1=="")
  {
  document.getElementById("panel_womaterialissuefromstores").innerHTML="";
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
    document.getElementById("panel_womaterialissuefromstores").innerHTML=xmlhttp.responseText;

//    hidegrid();
    $("#selectiondetails").hide();  
    }
  }

xmlhttp.open("GET","WOMaterialissuefromstores-datagridload.php?p=" + str1 + "&q=" + str2 + "&r=" + str3 + "&s=" + str4 + "&t=" + str5 + "&u=" + str6 + "&v=" + str7 + "&w=" + str8,true);     
xmlhttp.send(); 
}
</script>
<?php
//--------------------display 
$panel_id='panel_womaterialissuefromstores';
$leg_l1='Search WO';
$leg_r1="Item's details";
//..........................

$fieldid=get_fieldid('wo','workorders',$db);

if (isset($_GET['StockID'])){
    $StockID = strtoupper($_GET['StockID']);
} elseif (isset($_POST['SupplierID'])){
   $StockID = strtoupper($_POST['StockID']);
} else {
    unset($StockID);
}
if (!isset($_POST['StockLocation'])){
    if (isset($_SESSION['UserStockLocation'])){
        $_POST['StockLocation']=$_SESSION['UserStockLocation'];
    }
}  
 
// This is aleady linked from this page
//echo "<a href='" . $rootpath . '/SelectSupplier.php?' . SID . "'>" . _('Back to Suppliers') . '</a><br>';
echo '<p class="page_title_text">' . ' ' . _('Materials Issue') . '';

$InputError = 0;

if (isset($Errors)) {
    unset($Errors);
}

$Errors=Array();
if(isset($_POST['clear']))       {

    clearfields();
   unset($StockID); 
}
//if(isset($_POST['save']))       {

//    submit(&$db,&$StockID,&$DemandID);
//}
function clearfields()      {
    
 unset($_POST['Wono']);
 unset($_POST['Duedate']);
 unset($_POST['Reqd']);
 unset($_POST['demandno']);
 unset($_POST['StockID']); 
 unset($_POST['StockLocation']);
 unset($_POST['Quantity']);
 unset($_POST['Batch']);  
}

if (isset($_POST['save'])) { //The update button has been clicked   

$sql7="SELECT worequirements.stockid,     
              worequirements.qtypu
       FROM worequirements
       WHERE wo=".$_POST['Wono'];  
$result7=DB_query($sql7,$db);
while($myrow7=DB_fetch_array($result7))     {
    
$issueqty=$_POST['Miqty']*$myrow7['qtypu'];   
$sql8="UPDATE womaterialrequestdetails
       SET qtyissued=qtyissued+".$issueqty."
       WHERE stockid='".$myrow7['stockid']."'";
$result8=DB_query($sql8,$db);


}
clearfields();
} // End of function submit()

    echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "?" . SID . "'>";  
    
    echo "<div id='fullbody_womaterialissuefromstores'>";


//    echo "<input type='hidden' name='New' VALUE='Yes'>";
 //-------------------------------------------------------------Start of Panels
    echo"<div class='panels' id='$panel_id'>";
    echo '<table width=100% ><tr><td width=50% valign="top" height=270px >';   
    
//--------------------------------------------------------------Start of Left Panel1  
    echo "<fieldset  class='left_panel_1'>";     
    echo"<legend><h3>$leg_l1</h3>";
    echo"</legend>"; 
    echo'<div style="height:230px;overflow:auto;">';
    echo'<table id="left_panel_1_WOMaterialissuefromstores" width=100%>'; 
    include('WOMaterialissuefromstores-leftpanel1.php');
    echo'</table>'; 
    echo'</div>';
    echo "</fieldset>"; 
    echo'</td>';
    
//--------------------------------------------------------------End of Left Panel1

//--------------------------------------------------------------Start of right Panel1  
    echo'<td valign="top" height=270px id="WOMaterialissuefromstores-right_panel_1">';
    
//    echo "<fieldset class='right_panel_1'>";     
//    echo"<legend><h3>$leg_r1</h3>";
//    echo"</legend>";
//    echo '<table width=100% class=sortable>';
//    include('WOMaterialissuefromstores-rightpanel1.php');
//    echo"</table>";
//    echo "</fieldset>"; 
    

    
    echo"</td></tr></table>";
    echo"</div>"; 
     
//--------------------------------------------------------------End of Panels

    echo"<div class=buttons>";
    echo "<table width=50%><tr>";
    echo "<td><input type='Submit' name='save' VALUE='" . _('Save') . "' onclick='if(log_in()==1)return false;'></td>";
    echo "<td><input type='Submit' name='skip' VALUE='" . _('Skip') . "' onclick='if(log_in()==1)return false;'></td>";
    echo "<td><input type='Submit' name='clear' VALUE='" . _('Clear') . "'></td>";
    echo "<td><input type='Submit' name='delete' VALUE='" . _('Delete') . "'></td>";
    echo "<td><input type='Button' class='button_details_show' name='details' VALUE='" . _('Details') . "'></td>";
    echo "<td><input type='Button' name='search' VALUE='" . _('Search') . "' onclick='wosearch()'></td>";
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
    echo "<a>" . _('') . '</a>';
    echo'<br>'; 
    echo'<td VALIGN=TOP class="menu_group_items"></td>';
    echo '<td VALIGN=TOP class="menu_group_items">';
    echo "<a style='cursor:pointer;' id='1' onclick='viewselection2(this.id)'>" . _('View Line items') . '</a><br>';
    echo'<br>';
    echo '</td></tr>';
//    echo'<tr><td></td><td></td><td>';
//    echo "<input type='Button' class='button_details_hide' VALUE='" . _('Hide Details') . "'>"; 
//    echo'</td></tr>';
    echo'</table>';
    
    }
//-----------------------------------------------------------------------End of Details   
      

//-----------------------------------------------------------------------Data Grid     
    echo"<div class='Datagrid' id=Datagrid_womaterialissuefromstores>";
    include('WOMaterialissuefromstores-datagrid.php');  
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


f=common_error('woentrystockid','Please Select an Item');  if(f==1) { return f;} 
             

if(f==0){f=common_error('duedate','Enter the Duedate');  if(f==1) {return f; }}                  
if(f==0){f=common_error('quantity','Enter the quantity, to skip a date use the skip button');  if(f==1) { document.forms[0].skip.focus(); return f; }}   
 

}   
function hidegrid(){
  $(".selectiondetails").hide(); 
  $('.button_details_show').click(function() {
  $('.selectiondetails').slideToggle('slow', function() {

  });
}); 
 }   
</script>