$(function () {
    $('.fonction_cv').select2({
        theme: "classic",
        width: 'resolve',
        placeholder: 'Choisir une fonction',
    });

    $("#tg_organisme_idAdresse_cdPays").select2({
        dropdownParent: $("#bl_ETR"),
        placeholder: "Sélectionner un pays",
    });

    //////////////////  ORGANISME    PUBLIC  ///////////////////

    $(document).on("click", "#addOrPubFr", function (event) {

        // event.preventDefault();
        $('form[name="BlPartenairePufType"]').trigger("reset");
        $("#add_").show();
        $("#modif").hide();
        $('#bl_PUF').modal('show');


    });

    $(document).on("click", "#submitOrPubFr", function () {
        var path = $(this).data('id');
        if($("#BlPartenairePufType_rnsr_puf").val() == '' ){
            alert('Le numéro RNSR est vide ou inconnu !');
            return false
        }
        if($("#BlPartenairePufType_name_tut_heb_puf").val() == '' ){
            alert('Le nom de la tutelle hébergeante est obligatoire !');
            return false
        }
        // AJAX request
        $.ajax({
            url: path,
            type: 'post',
            data: {
                'rnsr': $("#BlPartenairePufType_rnsr_puf").val(),
                'siret': $("#BlPartenairePufType_siret_tut_heb_puf").val(),
                'name_tut_heb': $("#BlPartenairePufType_name_tut_heb_puf").val(),
                'laboratoire': $("#BlPartenairePufType_laboratoire_puf").val(),
                'code_unite' : $("#BlPartenairePufType_code_unite_puf").val(),
                'adress_tut_heb': $("#BlPartenairePufType_adress_tut_heb_puf").val(),
                'compl_adress_tut_heb': $("#BlPartenairePufType_compl_adress_tut_heb_puf").val(),
                'postal_code_tut_heb': $("#BlPartenairePufType_postal_code_tut_heb_puf").val(),
                'country_tut_heb': $("#BlPartenairePufType_country_tut_hub_puf").val(),
                'city_tut_heb':     $("#BlPartenairePufType_city_tut_hub_puf").val(),
            },
            success: function (data) {
                $('#succes').show();
                $('#succes').html("<div class='alert alert-success'> Organisme à bien été enregistré </div>");
                $('#bl_PUF').modal('hide');
                $('form[name="BlPartenairePufType"]').trigger("reset");
                location.reload();
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert('Error : ' + errorThrown);
            }
        });
    });

    // clic ouvre le modal pour modif via ajax
    $(document).on("click", "#editOrPubFr", function () {
        var path = $(this).data('id');
        $.ajax({
            'url': path,
            type: 'POST',
            success: function (data) {

                $("#BlPartenairePufType_rnsr_puf").val(data['rnsr_puf']);
                $("#BlPartenairePufType_siret_tut_heb_puf").val(data['siret_puf']);
                $("#select2-BlPartenairePufType_name_tut_heb_puf-container").html(data['name_tut_heb_puf']);
                $("#BlPartenairePufType_laboratoire_puf").val(data['laboratoire_puf']);
                $("#BlPartenairePufType_code_unite_puf").val(data['code_unite']);
                $("#BlPartenairePufType_adress_tut_heb_puf").val(data['adress_tut_heb_puf']);
                $("#BlPartenairePufType_compl_adress_tut_heb_puf").val(data['compl_adress_tut_heb_puf']);
                $("#BlPartenairePufType_postal_code_tut_heb_puf").val(data['postal_code_tut_heb_puf']);
                $("#BlPartenairePufType_city_tut_hub_puf").val(data['city_tut_heb_puf']);
                $("#BlPartenairePufType_country_tut_hub_puf").val(data['country_tut_heb_puf']);
                $("#idorganisme").val(data['idorganisme']);
                $("#add_").hide();
                $("#modif").show();
            }
        });
    });

    // clic ouvre le modal pour modif via ajax
    $(document).on("click", "#updateOrPubFr", function () {
        if($("#BlPartenairePufType_rnsr_puf").val() == '' ){
            alert('Le numéro RNSR est vide ou inconnu !');
            return false
        }
        if($("#BlPartenairePufType_name_tut_heb_puf").val() == '' ){
            alert('Le nom de la tutelle hébergeante est obligatoire !');
            return false
        }
        var idorganisme = $("#idorganisme").val();
        // AJAX request
        $.ajax({
            'url': '/fr/cv/org/pub/fr/' + idorganisme + '/edit',
            type: 'POST',
            data: {
                'rnsr': $("#BlPartenairePufType_rnsr_puf").val(),
                'siret': $("#BlPartenairePufType_siret_tut_heb_puf").val(),
                'name_tut_heb': $("#BlPartenairePufType_name_tut_heb_puf").val(),
                'laboratoire': $("#BlPartenairePufType_laboratoire_puf").val(),
                'code_unite' : $("#BlPartenairePufType_code_unite_puf").val(),
                'adress_tut_heb': $("#BlPartenairePufType_adress_tut_heb_puf").val(),
                'compl_adress_tut_heb': $("#BlPartenairePufType_compl_adress_tut_heb_puf").val(),
                'postal_code_tut_heb': $("#BlPartenairePufType_postal_code_tut_heb_puf").val(),
                'country_tut_heb': $("#BlPartenairePufType_country_tut_hub_puf").val(),
                'city_tut_heb':     $("#BlPartenairePufType_city_tut_hub_puf").val(),
            },
            success: function (data) {
                $('form[name="BlPartenairePufType"]').trigger("reset");
                location.reload();

            }
        });
    });

    // clic ouvre le modal pour modif via ajax
    $(document).on("click", "#deleteOrPubFr", function () {
        var path = $(this).data('id');
        // AJAX request
        $.ajax({
            'url': path,
            type: 'DELETE',
            success: function (data) {
                // Add response in Modal body
                $('.modal-body').html(data);
                // Display Modal
                $('#deleteLangue').modal('show');
            }
        });
    });




    //////////////////  ORGANISME    PRIVE  ///////////////////
    $(document).on("click", "#addOrPrivFr", function (event) {
        // event.preventDefault();
        $('form[name="BlPartenairePrfType"]').trigger("reset");
        $("#add_p").show();
        $("#modif_p").hide();
        $('#bl_PRF').modal('show');
    });

    $(document).on("click", "#submitOrPrivFr", function (e) {
        if($("#BlPartenairePrfType_siret_tut_gest_prf").val() == '' || $("#BlPartenairePrfType_siret_tut_gest_prf").val().length != 14){
            alert('Le numéro SIRET est vide ou inconnu !');
            return false
        }
        var path = $(e.target).data('id');
        $.ajax({
            'url': path,
            type: 'POST',
            data: {
                'siret_tut_gest': $("#BlPartenairePrfType_siret_tut_gest_prf").val(),
                'service': $("#BlPartenairePrfType_service").val(),
                'name_tut_gest': $("#BlPartenairePrfType_name_tut_gest_prf").val(),
                'sigle': $("#BlPartenairePrfType_sigle_prf").val(),
                'adress_tut_gest': $("#BlPartenairePrfType_adress_tut_gest_prf").val(),
                'compl_adress_tut_gest': $("#BlPartenairePrfType_compl_adress_tut_gest_prf").val(),
                'postal_code_tut_gest': $("#BlPartenairePrfType_postal_code_tut_gest_prf").val(),
                'city_tut_gest': $("#BlPartenairePrfType_city_tut_g_prf").val(),
                'country_tut_gest': $("#BlPartenairePrfType_country_tut_g_prf").val(),
            },
            success: function (data) {
                $('#succes').show();
                $('#succes').html("<div class='alert alert-success'> Organisme à bien été enregistré </div>");
                $('#bl_PRF').modal('hide');
                $('form[name="BlPartenairePrfType"]').trigger("reset");
                window.location = document.referrer + '#OrganismePrivFran';
                location.reload();
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert('Error : ' + errorThrown);
            }
        });
    });

    $(document).on("click", "#editOrPrivFr", function () {
        var path = $(this).data('id');

        $.ajax({
            'url': path,
            type: 'POST',
            success: function (data) {
                $("#BlPartenairePrfType_siret_tut_gest_prf").val(data['siret_tut_gest_prf']);
                $("#BlPartenairePrfType_service").val(data['service']);
                $("#BlPartenairePrfType_name_tut_gest_prf").val(data['name_tut_gest_prf']);
                $("#BlPartenairePrfType_sigle_prf").val(data['sigle_prf']);
                $("#BlPartenairePrfType_adress_tut_gest_prf").val(data['adress_tut_gest_prf']);
                $("#BlPartenairePrfType_compl_adress_tut_gest_prf").val(data['compl_adress_tut_gest_prf']);
                $("#BlPartenairePrfType_postal_code_tut_gest_prf").val(data['postal_code_tut_gest_prf']);
                $("#BlPartenairePrfType_city_tut_g_prf").val(data['city_tut_gest_prf']);
                $("#BlPartenairePrfType_country_tut_g_prf").val(data['country_tut_gest_prf']);
                $("#idOrganisme").val(data['idOrganisme']);
                $("#add_p").hide();
                $("#modif_p").show();
            }
        });
    });

    // clic ouvre le modal pour modif via ajax
    $(document).on("click", "#updateOrPrivFr", function () {
        if($("#BlPartenairePrfType_siret_tut_gest_prf").val() == '' || $("#BlPartenairePrfType_siret_tut_gest_prf").val().length != 14){
            alert('Le numéro SIRET est vide ou inconnu !');
            return false
        }
        var idorganisme = $("#idOrganisme").val();
        // AJAX request
        $.ajax({
            'url': '/fr/cv/org/priv/fr/' + idorganisme + '/edit',
            type: 'POST',
            data: {
                'siret_tut_gest': $("#BlPartenairePrfType_siret_tut_gest_prf").val(),
                'service': $("#BlPartenairePrfType_service").val(),
                'name_tut_gest': $("#BlPartenairePrfType_name_tut_gest_prf").val(),
                'sigle': $("#BlPartenairePrfType_sigle_prf").val(),
                'adress_tut_gest': $("#BlPartenairePrfType_adress_tut_gest_prf").val(),
                'compl_adress_tut_gest': $("#BlPartenairePrfType_compl_adress_tut_gest_prf").val(),
                'postal_code_tut_gest': $("#BlPartenairePrfType_postal_code_tut_gest_prf").val(),
                'city_tut_gest': $("#BlPartenairePrfType_city_tut_g_prf").val(),
                'country_tut_gest': $("#BlPartenairePrfType_country_tut_g_prf").val(),
            },
            success: function (data) {
                $('form[name="BlPartenairePrfType"]').trigger("reset");
                $('#bl_PRF').modal('hide');
                window.location = document.referrer + '#OrganismePrivFran';
                location.reload();
            }
        });
    });

    // clic ouvre le modal pour modif via ajax
    $(document).on("click", "#deleteOrPrivFr", function () {
        var path =  $(this).data('id');
        // AJAX request
        $.ajax({
            url: path,
            type: 'delete',
            success: function(response){
                // Add response in Modal body
                $('.modal-body').html(response);
                // Display Modal
                $('#deleteLangue').modal('show');
            }
        });
    });

    // $(document).on("click", "#deleteOrPrivFr", function () {
    //     var path = $(this).data('id');
    //     alert('voulez-vous supprimer ?');
    //     // AJAX request
    //     $.ajax({
    //         'url': path,
    //         type: 'DELETE',
    //         success: function (data) {
    //             $('#bl_PRF').modal('hide');
    //             $('form[name="BlPartenairePrfType"]').trigger("reset");
    //             window.location = document.referrer + '#OrganismePrivFran';
    //             location.reload();
    //         }
    //     });
    // });


    //////////////////  ORGANISME    ETRANGER  ///////////////////

    $(document).on("click", "#addOrEtrFr", function () {
        var path = $(this).data('id');

        $.ajax({
            type: 'POST',
            'url': path,
            success: function (data) {
                $('#bl_ETR').find('.modal-body').html(data);

                $("#tg_organisme_idAdresse_cdPays").select2({
                    placeholder: "Sélectionner un pays",
                });
            }
        });
    });

    $(document).on("click", "#updateOrEtr", function () {
            var path = $(this).data('id');
            $.ajax({
                // type: 'POST',
                'url': path,
                success: function (data) {
                    $('#bl_ETR_update').find('.modal-body').html(data);
                }
            });
        });

    $(document).on("click", "#deleteOrEtr", function () {
        var path =  $(this).data('id');
        // AJAX request
        $.ajax({
            url: path,
            type: 'delete',
            success: function(response){
                // Add response in Modal body
                $('.modal-body').html(response);
                // Display Modal
                $('#deleteLangue').modal('show');
            }
        });
    });
});