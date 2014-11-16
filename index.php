<?php

if (isset($_GET["s"])) { $server = $_GET["s"]; } else { $server = 0; }
if (isset($_GET["l"])) { $ipLocal = $_GET["l"]; } else { $ipLocal = ""; }
if (isset($_GET["n"])) { $serverName = $_GET["n"]; } else { $serverName = ""; }

if($server == 1)
{
	$ipRemote = "" . $_SERVER['REMOTE_ADDR'];
	$ipRemote = str_replace(".","D", $ipRemote);
	
	$f = file("servers.txt");
	$serverFile = $f[0];
	
	$serverList = explode("#", $serverFile);
	$serverListLength = count($serverList);
	$newList = "";
	$match = false;
	$first = true;
	
	if($serverListLength == 0)
	{
		$newList = $ipRemote . ";" . $ipLocal . ";" . $serverName . ";" . date('i');
	}
	else
	{
		for ($i = 0; $i < $serverListLength; $i++)
		{
			list($data_remote, $data_local, $data_name, $data_time) = explode(";", $serverList[$i]);
			$currentTime = date('i');
			
			if($data_local == $ipLocal && $data_name == $serverName)
			{
				$match = true;
				if($first == false)
				{
					$newList .= "#";
				}
				$first = false;
				
				$newList .= $data_remote . ";" . $data_local . ";" . $data_name . ";" . date('i');
			}
			else if(($data_time + 3 > $currentTime && $data_time - 3 < $currentTime) || ($data_time > 56 && $currentTime < 3))
			{
				if($first == false)
				{
					$newList .= "#";
				}
				$first = false;

				$newList .= $data_remote . ";" . $data_local . ";" . $data_name . ";" . $data_time;
			}
		}
		
		if($match == false)
		{
			if($first == false)
			{
				$newList .= "#";
			}
			$newList .= $ipRemote . ";" . $ipLocal . ";" . $serverName . ";" . date('i');
		}
	}
	
	$overwritefile = fopen("servers.txt", "w");
	fwrite($overwritefile, $newList);
}
else
{
	$f = file("servers.txt");
	$serverFile = $f[0];
	$serverList = explode("#", $serverFile);
	$serverListLength = count($serverList);
	$newList = "";
	$first = true;
	
	for ($i = 0; $i < $serverListLength; $i++)
	{
		list($data_remote, $data_local, $data_name, $data_time) = explode(";", $serverList[$i]);
		$currentTime = date('i');
		
		if(($data_time + 3 > $currentTime && $data_time - 3 < $currentTime) || ($data_time > 56 && $currentTime < 3))
		{
			if($first == false)
			{
				$newList .= "#";
			}
			$first = false;

			$newList .= $data_remote . ";" . $data_local . ";" . $data_name . ";" . $data_time;
		}
	}
	
	$overwritefile = fopen("servers.txt", "w");
	fwrite($overwritefile, $newList);
	echo $newList;
}

?>
