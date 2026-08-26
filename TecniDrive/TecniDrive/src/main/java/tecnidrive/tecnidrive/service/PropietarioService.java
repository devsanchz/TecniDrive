package tecnidrive.tecnidrive.service;

import org.springframework.stereotype.Service;
import tecnidrive.tecnidrive.model.Propietario;
import tecnidrive.tecnidrive.repository.PropietarioRepository;

import java.util.List;
import java.util.Optional;

@Service
public class PropietarioService{
    private final PropietarioRepository propietarioRepository;

    public PropietarioService(PropietarioRepository propietarioRepository){
        this.propietarioRepository = propietarioRepository;
    }
    public List<Propietario> listarPropietarios(){
        return propietarioRepository.findAll();
    }
    public Optional<Propietario> buscarporId(Integer id){
        return propietarioRepository.findById(id);
    }
    public Propietario guardar(Propietario propietario){
        return propietarioRepository.save(propietario);
    }
}