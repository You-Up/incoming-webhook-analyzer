<?php
/**
 * @date 2017-12-17
 *
 * @see https://cronitor.io/docs/third-party-integrations
 */

namespace WebhookParser\Parsers\Cronitor;

use WebhookParser\WebhookIncident;
use WebhookParser\Parser;

class Cronitor_01 extends Parser
{
    /**
     * Detect if an incoming webhook should be process by this class.
     *
     * @return bool
     */
    public function isMatch()
    {
        if (false === strpos($this->request->userAgent(), 'python-requests')) {
            return false;
        }
        $keys = array_keys($this->request->input());
        sort($keys);

        return $keys === ['description', 'id', 'monitor', 'rule'];
    }

    /**
     * Extract and format the information from the request into a WebhookIncident.
     *
     * @return \WebhookParser\WebhookIncident
     */
    public function extract()
    {
        $incident = new WebhookIncident();
        $requestTime = new \DateTime();
        $requestTime->setTimestamp($this->request->server('REQUEST_TIME'));
        $incident->setCreatedAt($requestTime);

        $incident->setAction(WebhookIncident::ACTION_CREATE);
        $incident->setExternalId($this->request->post('id'));

        if ('has_failed' === $this->request->post('rule')) {
            $incident->setSummary("Cronitor '".$this->request->post('monitor')."' has failed");
        } else {
            throw new \Exception('Cronitor rule not handled');
        }

        return $incident;
    }
}
