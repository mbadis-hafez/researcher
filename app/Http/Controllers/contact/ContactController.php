<?php

namespace App\Http\Controllers\contact;

use App\Exports\ContactsExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Contact\StoreByBulkContactRequest;
use App\Http\Requests\Contact\StoreContactRequest;
use App\Http\Requests\Contact\UpdateContactRequest;
use App\Http\Resources\ContactResource;
use App\Models\Contact;
use App\Repositories\ContactRepository;
use App\Services\ContactImportService;
use App\Traits\FileManagerTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ContactController extends Controller
{
    use FileManagerTrait;

    public function __construct(private ContactRepository $contactRepository) {}

    public function index() // Removed :View
    {
        $contacts = $this->contactRepository->getContacts();

        // Get statistics for both active and pending contacts
        $totalContacts = $contacts->count();
        $activeContacts = $contacts->where('status', '2')->count();
        $pendingContacts = $contacts->where('status', '1')->count();
        $percentageChange = $this->calculatePercentageChange(); // Implement this method
        $pendingPercentageChange = $this->calculatePendingPercentageChange();


        if (request()->ajax()) {
            return response()->json([
                'data' => ContactResource::collection($contacts)
            ]);
        }

        return view('content.apps.contact.index', [
            'contacts' => $contacts,
            'stats' => [
                'total_contacts' => $totalContacts,
                'active_contacts' => $activeContacts,
                'percentage_change' => $percentageChange,
                'pending_contacts' => $pendingContacts,
                'pending_percentage_change' => $pendingPercentageChange,
                'time_period' => 'Last week analytics'
            ]
        ]);
    }

    private function calculatePercentageChange()
    {
        // Implement your logic to calculate percentage change
        // For example, compare current week with previous week
        $currentWeekCount = Contact::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
        $lastWeekCount = Contact::whereBetween('created_at', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()])->count();

        if ($lastWeekCount == 0) return 0; // prevent division by zero

        return round((($currentWeekCount - $lastWeekCount) / $lastWeekCount) * 100);
    }

    private function calculatePendingPercentageChange()
    {
        $currentWeekPending = Contact::where('status', '1')
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->count();

        $lastWeekPending = Contact::where('status', '1')
            ->whereBetween('created_at', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()])
            ->count();

        if ($lastWeekPending == 0) return 0; // prevent division by zero

        return round((($currentWeekPending - $lastWeekPending) / $lastWeekPending) * 100);
    }

    public function show($id) {}

    public function create(): View
    {
        // $countries = DB::table('countries')->get();
        $countries = countries();
        return view('content.apps.contact.create', ['countries' => $countries]);
    }

    public function createWithBulk(): View
    {
        return view('content.apps.contact.create-with-bulk');
    }

    public function store(StoreContactRequest $request): RedirectResponse
    {
        $this->contactRepository->createContact($request->validated());

        return redirect()->route('app-contact-list')->with('success', trans('contact saved successfully'));
    }

    public function storeByBulk(StoreByBulkContactRequest $request, ContactImportService $contactImportService): RedirectResponse
    {
        try {
            $contactImportService->import($request->file('contacts_file'));
        } catch (\Exception $e) {
            Log::info($e->getMessage());
        }

        return back()->with('success', trans('Contacts saved successfully'));
    }

    public function edit($id): View
    {
        $contact = $this->contactRepository->getContactById($id);
        // $countries = DB::table('countries')->get();
        $countries = countries();
        return view('content.apps.contact.edit', ['contact' => $contact, 'countries' => $countries]);
    }

    public function update(UpdateContactRequest $request, $id): RedirectResponse
    {
        $this->contactRepository->updateContact($id, $request->validated());

        return redirect()->back()->with('success', trans('Artist updated successfully'));
    }

    public function destroy($id): RedirectResponse
    {
        $this->contactRepository->deleteContact($id);

        return redirect()->route('app-contact-list')->with('success', trans('Artist deleted successfully'));
    }

    public function exportList(Request $request): BinaryFileResponse|RedirectResponse
    {
        $contacts = $this->contactRepository->getAllContacts();
        $data = [
            'contacts' => $contacts,
        ];

        return Excel::download(new ContactsExport($data), 'contacts-list-' . now()->format('Y-m-d') . '.xlsx');
    }

}