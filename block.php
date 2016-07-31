<?php
$con=mysql_connect('localhost','tsunamis_biotech','bio23tech45');
$cnt=mysql_select_db('tsunamis_biotech',$con);
$sql="UPDATE `www_users` SET`blocked`=1 
       WHERE userid in ('bdedelhi1_1','bdeeklm1_1','bdeeklm1_2','bdekoz1_1','bdekoz1_2','bdekoz1_3','bdetvm1_1','bdetvm1_2','bdetvm1_3','ccekoz1','bde_international1','bde_national1','bdmdelhi1','bdmeklm1','bdmkoz1','bdmtvm1','bdm_incident','bdm_international','bdm_national','BIOTECHIT','businesshead','cccmho','ccedelhi1','cceeklm1','cceeklm2','ccekoz1','ccetvm1','ccetvm2','ccetvm3','exe_asst')";
mysql_query($sql,$con);     
?>
