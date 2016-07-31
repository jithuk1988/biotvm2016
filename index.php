<?php

/* $Id: index.php 4574 2011-05-25 10:30:45Z daintree $*/
    $PageSecurity = 0;

include('includes/session.inc');
$title=_('Main Menu');

/*The module link codes are hard coded in a switch statement below to determine the options to show for each tab */
$ModuleLink = array('leads', 'orders', 'AR', 'CS', 'AP', 'PO', 'stock', 'manuf',  'GL', 'FA', 'PC', 'system');
/*The headings showing on the tabs accross the main index used also in WWW_Users for defining what should be visible to the user */
$ModuleList = array(_('Sales Leads'),
                _('Sales'),
				_('Receivables'),
                _('Customer Support'),
				_('Payables'),
				_('Purchases'),
				_('Inventory'),
				_('Manufacturing'),
				_('General Ledger'),
				_('Asset Manager'),
				_('Petty Cash'),
				_('Setup'));

if (isset($_GET['Application'])){ /*This is sent by this page (to itself) when the user clicks on a tab */
	$_SESSION['Module'] = $_GET['Application'];
}

include('includes/header.inc');
   echo"<table width=100%>";

include('includes/taskalert.inc');

//////////////////////////////////////////////////////
  if($_SESSION[UserID]!='useradmin')
  {
  $empid=$_SESSION['empid'];   
  

$sql_emp1="SELECT * FROM bio_emp WHERE empid=".$empid;
     $result_emp1=DB_query($sql_emp1,$db);
     $myrow_emp1=DB_fetch_array($result_emp1);
     
       
     
 $employee_arr=array();   
     $sql_drop="DROP TABLE if exists `emptable`";
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
                      
                      $sql_insert1="INSERT INTO emptable (empid) VALUES ($empid)";
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
   
   $team_arr=array();
   $sql6="SELECT teamid FROM bio_teammembers WHERE empid IN ($employee_arr)";
    $result6=DB_query($sql6,$db);
    while($row6=DB_fetch_array($result6))
    {
        $team_arr[]=$row6['teamid'];
    }
         
    $team_array=join(",", $team_arr);


/*$sql8="SELECT count( DISTINCT bio_leadtask.leadid )
FROM bio_leadtask
WHERE bio_leadtask.teamid in ($team_array)
AND bio_leadtask.viewstatus =1
AND bio_leadtask.taskid =0
AND bio_leadtask.taskcompletedstatus =0
AND bio_leadtask.leadid NOT
IN (

SELECT leadid
FROM bio_leadschedule
)
AND bio_leadtask.leadid NOT
IN (

SELECT leadid
FROM bio_leads
WHERE leadstatus =20
)
AND bio_leadtask.leadid NOT
IN (

SELECT leadid
FROM bio_leads
WHERE enqtypeid in (3,1)
)";
$result8=DB_query($sql8,$db);
 $myrow_cnt1=DB_fetch_array($result8);    */      
   $sql8="SELECT teamid FROM bio_teammembers WHERE empid=".$_SESSION['empid'];
   $result8=DB_query($sql8,$db);  
   $myrow_cnt1=DB_fetch_array($result8); 
$team=$myrow_cnt1[0];
//$_SESSION['cnt']=$assign;
 $sql9="SELECT count(distinct bio_leadtask.leadid) 
FROM bio_cust LEFT JOIN bio_leads on bio_cust.cust_id=bio_leads.cust_id 
LEFT JOIN bio_district ON bio_district.cid=bio_cust.nationality 
AND bio_district.stateid=bio_cust.state 
AND bio_district.did=bio_cust.district 
Left join bio_leadtask on bio_leadtask.leadid=bio_leads.leadid 
Left join bio_leadsources on bio_leadsources.id=bio_leads.sourceid 
LEft join bio_task on bio_task.taskid=bio_leadtask.taskid 
LEft join bio_leadteams on bio_leadteams.teamid=bio_leadtask.teamid 
Left join bio_enquirytypes on bio_enquirytypes.enqtypeid=bio_leads.enqtypeid 
LEft join bio_status on bio_status.statusid=bio_leads.leadstatus 
where bio_leadtask.teamid=$team AND bio_leads.leadstatus NOT IN(20,6) 
AND bio_leadtask.viewstatus=1 AND bio_leadtask.taskcompletedstatus=0
 AND bio_leads.enqtypeid=2";
                 $result9=DB_query($sql9,$db); 
                    $myrow_cnt3=DB_fetch_array($result9); 
$assign=$myrow_cnt3[0]; 
$_SESSION['cnt']=$assign;  }
//////////////////////////////////////////////////////
if (count($_SESSION['AllowedPageSecurityTokens'])==1 and $SupplierLogin==0){

/* if there is only one security access and its 1 (it has to be 1 for this page came up at all)- it must be a customer log on
 * need to limit the menu to show only the customer accessible stuff this is what the page looks like for customers logging in
 */
?>


		<tr>
		<td class="menu_group_items">  <!-- Orders transaction options -->
		<table class="table_index">
			<tr>
			<td class="menu_group_item">
				<?php echo '<p>&bull; <a href="' . $rootpath . '/CustomerInquiry.php?CustomerID=' . $_SESSION['CustomerID'] . '">' . _('Account Status') . '</a></p>'; ?>
			</td>
			</tr>
			<tr>
			<td class="menu_group_item">
				<?php echo '<p>&bull; <a href="' . $rootpath . '/SelectOrderItems.php?NewOrder=Yes">' . _('Place An Order') . '</a></p>'; ?>
			</td>
			</tr>
			<tr>
			<td class="menu_group_item">
				<?php echo '<p>&bull; <a href="' . $rootpath . '/SelectCompletedOrder.php?SelectedCustomer=' . $_SESSION['CustomerID'] . '">' . _('Order Status') . '</a></p>'; ?>
			</td>
			</tr>
		</table>
	</td>
<?php
	include('includes/footer.inc');
	exit;
} else if (count($_SESSION['AllowedPageSecurityTokens'])==1 and $SupplierLogin==1){
?>

		<tr>
		<td class="menu_group_items">  <!-- Orders transaction options -->
		<table class="table_index">
			<tr>
			<td class="menu_group_item">
				<?php echo '<p>&bull; <a href="' . $rootpath . '/SupplierTenders.php">' . _('Supplier Tenders') . '</a></p>'; ?>
			</td>
			</tr>
		</table>
	</td>
<?php
} else {  /* Security settings DO allow seeing the main menu */

?>
		<table width="100%"><td width="10%" valign="top"><table class="main_menu" width="100%" cellspacing="0" cellpadding="0" border="0">

	<?php


	$i=0;

	while ($i < count($ModuleLink)){
		// This determines if the user has display access to the module see config.php and header.inc
		// for the authorisation and security code
		if ($_SESSION['ModulesEnabled'][$i]==1)	{

			// If this is the first time the application is loaded then it is possible that
			// SESSION['Module'] is not set if so set it to the first module that is enabled for the user
			if (!isset($_SESSION['Module'])OR $_SESSION['Module']==''){
				$_SESSION['Module']=$ModuleLink[$i];
			}
			if ($ModuleLink[$i] == $_SESSION['Module']){
				echo '<tr><td class="main_menu_selected"><a href="' . $_SERVER['PHP_SELF'] . '?Application='. $ModuleLink[$i] . '">' . $ModuleList[$i] . '</a></td></tr>';
			} else {
				echo '<tr><td class="main_menu_unselected"><a href="' . $_SERVER['PHP_SELF'] . '?Application='. $ModuleLink[$i] . '">' . $ModuleList[$i] . '</a></td></tr>';
			}
		}
		$i++;
	}

	?>
		</table></td><td valign="top">
	<?php


	switch ($_SESSION['Module']) {

                 	Case 'leads': /* Leads Module */

	
function getBrowser()
{
    $u_agent = $_SERVER['HTTP_USER_AGENT'];
    $bname = 'Unknown';
    $platform = 'Unknown';
    $version= "";

    //First get the platform?
    if (preg_match('/linux/i', $u_agent)) {
        $platform = 'linux';
    }
    elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
        $platform = 'mac';
    }
    elseif (preg_match('/windows|win32/i', $u_agent)) {
        $platform = 'windows';
    }
   
    // Next get the name of the useragent yes seperately and for good reason
    if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent))
    {
        $bname = 'Internet Explorer';
        $ub = "MSIE";
    }
    elseif(preg_match('/Firefox/i',$u_agent))
    {
        $bname = 'Mozilla Firefox';
        $ub = "Firefox";
    }
    elseif(preg_match('/Chrome/i',$u_agent))
    {
        $bname = 'Google Chrome';
        $ub = "Chrome";
    }
    elseif(preg_match('/Safari/i',$u_agent))
    {
        $bname = 'Apple Safari';
        $ub = "Safari";
    }
    elseif(preg_match('/Opera/i',$u_agent))
    {
        $bname = 'Opera';
        $ub = "Opera";
    }
    elseif(preg_match('/Netscape/i',$u_agent))
    {
        $bname = 'Netscape';
        $ub = "Netscape";
    }
   
    // finally get the correct version number
    $known = array('Version', $ub, 'other');
    $pattern = '#(?<browser>' . join('|', $known) .
    ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
    if (!preg_match_all($pattern, $u_agent, $matches)) {
        // we have no matching number just continue
    }
   
    // see how many we have
    $i = count($matches['browser']);
    if ($i != 1) {
        //we will have two since we are not using 'other' argument yet
        //see if version is before or after the name
        if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
            $version= $matches['version'][0];
        }
        else {
            $version= $matches['version'][1];
        }
    }
    else {
        $version= $matches['version'][0];
    }
   
    // check if we have a number
    if ($version==null || $version=="") {$version="?";}
   
    return array(
        'userAgent' => $u_agent,
        'name'      => $bname,
        'version'   => $version,
        'platform'  => $platform,
        'pattern'    => $pattern
    );
}

// now try it
$ua=getBrowser();
$yourbrowser= "Your browser: " . $ua['name'] . " " . $ua['version'] . " on " .$ua['platform'] . " reports: <br >" . $ua['userAgent'];
//print_r($yourbrowser);
if($ua['name']=='Opera')
{
    ?>
    
<script>
alert('Your Browser Opera does not Support full functionalities of ERP \n Please use Mozilla Firefox or Google Chrome' );
window.close();
</script>
    
    <?php
}

   if($ua['name']=='Internet Explorer')
{
    ?>
    
<script>
alert('Your Browser Internet Explorer does not Support full functionalities of ERP \n Please use Mozilla Firefox or Google Chrome' );
window.close();
</script>
    
    <?php
} 
    
    
   if($ua['name']=='Apple Safari')
{
    ?>
    
<script>
alert('Your Browser Internet Explorer does not Support full functionalities of ERP \n Please use Mozilla Firefox or Google Chrome' );
window.close();
</script>
    
    <?php
} 

   if($ua['name']=='Netscape')
{
    ?>
    
<script>
alert('Your Browser Internet Explorer does not Support full functionalities of ERP \n Please use Mozilla Firefox or Google Chrome' );
window.close();
</script>
    
    <?php
}  
     $sql9="SELECT count( * )
FROM `bio_approval`
WHERE `approval_user` ='".$_SESSION['UserID']."'
AND `taskcompletedstatus` =0" ;
$result9=DB_query($sql9,$db);  
$myrow9=DB_fetch_array($result9);
$count=$myrow9[0]; 
 ?>
		<table width="100%">
			<tr>
			<td valign="top" class="menu_group_area">
				<table width="100%">

					<?php OptionHeadings(); ?>

					<tr>
					<td class="menu_group_items">  <!-- Leads Transactions -->
						<table width="100%" class="table_index">
						       <tr>
						       <td class="menu_group_item">
                                    <?php echo '<p>&bull; <a href="' . $rootpath . '/bio_createleads.php">' . _('Create Leads') . '</a></p>'; ?>                      
						       </td>
						       </tr>

  <tr>
						       <td class="menu_group_item">
                                    <?php echo '<p>&bull; <a href="' . $rootpath . '/bio_lsgreg.php">' . _('LSG Registration') . '</a></p>'; ?>                      
						       </td>
						       </tr>
                               
                               
                               
  <tr>
                               <td class="menu_group_item">
                                    <?php echo '<p>&bull;<b> <a style="font-family:arial;font-size:13px;font-weight:bold;" href="' . $rootpath . '/bio_createdealer.php">' . _('Dealer Registration') . '</a></b></p>'; ?>                      
                               </td>
                               </tr>
                                 <tr>
                               <td class="menu_group_item">
                                    <?php echo '<p>&bull;<b> <a style="font-family:arial;font-size:13px;font-weight:bold;" href="' . $rootpath . '/bio_network_customer.php">' . _('Network Group Registration') . '</a></b></p>'; ?>                      
                               </td>
                               </tr>
                                        <tr>
                               <td class="menu_group_item">
                                    <?php echo '<p>&bull;<b> <a style="font-family:arial;font-size:13px;font-weight:bold;" href="' . $rootpath . '/bio_proposal.php">' . _('Select Plant for Domestic') . '</a></b></p>'; ?>                      
                               </td>
                               </tr>         
                                                             <?php if($_SESSION['designationcode'] =='CCE'){  ?>    
                                                          <tr>
                            <td class="menu_group_item">
                                <?php echo '&bull; <a href="' . $rootpath . '/bio_domTaskview.php">' . _('Domestic Task View') . '</a>'; ?>
                            </td>                        </tr>
                                                                                      <tr>
                                                                                      <tr>
                            <td class="menu_group_item">
                                <?php echo '&bull; <a href="' . $rootpath . '/bio_OLDdocumentmanagement.php">' . _('Old Document Collection') . '</a>'; ?>
                            </td>                        </tr>
                                                                                      <tr>
                                                                                      
                            <td class="menu_group_item">
                                <?php echo '&bull; <a href="' . $rootpath . '/bio_documents.php">' . _('Collect Documents') . '</a>'; ?>
                            </td>                        </tr>                     
                                                    <?php }?>         
                                                      <tr>  
                               
                             <?php if( $_SESSION['designationcode'] =='MD' OR $_SESSION['designationcode'] =='BH' OR $_SESSION['designationcode'] =='SA' OR $_SESSION['designationcode'] =='BDE' OR $_SESSION['designationcode'] =='AD' OR $_SESSION['designationcode'] =='BDM'  OR $_SESSION['designationcode'] =='ABH')
                             {  
                                  if( $_SESSION['UserID']=='bdmtvm1' ||$_SESSION['UserID']=='bdmkoz1' ||$_SESSION['UserID']=='bdmeklm1' ||$_SESSION['UserID']=='bdm_national' ||$_SESSION['UserID']=='businesshead')
                                  {  
                                 ?>  
                             
                                 <tr>
                            <td class="menu_group_item">
                                <?php echo '&bull; <blink><a style="font-family:arial;font-size:13px;font-weight:bold;" href="' . $rootpath . '/bio_leadtaskassign.php">' . _('Assign the Task ') .'('.$assign.')</a></blink>'; ?>
                            </td>
                            </tr>
                            <?php
                                  }
                                  else
                                  {
                                         ?>
                                            <tr>
                            <td class="menu_group_item">
                                <?php echo '&bull; <a style="font-family:arial;font-size:13px;font-weight:bold;" href="' . $rootpath . '/bio_leadtaskassign.php">' . _('Assign the Task ').'</a>'; ?>
                            </td>
                            </tr>
                                         <?php
                                  }
                            
                            ?>
                              
                                                                                      <tr>
                                                                                      
                            <td class="menu_group_item">
                                <?php echo '&bull; <a href="' . $rootpath . '/bio_documents.php">' . _('Collect Documents') . '</a>'; ?>
                            </td>                        </tr>                     
                                   
                             <tr>
                            <td class="menu_group_item">
                                <?php echo '&bull; <a href="' . $rootpath . '/bio_instTaskview.php">' . _('Institutional Daily Tasks') . '</a>'; ?>
                            </td>
                            </tr>    
                             <tr>
                            <td class="menu_group_item">
                                <?php echo '&bull; <a href="' . $rootpath . '/bio_dealerdetail.php?">' . _('Dealer Tasks') . '</a>'; ?>
                            </td>
                            </tr>   
                            <?php if($_SESSION['designationcode'] =='BDM' && $_SESSION['officeid'] ==1){  ?>    
                            
                                       <tr>
                            <td class="menu_group_item">
                                <?php echo '&bull; <a href="' . $rootpath . '/bio_domTaskview.php">' . _('National Domestic Leads') . '</a>'; ?>
                            </td>
                            </tr>       
                            
                                         <?php } ?>  

 <?php if($_SESSION['designationcode'] =='BDM' && $_SESSION['officeid'] !=1){  ?>    
                            
                                       <tr>
                            <td class="menu_group_item">
                                <?php echo '&bull; <a href="' . $rootpath . '/bio_domTaskview.php">' . _('Domestic Email Leads') . '</a>'; ?>
                            </td>
                            </tr>       
                            
                                         <?php } ?>  



                            <tr>
                            <td class="menu_group_item">
                                <?php echo '&bull; <a href="' . $rootpath . '/bio_fp_proposal.php">' . _('Feasibility Proposal') . '</a>'; ?>
                            </td>
                            </tr> <tr>
                            <td class="menu_group_item">
                                <?php echo '&bull; <a href="' . $rootpath . '/bio_feasibilitystudyentry.php">' . _('Feasibility Entry') . '</a>'; ?>
                            </td>
                            </tr>
<tr>
                            <td class="menu_group_item">
                                <?php echo '&bull; <a href="' . $rootpath . '/bio_conceptproposal.php">' . _('Concept Proposal') . '</a>'; ?>
                            </td>
                            </tr>
                              <tr>
                            <td class="menu_group_item">
                                <?php echo '<p>&bull; <a href="' . $rootpath . '/bio_pre_orderdocuments.php">' . _('Add/View Tender Documents') . '</a></p>'; ?>                      
                            </td>
                            </tr> 
                               
                           <tr>
 

                                 <?php } ?>    
                                 
                                 
                                 
                                 
 <?php if( $_SESSION['designationcode'] =='BDE'){ ?>

     <tr>
                            <td class="menu_group_item">
                                <?php echo '&bull; <a href="' . $rootpath . '/bio_instTaskview.php">' . _('Institutional Daily Tasks') . '</a>'; ?>
                            </td>
                            </tr>       
                            <tr>
                             <?php if($_SESSION['officeid'] ==1){  ?>    
                            
                                       <tr>
                            <td class="menu_group_item">
                                <?php echo '&bull; <a href="' . $rootpath . '/bio_domTaskview.php">' . _('National Domestic Leads') . '</a>'; ?>
                            </td>
                            </tr>       
                            
                                         <?php } ?>  
                            <td class="menu_group_item">
                                <?php echo '&bull; <a href="' . $rootpath . '/bio_fp_proposal.php">' . _('Feasibility Proposal') . '</a>'; ?>
                            </td>
                            </tr>
                       

                               <tr>
                               <td class="menu_group_item">
                                    <?php echo '<p>&bull; <a href="' . $rootpath . '/bio_proposal.php">' . _('Select Plant') . '</a></p>'; ?>                      
                               </td>
                               </tr>
                                       <?php } ?>      
                               <?php if( $_SESSION['designationcode'] =='BDM'){ ?>  
                                   <tr>
                            <td class="menu_group_item">
                                <?php echo '&bull; <a href="' . $rootpath . '/bio_documentVerification.php">' . _('Document Verification') . '</a>'; ?>
                            </td>
                            </tr>    
                                  <?php } ?>                  
                     <!---             
                               <tr>
                               <td class="menu_group_item">
                                    <?php echo '<p>&bull; <a href="' . $rootpath . '/bio_approveproposal.php">' . _('Approve Proposals') . '</a></p>'; ?>                      
                               </td>
                               </tr>
         <tr>
                            <td class="menu_group_item">
                                <?php echo '&bull; <a href="' . $rootpath . '/bio_feasibilitystudy.php">' . _('Feasibility Study') . '</a>'; ?>
                            </td>
                            </tr> 
                                                                   </tr>
                                               
                             <tr>
                            <td class="menu_group_item">
                                <?php echo '&bull; <a href="' . $rootpath . '/bio_feasibilitystudyentry.php">' . _('Feasibility Study Entry') . '</a>'; ?>
                            </td>
                            </tr>
                            
                            <tr>
                            <td class="menu_group_item">
                                <?php echo '<p>&bull; <a href="' . $rootpath . '/bio_conceptproposal.php">' . _('Concept Proposal') . '</a></p>'; ?>                      
                            </td>
                            </tr>
                                                     
                            <td class="menu_group_item">
                                <?php echo '&bull; <a href="' . $rootpath . '/bio_detailedprojectproposal.php">' . _('Detailed Project Report') . '</a>'; ?>
                            </td>
                            </tr>     -->
                           
				<tr>
                            <td class="menu_group_item">
                                <?php echo '&bull; <a href="' . $rootpath . '/bio_viewleads.php">' . _('View Leads') . '</a>'; ?>
                            </td>
                            </tr>
                            <tr>
                            <td class="menu_group_item">
                                <?php echo '&bull; <a href="' . $rootpath . '/bio_leads_view.php">' . _('Restore Dropped Leads ') . '</a>'; ?>
                            </td>
                            </tr>


 <?php if( $_SESSION['designationcode'] =='BDM' OR $_SESSION['designationcode'] =='MD' OR $_SESSION['designationcode'] =='BH' OR $_SESSION['designationcode'] =='AD'){ ?>     
            
               
            
            
             <tr>
                            <td class="menu_group_item">
                                <?php echo '&bull; <b><a style="font-family:arial;font-size:13px;font-weight:bold;" href="' . $rootpath . '/bio_dealerview.php">' . _('View Dealer Enquiry') . '</a></b>'; ?>
                            </td>
                            </tr>      
                            
                               <tr>
                            <td class="menu_group_item">
                                <?php echo '&bull; <b><a style="font-family:arial;font-size:13px;font-weight:bold;" href="' . $rootpath . '/bio_childcustomer1.php">' . _('Dealers Customers old') . '</a></b>'; ?>
                            </td>
                            </tr>
                                      <tr>
                            <td class="menu_group_item">
                                <?php echo '&bull; <b><a style="font-family:arial;font-size:13px;font-weight:bold;" href="' . $rootpath . '/dealercust.php">' . _('Dealers Customers') . '</a></b>'; ?>
                            </td>
                            </tr>
                            
                            
                            <tr>
                            <td class="menu_group_item">
                                <?php echo '&bull; <b><a style="font-family:arial;font-size:13px;font-weight:bold;" href="' . $rootpath . '/bio_dealerscustomer.php">' . _('Dealer Customer documents') . '</a></b>'; ?>
                            </td>
                            </tr>
                            
                            <tr>
                            <td class="menu_group_item">
                                <?php echo '&bull; <b><a style="font-family:arial;font-size:13px;font-weight:bold;" href="' . $rootpath . '/bio_dealers_orderReport.php">' . _('Dealer Report') . '</a></b>'; ?>
                            </td>
                            </tr>
                             <tr>
                            <td class="menu_group_item">
                                <?php echo '&bull; <b><a style="font-family:arial;font-size:13px;font-weight:bold;" href="' . $rootpath . '/bio_email.php">' . _('Register Incidents From Mail') . '</a></b>'; ?>
                            </td>
                            </tr>
           <?php } ?>
                            <tr>    

                            <td class="menu_group_item">
                                <?php echo '&bull; <a href="' . $rootpath . '/bio_viewlsg.php">' . _('LSG Projects') . '</a>'; ?>
                            </td>
                            </tr>
    
                                <?php if($_SESSION['designationcode'] =='MD' OR $_SESSION['designationcode'] =='BH'){ 
                                if ($count==0)   {
                                 ?>       
                          
                            
                            <tr>
                            <td class="menu_group_item">
                                <?php echo '&bull; <a href="' . $rootpath . '/bio_approval.php">' . _('Approval') . '</a>'; ?>
                            </td>
                            </tr>
                                            <?php }
                                            else { ?>
                                                     <tr>
                            <td class="menu_group_item">
                                <?php echo '&bull;<b><blink> <a style="font-family:arial;font-size:13px;font-weight:bold;" href="' . $rootpath . '/bio_approval.php">' . _('Approval ') .'('.$count.')</a></blink></b>'; ?>
                            </td>
                            </tr>
                                            <?php } ?>

                                  <?php }?>                               <tr>
                            <td class="menu_group_item">
                                <?php echo '&bull; <b><a style="font-family:arial;font-size:13px;font-weight:bold;" href="' . $rootpath . '/CustomerReceipt.php?NewReceipt=Yes&Type=Customer">' . _('Create Customer Receipt') . '</a></b>'; ?>
                            </td>
                            </tr>
                            <tr>
                            <td class="menu_group_item">
                                <?php echo '&bull; <a href="' . $rootpath . '/bio_multiplePayment.php">' . _('Receive Payment') . '</a>'; ?>
                            </td>
                            </tr>
                            
                           
                            

                    
						<tr> <td class="menu_group_item">
                                <?php echo '&bull; <a href="' . $rootpath . '/bio_complaint_register.php">' . _('New Complaint Register') . '</a>'; ?>
                            </td>
                            </tr>
	
							<tr>
                            <td class="menu_group_item">
                                <?php echo '&bull; <a href="' . $rootpath . '/bio_nwinstallationstatus.php">' . _(' Followup Calls') . '</a>'; ?>
                            </td>
                            </tr>            
                                  <?php if($_SESSION['designationcode'] =='AD' OR $_SESSION['designationcode'] =='MD' OR $_SESSION['designationcode'] =='BDM' ){  ?>  
                      
	 
  <tr>
                            <td class="menu_group_item">
                                <?php echo '&bull; <a href="' . $rootpath . '/bio_viewmails.php">' . _(' View Mails') . '</a>'; ?>
                            </td>
                            </tr> 
                                                                                  <tr>
                            <td class="menu_group_item">
                                <?php echo '&bull; <a href="' . $rootpath . '/bio_incidentList.php">' . _('View Allotted Complaints') . '</a>'; ?>
                            </td>
                           </tr>
                      
                                                                   <?php } else {
                                                                      ?> 
                                                                                                                              <tr>
                            <td class="menu_group_item">
                                <?php echo '&bull; <a href="' . $rootpath . '/bio_incidentList.php">' . _('View Allotted Complaints') . '</a>'; ?>
                            </td>
                                                                                                                                                                                     
                            </tr>                                       <?php
                                                                   }
                                                                       ?>    
                                                                      
                                                                       <tr>
                            <td class="menu_group_item">
                                <?php echo '&bull; <a href="' . $rootpath . '/cdmsurveynew.php">' . _('CDM Client List') . '</a>'; ?>
                            </td>
                            </tr>
                              <tr>
                            <td class="menu_group_item">
                                <?php echo '&bull; <a href="' . $rootpath . '/CDMlist.php">' . _('CDM Survey Customers') . '</a>'; ?>
                            </td>
                            </tr>  
                            <tr>
                            <td class="menu_group_item">
                                <?php echo '&bull; <a href="' . $rootpath . '/cdmdup.php">' . _('IORA List') . '</a>'; ?>
                            </td>
                            </tr> 
                               <tr>
                            <td class="menu_group_item">
                                <?php echo '&bull; <a href="' . $rootpath . '/splist.php">' . _('Special List') . '</a>'; ?>
                            </td>
                            </tr>
  <?php   if($_SESSION['UserID']=="admin")
  {?>
       <tr>
                            <td class="menu_group_item">
                                <?php echo '&bull;<blink> <a style="font-weight:bold;color:red;" href="' . $rootpath . '/clientreport.php">' . _('Year wise Customer Report') . '</a></blink>'; ?>
                            </td>
                                      </tr><tr>
                            <td class="menu_group_item">
                                <?php echo '&bull;<blink> <a style="font-weight:bold;color:red;" href="' . $rootpath . '/taskyear.php">' . _('Report Demo') . '</a></blink>'; ?>
                            </td>
                            </tr>
<?php
  }
                                                                                       ?>
                                                         <?php if( $_SESSION['designationcode'] =='CCE'){ ?>
                                                                      <tr>
                            <td class="menu_group_item">
                                <?php echo '&bull; <a href="' . $rootpath . '/bio_incidentList.php">' . _('View Complaints') . '</a>'; ?>
                            </td>
                            </tr>   
                                                                                                                        <?php }?>  
                            
							<!-- <tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/PcAssignCashToTab.php">' . _('Assign Cash to PC Tab') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/PcClaimExpensesFromTab.php' . SID . '">' . _('Claim Expenses From PC Tab') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/PcAuthorizeExpenses.php">' . _('Expenses Authorisation') . '</a></p>'; ?>
							</td>
							</tr> -->
						</table>
					</td>
					<td class="menu_group_items">  <!-- Leads Inquiries -->
						<table width="100%" class="table_index">
							<!--<tr>
							<td class="menu_group_item">
								General Report
							</td>
							</tr> -->
 <?php if( $_SESSION['designationcode'] =='MD' OR $_SESSION['designationcode'] =='BH' OR $_SESSION['designationcode'] =='SA' OR $_SESSION['designationcode'] =='AD' OR $_SESSION['designationcode'] =='BDM' OR $_SESSION['designationcode'] =='BDE' OR $_SESSION['designationcode'] =='ABH'){  ?>  
                          
                            <tr>
                            <td class="menu_group_item">
                                <?php echo '&bull; <a href="' . $rootpath . '/bio_consolidated_leadreport.php">' . _('Lead Status Report') . '</a>'; ?>
                            </td>
                            </tr>  
                                  <tr>
                            <td class="menu_group_item">
                                <?php echo '&bull; <a href="' . $rootpath . '/bio_ordered_plantstatus.php">' . _('Plant Status Report') . '</a>'; ?>
                            </td>
                            </tr> 
                              <?php }?>                 
                           <?php if( $_SESSION['designationcode'] =='MD' OR $_SESSION['designationcode'] =='BH' OR $_SESSION['designationcode'] =='SA' OR $_SESSION['designationcode'] =='AD' OR $_SESSION['designationcode'] =='BDM' OR $_SESSION['designationcode'] =='BDE' OR $_SESSION['designationcode'] =='ABH'){  ?>
                            <tr>
                            <td class="menu_group_item">
                                <?php echo '&bull; <a href="' . $rootpath . '/bio_instTaskReport.php">' . _('Institution Task Register') . '</a>'; ?>
                            </td>
                            </tr>  
                             </tr>
            <?php if($_SESSION['designationcode'] =='MD' OR $_SESSION['designationcode'] =='BH' OR $_SESSION['designationcode'] =='SA' OR $_SESSION['designationcode'] =='AD'){  ?>  
 <tr>
                            <td class="menu_group_item">
                                <?php echo '<p>&bull; <a href="' . $rootpath . '/bio_emailleads.php">' . _('Email Lead List') . '</a></p>'; ?>
                            </td>
                            </tr>
 <tr>
                            <td class="menu_group_item">
                                <?php echo '<p>&bull; <a href="' . $rootpath . '/bio_clientlist1.php">' . _('Client List') . '</a></p>'; ?>
                            </td>
                            </tr>



<?php }?>

                                                                                                                                                          
                           <!-- 
                            <tr>
                            <td class="menu_group_item">
                                <?php echo '<p>&bull; <a href="' . $rootpath . '/bio_leadreportframe.php">' . _('Lead Report') . '</a></p>'; ?>
                            </td>
                            </tr>-->
                            
                                   <?php }?>
                            <tr>
                            <td class="menu_group_item">
                                <?php echo '<p>&bull; <a href="' . $rootpath . '/bio_dprint_A5p.php">' . _('Print Reciept') . '</a></p>'; ?>
                            </td>
                            </tr>
                            
                          <!---  <tr>
                            <td class="menu_group_item"> 
                                <?php echo '<p>&bull; <a href="' . $rootpath . '/bio_dfeaprint_A5p.php">' . _('Print Feasibility Reciept') . '</a></p>'; ?>
                            </td>
                            </tr>  -->
                            
                         <!---
                            
                            <tr>
                            <td class="menu_group_item"> 
                                <?php echo '<p>&bull; <a href="' . $rootpath . '/bio_activeleads_A5p.php">' . _('Active Leads') . '</a></p>'; ?>
                            </td>
                            </tr>
                            
                            <tr>
                            <td class="menu_group_item"> 
                                <?php echo '<p>&bull; <a href="' . $rootpath . '/bio_inactiveleads.php">' . _('Inactive leads') . '</a></p>'; ?>
                            </td>
                            </tr>
                            
                            <tr>
                            <td class="menu_group_item"> 
                                <?php echo '<p>&bull; <a href="' . $rootpath . '/bio_passiveleads_A5p.php">' . _('Passive Leads') . '</a></p>'; ?>
                            </td>
                            </tr>  -->
                                                                <?php if($_SESSION['designationcode'] !='CCE'){  ?>       

                                     <tr>
                            <td class="menu_group_item">
                                <?php echo '&bull; <a href="' . $rootpath . '/bio_dealersaccountdetails.php">' . _('Dealer Orders') . '</a>'; ?>
                            </td>
                            </tr>                    
                              <tr> <tr>
                            <td class="menu_group_item">
                                <?php echo '&bull; <a href="' . $rootpath . '/bio_document_archiving1.php">' . _('Document Archieving') . '</a>'; ?>
                            </td>
                            </tr>                    
                              <tr>
                            <td class="menu_group_item">
                                <?php echo '&bull; <a href="' . $rootpath . '/bio_taskReport.php">' . _('Task Report') . '</a>'; ?>
                            </td>
                            </tr>          
                                       <tr>
                            <td class="menu_group_item">
                                <?php echo '&bull; <a href="' . $rootpath . '/bio_orderDocuments.php">' . _('Client/Document Report') . '</a>'; ?>
                            </td>
                            </tr>         
                                       <?php }?>
                                       
                        <!--    
                            <tr>
                            <td class="menu_group_item"> 
                                <?php echo '<p>&bull; <a href="' . $rootpath . '/bio_activeproposals_A5p.php">' . _('Active Proposals') . '</a></p>'; ?>
                            </td>
                            </tr>
                            
                            <tr>
                            <td class="menu_group_item"> 
                                <?php echo '<p>&bull; <a href="' . $rootpath . '/bio_inactiveProposal.php">' . _('Inactive Proposal') . '</a></p>'; ?>
                            </td>
                            </tr> 
                            
                            <tr>
                            <td class="menu_group_item"> 
                                <?php echo '<p>&bull; <a href="' . $rootpath . '/bio_passiveproposals_A5p.php">' . _('Passive Proposals') . '</a></p>'; ?>
                            </td>
                            </tr>
                            
                            <tr>
                            <td class="menu_group_item"> 
                                <?php echo '<p>&bull; <a href="' . $rootpath . '/bio_activefeasibilitystudyproposals.php">' . _('Active FS') . '</a></p>'; ?>
                            </td>
                            </tr>
                            
                            <tr>
                            <td class="menu_group_item"> 
                                <?php echo '<p>&bull; <a href="' . $rootpath . '/bio_inactiveFS.php">' . _('Inactive FS') . '</a></p>'; ?>
                            </td>
                            </tr>
                            
                            <tr>
                            <td class="menu_group_item"> 
                                <?php echo '<p>&bull; <a href="' . $rootpath . '/bio_passivefeasibilitystudyproposals.php">' . _('Passive FS') . '</a></p>'; ?>
                            </td>
                            </tr>
                            
                            <tr>
                            <td class="menu_group_item"> 
                                <?php echo '<p>&bull; <a href="' . $rootpath . '/bio_CPactiveLeads.php">' . _('Active ConceptProposal') . '</a></p>'; ?>
                            </td>
                            </tr>
                            
                            <tr>
                            <td class="menu_group_item"> 
                                <?php echo '<p>&bull; <a href="' . $rootpath . '/bio_CPinactive.php">' . _('Inactive ConceptProposal') . '</a></p>'; ?>
                            </td>
                            </tr>
                            
                            <tr>
                            <td class="menu_group_item"> 
                                <?php echo '<p>&bull; <a href="' . $rootpath . '/bio_CPpassiveLeads.php">' . _('Passive ConceptProposal') . '</a></p>'; ?>
                            </td>
                            </tr>
                            
                            <tr>
                            <td class="menu_group_item"> 
                                <?php echo '<p>&bull; <a href="' . $rootpath . '/bio_DPRactiveLeads.php">' . _('Active DPR') . '</a></p>'; ?>
                            </td>
                            </tr>
                            
                            <tr>
                            <td class="menu_group_item"> 
                                <?php echo '<p>&bull; <a href="' . $rootpath . '/bio_inactiveDPR.php">' . _('Inactive DPR') . '</a></p>'; ?>
                            </td>
                            </tr> 
            
                            <tr>
                            <td class="menu_group_item"> 
                                <?php echo '<p>&bull; <a href="' . $rootpath . '/bio_DPRpassiveLeads.php">' . _('Passive DPR') . '</a></p>'; ?>
                            </td>
                            </tr>       <tr>
                            <td class="menu_group_item">
                                <?php echo '&bull; <a href="' . $rootpath . '/bio_usersassignedtasks.php">' . _('User Assigned Tasks') . '</a>'; ?>
                            </td>
                            </tr>
                        -->                               
                            <tr>
                            <td class="menu_group_item"> 
                                <?php echo '<p>&bull; <a href="' . $rootpath . '/bio_cashbook_A5L.php">' . _('Cash Book') . '</a></p>'; ?>
                            </td>
                            </tr>  
                            
                            <tr>
                               <td class="menu_group_item">
                                    <?php echo '<p>&bull; <a href="' . $rootpath . '/bio_regionwise_Search4.php">' . _('Consolidated Document Register') . '</a></p>'; ?>                      
                               </td>
                            </tr>      
                            
                          <tr>
                               <td class="menu_group_item">
                                    <?php echo '<p>&bull; <a href="' . $rootpath . '/bio_installationstatusdoc.php">' . _('Followup call Report') . '</a></p>'; ?>                      
                               </td>
                            </tr>         
           <?php if($_SESSION['UserID']=="admin" || $_SESSION['UserID']=="exe_asst" || $_SESSION['UserID']=="useradmin"|| $_SESSION['UserID']=="account1"){ ?>               
                          <tr>
                               <td class="menu_group_item">
                                    <?php echo '<p>&bull; <a href="' . $rootpath . '/bio_incentive_inst_close2.php">' . _('Inst Incentive View') . '</a></p>'; ?>                      
                               </td>
                            </tr>  
                                      
                          <tr>
                               <td class="menu_group_item">
                                    <?php echo '<p>&bull; <a href="' . $rootpath . '/bio_incentive_dom_close.php">' . _('Dom Incentive View') . '</a></p>'; ?>                      
                               </td>
                            </tr>  
                          <tr>
                               <td class="menu_group_item">
                                    <?php echo '<p>&bull; <a href="' . $rootpath . '/bio_incentive_leadsource.php">' . _('Leadsource Incentive View') . '</a></p>'; ?>                      
                               </td>
                          </tr> 
                          <tr>
                               <td class="menu_group_item">
                                    <?php echo '<p>&bull; <a href="' . $rootpath . '/bio_incentive_office.php">' . _('Office Incentive View') . '</a></p>'; ?>                      
                               </td>
                          </tr> 
                      <?php } ?>    
                            <tr>
                               <td class="menu_group_item">
                                    <?php echo '<p>&bull; <a href="' . $rootpath . '/bio_installeddate.php">' . _('Installation Report') . '</a></p>'; ?>                      
                               </td>
                            </tr>  
                            
                            <tr>
                               <td class="menu_group_item">
                                    <?php echo '<p>&bull; <a href="' . $rootpath . '/bio_customerlist.php">' . _('Payment List') . '</a></p>'; ?>                      
                               </td>
                            </tr>  
                            
                            <tr>
                            <td class="menu_group_item">
                                <?php echo '&bull; <a href="' . $rootpath . '/bio_customer_filename.php">' . _('Archived File List') . '</a>'; ?>
                            </td>
                            </tr>   
                            <tr>
                            <td class="menu_group_item">
                                <?php echo '&bull; <a href="' . $rootpath . '/bio_view_documentlist.php">' . _('Archived Document List') . '</a>'; ?>
                            </td>
                            </tr>    <tr>
                            <td class="menu_group_item">
                                <?php echo '&bull; <a href="' . $rootpath . '/bio_filename.php">' . _('Archived File List(District wise)') . '</a>'; ?>
                            </td>
                            </tr>
                                       <tr>
                            <td class="menu_group_item">
                                <?php echo '&bull; <a href="' . $rootpath . '/bio_arclocation_list.php">' . _('LSG File Locations') . '</a>'; ?>
                            </td>
                            </tr>     
                               <tr>
                            <td class="menu_group_item">
                                <?php echo '&bull; <a href="' . $rootpath . '/bio_pending_orderlist.php">' . _('Installation Pending List') . '</a>'; ?>
                            </td>
                            </tr>
                             <?php if($_SESSION['UserID']=='ccetvm1' || $_SESSION['UserID']=='ccetvm1' || $_SESSION['UserID']=='ccetvm2' || $_SESSION['UserID']=='cceeklm1' || $_SESSION['UserID']=='cceeklm2' || $_SESSION['UserID']=='ccekoz1' || $_SESSION['UserID']=='exe_asst' || $_SESSION['UserID']=='asst_bh1'|| $_SESSION['UserID']=='admin'){  ?>  
                             <tr>
                            <td class="menu_group_item">
                                <?php echo '<p>&bull; <a style="font-weight:bold" href="' . $rootpath . '/bio_ns.php">' . _('Domestic Customer List') . '</a></p>'; ?>
                            </td>
                            </tr>             <?php
                                   }
                                              ?>

 <?php 
if($_SESSION['UserID']=='asst_bh1' || $_SESSION['UserID']=='exe_asst' || $_SESSION['UserID']=='admin'){ 

 
?> 
 <tr>
                            <td class="menu_group_item">
                                <?php echo '<p>&bull; <a style="font-weight:bold; color:blue" href="' . $rootpath . '/report1copy.php">' . _('Report 1') . '</a></p>'; ?>
                            </td>
                            </tr> 
 <tr>
                            <td class="menu_group_item">
 <?php echo '<p>&bull; <a style="font-weight:bold; color:blue" href="' . $rootpath . '/bio_report_lead.php">' . _('Report 2') . '</a></p>'; ?>
                            </td>
                            </tr> 
 <tr>
                            <td class="menu_group_item">
                                <?php echo '<p>&bull; <a style="font-weight:bold; color:blue" href="' . $rootpath . '/bio_leadSource_report.php">' . _('Lead Source report') . '</a></p>'; ?>
                            </td>
                            </tr> 
                            <tr>
                            <td class="menu_group_item">
                                <?php echo '<p>&bull; <a style="font-weight:bold; color:blue" href="' . $rootpath . '/report4new.php">' . _('INCENTIVE REPORT1') . '</a></p>'; ?>
                            </td>
                            </tr> 
                                 <tr>
                            <td class="menu_group_item">
                                <?php echo '<p>&bull; <a style="font-weight:bold; color:blue" href="' . $rootpath . '/report3.php">' . _('INCENTIVE REPORT2') . '</a></p>'; ?>
                            </td>
                            </tr> 
                                 <tr>
                            <td class="menu_group_item">
                                <?php echo '<p>&bull; <a style="font-weight:bold; color:blue" href="' . $rootpath . '/capacityreport.php">' . _('CAPACITY REPORT') . '</a></p>'; ?>
                            </td>
                            </tr> 
                               <tr>
                            <td class="menu_group_item">
                                <?php echo '<p>&bull; <a style="font-weight:bold; color:blue" href="' . $rootpath . '/distreport.php">' . _('DISTRICT WISE REPORT') . '</a></p>'; ?>
                            </td>
                            </tr> 
                              <tr>
                            <td class="menu_group_item">
                                <?php echo '<p>&bull; <a style="font-weight:bold; color:blue" href="' . $rootpath . '/MIS2.php">' . _('MIS REPORT1') . '</a></p>'; ?>
                            </td>
                            </tr> 
                              <tr>
                            <td class="menu_group_item">
                                <?php echo '<p>&bull; <a style="font-weight:bold; color:blue" href="' . $rootpath . '/MIS3.php">' . _('MIS REPORT2') . '</a></p>'; ?>
                            </td>
                            </tr> 
 <?php
                                   }
                                              ?>


                                  <!--      <tr>
                            <td class="menu_group_item">
                                <?php echo '&bull; <a href="' . $rootpath . '/bio_task_escalation.php">' . _('Escalated Tasks') . '</a>'; ?>
                            </td>
                            </tr>                                        -->
                                                        <!-- <tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/PcReportTab.php">' . _('PC Tab General Report') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo GetRptLinks('prch'); ?>
							</td>
							</tr> -->
					</table>
					</td>
					<td class="menu_group_items">   <!-- Leads Maintenance -->
						<table width="100%" class="table_index">
                        
            <?php if($_SESSION['designationcode'] =='MD' OR $_SESSION['designationcode'] =='BH' OR $_SESSION['designationcode'] =='SA' OR $_SESSION['designationcode'] =='AD'){  ?>  

                                                    <tr>
                                                    
                            <td class="menu_group_item">
                            
                                <?php echo '&bull; <a href="' . $rootpath . '/bio_country.php">' . _('Country') . '</a>'; ?>
                            </td>
                            </tr>
                            
                             <tr>
                            <td class="menu_group_item">
                                <?php echo '&bull; <a href="' . $rootpath . '/bio_employee.php">' . _('Employee Master') . '</a>'; ?>
                            </td>
                            </tr>                       
                            
                            <tr>
                            <td class="menu_group_item">
                                <?php echo '&bull; <a href="' . $rootpath . '/bio_state.php">' . _('State') . '</a>'; ?>
                            </td>
                            </tr>
                                <tr>
                            <td class="menu_group_item">
                                <?php echo '&bull; <a href="' . $rootpath . '/bio_district.php">' . _('District') . '</a>'; ?>
                            </td>
                            </tr>
                            
							<tr>
							<td class="menu_group_item">
								<?php echo '&bull; <a href="' . $rootpath . '/bio_enqtypes.php">' . _('Enquiry Types') . '</a>'; ?>
							</td>
							</tr>
							<tr>
                            <td class="menu_group_item">
                                <?php echo '&bull; <a href="' . $rootpath . '/bio_feedstocks.php">' . _('Feed Stocks') . '</a>'; ?>
                            </td>
                            </tr><tr>
                            <td class="menu_group_item">
                                <?php echo '&bull; <a href="' . $rootpath . '/bio_feedsource.php">' . _('Feed Stock Types') . '</a>'; ?>
                            </td>
                            </tr>
                            <tr>
							<td class="menu_group_item">
								<?php echo '&bull; <a href="' . $rootpath . '/bio_outputtypes.php">' . _('Output Types') . '</a>'; ?>
							</td>
							</tr> 
							<tr>
							<td class="menu_group_item">
								<?php echo '&bull; <a href="' . $rootpath . '/bio_schemes.php">' . _('Schemes') . '</a>'; ?>
							</td>
							</tr>
 							<tr>
                            			<td class="menu_group_item">
                                			<?php echo '&bull; <a href="' . $rootpath . '/bio_subsidymaster.php">' . _('Subsidy') . '</a>'; ?>
                            			</td>
                            			</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '&bull; <a href="' . $rootpath . '/bio_sourcetypes.php">' . _('Source Types') . '</a>'; ?>
							</td>
							</tr>
                            
                                                            <tr>
                            <td class="menu_group_item">
                                <?php echo '&bull; <a href="' . $rootpath . '/bio_sourceproperty.php">' . _('Lead Source Property') . '</a>'; ?>
                            </td>
                            </tr>
                            
                            
                            <tr>
                            <td class="menu_group_item">
                                <?php echo '&bull; <a href="' . $rootpath . '/bio_leadsources.php">' . _('Lead Sources') . '</a>'; ?>
                            </td>
                            </tr>
                                 <tr>
                            <td class="menu_group_item">
                                <?php echo '&bull; <a href="' . $rootpath . '/bio_leadteams.php">' . _('Lead teams') . '</a>'; ?>
                            </td>
                            </tr>
                                                        <tr>
                            <td class="menu_group_item">
                                <?php echo '&bull; <a href="' . $rootpath . '/bio_costcentres.php">' . _('Costcentres') . '</a>'; ?>
                            </td>
                            </tr>
                              <tr>
                            <td class="menu_group_item">
                                <?php echo '&bull; <a href="' . $rootpath . '/compla.php">' . _('Complaint type Master') . '</a>'; ?>
                            </td>
                            </tr>
                            <tr>
                            <td class="menu_group_item">
                                <?php echo '&bull; <a href="' . $rootpath . '/bio_feedstocksourcetypes.php">' . _('Feed Stock Source Master') . '</a>'; ?>
                            </td>
                            </tr>
                            
                            <tr>
                            <td class="menu_group_item">
                                <?php echo '&bull; <a href="' . $rootpath . '/bio_changepolicy.php">' . _('Policy Master') . '</a>'; ?>
                            </td>
                            </tr>
                            
                             <tr>
                            <td class="menu_group_item">
                                <?php echo '&bull; <a href="' . $rootpath . '/bio_reasonmaster.php">' . _('Reasons Master') . '</a>'; ?>
                            </td>
                            </tr>
                            <tr>
                            <td class="menu_group_item">
                                <?php echo '&bull; <a href="' . $rootpath . '/bio_mailbody_master.php">' . _('Email Body Master') . '</a>'; ?>
                            </td>
                            </tr>
                             <tr>
                              <tr>
                            <td class="menu_group_item">
                                <?php echo '&bull; <a href="' . $rootpath . '/bio_ProductServices.php">' . _('ProductServices Master') . '</a>'; ?>
                            </td>
                            </tr>
                             <tr>
                            <td class="menu_group_item">
                                <?php echo '&bull; <a href="' . $rootpath . '/bio_taskreason.php">' . _('Task Remark') . '</a>'; ?>
                            </td>
                            </tr>
                               <tr>
                            <td class="menu_group_item">
                                <?php echo '&bull; <a href="' . $rootpath . '/bio_target.php">' . _('Marketting Target Master') . '</a>'; ?>
                            </td>
                            </tr>
                          
                            
              <?php if($_SESSION['UserID']=="admin" || $_SESSION['UserID']=="exe_asst" || $_SESSION['UserID']=="useradmin"){ ?>                
                            
                             <tr>
                            <td class="menu_group_item">
                                <?php echo '&bull; <a href="' . $rootpath . '/bio_designation.php">' . _('Designation') . '</a>'; ?>
                            </td>
                            </tr>
                          
                                     <tr>
                            <td class="menu_group_item">
                                <?php echo '&bull; <a href="' . $rootpath . '/bio_target_leadsources.php ">' . _('Leadsource Target Master') . '</a>'; ?>
                            </td>
                            </tr>
                                                             <tr>
                            <td class="menu_group_item">
                                <?php echo '&bull; <a href="' . $rootpath . '/bio_target_offices.php ">' . _('Office Target Master') . '</a>'; ?>
                            </td>
                            </tr>
                                                         <tr>
                            <td class="menu_group_item">
                                <?php echo '&bull; <a href="' . $rootpath . '/bio_Achievement_outpolicy.php">' . _('Leadsource Incentive Policy') . '</a>'; ?>
                            </td>
                            </tr>
                             <tr>
                            <td class="menu_group_item">
                                <?php echo '&bull; <a href="' . $rootpath . '/bio_Achievement_inpolicy.php">' . _('Marketting Incentive Policy') . '</a>'; ?>
                            </td>
                            </tr>
                             <tr>
                            <td class="menu_group_item">
                                <?php echo '&bull; <a href="' . $rootpath . '/bio_Achievement_office.php ">' . _('Office Incentive Policy') . '</a>'; ?>
                            </td>
                            </tr>
                            
                            <?php } ?>
                        <!--    
                            
                          <tr>
                            <td class="menu_group_item">
                                <?php echo '&bull; <a href="' . $rootpath . '/bio_signatories.php">' . _('Authrised Signatory and Approval Authority') . '</a>'; ?>
                            </td>
                            </tr>

                         -->   
                           
                            
                            <tr>
                            <td class="menu_group_item">
                                <?php echo '&bull; <a href="' . $rootpath . '/bio_costbenefitmaster.php">' . _('Cost Benefit Master') . '</a>'; ?>
                            </td>
                            </tr>
                            
                            <tr>
                            <td class="menu_group_item">
                                <?php echo '&bull; <a href="' . $rootpath . '/addlocation.php">' . _('Document Archiving Location') . '</a>'; ?>
                            </td>
                            </tr>
                            
                            
                           <tr>
                            <td class="menu_group_item">
                                <?php echo '&bull; <a href="' . $rootpath . '/bio_remarks.php">' . _('Remarks') . '</a>'; ?>
                            </td>
                            </tr>
                             <tr>
                            <td class="menu_group_item">
                                <?php echo '&bull; <a href="' . $rootpath . '/bio_schedule_master.php">' . _('Schedule Master') . '</a>'; ?>
                            </td>
                            </tr>
                           <tr>
                            <td class="menu_group_item">
                                <?php echo '&bull; <a href="' . $rootpath . '/bio_schedule.php">' . _('Create Schedule') . '</a>'; ?>
                            </td>
                            </tr> 
                                  
                           <tr>
                            <td class="menu_group_item">
                                <?php echo '&bull; <a href="' . $rootpath . '/bio_documentlist.php">' . _('Document Master') . '</a>'; ?>
                            </td>
                            </tr>           <tr>
                            <td class="menu_group_item">
                                <?php echo '&bull; <a href="' . $rootpath . '/bio_incidentType.php">' . _('Incident Type Master') . '</a>'; ?>
                            </td>
                            </tr>   <tr>
                            <td class="menu_group_item">
                                <?php echo '&bull; <a href="' . $rootpath . '/bio_assignDepartment.php">' . _('Incident Handling Department') . '</a>'; ?>
                            </td>
                            </tr>                              </tr>   <tr>
                            <td class="menu_group_item">
                                <?php echo '&bull; <a href="' . $rootpath . '/bio_designationfilters.php">' . _('Users Filtering Criteria') . '</a>'; ?>
                            </td>
                            </tr>                               </tr>                              </tr>   <tr>
                            <td class="menu_group_item">
                                <?php echo '&bull; <a href="' . $rootpath . '/bio_institutionframe.php">' . _('Institution Type Master') . '</a>'; ?>
                            </td>
                            </tr> 
				<td class="menu_group_item">
                                <?php echo '&bull; <a href="' . $rootpath . '/bio_inst_feedstockMaster.php">' . _('Institution Type & Feedstock source Master') . '</a>'; ?>
                            </td>
                            </tr>
                                   </tr> 
                <td class="menu_group_item">
                                <?php echo '&bull; <a href="' . $rootpath . '/bio_optionalitemdetails.php">' . _('Optional Item Master') . '</a>'; ?>
                            </td>
                            </tr>
                            <?php }?>
						</table>
					</td>
					</tr>
				</table>
			</td>
			</tr>
		</table>
	<?php
		break;

	case 'orders': //Sales Orders
	?>

		<table width="100%">
			<tr>
			<td class="menu_group_area">
				<table width="100%" >

					<?php
  					// displays the main area headings
					  OptionHeadings();
					?>

					<tr>
					<td class="menu_group_items">  <!-- Orders transaction options -->
						<table width="100%" class="table_index">
						<!---	<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/SaleOrderRegistration.php?NewOrder=Yes">' . _('Create An Order or Quotation') . '</a></p>'; ?>
							</td>
							</tr>-->

                            <tr>
                            <td class="menu_group_item">
                                <?php echo '<p>&bull; <a href="' . $rootpath . '/bio_SelectCustomer.php">' . _('Create Customer in Sales from Leads') . '</a></p>'; ?>
                            </td>
                            </tr>
                                                        <tr>
                            <td class="menu_group_item">
                                <?php echo '<p>&bull; <a href="' . $rootpath . '/SelectOrderItems.php?NewOrder=Yes">' . _('Place an Order') . '</a></p>'; ?>
                            </td>
                            </tr>
                              <tr>
                            <td class="menu_group_item">
                                <?php echo '<p>&bull; <a href="' . $rootpath . '/bio_createOrderfromleads.php?NewOrder=Yes">' . _('Create Order in Sales from Leads') . '</a></p>'; ?>
                            </td>
                            </tr>
                                     <tr>
                            <td class="menu_group_item">
                                <?php echo '<p>&bull; <a href="' . $rootpath . '/bio_viewDoclist.php">' . _('Document Management') . '</a></p>'; ?>
                            </td>
                            </tr>
                             <tr>       <tr>
                            <td class="menu_group_item">
                                <?php echo '<p>&bull; <a href="' . $rootpath . '/bio_pendingDoclist.php">' . _('Pending Documents') . '</a></p>'; ?>
                            </td>
                            </tr>         
                                  
                                                                <?php if($_SESSION['designationcode'] !='CCE'){  ?>  

						<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/CounterSales.php?' .sid . '">' . _('Enter Counter Sales') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/PDFPickingList.php?' .sid . '">' . _('Print Picking Lists') . '</a></p>'; ?>
							</td>
							</tr>
						<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/SelectSalesOrder.php">' . _('Outstanding Sales Orders/Quotations') . '</a></p>'; ?>
							</td>
							</tr>                           <tr>
                            <td class="menu_group_item">
                                <?php echo '<p>&bull; <a href="' . $rootpath . '/so_approvals.php">' . _('Sale Order Approval') . '</a></p>'; ?>
                            </td>
                            </tr>        <?php } ?>
							<!--<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/SpecialOrder.php?NewSpecial=Yes">' . _('Special Order') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/SelectRecurringSalesOrder.php?' .sid . '">' . _('Recurring Order Template') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/RecurringSalesOrdersProcess.php?' .sid . '">' . _('Process Recurring Orders') . '</a></p>'; ?>
							</td>
							</tr>-->
						</table>
					</td>
					<td class="menu_group_items"> <!-- Orders Inquiry options -->
						<table width="100%" class="table_index">
                        <?php if($_SESSION['designationcode'] !='CCE'){  ?>   
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/SelectCompletedOrder.php">' . _('Order Inquiry') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/PDFPriceList.php">' . _('Print Price Lists') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>     <tr>
                            <td class="menu_group_item">
                                <?php echo '<p>&bull; <a href="' . $rootpath . '/bio_prices.php">' . _('View Price Lists') . '</a></p>'; ?>
                            </td>
                            </tr>
                            <tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/PDFOrderStatus.php">' . _('Order Status Report') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/PDFOrdersInvoiced.php">' . _('Orders Invoiced Reports') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/DailySalesInquiry.php">' . _('Daily Sales Inquiry') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/SalesByTypePeriodInquiry.php">' . _('Sales By Sales Type Inquiry') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/SalesCategoryPeriodInquiry.php">' . _('Sales By Category Inquiry') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/SalesTopItemsInquiry.php">' . _('Top Sellers Inquiry') . '</a></p>'; ?>
							</td>
							</tr>
							
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/PDFDeliveryDifferences.php">' . _('Order Delivery Differences Report') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/PDFDIFOT.php">' . _('Delivery In Full On Time (DIFOT) Report') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/SalesInquiry.php">' . _('Sales Order Detail Or Summary Inquiries') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/TopItems.php">' . _('Top Sales Items Report') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/PDFLowGP.php">' . _('Sales With Low Gross Profit Report') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo GetRptLinks('ord'); ?>
							</td>
							</tr>
                                    <?php } ?>  
						</table>
					</td>

					<td class="menu_group_items"> <!-- Orders Maintenance options -->
						<table width="100%">
							<!--<tr>
					<td class="menu_group_items">
						<table width="100%" class="table_index">
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/SelectContract.php">' . _('Select Contract') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/Contracts.php">' . _('Create Contract') . '</a></p>'; ?>
							</td>
							</tr>
						</table>
					</td>
					</tr>-->
				</table>
			</td>
			</tr>
		</table>
	<?php
		break;
	/* ****************** END OF ORDERS MENU ITEMS **************************** */


	Case 'AR': //Debtors Module

	unset($ReceiptBatch);
	unset($AllocTrans);

	?>
		<table width="100%">
			<tr>
			<td valign="top" class="menu_group_area">
				<table width="100%">

					<?php OptionHeadings(); ?>

					<tr>
					<td class="menu_group_items">
						<table width="100%"class="table_index">   <tr>
                            <td class="menu_group_item">
                                <?php echo '<p>&bull; <a href="' . $rootpath . '/bio_delivered_item_invoice.php">' . _('Invoice Delivered Item') . '</a></p>'; ?>
                            </td>
                            </tr>
                            <tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/SelectSalesOrder.php">' . _('Select Order to Invoice') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/SelectCreditItems.php?NewCredit=Yes">' . _('Create A Credit Note') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/CustomerReceipt.php?NewReceipt=Yes&Type=Customer">' . _('Enter Receipts') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">

								<?php echo '<p>&bull; <a href="' . $rootpath . '/CustomerAllocations.php">' . _('Allocate Receipts or Credit Notes') . '</a></p>'; ?>
							</td>
							</tr>
                            <tr>
                            <td class="menu_group_item">

                                <?php echo '<p>&bull; <a href="' . $rootpath . '/bio_storedespatch.php">' . _('Create Invoice against Despatch Clearence') . '</a></p>'; ?>
                            </td>
                            </tr>
						</table>
					</td>
					<td class="menu_group_items">
						<table width="100%" class="table_index">
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/CustWhereAlloc.php">' . _('Where Allocated Inquiry') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php
									if ($_SESSION['InvoicePortraitFormat']==0){
										echo '<p>&bull; <a href="' . $rootpath . '/PrintCustTrans.php">' . _('Print Invoices or Credit Notes') . '</a></p>';
									} else {
										echo '<p>&bull; <a href="' . $rootpath . '/PrintCustTransPortrait.php">' . _('Print Invoices or Credit Notes') . '</a></p>';
									}
								?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/PrintCustStatements.php">' . _('Print Statements') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/SalesAnalRepts.php">' . _('Sales Analysis Reports') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/AgedDebtors.php">' . _('Aged Customer Balances/Overdues Report') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/PDFBankingSummary.php">' . _('Re-Print A Deposit Listing') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/DebtorsAtPeriodEnd.php">' . _('Debtor Balances At A Prior Month End') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/PDFCustomerList.php">' . _('Customer Listing By Area/Salesperson') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/SalesGraph.php">' . _('Sales Graphs') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/PDFCustTransListing.php">' . _('List Daily Transactions') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/CustomerTransInquiry.php">' . _('Customer Transaction Inquiries') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo GetRptLinks('ar'); ?>
							</td>
							</tr>
						</table>
					</td>
					<td class="menu_group_items">
						<table width="100%" class="table_index">
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/Customers.php">' . _('Add Customer') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/SelectCustomer.php">' . _('Customers') . '</a></p>'; ?>
							</td>
							</tr>
						</table>
					</td>
					</tr>
				</table>
			</td>
			</tr>
		</table>
	<?php

	/* ********************* 	END OF AR OPTIONS **************************** */
		break;
        

        Case 'CS': //Customer Support Module

//    unset($ReceiptBatch);
//    unset($AllocTrans);

    ?>
        <table width="100%">
            <tr>
            <td valign="top" class="menu_group_area">
                <table width="100%">

                    <?php OptionHeadings();?>
                       
                    <tr>
                    <td class="menu_group_items">                       <!-- CS transaction options --> 
                    
                        <table width="100%"class="table_index">
                         
                            <tr>
                            <td class="menu_group_item">
                                <?php echo '<p>&bull; <a style="font-family:arial;font-size:13px;font-weight:bold;" href="' . $rootpath . '/bio_PendingdespatchClearence.php">' . _('Despatch Clearence') . '</a></p>'; ?>
                            </td>
                            </tr>
                             <tr>
                            <td class="menu_group_item">
                                <?php echo '<p>&bull; <a style="font-family:arial;font-size:13px;font-weight:bold;" href="' . $rootpath . '/bio_dispatched_cust_cancel.php">' . _('Despatch Clearence Cancellation') . '</a></p>'; ?>
                            </td>
                            </tr>
                             <tr>
                            <td class="menu_group_item">
                                <?php echo '<p>&bull; <a style="font-family:arial;font-size:13px;font-weight:bold;" href="' . $rootpath . '/bio_dispatched_cust.php">' . _('Delivery Management') . '</a></p>'; ?>
                            </td>
                            </tr>      <tr>
                            <td class="menu_group_item">
                                <?php echo '<p>&bull; <a style="font-family:arial;font-size:13px;font-weight:bold;" href="' . $rootpath . '/bio_installation.php">' . _('Installation Management') . '</a></p>'; ?>
                            </td>
                            </tr> 
                            <tr>
                            <td class="menu_group_item">
                                <?php echo '<p>&bull; <a style="font-family:arial;font-size:13px;font-weight:bold;" href="' . $rootpath . '/bio_amcRegister.php">' . _('AMC Registration') . '</a></p>'; ?>
                            </td>
                            </tr> 
                              <tr>
                            <td class="menu_group_item">
                                <?php echo '<p>&bull; <a style="font-family:arial;font-size:13px;font-weight:bold;" href="' . $rootpath . '/bio_amc_cust_list.php">' . _('AMC Scheduling') . '</a></p>'; ?>
                            </td>
                            </tr>    <tr>
                            <td class="menu_group_item">
                                <?php echo '<p>&bull; <a style="font-family:arial;font-size:13px;font-weight:bold;" href="' . $rootpath . '/bio_nwinstallationstatuses.php">' . _('Post Installation Calls') . '</a></p>'; ?>
                            </td>
                            </tr> 
                               <tr>
                            <td class="menu_group_item">
                                <?php echo '<p>&bull; <a style="font-family:arial;font-size:13px;font-weight:bold;" href="' . $rootpath . '/bio_cspendingtask.php">' . _('Pending Tasks') . '</a></p>'; ?>
                            </td>
                            </tr> 
                            <tr>
                            <td class="menu_group_item">
                                <?php echo '<p>&bull; <a style="font-family:arial;font-size:13px;font-weight:bold;" href="' . $rootpath . '/bio_pm_cust_list.php">' . _('Periodic maintenance') . '</a></p>'; ?>
                            </td>
                            </tr> 
                            
                               <tr>
                            <td class="menu_group_item">
                                <?php echo '<p>&bull; <a style="font-family:arial;font-size:13px;font-weight:bold;" href="' . $rootpath . '/bio_incidentRegister_crm.php">' . _('Complaint Registration') . '</a></p>'; ?>
                            </td>
                            </tr> 
                            <tr>
                            <td class="menu_group_item">
                                <?php echo '<p>&bull; <a style="font-family:arial;font-size:13px;font-weight:bold;" href="' . $rootpath . '/bio_incidentdepartment_crm.php">' . _('Complaint List') . '</a></p>'; ?>
                            </td>
                            </tr> 
                           
                            
                           
                         
                        </table>   
                        
                    </td> 
                    
                    <td class="menu_group_items">                       <!-- CS Inquiries -->    
                    </td>
                    
                    <td class="menu_group_items"> 
                     <table width="100%"class="table_index">                      <!-- CS Maintenance Options --> 
                             <tr>
                       <td class="menu_group_item">
                                <?php echo '<p>&bull; <a style="font-family:arial;font-size:13px;font-weight:bold;" href="' . $rootpath . '/bio_installation_task.php">' . _('Installation tasks') . '</a></p>'; ?>
                            </td>
                            </tr> 
                            
                                                        <tr>
                            <td class="menu_group_item">
                                <?php echo '<p>&bull; <a style="font-family:arial;font-size:13px;font-weight:bold;" href="' . $rootpath . '/bio_periodic_maintenance.php">' . _('Periodic Maintenance tasks') . '</a></p>'; ?>
                            </td>
                            </tr> 
                    
                                 </table>
                    </td> 
                    </tr>  
                      
                </table>
            </td>    
            </tr>
        </table>
    <?php

        break;  
        
          
    /* *********************    END OF CS OPTIONS **************************** */
         

	Case 'AP': //Creditors Module

	?>
		<table width="100%">
			<tr>
			<td valign="top" class="menu_group_area">
				<table width="100%">

					<?php OptionHeadings(); ?>

					<tr>
					<td class="menu_group_items"> <!-- AP transaction options -->
						<table width="100%" class="table_index">
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/SelectSupplier.php">' . _('Select Supplier') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . "/SupplierAllocations.php?" . SID . '">' . _('Supplier Allocations') . '</a></p>'; ?>
							</td>
							</tr>
						</table>
					</td>
					<td class="menu_group_items">  <!-- AP Inquiries -->
						<table width="100%" class="table_index">
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/AgedSuppliers.php">' . _('Aged Supplier Report') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/SuppPaymentRun.php">' . _('Payment Run Report') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/PDFRemittanceAdvice.php">' . _('Remittance Advices') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/OutstandingGRNs.php">' . _('Outstanding GRNs Report') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/SupplierBalsAtPeriodEnd.php">' . _('Supplier Balances At A Prior Month End') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/PDFSuppTransListing.php">' . _('List Daily Transactions') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/SupplierTransInquiry.php">' . _('Supplier Transaction Inquiries') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo GetRptLinks('ap'); ?>
							</td>
							</tr>
						</table>
					</td>
					<td class="menu_group_items">   <!-- AP Maintenance Options -->
						<table width="100%" class="table_index">
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/Suppliers.php">' . _('Add Supplier') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/Factors.php">' . _('Maintain Factor Companies') . '</a></p>'; ?>
							</td>
							</tr>
						</table>
					</td>
					</tr>
				</table>
			</td>
			</tr>
		</table>
	<?php
		break;

	Case 'PO': /* Purchase Ordering */

	?>
		<table width="100%">
			<tr>
			<td valign="top" class="menu_group_area">
				<table width="100%">

					<?php OptionHeadings(); ?>

					<tr>
					<td class="menu_group_items">  <!-- PO Transactions -->
						<table width="100%" class="table_index">
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/PO_SelectOSPurchOrder.php">' . _('Purchase Orders') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/bio_purchase_select.php' . SID . '">' . _('Add Purchase Order') . '</a></p>'; ?>
							</td>
							</tr>
                            <tr>
                            <td class="menu_group_item">
                                <?php echo '<p>&bull; <a href="' . $rootpath . '/bio_grn.php' . SID . '">' . _('Goods Recieve') . '</a></p>'; ?>
                            </td>
                            </tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/OffersReceived.php">' . _('Process Tenders and Offers') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/PO_AuthoriseMyOrders.php">' . _('Orders to Authorise') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/SelectSupplier.php">' . _('Shipment Entry') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/Shipt_Select.php">' . _('Select A Shipment') . '</a></p>'; ?>
							</td>
							</tr>
						</table>
					</td>
					<td class="menu_group_items">  <!-- PO Inquiries -->
						<table width="100%" class="table_index">
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/PO_SelectPurchOrder.php">' . _('Purchase Order Inquiry') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/POReport.php">' . _('Purchase Order Detail Or Summary Inquiries') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo GetRptLinks('prch'); ?>
							</td>
							</tr>
					</table>
					</td>
					<td class="menu_group_items">   <!-- PO Maintenance -->
						<table width="100%" class="table_index">
						</table>
					</td>
					</tr>
				</table>
			</td>
			</tr>
		</table>
	<?php
		break;

	/* ****************************** END OF PURCHASING OPTIONS ******************************** */


	Case 'stock': //Inventory Module

	?>
		<table width="100%">
			<tr>
			<td valign="top" class="menu_group_area">
				<table width="100%">

					<?php OptionHeadings(); ?>

					<tr>
					<td class="menu_group_items">
						<table width="100%" class="table_index">
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/PO_SelectOSPurchOrder.php">' . _('Receive Purchase Orders') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/StockLocTransfer.php' . SID . '">' . _('Bulk Inventory Transfer') . ' - ' . _('Dispatch') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/StockLocTransferReceive.php">' . _('Bulk Inventory Transfer') . ' - ' . _('Receive') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/StockTransfers.php?New=Yes">' . _('Inventory Location Transfers') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/StockAdjustments.php?NewAdjustment=Yes">' . _('Inventory Adjustments') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/ReverseGRN.php">' . _('Reverse Goods Received') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/StockCounts.php">' . _('Enter Stock Counts') . '</a></p>'; ?>
							</td>
							</tr>  
                             <tr>
                            <td class="menu_group_item">
                                <?php echo '<p>&bull; <a style="font-weight:bold" href="' . $rootpath . '/bio_adjust.php">' . _('Stock Register') . '</a></p>'; ?>
                            </td>
                            </tr>
						</table>
					</td>
					<td class="menu_group_items">
						<table width="100%" class="table_index">
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/StockSerialItemResearch.php">' . _('Serial Item Research Tool') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/PDFPrintLabel.php">' . _('Print Price Labels') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/StockMovements.php">' . _('Inventory Item Movements') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/StockStatus.php">' . _('Inventory Item Status') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/StockUsage.php">' . _('Inventory Item Usage') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/InventoryQuantities.php">' . _('Inventory Quantities') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/ReorderLevel.php">' . _('Reorder Level') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/StockDispatch.php">' . _('Stock Dispatch') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/InventoryValuation.php">' . _('Inventory Valuation Report') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/InventoryPlanning.php">' . _('Inventory Planning Report') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/InventoryPlanningPrefSupplier.php">' . _('Inventory Planning Based On Preferred Supplier Data') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/StockCheck.php">' . _('Inventory Stock Check Sheets') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/StockQties_csv.php">' . _('Make Inventory Quantities CSV') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/PDFStockCheckComparison.php">' . _('Compare Counts Vs Stock Check Data') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/StockLocMovements.php">' . _('All Inventory Movements By Location/Date') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/StockLocStatus.php">' . _('List Inventory Status By Location/Category') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/StockQuantityByDate.php">' . _('Historical Stock Quantity By Location/Category') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/PDFStockNegatives.php">' . _('List Negative Stocks') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/PDFPeriodStockTransListing.php?">' . _('Period Stock Transaction Listing') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/PDFStockTransfer.php">' . _('Stock Transfer Note') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/ReprintGRN.php">' . _('Reprint GRN') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo GetRptLinks('inv'); ?>
							</td>
							</tr>
						</table>
					</td>
					<td class="menu_group_items">
						<table width="100%" class="table_index">
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/Stocks.php">' . _('Add A New Item') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/SelectProduct.php">' . _('Select An Item') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/SalesCategories.php">' . _('Sales Category Maintenance') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/PricesBasedOnMarkUp.php">' . _('Add or Update Prices Based On Costs Or Other Price List') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/PricesByCost.php">' . _('View or Update Prices Based On Costs') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/ReorderLevelLocation.php">' . _('Reorder Level By Category/Location') . '</a></p>'; ?>
							</td>
							</tr>
						</table>
					</td>
					</tr>
				</table>
			</td>
			</tr>
		</table>
	<?php
		break;

	/* ****************************** END OF INVENTORY OPTIONS *********************************** */

	Case 'manuf': //Manufacturing Module

	?>
		<table width="100%">
			<tr>
			<td valign="top" class="menu_group_area">
				<table width="100%">

					<?php OptionHeadings(); ?>

					<tr>
					<td class="menu_group_items">
						<table width="100%" class="table_index">
                        <tr>
                            <td class="menu_group_item">
                                <?php echo '<p>&bull; <a href="' . $rootpath . '/bio_stock_item.php">' . _('Production Location ') . '</a></p>'; ?>
                            </td>
                            </tr>
                                                              
                                           <!--   <tr>
                            <td class="menu_group_item">
                                <?php echo '<p>&bull; <a href="' . $rootpath . '/SeasonDemands.php">' . _('Monthly Demands') . '</a></p>'; ?>
                            </td>
                            </tr>

                        <tr>
                              <td class="menu_group_item">
                                <?php echo '<p>&bull; <a href="' . $rootpath . '/MasterSchedule.php">' . _('Daily Schedules') . '</a></p>'; ?>
                            </td>
                            </tr>
                                <tr>
                              <td class="menu_group_item">
                                <?php echo '<p>&bull; <a href="' . $rootpath . '/WorkOrderEntry_new2.php">' . _('Work Order For Plants') . '</a></p>'; ?>
                            </td>
                            </tr>      -->
                                     <tr>
                              <td class="menu_group_item">
                                <?php echo '<p>&bull; <a href="' . $rootpath . '/SeasonDEmand_weekly.php">' . _('Weekly Production Plan') . '</a></p>'; ?>
                            </td>
                            </tr>  
                                     <tr>
                              <td class="menu_group_item">
                                <?php echo '<p>&bull; <a href="' . $rootpath . '/bio_WOFromDemand.php">' . _('WO Creation') . '</a></p>'; ?>
                            </td>
                            </tr>
                             </tr>           <tr>
                              <td class="menu_group_item">
                                <?php echo '<p>&bull; <a href="' . $rootpath . '/bio_workordercredit.php">' . _('Plant Crediting') . '</a></p>'; ?>
                            </td>
                            </tr>
                             </tr> 
                            <tr>
                              <td class="menu_group_item">
                                <?php echo '<p>&bull; <a href="' . $rootpath . '/bio_stocktransfer.php">' . _('Stock Transfer') . '</a></p>'; ?>
                            </td>
                            </tr>
                             <tr>
                              <td class="menu_group_item">
                                <?php echo '<p>&bull; <a href="' . $rootpath . '/bio_stockrecieve.php">' . _('Recieve Transfer Plant') . '</a></p>'; ?>
                            </td>
                            </tr>
                                <tr>
                              <td class="menu_group_item">
                                <?php echo '<p>&bull; <a href="' . $rootpath . '/bio_rawmaterialrecieve.php">' . _('Recieve Transfer Items') . '</a></p>'; ?>
                            </td>
                            </tr>
                                       <!--
							<tr>
							  <td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/WorkOrderEntry.php">' . _('Create Work Order') . '</a></p>'; ?>
							</td>
							</tr>
					        <td class="menu_group_item">
                                <?php echo '<p>&bull; <a href="' . $rootpath . '/PO_Header.php">' . _('Add Purchase Order') . '</a></p>'; ?>
                            </td>
                            </tr>      
                         
						
                        
                            
                       <tr>
                            <td class="menu_group_item">
                                <?php echo '<p>&bull; <a href="' . $rootpath . '/ST-Request.php">' . _('Stock transfer - Request') . '</a></p>'; ?>
                            </td>
                            </tr>
                                 
                       
                         <tr>
                            <td class="menu_group_item">
                                <?php echo '<p>&bull; <a href="' . $rootpath . '/ST-Issue.php">' . _('Stock Transfer - Issue') . '</a></p>'; ?>
                            </td>
                            </tr>    
                            
                        <tr>
                            <td class="menu_group_item">
                                <?php echo '<p>&bull; <a href="' . $rootpath . '/ST-Receive.php">' . _('Stock Transfer - Receive') . '</a></p>'; ?>
                            </td>
                            </tr>     -->
                        </table>
					</td>
					<td class="menu_group_items">
						<table width="100%" class="table_index">
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/SelectWorkOrder.php">' . _('Select A Work Order') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/BOMInquiry.php">' . _('Costed Bill Of Material Inquiry') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/WhereUsedInquiry.php">' . _('Where Used Inquiry') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/BOMListing.php">' . _('Bill Of Material Listing') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/BOMIndented.php">' . _('Indented Bill Of Material Listing') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/BOMExtendedQty.php">' . _('List Components Required') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/BOMIndentedReverse.php">' . _('Indented Where Used Listing') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/MRPReport.php">' . _('MRP') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/MRPShortages.php">' . _('MRP Shortages') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/MRPPlannedPurchaseOrders.php">' . _('MRP Suggested Purchase Orders') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/MRPPlannedWorkOrders.php">' . _('MRP Suggested Work Orders') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/MRPReschedules.php">' . _('MRP Reschedules Required') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo GetRptLinks('man'); ?>
							</td>
							</tr>
						</table>
					</td>
					<td class="menu_group_items">
						<table width="100%" class="table_index">
                        <tr>
                            <td class="menu_group_item">
                                <?php echo '<p>&bull; <a href="' . $rootpath . '/SupplierMaster.php">' . _('Supplier') . '</a></p>'; ?>
                            </td>
                            </tr>
                        
                        <tr>
                            <td class="menu_group_item">
                                <?php echo '<p>&bull; <a href="' . $rootpath . '/LocationMaster.php">' . _('Store Location') . '</a></p>'; ?>
                            </td>
                            </tr>

                        
							
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/BillsOfMaterials.php">' . _('Bills Of Material') . '</a></p>'; ?>
							</td>
							</tr>

							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/MRPDemands.php">' . _('Master Schedule') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/MRPCreateDemands.php">' . _('Auto Create Master Schedule') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/MRP.php">' . _('MRP Calculation') . '</a></p>'; ?>
							</td>
							</tr>

						</table>
					</td>
					</tr>
				</table>
			</td>
			</tr>
		</table>
	<?php
		break;

	Case 'system': //System setup

	?>
		<table width='100%'>
			<tr>
			<td valign="top" class="menu_group_area">
				<table width="100%" >
					<tr>
					<td class="menu_group_headers">
						<table style="background: url(Dark-Green.jpg); border-radius: 12px;">
							<tr>
							<td>
								<?php echo '<img src="'. $rootpath . '/css/' . $theme . '/images/company.png" title="' . _('General Setup Options') . '" alt="">'; ?>
							</td>
							<td class="menu_group_headers_text">
								<?php echo _('General'); ?>
							</td>
							</tr>
						</table>
					</td>
					<td class="menu_group_headers">
						<table style="background: url(Dark-Green.jpg); border-radius: 12px;">
							<tr>
							<td>
								<?php echo '<img src="'. $rootpath . '/css/' . $theme . '/images/ar.png" title="' . _('Receivables/Payables Setup') . '" alt="">'; ?>
							</td>
							<td class="menu_group_headers_text">
								<?php
                                 echo _('Receivables/Payables');
                                 ?>

							</td>
							</tr>
						</table>
					</td>
					<td class="menu_group_headers">
						<table style="background: url(Dark-Green.jpg); border-radius: 12px;">
							<tr>
							<td>
								<?php echo '<img src="'. $rootpath . '/css/' . $theme . '/images/inventory.png" title="' . _('Inventory Setup') . '" alt="">'; ?>
							</td>
							<td class="menu_group_headers_text">
								<?php echo _('Inventory Setup'); ?>
							</td>
							</tr>
						</table>
					</td>


					</tr>
					<tr>

					<td class="menu_group_items">	<!-- Gereral set up options -->
						<table width="100%" class="table_index">   
                        <tr>
                            <td class="menu_group_item">
                                <?php echo '<p>&bull; <a href="' . $rootpath . '/WWW_Users.php">' . _('User Maintenance') . '</a></p>'; ?>
                            </td>
                            </tr>
                    
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/CompanyPreferences.php">' . _('Company Preferences') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/SystemParameters.php">' . _('Configuration Settings') . '</a></p>'; ?>
							</td>
							</tr>
							
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/WWW_Access.php">' . _('Role Permissions') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/PageSecurity.php">' . _('Page Security Settings') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/SecurityTokens.php">' . _('Define Security Tokens') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/BankAccounts.php">' . _('Bank Accounts') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/Currencies.php">' . _('Currency Maintenance') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/TaxAuthorities.php">' . _('Tax Authorities and Rates Maintenance') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/TaxGroups.php">' . _('Tax Group Maintenance') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/TaxProvinces.php">' . _('Dispatch Tax Province Maintenance') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/TaxCategories.php">' . _('Tax Category Maintenance') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/PeriodsInquiry.php">' . _('List Periods Defined') . ' <span style="font-size: 9px;">(' . _('Periods are automatically maintained') . ')</span></a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/reportwriter/admin/ReportCreator.php">' . _('Report Builder Tool') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/AuditTrail.php">' . _('View Audit Trail') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/GeocodeSetup.php">' . _('Geocode Setup') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/FormDesigner.php">' . _('Form Layout Editor') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/Labels.php">' . _('Label Templates Maintenance') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/SMTPServer.php">' . _('SMTP Server Details') . '</a></p>'; ?>
							</td>
							</tr>
                       
						</table>
					</td>

					<td class="menu_group_items">	<!-- AR/AP set-up options -->
						<table width="100%" class="table_index">
							
                            <tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/SalesTypes.php">' . _('Sales Types') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/CustomerTypes.php">' . _('Customer Types') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/SupplierTypes.php">' . _('Supplier Types') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/CreditStatus.php">' . _('Credit Status') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/PaymentTerms.php">' . _('Payment Terms') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/PO_AuthorisationLevels.php">' . _('Set Purchase Order Authorisation levels') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/PaymentMethods.php">' . _('Payment Methods') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/SalesPeople.php">' . _('Sales People') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/Areas.php">' . _('Sales Areas') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/Shippers.php">' . _('Shippers') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/SalesGLPostings.php">' . _('Sales GL Interface Postings') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/COGSGLPostings.php">' . _('COGS GL Interface Postings') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/FreightCosts.php">' . _('Freight Costs Maintenance') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/DiscountMatrix.php">' . _('Discount Matrix') . '</a></p>'; ?>
							</td>
							</tr>
                         
						</table>
					</td>

					<td class="menu_group_items">	<!-- Inventory set-up options -->
						<table width="100%" class="table_index">
                        
                             <tr>
                            <td class="menu_group_item">
                                <?php echo '&bull; <a href="' . $rootpath . '/bio_employee.php">' . _('Employee') . '</a>'; ?>
                            </td>
                            </tr>
                            
                            <tr>
                            <td class="menu_group_item">
                                <?php echo '&bull; <a href="' . $rootpath . '/bio_offices.php">' . _('Office') . '</a>'; ?>
                            </td>
                            </tr>
                            
                            <tr>
                            <td class="menu_group_item">
                                <?php echo '&bull; <a href="' . $rootpath . '/bio_department.php">' . _('Department') . '</a>'; ?>
                            </td>
                            </tr>
                            
                            <tr>
                            <td class="menu_group_item">
                                <?php echo '&bull; <a href="' . $rootpath . '/bio_designation.php">' . _('Designation') . '</a>'; ?>
                            </td>
                            </tr>
                                <tr>                                                        
                            <td class="menu_group_item">
                                <?php echo '&bull; <a href="' . $rootpath . '/bio_leadteams.php">' . _('Lead Teams') . '</a>'; ?>
                            </td>
                            </tr>
                        
                           
                            <tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/StockCategories.php">' . _('Inventory Categories Maintenance') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/Locations.php">' . _('Inventory Locations Maintenance') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/DiscountCategories.php">' . _('Discount Category Maintenance') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/UnitsOfMeasure.php">' . _('Units of Measure') . '</a></p>'; ?>
							</td>
							</tr>
							<tr></tr>

							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/MRPCalendar.php">' . _('MRP Available Production Days') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/MRPDemandTypes.php">' . _('MRP Demand Types') . '</a></p>'; ?>
							</td>
							</tr>
                  
                            <tr>
                            <td class="menu_group_item">
                                <?php echo '<p>&bull; <a href="' . $rootpath . '/block.php">' . _('Block') . '</a></p>'; ?>
                            </td>
                            </tr>
                            
                            <tr>
                            <td class="menu_group_item">
                                <?php echo '<p>&bull; <a href="' . $rootpath . '/unblock.php">' . _('Unblock') . '</a></p>'; ?>
                            </td>
                            </tr>
                     
						</table>
					</td>
					</tr>
				</table>
			</td>
			</tr>
		</table>
	<?php
		break;

	Case 'GL': //General Ledger

	?>
		<table width="100%">
			<tr>
			<td valign="top" class="menu_group_area">
				<table width="100%">		<!-- Gereral Ledger Option Headings-->

    					<?php OptionHeadings(); ?>

					<tr>
					<td class="menu_group_items"> <!-- General transactions options -->
						<table width="100%" class="table_index">
                        <?php
                            if($_SESSION[UserID!='useradmin'])
                            {
                        ?>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/Payments.php?NewPayment=Yes">' . _('Bank Account Payments Entry') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/CustomerReceipt.php?NewReceipt=Yes&Type=GL">' . _('Bank Account Receipts Entry') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/GLJournal.php?NewJournal=Yes">' . _('Journal Entry') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/BankMatching.php?Type=Payments">' . _('Bank Account Payments Matching') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/BankMatching.php?Type=Receipts">' . _('Bank Account Receipts Matching') . '</a></p>'; ?>
							</td>
							</tr>
                            <?php
    }
                             ?>
						</table>
					</td>
					<td class="menu_group_items">  <!-- Gereral inquiry options -->
						<table width="100%" class="table_index">
                        <?php
                            if($_SESSION[UserID!='useradmin'])
                            {
                        ?>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/GLTrialBalance.php">' . _('Trial Balance') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/SelectGLAccount.php">' . _('Account Inquiry') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/GLAccountReport.php">' . _('Account Listing') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/GLAccountCSV.php">' . _('Account Listing to CSV File') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/BankReconciliation.php">' . _('Bank Account Reconciliation Statement') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/PDFChequeListing.php">' . _('Cheque Payments Listing') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/DailyBankTransactions.php">' . _('Daily Bank Transactions') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/GLProfit_Loss.php">' . _('Profit and Loss Statement') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/GLBalanceSheet.php">' . _('Balance Sheet') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/GLTagProfit_Loss.php">' . _('Tag Reports') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/Tax.php">' . _('Tax Reports') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo GetRptLinks('gl'); ?>
							</td>
							</tr>
                            <?php
    }
                             ?>
						</table>
					</td>
					<td class="menu_group_items">  <!-- Gereral Ledger Maintenance options -->
						<table width="100%" class="table_index">
                        <?php
                            if($_SESSION[UserID!='useradmin'])
                            {
                        ?>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/GLAccounts.php">' . _('GL Account') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/GLBudgets.php">' . _('GL Budgets') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/AccountGroups.php">' . _('Account Groups') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/AccountSections.php">' . _('Account Sections') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/GLTags.php">' . _('GL Tags') . '</a></p>'; ?>
							</td>
							</tr>
                              <?php
                            }
                              ?>
						</table>
					</td>
					</tr>
				</table>
			</td>
			</tr>
		</table>
	<?php
	break;
	Case 'FA': //Fixed Assets

	?>
		<table width="100%">
			<tr>
			<td valign="top" class="menu_group_area">
				<table width="100%">		<!-- Fixed Asset Option Headings-->
					<?php OptionHeadings(); ?>
					<tr>
					<td class="menu_group_items"> <!--  Fixed Asset transactions options -->
						<table width="100%" class="table_index">
						<tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/FixedAssetItems.php">' . _('Add a new Asset') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/SelectAsset.php">' . _('Select an Asset') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/FixedAssetTransfer.php">' . _('Change Asset Location') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/FixedAssetDepreciation.php' . SID . '">' . _('Depreciation Journal') . '</a></p>'; ?>
							</td>
							</tr>
						</table>
					</td>
					<td class="menu_group_items"> <!-- Fixed Asset transactions options -->
						<table width="100%" class="table_index">
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/FixedAssetRegister.php">' . _('Asset Register') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo GetRptLinks('fa'); ?>
							</td>
							</tr>
						</table>
					</td>
					<td class="menu_group_items"> <!-- General transactions options -->
						<table width="100%" class="table_index">
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/FixedAssetCategories.php">' . _('Asset Categories Maintenance') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/FixedAssetLocations.php">' . _('Add or Maintain Asset Locations') . '</a></p>'; ?>
							</td>
							</tr>
						</table>
					</td>
				</table>
			</td>
			</tr>
		</table>
		<?php
	break;

	Case 'PC': /* Petty Cash Module */

	?>
		<table width="100%">
			<tr>
			<td valign="top" class="menu_group_area">
				<table width="100%">

					<?php OptionHeadings(); ?>

					<tr>
					<td class="menu_group_items">  <!-- PC Transactions -->
						<table width="100%" class="table_index">
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/PcAssignCashToTab.php">' . _('Assign Cash to PC Tab') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/PcClaimExpensesFromTab.php' . SID . '">' . _('Claim Expenses From PC Tab') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/PcAuthorizeExpenses.php">' . _('Expenses Authorisation') . '</a></p>'; ?>
							</td>
							</tr>
						</table>
					</td>
					<td class="menu_group_items">  <!-- Pc Inquiries -->
						<table width="100%" class="table_index">
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/PcReportTab.php">' . _('PC Tab General Report') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo GetRptLinks('prch'); ?>
							</td>
							</tr>
					</table>
					</td>
					<td class="menu_group_items">   <!-- PC Maintenance -->
						<table width="100%" class="table_index">
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/PcTypeTabs.php">' . _('Types of PC Tabs') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/PcTabs.php">' . _('PC Tabs') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/PcExpenses.php">' . _('PC Expenses') . '</a></p>'; ?>
							</td>
							</tr>
							<tr>
							<td class="menu_group_item">
								<?php echo '<p>&bull; <a href="' . $rootpath . '/PcExpensesTypeTab.php">' . _('Expenses for Type of PC Tab') . '</a></p>'; ?>
							</td>
							</tr>
						</table>
					</td>
					</tr>
				</table>
			</td>
			</tr>
		</table>
	<?php
		break;

	} //end of module switch
} /* end of if security allows to see the full menu */

// all tables started are ended within this index script which means 2 outstanding from footer.

include('includes/footer.inc');

function OptionHeadings() {

global $rootpath, $theme;

?>

	<tr>
	<td valign="top" class="menu_group_headers"> <!-- Orders option Headings -->
		<table style="background: url(Dark-Green.jpg); border-radius: 12px;">
			<tr>
			<td>
				<?php echo '<img src="' . $rootpath . '/css/' . $theme . '/images/transactions.png" title="' . _('Transactions') . '" alt="">'; ?>
			</td>
			<td class="menu_group_headers_text">
				<?php echo _('Transactions'); ?>
			</td>
			</tr>
		</table>
	</td>
	<td valign="top" class="menu_group_headers">
		<table  border-radius="10px" style="background: url(Dark-Green.jpg); border-radius: 12px;">
			<tr>
			<td>
				<?php echo '<img src="' . $rootpath . '/css/' . $theme . '/images/reports.png" title="' . _('Inquiries and Reports') . '" alt="">'; ?>
			</td>
			<td class="menu_group_headers_text">
				<?php echo _('Inquiries and Reports'); ?>
			</td>
			</tr>
		</table>
	</td>
	<td valign="top" class="menu_group_headers">
		<table style="background: url(Dark-Green.jpg); border-radius: 12px;">
			<tr>
			<td>
				<?php echo '<img src="' . $rootpath . '/css/' . $theme . '/images/maintenance.png" title="' . _('Maintenance') . '" alt="">'; ?>
			</td>
			<td class="menu_group_headers_text">
				<?php echo _('Maintenance'); ?>
			</td>
			</tr>
		</table>
	</td>
	</tr>

<?php

}

function GetRptLinks($GroupID) {
/*
This function retrieves the reports given a certain group id as defined in /reports/admin/defaults.php
in the acssociative array $ReportGroups[]. It will fetch the reports belonging solely to the group
specified to create a list of links for insertion into a table to choose a report. Two table sections will
be generated, one for standard reports and the other for custom reports.
*/
	global $db, $rootpath;
	require_once('reportwriter/languages/en_US/reports.php');
	require_once('reportwriter/admin/defaults.php');

	$Title= array(_('Custom Reports'), _('Standard Reports and Forms'));

	$sql= "SELECT id,
								reporttype,
								defaultreport,
								groupname,
								reportname
				FROM reports
				ORDER BY groupname,
									reportname";
	$Result=DB_query($sql,$db,'','',false,true);
	$ReportList = '';
	while ($Temp = DB_fetch_array($Result)) $ReportList[] = $Temp;

	$RptLinks = '';
	for ($Def=1; $Def>=0; $Def--) {
		$RptLinks .= '<tr><td class="menu_group_headers"><div align="center">'.$Title[$Def].'</div></td></tr>';
		$NoEntries = true;
		if ($ReportList) { // then there are reports to show, show by grouping
			foreach ($ReportList as $Report) {
				if ($Report['groupname']==$GroupID AND $Report['defaultreport']==$Def) {
					$RptLinks .= '<tr><td class="menu_group_item">';
					$RptLinks .= '<a href="' . $rootpath . '/reportwriter/ReportMaker.php?action=go&reportid=' . $Report['id'] . '">' . _($Report['reportname']) . '</a>';
					$RptLinks .= '</td></tr>';
					$NoEntries = false;
				}
			}
			// now fetch the form groups that are a part of this group (List after reports)
			$NoForms = true;
			foreach ($ReportList as $Report) {
				$Group=explode(':',$Report['groupname']); // break into main group and form group array
				if ($NoForms AND $Group[0]==$GroupID AND $Report['reporttype']=='frm' AND $Report['defaultreport']==$Def) {
					$RptLinks .= '<tr><td class="menu_group_item">';
					$RptLinks .= '<img src="' . $rootpath . '/css/' . $_SESSION['Theme'] . '/images/folders.gif" width="16" height="13">&nbsp;';
					$RptLinks .= '<a href="' . $rootpath . '/reportwriter/FormMaker.php?id=' . $Report['groupname'] . '">';
					$RptLinks .= $FormGroups[$Report['groupname']] . '</a>';
					$RptLinks .= '</td></tr>';
					$NoForms = false;
					$NoEntries = false;
				}
			}
		}
		if ($NoEntries) $RptLinks .= '<tr><td class="menu_group_item">' . _('There are no reports to show!') . '</td></tr>';
	}
	return $RptLinks;
}

?>
