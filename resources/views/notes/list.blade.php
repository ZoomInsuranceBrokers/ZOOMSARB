@extends('layouts.app')

@section('title', 'Notes List')

@section('content')
<div style="max-width: 1200px; margin: 0 auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h2 style="color:#2e3192; font-weight:700;">Notes List</h2>
        <a href="{{ route('note.credit.create', ['id' => $id]) }}"
            style="background: #2e3192; color: #fff; border-radius: 6px; padding: 0.6rem 1.5rem; font-weight:600; text-decoration:none; font-size:1rem;">
            + Add New Credit Note
        </a>
        <a href="{{ route('note.debit.create', ['id' => $id]) }}"
            style="background: #2e3192; color: #fff; border-radius: 6px; padding: 0.6rem 1.5rem; font-weight:600; text-decoration:none; font-size:1rem;">
            + Add New Debit Note
        </a>
    </div>
    <div style="overflow-x:auto;">
        <table style="width:100%; border-collapse:collapse; background:#fff;">
            <thead>
                <tr style="background:#e3f0ff;">
                    <th style="padding:0.7rem; border:1px solid #e3f0ff;">Invoice Number</th>
                    <th style="padding:0.7rem; border:1px solid #e3f0ff;">Date</th>
                    <th style="padding:0.7rem; border:1px solid #e3f0ff;">To</th>
                    <th style="padding:0.7rem; border:1px solid #e3f0ff;">Download</th>
                    <th style="padding:0.7rem; border:1px solid #e3f0ff;">Signed Note</th>

                </tr>
            </thead>
            <tbody>
                @forelse($notes as $note)
                <tr>
                    <td style="padding:0.7rem; border:1px solid #e3f0ff;">{{ $note->invoice_number }}</td>
                    <td style="padding:0.7rem; border:1px solid #e3f0ff;">{{ $note->date }}</td>

                    <td style="padding:0.7rem; border:1px solid #e3f0ff;">{{ $note->to }}</td>

                    <td style="padding:0.7rem; border:1px solid #e3f0ff;">
                        <a href="{{ route('note.pdf', $note->id) }}"
                            style="color:#2e3192; text-decoration:underline;">PDF</a>
                    </td>
                    <td style="padding:0.7rem; border:1px solid #e3f0ff;">
                        @if($note->signed_note == null)
                            <form action="{{ route('note.upload-signed', $note->id) }}" method="POST" enctype="multipart/form-data" style="display:inline;">
                                @csrf
                                <input type="file" name="signed_note" accept=".pdf,.jpg,.jpeg,.png" required 
                                       style="display:none;" id="signedNote{{ $note->id }}" 
                                       onchange="this.form.submit()">
                                <button type="button" onclick="document.getElementById('signedNote{{ $note->id }}').click()" 
                                        style="background:#ff9800; color:#fff; border:none; border-radius:4px; padding:0.4rem 0.8rem; font-size:0.8rem; cursor:pointer;">
                                    <i class="fas fa-upload"></i> Upload Signed
                                </button>
                            </form>
                        @else
                            <a href="{{ route('note.download-signed', $note->id) }}" 
                               style="background:#4caf50; color:#fff; border-radius:4px; padding:0.4rem 0.8rem; text-decoration:none; font-size:0.8rem; display:inline-block;">
                                <i class="fas fa-download"></i> Download Signed
                            </a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="12" style="text-align:center; padding:1.2rem;">No notes found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection