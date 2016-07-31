
    <?php
      
    $PageSecurity = 15;
include('includes/session.inc');
$title = _('Project master ') . ' / ' . _('Maintenance');
include('includes/header.inc');

if (isset($_POST['SelectedType'])){
    $SelectedType = strtoupper($_POST['SelectedType']);
} elseif (isset($_GET['SelectedType'])){
    $SelectedType = strtoupper($_GET['SelectedType']);
}

if (isset($Errors)) {
    unset($Errors);
}
 $Errors = array();

echo '<p class="page_title_text"><img src="'.$rootpath.'/css/'.$theme.'/images/maintenance.png" title="' . _('Project master')
    . '" alt="" />' . _('Project Setup') . '</p>';
echo '<div class="page_help_text">' . _('Add/edit/delete Project master') . '</div><br />';
echo '<a href="index.php">Back To Home</a>';
echo '<table style=width:25%><tr><td>';
echo '<fieldset style="height:250px">';
echo '<legend><b>Project Master</legend>' ;
echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '">';
  
echo '<br><br><table class="selection">'; 
  

echo '<tr><td>' . ('Project name') . ':</td>
        <td><input type="text" name="Project name" value="' . $_POST['Project master'] . '"></td>
        </tr>';
        
echo '<tr><td>' . ('Category') . ':</td>
        <td><input type="text" name="Category" value="' . $_POST['Project master'] . '"></td>
        </tr>';  
        
        
echo '<tr><td>' . ('Sub category') . ':</td>
        <td><input type="text" name="Sub category" value="' . $_POST['Project master'] . '"></td>
        </tr>'; 
                         
echo '<tr><td>Mandatory items</td>';
echo '<td><select name="item"></td></tr>';   
     
echo '<tr><td>Optional item </td>';
echo '<td><select name="optional"></td></tr>';  
     
echo '<tr><td>Schemes   </td>';
echo '<td><select name="schems"></td></tr>';  
  
  
echo '<tr><td></td><td><input type="submit" name="submit" value="Assign"></td></tr>';   
                 
   
    
?>  