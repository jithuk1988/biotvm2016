<?php
  $PageSecurity = 80;  
  include('includes/session.inc');
  $PaperSize ='A4';
  include('includes/PDFStarter.php'); 
  $leadid=$_GET['lead'];
  $leadtaskid=$_GET['tid'];    
  $FontSize=9;
  $PageNumber=1;
  $line_height=12;
  $Xpos = $Left_Margin+1;
  $WhereCategory = " ";
  $CatDescription = " ";
//==================================================================================================================//
$crdt=date("Y-m-d H:i:s");
 $emp_ID=$_SESSION['empid']; 
        $sql_team="SELECT teamid FROM bio_teammembers WHERE empid=".$emp_ID;
        $result_team=DB_query($sql_team,$db);
        $row_team=DB_fetch_array($result_team);
        $assignedfrm=$row_team['teamid'];
      


 $sql_flag="UPDATE bio_leadtask SET taskcompletedstatus = '1',
                                          taskcompleteddate='".$crdt."' 
                   WHERE bio_leadtask.leadid=".$leadid." 
                     AND bio_leadtask.taskid=2 
                     AND taskcompletedstatus!=2
                     AND taskcompletedstatus!=1
                     AND tid=$leadtaskid";   
    DB_query($sql_flag,$db);




 $sql1="SELECT bio_leads.leadid,
               bio_cust.cust_id,
               bio_cust.custname,
               bio_cust.contactperson,
               bio_cust.custphone,
               bio_cust.pin,
               bio_cust.custmob,
               bio_cust.custmail,
               bio_cust.houseno,
               bio_cust.housename,
               bio_cust.area1,
               bio_cust.area2,
               bio_cust.head_org,
               bio_cust.headdesig,     
               bio_cust.headphone,
               bio_cust.contact_desig,
               bio_cust.nationality,
               bio_cust.state AS State,
               bio_cust.district AS District,
               bio_cust.nature_org,
               bio_cust.headmob,
               bio_cust.LSG_type,
               bio_cust.block_name,
               bio_cust.village,
               bio_cust.LSG_name,
               bio_cust.LSG_ward,     
               bio_cust.taluk,
               bio_cust.nature_org,
               bio_district.district,
               bio_state.state,
               bio_leads.leaddate,
               bio_leadtask.taskcompleteddate,
               bio_leadtask.teamid,
               bio_status.biostatus 
         FROM  bio_leads,
               bio_cust,
               bio_district,
               bio_status,
               bio_state,
               bio_leadtask
              
         WHERE bio_leads.cust_id=bio_cust.cust_id
           AND bio_status.statusid=bio_leads.leadstatus     
           AND bio_district.did=bio_cust.district
           AND bio_district.stateid=bio_cust.state
           AND bio_district.cid=bio_cust.nationality
           AND bio_state.stateid=bio_cust.state
           AND bio_state.cid=bio_cust.nationality
           AND bio_leadtask.leadid=bio_leads.leadid
           AND bio_leads.leadid=".$leadid."
           AND bio_leadtask.tid=".$leadtaskid;
           
   $result1=DB_query($sql1,$db); 
   $myrow2=DB_fetch_array($result1);                
   $custid=$myrow2['cust_id'];
   $institution=$myrow2['custname'];
   $custname=$myrow2['contactperson'];   
   $phoneno=$myrow2['custphone'];
   $mobno=$myrow2['custmob']; 
   $place=$myrow2['area1'];
   $email=$myrow2['custmail'];   
   $houseno=$myrow2['houseno'];    
   $pincode=$myrow2['pin'];
   $district=$myrow2['district'];      
   $state=$myrow2['state'];
   $nationality=$myrow2['nationality'];                        
   $doorno=$myrow2['houseno'];    
   $street=$myrow2['housename'];   
   $post=$myrow2['area2']; 
   $headorg=$myrow2['head_org'];  
   $headdesig=$myrow2['headdesig'];
   $head_phone=$myrow2['headphone'];
   $head_mob=$myrow2['headmob'];
   $contactdesig=$myrow2['contact_desig'];             
   $leaddate=ConvertSQLDate($myrow2['leaddate']);        
   $lsgtype=$myrow2['LSG_type'];             
   $nature_org=$myrow2['nature_org'];
   $lsgid=$myrow2['LSG_name'];
   $panchayath=$myrow2['block_name'];  
   $state1=$myrow2['State'];
   $district1=$myrow2['District'];                                  
 
                           
   if($phoneno!="" AND $mobno!=""){
       $number=$phoneno." , ".$mobno;
   }elseif($phoneno!=""){
       $number=$phoneno;
   }elseif($mobno!=""){
       $number=$mobno;
   }    
   if($houseno=='0'){
   $houseno="";
   }
   if($place=='0'){   
   $place="";
   } 
   if($email=='0'){
       $email="";
  }  
   if($pincode=='0'){ 
     $pincode="";
   } 
   if($district=='0'){
       $district="";
   } 
   if($state=='0'){
       $state="";
   } 
    if($doorno=='0'){
       $doorno="";
   }
   if($street=='0'){
       $street="";
   } 
   if($post=='0'){
       $post="";
   }  
   if($headorg=='0'){
       $headorg="";
   }  
    if($headdesig=='0'){
       $headdesig="";
   }  
    if($head_phone=='0'){
       $head_phone="";
   }  
    if($contactdesig=='0'){
       $contactdesig="";
   }   
   
   $team=$myrow2['teamid'];
   $completed=ConvertSQLDate($myrow2['taskcompleteddate']);
 
   //$to=ConvertSQLDate($myrow2['duedate']);    
  $sql_team="SELECT * FROM bio_emp,bio_leadteams,bio_teammembers 
        WHERE bio_leadteams.teamid=bio_teammembers.teamid
        AND bio_emp.empid=bio_teammembers.empid
        AND bio_leadteams.teamid=".$team; 
     $result_team=DB_query($sql_team,$db); 
     $myrow=DB_fetch_array($result_team);
     $officername=$myrow['empname'];
      
 if($nature_org!=NULL){   
   $sql_org="SELECT bio_inst_nature.nature 
               FROM bio_inst_nature     
              WHERE bio_inst_nature.nature_id=".$nature_org;     
     
    $result_org=DB_query($sql_org,$db);
    $myrow2=DB_fetch_array($result_org);
    $instname=$myrow2['nature'];
 }  
    
         
    if($instname=='0'){
       $instname="";
   }        
     
//==============================================================================================================//
    $FontSize=9;
    $YPos= $Page_Height-$Top_Margin;   
    $line_height=15;        
    $Xpos = $Left_Margin+5;
    $YPos -=(2*$line_height);       
    $img1='companies/'.$_SESSION['DatabaseName'].'/logo.jpg';
    $pdf->addJpegFromFile($img1,$XPos+392,$YPos-=$line_height,0,55);
    $img2= 'companies/'.$_SESSION['DatabaseName'].'/logof.jpg';
    $pdf->addJpegFromFile($img2,$XPos+35,$YPos,0,55);  
    $pdf->line($Left_Margin,$YPos-2,$Left_Margin+534,$YPos-2);
//=============================================================================================================//  
   $pdf->addTextWrap($Left_Margin+200,$YPos-=$line_height+5,100,$FontSize+3,_('INSTITUTIONAL DETAILS'), 'center');      
   $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+5,200,$FontSize+1,_('Customer ID :'), 'left'); 
   $pdf->addTextWrap($Left_Margin+64,$YPos-=$line_height-15,900,$FontSize+1,$custid,'',0); 
   $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+5,900,$FontSize+1,_('Name of Institution                                  :'), 'left');    
   $pdf->addTextWrap($Left_Margin+184,$YPos-=$line_height-15,900,$FontSize+1,$institution,'',0);
   $string5="Building No/Name :  ".$doorno."   Street : ".$street."";  
   $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+5,900,$FontSize+1,_('Full postal address                                  : '.$string5), 'left');  
 // $pdf->addTextWrap($Left_Margin+230,$YPos-=$line_height+10,900,$FontSize+1,$string5,'',0);    
   $pdf->addTextWrap($Left_Margin+184,$YPos-=$line_height+5,200,$FontSize+1,_('Place :  '.$place), 'left');
   //$pdf->addTextWrap($Left_Margin+215,$YPos-=$line_height-15,900,$FontSize+1,,$place,'',0);
 //$pdf->addTextWrap($Left_Margin+183,$YPos-=$line_height-15,900,$FontSize+1,$place,'',0);    
   $string5="Post :  ".$post."   Pincode : ".$pincode."";                   
   $pdf->addTextWrap($Left_Margin+183,$YPos-=$line_height+5,900,$FontSize+1,$string5,'',0);  
    if($lsgtype==1) {
        if($lsgid==12){
         $lsgname='Thiruvananthapuram';
         }
       if($lsgid==6)
       {
           $lsgname='Kollam';
       }            
        if($lsgid==2)
        {
            $lsgname='Eranakulam';
        }
        if($lsgid==8)
        {
            $lsgname='Kozhikode';
        }
         if($lsgid==13)
         {
             $lsgname='Thrissur';
         } 
     $lsg_name="Corporation:".$lsgname;    
                                      
    }
   if($lsgtype==2) {      
   $sql="SELECT * FROM bio_municipality WHERE country=$nationality AND state=$state1 AND district=$district1 AND id=$lsgid";
        $result=DB_query($sql,$db);  
    $myrow1=DB_fetch_array($result);
        
         $lsgname=$myrow1['municipality'];
         $lsg_name="Municipality:".$lsgname;
    }
    if($lsgtype==3) { 
        
        $sql="SELECT * FROM bio_block WHERE country=$nationality AND state=$state1 AND district=$district1 AND id=$lsgid";
        $result=DB_query($sql,$db);
        $myrow1=DB_fetch_array($result);
        $lsgname=$myrow1['block'];
         
        $sql="SELECT * FROM bio_panchayat WHERE country=$nationality AND state=$state1 AND district=$district1 AND id=$panchayath";
        $result=DB_query($sql,$db);
        $myrow1=DB_fetch_array($result);
        $panchayath_name=$myrow1['name'];
        $lsg_name="Panchayath:".$panchayath_name." Block:".$lsgname;      
    }
   if($lsgtype!=NULL){
      $pdf->addTextWrap($Left_Margin+178,$YPos-=$line_height+5,900,$FontSize+1,_(':'),'left'); 
     $pdf->addTextWrap($Left_Margin+183,$YPos,900,$FontSize+1,$lsg_name,'',0);  
   }  
   $string3= "".$district." , ".$state."";                           
   $pdf->addTextWrap($Left_Margin+183,$YPos-=$line_height+5,900,$FontSize+1,$string3,'',0);
   $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+5,900,$FontSize+1,_('E-mail                                                     :'), 'left'); 
   $pdf->addTextWrap($Left_Margin+183,$YPos-=$line_height-15,900,$FontSize+1,$email,'',0); 
   $string= "Contact Telephone Numbers                 : ".$number."";   
   $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+5,900,$FontSize+1,$string,'',0); 
   $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+5,900,$FontSize+1,_('Nature of Institution                                : '.$instname), 'left'); 
   //$string1= ": Designation : ".$headdesig."                    Contact No : ".$mobno."";
//   $pdf->addTextWrap($Left_Margin+175,$YPos-=$line_height+5,900,$FontSize+1,$string1,'',0);
   $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+5,900,$FontSize+1,_('Head of the Organisation                       : '.$headorg), 'left');  
   //$pdf->addTextWrap($Left_Margin+175,$YPos-=$line_height+5,900,$FontSize+1,_(': Designation : .............................................Contact No :.............................'), 'left');
   $stringdesg= ": Designation : ".$headdesig."                Contact No : ".$head_phone.",".$head_mob;
   $pdf->addTextWrap($Left_Margin+175,$YPos-=$line_height+5,900,$FontSize+1,$stringdesg,'',0);
   $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+5,900,$FontSize+1,_('Contact person of the Organisation       :'), 'left'); 
   $pdf->addTextWrap($Left_Margin+182,$YPos-=$line_height-15,900,$FontSize+1,$custname,'',0);
   $string1= ": Designation : ".$contactdesig."                Contact No : ".$mobno."";
   $pdf->addTextWrap($Left_Margin+175,$YPos-=$line_height+5,900,$FontSize+1,$string1,'',0);     
   $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+5,900,$FontSize+1,_('Date of Application: '.$leaddate), 'left');
   $fs_date='  Date of Feasibility Study: '.$completed; 
   $pdf->addTextWrap($Left_Margin+180,$YPos,900,$FontSize+1,$fs_date, 'left');
   $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+5,900,$FontSize+1,_('Name of the Officers conducted the feasibility study'), 'left');  
   $employee=' :  '. $officername;
   $pdf->addTextWrap($Left_Margin+175,$YPos-=$line_height+5,900,$FontSize+1,$employee, 'left');  
   //$pdf->addTextWrap($Left_Margin+175,$YPos-=$line_height+5,900,$FontSize+1,_('2. : ................................................................'), 'left');  
   $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+5,900,$FontSize+1,_('Time spent for the study                         : ....................................................................'), 'left');   
   $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+5,900,$FontSize+1,_('Name of the Officer represented by the Institution'), 'left');  
   $pdf->addTextWrap($Left_Margin+175,$YPos-=$line_height+5,900,$FontSize+1,_('1 : .................................................................'), 'left'); 
   $pdf->addTextWrap($Left_Margin+175,$YPos-=$line_height+5,900,$FontSize+1,_('2 : .................................................................'), 'left'); 
   $pdf->addTextWrap($Left_Margin+90,$YPos-=$line_height+10,900,$FontSize+2,_('ROUTE TO THE ORGANISATION FROM THE NEAREST TOWN '), 'left'); 
   $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+100,900,$FontSize+1,_('For Office Use'), 'left');  
   $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height+10,900,$FontSize,_('Date of submission of the feasibility study....................... Date of Despatch of DPR................................................'), 'left');   
   $pdf->addTextWrap($Left_Margin+20,$YPos-=$line_height+5,900,$FontSize,_('Date on whioh detailed project preferred (DPR)..............................Action Taken on DPR.......................................'), 'left');   
   $YPos=$Bottom_Margin + $line_height;
// $page='Page No '.$PageNumber;
// $pdf->addTextWrap($Left_Margin+230,$YPos,800,$FontSize,$page,'',0); 
   $pdf->OutputD($_SESSION['DatabaseName'] . 'Register report_' . Date('Y-m-d') . '.pdf');
   $pdf->__destruct();  
?>






                




