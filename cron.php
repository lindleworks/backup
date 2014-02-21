<?php
require_once(dirname((__FILE__)) . '/_core/classes/core.php');
//attempt to run specific job
if ($JobId != "" && $JobId > 0)
{
	RunJob($JobId);
}
else
{
	$LockFactory = new LockFactory();
	$LockArray = $LockFactory->GetAll("");
	if (count($LockArray) == 0)
	{
		$Lock = new Lock();
		$Lock->Locked = 1;
		$LockFactory->Save($Lock);
		$JobFactory = new JobFactory();
		$JobArray = $JobFactory->GetAll(" where ScheduleInterval != 'Manual' and NextRunDate <= datetime('now') order by NextRunDate ");
		foreach ($JobArray as &$Job)
		{
			RunJob($Job->JobId);
		}
		$LockArray = $LockFactory->GetAll("");
		foreach ($LockArray as &$Lock)
		{
			$LockFactory->Delete($Lock);
		}
	}
}

?>