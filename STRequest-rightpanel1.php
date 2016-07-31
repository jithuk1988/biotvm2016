<?php
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

            echo '<td>'. _('In Stock Category') . ':</td><td>';
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

         echo '</select></td><tr>';
               echo '<td>'. _('Enter partial') . '<b> ' . _('Description') . '</b>:</td><td>';


           if (isset($_POST['Keywords'])) {
              echo '<input type="text" name="Keywords" value="' . $_POST['Keywords'] . '" size=20 maxlength=25>';
            } else {
            echo '<input type="text" name="Keywords" size=20 maxlength=25>';
          }

echo '</td></tr><tr>';

echo '<td><font size 3><b>' . _('OR') . '</b></font>' . _('Enter partial') .' <b>'. _('Stock Code') . '</b>:</td>';
echo '<td>';

if (isset($_POST['StockCode'])) {
    echo '<input type="text" name="StockCode" value="'. $_POST['StockCode'] . '" size=15 maxlength=18>';
} else {
    echo '<input type="text" name="StockCode" size=15 maxlength=18>';
}

echo '</td></tr>';

    
?>
