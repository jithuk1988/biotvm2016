<?php
$PageSecurity = 20;
include('includes/session.inc');
$pagetype=3;
$title = _('Concept Proposal');  
include('includes/header.inc');
include('includes/sidemenu.php');
include('includes/bio_GetPrice.inc');    
$office=$_SESSION['officeid']; 
?>

<!--<style type="text/css" >

</style>  -->

<?php                                                                                       
echo '<center><font style="color: #333;
    background:#fff;
    font-weight:bold;
    letter-spacing:0.10em;
    font-size:16px;
    font-family:Georgia;
    text-shadow: 1px 1px 1px #666;">CONCEPT PROPOSALS</font></center>';
//========================================================================   

if(!isset($_SESSION['plant_selected']))      
{
    $_SESSION['plant_selected']=0;
} 


if(isset($_GET['budgetinitial']))      
 {
    
$budgetinitial=$_GET['budgetinitial'];    

if(!isset($_SESSION['budgetinitial']))     
 {

$_SESSION['budgetinitial']= $budgetinitial;   
    
}

}

if(isset($_SESSION['budgetinitial']))     
 {  
    
$budgetinitial= $_SESSION['budgetinitial'];   
       
}

if(isset($_GET['remove']))    
  {

$stockid=$_GET['stockid'];    
$leadid=$_GET['lead'];
    
$sql="DELETE
      FROM bio_temppropitems
      WHERE leadid= $leadid AND
            stockid='$stockid'";    
$result=DB_query($sql,$db);

$sql_delete="DELETE FROM bio_temppropsubsidy
                   WHERE bio_temppropsubsidy.leadid=".$leadid."
                   AND bio_temppropsubsidy.stockid='".$stockid."'";
$result_delete = DB_query($sql_delete,$db);

$sql="SELECT sum(netprice) FROM bio_temppropitems WHERE leadid=$leadid";     //select sum
$result=DB_query($sql,$db);
$row=DB_fetch_array($result);

 $netprice=$row[0];
 $sum=$netprice-$_SESSION['budgetinitial'];
 $_SESSION['sum']= $sum;
}

$expectdate=FormatDateForSQL($_POST['expectdate']);

if(isset($_POST['submit']))
{
    
    $lead=$_POST['LeadID'];
    $cur_date=date("Y-m-d");
    $userid=$_SESSION['UserID'];
    $crdt=date("Y-m-d H:i:s");
    $fsentryid=$_POST['FSEntryID'];
    
 $empid=$_SESSION['empid'];
  
        $sql_team="SELECT teamid FROM bio_teammembers WHERE empid=".$empid;
        $result_team=DB_query($sql_team,$db);
        $row_team=DB_fetch_array($result_team);
        $assignedfrm=$row_team['teamid'];
 
 
 
$sql_app="SELECT www_users.userid 
            FROM bio_emp,www_users
            WHERE bio_emp.designationid=4
            AND www_users.empid=bio_emp.empid";
$result_app=DB_query($sql_app,$db);
$row_app=DB_fetch_array($result_app);
$approval_by=$row_app['userid'];

if($_SESSION['UserID']==$approval_by)
{
    $approval_by='admin';
}
  
    
 $sql="SELECT stockid,
             description,
             qty,price,
             tprice 
        FROM bio_temppropitems 
       WHERE leadid=".$lead;
//echo "$sql";
$result=DB_query($sql,$db);
$num_rows = $result->num_rows;

if ($num_rows > 0) 
{ 
    $_SESSION['plant_selected']=1;
$sql2="SELECT SUM(tprice) AS totalprice FROM bio_temppropitems WHERE leadid=".$lead;
//echo "$sql2";
$result2=DB_query($sql2,$db);
$myrow2=DB_fetch_array($result2);
$totalprice=$myrow2[0];    
 
if ($totalprice > 0) 
{
$sql3="INSERT INTO bio_conceptproposal (lead_id, 
                                        fsentry_id, 
                                        total_price,
                                        created_on,
                                        expect_date,
                                        created_by,
                                        approved_by,
                                        signatory_by,
                                        status,
                                        tender_status) 
                               VALUES ('".$lead."',
                                       '".$fsentryid."',
                                       '".$totalprice."',
                                       '".$cur_date."',
                                       '".$expectdate."',
                                       '".$userid."',
                                       '".$approval_by."',
                                       '".$userid."',
                                       1,
                                       '".$_POST['tenderstatus']."')";
//echo "$sql3";
$ErrMsg =  _('An error occurred while inserting proposal data');
$result3=DB_query($sql3,$db,$Errmsg);    
//echo"";
 
    
$sql7="SELECT LAST_INSERT_ID()";
$result7=DB_query($sql7,$db);
$myrow7=DB_fetch_array($result7);
$lastid=$myrow7[0];
$i=0;
    
while ($myrow=DB_fetch_array($result))  
 {
  $sql4="INSERT INTO bio_conceptproposaldetails (cp_id,
                                                 slno,
                                                 stockid,
                                                 description,
                                                 qty,
                                                 price,
                                                 tprice) 
                                        VALUES ('".$lastid."',
                                                '".++$i."',
                                                '".$myrow['stockid']."',
                                                '".$myrow['description']."',
                                                '".$myrow['qty']."',
                                                '".$myrow['price']."',
                                                '".$myrow['tprice']."')";
                                          
  $ErrMsg =  _('An error occurred while inserting proposal details data');
  $result4=DB_query($sql4,$db,$Errmsg);
  
      


}// end $result while loop    


//  $sql_sch="SELECT * FROM bio_temppropsubsidy
//            WHERE leadid=".$lead;
//  $result_sch=DB_query($sql_sch,$db);
//  $row_count=DB_num_rows($result_sch);
//  
//  if($row_count>0){
//      
//  while($myrow_sch=DB_fetch_array($result_sch)){
      
  $sql_subsidy="INSERT INTO bio_cpsubsidy(cp_id,
                                          leadid,
                                          stockid,
                                          scheme,
                                          amount)
                                   SELECT $lastid,
                                          leadid,
                                          stockid,
                                          scheme,
                                          amount FROM bio_temppropsubsidy WHERE leadid=$lead";
        
        $result_subsidy=DB_query($sql_subsidy,$db,$Errmsg);
        
//    }  
 // }
  
    $task_ID=10;
    $duedate="0000-00-00";
    $date1="0000-00-00";
    $status=0;
if($lastid==0 or $lastid=='')
{
$lastid=$_SESSION['cpid'];
}

    
    $sql_appr="INSERT INTO bio_approval(taskid,
                                        leadid,
                                        submitted_user,
                                        approval_user,
                                        assigneddate,
                                        duedate,
                                        taskcompleteddate,
                                        taskcompletedstatus,
                                        proposal_no) 
                                VALUES ('".$task_ID."',
                                        '".$lead."',
                                        '".$userid."',
                                        '".$approval_by."',
                                        '".$cur_date."',
                                        '".$duedate."',
                                        '".$date1."',
                                        '".$status."',
                                        '".$lastid."')";
                                        
    $result_appr=DB_query($sql_appr,$db);  
        
}// end if total price  >0  


//echo $sql_doc="SELECT bio_predocuments.leadid FROM bio_predocuments WHERE bio_predocuments.leadid=$lead";       
// $result_doc=DB_query($sql_doc,$db);
// $count1=DB_num_rows($result_doc);

// if($count1>0){
// 
// echo     $sql_pre="INSERT INTO bio_predocuments(leadid,cpid)
//                VALUES('".$_POST['leadid1']."',   
//                       '".$_POST['cpid1']."')";
//     
//      $result_pre=DB_query($sql_pre,$db);
// }






 

if($result_subsidy)
{
   $sql_del="DELETE FROM bio_temppropsubsidy";
//   $ErrMsg =  _('An error occurred while deleting temp proposal items');
   $result_del=DB_query($sql_del,$db); 
}  

$sql5="UPDATE bio_leads SET leadstatus = '11' WHERE bio_leads.leadid=".$lead;
$ErrMsg =  _('An error occurred while updating lead status to leads data');     
$result5=DB_query($sql5,$db,$Errmsg);


     //    $taskid=3;
         
         $sql_flag="UPDATE bio_leadtask SET taskcompletedstatus = '1',
                                          taskcompleteddate='".$cur_date."' 
                   WHERE bio_leadtask.leadid=$lead 
                     AND bio_leadtask.taskid=3 
                     AND taskcompletedstatus!=2
                     AND teamid=$assignedfrm";   
    DB_query($sql_flag,$db);
    

$sql6="DELETE FROM bio_temppropitems WHERE leadid=".$lead;
$ErrMsg =  _('An error occurred while deleting temp proposal items');
$result6=DB_query($sql6,$db,$Errmsg);



 ?>
      
      <script>
      
     var lead=<?php echo $lead; ?>;
     window.opener.location='bio_instTaskview.php?lead='+ lead;
      window.close();

      </script>
      <?php


    

}
    else 
    {
        echo "No items could be retrieved";
    } ;
    unset($_SESSION['plant_selected']);      
}



if(isset($_POST['Editcp']))
{

    $sql_app="SELECT www_users.userid 
                FROM bio_emp,www_users
               WHERE bio_emp.designationid=4
                 AND www_users.empid=bio_emp.empid";
    $result_app=DB_query($sql_app,$db);
    $row_app=DB_fetch_array($result_app);
    $approval_by=$row_app['userid'];
                                                                     
if($_SESSION['UserID']==$approval_by)
{
    $approval_by='admin';
}
 $lead=$_POST['LeadID'];
 if($lead=="")
 {
    $lead=$_SESSION['leadid'];
 }
    
    $cur_date=date("Y-m-d");
    $userid=$_SESSION['UserID'];
    $crdt=date("Y-m-d H:i:s");
    
    $cp_id=$_POST['CpID'];
 if($cp_id=="")
 {
    $cp_id=$_SESSION['cpid'];
        
 }
    

    
    $sql="SELECT stockid,description,qty,price,tprice FROM bio_temppropitems WHERE leadid=".$lead;
//echo "$sql";
    $result=DB_query($sql,$db);
    $num_rows = $result->num_rows;
//echo "num rows= ".$num_rows;  

   $sql1="SELECT * FROM bio_conceptproposaldetails WHERE cp_id=".$cp_id;
    $result1=DB_query($sql1,$db); 
    $myrow1=DB_fetch_array($result1);
 
 if ($num_rows > 0) 
 {
    
    $sql2="SELECT SUM(tprice) AS totalprice FROM bio_temppropitems WHERE leadid=".$lead;
    $result2=DB_query($sql2,$db);
    $myrow2=DB_fetch_array($result2);
    $totalprice=$myrow2[0];
//echo "total price=".$totalprice;


if ($totalprice > 0) 
{
   $sql3="UPDATE bio_conceptproposal SET total_price=".$totalprice.", status=1
           WHERE cp_id=".$cp_id."
             AND lead_id=".$lead;
    $result3=DB_query($sql3,$db); 
    
    
    
     $sql_item_delt="DELETE FROM bio_conceptproposaldetails WHERE cp_id=".$cp_id;
     $result_item_delt=DB_query($sql_item_delt,$db); 
     $i=0; 
                
    while ($myrow=DB_fetch_array($result))   
    { 
           $i++; 
            
           $sql4="INSERT INTO bio_conceptproposaldetails (cp_id,
                                                          slno,
                                                          stockid,
                                                          description,
                                                          qty,
                                                          price,
                                                          tprice) 
                                                 VALUES (".$cp_id.",
                                                         ".$i.",
                                                        '".$myrow['stockid']."',
                                                        '".$myrow['description']."',
                                                         ".$myrow['qty'].",
                                                         ".$myrow['price'].",
                                                         ".$myrow['tprice'].")";
                                                      
      $result4=DB_query($sql4,$db);    
        
    }            
                
     
    
  /*  while ($myrow=DB_fetch_array($result))   {  --temp
        while ($myrow1=DB_fetch_array($result1))   {
             
            if($myrow['stockid']==$myrow1['stockid']){
                $i=$myrow1['slno'];
                $stock_id=$myrow1['stockid']; 
                
          echo      $sql_item_delt="DELETE FROM bio_conceptproposaldetails 
                                        WHERE cp_id=".$cp_id."
                                        AND stockid='$stock_id'";
                $result_item_delt=DB_query($sql_item_delt,$db);
                
                
      echo          $sql4="INSERT INTO bio_conceptproposaldetails (cp_id,
                                                        slno,
                                                        stockid,
                                                        description,
                                                        qty,
                                                        price,
                                                        tprice) 
                                              VALUES (".$cp_id.",
                                                      ".$i.",
                                                     '".$myrow['stockid']."',
                                                     '".$myrow['description']."',
                                                      ".$myrow['qty'].",
                                                      ".$myrow['price'].",
                                                      ".$myrow['tprice'].")";
                $result4=DB_query($sql4,$db);
                
               
            }else{
               $sql_no="SELECT MAX(slno) FROM bio_conceptproposaldetails
                         WHERE cp_id=".$cp_id;
                $result_no=DB_query($sql_no,$db);
                $myrow_no=DB_fetch_array($result_no);
                $i=$myrow_no[0]+1;
                
               $sql4="INSERT INTO bio_conceptproposaldetails (cp_id,
                                                        slno,
                                                        stockid,
                                                        description,
                                                        qty,
                                                        price,
                                                        tprice) 
                                              VALUES (".$cp_id.",
                                                      ".$i.",
                                                     '".$myrow['stockid']."',
                                                     '".$myrow['description']."',
                                                      ".$myrow['qty'].",
                                                      ".$myrow['price'].",
                                                      ".$myrow['tprice'].")";
                $result4=DB_query($sql4,$db); 
            }
        }
         
             
             
         
    }  */



  $sql_sch="SELECT * FROM bio_temppropsubsidy
            WHERE leadid=".$lead;
  $result_sch=DB_query($sql_sch,$db);
  $row_count=DB_num_rows($result_sch);
  
  if($row_count>0)
  {
    while($myrow_sch=DB_fetch_array($result_sch))
    {
        
        $sql_subsidy="INSERT INTO bio_cpsubsidy(cp_id,
                                                leadid,
                                                stockid,
                                                scheme,
                                                amount)
                        VALUES('".$cp_id."',
                               '".$myrow_sch['leadid']."',
                               '".$myrow_sch['stockid']."',
                               '".$myrow_sch['scheme']."',
                               '".$myrow_sch['amount']."')";
        $result_subsidy=DB_query($sql_subsidy,$db,$Errmsg);
        
    }  
  }
  
  $task_ID=10;
    $duedate="0000-00-00";
    $date1="0000-00-00";
    $status=0;
if($lastid==0 or $lastid=='')
{
$lastid=$_SESSION['cpid'];
} 
    
/*    $sql_appr="INSERT INTO bio_approval(taskid,
                                   leadid,
                                   submitted_user,
                                   approval_user,
                                   assigneddate,
                                   duedate,
                                   taskcompleteddate,
                                   taskcompletedstatus,
                                   proposal_no) 
                            VALUES ('".$task_ID."',
                                    '".$lead."',
                                    '".$userid."',
                                    '".$approval_by."',
                                    '".$cur_date."',
                                    '".$duedate."',
                                    '".$date1."',
                                    '".$status."',
                                    '".$lastid."')";
    $result_appr=DB_query($sql_appr,$db);  */
  
  
    
}
    
  if($result_subsidy)
  {
   $sql_del="DELETE FROM bio_temppropsubsidy";
//   $ErrMsg =  _('An error occurred while deleting temp proposal items');
   $result_del=DB_query($sql_del,$db); 
}

 if ($result3 & $result4) 
 {
     $sql6="DELETE FROM bio_temppropitems WHERE leadid=".$lead;
     $ErrMsg =  _('An error occurred while deleting temp proposal items');
     $result6=DB_query($sql6,$db,$Errmsg);
}   
    
}   

}  

if(isset($_GET['cpidcancel']))
{
    
 $leadidcancel=$_GET['leadidcancel'];  
 $cpidcancel=$_GET['cpidcancel']; 
 $date1="0000-00-00";
 
 
 $sql_cancel="UPDATE bio_conceptproposal SET status = '5' WHERE bio_conceptproposal.cp_id=".$cpidcancel;  
 $result_cancel=DB_query($sql_cancel,$db,$Errmsg);
 
 $sql5="UPDATE bio_leads SET leadstatus = '10' WHERE bio_leads.leadid=".$leadidcancel;
$ErrMsg =  _('An error occurred while updating lead status to leads data');     
$result5=DB_query($sql5,$db,$Errmsg);



$sql_flag="UPDATE bio_leadtask SET taskcompletedstatus = '0',
                                          taskcompleteddate='".$date1."' 
                   WHERE bio_leadtask.leadid=$leadidcancel 
                     AND bio_leadtask.taskid=3 
                     AND taskcompletedstatus=1";   
    DB_query($sql_flag,$db);
  
}

 
//======================================================================== 
function delleadfromtempprod($lead,$db) 
{
    $sql4="DELETE FROM bio_temppropitems where leadid=".$lead;
// echo $sql4;
    $result4=DB_query($sql4,$db);
    $sql_4="DELETE FROM bio_temppropsubsidy where leadid=".$lead;
// echo $sql4;
    $result_4=DB_query($sql_4,$db);
    }

function checkduplicateentries($stockid,$lead,$db)
 {
  $sql4="SELECT COUNT(*) FROM bio_temppropitems WHERE leadid=".$lead ." AND stockid='".$stockid."'";
  $result4=DB_query($sql4,$db);
  $myrow4=DB_fetch_array($result4);
  if ($myrow4[0]>0) 
  {
    return 1;
  }
  else 
  {
      return 0;
  };
 }

function findstockitemdetails($stockid,$db) 
{
  global $stockid;
  global $itemdesc;
  global $price;
    
    $sqlw="SELECT stockmaster.description as description,
                  stockcatproperties.label as label,
                  stockitemproperties.value as value 
             FROM stockmaster
       INNER JOIN stockcategory on stockmaster.categoryid=stockcategory.categoryid
  LEFT OUTER JOIN stockitemproperties on stockmaster.stockid=stockitemproperties.stockid
  LEFT OUTER JOIN stockcatproperties on stockcatproperties.stkcatpropid=stockitemproperties.stkcatpropid
           WHERE  stockmaster.stockid='$stockid'";
//   echo $sqlw;
   $ErrMsg =  _('There is a problem in retrieving item description of') . ' ' . $stockid  .  _('the error message returned by the SQL server was');
   $resultw=DB_query($sqlw,$db,$ErrMsg);
   $itemdesc='';
$a=0;
while ($myroww=DB_fetch_array($resultw))  
 {
  if ($a==0)
   {
       $itemdesc.=$myroww['description'].':</br> ';
        $a=1;
   }
 $itemdesc.=$myroww['label'].': '.$myroww['value'].'</br> ';
}

}


function addpropitem($stockid,$lead,$db) 
{
  global $stockid;
  global $itemdesc;
  global $price;
  global $lead;
 // global $item
         $_SESSION['leid']=$lead;
//echo $_SESSION['leid'];
         $price=GetPrice($stockid,$db);
         findstockitemdetails($stockid,$db);
         $subsidy=0;
         $netprice=0; 
         
$sql_des="SELECT longdescription FROM stockmaster
           WHERE stockid='".$stockid."'";
$result_des=DB_query($sql_des,$db);
$myrow_des=DB_fetch_array($result_des);
$longdes=$myrow_des['longdescription'];

$sql_sub="SELECT * FROM bio_subsidymaster
                  WHERE subsidy_plant_id='$stockid'";
$result_sub=DB_query($sql_sub,$db);
$subsidy_count=DB_num_rows($result_sub);

 

  

if($subsidy_count>0)
{
    while($myrow_sub=DB_fetch_array($result_sub))
  {
    $scheme=$myrow_sub['subsidy_scheme_id'];
    $amount=$myrow_sub['subsidy_amount'];
    $sql_sub1="INSERT INTO bio_temppropsubsidy(leadid,
                                              stockid,
                                              scheme,
                                              amount) 
                                       VALUES(".$lead.",
                                              '".$stockid."',
                                              '".$scheme."',
                                              '".$amount."')";
    $result_sub1=DB_query($sql_sub1,$db);
  }

 $sql_sub_amount="SELECT SUM(subsidy_amount) FROM bio_subsidymaster
                 WHERE subsidy_plant_id='$stockid'";
$result_sub_amount=DB_query($sql_sub_amount,$db);
 $myrow_sub_amount=DB_fetch_array($result_sub_amount);
$subsidy=$myrow_sub_amount[0];

echo $netprice=$price-$subsidy;
}
 else
 {
  $subsidy=0;
  $netprice=$price;  
}     
   
  $sql3="INSERT INTO bio_temppropitems (stockid,
                                      description,
                                      qty,
                                      price,
                                      tprice,
                                      leadid,
                                      subsidy,
                                      netprice) 
                              VALUES ('".$stockid."',
                                      '".$longdes."',
                                             1,
                                      ".number_format($price,2,'.','').",
                                      ".number_format($price,2,'.','').",
                                      ".$lead.",
                                      ".$subsidy.",
                                      ".number_format($netprice,2,'.','').")";  
                                      
$result3=DB_query($sql3,$db);

 $sql_opt=  "INSERT INTO   bio_temppropitems (stockid,
                                      description,
                                      qty,
                                      price,
                                      tprice,
                                      leadid,
                                      subsidy,
                                      netprice)
SELECT  c.opt_stockid,a.description,c.opt_qty,COALESCE(b.price,0),
COALESCE(b.price*c.opt_qty,0),".$lead.",0,COALESCE(b.price*c.opt_qty,0) 
FROM stockmaster a
LEFT JOIN prices b ON a.stockid=b.stockid
LEFT JOIN  bio_optionalitemdetails c ON a.stockid=c.opt_stockid
WHERE  c.prnt_stockid='".$stockid."'";  
$result_opt=DB_query($sql_opt,$db);

  $sql4="SELECT stockid,
               description,
               qty,                         
               price,
               tprice,
               subsidy,
               netprice 
          FROM bio_temppropitems 
         WHERE leadid=".$lead;
 $result4=DB_query($sql4,$db);
//$count=DB_fetch_row($result4);
$num_rows4 = $result4->num_rows; 
if ($num_rows4 > 0) 
{
  $_SESSION['plant_selected']=1; 
}
  else 
  {
      $_SESSION['plant_selected']=0;
  }

echo "<input type=\"hidden\" name=\"stock".$k."\" id=\"stock".$k."\" value='".$stockid."'>";
echo "<input type=\"hidden\" name=\"lead\" id=\"lead\" value='".$lead."'>";
echo "<table style='width:800px' border='1'>";
echo "<tr><th width=50>Stock Id</th>
          <th width=200>Item Description</th>
          <th>qty</th>
          <th>Unit Price</th>
          <th>Gross Price</th>
          <th>Subsidy</th>
          <th>Net Price</th>
          </tr><tbody>";
$k=0;


$budgetrem=$_SESSION['budgetinitial'];  

while ($myrow4=DB_fetch_array($result4))  
 {
       $tprice=$myrow4['qty']*$myrow4['price'];
       $k++;
       $stock[$k]=$myrow4['stockid'];
       $item=$myrow4['stockid'];
       $budgetrem-=$myrow4['netprice']; 
//$item=urlencode($myrow4['stockid']);
 echo "<tr><td>".$stock[$k]."</td><td>".$myrow4['description']."</td>
       <td><input size=3 style=\"text-align: right\" type=\"text\" name=\"qty".$k."\" id=\"qty".$k."\" value=".$myrow4['qty']." onchange=updatetotalitemprice(".$k.",".$lead.",'".$stock[$k]."')></td>
       <td class=number><input size=9 style=\"text-align: right\" type=\"text\" name=\"price".$k."\" id=\"price".$k."\" value=".$myrow4['price']." onchange=updatetotalitemprice(".$k.",".$lead.",'".$stock[$k]."')></td>
       <td class=number><input size=9 style=\"text-align: right\" type=\"text\" name=\"tprice".$k."\" id=\"tprice".$k."\" value=".$tprice." readonly onblur=updatebudgetrem(".$lead.");onchange=updatebudgetrem(".$lead.")></td>
       <td class=number><input size=9 style=\"text-align: right\" type=\"text\" name=\"subsidy".$k."\" id=\"subsidy".$k."\" value=".$myrow4['subsidy']." readonly></td>
       <td class=number><input size=9 style=\"text-align: right\" type=\"text\" name=\"netprice".$k."\" id=\"netprice".$k."\" value=".$myrow4['netprice']." readonly></td>
       <td align=center><a  style='cursor:pointer;'  id='$lead' onclick='addSubsidy($lead,\"$item\",0,1)'>" . _('Manage Subsidy') . "</a></td>
       <td align=center><a  style='cursor:pointer;'  id='$lead' onclick='removeitm($lead,\"$item\",3,1)'>" . _('Remove') . "</a></td>

       </tr>";
}
echo "<tr><td colspan=2>
      <input type='button' id=\"".$lead."\" value='Add Item'  onclick='selectplant(this.id,0);'>
       </td>";
echo "<tr><td colspan=4>
      Initail Budget : ".$_SESSION['budgetinitial']."
      </td>";

echo"<td colspan=4>
     Budget Remaining :<input size=9 style=\"text-align: right\" type=\"text\" name=\"budgetrem\" id=\"budgetrem\" value=".$budgetrem." readonly></td>
      </td>"; 

 echo "</tbody></table>";
 
}

function disppropitem($lead,$db) 
{   
    
//global $stockid;
//global $itemdesc;
//global $price;
global $lead;
$_SESSION['leid']=$lead;
//echo $_SESSION['leid'];
//$price=GetPrice($stockid,$db);
//findstockitemdetails($stockid,$db);

//echo "<form name=\"prop\" >";
// $sql3="INSERT INTO bio_temppropitems (stockid,description,qty,price,tprice,leadid) VALUES ('".$stockid."','".$itemdesc."',1,".number_format($price,2,'.','').",".number_format($price,2,'.','').",".$lead.")";
// $result3=DB_query($sql3,$db);

//$count=DB_fetch_row($result4);
//echo "<input type=\"hidden\" name=\"stock".$k."\" id=\"stock".$k."\" value='".$stockid."'>";
echo "<input type=\"hidden\" name=\"lead\" id=\"lead\" value='".$lead."'>";
echo "<table style='width:800px' border='1'>";
echo "<tr><th width=50>Stock Id</th>
          <th width=200>Item Description</th>
          <th>qty</th>
          <th>Unit Price</th>
          <th>Gross Price</th>
          <th>Subsidy</th>
          <th>Net Price</th></tr><tbody>";
$k=0;
$sql4="SELECT stockid,description,qty,price,tprice,subsidy,netprice FROM bio_temppropitems where leadid=".$lead;

$result4=DB_query($sql4,$db);
 $num_rows4 = $result4->num_rows;//edited

while ($myrow4=DB_fetch_array($result4))   
{
       $tprice=$myrow4['qty']*$myrow4['price'];
       $k++;
       $stock[$k]=$myrow4['stockid'];
       $item=$stock[$k];
       $budgetrem-=$myrow4['netprice'];
 
 echo "<tr><td>".$stock[$k]."</td><td>".$myrow4['description']."</td>
       <td><input size=3 style=\"text-align: right\" type=\"text\" name=\"qty".$k."\" id=\"qty".$k."\" value=".$myrow4['qty']." onchange=updatetotalitemprice(".$k.",".$lead.",'".$stock[$k]."')></td>
       <td class=number><input size=9 style=\"text-align: right\" type=\"text\" name=\"price".$k."\" id=\"price".$k."\" value=".$myrow4['price']." onchange=updatetotalitemprice(".$k.",".$lead.",'".$stock[$k]."')></td>
       <td class=number><input size=9 style=\"text-align: right\" type=\"text\" name=\"tprice".$k."\" id=\"tprice".$k."\" value=".$tprice."></td>
       <td class=number><input size=9 style=\"text-align: right\" type=\"text\" name=\"subsidy".$k."\" id=\"subsidy".$k."\" value=".$myrow4['subsidy']." readonly></td>
       <td class=number><input size=9 style=\"text-align: right\" type=\"text\" name=\"netprice".$k."\" id=\"netprice".$k."\" value=".$myrow4['netprice']." onblur=updatebudgetrem(".$lead.");onchange=updatebudgetrem(".$lead.")></td> 
       <td align=center><a  style='cursor:pointer;'  id='$lead' onclick='addSubsidy($lead,\"$item\",0,1)'>" . _('Manage Subsidy') . "</a></td>
       <td align=center><a  style='cursor:pointer;'  id='$lead' onclick='removeitm($lead,\"$item\",3,1)'>" . _('Remove') . "</a></td>


       </tr>";
}
echo "<tr><td colspan=2>
      <input type='button' id=\"".$lead."\" value='Add Item'  onclick='selectplant(this.id,0);'>
      </td>";
//echo "<td colspan=3>
//          <input type='button' id=\"".$lead."\" value='Save this proposal' onclick='saveproposal(this.id,$flag2);' >
//       </td></tr>";

echo "<tr><td colspan=4>
      Initail Budget : ".$_SESSION['budgetinitial']."
      </td>";
      
      if($_SESSION['sum']=="")
      {
            $budgetrem;
      }else
      {
         $budgetrem=$_SESSION['sum']; 
      }

echo"<td colspan=4>
     Budget Remaining :<input size=9 style=\"text-align: right\" type=\"text\" name=\"budgetrem\" id=\"budgetrem\" value=".$budgetrem." readonly></td>
      </td>"; 

 echo "</tbody></table>";
// echo "</form>";
}   


function addpropitem_edit($stockid,$lead,$cp_ID,$db) 
{
//    echo$pr_id;
global $stockid;
global $itemdesc;
global $price;
global $lead;
    $_SESSION['leid']=$lead;
//echo $_SESSION['leid'];
    $price=GetPrice($stockid,$db);
    findstockitemdetails($stockid,$db);
    $subsidy=0;
    $netprice=0;
 $sql_des="SELECT longdescription FROM stockmaster
          WHERE stockid='".$stockid."'";
 $result_des=DB_query($sql_des,$db);
 $myrow_des=DB_fetch_array($result_des);
 $longdes=$myrow_des['longdescription'];

$sql_sub="SELECT * FROM bio_subsidymaster
            WHERE subsidy_plant_id='$stockid'";
$result_sub=DB_query($sql_sub,$db);
$subsidy_count=DB_num_rows($result_sub);

if($subsidy_count>0){
    while($myrow_sub=DB_fetch_array($result_sub)){
    $scheme=$myrow_sub['subsidy_scheme_id'];
    $amount=$myrow_sub['subsidy_amount'];
    $sql_sub1="INSERT INTO bio_temppropsubsidy(leadid,
                                              stockid,
                                              scheme,
                                              amount) 
                                       VALUES(".$lead.",
                                              '".$stockid."',
                                              '".$scheme."',
                                              '".$amount."')";
    $result_sub1=DB_query($sql_sub1,$db);
}

$sql_sub_amount="SELECT SUM(subsidy_amount) FROM bio_subsidymaster
            WHERE subsidy_plant_id='$stockid'";
$result_sub_amount=DB_query($sql_sub_amount,$db);
$myrow_sub_amount=DB_fetch_array($result_sub_amount);
$subsidy=$myrow_sub_amount[0];
$netprice=$price-$subsidy;
}
else{
  $subsidy="";
  $netprice=$price;  
}

echo "<form name=\"prop\" >";
$sql3="INSERT INTO bio_temppropitems (stockid,description,qty,price,tprice,leadid,subsidy,netprice) VALUES ('".$stockid."','".$longdes."',1,".number_format($price,2,'.','').",".number_format($price,2,'.','').",".$lead.",'".$subsidy."',".$netprice.")";
$result3=DB_query($sql3,$db);
$sql4="SELECT stockid,description,qty,price,tprice,subsidy,netprice FROM bio_temppropitems where leadid=".$lead;
$result4=DB_query($sql4,$db);
$num_rows4 = $result4->num_rows;//edited
if ($num_rows4 > 0) {
  $_SESSION['plant_selected']=1; }
  else {$_SESSION['plant_selected']=0;}
//$count=DB_fetch_row($result4);
echo "<input type=\"hidden\" name=\"stock".$k."\" id=\"stock".$k."\" value='".$stockid."'>";
echo "<input type=\"hidden\" name=\"lead\" id=\"lead\" value='".$lead."'>";
echo "<table style='width:800px' border='1'>";
echo "<tr><th width=50>Stock Id</th><th width=200>Item Description</th><th>qty</th><th>Unit Price</th><th>Gross Price</th><th>Subsidy</th><th>Net Price</th></tr><tbody>";
$k=0;

while ($myrow4=DB_fetch_array($result4))   
{
    
  $tprice=$myrow4['qty']*$myrow4['price'];
$k++;
$stock[$k]=$myrow4['stockid'];
$item=$myrow4['stockid'];
$budgetrem-=$myrow4['netprice'];
//$item=urlencode($myrow4['stockid']);
   echo "<tr><td>".$stock[$k]."</td><td>".$myrow4['description']."</td>
       <td><input size=3 style=\"text-align: right\" type=\"text\" name=\"qty".$k."\" id=\"qty".$k."\" value=".$myrow4['qty']." onchange=updatetotalitemprice(".$k.",".$lead.",'".$stock[$k]."')></td>
       <td class=number><input size=9 style=\"text-align: right\" type=\"text\" name=\"price".$k."\" id=\"price".$k."\" value=".$myrow4['price']." onchange=updatetotalitemprice(".$k.",".$lead.",'".$stock[$k]."')></td>
       <td class=number><input size=9 style=\"text-align: right\" type=\"text\" name=\"tprice".$k."\" id=\"tprice".$k."\" value=".$tprice."></td>
       <td class=number><input size=9 style=\"text-align: right\" type=\"text\" name=\"subsidy".$k."\" id=\"subsidy".$k."\" value=".$myrow4['subsidy']." readonly></td>
       <td class=number><input size=9 style=\"text-align: right\" type=\"text\" name=\"netprice".$k."\" id=\"netprice".$k."\" value=".$myrow4['netprice']." onblur=updatebudgetrem(".$lead.");onchange=updatebudgetrem(".$lead.")></td> 
       <td align=center><a  style='cursor:pointer;'  id='$lead' onclick='addSubsidy($lead,\"$item\",0,1)'>" . _('Manage Subsidy') . "</a></td>
       <td align=center><a  style='cursor:pointer;'  id='$lead' onclick='removeitm($lead,\"$item\",4,1)'>" . _('Remove') . "</a></td>

       </tr>";
}
echo "<tr><td colspan=2>
          <input type='button' id=\"".$lead."\" value='Add Item1'  onclick='selectplant(this.id,2);'>
       </td>";
echo "<td colspan=3>
      <input type='submit' name='Editcp' id=\"".$lead."\"  value='Update'>   
       </td></tr>";
       
echo "<tr><td colspan=4>
      Initail Budget : ".$_SESSION['budgetinitial']."
      </td>";

echo"<td colspan=4>
     Budget Remaining : ".$budgetrem."
      </td>";        

 echo "</tbody></table>";
 echo "</form>";
}


function disppropitem_edit($lead,$cp_ID,$db) {
//global $stockid;
//global $itemdesc;
//global $price;
global $lead;
$_SESSION['leid']=$lead;
//echo $_SESSION['leid'];
//$price=GetPrice($stockid,$db);
//findstockitemdetails($stockid,$db);

echo "<form name=\"prop\" >";
// $sql3="INSERT INTO bio_temppropitems (stockid,description,qty,price,tprice,leadid) VALUES ('".$stockid."','".$itemdesc."',1,".number_format($price,2,'.','').",".number_format($price,2,'.','').",".$lead.")";
// $result3=DB_query($sql3,$db);
$sql4="SELECT stockid,description,qty,price,tprice,subsidy,netprice FROM bio_temppropitems where leadid=".$lead;
$result4=DB_query($sql4,$db);
$num_rows4 = $result4->num_rows;//edited
if ($num_rows4 > 0) 
{
  $_SESSION['plant_selected']=1; 
}
  else 
  {
      $_SESSION['plant_selected']=0;
  }
//$count=DB_fetch_row($result4);
//echo "<input type=\"hidden\" name=\"stock".$k."\" id=\"stock".$k."\" value='".$stockid."'>";
echo "<input type=\"hidden\" name=\"lead\" id=\"lead\" value='".$lead."'>";
echo "<table style='width:800px' border='1'>";
echo "<tr><th width=50>Stock Id</th><th width=200>Item Description</th><th>qty</th><th>Unit Price</th><th>Gross Price</th><th>Subsidy</th><th>Net Price</th></tr><tbody>";
$k=0;

while ($myrow4=DB_fetch_array($result4))   
{
  $tprice=$myrow4['qty']*$myrow4['price'];
$k++;
$stock[$k]=$myrow4['stockid'];
$item=$stock[$k];
$budgetrem-=$myrow4['netprice'];  
   echo "<tr><td>".$stock[$k]."</td><td>".$myrow4['description']."</td>
       <td><input size=3 style=\"text-align: right\" type=\"text\" name=\"qty".$k."\" id=\"qty".$k."\" value=".$myrow4['qty']." onchange=updatetotalitemprice(".$k.",".$lead.",'".$stock[$k]."')></td>
       <td class=number><input size=9 style=\"text-align: right\" type=\"text\" name=\"price".$k."\" id=\"price".$k."\" value=".$myrow4['price']." onchange=updatetotalitemprice(".$k.",".$lead.",'".$stockid."')></td>
       <td class=number><input size=9 style=\"text-align: right\" type=\"text\" name=\"tprice".$k."\" id=\"tprice".$k."\" value=".$tprice."></td>
       <td class=number><input size=9 style=\"text-align: right\" type=\"text\" name=\"subsidy".$k."\" id=\"subsidy".$k."\" value=".$myrow4['subsidy']." readonly></td>
       <td class=number><input size=9 style=\"text-align: right\" type=\"text\" name=\"netprice".$k."\" id=\"netprice".$k."\" value=".$myrow4['netprice']." onblur=updatebudgetrem(".$lead.");onchange=updatebudgetrem(".$lead.")></td> 
       <td align=center><a  style='cursor:pointer;'  id='$lead' onclick='addSubsidy($lead,\"$item\",0,1)'>" . _('Manage Subsidy') . "</a></td>
       <td align=center><a  style='cursor:pointer;'  id='$lead' onclick='removeitm($lead,\"$item\",4,1)'>" . _('Remove') . "</a></td>


       </tr>";
}
echo "<tr><td colspan=2>
          <input type='button' id=\"".$lead."\" value='Add Item2'  onclick='selectplant(this.id,2);'>
       </td>";
echo "<td colspan=3>
      <input type='submit' name='Editcp' id=\"".$lead."\"  value='Update'>      
       </td></tr>";
       
echo "<tr><td colspan=4>
      Initail Budget : ".$_SESSION['budgetinitial']."
      </td>";

echo"<td colspan=4>
     Budget Remaining : ".$budgetrem."
      </td>";        

 echo "</tbody></table>";
 echo "</form>";
}
    
   

echo"<div id=fullbody>";
echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "?" . SID . "' onsubmit='return checkSubmit();'>";  //edited   
echo '<table style="width:70%"><tr><td>'; 
echo"<div id=fullpanel>";

echo"<div id=foredit>";

echo"<div id=panel>";
echo '<table><tr>';    
echo'<td>';


echo"<fieldset style='width:820px;height:200px'><legend>Customer Details</legend>";
echo"<table width=100%>";

if($_GET['lead']!='')
{
   $leadid=$_GET['lead']; 
   
$sql_fsapproval="SELECT status FROM bio_fsproposal WHERE leadid=$leadid";
$result_fsapproval=DB_query($sql_fsapproval,$db);
$row_fsapproval=DB_fetch_array($result_fsapproval);

if($row_fsapproval['status']==1)
{
        ?>  
            <script>
            
            alert("Feasibility Proposal not approved");
            window.close();
           
            </script>
       <?php 
}
$sql="SELECT
    `bio_leads`.`leadid`
    , `bio_cust`.`custname`
    , `bio_cust`.`contactperson`
    , `bio_cust`.`custphone`
    , `bio_cust`.`custmob`
    , `bio_cust`.`area1`
    , `bio_district`.`district`
    , `bio_outputtypes`.`outputtype`
    , `bio_leadteams`.`teamname`
    , `bio_cust`.`custmail`
    , `bio_cust`.`cust_id`
    , `bio_cust`.`houseno`
    , `bio_cust`.`housename`
    , `bio_cust`.`pin`
    , `bio_fs_entrydetails`.`teamid`
    , `bio_fs_entrydetails`.`budget`
FROM
    `bio_leads`
    LEFT JOIN `bio_cust` 
        ON (`bio_leads`.`cust_id` = `bio_cust`.`cust_id`)
    LEFT JOIN `bio_outputtypes` 
        ON (`bio_leads`.`outputtypeid` = `bio_outputtypes`.`outputtypeid`)
    LEFT JOIN `bio_fs_entrydetails` 
        ON (`bio_leads`.`leadid` = `bio_fs_entrydetails`.`leadid`)
    LEFT JOIN `bio_district` 
        ON (`bio_cust`.`nationality` = `bio_district`.`cid`) AND (`bio_cust`.`state` = `bio_district`.`stateid`) AND (`bio_cust`.`district` = `bio_district`.`did`)
    LEFT JOIN `bio_leadteams` 
        ON (`bio_fs_entrydetails`.`teamid` = `bio_leadteams`.`teamid`)
        WHERE bio_leads.leadid=".$leadid;
              
 $result=DB_query($sql,$db);
  $myrow=DB_fetch_array($result); 
//print_r($count);
 $no=0; 
 $k=0; 
 
 $mob=$myrow['custmob'];
 $cname=$myrow['custname'];  
 if($myrow['custphone']!='-')
 {
     $cph=$myrow['custphone']; 
 }
 else
 {
     $cph=$myrow['custmob']; 
 }
 
 $place=$myrow['area1'];
 $dist=$myrow['district'];
 $ste=$myrow['state'];
 $hno=$myrow['houseno'];
 $hname=$myrow['housename'];
 $contactperson=$myrow['contactperson']; 
 $district=$myrow['district']; 
 $pin=$myrow['pin'];
 $mail=$myrow['custmail'];  
 $cust_ID=$myrow['cust_id'];
 $ctry=$myrow['nationality']; 
 $asgnteam_id=$myrow['teamid'];
 $asgnteam=$myrow['teamname'];
 $budget=$myrow['budget'];
    $district=$myrow['district'];

    
$budgetinitial=$myrow['budget'];   

if(!isset($_SESSION['budgetinitial']))    
  {

$_SESSION['budgetinitial']= $budgetinitial;   
    
}



if(isset($_SESSION['budgetinitial']))     
 {  
    
$budgetinitial= $_SESSION['budgetinitial'];   
    
    
}
 
     /*
 $sql1="SELECT bio_district.district 
          FROM bio_district
          WHERE bio_district.stateid=".$ste."  
          AND bio_district.cid=".$ctry;     
 $result1=DB_query($sql1,$db);
 $myrow=DB_fetch_array($result1);
 $district=$myrow[0];   */
    
    
 $sql_fsedit="SELECT * FROM bio_fs_entrydetails
                        WHERE leadid=".$leadid;
$result_fsedit=DB_query($sql_fsedit,$db);
$myrow_fsedit=DB_fetch_array($result_fsedit);
$fsentr_ID=$myrow_fsedit['fsentry_id'];   
    
    
}


echo"<div id=cus_details>"; 
echo"<tr><td width=50%>Organisation Name</td>";
echo"<td><input type='text' name='Custname' id='custname' value='$cname' style=width:175px></td></tr>";

echo"<tr></tr>";
echo"<tr><td width=50%>Contact Person</td>";
echo"<td><input type='text' name='Contactperson' id='contactperson' value='$contactperson' style=width:175px></td></tr>"; 

echo"<tr></tr>";
echo"<tr><td width=50%>Mobile Number</td>";
echo"<td><input type='text' name='mobile' id='custmob' value='$mob' style=width:175px></td></tr>";

echo"<tr></tr>";
echo"<tr><td>Phone Number</td>"; 
echo"<td><input type='text' name='phone' id='custph' value='$cph' style=width:175px></td></tr>"; 

echo"<tr><td width=50%>Expected Date</td>";
echo'<td><input type="text" name="expectdate" required id="expectdate"  style=width:175px class=date alt='.$_SESSION['DefaultDateFormat'].'  onChange="return isDate(this, this.value, '."'".$_SESSION['DefaultDateFormat']."'".')'.'"'.'></td></tr>';

echo"<tr><td width=50%>Budget Amount</td>";  
echo "<td><input type=text readonly name='Budget' id='budget' value='$budget' style=width:175px></td></tr>"; 
     
echo"</div>";    

echo"<tr><td width=50%>Plant</td>";
echo "<td id='plantname'><input type='hidden' name='Plantid' id='plantid'>
      <a onclick='selectplant($leadid,1,$budget)'>Select</a></td></tr>";
      
echo"<tr><td width=50%>Do you want to add Tender Details</td>";
echo "<td><select name='tenderstatus' id='tenderstatus'>
         <option value=0></option>;
         <option value=1>Yes</option>;
         <option value=2>No</option>;
         </select></td></tr>";      
      

echo"</table>";
echo"</td></tr";
echo"</fieldset>"; 
echo"</div>";


if($fsentr_ID!="")
{

echo"<tr><td>";
echo"<div>";
echo "<fieldset style='width:785px'>";   
echo "<legend><h3>Feasibility Study Details</h3>";
echo "</legend>";



echo"<table  style='width:85%;' border=0>";
echo"<tr style='background:#D50000;color:white'>";
echo"<td>Slno</td>
     <td>Feed Stock Source</td>
     <td>Feed Stock</td>
     <td>Weight/Ltr</td></tr>";
 $n=1;
 $sql_fstock="SELECT bio_feedstocks.feedstocks,
                     bio_fs_feedstockdetails.gasout,
                     bio_fs_feedstockdetails.weight,
                     bio_fssources.source
                FROM bio_fs_feedstockdetails,
                     bio_feedstocks,
                     bio_fssources
               WHERE bio_feedstocks.id=bio_fs_feedstockdetails.feedstockid
                 AND bio_fs_feedstockdetails.feedstocksourceid=bio_fssources.id
                 AND bio_fs_feedstockdetails.leadid=".$leadid;
                 
 $result_fstock=DB_query($sql_fstock, $db);    
 while($myrow=DB_fetch_array($result_fstock))
 {
    echo "<tr style='background:#000080;color:white'>
        <td>$n</td>
        <td>$myrow[3]<input type='hidden' id='hfeedstock' value='$myrow[3]'></td>
        <td>$myrow[0]<input type='hidden' id='hfeedsource' value='$myrow[0]'></td>
        <td>$myrow[2]<input type='hidden' id='hfeedweight' value='$myrow[2]'></td>
        
        ";
 $n++; 
  //<td><a style='cursor:pointer;color:white;' id='$myrow[1]' onclick='editfeedstok(this.id)'>Edit</a ></td>
//        <td><a style='cursor:pointer;color:white' id='$myrow[1]' onclick='deletfeedstok(this.id)'>Delete</a></td></tr>
}

echo"</table>";   
echo "<table  style='width:65%;'><tr style='background:#000080;color:white' id='editfeed'></tr></table>";    

echo"<table  style='width:90%;' border=0>";
echo"<tr><td style='width:45%;'>";
echo"<table border=0 style='width:90%;'>";
echo"<tr><td>Easily Degradable</td>";
echo"<td>:<input type='hidden' name='edegradable' id='edegradable' value='".$myrow_fsedit['edegradable']."'>".$myrow_fsedit['edegradable']." Kg</td></tr>";
echo"<tr></tr>";

echo"<tr><td>Slow Degradable</td>";
echo"<td>:<input type='hidden' name='sdegradable' id='sedegradable' value='".$myrow_fsedit['sdegradable']."'>".$myrow_fsedit['sdegradable']." Kg</td></tr>";
echo"<tr></tr>";

echo"<tr><td>Very Slow Degradable</td>";
echo"<td>:<input type='hidden' name='vsdegradable' id='vsedegradable' value='".$myrow_fsedit['vsdegradable']."'>".$myrow_fsedit['vsdegradable']." Kg</td></tr>";
echo"<tr></tr>";

echo"<tr><td>Non Degradable</td>";
echo"<td>:<input type='hidden' name='nondegradable' id='nonedegradable' value='".$myrow_fsedit['ndegradable']."'>".$myrow_fsedit['ndegradable']." Kg</td></tr>";
echo"<tr></tr>";



$myrow_fsedit['actual_feedstock']= $myrow_fsedit['edegradable'] + $myrow_fsedit['sdegradable'] + $myrow_fsedit['vsdegradable'] + $myrow_fsedit['ndegradable'];

echo"<tr><td>Total Feedstocks</td>";
echo"<td>:<input type='hidden' name='actual' id='actual' value='".$myrow_fsedit['actual_feedstock']."'>".$myrow_fsedit['actual_feedstock']." Kg</td></tr>";
echo"<tr></tr>";

echo"<tr><td>Organic waste water</td>";
echo"<td>:<input type='hidden' name='orgwastewater' id='orgwastewater' value='".$myrow_fsedit['liquid_waste']."'>".$myrow_fsedit['liquid_waste']." Ltr</td></tr>";
echo"<tr></tr>";

echo"<tr><td>Total Gas</td>";
echo"<td>:<input type='hidden' name='TotalGas' id='totalgas' value='".$myrow_fsedit['total_gas']."'>".$myrow_fsedit['total_gas']."</td></tr>";
echo"</table>";
echo"</fieldset>";
echo"</div>";
echo"</td></tr>"; 
} 
  
      
//=================================================plant select

echo"<table>";
if($_GET['stockid'])
{
    $stockid=$_GET['stockid'];
if ($_GET['lead']) {
  $lead=$_GET['lead'];
}
if ($stockid) {
    echo '<script type="JavaScript">$("#grid").hide();</script>';
}
echo "<table width=700px>";
echo "<tr><td colspan='2'>";
echo "<div id='sellist'>";
echo "<div id='messageBox'>&nbsp;</div>";
if ($_GET['first']==1) {
  delleadfromtempprod($lead,$db);
  addpropitem($stockid,$lead,$db);
}elseif($_GET['first']==2){
    if($_SESSION['cpid']!=''){
        $pr_id=$_SESSION['cpid'];
    }else{
        $pr_id=0;
    }

  if (checkduplicateentries($stockid,$lead,$db)==0) {
    addpropitem_edit($stockid,$lead,$pr_id,$db);
}  else {
  echo "Item is already selected.";  
  disppropitem_edit($lead,$pr_id,$db);
}
    
}elseif($_GET['first']==3){
  disppropitem($lead,$db);  
    
}
elseif($_GET['first']==4){
  if($_SESSION['cpid']!=''){
        $pr_id=$_SESSION['cpid'];
    }else{
        $pr_id=0;
    }  
  disppropitem_edit($lead,$pr_id,$db);  
    
}
 else {
if (checkduplicateentries($stockid,$lead,$db)==0) {
    addpropitem($stockid,$lead,$db);
}  else {
  echo "Item is already selected.";
disppropitem($lead,$db);
}
}
echo "</div>";
echo "</td></tr>";
    
}


echo"<input type='hidden' name='LeadID' id='leadid' value='$lead'>"; 
echo"<input type='hidden' name='FSEntryID' id='fsentryid' value='$fsentr_ID'>"; 


echo'<tr><td>';
echo'<div id="plant_select"></div>';
echo"</td></tr></table>";
echo"</div>";  
//=================================================




//=======================================================

if($_GET['first']!=2)
{
  if($_GET['first']!=4)
  {  
echo"<table>";
echo '<tr><td colspan=3><center>';        
echo '<div id="show_sub_categories" align="center">';
//echo '<img src="loader.gif" style="margin-top:8px; float:left" id="loader" alt="" />';
echo '</div>';  
echo '<input type=submit name="submit" id="leads" value=Submit onclick="if(expect_date()==1)return false;">';//edited
echo '<input name="clear" type="submit" value=Clear ><input id="shwprint" type="button" name="shwprint" value="view/hide proposals" >';     
echo '</center></td>';    
echo '</tr>';    
echo '</table>';
  }
}
echo'</form>';
echo "<div class='center'  align='middle'>";//edited
echo "<form name=clear metho='post'>";
echo '<input name="clear1" id="clear1" type="submit" value=Clear >';
echo "</form>";
echo "</div>";
echo'</div>';


 
//======================================================== lead details grid

echo'<div id="leadgrid">';
echo"<fieldset style='float:center;width:820px;'><legend>Lead Details</legend>"; 
echo "<form name=filter method='post'>";
echo"<table style='border:1px solid #F0F0F0;width:100%'>";


echo"<tr><td>Date From</td><td>Date To</td><td>Name</td><td>Place</td><td>Office</td><td>Lead Source</td></tr>";
echo"<tr>";
echo '<td><input type="text" style="width:100px" id="df1" class=date alt='.$_SESSION['DefaultDateFormat'].' name="df1" onChange="return isDate(this, this.value, '."'".$_SESSION['DefaultDateFormat']."'".')'.'"'.'></td>';  
echo '<td><input type="text" style="width:100px" id="dt1" class=date alt='.$_SESSION['DefaultDateFormat'].' name="dt1" onChange="return isDate(this, this.value, '."'".$_SESSION['DefaultDateFormat']."'".')'.'"'.'></td>';
echo"<td><input type='text' name='byname1' id='byname1'></td>";
echo"<td><input type='text' name='byplace1' id='byplace1'></td>";
echo '<td><select name="off1" id="off1" style="width:100px">';
echo '<option value=0></option>'; 


$sql1="select * from bio_office";
$result1=DB_query($sql1,$db);
while($row1=DB_fetch_array($result1))
{
  echo "<option value=$row1[id]>$row1[office]</option>";
}
echo '</select></td>';
echo '<td><select name="leadsrc1" id="leadsrc1" style="width:100px">';
echo '<option value=0></option>';
echo '<option value="ALL">Select ALL</option>';
$sql1="select * from bio_leadsources";
$result1=DB_query($sql1,$db);
while($row1=DB_fetch_array($result1))
{
  echo "<option value=$row1[id]>$row1[sourcename]</option>";
}                                                             
echo '</select></td>';      

echo"<td><input type='submit' name='filterbut1' id='filterbut1' value='search'></td>";
echo"</tr>";
echo"</table>"; 

echo "<div style='height:200px; width:100%; overflow:scroll;'>";
echo"<table style='width:100%' border=0> ";   
echo"<tr><th>Slno</th><th>Name</th><th>Date</th><th>Output</th><th>Cust type</th><th>Team</th></tr>";
echo '<tbody>';

$office=$_SESSION['UserStockLocation']; 
$empid=$_SESSION['empid'];
 $employee_arr=array();   
     $sql_drop="DROP TABLE `emptable`";
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
                      
                      $sql_insert1="INSERT INTO emptable (empid) VALUES ($_SESSION[empid])";
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
   
    $team=array();  
    $sql6="SELECT teamid FROM bio_teammembers WHERE empid IN ($employee_arr)";
    $result6=DB_query($sql6,$db);
   while($row6=DB_fetch_array($result6))
   {
        $team[]=$row6['teamid'];
   }
        $team_array=join(",", $team);
        
        
    $sql_usr="SELECT userid FROM www_users WHERE empid IN ($employee_arr)";
     $result_usr=DB_query($sql_usr,$db);
     while($row_usr=DB_fetch_array($result_usr))
     {
    
    $userid[]="'".$row_usr['userid']."'";     
    
     }
     $user_array=join(",", $userid);    
        
        


  $sql="SELECT bio_cust.cust_id AS custid,  
               bio_cust.custname AS custname,               
               bio_cust.area1 AS place,
               bio_enquirytypes.enquirytype AS enqtype,
               bio_outputtypes.outputtype AS outputtype,
               bio_leadtask.leadid AS leadid, 
               bio_leads.leaddate AS leaddate,
               bio_leadteams.teamname AS teamname,
               bio_leadsources.sourcename AS sourcename,
               bio_office.id AS officeid,
               bio_office.office AS office,
               bio_fsproposal.status
          FROM bio_cust,
               bio_leads,
               bio_leadteams,
               bio_leadsources,
               bio_enquirytypes,
               bio_office,
               bio_outputtypes,
               bio_leadtask,
               bio_fs_entrydetails,
               bio_fsproposal   
         WHERE bio_cust.cust_id=bio_leads.cust_id 
           AND bio_leads.enqtypeid=bio_enquirytypes.enqtypeid
           AND bio_leads.enqtypeid=2
           AND bio_leads.outputtypeid=bio_outputtypes.outputtypeid  
           AND bio_leadsources.id=bio_leads.sourceid  
           AND bio_office.id=bio_leadsources.officeid
           AND bio_leads.leadstatus=10  
           AND bio_fs_entrydetails.leadid=bio_leads.leadid  
           AND bio_fs_entrydetails.leadid=bio_fsproposal.leadid 
           AND bio_leadtask.taskid=3
           AND bio_leadtask.leadid=bio_leads.leadid 
           AND bio_leadtask.teamid IN ($team_array)
           AND bio_leadtask.taskcompletedstatus=0
           AND bio_leadtask.viewstatus=1
           AND bio_leadteams.teamid=bio_leadtask.teamid
           ";   

 //echo $sql5;
 if(isset($_POST['filterbut1']))
 {  
    if ((isset($_POST['df1'])) && (isset($_POST['dt1'])))   {
    if (($_POST['df1']!="") && ($_POST['dt1']!=""))  { 
    $sourcetypefrom=FormatDateForSQL($_POST['df1']);   
    $sourcetypeto=FormatDateForSQL($_POST['dt1']);
    $sql.=" AND bio_leads.leaddate BETWEEN '".$sourcetypefrom."' AND '".$sourcetypeto."'";
    }  }
    $officeid=$_POST['off1'];
  //  echo $officeid;
    if (isset($_POST['byname1']))  {        
    if ($_POST['byname1']!='')   
    $sql .= " AND bio_cust.custname LIKE '%".$_POST['byname1']."%'";   
    }                                                                    
            
    if (isset($_POST['byplace1'])) {
    if ($_POST['byplace1']!='') 
    $sql .= " AND bio_cust.area1 LIKE '%".$_POST['byplace1']."%'"; 
    }
    
    if (isset($_POST['off1']))    {
    if (($_POST['off1']!='')&&($_POST['off1']!='0'))
    $sql .=" AND bio_leadsources.officeid=".$officeid;    
    }
    
    if (isset($_POST['leadsrc1'])) {
    if (($_POST['leadsrc1']!='ALL') && ($_POST['leadsrc1']!=0))
    $sql .=" AND bio_leads.sourceid='".$_POST['leadsrc1']."'";
    }
 }      
  
$result=DB_query($sql,$db);
 $no=0; 

          $k=0; 
          while($myrow=DB_fetch_array($result))
          {    //echo $myrow[0];   
           
          $no++;
               if ($k==1)
                {
                    echo '<tr class="EvenTableRows">';
                    $k=0;
                    
                }
                 else 
                 {
                    echo '<tr class="OddTableRows">';
//                    $k=1;     
                 }
                    
                    
                    $leadid=$myrow['leadid'];
                    $status=$myrow['status'];
                    

                   
                    
                    
printf("<td cellpading=2>%s</td>
        <td>%s</td>
        <td>%s</td>
        <td>%s</td>
        <td>%s</td>
        <td>%s</td>

         
        <td><a  style='cursor:pointer;'  id='$leadid' onclick='passid(this.id)'>" . _('Select') . "</a></td>  
        </tr>",
        
        $no,
        $myrow['custname'],
        ConvertSQLDate($myrow['leaddate']),
        $myrow['outputtype'],
        $myrow['enqtype'],
        $myrow['teamname']);
        }



echo"</tr></tbody></table>";
//echo"</div>";
echo"</fieldset>";  
echo'</div>'; 
//========================================================= proposal grid
echo'<div id="cpgrid">';
echo"<fieldset style='float:middle;width:820px;'><legend>Proposal Details</legend>";  
 
echo"<table style='border:1px solid #F0F0F0;width:100%'>"; 

echo"<tr><td>Date From</td><td>Date To</td><td>Name</td><td>Place</td><td>Office</td><td>Lead Source</td></tr>"; 

echo"<tr>";
echo '<td><input type="text" style="width:100px" id="df2" class=date alt='.$_SESSION['DefaultDateFormat'].' name="df2" onChange="return isDate(this, this.value, '."'".$_SESSION['DefaultDateFormat']."'".')'.'"'.'></td>';  
echo '<td><input type="text" style="width:100px" id="dt2" class=date alt='.$_SESSION['DefaultDateFormat'].' name="dt2" onChange="return isDate(this, this.value, '."'".$_SESSION['DefaultDateFormat']."'".')'.'"'.'></td>';

echo"<td><input type='text' name='byname2' id='byname2'></td>";
echo"<td><input type='text' name='byplace2' id='byplace2'></td>";
echo '<td><select name="off2" id="off2" style="width:100px">';
echo '<option value=0></option>';

    $sql1="select * from bio_office";
    $result1=DB_query($sql1,$db);
    while($row1=DB_fetch_array($result1))
    {
  echo "<option value=$row1[id]>$row1[office]</option>";
  }
echo '</select></td>';
echo '<td><select name="leadsrc2" id="leadsrc2" style="width:100px">';
echo '<option value=0></option>';
echo '<option value="ALL">Select ALL</option>';

    $sql1="select * from bio_leadsources";
    $result1=DB_query($sql1,$db);
    while($row1=DB_fetch_array($result1))
    {
  echo "<option value=$row1[id]>$row1[sourcename]</option>";
  }                                                             
echo '</select></td>';      

echo"<td><input type='submit' name='filterbut2' id='filterbut2' value='search'></td>";
echo"</tr>";
echo"</table>";

echo "<div style='height:200px; width:100%; overflow:scroll;'>"; 
echo"<table style='width:100%' border=0> ";   
echo"<tr><th>Slno</th>
         <th>Name</th>
         <th>Created On</th>
         <th>Total Price</th>
         <th>Status</th></tr>";
         
$sql8="SELECT bio_conceptproposal.cp_id AS conceptproposalid,
              bio_conceptproposal.fsentry_id,
              bio_conceptproposal.total_price AS total_price,
              bio_conceptproposal.created_on AS proposaldate,
              bio_leads.cust_id,
              bio_cust.custname AS custname,
              bio_cust.area1 AS place,
              bio_leads.leaddate AS leaddate,
              bio_office.id AS officeid,
              bio_office.office AS office,
              bio_leadsources.sourcename AS sourcename,
              bio_proposal_status.status,
              bio_conceptproposal.status as cp_status,
              bio_conceptproposal.lead_id
         FROM bio_conceptproposal,
              bio_leads,
              bio_office,
              bio_leadsources,
              bio_cust,
              bio_proposal_status 
        WHERE bio_conceptproposal.lead_id=bio_leads.leadid
          AND bio_office.id=bio_leadsources.officeid 
          AND bio_leads.cust_id=bio_cust.cust_id
          AND bio_leadsources.id=bio_leads.sourceid
          AND bio_proposal_status.statusid=bio_conceptproposal.status
          AND bio_conceptproposal.status!=5 
          AND bio_leads.leadstatus!=20
          AND bio_conceptproposal.created_by IN ($user_array)";      
       

if(isset($_POST['filterbut2']))
{  
    if ((isset($_POST['df2'])) && (isset($_POST['dt2'])))   
    {
    if (($_POST['df2']!="") && ($_POST['dt2']!="")) 
     { 
    $sourcetypefrom=FormatDateForSQL($_POST['df2']);   
    $sourcetypeto=FormatDateForSQL($_POST['dt2']);
    $sql8.=" AND bio_conceptproposal.createdon BETWEEN '".$sourcetypefrom."' AND '".$sourcetypeto."'";
    } 
    }
    $officeid=$_POST['off2'];
    echo $officeid;
    if (isset($_POST['byname2'])) 
     {        
    if ($_POST['byname2']!='')   
    $sql8 .= " AND bio_cust.custname LIKE '%".$_POST['byname2']."%'";   
    }                                                                    
            
    if (isset($_POST['byplace2'])) 
    {
    if ($_POST['byplace2']!='') 
    $sql8 .= " AND bio_cust.area1 LIKE '%".$_POST['byplace2']."%'"; 
    }
    
    if (isset($_POST['off2']))   
     {
    if (($_POST['off2']!='')&&($_POST['off2']!='0'))
    $sql8.=" AND bio_leadsources.officeid=".$officeid;    
    }
    
    if (isset($_POST['leadsrc2'])) 
    {
    if (($_POST['leadsrc2']!='ALL') && ($_POST['leadsrc2']!=0))
    $sql8.=" AND bio_leads.sourceid='".$_POST['leadsrc2']."'";
    }
 }   
 
 $result8=DB_query($sql8,$db);
 $k=0;
 $no=0;

 
while($myrow8=DB_fetch_array($result8))   
  {
    
 $cpid=$myrow8['conceptproposalid'];
 $leadid=$myrow8['lead_id'];    
 $plantid=$myrow8['plant'];
 $plantid2=explode(',',$plantid);
 $n=sizeof($plantid2);
 $plants="";

for($i=0;$i<$n;$i++)       
 {

$sql="SELECT description
      FROM stockmaster
      WHERE stockid='$plantid2[$i]'";
$result=DB_query($sql,$db);
$myrow=DB_fetch_array($result);
$plants=$myrow[0].",".$plants;

} 

 

 $sql_reject="SELECT reason FROM bio_rejectedproposal WHERE leadid=$leadid"; 
 $result_reject=DB_query($sql_reject,$db);
 $row_reject=DB_fetch_array($result_reject);
 $reject=$row_reject['reason'];
 
 $rejectedreason="Rejected reason:".$reject;

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
           
           echo"<td cellpading=2>".$no."</td> 
                <td>".$myrow8['custname']."</td> 
                <td>".ConvertSQLDate($myrow8['proposaldate'])."</td>  
                <td>".$myrow8['total_price']."</td>";
   if($myrow8['status']=="Rejected by Biotech"){             
           //echo"<td>".$myrow8['status']."</td>";
           echo"<td><a title='$rejectedreason' href='' id='trigger'>".$myrow8['status']."</a></td>";
   }else{
         echo"<td>".$myrow8['status']."</td>";
   }
    if($myrow8['cp_status']==1)
    {     
        echo"<td></td>
             <td><a  style='cursor:pointer;'  onclick=CpEdit('$cpid','$leadid')>" . _('Edit') . "</a></td>
             <td><a  style='cursor:pointer;'  onclick=Newcp('$cpid','$leadid')>" . _('New CP') . "</a></td>
             <td><a  style='cursor:pointer;'  onclick=Cpcancel('$cpid','$leadid')>" . _('Cancel') . "</a></td> </tr>";
   }
   elseif($myrow8['cp_status']==2)
   {
       echo"<td></td>
             <td><a  style='cursor:pointer;'  onclick=CpEdit('$cpid','$leadid')>" . _('Select') . "</a></td>
             <td><a  style='cursor:pointer;'  onclick=Cpcancel('$cpid','$leadid')>" . _('Cancel') . "</a></td></tr>";
   }
   elseif($myrow8['cp_status']==3)
   {
       echo"<td></td>             <td></td>
             <td><a  style='cursor:pointer;'  onclick=Newcp('$cpid','$leadid')>" . _('New CP') . "</a></td></tr>";
   }
   elseif($myrow8['cp_status']==4 || $myrow8['cp_status']==6)
   {
       echo"<td><a  style='cursor:pointer;'  onclick=CPPrint('$cpid','$leadid')>" . _('Print') . "</a></td>
            <td></td>
            <td><a  style='cursor:pointer;'  onclick=Newcp('$cpid','$leadid')>" . _('New CP') . "</a></td>
            <td><a  style='cursor:pointer;'  onclick=Cpcancel('$cpid','$leadid')>" . _('Cancel') . "</a></td></tr>"; 
   }

}

echo '<tbody>';    
echo"</tr></tbody></table>";
                                                       


echo"</div>";
echo"</fieldset>";  
echo'</div>';

echo"</td></tr></table>";
echo'</form>';
echo"</div>";
echo"</div>"; 


  if(isset($_GET['cpid']))
  {
 $id=$_GET['cpid'];
 $leadid=$_GET['leadid'];

 echo"<input type='hidden' id='cpid1' name='cpid' value='$id'>";
 echo"<input type='hidden' id='leadid1' name='leadid1' value='$leadid'>";
  
 $sql5="SELECT letter_no ,letter_date FROM bio_conceptproposal where bio_conceptproposal.cp_id =".$id; 
 $result=DB_query($sql5,$db);
 $myrow=DB_fetch_array($result);
 
 if(($myrow['letter_no']==0 OR $myrow['letter_no']=="") AND ($myrow['letter_date']==0 OR $myrow['letter_date']==""))
 {
 ?>
 <script>
 var str1=document.getElementById('cpid1').value;
 var str2=document.getElementById('leadid1').value;
 controlWindow=window.open("bio_cp_letterdetails.php?cpid=" + str1 +"&leadid="+ str2,"cpletterdetails","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=700,height=400"); 
 </script> 
 <?php
 }
  else
 {
 ?>     
 <script>
 
 var str1=document.getElementById('leadid1').value;
 var str2=document.getElementById('cpid1').value;
 
 controlWindow=window.open("bio_cpIndex.php?leadid=" + str1 +"&cpid="+ str2,"selectConceptProposalpdf","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=700,height=400");
  
// controlWindow=window.open("bio_proposal_coveringletter.php?leadid=" + str1 +"&propid=" + str2);
 </script>
 <?php    
  }
}

?>  

<script type="text/javascript" >

  $(document).ready(function() 
  {
  $('#district1').hide();
  $('#cpgrid').hide();
  $("#error").fadeOut(3000);                                    
  $("#warn").fadeOut(3000);
  $("#success").fadeOut(3000);
  $("#info").fadeOut(3000);
  $("#db_message").fadeOut(3000);  
  $('#sourcetype').change(function() { });                                                                                
  $('#shwprint').click(function() {
  $('#cpgrid').slideToggle('slow',function(){});
  $('#leadgrid').slideToggle('slow',function(){});
});    
}); 



   
   
function log_in()
{
 var f=0;                                                  //State
/* if(f==0){f=common_error('custname','Please enter Organization name');  if(f==1){return f; }  }  
 if(f==0){f=common_error('contactperson','Please enter Contact persons name');  if(f==1) { return f; }}
 if(f==0){f=common_error('custmob','Please enter mobile number');  if(f==1){return f; }  }            */
//if(f==0){f=common_error('enddate','Please enter end date');  if(f==1){return f; }  } 
// if(f==0){f=common_error('cpcharge','Please enter CP Charge');  if(f==1){return f; }  }
 /*if(f==0){
 var plant=document.getElementById('plantid').value;
 alert(plant);
 if(plant=="no") {
       alert("Select a plant");f=1;return f; 
      }
}     */
}  
 
   function expect_date()//edited
{
 var f=0;  
 if(f==0)
 {
     f=common_error('expectdate','Please Select an Expected date');  
     if(f==1) 
     { 
         return f;
     }}
//var enquiry=document.getElementById('expectdate').value; 
                                                 //State
/* if(f==0){f=common_error('custname','Please enter Organization name');  if(f==1){return f; }  }  
 if(f==0){f=common_error('contactperson','Please enter Contact persons name');  if(f==1) { return f; }}
 if(f==0){f=common_error('custmob','Please enter mobile number');  if(f==1){return f; }  }            */
//if(f==0){f=common_error('enddate','Please enter end date');  if(f==1){return f; }  } 
// if(f==0){f=common_error('cpcharge','Please enter CP Charge');  if(f==1){return f; }  }
 /*if(f==0){
 var plant=document.getElementById('plantid').value;
 alert(plant);
 if(plant=="no") {
       alert("Select a plant");f=1;return f; 
      }
}     */

}  
 
 
 function showCD4()
 {
 var str1=document.getElementById("feedstock").value;
 var str2=document.getElementById("weight").value;
//   alert("hii");
//   alert(str1);
 if(str1=="")
 {
 alert("Select Feedstock"); 
 document.getElementById("feedstock").focus(); 
  return false;  
 }
 if (str1=="")
  {
  document.getElementById("feedstockdiv").innerHTML="";     //editleads
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
    document.getElementById("feedstockdiv").innerHTML=xmlhttp.responseText;
    document.getElementById('feedstock').value="";       document.getElementById('weight').value="";
    }
  } 
 xmlhttp.open("GET","bio_sourcetypedetails-conceptpro.php?feedstock=" + str1  + "&weight=" + str2 ,true);
 xmlhttp.send();    
 }


 function showproposals(str){ //var a="#"+str;
//$(a).hide();
 var str1=document.getElementById("fsentry").value;
//alert(str1);
 str=2;
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
    
    document.getElementById("plant_select").innerHTML=xmlhttp.responseText; 
  }
  }
 xmlhttp.open("GET","bio_cp_plantdetails.php?leadid=" + str +"&fsid="+str1 ,true);
 xmlhttp.send();
}
/*

function showproposals(str){
   myRef = window.open("bio_cp_plantdetails.php?leadid=" + str,"plantdetails","toolbar=yes,location=no,directories=no,status=yes,menubar=yes,scrollbars=yes,resizable=no,width=600,height=400"); 
    
}
*/
  
function passid(str1,str2){
                    //alert(str1);

          
if(str2==1){
      alert("Feasibility Proposal not approved"); 
      return;
}

 if (str1=="")
  {
  document.getElementById("panel").innerHTML="";     
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
    document.getElementById("panel").innerHTML=xmlhttp.responseText;  
    fsid(str1);
    }
  } 
 xmlhttp.open("GET","bio_conceptproposaldetails.php?p=" + str1,true);
 xmlhttp.send(); 
 }


function CPreport(str1){
 alert("sdfdfds");  
 }


function fscalc(str1){
 var str1=document.getElementById("generatingsource").value;
 var str2=document.getElementById("feedstockqty").value;
 if (str1=="")
  {
  document.getElementById("fstotalcalc").innerHTML="";     
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
    document.getElementById("fstotalcalc").innerHTML=xmlhttp.responseText;  
 
    }
  } 
 xmlhttp.open("GET","bio_conceptproposal-fscalc.php?source=" + str1 + "&fsqty=" + str2,true);
 xmlhttp.send(); 
 }


function fsid(str1){
 if (str1=="")
  {
  document.getElementById("fstudyid").innerHTML="";     
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
    document.getElementById("fstudyid").innerHTML=xmlhttp.responseText;  
    }
  } 
 xmlhttp.open("GET","bio_conceptproposal-fsid.php?p=" + str1,true);
 xmlhttp.send(); 
 }

 
function editfeedstok(str)
{
//alert("hii");
//var str1=document.getElementById("hfeedstock").value;
//var str2=document.getElementById("hfeedweight").value;
 if (str=="")
  {
  document.getElementById("editfeed").innerHTML="";
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
    document.getElementById("editfeed").innerHTML=xmlhttp.responseText;
//    document.getElementById('loc').focus(); 
    }
  } 
xmlhttp.open("GET","bio_sourcetypedetails-conceptpro.php?upfeedstockid=" + str,true);
xmlhttp.send();    

}

function CpEdit(str1,str2)
{
//alert(str1);
//alert(str2);
if (str1=="")
  {
  document.getElementById("foredit").innerHTML="";
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
    document.getElementById("foredit").innerHTML=xmlhttp.responseText;
//    document.getElementById('loc').focus(); 
    }
  } 
xmlhttp.open("GET","bio_CPEdit.php?p=" + str1 + "&q=" + str2,true);
xmlhttp.send();    

}


function Newcp(str1,str2)
{
//alert(str1);
//alert(str2);
if (str1=="")
  {
  document.getElementById("panel").innerHTML="";
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
    document.getElementById("panel").innerHTML=xmlhttp.responseText;
//    document.getElementById('loc').focus(); 
    }
  } 
xmlhttp.open("GET","bio_CPNew.php?p=" + str1 + "&q=" + str2,true);
xmlhttp.send();    

}



function doedit()
{
//   alert("hii");
//   alert(str);

var str=document.getElementById("fdstk").value;    
var str1=document.getElementById("h1feedstock").value;
var str2=document.getElementById("h1feedweight").value;
// alert(str); alert(str1);     alert(str2); 
if (str=="")
  {
  document.getElementById("feedstockdiv").innerHTML="";
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
    document.getElementById("feedstockdiv").innerHTML=xmlhttp.responseText;
    $('#h1feedweight').focus(); 
    }
  } 
xmlhttp.open("GET","bio_sourcetypedetails-conceptpro.php?edid=" + str + "&edfd=" + str1 + "&edwt=" + str2,true);
xmlhttp.send();    

}  

function plantname(str)
{ 
var plantid=document.getElementById("plantid").value; 

if(plantid!="no")       {

var newstr=plantid + ',' + str;    
   
}else   {
    var newstr=str; 
}
if (str=="")
  {
  document.getElementById("plantname").innerHTML="";
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
    document.getElementById("plantname").innerHTML=xmlhttp.responseText;

    }
  } 
xmlhttp.open("GET","bio_conceptpro-plantname.php?id=" + newstr,true);
xmlhttp.send();    

}  
function deletfeedstok(str)
{
//   alert("hii");
//   alert(str);


// alert(str); alert(str1);     alert(str2); 
if (str=="")
  {
  document.getElementById("feedstockdiv").innerHTML="";
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
    document.getElementById("feedstockdiv").innerHTML=xmlhttp.responseText;

    }
  } 
xmlhttp.open("GET","bio_sourcetypedetails-conceptpro.php?delet=" + str,true);
xmlhttp.send();    

}


function advdetail(str)
{
if (str=="")
  {
  document.getElementById("amt").innerHTML="";
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
    document.getElementById("amt").innerHTML=xmlhttp.responseText;

    }
  } 
xmlhttp.open("GET","bio_fsamountdetails.php?adv=" + str,true);
xmlhttp.send();    
}



function selectplant(str,str2,str3)
{ 
controlWindow=window.open("bio_selectplantfor_cp.php?lead="+str+"&first="+str2+"&budgetinitial="+str3,"selplant","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=700,height=400");
}


function addSubsidy(lead,item,first,add)  
{
  controlWindow=window.open("bio_biocpaddsubsidy.php?ledid="+lead+"&item="+item+"&first="+first+"&add="+add,"addsubsidy","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=700,height=400");
}

function cptender(str)
{
  //  alert(str);
controlWindow=window.open("bio_cp_tender.php?lead=" + str,"newtender","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=900,height=400");
}

function removeitm(lead,item,first,remove)  
{
 //   alert(first);
    
//    first=3;
//    var remove=1;
location.href = "bio_conceptproposal.php?stockid=" + item + "&lead=" + lead + "&first=" + first + "&remove=" + remove;
}


function updatetotalitemprice(k,lead,stock)        //updateitemprice Row this   ///////////////////
{ //var a="#"+str;
//$(a).hide();
// alert(str);
//$("#grid").hide();
if (stock=="")
  {
  document.getElementById("tprice").value="";
  return;
  }
  
 var s=stock;
 var q=document.getElementById('qty'+k).value;
 var p=document.getElementById('price'+k).value;
 var t=document.getElementById('tprice'+k).value=q*p;
 var sub1=document.getElementById('subsidy'+k).value;
 var sub=q*sub1;
 var n=document.getElementById('netprice'+k).value=t-sub;

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
    document.getElementById("tprice").value=xmlhttp.responseText;
    }
  }
xmlhttp.open("GET","bio_updateproptempprice.php?stock="+s+"&qty="+q+"&price="+p+"&tprice="+t+"&lead="+lead+"&subsidy="+sub+"&nprice="+n);
xmlhttp.send();

if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp2=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp2=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp2.onreadystatechange=function()
  {
  if (xmlhttp2.readyState==4 && xmlhttp2.status==200)
    {
    document.getElementById("subsidy").value=xmlhttp2.responseText;
    }
  }
xmlhttp2.open("GET","bio_updateproptempsubsidy.php?stock="+s+"&qty="+q+"&price="+p+"&tprice="+t+"&lead="+lead+"&subsidy="+sub+"&nprice="+n);
xmlhttp2.send();


if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp1=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp1=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp1.onreadystatechange=function()
  {
  if (xmlhttp1.readyState==4 && xmlhttp1.status==200)
    {
    document.getElementById("netprice").value=xmlhttp1.responseText;
    }
  }
xmlhttp1.open("GET","bio_updateproptempnetprice.php?stock="+s+"&qty="+q+"&price="+p+"&tprice="+t+"&lead="+lead+"&subsidy="+sub+"&nprice="+n);
xmlhttp1.send();

}

function updatebudgetrem(lead)
{
  //alert(lead);
  
    

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
    document.getElementById("budgetrem").value=xmlhttp.responseText;

    }
  }
xmlhttp.open("GET","bio_updatebudgetrem.php?lead=" + lead);
xmlhttp.send();

}


function modifycp(lead,cpid)
{
  //alert(lead);
//  alert(cpid);
    var answer = confirm("Do you want to Save this as a new concept proposal?");
    if (answer)
    {
        var proposal=0;
        
    }
    else
    {
        var proposal=cpid;
        
    }
    
if (lead=="")
  {
  document.getElementById("sellist").innerHTML="";
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
    document.getElementById("sellist").innerHTML=xmlhttp.responseText;

    }
  }
xmlhttp.open("GET","bio_modifycpl.php?lead=" + lead + "&propid=" + proposal);
xmlhttp.send();
}

function Cpcancel(str1,str2)
{
location.href="?cpidcancel=" + str1 + "&leadidcancel=" + str2;
}


function CPPrint(str1,str2)

{ 
location.href="?cpid=" + str1 + "&leadid=" + str2;
}


function checkSubmit()//edited
{   
//var k=document.getElementById("k_value").value;
//var qty='qty'+k;

var plant=<?php echo $_SESSION['plant_selected'];?>;

    if (plant>0)
        {
            return true;
        }
        else
        {
            alert('please select the plant');
            return false;
        }
           
}

</script>