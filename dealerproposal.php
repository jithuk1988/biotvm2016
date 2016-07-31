<?php
$PageSecurity = 80;
include('includes/session.inc');
$title = _('Proposal');
$pagetype=1;
include('includes/header.inc');
include('includes/sidemenu.php');
include('includes/bio_GetPrice.inc');

if (isset($_POST['SelectedType'])){
    $SelectedType = strtoupper($_POST['SelectedType']);
} elseif (isset($_GET['SelectedType'])){
    $SelectedType = strtoupper($_GET['SelectedType']);
}

/* 
if(!isset($_GET['stockid'])) 
{

    $tmpsubdel="DELETE FROM bio_temppropsubsidy WHERE leadid='".$_SESSION['leadid']."'";
      DB_query($tmpsubdel,$db); 

      $tmpdel="DELETE FROM bio_temppropitems WHERE leadid='".$_SESSION['leadid']."'";
      DB_query($tmpdel,$db);   
 } 
       */
   if($_GET['delete'])   
   {
       $leadid=$_GET['SelectedType1'];
       $propid=$_GET['propid'] ;
       $sql_plantdel="DELETE FROM bio_lsgplantdetails WHERE  propid='".$propid."'"; 
     $result_plantdel=DB_query($sql_plantdel,$db);
         $sql_sec="DELETE FROM bio_lsgplant WHERE  propid='".$propid."' AND leadid='".$leadid."'"; 
     $result_sec=DB_query($sql_sec,$db);
         
           $sql_thi="DELETE FROM bio_fundingagency WHERE  propid='".$propid."' AND leadid='".$leadid."'"; 
     $result_thi=DB_query($sql_thi,$db);
   } 
       
 if($_POST['edit'])                                  
 {
     //echo"edit";
    $propid=$_SESSION['propid'];
    
    $leadid=$_SESSION['leadid'];   
    $qty1=$_POST['qty1'];
        $price1= $_POST['price1'];
        $tprice1= $_POST['tprice1'];
    
   $sql_plant="SELECT * FROM bio_temppropitems WHERE leadid='".$_SESSION['leadid']."'";
    $result_plant=DB_query($sql_plant,$db);

     $result=DB_query("SELECT SUM(tprice) FROM bio_temppropitems WHERE leadid='".$leadid."' ",$db);
     $myrow=DB_fetch_row($result); 

 $sql_totprice="UPDATE bio_lsgplant SET totprice='".$tprice1."'                                       
                                      WHERE propid='".$propid."'
                                      AND leadid= $leadid";
                                      
 $result_totprice=DB_query($sql_totprice,$db); 
 
 
 
 
 
/*     $sql="DELETE FROM bio_lsgplant WHERE leadid=$leadid AND propid='".$_POST['flag']."'"; 
     $result=DB_query($sql,$db);   */
     
      $sql_plantdel="DELETE FROM bio_lsgplantdetails WHERE  propid='".$propid."'"; 
     $result_plantdel=DB_query($sql_plantdel,$db);
     
     $sql_funddel="DELETE FROM bio_fundingagency WHERE leadid=$leadid AND propid='".$propid."'"; 
     $result_funddel=DB_query($sql_funddel,$db);
     
     $sql_temp ="SELECT * FROM bio_temppropsubsidy WHERE leadid='".$_SESSION['leadid']."'";
    $result_temp=DB_query($sql_temp, $db);       
    while($myrow_temp=DB_fetch_array($result_temp))
    {
       $leadid=$myrow_temp['leadid']; 
        $amount=$myrow_temp['amount'];
       $totalamount=$qty1*$amount;
     $sql_temp1="INSERT INTO bio_fundingagency (leadid,propid,funding_agency,amount) VALUES($leadid,$propid,'".$myrow_temp['scheme']."','".$totalamount."')"; 
            $result_temp1=DB_query($sql_temp1,$db);
    } 
        $slno=0;
    while($myrow_plant=DB_fetch_array($result_plant))
    {
    $sql_prop="INSERT INTO bio_lsgplantdetails(propid,slno,stockid,description,qty,price,tprice)
                                              VALUES($propid,
                                                    ".++$slno.",
                                                    '".$myrow_plant[stockid]."',
                                                    '".$myrow_plant[description]."',
                                                   '".$qty1."',
                                                    '".$price1."',
                                                    '".$tprice1."')";
     $result_prop=DB_query($sql_prop,$db);
     

  }
 
   $sql4="DELETE FROM bio_temppropitems where leadid=$leadid";
   $result4=DB_query($sql4,$db);
   $sql5="DELETE FROM bio_temppropsubsidy where leadid=$leadid"; 
   $result5=DB_query($sql5,$db);    
 }            
       
 
if($_POST['submit'])
 {
        echo"submit"; 
        $qty1=$_POST['qty1'];
        $price1= $_POST['price1'];
        $tprice1= $_POST['tprice1'];
    $leadid=$_SESSION['leadid'];
   $sql_plant="SELECT * FROM bio_temppropitems WHERE leadid='".$_SESSION['leadid']."'";
  $result_plant=DB_query($sql_plant,$db);

     $result=DB_query("SELECT SUM(tprice) FROM bio_temppropitems WHERE leadid='".$leadid."' ",$db);
     $myrow=DB_fetch_row($result); 


   
      $sql_plant1="INSERT INTO bio_lsgplant(propdate,leadid,totprice,status) VALUES('".date("Y-m-d")."',$leadid,$tprice1,0)";
      $result_plant1=DB_query($sql_plant1,$db);
       
       $sql_plant1= "SELECT LAST_INSERT_ID()" ; 
       $result5=DB_query($sql_plant1,$db);                                                                 //
       $checkresult5=DB_fetch_array($result5);
       $propid=$checkresult5[0]; 
       
       
      $sql_temp ="SELECT * FROM bio_temppropsubsidy WHERE leadid='".$_SESSION['leadid']."'";
    $result_temp=DB_query($sql_temp, $db);       
    while($myrow_temp=DB_fetch_array($result_temp))
    {
       $leadid=$myrow_temp['leadid']; 
       $amount=$myrow_temp['amount'];
       $totalamount=$qty1*$amount;
    $sql_temp1="INSERT INTO bio_fundingagency (leadid,propid,funding_agency,amount) VALUES($leadid,$propid,'".$myrow_temp['scheme']."','".$totalamount."')"; 
           $result_temp1=DB_query($sql_temp1,$db);
    } 
    

    $slno=0;
    while($myrow_plant=DB_fetch_array($result_plant))
    {
     $sql_prop="INSERT INTO bio_lsgplantdetails(propid,slno,stockid,description,qty,price,tprice)
                                              VALUES($propid,
                                                    ".++$slno.",
                                                    '".$myrow_plant[stockid]."',
                                                    '".$myrow_plant[description]."',
                                                    '".$qty1."',
                                                    '".$price1."',
                                                    '".$tprice1."')";
     $result_prop=DB_query($sql_prop,$db);
     
 //   }  
//  }
  //else
 // {
      
       
     
   /*$sql_lsgplant="UPDATE bio_lsgplant SET propdate='".date("Y-m-d")."',                                          
                                      totprice='".$myrow[0]."',
                                      status=0
                                      WHERE propid='".$_POST['flag']."'
                                      AND leadid= $leadid";
                                      
 $result_lsgplant=DB_query($sql_lsgplant,$db); 
 
 $sql_plantdetails="UPDATE bio_lsgplantdetails SET  stockid= '".$myrow_plant[stockid]."',  
                                                    description='".$myrow_plant[description]."',
                                                    qty= '".$myrow_plant[qty]."',
                                                    price='".$myrow_plant[price]."',
                                                    tprice='".$myrow_plant[tprice]."'
                                                     WHERE $propid='".$_POST['flag']."'";
                                                    
 $result_plantdetails=DB_query($sql_plantdetails,$db);  
 
 $sql_fund="UPDATE bio_fundingagency SET                          
                                          funding_agency='".$myrow_temp['scheme']."',
                                          amount='".$myrow_temp['amount']."'      
                                           WHERE $propid='".$_POST['flag']."'
                                           AND leadid=$leadid";
                                           
$result_fund=DB_query($sql_fund,$db); 
                                          
   
                                   
  prnMsg( _('Proposal Details ') . $_POST['leadid'] . _(' has been updated successfully.'),'success');*/                                    
                                      
 
 // }
//     $_SESSION['lsgid'];
//     $sql_id="INSERT INTO bio_lsgid(lsgregid,propid)VALUES('".$_SESSION['lsgid']."',$propid)";
//    $result_id=DB_query($sql_id,$db);
    
 
}      
   $sql4="DELETE FROM bio_temppropitems where leadid=$leadid";
  $result4=DB_query($sql4,$db);
   $sql5="DELETE FROM bio_temppropsubsidy where leadid=$leadid"; 
   $result5=DB_query($sql5,$db); 
 }                                         
 

if($_SESSION['leadid']!='')
{          
      $leadid=$_SESSION['leadid'];
}   
if($_GET['leadid']) 
{ 
     $leadid=$_GET['leadid'];
     $_SESSION['lsgid'] =$_GET['id'];
     $_SESSION['leadid']=$_GET['leadid'];
     
   $sql4="DELETE FROM bio_temppropitems";
   $result4=DB_query($sql4,$db);
   $sql5="DELETE FROM bio_temppropsubsidy";      //      where leadid=$leadid    
   $result5=DB_query($sql5,$db);   
      
}
           
echo '<center><font style="color: #333;
    background:#fff;
    font-weight:bold;
    letter-spacing:0.10em;
    font-size:16px;
    font-family:Georgia;
    text-shadow: 1px 1px 1px #666;">PROPOSAL TEMPLATE</font></center>';
   
echo"<table style='width:700px'>";
echo"<tr><td>";
echo"<div id=panel>"; 
echo"<table>";
echo"<tr><td style='width:50%'>";
echo"<fieldset style='height:230px;width:600px'><legend>Proposal Details</legend>";
echo"<table style='width:90%'>";

$sql="SELECT bio_leads.leadid,bio_cust.cust_id,debtorsmaster.debtorno,debtorsmaster.name, 
CONCAT( debtorsmaster.address1, '-', debtorsmaster.address2 ) AS 'address', 
CONCAT( bio_cust.custphone, '<br>', bio_cust.custmob ) AS 'mobile'
FROM `debtorsmaster`
INNER JOIN bio_cust ON debtorsmaster.debtorno = bio_cust.`debtorno`
INNER join bio_leads ON bio_leads.cust_id=bio_cust.cust_id
AND  bio_leads.enqtypeid=8
AND debtorsmaster.debtorno LIKE 'DL%' AND bio_leads.leadid=".$leadid;
  $result=DB_query($sql,$db); 
  $myrow = DB_fetch_array($result);
  
  $project_name   = $myrow['name'];
  $total_projectcost =$myrow['address']; 
 // $num_beneficiaries = $myrow['num_beneficiaries'];  
  
  $cph=$myrow['mobile'];

       

  
echo"<tr><td width=50%>Name:</td>";
echo"<td><input type='hidden' name='custname' id='custname' value='$project_name'>$project_name</td></tr>";
echo"<tr></tr>";
echo"<tr><td> Contact Number:</td>";
echo"<td><input type='hidden' name='custph' id='custph' value='$cph'>$cph</td></tr>";
echo"<tr></tr>";
echo"<tr><td>ADDRESS</td>";
echo"<td><input type='hidden' name='email' id='email' value='$total_projectcost'>$total_projectcost</td></tr>";
echo"<tr></tr>";

echo"<tr></tr>";
/*echo"<tr><td>Customer District:</td>";
echo"<td><input type='hidden' name='custdist' id='custdist' value='$district'>$district</td></tr>";
echo"<tr></tr>";
echo"<tr><td>Lead Source Team:</td>";
echo"<td><input type='hidden' name='team' id='team' value='$team'>$team</td></tr>";
echo"<tr></tr>";
echo"<tr><td>Lead Source:</td>";
echo"<td><input type='hidden' name='source' id='source' value='$sourcename'>$sourcename</td></tr>";
echo"<tr></tr>";
echo"<tr><td>Total Amount Paid:</td>";
echo"<td><input type='hidden' name='amount' id='amount' value='$amount'>$amount</td></tr>";
echo"<tr></tr>";
echo"<tr><td>Investment Size:</td>"; 
echo"<td><input type='hidden' name='createdby' id='createdby' value='$investmentsize'>$investmentsize</td></tr>";  
echo"<tr></tr>";
echo"<tr><td>Created By:</td>"; 
echo"<td><input type='hidden' name='createdby' id='createdby' value='$createdby'>$createdby</td></tr>";   

echo"<input type='text' name='lsgid' value='$id'>"; */
echo"</table>";
echo"</fieldset>";
echo"</td>";


echo "<div id=\"refr\" style=\"display:none\">";
echo "<tr><td>&nbsp;</td></tr>";

echo "</div>";

echo "</table></fieldset>";
echo "</td></tr>";
echo "</table>";



//echo "<input type=\"text\" id=\"sid\" name=\"sid\" value='' onchange=location.href=\"?stockid=this.value\";>";


/*
if ($_GET['stockid']) {
  $stockid=$_GET['stockid'];


echo "<table width=700px>";
echo "<tr><td colspan='2'>";
echo "<div id='sellist' style='background: #D6DEF7;'>";
echo "<div id='messageBox'>&nbsp;</div>";
if ($_GET['first']==1) {
  delleadfromtempprod($lead,$db);
  addpropitem($stockid,$lead,$flag2,$db);
}elseif($_GET['first']==2){
    if($_SESSION['ProposalID']!=''){
        $pr_id=$_SESSION['ProposalID'];
//        unset($_SESSION['ProposalID']);
    }else{
        $pr_id=0;
    }

  if (checkduplicateentries($stockid,$lead,$db)==0) {
    addpropitem_edit($stockid,$lead,$flag2,$pr_id,$db);
}  else {
  echo "Item is already selected.";  
  disppropitem_edit($lead,$flag2,$pr_id,$db);
}
    
}
 else {
if (checkduplicateentries($stockid,$lead,$db)==0) {
    addpropitem($stockid,$lead,$flag2,$db);
}  else {
  echo "Item is already selected.";
// echo "<script type=\"JavaScript\">
//  msg='Item is already selected.';
//  displayAlertMessage(msg);
//  selectcategory(".$lead.",0);
//  </script>";
disppropitem($lead,$flag2,$db);
}
}
echo "</div>";
echo "</td></tr>";
}                */
echo "</div>";



/*if ($_GET['categoryid']) {
  $categoryid=$_GET['categoryid'];
  if ($categoryid) {
    echo '<script>$("#grid").hide();</script>';
  }

}      */

//-----------------------------------------------------------------------------------------


       
echo "<div id='grid' style='background: #D6DEF7;'>";
echo "<table>";
echo"<tr><td colspan=2>";

echo "<form action=" . $_SERVER['PHP_SELF'] . "?" . SID ." method=post>";
//echo "<fieldset style='height:230px;width:650px'><legend>Template Selection</legend>";
 echo "<fieldset style='width:870px'>";
   echo "<legend><h3>Template Selection</h3>";     
   echo "</legend>";
     

     echo "<table border=1 >";
     echo "<tr>";
     echo '<td>Main Category<select name="MainCategoryID" id="maincatid" onchange="Filtercategory()">';   
      $sql = "SELECT subcategoryid,subcategorydescription FROM `substockcategory` order by subcategoryid";

$result = DB_query($sql,$db,$ErrMsg,$DbgMsg);
 echo '<option value=0>Select Main category</option>';
while ($myrow=DB_fetch_array($result)){
    if ($myrow['maincatid']==1){
        echo '<option selected value="'. $myrow['subcategoryid'] . '">' . $myrow['subcategorydescription'];
    } else {
        echo '<option value="'. $myrow['subcategoryid'] . '">' . $myrow['subcategorydescription'];
    }
    $MainCategory=$myrow['subcategoryid'];
}
if (!isset($_POST['MainCategoryID'])) {
    $_POST['MainCategoryID']=$MainCategory;
}

echo '</select></td>';  

 $sql="SELECT categoryid,categorydescription,maincatid from stockcategory,
             bio_maincat_subcat
             WHERE stockcategory.categoryid= bio_maincat_subcat.subcatid
             AND bio_maincat_subcat.maincatid =1
             order by stockcategory.categorydescription asc";
      $result=DB_query($sql,$db);
echo '<td id="subcat">Sub Category<select name="caty" id="caty" style="width:200px">';
//      echo '<option value=0>Select category</option>';   

    $f=0;
  while($myrow=DB_fetch_array($result))
  {  
      
  if ($myrow['categoryid']==$planttype)  
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
    echo $myrow['categoryid'] . '">'.$myrow['categorydescription'];
    echo '</option>';
    $f++;
   } 
  echo '</select></td>';
  
  //Search

  echo '<td><input type="button" name="search" id="search" value="Search" style="width:100px" onclick="showItems(0);"></td></tr></table>';  
  echo "<br> ";    
  
 
function checkduplicateentries($stockid,$lead,$db) {
  $sql4="SELECT COUNT(*) FROM bio_temppropitems WHERE leadid=".$lead ." AND stockid='".$stockid."'";
  $result4=DB_query($sql4,$db);
  $myrow4=DB_fetch_array($result4);
  if ($myrow4[0]>0) {
    return 1;
  }
  else {return 0;};
 } 
  
function additems($stockid,$leadid,$flag,$db)
{    

$sql_des="SELECT longdescription FROM stockmaster WHERE stockid='".$stockid."'";
$result_des=DB_query($sql_des,$db);
$myrow_des=DB_fetch_array($result_des);
 $longdes=$myrow_des['longdescription'];

  $price=GetPrice($stockid,$db); 
 
 $sql_sub="SELECT * FROM bio_subsidymaster
            WHERE subsidy_plant_id='$stockid'";
$result_sub=DB_query($sql_sub,$db);
$subsidy_count=DB_num_rows($result_sub);

if($subsidy_count>0){
   // echo "ffffffff";
    while($myrow_sub=DB_fetch_array($result_sub)){
    $result_debtorcode=DB_query("SELECT debtorcode FROM bio_schemes WHERE schemeid='".$myrow_sub['subsidy_scheme_id']."'",$db);  
    $myrow=DB_fetch_row($result_debtorcode);  
    $scheme=$myrow[0];
    
    $amount=$myrow_sub['subsidy_amount'];
     $sql_sub1="INSERT INTO bio_temppropsubsidy(leadid,
                                              stockid,
                                              scheme,
                                              amount) 
                                       VALUES(".$leadid.",
                                              '".$stockid."',
                                              '".$scheme."',
                                              '".$amount."')";
    $result_sub1=DB_query($sql_sub1,$db);
}

 $sql_sub_amount="SELECT SUM(subsidy_amount) FROM bio_subsidymaster
            WHERE subsidy_plant_id='$stockid'";
$result_sub_amount=DB_query($sql_sub_amount,$db);
$myrow_sub_amount=DB_fetch_array($result_sub_amount);
 $subsidy=$myrow_sub_amount[0];
 }
 if($subsidy=='' )
 { 
     $subsidy=0;   
 }
 $netprice=$price-$subsidy;  
 
 
if(isset($_GET['stkdel'])) 
{    
     $stkdel=$_GET['stkdel'];    
  $sql6="DELETE FROM bio_temppropitems where stockid='$stkdel' AND leadid=$leadid";
   $result6=DB_query($sql6,$db);
 
  unset($_GET['stkdel']) ;
}
else
{
 echo $_POST['price'];
 echo $_POST['tprice'];
$sql3="INSERT INTO bio_temppropitems (stockid,description,qty,price,tprice,leadid,subsidy,netprice) 
         VALUES ('".$stockid."','".$longdes."',1,".number_format($price,2,'.','').",".number_format($price,2,'.','').",".$leadid.",'".$subsidy."',".$netprice.")";
 $result3=DB_query($sql3,$db);   
}


  if($flag==0)
  {        

    display($leadid,$flag,$db); 

  }  
  else if($flag==1)
  {
   displayedit($leadid,$propid,$db);   
      
  }
}
  

function display($leadid,$flag,$db)
{
    
      $sql4="SELECT stockid,description,qty,price,tprice,subsidy,netprice FROM bio_temppropitems where leadid=".$leadid;
         $result4=DB_query($sql4,$db);

//echo "<input type=\"hidden\" name=\"lead\" id=\"lead\" value='".$lead."'>";
echo "<table style='width:800px' border='1'>";
echo "<tr><th width=50>Stock Id</th><th width=200>Item Description</th><th>qty</th><th>Unit Price</th><th>Gross Price</th></tr>";
$k=0;
                                        
 while ($myrow4=DB_fetch_array($result4))   {     
  $tprice=$myrow4['qty']*$myrow4['price'];
    $k++;
 $stock[$k]=$myrow4['stockid'];
    $item=$stock[$k];
                                                                                                                                                                                     
   echo "<tr><td>".$stock[$k]."</td><td>".$myrow4['description']."</td>                                                                                                             
       <td><input size=3 style=\"text-align: right\" type=\"text\" name=\"qty".$k."\" id=\"qty".$k."\" value=".$myrow4['qty']." onchange=updatetotalitemprice(".$k.",".$leadid.",'".$stock[$k]."')></td>      
       <td class=number><input size=9 style=\"text-align: right\" type=\"text\" name=\"price".$k."\" id=\"price".$k."\" value=".$myrow4['price']." onchange=updatetotalitemprice(".$k.",".$leadid.",'".$stock[$k]."')></td>
       <td class=number><input size=9 style=\"text-align: right\" type=\"text\" name=\"tprice".$k."\" id=\"tprice".$k."\" value=".$tprice.">
       <input size=9 style=\"text-align: right\" type=\"hidden\" name=\"subsidy".$k."\" id=\"subsidy".$k."\" value=".$myrow4['subsidy']." readonly></td>          
       <td align=center><a  style='cursor:pointer;'  id='$stock[$k]' onclick='addfund(this.id,$leadid)'>" . _('Select Funding Agency') . "</a></td>
        <td align=center><a href=". $_SERVER['PHP_SELF'] . '?stkdel='.$stock[$k].'&stockid='.$stock[$k].">" . _('Remove') . "</td>  
      
       </tr>";
}
$totrow=$k;
echo '<input type="hidden" name="totrow" value=$totrow>';
$result_totsub=DB_query("SELECT SUM(amount) FROM bio_temppropsubsidy WHERE leadid=$leadid",$db);
$myrow_totsub=DB_fetch_array($result_totsub);
$result_totprice=DB_query("SELECT SUM(tprice) FROM bio_temppropitems WHERE leadid=$leadid",$db);
$myrow_totprice=DB_fetch_array($result_totprice);                                                                    

$benshare=$myrow_totprice[0]-$myrow_totsub[0];
$projectcost=$benshare + $myrow_totsub[0];

 echo"<tr><td colspan=4>Total Subsidy<input type='text' readonly name='subsidy' id='subsidy' value=".$myrow_totsub[0]."></td>  
 <td align=right colspan=4>Beneficiary Share<input type='text'  readonly name='beneficiary_share' id='beneficiary_share' value=".$benshare.">    
 </td></tr>";
 

 echo "<tr><td colspan=3><input type='submit' id=\"".$leadid."\" name='submit' value='Save this proposal'></td>; 
 <td align=right colspan=4>Total Project Cost<input type='text'  readonly name='total_prjcost' id='total_prjcost' value=".$projectcost.">    
 </td></tr>";

 echo "</table>";    
  /*echo "<td colspan=3>      
  <td class=number><input size=9 style=\"text-align: right\" type=\"text\" name=\"subsidy".$k."\" id=\"subsidy".$k."\" value=".$myrow4['subsidy']." ></td>  
  </td></tr>";         */
                                                                                                              
 // echo '<input type="text" name="flag" id="flag" value="'.$flag.'" />'; 
  }
  
 function displayedit($leadid,$propid,$db)
{
    
      $sql4="SELECT stockid,description,qty,price,tprice,subsidy,netprice FROM bio_temppropitems where leadid=".$leadid;
      $result4=DB_query($sql4,$db);

//echo "<input type=\"hidden\" name=\"lead\" id=\"lead\" value='".$lead."'>";
echo "<table style='width:800px' border='1'>";
echo "<tr><th width=50>Stock Id</th><th width=200>Item Description</th><th>qty</th><th>Unit Price</th><th>Gross Price</th></tr>";
$k=0;
                                        
 while ($myrow4=DB_fetch_array($result4))   {     
  $tprice=$myrow4['qty']*$myrow4['price'];
    $k++;
 $stock[$k]=$myrow4['stockid'];
    $item=$stock[$k];
                                                                                                                                                                                     
   echo "<tr><td>".$stock[$k]."</td><td>".$myrow4['description']."</td>                                                                                                             
       <td><input size=3 style=\"text-align: right\" type=\"text\" name=\"qty".$k."\" id=\"qty".$k."\" value=".$myrow4['qty']." onchange=updatetotalitemprice(".$k.",".$leadid.",'".$stock[$k]."')></td>      
       <td class=number><input size=9 style=\"text-align: right\" type=\"text\" name=\"price".$k."\" id=\"price".$k."\" value=".$myrow4['price']." onchange=updatetotalitemprice(".$k.",".$leadid.",'".$stock[$k]."')></td>
       <td class=number><input size=9 style=\"text-align: right\" type=\"text\" name=\"tprice".$k."\" id=\"tprice".$k."\" value=".$tprice.">
       <input size=9 style=\"text-align: right\" type=\"hidden\" name=\"subsidy".$k."\" id=\"subsidy".$k."\" value=".$myrow4['subsidy']." readonly></td>          
       <td align=center><a  style='cursor:pointer;'  id='$stock[$k]' onclick='addfund(this.id,$leadid,1)'>" . _('Select Funding Agency') . "</a></td>
        <td align=center><a href=". $_SERVER['PHP_SELF'] . '?stkdel='.$stock[$k].'&stockid='.$stock[$k].">" . _('Remove') . "</td>  
      
       </tr>";
}

$result_totsub=DB_query("SELECT SUM(amount) FROM bio_temppropsubsidy WHERE leadid=$leadid",$db);
$myrow_totsub=DB_fetch_array($result_totsub);
$result_totprice=DB_query("SELECT SUM(tprice) FROM bio_temppropitems WHERE leadid=$leadid",$db);
$myrow_totprice=DB_fetch_array($result_totprice);

$benshare=$myrow_totprice[0]-$myrow_totsub[0];
$projectcost=$benshare + $myrow_totsub[0];

 echo"<tr><td colspan=4>Total Subsidy<input type='text' readonly name='subsidy' id='subsidy' value=".$myrow_totsub[0]."></td>  
 <td align=right colspan=4>Beneficiary Share<input type='text'  readonly name='beneficiary_share' id='beneficiary_share' value=".$benshare.">    
 </td></tr>";
 
 
/*echo "<tr><td colspan=2>
      <input type='button' id=\"".$leadid."\" value='Add Item'  onclick='showItems(1);'>
      </td>"; */
 echo "<tr><td colspan=3><input type='submit' id=\"".$leadid."\" name='edit' value='Save this proposal'></td>; 
 <td align=right colspan=4>Total Project Cost<input type='text'  readonly name='total_prjcost' id='total_prjcost' value=".$projectcost.">    
 </td></tr>";

 echo "</table>";    
  /*echo "<td colspan=3>      
  <td class=number><input size=9 style=\"text-align: right\" type=\"text\" name=\"subsidy".$k."\" id=\"subsidy".$k."\" value=".$myrow4['subsidy']." ></td>  
  </td></tr>";         */
                                                                                                              
  echo '<input type="hidden" name="propid" id="propid" value="'.$propid.'" />'; 
  } 
  
  
 
if(isset($_GET['stockid'])) 
{   
    $leadid=$_SESSION['leadid'];
    $propid=$_SESSION['propid'];
    $stockid=$_GET['stockid']; 
    $flag=$_GET['flag'];  
    
      if (checkduplicateentries($stockid,$leadid,$db)==0) {
    additems($stockid,$leadid,$flag,$db);  
    } 
    else 
    {
        echo "Item is already selected.";  
        if($flag==0)
        {
            display($leadid,$flag,$db);  
        }
        else
        { 
            displayedit ($leadid,$propid,$db);                    
        }
    
    }                       
}        
if(isset($_GET['FAchange'])) 
{  
   $propid=$_SESSION['propid']; 
   $leadid=$_GET['FAchange']; 
   $flag=$_GET['flag'];  
    if($flag==0)
    {
    display($leadid,$flag,$db); 
        
    }  
   else
   { 
     displayedit ($leadid,$propid,$db);  
                   
   }
 }   
 
if(isset($_GET['Echange'])) 
{   
   $propid=$_SESSION['propid']; 
   $leadid=$_GET['Echange']; 
   $flag=$_GET['flag'];  
   if($flag==0)
   {
      display($leadid,$flag,$db); 
   }
   else
   {
       displayedit ($leadid,$propid,$db); 
        
   }
    
}  


if((isset($_GET['SelectedType1'])) &&  (isset($_GET['propid']))) 
{
    
    $leadid=$_GET['SelectedType1'];
    
    $propid=$_GET['propid'];
    
  $sql4="DELETE FROM bio_temppropitems where leadid=$leadid";
   $result4=DB_query($sql4,$db);
   $sql5="DELETE FROM bio_temppropsubsidy where leadid=$leadid"; 
   $result5=DB_query($sql5,$db);      
    
  
    
 $sql="SELECT * FROM bio_lsgplantdetails,bio_lsgplant WHERE bio_lsgplantdetails.propid=bio_lsgplant.propid AND bio_lsgplant.leadid=$leadid AND bio_lsgplant.propid=$propid";   
 $result=DB_query($sql,$db); 
 
 
 while ($myrow = DB_fetch_array($result)) { 
 
 $sql1="INSERT INTO bio_temppropitems(stockid,leadid,description,qty,price,tprice) VALUES('".$myrow[stockid]."','".$myrow[leadid]."','".$myrow[description]."','".$myrow[qty]."','".$myrow[price]."','".$myrow[tprice]."')";   
 $result1=DB_query($sql1,$db);   
}
$sql_subsidy="SELECT * FROM bio_fundingagency WHERE  bio_fundingagency.leadid=$leadid  AND  bio_fundingagency.propid='".$propid."'";

$result_subsidy=DB_query($sql_subsidy,$db);
while($myrow=DB_fetch_array($result_subsidy)) 
 {
     
  $sql2="INSERT INTO bio_temppropsubsidy(leadid,scheme,amount) VALUES('".$myrow[leadid]."','".$myrow[funding_agency]."','".$myrow[amount]."')";   
 $result1=DB_query($sql2,$db);
      
}
   $_SESSION['propid']=$propid;
  displayedit($leadid,$propid,$db); 
}

            

        
echo"</fieldset>";
//echo"</form>";
//echo "</td></tr>";
echo"</div>";
//echo "</td></tr>";
//echo"</table>";
 
/*echo "</td></tr>";
echo "</table>";
echo "</div>";   */


//------------------------------------------------PROPOSAL DETAILS----------------------------------------------------------------------//


echo'<div id="cpgrid">';
echo"<fieldset style='float:middle;width:865px;'><legend>Proposal Details</legend>";  
 
echo"<table style='border:1px solid #F0F0F0;width:100%'>"; 


 $sql="SELECT bio_lsgplant.propid AS propid,
                                          bio_lsgplant.propdate AS propdate,
                                          bio_lsgplantdetails.stockid AS stockid,
                                          
                                          stockmaster.longdescription AS longdescription,
                                          bio_lsgplant.totprice,
                                          bio_lsgplant.leadid,
                                          COUNT(bio_lsgplant.propid) AS count                                
                                     FROM bio_lsgplant,bio_lsgplantdetails,stockmaster
                                    WHERE bio_lsgplantdetails.propid=bio_lsgplant.propid
                                      AND stockmaster.stockid=bio_lsgplantdetails.stockid
                                      AND bio_lsgplant.leadid=$leadid
                                 GROUP BY bio_lsgplant.propid HAVING count>0";
             
              
$result = DB_query($sql,$db);  


echo "<div style='overflow:scroll;height:10px'>";
    echo '<table class="selection" width="100%">';
    echo '<tr>  
        <th>' . _('SlNo') . '</th>
        <th>' . _('Plant Name') . '</th>
        <th>' . _('Created On') . '</th>
        <th>' . _('Total Project Cost') . '</th> 
        </tr>';
 $k=0;       
 $slno=0;
while ($myrow = DB_fetch_array($result)) {
    if ($k==1){
        echo '<tr class="EvenTableRows">';
        $k=0;
    } else {
        echo '<tr class="OddTableRows">';
        $k=1;
    }
    $slno++;
    
printf('<td>%s</td>
            <td>%s</td>  
            <td>%s</td>
            <td>%s</td>
            <td><a href="%sSelectedType1=%s&propid=%s">' . _('Edit') . '</td>          
       ',
        $slno,
        $myrow[3],
        $myrow[1],
        $myrow[4], 
        $_SERVER['PHP_SELF'] . '?', 
        $myrow[5],$myrow[0],
        $_SERVER['PHP_SELF'] . '?', 
        $myrow[0] 

        )
  
        ;  
        $leadi=$myrow['leadid'] ;
        $pro=$myrow['propid'];
        echo     '<td><a href="dealerproposal.php?SelectedType1='.$leadi.'&propid='.$pro.'&delete=50">' . _('DELETE') . '</td>  </tr>   ';
    
}    
    
 

echo"</div>";
echo"</fieldset>";
echo"</table>";
echo"</form>"; 

?>

<script>

$(document).ready(function() {
$("#proposalgrid").hide();
$('#shwprint').click(function() {
        $('#proposalgrid').slideToggle('slow',function(){});
       $('#leadgrid').slideToggle('slow',function(){});
    });

});

function passid(str){

location.href="?lead=" +str;
}

function saveproposal(lead){ //var a="#"+str;
//$(a).hide();
// alert(str);

//$("#grid").hide();
if (lead=="")
  {
  document.getElementById("sellist").innerHTML="";
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
    document.getElementById("sellist").innerHTML=xmlhttp.responseText;

    }
  }
xmlhttp.open("GET","bio_saveproposal.php?lead=" + lead +"&flag2="+flag2);
xmlhttp.send();

}


function modifyproposal(lead,propid){ 
    var answer = confirm("Do you want to Save this as a new proposal?");
    if (answer){
        var proposal=0;
    }else{
        var proposal=propid;
    }
    
if (lead=="")
  {
  document.getElementById("sellist").innerHTML="";
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
    document.getElementById("sellist").innerHTML=xmlhttp.responseText;

    }
  }
xmlhttp.open("GET","bio_modifyproposal.php?lead=" + lead + "&propid=" + proposal);
xmlhttp.send();

}
 

function updatetotalitemprice(k,lead,stock){ 

/*alert(lead);
alert(k);
alert(stock);  */

if (stock=="")
  {
  document.getElementById("tprice").value="";
  return;
  }
   var s=stock;
 var q=document.getElementById('qty'+k).value;            
 var p=document.getElementById('price'+k).value;          
 var t=document.getElementById('tprice'+k).value=q*p;     
 var sub1=document.getElementById('subsidy'+k).value;     
 var sub=q*sub1;
 var n=document.getElementById('netprice'+k).value=t-sub;
 
/* var b=document.getElementById('beneficiary_share'+k).value=t-sub1;       
 var tprj=document.getElementById('total_prjcost'+k).value=b+sub1; */
 

 
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
 
  document.getElementById("tprice").value=xmlhttp.responseText;     
     
    }
  }
xmlhttp.open("GET","bio_updateproptempprice.php?stock="+s+"&qty="+q+"&price="+p+"&tprice="+t+"&lead="+lead+"&subsidy="+sub+"&nprice="+n);
xmlhttp.send();

if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp2=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp2=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp2.onreadystatechange=function()
  {
  if (xmlhttp2.readyState==4 && xmlhttp2.status==200)
    {
    document.getElementById("subsidy").value=xmlhttp2.responseText;
    }
  }
xmlhttp2.open("GET","bio_updateproptempsubsidy.php?stock="+s+"&qty="+q+"&price="+p+"&tprice="+t+"&lead="+lead+"&subsidy="+sub+"&nprice="+n);
xmlhttp2.send();


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
    document.getElementById("netprice").value=xmlhttp1.responseText;
    }
  }
xmlhttp1.open("GET","bio_updateproptempnetprice.php?stock="+s+"&qty="+q+"&price="+p+"&tprice="+t+"&lead="+lead+"&subsidy="+sub+"&nprice="+n);
xmlhttp1.send();

}

function selectcategory(lead,first,flag2)  {
  controlWindow=window.open("bio_biopropadditem.php?ledid="+lead+"&first="+first+"&flag2="+flag2,"selcaty","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=700,height=400");
}

function selectpropitems(str) {
 controlWindow=window.open("bio_showselpropitems1.php?ledid="+str,"proplist","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=700,height=600");
}

function saveproposal2(lead) {
 controlWindow=window.open("bio_saveproposa2.php?lead="+lead,"saveprop","toolbar=yes,location=no,directories=no,status=no,menubar=yes,scrollbars=no,resizable=no,width=700,height=500");
 //window.location="bio_saveproposal.php?lead="+lead;
}

function addSubsidy(lead,item,flag,first,add)  {
  controlWindow=window.open("bio_biopropaddsubsidy.php?ledid="+lead+"&item="+item+"&flag="+flag+"&first="+first+"&add="+add,"addsubsidy","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=700,height=400");
}


/*function showItems(str,str2,str3){ 
controlWindow=window.open("bio_selectplantfor_cp.php?lead="+str+"&first="+str2+"&budgetinitial="+str3,"selplant","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=700,height=400");
}   */


function viewProposal(str1,str2){ 
if (str1=="")
  {
  document.getElementById("panel").innerHTML="";
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
    document.getElementById("panel").innerHTML=xmlhttp.responseText;

    }
  }
xmlhttp.open("GET","bio_editstdproposals.php?lead=" + str1 + "&propid=" + str2);
xmlhttp.send();

}



function printProposal(str1,str2){
location.href="?leadid=" + str1 + "&propid=" +str2; 
} 




function displayAlertMessage(message) {
    var timeOut = 100
    jQuery('#messageBox').text(message).fadeIn()
    jQuery('#messageBox').css("display", "block")
    setTimeout(function() {
    jQuery('#messageBox').fadeOut()
    jQuery('#messageBox').css("display", "none")
    }, timeOut * 1000);
}

function Filtercategory()       {

var str= document.getElementById("maincatid").value;
    
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
xmlhttp.open("GET","bio_maincatsubcatcp.php?maincatid="+str,true);
xmlhttp.send(); 
    

}


 function showItems(str)
{               
    var str1=document.getElementById("caty").value; 
   myRef = window.open("dealerplantselect.php?categoryid="+str1+"&flag="+str,"lsgplant","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=700,height=400");
   
}   

  function addfund(str,str2,str3) 
 {
         //  alert(str3);
  controlWindow=window.open("bio_addfundingagency.php?stockid="+str+ "&leadid="+str2+ "&flag="+str3, "addfund","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=700,height=400");
  
 }              
 
</script>



