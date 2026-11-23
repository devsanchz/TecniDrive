from django.contrib import admin

# Register your models here.
from django.contrib import admin
from .models import Mecanico, Taller

admin.site.register(Mecanico)
admin.site.register(Taller)
