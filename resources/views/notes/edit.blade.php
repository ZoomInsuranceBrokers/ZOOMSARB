@extends('layouts.app')

@section('title', 'Edit Note')

@section('content')
<div style="max-width: 850px; margin: 0 auto;">
    <div style="background: #fff; border-radius: 14px; box-shadow: 0 2px 16px #2e319222; padding: 2.5rem 2rem; margin-top:2rem;">
        <h2 style="color:#2e3192; font-weight:700; margin-bottom:2rem; text-align:center;">Edit Note</h2>
        <form method="POST" action="{{ route('note.update', $note->id) }}" onsubmit="return prepareParticularsJson();">
            @csrf
            <div style="display: flex; gap: 1.5rem; flex-wrap: wrap;">
                <div style="flex:1 1 250px;">
                    <label for="bank_id" class="note-label">Bank Details</label>
                    <select id="bank_id" name="bank_id" class="form-input" required>
                        <option value="">Select Bank</option>
                        @foreach($bankingDetails as $bank)
                            <option value="{{ $bank->id }}" {{ $note->bank_id == $bank->id ? 'selected' : '' }}>{{$bank->Account_No}}</option>
                        @endforeach
                    </select>
                </div>
                <div style="flex:1 1 250px;">
                    <label for="note_type" class="note-label">Note Type</label>
                    <select id="note_type" name="note_type" class="form-input" required>
                        <option value="">Select Type</option>
                        <option value="credit" {{ $note->credit_type == 'credit' ? 'selected' : '' }}>Credit</option>
                        <option value="debit" {{ $note->credit_type == 'debit' ? 'selected' : '' }}>Debit</option>
                    </select>
                </div>
            </div>
            <div style="display: flex; gap: 1.5rem; flex-wrap: wrap; margin-top:1.2rem;">
                <div style="flex:1 1 250px;">
                    <label for="invoice_number" class="note-label">Invoice Number</label>
                    <input type="text" id="invoice_number" name="invoice_number" class="form-input" value="{{ $note->invoice_number }}" required>
                </div>
                <div style="flex:1 1 250px;">
                    <label for="date" class="note-label">Date</label>
                    <input type="date" id="date" name="date" class="form-input" value="{{ $note->date }}" required>
                </div>
                <div style="flex:1 1 250px;">
                    <label for="to" class="note-label">To</label>
                    <input type="text" id="to" name="to" class="form-input" value="{{ $note->to }}" required>
                </div>
            </div>
            <div style="display: flex; gap: 1.5rem; flex-wrap: wrap; margin-top:1.2rem;">
                <div style="flex:1 1 250px;">
                    <label for="reinsured" class="note-label">Reinsured</label>
                    <input type="text" id="reinsured" name="reinsured" class="form-input" value="{{ $note->reinsured }}" required>
                </div>
                <div style="flex:1 1 250px;">
                    <label for="Reinsurer" class="note-label">Reinsurer</label>
                    <input type="text" id="Reinsurer" name="Reinsurer" class="form-input" value="{{ $note->Reinsurer }}" required>
                </div>
                <div style="flex:1 1 250px;">
                    <label for="original_insured" class="note-label">Original Insured</label>
                    <input type="text" id="original_insured" name="original_insured" class="form-input" value="{{ $note->original_insured }}" required>
                </div>
            </div>
            <div style="margin-top:1.2rem;">
                <label for="PPW" class="note-label">PPW</label>
                <input type="text" id="PPW" name="PPW" class="form-input" value="{{ $note->PPW }}" required>
            </div>
            <div style="margin-top:1.2rem;">
                <label class="note-label">Particulars</label>
                <div id="particularsWrapper" style="margin-bottom:0.5rem;">
                    @foreach($note->particulars as $key => $value)
                        <div style="display:flex; gap:0.5rem; margin-bottom:0.5rem;">
                            <input type="text" class="form-input" value="{{ $key }}" style="flex:2;">
                            <input type="number" class="form-input" value="{{ $value }}" style="flex:1;">
                        </div>
                    @endforeach
                </div>
                <button type="button" onclick="addParticular()"
                    style="margin-top:0.5rem; background:#2e3192; color:#fff; border:none; border-radius:6px; padding:0.4rem 1.2rem; font-size:0.95rem; cursor:pointer;">
                    + Add Particular
                </button>
            </div>
            <input type="hidden" name="particulars" id="particularsJson">
            <div style="text-align:center; margin-top:2.5rem;">
                <button type="submit"
                    style="background: linear-gradient(90deg, #2e3192 0%, #1bffff 100%); color: #fff; border: none; border-radius: 8px; padding: 0.9rem 2.5rem; font-size: 1.1rem; font-weight: 700; cursor: pointer; box-shadow: 0 2px 8px #2e319222;">
                    <i class="fas fa-save"></i> Update Note
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
        color:#2e3192;
        font-weight:600;
        margin-bottom:0.2rem;
        display:block;
    }
</style>
<script>
    function addParticular() {
        const wrapper = document.getElementById('particularsWrapper');
        const div = document.createElement('div');
        div.style.display = 'flex';
        div.style.gap = '0.5rem';
        div.style.marginBottom = '0.5rem';

        const nameInput = document.createElement('input');
        nameInput.type = 'text';
        nameInput.className = 'form-input';
        nameInput.placeholder = 'Particular Name';
        nameInput.style.flex = '2';

        const amountInput = document.createElement('input');
        amountInput.type = 'number';
        amountInput.className = 'form-input';
        amountInput.placeholder = 'Amount';
        amountInput.style.flex = '1';

        div.appendChild(nameInput);
        div.appendChild(amountInput);
        wrapper.appendChild(div);
    }

    function prepareParticularsJson() {
        const wrapper = document.getElementById('particularsWrapper');
        const rows = wrapper.querySelectorAll('div');
        let particulars = {};
        rows.forEach(row => {
            const inputs = row.querySelectorAll('input');
            const key = inputs[0].value.trim();
            const value = inputs[1].value.trim();
            if (key !== '') {
                particulars[key] = value;
            }
        });
        document.getElementById('particularsJson').value = JSON.stringify(particulars);
        return true;
    }
</script>
@endsection
