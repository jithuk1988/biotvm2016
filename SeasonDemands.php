<?php
$PageSecurity = 2;
$PricesSecurity = 9;

include('includes/session.inc');

$title = _('Monthly Demands');

include('includes/header.inc');
$pagetype=3;
include('includes/sidemenu1.php');
include('includes/SQL_CommonFunctions.inc');
include('includes/formload.inc');
?>
<script type="text/javascript">

$(document).ready(function(){  
  $("#right_panel_1").hide();  });

$(document).ready(function(){  
 
  $("#selectiondetails").hide(); 
        
$('.button_details_show').click(function() {
  $('#selectiondetails').slideToggle('slow', function() {
    // Animation complete.
  });
}); 

});

function showDetails(str1,str2)
{
   $("#right_panel_1").show();
 str1=document.getElementById(str1).value;
 str2=document.getElementById(str2).value;  
 
if (str1=="")
  {
  document.getElementById("right_panel_1").innerHTML="";
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
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("right_panel_1").innerHTML=xmlhttp.responseText;  
    document.getElementById('loc').focus(); 
    }
  } 
xmlhttp.open("GET","showproductionhistory.php?p=" + str1 + "&q=" + str2,true);
xmlhttp.send();    
}

function showSeasonDemand(str1)
{

 
if (str1=="")
  {
  document.getElementById("panel_seasdem").innerHTML="";
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
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("panel_seasdem").innerHTML=xmlhttp.responseText;  
    document.getElementById('loc').focus(); 
    }
  } 
xmlhttp.open("GET","showseasondemands.php?p=" + str1 ,true);
xmlhttp.send();    
}

/*function changeSeason(str1,str2)
{

 
if (str1=="")
  {
  document.getElementById("panel_seasdem").innerHTML="";
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
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("panel_seasdem").innerHTML=xmlhttp.responseText;  
    document.getElementById('loc').focus(); 
    }
  } 
xmlhttp.open("GET","changeseasonindemands.php?p=" + str1 + "&q=" + str2,true);
xmlhttp.send();    
} */

//1 function changeGrid(str)
function changeGrid() 
{

 //1 var str1=document.getElementById('seasonyear').value;       alert(str1) ;
 var str1=document.getElementById('seasonyear').value;
 var str2=document.getElementById('season').value;
 //alert(str1) ;
//alert(str2);

if (str1=="")
  {
  document.getElementById("Datagrid").innerHTML="";
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
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("Datagrid").innerHTML=xmlhttp.responseText;  
    document.getElementById('loc').focus(); 
    }
  } 
xmlhttp.open("GET","changeseasondemandsgd.php?year=" + str1 + "&month=" + str2,true);
xmlhttp.send();    

}




</script>


<?php
$fieldid=get_fieldid('seasondemandid','seasondemands',$db);

echo '<p class="page_title_text">' . ' ' . _('Monthly Demands') . '';

echo"<div id=fullbody>";
echo '<form name="SeasonDemandsForm"  method="post" action="' . $_SERVER['PHP_SELF'] . '?' .SID .'">';




if (isset($_POST['submit'])) 
{
    $InputError=0;
   // echo "month";
     $season=$_POST['Season'];
    //echo "<br/>";
    //echo "item";
     $StockID=$_POST['Item'];
    //echo "<br/>";
    //echo "year"; 
     $seasonyear=$_POST['SeasonYear'];
    //echo "<br/>";
//    $sql_seasonid="SELECT season_id
//           FROM m_season
//           WHERE season_sub_id=" . $season . " AND start_eng_year=".$seasonyear;
//    echo"<br />";
//    $result_seasonid = DB_query($sql_seasonid, $db); 
//    $myrow_seasonid = DB_fetch_array($result_seasonid);  
//      echo "season id";  
//    echo $seasonid=$myrow_seasonid[0];
     
    
    if(isset($_POST['DID'])){
        $InputError=1;
         $sql = "UPDATE seasondemands SET
                        demandquantity='" .$_POST['DemandQty']. "' WHERE seasondemandid=".$_POST['DID'];
            $msg = _('The demand record has been updated to the database for') . ' ' . $StockID;
            $result = DB_query($sql,$db,_('The update/addition of the MRP demand record failed because'));
            prnMsg($msg,'success');
     }
     else
     {
     
    $sql_value2="SELECT * FROM seasondemands 
                        WHERE year=".$seasonyear."
                        AND seasonid=".$season."
                        AND itemcode='".$StockID."'";
        //echo"<br />";
        $result_value2=DB_query($sql_value2,$db);
    $myrow_count2 = DB_num_rows($result_value2);
    
    
    if($myrow_count2>0)
        {
                prnMsg('Monthly demand is already entered for this item','warn');
                //confirm("Monthly demand is already entered for this item. Do you want to increase the target?");
        }
    
/*    else{
        if(isset($season) AND $InputError==0){
        
       echo  $sql_value1="SELECT * FROM productionperiod WHERE seasonid=".$seasonid;
        //echo"<br />";
        $result_value1=DB_query($sql_value1,$db);
        $myrow_value1=DB_fetch_array($result_value1);
        $myrow_count = DB_num_rows($result_value1);
        if($myrow_count==0){
                prnMsg('Production Period is not specified for '. $season,'warn');
        }
        else{
        $pro_id=$myrow_value1[0];
        $date1=$myrow_value1['startdate'];
        $date2=$myrow_value1['enddate'];
        $d1=str_replace("-","/",$date1);
        $d2=str_replace("-","/",$date2);
        //$d1="20/03/2011";
//        echo "hh".$d2=FormatDateForSQL($d1);
        //echo"ttttttttttttttttt". $date_trial = strtotime($d1 . " +1 day")."ggggggggggggggg<br />";
//        echo $next_date[]=date("d/m/Y",$date_trial);
        
        $datearr1 = explode("-",$date1); 
        $datearr2 = explode("-",$date2);
//      print_r($datearr1);
        $date_diff= gregoriantojd($datearr2[1], $datearr2[2], $datearr2[0]) - gregoriantojd($datearr1[1], $datearr1[2], $datearr1[0]);
//      echo"Days-".$days=$date_diff+1;
        $sdate=ConvertSQLDate($myrow_value1['startdate']);
        $next_date[]=$sdate; 
        for($i=1;$i<=$date_diff;$i++){
            $date_trial = strtotime($d1 . " +$i day");
            $next_date[]=date("d/m/Y",$date_trial);
            $next_date[$i];
        }
        
//        print_r($next_date);
                  if(isset($pro_id))                          */
        else {
//            echo"haii";
           $sql = "INSERT INTO seasondemands (
                            seasonid,
                            year,
                            itemcode,
                            demandquantity,
                            producedquantity,
                            remarks)
                        VALUES ('" . $season . "',
                            '" . $seasonyear . "',
                            '" . $StockID . "',
                            '" .$_POST['DemandQty']. "',
                            0,
                            0
                        )";
            $msg = _('A new demand record has been added to the database for') . ' ' . $StockID;
                 $result = DB_query($sql,$db,_('The update/addition of the MRP demand record failed because'));
            //echo"<br />";
            
           //for($i=0;$i<=$date_diff;$i++)
            //{
            //$mrpdate=FormatDateForSQL($next_date[$i]);
            
                 $s= date("t", strtotime($seasonyear . "-" . $season . "-01"));
                for($i=1;$i<=$s;$i++)
                {
                 $dmy="$i/$season/$seasonyear";     
               $mrpdate=FormatDateForSQL($dmy);  
            $sql_mrp = "INSERT INTO mrpdemands (
                            stockid,
                            mrpdemandtype,
                            quantity,
                            duedate,
                            remarks,
                            season_id,
                            year)
                        VALUES ('" .$StockID. "',
                            'N',
                            '0',
                            '" . $mrpdate . "',
                            'NULL',
                            '" . $season . "',
                            '" . $seasonyear."'
                        )";
                        //echo"<br />";
            $msg = _('A new demand record has been added to the database for') . ' ' . $StockID;
                 $result = DB_query($sql_mrp,$db,_('The update/addition of the MRP demand record failed because'));
        
            }   //}
            prnMsg($msg,'success');
            echo '<br>';
        }
                                            } 
}
// $month= date(m);

  if($_POST[SeasonYear]!=NULL)
 {
     $current_year=$_POST[SeasonYear];
 }else
 {    
     $current_year=date("Y");
 } 
  if($_POST[Season]!=NULL)
 {
     $month=$_POST[Season];
 }else
 {    
     $month=date("m");
 } 

echo"<div class=panels id=panel_seasdem>";
panel($db,$current_year,$month); 
echo"</div>";

echo"<p><div class='centre' id='buttons'>";

buttons($db);

echo '</div></form>';

echo"<div id='selectiondetails'>";

selectiondetails($db);

echo "</div>";

echo"<div id='Datagrid' style='height:200px; overflow:auto;'>";

datagrid($db,$current_year,$month);

echo "</div>";


echo"</div>";

function panel(&$db,&$current_year,$month){
    
//--------------------------------------------------------------Start of Left Panel1
echo '<table width=100%><tr>';
echo'<td width=50%>';
echo'<div id=left_panel_1>';
echo "<fieldset id='left_panel_1' style='height:150px;'>"; 
echo"<legend><h3>Enter Monthly Demands</h3>";
echo"</legend>";
echo"<table>";

    //$month= date(m);  

//echo $current_month=date("m"); 

echo"<tr>
    <td>". _('Year') .":</td>
    <td><input type='text' name='SeasonYear' id='seasonyear' value='".$current_year."' style='width:190px;' onchange=changeGrid()></td>
    </tr>";
    
    
       //echo $current_year;
       
       
//$sql_season = 'SELECT m_sub_season.season_sub_id,     
//                m_sub_season.seasonname
//                FROM m_sub_season,m_season
//                WHERE m_sub_season.season_sub_id=m_season.season_sub_id
//                AND is_current=1';
//$result_season = DB_query($sql_season,$db);
//$myrow = DB_fetch_array($result_season);
//$seasonname=$myrow[1];
//$seasonnameID=$myrow[0];


echo"<tr>
    <td>". _('Month') .":</td>
    <td><select name='Season' id='season' style='width:190px;' onchange=changeGrid() onkeyup=changeGrid()>";

$sql_season = 'SELECT m_sub_season.season_sub_id, m_sub_season.seasonname FROM m_sub_season';
$result_season = DB_query($sql_season,$db);
while ($myrow = DB_fetch_array($result_season)) {
    
    if ($myrow['season_sub_id']==$month)
    {
        echo '<option selected value="';
    } else 
    {
        if ($f==0) 
        {
            echo '<option>';
        }
        echo '<option value="';
        $f++;
    }
    echo $myrow['season_sub_id'] . '">'.$myrow['seasonname'];  
    echo '</option>';
    
    
/*    if (isset($_GET['Season']) and $myrow['seasonname']==$_GET['Season']) {
         echo "<option selected value='" .$myrow['season_sub_id'] . "'>" . $myrow['seasonname'];
    } else {                                                                                                             
        echo "<option value='" . $myrow['season_sub_id'] . "'>" . $myrow['seasonname'];
    }  */
    
} //end while loop
    
echo"</select>
    </td>
    </tr>";
    
/*echo"<tr>
    <td>". _('Season') .":</td>
    <td><input type='hidden' name='Season' id='season' value='".$seasonnameID."'>$seasonname";
echo"<td>"    ;

echo "<a style='cursor:pointer;'  onclick='changeSeason()'>" . _('Change Season') . '</a><br>';
echo"<tr>"    ;    */
    
//<select name='Season' id='season' style='width:190px;'>
//$sql_season = 'SELECT m_sub_season.season_sub_id,     
//                m_sub_season.seasonname
//  FROM m_sub_season';
//$result_season = DB_query($sql_season,$db);
//while ($myrow = DB_fetch_array($result_season)) {
//    if (isset($_GET['Season']) and $myrow['seasonname']==$_GET['Season']) {
//         echo "<option selected value='" .$myrow['season_sub_id'] . "'>" . $myrow['seasonname'];
//    } else {                                                                                                             
//        echo "<option value='" . $myrow['season_sub_id'] . "'>" . $myrow['seasonname'];
//    }
//    
//} //end while loop
//    
//echo"</select>
 echo"</td>
    </tr>";
    
echo"<tr>
    <td>". _('Item') .":</td>
    <td><select name='Item' id='item' style='width:190px;'>";
$userstockid='';
$sql_item = "SELECT stockid,description
  FROM stockmaster WHERE mbflag='M' AND categoryid !=13
                ORDER BY stockid";
$result_item = DB_query($sql_item,$db); 
while ($myrow = DB_fetch_array($result_item)) {
    if (isset($_GET['Item']) and $myrow['description']==$_GET['Item']) {
         echo "<option selected value='" .$myrow['stockid'] . "'>" . $myrow['description'];
    } else {                                                                                                             
        echo "<option value='" . $myrow['stockid'] . "'>" . $myrow['description'];
    }
    
} //end while loop
     
echo"</select>
    </td>
    </tr>"; 
    
echo"<tr>
    <td>". _('Demand Quantity') .":</td>
    <td><input type='Text' name='DemandQty' id='demandqty' style='width:190px;' onkeyup=showDetails('season','item') onkeychange=showDetails('season','item')></td>
    </tr>";   

echo"</table>";
echo"</fieldset>";
echo'</div>';
echo"</td>";


echo'<td>';    
echo'<div id=right_panel_1>';

echo'</div>'; 
echo"</td></tr></table>";

}




function buttons(&$db){
    
    echo "<table ><tr>";
    echo "<td><input type='Submit' name='submit' VALUE='" . _('Save') . "' onclick='if(log_in()==1)return false'></td>";
    echo "<td><input type='Submit' name='clear' VALUE='" . _('Clear') . "'></td>";
    //echo "<td><input type='Submit' name='delete' VALUE='" . _('Delete') . "'></td>";
    //echo "<td><input type='Button' class='button_details_show' name='details' VALUE='" . _('Details') . "'></td>";
    echo "</tr></table>";
    
}

function selectiondetails(&$db){
    
     echo '<table width=100% colspan=2 border=2 cellpadding=4>
    <tr>
        <th width="33%">' . _('Item Inquiries') . '</th>
        <th width="33%">' . _('Item Transactions') . '</th>
        <th width="33%">' . _('Item Maintenance') . '</th>
    </tr>';
echo"<tr><td  VALIGN=TOP class='menu_group_items'>";

echo"</td></tr>";


echo'</table>';
}


function datagrid(&$db,&$current_year,&$month){

    echo '<table width=100%>
    <tr>
        <th width="10%">' . _('Sl.No') . '</th>
        <th width="33%">' . _('Item') . '</th>
        <th width="33%">' . _('Target') . '</th>
        
        
    </tr>';
    

                 
                  /**************************** grid view ******************************/
                  
                  $sql_list='SELECT seasondemands.seasondemandid,
                      seasondemands.itemcode,
                      seasondemands.demandquantity,
                      seasondemands.producedquantity
                 FROM seasondemands
                 WHERE seasondemands.year='.$current_year.'
                 AND seasondemands.seasonid='.$month.' order by seasondemandid desc';
                  
                  /**********************************************************/
                 
                 
                 
   $result_list = DB_query($sql_list,$db);
    
    $k=0; //row colour counter 
    $slno=0;
    
    while ($myrow = DB_fetch_array($result_list)) 
    {
        if ($k==1)
        {
            echo '<tr class="EvenTableRows" id="'.$myrow['seasondemandid'].'" onclick=showSeasonDemand(this.id)>';
            $k=0;
        } 
        else 
        {
            echo '<tr class="OddTableRows" id="'.$myrow['seasondemandid'].'" onclick=showSeasonDemand(this.id)>';
            $k=1;
        }
        
        $sql2="SELECT description FROM stockmaster WHERE stockid='".$myrow['itemcode']."'";
        $ErrMsg = _('The SQL to find the parts selected failed with the message');
        $result2 = DB_query($sql2,$db,$ErrMsg);
        $myrow2=DB_fetch_array($result2);
    
        $slno++;
    
        printf(" <td>%s</td>      <td>%s</td>               <td>%s</td>
                </tr>",$slno,$myrow2['description'],$myrow['demandquantity'] );
            //,$myrow['']);
    }
echo'<tfoot><tr>';
echo'<td colspan=10>Number of records:'.$slno.'</td>';
echo'</tr></tfoot>';    
echo'</table>'; 
}



// Number of days in a given month of given year 
//echo $s= date("t", strtotime($current_year . "-" . $month . "-01")); 



?>
<script>
document.getElementById('seasonyear').focus();   
//document.forms[0].itemcode.focus();  

function log_in()
{
    
var f=0;
var p=0;

f=common_error('seasonyear','Please enter Year');  
if(f==1) 
{ 
    return f;
}

if(f==0)
{
    f=common_error('demandqty','Please enter Demand Quantity');  
    if(f==1)
    {
        return f; 
    }  
} 
//if(f==0){f=common_error('longdescription','Please enter a Long description');  if(f==1){return f; }  }  
//if(f==0){f=common_error('itempicture','Please select an Item picture');  if(f==1){return f; }  }  
//if(f==0){f=common_error('category','Please select a Category');  if(f==1){return f; }  }  
//if(f==0){f=common_error('volume','Please enter Packaged volume');  if(f==1){return f; }  }

//if(f==0){f=nonvalid('taskname','Please avoid special characters');  if(f==1){return f; }  }
//document.forms[0].itemcode.focus();           

}

</script>