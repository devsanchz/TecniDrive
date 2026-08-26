package tecnidrive.tecnidrive.controller;

import tecnidrive.tecnidrive.service.MecanicoService;
import tecnidrive.tecnidrive.service.PersonaService;
import tecnidrive.tecnidrive.service.PropietarioService;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.GetMapping;


@Controller
public class VistaController {

    @Autowired
    private PersonaService personaService;

    @Autowired
    private PropietarioService propietarioService;

    @Autowired
    private MecanicoService mecanicoService;
    
    @GetMapping("/")
    public String index() {
    return "index";
    }

//===============================
//ENDPOINTS PARA AUTENTIFICAR
//===============================
    @GetMapping("/login")
    public String login() {
        return "login";
    }

       @GetMapping("/registro")
    public String registro() {
        return "registro";
    }

    @GetMapping("/rol")
    public String rol() {
        return "rol";
    }

//===============================
//ENDPOINTS PARA PROPIETARIO
//===============================

    @GetMapping("/propietario/vehiculo")
    public String propietarioVehiculo() {
        return "propietario-vehiculo";
    }

    @GetMapping("/propietario/taller")
    public String propietarioTaller() {
        return "propietario-taller";
    }

    @GetMapping("/propietario/cita")
    public String propietarioCita() {
        return "propietario-cita";
    }

//===============================
//ENDPOINTS PARA MECANICO
//===============================   
 
    @GetMapping("/mecanico/taller")
    public String mecanicoTaller() {
        return "mecanico-taller";
    }

    @GetMapping("/mecanico/cita")
    public String mecanicoCita() {
        return "mecanico-cita";
    }

    @GetMapping("/mecanico/control")
    public String mecanicoControl() {
        return "mecanico-control";
    }

//===============================
//ENDPOINTS PARA ADMINISTRADOR
//===============================

}