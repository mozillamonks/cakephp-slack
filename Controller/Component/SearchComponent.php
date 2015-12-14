<?php
/**
 * Slack API - search method implementation.
 *
 * @author        Hiroki Yagyu.
 * @link          https://github.com/HirokiYagyu/Slack
 * @package       Slack.Controller.Component
 * @since         SlackPlugin v1.0.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses('BaseComponent', 'Slack.Controller/Component');

/**
 * Slack API Search コンポーネント.
 *
 * Search your team's files and messages.
 *
 * @package       Slack.Controller.Component
 */
class SearchComponent extends BaseComponent
{
    /**
     * Slack API method name.
     * @var string
     * @see https://api.slack.com/methods
     */
    protected static $_method = 'search';

    /**
     * Authentication token.
     * @var string
     */
    const OPTION_TOKEN = 'token';

    /**
     * Search query. May contains booleans, etc.
     * @var string
     */
    const OPTION_QUERY = 'query';

    /**
     * Return matches sorted by either <code>score</code> or <code>timestamp</code>.
     * @var string
     */
    const OPTION_SORT = 'sort';

    /**
     * Change sort direction to ascending (<code>asc</code>) or descending (<code>desc</code>).
     * @var string
     */
    const OPTION_SORT_DIR = 'sort_dir';

    /**
     * Pass a value of <code>1</code> to enable query highlight markers (see below).
     * @var string
     */
    const OPTION_HIGHLIGHT = 'highlight';

    /**
     * Number of items to return per page.
     * @var string
     */
    const OPTION_COUNT = 'count';

    /**
     * Page number of results to return.
     * @var string
     */
    const OPTION_PAGE = 'page';

    /**
     * 指定されたクエリをチーム全体から検索する.
     *
     * This method allows to to search both messages and files in a single call.
     *
     * ### Eg.
     * ```
     * $Search->all( 'sample text' );
     * ```
     *
     * ### Response.
     * ```
     * {
     *     "ok": true,
     *     "query": "sample text",
     *     "messages": {
     *         "total": 5,
     *         "pagination": {omit...},
     *         "paging": {omit...},
     *         "matches": [
     *             {omit...}
     *         ]
     *     },
     *     "files": {
     *         "total": 3,
     *         "pagination": {omit...},
     *         "paging": {omit...},
     *         "matches": [
     *             {omit...},
     *             {omit...},
     *             {omit...}
     *         ]
     *     },
     *     "posts": {
     *         "total": 0,
     *         "matches": []
     *     }
     * }
     * ```
     *
     * ### Use Option.
     * <dl class="tree">
     *   <dt>SearchComponent::OPTION_TOKEN</dt>
     *     <dd>Authentication token (Requires scope: `search:read`)</dd>
     *   <dt>SearchComponent::OPTION_QUERY</dt>
     *     <dd>Search query. May contains booleans, etc.</dd>
     *   <dt>SearchComponent::OPTION_SORT</dt>
     *     <dd>Return matches sorted by either `score` or `timestamp`.</dd>
     *   <dt>SearchComponent::OPTION_SORT_DIR</dt>
     *     <dd>Change sort direction to ascending (`asc`) or descending (`desc`).</dd>
     *   <dt>SearchComponent::OPTION_HIGHLIGHT</dt>
     *     <dd>Pass a value of `1` to enable query highlight markers (see below).</dd>
     *   <dt>SearchComponent::OPTION_COUNT</dt>
     *     <dd>Number of items to return per page.</dd>
     *   <dt>SearchComponent::OPTION_PAGE</dt>
     *     <dd>Page number of results to return.</dd>
     * </dl>
     *
     * @param string $query 検索対象.
     * @param array $option オプション.
     * @return mixed レスポンスデータ.
     *
     * @see https://api.slack.com/methods/search.all
     */
    public function fetchAll( $query, array $option=[] )
    {
        $requestData = [
            self::OPTION_TOKEN => Slack\API_TOKEN,
            self::OPTION_QUERY => $query,
            self::OPTION_SORT => null,
            self::OPTION_SORT_DIR => null,
            self::OPTION_HIGHLIGHT => null,
            self::OPTION_COUNT => null,
            self::OPTION_PAGE => null,
        ];
        $requestData = array_merge( $requestData, $option );
        $response = self::getRequest( 'all', self::_nullFilter($requestData) );
        return $response;
    }

    /**
     * 指定されたクエリをファイルから検索する.
     *
     * This method returns files matching a search query.
     *
     * ### Eg.
     * ```
     * $Search->fetchFiles( 'sample text' );
     * ```
     *
     * ### Response.
     * ```
     * {
     *     "ok": true,
     *     "query": "sample text",
     *     "files": {
     *         "total": 3,
     *         "pagination": {omit...},
     *         "paging": {omit...},
     *         "matches": [
     *             {omit...},
     *             {omit...},
     *             {omit...}
     *         ]
     *     }
     * }
     * ```
     *
     * ### Use Option.
     * <dl class="tree">
     *   <dt>SearchComponent::OPTION_TOKEN</dt>
     *     <dd>Authentication token (Requires scope: `search:read`)</dd>
     *   <dt>SearchComponent::OPTION_QUERY</dt>
     *     <dd>Search query. May contains booleans, etc.</dd>
     *   <dt>SearchComponent::OPTION_SORT</dt>
     *     <dd>Return matches sorted by either `score` or `timestamp`.</dd>
     *   <dt>SearchComponent::OPTION_SORT_DIR</dt>
     *     <dd>Change sort direction to ascending (`asc`) or descending (`desc`).</dd>
     *   <dt>SearchComponent::OPTION_HIGHLIGHT</dt>
     *     <dd>Pass a value of `1` to enable query highlight markers.</dd>
     *   <dt>SearchComponent::OPTION_COUNT</dt>
     *     <dd>Number of items to return per page.</dd>
     *   <dt>SearchComponent::OPTION_PAGE</dt>
     *     <dd>Page number of results to return.</dd>
     * </dl>
     *
     * @param string $query 検索対象.
     * @param array $option オプション.
     * @return mixed レスポンスデータ.
     *
     * @see https://api.slack.com/methods/search.files
     */
    public function fetchFiles( $query, array $option=[] )
    {
        $requestData = [
            self::OPTION_TOKEN => Slack\API_TOKEN,
            self::OPTION_QUERY => $query,
            self::OPTION_SORT => null,
            self::OPTION_SORT_DIR => null,
            self::OPTION_HIGHLIGHT => null,
            self::OPTION_COUNT => null,
            self::OPTION_PAGE => null,
        ];
        $requestData = array_merge( $requestData, $option );
        $response = self::getRequest( 'files', self::_nullFilter($requestData) );
        return $response;
    }

    /**
     * 指定されたクエリをメッセージから検索する.
     *
     * This method returns messages matching a search query.
     *
     * ### Eg.
     * ```
     * $Search->fetchMessages( 'sample text' );
     * ```
     *
     * ### Response.
     * ```
     * {
     *     "ok": true,
     *     "query": "sample text",
     *     "messages": {
     *         "total": 1,
     *         "pagination": {omit...},
     *         "paging": {omit...},
     *         "matches": [
     *             {omit...}
     *         ]
     *     }
     * }
     * ```
     *
     * ### Use Option.
     * <dl class="tree">
     *   <dt>SearchComponent::OPTION_TOKEN</dt>
     *     <dd>Authentication token (Requires scope: `search:read`)</dd>
     *   <dt>SearchComponent::OPTION_QUERY</dt>
     *     <dd>Search query. May contains booleans, etc.</dd>
     *   <dt>SearchComponent::OPTION_SORT</dt>
     *     <dd>Return matches sorted by either `score` or `timestamp`.</dd>
     *   <dt>SearchComponent::OPTION_SORT_DIR</dt>
     *     <dd>Change sort direction to ascending (`asc`) or descending (`desc`).</dd>
     *   <dt>SearchComponent::OPTION_HIGHLIGHT</dt>
     *     <dd>Pass a value of `1` to enable query highlight markers.</dd>
     *   <dt>SearchComponent::OPTION_COUNT</dt>
     *     <dd>Number of items to return per page.</dd>
     *   <dt>SearchComponent::OPTION_PAGE</dt>
     *     <dd>Page number of results to return.</dd>
     * </dl>
     *
     * @param string $query 検索対象.
     * @param array $option オプション.
     * @return mixed レスポンスデータ.
     *
     * @see https://api.slack.com/methods/search.files
     */
    public function fetchMessages( $query, array $option=[] )
    {
        $requestData = [
            self::OPTION_TOKEN => Slack\API_TOKEN,
            self::OPTION_QUERY => $query,
            self::OPTION_SORT => null,
            self::OPTION_SORT_DIR => null,
            self::OPTION_HIGHLIGHT => null,
            self::OPTION_COUNT => null,
            self::OPTION_PAGE => null,
        ];
        $requestData = array_merge( $requestData, $option );
        $response = self::getRequest( 'messages', self::_nullFilter($requestData) );
        return $response;
    }
}
