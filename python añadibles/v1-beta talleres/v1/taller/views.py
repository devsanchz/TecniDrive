from django.shortcuts import render

# Create your views here.
from django.shortcuts import render, redirect
from .models import Taller, Mecanico

def lista_talleres(request):
    talleres = Taller.objects.all()
    return render(request, 't_lista.html', {'talleres': talleres})

def crear_taller(request):
    mecanicos = Mecanico.objects.all()
    if request.method == 'POST':
        Taller.objects.create(
            foto_taller=request.POST.get('foto_taller') or None,
            nombre_taller=request.POST.get('nombre_taller'),
            descripcion_taller=request.POST.get('descripcion_taller'),
            direccion_taller=request.POST.get('direccion_taller'),
            horario_taller=request.POST.get('horario_taller'),
            estado_taller=request.POST.get('estado_taller') == 'on',
            motivo_estado=request.POST.get('motivo_estado') or None,
            mecanicos_id_mecanico_id=request.POST.get('mecanicos_id_mecanico')
        )
        return redirect('lista_talleres')
    return render(request, 't_crear.html', {'mecanicos': mecanicos})
