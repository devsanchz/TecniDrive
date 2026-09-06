package com.proyecto.tecnidrive.model;

import jakarta.persistence.*;
import lombok.*;
import lombok.experimental.SuperBuilder;

import java.time.LocalDateTime;

@Entity
@Table(name = "vehiculo")
@Getter
@Setter
@NoArgsConstructor
@AllArgsConstructor
@SuperBuilder
public class Vehiculo {

    @Id
    @Column(name = "placa", length = 6)
    private String placa;

    @Column(name = "model_year", nullable = false, length = 4)
    private String modelYear;

    @Column(name = "fecha_registro", insertable = false, updatable = false)
    private LocalDateTime fechaRegistro;

    @Column(name = "estado_vehi", nullable = false)
    private Boolean estadoVehi;

    @Column(name = "motivo_estado", length = 100)
    private String motivoEstado;

    @ManyToOne(fetch = FetchType.LAZY)
    @JoinColumn(name = "propietarios_id_propietario", nullable = false)
    private Propietario propietario;

    @ManyToOne(fetch = FetchType.LAZY)
    @JoinColumn(name = "tipos_vehiculo_id_tipo_vehi", nullable = false)
    private TipoVehiculo tipoVehiculo;

    @ManyToOne(fetch = FetchType.LAZY)
    @JoinColumn(name = "modelos_id_modelo", nullable = false)
    private Modelo modelo;

    @ManyToOne(fetch = FetchType.LAZY)
    @JoinColumn(name = "servicio_vehiculo_id_tipo_servicio", nullable = false)
    private ServicioVehiculo servicioVehiculo;
}
