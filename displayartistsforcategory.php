<?php
	session_start();
	$artistid="";
	$category1="";
	$category2="";
	$category1value="";
	$category2value="";
	if(isset($_GET['artistid']))
	{
		$artistid=$_GET['artistid'];
	}
	if(isset($_GET['category1']))
	{
		$category1=$_GET['category1'];
	}
	if(isset($_GET['category2']))
	{
		$category2=$_GET['category2'];
	}
	if(isset($_GET['category1value']))
	{
		$category1value=$_GET['category1value'];
	}
	if(isset($_GET['category2value']))
	{
		$category2value=$_GET['category2value'];
	}

	
	$con=mysql_connect('localhost','root','123');
	if(!$con){
		die(mysql_err());
	}
	else
	{
		mysql_select_db('artangled',$con);
		if($category1=="Student of")
		{
			$sql="SELECT artistid,artist2,name,image from relationship,artist where artist1=artistid and relationid=100 and artist2=".$artistid;
			$sql1="SELECT name from artist where artistid=".$artistid;
			$res1=mysql_query($sql1);
			$row1=mysql_fetch_assoc($res1);
			$name=$row1['name'];
		}
		else if($category1=="Assistant of")
		{
			$sql="SELECT artistid,artist2,name,image from relationship,artist where artist1=artistid and   relationid=101 and artist2=".$artistid;
			$sql1="SELECT name from artist where artistid=".$artistid;
			$res1=mysql_query($sql1);
			$row1=mysql_fetch_assoc($res1);
			$name=$row1['name'];
		}
		else if($category1=="gender")
		{
			$sql="select artistid,image,name from artist where gender='female' and era='".$category2value."'";
		}
		else
		{
			$sql="select artistid,image,name from artist where ".$category1."='".$category1value."'";
		}
		$res=mysql_query($sql);
		?>
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml">
<head lang="en">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Artangled</title>
<link href="http://fonts.googleapis.com/css?family=Rye" rel="stylesheet" type="text/css">
<link href="http://fonts.googleapis.com/css?family=Buenard" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="css/screen.css" type="text/css" media="screen" />
<link rel="shortcut icon" type="image/ico" href="images/favicon.ico" />	
<link rel="stylesheet" href="css/style.css">
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
	padding-bottom:2%;
}
.artistcontainer
{
	display:inline-block;
	width: 15%;
	margin-left:auto;
	border-radius:20px;
	margin:5px;
	background-color:#666666;
	height:180px;

}
.imagecontainer
{
	display:inline-block;
	width: 90%;
	height:80%;
	margin-left:auto;
	border-radius:20px;
	margin-top:1%;
	cursor:hand;
	cursor:pointer;
}
.BoxImage
{
	border-radius: 20px;
    -moz-border-radius: 20px;
    -khtml-border-radius: 20px;
    -webkit-border-radius: 20px;
}
.namecontainer
{
	font-family: "Buenard", serif;
	color:white;
	margin-top:1%;
	margin-bottom:1%;
}
</style>
<script type="application/javascript">
function getUserProfile(userId)
{
	document.getElementById("artistid").value=userId;
	document.form1.action="artist.php";
	document.form1.submit();
	return false;
}
</script>
</head>

<body style="background-image:url(../images/bg-checker.png)">
<form id="form1" name="form1" method="get">
<div class="outercontainer" align="center">
<div id="logo"><a href="artist.php">
  <img src="images/Artangled.png" class="displayed"></a>
</div>
<div class="headingcontainer">
  <p class="headingtext">
  	<?php
		if($category1=="Student of")
		{
			echo("Students of ".$name);
		}
		else if($category1=="Assistant of")
		{
			echo("Assistants of ".$name);
		}
		else if($category1=="gender")
		{
			echo("Women artists from ".$category2value);
		}
		else
		{ 
  			echo("Artists with ". $category1." : ".$category1value);
		}
	?> 
  </p>
</div>
<div class="bodycontainer">
<?php
		
		while($row=mysql_fetch_assoc($res))
		{
			?>
			<div class="artistcontainer">
    		<div class="imagecontainer">
			<img class="BoxImage" width="100%" height="100%" src="
            <?php
			if($row['image']!="")
			{
				echo($row['image']);
			}
			else
			{
				echo('/images/image-not-available.png');
			}?>
			" 
            <?php 
			echo('onclick="getUserProfile('.$row['artistid'].');"');
			?>
            />
            
			</div>
        	<div class="namecontainer">
			<?php echo($row['name']); ?>
			</div>
    		</div>
            <?php
		}
		mysql_close($con);
	}?>

</div>
</div>
<input id="artistid" name="artistid" type="hidden" value="" />
</form>
</body>
</html>
