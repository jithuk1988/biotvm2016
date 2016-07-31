<?php
$PageSecurity = 5;

include('includes/session.inc');

$title = _('Stores Request');

include('includes/header.inc');

$pagetype=2;
include('includes/sidemenu1.php');
include('includes/SQL_CommonFunctions.inc');
include('includes/formload.inc');
?>
<script type="text/javascript"> 
$(document).ready(function(){
    $(".selectiondetails").hide();   
document.forms[0].Wono.focus();  
    
  $("#error").fadeOut(3000);
    $("#warn").fadeOut(3000);
      $("#success").fadeOut(3000);
        $("#info").fadeOut(3000);
         $(".db_message").fadeOut(3200);  
        
      $(".skip").hide();       
  


        
$('.button_details_show').click(function() {
  $('.selectiondetails').slideToggle('slow', function() {
    // Animation complete.
  });
}); 

         
});

function changeid(str)         {


if (str=="")
  {
      alert("Enter a WO number");
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
                                                                                     
    document.getElementById("reqid").innerHTML=xmlhttp.responseText;
    document.forms[0].Srqty.focus(); 
    }
  }

xmlhttp.open("GET","WOStoresrequest-leftpanel1-reqid.php?id=" + str,true);     
xmlhttp.send(); 
    
    
    
}

function updateid(str)         {                      

var idn=document.getElementById("reqno").value;
var ido=document.getElementById("reqno1").value;

 
if (ido=="")
  {
      alert("Enter a WO number");
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
                                                                                     
    document.getElementById("newid").innerHTML=xmlhttp.responseText;
    
    }
  }

xmlhttp.open("GET","WOStoresrequest-leftpanel1-reqid-newid.php?idchange=" + idn + "&idold=" + ido,true);     
xmlhttp.send(); 

$(".savebutton").show();     
    
    
}


 function wosearch(str1,str2,str3,str4,str5,str6,str7,str8)
{
str2= str1;

var str1=document.getElementById("wono").value;
if (str1=="")
  {
      alert("Enter a WO number");
      document.forms[0].Wono.focus();  
//  document.getElementById("left_panel_1_WOStoresrequest").innerHTML="";
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
    $(".selectiondetails").hide(); 
    }
  }

xmlhttp.open("GET","WOStoresrequest-leftpanel1.php?p=" + str1 + "&q=" + str2 + "&r=" + str3 + "&s=" + str4 + "&t=" + str5 + "&u=" + str6 + "&v=" + str7 + "&w=" + str8,true);     
xmlhttp.send();
}
function SRreport(str)     {

myRef = window.open('WOStoresrequest-SR-pdf.php?id='+ str,'estr1');    
    
}
 function reportview(str1)
{

if (str1=="")
  {
  document.getElementById("WOStoresrequest-right_panel_1").innerHTML="";
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
                                                                           
    document.getElementById("WOStoresrequest-right_panel_1").innerHTML=xmlhttp.responseText;
//    document.forms[0].Srqty.focus(); 
//    $(".selectiondetails").hide(); 
    }
  }

xmlhttp.open("GET","WOStoresrequest-rightpanel1-srreg.php?p=" + str1,true);     
xmlhttp.send();
}

 function viewreport(str1,str2,str3,str4,str5,str6,str7,str8)
{
var item=document.getElementById("wosr-item").value;    

if(item=='')     {

alert("Select an item to view its SR");    
return;   
}
if(str2==2)         {
    
myRef = window.open('WOStoresrequest-reports-sritem.php?id='+ str1 +'&item='+item,'estr1');        
}

}


 function loadfromgrid(str1,str2,str3,str4,str5,str6,str7,str8)
{
/*if(str2==1)     {
    
$(".savebutton").hide(); 
}  */  


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
    $(".selectiondetails").hide(); 
    }
  }

xmlhttp.open("GET","WOStoresrequest-leftpanel1.php?p=" + str1 + "&q=" + str2 + "&r=" + str3 + "&s=" + str4 + "&t=" + str5 + "&u=" + str6 + "&v=" + str7 + "&w=" + str8,true);     
xmlhttp.send();
}
                                                                                      
 function showdetails(str1,str2,str3,str4,str5,str6,str7,str8)
{
var str2=document.getElementById("wono").value;
var str3=document.getElementById("fg").value;
var str4=document.getElementById("woqty").value; 
var str5=document.getElementById("editcheck").value;
if(str5==1)     {
    
var str6=document.getElementById("reqno").value;     
    
}

// alert(str5);
if (str2=="")
  {
  document.getElementById("WOStoresrequest-right_panel_1").innerHTML="";
  return;
  }
  show_progressbar('WOStoresrequest-right_panel_1'); 
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
    document.getElementById("WOStoresrequest-right_panel_1").innerHTML=xmlhttp.responseText;

//    hidegrid();

    }
  }

xmlhttp.open("GET","WOStoresrequest-rightpanel1.php?p=" + str1 + "&q=" + str2 + "&r=" + str3 + "&s=" + str4 + "&t=" + str5 + "&u=" + str6 + "&v=" + str7 + "&w=" + str8,true);     
xmlhttp.send(); 
}
 function viewsr(str1,str2,str3,str4,str5,str6,str7,str8)
{ 
   // alert(str1);
  //  alert(str2); 
   // alert(str3); 
    
if(str3!=3)     {
var str3=document.getElementById("wono").value;
var str4=document.getElementById("fg").value;
var str5=document.getElementById("woqty").value; 
//alert (str3,str4,str5);
} 
if (str3=="")
  {
  document.getElementById("Datagrid_wostoresrequest").innerHTML="";
  return;
  }
 // show_progressbar('Datagrid_wostoresrequest'); 
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
    document.getElementById("Datagrid_wostoresrequest").innerHTML=xmlhttp.responseText;


    $("#selectiondetails").hide();  
    }
  }
if(str2==1)     { 
       //  alert("ff");
xmlhttp.open("GET","WOStoresrequest-datagrid-srs.php?p=" + str1 + "&q=" + str2 + "&r=" + str3 + "&s=" + str4 + "&t=" + str5 + "&u=" + str6 + "&v=" + str7 + "&w=" + str8,true); 

}else if(str2==2)    {
             //        alert("dd");
xmlhttp.open("GET","WOStoresrequest-datagrid.php?p=" + str1 + "&q=" + str2 + "&r=" + str3 + "&s=" + str4 + "&t=" + str5 + "&u=" + str6 + "&v=" + str7 + "&w=" + str8,true);    
}else if(str2==3)    {
                  //  alert ("gg");
xmlhttp.open("GET","WOStoresrequest-datagrid-wostatus.php?p=" + str1 + "&q=" + str2 + "&r=" + str3 + "&s=" + str4 + "&t=" + str5 + "&u=" + str6 + "&v=" + str7 + "&w=" + str8,true);    
}      
xmlhttp.send(); 
} 
 function selectedbomdetails(str1,str2,str3,str4,str5,str6,str7,str8)
{
//var str2=document.getElementById("wono").value;                    
//var str3=document.getElementById("fg").value;
//var str4=document.getElementById("woqty").value; 



myRef = window.open('WOStoresrequest-bomdetails-popup.php?id='+ str1,'estr1',
'left=20,top=20,width=500,height=500,toolbar=1,scrollbars=1,dependent=yes');
}
</script>
<?php
//--------------------display 
$panel_id='panel_wostoresrequest';
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
echo '<p class="page_title_text">' . ' ' . _('Stores Request') . '';

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
  
  $Reqdate=FormatDateForSQL($_POST['Reqdate']);  
  $FGqty=$_POST['Srqty'];  
  
    
 $sql7="SELECT loccode
      FROM workorders
      WHERE wo=".$_POST['Wono'];
$result7=DB_query($sql7,$db);
$myrow7=DB_fetch_array($result7);

if(!isset($_POST['Reqno']))     {
    
    if($_POST['Srtype']==2)         {
    
    $where="WHERE wo=" . $_POST['Wono']." AND
                          worequirements.stockid=stockmaster.stockid";    
        
    }elseif($_POST['Srtype']==1)         {
    
    $where="WHERE wo=". $_POST['Wono']."      AND
                          worequirements.stockid=stockmaster.stockid  AND
                          stockmaster.categoryid!=10";
    }elseif($_POST['Srtype']==3)         {   
        
    $where="WHERE wo=". $_POST['Wono']."      AND
                          worequirements.stockid=stockmaster.stockid  AND
                          stockmaster.categoryid=10";    
    }                   


             $ReqID=0;
            $sql1="SELECT MAX(reqno)
                   FROM womaterialrequest";
            $result1 = DB_query($sql1, $db); 
            while($myrow1= DB_fetch_array($result1))    {
                
                $ReqID=$myrow1[0];
            }      
               $ReqID++;
               
            $sql2="INSERT INTO womaterialrequest (reqno,
                  wono,loccode,reqty,reqdate,srtype) VALUES ( ".$ReqID.",
                  ".$_POST['Wono'].",
                  '".$myrow7['loccode']."',
                  '".$FGqty."',
                  '".$Reqdate."',
                  ".$_POST['Srtype']."
                  )"; 
                  
                  $result2 = DB_query($sql2, $db); 
                  
    
                          
                          
    $RequirmentsResult = DB_query("SELECT worequirements.stockid,
                        autoissue,
                        qtypu,
                        stockmaster.description,
                        stockmaster.decimalplaces

                    FROM worequirements,stockmaster
                    ".$where."
                    ",
                    $db); 
    while ($RequirementsRow = DB_fetch_array($RequirmentsResult)){
        
       $Reqqty=$FGqty*$RequirementsRow['qtypu'];
        
                          $sql3="INSERT INTO womaterialrequestdetails (reqno,
                  stockid,
                  qtyrequest) VALUES ( ".$ReqID.",
                  '".$RequirementsRow['stockid']."',
                  ".$Reqqty."       
                  
                  )";
                  
              $result3 = DB_query($sql3, $db);   
        
        
    }  
}else   {
         
    $FGqty=$_POST['Srqty']; 
    $ReqID=$_POST['Reqno'];

           $sql2="UPDATE womaterialrequest 
                  SET reqty=".$FGqty.",
                      statusid=".$_POST['Srstatusid']."
                  WHERE reqno=".$_POST['Reqno']; 
                  
           $result2 = DB_query($sql2,$db); 
                  
           $sql3="DELETE FROM womaterialrequestdetails
                  WHERE reqno=".$ReqID;
           $result3=DB_query($sql3,$db);                                    
           
            if($_POST['Srtype']==2)         {
    
    $where="WHERE wo=" . $_POST['Wono']." AND
                          worequirements.stockid=stockmaster.stockid";    
        
    }elseif($_POST['Srtype']==1)         {
    
    $where="WHERE wo=". $_POST['Wono']."      AND
                          worequirements.stockid=stockmaster.stockid  AND
                          stockmaster.categoryid!=10";
    }elseif($_POST['Srtype']==3)         {   
        
    $where="WHERE wo=". $_POST['Wono']."      AND
                          worequirements.stockid=stockmaster.stockid  AND
                          stockmaster.categoryid=10";    
    }  
                  
    $sql9 = "SELECT worequirements.stockid,
                        stockmaster.description,
                        stockmaster.decimalplaces,
                        autoissue,
                        qtypu
                    FROM worequirements INNER JOIN stockmaster
                    ON worequirements.stockid=stockmaster.stockid
                    ".$where.""; 
    $RequirmentsResult=DB_query($sql9,$db);
    

    
    while ($RequirementsRow = DB_fetch_array($RequirmentsResult)){
        
       $Reqqty=$FGqty*$RequirementsRow['qtypu'];
        
          $sql3="INSERT INTO womaterialrequestdetails (reqno,
                  stockid,
                  qtyrequest) VALUES ( ".$ReqID.",
                  '".$RequirementsRow['stockid']."',
                  ".$Reqqty."       
                  
                  )";
                  
              $result3 = DB_query($sql3, $db);   
}

 print'<script>
     var answer = confirm("Do you want to PRINT the Stores Request?")

   if (answer){
SRreport('.$ReqID.');

   }
 
 
 
 
 
 </script> ';


}
clearfields();
} // End of function submit()

if(isset($_POST['delete']))         {
    
clearfields();    
}

    echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "?" . SID . "'>";  
    
    echo "<div id='fullbody_woentry'>";


//    echo "<input type='hidden' name='New' VALUE='Yes'>";
 //-------------------------------------------------------------Start of Panels
    echo"<div class='panels' id='$panel_id'>";
    echo '<table width=100% ><tr><td width=50% valign="top" height=270px >';   
    
//--------------------------------------------------------------Start of Left Panel1  
    echo "<fieldset  class='left_panel_1'>";     
    echo"<legend><h3>$leg_l1</h3>";
    echo"</legend>"; 
    echo'<div style="height:230px;overflow:auto;">';
    echo'<table id="left_panel_1_WOStoresrequest" width=100%>'; 
    include('WOStoresrequest-leftpanel1.php');
    echo'</table>'; 
    echo'</div>';
    echo "</fieldset>"; 
    echo'</td>';
    
//--------------------------------------------------------------End of Left Panel1

//--------------------------------------------------------------Start of right Panel1  
    echo'<td valign="top" height="270px" id="WOStoresrequest-right_panel_1">';
    
//    echo "<fieldset class='right_panel_1'>";     
//    echo"<legend><h3>$leg_r1</h3>";
//    echo"</legend>"; 
//    echo '<table width=100% class=sortable>';
//    include('WOStoresrequest-rightpanel1.php');
//    echo"</table>";
//    echo "</fieldset>"; 
    

    
    echo"</td></tr></table>";
    echo"</div>";  
//--------------------------------------------------------------End of Panels

    echo"<div class=buttons>";
    echo "<table width=50%><tr>";
    echo "<td><input type='Submit' class=savebutton name='save' VALUE='" . _('Save') . "' onclick='if(log_in()==1)return false;'></td>";
    echo "<td><input type='Submit' name='skip' class='skip' VALUE='" . _('Skip') . "' onclick='if(log_in()==1)return false;'></td>";
    echo "<td><input type='Submit' name='clear' VALUE='" . _('Clear') . "'></td>";
    echo "<td><input type='Submit' name='delete' VALUE='" . _('Delete') . "' onclick='if(log_del()==1)return false;'></td>";
    echo "<td><input type='Button' class='button_details_show' name='details' VALUE='" . _('Details') . "'></td>";
    echo "<td><input type='Button' name='search' VALUE='" . _('Search') . "' onclick='wosearch(2)'></td>";
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
    echo "<a style='cursor:pointer;' id='1' onclick='showdetails(this.id)'>" . _('View BOM requirements') . '</a><br>';
    echo "<a style='cursor:pointer;' id='2' onclick='reportview(this.id)'>" . _('View SRs agains the selected item') . '          </a><br>';
    echo "<a style='cursor:pointer;' id='1' onclick='viewsr(this.id,1)'>" . _('View SRs against selected WO') . '</a><br>';
    echo "<a style='cursor:pointer;' id='1' onclick='viewsr(this.id,2)'>" . _('View Work Orders') . '</a><br>';
    echo "<a style='cursor:pointer;' id='1' onclick='viewsr(this.id,3)'>" . _('View WO status') . '</a><br>';
    echo'<br>';
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
  /////////////  include('WOStoresrequest-datagrid.php');  
    /////////////////////
    
if($_GET['r']==3)        {

$_SESSION['StockID']=$_GET['p'];    
    
}
 
    $part=$_SESSION['StockID'];
    $SeasonID=$_SESSION['SeasonID'];  
    $where = "";
    if ($part) {
        $where = "woitems.stockid='".$part."' AND ";
    
    // If part is entered, it overrides demandtype

    }

    $sql ="SELECT woitems.qtyreqd,
                  woitems.wo,
                  woitems.stockid,
                  stockmaster.description,
                  wostatus.statusid,
                  status.status,
                  workorders.startdate
FROM woitems,wostatus,stockmaster,status,workorders

WHERE ".$where."
      woitems.wo=wostatus.wono AND
      woitems.stockid=stockmaster.stockid   AND
      wostatus.statusid!=1  AND
      wostatus.statusid=status.statusid     AND
      woitems.wo=workorders.wo";      
         $result=DB_query($sql,$db);

      echo '<table width=100% class=sortable>';
      $tableheader = "<thead><tr>
        <th colspan=5 text-align=left>Item selected is: ".$part."</th></tr><tr>
        <th>" . _('Sl no:') . "</th>
        <th>" . _('WO date') . "</th>
        <th>" . _('WO no:') . "</th>
        <th>" . _('Item') . "</th>
        <th>" . _('WO qty') . "</th> 
        <th>" . _('WO status') . "</th>
        </tr></thead>";
    echo $tableheader;
    $j = 1;
      $RowIndex = 0;

      $k=0;
      $slno=0;
      echo"<tbody>";
    while ($myrow=DB_fetch_array($result))   {
        
        $startdate=ConvertSQLDate($myrow['startdate']);
        
                        if ($k==1)
        {
            echo '<tr class="EvenTableRows"  id="'.$myrow['wo'].'" onclick=loadfromgrid(this.id,2)>';
            $k=0;
        } else 
        {
            echo '<tr class="OddTableRows"  id="'.$myrow['wo'].'" onclick=loadfromgrid(this.id,2)>';
            $k++;
        }  
        
       $slno++; 
        printf("<td>$slno</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            </tr>",
            $startdate,
            $myrow['wo'],
            $myrow['description'],
            $myrow['qtyreqd'], 
            $myrow['status']
            );

    $RowIndex = $RowIndex + 1;
//end of page full new headings if
    }
//end of while loop
    echo'<tfoot><tr>';
    echo'<td colspan=7>Number of records:'.$slno.'</td>';
    echo'</tr></tfoot>';
    echo'</tbody>';
    echo '</table>';
    
    /////////////////////
    echo"</div>";
//-----------------------------------------------------------------------End of Data Grid    
 
    echo '</form>';        
    echo "</div>";   
    echo "<br>";
  
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

/*
function caps1()
{
//   alert("sss");
UCWords('custid','Name should be begin with capital letter');
}

     */
 function log_del()
{  //  alert("sss"); alert(mail);
var f=0;       


f=common_error('wono','No SR selected');  if(f==1) { return f;} 


if(f==0){f=common_error('editcheck','Select SR from the details panel');  if(f==1) {document.forms[0].details.focus(); return f; }}   
 

}   

 function log_in()
{  //  alert("sss"); alert(mail);
var f=0;       


f=common_error('srqty','SR quantity cannot be left blank');  if(f==1) { return f;} 
             

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