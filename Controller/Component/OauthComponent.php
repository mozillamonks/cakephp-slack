<?php
/**
 * Slack API - oauth method implementation.
 *
 * @author        Hiroki Yagyu.
 * @link          https://github.com/HirokiYagyu/Slack
 * @package       Slack.Controller.Component
 * @since         SlackPlugin v1.0.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses('BaseComponent', 'Slack.Controller/Component');

/**
 * Slack API OAuth コンポーネント.
 *
 * Exchanges a temporary OAuth code for an API token.
 *
 * @package       Slack.Controller.Component
 */
class OAuthComponent extends BaseComponent
{
    /**
     * Slack API method name.
     * @var string
     * @see https://api.slack.com/methods
     */
    protected static $_method = 'oauth';

    /**
     * Issued when you created your application.
     * @var string
     */
    const OPTION_CLIENT_ID = 'client_id';

    /**
     * Issued when you created your application.
     * @var string
     */
    const OPTION_CLIENT_SECRET = 'client_secret';

    /**
     * The <code>code</code> param returned via the OAuth callback.
     * @var string
     */
    const OPTION_CODE = 'code';

    /**
     * This must match the originally submitted URI (if one was sent).
     * @var string
     */
    const OPTION_REDIRECT_URI = 'redirect_uri';

    /**
     * OAuth 認証で API に接続する.
     *
     * This method allows you to exchange a temporary OAuth `code` for an API access token.
     * This is used as part of the OAuth authentication flow.
     *
     * As discussed in RFC 6749 it is possible to supply the Client ID and Client Secret using the HTTP Basic authentication scheme.
     * If HTTP Basic authentication is used you do not need to supply the `client_id` and `client_secret` parameters as part of the request.
     *
     * @see https://api.slack.com/docs/oauth
     * @see https://tools.ietf.org/html/rfc6749#section-2.3.1
     *
     * ### Eg.
     * ``` {.prettyprint .lang-php}
     * $Oauth->access( '00000000000.00000000000', 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx', '00000000000.00000000000.ffffffffff' );
     * ```
     *
     * ### Response.
     * ``` {.prettyprint .lang-js}
     * {
     *   "access_token": "xoxt-01234567890-1234567890123",
     *   "scope": "read"
     * }
     * ```
     *
     * ### Use Option.
     * OAuthComponent::OPTION_CLIENT_ID
     * :  Issued when you created your application.
     * OAuthComponent::OPTION_CLIENT_SECRET
     * :  Issued when you created your application.
     * OAuthComponent::OPTION_CODE
     * :  The `code` param returned via the OAuth callback.
     * OAuthComponent::OPTION_REDIRECT_URI
     * :  This must match the originally submitted URI (if one was sent).
     *
     * @param string $client_id クライアント ID.
     * @param string $client_secret クライアントシークレット.
     * @param string $code OAuth callback にて得たコード.
     * @param array $option オプション.
     * @return mixed レスポンスデータ.
     *
     * @see https://api.slack.com/methods/oauth.access
     */
    public function access( $client_id, $client_secret, $code, array $option=[] )
    {
        $requestData = [
            self::OPTION_CLIENT_ID => $client_id,
            self::OPTION_CLIENT_SECRET => $client_secret,
            self::OPTION_CODE => $code,
            self::OPTION_REDIRECT_URI => null,
        ];
        $requestData = array_merge( $requestData, $option );
        $response = self::postRequest( 'access', self::_nullFilter($requestData) );
        return $response;
    }
}
