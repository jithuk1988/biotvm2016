<?php

/* $Id: CustomerBranches.php 4591 2011-06-09 10:33:38Z daintree $*/
$PageSecurity = 80;

include('includes/session.inc');
$title = _('Customer Branches');
include('includes/header.inc');

if (isset($_GET['DebtorNo'])) {
    $DebtorNo = strtoupper($_GET['DebtorNo']);
} else if (isset($_POST['DebtorNo'])){
    $DebtorNo = strtoupper($_POST['DebtorNo']);
}

if (!isset($DebtorNo)) {
    prnMsg(_('This page must be called with the debtor code of the customer for whom you wish to edit the branches for').'.
        <br />'._('When the pages is called from within the system this will always be the case').' <br />'.
            _('Select a customer first then select the link to add/edit/delete branches'),'warn');
    include('includes/footer.inc');
    exit;
}


if (isset($_GET['SelectedBranch'])){
    $SelectedBranch = strtoupper($_GET['SelectedBranch']);
} else if (isset($_POST['SelectedBranch'])){
    $SelectedBranch = strtoupper($_POST['SelectedBranch']);
}

if (isset($Errors)) {
    unset($Errors);
}


    //initialise no input errors assumed initially before we test
$Errors = array();
$InputError = 0;


if (isset($_POST['submit'])) {

    $i=1;

    /* actions to take once the user has clicked the submit button
    ie the page has called itself with some user input */

    //first off validate inputs sensible

    $_POST['BranchCode'] = strtoupper($_POST['BranchCode']);

    if (ContainsIllegalCharacters($_POST['BranchCode']) OR strstr($_POST['BranchCode'],' ')) {
        $InputError = 1;
        prnMsg(_('The Branch code cannot contain any of the following characters')." -  & \'",'error');
        $Errors[$i] = 'BranchCode';
        $i++;
    }
    if (strlen($_POST['BranchCode'])==0) {
        $InputError = 1;
        prnMsg(_('The Branch code must be at least one character long'),'error');
        $Errors[$i] = 'BranchCode';
        $i++;
    }
    if (!is_numeric($_POST['FwdDate'])) {
        $InputError = 1;
        prnMsg(_('The date after which invoices are charged to the following month is expected to be a number and a recognised number has not been entered'),'error');
        $Errors[$i] = 'FwdDate';
        $i++;
    }
    if ($_POST['FwdDate'] >30) {
        $InputError = 1;
        prnMsg(_('The date (in the month) after which invoices are charged to the following month should be a number less than 31'),'error');
        $Errors[$i] = 'FwdDate';
        $i++;
    }
    if (!is_numeric($_POST['EstDeliveryDays'])) {
        $InputError = 1;
        prnMsg(_('The estimated delivery days is expected to be a number and a recognised number has not been entered'),'error');
        $Errors[$i] = 'EstDeliveryDays';
        $i++;
    }
    if ($_POST['EstDeliveryDays'] >60) {
        $InputError = 1;
        prnMsg(_('The estimated delivery days should be a number of days less than 60') . '. ' . _('A package can be delivered by seafreight anywhere in the world normally in less than 60 days'),'error');
        $Errors[$i] = 'EstDeliveryDays';
        $i++;
    }
    if (!isset($_POST['EstDeliveryDays'])) {
        $_POST['EstDeliveryDays']=1;
    }
    if (!isset($latitude)) {
        $latitude=0.0;
        $longitude=0.0;
    }
    if ($_SESSION['geocode_integration']==1 ){
        // Get the lat/long from our geocoding host
        $sql = "SELECT * FROM geocode_param WHERE 1";
        $ErrMsg = _('An error occurred in retrieving the information');
        $resultgeo = DB_query($sql, $db, $ErrMsg);
        $row = DB_fetch_array($resultgeo);
        $api_key = $row['geocode_key'];
        $map_host = $row['map_host'];
        define('MAPS_HOST', $map_host);
        define('KEY', $api_key);
        if ($map_host=="") {
        // check that some sane values are setup already in geocode tables, if not skip the geocoding but add the record anyway.
            echo '<div class="warn">' . _('Warning - Geocode Integration is enabled, but no hosts are setup.  Go to Geocode Setup') . '</div>';
                } else {
            $address = $_POST['BrAddress1'] . ", " . $_POST['BrAddress2'] . ", " . $_POST['BrAddress3'] . ", " . $_POST['BrAddress4'];
            $base_url = 'http://' . MAPS_HOST . '/maps/geo?output=xml&key=' . KEY;
            $request_url = $base_url . '&q=' . urlencode($address);
            $xml = simplexml_load_string(utf8_encode(file_get_contents($request_url))) or die("url not loading");
            $coordinates = $xml->Response->Placemark->Point->coordinates;
            $coordinatesSplit = explode(",", $coordinates);
            // Format: Longitude, Latitude, Altitude
            $latitude = $coordinatesSplit[1];
            $longitude = $coordinatesSplit[0];
            
            $status = $xml->Response->Status->code;
            if (strcmp($status, '200') == 0) {
                // Successful geocode
                    $geocode_pending = false;
                $coordinates = $xml->Response->Placemark->Point->coordinates;
                $coordinatesSplit = explode(",", $coordinates);
                // Format: Longitude, Latitude, Altitude
                $latitude = $coordinatesSplit[1];
                $longitude = $coordinatesSplit[0];
            } else {
                // failure to geocode
                $geocode_pending = false;
                echo '<div class="page_help_text"><b>' . _('Geocode Notice') . ':</b> ' . _('Address') . ': ' . $address . ' ' . _('failed to geocode');
                echo _('Received status') . ' ' . $status . '</div>';
            }
        }
    }
    if (isset($SelectedBranch) AND $InputError !=1) {
                                     
        /*SelectedBranch could also exist if submit had not been clicked this code would not run in this case cos submit is false of course see the     delete code below*/

        $sql = "UPDATE custbranch SET brname = '" . $_POST['BrName'] . "',
                        braddress1 = '" . $_POST['BrAddress1'] . "',
                        braddress2 = '" . $_POST['BrAddress2'] . "',
                        braddress3 = '" . $_POST['BrAddress3'] . "',
                        braddress4 = '" . $_POST['BrAddress4'] . "',
                        braddress5 = '" . $_POST['BrAddress5'] . "',
                        braddress6 = '" . $_POST['BrAddress6'] . "',
                        lat = '" . $latitude . "',
                        lng = '" . $longitude . "',
                        specialinstructions = '" . $_POST['specialinstructions'] . "',
                        phoneno='" . $_POST['PhoneNo'] . "',
                        faxno='" . $_POST['FaxNo'] . "',
                        fwddate= '" . $_POST['FwdDate'] . "',
                        contactname='" . $_POST['ContactName'] . "',
                        salesman= '" . $_POST['Salesman'] . "',
                        area='" . $_POST['Area'] . "',
                        estdeliverydays ='" . $_POST['EstDeliveryDays'] . "',
                        email='" . $_POST['Email'] . "',
                        taxgroupid='" . $_POST['TaxGroup'] . "',
                        defaultlocation='" . $_POST['DefaultLocation'] . "',
                        brpostaddr1 = '" . $_POST['BrPostAddr1'] . "',
                        brpostaddr2 = '" . $_POST['BrPostAddr2'] . "',
                        brpostaddr3 = '" . $_POST['BrPostAddr3'] . "',
                        brpostaddr4 = '" . $_POST['BrPostAddr4'] . "',
                        disabletrans='" . $_POST['DisableTrans'] . "',
                        defaultshipvia='" . $_POST['DefaultShipVia'] . "',
                        custbranchcode='" . $_POST['CustBranchCode'] ."',
                        deliverblind='" . $_POST['DeliverBlind'] . "'
                    WHERE branchcode = '".$SelectedBranch."' AND debtorno='".$DebtorNo."'";

        $msg = $_POST['BrName'] . ' '._('branch has been updated.');

    } else if ($InputError !=1) {

    /*Selected branch is null cos no item selected on first time round so must be adding a    record must be submitting new entries in the new Customer Branches form */

        $sql = "INSERT INTO custbranch (branchcode,
                        debtorno,
                        brname,
                        braddress1,
                        braddress2,
                        braddress3,
                        braddress4,
                        braddress5,
                        braddress6,
                        lat,
                        lng,
                         specialinstructions,
                        estdeliverydays,
                        fwddate,
                        salesman,
                        phoneno,
                        faxno,
                        contactname,
                        area,
                        email,
                        taxgroupid,
                        defaultlocation,
                        brpostaddr1,
                        brpostaddr2,
                        brpostaddr3,
                        brpostaddr4,
                        disabletrans,
                        defaultshipvia,
                        custbranchcode,
                        deliverblind,
                        cid,
                        stateid,
                        did,
                        taluk,
                        LSG_type,
                        LSG_name,
                        block_name,
                        LSG_ward,
                        village)
                VALUES ('" . $_POST['BranchCode'] . "',
                    '" . $DebtorNo . "',
                    '" . $_POST['BrName'] . "',
                    '" . $_POST['BrAddress1'] . "',
                    '" . $_POST['BrAddress2'] . "',
                    '" . $_POST['BrAddress3'] . "',
                    '" . $_POST['BrAddress4'] . "',
                    '" . $_POST['BrAddress5'] . "',
                    '" . $_POST['BrAddress6'] . "',
                    '" . $latitude . "',
                    '" . $longitude . "',
                    '" . $_POST['specialinstructions'] . "',
                    '" . $_POST['EstDeliveryDays'] . "',
                    '" . $_POST['FwdDate'] . "',
                    '" . $_POST['Salesman'] . "',
                    '" . $_POST['PhoneNo'] . "',
                    '" . $_POST['FaxNo'] . "',
                    '" . $_POST['ContactName'] . "',
                    '" . $_POST['Area'] . "',
                    '" . $_POST['Email'] . "',
                    '" . $_POST['TaxGroup'] . "',
                    '" . $_POST['DefaultLocation'] . "',
                    '" . $_POST['BrPostAddr1'] . "',
                    '" . $_POST['BrPostAddr2'] . "',
                    '" . $_POST['BrPostAddr3'] . "',
                    '" . $_POST['BrPostAddr4'] . "',
                    '" . $_POST['DisableTrans'] . "',
                    '" . $_POST['DefaultShipVia'] . "',
                    '" . $_POST['CustBranchCode'] ."',
                    '" . $_POST['DeliverBlind'] . "',
                    '" . $_POST['country'] . "',
                    '" . $_POST['State'] . "',
                    '" . $_POST['District'] . "',
                    '" . $_POST['taluk'] . "',
                    '" . $_POST['lsgType'] . "',
                    '" . $_POST['lsgName'] . "',
                    '" . $_POST['gramaPanchayath'] . "',
                    '" . $_POST['lsgWard'] . "',
                    '" . $_POST['village'] . "'
                    )";
    }
    echo '<br />';
    $msg = _('Customer branch').'<b> ' . $_POST['BranchCode'] . ': ' . $_POST['BrName'] . ' </b>'._('has been added, add another branch, or return to the') . ' <a href="Customers.php">' . _('Add New Customers') . '</a>';

    //run the SQL from either of the above possibilites

    $ErrMsg = _('The branch record could not be inserted or updated because');
    if ($InputError==0) {
        $result = DB_query($sql,$db, $ErrMsg);
    }
 
 echo"<input type='hidden' name='flag' id='flag' value='".$_POST['flag']."'>"; 
 echo"<input type='hidden' name='leadid' id='leadid' value='".$_POST['leadid']."'>";
  echo"<input type='hidden' name='LeadType' id='leadtype' value='".$_POST['LeadType']."'>"; 
 if(isset($_POST['flag']) AND $_POST['flag']!="")                                                     
 {
     if($_POST['LeadType']==2){
       ?>
     <script>
     var lead=<?php echo $_POST['leadid']; ?>;
     var flag=document.getElementById('flag').value;   
      window.opener.location='bio_instTaskview.php?lead='+ lead+'&flag=' + flag;
      window.close();
     
     </script>
     <?php  
     }
     else{                   
     ?>
     <script>
     var flag=document.getElementById('flag').value;   
     var lead=document.getElementById('leadid').value; 
     window.opener.location='bio_domTaskview.php?flag=' + flag + '&lead='+ lead;
     window.close();
     </script>
     <?php
     }
 }elseif($_SESSION['CounterSale']=='countersale'){    
  $sql=DB_query("UPDATE bio_leads SET leadstatus=25 WHERE leadid='".$_POST['leadid']."'",$db);   
     ?>
     <script>
     controlWindow=window.open("bio_createOrderfromleads.php?NewOrder=Yes","so_cp","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=1500,height=600");
     window.close();
     </script>
     <?php
 }
elseif(isset($_POST['incidentflag']) AND $_POST['incidentflag']!=""){
              $_SESSION['debtorid']=$DebtorNo;
             $sql=  DB_query("UPDATE bio_leads SET leadstatus=25 WHERE leadid='".$_POST['leadid']."'",$db);  
   ?>
     <script>
    controlWindow=window.open("bio_createOrderfromleads.php?NewOrder=Yes","so_cp","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=1500,height=600");
     window.close();
     </script>
     <?php
     
 } else{    
         $_SESSION['debtorid']=$DebtorNo;
         $sql=  DB_query("UPDATE bio_leads SET leadstatus=25 WHERE leadid='".$_POST['leadid']."'",$db);  
   ?>
   
     <script>
    controlWindow=window.open("bio_createOrderfromleads.php?NewOrder=Yes","so_cp","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=1500,height=600");
     window.close();
     </script>
     <?php
     
 }

 
    if (DB_error_no($db) ==0 and $InputError==0) {
        prnMsg($msg,'success');
        unset($_POST['BranchCode']);
        unset($_POST['BrName']);
        unset($_POST['BrAddress1']);
        unset($_POST['BrAddress2']);
        unset($_POST['BrAddress3']);
        unset($_POST['BrAddress4']);
        unset($_POST['BrAddress5']);
        unset($_POST['BrAddress6']);
        unset($_POST['specialinstructions']);
        unset($_POST['EstDeliveryDays']);
        unset($_POST['FwdDate']);
        unset($_POST['Salesman']);
        unset($_POST['PhoneNo']);
        unset($_POST['FaxNo']);
        unset($_POST['ContactName']);
        unset($_POST['Area']);
        unset($_POST['Email']);
        unset($_POST['TaxGroup']);
        unset($_POST['DefaultLocation']);
        unset($_POST['DisableTrans']);
        unset($_POST['BrPostAddr1']);
        unset($_POST['BrPostAddr2']);
        unset($_POST['BrPostAddr3']);
        unset($_POST['BrPostAddr4']);
        unset($_POST['DefaultShipVia']);
        unset($_POST['CustBranchCode']);
        unset($_POST['DeliverBlind']);
        unset($SelectedBranch);
    }
            
} else if (isset($_GET['delete'])) {
//the link to delete a selected record was clicked instead of the submit button

// PREVENT DELETES IF DEPENDENT RECORDS IN 'DebtorTrans'

    $sql= "SELECT COUNT(*) FROM debtortrans WHERE debtortrans.branchcode='".$SelectedBranch."' AND debtorno = '".$DebtorNo."'";
    $result = DB_query($sql,$db);
    $myrow = DB_fetch_row($result);
    if ($myrow[0]>0) {
        prnMsg(_('Cannot delete this branch because customer transactions have been created to this branch') . '<br />' .
             _('There are').' ' . $myrow[0] . ' '._('transactions with this Branch Code'),'error');

    } else {
        $sql= "SELECT COUNT(*) FROM salesanalysis WHERE salesanalysis.custbranch='".$SelectedBranch."' AND salesanalysis.cust = '".$DebtorNo."'";

        $result = DB_query($sql,$db);

        $myrow = DB_fetch_row($result);
        if ($myrow[0]>0) {
            prnMsg(_('Cannot delete this branch because sales analysis records exist for it'),'error');
            echo '<br />'._('There are').' ' . $myrow[0] . ' '._('sales analysis records with this Branch Code/customer');

        } else {

            $sql= "SELECT COUNT(*) FROM salesorders WHERE salesorders.branchcode='".$SelectedBranch."' AND salesorders.debtorno = '".$DebtorNo."'";
            $result = DB_query($sql,$db);

            $myrow = DB_fetch_row($result);
            if ($myrow[0]>0) {
                prnMsg(_('Cannot delete this branch because sales orders exist for it') . '. ' . _('Purge old sales orders first'),'warn');
                echo '<br />'._('There are').' ' . $myrow[0] . ' '._('sales orders for this Branch/customer');
            } else {
                // Check if there are any users that refer to this branch code
                $sql= "SELECT COUNT(*) FROM www_users WHERE www_users.branchcode='".$SelectedBranch."' AND www_users.customerid = '".$DebtorNo."'";

                $result = DB_query($sql,$db);
                $myrow = DB_fetch_row($result);

                if ($myrow[0]>0) {
                    prnMsg(_('Cannot delete this branch because users exist that refer to it') . '. ' . _('Purge old users first'),'warn');
                    echo '<br />'._('There are').' ' . $myrow[0] . ' '._('users referring to this Branch/customer');
                } else {

                    $sql="DELETE FROM custbranch WHERE branchcode='" . $SelectedBranch . "' AND debtorno='" . $DebtorNo . "'";
                    $ErrMsg = _('The branch record could not be deleted') . ' - ' . _('the SQL server returned the following message');
                        $result = DB_query($sql,$db,$ErrMsg);
                    if (DB_error_no($db)==0){
                        prnMsg(_('Branch Deleted'),'success');
                    }
                }
            }
        }
    } //end ifs to test if the branch can be deleted

}  
if (!isset($SelectedBranch)){
                  
/* It could still be the second time the page has been run and a record has been selected for modification - SelectedBranch will exist because it was sent with the new call. If its the first time the page has been displayed with no parameters then none of the above are true and the list of branches will be displayed with links to delete or edit each. These will call the same page again and allow update/input or deletion of the records*/

    $sql = "SELECT debtorsmaster.name,
            custbranch.branchcode,
            brname,
            salesman.salesmanname,
            areas.areadescription,
            contactname,
            phoneno,
            faxno,
            custbranch.email,
            taxgroups.taxgroupdescription,
            custbranch.branchcode,
            custbranch.disabletrans
       FROM custbranch,
            debtorsmaster,
            areas,
            salesman,
            taxgroups
        WHERE custbranch.debtorno=debtorsmaster.debtorno
        AND custbranch.area=areas.areacode
        AND custbranch.salesman=salesman.salesmancode
        AND custbranch.taxgroupid=taxgroups.taxgroupid
        AND custbranch.debtorno = '".$DebtorNo."'";

    $result = DB_query($sql,$db);
    $myrow = DB_fetch_row($result);
    $TotalEnable = 0;
    $TotalDisable = 0;
    if ($myrow) {
        echo '<p Class="page_title_text"><img src="'.$rootpath.'/css/'.$theme.'/images/customer.png" title="' . _('Customer') .
            '" alt="" />' . ' ' . _('Branches defined for'). ' '. $DebtorNo . ' - ' . $myrow[0] . '</p>';
        echo '<table class=selection>';
        
        echo '<tr><th>'._('Code').'</th>
                <th>'._('Name').'</th>
                <th>'._('Branch Contact').'</th>
                <th>'._('Salesman').'</th>
                <th>'._('Area').'</th>
                <th>'._('Phone No').'</th>
                <th>'._('Fax No').'</th>
                <th>'._('Email').'</th>
                <th>'._('Tax Group').'</th>
                <th>'._('Enabled?').'</th></tr>';

        $k=0;
        do {
            if ($k==1){
                echo '<tr class="EvenTableRows">';
                $k=0;
            } else {
                echo '<tr class="OddTableRows">';
                $k=1;
            }

            printf('<td>%s</td>
                <td>%s</td>
                <td>%s</td>
                <td>%s</td>
                <td>%s</td>
                <td>%s</td>
                <td>%s</td>
                <td><a href="Mailto:%s">%s</a></td>
                <td>%s</td>
                <td>%s</td>
                <td><a href="%s?DebtorNo=%s&SelectedBranch=%s">%s</td>
                <td><a href="%s?DebtorNo=%s&SelectedBranch=%s&delete=yes" onclick=\'return confirm("' . _('Are you sure you wish to delete this branch?') . '");\'>%s</td></tr>',
                $myrow[10],
                $myrow[2],
                $myrow[5],
                $myrow[3],
                $myrow[4],
                $myrow[6],
                $myrow[7],
                $myrow[8],
                $myrow[8],
                $myrow[9],
                ($myrow[11]?_('No'):_('Yes')),
                $_SERVER['PHP_SELF'],
                $DebtorNo,
                urlencode($myrow[1]),
                _('Edit'),
                $_SERVER['PHP_SELF'],
                $DebtorNo,
                urlencode($myrow[1]),
                _('Delete Branch'));
            
            if ($myrow[11]){ 
                $TotalDisable++; 
            }else { 
                $TotalEnable++; 
            }

        } while ($myrow = DB_fetch_row($result));
        //END WHILE LIST LOOP
        echo '</table><br /><table class=selection><tr><td><div class="centre">';
        echo '<b>'.$TotalEnable.'</b> ' . _('Branches are enabled.') . '<br />';
        echo '<b>'.$TotalDisable.'</b> ' . _('Branches are disabled.') . '<br />';
        echo '<b>'.($TotalEnable+$TotalDisable). '</b> ' . _('Total Branches') . '</div></td></tr></table>';
    } else {
        
        if($_GET['incidentflag']==1){
            
            $sql = "SELECT debtorsmaster.name,
                debtorsmaster.address1,
                debtorsmaster.address2,
                debtorsmaster.address3,
                debtorsmaster.address4,
                debtorsmaster.address5,
                debtorsmaster.address6
            FROM debtorsmaster
            WHERE debtorsmaster.debtorno = '".$DebtorNo."'";
            
        }else{
              
             $sql = "SELECT debtorsmaster.name,
                debtorsmaster.address1,
                debtorsmaster.address2,
                debtorsmaster.address3,
                debtorsmaster.address4,
                debtorsmaster.address5,
                debtorsmaster.address6
                
            FROM debtorsmaster
            WHERE debtorsmaster.debtorno = '".$DebtorNo."'";
        }
        
      $sql_autofill="SELECT debtorsmaster.cid,
                     debtorsmaster.stateid, 
                     debtorsmaster.did,
                     debtorsmaster.taluk,
                     debtorsmaster.LSG_type,
                     debtorsmaster.LSG_name,
                     debtorsmaster.block_name,
                     debtorsmaster.LSG_ward,
                     debtorsmaster.village                   
                FROM debtorsmaster 
                WHERE debtorsmaster.debtorno = '".$DebtorNo."'";
                
        $result_autofill = DB_query($sql_autofill,$db); 
        $myrow_autofill = DB_fetch_array($result_autofill); 
        $_POST['cid'] = $myrow_autofill['cid'];
        $_POST['stateid'] = $myrow_autofill['stateid'];
        $_POST['did'] = $myrow_autofill['did'];   
        $LSGtypeid = $myrow_autofill['LSG_type'];         
        if($LSGtypeid==1){$lsgtype="Corporation";}
        elseif($LSGtypeid==2){$lsgtype="Municipality";}
        elseif($LSGtypeid==3){$lsgtype="Panchayath";}   
        $_POST['LSG_name'] = $myrow_autofill['LSG_name'];   
        $_POST['block_name'] = $myrow_autofill['block_name'];   
        $_POST['taluk'] = $myrow_autofill['taluk']; 
        $_POST['ward'] = $myrow_autofill['LSG_ward'];
        $_POST['village'] = $myrow_autofill['village']; 

        $result = DB_query($sql,$db);
        $myrow = DB_fetch_row($result);
        echo '<div class="page_help_text">'._('No Branches are defined for').' - '.$myrow[0]. '. ' . _('You must have a minimum of one branch for each Customer. Please add a branch now.') .'</div>';
        $_POST['BranchCode'] = substr($DebtorNo,0,10);
        $_POST['BrName'] = $myrow[0];
        $_POST['BrAddress1'] = $myrow[1];
        $_POST['BrAddress2'] = $myrow[2];
        $_POST['BrAddress3'] = $myrow[3];
        $_POST['BrAddress4'] = $myrow[4];
        $_POST['BrAddress5'] = $myrow[5];
        $_POST['BrAddress6'] = $myrow[6];
          
        
        unset($myrow);
        
        $sql_cperson="SELECT bio_cust.contactperson FROM bio_cust,debtorsmaster WHERE bio_cust.debtorno=debtorsmaster.debtorno AND debtorsmaster.debtorno = '".$DebtorNo."'";
        $result_cperson=DB_query($sql_cperson,$db);
        $row_cperson=DB_fetch_array($result_cperson);
        $_POST['ContactName']=$row_cperson['contactperson'];
    }
}
                
if (!isset($_GET['delete'])) {                          
    echo '<form method="post" action="' . $_SERVER['PHP_SELF'] .'">';
    echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

    if (isset($SelectedBranch)) {                    
        //editing an existing branch

        $sql = "SELECT branchcode,
                brname,
                braddress1,
                braddress2,
                braddress3,
                braddress4,
                braddress5,
                braddress6,
                specialinstructions,
                estdeliverydays,
                fwddate,
                salesman,
                area,
                phoneno,
                faxno,
                contactname,
                email,
                taxgroupid,
                defaultlocation,
                brpostaddr1,
                brpostaddr2,
                brpostaddr3,
                brpostaddr4,
                disabletrans,
                defaultshipvia,
                custbranchcode,
                deliverblind               
            FROM custbranch
            WHERE branchcode='".$SelectedBranch."'
            AND debtorno='".$DebtorNo."'";

        $result = DB_query($sql, $db);
        $myrow = DB_fetch_array($result);

        if ($InputError==0) {
            $_POST['BranchCode'] = $myrow['branchcode'];
            $_POST['BrName']  = $myrow['brname'];
            $_POST['BrAddress1']  = $myrow['braddress1'];
            $_POST['BrAddress2']  = $myrow['braddress2'];
            $_POST['BrAddress3']  = $myrow['braddress3'];
            $_POST['BrAddress4']  = $myrow['braddress4'];
            $_POST['BrAddress5']  = $myrow['braddress5'];
            $_POST['BrAddress6']  = $myrow['braddress6'];
            $_POST['specialinstructions']  = $myrow['specialinstructions'];
            $_POST['BrPostAddr1']  = $myrow['brpostaddr1'];
            $_POST['BrPostAddr2']  = $myrow['brpostaddr2'];
            $_POST['BrPostAddr3']  = $myrow['brpostaddr3'];
            $_POST['BrPostAddr4']  = $myrow['brpostaddr4'];
            $_POST['EstDeliveryDays']  = $myrow['estdeliverydays'];
            $_POST['FwdDate'] =$myrow['fwddate'];
            $_POST['ContactName'] = $myrow['contactname'];
            $_POST['Salesman'] =$myrow['salesman'];
            $_POST['Area'] =$myrow['area'];
            $_POST['PhoneNo'] =$myrow['phoneno'];
            $_POST['FaxNo'] =$myrow['faxno'];
            $_POST['Email'] =$myrow['email'];
            $_POST['TaxGroup'] = $myrow['taxgroupid'];
            $_POST['DisableTrans'] = $myrow['disabletrans'];
            $_POST['DefaultLocation'] = $myrow['defaultlocation'];
            $_POST['DefaultShipVia'] = $myrow['defaultshipvia'];
            $_POST['CustBranchCode'] = $myrow['custbranchcode'];
            $_POST['DeliverBlind'] = $myrow['deliverblind'];
            

        }
        
        echo '<input type=hidden name="SelectedBranch" value="' . $SelectedBranch . '" />';
        echo '<input type=hidden name="BranchCode" value="' . $_POST['BranchCode'] . '" />';   
        
        echo '<p Class="page_title_text"><img src="'.$rootpath.'/css/'.$theme.'/images/customer.png" title="' . _('Customer') .
            '" alt="">' . ' ' . _('Change Details for Branch'). ' '. $SelectedBranch . '</p>';
        if (isset($SelectedBranch)) {
            echo '<div class="centre"><a href=' . $_SERVER['PHP_SELF'] . '?DebtorNo=' . $DebtorNo. '>' . _('Show all branches defined for'). ' '. $DebtorNo . '</a></div>';
        }
        echo '<br><table class="selection">';
        echo '<tr><th colspan=2><div class="centre"><b>'._('Change Branch').'</b></th></tr>';
        echo '<tr><td>'._('Branch Code').':</td><td>';

        echo $_POST['BranchCode'] . '</td></tr>';

    } else { //end of if $SelectedBranch only do the else when a new record is being entered
                                 
    /* SETUP ANY $_GET VALUES THAT ARE PASSED.  This really is just used coming from the Customers.php when a new customer is created.
            Maybe should only do this when that page is the referrer?
    */
        if (isset($_GET['BranchCode'])){

            $sql="SELECT name,
                    address1,
                    address2,
                    address3,
                    address4,
                    address5,
                    address6
                    FROM
                    debtorsmaster
                    WHERE debtorno='".$_GET['BranchCode']."'";
            $result = DB_query($sql, $db);
            $myrow = DB_fetch_array($result);
            $_POST['BranchCode'] = $_GET['BranchCode'];
            $_POST['BrName']     = $myrow['name'];
             $_POST['BrAddress1'] = $myrow['addrsss1'];
            $_POST['BrAddress2'] = $myrow['addrsss2'];
            $_POST['BrAddress3'] = $myrow['addrsss3'];
             $_POST['BrAddress4'] = $myrow['addrsss4'];
            $_POST['BrAddress5'] = $myrow['addrsss5'];
            $_POST['BrAddress6'] = $myrow['addrsss6'];      
            
            
        }
        if (!isset($_POST['BranchCode'])) {
            $_POST['BranchCode']='';
        }
        echo '<p Class="page_title_text"><img src="'.$rootpath.'/css/'.$theme.'/images/customer.png" title="' . _('Customer') . '" alt="">' . ' ' . _('Add a Branch').'</p>';
        echo '<table class=selection>
                <tr>
                    <td>'._('Branch Code'). ':</td>
                    <td><input ' .(in_array('BranchCode',$Errors) ?  'class="inputerror"' : '' ) . ' tabindex=1 type="text" name="BranchCode" size=12 maxlength=10 value="' . $_POST['BranchCode'] . '"></td>
                </tr>';
        $_POST['DeliverBlind'] = $_SESSION['DefaultBlindPackNote'];
    }



    if (DB_num_rows($result)==0){
        echo '</table>';
        prnMsg(_('There are no sales people defined as yet') . ' - ' . _('customer branches must be allocated to a sales person') . '. ' . _('Please use the link below to define at least one sales person'),'error');
        echo '<p align="center"><a href="' . $rootpath . '/SalesPeople.php">'._('Define Sales People') . '</a>';
        include('includes/footer.inc');
        exit;
    }

    echo '<input type=hidden name="DebtorNo" value="'. $DebtorNo . '" />';
    echo '<input type=hidden name="flag" value="'. $_GET['flag'] . '" />';
    echo '<input type=hidden name="leadid" value="'. $_GET['leadid'] . '" />';
     echo"<input type='hidden' name='incidentflag' id='incidentflag' value='".$_GET['incidentflag']."'>";
     echo"<input type='hidden' name='LeadType' id='leadtype' value='".$_GET['leadtype']."'>";  

    echo '<tr>
            <td>'._('Branch Name').':</td>';
    if (!isset($_POST['BrName'])) {$_POST['BrName']='';}
    echo '<td><input tabindex=2 type="text" name="BrName" size=41 maxlength=100 value="'. $_POST['BrName'].'"></td>
        </tr>';
    echo '<tr>
            <td>'._('Branch Contact').':</td>';
    if (!isset($_POST['ContactName'])) {$_POST['ContactName']='';}
    echo '<td><input tabindex=3 type="text" name="ContactName" size=41 maxlength=100 value="'. $_POST['ContactName'].'"></td>
        </tr>';
    echo '<tr><td>'._('Street Address 1 (Street)').':</td>';
    if (!isset($_POST['BrAddress1'])) {$_POST['BrAddress1']='';}
    echo '<td><input tabindex=4 type="text" name="BrAddress1" size=41 maxlength=200 value="'. $_POST['BrAddress1'].'"></td></tr>';
    echo '<tr><td>'._('Street Address 2 (Suburb/City)').':</td>';
    if (!isset($_POST['BrAddress2'])) {$_POST['BrAddress2']='';}
    echo '<td><input tabindex=5 type="text" name="BrAddress2" size=41 maxlength=200 value="'. $_POST['BrAddress2'].'"></td></tr>';
    echo '<tr><td>'._('Street Address 3 (State)').':</td>';
    if (!isset($_POST['BrAddress3'])) {$_POST['BrAddress3']='';}
    echo '<td><input tabindex=6 type="text" name="BrAddress3" size=41 maxlength=200 value="'. $_POST['BrAddress3'].'"></td></tr>'; 
      
//--------------------------------------

//---------country--------------//    
    
    echo"<tr><td>Country*</td><td>";
    echo '<select name="country" id="country" tabindex=6 onchange="showstate(this.value)" style="width:190px">';
    
    $sql="SELECT * FROM bio_country ORDER BY cid";     $result=DB_query($sql,$db);
    
    $f=0;
    while($myrow1=DB_fetch_array($result))    
    {                                              
        if ($myrow1['cid']==$_POST['cid'])   
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
        echo $myrow1['cid'] . '">'.$myrow1['country'];
        echo '</option>';
        $f++;
   } 
   echo '</select></td></tr>';
   
   //--------------state-----------------//


     echo"<tr id='showstate'>";
     echo"<td>State*</td><td>";
     echo '<select name="State" id="state" style="width:190px" tabindex=7 onchange="showdistrict(this.value)">';
     
     $sql="SELECT * FROM bio_state WHERE cid='".$_POST['cid']."' ORDER BY stateid";        $result=DB_query($sql,$db);   
 
    $f=0;
    while($myrow1=DB_fetch_array($result))
    {
        if ($myrow1['stateid']==$_POST['stateid'])
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
   echo '</select></td>'; 
   echo'</tr>';
   
   //-------------District---------------//  

 
     echo"<tr id='showdistrict'>";
     echo"<td>District*</td><td>";
     echo '<select name="District" id="Districts" style="width:190px" tabindex=8 onchange="showtaluk(this.value)">';          
 
     $sql="SELECT * FROM bio_district WHERE cid='".$_POST['cid']."' AND stateid='".$_POST['stateid']."' ORDER BY did";      $result=DB_query($sql,$db);   
 
     $f=0;
     while($myrow1=DB_fetch_array($result))
     {
        if ($myrow1['did']==$_POST['did'])
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
   echo '</select></td>';
   echo'</tr>'; 
   
    echo '<tr><td>' . _('LSG Type') . ':</td>';
    echo '<td><select name="lsgType" id="lsgType" style="width:190px" tabindex=9 onchange=showblock(this.value)>';               
    echo '<option value='.$LSGtypeid.'>'.$lsgtype.'</option>'; 
    echo '<option value=1>Corporation</option>';
    echo '<option value=2>Municipality</option>';
    echo '<option value=3>Panchayat</option>';           
    echo '</select></td></tr>'; 
    
    echo '<tr><td align=left colspan=2>';
    echo'<div style="align:left" id=block>';
    
        if($LSGtypeid==1) 
        {
        
        $sql="SELECT * FROM bio_corporation WHERE country='".$_POST['cid']."' AND state='".$_POST['stateid']."' AND district='".$_POST['did']."'";
        $result=DB_query($sql,$db);
        $row=DB_fetch_array($result);
        $cid=$row['country'];
        $sid=$row['state'];
        $district=$row['district'];


                 //echo"11111111"; 
          if($_POST['cid']==1 && $_POST['stateid']==14)  
          {
              if($_POST['did']==12){$distname='Thiruvananthapuram';}
              if($_POST['did']==6){$distname='Kollam';} 
              if($_POST['did']==2){$distname='Eranakulam';} 
              if($_POST['did']==13){$distname='Thrissur';} 
              if($_POST['did']==8){$distname='Kozhikode';} 
                    echo '<table align=left ><tr><td width=200px>' . _('Corporation Name') . ':</td>';
                    echo '<td><select name="lsgName" readonly id="lsgName" style="width:190px">';
                    echo "<option value='".$_POST['did']."'>".$distname."</option>"; 
                    echo '</select></td>';    
                    echo '</tr></table>';      
          }
        
        }elseif($LSGtypeid==2) 
        {
            //echo"2222222";
        echo '<table align=left ><tr><td width=200px>' . _('Municipality Name') . ':</td>';    
        
        $sql="SELECT * FROM bio_municipality WHERE country='".$_POST['cid']."' AND state='".$_POST['stateid']."' AND district='".$_POST['did']."'";
        $result=DB_query($sql,$db);
        
        echo '<td><select name="lsgName" id="lsgName" style="width:190px">';
         $f=0;
         while($myrow1=DB_fetch_array($result))
         {
         if ($myrow1['id']==$_POST['LSG_name'])
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
      echo'</td></tr></table>'; 
        
        }elseif($LSGtypeid==3) 
        {
            //echo"3333333"; 
         echo '<table align=left ><tr><td width=200px>' . _('Block Name') . ':</td>';    
         
         $sql="SELECT * FROM bio_block WHERE country='".$_POST['cid']."' AND state='".$_POST['stateid']."' AND district='".$_POST['did']."'";
         $result=DB_query($sql,$db);
         
         echo '<td><select name="lsgName" id="lsgName" style="width:190px" tabindex=11>';
         $f=0;
         while($myrow1=DB_fetch_array($result))
         {
         if ($myrow1['id']==$_POST['LSG_name'])
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
      echo'</tr>'; 
      
      echo '<tr><td>' . _('Panchayat Name') . ':</td>';         //grama panchayath
         
         $sql="SELECT * FROM bio_panchayat WHERE country='".$_POST['cid']."' AND state='".$_POST['stateid']."' AND district='".$_POST['did']."'";
         $result=DB_query($sql,$db);
         
         echo '<td><select name="gramaPanchayath" id="gramaPanchayath" style="width:190px" tabindex=11>';
         $f=0;
         while($myrow1=DB_fetch_array($result))
         {
         if ($myrow1['id']==$_POST['block_name'])
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
      echo'</tr></table>';      
            
        }
                    
        echo'</div>';
        echo'</td></tr>';
        
        
        
               echo '<tr><td>' . _('LSG (Ward No/Ward Name)') . ':</td>
                  <td><input tabindex=9 type="Text" name="lsgWard" id="lsgWard" style=width:190px maxlength=15 value="'.$_POST['ward'].'"></td></tr>';  
                  
      echo"<tr><td>Taluk*</td><td>";
      $sql="SELECT * FROM bio_taluk WHERE bio_taluk.country='".$_POST['cid']."' AND bio_taluk.state='".$_POST['stateid']."' AND bio_taluk.district='".$_POST['did']."'";
      $result=DB_query($sql,$db);
      echo '<select name="taluk" id="taluk" style="width:190px" tabindex=11>';
      $f=0;
      while($myrow1=DB_fetch_array($result))
      {
      if ($myrow1['id']==$_POST['taluk'])
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
      echo $myrow1['id'] . '">'.$myrow1['taluk'];
      echo '</option>';
      $f++;
      }
      echo '</select>';
      echo'</td></tr>';     
      
      
    echo"<tr id='showvillage'>";
        echo"<td>Village</td><td>";
    $sql="SELECT * FROM bio_village WHERE bio_village.country='".$_POST['cid']."' AND bio_village.state='".$_POST['stateid']."' AND bio_village.district='".$_POST['did']."' AND bio_village.taluk='".$_POST['taluk']."'";



    $result=DB_query($sql,$db);
  echo '<select name="village" id="village" tabindex=12 style="width:190px">';
   $f=0;
  while($myrow1=DB_fetch_array($result))
  {
  if ($myrow1['id']==$_POST['village'])
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
    echo $myrow1['id'] . '">'.$myrow1['village'];
    echo '</option>';
    $f++;
   }

  echo '</select>';
  echo'</td>';
  echo"</tr>";        

//--------------------------------------

    echo '<tr><td>'._('Special Instructions').':</td>';
    if (!isset($_POST['specialinstructions'])) {$_POST['specialinstructions']='';}
    echo '<td><input tabindex=10 type="text" name="specialinstructions" size=56 value="'. $_POST['specialinstructions'].'"></td></tr>';
    echo '<tr style="display:none"><td>'._('Default days to deliver').':</td>';
    if (!isset($_POST['EstDeliveryDays'])) {$_POST['EstDeliveryDays']=0;}
    echo '<td><input ' .(in_array('EstDeliveryDays',$Errors) ?  'class="inputerror"' : '' ) .' tabindex=11 type="text" class=number name="EstDeliveryDays" size=4 maxlength=2 value='. $_POST['EstDeliveryDays'].'></td></tr>';
    echo '<tr style="display:none"><td>'._('Forward Date After (day in month)').':</td>';
    if (!isset($_POST['FwdDate'])) {$_POST['FwdDate']=0;}
    echo '<td><input ' .(in_array('FwdDate',$Errors) ?  'class="inputerror"' : '' ) .' tabindex=12 type="text" class=number name="FwdDate" size=4 maxlength=2 value='. $_POST['FwdDate'].'></td></tr>';

    echo '<tr><td>'._('Salesperson').':</td>';
    echo '<td><select tabindex=13 name="Salesman">';
    
        //SQL to poulate account selection boxes
    $sql = "SELECT salesmanname, 
                    salesmancode 
            FROM salesman 
            WHERE current = 1";

    $result = DB_query($sql,$db);

    while ($myrow = DB_fetch_array($result)) {
        if ( $myrow['salesmancode']==$_POST['Salesman']) {        // isset($_POST['Salesman']) and
            echo '<option selected value=';
        } else {
            echo '<option value=';
        }
        echo $myrow['salesmancode'] . '>' . $myrow['salesmanname'] . '</option>';

    } //end while loop

    echo '</select></td></tr>';

    DB_data_seek($result,0);

    $sql = "SELECT areacode, areadescription FROM areas order by areadescription desc";
    $result = DB_query($sql,$db);
    if (DB_num_rows($result)==0){
        echo '</table>';
        prnMsg(_('There are no areas defined as yet') . ' - ' . _('customer branches must be allocated to an area') . '. ' . _('Please use the link below to define at least one sales area'),'error');
        echo '<br /><a href="' . $rootpath. '/Areas.php">'._('Define Sales Areas').'</a>';
        include('includes/footer.inc');
        exit;
    }

    echo '<tr><td>'._('Sales Area').':</td>';
    echo '<td><select tabindex=14 name="Area">';
    while ($myrow = DB_fetch_array($result)) {
        if (isset($_POST['Area']) and $myrow['areacode']==$_POST['Area']) {
            echo '<option selected value="';
        } else {
            echo '<option value="';
        }
        echo $myrow['areacode'] . '">' . $myrow['areadescription'] . '</option>';

    } //end while loop


    echo '</select></td></tr>';
    DB_data_seek($result,0);

    $sql = "SELECT loccode, locationname FROM locations";
    $result = DB_query($sql,$db);

    if (DB_num_rows($result)==0){
        echo '</table>';
        prnMsg(_('There are no stock locations defined as yet') . ' - ' . _('customer branches must refer to a default location where stock is normally drawn from') . '. ' . _('Please use the link below to define at least one stock location'),'error');
        echo '<br /><a href="' . $rootpath . '/Locations.php">'._('Define Stock Locations').'</a>';
        include('includes/footer.inc');
        exit;
    }

    echo '<tr style="display:none"><td>'._('Draw Stock From').':</td>';
    echo '<td><select tabindex=15 name="DefaultLocation">';

    while ($myrow = DB_fetch_array($result)) {
        if (isset($_POST['DefaultLocation']) and $myrow['loccode']==$_POST['DefaultLocation']) {
            echo '<option selected value="';
        } else {
            echo '<option value="';
        }
        echo $myrow['loccode'] . '">' . $myrow['locationname'] . '</option>';

    } //end while loop

    echo '</select></td></tr>';  
    $mobile=$_SESSION['Fax'];  
    $phone=$_SESSION['Phone'];
    $email=$_SESSION['Email'];
//echo $_SESSION['Phone'].'trrrere';   
    echo '<tr><td>'._('Phone Number').':</td>';
//    if (!isset($_POST['PhoneNo'])) 
    echo '<td><input tabindex=16 type="Text" name="PhoneNo" size=22 maxlength=20 value="'. $phone.'"></td></tr>';

    echo '<tr><td>'._('Fax Number').':</td>';
    //if (!isset($_POST['FaxNo'])) 
    echo '<td><input tabindex=17 type="Text" name="FaxNo" size=22 maxlength=20 value="'. $mobile.'"></td></tr>';
    echo '<tr><td>'.(($email) ? '<a href="Mailto:'.$email.'">'._('Email').':</a>' : _('Email').':').'</td>';
      //only display email link if there is an email address
    echo '<td><input tabindex=18 type="text" name="Email" size=56 maxlength=100 value="'. $email.'"></td></tr>';

    
unset($_SESSION['Phone']);
unset($_SESSION['Fax']);    
unset($_SESSION['Email']);    

    //if (!isset($_POST['Email'])) {$_POST['Email']='';}
    echo '<tr style="display:none"><td>'._('Tax Group').':</td>';
    echo '<td><select tabindex=19 name="TaxGroup">';

    DB_data_seek($result,0);

    $sql = "SELECT taxgroupid, taxgroupdescription FROM taxgroups";
    $result = DB_query($sql,$db);

    while ($myrow = DB_fetch_array($result)) {
        if (isset($_POST['TaxGroup']) and $myrow['taxgroupid']==$_POST['TaxGroup']) {
            echo '<option selected value="';
        } else {
            echo '<option value="';
        }
        echo $myrow['taxgroupid'] . '">' . $myrow['taxgroupdescription'] . '</option>';

    } //end while loop

    echo '</select></td></tr>';
    echo '<tr style="display:none"><td>'._('Transactions on this branch') . ':</td>
        <td><select tabindex=20 name="DisableTrans">';
    if ($_POST['DisableTrans']==0){
        echo '<option selected value=0>' . _('Enabled') . '</option>';
        echo '<option value=1>' . _('Disabled') . '</option>';
    } else {
        echo '<option selected value=1>' . _('Disabled') . '</option>';
        echo '<option value=0>' . _('Enabled') . '</option>';
    }

    echo '    </select></td></tr>';

    echo '<tr style="display:none"><td>'._('Default freight/shipper method') . ':</td>
            <td><select tabindex=21 name="DefaultShipVia">';
    $SQL = "SELECT shipper_id, shippername FROM shippers";
    $ShipperResults = DB_query($SQL,$db);
    while ($myrow=DB_fetch_array($ShipperResults)){
        if (isset($_POST['DefaultShipVia'])and $myrow['shipper_id']==$_POST['DefaultShipVia']){
            echo '<option selected value=' . $myrow['shipper_id'] . '>' . $myrow['shippername'] . '</option>';
        }else {
            echo '<option value=' . $myrow['shipper_id'] . '>' . $myrow['shippername'] . '</option>';
        }
    }

    echo '</select></td></tr>';

    /* This field is a default value that will be used to set the value
    on the sales order which will control whether or not to display the
    company logo and address on the packlist */
    echo '<tr style="display:none"><td>' . _('Default Packlist') . ':</td><td><select tabindex=22 name="DeliverBlind">';
    if ($_POST['DeliverBlind']==2){
        echo '<option value="1">' . _('Show company details and logo') . '</option>';
        echo '<option selected  value="2">' . _('Hide company details and logo') . '</option>';
    } else {
        echo '<option selected value="1">' . _('Show company details and logo') . '</option>';
        echo '<option value="2">' . _('Hide company details and logo') . '</option>';
    }
    
    echo '</select></td></tr>';

    echo '<tr><td>'._('Postal Address 1 (Street)').':</td>';
    if (!isset($_POST['BrPostAddr1'])) {$_POST['BrPostAddr1']='';}
    echo '<td><input tabindex=23 type="Text" name="BrPostAddr1" size=41 maxlength=200 value="'. $_POST['BrPostAddr1'].'"></td></tr>';
    echo '<tr><td>'._('Postal Address 2 (Suburb/City)').':</td>';
    if (!isset($_POST['BrPostAddr2'])) {$_POST['BrPostAddr2']='';}
    echo '<td><input tabindex=24 type="Text" name="BrPostAddr2" size=41 maxlength=200 value="'. $_POST['BrPostAddr2'].'"></td></tr>';
    echo '<tr><td>'._('Postal Address 3 (State)').':</td>';
    if (!isset($_POST['BrPostAddr3'])) {$_POST['BrPostAddr3']='';}
    echo '<td><input tabindex=25 type="Text" name="BrPostAddr3" size=31 maxlength=200 value="'. $_POST['BrPostAddr3'].'"></td></tr>';
    echo '<tr><td>'._('Postal Address 4 (Postal Code)').':</td>';
    if (!isset($_POST['BrPostAddr4'])) {$_POST['BrPostAddr4']='';}
    echo '<td><input tabindex=26 type="Text" name="BrPostAddr4" size=21 maxlength=20 value="'. $_POST['BrPostAddr4'].'"></td></tr>';
    echo '<tr style="display:none"><td>'._('Customers Internal Branch Code (EDI)').':</td>';
    if (!isset($_POST['CustBranchCode'])) {$_POST['CustBranchCode']='';}
    echo '<td><input tabindex=27 type="Text" name="CustBranchCode" size=31 maxlength=30 value="'. $_POST['CustBranchCode'].'"></td></tr>';
    echo '</table>';
    echo '<br /><div class="centre"><input tabindex=28 type="submit" name="submit" value="' . _('Enter Branch') . '"></div>';
    echo '</form>';

} //end if record deleted no point displaying form to add record

include('includes/footer.inc');
?>

<script type="text/javascript"> 

 function showstate(str){ 
  //alert(str);
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
    document.getElementById("state").focus();
    }
  }
xmlhttp.open("GET","bio_CustlsgSelection.php?country=" + str,true);
xmlhttp.send();
}

function showdistrict(str){       //alert(str);
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
           document.getElementById('Districts').focus();

    }
  }
xmlhttp.open("GET","bio_CustlsgSelection.php?state=" + str + "&country1=" + str1,true);
xmlhttp.send();
}

 function showblock(str){   
     str1=document.getElementById("country").value;
     str2=document.getElementById('state').value;
     str3=document.getElementById('Districts').value;
     if(str==1 && (str3==1 || str3==3 || str3==4 || str3==5 || str3==7 || str3==9 || str3==10 || str3==11 || str3==14)){
         alert("No Corporation for this district");
         document.getElementById("block").innerHTML="";
         return;
     }
//alert(str1);   alert(str2);       alert(str3);
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
xmlhttp.open("GET","bio_CustlsgSelection.php?block=" + str +"&country1="+ str1 +"&state1="+ str2 +"&district="+ str3,true);
xmlhttp.send(); 
} 

 </script>   