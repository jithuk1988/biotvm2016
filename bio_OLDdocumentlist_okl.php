<?php
    $PageSecurity = 80;
include('includes/session.inc');
$title = _('Document Collection');  
include('includes/header.inc');

echo '<center><font style="color: #333;
    background:#fff;
    font-weight:bold;
    letter-spacing:0.10em;
    font-size:16px;
    font-family:Georgia;
    text-shadow: 1px 1px 1px #666;">Old Documents Management</font></center>';
    
    
  
    if( $_POST['submit'] )
    {                                 
                                     
        for ( $i=1;$i<=$_POST['no'];$i++ ) 
        {                                          
               if($_POST['status'.$i]=='on')
               {
                   $_POST['date'.$i];                                       
                   $_POST['autono'.$i];
                   $_POST['doc'.$i];            
                   
                   $_POST['docno1'.$i]; 
                   

                   
                   $sql2="SELECT status FROM bio_oldorderdoclist WHERE docno='".$_POST['doc'.$i]."'
                                                                 
                                                                 AND   orderno='".$_POST['orderno']."'";
                   $result2=DB_query($sql2,$db);
                   $row2=DB_fetch_array($result2);
                   $status=$row2['status'];
                   

                   if($status==0) 
                   {

                           $sql_update="UPDATE bio_oldorderdoclist SET letterno='".$_POST['autono'.$i]."',
                                                                    status=1,
                                                                    receivedDate='".FormatDateForSQL($_POST['date'.$i])."',
                                                                    receivedBy='".$_SESSION['UserID']."' 
                                                              WHERE docno='".$_POST['doc'.$i]."'
                                                              
                                                              AND   orderno='".$_POST['orderno']."'";
                      
                   }elseif( $_POST['docno1'.$i]!=""){
                       
                           $sql_update="UPDATE bio_oldorderdoclist SET letterno='".$_POST['autono'.$i]."',
                                                                    receivedDate='".FormatDateForSQL($_POST['date'.$i])."',
                                                                    receivedBy='".$_SESSION['UserID']."' 
                                                              WHERE docno='".$_POST['doc'.$i]."'
                                                              
                                                              AND   orderno='".$_POST['orderno']."'";
                       
                   }
                   DB_query($sql_update,$db);                                                 
               }              
          }       
      }
      
      
      

      
    
    echo "<form action=" . $_SERVER['PHP_SELF'] . "?" . SID ." method=POST>";  

               
 if($_GET['orderno']!='')
 {
     $enqid=$_GET['enq'];
     

                    
//---------------------------------------------------------------------//     
 echo"<table style='border:1px solid #F0F0F0;width:100%'>";
 echo"<tr><td>";
 
                 
 
//-------------- Inward documents-----------------------
    echo"<fieldset style='width:95%;'>";
    echo"<legend><h3>Inward Documents</h3></legend>";
    echo "<table class='selection' style='width:98%;'>";
    
    echo"<tr><th>Slno</td><th>Document Name</td><th>Select</td><th>Received Date</td><th>Document No</td></tr>";   
    
                        $sql_inwards="SELECT bio_oldorderdoclist.orderno,
                                             bio_oldorderdoclist.docno,
                                             bio_oldorderdoclist.status,
                                             bio_oldorderdoclist.receivedDate,
                                             bio_oldorders.debtorno, 
                                             bio_document_master.document_name 
                                      FROM   bio_oldorderdoclist,bio_document_master,bio_oldorders 
                                      WHERE  bio_oldorderdoclist.orderno =".$_GET['orderno']." 
                                      AND    bio_oldorderdoclist.docno=bio_document_master.doc_no
                                      AND    bio_oldorderdoclist.orderno=bio_oldorders.orderno 
                                      AND    bio_document_master.document_type=1 
                                      AND    bio_document_master.enqtypeid=$enqid"; 
    

                        $result_inwards=DB_query($sql_inwards,$db);              
                        $i=0;
                        while($row_inwards=DB_fetch_array($result_inwards))
                        {
                        $i++;   
                        
                        if($row_inwards['receivedDate'] != NULL ){
                           $datein=convertSQLDate($row_inwards['receivedDate']); 
                        }else{                          
                              $datein="";
                        }  
                        
                        $debtorno=$row_inwards['debtorno'];
                        $autono=$row_inwards['debtorno'].'/'.date('Y').'/'.$row_inwards['docno'];
                        
                                                                                             
                             echo"<tr id='editdocumentin".$i."' style='background:#A8A4DB'>
                                                            <td>$i</td>
                                                            <td>".$row_inwards['document_name']."</td>"; 
                                           if($row_inwards['status']>=1)  {
                                                        echo"<td><input type=checkbox checked id='status".$i."' name='status".$i."'>
                                                                 <input type=hidden id='doc".$i."' name='doc".$i."' value='".$row_inwards['docno']."'></td>  
                                                             <td><input type=text readonly id='date".$i."' name='date".$i."' value='$datein' ></td>
                                                             <td><input type='text' readonly id='autono".$i."' name='autono".$i."' value='$autono'></td>";
                                                        if($row_inwards['status']==2){
                                                                 echo"<td>Verified</td>";
                                                        }else{
                                                                 echo"<td width='35px'><a style='cursor:pointer;' id='$i' onclick='editdocin(this.id);'>" . _('Edit ') . "</a></td>";
                                                        }
                                           }else{
                                                        echo"<td><input type=checkbox id='status".$i."' name='status".$i."'  onchange=idproof('$i'); >
                                                                 <input type=hidden id='doc".$i."' name='doc".$i."' value='".$row_inwards['docno']."'></td> 
                                                             <td><input type=text id='date".$i."' class=date alt=".$_SESSION['DefaultDateFormat']." name='date".$i."' value='$datein' ></td>     
                                                             <td><input type='text' readonly id='autono".$i."' name='autono".$i."' value='$autono'></td>";                                                                                                                                                                         
                                           }          
                             echo"</tr>";
                                
                             
                        }
                        
                        $isum=$i;

    echo "</table>";

    echo "</fieldset>";
//------------------------------------------------------  

echo"</td><td>";

//------------- Outward documents-----------------------
    echo"<fieldset style='width:95%;height:255px'>";
    echo"<legend><h3>Outward Documents</h3></legend>";
    echo "<table class='selection' style='width:98%'>";
    
    echo"<tr><th>Slno</td><th>Document Name</td><th>Select</td><th>Issued Date</td><th>Document No</td></tr>";  
    
    
                       $sql_outwards="SELECT bio_oldorderdoclist.orderno,
                                             bio_oldorderdoclist.docno,
                                             bio_oldorderdoclist.status,
                                             bio_oldorderdoclist.letterno,
                                             bio_oldorderdoclist.receivedDate,   
                                             bio_document_master.document_name 
                                      FROM   bio_oldorderdoclist,bio_document_master 
                                      WHERE  bio_oldorderdoclist.orderno =".$_GET['orderno']." 
                                      AND    bio_oldorderdoclist.docno=bio_document_master.doc_no 
                                      AND    bio_document_master.document_type=2 
                                      AND    bio_document_master.enqtypeid=$enqid";
                            $result_outwards=DB_query($sql_outwards,$db);                        
                            $j=$isum;
                            while($row_outwards=DB_fetch_array($result_outwards))
                            {
                                $j++; 
                                if($row_outwards['receivedDate'] != NULL ){
                                $dateout=convertSQLDate($row_outwards['receivedDate']); 
                                }else{                          
                                $dateout="";
                                } 
                                
                                
                                 echo"<tr id='editdocumentout".$j."' style='background:#A8A4DB'><td>$j</td>
                                                            <td>".$row_outwards['document_name']."</td>"; 
                                                     if($row_outwards['status']>=1)  {
                                                             echo"<td><input type=checkbox checked id='status".$j."' name='status".$j."'>
                                                                      <input type=hidden id='doc".$j."' name='doc".$j."' value='".$row_outwards['docno']."'></td>   
                                                                  <td><input type=text readonly id='date".$j."'  name='date".$j."' value='$dateout' ></td>
                                                                  <td><input type='text' readonly id='autono".$j."' name='autono".$j."' value='".$row_outwards['letterno']."'></td>"; 
                                                             if($row_outwards['status']==2){
                                                                    echo"<td>Verified</td>";
                                                             }else{     
                                                                    echo"<td width='35px'><a style='cursor:pointer;' id='$j' onclick='editdocout(this.id);'>" . _('Edit ') . "</a></td>";
                                                             }
                                                     }else{
                                                              echo"<td><input type=checkbox id='status".$j."' name='status".$j."'>
                                                                       <input type=hidden id='doc".$j."' name='doc".$j."' value='".$row_outwards['docno']."'></td>  
                                                                   <td><input type=text id='date".$j."' class=date alt=".$_SESSION['DefaultDateFormat']." name='date".$j."' value='$dateout'></td>
                                                                   <td><input type='text' id='autono".$j."' name='autono".$j."' value='".$row_outwards['letterno']."'></td>";     
                                                     }
                                 echo"</tr>";
                                  
                                
                            }
                            $no=$j;
                            
    
    echo "</table>";

    echo "</fieldset>";
//------------------------------------------------------   
                                                                          

echo'<input type=hidden name=orderno id=orderno value='.$_GET['orderno'].'>';
echo'<input type=hidden name=debtorno id=debtorno value='.$debtorno.'>';
echo'<input type=hidden name=custid id=custid value='.$row1['cust_id'].'>';
echo'<input type=hidden name=no id=no value='.$no.'>';


echo'<tr><td colspan=2><p><div class="centre">
         <input type=submit name=submit value="' . _('Received/Issued') . '" onclick="if(validation()==1) return false;">
         <input type="Button" class=button_details_show name=details VALUE="' . _('Print') . '">';  
echo"</td></tr>";
echo"</table>";   

//----------------------------------------------------------------------//

echo''; 
 
 echo"</fieldset>";
 
 
 }  
 
 
 
    echo"<fieldset style='width:90%;'>";
    echo"<legend>Old Customers</legend>";
    echo"<table style='border:1px solid #F0F0F0;width:80%'>"; 

echo"<tr><td>CreatedON From</td><td>CreatedON To</td><td>Name</td><td>Contact No</td><td>Client From</td><td>Client To</td><td>Register Type</td></tr>";
echo"<tr>"; 
echo "<td><input type=text id='createdfrm' class=date alt=".$_SESSION['DefaultDateFormat']." name='createdfrm' value='$_POST[createdfrm]' onChange='return isDate(this, this.value, ".$_SESSION['DefaultDateFormat'].")'></td>";
    echo "<td><input type=text id='createdto' class=date alt=".$_SESSION['DefaultDateFormat']." name='createdto' value='$_POST[createdto]' onChange='return isDate(this, this.value, ".$_SESSION['DefaultDateFormat'].")'></td>";

    echo "<td><input style=width:150px type='text' name='name' id='name' style='width:100px'></td>";  
    echo "<td><input style=width:150px type='text' name='contno' id='contno' style='width:100px'></td>"; 
   // echo "<td><input style=width:150px type='text' name='district' id='district' style='width:100px'></td>";

    echo "<td><input type=text id='periodfrm' class=date alt=".$_SESSION['DefaultDateFormat']." name='periodfrm' value='$_POST[periodfrm]' onChange='return isDate(this, this.value, ".$_SESSION['DefaultDateFormat'].")'></td>";
    echo "<td><input type=text id='periodto' class=date alt=".$_SESSION['DefaultDateFormat']." name='periodto' value='$_POST[periodto]' onChange='return isDate(this, this.value, ".$_SESSION['DefaultDateFormat'].")'></td>";
     if((isset($_POST['regtype'])) AND ($_POST['regtype']!=0))
 {
 echo'<td><select name=regtype>';
     if($_POST['regtype']==1){
        echo'<option value=1>Consolidated</option>';
        echo'<option value=2>Detailed</option>'; 
     }elseif($_POST['regtype']==2){  
        echo'<option value=2>Detailed</option>';
        echo'<option value=1>Consolidated</option>';  
     }
 echo'</select></td>';     
 }else{     
 echo'<td><select name=regtype>
              <option value=2>Detailed</option>
              <option value=1>Consolidated</option>
      </select></td>';
 }  
    echo "</tr>";
    
    //     echo"<tr><td style='width:50%'>Plant Model</td><td>";

//    echo '<select name="plant model" id="Plant Model" tabindex=9 onchange="showstate(this.value)" style="width:190px">';
   
    //
//   $sql1="SELECT distinct stockmaster.description,stockmaster.stockid from stockmaster,stockcategory,bio_maincat_subcat where stockmaster.categoryid in ('P-LSGD','PDO','OP') order by stockmaster.longdescription asc";
//      $result1=DB_query($sql1, $db);
//      $desp=$myrow1['longdescription'];    
//   echo '<tr><td>Plant&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'; 
//   echo '<select name="plant" id="plant" style="width:130px" onchange="showdescription()">';
//    $f=0;
//    
//    while($myrow1=DB_fetch_array($result1))
//    { 
//    if ($myrow1['stockid']==$_POST['plant']) 
//    {
//    echo '<option selected value="';
//    
//    } else {
//        if ($f==0) 
//        {
//        echo '<option value="0">--SELECT--</option>';
//        }
//        echo '<option value="';
//        $f++;
//    }
//    echo $myrow1['stockid'] . '">'.$myrow1['description'];             
//    echo '</option>';
//    }
//  echo '</select></td></tr>'; 
//   
   
   
   
   
   
   
   
   
//    
//    echo"<td>Country<select name='country' id='country' onchange='showstate(this.value)' style='width:100px'>";
//$sql="SELECT * FROM bio_country ORDER BY cid";
//$result=DB_query($sql,$db);
// $f=0;
//  while($myrow1=DB_fetch_array($result))
//  {  
//  if ($myrow1['cid']==1)  
//    {         //echo $myrow1['cid'];     
//    echo '<option selected value="';
//    } else 
//    {
//    if ($f==0) 
//        {
//        echo '<option>';
//        }
//        echo '<option value="';
//    }
//    echo $myrow1['cid'] . '">'.$myrow1['country'];
//    echo '</option>';
//    $f++;
//   }    
//  echo '</select></td>';
//  echo '<td id="showstate">State<select name="State" id="state" onchange="showdistrict(this.value)" style="width:100px">';
//  $sql="SELECT * FROM bio_state ORDER BY stateid";
//  $result=DB_query($sql,$db);
//  $f=0;
//  while($myrow1=DB_fetch_array($result))
//  {
//  if ($myrow1['stateid']==14)
//    {
//    echo '<option selected value="';
//    } else
//    {
//    if ($f==0)
//        {
//        echo '<option>';
//        }
//        echo '<option value="';
//    }
//    echo $myrow1['stateid'] . '">'.$myrow1['state'];
//    echo '</option>';
//    $f++;
//   }
//  echo '</select>';
//  echo'</td>'; 
// echo '<td id="showdistrict">District<select name="District" id="Districts" style="width:100px"   onchange="showtaluk(this.value)">'; 
//  $sql="SELECT * FROM bio_district WHERE stateid=14 ORDER BY did";
//    $result=DB_query($sql,$db);
//    $f=0;
//  while($myrow1=DB_fetch_array($result))
//  {
//  if ($myrow1['did']==$_POST['District'])
//    {
//    echo '<option selected value="';
//    } else
//    {
//    if ($f==0)
//        {
//        echo '<option>';
//        }
//        echo '<option value="';
//    }
//    echo $myrow1['did'] . '">'.$myrow1['district'];
//    echo '</option>';
//    $f++;
//   } 
//  echo '</select>';
//  echo'</td>';
//    
//echo '<td><select name="Taluk" id="taluk" style="width:100px">';   
//  
//    $sql="SELECT * FROM bio_taluk WHERE bio_taluk.country=$nationality AND bio_taluk.state=$state AND bio_taluk.district=$district";
//      $result=DB_query($sql,$db);

//      $f=0;
//      while($myrow1=DB_fetch_array($result))
//      {
//      if ($myrow1['id']==$_POST['taluk'])
//      {
//      echo '<option selected value="';
//      } else
//      {
//      if ($f==0)
//        {
//        echo '<option>';
//        }
//        echo '<option value="';
//        }
//      echo $myrow1['id'] . '">'.$myrow1['taluk'];
//      echo '</option>';
//      $f++;
//      }
//      echo '</select>';
//      echo'</td>';        
//       echo"</tr>";    
//      echo '<td id=showtaluk>Taluk<select name="Taluk" id="taluk" style="width:100px" tabindex=11 onchange="showVillage(this.value)">';
//      $sql_taluk="SELECT * FROM bio_taluk ORDER BY bio_taluk.taluk ASC";
//      $result_taluk=DB_query($sql_taluk,$db);
//      $f=0;
//      while($myrow7=DB_fetch_array($result_taluk))
//      {
//      if ($myrow7['id']==$_POST['taluk'])
//      {
//      echo '<option selected value="';
//      } else
//      {
//      if ($f==0)
//        {
//        echo '<option>';
//        }
//        echo '<option value="';
//        }
//      echo $myrow7['id'] . '">'.$myrow7['taluk'];
//      echo '</option>';
//      $f++;
//      }
//      echo '</select>';
//      echo'</td>';     
//echo"<td id=showvillage>Village<select name='Village' id='village' style='width:100px'>";      
//   $sql_taluk="SELECT * FROM bio_village ORDER BY bio_village.village ASC";
//      $result_taluk=DB_query($sql_taluk,$db);
//      $f=0;
//      while($myrow7=DB_fetch_array($result_taluk))
//      {
//      if ($myrow7['id']==$_POST['village'])
//      {
//      echo '<option selected value="';
//      } else
//      {
//      if ($f==0)
//        {
//        echo '<option>';
//        }
//        echo '<option value="';
//        }
//      echo $myrow7['id'] . '">'.$myrow7['village'];
//      echo '</option>';
//      $f++;
//      }
//      echo '</select>';
//      echo'</td>';
//      echo '<td>LSG Type<select name="lsgType" id="lsgType" style="width:100px" onchange="showblock(this.value)">';
//    echo '<option value=0></option>'; 
//    echo '<option value=1>Corporation</option>';
//    echo '<option value=2>Muncipality</option>';
//    echo '<option value=3>Panchayat</option>';           
//    echo '</select></td>'; 
//  
//        echo '<td align=left colspan=2>';
//        echo'<div style="align:right" id=block>';             
//        echo'</div>';
//        echo'</td>';
//      
//        echo'<td><div id=showpanchayath></div></td>';
//    
 
//    
//echo"</tr>"; 

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
  echo '<td id="showstate">State<select name="State" id="state" onchange="showdistrict(this.value)" style="width:130px">';
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
 echo '<td id="showdistrict">District<select name="District" id="Districts" style="width:130px"   onchange="showtaluk(this.value)">'; 
  $sql="SELECT * FROM bio_district WHERE stateid=14 ORDER BY did";
    $result=DB_query($sql,$db);
    $f=0;
  while($myrow1=DB_fetch_array($result))
  {
  if ($myrow1['did']==$_POST['District'])
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
    
//echo '<td><select name="Taluk" id="taluk" style="width:100px">';   
//  
//    $sql="SELECT * FROM bio_taluk WHERE bio_taluk.country=$nationality AND bio_taluk.state=$state AND bio_taluk.district=$district";
//      $result=DB_query($sql,$db);

//      $f=0;
//      while($myrow1=DB_fetch_array($result))
//      {
//      if ($myrow1['id']==$_POST['taluk'])
//      {
//      echo '<option selected value="';
//      } else
//      {
//      if ($f==0)
//        {
//        echo '<option>';
//        }
//        echo '<option value="';
//        }
//      echo $myrow1['id'] . '">'.$myrow1['taluk'];
//      echo '</option>';
//      $f++;
//      }
//      echo '</select>';
//      echo'</td>';        
//       echo"</tr>";    
      echo '<td id=showtaluk>Taluk<select name="Taluk" id="taluk" style="width:130px" tabindex=11 onchange="showVillage(this.value)">';
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
           
echo"<td id=showvillage>Village<select name='Village' id='village' style='width:130px'>";      
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


   $sql1="SELECT distinct stockmaster.description,stockmaster.stockid from stockmaster,stockcategory,bio_maincat_subcat where stockmaster.categoryid in ('P-LSGD','PDO','OP') order by stockmaster.longdescription asc";
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

  echo"<td id=showPlantstatus>Plant Status<select name='Plantstatus' id='Plantstatus' style='width:130px'>";      
  
  $sql="SELECT * FROM bio_plantstatus ORDER BY id";
$result=DB_query($sql,$db);
//echo"<option value=0></option>";
 $f=0;
  while($myrow1=DB_fetch_array($result))
  {  
  if ($myrow1['id']==$_POST['Plantstatus'])  
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
    echo $myrow1['id'] . '">'.$myrow1['Plantstatus'];
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

 echo'<div id=conso>';   
    echo'<td>From Beneficiary <select name="docname_frm" id="docname_frm" style="width:150px" >';
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

    echo'<td>To Beneficiary <select name="docname_to" id="docname_to" style="width:150px" >';
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


echo '<td>Status<select name="docstatus" id="docstatus" style="width:100px">';
echo '<option value=0></option>'; 
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
 
    echo '<td><input type=submit name=filter value=Search></td>';

//echo"</tr>";
echo"</table>";

  
  
  
  
       
echo"</table>";
echo"<br />";


{
    echo '<script type="JavaScript">$("#conso").hide();</script>';  
}

    
    echo "<div style='height:320px; overflow:scroll;'>";
    echo"<table style='border:1px solid #F0F0F0;width:90%'>";
if($_POST['regtype']!=1)   {
echo"<tr><th>SL No</th><th>Order No</th><th>Customer Name</th><th>Contact No</th><th>Client Since</th><th>District</th><th>Customer Type</th><th>Created On</th></tr>";    
}else{
echo"<tr><th>No:of orders</th><th>Document Name</th><th>Recieved Documents</th><th>Pending Documents</th></tr>";     
}    
    

 $sql_old="SELECT DISTINCT bio_oldorderdoclist.orderno,
                  debtorsmaster.debtorno,
                  debtorsmaster.name,
                  bio_district.district,
                  debtorsmaster.clientsince,
                  custbranch.phoneno,
                  custbranch.faxno,
                  bio_oldorders.createdon  
 
           FROM   bio_oldorderdoclist,bio_oldorders,debtorsmaster,custbranch,bio_district 
           WHERE  bio_oldorders.orderno=bio_oldorderdoclist.orderno
           AND    debtorsmaster.debtorno=bio_oldorders.debtorno 
           AND    debtorsmaster.debtorno=custbranch.branchcode
           AND    bio_district.cid=debtorsmaster.cid
           AND    bio_district.stateid=debtorsmaster.stateid   
           AND    bio_district.did=debtorsmaster.did";
           // AND    bio_oldorderdoclist.status=0
           if(isset($_POST['filter']))
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
    if (isset($_POST['country']))    {
     if($_POST['country']!=0)   {
     $sql_old .=" AND debtorsmaster.cid=".$_POST['country'];    }
     }
                                                                                
    if (isset($_POST['State']))    {
     if($_POST['State']!=0)   {
     $sql_old .=" AND debtorsmaster.stateid=".$_POST['State'];    }
     }
       if (isset($_POST['District']))    {
     if($_POST['District']!=0)   {
     $sql_old .=" AND debtorsmaster.did=".$_POST['District'];
      
     if (isset($_POST['lsgType']))    {
     if($_POST['lsgType']!=0)   {
       if($_POST['lsgType']!=NULL)   {  
         
     $sql_old .=" AND debtorsmaster.LSG_type=".$_POST['lsgType'];    
     
     if (isset($_POST['lsgName']))    {
     if($_POST['lsgName']==1 OR $_POST['lsgName']==2)   {
$sql_old .=" AND debtorsmaster.LSG_name=".$_POST['lsgName'];    }
    
       elseif($_POST['lsgName']==3){
       $sql_old .=" AND debtorsmaster.LSG_name=".$_POST['lsgName'];    } 
              
       }
       
       if (isset($_POST['gramaPanchayath']))    {  
      if($_POST['gramaPanchayath']!=0 OR $_POST['gramaPanchayath']!=NULL)   {
$sql_old .=" AND debtorsmaster.block_name=".$_POST['gramaPanchayath'];    }       
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
     
      if (isset($_POST['Plantstatus']))    {
     if($_POST['Plantstatus']!=0)   {
     $sql_old .=" AND bio_oldorders.currentstatus=".$_POST['Plantstatus'];    }
     } 
     
                    if ( $_POST['enq']!=0)
                   {  
                     if ( $_POST['enq']==1){                                   
                       $sql_old .= " AND bio_oldorders.debtorno LIKE 'D%'";  
                     }else if ( $_POST['enq']==2){                                   
                       $sql_old .= " AND bio_oldorders.debtorno LIKE 'C%'";                 
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
           
 $sql_old .=" order by debtorsmaster.clientsince asc ";
$result_old=DB_query($sql_old,$db);

$k=0;
$slno=0;
while($row_old=DB_fetch_array($result_old))
{
$slno++;   
     if($_POST['regtype']!=1) 
     {
          if ($k==1)
          {
            echo '<tr class="EvenTableRows">';
            $k=0;
          }else 
          {
            echo '<tr class="OddTableRows">';
            $k=1;     
          }
     }
   
    
      $orderno=$row_old['orderno'];
    
      $debtorno=$row_old['debtorno'];  
      $first_letter=$debtorno[0];
      if($first_letter=='D'){
          $enq=1;
          $sql3="SELECT enquirytype FROM bio_enquirytypes WHERE enqtypeid=1";
      }elseif($first_letter=='C'){
          $enq=2;
          $sql3="SELECT enquirytype FROM bio_enquirytypes WHERE enqtypeid=2";
      }
      $result3=DB_query($sql3,$db);
      $row3=DB_fetch_array($result3);
     $dat= ConvertSQLDate  ($row_old['clientsince']);
     $ph=$row_old['faxno'];
     if($row_old['faxno']=='')
     {
         $ph= $row_old['phoneno'];
     }
     
     if($_POST['regtype']==1) 
     {
         
             if($_POST['enq']==1) { $enq='D'; }
             if($_POST['enq']==2) { $enq='C'; }
             
$result_order=db_query("SELECT COUNT(DISTINCT bio_oldorderdoclist.orderno) FROM bio_oldorderdoclist,bio_oldorders WHERE bio_oldorderdoclist.orderno= bio_oldorders.orderno AND bio_oldorders.debtorno LIKE '$enq%' ",$db);
$myrow_order=DB_fetch_row($result_order);      
   
         $result=db_query("SELECT  bio_document_master.document_name,bio_oldorderdoclist.docno 
                           FROM   bio_oldorders,bio_oldorderdoclist,bio_document_master
                           WHERE  bio_oldorders.debtorno LIKE '$enq%'
                           AND    bio_oldorderdoclist.orderno=bio_oldorders.orderno
                           AND    bio_oldorderdoclist.docno=bio_document_master.doc_no
                           GROUP BY bio_document_master.document_name
                           ORDER BY bio_oldorderdoclist.docno",$db);


         echo"<tr><td><b>$myrow_order[0]</b></td></tr>";
         while($myrow=DB_fetch_array($result))
         {

             $result_count1=db_query("SELECT COUNT(*) 
                                      FROM   bio_oldorderdoclist 
                                      WHERE  bio_oldorderdoclist.docno='".$myrow['docno']."'
                                      AND    bio_oldorderdoclist.status=1  ",$db); 

             $myrow_count1=DB_fetch_row($result_count1);
             
             $result_count2=db_query("SELECT COUNT(*) 
                                      FROM   bio_oldorderdoclist 
                                      WHERE  bio_oldorderdoclist.docno='".$myrow['docno']."'
                                      AND    bio_oldorderdoclist.status=0  ",$db); 

             $myrow_count2=DB_fetch_row($result_count2);
         if ($k==1)
          {
            echo '<tr class="EvenTableRows">';
            $k=0;
          }else 
          {
            echo '<tr class="OddTableRows">';
            $k=1;     
          }
          
             echo"    <td></td>
                      <td>".$myrow['document_name']."</td><td>$myrow_count1[0]</td><td>$myrow_count2[0]</td>
             </tr>";
             
         }
        exit; 
     } 
     else
     {
                                                            
      echo"
               <td>".$slno."</td>
               <td>".$row_old['orderno']."</td> 
               <td>".$row_old['name']."</td>
               <td>".$ph."</td> 
               <td>".$dat."</td> 
               <td>".$row_old['district']."</td> 
               <td>".$row3['enquirytype']."</td>
               <td>".$row_old['createdon']."</td> 
          ";

     }   
    
}       


echo"</table>";
echo"</div>";
echo"</fieldset>";
echo"</form>";      
    
    
?>


<script type="text/javascript">  


  $(document).ready(function() {
  $('#conso').hide();
  });

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
         document.getElementById("showdocument").innerHTML=xmlhttp.responseText; 
    }
  } 
xmlhttp.open("GET","bio_docCustSelection.php?enqid=" + str1,true);
xmlhttp.send(); 
} 

function viewolddocs(str,str1)
{    // alert(str);alert(str1);
    location.href="?orderno="+str+"&enq="+str1;
}

function validation()
{
    var f=0; 
    var no=document.getElementById("no").value;      
    for( i=1; i<= no; i++)
    {
       var status='status'+i;                         
       if(document.getElementById(status).checked==true)
       {                        
           var date='date'+i;    
           if(f==0){f=common_error2(date,'Please Enter a date');  if(f==1) { return f; }} 
           var autono='autono'+i;
           if(f==0){f=common_error2(autono,'Please enter Document No');  if(f==1) { return f; }} 
       }
    }                                                                                           
}

function idproof(str)
{

     var docin='doc'+str;      
     var str1=document.getElementById(docin).value;    //alert(str1); 
     
     if(str1==7 || str1==22 || str1==35) 
     {
                        
         var str2=document.getElementById('debtorno').value;
         
         var status='status'+str;        //alert(status);
        // var str2=document.getElementById(status).checked;     //alert(document.getElementById(status).checked);
         //alert str2;
         if(document.getElementById(status).checked==true)
         {
             controlWindow=window.open("bio_idproof.php?debtorno=" + str2 ,"idproof","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,width=700,height=400");     
         } 
     }                         
}


function editdocin(str){
    
    var id='editdocumentin'+str;
//    alert(id);
    var docid='doc'+str;
    var docno=document.getElementById(docid).value; 
    var rdateid='date'+str;
    var rdate=document.getElementById(rdateid).value;
//    alert(rdate);
    var autoid='autono'+str;
    var autono=document.getElementById(autoid).value;
//    alert(docno);  
//    alert(str);
//    alert(lead); alert(task);
if (str=="")
  {
  document.getElementById(id).innerHTML="";
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
    document.getElementById(id).innerHTML=xmlhttp.responseText;

    }
  } 

xmlhttp.open("GET","bio_documentedit.php?rdate=" + rdate + "&docno=" + docno + "&i="+ str + "&autono=" +autono,true);
xmlhttp.send();    
}



function editdocout(str){
    
    var id='editdocumentout'+str;
//    alert(id);
    var docid='doc'+str;
    var docno=document.getElementById(docid).value; 
    var rdateid='date'+str;
    var rdate=document.getElementById(rdateid).value;
//    alert(rdate);
    var letterid='autono'+str;       
    var letterno=document.getElementById(letterid).value;   
//    alert(letterno); 
//    alert(docno);  
    

if (str=="")
  {
  document.getElementById(id).innerHTML="";
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
    document.getElementById(id).innerHTML=xmlhttp.responseText;

    }
  } 

xmlhttp.open("GET","bio_documentedit.php?rdate=" + rdate + "&docno=" + docno + "&j="+ str + "&letterno=" +letterno,true);
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
xmlhttp.open("GET","bio_showstate_incident.php?country=" + str,true);
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
xmlhttp.open("GET","bio_showstate_incident.php?state=" + str + "&country1=" + str1,true);
xmlhttp.send();

}

 function showblock(str){   
     str1=document.getElementById("country").value;
     str2=document.getElementById('state').value;
     str3=document.getElementById('Districts').value;
     if(str==1 && (str3==1 || str3==3 || str3==4 || str3==5 || str3==7 || str3==9 || str3==10 || str3==10 || str3==14)){
         alert("No Corporation for this district");
         document.getElementById("block").innerHTML="";
         return;
     }
//alert(str1);   alert(str2);       alert(str3);
 if (str3=="")
  {           
   alert("Please select the district");   
  document.getElementById('Districts').focus(); 
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
xmlhttp.open("GET","bio_CustlsgSelection.php?block=" + str +"&country1="+ str1 +"&state1="+ str2 +"&district="+ str3,true);
xmlhttp.send(); 
} 
function showtaluk(str){   
     str1=document.getElementById("country").value;
     str2=document.getElementById('state').value;
     str3=document.getElementById('Districts').value;
 if (str3=="")
  {           
   alert("Please select the district");   
  document.getElementById('Districts').focus(); 
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
xmlhttp.open("GET","bio_CustlsgSelection_incident.php?taluk=" + str +"&country1="+ str1 +"&state1="+ str2 +"&district="+ str3 + "&taluk=" + str,true);
xmlhttp.send(); 
}





   function showVillage(str){   
    str1=document.getElementById("country").value;
     str2=document.getElementById('state').value;
     str3=document.getElementById('Districts').value;
      //alert(str);  
 if (str=="")
  {  
      alert("Please select the district");   
  document.getElementById('Districts').focus(); 
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
xmlhttp.open("GET","bio_CustlsgSelection_incident.php?village=" + str + "&country1="+ str1 +"&state1="+ str2 +"&district="+ str3,true);
xmlhttp.send(); 
}






 function showgramapanchayath(str){   
    str1=document.getElementById("country").value;
     str2=document.getElementById('state').value;
     str3=document.getElementById('Districts').value;
     // alert(str2);  
 if (str=="")
  {  
      alert("Please select the district");   
  document.getElementById('Districts').focus(); 
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
xmlhttp.open("GET","bio_Custlsg_Selection.php?grama=" + str + "&country1="+ str1 +"&state2="+ str2 +"&district="+ str3,true);
xmlhttp.send(); 
}







</script>
