<?php

namespace App\Listeners;

use App\Events\CommentSent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Message;

class SendMessage
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  CommentSent  $event
     * @return void
     */
    public function handle(CommentSent $event)
    {
        Message::create([
            "title"=>"新しいコメントが届いています",
            "body"=>"あなたの作成した問題に改善要望が届いています。「確認する」をクリックし、届いた確認要望をご確認ください。",
            "user_id"=>$event->sendToId,
            "link_type"=>"comment",
            "link_data"=>json_encode([
                "section_id"=>$event->sectionId,
                "question_id"=>$event->questionId,
            ])
        ]);
    }
}
