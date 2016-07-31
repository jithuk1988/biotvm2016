<?php

$PageSecurity = 5;
include('includes/session.inc');

$currentyear=date("Y");


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
echo '<tr><td>' . _('Season') . "*</td><td><select name='Season' id='season'>";

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

$StockID='';
echo '<tr><td>' . _('Item') . "*</td><td><select name='StockID' id='stockid'>";
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


  echo'<tr><td><input type="button" id=1 value="view as html" onclick=viewreport(this.id,"'.$reportof.'")></td></tr>'; 
  echo'<tr><td><input type="button" id=2 value="view as excel" onclick=viewreport(this.id,"'.$reportof.'")></td></tr>';
?>
