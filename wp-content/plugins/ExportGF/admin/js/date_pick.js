jQuery(document).ready(function($){
    $(function() {
        $( "#export_date_start" ).datepicker({
            defaultDate: "+1w",
            changeMonth: true,
            numberOfMonths: 1,
            onClose: function( selectedDate ) {
                $( "#export_date_end" ).datepicker( "option", "minDate", selectedDate );
            }
        });
        $( "#export_date_end" ).datepicker({
            defaultDate: "+1w",
            changeMonth: true,
            numberOfMonths: 1,
            onClose: function( selectedDate ) {
                $( "#export_date_start" ).datepicker( "option", "maxDate", selectedDate );
            }
        });
    });
});