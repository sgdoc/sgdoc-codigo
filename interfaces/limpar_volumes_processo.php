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
<head>
    <script type="text/javascript">
        /*Botao limpar volumes*/
        $('#botao-limpar-volumes-processo').live('click', function(){
            // deve enviar, através de $.post, uma requisição para o método que vai limpar os processos, somente após confirmação do usuário
            var c = confirm('Esta operação excluirá todos os volumes do processo. Você tem certeza de que deseja prosseguir?');
            if (c){
                /*Ativar Preloader*/
                $('#progressbar').show();

                $.post("modelos/processos/processos.php", {
                    acao: 'limpar-volumes',
                    processo: $('#NUMERO_DETALHAR_PROCESSO').val()
                },
                function(data){
                    try{
                        if(data.success == 'true'){
                            alert('Volumes removidos com sucesso!'); 
                            $('#progressbar').hide();
                        }else{
                            alert(data.error);
                            /*Desativar Preloader*/
                            $('#progressbar').hide();
                        }
                    }catch(e){
                        alert('Ocorreu um erro ao tentar corrigir os volumes do processo!\n['+e+']');
                        /*Desativar Preloader*/
                        $('#progressbar').hide();     
                    }
                }, "json");
            }
        });
    </script>
</head>