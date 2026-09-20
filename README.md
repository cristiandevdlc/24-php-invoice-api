# PHP Invoice API

API PHP sin framework para calcular subtotal, impuesto y total de una factura.

```powershell
php -S localhost:8082 index.php
curl -X POST http://localhost:8082/invoice -H "Content-Type: application/json" -d '{"client":"Acme","items":[{"description":"Web","quantity":1,"price":1200}]}'
```
