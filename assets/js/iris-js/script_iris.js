$(function () {
    $('[data-toggle="tooltip_info"]').tooltip()
    $('[rel="tooltip"]').tooltip({trigger: "hover"});
    $('[data-toggle="popover"]').popover();
    $("#sortTable").DataTable({
        bPaginate: false,
        bLengthChange: false,
        bFilter: true,
        bInfo: false,
        bAutoWidth: false,
        searching: false,
    });

        $(".select2_").select2();
        $(".select2_personne").select2({
            minimumInputLength: 2,
            allowClear: true,
            width: '100%',
            placeholder: "Pilote de l'appel à projet",
            language: {
                inputTooShort: function () {
                    return "Veuillez saisir 2 caractères ou plus...";
                }
            },
            ajax: {
                url: '/fr/personne/search/set_ajax_personne',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term, // search term
                        page: params.page
                    };
                },
                processResults: function (data, params) {
                    // parse the results into the format expected by Select2
                    // since we are using custom formatting functions we do not need to
                    // alter the remote JSON data, except to indicate that infinite
                    // scrolling can be used
                    params.page = params.page || 1;
                    return {
                        results: data,
                        pagination: {
                            more: (params.page * 30) < data.total_count
                        }
                    };
                },
                cache: true
            },
        })
        $(".select2_personne_mult").select2({
            minimumInputLength: 2,
            multiple: true,
            allowClear: true,
            width: '100%',
            placeholder: "Recherche une personne",
            language: {
                inputTooShort: function () {
                    return "Veuillez saisir 2 caractères ou plus...";
                }
            },
            ajax: {
                url: '/fr/personne/search/set_ajax_personne',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term, // search term
                        page: params.page
                    };
                },
                processResults: function (data, params) {
                    params.page = params.page || 1;
                    return {
                        results: data,
                        pagination: {
                            more: (params.page * 30) < data.total_count
                        }
                    };
                },
                cache: true
            },
        })

    // texte minimizer
    var minimized_elements = $('.minimize');
    minimized_elements.each(function() {
        var t = $(this).text();
        if (t.length < 100) return;

        $(this).html(
            t.slice(0, 100) + '<span>... </span><a style="font-size:12px;color:blue" href="#" class="more">Lire la suite</a>' +
            '<span style="display:none;">' + t.slice(100, t.length) + ' <a style="font-size:12px;color:blue" href="#" class="less">Replier</a></span>'
        );
    });

    $('a.more', minimized_elements).click(function(event) {
        event.preventDefault();
        $(this).hide().prev().hide();
        $(this).next().show();
    });
    $('a.less', minimized_elements).click(function(event) {
        event.preventDefault();
        $(this).parent().hide().prev().show().prev().show();
    });
    // end text minimizer

})