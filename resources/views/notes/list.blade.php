@extends('layouts.app')

@section('title', 'Notes List')

@section('content')
    <div style="max-width: 1200px; margin: 0 auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h2 style="color:#2e3192; font-weight:700;">Notes List</h2>
            <a href="{{ route('note.create', ['id' => $id]) }}"
                style="background: #2e3192; color: #fff; border-radius: 6px; padding: 0.6rem 1.5rem; font-weight:600; text-decoration:none; font-size:1rem;">
                + Add New Note
            </a>
        </div>
        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse; background:#fff;">
                <thead>
                    <tr style="background:#e3f0ff;">
                        <th style="padding:0.7rem; border:1px solid #e3f0ff;">Invoice Number</th>
                        <th style="padding:0.7rem; border:1px solid #e3f0ff;">Date</th>
                        <th style="padding:0.7rem; border:1px solid #e3f0ff;">PPW</th>
                        <th style="padding:0.7rem; border:1px solid #e3f0ff;">Actions</th>
                        <th style="padding:0.7rem; border:1px solid #e3f0ff;">Download</th>

                    </tr>
                </thead>
                <tbody>
                    @forelse($notes as $note)
                        <tr>
                            <td style="padding:0.7rem; border:1px solid #e3f0ff;">{{ $note->invoice_number }}</td>
                            <td style="padding:0.7rem; border:1px solid #e3f0ff;">{{ $note->date }}</td>

                            <td style="padding:0.7rem; border:1px solid #e3f0ff;">{{ $note->PPW }}</td>
                            <td style="padding:0.7rem; border:1px solid #e3f0ff;">
                                <a href="{{ route('note.edit', $note->id) }}"
                                    style="color:#2e3192; text-decoration:underline;">Edit</a>
                            </td>
                            <td style="padding:0.7rem; border:1px solid #e3f0ff;">
                                <a href="{{ route('note.pdf', $note->id) }}"
                                    style="color:#2e3192; text-decoration:underline;">PDF</a>
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
