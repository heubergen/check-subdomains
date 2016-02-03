<?php
set_time_limit(0);
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Subdomain Scanner</title>
	</head>
	<body>
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
Enter URL : <input type="text" class="Input" name="target" value="<?php if(isset($_POST['target']))
{echo htmlentities($_POST['target']);}?>" placeholder="http://example.com" size="50" />
					<input type="submit" name="submit" class="Button" value="Scan" />
<br />
<br />
<?php
if(isset($_POST['target'],$_POST['submit']) && filter_var($_POST['target'],FILTER_VALIDATE_URL))
{
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
else {
echo ("URL is invalid. URL must be formatted as: http(s)://example.com (for compatibility reasons)");
}
?>
				</body>
			</html>