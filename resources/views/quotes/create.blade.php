@extends('layouts.app')

@section('title', 'Add New Quote Slip')

@section('content')
    <div style="max-width: 1050px; margin: 0 auto;">
        <div
            style="background: linear-gradient(135deg, #e3f0ff 0%, #f7faff 100%); border-radius: 18px; box-shadow: 0 2px 16px #2e319222; padding: 2.5rem 2rem; margin-bottom: 2rem; animation: fadeInDown 1s;">
            <h2 style="text-align:center; color:#2e3192; font-weight:700; margin-bottom:1.5rem;">Add New Quote Slip</h2>
            <form method="POST" action="{{ route('quotes.store') }}">
                @csrf
                <script>
                    function updateCurrencyLabels() {
                        var currencySelect = document.getElementById('currency');
                        var selected = currencySelect.options[currencySelect.selectedIndex].text;
                        var currencyLabels = document.querySelectorAll('.currency-label');
                        var placeholders = {
                            'INR': 'INR',
                            'Dollar': 'Dollar',
                            'Euro': 'Euro'
                        };
                        currencyLabels.forEach(function(label) {
                            label.textContent = placeholders[selected];
                        });
                        // Update placeholders for number inputs
                        var numberInputs = document.querySelectorAll('input[type="number"]');
                        numberInputs.forEach(function(input) {
                            if (input.placeholder) {
                                input.placeholder = input.placeholder.replace(/Rupee|Dollar|Euro/, placeholders[selected]);
                            }
                        });
                    }
                    document.addEventListener('DOMContentLoaded', function() {
                        updateCurrencyLabels();
                    });
                </script>
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
                            <option value="Terrorism and Sabotage and Terrorism Liability Insurance">Terrorism and Sabotage
                                and Terrorism Liability Insurance</option>
                            <option value="Professional Indemnity">Professional Indemnity Policy</option>
                            <option value="Cyber Insurance">Cyber Insurance</option>
                            <option value="Political Violence Insurance">Political Violence Insurance</option>

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
                    <!-- Currency Selection -->
                    <div style="flex:1 1 400px;">
                        <label for="currency" style="color:#2e3192; font-weight:600;">Currency Type</label>
                        <select id="currency" name="currency_type" class="form-input" onchange="updateCurrencyLabels()">
                            <option value="INR">INR</option>
                            {{-- <option value="USD">Dollar</option>
                            <option value="EUR">Euro</option> --}}
                        </select>
                    </div>
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
                <div style="margin-top:1.2rem;">
                    <label style="color:#2e3192; font-weight:600;">Risk Locations</label>
                    <div style="margin-bottom:0.7rem;">
                        <input type="checkbox" id="riskLocationAnnexure" name="risk_location_as_per_annexure" value="1"
                            onchange="toggleRiskLocationAnnexure()">
                        <label for="riskLocationAnnexure" style="color:#2e3192; font-weight:500; margin-left:4px;">As per
                            Annexure</label>
                    </div>
                    <!-- Field Headers -->
                    <div style="display:flex; flex-wrap:wrap; gap:0.5rem; margin-bottom:0.5rem; padding:0.5rem; background:#e3f0ff; border-radius:6px; font-weight:600; color:#2e3192; font-size:0.9rem;">
                        <div style="flex:1 1 200px;">Risk Location</div>
                        <div style="flex:1 1 150px;">Property Damage</div>
                        <div style="flex:1 1 150px;">Business Interruption</div>
                        <div style="flex:1 1 150px;">Total Sum Insured</div>
                        <div style="width:40px;">Action</div>
                    </div>
                    <div id="riskLocationsWrapper">
                        <div style="display:flex; flex-wrap:wrap; gap:0.5rem; margin-bottom:1rem; padding:1rem; border:1px solid #e3f0ff; border-radius:8px; background:#f7faff;">
                            <input type="text" name="risk_location[]" class="form-input" style="flex:1 1 200px;"
                                placeholder="Enter risk location">
                            <input type="number" name="risk_property_damage[]" class="form-input" style="flex:1 1 150px;" placeholder="Property Damage"
                                min="0" step="any" oninput="calculateRiskTotal(this)">
                            <input type="number" name="risk_business_interruption[]" class="form-input" style="flex:1 1 150px;" placeholder="Business Interruption"
                                min="0" step="any" oninput="calculateRiskTotal(this)">
                            <input type="number" name="risk_total_sum[]" class="form-input" style="flex:1 1 150px;" placeholder="Total Sum Insured"
                                readonly style="background:#e3f0ff;">
                            <button type="button" onclick="removeRiskLocation(this)"
                                style="background:#e74c3c; color:#fff; border:none; border-radius:6px; padding:0.3rem 0.8rem; font-size:1rem; cursor:pointer;">&times;</button>
                        </div>
                    </div>
                    <button type="button" onclick="addRiskLocation()"
                        style="margin-top:0.5rem; background:#2e3192; color:#fff; border:none; border-radius:6px; padding:0.4rem 1.2rem; font-size:0.95rem; cursor:pointer;">Add
                        More</button>
                    <input type="hidden" id="riskLocationsJson" name="risk_locations_json">
                    <input type="hidden" id="riskLocationAnnexureHidden" name="risk_location_as_per_annexure"
                        value="0">
                </div>
                <!-- Sum Insured Details -->
                <div style="display: flex; flex-wrap: wrap; gap: 1.5rem; margin-top:1.2rem;">
                    <div style="flex:1 1 300px;">
                        <label for="property_damage" style="color:#2e3192; font-weight:600;">Property Damage (<span
                                class="currency-label">Rupee</span>)</label>
                        <input type="number" id="property_damage" name="property_damage" class="form-input"
                            min="0" step="any" oninput="updateTotalSumInsured()"
                            placeholder="Enter amount in Rupee">
                    </div>
                    <div style="flex:1 1 300px;">
                        <label for="business_interruption" style="color:#2e3192; font-weight:600;">Business Interruption
                            (<span class="currency-label">Rupee</span>)</label>
                        <input type="number" id="business_interruption" name="business_interruption" class="form-input"
                            min="0" step="any" oninput="updateTotalSumInsured()"
                            placeholder="Enter amount in Rupee">
                    </div>
                    <div style="flex:1 1 300px;">
                        <label for="total_sum_insured" style="color:#2e3192; font-weight:600;">Total Sum Insured (<span
                                class="currency-label">Rupee</span>)</label>
                        <input type="number" id="total_sum_insured" name="total_sum_insured" class="form-input"
                            readonly style="background:#e3f0ff;" placeholder="Amount in Rupee">
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

        function toggleRiskLocationAnnexure() {
            var annexureCheckbox = document.getElementById('riskLocationAnnexure');
            var wrapper = document.getElementById('riskLocationsWrapper');
            var hidden = document.getElementById('riskLocationAnnexureHidden');
            if (annexureCheckbox.checked) {
                wrapper.style.display = 'none';
                hidden.value = '1';
                // Clear all risk location fields
                var inputs = wrapper.querySelectorAll('input');
                inputs.forEach(function(input) {
                    input.value = '';
                });
            } else {
                wrapper.style.display = '';
                hidden.value = '0';
            }
        }
    </script>
    <script>
        // Download sample CSV
        function downloadSampleCSV() {
            const csv = "Risk Location,Property Damage,Business Interruption,Total Sum Insured\nLocation 1,800000,200000,1000000\nLocation 2,1500000,500000,2000000";
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
                    if (parts.length >= 3) {
                        const div = document.createElement('div');
                        div.style.display = 'flex';
                        div.style.flexWrap = 'wrap';
                        div.style.gap = '0.5rem';
                        div.style.marginBottom = '1rem';
                        div.style.padding = '1rem';
                        div.style.border = '1px solid #e3f0ff';
                        div.style.borderRadius = '8px';
                        div.style.background = '#f7faff';
                        const propertyDamage = parseFloat(parts[1].trim()) || 0;
                        const businessInterruption = parseFloat(parts[2].trim()) || 0;
                        const total = propertyDamage + businessInterruption;
                        div.innerHTML = `
                    <input type="text" name="risk_location[]" class="form-input" style="flex:1 1 200px;" placeholder="Enter risk location" value="${parts[0].trim()}" required>
                    <input type="number" name="risk_property_damage[]" class="form-input" style="flex:1 1 150px;" placeholder="Property Damage" min="0" step="any" value="${propertyDamage}" oninput="calculateRiskTotal(this)" required>
                    <input type="number" name="risk_business_interruption[]" class="form-input" style="flex:1 1 150px;" placeholder="Business Interruption" min="0" step="any" value="${businessInterruption}" oninput="calculateRiskTotal(this)" required>
                    <input type="number" name="risk_total_sum[]" class="form-input" style="flex:1 1 150px;" placeholder="Total Sum Insured" value="${total}" readonly style="background:#e3f0ff;">
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
            div.style.flexWrap = 'wrap';
            div.style.gap = '0.5rem';
            div.style.marginBottom = '1rem';
            div.style.padding = '1rem';
            div.style.border = '1px solid #e3f0ff';
            div.style.borderRadius = '8px';
            div.style.background = '#f7faff';
            div.innerHTML = `
        <input type="text" name="risk_location[]" class="form-input" style="flex:1 1 200px;" placeholder="Enter risk location" required>
        <input type="number" name="risk_property_damage[]" class="form-input" style="flex:1 1 150px;" placeholder="Property Damage" min="0" step="any" oninput="calculateRiskTotal(this)" required>
        <input type="number" name="risk_business_interruption[]" class="form-input" style="flex:1 1 150px;" placeholder="Business Interruption" min="0" step="any" oninput="calculateRiskTotal(this)" required>
        <input type="number" name="risk_total_sum[]" class="form-input" style="flex:1 1 150px;" placeholder="Total Sum Insured" readonly style="background:#e3f0ff;">
        <button type="button" onclick="removeRiskLocation(this)" style="background:#e74c3c; color:#fff; border:none; border-radius:6px; padding:0.3rem 0.8rem; font-size:1rem; cursor:pointer;">&times;</button>
    `;
            wrapper.appendChild(div);
        }

        function removeRiskLocation(btn) {
            btn.parentElement.remove();
        }

        // Calculate total sum for each risk location
        function calculateRiskTotal(input) {
            const container = input.closest('div');
            const propertyDamage = parseFloat(container.querySelector('input[name="risk_property_damage[]"]').value) || 0;
            const businessInterruption = parseFloat(container.querySelector('input[name="risk_business_interruption[]"]').value) || 0;
            const totalField = container.querySelector('input[name="risk_total_sum[]"]');
            totalField.value = propertyDamage + businessInterruption;
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
                const propertyDamages = document.getElementsByName('risk_property_damage[]');
                const businessInterruptions = document.getElementsByName('risk_business_interruption[]');
                const totalSums = document.getElementsByName('risk_total_sum[]');
                const hidden = document.getElementById('riskLocationsJson');

                let arr = [];

                for (let i = 0; i < locations.length; i++) {
                    const loc = locations[i].value.trim();
                    const propDamage = parseFloat(propertyDamages[i].value) || 0;
                    const bizInterruption = parseFloat(businessInterruptions[i].value) || 0;
                    const total = parseFloat(totalSums[i].value) || 0;
                    
                    if (loc !== '') {
                        arr.push({
                            location: loc,
                            property_damage: propDamage,
                            business_interruption: bizInterruption,
                            total_sum_insured: total
                        });
                    }
                }

                console.log("Final JSON:", arr);
                if (hidden) {
                    hidden.value = arr.length ? JSON.stringify(arr) : '';
                }

                var annexureCheckbox = document.getElementById('riskLocationAnnexure');
                var wrapper = document.getElementById('riskLocationsWrapper');
                var hiddenAnnexure = document.getElementById('riskLocationAnnexureHidden');
                if (annexureCheckbox && annexureCheckbox.checked) {
                    // Hide risk location fields and send null
                    var locations = wrapper.querySelectorAll('input[name="risk_location[]"]');
                    var propertyDamages = wrapper.querySelectorAll('input[name="risk_property_damage[]"]');
                    var businessInterruptions = wrapper.querySelectorAll('input[name="risk_business_interruption[]"]');
                    var totalSums = wrapper.querySelectorAll('input[name="risk_total_sum[]"]');
                    
                    locations.forEach(function(input) {
                        input.value = '';
                        input.removeAttribute('required');
                    });
                    propertyDamages.forEach(function(input) {
                        input.value = '';
                        input.removeAttribute('required');
                    });
                    businessInterruptions.forEach(function(input) {
                        input.value = '';
                        input.removeAttribute('required');
                    });
                    totalSums.forEach(function(input) {
                        input.value = '';
                    });
                    hiddenAnnexure.value = '1';
                } else {
                    hiddenAnnexure.value = '0';
                }
            });
        });
    </script>

@endsection
