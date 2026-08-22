package com.tecnidrive.proyecto.model;

//Como lo hizo DAVID esta bien pero tambien se puede simplificar algunas cosas como los import
import jakarta.persistence.*;
import java.time.LocalDateTime;
import lombok.Getter;
import lombok.NoArgsConstructor;
import lombok.Setter;

//👹PARA EVITAR DATOS REPETIDOS
import java.util.Set;


@Getter
@Setter
@NoArgsConstructor
@Entity
//👹 Indica que Persona es la clase padre y que las clases hijas,
// como Propietario, tendrán su propia tabla relacionada mediante el mismo ID.
@Inheritance(strategy = InheritanceType.JOINED)

@Table(name = "personas")

public class Persona {

     @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    @Column(name = "id_persona")
    private Integer idPersona;

  
    @Column(name = "primer_nombre", length = 30, nullable = false)
    private String primerNombre;

    @Column(name = "segundo_nombre", length = 30)
    private String segundoNombre;

    @Column(name = "primer_apellido", length = 25, nullable = false)
    private String primerApellido;

    @Column(name = "segundo_apellido", length = 25, nullable = false)
    private String segundoApellido;

    @Column(name = "email", length = 60, nullable = false, unique = true)
    private String email;

    @Column(name = "password_hash", length = 255, nullable = false)
    private String passwordHash;

    @Column(name = "codigo_recuperacion", length = 10)
    private String codigoRecuperacion;

    @Column(name = "fecha_expiracion")
    private LocalDateTime fechaExpiracion;

//CORRECION: quite el creationtimestamp ya que la BDD ya esta con current_time que mysql gestiona la fecha
// insertable = false: le dice a Hibernate que no mande esta columna en el INSERT, para que mysql use su propio DEFAULT CURRENT_TIMESTAMP
    @Column(name = "fecha_registro", nullable = false, updatable = false, insertable = false)
    private LocalDateTime fechaRegistro;

    @Column(name = "avatarcolor", length = 15)
    private String avatarColor;


//👹MI PARTE:agrego la relacion con la tabla ROLES, aqui se usan cosas nuvas: manytomany: se refiere que tanto como personas y roles pueden tener varios cada uno,
 //jointable: es para decir ue la relacion entre dos entidades ya esta en una tabla y se ponen el nombre de esa: el primer join es para ver que relacion representa a 
// la persona y el segundo como es para ver que relacion representa a la entidad Rol, y setRol evita que se repitan elementos dentro de la colección de roles de una persona.
   @ManyToMany
@JoinTable(
    name = "roles_has_persona",
    joinColumns = @JoinColumn(name = "personas_id_persona"),
    inverseJoinColumns = @JoinColumn(name = "roles_id_rol")
)
private Set<Rol> roles;



//METODOS PARA ESTO USE LA EXTENSION LOMBOK DE DAVID
}
