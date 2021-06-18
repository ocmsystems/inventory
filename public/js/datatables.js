var DataTableListing = {
    selector: '.datatable',
    options: {
        
        retrieve: true,
        "pageLength": 10,
        conditionalPaging: true,
        "serverSide": true,
        "order": [],
        "drawCallback": function(settings) {
    
            if($('table#datatable td').hasClass('dataTables_empty')){
                $('#datatable_paginate').hide();
            }else{
                $('#datatable_paginate').show();
            }
            $("#datatable_overlay").hide();
    
        },
        "preDrawCallback": function(settings){
            $("#datatable_overlay").show();
        },
        "fnRowCallback": function ( nRow, aData, iDisplayIndex ) {
            nRow.setAttribute('data-link', aData['link']);
            return nRow;
        }
    },
    ajaxURL: '',
    
    init: function(){
        
        if(DataTableListing.ajaxURL != ''){

            DataTableListing.options['ajax'] = $.fn.dataTable.pipeline( {
                "url": DataTableListing.ajaxURL,
                'pages': 10,
                "method": "POST",
                "headers": {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                "data": function(d){
                    d['_token'] = $('meta[name="csrf-token"]').attr('content');
                }
            } );
        }
        
        $(DataTableListing.selector).DataTable(DataTableListing.options);
        $('.dataTables_length').addClass('bs-select');

    },






}