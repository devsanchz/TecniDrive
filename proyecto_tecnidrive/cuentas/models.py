from django.db import models

# Create your models here.
from django.contrib.auth.models import (
    AbstractBaseUser,
    BaseUserManager,
    PermissionsMixin,
)
from django.core.validators import RegexValidator
from django.db import models
from django.utils import timezone


# Validador reutilizable: celular colombiano de 10 dígitos que empieza por 3
validar_telefono = RegexValidator(
    regex=r"^3\d{9}$",
    message="Ingresa un celular válido de 10 dígitos que empiece por 3.",
)


class PersonaManager(BaseUserManager):
    """Enseña a Django a crear usuarios usando el email como identificador."""

    def create_user(self, email, password=None, **campos_extra):
        if not email:
            raise ValueError("El email es obligatorio.")
        # normalize_email pone en minúscula la parte del dominio (@GMAIL.com -> @gmail.com)
        email = self.normalize_email(email)
        persona = self.model(email=email, **campos_extra)
        # set_password guarda la contraseña cifrada (hash), nunca en texto plano
        persona.set_password(password)
        persona.save(using=self._db)
        return persona

    def create_superuser(self, email, password=None, **campos_extra):
        campos_extra.setdefault("is_staff", True)
        campos_extra.setdefault("is_superuser", True)
        campos_extra.setdefault("rol", Persona.Rol.ADMINISTRADOR)
        return self.create_user(email, password, **campos_extra)


class Persona(AbstractBaseUser, PermissionsMixin):
    # Roles: valores fijos, por eso usamos choices y no una tabla aparte.
    # El primer valor se guarda en la base de datos; el segundo se muestra al usuario.
    class Rol(models.TextChoices):
        ADMINISTRADOR = "administrador", "Administrador"
        PROPIETARIO = "propietario", "Propietario"
        MECANICO = "mecanico", "Mecánico"

    primer_nombre = models.CharField(max_length=30)
    segundo_nombre = models.CharField(max_length=30, blank=True)
    primer_apellido = models.CharField(max_length=25)
    segundo_apellido = models.CharField(max_length=25)
    email = models.EmailField(max_length=60, unique=True)
    rol = models.CharField(max_length=15, choices=Rol.choices, default=Rol.PROPIETARIO)
    fecha_registro = models.DateTimeField(auto_now_add=True)

    # Campos que Django exige para el login y el admin
    is_active = models.BooleanField(default=True)
    is_staff = models.BooleanField(default=False)

    objects = PersonaManager()

    # El email reemplaza al username para iniciar sesión
    USERNAME_FIELD = "email"
    # Campos que pide createsuperuser además del email y la contraseña
    REQUIRED_FIELDS = ["primer_nombre", "primer_apellido", "segundo_apellido"]

    def __str__(self):
        return f"{self.primer_nombre} {self.primer_apellido}"

    def nombre_completo(self):
        partes = [
            self.primer_nombre,
            self.segundo_nombre,
            self.primer_apellido,
            self.segundo_apellido,
        ]
        # Une solo las partes que no están vacías (el segundo nombre es opcional)
        return " ".join(parte for parte in partes if parte)


class Propietario(models.Model):
    # OneToOne + primary_key: este registro ES la persona, ampliada con datos de propietario.
    # Acceso inverso automático: persona.propietario
    persona = models.OneToOneField(
        Persona,
        on_delete=models.CASCADE,
        primary_key=True,
        related_name="propietario",
    )
    telefono = models.CharField(max_length=10, validators=[validar_telefono])
    # null=True aquí es la excepción: permite varios propietarios sin licencia
    # y mantiene unique=True para los que sí la tienen.
    numero_licencia = models.CharField(
        max_length=11, unique=True, null=True, blank=True
    )

    def __str__(self):
        return f"Propietario: {self.persona.nombre_completo()}"


class Mecanico(models.Model):
    persona = models.OneToOneField(
        Persona,
        on_delete=models.CASCADE,
        primary_key=True,
        related_name="mecanico",
    )
    telefono = models.CharField(max_length=10, validators=[validar_telefono])

    def __str__(self):
        return f"Mecánico: {self.persona.nombre_completo()}"


class LicenciaCategoria(models.Model):
    """Una categoría de licencia que tiene un propietario, con su vigencia."""

    class Categoria(models.TextChoices):
        A1 = "A1", "A1"
        A2 = "A2", "A2"
        B1 = "B1", "B1"
        B2 = "B2", "B2"
        C1 = "C1", "C1"
        C2 = "C2", "C2"
        C3 = "C3", "C3"

    propietario = models.ForeignKey(
        Propietario, on_delete=models.CASCADE, related_name="categorias"
    )
    categoria = models.CharField(max_length=3, choices=Categoria.choices)
    vigencia = models.DateField()

    class Meta:
        # Un propietario no puede tener dos veces la misma categoría
        constraints = [
            models.UniqueConstraint(
                fields=["propietario", "categoria"],
                name="unica_categoria_por_propietario",
            )
        ]

    @property
    def esta_vigente(self):
        # Se calcula con la fecha de hoy, así nunca queda desactualizado
        return self.vigencia >= timezone.localdate()

    def __str__(self):
        return f"{self.categoria} - {self.propietario.persona.nombre_completo()}"