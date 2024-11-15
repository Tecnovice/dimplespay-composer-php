# **DimplesPay PHP SDK**

[![License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)  
A simple and flexible PHP SDK for integrating with the **DimplesPay API**, designed for seamless payment management.

---

## **Features**
- Authenticate with the DimplesPay API
- Perform transactions and manage payments
- Simplified HTTP requests using Guzzle
- Extendable for custom implementations

---

## **Installation**

To get started, install the SDK using Composer:

```bash
composer require tecnovice/dimplespay
```

Ensure you have PHP 8.0 or later installed.

---

## **Usage**

### **Step 1: Configure Environment Variables**
Create a `.env` file in your project root or set the environment variables directly in your system. Use the following keys:

```env
DIMPLESPAY_CLIENT_ID=your-client-id
DIMPLESPAY_SECRET_ID=your-secret-id
DIMPLESPAY_BASE_URL=https://api.dimplespay.com
```

Alternatively, you can pass these directly to the client during initialization.

---

### **Step 2: Initialize the Client**

```php
require 'vendor/autoload.php';

use DimplesPay\DimplesPayClient;

$client = new DimplesPayClient([
    'client_id' => 'your-client-id',
    'secret_id' => 'your-secret-id',
    'base_url' => 'https://api.dimplespay.com'
]);
```

---

### **Step 3: Fetch an Access Token**

```php
$accessToken = $client->getAccessToken();
echo "Access Token: " . $accessToken['access_token'];
```

---

### **1. Authentication: Get Access Token**  
Generate an access token to initiate secure API calls.  

#### **Method:**  
`POST /authentication/token`  

#### **Example Usage:**  

```php
require 'vendor/autoload.php';

use DimplesPay\DimplesPayClient;

$client = new DimplesPayClient([
    'client_id' => 'your-client-id',
    'secret_id' => 'your-secret-id',
    'base_url' => 'https://api.dimplespay.com'
]);

$response = $client->getAccessToken();

if ($response['status'] === 'success') {
    echo "Access Token: " . $response['access_token'];
    echo "Expires In: " . $response['expire_time'] . " seconds";
} else {
    echo "Failed to fetch token: " . $response['message'];
}
```

---

### **2. Initiate Payment**  
Start a new payment transaction.  

#### **Endpoint:**  
`POST /payment/create`  

#### **Required Parameters:**  
- `amount`: (decimal) The transaction amount (must be rounded to 2 decimal places).  
- `currency`: (string) Currency code in uppercase (e.g., "USD", "XAF").  
- `return_url`: (string) URL to redirect after payment success.  
- `cancel_url`: (string, optional) URL to redirect after payment failure.  
- `custom`: (string, optional) Custom transaction ID for reference.  

#### **Example Usage:**  

```php
$response = $client->initiatePayment([
    'amount' => 100.00,
    'currency' => 'USD',
    'return_url' => 'https://example.com/success',
    'cancel_url' => 'https://example.com/cancel',
    'custom' => '123ABC456DEF'
]);

if ($response['status'] === 'success') {
    echo "Payment Created Successfully!";
    echo "Payment URL: " . $response['data']['payment_url'];
} else {
    echo "Payment Failed: " . $response['message'];
}
```

---

### **3. Check Payment Status**  
Retrieve the status of a payment.  

#### **Endpoint:**  
`GET /payment/status`  

#### **Required Parameters:**  
- `token`: (string) The payment token obtained during payment initiation.  

#### **Example Usage:**  

```php
$response = $client->checkPaymentStatus('payment-token-here');

if ($response['status'] === 'success') {
    echo "Payment Status: SUCCESS";
    echo "Transaction ID: " . $response['data']['trx_id'];
    echo "Payer Email: " . $response['data']['payer']['email'];
} else {
    echo "Failed to fetch payment status: " . $response['message'];
}
```

---

## **Error Handling**  

Every API call may throw an exception in case of invalid data or server issues. Wrap calls in try-catch blocks to handle errors gracefully.  

```php
try {
    $response = $client->initiatePayment([...]);
} catch (\DimplesPay\Exceptions\DimplesPayException $e) {
    echo "Error: " . $e->getMessage();
}
```

---

This documentation now focuses solely on the endpoints for **authentication**, **payment initiation**, and **payment status checks**. Include this in your `README.md` file for clarity and developer guidance. Let me know if any other adjustments are needed!

---

## **Testing**

Install PHPUnit to run tests:

```bash
composer install --dev
vendor/bin/phpunit
```

---

## **Contributing**

We welcome contributions! To get started:

1. Fork the repository
2. Create a new branch for your feature/bugfix
3. Commit your changes
4. Submit a pull request

---

## **License**

This package is open-sourced software licensed under the [MIT license](LICENSE).

---

## **Support**

For issues or feature requests, please visit the [GitHub Issues page](https://github.com/tecnovice/dimplespay/issues).

---

Feel free to modify this as needed! Let me know if you’d like additional sections or further customization.