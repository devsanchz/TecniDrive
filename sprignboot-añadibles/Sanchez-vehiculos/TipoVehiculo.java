package com.proyecto.tecnidrive.model;

import jakarta.persistence.*;
import lombok.*;
import lombok.experimental.SuperBuilder;

@Entity
@Table(name = "tipos_vehiculo")
@Getter
@Setter
@NoArgsConstructor
@AllArgsConstructor
@SuperBuilder
public class TipoVehiculo {

    @Id
    @Column(name = "id_tipo_vehi")
    private Integer idTipoVehi;

    @Column(name = "texto_tipo_vehi", nullable = false, length = 20)
    private String textoTipoVehi;
}
