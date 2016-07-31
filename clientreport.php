<?php
   $PageSecurity = 80;
    include ('includes/session.inc');
$title = _('Customer Report');
include ('includes/header.inc'); 
?>
<script type="text/javascript"> 
function viewcustomer(str4)
{
controlWindow=window.open("Customers.php?DebtorNo="+str4,"customer","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=1200,height=600");
} 
 function baseline(str9)
{
controlWindow=window.open("bio_select1.php?DebtorNo="+str9,"baseline","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=1200,height=600");
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
      controlWindow=window.open("excelr.php","Excel","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=100,height=100");

}//cdmexcelsur
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


 function showgramapanchayath(str){   
   str1=1;
     str2=document.getElementById('state').value;
     str3=document.getElementById('district').value;
     // alert(str2);  
 if (str=="")
  {  
      alert("Please select the district");   
  document.getElementById('district').focus(); 
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
     document.getElementById("showpanchayath").innerHTML=xmlhttp.responseText; 
       
    }
  } 
xmlhttp.open("GET","bio_Custlsg_Selection.php?grama=" + str + "&country1="+ str1 +"&state2="+ str2 +"&district="+ str3,true);
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

  function showblocks(sttr)
        {
            
        if (sttr=="")
  {
  
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
     var blk=xmlhttp.responseText; 
        document.getElementById("lsgName").value=blk;
    }
  } 
xmlhttp.open("GET","bio_Custblock.php?grama=" + sttr,true);
xmlhttp.send(); 
}      
function panel()
{
      document.getElementById('bas').style.visibility="hidden";  
}
function showpan()
{
     document.getElementById('bas').style.visibility="visible";   
}
</script>
<?php
                                                   // $_SERVER['PHP_SELF'] 
    echo'<div >'; 
    
    echo "<form method='POST' name=form1 action='" . $_SERVER['PHP_SELF'] . "'>"; 


   
   
echo"<div id=grid>";
       echo"<fieldset style='width:90%;'>";
       echo"<legend><h3></h3></legend>";
        echo"<table style='width:100%;'><tr>";  
        
       
      
          echo"<td>Year:</td><td><select name='oryear'> "; 
               echo '<option value="-1">-SELECT-</option>';
                       echo '<option value="2014">2014</option>';
          
              echo "<option value='2013'>2013</option>";      
                 echo '<option value="2012">2012</option>';
          
              echo "<option value='2011'>2011</option>";
                 echo '<option value="2010">2010</option>';
          
              echo "<option value='2009'>2009</option>";
                  echo '<option value="2008">2008</option>';
          
              echo "<option value='2007'>2007</option>";
      /*   $sqpt='SELECT DISTINCT YEAR(leaddate) FROM bio_leads where YEAR(leaddate)!=0 ORDER BY YEAR(leaddate)  DESC   ';
          $res_pt=DB_query($sqpt,$db);
        
            while ($mpt=DB_fetch_array($res_pt))
          {
              if($_POST['oryear']==$mpt[0])
              {
                 echo "<option selected value=".$mpt[0].">".$mpt[0]."</option>"; 
              }
              else
              echo "<option value=".$mpt[0].">".$mpt[0]."</option>";
          }    */
          echo"</select></td>";
          
          echo "<td>Type: <select name='atype'>";
            echo '<option value="1">ALL</option>';
          
              echo "<option value='0'>Archieved</option>";
         
          echo"</select></td>";
         
                    echo"<td>Capacity From:</td><td><input type='text' name=capacity1  value='".$_POST['capacity1']."'></td> <td>To <input type='text' name=capacity2  value='".$_POST['capacity2']."'></td>"; 
         
         
         
        echo"<td><input type='submit' name='view' id='submit' value=search></td></tr></table></div>";
        echo"<div id='close'>";
                    if(isset($_POST['view']))   
        {
        $sql0=" select debtorsmaster.name,debtorsmaster.debtorno,concat(debtorsmaster.address1,'</br>',debtorsmaster.address2) as address,
  bio_district.district as district,bio_state.code,debtorsmaster.debtorno as 'debtorno',
concat(custbranch.phoneno,'</br>',custbranch.faxno) as mobile,
ifnull(date_format(bio_installation_status.installed_date,'%d-%m-%Y'),'NA') as insdate,LEFT(stockitemproperty.model,1) as model,stockitemproperty.capacity,
 bio_plantstatus.code as 'plantstatus' ,orderplant.stkcode as 'stockid',
 bio_corporation.corporation,
bio_municipality.municipality,
debtorsmaster.block_name ,
debtorsmaster.careof,
bio_panchayat.name as pname,
debtorsmaster.LSG_type,
bio_block.block

 
from salesorders 
left join bio_installation_status on salesorders.orderno=bio_installation_status.orderno
left join debtorsmaster on salesorders.debtorno=debtorsmaster.debtorno
left join custbranch on debtorsmaster.debtorno=custbranch.debtorno
left join bio_state on `debtorsmaster`.`cid`=bio_state.cid and `debtorsmaster`.`stateid` = `bio_state`.`stateid`
left join bio_district on bio_district.cid=debtorsmaster.cid and bio_district.stateid=debtorsmaster.stateid and bio_district.did=debtorsmaster.did

left join orderplant on salesorders.orderno=orderplant.orderno
left join bio_corporation on bio_corporation.country=custbranch.cid AND bio_corporation.state=debtorsmaster.stateid
AND bio_corporation.district=custbranch.did
left join bio_municipality on bio_municipality.id = debtorsmaster.LSG_name AND  bio_municipality.country=debtorsmaster.cid AND bio_municipality.state=debtorsmaster.stateid
AND bio_municipality.district=debtorsmaster.did
left join bio_panchayat on  bio_panchayat.id = debtorsmaster.block_name 
AND bio_panchayat.block =debtorsmaster.LSG_name AND   bio_panchayat.country=debtorsmaster.cid AND bio_panchayat.state=debtorsmaster.stateid
AND bio_panchayat.district=debtorsmaster.did
left join bio_block on bio_block.id=bio_panchayat.block and bio_panchayat.id = debtorsmaster.block_name 
AND bio_panchayat.block =debtorsmaster.LSG_name AND   bio_panchayat.country=debtorsmaster.cid AND bio_panchayat.state=debtorsmaster.stateid
AND bio_panchayat.district=debtorsmaster.did left join stockitemproperty on orderplant.stkcode=stockitemproperty.stockid
left join bio_plantstatus on bio_installation_status.plant_status=bio_plantstatus.id 

where salesorders.debtorno not like '0000' "; 





$sql1="SELECT debtorsmaster.name,debtorsmaster.debtorno,concat(debtorsmaster.address1,'</br>',debtorsmaster.address2) as address,
`bio_district`.district as district ,bio_state.code,`bio_oldorders`.`debtorno` as 'debtorno' ,
  concat( `custbranch`.`phoneno` , '</br>', `custbranch`.`faxno` ) AS 'mobile',
  ifnull(date_format( `bio_oldorders`.`installationdate` , '%d-%m-%Y' ),'NA') AS insdate ,
  LEFT(stockitemproperty.model,1) as model, stockitemproperty.capacity, bio_plantstatus.code as 'plantstatus',bio_oldorders.plantid as 'stockid',
 bio_corporation.corporation,
bio_municipality.municipality,
custbranch.block_name ,


debtorsmaster.careof,
bio_panchayat.name as pname,
custbranch.LSG_type,
bio_block.block
FROM `bio_oldorders`
INNER JOIN `debtorsmaster` ON ( `bio_oldorders`.`debtorno` = `debtorsmaster`.`debtorno` )
INNER JOIN `custbranch` ON ( `debtorsmaster`.`debtorno` = `custbranch`.`debtorno` )
left join bio_state on `debtorsmaster`.`cid`=bio_state.cid and `debtorsmaster`.`stateid` = `bio_state`.`stateid`
LEFT JOIN `bio_district` ON ( `debtorsmaster`.`did` = `bio_district`.`did` )
AND (
`bio_district`.`cid` = `debtorsmaster`.`cid`
)
AND (
`debtorsmaster`.`stateid` = `bio_district`.`stateid`
)
left JOIN stockitemproperty ON bio_oldorders.plantid = stockitemproperty.stockid
left join bio_corporation on bio_corporation.country=debtorsmaster.cid AND bio_corporation.state=debtorsmaster.stateid
AND bio_corporation.district=custbranch.did
left join bio_municipality on bio_municipality.id = debtorsmaster.LSG_name AND  bio_municipality.country=debtorsmaster.cid AND bio_municipality.state=debtorsmaster.stateid
AND bio_municipality.district=debtorsmaster.did
left join bio_panchayat on  bio_panchayat.id = debtorsmaster.block_name 
AND bio_panchayat.block =debtorsmaster.LSG_name AND   bio_panchayat.country=debtorsmaster.cid AND bio_panchayat.state=debtorsmaster.stateid
AND bio_panchayat.district=debtorsmaster.did
left join bio_block on bio_block.id=bio_panchayat.block and bio_panchayat.id = debtorsmaster.block_name 
AND bio_panchayat.block =debtorsmaster.LSG_name AND   bio_panchayat.country=debtorsmaster.cid AND bio_panchayat.state=debtorsmaster.stateid
AND bio_panchayat.district=debtorsmaster.did
LEFT JOIN bio_plantstatus ON bio_oldorders.currentstatus = bio_plantstatus.id

where bio_oldorders.debtorno not like '00000' "; 
   

if(($_POST['view']) || ($_POST['go']) || ($_POST['previous']) || ($_POST['next']) ) 
              {
                       
    if($_POST['oryear']!=-1) {
	 $endyear=$_POST['oryear']+1;
	 $start=$_POST['oryear']."-04-01";
	 $end=$endyear."-03-31";
      echo "<center><h2>"."Customer List From <b>".$start."</b> To <b>".$end."</b></h3></center>";    
                    $sql1.="  AND `bio_oldorders`.`installationdate` between '".$start."' and '".$end."'";
                    $sql0.="  AND bio_installation_status.installed_date between '".$start."' and '".$end."'";  
                     //$sql_selall.="AND bio_installation_status.orderno='890'";  
                  }
                  if($_POST['atype']==0) {
                        $sql1.=" AND bio_oldorders.debtorno in (select debtorno from bio_fileno)";
                         $sql0.=" AND salesorders.debtorno in (select debtorno from bio_fileno)";
                 
                  }
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
                  else{                  
    if($_POST['capacity2']!=NULL) {
                    $sql0.=" AND stockitemproperty.capacity => ".$_POST['capacity2'].""; 
                    $sql1.=" AND stockitemproperty.capacity => ".$_POST['capacity2'].""; 
                     //$sql_selall.="AND bio_installation_status.orderno='890'";  
                  }
               
}

   /*  if($_POST['cdm']==2) 
     {
        $sql0.=" AND salesorders.debtorno not in (SELECT `debtorno` FROM `bio_cdmbase`)";
         
       $sql1.= " AND `bio_oldorders`.`debtorno` not in (SELECT `debtorno` FROM `bio_cdmbase`)";
     } */    
                  
                 
              }
              $sql0.=" order by left(salesorders.debtorno,1),length(salesorders.debtorno),salesorders.debtorno";
       
         $sql1.= " order by left(bio_oldorders.debtorno,1),length(bio_oldorders.debtorno),bio_oldorders.debtorno";
  $sql2="(".$sql0.") UNION (".$sql1.")";
 
$_SESSION['qry']=$sql2;
   $result_full=DB_query($sql2,$db); 
   
   
    $ListCount = DB_num_rows($result_full);
         if(($_POST['cdm']==2) AND $ListCount>100)
         {
            if($ListCount>100)
            {
                $numb=$ListCount/100;
                $mode=$ListCount%100;
                if($mode>0)
                {
                    $numb=$numb+1;
                }
            } 
   if($_SESSION['page']>$numb) 
   {
       unset ($_SESSION['page']);
   }        
    if($_POST['view'])
{
    $_SESSION['page']=$_POST['pagenum'];
}           
   if($_POST['go'])
{
    $_SESSION['page']=$_POST['pagenum'];
}
if($_POST['previous'])
{
    if($_POST['pagenum']>1)
    {
      $_SESSION['page']=$_POST['pagenum']-1;    
    }

}
if($_POST['next'])
{
 if($_SESSION['page']<$numb) 
 {
       $_SESSION['page']=$_POST['pagenum']+1;     
 }  
}               
             
          echo '<select name="pagenum">';   
             $a=1;
              while($a<=$numb)
              {
                 if($_SESSION['page']==$a)
                  {
                       echo '<option selected value='.$a.'>'.$a.'</option>';   
                  }else
                  {
                              echo '<option value='.$a.'>'.$a.'</option>';   
                
                 
                  }
             
            
                  $a++;
              }    
               echo '</select>';
              // echo $numb;
             // echo $_SESSION['page'];
/*                echo '<input type="submit" name="go" value="Go">';
                echo '<input type="submit" name="previous" value="previous">';
              echo '<input type="submit" name="next" value="next">' ;
                                                                          */
   
                                               }
   
        }
         echo "<div style='height:400px; overflow:auto;'>";  
if($_SESSION[UserID]=='admin')
{
   echo"<a id='$debtor' onclick='exc()'>Export to Excel</a> ";     
}
echo "<table style='border:1 solid #000000;width:100%'>";
echo "<tr><th>Slno</th>";
echo "<th>Code</th>";
echo "<th>Name</th>";
echo "<th>Address</th>";
echo "<th>LSG</th>";
echo "<th>Block</th>";
echo "<th>District</th>";
echo "<th>Contact No</th>";
echo "<th>Installed date</th>";
echo "<th>Plant Size</th>";
echo "<th>Model</th>";
echo "<th>STS</th>";  
       $k=0;
$i=1;
if(($_POST['cdm']==2) && $ListCount>100)
{
    
$pagestart=($_SESSION['page']-1)*100;  
DB_data_seek($result_full,$pagestart);
$pagetotal=100;

}  else
{
 $pagetotal=$ListCount;
}
   while(($row=DB_fetch_array($result_full)) AND ($i<=$pagetotal) ) 
   {    $leadid=$row[5];
         $ba=0;
             if ($k==1)
             {
               echo '<tr class="EvenTableRows">';
                $k=0;
             }else 
              {
               echo '<tr class="OddTableRows">';
               $k=1;     
               }
                    if($row['LSG_type']==1){
         $LSG_name=$row['corporation']."(C)";
     }elseif($row['LSG_type']==2){
         $LSG_name=$row['municipality']."(M)";
     }elseif($row['LSG_type']==3){
         if($row['block_name']!=0){
         $LSG_name=$row['pname']."(P)";
         }
     }elseif($row['LSG_type']==0){
         $LSG_name="";
     }
          
       echo '<td>'.$i.'</td>';  
        echo '<td>'.$row["debtorno"].'</td>'; 
       echo '<td>'.$row["name"].'</td>'; 
          echo '<td>'.  $row["address"].'</td>';
                    // echo '<td>'.  $row["code"].'</td>';
       
             echo '<td>'.  $LSG_name.'</td>';
			 if($row['LSG_type']==3)
			 {
			  echo '<td>'.  $row["block"].'</td>';
			 }
			else{
			 
			    echo '<td></td>';
               }
			   echo '<td>'.  $row["district"].'</td>';
                   
       echo '<td>'.$row["mobile"].'</td>';                                          
       echo '<td>'.$row["insdate"].'</td>';
           echo '<td>'.$row["capacity"].'</td>'; 
          echo '<td>'.$row["model"].'</td>';  
       
               echo '<td>'.$row["plantstatus"].'</td>';
        
           
                
                             
   
          //    echo '<td>'.$row["plantstatus"].'</td>';
              
       echo'</tr>';                                            
       $i++;                             //             
   }             echo "<input type=hidden name='totrow' value=".$i.">";                                                     
   echo '</table>'; 
   echo '</table>';

       
   
 /*  if($_POST['cdm']==2) 
     {
        $sql0.=" AND salesorders.debtorno not in (SELECT `debtorno` FROM `bio_cdmbase`)";
         
       $sql1.= " AND `bio_oldorders`.`debtorno` not in (SELECT `debtorno` FROM `bio_cdmbase`)";
     } */                 
?>

