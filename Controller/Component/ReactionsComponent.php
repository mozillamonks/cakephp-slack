<?php
/**
 * Slack API - reactions method implementation.
 *
 * @author        Hiroki Yagyu.
 * @link          https://github.com/HirokiYagyu/Slack
 * @package       Slack.Controller.Component
 * @since         SlackPlugin v1.0.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses('BaseComponent', 'Slack.Controller/Component');

/**
 * Slack API Reactions コンポーネント.
 *
 * @package       Slack.Controller.Component
 */
class ReactionsComponent extends BaseComponent
{
    /**
     * Slack API method name.
     * @var string
     * @see https://api.slack.com/methods
     */
    protected static $_method = 'reactions';

    /**
     * Authentication token.
     * @var string
     */
    const OPTION_TOKEN = 'token';

    /**
     * Reaction (emoji) name.
     * @var string
     */
    const OPTION_NAME = 'name';

    /**
     * File to add reaction to.
     * @var string
     */
    const OPTION_FILE = 'file';

    /**
     * File comment to add reaction to.
     * @var string
     */
    const OPTION_FILE_COMMENT = 'file_comment';

    /**
     * Channel where the message to add reaction to was posted.
     * @var string
     */
    const OPTION_CHANNEL = 'channel';

    /**
     * Timestamp of the message to add reaction to.
     * @var string
     */
    const OPTION_TIMESTAMP = 'timestamp';

    /**
     * If true always return the complete reaction list.
     * @var string
     */
    const OPTION_FULL = 'full';

    /**
     * Show reactions made by this user. Defaults to the authed user.
     * @var string
     */
    const OPTION_USER = 'user';

    /**
     * Number of items to return per page.
     * @var string
     */
    const OPTION_COUNT = 'count';

    /**
     * Page number of results to return.
     * @var string
     */
    const OPTION_PAGE = 'page';

    /**
     * リアクションを追加する.
     *
     * This method adds a reaction (emoji) to an item (file, file comment, channel message, group message, or direct message).
     * One of file, file_comment, or the combination of channel and timestamp must be specified.
     *
     * ### Eg.
     * ``` {.prettyprint .lang-php}
     * $Reactions->add('full_moon_with_face',[
     *     ReactionsComponent::OPTION_CHANNEL => 'C01234567',
     *     ReactionsComponent::OPTION_TIMESTAMP => '1440000000.000001'
     * ]);
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
     * ReactionsComponent::OPTION_TOKEN
     * :  Authentication token (Requires scope: `reactions:write`)
     * ReactionsComponent::OPTION_NAME
     * :  Reaction (emoji) name.
     * ReactionsComponent::OPTION_FILE
     * :  File to add reaction to.
     * ReactionsComponent::OPTION_FILE_COMMENT
     * :  File comment to add reaction to.
     * ReactionsComponent::OPTION_CHANNEL
     * :  Channel where the message to add reaction to was posted.
     * ReactionsComponent::OPTION_TIMESTAMP
     * :  Timestamp of the message to add reaction to.
     *
     * @param string $name 絵文字名.
     * @param array $option オプション.
     * @return mixed レスポンスデータ.
     *
     * @see https://api.slack.com/methods/reactions.add
     */
    public function add( $name, array $option=[] )
    {
        $requestData = [
            self::OPTION_TOKEN => Slack\API_TOKEN,
            self::OPTION_NAME => $name,
            self::OPTION_FILE => null,
            self::OPTION_FILE_COMMENT => null,
            self::OPTION_CHANNEL => null,
            self::OPTION_TIMESTAMP => null,
        ];
        $requestData = array_merge( $requestData, $option );
        $response = self::postRequest( 'add', self::_nullFilter($requestData) );
        return $response;
    }

    /**
     * リアクションを取得する.
     *
     * This method returns a list of all reactions for a single item (file, file comment, channel message, group message, or direct message).
     *
     * ### Eg.
     * ``` {.prettyprint .lang-php}
     * $Reactions->add('full_moon_with_face',[
     *     ReactionsComponent::OPTION_CHANNEL => 'C01234567',
     *     ReactionsComponent::OPTION_TIMESTAMP => '1440000000.000001'
     * ]);
     * ```
     *
     * ### Response.
     * ``` {.prettyprint .lang-js}
     * {
     *   "ok": true,
     *   "type": "message",
     *   "channel": "C01234567",
     *   "message": {
     *     "user": "U01234567",
     *     "members": [
     *       "U01234567"
     *     ],
     *     "type": "message",
     *     "subtype": "channel_archive",
     *     "text": "<@U01234567|crepuscular> archived the channel (w/ 1 member)",
     *     "ts": "1440000000.000001",
     *     "permalink": "https://{your_team}.slack.com/archives/{channel_name}/p1440000000000001",
     *     "reactions": [
     *       {
     *         "name": "full_moon_with_face",
     *         "users": [
     *           "U01234567"
     *         ],
     *         "count": 1
     *       }
     *     ]
     *   }
     * }
     * ```
     *
     * ### Use Option.
     * ReactionsComponent::OPTION_TOKEN
     * :  Authentication token (Requires scope: `reactions:read`)
     * ReactionsComponent::OPTION_FILE
     * :  File to get reactions for.
     * ReactionsComponent::OPTION_FILE_COMMENT
     * :  File comment to get reactions for.
     * ReactionsComponent::OPTION_CHANNEL
     * :  Channel where the message to get reactions for was posted.
     * ReactionsComponent::OPTION_TIMESTAMP
     * :  Timestamp of the message to get reactions for.
     * ReactionsComponent::OPTION_FULL
     * :  If true always return the complete reaction list.
     *
     * @param array $option オプション.
     * @return mixed レスポンスデータ.
     *
     * @see https://api.slack.com/methods/reactions.get
     */
    public function fetch( array $option=[] )
    {
        $requestData = [
            self::OPTION_TOKEN => Slack\API_TOKEN,
            self::OPTION_FILE => null,
            self::OPTION_FILE_COMMENT => null,
            self::OPTION_CHANNEL => null,
            self::OPTION_TIMESTAMP => null,
            self::OPTION_FULL => null,
        ];
        $requestData = array_merge( $requestData, $option );
        $response = self::getRequest( 'get', self::_nullFilter($requestData) );
        return $response;
    }

    /**
     * リアクションのリストを取得する.
     *
     * This method returns a list of all items (file, file comment, channel message, group message, or direct message) reacted to by a user.
     *
     * ### Eg.
     * ``` {.prettyprint .lang-php}
     * $Reactions->fetchList();
     * ```
     *
     * ### Response.
     * ``` {.prettyprint .lang-js}
     * {
     *   "ok": true,
     *   "items": [
     *     {
     *       "type": "message",
     *       "channel": "C01234567",
     *       "message": {
     *         "user": "U01234567",
     *         "members": [
     *           "U01234567"
     *         ],
     *         "type": "message",
     *         "subtype": "channel_archive",
     *         "text": "<@U01234567|crepuscular> archived the channel (w/ 1 member)",
     *         "ts": "1440000000.000001",
     *         "permalink": "https://{your_team}.slack.com/archives/{channel_name}/p1440000000000001",
     *         "reactions": [
     *           {
     *             "name": "full_moon_with_face",
     *             "users": [
     *               "U01234567"
     *             ],
     *             "count": 1
     *           }
     *         ]
     *       }
     *     }
     *   ],
     *   "paging": {
     *     "count": 100,
     *     "total": 1,
     *     "page": 1,
     *     "pages": 1
     *   }
     * }
     *
     * @param array $option オプション.
     * @return mixed レスポンスデータ.
     *
     * @see https://api.slack.com/methods/reactions.list
     */
    public function fetchList( array $option=[] )
    {
        $requestData = [
            self::OPTION_TOKEN => Slack\API_TOKEN,
            self::OPTION_USER => null,
            self::OPTION_FULL => null,
            self::OPTION_COUNT => null,
            self::OPTION_PAGE => null,
        ];
        $requestData = array_merge( $requestData, $option );
        $response = self::getRequest( 'list', self::_nullFilter($requestData) );
        return $response;
    }

    /**
     * リアクションを削除.
     *
     * This method removes a reaction (emoji) from an item (file, file comment, channel message, group message, or direct message).
     * One of `file`, `file_comment`, or the combination of `channel` and `timestamp` must be specified.
     *
     * ### Eg.
     * ``` {.prettyprint .lang-php}
     * $Reactions->remove('full_moon_with_face',[
     *     ReactionsComponent::OPTION_CHANNEL => 'C01234567',
     *     ReactionsComponent::OPTION_TIMESTAMP => '1440000000.000001'
     * ]);
     * ```
     *
     * ### Response.
     * ``` {.prettyprint .lang-js}
     * {
     *   "ok": true
     * }
     *
     * @param string $name 絵文字名.
     * @param array $option オプション.
     * @return mixed レスポンスデータ.
     *
     * @see https://api.slack.com/methods/reactions.remove
     */
    public function remove( $name, array $option=[] )
    {
        $requestData = [
            self::OPTION_TOKEN => Slack\API_TOKEN,
            self::OPTION_NAME => $name,
            self::OPTION_FILE => null,
            self::OPTION_FILE_COMMENT => null,
            self::OPTION_CHANNEL => null,
            self::OPTION_TIMESTAMP => null,
        ];
        $requestData = array_merge( $requestData, $option );
        $response = self::postRequest( 'remove', self::_nullFilter($requestData) );
        return $response;
    }
}
