<?php
/**
 * Slack API - files method implementation.
 *
 * @author        Hiroki Yagyu.
 * @link          https://github.com/HirokiYagyu/Slack
 * @package       Slack.Controller.Component
 * @since         SlackPlugin v1.0.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses('BaseComponent', 'Slack.Controller/Component');

/**
 * Slack API Files コンポーネント.
 *
 * Get info on files uploaded to Slack, upload new files to Slack.
 *
 * @package       Slack.Controller.Component
 */
class FilesComponent extends BaseComponent
{
    /**
     * Slack API method name.
     * @var string
     * @see https://api.slack.com/methods
     */
    protected static $_method = 'files';

    /**
     * Authentication token.
     * @var string
     */
    const OPTION_TOKEN = 'token';

    /**
     * Filter files created by a single user.
     * @var string
     */
    const OPTION_USER = 'user';

    /**
     * Filter files created after this timestamp (inclusive).
     * @var string
     */
    const OPTION_TS_FROM = 'ts_from';

    /**
     * Filter files created before this timestamp (inclusive).
     * @var string
     */
    const OPTION_TS_TO = 'ts_to';

    /**
     * Filter files by type:
     * <ul>
     *   <li><code>all</code> - All files</li>
     *   <li><code>posts</code> - Posts</li>
     *   <li><code>snippets</code> - Snippets</li>
     *   <li><code>images</code> - Image files</li>
     *   <li><code>gdocs</code> - Google docs</li>
     *   <li><code>zips</code> - Zip files</li>
     *   <li><code>pdfs</code> - PDF files</li>
     * </ul>
     * You can pass multiple values in the types argument, like <code>types=posts,snippets</code>.
     * The default value is <code>all</code>, which does not filter the list.
     */
    const OPTION_TYPES = 'types';

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
     * File to fetch info for.
     * @var string
     */
    const OPTION_FILE = 'file';

    /**
     * File contents via a POST var.
     * @var string
     */
    const OPTION_CONTENT = 'content';

    /**
     * Slack-internal file type identifier.
     * @var string
     */
    const OPTION_FILETYPE = 'filetype';

    /**
     * Filename of file.
     * @var string
     */
    const OPTION_FILENAME = 'filename';

    /**
     * Title of file.
     * @var string
     */
    const OPTION_TITLE = 'title';

    /**
     * Initial comment to add to file.
     * @var string
     */
    const OPTION_INITIAL_COMMENT = 'initial_comment';

    /**
     * Comma separated list of channels to share the file into. */
    const OPTION_CHANNELS = 'channels';

    /**
     * リストを取得する.
     *
     * This method returns a list of files within the team.
     * It can be filtered and sliced in various ways.
     *
     * ### Eg.
     * ```
     * $Files->fetchList();
     * ```
     *
     * ### Response.
     * ```
     * {
     *     "ok": true,
     *     "files": [
     *         {
     *             "id": "F01234567",
     *             "created": 1440000000,
     *             "timestamp": 1440000000,
     *             "name": "sample.txt",
     *             "title": "Untitled",
     *             "mimetype": "text/plain",
     *             "filetype": "text",
     *             "pretty_type": "Plain Text",
     *             "user": "U01234567",
     *             "editable": true,
     *             "size": 12,
     *             "mode": "snippet",
     *             "is_external": false,
     *             "external_type": "",
     *             "is_public": true,
     *             "public_url_shared": false,
     *             "display_as_bot": false,
     *             "username": "",
     *             "url": "https://slack-files.com/files-pub/T01234567-F01234567-0000000000/sample.txt",
     *             "url_download": "https://slack-files.com/files-pub/T01234567-F01234567-0000000000/download/sample.txt",
     *             "url_private": "https://files.slack.com/files-pri/T01234567-F01234567/sample.txt",
     *             "url_private_download": "https://files.slack.com/files-pri/T01234567-F01234567/download/sample.txt",
     *             "permalink": "https://{your_team}.slack.com/files/{your_name}/F01234567/sample.txt",
     *             "permalink_public": "https://slack-files.com/T01234567-F01234567-0000000000",
     *             "edit_link": "https://{your_team}.slack.com/files/{your_name}/F01234567/sample.txt/edit",
     *             "preview": "Hello Slack!",
     *             "preview_highlight": "<div class=\"CodeMirror cm-s-default CodeMirrorServer\">\n<div class=\"CodeMirror-code\">\n<div><pre>Hello Slack!</pre></div>\n</div>\n</div>\n",
     *             "lines": 1,
     *             "lines_more": 0,
     *             "channels": [
     *                 "C0EPZ563D"
     *             ],
     *             "groups": [],
     *             "ims": [],
     *             "comments_count": 0
     *         }
     *     ],
     *     "paging": {
     *         "count": 100,
     *         "total": 1,
     *         "page": 1,
     *         "pages": 1
     *     }
     * }
     * ```
     *
     * ### Use Option.
     * <dl class="tree">
     *     <dt>FilesComponent::OPTION_TOKEN</dt>
     *       <dd>Authentication token (Requires scope: `files:read`)</dd>
     *     <dt>FilesComponent::OPTION_USER</dt>
     *       <dd>Filter files created by a single user.</dd>
     *     <dt>FilesComponent::OPTION_TS_FROM</dt>
     *       <dd>Filter files created after this timestamp (inclusive).</dd>
     *     <dt>FilesComponent::OPTION_TS_TO</dt>
     *       <dd>Filter files created before this timestamp (inclusive).</dd>
     *     <dt>FilesComponent::OPTION_TYPES</dt>
     *       <dd>Filter files by type:
     *         <ul>
     *           <li>`all` - All files</li>
     *           <li>`posts` - Posts</li>
     *           <li>`snippets` - Snippets</li>
     *           <li>`images` - Image files</li>
     *           <li>`gdocs` - Google docs</li>
     *           <li>`zips` - Zip files</li>
     *           <li>`pdfs` - PDF files</li>
     *         </ul>
     *         You can pass multiple values in the types argument, like `types=posts,snippets`.
     *         The default value is `all`, which does not filter the list.
     *       </dd>
     *     <dt>FilesComponent::OPTION_COUNT</dt>
     *       <dd>Number of items to return per page.</dd>
     *     <dt>FilesComponent::OPTION_PAGE</dt>
     *       <dd>Page number of results to return.</dd>
     * </dl>
     *
     * @param array $option オプション.
     * @return mixed レスポンスデータ.
     *
     * @see https://api.slack.com/methods/files.list
     */
    public function fetchList( array $option=[] )
    {
        $requestData = [
            self::OPTION_TOKEN => Slack\API_TOKEN,
            self::OPTION_USER => null,
            self::OPTION_TS_FROM => null,
            self::OPTION_TS_TO => null,
            self::OPTION_TYPES => null,
            self::OPTION_COUNT => null,
            self::OPTION_PAGE => null,
        ];
        $requestData = array_merge( $requestData, $option );
        $response = self::getRequest( 'list', self::_nullFilter($requestData) );
        return $response;
    }

    /**
     * ファイル情報を取得する.
     *
     * This method returns information about a file in your team.
     *
     * ### Eg.
     * ```
     * $Files->fetchInfo( 'F01234567' );
     * ```
     *
     * ### Response.
     * ```
     * {
     *     "ok": true,
     *     "file": {
     *         "id": "F01234567",
     *         "created": 1440000000,
     *         "timestamp": 1440000000,
     *         "name": "sample.txt",
     *         "title": "Untitled",
     *         "mimetype": "text/plain",
     *         "filetype": "text",
     *         "pretty_type": "Plain Text",
     *         "user": "U01234567",
     *         "editable": true,
     *         "size": 12,
     *         "mode": "snippet",
     *         "is_external": false,
     *         "external_type": "",
     *         "is_public": true,
     *         "public_url_shared": false,
     *         "display_as_bot": false,
     *         "username": "",
     *         "url": "https://slack-files.com/files-pub/T01234567-F01234567-0000000000/sample.txt",
     *         "url_download": "https://slack-files.com/files-pub/T01234567-F01234567-0000000000/download/sample.txt",
     *         "url_private": "https://files.slack.com/files-pri/T01234567-F01234567/sample.txt",
     *         "url_private_download": "https://files.slack.com/files-pri/T01234567-F01234567/download/sample.txt",
     *         "permalink": "https://{your_team}.slack.com/files/{your_name}/F01234567/sample.txt",
     *         "permalink_public": "https://slack-files.com/T01234567-F01234567-0000000000",
     *         "edit_link": "https://{your_team}.slack.com/files/{your_name}/F01234567/sample.txt/edit",
     *         "preview": "Hello Slack!",
     *         "preview_highlight": "<div class=\"CodeMirror cm-s-default CodeMirrorServer\">\n<div class=\"CodeMirror-code\">\n<div><pre>Hello Slack!</pre></div>\n</div>\n</div>\n",
     *         "lines": 1,
     *         "lines_more": 0,
     *         "channels": [
     *             "C0EPZ563D"
     *         ],
     *         "groups": [],
     *         "ims": [],
     *         "comments_count": 0
     *     },
     *     "content": "Hello Slack!",
     *     "is_truncated": false,
     *     "content_highlight_html": "<div class=\"CodeMirror cm-s-default CodeMirrorServer\">\n<div class=\"CodeMirror-code\">\n<div><pre>Hello Slack!</pre></div>\n</div>\n</div>\n",
     *     "content_highlight_css": "\n.CodeMirror {\n  font-family: monospace;\n  height: auto;\n}\n\n.CodeMirror pre {\n  padding: 0;\n  background: transparent;\n  font-family: inherit;\n  font-size: inherit;\n  margin: 0;\n  white-space: pre;\n  word-wrap: normal;\n  line-height: inherit;\n  color: inherit;\n  z-index: 2;\n  position: relative;\n  overflow: visible;\n}\n\n.cm-keyword {color: #708;}\n.cm-atom {color: #219;}\n.cm-number {color: #164;}\n.cm-def {color: #00f;}\n.cm-variable-2 {color: #05a;}\n.cm-variable-3 {color: #085;}\n.cm-comment {color: #a50;}\n.cm-string {color: #a11;}\n.cm-string-2 {color: #f50;}\n.cm-meta {color: #555;}\n.cm-qualifier {color: #555;}\n.cm-builtin {color: #30a;}\n.cm-bracket {color: #997;}\n.cm-tag {color: #170;}\n.cm-attribute {color: #00c;}\n.cm-header {color: blue;}\n",
     *     "comments": [],
     *     "paging": {
     *         "count": 100,
     *         "total": 0,
     *         "page": 1,
     *         "pages": 0
     *     }
     * }
     * ```
     *
     * ### Use Option.
     * <dl class="tree">
     *     <dt>FilesComponent::OPTION_TOKEN</dt>
     *       <dd>Authentication token (Requires scope: `files:read`)</dd>
     *     <dt>FilesComponent::OPTION_FILE</dt>
     *       <dd>File to fetch info for.</dd>
     *     <dt>FilesComponent::OPTION_COUNT</dt>
     *       <dd>Number of items to return per page.</dd>
     *     <dt>FilesComponent::OPTION_PAGE</dt>
     *       <dd>Page number of results to return.</dd>
     * </dl>
     *
     * @param string $file ファイル ID.
     * @param array $option オプション.
     * @return mixed レスポンスデータ.
     *
     * @see https://api.slack.com/methods/files.info
     */
    public function fetchInfo( $file, array $option=[] )
    {
        $requestData = [
            self::OPTION_TOKEN => Slack\API_TOKEN,
            self::OPTION_FILE => $file,
            self::OPTION_COUNT => null,
            self::OPTION_PAGE => null,
        ];
        $requestData = array_merge( $requestData, $option );
        $response = self::getRequest( 'info', self::_nullFilter($requestData) );
        return $response;
    }

    /**
     * ファイルをアップロードする.
     *
     * This method allows you to create or upload an existing file.
     *
     * ### Eg.
     * ```
     * $Files->upload([
     *     FilesComponent::OPTION_CONTENT => 'Hello Slack!',
     *     FilesComponent::OPTION_FILENAME => 'sample.txt',
     *     FilesComponent::OPTION_CHANNELS => 'C0123456789',
     * ]);
     * ```
     *
     * ### Response.
     * ```
     * {
     *     "ok": true,
     *     "file": {
     *         "id": "F01234567",
     *         "created": 1440000000,
     *         "timestamp": 1440000000,
     *         "name": "-.txt",
     *         "title": "Untitled",
     *         "mimetype": "text/plain",
     *         "filetype": "text",
     *         "pretty_type": "Plain Text",
     *         "user": "U01234567",
     *         "editable": true,
     *         "size": 12,
     *         "mode": "snippet",
     *         "is_external": false,
     *         "external_type": "",
     *         "is_public": true,
     *         "public_url_shared": false,
     *         "display_as_bot": false,
     *         "username": "",
     *         "url": "https://slack-files.com/files-pub/T01234567-F01234567-0000000000/sample.txt",
     *         "url_download": "https://slack-files.com/files-pub/T01234567-F01234567-0000000000/download/sample.txt",
     *         "url_private": "https://files.slack.com/files-pri/T01234567-F01234567/sample.txt",
     *         "url_private_download": "https://files.slack.com/files-pri/T01234567-F01234567/download/sample.txt",
     *         "permalink": "https://{your_team}.slack.com/files/{your_name}/F01234567/sample.txt",
     *         "permalink_public": "https://slack-files.com/T01234567-F01234567-0000000000",
     *         "edit_link": "https://{your_team}.slack.com/files/{your_name}/F01234567/sample.txt/edit",
     *         "preview": "Hello Slack!",
     *         "preview_highlight": "<div class=\"CodeMirror cm-s-default CodeMirrorServer\">\n<div class=\"CodeMirror-code\">\n<div><pre>Hello Slack!</pre></div>\n</div>\n</div>\n",
     *         "lines": 1,
     *         "lines_more": 0,
     *         "channels": [],
     *         "groups": [],
     *         "ims": [],
     *         "comments_count": 0
     *     }
     * }
     * ```
     *
     * ### Use Option.
     * <dl class="tree">
     *   <dt>FilesComponent::OPTION_TOKEN</dt>
     *     <dd>Authentication token (Requires scope: `files:write:user`)</dd>
     *   <dt>FilesComponent::OPTION_FILE</dt>
     *     <dd>File contents via `multipart/form-data`.</dd>
     *   <dt>FilesComponent::OPTION_CONTENT</dt>
     *     <dd>File contents via a POST var.</dd>
     *   <dt>FilesComponent::OPTION_FILETYPE</dt>
     *     <dd>Slack-internal file type identifier.</dd>
     *   <dt>FilesComponent::OPTION_FILENAME</dt>
     *     <dd>Filename of file.</dd>
     *   <dt>FilesComponent::OPTION_TITLE</dt>
     *     <dd>Title of file.</dd>
     *   <dt>FilesComponent::OPTION_INITIAL_COMMENT</dt>
     *     <dd>Initial comment to add to file.</dd>
     *   <dt>FilesComponent::OPTION_CHANNELS</dt>
     *     <dd>Comma separated list of channels to share the file into.</dd>
     * </dl>
     *
     * @param array $option オプション.
     * @return mixed レスポンスデータ.
     *
     * @see https://api.slack.com/methods/files.upload
     */
    public function upload( array $option=[] )
    {
        $requestData = [
            self::OPTION_TOKEN => Slack\API_TOKEN,
            self::OPTION_FILE => null,
            self::OPTION_CONTENT => null,
            self::OPTION_FILETYPE => null,
            self::OPTION_FILENAME => null,
            self::OPTION_TITLE => null,
            self::OPTION_INITIAL_COMMENT => null,
            self::OPTION_CHANNELS => null,
        ];
        $requestData = array_merge( $requestData, $option );
        $response = self::postRequest( 'upload', self::_nullFilter($requestData) );
        return $response;
    }

    /**
     * ファイルを削除する.
     *
     * This method deletes a file from your team.
     *
     * ### Eg.
     * ```
     * $Files->delete( 'F01234567' );
     * ```
     *
     * ### Response.
     * ```
     * {
     *     "ok": true,
     * }
     * ```
     *
     * ### Use Option.
     * <dl class="tree">
     *   <dt>FilesComponent::OPTION_TOKEN</dt>
     *     <dd>Authentication token (Requires scope: `files:write:user`)</dd>
     *   <dt>FilesComponent::OPTION_FILE</dt>
     *     <dd>ID of file to delete.</dd>
     * </dl>
     *
     * @param string $file ファイル ID.
     * @param array $option オプション.
     * @return mixed レスポンスデータ.
     *
     * @see https://api.slack.com/methods/files.delete
     */
    public function delete( $file, array $option=[] )
    {
        $requestData = [
            self::OPTION_TOKEN => Slack\API_TOKEN,
            self::OPTION_FILE => $file,
        ];
        $requestData = array_merge( $requestData, $option );
        $response = self::postRequest( 'delete', self::_nullFilter($requestData) );
        return $response;
    }
}
