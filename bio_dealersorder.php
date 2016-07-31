<?php
  $PageSecurity = 80;
include('includes/session.inc');
$title = _('Dealers Order List');  
include('includes/header.inc');

echo '<center><font style="color: #333;
       background:#fff;
       font-weight:bold;
       letter-spacing:0.10em;
       font-size:16px;
       font-family:Georgia;
       text-shadow: 1px 1px 1px #666;">Dealers Order List</font></center>';
    
    

echo "<form action=" . $_SERVER['PHP_SELF'] . "?" . SID ." method=POST>"; 
echo"<div id=grid>";
echo"<fieldset style='width:70%;'>";
echo"<legend><h3>Dealers Order List Details</h3></legend>";
echo"<table style='border:1px solid #F0F0F0;width:90%'>"; 

echo"<tr><td>Dealer Type</td><td>Name</td><td>Contact No</td><td>Place</td></tr>";

echo"<tr>"; 

    echo '<td><select name="sale" id="sale" style="width:135px">';
    echo '<option value=0></option>';   
    $sql1="select * from salesman where salesmancode not in (1,2,3)";
    $result1=DB_query($sql1,$db);
    while($row1=DB_fetch_array($result1))
    {
    //  echo "<option value=$row1[enqtypeid]>$row1[enquirytype]</option>";
        if ($row1['salesmancode']==$_POST['salesmanname'])
           {
             echo '<option selected value="';
           } else 
           { 
               echo '<option value="'; 
           }
           echo $row1['salesmancode'] . '">'.$row1['salesmanname'];
           echo '</option>';  
    }

    echo '</select></td>';


    echo "<td><input style=width:150px type='text' name='name' id='name' style='width:100px'></td>";  
    echo "<td><input style=width:150px type='text' name='contno' id='contno' style='width:100px'></td>"; 
    echo "<td><input style=width:150px type='text' name='place' id='place' style='width:100px'></td>";
    
    echo '<td></td><td><input type=submit name=filter value=Search></td>';   
    
echo"</tr>";


echo"</table>";
echo"<br />";
        echo "<table class='selection' style='width:90%'>";
        echo '<tr>  <th>' . _('Slno') . '</th>
                    <th>' . _('Customer Name') . '</th>  
                    <th>' . _ ('Contact Name') . '</th>  
                    <th>' . _ ('Phone') . '</th> 
                    <th>' . _('Mobile') . '</th>
                    <th>' . _('Salesman') . '</th> 
              </tr>';

$sql_so="SELECT custbranch.brname,
                custbranch.braddress1,
                custbranch.braddress2,
                custbranch.braddress3,
                custbranch.contactname,
                custbranch.phoneno,
                custbranch.faxno,
                custbranch.salesman,
                salesman.salesmancode,
                salesman.salesmanname   
           FROM custbranch,
                salesman
          WHERE custbranch.salesman!=0
            AND salesman.salesmancode=custbranch.salesman";
         
         if(isset($_POST['filter']))
         {

         if($_POST['name']!="")
         {
         $sql_so .= " AND custbranch.brname LIKE '".$_POST['name']."%'"; 
         }
                 
         if($_POST['contno']!="")
         {
         $sql_so .= " AND custbranch.phoneno LIKE '".$_POST['contno']."%'"; 
         }
                 
         if($_POST['place']!="")
         {
         $sql_so .= " AND custbranch.braddress2 LIKE '".$_POST['place']."%'"; 
         }


$result_so=DB_query($sql_so,$db);

$k=0;
$slno=1;
while($row_so=DB_fetch_array($result_so))
{
    $leadid=$row_so['leadid'];
    $orderno=$row_so['orderno']; 
    
    
          if ($k==1)
          {
            echo '<tr class="EvenTableRows">';
            $k=0;
          }else 
          {
            echo '<tr class="OddTableRows">';
            $k=1;     
          }
          
    
    echo"<td>$slno</td>
         <td><b>".$row_so['brname']."</b><br>".$row_so['braddress1']."<br>".$row_so['braddress2']."<br>".$row_so['braddress3']."</br></td> 
         <td>".$row_so['contactname']."</td> 
         <td>".$row_so['phoneno']."</td> 
         <td>".$row_so['faxno']."</td>
         <td>".$row_so['salesmanname']."</td> 
         </tr>";
                                
 $slno++;                               
    
}

} 

echo"</table>";

echo"</fieldset>";
echo"</div>";
echo"</form>";
?>


