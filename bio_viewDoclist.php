<?php
  $PageSecurity = 80;
include('includes/session.inc');
$title = _('View Document Master');
include('includes/header.inc');  

    echo '<br>';   echo '<br>';   
    echo '<table style=width:25%><tr><td>';
    echo "<fieldset style='width:650px'>";
    echo '<legend><b>View Documents</b></legend>';
     
  if(isset($_GET['update']))
  {  
      $id=$_GET['update'];
      $date=$_GET['date'];    
      $status=$_GET['status'];
   
           $sql = "UPDATE bio_documentlist
                    SET 
                          receivedDate='".$date."' ,status='".$status."'
                    WHERE id =".$id;
            $result=DB_query($sql,$db);
}

echo '<br>';
echo '<div id="panel">';
      echo "<div style='height:300px'>";
      echo "<table class='selection' style='width:100%'>";
      echo '<tr>  <th>' . _('Slno') . '</th> <th>' . _ ('Orderno') . '</th> 
                <th>' . _('Customer Name') . '</th>

           <th>' . _ ('Place') . '</th> 
      
               </tr>';
      $sql1="SELECT  bio_documentlist.id,
                     bio_documentlist.orderno,
                     salesorders.debtorno,
                     debtorsmaster.name,
                     debtorsmaster.address3
              FROM   bio_documentlist,salesorders,debtorsmaster
              WHERE  salesorders.orderno= bio_documentlist.orderno
               AND   salesorders.debtorno=debtorsmaster.debtorno
              GROUP BY bio_documentlist.orderno";
  $result1=DB_query($sql1, $db);           

   $slno=1; 
  while($row1=DB_fetch_array($result1) )
  
  {      $id=$row1['id'];     
         $name=$row1['name'];     
         $address3=$row1['address3']; 
             
          if ($k==1){
        echo '<tr class="EvenTableRows">';
        $k=0;
    } else {
        echo '<tr class="OddTableRows">';
        $k=1;
    }  

 $orderno=$row1['orderno'];
echo"<tr style='background:#A8A4DB'><td>$slno</td> <td>$orderno</td> 
                                    <td>$name</td> 
                                    <td>$address3</td>
                                    <td><a style='cursor:pointer;' id='$orderno' onclick='viewDocument(this.id)'>View All Documents</a></td>
                                     </tr>";   $slno++;   

}

 echo '</table></div></div></fieldset></table>';   
 
?>
<script>

   function update(str,i){
       // alert(str);
        //alert(i);
       var rid='rdate'+i;
       var status='status'+i;
       
  var rdate=document.getElementById(rid).value;
  var statusid=document.getElementById(status).value;      
         
location.href="?update=" +str + "&date=" + rdate+ "&status=" + statusid;         
 
}

  function viewDocument(str){
//alert(str);
if (str=="")
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
    //document.getElementById('loc').focus(); 
    }
  } 
xmlhttp.open("GET","bio_viewDocuments.php?orderno=" + str,true);
xmlhttp.send(); 
}


</script>