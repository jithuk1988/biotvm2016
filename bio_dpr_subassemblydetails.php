<?php
  $PageSecurity = 80;
  include('includes/session.inc');
  if(isset($_GET['subassembly'])){
      $subassembly=$_GET['subassembly'];
      $quantity=$_GET['quantity'];
      $price=$_GET['price'];
      if($subassembly==""){
          $subassembly=0;
      }
      if($quantity==""){
          $quantity=0;
      }
      if($price==""){
          $price=0;
      }
      $sql_sub1="INSERT INTO bio_subassemblytemp(stockid,
                                            quantity,
                                            price) 
                                 VALUES ('".$subassembly."',
                                         '".$quantity."',
                                         '".$price."')";
      $result_sub1 = DB_query($sql_sub1,$db);
      $tempflg=DB_Last_Insert_ID($Conn,'bio_subassemblytemp','temp_id');        
      echo"<input type='hidden' id='subtempid' value='".$tempflg."'>";
      
      echo"<table  style='width:75%;' border=0>";
      echo"<tr style='background:#D50000;color:white'>
      <td>Slno</td>
      <td>Item</td>
      <td>Quantity</td>
      <td>Price</td>
      </tr>";
      
      $sql_sub2="SELECT stockmaster.stockid,
                        stockmaster.longdescription,
                        bio_subassemblytemp.quantity,
                        bio_subassemblytemp.temp_id,
                        bio_subassemblytemp.price 
                   FROM bio_subassemblytemp,stockmaster 
                  WHERE stockmaster.stockid=bio_subassemblytemp.stockid";
      $result_sub2=DB_query($sql_sub2, $db);
      $n=1;
      while($myrow=DB_fetch_array($result_sub2)){
          echo "<tr style='background:#000080;color:white'>
          <td>$n</td>
          <td>".$myrow['longdescription']."<input type='hidden' id='subid' value='".$myrow['stockid']."'></td>
          <td>".$myrow['quantity']."<input type='hidden' id='quantity' value='".$myrow['quantity']."'></td>
          <td>".$myrow['price']."<input type='hidden' id='price' value='".$myrow['price']."'></td>
          <td><a style='cursor:pointer;color:white;' id='".$myrow['temp_id']."' onclick='editsubassembly(this.id)'>Edit</a ></td>
          <td><a style='cursor:pointer;color:white' id='".$myrow['temp_id']."' onclick='deletsubassembly(this.id)'>Delete</a></td></tr>";
          $n++;
      }echo"</table>";   echo "<table  style='width:75%;'><tr style='background:#000080;color:white' id='editsub'></tr></table>";
  }
  
  
  if(isset($_GET['tempid'])|| $_GET['tempid']!=""){
      $tempID=$_GET['tempid'];
      $sql_sub3="SELECT stockmaster.stockid,
                        stockmaster.longdescription,
                        bio_subassemblytemp.quantity,
                        bio_subassemblytemp.temp_id,
                        bio_subassemblytemp.stockid 
                   FROM bio_subassemblytemp,stockmaster 
                  WHERE stockmaster.stockid=bio_subassemblytemp.stockid
                   AND bio_subassemblytemp.temp_id=".$tempID;
      $result1=DB_query($sql_sub3, $db);
      while($myrow=DB_fetch_array($result1)){
          echo"<td>Edit</td>
               <td colspan='2'><input type='hidden' id='subassemblyid' name='SubAssemblyID' value='".$myrow['stockid']."'>".$myrow['longdescription']."</td>
               <td><input type='text' id='subquantity' style='width:90px' name='SubQuantity' value='".$myrow['quantity']."'></td>
               <td><input type='hidden' id='price' value='".$myrow['price']."'>".$myrow['price']."</td>
               <input type='hidden' id='subasstempid' name='SubAssTempID' value='".$myrow['temp_id']."'> 
               <td><input type='button' id='updatesub' name='UpdateSub' value='edit' onclick='doedit()'></td>";
      }
  }
  
  if(isset($_GET['editid'])){
      $quantity1=$_GET['editqty'];
      $tempID1=$_GET['editid'];
      $sql_edit="UPDATE bio_subassemblytemp
                     SET quantity=".$quantity1." 
                     WHERE temp_id=".$tempID1;
      $result_edit=DB_query($sql_edit, $db); 

      echo"<table  style='width:75%;' border=0>
      <tr style='background:#D50000;color:white'>
      <td>Slno</td>
      <td>Item</td>
      <td>Quantity</td>
      <td>Price</td></tr>";
      
      $sql="SELECT stockmaster.stockid,
                        stockmaster.longdescription,
                        bio_subassemblytemp.quantity,
                        bio_subassemblytemp.temp_id 
                   FROM bio_subassemblytemp,stockmaster 
                  WHERE stockmaster.stockid=bio_subassemblytemp.stockid";
      $result1=DB_query($sql, $db);    
      $n=1;
      while($myrow=DB_fetch_array($result1)){
          echo "<tr style='background:#000080;color:white'>
          <td>$n</td>
          <td>".$myrow['longdescription']."<input type='hidden' id='subid' value='".$myrow['stockid']."'></td>
          <td>".$myrow['quantity']."<input type='hidden' id='quantity' value='".$myrow['quantity']."'></td>
          <td>".$myrow['price']."<input type='hidden' id='price' value='".$myrow['price']."'></td>
          <td><a style='cursor:pointer;color:white;' id='".$myrow['temp_id']."' onclick='editsubassembly(this.id)'>Edit</a ></td>
          <td><a style='cursor:pointer;color:white' id='".$myrow['temp_id']."' onclick='deletsubassembly(this.id)'>Delete</a></td></tr>";
          $n++;
      }echo "<table  style='width:75%;'><tr style='background:#000080;color:white' id='editsub'></tr></table>";
  }
  
  if(isset($_GET['deletid'])){
      $tempID2=$_GET['deletid'];
      $sql="DELETE FROM `bio_subassemblytemp` WHERE `temp_id` = $tempID2 ";
      $result1=DB_query($sql, $db);
      
      echo"<table  style='width:65%;' border=0>";
      echo"<tr style='background:#D50000;color:white'>
      <td>Slno</td>
      <td>Item</td>
      <td>Quantity</td>
      <td>Price</td>
      </tr>";
      
      $sql_sub2="SELECT stockmaster.stockid,
                        stockmaster.longdescription,
                        bio_subassemblytemp.quantity,
                        bio_subassemblytemp.temp_id 
                   FROM bio_subassemblytemp,stockmaster 
                  WHERE stockmaster.stockid=bio_subassemblytemp.stockid";
      $result_sub2=DB_query($sql_sub2, $db);
      $n=1;
      while($myrow=DB_fetch_array($result_sub2)){
          echo "<tr style='background:#000080;color:white'>
          <td>$n</td>
          <td>".$myrow['longdescription']."<input type='hidden' id='subid' value='".$myrow['stockid']."'></td>
          <td>".$myrow['quantity']."<input type='hidden' id='quantity' value='".$myrow['quantity']."'></td>
          <td>".$myrow['price']."<input type='hidden' id='price' value='".$myrow['price']."'></td>
          <td><a style='cursor:pointer;color:white;' id='".$myrow['temp_id']."' onclick='editsubassembly(this.id)'>Edit</a ></td>
          <td><a style='cursor:pointer;color:white' id='".$myrow['temp_id']."' onclick='deletsubassembly(this.id)'>Delete</a></td></tr>";
          $n++;
      }echo"</table>";;
  } 




  
?>
