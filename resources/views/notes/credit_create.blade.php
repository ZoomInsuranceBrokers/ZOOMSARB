@extends('layouts.app')

@section('title', 'Create Credit Note')

@section('content')
<div style="max-width: 850px; margin: 0 auto;">
    <div style="background: #fff; border-radius: 14px; box-shadow: 0 2px 16px #2e319222; padding: 2.5rem 2rem; margin-top:2rem;">
        <h2 style="color:#2e3192; font-weight:700; margin-bottom:2rem; text-align:center;">Create Credit Note</h2>
        <form method="POST" action="{{ route('credit.store') }}" onsubmit="return prepareParticularsJson();">
            @csrf
            <input type="hidden" name="quote_id" value="{{ $quote_id }}">
            <input type="hidden" name="note_type" value="credit">

            <div style="display: flex; gap: 1.5rem; flex-wrap: wrap;">
                <div style="flex:1 1 250px;">
                    <label for="date" class="note-label">Date</label>
                    <input type="date" id="date" name="date" class="form-input" value="{{ date('Y-m-d') }}" required>
                </div>
                <div style="flex:1 1 250px;">
                    <label for="to" class="note-label">To</label>
                    <input type="text" id="to" name="to" class="form-input" required>
                </div>
            </div>


            <!-- Reinsurer Selection -->
            <div style="margin-top:1.2rem;">
                <label for="selected_reinsurer" class="note-label">Select Reinsurer</label>

                <select id="selected_reinsurer" name="selected_reinsurer" class="form-input" required onchange="updateSelectedReinsurer()">
                    <option value="">Select Reinsurer</option>
                    @if($quote->reinsurer)
                        @php
                            $reinsurers = json_decode($quote->reinsurer, true);
                        @endphp
                        @foreach($reinsurers as $index => $reinsurer)
                            <option value="{{ $reinsurer['name'] }}" data-name="{{ $reinsurer['name'] }}" data-percentage="{{ $reinsurer['percentage'] }}">
                                {{ $reinsurer['name'] }} ({{ $reinsurer['percentage'] }}%)
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>

            <!-- Premium Calculation Section -->
            <div style="margin-top:1.2rem;">
                <h4 style="color:#2e3192; font-weight:600; margin-bottom:1rem;">Premium Calculation</h4>

                <div style="display: flex; gap: 1.5rem; flex-wrap: wrap;">
                    <div style="flex:1 1 250px;">
                        <label for="total_premium" class="note-label">Total Premium</label>
                        <input type="number" id="total_premium" name="total_premium" class="form-input"
                               placeholder="Enter total premium" step="0.01" min="0" oninput="calculatePremium()" required>
                    </div>
                    <div style="flex:1 1 250px;">
                        <label for="ceding_commission" class="note-label">Ceding Commission (%)</label>
                        <input type="number" id="ceding_commission" name="ceding_commission" class="form-input"
                               placeholder="0" step="0.01" min="0" value="0" oninput="calculatePremium()">
                    </div>
                    <div style="flex:1 1 250px;">
                        <label for="ri_brokerage" class="note-label">RI Brokerage (%)</label>
                        <input type="number" id="ri_brokerage" name="ri_brokerage" class="form-input"
                               placeholder="0" step="0.01" min="0" value="0" oninput="calculatePremium()">
                    </div>
                </div>

                <!-- Calculation Results -->
                <div style="margin-top:1.5rem; background:#f8f9fa; padding:1.5rem; border-radius:8px; border:1px solid #e3f0ff;">
                    <h5 style="color:#2e3192; font-weight:600; margin-bottom:1rem;">Particulars Amount</h5>

                    <div style="display:flex; justify-content:space-between; margin-bottom:0.5rem;">
                        <span>Total premium</span>
                        <span id="display_total_premium" style="font-weight:600;">0.00</span>
                    </div>

                    <div style="display:flex; justify-content:space-between; margin-bottom:0.5rem;">
                        <span id="reinsurer_share_label">Reinsurer's share @ 0%</span>
                        <span id="display_reinsurer_share" style="font-weight:600;">0.00</span>
                    </div>

                    <div style="display:flex; justify-content:space-between; margin-bottom:0.5rem;">
                        <span id="ceding_commission_label">Less: Ceding Commission @ 0%</span>
                        <span id="display_ceding_commission" style="font-weight:600; color:#e74c3c;">0.00</span>
                    </div>

                    <div style="display:flex; justify-content:space-between; margin-bottom:0.5rem;">
                        <span id="ri_brokerage_label">Less: RI brokerage @ 0%</span>
                        <span id="display_ri_brokerage" style="font-weight:600; color:#e74c3c;">0.00</span>
                    </div>

                    <hr style="margin:1rem 0; border:1px solid #dee2e6;">

                    <div style="display:flex; justify-content:space-between; font-weight:700; font-size:1.1rem; color:#2e3192;">
                        <span id="total_due_label">Total due to Reinsurer</span>
                        <span id="display_total_due">0.00</span>
                    </div>
                </div>
            </div>

            <input type="hidden" name="particulars" id="particularsJson">
            <input type="hidden" name="reinsurer_percentage" id="reinsurer_percentage">
            <input type="hidden" name="reinsurer_name" id="reinsurer_name">

            <div style="text-align:center; margin-top:2.5rem;">
                <button type="submit"
                    style="background: linear-gradient(90deg, #27ae60 0%, #2ecc71 100%); color: #fff; border: none; border-radius: 8px; padding: 0.9rem 2.5rem; font-size: 1.1rem; font-weight: 700; cursor: pointer; box-shadow: 0 2px 8px #27ae6022;">
                    <i class="fas fa-save"></i> Save Credit Note
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    .form-input {
        width: 100%;
        padding: 0.7rem 1rem;
        border: 1.5px solid #e3f0ff;
        border-radius: 8px;
        background: #f7faff;
        font-size: 1rem;
        margin-top: 0.3rem;
        transition: border 0.2s;
        box-sizing: border-box;
    }
    .form-input:focus {
        border-color: #2e3192;
        outline: none;
        background: #e3f0ff;
    }
    .note-label {
        color:#2e3192;
        font-weight:600;
        margin-bottom:0.2rem;
        display:block;
    }
</style>

<script>
    let selectedReinsurerData = null;

    function updateSelectedReinsurer() {
        const select = document.getElementById('selected_reinsurer');
        const selectedOption = select.options[select.selectedIndex];

        if (selectedOption.value) {
            selectedReinsurerData = {
                name: selectedOption.getAttribute('data-name'),
                percentage: parseFloat(selectedOption.getAttribute('data-percentage'))
            };

            // Update hidden fields
            document.getElementById('reinsurer_percentage').value = selectedReinsurerData.percentage;
            document.getElementById('reinsurer_name').value = selectedReinsurerData.name;

            // Update labels
            document.getElementById('reinsurer_share_label').textContent =
                `${selectedReinsurerData.name}'s share @ ${selectedReinsurerData.percentage}%`;
            document.getElementById('total_due_label').textContent =
                `Total due to ${selectedReinsurerData.name}`;

            calculatePremium();
        } else {
            selectedReinsurerData = null;
            resetCalculations();
        }
    }

    function calculatePremium() {
        if (!selectedReinsurerData) {
            resetCalculations();
            return;
        }

        const totalPremium = parseFloat(document.getElementById('total_premium').value) || 0;
        const cedingCommissionPercent = parseFloat(document.getElementById('ceding_commission').value) || 0;
        const riBrokeragePercent = parseFloat(document.getElementById('ri_brokerage').value) || 0;

        // Calculate reinsurer's share
        const reinsurerShare = (totalPremium * selectedReinsurerData.percentage) / 100;

        // Calculate commissions
        const cedingCommission = (reinsurerShare * cedingCommissionPercent) / 100;
        const riBrokerage = (reinsurerShare * riBrokeragePercent) / 100;

        // Calculate total due
        const totalDue = reinsurerShare - cedingCommission - riBrokerage;

        // Update display
        document.getElementById('display_total_premium').textContent = formatCurrency(totalPremium);
        document.getElementById('display_reinsurer_share').textContent = formatCurrency(reinsurerShare);
        document.getElementById('display_ceding_commission').textContent = formatCurrency(cedingCommission);
        document.getElementById('display_ri_brokerage').textContent = formatCurrency(riBrokerage);
        document.getElementById('display_total_due').textContent = formatCurrency(totalDue);

        // Update labels with percentages
        document.getElementById('ceding_commission_label').textContent =
            `Less: Ceding Commission @ ${cedingCommissionPercent}%`;
        document.getElementById('ri_brokerage_label').textContent =
            `Less: RI brokerage @ ${riBrokeragePercent}%`;
    }

    function resetCalculations() {
        document.getElementById('display_total_premium').textContent = '0.00';
        document.getElementById('display_reinsurer_share').textContent = '0.00';
        document.getElementById('display_ceding_commission').textContent = '0.00';
        document.getElementById('display_ri_brokerage').textContent = '0.00';
        document.getElementById('display_total_due').textContent = '0.00';
        document.getElementById('reinsurer_share_label').textContent = "Reinsurer's share @ 0%";
        document.getElementById('total_due_label').textContent = "Total due to Reinsurer";
        document.getElementById('ceding_commission_label').textContent = "Less: Ceding Commission @ 0%";
        document.getElementById('ri_brokerage_label').textContent = "Less: RI brokerage @ 0%";
    }

    function formatCurrency(amount) {
        return new Intl.NumberFormat('en-IN', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }).format(amount);
    }

    function prepareParticularsJson() {
        if (!selectedReinsurerData) {
            alert('Please select a reinsurer');
            return false;
        }

        const totalPremium = parseFloat(document.getElementById('total_premium').value) || 0;
        const cedingCommissionPercent = parseFloat(document.getElementById('ceding_commission').value) || 0;
        const riBrokeragePercent = parseFloat(document.getElementById('ri_brokerage').value) || 0;

        if (totalPremium <= 0) {
            alert('Please enter a valid total premium');
            return false;
        }

        // Calculate values
        const reinsurerShare = (totalPremium * selectedReinsurerData.percentage) / 100;
        const cedingCommission = (reinsurerShare * cedingCommissionPercent) / 100;
        const riBrokerage = (reinsurerShare * riBrokeragePercent) / 100;
        const totalDue = reinsurerShare - cedingCommission - riBrokerage;

        // Prepare particulars object with percentage labels
        let particulars = {
            'Total premium': totalPremium,
            [`${selectedReinsurerData.name}'s share @ ${selectedReinsurerData.percentage}%`]: reinsurerShare,
            [`Less: Ceding Commission @ ${cedingCommissionPercent}%`]: -cedingCommission,
            [`Less: RI brokerage @ ${riBrokeragePercent}%`]: -riBrokerage,
            [`Total due to ${selectedReinsurerData.name}`]: totalDue
        };

        document.getElementById('particularsJson').value = JSON.stringify(particulars);
        return true;
    }
</script>
@endsection
