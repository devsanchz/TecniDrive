package com.proyecto.tecnidrive.service;

import com.lowagie.text.Document;
import com.lowagie.text.Element;
import com.lowagie.text.Font;
import com.lowagie.text.FontFactory;
import com.lowagie.text.PageSize;
import com.lowagie.text.Paragraph;
import com.lowagie.text.pdf.PdfPCell;
import com.lowagie.text.pdf.PdfPTable;
import com.lowagie.text.pdf.PdfWriter;
import org.springframework.stereotype.Service;

import java.awt.Color;
import java.io.ByteArrayOutputStream;
import java.time.format.DateTimeFormatter;
import java.util.List;

@Service
public class UsuarioReportePdfService {

    private static final DateTimeFormatter FORMATO_FECHA = DateTimeFormatter.ofPattern("dd/MM/yyyy");
// pq byte? pq el resultado de el pdf se toma en bytes crudos, entonces se manda de una vez para descargar, ya q no seria una vista previa
    public byte[] generarReporteUsuarios(List<UsuarioReporteDTO> usuarios) throws Exception {
        ByteArrayOutputStream salida = new ByteArrayOutputStream();
        Document documento = new Document(PageSize.A4, 30, 30, 30, 30);
        PdfWriter.getInstance(documento, salida);
        documento.open();
// sin esta parte las fuentes serian todo del mismo tamaño (es algo solo para visibilidad)
        Font fuenteTitulo = FontFactory.getFont(FontFactory.HELVETICA_BOLD, 16);
        Font fuenteEncabezado = FontFactory.getFont(FontFactory.HELVETICA_BOLD, 9, Color.WHITE);
        Font fuenteNormal = FontFactory.getFont(FontFactory.HELVETICA, 9);
        Font fuenteFecha = FontFactory.getFont(FontFactory.HELVETICA, 9, Color.DARK_GRAY);
// en esta parte se usan muchas librerias de githubPDF ´ como paragrahp y antes tmb pdf table q esta mas adelante
        Paragraph titulo = new Paragraph("TecniDrive - Reporte General de Usuarios", fuenteTitulo);
        titulo.setAlignment(Element.ALIGN_CENTER);
        documento.add(titulo);

        Paragraph fechaGeneracion = new Paragraph(
                "Generado el: " + java.time.LocalDate.now().format(FORMATO_FECHA), fuenteFecha);
        fechaGeneracion.setAlignment(Element.ALIGN_CENTER);
        fechaGeneracion.setSpacingAfter(15);
        documento.add(fechaGeneracion);
 // aqui use float para derivar bien la parte del tamaño
        PdfPTable tabla = new PdfPTable(new float[]{8, 22, 25, 15, 15, 15});
        tabla.setWidthPercentage(100);
// configuracion del encabezado de tabla,

/**
 * olvide mencionar algo y esque estos escripts no son propios de java
 * son de librerias que estan incluidas, lo que facilita el trabajo
 * empezando en que todo se maneja en bytes y no bits, entoonces 
 * realiza un proceso mas rapido y en el general esta apenas para que quepa todo
 * sin embargo hay iferentes dependencias en pom.xml como jasperPDF (o algo asi segun chatgpt) 
 * que funcionan para consultas mas especificas, pero este es perfecto con el que necesitamos 
 * pq no genera conflicto
*/
        String[] encabezados = {"ID", "Nombre completo", "Correo", "Teléfono", "Rol", "Fecha registro"};
        for (String encabezado : encabezados) {
            PdfPCell celda = new PdfPCell(new Paragraph(encabezado, fuenteEncabezado));
            celda.setBackgroundColor(Color.DARK_GRAY);
            celda.setHorizontalAlignment(Element.ALIGN_CENTER);
            celda.setPadding(5);
            tabla.addCell(celda);
        }

        for (UsuarioReporteDTO usuario : usuarios) {
            agregarCelda(tabla, String.valueOf(usuario.getId()), fuenteNormal);
            agregarCelda(tabla, usuario.getNombreCompleto(), fuenteNormal);
            agregarCelda(tabla, usuario.getCorreo(), fuenteNormal);
            agregarCelda(tabla, usuario.getTelefono(), fuenteNormal);
            agregarCelda(tabla, usuario.getRol(), fuenteNormal);
            agregarCelda(tabla, usuario.getFechaRegistro().format(FORMATO_FECHA), fuenteNormal);
        }

        documento.add(tabla);

        Paragraph totalUsuarios = new Paragraph("Total de usuarios: " + usuarios.size(), fuenteNormal);
        totalUsuarios.setSpacingBefore(15);
        documento.add(totalUsuarios);

        documento.close();
        return salida.toByteArray();
    }

    private void agregarCelda(PdfPTable tabla, String contenido, Font fuente) {
        PdfPCell celda = new PdfPCell(new Paragraph(contenido == null ? "" : contenido, fuente));
        celda.setBorder(PdfPCell.NO_BORDER);
        celda.setPadding(4);
        celda.setHorizontalAlignment(Element.ALIGN_LEFT);
        tabla.addCell(celda);
    }
}
