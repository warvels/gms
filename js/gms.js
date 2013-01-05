// Javascript code for gms 
// handles events and display enhancements


// Revisions
// 2012-12-24 : Comments and cleanup
// 2012-12-29 : Added all  "required" fields of Submit form (email is NOT required)
//            : use new gmstrace() function to deal with IE inability ot handle console.log()
// 2012-12-30 : Added like/dislike button and counts to grid - using fakecol from api
// 2012-12-31 : Added Details (modal popup) to show all details text about a problem
//            : Added Like and Dislike click handlers to call api 
// 2013-01-01 : Validated entered comments (length and existance)


// Use require.js library to modularize our functions
// define shortcut name for paths to JavaScript code we will be including
 require.config({
    paths:{
        'jquery':'https://ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery',
        'underscore':'http://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.3.3/underscore-min',
        'bootstrap':'lib/bootstrap',
        'fuelux':'lib/fuelux',
		'validate':'lib/validate/jquery.validate'
    }, shim: {
		// jquery.validate depends on jquery
        validate:{
            deps:["jquery"]
        }
    }
});


// include required JS libraries
// jquery and fuelux/all use global namespace.  problemDatasource is modularized, so pass it along
// to require.js and it will take care of things!
require(['jquery', 'js/problemDatasource', 'validate', 'fuelux/all' ], function ($, problemDataSource) {

    // after document is loaded, set event handlers and create grids
	// anonymous main document function
    $(document).ready(function () {

		// prepare the add a new problem form validation
		setupProblemFormValidation();

        //$('#myTab a:last').tab('show');
        //$('.hometext').fadeOut(2).fadeIn(1000);
        $('#earth-img').fadeIn(1000);
        //$('#earth-img').show();


        // define submit button event handler for problem submit form
        $('#btnProblemSubmit').click(function (e) {
			// prevent other event handlers from executing 
            e.preventDefault();
			
			var validator = $('#problem-form').valid();
			
            if  (validator) {
                //console.log("valid");
				gmstrace('Problem Submit form is Valid');
                addProblem();
				// now clear the form
				//$('#problem-form').resetForm();
				//validator.resetForm();
				//form.reset();
				//$("problem-form").focusout();
				//$('#problem-form').get(0).reset();
            } else {
                //console.log("oops!");
				gmstrace('oops. Error adding new Problem form');
            }
        });

        // initialize and display for the View Submitted / Problem data grid
        createProblemDataGrid();
		
		// get recent annoncements from the DB to display on the home page
		getannouncements();

        // make comments div a modal (using Twitter Bootstrap modal widget)
        $('#commentsModal').modal({show:false});

		// COMMENTS - showing Modal and adding more comments on Click
        //  click handler for comment on a problem link, opens comment div modal
		//  .live will create event handler for this and future clicks
        $('.comment-link').live('click', function () {
            // get problem id from data-probId attribute
            var probId = $(this).attr("data-probid");
            getComments(probId);

            // get problem name attribute from this comment link, and display in modal popup
            var probName = $(this).attr("data-probname");
            $('#spanCommentsModalProblemName').html(probName);
            // display popup (might want to move this to happen after comments retrieved from server)
            $('#commentsModal').modal('show');

        });
        // click handler for Save the new comment button in Comments Modal
        $('#btnSaveComment').live('click', function () {
            saveComment();
        });
        // click handler for close comments modal button
        $('#btnCloseCommentModal').live('click', function () {
            // clear comment input
            $('inpComment').val('');
        });

		
		// DETAILS modal btn click function - get the details for the probid and then "show" that modal popup
		$('.details-link').live('click', function () {
            // get problem id from data-probId attribute
            var probId = $(this).attr("data-probid");
            getDetails(probId);
            // get problem name attribute from this details link, and display problem and details in modal popup
            var probName = $(this).attr("data-probname");
            $('#spanDetailsModalProblemName').html(probName);
            // display popup (might want to move this to happen after comments retrieved from server)
            $('#detailsModal').modal('show');
        });

		// like / dislike button click handlers - simply increment the liked and disliked fields for this problem
		$('.like-problem').live('click', function () {
            // get problem id from data-probId attribute
            var probId = $(this).attr("data-probid");
            likeProblem(probId);
            // inform user of action taken - How do we update the like/dislike counter?
        });
		$('.dislike-problem').live('click', function () {
            // get problem id from data-probId attribute
            var probId = $(this).attr("data-probid");
            dislikeProblem(probId);
            // inform user of action taken - How do we update the like/dislike counter?
        });

	});

	
    // ****************  Submit problem functions  ***************************
	
	// use jquery.validate the validate the "add a new problem Form"
    function setupProblemFormValidation() {
        //console.log('setup problem form Validate');
		gmstrace('setup problem form Validate');
		
		$('#problem-form').validate({
	    rules: {
	      inpProblemSuggestion: {
	      	minlength: 8,
	        required: true
	      },
		  inpProblemCategory: {
	      	minlength: 8,
	        required: true
	      },
	      inpProblemEmail: {
	        email: true
	      },
		  txtProblemDetails: {
	      	minlength: 10,
	        required: false
	      }
		  
	    },
	    highlight: function(label) {
	    	$(label).closest('.control-group').addClass('error');
	    },
	    success: function(label) {
	    	label
	    		.text('OK!').addClass('valid')
	    		.closest('.control-group').addClass('success');
	    },
        onsubmit: false
	  });
    }	
	
	
    // called when submit button clicked
    // get form inputs and post to problem add API on server
    function addProblem() {
        //console.log('adding problem');
		gmstrace('adding problem');
        var data = problemFormToJSON()
        $.ajax({
            type:'POST',
            contentType:'application/json',
            url:"api/problems",
            dataType:"json",
            data:data,
            success:function (data, textStatus, jqXHR) {
                alert('Your Suggestion / Problem was added successfully');
            },
            error:function (jqXHR, textStatus, errorThrown) {
                alert('Error adding problem: ' + textStatus);
            }
        });
    }


    // get the values entered on the problem form, and package them in a JSON string
    // to use as form data to send to server
    function problemFormToJSON() {
        return JSON.stringify({
            "id":"",
            "suggestion":$('#inpProblemSuggestion').val(),
            "email":$('#inpProblemEmail').val(),
            "subjarea":$('#inpProblemCategory').val(),
            "details":$('#txtProblemDetails').val()
        });
    }


	
    // ****************  Problem datagrid functions ********************************
    // create a data-grid to display problems
    // the ProblemDataSource object is defined in problemDatasource.js

    // here we will define the columns to display, and any speciall formatting of data on each row
    function createProblemDataGrid() {

		gmstrace('createProblemDataGrid - start');
		
        var problemDataSource = new ProblemDataSource({
            columns:[
				/*
                {
                    property:'idinput',
                    label:'ID',
                    sortable:true
                },                
                    property:'nick',
                    label:'Nickname',
                    sortable:true
                },
                {
                    property:'fname',
                    label:'First Name',
                    sortable:true
                },
                {
                    property:'lname',
                    label:'Last Name',
                    sortable:true
                },
				*/
                {
                    property:'created_dt',
                    label:'Created on',
                    sortable:true
                },
				/*{
                 property:'email',
                 label:'email',
                 sortable:true
                 },*/
                {
                    property:'area',
                    label:'Category',
                    sortable:true
                },
                {
                    property:'suggestion',
                    label:'Problem',
                    sortable:true
                },
                /* hide these per George - will need some way to see the full details
				{
                    property:'details',
                    label:'Details',
                    sortable:true
                },*/
                {
                    property:'comments',
                    label:'Comments',
                    sortable:false
                },
				{	
					property:'fakedetails',
					label: 'Details', 
					sortable:false 
				},
				// Create a pseudo column that is used to combine the liked, disliked values into buttons
                {
                    property:'likeanddislike',
                    label:'Like/Dislike',
                    sortable:false
                }
				
            ],
            formatter:function (items) {
                // defines how to display each element in our json object
                // for each comment, make it look like a link, and include the problem ID as a data
                // attribute so we can have it later when we want to fetch comments for that problem
				// gmstrace('createProblemDataGrid - formatter:function');
				var tempvalue = '';
                $.each(items, function (index, item) {

					// create the details link span that contains problem id and suggestion
					tempvalue = "<a href='#'><span class='comment-link' data-probId='"
                        + item.idinput + "' data-probname='" + item.suggestion + "'>Comments</span></a>";
                    item.comments = tempvalue;

					// display liked, disliked data and allow submitting of votes using buttons from bootstrap toggle buttons
					tempvalue = "<div id='gms-like-dislike' class='btn-group' data-toggle='buttons-radio'>"
						+ "<span class='like-problem' data-probId='" + item.idinput + "'><button type='button' class='btn btn-primary' id='btnlikeProblem'>"
						+ "Like " + item.liked + "</button></span>" 
						+ "<span class='dislike-problem' data-probId='" + item.idinput + "'><button type='button' class='btn btn-primary' id='btndislikeProblem'>"
						+ "DisLike " + item.disliked + "</button></span>" 
						+ "</div>";
						// gmstrace(tempvalue);
					item.likeanddislike = tempvalue;

					// create the details link span that contains problem id and suggestion
					tempvalue = "<a href='#'><span class='details-link' data-probId='"
                        + item.idinput + "' data-probname='" + item.suggestion + "'>Details</span></a>";
					item.fakedetails = tempvalue;
					
					
					// allow link to the 'details' about this problem
					// item.suggestion = item.suggestion + " <a href='#'>Full Details</a>";
                });
            },
            search:''
        });

		//gmstrace('createProblemDataGrid - after setup columns');
		
		// Load the data from problemDatasource into the fuelex data grid
        $('#MyGrid').datagrid({
            dataSource:problemDataSource
        });

		//gmstrace('createProblemDataGrid - after #Mygrid');

    }


    /* ******************************  Comments functions ***********************************  */
    // load comments from server
    function getComments(problemId) {
        //console.log('GET ting comments');
		gmstrace('GET ting comments');
        // get the DOM element for the comment list (jquery function to find "listComments" <div> in the DOM)
        $listComments = $('#listComments');
        // clear the list and input (set the html for this div to empty)
        $listComments.html('');
		
        // include problemId on data-probid attribute of Save Comment button, we'll
        // need the problem ID when we want to save the comment
        $('#btnSaveComment').attr('data-probid',problemId);
		// clear input box for the comment(s)
        $('#inpComment').val('');

        // url to get a list of comments for a problem
        var url = "api/problems/" + problemId + "/comments";


        // fire the ajax request for comments  (this is a deferred object whose .done and .fail
        // functions don't happen until a response is received from the server
        var req = $.ajax(url, {
            dataType:'json',
            type:'GET'
        });


        // when data returned successfully, populate list
        req.done(function (response, textStatus, jqXHR) {
            // array of comments is in response
            // iterate over the list, adding each comment to the displayed list
            if (response.length) {
                $.each(response, function (i, comment) {
                    $('#listComments').append('<li>' + comment.comment_txt + '</li>');
                });
            } else {
                $('#listComments').append('<li>No comments on this issue</li>')
            }


        });

        // if request fails, display an error
        req.fail(function (jqXHR, textSTatus, errorThrown) {
            debugger;
        });


    }


    // called when submit button clicked
    // get form inputs and post to problem add API on server
    function saveComment() {
	
		// Validate the comment text (must exist and cannot be > 1000
		var comment_entered = $('#inpComment').val();
		if (!comment_entered) {
			alert('Please enter a comment or close to exit');
			return false;
		}
		if (comment_entered.length > 1000) {
			// should do this in the UI
			alert('comment truncated to 1000 characters');
			comment_entered = comment_entered.substring(0, 999);
		}
        // convert inputs to JSON string
		// created_by = 1  where 1 is the id of the fellow table - 1 is the anonymous default user
        var data = JSON.stringify({ id:"",
            comment_txt:$('#inpComment').val(),
            related_to:$('#btnSaveComment').attr('data-probid'),
            created_by:1 });

        var url = "api/comments";

        var req = $.ajax(url, {
            type:'POST',
            contentType:'application/json',
            dataType:"json",
            data:data });


        req.done(function (data, textStatus, jqXHR) {
            // clear input
            $('#inpComment').val('');
            // redisplay comments
            var probId = $('#btnSaveComment').attr('data-probid');
            getComments(probId);
        });

        req.fail(function (jqXHR, textStatus, errorThrown) {
            alert('Error adding comment');
        });

    }
	
	
	

    /* ******************************  Details functions ***********************************  */
    // load details from server based on problemID
    function getDetails(problemId) {
		gmstrace('GET ting Details on ' + problemId);

        // get the DOM element for the comment list (jquery function to find "listDetails" <div> in the DOM)
        // clear the details (set the html for this div to empty)
        //$listDetails = $('#listDetails');
        //$listDetails.html('');

		// get DOM element for the textDetails and set that html in that div to null
        $textDetails = $('#textDetails');
        $textDetails.html('');
		
        // url to get the details for a problem
        var url = "api/problems/" + problemId;

        // fire the ajax request for problem  (this is a deferred object whose .done and .fail
        //   functions don't happen until a response is received from the server)
        var req = $.ajax(url, {
            dataType:'json',
            type:'GET'
        });

        // when data returned successfully, populate html div with Details
        req.done(function (response, textStatus, jqXHR) {
            // array of details is in response
            // iterate over the list, adding each details
            if (response.details) {
                //$('#listDetails').append('<li>' + response.details + '</li>');
                $('#textDetails').html( response.details );
            } else {
                $('#textDetails').html('No additional details found for this problem.')
            }
        });

        // if request fails, display an error
        req.fail(function (jqXHR, textSTatus, errorThrown) {
            debugger;
        });


    }


	
    /* ******************************  Like / Dislike functions ***********************************  */
    // 
	function likeProblem(problemId) {
        // url to PUT the like 
        var url = "api/problems/" + problemId + "/like"
        // fire the ajax request to PUT this like onto the problem
        var req = $.ajax(url, {
            dataType:'json',
            type:'PUT'
        });

        // when data PUT  successfully = show alert and update 
        req.done(function (response, textStatus, jqXHR) {
            // check the response for the liked count and display to usre
            var liked = response.liked;
            if (liked) {
                alert('This problem has now been liked ' + liked + ' times.');
            } else {
                alert('Unable to like this problem');
            }
			
		// jsw  $('#MyGrid').datagrid({  refreshData });
			
        });

        // if request fails, display an error
        req.fail(function (jqXHR, textSTatus, errorThrown) {
            debugger;
        });		
	}
    // 
	function dislikeProblem(problemId) {
        // url to PUT the dislike 
        var url = "api/problems/" + problemId + "/dislike"
        // fire the ajax request to PUT this like onto the problem
        var req = $.ajax(url, {
            dataType:'json',
            type:'PUT'
        });

        // when data PUT  successfully = show alert and update 
        req.done(function (response, textStatus, jqXHR) {
            // check the response for the disliked count and display to user
            var disliked = response.disliked;
            if (disliked) {
                alert('This problem has now been disliked ' + disliked + ' times.');
            } else {
                alert('Unable to dislike this problem');
            }
			

        });

        // if request fails, display an error
        req.fail(function (jqXHR, textSTatus, errorThrown) {
            debugger;
        });		
	}


	
    /* ******************************  Announcements functions ***********************************  */
    // load announcements from server
    function getannouncements() {
        //console.log('GET ting announcements');
		gmstrace('GET ting announcements');
        // get the DOM element for the comment list
        $listAnnouncements = $('#listAnnouncements');
        // clear the list and input
        $listAnnouncements.html('');

        // url to get a list of the Last N announcements  (rostrums - thanks george )
        var url = "api/rostrums?last=3";

        // fire the ajax request for announcements (this is a deferred object whose .done and .fail
        // functions don't happen until a response is received from the server
        var req = $.ajax(url, {
            dataType:'json',
            type:'GET'
        });

        // when data returned successfully, populate list
        req.done(function (response, textStatus, jqXHR) {
            // array of announcements is in response
            // iterate over the list, adding each announcement to the displayed list
            var announcements = response.announcements;
            if (announcements.length) {
                $.each(announcements, function (i, announcements) {
                    // show each announcement date and text
                    $('#listAnnouncements').append('<li>' + announcements.created_on + ' <font color="white">' + announcements.our_text + '</font></li>');
                });
            } else {
                $('#listAnnouncements').append('<li>No Recent Announcements</li>')
            }

        });

        // if request fails, display an error
        req.fail(function (jqXHR, textSTatus, errorThrown) {
            debugger;
        });


    }

	
    /* ****************************** Misc Functions ***********************************  */
	// for IE, gotta catch any error for console.log (not supported)
	function gmstrace(tracestr) {
		try { console.log(tracestr) } catch (e) { 
			// only turn on the alert() if you want IE to show you a million debugging alerts.
			//alert('TRACE: ' + tracestr) 
		}
		
	}

});





