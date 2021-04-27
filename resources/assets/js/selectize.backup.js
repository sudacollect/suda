    // $('input.selectize').selectize({
    //     delimiter: ',',
    //     plugins: ['remove_button'],
    //     persist: false,
    //     create: function(input) {
    //         return {
    //             value: input,
    //             text: input
    //         }
    //     }
    // });
    // $('select[selectize="true"]').selectize({
    //     create: false,
    //     // sortField: 'text'
    // });
    
    
    
    // $('select[selectize="multiple"]').selectize({
    //     plugins: ['remove_button'],
    //     maxItems: null,
    //     persist:false,
    //     hideSelected:true, 
    // });


    // $.fn.selectTag = function(options){

    //     var el = this;

    //     var default_options = {
    //         taxonomy: 'post_tag',
    //         maxItems: 5,
    //         create: false,
    //         url: suda.link('sdone/tags/search/json'),
    //         method: 'POST',
    //     };
        
    //     var settings = $.extend({}, default_options,options);
        

    //     var selectize_options = {
    //         maxItems: settings.maxItems,
    //         valueField: 'name',
    //         labelField: 'name',
    //         searchField: 'name',
    //         plugins:['remove_button'],
    //         options: [],
    //         create: settings.create,
    //         render: {
    //             option: function(item, escape) {
    //                 return '<div><span>'+item.name+'</span></div>';
    //             },
    //         },
    //         load: function(query, callback) {
    //             if (!query.length){
    //                 return callback();
    //             }
                
    //             $.ajax({
    //                 url: settings.url,
    //                 type: settings.method,
    //                 dataType: 'json',
    //                 data: {
    //                     taxonomy: settings.taxonomy,
    //                     name: query,
    //                     page_limit: 10,
    //                     _token: suda.data('csrfToken')
    //                 },
    //                 error: function(xhr) {
    //                     callback();
    //                 },
    //                 success: function(res) {
    //                     callback(res.tags);
    //                 },
    //             });
    //         }
    //     };

    //     $(el).selectize(selectize_options);

    // };