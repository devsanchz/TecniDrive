package com.proyecto.tecnidrive.model;

import jakarta.persistence.*;
import lombok.*;
import lombok.experimental.SuperBuilder;

@Entity
@Table(name = "modelos")
@Getter
@Setter
@NoArgsConstructor
@AllArgsConstructor
@SuperBuilder
public class Modelo {

    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    @Column(name = "id_modelo")
    private Integer idModelo;

    @Column(name = "nombre_modelo", nullable = false, length = 28)
    private String nombreModelo;

    @ManyToOne(fetch = FetchType.LAZY)
    @JoinColumn(name = "marcas_id_marca", nullable = false)
    private Marca marca;
}
