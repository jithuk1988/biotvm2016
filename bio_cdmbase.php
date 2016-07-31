<?php
   $PageSecurity = 40;
    include ('includes/session.inc');
$title = _('CDM baseline adding');
include ('includes/header.inc'); 
?>
<script type="text/javascript"> 

function viewcustomer(str4)
{
controlWindow=window.open("Customers.php?DebtorNo="+str4,"customer","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=1200,height=600");
} 
function model(str6)
{
controlWindow=window.open("modelchange.php?DebtorNo="+str6,"model","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=1200,height=600");
} 
function insdate(str5)
{
controlWindow=window.open("insdate.php?DebtorNo="+str5,"insdate","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=1200,height=600");
} 

function exc()
{
      controlWindow=window.open("cdmexcelsur.php","Excel","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=100,height=100");

}
function showdist(str)
{

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
     document.getElementById("district").innerHTML=xmlhttp.responseText; 
       
    }
  } 
xmlhttp.open("GET","bio_cdmdistrict.php?state=" + str,true);
xmlhttp.send();  
}
 function showblock(str){   
     str1=1;
     str2=document.getElementById('state').value;
     str3=document.getElementById('district').value;
     
    if(str==1 && (str3==1 || str3==3 || str3==4 || str3==5 || str3==7 || str3==9 || str3==10 || str3==10 || str3==14)){
         alert("No Corporation for this district");
         document.getElementById("block").innerHTML="";
         return;
     }
//alert(str1);   alert(str2);       alert(str3);
 if (str3=="")
  {           
   alert("Please select the district");   
  document.getElementById('district').focus(); 
  return;
  }

if (str=="")
  {
  document.getElementById("block").innerHTML="";
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
     document.getElementById("block").innerHTML=xmlhttp.responseText; 
       
    }
  } 
xmlhttp.open("GET","bio_CustlsgSelection.php?block=" + str +"&country1="+ str1 +"&state1="+ str2 +"&district="+ str3,true);
xmlhttp.send();
} 
</script>
<?php
 echo '<center><font style="color: #333;
    background:#fff;
    font-weight:bold;
    letter-spacing:0.10em;
    font-size:16px;
    font-family:Georgia;
    text-shadow: 1px 1px 1px #666;"><br>Customer list</br></font></center>';
     echo'<table width=98% ><tr><td>';                                            // $_SERVER['PHP_SELF'] 
    echo'<div >'; 
    
    echo "<form method='POST' name=form1 action='" . $_SERVER['PHP_SELF'] . "'>"; 
    
    

    echo "<a href='CDMlist.php'><b><span>VIEW MONITERED CUSTOMERS</span></b></a> ";

    if(isset($_POST['submit']))   
{
  if($_POST['fire'])
   {
       echo  $fire=1; 
   }
    if($_POST['lpg'])
    {
     echo   $lpg=1; 
    }
        if($_POST['grid'])
        {
        echo  $grid=1;  
        }
     for($i=0;$i<$_POST['totrow'];$i++)
           {
            // echo $_POST['sel'.$i];
           if($_POST['sel'.$i])
             {
    $sql_insert="INSERT INTO bio_cdmbase (`debtorno`, `firewood`, `lpg`, `grid`) VALUES ('".$_POST['sel'.$i]."','".$fire."','".$lpg."','".$grid."')";   
 //   INSERT INTO `bio_cdmbase`(`debtorno`, `firewood`, `lpg`, `grid`) VALUES ([value-1],[value-2],[value-3],[value-4])
            $res_insert=DB_query($sql_insert,$db);
             }
           }      
}
   
echo"<div id=grid>";
     
       echo"<fieldset style='width:90%;'>";
       echo"<legend><h3></h3></legend>";
            echo"<table style='width:100%;'><tr>";  
        
       
         echo"<td>Capacity From:</td><td><input type='text' name=capacity1  value='".$_POST['capacity1']."'></td> <td>To <input type='text' name=capacity2  value='".$_POST['capacity2']."'></td>"; 
          echo"<td>Model:</td><td><select name='Model'> ";
         $sqpt="Select distinct model from stockitemproperty ";
          $res_pt=DB_query($sqpt,$db);
            echo '<option value="-1">select</option>';
            while ($mpt=DB_fetch_array($res_pt))
          {
              echo "<option value=".$mpt[0].">".$mpt[0]."</option>";
          }
          echo"</select></td>";
         
         
         
         
         
         $sq="Select stateid,state from bio_state where cid=1";
          $res_st=DB_query($sq,$db);
         
         echo"<td>State:</td><td><select name='state' id='state' onchange='showdist(this.value)'> ";
        echo '<option value="-1">select</option>';
         while ($mr=DB_fetch_array($res_st))
          {
              echo "<option value=".$mr[0].">".$mr[1]."</option>";
          }
          echo"</select></tr></td>";
          if($_POST['state'] < 0)
          {
              $_POST['state'] = 14;//'".$_POST['state']."'
          }
           $sqdt="Select did,district from bio_district where cid=1 and stateid=14";
          $res_dt=DB_query($sqdt,$db);
          echo "<div id='dist' name='dist'> <td>District:</td><td><select name='district' id='district'>"; 
           echo '<option value="-1">select</option>';
            while ($md=DB_fetch_array($res_dt))
          {
              echo "<option value=".$md[0].">".$md[1]."</option>";
          }
          
          echo"</select></td></div>";
          
              echo '<td>LSG Type<select name="lsgType" id="lsgType" style="width:100px" onchange="showblock(this.value)">';
    echo '<option value=0></option>'; 
    echo '<option value=1>Corporation</option>';
    echo '<option value=2>Muncipality</option>';
    echo '<option value=3>Panchayat</option>';           
    echo '</select></td>'; 
       echo '<td align=left colspan=2>';
        echo'<div style="align:right" id=block>';             
        echo'</div>';
        echo'</td>';
      
        echo'<td><div id=showpanchayath></div></td>';
        echo"<td><input type='submit' name='view' id='submit' value=search></td></tr></table></div>";
        echo"<div id='close'>";
            echo"<fieldset style='width:90%;'>";
       echo"<legend><h3>SELECT BASE LINE </h3></legend>";
       echo "<table>";
       echo '<tr><td><b>FIREWOOD</b><input type="checkbox" name="fire"></td><td><b>LPG</b><input type="checkbox" name="lpg"></td><td><b>GRID</b><input type="checkbox" name="grid"></td></td>';
 
       echo "<tr><td></td></tr>";
       echo "</table>";
       echo "</fieldset>";

     
       echo "<div style='height:400px; overflow:auto;'>";
if($_SESSION[UserID]=='admin')
{
   echo"<a id='$debtor' onclick='exc()'>Export to Excel</a> ";     
}
echo "<table style='border:1 solid #000000;width:100%'>";
echo "<tr><th>Slno</th>";
echo "<th>Code</th>";
echo "<th>Customer name</th>";
echo "<th>Address</th>";
echo "<th>ST</th>";
echo "<th>District</th>";

echo "<th>Customer phone</th>";
echo "<th>Installed date</th>";
echo "<th>Model</th>";
echo "<th>Capacity</th>";

echo "<th>Plant status</th>";

  $sql0=" select debtorsmaster.name,concat(custbranch.braddress1,'</br>',custbranch.braddress2) as address,bio_district.district,bio_state.code,debtorsmaster.debtorno as 'debtorno',
concat(custbranch.phoneno,'</br>',custbranch.faxno) as mobile,
ifnull(date_format(bio_installation_status.installed_date,'%d-%m-%Y'),'NA') as insdate,stockitemproperty.model,stockitemproperty.capacity, bio_plantstatus.plantstatus ,orderplant.stkcode as 'stockid'
from salesorders 
left join bio_installation_status on salesorders.orderno=bio_installation_status.orderno
left join debtorsmaster on salesorders.debtorno=debtorsmaster.debtorno
left join custbranch on debtorsmaster.debtorno=custbranch.debtorno
left join bio_state on `custbranch`.`cid`=bio_state.cid and `custbranch`.`stateid` = `bio_state`.`stateid`
left join bio_district on bio_district.cid=custbranch.cid and bio_district.stateid=custbranch.stateid and bio_district.did=custbranch.did
left join bio_cdmbase on bio_cdmbase.debtorno=salesorders.debtorno
left join orderplant on salesorders.orderno=orderplant.orderno
left join stockitemproperty on orderplant.stkcode=stockitemproperty.stockid
left join bio_plantstatus on bio_installation_status.plant_status=bio_plantstatus.id where salesorders.debtorno not like '0000' "; 


$sql1="SELECT debtorsmaster.name,concat(custbranch.braddress1,'</br>',custbranch.braddress2) as address,`bio_district`.`district` ,bio_state.code,`bio_oldorders`.`debtorno` as 'debtorno' ,  concat( `custbranch`.`phoneno` , '</br>', `custbranch`.`faxno` ) AS 'mobile',ifnull(date_format( `bio_oldorders`.`installationdate` , '%d-%m-%Y' ),'NA') AS insdate ,stockitemproperty.model, stockitemproperty.capacity, bio_plantstatus.plantstatus,bio_oldorders.plantid as 'stockid'
FROM `bio_oldorders`
INNER JOIN `debtorsmaster` ON ( `bio_oldorders`.`debtorno` = `debtorsmaster`.`debtorno` )
INNER JOIN `custbranch` ON ( `debtorsmaster`.`debtorno` = `custbranch`.`debtorno` )
left join bio_state on `custbranch`.`cid`=bio_state.cid and `custbranch`.`stateid` = `bio_state`.`stateid`
LEFT JOIN `bio_district` ON ( `custbranch`.`did` = `bio_district`.`did` )
AND (
`bio_district`.`cid` = `custbranch`.`cid`
)
AND (
`custbranch`.`stateid` = `bio_district`.`stateid`
)
left JOIN stockitemproperty ON bio_oldorders.plantid = stockitemproperty.stockid
left join bio_cdmbase on bio_cdmbase.debtorno=`bio_oldorders`.`debtorno`
LEFT JOIN bio_plantstatus ON bio_oldorders.currentstatus = bio_plantstatus.id
where bio_oldorders.debtorno not like '00000' 
"; 
if($_POST['view'])
                  {
                  if($_POST['capacity1']!='') {
                    
                     if($_POST['capacity2']!='')
                     {
                         $sql0.=" AND stockitemproperty.capacity between ".$_POST['capacity1']." and ".$_POST['capacity2']."";
                         $sql1.=" AND stockitemproperty.capacity between ".$_POST['capacity1']." and ".$_POST['capacity2']."";
                     }
                     else
                     {
                     $sql0.=" AND stockitemproperty.capacity >= ".$_POST['capacity1'];
                     $sql1.=" AND stockitemproperty.capacity >= ".$_POST['capacity1'];
                     //$sql_selall.="AND bio_installation_status.orderno='890'";  
                     }
                     }
                  else

{                  
    if($_POST['capacity2']!=NULL) {
                    $sql0.=" AND stockitemproperty.capacity => ".$_POST['capacity2'].""; 
                    $sql1.=" AND stockitemproperty.capacity => ".$_POST['capacity2'].""; 
                     //$sql_selall.="AND bio_installation_status.orderno='890'";  
                  }
                  else
                  {
                      
                  }
}
if($_POST['Model']!=-1)
{
    $sql0.=" AND stockitemproperty.model='".$_POST['Model']."'"; 
                    $sql1.=" AND stockitemproperty.model = '".$_POST['Model']."'"; 
}

/*      if($_POST['District']!=0)   {
     $sql .=" AND bio_incident_cust.district=".$_POST['District'];
      
     if (isset($_POST['lsgType']))    {
     if($_POST['lsgType']!=0)   {
       if($_POST['lsgType']!=NULL)   {  
         
     $sql .=" AND bio_incident_cust.LSG_type=".$_POST['lsgType'];    
     
     if (isset($_POST['lsgName']))    {
     if($_POST['lsgName']==1 OR $_POST['lsgName']==2)   {
     $sql .=" AND bio_incident_cust.LSG_name=".$_POST['lsgName'];    }
    
       elseif($_POST['lsgName']==3){
       $sql .=" AND bio_incident_cust.LSG_name=".$_POST['lsgName'];    } 
              
       }
       
       if (isset($_POST['gramaPanchayath']))    {  
      if($_POST['gramaPanchayath']!=0 OR $_POST['gramaPanchayath']!=NULL)   {
     $sql .=" AND bio_incident_cust.block_name=".$_POST['gramaPanchayath'];    }       
     }
     }
     }
     }   
     }*/

                   if($_POST['district']!=-1) {
                    $sql0.=" AND bio_district.did = '".$_POST['district']."'";
                    $sql1.=" AND bio_district.did = '".$_POST['district']."'";  
                   
                     //$sql_selall.="AND bio_installation_status.orderno='890'"; 
                      if (isset($_POST['lsgType']))    {
     if($_POST['lsgType']!=0)   {
       if($_POST['lsgType']!=NULL)   {  
          $sql0 .=" AND custbranch.LSG_type=".$_POST['lsgType']; 
          $sql1 .=" AND custbranch.LSG_type=".$_POST['lsgType'];      
                if (isset($_POST['lsgName']))    {
     if($_POST['lsgName']==1 OR $_POST['lsgName']==2)   {      
          $sql0.=" AND custbranch.LSG_name=".$_POST['lsgName'];  
     $sql1.=" AND custbranch.LSG_name=".$_POST['lsgName'];  }        
      elseif($_POST['lsgName']==3){
       $sql0 .=" AND custbranch.LSG_name=".$_POST['lsgName'];  
        $sql1 .=" AND custbranch.LSG_name=".$_POST['lsgName'];  } 
         }
         if (isset($_POST['gramaPanchayath']))    {  
      if($_POST['gramaPanchayath']!=0 OR $_POST['gramaPanchayath']!=NULL)   {
     $sql0 .=" AND custbranch.block_name=".$_POST['gramaPanchayath']; 
         $sql1 .=" AND custbranch.block_name=".$_POST['gramaPanchayath']; }       
     } 
     }
     }
     }
     }      
                  
                  if($_POST['state']!=-1) {
                    $sql0.=" AND `bio_state`.`stateid` = '".$_POST['state']."'";
                    $sql1.=" AND `bio_state`.`stateid`= '".$_POST['state']."'";  
                     //$sql_selall.="AND bio_installation_status.orderno='890'";  
                  }
              }
            $sql0.=" AND salesorders.debtorno not in (SELECT `debtorno` FROM `bio_cdmbase`) order by left(salesorders.debtorno,1),length(salesorders.debtorno),salesorders.debtorno";
         
            $sql1.= "AND `bio_oldorders`.`debtorno` not in (SELECT `debtorno` FROM `bio_cdmbase`)  order by left(bio_oldorders.debtorno,1),length(bio_oldorders.debtorno),bio_oldorders.debtorno";
  $sql2="(".$sql0.") UNION (".$sql1.")";
 
$_SESSION['qry']=$sql2;
       $result=DB_query($sql2,$db);  
       $k=0;
$i=1;
   while($row=DB_fetch_array($result))
   {    $leadid=$row[5];
             if ($k==1)
             {
               echo '<tr class="EvenTableRows">';
                $k=0;
             }else 
              {
               echo '<tr class="OddTableRows">';
               $k=1;     
               }
              
       echo '<td>'.$i.'</td>';
         echo '<td><a id='.$row["debtorno"].' onclick="viewcustomer(this.id);return false;" href="">'.$row["debtorno"].'</a></td>';   
       echo '<td>'.$row["name"].'</td>'; 
          echo '<td>'.  $row["address"].'</td>';
                     echo '<td>'.  $row["code"].'</td>';
            echo '<td>'.  $row["district"].'</td>';
         
       echo '<td>'.$row["mobile"].'</td>';                                          
       echo '<td><a id='.$row["debtorno"].' onclick="insdate(this.id);return false;" href="">'.$row["insdate"].'</a></td>';
       if($row["model"]!="")
       {
          echo '<td><a id='.$row["debtorno"].' onclick="model(this.id);return false;" href="">'.$row["model"].'</a></td>';  
       }
       else
       {
          echo '<td><a id='.$row["debtorno"].' onclick="model(this.id);return false;" href="">NA</td>';  
       }
          
            echo '<td>'.$row["capacity"].'</td>'; 
      $sqls="Select debtorno from cdmlist where debtorno='".  $row["debtorno"]."'";
        $rt=DB_query($sqls,$db); 
        if(DB_num_rows($rt)>0)
        {
            echo '<td><b>CDM SELECTED</b></td>';
        }
        else
        {
       echo '<td>'.$row["plantstatus"].'</td>';
        }
          //    echo '<td>'.$row["plantstatus"].'</td>';
          if($_POST['view'])
    {
            echo'<td><input type="checkbox" name="sel'.$i.'" value='.$row['debtorno'].' checked> </td></tr>'; 
    }else
    {
    echo'<td><input type="checkbox" name="sel'.$i.'" value='.$row['debtorno'].'> </td></tr>';   
    }       
       echo'</tr>';                                            
       $i++;                             //             
   }             echo "<input type=hidden name='totrow' value=".$i.">";                                                     
   echo '</table>'; 
   echo '</table>';
   echo '<center><input type="submit" name="submit"></center>';   

                
?>
