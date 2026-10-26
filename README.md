# LocaGen PRO

LocaGen PRO is a PHP and MySQL web app I built for translating game localization files with Google's Gemini API. After logging in, you upload a JSON language file, pick a target language, and the app sends the file to Gemini with instructions to translate only the values, keep the keys, and use natural gaming language instead of word-for-word output. The translated file is saved on the server and offered as a download. Every translation is logged, and admin users get a page that lists all logs.

- Other version: [LocaGen FREE](https://github.com/manyaksisko35/LocaGenFree) (Python desktop app, uses Google Translate)
- itch.io: [LocaGen PRO - Universal Game Localization Tool](https://hasan-alp-gungor.itch.io/locagen-pro-universal-game-localization-tool)
- Project page: [hasanalpgungor.com/en/projects/locagen](https://hasanalpgungor.com/en/projects/locagen)

## Features

- Login with username and password, session-based. `index.php` and `process.php` require a logged-in user.
- Two roles, `admin` and `user`. Admins are sent to the admin panel after login; normal users go to the translator.
- Upload a `.json` file and choose a target language: Turkish, English, German, Spanish, French or Italian.
- Files that are not `.json` or are not valid JSON are rejected with an error message.
- Translation is done by the `gemini-2.5-flash` model through the Gemini REST API, with JSON output mode turned on. The prompt tells the model to leave keys untouched and return only valid JSON.
- The request is sent with `fetch` in the background while a progress bar runs; the result appears on the same page with a download button.
- Output is saved to `uploads/translated_<timestamp>.json` (pretty-printed, UTF-8).
- Each successful translation adds a row to the `logs` table (user, original file name, target language, time).
- Admin panel (`admin.php`) shows all logs with the username, newest first. Non-admin users are redirected to the login page.
- Logout clears the session.

## Built with

- PHP (PDO for MySQL, cURL for the API call)
- MySQL / MariaDB
- Bootstrap 5.3 (loaded from jsDelivr) plus custom CSS
- Plain JavaScript (`fetch` + `FormData`)
- Google Gemini API (`gemini-2.5-flash`)

## Getting started

### Requirements

- PHP with the `pdo_mysql` and `curl` extensions. The SQL dump was exported from XAMPP (PHP 8.2.12, MariaDB 10.4.32), so a current XAMPP install is the easiest setup.
- MySQL or MariaDB
- A Gemini API key from [Google AI Studio](https://aistudio.google.com/)

### Setup

1. Copy the project into your web root, for example `C:\xampp\htdocs\locagen`.
2. Start Apache and MySQL from the XAMPP control panel.
3. Create a database named `locagen` (utf8mb4) and import `locagen.sql` into it, either in phpMyAdmin (Import tab) or from the command line:

   ```bash
   mysql -u root -p -e "CREATE DATABASE locagen CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci"
   mysql -u root -p locagen < locagen.sql
   ```

   The dump creates the `users` and `logs` tables, two demo accounts and some sample log rows.
4. Open `includes/db.php` and set the host, database name, username and password for your own MySQL user. Use your own password; do not commit real credentials.
5. Create a file named `.env` in the project root (next to `process.php`) with your API key:

   ```ini
   GEMINI_API_KEY=your_api_key_here
   ```

   `process.php` reads this file with `parse_ini_file`. Keep `.env` out of git (add it to `.gitignore`), and if you put the app on a public server, block web access to it.
6. Make sure PHP can write to the `uploads/` folder (it is created automatically if missing).
7. Open `http://localhost/locagen/login.php`.

### Demo accounts

`locagen.sql` creates two accounts, both with the password `123456`:

| Username   | Role  |
|------------|-------|
| `admin`    | admin |
| `dev_user` | user  |

Change these before using the app anywhere but your own machine. There is no sign-up page; users are added directly in the database. Passwords are stored as MD5 hashes, so a new user looks like this:

```sql
INSERT INTO users (username, password, role) VALUES ('alice', MD5('choose-a-password'), 'user');
```

## Example

Uploaded file (English):

```json
{
    "menu_start_game": "Start New Game",
    "item_potion_health": "Health Potion",
    "dialogue_guard_stop": "HALT! You cannot pass without permission."
}
```

Downloaded file with Turkish selected (wording comes from Gemini and can differ between runs):

```json
{
    "menu_start_game": "Yeni Oyuna Başla",
    "item_potion_health": "Can İksiri",
    "dialogue_guard_stop": "DUR! İznin olmadan öteye adım atamazsın."
}
```

A full sample output is in `uploads/translated_1780316955.json`.

## Türkçe

LocaGen PRO, oyun dil dosyalarını (JSON) Gemini API ile çeviren bir PHP + MySQL web uygulamasıdır. Kullanıcı giriş yaptıktan sonra dosyasını yükler, hedef dili seçer ve çevrilmiş dosyayı indirir; anahtarlar değişmez, sadece değerler çevrilir. Her çeviri veritabanına kaydedilir ve admin paneli tüm kayıtları listeler. Kurulum için `locagen.sql` dosyasını içe aktarmanız, `includes/db.php` içindeki bağlantı bilgilerini düzenlemeniz ve proje klasörüne `GEMINI_API_KEY` içeren bir `.env` dosyası koymanız gerekir.

Made by [Hasan Alp Güngör](https://hasanalpgungor.com/en)
