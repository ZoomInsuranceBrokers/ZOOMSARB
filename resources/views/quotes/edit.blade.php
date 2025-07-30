@extends('layouts.app')

@section('title', 'Edit Quote')

@section('content')
    <div style="max-width: 1050px; margin: 0 auto;">
        <div
            style="background: linear-gradient(135deg, #e3f0ff 0%, #f7faff 100%); border-radius: 18px; box-shadow: 0 2px 16px #2e319222; padding: 2.5rem 2rem; margin-bottom: 2rem;">
            <h2 style="text-align:center; color:#2e3192; font-weight:700; margin-bottom:1.5rem;">Edit Quote Slip</h2>
            <form method="POST" action="{{ route('quotes.update', $quote->id) }}">
                @csrf
                @method('PUT')
                <div style="display: flex; flex-wrap: wrap; gap: 1.5rem;">
                    <div style="flex:1 1 400px;">
                        <label for="insured_name" style="color:#2e3192; font-weight:600;">Insured Name</label>
                        <input type="text" id="insured_name" name="insured_name" class="form-input"
                            value="{{ $quote->insured_name }}">
                    </div>
                    <div style="flex:1 1 400px;">
                        <label for="insured_address" style="color:#2e3192; font-weight:600;">Insured Address</label>
                        <input type="text" id="insured_address" name="insured_address" class="form-input"
                            value="{{ $quote->insured_address }}">
                    </div>
                </div>
                <div style="display: flex; flex-wrap: wrap; gap: 1.5rem; margin-top:1.2rem;">
                    <div style="flex:1 1 400px;">
                        <label for="policy_name" style="color:#2e3192; font-weight:600;">Policy Name</label>
                        <select id="policy_name" name="policy_name" class="form-input">
                            <option value="">Select Policy</option>
                            <option value="Terrorism and Sabotage and Terrorism Liability Insurance"
                                {{ $quote->policy_name == 'Terrorism and Sabotage and Terrorism Liability Insurance' ? 'selected' : '' }}>
                                Terrorism and Sabotage and Terrorism Liability Insurance</option>

                            <option value="Professional Indemnity"
                                {{ $quote->policy_name == 'Professional Indemnity' ? 'selected' : '' }}>Professional
                                Indemnity Policy</option>
                            <option value="Cyber Insurance"
                                {{ $quote->policy_name == 'Cyber Insurance' ? 'selected' : '' }}>Cyber Insurance</option>
                        </select>
                    </div>
                    <div style="flex:1 1 400px;">
                        <label style="color:#2e3192; font-weight:600;">Policy Period</label>
                        @php
                            $period = $quote->policy_period ? explode(' - ', $quote->policy_period) : [null, null];
                            function parseDateForInput($dateStr)
                            {
                                if (!$dateStr) {
                                    return '';
                                }
                                try {
                                    return \Carbon\Carbon::createFromFormat('d/m/Y', $dateStr)->format('Y-m-d');
                                } catch (\Exception $e) {
                                    return '';
                                }
                            }
                        @endphp
                        <div style="display: flex; gap: 0.5rem;">
                            <input type="date" id="policy_start_date" name="policy_start_date" class="form-input"
                                value="{{ parseDateForInput($period[0] ?? '') }}" style="flex:1;">
                            <span style="align-self:center;">to</span>
                            <input type="date" id="policy_end_date" name="policy_end_date" class="form-input"
                                value="{{ parseDateForInput($period[1] ?? '') }}" style="flex:1;">
                        </div>
                        <div style="margin-top:0.5rem;">
                            <input type="checkbox" id="policy_period_tba" name="policy_period_tba" value="1"
                                onchange="togglePolicyPeriodTBA(this)">
                            <label for="policy_period_tba" style="color:#2e3192; font-weight:500; margin-left:4px;">To Be
                                Advised</label>
                        </div>
                    </div>
                </div>
                <div style="display: flex; flex-wrap: wrap; gap: 1.5rem; margin-top:1.2rem;">
                    <div style="flex:1 1 400px;">
                        <label for="occupancy" style="color:#2e3192; font-weight:600;">Occupancy</label>
                        <input type="text" id="occupancy" name="occupancy" class="form-input"
                            value="{{ $quote->occupancy }}">
                    </div>
                    <div style="flex:1 1 400px;">
                        <label for="jurisdiction" style="color:#2e3192; font-weight:600;">Jurisdiction</label>
                        <input type="text" id="jurisdiction" name="jurisdiction" class="form-input"
                            value="{{ $quote->jurisdiction }}">
                    </div>
                </div>
                <!-- Risk Locations (multiple rows) -->
                <!-- Risk Locations (with Sum Insured, Upload/Download, Add More) -->
                {{-- <div style="margin-top:1.2rem;">
                    <label style="color:#2e3192; font-weight:600;">Risk Locations</label>

                    <!-- Download + Upload Buttons -->
                    <div style="margin-bottom:0.7rem; margin-top:1.2rem;">
                        <button type="button" onclick="downloadSampleCSV()"
                            style="background:#2e3192; color:#fff; border:none; border-radius:6px; padding:0.3rem 1.1rem; font-size:0.95rem; cursor:pointer;">
                            Download Sample CSV
                        </button>

                        <input type="file" id="riskLocationsCSV" accept=".csv"
                            style="margin-left:1rem; display:inline-block;" onchange="handleCSVUpload(this)"
                            class="form-input">
                        <label for="riskLocationsCSV"
                            style="color:#2e3192; font-weight:500; margin-left:6px; cursor:pointer;">Upload CSV</label>
                    </div>

                    <!-- Dynamic Input Rows -->
                    <div id="riskLocationsWrapper">
                        @php
                            $riskLocations = $quote->risk_locations
                                ? json_decode($quote->risk_locations, true)
                                : ['' => ''];
                        @endphp
                        @foreach ($riskLocations as $loc => $sum)
                            <div style="display:flex; gap:0.5rem; margin-bottom:0.5rem;">
                                <input type="text" name="risk_location[]" class="form-input" value="{{ $loc }}"
                                    placeholder="Enter risk location" required>
                                <input type="number" name="risk_sum_insured[]" class="form-input"
                                    value="{{ $sum }}" placeholder="Sum Insured" min="0" step="any"
                                    required>
                                <button type="button" onclick="removeRiskLocation(this)"
                                    style="background:#e74c3c; color:#fff; border:none; border-radius:6px; padding:0.3rem 0.8rem; font-size:1rem; cursor:pointer;">&times;</button>
                            </div>
                        @endforeach
                    </div>

                    <!-- Add More Button -->
                    <button type="button" onclick="addRiskLocation()"
                        style="margin-top:0.5rem; background:#2e3192; color:#fff; border:none; border-radius:6px; padding:0.4rem 1.2rem; font-size:0.95rem; cursor:pointer;">
                        Add More
                    </button>

                    <!-- Hidden JSON field -->
                    <input type="hidden" id="riskLocationsJson" name="risk_locations_json">
                </div> --}}

                <!-- Sum Insured Details -->
                <div style="display: flex; flex-wrap: wrap; gap: 1.5rem; margin-top:1.2rem;">
                    <div style="flex:1 1 300px;">
                        <label for="property_damage" style="color:#2e3192; font-weight:600;">Property Damage</label>
                        <input type="number" id="property_damage" name="property_damage" class="form-input" min="0"
                            step="any" value="{{ $quote->property_damage }}" oninput="updateTotalSumInsured()">
                    </div>
                    <div style="flex:1 1 300px;">
                        <label for="business_interruption" style="color:#2e3192; font-weight:600;">Business
                            Interruption</label>
                        <input type="number" id="business_interruption" name="business_interruption" class="form-input"
                            min="0" step="any" value="{{ $quote->business_interruption }}"
                            oninput="updateTotalSumInsured()">
                    </div>
                    <div style="flex:1 1 300px;">
                        <label for="total_sum_insured" style="color:#2e3192; font-weight:600;">Total Sum Insured</label>
                        <input type="number" id="total_sum_insured" name="total_sum_insured" class="form-input"
                            value="{{ $quote->total_sum_insured }}" readonly style="background:#e3f0ff;">
                    </div>
                </div>
                <div style="margin-top:1.2rem;">
                    <label for="coverage" style="color:#2e3192; font-weight:600;">Coverage</label>
                    <input type="text" id="coverage" name="coverage" class="form-input"
                        value="{{ $quote->coverage }}">
                </div>
                <!-- Limit of Indemnity (multiple rows) -->
                <div style="margin-top:1.2rem;">
                    <label style="color:#2e3192; font-weight:600;">Limit of Indemnity</label>
                    <div id="indemnityLimitsWrapper">
                        @php
                            $limits = $quote->limit_of_indemnity ? json_decode($quote->limit_of_indemnity, true) : [''];
                        @endphp
                        @foreach ($limits as $lim)
                            <input type="text" name="limit_of_indemnity[]" class="form-input"
                                value="{{ $lim }}" placeholder="Enter limit of indemnity">
                        @endforeach
                    </div>
                    <button type="button" onclick="addIndemnityLimit()"
                        style="margin-top:0.5rem; background:#2e3192; color:#fff; border:none; border-radius:6px; padding:0.4rem 1.2rem; font-size:0.95rem; cursor:pointer;">Add
                        More</button>
                </div>
                <div style="display: flex; flex-wrap: wrap; gap: 1.5rem; margin-top:1.2rem;">
                    <div style="flex:1 1 400px;">
                        <label for="indemnity_period" style="color:#2e3192; font-weight:600;">Indemnity Period (Business
                            Interruption)</label>
                        <input type="text" id="indemnity_period" name="indemnity_period" class="form-input"
                            value="{{ $quote->indemnity_period }}">
                    </div>
                    <div style="flex:1 1 400px;">
                        <label style="color:#2e3192; font-weight:600;">Additional Covers Opted</label>
                        <div id="additionalCoversWrapper">
                            @php
                                $additionalCovers = $quote->additional_covers
                                    ? json_decode($quote->additional_covers, true)
                                    : [''];
                            @endphp
                            @foreach ($additionalCovers as $cover)
                                <input type="text" name="additional_covers[]" class="form-input"
                                    value="{{ $cover }}" placeholder="Enter additional cover">
                            @endforeach
                        </div>
                        <button type="button" onclick="addAdditionalCover()"
                            style="margin-top:0.5rem; background:#2e3192; color:#fff; border:none; border-radius:6px; padding:0.4rem 1.2rem; font-size:0.95rem; cursor:pointer;">
                            Add More
                        </button>
                    </div>
                </div>
                <div style="margin-top:1.2rem;">
                    <label for="claims" style="color:#2e3192; font-weight:600;">Claims</label>
                    <textarea id="claims" name="claims" class="form-input" rows="2">{{ $quote->claims }}</textarea>
                </div>
                <div style="display: flex; flex-wrap: wrap; gap: 1.5rem; margin-top:1.2rem;">
                    <div style="flex:1 1 400px;">
                        <label style="color:#2e3192; font-weight:600;">Deductibles</label>
                        <div id="deductiblesWrapper">
                            @php
                                $deductibles = $quote->deductibles ? json_decode($quote->deductibles, true) : [''];
                            @endphp
                            @foreach ($deductibles as $deductible)
                                <input type="text" name="deductibles[]" class="form-input"
                                    value="{{ $deductible }}" placeholder="Enter deductible">
                            @endforeach
                        </div>
                        <button type="button" onclick="addDeductible()"
                            style="margin-top:0.5rem; background:#2e3192; color:#fff; border:none; border-radius:6px; padding:0.4rem 1.2rem; font-size:0.95rem; cursor:pointer;">
                            Add More
                        </button>
                    </div>
                    <div style="flex:1 1 400px;">
                        <label for="premium" style="color:#2e3192; font-weight:600;">Premium</label>
                        <input type="text" id="premium" name="premium" class="form-input"
                            value="{{ $quote->premium }}">
                    </div>
                </div>
                <div style="margin-top:1.2rem;">
                    <label for="support" style="color:#2e3192; font-weight:600;">Support</label>
                    <input type="text" id="support" name="support" class="form-input"
                        value="{{ $quote->support }}">
                </div>
                <!-- Remark Fields -->
                <div style="margin-top:1.5rem;">
                    <div style="display: flex; flex-wrap: wrap; gap: 1.5rem;">
                        <div style="flex:1 1 300px;">
                            <label for="remark1" style="color:#2e3192; font-weight:600;">Remark 1</label>
                            <textarea id="remark1" name="remark1" class="form-input" rows="2">{{ $quote->remark1 }}</textarea>
                        </div>
                        <div style="flex:1 1 300px;">
                            <label for="remark2" style="color:#2e3192; font-weight:600;">Remark 2</label>
                            <textarea id="remark2" name="remark2" class="form-input" rows="2">{{ $quote->remark2 }}</textarea>
                        </div>
                        <div style="flex:1 1 300px;">
                            <label for="remark3" style="color:#2e3192; font-weight:600;">Remark 3</label>
                            <textarea id="remark3" name="remark3" class="form-input" rows="2">{{ $quote->remark3 }}</textarea>
                        </div>
                    </div>
                </div>
                <div style="margin-top:1.5rem;">
                    <div style="display: flex; flex-wrap: wrap; gap: 1.5rem;">
                        <div style="flex:1 1 300px;">
                            <label for="is_final_submit" style="color:#2e3192; font-weight:600;">Is Converted?</label>
                            <select id="is_final_submit" name="is_final_submit" class="form-input">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                        </div>
                    </div>

                    <!-- Hidden Fields (Sedant & Reinsurer) -->
                    <div id="conversionFields" style="display:none; margin-top:1rem; flex-wrap: wrap; gap: 1.5rem;">
                        <div style="flex:1 1 300px;">
                            <label for="cedant_name" style="color:#2e3192; font-weight:600;">Cedant Name</label>
                            <input type="text" name="cedant" id="cedant_name" class="form-input"
                                placeholder="Enter Cedant Name" value="{{ $quote->cedant ?? '' }}">
                        </div>

                        <div style="flex:1 1 300px;">
                            <label for="brokerage_percentage" style="color:#2e3192; font-weight:600;">Our Brokerage
                                Percentage</label>
                            <input type="number" name="brokerage_percentage" id="brokerage_percentage"
                                class="form-input" placeholder="100" value="{{ $quote->brokerage_percentage ?? 100 }}"
                                min="0" max="100" step="0.01">
                        </div>

                        <div style="flex:1 1 100%; margin-top:1rem;">
                            <label style="color:#2e3192; font-weight:600;">Reinsurer Names & Business Share</label>
                            <div id="reinsurersWrapper">
                                @php
                                    $reinsurers = $quote->reinsurers
                                        ? json_decode($quote->reinsurers, true)
                                        : [['name' => '', 'percentage' => '']];
                                @endphp
                                @foreach ($reinsurers as $reinsurer)
                                    <div style="display:flex; gap:0.5rem; margin-bottom:0.5rem; align-items:center;">
                                        <input type="text" name="reinsurer_names[]" class="form-input"
                                            value="{{ is_array($reinsurer) ? $reinsurer['name'] : $reinsurer }}"
                                            placeholder="Enter Reinsurer Name" style="flex:2;">
                                        <input type="number" name="reinsurer_percentages[]" class="form-input"
                                            value="{{ is_array($reinsurer) ? $reinsurer['percentage'] : '' }}"
                                            placeholder="%" min="0" max="100" step="0.01"
                                            style="flex:1;">
                                        <span style="color:#2e3192; font-weight:500; font-size:0.9rem;">%</span>
                                        <button type="button" onclick="removeReinsurer(this)"
                                            style="background:#e74c3c; color:#fff; border:none; border-radius:6px; padding:0.3rem 0.8rem; font-size:1rem; cursor:pointer;">&times;</button>
                                    </div>
                                @endforeach
                            </div>
                            <div style="margin-top:0.5rem; display:flex; gap:1rem; align-items:center;">
                                <button type="button" onclick="addReinsurer()"
                                    style="background:#2e3192; color:#fff; border:none; border-radius:6px; padding:0.4rem 1.2rem; font-size:0.95rem; cursor:pointer;">
                                    Add More Reinsurer
                                </button>
                                <div id="totalPercentage" style="color:#2e3192; font-weight:600; font-size:0.95rem;">
                                    Total: <span id="totalPercent">0</span>%
                                </div>
                            </div>
                            <input type="hidden" name="reinsurers_json" id="reinsurersJson">
                        </div>


                        <script>
                            // Add more reinsurers with percentage
                            function addReinsurer() {
                                const wrapper = document.getElementById('reinsurersWrapper');
                                const div = document.createElement('div');
                                div.style.display = 'flex';
                                div.style.gap = '0.5rem';
                                div.style.marginBottom = '0.5rem';
                                div.style.alignItems = 'center';
                                div.innerHTML = `
        <input type="text" name="reinsurer_names[]" class="form-input" placeholder="Enter Reinsurer Name" style="flex:2;">
        <input type="number" name="reinsurer_percentages[]" class="form-input" placeholder="%" min="0" max="100" step="0.01" style="flex:1;" oninput="calculateTotal()">
        <span style="color:#2e3192; font-weight:500; font-size:0.9rem;">%</span>
        <button type="button" onclick="removeReinsurer(this)" style="background:#e74c3c; color:#fff; border:none; border-radius:6px; padding:0.3rem 0.8rem; font-size:1rem; cursor:pointer;">&times;</button>
    `;
                                wrapper.appendChild(div);
                                calculateTotal();
                            }

                            function removeReinsurer(btn) {
                                btn.parentElement.remove();
                                calculateTotal();
                            }

                            // Calculate total percentage
                            function calculateTotal() {
                                const percentages = document.getElementsByName('reinsurer_percentages[]');
                                let total = 0;
                                for (let i = 0; i < percentages.length; i++) {
                                    const value = parseFloat(percentages[i].value) || 0;
                                    total += value;
                                }
                                document.getElementById('totalPercent').textContent = total.toFixed(2);

                                // Color coding for total
                                const totalElement = document.getElementById('totalPercentage');
                                if (total > 100) {
                                    totalElement.style.color = '#e74c3c'; // Red if over 100%
                                } else if (total === 100) {
                                    totalElement.style.color = '#27ae60'; // Green if exactly 100%
                                } else {
                                    totalElement.style.color = '#2e3192'; // Blue if under 100%
                                }
                            }

                            // Add event listeners to existing percentage inputs
                            document.addEventListener('DOMContentLoaded', function() {
                                // Add event listeners to existing inputs
                                const existingPercentages = document.getElementsByName('reinsurer_percentages[]');
                                for (let i = 0; i < existingPercentages.length; i++) {
                                    existingPercentages[i].addEventListener('input', calculateTotal);
                                }

                                // Calculate initial total
                                calculateTotal();

                                // Prepare JSON on form submit
                                document.querySelector('form').addEventListener('submit', function(e) {
                                    const names = document.getElementsByName('reinsurer_names[]');
                                    const percentages = document.getElementsByName('reinsurer_percentages[]');
                                    let reinsurersArray = [];

                                    for (let i = 0; i < names.length; i++) {
                                        const name = names[i].value.trim();
                                        const percentage = parseFloat(percentages[i].value) || 0;
                                        if (name !== '') {
                                            reinsurersArray.push({
                                                name: name,
                                                percentage: percentage
                                            });
                                        }
                                    }

                                    document.getElementById('reinsurersJson').value = JSON.stringify(reinsurersArray);
                                });
                            });

                            // ...existing JavaScript functions...
                        </script>
                    </div>

                </div>
                <div style="text-align:center; margin-top:2rem;">
                    <button type="submit"
                        style="background: linear-gradient(90deg, #2e3192 0%, #1bffff 100%); color: #fff; border: none; border-radius: 8px; padding: 0.9rem 2.5rem; font-size: 1.1rem; font-weight: 700; cursor: pointer; box-shadow: 0 2px 8px #2e319222; transition: background 0.2s;">
                        <i class="fas fa-save"></i> Update Quote
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
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const select = document.getElementById('is_final_submit');
            const fields = document.getElementById('conversionFields');

            function toggleFields() {
                if (select.value === "1") {
                    fields.style.display = "flex";
                } else {
                    fields.style.display = "none";
                }
            }

            // Run on page load (in case of edit)
            toggleFields();

            // Run on change
            select.addEventListener('change', toggleFields);
        });
    </script>

    <script>
        // Add more indemnity limits
        function addIndemnityLimit() {
            const wrapper = document.getElementById('indemnityLimitsWrapper');
            const input = document.createElement('input');
            input.type = 'text';
            input.name = 'limit_of_indemnity[]';
            input.className = 'form-input';
            input.placeholder = 'Enter limit of indemnity';
            input.style.marginTop = '0.5rem';
            wrapper.appendChild(input);
        }
        // Add more additional covers
        function addAdditionalCover() {
            const wrapper = document.getElementById('additionalCoversWrapper');
            const input = document.createElement('input');
            input.type = 'text';
            input.name = 'additional_covers[]';
            input.className = 'form-input';
            input.placeholder = 'Enter additional cover';
            input.style.marginTop = '0.5rem';
            wrapper.appendChild(input);
        }
        // Add more deductibles
        function addDeductible() {
            const wrapper = document.getElementById('deductiblesWrapper');
            const input = document.createElement('input');
            input.type = 'text';
            input.name = 'deductibles[]';
            input.className = 'form-input';
            input.placeholder = 'Enter deductible';
            input.style.marginTop = '0.5rem';
            wrapper.appendChild(input);
        }
        // Update total sum insured
        function updateTotalSumInsured() {
            const pd = parseFloat(document.getElementById('property_damage').value) || 0;
            const bi = parseFloat(document.getElementById('business_interruption').value) || 0;
            document.getElementById('total_sum_insured').value = pd + bi;
        }
    </script>
    <script>
        // Add more reinsurers

        // Show/hide conversion fields
        document.addEventListener('DOMContentLoaded', function() {
            const isConvertedSelect = document.getElementById('is_final_submit');
            const conversionFields = document.getElementById('conversionFields');

            isConvertedSelect.addEventListener('change', function() {
                if (this.value == '1') {
                    conversionFields.style.display = 'flex';
                } else {
                    conversionFields.style.display = 'none';
                }
            });

            // Set initial state
            if (isConvertedSelect.value == '1') {
                conversionFields.style.display = 'flex';
            }
        });

        // ...existing JavaScript functions...
    </script>
    <script>
        // Download sample CSV
        function downloadSampleCSV() {
            const csv = "Risk Location,Sum Insured\nLocation 1,1000000\nLocation 2,2000000";
            const blob = new Blob([csv], {
                type: 'text/csv'
            });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = "risk_locations_sample.csv";
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);
        }

        // Handle CSV upload
        function handleCSVUpload(input) {
            const file = input.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = function(e) {
                const lines = e.target.result.split('\n').map(l => l.trim()).filter(l => l.length);
                // Remove header if present
                let start = 0;
                if (lines[0].toLowerCase().includes('risk location')) start = 1;
                // Remove all current fields except the first one
                const wrapper = document.getElementById('riskLocationsWrapper');
                wrapper.innerHTML = '';
                for (let i = start; i < lines.length; i++) {
                    const parts = lines[i].split(',');
                    if (parts.length >= 2) {
                        const div = document.createElement('div');
                        div.style.display = 'flex';
                        div.style.gap = '0.5rem';
                        div.style.marginBottom = '0.5rem';
                        div.innerHTML = `
                    <input type="text" name="risk_location[]" class="form-input" placeholder="Enter risk location" value="${parts[0].trim()}" required>
                    <input type="number" name="risk_sum_insured[]" class="form-input" placeholder="Sum Insured" min="0" step="any" value="${parts[1].trim()}" required>
                    <button type="button" onclick="removeRiskLocation(this)" style="background:#e74c3c; color:#fff; border:none; border-radius:6px; padding:0.3rem 0.8rem; font-size:1rem; cursor:pointer;">&times;</button>
                `;
                        wrapper.appendChild(div);
                    }
                }
            };
            reader.readAsText(file);
        }
    </script>
    <script>
        function addRiskLocation() {
            const wrapper = document.getElementById('riskLocationsWrapper');
            const div = document.createElement('div');
            div.style.display = 'flex';
            div.style.gap = '0.5rem';
            div.style.marginBottom = '0.5rem';
            div.innerHTML = `
        <input type="text" name="risk_location[]" class="form-input" placeholder="Enter risk location" required>
        <input type="number" name="risk_sum_insured[]" class="form-input" placeholder="Sum Insured" min="0" step="any" required>
        <button type="button" onclick="removeRiskLocation(this)" style="background:#e74c3c; color:#fff; border:none; border-radius:6px; padding:0.3rem 0.8rem; font-size:1rem; cursor:pointer;">&times;</button>
    `;
            wrapper.appendChild(div);
        }

        function removeRiskLocation(btn) {
            btn.parentElement.remove();
        }

        // On form submit, build JSON and put in hidden input
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelector('form').addEventListener('submit', function(e) {
                const tba = document.getElementById('policy_period_tba');
                if (tba && tba.checked) {
                    document.getElementById('policy_start_date').value = '';
                    document.getElementById('policy_end_date').value = '';
                }

                const locations = document.getElementsByName('risk_location[]');
                const sums = document.getElementsByName('risk_sum_insured[]');
                const hidden = document.getElementById('riskLocationsJson');

                let arr = [];

                for (let i = 0; i < locations.length; i++) {
                    const loc = locations[i].value.trim();
                    const sum = sums[i].value.trim();
                    if (loc !== '') {
                        arr.push({
                            location: loc,
                            sum_insured: sum
                        });
                    }
                }

                console.log("Final JSON:", arr);
                if (hidden) {
                    hidden.value = arr.length ? JSON.stringify(arr) : '';
                }
            });
        });
    </script>
@endsection
