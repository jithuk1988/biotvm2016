<?php
$PageSecurity=5;
include('includes/session.inc');

 $title = _('Daily Schedules');  
 include('includes/header.inc');   
$pagetype=2;
include('includes/sidemenu1.php');
include('includes/SQL_CommonFunctions.inc');
include('includes/formload.inc');
?>
<script type="text/javascript"> 
/*
$(document).ready(function(){
document.forms[0].qantity.focus();  
  calenderr("duedate");  
  $("#error").fadeOut(3000);
    $("#warn").fadeOut(3000);
      $("#success").fadeOut(3000);
        $("#info").fadeOut(3000);
         $(".db_message").fadeOut(3200);  
        
        
  $("#selectiondetails").hide(); 

        
$('.button_details_show').click(function() {
  $('#selectiondetails').slideToggle('slow', function() {
    // Animation complete.
  });                                                               
}); 

         
});
    */

 function calenderr(){
        new JsDatePick({
            useMode:2,
            target:"duedate",
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


 function showseasonsel(str1,str2,str3,str4,str5,str6,str7,str8)
{
//alert(str1); 
if (str1=="")
  {
  document.getElementById("masterschedule_season_text").innerHTML="";
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
                                                                                     
    document.getElementById("masterschedule_season_text").innerHTML=xmlhttp.responseText;
    document.forms[0].taskname.focus(); 
    calenderr("duedate");
    }
  }

xmlhttp.open("GET","Masterschedule-leftpanel-seasonsel.php?p=" + str1 + "&q=" + str2 + "&r=" + str3 + "&s=" + str4 + "&t=" + str5 + "&u=" + str6 + "&v=" + str7 + "&w=" + str8,true);     
xmlhttp.send(); 
}

 function showdetails(str1,str2,str3,str4,str5,str6,str7,str8)
{
//alert(str1); 
if (str1=="")
  {
  document.getElementById("panel_dailysche").innerHTML="";
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
                                                                                     
    document.getElementById("panel_dailysche").innerHTML=xmlhttp.responseText;
    document.forms[0].taskname.focus(); 
    calenderr("duedate");
    }
  }

xmlhttp.open("GET","Masterschedule-panels.php?p=" + str1 + "&q=" + str2 + "&r=" + str3 + "&s=" + str4 + "&t=" + str5 + "&u=" + str6 + "&v=" + str7 + "&w=" + str8,true);     
xmlhttp.send(); 
}

 function selection(str1,str2,str3,str4,str5,str6,str7,str8)
{

var str2=document.getElementById("itemcode").value;

if (str1=="")
  {
  document.getElementById("right_panelfull_1_MasterSchedule").innerHTML="";
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
                                                                              
    document.getElementById("right_panelfull_1_MasterSchedule").innerHTML=xmlhttp.responseText;
    document.forms[0].taskname.focus(); 
    calenderr("duedate");
    }
  }

xmlhttp.open("GET","Masterschedule-reports.php?p=" + str1 + "&q=" + str2 + "&r=" + str3 + "&s=" + str4 + "&t=" + str5 + "&u=" + str6 + "&v=" + str7 + "&w=" + str8,true);     
xmlhttp.send(); 
}

 function viewdemands(str1,str2,str3,str4,str5,str6,str7,str8)
{
str2=document.getElementById("itemcode").value;

if(str1==1)     {
    
var season=document.getElementById("masterschedule_selsea").value;

myRef = window.open('MasterSchedule-reports-schedules-view.php?id='+ str2 + "&season=" + season,'estr1');
}else if(str1==2)       {
    
myRef = window.open('MasterSchedule-reports-schedules-excel.php?id='+ str2 + "&season=" + season,'estr1');    
}}




/***************************/





 function autochange(str,str1,str2) 
{     //alert(str2) ;   
        var edit1='edit1'+str2;
 
        
    
        var tq=document.getElementById("tqantity").value; 
        document.getElementById("tqantity").value=tq-str1;
        
             
if (str1=="")
  {
  document.getElementById("masterschedule_selsea").innerHTML="";
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
        document.getElementById(edit1).innerHTML=xmlhttp.responseText;      
 //  document.getElementById("Datagrid_masterschedule").innerHTML=xmlhttp.responseText; 

    
    }
  } 
xmlhttp.open("GET","masterschedule_autochange.php?dmid=" +str + "&amt=" +str1,true);   
xmlhttp.send();    
}


/****************************/










 function changeItem() 
{              
 var str1=document.getElementById('masterschedule_selsea').value; 
  var str=document.getElementById('masterschedule_year').value;    
 //
//  alert(str1) ;  

if (str1=="")
  {
  document.getElementById("masterschedule_selsea").innerHTML="";
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
    document.getElementById("itemcode").innerHTML=xmlhttp.responseText;  
    //document.getElementById('loc').focus(); 
    }
  } 
xmlhttp.open("GET","masterschedule_changeitemcombo.php?month=" + str1 + "&year=" +str,true);
xmlhttp.send();    

} 

 function datagridload1()
{
    var str3=document.getElementById("masterschedule_selsea").value;
   var str4=document.getElementById("itemcode").value;
    var str5=document.getElementById("masterschedule_year").value;
    
//alert(str3);
//alert(str4); 
//alert(str5);      
if (str4=="")
  {

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
    document.getElementById("bal").innerHTML=xmlhttp.responseText;
    document.forms[0].duedate.focus();    
//   calenderr("duedate");
    hidegrid();
//    $("#selectiondetails").hide();  
    }
  }

xmlhttp.open("GET","Masterschedule-itemchange.php?month=" + str3 + "&item=" + str4 + "&year=" + str5,true);     
xmlhttp.send(); 


if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp1=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp1=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp1.onreadystatechange=function()
  {
  if (xmlhttp1.readyState==4 && xmlhttp1.status==200)
    {
    document.getElementById("Datagrid_masterschedule").innerHTML=xmlhttp1.responseText;
    document.forms[0].duedate.focus();    
//   calenderr("duedate");
    hidegrid();
//    $("#selectiondetails").hide();  
    }
  }

xmlhttp1.open("GET","Masterschedule-datagrid.php?month=" + str3 + "&item=" + str4 + "&year=" + str5,true);     
xmlhttp1.send();  

}
</script>
<?php    
//--------------------display 
$panel_id='panel_dailysche';
$leg_l1='Daily production plans';
//$leg_r1="Past season's details";
//..........................

$fieldid=get_fieldid('supplierid','suppliers',$db);

if (isset($_GET['StockID'])){
    $StockID = strtoupper($_GET['StockID']);
} elseif (isset($_POST['SupplierID'])){
   $StockID = strtoupper($_POST['StockID']);
} else {
    unset($StockID);
}

// This is aleady linked from this page
//echo "<a href='" . $rootpath . '/SelectSupplier.php?' . SID . "'>" . _('Back to Suppliers') . '</a><br>';
echo '<p class="page_title_text">' . ' ' . _('Master Schedule') . '';

$InputError = 0;

if (isset($Errors)) {
    unset($Errors);
}  

$Errors=Array();   
if(isset($_POST['clear']))       {

   unset($StockID); 
}            
if(isset($_POST['save']))       {

  submit($db,$StockID,$DemandID);
}else if(isset($_POST['skip']))       {
    
    $_POST['Quantity']='';
   submit($db,$StockID,$DemandID);

}                
function submit(&$db,&$StockID,&$DemandID)      {//####SUBMIT_SUBMIT_SUBMIT_SUBMIT_SUBMIT_SUBMIT_SUBMIT_SUBMIT####

// In this section if hit submit button. Do edit checks. If all checks pass, see if record already
// exists for StockID/Duedate/MRPDemandtype combo; that means do an Update, otherwise, do INSERT.
//initialise no input errors assumed initially before we test
    // echo "<br/>Submit - DemandID = $DemandID<br/>";
    $FormatedDuedate = FormatDateForSQL($_POST['Duedate']);
    $InputError = 0;

      //echo"<br><br>".$db;
    /* actions to take once the user has clicked the submit button
    ie the page has called itself with some user input */

    //first off validate inputs sensible
    
  $SesonID=$_POST['SeasonID'];
    $StockID=$_POST['StockID'];
    if(!isset($_POST['skip']))      {
    
    if (!is_numeric($_POST['Quantity'])) {
        $InputError = 1;
        prnMsg(_('Quantity must be numeric'),'error');
    }
    if ($_POST['Quantity'] <= 0) {
        $InputError = 1;
        prnMsg(_('Quantity must be greater than 0'),'error');
    }
    }
    
    if (!is_Date($_POST['Duedate'])) {
        $InputError = 1;
        prnMsg(_('Invalid due date'),'error');
    }  


// Check if part number/demand type/due date combination already exists       
$statusid=3;  
if(isset($_POST['statusid']))       {
    
$statusid=$_POST['statusid'];    

}
 
    

            //If $myrow[0] > 0, it means this is an edit, so do an update
                 $sql = "UPDATE mrpdemands 
                         SET quantity = '" . $_POST['Quantity'] . "',
                             statusid='" . $statusid . "',
                             season_id='" . $_POST['SeasonID'] . "'                   
                         WHERE  duedate = '" . $FormatedDuedate . "'    AND
                                stockid= '" . $StockID . "'     AND
                                season_id= '" . $SesonID . "'";
                $msg = _("The MRP demand record has been updated for").' '.$StockID;

        $result = DB_query($sql,$db,_('The update/addition of the MRP demand record failed because'));
        prnMsg($msg,'success');
        echo '<br>';

            
        
        unset ($_POST['MRPDemandtype']);
        unset ($_POST['Quantity']);
        unset ($_POST['StockID']);
        unset ($_POST['Duedate']);
        unset ($StockID);
        unset ($DemandID);


         
//display($db,$StockID,$DemandID);
} // End of function submit()
function delete(&$db,$DemandID,$demandtype,$StockID) 
{
    // ####DELETE_DELETE_DELETE_DELETE_DELETE_DELETE_####
    // If wanted to have a Confirm routine before did actually deletion, could check if
    // deletion = "yes"; if it did, display link that redirects back to this page
    // like this - <a href=" ' . $_SERVER['PHP_SELF'] . '?&delete=confirm&StockID=' . "$StockID" . ' ">
    // that sets delete=confirm. If delete=confirm, do actually deletion.
    //  This deletes an individual record by DemandID if called from a listall that shows
    // edit/delete or deletes all of a particular demand type if press Delete Demand Type button.
    $where = " ";
    if ($demandtype) 
    {  
        $where = ' WHERE mrpdemandtype =' . "'"  .  $demandtype . "'";
    }
    if ($DemandID) 
    { 
        $sql = 'SELECT mrpdemands.stockid FROM mrpdemands WHERE mrpdemands.demandid='.$DemandID ;
        $ErrMsg = _('The SQL to find the parts selected failed with the message');
        $result = DB_query($sql,$db,$ErrMsg);
        $myrow = DB_fetch_row($result);
        $StockID=$myrow[0];
        $where = ' WHERE demandid =' . "'"  .  $DemandID . "'";
    }
    $sql="DELETE FROM mrpdemands $where";
    $result = DB_query($sql,$db);
    if ($DemandID) 
    {
        prnMsg(_("The MRP demand record for") ." ". $StockID ." ". _("has been deleted"),'succes');
    } 
    else 
    {
        prnMsg(_("All records for demand type") ." ". $demandtype ." " . _("have been deleted"),'succes');
    } 
    unset ($DemandID); 
    unset ($StockID);
    //display($db,$stockID,$DemandID);        

} // End of function delete()        

   echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "?" . SID . "'>";  
    
    echo "<div id='fullbody_masterschedule'>";
                        

//    echo "<input type='hidden' name='New' VALUE='Yes'>";
 //-------------------------------------------------------------Start of Panels
    echo"<div class=panels id=$panel_id>";
    echo '<table width=100%><tr><td width=50% valign="top">';   
    
//--------------------------------------------------------------Start of Left Panel1  
    echo "<fieldset class='left_panel_1'>";     
    echo"<legend><h3>$leg_l1</h3>";
    echo"</legend>"; 
    echo'<table>'; 
    include('MasterSchedule-leftpanel1.php');   
    echo'</table>';                                  
    echo "</fieldset>";                                                    
    echo'</td>';                                                 
                                                                
//--------------------------------------------------------------End of Left Panel1

//--------------------------------------------------------------Start of right Panel1  
    echo'<td valign="top"  id="right_panelfull_1_MasterSchedule">';     
 /*                                                                             
    echo "<fieldset class='right_panel_1'>";     
    echo"<legend><h3>$leg_r1</h3>";
    echo"</legend>";
    echo '<table width=100% class=sortable>';
   // include('MasterSchedule-rightpanel1.php'); //----------M
    echo"</table>";
     "</fieldset>";    */
  
    
    echo"</td></tr></table>";
    echo'<br>';  
    echo"</div>";  
                                                 
//--------------------------------------------------------------End of Panels
   /*
  echo"<div class=buttons>";
    echo "<table width=50%><tr>";
   echo "<td><input type='Submit' name='save' VALUE='" . _('Save') . "' onclick='if(log_in()==1)return false;'></td>";
   echo "<td><input type='Submit' name='skip' VALUE='" . _('Skip') . "' ></td>";
  echo "<td><input type='Submit' name='clear' VALUE='" . _('Clear') . "'></td>";

    echo "<td><input type='Button' class='button_details_show' name='details' VALUE='" . _('Details') . "'></td>";
   echo "</tr></table>";  
    echo"</div>";      */
    
//--------------------------------------------------------------End of Buttons       

    
    /* echo"<div id='selectiondetails'>"; 
    selectiondetails($rootpath,$db);
    echo"</div>"; 
    function selectiondetails($rootpath,$db)     {  
    echo '<table width=100% colspan=2 border=0 cellpadding=4>';
    echo "<tr>
        <th width=33%>" . _('Inquiries') . "</th>
        <th width=33%>". _('Transactions') . "</th>
        <th width=33%>" . _('Maintenance') . "</th>
    </tr>";
    echo '<tr><td VALIGN=TOP class="menu_group_items">';*/       /* Inquiry Options */
//  echo "<a>" . _('') . '</a>';
/*    echo'<br>'; 
    echo'<td VALIGN=TOP class="menu_group_items"></td>';
    echo '<td VALIGN=TOP class="menu_group_items">';
    echo "<a style='cursor:pointer;' id='2' onclick='selection(this.id)'>" . _('View Daily Schedules') . '</a><br>';
    echo'<br>';
    echo '</td></tr>';
    echo'<tr><td></td><td></td><td>';
    echo "<input type='Button' class='button_details_hide' VALUE='" . _('Hide Details') . "'>"; 
    echo'</td></tr>';
    echo'</table>';
    }           */
    echo'</div>';    
            
//-----------------------------------------------------------------------End of Details   
      
    echo"<div class='Datagrid' id=Datagrid_masterschedule>";
   /// include('Masterschedule-datagrid.php'); 
   
          $_POST['StockID ']=$_GET['p'];  
 if($_GET['p']!='')     {
     
  $_SESSION['StockID']=$_GET['p'];  
  $_SESSION['SeasonID']=$_GET['r'];   
 }
 
    //$part=$_SESSION['StockID'];
    //$SeasonID=$_SESSION['SeasonID'];  
      $part=$_GET['p'];  
    $SeasonID=$_GET['r'];
    $yr=$_GET['year'];        
    
    $where = "";
    if ($part) {
        $where = ' WHERE mrpdemands.stockid =' . "'"  .  $part . "' AND 
                         mrpdemands.season_id=$SeasonID     AND
                         mrpdemands.year=$yr     AND
                         mrpdemands.stockid = stockmaster.stockid";
    
    // If part is entered, it overrides demandtype


        
    
    $sql = 'SELECT mrpdemands.demandid,
                   mrpdemands.stockid,
                   mrpdemands.quantity,
                   mrpdemands.statusid,
                   mrpdemands.duedate,
                   stockmaster.description
        FROM mrpdemands,stockmaster
        ' .
         $where    . ' ORDER BY mrpdemands.duedate';      
         $result=DB_query($sql,$db);
 }
      echo '<table width=100% class=sortable>';
      $tableheader = "<thead><tr>
        <th>" . _('Sl no:') . "</th>
        <th>" . _('Item') . "</th>
        <th>" . _('Demand Quantity') . "</th>
        <th>" . _('Status') . "</th>
        <th>" . _('Date') . "</th>
        </tr></thead>";
    echo $tableheader;
    $j = 1;
      $RowIndex = 0;

      $k=0;
      $slno=0;
      echo"<tbody>";
    while ($myrow=DB_fetch_array($result))   {    
           $slno++;     
           
         if($myrow['statusid']!=3)
         {
             $a="<input type='text'  value='%s' readonly onchange=autochange(".$myrow['demandid'].",this.value);>";
         }
         else
         {
             $a="<input type='text'  value='%s'  onchange=autochange(".$myrow['demandid'].",this.value,$slno);>";
         }
                 
        $sql5="SELECT status
              FROM dev_mrpdemandstatus
              WHERE statusid=".$myrow['statusid']."";
        $result5=DB_query($sql5,$db);
        $myrow5=DB_fetch_array($result5);
                        if ($k==1)
        {
            echo '<tr class="EvenTableRows"  id="'.$myrow['demandid'].'" >';
            $k=0;
        } else 
        {
            echo '<tr class="OddTableRows"  id="'.$myrow['demandid'].'" >';
            $k++;
        }  
        
          
        printf("<td>$slno</td>
            <td>%s</td>
            <td id='edit1".$slno."'>$a</td>
            <td>%s</td>
            <td>%s</td>
            </tr>",
            $myrow['description'],
            $myrow['quantity'],
            $myrow5[0],
            $myrow['duedate']
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
   
   
   
   
        ////////////
    
    echo"</div>";
  
 
    echo '</form>';        
    echo "</div>";   
    echo "<br>";
  
  include('includes/smenufooter.inc'); 
         
?>
<script language="javascript">


 //document.getElementById('custid').focus(); 
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
}     */
 function log_in()
{  //  alert("sss"); alert(mail);
var f=0;       


f=common_error('duedate','Please Enter the Duedate');  if(f==1) { return f;} 
             


if(f==0){f=common_error('qantity','Enter the quantity, to skip a date use the skip button');  if(f==1) { document.forms[0].skip.focus();return f; }}                  
if(f==0){f=common_error('suppliername','Please Enter company name');  if(f==1) { return f; }}     
if(f==0){f=common_error('outputtype','Please Select an Output Type');  if(f==1) { return f; }} 

if(f==0){f=common_error('sourcetype','Please Select a LeadSource Type');  if(f==1) { return f; }}
if(f==0){f=common_error('source','Please Select a LeadSource');  if(f==1) { return f; }} 
if(f==0){f=common_error('Scheme','Please Select a Scheme');  if(f==1) { return f; }}     
if(f==0){f=common_error('feedstock','Please Select a Fead Stock');  if(f==1) { return f; }}     }

function hidegrid(){
  $("#selectiondetails").hide(); 
  $('.button_details_show').click(function() {
  $('#selectiondetails').slideToggle('slow', function() {

  });
}); 
 }      

</script>