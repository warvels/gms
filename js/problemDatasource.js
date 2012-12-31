// Javascript code for gms Problems data grids
// this defines the data source that will provide an array of data for the Problems data grid
// we'll keep a local buffer, and fetch from the server with an ajax call when we need
// data not in the buffer
//
// Revisions
// 2012-12-30 : Added unique 'self' variables in getPage() - for IE bullshit

var ProblemDataSource = function (options) {
    this._formatter = options.formatter;
    this._columns = options.columns;
    this.buffer = { empty:true, response:{} };
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
        var self = this;
		//var d = new Date(); ?_=' + d.getTime();   -- maybe help with IE caching of ajax url 
        var url = 'api/problems?status=approved'

        if (options.search) {

            // TODO:  add search functionality

            // Flickr example had these params  (see http://dailyjs.com/2012/10/29/fuel-ux/ )
            // url += '&tags=' + options.search;
            // url += '&per_page=' + options.pageSize;
            // url += '&page=' + (options.pageIndex + 1);
            $.ajax(url, {
                dataType:'json',
				async: false,
				cache: false, 					
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
					 
                    // Allow client code to format the data - this is where "formatter" in gms.js occurrs.
                    if (self._formatter) self._formatter(data);

                    // Return data to Datagrid
                    callback({ data:data, start:start, end:end, count:count, pages:pages, page:page });

                });

        } else {

			// Normal - non-search
		
            if (self.buffer.empty) {
                // initial load of data without any filters
                $.ajax(url, {
                    dataType:'json',
					async: false,
					cache: false, 					
                    type:'GET'
                }).success(function (response) {
					self.buffer.response = response;
					self.getPage( options, callback)
                 }).fail(function(response) {
					alert('System Failure - unable to load current Problems known to mankind')
				 });
				 

            } else {
				// buffer already loaded. 
				self.getPage( options, callback )
            }
        }
    },
	
	
	
	// Function to Load and format the current page of problems
    getPage:function (options, callback) {
	
		// must use a unique varaible name for self (to support IE)
        var selfgP = this;
        var response = selfgP.buffer.response;
        // Prepare data to return to Datagrid
        //debugger;
        var data = response.problem;
        var count = response.problem.length;
        // save to buffer in DataSource object, so we don't have to
        // hit server for every page
        selfgP.buffer.response = response;
        selfgP.buffer.empty = false;

        var startIndex = options.pageIndex * options.pageSize;
        var endIndex = startIndex + options.pageSize;
        var end = (endIndex > count) ? count : endIndex;
        var pages = Math.ceil(count / options.pageSize);
        var page = options.pageIndex + 1;
        var start = startIndex + 1;

        data = data.slice(startIndex, endIndex);

        // Allow client code to format the data
        if (selfgP._formatter) {
			selfgP._formatter(data);
		}

        // Return data to Datagrid
        callback({ data:data, start:start, end:end, count:count, pages:pages, page:page });

    }
};