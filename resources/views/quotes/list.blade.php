@extends('layouts.app')

@section('title', 'All Quotes')

@section('content')
    <h2 style="color:#2e3192; font-weight:700; margin-bottom:2rem; text-align:center;">All Quotes</h2>
    <div style="display: flex; flex-wrap: wrap; gap: 2rem; justify-content: center;">
        @forelse($quotes as $quote)
            <div onclick="window.location='{{ route('quotes.show', $quote->id) }}'"
                style="cursor:pointer; background: linear-gradient(135deg, #e3f0ff 0%, #f7faff 100%); border-radius: 16px; box-shadow: 0 2px 16px #2e319222; padding: 2rem 1.5rem; min-width: 280px; max-width: 320px; flex: 1 1 300px; transition: box-shadow 0.2s, transform 0.2s; position:relative; animation: popIn 0.7s;">
                <div style="font-size:1.1rem; color:#2e3192; font-weight:600; margin-bottom:0.7rem;">
                    {{ $quote->policy_name ?? 'No Policy Name' }}
                </div>
                <div style="font-size:1rem; color:#555; margin-bottom:1.2rem;">
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
                        @csrf
                        @method('DELETE')
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
        @empty
            <div style="color:#888; font-size:1.2rem; text-align:center;">No quotes found.</div>
        @endforelse
    </div>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.all.min.js"></script>
    <script>
        function handlePdfDownload(event, quoteId, isFinalSubmit) {
            event.preventDefault();
            Swal.fire({
                title: 'Final Submit',
                text: "This is the final submit. If you proceed, you can't edit this quote anymore. Do you want to continue?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#2e3192',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Final Submit & Download',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '/quotes/' + quoteId + '/finalsubmit-download';
                }
            });
            return false;
        }
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
    </style>
@endsection
