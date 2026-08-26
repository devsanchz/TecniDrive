package com.proyecto.tecnidrive.entity;

import jakarta.persistence.*;
import lombok.*;

//filtro de roles

@Entity
    @Table(name = "roles")
    @Getter
    @Setter
    @NoArgsConstructor
    @AllArgsConstructor
    @Builder
    public class Rol {
        @Id
        @GeneratedValue(strategy = GenerationType.IDENTITY)
        private Long id;
        private String nombre;
    }
