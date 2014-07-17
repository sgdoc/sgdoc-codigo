;
function jquery_get_checked_combos(classe){
    var string = '';
    $('.'+classe).each(function(){
        if($(this).attr("checked") == true){
            string += '|' + $(this).val();
        }
    }); 
    return string;                      
}

function jquery_inverter_string(string){
    var i = string.length;
    var s = '';
    if(i>0){
        i=i-1;
        for (var x = i; x >=0; x--){
            s = s + string.charAt(x);
        }
    }
    return s;
}

/**
 * Complementa mensagem de registro vazio com conteúdo da Pesquisa efetuada
 * @param {string} parStrDataTable
 */
function jquery_datatable_complementa_mensagem_vazia( parStrDataTable )
{
    var objPesquisa = $('#'+ parStrDataTable +'_filter input');
    if( objPesquisa.val() != '' ){        
        $('#' + parStrDataTable +' tbody tr td').append(" Pesquisa Efetuada: [" + objPesquisa.val() + "]");
    }
}

function jquery_replace_data(string){
    var a = string.split('/');

    if(a[0]!=undefined && a[1]!=undefined && a[2]!=undefined){
        return(a[2]+'-'+a[1]+'-'+a[0]);
    }else if(a[0]!=undefined && a[1]!=undefined){
        if(a[1].length==4){
            return(a[1]+'-');
        }else{
            return(a[1]+'-'+a[0]);
        }
    }else if(a[0]!=undefined){
        return(a[0]);
    }else{
        return(string);
    }
}

function convertDateToString(data){
    if(data.length==10){
        return data.substr(8, 2) +'/'+data.substr(5, 2)+'/'+data.substr(0, 4);
    }else if (data.length > 10){
        return data.substr(8, 2) + '/' + data.substr(5, 2)+'/'+data.substr(0, 4) + ' ' + data.substr(10);
    }else{
        return 'Em Branco';
    }
}

function gerarRaiz(digital){
    raiz = (digital / 10000);
    raiz = Math.floor(raiz);
    raiz = 'LOTE' + raiz;
    return raiz;

}

function limparCamposTexto(campo){
    for(var j in campo){
        document.getElementById(campo[j]).value="";
    }
}

function addOption(selectbox,text,value){
    var optn = document.createElement("OPTION");
    optn.text = text;
    optn.value = value;
    selectbox.options.add(optn);
}

function delOption(selectbox){
    alert(selectbox.value);
    for (var i = selectbox.options.length - 1; i >= 0; i--){
        selectbox.options[i] = null;
    }
    selectbox.selectedIndex = -1;
}

function selectOption(selectbox,posicao){
    for(var j in selectbox){
        document.getElementById(selectbox[j]).selectedIndex = posicao;
    }
}

function mostrarDiv(obj){
    var div = $('#'+obj);
    div.slideDown('faster');
}

function ocultarDiv(obj){
    var div = $('#'+obj);
    div.slideUp('faster');
}

function limpaCombo(idCombo){
    var oList = document.getElementById(idCombo);
    for (var i = oList.options.length - 1; i >= 0; i--){
        oList.options[i] = null;
    }
    oList.selectedIndex = -1;
}

function limparFrame(idFrame){
    document.getElementById(idFrame).src="fundo_visualizador.php";
}

function SoNumero(nro){
    var valid    = "0123456789";
    var numerook = "";
    var temp;

    for (var i=0; i< nro.length; i++) {
        temp = nro.substr(i, 1);
        if (valid.indexOf(temp) != -1)
            numerook = numerook + temp;
    }
    return(numerook);
}


function DigitaLetra(obj){
    return obj;
    var valid    = " abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ ";
    var numerook = "";
    var temp;

    for (var i=0; i< obj.value.length; i++) {
        temp = obj.value.substr(i, 1);
        if (valid.indexOf(temp) != -1)
            numerook = numerook + temp;
    }
    obj.value = numerook;
}


function DigitaLetraSeguro(obj){
    return obj;
    var strPos = obj.selectionStart;

    var valid    = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-1234567890.,/: ";
    var numerook = "";
    var temp;
    for (var i=0; i< obj.value.length; i++) {
        temp =  limpar_string(obj.value.toString().charAt(i));
        if (valid.indexOf(temp) != -1)
            numerook = numerook + temp.toUpperCase();
    }
    obj.value = numerook;
    obj.selectionEnd = strPos;
}

function limpar_string(text) {
    return text;
    text = text.replace(new RegExp('[ÀÁÂÃÄÅ]','gi'), 'A');
    text = text.replace(new RegExp('[äáàâã]','gi'), 'a');
    text = text.replace(new RegExp('[ëéèê]','gi'), 'e');
    text = text.replace(new RegExp('[ÈÉÊË]','gi'), 'E');
    text = text.replace(new RegExp('[ìíî]','gi'), 'i');
    text = text.replace(new RegExp('[ÍÌÎ]','gi'), 'I');
    text = text.replace(new RegExp('[ÖÓÒÔÕ]','gi'), 'O');
    text = text.replace(new RegExp('[öóòôõ]','gi'), 'o');
    text = text.replace(new RegExp('[ÜÚÙÛ]','gi'), 'U');
    text = text.replace(new RegExp('[üúùû]','gi'), 'U');
    text = text.replace(new RegExp('[Çç]','gi'), 'C');
    text = text.replace(new RegExp('[ºª!#$%^&*<>?"~`]','gi'), ' ');
    return text;
}

function DigitaNumero(obj){
    var valid    = "1234567890 ";
    var numerook = "";
    var temp;

    for (var i=0; i< obj.value.length; i++) {
        temp = obj.value.substr(i, 1);
        if (valid.indexOf(temp) != -1)
            numerook = numerook + temp;
    }
    obj.value = numerook;
}

function zeroFill(obj, size){
    var temp;
    
    temp = ('0000000' + obj.value).slice(-7);
    obj.value = temp;
}

function DigitaNumeroSeguro(obj){
    var valid    = "1234567890/ ";
    var numerook = "";
    var temp;

    for (var i=0; i< obj.value.length; i++) {
        temp = obj.value.substr(i, 1);
        if (valid.indexOf(temp) != -1)
            numerook = numerook + temp;
    }
    obj.value = numerook;
}


function TamanhoMax(campo, TamanhoMaximo){
    if (campo.value.length > TamanhoMaximo)	{
        campo.value = campo.value.substring(0,TamanhoMaximo);
    }
	
}

function ImprimirTela(){
    document.getElementById('rodape').style.display="block";
    window.print();
    window.location.reload();
}


function BloquearCampos(campos,tipo,status){

    switch(tipo)    {
	
        case "disabled":
            for(campo in campos)            {
                document.getElementById(campos[campo]).disabled=status;
            }
            break;
	
        case "value":
            for(campo in campos)            {
                document.getElementById(campos[campo]).value=status;
            }
            break;
		
        case "readonly":
            for(campo in campos)            {
                document.getElementById(campos[campo]).readOnly=status;
            }
            break;
		
        default:
            alert("Atributo Invalido...");
            break;
    }

}

function FiltraCampo(codigo) {
    try{
        
        if(typeof(codigo) == 'undefined'){
            return '';
        }

        var s = "";
        var tam = codigo.length;
                
        for (i = 0; i < tam ; i++) {
            if (codigo.substring(i,i + 1) == "0" ||
                codigo.substring(i,i + 1) == "1" ||
                codigo.substring(i,i + 1) == "2" ||
                codigo.substring(i,i + 1) == "3" ||
                codigo.substring(i,i + 1) == "4" ||
                codigo.substring(i,i + 1) == "5" ||
                codigo.substring(i,i + 1) == "6" ||
                codigo.substring(i,i + 1) == "7" ||
                codigo.substring(i,i + 1) == "8" ||
                codigo.substring(i,i + 1) == "9"  )
                s = s + codigo.substring(i,i + 1);
        }
        return s;  

    }catch(e){
        
    }

    
    
}

function substituirTexto(elemento, texto) {
    if (elemento != null) {
        limparTexto(elemento);
        var newNode = document.createTextNode(texto);
        elemento.appendChild(newNode);
    }
}

function limparTexto(elemento) {
    if (elemento != null) {
        if (elemento.childNodes) {
            for (var i = 0; i < elemento.childNodes.length; i++) {
                var childNode = elemento.childNodes[i];
                elemento.removeChild(childNode);
            }
        }
    }
}

function mascara_cpf_usuario(cpf){
    return cpf.substr(0,3) + '.' + cpf.substr(3,3) + '.' + cpf.substr(6,3)+ '-' + cpf.substr(9,2);
}

function formatar_telefone_sem_ddd(objeto){

    var valid    = "0123456789-";
    var numerook = "";
    var temp;
    var nro = objeto.value;

    for (var i=0; i< nro.length; i++) {
        temp = nro.substr(i, 1);
        if (valid.indexOf(temp) != -1)
            numerook = numerook + temp;
    }
  
    if(numerook.length == 4)
        objeto.value = numerook + '-';
}


function strZero(obj,nBytes){
    var strRetorno = obj.value.toString();
    if (obj.value.length != nBytes)
        for (var i=1;i<= nBytes - obj.value.length;i++)
            strRetorno = "0" + strRetorno;
    obj.value = strRetorno;
}


function Trim(obj){
    Ltrim(obj);
    Rtrim(obj);
}


function Rtrim(obj) {
    varx = obj.value;
    while (varx.substr(varx.length - 1,1) == " ")
    {
        varx = varx.substr(0, varx.length -1);
    }
    obj.value = varx;
}


function Ltrim(obj) {
    varx = obj.value;
    while (varx.substr(0,1) == " ")
    {
        varx = varx.substr(1, varx.length -1);
    }
    obj.value = varx;
}

function limparCampoData(id){
    document.getElementById(id).value = "";
}


function formatarTelefoneComDDD(telefone){
    msg  = "(" + telefone.substring(0,2) + ") ";
    msg += telefone.substring(2,6) + "-";
    msg += telefone.substring(6);
    return msg;
}

function formatarCPF(cpf){
    msg  = cpf.substring(0,3) + ".";
    msg += cpf.substring(3,6) + ".";
    msg += cpf.substring(6,9) + "-";
    msg += cpf.substring(9);
    return msg;
}

function soNumero(str){
    val = '';
    for (x = 0; x < str.length; x++) {
        if (str.charAt(x) == '0'){
            val += str.charAt(x);
        }
        else if(parseInt(str.charAt(x))){
            val += str.charAt(x);   
        }
    }
    return val;
}

function limparFormulario(element){
    $(element).find(':input').each(function() {
        switch(this.type){
            case 'password':
            case 'hidden':
            case 'select-multiple':
            case 'select-one':
            case 'text':
            case 'textarea':
                $(this).val('');
                break;
            case 'checkbox':
            case 'radio':
                this.checked = false;
        }
    });
}

function verificaFormularioPreenchido(element){
    var result = false;
    $(element).find(':input').each(function() {
        if($(this).val() != ""){
            result = true;
        }
    });
    return result;
}