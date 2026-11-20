from django.contrib import admin

from .models import LicenciaCategoria, Mecanico, Persona, Propietario


@admin.register(Persona)
class PersonaAdmin(admin.ModelAdmin):
    list_display = ["email", "primer_nombre", "primer_apellido", "rol", "is_active"]
    list_filter = ["rol", "is_active"]
    search_fields = ["email", "primer_nombre", "primer_apellido"]
    # Ocultamos el hash de la contraseña: no se debe editar a mano
    exclude = ["password"]

    def has_add_permission(self, request):
        # Las personas se crean desde el registro de la página, donde la
        # contraseña se cifra bien y se crea también su perfil.
        return False


admin.site.register(Propietario)
admin.site.register(Mecanico)
admin.site.register(LicenciaCategoria)