<?php
/**
 * Slack API - abstract API class.
 *
 * @author        Hiroki Yagyu.
 * @link          https://github.com/HirokiYagyu/Slack
 * @package       Slack.Controller.Component
 * @since         SlackPlugin v1.0.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses('Component', 'Controller');
App::uses('HttpSocket', 'Network/Http');

/**
 * Slack API 用の抽象クラス.
 *
 * @package       Slack.Controller.Component
 */
abstract class BaseComponent extends Component
{
    protected static $_endPointUrl = 'https://slack.com/api';
    protected static $_method = '';
    protected static $_methodDelimiter = '.';
    protected static $_userAgent = 'APIPuncher-v1.0.0;';

    /**
     * Return json prettify.
     * @var string
     */
    const OPTION_PRETTY = 'pretty';

    /**
     * GET リクエスト.
     * 30x の際にリダイレクト先を追う回数は 3 回まで.
     *
     * @param string $action エンドポイントに続くアクション名.
     * @param array $requestData リクエストデータ.
     * @return mixed レスポンスデータ.
     */
    public static function getRequest( $action, array $parameters )
    {
        $HttpSocket = new HttpSocket();
        return static::_afterRequest( $HttpSocket->get(static::_endPoint().$action, $parameters, ['redirect'=>3,'header'=>['User-Agent'=>static::$_userAgent]]), $HttpSocket );
    }

    /**
     * POST リクエスト.
     *
     * @param string $action エンドポイントに続くアクション名.
     * @param array $requestData リクエストデータ.
     * @return mixed レスポンスデータ.
     */
    public static function postRequest( $action, array $requestData )
    {
        $HttpSocket = new HttpSocket();
        return static::_afterRequest( $HttpSocket->post(static::_endPoint().$action, $requestData, ['header'=>['User-Agent'=>static::$_userAgent]]), $HttpSocket );
    }

    /**
     * PUT リクエスト.
     *
     * @param string $action エンドポイントに続くアクション名.
     * @param array $requestData リクエストデータ.
     * @return mixed レスポンスデータ.
     */
    public static function putRequest( $action, array $requestData )
    {
        $HttpSocket = new HttpSocket();
        return self::_afterRequest( $HttpSocket->put(self::_endPoint().$action, $requestData, ['header'=>['User-Agent'=>static::$_userAgent]]), $HttpSocket );
    }

    /**
     * DELETE リクエスト.
     *
     * @param string $action エンドポイントに続くアクション名.
     * @param array $requestData リクエストデータ.
     * @return mixed レスポンスデータ.
     */
    public static function deleteRequest( $action, array $requestData )
    {
        $HttpSocket = new HttpSocket();
        return self::_afterRequest( $HttpSocket->delete(self::_endPoint().$action, $requestData, ['header'=>['User-Agent'=>static::$_userAgent]]), $HttpSocket );
    }

    /**
     * API のエンドポイントを返す.
     * @return string エンドポイント URL.
     */
    protected static function _endPoint()
    {
        return static::$_endPointUrl . '/' . static::$_method . static::$_methodDelimiter;
    }

    /**
     * リクエストコールバック.
     * データの加工などを行うためのメソッド.
     *
     * @param HttpSocketResponse $response 無加工のレスポンス.
     * @return mixed|string レスポンスの body 部.
     */
    protected static function _afterRequest( HttpSocketResponse $response, HttpSocket $request=null )
    {
        return $response->body;
    }

    /**
    * 連想配列の値が null の要素を省いた連想配列を返す.
    *
    * @param array $parameters 連想配列.
    * @return array 値に null を持つ要素を取り除いた連想配列.
    *
    * @see http://php.net/manual/ja/function.array-filter.php
    */
    protected static function _nullFilter( array $parameters )
    {
        return array_filter( $parameters, function($v){return $v!==null;} );
    }
}
