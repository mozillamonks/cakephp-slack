<?php
/**
 * Slack API - api method implementation.
 *
 * @author        Hiroki Yagyu.
 * @link          https://github.com/HirokiYagyu/Slack
 * @package       Slack.Controller.Component
 * @since         SlackPlugin v1.0.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses('BaseComponent', 'Slack.Controller/Component');

/**
 * Slack API Api コンポーネント.
 *
 * Checks API calling code.
 *
 * @package       Slack.Controller.Component
 */
class ApiComponent extends BaseComponent
{
    /**
     * Slack API method name.
     * @var string
     * @see https://api.slack.com/methods
     */
    protected static $_method = 'api';

    /**
     * Error response to return.
     * @var string
     */
    const OPTION_ERROR = 'error';

    /**
     * API をテストする.
     *
     * This method helps you test your calling code.
     *
     * ### Eg.
     * ```
     * $Api->test([
     *     ApiComponent::OPTION_ERROR => 'my_error',
     *     'foo' => 'bar',
     * ]);
     * ```
     *
     * ### Response.
     * ```
     * {
     *     "ok": false,
     *     "error": "my_error",
     *     "args": {
     *         "error": "my_error",
     *         "foo": "bar"
     *     }
     * }
     * ```
     *
     * ### Use Option.
     * <dl class="tree">
     *    <dt>ApiComponent::OPTION_ERROR</dt>
     *    <dd>Error response to return.</dd>
     * </dl>
     *
     * @param array $option オプション.
     * @return mixed レスポンスデータ.
     *
     * @see https://api.slack.com/methods/api.test
     */
    public function test( array $option=[] )
    {
        $requestData = [
            self::OPTION_ERROR => null, // sample.
            'bar' => null, // sample.
        ];
        $requestData = array_merge( $requestData, $option );
        $response = self::getRequest( 'test', self::_nullFilter($requestData) );
        return $response;
    }
}
