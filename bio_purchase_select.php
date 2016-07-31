<?php
$PageSecurity=80;
$pagetype=1;
include('includes/session.inc');
$title = _('Purchase'); 
include('includes/header.inc');
include('includes/sidemenu2.php');
 echo '<center><font style="color: #333;
    background:#fff;
    font-weight:bold;
    letter-spacing:0.10em;
    font-size:16px;
    font-family:Georgia;
    text-shadow: 1px 1px 1px #666;">Purchase Order </font></center>';
 if(isset($_POST['selitem']))
 {   
 $sqliten="   SELECT bio_maincat_subcat.maincatid,bio_maincat_subcat.subcatid,bio_maincat_subcat.subsubcatid
FROM `bio_maincat_subcat`
WHERE bio_maincat_subcat.subsubcatid like (select categoryid from stockmaster where stockmaster.stockid like '".$_POST['selitem']."')";
$allcat=DB_query($sqliten,$db, $ErrMsg);
$allcatid=DB_fetch_row($allcat);

$_POST['MainCategoryID']=$allcatid[0];
$_POST['SubCategoryID']=$allcatid[1];
$_POST['CategoryID']=$allcatid[2];
if($allcatid[0]==0 and $allcatid[0]=="")
{
 $fill='no';
}
 }
if(isset($_POST['orderend']))
{ $status="Authorised";
   $sql = "INSERT INTO purchorders (supplierno,
                                    orddate,
                                    intostocklocation,
                                    revised,
                                    status,
                                    deliverydate,
                                    allowprint)
                                    VALUES('".$_POST['supplid']."',
                                    '" . Date('Y-m-d') . "',
                                    '".$_POST['StkLocation']."',
                                    '" . Date('Y-m-d') . "',
                                    '".$status."',
                                    '".FormatDateForSql($_POST['deliverdate'])."',1 )";
    
            $ErrMsg =  _('The purchase order header record could not be inserted into the database because');
            $DbgMsg = _('The SQL statement used to insert the purchase order header record and failed was');
          $result = DB_query($sql,$db,$ErrMsg,$DbgMsg,true);

             /*Insert the purchase order detail records */
      $lastinsert=DB_Last_Insert_ID($Conn,'purchorders','orderno');  
       
   $sql = "INSERT INTO purchorderdetails (orderno,
                                            itemcode,
                                            deliverydate,
                                            itemdescription,
                                            unitprice,
                                            quantityord
                                            )
                                            VALUES ( $lastinsert,
                                            '".$_POST['selitem']."',
                                            '".FormatDateForSql($_POST['deliverdate'])."',
                                            '".$_POST['itemname']."',
                                            ".$_POST['nunit'].",
                                            ".$_POST['qty'].")";
                    $ErrMsg =_('One of the purchase order detail records could not be inserted into the database because');
                    $DbgMsg =_('The SQL statement used to insert the purchase order detail record and failed was');
                    
                    $result =DB_query($sql,$db,$ErrMsg,$DbgMsg,true);
                    
                 //   <a  style='cursor:pointer;'  id='$SupplierID' onclick='openwindo(this.id)'>" ._('Add Items') . " </a>
$print='<a href="' . $rootpath . '/bio_purchase_print.php?' . SID . '&orderno=' . $lastinsert . '">' . _('Print Now') . '</a>  ';                  
        prnMsg(_('Purchase Order') . ' ' . $lastinsert . ' ' . _('has been created'). ' ' . $print,'success');        
            
}
echo'<table width=98% ><tr><td>'; 
echo'<div >'; 
echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "'>";  

//echo'<table class=selection>';
 echo'<table class=selection >';//
 
    echo'<tr><td>' . _('Main category') . ':</td>';
  echo '<td><select name="MainCategoryID" id="maincatid" style="width:200px"  onchange="Filtercategory()" onblur="Filtercategory()">';

$sql = "SELECT maincatid, 
               maincatname 
        FROM bio_maincategorymaster  where rowstatus=1
        ORDER BY maincatname ASC";
$ErrMsg = _('The stock categories could not be retrieved because');
$DbgMsg = _('The SQL used to retrieve stock categories and failed was');
$result = DB_query($sql,$db,$ErrMsg,$DbgMsg);
 // echo '<option value=>';
 $f=0;
 if($fill=='no')
 {
  echo '<option selected value="0">No Category</option>';
 }
while ($myrow=DB_fetch_array($result)){
   
  if (isset($_POST['MainCategoryID']) AND $myrow['maincatid']==$_POST['MainCategoryID']){
        echo '<option selected value="'. $myrow['maincatid'] . '">' . $myrow['maincatname'];
    } else {
        echo '<option value="'. $myrow['maincatid'] . '">' . $myrow['maincatname'];
    }
   
    if($f==0)
    {$f++;
        $MainCategory=$myrow['maincatid'];
        
    }
}
if (!isset($_POST['MainCategoryID'])) {
   $_POST['MainCategoryID']=$MainCategory;
}

echo '</select></td>';//echo "<br>main sql1".$sql;

echo'<tr><td >' . _('Sub category') . ':</td><td id="subcat"><select name="SubCategoryID" id="SubCategoryID"  style="width:200px" onchange="Filtercategory2()"  onblur="Filtercategory2()">';

$sql2 = "SELECT `subcategoryid`,`subcategorydescription` FROM substockcategory,bio_maincat_subcat
        
        WHERE substockcategory.subcategoryid= bio_maincat_subcat.subcatid ";
if($_POST['MainCategoryID'])
{
    $sql2.="AND bio_maincat_subcat.maincatid like '".$_POST['MainCategoryID']."'  group by subcategoryid ORDER BY subcategoryid ASC ";
}
$ErrMsg = _('The stock categories could not be retrieved because');
$DbgMsg = _('The SQL used to retrieve stock categories and failed was');//
$result = DB_query($sql2,$db,$ErrMsg,$DbgMsg);
$f=0;
 if($fill=='no')
 {
  echo '<option selected value="0">No Category</option>';
 }
while ($myrow=DB_fetch_array($result)){
    if (isset($_POST['SubCategoryID']) AND $myrow['subcategoryid']==$_POST['SubCategoryID']){
        echo '<option selected value="'. $myrow['subcategoryid'] . '">' . $myrow['subcategorydescription'];
    } else {
        echo '<option value="'. $myrow['subcategoryid'] . '">' . $myrow['subcategorydescription'];
    }
    if($f==0)
    {$f++;
    $SubCategory=$myrow['subcategoryid'];
    }
}

if (!isset($_POST['SubCategoryID'])) {
    $_POST['SubCategoryID']=$SubCategory;
}


echo '</select></td></tr>';//echo "<br>sub sql2".$sql2;
echo '<tr><td>' . _('Sub sub category') . ':</td><td id="subsubcat"><select name="CategoryID" id="CategoryID" style="width:200px" 
onblur="Filtercategory3()" onchange="Filtercategory3()">';

$sql3 = "SELECT categoryid, categorydescription 
        FROM stockcategory,
             bio_maincat_subcat
        WHERE stockcategory.categoryid= bio_maincat_subcat.subsubcatid ";
if($_POST['SubCategoryID'])
{
    $sql3.="AND bio_maincat_subcat.subcatid like '".$_POST['SubCategoryID']."' ";
}
$ErrMsg = _('The stock categories could not be retrieved because');
$DbgMsg = _('The SQL used to retrieve stock categories and failed was');
$result = DB_query($sql3,$db,$ErrMsg,$DbgMsg);
$f=0;
 if($fill=='no')
 {
  echo '<option selected value="0">No Category</option>';
 }
while ($myrow=DB_fetch_array($result)){
    if (!isset($_POST['CategoryID']) or $myrow['categoryid']==$_POST['CategoryID']){
        echo '<option selected value="'. $myrow['categoryid'] . '">' . $myrow['categorydescription'];
    } else {
        echo '<option value="'. $myrow['categoryid'] . '">' . $myrow['categorydescription'];
    }
    if($f==0)
    {$f++;
    $Category=$myrow['categoryid'];
    }
}

if (!isset($_POST['CategoryID'])) {
    $_POST['CategoryID']=$Category;
}

echo '</select></td></tr>';//echo "<br>subsub".$sql3;
echo '<tr><td>' . _('Item name') . ':</td><td id="seleitem"><select name="selitem" id="selitem" style="width:200px" >';
$sql4 = "SELECT `stockid`,`description` FROM `stockmaster`";
$ErrMsg = _('The stock categories could not be retrieved because');
$DbgMsg = _('The SQL used to retrieve stock categories and failed was');
$result = DB_query($sql4,$db,$ErrMsg,$DbgMsg);
//echo $sql;
$f=0;
while ($myrow=DB_fetch_array($result)){
    if ( $myrow['stockid']==$_GET['CategoryID'] or $myrow['stockid']==$_POST['selitem']){
        echo '<option selected value="'. $myrow['stockid'] . '">' . $myrow['description'];
    } else {
        echo '<option value="'. $myrow['stockid'] . '">' . $myrow['description'];
    }
    $stockid=$myrow['stockid'];
}



 
echo '</select></td></tr>';//echo "<br>item".$sql4;
echo'<tr><td colspan="4" scope="row"><center><input type=submit name=submit value="Search" onclick="if(log_in()==1)return false;">
</center></td></tr>';



 echo"</table >"; 
// echo'<center><input type=submit name=submit value="Search" onclick="if(log_in()==1)return false;"></center>'; 

 if(isset($_POST['submit']))
 {
     //echo $_POST['selitem'];
     $sql2="select stockmaster.stockid,stockmaster.description,sum(locstock.quantity) as qty,suppliers.suppname,suppliers.supplierid,locations.loccode
from stockmaster 
inner join locstock on(stockmaster.stockid=locstock.stockid) 
inner join bio_supplieritems on(stockmaster.stockid=itemid)
inner join suppliers on(bio_supplieritems.supplierid=suppliers.supplierid)
inner join locations on(locations.loccode=locstock.loccode) 
where stockmaster.stockid='".$_POST['selitem']."' ";//and locstock.loccode='".$_POST['StkLocation']."'
 $result=DB_query($sql2,$db);
       $myrow = DB_fetch_row($result); 
        
  $sqlde="SELECT sum(`quantity`)as demqty
FROM `mrpdemands`
WHERE `stockid` LIKE '".$_POST['selitem']."'";
      $deresult=DB_query($sqlde,$db);
       $derow = DB_fetch_row($deresult);
       
$sqlRS="SELECT `unitprice`,`actprice` FROM `purchorderdetails` WHERE `itemcode`= '".$_POST['selitem']."'";
      $RSresult=DB_query($sqlRS,$db);
       $RSrow = DB_fetch_row($RSresult);
       
       
echo'<table width=100% border=2 bordercolor="green">';
  echo'   <tr>
        <th><font color=blue size=4><b>' . _('Item Details') . '</b></font></th>
          
            <th><font color=blue size=4><b>' . _('Enter New Item Details') . '</b></font></th>
        </tr>';
        
echo'<tr><td valign=top><table border=0 width=100%>';
echo'<tr><td><b>Item name</td><td>:</td><td><b>'.$myrow[1].'</td></tr>';
echo'<input type="hidden" name="itemname" value="'.$myrow[1].'">';

echo'<tr><td><b>Stock qty</td><td>:</td><td>';
if($myrow[2]!=0){echo'<b>'.$myrow[2].' </td></tr>';}
else{echo'<b>NULL </td></tr>'; }
echo'<tr><td><b>MRP demand</td><td>:</td><td>';
if($derow[0]!=0){echo'<b>'.$derow[0].'</td></tr>';}
else{echo'<b>NULL </td></tr>'; }
echo'<tr><td><b>Last unit puchase price</td><td>:</td><td>';
if($RSrow[0]!=0){echo'<b>'.$RSrow[0].'/unit </td></tr>';}
else{echo'<b>NULL </td></tr>'; }

echo'<input type="hidden" name="unit" id="unit" value="'.$RSrow[0].'">';




echo'</tr></table></td>';

echo'<td><table border=0 width=100%>';

echo '<tr><td>' . _('Warehouse') . ':</td>
            <td><select name=StkLocation >';

    $sql = "SELECT loccode,
                    locationname
            FROM locations where managed=1";
    $LocnResult = DB_query($sql,$db);

    while ($LocnRow=DB_fetch_array($LocnResult)){
        if (isset($_POST['StkLocation']) and ($_POST['StkLocation'] == $LocnRow['loccode'] OR
                ($_POST['StkLocation']=='' AND $LocnRow['loccode']==$_SESSION['UserStockLocation']))){
            echo '<option selected value="' . $LocnRow['loccode'] . '">' . $LocnRow['locationname'] . '</option>';
        } else {
            echo '<option value="' . $LocnRow['loccode'] . '">' . $LocnRow['locationname'] . '</option>';
        }
    }
echo '</select></td></tr>';
echo '<tr><td>' . _('Supplier Name') . ':</td>
            <td><select name=supplid id=supplid>';

    $sql = "SELECT suppliers.`suppname`,suppliers.`supplierid` 
FROM `bio_supplieritems`
inner join suppliers on (bio_supplieritems.`supplierid`=suppliers.`supplierid`) where bio_supplieritems.`itemid` 
LIKE '".$_POST['selitem']."'";//LIKE '".$_POST['submit']."'
    $supResult = DB_query($sql,$db);

    while ($supRow=DB_fetch_array($supResult)){
        if ($_POST['df']==$supRow['supplierid']){
            echo '<option selected value="' . $supRow['supplierid'] . '">' . $supRow['suppname']. '</option>';
        } else {
            echo '<option value="' . $supRow['supplierid'] . '">' . $supRow['suppname']. '</option>';
        }
    }
echo '</select>
<a href="'.$rootpath.'/Suppliers.php" target=_blank>' ._('Add new vendor') . '</a></td></tr>';

echo'<tr><td>Deliver by</td><td>
    <input  style="width:140px" type="text" name="deliverdate" id="deliverdate" class=date alt="'.$_SESSION['DefaultDateFormat']. '" value='.date("d/m/Y").'></td></tr>';
echo'<tr><td>Order qty</td><td><input type="text" name="qty" id="qty" onchange="viewtotal()"></td></tr>';
echo'<tr><td>Order  price/unit</td><td><input type="text" name="nunit" id="nunit" onchange="viewtotal()"></td></tr>';



echo'<tr><td>Total price</td><td id="sumamount"><b></b></td></tr>';

//echo'<tr><td colspan="2" scope="row"><center><input type="submit" name="orderend" id="orderend"></center></td></tr>';
echo'</tr></table></td>';
 
echo"</table>";
 echo'<center><input type="submit" name="orderend" id="orderend" value="Order" onclick="if(order()==1)return false;"></center>'; 

   
      
 }        
echo"</form>";
echo"</div></td></tr>";   

echo"</table>";

  
?>
<script>
function log_in()
{
     

  var str1=document.getElementById("selitem").value;
    //alert(str1);
    
   if (str1=="")
  { 
      alert("Place select item");
      
     document.getElementById("selitem").focus(); 
     return 1;
  }
    
}

function order()
{
var str1=document.getElementById("supplid").value;
    //alert(str1);
    
   if (str1=="")
  { 
      alert(" Select supplier");
      document.getElementById("supplid").focus(); 
     return 1;
  }
var str2=document.getElementById("qty").value;
  if (str2=="")
  { 
      alert("Enter order Qty ");
      
     document.getElementById("qty").focus(); 
     return 1;
  }
  var str1=document.getElementById("nunit").value;
   if (str3=="")
  { 
      alert("Enter order Qty ");
      
     document.getElementById("nunit").focus(); 
     return 1;
  }
    
}
function viewtotal()
{

  var str2=document.getElementById("qty").value;
  var str1=document.getElementById("nunit").value;

  var sub=str1*str2;//alert(sub);
  if(sub<=0)
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
  { // alert (str1);
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
          
         // document.getElementById('showloc').style.display = "block";
     document.getElementById("sumamount").innerHTML=xmlhttp.responseText; 
     
    }            
  } 
xmlhttp.open("GET","bio_maincatsubcat_purchase_ajx.php?total="+sub,true);//alert(str1);
xmlhttp.send();        
}
</script>
<script>

function Filtercategory()       {

var str= document.getElementById("maincatid").value;
//alert(str);
    
if (str=="")
  {
  document.getElementById("propertyvalue").innerHTML="";
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
        
    document.getElementById("subcat").innerHTML=xmlhttp.responseText;
//      $('#dinhide').show(); 
    }
  } 
  //alert(str);
xmlhttp.open("GET","bio_maincatsubcat_purchase_ajx.php?maincatid="+str,true);
xmlhttp.send(); 
    

}


function Filtercategory2()   {

var str= document.getElementById("maincatid").value;
var str1= document.getElementById("SubCategoryID").value;
   //alert(str);
if (str=="")
  {
  document.getElementById("propertyvalue").innerHTML="";
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
        
    document.getElementById("subsubcat").innerHTML=xmlhttp.responseText;
//      $('#dinhide').show(); 
    }
  } 
  //alert(str);
xmlhttp.open("GET","bio_maincatsubcat_purchase_ajx.php?maincatid="+str+"&subcatid="+str1,true);
xmlhttp.send(); 
    

}
function Filtercategory3()       {

var str= document.getElementById("maincatid").value;
var str1= document.getElementById("SubCategoryID").value;
var str2= document.getElementById("CategoryID").value;
    //alert("gdhdd");
if (str2=="")
  {
  document.getElementById("CategoryID").innerHTML="";
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
        
    document.getElementById("seleitem").innerHTML=xmlhttp.responseText;
//      $('#dinhide').show(); 
    }
  } 
  //alert(str);
xmlhttp.open("GET","bio_maincatsubcat_purchase_ajx.php?maincatid="+str+"&subcatid="+str1+"&subsubcatid="+str2,true);
xmlhttp.send(); 
    

}
</script>