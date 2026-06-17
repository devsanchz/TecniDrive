const val APP_VERSION = "1.0.0"

fun main() {
    while (true) {
        println("Bienvenido a TECNIDRIVE v$APP_VERSION")

        println("""
            ----- Gestiona talleres y vehículos con total control -----
            Selecciona una opción:
            1.- Iniciar sesión
            2.- Registrarse
        """.trimIndent())

        when (readln().toIntOrNull()) {
            1 -> addingreso()
            2 -> addregistro()
            else -> println("Opción inválida")
        }
    }
}

fun addingreso() {
    println("=== INICIO DE SESIÓN ===")

    println("Correo:")
    val email = readln()

    println("Contraseña:")
    val password = readln()

    // ❌ Si está vacío, mostramos opciones
    if (email.isBlank() || password.isBlank()) {
        println("Datos incompletos")

        while (true) {
            println("""
                1.- Intentar de nuevo
                2.- Recuperar contraseña
                3.- Volver
            """.trimIndent())

            when (readln().toIntOrNull()) {
                1 -> return addingreso() // volver a intentar login
                2 -> addrecuperar()
                3 -> return              // vuelve al menú principal
                else -> println("Opción inválida")
            }
        }
    }

    println("Verificando datos...")
}

fun addregistro() {
    println("Crea tu cuenta...")

    println("Primer nombre:")
    val nombre = readln()

    println("Segundo nombre (opcional):")
    val nombre2 = readln()

    println("Primer Apellido:")
    val apellido = readln()

    println("Segundo Apellido:")
    val apellido2 = readln()

    println("Teléfono:")
    val telefono = readln()

    println("Correo electrónico:")
    val email = readln()

    println("Contraseña:")
    val password = readln()

    if (email.isNotBlank() && password.isNotBlank()) {
        println("Registro exitoso")
    } else {
        println("Datos incompletos")
    }
}

fun addrecuperar() {
    println("""
        ----- Te enviaremos un código a tu correo -----
        4.- Enviar
    """.trimIndent())

    when (readln().toIntOrNull()) {
        4 -> addcodigo()
        else -> println("Opción inválida")
    }
}

fun addcodigo() {
    println("Código enviado, escríbelo:")
    val codigo = readln()

    if (codigo.isNotBlank()) {
        println("Restableciendo acceso...")
    }
}