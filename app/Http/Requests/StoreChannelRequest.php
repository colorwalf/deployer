<?php

namespace REBELinBLUE\Deployer\Http\Requests;

/**
 * Request for validating channels.
 */
class StoreChannelRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return array_merge([
            'name'                       => 'required|max:255',
            'project_id'                 => 'required|integer|exists:projects,id',
            'type'                       => 'required|in:slack,hipchat,twilio,mail,custom',
            'on_deployment_success'      => 'boolean',
            'on_deployment_failure'      => 'boolean',
            'on_link_down'               => 'boolean',
            'on_link_still_down'         => 'boolean',
            'on_link_recovered'          => 'boolean',
            'on_heartbeat_missing'       => 'boolean',
            'on_heartbeat_still_missing' => 'boolean',
            'on_heartbeat_recovered'     => 'boolean',
        ], $this->configRules());
    }

    /**
     * Gets the input which are allowed in the request based on the type.
     *
     * @return array
     */
    public function configOnly()
    {
        return $this->only(array_keys($this->configRules()));
    }

    /**
     * Validation rules specific to slack.
     *
     * @return array
     */
    private function slackRules()
    {
        return [
            'channel' => 'required|max:255|channel',
            'webhook' => 'required|regex:/^https:\/\/hooks.slack.com\/services\/[a-z0-9]+\/[a-z0-9]+\/[a-z0-9]+$/i',
            'icon'    => 'string|nullable|regex:/^:(.*):$/',
        ];
    }

    /**
     * Validation rules specific to email.
     *
     * @return array
     */
    private function mailRules()
    {
        return [
            'email' => 'required|email',
        ];
    }

    /**
     * Validation rules specific to hipchat.
     *
     * @return array
     */
    private function hipchatRules()
    {
        return [
            'room' => 'required',
        ];
    }

    /**
     * Validation rules specific to twilio.
     *
     * @return array
     */
    private function twilioRules()
    {
        return [
            'telephone' => 'required', // FIXME: REGULAR EXPRESSION
        ];
    }

    /**
     * Validation rules specific to custom channels.
     *
     * @return array
     */
    private function customRules()
    {
        return [
            'url' => 'required|url',
        ];
    }

    /**
     * Gets the additional rules based on the type from the request.
     *
     * @return array
     */
    private function configRules()
    {
        $type = $this->get('type') . 'Rules';
        if (method_exists($this, $type)) {
            return $this->{$type}();
        }

        return [];
    }
}
