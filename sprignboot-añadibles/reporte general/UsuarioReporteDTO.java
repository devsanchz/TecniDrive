package com.proyecto.tecnidrive.service;

import java.time.LocalDate;

public class UsuarioReporteDTO {

    private Long id;
    private String nombreCompleto;
    private String correo;
    private String telefono;
    private String rol;
    private LocalDate fechaRegistro;

    public UsuarioReporteDTO(Long id, String nombreCompleto, String correo, String telefono, String rol, LocalDate fechaRegistro) {
        this.id = id;
        this.nombreCompleto = nombreCompleto;
        this.correo = correo;
        this.telefono = telefono;
        this.rol = rol;
        this.fechaRegistro = fechaRegistro;
    }
// aqui solo tome los datos para consulta
    public Long getId() { return id; }
    public String getNombreCompleto() { return nombreCompleto; }
    public String getCorreo() { return correo; }
    public String getTelefono() { return telefono; }
    public String getRol() { return rol; }
    public LocalDate getFechaRegistro() { return fechaRegistro; }
}
