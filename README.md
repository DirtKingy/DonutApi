# Royal Donut Kitchen API

## Overzicht

Deze Laravel API beheert het menu van de Royal Donut Kitchen. Je kunt donuts opvragen, toevoegen en verwijderen via RESTful endpoints. De API ondersteunt sorteren op naam en beoordelingsscore.

---

## Features

- RESTful API voor donuts (`GET`, `POST`, `DELETE`)
- Validatie van invoer (unieke namen, seal_of_approval tussen 1 en 5, prijs positief)
- Sorteren van donuts via query parameters (`sort=name|approval`, `order=asc|desc`)
- Volledige test coverage met PHPUnit en database refresh
- Database seeding met voorbeelddata van donuts
- Routes onder `/api/donuts`

---

## Installatie

1. Clone de repository:

   ```bash
   git clone https://github.com/jouwgebruikersnaam/royal-donut-kitchen-api.git
   cd royal-donut-kitchen-api
   ```

2. Installeer dependencies via Composer:

   ```bash
   composer install
   ```

3. Kopieer `.env.example` naar `.env` en configureer database settings.

4. Maak app key aan:

   ```bash
   php artisan key:generate
   ```

5. Voer migraties en seeders uit:

   ```bash
   php artisan migrate --seed
   ```

6. Start de Laravel development server:

   ```bash
   php artisan serve
   ```

---

## API Endpoints

### 1. Haal alle donuts op

```http
GET /api/donuts
```

**Query parameters (optioneel):**

- `sort`: `name` of `approval` (beoordeling)
- `order`: `asc` of `desc` (standaard `asc`)

**Voorbeeld:**

```
GET /api/donuts?sort=name&order=asc
```

**Response:**

```json
[
  {
    "id": 1,
    "name": "Moonlit Meringue",
    "seal_of_approval": 4,
    "price": 8.0,
    "created_at": "2025-06-12T10:13:15",
    "updated_at": "2025-06-12T10:13:15"
  }
]
```

---

### 2. Voeg een donut toe

```http
POST /api/donuts
Content-Type: application/json
```

**Payload:**

```json
{
  "name": "Nieuwe Donut",
  "seal_of_approval": 3,
  "price": 7.5
}
```

**Validatie:**

- `name`: verplicht, uniek, max 255 tekens
- `seal_of_approval`: integer tussen 1 en 5
- `price`: positief getal

**Response (201 Created):**

```json
{
  "id": 16,
  "name": "Nieuwe Donut",
  "seal_of_approval": 3,
  "price": 7.5,
  "created_at": "...",
  "updated_at": "..."
}
```

---

### 3. Verwijder een donut

```http
DELETE /api/donuts/{id}
```

**Response bij succes:**

```json
{
  "message": "Donut deleted"
}
```

**Response bij niet gevonden:**

```json
{
  "message": "Donut not found"
}
```

---

## Testing

De tests zijn geschreven met PHPUnit en kunnen worden uitgevoerd met:

```bash
php artisan test
```

Er zijn tests voor:

- Donut lijst ophalen, met en zonder sortering
- Geldige donuts toevoegen
- Ongeldige invoer afhandelen
- Donuts verwijderen

---

## Project structuur

- **app/Models/DonutApi.php** — Eloquent model met factory
- **app/Http/Controllers/DonutApiController.php** — API controller met `index`, `store`, `destroy`
- **database/migrations/** — Migratie voor donuts tabel
- **database/seeders/DonutsTableSeeder.php** — Seeder met voorbeelddonuts
- **routes/api.php** — API routes onder `/api/donuts`
- **tests/Feature/DonutApiTest.php** — Integratietests

---

**Veel plezier met de Royal Donut Kitchen API! 🍩**
