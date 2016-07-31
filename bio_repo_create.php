<?php
$PageSecurity = 80;
include('includes/session.inc');

$leads_target_q="SELECT COALESCE(sum(bio_target.target),0) as leads_target FROM bio_target
        WHERE bio_target.enqid=2 AND bio_target.month=6 AND bio_target.year=2012
	AND bio_target.task=0 ";

$mtg_target_q="SELECT COALESCE(sum(bio_target.target),0) as mtg_target  FROM bio_target
        WHERE bio_target.enqid=2 AND bio_target.month=6 AND bio_target.year=2012
	AND bio_target.task IN(18,27)";

$fs_target_q="SELECT COALESCE(sum(bio_target.target),0) as fs_target FROM bio_target
        WHERE 	bio_target.enqid=2 AND bio_target.month=6 AND bio_target.year=2012
	AND bio_target.task =2";

$cp_target_q="SELECT COALESCE(sum(bio_target.target),0) as cp_target FROM bio_target
        WHERE bio_target.enqid=2 AND bio_target.month=6 AND bio_target.year=2012
	AND bio_target.task=3";

$so_target_q="SELECT COALESCE(sum(bio_target.target),0) as so_target FROM bio_target
        WHERE bio_target.enqid=2 AND bio_target.month=6 AND bio_target.year=2012
	AND bio_target.task=5";

$fscollection_target_q="SELECT COALESCE(sum(bio_target.target),0) as fscollection_target FROM bio_target
        WHERE bio_target.enqid=2 AND bio_target.month=6 AND bio_target.year=2012
	AND bio_target.task=28";

$so_adv_coll_target_q="SELECT COALESCE(sum(bio_target.target),0) as so_adv_coll_target FROM bio_target
        WHERE 	bio_target.enqid=2 AND bio_target.month=6 AND bio_target.year=2012
	AND bio_target.task=20";

$supply_pymt_coll_target_q="SELECT COALESCE(sum(bio_target.target),0) as supply_pymt_coll_target FROM bio_target
        WHERE 	bio_target.enqid=2 AND bio_target.month=6 AND bio_target.year=2012
	AND bio_target.task=26";

$sql_leads_perf_q="SELECT COALESCE( count( * ) , 0 ) AS leads_perf
   FROM bio_leads WHERE bio_leads.leaddate BETWEEN '2012-06-01' AND '2012-06-30'
   AND bio_leads.enqtypeid =2  AND leadstatus !=20";

$mtg_perf_q="SELECT COALESCE( count( * ) , 0 ) AS mtg_perf
FROM bio_leadtask
LEFT JOIN bio_leads ON bio_leadtask.leadid = bio_leads.leadid
LEFT JOIN bio_task ON bio_task.taskid = bio_leadtask.taskid
WHERE bio_leadtask.viewstatus =1
AND bio_leadtask.taskcompletedstatus =1
AND bio_leadtask.taskcompleteddate
BETWEEN '2012-06-01' AND '2012-06-30'
AND bio_leads.enqtypeid =2
AND bio_leadtask.taskid IN (18,27) ";

$fs_perf_q="SELECT COALESCE( count( * ) , 0 ) AS fs_perf
FROM bio_leadtask
LEFT JOIN bio_leads ON bio_leadtask.leadid = bio_leads.leadid
LEFT JOIN bio_task ON bio_task.taskid = bio_leadtask.taskid
WHERE bio_leadtask.viewstatus =1
AND bio_leadtask.taskcompletedstatus =1
AND bio_leadtask.taskcompleteddate
BETWEEN '2012-06-01' AND '2012-06-30'
AND bio_leads.enqtypeid =2
AND bio_leadtask.taskid =2 ";

$cp_perf_q="SELECT COALESCE( count( * ) , 0 ) AS cp_perf
FROM bio_leadtask
LEFT JOIN bio_leads ON bio_leadtask.leadid = bio_leads.leadid
LEFT JOIN bio_task ON bio_task.taskid = bio_leadtask.taskid
WHERE bio_leadtask.viewstatus =1
AND bio_leadtask.taskcompletedstatus =1
AND bio_leadtask.taskcompleteddate
BETWEEN '2012-06-01' AND '2012-06-30'
AND bio_leads.enqtypeid =2
AND bio_leadtask.taskid =3 ";

$so_perf_q="SELECT COALESCE( count( * ) , 0 ) AS so_perf
FROM bio_leadtask
LEFT JOIN bio_leads ON bio_leadtask.leadid = bio_leads.leadid
LEFT JOIN bio_task ON bio_task.taskid = bio_leadtask.taskid
WHERE bio_leadtask.viewstatus =1
AND bio_leadtask.taskcompletedstatus =1
AND bio_leadtask.taskcompleteddate
BETWEEN '2012-06-01' AND '2012-06-30'
AND bio_leads.enqtypeid =2
AND bio_leadtask.taskid =5 ";

$fscollection_perf_q="SELECT COALESCE(sum( amount ),0) as fscollection_perf
FROM bio_advance
WHERE paydate
BETWEEN '2012-06-01' AND '2012-06-30'
AND head_id=2";


$so_adv_coll_perf_q="SELECT COALESCE(sum( amount ),0) as so_adv_coll_perf
FROM bio_advance
WHERE paydate
BETWEEN '2012-06-01' AND '2012-06-30'
AND head_id=9";

$supply_pymt_coll_perf_q="SELECT COALESCE(sum( amount ),0) as supply_pymt_coll_perf
FROM bio_advance
WHERE paydate
BETWEEN '2012-06-01' AND '2012-06-30'
AND head_id=10";

//update_repo1();
/* $sql_rec_exists="SELECT id from bio_reportda1 where year=2012 and month=06 and enqtype=2 and reporttype=1";
$result_exists= DB_query($sql_rec_exists,$db);
if ($result_exists) {
  update_repo1();
} else {
  insert_repo1();
} */

//function insert_repo1() {
/*$sql1="INSERT INTO bio_reportda1 (reporttype,year,month,enqtype,
              leads_target,mtg_target,fs_target,
              cp_target,so_target,fscollection_target,
              so_adv_coll_target,supply_pymt_coll_target,
              leads_perf,mtg_perf,fs_perf,cp_perf,so_perf,
              fscollection_perf,so_adv_coll_perf,supply_pymt_coll_perf) values
(1,2012,6,2,
($leads_target_q),($mtg_target_q),($fs_target_q),
($cp_target_q),($so_target_q),($fscollection_target_q),
($so_adv_coll_target_q),($supply_pymt_coll_target_q),
($sql_leads_perf_q),($mtg_perf_q),($fs_perf_q),($cp_perf_q),($so_perf_q),
($fscollection_perf_q),($so_adv_coll_perf_q),($supply_pymt_coll_perf_q)
)";
$result1=DB_query($sql1,$db);
if ($result1)   {echo 'Report saved';}
   else {echo 'Error saving report';}
//}*/

//function update_repo1() {
  $sql2="UPDATE bio_reportda1
  SET leads_target=($leads_target_q),leads_perf=($sql_leads_perf_q),mtg_target=($mtg_target_q),mtg_perf=($mtg_perf_q),
      fs_target=($fs_target_q),fs_perf=($fs_perf_q),cp_target=($cp_target_q),cp_perf=($cp_perf_q),
      so_target=($so_target_q),so_perf=($so_perf_q),fscollection_target=($fscollection_target_q),
      fscollection_perf=($fscollection_perf_q),so_adv_coll_target=($so_adv_coll_target_q),
      so_adv_coll_perf=($so_adv_coll_perf_q),
      supply_pymt_coll_target=($supply_pymt_coll_target_q),
      supply_pymt_coll_perf=($supply_pymt_coll_perf_q) WHERE year=2012 and month=06 and enqtype=2 and reporttype=1";
  //echo $sql2;
$result2 = DB_query($sql2,$db);
if ($result2)   {echo 'Report updated';}


?>