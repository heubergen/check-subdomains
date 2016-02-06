<?php
set_time_limit(0);
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Subdomain Scanner</title>
		<!-- Material Design Lite -->
    <script src="https://code.getmdl.io/1.1.1/material.min.js"></script>
    <link rel="stylesheet" href="https://code.getmdl.io/1.1.1/material.indigo-pink.min.css">
    <!-- Material Design icon font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
		<!-- CSS for form -->
		<link rel="stylesheet" href="css/form.css">
		<!-- Responsive Table, thanks to @geoffyuen -->
		<link href="css/responsive_table.css" rel="stylesheet">
	</head>
	<body>
    <div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
			<header class="mdl-layout__header">
			<div class="mdl-layout__header-row">
				<!-- Title -->
				<span class="mdl-layout-title">Title</span>
				<!-- Add spacer, to align navigation to the right -->
				<div class="mdl-layout-spacer"></div>
			</div>
		</header>
		<main class="mdl-layout__content">
	<div class="page-content">
		<?php
function is_ipv4($ip)
{
    return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) ? $ip : '(Null)';
}
if(!file_exists('subdomains.inc'))
{
    echo 'Please upload the list of subdomains as <span style="color: #F00;">subdomains.inc</span>';
    exit();
}
?>
<form action="" method="POST">
	<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
		<input type="text" class="mdl-textfield__input" name="target" id="sample3" value="<?php if(isset($_POST['target'])) {echo htmlentities($_POST['target']);}?>">
		<label class="mdl-textfield__label" for="sample3">URL</label>
		<input type="submit" name="submit" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect own-button" value="Scan" />
	</div>
</form>
<br />
<br />
<?php
if(isset($_POST['target'],$_POST['submit']) && filter_var($_POST['target'],FILTER_VALIDATE_URL))
{
		echo '<table id="subdomains">';
		echo "<caption>Subdomains</caption>";
		echo "<thead>";
		echo "<tr><th>Domain<th>IP";
		echo "<tbody>";
    require('subdomains.inc');
    $targ = parse_url($_POST['target']);
    $target = $targ['host'];
    $target = str_replace("www.","",$target);
    $i = 0;
	foreach($Subdomains as $val)
    {
		//Set up settings for cURL transfer and set URL.
        $url = "http://".$val.".".$target;
        $ch[$i] = curl_init($url);
        curl_setopt($ch[$i], CURLOPT_PORT, 80);
				curl_setopt($ch[$i], CURLOPT_RETURNTRANSFER, true);
        $i++;
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
				curl_multi_exec($mh,$null); //Processes each of the handles in the stack.
			}
			catch(Exception $e) //Catch error
			{
				echo "Could Not Execute"; //Display very informative error text
			}
			for($i=0 ; $i < $numberof ; $i++) //loop as long as the forearch above is true
			{
				if(!curl_error($ch[$i]) && empty(curl_multi_getcontent($ch[$i])))
				{
					echo "<tr>";
					echo "<td>".'http://'.htmlentities($Subdomains[$i].".".$target)."</td>";
					$site = htmlentities($Subdomains[$i].".".$target);
					$ip = is_ipv4(gethostbyname($site));
					echo "<td>"."$ip"."</td>";
					echo "</tr>";
				}
				curl_multi_remove_handle($mh,$ch[$i]); //Close Connection
				curl_close($ch[$i]); //Close Connection
			}
			curl_multi_close($mh); //Closes a set of cURL handles.
		}
elseif (isset($_POST['target']))	{
echo ("URL is invalid. URL must be formatted as: http(s)://example.com (for compatibility reasons)");
}
else {

}
?>
</table>
</div>
</main>
</div>
				</body>
			</html>
