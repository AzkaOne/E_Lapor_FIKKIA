# Figma Teal/Emerald Visual Redesign Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Transform the visual theme and component styling of E-LAPOR FIKKIA across all pages to precisely match the Figma file design (`5xnSe5GPAns0xkMJFenIdL`), applying the authentic Figma Teal/Emerald color palette (`#0A8080`, `#0C3934`, `#1A5B51`, `#059669`, `#ECFDF5`, `#CCFBF1`, `#F0FDFA`), badge overlays, notice cards, sidebars, and stat widgets.

**Architecture:** Update the master Tailwind color presets, layouts (`app.blade.php`, `guest.blade.php`), navigation components (`navbar.blade.php`, `sidebar.blade.php`), public landing page (`welcome.blade.php`), auth screens, tracking portal, and role dashboards (Pelapor, Kabag, Kasubag, Petugas) to extract and mirror Figma design tokens from Page 1 (HAL UTAMA), Page 2 (Selection & Tracking), and Page 3 (Dasbor E-Lapor UNAIR Modern).

**Tech Stack:** Laravel 12/13 Blade, Tailwind CSS, Alpine.js, Plus Jakarta Sans, SVG icons, PHPUnit Feature Tests.

**Spec:** [`docs/superpowers/specs/2026-10-31-e-lapor-system-design.md`](file:///d:/ALL%20PROJECT/E_LAPOR/docs/superpowers/specs/2026-10-31-e-lapor-system-design.md) & Figma File `5xnSe5GPAns0xkMJFenIdL`.

## Global Constraints

- **Color Tokens:**
  - Primary Action / Buttons: `#0A8080` (Teal) / hover `#086666`
  - Sidebar Dark Surface: `#0C3934` (Deep Forest Teal)
  - Sidebar Active Link Surface: `#1A5B51` (Pine Emerald)
  - Sidebar Submenu & Subtext: `#CCFBF1`, `rgba(153,246,228,0.9)`
  - Card Subtle Borders: `border-[#CCFBF1]` / `border-[rgba(153,246,228,0.6)]`
  - Notice Box Surface: `bg-[#ECFDF5]` / `border-[rgba(167,243,208,0.6)]` / text `#064E3B`
  - Positive / Checkmark Accent: `#059669` / `#10B981`
  - Background Neutral: `#F8FAFC` / `#F1F5F9`
  - Text Primary: `#0F172A` / `#1E293B`, Muted `#64748B`
- **Typography:** `Plus Jakarta Sans` across all headings and body text.
- **Components & Modals:** Preserve existing form inputs, CSRF tokens, Alpine.js dialogs, and route endpoints intact.
- **Test Integrity:** All 37 existing feature test assertions must continue to pass 100%.

## Review Focus

1. Public Landing Page (`/`): Features two cards (Pelapor & Whistleblowing) with authentic Figma `#0A8080` buttons, `#ECFDF5` notice containers with shield checkmarks, and institutional FIKKIA header.
2. Sidebar Navigation: Deep forest `#0C3934` background with active state `#1A5B51`, university seal badge, and light teal submenus (`#CCFBF1`).
3. Topbar & Header: Greets the active user role with proper title, notification icon, profile badge, and live date/clock pill.
4. Stat & Metric Cards: White rounded cards with light tint icon boxes (`#EFF6FF`, `#F0FDFA`, `#FFFBEB`, `#FFF1F2`) and directional trend percentages (`#059669`, `#D97706`, `#E11D48`).
5. Action Buttons & Form Controls: Form buttons consistently use `#0A8080` with hover states, replacing outdated navy blue classes.

---

### Task 1: Update Design Tokens & Master Layouts

**Files:**
- Modify: `design-system/e-lapor-fikkia/MASTER.md`
- Modify: `resources/views/layouts/app.blade.php`
- Modify: `resources/views/layouts/guest.blade.php`
- Modify: `resources/views/components/navbar.blade.php`
- Modify: `resources/views/components/sidebar.blade.php`
- Test: `tests/Feature/FigmaThemeVisualTest.php`

**Interfaces:**
- Consumes: Tailwind CDN configuration with customized theme colors:
  - `teal-brand`: `#0A8080`
  - `teal-dark`: `#0C3934`
  - `teal-active`: `#1A5B51`
  - `teal-light`: `#CCFBF1`
  - `emerald-surface`: `#ECFDF5`
- Produces: Master layouts with authenticated and guest wrappers rendered in authentic Figma Teal/Emerald colors.

- [ ] **Step 1: Write the failing visual test**

```php
// tests/Feature/FigmaThemeVisualTest.php
namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\SubDepartment;

class FigmaThemeVisualTest extends TestCase
{
    use RefreshDatabase;

    public function test_landing_page_renders_figma_teal_palette(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('#0a8080', false);
        $response->assertSee('#ecfdf5', false);
    }

    public function test_authenticated_sidebar_renders_figma_dark_teal_sidebar(): void
    {
        $sarpras = SubDepartment::create(['name' => 'Sarpras', 'code' => 'SARPRAS']);
        $kabag = User::factory()->create(['role' => 'kabag']);

        $response = $this->actingAs($kabag)->get('/kabag/dashboard');
        $response->assertStatus(200);
        $response->assertSee('#0c3934', false);
        $response->assertSee('#1a5b51', false);
    }
}
```

- [ ] **Step 2: Run test to verify it fails**

Run: `php ./vendor/bin/phpunit tests/Feature/FigmaThemeVisualTest.php`
Expected: FAIL (does not see `#0a8080` or `#0c3934` in response output).

- [ ] **Step 3: Update `MASTER.md`, master layouts, `navbar.blade.php`, and `sidebar.blade.php`**
  - Inject Tailwind theme configuration in `app.blade.php` and `guest.blade.php` mapping `teal-brand` `#0A8080`, `teal-dark` `#0C3934`, `teal-active` `#1A5B51`, `teal-light` `#CCFBF1`, `emerald-surface` `#ECFDF5`.
  - Update `sidebar.blade.php` background to `bg-[#0c3934]`, active items to `bg-[#1a5b51]`, submenus to `border-[rgba(15,118,110,0.5)]` and `text-[#ccfbf1]`.
  - Update `navbar.blade.php` to use `#0c3934` / `#0a8080` accents and institutional styling.

- [ ] **Step 4: Run test to verify it passes**

Run: `php ./vendor/bin/phpunit tests/Feature/FigmaThemeVisualTest.php`
Expected: PASS.

- [ ] **Step 5: Commit**

```bash
git add design-system resources/views/layouts resources/views/components tests/Feature/FigmaThemeVisualTest.php
git commit -m "style: apply figma teal and emerald master tokens to layouts and navigation"
```

---

### Task 2: Redesign Public Landing Page & Auth Screens

**Files:**
- Modify: `resources/views/welcome.blade.php`
- Modify: `resources/views/auth/login.blade.php`
- Modify: `resources/views/auth/register.blade.php`
- Test: `tests/Feature/FigmaThemeVisualTest.php`

**Interfaces:**
- Consumes: Figma Page 1 (`0:1` / `29:25` HAL UTAMA) design elements:
  - Header with FIKKIA emblem and banner
  - Pill tag `E-Lapor FIKKIA` with green dot indicator
  - Card 1: Laporan Pelayanan (Card border `border-[rgba(153,246,228,0.6)]`, `#0A8080` button, `#ECFDF5` notice box with shield checkmarks, paper document illustration)
  - Card 2: Laporan Anonim / Whistleblowing (Matching card geometry, action button, shield features)
  - Footer with copyright and slogan "Bersama Mewujudkan Pelayanan yang Lebih Baik"
- Produces: Visually faithful welcome and auth pages with primary `#0A8080` action buttons and emerald tints.

- [ ] **Step 1: Add assertions to `tests/Feature/FigmaThemeVisualTest.php`**

```php
    public function test_auth_pages_render_figma_teal_action_buttons(): void
    {
        $login = $this->get('/login');
        $login->assertStatus(200);
        $login->assertSee('#0a8080', false);

        $register = $this->get('/register');
        $register->assertStatus(200);
        $register->assertSee('#0a8080', false);
    }
```

- [ ] **Step 2: Run test to verify it fails**

Run: `php ./vendor/bin/phpunit tests/Feature/FigmaThemeVisualTest.php`
Expected: FAIL.

- [ ] **Step 3: Redesign `welcome.blade.php`, `login.blade.php`, and `register.blade.php`**
  - Replace blue and navy hexes with Figma `#0A8080`, `#0C3934`, `#ECFDF5`, `#064E3B`.
  - Add decorative curved backdrop SVG elements matching Figma `35:838`.
  - Add the paper document icon badge and checkmark shields inside the notice container.

- [ ] **Step 4: Run test to verify it passes**

Run: `php ./vendor/bin/phpunit tests/Feature/FigmaThemeVisualTest.php`
Expected: PASS.

- [ ] **Step 5: Commit**

```bash
git add resources/views/welcome.blade.php resources/views/auth tests/Feature/FigmaThemeVisualTest.php
git commit -m "style: redesign landing and auth pages with figma teal card and notice styling"
```

---

### Task 3: Redesign Anonymous Whistleblowing & Tracking Pages

**Files:**
- Modify: `resources/views/whistleblowing/create.blade.php`
- Modify: `resources/views/whistleblowing/track.blade.php`
- Test: `tests/Feature/FigmaThemeVisualTest.php`

**Interfaces:**
- Consumes: Figma Page 2 (`39:160`) selection and tracking flow:
  - Breadcrumb navigation (`Beranda > Lacak Laporan`)
  - Whistleblowing ticket confirmation modal styled in emerald theme
  - Status timeline cards with teal indicators (`#0A8080` active state)
- Produces: Polished Whistleblowing reporting and public tracking views matching Figma.

- [ ] **Step 1: Add assertion to `tests/Feature/FigmaThemeVisualTest.php`**

```php
    public function test_whistleblowing_and_tracking_render_teal_theme(): void
    {
        $wb = $this->get('/whistleblowing');
        $wb->assertStatus(200);
        $wb->assertSee('#0a8080', false);

        $track = $this->get('/tracking');
        $track->assertStatus(200);
        $track->assertSee('#0a8080', false);
    }
```

- [ ] **Step 2: Run test to verify it fails**

Run: `php ./vendor/bin/phpunit tests/Feature/FigmaThemeVisualTest.php`
Expected: FAIL.

- [ ] **Step 3: Implement redesigned `create.blade.php` and `track.blade.php`**
  - Replace old blue accents with `#0A8080` submit button, `#F0FDFA` input highlights, and `#ECFDF5` alert boxes.
  - Style ticket modal with emerald checkmark badge.

- [ ] **Step 4: Run test to verify it passes**

Run: `php ./vendor/bin/phpunit tests/Feature/FigmaThemeVisualTest.php`
Expected: PASS.

- [ ] **Step 5: Commit**

```bash
git add resources/views/whistleblowing tests/Feature/FigmaThemeVisualTest.php
git commit -m "style: redesign whistleblowing submission and tracking in figma teal theme"
```

---

### Task 4: Redesign Pelapor Portal (Dashboard, Status, History)

**Files:**
- Modify: `resources/views/pelapor/dashboard.blade.php`
- Modify: `resources/views/pelapor/status.blade.php`
- Modify: `resources/views/pelapor/history.blade.php`
- Test: `tests/Feature/FigmaThemeVisualTest.php`

**Interfaces:**
- Consumes: Pelapor authenticated session.
- Produces:
  - Form submission card with `#0A8080` buttons and teal focus rings.
  - Status progression cards with green milestone badges.
  - History cards and interactive 5-star rating modal with amber stars and teal confirmation.

- [ ] **Step 1: Add assertion to `tests/Feature/FigmaThemeVisualTest.php`**

```php
    public function test_pelapor_portal_renders_teal_theme(): void
    {
        $pelapor = User::factory()->create(['role' => 'pelapor', 'email' => 'mhs@unair.ac.id']);
        $res = $this->actingAs($pelapor)->get('/pelapor/dashboard');
        $res->assertStatus(200);
        $res->assertSee('#0a8080', false);
    }
```

- [ ] **Step 2: Run test to verify it fails**

Run: `php ./vendor/bin/phpunit tests/Feature/FigmaThemeVisualTest.php`
Expected: FAIL.

- [ ] **Step 3: Update `pelapor/*.blade.php` views**
  - Implement `#0A8080` form buttons and `#0C3934` heading accents.
  - Update status pills to match Figma badges.

- [ ] **Step 4: Run test to verify it passes**

Run: `php ./vendor/bin/phpunit tests/Feature/FigmaThemeVisualTest.php`
Expected: PASS.

- [ ] **Step 5: Commit**

```bash
git add resources/views/pelapor tests/Feature/FigmaThemeVisualTest.php
git commit -m "style: apply figma teal and emerald styling to pelapor portal"
```

---

### Task 5: Redesign KABAG Suite (Dashboard, Verification, Whistleblowing, Users)

**Files:**
- Modify: `resources/views/kabag/dashboard.blade.php`
- Modify: `resources/views/kabag/detail.blade.php`
- Modify: `resources/views/kabag/verification.blade.php`
- Modify: `resources/views/kabag/whistleblowing.blade.php`
- Modify: `resources/views/kabag/my_reports.blade.php`
- Modify: `resources/views/kabag/history.blade.php`
- Modify: `resources/views/kabag/users/index.blade.php`
- Test: `tests/Feature/FigmaThemeVisualTest.php`

**Interfaces:**
- Consumes: Figma Page 3 (`106:4434` Dasbor E-Lapor UNAIR Modern 1):
  - Topbar greeting: "SELAMAT DATANG, Bapak/Ibu Kepala Bagian" with profile badge and live date pill (`106:4535`).
  - Metric cards: Total Laporan, Dalam Penanganan, Menunggu Verifikasi, Perlu Perhatian with directional percentage arrows (`106:4582`).
  - Table card: Laporan Terbaru (`106:4802`) with teal status badges and action links.
  - Disposition, Verification, and Revision modals styled in emerald/teal.

- [ ] **Step 1: Add assertion to `tests/Feature/FigmaThemeVisualTest.php`**

```php
    public function test_kabag_dashboard_renders_page3_figma_stat_cards(): void
    {
        $dekanat = SubDepartment::create(['name' => 'Dekanat', 'code' => 'DEKANAT']);
        $kabag = User::factory()->create(['role' => 'kabag', 'sub_department_id' => $dekanat->id]);

        $res = $this->actingAs($kabag)->get('/kabag/dashboard');
        $res->assertStatus(200);
        $res->assertSee('SELAMAT DATANG');
        $res->assertSee('Total Laporan');
        $res->assertSee('#0a8080', false);
    }
```

- [ ] **Step 2: Run test to verify it fails**

Run: `php ./vendor/bin/phpunit tests/Feature/FigmaThemeVisualTest.php`
Expected: FAIL.

- [ ] **Step 3: Redesign KABAG views**
  - Implement top greeting bar with profile card and date indicator matching Figma `106:4535`.
  - Implement 4 metric cards matching Figma `106:4582`.
  - Style disposition modals and verification cards with `#0A8080` buttons and `#ECFDF5` accents.

- [ ] **Step 4: Run test to verify it passes**

Run: `php ./vendor/bin/phpunit tests/Feature/FigmaThemeVisualTest.php`
Expected: PASS.

- [ ] **Step 5: Commit**

```bash
git add resources/views/kabag tests/Feature/FigmaThemeVisualTest.php
git commit -m "style: redesign kabag suite to match figma page 3 modern dashboard"
```

---

### Task 6: Redesign KASUBAG & PETUGAS Portals & Full E2E Verification

**Files:**
- Modify: `resources/views/kasubag/dashboard.blade.php`
- Modify: `resources/views/kasubag/detail.blade.php`
- Modify: `resources/views/kasubag/status.blade.php`
- Modify: `resources/views/kasubag/my_reports.blade.php`
- Modify: `resources/views/kasubag/history.blade.php`
- Modify: `resources/views/kasubag/users/index.blade.php`
- Modify: `resources/views/petugas/dashboard.blade.php`
- Modify: `resources/views/petugas/my_reports.blade.php`
- Modify: `resources/views/petugas/history.blade.php`
- Test: `tests/Feature/FigmaThemeVisualTest.php`
- Test: All 37 existing tests via `php ./vendor/bin/phpunit`

**Interfaces:**
- Consumes: Kasubag and Petugas authenticated sessions.
- Produces: Complete, cohesive visual styling across all 4 roles matching Figma, with 100% test pass rate.

- [ ] **Step 1: Add assertion for Kasubag & Petugas views**

```php
    public function test_kasubag_and_petugas_render_teal_theme(): void
    {
        $sarpras = SubDepartment::create(['name' => 'Sarpras', 'code' => 'SARPRAS']);
        $kasubag = User::factory()->create(['role' => 'kasubag', 'sub_department_id' => $sarpras->id]);
        $petugas = User::factory()->create(['role' => 'petugas', 'sub_department_id' => $sarpras->id]);

        $res1 = $this->actingAs($kasubag)->get('/kasubag/dashboard');
        $res1->assertStatus(200);
        $res1->assertSee('#0a8080', false);

        $res2 = $this->actingAs($petugas)->get('/petugas/dashboard');
        $res2->assertStatus(200);
        $res2->assertSee('#0a8080', false);
    }
```

- [ ] **Step 2: Run test to verify it fails**

Run: `php ./vendor/bin/phpunit tests/Feature/FigmaThemeVisualTest.php`
Expected: FAIL.

- [ ] **Step 3: Update `kasubag/*.blade.php` and `petugas/*.blade.php`**
  - Apply cohesive `#0C3934` sidebars, `#0A8080` buttons, and `#ECFDF5` evidence tags.
  - Verify all modal dialogs, status badges, and action buttons.

- [ ] **Step 4: Run full test suite to verify 100% pass**

Run: `php ./vendor/bin/phpunit`
Expected: PASS (all tests green).

- [ ] **Step 5: Commit**

```bash
git add resources/views/kasubag resources/views/petugas tests/Feature/FigmaThemeVisualTest.php
git commit -m "style: complete figma teal redesign for kasubag and petugas portals"
```
