<?php
/**
 * Slack API - groups method implementation.
 *
 * @author        Hiroki Yagyu.
 * @link          https://github.com/HirokiYagyu/Slack
 * @package       Slack.Controller.Component
 * @since         SlackPlugin v1.0.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses('BaseComponent', 'Slack.Controller/Component');

/**
 * Slack API Groups コンポーネント.
 *
 * Get info on your team's private groups.
 *
 * @package       Slack.Controller.Component
 */
class GroupsComponent extends BaseComponent
{
    /**
     * Slack API method name.
     * @var string
     * @see https://api.slack.com/methods
     */
    protected static $_method = 'groups';

    /**
     * Authentication token.
     * @var string
     */
    const OPTION_TOKEN = 'token';

    /** Channel name. */
    const OPTION_CHANNEL = 'channel';

    /** Name of channel. */
    const OPTION_NAME = 'name';

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

    /** User to invite to channel. */
    const OPTION_USER = 'user';

    /** Don't return archived channels. */
    const OPTION_EXCLUDE_ARCHIVED = 'exclude_archived';

    /** Timestamp of the most recently seen message. */
    const OPTION_TS = 'ts';

    /** The new purpose. */
    const OPTION_PURPOSE = 'purpose';

    /** The new purpose. */
    const OPTION_TOPIC = 'topic';

    /**
     * グループをアーカイブする.
     *
     * This method archives a channel.
     *
     * ### Eg.
     * ``` {.prettyprint .lang-php}
     * $Groups->archive( 'G01234567' );
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
     * GroupsComponent::OPTION_TOKEN
     * :  Authentication token (Requires scope: `groups:write`)
     *
     * GroupsComponent::OPTION_CHANNEL
     * :  Private group to archive.
     *
     * @param string $channel グループ名.
     * @param array $option オプション.
     * @return mixed レスポンスデータ.
     *
     * @see https://api.slack.com/methods/groups.archive
     */
    public function archive( $channel, array $option=[] )
    {
        $requestData = [
            self::OPTION_TOKEN => Slack\API_TOKEN,
            self::OPTION_CHANNEL => $channel,
        ];
        $requestData = array_merge( $requestData, $option );
        $response = self::postRequest( 'archive', self::_nullFilter($requestData) );
        return $response;
    }

    /**
     * グループをクローズする.
     *
     * This method closes a private group.
     *
     * ### Eg.
     * ``` {.prettyprint .lang-php}
     * $Groups->close( 'G01234567' );
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
     * GroupsComponent::OPTION_TOKEN
     * :  Authentication token (Requires scope: `groups:write`)
     *
     * GroupsComponent::OPTION_CHANNEL
     * :  Group to close.
     *
     * @param string $channel グループ名.
     * @param array $option オプション.
     * @return mixed レスポンスデータ.
     *
     * @see https://api.slack.com/methods/groups.close
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
     * グループを作成する.
     *
     * This method creates a private group.
     *
     * ### Eg.
     * ``` {.prettyprint .lang-php}
     * $Groups->create( 'secretplans' );
     * ```
     *
     * ### Response.
     * ``` {.prettyprint .lang-js}
     * {
     *   "ok": true,
     *   "group": {
     *     "id": "G01234567",
     *     "name": "secretplans",
     *     "is_group": true,
     *     "created": 1440000000,
     *     "creator": "U01234567",
     *     "is_archived": false,
     *     "is_mpim": false,
     *     "is_open": true,
     *     "last_read": "0000000000.000000",
     *     "latest": null,
     *     "unread_count": 0,
     *     "unread_count_display": 0,
     *     "members": [
     *       "U01234567"
     *     ],
     *     "topic": {
     *       "value": "",
     *       "creator": "",
     *       "last_set": 0
     *     },
     *     "purpose": {
     *       "value": "",
     *       "creator": "",
     *       "last_set": 0
     *     }
     *   }
     * }
     * ```
     *
     * ### Use Option.
     * GroupsComponent::OPTION_TOKEN
     * :  Authentication token (Requires scope: `groups:write`)
     *
     * GroupsComponent::OPTION_CHANNEL
     * :  Group to close.
     *
     * @param string $name グループ名.
     * @param array $option オプション.
     * @return mixed レスポンスデータ.
     *
     * @see https://api.slack.com/methods/groups.create
     */
    public function create( $name, array $option=[] )
    {
        $requestData = [
            self::OPTION_TOKEN => Slack\API_TOKEN,
            self::OPTION_NAME => $name,
        ];
        $requestData = array_merge( $requestData, $option );
        $response = self::postRequest( 'create', self::_nullFilter($requestData) );
        return $response;
    }

    /**
     * 子グループを作成する.
     *
     * このメソッドは既存のプライベートグループから次の手順を実行します.
     * - 既存グループの名前変更を行います. (例: "example" から "exmaple-archived")
     * - 既存グループをアーカイブします.
     * - 既存グループの名前で新しいグループを作成します.
     * - 作成された新しいグループに、既存グループの参加メンバー全てを追加します.
     *
     * これは以前のチャット履歴を隠しながら既存グループに新しいメンバーを招待するときに便利です.
     * 筋書きとして `groups.createChild` に続いて `groups.invite` を呼び出します.
     *
     * 新しいグループはオリジナルのアーカイブ化グループを指すように特別な `parent_group` というプロパティを持ちます.
     * これは両グループのメンバーにのみ返されます、したがって新しく招待したメンバーには表示されません.
     *
     * This method takes an existing private group and performs the following steps:
     * - Renames the existing group (from "example" to "example-archived").
     * - Archives the existing group.
     * - Creates a new group with the name of the existing group.
     * - Adds all members of the existing group to the new group.
     *
     * This is useful when inviting a new member to an existing group while hiding all previous chat history from them.
     * In this scenario you can call `groups.createChild` followed by `groups.invite`.
     *
     * The new group will have a special `parent_group` property pointing to the original archived group.
     * This will only be returned for members of both groups, so will not be visible to any newly invited members.
     *
     * ### Eg.
     * ``` {.prettyprint .lang-php}
     * $Groups->createChild( 'G01234567' );
     * ```
     *
     * ### Response.
     * ``` {.prettyprint .lang-js}
     * {
     *   "ok": true,
     *   "group": {
     *     "id": "G01234568",
     *     "name": "secretplans",
     *     "is_group": true,
     *     "created": 1440000000,
     *     "creator": "U01234567",
     *     "is_archived": false,
     *     "is_mpim": false,
     *     "members": [
     *       "U01234567"
     *     ],
     *     "topic": {
     *       "value": "",
     *       "creator": "",
     *       "last_set": 0
     *     },
     *     "purpose": {
     *       "value": "",
     *       "creator": "",
     *       "last_set": 0
     *     }
     *   }
     * }
     * ```
     *
     * ### Use Option.
     * GroupsComponent::OPTION_TOKEN
     * :  Authentication token (Requires scope: `groups:write`)
     *
     * GroupsComponent::OPTION_CHANNEL
     * :  Group to clone and archive.
     *
     * @param string $channel グループ名.
     * @param array $option オプション.
     * @return mixed レスポンスデータ.
     *
     * @see https://api.slack.com/methods/groups.createChild
     */
    public function createChild( $channel, array $option=[] )
    {
        $requestData = [
            self::OPTION_TOKEN => Slack\API_TOKEN,
            self::OPTION_CHANNEL => $channel,
        ];
        $requestData = array_merge( $requestData, $option );
        $response = self::postRequest( 'createChild', self::_nullFilter($requestData) );
        return $response;
    }

    /**
     * メッセージ、イベントの履歴を取得する.
     *
     * This method returns a portion of messages/events from the specified private group.
     * To read the entire history for a group, call the method with no `latest` or `oldest` arguments, and then continue paging using the instructions below.
     *
     * ### Eg.
     * ``` {.prettyprint .lang-php}
     * $Groups->fetchHistory( 'G01234567' );
     * ```
     *
     * ### Response.
     * ``` {.prettyprint .lang-js}
     *
     * {
     *   "ok": true,
     *   "messages": [
     *     {
     *       "user": "U01234567",
     *       "type": "message",
     *       "subtype": "group_archive",
     *       "text": "<@U01234567|user-name-sample> archived the private channel",
     *       "ts": "1440000000.000001"
     *     },
     *   ],
     *   "has_more": false
     * }
     * ```
     *
     * ### Use Option.
     * GroupsComponent::OPTION_TOKEN
     * :  Authentication token (Requires scope: `groups:history`)
     *
     * GroupsComponent::OPTION_CHANNEL
     * :  Group to fetch history for.
     *
     * GroupsComponent::OPTION_LATEST
     * :  End of time range of messages to include in results.
     *
     * GroupsComponent::OPTION_OLDEST
     * :  Start of time range of messages to include in results.
     *
     * GroupsComponent::OPTION_INCLUSIVE
     * :  Include messages with latest or oldest timestamp in results.
     *
     * GroupsComponent::OPTION_COUNT
     * :  Number of messages to return, between 1 and 1000.
     *
     * GroupsComponent::OPTION_UNREADS
     * :  Include `unread_count_display` in the output?
     *
     * @param string $channel グループ名.
     * @param array $option オプション.
     * @return mixed レスポンスデータ.
     *
     * @see https://api.slack.com/methods/groups.history
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
     * グループ情報を取得する.
     *
     * ### Eg.
     * ``` {.prettyprint .lang-php}
     * $Groups->fetchInfo( 'G01234567' );
     * ```
     *
     * ### Response.
     * ``` {.prettyprint .lang-js}
     * {
     *   "ok": true,
     *   "group": {
     *     "id": "G01234567",
     *     "name": "secretplans-archived",
     *     "is_group": true,
     *     "created": 1440000000,
     *     "creator": "U01234567",
     *     "is_archived": true,
     *     "is_mpim": false,
     *     "is_open": true,
     *     "last_read": "1440000000.000001",
     *     "latest": {
     *       "user": "U01234567",
     *       "type": "message",
     *       "subtype": "group_archive",
     *       "text": "<@U01234567|user-name-sample> archived the private channel",
     *       "ts": "1440000000.000001"
     *     },
     *     "unread_count": 0,
     *     "unread_count_display": 0,
     *     "members": [
     *       "U01234567"
     *     ],
     *     "topic": {
     *       "value": "",
     *       "creator": "",
     *       "last_set": 0
     *     },
     *     "purpose": {
     *       "value": "",
     *       "creator": "",
     *       "last_set": 0
     *     }
     *   }
     * }
     * ```
     *
     * ### Use Option.
     * GroupsComponent::OPTION_TOKEN
     * :  Authentication token (Requires scope: `groups:read`)
     *
     * GroupsComponent::OPTION_CHANNEL
     * :  Group to get info on.
     *
     * @param string $channel グループ名.
     * @param array $option オプション.
     * @return mixed レスポンスデータ.
     *
     * @see https://api.slack.com/methods/groups.info
     */
    public function fetchInfo( $channel, array $option=[] )
    {
        $requestData = [
            self::OPTION_TOKEN => Slack\API_TOKEN,
            self::OPTION_CHANNEL => $channel,
        ];
        $requestData = array_merge( $requestData, $option );
        $response = self::getRequest( 'info', self::_nullFilter($requestData) );
        return $response;
    }

    /**
     * ユーザを招待する.
     *
     * This method is used to invite a user to a private group.
     * The calling user must be a member of the group.
     *
     * To invite a new member to a group without giving them access to the archives of the group call the `groups.createChild` method before inviting.
     *
     * ### Eg.
     * ``` {.prettyprint .lang-php}
     * $Groups->invite( 'G01234567', 'U01234568' );
     * ```
     *
     * ### Response.
     * ``` {.prettyprint .lang-js}
     * {
     *   "ok": true,
     *   "group": {
     *     "id": "G01234567",
     *     "name": "secretplans-archived",
     *     "is_group": true,
     *     "created": 1440000000,
     *     "creator": "U01234567",
     *     "is_archived": false,
     *     "is_mpim": false,
     *     "is_open": true,
     *     "last_read": "1440000000.000001",
     *     "latest": {
     *       "user": "U01234567",
     *       "type": "message",
     *       "subtype": "group_unarchive",
     *       "text": "<@U01234567|user-name-sample> un-archived the private channel",
     *       "ts": "1440000000.000001"
     *     },
     *     "unread_count": 0,
     *     "unread_count_display": 0,
     *     "members": [
     *       "U01234567",
     *       "U01234568"
     *     ],
     *     "topic": {
     *       "value": "",
     *       "creator": "",
     *       "last_set": 0
     *     },
     *     "purpose": {
     *       "value": "",
     *       "creator": "",
     *       "last_set": 0
     *     }
     *   }
     * }
     * ```
     *
     * ### Use Option.
     * GroupsComponent::OPTION_TOKEN
     * :  Authentication token (Requires scope: `groups:write`)
     *
     * GroupsComponent::OPTION_CHANNEL
     * :  Private group to invite user to.
     *
     * GroupsComponent::OPTION_USER
     * :  User to invite.
     *
     * @param string $channel グループ名.
     * @param string $user ユーザ名.
     * @param array $option オプション.
     * @return mixed レスポンスデータ.
     *
     * @see https://api.slack.com/methods/groups.invite
     */
    public function invite( $channel, $user, array $option=[] )
    {
        $requestData = [
            self::OPTION_TOKEN => Slack\API_TOKEN,
            self::OPTION_CHANNEL => $channel,
            self::OPTION_USER => $user,
        ];
        $requestData = array_merge( $requestData, $option );
        $response = self::postRequest( 'invite', self::_nullFilter($requestData) );
        return $response;
    }

    /**
     * ユーザをキックする.
     *
     * This method allows a user to remove another member from a team channel.
     *
     * ### Eg.
     * ``` {.prettyprint .lang-php}
     * $Channels->kick( 'C1234567890', 'U12345678' );
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
     * GroupsComponent::OPTION_TOKEN
     * :  Authentication token (Requires scope: `groups:write`)
     *
     * GroupsComponent::OPTION_CHANNEL
     * :  Group to remove user from.
     *
     * GroupsComponent::OPTION_USER
     * :  User to remove from group.
     *
     * @param string $channel グループ名.
     * @param string $user ユーザ名.
     * @param array $option オプション.
     * @return mixed レスポンスデータ.
     *
     * @see https://api.slack.com/methods/groups.kick
     */
    public function kick( $channel, $user, array $option=[] )
    {
        $requestData = [
            self::OPTION_TOKEN => Slack\API_TOKEN,
            self::OPTION_CHANNEL => $channel,
            self::OPTION_USER => $user,
        ];
        $requestData = array_merge( $requestData, $option );
        $response = self::postRequest( 'kick', self::_nullFilter($requestData) );
        return $response;
    }

    /**
     * グループから退席する.
     *
     * This method is used to leave a private group.
     *
     * ### Eg.
     * ``` {.prettyprint .lang-php}
     * $Groups->leave( 'G01234567' );
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
     * GroupsComponent::OPTION_TOKEN
     * :  Authentication token (Requires scope: `groups:write`)
     *
     * GroupsComponent::OPTION_CHANNEL
     * :  Group to leave.
     *
     * @param string $channel グループ名.
     * @param array $option オプション.
     * @return mixed レスポンスデータ.
     *
     * @see https://api.slack.com/methods/groups.leave
     */
    public function leave( $channel, array $option=[] )
    {
        $requestData = [
            self::OPTION_TOKEN => Slack\API_TOKEN,
            self::OPTION_CHANNEL => $channel,
        ];
        $requestData = array_merge( $requestData, $option );
        $response = self::postRequest( 'leave', self::_nullFilter($requestData) );
        return $response;
    }

    /**
     * チャンネルリストを取得する.
     *
     * This method returns a list of groups in the team that the caller is in and archived groups that the caller was in.
     * The list of (non-deactivated) members in each group is also returned.
     *
     * ### Eg.
     * ``` {.prettyprint .lang-php}
     * $Groups->fetchList();
     * ```
     *
     * ### Response.
     * ``` {.prettyprint .lang-js}
     * {
     *   "ok": true,
     *   "groups": [
     *     {
     *       "id": "G01234567",
     *       "name": "secretplans",
     *       "is_group": true,
     *       "created": 1440000000,
     *       "creator": "U01234567",
     *       "is_archived": false,
     *       "is_mpim": false,
     *       "members": [
     *         "U01234567",
     *         "U01234568",
     *       ],
     *       "topic": {
     *         "value": "",
     *         "creator": "",
     *         "last_set": 0
     *       },
     *       "purpose": {
     *         "value": "",
     *         "creator": "",
     *         "last_set": 0
     *       }
     *     }
     *   ]
     * }
     * ```
     *
     * ### Use Option.
     * GroupsComponent::OPTION_TOKEN
     * :  Authentication token (Requires scope: `groups:read`)
     *
     * GroupsComponent::OPTION_EXCLUDE_ARCHIVED
     * :  Don't return archived groups.
     *
     * @param array $option オプション.
     * @return mixed レスポンスデータ.
     *
     * @see https://api.slack.com/methods/groups.leave
     */
    public function fetchList( array $option=[] )
    {
        $requestData = [
            self::OPTION_TOKEN => Slack\API_TOKEN,
            self::OPTION_EXCLUDE_ARCHIVED => null,
        ];
        $requestData = array_merge( $requestData, $option );
        $response = self::getRequest( 'list', self::_nullFilter($requestData) );
        return $response;
    }

    /**
     * 該当チャンネルの read cursor 位置を移動する. ("ここまで読んだ線" の変更. これ以降のメッセージは NEW MESSAGES 扱いされる.)
     *
     * This method moves the read cursor in a private group.
     *
     * ### Eg.
     * ``` {.prettyprint .lang-php}
     * $Groups->mark( 'G01234567', '1440000000.000001' );
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
     * GroupsComponent::OPTION_TOKEN
     * :  Authentication token (Requires scope: `groups:write`)
     *
     * GroupsComponent::OPTION_CHANNEL
     * :  Group to set reading cursor in.
     *
     * GroupsComponent::OPTION_TS
     * :  Timestamp of the most recently seen message.
     *
     * @param string $channel グループ名.
     * @param string $timestamp read cursor を移動したいメッセージの投稿日時(UNIX TIME).
     * @param array $option オプション.
     * @return mixed レスポンスデータ.
     *
     * @see https://api.slack.com/methods/groups.mark
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
     * グループをオープンする.
     *
     * This method opens a private group.
     *
     * ### Eg.
     * ``` {.prettyprint .lang-php}
     * $Groups->open( 'G01234567' );
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
     * GroupsComponent::OPTION_TOKEN
     * :  Authentication token (Requires scope: `groups:write`)
     *
     * GroupsComponent::OPTION_CHANNEL
     * :  Group to open.
     *
     * @param string $channel グループ名.
     * @param array $option オプション.
     * @return mixed レスポンスデータ.
     *
     * @see https://api.slack.com/methods/groups.open
     */
    public function open( $channel, array $option=[] )
    {
        $requestData = [
            self::OPTION_TOKEN => Slack\API_TOKEN,
            self::OPTION_CHANNEL => $channel,
        ];
        $requestData = array_merge( $requestData, $option );
        $response = self::postRequest( 'open', self::_nullFilter($requestData) );
        return $response;
    }

    /**
     * グループ名を変更する.
     *
     * This method opens a private group.
     *
     * ### Eg.
     * ``` {.prettyprint .lang-php}
     * $Groups->rename( 'G01234567', 'rename-private-group' );
     * ```
     *
     * ### Response.
     * ``` {.prettyprint .lang-js}
     * {
     *   "ok": true,
     *   "channel": {
     *     "id": "G01234567",
     *     "name": "rename-private-group",
     *     "is_group": true,
     *     "created": 1440000000
     *   }
     * }
     * ```
     *
     * ### Use Option.
     * GroupsComponent::OPTION_TOKEN
     * :  Authentication token (Requires scope: `groups:write`)
     *
     * GroupsComponent::OPTION_CHANNEL
     * :  Group to rename.
     *
     * GroupsComponent::OPTION_NAME
     * :  New name for group.
     *
     * @param string $channel グループ名.
     * @param string $rename 変更したいグループ名.
     * @param array $option オプション.
     * @return mixed レスポンスデータ.
     *
     * @see https://api.slack.com/methods/groups.rename
     */
    public function rename( $channel, $rename, array $option=[] )
    {
        $requestData = [
            self::OPTION_TOKEN => Slack\API_TOKEN,
            self::OPTION_CHANNEL => $channel,
            self::OPTION_NAME => $rename,
        ];
        $requestData = array_merge( $requestData, $option );
        $response = self::postRequest( 'rename', self::_nullFilter($requestData) );
        return $response;
    }

    /**
     * チャンネルの目的を設定する.
     *
     * This method opens a private group.
     *
     * ### Eg.
     * ``` {.prettyprint .lang-php}
     * $Groups->setPurpose( 'G01234567', 'rename-private-group' );
     * ```
     *
     * ### Response.
     * ``` {.prettyprint .lang-js}
     * {
     *   "ok": true,
     *   "purpose": "This is the new purpose!"
     * }
     * ```
     *
     * ### Use Option.
     * GroupsComponent::OPTION_TOKEN
     * :  Authentication token (Requires scope: `groups:write`)
     *
     * GroupsComponent::OPTION_CHANNEL
     * :  Private group to set the purpose of.
     *
     * GroupsComponent::OPTION_PURPOSE
     * :  The new purpose.
     *
     * @param string $channel グループ名.
     * @param string $purpose 目的.
     * @param array $option オプション.
     * @return mixed レスポンスデータ.
     *
     * @see https://api.slack.com/methods/groups.rename
     */
    public function setPurpose( $channel, $purpose, array $option=[] )
    {
        $requestData = [
            self::OPTION_TOKEN => Slack\API_TOKEN,
            self::OPTION_CHANNEL => $channel,
            self::OPTION_PURPOSE => $purpose,
        ];
        $requestData = array_merge( $requestData, $option );
        $response = self::postRequest( 'setPurpose', self::_nullFilter($requestData) );
        return $response;
    }

    /**
     * チャンネルのトピックを設定する.
     *
     * This method is used to change the topic of a private group.
     * The calling user must be a member of the private group.
     *
     * ### Eg.
     * ``` {.prettyprint .lang-php}
     * $Groups->setPurpose( 'G01234567', 'rename-private-group' );
     * ```
     *
     * ### Response.
     * ``` {.prettyprint .lang-js}
     * {
     *   "ok": true,
     *   "topic": "This is the new topic!"
     * }
     * ```
     *
     * ### Use Option.
     * GroupsComponent::OPTION_TOKEN
     * :  Authentication token (Requires scope: `groups:write`)
     *
     * GroupsComponent::OPTION_CHANNEL
     * :  Private group to set the topic of.
     *
     * GroupsComponent::OPTION_TOPIC
     * :  The new topic.
     *
     * @param string $channel グループ名.
     * @param string $topic トピック.
     * @param array $option オプション.
     * @return mixed レスポンスデータ.
     *
     * @see https://api.slack.com/methods/groups.rename
     */
    public function setTopic( $channel, $topic, array $option=[] )
    {
        $requestData = [
            self::OPTION_TOKEN => Slack\API_TOKEN,
            self::OPTION_CHANNEL => $channel,
            self::OPTION_TOPIC => $topic,
        ];
        $requestData = array_merge( $requestData, $option );
        $response = self::postRequest( 'setTopic', self::_nullFilter($requestData) );
        return $response;
    }

    /**
     * アーカイブされたグループを元に戻す.
     *
     * This method unarchives a private group.
     *
     * ### Eg.
     * ``` {.prettyprint .lang-php}
     * $Groups->unarchive( 'G01234567' );
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
     * GroupsComponent::OPTION_TOKEN
     * :  Authentication token (Requires scope: `groups:write`)
     *
     * GroupsComponent::OPTION_CHANNEL
     * :  Group to unarchive
     *
     * @param string $channel グループ名.
     * @param array $option オプション.
     * @return mixed レスポンスデータ.
     *
     * @see https://api.slack.com/methods/groups.archive
     */
    public function unarchive( $channel, array $option=[] )
    {
        $requestData = [
            self::OPTION_TOKEN => Slack\API_TOKEN,
            self::OPTION_CHANNEL => $channel,
        ];
        $requestData = array_merge( $requestData, $option );
        $response = self::postRequest( 'unarchive', self::_nullFilter($requestData) );
        return $response;
    }
}
