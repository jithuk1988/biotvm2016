<?php

$currentyear=2012;

//if(isset($_SESSION['StockID']))     {
//    
//$StockID=$_SESSION['StockID'];    
//}

$sql="SELECT m_season.season_id,
             m_season.season_sub_id
      FROM m_season
      WHERE m_season.is_current=1";
$result=DB_query($sql,$db);
$myrow=DB_fetch_array($result);
$SeasonnameID=$myrow[1];
$SeasonID=$myrow[0];


echo'<input type="hidden" name="SeasonID" value='.$SeasonID.'>';
echo "<tr><input type='hidden' id='' name='' value=$currentyear></tr>"; 
echo '<tr><td>' . _('Season') . "*</td><td><select name='Season'>";

       $sql1 = 'SELECT m_sub_season.seasonname,
                     season_sub_id
                FROM m_sub_season';
        $result1 = DB_query($sql1,$db);
        
        
        
  $Year=Date("Y"); 
        $f=0;
        while ($myrow1 = DB_fetch_array($result1)) {
            
            if ( $myrow1['season_sub_id']==$SeasonnameID) {
                echo "<option selected value='" . $myrow1['season_sub_id'] . "'>" . $myrow1['seasonname']; 
            } else  if ($f==0){
                         
        echo '<option>';

            }     echo "<option value='" . $myrow1['season_sub_id'] . "'>" . $myrow1['seasonname']; 
           
        $f++;    
        } //end while loop
        echo '</select>'; //--------------</select season>
echo"</td></tr>";
echo '<tr><td>' . _('Location') . "*</td><td><select name='Loccode'>";

        $sql = "SELECT locations.loccode,     
                       locations.locationname                                        
                FROM locations";
            $result = DB_query($sql,$db); 
$f=0;            
while ($myrow= DB_fetch_array($result)) {
            if ( $myrow['loccode']==$_SESSION['UserStockLocation']) {
                echo "<option selected value='" . $myrow['loccode'] . "'>" . $myrow['locationname']; 
            } else if ($f==0){
                         
        echo '<option>';
        }
 echo "<option value='" . $myrow['loccode'] . "'>" . $myrow['locationname']; 
          
        
        $f++;    
        } //end while loop
            
        echo '</select>';  //--------------</select Location>
echo"</td></tr>";

$StockID='';
echo '<tr><td>' . _('Item') . "*</td><td><select name='StockID' id='woentrystockid' onchange='datagridload(this.value,".$SeasonID.")'>";
//$_SESSION['UserStockID']
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
            
        echo '</select>';  //--------------</select Item>
echo"</td></tr>";
if(!isset($_GET['p']))      {
    
$sql8="SELECT duedate,
              demandid,
              quantity     
       FROM mrpdemands
       WHERE stockid='".$_SESSION['StockID']."'     AND
             statusid=4
       ORDER BY duedate
       DESC LIMIT 1";
$result8=DB_query($sql8,$db);
$numrow=DB_num_rows($result8);
$myrow8=DB_fetch_array($result8);
if($numrow>0)      {
$_POST['Duedate']=ConvertSQLDate($myrow8['duedate']);
$_POST['demandno']=$myrow8['demandid'];
$_POST['DemandQuantity']=$myrow8['quantity'];
}
$sql9="SELECT serialno     
       FROM woserialnos
       WHERE stockid='".$_SESSION['StockID']."'
       ORDER BY serialno
       DESC LIMIT 1";
$result9=DB_query($sql9,$db);
$myrow9=DB_fetch_array($result9);
$_POST['Batch']=$myrow9['serialno'];
}

echo '<tr>';
echo"<input type='hidden' class=date alt=".$_SESSION['DefaultDateFormat']." name='Reqd' value='".$_POST['Duedate']."' id='reqd' size=25
            maxlength=12 value=".$_POST['Duedate'].">";
echo '<tr><td>' . _('Required Date') . "*</td>";
echo"<td><input type='hidden' class=date alt=".$_SESSION['DefaultDateFormat']." name='Duedate' value='".$_POST['Duedate']."' id='duedate' size=25
            maxlength=12>".$_POST['Duedate']."</td>"; 
echo '<tr><td>' . _('Demand Quantity') . "</td><td><input type='hidden' id='demandquantity' name='DemandQuantity' value='".$_POST['DemandQuantity']."' size=25 maxlength=40>
".$_POST['DemandQuantity']."</td></tr>";
echo '<tr><td>' . _('WO Quantity') . "*</td><td><input type='text' id='quantity' name='Quantity' value='".$_POST['Quantity']."' size=25 maxlength=40></td></tr>";
echo '<tr><td>' . _('Batch') . "*</td><td><input type='text' id='batch' name='Batch' value='".$_POST['Batch']."' size=25 maxlength=40></td></tr>"; 
if($wono!='')       {
    

echo '<tr><td>' . _('Status') . "*</td><td><select name='Wostatus'>";

        $sql = "SELECT status,
                       statusid                                        
                FROM status";
            $result = DB_query($sql,$db); 
            
while ($myrow= DB_fetch_array($result)) {
            if ( $myrow['statusid']==$_POST['Wostatus']) {
                echo "<option selected value='" . $myrow['statusid'] . "'>" . $myrow['status']; 
            } 
 echo "<option value='" . $myrow['statusid'] . "'>" . $myrow['status']; 
   
        } //end while loop
            
        echo '</select>';  //--------------</select Location>
echo"</td></tr>";   
    
}
echo '<input type="hidden" name=demandno value="'.$_POST['demandno'].'">';
?>