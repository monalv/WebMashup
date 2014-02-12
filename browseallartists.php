<?php
	session_start();
	$con=mysql_connect('localhost','root','123');
	if(!$con){
		die(mysql_err());
	}
	else
	{
		$alphabet='A';
		if(isset($_GET['alphabet']))
		{
			$alphabet=$_GET['alphabet'];
		}
		$sql="select name,artistid from artist where name like '".$alphabet."%' order by name";
		mysql_select_db('artangled',$con);
		$res=mysql_query($sql);
		$artistcount=mysql_num_rows($res);
		
?>
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Artangled</title>
<link href="http://fonts.googleapis.com/css?family=Rye" rel="stylesheet" type="text/css">
<link href="http://fonts.googleapis.com/css?family=Buenard" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="css/screen.css" type="text/css" media="screen" />
<link rel="shortcut icon" type="image/ico" href="images/favicon.ico" />	
<link href='http://fonts.googleapis.com/css?family=Lora' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="css/style.css">
<script type="application/javascript">
function getUserProfile(userId)
{
	document.getElementById("artistid").value=userId;
	document.form1.action="artist.php";
	document.form1.submit();
	return false;
}
function getUsersList(alphabet)
{
	document.getElementById("alphabet").value=alphabet;
	document.form1.action="browseallartists.php";
	document.form1.submit();
	return false;
}
</script>
<style type="text/css">
.outercontainer {
	width: 100%;
	height:inherit;
}
.headingcontainer {
	width: 90%;
	margin:5px;
	border-radius:20px;
	background-color:#666666;
}

.headingtext{
	font-family: "Rye", cursive;
	color:white;
	font-size:20px;
	font-weight:600;
	text-align:center;
}
.bodycontainer {
	width: 90%;
	border-radius:20px;
	background-color:#666666;
	margin:5px;
}
#linkscontainer
{
	padding:5%;
	width:30%;
	display:inline-block;
	font-family: Lora, serif;
	vertical-align:top;
}
#linkscontainer ul li
{
	padding: .2em 1em;
}
</style>
</head>

<body style="background-image:url(../images/bg-checker.png)">
<form id="form1" name="form1" method="get">
<div class="outercontainer" align="center">
	<div id="logo"><a href="artist.php">
	  <img src="images/Artangled.png" class="displayed"></a>
	</div>
	<div class="headingcontainer">
	  <p class="headingtext">
      	Browse all artists by name A-Z
	  </p>
	</div>
  <div class="headingcontainer">
    <p style="color:white">
      	<a href="#" onclick="getUsersList('A')">A</a>&nbsp;
        <a href="#" onclick="getUsersList('B')">B</a>&nbsp;
        <a href="#" onclick="getUsersList('C')">C</a>&nbsp;
        <a href="#" onclick="getUsersList('D')">D</a>&nbsp;
        <a href="#" onclick="getUsersList('E')">E</a>&nbsp;
        <a href="#" onclick="getUsersList('F')">F</a>&nbsp;
        <a href="#" onclick="getUsersList('G')">G</a>&nbsp;
        <a href="#" onclick="getUsersList('H')">H</a>&nbsp;
        <a href="#" onclick="getUsersList('I')">I</a>&nbsp;
        <a href="#" onclick="getUsersList('J')">J</a>&nbsp;
        <a href="#" onclick="getUsersList('K')">K</a>&nbsp;
        <a href="#" onclick="getUsersList('L')">L</a>&nbsp;
        <a href="#" onclick="getUsersList('M')">M</a>&nbsp;
        <a href="#" onclick="getUsersList('N')">N</a>&nbsp;
        <a href="#" onclick="getUsersList('O')">O</a>&nbsp;
        <a href="#" onclick="getUsersList('P')">P</a>&nbsp;
        <a href="#" onclick="getUsersList('Q')">Q</a>&nbsp;
        <a href="#" onclick="getUsersList('R')">R</a>&nbsp;
        <a href="#" onclick="getUsersList('S')">S</a>&nbsp;
        <a href="#" onclick="getUsersList('T')">T</a>&nbsp;
        <a href="#" onclick="getUsersList('U')">U</a>&nbsp;
        <a href="#" onclick="getUsersList('V')">V</a>&nbsp;
        <a href="#" onclick="getUsersList('W')">W</a>&nbsp;
        <a href="#" onclick="getUsersList('X')">X</a>&nbsp;
        <a href="#" onclick="getUsersList('Y')">Y</a>&nbsp;
        <a href="#" onclick="getUsersList('Z')">Z</a>
    </p>
	</div>
	<div class="bodycontainer" align="left">
    	<div id="linkscontainer" align="left">
        	<ul>
            <?php 
				$counter=0;
				while($counter<($artistcount/2) && $row=(mysql_fetch_assoc($res)))
				{
					echo('<li><a href="#" onclick="getUserProfile('.$row['artistid'].')">'.$row['name'].'</a></li>');
					$counter++;
				}
				mysql_data_seek($res,$counter);
			?>
            </ul>
	    </div>
        <div id="linkscontainer" align="left">
        	<ul>
            <?php
				while($row=(mysql_fetch_assoc($res)))
				{
					echo('<li><a href="#" onclick="getUserProfile('.$row['artistid'].')">'.$row['name'].'</a></li>');
				}
			?>
            </ul>
	    </div>
	</div>
</div>
<input id="artistid" name="artistid" type="hidden" value="" />
<input id="alphabet" name="alphabet" type="hidden" value="" />
</form>
</body>
</html>

	<?php	
		mysql_close($con);
	}
?>