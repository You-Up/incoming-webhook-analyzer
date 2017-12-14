<?php
/**
 * @date 2017-11-23
 * @see LINK_TO_DOCS
 */
namespace WebhookParser\Parsers\Sentry;

use WebhookParser\WebhookIncident;
use WebhookParser\Parser;

class Sentry_01 extends Parser
{
    /**
     * Detect if an incoming webhook should be process by this class
     *
     * @return bool
     */
    public function isMatch()
    {
        $userAgent = $this->request->userAgent();

        return substr($userAgent, 0, 6) === 'sentry' && str_contains($userAgent, 'sentry.io');
    }

    /**
     * Extract and format the information from the request into a WebhookIncident
     *
     * @return \WebhookParser\WebhookIncident
     */
    public function extract()
    {
        $incident = new WebhookIncident();

        $date = new \DateTime();
        $date->setTimestamp($this->request->post('event')['received']);
        $incident->setCreatedAt($date);

        $incident->setSummary($this->request->post('message'));

        $incident->setLink($this->request->post('url'));

        return $incident;
    }
}