{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.4.5/jquery-ui-timepicker-addon.min.js"></script>
<script src="//cdn.ckeditor.com/4.5.4/full/ckeditor.js"></script> --}}

<!-- jQuery -->
<script src="/dist/plugins/jquery/jquery.min.js"></script>
{{-- <script src="https://code.jquery.com/ui/1.11.3/jquery-ui.min.js"></script> --}}

<!-- Bootstrap -->
<script src="/dist/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Bootstrap Select -->
<script src="/dist/plugins/bootstrap-select/bootstrap-select.js"></script>

<!-- MDB -->
{{-- <script src="/dist/plugins/mdb/js/jquery.min.js"></script> --}}
{{-- <script src="/dist/plugins/mdb/js/popper.min.js"></script> --}}
{{-- <script src="/dist/plugins/mdb/js/bootstrap.min.js"></script> --}}
{{-- <script src="/dist/plugins/mdb/js/mdb.min.js"></script> --}}


<script src="/dist/plugins/materialize/js/materialize.min.js"></script>



<!-- QuickAdmin -->
<script src="{{ url('quickadmin/js') }}/main.js"></script>

<!-- AdminLTE -->
<script src="/dist/js/adminlte.js"></script>


{{-- <script src="{{ url('quickadmin/js') }}/timepicker-add-on.js"></script>
<script src="{{ url('quickadmin/js') }}/timepicker.js"></script> --}}


<!-- Datatables -->
<script src="/dist/plugins/datatables/jquery.dataTables.js"></script>
<script src="/dist/plugins/datatables/dataTables.bootstrap4.js"></script>
<script type="text/javascript" src="/dist/plugins/datatables/dataTables.pipelining.js"></script>

{{-- <script src="/dist/plugins/mdb/js/addons/datatables.min.js"></script>
<script src="/dist/plugins/mdb/js/addons/datatables-select.min.js"></script> --}}

<!-- iCheck -->
{{-- <script src="/dist/plugins/iCheck/icheck.min.js"></script> --}}

<!-- Daterangepicker -->
<script src="/dist/plugins/daterangepicker/moment.min.js"></script>
<script src="/dist/plugins/daterangepicker/daterangepicker-v2.js"></script>
<script src="/dist/plugins/datetimepicker/js/bootstrap-datetimepicker.min.js"></script>

<!-- Kratik Fileinput -->
<script type="text/javascript" src="/dist/plugins/kratik-bootstrap-fileinput/js/fileinput.min.js"></script>

<!-- jQuery Multi Se    lect -->
{{-- <script src="/dist/plugins/multiselect/jquery.multi-select.js"></script> --}}


<!-- Toastr -->
<script src="/dist/plugins/toastr/js/toastr.min.js"></script>

<script src="/js/utils.js"></script>
<script src="/js/datatables.js"></script>
<script src="/js/autocomplete.js"></script>




<?php

$approverObj = new App\Models\Approvers();
$modules = $approverObj->modules;

?>
<script type="text/javascript">
    $(document).ready(function(){
        //initialize all modals

        $(".date_and_time_picker").daterangepicker({
            timePicker: true,
            locale: {
                format: 'MMM DD, YYYY hh:mm A'
            },
        });

        $('.datetimepicker').daterangepicker({
            singleDatePicker: true,
            locale: {
                format: 'MMM DD, YYYY '
            },
            minDate: moment(),
        });

        $('.datepicker').daterangepicker({
            singleDatePicker: true,
            locale: {
                format: 'MMM DD, YYYY'
            },
            minDate: moment()
        });

    });

</script>