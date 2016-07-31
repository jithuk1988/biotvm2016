<?php

  $PageSecurity = 80;
include('includes/session.inc');

  
  
      
   $enqtype=$_GET['enqtypeid']; 
   $DebtorNo=customerCodeGenaration($enqtype,$db);
  


  echo '<tr><td width=237px>' . _('Customer Code') . ':</td><td align=left><input tabindex=1 type="text" name="DebtorNo" size=11 maxlength=10 style="width:100px" value="'.$DebtorNo.'"></td></tr>';
   
    
    // Show Customer Type drop down list
    if($enqtype==1){
    $result=DB_query("SELECT typeid, typename FROM debtortype WHERE typeid IN (1,2,3,4) ", $db);
    }
     else if($enqtype==4){
    $result=DB_query("SELECT typeid, typename FROM debtortype WHERE typeid IN (1,2,3,4,5,6,7,8) ", $db);
    }
    elseif($enqtype==2 || $enqtype==3){
    $result=DB_query("SELECT typeid, typename FROM debtortype WHERE typeid IN (5,6,7,8) ", $db);  
    }
    elseif($enqtype==10){
    $result=DB_query("SELECT typeid, typename FROM debtortype WHERE typeid IN (9) ", $db);  
    }
     
//    else
//    if (DB_num_rows($result)==0){
//       $DataError =1;
//       echo '<a href="SalesTypes.php?" target="_parent">' . _('Setup Types') . '</a>';
//       echo '<tr><td colspan=2>' . prnMsg(_('No Customer types/price lists defined'),'error') . '</td></tr>';
//    } else {
//        echo '<tr ><td>' . _('Customer Type') . ':</td>
//                   <td><select tabindex=9 name="typeid" style="width:100px">';                                                    
//                    echo'<option value=0></option>';
//        while ($myrow = DB_fetch_array($result)) {
//            echo '<option value="'. $myrow['typeid'] . '">' . $myrow['typename'] . '</option>';
//        } //end while loop
//        DB_data_seek($result,0);
//        echo '</select></td></tr>';
//    }


  
function customerCodeGenaration(&$enquirytype,&$db)
{
    if($enquirytype==1){
        $type="D";
    }elseif($enquirytype==2){
       $type="C"; 
    }elseif($enquirytype==4){
       $type="W"; 
    }
    
    elseif($enquirytype==3){
       $type="L";  
    }elseif($enquirytype==8){
       $type="DL";  
    }elseif($enquirytype==10){
       $type="FA";  
    }
    
    $sql_code="SELECT * FROM bio_clientidgeneration WHERE enquirytype='".$enquirytype."'";   
    $result_code = DB_query($sql_code,$db);
    $myrow_count = DB_num_rows($result_code);
    $myrow_code = DB_fetch_array($result_code); 
    if($myrow_count>0){
        $code=$myrow_code['code'];
        $code++;
        $sql="UPDATE bio_clientidgeneration  SET code=".$code." WHERE enquirytype='".$enquirytype."'";
        $ID=$type.$code;
    }
    else{
        $code=1;
        $sql="INSERT INTO bio_clientidgeneration(
                                         enquirytype,
                                        code) 
                                  VALUES('".$enquirytype."',
                                         ".$code.")";
        $ID=$type.$code;
    }
    $result = DB_query($sql,$db); 
    return($ID) ;
}






  
?>
