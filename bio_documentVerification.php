<?php
      $PageSecurity = 80;
include('includes/session.inc');
$title = _('Document Verification');  
include('includes/header.inc');

echo '<center><font style="color: #333;
    background:#fff;
    font-weight:bold;
    letter-spacing:0.10em;
    font-size:16px;
    font-family:Georgia;
    text-shadow: 1px 1px 1px #666;">Document Verification</font></center>';
    
    
    
         $empid=$_SESSION['empid'];   
  

$sql_emp1="SELECT * FROM bio_emp WHERE empid=".$empid;
     $result_emp1=DB_query($sql_emp1,$db);
     $myrow_emp1=DB_fetch_array($result_emp1);
     
       
     
 $employee_arr=array();   
     $sql_drop="DROP TABLE if exists `emptable`";
     $result_drop=DB_query($sql_drop,$db);
 
                      $sql_create="CREATE TABLE emptable (empid int)";
                      $result_create=DB_query($sql_create,$db);   
                       
                  function showemp($empid,$db,$y)         
                  {  
                      $sql3="SELECT empid FROM bio_emp WHERE reportto='".$empid."'";
                      $result3=DB_query($sql3,$db);
                      

                      
                      $employee_arr=array();
                      while($row3=DB_fetch_array($result3))
                      {
                      $empid=$row3['empid'];
                      $sql_insert="INSERT INTO emptable (empid) VALUES ($empid)";
                      $result_insert=DB_query($sql_insert,$db);
                      
                      $employee_arr[]=$empid;

                      showemp($empid,$db,$y);    
                                            
                      } 
                                      
                  } 
                      
                      $sql_insert1="INSERT INTO emptable (empid) VALUES ($empid)";
                      $result_insert2=DB_query($sql_insert1,$db);
     
     $sql2="SELECT empid,reportto FROM bio_emp WHERE reportto=$empid";
     $result2=DB_query($sql2,$db);   
  
     while($row2=DB_fetch_array($result2))
     { 

        $empid=$row2['empid'];
        $employee_arr[]=$empid;
        $y=$empid;
        showemp($empid,$db,$y);
         
     } 
       

     $sql_select="SELECT empid FROM emptable";
     $result_select=DB_query($sql_select,$db);
     
     while($row_select=DB_fetch_array($result_select))
     {
          $employee_arr[]=$row_select['empid'];
     }
     
     $employee_arr=join(",", $employee_arr);
   
   $team_arr=array();
   $sql6="SELECT teamid FROM bio_teammembers WHERE empid IN ($employee_arr)";
    $result6=DB_query($sql6,$db);
    while($row6=DB_fetch_array($result6))
    {
        $team_arr[]=$row6['teamid'];
    }
         
    $team_array=join(",", $team_arr);
    
  
    echo"<div id=ret_verify></div>";
                     

    
    
    echo"<fieldset style='width:70%;'>";
    echo"<legend><h3>Documents Received</h3></legend>";
    
    echo "<table class='selection' style='width:90%;'>";
    echo"<tr><th>Order No</td><th>Order Date</th><th>Customer Name</td><th>Customer Type</td><th>Team</th><th>Documents Received</th><th>Verify All Documents</th></tr>";   
    
           if($_SESSION['UserID']=='bdmtvm1')
           {
    
        $sql_grid="SELECT DISTINCT salesorders.orderno,
                              salesorders.orddate,
                              bio_cust.custname,
                              bio_enquirytypes.enquirytype,
                              bio_leadteams.teamname                
                        
              FROM   bio_documentlist,salesorders,bio_cust,bio_leads,bio_enquirytypes,bio_leadtask,bio_leadteams
              WHERE  salesorders.orderno=bio_documentlist.orderno
              AND    salesorders.leadid=bio_leads.leadid             
              AND    bio_cust.cust_id=bio_leads.cust_id
              AND    bio_enquirytypes.enqtypeid=bio_leads.enqtypeid
              AND    bio_leadtask.teamid=bio_leadteams.teamid
              AND    bio_leadtask.leadid=bio_leads.leadid
              AND    bio_documentlist.status=1
              AND    bio_documentlist.receivedBy='ccetvm1' 
              ORDER BY bio_documentlist.receivedDate DESC";
           }
                    else  if($_SESSION['UserID']=='bdmeklm1')
           {
    
        $sql_grid="SELECT DISTINCT salesorders.orderno,
                              salesorders.orddate,
                              bio_cust.custname,
                              bio_enquirytypes.enquirytype,
                              bio_leadteams.teamname                
                        
              FROM   bio_documentlist,salesorders,bio_cust,bio_leads,bio_enquirytypes,bio_leadtask,bio_leadteams
              WHERE  salesorders.orderno=bio_documentlist.orderno
              AND    salesorders.leadid=bio_leads.leadid             
              AND    bio_cust.cust_id=bio_leads.cust_id
              AND    bio_enquirytypes.enqtypeid=bio_leads.enqtypeid
              AND    bio_leadtask.teamid=bio_leadteams.teamid
              AND    bio_leadtask.leadid=bio_leads.leadid
              AND    bio_documentlist.status=1
              AND    bio_documentlist.receivedBy='cceeklm1' 
              ORDER BY bio_documentlist.receivedDate DESC";
           }
               else  if($_SESSION['UserID']=='bdmkoz1')
           {
    
        $sql_grid="SELECT DISTINCT salesorders.orderno,
                              salesorders.orddate,
                              bio_cust.custname,
                              bio_enquirytypes.enquirytype,
                              bio_leadteams.teamname                
                        
              FROM   bio_documentlist,salesorders,bio_cust,bio_leads,bio_enquirytypes,bio_leadtask,bio_leadteams
              WHERE  salesorders.orderno=bio_documentlist.orderno
              AND    salesorders.leadid=bio_leads.leadid             
              AND    bio_cust.cust_id=bio_leads.cust_id
              AND    bio_enquirytypes.enqtypeid=bio_leads.enqtypeid
              AND    bio_leadtask.teamid=bio_leadteams.teamid
              AND    bio_leadtask.leadid=bio_leads.leadid
              AND    bio_documentlist.status=1
              AND    bio_documentlist.receivedBy='ccekoz1' 
              ORDER BY bio_documentlist.receivedDate DESC";
           }
           
        $result_grid=DB_query($sql_grid,$db);
        
        $i=0;
        while($row_grid=DB_fetch_array($result_grid)) 
        {
            $i++;
            $orderno=$row_grid['orderno'];
            
            $sql1="SELECT COUNT(*) FROM bio_documentlist WHERE orderno='".$row_grid['orderno']."' AND status=1";
            $result1=DB_query($sql1,$db);
            $row1=DB_fetch_array($result1);
            
            
            
                  if ($k==1)
                  {
                    echo '<tr class="EvenTableRows">';
                    $k=0;
                  }else 
                  {
                    echo '<tr class="OddTableRows">';
                    $k=1;     
                  }

            
                                echo"<td>".$row_grid['orderno']."</td> 
                                     <td>".$row_grid['orddate']."</td>  
                                     <td>".$row_grid['custname']."</td>   
                                     <td>".$row_grid['enquirytype']."</td> 
                                     <td>".$row_grid['teamname']."</td> 
                                     <td>".$row1[0]."</td> 
                                     <td><input type=checkbox id='$orderno' name='verify".$i."' onclick='verify(this.id);'>Verify All
                                     <td width='50px'><a style='cursor:pointer;' id='$orderno' onclick='selectdocs(this.id);'>" . _('Select ') . "</a></td>  
                                </tr>";
                                

        }  
        
        
                           
        echo"</table>";    
        
        echo"<br />";
        
        echo'<div class="centre">';
        echo"<input type=button name=submit value=Print onclick=print();>";  
        echo'</div>';
        
        echo"</fieldset>";   

    
    
?>


<script type="text/javascript">  


function print()
{

location.href="bio_communicationMemo.php";   

}

function selectdocs(str)
{
    //alert(str);
controlWindow=window.open("bio_docVerify.php?orderno=" + str ,"docVerify","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,width=900,height=500");
//location.href="bio_docVerify.php?orderno=" + str;   

}


             
function verify(str){                
           //  alert(str);
if (str!="")
{
    var con=confirm("Do you want to verify all received documents?");            
     if(con==true)
     {
         p=1;
     }
     else
     {
         p=0;
     }
  
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
    document.getElementById("ret_verify").innerHTML=xmlhttp.responseText;
    window.location="bio_documentVerification.php"; 
    }
  } 

xmlhttp.open("GET","bio_docCustSelection.php?verify=" + str +"&p=" +p,true);
xmlhttp.send(); 

   
}  

</script>
