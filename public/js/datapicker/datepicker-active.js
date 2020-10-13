(function ($) {
 "use strict";
 
	 $('#data_1 .input-group.date').datepicker({
		todayBtn: "linked",
		keyboardNavigation: false,
		forceParse: false,
		calendarWeeks: true,
		autoclose: true
	});

	$('#data_2 .input-group.date').datepicker({
		startView: 1,
		todayBtn: "linked",
		keyboardNavigation: false,
		forceParse: false,
		autoclose: true,
		format: "dd/mm/yyyy"
	});

	$('#data_3 .input-group.date').datepicker({
		startView: 2,
		todayBtn: "linked",
		keyboardNavigation: false,
		forceParse: false,
		autoclose: true
	});

	$('#data_4 .input-group.date').datepicker({
		minViewMode: 1,
		keyboardNavigation: false,
		forceParse: false,
		autoclose: true,
		todayHighlight: true
	});

	$('#data_5 .input-daterange').datepicker({
		keyboardNavigation: false,
		forceParse: false,
		autoclose: true
	});


	$.fn.datepicker.dates['fr'] = {
		days: ["dimanche", "lundi", "mardi", "mercredi", "jeudi", "vendredi", "samedi"],
		daysShort: ["dim.", "lun.", "mar.", "mer.", "jeu.", "ven.", "sam."],
		daysMin: ["dim.", "lun.", "mar.", "mer.", "jeu.", "ven.", "sam."],
		months: ["janvier", "février", "mars", "avril", "mai", "juin", "juillet", "août", "septembre", "octobre", "novembre", "décembre"],
		monthsShort: ["janv.", "févr.", "mars", "avril", "mai", "juin", "juil.", "août", "sept.", "oct.", "nov.", "déc."],
		today: "Aujourd'hui",
		monthsTitle: "Mois",
		clear: "Effacer",
		weekStart: 1,
		format: "dd/mm/yyyy"
	};



})(jQuery); 