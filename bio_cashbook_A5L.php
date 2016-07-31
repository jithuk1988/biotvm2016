<?php
  $PageSecurity = 80;  
  include('includes/session.inc');
  $PaperSize ='A4';    
  
  include('includes/PDFStarter.php');
  $FontSize=9;
  $PageNumber=1;
  $line_height=12;

  $Xpos = $Left_Margin+1;
  $WhereCategory = " ";
  $CatDescription = " ";
  
  $FontSize=9;
  $YPos= $Page_Height-$Top_Margin;   
  $line_height=15;        
  $Xpos = $Left_Margin+5;
  $YPos -=(2*$line_height);
  
  $user_ID=$_SESSION['UserID'];
  
  $sql_emp="SELECT  bio_emp.empname
        FROM  bio_emp,www_users
        WHERE www_users.empid = bio_emp.empid
        AND www_users.userid='".$user_ID."'";
  $result_emp=DB_query($sql_emp,$db);
  $myrow_emp=DB_fetch_array($result_emp);
  $empname=$myrow_emp['empname'];
  
  $sql_ledger="SELECT bio_advance.adv_id,
                        bio_advance.paydate, 
                        bio_advance.head_id, 
                        bio_advance.amount, 
                        bio_advance.mode_id, 
                        bio_advance.serialnum, 
                        bio_advance.bankname,
                        bio_advance.leadid, 
                        bio_advance.status, 
                        bio_cashheads.heads, 
                        bio_paymentmodes.modes,
                        bio_cust.custname
                  FROM  bio_advance, bio_cashheads, bio_paymentmodes,bio_cust,bio_leads
                 WHERE  bio_advance.collected_by = '$user_ID'
                   AND  bio_cashheads.head_id = bio_advance.head_id 
                   AND  bio_paymentmodes.id = bio_advance.mode_id
                   AND  bio_cust.cust_id=bio_leads.cust_id
                   AND  bio_leads.leadid=bio_advance.leadid  order by adv_id asc";
    
    
    $result_ledger=DB_query($sql_ledger,$db,'','',false,true);
    $recptno=array();
    $date=array();
    $customer=array();
    $description=array();
    $payment_mode=array();
    $amount=array();
    $mode_id=array();
    While ($myrow = DB_fetch_array($result_ledger,$db)){
        
        $recptno[]=$myrow['adv_id'];
        $date[]=ConvertSQLDate($myrow['paydate']);
        $customer[]=$myrow['custname'];
        $description[]=$myrow['heads'];
        $payment_mode[]=$myrow['modes'];
        $amount[]=$myrow['amount'];
        $mode_id[]=$myrow['mode_id'];
    }
  $total_count=sizeof($recptno);
  
  
    
    
  $img1='companies/'.$_SESSION['DatabaseName'].'/logo.jpg';
  $pdf->addJpegFromFile($img1,$XPos+392,$YPos-=$line_height,0,55);
//    echo"1st".$YPos;
  $img2= 'companies/'.$_SESSION['DatabaseName'].'/logof.jpg';
  $pdf->addJpegFromFile($img2,$XPos+35,$YPos,0,55);  
  $pdf->line($Left_Margin,$YPos-2,$Left_Margin+534,$YPos-2);
  
  $cashier='Cashier : '.$empname;
  $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+12,100,$FontSize+2,$cashier, 'left');
  $pdf->addTextWrap($Left_Margin+220,$YPos,100,$FontSize+5,'Cash Book', 'center');
  $pdf->line($Left_Margin+235,$YPos-2,$Left_Margin+305,$YPos-2); 
  $pdf->addTextWrap($Page_Width-$Right_Margin-80,$YPos,90,$FontSize+2,Date($_SESSION['DefaultDateFormat']), 'right');
  
/*  
  $pdf->SetFillColor(255);
$pdf->SetTextColor(0);
$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(.3);

  $header=array('Sl No.','Rcpt No.','Customer', 'Description', 'Payment Mode', 'Amount', 'Remark');
  $w = array(100, 100, 100, 100, 100, 100, 100, 100);
  for($i=0;$i<count($header); $i++)
        $pdf->Cell($w[$i],7, $header[$i], 1, 0, 'L', true);

*/  
  
  $pdf->addTextWrap($Left_Margin+5,$YPos-=$line_height+20,100,$FontSize+1,_('Sl No.'), 'left');
  $pdf->addTextWrap($Left_Margin+40,$YPos,100,$FontSize+1,_('Rcpt No.'), 'left');
  $pdf->addTextWrap($Left_Margin+95,$YPos,100,$FontSize+1,_('Date'), 'left');
  $pdf->addTextWrap($Left_Margin+140,$YPos,100,$FontSize+1,_('Customer'), 'left');
  $pdf->addTextWrap($Left_Margin+260,$YPos,100,$FontSize+1,_('Description'), 'left');
  $pdf->addTextWrap($Left_Margin+390,$YPos,100,$FontSize+1,_('Payment Mode'), 'left');
  $pdf->addTextWrap($Left_Margin+465,$YPos,100,$FontSize+1,_('Amount'), 'left');
  $pdf->addTextWrap($Left_Margin+510,$YPos,100,$FontSize+1,_('Remark'), 'left');
  
  $no=1;
  $page_total=0;
  $cbf=0;
  $total_amount=0;
  for($i=0;$i<$total_count;$i++){
      $pdf->addTextWrap($Left_Margin+15,$YPos-=$line_height+10,140,$FontSize,$no,'',0);
      $pdf->addTextWrap($Left_Margin+45,$YPos,140,$FontSize,$recptno[$i],'',0);  
      $pdf->addTextWrap($Left_Margin+87,$YPos,140,$FontSize,$date[$i],'',0);
      $pdf->addTextWrap($Left_Margin+140,$YPos,140,$FontSize,$customer[$i],'',0);
      $pdf->addTextWrap($Left_Margin+250,$YPos,140,$FontSize,$description[$i],'',0);
      $pdf->addTextWrap($Left_Margin+420,$YPos,140,$FontSize,$payment_mode[$i],'',0);
      $pdf->addTextWrap($Left_Margin+466,$YPos,140,$FontSize,$amount[$i],'',0);
      $page_total+=$amount[$i];
      //$cbf+=$page_total;
      $total_amount+=$amount[$i];
      if($mode_id[$i]==1){
          $amt_cash+=$amount[$i];
          
      }
      elseif($mode_id[$i]!=1){
          $amt_dd+=$amount[$i];
      }
      $no++;
      
      if ($YPos < $Bottom_Margin + $line_height+150){
          
          
          $pdf->addTextWrap($Left_Margin+250,$YPos-=$line_height+10,140,$FontSize+2,'TOTAL','',0);
          $pdf->addTextWrap($Left_Margin+462,$YPos,140,$FontSize+2,$page_total,'',0);
          if($PageNumber>1){
              $pdf->addTextWrap($Left_Margin+250,$YPos-=$line_height+10,140,$FontSize+2,'CBF','',0);
              $pdf->addTextWrap($Left_Margin+462,$YPos,140,$FontSize+2,$cbf,'',0);
              $pdf->addTextWrap($Left_Margin+250,$YPos-=$line_height+10,140,$FontSize+2,'Total Carry over','',0);
              $pdf->addTextWrap($Left_Margin+462,$YPos,140,$FontSize+2,$total_amount,'',0);
              
          }
         // echo'pt:'.$page_total;
//          echo'cbf:'.$cbf;
//           echo'tot:'.$total_amount;
          $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+10,140,$FontSize+2,'Prepared By','',0);
          $pdf->addTextWrap($Left_Margin+462,$YPos,140,$FontSize+2,'Approved By','',0);
          
          $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+2,140,$FontSize+1,$empname,'',0);
//          $pdf->addTextWrap($Left_Margin+462,$YPos,140,$FontSize+1,'MD','',0);
          
          
            $YPos=$Bottom_Margin - 5;
            $page='Page No '.$PageNumber;
            $pdf->addTextWrap($Left_Margin+230,$YPos,800,$FontSize,$page,'',0);
            $PageNumber++; 
            $pdf->newPage();
            $FontSize=9;
//            echo'pt:'.$page_total;
            $cbf+=$page_total;
            $page_total=0;
            $YPos= $Page_Height-$Top_Margin;   
            $line_height=15;        
            $Xpos = $Left_Margin+5;
            $YPos -=(2*$line_height);
//            $YPos+=30;
            $img1='companies/'.$_SESSION['DatabaseName'].'/logo.jpg';
  $pdf->addJpegFromFile($img1,$XPos+392,$YPos-=$line_height,0,55);
//    echo"1st".$YPos;
  $img2= 'companies/'.$_SESSION['DatabaseName'].'/logof.jpg';
  $pdf->addJpegFromFile($img2,$XPos+35,$YPos,0,55);  
  $pdf->line($Left_Margin,$YPos-2,$Left_Margin+534,$YPos-2);
  
  $cashier='Cashier : '.$empname;
  $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+12,100,$FontSize+2,$cashier, 'left');
  $pdf->addTextWrap($Left_Margin+220,$YPos,100,$FontSize+5,'Cash Book', 'center');
  $pdf->line($Left_Margin+235,$YPos-2,$Left_Margin+305,$YPos-2); 
  $pdf->addTextWrap($Page_Width-$Right_Margin-80,$YPos,90,$FontSize+2,Date($_SESSION['DefaultDateFormat']), 'right');
  
  $pdf->addTextWrap($Left_Margin+5,$YPos-=$line_height+20,100,$FontSize+1,_('Sl No.'), 'left');
  $pdf->addTextWrap($Left_Margin+40,$YPos,100,$FontSize+1,_('Rcpt No.'), 'left');
  $pdf->addTextWrap($Left_Margin+95,$YPos,100,$FontSize+1,_('Date'), 'left');
  $pdf->addTextWrap($Left_Margin+140,$YPos,100,$FontSize+1,_('Customer'), 'left');
  $pdf->addTextWrap($Left_Margin+260,$YPos,100,$FontSize+1,_('Description'), 'left');
  $pdf->addTextWrap($Left_Margin+390,$YPos,100,$FontSize+1,_('Payment Mode'), 'left');
  $pdf->addTextWrap($Left_Margin+465,$YPos,100,$FontSize+1,_('Amount'), 'left');
  $pdf->addTextWrap($Left_Margin+510,$YPos,100,$FontSize+1,_('Remark'), 'left');
  
                  
        }
  }
  $pdf->addTextWrap($Left_Margin+250,$YPos-=$line_height+10,140,$FontSize+2,'TOTAL','',0);
  $pdf->addTextWrap($Left_Margin+462,$YPos,140,$FontSize+2,$page_total,'',0);
  if($PageNumber>1){
      $pdf->addTextWrap($Left_Margin+250,$YPos-=$line_height+10,140,$FontSize+2,'CBF','',0);
      $pdf->addTextWrap($Left_Margin+462,$YPos,140,$FontSize+2,$cbf,'',0);
      $pdf->addTextWrap($Left_Margin+250,$YPos-=$line_height+10,140,$FontSize+2,'Total Carry over','',0);
      $pdf->addTextWrap($Left_Margin+462,$YPos,140,$FontSize+2,$total_amount,'',0);
  }
  $total_amt_in_cash='Total Amount in Cash        : '.$amt_cash;
  $total_amt_in_dd='Total Amount in DD/Cheque : '.$amt_dd;
  $total_amt=$amt_cash+$amt_dd;
  $grand_total='Grand Total                   : '.$total_amt;
  if($amt_cash>0){
  $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+10,200,$FontSize+2,$total_amt_in_cash,'',0);
  }
  if($amt_dd>0){
     $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+5,200,$FontSize+2,$total_amt_in_dd,'',0); 
  }
  $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+5,200,$FontSize+2,$grand_total,'',0);
  
  
  $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+10,140,$FontSize+2,'Prepared By','',0);
  $pdf->addTextWrap($Left_Margin+462,$YPos,140,$FontSize+2,'Approved By','',0);
          
  $pdf->addTextWrap($Left_Margin,$YPos-=$line_height+2,140,$FontSize+1,$empname,'',0);
//$pdf->addTextWrap($Left_Margin+462,$YPos,140,$FontSize+1,'MD','',0);
  
  
  $YPos=$Bottom_Margin-5;
  $page='Page No '.$PageNumber;
  $pdf->addTextWrap($Left_Margin+230,$YPos,800,$FontSize,$page,'',0);
  
  
  
  
  $pdf->OutputD($_SESSION['DatabaseName'] . '_Cash Book_' . Date('Y-m-d') . '.pdf');
  $pdf->__destruct();
    
    
    
    
    
    
    
    
    
    
    
    
?>
