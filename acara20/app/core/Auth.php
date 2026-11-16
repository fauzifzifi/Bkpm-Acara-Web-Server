<?php
class Auth {
 const SECRET = 'kunci_rahasia_workshop_2026';
 private static function base64url(string $data): string {
 return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
 }
 public static function generateToken(array $payload): string {
 $header = self::base64url(json_encode(['alg' => 'HS256', 'typ' =>
'JWT']));
 $payload['exp'] = time() + 3600;
 $payloadEnc = self::base64url(json_encode($payload));
 $signature = self::base64url(hash_hmac('sha256',
"$header.$payloadEnc", self::SECRET, true));
 return "$header.$payloadEnc.$signature";
 }
 public static function validateToken(string $token) {
 $parts = explode('.', $token);
 if (count($parts) !== 3) return false;
 [$header, $payload, $signature] = $parts;
 $valid = self::base64url(hash_hmac('sha256', "$header.$payload",
self::SECRET, true));
 if (!hash_equals($valid, $signature)) return false;
 $data = json_decode(base64_decode($payload), true);
 if ($data['exp'] < time()) return false;
 return $data;
 }
 public static function requireAuth(): array {
 $headers = getallheaders();
 $authHeader = $headers['Authorization'] ?? '';
 if (!preg_match('/Bearer\\s(\\S+)/', $authHeader, $m)) {
 Response::json(401, false, 'Token tidak ditemukan');
 }
 $payload = self::validateToken($m[1]);
 if (!$payload) {
 Response::json(401, false, 'Token tidak valid atau
kedaluwarsa');
 }
 return $payload;
 }
}
