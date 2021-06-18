var UTILS = {


    ajaxRequest: function(requestData, callbackFunc){

        $.ajax({
            url: requestData['url'],
            data : requestData['data'],
            type: requestData['type'],
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: "json",
            success : function(response){

                if(typeof requestData['callback'] != 'undefined'){
                    var callback = requestData['callback'];
                    callback(requestData, response);
                }else{

                    if(typeof callbackFunc != 'undefined'){
                        callbackFunc(requestData, response);
                    }

                }
            },
            error : function(response){

                if(typeof requestData['errCallback'] != 'undefined'){
                    var callback = requestData['errCallback'];
                    callback(requestData, response);
                }

            }

        });

    },

     decode: function(html) {
        var txt = document.createElement("textarea");
        txt.innerHTML = html;
        return txt.value;
    }

}