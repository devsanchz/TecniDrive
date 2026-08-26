package com.proyecto.tecnidrive.entity;

import jakarta.persistence.*;

import lombok.*;
import lombok.experimental.SuperBuilder;

import java.time.LocalDateTime;
import java.util.Set;
import lombok.Getter;

@Entity
@Table(name = "personas")
@Inheritance(strategy = InheritanceType.JOINED)
@Getter 
@Setter 
@NoArgsConstructor 
@SuperBuilder

//aqui solo defino la base para el dto
//dto: objeto de transferencia de datos
public class persona {
    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long id;

    private String nombre;
    private String correo;
    private String telefono;

    @Column(name = "fecha_registro")
    private LocalDateTime fechaRegistro;

    @ManyToMany
    @JoinTable(
        name = "roles_has_persona",
        joinColumns = @JoinColumn(name = "id_persona"),
        inverseJoinColumns = @JoinColumn(name = "id_rol")
    )
    private Set<Rol> roles;
}