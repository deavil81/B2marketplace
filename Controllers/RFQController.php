<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Rfq;
use Illuminate\Support\Facades\Auth;
use App\Models\Proposal;
use App\Models\User;
use App\Models\Response;
use App\Notifications\ProposalAcceptedNotification;

class RFQController extends Controller
{
    public function create()
    {
        // Check if the logged-in user is a buyer
        if (auth()->user()->role !== 'buyer') {
            abort(403, 'Unauthorized access');
        }

        // Fetch all categories from the database
        $categories = Category::all();

        // Pass the categories to the view
        return view('rfqs.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'required|exists:subcategories,id',
            'quantity' => 'required|integer|min:1',
            'budget' => 'required|numeric|min:1',
            'deadline' => 'required|date|after:today',
        ]);
    
        // Check for existing RFQ with the same title, description, and category
        $existingRFQ = RFQ::where('title', $request->title)
                          ->where('description', $request->description)
                          ->where('category_id', $request->category_id)
                          ->first();
    
        if ($existingRFQ) {
            return redirect()->back()->withErrors(['duplicate' => 'An RFQ with similar details already exists.']);
        }
    
        // Create the new RFQ if no duplicate exists
        RFQ::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'subcategory_id' => $request->subcategory_id,
            'quantity' => $request->quantity,
            'budget' => $request->budget,
            'deadline' => $request->deadline,
            'status' => 'Open',
        ]);
    
        return redirect()->route('rfqs.index')->with('success', 'RFQ created successfully.');
    }
    
    public function index()
    {
        // Ensure only buyers can view RFQs
        if (auth()->user()->role !== 'buyer') {
            abort(403, 'Unauthorized access');
        }

        // Fetch RFQs created by the logged-in user that have not expired
        $rfqs = RFQ::with('category')
                ->where('user_id', auth()->id())
                ->where('deadline', '>=', now())
                ->get();

        return view('rfqs.index', compact('rfqs'));
    }
    public function authrfqs()
    {
        // Ensure only buyers can view RFQs
        if (auth()->user()->role !== 'buyer') {
            abort(403, 'Unauthorized access');
        }

        // Fetch RFQs created by the logged-in user that have not expired
        $rfqs = RFQ::with('category')
                ->where('user_id', auth()->id())
                ->where('deadline', '>=', now())
                ->get();

        return view('rfqs.authrfqs', compact('rfqs'));
    }

    public function list()
    {
        if (auth()->user()->role !== 'seller') {
            abort(403, 'Unauthorized access');
        }

        // Debugging: Check if the new view exists
        if (!view()->exists('manufacturers.list')) {
            return response()->json([
                'error' => "View file 'manufacturers.list' does not exist!",
                'available_views' => scandir(resource_path('views/manufacturers'))
            ]);
        }

        $rfqs = RFQ::where('deadline', '>=', now())->with('category')->get();

        return view('manufacturers.list', compact('rfqs'));
    }

    public function submitProposal(Request $request, $rfqId)
    {
        $request->validate([
            
            'price' => 'required|numeric|min:1',
            'lead_time' => 'required|integer|min:1',
            'description' => 'required|string',
            'moq' => 'nullable|integer|min:1',
            'payment_terms' => 'nullable|string',
            'shipping_terms' => 'nullable|string',
            'attachment' => 'nullable|file|mimes:pdf,jpg,png,doc,docx|max:2048',
        ]);
    
        // Handle file upload
        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('proposals', 'public');
        }
    
        // Save proposal
        Proposal::create([
            'rfq_id' => $rfqId,
            'seller_id' => auth()->id(),
            'price' => $request->price,
            'lead_time' => $request->lead_time,
            'description' => $request->description,
            'moq' => $request->moq,
            'payment_terms' => $request->payment_terms,
            'shipping_terms' => $request->shipping_terms,
            'attachment' => $attachmentPath,
        ]);
        
        // Save response in the responses table
        Response::create([
            'rfq_id' => $rfqId,
            'user_id' => auth()->id(), // Since we are using user_id instead of manufacturer_id
            'proposal' => $request->description, // Assuming proposal is stored as description
            'price' => $request->price,
            'status' => 'pending',
        ]);
    
        return back()->with('success', 'Proposal submitted successfully.');
    }

    public function viewProposals($rfqId)
    {
        $rfq = RFQ::with('proposals.user')->findOrFail($rfqId);
        
        // Ensure only the buyer who created the RFQ can see proposals
        if (auth()->id() !== $rfq->user_id) {
            abort(403, 'Unauthorized access');
        }
        return view('rfqs.proposals', compact('rfq'));
    }
   
    public function show(RFQ $rfq)
    {
        return view('rfqs.show', compact('rfq'));
    }

    public function close(Rfq $rfq)
    {
        // Ensure the logged-in user owns this RFQ
        if (Auth::id() !== $rfq->user_id) {
            return redirect()->route('buyer.dashboard')->with('error', 'Unauthorized action.');
        }

        // Update RFQ status to closed
        $rfq->update(['status' => 'closed']);

        return redirect()->route('buyer.dashboard')->with('success', 'RFQ closed successfully.');
    }

    public function open(Rfq $rfq)
    {
        // Ensure the logged-in user owns this RFQ
        if (Auth::id() !== $rfq->user_id) {
            return redirect()->route('buyer.dashboard')->with('error', 'Unauthorized action.');
        }

        // Update RFQ status to open
        $rfq->update(['status' => 'open']);

        return redirect()->route('buyer.dashboard')->with('success', 'RFQ opened successfully.');
    }
    
}
