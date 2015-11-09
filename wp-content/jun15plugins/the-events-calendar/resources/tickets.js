var ticketHeaderImage = window.ticketHeaderImage || {};

(function (window, $, undefined) {

	ticketHeaderImage = {

		// Call this from the upload button to initiate the upload frame.
		uploader: function () {

			var frame = wp.media({
				title: HeaderImageData.title,
				multiple: false,
				library: { type: 'image' },
				button: { text: HeaderImageData.button }
			});

			// Handle results from media manager.
			frame.on('close', function () {
				var attachments = frame.state().get('selection').toJSON();
				if (attachments.length)
					ticketHeaderImage.render(attachments[0]);
			});

			frame.open();
			return false;
		},
		// Output Image preview and populate widget form.
		render: function (attachment) {
			$('#tribe_ticket_header_preview').html(ticketHeaderImage.imgHTML(attachment));
			$('#tribe_ticket_header_image_id').val(attachment.id);
			$('#tribe_ticket_header_remove').show();
		},
		// Render html for the image.
		imgHTML: function (attachment) {
			var img_html = '<img src="' + attachment.url + '" ';
			img_html += 'width="' + attachment.width + '" ';
			img_html += 'height="' + attachment.height + '" ';
			img_html += '/>';
			return img_html;
		}
	};


	$(document).ready(function () {

		var datepickerOpts = {
			dateFormat: 'yy-mm-dd',
			showAnim: 'fadeIn',
			changeMonth: true,
			changeYear: true,
			numberOfMonths: 3,
			showButtonPanel: true,
			onChange: function () {
			},
			onSelect: function (dateText, inst) {
				var the_date = $.datepicker.parseDate('yy-mm-dd', dateText);
				if (inst.id === "ticket_start_date") {
					$("#ticket_end_date").datepicker('option', 'minDate', the_date);
					if (the_date)
						$(".ticket_start_time").show();
					else
						$(".ticket_start_time").hide();
				} else {
					$("#ticket_start_date").datepicker('option', 'maxDate', the_date);
					if (the_date)
						$(".ticket_end_time").show();
					else
						$(".ticket_end_time").hide();
				}
			}
		};

		$("#ticket_start_date").datepicker(datepickerOpts).keyup(function (e) {
			if (e.keyCode == 8 || e.keyCode == 46) {
				$.datepicker._clearDate(this);
			}
		});
		$("#ticket_end_date").datepicker(datepickerOpts).keyup(function (e) {
			if (e.keyCode == 8 || e.keyCode == 46) {
				$.datepicker._clearDate(this);
			}
		});

		/* Show the advanced metabox for the selected provider and hide the others on selection change */
		$('input[name=ticket_provider]:radio').change(function () {
			$('tr.ticket_advanced').hide();
			$('tr.ticket_advanced_' + this.value).show();
		});

		/* Show the advanced metabox for the selected provider and hide the others at ready */
		$('input[name=ticket_provider]:checked').each(function () {
			$('tr.ticket_advanced').hide();
			$('tr.ticket_advanced_' + this.value).show();
		});

		/* "Add a ticket" link action */
		$('a#ticket_form_toggle').click(function (e) {
			$('h4.ticket_form_title_edit').hide();
			$('h4.ticket_form_title_add').show();
			$(this).hide();
			ticket_clear_form();
			$('#ticket_form').show();
			$("#ticket_name.ticket_field").val( $("#title").val() );//JER innovation automation across the nation
			//console.log("JER ticket is now " + $("#ticket_name").val());
			$("#ticket_end_date").val( $("#EventStartDate").val() ); //JER added to set ticket date from event date
			//console.log("JER ticket date is " + $("#ticket_end_date").val());
			$('html, body').animate({
				scrollTop: $("#ticket_form_table").offset().top - 50
			}, 500);
			e.preventDefault();
		});

		/* "Cancel" button action */
		$('#ticket_form_cancel').click(function () {

			ticket_clear_form();

			$('html, body').animate({
				scrollTop: $("#event_tickets").offset().top - 50
			}, 500);

		});

		/* "Save Ticket" button action */
		$('#ticket_form_save').click(function () {

			tickets_start_spin();
			//console.log($('.ticket_field').serialize());
			var params = {
				action: 'tribe-ticket-add-' + $('input[name=ticket_provider]:checked').val(),
				formdata: $('.ticket_field').serialize(),
				post_ID: $('#post_ID').val(),
				nonce: TribeTickets.add_ticket_nonce
			};
			//console.log('params set ' + params.action);
			$.post(
				ajaxurl,
				params,
				function (response) {
					if (response.success) {
						//console.log('successful action ' + ajaxurl);
						$("#EventCost").val( "$" + $("#ticket_price.ticket_field").val() + "/$" + $("#dayof_price.ticket_field").val() );
						ticket_clear_form();
						$('td.ticket_list_container').empty().html(response.data);
						$('.ticket_time').hide();
					}
				},
				'json'
			).error(function(d,t,e) {
    			// catch error
				console.log('save error ['+ d.responseText + '] ' + t + ' e: ' + e);
			}).complete(function () {
					$('html, body').animate({
						scrollTop: $("#event_tickets").offset().top - 50
					}, 500);

					tickets_stop_spin();
				});

		});

		/* "Delete Ticket" link action */

		$('#tribetickets').on('click', '.ticket_delete', function (e) {
			//console.log('gonna try delete');
			e.preventDefault();

			tickets_start_spin();

			var params = {
				action: 'tribe-ticket-delete-' + $(this).attr("attr-provider"),
				post_ID: $('#post_ID').val(),
				ticket_id: $(this).attr("attr-ticket-id"),
				nonce: TribeTickets.remove_ticket_nonce
			};

			$.post(
				ajaxurl,
				params,
				function (response) {
				//console.log('deleting ticket');
					if (response.success) {
						ticket_clear_form();
						$('td.ticket_list_container').empty().html(response.data);
					}
				},
				'json'
			).error(function(d,t,e) {
    			// catch error
				console.log('error d:' + d.responseText + ' ' + t + ' e: ' + e);
			}).complete(function () {
					tickets_stop_spin();
				});


		});

		/* "Edit Ticket" link action */

		$('#tribetickets')
			.on('click', '.ticket_edit', function (e) {
				e.preventDefault();

				$('h4.ticket_form_title_edit').show();
				$('h4.ticket_form_title_add').hide();
				tickets_start_spin();

				var params = {
					action: 'tribe-ticket-edit-' + $(this).attr("attr-provider"),
					post_ID: $('#post_ID').val(),
					ticket_id: $(this).attr("attr-ticket-id"),
					nonce: TribeTickets.edit_ticket_nonce
				};
				$.post(
					ajaxurl,
					params,
					function (response) {
						ticket_clear_form();

						$('#ticket_id').val(response.data.ID);
						//if(response.data.name) 
						$('#ticket_name').val(response.data.name);
						$('#ticket_description').val(response.data.description);
						$('#ticket_price').val(response.data.price);
						if (typeof response.data.start_date == 'string' || response.data.start_date instanceof String ) {
							var start_date = response.data.start_date.substring(0, 10);
							var end_date = response.data.end_date.substring(0, 10);
							$('#ticket_start_date').val( start_date );
							$('#ticket_end_date').val( end_date );
							var start_hour = response.data.start_date.substring(11, 13);
							var start_meridian = 'am';
							if (parseInt(start_hour) > 12) {
								start_meridian = 'pm';
								start_hour = parseInt(start_hour) - 12;
								start_hour = ("0" + start_hour).slice(-2);
							}
							if (parseInt(start_hour) === 12) {
								start_meridian = 'pm';
							}

							$('#ticket_start_hour').val(start_hour);
							$('#ticket_start_meridian').val(start_meridian);

							$('.ticket_start_time').show();
						} else {
							//console.log("start_date is not a string it is " + response.data.start_date);
						}

						if (response.data.end_date) {

							var end_hour = response.data.end_date.substring(11, 13);
							var end_meridian = 'am';
							if (parseInt(end_hour) > 12) {
								end_meridian = 'pm';
								end_hour = parseInt(end_hour) - 12;
								end_hour = ("0" + end_hour).slice(-2);
							}
							if (parseInt(end_hour) === 12) {
								end_meridian = 'pm';
							}

							$('#ticket_end_hour').val(end_hour);
							$('#ticket_end_meridian').val(end_meridian);

							$('#ticket_start_minute').val(response.data.start_date.substring(14, 16));
							$('#ticket_end_minute').val(response.data.end_date.substring(14, 16));

							$('.ticket_end_time').show();
						}

						$('tr.ticket_advanced_' + response.data.provider_class).remove();
						$('tr.ticket.bottom').before(response.data.advanced_fields);

						$('input:radio[name=ticket_provider]').filter('[value=' + response.data.provider_class + ']').click();

						$('a#ticket_form_toggle').hide();
						$('#ticket_form').show();

					},
					'json'
				).error(function(d,t, e) {
    				// catch error
					console.log('edit error ' + t + ' e: ' + e);
				}).complete(function () {
						$('html, body').animate({
							scrollTop: $("#ticket_form_table").offset().top - 50
						}, 500);

						tickets_stop_spin();
					});

			})
			.on('click', '#tribe_ticket_header_image', function (e) {
				e.preventDefault();
				ticketHeaderImage.uploader( '', '' );
			});


		var $remove = $('#tribe_ticket_header_remove');
		var $preview = $('#tribe_ticket_header_preview');

		if ($preview.find('img').length)
			$remove.show();

		$remove.live('click', function (e) {

			e.preventDefault();
			$preview.html('');
			$remove.hide();
			$('#tribe_ticket_header_image_id').val('');

		});

		/* Helper functions */

		function ticket_clear_form() {
			//console.log('ticket_clear_form');
			$('a#ticket_form_toggle').show();

			//JER $('#ticket_form input:not(:button):not(:radio):not(:checkbox)').val('');
			$('#ticket_form input:checkbox').attr('checked', false);

			$('.ticket_start_time').hide();
			$('.ticket_end_time').hide();

			//$('#ticket_form textarea').val('Purchase this ticket if you are a Member or will be the Guest of a Member');

			$('#ticket_form').hide();
		}

		function tickets_start_spin() {
			jQuery('#event_tickets').css('opacity', '0.5');
			jQuery("#tribe-loading").show();
		}

		function tickets_stop_spin() {
			jQuery('#event_tickets').css('opacity', '1');
			jQuery("#tribe-loading").hide();
		}

		if ($('#tribe_ticket_header_preview img').length) {

			var $tiximg = $('#tribe_ticket_header_preview img');
			$tiximg.removeAttr("width").removeAttr("height");

			function tribe_fix_image_width() {
				if ($('#tribetickets').width() < $tiximg.width()) {
					$tiximg.css("width", '95%');
				}
			}

			tribe_fix_image_width();
		}
	});

})(window, jQuery);
