package com.proyecto.tecnidrive.controller;

import com.proyecto.tecnidrive.repository.PersonaRepository;
import com.proyecto.tecnidrive.service.UsuarioReporteDTO;
import com.proyecto.tecnidrive.service.UsuarioReportePdfService;
import org.springframework.http.HttpHeaders;
import org.springframework.http.MediaType;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RestController;

import java.util.List;

@RestController
@RequestMapping("/api/reportes")
public class UsuarioReporteController {

    private final UsuarioReportePdfService reportePdfService;
    private final PersonaRepository personaRepository;

    public UsuarioReporteController(UsuarioReportePdfService reportePdfService, PersonaRepository personaRepository) {
        this.reportePdfService = reportePdfService;
        this.personaRepository = personaRepository;
    }
// casi todo lo que ssale en esa parte esta en el archivo UsuarioReportePdService
    @GetMapping("/usuarios")
    public ResponseEntity<byte[]> descargarReporteUsuarios() throws Exception {
        List<UsuarioReporteDTO> usuarios = personaRepository.findAll().stream()
// mediante .map() lo que hace es estrucutrar y recibir los datos que solicito mediante service
                .map(p -> new UsuarioReporteDTO(
                        p.getId(),
                        p.getNombres() + " " + p.getApellidos(),
                        p.getCorreo(),
                        p.getTelefono(),
                        p.getRoles().stream().findFirst().map(r -> r.getNombre()).orElse(""),
                        p.getFechaRegistro()
                ))
                .toList();

        byte[] pdf = reportePdfService.generarReporteUsuarios(usuarios);

        HttpHeaders headers = new HttpHeaders();
        headers.setContentType(MediaType.APPLICATION_PDF);
// esto solo genera la forma y el nombre del archivo del reporte en formato pdf
        headers.setContentDispositionFormData("attachment", "reporte_usuarios.pdf");
// excepto ok() eso esrablece el orden adecuado de algunas cosas como las tablas
        return ResponseEntity.ok().headers(headers).body(pdf);
    }
}
