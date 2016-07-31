<?php
       $PageSecurity = 80;
     include('includes/session.inc');
     
     $title = _('Update');  
include('includes/header.inc');


$sql="UPDATE bio_advance SET collected_by='businesshead' WHERE collected_by='biju'";   DB_query($sql,$db);
$sql="UPDATE bio_advance SET collected_by='bdmkoz1' WHERE collected_by='manu'";     DB_query($sql,$db);

$sql="UPDATE bio_approval SET submitted_user='businesshead' WHERE submitted_user='biju'";   DB_query($sql,$db);
$sql="UPDATE bio_approval SET submitted_user='bdmkoz1' WHERE submitted_user='manu'";   DB_query($sql,$db);
$sql="UPDATE bio_approval SET approval_user='businesshead' WHERE approval_user='biju'";   DB_query($sql,$db);
$sql="UPDATE bio_approval SET approval_user='bdmkoz1' WHERE approval_user='manu'";   DB_query($sql,$db);

$sql="UPDATE bio_conceptproposal SET created_by='businesshead' WHERE created_by='biju'";   DB_query($sql,$db);
$sql="UPDATE bio_conceptproposal SET created_by='bdmkoz1' WHERE created_by='manu'";   DB_query($sql,$db);
$sql="UPDATE bio_conceptproposal SET approved_by='businesshead' WHERE approved_by='biju'";   DB_query($sql,$db);
$sql="UPDATE bio_conceptproposal SET approved_by='bdmkoz1' WHERE approved_by='manu'";   DB_query($sql,$db);
$sql="UPDATE bio_conceptproposal SET signatory_by='businesshead' WHERE signatory_by='biju'";   DB_query($sql,$db);
$sql="UPDATE bio_conceptproposal SET signatory_by='bdmkoz1' WHERE signatory_by='manu'";   DB_query($sql,$db);  

$sql="UPDATE bio_feasibilitystudy SET created_by='businesshead' WHERE created_by='biju'";   DB_query($sql,$db);
$sql="UPDATE bio_feasibilitystudy SET created_by='bdmkoz1' WHERE created_by='manu'";   DB_query($sql,$db);
$sql="UPDATE bio_feasibilitystudy SET approved_by='businesshead' WHERE approved_by='biju'";   DB_query($sql,$db);
$sql="UPDATE bio_feasibilitystudy SET approved_by='bdmkoz1' WHERE approved_by='manu'";   DB_query($sql,$db);

$sql="UPDATE bio_fsproposal SET fp_createdby='businesshead' WHERE fp_createdby='biju'";   DB_query($sql,$db);
$sql="UPDATE bio_fsproposal SET fp_createdby='bdmkoz1' WHERE fp_createdby='manu'";   DB_query($sql,$db);
$sql="UPDATE bio_fsproposal SET fp_approvalby='businesshead' WHERE fp_approvalby='biju'";   DB_query($sql,$db);
$sql="UPDATE bio_fsproposal SET fp_approvalby='bdmkoz1' WHERE fp_approvalby='manu'";   DB_query($sql,$db);

$sql="UPDATE bio_fs_entrydetails SET created_by='businesshead' WHERE created_by='biju'";   DB_query($sql,$db);
$sql="UPDATE bio_fs_entrydetails SET created_by='bdmkoz1' WHERE created_by='manu'";     DB_query($sql,$db);

$sql="UPDATE bio_incidents SET createdby='businesshead' WHERE createdby='biju'";   DB_query($sql,$db);
$sql="UPDATE bio_incidents SET createdby='bdmkoz1' WHERE createdby='manu'";     DB_query($sql,$db);

$sql="UPDATE bio_leads SET created_by='businesshead' WHERE created_by='biju'";   DB_query($sql,$db);
//$sql="UPDATE bio_leads SET created_by='bdmkoz1' WHERE created_by='manu'";     DB_query($sql,$db);

$sql="UPDATE bio_oldorders SET createdby='businesshead' WHERE createdby='biju'";   DB_query($sql,$db);
$sql="UPDATE bio_oldorders SET createdby='bdmkoz1' WHERE createdby='manu'";     DB_query($sql,$db);

$sql="UPDATE bio_orderapproval SET submitted_by='businesshead' WHERE submitted_by='biju'";   DB_query($sql,$db);
$sql="UPDATE bio_orderapproval SET submitted_by='bdmkoz1' WHERE submitted_by='manu'";   DB_query($sql,$db);
$sql="UPDATE bio_orderapproval SET approval_by='businesshead' WHERE approval_by='biju'";   DB_query($sql,$db);
$sql="UPDATE bio_orderapproval SET approval_by='bdmkoz1' WHERE approval_by='manu'";   DB_query($sql,$db);

$sql="UPDATE bio_proposal SET createdby='businesshead' WHERE createdby='biju'";   DB_query($sql,$db);
$sql="UPDATE bio_proposal SET createdby='bdmkoz1' WHERE createdby='manu'";     DB_query($sql,$db);

$sql="UPDATE salesorders SET created_by='businesshead' WHERE created_by='biju'";   DB_query($sql,$db);
$sql="UPDATE salesorders SET created_by='bdmkoz1' WHERE created_by='manu'";   DB_query($sql,$db);
$sql="UPDATE salesorders SET approval_by='businesshead' WHERE approval_by='biju'";   DB_query($sql,$db);
$sql="UPDATE salesorders SET approval_by='bdmkoz1' WHERE approval_by='manu'";   DB_query($sql,$db);
$sql="UPDATE salesorders SET signatory_by='businesshead' WHERE signatory_by='biju'";   DB_query($sql,$db);
$sql="UPDATE salesorders SET signatory_by='bdmkoz1' WHERE signatory_by='manu'";   DB_query($sql,$db);  

$sql="UPDATE www_users SET userid='businesshead' WHERE userid='biju'";   DB_query($sql,$db);
//$sql="UPDATE www_users SET userid='' WHERE userid='manu'";     DB_query($sql,$db);

?>
