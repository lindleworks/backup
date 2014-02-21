<?php
function ReturnNextRun($Record)
{
	$timestamp = time();
	//get the last run time
	if ($Record->JobId != "")
	{
		$JobLogFactory = new JobLogFactory();
		$JobLogArray = $JobLogFactory->GetAll(" where JobId = " . $Record->JobId . " order by DateRan desc ");
		$i = 0;
		foreach ($JobLogArray as &$JobLog)
		{
			$i++;
			if ($i == 1)
			{
				$lastRun = strtotime($JobLog->DateRan);
			}
		}
	}
	if (!isset($lastRun))
	{
		$lastRun = $timestamp; //$Record->DateCreated;
	}
	if ($Record->ScheduleInterval == "Daily")
	{
		$nextTime = strtotime(gmdate("m",$timestamp) . "/" . gmdate("d",$timestamp) . "/" . gmdate("Y",$timestamp) . " " . $Record->ScheduleHour . ":" . $Record->ScheduleMinute . " GMT");
		//echo "nextTime: " . gmdate("m-d-Y h:i:s a",$nextTime) . "\n";
		//echo "currentTime: " . gmdate("m-d-Y h:i:s a",$timestamp) . "\n";
		if ($nextTime < $timestamp)
		{
			//echo "nextTime is less that currentTime\n";
			$nextTime = $nextTime + 86400;
			//echo "nextTime: " . gmdate("m-d-Y h:i:s a",$nextTime) . "\n";
			if (isset($lastRun))
			{
				//echo "lastRun is set!\n";
				$previousTime = $nextTime - 86400;
				if ($previousTime > $lastRun)
				{
					//echo "previousTime is greater that lastTime\n";
					//echo "previousTime: " . gmdate("m-d-Y h:i:s a",$previousTime) . "\n";
					$nextTime = $previousTime;
					//echo "new nextTime: " . gmdate("m-d-Y h:i:s a",$nextTime) . "\n";
				}
			}
		}
	}
	else if ($Record->ScheduleInterval == "Hourly")
	{
		$nextTime = strtotime(gmdate("m",$timestamp) . "/" . gmdate("d",$timestamp) . "/" . gmdate("Y",$timestamp) . " " . gmdate("H",$timestamp) . ":" . $Record->ScheduleMinute . " GMT");
		if ($nextTime < $timestamp)
		{
			$nextTime = $nextTime + 3600;
			if (isset($lastRun))
			{
				$previousTime = $nextTime - 3600;
				if ($previousTime > $lastRun)
				{
					$nextTime = $previousTime;
				}
			}
		}
	}
	else if ($Record->ScheduleInterval == "Weekly")
	{
		$nextWeekTime = strtotime("next " . $Record->ScheduleDayOfWeek);
		$nextTime = strtotime(gmdate("m",$nextWeekTime) . "/" . gmdate("d",$nextWeekTime) . "/" . gmdate("Y",$nextWeekTime) . " " . $Record->ScheduleHour . ":" . $Record->ScheduleMinute . " GMT");
	}
	else if ($Record->ScheduleInterval == "Monthly")
	{
		$nextTime = strtotime(gmdate("m",$timestamp) . "/" . $Record->ScheduleDayOfMonth . "/" . gmdate("Y",$timestamp) . " " . $Record->ScheduleHour . ":" . $Record->ScheduleMinute . " GMT");
		//echo "nextTime: " . gmdate("m-d-Y h:i:s a",$nextTime) . "<br>";
		//echo "currentTime: " . gmdate("m-d-Y h:i:s a",$timestamp) . "<br>";
		if ($nextTime < $timestamp)
		{
			//echo "nextTime is less that currentTime<br>";
			$nextMonthTime = strtotime("next month");
			$nextTime = strtotime(gmdate("m",$nextMonthTime) . "/" . $Record->ScheduleDayOfMonth . "/" . gmdate("Y",$nextMonthTime) . " " . $Record->ScheduleHour . ":" . $Record->ScheduleMinute . " GMT");
			//echo "nextTime: " . gmdate("m-d-Y h:i:s a",$nextTime) . "<br>";
			if (isset($lastRun))
			{
				//echo "lastRun is set!<br>";
				$lastMonthTime = strtotime("last month");
				$previousTime = strtotime(gmdate("m",$lastMonthTime) . "/" . $Record->ScheduleDayOfMonth . "/" . gmdate("Y",$lastMonthTime) . " " . $Record->ScheduleHour . ":" . $Record->ScheduleMinute . " GMT");
				if ($previousTime > $lastRun)
				{
					//echo "previousTime is greater that lastTime<br>";
					//echo "previousTime: " . gmdate("m-d-Y h:i:s a",$previousTime) . "<br>";
					$nextTime = $previousTime;
					//echo "new nextTime: " . gmdate("m-d-Y h:i:s a",$nextTime) . "<br>";
				}
			}
		}
	}
	return $nextTime;
}
?>