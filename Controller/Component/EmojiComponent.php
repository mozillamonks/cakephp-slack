<?php
/**
 * Slack API - emoji method implementation.
 *
 * @author        Hiroki Yagyu.
 * @link          https://github.com/HirokiYagyu/Slack
 * @package       Slack.Controller.Component
 * @since         SlackPlugin v1.0.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses('BaseComponent', 'Slack.Controller/Component');

/**
 * Slack API Emoji コンポーネント.
 *
 * @package       Slack.Controller.Component
 */
class EmojiComponent extends BaseComponent
{
    /**
     * Slack API method name.
     * @var string
     * @see https://api.slack.com/methods
     */
    protected static $_method = 'emoji';

    /**
     * Authentication token.
     * @var string
     */
    const OPTION_TOKEN = 'token';

    /**
     * リストを取得する.
     *
     * This method lists the custom emoji for a team.
     *
     * ### Eg.
     * ```
     * $Emoji->fetchList();
     * ```
     *
     * ### Response.
     * ```
     * {
     *     "ok": true,
     *     "emoji": {
     *         "bowtie": "https://myteam.slack.com/emoji/bowtie/f3ec6f2bb0.png",
     *         "neckbeard": "https://myteam.slack.com/emoji/neckbeard/c8ec7bf188.png",
     *         "metal": "https://myteam.slack.com/emoji/metal/9f936a4278.png",
     *         "fu": "https://myteam.slack.com/emoji/fu/2f615de37f.png",
     *         "feelsgood": "https://myteam.slack.com/emoji/feelsgood/7bcbaa15fa.png",
     *         "finnadie": "https://myteam.slack.com/emoji/finnadie/08e66eb46d.png",
     *         "goberserk": "https://myteam.slack.com/emoji/goberserk/d8b892d59b.png",
     *         "godmode": "https://myteam.slack.com/emoji/godmode/1bd6476fbb.png",
     *         "hurtrealbad": "https://myteam.slack.com/emoji/hurtrealbad/b9c3d648e6.png",
     *         "rage1": "https://myteam.slack.com/emoji/rage1/0c3685290c.png",
     *         "rage2": "https://myteam.slack.com/emoji/rage2/feaf8897c6.png",
     *         "rage3": "https://myteam.slack.com/emoji/rage3/8e11678fbf.png",
     *         "rage4": "https://myteam.slack.com/emoji/rage4/a8029a3996.png",
     *         "suspect": "https://myteam.slack.com/emoji/suspect/ca4ab3c7c7.png",
     *         "trollface": "https://myteam.slack.com/emoji/trollface/8c0ac4ae98.png",
     *         "octocat": "https://myteam.slack.com/emoji/octocat/627964d7c9.png",
     *         "squirrel": "https://myteam.slack.com/emoji/squirrel/465f40c0e0.png",
     *         "glitch_crab": "https://myteam.slack.com/emoji/glitch_crab/db049f1f9c.png",
     *         "piggy": "https://myteam.slack.com/emoji/piggy/b7762ee8cd.png",
     *         "cubimal_chick": "https://myteam.slack.com/emoji/cubimal_chick/85961c43d7.png",
     *         "beryl": "https://myteam.slack.com/emoji/beryl/efe1ba7e7a.png",
     *         "dusty_stick": "https://myteam.slack.com/emoji/dusty_stick/6177a62312.png",
     *         "rube": "https://myteam.slack.com/emoji/rube/74ef8ef199.png",
     *         "slack": "https://myteam.slack.com/emoji/slack/5ee0c9bea3.png",
     *         "pride": "https://myteam.slack.com/emoji/pride/56b1bd3388.png",
     *         "shipit": "alias:squirrel",
     *         "troll": "alias:trollface",
     *         "white_square": "alias:white_large_square",
     *         "black_square": "alias:black_large_square",
     *         "simple_smile": "https://slack.global.ssl.fastly.net/66f9/img/emoji_2015/apple-old/simple_smile.png"
     *     }
     * }
     * ```
     *
     * ### Use Option.
     * <dl class="tree">
     *   <dt>EmojiComponent::OPTION_TOKEN</dt>
     *     <dd>Authentication token (Requires scope: `emoji:read`)</dd>
     * </dl>
     *
     * @param array $option オプション.
     * @return mixed レスポンスデータ.
     *
     * @see https://api.slack.com/methods/emoji.list
     */
    public function fetchList( array $option=[] )
    {
        $requestData = [
            self::OPTION_TOKEN => Slack\API_TOKEN,
        ];
        $requestData = array_merge( $requestData, $option );
        $response = self::getRequest( 'list', self::_nullFilter($requestData) );
        return $response;
    }
}
