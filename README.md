
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
