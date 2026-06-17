<?php
namespace Config;

class JWT
{
    // MINIMO 32 CARACTERES PARA QUE HS256 LA ACEPTE
    public static string $key = 'mi_clave_super_secreta_2026_tecnidrive_api';
    public static string $algorithm = 'HS256';
    public static int $expireTime = 3600; // 1 hora
}