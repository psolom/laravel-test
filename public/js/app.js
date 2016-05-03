// Execute JavaScript on page load
$(function() {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // init flagStrap plugin if countries list is passed
    if(countries) {
        $('#countries-list').flagStrap({
            countries: countries,
            placeholder: {
                value: "",
                text: "Select country"
            },
            buttonType: "btn-info",
            scrollable: true,
            scrollableHeight: "250px",
            onSelect: function (value, element) {
                if(value) {
                    $('#country-code').val(value);
                }
            }
        });
    }

    // submit the form with ajax
    $('#country-form').on('submit', function(e) {
        e.preventDefault();

        var countryCode = $('#country-code').val();
        if(!countryCode) {
            alert('Please, chose a country from list.');
            return false;
        }

        $.ajax({
            url: '/site/phone',
            method: 'POST',
            data: {
                countryCode: countryCode,
                _token: $('#csrf-token').val()
            }
        }).done(function(data) {
            $('#phone-container').html(data);
        }).fail(function(error) {
            console.log(error);
        });
    });
});