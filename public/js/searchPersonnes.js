$('#favorite_rules').select2({
    placeholder: "Sélectionner favoris"
});

var rules_basic = {
    condition: 'AND',
    rules: [{
        id: 'p.lbNomUsage',
        operator: 'is_not_null',
        value: ''
    }]
};

$('#builder-basic').queryBuilder({
    plugins: ['bt-tooltip-errors'],
    filters : [{
        id: 'p.lbNomUsage',
        label: 'Nom',
        type: 'string'
    }],
    rules: rules_basic
});


// Some stuff to add objects in the array objArr

$('#builder-basic').queryBuilder('addFilter',objArr,1); // To add new filters object.


function updateFilters() {

    var sql_raw = $('#builder-basic').queryBuilder('getSQL', 'named');
    return sql_raw;
}

$(document).ready(function () {


    // favorite search selected

    $('#favorite_rules').on('change', function() {
        var rules =  $(this).find(':selected').data('role')
        var customRules = decodeURIComponent(rules);
        $('#builder-basic').queryBuilder('setRules', JSON.parse(customRules) );
    });


    updateFilters();

    var dataTable =    $('#personnesTable').DataTable({
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'orderCellsTop': true,
        //  'searching': false, // Remove default Search Control
        'ajax': {
            'url': "/fr/advancedsearch",
            'data': function(data){

                // Read values
                var nom = $('#Nom').val();
                var email = $('#Email').val();

                // Append to data
                data.sql = updateFilters();
                data.searchByEmail = email;
                data.searchByName = nom;
            }
        },
        'columns': [
            { data: 'lbNomUsage' },
            { data: 'email' },
            { data: 'gender' },
            { data: 'langue' },
        ]
    });

    $('#personnesTable thead tr:eq(1) th').each( function () {
        var title = $(this).text();
        $(this).html( '<input type="text" id="' + title + '" placeholder="Rechercher '+title+'" class="form-control" />' );
    } );

    $('#recherche-person').on('click', function () {
        dataTable.draw();
    });

    $('#favorisSaved').click(function (e) {

        var sql_raw = $('#builder-basic').queryBuilder('getRules');
        var rules = encodeURIComponent(JSON.stringify(sql_raw, null, 2));
        var name_rules = $('#nom').val();

        $.ajax({
            url: "/fr/favoritesave",
            type: 'post',
            data: {
                rules: rules,
                name_rules: name_rules
            },
            success: function (data) {
                if(data.result == 0) {
                    $('#nom').after('<span class="invalid-feedback d-block ExpertNsError"><span class="d-block"><span class="form-error-icon badge badge-danger text-uppercase">Erreur</span><span class="form-error-message"> '+data.data+'</span></span></span>');
                } else {
                    $('#nom').val();
                    location.reload();
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert('Error : ' + errorThrown);
            }
        });

    });

});