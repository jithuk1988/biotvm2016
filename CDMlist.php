
<?php
   $PageSecurity = 40;
    include ('includes/session.inc');
$title = _('CDM list');
include ('includes/header.inc'); 
?>
<script src="SpryValidationTextField.js" type="text/javascript"></script>
<link href="SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<script type="text/javascript"> 
function viewcustomer(str4)
{
controlWindow=window.open("Customers.php?DebtorNo="+str4,"customer","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=1200,height=600");
}
 function selectTask(str9)
{
controlWindow=window.open("cdmhistory.php?DebtorNo="+str9,"customer","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=1200,height=600");
}
function insdate(str5)
{
controlWindow=window.open("insdate.php?DebtorNo="+str5,"insdate","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=1200,height=600");
} 
function exc()
{
      controlWindow=window.open("cdmexcel.php","Excel","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=100,height=100");

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
 
    
    echo "<form method='POST' name=form1 action='" . $_SERVER['PHP_SELF'] . "'>";
    echo '<div id="display"></div>';
        echo "<a href='CDMsurvey.php'><b><span>ADD CUSTOMER</span></b></a> ";
     echo'<table width=98% ><tr><td>'; 
    echo'<div >'; 
    if(isset($_GET['delete'])){ 
    $id=$_GET['delete'];
    $sql_dlt= "DELETE FROM `cdmlist` WHERE `debtorno`='$id'";
     $result_dlt=DB_query($sql_dlt,$db); 
      }
   /*  if(isset($_POST['submit']))   
{
     for($i=0;$i<$_POST['totrow'];$i++)
           {
            // echo $_POST['sel'.$i];
           if($_POST['sel'.$i])
             {
    $sql_insert="INSERT INTO `cdmlist`(`debtorno`, `status`) VALUES ('".$_POST['sel'.$i]."',0)";   
             $res_insert=DB_query($sql_insert,$db);
             }
           }      
}*/
//----------------------------------------------//
   if($_POST['submit1'])    
    {
        
        $debtor=$_POST['debtorn'] ;
        $vpa=$_POST['vpa'];
        $firewood=$_POST['fire'] ;
        $lpg=$_POST['lpg'];
        $grid=$_POST['grid'];
        $invno=$_POST['invoice'];
        $invdate=FormatDateForSQL($_POST['invdate']);
        $accept=$_POST['accept'];
        
            for($j=1;$j<$_POST['totrow'];$j++)
           {
            // echo $_POST['sel'.$i];
 
           if($_POST['cat'.$j])
             {
                 $su_array[]=$_POST['cat'.$j];
              $sum=join(',',$su_array);
             
             }
           } 
        
      /* for($j=0;$j<$_POST['totalrow'];$j++)
         {
            
            // echo $_POST['sel'.$i];
           if($_POST['cat'.$j])
             {
                 echo "llklkl";
           
             //echo  $catall=",".$_POST['cat'.$j];
             }
           }     */
            
      
       $sql_fuel="INSERT INTO `bio_past_fuel`(`debtorno`, `vpano`, `firewood`, `lpg`, `grid`, `invoiceno`, `invoicedate`, `acceptedby`,currdate,wastecatid,surdate) 
        VALUES
         ('".$debtor."','".$vpa."','".$firewood."','".$lpg."','".$grid."','".$invno."','".$invdate."','".$accept."',curdate(),'".$sum."','".FormatDateForSql($_POST['surdate'])."')";
      $res_fuel=DB_query($sql_fuel,$db); 
       $sql_status="UPDATE `cdmlist` SET `status`=1 WHERE `debtorno`='".$debtor."'" ;
           $res_status=DB_query($sql_status,$db);
       //---------------------------------------update-------------------------------//     L95
      
      $name=$_POST['name'];
      $add1=$_POST['house'];
      $add2=$_POST['street'];
      $phone=$_POST['phone'];
      $mobile=$_POST['mobile'];
       $sql_upd1="UPDATE `custbranch` SET `brname`='".$name."',`braddress1`='".$add1."',`braddress2`='".$add2."',`phoneno`='".$phone."',`faxno`='".$mobile."' 
      WHERE
       `debtorno`='".$debtor."'";
           $res_upd1=DB_query($sql_upd1,$db);
     $sql_upd2="UPDATE `debtorsmaster` SET `name`='".$name."',`address1`='".$add1."',`address2`='".$add2."' WHERE `debtorno`='".$debtor."'";     
               $res_upd2=DB_query($sql_upd2,$db);
    }
   if($_POST['submit2'])    
    {
        $debtor=$_POST['debtorn'] ;
        $vpa=$_POST['vpa'];
        $firewood=$_POST['fire'] ;
        $lpg=$_POST['lpg'];
        $grid=$_POST['grid'];
        $invno=$_POST['invoice'];
        $invdate=FormatDateForSql($_POST['invdate']);
        $accept=$_POST['accept'];
         
          $sql_num="SELECT count(surveyno) FROM `bio_present_fuel` WHERE `debtorno`='".$debtor."'";
         $result_num=DB_query($sql_num,$db); 
             $result_numb=DB_fetch_array($result_num);
             $surveyno= $result_numb[0];
             if($surveyno==0)
             {
                 $surno=1;
             } else
             {
                  $surno=$surveyno+1;
             }
  $sql_fuel="INSERT INTO `bio_present_fuel`(`debtorno`, `vpano`, `firewood`, `lpg`, `grid`, `invoiceno`, `invoicedate`, `acceptedby`,surveyno,currdate,surdate) 
        VALUES
         ('".$debtor."','".$vpa."','".$firewood."','".$lpg."','".$grid."','".$invno."','".$invdate."','".$accept."','".$surno."',curdate(),'".FormatDateForSql($_POST['surdate'])."')";
        $res_fuel=DB_query($sql_fuel,$db); 
         $sql_status="UPDATE `cdmlist` SET `status`=2 WHERE `debtorno`='".$debtor."'" ;
                $res_status=DB_query($sql_status,$db);  
          //--------------------------waste----------------------//
          
          $sql_was="SELECT * FROM `bio_waste_cat` where  `catid` in(".$_POST['soni'].")"; 
             $result_was=DB_query($sql_was,$db); 
             $i=0;
             $sql_srr="SELECT max(surveyno) FROM `bio_present_fuel` WHERE `debtorno`='".$debtor."'";
                 $result_srr=DB_query($sql_srr,$db); 
             $result_srr=DB_fetch_array($result_srr);
             $surve= $result_srr[0];
            while($row_was=DB_fetch_array($result_was))
            {
                if($_POST['caty'.$i]>0)
                {   
                $sql_new5="INSERT INTO `bio_waste_survey`(`vpano`, `debtorno`, `surveyno`, `wastecatid`, `qty1`, `unit`, `curdate`,surdate) VALUES 
                ('".$vpa."','".$debtor."','".$surno."','".$row_was['catid']."','".$_POST['caty'.$i]."','kg',curdate(),'".FormatDateForSql($_POST['surdate'])."')";
                  $res_new5=DB_query($sql_new5,$db); 
                }
                $i++;
            }
            
    }

   

       echo "<div style='height:400px; overflow:auto;'>";
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
         
         echo"<td>State:</td><td><select name='state'> ";
        echo '<option value="-1">select</option>';
         while ($mr=DB_fetch_array($res_st))
          {
              echo "<option value=".$mr[0].">".$mr[1]."</option>";
          }
          echo"</select></td>";
           $sqdt="Select did,district from bio_district where cid=1 and stateid=14";
          $res_dt=DB_query($sqdt,$db);
          echo "<div id='dist' name='dist'> <td>District:</td><td><select name='district'>"; 
           echo '<option value="-1">select</option>';
            while ($md=DB_fetch_array($res_dt))
          {
              echo "<option value=".$md[0].">".$md[1]."</option>";
          }
          echo"</select></td></div>";
        echo"<td><input type='submit' name='view' id='submit' value=search></td></tr></table>";
echo "<table style='border:1 solid #000000;width:100%'>";
echo "<tr><th>Slno</th>";
echo "<th>Code</th>";
echo "<th>Customer name</th>";
echo "<th>Address</th>";
echo "<th>District</th>";
echo "<th>Customer phone</th>";
echo "<th>Installed date</th>";
echo "<th>Model</th>";
echo "<th>Capacity</th>";
echo "<th>Replacing Fuel</th>";
echo "<th>Survey status</th>";

  $sql0="SELECT debtorsmaster.name,CONCAT(custbranch.braddress1,'</br>',custbranch.braddress2) AS address,bio_district.district,debtorsmaster.debtorno AS 'debtorno',
CONCAT(custbranch.phoneno,'</br>',custbranch.faxno) AS mobile,
DATE_FORMAT(bio_installation_status.installed_date,'%d-%m-%Y') AS insdate,stockitemproperty.model,stockitemproperty.capacity, bio_plantstatus.plantstatus ,orderplant.stkcode AS 'stockid', CASE WHEN bio_past_fuel.firewood>0 THEN 'FIREWOOD' WHEN bio_past_fuel.lpg>0 THEN 'LPG' WHEN bio_past_fuel.grid>0 THEN 'GRID' END AS 'Replacing'
FROM salesorders 
LEFT JOIN bio_installation_status ON salesorders.orderno=bio_installation_status.orderno
LEFT JOIN debtorsmaster ON salesorders.debtorno=debtorsmaster.debtorno
LEFT JOIN custbranch ON debtorsmaster.debtorno=custbranch.debtorno
LEFT JOIN bio_district ON bio_district.cid=custbranch.cid AND bio_district.stateid=custbranch.stateid AND bio_district.did=custbranch.did

LEFT JOIN orderplant ON salesorders.orderno=orderplant.orderno
LEFT JOIN stockitemproperty ON orderplant.stkcode=stockitemproperty.stockid
LEFT JOIN bio_plantstatus ON bio_installation_status.plant_status=bio_plantstatus.id AND salesorders.debtorno NOT IN (SELECT debtorno FROM cdmlist) 

INNER JOIN cdmlist ON   salesorders.debtorno= cdmlist.debtorno
LEFT JOIN bio_past_fuel ON  bio_past_fuel.debtorno=cdmlist.debtorno where salesorders.debtorno not like '000000'";


$sql1="SELECT debtorsmaster.name,concat(custbranch.braddress1,'</br>',custbranch.braddress2) as address,`bio_district`.`district` ,`bio_oldorders`.`debtorno` as 'debtorno' ,  concat( `custbranch`.`phoneno` , '</br>', `custbranch`.`faxno` ) AS 'mobile',date_format( `bio_oldorders`.`installationdate` , '%d-%m-%Y' ) AS insdate ,stockitemproperty.model, stockitemproperty.capacity, bio_plantstatus.plantstatus,bio_oldorders.plantid as 'stockid' , CASE WHEN bio_past_fuel.firewood>0 THEN 'FIREWOOD' WHEN bio_past_fuel.lpg>0 THEN 'LPG' WHEN bio_past_fuel.grid>0 THEN 'GRID' END AS 'Replacing'
FROM `bio_oldorders`
INNER JOIN `debtorsmaster` ON ( `bio_oldorders`.`debtorno` = `debtorsmaster`.`debtorno` )
INNER JOIN `custbranch` ON ( `debtorsmaster`.`debtorno` = `custbranch`.`debtorno` )
LEFT JOIN `bio_district` ON ( `custbranch`.`did` = `bio_district`.`did` )
AND (
`bio_district`.`cid` = `custbranch`.`cid`
)
AND (
`custbranch`.`stateid` = `bio_district`.`stateid`
)

LEFT JOIN bio_plantstatus ON bio_oldorders.currentstatus = bio_plantstatus.id
inner join cdmlist on   bio_oldorders.debtorno= cdmlist.debtorno
LEFT JOIN bio_past_fuel ON  bio_past_fuel.debtorno=cdmlist.debtorno
LEFT JOIN stockitemproperty ON bio_oldorders.plantid = stockitemproperty.stockid where bio_oldorders.debtorno not like '000000'
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



                   if($_POST['district']!=-1) {
                    $sql0.=" AND bio_district.did = '".$_POST['district']."'";
                    $sql1.=" AND bio_district.did = '".$_POST['district']."'";  
                     //$sql_selall.="AND bio_installation_status.orderno='890'";  
                  }
                  if($_POST['state']!=-1) {
                    $sql0.=" AND `bio_state`.`stateid` = '".$_POST['state']."'";
                    $sql1.=" AND `bio_state`.`stateid`= '".$_POST['state']."'";  
                     //$sql_selall.="AND bio_installation_status.orderno='890'";  
                  }
              }
            $sql0.=" order by left(salesorders.debtorno,1),length(salesorders.debtorno),salesorders.debtorno";
         
            $sql1.= " order by left(bio_oldorders.debtorno,1),length(bio_oldorders.debtorno),bio_oldorders.debtorno";
 $sql2="(".$sql0.") UNION (".$sql1.")";
       $result=DB_query($sql2,$db);  

     //  $result=DB_query($sql2,$db);  
       $k=0;
$i=0;    
          $slno=1;       
          
          
                                       //order by debtorsmaster.debtorno
   while($row=DB_fetch_array($result))
   {    $debtor=$row['debtorno'];
             if ($k==1)
             {
               echo '<tr class="EvenTableRows">';
                $k=0;
             }else 
              {
               echo '<tr class="OddTableRows">';
               $k=1;     
               }
             $sql_sur="SELECT `status` FROM `cdmlist` WHERE `debtorno`='".$row['debtorno']."'";
                 $result_sur=DB_query($sql_sur,$db); 
             $result_surve=DB_fetch_array($result_sur);
             $survey=$result_surve[0]; 
             
             echo '<td>'.$slno.'</td>';  
         echo '<td><a id='.$row["debtorno"].' onclick="viewcustomer(this.id);return false;" href="">'.$row["debtorno"].'</a></td>';   
        echo '<td>'.$row["name"].'</td>'; 
          echo '<td>'.  $row["address"].'</td>';
          echo '<td>'.  $row["district"].'</td>';
       echo '<td>'.$row["mobile"].'</td>';                                          
       echo '<td><a id='.$row["debtorno"].' onclick="insdate(this.id);return false;" href="">'.$row["insdate"].'</a></td>';
     echo '<td>'.$row["model"].'</td>';
echo '<td>'.$row["capacity"].'</td>'; 
       echo '<td>'.$row["Replacing"].'</td>';
        if($survey==0)
             {
               echo '<td>SURVEY NOT STARTED</td>';   
             }else
             {
                echo '<td>SURVEY STARTED</td>';    
             }
             
             
        echo"<td><a id='$debtor' onclick='dlt(this.id)'>SELECT</a> </td>";
        echo"<td><a id='$debtor' onclick='slt(this.id)'>DELETE</a> </td>";
         echo "<input type=hidden name='debtor' id='debtor' value=".$debtor.">";               
       echo'</tr>';                                            
       $i++;  
       $slno++;                           //             
   }             echo "<input type=hidden name='totrow' value=".$i.">";   
if($_SESSION[UserID]=='admin')
{
   echo"<a id='$debtor' onclick='exc()'>Export to Excel</a> ";            
}                                    
   echo '</table>'; 
   echo '</table>'; 
?>
<script type="text/javascript">
function slt(str){
location.href="?delete=" +str;         
}
function dlt(str)
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
  { // alert (str1);
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
          
         // document.getElementById('showloc').style.display = "block";
     document.getElementById("display").innerHTML=xmlhttp.responseText; 
     
    }            
  } 
xmlhttp.open("GET","cdmlistajax.php?str="+str,true);//alert(str4);
xmlhttp.send();     // alert(str3);*/
}
function fuel()
{
   var derno=document.getElementById('debtor').value; 
   // window.open("call_log.php");
  controlWindow=window.open("CDMfuel.php?DebtorNo="+derno,"viewlog", "toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=800,height=600");    
}
function validation()
{
 
    str1=document.getElementById('fire').value;     
     str2=document.getElementById('lpg').value;
     str3=document.getElementById('vpa').value;
     str4=document.getElementById('grid').value;
     if(str3=="")
     {
          alert("please enter VPA number");
                   return 1; 
     }
     if(str1=="" && str2=="" && str4=="")
     {
        alert("please enter atleast one of the method");
                   return 1;
     }

          if(isNaN(str1))
          {
              alert("please enter valid unit");
               return 1;
          }
            if(isNaN(str2))
          {
              alert("please enter valid unit");
               return 1;
          }
            if(isNaN(str4))
          {
              alert("please enter valid unit");
               return 1;
          }
         
                     
             
             
}

</script>