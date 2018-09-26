<?php

namespace OutboundHook;

use Supplier\GeneralStatics;
use Cortex\LanguageProcessor;

class Hub
{

    private const fbmeSendAPI = 'https://graph.facebook.com/v3.1/me/';

    private $recipient_id;

    public function __construct($message, $sender) {
        $this->recipient_id = $sender;
        if ($message->text) {
            $lingo = new LanguageProcessor;
            $this->sendMessage($lingo->feedIn($message->text));
        } elseif ($message->attachments) {
            $this->sendMessage('`AttachmentHandler` is still a work in progress.');
        }
    }

    private function sendMessage($message) {
        $this->setSenderAction('mark_seen', false);
        $this->setSenderAction('typing_on');
        $data->recipient->id = $this->recipient_id;
        if (is_array($message)) {
            foreach ($message as $item) {
                $data->message->text = $item;
                $return[] = $this->postRequest($data, 'messages');
            }
        } else {
            $data->message->text = $message;
            $return = $this->postRequest($data, 'messages');
        }
        return $return;
    }

    private function sendAttachment($attachment_id, $attachment_type) {
        $this->setSenderAction('mark_seen', false);
        $data->recipient->id = $this->recipient_id;
        $data->message->attachment->type = $attachment_type;
        $data->message->attachment->payload->attachment_id = $attachment_id;
        return $this->postRequest($data, 'messages');
    }

    private function setSenderAction($flag, $delay = true) {
        $data->recipient->id = $this->recipient_id;
        $data->sender_action = $flag;
        $action = $this->postRequest($data, 'messages');
        if ($delay)
            sleep(2);
        return $action;
    }

    private function uploadAttachmentFromURL($url, $file_type) {
        $data->message->attachment->type = $file_type;
        $data->message->attachment->payload->is_reusable = true;
        $data->message->attachment->payload->url = $url;
        $action = $this->postRequest($data, 'message_attachments');
        return $action->attachment_id;
    }

    private function postRequest($data, $handler) {
        return json_decode(file_get_contents(
            self::fbmeSendAPI . $handler . '?access_token='.
            GeneralStatics::getConfig('page_access_token'), false,
            stream_context_create(array ('http' =>
                    array (
                        'method'  => 'POST',
                        'header'  => 'Content-Type: application/json',
                        'content' => json_encode($data)
                    )
                )
            )));
    }

}