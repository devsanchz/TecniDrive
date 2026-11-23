#aqui solo me faltaria lo de las urls que redirecciona los datos
from django import models
class Dias(models.Model):
    dias =[
        (0, 'lunes')
        (1, 'martes')
        (2, 'miercoles')
        (3, 'jueves')
        (4, 'viernes')
        (5, 'sabado')
        (6, 'domingo')
    ]

dia = models.IntegerField()
H_apertura = models.TimeField()
H_cierre = models.TimeField()

#el dato devuelto en este def es bool, para simplificar las cosas, ya el sistema muestra si esta cerrado de horario
# y no si esta deshabilitado del sistema

def cerrado(cerrado):
    if H_apertura <= H_cierre:
        return H_apertura <= cerrado <= H_cierre

    if H_apertura >= H_cierre:
        return H_apertura <= cerrado >= H_cierre
