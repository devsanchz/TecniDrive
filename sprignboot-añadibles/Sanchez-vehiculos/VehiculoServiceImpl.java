package com.proyecto.tecnidrive.service.impl;

import com.proyecto.tecnidrive.dto.VehiculoRequestDTO;
import com.proyecto.tecnidrive.dto.VehiculoResponseDTO;
import com.proyecto.tecnidrive.exception.RecursoNoEncontradoException;
import com.proyecto.tecnidrive.model.*;
import com.proyecto.tecnidrive.repository.*;
import com.proyecto.tecnidrive.service.VehiculoService;
import lombok.RequiredArgsConstructor;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;

import java.util.List;
import java.util.stream.Collectors;

@Service
@RequiredArgsConstructor
public class VehiculoServiceImpl implements VehiculoService {

    private final VehiculoRepository vehiculoRepository;
    private final PropietarioRepository propietarioRepository;
    private final TipoVehiculoRepository tipoVehiculoRepository;
    private final ModeloRepository modeloRepository;
    private final ServicioVehiculoRepository servicioVehiculoRepository;

    @Override
    @Transactional
    public VehiculoResponseDTO crear(VehiculoRequestDTO dto) {
        if (vehiculoRepository.existsById(dto.getPlaca())) {
            throw new IllegalArgumentException("Ya existe un vehículo registrado con esa placa");
        }

        Propietario propietario = propietarioRepository.findById(dto.getIdPropietario())
                .orElseThrow(() -> new RecursoNoEncontradoException(
                        "No existe un propietario con id " + dto.getIdPropietario()));

        TipoVehiculo tipoVehiculo = tipoVehiculoRepository.findById(dto.getIdTipoVehiculo())
                .orElseThrow(() -> new RecursoNoEncontradoException(
                        "No existe un tipo de vehículo con id " + dto.getIdTipoVehiculo()));

        Modelo modelo = modeloRepository.findById(dto.getIdModelo())
                .orElseThrow(() -> new RecursoNoEncontradoException(
                        "No existe un modelo con id " + dto.getIdModelo()));

        ServicioVehiculo servicioVehiculo = servicioVehiculoRepository.findById(dto.getIdTipoServicio())
                .orElseThrow(() -> new RecursoNoEncontradoException(
                        "No existe un tipo de servicio con id " + dto.getIdTipoServicio()));

        Vehiculo vehiculo = Vehiculo.builder()
                .placa(dto.getPlaca())
                .modelYear(dto.getModelYear())
                .estadoVehi(dto.getEstadoVehi())
                .motivoEstado(dto.getMotivoEstado())
                .propietario(propietario)
                .tipoVehiculo(tipoVehiculo)
                .modelo(modelo)
                .servicioVehiculo(servicioVehiculo)
                .build();

        return mapearAResponseDTO(vehiculoRepository.save(vehiculo));
    }

    @Override
    public VehiculoResponseDTO obtenerPorPlaca(String placa) {
        return mapearAResponseDTO(buscarVehiculoOLanzarExcepcion(placa));
    }

    @Override
    public List<VehiculoResponseDTO> listarTodos() {
        return vehiculoRepository.findAll().stream()
                .map(this::mapearAResponseDTO)
                .collect(Collectors.toList());
    }

    @Override
    public List<VehiculoResponseDTO> listarPorPropietario(Long idPropietario) {
        return vehiculoRepository.findByPropietarioIdPersona(idPropietario).stream()
                .map(this::mapearAResponseDTO)
                .collect(Collectors.toList());
    }

    @Override
    @Transactional
    public VehiculoResponseDTO actualizar(String placa, VehiculoRequestDTO dto) {
        Vehiculo vehiculo = buscarVehiculoOLanzarExcepcion(placa);

        if (!vehiculo.getPropietario().getIdPersona().equals(dto.getIdPropietario())) {
            Propietario propietario = propietarioRepository.findById(dto.getIdPropietario())
                    .orElseThrow(() -> new RecursoNoEncontradoException(
                            "No existe un propietario con id " + dto.getIdPropietario()));
            vehiculo.setPropietario(propietario);
        }

        if (!vehiculo.getTipoVehiculo().getIdTipoVehi().equals(dto.getIdTipoVehiculo())) {
            TipoVehiculo tipoVehiculo = tipoVehiculoRepository.findById(dto.getIdTipoVehiculo())
                    .orElseThrow(() -> new RecursoNoEncontradoException(
                            "No existe un tipo de vehículo con id " + dto.getIdTipoVehiculo()));
            vehiculo.setTipoVehiculo(tipoVehiculo);
        }

        if (!vehiculo.getModelo().getIdModelo().equals(dto.getIdModelo())) {
            Modelo modelo = modeloRepository.findById(dto.getIdModelo())
                    .orElseThrow(() -> new RecursoNoEncontradoException(
                            "No existe un modelo con id " + dto.getIdModelo()));
            vehiculo.setModelo(modelo);
        }

        if (!vehiculo.getServicioVehiculo().getIdTipoServicio().equals(dto.getIdTipoServicio())) {
            ServicioVehiculo servicioVehiculo = servicioVehiculoRepository.findById(dto.getIdTipoServicio())
                    .orElseThrow(() -> new RecursoNoEncontradoException(
                            "No existe un tipo de servicio con id " + dto.getIdTipoServicio()));
            vehiculo.setServicioVehiculo(servicioVehiculo);
        }

        vehiculo.setModelYear(dto.getModelYear());
        vehiculo.setEstadoVehi(dto.getEstadoVehi());
        vehiculo.setMotivoEstado(dto.getMotivoEstado());

        return mapearAResponseDTO(vehiculoRepository.save(vehiculo));
    }

    @Override
    @Transactional
    public void eliminar(String placa) {
        Vehiculo vehiculo = buscarVehiculoOLanzarExcepcion(placa);
        vehiculoRepository.delete(vehiculo);
    }

    private Vehiculo buscarVehiculoOLanzarExcepcion(String placa) {
        return vehiculoRepository.findById(placa)
                .orElseThrow(() -> new RecursoNoEncontradoException(
                        "No existe un vehículo con placa " + placa));
    }

    private VehiculoResponseDTO mapearAResponseDTO(Vehiculo vehiculo) {
        return VehiculoResponseDTO.builder()
                .placa(vehiculo.getPlaca())
                .modelYear(vehiculo.getModelYear())
                .fechaRegistro(vehiculo.getFechaRegistro())
                .estadoVehi(vehiculo.getEstadoVehi())
                .motivoEstado(vehiculo.getMotivoEstado())
                .idPropietario(vehiculo.getPropietario().getIdPersona())
                .nombrePropietario(vehiculo.getPropietario().getPrimerNombre() + " " + vehiculo.getPropietario().getPrimerApellido())
                .idTipoVehiculo(vehiculo.getTipoVehiculo().getIdTipoVehi())
                .textoTipoVehi(vehiculo.getTipoVehiculo().getTextoTipoVehi())
                .idModelo(vehiculo.getModelo().getIdModelo())
                .nombreModelo(vehiculo.getModelo().getNombreModelo())
                .nombreMarca(vehiculo.getModelo().getMarca().getNombreMarca())
                .idTipoServicio(vehiculo.getServicioVehiculo().getIdTipoServicio())
                .textoServicio(vehiculo.getServicioVehiculo().getTextoServicio())
                .build();
    }
}
