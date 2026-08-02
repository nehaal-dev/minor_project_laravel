# Laravel REST API — Phase 1 (Public API)
> Flow pehle socho, code baad mein likho

---

## API Kya Hai

```
API = Application Programming Interface
    = Do alag applications ke beech intermediary
    = Frontend (React/Mobile) ↔ Backend (Laravel) ka bridge
```

---

## web.php vs api.php — 3 Fark

| | web.php | api.php |
|---|---|---|
| URL | `/customers` | `/api/customers` (auto prefix) |
| CSRF | Mandatory (`@csrf`) | Nahi hota — token auth use hoti hai |
| Response | HTML/Blade | JSON |

---

## HTTP Methods — Rule

```
GET    → data read karna (list ya single)
POST   → naya data save karna
PUT    → existing data update karna
DELETE → data delete karna
```

---

## HTTP Status Codes — Yaad Rakho

```
200 → OK — data mila, sab theek
201 → Created — naya record bana
404 → Not Found — record nahi mila
422 → Unprocessable — validation fail
401 → Unauthorized — login nahi hai
```

---

## Project Setup

### api.php File Banana — Laravel 11

```bash
php artisan install:api
```

Ye automatically karta hai:
```
✓ routes/api.php file banata hai
✓ laravel/sanctum install karta hai
✓ personal_access_tokens migration run karta hai
```

### User Model Mein Trait Add Karo

```php
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
}
```

---

## Folder Structure — Industry Convention

```
app/Http/Controllers/
├── Api/
│   └── CustomerController.php  ← API controllers
└── CustomerController.php      ← Web controllers
```

**Command:**
```bash
php artisan make:Controller Api/CustomerController
# Api/ folder automatically ban jaata hai
```

**Namespace:**
```php
namespace App\Http\Controllers\Api;  // Api folder ke andar
```

---

## Routes — api.php

```php
use App\Http\Controllers\Api\CustomerController;

Route::get('/customers',  [CustomerController::class, 'index']);
Route::post('/customers', [CustomerController::class, 'store']);
```

**Rules:**
```
URL pe action mat likho:
POST /customers/store  ❌
POST /customers        ✓  — HTTP method se action pata chalta hai

web controller import mat karo:
use App\Http\Controllers\CustomerController;     ❌
use App\Http\Controllers\Api\CustomerController; ✓
```

---

## Controller — Response Format

### Web Controller vs API Controller

```php
// Web — HTML return karta hai
return view('customers.index', compact('customers'));

// API — JSON return karta hai
return response()->json([
    'success' => true,
    'message' => 'Data fetched.',
    'data'    => $data
], 200);
```

### Standard JSON Response Format

```php
// Success
return response()->json([
    'success' => true,           // boolean
    'message' => 'Human readable message.',
    'data'    => $data           // actual data
], 200);

// Error
return response()->json([
    'success' => false,
    'message' => 'Validation failed.',
    'errors'  => $validator->errors()
], 422);
```

---

## Validation — 2 Ways

### Way 1 — `$request->validate()` (Simple)

```php
$request->validate([
    'name' => 'required|string|min:3',
]);
// Fail hone pe automatically JSON return karta hai
// BUT — Postman mein ye header set karna padega:
// Accept: application/json
```

### Way 2 — `validator()` Manual (More Control)

```php
$validator = validator($request->all(), [
    'name'    => 'required|string|min:3',
    'country' => 'required|string',
    'gender'  => 'required|string',
    'payment' => 'required|array',
    'image'   => 'required|file|max:2048',
]);

if ($validator->fails()) {  // fails() — 's' lagta hai, fail() nahi
    return response()->json([
        'success' => false,
        'message' => 'Validation failed.',
        'errors'  => $validator->errors()
    ], 422);
}
```

**Fark:**
```
$request->validate()  → simple, Accept header mandatory
validator() manual    → zyada control, header ki zarurat nahi
```

---

## index() Method

### Socho Pehle
```
1. Saara data chahiye — paginate karo (all() mat use karo large data pe)
2. Resource se format control karo
3. JSON return karo — 200
```

```php
public function index()
{
    return response()->json([
        'success' => true,
        'message' => 'Customer data fetched successfully.',
        'data'    => CustomerResource::collection(Customer::paginate(5))
    ], 200);
}
```

---

## store() Method

### Socho Pehle
```
1. Validate karo — pehle
2. File upload karo — validate ke baad
3. Database mein save karo
4. Resource se format karke return karo — 201
```

```php
public function store(Request $request)
{
    $validator = validator($request->all(), [
        'name'    => 'required|string|min:3',
        'country' => 'required|string',
        'gender'  => 'required|string',
        'payment' => 'required|array',
        'image'   => 'required|file|max:2048',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'Validation failed.',
            'errors'  => $validator->errors()
        ], 422);
    }

    $path = $request->file('image')->store('picture', 'public');

    $customer = Customer::create([
        'name'    => $request->name,
        'country' => $request->country,
        'gender'  => $request->gender,
        'image'   => $path,
        'payment' => $request->payment,
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Customer created successfully.',
        'data'    => new CustomerResource($customer)
    ], 201);
}
```

---

## API Resource — Response Format Control

### Kyun Chahiye

```
Bina Resource ke response mein aata hai:
- deleted_at → sensitive, client ko nahi chahiye
- image → sirf path, full URL nahi
- created_at → ajeeb format

Resource se ye control milta hai:
- kaunsi field jaayegi
- kaunsa format mein jaayegi
```

### Command

```bash
php artisan make:resource CustomerResource
# File banti hai: app/Http/Resources/CustomerResource.php
```

### CustomerResource.php

```php
public function toArray(Request $request): array
{
    return [
        'id'         => $this->id,        // $this = model object
        'name'       => $this->name,
        'gender'     => $this->gender,
        'payment'    => $this->payment,
        'country'    => $this->country,
        'image'      => asset('storage/' . $this->image),  // full URL
        'created_at' => $this->created_at->format('d-m-Y'), // clean date
        // deleted_at — nahi likha → response mein nahi aayega
    ];
}
```

**`$this` vs `$request`:**
```
$this    → model object (database ka data) — Resource mein use karo
$request → HTTP request (form se aaya data) — Controller mein use karo
```

### Single vs Collection

```php
new CustomerResource($customer)              // single record ke liye
CustomerResource::collection($customers)    // multiple records ke liye
```

---

## Mistakes Jo Hoti Hain

| Galat | Sahi |
|---|---|
| `php artisan make:route` | Routes manually `api.php` mein likhte hain |
| `--table=customers` in migrate | `--table` sirf `make:migration` ke saath |
| Web controller import in api.php | `Api\CustomerController` import karo |
| `$request->id` in Resource | `$this->id` — Resource mein `$this` use hota hai |
| `asset('storage/', $this->image)` | `asset('storage/' . $this->image)` — dot se join |
| `$validator->fail()` | `$validator->fails()` — 's' lagta hai |
| `POST /customers/store` | `POST /customers` — URL mein action nahi likhte |

---

## Artisan Commands

```bash
php artisan install:api                      # api.php + sanctum setup
php artisan make:Controller Api/CustomerController  # Api folder mein controller
php artisan make:resource CustomerResource   # Resource file banana
php artisan route:list                       # saare routes dekho
```

---

## Postman Testing Tips

```
GET  /api/customers         → index test
POST /api/customers         → store test — form-data use karo

Header add karo agar $request->validate() use kar rahe ho:
Key:   Accept
Value: application/json
```

---

*Phase 2 Next: Sanctum Auth — Register, Login, Token, Protected Routes*