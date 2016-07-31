<?php
$PageSecurity = 80;
include('includes/session.inc');
$title = _('Leads');  
include('includes/header.inc');
include('includes/sidemenu.php');
include('includes/bio_GetPrice.inc');
$office=$_SESSION['officeid'];   
 ?>
 <style>
/* image:hover{border:1px solid white;}*/
/* a.button {
    border : gray outset 2px ;
    text-decoration: none;
}*/
 </style>
 <script>
  function view(str1)
{    
  if (str1=="")
  {
  document.getElementById("caty").focus();
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
  { // alert (str1);
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
          
         // document.getElementById('showloc').style.display = "block";
     document.getElementById("caty2").innerHTML=xmlhttp.responseText; 
     
    }            
  } 
xmlhttp.open("GET","bio_selectplantforleadsan.php?subcatid="+str1,true);//alert(str1);
xmlhttp.send();        
}
function view2(str1)
{    
    
   /*  var str2=document.getElementById("item").value;
     alert(str2) ;
     if((str2)!=0 )
     {
        document.getElementById("item").innerHTML="";   
     }  */
//alert("hhh"+str1);
if (str1=="")
  {
  document.getElementById("caty").focus();
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
         // document.getElementById('showloc').style.display = "block";
  document.getElementById("item").innerHTML=xmlhttp.responseText;         //xmlhttp.responseText
     
    }            
  } 
xmlhttp.open("GET","bio_selectplantforleadsan.php?subsubcatid="+str1,true);//alert(str1);
xmlhttp.send();     
}
</script>
 <script type="text/javascript">
        function finishAjax(id, response){
  $('#loader').hide();
  $('#show_heading').show();
  $('#'+id).html(unescape(response));
  $('#'+id).fadeIn();
} 

  $(document).ready(function() {
  $('#district1').hide();
      $('#printgrid').hide();
  $("#error").fadeOut(3000);
    $("#warn").fadeOut(3500);
      $("#success").fadeOut(3000);
        $("#info").fadeOut(3000);
                $("#db_message").fadeOut(3000);  
         
 $('#sourcetype').change(function() {
  $('#dinhide').hide();
}); 
 $('#shwprint').click(function() {
  $('#printgrid').slideToggle('slow',function(){});
});

$("#selectiondetails").hide(); 
        
$('.button_details_show').click(function() {
  $('#selectiondetails').slideToggle('slow', function() {
    // Animation complete.
  });
});


 
   });
 
 function displayVals() {
//     alert("sss");
      var multipleValues = document.getElementById("remarklist").value;
//      alert(multipleValues);
        document.getElementById("remarks").value=multipleValues;

    }
   
    
  $("#remarklist").change(displayVals);
    displayVals();
    
 
    
   
//    $('#leads').click(function() { 

//    f=validateemail('email','Please Enter valid email');  if(f==1) { return false; }   
//});

function caps1()
{
//   alert(str);
         
UCWords('custid','Name should be begin with capital letter');

}

function capitaliseFirstLetter(string,str)
{   
                                 
   cap=string.charAt(0).toUpperCase() + string.slice(1);
 if(str==1) {      
   document.getElementById('careof').value=cap;
 }else if(str==2){
   document.getElementById('HouseName').value=cap;   
 }else if(str==3){
   document.getElementById('Area1').value=cap;   
 }else if(str==4){
   document.getElementById('Area2').value=cap;   
 }else if(str==5){
   document.getElementById('contactPerson').value=cap;   
 }else if(str==6){
   document.getElementById('Houseno').value=cap;   
 }
}


function validate() 
{
 
   var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
   var address = document.getElementById('email').value;
   if(reg.test(address) == false) {
     alert('Invalid Email Address');  
     document.getElementById('email').focus();  

                         
      return false;
   }
}

    

  function printshow()
{                               
 var str=document.getElementById('printnshow').value;   
 var str1=document.getElementById('datefrm').value;        
 var str2=document.getElementById('dateto').value;   
  var str3=document.getElementById('offic').value; 
  var str4=document.getElementById('place').value; 
var str5=document.getElementById('enquiry1').value; 
if (str=="")
  {
  document.getElementById("printandshow").innerHTML="";
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
  {           //alert(str);   
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {

    document.getElementById("printandshow").innerHTML=xmlhttp.responseText;
//      $('#dinhide').show(); 
    }
  } 
  //alert(str);
xmlhttp.open("GET","bio_PrintLeadSource.php?id=" + str +  "&from=" + str1 + "&to=" + str2 + "&offic=" + str3 + "&place=" + str4 + "&etype=" + str5,true);
xmlhttp.send(); 
                                        
}
function showCD9(){                           
 var str=document.getElementById('feedstockad').value;   
 var str1=document.getElementById('weightad').value;        
 var str2=document.getElementById('leadid').value;   
   if(str==""){alert("please select a feadstock");document.getElementById("feedstockad").focus(); return false;}
//alert(str);alert(str1);alert(str2);
if (str2=="")
  {
  document.getElementById("shadd").innerHTML="";
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
    {    function message(){alert("sss");}     

    document.getElementById("shadd").innerHTML=xmlhttp.responseText;
//      $('#dinhide').show(); 
    }
  } 
  //alert(str);
xmlhttp.open("GET","bio_add.php?feedstok=" + str +  "&weight=" + str1 + "&lead=" + str2,true);
xmlhttp.send(); 
 }
 
 
 function showstate(str){ 

if (str=="")
  {
  document.getElementById("showstate").innerHTML="";
  return;
  }
show_progressbar('showstate');

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
xmlhttp.open("GET","bio_showstate.php?country=" + str,true);
xmlhttp.send();
}

function alert_id()
{
    if($('#State').val() == '')
    alert('Please select a sub category.');
    else
    alert($('#State').val());
    return false;
}

 
function showdistrict(str){       //alert(str);
str1=document.getElementById("country").value;
//alert(str1);
if (str=="")
  {
  document.getElementById("showdistrict").innerHTML="";
  return;
  }
show_progressbar('showdistrict');
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
xmlhttp.open("GET","bio_showstate.php?state=" + str + "&country1=" + str1,true);
xmlhttp.send();

}
function addnew(str){ 
    if(str=="New"){
$('#showdistrict').hide();
$('#district1').show();
  document.getElementById('Districts').focus();


  }
}



/*$(document).ready(function() {
      $('#lsgdid').hide(); 
    $('#loader').hide();
    $('#show_heading').hide();
    
    $('#Nationality').change(function(){
        $('#show_sub_categories').fadeOut();
        $('#loader').show();
        $.post("get_chid_categories.php", {
            parent_id: $('#Nationality').val(),
        }, function(response){
            
            setTimeout("finishAjax('show_sub_categories', '"+escape(response)+"')", 400);
        });
        return false;
    });
});
      */

function getgrid(){
//    alert(str);
str=document.getElementById("enquiry").value;
    if (str=="")
  {
  document.getElementById("leaddetails").innerHTML="";
  return;
  }
  show_progressbar("leaddetails");
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
    document.getElementById("leaddetails").innerHTML=xmlhttp.responseText;
getservice(str);
    }
  }
xmlhttp.open("GET","bio_showgrid.php?enggrid=" + str,true);
xmlhttp.send();


}
 
  function duplicatename(){
              
 var custname=document.getElementById('custid').value;   
 
// alert(std);    
// alert(code);    alert(phone);
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
       var match=document.getElementById("duplicatename").innerHTML=xmlhttp.responseText;      //alert(match);
       if(match!=null)
        {          
          //  alert("Customer already exists"); 
        }
        
    }
  } 
xmlhttp.open("GET","bio_duplicateChecking.php?name=" + custname,true);  
xmlhttp.send();  
}     

function duplicate(str1,str2)
{
     window.open("bio_editleadsnew.php?q=" + str1 + "&en=" + str2);     
}      

function checkLandline(){   
 
 var enquiry=document.getElementById('enquiry').value; 
 var code=document.getElementById('code').value;
 var phone=document.getElementById('phone').value;
 var std=code+"-"+phone;  
// alert(std);    
// alert(code);    alert(phone);     alert(enquiry);
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
     
        var id=xmlhttp.responseText; 
  
        if(id!=0){
            alert("Customer already exists");
            myRef = window.open("bio_editleadsnew.php?q=" + id + "&en=" + enquiry);
          }          
                
    }
  } 
xmlhttp.open("GET","bio_mobilecalc.php?std=" + std + "&enquiry=" + enquiry,true);  
xmlhttp.send();  
}            

function ksebtest(){
        
    
    var ksebno=document.getElementById('kseb').value;
    var enquiry=document.getElementById('enquiry').value;   
    /*if(enquiry==0){
        alert("Select enquiry type"); 
        document.getElementById("enquiry").focus();  
        return false;
    }   */
 //alert(mobile); 
 //alert(enquiry); 
 if (ksebno=="")
  {
  document.getElementById("kseb").innerHTML="";
  document.getElementById("kseb").focus();
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
    var id=document.getElementById("eb").value=xmlhttp.responseText;
    if(id!=0){
        alert("Customer already exists");
        myRef = window.open("bio_editleadsnew.php?q=" + id + "&en=" + enquiry);
    } 
    }
  } 
xmlhttp.open("GET","bio_mobilecalc.php?ksebno=" + ksebno,true);  
xmlhttp.send();  
}
  

function checkMobile(){
        
    
    var mobile=document.getElementById('mobile').value;
    var enquiry=document.getElementById('enquiry').value;   
  //  alert(mobile);
 // alert(enquiry) ;
    /*if(enquiry==0){
        alert("Select enquiry type"); 
        document.getElementById("enquiry").focus();  
        return false;
    }   */
 //alert(mobile); 
 //alert(enquiry);     
           
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
    var id=xmlhttp.responseText;
    // alert(id);
    if(id!=0){
        alert("Customer already exists");
        myRef = window.open("bio_editleadsnew.php?q=" + id + "&en=" + enquiry);
    } 
    }
  } 
xmlhttp.open("GET","bio_mobilecalc.php?mobile=" + mobile + "&enquiry=" + enquiry,true);  
xmlhttp.send();  
}
  
 
 
 
function feedupdte1(str,str1)
{
//  alert("hii"); 

//alert(str); 
//alert(str1);

//var str1=document.getElementById("hfeedstock").value;
//var str2=document.getElementById("hfeedweight").value;
          
if (str1=="")
  {
  document.getElementById("edittedsho").innerHTML="";
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
    {     // alert("ddd");
    document.getElementById("edittedsho").innerHTML=xmlhttp.responseText;
//    document.getElementById('loc').focus();
    }
  }
xmlhttp.open("GET","bio_sourcetypedetails.php?ledid=" + str1 + "&fed=" + str,true);
xmlhttp.send();

}

 function showtaluk(str){   
     str1=document.getElementById("country").value;
     str2=document.getElementById('state').value;
     str3=document.getElementById('Districts').value;
//alert(str1);   alert(str2);       alert(str3);
if (str1=="")
  {
  document.getElementById("showtaluk").innerHTML="";
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
     document.getElementById("showtaluk").innerHTML=xmlhttp.responseText; 
       
    }
  } 
xmlhttp.open("GET","bio_CustlsgSelection.php?taluk=" + str +"&country1="+ str1 +"&state2="+ str2 +"&district="+ str3,true);
xmlhttp.send(); 
} 

function dofeeedit(str1,str2,str3)
{
var str1=document.getElementById("feedleadid").value;
var str2=document.getElementById("biofeedstockid").value;
var str3=document.getElementById("fedwt").value;
// alert(str1);       alert(str2);        alert(str3);
if (str1=="")
  {

  document.getElementById("editact").innerHTML="";
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
    {      //alert("ddd");
    document.getElementById("editact").innerHTML=xmlhttp.responseText;
//    document.getElementById('loc').focus();
    }
  }
xmlhttp.open("GET","bio_sourcetypedetails.php?ediled=" + str1 + "&fedidedi=" + str2 + "&fedwt=" + str3,true);
xmlhttp.send();

}

function replace_html(id, content) {
	document.getElementById(id).innerHTML = content;
}
var progress_bar = new Image();
progress_bar.src = '4.gif';
function show_progressbar(id) {
	replace_html(id, '<img src="4.gif" border="0" alt="Loading, please wait..." />Loading...');
}


function showCD(str)
{
  // alert("hiii");
       // alert(str); 
//$(document).ready(function(){  

if (str=="")
  {
  document.getElementById("showsource").innerHTML="";
  return;
  }
show_progressbar('showsource');
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
    document.getElementById("showsource").innerHTML=xmlhttp.responseText;
                             $("#source").focus();
   // document.getElementById("source").focus();
    }
  } 
  //alert(str);

xmlhttp.open("GET","bio_getsource.php?q="+str,true);
xmlhttp.send(); 
$("#hidetable").hide();  
}

function showCD1(str)
{
    var str1=document.getElementById("sourcetype").value; /*alert(str1);*/
   //  alert(str);
  // $("# sourcedetails").show(); 
// $("# hidetr").show();
$('#dinhide').show();

if (str=="")
  {
  document.getElementById("sourcedetails").innerHTML="";
  return;
  }
show_progressbar('sourcedetails');
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
        
    document.getElementById("sourcedetails").innerHTML=xmlhttp.responseText;
      //$('#dinhide').show(); 
    }
  } 
  //alert(str);
xmlhttp.open("GET","bio_sourcenew.php?q="+str+"&sd="+str1,true);
xmlhttp.send(); 
 
}


function getservice(str){
//    alert(str);
//str=document.getElementById("enquiry").value;
    if (str=="")
  {
  document.getElementById("showfeasibility").innerHTML="";
  return;
  }
  show_progressbar("showfeasibility");
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
    document.getElementById("showfeasibility").innerHTML=xmlhttp.responseText;
//if(str==2){getservice(str);}
    }
  }
xmlhttp.open("GET","bio_showgrid.php?service=" + str,true);
xmlhttp.send();


}

function output(str1){
//    alert(str);
str=document.getElementById("enquiry").value;
    if (str=="")
  {
  document.getElementById("showoutputtype").innerHTML="";
  return;
  }
  show_progressbar("showoutputtype");
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
    document.getElementById("showoutputtype").innerHTML=xmlhttp.responseText;
//if(str==2){getservice(str);}
    }
  }
xmlhttp.open("GET","bio_showgrid.php?output=" + str + "&plant=" + str1,true);
xmlhttp.send();


}

function showCD1_dealer(str)
{
   //  alert(str);
  // $("# sourcedetails").show(); 
// $("# hidetr").show();
$('#dinhide').show();
if (str=="")
  {
  document.getElementById("sourcedetails").innerHTML="";
  return;
  }
show_progressbar('sourcedetails');
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
        
    document.getElementById("sourcedetails").innerHTML=xmlhttp.responseText;
      //$('#dinhide').show(); 
    }
  } 
  //alert(str);
xmlhttp.open("GET","bio_sourcetypedetails_dealer.php?q="+str,true);
xmlhttp.send(); 
 
}


function showinstitute(str)
{    
// alert('str');
str1=document.getElementById("enquiry").value;
if(str1==3){ $('#lsgdid').show();   }
else{ $('#lsgdid').hide();   } 

if(str1==1){
  $('#plantselection').show();  
}else{
  $('#plantselection').hide();  
}
 
if (str=="")
  {
  document.getElementById("instdom").innerHTML="";
  return;
  }
  show_progressbar("instdom");
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
    document.getElementById("instdom").innerHTML=xmlhttp.responseText;
                        document.getElementById('custid').focus();    

    
   productservices(str);
   //remarkss(str);
    // gradeview(str);
   // showurgency(str);
  
    }
  }
xmlhttp.open("GET","bio_showdom.php?dom=" + str + "&enq=" + str1,true);

xmlhttp.send();
//
}

function productservices(str)
{
//    alert(str);

    if (str=="")
  {
  document.getElementById("showfeasibility").innerHTML="";
  return;
  }
  show_progressbar("showfeasibility");
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
    document.getElementById("showfeasibility").innerHTML=xmlhttp.responseText;
//getservice(str);
  remarkss(str);
    
    }
  }
xmlhttp.open("GET","bio_productservices_ajax.php?enqid=" + str,true);
xmlhttp.send();


}
function remarkss(str)
{
//    alert(str);

    if (str=="")
  {
  document.getElementById("showremarks").innerHTML="";
  return;
  }
  show_progressbar("showremarks");
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
    document.getElementById("showremarks").innerHTML=xmlhttp.responseText;
//getservice(str);
  gradeview(str);
    
    }
  }
xmlhttp.open("GET","bio_leadremark_ajax.php?enqid=" + str,true);
xmlhttp.send();


}
function gradeview(str)
{
//    alert(str);

    if (str=="")
  {
  document.getElementById("gradeview").innerHTML="";
  return;
  }
  show_progressbar("gradeview");
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
    document.getElementById("gradeview").innerHTML=xmlhttp.responseText;
//getservice(str);
showurgency(str);
    }
  }
xmlhttp.open("GET","bio_gradeview_ajax.php?enqid=" + str,true);
xmlhttp.send();


}

function showurgency(str){
//    alert(str);

    if (str=="")
  {
  document.getElementById("schedule").innerHTML="";
  return;
  }
  show_progressbar("schedule");
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
    document.getElementById("schedule").innerHTML=xmlhttp.responseText;
//getservice(str);
    }
  }
xmlhttp.open("GET","bio_showurgency.php?enqid=" + str,true);
xmlhttp.send();


}


function showcatitems(str1){
if (str1=="")
  {
  document.getElementById("items").innerHTML="";     //editleads
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
    document.getElementById("items").innerHTML=xmlhttp.responseText;
//if(str==2){getservice(str);}
    }
  }
xmlhttp.open("GET","bio_selectplantforlead.php?catid="+str1,true);
xmlhttp.send();


}




 function showvillage(str)
{   
  // alert(str);
     str1=document.getElementById("country").value;
     str2=document.getElementById('state').value;
     str3=document.getElementById('Districts').value;
//alert(str1);   alert(str2);       alert(str3);
if (str=="")
  {
  document.getElementById("showvillage").innerHTML="";
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
     document.getElementById("showvillage").innerHTML=xmlhttp.responseText; 
       
    }
  } 
xmlhttp.open("GET","bio_CustlsgSelection.php?village=" + str +"&country1="+ str1 +"&state2="+ str2 +"&district="+ str3,true);
xmlhttp.send(); 
} 


function showCD2(str1,str2)
{
   //alert("hii");
//   alert(str2);   alert(str1);
if (str1=="")
  {
  document.getElementById("editleads").innerHTML="";
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
    document.getElementById("editleads").innerHTML=xmlhttp.responseText;
//    document.getElementById('inputField').focus(); 

    }
  } 
xmlhttp.open("GET","bio_editleads.php?q=" + str1 + "&en=" + str2,true);
xmlhttp.send();    

}



function log_in()
{  //  alert("sss"); alert(mail);
var f=0;                                                  //State
var hlol=document.getElementById('hlol').value;
// alert(hlol);
if(f==0){f=common_error('enquiry','Please Select an Enquiry Type');  if(f==1) { return f; }}

var enquiry=document.getElementById('enquiry').value;
if(enquiry!=1){
    if(enquiry!=2){
        if(enquiry!=5){
        f=common_error('project','Please Enter Project Name');  if(f==1) { return f;}
        }else if(enquiry==5){
        f=common_error('project','Please select Project Name');  if(f==1) { return f;}
        }
    }
} 
f=common_error('custid','Please Enter Customer Name');  if(f==1) { return f;} 

//alert(enquiry);
/*if(enquiry!=1){
    if(enquiry!=5){
       if(enquiry!=6){ 
   f=common_error('contactPerson','Please Enter Contact Person');  if(f==1) { return f;}
       } 
    }
}*/
  

if(f==0){f=common_error('country','Please Select a Country');  if(f==1) { return f; }}
if(document.getElementById('country').value==1)
{                     
if(f==0){f=common_error('state','Please Select a State');  if(f==1) { return f; }}
if(document.getElementById('state').value==14){
if(f==0){f=common_error('Districts','Please Select a District');  if(f==1) { return f; }}
}
}
if(f==0)
{
    var y=document.getElementById('phone').value; 
    var x=document.getElementById('mobile').value;
    if(x=="" && y==""){ alert("Please enter atleast one contact number");f=1;} 
    if(f==1) { document.getElementById('phone').focus();return f; } }
    
if(f==0)
{
     
    var x=document.getElementById('phone').value;
    var y=document.getElementById('mobile').value;
    if(x!=""){
       var l=x.length;
    
            if(isNaN(x)||x.indexOf(" ")!=-1)
           {  f=1;
              alert("Enter a numeric value"); document.getElementById('phone').focus();
              if(x=""){f=0;}
              return f; 
           }
           if(l>8 || l<6)
           {
               
             f=1;  
              alert("Please enter valid phone number"); document.getElementById('phone').value=""; 
              document.getElementById('phone').focus();
              return f;
           } 
    }
    if(y!=""){
         var l=y.length;
    
            if(isNaN(y)||y.indexOf(" ")!=-1)
           {  f=1;
              alert("Enter a numeric value"); document.getElementById('mobile').focus();
              if(y=""){f=0;}
              return f; 
           }
           if(l>11 || l<10)
           {
               
             f=1;  
              alert("Please enter valid mobile number"); document.getElementById('mobile').value=""; 
              document.getElementById('mobile').focus();
              return f;
           } 
    }
    
}
//if(f==0){f=common_error('kseb','Please Enter ');  if(f==1) { return f; }}


//var type1=document.getElementById('outputtype1').value;
//alert(type1);


var typecnt=document.getElementById('houttype').value;
//alert(typecnt);
if(f==0){
    
var chks = document.getElementsByName('outputtype[]');
var hasChecked = false;

for (var i = 0; i < chks.length; i++)
{
if (chks[i].checked)
{
hasChecked = true;
break;
}
}
if (hasChecked == false)
{
f=1;
alert("Please select at least one Output type.");
return f; 
}

    
}                  
if(f==0){f=common_error('sourcetype','Please Select a LeadSource Type');  if(f==1) { return f; }}
if(f==0){f=common_error('source','Please Select a LeadSource');  if(f==1) { return f; }} 
 
if(f==0){f=common_error('productservices','Please Select a Product services');  if(f==1) { return f; }}
if(f==0){f=common_error('replace','Please Select a Replacement Fuel');  if(f==1) { return f; }}

if(enquiry!=6){  
if(f==0){f=common_error('urgencylevel','Please Select urgency level');  if(f==1) { return f; }}
}
//if(f==0){f=common_error('outputtype','Please Select an Output Type');  if(f==1) { return f; }}                 

//if(f==0){f=schemecheck();  if(f==1) { return f; }}  
//if(f==0){   alert("ss");
//    for(i=1;i>hlol;i++){var Scheme=schm+i;
// if(document.getElementById('Scheme').checked==false)      
//{
//   f=1;   
//  }  
//    }
//    
//    f=common_error('Scheme','Please Select a Scheme'); 
//     if(f==1) { return f; }}





     
//if(f==0){f=common_error('feedstock','Please Select a Fead Stock');  if(f==1) { return f; }}
// if(f==0){f=common_error('feedstockad','Please Select a Fead Stock');  if(f==1) { return f; }}     
}



function showFeeds(str1,str2){
//alert(str1);
if (str1=="")
  {
  document.getElementById("feeds").innerHTML="";
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
    document.getElementById("feeds").innerHTML=xmlhttp.responseText;  
    document.getElementById('loc').focus(); 
    }
  } 
xmlhttp.open("GET","bio_showfeeds.php?p=" + str1,true);
xmlhttp.send(); 
}

function updatetotalitemprice(k,user,stock){ //var a="#"+str;
//$(a).hide();
// alert(str);
//$("#grid").hide();
if (stock=="")
  {
  document.getElementById("tprice").value="";
  return;
  }
   var s=stock;
 var q=document.getElementById('qty'+k).value;
 var p=document.getElementById('price'+k).value;
 var t=document.getElementById('tprice'+k).value=q*p;
 var sub1=document.getElementById('subsidy'+k).value;
 var sub=q*sub1;
 var n=document.getElementById('netprice'+k).value=t-sub;

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
    document.getElementById("tprice").value=xmlhttp.responseText;
    }
  }
xmlhttp.open("GET","bio_updateproptemppricelead.php?stock="+s+"&qty="+q+"&price="+p+"&tprice="+t+"&user="+user+"&subsidy="+sub+"&nprice="+n);
xmlhttp.send();
}


function showCD4()
{
    var str1=document.getElementById("feedstock").value;
var str2=document.getElementById("weight").value;
//   alert("hii");
//   alert(str1);
if(str1==""){
alert("select a Feedstock"); document.getElementById("feedstock").focus();  return false;  }
if (str1=="")
  {
  document.getElementById("feedstockdiv").innerHTML="";     //editleads
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
    document.getElementById("feedstockdiv").innerHTML=xmlhttp.responseText;
    document.getElementById('feedstock').value="";       document.getElementById('weight').value="";
    }
  } 
xmlhttp.open("GET","bio_sourcenewfeed.php?feedstock=" + str1  + "&weight=" + str2 ,true);
xmlhttp.send();    

}


 function showblock(str){   
     str1=document.getElementById("country").value;
     str2=document.getElementById('state').value;
     str3=document.getElementById('Districts').value;
     if (str3=="")
     {
     alert("Please select a district");    
     document.getElementById("Districts").focus();
     document.getElementById("lsgType").value=0;
     return;
     }
     

     if(str==1 && (str3==1 || str3==3 || str3==4 || str3==5 || str3==7 || str3==9 || str3==10 || str3==10 || str3==14)){
         
         alert("No Corporation for this district");
         document.getElementById("block").innerHTML="";
         return;
     }
         // document.getElementById("Districts").focus();
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
        
        if(str==1)
        {
             document.getElementById("lsgWard").focus();
        }   
         if(str==2||str==3)
        {
             document.getElementById("lsgName").focus();
        }
       
    }
  } 
xmlhttp.open("GET","bio_CustlsgSelection.php?block=" + str +"&country1="+ str1 +"&state1="+ str2 +"&district="+ str3,true);
xmlhttp.send(); 
} 


function editfeedstok(str)
{
   //alert("hii");
//var str1=document.getElementById("hfeedstock").value;
//var str2=document.getElementById("hfeedweight").value;

if (str=="")
  {
  document.getElementById("editfeed").innerHTML="";
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
    document.getElementById("editfeed").innerHTML=xmlhttp.responseText;
//    document.getElementById('loc').focus(); 
    }
  } 

xmlhttp.open("GET","bio_sourcetypedetails.php?upfeedstockid=" + str,true);
xmlhttp.send();    

}

function showItems()
{
var str1=document.getElementById("caty").value;
var str2=document.getElementById("item").value;
//alert(str1);
//alert(str2);
if(str1==0){
alert("select a Catagory"); document.getElementById("caty").focus();  return false;  }
if(str2==0){
alert("select an Item"); document.getElementById("item").focus();  return false;  }
if (str1==0)
  {
  document.getElementById("selecteditems").innerHTML="";     //editleads
  return;
  }
 if (str2==0)
  {
  document.getElementById("selecteditems").innerHTML="";     //editleads
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
    document.getElementById("selecteditems").innerHTML=xmlhttp.responseText;
    document.getElementById('caty').value="";       document.getElementById('item').value="";
    }
  } 
xmlhttp.open("GET","bio_additemsforleads.php?cat=" + str1  + "&item=" + str2 ,true);
xmlhttp.send();    

}


function doedit()
{
//   alert("hii");
//   alert(str);

var str=document.getElementById("fdstk").value;    
var str1=document.getElementById("h1feedstock").value;
var str2=document.getElementById("h1feedweight").value;
// alert(str); alert(str1);     alert(str2); 
if (str=="")
  {
  document.getElementById("feedstockdiv").innerHTML="";
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
    document.getElementById("feedstockdiv").innerHTML=xmlhttp.responseText;
    $('#h1feedweight').focus(); 
    }
  } 
xmlhttp.open("GET","bio_sourcetypedetails.php?edid=" + str + "&edfd=" + str1 + "&edwt=" + str2,true);
xmlhttp.send();    

}    




function addSubsidy(str1,str2){
    /*alert(str1);
    alert(str2);*/
if (str1=="")
  {
  document.getElementById("viewsubsidy").innerHTML="";     //editleads
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
    document.getElementById("viewsubsidy").innerHTML=xmlhttp.responseText;
//if(str==2){getservice(str);}
    }
  }
xmlhttp.open("GET","bio_addsubsidyforlead.php?user="+str1+"&item="+str2,true);
xmlhttp.send();


}


function deletfeedstok(str)
{
//   alert("hii");
//   alert(str);


// alert(str); alert(str1);     alert(str2); 
if (str=="")
  {
  document.getElementById("feedstockdiv").innerHTML="";
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
    document.getElementById("feedstockdiv").innerHTML=xmlhttp.responseText;

    }
  } 
xmlhttp.open("GET","bio_sourcetypedetails.php?delet=" + str,true);
xmlhttp.send();    

}

function san()
{
    
    myRef = window.open("bio_print_A5p.php");
    
}

function feasibilityP()
{
    myRef = window.open("bio_feasibility_print.php");
}

function prop(){window.location = "bio_proposal.php";}
//function amntmode(str){
//if (str=="")
//  {
//  document.getElementById("modeamt").innerHTML="";
//  return;
//  }
//if (window.XMLHttpRequest)
//  {// code for IE7+, Firefox, Chrome, Opera, Safari
//  xmlhttp=new XMLHttpRequest();
//  }
//else
//  {// code for IE6, IE5
//  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
//  }
//xmlhttp.onreadystatechange=function()
//  {
//  if (xmlhttp.readyState==4 && xmlhttp.status==200)
//    {
//    document.getElementById("modeamt").innerHTML=xmlhttp.responseText;

//    }
//  } 
//xmlhttp.open("GET","bio_amountdetails.php?mod=" + str,true);
//xmlhttp.send();    
//}
function advdetail(str){
if (str=="")
  {
  document.getElementById("amt").innerHTML="";
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
    document.getElementById("amt").innerHTML=xmlhttp.responseText;
      document.getElementById("amtdate").focus();
    }
  } 
xmlhttp.open("GET","bio_amountdetails.php?adv=" + str,true);
xmlhttp.send();    
}
</script>
<?php  
if(isset($_POST['clear'])){   
unset($_POST['feedstock']); 
unset($_POST['enquiry']);
unset($_POST['identity']); 
unset($_POST['outputtype']); 
unset($_POST['sourcetype']); 
unset($_POST['printsource']); 
unset($_POST['feedstock']); 
//unset($_POST['enquiry']); 


}


if(!isset($_POST['submit'])){  
$tempdrop="DROP TABLE IF EXISTS bio_feedtemp";
DB_query($tempdrop,$db);   
$temptable="CREATE TABLE bio_feedtemp (
                         temp_id INT NOT NULL AUTO_INCREMENT ,
                         feedstockid INT NULL ,
                         weight DECIMAL NULL ,
           PRIMARY KEY ( temp_id )
                        )";

DB_query($temptable,$db);  
$sql="ALTER TABLE `bio_feedtemp` ADD `status` INT NOT NULL DEFAULT '0'" ; DB_query($sql,$db);
 }
 
$did=DB_Last_Insert_ID($Conn,'bio_district','did');
$did=$did+1;

 if(($_POST['Districts'])!=""){  $dt=$_POST['Districts'];
 $st=$_POST['State'];
 
 $sql="INSERT INTO bio_district(
                   bio_district.stateid,
                   bio_district.did,
                   bio_district.district) 
            VALUES($st,$did,'$dt')";
 $result=DB_query($sql,$db);
 
 $_POST['District']=DB_Last_Insert_ID($Conn,'bio_district','did');

 }
 
 if(isset($_POST['submit']))
 {
 
 //exit;      
 $scheme=$_POST['schm'];
 if($scheme!=""){
 foreach($scheme as $id){
 $sourcescheme.=$id.",";
 } 
 $schemeid=substr($sourcescheme,0,-1);   
 }
 else{
      $schemeid="";
      }
      $outputtype=$_POST['outputtype'];
 foreach($outputtype as $id1){
      $sourcetype1.=$id1.",";
      } 
      $outputtypeid=substr($sourcetype1,0,-1);             
        unset($delleadid); 
      $date=date("Y-m-d");
      $custname=$_POST['custname'];
      $address=$_POST['address'];
        if($_POST['code']!=""){
      $phone=$_POST['code']."-".$_POST['phone'];   
      }
      $mobile=$_POST['mobile'];  
      $email=$_POST['email'];
      $teamid=$_SESSION['teamid'];
      $productservicesid=$_POST['productservices'];
      $enquiryid=$_POST['enquiry'];
      $investmentsize=$_POST['investmentsize']; $schemeid;
//      $schemeid=$_POST['scheme'];
      $idtype=$_POST['identity'];
    //  $ksebno=$_POST['kseb'];
      $idno=$_POST['identityno'];
      $feedstockid=$_POST['feedstock'];
//      $rmkg=$_POST['rmkg'];  
//      $outputtypeid=$_POST['outputtype'];
      $sourcetype=$_POST['sourcetype'];
      $sourceid=$_SESSION['sourceid'];
      $parent_leadid=$_POST['Parent'];
       $grad=$_POST['grade'];
//      echo $POST_['Houseno'];
//      exit;  
 $urgency_level=$_POST['UrgencyLevel'];  
      if($_POST['advanceamt']=='')
      {
          $advanceamt=0; 
      }
      else
      {
          $advanceamt=$_POST['advanceamt'];
      }
      if($_POST['Houseno']==""){$_POST['Houseno']=" ";}    
      if($_POST['HouseName']==""){$_POST['HouseName']=" ";}
      if($_POST['Area1']==""){$_POST['Area1']=" ";} 
      if($_POST['Area2']==""){$_POST['Area2']=" ";} 
      if($_POST['Pin']==""){$_POST['Pin']=" ";}
      if($_POST['country']==""){$_POST['country']=" ";}    
      if($_POST['State']==""){$_POST['State']=" ";}
      if($_POST['District']==""){$_POST['District']=" ";} 
       
      if($_POST['contactPerson']==""){$_POST['contactPerson']=$_POST['custname'];}  
            if($phone==""){$_POST['phone']=" ";}      
                  if($mobile==""){$_POST['mobile']=" ";}      
                        if($email==""){$_POST['email']=" ";}                     
      $status=$_POST['status'] ;
      $remarks=$_POST['remarks'];
       //echo "sssssssssss".$_POST['Area2']."nnnnnnnnnnnnnnnn";
       
 $InputError=0;
 $count=0;
 if($enquiryid==1){
     
     if($custname!="" AND $idtype!="" AND $idno!=""){
         $sql_check="SELECT * FROM bio_cust,bio_leads
                             WHERE bio_cust.custname='".$custname."'
                               AND bio_leads.id_type=".$idtype."
                               AND bio_leads.id_no='".$idno."'
                               AND bio_leads.cust_id=bio_cust.cust_id";
                     
  $result_check=DB_query($sql_check,$db);
  $myrow_check=DB_fetch_array($result_check);
  $count=DB_num_rows($result_check);
  $_SESSION['lead']=$myrow_check['leadid'];
  $_SESSION['enquiry']=$myrow_check['enqtypeid'];
  }
  } 
  
  
  if($count>0){
     $InputError=1; 
     print'<script>
      alert("The customer is already exists");
      myRef = window.open("bio_editleadsnew.php");
   </script> ';
     }         
  
  else{          
       
     $sqlcust="INSERT INTO `bio_cust` (
                           `custname`, 
                           `custphone`, 
                           `custmob`, 
                           `custmail`, 
                           `houseno`,
                           `housename`,
                           `area1`,
                           `area2`,
                           `pin`,                                                                                                                                                                                                                             
                           `nationality`,
                           `state`,
                           `district`,
                           `contactperson`,
                           careof,
                           taluk,
                           LSG_type,
                           LSG_name,
                           block_name,
                           LSG_ward,
                           village) 
                   VALUES ('$custname',
                           '$phone',
                           '$mobile',
                           '$email',
                           '".$_POST['Houseno']."',
                           '".$_POST['HouseName']."',
                           '".$_POST['Area1']."',
                           '".$_POST['Area2']."',
                           '".$_POST['Pin']."',
                           '".$_POST['country']."',
                           '".$_POST['State']."',
                           '".$_POST['District']."',
                           '".$_POST['contactPerson']."',
                           '".$_POST['careof']."',
                           '".$_POST['taluk']."',
                           '".$_POST['lsgType']."',
                           '".$_POST['lsgName']."',
                           '".$_POST['gramaPanchayath']."',
                           '".$_POST['lsgWard']."',
                           '".$_POST['village']."')";
  //$result=DB_query($sql, $db);  
//  exit;     
           $ErrMsg =  _('An error occurred inserting the new Sales Leads record because');
           $Dbgmsg =  _('The SQL used to insert the Sales Leads record was');
           $result = DB_query($sqlcust,$db,$ErrMsg,$DbgMsg);
//  prnMsg( _('The Sales Leads record has been added'),'success');                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         
     //exit;
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------     
 $custid=DB_Last_Insert_ID($Conn,'bio_feedtemp','temp_id');
 $newcust=$custid;
//---------------------------------------------------------------------------------------------------------------------------------------------------------------------
$sql="INSERT INTO `bio_leads` (
                  `leaddate`, 
                  `teamid`,
                  `sourceid`, 
                  `productservicesid`, 
                  `enqtypeid`, 
                  `investmentsize`, 
                  `schemeid`, 
                  `outputtypeid`, 
                  `advanceamount`, 
                  `remarks`, 
                  `status`,
                  `cust_id`,
                  `created_by`,
                  ID_type,ID_no,
                  parent_leadid,grade) 
          VALUES ('$date',
                  '$teamid',
                   '".$sourceid."',
                  '".$productservicesid."',
                  ".$enquiryid.",
                  '$investmentsize',
                  '$schemeid',
                  '".$outputtypeid."',
                  ".$advanceamt.",
                  '$remarks',
                  '$status',
                   $custid,
                  '$_SESSION[UserID]',
                  '$idtype',
                  '$idno',
                  '$parent_leadid','$grad')";        // exit;
                  
  //$result=DB_query($sql, $db);  
//  exit;
//echo$teamid;
//echo$sourceid;//


  $ErrMsg =  _('An error occurred inserting the new Sales Leads record because');
  $Dbgmsg =  _('The SQL used to insert the Sales Leads record was');
  $result = DB_query($sql,$db,$ErrMsg,$DbgMsg);
  prnMsg( _('The Sales Leads record has been added'),'success');
  
  $lead=DB_Last_Insert_ID($Conn,'bio_leads','leadid');
  $custid=DB_Last_Insert_ID($Conn,'bio_feedtemp','temp_id');
  $leadprint=$custid;

if($enquiryid==3 or $enquiryid==4 or $enquiryid==6){ 
  $sqlpro="INSERT INTO `bio_lsgdproject`(
                       `leadid`,
                       `project_name`) 
                VALUES('$lead',
                       '".$_POST['Project']."')";
                     
     $result = DB_query($sqlpro,$db,$ErrMsg,$DbgMsg);                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          
     //exit;                                                                                                  

}

$sql="INSERT INTO bio_leadfeedstocks(SELECT $custid,bio_feedtemp.feedstockid,bio_feedtemp.weight,bio_feedtemp.status FROM bio_feedtemp)";
$result1=DB_query($sql, $db);   
$tempdrop="DROP TABLE IF EXISTS bio_feedtemp";
DB_query($tempdrop,$db); 


$temptable="CREATE TABLE bio_feedtemp (
temp_id INT NOT NULL AUTO_INCREMENT ,
feedstockid INT NULL ,
weight DECIMAL NULL ,
PRIMARY KEY ( temp_id )
)";

 DB_query($temptable,$db);  
 $sql="ALTER TABLE `bio_feedtemp` ADD `status` INT NOT NULL DEFAULT '0'" ; 
 DB_query($sql,$db); 
 $custid;
 
 $mode=$_POST['mode'];
 $amountno=$_POST['amtno'];$amountbank=$_POST['amtbank'];
 $advanceamt; 
 $offic=$_SESSION['UserStockLocation'];
 $amtdate=FormatDateForSQL($_POST['amtdate']);
 
if($amtdate=='--'){$amtdate="0000-00-00";}
if($advanceamt>0){
     $sql="INSERT INTO bio_advance (leadid, 
                                    head_id,
                                    mode_id, 
                                    date, 
                                    serialnum,
                                    bankname,
                                    paydate,
                                    amount,
                                    officid,
                                    status,
                                    collected_by) 
                           VALUES ('$lead',
                                   0,
                                   '".$mode."',
                                   '".$amtdate."',
                                   '".$amountno."',
                                   '".$amountbank."',
                                   '".FormatDateForSQL(Date($_SESSION[DefaultDateFormat]))."',
                                   '$advanceamt',
                                   '$offic',
                                   0,
                                   '".$_SESSION['UserID']."'
                                   )"; 
                              
    $result1=DB_query($sql, $db); 
    $adv_id=DB_Last_Insert_ID($Conn,'bio_advance','adv_id');
} 
  
         
$emp_ID=$_SESSION['empid'];
    
    $sql_team="SELECT teamid FROM bio_teammembers WHERE empid=".$emp_ID;
    $result_team=DB_query($sql_team,$db);
    $row_team=DB_fetch_array($result_team);
    $assignedfrm=$row_team['teamid'];
    
    $sql_desg="SELECT designationid FROM bio_emp WHERE empid=".$emp_ID;
    $result_desg=DB_query($sql_desg,$db);
    $row_desg=DB_fetch_array($result_desg);
    $designation=$row_desg['designationid'];
    
    
    

    
     

//----------------------------------------Assigning Domestic customers to CCE/BH-------------------------------------------// 

if($enquiryid==1 or $enquiryid==5)
{
    $sql_cce="SELECT www_users.empid,
                     bio_teammembers.teamid 
                FROM www_users,bio_teammembers 
               WHERE bio_teammembers.empid=www_users.empid";
               
   if($_POST['country']==1 && $_POST['State']==14)        //KERALA
   {                  
       if( $_POST['District']==6 || $_POST['District']==11 || $_POST['District']==12 )    //KLM-PTA-TVM
       {
           if($_SESSION['UserID']=='ccetvm2')
           {
              $sql_cce.=" AND www_users.userid='".$_SESSION['UserID']."'"; 
           }
           
           else if($_SESSION['UserID']=='ccetvm3')
           {
                  $sql_cce.=" AND www_users.userid='".$_SESSION['UserID']."'";  
           }
           else{
              $sql_cce.=" AND www_users.userid='".ccetvm1."'";
           }
       }
       elseif( $_POST['District']==1 || $_POST['District']==2 || $_POST['District']==3 || $_POST['District']==7 || $_POST['District']==13 ) //ALP-EKM-IDK-KTM-TRS
       {
             if($_SESSION['UserID']=='cceeklm1')
           {
              $sql_cce.=" AND www_users.userid='".cceeklm1."'"; 
           }
           else if ($_SESSION['UserID']=='cceeklm2') 
           {
           
           $sql_cce.=" AND www_users.userid='".cceeklm2."'";    
           }
           else
           {
                $sql_cce.=" AND www_users.userid='".cceeklm2."'";    
           }                
       }
       elseif( $_POST['District']==4 || $_POST['District']==5 || $_POST['District']==8 || $_POST['District']==9 || $_POST['District']==10 || $_POST['District']==14 ) //KNR-KSR-KZH-MLP-PLK-WND
       {
           $sql_cce.=" AND www_users.userid='".ccekoz1."'";
       }
   } 
   elseif($_POST['country']==1 && $_POST['State']!=14)     //OUTSIDE KERALA
   {
       $sql_cce.=" AND www_users.userid='".bde_national."'";
   }elseif($_POST['country']!=1){                           //OUTSIDE INDIA
       $sql_cce.=" AND www_users.userid='".bde_international."'";
       
   }
   
   $result_cce=DB_query($sql_cce,$db);
   $row_cce=DB_fetch_array($result_cce);
   $teamid=$row_cce['teamid'];   
   
   $assigned_date=date("Y-m-d");
    
   if($_POST['country']==1 && $_POST['State']!=14)
   {
       if(isset($_POST['PlantSelectFlag'])){
           $sql_schedule="INSERT INTO bio_leadschedule VALUES($lead,30)"; 
           $result_schedule=DB_query($sql_schedule,$db);
           
           $sql_schedule1="SELECT task_master_id,
                                  actual_task_day 
                             FROM bio_schedule 
                            WHERE schedule_master_id=(SELECT scheduleid FROM bio_leadschedule WHERE leadid=$lead) 
                         ORDER BY schedule_id ASC";  
    $result_schedule1=DB_query($sql_schedule1,$db);
    
     while($row_schedule1=DB_fetch_array($result_schedule1))
        {       
                $taskid=$row_schedule1['task_master_id'];
                $date_interval+=$row_schedule1['actual_task_day'];
                
                if($taskid==18 OR $taskid==20 OR $taskid==1 OR $taskid==21 OR $taskid==5){
                    $date_interval=0;
                    $taskcompleted_status=1;
                    $taskcompleted_date=date("Y-m-d");
                    
                    $sql_leadTask="INSERT INTO bio_leadtask (taskid,
                                                             leadid,
                                                             teamid,
                                                             assigneddate,
                                                             taskcompleteddate,
                                                             taskcompletedstatus,
                                                             assigned_from,
                                                             viewstatus)
                                                      VALUES('".$taskid."',
                                                             '".$lead."',
                                                             '".$teamid."',
                                                             '".$assigned_date."',
                                                             '".$taskcompleted_date."',
                                                             '".$taskcompleted_status."',
                                                             '".$assignedfrm."',
                                                             1)";
                    $result_leadTask=DB_query($sql_leadTask,$db);
                    }
                
                //$duedate=date("Y-m-d",mktime(0,0,0,date("m"),date("d")+$date_interval,date("y")));  
                else{
                    
                    $sql_leadTask="INSERT INTO bio_leadtask (taskid,
                                                             leadid,
                                                             teamid,
                                                             assigneddate,
                                                             duedate,
                                                             assigned_from,
                                                             viewstatus)
                                                      VALUES('".$taskid."',
                                                             '".$lead."',
                                                             '".$teamid."',
                                                             '".$assigned_date."',
                                                             '".date("Y-m-d",mktime(0,0,0,date("m"),date("d")+$date_interval,date("y")))."',
                                                             '".$assignedfrm."',
                                                             1)";
                 $result_leadTask=DB_query($sql_leadTask,$db); 
                
                $assigned_date=date("Y-m-d",mktime(0,0,0,date("m"),date("d")+$date_interval+1,date("y"))); 
                $date_interval+=1;
                }
        }           
       }
       
       else{
           
           $sql_leadTask="INSERT INTO bio_leadtask (taskid,
                                                    leadid,
                                                    teamid,
                                                    assigneddate,
                                                    assigned_from,
                                                    viewstatus)
                                             VALUES(0,
                                                    '".$lead."',
                                                    '".$teamid."',
                                                    '".$assigned_date."',
                                                    '".$assignedfrm."',
                                                    1)";
           $result_leadTask=DB_query($sql_leadTask,$db);
       }
       
       
       }  
       else  
       {
       
       if(isset($_POST['PlantSelectFlag'])){
           
           $sql_schedule="INSERT INTO bio_leadschedule VALUES($lead,30)"; 
           $result_schedule=DB_query($sql_schedule,$db);
           
           $sql_schedule1="SELECT task_master_id,
                                  actual_task_day 
                             FROM bio_schedule 
                            WHERE schedule_master_id=(SELECT scheduleid FROM bio_leadschedule WHERE leadid=$lead) 
                         ORDER BY schedule_id ASC";  
    $result_schedule1=DB_query($sql_schedule1,$db);
     
     while($row_schedule1=DB_fetch_array($result_schedule1))
        {       
                $taskid=$row_schedule1['task_master_id'];
                $date_interval+=$row_schedule1['actual_task_day'];
                if($taskid==18 OR $taskid==20 OR $taskid==1 OR $taskid==21 OR $taskid==5){
                    $date_interval=0;
                    $taskcompleted_status=1;
                    $taskcompleted_date=date("Y-m-d");
                    
                    $sql_leadTask="INSERT INTO bio_leadtask (taskid,
                                                             leadid,
                                                             teamid,
                                                             assigneddate,
                                                             taskcompleteddate,
                                                             taskcompletedstatus,
                                                             assigned_from,
                                                             viewstatus)
                                                      VALUES('".$taskid."',
                                                             '".$lead."',
                                                             '".$teamid."',
                                                             '".$assigned_date."',
                                                             '".$taskcompleted_date."',
                                                             '".$taskcompleted_status."',
                                                             '".$assignedfrm."',
                                                             1)";
                    $result_leadTask=DB_query($sql_leadTask,$db);
                }
                
                //$duedate=date("Y-m-d",mktime(0,0,0,date("m"),date("d")+$date_interval,date("y")));  
                else{
                    
                   $sql_leadTask="INSERT INTO bio_leadtask (taskid,
                                                            leadid,
                                                            teamid,
                                                            assigneddate,
                                                            duedate,
                                                            assigned_from,
                                                            viewstatus)
                                                     VALUES('".$taskid."',
                                                            '".$lead."',
                                                            '".$teamid."',
                                                            '".$assigned_date."',
                                                            '".date("Y-m-d",mktime(0,0,0,date("m"),date("d")+$date_interval,date("y")))."',
                                                            '".$assignedfrm."',
                                                            1)";
                  $result_leadTask=DB_query($sql_leadTask,$db); 
                
                $assigned_date=date("Y-m-d",mktime(0,0,0,date("m"),date("d")+$date_interval+1,date("y"))); 
                $date_interval+=1;  
                }
        }                
           
    }
   
   else{
    
    $sql_schedule="INSERT INTO bio_leadschedule VALUES($lead,$urgency_level)"; 
    $result_schedule=DB_query($sql_schedule,$db);

    $sql_schedule1="SELECT task_master_id,
                           actual_task_day 
                      FROM bio_schedule 
                     WHERE schedule_master_id=(SELECT scheduleid FROM bio_leadschedule WHERE leadid=$lead) 
                  ORDER BY schedule_id ASC";  
    $result_schedule1=DB_query($sql_schedule1,$db);
    
   while($row_schedule1=DB_fetch_array($result_schedule1))
        {       
                $taskid=$row_schedule1['task_master_id'];
                $date_interval+=$row_schedule1['actual_task_day'];
                
                //$duedate=date("Y-m-d",mktime(0,0,0,date("m"),date("d")+$date_interval,date("y")));  
                
                $sql_leadTask="INSERT INTO bio_leadtask (taskid,
                                                         leadid,
                                                         teamid,
                                                         assigneddate,
                                                         duedate,
                                                         assigned_from,
                                                         viewstatus)
                                                  VALUES('".$taskid."',
                                                         '".$lead."',
                                                         '".$teamid."',
                                                         '".$assigned_date."',
                                                         '".date("Y-m-d",mktime(0,0,0,date("m"),date("d")+$date_interval,date("y")))."',
                                                         '".$assignedfrm."',
                                                         1)";
               $result_leadTask=DB_query($sql_leadTask,$db); 
               $assigned_date=date("Y-m-d",mktime(0,0,0,date("m"),date("d")+$date_interval+1,date("y"))); 
               $date_interval+=1; 
                }                                 
              } 
             }
    
    if(isset($_POST['PlantSelectFlag'])){
             $userid=$_SESSION['UserID'];
             $empid=$_SESSION['empid'];
             $sql_rep="SELECT reportto FROM bio_emp WHERE empid=$empid";
             $result_rep=DB_query($sql_rep,$db);
             $row_rep=DB_fetch_array($result_rep);
             $emp_repoff=$row_rep['reportto'];
   if($emp_repoff==0){
      $emp_repoff=1;
 }

$sql_user="SELECT www_users.userid
             FROM www_users
            WHERE www_users.empid=".$emp_repoff;
$result_user=DB_query($sql_user,$db);
$row_user=DB_fetch_array($result_user);
$approval_by=$row_user['userid'];


    $sql="SELECT stockid,
                 description,
                 qty,
                 price,
                 tprice 
            FROM bio_temppropitemslead 
           WHERE userid='".$userid."'";
//echo "$sql";
$result=DB_query($sql,$db);
$num_rows = $result->num_rows;

 if ($num_rows > 0) {
     $sql2="SELECT SUM(tprice) AS totalprice FROM bio_temppropitemslead WHERE userid='".$userid."'";
//echo "$sql2";
    $result2=DB_query($sql2,$db);
    $myrow2=DB_fetch_array($result2);
    $totalprice=$myrow2[0];
//echo "total price=".$totalprice;
 if ($totalprice > 0) {
     $sql3="INSERT INTO bio_proposal (propdate, leadid, totprice, status,createdby) 
                              VALUES ('".$date."',$lead,$totalprice,1,'$userid')";
//echo "$sql3";
     $ErrMsg =  _('An error occurred while inserting proposal data');
     $result3=DB_query($sql3,$db,$Errmsg);
// if ($result3) {echo "insert bioproposal done successfully";}
$sql7="SELECT LAST_INSERT_ID()";
$result7=DB_query($sql7,$db);
$myrow7=DB_fetch_array($result7);
$lastid=$myrow7[0];
$i=0;

while ($myrow=DB_fetch_array($result))   {
  
  $sql4="INSERT INTO bio_proposaldetails (propid,slno,
                                          stockid,
                                          description,
                                          qty,
                                          price,
                                          tprice) 
                                  VALUES (".$lastid.",
                                          ".++$i.",
                                          '".$myrow['stockid']."',
                                          '".$myrow['description']."',
                                          ".$myrow['qty'].",
                                          ".$myrow['price'].",
                                          ".$myrow['tprice'].")";
  $ErrMsg =  _('An error occurred while inserting proposal details data');
  $result4=DB_query($sql4,$db,$Errmsg);
}  // end $result while loop
  //if ($result4) {echo "insert bioproposal details done successfully";}
  
  $sql_sch="SELECT * FROM bio_temppropsubsidyleads
                    WHERE userid='".$userid."'";
  $result_sch=DB_query($sql_sch,$db);
  $row_count=DB_num_rows($result_sch);
  
  if($row_count>0){
    while($myrow_sch=DB_fetch_array($result_sch)){
        
        $sql_subsidy="INSERT INTO bio_propsubsidy(propid,
                                                  leadid,
                                                  stockid,
                                                  scheme,
                                                  amount)
                                           VALUES('".$lastid."',
                                                  '".$lead."',
                                                  '".$myrow_sch['stockid']."',
                                                  '".$myrow_sch['scheme']."',
                                                  '".$myrow_sch['amount']."')";
        $result_subsidy=DB_query($sql_subsidy,$db,$Errmsg);
    }  
  }
  
  $f=0;
  $sql_check="SELECT * FROM bio_proposaldetails 
                      WHERE propid=".$lastid;
  $result_check=DB_query($sql_check,$db);
  
  while ($myrow_check=DB_fetch_array($result_check))   {
         $acual_price=GetPrice($myrow_check['stockid'],$db);
         $new_price=$myrow_check['price'];
      
      if($acual_price==$new_price){
          $f=0;
      }
      else{
          $f=1;
          break;
      }
  }
  
  if($f==1){
      
    $task_ID=6;
    $duedate="0000-00-00";
    $date1="0000-00-00";
    $status=0;
    
    
    $sql_appr="INSERT INTO bio_approval(taskid,
                                        leadid,
                                        submitted_user,
                                        approval_user,
                                        assigneddate,
                                        duedate,
                                        taskcompleteddate,
                                        taskcompletedstatus,
                                        proposal_no) 
                               VALUES ('".$task_ID."',
                                       '".$lead."',
                                       '".$userid."',
                                       '".$approval_by."',
                                       '".$cur_date."',
                                       '".$duedate."',
                                       '".$date1."',
                                       '".$status."',
                                       '".$lastid."')";
    $result_appr=DB_query($sql_appr,$db);
  }
  elseif($f==0){
      
    $sql_1="UPDATE bio_proposal SET status=4
             WHERE propid=".$lastid;
    $result_1r=DB_query($sql_1,$db);
    }
  } // end if total price  >0
  
  
if($result_subsidy){
   $sql_del="DELETE FROM bio_temppropsubsidyleads";
//   $ErrMsg =  _('An error occurred while deleting temp proposal items');
   $result_del=DB_query($sql_del,$db); 
}
if ($result3 & $result4) {
    $sql5="UPDATE bio_leads SET leadstatus = '25' WHERE bio_leads.leadid=".$lead; 
    $result5=DB_query($sql5,$db,$Errmsg);

// if ($result5) {
// echo "Updated lead status";
// }
    $sql6="DELETE FROM bio_temppropitemslead WHERE userid='".$userid."'";
    $ErrMsg =  _('An error occurred while deleting temp proposal items');
    $result6=DB_query($sql6,$db,$Errmsg);
//========================================================
}  // end if result 3 and 4
}


 else
    {
    echo "No items could be retrieved";
    }   
  }
}
 if($enquiryid==8)
 {
      $teamid=$_SESSION['teamid'];
  $assigned_date=date("Y-m-d"); 
  $date_interval=1;  
    $sql_leadTask="INSERT INTO bio_leadtask (taskid,
                                                             leadid,
                                                             teamid,
                                                             assigneddate,
                                                             duedate,
                                                             assigned_from,
                                                             viewstatus)
                                                      VALUES('18',
                                                             '".$lead."',
                                                             '".$teamid."',
                                                             '".$assigned_date."',
                                                             '".date("Y-m-d",mktime(0,0,0,date("m"),date("d")+$date_interval,date("y")))."',
                                                             '".$assignedfrm."',
                                                             1)";
                 $result_leadTask=DB_query($sql_leadTask,$db);   
                 $date_interval=$date_interval+2;
                 $sql_leadTask2="INSERT INTO bio_leadtask (taskid,
                                                             leadid,
                                                             teamid,
                                                             assigneddate,
                                                             duedate,
                                                             assigned_from,
                                                             viewstatus)
                                                      VALUES('21',
                                                             '".$lead."',
                                                             '".$teamid."',
                                                             '".$assigned_date."',
                                                             '".date("Y-m-d",mktime(0,0,0,date("m"),date("d")+$date_interval,date("y")))."',
                                                             '".$assignedfrm."',
                                                             1)";
                 $result_leadTask2=DB_query($sql_leadTask2,$db);   
                 
 }
elseif($enquiryid==2 || $enquiryid==4)
{
//--------------------------------------PTS------------------------------------ 
    
if($designation==4 OR $designation==5 OR $designation==9){
    
    $sql_schedule="INSERT INTO bio_leadschedule VALUES($lead,$urgency_level)"; 
    $result_schedule=DB_query($sql_schedule,$db);

    $sql_schedule1="SELECT task_master_id,
                           actual_task_day 
                      FROM bio_schedule 
                     WHERE schedule_master_id=(SELECT scheduleid FROM bio_leadschedule WHERE leadid=$lead) 
                  ORDER BY schedule_id ASC";  
    $result_schedule1=DB_query($sql_schedule1,$db);
    $assigned_date=date("Y-m-d");
    
    while($row_schedule1=DB_fetch_array($result_schedule1))
    {       
        $taskid=$row_schedule1['task_master_id'];
        $date_interval+=$row_schedule1['actual_task_day'];
        
        //$duedate=date("Y-m-d",mktime(0,0,0,date("m"),date("d")+$date_interval,date("y")));  
        
    $sql_leadTask="INSERT INTO bio_leadtask (taskid,
                                             leadid,
                                             teamid,
                                             assigneddate,
                                             duedate,
                                             assigned_from,
                                             viewstatus)
                                      VALUES('".$taskid."',
                                             '".$lead."',
                                             '".$assignedfrm."',
                                             '".$assigned_date."',
                                             '".date("Y-m-d",mktime(0,0,0,date("m"),date("d")+$date_interval,date("y")))."',
                                             '".$assignedfrm."',
                                             1)";
    $result_leadTask=DB_query($sql_leadTask,$db); 
        
        $assigned_date=date("Y-m-d",mktime(0,0,0,date("m"),date("d")+$date_interval+1,date("y"))); 
        $date_interval+=1;                                   
    }         
    
    
    
}else{
    
    
    $sql_cce="SELECT www_users.empid,
                     bio_teammembers.teamid 
                FROM www_users,bio_teammembers 
               WHERE bio_teammembers.empid=www_users.empid";
               
   if($_POST['country']==1 && $_POST['State']==14)        //KERALA
   {                  
       if( $_POST['District']==6 || $_POST['District']==11 || $_POST['District']==12 )    //KLM-PTA-TVM
       {
           $sql_cce.=" AND www_users.userid='".bdetvm1."'";
       }
       elseif( $_POST['District']==1 || $_POST['District']==2 || $_POST['District']==3 || $_POST['District']==7 || $_POST['District']==13 ) //ALP-EKM-IDK-KTM-TRS
       {
           $sql_cce.=" AND www_users.userid='".bdeeklm1."'";                    
       }
       elseif( $_POST['District']==4 || $_POST['District']==5 || $_POST['District']==8 || $_POST['District']==9 || $_POST['District']==10 || $_POST['District']==14 ) //KNR-KSR-KZH-MLP-PLK-WND
       {
           $sql_cce.=" AND www_users.userid='".bdekoz1."'";
       }
   } 
   elseif($_POST['country']==1 && $_POST['State']!=14)     //OUTSIDE KERALA
   {
       $sql_cce.=" AND www_users.userid='".bde_national."'";
   }elseif($_POST['country']!=1){                           //OUTSIDE INDIA
       $sql_cce.=" AND www_users.userid='".bde_international."'";
       
   }
   
   $result_cce=DB_query($sql_cce,$db);
   $row_cce=DB_fetch_array($result_cce);
   $teamid=$row_cce['teamid'];   
   
   $assigned_date=date("Y-m-d");
   
   
    
   $sql_schedule="INSERT INTO bio_leadschedule VALUES($lead,$urgency_level)"; 
    $result_schedule=DB_query($sql_schedule,$db);

    $sql_schedule1="SELECT task_master_id,
                           actual_task_day 
                      FROM bio_schedule 
                     WHERE schedule_master_id=(SELECT scheduleid FROM bio_leadschedule WHERE leadid=$lead) 
                  ORDER BY schedule_id ASC";  
    $result_schedule1=DB_query($sql_schedule1,$db);
    $assigned_date=date("Y-m-d");
    
    while($row_schedule1=DB_fetch_array($result_schedule1))
    {       
        $taskid=$row_schedule1['task_master_id'];
        $date_interval+=$row_schedule1['actual_task_day'];
        
        //$duedate=date("Y-m-d",mktime(0,0,0,date("m"),date("d")+$date_interval,date("y")));  
        
    $sql_leadTask="INSERT INTO bio_leadtask (taskid,
                                             leadid,
                                             teamid,
                                             assigneddate,
                                             duedate,
                                             assigned_from,
                                             viewstatus)
                                      VALUES('".$taskid."',
                                             '".$lead."',
                                             '".$teamid."',
                                             '".$assigned_date."',
                                             '".date("Y-m-d",mktime(0,0,0,date("m"),date("d")+$date_interval,date("y")))."',
                                             '".$assignedfrm."',
                                             1)";
    $result_leadTask=DB_query($sql_leadTask,$db); 
        
        $assigned_date=date("Y-m-d",mktime(0,0,0,date("m"),date("d")+$date_interval+1,date("y"))); 
        $date_interval+=1;                                   
    }         
    
 }

//----------------------------------------------------------------------------- 

   /* $sql_task="SELECT bio_target.taskid,
                      bio_leadtasktarget.task_count
                 FROM bio_target,
                      bio_leadtasktarget
                WHERE assigneddate <= '$date'
                  AND duedate >= '$date'
                  AND officeid =".$office."
                  AND team_id =".$teamid." 
                  AND bio_target.taskid=bio_leadtasktarget.taskid
                  AND bio_leadtasktarget.target=1"; 
                     
    $result_task=DB_query($sql_task, $db);     
    $myrow_task=DB_fetch_array($result_task);
    $myrow_count1 = DB_num_rows($result_task);
    $task=$myrow_task[0];
    $target=$myrow_task[1];
    $target=$target+1;
     
 if($myrow_task[0]>0){
    $sql_leadtask="UPDATE bio_leadtasktarget
                      SET task_count=".$target."
                    WHERE taskid='$task'";
    $result_leadtask= DB_query($sql_leadtask,$db);
    }              */
}
//---------------------------------------------------------------------------------------------------
 


if(($enquiryid==1) && isset($_POST['PlantSelectFlag'])){
    $_SESSION['custid']=$newcust;
    $_SESSION['enquiryid']=$enquiryid;
    $_SESSION['CounterSale']="countersale";
    $_SESSION['lead']=$lead;
 print'<script>

     var answer = confirm("Do you want to Create the Order now?")

   if (answer){
    controlWindow=window.open("LeadsCustomers.php","CreateCustomer","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=1200,height=900");    
}
 </script> ';
    
    
}

if($advanceamt>0){
   $_SESSION['adv_id']=$adv_id;
 print'<script>

     var answer = confirm("Do you want to PRINT the Advance Receipt?")

   if (answer){
san();

   }
 </script> ';

}

//if(($enquiryid==1) && !isset($_POST['PlantSelectFlag'])){
//     $_SESSION['lead']=$lead;
// print'<script>

//     var answer = confirm("Do you want to Create the Proposals?")

//   if (answer){
//prop();

//   }
// </script> ';

//    
//}
 if($_POST['sourcetype']==14 || $_POST['sourcetype']==15 || $_POST['sourcetype']==16)
 {
     $sql_dealstaff="INSERT INTO bio_leadsource_dealstaff (leadid,sourcetype,sourceid) VALUES ('".$lead."','".$_POST['sourcetype']."','".$sourceid."')";
     $ressult_dealstaff=DB_query($sql_dealstaff,$db);
 }
    
}
unset($_POST['feedstock']);
unset($_POST['enquiry']);
unset($_POST['identity']);
unset($_POST['outputtype']);
unset($_POST['sourcetype']);
unset($_POST['printsource']);
unset($_POST['feedstock']);
unset($_POST['productservices']);
unset($_POST['District']); 
unset($_POST['country']);
}   
 
 
 if(isset($_GET['Delete']))
 {
  $delleadid=$_GET['Num'];
 
//if(  $_POST['leadid1'])
//{      echo "hii";
//    echo   $leadid;
//}
     $sql="DELETE FROM bio_leads WHERE leadid=".$delleadid;
// $result=DB_query($sql,$db);
           $ErrMsg =  _('An error occurred inserting the new Sales Leads record because');
           $Dbgmsg =  _('The SQL used to insert the Sales Leads record was');
           $result = DB_query($sql,$db,$ErrMsg,$DbgMsg);
           
  prnMsg( _('The Sales Leads record has been Deleted'),'success');
  //unset($delleadid); 
      //display($db);
 }
 
 
 if(isset($_POST['edit']) and isset($_POST['leadid1']))
 {     
     $_POST['code'];
     $sourcetype=$_POST['sourcetype'];
     $leadid= $_POST['leadid1']; 
     $_POST['customerid']; 
       
 if($_POST['contactPerson']==""){$_POST['contactPerson']=$_POST['custname'];}
//echo $_POST['advanceamt'];
    
        $outputtype=$_POST['outputtype'];
foreach($outputtype as $id2){
        $otype.=$id2.",";
      }
      
        $sourcetype;
        $outputtypeid=substr($otype,0,-1);
        $scheme=$_POST['schm'];
      
foreach($scheme as $id){
        $sourcescheme.=$id.",";
      } 
        $schemeid=substr($sourcescheme,0,-1);           
  

   $sql="UPDATE `bio_cust` 
             SET`custname` = '".$_POST['custname']."',
                 `contactperson` = '".$_POST['contactPerson']."',
                 `houseno` = '".$_POST['Houseno']."',      
                 `housename` ='".$_POST['HouseName']."',
                 `area1` = '".$_POST['Area1']."',      
                 `area2` ='".$_POST['Area2']."',
                 `pin` = '".$_POST['Pin']."',      
                 `custphone` = '".$_POST['code']."-".$_POST['phone']."',     
                 `custmob` = '".$_POST['mobile']."',
                 `custmail` = '".$_POST['email']."',
                 `careof` = '".$_POST['careof']."',
                 `taluk` = '".$_POST['taluk']."',
                 `LSG_type` = '".$_POST['lsgType']."', 
                 `LSG_name` = '".$_POST['lsgName']."',
                 `block_name` = '".$_POST['gramaPanchayath']."',
                 `LSG_ward` = '".$_POST['lsgWard']."',
                 `village` = '".$_POST['village']."' 
           WHERE `bio_cust`.`cust_id` ='".$_POST['customerid']."'";  
                  
   $result = DB_query($sql,$db,$ErrMsg,$DbgMsg);


$sql="UPDATE `bio_leads` SET  

        `productservicesid` ='".$_POST['productservices']."',
        `schemeid` ='".$schemeid."',
        `outputtypeid` = '".$outputtypeid."',
        `advanceamount` = ".$_POST['advanceamt'].",
        `investmentsize` = '".$_POST['investmentsize']."',
        `remarks` = '".$_POST['remarks']."',
        `id_type` = '".$_POST['identity']."', 
        `id_no` = '".$_POST['identityno']."',
        `status` = '".$_POST['status']."' WHERE `bio_leads`.`leadid` =$leadid";
        
        
             /*   `sourceid` = ".$_POST['source'].",*/
       
        
      //  $result=DB_query($sql,$db);
           $ErrMsg =  _('An error occurred inserting the new Sales Leads record because');
           $Dbgmsg =  _('The SQL used to insert the Sales Leads record was');
           $result = DB_query($sql,$db,$ErrMsg,$DbgMsg);
  prnMsg( _('The Sales Leads record has been Updated'),'success');
      // display($db);
      
$sqlprj="UPDATE `bio_lsgdproject` SET `project_name` = '".$_POST['Project']."' WHERE `bio_lsgdproject`.`leadid` =$leadid";  
$result = DB_query($sqlprj,$db,$ErrMsg,$DbgMsg);  
     
unset($_POST['feedstock']);
unset($_POST['enquiry']); 
unset($_POST['identity']);
unset($_POST['outputtype']);
unset($_POST['sourcetype']);
unset($_POST['printsource']);
unset($_POST['feedstock']);     
unset($_POST['productservices']);             
unset($_POST['District']);
 } //=======================================================================================================25-7
 
//  if(isset($_POST['upfeedstcks1'])){ echo"sssssssssssssssssssss".$_POST['h1feedstock'];exit;}    
 
 function display($db)  
 {      //style='float:left;width:95%;height:415px'
 echo"<table style='width:100%'><tr><td>";
 
// $_SESSION['UserStockLocation'];
     echo "<fieldset style='float:center;width:97%;'>";     
     echo "<legend><h3>Created Leads Details</h3>";
     echo "</legend>";
     echo "<table style='border:1px solid #F0F0F0;width:800px'>";
     
     echo '<tr><td>Created On</td>   
                <td>Name</td>
                <td>District</td>
                <td>Office</td>
                <td>Lead Sourse</td></tr>';
                
       //echo '<tr><td><input type="text" style="width:100px" id="datef" class=date alt='.$_SESSION['DefaultDateFormat']. ' name="datef" onChange="return isDate(this, this.value, '."'".$_SESSION['DefaultDateFormat']."'".')'.'"'.'></td>';  
       //echo '<td><input type="text" style="width:100px" id="datet" class=date alt='.$_SESSION['DefaultDateFormat'].' name="datet" onChange="return isDate(this, this.value, '."'".$_SESSION['DefaultDateFormat']."'".')'.'"'.'></td>';
      
      echo '<tr><td><select name="Actiondate" id="actiondate" style="width:120px">';
      echo '<option value=0></option>';
      echo '<option value="1">Today</option>';
      //echo '<option value="2">Tommorrow</option>';
      echo '<option value="3">Yesterday</option>';
      echo '<option value="4">Week</option>';
      echo '<option value="5">All</option>';
      echo '<option selected='.$_POST['Actiondate'].'>'.$actiondatedesc.'</option>'; 
      echo '</select></td>';
      
      
      echo '<td><input type="text" name="byname" id="byplace" style="width:120px"></td>';   
      echo '<td><input type="text" name="byplace" id="byplace" style="width:120px"></td>';
      echo '<td><select name="off" id="off" style="width:100px">';
      echo '<option value=0></option>';

         
      $sql1="select * from bio_office";
      $result1=DB_query($sql1,$db);
      while($row1=DB_fetch_array($result1))
      {
          echo "<option value=$row1[id]>$row1[office]</option>";
      }
      echo '</select></td>';
      echo '<td><select name="leadsrc" id="leadsrc" style="width:100px">';
      echo '<option value=0></option>';
      echo '<option value="ALL">Select ALL</option>';
      
      $sql1="select * from bio_leadsources";
      $result1=DB_query($sql1,$db);
      
      while($row1=DB_fetch_array($result1))
      {
          echo "<option value=$row1[id]>$row1[sourcename]</option>";
      }                                                             
      echo '</select></td>';      
      echo '<td><input type="submit" name="filterbut" id="filterbut" value="Search"></td></tr>';
      echo '</table>';
      
      
      echo "<div class='sortable' border=0 width=100%>";  
      echo "<table style='border:1px solid #F0F0F0;width:800px'>";   
      echo "<table  style='width:800px;' id='leaddetailshead'>";
             
   
     echo "<thead>
                <tr BGCOLOR =#800000>
                <th width='150px'>" . _('Customer Name/<br>Organistion Name') . "</th>
                <th width='100px'>" . _('Contact No:') . "</th>
                <th width='170px'>" . _('District') . "</th>
                <th width='150px'>" . _('Next Action') . "</th>
                <th width='85px'>" . _('Action Date') . "</th>
                <th width='85px'>" . _('Status') . "</th>
                <th width='65px'>" . _('Edit') . "</th>
                </tr></thead>";
                
      echo "</table>";
      echo "<div style='height:100px; overflow:scroll;'>";
      echo "<table  style='width:785px;' id='leaddetails'>";
 
  $date2=date("Y-m-d");
  $dt=date("Y-m-d",strtotime("-1 months"));
  $date_flag=0;
$sql5="SELECT bio_leads.leadid AS leadid, 
              bio_leads.leaddate AS leaddate, 
              bio_cust.cust_id AS custid,  
              bio_cust.custname AS custname,               
              bio_cust.houseno AS houseno,               
              bio_cust.housename AS housename,
              bio_cust.area1 AS place,
              bio_cust.custphone AS custphone,
              bio_cust.custmob AS custmob,
              bio_enquirytypes.enqtypeid AS enqtypeid,
              bio_enquirytypes.enquirytype AS enqtype,
              bio_leads.outputtypeid AS outputid,
              bio_outputtypes.outputtype AS outputtype,
              bio_cust.state AS state,
              bio_cust.district AS districtid,
              bio_district.district AS district,
              bio_status.biostatus
         FROM bio_leads,  
              bio_cust,
              bio_enquirytypes,
              bio_outputtypes,
              www_users,
              bio_district,
              bio_emp,
              bio_office,

              bio_status
        WHERE bio_leads.created_by='$_SESSION[UserID]'
          AND bio_leads.cust_id=bio_cust.cust_id
          AND bio_leads.enqtypeid=bio_enquirytypes.enqtypeid         
          AND bio_leads.outputtypeid=bio_outputtypes.outputtypeid
          AND bio_cust.district=bio_district.did
          AND bio_district.stateid=bio_cust.state
          AND bio_district.cid=bio_cust.nationality
          AND bio_leads.created_by=www_users.userid
          AND www_users.empid=bio_emp.empid
          AND bio_emp.offid=bio_office.id

          AND bio_status.statusid=bio_leads.leadstatus 
          AND bio_leads.leadstatus=0          
";   
   //         bio_leadsources                AND bio_leadsources.id=bio_leads.sourceid
//    $date = date("Y-m-d");
//$newdate = strtotime ( '-10 day' , strtotime ( $date ) ) ;
//$newdate = date ( 'Y-m-j' , $newdate );

$Currentdate=FormatDateForSQL(Date("d/m/Y"));
 if(isset($_POST['filterbut']))
 {
 
  
     
  if (isset($_POST['Actiondate']))  {
    $date_flag=1;      
    if ($_POST['Actiondate']!='')       {  
    if ($_POST['Actiondate']==1) {
        
    $sql5 .=" AND bio_leads.leaddate = '".$Currentdate."'";
        
    }
    if ($_POST['Actiondate']==2) {
        
        $date=explode("-",$Currentdate);

                    $startdate2=$date[1]."/".$date[2]."/".$date[0];
  
                    $Tommorrow1 = strtotime($startdate2 . " +1 day");
                    $Tommorrow=date("d/m/Y",$Tommorrow1);

                    $Tommorrow2=FormatDateForSQL($Tommorrow);    
        
                    $sql5 .=" AND bio_leads.leaddate = '".$Tommorrow2."'";
        
    }
    if ($_POST['Actiondate']==3) {
        
        $date=explode("-",$Currentdate);

                          $startdate2=$date[1]."/".$date[2]."/".$date[0];
  
                          $Yesterday1 = strtotime($startdate2 . " -1 day");
                          $Yesterday=date("d/m/Y",$Yesterday1);

                          $Yesterday2=FormatDateForSQL($Yesterday);    
        
                          $sql5 .=" AND bio_leads.leaddate = '".$Yesterday2."'";
        
    }
    if ($_POST['Actiondate']==4) {
        
        $date=explode("-",$Currentdate);

                          $startdate2=$date[1]."/".$date[2]."/".$date[0];
  
                          $lastweek1 = strtotime($startdate2 . " -7 day");
                          $lastweek=date("d/m/Y",$lastweek1);

                          $lastweek2=FormatDateForSQL($lastweek);
                
        
                          $sql5.=" AND bio_leads.leaddate BETWEEN '$lastweek2' AND '$Currentdate'";

    }
    if ($_POST['Actiondate']==5) {
        
    
        
    }   
    }
    }         
   
   
   
    if ((isset($_POST['datef'])) && (isset($_POST['datet'])))   
    {
    if (($_POST['datef']!="") && ($_POST['datet']!=""))  
    { 
    $sourcetypefrom=FormatDateForSQL($_POST['datef']);   
    $sourcetypeto=FormatDateForSQL($_POST['datet']);
    $sql5.=" AND bio_leads.leaddate BETWEEN '".$sourcetypefrom."' AND '".$sourcetypeto."'";
     }  
    }
    $officeid=$_POST['off'];
  //  echo $officeid;
    if (isset($_POST['byname']))  {        
    if ($_POST['byname']!='')   
    $sql5 .= " AND bio_cust.custname LIKE '%".$_POST['byname']."%'";   
    }                                                                    
            
    if (isset($_POST['byplace'])) {
    if ($_POST['byplace']!='') 
    $sql5 .= " AND bio_district.district LIKE '%".$_POST['byplace']."%'"; 
    }
    
    if (isset($_POST['off']))    {
    if (($_POST['off']!='')&&($_POST['off']!='0'))
    $sql5.=" AND bio_office.id=".$officeid;    
    }
    
    if (isset($_POST['leadsrc'])) {
    if (($_POST['leadsrc']!='ALL') && ($_POST['leadsrc']!=0))
    $sql5.=" AND bio_leads.sourceid='".$_POST['leadsrc']."'";
    }
 }
 else{
     if($date_flag==0){
        $sql5.=" AND bio_leads.leaddate between '$dt' and '$date2'"; 
     }
 }
 
        $sql5.=" order by bio_leads.leadid desc";
     //  echo $sql5;
 
 function convertsqldatefordis($d) {
          $array=explode('-',$d);
          $dd="$array[2]/$array[1]/$array[0]";
return $dd;        
}
//echo $sql5;
          $result=DB_query($sql5,$db);
     // $count=DB_fetch_row($result); 
     //print_r($count);
    echo '<tbody>';
    echo '<tr>';                                       
    $no=0; 
    
          $k=0; 
          while($myrow=DB_fetch_array($result))
          {    
               if ($k==1)
                {
                    echo '<tr class="EvenTableRows">';
                    $k=0;
                    
                }
                 else 
                 {
                    echo '<tr class="OddTableRows">';
                    $k=1;     
                 }
              $no++;
                
                  $leadid=$myrow['leadid']; $enq=$myrow['enqtypeid'];
                  $outputtype_id=$myrow['outputid'];
                  $outputtype_id2=explode(',',$outputtype_id);
                  $n=sizeof($outputtype_id2);
                  $outputtypes="";
              for($i=0;$i<$n;$i++)        {
                  $sql_1="SELECT bio_outputtypes.outputtype
                            FROM bio_outputtypes
                           WHERE outputtypeid='$outputtype_id2[$i]'";
                  $result_1=DB_query($sql_1,$db);
                  
                  $myrow_1=DB_fetch_array($result_1);
                  $outputtypes=$myrow_1[0].",".$outputtypes;
                  } 
                  $outputtypes=substr($outputtypes,0,-1);
                  $custname=$myrow['custname'];
                  $contact_person=$custname;
                  $custmob=$myrow['custmob'];
                  
               if($custmob=="" OR $custmob==0){
                  $custmob=$myrow['custphone'];
               if($custmob=="" OR $custmob==0){
                  $custmob="";
                  }
               }  
                  
               $sql_action="SELECT * FROM bio_leadtask,
                                          bio_task
                                    WHERE bio_leadtask.leadid=".$leadid."
                                      AND bio_leadtask.taskcompletedstatus=0
                                      AND bio_task.taskid=bio_leadtask.taskid
                                 ORDER BY bio_leadtask.assigneddate ASC";
                                 
               $result_action=DB_query($sql_action,$db);
               $myrow_action=DB_fetch_array($result_action);
               $next_action=$myrow_action['task']; 
               $action_date=$myrow_action['assigneddate'];
               
      if($action_date!="" AND $next_action!==""){
         $action_date=ConvertSQLDate($action_date);
      }else{
         $next_action='Not assigned';
         $action_date='Not assigned'; 
      }
      echo "<input type='hidden' id='leadid' name='leadid' value='$leadid'>";      
 
	  $custmob=str_replace(',','<br />',$custmob);
          
printf("<td cellpading=2 width='150px'>%s</td>
        <td width='100px'>%s</td>
        <td width='170px'>%s</td>
        <td width='160px'>%s</td>
        <td width='85px'>%s</td> 
        <td width='85px'>%s</td> 
        <td width='50px'><a  style='cursor:pointer;'  onclick=showCD2('$leadid','$enq')>" . _('Edit') . "</a></td>  
        </tr>",
        $custname,
        $custmob,
        $myrow['district'],
        $next_action,
        $action_date,
        $myrow['biostatus'],
        $advance_amount,
        $_SERVER['PHP_SELF'] . '?' . SID,$myrow[0]);
        }                
         
          //$_SERVER['PHP_SELF'] . '?' . SID,$myrow[0]         <td><a href='%sNum=%s&Delete=1'>" . _('Delete') . '</td>  
      echo '</tbody>';
      echo '</table>';  
      echo '</div>';  
      echo '</div>';
      echo "</fieldset></td></tr><tr><td><table>";
      echo"</table>"; 
 }
    //echo " <div style='width:100%'>";
   
//if(isset($_POST['weight']))    
//function addfeedstock()
//   {
//       echo "hi";
//       $sql1="SELECT leadid FROM bio_leads ORDER BY id DESC";
//       $result1=DB_query($sql1, $db);
//       $myrow1=DB_fetch_array($result1);
//       $lid=$myrow1[0];
//       echo $lid;
//   }    
   // echo "<div style='width:110%;height:230%;'>";           
   echo '<center><font style="color: #333;
          background:#fff;
          font-weight:bold;
          letter-spacing:0.10em;
          font-size:16px;
          font-family:Georgia;
          text-shadow: 1px 1px 1px #666;">LEAD REGISTER</font></center>'; 
   
   echo "<form action=" . $_SERVER['PHP_SELF'] . "?" . SID ." method=post name='form'>";
   echo"<table><tr><td>";
   echo "<div id=editleads>";  
   echo "<table border=0 style='width:55%;height:150%;'>"; 
   echo "<tr><td style='width:43%' valign=top>";
//Customer Details Fieldset.............................Customer Details Fieldset...............................Customer Details Fieldset 
//echo "<div>";  

   echo "<fieldset style='float:left;width:95%;height:auto'>";     
   echo "<legend><h3>Customer Details</h3>";
   echo "</legend>";     
   echo "<table border=0 style='width:100%'>";  
//Customer Details

    echo '<tr><td style="width:45%">Customer Type*</td>';
    echo  '<td style="text-align:left;width:55%">';
    echo '<select name="enquiry" id="enquiry" style="width:164px" tabindex=1  onchange="showinstitute(this.value)">';
          
/*          $sql1="SELECT * FROM bio_enquirytypes"; 
          $result1=DB_query($sql1,$db);
          $f=0;
   
    while($myrow1=DB_fetch_array($result1))
    { 
    if ($myrow1['enqtypeid']==$_POST['enquiry']) 
    {
    echo '<option selected value="';
    } else {
    if ($f==0) 
    {
    echo '<option>';
    }
    echo '<option value="';
    $f++;
    }
    echo $myrow1['enqtypeid'] . '">'.$myrow1['enquirytype'];
    echo '</option>';
    }  */
    echo '<option value=""></option>';
    echo '<option value="1">Domestic</option>';
    echo '<option value="2">Institution</option>';
    echo '<option value="3">LSGD</option>';
    echo '<option value="8">Dealer</option>'; 
    echo '<option value="7">Joint Venture</option>';
     echo '<option value="11">Others</option>';
    echo '</select>';    
    echo '</td></tr>';
    
//    echo "<tr id='lsgdid'><td style='width:50%'>LSGD Type:</td>";
//    echo "<td><select id='lsgd' name='lsgd' onchange='showinstitute(this.value)' style='width:190px'>";
//    echo'<option value=""></option>';
//    echo'<option value="1">Domestic</option>';
//    echo'<option value="2">Institute</option>';
//    echo'<option value="4">LSGD Direct</option>';
//    echo"</select></td></tr>"; 
        
    
    echo'</table>';
    
    echo"<table width='100%' border=0 id='instdom'>";
    echo "<tr><td style='width:45%'>Customer Name*</td>";
    echo "<td  style='text-align:left;width:55%'><input type='text' name='custname' id='custid' tabindex=2 style='text-transform:capitalize;width:160px'></td>";
    
  // echo "jjjjjj";
//   echo $_SESSION['officeid'];
//   echo $_SESSION['level'];
//   echo $_SESSION['designationcode'];
//   echo $_SESSION['officetype'];
    
    echo "<tr><td style='width:45%'>House No:</td><td style='text-align:left;width:55%'><input type='text' name='Houseno' id='Houseno' tabindex=3 onkeyup='' style='width:160px'></td></tr>";    
    echo "<tr><td style='width:45%'>House / Org</td><td style='text-align:left;width:55%'><input type='text' name='HouseName' id='HouseName' tabindex=4 onkeyup='' style='width:160px'></td></tr>";
    echo "<tr><td style='width:45%'>Residencial Area:</td><td style='text-align:left;width:55%'><input type='text' name='Area1' id='Area1' tabindex=5 onkeyup='' style='width:160px'></td></tr>";
    echo "<tr><td style='width:45%'>Post Office:</td><td style='text-align:left;width:55%'><input type='text' name='Area2' id='Area2' tabindex=6 onkeyup='' style='width:160px'></td></tr>";
    echo" <tr><td style='width:45%'>Pin:</td><td style='text-align:left;width:55%'><input type='text' name='Pin' id='Pin' tabindex=7 onkeyup='' style='width:160px'></td></tr>";    
    echo"</table>";
    echo"<table width='100%' border=0>";
       // echo "<tr><td>Nationality:</td><td><select name='Nationality' id='Nationality' style='width:99%'><option value='INDIA'>INDIA</option></select></td></tr>" ;
    
    $sql="SELECT * FROM bio_country ORDER BY cid";
    $result=DB_query($sql,$db);
    
    echo"<tr><td style='width:45%'>Country*</td><td  style='text-align:left;width:55%'>";
    echo '<select name="country" id="country" tabindex=9 onchange="showstate(this.value)" style="width:164px">';
    $f=0;
  while($myrow1=DB_fetch_array($result))
  {  
  if ($myrow1['cid']==1)  
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
  echo '</select></td></tr>';
  
//        echo"<input type='text'  id='Nationality' onkeyup='' style='width:99%'></td></tr>";
            
//        echo "<tr><td  style='width:45%'>State:</td><td  style='text-align:left;width:55%'><input type='text' name='State' id='State' onkeyup='' style='width:164px'></td></tr>"; 
// =======       echo "<tr><td>State:</td><td><select name='State' id=' State'style='width:99%'><option value='Kerala' >Kerala</option></select></td></tr>" ;    
           // echo"<tr id='showstate'><td>State*</td><td><select /*disabled*/ style='width:100%'><option></option></select>";
//            echo'</td>'; echo'</tr>';

    $sql="SELECT * FROM bio_state WHERE cid=1 ORDER BY stateid";
    $result=DB_query($sql,$db);
 
 echo"<tr id='showstate'><td  style='width:45%'>State*</td><td style='text-align:left;width:55%'>";
 echo '<select name="State" id="state" style="width:164px" tabindex=10 onchange="showdistrict(this.value)">';
   $f=0;
  while($myrow1=DB_fetch_array($result))
  {
  if ($myrow1['stateid']==14)
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
  echo'</tr>';
  
 // echo"<tr id='showdistrict'><td>District*</td><td><select disabled style='width:100%'><option>District</option></select></td>";
  //echo'</tr>';
  //echo"<tr id='district1'><td>Districts:</td><td><input type='text' name='Districts' style='width:100%' id='District'></td>";
  //echo'</tr>';
  
  $sql="SELECT * FROM bio_district WHERE stateid=14 ORDER BY did";
  $result=DB_query($sql,$db);
 
 echo"<tr id='showdistrict'><td  style='width:45%'>District*</td><td  style='text-align:left;width:55%'>";
 echo '<select name="District" id="Districts" style="width:164px" tabindex=11 onchange="showtaluk(this.value)">';
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
  echo'</tr>';
  
    echo '<tr><td style="width:45%">' . _('LSG Type') . ':</td>';
    echo '<td style="width:55%"><select name="lsgType" tabindex=12 id="lsgType" style="width:164px" onchange=showblock(this.value)>';
    echo '<option value=0></option>'; 
    echo '<option value=1>Corporation</option>';
    echo '<option value=2>Muncipality</option>';
    echo '<option value=3>Panchayat</option>';           
    echo '</select></td></tr>'; 
    
        echo '<tr><td align=left colspan=2>';
        echo'<div style="align:left" id=block>';
                    
        echo'</div>';
        echo'</td></tr>';
        
        echo"<tr id='showgramapanchayath'></tr>";  
        
    echo '<tr><td style="width:45%">' . _('LSG-Ward No/Name') . ':</td>
              <td style="width:55%"><input tabindex=15 type="Text" name="lsgWard" id="lsgWard" style=width:160px value=""></td></tr>';       
              
        echo"<tr id='showtaluk'></tr>";     
        echo"<tr id='showvillage'></tr>";  
//    echo '<tr><td>' . _('Village Name'). ':</td>
//              <td><input tabindex=9 type="Text" name="village" id="village" style=width:190px value=""></td></tr>';       
  
  
//        echo "<tr><td>District:</td><td><input type='text' name='District' id='District' onkeyup='' style='width:99%'></td>";       =========
//echo "<tr><td>District:</td><td><select name='District' id=' Districts'style='width:99%'>
//<option value='Thiruvananthapuram' >Thiruvananthapuram</option><option value='Kollam' >Kollam</option></select></td></tr>" ;         
    echo '</tr>';
    echo '<tr><td style="width:45%">Phone number*</td>';
    echo "<td align='left' style='width:55%;padding-left:0px'><table align='left' style='padding-left:0px' width='155px'><td><input type=text name='code' id='code' placeholder='STD' style='width:50px;padding-left:0px' tabindex=18></td><td><input type=text name=phone placeholder='NUMBER' tabindex=19 id=phone style='width:100px' onchange=checkLandline()></td></table></td></tr>";
    
    echo '<tr><td style="width:45%">Mobile Number</td>';
    echo "<td style='width:55%'><input type=text name='mobile' id='mobile' style='width:160px' tabindex=20 onchange='checkMobile()'></td></tr>"; 
    echo '<tr><td style="width:45%">Email id</td>';
    echo "<td style='width:55%'><input type=text name='email' id='email' style='width:160px' tabindex=21 onchange='validate()'></td></tr>";
    //Product Sevices


    echo '<tr><td style="width:45%">Identity Type</td>';
    echo'<td style="width:55%"><select name="identity" id="identity" style="width:164px" tabindex=22>';
    $sql1="SELECT * FROM bio_identity";
    $result1=DB_query($sql1,$db);
$f=0;
while($myrow1=DB_fetch_array($result1))
{
    if ($myrow1['ID_no']==$_POST['identity'])
    {
        echo '<option selected value="';
    } else 
    {
        if ($f==0) 
        {
            echo '<option>';
        }
        echo '<option value="';
        $f++;
    }
    echo $myrow1['ID_no'] . '">'.$myrow1['ID_type'];  
    echo '</option>';
}
echo'</select></td></tr>';

    echo '<tr><td style="width:45%">Identity No</td>'; 
    echo '<td style="width:55%"><input type="text" style="width:160px" name="identityno" id="identityno" tabindex=23></td></tr>'; 
   // echo '<tr><td>KSEB NO</td>'; 
   // echo '<td><input type="text" style="width:190px" name="kseb" id="kseb" tabindex=24 onchange="ksebtest()"></td><td id=eb></td></tr>'; 

   
  //Enquiry type
//    echo '<tr><td>Enquiry Type</td>';
//    echo  '<td>';
//    echo '<select name="enquiry" id="enquiry" style="width:190px">';
//    $sql1="SELECT * FROM bio_enquirytypes"; 
//    $result1=DB_query($sql1,$db);
//    $f=0;
//    while($myrow1=DB_fetch_array($result1))
//    { 
//    if ($myrow1['enqtypeid']==$_POST['enquiry']) 
//    {
//       
//    echo '<option selected value="';
//    
//    } else {
//        if ($f==0) 
//        {
//        echo '<option>';
//        }
//        echo '<option value="';
//        $f++;
//    }
//    echo $myrow1['enqtypeid'] . '">'.$myrow1['enquirytype'];
//    echo '</option>';
//    }
//     
//    echo '</select>';    
//    echo '</td></tr>';

    
    
      //Feedstock
//    echo '<tr><td>Feed Stock</td><td>';
//     
//  $sql1="SELECT * FROM bio_feedstocks";
//  $result1=DB_query($sql1, $db);
//  echo '<select name=feedstock style="width:190px">';
//  while($myrow1=DB_fetch_array($result1))
//  {  
//  if ($myrow1['id']==$_POST['feedstock']) 
//    {
//    echo '<option selected value="';
//    
//    } else {
//        echo '<option value="';
//    }
//    echo $myrow1['id'] . '">'.$myrow1['feedstocks']; 
//    echo '</option>' ;
//   }
//  echo '</select></td>';
//  echo '</tr>';

   //output types
 /*  
  echo '<tr id="showoutputtype"><td>Output Type*</td>';
  echo'<td>';
  $sql1="SELECT * FROM bio_outputtypes";
  $result1=DB_query($sql1, $db);
  echo '<select name="outputtype" id="outputtype" style="width:190px">';
  $f=0;
  while($myrow1=DB_fetch_array($result1))
  {  
  if ($myrow1['outputtypeid']==$_POST['outputtype']) 
    {
    echo '<option selected value="';
    
    } else 
    {
        if ($f==0) 
        {
        echo '<option>';
        }
        echo '<option value="';
        $f++;
    }
    echo $myrow1['outputtypeid'] . '">'.$myrow1['outputtype'];  
    echo '</option>';
  }
  echo '</select></td>';
  echo '</tr>';
*/
  
  
    //Raw Materials
//  echo '<tr><td>Raw Materials(Kg.)</td>';
//  echo "<td><input type=text name=rmkg style='width:98%'></td></tr>"; 
  //Advance amount  
  

        
  
  echo"<table style='width:100%' id='modeamt'>";
  echo"</table>"; 

  echo "</fieldset>";   
 // echo '</div>';
  echo "</td>";
  
  
   //Leads details fieldset .................................Leads details fieldset.....................Leads details fieldset 
   
   
  echo "<td style='width:57%' valign=top>";

  echo "<fieldset style='float:right;width:94%;height:497px;'>";         
  echo "<legend><h3>Leads Details</h3>";
  echo "</legend>";
  echo "<div style='height:450px;overflow:scroll'>"; 
  echo "<table border=0 style='width:100%'>";    
  
echo '<tr><td width=45%>Advance Amount</td>';
echo "<td width=55%><input type=text name=advanceamt id='advance' style='width:170px' tabindex=24 onblur='amntmode(this.value)'></td></tr>";
             
echo'<tr>';
echo'<td width=45%>Mode of payment:</td>';
echo'<td width=55%><select name="mode" id="paymentmode" style="width:174px" tabindex=25 onchange="advdetail(this.value)">';
$sql1="SELECT * FROM bio_paymentmodes";
$result1=DB_query($sql1, $db); 
$f=0;
while($myrow1=DB_fetch_array($result1))
{
    if ($myrow1['id']==$_POST['modes'])
    {
        echo '<option selected value="';
    } else 
    {
        if ($f==0) 
        {
            echo '<option>';
        }
        echo '<option value="';
        $f++;
    }
    echo $myrow1['id'] . '">'.$myrow1['modes'];  
    echo '</option>';
  }
echo'</select></td></tr>';  
         
echo"<tr><td colspan=2><table style='width:100%' id='amt'>";
echo"</table></td></tr>";    

//echo '<tr><td style="padding-left:0px"><table>';  
   echo '<tr><td  width=45%>Output Type*</td>';
//    echo'<tr><td  width=55% style="padding-left:0px" colspan="2"><table style="padding-left:0px" border=0><tr>';
    $sql_out="SELECT * FROM bio_outputtypes";
    $result_out=DB_query($sql_out, $db);
    $j=1;
    echo'<td align="left"  style="padding-left:0px" ><table align="left"   style="padding-left:0px" ><tr>';
    while($mysql_out=DB_fetch_array($result_out)){
        
    echo'<td style="padding-left:0px"><input style="padding-left:0px" type="checkbox" id="outputtype"'.$j.' name="outputtype[]" tabindex=26 value='.$mysql_out[0].'>'.$mysql_out[1].'</td>';
        $j++;    
            
        if( ($j%2)-1==0 ){
            echo'</tr><tr>';
        }
               
    } 
    echo"</tr>";  
    echo"</table></td></tr>";        
    echo"<input type='hidden' name='houttype' id='houttype' value='$j'>";
    
     
   echo '<tr><td width=45%>LeadSource Type*</td>';
  echo '<td width=55%>'; 

  echo '<select name="sourcetype" id="sourcetype" style="width:174px" tabindex=27 onchange=showCD(this.value)>';
  $sql="SELECT * FROM `bio_leadsourcetypes` where rowstatus!=1";//
  $result=DB_query($sql,$db); 
  echo $count=DB_fetch_row($sql,$db);
    $c=0;  
  while ($myrow = DB_fetch_array($result)) {
     
    if ($myrow['id']==$_POST['sourcetype']) 
    {
    echo '<option selected value="';
    } else if($c==0){echo '<option>';  }
        echo '<option value="';
    
    echo $myrow['id'] . '">'.$myrow['leadsourcetype'];     
   echo '</option>';
   $c++;
    }         
    echo $c;
    echo '</select></td></tr>';
    

   $d=9; 
   
//   echo '<tr><td colspan=2><div  id=hidetr>' ;
   echo '<tr id=showsource>';     
  
   echo '</tr>';
   echo "<tr><td colspan=2 style='width:44%;align=left;'>";
   

   echo '<div id="dinhide">';
   
   echo '<div id=sourcedetails class=sourcedetails>';    
   echo '</div>'; 
   
   echo '</div>'; 
//   echo '</td></tr>';  
    echo '</tr>';
     
    // echo '</div></tr>' ;
   // echo '</tr>' ; 
   // echo '<td>';
     


    echo '<tr><td width=45%>Investment Size</td>';
    echo "<td width=55%><input type=text name=investmentsize id=invest style='width:170px' tabindex=28></td>";
    echo '</tr>';
    
     //Scheme
    
    
//$sql="SELECT * FROM bio_schemes";
//  $result=DB_query($sql, $db);
//  echo '<select name=scheme id="Scheme" style="width:192px" tabindex=42>';
//  $f=0;
//  while($myrow=DB_fetch_array($result))
//  {  
//  if ($myrow['schemeid']==$_POST['scheme'])
//    {
//        echo '<option selected value="';
//    }
//  else
//   {   
//       if ($f==0) 
//        {
//        echo '<option>';
//        }    
//        echo '<option value="';
//   }
//     echo $myrow['schemeid'] . '">'.$myrow['scheme'];     
//     echo '</option>';
//     $f++;
//  }
//    echo '</select></td>';
//    echo '</tr>'; 
  
//  echo'<tr><td colspan="2"><table border=0><tr>';   
  
    echo '<tr><td>Scheme</td>'; 
    $sql1="SELECT * FROM bio_schemes";
    $result1=DB_query($sql1, $db); $j=1;
    echo'<td>';
    while ($taskdetails=DB_fetch_array($result1))
    {
    echo'<input type="checkbox" id="schm"'.$j.' name="schm[]" tabindex=29 value='.$taskdetails[0].'>'.$taskdetails[1].'';       
    $j++;         
    } 
                               
    echo"</td></tr>";
    
 //       $j++;
//        if($j>=2){
//            echo'</tr><tr><td></td>';
//        }         
                 
                        
                 /*  printf(' <td style="background:#8080FF;color:white;">%s</td>
                <td style="background:#000080;color:white"><input type="checkbox" tabindex=30 id="schm"'.$j.' name="schm[]" value="%s">%s</td>',$j,                  
               
                  $taskdetails[0],
                  $taskdetails[1]); */                 
  
    echo"<input type='hidden' name='hlol' id='hlol' value='$j'>";
//      echo $j;
    echo '<tr id="showfeasibility"><td width=45%>Product Services*</td>';
    echo'<td  width=55%>';
        $sql1="SELECT * FROM bio_productservices where enquiry_type=1";
        $result1=DB_query($sql1, $db);
  echo '<select name="productservices" id="productservices" style="width:174px" tabindex=30>';
  $f=0;
  while($myrow1=DB_fetch_array($result1))
  {  
  if ($myrow1['id']==$_POST['productservices'])  
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
    echo $myrow1['id'] . '">'.$myrow1['productservices'];
    echo '</option>';
    $f++;   } 
  echo '</select>'; 
  echo '</td>'; 
  echo '</tr>';
  
  
    echo '<tr id="replace"><td width=45%>Replacement Fuel*</td>';
    echo'<td  width=55%>';
         echo '<select name="replace" id="replace" style="width:174px" tabindex=30>';
  
        echo '<option value="1">LPG</option>';
                    echo '<option value="2">Firewood</option>';
        echo '<option value="3">Electricity</option>';
  echo '</select>'; 
  echo '</td>'; 
  echo '</tr>';
  
  
  
//$DateString=date("d/m/Y",mktime(0,0,0,date("m"),date("d")+1,date("y")));
//echo"<tr><td>Next Contact Date</td>";
//echo"<td><input type='text' name='StartDate' id='startdate' class=date alt='".$_SESSION['DefaultDateFormat']. "' tabindex=32 style='width:150px' value='".$DateString."'></td></tr>";    

    //Status 
//    echo '<tr><td>Status</td>';
//    echo "<td><input type=text name=status tabindex=33 style='width:80%'></td>";
//    echo '</tr>';
    //Remarks
    echo '<tr id="showremarks"><td  width=45%>Select a Remark</td>';
    echo'<td  width=55%>';
    echo '<select name="RemarkList" id="remarklist" tabindex=31 style="width:174px">';
        echo '<option value="" selected></option>';
  $sql="SELECT * FROM bio_remarks";
  $result=DB_query($sql,$db); 
  echo $count=DB_fetch_row($sql,$db);
      
  while ($myrow = DB_fetch_array($result)) {

    echo '<option value="';
    echo $myrow['remark'] . '">'.$myrow['remark'];     
    echo '</option>';

    }         
    echo '</select>';
    
    echo'</td>';
    echo'</tr>';
    echo'<tr><td width="45%">Remarks</td>';
//    echo "<td width='45%'><input type=text name=remarks id=remarks></td>";  
    echo "<td><textarea  name=remarks id=remarks rows=4 cols=22 tabindex=32 style=resize:none;></textarea></td>"; 
    echo '</tr>';
    echo ' <tr id="gradeview"><td>Grade</td>';
       echo '<td width=55%>

 <table style="width:85%" border="0">
  <tr width>
    <td width="15px" bgcolor="#339900"><label>
      <input type="radio" name="grade" id="grade" value="A" />
    A</label></td>
    <td width="15px" bgcolor=orange><label>
      <input type="radio" name="grade" id="grade" value="B" />
    B</label></td>
    <td width="15px" bgcolor="#0099CC"><label>
      <input type="radio" name="grade" id="grade" value="C" />
    C</label></td>
    <td width="15px" bgcolor="#CCFF33"><label>
      <input type="radio" name="grade" id="grade" value="D" />
    D</label>
      <td width="15px" bgcolor=Pink><label>
     <input type="radio" name="grade" id="grade" value="E" />
    E</label></td>
 </tr>
</table>

                  </td>  </tr>'; 
    
      echo "<tr id='schedule'><td width='45%'>Urgency Level</td>";
      
       
  echo '<td width="55%">';

  $sql1="SELECT * FROM bio_schedule_master";
  $result1=DB_query($sql1, $db);
  echo '<select name="UrgencyLevel" id="urgencylevel" style="width:174px" tabindex="33">';
  $f=0;
  while($myrow1=DB_fetch_array($result1))
  {  
  if ($myrow1['master_schedule_id']==$_POST['UrgencyLevel']) 
    {
    echo '<option selected value="';
    
    } else {
        if($f==0){echo '<option>';   }
        echo '<option value="';
    }
    echo $myrow1['master_schedule_id'] . '">'.$myrow1['schedule']." - ".$myrow1['schedule_days']." days"; 
    echo '</option>' ;
   $f++; 
   }
 echo '</select>';
 echo "</td></tr>";
    
    
    echo "</table>";    echo"</div>"; 
    echo "</fieldset>"; 
  //echo '</form>'; 
//echo '</td></tr>';
    //Feedstock  
    
     echo"<tr><td colspan='5'><center> ";
   echo"<div id=plantselection>";
   echo "<fieldset style='width:800px'>";   
   echo "<legend><h3>Select Plant</h3>";
   echo "</legend>";
   
   $sql4="DELETE FROM bio_temppropitemslead where userid='".$_SESSION['UserID']."'";
   $result4=DB_query($sql4,$db);
   $sql_4="DELETE FROM bio_temppropsubsidyleads where userid='".$_SESSION['UserID']."'";
   $result_4=DB_query($sql_4,$db);
   
  /* $sql_cat="SELECT subcatid FROM bio_maincat_subcat WHERE maincatid=1";
   $result_cat=DB_query($sql_cat,$db);
   $cat_arr=array();
   while($row_cat=DB_fetch_array($result_cat)) 
   {  
      $cat_arr[]="'".$row_cat['subcatid']."'";
      $plant_array=join(",", $cat_arr); 
   }
   $plant_array= $plant_array.",'BCLT'";
   
   $sql="SELECT categoryid,categorydescription from stockcategory
                    WHERE stockcategory.categoryid IN ($plant_array)";
   $result=DB_query($sql,$db);
      
echo "<table border=0 style='align:left'>";
echo "<tr><td>Catagory</td>";
echo '<td><select name="caty" id="caty" style="width:300px" onchange=showcatitems(this.value)>';
      echo '<option value=0>Select category</option>';
      while ( $myrow = DB_fetch_array ($result) ) {
          echo "<option value=".$myrow[categoryid].">".$myrow[categorydescription]."</option>";
      }
      echo '</select></td>';
     $sql="SELECT stockid,longdescription from stockmaster";
     $result=DB_query($sql,$db);
      
      echo '<td>Item</td>';
      echo '<td id=items><select name="Item" id="item" style="width:300px">';
      echo '<option value=0>Select Item</option>';
      while ( $myrow = DB_fetch_array ($result) ) {
          echo "<option value=".$myrow[stockid].">".$myrow[longdescription]."</option>";
      }
      echo '</select></td>';*/
      
     $sql="    SELECT `subcategoryid`,`subcategorydescription` FROM substockcategory WHERE substockcategory.maincatid =1";
   $result=DB_query($sql,$db);
      
echo "<table border=0 style='align:left'>";
echo "<tr><td>Catagory</td>";
echo '<td><select name="caty" id="caty" style="width:200px"  onchange="view(this.value)">';
      echo '<option value=0>Select category</option>';
      while ( $myrow = DB_fetch_array ($result) ) {
          echo "<option value=".$myrow[subcategoryid].">".$myrow[subcategorydescription]."</option>";
      }
      echo '</select></td>';
      echo '<td><select name="caty2" id="caty2" style="width:200px" onchange="view2(this.value)" onblur="view2(this.value)"></select></td>';
     $sql="SELECT stockid,longdescription from stockmaster";
     $result=DB_query($sql,$db);
      echo '<td id="items" ><select name="Item" id="item" style="width:200px">';
      echo '<option value=0>Select Item</option>';
   while ( $myrow = DB_fetch_array ($result) ) {
          echo "<option value=".$myrow[stockid].">".$myrow[longdescription]."</option>";
      }  
      echo '</select></td>';  
      
      echo '<td><input type="button" name="search" id="search" value="Add" style="width:100px" onclick="showItems()"></td></tr></table>';
   echo"<br />";
   echo"<div id='selecteditems'></div>";


   echo "</fieldset>";
   echo"</div>";
   echo"</center></td></tr>";
   
   echo "<tr><td colspan='5' id=viewitems>";
   
 
   echo "</td></tr>";
    
     

 
 

   echo "<tr><td colspan='5'>";
   echo "<fieldset style='width:800px'>";   
   echo "<legend><h3>Feed Stock Details</h3>";
   echo "</legend>"; 
   echo "<table style='align:left' border=0>";
   echo "<tr><td>Feed Stock</td>";
//Feedstock
   echo '<td>';

 $sql1="SELECT * FROM bio_feedstocks";
  $result1=DB_query($sql1, $db);
  echo '<select name="feedstock" id="feedstock" tabindex=36 style="width:190px">';
  $f=0;
  while($myrow1=DB_fetch_array($result1))
  {  
  if ($myrow1['id']==$_POST['feedstock']) 
    {
    echo '<option selected value="';
    
    } else {
        if($f==0){echo '<option>';   }
        echo '<option value="';
    }
    echo $myrow1['id'] . '">'.$myrow1['feedstocks']; 
    echo '</option>' ;
   $f++; 
   }
  echo '</select>';
  echo "</td>";
  echo "<td>Weight in Kg/Ltr</td>";
  echo "<td><input type=text name='weight' id='weight' tabindex=37 style='width:83%'></td>";
  echo "<td>";
 echo '<input type="button" name="addfeedstock" id="addfeedstock" value="Add" tabindex=38 onclick="showCD4()">';
//  echo '<input type="button" name="addfeedstock" id="addfeedstock" value=Add>';
  echo "</td>";
  
  echo "</tr>";
  
  

echo "</table>";

echo"<div id='editfdstok'></div>";
echo"<div id='feedstockdiv'></div>";
//==================================================


//=====================================================
//    echo $_POST['h1feedstock'];
  echo "</fieldset>";
echo "</td></tr>";
 
echo '<tr><td colspan=3><center>';        
/*<div id="show_sub_categories" align="center">
<img src="loader.gif" style="margin-top:8px; float:left" id="loader" alt="" />
</div>';   */
echo '<input type=submit name="submit" id="leads" value=Submit  tabindex=40 onclick="if(log_in()==1)return false;">';
echo '<input name="clear" type="submit" value=Clear ><input id="shwprint" type="button" name="shwprint" value="Report" >';
echo '<input type="Button" class=button_details_show name=details VALUE="' . _('Details') . '">';     
  echo '</center></td>';    
  echo '</tr>';
  
  
  //==========================================================
 
 echo"<tr><td colspan='5'>"; 
 echo"<div id='selectiondetails'>";

echo"<fieldset style='width:700px; overflow:auto;'>";
echo"<legend><h3>All Links</h3></legend>";
echo '<table width="100%">
    <tr>
        <th width="50%">' . _('Masters') . '</th>
        <th width="50%">' . _('Reports') . '</th>
   
    </tr>';
echo"<tr><td  VALIGN=TOP >";
echo '<a href="bio_viewleads.php" style=cursor:pointer; >' . _('View Leads') . '</a><br>';
echo '<a href="bio_report.php" style=cursor:pointer; >' . _('Transfer Leads') . '</a><br>';
echo '<a href="bio_activefeasibilitystudyproposals.php" style=cursor:pointer; >' . _('Active Leads') . '</a><br>';
echo '<a href="bio_passivefeasibilitystudyproposals.php" style=cursor:pointer; >' . _('Passive Leads') . '</a><br>';
echo '<a style=cursor:pointer; onclick=showProdPeriod()>' . _('Cutomer Ledger') . '</a><br>';
echo '<a style=cursor:pointer; onclick=showProdPeriod()>' . _('Cash Book') . '</a><br>';


echo"</td><td  VALIGN=TOP >";
echo '<a href="bio_dprint_A5p.php" style=cursor:pointer;>' . _('Print Advance Reciept') . '</a><br>';
//echo '<a style=cursor:pointer; onclick=showProdPeriod()>' . _('Print Covering Letter') . '</a><br>';
//echo '<a style=cursor:pointer; onclick=addNewSeasonName()>' . _('Add New Season Name') . '</a><br>';
echo"</td></tr>";
echo'</table>';
echo"</fieldset>";

echo "</div>";  
  
echo"</tr></td>"  ;
  
 
  
  
  //==========================================================
  
 echo"<tr><td colspan='2'>";
 echo '<div id=duplicatename>';  
 
 
 
 
    
 echo '</div>';  
 echo "</td></tr>";
  
  
  echo"<tr><td colspan='2'>"; 
  echo"<div id='printgrid' style='margin:auto;'>";
  echo"<table><tr><td>Date From";
echo'<input type="text"  id="datefrm" class=date alt='.$_SESSION['DefaultDateFormat']. ' name="datefrm" onChange="return isDate(this, this.value, '."'".$_SESSION['DefaultDateFormat']."'".')'.'"'.'>';    
 echo"</td><td>To Date ";
 echo'<input type="text"  id="dateto" class=date alt='.$_SESSION['DefaultDateFormat']. ' name="dateto" onChange="return isDate(this, this.value, '."'".$_SESSION['DefaultDateFormat']."'".')'.'"'.'>';    
 echo"</td>";
      
 echo"<td>Office";
     echo '<select name="offic" id="offic">';
   $sql1="SELECT bio_office.id,bio_office.office
FROM bio_office";
   $result1=DB_query($sql1,$db) ;
   $a=0;
      while ($myrow1 = DB_fetch_array($result1)) {
    if ($myrow1['id']==$_POST['office']) 
    {
    echo '<option  selected value="';
} else if($a==0){echo"<option>";  }

        echo '<option value="';
        echo $myrow1['id'] .'">'.$myrow1['office'];
        echo  '</option>';
        $a++;
    }  
     echo"</select>";    
     echo"</td>";
     
     echo '<td>Place';
     echo '<input type="text" name="place" id="place"></td>';

     echo '<td>Enquiry Type';
      echo '<select name="enquiry1" id="enquiry1" style="width:190px">';
    $sql1="SELECT * FROM bio_enquirytypes"; 
    $result1=DB_query($sql1,$db);
    $f=0;
    while($myrow1=DB_fetch_array($result1))
    { 
    if ($myrow1['enqtypeid']==$_POST['enquiry']) 
    {
       
    echo '<option selected value="';
    
    } else {
        if ($f==0) 
        {
        echo '<option>';
        }
        echo '<option value="';
        $f++;
    }
    echo $myrow1['enqtypeid'] . '">'.$myrow1['enquirytype'];
    echo '</option>';
    }
     
    echo '</select>';
     echo '</td>';
     
 echo "<td colspan=''>";
   echo 'LeadSource'; 
   
   echo '<select name="printsource" id="printnshow" style="width:192px" onchange="printshow()">';

   $sql1="SELECT id,sourcename, sourcetypeid
FROM bio_leadsources and rowstatus!=1";
   $result1=DB_query($sql1,$db) ;
       $a=0;
   while ($myrow1 = DB_fetch_array($result1)) {
    if ($myrow1['id']==$_POST['source']) 
    {
    echo '<option  selected value="';
} else if($a==0){echo"<option>"; echo'<option value="ALL">---Select ALL---</option>';  }


        echo '<option value="';
        echo $myrow1['id'] .'">'.$myrow1['sourcename'];
        echo  '</option>';
        $a++;
    }  
    echo '</select>';    
    echo"<tr><td colspan='2'><div id='printandshow' style='margin:auto;'>";  
    echo"</div></td></tr></table>"; echo '</div>';  
    echo"</td></tr>";
    echo '</table></td></tr><tr><td>';
//  echo "</div>";
   echo '</div>'; 
   echo "<table style='width:100%;height:auto;' border=0><tr><td>";  
   display($db);
   
   
   
   
   echo "</td></tr></table></td></tr></table>";
   echo '</form>';         
 // echo '</div>'; 

// include('includes/footer.inc'); 
?>    
<script type="text/javascript">






//location.href="bio_print.php?lead=" + str;     
/*function schemecheck()
{  var f=0;
    var a=new Array();
    a=document.getElementsByName("schm[]");
//    alert("Length:"+a.length);
    var p=0;
    for(i=0;i<a.length;i++){
        if(a[i].checked){
//            alert(a[i].value);
            p=1;
        }
    }
    if (p==0){ var f=1;
        alert('please select at least one Scheme');
        return f;
    }

//    document.some_form.submitted.value='yes';
//    return true;
}  */


       
        













</script>

