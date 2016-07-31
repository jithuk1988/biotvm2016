<?php
$PageSecurity = 80;   
include('includes/session.inc');

$title = _('Search plants supplied');

include('includes/header.inc');

echo '<p class="page_title_text"><img src="'.$rootpath.'/css/'.$theme.'/images/magnifier.png" title="' . _('Search') .
    '" alt="" />' . ' ' . _('Region Wise Search') . '</p>';

echo '<form action="' . $_SERVER['PHP_SELF'] . '" method="post">';


echo '<table class="selection">'; 
echo '<tr>';

echo"<td>Country<select name='country' id='country' onchange='showstate(this.value)' style='width:130px'>";
$sql="SELECT * FROM bio_country ORDER BY cid";
$result=DB_query($sql,$db);
 $f=0;
 
   if(isset($_POST['country'])){
      $country=$_POST['country'];
  }else{
      $country=1;
  }
 
  while($myrow1=DB_fetch_array($result))
  {  
  if ($myrow1['cid']==$country)  
    {         //echo $myrow1['cid'];     
    echo '<option selected value="';
    } else 
    {
    if ($f==0) 
        {
        echo '<option>';
        }
        echo '<option value="';
    }
    echo $myrow1['cid'] . '">'.$myrow1['country'];
    echo '</option>';
    $f++;
   }    
  echo '</select></td>'; 
  
    echo '<td id="showstate">State<select name="state" id="state" onchange="showdistrict(this.value)" style="width:130px">';
  $sql="SELECT * FROM bio_state WHERE cid=$country ORDER BY stateid";
  $result=DB_query($sql,$db);
  $f=0;      
  
  if(isset($_POST['state'])){
      $state=$_POST['state'];
  }else{
      $state=14;
  }
  while($myrow1=DB_fetch_array($result))
  {
  if ($myrow1['stateid']==$state)
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
    echo $myrow1['stateid'] . '">'.$myrow1['state'];
    echo '</option>';
    $f++;
   }
  echo '</select>';
  echo'</td>'; 
  
  
 echo '<td id="showdistrict">District<select name="district" id="district" style="width:130px" onchange="showtaluk(this.value);">'; 
  $sql="SELECT * FROM bio_district WHERE stateid=14 ORDER BY did";
    $result=DB_query($sql,$db);
    $f=0;
  while($myrow1=DB_fetch_array($result))
  {
  if ($myrow1['did']==$_POST['district'])
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
    echo $myrow1['did'] . '">'.$myrow1['district'];
    echo '</option>';
    $f++;
   } 
  echo '</select>';
  echo'</td>';
  
  
    echo '<td>' . _('LSG Type') . '';
    echo '<select name="lsgType" id="lsgType" style="width:120px" tabindex=9 onchange=showblock(this.value)>';               
 if($_POST[lsgType]==1){ 
    echo '<option selected value=1>Corporation</option>';
    echo '<option value=2>Municipality</option>';
    echo '<option value=3>Panchayat</option>';     
 }elseif($_POST[lsgType]==2){      
    echo '<option value=1>Corporation</option>';
    echo '<option selected value=2>Municipality</option>';
    echo '<option value=3>Panchayat</option>';    
 }elseif($_POST[lsgType]==3){  
    echo '<option value=1>Corporation</option>';
    echo '<option value=2>Municipality</option>';
    echo '<option selected value=3>Panchayat</option>';  
 }else{
    echo '<option value=0></option>'; 
    echo '<option value=1>Corporation</option>';
    echo '<option value=2>Municipality</option>';
    echo '<option value=3>Panchayat</option>';  
 }
     echo '</select></td>';  
 
  if($_POST['lsgType']==1) {  
              if($_POST['district']==12){$distname='Thiruvananthapuram';}
              if($_POST['district']==6){$distname='Kollam';} 
              if($_POST['district']==2){$distname='Eranakulam';} 
              if($_POST['district']==13){$distname='Thrissur';} 
              if($_POST['district']==8){$distname='Kozhikode';} 
                    echo '<td width=130px>' . _('Corporation Name') . ':</td>';
                    echo '<td><select name="lsgName" readonly id="lsgName" style="width:190px" tabindex=11>';
                    echo "<option value='".$_POST['district']."'>".$distname."</option>"; 
                    echo '</select></td>';    
  }elseif($_POST['lsgType']==2) { 
      echo'<td width=130px>' . _('Municipality Name') . ':</td>';    
        
        $sql="SELECT * FROM bio_municipality WHERE country='".$_POST['country']."' AND state='".$_POST['state']."' AND district='".$_POST['district']."'";
        $result=DB_query($sql,$db);
        
        echo '<td><select name="lsgName" id="lsgName" style="width:190px" tabindex=11>';
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
      echo'</td>';
  }elseif($_POST['lsgType']==3) {
      echo'<td width=130px>' . _('Block Name') . ':';    
         
         $sql="SELECT * FROM bio_block WHERE country='".$_POST['country']."' AND state='".$_POST['state']."' AND district='".$_POST['district']."'";
         $result=DB_query($sql,$db);
         
         echo '<select name="lsgName" id="lsgName" style="width:130px" tabindex=11 onchange="showgramapanchayath(this.value)">';
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
      
      echo'<td width=130px>' . _('Panchayat Name') . ':';         
         
         $sql="SELECT * FROM bio_panchayat WHERE country='".$_POST['country']."' AND state='".$_POST['state']."' AND district='".$_POST['district']."'";
         $result=DB_query($sql,$db);
         
         echo '<select name="gramaPanchayath" id="gramaPanchayath" style="width:130px" tabindex=11>';
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
  }     
  echo '<td id=block></td>';  

    echo '<td>Customer Type<select name="enq" id="enq" style="width:150px">';
    echo '<option value=0></option>';   
    $sql1="select * from bio_enquirytypes";
    $result1=DB_query($sql1,$db);
    while($row1=DB_fetch_array($result1))
    {
    //  echo "<option value=$row1[enqtypeid]>$row1[enquirytype]</option>";
        if ($row1['enqtypeid']==$_POST['enq'])
           {
             echo '<option selected value="';
           } else 
           { 
               echo '<option value="'; 
           }
           echo $row1['enqtypeid'] . '">'.$row1['enquirytype'];
           echo '</option>';  
    }

echo '</select></td>'; 

        echo '<td align=right><input type=submit name=filter value=Search onclick="if(validation()==1)return false;"></td>';   
  
echo '</tr>';
echo '</table>';

echo '</form>';
echo"<br />";



if(isset($_POST['filter'])){
       

   if(isset($_POST['lsgType']) && $_POST['lsgType']==3) {
        if(isset($_POST['gramaPanchayath']) && $_POST['gramaPanchayath']!=""){
        $groupby=" GROUP BY ";                                                               $lsg="block";
        }elseif(isset($_POST['lsgName']))   {
        $groupby=" GROUP BY debtorsmaster.block_name,";                                      $lsg="panchayath";
        }
    }elseif(isset($_POST['lsgType']) && $_POST['lsgType']==2 && $_POST['lsgName']=="") {
        $groupby=" GROUP BY debtorsmaster.LSG_name,";                                        $lsg="municipality";    
    }elseif(isset($_POST['lsgType']) && $_POST['lsgType']==1) {
        $groupby=" GROUP BY ";                                                               $lsg="corporation";
    }elseif(isset($_POST['state']))  {
        $groupby=" GROUP BY debtorsmaster.did,";                                             $lsg="district";
    }elseif(isset($_POST['country']) && $_POST['country']!=0)  {
        $groupby=" GROUP BY debtorsmaster.stateid,";                                         $lsg="state";
    }    
     
                    //     CREATE OR REPLACE VIEW v_pend_new AS
$sqlview_pending="   

SELECT docno,bio_document_master.document_name,COUNT( * ) AS c1,0 as c2,bio_district.district,bio_municipality.municipality,bio_panchayat.name AS panchayath,bio_block.block,bio_corporation.corporation,
       debtorsmaster.cid,debtorsmaster.stateid,debtorsmaster.did,debtorsmaster.LSG_type,debtorsmaster.LSG_name,debtorsmaster.block_name
FROM bio_documentlist 
INNER JOIN salesorders ON bio_documentlist.orderno=salesorders.orderno 
INNER JOIN debtorsmaster ON debtorsmaster.debtorno=salesorders.debtorno 
INNER JOIN bio_document_master ON bio_documentlist.docno=bio_document_master.doc_no 
LEFT JOIN bio_district ON (`debtorsmaster`.`did` = `bio_district`.`did`) AND (`bio_district`.`cid` = `debtorsmaster`.`cid`) AND (`debtorsmaster`.`stateid` = `bio_district`.`stateid`)
LEFT JOIN bio_corporation ON (`bio_corporation`.`country` = `debtorsmaster`.`cid`) AND (`bio_corporation`.`state` = `debtorsmaster`.`stateid`) AND (`bio_corporation`.`district` = `debtorsmaster`.`LSG_name`) AND (`bio_corporation`.`district` = `debtorsmaster`.`did`) 
LEFT JOIN bio_municipality ON (`bio_municipality`.`country` = `debtorsmaster`.`cid`) AND (`bio_municipality`.`state` = `debtorsmaster`.`stateid`) AND (`bio_municipality`.`district` = `debtorsmaster`.`did`) AND (`bio_municipality`.`id` = `debtorsmaster`.`LSG_name`) 
LEFT JOIN bio_block ON (`debtorsmaster`.`LSG_name` = `bio_block`.`id`) AND (`debtorsmaster`.`did` = `bio_block`.`district`) AND (`debtorsmaster`.`stateid` = `bio_block`.`state`) AND (`debtorsmaster`.`cid` = `bio_block`.`country`) 
LEFT JOIN bio_panchayat ON (`debtorsmaster`.`did` = `bio_panchayat`.`district`) AND (`debtorsmaster`.`block_name` = `bio_panchayat`.`id`) AND (`debtorsmaster`.`stateid` = `bio_panchayat`.`state`) AND (`debtorsmaster`.`LSG_name` = `bio_panchayat`.`block`) AND (`debtorsmaster`.`cid` = `bio_panchayat`.`country`) 

WHERE bio_documentlist.status = 0 AND bio_documentlist.docno IN (SELECT doc_no FROM bio_document_master WHERE enqtypeid =".$_POST['enq'].")  ";
                    
              
      if ($_POST['enq']!=0)
      {  
         if ( $_POST['enq']==1){                                   
           $sqlview_pending .= " AND salesorders.debtorno LIKE 'D%'";  
         }else if ( $_POST['enq']==2){                                   
           $sqlview_pending .= " AND salesorders.debtorno LIKE 'C%'";                 
         }else if ( $_POST['enq']==3){                                   
           $sqlview_pending .= " AND salesorders.debtorno LIKE 'L%'";                 
         }
      }        
    
    if (isset($_POST['country']))    {
     if($_POST['country']!=0)   {
     $sqlview_pending .=" AND debtorsmaster.cid=".$_POST['country'];    }
     }
                                                                                
    if (isset($_POST['state']))    {
     if($_POST['state']!=0)   {
     $sqlview_pending .=" AND debtorsmaster.stateid=".$_POST['state'];    }
     }
     
    if (isset($_POST['district']))    {
     if($_POST['district']!=0)   {
     $sqlview_pending .=" AND debtorsmaster.did=".$_POST['district'];
      
    if (isset($_POST['lsgType']))    {
     if($_POST['lsgType']!=0)   {
       if($_POST['lsgType']!=NULL)   {  
         
     $sqlview_pending .=" AND debtorsmaster.LSG_type=".$_POST['lsgType'];    
       } 
    if (isset($_POST['lsgName']) && $_POST['lsgName']!="")    {
     if($_POST['lsgType']==1 OR $_POST['lsgType']==2)   {
     $sqlview_pending .=" AND debtorsmaster.LSG_name=".$_POST['lsgName'];    }
    
       elseif($_POST['lsgType']==3){
       $sqlview_pending .=" AND debtorsmaster.LSG_name=".$_POST['lsgName'];    } 
              
       
    if (isset($_POST['gramaPanchayath']))    {  
      if($_POST['gramaPanchayath']!=0 OR $_POST['gramaPanchayath']!=NULL)   {
      $sqlview_pending .=" AND debtorsmaster.block_name=".$_POST['gramaPanchayath'];    }       
     }
     }
     }
     }   
     }
             
     }    
          
$sqlview_pending .=" $groupby bio_documentlist.docno";     
DB_query($sqlview_pending,$db); 
                       //  CREATE OR REPLACE VIEW v_pend_old AS
$sqlview_pending_old="    

SELECT docno,bio_document_master.document_name,0 AS c1,COUNT(*) as c2,bio_district.district,bio_municipality.municipality,bio_panchayat.name AS panchayath,bio_block.block,bio_corporation.corporation,
       debtorsmaster.cid,debtorsmaster.stateid,debtorsmaster.did,debtorsmaster.LSG_type,debtorsmaster.LSG_name,debtorsmaster.block_name 
FROM bio_oldorderdoclist 
INNER JOIN bio_oldorders ON bio_oldorderdoclist.orderno=bio_oldorders.orderno 
INNER JOIN debtorsmaster ON debtorsmaster.debtorno=bio_oldorders.debtorno
INNER JOIN bio_document_master ON bio_oldorderdoclist.docno=bio_document_master.doc_no 
LEFT JOIN bio_district ON (`debtorsmaster`.`did` = `bio_district`.`did`) AND (`bio_district`.`cid` = `debtorsmaster`.`cid`) AND (`debtorsmaster`.`stateid` = `bio_district`.`stateid`)
LEFT JOIN bio_corporation ON (`bio_corporation`.`country` = `debtorsmaster`.`cid`) AND (`bio_corporation`.`state` = `debtorsmaster`.`stateid`) AND (`bio_corporation`.`district` = `debtorsmaster`.`LSG_name`) AND (`bio_corporation`.`district` = `debtorsmaster`.`did`) 
LEFT JOIN bio_municipality ON (`bio_municipality`.`country` = `debtorsmaster`.`cid`) AND (`bio_municipality`.`state` = `debtorsmaster`.`stateid`) AND (`bio_municipality`.`district` = `debtorsmaster`.`did`) AND (`bio_municipality`.`id` = `debtorsmaster`.`LSG_name`) 
LEFT JOIN bio_block ON (`debtorsmaster`.`LSG_name` = `bio_block`.`id`) AND (`debtorsmaster`.`did` = `bio_block`.`district`) AND (`debtorsmaster`.`stateid` = `bio_block`.`state`) AND (`debtorsmaster`.`cid` = `bio_block`.`country`) 
LEFT JOIN bio_panchayat ON (`debtorsmaster`.`did` = `bio_panchayat`.`district`) AND (`debtorsmaster`.`block_name` = `bio_panchayat`.`id`) AND (`debtorsmaster`.`stateid` = `bio_panchayat`.`state`) AND (`debtorsmaster`.`LSG_name` = `bio_panchayat`.`block`) AND (`debtorsmaster`.`cid` = `bio_panchayat`.`country`) 

WHERE bio_oldorderdoclist.status = 0 AND bio_oldorderdoclist.docno IN (SELECT doc_no FROM bio_document_master WHERE enqtypeid =".$_POST['enq'].")  ";
                    
              
      if ($_POST['enq']!=0)
      {  
         if ( $_POST['enq']==1){                                   
           $sqlview_pending_old .= " AND bio_oldorders.debtorno LIKE 'D%'";  
         }else if ( $_POST['enq']==2){                                   
           $sqlview_pending_old .= " AND bio_oldorders.debtorno LIKE 'C%'";                 
         }else if ( $_POST['enq']==3){                                   
           $sqlview_pending_old .= " AND bio_oldorders.debtorno LIKE 'L%'";                 
         }
      }        
    
    if (isset($_POST['country']))    {
     if($_POST['country']!=0)   {
     $sqlview_pending_old .=" AND debtorsmaster.cid=".$_POST['country'];    }
     }
                                                                                
    if (isset($_POST['state']))    {
     if($_POST['state']!=0)   {
     $sqlview_pending_old .=" AND debtorsmaster.stateid=".$_POST['state'];    }
     }
     
    if (isset($_POST['district']))    {
     if($_POST['district']!=0)   {
     $sqlview_pending_old .=" AND debtorsmaster.did=".$_POST['district'];
      
    if (isset($_POST['lsgType']))    {
     if($_POST['lsgType']!=0)   {
       if($_POST['lsgType']!=NULL)   {  
         
     $sqlview_pending_old .=" AND debtorsmaster.LSG_type=".$_POST['lsgType'];    
       } 
    if (isset($_POST['lsgName']) && $_POST['lsgName']!="")    {
     if($_POST['lsgType']==1 OR $_POST['lsgType']==2)   {
     $sqlview_pending_old .=" AND debtorsmaster.LSG_name=".$_POST['lsgName'];    }
    
       elseif($_POST['lsgType']==3){
       $sqlview_pending_old .=" AND debtorsmaster.LSG_name=".$_POST['lsgName'];    } 
              
       
    if (isset($_POST['gramaPanchayath']))    {  
      if($_POST['gramaPanchayath']!=0 OR $_POST['gramaPanchayath']!=NULL)   {
      $sqlview_pending_old .=" AND debtorsmaster.block_name=".$_POST['gramaPanchayath'];    }       
     }
     }
     }
     }   
     }
             
     }    
          
$sqlview_pending_old .=" $groupby bio_oldorderdoclist.docno";     
DB_query($sqlview_pending_old,$db);

                // CREATE OR REPLACE VIEW v_pendingdoclist AS                  
$sql_pending="

              SELECT docno,document_name,SUM(c1)+SUM(c2) AS tot1,0 AS tot2,district,municipality,panchayath,block,corporation,
                     cid,stateid,did,LSG_type,LSG_name,block_name   
              FROM(
              $sqlview_pending UNION ALL $sqlview_pending_old) t1
              group by $lsg,docno";
 DB_query($sql_pending,$db);     
  
//*
$sqlview_received="
                                               
SELECT docno,bio_document_master.document_name,COUNT(*) as c1,0 AS c2,bio_district.district,bio_municipality.municipality,bio_panchayat.name AS panchayath,bio_block.block,bio_corporation.corporation,
       debtorsmaster.cid,debtorsmaster.stateid,debtorsmaster.did,debtorsmaster.LSG_type,debtorsmaster.LSG_name,debtorsmaster.block_name 
FROM bio_documentlist 
INNER JOIN salesorders ON bio_documentlist.orderno=salesorders.orderno 
INNER JOIN debtorsmaster ON debtorsmaster.debtorno=salesorders.debtorno    
INNER JOIN bio_document_master ON bio_documentlist.docno=bio_document_master.doc_no 

LEFT JOIN bio_district ON (`debtorsmaster`.`did` = `bio_district`.`did`) AND (`bio_district`.`cid` = `debtorsmaster`.`cid`) AND (`debtorsmaster`.`stateid` = `bio_district`.`stateid`) 
LEFT JOIN bio_corporation ON (`bio_corporation`.`country` = `debtorsmaster`.`cid`) AND (`bio_corporation`.`state` = `debtorsmaster`.`stateid`) AND (`bio_corporation`.`district` = `debtorsmaster`.`LSG_name`) AND (`bio_corporation`.`district` = `debtorsmaster`.`did`) 
LEFT JOIN bio_municipality ON (`bio_municipality`.`country` = `debtorsmaster`.`cid`) AND (`bio_municipality`.`state` = `debtorsmaster`.`stateid`) AND (`bio_municipality`.`district` = `debtorsmaster`.`did`) AND (`bio_municipality`.`id` = `debtorsmaster`.`LSG_name`) 
LEFT JOIN bio_block ON (`debtorsmaster`.`LSG_name` = `bio_block`.`id`) AND (`debtorsmaster`.`did` = `bio_block`.`district`) AND (`debtorsmaster`.`stateid` = `bio_block`.`state`) AND (`debtorsmaster`.`cid` = `bio_block`.`country`) 
LEFT JOIN bio_panchayat ON (`debtorsmaster`.`did` = `bio_panchayat`.`district`) AND (`debtorsmaster`.`block_name` = `bio_panchayat`.`id`) AND (`debtorsmaster`.`stateid` = `bio_panchayat`.`state`) AND (`debtorsmaster`.`LSG_name` = `bio_panchayat`.`block`) AND (`debtorsmaster`.`cid` = `bio_panchayat`.`country`) 

WHERE bio_documentlist.status > 0 AND bio_documentlist.docno IN (SELECT doc_no FROM bio_document_master WHERE enqtypeid =".$_POST['enq'].")";
       if ($_POST['enq']!=0)
      {  
         if ( $_POST['enq']==1){                                   
           $sqlview_received .= " AND salesorders.debtorno LIKE 'D%'";  
         }else if ( $_POST['enq']==2){                                   
           $sqlview_received .= " AND salesorders.debtorno LIKE 'C%'";                 
         }else if ( $_POST['enq']==3){                                   
           $sqlview_received .= " AND salesorders.debtorno LIKE 'L%'";                 
         }
      }        
    
    if (isset($_POST['country']))    {
     if($_POST['country']!=0)   {
     $sqlview_received .=" AND debtorsmaster.cid=".$_POST['country'];    }
     }
                                                                                
    if (isset($_POST['state']))    {
     if($_POST['state']!=0)   {
     $sqlview_received .=" AND debtorsmaster.stateid=".$_POST['state'];    }
     }
     
    if (isset($_POST['district']))    {
     if($_POST['district']!=0)   {
     $sqlview_received .=" AND debtorsmaster.did=".$_POST['district'];
      
    if (isset($_POST['lsgType']))    {
     if($_POST['lsgType']!=0)   {
       if($_POST['lsgType']!=NULL)   {  
         
     $sqlview_received .=" AND debtorsmaster.LSG_type=".$_POST['lsgType'];    
       } 
    if (isset($_POST['lsgName']) && $_POST['lsgName']!="")    {
     if($_POST['lsgType']==1 OR $_POST['lsgType']==2)   {
     $sqlview_received .=" AND debtorsmaster.LSG_name=".$_POST['lsgName'];    }
    
       elseif($_POST['lsgType']==3){
       $sqlview_received .=" AND debtorsmaster.LSG_name=".$_POST['lsgName'];    } 
              
       
    if (isset($_POST['gramaPanchayath']))    {  
      if($_POST['gramaPanchayath']!=0 OR $_POST['gramaPanchayath']!=NULL)   {
      $sqlview_received .=" AND debtorsmaster.block_name=".$_POST['gramaPanchayath'];    }       
     }
     }
     }
     }   
     }
             
     }  
       
$sqlview_received .=" $groupby bio_documentlist.docno";  
DB_query($sqlview_received,$db);        


$sqlview_received_old="    

SELECT docno,bio_document_master.document_name,0 AS c1,COUNT(*) as c2,bio_district.district,bio_municipality.municipality,bio_panchayat.name AS panchayath,bio_block.block,bio_corporation.corporation,
       debtorsmaster.cid,debtorsmaster.stateid,debtorsmaster.did,debtorsmaster.LSG_type,debtorsmaster.LSG_name,debtorsmaster.block_name
FROM bio_oldorderdoclist 
INNER JOIN bio_oldorders ON bio_oldorderdoclist.orderno=bio_oldorders.orderno 
INNER JOIN debtorsmaster ON debtorsmaster.debtorno=bio_oldorders.debtorno
INNER JOIN bio_document_master ON bio_oldorderdoclist.docno=bio_document_master.doc_no 
LEFT JOIN bio_district ON (`debtorsmaster`.`did` = `bio_district`.`did`) AND (`bio_district`.`cid` = `debtorsmaster`.`cid`) AND (`debtorsmaster`.`stateid` = `bio_district`.`stateid`)
LEFT JOIN bio_corporation ON (`bio_corporation`.`country` = `debtorsmaster`.`cid`) AND (`bio_corporation`.`state` = `debtorsmaster`.`stateid`) AND (`bio_corporation`.`district` = `debtorsmaster`.`LSG_name`) AND (`bio_corporation`.`district` = `debtorsmaster`.`did`) 
LEFT JOIN bio_municipality ON (`bio_municipality`.`country` = `debtorsmaster`.`cid`) AND (`bio_municipality`.`state` = `debtorsmaster`.`stateid`) AND (`bio_municipality`.`district` = `debtorsmaster`.`did`) AND (`bio_municipality`.`id` = `debtorsmaster`.`LSG_name`) 
LEFT JOIN bio_block ON (`debtorsmaster`.`LSG_name` = `bio_block`.`id`) AND (`debtorsmaster`.`did` = `bio_block`.`district`) AND (`debtorsmaster`.`stateid` = `bio_block`.`state`) AND (`debtorsmaster`.`cid` = `bio_block`.`country`) 
LEFT JOIN bio_panchayat ON (`debtorsmaster`.`did` = `bio_panchayat`.`district`) AND (`debtorsmaster`.`block_name` = `bio_panchayat`.`id`) AND (`debtorsmaster`.`stateid` = `bio_panchayat`.`state`) AND (`debtorsmaster`.`LSG_name` = `bio_panchayat`.`block`) AND (`debtorsmaster`.`cid` = `bio_panchayat`.`country`) 

WHERE bio_oldorderdoclist.status > 0 AND bio_oldorderdoclist.docno IN (SELECT doc_no FROM bio_document_master WHERE enqtypeid =".$_POST['enq'].")  ";
                    
              
      if ($_POST['enq']!=0)
      {  
         if ( $_POST['enq']==1){                                   
           $sqlview_received_old .= " AND bio_oldorders.debtorno LIKE 'D%'";  
         }else if ( $_POST['enq']==2){                                   
           $sqlview_received_old .= " AND bio_oldorders.debtorno LIKE 'C%'";                 
         }else if ( $_POST['enq']==3){                                   
           $sqlview_received_old .= " AND bio_oldorders.debtorno LIKE 'L%'";                 
         }
      }        
    
    if (isset($_POST['country']))    {
     if($_POST['country']!=0)   {
     $sqlview_received_old .=" AND debtorsmaster.cid=".$_POST['country'];    }
     }
                                                                                
    if (isset($_POST['state']))    {
     if($_POST['state']!=0)   {
     $sqlview_received_old .=" AND debtorsmaster.stateid=".$_POST['state'];    }
     }
     
    if (isset($_POST['district']))    {
     if($_POST['district']!=0)   {
     $sqlview_received_old .=" AND debtorsmaster.did=".$_POST['district'];
      
    if (isset($_POST['lsgType']))    {
     if($_POST['lsgType']!=0)   {
       if($_POST['lsgType']!=NULL)   {  
         
     $sqlview_received_old .=" AND debtorsmaster.LSG_type=".$_POST['lsgType'];    
       } 
    if (isset($_POST['lsgName']) && $_POST['lsgName']!="")    {
     if($_POST['lsgType']==1 OR $_POST['lsgType']==2)   {
     $sqlview_received_old .=" AND debtorsmaster.LSG_name=".$_POST['lsgName'];    }
    
       elseif($_POST['lsgType']==3){
       $sqlview_received_old .=" AND debtorsmaster.LSG_name=".$_POST['lsgName'];    } 
              
       
    if (isset($_POST['gramaPanchayath']))    {  
      if($_POST['gramaPanchayath']!=0 OR $_POST['gramaPanchayath']!=NULL)   {
      $sqlview_received_old .=" AND debtorsmaster.block_name=".$_POST['gramaPanchayath'];    }       
     }
     }
     }
     }   
     }
             
     }    
          
$sqlview_received_old .=" $groupby bio_oldorderdoclist.docno";     
DB_query($sqlview_received_old,$db);
                 //  CREATE OR REPLACE VIEW v_receiveddoclist AS 
$sql_received="
               SELECT docno,document_name,0 AS tot1,SUM(c1)+SUM(c2) AS tot2,district,municipality,panchayath,block,corporation,
                      cid,stateid,did,LSG_type,LSG_name,block_name
               FROM(
               $sqlview_received UNION ALL $sqlview_received_old)t2
               group by $lsg,docno";
 DB_query($sql_received,$db); 
 
 
 
$sql="

SELECT docno,document_name,SUM(tot1) as count_not_recd,SUM(tot2) as count_recd,district,municipality,panchayath,block,corporation,
       cid,stateid,did,LSG_type,LSG_name,block_name  FROM(
                                                                                                                    
SELECT docno,document_name,tot1,tot2,district,municipality,panchayath,block,corporation,
       cid,stateid,did,LSG_type,LSG_name,block_name 
FROM ($sql_pending) t4 GROUP BY $lsg,docno
UNION ALL
SELECT docno,document_name,tot1,tot2,district,municipality,panchayath,block,corporation,
       cid,stateid,did,LSG_type,LSG_name,block_name
FROM ($sql_received) t3 GROUP BY $lsg,docno 
     
)t5 GROUP BY $lsg,docno ORDER BY $lsg,docno";       
$result=DB_query($sql,$db);                   

  
 
echo"<table style='border:1px solid #F0F0F0;width:90%' ; >";   
echo"<tr>";
echo"<th width=100px>No:of orders</th><th>Document Name</th><th>Recieved Documents</th><th>Pending Documents</th>";  

 $k=0;  $i=0;
 while($myrow_viewdocs=DB_fetch_array($result))
 {   
     
     $nooforders=$myrow_viewdocs['count_not_recd']+$myrow_viewdocs['count_recd'];
     
     
if(isset($_POST['lsgType']) && $_POST['lsgType']==3) {
    if(isset($_POST['gramaPanchayath'])){
        $regionName=$myrow_viewdocs['panchayath'];                        
    }elseif(isset($_POST['lsgName']))  {
        $regionName=$myrow_viewdocs['block'];        
    }
}elseif(isset($_POST['lsgType']) && $_POST['lsgType']==2 && ($_POST['lsgName']=="" || $_POST['lsgName']!="")) {    
    $regionName=$myrow_viewdocs['municipality'];    
}elseif(isset($_POST['lsgType']) && $_POST['lsgType']==1) {
    $regionName=$myrow_viewdocs['corporation']; 
}elseif(isset($_POST['state']))  {
    $regionName=$myrow_viewdocs['district'];  
}elseif(isset($_POST['country']) && $_POST['country']!=0)  {
    $regionName=$myrow_region['state'];  
}     
    
    if($_POST['enq']==1){ $docs=12; }
elseif($_POST['enq']==2){ $docs=16; }


    $did=$myrow_viewdocs['did'];
    $nam=$myrow_viewdocs['LSG_name']; 
    $pan=$myrow_viewdocs['block_name'];

        
    
            if( $i%$docs==0 )  {
            echo"<tr><td width=100px><b><a style='cursor:pointer;' onclick='passid($did,$nam,$pan)'>$regionName:&nbsp;</a></b><i>$nooforders</i></td></tr>";     
            }
  
            if($_POST['enq']!=0 ){        //&& $myrow_region[count]!=0
            

                      if ($k==1)
                      {
                        echo '<tr class="EvenTableRows">';
                        $k=0;
                      }else 
                      {
                        echo '<tr class="OddTableRows">';
                        $k=1;     
                      }
            echo"<td></td><td>$myrow_viewdocs[document_name]</td><td>$myrow_viewdocs[count_recd]</td><td>$myrow_viewdocs[count_not_recd]</td>"; 
            }
  
   $i++;
 }   


echo"</tr>";
echo"</tr></table>";

}

?>


<script type="text/javascript"> 

function passid(dis,nam,pan){                                    
                                   
   var cty=document.getElementById("country").value;     
   var sta=document.getElementById("state").value;   
   var typ=document.getElementById("lsgType").value;       
   var enq=document.getElementById("enq").value; 
//   if(typ!=0){      
//       var nam=document.getElementById("lsgName").value;    
//       if(typ==3 && nam!=""){     
//       var pan=document.getElementById("gramaPanchayath").value;        // alert(pan);
//       }else{
//       var pan=0;    
//       }  
//   } 
//   else{            
//       var nam=0;                                             
//   }    
 


 var id="&country="+cty+"&state="+sta+"&district="+dis+"&enq="+enq;   
                     
 if(typ!=0)   {   id+="&lsgType="+typ+"&lsgName="+nam;   } 
 if(typ==3)   {   id+="&gramaPanchayath="+pan;   }         
   
window.open("bio_orderDocuments.php?regtype=2"+id);      
  
}


function validation() {
 var f=0;    

  f=common_error('enq','Please Select an Enquiry Type');  if(f==1) { return f; }    
}

function showstate(str){
   // alert(str); 

if (str=="")
  {
  document.getElementById("showstate").innerHTML="";
  return;
  }
//show_progressbar('showstate');

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
    {                 //  alert(str); 
    document.getElementById("showstate").innerHTML=xmlhttp.responseText;

    }
  }
xmlhttp.open("GET","bio_lsgFilter.php?country=" + str,true);
xmlhttp.send();
}



function showdistrict(str){       
//    alert(str);
str1=document.getElementById("country").value;
//alert(str1);
if (str=="")
  {
  document.getElementById("showdistrict").innerHTML="";
  return;
  }
//show_progressbar('showdistrict');
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
    document.getElementById("showdistrict").innerHTML=xmlhttp.responseText;

    }
  }
xmlhttp.open("GET","bio_lsgFilter.php?state=" + str + "&country1=" + str1,true);
xmlhttp.send();

}

 function showblock(str){   
     str1=document.getElementById("country").value;
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
xmlhttp.open("GET","bio_lsgFilter.php?block=" + str +"&country1="+ str1 +"&state1="+ str2 +"&district="+ str3,true);
xmlhttp.send(); 
} 

 function showgramapanchayath(str){   
 //  alert(str);
     str1=document.getElementById("country").value;
     str2=document.getElementById('state').value;
     str3=document.getElementById('district').value;
//alert(str1);   alert(str2);       alert(str3);
if (str=="")
  {
  document.getElementById("showgramapanchayath").innerHTML="";
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
     document.getElementById("showgramapanchayath").innerHTML=xmlhttp.responseText; 
       
    }
  } 
xmlhttp.open("GET","bio_lsgFilter.php?grama=" + str +"&country1="+ str1 +"&state2="+ str2 +"&district="+ str3,true);
xmlhttp.send(); 
}       


</script>