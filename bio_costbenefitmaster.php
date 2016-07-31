<?php

  $PageSecurity = 40;
include('includes/session.inc');
$title = _('Cost Benefit') . ' / ' . _('Maintenance');
include('includes/header.inc');

echo '<p class="page_title_text"><img src="'.$rootpath.'/css/'.$theme.'/images/maintenance.png" title="' . _('Enquiry Types')
    . '" alt="" />' . _('Cost Benefit Setup') . '</p>';
echo '<div class="page_help_text">' . _('Add/edit/delete Cost Benefit') . '</div><br />';
   

echo '<a href="index.php">Back to Home</a>';


if (isset($_POST['submit'])){
 $sql = "INSERT INTO bio_costbenefitmaster(outputid,
                                               output_unit,
                                               commercialproduct_id,
                                               product_unit,
                                               quantity,
                                               unitprice)
                                  VALUES ('" . $_POST['output'] . "',
                                          '" . $_POST['OutputUnit'] . "',
                                          '" . $_POST['CommercialProduct'] . "',   
                                          '" . $_POST['ProductUnit'] . "',  
                                          '" . $_POST['Quantity'] . "',
                                          '" . $_POST['UnitPrice'] ."')";                                           
        $result = DB_query($sql,$db);
}

echo '<table style=width:25%><tr><td>';
echo '<fieldset style="height:250px">';
echo '<legend><b>Cost Benefit Master</b></legend>';

echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '">'; 
echo '<br><br><table class="selection">';


echo '<tr><td>Output</td>';
echo '<td><select name="output" id="output">';
echo '<option value="0"></option>';
$sql1="select * from bio_output";
$result1=DB_query($sql1,$db);
while($row1=DB_fetch_array($result1))
{
   echo '<option value='.$row1['outputid'].'>'.$row1['output'].'</option>'; 
}

echo '</select></td></tr>';
//echo '<tr><td>Quantity</td>;<td><input type="text" name="output"></td></tr>';

 echo '<tr><td>Unit</td>';
echo '<td><select name="OutputUnit" id="outputunit">';
echo '<option value="0"></option>';
$sql1="select * from unitsofmeasure";
$result1=DB_query($sql1,$db);
  while($row1=DB_fetch_array($result1))
 {
  
   echo '<option value='.$row1['unitid'].'>'.$row1['unitname'].'</option>';
       
 }

  echo '</select></td></tr>';


  echo '<tr><td>Equivalent commercial product</td>';
echo '<td><select name="CommercialProduct" id="commercial product">';
echo '<option value="0"></option>';

  $sql1="select * from bio_commercialproduct";
$result1=DB_query($sql1,$db);
while($row1=DB_fetch_array($result1))
{
   echo '<option value='.$row1['commercialproduct_id'].'>'.$row1['commercialproduct'].'</option>'; 
}

echo '</select></td></tr>';
                 
echo '<tr><td>Unit</td>';
echo '<td><select name="ProductUnit" id="pdct">';
echo '<option value="0"></option>';

    $sql1="select * from unitsofmeasure";
$result1=DB_query($sql1,$db);
  while($row1=DB_fetch_array($result1))
 {
  
   echo '<option value='.$row1['unitid'].'>'.$row1['unitname'].'</option>';
       
 }

  echo '</select></td></tr>';



 echo '<tr><td>Quantity</td><td><input type="text" name="Quantity" id="qty"></td></tr>';
 echo '<tr><td>Unit price</td><td><input type="text" name="UnitPrice" id="uprice"></td></tr>';

 
    //echo '<tr><td>Unit</td><td><input type="text" name="unit"></td></tr>';
 
//echo '<tr><td>UOM</td>';
//echo '<td><select name="uom">';
//echo '<option value="0"></option></select></td></tr>';
//echo '<tr><td>Commercial item</td><td><input type="text" name="comitem"></td></tr>';
//echo '<tr><td>Unit</td><td><input type="text" name="unit"></td></tr>';  
//echo '<tr><td>Benefit per year</td><td><input type="text" name="bperyear"></td></tr>'; 
//echo '<tr><td>Expense name</td><td><input type="text" name="expname"></td></tr>'; 
//echo '<tr><td>Amount per year</td><td><input type="text" name="aperyear"></td></tr>'; 
  //echo '<tr><td></td><td><input type="submit" name="submit" value="Save"></td></tr>';
  echo'<tr><td></td><td> <input type="Submit" name="submit" value="' . _('Save') . '" onclick=" if(validate()==1)return false"></td></tr>'; 
      echo '</table>';
      echo '</form></fieldset>';

      echo "<fieldset style='width:560px'>";
      echo "<legend><h3>Cost benefit Details</h3></legend>";
      echo "<div style='overflow:scroll;height:150px'>";
      echo "<table class='selection' style='width:100%'>";
      echo '<tr>  <th>' . _('Slno') . '</th>  
                <th>' . _('Output') . '</th>
        <th>' . _('Equivalent Commercial Product') . '</th>
           <th>' . _ ('Quantity') . '</th> 

        <th>' . _('Price') . '</th>
               </tr>';
  
              
        
  $sql1="SELECT * FROM bio_costbenefitmaster";
  $result1=DB_query($sql1, $db);  
 $k=0 ;$slno=0; 
  while($row1=DB_fetch_array($result1) )
  
  { 
          if ($k==1){
        echo '<tr class="EvenTableRows">';
        $k=0;
    } else {
        echo '<tr class="OddTableRows">';
        $k=1;
    }  
      $slno++;
     $quantity=$row1['quantity'];
   
   $price=$row1['unitprice'];
   
  $sql2="select * from bio_output where  bio_output.outputid=".$row1['outputid']."";
  $result2=DB_query($sql2,$db);
  $row2=DB_fetch_array($result2);
  $output=$row2['output'] ;
  
  $sql3="select * from bio_commercialproduct where  bio_commercialproduct.commercialproduct_id=".$row1['commercialproduct_id']."";
        
  $result3=DB_query($sql3,$db);
  $row3=DB_fetch_array($result3);
  
  
//$output=$row2['output'] ;  
echo '<td>'.$slno.'</td><td>'.$output.'</td> <td> '.$row3['commercialproduct'].'</td><td>'.$quantity.'</td>  <td>'.$price.'</td> <td>  <a href="%sSelectedType=%s">' . _('Edit') . '</td> <td><a href="%sSelectedType=%s">' . _('Delete') . '</td></tr>';
  } 
echo '</td></tr></table>';

      //  
      //$k=0; //row colour counter

//while ($myrow = DB_fetch_row($result)) {

           // printf('<td>%s</td>
//            <td>%s</td>
//            <td><a href="%sSelectedType=%s">' . _('Edit') . '</td>
//            <td><a href="%sSelectedType=%s&delete=yes" onclick=\'return confirm("' .
//                _('Are you sure you wish to delete this Feedstocks?') . '");\'>' . _('Delete') . '</td>
//        </tr>',
//        $myrow[0],
//        $myrow[1],
//        $_SERVER['PHP_SELF'] . '?', 
//        $myrow[0],
//        $_SERVER['PHP_SELF'] . '?', 
//        $myrow[0]);
include('includes/footer.inc');
?>
<Script>
function validate()
{     
  
//    document.getElementById('phone').focus();
var f=0;
var p=0;
if(f==0){f=common_error('output','Please select an output');  if(f==1){return f; }  }
if(f==0){f=common_error('outputunit','Please select a unit');  if(f==1){return f; }  }  
if(f==0){f=common_error('commercialproduct','Please a product');  if(f==1){return f; }  }  
if(f==0){f=common_error('pdct','Please select unit');  if(f==1){return f; }  } 
if(f==0){f=common_error('qty','Please specify the quantity');  if(f==1){return f; }  }  
if(f==0){f=common_error('uprice','Please specify unit price');  if(f==1){return f; }  }    
// f=common_error('itemcode','Please enter an item code');if(f==1)return f;
}</Script>