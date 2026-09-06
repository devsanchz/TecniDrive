package com.proyecto.tecnidrive.model;

import jakarta.persistence.*;
import lombok.*;
import lombok.experimental.SuperBuilder;

@Entity
@Table(name = "servicio_vehiculo")
@Getter
@Setter
@NoArgsConstructor
@AllArgsConstructor
@SuperBuilder
public class ServicioVehiculo {

    @Id
    @Column(name = "id_tipo_servicio")
    private Integer idTipoServicio;

    @Column(name = "texto_servicio", nullable = false, length = 20)
    private String textoServicio;
}
