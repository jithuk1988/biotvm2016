<?php
$PageSecurity = 80;
include('includes/session.inc');
$title = _('DocumentList');  
include('includes/header.inc');          
echo '<form action="' . $_SERVER['PHP_SELF'] . '" method="post" onsubmit="return validate()"  >';   // onsubmit="return validate()"   
  echo"<fieldset >";      //style='width:90%;'
echo"<legend><h3></h3></legend>";
    echo"<table ><tr>";  //style='width:100%';
  
    echo"<td><input type='checkbox' name='address' id='address' value='1'   onclick='addAddress()'>Received Documents Only &nbsp;&nbsp;&nbsp;";
    echo"<td id='addressdiv'>From Date:</td><td id='addressdiv1'><input type='text' name='fr_date' class=date alt=".$_SESSION['DefaultDateFormat']."></td>"; 
    echo"<td id='addressdiv2'>To Date:</td><td id='addressdiv3'><input type='text' name='to_date' class=date alt=".$_SESSION['DefaultDateFormat']."></td>"; 
      
    echo '<td>Customer Type<select name="enq" id="enq" style="width:150px" >';    //     onchange=showdocs(this.value)
    echo '<option value=0></option>';   
    $sql1="select * from bio_enquirytypes";
    $result1=DB_query($sql1,$db);   
 
    while($row1=DB_fetch_array($result1))
    {
    //  echo "<option value=$row1[enqtypeid]>$row1[enquirytype]</option>";
        if ($row1['enqtypeid']==$_POST['enq'])
           {
             echo '<option selected value="';
           } 
           else {

            echo '<option value="';
        }
           echo $row1['enqtypeid'] . '">'.$row1['enquirytype'];
           echo '</option>';  

    }

echo '</select></td><td>Archieve</td>';
   if($_POST[arc]==1){
 echo'<td><select name=arc id=arc >
       <option  value=0>Unarchieved</option>
       <option selected value=1>Archieved</option> </select></td>';  
   }
   else
   {
        echo'<td><select name=arc id=arc >
       <option selected value=0>Unarchieved</option>
       <option  value=1>Archieved</option> </select></td>'; 
       
   }
   echo '<td><input type="Submit" name="submit"  value="' . _('Search') . '" ></td></tr></table></div>';
    
      echo"</fieldset >"; 
echo '</form>'; 
  
if(isset($_POST['submit']))
{

$sql_old="SELECT DISTINCT `bio_oldorderdoclist`.`orderno` AS oldorders,0 AS neworders
    , `bio_oldorders`.`createdon` AS orderdate
    , `debtorsmaster`.`debtorno`
    , `debtorsmaster`.`name`
    , `debtorsmaster`.`block_name`  
    , `debtorsmaster`.`clientsince`
    , `custbranch`.`phoneno`
    , `custbranch`.`faxno`
    , `bio_district`.`district`
    , `bio_panchayat`.`name` AS panchayath
    , `bio_corporation`.`corporation`
    , `bio_municipality`.`municipality`
    , `debtorsmaster`.`LSG_type` ,bio_fileno.fileno  
FROM
    `bio_oldorderdoclist`
    INNER JOIN `bio_oldorders` 
        ON (`bio_oldorderdoclist`.`orderno` = `bio_oldorders`.`orderno`)
    INNER JOIN `debtorsmaster` 
        ON (`bio_oldorders`.`debtorno` = `debtorsmaster`.`debtorno`)
    INNER JOIN `custbranch` 
        ON (`debtorsmaster`.`debtorno` = `custbranch`.`debtorno`)
    LEFT JOIN `bio_district` 
        ON (`debtorsmaster`.`did` = `bio_district`.`did`) AND (`bio_district`.`cid` = `debtorsmaster`.`cid`) AND (`debtorsmaster`.`stateid` = `bio_district`.`stateid`)
    LEFT JOIN `bio_panchayat` 
        ON (`debtorsmaster`.`did` = `bio_panchayat`.`district`) AND (`debtorsmaster`.`block_name` = `bio_panchayat`.`id`) AND (`debtorsmaster`.`stateid` = `bio_panchayat`.`state`) AND (`debtorsmaster`.`LSG_name` = `bio_panchayat`.`block`) AND (`debtorsmaster`.`cid` = `bio_panchayat`.`country`)
    LEFT JOIN `bio_corporation` 
        ON (`bio_corporation`.`country` = `debtorsmaster`.`cid`) AND (`bio_corporation`.`state` = `debtorsmaster`.`stateid`) AND (`bio_corporation`.`district` = `debtorsmaster`.`LSG_name`) AND (`bio_corporation`.`district` = `debtorsmaster`.`did`)
    LEFT JOIN `bio_municipality` 
        ON (`bio_municipality`.`country` = `debtorsmaster`.`cid`) AND (`bio_municipality`.`state` = `debtorsmaster`.`stateid`) AND (`bio_municipality`.`district` = `debtorsmaster`.`did`) AND (`bio_municipality`.`id` = `debtorsmaster`.`LSG_name`)
    LEFT JOIN `bio_fileno`
        on (bio_fileno.orderno=`bio_oldorders`.`orderno`) AND (bio_fileno.debtorno=`debtorsmaster`.`debtorno`)
    WHERE bio_oldorderdoclist.status!=5"; 
    if($_POST['arc']==1)
                   {
                       $sql_old .= " AND `debtorsmaster`.`debtorno` in  (SELECT bio_fileno.debtorno FROM `bio_fileno`)  AND `bio_oldorderdoclist`.`orderno` in  (SELECT bio_fileno.orderno FROM `bio_fileno`) ";     
                   }   
                else    if($_POST['arc']==0)
                    {
                            $sql_old .= " AND `debtorsmaster`.`debtorno` not in (SELECT bio_fileno.debtorno FROM `bio_fileno`) AND `bio_oldorderdoclist`.`orderno` not in  (SELECT bio_fileno.orderno FROM `bio_fileno`)  ";                                                                                                                                                                                                
                    }
    if ( $_POST['enq']!=0 || $_GET['enq']!=0)
                   {  
                     if ( $_POST['enq']==1 || $_GET['enq']==1){                                   
                       $sql_old .= " AND bio_oldorders.debtorno LIKE 'D%'";  
                     }else if ( $_POST['enq']==2 || $_GET['enq']==2){                                   
                       $sql_old .= " AND bio_oldorders.debtorno LIKE 'C%'";                 
                     }else if ( $_POST['enq']==3 || $_GET['enq']==3){                                   
                       $sql_old .= " AND bio_oldorders.debtorno LIKE 'L%'";                 
                     }
                   } 
if($_POST['fr_date']!=NULL and $_POST['to_date']!=NULL)
{
$sql_old.=" AND  bio_oldorderdoclist.receivedDate BETWEEN '".FormatDateForSQL($_POST['fr_date'])."' AND '".FormatDateForSQL($_POST['to_date'])."'";   
}                                                           

$sql_so="SELECT DISTINCT 0 AS oldorders,`salesorders`.`orderno` AS neworders
    , `salesorders`.`orddate` AS orderdate
    , `debtorsmaster`.`debtorno`
    , `debtorsmaster`.`name`
    , `debtorsmaster`.`block_name`  
    , `debtorsmaster`.`clientsince`
    , `custbranch`.`phoneno`
    , `custbranch`.`faxno`
    , `bio_district`.`district`
    , `bio_panchayat`.`name` AS panchayath
    , `bio_corporation`.`corporation`
    , `bio_municipality`.`municipality`
    , `debtorsmaster`.`LSG_type`,bio_fileno.fileno
FROM  `bio_documentlist`        
    INNER JOIN `salesorders` 
        ON (`bio_documentlist`.`orderno` = `salesorders`.`orderno`)
    INNER JOIN `salesorderdetails` 
        ON (`salesorderdetails`.`orderno` = `salesorders`.`orderno`)    
    INNER JOIN debtorsmaster 
        ON (`salesorders`.`debtorno` = `debtorsmaster`.`debtorno`)
    INNER JOIN `custbranch` 
        ON (`debtorsmaster`.`debtorno` = `custbranch`.`debtorno`)
    LEFT JOIN `bio_district` 
        ON (`debtorsmaster`.`did` = `bio_district`.`did`) AND (`bio_district`.`cid` = `debtorsmaster`.`cid`) AND (`debtorsmaster`.`stateid` = `bio_district`.`stateid`)
    LEFT JOIN `bio_panchayat` 
        ON (`debtorsmaster`.`did` = `bio_panchayat`.`district`) AND (`debtorsmaster`.`block_name` = `bio_panchayat`.`id`) AND (`debtorsmaster`.`stateid` = `bio_panchayat`.`state`) AND (`debtorsmaster`.`LSG_name` = `bio_panchayat`.`block`) AND (`debtorsmaster`.`cid` = `bio_panchayat`.`country`)
    LEFT JOIN `bio_corporation` 
        ON (`bio_corporation`.`country` = `debtorsmaster`.`cid`) AND (`bio_corporation`.`state` = `debtorsmaster`.`stateid`) AND (`bio_corporation`.`district` = `debtorsmaster`.`LSG_name`) AND (`bio_corporation`.`district` = `debtorsmaster`.`did`)
    LEFT JOIN `bio_municipality` 
        ON (`bio_municipality`.`country` = `debtorsmaster`.`cid`) AND (`bio_municipality`.`state` = `debtorsmaster`.`stateid`) AND (`bio_municipality`.`district` = `debtorsmaster`.`did`) AND (`bio_municipality`.`id` = `debtorsmaster`.`LSG_name`)
    LEFT JOIN `bio_fileno`
        on (bio_fileno.orderno=`salesorders`.`orderno`) AND (bio_fileno.debtorno=`debtorsmaster`.`debtorno`)
    WHERE bio_documentlist.status!=5"; 
    if($_POST['arc']==1)
                   {
                       $sql_so .= " AND `debtorsmaster`.`debtorno` in  (SELECT bio_fileno.debtorno FROM `bio_fileno`) AND `salesorders`.`orderno` in  (SELECT bio_fileno.orderno FROM `bio_fileno`) ";     
                   }   
                else    if($_POST['arc']==0)
                    {
                            $sql_so .= " AND `debtorsmaster`.`debtorno` not in (SELECT bio_fileno.debtorno FROM `bio_fileno`) AND `salesorders`.`orderno` not in  (SELECT bio_fileno.orderno FROM `bio_fileno`)  ";                                                                                                                                                                                                
                    }
    if ( $_POST['enq']!=0 || $_GET['enq']!=0)
                   {  
                     if ( $_POST['enq']==1 || $_GET['enq']==1){                                   
                       $sql_so .= " AND salesorders.debtorno LIKE 'D%'";  
                     }else if ( $_POST['enq']==2 || $_GET['enq']==2){                                   
                       $sql_so .= " AND salesorders.debtorno LIKE 'C%'";                 
                     }else if ( $_POST['enq']==3 || $_GET['enq']==3){                                   
                       $sql_so .= " AND salesorders.debtorno LIKE 'L%'";                 
                     }
                   } 
    if($_POST['fr_date']!=NULL and $_POST['to_date']!=NULL)
{
    
$sql_so.=" AND  bio_documentlist.receivedDate BETWEEN '".FormatDateForSQL($_POST['fr_date'])."' AND '".FormatDateForSQL($_POST['to_date'])."'";   
}  
       $sql=   $sql_old." UNION ".$sql_so;      
    //  echo   $sql;
$result_old=DB_query($sql,$db);
 
$order_new=array(); 
$order_old=array();  
   
$k=0;
$slno=0;
   $enq1=$_POST['enq']; 

        echo '<br>';
    echo"<table><tr><th>SL No</th><th>Customer Name</th><th>District</th><th>LSG</th>";
    if($_POST['arc']==1){echo"<th>File No</th>";}
    if($_POST['enq']!=0)
    {
       echo"      <th>Doc1</th><th>Doc2</th><th>Doc3</th><th>Doc4</th><th>Doc5</th><th>Doc6</th><th>Doc7</th><th>Doc8</th><th>Doc9</th><th>Doc10</th><th>Doc11</th><th>Doc12</th>";
                                                                  if($_POST['enq']==2 || $_POST['enq']==3)  {
                                                                  echo"<th>Doc13<th>Doc14</th><th>Doc15</th><th>Doc16</th>"; 
                                                                  }   }
                                                                  echo"<th>View</th></tr>"; 
while($row_old=DB_fetch_array($result_old))
{

  $slno++; 
     $new=$row_old['neworders'];  
      if($new!='' && $new!=0)
      {   
      $orderno=$new; 
      $order_new[]=$new; 
     
      }
      
      $old=$row_old['oldorders'];  
      if($old!='' && $old!=0)
      {    
      $orderno=$old;   
      $order_old[]=$old;

      }
/*     $debtorno=$row_old['debtorno'];  
     $sql_ordtable="SELECT orderno FROM bio_oldorders WHERE debtorno='$debtorno'";
      $result_ordertable=DB_query($sql_ordtable,$db);
      $count=DB_num_rows($result_ordertable);
      $myrow_ordtable=DB_fetch_array($result_ordertable); 
      if($count>0)
      {
         $ordtab=1;  
      }
      else{
      $sql_ordtable="SELECT orderno FROM salesorders WHERE debtorno='$debtorno'";
      $result_ordertable=DB_query($sql_ordtable,$db);
      $count=DB_num_rows($result_ordertable);
      $myrow_ordtable=DB_fetch_array($result_ordertable);
      if($count>0)
      {
         $ordtab=2;  
      }    
      } */
/*if($orderno!='' && $debtorno!='')
{                 
        $sql_doc="SELECT bio_fileno.fileno
                    FROM bio_fileno 
                   WHERE bio_fileno.orderno=$orderno 
                     AND bio_fileno.debtorno=$debtorno
                      ";
                     
} */
if(isset($_POST['enq'])) {

      
     if($row_old['LSG_type']==1){
         $LSG_name=$row_old['corporation']."(C)";
     }elseif($row_old['LSG_type']==2){
         $LSG_name=$row_old['municipality']."(M)";
     }elseif($row_old['LSG_type']==3){
         if($row_old['block_name']!=0 || $myrow3['LSG_name']!=0){
         $LSG_name=$row_old['panchayath']."(P)";
         }
     }elseif($row_old['LSG_type']==0){
         $LSG_name="";
     }             
}                  

  
if($new!='' && $new!=0)
{                 
        $sql_doc="SELECT bio_documentlist.status,bio_document_master.document_name 
                    FROM bio_documentlist,bio_document_master 
                   WHERE bio_documentlist.orderno=$new 
                     AND bio_documentlist.docno=bio_document_master.doc_no 
                     AND bio_document_master.enqtypeid='".$_POST['enq']."'";                
}
elseif($old!='' && $old!=0)  
{
    $sql_doc="SELECT bio_oldorderdoclist.status,bio_document_master.document_name 
                FROM bio_oldorderdoclist,bio_document_master 
               WHERE bio_oldorderdoclist.orderno=$old 
                 AND bio_oldorderdoclist.docno=bio_document_master.doc_no 
                 AND bio_document_master.enqtypeid='".$_POST['enq']."'";
}               
$result_doc=DB_query($sql_doc,$db);
      if ($k==1)
          {
            echo '<tr class="EvenTableRows">';
            $k=0;
          }else 
          {
            echo '<tr class="OddTableRows">';
            $k=1;     
          }
                                              
 
      echo"
               <td>".$slno."</td> 
            
               <td>".$row_old['name']."</td>
             <td class='dist'>".$row_old['district']."</td> 
             <td>".$LSG_name."</td> "; 
             if($_POST['arc']==1){
          echo"   <td>".$row_old['fileno']."</td> "; }           // <td><a href id='".$ordtab."-".$myrow_ordtable[0]."-".$enq."' onclick='viewdocuments(this.id);return false;'>View</a></td>
while($row_doc=DB_fetch_array($result_doc))
{    
if($row_doc['status']<1) {
    $status="No";
    $fontcolor='red';  

}else{
    $status="Yes";
    $fontcolor='blue';
}     

      echo"<td title='".$row_doc['document_name']."'> <font color='$fontcolor'>".$status."</font></td>";    
                  
}  

echo "<td><a href id='".$ordtab."-".$new."-".$old."-".$enq1."' onclick='viewdocuments(this.id);return false;'>View</a></td>";                                                                                                      
//echo "<td><a href id='".$ordtab."-".$myrow_ordtable[0]."-".$enq1."' onclick='viewdocuments(this.id);return false;'>View</a></td>";

             

 
  
    
          
  

}
                echo"</table>"; 
}  
 
     
?>
<script type="text/javascript"> 
   var addr=document.getElementById("address").checked;
 if(addr==false){
   $("#addressdiv").hide();
  $("#addressdiv1").hide();
  $("#addressdiv2").hide();
  $("#addressdiv3").hide();   
   
}
    function addAddress(str) {
       // $("#addressdiv").hide();   
    addr=document.getElementById("address").checked;
    if(addr==true){
       $('#addressdiv').fadeIn(1000);
       $('#addressdiv1').fadeIn(1000);
       $('#addressdiv2').fadeIn(1000);
       $('#addressdiv3').fadeIn(1000); 
    }else if(addr==false){
       $('#addressdiv').fadeOut(1000); 
       $('#addressdiv1').fadeOut(1000); 
       $('#addressdiv2').fadeOut(1000); 
       $('#addressdiv3').fadeOut(1000); 
    } 
 }
 
          

function viewdocuments(str)
{      
//return false;                        // alert(str);
    var split = str.split('-');  
   // var split1=split[0];             
    var split2=split[1];                                    
    var split3=split[2];
    var split4=split[3];                             // alert(split); 
    
    if(split2==0)
    {      
       controlWindow=window.open("bio_OLDdocumentmanagement.php?orderno="+split3+"&enq="+split4,"docdetails","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=1200,height=600");     
    return false;
    }
    else if(split3==0)
    {     
       controlWindow=window.open("bio_documentmanagement.php?orderno="+split2,"plantdetails","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=1200,height=600") 
    return false;
    }            
}  
function validate(str)
{
var ct=document.getElementById('enq').value;  //   alert(ct);
if(ct!=0)
{    
    return true;
} 
else{ 
alert("Select Custmer Type");                        
return false;
}

}
 </script>