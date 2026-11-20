from django import forms
from django.contrib.auth import password_validation
from django.contrib.auth.forms import AuthenticationForm
from django.core.exceptions import ValidationError
from django.db import transaction

from .models import Mecanico, Persona, Propietario, validar_telefono


class RegistroForm(forms.ModelForm):
    # Solo estos dos roles se pueden elegir al registrarse
    ROLES_PERMITIDOS = [
        (Persona.Rol.PROPIETARIO, "Propietario"),
        (Persona.Rol.MECANICO, "Mecánico"),
    ]

    rol = forms.ChoiceField(choices=ROLES_PERMITIDOS)
    telefono = forms.CharField(max_length=10, validators=[validar_telefono])
    password1 = forms.CharField(label="Contraseña", widget=forms.PasswordInput)
    password2 = forms.CharField(
        label="Confirmar contraseña", widget=forms.PasswordInput
    )

    class Meta:
        model = Persona
        fields = [
            "primer_nombre",
            "segundo_nombre",
            "primer_apellido",
            "segundo_apellido",
            "email",
        ]

    def clean_email(self):
        # Guardamos el email siempre en minúscula para evitar duplicados
        # como Ana@x.com y ana@x.com
        email = self.cleaned_data["email"].lower()
        if Persona.objects.filter(email=email).exists():
            raise ValidationError("Ya existe una cuenta con este correo.")
        return email

    def clean_password2(self):
        password1 = self.cleaned_data.get("password1")
        password2 = self.cleaned_data.get("password2")
        if password1 and password2 and password1 != password2:
            raise ValidationError("Las contraseñas no coinciden.")
        if password2:
            # Aplica los validadores de AUTH_PASSWORD_VALIDATORS (settings.py)
            password_validation.validate_password(password2)
        return password2

    def save(self):
        # FLUJO:
        # 1. Preparamos la Persona sin guardarla todavía.
        # 2. Le asignamos el rol y la contraseña cifrada.
        # 3. Guardamos Persona y perfil juntos (todo o nada).
        with transaction.atomic():
            persona = super().save(commit=False)
            persona.rol = self.cleaned_data["rol"]
            persona.set_password(self.cleaned_data["password1"])
            persona.save()

            telefono = self.cleaned_data["telefono"]
            if persona.rol == Persona.Rol.PROPIETARIO:
                Propietario.objects.create(persona=persona, telefono=telefono)
            else:
                Mecanico.objects.create(persona=persona, telefono=telefono)
        return persona


class LoginForm(AuthenticationForm):
    # AuthenticationForm llama "username" al primer campo; aquí es el email
    username = forms.EmailField(label="Correo electrónico")

    def clean_username(self):
        return self.cleaned_data["username"].lower()