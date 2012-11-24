var ProblemDataSource = function (options) {
    this._formatter = options.formatter;
    this._columns = options.columns;
};

ProblemDataSource.prototype = {

    /**
     * Returns stored column metadata
     */
    columns:function () {
        return this._columns;
    },

    /**
     * Called when Datagrid needs data. Logic should check the options parameter
     * to determine what data to return, then return data by calling the callback.
     * @param {object} options Options selected in datagrid (ex: {pageIndex:0,pageSize:5,search:'searchterm'})
     * @param {function} callback To be called with the requested data.
     */
    data:function (options, callback) {

        // var url = 'http://api.flickr.com/services/rest/?method=flickr.photos.search&api_key=d6d798f51bbd5ec0a1f9e9f1e62c43ab&format=json';
        var url = 'api/problems';
        var self = this;

        if (options.search) {


            // TODO:  add search functionality

            // Flickr example had these params  (see http://dailyjs.com/2012/10/29/fuel-ux/ )
            // url += '&tags=' + options.search;
            // url += '&per_page=' + options.pageSize;
            // url += '&page=' + (options.pageIndex + 1);

            $.ajax(url, {
                dataType:'json',
                type:'GET'

            }).done(function (response) {

                    // Prepare data to return to Datagrid
                    //debugger;
                    var data = response.problem;
                    var count = 50;
                    var startIndex = 1;
                    var endIndex = 50;
                    var start = 1;
                    var end = 50;
                    var pages = 1;
                    var page = 1;

                    /* code from example Flickr datasource
                     var data = response.photos.photo;
                     var count = response.photos.total;
                     var startIndex = (response.photos.page - 1) * response.photos.perpage;
                     var endIndex = startIndex + response.photos.perpage;
                     var end = (endIndex > count) ? count : endIndex;
                     var pages = response.photos.pages;
                     var page = response.photos.page;
                     var start = startIndex + 1;
                     */
                    // Allow client code to format the data
                    if (self._formatter) self._formatter(data);

                    // Return data to Datagrid
                    callback({ data:data, start:start, end:end, count:count, pages:pages, page:page });

                });

        } else {

            // initial load of data without any filters
            $.ajax(url, {

                dataType:'json',
                type:'GET'

            }).done(function (response) {

                    // Prepare data to return to Datagrid
                    //debugger;
                    var data = response.problem;
                    var count = 50;
                    var startIndex = 1;
                    var endIndex = 50;
                    var start = 1;
                    var end = 50;
                    var pages = 1;
                    var page = 1;


                    // Allow client code to format the data
                    if (self._formatter) self._formatter(data);

                    // Return data to Datagrid
                    callback({ data:data, start:start, end:end, count:count, pages:pages, page:page });

                });



        }
    }
};