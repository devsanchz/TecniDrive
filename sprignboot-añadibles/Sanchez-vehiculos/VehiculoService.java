package com.proyecto.tecnidrive.service;

import com.proyecto.tecnidrive.dto.VehiculoRequestDTO;
import com.proyecto.tecnidrive.dto.VehiculoResponseDTO;

import java.util.List;

public interface VehiculoService {

    VehiculoResponseDTO crear(VehiculoRequestDTO dto);

    VehiculoResponseDTO obtenerPorPlaca(String placa);

    List<VehiculoResponseDTO> listarTodos();

    List<VehiculoResponseDTO> listarPorPropietario(Long idPropietario);

    VehiculoResponseDTO actualizar(String placa, VehiculoRequestDTO dto);

    void eliminar(String placa);
}
