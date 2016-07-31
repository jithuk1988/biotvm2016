<?php
$PageSecurity = 40;
include('includes/session.inc');
$title = _('Stock Edit');
include('includes/header.inc'); 
$pagetype=1;
include('includes/sidemenu2.php');

?>

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


function Filtercategory2()       {

var str= document.getElementById("maincatid").value;
var str1= document.getElementById("SubCategoryID").value;
   // alert("gdhdd");
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

 /* 
function validate()
{
   
 var f=0; 
if(f==0){f=common_error('room1','Please Enter room');  if(f==1) { return f; }}     
if(f==0){f=common_error('rack1','Please Enter rack');  if(f==1) { return f; }}          
if(f==0){f=common_error('bin1','Please Enter bin');  if(f==1) { return f; }}
if(f==0)
{ 
    var x=document.getElementById('lastfileno1').value;
    if(x!=""){
       var l=x.length;
            if(isNaN(x))
           {  f=1;
              alert("Enter a numeric value"); document.getElementById('lastfileno1').focus();
              if(x=""){f=0;}
              return f; 
           }  
    }     
}        
}
function dlt(str){
location.href="?delete=" +str;
}
function custfilename_print(){
    str1=document.getElementById("lsgType").value;
    str2=document.getElementById("lsgName").value;
    str3=document.getElementById('Districts').value;
    str4=document.getElementById('year').value;
    str5=document.getElementById('loc').value;
    if(str1==3)  {str6=document.getElementById("gramaPanchayath").value;}
    controlWindow=window.open("bio_custfilename_print.php?lsgtype=" + str1 + "&lsgname1=" + str2 + "&dist=" + str3 + "&year1=" +str4 + "&location=" + str5 + "&grama2=" + str6 ,"idproof","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=900,height=400"); 
}

function replace_html(id, content) {
    document.getElementById(id).innerHTML = content;
}

var progress_bar = new Image();
progress_bar.src = '4.gif';

function show_progressbar(id) {
    replace_html(id, '<img src="4.gif" border="0" alt="Loading, please wait..." />Loading...');
}


function showitem(str){ 

if (str=="")
  {
  document.getElementById("showstate").innerHTML="";
  return;
  }
show_progressbar('showstate');

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
    document.getElementById("state").focus();
    }
  }
xmlhttp.open("GET","bio_itmlist.php?category=" + str,true);
xmlhttp.send();
}



function showdistrict(str){       //alert(str);
str1=document.getElementById("country").value;
//alert(str1);
if (str=="")
  {
  document.getElementById("showdistrict").innerHTML="";
  return;
  }
show_progressbar('showdistrict');
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
           document.getElementById('Districts').focus();

    }
  }
xmlhttp.open("GET","customerfilestate.php?state=" + str + "&country1=" + str1,true);
xmlhttp.send();

}
function showblock(str){
   
     str1=document.getElementById("country").value;
     str2=document.getElementById('state').value;
     str3=document.getElementById('Districts').value;
     if (str3=="")
     {
     alert("Please select a district");    
     document.getElementById("Districts").focus();
     document.getElementById("lsgType").value=0;
     return;
     }
     

     if(str==1 && (str3==1 || str3==3 || str3==4 || str3==5 || str3==7 || str3==9 || str3==10 || str3==10 || str3==14)){
         
         alert("No Corporation for this district");
         document.getElementById("block").innerHTML="";
         return;
     }
//alert(str1);   alert(str2);       alert(str3);
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
xmlhttp.open("GET","customerlsgfile.php?block=" + str +"&country1="+ str1 +"&state1="+ str2 +"&district="+ str3,true);
xmlhttp.send(); 
} 

 function showgramapanchayath(str){   
   //alert(str);
     str1=document.getElementById("country").value;
     str2=document.getElementById('state').value;
     str3=document.getElementById('Districts').value;
//alert(str1);   alert(str2);       alert(str3);
if (str=="")
  {
  document.getElementById("showgramapanchayath").innerHTML="";
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
     document.getElementById("showgramapanchayath").innerHTML=xmlhttp.responseText; 
       
    }
  } 
xmlhttp.open("GET","customerlsgfile.php?grama=" + str +"&country1="+ str1 +"&state2="+ str2 +"&district="+ str3,true);
xmlhttp.send(); 
}

 function showlocation(){
     
     str1=document.getElementById("lsgType").value;
     str2=document.getElementById("lsgName").value;
   if(str1==3)  {str3=document.getElementById("gramaPanchayath").value;}
     str4=document.getElementById("country").value;
     str5=document.getElementById('state').value;
     str6=document.getElementById('Districts').value; 
     
   //alert("str");        
if (str1=="")
  {
  document.getElementById("showlocation1").innerHTML="";
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
     document.getElementById("showlocation1").innerHTML=xmlhttp.responseText; 
       
    }
  } 
xmlhttp.open("GET","customerlocfile.php?lsg="+ str1 +"&lsgname1="+ str2 +"&grama1="+ str3 + "&country1="+ str4 + "&state1="+ str5 + "&dist="+ str6,true);
xmlhttp.send(); 
}
        */

///////////////////////////////////
/*function showitem(str){
      // alert(str); 
     str6=document.getElementById('cat').value;
   //  document.getElementById("showloc").innerHTML="";
           function BeginLoading(){
   //  document.getElementById("showloc").innerHTML=""
    document.getElementById('ajaxloader').style.display = "block";
  //  xmlhttp.send();
}
     BeginLoading(); 
      //document.getElementById('ajaxloader').style.display = "block";
   // http.send(params);
   // 
   //alert(str7);  
    
if (str=="")
  {
  document.getElementById("showloc").innerHTML="";
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
        
         // document.getElementById('showloc').style.display = "block";
     document.getElementById("showloc").innerHTML=xmlhttp.responseText; 
     document.getElementById('ajaxloader').style.display = "none";
                 
    }            
  } 
 


xmlhttp.open("GET","bio_itmlist.php?category=" + str6,true);
xmlhttp.send();     
 
// 
}
function showitem2(str){
      // alert(str); 
     str6=document.getElementById('maincat').value;
   //  document.getElementById("showloc").innerHTML="";
           function BeginLoading(){
   //  document.getElementById("showloc").innerHTML=""
    document.getElementById('ajaxloader').style.display = "block";
  //  xmlhttp.send();
}
     BeginLoading(); 
      //document.getElementById('ajaxloader').style.display = "block";
   // http.send(params);
   // 
   //alert(str7);  
    
if (str=="")
  {
  document.getElementById("showloc").innerHTML="";
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
        
         // document.getElementById('showloc').style.display = "block";
     document.getElementById("showloc").innerHTML=xmlhttp.responseText; 
     document.getElementById('ajaxloader').style.display = "none";
                 
    }            
  } 
 


xmlhttp.open("GET","bio_itmlist.php?maincategory=" + str6,true);
xmlhttp.send();     
 
// 
}*/
function showitem3(str){
     
     //str6=document.getElementById('maincat').value;
   //  document.getElementById("showloc").innerHTML="";
           function BeginLoading(){
   //  document.getElementById("showloc").innerHTML=""
    document.getElementById('ajaxloader').style.display = "block";
  //  xmlhttp.send();
}
  
     BeginLoading(); 
      //document.getElementById('ajaxloader').style.display = "block";
   // http.send(params);
   // 
   //alert(str7);  
  //  alert("xggfgf");
if (str=="")
  {
       //alert("xggfgf"); 
  document.getElementById("cat").innerHTML="";
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
     //   alert("xggfgf"); 
         // document.getElementById('showloc').style.display = "block";
     document.getElementById("showdistrict").innerHTML=xmlhttp.responseText; 
     document.getElementById('ajaxloader').style.display = "none";
                 
    }            
  } 
 


xmlhttp.open("GET","bio_itmlist.php?maincat=" + str,true);
xmlhttp.send();     
 
// 
}
      function exc()
{
      controlWindow=window.open("excitem.php","Excel","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=100,height=100");

}//cdmexcelsur

  function adjust(str4)
 {
     controlWindow=window.open("StockAdjustments.php?StockID="+str4,"Adjustment","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=1200,height=600");
 }
  function edititem(str9)
 {
     controlWindow=window.open("Stocks.php?StockID="+str9,"Edit","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=1200,height=600");
 }
    function move(str91)
 {
     controlWindow=window.open("StockMovements.php?StockID="+str91,"Stock Ledger","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=1200,height=600");
 }
 
         /*
function showitemss(str){ 

if (str=="")
  {
  document.getElementById("showstate").innerHTML="";
  return;
  }
show_progressbar('showstate');

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
    document.getElementById("state").focus();
    }
  }
xmlhttp.open("GET","bio_itmlist.php?category=" + str,true);
xmlhttp.send();
}                       */

</script>


<?php
echo '<p class="page_title_text"><img src="'.$rootpath.'/css/'.$theme.'/images/customer.png" title="' . _('List of customers with file number')
    . '" alt="" />' . _('Stock Register') . '</p>';
//echo '<div class="page_help_text">' . _('Enter file location for each LSG') . '</div><br />';

/*if(isset($_GET['delete'])){ $id=$_GET['delete'];
$sql="DELETE FROM bio_district WHERE id = $id";
$result=DB_query($sql,$db);
}*/
    echo "<form action=" . $_SERVER['PHP_SELF'] . "?" . SID ." method=post>";

 echo"<table width='700px'><tr><td>";
 
 
 echo'<table class=selection >';
 
    echo'<tr><td>' . _('Main category') . ':</td>';
  echo '<td><select name="MainCategoryID" id="maincatid" style="width:200px"  onchange="Filtercategory()" onblur="Filtercategory()">';

$sql = "SELECT maincatid, 
               maincatname 
        FROM bio_maincategorymaster  where rowstatus=1
        ORDER BY maincatname desc";
$ErrMsg = _('The stock categories could not be retrieved because');
$DbgMsg = _('The SQL used to retrieve stock categories and failed was');
$result = DB_query($sql,$db,$ErrMsg,$DbgMsg);
  echo '<option value=>';
while ($myrow=DB_fetch_array($result)){
    if (isset($_POST['MainCategoryID']) AND $myrow['maincatid']==$_POST['MainCategoryID']){
        echo '<option selected value="'. $myrow['maincatid'] . '">' . $myrow['maincatname'];
    } else {
        echo '<option value="'. $myrow['maincatid'] . '">' . $myrow['maincatname'];
    }
    $MainCategory=$myrow['maincatid'];
}
/*if (!isset($_POST['MainCategoryID'])) {
    $_POST['MainCategoryID']=$MainCategory;
}
*/
echo '</select></td>';



echo'<tr><td >' . _('Sub category') . ':</td><td id="subcat"><select name="SubCategoryID" id="SubCategoryID"  style="width:200px" onchange="Filtercategory2()"  onblur="Filtercategory2()">';


echo '</select></td></tr>';

echo '<tr><td>' . _('Sub sub category') . ':</td><td id="subsubcat">';
echo '<select name="CategoryID" id="CategoryID" onchange="Filtercategory3()" onblur="Filtercategory3()">';//onChange="ReloadForm(ItemForm.UpdateCategories)

$sql = "SELECT bio_maincat_subcat.subsubcatid as 'categoryid',stockcategory.categorydescription as 'categorydescription' FROM `bio_maincat_subcat`,stockcategory where stockcategory.categoryid=bio_maincat_subcat.subsubcatid and  bio_maincat_subcat.subsubcatid!='' ";
$ErrMsg = _('The stock categories could not be retrieved because');
$DbgMsg = _('The SQL used to retrieve stock categories and failed was');
$result = DB_query($sql,$db,$ErrMsg,$DbgMsg);

while ($myrow=DB_fetch_array($result)){
    if (!isset($_POST['CategoryID']) or $myrow['categoryid']==$_POST['CategoryID']){
        echo '<option selected value="'. $myrow['categoryid'] . '">' . $myrow['categorydescription'];
    } else {
        echo '<option value="'. $myrow['categoryid'] . '">' . $myrow['categorydescription'];
    }
    $Category=$myrow['categoryid'];
}

if (!isset($_POST['CategoryID'])) {
    $_POST['CategoryID']=$Category;
}

echo '</select></td></tr>';


echo'<tr> <td colspan="4" scope="row"><center><input type="submit" name="Search" value="Search">
</center></td></tr>';

           echo"<tr><td colspan='4'><a id='exp' onclick='exc()'>Export Full Item List</a></td></tr>";

 echo"</table >"; 

  

 

   /* echo"<tr id='showstate'><td>State</td><td>";
            $sql="SELECT * FROM bio_state where cid=$cid ORDER BY stateid";*/

  if($_GET['lsg'])
  {
      echo"<table>";
     
        echo"<tr><th>Room</th><th>Rack</th><th>Bin</th></tr>";
      echo"<tr><td><input type='text' name='room1' id='room1' tabindex=4  onkeyup=''></td>";
            echo"<td><input type='text' name='rack1' id='rack1' tabindex=4 onkeyup='' ></td>";
            echo"<td><input type='text' name='bin1' id='bin1' tabindex=4 onkeyup='' ></td>";
   //echo"<td><input type='text' name='lastfileno1' id='lastfileno1' tabindex=4 value=0 onkeyup='' ></td>";
   echo"<td><input type='submit' name='submit1' id='submit1' value='submit' onclick=' if(validate()==1)return false;'></td></tr>";
      echo"</table>";
      
  }else{
      echo"<table id='showlocation1' width='750px'>";
  
      echo"</table>";
  }
  
        
   

   
      echo"<div id='showloc1' width='800px'>";
      
     
          echo" <center><div style='display:none' id='ajaxloader'><img  src='ajax-loader-large.gif' /></div></center>";
          
      
      echo"</div>";
      
      
 echo"</table></form>";

/*function getnextid($table,$field,$condition)   {
$sql2="select max($field)+1 as nextid from $table $condition";
//echo $sql;
$result=DB_query($sql2,$db);
$myrow=DB_fetch_array($result);
$nextid=$myrow[0];
echo 'nnnn'.$nextid;
return $nextid;


}*/

if(isset($_POST['Search']))
     {
         if(isset($_POST['CategoryID']))
         {
         $sql="SELECT stockmaster.stockid,stockmaster.units,stockmaster.description,locstock.quantity as qoh from stockmaster,locstock where stockmaster.categoryid='".$_POST['CategoryID']."' and stockmaster.stockid=locstock.stockid and loccode='".$_SESSION['UserStockLocation']."' Order by qoh desc";
         
        
     $result=DB_query($sql,$db);
                  $slno=1;
        echo" <center><div id='ajaxloader'><img style='display:none' src='ajax-loader-large.gif' /></div></center>";
                  echo "<table id='showloc' ><tr><th>SL NO</th><th>STOCKID</th><th>DESCRIPTION</th><th>UNITS</th><th>STOCK</th><th>Edit</th><th>Adjustment</th><th>Ledger</th></tr>";
     while($row1=DB_fetch_array($result)){
         if ($k==1)
          {
            echo '<tr class="EvenTableRows">';
            $k=0;
          }else 
          {
            echo '<tr class="OddTableRows">';
            $k=1;     
          }
          $adj=$row1['stockid']."&Description=".$row1['description'];
          echo"<td >".$slno."</td>
        <td>".$row1['stockid']."</td>
        <td>".$row1['description']."</td>
        <td>".$row1['units']."</td>
        <td>".$row1['qoh']."</td>
        <td><a href id='".$row1['stockid']."' onclick='edititem(this.id);return false;'>".Edit."</a></td>
        <td><a href id='".$adj."' onclick='adjust(this.id);return false;'>".Adjustment."</a></td>
        <td><a href id='".$row1['stockid']."' onclick='move(this.id);return false;'>".Ledger."</a></td>
        </tr>";
         $slno++;
     }
                 echo '</table>';
    
     } 
}
     
?> 
