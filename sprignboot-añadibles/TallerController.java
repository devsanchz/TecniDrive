package tecnidrive.tecnidrive.controller;

import jakarta.servlet.http.HttpSession;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.PostMapping;
import org.springframework.web.bind.annotation.RequestParam;

import tecnidrive.tecnidrive.model.Mecanico;
import tecnidrive.tecnidrive.model.Taller;
import tecnidrive.tecnidrive.service.MecanicoService;
import tecnidrive.tecnidrive.service.TallerService;

@Controller
public class TallerController {

    @Autowired
    private TallerService tallerService;

    @Autowired
    private MecanicoService mecanicoService;

    @PostMapping("/mecanico/taller/registrar")
    public String registrarTaller(
            @RequestParam("nombre") String nombre,
            @RequestParam("especialidad") String especialidad,
            @RequestParam("descripcion") String descripcion,
            @RequestParam("ubicacion") String ubicacion,
            @RequestParam("dias[]") String[] dias,
            @RequestParam("horas[]") String[] horas,
            @RequestParam("servicio[]") String[] servicios,
            @RequestParam("precio[]") String[] precios,
            HttpSession session) {

        Integer idPersona = (Integer) session.getAttribute("idPersona");

        if (idPersona == null) {
            return "redirect:/login";
        }

        Mecanico mecanico = mecanicoService.obtenerPorId(idPersona);

        if (mecanico == null) {
            return "redirect:/login";
        }

        Taller taller = new Taller();

        taller.setNombreTaller(nombre);
        taller.setDescripcionTaller(descripcion);
        taller.setDireccionTaller(ubicacion);

        StringBuilder horario = new StringBuilder();

        for (int i = 0; i < dias.length; i++) {
            if (i > 0) {
                horario.append(" | ");
            }

            horario.append(dias[i])
                   .append(": ")
                   .append(horas[i]);
        }

        taller.setHorarioTaller(horario.toString());

        taller.setEstadoTaller(true);
        taller.setMecanico(mecanico);

        tallerService.registrarTaller(
                taller,
                especialidad,
                servicios,
                precios
        );

        return "redirect:/mecanico/taller?registro=exitoso";
    }
}