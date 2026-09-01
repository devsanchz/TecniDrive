package tecnidrive.tecnidrive.service;

import java.util.HashSet;
import java.util.Set;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;

import tecnidrive.tecnidrive.model.Mecanico;
import tecnidrive.tecnidrive.model.Propietario;
import tecnidrive.tecnidrive.model.Rol;
import tecnidrive.tecnidrive.repository.MecanicoRepository;
import tecnidrive.tecnidrive.repository.PropietarioRepository;
import tecnidrive.tecnidrive.repository.RolRepository;

@Service
public class RegistroService {

    @Autowired
    private PropietarioRepository propietarioRepository;

    @Autowired
    private MecanicoRepository mecanicoRepository;

    @Autowired
    private RolRepository rolRepository;

    public void registrarPropietario(
            String primerNombre,
            String segundoNombre,
            String primerApellido,
            String segundoApellido,
            Long telefono,
            String email,
            String password) {

        Rol rol = rolRepository.findById(2)
                .orElseThrow(() -> new RuntimeException("Rol Propietario no encontrado"));

        Propietario propietario = new Propietario();

        propietario.setPrimerNombre(primerNombre);
        propietario.setSegundoNombre(segundoNombre);
        propietario.setPrimerApellido(primerApellido);
        propietario.setSegundoApellido(segundoApellido);
        propietario.setEmail(email);

        // Sin encriptación por ahora.
        propietario.setPasswordHash(password);

        propietario.setTelefonoPropietario(telefono);

        Set<Rol> roles = new HashSet<>();
        roles.add(rol);
        propietario.setRoles(roles);

        propietarioRepository.save(propietario);
    }

    public void registrarMecanico(
            String primerNombre,
            String segundoNombre,
            String primerApellido,
            String segundoApellido,
            Long telefono,
            String email,
            String password) {

        Rol rol = rolRepository.findById(3)
                .orElseThrow(() -> new RuntimeException("Rol Mecánico no encontrado"));

        Mecanico mecanico = new Mecanico();

        mecanico.setPrimerNombre(primerNombre);
        mecanico.setSegundoNombre(segundoNombre);
        mecanico.setPrimerApellido(primerApellido);
        mecanico.setSegundoApellido(segundoApellido);
        mecanico.setEmail(email);

        // Sin encriptación por ahora.
        mecanico.setPasswordHash(password);

        mecanico.setTelefonoMecanico(telefono);

        Set<Rol> roles = new HashSet<>();
        roles.add(rol);
        mecanico.setRoles(roles);

        mecanicoRepository.save(mecanico);
    }
}