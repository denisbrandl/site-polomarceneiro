/** Cadastro Usuarios **/
$("#cnpj").val("04.412.900/2066-50");
$("#inscricao_estadual").val("123456789");
$("#razao_social").val("Denis Brandl 03366544996");
$("#nome_fantasia").val("Minor Soluções Web");
$("#nome_completo").val("Denis Brandl");
$("#email").val("denisbr@gmail.com");
$("#senha").val("123456");
$("#senha2").val("123456");
$("#telefone").val("(47) 99178-6533");
$("#cep").val("89040-400");
$("#endereco").val("Rua Divinópolis");
$("#endereco_numero").val("866");
$("#complemento").val("Apto 421");
$("#bairro").val("Velha Central");
$("#cidade").val("Blumenau");
$("#uf").val("SC");

/** Cadastro de Materiais */

$("#titulo").val("MDF Gobi Conceito 2 Faces 6mm Duratex");
$("#id_categoria").val("1");
$("#id_subcategoria").val("4");
$("#id_marca").val("1");
$("#id_linha").val("2");
$("#id_cor").val("7");
$("#quantidade").val("2");
$("#unidade_medida").val("1");
$("#largura").val("2750");
$("#altura").val("1840");
$("#espessura").val("6");
$("#profundidade").val("0");
$("#peso").val("0");

function geraCamposPreenchidos() {
    var str_campos_preenchidos = '';
    $("#frmFormularioPrincipal").map(function(idx, elem) {
        str_campos_preenchidos = str_campos_preenchidos + '$("#'+ $(elem).attr('name')+ '").val("'+$(elem).val()+'");';
    });    
    console.log(str_campos_preenchidos);
}

function geraEstruturaJqueryValidation() {
	var str_estrutura_rules = '';
	var str_estrutura_messages = '';
	$("#frmFormularioPrincipal").map(function(idx, elem) {
		if ($(elem).attr('required') == "required") {
			str_estrutura_rules = str_estrutura_rules + $(elem).attr('name') + ':\t{\n\trequired: true\n\t},\n';
			str_estrutura_messages = str_estrutura_messages + $(elem).attr('name') + ': {\n    required: "'+$( '#'+$(elem).attr('id') ).next().text().trim()+'"\n},\n';			
		}
	}
	console.log(str_estrutura_rules);
	console.log(str_estrutura_messages);
}