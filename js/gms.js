// Javascript code for index-2.html
// handles events and display enhancements


// Use require.js library to modularize our functions

// define shortcut name for paths to JavaScript code we will be including
requirejs.config({
    paths:{
        'jquery':'https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min',
        'underscore':'http://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.3.3/underscore-min',
        'bootstrap':'lib/bootstrap',
        'fuelux':'lib/fuelux'
    }
});


// include required JS libraries
// jquery and fuelux/all use global namespace.  problemDatasource is modularized, so pass it along
// to require.js and it will take care of things!
require(['jquery', 'fuelux/all', 'js/problemDatasource'], function (problemDataSource) {

    //$('#myTab a:last').tab('show');
    $('.hometext').fadeOut(2).fadeIn(1000);
    $('img').fadeOut(2).fadeIn(5000);


    // define submit button event handler for problem submit form
    $('#btnProblemSubmit').click( function(){
        addProblem()
    });

    // initialize and display problem data grid
    createProblemDataGrid();




    // ****************  Submit problem functions  ***************************

    // called when submit button clicked
    // get form inputs and post to problem add API on server
    function addProblem() {
        console.log('adding problem');
        var data = problemFormToJSON()
        $.ajax({
            type: 'POST',
            contentType: 'application/json',
            url: "api/problems",
            dataType: "json",
            data: data,
            success: function(data, textStatus, jqXHR){
                alert('Suggestions added successfully');
            },
            error: function(jqXHR, textStatus, errorThrown){
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
                {
                    property:'email',
                    label:'email',
                    sortable:true
                },
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
                }
            ],
            formatter:function (items) {
                $.each(items, function (index, item) {
                    item.area = item.area;
                    item.suggestion = item.suggestion;
                });
            },
            search:'cat'
        });

        $('#MyGrid').datagrid({
            dataSource:problemDataSource
        });

    }



});





