@extends('layouts.app')

@section('title', 'All Quotes')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h2 style="color:#2e3192; font-weight:700; margin:0;">All Quotes</h2>
    </div>

    <div style="background: #fff; border-radius: 12px; box-shadow: 0 2px 16px #2e319222; padding: 1.5rem; margin-bottom: 2rem;">
        <table id="quotesTable" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>Policy Name</th>
                    <th>Insured Name</th>
                    <th>Policy Period</th>
                    <th>Edit</th>
                    <th>Quote Slip</th>
                    <th>Delete</th>
                    <th>Annexure</th>
                    <th>Placement Slip</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($quotes as $quote)
                    <tr>
                        <td>
                            <a href="{{ route('quotes.show', $quote->id) }}" style="color: #2e3192; text-decoration: none; font-weight: 600;">
                                {{ $quote->policy_name ?? 'No Policy Name' }}
                            </a>
                        </td>
                        <td>{{ $quote->insured_name ?? 'N/A' }}</td>
                        <td>{{ $quote->policy_period ?? 'N/A' }}</td>
                        <td>
                            @if ($quote->is_final_submit  == 0 || $quote->is_final_submit  == 2)
                                <a href="{{ route('quotes.edit', $quote->id) }}" 
                                   style="background: #1bffff; color: #2e3192; border-radius: 4px; padding: 0.4rem 0.8rem; font-weight:600; text-decoration:none; font-size:0.8rem; white-space: nowrap; display: inline-block;">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                            @else
                                <a href="{{ route('note.list', $quote->id) }}" 
                                   style="background: #1bffff; color: #2e3192; border-radius: 4px; padding: 0.4rem 0.8rem; font-weight:600; text-decoration:none; font-size:0.8rem; white-space: nowrap; display: inline-block;">
                                    <i class="fas fa-edit"></i> Notes
                                </a>
                            @endif
                        </td>
                        <td>
                            <button onclick="openCurrencyModal({{ $quote->id }})"
                                    style="background: #2e3192; color: #fff; border:none; border-radius: 4px; padding: 0.4rem 0.8rem; font-weight:600; font-size:0.8rem; cursor:pointer; white-space: nowrap;">
                                <i class="fas fa-file-pdf"></i> Quote Slip
                            </button>
                        </td>
                        <td>
                            <form method="POST" action="{{ route('quotes.destroy', $quote->id) }}" style="display:inline;"
                                  onsubmit="return confirm('Are you sure you want to delete this quote?');">
                                @method('DELETE')
                                @csrf
                                <button type="submit" 
                                        style="background: #d32f2f; color: #fff; border:none; border-radius: 4px; padding: 0.4rem 0.8rem; font-weight:600; font-size:0.8rem; cursor:pointer; white-space: nowrap;">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>
                        </td>
                        <td>
                            <a href="{{ route('quotes.policywording', $quote->id) }}" 
                               style="background: #ffb347; color: #2e3192; border-radius: 4px; padding: 0.4rem 0.8rem; font-weight:600; text-decoration:none; font-size:0.8rem; white-space: nowrap; display: inline-block;">
                                <i class="fas fa-file-alt"></i> Annexure
                            </a>
                        </td>
                        <td>
                            @if ($quote->is_final_submit == 1)
                                <a href="{{ route('quotes.placement-slip', $quote->id) }}" 
                                   style="background: #4caf50; color: #fff; border-radius: 4px; padding: 0.4rem 0.8rem; font-weight:600; text-decoration:none; font-size:0.8rem; white-space: nowrap; display: inline-block;">
                                    <i class="fas fa-file-contract"></i> Placement Slip
                                </a>
                            @elseif ($quote->is_final_submit == 2)
                                <button
                                        style="background: #e74c3c; color: #fff; border:none; border-radius: 4px; padding: 0.4rem 0.8rem; font-weight:600; font-size:0.8rem; cursor:pointer; white-space: nowrap;">
                                    <i class="fas fa-times-circle"></i> Business Lost
                                </button>
                            @else
                                <span style="color: #888; font-size: 0.8rem; font-style: italic;">
                                    Not converted yet
                                </span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Business Lost Remark Modal -->
    <div id="businessLostModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:1000; align-items:center; justify-content:center;">
        <div style="background:#fff; border-radius:12px; padding:2rem; max-width:500px; width:90%; box-shadow:0 4px 20px rgba(0,0,0,0.3);">
            <h3 style="color:#e74c3c; font-weight:700; margin-bottom:1.5rem; text-align:center;">
                <i class="fas fa-times-circle"></i> Business Lost
            </h3>
            
            <div style="margin-bottom:1.5rem;">
                <label style="color:#2e3192; font-weight:600; display:block; margin-bottom:0.5rem;">Reason:</label>
                <div id="businessLostReason" style="background:#f8f9fa; border:1px solid #e3f0ff; border-radius:8px; padding:1rem; min-height:60px; font-size:1rem; color:#333;">
                    <!-- Reason will be populated here -->
                </div>
            </div>
            
            <div style="display:flex; justify-content:center;">
                <button type="button" onclick="closeBusinessLostModal()" style="background:#2e3192; color:#fff; border:none; border-radius:6px; padding:0.7rem 1.5rem; font-weight:600; cursor:pointer;">
                    Close
                </button>
            </div>
        </div>
    </div>

    <!-- Currency Selection Modal -->
    <div id="currencyModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:1000; align-items:center; justify-content:center;">
        <div style="background:#fff; border-radius:12px; padding:2rem; max-width:400px; width:90%; box-shadow:0 4px 20px rgba(0,0,0,0.3);">
            <h3 style="color:#2e3192; font-weight:700; margin-bottom:1.5rem; text-align:center;">Select Currency for PDF Download</h3>
            
            <form id="currencyForm">
                <div style="margin-bottom:1.2rem;">
                    <label style="color:#2e3192; font-weight:600; display:block; margin-bottom:0.5rem;">Action:</label>
                    <div style="display:flex; gap:1rem; align-items:center;">
                        <label style="font-weight:600; color:#333;">
                            <input type="radio" name="mode" value="download" checked style="margin-right:0.4rem;"> Download
                        </label>
                        <label style="font-weight:600; color:#333;">
                            <input type="radio" name="mode" value="view" style="margin-right:0.4rem;"> View
                        </label>
                    </div>
                </div>

                <div style="margin-bottom:1.2rem;">
                    <label for="currency" style="color:#2e3192; font-weight:600; display:block; margin-bottom:0.5rem;">Currency:</label>
                    <select id="currency" name="currency" style="width:100%; padding:0.7rem; border:1.5px solid #e3f0ff; border-radius:8px; background:#f7faff; font-size:1rem;" onchange="toggleExchangeRate()">
                        <option value="INR">INR (Indian Rupee)</option>
                        <option value="USD">USD (US Dollar)</option>
                        <option value="EUR">EUR (Euro)</option>
                    </select>
                </div>
                
                <div id="exchangeRateDiv" style="margin-bottom:1.2rem; display:none;">
                    <label for="exchangeRate" style="color:#2e3192; font-weight:600; display:block; margin-bottom:0.5rem;">Exchange Rate (1 <span id="selectedCurrency">USD</span> = ? INR):</label>
                    <input type="number" id="exchangeRate" name="exchangeRate" step="0.01" min="0" placeholder="Enter exchange rate" style="width:100%; padding:0.7rem; border:1.5px solid #e3f0ff; border-radius:8px; background:#f7faff; font-size:1rem;">
                    <small style="color:#666; font-size:0.85rem; margin-top:0.3rem; display:block;">Example: If 1 USD = 84 INR, enter 84</small>
                </div>
                
                <div style="display:flex; gap:1rem; justify-content:flex-end; margin-top:1.5rem;">
                    <button type="button" onclick="closeCurrencyModal()" style="background:#ccc; color:#333; border:none; border-radius:6px; padding:0.7rem 1.5rem; font-weight:600; cursor:pointer;">
                        Cancel
                    </button>
                    <button type="submit" style="background:#2e3192; color:#fff; border:none; border-radius:6px; padding:0.7rem 1.5rem; font-weight:600; cursor:pointer;">
                        Download PDF
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.min.css">
    
    <!-- jQuery and DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.all.min.js"></script>

    <script>
        let currentQuoteId = null;
        let quotesTable;

        $(document).ready(function() {
            // Initialize DataTable
            quotesTable = $('#quotesTable').DataTable({
                responsive: true,
                pageLength: 10,
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                order: [[0, 'asc']], // Sort by policy name
                columnDefs: [
                    {
                        targets: [3, 4, 5, 6, 7], // Action columns (including new Placement Slip column)
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    }
                ],
                language: {
                    search: "Search quotes:",
                    lengthMenu: "Show _MENU_ quotes per page",
                    info: "Showing _START_ to _END_ of _TOTAL_ quotes",
                    infoEmpty: "Showing 0 to 0 of 0 quotes",
                    infoFiltered: "(filtered from _MAX_ total quotes)",
                    zeroRecords: "No quotes found matching your search",
                    emptyTable: "No quotes available"
                },
                dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                     '<"row"<"col-sm-12"tr>>' +
                     '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p">>'
            });
        });

        function openCurrencyModal(quoteId) {
            currentQuoteId = quoteId;
            document.getElementById('currencyModal').style.display = 'flex';
            // Reset form
            document.getElementById('currency').value = 'INR';
            document.getElementById('exchangeRate').value = '';
            // default mode to download
            const downloadRadio = document.querySelector('input[name="mode"][value="download"]');
            if (downloadRadio) downloadRadio.checked = true;
            toggleExchangeRate();
        }

        function closeCurrencyModal() {
            document.getElementById('currencyModal').style.display = 'none';
            currentQuoteId = null;
        }

        function showBusinessLostRemark(reason) {
            document.getElementById('businessLostReason').innerHTML = reason || 'No reason provided';
            document.getElementById('businessLostModal').style.display = 'flex';
        }

        function closeBusinessLostModal() {
            document.getElementById('businessLostModal').style.display = 'none';
        }

        function toggleExchangeRate() {
            const currency = document.getElementById('currency').value;
            const exchangeRateDiv = document.getElementById('exchangeRateDiv');
            const selectedCurrencySpan = document.getElementById('selectedCurrency');
            
            if (currency === 'INR') {
                exchangeRateDiv.style.display = 'none';
                document.getElementById('exchangeRate').removeAttribute('required');
            } else {
                exchangeRateDiv.style.display = 'block';
                document.getElementById('exchangeRate').setAttribute('required', 'required');
                selectedCurrencySpan.textContent = currency;
            }
        }

        // Handle form submission
        document.getElementById('currencyForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const mode = document.querySelector('input[name="mode"]:checked')?.value || 'download';
            const currency = document.getElementById('currency').value;
            const exchangeRate = document.getElementById('exchangeRate').value;

            // Validate exchange rate for non-INR currencies
            if (currency !== 'INR') {
                if (!exchangeRate || parseFloat(exchangeRate) <= 0) {
                    Swal.fire({
                        title: 'Invalid Exchange Rate',
                        text: 'Please enter a valid exchange rate greater than 0.',
                        icon: 'error',
                        confirmButtonColor: '#2e3192'
                    });
                    return;
                }

                // Additional validation for reasonable exchange rates
                const rate = parseFloat(exchangeRate);
                if (currency === 'USD' && (rate < 70 || rate > 100)) {
                    Swal.fire({
                        title: 'Unusual Exchange Rate',
                        text: 'USD exchange rate seems unusual (expected range: 70-100 INR). Are you sure this is correct?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, continue',
                        cancelButtonText: 'Let me check',
                        confirmButtonColor: '#2e3192'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            openQuotePdf(mode, currency, rate);
                        }
                    });
                    return;
                }

                if (currency === 'EUR' && (rate < 80 || rate > 110)) {
                    Swal.fire({
                        title: 'Unusual Exchange Rate',
                        text: 'EUR exchange rate seems unusual (expected range: 80-110 INR). Are you sure this is correct?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, continue',
                        cancelButtonText: 'Let me check',
                        confirmButtonColor: '#2e3192'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            openQuotePdf(mode, currency, rate);
                        }
                    });
                    return;
                }
            }

            openQuotePdf(mode, currency, exchangeRate);
        });
        
        function openQuotePdf(mode, currency, exchangeRate) {
            // mode: 'view' or 'download'
            // Build URL using query parameters: /quotes/{id}/download/{currency}/{exchangeRate}?mode={mode}
            let url = `/quotes/${currentQuoteId}/download/${currency}`;
            if (currency !== 'INR' && exchangeRate) {
                url += `/${exchangeRate}`;
            }
            // Add mode as query parameter
            url += `?mode=${mode}`;

            // Close modal and open the URL in a new tab/window
            closeCurrencyModal();
            window.open(url, '_blank');
        }

        // Close modal when clicking outside
        document.getElementById('currencyModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeCurrencyModal();
            }
        });

        // Close business lost modal when clicking outside
        document.getElementById('businessLostModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeBusinessLostModal();
            }
        });
    </script>

    <style>
        /* DataTable Styling */
        .dataTables_wrapper {
            font-family: Arial, sans-serif;
        }
        
        .dataTables_filter input {
            border: 1.5px solid #e3f0ff;
            border-radius: 8px;
            background: #f7faff;
            padding: 0.5rem 0.8rem;
            margin-left: 0.5rem;
        }
        
        .dataTables_length select {
            border: 1.5px solid #e3f0ff;
            border-radius: 6px;
            background: #f7faff;
            padding: 0.3rem 0.5rem;
            margin: 0 0.5rem;
        }
        
        #quotesTable {
            border-collapse: collapse;
        }
        
        #quotesTable thead th {
            background: linear-gradient(135deg, #e3f0ff 0%, #f7faff 100%);
            color: #2e3192;
            font-weight: 700;
            border: 1px solid #ddd;
            padding: 12px 8px;
            font-size: 0.9rem;
        }
        
        #quotesTable tbody td {
            border: 1px solid #eee;
            padding: 10px 8px;
            font-size: 0.85rem;
            vertical-align: middle;
        }
        
        #quotesTable tbody tr:hover {
            background: #f8fafe;
        }
        
        .dataTables_paginate .paginate_button {
            border: 1px solid #e3f0ff;
            background: #f7faff;
            color: #2e3192 !important;
            border-radius: 6px;
            margin: 0 2px;
            padding: 6px 12px;
        }
        
        .dataTables_paginate .paginate_button:hover {
            background: #e3f0ff !important;
            border-color: #2e3192;
        }
        
        .dataTables_paginate .paginate_button.current {
            background: #2e3192 !important;
            color: white !important;
            border-color: #2e3192;
        }
        
        .text-end {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .dataTables_info {
            color: #666;
            font-size: 0.9rem;
        }
        
        .dataTables_length {
            color: #666;
            font-size: 0.9rem;
        }
    </style>
@endsection
