<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quote;
use App\Models\Note;
use App\Models\BankingDetail;
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
            // Handle Policy Period (To Be Advised or Dates)
            if (!empty($data['policy_start_date']) && !empty($data['policy_end_date'])) {
                $data['policy_period'] = date('d/m/Y', strtotime($data['policy_start_date'])) . ' - ' . date('d/m/Y', strtotime($data['policy_end_date']));
            } else {
                $data['policy_period'] = null;
            }

            // Handle Risk Locations as JSON (location + sum insured pairs)
            if (!empty($data['risk_location']) && !empty($data['risk_sum_insured'])) {
                $riskLocations = [];
                foreach ($data['risk_location'] as $index => $location) {
                    $location = trim($location);
                    $sum = isset($data['risk_sum_insured'][$index]) ? trim($data['risk_sum_insured'][$index]) : null;
                    if ($location !== '' && $sum !== null) {
                        $riskLocations[$location] = is_numeric($sum) ? (float)$sum : $sum;
                    }
                }
                $data['risk_locations'] = json_encode($riskLocations);
            } else {
                $data['risk_locations'] = null;
            }

            // Handle arrays as JSON
            $data['limit_of_indemnity'] = isset($data['limit_of_indemnity']) ? json_encode($data['limit_of_indemnity']) : null;
            $data['additional_covers'] = isset($data['additional_covers']) ? json_encode($data['additional_covers']) : null;
            $data['deductibles'] = isset($data['deductibles']) ? json_encode($data['deductibles']) : null;

            // Other fields
            $data['is_submit'] = 1;
            $data['user_id'] = Auth::user()->id;

            // Remove helper/unused fields
            unset(
                $data['policy_start_date'],
                $data['policy_end_date'],
                $data['policy_period_tba'],
                $data['risk_locations_json'],
                $data['risk_location'],
                $data['risk_sum_insured']
            );

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

        if (!empty($data['policy_start_date']) && !empty($data['policy_end_date'])) {
            $data['policy_period'] = date('d/m/Y', strtotime($data['policy_start_date'])) . ' - ' . date('d/m/Y', strtotime($data['policy_end_date']));
        } else {
            $data['policy_period'] = null;
        }

        // Handle Risk Locations as JSON (location + sum insured pairs)
        if (!empty($data['risk_location']) && !empty($data['risk_sum_insured'])) {
            $riskLocations = [];
            foreach ($data['risk_location'] as $index => $location) {
                $location = trim($location);
                $sum = isset($data['risk_sum_insured'][$index]) ? trim($data['risk_sum_insured'][$index]) : null;
                if ($location !== '' && $sum !== null) {
                    $riskLocations[$location] = is_numeric($sum) ? (float)$sum : $sum;
                }
            }
            $data['risk_locations'] = json_encode($riskLocations);
        } else {
            $data['risk_locations'] = null;
        }

        $data['limit_of_indemnity'] = isset($data['limit_of_indemnity']) ? json_encode($data['limit_of_indemnity']) : null;
        $data['additional_covers'] = isset($data['additional_covers']) ? json_encode($data['additional_covers']) : null;
        $data['deductibles'] = isset($data['deductibles']) ? json_encode($data['deductibles']) : null;
        $data['is_final_submit'] = $data['is_final_submit'] == 0 ? 0 : 1;

        unset(
            $data['policy_start_date'],
            $data['policy_end_date'],
            $data['policy_period_tba'],
            $data['risk_locations_json'],
            $data['risk_location'],
            $data['risk_sum_insured']
        );
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

    public function noteList($id)
    {
        $notes = Note::where('quote_id', $id)->get();

        return view('notes.list', compact('notes', 'id'));
    }

    public function createNote($id)
    {
        $quote_id = $id;
        $bankingDetails = BankingDetail::all();
        return view('notes.create', compact('bankingDetails', 'quote_id'));
    }

    public function storeNote(Request $request)
    {
        $particulars = json_decode($request->input('particulars'), true);

        $note = Note::create([
            'quote_id'         => $request->input('quote_id'),
            'bank_id'          => $request->input('bank_id'),
            'user_id'          => Auth::user()->id,
            'credit_type'      => $request->input('note_type'), // or 'credit_type' if that's your column
            'invoice_number'   => $request->input('invoice_number'),
            'date'             => $request->input('date'),
            'to'               => $request->input('to'),
            'reinsured'        => $request->input('reinsured'),
            'Reinsurer'        => $request->input('Reinsurer'),
            'original_insured' => $request->input('original_insured'),
            'PPW'              => $request->input('PPW'),
            'particulars'      => $particulars,
        ]);

        return redirect()->route('note.list', $request->input('quote_id'))->with('success', 'Note created successfully!');
    }

    public function editNote($id)
    {
        $note = \App\Models\Note::findOrFail($id);
        $bankingDetails = \App\Models\BankingDetail::all();
        return view('notes.edit', compact('note', 'bankingDetails'));
    }

    public function updateNote(Request $request, $id)
    {
        $note = \App\Models\Note::findOrFail($id);
        $particulars = json_decode($request->input('particulars'), true);

        $note->update([
            'bank_id'          => $request->input('bank_id'),
            'credit_type'      => $request->input('note_type'),
            'invoice_number'   => $request->input('invoice_number'),
            'date'             => $request->input('date'),
            'to'               => $request->input('to'),
            'reinsured'        => $request->input('reinsured'),
            'Reinsurer'        => $request->input('Reinsurer'),
            'original_insured' => $request->input('original_insured'),
            'PPW'              => $request->input('PPW'),
            'particulars'      => $particulars,
        ]);

        return redirect()->route('note.list', $note->quote_id)->with('success', 'Note updated successfully!');
    }

    public function downloadNotePdf($id)
    {
        $note = Note::findOrFail($id);
        $quote = Quote::findOrFail($note->quote_id);
        $bankingDetail = BankingDetail::findOrFail($note->bank_id);

        return Pdf::loadView('notes.pdf', compact('note'))
            ->setPaper('a4')
            ->download('note_' . $note->invoice_number . '.pdf');
        // return view('notes.pdf', compact('note'));
    }
}
