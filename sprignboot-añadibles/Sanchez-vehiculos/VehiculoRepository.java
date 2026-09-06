package com.proyecto.tecnidrive.repository;

import com.proyecto.tecnidrive.model.Vehiculo;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.stereotype.Repository;

import java.util.List;

@Repository
public interface VehiculoRepository extends JpaRepository<Vehiculo, String> {

    List<Vehiculo> findByPropietarioIdPersona(Long idPersona);

    List<Vehiculo> findByEstadoVehi(Boolean estadoVehi);
}
