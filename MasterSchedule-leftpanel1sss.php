<?php

$currentyear=2012;

//if(isset($_SESSION['StockID']))     {
//    
//$StockID=$_SESSION['StockID'];    
//}
if((isset($_GET['r']))  AND ($_GET['r']!="undefined"))       {
$SeasonID=$_GET['r'];
$where="WHERE m_season.season_id=$SeasonID    AND
              m_season.season_sub_id=m_sub_season.season_sub_id";
}else       {
    
    
$where="WHERE m_season.is_current=1       AND
            m_season.season_sub_id=m_sub_season.season_sub_id";
}
$sql="SELECT m_season.season_id,
             m_season.season_sub_id,
             m_sub_season.seasonname
      FROM m_season,m_sub_season 
      $where";
$result=DB_query($sql,$db);
$myrow=DB_fetch_array($result);
$SeasonnameID=$myrow[1];
$SeasonID=$myrow[0];
$Seasonname=$myrow[2];

//echo'<input type="hidden" name="SeasonID" value='.$SeasonID.'>';
echo '<tr><td>' . _('Year') . "*</td><input type='hidden' id='masterschedule_year' name='Currentyear' value=$currentyear><td>$currentyear</td></tr>"; 
echo '<tr id="masterschedule_season_text"><td>' . _('Season') . "*</td><td><input type='hidden' name='SeasonID' 
                                                value='$SeasonID' id='masterschedule_selsea' size=25>$Seasonname
                                  </td><td><a onclick='showseasonsel($SeasonID)'>change season</a></td></tr>"; 

$StockID='';
echo '<tr><td>' . _('Item') . "*</td><td><select name='StockID' id='itemcode' onchange='datagridload1(this.value,".$SeasonID.")'>";

            $sql = "SELECT stockmaster.stockid,
                    stockmaster.description                   
                FROM stockmaster
                WHERE stockmaster.stockid in (SELECT itemcode from seasondemands where seasonid=$SeasonID)";
            $result = DB_query($sql,$db); 
$f=0;        if($_SESSION['StockID']==NULL)
            {
                echo'<option>';  }  
while ($myrow= DB_fetch_array($result)) {
            if ( $myrow['stockid']==$_SESSION['StockID']) {
                echo "<option selected value='" . $myrow['stockid'] . "'>" . $myrow['description']; 
            }  else    
            
                   if($myrow['stockid']!=$_SESSION['StockID'])    {
 echo "<option value='" . $myrow['stockid'] . "'>" . $myrow['description']; 
          
        
        $f++;     }
        } //end while loop
            
        echo '</select>'; 
echo"</td></tr>";
if(isset($_SESSION['StockID']))     {
if(!isset($_POST['Duedate']))       {
$sql="SELECT duedate
      FROM mrpdemands
      WHERE stockid='".$_SESSION['StockID']."' AND
            statusid=1 AND 
            season_id=$SeasonID
      ORDER BY duedate 
      ASC LIMIT 1";
$result=DB_query($sql,$db);
$myrow=DB_fetch_array($result);
if($myrow[0]!='')       {
$_POST['Duedate']=ConvertSQLDate($myrow[0]);  
}
}
}
echo '<tr><td>' . _('Date') . "*</td>";
echo"<td><input type='Text' name='Duedate' value='".$_POST['Duedate']."' id='duedate' size=25
            maxlength=12 ></td>";
echo '<tr><td>' . _('Quantity') . "*</td><td><input type='text' id='qantity' name='Quantity' value='".$_POST['Quantity']."' size=25 maxlength=40></td></tr>";

if(isset($_GET['p']))       {
$sql4="SELECT statusid,
              status
       FROM dev_mrpdemandstatus"; 
$result4=DB_query($sql4,$db);  
echo'<tr><td>Status</td><td><select name=statusid>';
while($myrow4=DB_fetch_array($result4))         {
if($myrow4['statusid']==$_POST['Statusid'])     {
    
echo '<option selected value='.$myrow4['statusid'].'>'.$myrow4['status'].'';     
}else{
echo '<option value='.$myrow4['statusid'].'>'.$myrow4['status'].'';
}
    
}
echo'</select></td></tr>';
 
} 


/* ------------------- SHOWING REMAINING QUANTITY TO BE PRODUCED ---------------------- */

    $sqlr="SELECT seasondemands.demandquantity
      FROM seasondemands where itemcode='" . $_SESSION['StockID'] . "' and seasonid=$SeasonID";
             $resultr=DB_query($sqlr,$db);
             $myrowr=DB_fetch_array($resultr);  
             $dem=$myrowr['demandquantity'];
             $sql_mrp="Select sum(quantity) from mrpdemands where stockid='". $_SESSION['StockID'] ."' and season_id=$SeasonID";
             $rst=DB_query($sql_mrp,$db);
             $mrow=DB_fetch_array($rst);
             $sumitem=$mrow[0];
             $rem= $dem-$sumitem;
             
               //echo   $myrowr[0];     
 echo '<tr><td>' . _('Remaining quantity to be produced') . "*</td>
 <td><input type='text' id='tqantity' name='tQuantity' value='".$rem."' size=25 maxlength=40></td></tr>"; 
 
    
    /* ---------------------------------------------------------------------------------------- */



?>
