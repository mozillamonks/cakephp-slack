<?php
/**
 * Slack API - im method implementation.
 *
 * @author        Hiroki Yagyu.
 * @link          https://github.com/HirokiYagyu/Slack
 * @package       Slack.Controller.Component
 * @since         SlackPlugin v1.0.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses('BaseComponent', 'Slack.Controller/Component');

/**
 * Slack API Im コンポーネント.
 *
 * Get info on your direct messages.
 *
 * @package       Slack.Controller.Component
 */
class ImComponent extends BaseComponent
{
    /**
     * Slack API method name.
     * @var string
     * @see https://api.slack.com/methods
     */
    protected static $_method = 'im';

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

    /** User to open a direct message channel with. */
    const OPTION_USER = 'user';

    /** Timestamp of the most recently seen message. */
    const OPTION_TS = 'ts';

    /**
     * Direct Message Channel をクローズする.
     *
     * This method closes a direct message channel.
     *
     * ### Eg.
     * ``` {.prettyprint .lang-php}
     * $Im->close( 'D01234567' );
     * ```
     *
     * ### Response.
     * ``` {.prettyprint .lang-js}
     * {
     *   "ok": true
     * }
     * ```
     *
     * ### Use Option.
     * ImComponent::OPTION_TOKEN
     * :  Authentication token (Requires scope: `im:write`)
     *
     * ImComponent::OPTION_CHANNEL
     * :  Direct message channel to close.
     *
     * @param string $channel グループ名.
     * @param array $option オプション.
     * @return mixed レスポンスデータ.
     *
     * @see https://api.slack.com/methods/im.close
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
     * This method returns a portion of messages/events from the specified direct message channel.
     * To read the entire history for a direct message channel, call the method with no `latest` or `oldest` arguments, and then continue paging using the instructions below.
     *
     * ### Eg.
     * ``` {.prettyprint .lang-php}
     * $Im->fetchHistory( 'D01234567' );
     * ```
     *
     * ### Response.
     * ``` {.prettyprint .lang-js}
     * {
     *   "ok": true,
     *   "messages": [
     *     {
     *       "type": "message",
     *       "user": "U01234567",
     *       "text": "Hello!",
     *       "ts": "1440000000.000001"
     *     }
     *   ],
     *   "has_more": false
     * }
     * ```
     *
     * ### Use Option.
     * ImComponent::OPTION_TOKEN
     * :  Authentication token (Requires scope: `im:history`)
     *
     * ImComponent::OPTION_CHANNEL
     * :  Direct message channel to fetch history for.
     *
     * ImComponent::OPTION_LATEST
     * :  End of time range of messages to include in results.
     *
     * ImComponent::OPTION_OLDEST
     * :  Start of time range of messages to include in results.
     *
     * ImComponent::OPTION_INCLUSIVE
     * :  Include messages with latest or oldest timestamp in results.
     *
     * ImComponent::OPTION_COUNT
     * :  Number of messages to return, between 1 and 1000.
     *
     * ImComponent::OPTION_UNREADS
     * :  Include `unread_count_display` in the output?
     *
     * @param string $channel グループ名.
     * @param array $option オプション.
     * @return mixed レスポンスデータ.
     *
     * @see https://api.slack.com/methods/im.history
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
     * This method returns a list of all im channels that the user has.
     *
     * ### Eg.
     * ``` {.prettyprint .lang-php}
     * $Im->fetchList();
     * ```
     *
     * ### Response.
     * ``` {.prettyprint .lang-js}
     * {
     *   "ok": true,
     *   "ims": [
     *     {
     *       "id": "D01234567",
     *       "is_im": true,
     *       "user": "U01234567",
     *       "created": 1440000000,
     *       "is_user_deleted": false
     *     }
     *   ]
     * }
     * ```
     *
     * ### Use Option.
     * ImComponent::OPTION_TOKEN
     * :  Authentication token (Requires scope: `im:read`)
     *
     * @param array $option オプション.
     * @return mixed レスポンスデータ.
     *
     * @see https://api.slack.com/methods/im.list
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
     * This method moves the read cursor in a direct message channel.
     *
     * ### Eg.
     * ``` {.prettyprint .lang-php}
     * $Im->mark( 'D01234567', '1440000000.000001' );
     * ```
     *
     * ### Response.
     * ``` {.prettyprint .lang-js}
     * {
     *   "ok": true
     * }
     * ```
     *
     * ### Use Option.
     * ImComponent::OPTION_TOKEN
     * :  Authentication token (Requires scope: `im:write`)
     *
     * ImComponent::OPTION_CHANNEL
     * :  Direct message channel to set reading cursor in.
     *
     * ImComponent::OPTION_TS
     * :  Timestamp of the most recently seen message.
     *
     * @param string $channel グループ名.
     * @param string $timestamp read cursor を移動したいメッセージの投稿日時(UNIX TIME).
     * @param array $option オプション.
     * @return mixed レスポンスデータ.
     *
     * @see https://api.slack.com/methods/im.mark
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
     * This method opens a direct message channel with another member of your Slack team.
     *
     * ### Eg.
     * ``` {.prettyprint .lang-php}
     * $Im->open( 'D01234568' );
     * ```
     *
     * ### Response.
     * ``` {.prettyprint .lang-js}
     * {
     *   "ok": true,
     *   "no_op": true,
     *   "already_open": true,
     *   "channel": {
     *     "id": "D01234568"
     *   }
     * }
     * ```
     *
     * ### Use Option.
     * ImComponent::OPTION_TOKEN
     * :  Authentication token (Requires scope: `im:write`)
     *
     * ImComponent::OPTION_USER
     * :  User to open a direct message channel with.
     *
     * @param string $user ユーザ ID.
     * @param array $option オプション.
     * @return mixed レスポンスデータ.
     *
     * @see https://api.slack.com/methods/im.open
     */
    public function open( $user, array $option=[] )
    {
        $requestData = [
            self::OPTION_TOKEN => Slack\API_TOKEN,
            self::OPTION_USER => $user,
        ];
        $requestData = array_merge( $requestData, $option );
        $response = self::postRequest( 'open', self::_nullFilter($requestData) );
        return $response;
    }
}
