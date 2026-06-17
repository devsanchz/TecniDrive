<?php
namespace App\Filters;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Config\JWT as JWTConfig;
class JWTAuthFilter implements FilterInterface
{
public function before(
RequestInterface $request,
$arguments = null
) {
$header = $request->getHeaderLine(
'Authorization'
);
if (!$header) {
return service('response')
->setStatusCode(401)
->setJSON([
'error' => 'Token requerido'
]);
}

$token = str_replace(
'Bearer ',
'',
$header
);
try {
$decoded = JWT::decode(
$token,
new Key(
JWTConfig::$key,
JWTConfig::$algorithm
)
);
$request->user = $decoded;
} catch (\Exception $e) {
return service('response')
->setStatusCode(401)
->setJSON([
'error' => 'Token inválido'
]);
}
}
public function after(
RequestInterface $request,
ResponseInterface $response,
$arguments = null
) {
}
}