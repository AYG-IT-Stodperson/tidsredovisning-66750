# Tidapp IT-24 66750
Ett webbaserat system för tidsredovisning där användare kan hantera aktiviteter och uppgifter.
Projektet är byggt som en frontend-applikation med HTML, CSS och JavaScript och innehåller flera vyer för att skapa, redigera och visa aktiviteter och uppgifter.

Projektet är en skoluppgift och applikationen använder främst PHP för backendlogik tillsammans med HTML, CSS och JavaScript för frontend.

## Funktioner

* Visa aktiviteter
* Skapa och redigera aktiviteter
* Hantera uppgifter
* Enkel frontend-struktur med separata HTML-, CSS- och JavaScript-filer
* Förberedd struktur för API-integration via `/api`
* Dokumentationsmapp för projektmaterial

## Projektstruktur

```text
.
├── api/                  # API-relaterad kod eller mockdata
├── css/                  # CSS-filer
├── dokumentation/        # Dokumentation och projektmaterial
├── dummy/                # Testdata / dummydata
├── images/               # Bilder och resurser
├── js/                   # JavaScript-filer
├── aktivitet.html        # Sida för aktiviteter
├── editaktivitet.html    # Redigera aktivitet
├── edituppgift.html      # Redigera uppgift
├── index.html            # Startsida
├── uppgifter.html        # Sida för uppgifter
└── style tidapp.css      # Global styling
```

---

# Kom igång lokalt

## 1. Klona projektet

```bash
git clone https://github.com/AYG-IT-Stodperson/tidsredovisning-66750.git
```

Gå sedan in i projektmappen:

```bash
cd tidsredovisning-66750
```

---

## 2. Starta projektet lokalt

Projektet använder PHP och bör därför köras via en lokal PHP-server.

### Alternativ 1 – PHP:s inbyggda utvecklingsserver

Kontrollera först att PHP är installerat:

```bash
php -v
```

Starta sedan projektet:

```bash
php -S localhost:8000
```

Öppna därefter:

```text
http://localhost:8000
```

---

### Alternativ 2 – XAMPP / Laragon / MAMP

1. Installera exempelvis XAMPP
2. Lägg projektmappen i:

```text
htdocs/
```

3. Starta Apache
4. Öppna projektet i webbläsaren:

```text
http://localhost/tidsredovisning-66750
```

---

# Deployment

Eftersom projektet är en statisk webbapplikation kan det deployas på flera enkla hostingplattformar.

## Deploy via GitHub Pages

### 1. Push till GitHub

```bash
git add .
git commit -m "Deploy-ready"
git push origin main
```

### 2. Aktivera GitHub Pages

1. Gå till repositoryt på GitHub
2. Öppna:

```text
Settings → Pages
```

3. Under **Source**:

   * Välj `Deploy from a branch`
   * Branch: `main`
   * Folder: `/root`

4. Klicka på **Save**

Efter några minuter publiceras sidan på:

```text
https://AYG-IT-Stodperson.github.io/tidsredovisning-66750/
```

---

---

# Fortsatt utveckling

## Rekommenderad utvecklingsmiljö

För att fortsätta utveckla projektet rekommenderas följande verktyg:

* PHP 8+
* VS Code
* Git
* XAMPP, Laragon eller MAMP
* Google Chrome eller Firefox

Rekommenderade VS Code-tillägg:

* PHP Intelephense
* Live Server
* Prettier
* ESLint
* GitLens

---

## Installera projektet lokalt

### 1. Klona repositoryt

```bash
git clone https://github.com/AYG-IT-Stodperson/tidsredovisning-66750.git
```

Gå in i projektmappen:

```bash
cd tidsredovisning-66750
```

---

### 2. Installera och starta lokal server

#### Med PHP:s inbyggda server

Kontrollera att PHP är installerat:

```bash
php -v
```

Starta sedan projektet:

```bash
php -S localhost:8000
```

Öppna sedan projektet i webbläsaren:

```text
http://localhost:8000
```

---

#### Med XAMPP

1. Installera XAMPP
2. Flytta projektet till:

```text
C:/xampp/htdocs/
```

3. Starta Apache via XAMPP Control Panel
4. Öppna:

```text
http://localhost/tidsredovisning-66750
```

---

## Struktur för fortsatt utveckling

### Frontend

Frontend-filer ligger huvudsakligen i:

```text
/css
/js
/images
```

HTML- och PHP-filer ligger i projektets root.

Vid vidareutveckling rekommenderas att:

* Dela upp JavaScript i mindre moduler
* Separera API-anrop från UI-logik
* Samla återanvändbara komponenter i egna filer
* Strukturera CSS i separata filer per vy eller komponent

---

### Backend

PHP-kod och API-logik bör placeras i:

```text
/api
```

Rekommenderad vidare struktur:

```text
/api
├── controllers/
├── models/
├── services/
├── config/
└── routes/
```

Det gör projektet enklare att underhålla när fler funktioner läggs till.

---

## Databas (rekommenderad vidareutveckling)

Projektet kan vidareutvecklas med exempelvis:

* MySQL
* MariaDB
* SQLite

Exempel på databasfunktioner:

* Användare
* Tidrapporter
* Aktiviteter
* Uppgifter
* Roller och behörigheter

---

## Git-workflow

### Skapa ny feature-branch

```bash
git checkout -b feature/min-funktion
```

### Hämta senaste ändringar

```bash
git pull origin main
```

### Commita ändringar

```bash
git add .
git commit -m "Beskrivning av ändring"
```

### Push till GitHub

```bash
git push origin feature/min-funktion
```

Skapa därefter en Pull Request.

---

## Felsökning

### PHP fungerar inte

Kontrollera:

```bash
php -v
```

Om kommandot inte fungerar behöver PHP installeras eller läggas till i PATH.

---

### Apache startar inte i XAMPP

Vanliga orsaker:

* Port 80 används redan
* IIS körs i bakgrunden
* Skype eller Docker använder samma portar

Lösning:

* Byt Apache-port till exempelvis 8080
* Starta sedan via:

```text
http://localhost:8080
```

---

# Förslag på vidareutveckling

* Backend med autentisering
* Databas för lagring av tidrapporter
* Inloggningssystem
* Dashboard med statistik
* Export till PDF eller Excel
* Responsiv mobilanpassning
* Testning med Jest eller Cypress
* CI/CD via GitHub Actions

---

# Teknologier

Projektet använder:

* PHP
* HTML5
* CSS3
* JavaScript
* Git & GitHub

---

# Licens

Projektet är licensierat under GNU GPL v3.

Se filen `LICENSE` för mer information.

---

# Repository

[GitHub Repository](https://github.com/AYG-IT-Stodperson/tidsredovisning-66750?utm_source=chatgpt.com)
