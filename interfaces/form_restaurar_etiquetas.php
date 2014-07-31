<?php
/*
 * Copyright 2008 ICMBio
 * Este arquivo é parte do programa SISICMBio
 * O SISICMBio é um software livre; você pode redistribuíção e/ou modifição dentro dos termos
 * da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão
 * 2 da Licença.
 *
 * Este programa é distribuíção na esperança que possa ser útil, mas SEM NENHUMA GARANTIA; sem
 * uma garantia implícita de ADEQUAÇÃO a qualquer MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a
 * Licença Pública Geral GNU/GPL em português para maiores detalhes.
 * Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "LICENCA.txt",
 * junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço
 * www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF)
 * Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
 * */
?>
<script type="text/javascript">

    $(document).ready(function() {
        $('#botao-restaurar-etiquetas').click(function(){
            $('#div-restaurar-etiquetas').dialog('open');
        });

        $('#div-restaurar-etiquetas').dialog({
            title: 'Restaurar etiquetas',
            autoOpen: false,
            resizable: false,
            modal: true,
            width: 320,
            autoHeight: true,
            buttons: {
                'Adicionar Digital':function(){
                    novo();
                },
                'Restaurar Etiquetas':function(){
                    gerar();
                }
            }
        });
    });
    
    /*Functions*/
    function novo(){

        var form = document.getElementById('formulario');
        var aux = document.getElementById('aux');
        var num = new Number(aux.value);
        var TextBox = document.createElement('input');
        var NextLine = document.createElement('br');
        var NextSpan = document.createElement('span');
        var bExcluir = document.createElement('img');

        NextLine.setAttribute('id','line'+num);

        bExcluir.setAttribute('id','excluir'+num);
        bExcluir.setAttribute('onClick','return excluir("'+num+'")');
        bExcluir.setAttribute('src','imagens/fam/delete.png');

        TextBox.setAttribute('id','digital'+num);
        TextBox.setAttribute('class','FUNDOCAIXA1 digital_restaurar');
        TextBox.setAttribute('name','DIGITAL[]');
        TextBox.setAttribute('maxlength','7');
        TextBox.setAttribute('size','8');

        NextSpan.setAttribute('id','label'+num);
        NextSpan.setAttribute('class','style13');
        NextSpan.innerHTML = '#' + num + '';

        aux.value = new Number(num + 1);

        form.appendChild(NextSpan);
        form.appendChild(TextBox);
        form.appendChild(bExcluir);
        form.appendChild(NextLine);

    }

    function excluir(num) {

        var form = document.getElementById('formulario'	);
        var TextBox = document.getElementById('digital'+num);
        var Excluir = document.getElementById('excluir'+num);
        var Label = document.getElementById('label'+num);
        var Line = document.getElementById('line'+num);

        $('#label'+num).fadeOut('faster');
        $('#digital'+num).fadeOut('faster');
        $('#excluir'+num).fadeOut('faster',function(){
            form.removeChild(Label);
            form.removeChild(TextBox);
            form.removeChild(Excluir);
            form.removeChild(Line);
        });

        return false;

    }

    function gerar(){
        if(confirm("Você tem certeza que deseja restaurar as etiquetas agora?")){
            $('#formulario').submit();
        }
    }

</script>      
<!--Distribuicao-->
<div id="div-restaurar-etiquetas">
    <input id="aux" type="hidden" name="aux" value="1" />
    <form id="formulario" target="blank" action="restaurar_etiquetas.php" method="post">
    </form>
</div>