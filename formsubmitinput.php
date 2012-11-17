<!-- 
 * Submit problem / Submit Input form - formsubmitinput.php
 *  Generated initially form - http://phpformgen.sourceforge.net/new_demo/phpformgen/index.php
 *  form id : form281797
 * 
-->

		<!-- main FORM Styles sheet -->
		<link href="css/styleforms.css" rel="stylesheet" type="text/css">

		<!-- calendar stuff -->
		      <link rel="stylesheet" type="text/css" href="calendar/calendar-blue2.css" />
		      <script type="text/javascript" src="css/calendar/calendar.js"></script>
		      <script type="text/javascript" src="css/calendar/calendar-en.js"></script>
		      <script type="text/javascript" src="css/calendar/calendar-setup.js"></script>
		<!-- END calendar stuff -->

	    <!-- expand/collapse function -->
	    <SCRIPT type=text/javascript>
		<!--
		function collapseElem(obj)
		{
			var el = document.getElementById(obj);
			el.style.display = 'none';
		}


		function expandElem(obj)
		{
			var el = document.getElementById(obj);
			el.style.display = '';
		}


		//-->
		</SCRIPT>
		<!-- expand/collapse function -->


		<!-- expand/collapse function -->
		    <SCRIPT type=text/javascript>
			<!--

			// collapse all elements, except the first one
			function collapseAll()
			{
				var numFormPages = 1;

				for(i=2; i <= numFormPages; i++)
				{
					currPageId = ('mainForm_' + i);
					collapseElem(currPageId);
				}
			}


			//-->
			</SCRIPT>
		<!-- expand/collapse function -->


		 <!-- validate -->
		<SCRIPT type=text/javascript>
		<!--
			function validateField(fieldId, fieldBoxId, fieldType, required)
			{
				fieldBox = document.getElementById(fieldBoxId);
				fieldObj = document.getElementById(fieldId);

				if(fieldType == 'text'  ||  fieldType == 'textarea'  ||  fieldType == 'password'  ||  fieldType == 'file'  ||  fieldType == 'phone'  || fieldType == 'website')
				{	
					if(required == 1 && fieldObj.value == '')
					{
						fieldObj.setAttribute("class","mainFormError");
						fieldObj.setAttribute("className","mainFormError");
						fieldObj.focus();
						return false;					
					}

				}


				else if(fieldType == 'menu'  || fieldType == 'country'  || fieldType == 'state')
				{	
					if(required == 1 && fieldObj.selectedIndex == 0)
					{				
						fieldObj.setAttribute("class","mainFormError");
						fieldObj.setAttribute("className","mainFormError");
						fieldObj.focus();
						return false;					
					}

				}


				else if(fieldType == 'email')
				{	
					if((required == 1 && fieldObj.value=='')  ||  (fieldObj.value!=''  && !validate_email(fieldObj.value)))
					{				
						fieldObj.setAttribute("class","mainFormError");
						fieldObj.setAttribute("className","mainFormError");
						fieldObj.focus();
						return false;					
					}

				}



			}

			function validate_email(emailStr)
			{		
				apos=emailStr.indexOf("@");
				dotpos=emailStr.lastIndexOf(".");

				if (apos<1||dotpos-apos<2) 
				{
					return false;
				}
				else
				{
					return true;
				}
			}


			function validateDate(fieldId, fieldBoxId, fieldType, required,  minDateStr, maxDateStr)
			{
				retValue = true;

				fieldBox = document.getElementById(fieldBoxId);
				fieldObj = document.getElementById(fieldId);	
				dateStr = fieldObj.value;


				if(required == 0  && dateStr == '')
				{
					return true;
				}


				if(dateStr.charAt(2) != '/'  || dateStr.charAt(5) != '/' || dateStr.length != 10)
				{
					retValue = false;
				}	

				else	// format's okay; check max, min
				{
					currDays = parseInt(dateStr.substr(0,2),10) + parseInt(dateStr.substr(3,2),10)*30  + parseInt(dateStr.substr(6,4),10)*365;
					//alert(currDays);

					if(maxDateStr != '')
					{
						maxDays = parseInt(maxDateStr.substr(0,2),10) + parseInt(maxDateStr.substr(3,2),10)*30  + parseInt(maxDateStr.substr(6,4),10)*365;
						//alert(maxDays);
						if(currDays > maxDays)
							retValue = false;
					}

					if(minDateStr != '')
					{
						minDays = parseInt(minDateStr.substr(0,2),10) + parseInt(minDateStr.substr(3,2),10)*30  + parseInt(minDateStr.substr(6,4),10)*365;
						//alert(minDays);
						if(currDays < minDays)
							retValue = false;
					}
				}

				if(retValue == false)
				{
					fieldObj.setAttribute("class","mainFormError");
					fieldObj.setAttribute("className","mainFormError");
					fieldObj.focus();
					return false;
				}
			}
		//-->
		</SCRIPT>
		<!-- end validate -->




	</head>

	<body onLoad="collapseAll()">

	<div id="mainForm">


		<div id="formHeader">
				<h2 class="formInfo">GMS Problem Submit</h2>
				<p class="formInfo">Submit your problem to the Global Mind Share</p>
		</div>

					
		<BR/><!-- begin form -->
		<form method=post enctype=multipart/form-data action=processorsubmitinput.php onSubmit="return validatePage1();">
		<ul class=mainForm id="mainForm_1">

				<li class="mainForm" id="fieldBox_1">
					<label class="formFieldQuestion">Problem OR Question&nbsp;*</label>
					<input class=mainForm type=text name=field_1 id=field_1 size='110' maxlength="100" value=''></li>

				<li class="mainForm" id="fieldBox_2">
					<label class="formFieldQuestion">Subject Area&nbsp;*</label><select class=mainForm name=field_2 id=field_2>
					<!-- You must include this null Option so the it does not default the first real option
					     Also, if not here, then users can never select the first option
					 -->
					<option value=''> </option>
					<option value="Science and Technology">Science and Technology</option>
					<option value="Human Rights">Human Rights</option>
					<option value="Education">Education</option>
					<option value="Health and Medicine">Health and Medicine</option>
					<option value="Society and Politics">Society and Politics</option>
					<option value="Economy and Finance">Economy and Finance</option>
					<option value="Environment and Climate Changes">Environment and Climate Change</option>
					<option value="Art and Culture">Art and Culture</option>
					<option value="Religion and Philosophy">Religion and Philosophy</option></select>
				</li>

				<li class="mainForm" id="fieldBox_3">
					<label class="formFieldQuestion">Email 
					<!-- &nbsp;*   adds a tool tip with "info" icon -->
					<a class=info href=#><img src=imgs/tip_small.png border=0>
					<span class=infobox>Your email is Optional. You can enter this to help identify your submission</span>
					</a>
					</label>
					<input class=mainForm type=email name=field_3 id=field_3 size=80 value="" 
					style="background-image:url(imgs/email.png); background-repeat: no-repeat;  padding: 2px 2px 2px 25px;">
				</li>

				<li class="mainForm" id="fieldBox_4">
					<label class="formFieldQuestion"> Details &nbsp;*
					<a class=info href=#><img src=imgs/tip_small.png border=0>
					<span class=infobox>Enter full details about your problem statement. <br> Input is limited to 1000 characters. </span>
					</a>
					</label>
					</label>
					<textarea class=mainForm  name=field_4 id=field_4 maxlength="1000" rows=20 cols=100></textarea>
					</li>
		
		
		<!-- end of this page -->

		<!-- page validation -->
		<SCRIPT type=text/javascript>
		<!--
			function validatePage1()
			{
				retVal = true;
				if (validateField('field_1','fieldBox_1','text',1) == false)
 retVal=false;
if (validateField('field_2','fieldBox_2','menu',1) == false)
 retVal=false;
if (validateField('field_3','fieldBox_3','email',0) == false)
 retVal=false;
if (validateField('field_4','fieldBox_4','textarea',1) == false)
 retVal=false;

				if(retVal == false)
				{
					alert('Please correct the errors.  Fields marked with an asterisk (*) are required');
					return false;
				}
				return retVal;
			}
		//-->
		</SCRIPT>

		<!-- end page validaton -->



		<!-- Captcha and Submit page buttons -->
				<li class="mainForm">
					<label class="formFieldQuestion">
						Type the following:&nbsp;
						<a class=info href=#><img src=imgs/tip_small.png border=0>
						<span class=infobox>For security purposes, please type the letters in the image. Letters are case sensitive.</span>
						</a>
						<BR><img src="CaptchaSecurityImages.php" />
					</label>
<!-- testing by JSW -->								
<p class="footer" style="color:red">Forms by Kepi - Required fields are Asterik</p>

						<input id="captchaForm" name="security_code" class="mainForm" type="text"/>
				</li>
				<li class="mainForm">
					<input id="saveForm" class="mainForm" type="submit" value="Submit" />
				</li>
			</form>
			<!-- end of form -->
		<!-- close the display stuff for this page -->
		</ul>
		</div>

		<div id="footer">
#		<p class="footer"><a class=footer href=http://phpformgen.sourceforge.net>Generated by phpFormGenerator</a></p>
		</div>
		
