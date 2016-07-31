<?php
$PageSecurity = 5;

include('includes/session.inc');

$title = _('Dispatch Clearence');

include('includes/header.inc');

$pagetype=2;
include('includes/sidemenu.php');
include('includes/SQL_CommonFunctions.inc');
include('includes/formload.inc');
?>
<script type="text/javascript"> 
$(document).ready(function(){
document.forms[0].item_sel.focus();  
calenderr("dcdelivery");    
  $("#error").fadeOut(3000);
    $("#warn").fadeOut(3000);
      $("#success").fadeOut(3000);
        $("#info").fadeOut(3000);
         $(".db_message").fadeOut(3200);  
        
  $(".search").hide();      
  $(".selectiondetails").hide(); 
        
$('.button_details_show').click(function() {
    
//  $('.selectiondetails').slideToggle('slow', function() {
    // Animation complete.
//  });
$(".selectiondetails").show(); 
}); 



         
});

function san(str1,str2)
{
    window.location = 'PDFDcn.php?PONo='+ str1 +'&DCNo='+str2;

}
 function showdetails(str1,str2,str3,str4,str5,str6,str7,str8)
{
//alert(str1);
if (str1=="")
  {
  document.getElementById("right_panel_1_DCcreation").innerHTML="";
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

    document.getElementById("right_panel_1_DCcreation").innerHTML=xmlhttp.responseText;
    datagridshow(str1); 
    document.forms[0].Reqd.focus();   
//    $("#selectiondetails").hide();
    }
  }

xmlhttp.open("GET","DCcreation-rightpanel1.php?p=" + str1 + "&q=" + str2 + "&r=" + str3 + "&s=" + str4 + "&t=" + str5 + "&u=" + str6 + "&v=" + str7 + "&w=" + str8,true);     
xmlhttp.send(); 
}

 function fieldsearch(str1,str2,str3,str4,str5,str6,str7,str8)
{

str1=document.getElementById("item_sel").value;
str2=document.getElementById("pono").value;

if ((str1=0)&&(str2=""))
  {
      alert("Enter a PO no: or select an item for search");
      document.getElementById("item_sel").focus();  
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

    document.getElementById("left_panel_1_DCcreation").innerHTML=xmlhttp.responseText;
    datagridshow(str1); 
    document.forms[0].Reqd.focus();   
//    $("#selectiondetails").hide();
    }
  }

xmlhttp.open("GET","DCcreation-search.php?p=" + str1 + "&q=" + str2 + "&r=" + str3 + "&s=" + str4 + "&t=" + str5 + "&u=" + str6 + "&v=" + str7 + "&w=" + str8,true);     
xmlhttp.send(); 
}

 function datagridshow(str1,str2,str3,str4,str5,str6,str7,str8)
{

if (str1=="")
  {
  document.getElementById("Datagrid_dccreation").innerHTML="";
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
    document.getElementById("Datagrid_dccreation").innerHTML=xmlhttp.responseText;
    document.forms[0].Reqd.focus();   
      

    hidegrid();
    }
  }

xmlhttp.open("GET","DCcreation-datagrid.php?p=" + str1 + "&q=" + str2 + "&r=" + str3 + "&s=" + str4 + "&t=" + str5 + "&u=" + str6 + "&v=" + str7 + "&w=" + str8,true);     
xmlhttp.send(); 
}

 function selection(str1,str2,str3,str4,str5,str6,str7,str8)
{

if (str1=="")
  {
  document.getElementById("right_panelfull_1_DCcreation").innerHTML="";
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
    document.getElementById("right_panelfull_1_DCcreation").innerHTML=xmlhttp.responseText;
    document.forms[0].Reqd.focus();   
      

//    hidegrid();
    }
  }

  if(str1==1)       {
xmlhttp.open("GET","DCcreation-POstatus.php?p=" + str1 + "&q=" + str2 + "&r=" + str3 + "&s=" + str4 + "&t=" + str5 + "&u=" + str6 + "&v=" + str7 + "&w=" + str8,true);  
  }  
   if(str1==2)       {
xmlhttp.open("GET","DCcreation-viewdc.php?p=" + str1 + "&q=" + str2 + "&r=" + str3 + "&s=" + str4 + "&t=" + str5 + "&u=" + str6 + "&v=" + str7 + "&w=" + str8,true);  
  }  
xmlhttp.send(); 
}

 function selection1(str1,str2,str3,str4,str5,str6,str7,str8)
{
var item=document.getElementById("item_sel").value;
var pono=document.getElementById("pono").value;
if (str1=="")
  {
  document.getElementById("Datagrid_dccreation").innerHTML="";
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
    document.getElementById("Datagrid_dccreation").innerHTML=xmlhttp.responseText;

    }
  }

   if(str1==2)       {   
xmlhttp.open("GET","DCcreation-viewdc.php?p=" + item + "&q=" + pono + "&r=" + str3 + "&s=" + str4 + "&t=" + str5 + "&u=" + str6 + "&v=" + str7 + "&w=" + str8,true);  
  }   
   else if(str1==3)       {
       
if(item=="")        {
    
alert("Select an item to view its DCs");  
document.getElementById("item_sel").focus();
return; 
}
myRef = window.open('DCcreation-viewdc-view.php?p='+ item +'&q='+ pono,'estr1');   
   } 
xmlhttp.send();    
}

 function datagridload(str1,str2,str3,str4,str5,str6,str7,str8)
{

if (str1=="")
  {
  document.getElementById("left_panel_1_DCcreation").innerHTML="";
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
        
    document.getElementById("left_panel_1_DCcreation").innerHTML=xmlhttp.responseText;
 
    hidegrid();
//    $("#selectiondetails").hide();  
    }
  }
if(typeof(str5) != "undefined"){
    
//alert(str5);
str6=1;
}

xmlhttp.open("GET","DCcreation-panels.php?p=" + str1 + "&q=" + str2 + "&r=" + str3 + "&s=" + str4 + "&t=" + str5 + "&u=" + str6 + "&v=" + str7 + "&w=" + str8,true);   
calenderr("dcdelivery");
xmlhttp.send(); 
}
</script>
<?php
//--------------------display 
$panel_id='panel_dccreation';
$leg_l1='Select an Item';
$leg_r1="Search options";
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
echo '<p class="page_title_text">' . ' ' . _('Dispatch Clearence') . '';

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
$type="mf";

if(!isset($_POST['Dcno']))      {  
   $Formateddeliverydate = FormatDateForSQL($_POST['delivery']);
                 $sql4 = "INSERT INTO dispatchclearance( pono,
                          itemcode,
                          quantity,                         
                          storeid,
                          deliverydate,
                          qtyrecd,
                          type
                           
                          )
                           VALUES ( '" .$_POST['Pono']."',
                                    '" .$_POST['Item']."',
                                    '" .$_POST['Qty']."',
                                    '" . $_SESSION['loccode']."',
                                     '" .$Formateddeliverydate."',
                                     '0',
                                     '".$type."'
                           
                                        )" ;

            $ErrMsg4 =  _('The request could not be inserted into the database because');
            $DbgMsg4 = _('The SQL statement used to insert the  request and failed was');
            $result4 = DB_query($sql4,$db,$ErrMsg4,$DbgMsg4);   
                     
       //echo $_POST['Pono'.$i]."........ ".$_POST['Qty'.$i].".........".$_POST['delivery'.$i]."<br>" ;

        $sql7='SELECT dispatchclearance.dispatchclrno
                  FROM dispatchclearance'; 
            $ErrMsg7 =  _('The request could not be inserted into the database because');
            $DbgMsg7 = _('The SQL statement used to insert the  request and failed was');
            $result7 = DB_query($sql7,$db,$ErrMsg7,$DbgMsg7);  
            while($myrow7=DB_fetch_array($result7))   {    
                
                $DCNo=$myrow7['0'];

            }
                             
          
}else       {
    $delivery=FormatDateForSQL($_POST['delivery']);
    $sql6="UPDATE dispatchclearance
           SET deliverydate='".$delivery."',
               quantity=".$_POST['Qty'].",
               dcstatusid=".$_POST['DCstatus']." 
           WHERE dispatchclrno=".$_POST['Dcno']."";
    $result6=DB_query($sql6,$db);
    $DCNo=$_POST['Dcno'];
}
clearfields();
 print'<script>

     var answer = confirm("Do you want to PRINT the DC Note?")

   if (answer){
san("'.$PONo.'","'.$DCNo.'");

   }
 
 
 
 
 
 </script> ';
} // End of function submit()

    echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "?" . SID . "'>";  
    
    echo "<div id='fullbody_DCcreation'>";


//    echo "<input type='hidden' name='New' VALUE='Yes'>";
 //-------------------------------------------------------------Start of Panels
    echo"<div class='panels' id='$panel_id'>";
    echo '<table width=100% ><tr><td width=50% valign="top" height=270px>';   
    
//--------------------------------------------------------------Start of Left Panel1  
    echo "<fieldset  class='left_panel_1'>";     
    echo"<legend><h3>$leg_l1</h3>";
    echo"</legend>"; 
    echo'<div>';
    echo'<table id="left_panel_1_DCcreation" width=100%>'; 
    include('DCcreation-leftpanel1.php');
    echo'</table>'; 
    echo'</div>';
    echo "</fieldset>"; 
    echo'</td>';
    
//--------------------------------------------------------------End of Left Panel1

//--------------------------------------------------------------Start of right Panel1  
    echo'<td valign="top" height=270px id="right_panelfull_1_DCcreation">';
    
    echo "<fieldset class='right_panel_1'>";     
    echo"<legend><h3>$leg_r1</h3>";
    echo"</legend>";
    echo '<table width=100% id="right_panel_1_DCcreation">';
    include('DCcreation-rightpanel1.php');
    echo"</table>";
    echo "</fieldset>"; 
    

    
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
    echo "<td><input type='Button' class='search' name='search' VALUE='" . _('Search') . "' onclick='fieldsearch()'></td>";
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
    echo "<a style='cursor:pointer;' id='1' onclick='selection(this.id)'>" . _('View PO line items details') . '</a><br>';
    echo "<a style='cursor:pointer;' id='2' onclick='selection1(this.id)'>" . _('View DCs') . '</a><br>';
    echo "<a style='cursor:pointer;' id='3' onclick='selection1(this.id)'>" . _('View DCs(External HTML)') . '</a><br>';
    echo'<br>';
    echo '</td></tr>';
//    echo'<tr><td></td><td></td><td>';
//    echo "<input type='Button' class='button_details_hide' VALUE='" . _('Hide Details') . "'>"; 
//    echo'</td></tr>';
    echo'</table>';
    
    }
//-----------------------------------------------------------------------End of Details   
      

//-----------------------------------------------------------------------Data Grid     
    echo"<div class='Datagrid' id=Datagrid_dccreation>";
    include('DCcreation-datagrid.php');  
    echo"</div>";
//-----------------------------------------------------------------------End of Data Grid    
 
    echo '</form>';        
    echo "</div>";   
    echo "<br>";
  
  include('includes/smenufooter.inc'); 
 
?>
<script language="javascript">

function calenderr(){
        new JsDatePick({
            useMode:2,
            target:"dcdelivery",
            dateFormat:"%d/%m/%Y"
            /*selectedDate:{                This is an example of what the full configuration offers.
                day:5,                        For full documentation about these settings please see the full version of the code.
                month:9,
                year:2006
            },
            yearsRange:[1978,2020],
            limitToToday:false,
            cellColorScheme:"beige",
            dateFormat:"%m-%d-%Y",
            imgPath:"img/",
            weekStartDay:1*/
        });
    };
    
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
{ 

  
var f=0; 

f=common_error('pono','Enter PO number');  if(f==1) { return f;} 
 
 

if(f==0){f=common_error('dcdelivery','Enter the Duedate');  if(f==1) {return f; }}  
if(f==0){f=common_error('dcqty','Quantity field cannot be empty');  if(f==1) {return f; }}                 



if(f==0)       {       
var maxqty=document.getElementById("maxqty").value;
var dcqty=document.getElementById("dcqty").value;

var qtydiff=maxqty-dcqty;     
if(qtydiff<0) {
    f=1;
    alert("Quantity cannot exeed max dispatch qty");
    document.getElementById("dcqty").focus();
    return f;
}
}   
 

}   
function hidegrid(){
  $(".selectiondetails").hide(); 
//  $('.button_details_show').click(function() {
//  $('.selectiondetails').slideToggle('slow', function() {

//  });
//}); 
 }   
</script>