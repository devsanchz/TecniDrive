package tecnidrive.tecnidrive.controller;

import tecnidrive.tecnidrive.dto.LoginRequestDTO;
import tecnidrive.tecnidrive.model.Persona;
import tecnidrive.tecnidrive.model.Rol;
import tecnidrive.tecnidrive.service.PersonaService;

import jakarta.servlet.http.HttpSession;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.http.HttpStatus;
import org.springframework.http.ResponseEntity;
import org.springframework.security.crypto.password.PasswordEncoder;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.PostMapping;
import org.springframework.web.bind.annotation.RequestBody;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RestController;

import java.util.HashMap;
import java.util.Map;
import java.util.Optional;

@RestController
@RequestMapping("/api/auth")
public class AuthController {

    @Autowired
    private PersonaService personaService;

    @Autowired
    private PasswordEncoder passwordEncoder;

    @PostMapping("/login")
    public ResponseEntity<Map<String, Object>> login(
            @RequestBody LoginRequestDTO datosLogin,
            HttpSession session) {

        Map<String, Object> respuesta = new HashMap<>();

        // 1. Buscamos la persona por email
        Optional<Persona> personaOpt = personaService.buscarPorEmail(datosLogin.getEmail());

        // 2. Si no existe, mensaje genérico
        if (personaOpt.isEmpty()) {
            respuesta.put("mensaje", "Credenciales incorrectas incorrectos");
            return ResponseEntity.status(HttpStatus.UNAUTHORIZED).body(respuesta);
        }

        Persona persona = personaOpt.get();

        // 3. Comparamos la contraseña ingresada contra el hash guardado
        boolean passwordCorrecto = passwordEncoder.matches(
                datosLogin.getPassword(),
                persona.getPasswordHash()
        );

        if (!passwordCorrecto) {
            respuesta.put("mensaje", "Correo o contraseña incorrectos");
            return ResponseEntity.status(HttpStatus.UNAUTHORIZED).body(respuesta);
        }

        // 4. Tomamos el primer (y único, por ahora) rol de la persona
        Rol rolPersona = persona.getRoles().iterator().next();

        // 5. Guardamos los datos importantes en la sesión
        session.setAttribute("idPersona", persona.getIdPersona());
        session.setAttribute("email", persona.getEmail());
        session.setAttribute("rol", rolPersona.gettexto_rol());

        // 6. Decidimos a qué panel redirigir según el rol de la persona
        String redirectUrl;
        switch (rolPersona.gettexto_rol()) {
            case "Administrador":
                redirectUrl = "/panel-admin";
                break;
            case "Propietario":
                redirectUrl = "/panel-propietario";
                break;
            case "Mecanico":
                redirectUrl = "/panel-mecanico";
                break;
            default:
                redirectUrl = "/login";
        }

        // 7. Respondemos con éxito
        respuesta.put("mensaje", "Login exitoso");
        respuesta.put("rol", rolPersona.gettexto_rol());
        respuesta.put("nombre", persona.getPrimerNombre());
        respuesta.put("redirectUrl", redirectUrl);

        return ResponseEntity.ok(respuesta);
     }

       @GetMapping("/sesion")
    public ResponseEntity<Map<String, Object>> verSesion(HttpSession session) {

        Map<String, Object> respuesta = new HashMap<>();

        // Revisamos si existe el atributo "idPersona" en la sesión.
        // Si no existe, significa que nadie ha iniciado sesión todavía.
        Object idPersona = session.getAttribute("idPersona");

        if (idPersona == null) {
            respuesta.put("mensaje", "No hay una sesión activa");
            return ResponseEntity.status(HttpStatus.UNAUTHORIZED).body(respuesta);
        }

        respuesta.put("idPersona", idPersona);
        respuesta.put("email", session.getAttribute("email"));
        respuesta.put("rol", session.getAttribute("rol"));

        return ResponseEntity.ok(respuesta);
    }

    @PostMapping("/logout")
    public ResponseEntity<Map<String, Object>> logout(HttpSession session) {

        Map<String, Object> respuesta = new HashMap<>();

        // Destruye la sesión: borra idPersona, email y rol que habíamos guardado
        session.invalidate();

        respuesta.put("mensaje", "Sesión cerrada correctamente");
        return ResponseEntity.ok(respuesta);
    }
}