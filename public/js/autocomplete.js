var AutoCompleteField = {
    ajaxURL: '',
    targetElem: '.advancedAutoComplete',
    options: {
        resolver: 'custom',
        minLength: 2,
        events: {
            search: function (qry, callback) {
                // let's do a custom ajax call
                $.ajax(AutoCompleteField.ajaxURL,
                    {
                        data: { 'qry': qry}
                    }
                ).done(function (res) {
                    callback(res.results)
                });
            }
        }
    },


    init: function(){

        $(AutoCompleteField.targetElem).autoComplete(AutoCompleteField.options);

    }

}