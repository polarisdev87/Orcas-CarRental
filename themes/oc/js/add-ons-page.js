

        $(document).ready(function() {
            var input = $('.count input');
            $('.count .plus:first-of-type').on('click', function() {
                var val = parseInt(input.val(), 10);
                input.val(val + 1)
            });
            $('.count .minus:last-of-type').on('click', function() {
                var val = parseInt(input.val(), 10);
                if (val > 1) {
                    input.val(val - 1)
                }
            });
        });



