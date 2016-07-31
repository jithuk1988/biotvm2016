<?php
$PageSecurity = 5;

include('includes/session.inc');

$title = _('Work Order Entry');

include('includes/header.inc');

$pagetype=2;
include('includes/sidemenu1.php');
include('includes/SQL_CommonFunctions.inc');
include('includes/formload.inc');
?>
<script type="text/javascript"> 
$(document).ready(function(){
document.forms[0].StockID.focus();  
    
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

 function showdetails(str1,str2,str3,str4,str5,str6,str7,str8)
{
//alert(str1); 
if (str1=="")
  {
  document.getElementById("panel_woentry").innerHTML="";
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
                                                                                     
    document.getElementById("panel_woentry").innerHTML=xmlhttp.responseText;
    document.forms[0].taskname.focus(); 
    }
  }

xmlhttp.open("GET","WOEntry-panels.php?p=" + str1 + "&q=" + str2 + "&r=" + str3 + "&s=" + str4 + "&t=" + str5 + "&u=" + str6 + "&v=" + str7 + "&w=" + str8,true);     
xmlhttp.send();
}

function datagridload(str1,str2,str3,str4,str5,str6,str7,str8)
{

if (str1=="")
  {
  document.getElementById("fullbody_woentry").innerHTML="";
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

    document.getElementById("fullbody_woentry").innerHTML=xmlhttp.responseText;
    if(document.getElementById("reqd").value=="")        {
    document.forms[0].Reqd.focus();     
    }else       {
    document.forms[0].Quantity.focus();  
    }  
    hidegrid();
//    $("#selectiondetails").hide();  
    }
  }

xmlhttp.open("GET","WOEntry-itemchange.php?p=" + str1 + "&q=" + str2 + "&r=" + str3 + "&s=" + str4 + "&t=" + str5 + "&u=" + str6 + "&v=" + str7 + "&w=" + str8,true);     
xmlhttp.send(); 
}

function viewforreport(str1,str2,str3,str4,str5,str6,str7,str8)
{
  //alert ('fg');
if (str1=="")
  {
  document.getElementById("WOEntry-right_panel_1").innerHTML="";
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
    document.getElementById("WOEntry-right_panel_1").innerHTML=xmlhttp.responseText;

    if(document.getElementById("reqd").value=="")        {
    document.forms[0].Reqd.focus();     
    }else       {
    document.forms[0].Quantity.focus();  
    }  

//    $("#selectiondetails").hide();  
    }
  }

xmlhttp.open("GET","WOEntry-viewforreports.php?p=" + str1 + "&q=" + str2 + "&r=" + str3 + "&s=" + str4 + "&t=" + str5 + "&u=" + str6 + "&v=" + str7 + "&w=" + str8,true);     
xmlhttp.send(); 
}


function viewreport(str1,str2,str3,str4,str5,str6,str7,str8)
{
    
var season=document.getElementById("season").value;    
var item=document.getElementById("stockid").value;
if(item=='')     {

alert("Select an item to view its SR");    
return;   
}
if(str2==1)         {
myRef = window.open('WOEntry-reports-sritem.php?id='+ str1 +'&season='+ season +'&item='+item,'estr1');        
}

}

</script>
<?php
//--------------------display 
$panel_id='panel_woentry';
$leg_l1='Enter WO details';
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
echo '<p class="page_title_text">' . ' ' . _('Work Order Entry') . '';

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
 unset($_POST['DemandQuantity']);
 unset($_POST['Quantity']);
 unset($_POST['Batch']);  
}

if (isset($_POST['save'])) { //The update button has been clicked       

if(isset($_POST['Wonumber']))       {
    
    $sql7="SELECT COUNT(*) 
           FROM womaterialrequest
           WHERE wono=".$_POST['Wonumber'];
    $result7=DB_query($sql7,$db);
    $myrow7=DB_fetch_row($result7);
    if($myrow7[0]>0)        {
        
     echo'<script>alert("Work Order cannot be updated - SRs have already issued")</script>';   
    }else{
    
    $sql6="UPDATE wostatus 
           SET statusid=".$_POST['Wostatus']."
           WHERE wono=".$_POST['Wonumber'];
    $result6=DB_query($sql6,$db);
    }
}else       {
$_POST['Wono']=$fieldid;

//echo"<br>sdfsdfs".$_POST['demandno'];    
//break;    
  
         $FormatedDuedate1 = FormatDateForSQL($_POST['Duedate']); 
         $FormatedDuedate2 = FormatDateForSQL($_POST['Reqd']); 
         $InsWOResult = DB_query("INSERT INTO workorders (wo,
                                                     loccode,
                                                     requiredby,
                                                     startdate,
                                                     demandid)
                                  VALUES (" . $_POST['Wono'] . ",
                                            '" . $_SESSION['UserStockLocation'] . "',
                                            '" . $FormatedDuedate1 . "',
                                            '" . $FormatedDuedate2. "',
                                            " . $_POST['demandno'] . ")",
                                $db);

//if (isset($_POST['StockID']) AND isset($_POST['Wono'])){
      $InputError=false;   
      $CheckItemResult = DB_query("SELECT mbflag,
                                            eoq,
                                            controlled
                                            FROM stockmaster
                                            WHERE stockid='" . $_POST['StockID'] . "'",
                                            $db);
      if (DB_num_rows($CheckItemResult)==1){
              $CheckItemRow = DB_fetch_array($CheckItemResult);
            if ($CheckItemRow['controlled']==1 AND $_SESSION['DefineControlledOnWOEntry']==1){ //need to add serial nos or batches to determine quantity
                $EOQ = 0;
            } else {
                $EOQ = $CheckItemRow['eoq'];
            }
              if ($CheckItemRow['mbflag']!='M'){
                  prnMsg(_('The item selected cannot be added to a work order because it is not a manufactured item'),'warn');
                  $InputError=true;
              }
      } else {
              prnMsg(_('The item selected cannot be found in the database'),'error');
              $InputError = true;
      }
      $CheckItemResult = DB_query("SELECT stockid
                                    FROM woitems
                                    WHERE stockid='" . $_POST['StockID'] . "'
                                    AND wo=" .$_POST['Wono'],
                                    $db);
      if (DB_num_rows($CheckItemResult)==1){
              prnMsg(_('This item is already on the work order and cannot be added again'),'warn');
              $InputError=true;
      }


      if ($InputError==false){
        $CostResult = DB_query("SELECT SUM((materialcost+labourcost+overheadcost)*bom.quantity) AS cost
                                                        FROM stockmaster INNER JOIN bom
                                                        ON stockmaster.stockid=bom.component
                                                        WHERE bom.parent='" . $_POST['StockID'] . "'
                                                        AND bom.loccode='" . $_POST['StockLocation'] . "'",
                             $db);
            $CostRow = DB_fetch_row($CostResult);
        if (is_null($CostRow[0]) OR $CostRow[0]==0){
                $Cost =0;   
                prnMsg(_('The cost of this item as accumulated from the sum of the component costs is nil. This could be because there is no bill of material set up ... you may wish to double check this'),'warn');
        } else {
                $Cost = $CostRow[0];
        }
        if (!isset($EOQ)){
            $EOQ=1;
        }
        
        $Result = DB_Txn_Begin($db);
//          echo "<br><br>dddddddddd".$_POST['Quantity']; 
        // insert parent item info
        $sql = "INSERT INTO woitems (wo,
                                 stockid,
                                 qtyreqd,
                                 stdcost)
             VALUES ( " . $_POST['Wono'] . ",
                         '" .$_POST['StockID']  . "',
                         " . $_POST['Quantity'] . ",
                          " . $Cost . "
                          )";
        $ErrMsg = _('The work order item could not be added');
        $result = DB_query($sql,$db,$ErrMsg);    

        //Recursively insert real component requirements - see includes/SQL_CommonFunctions.in for function WoRealRequirements
        WoRealRequirements($db, $_POST['Wono'], $_POST['StockLocation'], $_POST['StockID']);

        $result = DB_Txn_Commit($db);        
         
    } //end if there were no input errors
//} 

//echo"<br>nfgfffffffffffffffffffff".$_POST['Wono'];
//                   echo"sdfsdsfdfdsfsdfsdfsdfsdfsfdfsdfsd";               
                      $sql1 = "INSERT INTO woserialnos (wo,
                                                stockid,
                                                serialno,
                                                quantity) 
                                        VALUES ('" . $_POST['Wono'] . "','" . $_POST['StockID'] . "', '" . $_POST['Batch'] . "','" . $_POST['Quantity'] . "')";

                                       $result1 = DB_query($sql1,$db,$ErrMsg); 
                                       
                                              $sql1 = "INSERT INTO wostatus (wono
                                                ) 
                                        VALUES ('" . $_POST['Wono'] . "')";

                                       $result1 = DB_query($sql1,$db,$ErrMsg);    
                                       
                                                                     
   
//    $Input_Error = false; //hope for the best
//     for ($i=1;$i<=$_POST['NumberOfOutputs'];$i++){


//           if (!is_numeric($_POST['Quantity'])){
//               prnMsg(_('The quantity entered must be numeric'),'error');
//            $Input_Error = true;
//        } elseif ($_POST['Quantity']<=0){
//            prnMsg(_('The quantity entered must be a positive number greater than zero'),'error');
//            $Input_Error = true;
//        }
        
        
//     }

//     if (!Is_Date($_POST['Reqd'])){
//        prnMsg(_('The required by date entered is in an invalid format'),'error');
//        $Input_Error = true;
//     }

//    if ($Input_Error == false) {

//        $SQL_ReqDate = FormatDateForSQL($_POST['Reqd']);
//        $QtyRecd=0;    

//        for ($i=1;$i<=$_POST['NumberOfOutputs'];$i++){
//                $QtyRecd+=$_POST['RecdQty'.$i];  
//        }

//        if ($QtyRecd==0){ //can only change factory location if Qty Recd is 0
//                $sql[] = "UPDATE workorders SET requiredby='" . $SQL_ReqDate . "'
//                                                
//                            WHERE wo=" . $_POST['Wono'];
//                            
//                              echo "ssssssssssssssssssss";                    
// 
//        } else {
//                prnMsg(_('The factory where this work order is made can only be updated if the quantity received on all output items is 0'),'warn');
//                $sql[] = "UPDATE workorders SET requiredby='" . $SQL_ReqDate . "'
//                            WHERE wo=" . $_POST['Wono'];
//        }


//        for ($i=1;$i<=$_POST['NumberOfOutputs'];$i++){
//            if (!isset($_POST['NextLotSNRef'.$i])) {
//                $_POST['NextLotSNRef'.$i]='';
//            }
//                if (isset($_POST['QtyRecd'.$i]) and $_POST['QtyRecd'.$i]>$_POST['OutputQty'.$i]){
//                        $_POST['OutputQty'.$i]=$_POST['QtyRecd'.$i]; //OutputQty must be >= Qty already reced
//                }
//                if ($_POST['RecdQty'.$i]==0 AND $_POST['HasWOSerialNos'.$i]==false){ // can only change location cost if QtyRecd=0
//                        $CostResult = DB_query("SELECT SUM((materialcost+labourcost+overheadcost)*bom.quantity) AS cost
//                                                        FROM stockmaster INNER JOIN bom
//                                                        ON stockmaster.stockid=bom.component
//                                                        WHERE bom.parent='" . $_POST['OutputItem'.$i] . "'
//                                                        AND bom.loccode='" . $_POST['StockLocation'] . "'",
//                                     $db);
//                        $CostRow = DB_fetch_row($CostResult);
//                        if (is_null($CostRow[0])){
//                            $Cost =0;  
//                            prnMsg(_('The cost of this item as accumulated from the sum of the component costs is nil. This could be because there is no bill of material set up ... you may wish to double check this'),'warn');
//                        } else {
//                            $Cost = $CostRow[0];
//                        }
//                        $sql[] = "UPDATE woitems SET qtyreqd =  ". $_POST['Quantity'] . ",
//                                                 
//                                                 stdcost =" . $Cost . "
//                                  WHERE wo=" . $_POST['Wono'] . "
//                                  AND stockid='" . $_POST['StockID'] . "'";
//                                  

//                                       
//                                       
//                                  
//                  } elseif ($_POST['HasWOSerialNos'.$i]==false) {
//                        $sql[] = "UPDATE woitems SET qtyreqd =  ". $_POST['Quantity'] . ",
//                                  WHERE wo=" . $_POST['Wono'] . "
//                                  AND stockid='" . $_POST['StockID'] . "'";
//                }
//        }

        //run the SQL from either of the above possibilites
//        $ErrMsg = _('The work order could not be added/updated');
//        foreach ($sql as $sql_stmt){
        //    echo '<br>' . $sql_stmt;
//            $result = DB_query($sql_stmt,$db,$ErrMsg);

//        }

//        prnMsg(_('The work order has been updated'),'success');

//        for ($i=1;$i<=$_POST['NumberOfOutputs'];$i++){
//                   unset($_POST['OutputItem'.$i]);
//                 unset($_POST['OutputQty'.$i]);
//                 unset($_POST['QtyRecd'.$i]);
//                 unset($_POST['NetLotSNRef'.$i]);
//                 unset($_POST['HasWOSerialNos'.$i]);
//        }
//        echo '<br><a href="' . $_SERVER['PHP_SELF'] . '?' . SID . "'>" . _('Enter a new work order') . '</a>';
//        echo '<br><a href="' . $rootpath . '/SelectWorkOrder.php?' . SID . '">' . _('Select an existing work order') . '</a>';
//        echo '<br><a href="'. $rootpath . '/WorkOrderCosting.php?' . SID . '&WO=' .  $_REQUEST['WO'] . '">' . _('Go to Costing'). '</a>';
//        echo '<br><br>';
//        
//    }  
//$sql="SELECT quantity
//      FROM mrpdemands
//      WHERE demandid=".$_POST['demandno'];
//$result=DB_query($sql,$db);
//$myrow=DB_fetch_array($result);
//$demandqty=$myrow[0];

//$sql2="SELECT wo
//       FROM workorders
//       WHERE demandid=".$_POST['demandno'];
//$result2=DB_query($sql2,$db);
//$totalwoqty=0;
//while($myrow2=DB_fetch_array($result2))     {
//    
//  $sql5="SELECT qtyreqd
//         FROM woitems
//         WHERE wo=".$myrow2[0];
//  $result5=DB_query($sql5);
//  $myrow5=DB_fetch_array($result5,$db);
//  $totalwoqty=$totalwoqty+$myrow5[0];
//    
//}

$sql4="UPDATE mrpdemands
       SET statusid=5
       WHERE demandid=".$_POST['demandno'];
$result4=DB_query($sql4,$db);

    
prnMsg(_('Work order has been created'),'success');   

clearfields();
}
} // End of function submit()

    echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "?" . SID . "'>";  
    
    echo "<div id='fullbody_woentry'>";


//    echo "<input type='hidden' name='New' VALUE='Yes'>";
 //-------------------------------------------------------------Start of Panels
    echo"<div class=panels id=$panel_id>";
    echo '<table width=100%><tr><td width=50% valign="top" height=270px>';   
    
//--------------------------------------------------------------Start of Left Panel1  
    echo "<fieldset class='left_panel_1'>";     
    echo"<legend><h3>$leg_l1</h3>";
    echo"</legend>"; 
    echo'<table>'; 
   // include('WOEntry-leftpanel1.php');
   
   //////////////////////
   $currentyear=2012;

//if(isset($_SESSION['StockID']))     {
//    
//$StockID=$_SESSION['StockID'];    
//}

$sql="SELECT m_season.season_id,
             m_season.season_sub_id
      FROM m_season
      WHERE m_season.is_current=1";
$result=DB_query($sql,$db);
$myrow=DB_fetch_array($result);
$SeasonnameID=$myrow[1];
$SeasonID=$myrow[0];


echo'<input type="hidden" name="SeasonID" value='.$SeasonID.'>';
echo "<tr><input type='hidden' id='' name='' value=$currentyear></tr>"; 
echo '<tr><td>' . _('Season') . "*</td><td><select name='Season'>";

       $sql1 = 'SELECT m_sub_season.seasonname,
                     season_sub_id
                FROM m_sub_season';
        $result1 = DB_query($sql1,$db);
        
        
        
  $Year=Date("Y"); 
        $f=0;
        while ($myrow1 = DB_fetch_array($result1)) {
            
            if ( $myrow1['season_sub_id']==$SeasonnameID) {
                echo "<option selected value='" . $myrow1['season_sub_id'] . "'>" . $myrow1['seasonname']; 
            } else  if ($f==0){
                         
        echo '<option>';

            }     echo "<option value='" . $myrow1['season_sub_id'] . "'>" . $myrow1['seasonname']; 
           
        $f++;    
        } //end while loop
        echo '</select>'; //--------------</select season>
echo"</td></tr>";
echo '<tr><td>' . _('Location') . "*</td><td><select name='Loccode'>";

        $sql = "SELECT locations.loccode,     
                       locations.locationname                                        
                FROM locations";
            $result = DB_query($sql,$db); 
$f=0;            
while ($myrow= DB_fetch_array($result)) {
            if ( $myrow['loccode']==$_SESSION['UserStockLocation']) {
                echo "<option selected value='" . $myrow['loccode'] . "'>" . $myrow['locationname']; 
            } else if ($f==0){
                         
        echo '<option>';
        }
 echo "<option value='" . $myrow['loccode'] . "'>" . $myrow['locationname']; 
          
        
        $f++;    
        } //end while loop
            
        echo '</select>';  //--------------</select Location>
echo"</td></tr>";

$StockID='';
echo '<tr><td>' . _('Item') . "*</td><td><select name='StockID' id='woentrystockid' onchange='datagridload(this.value,".$SeasonID.")'>";
//$_SESSION['UserStockID']
            $sql = "SELECT stockmaster.stockid,
                    stockmaster.description
                                        
                FROM stockmaster
                WHERE stockmaster.mbflag='M' AND stockmaster.categoryid !=13
                ORDER BY stockmaster.stockid";                                                            
            $result = DB_query($sql,$db); 
$f=0;            
while ($myrow= DB_fetch_array($result)) {
            if ( $myrow['stockid']==$_SESSION['StockID']) {
                echo "<option selected value='" . $myrow['stockid'] . "'>" . $myrow['description']; 
            } else if ($f==0){
                         
        echo '<option>';
        }
 echo "<option value='" . $myrow['stockid'] . "'>" . $myrow['description']; 
          
    
        $f++;    
        } //end while loop
            
        echo '</select>';  //--------------</select Item>
echo"</td></tr>";
if(!isset($_GET['p']))      {
    
$sql8="SELECT duedate,
              demandid,
              quantity     
       FROM mrpdemands
       WHERE stockid='".$_SESSION['StockID']."'     AND
             statusid=4
       ORDER BY duedate
       DESC LIMIT 1";
$result8=DB_query($sql8,$db);
$numrow=DB_num_rows($result8);
$myrow8=DB_fetch_array($result8);
if($numrow>0)      {
$_POST['Duedate']=ConvertSQLDate($myrow8['duedate']);
$_POST['demandno']=$myrow8['demandid'];
$_POST['DemandQuantity']=$myrow8['quantity'];
}
$sql9="SELECT serialno     
       FROM woserialnos
       WHERE stockid='".$_SESSION['StockID']."'
       ORDER BY serialno
       DESC LIMIT 1";
$result9=DB_query($sql9,$db);
$myrow9=DB_fetch_array($result9);
$_POST['Batch']=$myrow9['serialno'];
}

echo '<tr>';
echo"<input type='hidden' class=date alt=".$_SESSION['DefaultDateFormat']." name='Reqd' value='".$_POST['Duedate']."' id='reqd' size=25
            maxlength=12 value=".$_POST['Duedate'].">";
echo '<tr><td>' . _('Required Date') . "*</td>";
echo"<td><input type='hidden' class=date alt=".$_SESSION['DefaultDateFormat']." name='Duedate' value='".$_POST['Duedate']."' id='duedate' size=25
            maxlength=12>".$_POST['Duedate']."</td>"; 
echo '<tr><td>' . _('Demand Quantity') . "</td><td><input type='hidden' id='demandquantity' name='DemandQuantity' value='".$_POST['DemandQuantity']."' size=25 maxlength=40>
".$_POST['DemandQuantity']."</td></tr>";
echo '<tr><td>' . _('WO Quantity') . "*</td><td><input type='text' id='quantity' name='Quantity' value='".$_POST['Quantity']."' size=25 maxlength=40></td></tr>";
echo '<tr><td>' . _('Batch') . "*</td><td><input type='text' id='batch' name='Batch' value='".$_POST['Batch']."' size=25 maxlength=40></td></tr>"; 
if($wono!='')       {
    

echo '<tr><td>' . _('Status') . "*</td><td><select name='Wostatus'>";

        $sql = "SELECT status,
                       statusid                                        
                FROM status";
            $result = DB_query($sql,$db); 
            
while ($myrow= DB_fetch_array($result)) {
            if ( $myrow['statusid']==$_POST['Wostatus']) {
                echo "<option selected value='" . $myrow['statusid'] . "'>" . $myrow['status']; 
            } 
 echo "<option value='" . $myrow['statusid'] . "'>" . $myrow['status']; 
   
        } //end while loop
            
        echo '</select>';  //--------------</select Location>
echo"</td></tr>";   
    
}
echo '<input type="hidden" name=demandno value="'.$_POST['demandno'].'">';
   //////////////////////
   
    echo'</table>'; 
    echo "</fieldset>"; 
    echo'</td>';
    
//--------------------------------------------------------------End of Left Panel1

//--------------------------------------------------------------Start of right Panel1  
    echo'<td valign="top" height=270px id="WOEntry-right_panel_1">';
    
   /* echo "<fieldset class='right_panel_1'>";     
    echo"<legend><h3>$leg_r1</h3>";
    echo"</legend>";
    echo '<table width=100% class=sortable>';
    include('WorkOrderEntry-rightpanel1.php'); //----------M
    echo"</table>";
    echo "</fieldset>";  */
    

    
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
    echo "<a style='cursor:pointer;' id='viewwos' onclick='viewforreport(this.id,1)'>" . _('View Work Orders') . '</a><br>';
    echo '</td></tr>';
//    echo'<tr><td></td><td></td><td>';
//    echo "<input type='Button' class='button_details_hide' VALUE='" . _('Hide Details') . "'>"; 
//    echo'</td></tr>';
    echo'</table>';
    
    }
//-----------------------------------------------------------------------End of Details   
      

    
    echo"<div class='Datagrid' id=Datagrid_masterschedule>";
  //include('WOEntry-datagrid.php');  
  
 
if((isset($_GET['p'])) AND (($_GET['p']!="") OR ($_GET['p']!="undefined")))       {

  $_POST['StockID ']=$_GET['p'];    
  $_SESSION['StockID']=$_GET['p'];  
  $_SESSION['SeasonID']=$_GET['q'];   
 }
 
    $part=$_SESSION['StockID'];
    $SeasonID=$_SESSION['SeasonID'];  
    $where = "";
    if ($part) {
        $where = ' WHERE ((mrpdemands.statusid=4) OR (mrpdemands.statusid=5)) AND mrpdemands.stockid =' . "'"  .  $part . "' AND mrpdemands.season_id=$SeasonID";
    
    // If part is entered, it overrides demandtype

  

    $sql = 'SELECT mrpdemands.demandid,
                   mrpdemands.stockid,
                   mrpdemands.quantity,
                   mrpdemands.statusid,
                   mrpdemands.duedate,                 
                   stockmaster.description,
                   dev_mrpdemandstatus.status,
                   workorders.wo
        FROM mrpdemands
        LEFT JOIN stockmaster on mrpdemands.stockid = stockmaster.stockid
        LEFT JOIN dev_mrpdemandstatus on mrpdemands.statusid = dev_mrpdemandstatus.statusid
        LEFT JOIN workorders on mrpdemands.demandid = workorders.demandid' .
        $where    . ' ORDER BY mrpdemands.stockid, mrpdemands.duedate';      
        $result=DB_query($sql,$db);
 }
      echo '<table width=100% class=sortable>';
      $tableheader = "<thead><tr>
        <th colspan=5 text-align=left>Item selected is: ".$part."</th></tr><tr>
        <th>" . _('Sl no:') . "</th>
        <th>" . _('Demand no:') . "</th>
        <th>" . _('Date') . "</th>    
                 <th>" . _('Demand Qty') . "</th>   
        <th>" . _('WO Status') . "</th>
        <th>" . _('WO Qty') . "</th>
        </tr></thead>";
      echo $tableheader;
      $j = 1;
      $RowIndex = 0;

      $k=0;
      $slno=0;
      echo"<tbody>";
      while ($myrow=DB_fetch_array($result))   {
        
        if($myrow['wo']!='')        {
            
            $sql3="SELECT qtyreqd
                   FROM woitems
                   WHERE wo=".$myrow['wo'];
            $result3=DB_query($sql3,$db);
            $myrow3=DB_fetch_array($result3);
            $woqty=$myrow3[0];
            
            $sql4="SELECT wostatus.statusid,
                          status.status
                   FROM wostatus,status
                   WHERE wono=".$myrow['wo']."    AND
                         wostatus.statusid=status.statusid";
            $result4=DB_query($sql4,$db);
            $myrow4=DB_fetch_array($result4);
            $wostatus=$myrow4[1];
            
            
        }else   {
        
        $woqty="";
        $wostatus="no work orders created";
        }

        $duedate=ConvertSQLDate($myrow['duedate']);
        
        $datagridid=$myrow['demandid']."/".$myrow['wo'];
                        if ($k==1)
        {
            echo '<tr class="EvenTableRows"  id="'.$datagridid.'" onclick=showdetails(this.id)>';
            $k=0;
        } else 
        {
            echo '<tr class="OddTableRows"  id="'.$datagridid.'" onclick=showdetails(this.id)>';
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
            $myrow['demandid'],
            $duedate,
            $myrow['quantity'],
            $wostatus,
            $woqty
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
    echo"</div>";
  
 
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