@extends('layouts.app')

@section('title', 'All Quotes')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h2 style="color:#2e3192; font-weight:700; margin:0;">All Quotes</h2>

        <input type="text" id="searchInput" placeholder="Search by Insured..."
            style="padding: 0.6rem 1rem; border: 1.5px solid #e3f0ff; border-radius: 8px; background: #f7faff; font-size: 1rem; min-width: 300px;">
    </div>

    <div id="quotesContainer" style="display: flex; flex-wrap: wrap; gap: 2rem; justify-content: center;">
        @foreach ($quotes as $quote)
            <div class="quote-card" onclick="window.location='{{ route('quotes.show', $quote->id) }}'"
                style="cursor:pointer; background: linear-gradient(135deg, #e3f0ff 0%, #f7faff 100%); border-radius: 16px; box-shadow: 0 2px 16px #2e319222; padding: 2rem 1.5rem; min-width: 280px; max-width: 320px; flex: 1 1 300px; transition: box-shadow 0.2s, transform 0.2s; position:relative; animation: popIn 0.7s;">
                <div style="font-size:1.1rem; color:#2e3192; font-weight:600; margin-bottom:0.7rem;">
                    {{ $quote->policy_name ?? 'No Policy Name' }}
                </div>
                <div class="insured-name" style="font-size:1rem; color:#555; margin-bottom:1.2rem;">
                    <strong>Insured:</strong> {{ $quote->insured_name ?? 'N/A' }}
                </div>
                <div style="display:flex; gap:0.5rem; flex-wrap:wrap;">
                    @if (!$quote->is_final_submit)
                        <a href="{{ route('quotes.edit', $quote->id) }}" onclick="event.stopPropagation();"
                            style="background: #1bffff; color: #2e3192; border-radius: 6px; padding: 0.5rem 1.2rem; font-weight:600; text-decoration:none; font-size:0.98rem; transition: background 0.2s;">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                    @else
                        <a href="{{ route('note.list', $quote->id) }}" onclick="event.stopPropagation();"
                            style="background: #1bffff; color: #2e3192; border-radius: 6px; padding: 0.5rem 1.2rem; font-weight:600; text-decoration:none; font-size:0.98rem; transition: background 0.2s;">
                            <i class="fas fa-edit"></i> Credit/Debit Note
                        </a>
                    @endif
                    <a href="{{ route('quotes.download', $quote->id) }}" onclick="event.stopPropagation();"
                        style="background: #2e3192; color: #fff; border-radius: 6px; padding: 0.5rem 1.2rem; font-weight:600; text-decoration:none; font-size:0.98rem; transition: background 0.2s;">
                        <i class="fas fa-file-pdf"></i> PDF
                    </a>
                    <form method="POST" action="{{ route('quotes.destroy', $quote->id) }}" style="display:inline;"
                        onsubmit="return confirm('Are you sure you want to delete this quote?');">
                        @method('DELETE')
                        @csrf
                        <button type="submit" onclick="event.stopPropagation();"
                            style="background: #d32f2f; color: #fff; border:none; border-radius: 6px; padding: 0.5rem 1.2rem; font-weight:600; font-size:0.98rem; cursor:pointer; transition: background 0.2s;">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </form>
                    <a href="{{ route('quotes.policywording', $quote->id) }}" onclick="event.stopPropagation();"
                        style="background: #ffb347; color: #2e3192; border-radius: 6px; padding: 0.5rem 1.2rem; font-weight:600; text-decoration:none; font-size:0.98rem; transition: background 0.2s;">
                        <i class="fas fa-file-alt"></i> Policy Wording
                    </a>
                </div>
            </div>
        @endforeach
    </div>
    <div id="noResultsMessage"
        style="display:none; color:#888; font-size:1.2rem; text-align:center; width:100%; margin-top:2rem;">No quotes found
        for your search.</div>

    <div id="pagination" class="pagination-container"></div>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.all.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script>
        $(document).ready(function() {
            const itemsPerPage = 6;
            const $quotesContainer = $('#quotesContainer');
            const $quoteCards = $quotesContainer.children('.quote-card');
            const $paginationContainer = $('#pagination');
            const $noResultsMessage = $('#noResultsMessage');

            function setupPagination(visibleItems) {
                $paginationContainer.empty();
                const totalItems = visibleItems.length;

                // Hide all cards initially before showing the paginated ones
                $quoteCards.hide();
                $noResultsMessage.hide();

                if (totalItems === 0) {
                    $noResultsMessage.show();
                    return;
                }

                if (totalItems <= itemsPerPage) {
                    visibleItems.show(); // Show all if they fit on one page
                    return;
                }

                const pageCount = Math.ceil(totalItems / itemsPerPage);

                for (let i = 1; i <= pageCount; i++) {
                    const $button = $('<button class="pagination-button">' + i + '</button>');
                    $button.on('click', function() {
                        showPage(i, visibleItems);
                        $('.pagination-button').removeClass('active');
                        $(this).addClass('active');
                    });
                    $paginationContainer.append($button);
                }

                $paginationContainer.children().first().addClass('active');
                showPage(1, visibleItems);
            }

            function showPage(pageNumber, visibleItems) {
                const startIndex = (pageNumber - 1) * itemsPerPage;
                const endIndex = startIndex + itemsPerPage;

                // First, hide only the items that are part of the current visible set
                visibleItems.hide();
                // Then, show only the slice for the current page
                visibleItems.slice(startIndex, endIndex).show();
            }

            $('#searchInput').on('input', function() {
                const searchTerm = $(this).val().toLowerCase().trim();

                // Filter cards based on search term
                const $visibleCards = $quoteCards.filter(function() {
                    const insuredName = $(this).find('.insured-name').text().toLowerCase();
                    return insuredName.includes(searchTerm);
                });

                // Re-setup pagination with only the visible (filtered) cards
                setupPagination($visibleCards);
            });

            // Initial Load: Setup pagination for all cards
            setupPagination($quoteCards);
        });
    </script>
    <style>
        @keyframes popIn {
            from {
                opacity: 0;
                transform: scale(0.8);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
        .quote-card:hover {
            box-shadow: 0 8px 32px #2e319244;
            transform: translateY(-6px) scale(1.03);
        }
        .pagination-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 2.5rem;
            gap: 0.5rem;
        }
        .pagination-button {
            background: #f7faff;
            border: 1.5px solid #e3f0ff;
            color: #2e3192;
            font-weight: 600;
            border-radius: 8px;
            padding: 0.6rem 1rem;
            cursor: pointer;
            transition: background 0.2s, color 0.2s;
        }
        .pagination-button:hover {
            background: #e3f0ff;
        }
        .pagination-button.active {
            background: #2e3192;
            color: #fff;
            border-color: #2e3192;
        }
    </style>
@endsection
