# Finaz Kundenverwaltung

Willkommen!  

Ein Kundenverwaltungssystem mit **Vue3-Frontend (Quasar Framework)** , **Laravel-Backend** und **Docker**

---

## 📦 Clone Repository

```bash
git clone https://github.com/roshanzadeh1994/finaz-kundenverwaltung.git
```

## 🛠️ Voraussetzungen

- [Docker](https://www.docker.com/)
- [Docker Compose](https://docs.docker.com/compose/)

---

## ⚙️ Installation & Start


- git clone https://github.com/roshanzadeh1994/finaz-kundenverwaltung.git
- cd finaz-kundenverwaltung
- cp backend/.env.example backend/.env
- (entrypoint.sh) von CRLF auf LF umstellen (falls notwendig) 
- docker-compose up --build
- Bei Fehler (Crash) folgende Befehle ausführen:
    docker compose down --volumes
    docker compose build --no-cache
    docker compose up

- http://localhost:8080

## 🧪 Tests & Datenbank

- Datenbank: MySQL (wird automatisch im Container konfiguriert)
- Migrationen werden beim Start automatisch ausgeführt




