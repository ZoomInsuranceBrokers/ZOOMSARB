@extends('layouts.app')

@section('title', 'Create Debit Note')

@section('content')
<div style="max-width: 850px; margin: 0 auto;">
    <div style="background: #fff; border-radius: 14px; box-shadow: 0 2px 16px #2e319222; padding: 2.5rem 2rem; margin-top:2rem;">
        <h2 style="color:#2e3192; font-weight:700; margin-bottom:2rem; text-align:center;">Create Debit Note</h2>
        <form method="POST" action="{{ route('debit.store') }}" onsubmit="return prepareParticularsJson();">
            @csrf
            <input type="hidden" name="quote_id" value="{{ $quote_id }}">
            <input type="hidden" name="note_type" value="debit">

            <div style="display: flex; gap: 1.5rem; flex-wrap: wrap;">
                <div style="flex:1 1 250px;">
                    <label for="bank_id" class="note-label">Bank Details</label>
                    <select id="bank_id" name="bank_id" class="form-input" required>
                        <option value="">Select Bank</option>
                        @foreach($bankingDetails as $bank)
                            <option value="{{ $bank->id }}">{{ $bank->Account_No }} - {{ $bank->Bank ?? 'N/A' }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="flex:1 1 250px;">
                    <label for="date" class="note-label">Date</label>
                    <input type="date" id="date" name="date" class="form-input" value="{{ date('Y-m-d') }}" required>
                </div>
                <div style="flex:1 1 250px;">
                    <label for="to" class="note-label">To (Client Name)</label>
                    <input type="text" id="to" name="to" class="form-input" value="{{ $quote->client->name ?? '' }}" required>
                </div>
            </div>

            <div style="display: flex; gap: 1.5rem; flex-wrap: wrap; margin-top:1.2rem;">
                <div style="flex:1 1 250px;">
                    <label for="PPW" class="note-label">PPW</label>
                    <input type="text" id="PPW" name="PPW" class="form-input" required>
                </div>
            </div>

            <!-- Premium Calculation Section -->
            <div style="margin-top:1.2rem;">
                <h4 style="color:#2e3192; font-weight:600; margin-bottom:1rem;">Premium Calculation</h4>

                <div style="display: flex; gap: 1.5rem; flex-wrap: wrap;">
                    <div style="flex:1 1 250px;">
                        <label for="total_premium" class="note-label">Total Premium</label>
                        <input type="number" id="total_premium" name="total_premium" class="form-input"
                               placeholder="Enter total premium" step="0.01" min="0" oninput="calculateDebitNote()" required>
                    </div>
                    <div style="flex:1 1 250px;">
                        <label for="ceding_commission" class="note-label">Ceding Commission (%)</label>
                        <input type="number" id="ceding_commission" name="ceding_commission" class="form-input"
                               placeholder="0" step="0.01" min="0" value="0" oninput="calculateDebitNote()">
                    </div>
                    <div style="flex:1 1 250px;">
                        <label for="ri_brokerage" class="note-label">RI Brokerage (%)</label>
                        <input type="number" id="ri_brokerage" name="ri_brokerage" class="form-input"
                               placeholder="0" step="0.01" min="0" value="0" oninput="calculateDebitNote()">
                    </div>
                </div>

                <!-- Calculation Results -->
                <div id="particularsWrapper" style="margin-top:1.5rem; background:#f8f9fa; padding:1.5rem; border-radius:8px; border:1px solid #e3f0ff;">
                    <h5 style="color:#2e3192; font-weight:600; margin-bottom:1rem;">Particulars Amount</h5>
                    <!-- This will be populated by JavaScript -->
                </div>
            </div>

            <input type="hidden" name="particulars" id="particularsJson">

            <div style="text-align:center; margin-top:2.5rem;">
                <button type="submit"
                    style="background: linear-gradient(90deg, #e74c3c 0%, #c0392b 100%); color: #fff; border: none; border-radius: 8px; padding: 0.9rem 2.5rem; font-size: 1.1rem; font-weight: 700; cursor: pointer; box-shadow: 0 2px 8px #e74c3c22;">
                    <i class="fas fa-save"></i> Save Debit Note
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    .form-input { width: 100%; padding: 0.7rem 1rem; border: 1.5px solid #e3f0ff; border-radius: 8px; background: #f7faff; font-size: 1rem; margin-top: 0.3rem; transition: border 0.2s; box-sizing: border-box; }
    .form-input:focus { border-color: #2e3192; outline: none; background: #e3f0ff; }
    .note-label { color:#2e3192; font-weight:600; margin-bottom:0.2rem; display:block; }
    .particular-item { display:flex; justify-content:space-between; margin-bottom:0.5rem; }
    .particular-item span:last-child { font-weight:600; }
    .particular-total { font-weight:700; font-size:1.05rem; color:#2e3192; }
    .particular-sub-item { padding-left: 1.5rem; }
    .particular-sub-item span:last-child { color:#e74c3c; }
    .reinsurer-block { margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid #e3f0ff; }
    .reinsurer-block:last-of-type { border-bottom: none; padding-bottom: 0; }
</style>

<script>
    @php
        $reinsurers_list = [];
        if (!empty($quote->reinsurer)) {
            $decoded_reinsurers = json_decode($quote->reinsurer, true);
            if (is_array($decoded_reinsurers)) {
                $reinsurers_list = $decoded_reinsurers;
            }
        }
    @endphp
    const reinsurers = {!! json_encode($reinsurers_list) !!};
    const clientName = document.getElementById('to').value || 'Client';

    function formatCurrency(amount) {
        return new Intl.NumberFormat('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(amount);
    }

    function calculateDebitNote() {
        const totalPremium = parseFloat(document.getElementById('total_premium').value) || 0;
        const cedingCommissionPercent = parseFloat(document.getElementById('ceding_commission').value) || 0;
        const riBrokeragePercent = parseFloat(document.getElementById('ri_brokerage').value) || 0;
        const wrapper = document.getElementById('particularsWrapper');
        let html = `<h5 style="color:#2e3192; font-weight:600; margin-bottom:1rem;">Particulars Amount</h5>`;
        let grandTotalDue = 0;

        html += `<div class="particular-item"><span>Total premium of the policy</span><span>${formatCurrency(totalPremium)}</span></div>`;
        html += `<hr style="margin:1rem 0; border-top:1px solid #dee2e6;">`;

        reinsurers.forEach(reinsurer => {
            const name = reinsurer.name;
            const percentage = parseFloat(reinsurer.percentage);

            const reinsurerShare = (totalPremium * percentage) / 100;
            const cedingCommission = (reinsurerShare * cedingCommissionPercent) / 100;
            const riBrokerage = (reinsurerShare * riBrokeragePercent) / 100;
            const totalDue = reinsurerShare - cedingCommission - riBrokerage;
            grandTotalDue += totalDue;

            html += `<div class="reinsurer-block">`;
            html += `<div class="particular-item"><span>${name}'s share @ ${percentage}%</span><span>${formatCurrency(reinsurerShare)}</span></div>`;

            if (cedingCommission > 0) {
                html += `<div class="particular-item particular-sub-item"><span>Less: Ceding commission @ ${cedingCommissionPercent}%</span><span>${formatCurrency(cedingCommission)}</span></div>`;
            }
            if (riBrokerage > 0) {
                html += `<div class="particular-item particular-sub-item"><span>Less: RI brokerage @ ${riBrokeragePercent}%</span><span>${formatCurrency(riBrokerage)}</span></div>`;
            }

            html += `<div class="particular-item particular-total"><span>Premium due from ${clientName} for ${name}</span><span>${formatCurrency(totalDue)}</span></div>`;
            html += `</div>`;
        });

        html += `<hr style="margin:1rem 0; border-top:1px solid #dee2e6;">`;
        html += `<div class="particular-item" style="font-size:1.2rem; font-weight:700;"><span>Grand Total Due</span><span>${formatCurrency(grandTotalDue)}</span></div>`;

        wrapper.innerHTML = html;
    }

    function prepareParticularsJson() {
        const totalPremium = parseFloat(document.getElementById('total_premium').value) || 0;
        if (totalPremium <= 0) {
            alert('Please enter a valid total premium.');
            return false;
        }

        const cedingCommissionPercent = parseFloat(document.getElementById('ceding_commission').value) || 0;
        const riBrokeragePercent = parseFloat(document.getElementById('ri_brokerage').value) || 0;
        let particulars = {};
        let grandTotalDue = 0;

        particulars[`Total premium of the policy`] = totalPremium;

        reinsurers.forEach(reinsurer => {
            const name = reinsurer.name;
            const percentage = parseFloat(reinsurer.percentage);

            const reinsurerShare = (totalPremium * percentage) / 100;
            const cedingCommission = (reinsurerShare * cedingCommissionPercent) / 100;
            const riBrokerage = (reinsurerShare * riBrokeragePercent) / 100;
            const totalDue = reinsurerShare - cedingCommission - riBrokerage;
            grandTotalDue += totalDue;

            particulars[`${name}'s share @ ${percentage}%`] = reinsurerShare;
            if (cedingCommission > 0) {
                particulars[`Less: Ceding commission @ ${cedingCommissionPercent}% for ${name}`] = -cedingCommission;
            }
            if (riBrokerage > 0) {
                particulars[`Less: RI brokerage @ ${riBrokeragePercent}% for ${name}`] = -riBrokerage;
            }
            particulars[`Premium due from ${clientName} for ${name}`] = totalDue;
        });

        particulars['Grand Total Due'] = grandTotalDue;
        document.getElementById('particularsJson').value = JSON.stringify(particulars);
        return true;
    }

    document.addEventListener('DOMContentLoaded', calculateDebitNote);
</script>
@endsection
