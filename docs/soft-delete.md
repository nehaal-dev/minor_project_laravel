# Soft Delete in Laravel

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

## Interview Question

Difference between:
- delete()
- forceDelete()