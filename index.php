<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--
  GMS nice slide down menu navigation
  using ?page=xxx.php  and include() function for menu navigaiton
  gms/index.php
  REVISIONS
	1.9 - 2012-11-26 Added an html table that has all content in left col And annoucements in the right panel - 20%  
	1.91 - 2012-11-26 now load annoucements via php script that will pull all from 'rostrum' table

  
-->

<html>
    <head>
        <title>Global Mind Share</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <meta name="description" content="Global Mind Share" />
        <meta name="keywords" content="global mind share"/>
        <!-- The JavaScript for Slidebox menu -->
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
		<script type="text/javascript" src="js/jquery.easing.1.3.js"></script>
		<script type="text/javascript" src="js/slideboxmenu.fuction.js"></script>
		<link rel="shortcut icon" href="../favicon.ico" type="image/x-icon"/>

		<!-- style sheet for the slidedown menus -->
        <link rel="stylesheet" href="css/styleslidedown.css" type="text/css" media="screen"/>
        <style>
			body{
				font-family:Arial;
  				background:#000D20 url(images/earth_from_space.jpg) no-repeat ;
			}
			
			/* span for reference - only used for footer links to social media */
			span.reference{
				position:fixed;
				left:10px;
				bottom:10px;
				font-size:12px;
			}
			span.reference a{
				color:#aaa;
				text-transform:uppercase;
				text-decoration:none;
				text-shadow:1px 1px 1px #000;
				margin-right:30px;
			}
			span.reference a:hover{
				color:#ddd;
			}
			
			
			/* Margin above the Menu items */
			ul.sdt_menu{
				margin-top:15px;
			}
			/* allows display of GMS Logo Title */
			h1.title{
				text-indent:-9000px;
				background:transparent url(images/gmstitle.gif) no-repeat top left;
				//background:transparent url(images/earth_from_space.jpg) no-repeat top left;
				width:633px; height:80px;
				
			}

		</style>
		
    </head>

	
<body>


<!--   Original Area for gms version and social media links  -->				



<!--   Added a table that has all content in left col And annoucements in the right panel - 20%   -->

<table border="1" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td width="80%" align="left" valign="top"><font color="white"></font>
		
		
	
<!-- slide down menu -->	
<div class="content">



	<!-- this puts the GMS logo image above. make it a link to home too. -->
	<a href="index.php"> <h1 class="title"></h1>	</a>
	<ul id="sdt_menu" class="sdt_menu">
		<li>
			<!-- Vision -->
			<a href="index.php?page=vision">
				<img src="images/vision.jpg" alt=""/>
				<span class="sdt_active"></span>
				<span class="sdt_wrap">
					<span class="sdt_link">Vision</span>
					<span class="sdt_descr">What we can All do together</span>
				</span>
			</a>
		</li>
		<li>
			<!-- About Us -->
			<a href="index.php?page=aboutus">
				<img src="images/aboutus.jpg" alt=""/>
				<span class="sdt_active"></span>
				<span class="sdt_wrap">
					<span class="sdt_link">About us</span>
					<span class="sdt_descr">Who we are</span>
				</span>
			</a>
			<div class="sdt_box">
					<a href="http://www.facebook.com/globalmindshare">Facebook</a>
					<a href="https://twitter.com/globalmindshare">Twitter</a>
					<a href="http://www.youtube.com/globalmindshare">YouTube</a>
			</div>
		</li>
<!-- 		
		<li>
			<!-- Register Form 
			<a href="index.php?page=register">
				<img src="images/register.jpg" alt=""/>
				<span class="sdt_active"></span>
				<span class="sdt_wrap">
					<span class="sdt_link">Register</span>
					<span class="sdt_descr">Want to help? Sign up here</span>
				</span>
			</a>
		</li>
-->		
		<li>
			<!-- Submit new issue Form -->
			<a href="index.php?page=submitinput">
				<img src="images/submit.jpg" alt=""/>
				<span class="sdt_active"></span>
				<span class="sdt_wrap">
					<span class="sdt_link">Submit</span>
					<span class="sdt_descr">Problems Solutions</span>
				</span>
			</a>
		</li>

		<li>
			<!--View Submitted-->
			<a href="showSubmittedBasic.php">
				<img src="images/globehand.jpg" alt=""/>
				<span class="sdt_active"></span>
				<span class="sdt_wrap">
					<span class="sdt_link">View Submitted</span>
					<span class="sdt_descr">View all Submitted Problems</span>
				</span>
			</a>
		</li>
		
		<li>
			<!--Terms of use-->
			<a href="index.php?page=termsofuse">
				<img src="images/termsofuse.jpg" alt=""/>
				<span class="sdt_active"></span>
				<span class="sdt_wrap">
					<span class="sdt_link">Terms of Use</span>
					<span class="sdt_descr">The Legal Stuff</span>
				</span>
			</a>
		</li>
		
		<li>
			<!--Donations-->
			<a href="index.php?page=donations">
				<img src="images/donate-thCAQM49AO.jpg" alt=""/>
				<span class="sdt_active"></span>
				<span class="sdt_wrap">
					<span class="sdt_link">Donations</span>
					<span class="sdt_descr">We could use your financial support</span>
				</span>
			</a>
		</li>
<!-- 		 Query DB 

		<li>
			<a href="#">
				<img src="images/globehand.jpg" alt=""/>
				<span class="sdt_active"></span>
				<span class="sdt_wrap">
					<span class="sdt_link">Query DB</span>
					<span class="sdt_descr">Testing of database</span>
				</span>
			</a>
			<div class="sdt_box">
				<a href="showSubmittedBasic.php">View Submitted <b>V 1.0</b></a>
				<a href="showTableINPUT.php">List Input <b>V 2.0</b></a>
				<a href="input_select.php">List Input V 1.0</a>
				<a href="fellow_select.php">List Fellow</a>
				<a href="index.php?page=register">Register User</a>
			</div>
		</li>
-->
		
	</ul>
</div>


<!-- --------------- separator ---------------- which also KEEPS Social Media icons at bottom -->	
<div style="height:150px;margin-bottom:10px">   </div>


<!-- --------------- div for the Content below Menu ----------------  -->	
<!--
<div style="float:left;width:100%;height:150px;border:0px solid #000" class="gmscontent">		
-->
<div style="float:left;width:100%;border:0px solid #000" class="gmscontent">		

<?php

/* will do a basic php append to a log file
 $File = "phplog.txt"; 
 $Handle = fopen($File, 'a');   //append
 $logData = "start php\n"; 
 fwrite($Handle, $logData); 
 fclose($Handle); 
*/ 

 
# Navigation :  use url page=xxx to control which php pages to include

# setup document root path and gms relative path
$base = $_SERVER['DOCUMENT_ROOT'];
$sourcepath = 'gms/slideboxphp/';
# Use relative path (change)
$base = '';
$sourcepath = '';

# default HOME page
$default = $sourcepath.'home.php';

#echo 'base location for website '. $base; echo '<br>  - final website '. $base. $sourcepath;  echo '<br>';

# list of all site pages + the id they will be called by
$pages = array(
  'aboutus' => $sourcepath.'aboutus.php' 
 ,'vision' => $sourcepath.'vision.php'
 ,'termsofuse' => $sourcepath.'termsofuse.php'
 ,'donations' => $sourcepath.'donations.php'
 ,'register' => $sourcepath.'formregister.php'  
 ,'submitinput' => $sourcepath.'formsubmitinput.php'  
 ,'home' => $sourcepath.'home.php'
 );

#echo 'isset of get : '. isset( $_GET['page'] );  echo '<br>';
#echo $pages['vision'];
#echo "<br><hr>"; echo $pages['vision']; echo "<br><hr>";

# line delim
#echo "<br><hr>";

if ( isset($_GET['page']) and array_key_exists($_GET['page'],$pages)  )

{
foreach($pages as $pageid => $pagename) {
#echo $pageid. '  '.$pagename. '  aaa <br>';echo $base.$pagename. '  bbb<br>';

if($_GET['page'] == $pageid && file_exists($base.$pagename))
{
          /* if somebody's making a request for ?page=xxx and
          the page exists in the $pages array, we display it
          checking first it also exists as a page on the server */
		  #echo $base.$pagename;
          if (file_exists($base.$pagename)) include $base.$pagename;
      }
   } // end foreach
}
else {
          /* if the page isn't listed in $pages, or there's no ?page=xxx request
          we show the default page, again we'll also just make sure it exists as a file
          on the server */
		  #echo 'default ';
          if(file_exists($base.$default)) include $base.$default;
}
 
?>
</div>


  
<!-- Contact-us Global Mind Share - Social Media Links -->
<div id="ContactRight">
<!-- <p align="right"><strong><span class="grays-subtitle">Connect to GMS</span></strong></p> 
	<a href="index.php">GMS</a>
-->
	<p align="right">
	<a href="http://www.facebook.com/globalmindshare" target="_blank">
	 <img title="Follow GMS ON Facebook" style="BORDER-BOTTOM: 0px solid; BORDER-LEFT: 0px solid; WIDTH: 24px; HEIGHT: 24px; BORDER-TOP: 0px solid; BORDER-RIGHT: 0px solid" alt="Follow GMS ON Facebook" src="images/facebook-follow-lg.png" border="0"></a>
	<a href="https://twitter.com/globalmindshare" target="_blank">
	 <img title="Follow GMS ON Twitter" style="BORDER-BOTTOM: 0px solid; BORDER-LEFT: 0px solid; WIDTH: 24px; HEIGHT: 24px; BORDER-TOP: 0px solid; BORDER-RIGHT: 0px solid" alt="Follow GMS ON Twitter" src="images/twitter-follow-lg.png" border="0"></a>
	<a href="http://www.youtube.com/globalmindshare" target="_blank">
	 <img title="Follow GMS ON YouTube" style="BORDER-BOTTOM: 0px solid; BORDER-LEFT: 0px solid; WIDTH: 24px; HEIGHT: 24px; BORDER-TOP: 0px solid; BORDER-RIGHT: 0px solid" alt="Follow GMS ON YouTube" src="images/youtube-follow-lg.png" border="0"></a>
<!-- 
	 <a href="http://www.linkedin.com/company/9211" target="_blank">
	 <img title="Follow GMS ON LinkedIn" style="BORDER-BOTTOM: 0px solid; BORDER-LEFT: 0px solid; WIDTH: 24px; HEIGHT: 24px; BORDER-TOP: 0px solid; BORDER-RIGHT: 0px solid" alt="Follow GMS ON LinkedIn" src="images/linkedin-follow-lg.png" border="0"></a>
 -->	 
	</p>
</div>

		</td>
		
		<!--  table Cell 2 - the Announcements -->
		<td width="20%" align="left" valign="top" bordercolordark="#008000">
		<p></p>
		<font color="white"><b>Announcements</b></font>
		<!--  use php to select all rows from announcements and display -->
		<?php 
			$announce_file = 'showAnnouncements.php';
			if (file_exists($announce_file )) {include $announce_file;}
		?>
<!--		
		<ul style = "padding-left:7%;">
			<li>
			<font color="#0B75AF">
			<b>2012-11-25</b></font>
			<font color="white"> - liftoff : we have now launched the amazing GMS website for all members of the Earth to join in a unilateral effort to solve the problems that face us all</font>
			</li>
			<li>
			<font color="#0B75AF">
			<b>2012-11-21</b></font><font color="white"> - API created for Version 2</font>
			</li>
		</ul>
-->
		
		</td>
	</tr>
</table>



<!-- ------------------------------------------------ -->
<!-- Developer Only links -->

<!-- Display GMS website version - -->
<?php 
  $gmsversion_file = 'gmsversion.php';
  if (file_exists($gmsversion_file)) {include $gmsversion_file;}
?>
  

<!-- include GMS developer links  - -->
<?php 
  $developer_file = 'developer-links.php';
  if (file_exists($developer_file)) {include $developer_file ;}
?>

 
<div><span class="reference"></span></div>

<!-- --------------- footer for social media ---------------- -->	
<!-- 

        <div>
            <span class="reference">
                <a href="http://www.facebook.com/pages/KepiMindShare/119818971470811">Facebook</a>
				<a href="http://twitter.com/kepimindshare">Twitter</a>
				<a href="http://www.facebook.com/kepimindshare" target="_blank">
				 <img title="Follow GMS ON Facebook" style="BORDER-BOTTOM: 0px solid; BORDER-LEFT: 0px solid; WIDTH: 24px; HEIGHT: 24px; BORDER-TOP: 0px solid; BORDER-RIGHT: 0px solid" alt="Follow GMS ON Facebook" src="images/facebook-follow-lg.png" border="0"></a>

				<a href="http://twitter.com/kepimindshare" target="_blank">
				 <img title="Follow GMS ON Twitter" style="BORDER-BOTTOM: 0px solid; BORDER-LEFT: 0px solid; WIDTH: 24px; HEIGHT: 24px; BORDER-TOP: 0px solid; BORDER-RIGHT: 0px solid" alt="Follow GMS ON Twitter" src="images/twitter-follow-lg.png" border="0"></a>

				<a href="http://www.youtube.com/kepimindshare" target="_blank">
				 <img title="Follow GMS ON YouTube" style="BORDER-BOTTOM: 0px solid; BORDER-LEFT: 0px solid; WIDTH: 24px; HEIGHT: 24px; BORDER-TOP: 0px solid; BORDER-RIGHT: 0px solid" alt="Follow GMS ON YouTube" src="images/youtube-follow-lg.png" border="0"></a>

				<a href="http://www.linkedin.com/company/9211" target="_blank">
				 <img title="Follow GMS ON LinkedIn" style="BORDER-BOTTOM: 0px solid; BORDER-LEFT: 0px solid; WIDTH: 24px; HEIGHT: 24px; BORDER-TOP: 0px solid; BORDER-RIGHT: 0px solid" alt="Follow GMS ON LinkedIn" src="images/linkedin-follow-lg.png" border="0"></a>
 				
            </span>
		</div>

-->



		
    </body>
</html>