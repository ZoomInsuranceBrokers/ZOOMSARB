@extends('layouts.app')

@section('title', 'Create Debit Note')

@section('content')
    <div style="max-width: 850px; margin: 0 auto;">
        <div
            style="background: #fff; border-radius: 14px; box-shadow: 0 2px 16px #2e319222; padding: 2.5rem 2rem; margin-top:2rem;">
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
                            @foreach ($bankingDetails as $bank)
                                <option value="{{ $bank->id }}">{{ $bank->Account_No }} - {{ $bank->Bank ?? 'N/A' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div style="flex:1 1 250px;">
                        <label for="date" class="note-label">Date</label>
                        <input type="date" id="date" name="date" class="form-input" value="{{ date('Y-m-d') }}"
                            required>
                    </div>
                    <div style="flex:1 1 250px;">
                        <label for="to" class="note-label">To (Client Name)</label>
                        <select id="to" name="to" class="form-input" required>
                            <option value="">Select Company</option>
                            <option
                                value="Acko General Insurance Limited&#x0A;2nd Floor, #36/5, Hustlehub One East, Somasandrapalya, 27th Main road, Sector 2, HSR Layout, Bengaluru, Karnataka - 560102">
                                Acko General Insurance Limited</option>
                            <option
                                value="Agriculture Insurance Company of India Limited&#x0A;Plate B&C, 5th Floor, Block 1, East Kidwai Nagar, New Delhi-110023">
                                Agriculture Insurance Company of India Limited</option>
                            <option
                                value="Bajaj Allianz General Insurance Company Limited&#x0A;Bajaj Allianz House, Airport Road, Yerwada, Pune, Maharashtra, 411006">
                                Bajaj Allianz General Insurance Company Limited</option>
                            <option
                                value="Cholamandalam MS General Insurance Company Limited&#x0A;Dare House, 2nd Floor, NSC Bose Road, Parrys, Chennai-600001">
                                Cholamandalam MS General Insurance Company Limited</option>
                            <option
                                value="ECGC Limited&#x0A;ECGC Bhawan, CTS No.393,393/1 to 45, M.V Road Andheri East, Mumbai – 400069, Maharashtra, India">
                                ECGC Limited</option>
                            <option
                                value="Future Generali India Insurance Company Limited&#x0A;Unit number 801 & 802 8th floor, Tower C, Embassy 247 Park L.B.S Marg, Vikhroli (west) Mumbai 400083">
                                Future Generali India Insurance Company Limited</option>
                            <option
                                value="Digit General Insurance Limited&#x0A;1 to 6 floors, Ananta One (AR One), Pride Hotel Lane, Narveer Tanaji Wadi, City Survey No.1579, Shivaji Nagar, Pune-411005;">
                                Digit General Insurance Limited</option>
                            <option
                                value="HDFC ERGO General Insurance Company Limited&#x0A;HDFC House,1st Floor,165-166, Backbay Reclamation, H.T.Parekh Marg, Churchgate, Mumbai-400020">
                                HDFC ERGO General Insurance Company Limited</option>
                            <option
                                value="ICICI LOMBARD General Insurance Company Limited&#x0A;ICICI Lombard House, 414, Veer Savarkar Marg Near Siddhivinayak Temple, Prabhadevi Mumbai 400025, India">
                                ICICI LOMBARD General Insurance Company Limited</option>
                            <option
                                value="IFFCO TOKIO General Insurance Company Limited&#x0A;IFFCO Tower II, 4th & 5th Floor, Plot No. 3, Sector 29, Gurugram- 122001">
                                IFFCO TOKIO General Insurance Company Limited</option>
                            <option
                                value="Zurich Kotak General Insurance Company Limited&#x0A;Unit no. 401, 4th Floor, Silver Metropolis, Jai Coach Compound, Off Western Express Highway, Goregaon (East), Mumbai-400063">
                                Zurich Kotak General Insurance Company Limited</option>
                            <option
                                value="Kshema General Insurance Limited&#x0A;413, 4th Floor, My Home Tycoon Building, Kundan Bagh, Begumpet, Hyderabad - 500016">
                                Kshema General Insurance Limited</option>
                            <option
                                value="Liberty General Insurance Limited&#x0A;Unit 1501 & 1502, 15th floor, Tower 2, One International Center, Senapati Bapat Marg, Prabhadevi, Mumbai – 400013">
                                Liberty General Insurance Limited</option>
                            <option
                                value="Magma HDI General Insurance Company Limited&#x0A;Unit No. 1B & 2B, 2nd floor, Equinox Business Park, Tower – 3, LBS Marg, Kurla (West), Mumbai – 400070">
                                Magma HDI General Insurance Company Limited</option>
                            <option
                                value="National Insurance Co. Ltd&#x0A;Premises No.18-0374, Plot No.CBD-81, New Town, Kolkata-700 156">
                                National Insurance Co. Ltd</option>
                            <option
                                value="Navi General Insurance Limited&#x0A;Vaishnavi Tech Square, 7th Floor, Iballur Village, Begur Hobli, Bengaluru, Karnataka- 560102">
                                Navi General Insurance Limited</option>
                            <option
                                value="Raheja QBE General Insurance Co. Ltd.&#x0A;Raheja QBE General Insurance Co. Ltd. Ground Floor, P&G Plaza, Cardinal Gracious Road, Chakala, Andheri (East),Mumbai - 400099">
                                Raheja QBE General Insurance Co. Ltd.</option>
                            <option
                                value="Reliance General Insurance Company Limited&#x0A;Reliance General Insurance Company Limited, 6th Floor, Oberoi Commerz, International Business Park, Oberoi Garden City, Off Western Express Highway, Goregaon (East), Mumbai – 400063">
                                Reliance General Insurance Company Limited</option>
                            <option
                                value="Royal Sundaram General Insurance Company Limited&#x0A;Vishranthi Melaram Towers, No.2/319, Rajiv Gandhi Salai (OMR), Karapakkam, Chennai - 600097">
                                Royal Sundaram General Insurance Company Limited</option>
                            <option
                                value="SBI General Insurance Company Limited&#x0A;Fulcrum Building, 9th Floor, A & B wing, Sahar Road, Andheri (East), Mumbai 400099">
                                SBI General Insurance Company Limited</option>
                            <option
                                value="Shriram General Insurance Company Limited&#x0A;E-8, EPIP, RIICO Industrial Area, Sitapura, Jaipur - 302022 (Rajasthan)">
                                Shriram General Insurance Company Limited</option>
                            <option
                                value="Tata AIG General Insurance Company Limited&#x0A;Peninsula Business Park, Tower A, 15th Floor, G.K. Marg, Lower Parel, Mumbai – 400013">
                                Tata AIG General Insurance Company Limited</option>
                            <option
                                value="New India Assurance Company Limited&#x0A;87, M.G Road, Fort, Mumbai, Maharashtra – 400001">
                                New India Assurance Company Limited</option>
                            <option
                                value="Oriental Insurance Co. Ltd&#x0A;The Oriental Insurance Company Limited,“Oriental House”, A-25/27, Asaf Ali Road, New Delhi-I 110002">
                                Oriental Insurance Co. Ltd</option>
                            <option value="United India Insurance Co. Ltd&#x0A;No.24, Whites Road, Chennai -600014">United
                                India Insurance Co. Ltd</option>
                            <option
                                value="Universal Sompo General Insurance Company Limited&#x0A;Unit No.103, 1st Floor, Ackruti Star, MIDC, Andheri (East), Mumbai-400093, Maharashtra">
                                Universal Sompo General Insurance Company Limited</option>
                            <option
                                value="Zuno General Insurance Ltd. (formerly known as Edelweiss General Insurance Company Limited)&#x0A;2nd floor, Tower 3, Wing B, Kohinoor City, Kirol Road, Kurla (west) Mumbai City MH, 400070">
                                Zuno General Insurance Ltd. (formerly known as Edelweiss General Insurance Company Limited)
                            </option>
                            <option
                                value="Bharti Axa General Insurance Co. Ltd&#x0A;Phoenix House, Senapati Bapat Marg, Lower Parel, Mumbai, Maharashtra">
                                Bharti Axa General Insurance Co. Ltd</option>
                        </select>
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
                                placeholder="Enter total premium" step="0.01" min="0"
                                oninput="calculateDebitNote()" required>
                        </div>
                        <div style="flex:1 1 250px;">
                            <label for="ceding_commission" class="note-label">Ceding Commission (%)</label>
                            <input type="number" id="ceding_commission" name="ceding_commission" class="form-input"
                                placeholder="0" step="0.01" min="0" value="0" oninput="calculateDebitNote()">
                        </div>
                        <div style="flex:1 1 250px;">
                            <label for="ri_brokerage" class="note-label">RI Brokerage (%)</label>
                            <input type="number" id="ri_brokerage" name="ri_brokerage" class="form-input"
                                placeholder="0" step="0.01" min="0" value="0"
                                oninput="calculateDebitNote()">
                        </div>
                    </div>

                    <!-- Calculation Results -->
                    <div id="particularsWrapper"
                        style="margin-top:1.5rem; background:#f8f9fa; padding:1.5rem; border-radius:8px; border:1px solid #e3f0ff;">
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
            color: #2e3192;
            font-weight: 600;
            margin-bottom: 0.2rem;
            display: block;
        }

        .particular-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
        }

        .particular-item span:last-child {
            font-weight: 600;
        }

        .particular-total {
            font-weight: 700;
            font-size: 1.05rem;
            color: #2e3192;
        }

        .particular-sub-item {
            padding-left: 1.5rem;
        }

        .particular-sub-item span:last-child {
            color: #e74c3c;
        }

        .reinsurer-block {
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #e3f0ff;
        }

        .reinsurer-block:last-of-type {
            border-bottom: none;
            padding-bottom: 0;
        }
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
            return new Intl.NumberFormat('en-IN', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }).format(amount);
        }

        function calculateDebitNote() {
            const totalPremium = parseFloat(document.getElementById('total_premium').value) || 0;
            const cedingCommissionPercent = parseFloat(document.getElementById('ceding_commission').value) || 0;
            const riBrokeragePercent = parseFloat(document.getElementById('ri_brokerage').value) || 0;
            const wrapper = document.getElementById('particularsWrapper');
            let html = `<h5 style="color:#2e3192; font-weight:600; margin-bottom:1rem;">Particulars Amount</h5>`;
            let grandTotalDue = 0;

            html +=
                `<div class="particular-item"><span>Total premium of the policy</span><span>${formatCurrency(totalPremium)}</span></div>`;
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
                html +=
                    `<div class="particular-item"><span>${name}'s share @ ${percentage}%</span><span>${formatCurrency(reinsurerShare)}</span></div>`;

                if (cedingCommission > 0) {
                    html +=
                        `<div class="particular-item particular-sub-item"><span>Less: Ceding commission @ ${cedingCommissionPercent}%</span><span>${formatCurrency(cedingCommission)}</span></div>`;
                }
                if (riBrokerage > 0) {
                    html +=
                        `<div class="particular-item particular-sub-item"><span>Less: RI brokerage @ ${riBrokeragePercent}%</span><span>${formatCurrency(riBrokerage)}</span></div>`;
                }

                html +=
                    `<div class="particular-item particular-total"><span>Premium due from ${clientName} for ${name}</span><span>${formatCurrency(totalDue)}</span></div>`;
                html += `</div>`;
            });

            html += `<hr style="margin:1rem 0; border-top:1px solid #dee2e6;">`;
            html +=
                `<div class="particular-item" style="font-size:1.2rem; font-weight:700;"><span>Grand Total Due</span><span>${formatCurrency(grandTotalDue)}</span></div>`;

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
                    particulars[`Less: Ceding commission @ ${cedingCommissionPercent}% for ${name}`] = -
                        cedingCommission;
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
