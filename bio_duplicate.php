<?php

  $PageSecurity = 80;
include('includes/session.inc');



if($_GET['id']!="")                              
{
        
$name=$_GET['name'];
$phone=$_GET['phone']; 
$mob=$_GET['mob']; 
$flag=$_GET['flag'];
        
              $sql_dup="SELECT debtorsmaster.debtorno,
                               debtorsmaster.name,
                               salesorders.orderno,
                               salesorders.orddate AS date, 
                               salesorders.leadid,
                               custbranch.phoneno,
                               custbranch.faxno,
                               bio_district.district                             
                        FROM   debtorsmaster,salesorders,custbranch,bio_district 
                        WHERE  salesorders.debtorno=debtorsmaster.debtorno                           
                        AND    custbranch.branchcode=debtorsmaster.debtorno
                        AND    bio_district.did=debtorsmaster.did
                        AND    bio_district.stateid=debtorsmaster.stateid
                        AND    bio_district.cid=debtorsmaster.cid";   
      if($name!=""){
           $sql_dup .=" AND    debtorsmaster.name LIKE '$name%'";
      }     
      if($phone!=""){
           $sql_dup .=" AND    custbranch.phoneno LIKE '$phone%'";
      }       
      if($mob!=""){
           $sql_dup .=" AND    custbranch.faxno LIKE '$mob%'";
      }                                                                      
              $result_dup=DB_query($sql_dup,$db);
              $count_dup=DB_num_rows($result_dup);
              $choice=1;

        
      if($count_dup<=0)
      {
              
              $sql_dup="SELECT debtorsmaster.debtorno,
                               debtorsmaster.name,
                               custbranch.phoneno,
                               custbranch.faxno,
                               debtorsmaster.clientsince AS date,
                               bio_district.district
                         FROM  debtorsmaster,custbranch,bio_district 
                         WHERE custbranch.branchcode=debtorsmaster.debtorno
                         AND   bio_district.did=debtorsmaster.did
                         AND   bio_district.stateid=debtorsmaster.stateid
                         AND   bio_district.cid=debtorsmaster.cid ";
      if($name!=""){
            $sql_dup .=" AND    debtorsmaster.name LIKE '$name%'";
      }     
      if($phone!=""){
            $sql_dup .=" AND    custbranch.phoneno LIKE '$phone%'";
      }       
      if($mob!=""){
            $sql_dup .=" AND    custbranch.faxno LIKE '$mob%'";
      }                    
//      echo$sql_dup;                                    
              $result_dup=DB_query($sql_dup,$db);
              $count_dup=DB_num_rows($result_dup); 
              $choice=2; 
      }
      
      if($count_dup<=0)
      {   
               
              $sql_dup="SELECT bio_cust.cust_id,
                               bio_cust.custname AS name,
                               bio_cust.custphone AS phoneno,
                               bio_cust.custmob AS faxno,
                               bio_leads.leaddate AS date,
                               bio_leads.leadid,
                               bio_leads.enqtypeid,
                               bio_district.district 
                         FROM  bio_cust,bio_leads,bio_district 
                         WHERE bio_cust.cust_id=bio_leads.cust_id
                         AND   bio_district.did=bio_cust.district
                         AND   bio_district.stateid=bio_cust.state
                         AND   bio_district.cid=bio_cust.nationality"; 
      if($name!=""){
            $sql_dup .=" AND   bio_cust.custname LIKE '$name%'";
      }     
      if($phone!=""){
            $sql_dup .=" AND   bio_cust.custphone LIKE '$phone%'";
      }       
      if($mob!=""){
            $sql_dup .=" AND   bio_cust.custmob LIKE '$mob%'";
      }                   
                         
              $result_dup=DB_query($sql_dup,$db);
              $count_dup=DB_num_rows($result_dup);
              $choice=3;     
      }

      
        
        if($count_dup!=0)
        {  
                  echo "<fieldset style='float:center;width:97%;'>";  
                  echo "<legend><h3>Dupicate names</h3>";
                  echo "</legend>";
                  echo "<div style='height:80px; overflow:scroll;'>";
                  echo "<table style='width:90%;'><tr><td>";
                  
                   $k=0;
                   while($row_dup=DB_fetch_array($result_dup))
                   {
                                  $debtorno=$row_dup['debtorno'];
                                  $leadid=$row_dup['leadid'];  
                                  $custid=$row_dup['cust_id'];
                                  $enq=$row_dup['enqtypeid'];
                                  
                                  
                if ($choice==1)
                {
                    echo '<tr bgcolor="orange">';        
                }
                elseif ($choice==2)
                {
                    echo '<tr bgcolor="lightgrey">';  
                }  
                elseif ($choice==3)
                {
                    echo '<tr bgcolor="lightgreen">';  
                }
                                     
//                                printf("<td cellpading=2 width='150px'>%s</td>
//                                        <td width='100px'>%s</td>
//                                        <td width='170px'>%s</td>
//                                        <td width='160px'>%s</td>
//                                        <td width='50px'><a  style='cursor:pointer;'  onclick=oldorder('$choice','$custid','$debtorno')>" . _('Select') . "</a></td>  
//                                        </tr>",
//                                        $row_dup['name'],
//                                        $row_dup['phoneno']."<br />".$row_dup['faxno'],
//                                        $row_dup['district'],
//                                        $row_dup['date'],
//                                        $_SERVER['PHP_SELF'] . '?' . SID,$myrow[0]);

                       echo"<td cellpading=2 width='150px'>".$row_dup['name']."</td>";
                       echo"<td width='100px'>".$row_dup['phoneno']."<br />".$row_dup['faxno']."</td>";
                       echo"<td width='170px'>".$row_dup['district']."</td>"; 
                       echo"<td width='160px'>".$row_dup['date']."</td>";
                       if($flag==1){
                       echo"<td width='50px'><a  style='cursor:pointer;'  onclick=duplicate('$leadid','$enq')>" . _('Select') . "</a></td>"; 
                       }else{
                       echo"<td width='50px'><a  style='cursor:pointer;'  onclick=oldorder('$choice','$custid','$debtorno')>" . _('Select') . "</a></td>";    
                       }
                   }                                                                
                   
                   echo"</table></div></fieldset>"; 
        }
        else
        {
                 echo "0";       
        }
                   
                   
          
      } 
        

        
        
        
        
        
        
        
        
        

if($_GET['year']!="")
{
    echo$date='01/01/'.$_GET['year'];
    
}

?>
