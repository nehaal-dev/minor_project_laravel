# Laravel CRUD — Customer Module (Practice Notes)
> Nehal's scratch-built CRUD — flow pehle, code baad mein

---

## Project Setup

```bash
composer create-project laravel/laravel revision
cd revision
php artisan serve
php artisan storage:link  # symlink for file uploads
```

---

## Folder Structure (Important Files)

```
app/
├── Http/Controllers/CustomerController.php
├── Models/Customer.php
database/migrations/xxxx_create_customers_table.php
resources/views/customers/
    ├── index.blade.php
    ├── create.blade.php
    └── edit.blade.php
routes/web.php
storage/app/public/profile/   ← actual files yahan hain
public/storage/                ← symlink — browser yahan se access karta hai
```

---

## Migration

### Socho Pehle
```
1. Table ka naam — plural, lowercase (customers)
2. Har field ka data type decide karo:
   - Short text → string
   - Long text → text
   - Multiple values (checkbox) → json
3. Koi field unique honi chahiye? (email jaisi cheez — gender nahi)
4. Timestamps hamesha rakho
```

### Code
```php
Schema::create('customers', function (Blueprint $table) {
    $table->id();
    $table->string('name', 255);
    $table->string('gender', 20);
    $table->json('payment');        // array store hoga — ["Cash","Online"]
    $table->string('country', 100);
    $table->string('profile', 255); // sirf filename/path store hoga
    $table->timestamps();
});
```

### Key Learnings
- `string()` → VARCHAR — short text (naam, email, country)
- `text()` → TEXT — long content (bio, description, paragraphs)
- `json()` → JSON column — array/object store karne ke liye
- `unique()` → sirf wahan lagao jahan value genuinely unique ho (email, username) — gender pe mat lagao
- `migrate:fresh` → sab tables drop karke dobara banata hai (development mein use karo)
- `migrate:rollback` → last migration undo karta hai

---

## Model

### Socho Pehle
```
1. Kaunse columns form se directly aayenge? → $fillable mein daalo
2. Koi column special type hai? (json → array) → $casts mein daalo
3. Relationships hain kisi aur table se? → baad mein add karo
```

### Code
```php
class Customer extends Model
{
    protected $fillable = [
        'name', 'gender', 'payment', 'country', 'profile'
    ];

    protected $casts = [
        'payment' => 'array'
    ];
}
```

### Key Learnings
- `$fillable` → `create()` / `update()` se sirf ye columns pass honge — security ke liye
- Bina `$fillable` ke `create()` call karo → `MassAssignmentException` error
- `$casts` → database se fetch karte waqt auto type conversion
- `$guarded = []` → sab columns allow karo (opposite of fillable)
- `save()` vs `create()`:
  - `create([...])` → ek line mein insert — fillable zaruri
  - `new Model() + save()` → manually assign karo — fillable zaruri nahi

---

## Routes

### Socho Pehle
```
1. 7 standard actions chahiye: index, create, store, show, edit, update, destroy
2. Single record pe action → {parameter} chahiye route mein
3. Saari list pe action → parameter nahi chahiye
4. Route NAME plural rakho — convention hai (customers.index, not customer.index)
5. URL aur NAME alag hote hain — confuse mat ho
```

### Code
```php
Route::get('/customers',               [CustomerController::class, 'index'])  ->name('customers.index');
Route::get('/customers/create',        [CustomerController::class, 'create']) ->name('customers.create');
Route::post('/customers',              [CustomerController::class, 'store'])  ->name('customers.store');
Route::get('/customers/{customer}',    [CustomerController::class, 'show'])   ->name('customers.show');
Route::get('/customers/{customer}/edit',[CustomerController::class, 'edit'])  ->name('customers.edit');
Route::put('/customers/{customer}',    [CustomerController::class, 'update']) ->name('customers.update');
Route::delete('/customers/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy');
```

### Key Learnings
- `/` sirf homepage ke liye — resource ka URL descriptive hona chahiye `/customers`
- Route NAME aur URL alag hote hain:
  - URL → browser mein daalo: `127.0.0.1:8000/customers`
  - Name → blade mein use karo: `route('customers.index')`
- `->name()` use karo — URL change hone pe blade files nahi todna padega
- Debug tip: `php artisan route:list` → saare registered routes dekho
- 404 aaye → pehle route:list check karo

---

## Controller

### `index()` — List Dikhana

**Socho Pehle:** Saara data chahiye, koi specific id nahi. Latest pehle dikhao.

```php
public function index()
{
    $customer_data = Customer::latest()->get();
    return view('customers.index', compact('customer_data'));
}
```

---

### `create()` — Form Dikhana

**Socho Pehle:** Yahan koi logic nahi — sirf form return karna hai.

```php
public function create()
{
    return view('customers.create');
}
```

---

### `store()` — Naya Data Save Karna

**Socho Pehle:**
```
1. Pehle validate karo — bina validate kiye DB tak data jaane hi mat do
2. Phir file upload karo (validation ke BAAD, create() se PEHLE)
3. Phir Customer::create() se save karo
4. Redirect with success message
```

```php
public function store(Request $request)
{
    $request->validate([
        'name'    => 'required|string|min:3|max:255',
        'gender'  => 'required|string|max:10',
        'payment' => 'required|array',
        'country' => 'required|string|min:3|max:100',
        'image'   => 'required|file|image|max:2048',
    ]);

    $path = $request->file('image')->store('profile', 'public');

    Customer::create([
        'name'    => $request->name,
        'gender'  => $request->gender,
        'payment' => $request->payment,   // $casts handle karega array→json
        'country' => $request->country,
        'profile' => $path,
    ]);

    return redirect()->route('customers.index')->with('success', 'Customer created!');
}
```

---

### `edit()` — Purana Data Form Mein Bharna

**Socho Pehle:** Route Model Binding use karo — manually `find()` likhne ki zarurat nahi.

```php
public function edit(Customer $customer)
{
    return view('customers.edit', compact('customer'));
}
```

---

### `update()` — Data Update Karna

**Socho Pehle (ye sabse tricky tha — order yaad rakho):**
```
1. Validate karo — image yahan 'nullable' hai (store() mein 'required' tha)
2. Basic fields ka $data array banao (name, gender, payment, country)
3. Check karo image aayi hai ya nahi — $request->hasFile('image')
   Agar aayi hai:
     a. Purani image delete karo (Storage::disk('public')->delete())
     b. Nayi image store karo
     c. $data array mein profile path add karo
   Agar nahi aayi:
     - $data array mein profile mat daalo — purani image as-is rahegi
4. $customer->update($data) — saara data ek saath save
5. Redirect with success
```

```php
public function update(Request $request, Customer $customer)
{
    $request->validate([
        'name'    => 'required|string|min:3|max:255',
        'gender'  => 'required|string',
        'payment' => 'required|array',
        'country' => 'required|string',
        'image'   => 'nullable|file|image|max:2048',
    ]);

    $data = [
        'name'    => $request->name,
        'gender'  => $request->gender,
        'payment' => $request->payment,
        'country' => $request->country,
    ];

    if ($request->hasFile('image')) {
        Storage::disk('public')->delete($customer->profile);
        $data['profile'] = $request->file('image')->store('profile', 'public');
    }

    $customer->update($data);

    return redirect()->route('customers.index')->with('success', 'Customer updated!');
}
```

---

### `destroy()` — Delete Karna

**Socho Pehle — order important hai:**
```
1. PEHLE image delete karo storage se (jab tak $customer object exist karta hai)
2. PHIR database row delete karo
   (galat order: row pehle delete kiya → $customer->profile null ho jaata
    → image delete nahi ho payegi)
3. Redirect with success
```

```php
public function destroy(Customer $customer)
{
    Storage::disk('public')->delete($customer->profile);
    $customer->delete();
    return redirect()->route('customers.index')->with('success', 'Customer deleted!');
}
```

---

## Delete — Full Mental Flow (Image + DB Record)

```
1. User delete button click → DELETE request form se jaati hai
2. Route hit hota hai → destroy() method call hota hai
3. Route Model Binding → URL ki id se Laravel customer object inject kar deta hai
   /customers/5 → Customer id=5 automatically $customer mein
4. $customer->profile → database column ki VALUE milti hai (column NAME nahi)
   example: "profile/abc.jpg"
5. Storage::disk('public') → kaunsi disk/config use karni hai batata hai
   ('public' disk internally storage/app/public/ ko point karta hai)
6. delete($customer->profile) → actual file path chahiye, column naam nahi
7. $customer->delete() → DB row delete (ya soft delete agar trait lagi hai)
8. redirect()->route(...) → named route ko real URL mein resolve karta hai
```

### Mistakes Jo Yahan Hoti Hain
| Galat Sochna | Sahi Samajh |
|---|---|
| `disk('picture')` | `disk()` filesystem CONFIG naam leta hai ('public', 'local', 's3') — column naam nahi |
| `delete('image')` | `delete()` ko actual file PATH chahiye — `$customer->profile` jaisa value, hardcoded string nahi |
| `'customer'`, `'image'` hardcode karna | `$customer->profile` — dynamically model se value lo |
| `use App\Http\Controllers\Storage;` | `use Illuminate\Support\Facades\Storage;` — facade framework namespace se import hota hai |
| `redirect('customers.index')` | `redirect()->route('customers.index')` — bina `->route()` ke ye literal URL `/customers.index` bana deta hai, 404 aata hai |

---

## Blade Tips

### Socho Pehle
```
- Array field (payment) → direct echo nahi hoga, loop ya implode chahiye
- Edit form mein existing value pre-fill karna hai → checked/selected condition
- DELETE/PUT browser support nahi karta directly → @method() spoofing chahiye
- Image dikhane ke liye asset() + storage path chahiye
```

### Code
```blade
{{-- Success message --}}
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

{{-- Image display --}}
<img src="{{ asset('storage/' . $customer->profile) }}" width="50">

{{-- Payment array display --}}
@foreach($customer->payment as $p)
    <span class="badge badge-info">{{ $p }}</span>
@endforeach

{{-- Radio checked --}}
<input type="radio" name="gender" value="Male"
    {{ $customer->gender == 'Male' ? 'checked' : '' }}>

{{-- Checkbox checked --}}
<input type="checkbox" name="payment[]" value="Cash"
    {{ in_array('Cash', $customer->payment) ? 'checked' : '' }}>

{{-- Select selected --}}
<option value="India" {{ $customer->country == 'India' ? 'selected' : '' }}>India</option>

{{-- Delete form --}}
<form method="POST" action="{{ route('customers.destroy', $customer->id) }}">
    @csrf
    @method('DELETE')
    <button type="submit">Delete</button>
</form>

{{-- Edit form --}}
<form method="POST" action="{{ route('customers.update', $customer->id) }}"
    enctype="multipart/form-data">
    @csrf
    @method('PUT')
</form>
```

### Key Learnings
- `asset('storage/...')` → public URL banata hai browser ke liye
- `storage:link` → `public/storage` ko `storage/app/public` se connect karta hai
- File input mein `value` nahi hota — current image alag `<img>` tag se show karo
- DELETE/PUT routes ke liye `@method()` directive zaruri hai — HTML forms sirf GET/POST support karte hain
- `@csrf` har POST/PUT/DELETE form mein mandatory hai

---

## Storage — Important Concept

```
Actual file:    storage/app/public/profile/photo.jpg
Symlink:        public/storage/ → shortcut to storage/app/public/
Browser URL:    yourdomain.com/storage/profile/photo.jpg

Browser directly storage/ folder access nahi kar sakta — security reason
Sirf public/ folder web server expose karta hai
storage:link sirf public/storage ka shortcut banata hai
```

---

## Common Mistakes Log

| Mistake | Galat | Sahi |
|---|---|---|
| Route method | `->route('name')` | `->name('name')` |
| fillable | method banaya | protected property hai |
| File upload | `create()` ke andar | pehle store, phir create |
| Update method | `Customer::update()` | `$customer->update()` |
| Delete route | GET request | POST + @method('DELETE') |
| Payment display | direct echo | loop ya implode |
| File nullable | required in update | nullable in update |
| storage:link | skip kiya | zaruri hai — warna image nahi dikhi |
| disk() | column naam diya | disk config naam ('public') |
| delete() | hardcoded string diya | dynamic `$customer->profile` diya |
| Storage import | Controllers namespace se | Illuminate\Support\Facades se |
| redirect | sirf `redirect('name')` | `redirect()->route('name')` |

---

## Artisan Commands Cheatsheet

```bash
php artisan serve                   # server start
php artisan make:model Customer -mc # model + migration + controller
php artisan migrate                 # new migrations run karo
php artisan migrate:fresh           # sab drop, fresh start
php artisan migrate:rollback        # last migration undo
php artisan route:list              # saare routes dekho — 404 debug
php artisan storage:link            # public/storage symlink banao
php artisan cache:clear             # cache clear
```

---

*Built from scratch — blank file, no copy paste, no tutorial*
*Flow pehle socho, code baad mein likho — yehi habit banani hai*
*Every mistake logged = real learning*
