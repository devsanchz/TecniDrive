package com.proyecto.tecnidrive.dto;

import lombok.*;

import java.time.LocalDateTime;

@Getter
@Setter
@NoArgsConstructor
@AllArgsConstructor
@Builder
public class VehiculoResponseDTO {

    private String placa;
    private String modelYear;
    private LocalDateTime fechaRegistro;
    private Boolean estadoVehi;
    private String motivoEstado;
    private Long idPropietario;
    private String nombrePropietario;
    private Integer idTipoVehiculo;
    private String textoTipoVehi;
    private Integer idModelo;
    private String nombreModelo;
    private String nombreMarca;
    private Integer idTipoServicio;
    private String textoServicio;
}
