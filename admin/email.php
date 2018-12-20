<html>
				<head>
					<title></title>
		  	</head>
				<body>
			 	<div style='width:88%; color: #fff; padding:0px 0 15px 0; background-color: #68C5F1;'>
		     <div style='
		     	background-color: #fefefe;
		     	border-bottom:2px solid #2A63BD; 
		     	padding:7px 1% 7px;
		     	margin-bottom:15px;
		     	text-align: left; 
		     	'>
		       <img src='". $url ."images/logo.png' style='height:75px; width:75px;'/>
		       <a href='$url' style='text-decoration:none; color:#0e1d54;'>
						<span style='
							color:#2A63BD;
							display: block;
							font-size:20px;
							font-size:1.25rem;
							font-weight: bold;
							text-decoration: none;
						'>
						 	Membership Application Portal
						 </span>
					 </a>
			 	 </div>
			 <div style='padding:5px 1%; color:#fff; font-size:12px; font-family:Arial;'>
			 	<p style='margin-bottom:20px;'>Good Day Sir/Madam</p>
					<p style='margin-bottom:8px;'>
						$congraMsg You will need to click on the link below to do that or copy and paste in the address bar of your 
						browser to do same.
						<br/>
						$mailUrl<br/>
						$loginEmail<br/>
						$defaultPassword<br/>
					</p>
					<p style='margin-bottom:8px;'>
						<span style='font-weight:bold;'>NB</span><br/>
					   If you did not $requestRegister for an account at {$emailParameter['url']} please ignore this mail. Please do 
					   not reply to this mail as it is sent from an unmonitored address. You can contact us via {$emailParameter['contactemail']}
					</p>
				</div>
				<div style='margin-bottom:60px; margin-top:30px; padding: 0px 1%;'>
			   	IT Admin<br/>
			   	<a href='$url' style='color:#f0f0f0;'>For Membership Application Portal</a>
			 	</div>
			 	
				<div style='font-size:9px; background-color: #fefefe; border:1px solid #2A63BD; padding-top:10px; padding-bottom:10px;'>
					<div style='font-size:9px; float:left; color:#999; padding-left:5px' >
						Developed by <a style='color:#999; text-decoration:none;' rel='nofollow' href=''>
			     	ProIT Limited</a>
					</div>
					<div style='font-size:9px; float:right; color:#999; padding-right:5px' >
						&copy; ".date("Y")." NIM
					</div>
					<div style='clear:both;'></div>
				</div>
				</div>
				</body>
				</html>