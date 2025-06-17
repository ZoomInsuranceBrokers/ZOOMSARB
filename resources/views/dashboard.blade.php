
@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <h1 style="font-size:2.2rem; font-weight:700; color:#2e3192; margin-bottom:1.2rem; letter-spacing:1px; animation: fadeInDown 1s;">
        Welcome, {{ Auth::user()->name }}!
    </h1>
    <div style="display: flex; justify-content: center; margin-bottom: 2rem;">
        <div style="background: linear-gradient(135deg, #e3f0ff 0%, #1bffff 100%); box-shadow: 0 2px 8px #2e319222; border-radius: 14px; padding: 1.5rem 2.5rem; display: flex; align-items: center; gap: 1rem; animation: popIn 1.1s;">
            <span style="font-size: 1.3rem; color: #2e3192; font-weight: 600;">Add New Quote Slip</span>
            <a href="{{ route('quotes.create') }}" style="background: #2e3192; color: #fff; padding: 0.7rem 1.5rem; border-radius: 8px; font-weight: 600; text-decoration: none; font-size: 1rem; box-shadow: 0 2px 8px #2e319222; transition: background 0.2s;">
                <i class="fas fa-plus"></i> New Quote
            </a>
        </div>
    </div>

    <style>
        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-30px);}
            to { opacity: 1; transform: translateY(0);}
        }
        @keyframes popIn {
            from { opacity: 0; transform: scale(0.8);}
            to { opacity: 1; transform: scale(1);}
        }
        .add-quote-btn:hover {
            background: #1bffff !important;
            color: #2e3192 !important;
        }
    </style>
@endsection
