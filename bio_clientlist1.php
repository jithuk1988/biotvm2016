<?php
    $PageSecurity = 80;
include('includes/session.inc');
$title = _('Customer Search based on Plant');  
include('includes/header.inc');

echo '<center><font style="color: #333;
    background:#fff;
    font-weight:bold;
    letter-spacing:0.10em;
    font-size:16px;
    font-family:Georgia;
    text-shadow: 1px 1px 1px #666;">Customer Search</font></center>';
  
    echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '">'; 
    echo"<fieldset style='width:90%;'>";
    echo"<legend>Customers Search</legend>";
    echo"<table style='border:1px solid #F0F0F0;width:80%'>";                                 

    echo"<tr><td>Client From</td>
            <td>Client To</td>
            <td>Customer Type</td>
            <td>Plant Category</td>
            <td>Plant</td></tr>";
    echo"<tr>"; 
   

   echo "<td><input type=text id='periodfrm' class=date alt=".$_SESSION['DefaultDateFormat']." name='periodfrm' value='$_POST[periodfrm]' onChange='return isDate(this, this.value, ".$_SESSION['DefaultDateFormat'].")'></td>";
   echo "<td><input type=text id='periodto' class=date alt=".$_SESSION['DefaultDateFormat']." name='periodto' value='$_POST[periodto]' onChange='return isDate(this, this.value, ".$_SESSION['DefaultDateFormat'].")'></td>";
     
    echo '<td><select name="enq" id="enq" style="width:150px" >';
    echo '<option value=0></option>';   
    $sql1="select * from bio_enquirytypes";
    $result1=DB_query($sql1,$db);
    while($row1=DB_fetch_array($result1))
    {
           
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
 
      
     $sql_cat="SELECT subcatid FROM bio_maincat_subcat WHERE maincatid=1";
   $result_cat=DB_query($sql_cat,$db);
   $cat_arr=array();
   while($row_cat=DB_fetch_array($result_cat)) 
   {  
      $cat_arr[]="'".$row_cat['subcatid']."'";
      $plant_array=join(",", $cat_arr); 
     }
                                                                                
        $sql="SELECT categoryid,categorydescription from stockcategory
                    WHERE stockcategory.categoryid IN ($plant_array)";
   $result=DB_query($sql,$db);

   echo '<td><select name="plantcategory" id="plantcategory" style="width:130px" onchange="showdescription(this.value)">';  
   
    $f=0;
     
    while($myrow1=DB_fetch_array($result))
    { 
  if ($myrow1['categoryid']==$_POST['plantcategory'])  
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
    echo $myrow1['categoryid'] . '">'.$myrow1['categorydescription'];
    echo '</option>';                            
    $f++;
    }
  echo '</select></td>'; 
    

     $sql="SELECT stockid,description from stockmaster";
     $result=DB_query($sql,$db);
      echo"<td id=showPlant><select name='Item' id='item' style='width:130px'>";      
      echo '<option value=""></option>';

  while ( $myrow = DB_fetch_array ($result) ) {
         // echo "<option value=".$myrow[stockid].">".$myrow[description]."</option>";
         if ($myrow[stockid]==$_POST['Item'])  
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
    echo $myrow['stockid'] . '">'.$myrow['description'];
    echo '</option>';                            
    $f++;
      }
   
echo'</td>';   
  echo'</tr>';
  echo"<tr>";
  

echo'</tr></table></td>';
 
echo"<td style=text-align:right; colspan=2;><input type='submit' name='filterbut' id='filterbut' value='search'></td>";                          

  
echo"</tr>";
echo"</table>";
 
echo"</table>";
echo"<br />";

  
    echo "<div style='height:320px; overflow:scroll;'>";
    echo"<table style='border:1px solid #F0F0F0;width:90%'>";  

   // echo $title="<b>Order document report</b>"; 
   
   
    if($_POST['periodfrm']!=NULL){
       $title1.=' : From '.$_POST['periodfrm'];
       }       
    if($_POST['periodto']!=NULL){
       $title1.=' : To '.$_POST['periodto'];   
       }
    if($_POST['enq']!=NULL){
       $sql="SELECT * FROM bio_enquirytypes where enqtypeid=".$_POST['enq'];
       $result=DB_query($sql,$db);
       $myrow1=DB_fetch_array($result);
       $title1.=' : Customer Type : '.$myrow1['enquirytype'];
       }
    if($_POST['plantcategory']!=NULL){
       $sql="SELECT * FROM stockcategory where categoryid='".$_POST['plantcategory']."'";
       $result=DB_query($sql,$db);
       $myrow1=DB_fetch_array($result);
       $title1.=' : Plant Category : '.$myrow1['categorydescription'];
       }
    if($_POST['Item']!=NULL){
       $sql="SELECT * FROM stockmaster where stockid='".$_POST['Item']."'";
       $result=DB_query($sql,$db);
       $myrow1=DB_fetch_array($result);
       $title1.=' : Plant : '.$myrow1['description'];
       }
    
    
function title($a) 
{
echo "<font size='-1' style='margin-left:5%'><b>Count(".$a.")<b/></font>"; 
}    
echo "<tr><td colspan='8'><font size='-1'>"."<b>Search Details".$title1."</font></td></tr>";     
    echo"</td></tr>"; 
    

echo"<tr><th>SL No</th>
         <th>Customer Name</th>
         <th>Contact No</th>
         <th>District</th>
         <th>Plant</th>
         <th>Client Since</th></tr>";    
 
           
      // $sql = "SELECT debtorsmaster.name,custbranch.phoneno,custbranch.faxno,stockmaster.description,salesorders.orddate,bio_district.district
//               FROM   debtorsmaster,custbranch,salesorders,salesorderdetails
//               WHERE  custbranch.debtorno=debtorsmaster.debtorno
//               AND    stockmaster.stockid=salesorderdetails.stkcode
//               AND    salesorderdetails.orderno=salesorders.orderno
//               AND    debtorsmaster.debtorno=salesorders.debtorno
//               AND    debtorsmaster.cid=bio_district.cid
//               AND    debtorsmaster.stateid=bio_district.stateid 
//               AND    debtorsmaster.did=bio_district.did
//                ";       
               
          //$sql  = "SELECT  debtorsmaster.name,custbranch.phoneno,custbranch.faxno,bio_district.district,salesorders.orddate,bio_oldorders.installationdate,stockmaster.description                                                                                                                           
//                   FROM   debtorsmaster,custbranch,bio_district,salesorders,bio_oldorders,stockmaster,salesorderdetails 
//                   WHERE  custbranch.debtorno=debtorsmaster.debtorno  
//                   AND    debtorsmaster.cid=bio_district.cid
//                   AND    debtorsmaster.stateid=bio_district.stateid
//                   AND    debtorsmaster.did=bio_district.did
//                   AND    debtorsmaster.debtorno=salesorders.debtorno
//                   AND    stockmaster.stockid=salesorderdetails.stkcode
//                   AND    salesorderdetails.orderno=salesorders.orderno      
//                   AND    debtorsmaster.debtorno IN (

//                   SELECT  distinct salesorders.debtorno
//                   FROM   salesorders,salesorderdetails
//                   WHERE  salesorders.orderno = salesorderdetails.orderno"; 
//              if(isset($_POST['filterbut'])){                 
//             if ($_POST['Item']!='')    {        
//                  $sql .= "  AND  salesorderdetails.stkcode = '".$_POST['Item']."'";   
//             } }       
//                $sql .= "   UNION
//                   SELECT bio_oldorders.debtorno
//                   FROM    bio_oldorders";
//             if(isset($_POST['filterbut'])){                 
//             if ($_POST['item']!='')    {        
//                  $sql .= "  WHERE bio_oldorders.plantid = '".$_POST['item']."'";   
//             } }            
//                  $sql .= "  )";               
//                  
                  
     $sql1="SELECT `debtorsmaster`.`name`,
                   `debtorsmaster`.`clientsince`, 
                   `stockmaster`.`description`, 
                   `custbranch`.`phoneno`, 
                   `custbranch`.`phoneno`, 
                   `custbranch`.`faxno`, 
                   `bio_district`.`district`
              FROM `debtorsmaster`
         LEFT JOIN `custbranch` 
               ON (`debtorsmaster`.`debtorno` = `custbranch`.`debtorno`)
         LEFT JOIN `bio_district` 
               ON (`custbranch`.`cid` = `bio_district`.`cid`) 
              AND (`custbranch`.`stateid` = `bio_district`.`stateid`) 
              AND (`custbranch`.`did` = `bio_district`.`did`)
         LEFT JOIN `salesorders` 
               ON (`salesorders`.`debtorno` = `debtorsmaster`.`debtorno`)
         LEFT JOIN `salesorderdetails` 
               ON (`salesorders`.`orderno` = `salesorderdetails`.`orderno`)
         LEFT JOIN `stockmaster` 
               ON (`salesorderdetails`.`stkcode` = `stockmaster`.`stockid`)
              WHERE salesorderdetails.stkcode 
               IN (SELECT stockid FROM stockmaster WHERE categoryid 
               IN (SELECT subcatid FROM bio_maincat_subcat WHERE maincatid=1))";
               
               
               
if(isset($_POST['filterbut']))
  {
if($_POST['periodfrm']!="" && $_POST['periodto']!="")
  {
   $sql1 .= " AND debtorsmaster.clientsince BETWEEN '".FormatDateForSQL($_POST['periodfrm'])."' AND '".FormatDateForSQL($_POST['periodto'])."'"; 
  }
if ($_POST['enq']!=0)
  {  
if ($_POST['enq']==1){                                   
    $sql1 .= " AND debtorsmaster.debtorno LIKE 'D%'";  
}else if ( $_POST['enq']==2){                                   
           $sql1 .= " AND debtorsmaster.debtorno LIKE 'C%'";                 
 }                                    
 } 
if ($_POST['plantcategory']!='')
 {
$sql1 .= " AND salesorderdetails.stkcode IN(SELECT stockid FROM stockmaster WHERE categoryid='".$_POST['plantcategory']."')"; 
                       
}
if ($_POST['Item']!='')
{
$sql1 .= " AND salesorderdetails.stkcode ='".$_POST['Item']."'"; 
                       
}
                      
}

     $sql2="SELECT `debtorsmaster`.`name`,
                   `debtorsmaster`.`clientsince`, 
                   `stockmaster`.`description`, 
                   `custbranch`.`phoneno`, 
                   `custbranch`.`phoneno`, 
                   `custbranch`.`faxno`, 
                   `bio_district`.`district`
              FROM `debtorsmaster`
          LEFT JOIN custbranch 
               ON (`debtorsmaster`.`debtorno` = `custbranch`.`debtorno`)
         LEFT JOIN `bio_district` 
               ON (`custbranch`.`cid` = `bio_district`.`cid`) 
              AND (`custbranch`.`stateid` = `bio_district`.`stateid`) 
              AND (`custbranch`.`did` = `bio_district`.`did`)
         LEFT JOIN `bio_oldorders` 
               ON (`bio_oldorders`.`debtorno` = `debtorsmaster`.`debtorno`)
         LEFT JOIN `stockmaster` 
               ON (`bio_oldorders`.`plantid` = `stockmaster`.`stockid`)
              WHERE bio_oldorders.plantid 
                IN (SELECT stockid FROM stockmaster WHERE categoryid 
                IN (SELECT subcatid FROM bio_maincat_subcat WHERE maincatid=1))"; 
                     
        if(isset($_POST['filterbut']))
           {
                   
                if($_POST['periodfrm']!="" && $_POST['periodto']!="")
                 {
                     $sql2 .= " AND debtorsmaster.clientsince BETWEEN '".FormatDateForSQL($_POST['periodfrm'])."' AND '".FormatDateForSQL($_POST['periodto'])."'"; 
                 }
                 if ( $_POST['enq']!=0)
                   {  
                     if ( $_POST['enq']==1){                                   
                       $sq2 .= " AND debtorsmaster.debtorno LIKE 'D%'";  
                     }else if ( $_POST['enq']==2){                                   
                       $sql2 .= " AND debtorsmaster.debtorno LIKE 'C%'";                 
                   }                                    
                   } 
                                      if ($_POST['plantcategory']!='')
                   {
                      $sql2 .= " AND bio_oldorders.plantid IN(SELECT stockid FROM stockmaster WHERE categoryid='".$_POST['plantcategory']."')"; 
                       
                   }
                   
                   
                                      if ($_POST['Item']!='')
                   {
                      $sql2 .= " AND bio_oldorders.plantid ='".$_POST['Item']."'"; 
                       
                   }
                      
           }
        
            $sql=   $sql1." UNION ".$sql2;
  // echo $sql1;        
    /* $sql=" SELECT debtorsmaster.debtorno,debtorsmaster.name,custbranch.phoneno,custbranch.faxno,bio_district.district,salesorders.orderno,salesorders.orddate as orderdate, bio_oldorders.installationdate as installationdate 
            FROM debtorsmaster
            LEFT JOIN salesorders on debtorsmaster.debtorno=salesorders.debtorno 
            LEFT JOIN bio_oldorders on debtorsmaster.debtorno=bio_oldorders.debtorno 
            LEFT JOIN custbranch on custbranch.debtorno=debtorsmaster.debtorno
            LEFT JOIN bio_district on debtorsmaster.cid=bio_district.cid AND debtorsmaster.stateid=bio_district.stateid AND debtorsmaster.did=bio_district.did
            WHERE
            debtorsmaster.debtorno IN ( 
            SELECT distinct salesorders.debtorno 
            FROM salesorders,salesorderdetails 
            WHERE salesorderdetails.stkcode='PO-1' 
            UNION SELECT bio_oldorders.debtorno FROM bio_oldorders 
            WHERE plantid='PO-1')   ";                   */
                                              
         /*                  
           if(isset($_POST['filterbut']))
           {           
                
                if($_POST['periodfrm']!="" && $_POST['periodto']!="")
                 {
                     $sql .= " AND salesorders.orddate BETWEEN '".FormatDateForSQL($_POST['periodfrm'])."' AND '".FormatDateForSQL($_POST['periodto'])."'"; 
                 }
           }
               
                          */
      
     
     // if (isset($_POST['plantcategory']))    {
//                    
//     if($_POST['plantcategory']!='')   {        
//     $sql .=" AND stockmaster.categoryid='".$_POST['plantcategory']."'";    }
//     } 
      
 
                 /*
                    if ( $_POST['enq']!=0)
                   {  
                     if ( $_POST['enq']==1){                                   
                       $sql .= " AND debtorsmaster.debtorno LIKE 'D%'";  
                     }else if ( $_POST['enq']==2){                                   
                       $sql .= " AND debtorsmaster.debtorno LIKE 'C%'";                 
                   }                                    
                   } 
                                 */
                   
 

 
            
 
//echo  $sql;
$result=DB_query($sql,$db);
                           $k=0;
$slno=1;
$p=0;
while($row=DB_fetch_array($result))
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
          
                             //$row['installationdate'];
          
                      $dat= ConvertSQLDate($row['clientsince']);   

          

      echo "
                <td>".$slno."</td>
                
               
                   
                <td>".$row['name']."</td>
                <td>".$row['faxno']."</td>
                <td>".$row['district']."</td> 
                 
                      

                <td>".$row['description']."</td>
                 
                <td>".$dat."</td>     
               
                
          ";
          $slno++;
          $p++;
    
} 
title($p);

echo"</table>";
echo"</div>";
echo"</fieldset>";
echo"</form>";      
?>    
 

<script type="text/javascript">  
 
 
function showdescription(str){
if (str=="")
  {
  document.getElementById("showPlant").innerHTML="";     //editleads
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
    document.getElementById("showPlant").innerHTML=xmlhttp.responseText;
//if(str==2){getservice(str);}
    }
  }
xmlhttp.open("GET","bio_selectplantforlead.php?catid="+str,true);
xmlhttp.send();


}

  </script>
