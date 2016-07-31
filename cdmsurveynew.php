<?php
   $PageSecurity = 80;
    include ('includes/session.inc');
$title = _('CDM Client List');
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
         echo'<table width=98% ><tr><td>';                                            // $_SERVER['PHP_SELF'] 
    echo'<div >'; 
    
    echo "<form method='POST' name=form1 action='" . $_SERVER['PHP_SELF'] . "'>"; 

    echo "<center><b>CUSTOMER LIST</b></center><a href='CDMlist.php'><b><span>VIEW MONITERED CUSTOMERS</span></b></a> ";

    if(isset($_POST['submit']))   
{
    if($_POST['cdm']==2)
    {
        $h=0;
     
        if($_POST['fire'])
   {
         $fire=1; 
   }
    if($_POST['lpg'])
    {
       $lpg=1; 
    }
        if($_POST['grid'])
        {
         $grid=1;  
        }
           if($fire==1 || $lpg==1 || $grid==1)
        {
            $h=0;
        }else{$h=1;}
        if($h==0)
        {
           // $k=0;
           $totrow=$_POST['totrow'];  
     for($i=0;$i<$totrow;$i++)
           {       
               if($_POST['sel'.$i])
             {        
                // $s[]="('".$_POST['sel'.$i]."','".$fire."','".$lpg."','".$grid."')";
                     $sql_insert="INSERT INTO bio_cdmbase (`debtorno`, `firewood`, `lpg`, `grid`) VALUES ('".$_POST['sel'.$i]."','".$fire."','".$lpg."','".$grid."')";  
              $res_insert=DB_query($sql_insert,$db);
             }
     
             
            /*    $k=$totrow/50;
         
             for($j=1;$j<=$k+1;$j++)
              {        echo "b";
               $ave=$j*50;
              while($i<$ave)
              {         echo "c";
           if($_POST['sel'.$i])
             {             echo "d";
                // $s[]="('".$_POST['sel'.$i]."','".$fire."','".$lpg."','".$grid."')";
                       $sql_insert="INSERT INTO bio_cdmbase (`debtorno`, `firewood`, `lpg`, `grid`) VALUES ('".$_POST['sel'.$i]."','".$fire."','".$lpg."','".$grid."')";  
                $res_insert=DB_query($sql_insert,$db);
             }
             $i++;
              }   
              }   } */
           } 
                //  $k=join(",", $s);   
           
             //
           //echo  $sql_insert="INSERT INTO bio_cdmbase (`debtorno`, `firewood`, `lpg`, `grid`) VALUES $s";  
        }   
    }else
    {
     for($i=0;$i<$_POST['totrow'];$i++)
           {
            // echo $_POST['sel'.$i];
           if($_POST['sel'.$i])
             {
  $sql_insert="INSERT INTO `cdmlist`(`debtorno`, `status`) VALUES ('".$_POST['sel'.$i]."',0)";   
            $res_insert=DB_query($sql_insert,$db);
             }  
           }  }  
                 prnMsg('Baseline Added.','success');       
}
       echo"<fieldset style='width:90%;'>";
       echo"<legend></legend>";
        echo"<table style='width:100%;'><tr>";  
        echo '<td><input type="radio" name="cdm" id="cdm1" value=1 onclick="panel()" checked>CDM CUSTOMERS</td>';
            if($_POST['cdm']==2)
        {  
        echo '<td>
      <input type="radio" name="cdm" id="cdm2" value="2" onclick="showpan()" checked>BASELINE ADDING</td><td>';
        }else
        {
          echo '<td>
      <input type="radio" name="cdm" id="cdm2" value="2" onclick="showpan()" >BASELINE ADDING</td><td>';   
        }   
     
          if($_POST['cdm']==2)
   {
     echo '<div id="bas"">'; 

   }else
   {
    echo '<div id="bas"" style="visibility: hidden;">'; 

   }
       
        echo "<fieldset>";
           echo"<legend><h3>SELECT BASE LINE </h3></legend>";
       echo "<table>";
       echo '<tr><td><b>FIREWOOD</b><input type="checkbox" name="fire" id="fire"></td><td><b>LPG</b><input type="checkbox" name="lpg" id="lpg"></td><td><b>GRID</b>
       <input type="checkbox" name="grid" id="grid"></td></tr>';
 
    
       echo "</table>";
       echo "</fieldset>";
            echo "</div>"; 
          echo "</td></tr>";
        echo "</fieldset></table>";
   
   
echo"<div id=grid>";
       echo"<fieldset style='width:90%;'>";
       echo"<legend><h3></h3></legend>";
        echo"<table style='width:100%;'><tr>";  
        
       
         echo"<td>Capacity From:</td><td><input type='text' name=capacity1  value='".$_POST['capacity1']."'></td> <td>To <input type='text' name=capacity2  value='".$_POST['capacity2']."'></td>"; 
          echo"<td>Model:</td><td><select name='Model'> ";
         $sqpt='SELECT DISTINCT model
FROM stockitemproperty
WHERE model != ""';
          $res_pt=DB_query($sqpt,$db);
            echo '<option value="-1">select</option>';
            while ($mpt=DB_fetch_array($res_pt))
          {
              if($_POST['Model']==$mpt[0])
              {
                 echo "<option selected value=".$mpt[0].">".$mpt[0]."</option>"; 
              }
              else
              echo "<option value=".$mpt[0].">".$mpt[0]."</option>";
          }
          echo"</select></td>";
         
         
         
         
         
         $sq="Select stateid,state from bio_state where cid=1";
          $res_st=DB_query($sq,$db);
         
         echo"<td>State:</td><td><select name='state' id='state' onchange='showdist(this.value)'> ";
        echo '<option value="-1">select</option>';
         while ($mr=DB_fetch_array($res_st))
          {
              if($_POST['state']==$mr[0])
              {
                echo "<option selected value=".$mr[0].">".$mr[1]."</option>";  
              }
              echo "<option value=".$mr[0].">".$mr[1]."</option>";
          }
          echo"</select></tr></td>";
          if($_POST['state'] < 0)
          {
              $_POST['state'] = 14;//'".$_POST['state']."'
          }
           $sqdt="Select did,district from bio_district where cid=1 and stateid='".$_POST['state']."'";
          $res_dt=DB_query($sqdt,$db);
          echo "<div id='dist' name='dist'> <td>District:</td><td><select name='district' id='district'>"; 
           echo '<option value="-1">select</option>';
            while ($md=DB_fetch_array($res_dt))
          {
              if($_POST['district']==$md[0])
              {
                    echo "<option selected value=".$md[0].">".$md[1]."</option>";
              }
              echo "<option value=".$md[0].">".$md[1]."</option>";
          }
          
          echo"</select></td></div>";
          
                echo '<td>LSG Type<select name="lsgType" id="lsgType" style="width:100px" onchange="showblock(this.value)">';
                  echo '<option value=0></option>'; 
              if($_POST['lsgType']==1)
              {
                   echo '<option selected value=1>Corporation</option>';  
                       echo '<option value=2>Muncipality</option>';
                      echo '<option value=3>Panchayat</option>'; 
              }  else
              if($_POST['lsgType']==2)
              {
                 echo '<option value=1>Corporation</option>';  
                       echo '<option selected value=2>Muncipality</option>';
                      echo '<option value=3>Panchayat</option>';  
              }  else
              if($_POST['lsgType']==3)
              {
                 echo '<option value=1>Corporation</option>';  
                       echo '<option value=2>Muncipality</option>';
                      echo '<option selected value=3>Panchayat</option>';  
              }  else
              {
                    echo '<option value=1>Corporation</option>';  
                       echo '<option value=2>Muncipality</option>';
                      echo '<option  value=3>Panchayat</option>';   
              }
              if($_POST['lsgType']) 
                 {
                    $country1=1;
                    $state=$_POST['state'];      
                    $district=$_POST['district'];
                      if($_POST['lsgType']==1)            //Corporation
                             {
                              echo "<td align=left colspan=2>";
                                 echo'<div style="align:right" id=block>';             
    
                                  $sql="SELECT * FROM bio_corporation WHERE country=$country1 AND state=$state AND district=$district";
        $result=DB_query($sql,$db);
        $row=DB_fetch_array($result);
        $cid=$row['country'];
        $sid=$row['state'];
        $district=$row['district'];

          if($cid==1 && $sid==14)  
          {
              if($district==12){$distname='Thiruvananthapuram';}
              if($district==6){$distname='Kollam';} 
              if($district==2){$distname='Eranakulam';} 
              if($district==13){$distname='Thrissur';} 
              if($district==8){$distname='Kozhikode';} 
                    echo '<table align=left ><tr><td width=200px>' . _('Corporation Name') . ':</td>';
                    echo '<td><select name="lsgName" readonly id="lsgName" style="width:190px">';
                    echo "<option value='".$district."'>".$distname."</option>"; 
                    echo '</select></td>';   
                    echo '</tr></table>'; 
                        echo'</div>'; 
                        echo "</td>";    
          }
                             }
           else
           if($_POST['lsgType']==2) 
           {
               echo "<td align=left colspan=2>";
                echo'<div style="align:right" id=block>';    
               echo '<table align=left><tr><td width=200px>' . _('Municipality Name') . ':</td>';    
        
        $sql="SELECT * FROM bio_municipality WHERE country='$country1' AND state='$state' AND district='$district'";
        $result=DB_query($sql,$db);
        
        echo '<td><select name="lsgName" id="lsgName" style="width:190px" tabindex=13>';
         $f=0;
         while($myrow1=DB_fetch_array($result))
         {
         if ($myrow1['id']==$_POST['lsgName'])
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
         echo $myrow1['id'] . '">'.$myrow1['municipality'];
         echo '</option>';
         $f++;  
         }   

      echo '</select></td>';
      echo'</td></tr></table>'; 
          echo'</div>';
          echo "</td>";
           }
            elseif($_POST['lsgType']==3)   
            {
                     echo '<td align=left colspan=2>';
        echo'<div style="align:right" id=block>';  
         echo '<table align=left ><tr><td width=200px>' . _('Block Name') . ':</td>';    
         
         $sql="SELECT * FROM bio_block WHERE country=$country1 AND state=$state AND district=$district";
         $result=DB_query($sql,$db);
         
         echo '<td id="showgramapanchayath"><select name="lsgName" id="lsgName" style="width:190px" tabindex=13 onchange="showgramapanchayath(this.value)">';
         $f=0;
         while($myrow1=DB_fetch_array($result))
         {
         if ($myrow1['id']==$_POST['lsgName'])
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
         echo $myrow1['id'] . '">'.$myrow1['block'];
         echo '</option>';
         $f++;
         }

      echo '</select></td>';
      echo'</tr>';
      
     echo '<tr id="showpanchayath"><td width=200px>' . _('Panchayat Name') . ':</td>';  
      $blockid=$_POST['lsgName'];      
         
         $sql="SELECT * FROM bio_panchayat WHERE country=$country1 AND state=$state AND district=$district AND block=$blockid ";
         $result=DB_query($sql,$db);
         
         echo '<td><select name="gramaPanchayath" id="gramaPanchayath" style="width:190px" tabindex=14>';
         $f=0;
         while($myrow1=DB_fetch_array($result))
         {
         if ($myrow1['id']==$_POST['gramaPanchayath'])
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
         echo $myrow1['id'] . '">'.$myrow1['name'];
         echo '</option>';
         $f++;
         }

      echo '</select></td>';     
       
      
      echo'</table>';                       
        echo'</div>';
        echo'</td>';
      
       // echo'<td><div id=showpanchayath></div></td>';            echo '<tr id="showpanchayath"><td width=200px>' . _('Panchayat Name') . ':</td>';    
            }    
                 }
            else
                 {
                     echo '<td align=left colspan=2>';
        echo'<div style="align:right" id=block>';             
        echo'</div>';
        echo'</td>';
      
       // echo'<td><div id=showpanchayath></div></td>';
                     
                 }
        echo"<td><input type='submit' name='view' id='submit' value=search></td></tr></table></div>";
        echo"<div id='close'>";
                    if(isset($_POST['view']))   
        {
        $sql0=" select debtorsmaster.name,concat(debtorsmaster.address1,'</br>',debtorsmaster.address2) as address,
  bio_district.code as district,bio_state.code,debtorsmaster.debtorno as 'debtorno',
concat(custbranch.phoneno,'</br>',custbranch.faxno) as mobile,
ifnull(date_format(bio_installation_status.installed_date,'%d-%m-%Y'),'NA') as insdate,LEFT(stockitemproperty.model,1) as model,stockitemproperty.capacity,
 bio_plantstatus.code as 'plantstatus' ,orderplant.stkcode as 'stockid',
 bio_corporation.corporation,
bio_municipality.municipality,
debtorsmaster.block_name ,
bio_panchayat.name as pname,
debtorsmaster.LSG_type,
bio_cdmbase.firewood,
bio_cdmbase.lpg,
bio_cdmbase.grid
 
from salesorders 
inner join bio_installation_status on salesorders.orderno=bio_installation_status.orderno
left join debtorsmaster on salesorders.debtorno=debtorsmaster.debtorno
left join custbranch on debtorsmaster.debtorno=custbranch.debtorno
left join bio_state on `debtorsmaster`.`cid`=bio_state.cid and `debtorsmaster`.`stateid` = `bio_state`.`stateid`
left join bio_district on bio_district.cid=debtorsmaster.cid and bio_district.stateid=debtorsmaster.stateid and bio_district.did=debtorsmaster.did

left join orderplant on salesorders.orderno=orderplant.orderno
left join bio_corporation on bio_corporation.country=custbranch.cid AND bio_corporation.state=debtorsmaster.stateid
AND bio_corporation.district=custbranch.did
left join bio_municipality on bio_municipality.id = debtorsmaster.LSG_name AND  bio_municipality.country=debtorsmaster.cid AND bio_municipality.state=debtorsmaster.stateid
AND bio_municipality.district=debtorsmaster.did
left join bio_panchayat on  bio_panchayat.id = custbranch.block_name 
AND bio_panchayat.block =debtorsmaster.LSG_name AND   bio_panchayat.country=debtorsmaster.cid AND bio_panchayat.state=debtorsmaster.stateid
AND bio_panchayat.district=debtorsmaster.did
left join stockitemproperty on orderplant.stkcode=stockitemproperty.stockid
left join bio_cdmbase on bio_cdmbase.debtorno=salesorders.debtorno
left join bio_plantstatus on bio_installation_status.plant_status=bio_plantstatus.id where salesorders.debtorno not like '0000' "; 





$sql1="SELECT debtorsmaster.name,concat(debtorsmaster.address1,'</br>',debtorsmaster.address2) as address,
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
where bio_oldorders.debtorno not like '0000' AND `bio_oldorders`.`installationdate` !='0000-00-00'"; 
     /*
  $sql0=" select debtorsmaster.name,concat(debtorsmaster.address1,'</br>',debtorsmaster.address2) as address,
  bio_district.code as district,bio_state.code,debtorsmaster.debtorno as 'debtorno',
concat(custbranch.phoneno,'</br>',custbranch.faxno) as mobile,
ifnull(date_format(bio_installation_status.installed_date,'%d-%m-%Y'),'NA') as insdate,LEFT(stockitemproperty.model,1) as model,stockitemproperty.capacity,
 bio_plantstatus.code as 'plantstatus' ,orderplant.stkcode as 'stockid',
 bio_corporation.corporation,
bio_municipality.municipality,
debtorsmaster.block_name ,
bio_panchayat.name as pname,
debtorsmaster.LSG_type,
bio_cdmbase.firewood,
bio_cdmbase.lpg,
bio_cdmbase.grid
 
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
left join bio_panchayat on  bio_panchayat.id = custbranch.block_name 
AND bio_panchayat.block =debtorsmaster.LSG_name AND   bio_panchayat.country=debtorsmaster.cid AND bio_panchayat.state=debtorsmaster.stateid
AND bio_panchayat.district=debtorsmaster.did
left join stockitemproperty on orderplant.stkcode=stockitemproperty.stockid
left join bio_cdmbase on bio_cdmbase.debtorno=salesorders.debtorno
left join bio_plantstatus on bio_installation_status.plant_status=bio_plantstatus.id where salesorders.debtorno not like '0000' AND bio_installation_status.installed_date !='0000-00-00' "; 





$sql1="SELECT debtorsmaster.name,concat(debtorsmaster.address1,'</br>',debtorsmaster.address2) as address,
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
where bio_oldorders.debtorno not like '00000' AND `bio_oldorders`.`installationdate` !='0000-00-00'
";                 */
/*if($_SESSION['officeid']==2)
{
      $sql0.=" AND debtorsmaster.did in(1,2,3,7,13)";
                         $sql1.=" AND debtorsmaster.did in(1,2,3,7,13)";
}
if($_SESSION['officeid']==3)
{
      $sql0.=" AND debtorsmaster.did in(4,5,8,9,10,14)";
                         $sql1.=" AND debtorsmaster.did in(4,5,8,9,10,14)";
}

if($_SESSION['officeid']==4)
{
       $sql0.=" AND debtorsmaster.did in(6,11,12)";
                         $sql1.=" AND debtorsmaster.did in(6,11,12)";
}
else
{
    
}
*/

if(($_POST['view']) || ($_POST['go']) || ($_POST['previous']) || ($_POST['next']) ) 
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
                  else{                  
    if($_POST['capacity2']!=NULL) {
                    $sql0.=" AND stockitemproperty.capacity => ".$_POST['capacity2'].""; 
                    $sql1.=" AND stockitemproperty.capacity => ".$_POST['capacity2'].""; 
                     //$sql_selall.="AND bio_installation_status.orderno='890'";  
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
     }*/       if($_POST['state']!=-1) {
                    $sql0.=" AND `bio_state`.`stateid` = '".$_POST['state']."'";
                    $sql1.=" AND `bio_state`.`stateid`= '".$_POST['state']."'";  
                     //$sql_selall.="AND bio_installation_status.orderno='890'";  
                  }

                       if($_POST['district']!=-1) {
                    $sql0.=" AND bio_district.did = '".$_POST['district']."'";
                    $sql1.=" AND bio_district.did = '".$_POST['district']."'";  
                   
                     //$sql_selall.="AND bio_installation_status.orderno='890'"; 
                      if (isset($_POST['lsgType']))  
                        {
                          if($_POST['lsgType']!=0)  
                           {
                             if($_POST['lsgType']!=NULL)  
                              {  
                               $sql0 .=" AND debtorsmaster.LSG_type=".$_POST['lsgType']; 
                                $sql1 .=" AND debtorsmaster.LSG_type=".$_POST['lsgType'];      
                                if (isset($_POST['lsgName']))  
                                  {
                                   if($_POST['lsgName'])  
                                    {
                                     $sql0 .=" AND debtorsmaster.LSG_name=".$_POST['lsgName'];  
                                     $sql1 .=" AND debtorsmaster.LSG_name=".$_POST['lsgName']; 
                                    } 
                                  }
                                  if (isset($_POST['gramaPanchayath']))   
                                   {  
                                    if($_POST['gramaPanchayath']!=0 OR $_POST['gramaPanchayath']!=NULL)  
                                     {
                                      $sql0 .=" AND debtorsmaster.block_name=".$_POST['gramaPanchayath']; 
                                      $sql1 .=" AND debtorsmaster.block_name=".$_POST['gramaPanchayath'];
                                     }       
                                   } 
                              }
                           }
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
                echo '<input type="submit" name="go" value="Go">';
                echo '<input type="submit" name="previous" value="previous">';
              echo '<input type="submit" name="next" value="next">' ;
       
   
                                               }
   
        }
         echo "<div style='height:400px; overflow:auto;'>";  
if($_SESSION[UserID]=='admin' || $_SESSION[UserID]=='bdm_incident')
{
   echo"<a id='$debtor' onclick='exc()'>Export to Excel</a> ";     
}
echo "<table style='border:1 solid #000000;width:100%'>";
echo "<tr><th>Slno</th>";
echo "<th>Code</th>";
echo "<th>Customer name</th>";
echo "<th>Address</th>";
echo "<th>ST</th>";

echo "<th>DST</th>";
echo "<th width=50px>LSG</th>";
echo "<th>Customer phone</th>";
echo "<th>Installed date</th>";
echo "<th>Model</th>";
echo "<th>CTY</th>";
echo "<th>Base</th>";
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
         echo '<td><a id='.$row["debtorno"].' onclick="viewcustomer(this.id);return false;" href="">'.$row["debtorno"].'</a></td>';   
       echo '<td>'.$row["name"].'</td>'; 
          echo '<td>'.  $row["address"].'</td>';
                     echo '<td>'.  $row["code"].'</td>';
       
            
               echo '<td>'.  $row["district"].'</td>';
                    echo '<td>'.  $LSG_name.'</td>';
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
             echo '<td><a id='.$row["debtorno"].' onclick="baseline(this.id);return false;" href="">'.$bas.'</td>';   
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
          if($_POST['cdm']==2 && $bas=='')
          {
                 echo'<td><input type="checkbox" name="sel'.$i.'" value='.$row['debtorno'].' checked> </td></tr>';  
          }else
          {
    echo'<td><input type="checkbox" name="sel'.$i.'" value='.$row['debtorno'].'> </td></tr>';  }        
       echo'</tr>';                                            
       $i++;                             //             
   }             echo "<input type=hidden name='totrow' value=".$i.">";                                                     
   echo '</table>'; 
   echo '</table>';

       
   echo '<center><input type="submit" name="submit" onclick="if(valid()==1){return false;}"></center>';   

 /*  if($_POST['cdm']==2) 
     {
        $sql0.=" AND salesorders.debtorno not in (SELECT `debtorno` FROM `bio_cdmbase`)";
         
       $sql1.= " AND `bio_oldorders`.`debtorno` not in (SELECT `debtorno` FROM `bio_cdmbase`)";
     } */                 
?>
<script type="text/javascript">
function valid()
{
if (cdm2.checked == 1){
     if((fire.checked==1) || (lpg.checked==1) || (grid.checked==1))
     {
        
     }else
     {
         alert("please select atleast one Baseline");
         return 1; 
     }
    
}
}
</script>
