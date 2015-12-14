<?php
/**
 * Slack API - star method implementation.
 *
 * @author        Hiroki Yagyu.
 * @link          https://github.com/HirokiYagyu/Slack
 * @package       Slack.Controller.Component
 * @since         SlackPlugin v1.0.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses('BaseComponent', 'Slack.Controller/Component');

/**
 * Slack API Star コンポーネント.
 *
 * @package       Slack.Controller.Component
 */
class StarsComponent extends BaseComponent
{
    protected static $_method = 'stars';

    /** Authentication token (Requires scope: <code>admin</code>) */
    const OPTION_TOKEN = 'token';

    /** File to add star to. */
    const OPTION_FILE = 'file';

    /** File comment to add star to. */
    const OPTION_FILE_COMMENT = 'file_comment';

    /** Channel to add star to, or channel where the message to add star to was posted (used with <code>timestamp</code>). */
    const OPTION_CHANNEL = 'channel';

    /** Timestamp of the message to add star to. */
    const OPTION_TIMESTAMP = 'timestamp';

    /** Show stars by this user. Defaults to the authed user. */
    const OPTION_USER = 'user';

    /** Number of items to return per page. */
    const OPTION_COUNT = 'count';

    /** Timestamp of the message to add star to. */
    const OPTION_PAGE = 'page';

    /**
     * スターを追加する.
     *
     * @param string $channel チャンネル名.
     * @param array $option オプション.
     * @return mixed レスポンスデータ.
     *
     * @see https://api.slack.com/methods/stars.add
     */
    public function add( $channel, array $option=[] )
    {
        $requestData = [
            self::OPTION_TOKEN => Slack\API_TOKEN,
            self::OPTION_CHANNEL => $channel,
            self::OPTION_FILE=>null,
            self::OPTION_FILE_COMMENT=>null,
            self::OPTION_TIMESTAMP=>null,
        ];
        $requestData = array_merge( $requestData, $option );
        $response = self::postRequest( 'add', self::_nullFilter($requestData) );
        return $response;
    }

    /**
     * リストを取得する.
     *
     * @param array $option オプション.
     * @return mixed レスポンスデータ.
     *
     * @see https://api.slack.com/methods/stars.list
     */
    public function fetchList( array $option=[] )
    {
        $requestData = [
            self::OPTION_TOKEN => Slack\API_TOKEN,
            self::OPTION_USER => null,
            self::OPTION_COUNT => null,
            self::OPTION_PAGE => null,
        ];
        $requestData = array_merge( $requestData, $option );
        $response = self::getRequest( 'list', self::_nullFilter($requestData) );
        return $response;
    }

    /**
     * スターを削除する.
     *
     * @param string $channel チャンネル名.
     * @param array $option オプション.
     * @return mixed レスポンスデータ.
     *
     * @see https://api.slack.com/methods/stars.remove
     */
    public function remove( $channel, array $option=[] )
    {
        $requestData = [
            self::OPTION_TOKEN => Slack\API_TOKEN,
            self::OPTION_CHANNEL => $channel,
            self::OPTION_FILE=>null,
            self::OPTION_FILE_COMMENT=>null,
            self::OPTION_TIMESTAMP=>null,
        ];
        $requestData = array_merge( $requestData, $option );
        $response = self::postRequest( 'remove', self::_nullFilter($requestData) );
        return $response;
    }
}
