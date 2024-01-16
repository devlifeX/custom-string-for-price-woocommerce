jQuery(document).ready(function ($) {
    $('#product-search').select2({
        ajax: {
            url: admin_autocomplete_params.ajax_url,
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    action: 'admin_autocomplete_search',
                    nonce: admin_autocomplete_params.nonce,
                    term: params.term
                };
            },
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: true
        },
        minimumInputLength: 2,
        multiple: true,
    });


    // $("#product-search").on('change', function (e) {
    //     $("#products").val(($(this).val()));
    // });

});