<?php
if(!isset($_GET['p']))      {         
echo '<tr><td>' . _('Item') . "*</td><td><select name='StockID' id='itemcode_mi' onchange='wosearch()'>";

            $sql = "SELECT stockmaster.stockid,
                    stockmaster.description
                                        
                FROM stockmaster
                WHERE stockmaster.mbflag='M' AND stockmaster.categoryid !=13
                ORDER BY stockmaster.stockid";
            $result = DB_query($sql,$db); 
$f=0;            
while ($myrow= DB_fetch_array($result)) {
            if ( $myrow['stockid']==$_SESSION['StockID']) {
                echo "<option selected value='" . $myrow['stockid'] . "'>" . $myrow['description']; 
            } else if ($f==0){
                         
        echo '<option>';
        }
 echo "<option value='" . $myrow['stockid'] . "'>" . $myrow['description']; 
          
        
        $f++;    
        } //end while loop
            
        echo '</select>'; 
echo"</td></tr>";

echo '<tr style="background-color:lightgrey;"><td width=50%>Enter WO no:&nbsp;</td><td><input type="text" class="number" name="Wono" size=25 maxlength=40 id="wono" onchange=wosearch()></td></tr>';
echo '<tr style="background-color:lightgrey;"><td width=50%>Enter SR no:&nbsp;</td><td><input type="text" class="number" name="Srno" size=25 maxlength=40 id="srno" onchange=wosearch()></td></tr>';

}
/*
    else       {
                            
    $PageSecurity = 11; 
    include('includes/session.inc');
        
    $_POST['Reqno']=$_GET['p']; 

        $sql3="SELECT womaterialrequest.reqno,     
                      womaterialrequest.wono,
                      womaterialrequest.reqty,
                      dev_srstatus.srstatus,
                      woitems.stockid,
                      stockmaster.description
               FROM   womaterialrequest,woitems,stockmaster,dev_srstatus
               WHERE  womaterialrequest.reqno=".$_POST['Reqno']."    AND
                      womaterialrequest.wono=woitems.wo              AND
                      woitems.stockid=stockmaster.stockid            AND
                      womaterialrequest.statusid=dev_srstatus.srstatusid";

        $result3=DB_query($sql3,$db);
        $myrow3=DB_fetch_array($result3); 
        
    echo '<tr><td width=50%>Item:&nbsp;</td><td><input type="hidden" name="Stockname" size=25 maxlength=40 id="item" value='.$myrow3['description'].'>
    '.$myrow3['description'].'</td></tr>';      
    echo '<tr><td width=50%>WO no:&nbsp;</td><td><input type="hidden" class="number" name="Wono" size=25 maxlength=40 id="wono" value='.$myrow3['wono'].'>
    '.$myrow3['wono'].'</td></tr>';
    echo '<tr><td width=50%>SR no:&nbsp;</td><td><input type="hidden" class="number" name="Srno" size=25 maxlength=40 id="srno" value='.$myrow3['reqno'].'>
    '.$myrow3['reqno'].'</td></tr>';
    echo '<tr><td width=50%>SR qty:&nbsp;</td><td><input type="hidden" class="number" name="Srqty" size=25 maxlength=40 id="srqty" value='.$myrow3['reqty'].'>
    '.$myrow3['reqty'].'</td></tr>';
    echo'<tr><td>SR Status</td><td>'.$myrow3['srstatus'].'</td></tr>';
    echo "<tr><td></td><td><a style='cursor:pointer;' id='1' onclick='viewselection2(this.id)'>" . _('View Line items') . '</a><br><td></tr>';
                                                        
    }      */
    
?>



<script>
 function wosearch()
{
   
var item=document.getElementById("itemcode_mi").value;      
var wono=document.getElementById("wono").value;             
var srno=document.getElementById("srno").value;             

str1="";
str2="";
str3="";
if(typeof(item) != "undefined"){
    
var str1=item; 

}
if(typeof(wono) != "undefined"){
    
var str2=wono; 

}
if(typeof(srno) != "undefined"){
    
var str3=srno; 

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
                                                                                     
    document.getElementById("Datagrid_womaterialissuefromstores").innerHTML=xmlhttp.responseText;
    document.forms[0].Srqty.focus(); 
    }
  }

xmlhttp.open("GET","WOMaterialissuefromstores-search.php?p=" + str1 + "&q=" + str2 + "&r=" + str3 ,true);     
xmlhttp.send();
}
           //   + "&s=" + str4 + "&t=" + str5 + "&u=" + str6 + "&v=" + str7 + "&w=" + str8
</script>