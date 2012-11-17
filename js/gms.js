requirejs.config({
    paths: {
        'jquery': 'https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min',
        'underscore': 'http://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.3.3/underscore-min',
        'bootstrap': 'lib/bootstrap',
        'fuelux': 'lib/fuelux'
    }
});

require(['jquery', 'sample/data', 'sample/datasource', 'fuelux/all'], function ($, sampleData, StaticDataSource) {
//require(['jquery', 'fuelux/all'], function ($, sampleData, StaticDataSource) {


    //$('#myTab a:last').tab('show');
    $('.hometext').fadeOut(2).fadeIn(1000);
    $('img').fadeOut(2).fadeIn(5000);



    // DATAGRID
    var dataSource = new StaticDataSource({
        columns: [{
            property: 'toponymName',
            label: 'Name',
            sortable: true
        }, {
            property: 'countrycode',
            label: 'Country',
            sortable: true
        }, {
            property: 'population',
            label: 'Population',
            sortable: true
        }, {
            property: 'fcodeName',
            label: 'Type',
            sortable: true
        }],
        data: sampleData.geonames,
        delay: 250
    });

    $('#MyGrid').datagrid({
        dataSource: dataSource
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




$(function () {



})
