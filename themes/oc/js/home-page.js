jQuery(function($) {

    var stmToday = new Date();
    var stmTomorrow = new Date(+new Date() + 86400000);
    var stmStartDate = false;
    var stmEndDate = false;
    var startDate = false;
    var endDate = false;
    var dateFormatHide = 'M/D/YYYY';

    var allowTimes = [
        '06:00',
        '07:00',
        '08:00',
        '09:00',
        '10:00',
        '11:00',
        '12:00',
        '13:00',
        '14:00',
        '15:00',
        '16:00',
        '17:00',
        '18:00',
        '19:00',
        '20:00',
        '21:00',
        '22:00',
        '23:00'
    ];

    $('#car_rental_pickup_date').datetimepicker({
        timepicker: false,
        format: "m/d/Y",
        defaultDate: stmToday,
        value: stmToday,
        formatDate: "m/d/Y",
        scrollInput: false,
        defaultSelect: true,
        closeOnDateSelect: false,
        lang: 'en',
        onGenerate: function( ct, $i ) {
            $("input[name='pickup_date']").val(moment(ct).format(dateFormatHide));
        },
        onShow: function( ct ) {
            stmEndDate = $('#car_rental_return_date').val() ? moment($('#car_rental_return_date').val()).format(dateFormatHide) : moment(stmTomorrow).format(dateFormatHide);

            if (stmEndDate) {
                this.setOptions({
                    minDate: new Date(),
                    highlightedDates: [stmEndDate + ", Return Date, car-rental-form__highlight"]
                    // maxDate: stmEndDate
                });
            }
        },
        onSelectDate: function() {
            $('#car_rental_pickup_date').datetimepicker('close');
        },
        onClose: function( ct,$i ) {
            startDate = ct;
            $('#car_rental_pickup_date').attr('data-dt-hide', moment(ct).format(dateFormatHide));

            $("input[name='pickup_date']").val(moment(startDate).format(dateFormatHide));
        }
    });

    $('#car_rental_return_date').datetimepicker({
        timepicker: false,
        format: "m/d/Y",
        defaultDate: stmTomorrow,
        minDate: stmTomorrow,
        value: stmTomorrow,
        scrollInput: false,
        formatDate: "m/d/Y",
        defaultSelect: true,
        closeOnDateSelect: false,
        lang: 'en',
        onGenerate: function( ct, $i ) {
            $("input[name='return_date']").val(moment(ct).format(dateFormatHide));
        },
        onShow:function( ct ){
            stmStartDate = $('#car_rental_pickup_date').val() ? moment(startDate).format(dateFormatHide) : false;
            if(stmStartDate) {
                stmStartDate = stmStartDate.split(' ');
                stmStartDate = new Date(stmStartDate[0]);
            } else {
                stmStartDate = new Date();
            }
            stmStartDate.setDate(stmStartDate.getDate() + 1);
            //if($('.stm-date-timepicker-end').val()) stmStartDate = new Date($('.stm-date-timepicker-end').val().split(' ')[0]);
            this.setOptions({
                minDate: stmStartDate,
                defaultDate: stmStartDate,
                value: stmStartDate
            })
        },
        onSelectDate: function() {
            $('#car_rental_return_date').datetimepicker('close');
        },
        onClose: function( ct, $i ) {
            endDate = ct;

            $("input[name='return_date']").val(moment(ct).format('M/D/YYYY'));

            $('#car_rental_return_date').attr('data-dt-hide', moment(ct).format(dateFormatHide));
        }
    });

    $('#car_rental_pickup_time').datetimepicker({
        datepicker: false,
        defaultTime: '12:00',
        value: '12:00',
        format: "g:i A",
        formatTime: "g:i A",
        defaultSelect: false,
        closeOnDateSelect: false,
        timeHeightInTimePicker: 40,
        validateOnBlur: false,
        fixed: false,
        allowTimes: allowTimes,
        onGenerate: function( ct, $i ) {
            $("input[name='pickup_time']").val(moment(ct).format('HH:mm:ss'));
        },
        onClose: function( ct, $i ) {
            $("input[name='pickup_time']").val(moment(ct).format('HH:mm:ss'));
        }
    });

    $('#car_rental_return_time').datetimepicker({
        datepicker: false,
        defaultTime: '9:00',
        value: '9:00',
        format: "g:i A",
        formatTime: "g:i A",
        defaultSelect: false,
        closeOnDateSelect: false,
        timeHeightInTimePicker: 40,
        validateOnBlur: false,
        fixed: false,
        allowTimes: allowTimes,
        onGenerate: function( ct, $i ) {
            $("input[name='return_time']").val(moment(ct).format('HH:mm:ss'));
        },
        onClose: function( ct, $i ) {
            $("input[name='return_time']").val(moment(ct).format('HH:mm:ss'));
        }
    });

    $('#car_rental_pickup_location').on('change', function() {
        let checkedReturnLocation = $('#car_rental_same_return').is(':checked');

        if (checkedReturnLocation) {
            $('#car_rental_return_location').val(this.value);
        }
    });

    $('#car_rental_form button').click(function(event){
        let pickupLocation = $('#car_rental_pickup_location').val();
        let returnLocation = $('#car_rental_return_location').val();
        let sameReturnPickupLocation = $('#car_rental_same_return').is(':checked');
        let errors = [];

        //clear previous errors
        $('.car-rental-form__errors').html('');

        if (!pickupLocation) {
            errors.push('Please select a pickup location.');
        }

        if (!returnLocation && !sameReturnPickupLocation) {
            errors.push('Please select a return location.');
        }

        if (errors.length > 0) {
            event.preventDefault();
            $.each(errors, function (index, error) {
                $('.car-rental-form__errors').append('<p class="car-rental-form__error">' + error + '</p>');
            });
        }
    });

    $('#car_rental_same_return').click(function(){
        if ($(this).is(':checked')){
            $('.car-rental-form__location-pickup-selects').removeClass('car-rental-form__location-pickup-selects__pair');
            $('.car-rental-form__location-pickup-label__return-text').show();
        } else {
            $('.car-rental-form__location-pickup-selects').addClass('car-rental-form__location-pickup-selects__pair');
            $('.car-rental-form__location-pickup-label__return-text').hide();
        }
    });

    $('.car-rental-form__location-same__return').click(function(){
        $('#car_rental_same_return').trigger('click');
        $('#car_rental_return_location').val($('#car_rental_pickup_location').val());
    });

    $('.car-rental-form__have-promocode').click(function(){
        $(this).toggleClass('checked');

        if ($(this).hasClass('checked')){
            $('.car-rental-form__promocode').slideUp();
        } else {
            $('.car-rental-form__promocode').slideDown();
        }
    });

    $('.car-rental-form__select').on('change', function() {
        //clear previous errors
        $('.car-rental-form__errors').html('');
    });
});