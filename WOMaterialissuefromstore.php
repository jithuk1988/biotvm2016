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
    
//  $("#error").fadeOut(3000);
//    $("#warn").fadeOut(3000);
//      $("#success").fadeOut(3000);
//        $("#info").fadeOut(3000);
//         $(".db_message").fadeOut(3200);  
        
//  calenderr("issuedate");      
  $(".selectiondetails").hide(); 
        
$('.button_details_show').click(function() {
  $('.selectiondetails').slideToggle('slow', function() {
    // Animation complete.
  });
}); 

         
});
 function wosearch(str1,str2,str3,str4,str5,str6,str7,str8)
{
    alert(str1);
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

 function issuenotes(str1)
{

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

    }
  }

xmlhttp.open("GET","WOMaterialissuefromstores-issuenotes.php?p=" + str1,true);     
xmlhttp.send();
}



 function issueitems(str1)
{
str1=document.getElementById("itemcode_mi").value;

myRef = window.open('WOMaterialissuefromstores-issueitem.php?id='+ str1,'estr1',
'left=20,top=20,width=700,height=500,toolbar=1,scrollbars=1,dependent=yes');

}

 function datagridload_rpanel(str1)
{

myRef = window.open('WOMaterialissuefromstores-issuedetails.php?id='+ str1,'estr1',
'left=20,top=20,width=700,height=500,toolbar=1,scrollbars=1,dependent=yes');

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
   // calenderr("issuedate"); 
    }
  }

xmlhttp.open("GET","WOStoresrequest-leftpanel1.php?p=" + str1 + "&q=" + str2 + "&r=" + str3 + "&s=" + str4 + "&t=" + str5 + "&u=" + str6 + "&v=" + str7 + "&w=" + str8,true);     
xmlhttp.send();
}

 function viewselection2(str1,str2,str3,str4,str5,str6,str7,str8)
{
str2=document.getElementById("srno").value;                    

if(str2=="")        {

alert("Select a WO");    
document.getElementById("miwosearch").focus();
return;    
}
if(str1==1){
    
myRef = window.open('WOMaterialissuefromstores-picklist.php?id='+ str2,'estr1',
'left=20,top=20,width=700,height=500,toolbar=1,scrollbars=1,dependent=yes');

}else if (str1==2)       {
    
issuenotes(str2); 
    
}
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
    myRef = window.open('WOMaterialissuefromstores-picklist.php?id='+ str1,'estr1',
'left=20,top=20,width=700,height=500,toolbar=1,scrollbars=1,dependent=yes');
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

$_POST['FromLocation']=$_SESSION['UserStockLocation'];
$sql7="SELECT worequirements.stockid,     
              worequirements.qtypu
       FROM worequirements
       WHERE wo=".$_POST['Wono'];  
$result7=DB_query($sql7,$db);


while($myrow7=DB_fetch_array($result7))     {
 
 $QuantityIssued=$_POST['Miqty']*$myrow7['qtypu'];
    
        $SQL1="SELECT qtyrequest,
                      qtyissued
           FROM womaterialrequestdetails
           WHERE reqno=".$_POST['Srno']." AND
                 stockid='".$myrow7['stockid']."'";
    $result1=DB_query($SQL1,$db);
    $myrow1=DB_fetch_array($result1);
//          echo $QuantityIssued.".......".$myrow1[0]."......".$myrow1[1].".......<br>"; 
          $EffQty=$myrow1[1] + $QuantityIssued;
//           echo $EffQty."=".$myrow1[1]."+".$QuantityIssued;
//           exit;
//          if ($myrow1[0] < $QuantityIssued){
//            $InputError = true;
//            prnMsg(_('This issue cannot be processed because the issued quantity is greater than the request quantity'),'error');

//        } else if ($myrow1[0] < $EffQty){
//            $InputError = true;
//            prnMsg(_('This issue cannot be processed because the sum of quantity entered and quanity already issued exeeds the request quantity'),'error');

//        }
    $SQL = "SELECT materialcost+labourcost+overheadcost AS cost,
            controlled,
            serialised,
            mbflag
        FROM stockmaster
        WHERE stockid='" .$myrow7['stockid'] . "'";
    $Result = DB_query($SQL,$db);
    $IssueItemRow = DB_fetch_array($Result);

//    if ($IssueItemRow['cost']==0){
//        prnMsg(_('The item being issued has a zero cost. Zero cost items cannot be issued to work orders'),'error');
//        $InputError=1;
//    }  

    if ($_SESSION['ProhibitNegativeStock']==1
            AND ($IssueItemRow['mbflag']=='M' OR $IssueItemRow['mbflag']=='B')){
                                            //don't need to check labour or dummy items
        $SQL = "SELECT quantity FROM locstock
                WHERE stockid ='" . $myrow7['stockid'] . "'
                AND loccode ='" . $_POST['FromLocation'] . "'";
        $CheckNegResult = DB_query($SQL,$db);
        $CheckNegRow = DB_fetch_row($CheckNegResult);
        if ($CheckNegRow[0]<$QuantityIssued){
            $InputError = true;
            prnMsg(_('This issue cannot be processed because the system parameter is set to prohibit negative stock and this issue would result in stock going into negative. Please correct the stock first before attempting another issue'),'error');
        }

    }  
    
    if ($InputError==false){
        
 /************************ BEGIN SQL TRANSACTIONS ************************/

//        $Result = DB_Txn_Begin($db);
        /*Now Get the next WO Issue transaction type 28 - function in SQL_CommonFunctions*/
        $WOIssueNo = GetNextTransNo(28, $db);

        $PeriodNo = GetPeriod(Date($_SESSION['DefaultDateFormat']), $db);
        $SQLIssuedDate = FormatDateForSQL($_POST['IssuedDate']);
        $StockGLCode = GetStockGLCode($myrow7['stockid'],$db);       
        
        
                if ($IssueItemRow['mbflag']=='M' OR $IssueItemRow['mbflag']=='B'){
            /* Need to get the current location quantity will need it later for the stock movement */
            $SQL="SELECT locstock.quantity
                FROM locstock
                WHERE locstock.stockid='" . $myrow7['stockid'] . "'
                AND loccode= '" . $_POST['FromLocation'] . "'";

            $Result = DB_query($SQL, $db);
            if (DB_num_rows($Result)==1){
                $LocQtyRow = DB_fetch_row($Result);
                $NewQtyOnHand = ($LocQtyRow[0] - $QuantityIssued);
            } else {
            /*There must actually be some error this should never happen */
                $NewQtyOnHand = 0;
            }

            $SQL = "UPDATE locstock
                SET quantity = locstock.quantity - " . $QuantityIssued . "
                WHERE locstock.stockid = '" . $myrow7['stockid'] . "'
                AND loccode = '" . $_POST['FromLocation'] . "'";

            $ErrMsg =  _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The location stock record could not be updated because');
            $DbgMsg =  _('The following SQL to update the location stock record was used');
            $Result = DB_query($SQL, $db, $ErrMsg, $DbgMsg, true);
        } else {
            $NewQtyOnHand =0; //since we can't have stock of labour type items!!
        }
        /*Insert stock movements - with unit cost */

        $SQL = "INSERT INTO stockmoves (stockid,
                        type,
                        transno,
                        loccode,
                        trandate,
                        price,
                        prd,
                        reference,
                        qty,
                        standardcost,
                        newqoh)
                    VALUES ('" . $myrow7['stockid'] . "',
                            28,
                            " . $WOIssueNo . ",
                            '" . $_POST['FromLocation'] . "',
                            '" . Date('Y-m-d') . "',
                            " . $IssueItemRow['cost'] . ",
                            " . $PeriodNo . ",
                            '" . $_POST['Wono'] . "',
                            " . -$QuantityIssued . ",
                            " . $IssueItemRow['cost'] . ",
                            " . $NewQtyOnHand . ")";     
                            

        $Result = DB_query($SQL,$db);

        /*Get the ID of the StockMove... */
        $StkMoveNo = DB_Last_Insert_ID($db,'stockmoves','stkmoveno');       

if ($IssueItemRow['controlled'] ==1){
    
    
   echo"dfgdfgfdgd"; 
    exit;
}        
        
    }    
    
    
    
    
    
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
//    echo "<td><input type='Button' name='search' id='miwosearch' VALUE='" . _('Search') . "' onclick='wosearch()'></td>";
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
   // echo "<a>" . _('') . '</a>';
    echo'<br>'; 
    echo'<td VALIGN=TOP class="menu_group_items"></td>';
    echo '<td VALIGN=TOP class="menu_group_items">';
    echo "<a style='cursor:pointer;' id='1' onclick='viewselection2(this.id)'>" . _('View Line items') . '</a><br>';
    echo "<a style='cursor:pointer;' id='2' onclick='viewselection2(this.id)'>" . _('SRS issue notes ') . '</a><br>';
    echo "<a style='cursor:pointer;' id='3' onclick='issueitems(this.id)'>" . _('Issue notes of item selected ') . '</a><br>';
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
  //  include('WOMaterialissuefromstores-datagrid.php');  
   
         echo '<table width=100% class=sortable>';
      $tableheader = "<thead><tr>
        <th colspan=6 text-align=left>Item selected is: ".$part."</th></tr><tr>
        <th>" . _('Sl no') . "</th>
        <th>" . _('Date') . "</th>
        <th>" . _('SR no') . "</th>
        <th>" . _('WO no') . "</th>
        <th>" . _('Item') . "</th>
        <th>" . _('SR Qty') . "</th>
        <th>" . _('SR status') . "</th>
        </tr></thead>";
      echo $tableheader;
      $j = 1;
      $RowIndex = 0;

      $k=0;
      $slno=0;
      echo"<tbody>";
      while ($myrow=DB_fetch_array($result))   {
      
      
                        if ($k==1)
        {
            echo '<tr class="EvenTableRows"  id="'.$myrow['reqno'].'" onclick=datagridload(this.id)>';
            $k=0;
        } else 
        {
            echo '<tr class="OddTableRows"  id="'.$myrow['reqno'].'" onclick=datagridload(this.id)>';
            $k++;
        }  
        
       $slno++;
       
       $redate=ConvertSQLDate($myrow['reqdate']);
       
               printf("<td>$slno</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            </tr>",
            $redate,
            $myrow[0],
            $myrow[1],
            $myrow['stockid'],
            $myrow['reqty'],
            $myrow['srstatus']
            );

    $RowIndex = $RowIndex + 1;    
          
      }  
      
   
   
   
   
   
   
    echo"</div>";
//-----------------------------------------------------------------------End of Data Grid    
 
    echo '</form>';
    echo "</div>";   
    
  
  include('includes/smenufooter.inc'); 
 
?>
<script language="javascript">
// document.getElementById('custid').focus(); 
  $(document).ready(function() {
    $("#notice").fadeOut(3000);

 $('#sourcetype').change(function() {
  $('#dinhide').hide();
}); 
   }); 
//    $('#leads').click(function() { 

//    f=validateemail('email','Please Enter valid email');  if(f==1) { return false; }   
//});
/*function caps1()
{
//   alert("sss");
UCWords('custid','Name should be begin with capital letter');
} */

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