<?php
/**
 * Slack API - usergroups.users method implementation.
 *
 * @author        Hiroki Yagyu.
 * @link          https://github.com/HirokiYagyu/Slack
 * @package       Slack.Controller.Component
 * @since         SlackPlugin v1.0.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses('BaseComponent', 'Slack.Controller/Component');

/**
 * Slack API UsergroupsUsers コンポーネント.
 *
 * Checks API calling code.
 *
 * @package       Slack.Controller.Component
 */
class UsergroupsUsersComponent extends BaseComponent
{
    /**
     * Slack API method name.
     * @var string
     * @see https://api.slack.com/methods
     */
    protected static $_method = 'usergroups.users';

    /**
     * Authentication token.
     * @var string
     */
    const OPTION_TOKEN = 'token';

    /**
     * The encoded ID of the user group to update.
     * @var string
     */
    const OPTION_USERGROUP = 'usergroup';

    /**
     * Allow results that involve disabled user groups.
     * @var string
     */
    const OPTION_INCLUDE_DISABLED = 'include_disabled';

    /**
     * ユーザーグループ内のユーザを返す.
     *
     * This method returns a list of all users within a user group.
     *
     * ### Eg.
     * ```
     * $UsergroupsUsers->list( 'S01234567' );
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
     *    <dt>UsergroupsUsersComponent::OPTION_TOKEN</dt>
     *      <dd>Authentication token (Requires scope: `usergroups:read`)</dd>
     *    <dt>UsergroupsUsersComponent::OPTION_USERGROUP</dt>
     *      <dd>The encoded ID of the user group to update.</dd>
     *    <dt>UsergroupsUsersComponent::OPTION_INCLUDE_DISABLED</dt>
     *      <dd>Allow results that involve disabled user groups.</dd>
     * </dl>
     *
     * @param string $usergroup ユーザーグループ ID.
     * @param array $option オプション.
     * @return mixed レスポンスデータ.
     *
     * @see https://api.slack.com/methods/usergroups.user.list
     */
    public function fetchList( $usergroup, array $option=[] )
    {
        $requestData = [
            self::OPTION_TOKEN => Slack\API_TOKEN,
            self::OPTION_USERGROUP => $usergroup,
            self::OPTION_INCLUDE_DISABLED => null,
        ];
        $requestData = array_merge( $requestData, $option );
        $response = self::getRequest( 'list', self::_nullFilter($requestData) );
        return $response;
    }

    /**
     * ユーザーグループ内のユーザを更新する.
     *
     * This method updates the list of users that belong to a user group.
     * This method replaces all users in a user group with the list of users provided in the users parameter.
     *
     * ### Eg.
     * ```
     * $UsergroupsUsers->list( 'S01234567',['U01234567'] );
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
     *    <dt>UsergroupsUsersComponent::OPTION_TOKEN</dt>
     *      <dd>Authentication token (Requires scope: `usergroups:write`)</dd>
     *    <dt>UsergroupsUsersComponent::OPTION_USERGROUP</dt>
     *      <dd>The encoded ID of the user group to update.</dd>
     *    <dt>UsergroupsUsersComponent::OPTION_USERS</dt>
     *      <dd>A comma separated string of encoded user IDs that represent the entire list of users for the user group.</dd>
     *    <dt>UsergroupsUsersComponent::OPTION_INCLUDE_COUNT</dt>
     *      <dd>Include the number of users in the user group.</dd>
     * </dl>
     *
     * @param string $usergroup ユーザーグループ ID.
     * @param array $option オプション.
     * @return mixed レスポンスデータ.
     *
     * @see https://api.slack.com/methods/usergroups.user.update
     */
    public function update( $usergroup, array $option=[] )
    {
        $requestData = [
            self::OPTION_TOKEN => Slack\API_TOKEN,
            self::OPTION_USERGROUP => $usergroup,
            self::OPTION_USERS => null,
            self::OPTION_INCLUDE_COUNT => null,
        ];
        $requestData = array_merge( $requestData, $option );
        $response = self::postRequest( 'update', self::_nullFilter($requestData) );
        return $response;
    }
}
