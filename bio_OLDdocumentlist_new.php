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
    
    

 
 
    echo"<fieldset style='width:90%;'>";
    echo"<legend>Old Customers</legend>";
    echo"<table style='border:1px solid #F0F0F0;width:80%'>"; 
    
    echo "<form action=" . $_SERVER['PHP_SELF'] . "?" . SID ." method=POST>";

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

  echo"<td id=showPlantstatus>Plant Status<select name='Plantstatus' id='plantstatus' style='width:130px'>";      
  
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
 
    echo '<td><input type=submit name=filter value=Search></td>';

//echo"</tr>";
echo"</table>";

  
  
  
  
       
echo"</table>";
echo"<br />";




    
echo "<div style='height:320px; overflow:scroll;'>";
echo"<table style='border:1px solid #F0F0F0;width:90%'>";
if($_POST['regtype']!=1)   {
echo"<tr><th>SL No</th><th>Order No</th><th>Customer Name</th><th>Contact No</th><th>Client Since</th><th>District</th><th>Customer Type</th><th>Created On</th></tr>";    
}else{
echo"<tr><th>No:of orders</th><th>Document Name</th><th>Recieved Documents</th><th>Pending Documents</th></tr>";     
}    
    

 $sql_old="SELECT DISTINCT bio_oldorderdoclist.orderno AS oldorders,0 AS neworders,
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
           AND    debtorsmaster.debtorno=custbranch.debtorno
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
           
//$sql_old .=" order by debtorsmaster.clientsince asc ";

          echo $sql_old;

 $sql_so="SELECT DISTINCT  0 AS oldorders,salesorders.orderno AS neworders,
                  debtorsmaster.debtorno,
                  debtorsmaster.name,
                  bio_district.district,
                  debtorsmaster.clientsince,
                  custbranch.phoneno,
                  custbranch.faxno,
                  salesorders.orddate  
 
           FROM   salesorders,salesorderdetails,bio_documentlist,debtorsmaster,custbranch,bio_district 
           WHERE  debtorsmaster.debtorno=salesorders.debtorno 
           AND    salesorderdetails.orderno=salesorders.orderno        
           AND    bio_documentlist.orderno=salesorders.orderno
           AND    debtorsmaster.debtorno=custbranch.debtorno
           AND    bio_district.cid=debtorsmaster.cid
           AND    bio_district.stateid=debtorsmaster.stateid   
           AND    bio_district.did=debtorsmaster.did";
           // AND    bio_oldorderdoclist.status=0
           if(isset($_POST['filter']))
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
    if (isset($_POST['country']))    {
     if($_POST['country']!=0)   {
     $sql_so .=" AND debtorsmaster.cid=".$_POST['country'];    }
     }
                                                                                
    if (isset($_POST['State']))    {
     if($_POST['State']!=0)   {
     $sql_so .=" AND debtorsmaster.stateid=".$_POST['State'];    }
     }
       if (isset($_POST['District']))    {
     if($_POST['District']!=0)   {
     $sql_so .=" AND debtorsmaster.did=".$_POST['District'];
      
     if (isset($_POST['lsgType']))    {
     if($_POST['lsgType']!=0)   {
       if($_POST['lsgType']!=NULL)   {  
         
     $sql_so .=" AND debtorsmaster.LSG_type=".$_POST['lsgType'];    
     
     if (isset($_POST['lsgName']))    {
     if($_POST['lsgName']==1 OR $_POST['lsgName']==2)   {
$sql_so .=" AND debtorsmaster.LSG_name=".$_POST['lsgName'];    }
    
       elseif($_POST['lsgName']==3){
       $sql_so .=" AND debtorsmaster.LSG_name=".$_POST['lsgName'];    } 
              
       }
       
       if (isset($_POST['gramaPanchayath']))    {  
      if($_POST['gramaPanchayath']!=0 OR $_POST['gramaPanchayath']!=NULL)   {
$sql_so .=" AND debtorsmaster.block_name=".$_POST['gramaPanchayath'];    }       
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
     
/*      if (isset($_POST['Plantstatus']))    {
     if($_POST['Plantstatus']!=0)   {
     $sql_so .=" AND bio_oldorders.currentstatus=".$_POST['Plantstatus'];    }
     }       */
     
                   if ( $_POST['enq']!=0)
                   {  
                     if ( $_POST['enq']==1){                                   
                       $sql_so .= " AND salesorders.debtorno LIKE 'D%'";  
                     }else if ( $_POST['enq']==2){                                   
                       $sql_so .= " AND salesorders.debtorno LIKE 'C%'";                 
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
           
           $sql=   $sql_old." UNION ".$sql_so;      


$result_old=DB_query($sql,$db);

//$count_pending=array();
//$count_received=array();
$order_new=array(); 
$order_old=array();  

$k=0;
$slno=0;
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
      }
      $result3=DB_query($sql3,$db);
      $row3=DB_fetch_array($result3);   
                      
   
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
                                              
      echo"
               <td>".$slno."</td>
               <td>".$orderno."</td> 
               <td>".$row_old['name']."</td>
               <td>".$ph."</td> 
               <td>".$dat."</td> 
               <td>".$row_old['district']."</td> 
               <td>".$row3['enquirytype']."</td>
               <td>".$row_old['createdon']."</td>
               </tr>
          ";
          
 }  
  
 
}
echo"<br />".$oldorders_arr=join(",", $order_old);
echo"<br />".$neworders_arr=join(",", $order_new);
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
 echo "<br /><br />".$sql;  
 
//echo$sql="SELECT `docno`, COUNT(*) FROM bio_oldorderdoclist WHERE `status`=0 AND `orderno` IN ($oldorders_arr) AND `docno` IN (SELECT doc_no FROM bio_document_master WHERE enqtypeid='".$_POST['enq']."') GROUP BY `docno`";
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
//        echo "<td></td>
//             <td>".$myrow_docname['document_name']."</td><td>".$myrow[count_not_recd]."</td><td>".$myrow[count_not_recd]."</td>
//             </tr>";     
             
  }          
//  
//  $sql_docname="SELECT doc_no,document_name FROM bio_document_master WHERE enqtypeid='".$_POST['enq']."'";
//  $result_docname=DB_query($sql_docname,$db);
//  while($myrow_docname=DB_fetch_array($result_docname)) 
//  {
//    echo$sql1="SELECT COUNT(*) FROM bio_oldorderdoclist WHERE docno='$myrow_docname[0]' AND orderno='$order_old[$i]'";  
//  } 
                                                                         
    
    
                      
                 
/*        $sql_docname="SELECT doc_no,document_name FROM bio_document_master WHERE enqtypeid=1";
        $result_docname=DB_query($sql_docname,$db);
        while($myrow_docname=DB_fetch_array($result_docname))
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

$sql_pending="SELECT sum(count1) FROM (
              (SELECT count(*) as count1  FROM `bio_oldorderdoclist` WHERE docno= '".$myrow_docname[0]."' and orderno IN ($oldorders_arr) and status=0) 
               UNION ALL
              (SELECT count(*) as count1 FROM bio_documentlist WHERE docno ='".$myrow_docname[0]."' and orderno IN ($neworders_arr) and status=0)
              ) t1";         
$result_pending=DB_query($sql_pending,$db);                
$sql_received="SELECT sum(count1) FROM (
              (SELECT count(*) as count1  FROM `bio_oldorderdoclist` WHERE docno= '".$myrow_docname[0]."' and orderno IN ($oldorders_arr) and status>0) 
               UNION ALL
              (SELECT count(*) as count1 FROM bio_documentlist WHERE docno ='".$myrow_docname[0]."' and orderno IN ($neworders_arr) and status>0)
              ) t2";        
$result_received=DB_query($sql_received,$db);      

$row_received=DB_fetch_array($result_received);
$row_pending=DB_fetch_array($result_pending);          
                                                  */
   

          

             
  //      }
                                                  //----------------Institution-----------------//
/*}elseif($_POST['enq']==2){            
  
    $sql_docname="SELECT doc_no,document_name FROM bio_document_master WHERE enqtypeid=2";
    $result_docname=DB_query($sql_docname,$db);
    $k=0;
    while($myrow_docname=DB_fetch_array($result_docname))
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

$sql_pending="SELECT sum(count1) FROM (
              (SELECT count(*) as count1  FROM `bio_oldorderdoclist` WHERE docno= '".$myrow_docname[0]."' and orderno IN ($oldorders_arr) and status=0) 
               UNION ALL
              (SELECT count(*) as count1 FROM bio_documentlist WHERE docno ='".$myrow_docname[0]."' and orderno IN ($neworders_arr) and status=0)
              ) t1";         
$result_pending=DB_query($sql_pending,$db);                
$sql_received="SELECT sum(count1) FROM (
              (SELECT count(*) as count1  FROM `bio_oldorderdoclist` WHERE docno= '".$myrow_docname[0]."' and orderno IN ($oldorders_arr) and status>0) 
               UNION ALL
              (SELECT count(*) as count1 FROM bio_documentlist WHERE docno ='".$myrow_docname[0]."' and orderno IN ($neworders_arr) and status>0)
              ) t2";        
$result_received=DB_query($sql_received,$db);      

$row_received=DB_fetch_array($result_received);
$row_pending=DB_fetch_array($result_pending);
         
   

          
       echo "<td></td>
             <td>".$myrow_docname['document_name']."</td><td>".$row_received[0]."</td><td>".$row_pending[0]."</td>
             </tr>";




    }        
}       */        

}        

echo"</table>";
echo"</div>";
echo"</fieldset>";
echo"</form>";      
    
    
?>


<script type="text/javascript">  




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
