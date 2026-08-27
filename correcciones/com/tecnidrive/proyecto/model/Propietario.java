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
@Table(name = "propietarios")

//👹Nuevo mio para la relacion con persona
@PrimaryKeyJoinColumn(name = "id_propietario")
public class Propietario extends Persona {
    

     @Column(name = "telefono_propietario", nullable = false)
     private Long telefonoPropietario;

     @Column(name = "numero_licencia",length = 11)
     private String numeroLicencia;
}
