/*
 * Copyright 2013 ICMBio
 * Este arquivo é parte do programa SISICMBio
 * O SISICMBio é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos
 * da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão
 * 2 da Licença.
 *
 * Este programa é distribuído na esperança que possa ser útil, mas SEM NENHUMA GARANTIA; sem
 * uma garantia implícita de ADEQUAÇÃO a qualquer MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a
 * Licença Pública Geral GNU/GPL em português para maiores detalhes.
 * Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "LICENCA.txt",
 * junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço
 * www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF)
 * Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
 * */

/**
 * Applet de tratamento de documentos
 * @package br.gov.sgdoc
 * @name FileChooser
 * @author Fábio Lima <fabioolima@gmail.com>
 */


package br.gov.sgdoc;

import java.io.File;
import javax.swing.JFileChooser;
import javax.swing.UIManager;
import javax.swing.SwingUtilities;

public class FileChooser {

    private static JFileChooser fileChooser;

    public static File[] getFile() {

        File[] files = null;

        fileChooser = new JFileChooser();

        fileChooser.setFileFilter(new javax.swing.filechooser.FileFilter() {
            //Filtro, converte as letras em minúsculas antes de comparar
            public boolean accept(File fileInf){
                return fileInf.getName().toLowerCase().endsWith(".pdf") || fileInf.isDirectory();
            }
            //Texto que será exibido para o usuário
            public String getDescription() {
                return "Arquivos (.pdf)";
            }
        });

        fileChooser.addChoosableFileFilter(new javax.swing.filechooser.FileFilter() {
            //Filtro, converte as letras em minúsculas antes de comparar
            public boolean accept(File fileInf){
                return fileInf.getName().toLowerCase().endsWith(".tif") || fileInf.isDirectory();
            }
            //Texto que será exibido para o usuário
            public String getDescription() {
                return "Arquivos (.tif)";
            }
        });

        UIManager.put("FileChooser.openDialogTitleText", "Selecione um arquivo");
        UIManager.put("FileChooser.lookInLabelText", "Consultar em");
        UIManager.put("FileChooser.openButtonText", "Abrir");
        UIManager.put("FileChooser.cancelButtonText", "Cancelar");
        UIManager.put("FileChooser.fileNameLabelText", "Nome do Arquivo");
        UIManager.put("FileChooser.filesOfTypeLabelText", "Tipo de Arquivo");
        UIManager.put("FileChooser.openButtonToolTipText", "Abrir o Arquivo Selecionado");
        UIManager.put("FileChooser.cancelButtonToolTipText","Cancelar");
        UIManager.put("FileChooser.fileNameHeaderText","Nome");
        UIManager.put("FileChooser.upFolderToolTipText", "Subir um Nível");
        UIManager.put("FileChooser.homeFolderToolTipText","Área de Trabalho");
        UIManager.put("FileChooser.newFolderToolTipText","Criar Nova Pasta");
        UIManager.put("FileChooser.listViewButtonToolTipText","Lista");
        UIManager.put("FileChooser.newFolderButtonText","Criar Nova Pasta");
        UIManager.put("FileChooser.renameFileButtonText", "Renomear");
        UIManager.put("FileChooser.deleteFileButtonText", "Deletar");
        UIManager.put("FileChooser.filterLabelText", "Tipo");
        UIManager.put("FileChooser.detailsViewButtonToolTipText", "Detalhes");
        UIManager.put("FileChooser.fileSizeHeaderText","Tamanho");
        UIManager.put("FileChooser.fileDateHeaderText", "Data de Modificação");
        UIManager.put("FileChooser.acceptAllFileFilterText", "Todos os Arquivos");

        SwingUtilities.updateComponentTreeUI(fileChooser);
        fileChooser.setMultiSelectionEnabled(true);
        fileChooser.setFileHidingEnabled(true);

        int retorno = fileChooser.showOpenDialog(null);

        if(JFileChooser.APPROVE_OPTION == retorno)
        {
        	files = fileChooser.getSelectedFiles();
//            filePath = fileChooser.getSelectedFile().getAbsolutePath();
        }
        return files;
    }
}