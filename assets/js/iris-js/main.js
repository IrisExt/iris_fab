(function ($) {
 "use strict";

		
		
		
		
		$('#sidebarCollapse').on('click', function () {
                     $('#sidebar').toggleClass('active');
                     
                 });
 
		// Collapse ibox function
			$('#sidebar ul li').on('click', function () {
				var button = $(this).find('i.fa.indicator-mn');
				button.toggleClass('fa-angle-left').toggleClass('fa-angle-right');
				
			});

			
		$('#sidebarCollapse').on('click', function () {
			$("body").toggleClass("mini-navbar");
		});
		
		/*-----------------------------
			Menu Stick
		---------------------------------*/
		// $(".sicker-menu").sticky({topSpacing:0});
		//
		// $(document).on('click', '.header-right-menu .dropdown-menu', function (e) {
		// 	  e.stopPropagation();
		// 	});

		/*--------------------------
		 mCustomScrollbar
		---------------------------- */
			$(window).on("load",function(){
				$(".message-menu, .notification-menu, .comment-scrollbar, .notes-menu-scrollbar, .project-st-menu-scrollbar").mCustomScrollbar({
					autoHideScrollbar: true,
					scrollbarPosition: "outside",
					theme:"light-1"

				});
				$(".timeline-scrollbar").mCustomScrollbar({
					setHeight:636,
					autoHideScrollbar: true,
					scrollbarPosition: "outside",
					theme:"light-1"

				});
				$(".project-list-scrollbar").mCustomScrollbar({
					setHeight:636,
					theme:"light-2"
				});
				$(".messages-scrollbar").mCustomScrollbar({
					setHeight:503,
					autoHideScrollbar: true,
					scrollbarPosition: "outside",
					theme:"light-1"
				});
				$(".chat-scrollbar").mCustomScrollbar({
					setHeight:250,
					theme:"light-2"
				});
				$(".widgets-chat-scrollbar").mCustomScrollbar({
					setHeight:335,
					autoHideScrollbar: true,
					scrollbarPosition: "outside",
					theme:"light-1"
				});
				$(".widgets-todo-scrollbar").mCustomScrollbar({
					setHeight:322,
					autoHideScrollbar: true,
					scrollbarPosition: "outside",
					theme:"light-1"
				});
				$(".user-profile-scrollbar").mCustomScrollbar({
					setHeight:1820,
					autoHideScrollbar: true,
					scrollbarPosition: "outside",
					theme:"light-1"
				});
			});

			
			/*----------------------------
		 jQuery MeanMenu
		------------------------------ */
		jQuery('nav#dropdown').meanmenu();	
		
		// Collapse Chat function
			$('.chat-icon-link').on('click', function () {
				var button = $(this).find('i');
				button.toggleClass('fa-comments').toggleClass('fa-remove');
			});
		// Collapse ibox function
			$('.collapse-link').on('click', function () {
				var button = $(this).find('i');
				button.toggleClass('fa-chevron-up').toggleClass('fa-chevron-down');
				$( ".sparkline-content" ).slideToggle( "slow" );
			});
			$(".collapse-close").on('click', function(){
				$( "div.about-sparkline" ).fadeOut( 600 );
			});

		// Collapse ibox function
			$('.smart-collapse-link').on('click', function () {
				var button = $(this).find('i');
				button.toggleClass('fa-chevron-up').toggleClass('fa-chevron-down');
				$( ".smart-sparkline-list" ).slideToggle( "slow" );
			});
			$(".smart-collapse-close").on('click', function(){
				$( "div.sparkline-list" ).fadeOut( 600 );
			});


		// Collapse ibox function
			$('.sparkline7-collapse-link').on('click', function () {
				var button = $(this).find('i');
				button.toggleClass('fa-chevron-up').toggleClass('fa-chevron-down');
				$( ".sparkline7-graph" ).slideToggle( "slow" );
			});
			$(".sparkline7-collapse-close").on('click', function(){
				$( "div.sparkline7-list" ).fadeOut( 600 );
			});
		// Collapse ibox function
			$('.sparkline8-collapse-link').on('click', function () {
				var button = $(this).find('i');
				button.toggleClass('fa-chevron-up').toggleClass('fa-chevron-down');
				$( ".sparkline8-graph" ).slideToggle( "slow" );
			});
			$(".sparkline8-collapse-close").on('click', function(){
				$( "div.sparkline8-list" ).fadeOut( 600 );
			});


		// Collapse ibox function
			$('.sparkline9-collapse-link').on('click', function () {
				var button = $(this).find('i');
				button.toggleClass('fa-chevron-up').toggleClass('fa-chevron-down');
				$( ".sparkline9-graph" ).slideToggle( "slow" );
			});
			$(".sparkline9-collapse-close").on('click', function(){
				$( "div.sparkline9-list" ).fadeOut( 600 );
			});
			
		// Collapse ibox function
			$('.sparkline10-collapse-link').on('click', function () {
				var button = $(this).find('i');
				button.toggleClass('fa-chevron-up').toggleClass('fa-chevron-down');
				$( ".sparkline10-graph" ).slideToggle( "slow" );
			});
			$(".sparkline10-collapse-close").on('click', function(){
				$( "div.sparkline10-list" ).fadeOut( 600 );
			});
		// Collapse ibox function
			$('.sparkline11-collapse-link').on('click', function () {
				var button = $(this).find('i');
				button.toggleClass('fa-chevron-up').toggleClass('fa-chevron-down');
				$( ".sparkline11-graph" ).slideToggle( "slow" );
			});
			$(".sparkline11-collapse-close").on('click', function(){
				$( "div.sparkline11-list" ).fadeOut( 600 );
			});
			
			
		// Collapse ibox function
			$('.sparkline12-collapse-link').on('click', function () {
				var button = $(this).find('i');
				button.toggleClass('fa-chevron-up').toggleClass('fa-chevron-down');
				$( ".sparkline12-graph" ).slideToggle( "slow" );
			});
			$(".sparkline12-collapse-close").on('click', function(){
				$( "div.sparkline12-list" ).fadeOut( 600 );
			});
 
		// Collapse ibox function
			$('.sparkline13-collapse-link').on('click', function () {
				var button = $(this).find('i');
				button.toggleClass('fa-chevron-up').toggleClass('fa-chevron-down');
				$( ".sparkline13-graph" ).slideToggle( "slow" );
			});
			$(".sparkline13-collapse-close").on('click', function(){
				$( "div.sparkline13-list" ).fadeOut( 600 );
			});
 
		// Collapse ibox function
			$('.sparkline14-collapse-link').on('click', function () {
				var button = $(this).find('i');
				button.toggleClass('fa-chevron-up').toggleClass('fa-chevron-down');
				$( ".sparkline14-graph" ).slideToggle( "slow" );
			});
			$(".sparkline14-collapse-close").on('click', function(){
				$( "div.sparkline14-list" ).fadeOut( 600 );
			});
 
		// Collapse ibox function
			$('.sparkline15-collapse-link').on('click', function () {
				var button = $(this).find('i');
				button.toggleClass('fa-chevron-up').toggleClass('fa-chevron-down');
				$( ".sparkline15-graph" ).slideToggle( "slow" );
			});
			$(".sparkline15-collapse-close").on('click', function(){
				$( "div.sparkline15-list" ).fadeOut( 600 );
			});
 
		// Collapse ibox function
			$('.sparkline16-collapse-link').on('click', function () {
				var button = $(this).find('i');
				button.toggleClass('fa-chevron-up').toggleClass('fa-chevron-down');
				$( ".sparkline16-graph" ).slideToggle( "slow" );
			});
			$(".sparkline16-collapse-close").on('click', function(){
				$( "div.sparkline16-list" ).fadeOut( 600 );
			});

/////////////////// cacher les labels et les rubriques pour les blocks selon la phase ////
	$('.hidden_1').closest('.form-group').hide();
	var previous = $('.hidden_1').closest('.form-group');
	previous.prev('.nom-rubrique').hide();
 /////////////////////////////////////////////////////////////////////////////////////
	function openNav() {
		$("#mySidenav").css({'width': '55%'});
		$(".chip").css({'background-color': '#0288d1 '});
		$('#mySidenav .sidenav-content').html("<div id='loader'></div>");
	}
	function closeNav() {
		$("#mySidenav").css({'width': '0'});
		$(".chip").css({'background-color': '#bdbdbd'});
	}
    $(document).ready(function(){
		$('.closebtn').on('click', function() {
			closeNav();
		});

        $("#open").on("click", function () {
			$('#mySidenav .sidenav-head .sidenav-head-title').html('');
			var path = $(this).data('id');

            // Display sidenav
			openNav();

            // AJAX request
            $.ajax({
                url: path,
                type: 'POST',
                data: 'idProjet=' + $(this).data('idprojet'),
                success: function (response) {
                    // Add response in Modal body
                    $('#mySidenav .sidenav-content').html(response);
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    alert('Error : ' + errorThrown);
                }
            });
		});

        $(".show_cv").on("click", function () {
			var path = $(this).data('id');
			console.log(path);

            // Display sidenav
			openNav();

            // AJAX request
            $.ajax({
                url: path,
                type: 'POST',
                data: 'idPersonne=' + $(this).data('idpersonne'),
                success: function (response) {
					$('.collapse').collapse()
                    // Add response in Modal body
                    $('#mySidenav .sidenav-content').html(response);
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    alert('Error : ' + errorThrown);
                }
            });
		});

        $(".show_comments").on("click", function () {
            var path = $(this).data('id');

            // Display sidenav
			openNav();

            // AJAX request
            $.ajax({
                url: path,
                type: 'POST',
                data: 'idPersonne=' + $(this).data('idpersonne') + '&idProjet=' + $(this).data('idprojet'),
                success: function (response) {
                    // Add response in Modal body
                    $('#mySidenav .sidenav-content').html(response);
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    alert('Error : ' + errorThrown);
                }
            });
        });

        $(".show_date_rendu").on("click", function () {
            var path = $(this).data('id');
            var dhrenduexpert = $(this).data('dhrenduexpert').split('/');
            var dhrenducomite = $(this).data('dhrenducomite').split('/');
            var dhrenduphase = $(this).data('dhrenduphase').split('/');

            // Display sidenav
            openNav();

            // AJAX request
            $.ajax({
                url: path,
                type: 'POST',
                data: 'idPersonne=' + $(this).data('idpersonne') + '&idProjet=' + $(this).data('idprojet') + '&idAffectation=' + $(this).data('idaffectation'),
                success: function (response) {
                    // Add response in Modal body
					$('#mySidenav .sidenav-content').html(response);

					var date1 = dhrenduexpert[2]+'-'+dhrenduexpert[0]+'-'+dhrenduexpert[1];
					var date2 = dhrenducomite[2]+'-'+dhrenducomite[0]+'-'+dhrenducomite[1];
					var date3 = dhrenduphase[2]+'-'+dhrenduphase[0]+'-'+dhrenduphase[1];
					var ddate = new Date();
					var dyear = ddate.getFullYear();
					var dmonth = ddate.getMonth() + 1;
					var currentdate = ddate.getDate();
					ddate = dyear+'-'+dmonth+'-'+currentdate;

					$(".responsive-calendar").responsiveCalendar({
						time: dyear+'-'+dmonth,
						events: {[ddate]: {"url": "", "cls": "dhdujour"}, [date1]: {"url": "", "cls": "dhrenduexpert"}, [date2]: {"url": "", "cls": "dhrenducomite"}, [date3]: {"url": "", "cls": "dhrenduphase"}},
						onInit: function() {
							$('#calendar-1 .next-1 .btn-primary').css({'background-color': '#ffffff', 'border-color': '#ffffff'});
							$('#calendar-2 .prev-2 .btn-primary').css({'background-color': '#ffffff', 'border-color': '#ffffff'});
						},
						onDayClick: function(event) {
							console.log('click on Day');
							if (!$( this ).parent().hasClass('past') && !$( this ).parent().hasClass('not-current')) {
								$('#dhrendusent').val(this.dataset.day+'/'+this.dataset.month+'/'+this.dataset.year);
							}
						}
					});
					$('#date-jour').append(': '+currentdate+'/'+dmonth+'/'+dyear);
					$('#date-rendu-evaluateur').append(': '+dhrenduexpert[1]+'/'+dhrenduexpert[0]+'/'+dhrenduexpert[2]);
					$('#date-comite').append(': '+dhrenducomite[1]+'/'+dhrenducomite[0]+'/'+dhrenducomite[2]);
					$('#date-appel').append(': '+dhrenduphase[1]+'/'+dhrenduphase[0]+'/'+dhrenduphase[2]);
					$('#calendar-2 .next-2').trigger('click');
					$('#calendar-2 .next-2').on('click', function() {
						$('#calendar-1 .next-1').trigger('click');
					});
					$('#calendar-1 .prev-1').on('click', function() {
						$('#calendar-2 .prev-2').trigger('click');
					});
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    alert('Error : ' + errorThrown);
                }
            });
		});
	});
})(jQuery);
