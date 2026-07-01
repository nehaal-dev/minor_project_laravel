
paginate(10)
- Page numbers dikhata hai — 1, 2, 3, 4...
- Total records count karta hai — COUNT(*) query chalti hai
- "Previous" aur "Next" ke saath specific page numbers bhi hote hain
- Thoda slow — extra COUNT query chahiye
simplePaginate(10)
- Sirf "Previous" aur "Next" button dikhata hai — page numbers nahi
- Total records COUNT nahi karta
- Fast — ek hi query chalti hai
- Large datasets ke liye better

Kab Kaunsa Use Karo
paginate()       → jab page numbers dikhane ho (normal use case)
simplePaginate() → jab data bahut zyada ho, speed chahiye, page numbers matter nahi