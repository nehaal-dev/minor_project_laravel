# Soft Delete in Laravel

## Concept (1 line)

Soft delete = row database mein rehta hai, sirf `deleted_at` timestamp set ho jaata hai. Query automatically deleted rows hide kar deta hai.

| | Hard Delete | Soft Delete |
|---|---|---|
| Row | Permanently gone | Rehta hai DB mein |
| Recoverable | Nahi | Haan — `restore()` |
| Use case | Spam, temp data | User, Orders, important records |

---

## Setup — 3 Steps

**1. Migration (existing table mein column add karna)**
```bash
php artisan make:migration add_deleted_at_to_customers_table --table=customers
```
```php
public function up(): void
{
    Schema::table('customers', function (Blueprint $table) {
        $table->softDeletes()->after('updated_at');
    });
}

public function down(): void
{
    Schema::table('customers', function (Blueprint $table) {
        $table->dropSoftDeletes();
    });
}
```

**2. Model**
```php
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;
}
```

**3. Done — `destroy()` automatically soft delete karega**
```php
$customer->delete();  // ab ye hard delete nahi karega — deleted_at set karega
```
Controller mein kuch change nahi karna padta — trait lagne se behavior badal jaata hai.

---

## Query Methods

```php
Customer::all();                 // sirf active — deleted hide
Customer::withTrashed()->get();  // active + deleted dono
Customer::onlyTrashed()->get();  // sirf deleted
```

**Internally jo ho raha hai:**
```sql
-- Customer::all() ka actual query:
SELECT * FROM customers WHERE deleted_at IS NULL
```

---

## Restore Flow

**Route — parameter pe dhyan do, model binding nahi chalega kyunki record deleted hai:**
```php
Route::get('/customer/{id}/restore', [CustomerController::class, 'restore'])->name('customers.restore');
```

**Controller — `onlyTrashed()` use karo, `Customer $customer` binding nahi:**
```php
public function restore($id)
{
    $customer = Customer::onlyTrashed()->findOrFail($id);
    $customer->restore();

    return redirect()->route('customers.index')->with('success', 'Customer restored!');
}
```

**Kyun normal Route Model Binding fail karta hai:**
`Customer $customer` binding internally `Customer::all()` jaisa hi filter laga deta hai (`deleted_at IS NULL`) — deleted record milega hi nahi, 404 aayega. Isliye manual `$id` lo aur `onlyTrashed()` se khud dhundo.

---

## "Deleted Customers" Page — Pura Flow

```
Route → /customers/deleted (no parameter — list chahiye, single record nahi)
   ↓
Controller → trashed() method → Customer::onlyTrashed()->get()
   ↓
View → trashed.blade.php → list + restore button har row pe
```

```php
// Route
Route::get('/customers/deleted', [CustomerController::class, 'trashed'])->name('customers.trashed');

// Controller
public function trashed()
{
    $deletedCustomers = Customer::onlyTrashed()->get();
    return view('customers.trashed', compact('deletedCustomers'));
}
```
```blade
{{-- Blade — variable naam controller wale compact() se EXACT match hona chahiye --}}
@foreach ($deletedCustomers as $c)
    <td>{{ $c->deleted_at->format('d-m-Y h:i A') }}</td>
    <td><a href="{{ route('customers.restore', $c->id) }}" class="btn btn-success">Restore</a></td>
@endforeach
```

**Rule:** Single record → route mein `{id}` parameter. Pura list → parameter nahi chahiye.

---

## Mistakes Jo Khud Ki

| Likha | Sahi | Kyun |
|---|---|---|
| `$table->string('deleted_at')` | `$table->softDeletes()` | deleted_at timestamp hota hai, string nahi |
| `use Database\Eloquent\softDelete;` | `use Illuminate\Database\Eloquent\SoftDeletes;` | Illuminate missing, naam case/spelling galat |
| `Customer::where('deleted_at')->all()` | `$customer->restore()` | restore() built-in method hai, manual query nahi chahiye |
| `restore(Customer $customer)` | `restore($id)` + `onlyTrashed()->findOrFail()` | deleted record pe normal binding fail karta hai |
| Active customer row pe restore button | Sirf trashed list mein restore button | Active customer ko restore karne ka logically matlab nahi |

---

## Interview Questions

**Q: Soft delete vs hard delete?**
Soft delete row ko DB mein rakhta hai, `deleted_at` set karta hai, query se automatically hide karta hai. Hard delete permanent hai.

**Q: `Customer::all()` ke baad deleted customers dikhenge?**
Nahi — `SoftDeletes` trait automatically `WHERE deleted_at IS NULL` add kar deta hai.

**Q: Soft delete unique validation ko kaise affect karta hai?**
Agar `email` unique hai, soft-deleted customer ka email row mein abhi bhi exist karta hai — naya customer same email se create nahi hoga. Fix: validation mein `whereNull('deleted_at')` add karo ya custom rule banao.

**Q: `forceDelete()` kya hai?**
Soft-deleted record ko permanently delete karta hai — recovery ka option khatam.
```php
$customer->forceDelete();
```

---

## Practice Checklist (bina dekhe karo)

- [ ] Migration likho `softDeletes()->after()` ke saath
- [ ] Model mein trait + import
- [ ] `destroy()` test karo — Tinker se count check
- [ ] `restore()` route + controller — `{id}` aur `onlyTrashed()`
- [ ] Trashed list page — alag route, alag controller method, alag blade
- [ ] `forceDelete()` bhi likho — permanent delete kab use hota hai