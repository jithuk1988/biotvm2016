<?php
$PageSecurity = 80;  
include('includes/session.inc'); 
if($_GET['enggrid']){
    
    $enq=$_GET['enggrid'];
    echo "<thead>
                <tr BGCOLOR =#800000><th>" . _('Sl no:') . "</th>
                <th>" . _('Customer Name') . "</th>
                <th>" . _('Date') . "</th>
                <th>" . _('Enquiry Type') . "</th>
                <th>" . _('Output Type') . "</th>
</tr></thead>";
            //echo '<td></td>' ;
       //echo $count;
       $sql3="SELECT count(*) FROM bio_leads";
       $result3=DB_query($sql3,$db);
      $count=DB_fetch_row($result3);
      
 $sql="SELECT bio_leads.leadid,bio_leads.leaddate,bio_leads.enqtypeid,bio_leads.outputtypeid,bio_leads.teamid,bio_cust.custname FROM bio_leads,bio_cust WHERE 
 bio_leads.cust_id=bio_cust.cust_id
 AND bio_leads.enqtypeid=$enq";
      $result=DB_query($sql,$db);
     // $count=DB_fetch_row($result); 
     //print_r($count);
    echo '<tbody>';
    echo '<tr>';                                       
    $no=0; 
      if($count>0)
      {
          $k=0; 
          while($myrow=DB_fetch_array($result))
          {    //echo $myrow[0];
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
              $no++;
                
                 
                
                $sql1="SELECT enquirytype FROM bio_enquirytypes WHERE enqtypeid=".$myrow['enqtypeid'];
                $result1=DB_query($sql1, $db);
                $myrow1=DB_fetch_array($result1);                
                $sql2="SELECT outputtype FROM bio_outputtypes WHERE outputtypeid=".$myrow['outputtypeid'];
                $result2=DB_query($sql2, $db);
                $myrow2=DB_fetch_array($result2);                
                $sql3="SELECT teamname FROM bio_leadteams WHERE teamid=".$myrow['teamid'];
//                $result3=DB_query($sql3, $db);
                $myrow3=DB_fetch_array($result3);
//                $sql4="SELECT feedstocks FROM bio_feedstocks WHERE id=".$myrow['feedstockid'];
//                $result4=DB_query($sql4, $db);
//                $myrow4=DB_fetch_array($result4);
                
                 //echo  $myrow[0];
//                echo '<td>'.$no.'</td>';             
//                echo "<td>".$myrow['custname']."</td>";
//                echo '<td>'.$myrow['leaddate'].'</td>';
//                echo '<td>'.$myrow1['enquirytype'].'</td>'; 
//                echo '<td>'.$myrow2['outputtype'].'</td>';  
//                echo '<td>'.$myrow3['teamname'].'</td>';
//                echo "<td><a  style='cursor:pointer;' id=editleads>" . _('Edit') . "</a></td>";  
//                echo "<td><a href='onclick=delete('$myrow[0]')'>" . _('Delete') . "</a></td>";
//                echo '</tr>';
                  $leadid=$myrow[0]; 
          echo "<input type=hidden name=leadid value='$leadid'>";
          
printf("<td cellpading=2>%s</td>
        <td>%s</td>
        <td>%s</td>
        <td>%s</td>
        <td>%s</td>


         
        <td><a  style='cursor:pointer;'  onclick=showCD2('$leadid')>" . _('Edit') . "</a></td>  

        </tr>",
        
        $no,
        $myrow['custname'],
        $myrow['leaddate'],
        $myrow1['enquirytype'],
        $myrow2['outputtype'],

        $myrow3['teamname'], 
        $_SERVER['PHP_SELF'] . '?' . SID,
        $myrow[0]);
          }
          //$_SERVER['PHP_SELF'] . '?' . SID,$myrow[0]         <td><a href='%sNum=%s&Delete=1'>" . _('Delete') . '</td>
      }
      echo '</tbody>';
    
}  
if($_GET['service']==2){
    echo"<td>Services</td><td><select name='productservices' id='productservices'><option value='5'>Feasibility study</option></select></td>";
}


if($_GET['service']==1||$_GET['service']==3){
echo'<td>Product Services</td><td>';
  $sql1="SELECT * FROM bio_productservices";
  $result1=DB_query($sql1, $db);
  echo '<select name="productservices" id="productservices" style="width:190px">';
  $f=0;
  while($myrow1=DB_fetch_array($result1))
  {  
  if ($myrow1['id']==$_POST['productservices'])  
    { 
    echo '<option selected value="';
    } else 
    {
    if ($f==0) 
        {
        echo '<option>';
        }
        echo '<option value="';
    }
    echo $myrow1['id'] . '">'.$myrow1['productservices'];
    echo '</option>';
    $f++;
   } 
  echo '</select>'; 
  echo '</td>'; }



?>
