<?php
/**
 * Slack API - channels method implementation.
 *
 * @author        Hiroki Yagyu.
 * @link          https://github.com/HirokiYagyu/Slack
 * @package       Slack.Controller.Component
 * @since         SlackPlugin v1.0.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses('BaseComponent', 'Slack.Controller/Component');

/**
 * Slack API Channels コンポーネント.
 *
 * Get info on your team's Slack channels, create or archive channels, invite users, set the topic and purpose, and mark a channel as read.
 *
 * @package       Slack.Controller.Component
 */
class ChannelsComponent extends BaseComponent
{
    /**
     * Slack API method name.
     * @var string
     * @see https://api.slack.com/methods
     */
    protected static $_method = 'channels';

    /**
     * Authentication token.
     * @var string
     */
    const OPTION_TOKEN = 'token';

    /**
     * Channel name.
     * @var string
     */
    const OPTION_CHANNEL = 'channel';

    /**
     * Name of channel.
     * @var string
     */
    const OPTION_NAME = 'name';

    /**
     * End of time range of messages to include in results.
     * @var string
     */
    const OPTION_LATEST = 'latest';

    /**
     * Start of time range of messages to include in results.
     * @var string
     */
    const OPTION_OLDEST = 'oldest';

    /**
     * Include messages with latest or oldest timestamp in results.
     * @var string
     */
    const OPTION_INCLUSIVE = 'inclusive';

    /**
     * Number of messages to return, between 1 and 1000.
     * @var string
     */
    const OPTION_COUNT = 'count';

    /**
     * Include <code>unread_count_display</code> in the output?
     * @var string
     */
    const OPTION_UNREADS = 'unreads';

    /**
     * User to invite to channel.
     * @var string
     */
    const OPTION_USER = 'user';

    /**
     * Don't return archived channels.
     * @var string
     */
    const OPTION_EXCLUDE_ARCHIVED = 'exclude_archived';

    /**
     * Timestamp of the most recently seen message.
     * @var string
     */
    const OPTION_TS = 'ts';

    /**
     * The new purpose.
     * @var string
     */
    const OPTION_PURPOSE = 'purpose';

    /**
     * The new purpose.
     * @var string
     */
    const OPTION_TOPIC = 'topic';

    /**
     * チャンネルをアーカイブする.
     *
     * This method archives a channel.
     *
     * ### Eg.
     * ``` {.prettyprint .lang-php}
     * $Channels->archive( 'C1234567890' );
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
     * ChannelsComponent::OPTION_TOKEN
     * :  Authentication token (Requires scope: `channels:write`)
     *
     * ChannelsComponent::OPTION_CHANNEL
     * :  Channel to archive.
     *
     * @param string $channel チャンネル名.
     * @param array $option オプション.
     * @return mixed レスポンスデータ.
     *
     * @see https://api.slack.com/methods/channels.archive
     */
    public function archive( $channel, array $option=[] )
    {
        $requestData = [
            self::OPTION_TOKEN => Slack\API_TOKEN, // Authentication token (Requires scope: channels:write)
            self::OPTION_CHANNEL => $channel,
        ];
        $requestData = array_merge( $requestData, $option );
        $response = self::postRequest( 'archive', self::_nullFilter($requestData) );
        return $response;
    }

    /**
     * チャンネルを作成する.
     *
     * This method is used to create a channel.
     *
     * ### Eg.
     * ``` {.prettyprint .lang-php}
     * $Channels->create( 'foobar-channel' );
     * ```
     *
     * ### Response.
     * ``` {.prettyprint .lang-js}
     * {
     *   "ok": true,
     *   "channel": {
     *     "id": "C1234567890",
     *     "name": "foobar-channel",
     *     "is_channel": true,
     *     "created": 1440000000,
     *     "creator": "U01234567",
     *     "is_archived": false,
     *     "is_general": false,
     *     "is_member": true,
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
     * ChannelsComponent::OPTION_TOKEN
     * :  Authentication token (Requires scope: `channels:write`)
     *
     * ChannelsComponent::OPTION_NAME
     * :  Name of channel to create.
     *
     * @param string $name チャンネル名.
     * @param array $option オプション.
     * @return mixed レスポンスデータ.
     *
     * @see https://api.slack.com/methods/channels.create
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
     * メッセージ、イベントの履歴を取得する.
     *
     * This method returns a portion of messages/events from the specified channel.
     * To read the entire history for a channel, call the method with no `latest` or `oldest` arguments, and then continue paging using the instructions below.
     *
     * ### Eg.
     * ``` {.prettyprint .lang-php}
     * $Channels->fetchHistory( 'C1234567890' );
     * ```
     *
     * ### Response.
     * ``` {.prettyprint .lang-js}
     * {
     *   "ok": true,
     *   "messages": [
     *     {
     *       "user": "U01234567",
     *       "type": "message",
     *       "subtype": "channel_join",
     *       "text": "<@U01234567|user-name-sample> has joined the channel",
     *       "ts": "1440000000.000001"
     *     }
     *   ],
     *   "has_more": false
     * }
     * ```
     *
     * ### Use Option.
     * ChannelsComponent::OPTION_TOKEN
     * :  Authentication token (Requires scope: `channels:history`)
     *
     * ChannelsComponent::OPTION_CHANNEL
     * :  Channel to fetch history for.
     *
     * ChannelsComponent::OPTION_LATEST
     * :  End of time range of messages to include in results.
     *
     * ChannelsComponent::OPTION_OLDEST
     * :  Start of time range of messages to include in results.
     *
     * ChannelsComponent::OPTION_INCLUSIVE
     * :  Include messages with latest or oldest timestamp in results.
     *
     * ChannelsComponent::OPTION_COUNT
     * :  Number of messages to return, between 1 and 1000.
     *
     * ChannelsComponent::OPTION_UNREADS
     * :  Include `unread_count_display` in the output?
     *
     * @param string $channel チャンネル名.
     * @param array $option オプション.
     * @return mixed レスポンスデータ.
     *
     * @see https://api.slack.com/methods/channels.history
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
     * チャンネル情報を取得する.
     *
     * This method returns information about a team channel.
     *
     * ### Eg.
     * ``` {.prettyprint .lang-php}
     * $Channels->fetchInfo( 'C1234567890' );
     * ```
     *
     * ### Response.
     * ``` {.prettyprint .lang-js}
     * {
     *   "ok": true,
     *   "channel": {
     *     "id": "C1234567890",
     *     "name": "foobar-channel",
     *     "is_channel": true,
     *     "created": 1440000000,
     *     "creator": "U01234567",
     *     "is_archived": false,
     *     "is_general": false,
     *     "is_member": true,
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
     * ```
     *
     * ### Use Option.
     * ChannelsComponent::OPTION_TOKEN
     * :  Authentication token (Requires scope: `channels:read`)
     *
     * ChannelsComponent::OPTION_CHANNEL
     * :  Channel to get info on.
     *
     * @param string $channel チャンネル名.
     * @param array $option オプション.
     * @return mixed レスポンスデータ.
     *
     * @see https://api.slack.com/methods/channels.info
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
     * This method is used to invite a user to a channel.
     * The calling user must be a member of the channel.
     *
     * ### Eg.
     * ``` {.prettyprint .lang-php}
     * $Channels->invite( 'C1234567890', 'U01234567' );
     * ```
     *
     * ### Response.
     * ``` {.prettyprint .lang-js}
     * {
     *   "ok": true,
     *   "channel": {
     *     "id": "C1234567890",
     *     "name": "foobar-channel",
     *     "is_channel": true,
     *     "created": 1440000000,
     *     "creator": "U01234567",
     *     "is_archived": false,
     *     "is_general": false,
     *     "is_member": true,
     *     "last_read": "1440000000.000001",
     *     "latest": {
     *       "user": "U01234567",
     *       "type": "message",
     *       "subtype": "channel_join",
     *       "text": "<@U01234567|user-name-sample> has joined the channel",
     *       "ts": "1440000000.000001"
     *     },
     *     "unread_count": 0,
     *     "unread_count_display": 0,
     *     "members": [
     *       "U01234567",
     *       "U12345678"
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
     * ChannelsComponent::OPTION_TOKEN
     * :  Authentication token (Requires scope: `channels:write`)
     *
     * ChannelsComponent::OPTION_CHANNEL
     * :  Channel to invite user to.
     *
     * ChannelsComponent::OPTION_USER
     * :  User to invite to channel.
     *
     * @param string $channel チャンネル名.
     * @param string $user ユーザ名.
     * @param array $option オプション.
     * @return mixed レスポンスデータ.
     *
     * @see https://api.slack.com/methods/channels.invite
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
     * チャンネルに参加する.
     *
     * This method is used to join a channel.
     * If the channel does not exist, it is created.
     *
     * ### Eg.
     * ``` {.prettyprint .lang-php}
     * $Channels->join( 'foobar-channel' );
     * ```
     *
     * ### Response.
     * ``` {.prettyprint .lang-js}
     * {
     *   "ok": true,
     *   "channel": {
     *     "id": "C1234567890",
     *     "name": "foobar-channel",
     *     "is_channel": true,
     *     "created": 1440000000,
     *     "creator": "U01234567",
     *     "is_archived": false,
     *     "is_general": false,
     *     "is_member": true,
     *     "last_read": "1440000000.000001",
     *     "latest": {
     *       "user": "U01234567",
     *       "type": "message",
     *       "subtype": "channel_join",
     *       "text": "<@U01234567|user-name-sample> has joined the channel",
     *       "ts": "1440000000.000001"
     *     },
     *     "unread_count": 0,
     *     "unread_count_display": 0,
     *     "members": [
     *       "U01234567",
     *       "U12345678"
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
     * ChannelsComponent::OPTION_TOKEN
     * :  Authentication token (Requires scope: `channels:read`)
     *
     * ChannelsComponent::OPTION_NAME
     * :  Name of channel to join.
     *
     * @param string $name チャンネル名.
     * @param array $option オプション.
     * @return mixed レスポンスデータ.
     *
     * @see https://api.slack.com/methods/channels.join
     */
    public function join( $name, array $option=[] )
    {
        $requestData = [
            self::OPTION_TOKEN => Slack\API_TOKEN,
            self::OPTION_NAME => $name,
        ];
        $requestData = array_merge( $requestData, $option );
        $response = self::postRequest( 'join', self::_nullFilter($requestData) );
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
     * ChannelsComponent::OPTION_TOKEN
     * :  Authentication token (Requires scope: `channels:write`)
     *
     * ChannelsComponent::OPTION_CHANNEL
     * :  Channel to remove user from.
     *
     * ChannelsComponent::OPTION_USER
     * :  User to remove from channel.
     *
     * @param string $channel チャンネル名.
     * @param string $user ユーザ名.
     * @param array $option オプション.
     * @return mixed レスポンスデータ.
     *
     * @see https://api.slack.com/methods/channels.kick
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
     * チャンネルから退席する.
     *
     * This method is used to leave a channel.
     *
     * ### Eg.
     * ``` {.prettyprint .lang-php}
     * $Channels->leave( 'C1234567890' );
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
     * ChannelsComponent::OPTION_TOKEN
     * :  Authentication token (Requires scope: `channels:write`)
     *
     * ChannelsComponent::OPTION_CHANNEL
     * :  Channel to leave.
     *
     * @param string $channel チャンネル名.
     * @param array $option オプション.
     * @return mixed レスポンスデータ.
     *
     * @see https://api.slack.com/methods/channels.leave
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
     * ### Eg.
     * ``` {.prettyprint .lang-php}
     * $Channels->fetchList();
     * ```
     *
     * ### Response.
     * ``` {.prettyprint .lang-js}
     * {
     *   "ok": true,
     *   "channels": [
     *     {
     *       "id": "C1234567890",
     *       "name": "foobar-channel",
     *       "is_channel": true,
     *       "created": 1440000000,
     *       "creator": "U01234567",
     *       "is_archived": false,
     *       "is_general": false,
     *       "is_member": true,
     *       "members": [
     *         "U01234567"
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
     *       },
     *       "num_members": 0
     *     }
     *   ]
     * }
     * ```
     *
     * ### Use Option.
     * ChannelsComponent::OPTION_TOKEN
     * :  Authentication token (Requires scope: `channels:write`)
     *
     * ChannelsComponent::OPTION_EXCLUDE_ARCHIVED
     * :  Don't return archived channels.
     *
     * @param array $option オプション.
     * @return mixed レスポンスデータ.
     *
     * @see https://api.slack.com/methods/channels.list
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
     * This method moves the read cursor in a channel.
     *
     * ### Eg.
     * ``` {.prettyprint .lang-php}
     * $Channels->mark( 'C1234567890', '0000000000.000000' );
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
     * ChannelsComponent::OPTION_TOKEN
     * :  Authentication token (Requires scope: `channels:write`)
     *
     * ChannelsComponent::OPTION_CHANNEL
     * :  Channel to set reading cursor in.
     *
     * ChannelsComponent::OPTION_TS
     * :  Timestamp of the most recently seen message.
     *
     * @param string $channel チャンネル名.
     * @param integer $timestamp read cursor を移動したいメッセージの投稿日時(UNIX TIME).
     * @param array $option オプション.
     * @return mixed レスポンスデータ.
     *
     * @see https://api.slack.com/methods/channels.mark
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
     * チャンネル名を変更する.
     *
     * ### Eg.
     * ``` {.prettyprint .lang-php}
     * $Channels->rename( 'C1234567890', 'new-name' );
     * ```
     *
     * ### Response.
     * ``` {.prettyprint .lang-js}
     * {
     *   "ok": true,
     *   "channel": {
     *     "id": "C1234567890",
     *     "is_channel": true,
     *     "name": "new_name",
     *     "created": 1440000000
     *   }
     * }
     * ```
     *
     * ### Use Option.
     * ChannelsComponent::OPTION_TOKEN
     * :  Authentication token (Requires scope: `channels:write`)
     *
     * ChannelsComponent::OPTION_CHANNEL
     * :  Channel to get info on.
     *
     * @param string $channel チャンネル名.
     * @param string $rename 変更したいチャンネル名.
     * @param array $option オプション.
     * @return mixed レスポンスデータ.
     *
     * @see https://api.slack.com/methods/channels.rename
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
     * This method is used to change the purpose of a channel.
     * The calling user must be a member of the channel.
     *
     * ### Eg.
     * ``` {.prettyprint .lang-php}
     * $Channels->setPurpose( 'C1234567890', 'This is the new purpose!' );
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
     * ChannelsComponent::OPTION_TOKEN
     * :  Authentication token (Requires scope: `channels:write`)
     *
     * ChannelsComponent::OPTION_CHANNEL
     * :  Channel to set the purpose of.
     *
     * ChannelsComponent::OPTION_PURPOSE
     * :  The new purpose.
     *
     * @param string $channel チャンネル名.
     * @param string $purpose 目的.
     * @param array $option オプション.
     * @return mixed レスポンスデータ.
     *
     * @see https://api.slack.com/methods/channels.setPurpose
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
     * This method is used to change the topic of a channel.
     * The calling user must be a member of the channel.
     *
     * ### Eg.
     * ``` {.prettyprint .lang-php}
     * $Channels->setTopic( 'C1234567890', 'This is the new topic!' );
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
     * ChannelsComponent::OPTION_TOKEN
     * :  Authentication token (Requires scope: `channels:write`)
     *
     * ChannelsComponent::OPTION_CHANNEL
     * :  Channel to set the topic of.
     *
     * ChannelsComponent::OPTION_TOPIC
     * :  The new topic.
     *
     * @param string $channel チャンネル名.
     * @param string $topic トピック.
     * @param array $option オプション.
     * @return mixed レスポンスデータ.
     *
     * @see https://api.slack.com/methods/channels.setTopic
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
     * アーカイブされたチャンネルを元に戻す.
     *
     * This method unarchives a channel.
     * The calling user is added to the channel.
     *
     * ### Eg.
     * ``` {.prettyprint .lang-php}
     * $Channels->unarchive( 'C1234567890' );
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
     * ChannelsComponent::OPTION_TOKEN
     * :  Authentication token (Requires scope: `channels:write`)
     *
     * ChannelsComponent::OPTION_CHANNEL
     * :  Channel to unarchive.
     *
     * @param string $channel チャンネル名.
     * @param array $option オプション.
     * @return mixed レスポンスデータ.
     *
     * @see https://api.slack.com/methods/channels.unarchive
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
