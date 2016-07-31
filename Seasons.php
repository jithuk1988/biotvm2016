<?php
/* $Revision: 1.5 $ */
/* $Id: MRPDemands.php 3910 2010-09-30 15:01:39Z tim_schofield $*/
// Add, Edit, Delete, and List MRP demand records. Table is mrpdemands.
// Have separate functions for each routine. Use pass-by-reference - (&$db,&$StockID) -
// to pass values of $db and $StockID to functions. - when just used $db as variable,
// got error: Catchable fatal error: Object of class mysqli could not be converted to string

$PageSecurity=9;

include('includes/session.inc');
$title = _('Season master');
include('includes/header.inc'); 

if (isset($_GET['DemandID'])) {  
    
  $DemandID=$_GET['DemandID']; 
  listall($db,$DemandID);
  include('includes/footer.inc');
  exit; 
}
if (isset($_POST['DemandID'])) {  
    
  $DemandID=$_POST['DemandID']; 
}

if (isset($_POST['submit'])) { 
    $SeasonID=$_POST['SeasonName'];
    submit($db,$SeasonID,$DemandID);
} elseif (isset($_GET['delete'])) {
        echo $DemandID;
        echo $StockID;
//    delete($db,$DemandID,"",$StockID);
} elseif (isset($_GET['listall'])) {
    listall($db,"");
} elseif (isset($_POST['listsome'])) { 
    listall($db,$DemandID);
} else {
        listall($db,$DemandID);  
}


function submit(&$db,&$SeasonID,$DemandID)  {   
 
if(!isset($DemandID))       {
    
    $sql3="SELECT *
           FROM m_sub_season
           WHERE seasonname='" . $SeasonID . "'";
    $result3 = DB_query($sql3, $db); 
    
     if (DB_num_rows($result3) > 0){
        $InputError = 1;                                                    
        prnMsg(_('Record already exists for season name :'.$SeasonID.''),'error');
         
    }

    if ($InputError !=1){


    // If $myrow[0] from SELECT count(*) is zero, this is an entry of a new record
           $sql2 = "INSERT INTO m_sub_season (
                            seasonname)
                        VALUES ('" .$SeasonID. "')";   
            $msg = _('A new season record has been added to the database for') . ' ' . $SeasonID;  
                        $result2 = DB_query($sql2,$db,_('The update/addition of the season record failed because'));
        prnMsg($msg,'success');                   
  }

    }else if((isset($DemandID)) AND ($DemandID!=''))      {
      
 
      
      $sql2= "UPDATE m_sub_season SET
                            seasonname='" . $SeasonID . "'
             WHERE season_sub_id=$DemandID";
             
       $msg = _('The season record has been updated for') . ' ' . $SeasonID;  
                   $result2 = DB_query($sql2,$db,_('The update/addition of the season record failed because'));
        prnMsg($msg,'success');    
  }                
             


        echo '<br>';


        
        unset ($_POST['SeasonName']);
        unset ($StockID);
        unset ($DemandID);
        listall($db,$StockID,""); 

} // End of function submit()

function listall(&$db,$DemandID)  {

 echo "<div id='mid_content'>";
if(isset($DemandID))    {
    
   $_POST['SeasonName']=$_GET['Season'];
}
    echo '<form action=' . $_SERVER['PHP_SELF'] . '?' . SID .' method=post>';
    echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';
             
                 
    $sql = 'SELECT m_sub_season.season_sub_id,
                   m_sub_season.seasonname                                   
            FROM m_sub_season';

    $ErrMsg = _('The SQL to find the parts selected failed with the message');
    $result = DB_query($sql,$db,$ErrMsg);
             $i=  Date('Y-m-d');
        list($year, $month, $day) = split('[/.-]', $i); 
     echo"<table width=100%>";   
    echo"<tr><th colspan=2 align='center'>";  
                if($part!=='')    {               
            
           
    echo"<strong align='center'> Season details";  
        }
    echo"</th></tr><tr><td valign=top width=50%>";
    
    
    echo '<table cellpadding=2 colspan=7 class=selection >'; 
    
        echo"<tr bgcolor=#ccc><td align='center' colspan=4>";  
        
            
    echo"<strong align='center'> Enter demands for </strong>";  

    echo"</td><tr>";
    
       
                $TableHeader = '<tr> 
                        <th>' . _('Sl no:') . '</th>                          
                        <th>' . _('Season name') . '</th>
                        <th colspan=2></th> 
                   </tr>';      
      

        echo $TableHeader; 
        $j = 1;
        $k = 0;                     
    $ctr = 0;  
    $n=1;  
    while ($myrow = DB_fetch_row($result)) {
        
 
                         if ($k==1){
                echo '<tr class="EvenTableRows">';
                $k=0;
            } else {
                echo '<tr class="OddTableRows">';
                $k++;
            }  $tabindex=$j+4;
         
        //$displaydate = ConvertSQLDate($myrow3[0]);
        $ctr++;

                $d=$myrow[5];
        printf("<td>%s</td>                 
                <td align='left'>%s</td> 
                <td><a href=\"%s&DemandID=%s&Season=$myrow[1]\">" . _('Edit') . "</td>
                <td><a href=\"%s&DemandID=%s&delete=yes\">" . _('Delete') ."</td>
                ",
                $n,
                $myrow[1], 
                $_SERVER['PHP_SELF'] . '?' . SID,
                $myrow[0], 
                $_SERVER['PHP_SELF'] . '?' . SID,
                $myrow[0]);   
            
  
            $j++;
            $n++;        
    }                
               echo $d; 
                 
                if ($part!=='')  {
        
                 if ($k==1){
                echo '<tr class="EvenTableRows">';
                $k=0;
            } else {
                echo '<tr class="OddTableRows">';
                $k++;
            }
                               
            
            echo"<td></td>";
            echo"<td align=right><input type='Text' name='SeasonName' 
            maxlength=60 value='" . $_POST['SeasonName'] . "'></td>";
            if (isset($DemandID)) {
              echo"<input type='hidden' name='DemandID' value=$DemandID>";
             echo"<td><input type='submit' name='submit' value='update'></td> <td><a href='Seasons.php?'>Add new</a></td>";   
            }
            else {
                
              echo"<td><input type='submit' name='submit' value='submit'></td>";   
            }   
    }

            echo"</table></td>"; 
            echo "<td width=50% valign=top>";
            echo"<table><tr><td>"; 
     
     if(isset($DemandID))    {
    
    $sql2= "SELECT m_season.startdate,
                   m_season.enddate
            FROM m_season
            WHERE season_id=$DemandID";
    $result2=DB_query($sql2,$db);
    $myrow2=DB_fetch_array($result2);
   $_POST['startdate']=ConvertSQLDate($myrow2[0]);
   $_POST['enddate']=ConvertSQLDate($myrow2[1]); 
}
    echo '<form action=' . $_SERVER['PHP_SELF'] . '?' . SID .' method=post>';
    echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';
             
                 
    $sql = 'SELECT m_sub_season.season_sub_id,
                   m_sub_season.seasonname,
                   m_season.season_id,
                   m_season.startdate,     
                   m_season.enddate                 
            FROM m_sub_season,m_season
            WHERE m_season.season_sub_id=m_sub_season.season_sub_id
            ORDER BY m_season.startdate';
    //echo "<br/>sql is $sql<br/>";
    $ErrMsg = _('The SQL to find the parts selected failed with the message');
    $result = DB_query($sql,$db,$ErrMsg);
             $i=  Date('Y-m-d');
        list($year, $month, $day) = split('[/.-]', $i); 
    echo"<tr bgcolor=#ccc><td align='center'>";  
                if($part!=='')    {               
            
           
    echo"<strong align='center'> Season details";  
        }
    echo"</td><tr><td>";
    echo '<table cellpadding=2 colspan=7 class=selection>';    

                $TableHeader = '<tr> 
                        <th>' . _('Season id') . '</th>                          
                        <th>' . _('Season name') . '</th>
                        <th>' . _('Start Date') . '</th> 
                        <th>' . _('End Date') . '</th>
                        <th colspan=2></th> 
                   </tr>';      
      

        echo $TableHeader;  
        $j = 1;
        $k = 0;                     
    $ctr = 0;
    $m=1;    
    while ($myrow = DB_fetch_row($result)) {
        
 
                         if ($k==1){
                echo '<tr class="EvenTableRows">';
                $k=0;
            } else {
                echo '<tr class="OddTableRows">';
                $k++;
            }  $tabindex=$j+4;
        
        //$displaydate = ConvertSQLDate($myrow3[0]);
        $ctr++;

                $d=$myrow[5];
        printf("<td>%s</td>                 
                <td align='left'>%s</td>
                <td>%s</td>
                <td>%s</td> 
                <td><a href=\"%s&DemandID=%s&Season=$myrow[1]\">" . _('Edit') . "</td>
                <td><a href=\"%s&DemandID=%s&delete=yes\">" . _('Delete') ."</td>
                ",
                $m,
                $myrow[1],                 
                $myrow[3],  
                $myrow[4], 
                $_SERVER['PHP_SELF'] . '?' . SID,
                $myrow[2], 
                $_SERVER['PHP_SELF'] . '?' . SID,
                $myrow[2]);   
            

            $j++;
            $m++;        
    }                
               
             echo"</tr><tr bgcolor=#ccc><td> </td>
            
            <td><select name='Season'>";
            
 $sql7 = 'SELECT m_sub_season.season_sub_id,     
                m_sub_season.seasonname
  FROM m_sub_season';
$result7 = DB_query($sql7,$db);
while ($myrow7 = DB_fetch_array($result7)) {
    if (isset($_GET['Season']) and $myrow7['seasonname']==$_GET['Season']) {
         echo "<option selected value='" .$myrow7['season_sub_id'] . "'>" . $myrow7['seasonname'];
    } else {                                                                                                             
        echo "<option value='" . $myrow7['season_sub_id'] . "'>" . $myrow7['seasonname'];
    }
    
} //end while loop
echo '</select> ';
            echo"<input type='hidden' name='DemandID' value=$DemandID>";
            
            echo"<td><input type='Text' class=date alt=".$_SESSION['DefaultDateFormat']." name='startdate' size=12
            maxlength=12 value=" . $_POST['startdate'] . "></td>
            
            <td><input type='Text' class=date alt=".$_SESSION['DefaultDateFormat']." name='enddate' size=12
            maxlength=12 value=" . $_POST['enddate'] . "></td> ";
 
            echo"<td><input type='submit' name='submit' value='submit'></td>";    
     
             

    echo "</tr></table></td>";
    echo '</table>';
    echo '</form>';                                              
    unset ($StockID);

  echo"</td></tr></table>";      
echo "</div>"; 
} // End of function listall()

include('includes/footer.inc'); 
?>
