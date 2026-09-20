<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');
$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
if ($_SERVER['REQUEST_METHOD'] === 'GET' && $path === '/health') { echo json_encode(['status' => 'ok']); exit; }
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || $path !== '/invoice') { http_response_code(404); echo json_encode(['error' => 'Ruta no encontrada']); exit; }
$input = json_decode(file_get_contents('php://input'), true);
if (!is_array($input) || !is_array($input['items'] ?? null) || count($input['items']) === 0) { http_response_code(400); echo json_encode(['error' => 'items es obligatorio']); exit; }
$subtotal = 0.0; $items = [];
foreach ($input['items'] as $item) {
    $quantity = (float)($item['quantity'] ?? 0); $price = (float)($item['price'] ?? 0);
    if ($quantity <= 0 || $price < 0) { http_response_code(400); echo json_encode(['error' => 'Cantidad o precio inválido']); exit; }
    $line = $quantity * $price; $subtotal += $line; $items[] = ['description' => trim((string)($item['description'] ?? 'Servicio')), 'quantity' => $quantity, 'price' => $price, 'total' => round($line, 2)];
}
$tax = round($subtotal * 0.16, 2); echo json_encode(['client' => $input['client'] ?? 'Cliente', 'items' => $items, 'subtotal' => round($subtotal, 2), 'tax' => $tax, 'total' => round($subtotal + $tax, 2)], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
