@extends('layouts.app')

@section('title', 'Quote Details')

@section('content')
    <div style="max-width: 900px; margin: 0 auto;">
        <div
            style="background: linear-gradient(135deg, #e3f0ff 0%, #f7faff 100%); border-radius: 18px; box-shadow: 0 2px 16px #2e319222; padding: 2.5rem 2rem; margin-bottom: 2rem;">
            <h2 style="color:#2e3192; font-weight:700; margin-bottom:1.5rem;">Quote Details</h2>
            <table style="width:100%; border-collapse:separate; border-spacing:0 0.7rem;">
                <tr>
                    <td style="font-weight:600; color:#2e3192;">Policy Name</td>
                    <td>{{ $quote->policy_name }}</td>
                </tr>
                <tr>
                    <td style="font-weight:600; color:#2e3192;">Insured Name</td>
                    <td>{{ $quote->insured_name }}</td>
                </tr>
                <tr>
                    <td style="font-weight:600; color:#2e3192;">Insured Address</td>
                    <td>{{ $quote->insured_address }}</td>
                </tr>
                <tr>
                    <td style="font-weight:600; color:#2e3192;">Policy Period</td>
                    <td>{{ $quote->policy_period ?? 'To be advised' }}</td>
                </tr>
                <tr>
                    <td style="font-weight:600; color:#2e3192;">Occupancy</td>
                    <td>{{ $quote->occupancy }}</td>
                </tr>
                <tr>
                    <td style="font-weight:600; color:#2e3192;">Jurisdiction</td>
                    <td>{{ $quote->jurisdiction }}</td>
                </tr>
                <tr>
                    <td style="font-weight:600; color:#2e3192;">Risk Locations</td>
                    <td>
                        @if ($quote->risk_locations)
                            <ul style="margin:0; padding-left:1.2em;">
                                @foreach (json_decode($quote->risk_locations, true) as $location => $sum)
                                    <li><strong>{{ $location }}</strong>: Sum Insured: {{ number_format($sum) }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td style="font-weight:600; color:#2e3192;">Property Damage</td>
                    <td>{{ $quote->property_damage }}</td>
                </tr>
                <tr>
                    <td style="font-weight:600; color:#2e3192;">Business Interruption</td>
                    <td>{{ $quote->business_interruption }}</td>
                </tr>
                <tr>
                    <td style="font-weight:600; color:#2e3192;">Total Sum Insured</td>
                    <td>{{ $quote->total_sum_insured }}</td>
                </tr>
                <tr>
                    <td style="font-weight:600; color:#2e3192;">Coverage</td>
                    <td>{{ $quote->coverage }}</td>
                </tr>
                <tr>
                    <td style="font-weight:600; color:#2e3192;">Limit of Indemnity</td>
                    <td>
                        @if ($quote->limit_of_indemnity)
                            <ul style="margin:0; padding-left:1.2em;">
                                @foreach (json_decode($quote->limit_of_indemnity, true) as $lim)
                                    <li>{{ $lim }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td style="font-weight:600; color:#2e3192;">Indemnity Period</td>
                    <td>{{ $quote->indemnity_period }}</td>
                </tr>
                <tr>
                    <td style="font-weight:600; color:#2e3192;">Additional Covers Opted</td>
                    <td>{{ $quote->additional_covers }}</td>
                </tr>
                <tr>
                    <td style="font-weight:600; color:#2e3192;">Claims</td>
                    <td>{{ $quote->claims }}</td>
                </tr>
                <tr>
                    <td style="font-weight:600; color:#2e3192;">Deductibles</td>
                    <td>{{ $quote->deductibles }}</td>
                </tr>
                <tr>
                    <td style="font-weight:600; color:#2e3192;">Premium</td>
                    <td>{{ $quote->premium }}</td>
                </tr>
                <tr>
                    <td style="font-weight:600; color:#2e3192;">Support</td>
                    <td>{{ $quote->support }}</td>
                </tr>
                <tr>
                    <td style="font-weight:600; color:#2e3192;">Remark 1</td>
                    <td>{{ $quote->remark1 }}</td>
                </tr>
                <tr>
                    <td style="font-weight:600; color:#2e3192;">Remark 2</td>
                    <td>{{ $quote->remark2 }}</td>
                </tr>
                <tr>
                    <td style="font-weight:600; color:#2e3192;">Remark 3</td>
                    <td>{{ $quote->remark3 }}</td>
                </tr>
                <tr>
                    <td style="font-weight:600; color:#2e3192;">Is Active</td>
                    <td>{{ $quote->is_active ? 'Yes' : 'No' }}</td>
                </tr>
                <tr>
                    <td style="font-weight:600; color:#2e3192;">Is Submit</td>
                    <td>{{ $quote->is_submit ? 'Yes' : 'No' }}</td>
                </tr>
                <tr>
                    <td style="font-weight:600; color:#2e3192;">Is Edit</td>
                    <td>{{ $quote->is_edit ? 'Yes' : 'No' }}</td>
                </tr>
                <tr>
                    <td style="font-weight:600; color:#2e3192;">Is Final Submit</td>
                    <td>{{ $quote->is_final_submit ? 'Yes' : 'No' }}</td>
                </tr>
                <tr>
                    <td style="font-weight:600; color:#2e3192;">Policy Wording</td>
                    <td>
                        @if ($quote->policy_wording)
                            <div style="background:#fff; border-radius:8px; padding:1rem; max-height:200px; overflow:auto;">
                                {!! $quote->policy_wording !!}
                            </div>
                        @endif
                    </td>
                </tr>
            </table>
            <div style="margin-top:2rem;">
                <a href="{{ route('quotes.list') }}"
                    style="background: #2e3192; color: #fff; border-radius: 8px; padding: 0.7rem 2.2rem; font-weight:600; text-decoration:none; font-size:1rem; box-shadow: 0 2px 8px #2e319222; transition: background 0.2s;">
                    <i class="fas fa-arrow-left"></i> Back to Quotes
                </a>
            </div>
        </div>
    </div>
@endsection
