@extends('layouts.app')

@section('title', 'Create Credit Note')

@section('content')
    <div style="max-width: 850px; margin: 0 auto;">
        <div
            style="background: #fff; border-radius: 14px; box-shadow: 0 2px 16px #2e319222; padding: 2.5rem 2rem; margin-top:2rem;">
            <h2 style="color:#2e3192; font-weight:700; margin-bottom:2rem; text-align:center;">Create Credit Note</h2>
            <form method="POST" action="{{ route('credit.store') }}" onsubmit="return prepareParticularsJson();">
                @csrf
                <input type="hidden" name="quote_id" value="{{ $quote_id }}">
                <input type="hidden" name="note_type" value="credit">

                <div style="display: flex; gap: 1.5rem; flex-wrap: wrap;">
                    <div style="flex:1 1 250px;">
                        <label for="date" class="note-label">Date</label>
                        <input type="date" id="date" name="date" class="form-input" value="{{ date('Y-m-d') }}"
                            required>
                    </div>
                    <div style="flex:1 1 250px;">
                        <label for="to" class="note-label">To</label>
                        <select id="to" name="to" class="form-input" required>
                            <option value="">Select Company</option>
                            <option value="Everest&#x0A;30 Raffles Place Singapore 048622">Everest</option>
                            <option value="Echo Re&#x0A;Echo Reinsurance Limited Brandschenkestrasse 18-208001 Zurich">Echo
                                Re</option>
                            <option value="GP / CelsiusPro&#x0A;Seebahnstrasse 85 CH‑8003 Zürich Switzerland">GP /
                                CelsiusPro</option>
                            <option value="Canopius&#x0A;138 Market Street CapitaGreen, #04-01 Singapore 048946">Canopius
                            </option>
                            <option
                                value="Axa Climate&#x0A;AXA XL, A division of AXA Unit No. 608, 6th Floor, INS Tower, A Wing, Plot No. C-63, ‘G’ Block, Bandra Kurla Complex, Mumbai, India – 400051">
                                Axa Climate</option>
                            <option
                                value="SCOR&#x0A;Unit 907, 908–910, Kanakia Wallstreet, Village Chakala and Mulgaon, Andheri Kurla Road, Andheri East, Mumbai – 400093, India">
                                SCOR</option>
                            <option
                                value="Peak Re&#x0A;13/F–15/M Floor, WKCDA Tower, No. 8 Austin Road West, West Kowloon Cultural District, Kowloon, Hong Kong">
                                Peak Re</option>
                            <option value="Mapfre&#x0A;P.º de Recoletos 25, 28004 Madrid, Spain">Mapfre</option>
                            <option
                                value="Axis&#x0A;Axis Re Se, Dublin, Zurich Branch, Alfred Escher-Strasse, 50, 8002 Zürich, Switzerland">
                                Axis</option>
                            <option
                                value="Specialty MGA&#x0A;34 Lime Street Office 3.11 & 3.12, C/O Mnk Re Limited, London, EC3M 7AT, UNITED KINGDOM">
                                Specialty MGA</option>
                            <option value="Allianz&#x0A;3 Temasek Ave, Singapore 039190">Allianz</option>
                            <option
                                value="Volante&#x0A;Unit 1, Level 6, 4 North Avenue, Maker Maxity, Bandra Kurla Complex, Bandra East, Mumbai – 400 051, India">
                                Volante</option>
                            <option
                                value="Markel Capital Limited- Syndicate 3000&#x0A;Unit 1, Level 6, 4 North Avenue, Maker Maxity, Bandra Kurla Complex, Bandra East, Mumbai 400 051, India">
                                Markel Capital Limited- Syndicate 3000</option>
                            <option value="Helvetia&#x0A;Zürichstrasse 130, CH‑8600 Dübendorf (Zurich), Switzerland">
                                Helvetia</option>
                            <option
                                value="IRB Brasil&#x0A;Av. República do Chile, 330 (Torre Leste, 3E–4 Andares) Centro, Rio de Janeiro – RJ, CEP 20031‑170, Brazil">
                                IRB Brasil</option>
                            <option
                                value="Liberty&#x0A;42 Rue Washington, Building Monceau — 7th Floor, 75008 Paris, France">
                                Liberty</option>
                            <option value="Polish Re&#x0A;ul. Bytomska 4, 01‑612 Warszawa, Poland">Polish Re</option>
                            <option
                                value="Partner Re&#x0A;Level 38, Room 3837, Sun Hung Kai Centre 30 Harbour Road, Wan Chai, Hong Kong SAR">
                                Partner Re</option>
                            <option
                                value="AEGIS Managing Agency Limited&#x0A;25 Fenchurch Avenue London EC3M 5AD United Kingdom">
                                AEGIS Managing Agency Limited</option>
                            <option value="Renaissance Re&#x0A;Beethovenstrasse 33, CH‑8002 Zürich, Switzerland">Renaissance
                                Re</option>
                            <option value="MS Amlin&#x0A;MS Amlin AG, Kirchenweg 5, 8008 Zürich, Switzerland">MS Amlin
                            </option>
                            <option value="Hiscox&#x0A;Hiscox – 22 Bishopsgate London EC2N 4BQ, United Kingdom">Hiscox
                            </option>
                            <option value="Trans Re&#x0A;Sihlstrasse 38, PO Box 8021, 8001 Zürich, Switzerland">Trans Re
                            </option>
                            <option value="Hartford&#x0A;Hartford, Connecticut 06155‑0001 United States">Hartford</option>
                            <option value="Arch Re&#x0A;Talstrasse 65, 7th Floor, CH‑8001 Zürich, Switzerland">Arch Re
                            </option>
                            <option value="Convex&#x0A;52 Lime Street London EC3M 7AF United Kingdom">Convex</option>
                            <option value="SCR Morocco&#x0A;Tour ATLAS, Place Zellaqa B.P. 13183 Casablanca, Morocco">SCR
                                Morocco</option>
                            <option
                                value="Munich Re&#x0A;Unit 1101, B Wing, The Capital, Plot no. C-70, G Block, Bandra Kurla Complex (BKC), Bandra (East), Mumbai 400051, India">
                                Munich Re</option>
                            <option
                                value="Swiss Re&#x0A;A 701, 7th Floor, One BKC, Plot No. C-66 G Block, Bandra Kurla Complex, Mumbai 400051, India">
                                Swiss Re</option>
                            <option
                                value="GIC Re&#x0A;Suraksha, 170, Jamshedji Tata Road, Churchgate, Mumbai 400020, India">GIC
                                Re</option>
                            <option
                                value="Hannover Re&#x0A;B Wing, Unit No. 604, 6th Floor, Fulcrum, Sahar Road, Andheri (East), Mumbai 400099, India">
                                Hannover Re</option>
                            <option value="Antares&#x0A;21 Lime Street London EC3M 7HB United Kingdom">Antares</option>
                            <option value="Inigo&#x0A;6th Floor, 10 Fenchurch Avenue London EC3M 5AG United Kingdom">Inigo
                            </option>
                            <option value="Descartes&#x0A;5 Shenton Way, #22-04, UIC Building, Singapore 068808">Descartes
                            </option>
                            <option value="Klapton&#x0A;ACS 69, Mutsamudu, Autonomous Island of Anjouan, Union of Comoros">
                                Klapton</option>
                            <option
                                value="Allianz Commercial&#x0A;Office No. 66, 6th Floor, 3 North Avenue, Maker Maxity, Bandra Kurla Complex, Bandra (East), Mumbai – 400051, Maharashtra, India.">
                                Allianz Commercial</option>
                        </select>
                    </div>
                </div>


                <!-- Reinsurer Selection -->
                <div style="margin-top:1.2rem;">
                    <label for="selected_reinsurer" class="note-label">Select Reinsurer</label>

                    <select id="selected_reinsurer" name="selected_reinsurer" class="form-input" required
                        onchange="updateSelectedReinsurer()">
                        <option value="">Select Reinsurer</option>
                        @if ($quote->reinsurer)
                            @php
                                $reinsurers = json_decode($quote->reinsurer, true);
                            @endphp
                            @foreach ($reinsurers as $index => $reinsurer)
                                <option value="{{ $reinsurer['name'] }}" data-name="{{ $reinsurer['name'] }}"
                                    data-percentage="{{ $reinsurer['percentage'] }}">
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
                                placeholder="Enter total premium" step="0.01" min="0" oninput="calculatePremium()"
                                required>
                        </div>
                        <div style="flex:1 1 250px;">
                            <label for="ceding_commission" class="note-label">Ceding Commission (%)</label>
                            <input type="number" id="ceding_commission" name="ceding_commission" class="form-input"
                                placeholder="0" step="0.01" min="0" value="0" oninput="calculatePremium()">
                        </div>
                        <div style="flex:1 1 250px;">
                            <label for="ri_brokerage" class="note-label">RI Brokerage (%)</label>
                            <input type="number" id="ri_brokerage" name="ri_brokerage" class="form-input"
                                placeholder="0" step="0.01" min="0" value="0"
                                oninput="calculatePremium()">
                        </div>
                    </div>

                    <!-- Calculation Results -->
                    <div
                        style="margin-top:1.5rem; background:#f8f9fa; padding:1.5rem; border-radius:8px; border:1px solid #e3f0ff;">
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
                        @if ($quote->reinsurer_country == "India")
                        <div style="display:flex; justify-content:space-between; margin-bottom:0.5rem;">
                            <span id="gst_on_ri_brokerage_label">Less: GST on RI brokerage @ 18%</span>
                            <span id="display_gst_on_ri_brokerage" style="font-weight:600; color:#e74c3c;">0.00</span>
                        </div>
                        <div style="display:flex; justify-content:space-between; margin-bottom:0.5rem;">
                            <span id="tds_on_ri_brokerage_label">Add: TDS on RI brokerage @ 10%</span>
                            <span id="display_tds_on_ri_brokerage" style="font-weight:600; color:#e74c3c;">0.00</span>
                        </div>
                        @endif

                        <hr style="margin:1rem 0; border:1px solid #dee2e6;">

                        <div
                            style="display:flex; justify-content:space-between; font-weight:700; font-size:1.1rem; color:#2e3192;">
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
            color: #2e3192;
            font-weight: 600;
            margin-bottom: 0.2rem;
            display: block;
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

            // GST and TDS only if Indian reinsurer
            let gstOnRiBrokerage = 0;
            let tdsOnRiBrokerage = 0;
                let totalDue = reinsurerShare - cedingCommission - riBrokerage;
                if ('{{ $quote->reinsurer_country }}' === 'India') {
                    gstOnRiBrokerage = riBrokerage * 0.18;
                    tdsOnRiBrokerage = riBrokerage * 0.10;
                    totalDue = reinsurerShare - cedingCommission - riBrokerage - gstOnRiBrokerage + tdsOnRiBrokerage;
                }

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

            // GST & TDS display if Indian reinsurer
            if ('{{ $quote->reinsurer_country }}' === 'India') {
                document.getElementById('display_gst_on_ri_brokerage').textContent = formatCurrency(gstOnRiBrokerage);
                document.getElementById('display_tds_on_ri_brokerage').textContent = formatCurrency(tdsOnRiBrokerage);
            }
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
            if (document.getElementById('display_gst_on_ri_brokerage')) document.getElementById('display_gst_on_ri_brokerage').textContent = '0.00';
            if (document.getElementById('display_tds_on_ri_brokerage')) document.getElementById('display_tds_on_ri_brokerage').textContent = '0.00';
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
            let gstOnRiBrokerage = 0;
            let tdsOnRiBrokerage = 0;
            let totalDue = reinsurerShare - cedingCommission - riBrokerage;
            let particulars = {
                'Total premium': totalPremium,
                [`${selectedReinsurerData.name}'s share @ ${selectedReinsurerData.percentage}%`]: reinsurerShare,
                [`Less: Ceding Commission @ ${cedingCommissionPercent}%`]: -cedingCommission,
                [`Less: RI brokerage @ ${riBrokeragePercent}%`]: -riBrokerage
            };
            if ('{{ $quote->reinsurer_country }}' === 'India') {
                gstOnRiBrokerage = riBrokerage * 0.18;
                tdsOnRiBrokerage = riBrokerage * 0.10;
                particulars['Add: GST on RI brokerage @ 18%'] = -gstOnRiBrokerage;
                particulars['Less: TDS on RI brokerage @ 10%'] = tdsOnRiBrokerage;
                totalDue = reinsurerShare - cedingCommission - riBrokerage - gstOnRiBrokerage + tdsOnRiBrokerage;
            }
            particulars[`Total due to ${selectedReinsurerData.name}`] = totalDue;

            document.getElementById('particularsJson').value = JSON.stringify(particulars);
            return true;
        }
    </script>
@endsection
