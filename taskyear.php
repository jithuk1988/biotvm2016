<?php

   $PageSecurity = 80;
    include ('includes/session.inc');
$title = _('Task List');
include ('includes/header.inc'); 
  $ds=mysql_connect($server="localhost", $username="root", $password="2vd5cM0w5J4uEXRS");
 $s= mysql_select_db("biotech",$ds) ;
 
 echo "<br><br>";
 $qry="SELECT
    newlist.debtorno
    , custbranch.brname,
    CONCAT(custbranch.phoneno,',', custbranch.faxno) AS 'Contact No'
    , bio_district.district      ,
    ifnull(date_format(newlist.installationdate,'%d-%m-%Y'),'NA') as insdate
   
FROM
    biotech.newlist
    INNER JOIN biotech.custbranch 
        ON (newlist.debtorno = custbranch.debtorno)
    LEFT JOIN biotech.bio_district 
        ON (custbranch.stateid = bio_district.stateid) AND (custbranch.cid = bio_district.cid)AND custbranch.did=bio_district.did limit 10";
 $p=mysql_query($qry,$ds);
 echo '        <table border="1" cellpadding="0" cellspacing="0" dir="ltr" style="font-size:13px;font-family:arial,sans,sans-serif;border-collapse:collapse;border:1px solid #ccc">
            <tbody>                  <tr style="height:21px;">
                    <td data-sheets-value="[null,2,&quot;H1&quot;]" style="padding: 2px 3px; vertical-align: bottom; border: 1px solid rgb(0, 0, 0); font-weight: bold; text-align: center; background-color: rgb(201, 218, 248);">
                        ID</td>
                <td data-sheets-value="[null,2,&quot;H1&quot;]" style="padding: 2px 3px; vertical-align: bottom; border: 1px solid rgb(0, 0, 0); font-weight: bold; text-align: center; background-color: rgb(201, 218, 248);">
                        NAME</td>
                    <td data-sheets-value="[null,2,&quot;H2&quot;]" style="padding: 2px 3px; vertical-align: bottom; border-top-width: 1px; border-top-style: solid; border-top-color: rgb(0, 0, 0); border-right-width: 1px; border-right-style: solid; border-right-color: rgb(0, 0, 0); border-bottom-width: 1px; border-bottom-style: solid; border-bottom-color: rgb(0, 0, 0); font-weight: bold; text-align: center; background-color: rgb(201, 218, 248);">
                        CONTACT NO</td>
                    <td data-sheets-value="[null,2,&quot;H3&quot;]" style="padding: 2px 3px; vertical-align: bottom; border-top-width: 1px; border-top-style: solid; border-top-color: rgb(0, 0, 0); border-right-width: 1px; border-right-style: solid; border-right-color: rgb(0, 0, 0); border-bottom-width: 1px; border-bottom-style: solid; border-bottom-color: rgb(0, 0, 0); font-weight: bold; text-align: center; background-color: rgb(201, 218, 248);">
                        ADDRESS</td>
                    <td data-sheets-value="[null,2,&quot;H4&quot;]" style="padding: 2px 3px; vertical-align: bottom; border-top-width: 1px; border-top-style: solid; border-top-color: rgb(0, 0, 0); border-right-width: 1px; border-right-style: solid; border-right-color: rgb(0, 0, 0); border-bottom-width: 1px; border-bottom-style: solid; border-bottom-color: rgb(0, 0, 0); font-weight: bold; text-align: center; background-color: rgb(201, 218, 248);">
                        INSTALLED DATE</td>
                    <td data-sheets-value="[null,2,&quot;H5&quot;]" style="padding: 2px 3px; vertical-align: bottom; border-top-width: 1px; border-top-style: solid; border-top-color: rgb(0, 0, 0); border-right-width: 1px; border-right-style: solid; border-right-color: rgb(0, 0, 0); border-bottom-width: 1px; border-bottom-style: solid; border-bottom-color: rgb(0, 0, 0); font-weight: bold; text-align: center; background-color: rgb(201, 218, 248);">
                        TASK</td>
                    <td data-sheets-value="[null,2,&quot;H6&quot;]" style="padding: 2px 3px; vertical-align: bottom; border-top-width: 1px; border-top-style: solid; border-top-color: rgb(0, 0, 0); border-right-width: 1px; border-right-style: solid; border-right-color: rgb(0, 0, 0); border-bottom-width: 1px; border-bottom-style: solid; border-bottom-color: rgb(0, 0, 0); font-weight: bold; text-align: center; background-color: rgb(201, 218, 248);">
                        TARGET DATE</td>
                    <td data-sheets-value="[null,2,&quot;H7&quot;]" style="padding: 2px 3px; vertical-align: bottom; border-top-width: 1px; border-top-style: solid; border-top-color: rgb(0, 0, 0); border-right-width: 1px; border-right-style: solid; border-right-color: rgb(0, 0, 0); border-bottom-width: 1px; border-bottom-style: solid; border-bottom-color: rgb(0, 0, 0); font-weight: bold; text-align: center; background-color: rgb(201, 218, 248);">
                        STATUS</td>
                    <td data-sheets-value="[null,2,&quot;H8&quot;]" style="padding: 2px 3px; vertical-align: bottom; border-top-width: 1px; border-top-style: solid; border-top-color: rgb(0, 0, 0); border-right-width: 1px; border-right-style: solid; border-right-color: rgb(0, 0, 0); border-bottom-width: 1px; border-bottom-style: solid; border-bottom-color: rgb(0, 0, 0); font-weight: bold; text-align: center; background-color: rgb(201, 218, 248);">
                        COMPLETE DATE</td>
                    <td data-sheets-value="[null,2,&quot;H9&quot;]" style="padding: 2px 3px; vertical-align: bottom; border-top-width: 1px; border-top-style: solid; border-top-color: rgb(0, 0, 0); border-right-width: 1px; border-right-style: solid; border-right-color: rgb(0, 0, 0); border-bottom-width: 1px; border-bottom-style: solid; border-bottom-color: rgb(0, 0, 0); font-weight: bold; text-align: center; background-color: rgb(201, 218, 248);">
                        REMARKS</td>
                </tr>
        
       ';
 while($row=mysql_fetch_array($p)) 
{ 
        echo '        <tr style="height:21px;">
                    <td colspan="1" data-sheets-value="[null,2,&quot;ID&quot;]" rowspan="3" style="width:10px; padding:2px 3px 2px 3px;vertical-align:bottom;border-right:1px solid #000000;border-bottom:1px solid #000000;border-left:1px solid #000000;">
                        <div style="max-height:62px">'.$row[0].'
                            <span id="cke_bm_138S" style="display: none;">&nbsp;</span><span style="display: none;">&nbsp;</span></div>
                    </td>
                    <td colspan="1" data-sheets-value="[null,2,&quot;ID&quot;]" rowspan="3" style="padding:2px 3px 2px 3px;vertical-align:bottom;border-right:1px solid #000000;border-bottom:1px solid #000000;border-left:1px solid #000000;">
                        <div style="max-height:62px">
                            '.$row[1].'<span id="cke_bm_138S" style="display: none;">&nbsp;</span><span style="display: none;">&nbsp;</span></div>
                    </td>
                    <td colspan="1" data-sheets-value="[null,2,&quot;NM&quot;]" rowspan="3" style="padding:2px 3px 2px 3px;vertical-align:bottom;border-right:1px solid #000000;border-bottom:1px solid #000000;">
                        <div style="max-height:62px">
                            '.$row[2].'</div>
                    </td>
                    <td colspan="1" data-sheets-value="[null,2,&quot;AD&quot;]" rowspan="3" style="padding:2px 3px 2px 3px;vertical-align:bottom;border-right:1px solid #000000;border-bottom:1px solid #000000;">
                        <div style="max-height:62px">
                            '.$row[3].'</div>
                    </td>
                    <td colspan="1" data-sheets-value="[null,2,&quot;IN DT&quot;]" rowspan="3" style="padding:2px 3px 2px 3px;vertical-align:bottom;border-right:1px solid #000000;border-bottom:1px solid #000000;">
                        <div style="max-height:62px">
                            '.$row[4].'</div>
                    </td>';
                      $nwqry="SELECT
    tasks.title
    , ifnull(date_format(tasks.targetdate,'%d-%m-%Y'),'NA') as targetdate
    , taskstatus.statustitle
    , ifnull(date_format(tasks.actualdate,'%d-%m-%Y'),'NA') as actualdate
    , tasks.remark
FROM
    biotech.taskstatus
    INNER JOIN biotech.tasks 
        ON (taskstatus.status = tasks.status)
        WHERE debtorno='".$row[0]."'";
 $tk=mysql_query($nwqry,$ds);  
 while($rowtk=mysql_fetch_array($tk))      
                   {
                    echo '<td data-sheets-value="[null,2,&quot;T1&quot;]" style="padding:2px 3px 2px 3px;vertical-align:bottom;border-right:1px solid #000000;border-bottom:1px solid #000000;">
                        '.$rowtk[0].'</td>
                    <td data-sheets-value="[null,2,&quot;TD 1&quot;]" style="padding:2px 3px 2px 3px;vertical-align:bottom;border-right:1px solid #000000;border-bottom:1px solid #000000;">
                        '.$rowtk[1].'</td>
                    <td data-sheets-value="[null,2,&quot;ST 1&quot;]" style="padding:2px 3px 2px 3px;vertical-align:bottom;border-right:1px solid #000000;border-bottom:1px solid #000000;">
                        '.$rowtk[2].'</td>
                    <td data-sheets-value="[null,2,&quot;CD 1&quot;]" style="padding:2px 3px 2px 3px;vertical-align:bottom;border-right:1px solid #000000;border-bottom:1px solid #000000;">
                        '.$rowtk[3].'</td>
                    <td data-sheets-value="[null,2,&quot;RK 1&quot;]" style="padding:2px 3px 2px 3px;vertical-align:bottom;border-right:1px solid #000000;border-bottom:1px solid #000000;">
                        '.$rowtk[4].'</td>
                </tr>   ';
                   }
                    /*echo '<td data-sheets-value="[null,2,&quot;T2&quot;]" style="padding:2px 3px 2px 3px;vertical-align:bottom;border-right:1px solid #000000;border-bottom:1px solid #000000;">
                        T2</td>
                    
                    <td data-sheets-value="[null,2,&quot;TD 2&quot;]" style="padding:2px 3px 2px 3px;vertical-align:bottom;border-right:1px solid #000000;border-bottom:1px solid #000000;">
                        TD 2</td>
                    <td data-sheets-value="[null,2,&quot;ST 2&quot;]" style="padding:2px 3px 2px 3px;vertical-align:bottom;border-right:1px solid #000000;border-bottom:1px solid #000000;">
                        ST 2</td>
                    <td data-sheets-value="[null,2,&quot;CD 2&quot;]" style="padding:2px 3px 2px 3px;vertical-align:bottom;border-right:1px solid #000000;border-bottom:1px solid #000000;">
                        CD 2</td>
                    <td data-sheets-value="[null,2,&quot;RK 2&quot;]" style="padding:2px 3px 2px 3px;vertical-align:bottom;border-right:1px solid #000000;border-bottom:1px solid #000000;">
                        RK 2</td>
                </tr>
                <tr style="height:21px;">
                    <td data-sheets-value="[null,2,&quot;T3&quot;]" style="padding:2px 3px 2px 3px;vertical-align:bottom;border-right:1px solid #000000;border-bottom:1px solid #000000;">
                        T3</td>
                    <td data-sheets-value="[null,2,&quot;TD 3&quot;]" style="padding:2px 3px 2px 3px;vertical-align:bottom;border-right:1px solid #000000;border-bottom:1px solid #000000;">
                        TD 3</td>
                    <td data-sheets-value="[null,2,&quot;ST 3&quot;]" style="padding:2px 3px 2px 3px;vertical-align:bottom;border-right:1px solid #000000;border-bottom:1px solid #000000;">
                        ST 3</td>
                    <td data-sheets-value="[null,2,&quot;CD 3&quot;]" style="padding:2px 3px 2px 3px;vertical-align:bottom;border-right:1px solid #000000;border-bottom:1px solid #000000;">
                        CD 3</td>
                    <td data-sheets-value="[null,2,&quot;RK 3&quot;]" style="padding:2px 3px 2px 3px;vertical-align:bottom;border-right:1px solid #000000;border-bottom:1px solid #000000;">
                        RK 3<span id="cke_bm_138E" style="display: none;">&nbsp;</span></td>
                </tr>
                
                
                
                ';    */
}
echo '
            </tbody>
        </table>';
        echo "<br><br>";
          include ('includes/footer.php');
          
        ?>
