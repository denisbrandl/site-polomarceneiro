var formData = new FormData();
$('#enviarMensagem').click(function() {
	
	$("div.messages").empty();
    $("#tituloModal").empty();

	$("#frmFormularioPrincipal").validate({
		errorClass: 'erro-formulario',
		rules: {
			nome: {
				required: true,
				minlength: 5
			}
		},
		messages: {
			nome: {
				required: "Por favor informe o seu nome",
				minlength: "O nome necessita ter pelo menos 5 caracteres"
			}
		}
	});	
	
    if ($("#frmFormularioPrincipal").valid() == false) {
        $(".messages").append('<p class="text-danger"><strong>Por favor revise os dados preenchidos.</strong></p>');
        $('#modalMessages').modal('show');
        return false;
    }	
	
    var dados = [];
    $("#frmFormularioPrincipal input[type='text'], #frmFormularioPrincipal input[type='email'], #frmFormularioPrincipal textarea").map(function(idx, elem) {
        if ($(elem).val() != '') {
            dados[$(elem).attr('name')] = $(elem).val();
			
			formData.append($(elem).attr('name'), $(elem).val());
        }       
    });	
	
	formData.append('handle_anuncio', anuncio);
	
    $.ajax({
        url: url_global+'/api/enviarEmail/',
        type: 'post',
        dataType: 'json',
        contentType: false,
		processData: false,
        data: formData,
        success: (function (dados) {
            if (dados.success == 'false') {
                $(".modal-title").append('Erro enviar a mensagem');
                $(".messages").append(dados.message);
            }			
            if (dados.success == 'true') {
                $(".messages").append(dados.message);
            }
            $('#modalMessages').modal('show');
			
			// $("#btnEnviarFormulario").prop('disabled',false);
			// $("#btnEnviarFormulario").text('Salvar');
			
			formData = new FormData();
        })
    }); 
});

$('.msgWhatsapp').click(function() {
    var numero_telefone = $(this).attr('data-whats');
    console.log(numero_telefone);
    if(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)){
        window.open('https://api.whatsapp.com/send?phone='+numero_telefone.replace('tel:','')+'&text=Oi%2C peguei%20seu%20contato%20do%20site', '_blank');
    }else{
        window.open('https://web.whatsapp.com/send?phone='+numero_telefone.replace('tel:','')+'&text=Oi%2C peguei%20seu%20contato%20do%20site', '_blank');
    }  
});