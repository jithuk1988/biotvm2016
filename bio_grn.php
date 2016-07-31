<?php
  $PageSecurity = 80;
  $pagetype=1;
include ('includes/session.inc');
$title = _('GRN');
include ('includes/header.inc');
include('includes/sidemenu2.php');
include ('includes/SQL_CommonFunctions.inc');
echo '<center><font style="color: #333;
    background:#fff;
    font-weight:bold;
    letter-spacing:0.10em;
    font-size:16px;
    font-family:Georgia;
    text-shadow: 1px 1px 1px #666;">GRN CREATION</font></center>';
     echo'<table width=98% ><tr><td>'; 
    echo'<div >'; 
    echo "<form method='POST' name=form1 action='" . $_SERVER['PHP_SELF'] . "'>";  
    echo"<fieldset style='width:400px;height:155px'; overflow:auto;'>";
    echo"<legend><h3>Purchase</h3></legend>";
    echo '<table><tr><td>';
    $sql="SELECT
    maincatid,
    maincatname
    FROM bio_maincategorymaster where rowstatus=1";
    $rst=DB_query($sql,$db);// 
    echo '' . _('Main Category') . ':</td><td id="combo0"><select name="maincat"   style="width:200px"   onchange="view0(this.value)" onblur="view0(this.value)"';     
    echo '<option></option>';
    while($myrowc=DB_fetch_array($rst))
    {
      if($myrowc[maincatid]==$_POST['maincat'])
      {
          echo '<option selected value='.$myrowc[maincatid].'>'.$myrowc[maincatname].'</option>';
      }
      else
      {
     echo '<option value='.$myrowc[maincatid].'>'.$myrowc[maincatname].'</option>';
    }
    }
  echo '</select></td></tr> <tr><td>';
    echo '' . _('Sub Category') . ':</td><td id="combo1"><select name="subcat" id="subcat"  style="width:200px"   onchange="view(this.value)" onblur="view(this.value)">';

    $sql = "SELECT `subcategoryid`,`subcategorydescription` FROM `substockcategory` where maincatid='".$_POST['maincat']."'";
    $ErrMsg = _('The stock categories could not be retrieved because');
    $DbgMsg = _('The SQL used to retrieve stock categories and failed was');
    $result = DB_query($sql,$db,$ErrMsg,$DbgMsg);
    echo '<option></option>';
  //  echo '<option value=>';
    while ($myrow=DB_fetch_array($result))
         {
    
          if ($myrow['subcategoryid']==$_POST['subcat'] )
          {
           echo '<option selected value="'. $myrow['subcategoryid'] . '">' . $myrow['subcategorydescription'].'</option>';
          } 
          else
          {
           echo '<option value="'. $myrow['subcategoryid'] . '">' . $myrow['subcategorydescription'].'</option>';
          }
         }

/*   if (!isset($_POST['SubCategoryID']))
     {
       $_POST['SubCategoryID']=$SubCategory;
     }
*/

   echo '</select>';
    echo '</td></tr>
   <tr><td>Subsub Category</td><td id=combonew><select name="CategoryID" style="width:200px" onchange="view2(this.value)" onblur="view2(this.value)">';
 /* if($_POST['CategoryID'])
   {*/
  echo $sql = "SELECT categoryid, categorydescription 
        FROM stockcategory,
             bio_maincat_subcat
        WHERE stockcategory.categoryid= bio_maincat_subcat.subsubcatid     AND
              bio_maincat_subcat.maincatid ='".$_POST['maincat']."' AND bio_maincat_subcat.subcatid ='".$_POST['subcat']."'";
   $ErrMsg = _('The stock categories could not be retrieved because');
   $DbgMsg = _('The SQL used to retrieve stock categories and failed was');
   $result = DB_query($sql,$db,$ErrMsg,$DbgMsg);
   echo '<option value=0></option>';
while ($myrow=DB_fetch_array($result)){
    if (!isset($_POST['CategoryID']) or $myrow['categoryid']==$_POST['CategoryID']){
        echo '<option  selected value="'. $myrow['categoryid'] . '">' . $myrow['categorydescription'].'</option>';
    } else {
        echo '<option value="'. $myrow['categoryid'] . '">' . $myrow['categorydescription'].'</option>';
    }
    $Category=$myrow['categoryid'];
}

/*if (!isset($_POST['CategoryID'])) {
    $_POST['CategoryID']=$Category;
}
   }*/
   echo '</select></td></tr></td></tr>
   <tr><td>Item name</td><td id="combo2">';
    echo '<select name="combo2"  style="width:200px" onchange="view3(this.value)" >';
    if($_POST['CategoryID'])
    {
   $sql="SELECT
           `stockmaster`.`stockid`,
        `stockmaster`.`description` from `stockmaster` where stockmaster.categoryid='".$_POST['CategoryID']."'";
   $ErrMsg = _('The stock categories could not be retrieved because');
   $DbgMsg = _('The SQL used to retrieve stock categories and failed was');
   $result = DB_query($sql,$db,$ErrMsg,$DbgMsg);
    }
    else
    {
        $sql="SELECT
           `stockmaster`.`stockid`,
        `stockmaster`.`description` from `stockmaster";
          $ErrMsg = _('The stock categories could not be retrieved because');
   $DbgMsg = _('The SQL used to retrieve stock categories and failed was');
   $result = DB_query($sql,$db,$ErrMsg,$DbgMsg);
    }
   echo '<option></option>';
   while ($myrow=DB_fetch_array($result))
        {
          if($myrow['stockid']==$_POST['combo2'] || $myrow['stockid']==$_GET['select'])
            {
             echo '<option selected value="'. $myrow['stockid'] . '">' . $myrow['description'].'</option>';
            }
            else
             {
              echo '<option value="'. $myrow['stockid'] . '">' . $myrow['description'].'</option>';
              }
    
        }
   echo '</select></td></tr><tr><td id="selectsup">supplier</td><td><select name=supply id=supply style="width:200px" >';
   echo $sql1="select * from suppliers";
   $result1 = DB_query($sql1,$db);
   echo '<option></option>';
   while ($myrow1=DB_fetch_array($result1))
        {
          if($myrow1['supplierid']==$_POST['supply'])
            {
              echo '<option selected value="'. $myrow1['supplierid'] . '">' . $myrow1['suppname'].'</option>';
            }else
            { echo '<option value="'. $myrow1['supplierid'] . '">' . $myrow1['suppname'].'</option>';
            }
        }
   echo '</select></td></tr>';
  echo' <tr><td></td><td></td></tr></table>';
  echo'<center><input type="submit" name="submit" id="submit" value="submit"/></center>';
  echo'</fieldset>';
    $user=$_SESSION['UserID'];
 $sql="SELECT `realname` FROM `www_users` WHERE `userid` LIKE '".$user."'";
 $res=DB_query( $sql,$db);
 $row=DB_fetch_array($res);
 $username=$row[0];
    if(isset($_GET['select']))
 {   
 $sqliten="   SELECT bio_maincat_subcat.maincatid,bio_maincat_subcat.subcatid,bio_maincat_subcat.subsubcatid
FROM `bio_maincat_subcat`
WHERE bio_maincat_subcat.subsubcatid like (select categoryid from stockmaster where stockmaster.stockid like '".$_GET['select']."')";
$allcat=DB_query($sqliten,$db, $ErrMsg);
$allcatid=DB_fetch_row($allcat);
$sql_sup="SELECT `supplierno` FROM `purchorders` WHERE `orderno` like '".$_GET['ord']."' ";
$allcats=DB_query($sql_sup,$db, $ErrMsg);
$allcatids=DB_fetch_row($allcats);
$_POST['maincat']=$allcatid[0];
$_POST['subcat'] =$allcatid[1];
$_POST['supply']=$allcatids[0];
//$_POST['CategoryID']=$allcatid[2];

 }
    if($_POST['submit2'])
      {
       
      FormatDateForSql($_POST['date']);
       $GRN = GetNextTransNo(25, $db);
       $sql=DB_query("select rate from purchorders where orderno='".$_POST['orderno']."'",$db);
       $Res1=DB_fetch_row($sql);
       $LocalCurrencyPrice=$Res1[0];
       if($_POST['code']!="")
       {
         $SQL = "SELECT materialcost + labourcost + overheadcost as stdcost
                            FROM stockmaster
                            WHERE stockid='" . $_POST['code'] . "'";
                $ErrMsg =  _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The standard cost of the item being received cannot be retrieved because');
                $DbgMsg = _('The following SQL to retrieve the standard cost was used');
                $Result = DB_query($SQL,$db,$ErrMsg,$DbgMsg,true);
                 $myrow = DB_fetch_row($Result);

                if ($_POST['qtyrec']==0){ //its the first receipt against this line
                   $StandardCost = $myrow[0];
                }
                $CurrentStandardCost = $myrow[0];   
            $StandardCost=($CurrentStandardCost *  $_POST['nowrec'])+($StandardCost*$_POST['qtyrec'])/($_POST['nowrec'] + $_POST['qtyrec']);
                           
             $a=   $_POST['nowrec'] +  $_POST['qtyrec'];
     if($_POST['nowrec']==($_POST['qtyord']-$_POST['qtyrec']) )
         {
        $SQL = "UPDATE purchorderdetails SET quantityrecd = " . $a. ",
                                               stdcostunit='" . $StandardCost . "',
                                                    completed=1
                        WHERE podetailitem = '" . $_POST['podet'] . "'";
                      $StatusComment=FormatDateForSql($_POST['date']) .' - ' . _('Order Completed');
        $sql12="UPDATE purchorders
                    SET status='Completed',
                    stat_comment='" . $StatusComment . "'
                    WHERE orderno='" . $_POST['orderno'] . "'";
        $result12=DB_query($sql12,$db);
         } else 
         {
          $SQL = "UPDATE purchorderdetails SET
                                                quantityrecd = " .$a . ",
                                                stdcostunit='" . $StandardCost . "',
                                                completed=0
                                        WHERE podetailitem = '" . $_POST['podet'] . "'";
         }

            $ErrMsg =  _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The purchase order detail record could not be updated with the      quantity received because');
            $DbgMsg = _('The following SQL to update the purchase order detail record was used');
            $Result = DB_query($SQL,$db, $ErrMsg, $DbgMsg, true);
       }
       
     // echo  $_POST['desc'];

   $SQL = "INSERT INTO grns (grnbatch,
                                    podetailitem,
                                    itemcode,
                                    itemdescription,
                                    deliverydate,
                                    qtyrecd,stdcostunit,
                                    supplierid)
                            VALUES ('" . $GRN . "',
                                '" . $_POST['podet'] . "',
                                '" . $_POST['code'] . "',
                                '" . $_POST['desc'] . "',
                                '" . FormatDateForSql($_POST['date']) . "',
                                '" . $_POST['nowrec'] . "',
                                '" .$CurrentStandardCost. "',
                                '" . $_POST['suppno'] . "')";

         $ErrMsg =  _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('A GRN record could not be inserted') . '. ' . _('This receipt           of goods has not been processed because');
            $DbgMsg =  _('The following SQL to insert the GRN record was used');
            $Result = DB_query($SQL, $db, $ErrMsg, $DbgMsg, true);
          
             $sql_loc=DB_query("SELECT `intostocklocation` FROM  `purchorders` WHERE orderno='".$_POST['orderno']."'",$db);
                       $Res1=DB_fetch_row($sql_loc);  
           $loc=$Res1[0]; 
          $sql_qty1=DB_query("SELECT quantity FROM locstock WHERE loccode='".$loc."' AND stockid='".$_POST['code']."'",$db);
                       $Res1=DB_fetch_row($sql_qty1);
                        $newqty=$Res1[0]+$_POST['nowrec'];  
         $locstupdate="UPDATE locstock set quantity='".$newqty."' WHERE loccode='".$loc."' AND stockid='".$_POST['code']."'";
             $res_up=DB_query($locstupdate,$db);
             
          $SQL = "INSERT INTO stockmoves (stockid,
                                                type,
                                                transno,
                                                loccode,
                                                trandate,
                                                price,
                                                prd,
                                                reference,
                                                qty,
                                                newqoh,standardcost)
                                    VALUES (
                                        '" . $_POST['code'] . "',
                                        25,
                                        '" . $GRN . "',
                                        '" . $loc . "',
                                        '" . FormatDateForSql($_POST['date']) . "',
                                        '" . $LocalCurrencyPrice . "',
                                         '" . FormatDateForSql($_POST['date']) . "',
                                        '" . $_POST['suppno'] . " (" . $_POST['suppname'] . ") - " .$_POST['orderno'] . "',
                                        '" . $_POST['nowrec'] . "',
                                        '" . $newqty . "',
                                       '" . $StandardCost . "'
                                        )";

             $ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('stock movement records could not be inserted because');
                $DbgMsg =  _('The following SQL to insert the stock movement records was used');
                $Result = DB_query($SQL, $db, $ErrMsg, $DbgMsg, true);
         
            
              $rej=$_POST['sirnew']-$_POST['nowrec'];
               if($rej < 0)
               {
                   $reject==0;
               }else
               {
                   $reject=$rej;
               }
             $SQL = "INSERT INTO `bio_grn_invoice` (grnbatch,
                                                invoiceno,
                                                recieveby,
                                                varifyby,
                                                acceptby,
                                                rejected)
                                    VALUES (
                                        '" . $GRN . "',
                                        '" . $_POST['billno'] . "',
                                         '" . $_POST['recieve'] . "',
                                        '" . $_POST['varify'] . "',
                                           '" . $_POST['accept'] . "',
                                           '" . $reject . "'
                                        )";

            $ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('stock movement records could not be inserted because');
                $DbgMsg =  _('The following SQL to insert the stock movement records was used');
                $Result = DB_query($SQL, $db, $ErrMsg, $DbgMsg, true);
            
           
            
           
            
         $SQL = "SELECT `controlled`,`serialised` FROM `stockmaster` WHERE `stockid`='".$_POST['code']."'";
                $resul=DB_query($SQL,$db);
            $Res=DB_fetch_row($resul);
            
                        $cont=$Res[0];  
                         $ser=$Res[1];  
              if($cont==1 && $ser==1)
               {
                   for($i=1;$i<=$_POST['nowrec'];$i++)
                   
                   {
                       $sqlmno=" SELECT max( `stockmoveno` ) FROM `stockserialmoves`";
            $resulm=DB_query($sqlmno,$db);
            $Resm=DB_fetch_row($resulm);
            $StkMoveNo=$Resm[0]+1;
                       
                       $sql="SELECT serialno FROM serialno";
                   $res=DB_query($sql,$db);
                   $row=DB_fetch_array($res);
                    $serial=($row[0]+1);
                    $stockid=$_POST['code'];
                    $supplierid=$_POST['suppno'];
                    $serialnew="" . $supplierid . "-" . $stockid . "-" . $serial . "";
                   $SQL = "INSERT INTO stockserialitems (stockid,
                                                                loccode,
                                                                            serialno,
                                                                            quantity)
                                                                        VALUES ('" . $_POST['code'] . "',
                                                                            '" . $loc . "',
                                                                            '" . $serialnew . "',
                                                                            1)";
                                
                                    $ErrMsg =  _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The serial stock item record could not be                                     inserted because');
                                $DbgMsg =  _('The following SQL to insert the serial stock item records was used');
                                $Result = DB_query($SQL, $db, $ErrMsg, $DbgMsg, true);
                         $display[$i]= $serialnew;     
                                $SQL = "INSERT INTO stockserialmoves (stockmoveno,
                                                                    stockid,
                                                                    serialno,
                                                                    moveqty)
                                                            VALUES (
                                                                '" . $StkMoveNo . "',
                                                                '" . $_POST['code'] . "',
                                                                '" . $serialnew . "',
                                                                1
                                                                )";
                            $ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The serial stock movement record could not be inserted because');
                            $DbgMsg = _('The following SQL to insert the serial stock movement records was used');
                            $Result = DB_query($SQL, $db, $ErrMsg, $DbgMsg, true);
                              $sql="UPDATE serialno SET serialno='$serial'";
                   $res=DB_query($sql,$db);
                   }
               }
               $StatusComment=FormatDateForSql($_POST['date']) .' - ' . _('Order Completed');
               if($_POST['clos'])
               {
                   $sql12="UPDATE purchorders
                    SET status='Closed',
                    stat_comment='" . $StatusComment . "'
                    WHERE orderno='" . $_POST['orderno'] . "'";
        $result12=DB_query($sql12,$db);
               }
               
              
    echo"<tr><td colspan='20'>"; 
    $PONo=$_POST['oredernumb'];
    echo '<table><tr><td></td></tr><tr><td>';
   
    
      echo '<div class=centre>'. _('GRN number'). ' '. $GRN .' '. _('has been processed').'</td></tr>';
        if($cont==1 && $ser==1)
               {
                   echo "<tr><td><b>SERIAL NO:[";
                   for($i=1;$i<=$_POST['nowrec'];$i++)
              {
                  echo "".$display[$i]."";
                  if($i!=$_POST['nowrec'])
                  {
                      echo ",";
                  }
              }
              echo ']</b></td></tr>';
               }
    echo '<tr><td><a href=bio_grm_print.php?orderno='.$PONo.'&grnbatch='.$GRN.'>'. _('Print this Goods Received Note (GRN)').'</a></td></tr></table>';

    }
   
 // echo'</form>';
  
   


if(isset($_GET['select']))
   {
    $id=$_GET['select'];
    $ord=$_GET['ord'];
echo "<input type=hidden name=oredernumb value=".$ord.">";  
    $sql= "SELECT  `purchorderdetails`.`podetailitem`,itemcode,itemdescription, glcode, quantityord, quantityrecd, qtyinvoiced, shiptref, jobref, purchorders.orddate,           purchorderdetails.orderno, suppliers.suppname,purchorders.supplierno
    FROM purchorderdetails
    INNER JOIN purchorders ON purchorders.orderno = purchorderdetails.orderno
    INNER JOIN suppliers ON purchorders.supplierno = suppliers.supplierid
    WHERE  completed =0
    AND itemcode = '$id'  AND purchorderdetails.orderno='$ord'
    ORDER BY podetailitem";
    $result=DB_query($sql,$db); 
    
     echo"<tr><td colspan='2'>";  
    echo"<fieldset style='width:600px;'><legend>Purchase</legend>";
    echo '<table>';
    while($row=DB_fetch_array($result)) 
       {
         //  echo $row['itemdescription'];
        echo "<input type=hidden name=code value=".$id.">";    
        echo "<input type=hidden name=suppno value=".$row['supplierno'].">";
        echo "<input type=hidden name=orderno value=".$row['orderno'].">";
        echo "<input type=hidden name=qtyord value=".$row['quantityord'].">";
        echo "<input type=hidden name=qtyrec value=".$row['quantityrecd'].">";
        echo "<input type=hidden name=podet value=".$row['podetailitem'].">";
        echo "<input type=hidden name=desc value='".$row['itemdescription']."'>";
       $sub=$row['quantityord']-$row['quantityrecd'];
       $item=  $row['itemcode'];
       $sq="Select description from stockmaster where stockid='".$row['itemcode']."'";
           $rt=DB_query($sq,$db);
           $mr=DB_fetch_array($rt);
       echo "<input type=hidden name=substract id=substract value=".$sub.">";
       echo '<tr><td>';
       echo '<table border=1><tr><td>';
       echo "<table>
       <tr><td><b>Supplier name</b></td><td><b>".$row['suppname']."</b></td></tr>";
         echo "<input type=hidden name=suppname value=".$row['suppname'].">";      
         echo"  <tr><td><b>Item name</b></td><td><b>".$mr[0]."</b></td></tr>";
         echo "<input type=hidden name=itm value=".$mr[0].">";
       echo "<tr><td><b>PO no:</b></td><td style='width:50px;'><b>".$row['orderno']."</b></td></tr>";

       echo "<tr><td><b>Ordered quantity:</b></td><td><b>".$row['quantityord']."</b></td></tr>";

       echo "<tr><td><b>Already received quantity:</b></td><td><b>".$row['quantityrecd']."</b></td></tr>";
       echo "<tr><td><b>Balance:</b></td><td><b>".$sub."</b></td></tr>";
       echo "<tr><td><b>Recieved quantity:</b></td><td><input type=text name=sirnew id='sirnew'></td></tr>";
       echo '<tr><td><b>Date:</b></td><td><input type="text" name="date" class=date alt="'.$_SESSION['DefaultDateFormat'].'" value='.date("d/m/Y").' size=10 maxlength=10>
       </td></tr>';
           
     }
    echo '</td></tr><tr><td>';
    echo '</td></tr></table>';
    echo '</td><td>';
      echo '<table>';
     echo "<tr><td><b>Accepted quantity:</b></td><td><input type=text name=nowrec id='nowrec'></td></tr>";
    echo '<tr><td><b>Supplier invoiceno:</b></td><td><input type="text" name="billno" ></td></tr>';
    echo "<tr><td><b>Received by:</b></td><td><input type=text name=recieve value='".$username."'></td></tr>";
    echo '<tr><td><b>Varified by:</b></td><td><input type="text" name="varify"></td></tr>';
    echo '<tr><td><b>Accepted by:</b></td><td><input type="text" name="accept"></td></tr>';
    echo '<tr><td><b>Close this order:</b></td><td> <input type="checkbox"  name="clos" id="clos" ></td></tr>';  
   /* if($_POST['close']=='on')
     {
        echo '<input type=hidden name=check id=check value="2">'; 
     }*/
     echo '</table>';
    echo '</td></tr></table>';
    echo '</tr></td></table>';
    echo '</fieldset>';
    echo '<center><input type=submit name=submit2 value="Generate grn" onclick="if(valid()==1){return false;}"></center>';
  }
    echo"<tr><td colspan='2'>";  
    echo"<fieldset style='width:600px;'><legend>Purchase</legend>";//  
    
     echo ' <div style="height:200px; width: 100%; overflow: scroll;">';
    echo '<table>';
    echo"<tr>";
    if($_POST['combo2'] && $_POST['supply'])
     {
       echo '<th>SLNO:</th><th>ITEM</td><th>PO:NO&DATE</th><th>PO</th><th>QTY</th><th>RECEIVED-QTY</th><th>BALANCE</th><th>SELECT</th></tr>'; 
      $sql2="SELECT *
       FROM `purchorderdetails` , stockmaster, `purchorders`
       WHERE stockmaster.stockid = `purchorderdetails`.itemcode AND (quantityord-quantityrecd)!=0
       AND stockid='".$_POST['combo2']."'
       AND status!='Closed'
       AND purchorders.orderno=`purchorderdetails`.orderno
       AND `purchorders`.supplierno ='".$_POST['supply']."'
       group by  stockmaster.stockid";
       $result2=DB_query($sql2,$db);
        $d=1;// 
    while($row2=DB_fetch_array($result2))
       {    
            $id=$row2['stockid'];
            $order=$row2[1];
            $sub=$row2['quantityord']-$row2['quantityrecd'];
         if($sub>0)  
         {
             
         
         echo "<input type=hidden name=ordernew value=".$order.">";   
            $ff="?select=$id&ord=$order";

echo"<tr style='background:#A8A4DB'><td>$d</td>
<td>".$row2['description']."</td><td>".$row2[1]."</td>
<td>".ConvertSQLDate($row2['deliverydate'])."</td>
       <td>".$row2['quantityord']."</td><td>".$row2['quantityrecd']."</td>
       <td>".$sub."</td>
       <td><a href='#' id='$ff' onclick='dlt(this.id)'>select</a></td></tr>";
                   $d=$d+1;
         }
     }echo '</table></fieldset>';
  }
    else
     if($_POST['combo2'])
      {
       echo '<th>SLNO:</th><th>SUPPLIER</td><th>PO:NO</th><th>DATE</th><th>PO-QTY</th><th>RECEIVED-QTY</th><th>BALANCE</th><th>SELECT</th></tr>'; 
       $sql2="SELECT * FROM `purchorderdetails` ,stockmaster,purchorders where stockmaster.stockid=`purchorderdetails`.itemcode 
       AND  (quantityord-quantityrecd)!=0
       AND purchorders.status!='Closed'
       AND purchorders.orderno=`purchorderdetails`.orderno
        AND stockmaster.stockid='".$_POST['combo2']."'
        GROUP BY stockmaster.stockid"; 
       $result2=DB_query($sql2,$db);//
  
        $d=1;
         while($row2=DB_fetch_array($result2))
             {          $sub=$row2['quantityord']-$row2['quantityrecd'];
         if($sub>0)  
         {
             
             
             
                 $sql5="SELECT `suppname` FROM `suppliers` WHERE supplierid in(select `supplierno` from  `purchorders` where orderno=".$row2[1].")";
                 $result5=DB_query($sql5,$db);
                  while($row5=DB_fetch_array($result5))
                     {
                      $z=$row5['suppname'];
                      }
                    $order=$row2[1];   
                    $id=$row2['stockid'];
                   $ff="?select=$id&ord=$order";
               // $sub=$row2['quantityord']-$row2['quantityrecd'];
                echo"<tr style='background:#A8A4DB'><td>$d</td><td>$z</td><td>".$row2[1]."</td><td>".ConvertSQLDate($row2['deliverydate'])."</td>
                <td>".$row2['quantityord']."</td><td>".$row2['quantityrecd']."</td>
                <td>".$sub."</td>
                 <td><a href='#' id='$ff' onclick='dlt(this.id)'>select</a></td></tr>";
                   $d=$d+1;
            }}
      echo '</table></fieldset>';
    }
    else
    if($_POST['supply'])
    {
       echo '<th>SLNO:</th><th>ITEM</td><th>PO:NO</th><th>DATE</th><th>PO-QTY</th><th>RECEIVED-QTY</th><th>BALANCE</th><th>SELECT</th></tr>'; 
  $sql2="SELECT * FROM `purchorderdetails` ,stockmaster,`purchorders` where `purchorderdetails`.orderno in
        (select orderno from `purchorders` where supplierno='".$_POST['supply']."') AND stockmaster.stockid=`purchorderdetails`.itemcode AND (quantityord-quantityrecd)!=0      
        AND purchorders.status!='Closed'
       AND purchorders.orderno=`purchorderdetails`.orderno
        group by stockmaster.stockid";
      $result2=DB_query($sql2,$db);
            
          $d=1;
    while($row2=DB_fetch_array($result2))
         {  
              $sub=$row2['quantityord']-$row2['quantityrecd'];
         if($sub>0)  
         {
             $id=$row2['stockid'];
         $order=$row2[1];
          $ff="?select=$id&ord=$order";
           // $row2['orderno'];
       //$sub=$row2['quantityord']-$row2['quantityrecd'];
         echo"<tr style='background:#A8A4DB'><td>$d</td><td>".$row2['description']."</td><td>".$row2[1]."</td><td>".ConvertSQLDate($row2['deliverydate'])."</td>
         <td>".$row2['quantityord']."</td><td>".$row2['quantityrecd']."</td>
         <td>".$sub."</td>
         <td><a href='#' id='$ff' onclick='dlt(this.id)'>select</a></td></tr>";
                   $d=$d+1;
         }}echo '</table></fieldset>';
    }
    else
    {
         echo '<th>SLNO:</th><th>ITEM</th><th>PO:NO</th><th>DATE</th><th>PO-QTY</th><th>RECEIVED-QTY</th><th>BALANCE</th><th>SELECT</th></tr>'; 
    $sql2="SELECT * FROM `purchorderdetails` ,stockmaster,purchorders where stockmaster.stockid=`purchorderdetails`.itemcode 
         AND (quantityord-quantityrecd)!=0
         AND purchorders.status!='Closed'
         AND purchorders.orderno=`purchorderdetails`.orderno
         group by stockmaster.stockid";
         $result2=DB_query($sql2,$db);

          $d=1;
    while($row2=DB_fetch_array($result2))
         {
              $sub=$row2['quantityord']-$row2['quantityrecd'];
         if($sub>0)  
         {
          $id=$row2['stockid'];   
          $order=$row2[1];
          $ff="?select=$id&ord=$order";
        //  $sub=$row2['quantityord']-$row2['quantityrecd'];
          echo"<tr style='background:#A8A4DB'><td>$d</td><td>".$row2['description']."</td><td>".$row2[1]."</td><td>".ConvertSQLDate($row2['deliverydate'])."</td>
          <td>".$row2['quantityord']."</td><td>".$row2['quantityrecd']."</td>
          <td>".$sub."</td>
          <td><a href='#' id='$ff' onclick='dlt(this.id)'>select</a></td></tr>";
                   $d=$d+1;
         } }echo '</table></div></fieldset>';//
    }
    echo'</form>';

?>
<script>
function dlt(str){
    //alert(str);
    location.href=str;    
}
function view(str1)
{

  if (str1=="")
  {
  document.getElementById("combo1").focus();
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
  { // alert (str1);
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
          
         // document.getElementById('showloc').style.display = "block";
     document.getElementById("combonew").innerHTML=xmlhttp.responseText; 
     
    }            
  } 
xmlhttp.open("GET","bio_grn_ajax.php?subcatid="+str1,true);//alert(str1);
xmlhttp.send();        
}
function view2(str1)
{
  //ert("hhh");
  if (str1=="")
  {
  document.getElementById("combonew").focus();
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
  { // alert (str1);
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
         // document.getElementById('showloc').style.display = "block";
     document.getElementById("combo2").innerHTML=xmlhttp.responseText; 
     
    }            
  } 
xmlhttp.open("GET","bio_grn_ajax.php?subsubcatid="+str1,true);//alert(str1);
xmlhttp.send();        
}
function view3(str1)
{
//alert("hhhh");
  if (str1=="")
  {
  document.getElementById("combo2").focus();
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
  { // alert (str1);
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
          
         // document.getElementById('showloc').style.display = "block";
     document.getElementById("supply").innerHTML=xmlhttp.responseText; 
     
    }            
  } 
xmlhttp.open("GET","bio_grn_ajax.php?itemsel="+str1,true);//alert(str1);
xmlhttp.send();        
}
function valid()
{ 
  var  str1=document.getElementById("nowrec").value;
  var str2=document.getElementById("substract").value;
  str3=document.getElementById("sirnew").value;
 // var str=document.getElementById("clos").value;
// alert(""+str);  //alert(f);
 var a=str1;
 var b=str2;
 var h=b-a;//alert(h);
 var i=str3;
 var k=i-a;//alert(k);
if(i!="") 
{  if(k<0)
   {
    alert("Entered quantity is greater than delivery quantity");
   return 1;
   }
}  //alert(h); 
 if (clos.checked == 1){
       alert("All the orders will be closed");
}else{
   if(h<0) 
   {
    alert("Entered quantity is greater than delivery quantity ");
   return 1;
   }
  
   //  str3=document.getElementById("check").value;alert(str3);
     if(str1=="" || str1==0 || str1<0) 
  {
    alert("Please enter quantity to accept");
    return 1;
   }
   
}}
function view0(str1)
{
  if (str1=="")
  {
  document.getElementById("combo0").focus();
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
  { // alert (str1);
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
          
         // document.getElementById('showloc').style.display = "block";
     document.getElementById("subcat").innerHTML=xmlhttp.responseText; 
     
    }            
  } 
xmlhttp.open("GET","bio_grn_ajax.php?maincat="+str1,true);//alert(str1);
xmlhttp.send();        
}
</script>