package tecnidrive.tecnidrive.model;

import jakarta.persistence.Entity;
import jakarta.persistence.GeneratedValue;
import jakarta.persistence.GenerationType;
import jakarta.persistence.Id;
import jakarta.persistence.Table;

    @Entity
    @Table(name = "Roles")
    public class Rol{
        @Id
        @GeneratedValue(strategy = GenerationType.IDENTITY)
        private Long id;
        private String texto_rol;

        public Rol(){}
        public Long getid() { return id; }
        public void setid(Long id) { this.id = id; }
        public String gettexto_rol() { return texto_rol; }
        public void settexto_rol(String texto_rol) { this.texto_rol = texto_rol; }
    }