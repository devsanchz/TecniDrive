package tecnidrive.tecnidrive.repository;

import tecnidrive.tecnidrive.model.Persona;
import org.springframework.data.jpa.repository.JpaRepository;

import java.util.Optional;

public interface PersonaRepository extends JpaRepository<Persona, Integer> {

    Optional<Persona> findByEmail(String email);
}