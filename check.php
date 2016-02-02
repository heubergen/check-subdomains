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
$test = $_POST;
var_dump($test);
}
?>
