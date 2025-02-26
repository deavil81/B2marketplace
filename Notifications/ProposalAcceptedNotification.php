<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Bus\Queueable;

class ProposalAcceptedNotification extends Notification
{
    use Queueable;

    protected $rfq;
    
    public function __construct($rfq)
    {
        $this->rfq = $rfq;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Proposal Accepted!',
            'message' => "Your proposal for RFQ '{$this->rfq->title}' has been accepted.",
            'rfq_id' => $this->rfq->id,
            'buyer_id' => $this->rfq->user_id, // Store buyer ID for messaging
            'type' => 'proposal_accepted',
            'message_link' => route('messages.show', $this->rfq->user_id), // Link to chat
        ];
    }
}

