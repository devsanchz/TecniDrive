package com.tecnidrive.proyecto.repository;

//👹Mi parte
import com.tecnidrive.proyecto.model.Rol;

import org.springframework.data.jpa.repository.JpaRepository;

public interface RolRepository extends JpaRepository<Rol, Integer> {
}