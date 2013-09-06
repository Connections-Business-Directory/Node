<html>
	<head>
		<title>Example page - auto generated</title>
		<link href="http://steamdev.com/snippet/css/jquery.snippet.css" type="text/css" rel="Stylesheet" />
		<style>
			body{
				font: 75% arial,sans-serif;
			}
			.collapsible{
				padding-top: 7px;
			}
			.collapsible:hover{
				cursor: pointer;
				background-color: #D7DEFF;
			}
			.example{
				padding: 10px;
				margin-bottom: 10px;
				border: 2px solid #E2E7EF;
				width: 802px;
			}
			.outputbox{
				padding: 25px 5px;
				background-color: #E9EDF3;
				border: 1px solid white;
				overflow: auto;
			}
			h2{
				color: black;
				padding: 0px 10px;
				width: 805px;
				background-color: #E9E9E9;
			}
			pre{
				margin: 0;
				font-family: monospace;
			}
			.sidebar{
				min-width: 15em;
				border-right: 2px solid #E9E9E9;
				height: 600px;
				float: left;
			}
			.pname{
				font-size: 300%;
				text-decoration: none;
			}
			.pname a,
			.pname a:visited{
				color: #666;
				text-decoration: none;
				font-size: 82%;
			}
			.psum{
			}
			.psum a,
			.psum a:visited{
				color: #444;
				text-decoration: none;
				font-size: 90%;
			}
			

/*---------- typical Styles ---------*/
.sh_typical{background:none; padding:0; margin:0; border:0 none;}
.sh_typical .sh_sourceCode{background-color:#EEEEEE;color:#000;font-weight:normal;font-style:normal;}
.sh_typical .sh_sourceCode .sh_keyword{color:#6F5735;font-weight:normal;font-style:normal;}
.sh_typical .sh_sourceCode .sh_type{color:#00f;font-weight:normal;font-style:normal;}
.sh_typical .sh_sourceCode .sh_string{color:#575757;font-weight:normal;font-style:normal;}
.sh_typical .sh_sourceCode .sh_regexp{color:#f00;font-weight:normal;font-style:normal;}
.sh_typical .sh_sourceCode .sh_specialchar{color:#C42DA8;font-weight:normal;font-style:normal;}
.sh_typical .sh_sourceCode .sh_comment{color:#909090;font-weight:normal;font-style:italic;}
.sh_typical .sh_sourceCode .sh_number{color:#a900a9;font-weight:normal;font-style:normal;}
.sh_typical .sh_sourceCode .sh_preproc{color:#00b800;font-weight:normal;font-style:normal;}
.sh_typical .sh_sourceCode .sh_symbol{color:#f00;font-weight:normal;font-style:normal;}
.sh_typical .sh_sourceCode .sh_function{color:#000;font-weight:bold;font-style:normal;}
.sh_typical .sh_sourceCode .sh_cbracket{color:#f00;font-weight:normal;font-style:normal;}
.sh_typical .sh_sourceCode .sh_url{color:#f00;font-weight:normal;font-style:normal;}
.sh_typical .sh_sourceCode .sh_date{color:#00f;font-weight:bold;font-style:normal;}
.sh_typical .sh_sourceCode .sh_time{color:#00f;font-weight:bold;font-style:normal;}
.sh_typical .sh_sourceCode .sh_file{color:#00f;font-weight:bold;font-style:normal;}
.sh_typical .sh_sourceCode .sh_ip{color:#f00;font-weight:normal;font-style:normal;}
.sh_typical .sh_sourceCode .sh_name{color:#f00;font-weight:normal;font-style:normal;}
.sh_typical .sh_sourceCode .sh_variable{color:#ec7f15;font-weight:normal;font-style:normal;}
.sh_typical .sh_sourceCode .sh_oldfile{color:#C42DA8;font-weight:normal;font-style:normal;}
.sh_typical .sh_sourceCode .sh_newfile{color:#f00;font-weight:normal;font-style:normal;}
.sh_typical .sh_sourceCode .sh_difflines{color:#00f;font-weight:bold;font-style:normal;}
.sh_typical .sh_sourceCode .sh_selector{color:#ec7f15;font-weight:normal;font-style:normal;}
.sh_typical .sh_sourceCode .sh_property{color:#00f;font-weight:bold;font-style:normal;}
.sh_typical .sh_sourceCode .sh_value{color:#f00;font-weight:normal;font-style:normal;}

/*-------- Snippet Base Styles ----------*/
.snippet-wrap {position:relative;}
*:first-child+html .snippet-wrap {display:inline-block;}
* html .snippet-wrap {display:inline-block;}
.snippet-reveal{text-decoration:underline;}
.snippet-wrap .snippet-menu, .snippet-wrap .snippet-hide {position:absolute; top:10px; right:15px; font-size:.9em;z-index:1;background-color:transparent;}
.snippet-wrap .snippet-hide {top:auto; bottom:10px;}
*:first-child+html .snippet-wrap .snippet-hide {bottom:25px;}
* html .snippet-wrap .snippet-hide {bottom:25px;}
.snippet-wrap .snippet-menu pre, .snippet-wrap .snippet-hide pre {background-color:transparent; margin:0; padding:0;}
.snippet-wrap .snippet-menu a, .snippet-wrap .snippet-hide a {padding:0 5px; text-decoration:underline;}
.snippet-wrap pre.sh_sourceCode{padding:1em;line-height:1.8em;overflow:auto;position:relative;

-moz-border-radius:0px;
-webkit-border-radius:0px;
border-radius:0px;

box-shadow: none;
-moz-box-shadow: none;
-webkit-box-shadow: none;}
.snippet-wrap pre.snippet-textonly {padding:2em;}
*:first-child+html .snippet-wrap pre.snippet-formatted {padding:2em 1em;}
* html .snippet-wrap pre.snippet-formatted {padding:2em 1em;}
.snippet-reveal pre.sh_sourceCode {padding:.5em 1em; text-align:right;}
.snippet-wrap .snippet-num li{padding-left:1.5em;}
.snippet-wrap .snippet-no-num{list-style:none; padding:.6em 1em; margin:0;}
.snippet-wrap .snippet-no-num li {list-style:none; padding-left:0;}
.snippet-wrap .snippet-num {margin:1em 0 1em 1em; padding-left:3em;}
.snippet-wrap .snippet-num li {list-style:decimal-leading-zero outside none;}
.snippet-wrap .snippet-no-num li.box {padding:0 6px; margin-left:-6px;}
.snippet-wrap .snippet-num li.box {border:1px solid; list-style-position:inside; margin-left:-3em; padding-left:6px;}
*:first-child+html .snippet-wrap .snippet-num li.box {margin-left:-2.4em;}
* html .snippet-wrap .snippet-num li.box {margin-left:-2.4em;}
.snippet-wrap li.box-top {border-width:1px 1px 0 !important;}
.snippet-wrap li.box-bot {border-width:0 1px 1px !important;}
.snippet-wrap li.box-mid {border-width:0 1px !important;}
.snippet-wrap .snippet-num li .box-sp {width:18px; display:inline-block;}
*:first-child+html .snippet-wrap .snippet-num li .box-sp {width:27px;}
* html .snippet-wrap .snippet-num li .box-sp {width:27px;}
.snippet-wrap .snippet-no-num li.box {border:1px solid;}
.snippet-wrap .snippet-no-num li .box-sp {display:none;}

		</style>
		<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/start/jquery-ui.css" type="text/css" rel="Stylesheet" />
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" type="text/javascript"></script>
		<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js" type="text/javascript"></script>
		<script src="http://steamdev.com/snippet/js/jquery.snippet.min.js" type="text/javascript"></script>
		<script src="http://shjs.sourceforge.net/lang/sh_html.min.js" type="text/javascript"></script>
		<script src="http://shjs.sourceforge.net/lang/sh_php.min.js" type="text/javascript"></script>
		
		<script>
			jQuery(document).ready(function(){
				jQuery('.example .startclosed.collapsible').click(function() {
					jQuery(this).next().toggle('fast');	return false;
				}).next().hide();
				jQuery('.example .startopen.collapsible').click(function() {
					jQuery(this).next().toggle('fast');	return false;
				}).next().show();
				
				jQuery(".htmlcode").snippet("html",{style:"typical",showNum:false});
				jQuery(".phpcode").snippet("php",{style:"typical",showNum:false});
			});
		</script>
	</head>
	<body>
		<div class="maincontainer">

			<table style="padding:23px 0px 0px 0px; margin: 0px 0px 10px 0px; width:100%" cellpadding="0" cellspacing="0">
				<tbody>
					<tr style="height: 58px;">
						<td style="width: 9em;">
							<a href="http://code.google.com/p/phpcreatehtml/">
								<img src="http://phpcreatehtml.googlecode.com/hg/misc/phpcreatehtml.png" width="153" height="55" alt="phpcreatehtml logo">
							</a>
							</td>
						<td style="padding-left: 0.5em">
							<div class="pname">
								<a href="http://code.google.com/p/phpcreatehtml/"><span>phpcreatehtml</span></a>
							</div>
							<div class="psum">
								<a href="http://code.google.com/p/phpcreatehtml/"><span>This is a simple but powerful way to create html from php</span></a>
							</div>
						</td>
						<td style="white-space:nowrap;text-align:right; vertical-align:bottom;"></td>
					</tr>
				</tbody>
			</table>
 
			<table>
				<tr>
					<td class="sidebar">
					</td>
					<td class="exampleframe">
						<img src="http://phpcreatehtml.googlecode.com/hg/misc/phpcreatehtml_banner.png"><br>
<?php

	include "html.example.php";

?>
					</td>
				</tr>
			</table>
		</div>
		<script type="text/javascript">
		
		  var _gaq = _gaq || [];
		  _gaq.push(['_setAccount', 'UA-3452679-24']);
		  _gaq.push(['_trackPageview']);
		
		  (function() {
		    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		  })();
		
		</script>
	</body>
</html>