package com.tecnidrive.proyecto.repository;

//👹Mi parte
import com.tecnidrive.proyecto.model.Propietario;

import org.springframework.data.jpa.repository.JpaRepository;

public interface PropietarioRepository extends JpaRepository<Propietario, Integer> {
}