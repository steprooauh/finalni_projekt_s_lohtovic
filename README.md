# 🚴 Finální projekt 2. B

Webová aplikace pro správu a zobrazování cyklistických závodů v jednotlivých letech. Projekt umožňuje prohlížení závodů, filtrování, přidávání nových závodů, editaci existujících záznamů a jejich archivaci.

## Funkce

### Veřejná část
- Přehled dostupných ročníků závodů
- Zobrazení počtu závodů v jednotlivých letech
- Detail závodu
- Zobrazení:
  - názvu závodu
  - loga závodu
  - data konání
  - celkové délky
  - převýšení
  - zařazení do UCI Tour

### Administrace
- Přidání nového závodu
- Editace existujícího závodu
- Archivace (skrytí) závodu
- Nahrávání log závodů
- Správa UCI kategorií
- Zadávání popisu závodu pomocí WYSIWYG editoru

### Filtrování
- Zobrazení pouze uživatelem vytvořených závodů
- Vyhledávání závodů pomocí našeptávače

---

## Použité technologie

### Backend
- PHP 8+
- CodeIgniter 4

### Frontend
- Bootstrap 5
- Font Awesome
- Summernote
- Tom Select
- JavaScript

### Databáze
- MySQL / MariaDB

---

## Struktura projektu

```text
app/
├── Controllers/
├── Models/
├── Views/
│   ├── Layout/
│   ├── Race/
│   ├── Years/
│   └── Home/
public/
├── img/
│   └── logos/
└── node_modules/
```

---

## Hlavní obrazovky

### Domovská stránka
Zobrazuje všechny dostupné roky závodů formou karet.

### Přehled závodů
Obsahuje:

- název závodu
- logo
- stát
- kategorii UCI
- délku závodu
- převýšení
- datum konání

### Detail závodu
Obsahuje podrobné informace o vybraném závodu:

- název
- logo
- délku
- převýšení
- datum konání

---

## Přidání závodu

Administrátor může zadat:

| Pole | Popis |
|--------|--------|
| Název | Název závodu |
| Rok | Rok konání |
| UCI Tour | Kategorie závodu |
| Celková délka | Délka v km |
| Převýšení | Celkové převýšení v m |
| Logo | Obrázek závodu |
| Bio | Podrobný popis |

---

## Editace závodu

Editace umožňuje:

- změnit název
- změnit kategorii UCI
- nahrát nové logo

Výběr závodu probíhá pomocí našeptávače.

---

## Archivace závodu

Místo fyzického odstranění je závod:

- skryt z veřejného přehledu
- zachován v databázi
- připraven pro případné obnovení

---

## Uživatelské rozhraní

Projekt využívá:

- responzivní design
- Bootstrap karty
- modální okna
- formuláře s validací
- ikony Font Awesome

---

## Bezpečnost

Projekt obsahuje:

- CSRF ochranu formulářů
- validaci vstupů
- escapování výstupů
- kontrolu oprávnění uživatelů

---

## Vysvětlení JavaScriptu v zavod.php

### Inicializace Summernote editoru

```javascript
document.addEventListener("DOMContentLoaded", function() {
```

Po načtení stránky se spustí JavaScript a připraví všechny potřebné funkce.

---

```javascript
const pridatModal = document.getElementById('pridat');
```

Najde modální okno určené pro přidávání nového závodu.

---

```javascript
pridatModal.addEventListener('shown.bs.modal', function () {
```

Po otevření modálního okna se spustí editor Summernote.

---

```javascript
$('.summernote').summernote({
```

Vytvoří textový editor umožňující:

- tučné písmo
- podtržení
- seznamy
- tabulky
- editaci HTML

Editor slouží pro zadávání informací o závodu.

---

```javascript
pridatModal.addEventListener('hidden.bs.modal', function () {
    $('.summernote').summernote('destroy');
});
```

Po zavření okna se editor odstraní z paměti, aby se při dalším otevření nevytvářel znovu přes již existující instanci.

---

## Vyhledávání závodu pro editaci

```javascript
const searchInput = document.getElementById('zavod_search_input');
```

Načte vstupní pole, do kterého uživatel zadává název závodu.

---

```javascript
searchInput.addEventListener('input', function() {
```

Po každém napsaném znaku proběhne kontrola, zda existuje odpovídající závod.

---

```javascript
options.forEach(option => {
```

Projde všechny možnosti v seznamu závodů.

---

```javascript
if (option.value === inputValue)
```

Porovnává zadaný text s názvem závodu.

---

```javascript
hiddenIdInput.value = foundId;
```

Uloží ID nalezeného závodu do skrytého pole formuláře.

To je důležité, protože databáze pracuje s ID, ne s názvem.

---

```javascript
editWrapper.classList.remove('d-none');
```

Zobrazí formulář pro editaci.

---

```javascript
editWrapper.classList.add('d-none');
```

Pokud závod nebyl nalezen, formulář se opět skryje.

---

## Vyhledávání závodu pro odstranění

```javascript
const deleteInput = document.getElementById('zavod_delete_input');
```

Načte pole určené pro vyhledání závodu k odstranění.

---

```javascript
deleteTriggerBtn.removeAttribute('disabled');
```

Po nalezení závodu aktivuje tlačítko pro odstranění.

---

```javascript
deleteAlert.classList.remove('d-none');
```

Zobrazí upozornění, že závod bude pouze archivován.

---

## Dynamické vytvoření potvrzovacího okna

```javascript
deleteTriggerBtn.addEventListener('click', function() {
```

Po kliknutí na tlačítko odstranění se vytvoří potvrzovací okno.

---

```javascript
document.getElementById('delete_modal_container').innerHTML = `
```

JavaScript dynamicky vytvoří HTML kód Bootstrap modalu.

Výhoda:

- není nutné mít pro každý závod vlastní modal,
- modal vznikne pouze v případě potřeby.

---

```javascript
new bootstrap.Modal(document.getElementById(modalId)).show();
```

Zobrazí vytvořené potvrzovací okno.

---

## Filtrování „Pouze mnou vytvořené“

```javascript
const filterCheckbox = document.getElementById('btnCheckMoje');
```

Načte přepínač filtru.

---

```javascript
filterCheckbox.addEventListener('change', function() {
```

Po změně stavu checkboxu se spustí filtrování.

---

```javascript
currentUrl.searchParams.set('moje', '1');
```

Do URL přidá parametr:

```
?moje=1
```

Server následně zobrazí pouze závody vytvořené přihlášeným uživatelem.

---

```javascript
currentUrl.searchParams.delete('moje');
```

Parametr odstraní a zobrazí všechny závody.

---

```javascript
window.location.href = currentUrl.toString();
```

Obnoví stránku s novým filtrem.

---
## Shrnutí

JavaScript v souboru `zavod.php` zajišťuje:

- inicializaci textového editoru Summernote,
- dynamické otevírání a zavírání modálních oken,
- vyhledávání závodů pomocí našeptávače,
- automatické vyplňování formulářů,
- potvrzení odstranění závodu,
- filtrování závodů podle autora,
- zlepšení uživatelské přívětivosti aplikace bez nutnosti ručního obnovování formulářů.

---

## Instalace

### 1. Klonování repozitáře

```bash
git clone https://github.com/stepprok/finalni_projekt_s_lohtovic
```

### 2. Instalace závislostí

```bash
composer i
npm i
```

### 3. Nastavení databáze

V souboru `.env` nastavte:

```env
database.default.hostname = localhost
database.default.database = cycling
database.default.username = root
database.default.password =
```

### 4. Migrace databáze

```bash
php spark migrate
```

### 5. Spuštění projektu

```bash
php spark serve
```

---

## Autoři

**Štěpán Prokop**

**Jiří Lhota**

---

## Licence

Projekt byl vytvořen pro studijní účely.
