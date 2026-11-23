from django.urls import path
from . import views

urlpatterns = [
    path('talleres/', views.lista_talleres, name='lista_talleres'),
    path('talleres/crear/', views.crear_taller, name='crear_taller'),
]
