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
     * ``` {.prettyprint .lang-php}
     * $Usergroups->create( 'Marketing Team' );
     * ```
     *
     * ### Response.
     * ``` {.prettyprint .lang-js}
     * {
     *   "ok": true,
     *   "usergroup": {
     *     "id": "S01234567",
     *     "team_id": "T01234567",
     *     "is_usergroup": true,
     *     "is_subteam": true,
     *     "name": "Marketing Team",
     *     "description": "",
     *     "handle": "",
     *     "is_external": false,
     *     "date_create": 1440000001,
     *     "date_update": 1440000001,
     *     "date_delete": 0,
     *     "auto_type": null,
     *     "created_by": "U01234567",
     *     "updated_by": "U01234567",
     *     "deleted_by": null,
     *     "prefs": {
     *       "channels": [],
     *       "groups": []
     *     },
     *     "user_count": "0"
     *   }
     * }
     * ```
     *
     * ### Use Option.
     * UsergroupsComponent::OPTION_TOKEN
     * :  Authentication token (Requires scope: `usergroups:write`)
     * UsergroupsComponent::OPTION_NAME
     * :  A name for the user group. Must be unique among user groups.
     * UsergroupsComponent::OPTION_HANDLE
     * :  A mention handle. Must be unique among channels, users and user groups.
     * UsergroupsComponent::OPTION_DESCRIPTION
     * :  A short description of the user group.
     * UsergroupsComponent::OPTION_CHANNELS
     * :  A comma separated string of encoded channel IDs for which the user group uses as a default.
     * UsergroupsComponent::OPTION_INCLUDE_COUNT
     * :  Include the number of users in each user group.
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
     * ``` {.prettyprint .lang-php}
     * $Usergroups->disable( 'S01234567' );
     * ```
     *
     * ### Response.
     * ``` {.prettyprint .lang-js}
     * {
     *   "ok": true,
     *   "usergroup": {
     *     "id": "S01234567",
     *     "team_id": "T01234567",
     *     "is_usergroup": true,
     *     "is_subteam": true,
     *     "name": "Marketing Team",
     *     "description": "",
     *     "handle": "marketing",
     *     "is_external": false,
     *     "date_create": 1440000000,
     *     "date_update": 1440000000,
     *     "date_delete": 1440000000,
     *     "auto_type": null,
     *     "created_by": "U01234567",
     *     "updated_by": "U01234567",
     *     "deleted_by": "U01234567",
     *     "prefs": {
     *       "channels": [],
     *       "groups": []
     *     },
     *     "user_count": "0"
     *   }
     * }
     * ```
     *
     * ### Use Option.
     * UsergroupsComponent::OPTION_TOKEN
     * :  Authentication token (Requires scope: `usergroups:write`)
     * UsergroupsComponent::OPTION_USERGROUP
     * :  The encoded ID of the user group to disable.
     * UsergroupsComponent::OPTION_INCLUDE_COUNT
     * :  Include the number of users in the user group.
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
     * ``` {.prettyprint .lang-php}
     * $Usergroups->enable( 'S01234567' );
     * ```
     *
     * ### Response.
     * ``` {.prettyprint .lang-js}
     * {
     *   "ok": true,
     *   "usergroup": {
     *     "id": "S01234567",
     *     "team_id": "T01234567",
     *     "is_usergroup": true,
     *     "is_subteam": true,
     *     "name": "Marketing Team",
     *     "description": "",
     *     "handle": "marketing",
     *     "is_external": false,
     *     "date_create": 1440000000,
     *     "date_update": 1440000000,
     *     "date_delete": 0,
     *     "auto_type": null,
     *     "created_by": "U01234567",
     *     "updated_by": "U01234567",
     *     "deleted_by": null,
     *     "prefs": {
     *       "channels": [],
     *       "groups": []
     *     },
     *     "user_count": "0"
     *   }
     * }
     * ```
     *
     * ### Use Option.
     * UsergroupsComponent::OPTION_TOKEN
     * :  Authentication token (Requires scope: `usergroups:write`)
     * UsergroupsComponent::OPTION_USERGROUP
     * :  The encoded ID of the user group to enable.
     * UsergroupsComponent::OPTION_INCLUDE_COUNT
     * :  Include the number of users in the user group.
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
     * ``` {.prettyprint .lang-php}
     * $Usergroups->fetchList();
     * ```
     *
     * ### Response.
     * ``` {.prettyprint .lang-js}
     * {
     *   "ok": true,
     *   "usergroups": [
     *     {
     *       "id": "S01234567",
     *       "team_id": "T01234567",
     *       "is_usergroup": true,
     *       "is_subteam": true,
     *       "name": "Marketing Team",
     *       "description": "",
     *       "handle": "marketing",
     *       "is_external": false,
     *       "date_create": 1440000000,
     *       "date_update": 1440000000,
     *       "date_delete": 0,
     *       "auto_type": null,
     *       "created_by": "U01234567",
     *       "updated_by": "U01234567",
     *       "deleted_by": null,
     *       "prefs": {
     *         "channels": [],
     *         "groups": []
     *       },
     *       "user_count": "0"
     *     }
     *   ]
     * }
     * ```
     *
     * ### Use Option.
     * UsergroupsComponent::OPTION_TOKEN
     * :  Authentication token (Requires scope: `usergroups:read`)
     * UsergroupsComponent::OPTION_INCLUDE_DISABLED
     * :  Include disabled user groups.
     * UsergroupsComponent::OPTION_INCLUDE_COUNT
     * :  Include the number of users in each user group.
     * UsergroupsComponent::OPTION_INCLUDE_USERS
     * :  Include the list of users for each user group./dd>
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
     * ``` {.prettyprint .lang-php}
     * $Usergroups->update( 'S01234567', [
     *     UsergroupComponent::OPTION_CHANNELS => ['C01234567'],
     *     UsergroupComponent::OPTION_DESCRIPTION => 'test purpose!',
     *     UsergroupComponent::OPTION_HANDLE => 'marketing',
     * ] );
     * ```
     *
     * ### Response.
     * ``` {.prettyprint .lang-js}
     * {
     *   "ok": true,
     *   "usergroup": {
     *     "id": "S01234567",
     *     "team_id": "T01234567",
     *     "is_usergroup": true,
     *     "is_subteam": true,
     *     "name": "Marketing Team",
     *     "description": "test purpose!",
     *     "handle": "marketing",
     *     "is_external": false,
     *     "date_create": 1440000000,
     *     "date_update": 1440000000,
     *     "date_delete": 0,
     *     "auto_type": null,
     *     "created_by": "U01234567",
     *     "updated_by": "U01234567",
     *     "deleted_by": null,
     *     "prefs": {
     *       "channels": [
     *         "C01234567"
     *       ],
     *       "groups": []
     *     },
     *     "user_count": "0"
     *   }
     * }
     * ```
     *
     * ### Use Option.
     * UsergroupsComponent::OPTION_TOKEN
     *     <dd>Authentication token (Requires scope: `usergroups:write`)
     * UsergroupsComponent::OPTION_USERGROUP
     * :  The encoded ID of the user group to update.
     * UsergroupsComponent::OPTION_NAME
     * :  A name for the user group. Must be unique among user groups.
     * UsergroupsComponent::OPTION_HANDLE
     * :  A mention handle. Must be unique among channels, users and user groups.
     * UsergroupsComponent::OPTION_DESCRIPTION
     * :  A short description of the user group.
     * UsergroupsComponent::OPTION_CHANNELS
     * :  A comma separated string of encoded channel IDs for which the user group uses as a default.
     * UsergroupsComponent::OPTION_INCLUDE_COUNT
     * :  Include the number of users in the user group.
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
