<?php
 //$q=$_GET['q'];
 
 //$sql="SELECT custname FROM bio_incident_cust WHERE custname LIKE '%$my_data%' ORDER BY custname";
 //$result = mysql_query($sql,$mysq) or die(mysql_error());
if(isset($_POST['kw']) && $_POST['kw'] != '')
{
$mysq=mysql_connect('localhost','tsunamis_biotech','bio23tech45');
$db=mysql_select_db('tsunamis_biotech');
  $kws = $_POST['kw'];
  $kws = mysql_real_escape_string($kws);
  $query = "SELECT custname FROM bio_incident_cust WHERE custname LIKE '%$kws%' ORDER BY custname asc";
 
  $res = mysql_query($query,$mysq);
  //Here we count the number of returned rows. If it returned nothing then it will store 0.
  $count = mysql_num_rows($res);
  $i = 0;
 
  if($count > 0)
  {
    echo "<ul>";
    while($row = mysql_fetch_array($res))
    {
      echo "<a href='#'><li>";
      echo "<div id='auth_img'><img src='images/".$row['image']."'></div>";
      echo "<div id='rest'>";
      echo $row['quotes'];
      echo "";
      echo "";
      echo "<div id='auth_dat'>".$row['person']."&nbsp;&nbsp;&nbsp;&nbsp;".$row['date']."</div>";
      echo "</div>";
      echo "<div style='clear:both;'></div></li></a>";
      $i++;
      if($i == 5) break;
    }
    echo "</ul>";
    if($count > 5)
    {
      echo "<div id='view_more'><a href='#'>View more results</a></div>";
    }
  }
  else
  {
    echo "<div id='no_result'>No result found !</div>";
  }
}
?>