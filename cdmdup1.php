<?php
             $PageSecurity = 81;
    include ('includes/session.inc');
$title = _('CLIENT LIST');
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
      controlWindow=window.open("excel.php","Excel","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=100,height=100");

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
 echo '<center><font style="color: #333;
    background:#fff;
    font-weight:bold;
    letter-spacing:0.10em;
    font-size:16px;
    font-family:Georgia;
    text-shadow: 1px 1px 1px #666;"><br>BIOTECH CUSTOMER LIST</br></font></center>';
     echo'<table width=98% ><tr><td>';                                            // $_SERVER['PHP_SELF'] 
    echo'<div >'; 
    
    echo "<form method='POST' name=form1 action='" . $_SERVER['PHP_SELF'] . "'>"; 

       echo "<div style='height:400px; overflow:auto;'>";
echo "<table style='border:1 solid #000000;width:85%'>";
echo "<tr><th>Slno</th>";
echo "<th>Code</th>";
echo "<th>Customer name</th>";
echo "<th width=50px>LSG</th>";
echo "<th>ST</th>";
echo "<th>DST</th>";
echo "<th>Installed date</th>";
echo "<th>Model</th>";
echo "<th>CTY</th>";
echo "<th>Base</th>";
echo "<th>STS</th>"; 

 $sql0="( select debtorsmaster.name,
  bio_district.code as district,debtorsmaster.debtorno as 'debtorno',
ifnull(date_format(bio_installation_status.installed_date,'%d-%m-%Y'),'NA') as insdate,stockitemproperty.capacity,
 bio_plantstatus.plantstatus ,orderplant.stkcode as 'stockid',
 bio_corporation.corporation,
bio_municipality.municipality,
custbranch.block_name ,
bio_panchayat.name as pname,
custbranch.LSG_type,
bio_cdmbase.firewood,
bio_cdmbase.lpg,
bio_cdmbase.grid
 
from salesorders 
left join bio_installation_status on salesorders.orderno=bio_installation_status.orderno
left join debtorsmaster on salesorders.debtorno=debtorsmaster.debtorno
left join custbranch on debtorsmaster.debtorno=custbranch.debtorno
left join bio_state on `custbranch`.`cid`=bio_state.cid and `custbranch`.`stateid` = `bio_state`.`stateid`
left join bio_district on bio_district.cid=custbranch.cid and bio_district.stateid=custbranch.stateid and bio_district.did=custbranch.did

left join orderplant on salesorders.orderno=orderplant.orderno
left join bio_corporation on bio_corporation.country=custbranch.cid AND bio_corporation.state=custbranch.stateid
AND bio_corporation.district=custbranch.did
left join bio_municipality on bio_municipality.id = custbranch.LSG_name AND  bio_municipality.country=custbranch.cid AND bio_municipality.state=custbranch.stateid
AND bio_municipality.district=custbranch.did
left join bio_panchayat on  bio_panchayat.id = custbranch.block_name 
AND bio_panchayat.block =custbranch.LSG_name AND   bio_panchayat.country=custbranch.cid AND bio_panchayat.state=custbranch.stateid
AND bio_panchayat.district=custbranch.did
left join stockitemproperty on orderplant.stkcode=stockitemproperty.stockid
left join bio_cdmbase on bio_cdmbase.debtorno=salesorders.debtorno
left join bio_plantstatus on bio_installation_status.plant_status=bio_plantstatus.id where salesorders.debtorno not like '0000') "; 





$sql1="(SELECT debtorsmaster.name,
`bio_district`.code as district ,`bio_oldorders`.`debtorno` as 'debtorno' ,
  ifnull(date_format( `bio_oldorders`.`installationdate` , '%d-%m-%Y' ),'NA') AS insdate 
  , stockitemproperty.capacity, bio_plantstatus.plantstatus,bio_oldorders.plantid as 'stockid',
 bio_corporation.corporation,
bio_municipality.municipality,
custbranch.block_name ,
bio_panchayat.name as pname,
custbranch.LSG_type,
bio_cdmbase.firewood,
bio_cdmbase.lpg,
bio_cdmbase.grid
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
left join bio_corporation on bio_corporation.country=custbranch.cid AND bio_corporation.state=custbranch.stateid
AND bio_corporation.district=custbranch.did
left join bio_municipality on bio_municipality.id = custbranch.LSG_name AND  bio_municipality.country=custbranch.cid AND bio_municipality.state=custbranch.stateid
AND bio_municipality.district=custbranch.did
left join bio_panchayat on  bio_panchayat.id = custbranch.block_name 
AND bio_panchayat.block =custbranch.LSG_name AND   bio_panchayat.country=custbranch.cid AND bio_panchayat.state=custbranch.stateid
AND bio_panchayat.district=custbranch.did
left join bio_cdmbase on bio_cdmbase.debtorno=bio_oldorders.debtorno
LEFT JOIN bio_plantstatus ON bio_oldorders.currentstatus = bio_plantstatus.id
where bio_oldorders.debtorno not like '00000'  )
";                                          
  /*
$sql2="SELECT debtorsmaster.name,concat(debtorsmaster.address1,'</br>',debtorsmaster.address2) as address,
`bio_district`.code as district ,bio_state.code,`bio_oldorders`.`debtorno` as 'debtorno' ,
  concat( `custbranch`.`phoneno` , '</br>', `custbranch`.`faxno` ) AS 'mobile',
  ifnull(date_format( `bio_oldorders`.`installationdate` , '%d-%m-%Y' ),'NA') AS insdate ,
  LEFT(stockitemproperty.model,1) as model, stockitemproperty.capacity, bio_plantstatus.code as 'plantstatus',bio_oldorders.plantid as 'stockid',
 bio_corporation.corporation,
bio_municipality.municipality,
custbranch.block_name ,
bio_panchayat.name as pname,
custbranch.LSG_type,
bio_cdmbase.firewood,
bio_cdmbase.lpg,
bio_cdmbase.grid
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
left join bio_cdmbase on bio_cdmbase.debtorno=bio_oldorders.debtorno
LEFT JOIN bio_plantstatus ON bio_oldorders.currentstatus = bio_plantstatus.id
where bio_oldorders.debtorno not like '00000' AND `bio_oldorders`.`installationdate` !='0000-00-00' AND bio_oldorders.createdon = '2013-12-25'
"; 
         */
         $sql33= $sql0. " union ". $sql1; 
            $sql2.= " order by left(bio_oldorders.debtorno,1),length(bio_oldorders.debtorno),bio_oldorders.debtorno";
 
$_SESSION['qry']=$sql33;
    $result=DB_query($sql33,$db);  
       $k=0;
$i=1;
   while($row=DB_fetch_array($result))
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
                              echo '<td>'.  $LSG_name.'</td>';
                     echo '<td>'.  $row["code"].'</td>';
       
            
               echo '<td>'.  $row["district"].'</td>';
                                                  
       echo '<td>'.$row["insdate"].'</td>';
             if($row["model"]!="")
       {
          echo '<td>'.$row["model"].'</td>';  
       }
       else
       {
          echo '<td>NA</td>';  
       }
           
          
            echo '<td>'.$row["capacity"].'</td>'; 
            $fire=$row["firewood"];
             $lpg=$row["lpg"];
            $grid=$row["grid"];
                if($fire==1)
                {
                    $bas='WOD';
                    $ba=1;
                }
                  if($lpg==1)
                {
                    $bas='LPG';
                    $ba=1;
                }
                 if($grid==1)
                {
                    $bas='ELE';
                    $ba=1;
                }
     if($ba!=0)
     {
             echo '<td>'.$row["model"].'</td>';   
     }else
      echo '<td></td>';   
                
                             
      $sqls="Select debtorno from cdmlist where debtorno='".  $row["debtorno"]."'";
        $rt=DB_query($sqls,$db); 
        if(DB_num_rows($rt)>0)
        {
            echo '<td><b>CDM</b></td>';
        }
        else
        {
       echo '<td>'.$row["plantstatus"].'</td>';
        }
          //    echo '<td>'.$row["plantstatus"].'</td>';    
       echo'</tr>';                                            
       $i++;                             //             
   }           echo "<input type=hidden name='totrow' value=".$i.">";                                                     
   echo '</table>'; 
   echo '</table>';

                
?>
