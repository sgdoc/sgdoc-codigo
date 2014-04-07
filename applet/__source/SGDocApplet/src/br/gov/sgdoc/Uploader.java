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
 * @name Uploader
 * @author Fábio Lima <fabioolima@gmail.com>
 */

package br.gov.sgdoc;

import java.net.HttpURLConnection;
import java.net.URL;
import java.io.File;
import java.io.DataOutputStream;
import java.io.FileInputStream;
import java.io.InputStream;

import br.gov.sgdoc.gui.SGDocAppleDataModel;

public class Uploader {

	String Boundary = Long.toHexString(System.currentTimeMillis());

    private String status;
    
    private String urlUpload;
    
    private SGDocAppleDataModel model;

    public String getStatus() 
    {
    	System.out.println(status);
        return status;
    }
    
    public Uploader (String urlUpload, SGDocAppleDataModel model) 
    {
    	this.urlUpload = urlUpload;
    	this.model     = model;
    }
    
    public void run () 
    {
        try {
            URL url = new URL(this.urlUpload);
            HttpURLConnection theUrlConnection = (HttpURLConnection) url.openConnection();
            theUrlConnection.setDoOutput(true);
            theUrlConnection.setDoInput(true);
            theUrlConnection.setUseCaches(false);
            theUrlConnection.setChunkedStreamingMode(1024);
            theUrlConnection.setRequestProperty("Content-Type", "multipart/form-data; boundary=" + Boundary);
            
            DataOutputStream httpOut = new DataOutputStream(theUrlConnection.getOutputStream());
            
            for (int idx = 0; idx < this.model.getRowCount(); idx++) {
            	String isPublic = this.model.getValueAt(idx, 2).toString();
    			String filePath = this.model.getValueAt(idx, 3).toString();
	            File file = new File(filePath);
	            
	            String str = "--" + Boundary + "\r\n"
	                       + "Content-Disposition: form-data;name=\"SGDOC_UPLOAD[]\"; filename=\"" + file.getName() + "\"\r\n"
	                       + "Content-Type: application/pdf;\r\n"
	                       + "\r\n";
	
	            httpOut.write(str.getBytes());
	
	            @SuppressWarnings("resource")
				FileInputStream uploadFileReader = new FileInputStream(file);
	
	            int numBytesToRead = 1024;
	            int availableBytesToRead;
	            while ((availableBytesToRead = uploadFileReader.available()) > 0)
	            {
	                byte[] bufferBytesRead;
	                bufferBytesRead = availableBytesToRead >= numBytesToRead ? new byte[numBytesToRead]
	                        : new byte[availableBytesToRead];
	                uploadFileReader.read(bufferBytesRead);
	                httpOut.write(bufferBytesRead);
	                httpOut.flush();
	            }
	            httpOut.write(("--" + Boundary + "--\r\n").getBytes());
	            httpOut.flush();
	            httpOut.writeBytes("Content-Disposition: form-data;file[]=\"" + file.getName() + "\" public=\"" + isPublic + "\";");
	            httpOut.writeBytes("Content-Type: text/html;\r\n");
	            httpOut.write(("--" + Boundary + "--\r\n").getBytes());
	            
        	}
            httpOut.flush();
            httpOut.close();

            // read & parse the response
            InputStream inptStream = theUrlConnection.getInputStream();
            StringBuilder response = new StringBuilder();
            byte[] respBuffer = new byte[4096];
            while (inptStream.read(respBuffer) >= 0)
            {
                response.append(new String(respBuffer).trim());
            }
            inptStream.close();
            status = response.toString();
            model.deleteData();
        } catch (Exception excp) {
        	System.out.println(excp.getMessage());
            excp.printStackTrace();
        }
    }
}