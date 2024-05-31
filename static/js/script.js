function update(url) {
    window.history.pushState({}, '', site_domain + url);
}

document.addEventListener('DOMContentLoaded', function() {
    var navbarCustom = localStorage.getItem('navbar_custom');
    if (navbarCustom === '1') {
        $('#navbar-menu').attr('style', 'display: none !important');
        $('#navbar_menu_hide').removeAttr('style');
		$('#hidde_navbar').html('<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-badge-down" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 13v-6l-5 4l-5 -4v6l5 4z" /></svg>');
		$(this).attr('data-bs-original-title', 'Show Navbar');
    } else {
        $('#navbar-menu').removeAttr('style');
        $('#navbar_menu_hide').attr('style', 'display: none !important');
		$('#hidde_navbar').html('<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-badge-up" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 11v6l-5 -4l-5 4v-6l5 -4z" /></svg>');
		$(this).attr('data-bs-original-title', 'Hide Navbar');
    }
	$('#hidde_navbar').click(function(e) {
		e.preventDefault();
		
        if ($('#navbar-menu').is(':hidden')) {
			localStorage.setItem('navbar_custom', '0');
            $('#navbar-menu').removeAttr('style');
            $('#navbar_menu_hide').attr('style', 'display: none !important');
			$('#hidde_navbar').html('<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-badge-up" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 11v6l-5 -4l-5 4v-6l5 -4z" /></svg>');
			$(this).attr('data-bs-original-title', 'Hide Navbar');
		} else {
			localStorage.setItem('navbar_custom', '1');
            $('#navbar-menu').attr('style', 'display: none !important');
            $('#navbar_menu_hide').removeAttr('style');
			$('#hidde_navbar').html('<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-badge-down" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 13v-6l-5 4l-5 -4v6l5 4z" /></svg>');
			$(this).attr('data-bs-original-title', 'Show Navbar');
		}
    });
	
	$('.languages').on('click', function(e) {
		e.preventDefault();
		var dataId = $(this).data('id');
		var result = 'change_lang';
		removeCache();
		$.post(site_domain + '/execute/action.php', { result: result, data_id: dataId }, function(response) {
			var jsonData = JSON.parse(response);
			if (jsonData.type == 'success') {
				location.reload();
			}
			swal(jsonData.title, jsonData.subtitle, jsonData.type);
		});
	});
	window.copyText = function(text) {
		navigator.clipboard.writeText(text)
			.then(function() {
				console.log('Copied:', text);
			})
			.catch(function(error) {
				console.error('Error:', error);
			});
	}
	$(document).on('click', '#licenseOverview', function() {
		var dataId = $(this).data('id');
		var dataKey = $(this).data('key');
		
		$.ajax({
			url: site_domain + '/execute/action.php',
			type: 'POST',
			data: { result: 'create_info', dataid: dataId},
			success: function(response) {
				var jsonData = JSON.parse(response);
				if (jsonData.success == 1) {
					$('#input_license_id').val(dataId);
					$('#input_license_key_modal').val(dataKey);
					$('#lm_expire').html(jsonData.status);
					$('#lm_content').html(jsonData.html);
					$('#licenseInformation').offcanvas('show');
				} else if (jsonData.success == 3) {
					alertify.set('notifier','position', 'top-right');
					alertify.notify(jsonData.message, 'error', 5, function(){  console.log(jsonData.message); });
				} else {
					alertify.set('notifier','position', 'top-right');
					alertify.notify(jsonData.message, 'error', 5, function(){  console.log(jsonData.message); });
				}
			}
		});
	});
	
	$(document).on('click', '.license_overview', function() {
		var license_id = $('#input_license_id').val();
		var license_key = $('#input_license_key_modal').val();
		location.href = site_domain + '/license/' + license_key + '.' + license_id;
	});
	$(document).on('click', '.license_edit', function() {
		var license_id = $('#input_license_id').val();
		var license_key = $('#input_license_key_modal').val();
		location.href = site_domain + '/license/' + license_key + '.' + license_id + '/edit';
	});
	$(document).on('click', '.license_delete', function() {
		var license_id = $('#input_license_id').val();
		var license_key = $('#input_license_key_modal').val();
		location.href = site_domain + '/license/' + license_key + '.' + license_id + '/delete';
	});
	
	$(document).on('click', '.save_refill_key', function() {
		var chars = $('#settings_char_key').val();
		
		setPermanentCookie('refill_key_chars', chars);
	});
	
	$(document).on('click', '.refill_key', function(e) {
		e.preventDefault();
		
		var chars = $('#settings_char_key').val();
		if (chars.trim() === '') { 
			$('#settings_char_key').val('0123456789asdfghjklqwertyuiopzxcvbnm0123456789ASDFGHJKLQWERTYUIOPZXCVBNM0123456789asdfghjklqwertyuiopzxcvbnmasdfghjklqwertyuiopzxcvbnmdfsgfd'); 
		}
		
		refillKey();
	});
	
	$(document).on('click', '.sync_plataform', function(e) {
		var dataName = $(this).data('name');
		var dataId = $(this).data('id');
		var dataExample = $(this).data('example');
		var dataOptions = $(this).data('options');
		
		$('#plataform_name').text(dataName);
		$('#plataform_id').val(dataId);
		if (dataOptions == 'polymart.org') {
			$.ajax({
				type: "POST",
				url: site_domain + '/execute/action.php',
				data: { result: 'redirectPolymart' },
				success: function(response) {
					var jsonData = JSON.parse(response);
					location.href = jsonData.message;
				}
			});
		}
	});
	
	$(document).on('click', '.confirm_creating', function(e) {
		e.preventDefault();
		$('#generating_new_license').submit();
	});
	$('#generating_new_license').submit(function(e) {
        e.preventDefault();
        var formData = $(this).serializeArray();
		formData.push({ name: 'result', value: 'create_license' });
		var i = 0;
		var expireValue = $('#expire').val();
		if (expireValue.trim() === '') { $('#expire').addClass('is-invalid'); i++; } else { $('#expire').removeClass('is-invalid'); }
		
		var clientValue = $('#client_id').val();
		if (clientValue.trim() === '') { $('#client_id').addClass('is-invalid'); i++; } else { $('#client_id').removeClass('is-invalid'); }
		
		var keyValue = $('#license_key').val();
		if (keyValue.trim() === '') { $('#license_key').addClass('is-invalid'); i++; } else { $('#license_key').removeClass('is-invalid'); }
		
		var ipValue = $('#ip_cap').val();
		if (ipValue.trim() === '') { $('#ip_cap').addClass('is-invalid'); i++; } else { $('#ip_cap').removeClass('is-invalid'); }
		
		if (i > 0) { return; }
		
		$.ajax({
			type: "POST",
			url: site_domain + '/execute/action.php',
			data: formData,
			success: function(response) {
				var jsonData = JSON.parse(response);
				alertify.set('notifier','position', 'top-right');
				alertify.notify(jsonData.message, jsonData.type, 5, function(){  console.log(jsonData.message); });
				if (jsonData.type == 'success') {
					location.href = site_domain + '/license/' + keyValue + '.' + jsonData.id;
				}
			}
		});
	});
	
	
	$(document).on('click', '.edit_plataform', function(e) {
		var dataName = $(this).data('name');
		var dataLink = $(this).data('link');
		var dataId = $(this).data('id');
		var dataExample = $(this).data('example');
		
		$('#plataform_name').text(dataName);
		$('#plataform_name_input').val(dataName);
		$('#plataform_link').val(dataLink);
		$('#plataform_example').text(dataExample);
	});
	
    var totalList = $('#total');
    var searchBox = $('#search');
    var pageIn = $('#paginationID');
    var optionIn = $('#option');


	window.licenseCall = function() {
        var result = 'license';
        var total = totalList.val();
        var search = searchBox.val();
        var page = pageIn.val();
        var options = optionIn.val();
        var pageUse = $('#page').val();
		
        $.post(site_domain + '/execute/table.php', { result: result, total: total, search: search, pag: page, options : options, page : pageUse },
            function(response) {
				
                $('#load_index_result').html(response);
            }
        );
    }
	window.userCall = function() {
        var result = 'user';
        var total = totalList.val();
        var search = searchBox.val();
        var page = pageIn.val();
        var options = optionIn.val();
		
        $.post(site_domain + '/execute/table.php', { result: result, total: total, search: search, pag: page, options : options },
            function(response) {
                $('#load_index_result').html(response);
            }
        );
    }
	
	window.productCall = function() {
        var result = 'product';
        var total = totalList.val();
        var search = searchBox.val();
        var page = pageIn.val();
        var options = optionIn.val();
		
        $.post(site_domain + '/execute/table.php', { result: result, total: total, search: search, pag: page, options : options },
            function(response) {
                $('#load_index_result').html(response);
            }
        );
    }
	
	window.groupCall = function() {
        var result = 'groups';
        var total = totalList.val();
        var search = searchBox.val();
        var page = pageIn.val();
        var options = optionIn.val();
		
        $.post(site_domain + '/execute/table.php', { result: result, total: total, search: search, pag: page, options : options },
            function(response) {
                $('#load_index_result').html(response);
            }
        );
    }
	
	window.plataformCall = function() {
        var result = 'plataform';
        var total = totalList.val();
        var search = searchBox.val();
        var page = pageIn.val();
        var options = optionIn.val();
		
        $.post(site_domain + '/execute/table.php', { result: result, total: total, search: search, pag: page, options : options },
            function(response) {
                $('#load_index_result').html(response);
            }
        );
    }
	
	window.requestCall = function() {
        var result = 'plataform';
        var total = totalList.val();
        var search = searchBox.val();
        var page = pageIn.val();
        var options = optionIn.val();
		
        $.post(site_domain + '/execute/table.php', { result: result, total: total, search: search, pag: page, options : options },
            function(response) {
                $('#request_container').html(response);
            }
        );
    }
	
	
    totalList.change(function() { callByType(window.activeTab); });
    optionIn.change(function() { callByType(window.activeTab); });
    searchBox.change(function() { callByType(window.activeTab); });
    searchBox.on('input', function() { callByType(window.activeTab); });
    window.updatePage = function(type, id) {
        $('#paginationID').val(id);
        callByType(type);
    };
	
    window.updateOption = function(event, type, result) {
		event.preventDefault();
		var optionInput = $('#option');
		var icon = $(event.currentTarget).find('.icon_select');
		var hasSuffix = result.includes('#');
		if (optionInput.val() === result) {
			var currentOrder = hasSuffix ? result.split('#')[1] : 'ASC';
			var newOrder = (currentOrder === 'ASC') ? 'DESC' : 'ASC';
			result = result.split('#')[0] + (newOrder === 'ASC' ? '' : '#' + newOrder);
            setPermanentCookie('column_' + type + '_selected_icon', newOrder);
            var columnCookie = getCookie('column_' + type + '_selected');
		}
		setPermanentCookie('column_' + type + '_selected', result);
		optionInput.val(result);
		callByType(type);
	};
	
	
    function callByType(type) {
        switch (type) {
            case 'license':
                licenseCall();
                break;
            case 'user':
                userCall();
                break;
            case 'product':
                productCall();
                break;
            case 'plataform':
                plataformCall();
                break;
            case 'request':
                requestCall();
                break;
            case 'group':
                groupCall();
                break;
        }
    }
	
	function generateUniqueId() {
        return Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15);
    }

    function getCookie(name) {
        var value = "; " + document.cookie;
        var parts = value.split("; " + name + "=");
        if (parts.length === 2) return parts.pop().split(";").shift();
    }
	
	function getCookies(name) {
		var cookieName = name + "=";
		var decodedCookie = decodeURIComponent(document.cookie);
		var cookieArray = decodedCookie.split(';');
		for (var i = 0; i < cookieArray.length; i++) {
			var cookie = cookieArray[i].trim();
			if (cookie.indexOf(cookieName) === 0) {
				return cookie.substring(cookieName.length, cookie.length);
			}
		}
		return null;
	}

    function setPermanentCookie(name, value) {
        var d = new Date();
        d.setTime(d.getTime() + (365 * 24 * 60 * 60 * 1000)); // 365 días
        var expires = "expires=" + d.toUTCString();
        document.cookie = name + "=" + value + ";" + expires + ";path=/";
    }
	
	window.randomCodes = function(length, characters) {
		var result = '';
		var charactersLength = characters.length;
		for (var i = 0; i < length; i++) {
			result += characters.charAt(Math.floor(Math.random() * charactersLength));
		}
		return result;
	}

});

function copyText(text) {
    var input = document.createElement('input');
    input.setAttribute('value', text);
    document.body.appendChild(input);
    input.select();
    var result = document.execCommand('copy');
    document.body.removeChild(input);
	alertify.notify('Copied!', 'success', 5, function(){  console.log('copied'); });
    return result;
}
function copyOther(text) {
    var input = document.getElementById(text);
    input.select();
    var result = document.execCommand('copy');
	alertify.notify('Copied!', 'success', 5, function(){  console.log('copied'); });
    return result;
}


	function changeLanguage(lang) {
		$.post( site_domain + '/execute/language.php', { language : lang }, 
		   function( response ) {
			location.reload();
		   }
		);
	}
	
function logoutSession() {
	$.ajax({
		type: "POST",
		url: site_domain + '/execute/logout.php',
		data: $(this).serialize(),
		success: function(response)
		{
			setTimeout("location.href = " + site_domain + ";", 0000);
			alertify.notify('You have successfully closed your session!', 'success', 5, function(){  console.log('Session closed'); });
		}
	});
}
function refillKey() {
    var chars = document.getElementById('settings_char_key').value; // Obtener el valor del campo 'chars' del formulario
    var line_1 = customChar(8, chars);
    var line_2 = customChar(4, chars);
    var line_3 = customChar(4, chars);
    var line_4 = customChar(4, chars);
    var line_5 = customChar(12, chars);

    var separator = '-';
    var key = line_1 + separator + line_2 + separator + line_3 + separator + line_4 + separator + line_5;

    document.getElementById('license_key').value = key;
	alertify.notify('Refilled', 'success', 5, function(){  console.log('Refilled'); });
}

function customChar(length, chars) {
    var result = '';
    var characters = chars.split('');
    var charactersLength = characters.length;
    for (var i = 0; i < length; i++) {
        result += characters[Math.floor(Math.random() * charactersLength)];
    }
    return result;
}