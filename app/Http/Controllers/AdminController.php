<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quote;
use App\Models\Note;
use App\Models\PlacementSlip;
use App\Models\BankingDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
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

            // Handle Risk Locations as JSON (location + property damage + business interruption + total)
            if (!empty($data['risk_location'])) {
                $riskLocations = [];
                foreach ($data['risk_location'] as $index => $location) {
                    $location = trim($location);
                    $propertyDamage = isset($data['risk_property_damage'][$index]) ? (float)trim($data['risk_property_damage'][$index]) : 0;
                    $businessInterruption = isset($data['risk_business_interruption'][$index]) ? (float)trim($data['risk_business_interruption'][$index]) : 0;
                    $totalSum = isset($data['risk_total_sum'][$index]) ? (float)trim($data['risk_total_sum'][$index]) : 0;

                    if ($location !== '') {
                        $riskLocations[] = [
                            'location' => $location,
                            'property_damage' => $propertyDamage,
                            'business_interruption' => $businessInterruption,
                            'total_sum_insured' => $totalSum
                        ];
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
                $data['risk_property_damage'],
                $data['risk_business_interruption'],
                $data['risk_total_sum']
            );

            Quote::create($data);

            return redirect()->route('dashboard')->with('success', 'Quote created successfully!');
        } catch (\Exception $e) {
            return redirect()->route('dashboard')->with('error', 'Failed to create quote. Please try again.');
        }
    }

    public function quotesList()
    {
        $quotes = Quote::where('is_active', 1)->orderBy('id', 'desc')->get();

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

        // Handle Risk Locations as JSON (location + property damage + business interruption + total)
        if (!empty($data['risk_location'])) {
            $riskLocations = [];
            foreach ($data['risk_location'] as $index => $location) {
                $location = trim($location);
                $propertyDamage = isset($data['risk_property_damage'][$index]) ? (float)trim($data['risk_property_damage'][$index]) : 0;
                $businessInterruption = isset($data['risk_business_interruption'][$index]) ? (float)trim($data['risk_business_interruption'][$index]) : 0;
                $totalSum = isset($data['risk_total_sum'][$index]) ? (float)trim($data['risk_total_sum'][$index]) : 0;

                if ($location !== '') {
                    $riskLocations[] = [
                        'location' => $location,
                        'property_damage' => $propertyDamage,
                        'business_interruption' => $businessInterruption,
                        'total_sum_insured' => $totalSum
                    ];
                }
            }
            $data['risk_locations'] = json_encode($riskLocations);
        } else {
            $data['risk_locations'] = null;
        }

        // Handle reinsurers with percentages and brokerage
        if (!empty($data['reinsurers_json'])) {
            $data['reinsurers'] = $data['reinsurers_json'];
        } else if (!empty($data['reinsurer_names']) && !empty($data['reinsurer_percentages'])) {
            $reinsurersArray = [];
            foreach ($data['reinsurer_names'] as $index => $name) {
                $name = trim($name);
                $percentage = isset($data['reinsurer_percentages'][$index]) ? (float)$data['reinsurer_percentages'][$index] : 0;
                $brokerage = isset($data['reinsurer_brokerages'][$index]) ? (float)$data['reinsurer_brokerages'][$index] : 0;
                $ceding = isset($data['reinsurer_ceding_commissions'][$index]) ? (float)$data['reinsurer_ceding_commissions'][$index] : 0;

                if ($name !== '') {
                    $reinsurersArray[] = [
                        'name' => $name,
                        'percentage' => $percentage,
                        'brokerage' => $brokerage,
                        'ceding_commission' => $ceding
                    ];
                }
            }
            $data['reinsurer'] = !empty($reinsurersArray) ? json_encode($reinsurersArray) : null;
        } else {
            $data['reinsurer'] = null;
        }

        // Handle brokerage percentage with default value
        $data['brokerage_percentage'] = isset($data['brokerage_percentage']) && $data['brokerage_percentage'] !== ''
            ? (float)$data['brokerage_percentage']
            : 100.00;

        $data['limit_of_indemnity'] = isset($data['limit_of_indemnity']) ? json_encode($data['limit_of_indemnity']) : null;
        $data['additional_covers'] = isset($data['additional_covers']) ? json_encode($data['additional_covers']) : null;
        $data['deductibles'] = isset($data['deductibles']) ? json_encode($data['deductibles']) : null;

        unset(
            $data['policy_start_date'],
            $data['policy_end_date'],
            $data['policy_period_tba'],
            $data['risk_locations_json'],
            $data['risk_location'],
            $data['risk_property_damage'],
            $data['risk_business_interruption'],
            $data['risk_total_sum'],
            $data['reinsurer_names'],
            $data['reinsurer_percentages'],
            $data['reinsurer_brokerages'],
            $data['reinsurers_json']
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

    public function downloadPdf(Request $request, $id)
    {
        $quote = \App\Models\Quote::findOrFail($id);

        $currency = $request->get('currency', 'INR');
        $exchangeRate = $request->get('exchange_rate', 1);
        $mode = $request->get('mode', 'download'); // Get mode from query parameter

        if ($currency !== 'INR' && (!$exchangeRate || !is_numeric($exchangeRate) || $exchangeRate <= 0)) {
            return redirect()->back()->with('error', 'Invalid exchange rate provided.');
        }

        $exchangeRate = (float) $exchangeRate;

        $pdf = Pdf::loadView('quotes.pdf', compact('quote', 'currency', 'exchangeRate'));

        // If mode is 'view', stream the PDF inline; otherwise download it
        if ($mode === 'view') {
            return $pdf->stream('quote_' . $id . '_' . $currency . '.pdf');
        }

        return $pdf->download('quote_' . $id . '_' . $currency . '.pdf');
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

    public function createDebitNote($id)
    {
        $quote_id = $id;
        $bankingDetails = BankingDetail::all();
        $quote = Quote::findOrFail($id);
        return view('notes.debit_create', compact('bankingDetails', 'quote_id', 'quote'));
    }

    public function createCreditNote($id)
    {
        $quote_id = $id;
        $bankingDetails = BankingDetail::all();
        $quote = Quote::findOrFail($id);
        return view('notes.credit_create', compact('bankingDetails', 'quote_id', 'quote'));
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

        // Conditionally load banking details, as they may not exist for credit notes
        $bankingDetail = null;
        if ($note->bank_id) {
            $bankingDetail = BankingDetail::find($note->bank_id);
        }

        // Create a safe filename by replacing slashes with hyphens
        $filename =  'note_' . $note->id . '.pdf';

        // Pass all necessary data to the PDF view
        $pdf = Pdf::loadView('notes.pdf', compact('note', 'quote', 'bankingDetail'));

        return $pdf->setPaper('a4')->download($filename);
    }
    public function storeCreditNote(Request $request)
    {
        try {
            $request->validate([
                'quote_id' => 'required|exists:quotes,id',
                'date' => 'required|date',
                'to' => 'required|string|max:255',
                'selected_reinsurer' => 'required|string',
                'total_premium' => 'required|numeric|min:0',
                'ceding_commission' => 'nullable|numeric|min:0|max:100',
                'ri_brokerage' => 'nullable|numeric|min:0|max:100',
                'particulars' => 'required|string',
                'reinsurer_name' => 'required|string',
                'reinsurer_percentage' => 'required|numeric|min:0|max:100',
            ]);

            // Decode the particulars JSON from frontend
            $particulars = json_decode($request->input('particulars'), true);

            // Create the note
            $note = Note::create([
                'quote_id' => $request->input('quote_id'),
                'user_id' => Auth::user()->id,
                'credit_type' => 'credit',
                'date' => $request->input('date'),
                'to' => $request->input('to'),
                'particulars' => json_encode($particulars), // Store as JSON
                'reinsurer_name' => $request->input('reinsurer_name'),
                'total_premium' => $request->input('total_premium'),
                'invoice_number' => $this->generateInvoiceNumber('credit'), // Pass note type
            ]);

            return redirect()->route('note.list', $request->quote_id)
                ->with('success', 'Credit note created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to create credit note: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function storeDebitNote(Request $request)
    {
        try {
            // 1. Validate the incoming request data
            $request->validate([
                'quote_id' => 'required|exists:quotes,id',
                'bank_id' => 'required|exists:banking_details,id',
                'date' => 'required|date',
                'to' => 'required|string|max:255',
                'PPW' => 'required|string|max:255',
                'total_premium' => 'required|numeric|min:0',
                'particulars' => 'required|string', // The JSON string from the form
            ]);

            // 2. The 'particulars' field is already a well-formed JSON string.
            // We just need to save it directly.
            $particularsJson = $request->input('particulars');

            // 3. Create the new Debit Note record
            $note = Note::create([
                'quote_id' => $request->input('quote_id'),
                'user_id' => Auth::user()->id,
                'bank_id' => $request->input('bank_id'),
                'credit_type' => 'debit', // Set note type to debit
                'invoice_number' => $this->generateInvoiceNumber('debit'), // Generate debit note invoice number
                'date' => $request->input('date'),
                'to' => $request->input('to'),
                'PPW' => $request->input('PPW'),
                'total_premium' => $request->input('total_premium'),
                'particulars' => $particularsJson, // Save the complete JSON string

                // Set these fields based on the 'to' field for consistency
                'reinsured' => $request->input('to'),
                'Reinsurer' => 'Multiple', // As details are in particulars
                'original_insured' => $request->input('to'),
            ]);

            // 4. Redirect with a success message
            return redirect()->route('note.list', $request->quote_id)
                ->with('success', 'Debit note created successfully!');
        } catch (\Exception $e) {
            // 5. Handle any errors and redirect back with an error message
            return redirect()->back()
                ->with('error', 'Failed to create debit note: ' . $e->getMessage())
                ->withInput();
        }
    }
    private function generateInvoiceNumber($noteType = 'credit')
    {
        // Determine note type prefix
        $typePrefix = ($noteType === 'debit') ? 'DN' : 'CN';

        // Get current financial year
        $currentDate = now();
        $currentYear = $currentDate->year;
        $currentMonth = $currentDate->month;

        // Financial year starts from April (month 4)
        if ($currentMonth >= 4) {
            $financialYearStart = $currentYear;
            $financialYearEnd = $currentYear + 1;
        } else {
            $financialYearStart = $currentYear - 1;
            $financialYearEnd = $currentYear;
        }

        $financialYear = $financialYearStart . '-' . substr($financialYearEnd, 2, 2);

        // Create the pattern to search for existing notes
        $pattern = 'Zoom/RI/' . $typePrefix . '/' . $financialYear . '/%';

        // Get the last note number for this financial year and type
        $lastNote = Note::where('credit_type', $noteType)
            ->where('invoice_number', 'LIKE', $pattern)
            ->orderBy('invoice_number', 'desc')
            ->first();

        if ($lastNote) {
            // Extract the last number from format: Zoom/RI/CN/2025-26/01
            $parts = explode('/', $lastNote->invoice_number);
            $lastNumber = intval(end($parts));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        // Format: Zoom/RI/CN/2025-26/01 or Zoom/RI/DN/2025-26/01
        return 'Zoom/RI/' . $typePrefix . '/' . $financialYear . '/' . str_pad($newNumber, 2, '0', STR_PAD_LEFT);
    }

    public function placementSlip($id)
    {
        $quote = Quote::findOrFail($id);

        $placement_slip = PlacementSlip::where('quote_id', $id)->get();

        return view('quotes.placement_slip', compact('quote', 'placement_slip'));
    }

    public function createPlacementSlip($id)
    {
        $quote = Quote::findOrFail($id);
        return view('quotes.create_placement_slip', compact('quote'));
    }

    public function editPlacementSlip($id)
    {
        $placementSlip = PlacementSlip::findOrFail($id);
        return view('quotes.edit_placement_slip', compact('placementSlip'));
    }

    public function downloadPlacementSlip($id)
    {
        $placementSlip = PlacementSlip::findOrFail($id);
        $quote = Quote::findOrFail($placementSlip->quote_id);
        // Create a safe filename by replacing slashes with hyphens
        $filename = 'placement_slip_' . $placementSlip->id . '.pdf';

        // Pass all necessary data to the PDF view
        $pdf = Pdf::loadView('quotes.placement_slip_pdf', compact('placementSlip', 'quote'));

        return $pdf->setPaper('a4')->download($filename);
    }
    public function storePlacementSlip(Request $request)
    {
        $request->validate([
            'quote_id' => 'required|exists:quotes,id',
            'reinsurer_name' => 'required|string|max:255',
            'to' => 'required|string|max:255',
            'policy_wording' => 'required|string',
            'placement_type' => 'required|in:PPW,PPC',
        ]);

        $data = $request->all();

        // Handle Policy Period
        if (!empty($data['policy_start_date']) && !empty($data['policy_end_date'])) {
            $data['policy_period'] = date('d/m/Y', strtotime($data['policy_start_date'])) . ' - ' . date('d/m/Y', strtotime($data['policy_end_date']));
        } else {
            $data['policy_period'] = null;
        }

        // Handle Risk Locations as JSON
        if (!empty($data['risk_location'])) {
            $riskLocations = [];
            foreach ($data['risk_location'] as $index => $location) {
                $location = trim($location);
                $propertyDamage = isset($data['risk_property_damage'][$index]) ? (float)trim($data['risk_property_damage'][$index]) : 0;
                $businessInterruption = isset($data['risk_business_interruption'][$index]) ? (float)trim($data['risk_business_interruption'][$index]) : 0;
                $totalSum = isset($data['risk_total_sum'][$index]) ? (float)trim($data['risk_total_sum'][$index]) : 0;

                if ($location !== '') {
                    $riskLocations[] = [
                        'location' => $location,
                        'property_damage' => $propertyDamage,
                        'business_interruption' => $businessInterruption,
                        'total_sum_insured' => $totalSum
                    ];
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

        // Remove helper/unused fields
        unset(
            $data['policy_start_date'],
            $data['policy_end_date'],
            $data['policy_period_tba'],
            $data['risk_locations_json'],
            $data['risk_location'],
            $data['risk_property_damage'],
            $data['risk_business_interruption'],
            $data['risk_total_sum']
        );

        PlacementSlip::create($data);

        return redirect()->route('quotes.placement-slip', $request->input('quote_id'))
            ->with('success', 'Placement slip created successfully!');
    }
    public function updatePlacementSlip(Request $request, $id)
    {
        $request->validate([
            'policy_wording' => 'required|string',
            'placement_type' => 'required|in:PPW,PPC',
        ]);

        $placementSlip = PlacementSlip::findOrFail($id);

        $data = $request->all();

        // Handle Policy Period
        if (!empty($data['policy_start_date']) && !empty($data['policy_end_date'])) {
            $data['policy_period'] = date('d/m/Y', strtotime($data['policy_start_date'])) . ' - ' . date('d/m/Y', strtotime($data['policy_end_date']));
        } else {
            $data['policy_period'] = null;
        }

        // Handle Risk Locations as JSON
        if (!empty($data['risk_location'])) {
            $riskLocations = [];
            foreach ($data['risk_location'] as $index => $location) {
                $location = trim($location);
                $propertyDamage = isset($data['risk_property_damage'][$index]) ? (float)trim($data['risk_property_damage'][$index]) : 0;
                $businessInterruption = isset($data['risk_business_interruption'][$index]) ? (float)trim($data['risk_business_interruption'][$index]) : 0;
                $totalSum = isset($data['risk_total_sum'][$index]) ? (float)trim($data['risk_total_sum'][$index]) : 0;

                if ($location !== '') {
                    $riskLocations[] = [
                        'location' => $location,
                        'property_damage' => $propertyDamage,
                        'business_interruption' => $businessInterruption,
                        'total_sum_insured' => $totalSum
                    ];
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

        // Remove helper/unused fields
        unset(
            $data['_token'],
            $data['_method'],
            $data['policy_start_date'],
            $data['policy_end_date'],
            $data['policy_period_tba'],
            $data['risk_locations_json'],
            $data['risk_location'],
            $data['risk_property_damage'],
            $data['risk_business_interruption'],
            $data['risk_total_sum']
        );

        $placementSlip->update($data);

        return redirect()->route('quotes.placement-slip', $placementSlip->quote_id)
            ->with('success', 'Placement slip updated successfully!');
    }
    public function uploadSignedSlip(Request $request, $id)
    {
        $request->validate([
            'signed_slip' => 'required|file|mimes:pdf,jpg,jpeg,png',
        ]);

        $placementSlip = PlacementSlip::findOrFail($id);

        if ($request->hasFile('signed_slip')) {
            $file = $request->file('signed_slip');
            $filename = 'signed_slip_' . $placementSlip->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('signed_slips', $filename, 'public');
            $placementSlip->signed_slip = $path;
            $placementSlip->save();
        }

        return redirect()->back()->with('success', 'Signed slip uploaded successfully!');
    }

    public function uploadSignedNote(Request $request, $id)
    {
        $note = Note::findOrFail($id);

        $request->validate([
            'signed_note' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240', // 10MB max
        ]);

        if ($request->hasFile('signed_note')) {
            // Delete old signed note if exists
            if ($note->signed_note && Storage::disk('public')->exists($note->signed_note)) {
                Storage::disk('public')->delete($note->signed_note);
            }

            $file = $request->file('signed_note');
            $filename = 'signed_note_' . $note->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('signed_notes', $filename, 'public');
            $note->signed_note = $path;
            $note->save();
        }

        return redirect()->back()->with('success', 'Signed note uploaded successfully!');
    }

    public function downloadSignedNote($id)
    {
        $note = Note::findOrFail($id);

        if (!$note->signed_note || !Storage::disk('public')->exists($note->signed_note)) {
            return redirect()->back()->with('error', 'Signed note not found!');
        }

        $filePath = Storage::disk('public')->path($note->signed_note);

        // Sanitize the invoice number by removing invalid characters
        $sanitizedInvoiceNumber = preg_replace('/[\/\\\\:*?"<>|]/', '_', $note->invoice_number);
        $filename = 'signed_note_' . $sanitizedInvoiceNumber . '.' . pathinfo($note->signed_note, PATHINFO_EXTENSION);

        return response()->download($filePath, $filename);
    }
}
