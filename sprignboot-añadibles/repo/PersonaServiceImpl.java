package com.proyecto.tecnidrive.repo;

import java.util.Collection;
import java.util.List;

import org.springframework.stereotype.Service;
import com.proyecto.tecnidrive.dto.PersonaRequestDTO;
import com.proyecto.tecnidrive.dto.PersonaResponseDTO;

import com.proyecto.tecnidrive.entity.persona;

import jakarta.transaction.Transactional;
import lombok.*;

@Service
    @RequiredArgsConstructor
    @Slf4j
    @Transactional(value = true)
    public class PersonaServiceImpl implements personaService {
 
        private final PersonaRepository personaRepository;
 
        @Override
        @Transactional
        public PersonaResponseDTO crear(PersonaRequestDTO dto) {
            if (personaRepository.existsByCorreo(dto.correo())) {
                throw new IllegalArgumentException("Ya existe una persona con ese correo");
            }
 
            persona persona = Persona.builder()
                    .nombre(dto.nombre())
                    .correo(dto.correo())
                    .telefono(dto.telefono())
                    .build();
 
            log.info("Creando persona con correo: {}", dto.correo());
            return PersonaResponseDTO.desde(personaRepository.save(persona));
        }
 
        @Override
        public PersonaResponseDTO obtenerPorId(Long id) {
            return PersonaResponseDTO.desde(fetchById(id));
        }
 
        @Override
        public List<PersonaResponseDTO> listarTodos() {
            return personaRepository.findAll().stream()
                    .map(PersonaResponseDTO::desde)
                    .collect(Collection.toList());
        }
 
        @Override
        @Transactional
        public PersonaResponseDTO actualizar(Long id, PersonaRequestDTO dto) {
            Persona persona = buscarOFallar(id);
            persona.setNombre(dto.nombre());
            persona.setCorreo(dto.correo());
            persona.setTelefono(dto.telefono());
            return PersonaResponseDTO.desde(personaRepository.save(persona));
        }
 
        @Override
        @Transactional
        public void eliminar(Long id) {
            buscarOFallar(id);
            personaRepository.deleteById(id);
        }
 
        private Persona buscarOFallar(Long id) {
            return personaRepository.findById(id)
                    .orElseThrow(() -> new IllegalArgumentException("Persona no encontrada: " + id));
        }
    }
 