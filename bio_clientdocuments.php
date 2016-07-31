<?php
  $PageSecurity = 80;
include('includes/session.inc');
$title = _('Client Documents');  
include('includes/header.inc');
echo '<p class="page_title_text"><img src="'.$rootpath.'/css/'.$theme.'/images/maintenance.png" title="' . _('Client Documents')
    . '" alt="" />' . _('Client Documents Setup') . '</p>';
echo '<div class="page_help_text">' . _('Add / edit / delete Client Documents') . '</div><br />';
echo '<a href="index.php">Back to Home</a>';  
   
 if(isset($_GET['delete']))
  { 
      $did=$_GET['delete'];    
      $sql= "delete from bio_clientdocuments where bio_clientdocuments.id = $did";
     $result=DB_query($sql,$db); 
}
if(isset($_GET['edit'])){
       $eid=$_GET['edit']; 
$sql="SELECT * FROM bio_clientdocuments  WHERE bio_clientdocuments.id = $eid";
$result=DB_query($sql,$db);
$myrow2=DB_fetch_array($result);

$doc_name=$myrow2['doc_id']; 
$client=$myrow2['client_id']; 
  
} 
if(!isset($_POST['submit'])){ 

 $tempdrop="DROP TABLE IF EXISTS bio_clienttemp";
 DB_query($tempdrop,$db);

 $temptable="CREATE TABLE bio_clienttemp (
            temp_id INT NOT NULL AUTO_INCREMENT ,
            doc_id VARCHAR(50) NULL ,
            PRIMARY KEY ( temp_id ))";
DB_query($temptable,$db);
}
   
   
      
if (isset($_POST['submit'])){
      $_POST['SelectedType'];
      
    if ($_POST['SelectedType']!=""){
     $sql = "UPDATE bio_clientdocuments
                    SET 
            client_id='".$_POST['client']."' ,doc_id='".$_POST['document']."'
            WHERE id =" .$_POST['SelectedType'];
     $result=DB_query($sql,$db);
 }
 else {
  $sql5="SELECT *
                FROM bio_clienttemp";
        $result5=DB_query($sql5,$db);
    
        while($myrow5=DB_fetch_array($result5))     {
            $doc_id=$myrow5['doc_id'];
            $c_id=$_POST['client'];
            $sql6="INSERT INTO bio_clientdocuments(client_id,
                                                   doc_id)
                                           VALUES(".$c_id.",
                                                 ".$doc_id.")";
            $result6=DB_query($sql6,$db);
        }
 }
    
       $tempdrop="DROP TABLE IF EXISTS bio_clienttemp";
 DB_query($tempdrop,$db);

 $temptable="CREATE TABLE bio_clienttemp (
            temp_id INT NOT NULL AUTO_INCREMENT ,
            doc_id VARCHAR(50) NULL ,
            PRIMARY KEY ( temp_id ))";
DB_query($temptable,$db); 
}


echo '<table style=width:45%><tr><td>';
echo '<fieldset style="height:250px">';
echo '<legend><b>Client Documents</b></legend>';  
echo "<table>";     
   
echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '">'; 
 
echo '<br><br><table class="selection">';
   echo '<tr><td>&nbsp;</td></tr><tr><td style="width:30%">Client Type:</td>';
    echo  '<td>';
    
    echo '<select name="client" id="client" style="width:190px">';
    $sql1="SELECT * FROM bio_enquirytypes"; 
    $result1=DB_query($sql1,$db);
    $f=0;
 
    while($myrow1=DB_fetch_array($result1))
    { 
    if ($myrow1['enqtypeid']==$client) 
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
    echo $myrow1['enqtypeid'] . '">'.$myrow1['enquirytype'];
    echo '</option>';
    }
     
    echo '</select>';    
    echo '</td></tr>';
    
          
   $sql_cat="SELECT subcatid FROM bio_maincat_subcat WHERE maincatid=1";
$result_cat=DB_query($sql_cat,$db);

$cat_arr=array();
while($row_cat=DB_fetch_array($result_cat)) 
{  
  $cat_arr[]="'".$row_cat['subcatid']."'";
  $plant_array=join(",", $cat_arr); 
} 
      $sql1="SELECT * 
      FROM stockmaster
WHERE stockmaster.categoryid IN ($plant_array)";
      $result1=DB_query($sql1, $db);
 
 
    
    echo '<tr><td>' . _('Document') . ':</td>'; 
    echo '<td><select name="document" id="document" style="width:190px">';
   
$sql1="select * from bio_documents where doc_category=1";
$result1=DB_query($sql1,$db);
    $f=0;   
while($row1=DB_fetch_array($result1))
{
    if ($row1['doc_no']==$doc_name) 
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
    echo $row1['doc_no'] . '">'.$row1['doc_name'];
    echo '</option>';

}
    echo '</select> <input type="button" name="submit" value="Add" onclick="addClientdoc()"></td></tr>';    
    
    echo '<tr><td><input type="hidden" name="SelectedType" value='.$eid.'>&nbsp;</td></tr>';
    echo "</table>";
    echo"<br><div id='taskdiv'></div>";  
     echo "<table>";
      echo'<tr><td></td><td> <input type="Submit" name="submit" value="' . _('Save') . '" onclick=" if(validate()==1)return false"></td></tr>';  
     echo "</table>";                                   

      echo '</form> ';
      
          echo '&bull; <a href="' . $rootpath . '/bio_schemedocuments.php">' . _('Scheme Documents') . '</a>';
     echo '</fieldset>';  
       
           
      
            
      echo "<fieldset style='width:560px'>";
      echo "<legend><h3>Client Documents</h3></legend>";
      echo "<div style='overflow:scroll;height:150px'>";
      echo "<table class='selection' style='width:100%'>";
      echo '<tr>  <th>' . _('Slno') . '</th>  
      <th>' . _('Client Type') . '</th>
      <th>' . _('Document') . '</th>
      </tr>';
               
  $sql1="SELECT 
  bio_enquirytypes.enquirytype,
  bio_clientdocuments.client_id, 
  bio_clientdocuments.doc_id,
  bio_clientdocuments.id,
  bio_documents.doc_name  
  FROM 
      bio_documents,
      bio_clientdocuments,
      bio_enquirytypes
  WHERE bio_clientdocuments.client_id=bio_enquirytypes.enqtypeid 
  AND   bio_clientdocuments.doc_id= bio_documents.doc_no";
  $result1=DB_query($sql1, $db);  
 $k=0 ;$slno=1; 
  while($row1=DB_fetch_array($result1) )
  
  { 
          if ($k==1){
        echo '<tr class="EvenTableRows">';
        $k=0;
    } else {
        echo '<tr class="OddTableRows">';
        $k=1;
    }  
      
      $client=$row1['enquirytype'];
      $document=$row1['doc_name'];  
       $id=$row1['id']; 
      
      
      
      
      
echo"<tr style='background:#A8A4DB'><td>$slno</td>
                                    <td>$client</td>
                                    <td>$document</td> 
                                    
                                    <td><a href='#' id='$id' onclick='edit(this.id)'>Edit</a></td>
                                    
                                    <td><a href='#' id='$id' onclick='dlt(this.id)'>Delete</a></td>      
                                    
                                    </tr>";  $slno++;     

} 
      
?>

      
<script>

function dlt(str){
        //alert(str);
location.href="?delete=" +str;         
 
}

 function edit(str)
 {
//   alert(str);  
location.href="?edit=" +str;         
 
}

      
 function addClientdoc()
{

    var str1=document.getElementById("document").value;
   
//   alert(str1);

if(str1==0)
{
alert("Select a Document"); document.getElementById("document").focus();  return false;  
}
//          alert(str1); 
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
    document.getElementById("taskdiv").innerHTML=xmlhttp.responseText;
    //document.getElementById('document').value="";
    }
  }        
//alert(str1); 
xmlhttp.open("GET","bio_docClient.php?document=" + str1 ,true);
xmlhttp.send();    

}     
      
  

function validate()
{     
  

var f=0;
var p=0;
if(f==0){f=common_error('client','Please select the Client');  if(f==1){return f; }  }
if(f==0){f=common_error('document','Please select a Document');  if(f==1){return f; }  }  

}</Script>