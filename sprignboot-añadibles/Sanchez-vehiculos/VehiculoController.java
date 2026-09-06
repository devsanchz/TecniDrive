package com.proyecto.tecnidrive.controller;

import com.proyecto.tecnidrive.dto.VehiculoRequestDTO;
import com.proyecto.tecnidrive.dto.VehiculoResponseDTO;
import com.proyecto.tecnidrive.service.VehiculoService;
import jakarta.validation.Valid;
import lombok.RequiredArgsConstructor;
import org.springframework.http.HttpStatus;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.*;

import java.util.List;

@RestController
@RequestMapping("/api/vehiculos")
@RequiredArgsConstructor
public class VehiculoController {

    private final VehiculoService vehiculoService;

    @PostMapping
    public ResponseEntity<VehiculoResponseDTO> crear(@Valid @RequestBody VehiculoRequestDTO dto) {
        VehiculoResponseDTO creado = vehiculoService.crear(dto);
        return ResponseEntity.status(HttpStatus.CREATED).body(creado);
    }

    @GetMapping("/{placa}")
    public ResponseEntity<VehiculoResponseDTO> obtenerPorPlaca(@PathVariable String placa) {
        return ResponseEntity.ok(vehiculoService.obtenerPorPlaca(placa));
    }

    @GetMapping
    public ResponseEntity<List<VehiculoResponseDTO>> listarTodos() {
        return ResponseEntity.ok(vehiculoService.listarTodos());
    }

    @GetMapping("/propietario/{idPropietario}")
    public ResponseEntity<List<VehiculoResponseDTO>> listarPorPropietario(@PathVariable Long idPropietario) {
        return ResponseEntity.ok(vehiculoService.listarPorPropietario(idPropietario));
    }

    @PutMapping("/{placa}")
    public ResponseEntity<VehiculoResponseDTO> actualizar(
            @PathVariable String placa,
            @Valid @RequestBody VehiculoRequestDTO dto) {
        return ResponseEntity.ok(vehiculoService.actualizar(placa, dto));
    }

    @DeleteMapping("/{placa}")
    public ResponseEntity<Void> eliminar(@PathVariable String placa) {
        vehiculoService.eliminar(placa);
        return ResponseEntity.noContent().build();
    }
}
