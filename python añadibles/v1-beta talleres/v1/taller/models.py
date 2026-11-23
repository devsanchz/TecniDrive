from django.db import models

# Create your models here.


class Mecanico(models.Model):
    id_mecanico = models.AutoField(primary_key=True)
    telefono_mecanico = models.BigIntegerField()

    class Meta:
        db_table = 'mecanicos'

    def __str__(self):
        return f"Mecánico #{self.id_mecanico} ({self.telefono_mecanico})"


class Taller(models.Model):
    id_taller = models.AutoField(primary_key=True)
    foto_taller = models.CharField(max_length=255, null=True, blank=True)
    nombre_taller = models.CharField(max_length=80)
    descripcion_taller = models.CharField(max_length=150)
    direccion_taller = models.CharField(max_length=80)
    horario_taller = models.CharField(max_length=150)
    fecha_registro = models.DateTimeField(auto_now_add=True)
    estado_taller = models.BooleanField(default=True)
    motivo_estado = models.CharField(max_length=100, null=True, blank=True)
    mecanicos_id_mecanico = models.ForeignKey(
        Mecanico,
        on_delete=models.CASCADE,
        db_column='mecanicos_id_mecanico'
    )

    class Meta:
        db_table = 'taller'

    def __str__(self):
        return self.nombre_taller
