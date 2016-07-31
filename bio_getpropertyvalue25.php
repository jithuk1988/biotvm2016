<?php
$PageSecurity = 80;
include('includes/session.inc');

 $stypeid=$_GET['q'] ;

  //echo "hiiii".$stypeid;

$_SESSION['stypeid']=$stypeid;

  if(isset($_GET['q']))
    {
 echo "<fieldset style='width:90px;height:390px;'>";
echo "<legend><h3>Source Details</h3></legend>";
        echo "<table border=0 style='width:100%'>";
    $sql3="SELECT property,sourcepropertyid FROM bio_sourceproperty WHERE bio_sourceproperty.sourcetypeid=".$stypeid;
    $result3=DB_query($sql3,$db);    
    while($myrow3=DB_fetch_array($result3))
    {

      echo "<tr style='width:100px'>";
//        echo "<td>$myrow3[0]</td>";

//        echo "<td><input type=text name='propertyvalue'".$s."></td>";
//        echo "</tr>";

        printf("<td>%s</td>
                     <td><input  type='text' name='propertyvalue%s' style='width:165px'></td>                                               
                      ",
                $myrow3[0],
                $myrow3[1]);  

                echo "</tr>";
    }
    echo "</table>"; 
	echo "</fieldset>";
   } 
  
   //LEAD Team Details ......................LEAD Team Details............................LEAD Team Details..................LEAD Team Details
    
          echo "<div id=editmembers>" ;  
   if(isset($_GET['members']) || $_GET['members']!='' )
 {
      $members=$_GET['members'];
 $memcredit=$_GET['memcredit'];
     $sql="INSERT INTO bio_memberstemp (members,credit) VALUES('".$members."',".$memcredit.")";
     $result3=DB_query($sql,$db);               
  

     echo "<fielset>";
displaymembers($db);
     echo "</fielset>";
  }
           echo "</div>"; 
     
  if(isset($_GET['editmemberid'])) 
  {
      echo "hi"  ;
      $membersid=$_GET['editmemberid'];
          $sql1="SELECT * FROM bio_memberstemp WHERE temp_id=".$membersid;
    $result1=DB_query($sql1,$db);
    $myrow1=DB_fetch_array($result1);
        $member=$myrow1['members'];
    $memcredit=$myrow1['credit'];
    
    echo '<table class="selection">';
     echo "<input type=hidden name=updatememid id='updatememberid' value='$membersid'>";
echo '<tr><td>' . _('Add Members') . ':</td>';
    echo '<td>';
    echo "<input type='text' name='members' id='updatemembers' value='$member'>";
    echo '</td></tr>';

    echo '<tr><td>' . _('Credit percentage') . ':</td>';
    echo '<td>';
    echo "<input type='text' name='memcredit' id='updatememcredit' value=$memcredit>";
    echo '</td></tr>';
        echo '<tr><td></td><td colspan=2><input type=button name=addmem value="Update" onclick="showCD6()"></td></tr>';       
    echo "</table>";
  }
         
               
       echo "<div id=updatemembers>"; 
 
  if(isset($_GET['updatemembers1']) and isset($_GET['updatememberid1']))
  {
  $upmembers1=$_GET['updatemembers1'];
 $upmemcredit1=$_GET['updatememcredit1'];
 $upmemberid1=$_GET['updatememberid1'];
     $sql="UPDATE `bio_memberstemp` SET `members` = '".$upmembers1."',
            `credit` = ".$upmemcredit1." WHERE `bio_memberstemp`.`temp_id` =".$upmemberid1;
                $result = DB_query($sql,$db,$ErrMsg,$DbgMsg);
// prnMsg( _('The Sales Leads record has been Updated'),'success');
  //  $result1=DB_query($sql,$db);
     echo "<fielset>";
displaymembers($db);
     echo "</fielset>";
     unset($_POST['members']); 
     unset($_POST['memcredit']);
  }
         echo "</div>";  
function displaymembers($db)
   {
                echo "<div style='overflow:scroll;height:150px'>";
    $sql1="SELECT * FROM bio_memberstemp";
     $result1=DB_query($sql1,$db);
     echo "<table style='width:100%'>";
   echo "<thead>
                <tr BGCOLOR =#800000><th>" . _('Sl no:') . "</th>
                <th>" . _('Employee Name') . "</th>
                <th>" . _('Credit') . "</th>
                </tr></thead>";
                $k=0;
                $Sino=0;
//          $memid=$myrow[0]; 
          
     while($myrow1=DB_fetch_array($result1))
     {
                if ($k==1)
                {
                    echo '<tr class="EvenTableRows">';
                    $k=0;
                    
                }
                 else 
                 {
                    echo '<tr class="OddTableRows">';
                    $k=1;     
                 }
 $memid=$myrow1[0];
          echo "<input type=hidden name=memid id=memberid value=$memid>"; 
                 $Sino++;
 printf("<td cellpading=2>%s</td>
        <td>%s</td>
        <td>%s</td>         
        <td><a  style='cursor:pointer;'  onclick=showCD2('$memid')>" . _('Edit') . "</a></td>  
         <td><a href='%sSelectedMem=%s&deletemem=yes' onclick=\'return confirm('" .
                _("Are you sure you wish to delete this Lead Team Memeber?") . "');\'>" . _('Delete') . "</td>  
        
        </tr>",
        
        $Sino,
        $myrow1[1],
        $myrow1[2],
        $_SERVER['PHP_SELF'] . '?' . SID,
        $myrow1[0],    
        $_SERVER['PHP_SELF'] . '?' . SID,
        $myrow1[0]);
        
         
//        if(isset($SelectedMem))
     //   {
            //        if(isset($SelectedMem))
//        {
//            $sql1="DELETE FROM bio_memberstemp WHERE temp_id=".$SelectedMem;
//             $result1=DB_query($sql1,$db);
//        }
      //  }
      }


     
     echo "</table>";
     echo "</div>";
   }
   
   
   
      if($_GET['displaymembers']!='' and isset($_GET['displaymembers']))                            
   {   $displaymembers=$_GET['displaymembers'];
       echo "hii";
       $sql="SELECT bio_teammembers.teamid,bio_teammembers.empid,bio_teammembers.creditpercentage,bio_emp.empname,bio_leadteams.teamname
        FROM bio_teammembers,bio_emp,bio_leadteams 
       WHERE bio_teammembers.teamid=bio_leadteams.teamid AND bio_teammembers.teamid=".$displaymembers;
       $result=DB_query($sql,$db);
            echo "<table style='width:100%'>";
   echo "<thead>
                <tr BGCOLOR =#800000><th>" . _('Team Name') . "</th>
                <th>" . _('Employee Name') . "</th>
                <th>" . _('Credit') . "</th>
                </tr></thead>";
                $k=0;
                $Sino=0;

     while($myrow=DB_fetch_array($result))
     {
                if ($k==1)
                {
                    echo '<tr class="EvenTableRows">';
                    $k=0;
                    
                }
                 else 
                 {
                    echo '<tr class="OddTableRows">';
                    $k=1;     
                 }
 $memid=$myrow[1];
          echo "<input type=hidden name=memid id=memberid value=$memid>"; 
                 $Sino++;
 printf("<td cellpading=2>%s</td>
        <td>%s</td>
        <td>%s</td>         
        <td><a  style='cursor:pointer;'  onclick=showCD2('$memid')>" . _('Edit') . "</a></td>  
         <td><a href='%sSelectedMem=%s&deletemem=yes' onclick=\'return confirm('" .
                _("Are you sure you wish to delete this Lead Team Memeber?") . "');\'>" . _('Delete') . "</td>  
        
        </tr>",
        
        $Sino,
        $myrow[1],
        $myrow[2],
        $_SERVER['PHP_SELF'] . '?' . SID,
        $myrow[0],    
        $_SERVER['PHP_SELF'] . '?' . SID,
        $myrow[0]);
      }


     
     echo "</table>";
   }                               
?>       
