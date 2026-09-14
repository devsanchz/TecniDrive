package com.proyecto.tecnidrive.model;

import jakarta.persistence.Column;
import jakarta.persistence.Entity;
import jakarta.persistence.PrimaryKeyJoinColumn;
import jakarta.persistence.Table;
import lombok.EqualsAndHashCode;
import lombok.Getter;
import lombok.NoArgsConstructor;
import lombok.Setter;
import lombok.experimental.SuperBuilder;


@Entity
@Table(name = "mecanicos")
@PrimaryKeyJoinColumn(name = "id_mecanico")
@Getter
@Setter
@NoArgsConstructor
@SuperBuilder
@EqualsAndHashCode(callSuper = true)
public class Mecanico extends Persona {

    @Column(name = "telefono_mecanico", nullable = false)
    private Long telefonoMecanico;
}