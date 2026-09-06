package com.proyecto.tecnidrive.repository;

import com.proyecto.tecnidrive.model.TipoVehiculo;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.stereotype.Repository;

@Repository
public interface TipoVehiculoRepository extends JpaRepository<TipoVehiculo, Integer> {
}
