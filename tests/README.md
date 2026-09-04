# EquityPace Automated Test Suite

Comprehensive testing suite for EquityPace's 2026 U.S. Real Estate & Mortgage Financial Engines.

## Directory Structure

```
tests/
├── run-all-tests.php             # Master consolidated test runner
├── unit/
│   └── test-math-benchmarks.php  # Mathematical formula precision & edge-case tests
└── integration/
    └── test-calculators-http.php # HTTP 200, marker verification, and clean audit checks
```

## Running the Tests

### Option 1: Via Windows Batch Script
Double-click or run from terminal:
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
