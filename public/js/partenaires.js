////////////////////// web service RNSR & SIRET //////////
$( function() {
	$( "#BlPartenairePufType_rnsr_puf" ).autocomplete({
		source: function( request, response ) {
			// Fetch data
			$.ajax({
				url: '/fr/soumission/rnsrsearch/',
				type: 'post',
				dataType: "json",
				data: {
					search: request.term
				},
				success: function( data ) {
					response(data);
				}
			});
		},
		select: function(event, ui) {
			var rnsr = ui.item.value;
			getInfoRnsr(rnsr);
			getEtabsListe(rnsr);
		},
		minLength: 5
	});
	function getInfoRnsr(rnsr){

		$.ajax({
			url: '/fr/soumission/infornsr/',
			type: 'post',
			data: {
				rnsr: rnsr
			},
		}).done(function(data){
			var data = jQuery.parseJSON(data);

			$('#BlPartenairePufType_laboratoire_puf').val(data.sigle);
			$('#BlPartenairePufType_adress_tut_heb_puf').val(data.adr_postale);
			$('#BlPartenairePufType_city_tut_hub_puf').val(data.ville_postale);
			$('#BlPartenairePufType_postal_code_tut_heb_puf').val(data.code_postal);

		});
	}

	var o = new Option("Choisir une tutelle hébergeante", "");
	$(o).html("Choisir une tutelle hébergeante");
	$("#BlPartenairePufType_name_tut_heb_puf").append(o);

	$("#BlPartenairePufType_name_tut_heb_puf").select2({
		dropdownParent: $("#bl_PUF"),
		placeholder: "Sélectionner un numéro RNSR",
		data: null,
	});

	$("#BlPartenairePufType_delegation_tut_heb_puf").select2({
		dropdownParent: $("#bl_PUF"),
		placeholder: "Sélectionner un numéro RNSR",
		data: null,
	});

	function getEtabsListe(rnsr) {
		$.ajax({
			url: '/fr/soumission/etabsearch/',
			type: 'post',
			data: {
				rnsr: rnsr
			},
		}).done(function(data){
			var tut = jQuery.parseJSON(data);

			$('#BlPartenairePufType_name_tut_heb_puf option').not(':first').remove();
			$('#BlPartenairePufType_name_tut_heb_puf').select2({
				dropdownParent: $("#bl_PUF"),
				placeholder: "Choisir une tutelle hébergeante",
				data: tut,
			});
		});
	}

	$('#BlPartenairePufType_name_tut_heb_puf').change(function() {
		getDelegsListe();
		getInfosTutelle();
	});
	function getInfosTutelle() {
		var cleetab = $("#BlPartenairePufType_name_tut_heb_puf option:selected").val();
		$.ajax({
			url: '/fr/soumission/tutsearch/',
			type: 'post',
			data: {
				cleetab: cleetab
			},
		}).done(function(data){
			var data = jQuery.parseJSON(data);
			$('#BlPartenairePufType_code_unite_puf').val(data.numuai);
			$('#BlPartenairePufType_siret_tut_heb_puf').val(data.sirensiret);
		});
	}

	function getDelegsListe() {

		var cleetab = $("#BlPartenairePufType_name_tut_heb_puf option:selected").val();

		$.ajax({
			url: '/fr/soumission/delegsearch/',
			type: 'post',
			data: {
				cleetab: cleetab
			},
		}).done(function(data){
			var deleg = jQuery.parseJSON(data);

			$('#BlPartenairePufType_delegation_tut_heb_puf option').not(':first').remove();
			$('#BlPartenairePufType_delegation_tut_heb_puf').select2({
				dropdownParent: $("#bl_PUF"),
				placeholder: "Choisir une délégation",
				data: deleg,
			});
		});
	}

	$( "#BlPartenairePufType_siret_tut_gest_puf" ).autocomplete({
		source: function( request, response ) {
			// Fetch data
			$.ajax({
				url: '/fr/soumission/rnsrsearch/',
				type: 'post',
				dataType: "json",
				data: {
					search: request.term
				},
				success: function( data ) {
					response(data);
				}
			});
		},
		select: function(event, ui) {
			var siret = ui.item.value;

			getInfoSiret(siret);
		},
		minLength: 5
	});
	function getInfoSiret(siret){

		$.ajax({
			url: '/fr/soumission/infornsr/',
			type: 'post',
			data: {
				rnsr: siret
			},
		}).done(function(data){
			var data = jQuery.parseJSON(data);

			$('#BlPartenairePufType_name_tut_gest_puf').val(data.intitule);
			$('#BlPartenairePufType_adress_tut_gest_puf').val(data.adr_postale);
			$('#BlPartenairePufType_postal_code_tut_gest_puf').val(data.code_postal);
			$('#BlPartenairePufType_city_tut_g_puf').val(data.ville_postale);
		});
	}

	$( "#BlPartenairePrfType_siret_tut_gest_prf" ).autocomplete({
		source: function( request, response ) {
			// Fetch data
			$.ajax({
				url: '/fr/soumission/siretsearch/',
				type: 'post',
				dataType: "json",
				data: {
					siret: request.term
				},
				success: function( data ) {
					response(data);
				}
			});
		},
		select: function(event, ui) {
			var siret = ui.item.value;

			getInfoSiretPrf(siret);
		},
		minLength: 5
	});
	function getInfoSiretPrf(siret){

		$.ajax({
			url: '/fr/soumission/siretdatasearch/',
			type: 'post',
			data: {
				siret: siret
			},
		}).done(function(data){
			var data = jQuery.parseJSON(data);
			$('#BlPartenairePrfType_name_tut_gest_prf').val(data.raison_sociale);
			$('#BlPartenairePrfType_sigle_prf').val(data.sigle_anr);
			$('#BlPartenairePrfType_adress_tut_gest_prf').val(data.adresse);
			$('#BlPartenairePrfType_compl_adress_tut_gest_prf').val(data.complement_d_adresse);
			$('#BlPartenairePrfType_postal_code_tut_gest_prf').val(data.code_postal);
			$('#BlPartenairePrfType_country_tut_g_prf').val(data.id_pays);
			$('#BlPartenairePrfType_city_tut_g_prf').val(data.ville);

		});
	}
} );

//////////////////////////////////////////////////////////////
///////////////////////// Pays & villes //////////////////////
$("#BlPartenaireEtrType_country_etr").select2({
	dropdownParent: $("#bl_ETR"),
	placeholder: "Choisir un pays",
});
/////////////////////////////////////////////////////////////////////////////////

//////////////////////Partenaire open delete & edit ////////////////////////////////
$('.delete-partenaire').click(function (e) {
	var path = $(e.target).data('id');

	$.ajax({
		'url': path,
		type: 'POST',
		success: function (data) {
			location.reload();
		},
		error: function (XMLHttpRequest, textStatus, errorThrown) {
			alert('Error : ' + errorThrown);
		}
	});
});
$('#suivantPart').click(function (e) {
	var typ_part =   $('input[name=typ_part]:checked').val();
	$('#BlPartenariatType_type_part').val(typ_part);

	$('.PartError').hide();
	$('.EtrError').hide();
	$('.PrfError').hide();
	var buttonType = typ_part.toLowerCase();
	$('.add-part-'+buttonType+'').show();
	$('.edit-part-'+buttonType+'').hide();
	$('#bl_'+typ_part+'').modal(
		{  backdrop: 'static',
			keyboard: false
		},
		'show'
	);
});
$('.edit-partenaire').click(function (e) {
	var idPartenariat = $(this).data("id");
	var typPart = $(this).data("role");

	if('ETR' == typPart){
		$.ajax({
			'url': "/fr/soumission/etrshow/" + idPartenariat,
			type: 'POST',
			success: function (data) {
				$("#BlPartenaireEtrType_name_etr").val(data['name']);
				$("#BlPartenaireEtrType_laboratoire_etr").val(data['laboratoire']);
				$("#BlPartenaireEtrType_city_etr").val(data['city']);
				$("#select2-BlPartenaireEtrType_country_etr-container").html(data['country']);

				$('.EtrError').hide();
				$('.add-part-etr').hide();
				$('.edit-part-etr').show();
				$('#submitEtrEdit').data("id", "/fr/soumission/etredit/" + idPartenariat);

				$('#bl_ETR').modal(
					{  backdrop: 'static',
						keyboard: false
					},
					'show');
			},
			error: function (XMLHttpRequest, textStatus, errorThrown) {
				alert('Error : ' + errorThrown);
			}
		});
	}
	if('PRF' == typPart){
		$.ajax({
			'url': "/fr/soumission/prfshow/" + idPartenariat,
			type: 'POST',
			success: function (data) {

				$("#BlPartenairePrfType_siret_tut_gest_prf").val(data['siret_tut_gest_prf']);
				$("#BlPartenairePrfType_name_tut_gest_prf").val(data['name_tut_gest_prf']);
				$("#BlPartenairePrfType_sigle_prf").val(data['sigle_prf']);
				$("#BlPartenairePrfType_adress_tut_gest_prf").val(data['adress_tut_gest_prf']);
				$("#BlPartenairePrfType_compl_adress_tut_gest_prf").val(data['compl_adress_tut_gest_prf']);
				$("#BlPartenairePrfType_postal_code_tut_gest_prf").val(data['postal_code_tut_gest_prf']);
				$("#BlPartenairePrfType_city_tut_g_prf").val(data['city_tut_gest_prf']);
				$("#BlPartenairePrfType_country_tut_g_prf").val(data['country_tut_gest_prf']);

				$("#BlPartenairePrfType_banque_tut_g_prf").val(data['banque_tut_g_prf']);
				$("#BlPartenairePrfType_rib_tut_g_prf").val(data['rib_tut_g_prf']);
				$("#BlPartenairePrfType_iban_tut_g_prf").val(data['iban_tut_g_prf']);

				$("#BlPartenairePrfType_lastname_gest_admin_prf").val(data['lastname_gest_admin_prf']);
				$("#BlPartenairePrfType_firstname_gest_admin_prf").val(data['firstname_gest_admin_prf']);
				$("#BlPartenairePrfType_mail_gest_admin_prf").val(data['mail_gest_admin_prf']);

				$("#BlPartenairePrfType_lastname_rep_juridique_prf").val(data['firstname_rep_juridique']);
				$("#BlPartenairePrfType_firstname_rep_juridique_prf").val(data['lastname_rep_juridique']);
				$("#BlPartenairePrfType_function_rep_juridique_prf").val(data['function_rep_juridique']);

				$('.PrfError').hide();
				$('.add-part-prf').hide();
				$('.edit-part-prf').show();
				$('#submitPrfEdit').data("id", "/fr/soumission/prfedit/" + idPartenariat);

				$('#bl_PRF').modal(
					{  backdrop: 'static',
						keyboard: false
					},
					'show');
			},
			error: function (XMLHttpRequest, textStatus, errorThrown) {
				alert('Error : ' + errorThrown);
			}
		});
	}
	if('PUF' == typPart){
		$.ajax({
			'url': "/fr/soumission/pufshow/" + idPartenariat,
			type: 'POST',
			success: function (data) {

				$("#BlPartenairePufType_rnsr_puf").val(data['rnsr_puf']);
				$("#select2-BlPartenairePufType_name_tut_heb_puf-container").html(data['name_tut_heb_puf']);
				$("#select2-BlPartenairePufType_delegation_tut_heb_puf-container").html(data['delegation_puf']);
				$("#BlPartenairePufType_siret_tut_heb_puf").val(data['siret_puf']);
				$("#BlPartenairePufType_laboratoire_puf").val(data['laboratoire']);
				$("#BlPartenairePufType_code_unite_puf").val(data['code_unite_puf']);
				$("#BlPartenairePufType_adress_tut_heb_puf").val(data['adress_tut_heb_puf']);
				$("#BlPartenairePufType_compl_adress_tut_heb_puf").val(data['compl_adress_tut_heb_puf']);
				$("#BlPartenairePufType_postal_code_tut_heb_puf").val(data['postal_code_tut_heb_puf']);
				$("#BlPartenairePufType_country_tut_hub_puf").val(data['country_tut_heb_puf']);
				$("#BlPartenairePufType_city_tut_hub_puf").val(data['city_tut_heb_puf']);

				$("#BlPartenairePufType_siret_tut_gest_puf").val(data['siret_tut_gest_puf']);
				$("#BlPartenairePufType_name_tut_gest_puf").val(data['name_tut_gest_puf']);
				$("#BlPartenairePufType_adress_tut_gest_puf").val(data['adress_tut_gest_puf']);
				$("#BlPartenairePufType_compl_adress_tut_gest_puf").val(data['compl_adress_tut_gest_puf']);
				$("#BlPartenairePufType_postal_code_tut_gest_puf").val(data['postal_code_tut_gest_puf']);
				$("#BlPartenairePufType_city_tut_g_puf").val(data['city_tut_gest_puf']);
				$("#BlPartenairePufType_country_tut_g_puf").val(data['country_tut_gest_puf']);
				$("#BlPartenairePufType_banque_tut_g_puf").val(data['banque_tut_g_puf']);
				$("#BlPartenairePufType_rib_puf").val(data['rib_tut_g_puf']);
				$("#BlPartenairePufType_iban_puf").val(data['iban_tut_g_puf']);

				$("#BlPartenairePufType_firstname_direct_lab_puf").val(data['firstname_direct_lab_puf']);
				$("#BlPartenairePufType_lastname_direct_lab_puf").val(data['lastname_direct_lab_puf']);
				$("#BlPartenairePufType_courriel_direct_lab_puf").val(data['courriel_direct_lab_puf']);

				$("#BlPartenairePufType_lastname_gest_admin_puf").val(data['lastname_gest_admin_puf']);
				$("#BlPartenairePufType_firstname_gest_admin_puf").val(data['firstname_gest_admin_puf']);
				$("#BlPartenairePufType_mail_gest_admin_puf").val(data['mail_gest_admin_puf']);

				$("#BlPartenairePufType_lastname_rep_juridique_puf").val(data['firstname_rep_juridique']);
				$("#BlPartenairePufType_firstname_rep_juridique_puf").val(data['lastname_rep_juridique']);
				$("#BlPartenairePufType_function_rep_juridique_puf").val(data['function_rep_juridique']);

				$('.PartError').hide();
				$('.add-part-puf').hide();
				$('.edit-part-puf').show();
				$('#submitPufEdit').data("id", "/fr/soumission/pufedit/" + idPartenariat);

				$('#bl_PUF').modal(
					{  backdrop: 'static',
						keyboard: false
					},
					'show');
			},
			error: function (XMLHttpRequest, textStatus, errorThrown) {
				alert('Error : ' + errorThrown);
			}
		});
	}
});
///////////////////////////////////////////////////////////
/////////////////// Participants Add & Delete & Edit //////////////////////////
$('.add-participant').click(function (e) {
	var respsc = $(this).data("respsc");
	var idPartenariat = $(this).data("id");
	var typPart =  $(this).data("browse");

	if(respsc) {
		$('#BlParticipantsType_resp').attr('checked', false);
		$('#BlParticipantsType_resp').attr("disabled", true);
		$('.respsc').attr("hidden", true);
	} else {
		$('#BlParticipantsType_resp').attr('checked', true);
		$('#BlParticipantsType_resp').attr("disabled", false);
		$('.respsc').attr("hidden", false);
	}

	$('form[name="BlParticipantsType"]').trigger("reset");
	$('.add-edit-participant').data("id", "/fr/soumission/participantadd/" + idPartenariat);
	$('.add-edit-participant').attr("href", '#'+typPart);

	$("#BlParticipantsType_lbNomUsage").prop('disabled', false);
	$("#BlParticipantsType_lbPrenom").prop('disabled', false);
	$("#BlParticipantsType_orcid").prop('disabled', false);

	$('.PartError').hide();
	$('#BlParticipantsType_idPartenaire').val(idPartenariat);
	$(".add-edit-participant").text('Ajouter');
	$('#addParticipant').modal(
		{  backdrop: 'static',
			keyboard: false
		},
		'show'
	);

});
$('.delete-participant').click(function (e) {
	var path = $(e.target).data('id');

	$.ajax({
		'url': path,
		type: 'POST',
		success: function (data) {
			// console.log(data);
			location.reload();
		},
		error: function (XMLHttpRequest, textStatus, errorThrown) {
			alert('Error : ' + errorThrown);
		}
	});
});
$('.edit-participant').click(function (e) {
	var participant_id = $(this).data("id");
	var typPart =  $(this).data("browse");
	$('.respsc').attr("hidden", false);
	$('#BlParticipantsType_resp').attr("disabled", false);

	var currentRow =  $('#tr_' + participant_id).text();
	var resp =  $('#respsc_' + participant_id).val();
	var civ =  $('#civ_' + participant_id).text();
	var nom =  $('#nom_' + participant_id).text();
	var prenom =  $('#prenom_' + participant_id).text();
	var mail =  $('#mail_' + participant_id).text();
	var orcid =  $('#orcid_'+participant_id).text();
	orcid = orcid.replace(/\s/g, '');
	var idPartenaire =  $('#idPartenaire_' + participant_id).val();
	var gender = '';
	if(civ == 'Monsieur') gender = 1;
	if(civ == 'Madame') gender = 2;
	if(civ == 'Mademoiselle') gender = 3;

	if(gender) $("input[name='BlParticipantsType[civ]'][value='" + gender + "']").prop('checked', 'true');
	$('#BlParticipantsType_resp').prop('checked', false);
	if("true" === resp){
		$('#BlParticipantsType_resp').prop('checked', true);
	}
	$('#BlParticipantsType_civ').val(civ);
	$('#BlParticipantsType_lbNomUsage').val(nom);
	$('#BlParticipantsType_lbPrenom').val(prenom);
	$('#BlParticipantsType_adrMail').val(mail);
	$('#BlParticipantsType_orcid').val(orcid);

	$("#BlParticipantsType_lbNomUsage").prop('disabled', true);
	$("#BlParticipantsType_lbPrenom").prop('disabled', true);
	$("#BlParticipantsType_orcid").prop('disabled', true);


	$(".add-edit-participant").text('Modifier');
	$('.add-edit-participant').data("id", "/fr/soumission/participantedit/"+participant_id+"/"+idPartenaire);
	$('.add-edit-participant').attr("href", '#'+typPart);
	$('#addParticipant').modal(
		{  backdrop: 'static',
			keyboard: false
		},
		'show'
	);
});
////////////////////////////////////////////////////////////
function showOneCoordinateur(id, country) {
	$('.CoordinatEtr_' + country).prop('checked', false);
	$('#coordEtr_' + id).prop('checked', true);

	saveCoord(id);

}
function showOneCoordinateurFr(id) {
	$(".CoordinatFr").prop('checked', false);
	$('#coordFr_' + id).prop('checked', true);

	saveCoord(id);
}

function saveCoord(id){
	$.ajax({
		'url': '/fr/soumission/coord/' + id,
		type: 'POST',
		success: function (data) {
			console.log(data);
			//location.reload();
		},
		error: function (XMLHttpRequest, textStatus, errorThrown) {
			alert('Error : ' + errorThrown);
		}
	});
}


////////////// cout provisionnel //////////////////////////
$('.edit-cout-puf').click(function (e) {
	var idPartenariat = $(this).data("id");
	$.ajax({
		'url': "/fr/soumission/coutprev/" + idPartenariat,
		type: 'POST',
		success: function (data) {
			getCoutPrevPuf();
			$("#cout_1").val(data['ct_personnels_permanents']);
			$("#cout_2").val(data['ct_personnels_non_permanents_ss_fin']);
			$("#cout_3").val(data['ct_personnels_non_permanents']);
			$("#cout_4").val(data['ct_decharge']);
			$("#cout_5").val(data['ct_instruments']);
			$("#cout_6").val(data['ct_batiments']);
			$("#cout_7").val(data['ct_prestation_service']);
			$("#cout_8").val(data['ct_frais_gen']);

			$("#pers_1").val(data['pt_personnels_permanents']);
			$("#pers_2").val(data['pt_personnels_non_permanents_ss_fin']);
			$("#pers_3").val(data['pt_personnels_non_permanents']);
			$("#pers_4").val(data['pt_decharge']);

			$("#tfe").val(data['taux_frais_env']);
			$("#tad").val(data['taux_aide_dde']);
			$("#total_ad").html(data['mnt_aide_dde']);
			calculateCoutPuf();
			$('.coutProvTitle').html('Coût prévisionelles - Publique français');
			$('#submitCout').data("id", "/fr/soumission/coutprevedit/" + idPartenariat);

			$('#coutP').modal(
				{  backdrop: 'static',
					keyboard: false
				},
				'show'
			);
		},
		error: function (XMLHttpRequest, textStatus, errorThrown) {
			alert('Error : ' + errorThrown);
		}
	});
});
$('.edit-cout-prf').click(function (e) {
	var idPartenariat = $(this).data("id");
	$.ajax({
		'url': "/fr/soumission/coutprev/" + idPartenariat,
		type: 'POST',
		success: function (data) {
			getCoutPrevPrf();
			$("#cout_1").val(data['ct_personnels_permanents']);
			$("#cout_2").val(data['ct_personnels_non_permanents_ss_fin']);
			$("#cout_3").val(data['ct_personnels_non_permanents']);
			$("#cout_4").val(data['ct_decharge']);
			$("#cout_5").val(data['ct_instruments']);
			$("#cout_6").val(data['ct_batiments']);
			$("#cout_7").val(data['ct_prestation_service']);
			$("#cout_8").val(data['ct_frais_gen']);

			$("#pers_1").val(data['pt_personnels_permanents']);
			$("#pers_2").val(data['pt_personnels_non_permanents_ss_fin']);
			$("#pers_3").val(data['pt_personnels_non_permanents']);
			$("#pers_4").val(data['pt_decharge']);

			$("#tfp").val(data['taux_frais_pers']);
			$("#adep").val(data['autres_dep']);
			$("#tad").val(data['taux_aide_dde']);
			$("#total_ad").html(data['mnt_aide_dde']);
			calculateCoutPrf();

			$('.coutProvTitle').html('Coût prévisionelles - Privés français');
			$('#submitCout').data("id", "/fr/soumission/coutprevedit/" + idPartenariat);

			$('#coutP').modal(
				{  backdrop: 'static',
					keyboard: false
				},
				'show'
			);
		},
		error: function (XMLHttpRequest, textStatus, errorThrown) {
			alert('Error : ' + errorThrown);
		}
	});
});

function getCoutPrevPuf()
{
	var default_val = 0;

	var ajax_data =
		[
			{cat: "Personnels permanents", ctotal:"0.00", ptotal:"0", id: 1},
			{cat: "Personnels non permanents sans financement de l'ANR", ctotal:"0.00", ptotal:"0", id: 2},
			{cat: "Personnels non permanents", ctotal:"0.00", ptotal:"0", id: 3},
			{cat: "Décharge d'enseignement", ctotal:"0.00", ptotal:"0", id: 4},

			{cat: "Instruments / matériels", ctotal:"0.00", ptotal:"0", id: 5},
			{cat: "Bâtiments et terrains", ctotal:"0.00", ptotal:"0", id: 6},
			{cat: "Prestations de services et de droit PI", ctotal:"0.00", ptotal:"0", id: 7},
			{cat: "Frais généraux non forfaitisés", ctotal:"0.00", ptotal:"0", id: 8}
		]

	var tbl = '';
	tbl +='<table class="table table-hover">'
	tbl +='<thead>';
	tbl +='<tr>';
	tbl +='<th width="45%">Catégorie de dépenses</th>';
	tbl +='<th width="20%">Coût total</th>';
	tbl +='<th width="20%">Total personne/mois</th>';
	tbl +='</tr>';
	tbl +='</thead>';
	tbl +='<tbody>';
	$.each(ajax_data, function(index, val)
	{

		var row_id = val['id'];
		tbl +='<tr row_id="tr_'+row_id+'">';
		tbl +='<td >'+val['cat']+'</td>';
		tbl +='<td ><input type="number" class="row_data ctotal" edit_type="click" col_name="fname" id="cout_'+row_id+'" value="'+val['ctotal']+'" />€</td>';
		if(5 == row_id || 6 == row_id || 7 == row_id || 8 == row_id) {
			tbl +='<td style="background-color: silver"></td>';
		}
		else {
			tbl +='<td ><input type="number" class="row_data ptotal" edit_type="click" col_name="lname" id="pers_'+row_id+'" value="'+val['ptotal']+'" /></td>';
		}

		tbl +='</tr>';
	});

	tbl +='<tr>' +
		'<td class="">  </td>' +
		'<td class="">  </td>' +
		'<td class="">  </td>' +
		'</tr>';

	tbl +='<tr>' +
		'<td class="total">Total dépenses prévisionnelles</td>' +
		'<td style="border: 2px solid dimgray;"><span id="total_cout" style="display: contents;">0,00</span>€</td>' +
		'<td style="border: 2px solid dimgray;"><span id="total_personne" style="display: contents;">0</span></td>' +
		'</tr>';

	tbl +='<tr>' +
		'<td class="">  </td>' +
		'<td class="">  </td>' +
		'<td class="">  </td>' +
		'</tr>';

	tbl +='<tr style="background-color: lavender;">' +
		'<td >Taux Frais d\'Environnement (taux max 8%)</td>' +
		'<td colspan="2" >' +
		'<input type="number" class="row_data tfe" edit_type="click" col_name="tfe" id="tfe" value="0" style="background-color: lavender;" min="1" max="8" />%' +
		'</td>' +
		'</tr>';

	tbl +='<tr>' +
		'<td class="">  </td>' +
		'<td class="">  </td>' +
		'<td class="">  </td>' +
		'</tr>';

	tbl +='<tr style="height: 50px">' +
		'<td >Coût éligible</td>' +
		'<td colspan="2" style="border: 2px solid dimgray;"><span id="total_elig"  col_name="eligtotal" style="display: contents;">0.00</span>€</td>' +
		'</tr>';

	tbl +='<tr>' +
		'<td class="">  </td>' +
		'<td class="">  </td>' +
		'<td class="">  </td>' +
		'</tr>';

	tbl +='<tr style="height: 50px">' +
		'<td >Frais d\'environnnement</td>' +
		'<td colspan="2" style="border: 2px solid dimgray;"><span id="total_fe"  col_name="fetotal" style="display: contents;">0.00</span>€</td>' +
		'</tr>';

	tbl +='<tr>' +
		'<td class="">  </td>' +
		'<td class="">  </td>' +
		'<td class="">  </td>' +
		'</tr>';

	tbl +='<tr>' +
		'<td >Total coût éligible</td>' +
		'<td colspan="2" style="border: 2px solid dimgray;"><span id="total_ce"  col_name="cetotal" style="display: contents;">0.00</span>€</td>' +
		'</tr>';

	tbl +='<tr>' +
		'<td class="">  </td>' +
		'<td class="">  </td>' +
		'<td class="">  </td>' +
		'</tr>';

	tbl +='<tr  style="background-color: lavender;">' +
		'<td >Taux d\'aide demandée</td>' +
		'<td colspan="2" >' +
		'<input type="number" class="row_data tad" edit_type="click" col_name="fname" id="tad" value="'+default_val+'" style="background-color: lavender;" />%' +
		'</td>' +
		'</tr>';

	tbl +='<tr>' +
		'<td class="">  </td>' +
		'<td class="">  </td>' +
		'<td class="">  </td>' +
		'</tr>';

	tbl +='<tr>' +
		'<td >Aide demandée</td>' +
		'<td colspan="2" style="border: 2px solid dimgray;"><span id="total_ad"  col_name="adtotal">0.00</span>€</td>' +
		'</tr>';

	tbl +='</tbody>';
	tbl +='</table>'

	$(document).find('.tbl_user_data').html(tbl);

	$(document).on('input', '.row_data', function(event)
	{
		calculateCoutPuf()

	})

	$(document).on('click', '.row_data', function(event)
	{
		event.preventDefault();

		if($(this).attr('edit_type') == 'button')
		{
			return false;
		}
		$(this).addClass('bg-warning').css('padding','5px');
	})

	$(document).on('focusout', '.row_data', function(event)
	{
		event.preventDefault();

		if($(this).attr('edit_type') == 'button')
		{
			return false;
		}

		var row_id = $(this).closest('tr').attr('row_id');

		var row_div = $(this)
			.removeClass('bg-warning') //add bg css
			.css('padding','')

		var col_name = row_div.attr('col_name');
		var col_val = row_div.html();

		var arr = {};
		arr[col_name] = col_val;

		//use the "arr"	object for your ajax call
		$.extend(arr, {row_id:row_id});

		//out put to show
		$('.post_msg').html( '<pre class="bg-success">'+JSON.stringify(arr, null, 2) +'</pre>');

	})

}

function calculateCoutPuf()
{
	////////// total dépenses prévisionelles /////////////////////////
	var calculated_total_sum = 0;

	$(".ctotal").each(function () {
		var get_textbox_value = $(this).val();
		if (get_textbox_value) {
			calculated_total_sum += parseFloat(get_textbox_value);
		}
	});

	$("#total_cout").html(calculated_total_sum);

	var calculated_total_personne = 0;

	$(".ptotal").each(function () {
		var get_textbox_value = $(this).val();
		if (get_textbox_value) {
			calculated_total_personne += parseFloat(get_textbox_value);
		}
	});

	$("#total_personne").html(calculated_total_personne);

	////////// Coût éligible /////////////////////////
	var calculated_cout_eligible = 0; var tot_permanent = 0; var pers_permanents = 0; var pers_sans_fin = 0;
	if ($("#cout_1").val()) pers_permanents = $("#cout_1").val();
	if ($("#cout_2").val()) pers_sans_fin = $("#cout_2").val();

	var tot_permanent = parseFloat(pers_permanents) + parseFloat(pers_sans_fin);

	if (tot_permanent) {
		calculated_cout_eligible = parseFloat(calculated_total_sum) - parseFloat(tot_permanent);
	}

	$("#total_elig").html(calculated_cout_eligible);

	////////// Frais d'environnement /////////////////////////
	var calculated_fe = 0; var taux_frais_env = 1;
	if ($("#tfe").val()) taux_frais_env = $("#tfe").val();
	calculated_fe = parseFloat(calculated_cout_eligible) * parseFloat(taux_frais_env);
	calculated_fe = parseFloat(calculated_fe) / 100;
	$("#total_fe").html(calculated_fe);

	////////// Total coût + frais d'environnement /////////////////////////
	var calculated_totce = 0;
	calculated_totce = parseFloat(calculated_cout_eligible) + parseFloat(calculated_fe);
	$("#total_ce").html(calculated_totce);


	////////// Aide demandée /////////////////////////
	var calculated_aide_dde = 0; var tad = 0; var taux_aide = 1;
	if ($("#tad").val()) tad = $("#tad").val();

	if (tad) {
		calculated_aide_dde = parseFloat(calculated_totce) * parseFloat(tad);
		calculated_aide_dde = parseFloat(calculated_aide_dde) / 100;
	}

	$("#total_ad").html(calculated_aide_dde);

}
function getCoutPrevPrf()
{
	var ajax_data =
		[
			{cat: "Personnels permanents", ctotal:"0.00", ptotal:"0", id: 1},
			{cat: "Personnels non permanents sans financement de l'ANR", ctotal:"0.00", ptotal:"0", id: 2},
			{cat: "Personnels non permanents", ctotal:"0.00", ptotal:"0", id: 3},
			{cat: "Décharge d'enseignement", ctotal:"0.00", ptotal:"0", id: 4},

			{cat: "Instruments / matériels", ctotal:"0.00", ptotal:"0", id: 5},
			{cat: "Bâtiments et terrains", ctotal:"0.00", ptotal:"0", id: 6},
			{cat: "Prestations de services et de droit PI", ctotal:"0.00", ptotal:"0", id: 7},
			{cat: "Frais généraux non forfaitisés", ctotal:"0.00", ptotal:"0", id: 8}
		]

	var tbl = '';
	tbl +='<table class="table table-hover">'
	tbl +='<thead>';
	tbl +='<tr>';
	tbl +='<th width="45%">Catégorie de dépenses</th>';
	tbl +='<th width="20%">Coût total</th>';
	tbl +='<th width="20%">Total personne/mois</th>';
	tbl +='</tr>';
	tbl +='</thead>';
	tbl +='<tbody>';
	$.each(ajax_data, function(index, val)
	{
		var row_id = val['id'];
		tbl +='<tr row_id="tr_'+row_id+'">';
		tbl +='<td >'+val['cat']+'</td>';
		tbl +='<td ><input type="number" class="row_data ctotal" edit_type="click" col_name="fname" id="cout_'+row_id+'" value="'+val['ctotal']+'" />€</td>';
		if(5 == row_id || 6 == row_id || 7 == row_id || 8 == row_id) {
			tbl +='<td style="background-color: silver"></td>';
		}
		else {
			tbl +='<td ><input type="number" class="row_data ptotal" edit_type="click" col_name="lname" id="pers_'+row_id+'" value="'+val['ptotal']+'" /></td>';
		}

		tbl +='</tr>';
	});

	tbl +='<tr>' +
		'<td class="">  </td>' +
		'<td class="">  </td>' +
		'<td class="">  </td>' +
		'</tr>';

	tbl +='<tr>' +
		'<td class="total">Total dépenses prévisionnelles</td>' +
		'<td style="border: 2px solid dimgray;"><span id="total_cout" style="display: contents;">0.00</span>€</td>' +
		'<td style="border: 2px solid dimgray;"><span id="total_personne" style="display: contents;">0</span></td>' +
		'</tr>';

	tbl +='<tr>' +
		'<td class="">  </td>' +
		'<td class="">  </td>' +
		'<td class="">  </td>' +
		'</tr>';

	tbl +='<tr style="background-color: lavender;">' +
		'<td >Taux frais de personnel (max 68.00%)</td>' +
		'<td colspan="2" >' +
		'<input type="number" class="row_data tfp" edit_type="click" col_name="tfp" id="tfp" value="0" style="background-color: lavender;" min="1" max="68" />%' +
		'</td>' +
		'</tr>';

	tbl +='<tr>' +
		'<td class="">  </td>' +
		'<td class="">  </td>' +
		'<td class="">  </td>' +
		'</tr>';

	tbl +='<tr style="background-color: lavender;">' +
		'<td >Autres dépenses (max 7.00%)</td>' +
		'<td colspan="2" >' +
		'<input type="number" class="row_data adep" edit_type="click" col_name="adep" id="adep" value="0" style="background-color: lavender;" min="1" max="7" />%' +
		'</td>' +
		'</tr>';

	tbl +='<tr>' +
		'<td class="">  </td>' +
		'<td class="">  </td>' +
		'<td class="">  </td>' +
		'</tr>';

	tbl +='<tr style="height: 50px">' +
		'<td >Frais de personnel</td>' +
		'<td style="border: 2px solid dimgray;"><span id="percent_fp"  col_name="eligpercent" style="display: contents;">0,00</span>%</td>' +
		'<td style="border: 2px solid dimgray;"><span id="total_fp"  col_name="fptotal" style="display: contents;">0.00</span>€</td>' +
		'</tr>';

	tbl +='<tr>' +
		'<td class="">  </td>' +
		'<td class="">  </td>' +
		'<td class="">  </td>' +
		'</tr>';

	tbl +='<tr style="height: 50px">' +
		'<td >Autres dépenses</td>' +
		'<td style="border: 2px solid dimgray;"><span id="percent_ad"  col_name="fepercent" style="display: contents;">0,00</span>%</td>' +
		'<td style="border: 2px solid dimgray;"><span id="total_adepenses"  col_name="adptotal" style="display: contents;">0.00</span>€</td>' +
		'</tr>';

	tbl +='<tr>' +
		'<td class="">  </td>' +
		'<td class="">  </td>' +
		'<td class="">  </td>' +
		'</tr>';

	tbl +='<tr>' +
		'<td >Total Frais</td>' +
		'<td colspan="2" style="border: 2px solid dimgray;"><span id="total_frais"  col_name="fraistotal" style="display: contents;">0.00</span>€</td>' +
		'</tr>';

	tbl +='<tr>' +
		'<td class="">  </td>' +
		'<td class="">  </td>' +
		'<td class="">  </td>' +
		'</tr>';

	tbl +='<tr>' +
		'<td >Coût éligible</td>' +
		'<td colspan="2" style="border: 2px solid dimgray;"><span id="coutE"  col_name="cetotal" style="display: contents;">0.00</span>€</td>' +
		'</tr>';

	tbl +='<tr>' +
		'<td class="">  </td>' +
		'<td class="">  </td>' +
		'<td class="">  </td>' +
		'</tr>';

	tbl +='<tr style="background-color: lavender;">' +
		'<td >Taux d\'aide demandée</td>' +
		'<td colspan="2" ><input type="number" class="row_data tad" style="background-color: lavender;" edit_type="click" col_name="fname" id="tad" value="0" />%</td>' +
		'</tr>';

	tbl +='<tr>' +
		'<td class="">  </td>' +
		'<td class="">  </td>' +
		'<td class="">  </td>' +
		'</tr>';

	tbl +='<tr>' +
		'<td >Aide demandée</td>' +
		'<td colspan="2" style="border: 2px solid dimgray;"><span id="total_ad"  col_name="adtotal">0.00</span>€</td>' +
		'</tr>';

	tbl +='</tbody>';
	tbl +='</table>'

	$(document).find('.tbl_user_data').html(tbl);

	$(document).on('input', '.row_data', function(event)
	{
		calculateCoutPrf();

	});

	$(document).on('click', '.row_data', function(event)
	{
		event.preventDefault();

		if($(this).attr('edit_type') == 'button')
		{
			return false;
		}
		$(this).addClass('bg-warning').css('padding','5px');
	});

	$(document).on('focusout', '.row_data', function(event)
	{
		event.preventDefault();

		if($(this).attr('edit_type') == 'button')
		{
			return false;
		}

		var row_id = $(this).closest('tr').attr('row_id');

		var row_div = $(this)
			.removeClass('bg-warning') //add bg css
			.css('padding','')

		var col_name = row_div.attr('col_name');
		var col_val = row_div.html();

		var arr = {};
		arr[col_name] = col_val;

		//use the "arr"	object for your ajax call
		$.extend(arr, {row_id:row_id});

		//out put to show
		$('.post_msg').html( '<pre class="bg-success">'+JSON.stringify(arr, null, 2) +'</pre>');

	});

}
function calculateCoutPrf()
{
	////////// total dépenses prévisionelles /////////////////////////
	var calculated_total_sum = 0;

	$(".ctotal").each(function () {
		var get_textbox_value = $(this).val();
		if (get_textbox_value) {
			calculated_total_sum += parseFloat(get_textbox_value);
		}
	});

	$("#total_cout").html(calculated_total_sum);

	var calculated_total_personne = 0;

	$(".ptotal").each(function () {
		var get_textbox_value = $(this).val();
		if (get_textbox_value) {
			calculated_total_personne += parseFloat(get_textbox_value);
		}
	});

	$("#total_personne").html(calculated_total_personne);

	////////// frais de personnel /////////////////////////
	var calculated_fp = 0; var pers_permanents = 0; var pers_nn_permanents = 0; var decharges = 0;
	var tot_cout = 0; var taux_frais_personnel = 68;
	if ($("#tfp").val()) taux_frais_personnel = $("#tfp").val();
	if ($("#cout_1").val()) pers_permanents = $("#cout_1").val();
	if ($("#cout_3").val()) pers_nn_permanents = $("#cout_3").val();
	if ($("#cout_4").val()) decharges = $("#cout_4").val();

	var tot_cout = parseFloat(pers_permanents) + parseFloat(pers_nn_permanents) + parseFloat(decharges);

	if (tot_cout) {
		calculated_fp = parseFloat(tot_cout) * parseFloat(taux_frais_personnel);
		calculated_fp = parseFloat(calculated_fp) / 100;
	}
	$("#total_fp").html(calculated_fp);
	$("#percent_fp").html(taux_frais_personnel);
	////////// Autres dépenses /////////////////////////
	var calculated_ad = 0; var instrum = 0; var batim = 0; var presta = 0; var frais_gen=0;
	var tot_cout = 0; var taux_ad = 7;
	if ($("#adep").val()) taux_ad = $("#adep").val();
	if ($("#cout_5").val()) instrum = $("#cout_5").val();
	if ($("#cout_6").val()) batim = $("#cout_6").val();
	if ($("#cout_7").val()) presta = $("#cout_7").val();
	if ($("#cout_8").val()) frais_gen = $("#cout_8").val();
	var tot_cout = parseFloat(instrum) + parseFloat(batim) + parseFloat(presta) + parseFloat(frais_gen);

	if (tot_cout) {
		calculated_ad = parseFloat(tot_cout) * parseFloat(taux_ad);
		calculated_ad = parseFloat(calculated_ad) / 100;
	}
	$("#total_adepenses").html(calculated_ad);
	$("#percent_ad").html(taux_ad);
	////////// Total frais /////////////////////////
	var calculated_tf = 0;
	calculated_tf = parseFloat(calculated_ad) + parseFloat(calculated_fp);
	$("#total_frais").html(calculated_tf);

	////////// Coût éligible /////////////////////////
	var calculated_ce = 0;
	calculated_ce = parseFloat(calculated_tf) + parseFloat(calculated_total_sum);
	$("#coutE").html(calculated_ce);

	////////// Aide demandée /////////////////////////
	var calculated_aide_dde = 0; var tad = 0;
	if ($("#tad").val()) tad = $("#tad").val();

	if (tad) {
		calculated_aide_dde = parseFloat(calculated_ce) * parseFloat(tad);
		calculated_aide_dde = parseFloat(calculated_aide_dde) / 100;
	}

	$("#total_ad").html(calculated_aide_dde);
}
/////////////////////////////////////////////////////////
///////////////////////////////////////////////////////
$(document).ready(function() {
	if (location.hash) {
		$("a[href='" + location.hash + "']").tab("show");
	}
	$(document.body).on("click", "a[data-toggle='tab']", function(event) {
		location.hash = this.getAttribute("href");
	});
});
$(window).on("popstate", function() {
	var anchor = location.hash || $("a[data-toggle='tab']").first().attr("href");
	$("a[href='" + anchor + "']").tab("show");
});

/////////////////// cacher les labels et les rubriques pour les blocks selon la phase ////
$('.hidden_1').closest('.form-group').hide();
var previous = $('.hidden_1').closest('.form-group');
previous.prev('.nom-rubrique').hide();
/////////////////////////////////////////////////////////////////////////////////////