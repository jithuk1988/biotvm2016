<?php
$PageSecurity = 4; 
   
include('includes/session.inc');  
$title = _('Store requistion');                                     
include('includes/header.inc');  
include('includes/DefinePOClass.php');
include('includes/SQL_CommonFunctions.inc');

$identifier=$_POST['identifier']; 
echo'<input type="hidden" name="identifier" value='.$identifier.'>';

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
                 
   echo '<a href="'.$rootpath.'/PDFSrn.php?' . SID . "slno=".$slno. '">' ._('Print Request note') . '</a><br>';  echo '<a href="'.$rootpath.'/stocktransfer.php?' . SID . '">' ._('Back to store request') . '</a><br>';

}
    
elseif(isset($_POST['NewItem']))        {

                  
            
  if (($_POST['fromstoreloc'])==($_POST['storelocto'])){
        
        echo "<b>Both locations cannot be same</b><br>";
        echo '<a href="'.$rootpath.'/ST-Request.php?' . SID . '">' ._('Back to store request') . '</a><br>';
        exit;
    }
                  
         if (($_POST['stockrequest'])==StockTransfer){
             
             
    echo '<form action="'. $_SERVER['PHP_SELF'] . '?' . SID .'" method=post>';
                    echo '<a name="end"></a><div class="centre"><input type=hidden  name="fromstore" value="'.$_POST['fromstoreloc'].'" size=12 maxlength=12></div>';
                   echo '<a name="end"></a><div class="centre"><input type=hidden  name="reqstore" value="'.$_POST['storelocto'].'" size=12 maxlength=12></div>';
                  
                      
                  
                  
                  
                   echo '<div class="page_title_text"><font size=4>Store requisition</font></div><hr>'; 
                   
                    echo '<table cellpadding=2 colspan=7 border=1>';
                        $tableheader = '<tr>
                    <th>' . _('Itemcode') . '</th>
                    <th>' . _('Quantity') . '</th>
                    <th>' . _('To store') . '</th>
                   
                    
                   
                     
                </tr>';
                      
                   
                      echo $tableheader;
                    
                  $counter=0;
                  
                  foreach ($_POST as $key => $value) {
        if (substr($key, 0, 3)=='qty') {
            $ItemCode=substr($key, 3, strlen($key)-3);
            $Quantity=$value;
            $AlreadyOnThisOrder =0;
            
            if ($Quantity>0){
               
                 $sql2='SELECT  quantity
                         FROM locstock  WHERE stockid="'. $ItemCode.'"
                         AND loccode="'.$_POST['fromstoreloc'].'"';
                      $result2 = DB_query($sql2,$db);
                
                while($myrow2=DB_fetch_array($result2)) {    
                
                if ($myrow2[0]>$Quantity)   {
                
                echo '<a name="end"></a><div class="centre"><input type=text  name='."item".$counter.' value="'.$ItemCode.'" size=12 maxlength=12></div>';
                echo '<a name="end"></a><div class="centre"><input type=text  name='."qty".$counter.' value="'.$Quantity.'" size=12 maxlength=12></div>';                
                  echo "<tr><td>$ItemCode</td>
                     <td>$Quantity</td>
                     <td>$_POST[fromstoreloc]</td>
                     
                      </tr>"; 
                      $counter++; 
                }else   {
                    
                    echo "<b>That much quantity not avilable for '".$ItemCode."'</b> ";
                    
                }
                } 
                  
            }
        }
        
                  }
                  echo '<a name="end"></a><br><div class="centre"><input type=hidden  name="counter" value="'.$counter.'" size=12 maxlength=12></div>';
                  
                  echo "</table><br>";
                  
     echo ' <input type=hidden name="fromdate" size="12" maxlength="12" VALUE="NULL">';
     echo '<input type=hidden name="todate" size="12" maxlength="12" VALUE="NULL">';                 
    
     echo '<a name="end"></a><br><div class="centre">Delivery date:<input type=TEXT class=date name="Deliverydate" size=12 
           maxlength=12 VALUE="' . $_POST['Deliverydate'] . '"></div>';
     echo '<input type=hidden  name="Deliverytime" size=12 maxlength=12>';
     echo "<br>"; 
     echo '<a name="end"></a><br><div class="centre"><input type="submit" name="storerequest" value="Place Request"></div>';
     echo "</form>";
             
             
             
             
         }
                  
           
    
}else       {               
    
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
          

    echo "<table cellpadding=1 colspan=7 border=0>";

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

         
          echo '<b>' . $msg . '</b>';


              echo'<input type="hidden" name="stockrequest" value="StockTransfer">';
              echo'<input type="hidden" name="fromstoreloc" value='.$_POST['fromstoreloc'].'>';
              echo'<input type="hidden" name="storelocto" value='.$_POST['storelocto'].'>';
              echo'<input type="hidden" name="Deliverydate" value='.$_POST['Deliverydate'].'>';
    
   
                          
       
    
    if ($PartsDisplayed == $Maximum_Number_Of_Parts_To_Show){

    /*$Maximum_Number_Of_Parts_To_Show defined in config.php */

        prnMsg( _('Only the first') . ' ' . $Maximum_Number_Of_Parts_To_Show . ' ' . _('can be displayed') . '. ' . 
            _('Please restrict your search to only the parts required'),'info');
    }
    
  
       

           
    echo '<a name="end"></a><br><div class="centre"><input type="submit" name="NewItem" value="Place Request"></div>';
    
             
       unset($_POST['Search']);
                  
                
}
?>
