package tecnidrive.tecnidrive.model;

import jakarta.persistence.Entity;
import jakarta.persistence.GeneratedValue;
import jakarta.persistence.GenerationType;
import jakarta.persistence.Id;
import jakarta.persistence.JoinColumn;
import jakarta.persistence.ManyToOne;
import jakarta.persistence.Table;

@Entity
@Table(name = "Propietario")
public class Propietario {
    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long id_propietario;
    private int telefono_propietario;
    private String numero_licencia;

    @ManyToOne
    @JoinColumn(name = "Rol_ID")
    private Rol rol;

    public Propietario(){}
    public Long getid_propietario() { return id_propietario; }
    public void setid_propietario(Long id_propietario) { this.id_propietario = id_propietario; }
    public int gettelefono_propietario() { return telefono_propietario; }
    public void settelefono_propietario(int telefono_propietario) { this.telefono_propietario = telefono_propietario; }
    public String getnumero_licencia() { return numero_licencia; }
    public void setnumero_licencia(String numero_licencia) { this.numero_licencia = numero_licencia; }
    public Rol getRol() { return rol; }
    public void setRol(Rol rol) { this.rol = rol; }
}