Fuvarozó rendszer Laravelben
============================

## Telepítés és indítás

1. **Környezeti fájl másolása**
   ```bash
   cp .env.example .env
   ```
   Windows PowerShell alatt:
   ```powershell
   copy .env.example .env
   ```

2. **Összetevők telepítése**
   ```bash
   composer install
   npm install
   ```

3. **Alkalmazási kulcs generálása**
   ```bash
   php artisan key:generate
   ```

4. **Adatbázis beállítása**
   - Állítsd be az `.env` fájlban az adatbázis kapcsolatot (SQLite esetén a `DB_CONNECTION=sqlite`, és a `DB_DATABASE` mutasson a `database/database.sqlite` fájlra).
   - Ha SQLite-ot használsz és a fájl még nem létezik:
     ```bash
     touch database/database.sqlite
     ```

5. **Migrációk és seeder futtatása**
   ```bash
   php artisan migrate --seed
   ```
   Ez létrehozza az admin (jelszó: `123qwe123`) és a mintafelhasználók adatait.

## Előre feltöltött felhasználók

- Admin: `admin@example.com` / `123qwe123`
- Fuvarozó: `peter.kovacs@example.com` / `123qwe123`
- Fuvarozó: `eszter.nagy@example.com` / `123qwe123`

    Illetve van pár előre felvett Fuvar is.

6. **Frontend build**
   - Fejlesztői mód:
     ```bash
     npm run dev
     ```
   - Vagy Vite build:
     ```bash
     npm run build
     ```

7. **Fejlesztői szerver indítása**
   ```bash
   php artisan serve
   ```
   Az alapértelmezett URL: `http://127.0.0.1:8000`

## Opcionális REST API végpontok

- `POST /api/jobs` – új fuvar létrehozása
- `PUT /api/jobs/{job}` – meglévő fuvar módosítása
- `PATCH /api/jobs/{job}/status` – fuvar státuszának frissítése