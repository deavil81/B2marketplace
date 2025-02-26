<?php

namespace App\Http\Controllers;

use App\Notifications\ProposalAcceptedNotification;
use App\Models\Proposal;
use Illuminate\Http\Request;
use App\Models\User;

class ProposalController extends Controller
{
    public function acceptProposal($proposalId)
    {
        $proposal = Proposal::findOrFail($proposalId);

        // Ensure only the RFQ owner (buyer) can accept a proposal
        if (auth()->id() !== $proposal->rfq->user_id) {
            return back()->with('error', 'Unauthorized action.');
        }

        // Unselect all proposals for this RFQ
        Proposal::where('rfq_id', $proposal->rfq_id)->update(['selected' => 0]);

        // Select the chosen proposal
        $proposal->selected = 1;
        $proposal->save();

        // Notify the seller
        $seller = User::find($proposal->seller_id);
        if ($seller) {
            $seller->notify(new ProposalAcceptedNotification($proposal));
        }

        return back()->with('success', 'Proposal accepted and seller notified.');
    }
        
}
