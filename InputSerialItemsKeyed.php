<?php
/* $Id: InputSerialItemsKeyed.php 4588 2011-06-05 23:46:02Z daintree $*/
/*Input Serial Items - used for inputing serial numbers or batch/roll/bundle references
for controlled items - used in:
- ConfirmDispatchControlledInvoice.php
- GoodsReceivedControlled.php
- StockAdjustments.php
- StockTransfers.php
- CreditItemsControlled.php

*/

//we start with a batch or serial no header and need to display something for verification...
global $tableheader;

if (isset($_GET['LineNo'])){
	$LineNo = $_GET['LineNo'];
} elseif (isset($_POST['LineNo'])){
	$LineNo = $_POST['LineNo'];
}

/*Display the batches already entered with quantities if not serialised */

echo '<table class="selection">
		<tr><td valign=top>
			<table class="selection">';
echo $tableheader;

$TotalQuantity = 0; /*Variable to accumulate total quantity received */
$TotalQuantity = 0; /*Variable to accumulate total quantity received */
$RowCounter =0;

$k=0;

foreach ($LineItem->SerialItems as $Bundle){
//echo $Bundle->BundleQty;
	if ($RowCounter == 10){
		echo $tableheader;
		$RowCounter =0;
	} else {
		$RowCounter++;
	}

	if ($k==1){
		echo '<tr bgcolor="#CCCCCC">';
		$k=0;
	} else {
		echo '<tr bgcolor="#EEEEEE">';
		$k=1;
	}

	echo '<td>' . $Bundle->BundleRef . '</td>';

	if ($LineItem->Serialised==0 and $Perishable==0){
		echo '<td class=number>' . number_format($Bundle->BundleQty, $LineItem->DecimalPlaces) . '</td>';
	} else if ($LineItem->Serialised==0 and $Perishable==1){
		echo '<td class=number>' . number_format($Bundle->BundleQty, $LineItem->DecimalPlaces) . '</td>';
		echo '<td class=number>' . $Bundle->ExpiryDate . '</td>';
	}

	echo '<td><a href="' . $_SERVER['PHP_SELF'] . '?identifier=' . $identifier . '&Delete=' . $Bundle->BundleRef . '&StockID=' . $LineItem->StockID . '&LineNo=' . $LineNo .'">'. _('Delete'). '</a></td></tr>';

	 $TotalQuantity += $Bundle->BundleQty;
}


/*Display the totals and rule off before allowing new entries */
if ($LineItem->Serialised==1){
	echo '<tr><td class=number><B>'. _('Total Quantity'). ': ' . number_format($TotalQuantity,$LineItem->DecimalPlaces) . '</b></td></tr>';
} else {
	echo '<tr><td class=number><B>'. _('Total Quantity'). ':</b></td><td class=number><b>' . number_format($TotalQuantity,$LineItem->DecimalPlaces) . '</b></td></tr>';
}

/*Close off old table */
echo '</table></td><td valign=top>';

/*Start a new table for the Serial/Batch ref input  in one column (as a sub table
then the multi select box for selection of existing bundle/serial nos for dispatch if applicable*/
//echo '<TABLE><TR><TD valign=TOP>';

/*in the first column add a table for the input of newies */
echo '<table class=selection>';
echo $tableheader;


echo '<form action="' . $_SERVER['PHP_SELF'] . '" name="Ga6uF5Wa" method="post">
      <input type=hidden name="LineNo" value="' . $LineNo . '">
      <input type=hidden name="StockID" value="' . $StockID . '">
      <input type=hidden name="identifier" value="' . $identifier . '">
      <input type=hidden name="EntryType" value="KEYED">';
echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';
if ( isset($_GET['EditControlled']) ) {
	$EditControlled = isset($_GET['EditControlled'])?$_GET['EditControlled']:false;
} elseif ( isset($_POST['EditControlled']) ){
	$EditControlled = isset($_POST['EditControlled'])?$_POST['EditControlled']:false;
} else {
	$EditControlled=false;
}

$StartAddingAt = 0;
/*if ($EditControlled){

	foreach ($LineItem->SerialItems as $Bundle){

		echo '<tr><td valign=top><input type=text name="SerialNo'. $StartAddingAt .'"
			value="'.$Bundle->BundleRef.'" size=21  maxlength=20></td>';

		/*if the item is controlled not serialised - batch quantity required so just enter bundle refs
		into the form for entry of quantities manually 

		if ($LineItem->Serialised==1){
			echo '<input type=hidden name="Qty' . $StartAddingAt .'" Value=1></TR>';
		} else if ($LineItem->Serialised==0 and $Perishable==1) {
			echo '<td><input type=text class="number" name="Qty' . $StartAddingAt .'" size=11
				value="'. number_format($Bundle->BundleQty, $LineItem->DecimalPlaces). '" maxlength=10></tr>';
		} else {
			echo '<td><input type=text class="number" name="Qty' . $StartAddingAt .'" size=11
				value="'. number_format($Bundle->BundleQty, $LineItem->DecimalPlaces). '" maxlength=10></tr>';
		}

		$StartAddingAt++;
	}
}*/
$stockid=$StockID;
$location=$LocationOut;
 $SQL="SELECT serialno  FROM `stockserialitems` WHERE `stockid` LIKE '$stockid' AND `loccode` LIKE '$location'";
$res=DB_query($SQL,$db);
$i=0;

echo '</table>';
echo "<div style='height:300px; overflow:scroll;'>";
echo '<table width="100%" >';
 while ($myrow=DB_fetch_array($res))
     {
          if ($k==1){
            echo '<tr class="EvenTableRows">';
            $k=0;
        } else {
            echo '<tr class="OddTableRows">';
            $k++;
        }
     echo '<td>'.$myrow['serialno'].'</td><td valign=top>
     <input type=checkbox name="SerialNo'. ($StartAddingAt+$i) .'" value="'.$myrow['serialno'].'" size=21  maxlength=20></td>';
    if ($LineItem->Serialised==1){
        echo '<input type=hidden name="Qty' . ($StartAddingAt+$i) .'" Value=1></tr>';
    } 
   
      $i++;
     }
  echo'</tr>';
echo '</table>';  
  echo'</div>';/*
for ($i=0;$i < 10;$i++){


	echo '<tr><td valign=top><input type=text name="SerialNo'. ($StartAddingAt+$i) .'" size=21  maxlength=20></td>';

	if the item is controlled not serialised - batch quantity required so just enter bundle refs
	into the form for entry of quantities manually 

 if ($LineItem->Serialised==1){
		echo '<input type=hidden name="Qty' . ($StartAddingAt+$i) .'" Value=1></tr>';
	} else if ($LineItem->Serialised==0 and $Perishable==1) {
		echo '<td><input type=text class="number" name="Qty' . ($StartAddingAt+$i) .'" size=11  maxlength=10></td>';
		echo '<td><input type=text class="date" name="ExpiryDate' . ($StartAddingAt+$i) .'" size=11
		 value="" alt="'.$_SESSION['DefaultDateFormat'].'"  maxlength=10></td></tr>';
	} else {
		echo '<td><input type=text class="number" name="Qty' . ($StartAddingAt+$i) .'" size=11  maxlength=10></tr>';
	}
}
*/

echo '<br><div class=centre><input type=submit name="AddBatches" value="'. _('Enter'). '"></div>';
echo '</form></td><td valign=top>';
if ($ShowExisting){
	include('includes/InputSerialItemsExisting.php');
}
echo '</td></tr></table><script type="text/javascript">
//<![CDATA[
document.Ga6uF5Wa.SerialNo0.focus();
//]]>
</script>'; /*end of nested table */
?>