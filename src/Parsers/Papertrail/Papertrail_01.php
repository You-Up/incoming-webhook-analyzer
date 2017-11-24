<?php
/**
 * @date 2017-11-10
 * @see https://help.papertrailapp.com/kb/how-it-works/web-hooks
 * @author Thomas Genin
 */
namespace WebhookParser\Parsers\Papertrail;

use WebhookParser\WebhookIncident;
use WebhookParser\Parser;

class Papertrail_01 extends Parser
{
    private $payload = null;

    public function isMatch()
    {
        if (!$this->request->has('payload')) {
            return false;
        }

        $this->payload = json_decode( $this->request->input( 'payload' ), true );

        $needle = 'https://papertrailapp.com/searches/2';
        return array_key_exists('saved_search', $this->payload) &&
            array_key_exists('html_edit_url', $this->payload['saved_search']) &&
            substr($this->payload['saved_search']['html_edit_url'],0, strlen($needle)) === $needle;
    }

    public function extract()
    {
        $incident = new WebhookIncident();

        // Take only 1 example of an event, as they all match the papertrail query
        $event = $this->payload['events'][0];
        $incident->setCreatedAt(new \DateTime($event['received_at']));

        $message = $this->payload['saved_search']['name'];
        $incident->setTitle("papertrail alert triggered \"{$message}\"");

        $incident->setLink($this->payload['saved_search']['html_search_url']);

        return $incident;
    }
}