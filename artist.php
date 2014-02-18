<?php
	session_start();
	$userid=4360;
	#$userid=6966;
	#$userid=1334;
	if(isset($_GET['artistid'])){
		$userid=$_GET['artistid'];
	}
	$con=mysql_connect('localhost','root','123');
	if(!$con){
		die(mysql_err());
	}
	mysql_select_db('artangled',$con);
	$sql="select * from artist where artistid=".$userid;
	$res=mysql_query($sql);
	$row1=mysql_fetch_assoc($res);
	
	//Query the artworks
	$sql="SELECT artId,title,medium,artsize,artyear,artlink from artwork where artistid=".$userid." LIMIT 200";
	$resartworks=mysql_query($sql);
	
	
	//Query to fetch direct relations. Each relation goes in a box
	$sql="SELECT artist1,artist2,relationship,name,image from relationship,relationlist,artist where artist2=artistid and relationid=id and  artist1=".$userid;
	$resrelations=mysql_query($sql);
	//-----------------------------------------------------counts declaration block-----------------------------------------
	$relationscount=mysql_num_rows($resrelations);
	$boxcount=$relationscount;
	$studentcount=0;
	$movementcount=0;
	$subjectcount=0;
	$mediumcount=0;
	$nationalitycount=0;
	$gendercount=0;
	$assistantcount=0;
	//-----------------------------------------------------counts declaration block-----------------------------------------
	
	$isstudentblock=1;	//stores if students are shown as a group out individual entries
	if($boxcount<8)
	{
		//Query to fetch students of this artist
		$sql="SELECT artist1,artist2,name,image from relationship,artist where artist1=artistid and relationid=100 and artist2=".$userid;
		$resstudents=mysql_query($sql);
		$studentcount=mysql_num_rows($resstudents);
		if($boxcount+$studentc0ount<=3 || $studentcount==1) {
			$isstudentblock=0;
			$boxcount+=$studentcount;
		}
		else if($studentcount>0) {
			$boxcount+=1;
		}
	}
		//Check assistants of
	if($boxcount<8)
	{
		$sql="SELECT artist1,artist2,name,image from relationship,artist where artist1=artistid and relationid=101 and artist2=".$userid;
		$resassistants=mysql_query($sql);
		$assistantcount=mysql_num_rows($resassistants);
		$boxcount+=1;
	}
	if($boxcount<8)
	{
		$sql="SELECT movement,subject,medium,nationality,gender,era from artist where artistid=".$userid;
		$resothers=mysql_query($sql);
		$rowothers=mysql_fetch_assoc($resothers);
	}
	if($rowothers['movement']!="")
	{
		$sql="SELECT count(*) as cnt from artist where movement='".$rowothers['movement']."'";
		$resmovements=mysql_query($sql);
		$rowmovements=mysql_fetch_assoc($resmovements);
		$movementcount=$rowmovements['cnt'];
		$boxcount+=1;
	}
	//start from here and go ahead with subject as we did in Movement
	if($boxcount<8)
	{
		if($rowothers['subject']!="")
		{
			$sql="SELECT count(*) as cnt from artist where subject='".$rowothers['subject']."'";
			$ressubjects=mysql_query($sql);
			$rowsubjects=mysql_fetch_assoc($ressubjects);
			$subjectcount=$rowsubjects['cnt'];
			$boxcount+=1;
		}
	}
	if($boxcount<8)
	{
		if($rowothers['medium']!="")
		{
			$sql="SELECT count(*) as cnt from artist where medium='".$rowothers['medium']."'";
			$resmediums=mysql_query($sql);
			$rowmediums=mysql_fetch_assoc($resmediums);
			$mediumcount=$rowmediums['cnt'];
			$boxcount+=1;
		}
	}
	if($boxcount<8)
	{
		if($rowothers['nationality']!="")
		{
			$sql="SELECT count(*) as cnt from artist where nationality='".$rowothers['nationality']."'";
			$resnationalitys=mysql_query($sql);
			$rownationalitys=mysql_fetch_assoc($resnationalitys);
			$nationalitycount=$rownationalitys['cnt'];
			$boxcount+=1;
		}
	}
	if($boxcount<8)
	{
		if($rowothers['gender']!="")
		{
			$sql="SELECT count(*) as cnt from artist where gender='".$rowothers['gender']."' and era='".$rowothers['era']."'";
			$resgenders=mysql_query($sql);
			$rowgenders=mysql_fetch_assoc($resgenders);
			$gendercount=$rowgenders['cnt'];
			$boxcount+=1;
		}
	}	
	//If there are still enough boxes to accomodate students individually
	if($isstudentblock==1 && ($boxcount+$studentcount-1)<=8)
	{
		$isstudentblock=0;
		$boxcount+=$studentcount-1;
	}

	mysql_close($con);
	
	
	
?>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Artangled</title>
<meta name="description" content="">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<link rel="stylesheet" href="css/style.css">
<link rel="shortcut icon" type="image/ico" href="images/favicon.ico" />	
<!--------------------Headers for slider----------------------->
	<link rel="stylesheet" href="css/screen.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="css/lightbox.css" type="text/css" media="screen" />

  <link href='http://fonts.googleapis.com/css?family=Fredoka+One|Open+Sans:400,700' rel='stylesheet' type='text/css'>
  <link href="http://fonts.googleapis.com/css?family=Rye" rel="stylesheet" type="text/css">
  <!-------------------------Google Font----------------------------------------->
  <link href='http://fonts.googleapis.com/css?family=Lora' rel='stylesheet' type='text/css'>
  <link href='http://fonts.googleapis.com/css?family=Days+One' rel='stylesheet' type='text/css'>
  
  <script src="http://code.jquery.com/jquery-latest.min.js"></script>
  <script src="js/jquery-1.7.2.min.js"></script>
<script src="js/jquery-ui-1.8.18.custom.min.js"></script>
<script src="js/jquery.smooth-scroll.min.js"></script>
<script src="js/lightbox.js"></script>

<script>
  jQuery(document).ready(function($) {
      $('a').smoothScroll({
        speed: 1000,
        easing: 'easeInOutCubic'
      });

      $('.showOlderChanges').on('click', function(e){
        $('.changelog .old').slideDown('slow');
        $(this).fadeOut();
        e.preventDefault();
      })
  });

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-2196019-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

	function getUserProfile(artistid)
	{
		document.getElementById("artistid").value=artistid;
		document.form1.action="artist.php";
		document.form1.submit();
		return false;
	}
	function gotoCategory(artistid,category1,category2,category1value,category2value)
	{
		document.getElementById("artistid").value=artistid;
		document.getElementById("category1").value=category1;
		document.getElementById("category2").value=category2;
		document.getElementById("category1value").value=category1value;
		document.getElementById("category2value").value=category2value;

		document.form1.action="displayartistsforcategory.php";
		document.form1.submit();
		return false;
	}
	
	
</script>
<!--------------------Headers for slider----------------------->
<!-- IE6-8 support of HTML5 elements --> <!--[if lt IE 9]>
<![endif]-->
</head>
<body>
<form id="form1" name="form1" method="get">
<div id="wrapper">
	<header>
        <div id="logo"><a href="artist.php">
        <img src="images/Artangled.png" class="displayed"></a>
        </div>
        <div id="artistname">
        	<?php echo($row1['name']); ?>
        </div>
		<div style="display:inline-block;width:30%;font-family: 'Days One', sans-serif; font-size:18px" align="right">
        	<span style="background-color:#666666;padding:10px;cursor:pointer;cursor:hand;border-radius:15px"><a href="browseallartists.php">Browse all artists</a></span>
        </div>
        <div id="topcontainer">
        
            <div id="dpcontainer">
            	<img id="dpimage" width="100%" height="100%" src="
				<?php 
					if($row1['image']!="")
					{
						echo($row1['image']); 
					}
					else
					{
						echo("/images/image-not-available.png");
					}
				?>
                " />
            </div>
            <div id="desccontainer">
            <div id="descsubcontainer">
				<?php
					if($row1['abstract']!="")
					{

						echo($row1['abstract']); 
					}
					else
					{
						echo("Description about ".$row1['name']." is not available.");
					}
				 ?>
                 </div>
             </div>	
            <div id="detailscontainer">
                <ul><br />
                    <?php 
						if($row1['nationality']!="")
						{
							echo("<li> Nationality	: ".$row1['nationality']."</li><BR/>");
						}
						if($row1['born']!="")
						{
							echo("<li> Birth Year	: ".$row1['born']."</li><BR/>");
						}
						if($row1['died']!="")
						{
							echo("<li> Death Year	: ".$row1['died']."</li><BR/>");
						}
						if($row1['gender']!="")
						{
							echo("<li> Gender	: ".$row1['gender']."</li><BR/>");
						}
						if($row1['movement']!="")
						{
							echo("<li> Movement	: ".$row1['movement']."</li><BR/>");
						}
						if($row1['subject']!="")
						{
							echo("<li> Subject of Art	: ".$row1['subject']."</li><BR/>");
						}
						if($row1['medium']!="")
						{
							echo("<li> Medium of Art	: ".$row1['medium']."</li><BR/>");
						}
					?>
                </ul>
            </div>
        </div>
		<div id="artworktitle">
        	Artworks of <?php echo($row1['name']);?>
        </div>
        <div id="slidercontainer">
            <div style="max-height:170px;overflow:auto;">
				<div class="imageRow">
  					<div class="set">
                    <?php 
						$artworkcount=mysql_num_rows($resartworks);
						if($rowartwork=mysql_fetch_assoc($resartworks))
						{
							echo('<div class="single first">');
							echo('<a href="http://www.artnet.com'.$rowartwork['artlink'].'" rel="lightbox[artworks]" ');
							echo('title="');
							if($rowartwork['title']!="")
							{
								echo('Title:'.$rowartwork['title'].' ');
							}
							if($rowartwork['medium']!="")
							{
								echo('Medium:'.$rowartwork['medium'].' ');
							}
							if($rowartwork['artsize']!="")
							{
								echo('Size:'.$rowartwork['artsize'].' ');
							}
							if($rowartwork['artyear']!="")
							{
								echo('Year:'.$rowartwork['artyear'].' ');
							}
							echo('"><img src="http://www.artnet.com'.$rowartwork['artlink'].'" alt="Artworks: image 1 of '.$artworkcount.'" /></a>');
							echo('</div>');
						}
						$displayedartcount=1;
						if($artworkcount>1)
						{
							mysql_data_seek($resartworks,1);
							while($rowartwork=mysql_fetch_assoc($resartworks))
							{
								$displayedartcount++;
								echo('<div class="single">');
								echo('<a href="http://www.artnet.com'.$rowartwork['artlink'].'" rel="lightbox[artworks]" ');
								echo('title="');
								if($rowartwork['title']!="")
								{
									echo('Title:'.$rowartwork['title'].' ');
								}
								if($rowartwork['medium']!="")
								{
									echo('Medium:'.$rowartwork['medium'].' ');
								}
								if($rowartwork['artsize']!="")
								{
									echo('Size:'.$rowartwork['artsize'].' ');
								}
								if($rowartwork['artyear']!="")
								{
									echo('Year:'.$rowartwork['artyear'].' ');
								}
								echo('"><img src="http://www.artnet.com'.$rowartwork['artlink'].'" alt="Artworks: image '.$displayedartcount.' of '.$artworkcount.'" /></a>');
								echo('</div>');
							}
						}
					?>
			    	</div>
  				</div>
		 	</div>
		</div>
        </div>
		<div id="artworktitle" align="center">
        	Relationship network of <?php echo($row1['name']);?></div>
        </div>
        <br />
        <div id="BoxContainer" align="center">
        <?php 
			$filledboxcount=0;
			$temprelationscount=$relationscount;
			$tempstudentcount=0;
			while($filledboxcount<4 && $temprelationscount>0)
			{	
				mysql_data_seek($resrelations,($temprelationscount-1));
				$rowrelations=mysql_fetch_assoc($resrelations);
				echo('<div class="outerBox" > <div class="Box" id="box'.($filledboxcount+1).'"><img class="BoxImage" src=\'');
				if($rowrelations['image']!="")
				{
					echo($rowrelations['image']); 
				}

				else
				{
					echo("/images/image-not-available.png");
				}
				echo('\' width=100% height="100%" onclick="getUserProfile('.$rowrelations['artist2'].');"/>');
				echo('</div><div class="boxText">'.$rowrelations['relationship'].'</br>'.$rowrelations['name'].'</div></div>');
				$temprelationscount--;
				$filledboxcount++;
			}
			if($filledboxcount<4)
			{
				
				//Display students individually
				if($isstudentblock==0)
				{
					$tempstudentcount=$studentcount;
					while($filledboxcount<4 && $tempstudentcount>0)
					{
						mysql_data_seek($resstudents,($tempstudentcount-1));
						$rowstudents=mysql_fetch_assoc($resstudents);
						echo('<div class="outerBox" > <div class="Box" id="box'.($filledboxcount+1).'"><img class="BoxImage" src=\'');
						if($rowstudents['image']!="")
						{
							echo($rowstudents['image']); 
						}
						else
						{
							echo("/images/image-not-available.png");
						}
						echo('\' width=100% height="100%" onclick="getUserProfile('.$rowstudents['artist1'].');"/>');
						echo('</div><div class="boxText"> Teacher of</br>'.$rowstudents['name'].'</div></div>');
						$tempstudentcount--;
						$filledboxcount++;
					}
				}
				//Display students in 1 block
				else
				{
					$tempstudentcount=1;
					
					echo('<div class="outerBox" > <div class="Box" style="position: relative;" id="box'.($filledboxcount+1).'"');
					echo('onclick=\'gotoCategory('.$userid.',"Student of","","",""); \' >');
					/*echo('<div class="Subbox">');
					echo('<span class="Boxtextheader">Students of</span> <span class="Boxtextcontent">'.$row1['name'].'</span>');
					echo('<span class="Boxtextcount">'.$studentcount.' artists</span>');
					echo('</div></div>'); */
					echo('<img src="/images/box-background.png" class="BoxImage" width="100%" height="100%" alt="Image not found"  style="z-index: -1"/>');
					echo('<div style="position:absolute;left:15%;top:15%;"><span class="Boxtextheader">Students of</span> <span class="Boxtextcontent">'.$row1['name'].'</span><span class="Boxtextcount">'.$studentcount.' artists</span></div><div class="boxText"><br/></div></div>');
						
					/*echo('</div></div>');*/
					echo('</div>');
					
					$tempstudentcount--;
					$filledboxcount++;
				}	
			}
			if($filledboxcount<4 && $assistantcount==1)
			{
				$rowassistants=mysql_fetch_assoc($resassistants);
				echo('<div class="outerBox" > <div class="Box" id="box'.($filledboxcount+1).'"><img class="BoxImage" src=\'');
				if($rowassistants['image']!="")
				{
					echo($rowassistants['image']); 
				}
				else
				{
					echo("/images/image-not-available.png");
				}
				echo('\' width=100% height="100%" onclick="getUserProfile('.$rowassistants['artist1'].');"/>');
				echo('</div><div class="boxText"> Mentor of</br>'.$rowassistants['name'].'</div></div>');
				$assistantcount=0;
				$filledboxcount++;
			}
			else if($filledboxcount<4 && $assistantcount>1)
			{
				echo('<div class="outerBox" > <div class="Box" style="position: relative;" id="box'.($filledboxcount+1).'"');
					echo('onclick=\'gotoCategory('.$userid.',"Assistant of","","",""); \' >');
					/*echo('<div class="Subbox">');
					echo('<span class="Boxtextheader">Students of</span> <span class="Boxtextcontent">'.$row1['name'].'</span>');
					echo('<span class="Boxtextcount">'.$studentcount.' artists</span>');
					echo('</div></div>'); */
					echo('<img src="/images/box-background.png" class="BoxImage" width="100%" height="100%" alt="Image not found"  style="z-index: -1"/>');
					echo('<div style="position:absolute;left:15%;top:15%;"><span class="Boxtextheader">Assistants of</span> <span class="Boxtextcontent">'.$row1['name'].'</span><span class="Boxtextcount">'.$assistantcount.' artists</span></div><div class="boxText"><br/></div></div>');
						
					/*echo('</div></div>');*/
					echo('</div>');
					$filledboxcount++;
					$assistantcount=0;
			}
			if($filledboxcount<4 && $movementcount>0)
			{
				echo('<div class="outerBox" > <div class="Box" style="position: relative;" id="box'.($filledboxcount+1).'"');
				echo('onclick=\'gotoCategory("","Movement","","'.$row1['movement'].'",""); \' >');
				/*echo('<div class="Subbox" style="position: relative;">');*/
/*				echo('<span class="Boxtextheader">Art Movement</span> <span class="Boxtextcontent">'.$row1['movement'].'</span>');
				echo('<span class="Boxtextcount">'.$movementcount.' artists</span>');
*/
				echo('<img src="/images/box-background.png" class="BoxImage" width="100%" height="100%" alt="Image not found"  style="z-index: -1"/>');
				echo('<div style="position:absolute;left:15%;top:15%;"><span class="Boxtextheader">Art Movement</span> <span class="Boxtextcontent">'.$row1['movement'].'</span><span class="Boxtextcount">'.$movementcount.' artists</span></div><div class="boxText"></div></div>');
					
				/*echo('</div></div>');*/
				echo('</div>');
				
				$filledboxcount++;
				$movementcount=0;
			}
			if($filledboxcount<4 && $subjectcount>0)
			{
				echo('<div class="outerBox" > <div class="Box" style="position: relative;" id="box'.($filledboxcount+1).'"');
				echo('onclick=\'gotoCategory("","Subject","","'.$row1['subject'].'",""); \' >');
				/*echo('<div class="Subbox">');
				echo('<span class="Boxtextheader">Art Subject</span> <span class="Boxtextcontent">'.$row1['subject'].'</span>');
				echo('<span class="Boxtextcount">'.$subjectcount.' artists</span>');
				echo('</div></div>');*/
				echo('<img src="/images/box-background.png" class="BoxImage" width="100%" height="100%" alt="Image not found"  style="z-index: -1"/>');
				echo('<div style="position:absolute;left:15%;top:15%;"><span class="Boxtextheader">Art Subject</span> <span class="Boxtextcontent">'.$row1['subject'].'</span><span class="Boxtextcount">'.$subjectcount.' artists</span></div><div class="boxText"></div></div>');
										
					/*echo('</div></div>');*/
					echo('</div>');				
				$filledboxcount++;
				$subjectcount=0;
			}
			if($filledboxcount<4 && $mediumcount>0)
			{
				echo('<div class="outerBox" > <div class="Box" style="position: relative;" id="box'.($filledboxcount+1).'"');
				echo('onclick=\'gotoCategory("","Medium","","'.$row1['medium'].'",""); \' >');
/*				echo('<div class="Subbox">');
				echo('<span class="Boxtextheader">Art Medium</span> <span class="Boxtextcontent">'.$row1['medium'].'</span>');
				echo('<span class="Boxtextcount">'.$mediumcount.' artists</span>');
				echo('</div></div>');
				*/
				echo('<img src="/images/box-background.png" class="BoxImage" width="100%" height="100%" alt="Image not found"  style="z-index: -1"/>');
				echo('<div style="position:absolute;left:15%;top:15%;"><span class="Boxtextheader">Art Medium</span> <span class="Boxtextcontent">'.$row1['medium'].'</span><span class="Boxtextcount">'.$mediumcount.' artists</span></div><div class="boxText"></div></div>');
										
					/*echo('</div></div>');*/
					echo('</div>');				
				$filledboxcount++;
				$mediumcount=0;
			}
			if($filledboxcount<4 && $nationalitycount>0)
			{
				echo('<div class="outerBox" > <div class="Box" style="position: relative;" id="box'.($filledboxcount+1).'"');
				echo('onclick=\'gotoCategory("","Nationality","","'.$row1['nationality'].'",""); \' >');
				/*echo('<div class="Subbox">');
				echo('<span class="Boxtextheader">'.$row1['nationality'].' artists</span>');
				echo('<span class="Boxtextcount">'.$nationalitycount.' artists</span>');
				echo('</div></div>');*/
				echo('<img src="/images/box-background.png" class="BoxImage" width="100%" height="100%" alt="Image not found"  style="z-index: -1"/>');
				echo('<div style="position:absolute;left:15%;top:15%;"><span class="Boxtextheader">'.$row1['nationality'].' artists</span> <span class="Boxtextcount">'.$nationalitycount.' artists</span></div><div class="boxText"></div></div>');
										
					/*echo('</div></div>');*/
					echo('</div>');	
				$filledboxcount++;
				$nationalitycount=0;
			}
			if($filledboxcount<4 && $gendercount>0)
			{
				echo('<div class="outerBox" > <div class="Box" style="position: relative;" id="box'.($filledboxcount+1).'"');
				echo('onclick=\'gotoCategory("","gender","era","female","'.$row1['era'].'"); \' >');
				/*echo('<div class="Subbox">');
				echo('<span class="Boxtextheader">Women artists of</span> <span class="Boxtextcontent">'.$row1['era'].'</span>');
				echo('<span class="Boxtextcount">'.$gendercount.' artists</span>');
				echo('</div></div>');*/
				echo('<img src="/images/box-background.png" class="BoxImage" width="100%" height="100%" alt="Image not found"  style="z-index: -1"/>');
				echo('<div style="position:absolute;left:15%;top:15%;"><span class="Boxtextheader">Women artists of</span> <span class="Boxtextcontent">'.$row1['era'].'</span><span class="Boxtextcount">'.$gendercount.' artists</span></div><div class="boxText"></div></div>');
										
					/*echo('</div></div>');*/
					echo('</div>');	
				$filledboxcount++;
				$gendercount=0;
			}
			//If blocks are empty and students are displayed in block
			if($filledboxcount<4 && $isstudentblock==1 && $studentcount>0)
			{
				while($filledboxcount<4 && $studentcount>0)
				{
					mysql_data_seek($resstudents,($studentcount-1));
					$rowstudents=mysql_fetch_assoc($resstudents);
					echo('<div class="outerBox" > <div class="Box" id="box'.($filledboxcount+1).'"><img class="BoxImage" src=\'');
					if($rowstudents['image']!="")
					{
						echo($rowstudents['image']); 
					}
					else
					{
						echo("/images/image-not-available.png");
					}
					echo('\' width=100% height="100%" onclick="getUserProfile('.$rowstudents['artist1'].');"/>');
					echo('</div><div class="boxText"> Teacher of</br>'.$rowstudents['name'].'</div></div>');
					$studentcount--;
					$filledboxcount++;
				}
			}
			while($filledboxcount<4)
			{
				echo('<div class="outerBox" > <div class="Box" style="position: relative;" id="box'.($filledboxcount+1).'">');
				echo('<img src="/images/box-background.png" class="BoxImage" width="100%" height="100%" alt="Image not found"  style="z-index: -1"/>');
				echo('<div style="position:absolute;left:15%;top:15%;"></div><div class="boxText"></div></div>');
										
					/*echo('</div></div>');*/
					echo('</div>');	
				$filledboxcount++;
			}
		?>
               <div class="outerBox" >  <div class="Box" id="box5"><div height="90%"> <img class="BoxImage" src="
                <?php 
					
					if($row1['image']!="")
					{
						echo($row1['image']); 
					}
					else
					{
						echo("/images/image-not-available.png");
					}
				
				?>" width=100% height="100%" onClick="getUserProfile(<?php echo($row1['artistId']);?>);"/></div>
                <div class="boxText" style="padding-top:2%;"> <?php echo($row1['name']);?></div>
                </div></div>
        <?php 
			while($filledboxcount<8 && $temprelationscount>0)
			{	
				mysql_data_seek($resrelations,($temprelationscount-1));			
				$rowrelations=mysql_fetch_assoc($resrelations);
				echo('<div class="outerBox" > <div class="Box" id="box'.($filledboxcount+1).'"><img class="BoxImage" src=\'');
				if($rowrelations['image']!="")
				{
					echo($rowrelations['image']); 
				}
				else
				{
					echo("/images/image-not-available.png");
				}
				echo('\' width=100% height="100%" onclick="getUserProfile('.$rowrelations['artist2'].');"/>');
				echo('</div><div class="boxText">'.$rowrelations['relationship'].'</br>'.$rowrelations['name'].'</div></div>');
				$temprelationscount--;
				$filledboxcount++;
			}
			if($filledboxcount<8 && $tempstudentcount>0)
			{
				//Display students individually
				if($isstudentblock==0)
				{
					
					while($filledboxcount<8 && $tempstudentcount>0)
					{
						mysql_data_seek($resstudents,($tempstudentcount-1));
						$rowstudents=mysql_fetch_assoc($resstudents);
						echo('<div class="outerBox" > <div class="Box" id="box'.($filledboxcount+1).'"><img class="BoxImage" src=\'');
						if($rowstudents['image']!="")
						{
							echo($rowstudents['image']); 
						}
						else
						{
							echo("/images/image-not-available.png");
						}
						echo('\' width=100% height="100%" onclick="getUserProfile('.$rowstudents['artist1'].');"/>');
						echo('</div><div class="boxText"> Teacher of</br>'.$rowstudents['name'].'</div></div>');
						$tempstudentcount--;
						$filledboxcount++;
					}
				}
				//Display students in 1 block
				else
				{
					$tempstudentcount=1;
					
					echo('<div class="outerBox" > <div class="Box" style="position: relative;" id="box'.($filledboxcount+1).'"');
					echo('onclick=\'gotoCategory('.$userid.',"Student of","","",""); \' >');
					/*echo('<div class="Subbox">');
					echo('<span class="Boxtextheader">Students of</span> <span class="Boxtextcontent">'.$row1['name'].'</span>');
					echo('<span class="Boxtextcount">'.$studentcount.' artists</span>');
					echo('</div></div>');**/
					echo('<img src="/images/box-background.png" class="BoxImage" width="100%" height="100%" alt="Image not found"  style="z-index: -1"/>');
					echo('<div style="position:absolute;left:15%;top:15%;"><span class="Boxtextheader">Students of</span> <span class="Boxtextcontent">'.$row1['name'].'</span><span class="Boxtextcount">'.$studentcount.' artists</span></div><div class="boxText"></div></div>');
						
					/*echo('</div></div>');*/
					echo('</div>');
					$tempstudentcount--;
					$filledboxcount++;
				}	
			}
			if($filledboxcount<8 && $assistantcount==1)
			{
				$rowassistants=mysql_fetch_assoc($resassistants);
				echo('<div class="outerBox" > <div class="Box" id="box'.($filledboxcount+1).'"><img class="BoxImage" src=\'');
				if($rowassistants['image']!="")
				{
					echo($rowassistants['image']); 
				}
				else
				{
					echo("/images/image-not-available.png");
				}
				echo('\' width=100% height="100%" onclick="getUserProfile('.$rowassistants['artist1'].');"/>');
				echo('</div><div class="boxText"> Mentor of</br>'.$rowassistants['name'].'</div></div>');
				$assistantcount=0;
				$filledboxcount++;
			}
			else if($filledboxcount<8 && $assistantcount>1)
			{
				echo('<div class="outerBox" > <div class="Box" style="position: relative;" id="box'.($filledboxcount+1).'"');
					echo('onclick=\'gotoCategory('.$userid.',"Assistant of","","",""); \' >');
					/*echo('<div class="Subbox">');
					echo('<span class="Boxtextheader">Students of</span> <span class="Boxtextcontent">'.$row1['name'].'</span>');
					echo('<span class="Boxtextcount">'.$studentcount.' artists</span>');
					echo('</div></div>'); */
					echo('<img src="/images/box-background.png" class="BoxImage" width="100%" height="100%" alt="Image not found"  style="z-index: -1"/>');
					echo('<div style="position:absolute;left:15%;top:15%;"><span class="Boxtextheader">Assistants of</span> <span class="Boxtextcontent">'.$row1['name'].'</span><span class="Boxtextcount">'.$assistantcount.' artists</span></div><div class="boxText"><br/></div></div>');
						
					/*echo('</div></div>');*/
					echo('</div>');
					$filledboxcount++;
					$assistantcount=0;
			}
			if($filledboxcount<8 && $movementcount>0)
			{
				echo('<div class="outerBox" > <div class="Box" style="position: relative;" id="box'.($filledboxcount+1).'"');
				echo('onclick=\'gotoCategory("","Movement","","'.$row1['movement'].'",""); \' >');
				/*echo('<div class="Subbox">');
				echo('<span class="Boxtextheader">Art Movement</span> <span class="Boxtextcontent">'.$row1['movement'].'</span>');
				echo('<span class="Boxtextcount">'.$movementcount.' artists</span>');
				echo('</div></div>');*/
				echo('<img src="/images/box-background.png" class="BoxImage" width="100%" height="100%" alt="Image not found"  style="z-index: -1"/>');
				echo('<div style="position:absolute;left:15%;top:15%;"><span class="Boxtextheader">Art Movement</span> <span class="Boxtextcontent">'.$row1['movement'].'</span><span class="Boxtextcount">'.$movementcount.' artists</span></div><div class="boxText"></div></div>');
					
				/*echo('</div></div>');*/
				echo('</div>');
				$filledboxcount++;
			}
			if($filledboxcount<8 && $subjectcount>0)
			{
				echo('<div class="outerBox" > <div class="Box" style="position: relative;" id="box'.($filledboxcount+1).'"');
				echo('onclick=\'gotoCategory("","Subject","","'.$row1['subject'].'",""); \' >');
				/*echo('<div class="Subbox">');
				echo('<span class="Boxtextheader">Art Subject</span> <span class="Boxtextcontent">'.$row1['subject'].'</span>');
				echo('<span class="Boxtextcount">'.$subjectcount.' artists</span>');
				echo('</div></div>');*/
				echo('<img src="/images/box-background.png" class="BoxImage" width="100%" height="100%" alt="Image not found"  style="z-index: -1"/>');
				echo('<div style="position:absolute;left:15%;top:15%;"><span class="Boxtextheader">Art Subject</span> <span class="Boxtextcontent">'.$row1['subject'].'</span><span class="Boxtextcount">'.$subjectcount.' artists</span></div><div class="boxText"></div></div>');
										
					/*echo('</div></div>');*/
					echo('</div>');	
				$filledboxcount++;
			}
			if($filledboxcount<8 && $mediumcount>0)
			{
				echo('<div class="outerBox" > <div class="Box" style="position: relative;" id="box'.($filledboxcount+1).'"');
				echo('onclick=\'gotoCategory("","Medium","","'.$row1['medium'].'",""); \' >');
				/*echo('<div class="Subbox">');
				echo('<span class="Boxtextheader">Art Medium</span> <span class="Boxtextcontent">'.$row1['medium'].'</span>');
				echo('<span class="Boxtextcount">'.$mediumcount.' artists</span>');
				echo('</div></div>');*/
				echo('<img src="/images/box-background.png" class="BoxImage" width="100%" height="100%" alt="Image not found"  style="z-index: -1"/>');
				echo('<div style="position:absolute;left:15%;top:15%;"><span class="Boxtextheader">Art Medium</span> <span class="Boxtextcontent">'.$row1['medium'].'</span><span class="Boxtextcount">'.$mediumcount.' artists</span></div><div class="boxText"></div></div>');
										
					/*echo('</div></div>');*/
					echo('</div>');		
				$filledboxcount++;
			}
			if($filledboxcount<8 && $nationalitycount>0)
			{
				echo('<div class="outerBox" > <div class="Box" style="position: relative;" id="box'.($filledboxcount+1).'"');
				echo('onclick=\'gotoCategory("","Nationality","","'.$row1['nationality'].'",""); \' >');
				/*echo('<div class="Subbox">');
				echo('<span class="Boxtextheader">'.$row1['nationality'].' artists</span>');
				echo('<span class="Boxtextcount">'.$nationalitycount.' artists</span>');
				echo('</div></div>');*/
				echo('<img src="/images/box-background.png" class="BoxImage" width="100%" height="100%" alt="Image not found"  style="z-index: -1"/>');
				echo('<div style="position:absolute;left:15%;top:15%;"><span class="Boxtextheader">'.$row1['nationality'].' artists</span> <span class="Boxtextcount">'.$nationalitycount.' artists</span></div><div class="boxText"></div></div>');
										
					/*echo('</div></div>');*/
					echo('</div>');	
				$filledboxcount++;
			}
			if($filledboxcount<8 && $gendercount>0)
			{
				echo('<div class="outerBox" > <div class="Box" style="position: relative;" id="box'.($filledboxcount+1).'"');
				echo('onclick=\'gotoCategory("","gender","era","female","'.$row1['era'].'"); \' >');
				/*echo('<div class="Subbox">');
				echo('<span class="Boxtextheader">Women artists of</span> <span class="Boxtextcontent">'.$row1['era'].'</span>');
				echo('<span class="Boxtextcount">'.$gendercount.' artists</span>');
				echo('</div></div>');*/
				echo('<img src="/images/box-background.png" class="BoxImage" width="100%" height="100%" alt="Image not found"  style="z-index: -1"/>');
				echo('<div style="position:absolute;left:15%;top:15%;"><span class="Boxtextheader">Women artists of</span> <span class="Boxtextcontent">'.$row1['era'].'</span><span class="Boxtextcount">'.$gendercount.' artists</span></div><div class="boxText"></div></div>');
										
					/*echo('</div></div>');*/
					echo('</div>');	
				$filledboxcount++;
			}
			//If blocks are empty and students are displayed in block
			if($filledboxcount<8 && $isstudentblock==1 && $studentcount>0)
			{
				while($filledboxcount<8 && $studentcount>0)
				{
					mysql_data_seek($resstudents,($studentcount-1));
					$rowstudents=mysql_fetch_assoc($resstudents);
					echo('<div class="outerBox" > <div class="Box" id="box'.($filledboxcount+1).'"><img class="BoxImage" src=\'');
					if($rowstudents['image']!="")
					{
						echo($rowstudents['image']); 
					}
					else
					{
						echo("/images/image-not-available.png");
					}
					echo('\' width=100% height="100%" onclick="getUserProfile('.$rowstudents['artist1'].');"/>');
					echo('</div><div class="boxText"> Teacher of</br>'.$rowstudents['name'].'</div></div>');
					$studentcount--;
					$filledboxcount++;
				}
			}
			while($filledboxcount<8)
			{
				echo('<div class="outerBox" > <div class="Box" style="position: relative;" id="box'.($filledboxcount+1).'">');
				echo('<img src="/images/box-background.png" class="BoxImage" width="100%" height="100%" alt="Image not found"  style="z-index: -1"/>');
				echo('<div style="position:absolute;left:15%;top:15%;"></div><div class="boxText"></div></div>');
										
					/*echo('</div></div>');*/
					echo('</div>');	
				$filledboxcount++;
			}
		?>
        </div>

</header>
</div>
<section>
</section>
<footer>
</footer>
<script src="js/jquery-1.7.2.min.js"></script>
<input id="artistid" name="artistid" type="hidden" value="" />
<input id="category1" name="category1" type="hidden" value="" />
<input id="category2" name="category2" type="hidden" value="" />
<input id="category1value" name="category1value" type="hidden" value="" />
<input id="category2value" name="category2value" type="hidden" value="" />

<!-------------------------------------------------lightbox div-------------------------------------->


<!-------------------------------------------------lightbox div ends----------------------------------->
</form>
</body>
</html>
<?php } ?>