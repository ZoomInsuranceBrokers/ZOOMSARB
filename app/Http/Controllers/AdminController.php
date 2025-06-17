<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quote;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminController extends Controller
{
    public function index()
    {
        return view('dashboard');
    }

    public function createQuote()
    {
        return view('quotes.create');
    }

    public function storeQuote(Request $request)
    {
        try {
            $data = $request->all();

            $data['risk_locations'] = isset($data['risk_locations']) ? json_encode($data['risk_locations']) : null;
            $data['limit_of_indemnity'] = isset($data['limit_of_indemnity']) ? json_encode($data['limit_of_indemnity']) : null;
            $data['is_submit'] = 1;
            $data['user_id'] = Auth::user()->id;
            Quote::create($data);

            return redirect()->route('dashboard')->with('success', 'Quote created successfully!');
        } catch (\Exception $e) {
            return redirect()->route('dashboard')->with('error', 'Failed to create quote. Please try again.');
        }
    }

    public function quotesList()
    {
        $quotes = Quote::where('is_active', 1)->get();
        return view('quotes.list', compact('quotes'));
    }

    public function showQuotes($id)
    {
        $quote = Quote::findOrFail($id);
        return view('quotes.show', compact('quote'));
    }

    public function editQuotes($id)
    {
        $quote = Quote::findOrFail($id);
        return view('quotes.edit', compact('quote'));
    }

    public function updateQuotes(Request $request, $id)
    {
        $quote = Quote::findOrFail($id);

        $data = $request->all();
        $data['risk_locations'] = isset($data['risk_locations']) ? json_encode($data['risk_locations']) : null;
        $data['limit_of_indemnity'] = isset($data['limit_of_indemnity']) ? json_encode($data['limit_of_indemnity']) : null;

        $quote->update($data);

        return redirect()->route('quotes.show', $quote->id)->with('success', 'Quote updated successfully!');
    }
    public function destroyQuotes($id)
    {
        $quote = Quote::findOrFail($id);
        $quote->is_active = 0;
        $quote->save();

        return redirect()->route('quotes.list')->with('success', 'Quote deleted (deactivated) successfully!');
    }

    public function policyWording($id)
    {
        $quote = Quote::findOrFail($id);
        return view('quotes.policy_wording', compact('quote'));
    }

    public function savePolicyWording(Request $request, $id)
    {
        $quote = Quote::findOrFail($id);
        $quote->policy_wording = $request->input('policy_wording');
        $quote->save();

        return redirect()->route('quotes.show', $id)->with('success', 'Policy wording updated successfully!');
    }

    public function downloadPdf($id)
    {
        $quote = \App\Models\Quote::findOrFail($id);

        $pdf = Pdf::loadView('quotes.pdf', compact('quote'));
        return $pdf->download('quote_' . $id . '.pdf');
    }

    public function finalSubmitAndDownload($id)
    {
        $quote = Quote::findOrFail($id);
        $quote->is_final_submit = 1;
        $quote->save();

        return redirect()->route('quotes.download', $id);
    }
}
