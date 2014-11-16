<?php

if (isset($_GET["s"])) { $server = $_GET["s"]; } else { $server = 0; }
if (isset($_GET["l"])) { $ipLocal = $_GET["l"]; } else { $ipLocal = ""; }
if (isset($_GET["n"])) { $serverName = $_GET["n"]; } else { $serverName = ""; }

if($server == 1)
{
	$ipRemote = "" . $_SERVER['REMOTE_ADDR'];
	$ipRemote = str_replace(".","D", $ipRemote);
	
	//load old list
	$f = file("servers.txt");
	$serverFile = $f[0];
	
	//create serverlist array
	$serverList = explode("#", $serverFile);
	$serverListLength = count($serverList);
	$newList = "";
	$match = false;
	$first = true;
	
	//add if list is empty
	if($serverListLength == 0)
	{
		$newList = $ipRemote . ";" . $ipLocal . ";" . $serverName . ";" . date('i');
	}
	else
	{
		//loop through all servers
		for ($i = 0; $i < $serverListLength; $i++)
		{
			list($data_remote, $data_local, $data_name, $data_time) = explode(";", $serverList[$i]);
			$currentTime = date('i');
			
			//if server is already on list
			if($data_local == $ipLocal && $data_name == $serverName)
			{
				$match = true;
				if($first == false)
				{
					$newList .= "#";
				}
				$first = false;
				
				//save with new lastupdate time
				$newList .= $data_remote . ";" . $data_local . ";" . $data_name . ";" . date('i');
			}
			//if server is younger then 3 minutes
			else if(($data_time + 3 > $currentTime && $data_time - 3 < $currentTime) || ($data_time > 56 && $currentTime < 3))
			{
				if($first == false)
				{
					$newList .= "#";
				}
				$first = false;

				//save on new list
				$newList .= $data_remote . ";" . $data_local . ";" . $data_name . ";" . $data_time;
			}
		}
		
		//if server doesn't exist on list
		if($match == false)
		{
			if($first == false)
			{
				$newList .= "#";
			}
			
			//add new server
			$newList .= $ipRemote . ";" . $ipLocal . ";" . $serverName . ";" . date('i');
		}
	}
	
	//save new list
	$overwritefile = fopen("servers.txt", "w");
	fwrite($overwritefile, $newList);
}
else
{
	//get old serverlist
	$f = file("servers.txt");
	$serverFile = $f[0];
	$serverList = explode("#", $serverFile);
	$serverListLength = count($serverList);
	$newList = "";
	$first = true;
	
	//loop through all servers
	for ($i = 0; $i < $serverListLength; $i++)
	{
		list($data_remote, $data_local, $data_name, $data_time) = explode(";", $serverList[$i]);
		$currentTime = date('i');
		
		//if server is younger than 3 minutes
		if(($data_time + 3 > $currentTime && $data_time - 3 < $currentTime) || ($data_time > 56 && $currentTime < 3))
		{
			if($first == false)
			{
				$newList .= "#";
			}
			$first = false;

			//add server to new list
			$newList .= $data_remote . ";" . $data_local . ";" . $data_name . ";" . $data_time;
		}
	}
	
	//save new list
	$overwritefile = fopen("servers.txt", "w");
	fwrite($overwritefile, $newList);
	
	//echo server list
	echo $newList;
}

?>
