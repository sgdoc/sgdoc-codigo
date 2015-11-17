/* 
 * Monta uma lista com as opções de informações para exportação
 * TODO: tranformar em um objeto js
 */

/* Os id's abaixo representam um sigla da tabela do banco (doc / prz) e o nome das colunas nas tabelas.
 * Exceto os id's "prz-ppa" e "prz-exec_orc", que representam a inclusão de todos os dados do vínculo
 * com o PPA/LOA e das execuções orçamentárias, respectivamente. 
 */

var opcoes = '';
var defaultChecked = true;

function gerarFieldset() {
	var leg = $('<legend>', { text: 'Selecione as informações para exportação:' });
	var fls = $('<fieldset>', {
		css: {
			border: '1px #9ac619 dotted',
			margin: '2px',
			padding: '5px',
			'min-height': '180px'
		}
	});
	fls.append(leg);
	return fls;
}

function tratarNo(objSpan) {
	$('#'+objSpan.attr('idDiv')).toggle('fast', function() {
		if($('#'+objSpan.attr('idDiv')).is(":visible")) {
			objSpan.text('-');
			$('#'+objSpan.attr('idDiv')).css('margin-left', '20px');
		} else {
			objSpan.text('+');				
			$('#'+objSpan.attr('idDiv')).find('div').each(function(i, div) {
				$(div).css('display', 'none');
				$('span[idDiv="'+$(div).attr('id')+'"]').text('+');
			});
		}
	});	
}

function tratarFilhos(objChk) {
	$('div[idGrupo="'+objChk.attr('id')+'"]').find(':checkbox').each(function(i, chk) {
		$(chk).attr('checked', objChk.is(':checked'));
	});
}

function tratarPais(objChk) {
	objChk.parents('div[idGrupo]').each(function(i, obj) {
		$('#'+$(obj).attr('idGrupo')).attr('checked', ($(obj).find('input:checkbox:not(:checked)').length == 0));
	});
}

function tratarSelecao(lista) {
	var selecionados = '';
	for (var i=0; i<lista.length; i++) {
		if (lista[i].filhos) {
			selecionados = selecionados + tratarSelecao(lista[i].filhos);
		} else {
			if ($('#'+lista[i].id).is(':checked')) {
				selecionados = selecionados + '|' + lista[i].id;
			}
		}
	}
	return selecionados;
}

function gerarChk(objPai, lista) {
	
	var idGrupo = 'opcoes';
	if (objPai.attr('idGrupo')) {
		idGrupo = objPai.attr('idGrupo');
	}
	
	for (var i=0; i<lista.length; i++) {
		var chk = $('<input>', {
			type: 'checkbox',
			id: lista[i].id,
			checked: defaultChecked,
			click: function() { 
				tratarFilhos($(this));
				tratarPais($(this));
				$('#hdnOpcoes').val('');
				$('#hdnOpcoes').val(tratarSelecao(opcoes));
			} 
		});
		if (objPai.attr('idGrupo')) {
			chk.addClass('chk'+idGrupo);
		}

		var lbl = $('<label>', {
			text: lista[i].label,
			'for': lista[i].id
		});
		
		var pNo = $('<p>', {
			css: { margin: '1px' }
		});
		
		var spn = null;
		var div = null;
		
		if (lista[i].filhos) {
			spn = $('<span>', {
				id: 'spn_'+lista[i].id,
				idDiv: 'div_'+lista[i].id,
				text: '+',
				css: {
					'vertical-align': 'top',
					'font-size': '1.5em',
					'cursor': 'pointer'
				},
				click: function() { tratarNo($(this)); }
			});
			
			div = $('<div>', {
				id: 'div_'+lista[i].id,
				idGrupo: lista[i].id,
				css: {
					'margin-left': '20px',
					'display': 'none'
				}
			});
			gerarChk(div, lista[i].filhos);
		}
		
		pNo.append(spn);
		pNo.append(chk);
		pNo.append(lbl);
		
		objPai.append(pNo);
		objPai.append(div);
		
	}
	
}

function montarOpcoes(objDivOpcoes, strOpcoes) {
	
	opcoes = strOpcoes;
	
	objDivOpcoes.append($('<input>', { type:'hidden', id:'hdnOpcoes'}));
	
	var fieldset = gerarFieldset();
	gerarChk(fieldset, opcoes);
	objDivOpcoes.append(fieldset);
	$('#hdnOpcoes').val(tratarSelecao(opcoes));
	
}