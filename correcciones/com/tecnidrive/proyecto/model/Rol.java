package com.tecnidrive.proyecto.model;

//como lo hizo LAURA esta bien, pero se puede  simplificar en la llamada para importar las herramientas del JPA
import jakarta.persistence.*;

//LOMBOK DE DAVID
import lombok.Getter;
import lombok.NoArgsConstructor;
import lombok.Setter;

@Getter
@Setter
@NoArgsConstructor
@Entity
@Table(name = "roles")

public class Rol {
//CORRECION: segun nuestra base de datos los roles no tienen auto incremento entonces quite
//tambien esa linea para ser acordes a la BDD

//CORRECION: tambien el nombre del id lo cambie como esta en la  base de dato ya que por lo general JPA relaciona
//los nombres de los atributos con las columnas en la BDD
//CORRECION: para seguir acorde a la BDD aun que long esta bien y no afecta usuaremos integer de acuerdo a nuestro tipo de dato int que esta en la base
@Id
@Column(name = "id_rol")
private Integer idRol;

@Column(name = "texto_rol", length = 15, nullable = false)
private String textoRol;


//METODOS PARA ESTO USE LA EXTENSION LOMBOK DE DAVID
}
