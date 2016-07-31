<?php
$PageSecurity = 80;
include('includes/session.inc');
$pagetype=3;


$title = _('Select Customer');  
include('includes/header.inc');

echo '<center><font style="color: #333;
    background:#fff;
    font-weight:bold;
    letter-spacing:0.10em;
    font-size:16px;
    font-family:Georgia;
    text-shadow: 1px 1px 1px #666;">Select Customer</font></center>';


    echo '<p class="page_title_text"><img src="'.$rootpath.'/css/'.$theme.'/images/magnifier.png" title="' . _('Search') . '" alt="" />' . _('Search for the Customer') . '</p>';
    
    echo '<form name="SelectCustomer" method="post" action="' . $_SERVER['PHP_SELF'] . '?' .SID .'">
        <input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';
      
    echo '<table cellpadding="3" colspan="4" class="selection">
            <tr>
            <td><h5>' . _('Customer Name') . ':</h5></td>
            <td><input tabindex=1 type="Text" name="CustKeywords" size=20    maxlength=25></td>
            <td><h2><b>' . _('OR') . '</b></h2></td>
            <td><h5>' . _('Customer Mobile Number') . ':</h5></td>
            <td><input tabindex=3 type="text" name="CustPhone" size=15 maxlength=18></td>
            </tr>
        </table>
      
        <br /><div class="centre"><input tabindex=4 type="submit" name="SearchCust" value="' . _('Search Now') . '" />
    <input tabindex=5 type="submit" action="reset" value="' .  _('Reset') . '"></div>';    

    echo '</form>';
    
    if (isset($_POST['SearchCust'])) {
    
     $name=$_POST['CustKeywords'];
     $phone=$_POST['CustPhone'];
     
$sql_search="SELECT bio_cust.cust_id,
                    bio_cust.custname,
                    bio_cust.custmob,
                    bio_cust.custmob 
                FROM bio_cust,bio_advance,bio_leads 
                WHERE bio_cust.debtorno=''
                AND bio_leads.cust_id=bio_cust.cust_id
                AND bio_advance.leadid=bio_leads.leadid
                AND bio_advance.amount>0";
     
    if (isset($_POST['CustKeywords']))  {        
    if ($_POST['CustKeywords']!='') {  
    $sql_search .=" AND custname like '%$name%'";   
    }
    else
    {
            $sql_search .=" AND custname like '%%'";   
    }
}          
    if (isset($_POST['CustPhone']))  {        
    if ($_POST['CustPhone']!='')  { 
    $sql_search .=" AND custmob like '%$phone%'";   
    }  
    else
    {
           $sql_search .=" AND custmob like '%%'";        
    }   
}    

     
     echo"<table style='width:100%'> "; 
     echo"<br>";
     echo"<tr><th>Slno</th><th>Name</th><th>Mobile</th><th>House/Org</th><th>District</th><th>Enq Date</th><th>Cust type</th></tr>";
     
                                                                                                                                       
     $result_search=DB_query($sql_search,$db);  
     
     $no=1;      
     while($row_search=DB_fetch_array($result_search))
     {  
      $sql="SELECT bio_cust.cust_id,
                   bio_district.district,
                   bio_leads.leaddate,
                   bio_enquirytypes.enquirytype,
                   bio_leads.enqtypeid
              FROM bio_cust,bio_district,bio_leads,bio_enquirytypes 
             WHERE bio_cust.cust_id=bio_leads.cust_id 
               AND bio_leads.enqtypeid=bio_enquirytypes.enqtypeid
               AND bio_district.did=bio_cust.district
               AND bio_district.stateid=bio_cust.state
               AND bio_district.cid=bio_cust.nationality 
               AND bio_cust.cust_id=".$row_search['cust_id']."      
                    "; 
      $result=DB_query($sql,$db);
      $row=DB_fetch_array($result);
      $custid=$row['cust_id']; 
      
      $enqtype=$row['enqtypeid']; 
                                                                                                                                     
            if ($k == 1) {
            echo '<tr class="EvenTableRows">';
            $k = 0;
            } else {
            echo '<tr class="OddTableRows">';
            $k++;
            } 
            
         echo"<td>$no</td>
              <td>".$row_search['custname']."</td>
              <td>".$row_search['custmob']."</td> 
              <td>".$row_search['housename']."</td>
              <td>".$row['district']."</td>
              <td>".$row['leaddate']."</td>
              <td>".$row['enquirytype']."</td>
              <td><a  style='cursor:pointer;' onclick=selectForOrder('$custid','$enqtype')>" . _('Select') . "</a></td>
              ";   
         echo"</tr>";
         
     $no++;         
     }
     echo"</table>";                                                    
}




?>
<script type="text/javascript">


function selectForOrder(str1,str2)
{
//   alert(str1);
   window.location="LeadsCustomers.php?custid=" + str1 + "&enquiryid=" + str2;      
   
}


</script>