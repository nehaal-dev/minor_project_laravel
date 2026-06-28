# Soft Delete in Laravel

### Hard Delete:

$customer->delete();
- Row database se PERMANENTLY gone
- Wapas nahi mil sakta

### Soft Delete:

$customer->delete();
- Row database mein rehta hai
- Sirf 'deleted_at' column mein timestamp set ho jaata hai
- Query automatically is row ko hide kar deta hai
- Wapas la sakte ho restore() se

Real World Use Case
Hard delete  → Spam comment, temporary data
Soft delete  → User account, Orders, Important records
              (galti se delete ho jaye toh restore kar sako)

## What is Soft Delete?

Soft delete means data database se permanently remove nahi hota.

Laravel deleted_at column use karta h.

---

## Migration

```php
$table->softDeletes();
```

---

## Model

```php
use SoftDeletes;
```

---

## Important Methods

```php
User::withTrashed()->get();
User::onlyTrashed()->get();
User::restore();
User::forceDelete();
```

---
# Task — Customer Mein Soft Delete Add Karo
1. Naya migration banao — sirf 'deleted_at' column add karne ke liye
   (alter table — naya migration command use karo)

2. Customer Model mein SoftDeletes trait add karo
   — socho kahan likhna hai aur kya import karna hai

3. destroy() method — already hai, dekho kya change hota hai
   (hint: kuch nahi badalna padega, ya thoda?)

4. Naya method banao — restore()
   — soft deleted customer ko wapas active karo

5. Naya route add karo — restore ke liye

6. index() mein — socho, deleted customers dikhana hai ya nahi
   (agar dikhana ho toh withTrashed() use karna padega)

##  Flow  And solution 



## mistake

# Interview Question

Difference between:
- delete()
- forceDelete()