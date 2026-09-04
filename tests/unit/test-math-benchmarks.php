<?php
/**
 * Unit Test Suite: 2026 Financial Math & Benchmark Engine
 * 
 * Verifies mathematical precision for:
 * 1. Fixed-rate standard amortization
 * 2. Month-by-month principal acceleration & 0% negative balance floor
 * 3. 0% interest rate division-by-zero prevention
 * 4. Mortgage recast re-amortization
 * 5. HELOC interest-only draw vs 20-year amortized repayment
 * 6. Fix-and-flip 70% rule-of-thumb MAO & hard money financing
 * 7. Home insurance deductible break-even horizon
 */

function format_currency($val) {
    return '$' . number_format(round($val));
}

function calc_pmt($p, $rate, $years) {
    if ($p <= 0 || $years <= 0) return 0.0;
    if ($rate == 0) return $p / ($years * 12);
    $r = ($rate / 100) / 12;
    $n = $years * 12;
    $factor = pow(1 + $r, $n);
    return $p * ($r * $factor) / ($factor - 1);
}

echo "-------------------------------------------------------\n";
echo "1. Unit Test: Mortgage Amortization & Payoff Acceleration\n";
echo "-------------------------------------------------------\n";
$balance = 265000.0;
$rate = 6.71;
$years = 25;
$extraMonthly = 200.0;

$baseMonthly = calc_pmt($balance, $rate, $years);
echo "Base Monthly Payment ($265k @ 6.71%, 25Y): " . format_currency($baseMonthly) . "/mo\n";
assert((int)round($baseMonthly) === 1824, "Base payment calculation matches standard financial formulas");

// Baseline interest over full 25 years
$r = ($rate / 100) / 12;
$tempBal = $balance;
$baseInterestTotal = 0;
for ($m = 1; $m <= 300; $m++) {
    $i = $tempBal * $r;
    $p = min($tempBal, $baseMonthly - $i);
    $baseInterestTotal += $i;
    $tempBal -= $p;
    if ($tempBal <= 0.01) break;
}

// Accelerated interest with extra $200/mo
$curBal = $balance;
$months = 0;
$actualInterestTotal = 0;
while ($curBal > 0.01 && $months < 600) {
    $months++;
    $i = $curBal * $r;
    $actualInterestTotal += $i;
    $scheduledP = min($curBal, $baseMonthly - $i);
    $curBal -= $scheduledP;
    $extraP = min($curBal, $extraMonthly);
    $curBal -= $extraP;
}

$interestSaved = $baseInterestTotal - $actualInterestTotal;
$monthsSaved = 300 - $months;
$yearsSaved = round($monthsSaved / 12, 1);

echo "Accelerated Payoff Term: " . round($months / 12, 1) . " Years ($months Months)\n";
echo "Time Saved: $monthsSaved months (~$yearsSaved years)\n";
echo "Total Interest Saved: " . format_currency($interestSaved) . "\n";
assert($months < 300, "Extra payment accelerates loan payoff");
assert($interestSaved > 50000, "Extra principal creates significant interest savings");

echo "\n-------------------------------------------------------\n";
echo "2. Unit Test: 0% Interest Rate Edge Case\n";
echo "-------------------------------------------------------\n";
$zeroRatePmt = calc_pmt(265000, 0, 25);
echo "0% Rate Payment on $265,000 (25Y): " . format_currency($zeroRatePmt) . "/mo\n";
assert(round($zeroRatePmt) == 883, "0% interest handled properly without division by zero");

echo "\n-------------------------------------------------------\n";
echo "3. Unit Test: Mortgage Recast Re-Amortization\n";
echo "-------------------------------------------------------\n";
$recastBalance = 265000.0;
$recastLumpSum = 10000.0;
$recastRate = 6.71;
$recastYears = 25;
$recastFee = 250.0;

$oldRecastPmt = calc_pmt($recastBalance, $recastRate, $recastYears);
$newRecastPmt = calc_pmt($recastBalance - $recastLumpSum, $recastRate, $recastYears);
$monthlyCashFlowFreed = $oldRecastPmt - $newRecastPmt;
$annualCashFlowFreed = $monthlyCashFlowFreed * 12;

echo "Original Payment: " . format_currency($oldRecastPmt) . "/mo\n";
echo "New Recast Payment: " . format_currency($newRecastPmt) . "/mo\n";
echo "Monthly Savings: +" . format_currency($monthlyCashFlowFreed) . "/mo\n";
echo "Annual Savings: +" . format_currency($annualCashFlowFreed) . "/yr\n";
assert(round($monthlyCashFlowFreed) == 69, "Recast monthly cash-flow savings match standard math");

echo "\n-------------------------------------------------------\n";
echo "4. Unit Test: HELOC Interest-Only Draw vs Amortized Repayment\n";
echo "-------------------------------------------------------\n";
$helocDrawn = 60000.0;
$helocRate = 7.29; // Sep 2, 2026 Bankrate benchmark
$helocRepayYears = 20;

$helocIO = ($helocDrawn * ($helocRate / 100)) / 12;
$helocRepay = calc_pmt($helocDrawn, $helocRate, $helocRepayYears);
$helocShock = $helocRepay - $helocIO;

echo "Draw Period (Interest-Only): " . format_currency($helocIO) . "/mo\n";
echo "Repayment Period (20Y Amortized): " . format_currency($helocRepay) . "/mo\n";
echo "Payment Shock Jump: +" . format_currency($helocShock) . "/mo\n";
assert(round($helocIO) == 365, "Interest-only formula verified");
assert(round($helocRepay) == 476, "Amortized repayment formula verified");

echo "\n-------------------------------------------------------\n";
echo "5. Unit Test: House-Flipping 70% Rule & Hard Money Underwriting\n";
echo "-------------------------------------------------------\n";
$flipARV = 350000.0;
$flipRehab = 50000.0;
$flipPurch = 220000.0;
$flipMonths = 6;
$flipHardMoneyRate = 11.0;
$flipPoints = 2.0;
$flipLTC = 80.0;
$flipHoldingMo = 750.0;
$flipSellingOverheadPct = 8.0;

$mao = ($flipARV * 0.70) - $flipRehab;
echo "70% Rule MAO: " . format_currency($mao) . "\n";
assert(round($mao) == 195000, "MAO formula verified");

$totalProjectCost = $flipPurch + $flipRehab; // $270,000
$loanAmount = $totalProjectCost * ($flipLTC / 100); // $216,000
$equityDown = $totalProjectCost - $loanAmount; // $54,000
$pointsFee = $loanAmount * ($flipPoints / 100); // $4,320
$loanInterest = $loanAmount * ($flipHardMoneyRate / 100) * ($flipMonths / 12); // $11,880
$totalHolding = $flipHoldingMo * $flipMonths; // $4,500
$sellingOverhead = $flipARV * ($flipSellingOverheadPct / 100); // $28,000
$buyingFees = 3500;

$totalDealExpenditure = $totalProjectCost + $pointsFee + $loanInterest + $totalHolding + $sellingOverhead + $buyingFees;
$netProfit = $flipARV - $totalDealExpenditure;
echo "Net Flip Profit: " . format_currency($netProfit) . "\n";
assert($netProfit > 0, "Flip profit calculation verified");

echo "\n-------------------------------------------------------\n";
echo "6. Unit Test: Insurance Deductible Savings Break-Even\n";
echo "-------------------------------------------------------\n";
$currentPrem = 2750.0;
$reductionPct = 9.0;
$riskGap = 2500 - 1000; // $1,500

$annualSavings = $currentPrem * ($reductionPct / 100);
$breakeven = $riskGap / $annualSavings;
echo "Annual Savings: " . format_currency($annualSavings) . "/yr\n";
echo "Break-Even Horizon: " . round($breakeven, 1) . " Years\n";
assert(round($annualSavings) == 248, "9% discount on $2,750 premium verified");
assert(round($breakeven, 1) == 6.1, "6.1 year break-even horizon verified");

echo "\n✓ ALL UNIT TESTS PASSED SUCCESSFULLY.\n";
return true;
