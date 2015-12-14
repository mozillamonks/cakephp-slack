<?php
/**
 * Slack API - chat method implementation.
 *
 * @author        Hiroki Yagyu.
 * @link          https://github.com/HirokiYagyu/Slack
 * @package       Slack.Controller.Component
 * @since         SlackPlugin v1.0.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses('BaseComponent', 'Slack.Controller/Component');

/**
 * Slack API Chat コンポーネント.
 *
 * Post chat messages to Slack.
 *
 * @package       Slack.Controller.Component
 */
class ChatComponent extends BaseComponent
{
    /**
     * Slack API method name.
     * @var string
     * @see https://api.slack.com/methods
     */
    protected static $_method = 'chat';

    /**
     * Authentication token.
     * @var string
     */
    const OPTION_TOKEN = 'token';

    /**
     * Timestamp of the message.
     * @var string
     */
    const OPTION_TS = 'ts';

    /**
     * Channel containing the message.
     * @var string
     */
    const OPTION_CHANNEL = 'channel';

    /**
     * New text for the message, using the default formatting rules.
     * @var string
     * @see https://api.slack.com/docs/formatting
     */
    const OPTION_TEXT = 'text';

    /**
     * Name of bot.
     * @var string
     */
    const OPTION_USERNAME = 'username';

    /**
     * Pass true to post the message as the authed user, instead of as a bot.
     * @var string
     */
    const OPTION_AS_USER = 'as_user';

    /**
     * Structured message attachments.
     * @var string
     */
    const OPTION_ATTACHMENTS = 'attachments';

    /**
     * Change how messages are treated.
     * @var string
     */
    const OPTION_PARSE = 'parse';

    /**
     * Find and link channel names and usernames.
     * @var string
     */
    const OPTION_LINK_NAMES = 'link_names';

    /**
     * Pass true to enable unfurling of primarily text-based content.
     * @var string
     */
    const OPTION_UNFURL_LINKS = 'unfurl_links';

    /**
     * Pass false to disable unfurling of media content.
     * @var string
     */
    const OPTION_UNFURL_MEDIA = 'unfurl_media';

    /**
     * URL to an image to use as the icon for this message.
     * @var string
     */
    const OPTION_ICON_URL = 'icon_url';

    /**
     * emoji to use as the icon for this message.
     * @var string
     */
    const OPTION_ICON_EMOJI = 'icon_emoji';


    /**
     * メッセージを投稿する.
     *
     * This method posts a message to a public channel, private group, or IM channel.
     *
     * ### Eg.
     * ```
     * $Chat->post( 'C1234567890', 'hola!' );
     * ```
     *
     * ### Response.
     * ```
     * {
     *     "ok": true,
     *     "channel": "C1234567890",
     *     "ts": "1440000000.000001",
     *     "message": {
     *         "text": "hola",
     *         "username": "bot",
     *         "type": "message",
     *         "subtype": "bot_message",
     *         "ts": "1440000000.000001"
     *     }
     * }
     * ```
     *
     * ### Use Option.
     * <dl class="tree">
     *     <dt>ChatComponent::OPTION_TOKEN</dt>
     *       <dd>Authentication token (Requires scope: `identify`)</dd>
     *     <dt>ChatComponent::OPTION_TOKEN</dt>
     *       <dd>Authentication token (Requires scope: `chat:write:bot` or `chat:write:user`)</dd>
     *     <dt>ChatComponent::OPTION_CHANNEL</dt>
     *       <dd>Channel, private group, or IM channel to send message to. Can be an encoded ID, or a name. See below for more details.</dd>
     *     <dt>ChatComponent::OPTION_TEXT</dt>
     *       <dd>Text of the message to send. See below for an explanation of formatting.</dd>
     *     <dt>ChatComponent::OPTION_USERNAME</dt>
     *       <dd>Name of bot.</dd>
     *     <dt>ChatComponent::OPTION_AS_USER</dt>
     *       <dd>Pass true to post the message as the authed user, instead of as a bot.</dd>
     *     <dt>ChatComponent::OPTION_PARSE</dt>
     *       <dd>Change how messages are treated. See below.</dd>
     *     <dt>ChatComponent::OPTION_LINK_NAMES</dt>
     *       <dd>Find and link channel names and usernames.</dd>
     *     <dt>ChatComponent::OPTION_ATTACHMENTS</dt>
     *       <dd>Structured message attachments.</dd>
     *     <dt>ChatComponent::OPTION_UNFURL_LINKS</dt>
     *       <dd>Pass true to enable unfurling of primarily text-based content.</dd>
     *     <dt>ChatComponent::OPTION_UNFURL_MEDIA</dt>
     *       <dd>Pass false to disable unfurling of media content.</dd>
     *     <dt>ChatComponent::OPTION_ICON_URL</dt>
     *       <dd>URL to an image to use as the icon for this message.</dd>
     *     <dt>ChatComponent::OPTION_ICON_EMOJI</dt>
     *       <dd>emoji to use as the icon for this message. Overrides `icon_url`.</dd>
     * </dl>
     *
     * @param string $channel チャンネル名.
     * @param string $text 投稿したいテキスト.
     * @param array $option オプション.
     * @return mixed レスポンスデータ.
     *
     * @see https://api.slack.com/methods/chat.postMessage
     */
    public function post( $channel, $text, array $option=[] )
    {
        $requestData = [
            self::OPTION_TOKEN => Slack\API_TOKEN,
            self::OPTION_CHANNEL => $channel,
            self::OPTION_TEXT => $text,
            self::OPTION_USERNAME => null,
            self::OPTION_AS_USER => null,
            self::OPTION_PARSE => null,
            self::OPTION_LINK_NAMES => null,
            self::OPTION_ATTACHMENTS => null,
            self::OPTION_UNFURL_LINKS => null,
            self::OPTION_UNFURL_MEDIA => null,
            self::OPTION_ICON_URL => null,
            self::OPTION_ICON_EMOJI => null,
        ];
        $requestData = array_merge( $requestData, $option );
        $response = self::postRequest( 'postMessage', self::_nullFilter($requestData) );
        return $response;
    }

    /**
     * メッセージを削除する.
     *
     * This method deletes a message from a channel.
     *
     * ### Eg.
     * ```
     * $Chat->erase( 'C1234567890', '1440000000.000001' );
     * ```
     *
     * ### Response.
     * ```
     * {
     *     "ok": true,
     *     "channel": "C0123456789",
     *     "ts": "1440000000.000001"
     * }
     * ```
     *
     * ### Use Option.
     * <dl class="tree">
     *   <dt>ChatComponent::OPTION_TOKEN</dt>
     *     <dd>Authentication token (Requires scope: `chat:write`)</dd>
     *   <dt>ChatComponent::OPTION_TS</dt>
     *     <dd>Timestamp of the message to be deleted.</dd>
     *   <dt>ChatComponent::OPTION_CHANNEL</dt>
     *     <dd>Channel containing the message to be deleted.</dd>
     * </dl>
     *
     * @param string $channel チャンネル名.
     * @param integer $timestamp 削除したいメッセージの投稿日時(UNIX TIME).
     * @param array $option オプション.
     * @return mixed レスポンスデータ.
     *
     * @see https://api.slack.com/methods/chat.delete
     */
    public function erase( $channel, $timestamp, array $option=[] )
    {
        $requestData = [
            self::OPTION_TOKEN => Slack\API_TOKEN,
            self::OPTION_TS => $timestamp,
            self::OPTION_CHANNEL => $channel,
        ];
        $requestData = array_merge( $requestData, $option );
        $response = self::postRequest( 'delete', self::_nullFilter($requestData) );
        return $response;
    }

    /**
     * メッセージを更新する.
     *
     * This method updates a message in a channel.
     *
     * ### Eg.
     * ```
     * $Chat->update( 'C1234567890', '1440000000.000001', 'hola!' );
     * ```
     *
     * ### Response.
     * ```
     * {
     *     "ok": true,
     *     "channel": "C1234567890",
     *     "ts": "1440000000.000001",
     *     "text": "hola!",
     *     "message": {
     *         "type": "message",
     *         "user": "U01234567",
     *         "text": "hola!"
     *     }
     * }
     * ```
     *
     * ### Use Option.
     * <dl class="tree">
     *   <dt>ChatComponent::OPTION_TOKEN</dt>
     *     <dd>Authentication token (Requires scope: `chat:write`)</dd>
     *   <dt>ChatComponent::OPTION_TS</dt>
     *     <dd>Timestamp of the message to be updated.</dd>
     *   <dt>ChatComponent::OPTION_CHANNEL</dt>
     *     <dd>Channel containing the message to be updated.</dd>
     *   <dt>ChatComponent::OPTION_TEXT</dt>
     *     <dd>New text for the message, using the default formatting rules.</dd>
     *   <dt>ChatComponent::OPTION_ATTACHMENTS</dt>
     *     <dd>Structured message attachments.</dd>
     *   <dt>ChatComponent::OPTION_PARSE</dt>
     *     <dd>Change how messages are treated. See below.</dd>
     *   <dt>ChatComponent::OPTION_LINK_NAMES</dt>
     *     <dd>Find and link channel names and usernames.</dd>
     * </dl>
     *
     * @param string $channel チャンネル名.
     * @param integer $timestamp 更新したいメッセージの投稿日時(UNIX TIME).
     * @param string $text 投稿したいテキスト.
     * @param array $option オプション.
     * @return mixed レスポンスデータ.
     *
     * @see https://api.slack.com/methods/chat.update
     */
    public function update( $channel, $timestamp, $text, array $option=[] )
    {
        $requestData = [
            self::OPTION_TOKEN => Slack\API_TOKEN,
            self::OPTION_TS => $timestamp,
            self::OPTION_CHANNEL => $channel,
            self::OPTION_TEXT => $text,
            self::OPTION_ATTACHMENTS => null,
            self::OPTION_PARSE => null,
            self::OPTION_LINK_NAMES => null,
        ];
        $requestData = array_merge( $requestData, $option );
        $response = self::postRequest( 'update', self::_nullFilter($requestData) );
        return $response;
    }
}
