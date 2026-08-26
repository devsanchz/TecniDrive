package tecnidrive.tecnidrive.service;

import java.util.List;
import java.util.Optional;

import org.springframework.stereotype.Service;

import tecnidrive.tecnidrive.model.Rol;
import tecnidrive.tecnidrive.repository.RolRepository;

@Service
public class RolService{
    private final RolRepository rolRepository;
    
    public RolService(RolRepository rolRepository){
        this.rolRepository = rolRepository;
    }
    public List<Rol> listarRoles(){
        return rolRepository.findAll();
    }
    public Optional<Rol> buscarporId(Integer id){
        return rolRepository.findById(id);
    }
    public Rol guardar(Rol rol){
        return rolRepository.save(rol);
    }
    public void eliminar(Integer id){
        rolRepository.deleteById(id);
    }
}