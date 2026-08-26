package com.proyecto.tecnidrive.repo;

import org.springframework.stereotype.Repository;
import org.springframework.stereotype.Service;

import com.proyecto.tecnidrive.dto.PersonaRequestDTO;
import com.proyecto.tecnidrive.dto.PersonaResponseDTO;

import jakarta.transaction.Transactional;
import lombok.*;



public interface personaService {
        PersonaResponseDTO crear(PersonaRequestDTO dto);
        PersonaResponseDTO obtenerPorId(Long id);
        List<PersonaResponseDTO> listarTodos();
        PersonaResponseDTO actualizar(Long id, PersonaRequestDTO dto);
        void eliminar(Long id);
    }
    // ===================== CONTROLLER =====================
 
    @RestController
    @RequestMapping("/api/personas")
    @RequiredArgsConstructor
    public static class PersonaController {
 
        private final personaService personaService;
 
        @PostMapping
        public ResponseEntity<PersonaResponseDTO> crear(@RequestBody PersonaRequestDTO dto) {
            return ResponseEntity.ok(personaService.crear(dto));
        }
 
        @GetMapping("/{id}")
        public ResponseEntity<PersonaResponseDTO> obtener(@PathVariable Long id) {
            return ResponseEntity.ok(personaService.obtenerPorId(id));
        }
 
        @GetMapping
        public ResponseEntity<List<PersonaResponseDTO>> listar() {
            return ResponseEntity.ok(personaService.listarTodos());
        }
 
        @PutMapping("/{id}")
        public ResponseEntity<PersonaResponseDTO> actualizar(@PathVariable Long id, @RequestBody PersonaRequestDTO dto) {
            return ResponseEntity.ok(personaService.actualizar(id, dto));
        }
 
        @DeleteMapping("/{id}")
        public ResponseEntity<Void> eliminar(@PathVariable Long id) {
            personaService.eliminar(id);
            return ResponseEntity.noContent().build();
        }
    }
}
 