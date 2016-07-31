<?php
 $PageSecurity = 80;
include('includes/session.inc');/*onclick=showCD1(this.value)onchange=showCD1_dealer(this.value)onchange=showCD1_staff(this.value) onclick=showCD1(this.value)*/


  $sourcetypeid=$_GET['q'] ;

  if($sourcetypeid==16) 
  {
      echo '<td>Network Group Name*</td><td>'; 
         echo '<select name=source id="source" tabindex=25 style="width:192px" onchange=showCD1(this.value) onclick=showCD1(this.value)> >';

      $sql1="SELECT id,cust_code  FROM  bio_network_cust ";
   $result1=DB_query($sql1,$db) ;
       $a=0;
   while ($myrow1 = DB_fetch_array($result1)) {
    if ($sourcetypeid==$_POST['source']) 
    {
    echo '<option  selected value="';
} else if($a==0){echo"<option>"; }


        echo '<option value="';
        echo $myrow1['id'] .'">'.$myrow1['cust_code'];
        echo  '</option>';
        $a++;
    }
    echo '</select></td>';
  }
  elseif($sourcetypeid==15) 
  {
      echo '<td>Dealer Name*</td><td>'; 
         echo '<select name=source id="source" tabindex=25 style="width:192px" onchange=showCD1(this.value) onclick=showCD1(this.value)> >';

   $sql1="SELECT debtorno,brname  FROM `custbranch` WHERE `debtorno` LIKE '%DL%'";
   $result1=DB_query($sql1,$db) ;
       $a=0;
   while ($myrow1 = DB_fetch_array($result1)) {
    if ($sourcetypeid==$_POST['source']) 
    {
    echo '<option  selected value="';
} else if($a==0){echo"<option>"; }


        echo '<option value="';
        echo $myrow1['debtorno'] .'">'.$myrow1['brname'];
        echo  '</option>';
        $a++;
    }
    echo '</select></td>';
  }
    elseif($sourcetypeid==14) 
  {
      echo '<td>Staff Name*</td><td>'; 
              echo '<select name=source id="source" tabindex=25 style="width:192px" onchange=showCD1(this.value) onclick=showCD1(this.value)> >';

   $sql1="SELECT empname,empid FROM bio_emp where rowstatus!=1 order by empname asc ";
   $result1=DB_query($sql1,$db) ;
       $a=0;
   while ($myrow1 = DB_fetch_array($result1)) {
    if ($sourcetypeid==$_POST['source']) 
    {
    echo '<option  selected value="';
} else if($a==0){echo"<option>"; }


        echo '<option value="';
        echo $myrow1['empid'] .'">'.$myrow1['empname'];
        echo  '</option>';
        $a++;
    }
    echo '</select></td>'; 
  }
  
  else{
      echo '<td>Lead Source Name*</td><td>'; 
         echo '<select name=source id="source" tabindex=25 style="width:192px" onchange=showCD1(this.value) onclick=showCD1(this.value)>';

   $sql1="SELECT id,sourcename, sourcetypeid
FROM bio_leadsources
WHERE bio_leadsources.rowstatus!=1 and bio_leadsources.sourcetypeid=".$sourcetypeid." order by id desc";
   $result1=DB_query($sql1,$db) ;
       $a=0;
   while ($myrow1 = DB_fetch_array($result1)) {
    if ($sourcetypeid==$_POST['source']) 
    {
    echo '<option  selected value="';
} else if($a==0){echo"<option>"; }


        echo '<option value="';
        echo $myrow1['id'] .'">'.$myrow1['sourcename'];
        echo  '</option>';
        $a++;
    }
    echo '</select></td>';
  }


    
   //echo '</tr>';
?>
