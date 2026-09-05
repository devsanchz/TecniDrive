package tecnidrive.tecnidrive.service;

import java.math.BigDecimal;
import java.util.HashSet;
import java.util.List;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;

import tecnidrive.tecnidrive.model.Especialidad;
import tecnidrive.tecnidrive.model.Mecanico;
import tecnidrive.tecnidrive.model.Servicio;
import tecnidrive.tecnidrive.model.Taller;
import tecnidrive.tecnidrive.model.TallerHasServicio;
import tecnidrive.tecnidrive.model.TallerHasServicioId;
import tecnidrive.tecnidrive.repository.EspecialidadRepository;
import tecnidrive.tecnidrive.repository.ServicioRepository;
import tecnidrive.tecnidrive.repository.TallerHasServicioRepository;
import tecnidrive.tecnidrive.repository.TallerRepository;

@Service
public class TallerService {

    @Autowired
    private TallerRepository tallerRepository;

    @Autowired
    private EspecialidadRepository especialidadRepository;

    @Autowired
    private ServicioRepository servicioRepository;

    @Autowired
    private TallerHasServicioRepository tallerHasServicioRepository;

    public Taller obtenerTallerDelMecanico(Integer idMecanico) {
        List<Taller> talleres = tallerRepository.findByMecanico_IdPersona(idMecanico);

        if (talleres.isEmpty()) {
            return null;
        }

        return talleres.get(0);
    }

    @Transactional
    public void registrarTaller(
            Taller taller,
            String nombreEspecialidad,
            String[] nombresServicios,
            String[] precios) {

        // Especialidad
        Especialidad especialidad = especialidadRepository.findAll()
                .stream()
                .filter(e -> e.getNombreEspecialidad()
                        .equalsIgnoreCase(nombreEspecialidad))
                .findFirst()
                .orElseGet(() -> {
                    Especialidad nueva = new Especialidad();
                    nueva.setNombreEspecialidad(nombreEspecialidad);
                    return especialidadRepository.save(nueva);
                });

        HashSet<Especialidad> especialidades = new HashSet<>();
        especialidades.add(especialidad);
        taller.setEspecialidades(especialidades);

        // Guardar taller primero para obtener su ID
        Taller tallerGuardado = tallerRepository.save(taller);

        // Guardar servicios y sus precios
        for (int i = 0; i < nombresServicios.length; i++) {

            String nombreServicio = nombresServicios[i];

            Servicio servicio = servicioRepository.findAll()
                    .stream()
                    .filter(s -> s.getNombreServicio()
                            .equalsIgnoreCase(nombreServicio))
                    .findFirst()
                    .orElseGet(() -> {
                        Servicio nuevo = new Servicio();
                        nuevo.setNombreServicio(nombreServicio);
                        return servicioRepository.save(nuevo);
                    });

            BigDecimal precio = convertirPrecio(precios[i]);

            TallerHasServicio relacion = new TallerHasServicio();

            TallerHasServicioId id = new TallerHasServicioId();
            id.setIdTaller(tallerGuardado.getIdTaller());
            id.setIdServicio(servicio.getIdServicio());

            relacion.setId(id);
            relacion.setPrecioServicio(precio);

            tallerHasServicioRepository.save(relacion);
        }
    }

    private BigDecimal convertirPrecio(String precioTexto) {

        String precioLimpio = precioTexto
                .replace("$", "")
                .replace(".", "")
                .replace(",", ".")
                .trim();

        return new BigDecimal(precioLimpio);
    }
}