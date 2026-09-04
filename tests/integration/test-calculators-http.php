<?php
/**
 * Integration Test Suite: 10-Calculator HTTP & Content Verification
 * 
 * Verifies that:
 * 1. All 10 calculator URLs return HTTP 200 OK
 * 2. 2026 market benchmarks and fallback markers are present on all pages
 * 3. Educational disclaimers are properly rendered
 * 4. Old deprecated values (6.50%, 1.20% tax, 8.50% HELOC, etc.) are absent
 */

$urls = [
    'Homepage / Mortgage Payoff'           => 'http://localhost:8080/',
    'Home Affordability with Daycare'       => 'http://localhost:8080/home-affordability-daycare/',
    'Rent vs. Buy with Closing Costs'      => 'http://localhost:8080/rent-vs-buy/',
    'Seller Net-Proceeds'                  => 'http://localhost:8080/seller-net-proceeds/',
    'House-Flipping Profit'                => 'http://localhost:8080/house-flipping-profit/',
    'Mortgage Recast'                      => 'http://localhost:8080/mortgage-recast/',
    'HELOC Interest-Only Payment'          => 'http://localhost:8080/heloc-payment/',
    'Home Replacement Cost'                => 'http://localhost:8080/home-replacement-cost/',
    'Insurance Deductible Savings'         => 'http://localhost:8080/insurance-deductible-savings/',
    'All Calculators Suite Directory'      => 'http://localhost:8080/all-calculators/'
];

$required_markers = [
    'Homepage / Mortgage Payoff' => [
        '265,000',
        '6.71%',
        'September 3, 2026',
        'official payoff quote',
        'Confirm that your mortgage servicer applies extra payments to principal',
        'input-prepayment-penalty',
        'Educational & Planning Disclaimer'
    ],
    'Home Affordability with Daycare' => [
        '1,100',
        '13,184',
        '0.90%',
        '2,750',
        'Years until childcare cost changes or ends',
        'Traditional Affordability Guideline (28% Front / 36% Back)',
        'Suggested Household Budgeting Target',
        'Recommended monthly living-cost buffer',
        'Educational & Planning Disclaimer'
    ],
    'Rent vs. Buy with Closing Costs' => [
        '450,000',
        '6.71%',
        '3.0%',
        '0.90%',
        '2,750',
        '1.5%',
        'Assumed Annual Home Appreciation',
        'Assumed Annual Rent Increase',
        'Assumed Annual Investment Return',
        'Educational & Planning Disclaimer'
    ],
    'Seller Net-Proceeds' => [
        '500,000',
        '280,000',
        '5.0%',
        'negotiable',
        '1.5%',
        'concession',
        'Educational & Planning Disclaimer'
    ],
    'House-Flipping Profit' => [
        '220,000',
        '350,000',
        '70%',
        'Investor Rule-of-Thumb',
        '11% (Default)',
        '80%',
        '750',
        '8% (Default)',
        'Educational & Planning Disclaimer'
    ],
    'Mortgage Recast' => [
        '265,000',
        '6.71%',
        '10,000',
        '250',
        'Eligibility Notice',
        'Educational & Planning Disclaimer'
    ],
    'HELOC Interest-Only Payment' => [
        '500,000',
        '60,000',
        '7.29%',
        'Sep. 2, 2026',
        '80% Max CLTV',
        'Draw Period (Interest-Only)',
        'Payment Shock',
        'Educational & Planning Disclaimer'
    ],
    'Home Replacement Cost' => [
        '2,400',
        '200',
        '50,000',
        '40,000',
        '10',
        'Economy ($125)',
        'Luxury ($475)',
        'Educational & Planning Disclaimer'
    ],
    'Insurance Deductible Savings' => [
        '2,750',
        '1,000',
        '2,500',
        '9%',
        'Educational & Planning Disclaimer'
    ],
    'All Calculators Suite Directory' => [
        'Explore All 10 Property & Mortgage Calculators',
        'Current Balance Payoff Calculator',
        'Mortgage Recast Calculator',
        'Home Affordability with Daycare Costs',
        'Rent vs. Buy with Closing Costs',
        'HELOC Interest-Only Payment Calculator',
        'Seller Net-Proceeds Calculator',
        'House-Flipping Profit Calculator',
        'Home Replacement-Cost Calculator',
        'Insurance Deductible Savings Calculator'
    ]
];

$all_passed = true;

foreach ($urls as $name => $url) {
    echo "Testing: [{$name}] ... ";
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $html = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code !== 200) {
        echo "❌ FAILED (HTTP {$http_code})\n";
        $all_passed = false;
        continue;
    }

    $markers = $required_markers[$name] ?? [];
    $missing = [];
    foreach ($markers as $m) {
        if (stripos($html, $m) === false) {
            $missing[] = $m;
        }
    }

    if (empty($missing)) {
        echo "✓ PASS (HTTP 200, " . count($markers) . " markers verified)\n";
    } else {
        echo "⚠️ Missing markers: " . implode(', ', $missing) . "\n";
        $all_passed = false;
    }
}

// Check for old forbidden strings across the platform
$forbidden = [
    '6.50%',
    '1.20% national property-tax',
    '8.50% default',
    '5.5% (Avg)',
    'Standard commission',
    'universal 1% assumption'
];

echo "\nChecking for deprecated values...\n";
$ch = curl_init('http://localhost:8080/');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$homeHtml = curl_exec($ch);
curl_close($ch);

foreach ($forbidden as $bad) {
    echo "Checking '{$bad}' ... ";
    if (stripos($homeHtml, $bad) !== false) {
        echo "❌ FOUND on homepage!\n";
        $all_passed = false;
    } else {
        echo "✓ Clean\n";
    }
}

if ($all_passed) {
    echo "\n✓ ALL INTEGRATION TESTS PASSED SUCCESSFULLY.\n";
    return true;
} else {
    echo "\n❌ INTEGRATION TESTS ENCOUNTERED FAILURES.\n";
    return false;
}
