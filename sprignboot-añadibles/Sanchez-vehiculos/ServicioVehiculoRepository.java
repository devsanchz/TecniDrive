package com.proyecto.tecnidrive.repository;

import com.proyecto.tecnidrive.model.ServicioVehiculo;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.stereotype.Repository;

@Repository
public interface ServicioVehiculoRepository extends JpaRepository<ServicioVehiculo, Integer> {
}
