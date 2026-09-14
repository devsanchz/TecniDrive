package com.proyecto.tecnidrive.model;

import jakarta.persistence.Column;
import jakarta.persistence.Entity;
import jakarta.persistence.GeneratedValue;
import jakarta.persistence.GenerationType;
import jakarta.persistence.Id;
import jakarta.persistence.Inheritance;
import jakarta.persistence.InheritanceType;
import jakarta.persistence.Table;
import lombok.Getter;
import lombok.NoArgsConstructor;
import lombok.Setter;
import lombok.experimental.SuperBuilder;
import org.hibernate.annotations.CreationTimestamp;

import java.time.LocalDateTime;
@Entity
@Table(name = "personas")
@Inheritance(strategy = InheritanceType.JOINED)
@Getter
@Setter
@NoArgsConstructor
@SuperBuilder
public class Persona {

    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    @Column(name = "id_persona")
    private Integer id;

    @Column(name = "primer_nombre", length = 30, nullable = false)
    private String primerNombre;

    @Column(name = "segundo_nombre", length = 30)
    private String segundoNombre;

    @Column(name = "primer_apellido", length = 25, nullable = false)
    private String primerApellido;

    @Column(name = "segundo_apellido", length = 25, nullable = false)
    private String segundoApellido;

    @Column(name = "email", length = 60, nullable = false, unique = true)
    private String email;

    @Column(name = "password_hash", length = 255, nullable = false)
    private String passwordHash;

    @Column(name = "codigo_recuperacion", length = 10)
    private String codigoRecuperacion;

    @Column(name = "fecha_expiracion")
    private LocalDateTime fechaExpiracion;

    @CreationTimestamp
    @Column(name = "fecha_registro", nullable = false, updatable = false)
    private LocalDateTime fechaRegistro;

    @Column(name = "avatarcolor", length = 15)
    private String avatarColor;
}