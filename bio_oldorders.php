<?php
  $PageSecurity = 80;
include('includes/session.inc');
$title = _('Old Order Details');
include('includes/header.inc');
echo '<a href="index.php">Back to Home</a>';  
echo '<br /><br />'; 


if($_GET['Debtorid']!="")
{
   $_SESSION['debtorid']=$_GET['Debtorid']; 
   $debtorno=$_SESSION['debtorid'];
}    
else
{
   $debtorno=$_SESSION['debtorid'];  
}

$collectedBy=$_SESSION['UserID']; 
$createdate=date("Y-m-d");

if (isset($_POST['submit'])) {  
$plantid=$_POST['plant'];  

$sql1="SELECT stockmaster.longdescription FROM stockmaster WHERE stockmaster.stockid='".$plantid."'";
$result1=DB_query($sql1, $db);
$row2=DB_fetch_array($result1) ; 
$desp=$row2['longdescription'];  

    
      if($_POST['description']==""){
          $_POST['description']=$desp;
      }
      
      
    $date1= FormatDateForSQL($_POST['df1']);
    if($_POST['status']=='Plant not Working')
    {   $var=1;
    }else{
        $var=2;
    }
    
    echo'<input type=hidden name=status id=status value='.$var.'>';
    echo'<input type=hidden name=debtorno id=debtorno value='.$debtorno.'>';
    
   
    
   
   $sql = "INSERT INTO bio_oldorders(debtorno,client_id,plantid,description,quantity,price,
                       currentstatus,installationdate,createdby,createdon)
                                  VALUES ('" . $debtorno . "','" . $_POST['details'] . "','" . $_POST['plant'] . "','" . $_POST['description'] . "',
                                  '" . $_POST['quantity'] . "','" . $_POST['price'] . "','" . $_POST['status'] . "',
                                  '" . $date1 . "','" . $collectedBy . "','" . $createdate . "')";                                           
        $result = DB_query($sql,$db); 
        
        
        
        
        $orderno=DB_Last_Insert_ID($Conn,'bio_oldorders','orderno');
        
        $sql3="SELECT debtorno FROM bio_oldorders WHERE orderno=$orderno";
        $result3=DB_query($sql3,$db);
        $row3=DB_fetch_array($result3);
        $debno=$row3['debtorno'];
        
        
        $first_letter=$debno[0];
        
        if($first_letter=='D'){
           $sql2="SELECT doc_no FROM bio_document_master WHERE enqtypeid=1"; 
        }elseif($first_letter=='C'){
           $sql2="SELECT doc_no FROM bio_document_master WHERE enqtypeid=2"; 
        }
        $result2=DB_query($sql2,$db);
        while($row2=DB_fetch_array($result2))
        {
            $sql_oldorderdoc="INSERT INTO bio_oldorderdoclist (orderno,docno,status) VALUES ($orderno,".$row2['doc_no'].",0)";
           DB_query($sql_oldorderdoc,$db);
        }
        
        
          
  ?>
  <script>  
  
  var str=<?php echo $_POST['status']  ?>;  
//  alert(str);
  var str1=document.getElementById("debtorno").value;  
  
  if(str==9)
  {
                      //          alert(str1);    
      window.open("bio_incidentRegister.php?debtorno=" + str1 );
  }
  
  window.close();
  </script>               
  
  <?php  
              
    
}
                                      


  echo "<table border=0 style='width:40%';><tr><td>";  
  echo "<fieldset style='width:90%;height:350px'>";
  echo "<legend><h3>Old Order Details</h3></legend>";
  echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '">'; 
  echo '<table width=35%>'; 
  

   echo '<tr><td >Client Details</td>'; 
echo '<td><select name="details" id="details" style="width:280px" >';
$sql1="select * from bio_clientdetails";
$result1=DB_query($sql1,$db);
 $f=0;    
while($row1=DB_fetch_array($result1))
{
    if ($row1['client_id']==$priority) 
    {
       
    echo '<option selected value="';
    
    } else {
        if ($f==0) 
        {
        echo '<option value="0">--SELECT--</option>';
        }
        echo '<option value="';
               $f++;   
    }
    echo $row1['client_id'].'">'.$row1['details'];
    echo '</option>';

}    
     echo '</select></td></tr>';
     
      
    echo "<tr><td>Plant category</td>";
         echo '<td><select name="CategoryID" style="width:200px" onchange="view2(this.value)" >';
 /* if($_POST['CategoryID'])
   {*/
  echo $sql = "SELECT DISTINCT `make`
FROM `stockitemproperty`
WHERE make != 'null' ";
   $ErrMsg = _('The stock categories could not be retrieved because');
   $DbgMsg = _('The SQL used to retrieve stock categories and failed was');
   $result = DB_query($sql,$db,$ErrMsg,$DbgMsg);
   echo '<option value=0></option>';
while ($myrow=DB_fetch_array($result)){
    {
        echo '<option value="'. $myrow['make'] . '">' . $myrow['make'].'</option>';
    }
   
}
  
  
 /*  $sql1="SELECT distinct stockmaster.description,stockmaster.stockid from stockmaster,stockcategory,bio_maincat_subcat where stockmaster.categoryid in ('P-LSGD','PDO','OP','FD','FRP-GO','GD','LD','MD','RCC-MS') order by stockmaster.longdescription asc";
      $result1=DB_query($sql1, $db);
      $desp=$myrow1['longdescription'];    
   echo '<tr><td>Plant&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>'; 
   echo '<td><select name="plant" id="plant" style="width:280px" onchange="showdescription()">';
    $f=0;
    
    while($myrow1=DB_fetch_array($result1))
    { 
    if ($myrow1['stockid']==$_POST['plant']) 
    {
    echo '<option selected value="';
    
    } else {
        if ($f==0) 
        {
        echo '<option value="0">--SELECT--</option>';
        }
        echo '<option value="';
        $f++;
    }
    echo $myrow1['stockid'] . '">'.$myrow1['description'];             
    echo '</option>';
    }
                                                                     */
                                                                     
     echo '</select></td></tr>'; 
   echo '</tr><tr><td>Plant</td><td><select name="plant" id="plant"  style="width:200px">';
    if($_POST['CategoryID'])
    {
   $sql="SELECT stockitemproperty.`stockid` , stockitemproperty.capacity
FROM `stockitemproperty` , stockmaster
WHERE stockitemproperty.stockid = stockmaster.stockid
AND make LIKE '".$_POST['CategoryID']."'";    
   $ErrMsg = _('The stock categories could not be retrieved because');
   $DbgMsg = _('The SQL used to retrieve stock categories and failed was');
   $result = DB_query($sql,$db,$ErrMsg,$DbgMsg);
    }
       echo '<option></option>';
   while ($myrow=DB_fetch_array($result))
        {
          if($myrow['stockid']==$_POST['combo2'] || $myrow['stockid']==$_GET['select'])
            {
             echo '<option selected value="'. $myrow['stockid'] . '">' . $myrow['capacity'].'</option>';
            }
            else
             {
              echo '<option value="'. $myrow['stockid'] . '">' . $myrow['capacity'].'</option>';
              }
    
        }                                                                  
  echo '</select></td></tr></table>'; 
  echo '<div id="plantdiv"><table width=85%><tr><td>Plant Description</td><td><textarea name="description" id="description" rows="5" cols="35">'.$description.'</textarea> </td></tr></table></div>';
  echo'<table>';
  echo '<tr><td>Quantity</td><td><input type="text" name="quantity" id="quantity"  style="width:280px" ></td></tr>';   
  echo '<tr><td>Price</td><td><input type="text" name="price" id="price"  style="width:280px" ></td></tr>';  

  
echo '<tr><td >Current Status</td>'; 
echo '<td><select name="status" id="status" style="width:280px" >';
/*$sql1="select * from bio_plantstatus";
$result1=DB_query($sql1,$db);
 $f=0;    
while($row1=DB_fetch_array($result1))
{
    if ($row1['plantstatus']==$_POST['status']) 
    {
       
    echo '<option selected value="';
    
    } else {
        if ($f==0) 
        {
        echo '<option value="0">--SELECT--</option>';
        }
        echo '<option value="';
               $f++;   
    }
    echo $row1['id'].'">'.$row1['plantstatus'];
    echo '</option>';

}    
*/

echo '<option value="0">--SELECT--</option>';
echo '<option value="1">Working</option>';
echo '<option value="9">Not Working</option>';
echo '<option value="3">Phone Not Working</option>';
echo '<option value="10">Phone Ringing no response</option>'; 
echo '</select></td></tr>';

    $Currentdate=Date("d/m/Y"); 
  echo '<tr><td>Date of Installation</td><td><input type="text" style="width:280px" id="df1" class=date alt='.$_SESSION['DefaultDateFormat'].' name="df1" onChange="return isDate(this, this.value, '."'".$_SESSION['DefaultDateFormat']."'".')"  ></td></tr>';        //onfocus=check(this.value,"'.$debtorno.'");  
//   echo '<tr><td>Financial Year</td><td><input type="text" readonly style="width:280px" id="fin" class=date alt='.$_SESSION['DefaultDateFormat'].' name="fin" value='.$Currentdate.' ></td></tr>';
  echo'<tr><td id=date></td></tr>'; 
  
  echo"<input type='hidden' name='desp' id='desp' value='".$desp."'>"; 
  echo'<tr><td></td><td>  <input type="submit" name="submit" value="' . _('Save') . '" ></td></tr>'; 
  

          
  echo '</table>';
  echo '</form></fieldset>';
  
?>

<script type="text/javascript">  







function check(str,str1){
        
            //alert(str);
if (str=="")
  {
  document.getElementById("date").innerHTML="";     //editleads
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
        //document.getElementById("date").innerHTML=xmlhttp.responseText; 
    var id=document.getElementById("date").value=xmlhttp.responseText;
    if(id!=0){
//                alert(id);
        alert("Customer already exists");
//        myRef = window.open("bio_editleadsnew.php?q=" + id + "&en=" + enquiry);
        controlWindow=window.open("bio_oldorderlist.php?custid=" + id ,"oldorderlist","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,width=980,height=500");
    } 
    }
  } 
xmlhttp.open("GET","bio_olddupcheck.php?date=" + str + "&debno=" + str1,true);  
xmlhttp.send();  
}


function showdescription()
{
    
var dp=document.getElementById('plant').value;    
 if(dp!=0){
       $('#plantdiv').hide();
        //  alert(dp);
 }   
  else{
           $('#plantdiv').show(); 
    //alert("kuiiiiiiiiiiii");
  }  
    
}
 function view2(str1)
{

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
     document.getElementById("plant").innerHTML=xmlhttp.responseText; 
     
    }            
  } 
xmlhttp.open("GET","oldajax.php?subsubcatid="+str1,true);
xmlhttp.send();        
}
     
</Script>