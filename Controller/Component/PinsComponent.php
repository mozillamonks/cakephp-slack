<?php
/**
 * Slack API - pins method implementation.
 *
 * @author        Hiroki Yagyu.
 * @link          https://github.com/HirokiYagyu/Slack
 * @package       Slack.Controller.Component
 * @since         SlackPlugin v1.0.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses('BaseComponent', 'Slack.Controller/Component');

/**
 * Slack API Pins コンポーネント.
 *
 * @package       Slack.Controller.Component
 */
class PinsComponent extends BaseComponent
{
    /**
     * Slack API method name.
     * @var string
     * @see https://api.slack.com/methods
     */
    protected static $_method = 'pins';

    /**
     * Authentication token.
     * @var string
     */
    const OPTION_TOKEN = 'token';

    /**
     * Channel to pin the item in.
     * @var string
     */
    const OPTION_CHANNEL = 'channel';

    /**
     * File to pin.
     * @var string
     */
    const OPTION_FILE = 'file';

    /**
     * File comment to pin.
     * @var string
     */
    const OPTION_FILE_COMMENT = 'file_comment';

    /**
     * Timestamp of the message to pin.
     * @var string
     */
    const OPTION_TIMESTAMP = 'timestamp';

    /**
     * ピンを追加する.
     *
     * This method pins an item (file, file comment, channel message, or group message) to a particular channel.
     * The `channel` argument is required and one of `file`, `file_comment`, or `timestamp` must also be specified.
     *
     * ### Eg.
     * ```
     * $Pins->add('C01234567', [
     *     PinsComponent::OPTION_FILE_TIMESTAMP => '1440000000.000001'
     * ]);
     * ```
     *
     * ### Response.
     * ```
     * {
     *     "ok": true
     * }
     * ```
     *
     * ### Use Option.
     * <dl class="tree">
     *   <dt>PinsComponent::OPTION_TOKEN</dt>
     *     <dd>Authentication token (Requires scope: `pins:write`)</dd>
     *   <dt>PinsComponent::OPTION_CHANNEL</dt>
     *     <dd>Channel to pin the item in.</dd>
     *   <dt>PinsComponent::OPTION_FILE</dt>
     *     <dd>File to pin.</dd>
     *   <dt>PinsComponent::OPTION_FILE_COMMENT</dt>
     *     <dd>File comment to pin.</dd>
     *   <dt>PinsComponent::OPTION_TIMESTAMP</dt>
     *     <dd>Timestamp of the message to pin.</dd>
     * </dl>
     *
     * @param string $channel チャンネル名.
     * @param array $option オプション.
     * @return mixed レスポンスデータ.
     *
     * @see https://api.slack.com/methods/pins.add
     */
    public function add( $channel, array $option=[] )
    {
        $requestData = [
            self::OPTION_TOKEN => Slack\API_TOKEN,
            self::OPTION_CHANNEL => $channel,
            self::OPTION_FILE => null,
            self::OPTION_FILE_COMMENT => null,
            self::OPTION_FILE_TIMESTAMP => null,
        ];
        $requestData = array_merge( $requestData, $option );
        $response = self::postRequest( 'add', self::_nullFilter($requestData) );
        return $response;
    }

    /**
     * リストを取得する.
     *
     * This method lists the items pinned to a channel.
     *
     * ### Eg.
     * ```
     * $Pins->fetchList( 'C01234567' );
     * ```
     *
     * ### Response.
     * ```
     * {
     *     "ok": true,
     *     "items": [
     *         {
     *             "type": "message",
     *             "channel": "C01234567",
     *             "message": {
     *                 "user": "U01234567",
     *                 "members": [
     *                     "U01234567"
     *                 ],
     *                 "type": "message",
     *                 "subtype": "channel_join",
     *                 "text": "<@U01234567|user-name-sample> has joined the channel",
     *                 "ts": "1440000000.000001"
     *                 "permalink": "https://{your_team}.slack.com/archives/{channel_name}/p1440000000000001",
     *                 "pinned_to": [
     *                     "C01234567"
     *                 ]
     *             }
     *         }
     *     ]
     * }
     * ```
     *
     * ### Use Option.
     * <dl class="tree">
     *   <dt>PinsComponent::OPTION_TOKEN</dt>
     *     <dd>Authentication token (Requires scope: `pins:read`)</dd>
     *   <dt>PinsComponent::OPTION_CHANNEL</dt>
     *     <dd>Channel to get pinned items for.</dd>
     * </dl>
     *
     * @param string $channel チャンネル名.
     * @param array $option オプション.
     * @return mixed レスポンスデータ.
     *
     * @see https://api.slack.com/methods/pins.list
     */
    public function fetchList( $channel, array $option=[] )
    {
        $requestData = [
            self::OPTION_TOKEN => Slack\API_TOKEN,
            self::OPTION_CHANNEL => $channel,
        ];
        $requestData = array_merge( $requestData, $option );
        $response = self::getRequest( 'list', self::_nullFilter($requestData) );
        return $response;
    }

    /**
     * ピンを削除する.
     *
     * This method un-pins an item (file, file comment, channel message, or group message) from a channel.
     * The channel argument is required and one of file, file_comment, or timestamp must also be specified.
     *
     * ### Eg.
     * ```
     * $Pins->remove('C01234567', [
     *     PinsComponent::OPTION_FILE_TIMESTAMP => '1440000000.000001'
     * ]);
     * ```
     *
     * ### Response.
     * ```
     * {
     *     "ok": true
     * }
     * ```
     *
     * ### Use Option.
     * <dl class="tree">
     *   <dt>PinsComponent::OPTION_TOKEN</dt>
     *     <dd>Authentication token (Requires scope: `pins:write`)</dd>
     *   <dt>PinsComponent::OPTION_CHANNEL</dt>
     *     <dd>Channel where the item is pinned to.</dd>
     *   <dt>PinsComponent::OPTION_FILE</dt>
     *     <dd>File to un-pin.</dd>
     *   <dt>PinsComponent::OPTION_FILE_COMMENT</dt>
     *     <dd>File comment to un-pin.</dd>
     *   <dt>PinsComponent::OPTION_TIMESTAMP</dt>
     *     <dd>Timestamp of the message to un-pin.</dd>
     * </dl>
     *
     * @param string $channel チャンネル名.
     * @param array $option オプション.
     * @return mixed レスポンスデータ.
     *
     * @see https://api.slack.com/methods/pins.remove
     */
    public function remove( $channel, array $option=[] )
    {
        $requestData = [
            self::OPTION_TOKEN => Slack\API_TOKEN,
            self::OPTION_CHANNEL => $channel,
            self::OPTION_FILE => null,
            self::OPTION_FILE_COMMENT => null,
            self::OPTION_FILE_TIMESTAMP => null,
        ];
        $requestData = array_merge( $requestData, $option );
        $response = self::postRequest( 'remove', self::_nullFilter($requestData) );
        return $response;
    }
}
