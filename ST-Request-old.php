<?php
 

session_start();
$PageSecurity = 4; 
      include('includes/DefinePOClass.php');
include('includes/SQL_CommonFunctions.inc');
    
       include('includes/session.inc');  
        $title = _('Store requistion');                                     
include('includes/header.inc');
$pagetype=2;
include('includes/sidemenu.php');
$identifier=$_GET['identifier']; 
$Maximum_Number_Of_Parts_To_Show=50;      


if ((isset($_POST['storerequest'])) or (isset($_POST['Request']))){

if (isset($_POST['storerequest'])){
  
    
                    $sql3="SELECT slno 
                          FROM stocktransfer
                          ";
            $ErrMsg3 =  _('The slno cannot be selected from  the database because');
            $DbgMsg3 = _('The SQL statement used to select the  request and failed was');
            $result3 = DB_query($sql3,$db,$ErrMsg3,$DbgMsg3);                    
 
                 while($myrow3=DB_fetch_array($result3)) {  
                     
                     $slno=$myrow3[0] ;
                 }
                 
                 $slno++;
                 
              
                 
                 for ($i=0;$i<$_POST['counter'];$i++){
                     $Itemcode=$_POST['item'. $i];
                              
                   $sql2 = "INSERT INTO stocktransfer( slno,
                          itemcode,
                          requsitionid,
                          quantity,
                          deliverydate,
                          deliverytime,
                          storeid,
                          tostore,
                          pdrfromdate,
                          pdrtodate,
                          requestfor
                          
                          )
                           VALUES ( '" . $slno."',
                                   '" . $Itemcode."',
                                    '" .$_SESSION['UserID']."',
                                    '" .$_POST['qty'. $i]."',
                                    '" . $_POST['Deliverydate']."' ,
                                     '" . $_POST['Deliverytime']."' ,
                                    '" .$_POST['fromstore']."',     
                                    '" . $_POST['reqstore']."',
                                   '" .$_POST['fromdate']."',     
                                  '" .$_POST['todate']."',     
                                    'transfer'
                                          
                                      ) ";
            $ErrMsg2 =  _('The request could not be inserted into the database because');
            $DbgMsg2 = _('The SQL statement used to insert the  request and failed was');
            $result2 = DB_query($sql2,$db,$ErrMsg2,$DbgMsg2);               
              
                        
              
               
                 }
                 
       //             echo '<a href="'.$rootpath.'/stocktransfer.php?' . SID . "identifier=".$identifier. '">' ._('Print Request note') . '</a><br>';   echo '<a href="'.$rootpath.'/stocktransfer.php?' . SID . '">' ._('Back to store request') . '</a><br>';
               //  unset($_POST['processrequest']);
//    
//    
//  include('includes/footer.inc'); 
//    exit;
}





if (isset($_POST['Request'])) {
    
              
                   $sql3="SELECT slno 
                          FROM stocktransfer
                          ";
            $ErrMsg3 =  _('The slno cannot be selected from  the database because');
            $DbgMsg3 = _('The SQL statement used to select the  request and failed was');
            $result3 = DB_query($sql3,$db,$ErrMsg3,$DbgMsg3);                    
 
                 while($myrow3=DB_fetch_array($result3)) {  
                     
                     $slno=$myrow3[0] ;
                 }
                 
                 $slno++;
                 
              
                 
                 for ($i=0;$i<$_POST['counter'];$i++){
                     $Itemcode=$_POST['item'. $i];
                              
                   $sql2 = "INSERT INTO stocktransfer( slno,
                          itemcode,
                          requsitionid,
                          quantity,
                          deliverydate,
                          deliverytime,
                          storeid,
                          tostore,
                          pdrfromdate,
                          pdrtodate,
                          requestfor
                          
                          )
                           VALUES ( '" . $slno."',
                                   '" . $Itemcode."',
                                    '" .$_SESSION['UserID']."',
                                    '" .$_POST['qty'. $i]."',
                                    '" . $_POST['Deliverydate']."' ,
                                    '" . $_POST['Deliverytime']."' ,
                                    '" .$_POST['fromstore']."',     
                                    '" . $_POST['reqstore']."',
                                    '0',
                                    '0',
                                    'fg'
                                          
                                      ) ";
            $ErrMsg2 =  _('The request could not be inserted into the database because');
            $DbgMsg2 = _('The SQL statement used to insert the  request and failed was');
            $result2 = DB_query($sql2,$db,$ErrMsg2,$DbgMsg2);               
              
                        
              
               
                 }
                 
                 //   echo '<a href="'.$rootpath.'/stocktransfer.php?' . SID . "identifier=".$identifier. '">' ._('Print Request note') . '</a><br>';  echo '<a href="'.$rootpath.'/stocktransfer.php?' . SID . '">' ._('Back to store request') . '</a><br>';
               //  unset($_POST['processrequest']);
//    
//    
//  include('includes/footer.inc'); 
//    exit;
}
   echo '<a href="'.$rootpath.'/PDFSrn.php?' . SID . "slno=".$slno. '">' ._('Print Request note') . '</a><br>';  echo '<a href="'.$rootpath.'/stocktransfer.php?' . SID . '">' ._('Back to store request') . '</a><br>';
               //  unset($_POST['processrequest']);
    
    
  include('includes/smenufooter.inc'); 
    exit;
}
  

$counter=0;

              if (isset($_POST['NewItem']))    {
                  
                  
  if (($_POST['fromstoreloc'])==($_POST['storelocto'])){
        
        echo "<b>Both locations cannot be same</b><br>";
        echo '<a href="'.$rootpath.'/ST-Request.php?' . SID . '">' ._('Back to store request') . '</a><br>';
        exit;
    }
                  
         if (($_POST['stockrequest'])==StockTransfer){
             
             
    echo '<form action="'. $_SERVER['PHP_SELF'] . '?' . SID .'" method=post>';
                    echo '<a name="end"></a><div class="centre"><input type=hidden  name="fromstore" value="'.$_POST[fromstoreloc].'" size=12 maxlength=12></div>';
                   echo '<a name="end"></a><div class="centre"><input type=hidden  name="reqstore" value="'.$_POST['storelocto'].'" size=12 maxlength=12></div>';
                  
                      
                  
                  
                  
                   echo '<div class="page_title_text"><font size=4>Store requisition</font></div><hr>'; 
                   
                    echo '<table cellpadding=2 colspan=7 border=1>';
                        $tableheader = '<tr>
                    <th>' . _('Itemcode') . '</th>
                    <th>' . _('Quantity') . '</th>
                    <th>' . _('To store') . '</th>
                   
                    
                   
                     
                </tr>';
                      
                   
                      echo $tableheader;
                    
                  
                  
                  foreach ($_POST as $key => $value) {
        if (substr($key, 0, 3)=='qty') {
            $ItemCode=substr($key, 3, strlen($key)-3);
            $Quantity=$value;
            $AlreadyOnThisOrder =0;
            
            if ($Quantity>0){
                
                
                 echo '<a name="end"></a><div class="centre"><input type=hidden  name='."item".$counter.' value="'.$ItemCode.'" size=12 maxlength=12></div>';
                echo '<a name="end"></a><div class="centre"><input type=hidden  name='."qty".$counter.' value="'.$Quantity.'" size=12 maxlength=12></div>';
                
                
                 $sql2='SELECT  quantity
                         FROM locstock  WHERE stockid="'. $ItemCode.'"
                         AND loccode="'.$_POST[fromstoreloc].'"';
                      $result2 = DB_query($sql2,$db);
                
                while($myrow2=DB_fetch_array($result2)) {    
                
                if ($myrow2[0]>$Quantity)   {
                
                
                  echo "<tr><td>$ItemCode</td>
                     <td>$Quantity</td>
                     <td>$_POST[fromstoreloc]</td>
                     
                      </tr>"; 
                }else   {
                    
                    echo "<b>That much quantity not avilable for '".$ItemCode."'</b> ";
                    
                }
                } 
                $counter++;   
            }
        }
        
                  }
                  echo '<a name="end"></a><br><div class="centre"><input type=hidden  name="counter" value="'.$counter.'" size=12 maxlength=12></div>';
                  
                  echo "</table><br>";
                  
    echo ' <a name="end"></a><br><div class="centre">' . _('Production from') . ': <input type=TEXT name="fromdate" class="date"  size="12" maxlength="12" VALUE="' . $_POST['fromdate'] . '">';
echo '' . _(' To') . ': <input type=TEXT name="todate" class="date"  size="12" maxlength="12" VALUE="' . $_POST['todate'] . '"></div>';                 
    
     echo '<a name="end"></a><br><div class="centre">Delivery date:<input type=TEXT class=date name="Deliverydate" size=12 
           maxlength=12></div>';
     echo '<input type=hidden  name="Deliverytime" size=12 maxlength=12>';
     echo "<br>"; 
     echo '<a name="end"></a><br><div class="centre"><input type="submit" name="storerequest" value="Place Request"></div>';
     echo "</form>";
             
             
             
             
         }
                  
                  
      
              }
              
           
           
     if    (!isset($_POST['NewItem']))   
                  {
                
                      $SQL='SELECT categoryid,
                           categorydescription
                           FROM stockcategory
                          ORDER BY categorydescription';

              $result1 = DB_query($SQL,$db);
                    if (DB_num_rows($result1) == 0) {
                      echo '<p><font size=4 color=red>' . _('Problem Report') . ':</font><br>' . _('There are no stock categories currently defined please use the link below to set them up');
                      echo '<br><a href="' . $rootpath . '/StockCategories.php?' . SID .'">' . _('Define Stock Categories') . '</a>';
                      exit;
                     }

                 echo '<form action="'. $_SERVER['PHP_SELF'] . '?' . SID .'" method=post>';
             echo '<b>' . $msg . '</b>';
             echo '<p class="page_title_text"><img src="'.$rootpath.'/css/'.$theme.'/images/magnifier.png" title="' . _('Search') . '" alt="">' . ' ' . _('Search for Inventory Items'); 
            echo '<table><tr>';
            echo '<td>'. _('In Stock Category') . ':';
            echo '<select name="StockCat">';

             if (!isset($_POST['StockCat'])) {
             $_POST['StockCat'] = "";
                    }

             if ($_POST['StockCat'] == "All") {
             echo '<option selected value="All">' . _('All');
               }                else {
            echo '<option value="All">' . _('All');
                }

             while ($myrow1 = DB_fetch_array($result1)) {
              if ($myrow1['categoryid'] == $_POST['StockCat']) {
               echo '<option selected VALUE="' . $myrow1['categoryid'] . '">' . $myrow1['categorydescription'];
                } else {
                echo '<option value="' . $myrow1['categoryid'] . '">' . $myrow1['categorydescription'];
        }
          }

         echo '</select>';
               echo '<td>'. _('Enter partial') . '<b> ' . _('Description') . '</b>:</td><td>';


           if (isset($_POST['Keywords'])) {
              echo '<input type="text" name="Keywords" value="' . $_POST['Keywords'] . '" size=20 maxlength=25>';
            } else {
            echo '<input type="text" name="Keywords" size=20 maxlength=25>';
          }

echo '</td></tr><tr><td></td>';

echo '<td><font size 3><b>' . _('OR') . '</b></font>' . _('Enter partial') .' <b>'. _('Stock Code') . '</b>:</td>';
echo '<td>';

if (isset($_POST['StockCode'])) {
    echo '<input type="text" name="StockCode" value="'. $_POST['StockCode'] . '" size=15 maxlength=18>';
} else {
    echo '<input type="text" name="StockCode" size=15 maxlength=18>';
}

echo '</td></tr></table><br>';

echo '<div class="centre"><input type=submit name="Search" value="'. _('Search Now') . '"></div><hr>';        
       
 //unset($_POST['NewItem']);   
                            
               }        

                                                                                  
      
   
         
 if (isset($_POST['Search']) AND (!isset($_POST['fromdate'])) AND (!isset($_POST['todate'])) AND (!isset($_POST['itemname']))) {
    
               
    
         echo '<form action="'. $_SERVER['PHP_SELF'] . '?' . SID .'" method=post>';                       

    If ($_POST['Keywords'] AND $_POST['StockCode']) {
        $msg=_('Stock description keywords have been used in preference to the Stock code extract entered');
    }
    If ($_POST['Keywords']) {
        //insert wildcard characters in spaces

        $i=0;
        $SearchString = '%';
        while (strpos($_POST['Keywords'], ' ', $i)) {
            $wrdlen=strpos($_POST['Keywords'],' ',$i) - $i;
            $SearchString=$SearchString . substr($_POST['Keywords'],$i,$wrdlen) . '%';
            $i=strpos($_POST['Keywords'],' ',$i) +1;
        }
        $SearchString = $SearchString. substr($_POST['Keywords'],$i).'%';

        if ($_POST['StockCat']=='All'){
            $sql = "SELECT stockmaster.stockid,
                    stockmaster.description,
                    stockmaster.units
                FROM stockmaster INNER JOIN stockcategory
                ON stockmaster.categoryid=stockcategory.categoryid
                WHERE stockmaster.mbflag!='D'
                AND stockmaster.mbflag!='A'
                AND stockmaster.mbflag!='K'
                and stockmaster.discontinued!=1
                AND stockmaster.description " . LIKE . " '$SearchString'
                ORDER BY stockmaster.stockid";
        } else {
            $sql = "SELECT stockmaster.stockid,
                    stockmaster.description,
                    stockmaster.units
                FROM stockmaster INNER JOIN stockcategory
                ON stockmaster.categoryid=stockcategory.categoryid
                WHERE stockmaster.mbflag!='D'
                AND stockmaster.mbflag!='A'
                AND stockmaster.mbflag!='K'
                and stockmaster.discontinued!=1
                AND stockmaster.description " . LIKE . " '$SearchString'
                AND stockmaster.categoryid='" . $_POST['StockCat'] . "'
                ORDER BY stockmaster.stockid";
        }

    } elseif ($_POST['StockCode']){

        $_POST['StockCode'] = '%' . $_POST['StockCode'] . '%';

        if ($_POST['StockCat']=='All'){
            $sql = "SELECT stockmaster.stockid,
                    stockmaster.description,
                    stockmaster.units
                FROM stockmaster INNER JOIN stockcategory
                ON stockmaster.categoryid=stockcategory.categoryid
                WHERE stockmaster.mbflag!='D'
                AND stockmaster.mbflag!='A'
                AND stockmaster.mbflag!='K'
                and stockmaster.discontinued!=1
                AND stockmaster.stockid " . LIKE . " '" . $_POST['StockCode'] . "'
                ORDER BY stockmaster.stockid";
        } else {
            $sql = "SELECT stockmaster.stockid,
                    stockmaster.description,
                    stockmaster.units
                FROM stockmaster INNER JOIN stockcategory
                ON stockmaster.categoryid=stockcategory.categoryid
                WHERE stockmaster.mbflag!='D'
                AND stockmaster.mbflag!='A'
                AND stockmaster.mbflag!='K'
                and stockmaster.discontinued!=1
                AND stockmaster.stockid " . LIKE . " '" . $_POST['StockCode'] . "'
                AND stockmaster.categoryid='" . $_POST['StockCat'] . "'
                ORDER BY stockmaster.stockid";
        }

    } else {
        if ($_POST['StockCat']=='All'){
            $sql = "SELECT stockmaster.stockid,
                    stockmaster.description,
                    stockmaster.units
                FROM stockmaster INNER JOIN stockcategory
                ON stockmaster.categoryid=stockcategory.categoryid
                WHERE stockmaster.mbflag!='D'
                AND stockmaster.mbflag!='A'
                AND stockmaster.mbflag!='K'
                and stockmaster.discontinued!=1
                ORDER BY stockmaster.stockid";
        } else {
            $sql = "SELECT stockmaster.stockid,
                    stockmaster.description,
                    stockmaster.units
                FROM stockmaster INNER JOIN stockcategory
                ON stockmaster.categoryid=stockcategory.categoryid
                WHERE stockmaster.mbflag!='D'
                AND stockmaster.mbflag!='A'
                AND stockmaster.mbflag!='K'
                and stockmaster.discontinued!=1
                AND stockmaster.categoryid='" . $_POST['StockCat'] . "'
                ORDER BY stockmaster.stockid";
        }
    }

    $ErrMsg = _('There is a problem selecting the part records to display because');
    $DbgMsg = _('The SQL statement that failed was');
    $SearchResult = DB_query($sql,$db,$ErrMsg,$DbgMsg);

    if (DB_num_rows($SearchResult)==0 && $debug==1){
        prnMsg( _('There are no products to display matching the criteria provided'),'warn');
    }
    if (DB_num_rows($SearchResult)==1){

        $myrow=DB_fetch_array($SearchResult);
        $_GET['NewItem'] = $myrow['stockid'];
        DB_data_seek($SearchResult,0);
    }
          

    echo "<table cellpadding=1 colspan=7 border=1>";

    $tableheader = "<tr>
            <th>" . _('Code')  . "</th>
            <th>" . _('Description') . "</th>
            <th>" . _('Units') . "</th>
            <th><a href='#end'>"._('Go to end of list')."</a></th>
            </tr>";
    echo $tableheader;

    $j = 1;
    $k=0; //row colour counter

    while ($myrow=DB_fetch_array($SearchResult)) {

        if ($k==1){
            echo '<tr class="EvenTableRows">';
            $k=0;
        } else {
            echo '<tr class="OddTableRows">';
            $k=1;
        }

        $filename = $myrow['stockid'] . '.jpg';
        if (file_exists( $_SESSION['part_pics_dir'] . '/' . $filename) ) {

            $ImageSource = '<img src="'.$rootpath . '/' . $_SESSION['part_pics_dir'] . '/' . $myrow['stockid'] . 
                '.jpg" width="50" height="50">';

        } else {
            $ImageSource = '<i>'._('No Image').'</i>';
        }

            $uomsql='SELECT conversionfactor, suppliersuom
                    FROM purchdata
                    WHERE supplierno="'.$_SESSION['PO'.$identifier]->SupplierID.'"
                    AND stockid="'.$myrow['stockid'].'"';

            $uomresult=DB_query($uomsql, $db);
            if (DB_num_rows($uomresult)>0) {
                $uomrow=DB_fetch_array($uomresult);
                if (strlen($uomrow['suppliersuom'])>0) {
                    $uom=$uomrow['suppliersuom'];
                } else {
                    $uom=$myrow['units'];
                }
            } else {
                $uom=$myrow['units'];
            }
            printf("<td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td><input class='number' type='text' size=6 value=0 name='qty%s'></td>
            </tr>",
            $myrow['stockid'],
            $myrow['description'],
            $uom,
            $ImageSource,
            $myrow['stockid']);

        $PartsDisplayed++;
        if ($PartsDisplayed == $Maximum_Number_Of_Parts_To_Show){
            break;
        }
#end of page full new headings if
    }
#end of while loop
    echo '</table>';
    
         echo "<br>";
         
          echo '<b>' . $msg . '</b>';
               echo '<table><tr>';
               echo '<td>'. _('Stock request for') . ':';
               echo '<select name="stockrequest" >';
              echo '<option selected VALUE="finishedgoods">Finished Goods</option>';
               echo '<option selected VALUE="StockTransfer">Stock Transfer</option>';
              echo "</select>";
              echo"</table>";
         
      

    //echo '<a name="end"></a><br><div class="centre">Store id:<input type=TEXT  name="Storeid" size=12 maxlength=12></div>';
    $LocResult = DB_query("SELECT locationname, loccode FROM locations",$db);

              $SQL='SELECT locationname,loccode
                FROM locations ORDER BY loccode';

              $result1 = DB_query($SQL,$db);  
               echo '<b>' . $msg . '</b>';
               echo '<table><tr>';
               echo '<td>'. _('Stock Transfer from') . ':';
               echo '<select name="fromstoreloc" >';
            
            
            
     if (!isset($_POST['storeloc'])) {
              $_POST['fromstoreloc'] = "";
               }
                    
   
              while ($myrow1 = DB_fetch_array($result1)) {
               if ($myrow1['loccode'] == $_POST['fromstoreloc']) {
                    echo '<option selected VALUE="' . $myrow1['loccode'] . '">' . $myrow1['locationame'];
                } else {
                    echo '<option value="' . $myrow1['loccode'] . '">' . $myrow1['locationname'];
                    
     }
              }
              
              
           
     
     
       $SQL='SELECT locationname,loccode
                FROM locations ORDER BY loccode';

              $result1 = DB_query($SQL,$db);  
      echo '<table><tr>';
               echo '<td>'. _('To store ') . ':';
               echo '<select name="storelocto" >';
            
            
            
     if (!isset($_POST['storelocto'])) {
              $_POST['storelocto'] = "";
               }
                    
   
              while ($myrow1 = DB_fetch_array($result1)) {
               if ($myrow1['loccode'] == $_POST['storelocto']) {
                    echo '<option selected VALUE="' . $myrow1['loccode'] . '">' . $myrow1['locationame'];
                } else {
                    echo '<option value="' . $myrow1['loccode'] . '">' . $myrow1['locationname'];
                    
     }
       

   }    
   echo "</select>";
   echo "</table>";
   echo "<br>";
    
   
                          
       
    
    if ($PartsDisplayed == $Maximum_Number_Of_Parts_To_Show){

    /*$Maximum_Number_Of_Parts_To_Show defined in config.php */

        prnMsg( _('Only the first') . ' ' . $Maximum_Number_Of_Parts_To_Show . ' ' . _('can be displayed') . '. ' . 
            _('Please restrict your search to only the parts required'),'info');
    }
    
  
       

           
    echo '<a name="end"></a><br><div class="centre"><input type="submit" name="NewItem" value="Place Request"></div>';
    
             
       unset($_POST['Search']);
                  
                
} #end if SearchResults to show   
      
      // echo "</form>" ;                  
                
                
                
      if ((isset($_POST['fromdate'])) AND (isset($_POST['todate'])) AND (isset($_POST['itemname']))){
    
      
      
        echo '<div class="page_title_text"><font size=4>Store requisition</font></div><hr>'; 
                   
                    echo '<table cellpadding=2 colspan=7 border=1>';
                        $tableheader = '<tr>
                    <th>' . _('Itemcode') . '</th>
                    <th>' . _('From Storeid') . '</th>  
                    <th>' . _('To Storeid') . '</th>  
                    <th>' . _('Production from') . '</th>  
                    <th>' . _('Production to') . '</th>  
                    <th>' . _('Deliverydate') . '</th>
                    <th>' . _('Quantity') . '</th>
                    
                     
                </tr>';
                
                 echo $tableheader;
                 
                 
                 
                 
                  printf("<td>%s</td>
                          <td>%s</td>
                          <td>%s</td>
                          <td>%s</td>
                          <td>%s</td>
                          <td>%s</td>
                          <td><input class='number' type='text' size=6 value=0 name='qty%s'></td>
            </tr>",
            $_POST['itemname'],
            $myrow['description'],
            $uom,
            
            $_POST['fromdate'],
            $_POST['todate'],
            $myrow['stockid']);

            echo '<a name="end"></a><br><div class="centre"><input type="submit" name="NewItem" value="Place Request"></div>';
                  echo "</form>" ;
         
    
}                      
  
  
         
        
     



// end of showing search facilities

         
     
     
      
    
      //  echo "</form>" ;
               


include('includes/smenufooter.inc'); 
?>


