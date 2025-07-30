@extends('layouts.app')

@section('title', 'Add New Quote Slip')

@section('content')
    <div style="max-width: 1050px; margin: 0 auto;">
        <div
            style="background: linear-gradient(135deg, #e3f0ff 0%, #f7faff 100%); border-radius: 18px; box-shadow: 0 2px 16px #2e319222; padding: 2.5rem 2rem; margin-bottom: 2rem; animation: fadeInDown 1s;">
            <h2 style="text-align:center; color:#2e3192; font-weight:700; margin-bottom:1.5rem;">Add New Quote Slip</h2>
            <form method="POST" action="{{ route('quotes.store') }}">
                @csrf
                <div style="display: flex; flex-wrap: wrap; gap: 1.5rem;">
                    <div style="flex:1 1 400px;">
                        <label for="insured_name" style="color:#2e3192; font-weight:600;">Insured Name</label>
                        <input type="text" id="insured_name" name="insured_name" class="form-input">
                    </div>
                    <div style="flex:1 1 400px;">
                        <label for="insured_address" style="color:#2e3192; font-weight:600;">Insured Address</label>
                        <input type="text" id="insured_address" name="insured_address" class="form-input">
                    </div>
                </div>
                <div style="display: flex; flex-wrap: wrap; gap: 1.5rem; margin-top:1.2rem;">
                    <div style="flex:1 1 400px;">
                        <label for="policy_name" style="color:#2e3192; font-weight:600;">Policy Name</label>
                        <select id="policy_name" name="policy_name" class="form-input">
                            <option value="">Select Policy</option>
                            <option value="Terrorism and Sabotage and Terrorism Liability Insurance">Terrorism and Sabotage and Terrorism Liability Insurance</option>
                            <option value="Professional Indemnity">Professional Indemnity Policy</option>
                            <option value="Cyber Insurance">Cyber Insurance</option>
                        </select>
                    </div>
                    <div style="flex:1 1 400px;">
                        <label style="color:#2e3192; font-weight:600;">Policy Period</label>
                        <div style="display: flex; gap: 0.5rem;">
                            <input type="date" id="policy_start_date" name="policy_start_date" class="form-input"
                                style="flex:1;" required>
                            <span style="align-self:center;">to</span>
                            <input type="date" id="policy_end_date" name="policy_end_date" class="form-input"
                                style="flex:1;" required>
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
                        <input type="text" id="occupancy" name="occupancy" class="form-input">
                    </div>
                    <div style="flex:1 1 400px;">
                        <label for="jurisdiction" style="color:#2e3192; font-weight:600;">Jurisdiction</label>
                        <input type="text" id="jurisdiction" name="jurisdiction" class="form-input">
                    </div>
                </div>
                <!-- Risk Locations (multiple rows) -->

                {{-- <div style="margin-top:1.2rem;">
                    <label style="color:#2e3192; font-weight:600;">Risk Locations</label>
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
                    <div id="riskLocationsWrapper">
                        <div style="display:flex; gap:0.5rem; margin-bottom:0.5rem;">
                            <input type="text" name="risk_location[]" class="form-input"
                                placeholder="Enter risk location" required>
                            <input type="number" name="risk_sum_insured[]" class="form-input" placeholder="Sum Insured"
                                min="0" step="any">
                            <button type="button" onclick="removeRiskLocation(this)"
                                style="background:#e74c3c; color:#fff; border:none; border-radius:6px; padding:0.3rem 0.8rem; font-size:1rem; cursor:pointer;">&times;</button>
                        </div>
                    </div>
                    <button type="button" onclick="addRiskLocation()"
                        style="margin-top:0.5rem; background:#2e3192; color:#fff; border:none; border-radius:6px; padding:0.4rem 1.2rem; font-size:0.95rem; cursor:pointer;">
                        Add More
                    </button>
                    <input type="hidden" id="riskLocationsJson" name="risk_locations_json">
                </div> --}}
                <!-- Sum Insured Details -->
                <div style="display: flex; flex-wrap: wrap; gap: 1.5rem; margin-top:1.2rem;">
                    <div style="flex:1 1 300px;">
                        <label for="property_damage" style="color:#2e3192; font-weight:600;">Property Damage</label>
                        <input type="number" id="property_damage" name="property_damage" class="form-input"
                            min="0" step="any" oninput="updateTotalSumInsured()">
                    </div>
                    <div style="flex:1 1 300px;">
                        <label for="business_interruption" style="color:#2e3192; font-weight:600;">Business
                            Interruption</label>
                        <input type="number" id="business_interruption" name="business_interruption" class="form-input"
                            min="0" step="any" oninput="updateTotalSumInsured()">
                    </div>
                    <div style="flex:1 1 300px;">
                        <label for="total_sum_insured" style="color:#2e3192; font-weight:600;">Total Sum Insured</label>
                        <input type="number" id="total_sum_insured" name="total_sum_insured" class="form-input"
                            readonly style="background:#e3f0ff;">
                    </div>
                </div>
                <div style="margin-top:1.2rem;">
                    <label for="coverage" style="color:#2e3192; font-weight:600;">Coverage</label>
                    <input type="text" id="coverage" name="coverage" class="form-input">
                </div>
                <!-- Limit of Indemnity (multiple rows) -->
                <div style="margin-top:1.2rem;">
                    <label style="color:#2e3192; font-weight:600;">Limit of Indemnity</label>
                    <div id="indemnityLimitsWrapper">
                        <input type="text" name="limit_of_indemnity[]" class="form-input"
                            placeholder="Enter limit of indemnity">
                    </div>
                    <button type="button" onclick="addIndemnityLimit()"
                        style="margin-top:0.5rem; background:#2e3192; color:#fff; border:none; border-radius:6px; padding:0.4rem 1.2rem; font-size:0.95rem; cursor:pointer;">Add
                        More</button>
                </div>
                <div style="display: flex; flex-wrap: wrap; gap: 1.5rem; margin-top:1.2rem;">
                    <div style="flex:1 1 400px;">
                        <label for="indemnity_period" style="color:#2e3192; font-weight:600;">Indemnity Period (Business
                            Interruption)</label>
                        <input type="text" id="indemnity_period" name="indemnity_period" class="form-input">
                    </div>
                    <div style="flex:1 1 400px;">
                        <label style="color:#2e3192; font-weight:600;">Additional Covers Opted</label>
                        <div id="additionalCoversWrapper">
                            <input type="text" name="additional_covers[]" class="form-input"
                                placeholder="Enter additional cover">
                        </div>
                        <button type="button" onclick="addAdditionalCover()"
                            style="margin-top:0.5rem; background:#2e3192; color:#fff; border:none; border-radius:6px; padding:0.4rem 1.2rem; font-size:0.95rem; cursor:pointer;">
                            Add More
                        </button>
                    </div>
                </div>
                <div style="margin-top:1.2rem;">
                    <label for="claims" style="color:#2e3192; font-weight:600;">Claims</label>
                    <textarea id="claims" name="claims" class="form-input" rows="2"></textarea>
                </div>
                <div style="display: flex; flex-wrap: wrap; gap: 1.5rem; margin-top:1.2rem;">
                    <div style="flex:1 1 400px;">
                        <label style="color:#2e3192; font-weight:600;">Deductibles</label>
                        <div id="deductiblesWrapper">
                            <input type="text" name="deductibles[]" class="form-input"
                                placeholder="Enter deductible">
                        </div>
                        <button type="button" onclick="addDeductible()"
                            style="margin-top:0.5rem; background:#2e3192; color:#fff; border:none; border-radius:6px; padding:0.4rem 1.2rem; font-size:0.95rem; cursor:pointer;">
                            Add More
                        </button>
                    </div>
                    <div style="flex:1 1 400px;">
                        <label for="premium" style="color:#2e3192; font-weight:600;">Premium</label>
                        <input type="text" id="premium" name="premium" class="form-input">
                    </div>
                </div>
                <div style="margin-top:1.2rem;">
                    <label for="support" style="color:#2e3192; font-weight:600;">Support</label>
                    <input type="text" id="support" name="support" class="form-input">
                </div>
                <!-- Add Remark Button and Remark Fields -->
                <div style="text-align:center; margin-top:2rem;">
                    <button type="button" id="addRemarkBtn"
                        style="background: linear-gradient(90deg, #2e3192 0%, #1bffff 100%); color: #fff; border: none; border-radius: 8px; padding: 0.7rem 2.2rem; font-size: 1.05rem; font-weight: 600; cursor: pointer; box-shadow: 0 2px 8px #2e319222; transition: background 0.2s;">
                        <i class="fas fa-comment"></i> Add Remark
                    </button>
                </div>
                <div id="remarksSection" style="display:none; margin-top:1.5rem;">
                    <div style="display: flex; flex-wrap: wrap; gap: 1.5rem;">
                        <div style="flex:1 1 300px;">
                            <label for="remark1" style="color:#2e3192; font-weight:600;">Remark 1</label>
                            <textarea id="remark1" name="remark1" class="form-input" rows="2"></textarea>
                        </div>
                        <div style="flex:1 1 300px;">
                            <label for="remark2" style="color:#2e3192; font-weight:600;">Remark 2</label>
                            <textarea id="remark2" name="remark2" class="form-input" rows="2"></textarea>
                        </div>
                        <div style="flex:1 1 300px;">
                            <label for="remark3" style="color:#2e3192; font-weight:600;">Remark 3</label>
                            <textarea id="remark3" name="remark3" class="form-input" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div style="text-align:center; margin-top:2rem;">
                    <button type="submit"
                        style="background: linear-gradient(90deg, #2e3192 0%, #1bffff 100%); color: #fff; border: none; border-radius: 8px; padding: 0.9rem 2.5rem; font-size: 1.1rem; font-weight: 700; cursor: pointer; box-shadow: 0 2px 8px #2e319222; transition: background 0.2s;">
                        <i class="fas fa-save"></i> Save Quote
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

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
    <script>
        function togglePolicyPeriodTBA(checkbox) {
            const start = document.getElementById('policy_start_date');
            const end = document.getElementById('policy_end_date');
            if (checkbox.checked) {
                start.value = '';
                end.value = '';
                start.disabled = true;
                end.disabled = true;
            } else {
                start.disabled = false;
                end.disabled = false;
            }
        }
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
        // Update total sum insured
        function updateTotalSumInsured() {
            const pd = parseFloat(document.getElementById('property_damage').value) || 0;
            const bi = parseFloat(document.getElementById('business_interruption').value) || 0;
            document.getElementById('total_sum_insured').value = pd + bi;
        }
        // Show remarks section
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('addRemarkBtn').onclick = function() {
                document.getElementById('remarksSection').style.display = 'block';
                this.style.display = 'none';
            }
        });

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
