<?php
    $PageSecurity = 80;
include('includes/session.inc');
$title = _('Delivery Note Management');  
include('includes/header.inc');
echo '<center><font style="color: #333;
    background:#fff;
    font-weight:bold;
    letter-spacing:0.10em;
    font-size:16px;
    font-family:Georgia;
    text-shadow: 1px 1px 1px #666;">Delivery Note Management</font></center>';
  echo'<a href="bio_dispatched_cust.php"><h4>BACK TO LIST</h4></a>';  
   
      
    /*function get_instl_taskdate($type,$stkcode,$taskno,$newdate,$db){
        
    $sql="select prevtask from bio_def_cstask where cstype='$type' AND stockcode='$stkcode' AND taskno='$taskno'";
    $result=DB_query($sql,$db);
    $myrow=DB_fetch_row($result);
    if($myrow[0]!=0){
    $sql_days="SELECT daystocomplete from bio_def_cstask WHERE cstype='$type' AND stockcode='$stkcode' AND taskno='$myrow[0]'";
    $result_days=DB_query($sql_days,$db);
    $myrow_days=DB_fetch_array($result_days);
      $days=$myrow_days[0];
    
    $newdate = strtotime ( '+'.$days.' day' , strtotime ( $newdate ) );
     // echo $newdate = date ( 'Y-m-d' , $newdate );
   return $newdate = date ( 'Y-m-d' , $newdate );
    }else{
        return $newdate;
    }
      
 
}*/
      
   
    echo "<form action=" . $_SERVER['PHP_SELF'] . "?" . SID ." method=POST>";  
    echo"<div id=plant1></div>";

    if($_GET['orderno']==NULL){
        $orderno=$_POST['orderno'];
    }else{
     $orderno=$_GET['orderno'];
 }
    

    if($_GET['plant']==NULL){
        $planttype=$_POST['plant'];
    }
    else{
     $planttype=$_GET['plant'];
 } 
                        /*$sql_deliverydate="SELECT 
              salesorderdetails.stkcode,
              salesorderdetails.actualdispatchdate
              FROM salesorderdetails,salesorders,stockmaster
              WHERE salesorderdetails.orderno=salesorders.orderno
              AND stockmaster.stockid=salesorderdetails.stkcode
              AND salesorderdetails.completed=1
              AND salesorderdetails.orderno=".$orderno;
              $result_deliverydate=DB_query($sql_deliverydate,$db);
     $row_deliverydate=DB_fetch_array($result_deliverydate,$db);
     $deldat=explode(" ",$row_deliverydate['actualdispatchdate']);
     $deliverydate=$deldat[0];
     $sql_next="SELECT DATE_ADD('$deliverydate', INTERVAL 1 DAY) as nextdate";
     $result_next=DB_query($sql_next,$db);
     $row_next=DB_fetch_array($result_next,$db);
     $row_next['nextdate'];        */
 
               
 if($planttype!='')
 {
      
       
    $sql1="SELECT custbranch.brname,
              custbranch.phoneno,custbranch.did,custbranch.stateid,custbranch.cid,bio_district.district,
              salesorderdetails.sched_instlln_date,bio_despatchClearence.sched_instlln_date
              from custbranch,salesorders,salesorderdetails,bio_district,bio_despatchClearence 
              where salesorders.debtorno=custbranch.debtorno
              AND salesorders.orderno=salesorderdetails.orderno
              AND bio_district.did=custbranch.did
              AND    bio_district.stateid=custbranch.stateid
              AND    bio_district.cid=custbranch.cid
              AND bio_despatchClearence.orderno=salesorderdetails.orderno 
              AND bio_despatchClearence.despatch_id='".$_GET['id']."'
              AND salesorderdetails.orderno=".$orderno;
     $result1=DB_query($sql1,$db);
     $row1=DB_fetch_array($result1,$db);
     
     //$enqid=$row1['enqtypeid'];
     
  //echo dateschedule();        
    
echo"<fieldset style='width:90%;'>";


echo '<table class="selection">
            <tr>
                <th> ' . _('Customer Name') . ' :<b> ' . $row1['brname'].'</b></th>
                <th>' . _('District') . ' :<b> ' . $row1['district']. '</b></th>
                <th>' . _('Contact No') . ' :<b> ' . $row1['phoneno']. '</b></th>
            </tr>
            <tr>
                <th colspan ="3"><b>' . _('Scheduled Installation Date:') . ' ' . convertSQLDate($row1['sched_instlln_date']) . '</b></th>
            </tr>
            </table>
            <br />';
            
            
            
            $sql="SELECT salesorderdetails.orderlineno,salesorderdetails.orderno,bio_despatchClearence.id,bio_despatchClearence.despatch_id,
            bio_despatchClearence.serialno
             ,salesorderdetails.quantity ,stockmaster.description,bio_despatchClearence.dc_qty
             from salesorderdetails,stockmaster,bio_despatchClearence
             where 
              stockmaster.stockid=salesorderdetails.stkcode
             AND bio_despatchClearence.orderno=salesorderdetails.orderno 
             AND bio_despatchClearence.stockid=salesorderdetails.stkcode  
             AND bio_despatchClearence.despatch_id=".$_GET['id']."
             AND salesorderdetails.orderno=".$orderno;
             $result=DB_query($sql,$db);
                                                //    salesorderdetails.stkcode='".$planttype."'
            
            /*$sql_new="SELECT count(id) as count from bio_cstask where orderno=$orderno AND stockcode='$planttype' AND cstype=1";
            $result_new=DB_query($sql_new,$db);
            $row_new=DB_fetch_array($result_new);
            
            $sql_new1="SELECT count(id) as count1 from bio_def_cstask where stockcode='$planttype' AND cstype=1";
            $result_new1=DB_query($sql_new1,$db);
            $row_new1=DB_fetch_array($result_new1);
            
            
            $sql_min="SELECT min(prevtask) as recent_task from bio_cstask where orderno='$orderno' AND stockcode='$planttype' 
            AND status=0 AND cstype=1";
            $result_min=DB_query($sql_min,$db);
            $row_min=DB_fetch_array($result_min);   */
           //echo $row_min['recent_task'];
    
      //  echo $row_new['count'];
      
      //--------Select team-----//
      
                    
//---------------------------------------------------------------------//     
 echo"<table style='border:1px solid #F0F0F0;width:100%'>";
 echo"<tr><td>";
 
 //-------------- Add new Tasks-----------------------
 /*echo"<fieldset style='width:95%;'>";
    echo"<legend><h3>Add new task</h3></legend>";
    echo "<table class='selection' style='width:98%;'>";
    echo'<tr><td>Task :</td><td><input type="text" name="des" id="des"></td><td>Days to complete:</td><td><input type="text" name="comdays" id="comdays"></td><td>Previous Task No:</td><td><input type="text" name="prevno" id="prevno"></td><td><input type=submit name=newtask value="' . _('Add Task') . '" onclick= "if(validate()==1)return false;"></td></tr>';
     echo "</table>";
echo "</fieldset>";            */

                                    /* $sql_team="SELECT
    `bio_leadteams`.`teamname`,`bio_leadteams`.`teamid`
        FROM `bio_teammembers`
        INNER JOIN `bio_emp` ON (`bio_teammembers`.`empid` = `bio_emp`.`empid`)
        INNER JOIN `bio_leadteams` ON (`bio_leadteams`.`teamid` = `bio_teammembers`.`teamid`)
        WHERE bio_emp.offid=(SELECT officeid from bio_csmteams where did=".$row1['did'].")
        AND bio_emp.deptid IN (5,6)";*/


 
//-------------- Show itemS-----------------------
    echo"<fieldset style='width:95%;'>";
    echo"<legend><h3>Delivery Note For Item</h3></legend>";
    echo "<table style='width:90%;'>";
          
    echo"<tr bgcolor='#E08566'><td width='60px'>Order No</td>
             <td>Item</td>
             <td width='110px'>Quantity Ordered</td>
              <td width='100px'>Despatch Cleared</td>
              <td>Serial No.</td>
             <td width='100px'>Quantity For DN</td>
             <td width='100px'><a style=cursor:pointer; id='".$orderno."' onclick=select(this.id,'".$planttype."','".$_GET['id']."')>Print</a></td>
             </tr>"; 
                while($myrow=DB_fetch_array($result))
                {   
                    echo'<tr bgcolor=#F2D6BA><td>'.$myrow['orderno'].'</td>
                    <td>'.$myrow['description'].'</td>
                    <td>'.$myrow['quantity'].'</td>
                       <td>'.$myrow['dc_qty'].'</td>
                       <td>'.$myrow['serialno'].'</td>
                    <td><input type="text" readonly name="thisdel" id="thisdel" value='.$myrow['dc_qty'].'></td> 
                     
                    </tr>';
                } 
                      //  <td><a style=cursor:pointer; id='.$myrow['orderno'].' onclick=select(this.id,"'.$planttype.'","'.$myrow['id'].'")>Print</a></td>  
     
         
           
    



 echo "</table>";
echo "</fieldset>";
//------------------------------------------------------  
 
echo"</td><td>";

//------------------------------------------------------   
/*
echo'<input type=hidden name=new_year id=new_year value='.$row_next['nextdate'].'>';
echo'<input type=hidden name=no id=no value='.$no.'>';
echo'<input type=hidden name=orderno id=orderno value='.$orderno.'>';
echo'<input type=hidden name=plant id=plant value='.$planttype.'>';
echo'<input type=hidden name=custid id=custid value='.$row1['cust_id'].'>';
echo'<input type=hidden name=no id=no value='.$no.'>';
                                                                        */

//echo$a;
 
/*if($row_new['count']==0 && $row_new1['count1']!=0 ){
echo'<tr><td colspan=2><p><div class="centre">
         <input type=submit name=submit value="' . _('Submit') . '" onclick="if(validation()==1) return false;">';
}elseif($row_new['count']!=0){
    echo'<tr><td colspan=2><p><div class="centre">
         <input type=submit name=edit value="' . _('Edit') . '" onclick="if(validation()==1) return false;">';
}

echo"</td></tr>";    */
echo"</table>";
//------Reception---------------------------------------------------//   
 echo"<fieldset style='width:95%;'>";
    echo"<legend><h3>Delivery Note Reception</h3></legend>";
    
    
    
    echo "<table class='selection' style='width:60%;'>";     //  <td>Item Code</td>      <td width='110px'>Quantity Ordered</td>   <td width='100px'>Quantity For DN</td>      
             
    echo"<tr bgcolor='#E08566'><td width='60px'>DN No.</td>
    <td width='60px'>Order No</td>
              <td width='100px'>Recieve Date</td> 
             <td width='100px'>Recieve Status</td>
             </tr>"; 
$sql_recieve="SELECT *FROM bio_deliverynote where orderno='".$orderno."' AND despatch_id=".$_GET['id']." GROUP BY despatch_id";
   $result_recieve=DB_query($sql_recieve,$db);
   while($myrow_recieve=DB_fetch_array($result_recieve))
                {
                    echo'<tr bgcolor=#F2D6BA><td>'.$myrow_recieve['despatch_id'].'</td>
                    <td>'.$myrow_recieve['orderno'].'</td>
                  
                    <td><input type=text name=recdate id=recdate class=date alt='.$_SESSION['DefaultDateFormat'].'></td> 
                    <td><select id='.$myrow_recieve['despatch_id'].'  onchange=statuschange(this.id,'.$orderno.',this.value)>
                    <option value="0"></option>
                    <option value="1">Recieved</option>
                    </select></td>   
                    </tr>';
                } 
     echo "</table>";
echo "</fieldset>"; /* <td>'.$myrow_recieve['stockcode'].'</td>
                    <td>'.$myrow_recieve['quantity_ordered'].'</td>
                    <td>'.$myrow_recieve['this_delivery'].'</td   >   */
//----------------------------------------------------------------------//

echo''; 
 
 echo"</fieldset>";
 echo"</form>"; 
 

 }     
    
    
?>


<script type="text/javascript">  
function select(str,str1,str3)
{     
   var str2=document.getElementById('thisdel').value; 
               // alert(str3); 
  //  alert(str); 
window.location="bio_deliverynote_print.php?orderno=" + str + "&plant=" +str1 + "&thisdel=" + str2 + "&desid=" + str3;
}

function statuschange(str,str2,str3)
{
var str4=document.getElementById('recdate').value;  // alert(str4);     
 //alert(str3);//alert(str1);       
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
        
    document.getElementById("plant1").innerHTML=xmlhttp.responseText;
//      $('#dinhide').show(); 
    }
  } 
  //alert(str);
xmlhttp.open("GET","bio_deliverynotestatus.php?no="+str +  "&ord=" + str2 + "&status=" + str3 + "&recdate=" +str4,true);
xmlhttp.send();
    
}

</script>
