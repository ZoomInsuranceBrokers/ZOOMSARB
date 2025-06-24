
@extends('layouts.app')

@section('title', 'Policy Wording')

@section('content')
    <div style="max-width: 900px; margin: 0 auto;">
        <div style="background: linear-gradient(135deg, #e3f0ff 0%, #f7faff 100%); border-radius: 18px; box-shadow: 0 2px 16px #2e319222; padding: 2.5rem 2rem; margin-bottom: 2rem;">
            <h2 style="color:#2e3192; font-weight:700; margin-bottom:1.5rem;">Policy Wording for {{ $quote->policy_name }}</h2>
            <form method="POST" action="{{ route('quotes.policywording.save', $quote->id) }}">
                @csrf
                <div style="margin-bottom:1.5rem;">
                    <label for="policy_wording" style="color:#2e3192; font-weight:600;">Policy Wording (Rich Text / HTML)</label>
                    <textarea id="policy_wording" name="policy_wording" class="form-input" rows="12">{{ $quote->policy_wording }}</textarea>
                </div>
                <button type="submit" style="background: linear-gradient(90deg, #2e3192 0%, #1bffff 100%); color: #fff; border: none; border-radius: 8px; padding: 0.9rem 2.5rem; font-size: 1.1rem; font-weight: 700; cursor: pointer;">
                    <i class="fas fa-save"></i> Save Policy Wording
                </button>
            </form>
        </div>
    </div>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#policy_wording').summernote({
                height: 300,
                tabsize: 2,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['fontname', ['fontname']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link', 'picture', 'video', 'table', 'hr']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });
        });
    </script>
    <style>
        .form-input {
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
        .note-editor.note-frame {
            border-radius: 8px;
            border: 1.5px solid #e3f0ff;
        }
    </style>
@endsection
