<?php
/**
 * EquityPace Master Test Runner
 * 
 * Sequentially executes Unit Tests and Integration Tests
 * and outputs a consolidated test report.
 */

echo "=======================================================\n";
echo "EQUITYPACE 2026 CALCULATORS SUITE — MASTER TEST RUNNER\n";
echo "=======================================================\n\n";

$start_time = microtime(true);

echo ">>> RUNNING SUITE 1: MATHEMATICAL & BENCHMARK UNIT TESTS\n";
$unit_passed = require __DIR__ . '/unit/test-math-benchmarks.php';

echo "\n>>> RUNNING SUITE 2: HTTP & INTEGRATION BENCHMARK AUDIT\n";
$integration_passed = require __DIR__ . '/integration/test-calculators-http.php';

$elapsed = round(microtime(true) - $start_time, 2);

echo "\n=======================================================\n";
echo "FINAL CONSOLIDATED TEST SUMMARY\n";
echo "=======================================================\n";
echo "Unit Tests:        " . ($unit_passed ? "✓ PASSED (6/6)" : "❌ FAILED") . "\n";
echo "Integration Tests: " . ($integration_passed ? "✓ PASSED (10/10)" : "❌ FAILED") . "\n";
echo "Execution Time:    {$elapsed} seconds\n";

if ($unit_passed && $integration_passed) {
    echo "Overall Status:    🎉 ALL TESTS PASSED SUCCESSFULLY (100%)\n";
    exit(0);
} else {
    echo "Overall Status:    ⚠️ SOME TESTS FAILED\n";
    exit(1);
}
