<?php
/**
 * Slack API - auth method implementation.
 *
 * @author        Hiroki Yagyu.
 * @link          https://github.com/HirokiYagyu/Slack
 * @package       Slack.Controller.Component
 * @since         SlackPlugin v1.0.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses('BaseComponent', 'Slack.Controller/Component');

/**
 * Slack API Auth コンポーネント.
 *
 * Checks authentication & identity.
 *
 * @package       Slack.Controller.Component
 */
class AuthComponent extends BaseComponent
{
    /**
     * Slack API method name.
     * @var string
     * @see https://api.slack.com/methods
     */
    protected static $_method = 'auth';

    /**
     * Authentication token.
     * @var string
     */
    const OPTION_TOKEN = 'token';

    /**
     * トークンをつかって認証テストを行う.
     *
     * This method checks authentication and tells you who you are.
     *
     * ### Eg.
     * ```
     * $Auth->auth([
     *     AuthComponent::OPTION_TOKEN => `'xxxx-xxxxxxxxx-xxxx'`,
     * ]);
     * ```
     *
     * ### Response.
     * ```
     * {
     *     "ok": true,
     *     "url": "https:\/\/myteam.slack.com\/",
     *     "team": "My Team",
     *     "user": "cal",
     *     "team_id": "T12345",
     *     "user_id": "U12345"
     * }
     * ```
     *
     * ### Use Option.
     * <dl class="tree">
     *   <dt>AuthComponent::OPTION_TOKEN</dt>
     *     <dd>Authentication token (Requires scope: `identify`)</dd>
     * </dl>
     *
     * @param array $option オプション.
     * @return mixed レスポンスデータ.
     *
     * @see https://api.slack.com/methods/auth.test
     */
    public function test( array $option=[] )
    {
        $requestData = [
            self::OPTION_TOKEN => Slack\API_TOKEN,
        ];
        $requestData = array_merge( $requestData, $option );
        $response = self::getRequest( 'test', self::_nullFilter($requestData) );
        return $response;
    }
}
