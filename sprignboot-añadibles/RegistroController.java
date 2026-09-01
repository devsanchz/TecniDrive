package tecnidrive.tecnidrive.controller;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.PostMapping;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RequestParam;

import jakarta.servlet.http.HttpSession;
import tecnidrive.tecnidrive.service.RegistroService;

@Controller
@RequestMapping("/autentificar")
public class RegistroController {

    @Autowired
    private RegistroService registroService;

    @PostMapping("/registro")
    public String registrar(
            @RequestParam("primer_nombre") String primerNombre,
            @RequestParam(value = "segundo_nombre", required = false) String segundoNombre,
            @RequestParam("primer_apellido") String primerApellido,
            @RequestParam("segundo_apellido") String segundoApellido,
            @RequestParam("telefono") Long telefono,
            @RequestParam("email") String email,
            @RequestParam("password") String password,
            HttpSession session) {

        session.setAttribute("primerNombre", primerNombre);
        session.setAttribute("segundoNombre", segundoNombre);
        session.setAttribute("primerApellido", primerApellido);
        session.setAttribute("segundoApellido", segundoApellido);
        session.setAttribute("telefono", telefono);
        session.setAttribute("email", email);
        session.setAttribute("password", password);

        return "redirect:/rol";
    }

    @PostMapping("/finalizar")
    public String finalizarRegistro(
            @RequestParam("rol") Integer idRol,
            HttpSession session) {

        String primerNombre = (String) session.getAttribute("primerNombre");
        String segundoNombre = (String) session.getAttribute("segundoNombre");
        String primerApellido = (String) session.getAttribute("primerApellido");
        String segundoApellido = (String) session.getAttribute("segundoApellido");
        Long telefono = (Long) session.getAttribute("telefono");
        String email = (String) session.getAttribute("email");
        String password = (String) session.getAttribute("password");

        if (primerNombre == null || email == null || password == null) {
            return "redirect:/registro";
        }

        if (idRol == 2) {

            registroService.registrarPropietario(
                    primerNombre,
                    segundoNombre,
                    primerApellido,
                    segundoApellido,
                    telefono,
                    email,
                    password
            );

        } else if (idRol == 3) {

            registroService.registrarMecanico(
                    primerNombre,
                    segundoNombre,
                    primerApellido,
                    segundoApellido,
                    telefono,
                    email,
                    password
            );

        } else {
            return "redirect:/rol";
        }

        session.invalidate();

        return "redirect:/login";
    }
}