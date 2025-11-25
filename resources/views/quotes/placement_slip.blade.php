@extends('layouts.app')

@section('title', 'Placement Slip')

@section('content')
    <div style="max-width: 900px; margin: 0 auto;">
        <h2 style="color:#2e3192; font-weight:700; margin-bottom:1.5rem;">Reinsurers Placement Slip</h2>
        @php
            $reinsurers = $quote->reinsurer ? json_decode($quote->reinsurer, true) : [];
        @endphp
            <div style="margin-bottom: 1.5rem;">
                <a href="{{ route('placement-slip.create', ['id' => $quote->id]) }}" class="btn" style="background:#2e3192; color:#fff; border:none; border-radius:6px; padding:0.5rem 1.2rem; font-size:1rem; text-decoration:none;">Add Placement Slip</a>
            </div>
        
            <h3 style="color:#2e3192; font-weight:600; margin-top:2rem;">Placement Slip Details</h3>
            <table style="width:100%; border-collapse:collapse; background:#fff; margin-top:1rem;">
                <thead>
                    <tr style="background:#e3f0ff;">
                        <th style="padding:0.7rem; color:#2e3192; font-weight:600;">Reinsurer Name</th>
                        <th style="padding:0.7rem; color:#2e3192; font-weight:600;">To</th>
                        <th style="padding:0.7rem; color:#2e3192; font-weight:600;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($placement_slip as $slip)
                        <tr>
                            <td style="padding:0.7rem; vertical-align:middle;">{{ $slip->reinsurer_name }}</td>
                            <td style="padding:0.7rem; vertical-align:middle;">{{ $slip->to }}</td>
                            <td style="padding:0.7rem; vertical-align:middle; white-space:nowrap;">
                                <a href="{{ route('placement-slip.edit', ['id' => $slip->id]) }}" class="btn" style="background:#27ae60; color:#fff; border:none; border-radius:6px; padding:0.4rem 1rem; font-size:0.95rem; text-decoration:none; margin-right:0.5rem;">Edit</a>
                                <a href="{{ route('placement-slip.download', ['id' => $slip->id]) }}" class="btn" style="background:#e67e22; color:#fff; border:none; border-radius:6px; padding:0.4rem 1rem; font-size:0.95rem; text-decoration:none; margin-right:0.5rem;">Download</a>
                                @if(is_null($slip->signed_slip))
                                    <form method="POST" action="{{ route('placement-slip.upload-signed', ['id' => $slip->id]) }}" enctype="multipart/form-data" style="display:inline-block;">
                                        @csrf
                                        <label class="btn" style="background:#2e3192; color:#fff; border:none; border-radius:6px; padding:0.4rem 1rem; font-size:0.95rem; cursor:pointer; margin-right:0.5rem;">
                                            Upload Signed Slip
                                            <input type="file" name="signed_slip" accept="application/pdf,image/*" style="display:none;" onchange="this.form.submit()" required>
                                        </label>
                                    </form>
                                @else
                                    <a href="{{ asset('storage/signed_slips/' . basename($slip->signed_slip)) }}" class="btn" style="background:#2980b9; color:#fff; border:none; border-radius:6px; padding:0.4rem 1rem; font-size:0.95rem; text-decoration:none;">Download Signed Slip</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
            </tbody>
        </table>
    </div>
@endsection