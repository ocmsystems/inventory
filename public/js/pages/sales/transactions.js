var SALES_TRANSACTIONS = {

    init: function(){
        SALES_TRANSACTIONS.events.init();
    },

    events: {
        init: function(){
            SALES_TRANSACTIONS.events.lookup();
            SALES_TRANSACTIONS.events.quaggaListener();
            SALES_TRANSACTIONS.events.barcodeChange();
        },
        lookup: function(){
            $("#interactive").hide();
            $('.lookup').click(function(){
            
                if( $("#warehouse_id").val() != '' ){
                    $("#interactive").show();
                    
                    SALES_TRANSACTIONS.events.initializeQuagga();

                }else{
                    alert("You must select store first!.");
                }
            });
        },

        initializeQuagga: function(){
            
            Quagga.init({
                inputStream : {
                    name : "Live",
                    type : "LiveStream",
                    constraints: {
                        width: '640',
                        height: '480',                  
                        facingMode: "environment"  //"environment" for back camera, "user" front camera
                    }
                },
                decoder : {
                    readers :["code_128_reader","code_39_reader", "ean_reader", "ean_8_reader", "code_39_vin_reader", "codabar_reader", "upc_reader", "code_93_reader"],
                    debug: {
                        drawBoundingBox: true,
                        showFrequency: true,
                        drawScanline: true,
                        showPattern: true
                    },
                },
                numOfWorkers: 8, 
                frequency: 10,
                locate: true,
                locator: {
                    patchSize: 'large',
                    halfSample: false,
                },
            }, function(err) {
                if (err) {
                    console.log(err);
                    return
                }

                Quagga.start();
            });

        },

        quaggaListener: function(){
            Quagga.onDetected(function(result) {
                var last_code = result.codeResult.code;  
                $("label[for='barcode']").addClass("active");
                
                SALES_TRANSACTIONS.events.barcodeRequest(last_code);
            });
            
        },

        barcodeChange: function(){
            
            $("#barcode").change(function(){
                var last_code = $(this).val();  
                SALES_TRANSACTIONS.events.barcodeRequest(last_code);
            });

        },

        barcodeRequest: function(last_code){

            $("#barcode").val(last_code);
            UTILS.ajaxRequest({
                type: 'GET',
                data: {
                    barcode: last_code,
                    warehouse_id: $("#warehouse_id").val()
                },
                url: config.route.product_barcode
            }, SALES_TRANSACTIONS.events.barcodeCallback);

        },

        barcodeCallback: function(params, response){
            if( Object.keys(response.results).length > 0){
                SALES_TRANSACTIONS.build.product_description(response.results);
                $("input[name='product_id']").val(response.results.id);
                $("input[type='submit'][value='Submit for validation']").prop("disabled", false);

                $("#interactive").hide();
                Quagga.stop();
            // }else{
            //     alert("NO product is registered to that barcode. Please try again..");
            }
        },

    },

    build: {

        product_description: function(product){
            var html = '';
            html += '<div class="card">';
                html += '<div class="card-header no-border">';
                    html += '<div class="row">';
    
                        html += '<div class="col-4 col-lg-1">';
                            html += '<div class="row">';
                                html += '<div class="col-xs-4 col-lg-12"><img src="/uploads/'+product.image+'" style="width: 100%;"/></div>';
                            html += '</div>';
                        html += '</div>';
    
    
    
                        html += '<div class="col col-lg-8" style="font-size: 15px;">';
                            
                            html += '<div class="row">';
                                html += '<div class="col-sm-10 col-lg-6">';
                                    html += '<div class="input-field"><label class="active">SKU</label><span class="text-bold">' + product.sku + '</span></div>';
                                html += '</div>';
                            html += '</div>';
                        
                            html += '<div class="row">';
                                html += '<div class="col-sm-10 col-lg-6">';
                                    html += '<div class="input-field"><label class="active">Name</label><span class="text-bold">' + product.name + '</span>';
                                        if(typeof product.discount !== 'undefined'){
                                            html += '<span class="bg-success" style="font-size: 10px;">On sale</span>';
                                        }
                                    html += '</div>';
                                html += '</div>';
                            html += '</div>';
    
                            
                        
                            html += '<div class="row">';
                                html += '<div class="col-sm-10 col-lg-6">';
                                    html += '<div class="input-field"><label class="active">Description</label><span class="text-bold">' + product.description + '</span></div>';
                                html += '</div>';
                            html += '</div>';
    
                        
                        html += '<div class="row">';
                            html += '<div class="col-sm-10 col-lg-6">';
                                html += '<div class="input-field"><label class="active">Price</label><span class="text-bold">' + product.original_price + '</span></div>';
                            html += '</div>';
                        html += '</div>';
                        
                        if(typeof product.discount !== 'undefined'){
    
                            html += '<div class="row">';
                                html += '<div class="col-sm-10 col-lg-6">';
                                    html += '<div class="input-field"><label class="active">Discount</label><span class="text-bold">' + product.discount + '%</span></div>';
                                html += '</div>';
                            html += '</div>';
    
                            html += '<div class="row">';
                                html += '<div class="col-sm-10 col-lg-6">';
                                    html += '<div class="input-field"><label class="active">Discounted Price</label><span class="text-bold">' + product.discounted_price + '</span></div>';
                                html += '</div>';
                            html += '</div>';
                        }
                    html += '</div>';
                html += '</div>';
            html += '</div>';
    
            $("#product_details").html(html);
        }

    },
}


$(document).ready(SALES_TRANSACTIONS.init);