package com.tecnidrive.proyecto.repository;

//👹Mi parte
import com.tecnidrive.proyecto.model.Persona;

import org.springframework.data.jpa.repository.JpaRepository;

import java.util.Optional;

public interface PersonaRepository extends JpaRepository<Persona, Integer> {
    Optional<Persona> findByEmail(String email);
}
