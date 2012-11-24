requirejs.config({
    paths:{
        'jquery':'https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min',
        'underscore':'http://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.3.3/underscore-min',
        'bootstrap':'lib/bootstrap',
        'fuelux':'lib/fuelux'
    }
});


// include required JS libraries
require(['jquery', 'fuelux/all', 'js/problemDatasource'], function (problemDataSource) {

    //$('#myTab a:last').tab('show');
    $('.hometext').fadeOut(2).fadeIn(1000);
    $('img').fadeOut(2).fadeIn(5000);


    // DATAGRID
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
            },           {
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


    /*
     // SEARCH CONTROL
     $('#MySearch').on('searched', function (e, text) {
     alert('Searched: ' + text);
     });


     // PILLBOX
     $('#btnAdd').click(function() {
     $('#MyPillbox ul').append('<li>Item Eight</li>');
     });

     $('#btnRemove').click(function() {
     $('#MyPillbox li[data-value="foo"]').remove();
     });

     $('#btnItems').click(function() {
     var items = $('#MyPillbox').pillbox('items');
     console.log(items);
     });




     */

});





