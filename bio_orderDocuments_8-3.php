<?php
    $PageSecurity = 80;
include('includes/session.inc');
$title = _('Order Document List');  
//if($_POST['regtype']!=3) { 
include('includes/header.inc');
//}
echo '<center><font style="color: #333;
    background:#fff;
    font-weight:bold;
    letter-spacing:0.10em;
    font-size:16px;
    font-family:Georgia;
    text-shadow: 1px 1px 1px #666;">Order Documents</font></center>';
    
    
 
 
    echo"<fieldset style='width:90%;'>";
    echo"<legend>client / Document List</legend>";
      
   echo "<form action=" . $_SERVER['PHP_SELF'] . "?" . SID ." method=POST>";   
//if($_POST['regtype']!=3) {         
    echo"<table style='border:1px solid #F0F0F0;width:80%'>"; 
 
     

echo"<tr><td>CreatedON From</td><td>CreatedON To</td><td>Name</td><td>Contact No</td><td>Client From</td><td>Client To</td><td>Register Type</td></tr>";
echo"<tr>"; 
echo "<td><input type=text id='createdfrm' class=date alt=".$_SESSION['DefaultDateFormat']." name='createdfrm' value='$_POST[createdfrm]' onChange='return isDate(this, this.value, ".$_SESSION['DefaultDateFormat'].")'></td>";
echo "<td><input type=text id='createdto' class=date alt=".$_SESSION['DefaultDateFormat']." name='createdto' value='$_POST[createdto]' onChange='return isDate(this, this.value, ".$_SESSION['DefaultDateFormat'].")'></td>";

    echo "<td><input style=width:150px type='text' name='name' id='name' style='width:100px'></td>";  
    echo "<td><input style=width:150px type='text' name='contno' id='contno' style='width:100px'></td>"; 


    echo "<td><input type=text id='periodfrm' class=date alt=".$_SESSION['DefaultDateFormat']." name='periodfrm' value='$_POST[periodfrm]' onChange='return isDate(this, this.value, ".$_SESSION['DefaultDateFormat'].")'></td>";
    echo "<td><input type=text id='periodto' class=date alt=".$_SESSION['DefaultDateFormat']." name='periodto' value='$_POST[periodto]' onChange='return isDate(this, this.value, ".$_SESSION['DefaultDateFormat'].")'></td>";

  
 echo'<td><select name=regtype id=regtype onchange=consolidated(this.value);>';
 if($_POST[regtype]==1){
   echo'<option selected value=1>Consolidated</option>
        <option value=3>Document Status</option>  
        <option value=2>Detailed</option>'; 
 }elseif($_POST[regtype]==2){ 
   echo'<option selected value=2>Detailed</option>
        <option value=3>Document Status</option>  
        <option value=1>Consolidated</option>';   
 }elseif($_POST[regtype]==3){ 
   echo'<option selected value=3>Document Status</option>
        <option value=2>Detailed</option>  
        <option value=1>Consolidated</option>';   
 }else{
   echo'<option value=0></option>  
        <option value=3>Document Status</option>  
        <option selected value=2>Detailed</option>
        <option value=1>Consolidated</option>';  
 }          
 echo'</select></td>';
 
 echo "</tr>";
    


echo"<td>Country<select name='country' id='country' onchange='showstate(this.value)' style='width:130px'>";
$sql="SELECT * FROM bio_country ORDER BY cid";
$result=DB_query($sql,$db);
 $f=0;
  while($myrow1=DB_fetch_array($result))
  {  
  if ($myrow1['cid']==1)  
    {         //echo $myrow1['cid'];     
    echo '<option selected value="';
    } else 
    {
    if ($f==0) 
        {
        echo '<option>';
        }
        echo '<option value="';
    }
    echo $myrow1['cid'] . '">'.$myrow1['country'];
    echo '</option>';
    $f++;
   }    
  echo '</select></td>'; 
  
  echo '<td id="showstate">State<select name="state" id="state" onchange="showdistrict(this.value)" style="width:130px">';
  $sql="SELECT * FROM bio_state ORDER BY stateid";
  $result=DB_query($sql,$db);
  $f=0;      
  
  while($myrow1=DB_fetch_array($result))
  {
  if ($myrow1['stateid']==14)
    {
    echo '<option selected value="';
    } else
    {
    if ($f==0)
        {
        echo '<option>';
        }
        echo '<option value="';
    }
    echo $myrow1['stateid'] . '">'.$myrow1['state'];
    echo '</option>';
    $f++;
   }
  echo '</select>';
  echo'</td>'; 
  
 echo '<td id="showdistrict">District<select name="district" id="district" style="width:130px" onchange="showtaluk(this.value);">'; 
  $sql="SELECT * FROM bio_district WHERE stateid=14 ORDER BY did";
    $result=DB_query($sql,$db);
    $f=0;
  while($myrow1=DB_fetch_array($result))
  {
  if ($myrow1['did']==$_POST['district'])
    {
    echo '<option selected value="';
    } else
    {
    if ($f==0)
        {
        echo '<option>';
        }
        echo '<option value="';
    }
    echo $myrow1['did'] . '">'.$myrow1['district'];
    echo '</option>';
    $f++;
   } 
  echo '</select>';
  echo'</td>';
    

      echo '<td id=showtaluk>Taluk<select name="taluk" id="taluk" style="width:130px" tabindex=11 onchange="showVillage(this.value)">';
      $sql_taluk="SELECT * FROM bio_taluk ORDER BY bio_taluk.taluk ASC";
      $result_taluk=DB_query($sql_taluk,$db);
      $f=0;
      while($myrow7=DB_fetch_array($result_taluk))
      {
      if ($myrow7['id']==$_POST['taluk'])
      {
      echo '<option selected value="';
      } else
      {
      if ($f==0)
        {
        echo '<option>';
        }
        echo '<option value="';
      }
      echo $myrow7['id'] . '">'.$myrow7['taluk'];
      echo '</option>';
      $f++;
      }
      echo '</select>';
      echo '</td>';
          
echo"<td id=showvillage>Village<select name='village' id='village' style='width:130px'>";      
   $sql_taluk="SELECT * FROM bio_village ORDER BY bio_village.village ASC";
      $result_taluk=DB_query($sql_taluk,$db);
      $f=0;
      while($myrow7=DB_fetch_array($result_taluk))
      {
      if ($myrow7['id']==$_POST['village'])
      {
      echo '<option selected value="';
      } else
      {
      if ($f==0)
        {
        echo '<option>';
        }
        echo '<option value="';
        }
      echo $myrow7['id'] . '">'.$myrow7['village'];
      echo '</option>';
      $f++;
      }
      echo '</select>';
      echo'</td>';
      
    echo '<td>LSG Type<select name="lsgType" id="lsgType" style="width:130px" onchange="showblock(this.value)">';
    echo '<option value=0></option>'; 
    echo '<option value=1>Corporation</option>';
    echo '<option value=2>Muncipality</option>';
    echo '<option value=3>Panchayat</option>';           
    echo '</select></td>'; 
  
        echo '<td align=left colspan=2>';
        echo'<div style="align:right" id=block>';             
        echo'</div>';
        echo'</td>';
      
        echo'<td><div id=showpanchayath></div></td>';


   $sql1="SELECT distinct stockmaster.description,stockmaster.stockid from stockmaster,stockcategory,bio_maincat_subcat where stockmaster.categoryid in ('P-LSGD','PDO','OP','FRP-GO','GD','LD','MD','RCC-MS') order by stockmaster.longdescription asc";
      $result1=DB_query($sql1, $db);
      $desp=$myrow1['longdescription'];    
   echo '<tr><td>Plant'; 
   echo '<select name="plant" id="plant" style="width:130px" onchange="showdescription()">';
    $f=0;
    
    while($myrow1=DB_fetch_array($result1))
    { 
  if ($myrow1['stockid']==$_POST['plant'])  
    {         //echo $myrow1['cid'];     
    echo '<option selected value="';
    } else 
    {
    if ($f==0) 
        {
        echo '<option>';
        }
        echo '<option value="';
    }
    echo $myrow1['stockid'] . '">'.$myrow1['description'];
    echo '</option>';                            
    $f++;
    }
  echo '</select></td>'; 
  
  if($_GET['plantid']!=""){
      $plantid=$_GET['plantid'];
  }elseif($_POST['plantstatus']!=""){
      $plantid=$_POST['plantstatus'];
  }
  

  echo"<td id=showPlantstatus>Plant Status<select name='plantstatus' id='plantstatus' style='width:130px'>";      
  
  $sql="SELECT * FROM bio_plantstatus ORDER BY id";
$result=DB_query($sql,$db);
//echo"<option value=0></option>";
 $f=0;
  while($myrow1=DB_fetch_array($result))
  {  
  if ($myrow1['id']==$plantid)  
    {        // echo $myrow1['cid'];     
    echo '<option selected value="';
    } else 
    {
    if ($f==0) 
        {
        echo '<option>';
        }
        echo '<option value="';
    }
    echo $myrow1['id'] . '">'.$myrow1['plantstatus'];
    echo '</option>';
    $f++;
   }    
   
echo'</td>';   
 // echo'</tr>';
  //echo"<tr>";

    echo '<td>Customer Type<select name="enq" id="enq" style="width:150px" onchange=showdocs(this.value)>';
    echo '<option value=0></option>';   
    $sql1="select * from bio_enquirytypes";
    $result1=DB_query($sql1,$db);
    while($row1=DB_fetch_array($result1))
    {
    //  echo "<option value=$row1[enqtypeid]>$row1[enquirytype]</option>";
        if ($row1['enqtypeid']==$_POST['enq'])
           {
             echo '<option selected value="';
           } else 
           { 
               echo '<option value="'; 
           }
           echo $row1['enqtypeid'] . '">'.$row1['enquirytype'];
           echo '</option>';  
    }

echo '</select></td>';


    echo"<td colspan=2>";
    echo '<table id=showdocument>';

    
    echo'<td class=conso>From Beneficiary <select name="docname_frm" id="docname_frm" style="width:150px" >';
    echo '<option value=0></option>';   
    $sql1="select * from bio_document_master WHERE document_type=1";
    $result1=DB_query($sql1,$db);
    while($row1=DB_fetch_array($result1))
    {
    //  echo "<option value=$row1[enqtypeid]>$row1[enquirytype]</option>";
        if ($row1['doc_no']==$_POST['docname_frm'])
           {
             echo '<option selected value="';
           } else 
           { 
               echo '<option value="'; 
           }
           echo $row1['doc_no'] . '">'.$row1['document_name'];
           echo '</option>';  
    }

echo '</select></td>';

    echo'<td class=conso>To Beneficiary <select name="docname_to" id="docname_to" style="width:150px" >';
    echo '<option value=0></option>';   
    $sql1="select * from bio_document_master WHERE document_type=2";
    $result1=DB_query($sql1,$db);
    while($row1=DB_fetch_array($result1))
    {
    //  echo "<option value=$row1[enqtypeid]>$row1[enquirytype]</option>";
        if ($row1['doc_no']==$_POST['docname_to'])
           {
             echo '<option selected value="';
           } else 
           { 
               echo '<option value="'; 
           }
           echo $row1['doc_no'] . '">'.$row1['document_name'];
           echo '</option>';  
    }

echo '</select></td>';

echo'</tr></table></td>';


echo '<td class=conso>Status<select name="docstatus" id="docstatus" style="width:100px">';
echo '<option value=""></option>'; 
$sql1="select * from bio_docstatus";
$result1=DB_query($sql1,$db);
    while($row1=DB_fetch_array($result1))
    {
    //  echo "<option value=$row1[enqtypeid]>$row1[enquirytype]</option>";
        if ($row1['id']==$_POST['docstatus'])
           {
             echo '<option selected value="';
           } else 
           { 
               echo '<option value="'; 
           }
           echo $row1['id'] . '">'.$row1['status'];
           echo '</option>';  
    }
echo '</select></td>';
 
//echo"<td style=text-align:right; colspan=2;><input type='submit' name='filterbut' id='filterbut' value='search'></td>";                          
echo '</div>';
 
    echo '<td align=right><input type=submit name=filter value=Search></td>';

//echo"</tr>";
echo"</table>";

        
echo"</table>";
//}
echo"</form>"; 
echo"<br />";




    
//echo "<div style='height:320px; overflow:scroll;'>";   
if($_POST['regtype']!=3)   { 
echo"<table style='border:1px solid #F0F0F0;width:90%' ; >";
}else{
echo"<table border=1px>";    
}
echo"<thead>";
//edited for report heading
$title="<b>Order document report</b>";

if($_POST['createdfrm']!=NULL){
$title.=' from '.$_POST['createdfrm'];
}
if($_POST['createdto']!=NULL){
 $title.=' to '.$_POST['createdto'];   
}
if($_POST['regtype']==1){$title.=" of Consolidated ";}elseif($_POST['regtype']==2 || $_GET['regtype']==2){$title.=" of Detailed ";}
if($_POST['country']!=NULL || $_GET['country']!=NULL){
    if(isset($_POST['country'])){   $country=$_POST['country'];
    }else{                           $country=$_GET['country'];
    }
$sql="SELECT * FROM bio_country  where cid=".$country."";
$result=DB_query($sql,$db);
$myrow1=DB_fetch_array($result);
$title.=' of '.$myrow1['country'];
}
if($_POST['state']!=NULL || $_GET['state']!=NULL){
    if(isset($_POST['state'])){   $state=$_POST['state'];
    }else{                        $state=$_GET['state'];
    }
    $sql="SELECT * FROM bio_state where stateid=".$state."";
    $result=DB_query($sql,$db);
$myrow1=DB_fetch_array($result);
$title.=' , '.$myrow1['state'];
}
if($_POST['district']!=NULL){
 $sql="SELECT * FROM bio_district WHERE stateid=14 AND  did=".$_POST['district'];
$result=DB_query($sql,$db);
$myrow1=DB_fetch_array($result);
$title.=' , '.$myrow1['district'];
}
if($_POST['taluk']!=NULL){
      $sql="SELECT * FROM bio_taluk where id=".$_POST['taluk'];
      $result=DB_query($sql,$db);
      $myrow1=DB_fetch_array($result);
      $title.=' , '.$myrow1['taluk'];
      }
if($_POST['village']!=NULL){
      $sql="SELECT * FROM bio_village where id=".$_POST['village'];
      $result=DB_query($sql,$db);
      $myrow1=DB_fetch_array($result);
      $title.=' , '.$myrow1['village'];
      }
if($_POST['lsgType']==1){$title.=' , Co orporation';}elseif($_POST['lsgType']==2){$title.=' , Municipality';}elseif($_POST['lsgType']==3){$title.=' , Panchayat';}         
if($_POST['plant']!=NULL){    
      $title.=' , '.$_POST['plant'];
     }
if($_POST['plantstatus']!=NULL){
       $sql="SELECT * FROM bio_plantstatus where id=".$_POST['plantstatus'];
       $result=DB_query($sql,$db);
       $myrow1=DB_fetch_array($result);
       $title.=' , '.$myrow1['plantstatus'];
       }
                               
if($_POST['enq']!=NULL){
       $sql="SELECT * FROM bio_enquirytypes where enqtypeid=".$_POST['enq'];
       $result=DB_query($sql,$db);
       $myrow1=DB_fetch_array($result);
       $title.=' , '.$myrow1['enquirytype'];
       }
echo "<tr><td colspan='8'><font size='-1'>".$title."</font></td></tr>";
//edited for report heading
if($_POST['regtype']==1)   {   
    echo"<tr><th>No:of orders</th><th>Document Name</th><th>Recieved Documents</th><th>Pending Documents</th></tr>";     
}elseif($_POST['regtype']==2){
    echo"<tr><th>SL No</th>
             <th>DebtorNo</th>
             <th>Customer Name</th>
             <th>Contact No</th>
             <th>Client Since</th>
             <th class='dist'>District</th>
             <th class='enq'>Customer Type</th>
             <th>Created On</th></tr>"; 
             
}elseif($_POST['regtype']==3){
    echo"<tr><th>SL No</th><th>Customer Name</th><th>District</th><th>LSG</th>
              <th>Doc1</th><th>Doc2</th><th>Doc3</th><th>Doc4</th><th>Doc5</th><th>Doc6</th><th>Doc7</th><th>Doc8</th><th>Doc9</th><th>Doc10</th><th>Doc11</th><th>Doc12</th>";
                                                                  if($_POST['enq']==2 || $_POST['enq']==3)  {
                                                                  echo"<th>Doc13<th>Doc14</th><th>Doc15</th><th>Doc16</th>"; 
                                                                  }
                                                                  echo"<th>View</th>";                                                               
   echo"</tr>";    
}     
echo"</thead>";    

// $sql_old="SELECT DISTINCT bio_oldorderdoclist.orderno AS oldorders,0 AS neworders,
//                  debtorsmaster.debtorno,
//                  debtorsmaster.name,
//                  bio_district.district,
//                  debtorsmaster.clientsince,
//                  custbranch.phoneno,
//                  custbranch.faxno,
//                  bio_oldorders.createdon  
// 
//           FROM   bio_oldorderdoclist,bio_oldorders,debtorsmaster,custbranch,bio_district 
//           WHERE  bio_oldorders.orderno=bio_oldorderdoclist.orderno
//           AND    debtorsmaster.debtorno=bio_oldorders.debtorno 
//           AND    debtorsmaster.debtorno=custbranch.debtorno
//           AND    bio_district.cid=debtorsmaster.cid
//           AND    bio_district.stateid=debtorsmaster.stateid   
//           AND    bio_district.did=debtorsmaster.did";      
  
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
    , `debtorsmaster`.`LSG_type`
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
    WHERE bio_oldorderdoclist.status!=5";   
 if($_GET['plantid']!=''){
       
 $sql_old .=" AND bio_oldorders.currentstatus=".$_GET['plantid'];    
 }
           // AND    bio_oldorderdoclist.status=0
           if(isset($_POST['filter']) || ($_GET['regtype']==2))
           {                                
                
                if($_POST['createdfrm']!="" && $_POST['createdto']!="")
                 {
                     $sql_old .= " AND bio_oldorders.createdon BETWEEN '".FormatDateForSQL($_POST['createdfrm'])."' AND '".FormatDateForSQL($_POST['createdto'])."'"; 
                 }
                 if($_POST['name']!="")
                 {
                     $sql_old .= " AND debtorsmaster.name LIKE '".$_POST['name']."%'"; 
                 }
                 if($_POST['contno']!="")
                 {
                     $sql_old .= " AND custbranch.faxno LIKE '".$_POST['contno']."%'"; 
                 }
                 if($_POST['periodfrm']!="" && $_POST['periodto']!="")
                 {
                     $sql_old .= " AND debtorsmaster.clientsince BETWEEN '".FormatDateForSQL($_POST['periodfrm'])."' AND '".FormatDateForSQL($_POST['periodto'])."'"; 
                 }
//    if (isset($_POST['country']))    {
     if($_POST['country']!=0 || $_GET['country']!=0)   {
     if (isset($_POST['country'])){     
     $country=$_POST['country']; 
     }else{
     $country=$_GET['country'];    
     }
     $sql_old .=" AND debtorsmaster.cid=".$country;    
     }
//     }
                                                                                
//    if (isset($_POST['state']))    {
     if($_POST['state']!=0 || $_GET['state']!=0)   {
     if (isset($_POST['state'])){    
         $state=$_POST['state']; 
     }else{
         $state=$_GET['state'];
     }
     $sql_old .=" AND debtorsmaster.stateid=".$state;    
     }
//     }
       if (isset($_POST['district']) || isset($_GET['district']))    {
       if(isset($_POST['district']))   { 
          $district=$_POST['district'];
     }else{
          $district=$_GET['district'];
     }
     if($_POST['district']!=0 || $_GET['district']!=0)   {
     $sql_old .=" AND debtorsmaster.did=".$district;
      
     if ((isset($_POST['lsgType'])) || (isset($_GET['lsgType'])))    {
     if($_POST['lsgType']!=0 || $_GET['lsgType']!=0)   {
       if($_POST['lsgType']!=NULL || $_GET['lsgType']!=NULL)   {  
       if(isset($_POST['lsgType'])){
           $lsgtype=$_POST['lsgType'];
       }else{
           $lsgtype=$_GET['lsgType'];
       }  
         
     $sql_old .=" AND debtorsmaster.LSG_type=".$lsgtype;    
     
     if (isset($_POST['lsgName']) || (isset($_GET['lsgName'])))    {
     if(($_POST['lsgType']==1 OR $_POST['lsgType']==2)  || ($_GET['lsgType']==1 OR $_GET['lsgType']==2))  {
     if(isset($_POST['lsgName'])){
          $lsgname=$_POST['lsgName'];
       }else{
          $lsgname=$_GET['lsgName']; 
       }  
$sql_old .=" AND debtorsmaster.LSG_name=".$lsgname;    }
    
       elseif(($_POST['lsgType']==3)  || ($_GET['lsgType']==3)){
       if(isset($_POST['lsgName'])){
              $lsgname=$_POST['lsgName'];  
           }else{
              $lsgname=$_GET['lsgName']; 
       }  
       $sql_old .=" AND debtorsmaster.LSG_name=".$lsgname;    } 
              
       }
       
       if ((isset($_POST['gramaPanchayath'])) || (isset($_GET['gramaPanchayath'])))    {  
      if(($_POST['gramaPanchayath']!=0 OR $_POST['gramaPanchayath']!=NULL)  || ($_GET['gramaPanchayath']!=0 OR $_GET['gramaPanchayath']!=NULL))  {
      if(isset($_POST['gramaPanchayath'])) {
                  $panchayath=$_POST['gramaPanchayath'];
          }else{
                  $panchayath=$_GET['gramaPanchayath'];
          }
$sql_old .=" AND debtorsmaster.block_name=".$panchayath;    }       
     }
     }
     }
     }   
     }
     
    if (isset($_POST['taluk']))    {
     if($_POST['taluk']!=0 OR $_POST['taluk']!=NULL)   {
     $sql_old .=" AND debtorsmaster.taluk=".$_POST['taluk'];    }
     } 
     if (isset($_POST['village']))    {
     if($_POST['village']!='' OR $_POST['village']!=NULL)   {
$sql_old .="  AND debtorsmaster.village LIKE '%".$_POST['village']."%'";  }
     }
     
          
     }  
     
     if (isset($_POST['plant']))    {
          
     if($_POST['plant']!="")   {        
     $sql_old .=" AND bio_oldorders.plantid='".$_POST['plant']."'";    }
     }  
     
      if (isset($_POST['plantstatus']))    {
     if($_POST['plantstatus']!=0)   {
     $sql_old .=" AND bio_oldorders.currentstatus=".$_POST['plantstatus'];    }
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
                   
                   if ( $_POST['docname_frm']!=0)
                   {                                       
                       $sql_old .= " AND bio_oldorderdoclist.docno='".$_POST['docname_frm']."'";                   
                   }     
                   
                   if ( $_POST['docname_to']!=0)
                   {                                       
                       $sql_old .= " AND bio_oldorderdoclist.docno='".$_POST['docname_to']."'";                   
                   }     

                   if ($_POST['docstatus']!='' || $_POST['docstatus']!=0)
                   {        
                       $sql_old .= " AND bio_oldorderdoclist.status='".$_POST['docstatus']."'";
                   }    

           }
           
//$sql_old .=" order by debtorsmaster.clientsince asc ";

        //  echo $sql_old;

// $sql_so="SELECT DISTINCT  0 AS oldorders,salesorders.orderno AS neworders,
//                  debtorsmaster.debtorno,
//                  debtorsmaster.name,
//                  bio_district.district,
//                  debtorsmaster.clientsince,
//                  custbranch.phoneno,
//                  custbranch.faxno,
//                  salesorders.orddate  
// 
//           FROM   salesorders,salesorderdetails,bio_documentlist,debtorsmaster,custbranch,bio_district
//           WHERE  debtorsmaster.debtorno=salesorders.debtorno 
//           AND    salesorderdetails.orderno=salesorders.orderno        
//           AND    bio_documentlist.orderno=salesorders.orderno
//           AND    debtorsmaster.debtorno=custbranch.debtorno
//           AND    bio_district.cid=debtorsmaster.cid
//           AND    bio_district.stateid=debtorsmaster.stateid   
//           AND    bio_district.did=debtorsmaster.did";                        
           
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
    , `debtorsmaster`.`LSG_type`
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
    WHERE bio_documentlist.status!=5";    
 if($_GET['plantid']!=''){
 $sql_so .=" AND salesorders.currentstatus=".$_GET['plantid'];  
 }
           // AND    bio_oldorderdoclist.status=0
           if(isset($_POST['filter']) || ($_GET['regtype']==2))
           {           
                
                if($_POST['createdfrm']!="" && $_POST['createdto']!="")
                 {
                     $sql_so .= " AND salesorders.orddate BETWEEN '".FormatDateForSQL($_POST['createdfrm'])."' AND '".FormatDateForSQL($_POST['createdto'])."'"; 
                 }
                 if($_POST['name']!="")
                 {
                     $sql_so .= " AND debtorsmaster.name LIKE '".$_POST['name']."%'"; 
                 }
                 if($_POST['contno']!="")
                 {
                     $sql_so .= " AND custbranch.faxno LIKE '".$_POST['contno']."%'"; 
                 }
                 if($_POST['periodfrm']!="" && $_POST['periodto']!="")
                 {
                     $sql_so .= " AND debtorsmaster.clientsince BETWEEN '".FormatDateForSQL($_POST['periodfrm'])."' AND '".FormatDateForSQL($_POST['periodto'])."'"; 
                 }
//    if (isset($_POST['country']))    {
     if($_POST['country']!=0 || $_GET['country']!=0)   {
     if (isset($_POST['country'])){     
     $country=$_POST['country']; 
     }else{
     $country=$_GET['country'];    
     }
     $sql_so .=" AND debtorsmaster.cid=".$country;    }
//     }
                                                                                
//    if (isset($_POST['state']))    {
     if($_POST['state']!=0 || $_GET['state']!=0)   {
     if (isset($_POST['state'])){    
         $state=$_POST['state']; 
     }else{
         $state=$_GET['state'];
     }
     $sql_so .=" AND debtorsmaster.stateid=".$state;    }
//     }
       if (isset($_POST['district']) || isset($_GET['district']))    {
       if(isset($_POST['district']))   { 
          $district=$_POST['district'];
     }else{
          $district=$_GET['district'];
     }    
     if($_POST['district']!=0 || $_GET['district']!=0)   {
     $sql_so .=" AND debtorsmaster.did=".$district;
      
     if ((isset($_POST['lsgType'])) || (isset($_GET['lsgType'])))    {
     if($_POST['lsgType']!=0 || $_GET['lsgType']!=0)   {
       if($_POST['lsgType']!=NULL || $_GET['lsgType']!=NULL)   {
       if(isset($_POST['lsgType'])){
           $lsgtype=$_POST['lsgType'];
       }else{
           $lsgtype=$_GET['lsgType'];
       }  
         
     $sql_so .=" AND debtorsmaster.LSG_type=".$lsgtype;    
     
     if (isset($_POST['lsgName']) || isset($_GET['lsgName']))    {
     if(($_POST['lsgType']==1 OR $_POST['lsgType']==2) || ($_GET['lsgType']==1 OR $_GET['lsgType']==2))  {
       if(isset($_POST['lsgName'])){
          $lsgname=$_POST['lsgName'];
       }else{
          $lsgname=$_GET['lsgName']; 
       }  
     $sql_so .=" AND debtorsmaster.LSG_name=".$lsgname;    }
    
       elseif(($_POST['lsgType']==3) || ($_GET['lsgType']==3)) {
           if(isset($_POST['lsgName'])){
              $lsgname=$_POST['lsgName'];  
           }else{
              $lsgname=$_GET['lsgName']; 
       }  
       $sql_so .=" AND debtorsmaster.LSG_name=".$lsgname;    } 
              
       }
       
       if ((isset($_POST['gramaPanchayath'])) || (isset($_GET['gramaPanchayath'])))    {  
      if(($_POST['gramaPanchayath']!=0 OR $_POST['gramaPanchayath']!=NULL) || ($_GET['gramaPanchayath']!=0 OR $_GET['gramaPanchayath']!=NULL))  {
          if(isset($_POST['gramaPanchayath'])) {
                  $panchayath=$_POST['gramaPanchayath'];
          }else{
                  $panchayath=$_GET['gramaPanchayath'];
          }
$sql_so .=" AND debtorsmaster.block_name=".$panchayath;    }       
     }
     }
     }
     }   
     }
     
    if (isset($_POST['taluk']))    {
     if($_POST['taluk']!=0 OR $_POST['taluk']!=NULL)   {
     $sql_so .=" AND debtorsmaster.taluk=".$_POST['taluk'];    }
     } 
     if (isset($_POST['village']))    {
     if($_POST['village']!='' OR $_POST['village']!=NULL)   {
$sql_so .="  AND debtorsmaster.village LIKE '%".$_POST['village']."%'";  }
     }
     
          
     }  
     
     if (isset($_POST['plant']))    {
          
     if($_POST['plant']!="")   {        
     $sql_so .=" AND salesorderdetails.stkcode='".$_POST['plant']."'";    }
     }  
     
     if (isset($_POST['plantstatus']))    {
     if($_POST['plantstatus']!=0)   {
     $sql_so .=" AND salesorders.currentstatus=".$_POST['plantstatus'];    }
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
                   
                   if ( $_POST['docname_frm']!=0)
                   {                                       
                       $sql_so .= " AND bio_documentlist.docno='".$_POST['docname_frm']."'";                   
                   }     
                   
                   if ( $_POST['docname_to']!=0)
                   {                                       
                       $sql_so .= " AND bio_documentlist.docno='".$_POST['docname_to']."'";                   
                   }     

                   if ($_POST['docstatus']!='' || $_POST['docstatus']!=0)
                   {        
                       $sql_so .= " AND bio_documentlist.status='".$_POST['docstatus']."'";
                   }    

           }  
  //        $sql_so .=" order by debtorsmaster.clientsince asc ";     
          
      $sql=   $sql_old." UNION ".$sql_so;      


$result_old=DB_query($sql,$db);


$order_new=array(); 
$order_old=array();  

$k=0;
$slno=0;

//echo'<tbody style="height: 320px; display: block; overflow: auto; ">';

while($row_old=DB_fetch_array($result_old))
{

       
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
       
      $dat= ConvertSQLDate ($row_old['clientsince']);
      $ph=$row_old['faxno'];
      $orddate= ConvertSQLDate ($row_old['orderdate']);
     if($row_old['faxno']=='')
     {
         $ph= $row_old['phoneno'];
     }
     
      $debtorno=$row_old['debtorno'];  
      $first_letter=$debtorno[0];
      if($first_letter=='D'){
          $enq=1;
          $sql3="SELECT enquirytype FROM bio_enquirytypes WHERE enqtypeid=1";
      }elseif($first_letter=='C'){
          $enq=2;
          $sql3="SELECT enquirytype FROM bio_enquirytypes WHERE enqtypeid=2";
      }elseif($first_letter=='L'){
          $enq=3;
          $sql3="SELECT enquirytype FROM bio_enquirytypes WHERE enqtypeid=3";
      }
      $result3=DB_query($sql3,$db);
      $row3=DB_fetch_array($result3);  
      
      
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
      }
   //   echo" $ordtab-".$myrow_ordtable[0]; 
/*     
      if($_POST['lsgType']==1)   {
          $sql_lsgname="SELECT corporation AS lsgName FROM bio_corporation WHERE district=".$_POST['lsgName'].""; 
      }elseif($_POST['lsgType']==2)  {
          $sql_lsgname="SELECT municipality AS lsgName FROM bio_municipality WHERE id=".$_POST['lsgName']."";
      }elseif($_POST['lsgType']==3)  {
          $sql_lsgname="SELECT block AS lsgName FROM bio_block WHERE id=".$_POST['lsgName']."";
      }
      $result_lsgname=DB_query($sql_lsgname,$db); 
      $myrow_lsgname=DB_fetch_array($result_lsgname);               
                                                    */
$slno++;   
if($_POST['regtype']==3 && isset($_POST['enq'])) {
                
//          if ($k==1)
//          {
//            echo '<tr class="EvenTableRows">';
//            $k=0;
//          }else 
//          {
//            echo '<tr class="OddTableRows">';
//            $k=1;     
//          }
                //<td><a href id='".$row_old['debtorno']."' onclick='viewcustomer(this.id);return false;'>".$row_old['debtorno']."</a></td>         
                //<td>".$ph."</td> 
                //<td>".$dat."</td>   
                //<td>".$row3['enquirytype']."</td>
               //<td>".$row_old['createdon']."</td>   
               
           
      
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
    
                                
      echo"    <tr>
               <td>".$slno."</td> 
               
               <td>".$row_old['name']."</td>

               <td>".$row_old['district']."</td>     
               <td>".$LSG_name."</td>
               ";      
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
               echo"
               <td><a href id='".$ordtab."-".$myrow_ordtable[0]."-".$enq."' onclick='viewdocuments(this.id);return false;'>View</a></td>
               </tr>";
             

}  
elseif($_POST['regtype']==2 || $_POST['regtype']=="")  
{   
       $sqld="select debtorno from bio_fileno where debtorno='".$row_old['debtorno']."'";
          $sqld_rt=DB_query($sqld,$db);
          $mro=DB_fetch_array($sqld_rt);   
             if($mro['debtorno']==$row_old['debtorno'])
          {
            echo '<tr bgcolor="#F2BDBD">';
            $k=0;
          }
          else
          {
          if ($k==1)
          {
            echo '<tr class="EvenTableRows">';
            $k=0;
		  }
          else 
          {
		  echo '<tr class="OddTableRows">';
            $k=1;
		  }
          }
                                              
      echo"
               <td>".$slno."</td> 
               <td><a href id='".$row_old['debtorno']."' onclick='viewcustomer(this.id);return false;'>".$row_old['debtorno']."</a></td>
               <td>".$row_old['name']."</td>
               <td>".$ph."</td> 
               <td>".$dat."</td> 
               <td class='dist'>".$row_old['district']."</td> 
               <td class='enq'>".$row3['enquirytype']."</td>
               <td>".$orddate."</td>
               <td><a href id='".$ordtab."-".$myrow_ordtable[0]."-".$enq."' onclick='viewdocuments(this.id);return false;'>View</a></td>
               </tr>
          ";
          
 }  
  
 
}

$oldorders_arr=join(",", $order_old);
$neworders_arr=join(",", $order_new);
$len_old=count($order_old);
$len_new=count($order_new);  
$len=$len_old+$len_new;
       //--------------------------------------------------For consolidated-----------------------------------------------------//     

 if($_POST['regtype']==1) {
    

echo"<tr><td>$len</td></tr>";



$sql="SELECT docno,sum(c1) as count_not_recd,sum(c2) as count_recd FROM (

    SELECT docno,count(*) as c1, 0 as c2                                            
    FROM bio_oldorderdoclist
    WHERE STATUS =0";
if($oldorders_arr!=''){
$sql.=" AND bio_oldorderdoclist.orderno IN ($oldorders_arr)";  
}
$sql.=" AND docno IN (SELECT doc_no
    FROM bio_document_master
    WHERE enqtypeid ='".$_POST['enq']."')
    group by docno

    UNION ALL

    SELECT docno,count( * ) as c1 , 0 as c2
    FROM  bio_documentlist
    WHERE STATUS =0";
if($neworders_arr!=''){
$sql.=" AND bio_documentlist.orderno IN ($neworders_arr)";  
}    
    $sql.=" AND docno IN (SELECT doc_no
    FROM bio_document_master
    WHERE enqtypeid ='".$_POST['enq']."')
    group by docno

    UNION ALL

    SELECT docno,0 as c1, count( * ) as c2
    FROM bio_oldorderdoclist
    WHERE STATUS >0";
if($oldorders_arr!=''){
$sql.=" AND bio_oldorderdoclist.orderno IN ($oldorders_arr)";  
}   
$sql.=" AND docno IN (SELECT doc_no
    FROM bio_document_master
    WHERE enqtypeid ='".$_POST['enq']."')
    group by docno

    UNION ALL

    SELECT docno,0 as c1, count( * ) as c2
    FROM  bio_documentlist
    WHERE STATUS >0";
if($neworders_arr!=''){
$sql.=" AND bio_documentlist.orderno IN ($neworders_arr)";  
}       
$sql.=" AND docno IN (SELECT doc_no
    FROM bio_document_master
    WHERE enqtypeid ='".$_POST['enq']."')
    group by docno
    ) t1
    WHERE docno IN (SELECT doc_no
    FROM bio_document_master
    WHERE enqtypeid ='".$_POST['enq']."')
    group by docno";
// echo "<br /><br />".$sql;  
 

  $result=DB_query($sql,$db); 



  while($myrow=DB_fetch_array($result))  
  {
      $sql_docname="SELECT document_name FROM bio_document_master WHERE doc_no='$myrow[0]'";
      $result_docname=DB_query($sql_docname,$db);
      $myrow_docname=DB_fetch_array($result_docname);
      
            if ($k==1)
              {
                echo '<tr class="EvenTableRows">';
                $k=0;
              }else 
              {
                echo '<tr class="OddTableRows">';
                $k=1;     
              }
      
        echo "<td></td>
             <td>".$myrow_docname['document_name']."</td><td>".$myrow[count_recd]."</td><td>".$myrow[count_not_recd]."</td>
             </tr>";  
   
             
  }          


}        

echo"</div>";
//echo"</tbody>";
echo"</table>";

echo"</fieldset>";
    
    
    
?>


<script type="text/javascript">  

var dist=document.getElementById('district').value;
var enq=document.getElementById('enq').value;     
if(dist!=0)
{
    $(".dist").hide(); 
} 
if(enq!=0)
{
    $(".enq").hide(); 
}

        

function viewdocuments(str)
{      
//return false;                        // alert(str);
    var split = str.split('-');  
    var split1=split[0];             
    var split2=split[1]; 
    var split3=split[2];
    
    if(split1==1)
    {      
       controlWindow=window.open("bio_OLDdocumentmanagement.php?orderno="+split2+"&enq="+split3,"docdetails","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=1200,height=600");     
   // return false;
    }
    else if(split1==2)
    {     
       controlWindow=window.open("bio_documentmanagement.php?orderno="+split2,"plantdetails","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=1200,height=600") 
  //  return false;
    }            
}
 function viewcustomer(str4)
 {
     controlWindow=window.open("Customers.php?DebtorNo="+str4,"customer","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=1200,height=600");
 }

function consolidated(str)
{
    
    if(str==1) {
    $(".conso").hide();  
    }else{
    $(".conso").show();     
    }    
}


function showdocs(str1){   

//alert(str1);       



if (str1=="")
  {
  document.getElementById("showdocument").innerHTML="";
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
if(document.getElementById('regtype').value!=1)
{
         document.getElementById("showdocument").innerHTML=xmlhttp.responseText;      }
    }
  } 
xmlhttp.open("GET","bio_docCustSelection.php?enqid=" + str1,true);
xmlhttp.send(); 
} 




function showstate(str){
   // alert(str); 

if (str=="")
  {
  document.getElementById("showstate").innerHTML="";
  return;
  }
//show_progressbar('showstate');

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
    {                 //  alert(str); 
    document.getElementById("showstate").innerHTML=xmlhttp.responseText;

    }
  }
xmlhttp.open("GET","bio_lsgFilter.php?country=" + str,true);
xmlhttp.send();
}



function showdistrict(str){       
//    alert(str);
str1=document.getElementById("country").value;
//alert(str1);
if (str=="")
  {
  document.getElementById("showdistrict").innerHTML="";
  return;
  }
//show_progressbar('showdistrict');
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
    document.getElementById("showdistrict").innerHTML=xmlhttp.responseText;

    }
  }
xmlhttp.open("GET","bio_lsgFilter.php?state=" + str + "&country1=" + str1,true);
xmlhttp.send();

}


function showtaluk(str){                                  
     str1=document.getElementById("country").value;       
     str2=document.getElementById('state').value;         
//     str3=document.getElementById('district').value;      
 if (str=="")
  {           
   alert("Please select the district");   
  document.getElementById('district').focus(); 
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
     document.getElementById("showtaluk").innerHTML=xmlhttp.responseText; 
       
    }
  } 
xmlhttp.open("GET","bio_lsgFilter.php?taluk=" + str +"&country1="+ str1 +"&state1="+ str2  ,true);
xmlhttp.send(); 
}



 function showblock(str){   
     str1=document.getElementById("country").value;
     str2=document.getElementById('state').value;
     str3=document.getElementById('district').value;
     if(str==1 && (str3==1 || str3==3 || str3==4 || str3==5 || str3==7 || str3==9 || str3==10 || str3==10 || str3==14)){
         alert("No Corporation for this district");
         document.getElementById("block").innerHTML="";
         return;
     }
//alert(str1);   alert(str2);       alert(str3);
 if (str3=="")
  {           
   alert("Please select the district");   
  document.getElementById('district').focus(); 
  return;
  }

if (str=="")
  {
  document.getElementById("block").innerHTML="";
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
     document.getElementById("block").innerHTML=xmlhttp.responseText; 
       
    }
  } 
xmlhttp.open("GET","bio_lsgFilter.php?block=" + str +"&country1="+ str1 +"&state1="+ str2 +"&district="+ str3,true);
xmlhttp.send(); 
} 



   function showVillage(str){   
    str1=document.getElementById("country").value;
     str2=document.getElementById('state').value;
     str3=document.getElementById('district').value;
      //alert(str);  
 if (str=="")
  {  
      alert("Please select the district");   
  document.getElementById('district').focus(); 
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
     document.getElementById("showvillage").innerHTML=xmlhttp.responseText; 
       
    }
  } 
xmlhttp.open("GET","bio_lsgFilter.php?village=" + str + "&country1="+ str1 +"&state1="+ str2 +"&district="+ str3,true);
xmlhttp.send(); 
}






 function showgramapanchayath(str){   
    str1=document.getElementById("country").value;
     str2=document.getElementById('state').value;
     str3=document.getElementById('district').value;
     // alert(str2);  
 if (str=="")
  {  
      alert("Please select the district");   
  document.getElementById('district').focus(); 
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
     document.getElementById("showpanchayath").innerHTML=xmlhttp.responseText; 
       
    }
  } 
xmlhttp.open("GET","bio_lsgFilter.php?grama=" + str + "&country1="+ str1 +"&state2="+ str2 +"&district="+ str3,true);
xmlhttp.send(); 
}




</script>
