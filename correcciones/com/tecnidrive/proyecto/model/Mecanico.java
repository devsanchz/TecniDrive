package com.tecnidrive.proyecto.model;

//correccion: simplifique la importancion
import jakarta.persistence.*;
//Lo de david
import lombok.Getter;
import lombok.NoArgsConstructor;
import lombok.Setter;

@Getter
@Setter
@NoArgsConstructor
@Entity
@Table(name = "mecanicos")

//👹Nuevo mio para la relacion con persona
@PrimaryKeyJoinColumn(name = "id_mecanico")
public class Mecanico extends Persona {

    @Column(name = "telefono_mecanico", nullable = false)
    private Long telefonoMecanico;
}
