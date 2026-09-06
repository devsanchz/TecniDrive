package com.proyecto.tecnidrive.repository;

import com.proyecto.tecnidrive.model.Modelo;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.stereotype.Repository;

@Repository
public interface ModeloRepository extends JpaRepository<Modelo, Integer> {
}
