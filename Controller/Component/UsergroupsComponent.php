<?php
/**
 * Slack API - usergroups method implementation.
 *
 * @author        Hiroki Yagyu.
 * @link          https://github.com/HirokiYagyu/Slack
 * @package       Slack.Controller.Component
 * @since         SlackPlugin v1.0.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses('BaseComponent', 'Slack.Controller/Component');

/**
 * Slack API Usergroups コンポーネント.
 *
 * Get info on your team's user groups.
 *
 * @package       Slack.Controller.Component
 */
class UsergroupsComponent extends BaseComponent
{
    /**
     * Slack API method name.
     * @var string
     * @see https://api.slack.com/methods
     */
    protected static $_method = 'usergroups';

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
     * A name for the user group. Must be unique among user groups.
     * @var string
     */
    const OPTION_NAME = 'name';

    /**
     * A mention handle. Must be unique among channels, users and user groups.
     * @var string
     */
    const OPTION_HANDLE = 'handle';

    /**
     * A short description of the user group.
     * @var string
     */
    const OPTION_DESCRIPTION = 'description';

    /**
     * A comma separated string of encoded channel IDs for which the user group uses as a default.
     * @var string
     */
    const OPTION_CHANNELS = 'channels';

    /**
     * Include disabled user groups.
     * @var string
     */
    const OPTION_INCLUDE_DISABLED = 'include_disable';

    /**
     * Include the number of users in the user group.
     * @var string
     */
    const OPTION_INCLUDE_COUNT = 'include_count';

    /**
     * Include the list of users for each user group.
     * @var string
     */
    const OPTION_INCLUDE_USERS = 'include_users';

    /**
     * ユーザグループを作成する.
     *
     * This method is used to create a user group.
     *
     * ### Eg.
     * ```
     * $Usergroups->create( 'Marketing Team' );
     * ```
     *
     * ### Response.
     * ```
     * {
     *     "ok": true,
     *     "usergroup": {
     *         "id": "S01234567",
     *         "team_id": "T01234567",
     *         "is_usergroup": true,
     *         "is_subteam": true,
     *         "name": "Marketing Team",
     *         "description": "",
     *         "handle": "",
     *         "is_external": false,
     *         "date_create": 1440000001,
     *         "date_update": 1440000001,
     *         "date_delete": 0,
     *         "auto_type": null,
     *         "created_by": "U01234567",
     *         "updated_by": "U01234567",
     *         "deleted_by": null,
     *         "prefs": {
     *             "channels": [],
     *             "groups": []
     *         },
     *         "user_count": "0"
     *     }
     * }
     * ```
     *
     * ### Use Option.
     * <dl class="tree">
     *   <dt>UsergroupsComponent::OPTION_TOKEN</dt>
     *     <dd>Authentication token (Requires scope: `usergroups:write`)</dd>
     *   <dt>UsergroupsComponent::OPTION_NAME</dt>
     *     <dd>A name for the user group. Must be unique among user groups.</dd>
     *   <dt>UsergroupsComponent::OPTION_HANDLE</dt>
     *     <dd>A mention handle. Must be unique among channels, users and user groups.</dd>
     *   <dt>UsergroupsComponent::OPTION_DESCRIPTION</dt>
     *     <dd>A short description of the user group.</dd>
     *   <dt>UsergroupsComponent::OPTION_CHANNELS</dt>
     *     <dd>A comma separated string of encoded channel IDs for which the user group uses as a default.</dd>
     *   <dt>UsergroupsComponent::OPTION_INCLUDE_COUNT</dt>
     *     <dd>Include the number of users in each user group.</dd>
     * </dl>
     *
     * @param string $user ユーザ ID.
     * @param array $option オプション.
     * @return mixed レスポンスデータ.
     *
     * @see https://api.slack.com/methods/usergroups.create
     */
    public function create( $name, array $option=[] )
    {
        $requestData = [
            self::OPTION_TOKEN => Slack\API_TOKEN,
            self::OPTION_NAME => $name,
            self::OPTION_HANDLE => null,
            self::OPTION_DESCRIPTION => null,
            self::OPTION_CHANNELS => null,
            self::OPTION_INCLUDE_COUNT => null,
        ];
        $requestData = array_merge( $requestData, $option );
        $response = self::postRequest( 'create', self::_nullFilter($requestData) );
        return $response;
    }


    /**
     * 既存のユーザグループを無効にする.
     *
     * This method disables an existing user group.
     *
     * ### Eg.
     * ```
     * $Usergroups->disable( 'S01234567' );
     * ```
     *
     * ### Response.
     * ```
     * {
     *     "ok": true,
     *     "usergroup": {
     *         "id": "S01234567",
     *         "team_id": "T01234567",
     *         "is_usergroup": true,
     *         "is_subteam": true,
     *         "name": "Marketing Team",
     *         "description": "",
     *         "handle": "marketing",
     *         "is_external": false,
     *         "date_create": 1440000000,
     *         "date_update": 1440000000,
     *         "date_delete": 1440000000,
     *         "auto_type": null,
     *         "created_by": "U01234567",
     *         "updated_by": "U01234567",
     *         "deleted_by": "U01234567",
     *         "prefs": {
     *             "channels": [],
     *             "groups": []
     *         },
     *         "user_count": "0"
     *     }
     * }
     * ```
     *
     * ### Use Option.
     * <dl class="tree">
     *   <dt>UsergroupsComponent::OPTION_TOKEN</dt>
     *     <dd>Authentication token (Requires scope: `usergroups:write`)</dd>
     *   <dt>UsergroupsComponent::OPTION_USERGROUP</dt>
     *     <dd>The encoded ID of the user group to disable.</dd>
     *   <dt>UsergroupsComponent::OPTION_INCLUDE_COUNT</dt>
     *     <dd>Include the number of users in the user group.</dd>
     * </dl>
     *
     * @param string $usergroup ユーザグループ ID.
     * @param array $option オプション.
     * @return mixed レスポンスデータ.
     *
     * @see https://api.slack.com/methods/usergroups.disable
     */
    public function disable( $usergroup, array $option=[] )
    {
        $requestData = [
            self::OPTION_TOKEN => Slack\API_TOKEN,
            self::OPTION_USERGROUP => $usergroup,
            self::OPTION_INCLUDE_COUNT => null,
        ];
        $requestData = array_merge( $requestData, $option );
        $response = self::postRequest( 'disable', self::_nullFilter($requestData) );
        return $response;
    }

    /**
     * 既存の無効になっているユーザグループを有効にする.
     *
     * This method enables a user group which was previously disabled.
     *
     * ### Eg.
     * ```
     * $Usergroups->enable( 'S01234567' );
     * ```
     *
     * ### Response.
     * ```
     * {
     *     "ok": true,
     *     "usergroup": {
     *         "id": "S01234567",
     *         "team_id": "T01234567",
     *         "is_usergroup": true,
     *         "is_subteam": true,
     *         "name": "Marketing Team",
     *         "description": "",
     *         "handle": "marketing",
     *         "is_external": false,
     *         "date_create": 1440000000,
     *         "date_update": 1440000000,
     *         "date_delete": 0,
     *         "auto_type": null,
     *         "created_by": "U01234567",
     *         "updated_by": "U01234567",
     *         "deleted_by": null,
     *         "prefs": {
     *             "channels": [],
     *             "groups": []
     *         },
     *         "user_count": "0"
     *     }
     * }
     * ```
     *
     * ### Use Option.
     * <dl class="tree">
     *   <dt>UsergroupsComponent::OPTION_TOKEN</dt>
     *     <dd>Authentication token (Requires scope: `usergroups:write`)</dd>
     *   <dt>UsergroupsComponent::OPTION_USERGROUP</dt>
     *     <dd>The encoded ID of the user group to enable.</dd>
     *   <dt>UsergroupsComponent::OPTION_INCLUDE_COUNT</dt>
     *     <dd>Include the number of users in the user group.</dd>
     * </dl>
     *
     * @param string $usergroup ユーザグループ ID.
     * @param array $option オプション.
     * @return mixed レスポンスデータ.
     *
     * @see https://api.slack.com/methods/usergroups.enable
     */
    public function enable( $usergroup, array $option=[] )
    {
        $requestData = [
            self::OPTION_TOKEN => Slack\API_TOKEN,
            self::OPTION_USERGROUP => $usergroup,
            self::OPTION_INCLUDE_COUNT => null,
        ];
        $requestData = array_merge( $requestData, $option );
        $response = self::postRequest( 'enable', self::_nullFilter($requestData) );
        return $response;
    }

    /**
     * ユーザグループのリストを取得する.
     *
     * This method returns a list of all user groups in the team.
     * This can optionally include disabled user groups.
     *
     * ### Eg.
     * ```
     * $Usergroups->fetchList();
     * ```
     *
     * ### Response.
     * ```
     * {
     *     "ok": true,
     *     "usergroups": [
     *         {
     *             "id": "S01234567",
     *             "team_id": "T01234567",
     *             "is_usergroup": true,
     *             "is_subteam": true,
     *             "name": "Marketing Team",
     *             "description": "",
     *             "handle": "marketing",
     *             "is_external": false,
     *             "date_create": 1440000000,
     *             "date_update": 1440000000,
     *             "date_delete": 0,
     *             "auto_type": null,
     *             "created_by": "U01234567",
     *             "updated_by": "U01234567",
     *             "deleted_by": null,
     *             "prefs": {
     *                 "channels": [],
     *                 "groups": []
     *             },
     *             "user_count": "0"
     *         }
     *     ]
     * }
     * ```
     *
     * ### Use Option.
     * <dl class="tree">
     *   <dt>UsergroupsComponent::OPTION_TOKEN</dt>
     *     <dd>Authentication token (Requires scope: `usergroups:read`)</dd>
     *   <dt>UsergroupsComponent::OPTION_INCLUDE_DISABLED</dt>
     *     <dd>Include disabled user groups.</dd>
     *   <dt>UsergroupsComponent::OPTION_INCLUDE_COUNT</dt>
     *     <dd>Include the number of users in each user group.</dd>
     *   <dt>UsergroupsComponent::OPTION_INCLUDE_USERS</dt>
     *     <dd>Include the list of users for each user group./dd>
     * </dl>
     *
     * @param array $option オプション.
     * @return mixed レスポンスデータ.
     *
     * @see https://api.slack.com/methods/usergroups.list
     */
    public function fetchList( array $option=[] )
    {
        $requestData = [
            self::OPTION_TOKEN => Slack\API_TOKEN,
            self::OPTION_INCLUDE_DISABLED => null,
            self::OPTION_INCLUDE_COUNT => null,
            self::OPTION_INCLUDE_USERS => null,
        ];
        $requestData = array_merge( $requestData, $option );
        $response = self::getRequest( 'list', self::_nullFilter($requestData) );
        return $response;
    }

    /**
     * ユーザグループを更新する.
     *
     * This method updates the properties of an existing user group.
     *
     * ### Eg.
     * ```
     * $Usergroups->update( 'S01234567', [
     *     UsergroupComponent::OPTION_CHANNELS => ['C01234567'],
     *     UsergroupComponent::OPTION_DESCRIPTION => 'test purpose!',
     *     UsergroupComponent::OPTION_HANDLE => 'marketing',
     * ] );
     * ```
     *
     * ### Response.
     * ```
     * {
     *     "ok": true,
     *     "usergroup": {
     *         "id": "S01234567",
     *         "team_id": "T01234567",
     *         "is_usergroup": true,
     *         "is_subteam": true,
     *         "name": "Marketing Team",
     *         "description": "test purpose!",
     *         "handle": "marketing",
     *         "is_external": false,
     *         "date_create": 1440000000,
     *         "date_update": 1440000000,
     *         "date_delete": 0,
     *         "auto_type": null,
     *         "created_by": "U01234567",
     *         "updated_by": "U01234567",
     *         "deleted_by": null,
     *         "prefs": {
     *             "channels": [
     *                 "C01234567"
     *             ],
     *             "groups": []
     *         },
     *         "user_count": "0"
     *     }
     * }
     * ```
     *
     * ### Use Option.
     * <dl class="tree">
     *   <dt>UsergroupsComponent::OPTION_TOKEN</dt>
     *     <dd>Authentication token (Requires scope: `usergroups:write`)</dd>
     *   <dt>UsergroupsComponent::OPTION_USERGROUP</dt>
     *     <dd>The encoded ID of the user group to update.</dd>
     *   <dt>UsergroupsComponent::OPTION_NAME</dt>
     *     <dd>A name for the user group. Must be unique among user groups.</dd>
     *   <dt>UsergroupsComponent::OPTION_HANDLE</dt>
     *     <dd>A mention handle. Must be unique among channels, users and user groups.</dd>
     *   <dt>UsergroupsComponent::OPTION_DESCRIPTION</dt>
     *     <dd>A short description of the user group.</dd>
     *   <dt>UsergroupsComponent::OPTION_CHANNELS</dt>
     *     <dd>A comma separated string of encoded channel IDs for which the user group uses as a default.</dd>
     *   <dt>UsergroupsComponent::OPTION_INCLUDE_COUNT</dt>
     *     <dd>Include the number of users in the user group.</dd>
     * </dl>
     *
     * @param string $usergroup ユーザグループ ID.
     * @param array $option オプション.
     * @return mixed レスポンスデータ.
     *
     * @see https://api.slack.com/methods/usergroups.update
     */
    public function update( $usergroup, array $option=[] )
    {
        $requestData = [
            self::OPTION_TOKEN => Slack\API_TOKEN,
            self::OPTION_USERGROUP => $usergroup,
            self::OPTION_NAME => null,
            self::OPTION_HANDLE => null,
            self::OPTION_DESCRIPTION => null,
            self::OPTION_CHANNELS => null,
            self::OPTION_INCLUDE_COUNT => null,
        ];
        $requestData = array_merge( $requestData, $option );
        $response = self::postRequest( 'update', self::_nullFilter($requestData) );
        return $response;
    }
}
