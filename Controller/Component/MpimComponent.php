<?php
/**
 * Slack API - mpim method implementation.
 *
 * @author        Hiroki Yagyu.
 * @link          https://github.com/HirokiYagyu/Slack
 * @package       Slack.Controller.Component
 * @since         SlackPlugin v1.0.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses('BaseComponent', 'Slack.Controller/Component');

/**
 * Slack API Mpim コンポーネント.
 *
 * Get info on your multiparty direct messages.
 *
 * @package       Slack.Controller.Component
 */
class MpimComponent extends BaseComponent
{
    /**
     * Slack API method name.
     * @var string
     * @see https://api.slack.com/methods
     */
    protected static $_method = 'mpim';

    /**
     * Authentication token.
     * @var string
     */
    const OPTION_TOKEN = 'token';

    /** CDirect message channel to fetch history for. */
    const OPTION_CHANNEL = 'channel';

    /** End of time range of messages to include in results. */
    const OPTION_LATEST = 'latest';

    /** Start of time range of messages to include in results. */
    const OPTION_OLDEST = 'oldest';

    /** Include messages with latest or oldest timestamp in results. */
    const OPTION_INCLUSIVE = 'inclusive';

    /** Number of messages to return, between 1 and 1000. */
    const OPTION_COUNT = 'count';

    /** Include <code>unread_count_display</code> in the output? */
    const OPTION_UNREADS = 'unreads';

    /** Comma separated lists of users. The ordering of the users is preserved whenever a MPIM group is returned. */
    const OPTION_USERS = 'users';

    /** Timestamp of the most recently seen message. */
    const OPTION_TS = 'ts';

    /**
     * Direct Message Channel をクローズする.
     *
     * This method closes a multiparty direct message channel.
     *
     * ### Eg.
     * ```
     * $Mpim->close( 'G01234567' );
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
     *   <dt>MpimComponent::OPTION_TOKEN</dt>
     *     <dd>Authentication token (Requires scope: `mpim:write`)</dd>
     *   <dt>MpimComponent::OPTION_CHANNEL</dt>
     *     <dd>MPIM to close.</dd>
     * </dl>
     *
     * @param string $channel グループ名.
     * @param array $option オプション.
     * @return mixed レスポンスデータ.
     *
     * @see https://api.slack.com/methods/mpim.close
     */
    public function close( $channel, array $option=[] )
    {
        $requestData = [
            self::OPTION_TOKEN => Slack\API_TOKEN,
            self::OPTION_CHANNEL => $channel,
        ];
        $requestData = array_merge( $requestData, $option );
        $response = self::postRequest( 'close', self::_nullFilter($requestData) );
        return $response;
    }

    /**
     * メッセージ、イベントの履歴を取得する.
     *
     * This method returns a portion of messages/events from the specified multiparty direct message channel.
     * To read the entire history for a multiparty direct message, call the method with no `latest` or `oldest` arguments, and then continue paging using the instructions below.
     *
     * ### Eg.
     * ```
     * $Mpim->fetchHistory( 'G01234567' );
     * ```
     *
     * ### Response.
     * ```
     * {
     *     "ok": true,
     *     "messages": [
     *         {
     *             "type": "message",
     *             "user": "U01234567",
     *             "text": "Hello!",
     *             "ts": "1440000000.000001"
     *         }
     *     ],
     *     "has_more": false
     * }
     * ```
     *
     * ### Use Option.
     * <dl class="tree">
     *   <dt>MpimComponent::OPTION_TOKEN</dt>
     *     <dd>Authentication token (Requires scope: `mpim:history`)</dd>
     *   <dt>MpimComponent::OPTION_CHANNEL</dt>
     *     <dd>Multiparty direct message to fetch history for.</dd>
     *   <dt>MpimComponent::OPTION_LATEST</dt>
     *     <dd>End of time range of messages to include in results.</dd>
     *   <dt>MpimComponent::OPTION_OLDEST</dt>
     *     <dd>Start of time range of messages to include in results.</dd>
     *   <dt>MpimComponent::OPTION_INCLUSIVE</dt>
     *     <dd>Include messages with latest or oldest timestamp in results.</dd>
     *   <dt>MpimComponent::OPTION_COUNT</dt>
     *     <dd>Number of messages to return, between 1 and 1000.</dd>
     *   <dt>MpimComponent::OPTION_UNREADS</dt>
     *     <dd>Include `unread_count_display` in the output?</dd>
     * </dl>
     *
     * @param string $channel グループ名.
     * @param array $option オプション.
     * @return mixed レスポンスデータ.
     *
     * @see https://api.slack.com/methods/mpim.history
     */
    public function fetchHistory( $channel, array $option=[] )
    {
        $requestData = [
            self::OPTION_TOKEN => Slack\API_TOKEN,
            self::OPTION_CHANNEL => $channel,
            self::OPTION_LATEST => null,
            self::OPTION_OLDEST => null,
            self::OPTION_INCLUSIVE => null,
            self::OPTION_COUNT => null,
            self::OPTION_UNREADS => null,
        ];
        $requestData = array_merge( $requestData, $option );
        $response = self::getRequest( 'history', self::_nullFilter($requestData) );
        return $response;
    }

    /**
     * チャンネルリストを取得する.
     *
     * This method returns a list of all multiparty direct message channels that the user has.
     *
     * ### Eg.
     * ```
     * $Mpim->fetchList();
     * ```
     *
     * ### Response.
     * ```
     * {
     *     "ok": true,
     *     "groups": [
     *         {
     *             "id": "G01234567",
     *             "name": "mpdm-user--user_a--user_b--user_c-1",
     *             "is_group": true,
     *             "created": 1440000000,
     *             "creator": "U01234567",
     *             "is_archived": false,
     *             "is_mpim": true,
     *             "members": [
     *                 "U01234567",
     *                 "U01234568",
     *                 "U01234569",
     *                 "U01234560"
     *             ],
     *             "topic": {
     *                 "value": "Group messaging",
     *                 "creator": "U01234567",
     *                 "last_set": 1440000000
     *             },
     *             "purpose": {
     *                 "value": "Group messaging with: @user @user_a @user_b @user_c",
     *                 "creator": "U01234567",
     *                 "last_set": 1440000000
     *             }
     *         }
     *     ]
     * }
     * ```
     *
     * ### Use Option.
     * <dl class="tree">
     *   <dt>MpimComponent::OPTION_TOKEN</dt>
     *     <dd>Authentication token (Requires scope: `mpim:read`)</dd>
     * </dl>
     *
     * @param array $option オプション.
     * @return mixed レスポンスデータ.
     *
     * @see https://api.slack.com/methods/mpim.list
     */
    public function fetchList( array $option=[] )
    {
        $requestData = [
            self::OPTION_TOKEN => Slack\API_TOKEN,
        ];
        $requestData = array_merge( $requestData, $option );
        $response = self::getRequest( 'list', self::_nullFilter($requestData) );
        return $response;
    }

    /**
     * 該当チャンネルの read cursor 位置を移動する. ("ここまで読んだ線" の変更. これ以降のメッセージは NEW MESSAGES 扱いされる.)
     *
     * This method moves the read cursor in a multiparty direct message channel.
     *
     * ### Eg.
     * ```
     * $Mpim->mark( 'G01234567', '1440000000.000001' );
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
     *   <dt>MpimComponent::OPTION_TOKEN</dt>
     *     <dd>Authentication token (Requires scope: `mpim:write`)</dd>
     *   <dt>MpimComponent::OPTION_CHANNEL</dt>
     *     <dd>Multiparty direct message channel to set reading cursor in.</dd>
     *   <dt>MpimComponent::OPTION_TS</dt>
     *     <dd>Timestamp of the most recently seen message.</dd>
     * </dl>
     *
     * @param string $channel グループ名.
     * @param string $timestamp read cursor を移動したいメッセージの投稿日時(UNIX TIME).
     * @param array $option オプション.
     * @return mixed レスポンスデータ.
     *
     * @see https://api.slack.com/methods/mpim.mark
     */
    public function mark( $channel, $timestamp, array $option=[] )
    {
        $requestData = [
            self::OPTION_TOKEN => Slack\API_TOKEN,
            self::OPTION_CHANNEL => $channel,
            self::OPTION_TS => $timestamp,
        ];
        $requestData = array_merge( $requestData, $option );
        $response = self::postRequest( 'mark', self::_nullFilter($requestData) );
        return $response;
    }

    /**
     * Direct Message Channel をオープンする.
     *
     * This method opens a multiparty direct message.
     * Opening a multiparty direct message takes a list of up-to 8 encoded user ids.
     * If there is no MPIM already created that includes that exact set of members, a new MPIM will be created.
     * Subsequent calls to `mpim.open` with the same set of users will return the already existing MPIM conversation.
     *
     * ### Eg.
     * ```
     * $Mpim->open( [U01234568,U01234569,U01234560] );
     * ```
     *
     * ### Response.
     * ```
     * {
     *     "ok": true,
     *     "group": {
     *         "id": "G01234567",
     *         "name": "mpdm-user--user_a--user_b--user_c-1",
     *         "is_group": true,
     *         "created": 1440000000,
     *         "creator": "U01234567",
     *         "is_archived": false,
     *         "is_mpim": true,
     *         "is_open": false,
     *         "last_read": "0000000000.000000",
     *         "latest": null,
     *         "unread_count": 0,
     *         "unread_count_display": 0,
     *         "members": [
     *             "U01234567",
     *             "U01234568",
     *             "U01234569",
     *             "U01234560"
     *         ],
     *         "topic": {
     *             "value": "Group messaging",
     *             "creator": "U01234567",
     *             "last_set": 1440000000
     *         },
     *         "purpose": {
     *             "value": "Group messaging with: @user @user_a @user_b @user_c",
     *             "creator": "U01234567",
     *             "last_set": 1440000000
     *         }
     *     }
     * }
     * ```
     *
     * ### Use Option.
     * <dl class="tree">
     *   <dt>MpimComponent::OPTION_TOKEN</dt>
     *     <dd>Authentication token (Requires scope: `mpim:write`)</dd>
     *   <dt>MpimComponent::OPTION_USERS</dt>
     *     <dd>Comma separated lists of users. The ordering of the users is preserved whenever a MPIM group is returned.</dd>
     * </dl>
     *
     * @param array $users ユーザ ID の配列.
     * @param array $option オプション.
     * @return mixed レスポンスデータ.
     *
     * @see https://api.slack.com/methods/mpim.open
     */
    public function open( $users, array $option=[] )
    {
        $requestData = [
            self::OPTION_TOKEN => Slack\API_TOKEN,
            self::OPTION_USERS => implode(',',$users),
        ];
        $requestData = array_merge( $requestData, $option );
        $response = self::postRequest( 'open', self::_nullFilter($requestData) );
        return $response;
    }
}
