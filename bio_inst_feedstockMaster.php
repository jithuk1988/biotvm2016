<?php
  
   $PageSecurity = 80;
 include('includes/session.inc');
 $title = _('Institution Feedsotck Master'); 
 $pagetype=3; 
 include('includes/header.inc');
 include('includes/sidemenu.php');
 
 
 echo '<center><font style="color: #333;
    background:#fff;
    font-weight:bold;
    letter-spacing:0.10em;
    font-size:16px;
    font-family:Georgia;
    text-shadow: 1px 1px 1px #666;">INSTITUTION FEEDSTOCK</font></center>';
    
    
    
    
 if(!isset($_POST['submit'])){ 

 $tempdrop="DROP TABLE IF EXISTS bio_tempfeedstock";
 DB_query($tempdrop,$db);

 $temptable="CREATE TABLE bio_tempfeedstock (
            temp_id INT NOT NULL AUTO_INCREMENT ,
            instid INT NULL ,
            feedid INT NULL ,
            PRIMARY KEY ( temp_id ))";
DB_query($temptable,$db);

}     


if(isset($_POST['submit']))     
{
    
    $sql_tempselect="SELECT * FROM bio_tempfeedstock";
    $result_tempselect=DB_query($sql_tempselect,$db);
    while($row_tempselect=DB_fetch_array($result_tempselect))
    {
        $sql_insert="INSERT INTO bio_instfeedsource (instid,sourceid) VALUES (".$row_tempselect['instid'].",".$row_tempselect['feedid'].")";
        DB_query($sql_insert,$db);
    }
       
}   

                                                                

                                                                
                                                                

echo "<br />";
echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "?" . SID . "'>"; 
echo '<table style=width:70%><tr><td>';
echo '<tr><td>';
echo"<fieldset style=overflow:auto; style='width:560px'>";
echo"<legend><h3>Select</h3></legend>";      

echo"<table style=width:100%>";

    echo"<tr>";
    echo"<td>Institution Type</td>";
    
    echo '<td><select name="inst" id="inst"  style="width:130px" >';
    $sql="select * from bio_institution";
    $result=DB_query($sql,$db);
    echo'<option value=0></option>';
    while($row=DB_fetch_array($result))
    {
        if ($row['inst_id']==$_POST['inst'])
        {
            echo '<option selected value="';
        } else {
            echo '<option value="';
        }
        echo $row['inst_id'] . '">'.$row['institution_name'];
        echo '</option>';
    }
    echo'</select></td>';
        
    
    echo "<td>Feedstock Source</td>";
    
    echo '<td><select name="feed" id="feed"  style="width:130px" >';
    $sql="select * from bio_fssources";
    $result=DB_query($sql,$db);
    echo'<option value=0></option>';
    while($row=DB_fetch_array($result))
    {
        if ($row['id']==$_POST['feed'])
        {
            echo '<option selected value="';
        } else {
            echo '<option value="';
        }
        echo $row['id'] . '">'.$row['source'];
        echo '</option>';
    }
    echo'</select></td>';
    
    echo "<td><input type='button' name='addstocks' id='addstocks' value='Add' onclick=addstock()></td>";  
    echo'</tr>';
    echo'</table>';
    echo"<div id='displayfeed'></div>"; 
    echo"</fieldset>";
    echo"</td></tr>";
    echo'<tr><td colspan=2><p><div class="centre"><input type=submit name=submit value="' . _('Assign') . '" ></div></p>';         
    echo"</td></tr>";
    echo"</form>";

echo "<tr><td>";
echo "<fieldset style='width:560px'>";
echo "<legend><h3>Institution & Feedstock source Details</h3></legend>";
echo "<div style='overflow:scroll;height:150px'>";
echo "<table class='selection' style='width:100%'>";
echo '<tr><th>' . _('Sl.No') . '</th>  
                <th>' . _('Institution Type') . '</th>
                <th>' . _('Feedstock') . '</th>
                          </tr>';  
  $sql1="SELECT bio_fssources.source,
                bio_institution.institution_name 
        FROM bio_instfeedsource,bio_institution,bio_fssources
            WHERE bio_instfeedsource.instid=bio_institution.inst_id
            AND bio_instfeedsource.sourceid=bio_fssources.id
            ORDER BY bio_institution.inst_id";
  $result1=DB_query($sql1, $db);  
  $k=0 ;$slno=0;   
   while($myrow=DB_fetch_array($result1) )    
  {
      if ($k==1){
        echo '<tr class="EvenTableRows">';
        $k=0;
    } else {
        echo '<tr class="OddTableRows">';
        $k=1;
    }     
   $cid=$myrow[0]; 
   $slno++;      
   $inst_name=$myrow[1];
   $source_name=$myrow[0];          
   echo"<tr style='background:#A8A4DB'><td>$slno</td>
                                    <td>$inst_name </td>
                                    <td>$source_name </td></tr>"; 
                                    //<td><a href='#' id='$cid' onclick='edit(this.id)'>Edit</a></td>
//                                    
//                                    <td><a href='#' id='$cid' onclick='dlt(this.id)'>Delete</a></td>      
//                                    
//                                    </tr>";        
      
  } 
  
echo"</table>";
echo"</div>";
echo"</fieldset>";
echo"</td></tr></table>"; 





?>

<script type="text/javascript">   


function addstock()
{

    var str1=document.getElementById("inst").value;
    var str2=document.getElementById("feed").value;
//   alert(str1);
//   alert(str2);
if(str1=="0")
{
alert("Select Institution"); document.getElementById("inst").focus();  return false;  
}

if(str2=="0")
{
alert("Select feed stock"); document.getElementById("feed").focus();  return false;  
}
if (str1=="")
  {
  document.getElementById("displayfeed").innerHTML="";     //editleads
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
    document.getElementById("displayfeed").innerHTML=xmlhttp.responseText;
//    document.getElementById('taskid').value="";       document.getElementById('number').value="";
    }
  } 
xmlhttp.open("GET","bio_instfeedadd.php?inst=" + str1  + "&feed=" + str2 ,true);
xmlhttp.send();    

}

function editfeed(str)
{
   alert(str);


if (str=="")
  {
  document.getElementById("editfeed").innerHTML="";
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
    document.getElementById("editfeed").innerHTML=xmlhttp.responseText;
//    document.getElementById('loc').focus(); 
    }
  } 
xmlhttp.open("GET","bio_instfeedadd.php?tempid=" + str,true);
xmlhttp.send();    

}

</script>
