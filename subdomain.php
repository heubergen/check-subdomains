<?php
set_time_limit(0);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Subdomain Scanner</title>
	</head>

	<body>
		<?php
if(!file_exists('subdomains.inc'))
{
    echo 'Please upload the list of subdomains as <span style="color: #F00;">subdomains.inc</span>';
    exit();
}
?>
		<form action="" method="POST">
Enter URL : <input type="text" class="Input" name="target" value="<?php if(isset($_POST['target']))
{echo htmlentities($_POST['target']);}?>" placeholder="http://example.com" size="50" />
					<input type="submit" name="submit" class="Button" value="Scan" />
				</form>
				<br />
				<br />
				<?php
if(isset($_POST['target'],$_POST['submit']) && filter_var($_POST['target'],FILTER_VALIDATE_URL))
{
	//extract only usefull part of input from user
    require('subdomains.inc');
    $targ = parse_url($_POST['target']);
    $target = $targ['host'];
    $target = str_replace("www.","",$target);
    $i = 0; // i = 0
    foreach($Subdomains as $val)
    {
		//Set up settings for cURL transfer and set URL.
        $url = "http://".$val.".".$target;
        $ch[$i] = curl_init($url); //Initialize a cURL session
        curl_setopt($ch[$i], CURLOPT_PORT, 80); //Set an option for a cURL transfer
		curl_setopt($ch[$i], CURLOPT_RETURNTRANSFER, true);//Set an option for a cURL transfer
        $i++; //i = 1
    }
	$numberof = $i; //numberof = 1
	$mh = curl_multi_init(); //Allows the processing of multiple cURL handles asynchronously.
	for($i=0 ; $i < $numberof ; $i++) //loop as long as the forearch above is true
	{
		curl_multi_add_handle($mh,$ch[$i]); //Add a normal handel to a multi handle
	}
	$null = NULL;
	try 
	{
		curl_multi_exec($mh,$null);
	} 
	catch(Exception $e) 
	{
		echo "Could Not Execute";
	}
	for($i=0 ; $i < $numberof ; $i++) //loop as long as the forearch above is true
	{
		if(!curl_error($ch[$i]) && !strstr(curl_multi_getcontent($ch[$i]))) 
		{
			echo '<span style="color: #F00;"> http://'.htmlentities($Subdomains[$i].".".$target).'</span> exists<br />';
		}
		curl_multi_remove_handle($mh,$ch[$i]);
		curl_close($ch[$i]);
	}
	curl_multi_close($mh); //Closes a set of cURL handles.
}
?>
			</body>
		</html>
		