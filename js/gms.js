// Javascript code for gms 
// handles events and display enhancements


// Revisions
// 2012-12-24 : Comments and cleanup
// 2012-12-29 : Added all  "required" fields of Submit form (email is NOT required)
//            : use new gmstrace() function to deal with IE inability ot handle console.log()
// 2012-12-30 : Added like/dislike button and counts to grid - using fakecol from api



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
        $('#earth-img').fadeIn(5000);
        //$('#earth-img').show();


        // define submit button event handler for problem submit form
        $('#btnProblemSubmit').click(function (e) {
            e.preventDefault();
            if ($('#problem-form').valid()) {
                //console.log("valid");
				gmstrace('valid');
                addProblem();
				//$('#problem-form').resetForm();
				
            } else {
                //console.log("oops!");
				gmstrace('oops. error adding new problem form');
            }
        });

        // initialize and display for the View Submitted / Problem data grid
        createProblemDataGrid();
		
		// get recent annoncements from the DB to display on the home page
		getannouncements();

        // make comments div a modal (using Twitter Bootstrap modal widget)
        $('#commentsModal').modal({show:false});

        // click handler for comment on a problem link, opens comment div modal
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

        // click handler for save comment button
        $('#btnSaveComment').live('click', function () {
            saveComment();
        });

        // click handler for close comments modal button
        $('#btnCloseCommentModal').live('click', function () {
            // clear comment input
            $('inpComment').val('');
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
	        required: true
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
    // create a data grid to display problems
    // the ProblemDataSource object is defined in problemDatasource.js

    // here we will define the columns to display, and any speciall formatting of data on each row
    function createProblemDataGrid() {

		gmstrace('createProblemDataGrid - start');
		
        var problemDataSource = new ProblemDataSource({
            columns:[
				/*
                {
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
				// api returns a magical 'fakecol' this is used here to combine the liked, disliked values
                {
                    property:'fakecol',
                    label:'Like/Dislike',
                    sortable:false
                }
            ],
            formatter:function (items) {
                // defines how to display each element in our json object
                // for each comment, make it look like a link, and include the problem ID as a data
                // attribute so we can have it later when we want to fetch comments for that problem
				gmstrace('createProblemDataGrid - formatter');

                $.each(items, function (index, item) {
                    item.comments = "<a href='#'><span class='comment-link' data-probId='"
                        + item.idinput + "' data-probname='" + item.suggestion + "'>Comments</span></a>";
					// display liked, disliked data and allow submitting of votes using buttons and bootstrap toggle buttons
					item.fakecol = "<div id='gmslikedislike' class='btn-group' data-toggle='buttons-radio'><button type='button' class='btn btn-primary'>"
						+ "Liked " + item.liked + "</button>" 
						+ "<button type='button' class='btn btn-primary'>"
						+ "DisLiked " + item.disliked + "</button>" 
						+ "</div>";
					// allow link to the 'details' about this problem
					//item.suggestion = item.suggestion + " <a href='#'>Full Details</a>";
                });
            },
            search:''
        });

		gmstrace('createProblemDataGrid - after setup columns');

        $('#MyGrid').datagrid({
            dataSource:problemDataSource
        });

		gmstrace('createProblemDataGrid - after #Mygrid');

    }


    /* ******************************  Comments functions ***********************************  */
    // load comments from server
    function getComments(problemId) {
        //console.log('GET ting comments');
		gmstrace('GET ting comments');
        // get the DOM element for the comment list
        $listComments = $('#listComments');
        // clear the list and input
        $listComments.html('');
        // include problemId on data-probid attribute of Save Comment button, we'll
        // need the problem ID when we want to save the comment
        $('#btnSaveComment').attr('data-probid',problemId);
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

        // convert inputs to JSON string
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





