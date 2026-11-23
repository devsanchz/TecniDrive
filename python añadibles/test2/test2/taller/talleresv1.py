# models.py
from django.db import models

class Vehiculo(models.Model):
    placa = models.CharField(max_length=10, unique=True)
    marca = models.CharField(max_length=50)
    kilometraje = models.PositiveIntegerField(default=0)

    def __str__(self):
        return self.placa

# views.py
from django.shortcuts import render
from .models import Vehiculo

def lista_vehiculos(request):
    vehiculos = Vehiculo.objects.all()
    return render(request, "lista.html", {"vehiculos": vehiculos})

# urls.py
from django.urls import path
from django import views

urlpatterns = [
    path("vehiculos/", views.lista_vehiculos, name="lista_vehiculos"),
]