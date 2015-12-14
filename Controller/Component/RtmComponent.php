<?php
/**
 * Slack API - rtm method implementation.
 *
 * @author        Hiroki Yagyu.
 * @link          https://github.com/HirokiYagyu/Slack
 * @package       Slack.Controller.Component
 * @since         SlackPlugin v1.0.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses('BaseComponent', 'Slack.Controller/Component');

/**
 * Slack API Rtm コンポーネント.
 *
 * @package       Slack.Controller.Component
 */
class RtmComponent extends BaseComponent
{
    /**
     * Slack API method name.
     * @var string
     * @see https://api.slack.com/methods
     */
    protected static $_method = 'rtm';

    /**
     * Authentication token.
     * @var string
     */
    const OPTION_TOKEN = 'token';

    /**
     * Return timestamp only for latest message object of each channel (improves performance).
     */
    const OPTION_SIMPLE_LATEST = 'simple_latest';

    /**
     * Skip unread counts for each channel (improves performance).
     */
    const OPTION_NO_UNREADS = 'no_unreads';

    /**
     * Returns MPIMs to the client in the API response.
     */
    const OPTION_MPIM_AWARE = 'mpim_aware';

    /**
     * Real Time Messaging セッションを開始する.
     *
     * This method starts a Real Time Messaging API session.
     * Refer to the RTM API documentation for full details on how to use the RTM API.
     *
     * ### Eg.
     * ```
     * $Rtm->start();
     * ```
     *
     * ### Response.
     * ```
     * {
     *     "ok": true,
     *     "self": {omit...},
     *     "team": {omit...},
     *     "latest_event_ts": "1440000000.000001",
     *     "channels": [
     *         {omit...}
     *     ],
     *     "groups": [
     *         {omit...}
     *     ],
     *     "ims": [
     *         {omit...}
     *     ],
     *     "cache_ts": 1440000000,
     *     "subteams": {
     *         "self": [],
     *         "all": []
     *     },
     *     "users": [
     *         {omit...}
     *     ],
     *     "cache_version": "v11-mouse",
     *     "cache_ts_version": "v1-cat",
     *     "bots": [
     *         {omit...}
     *     ],
     *     "url": "wss://msxxx.slack-msgs.com/websocket/{base64 encoded binary data.}"
     * }
     * ```
     *
     * ### Use Option.
     * <dl class="tree">
     *   <dt>RtmComponent::OPTION_TOKEN</dt>
     *     <dd>Authentication token (Requires scope: `client`)</dd>
     *   <dt>RtmComponent::OPTION_SIMPLE_LATEST</dt>
     *     <dd>Return timestamp only for latest message object of each channel (improves performance).</dd>
     *   <dt>RtmComponent::OPTION_NO_UNREADS</dt>
     *     <dd>Skip unread counts for each channel (improves performance).</dd>
     *   <dt>RtmComponent::OPTION_MPIM_AWARE</dt>
     *     <dd>Returns MPIMs to the client in the API response.</dd>
     * </dl>
     *
     * @param array $option オプション.
     * @return mixed レスポンスデータ.
     *
     * @see https://api.slack.com/methods/rtm.start
     */
    public function start( array $option=[] )
    {
        $requestData = [
            self::OPTION_TOKEN => Slack\API_TOKEN,
            self::OPTION_SIMPLE_LATEST => null,
            self::OPTION_NO_UNREADS => null,
            self::OPTION_MPIM_AWARE => null,
        ];
        $requestData = array_merge( $requestData, $option );
        $response = self::getRequest( 'start', self::_nullFilter($requestData) );
        return $response;
    }
}
