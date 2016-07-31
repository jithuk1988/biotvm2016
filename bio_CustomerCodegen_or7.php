<?php

  $PageSecurity = 80;
include('includes/session.inc');

  
  
      
   $enqtype=$_GET['enqtypeid']; 
   $DebtorNo=customerCodeGenaration($enqtype,$db);
  
  echo'<td><input tabindex=1 readonly type="text" name="DebtorNo" size=11 maxlength=10 style="width:100px" value="'.$DebtorNo.'"></td>';
  
function customerCodeGenaration(&$enquirytype,&$db)
{
    if($enquirytype==1){
        $type="D";
    }elseif($enquirytype==2){
       $type="C"; 
    }elseif($enquirytype==3){
       $type="L";  
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
