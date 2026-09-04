# Mortgage & Homeownership Micro-Calculators

A collection of niche mortgage and homeownership calculators built for the problems generic finance-site calculators handle poorly — irregular payoff scenarios, real affordability math, and situational tools like recasts, HELOCs, and house-flipping profit.

## Calculators

- **Mortgage Payoff Calculator** — payoff timeline using current loan balance
- **Mortgage Payoff with Irregular Extra Payments** — models inconsistent, real-world extra payments instead of a fixed monthly add-on
- **Home Affordability Calculator** — factors in daycare costs alongside standard debt-to-income math
- **Rent vs. Buy Calculator** — includes closing costs in the comparison
- **Seller Net-Proceeds Calculator** — estimates what a seller actually walks away with after fees and payoff
- **House-Flipping Profit Calculator** — projects margin across purchase, rehab, and resale
- **Mortgage Recast Calculator** — models the effect of a lump-sum recast on payment and term
- **HELOC Interest-Only Payment Calculator** — payment estimates during the interest-only draw period
- **Home Replacement-Cost Calculator** — estimates rebuild cost separate from market value
- **Insurance Deductible Savings Calculator** — compares premium savings against higher deductibles

## Tech Stack

- PHP (WordPress-based)
- SQLite (via the SQLite Database Integration plugin)
- Local development via XAMPP

## Project Structure

```
mortgage-payoff-calculator/
├── .vscode/
│   └── settings.json
├── tests/
│   ├── run-all-tests.php             # Master consolidated test runner
│   ├── unit/
│   │   └── test-math-benchmarks.php  # Mathematical formula precision & edge-case tests
│   ├── integration/
│   │   └── test-calculators-http.php # HTTP 200, marker verification, and clean audit checks
│   └── README.md
├── wp-content/
│   ├── database/
│   │   ├── .ht.sqlite                # SQLite database
│   │   ├── .htaccess
│   │   └── index.php
│   ├── plugins/
│   │   ├── seo-pro/                  # SEO plugin
│   │   ├── sqlite-database-integration/
│   │   └── index.php
│   ├── themes/
│   └── uploads/
├── db.php
├── index.php
├── robots.txt
├── run-tests.bat
├── sitemap.xml
├── start-server.bat
└── wp-config.php
```

## Local Setup

1. Install [XAMPP](https://www.apachefriends.org/) and start Apache.
2. Clone this repo into your XAMPP `htdocs` folder (or your local WordPress install path).
3. Copy `wp-config.php` and fill in your local database credentials (use a sample/template config rather than committing real secrets).
4. Activate the SQLite Database Integration plugin and the calculator plugin from the WordPress admin.
5. Run `start-server.bat` or start Apache via XAMPP, then visit the site locally to use the calculators.

## Running the Tests

### Option 1: Via Windows Batch Script
```bash
run-tests.bat
```

### Option 2: Via PHP CLI
```bash
C:\xampp\php\php.exe tests\run-all-tests.php
```

### Option 3: Run Individual Suites
- **Unit Math Tests Only:**
  ```bash
  C:\xampp\php\php.exe tests\unit\test-math-benchmarks.php
  ```
- **HTTP & Content Audit Tests Only:**
  ```bash
  C:\xampp\php\php.exe tests\integration\test-calculators-http.php
  ```

## License

Add a license here if you intend this to be open source (MIT is a common default), or remove this section if it's private/portfolio-only.
