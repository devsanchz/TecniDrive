from django.shortcuts import redirect, render

# Create your views here.
from django.contrib import messages
from django.contrib.auth import login
from django.contrib.auth.decorators import login_required

from .forms import RegistroForm


def registro(request):
    # Si ya inició sesión, no tiene sentido registrarse otra vez
    if request.user.is_authenticated:
        return redirect("inicio")

    if request.method == "POST":
        form = RegistroForm(request.POST)
        if form.is_valid():
            persona = form.save()
            login(request, persona)  # abre la sesión
            messages.success(request, "Tu cuenta fue creada correctamente.")
            return redirect("inicio")
    else:
        form = RegistroForm()

    return render(request, "cuentas/registro.html", {"form": form})


@login_required
def inicio(request):
    # Más adelante cada rol tendrá su propio panel
    return render(request, "cuentas/inicio.html")