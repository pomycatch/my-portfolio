$(function() {

$('.contact-form button').click(function(event){
	var name = $('input[name=name]');
	var email = $('input[name=email]');
	var message = $('textarea');
	var messageLength = message.val().length;
	var pattern = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
	var is_email = pattern.test(email.val());
	
	if (!is_email && messageLength > 5) {
		$('<p class="form-invalid">Niepoprawny adres e-mail. Wiadomość nie została wysłana.</p>').insertBefore('.wrapper-contact-inputs').hide().slideDown().delay(5000).slideUp();
		return false;
	}

	if (messageLength <= 5 && is_email){
		$('<p class="form-invalid">Treść wiadomości musi być dłuższa niż 5 znaków. Wiadomość nie została wysłana.</p>').insertBefore('.wrapper-contact-inputs').hide().slideDown().delay(5000).slideUp();
		return false;
	}

	if (!is_email && messageLength <= 5 ){
		$('<p class="form-invalid">Adres e-mail jest niepoprawny, a treść wiadomości musi być dłuższa niż 5 znaków. Wiadomość nie została wysłana.</p>').insertBefore('.wrapper-contact-inputs').hide().slideDown().delay(5000).slideUp();
		return false;
	}
			
	if (is_email && message.val().length > 5) {
        post_data = {'userName':name.val(), 'userEmail':email.val(), 'userMessage':message.val(), 'form': 'kontakt'};
        $.post(window.location.pathname + "/../emailSender/contact_me.php", post_data, function(response){
	        if(response.type == 'error'){
	            var output = '<p class="form-invalid">'+response.text+'</p>';
	            $(output).insertBefore('.wrapper-contact-inputs').hide().slideDown().delay(5000).slideUp();
	        } else {
	            var output = '<p class="form-valid">'+response.text+'</p>';
	            $(output).insertBefore('.wrapper-contact-inputs').hide().slideDown().delay(5000).slideUp();
	        }
        }, 'json');
		return false;
	}
	else {
		event.preventDefault();
		$('<p class="form-invalid">Twoja wiadomość nie została wysłana.</p>').insertBefore('.wrapper-contact-inputs').hide().slideDown().delay(5000).slideUp();
	}
});

});