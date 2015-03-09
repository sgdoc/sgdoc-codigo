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
 * @package br.gov.sgdoc.gui
 * @name MainApplet
 * @author Fábio Lima <fabioolima@gmail.com>
 */

package br.gov.sgdoc.gui;

import br.gov.sgdoc.SGDocApplet;
import br.gov.sgdoc.FileChooser;
import br.gov.sgdoc.SGDocGui;
import br.gov.sgdoc.NewHandlePDF;

import javax.swing.JApplet;
import javax.swing.JPanel;

import java.awt.BorderLayout;
import java.awt.Dimension;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.io.File;
import java.text.DecimalFormat;
import java.util.ArrayList;
import java.util.List;
import java.util.concurrent.ExecutorService;
import java.util.concurrent.Executors;
import java.util.concurrent.Future;

import javax.swing.JButton;
import javax.swing.JScrollPane;
import javax.swing.JTable;
import javax.swing.Timer;
import javax.swing.JProgressBar;

public class MainApplet extends JApplet implements ActionListener
{
	private static final long serialVersionUID = -7042514315547901045L;
	
	private JTable table;
	
	private SGDocGui gui = new SGDocGui();
	
	private SGDocAppleDataModel model;
	
	public final static String BTN_EVENT_UPLOAD = "openUploader";
	
	public final static String BTN_EVENT_SEND = "sendData";
	
	private Timer timer;
	
	public JProgressBar progressBar;
	
	private ExecutorService exec;
	
	private List<Future<?>> listThread = new ArrayList<Future<?>>();

	/**
	 * Create the applet.
	 */
	public MainApplet() 
	{
		timer = new Timer(100, new TimerListener());
		
		JPanel mainPanel = new JPanel();
		getContentPane().add(mainPanel, BorderLayout.CENTER);
		mainPanel.setLayout(null);
		
		JButton btnSend = new JButton("Enviar");
		btnSend.addActionListener(this);
		btnSend.setActionCommand(MainApplet.BTN_EVENT_SEND);
		btnSend.setBounds(234, 263, 117, 25);
		mainPanel.add(btnSend);
		
		JButton btnUpload = new JButton("Selecionar");
		btnUpload.addActionListener(this);
		btnUpload.setActionCommand(MainApplet.BTN_EVENT_UPLOAD);
		btnUpload.setBounds(105, 263, 117, 25);
		mainPanel.add(btnUpload);
		model = new SGDocAppleDataModel();
		
		JPanel panelTable = new JPanel();
		panelTable.setBounds(11, 12, 430, 210);
		mainPanel.add(panelTable);
		
		table = new JTable();
		model = new SGDocAppleDataModel();;
		table.setModel(model);
		table.setPreferredScrollableViewportSize(new Dimension(426, 180));
		table.setFillsViewportHeight(true);
		panelTable.add(new JScrollPane(table));
		
		table.getColumnModel().getColumn(0).setMinWidth(200);
		table.getColumnModel().getColumn(3).setMinWidth(0);
		table.getColumnModel().getColumn(3).setMaxWidth(0);
		
		progressBar = new JProgressBar();
		progressBar.setBounds(21, 234, 406, 17);
		mainPanel.add(progressBar);
		
	}
	
	public void actionPerformed (ActionEvent evt)
    {
		switch (evt.getActionCommand()) 
		{
			case MainApplet.BTN_EVENT_UPLOAD:
				File[] fileUp = FileChooser.getFile();
				
	        	if (fileUp.length > 0) {
	        		for (int fil = 0; fil < fileUp.length; fil++) {
	        			String filePath = fileUp[fil].getAbsolutePath();
	        			File file = new File(filePath);
	        			if (model.checkAlreadyExist(file.getName())) {
	        				gui.showMessage("Arquivo já inserido !!");
	        			} else {
	        				Object[] values = { file.getName(), 
	        						MainApplet.readableFileSize(file.length()), 
	        						new Boolean(true), 
	        						filePath
	        				};        		
	        				model.addElement(values);
	        			}	        			
	        		}
	        	}
				break;
			case MainApplet.BTN_EVENT_SEND:
				this.setEnabled(false);
				progressBar.setIndeterminate(true);
				String ext = "pdf";
	        	for (int idx = 0; idx < model.getRowCount(); idx++) {
	        		String fileAbs = model.getValueAt(idx, 3).toString();
	        		int idxS = fileAbs.lastIndexOf('.');
		            if (idxS >= 0) {
		                ext = fileAbs.substring(idxS + 1);
		            }
		            
		            if ("pdf".equals(ext)) {
		                try {		                    
		                    NewHandlePDF worker = new NewHandlePDF(fileAbs, model, idx);
		                    exec = Executors.newCachedThreadPool();
		                    Future<?> future = exec.submit(worker);
		                    listThread.add(future);
		                    timer.start();
		                } catch (Exception excp) {
		                    gui.showMessage(excp.getMessage());
		                    System.err.println(excp.getMessage());
		                }
		            } else if ("tif".equals(ext)) {
		                System.out.println("TIFF ainda não é aceito.");
		            }
	        	}
				break;

			default:
				break;
		}
    }
	
	public static String readableFileSize(long size) 
	{
	    if(size <= 0) return "0";
	    final String[] units = new String[] { "B", "KB", "MB", "GB", "TB" };
	    int digitGroups = (int) (Math.log10(size)/Math.log10(1024));
	    return new DecimalFormat("#,##0.#").format(size/Math.pow(1024, digitGroups)) + " " + units[digitGroups];
	}
	
	class TimerListener implements ActionListener 
	{
		public void actionPerformed(ActionEvent evt) {
        	if (listThread.size() > 0) {
        		if (this.allDone()) {
        			listThread.clear();
        			try {
        				SGDocApplet.startUploader(model);
    					progressBar.setIndeterminate(false);
        			} catch (Exception excp) {
        				excp.printStackTrace();
        				gui.showMessage(excp.getMessage());
        			}
        		}        		
        	}
        }
        
        public boolean allDone ()
        {
        	for (Future<?> fut : listThread ) {
        		if (!fut.isDone()) {
        			return false;
        		}
        	}
        	return true;
        }
    }
}
