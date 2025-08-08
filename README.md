# Metric Satellite

**Metric Satellite** is a Laravel package that provides environment and application information for a Laravel installation through a secure API endpoint.

---

## Installation

Install the package via Composer:

```bash
composer require aryasvitkona/metric-satellite
```

Publish the configuration file:

```bash
php artisan vendor:publish --tag=metric-satellite-config
```

---

## Authentication

Metric Satellite supports HMAC-based authentication, optional IP allowlisting, and static token validation.

### Environment Variables

Add the following to your .env file:

```bash
SATELLITE_HMAC_ENABLED=true
SATELLITE_HMAC_KEY="superkey"

SATELLITE_TOKEN="supertoken"

SATELLITE_ALLOW_IPS="1.2.3.4,10.20.30.40/22"
```

### Token Example

```bash
curl -X GET "https://example.com/api/metrics/satellite" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer supersecret"
```

### HMAC Example

```bash
curl -X GET "https://example.com/api/metrics/satellite" \
 -H "Accept: application/json" \
 -H "X-Signature-Timestamp: 1733758484" \
 -H "X-Signature: 3f7e2f23e8b20a6e0f8b7b3dc8c5a2ffcb5f5c83e3c9b4c2db2a1adf1a4f32e9"
```

---

## License

This package is open-sourced software licensed under the MIT license.
