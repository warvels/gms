// Javascript code for index-2.html
// handles events and display enhancements


// Use require.js library to modularize our functions

// define shortcut name for paths to JavaScript code we will be including
requirejs.config({
    paths:{
        'jquery':'https://ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery',
        'underscore':'http://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.3.3/underscore-min',
        'bootstrap':'lib/bootstrap',
        'fuelux':'lib/fuelux'
    }
});


// include required JS libraries
// jquery and fuelux/all use global namespace.  problemDatasource is modularized, so pass it along
// to require.js and it will take care of things!
require(['jquery', 'js/problemDatasource', 'fuelux/all'], function ($, problemDataSource) {

    // after document is loaded, set event handlers and create grids
    $(document).ready(function () {

        //$('#myTab a:last').tab('show');
        //$('.hometext').fadeOut(2).fadeIn(1000);
         $('#earth-img').fadeIn(5000);
        //$('#earth-img').show();


        // define submit button event handler for problem submit form
        $('#btnProblemSubmit').click(function () {
            addProblem();
        });

        // initialize and display problem data grid
        createProblemDataGrid();

        // make comments div a modal (using Twitter Bootstrap modal widget)
        $('#commentsModal').modal({show:false});

        // click handler for comment link, opens comment div modal
        $('.comment-link').live('click', function () {
            // get problem id from data-probId attribute
            var probId = $(this).attr("data-probid");
            getComments(probId);

            // get problem name attribute from this comment link, and display in modal popup
            var probName = $(this).attr("data-probname");
            $('#spanCommentsModalProblemName').html(probName);
            // display popup (might want to move this to happen after comments retrieved from server)
            //$('#commentsModal').modal('show');

        });


    });

    // ****************  Submit problem functions  ***************************

    // called when submit button clicked
    // get form inputs and post to problem add API on server
    function addProblem() {
        console.log('adding problem');
        var data = problemFormToJSON()
        $.ajax({
            type:'POST',
            contentType:'application/json',
            url:"api/problems",
            dataType:"json",
            data:data,
            success:function (data, textStatus, jqXHR) {
                alert('Suggestions added successfully');
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

        var problemDataSource = new ProblemDataSource({
            columns:[
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
                    label:'Suggestion',
                    sortable:true
                },
                {
                    property:'details',
                    label:'Details',
                    sortable:true
                },
                {
                    property:'comments',
                    label:'Comments',
                    sortable:false
                }
            ],
            formatter:function (items) {
                // defines how to display each element in our json object
                // for each comment, make it look like a link, and include the problem ID as a data
                // attribute so we can have it later when we want to fetch comments for that problem
                $.each(items, function (index, item) {
                    item.comments = "<a href='#'><span class='comment-link' data-probId='"
                        + item.idinput + "' data-probname='" + item.suggestion + "'>Comments</span></a>";
                });
            },
            search:''
        });

        $('#MyGrid').datagrid({
            dataSource:problemDataSource
        });

    }

    // load comments from server
    function getComments(problemId) {
        console.log('GETting comments');
        // get the DOM element for the comment list
        $listComments = $('#listComments');
        // clear the list
        $listComments.html('');

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
            // array of comments is in response.comments
            // iterate over the list, adding each comment to the displayed list
            if (response.length) {
                $.each(response, function (i, comment) {
                    $('#listComments').append('<li>' + comment.comment_txt + '</li>');
                });
            } else {
                $('#listComments').append('<li>No comments on this issue</li>')
            }

            $('#commentsModal').modal('show');

        });

        // if request fails, display an error
        req.fail(function (jqXHR, textSTatus, errorThrown) {
            debugger;
        });

        /*
         // do this every time (not used)
         req.always(function(jqXHR, textSTatus, errorThrown ){
         debugger;
         });
         */
    }


});





