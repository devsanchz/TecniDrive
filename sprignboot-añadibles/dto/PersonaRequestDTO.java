package com.proyecto.tecnidrive.dto;

import jakarta.validate.Email;
import jakarta.persistence.*;
import lombok.*;
import java.time.LocalDateTime;
import java.util.List;
import java.util.Set;

import com.proyecto.tecnidrive.entity.Rol;
import com.proyecto.tecnidrive.entity.persona;

public record PersonaRequestDTO(
            @NonNull String nombre,
            @Email @NonNull String correo,
            String telefono
    ) {}
 
    public record PersonaResponseDTO(
            Long id,
            String nombre,
            String correo,
            String telefono,
            LocalDateTime fechaRegistro,
            List<String> roles
    ) {
        public static PersonaResponseDTO desde(persona persona) {
            List<String> nombresRoles = persona.getRoles() == null
                    ? List.of()
                    : persona.getRoles().stream().map(Rol::getNombre).toList();
 
            return new PersonaResponseDTO(
                    persona.getId(),
                    persona.getNombre(),
                    persona.getCorreo(),
                    persona.getTelefono(),
                    persona.getFechaRegistro(),
                    nombresRoles
            );
        }
    }
