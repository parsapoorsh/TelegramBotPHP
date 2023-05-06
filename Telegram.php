<?php

if (file_exists('TelegramErrorLogger.php')) {
    require_once 'TelegramErrorLogger.php';
}

/**
 * Telegram Bot Class.
 *
 * @author Gabriele Grillo <gabry.grillo@alice.it>
 * @author Parsa Poorsh <parsa.poorsh@gmail.com>
 */
class Telegram
{
    /**
     * Constant for type Inline Query.
     */
    const INLINE_QUERY = 'inline_query';
    /**
     * Constant for type Callback Query.
     */
    const CALLBACK_QUERY = 'callback_query';
    /**
     * Constant for type Edited Message.
     */
    const EDITED_MESSAGE = 'edited_message';
    /**
     * Constant for type Reply.
     */
    const REPLY = 'reply';
    /**
     * Constant for type Message.
     */
    const MESSAGE = 'message';
    /**
     * Constant for type Photo.
     */
    const PHOTO = 'photo';
    /**
     * Constant for type Video.
     */
    const VIDEO = 'video';
    /**
     * Constant for type Audio.
     */
    const AUDIO = 'audio';
    /**
     * Constant for type Voice.
     */
    const VOICE = 'voice';
    /**
     * Constant for type animation.
     */
    const ANIMATION = 'animation';
    /**
     * Constant for type sticker.
     */
    const STICKER = 'sticker';
    /**
     * Constant for type Document.
     */
    const DOCUMENT = 'document';
    /**
     * Constant for type Location.
     */
    const LOCATION = 'location';
    /**
     * Constant for type Contact.
     */
    const CONTACT = 'contact';
    /**
     * Constant for type Channel Post.
     */
    const CHANNEL_POST = 'channel_post';
    /**
     * Constant for type New Chat Member.
     */
    const NEW_CHAT_MEMBER = 'new_chat_member';
    /**
     * Constant for type Left Chat Member.
     */
    const LEFT_CHAT_MEMBER = 'left_chat_member';
    /**
     * Constant for type My Chat Member.
     */
    const MY_CHAT_MEMBER = 'my_chat_member';

    private $api;
    private string $bot_token;
    private array $data;
    private array $updates = [];
    private bool $log_errors;
    private array $proxy;
    private $update_type;

    /**
     * Create a Telegram instance from the bot token.
     *
     * @param $bot_token string the bot token
     * @param $log_errors bool enable or disable the logging
     * @param $proxy array with the proxy configuration (url, port, type, auth)
     *
     * @return void an instance of the class.
     */
    public function __construct(string $bot_token, bool $log_errors = true, array $proxy = [], $api = 'https://api.telegram.org')
    {
        $this->bot_token = $bot_token;
        $this->data = $this->getData();
        $this->log_errors = $log_errors;
        $this->proxy = $proxy;
        $this->api = $api;
    }

    /**
     * Contacts the various API endpoints.
     *
     * @param $api string the API endpoint
     * @param $content array the request parameters as array
     * @param $post bool tells if $content needs to be sends
     *
     * @return array the JSON Telegram's reply.
     */
    public function endpoint(string $api, array $content, bool $post = true): array
    {
        $url = $this->api.'/bot'.$this->bot_token.'/'.$api;
        if ($post) {
            $reply = $this->sendAPIRequest($url, $content);
        } else {
            $reply = $this->sendAPIRequest($url, [], false);
        }

        return json_decode($reply, true);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#getme">getMe</a>.
     *
     * @return array the JSON Telegram's reply.
     */
    public function getMe(): array
    {
        return $this->endpoint('getMe', [], false);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#logout">logOut</a>.
     *
     * @return array the JSON Telegram's reply.
     */
    public function logOut(): array
    {
        return $this->endpoint('logOut', [], false);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#close">close</a>.
     *
     * @return array the JSON Telegram's reply.
     */
    public function close(): array
    {
        return $this->endpoint('close', [], false);
    }

    /**
     * @return string the HTTP 200 to Telegram.
     */
    public function respondSuccess(): string
    {
        http_response_code(200);

        return json_encode(['status' => 'success']);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#sendmessage">sendMessage</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function sendMessage(array $content): array
    {
        return $this->endpoint('sendMessage', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#copymessage">copyMessage</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function copyMessage(array $content): array
    {
        return $this->endpoint('copyMessage', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#forwardmessage">forwardMessage</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function forwardMessage(array $content): array
    {
        return $this->endpoint('forwardMessage', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#sendphoto">sendPhoto</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function sendPhoto(array $content): array
    {
        return $this->endpoint('sendPhoto', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#sendaudio">sendAudio</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function sendAudio(array $content): array
    {
        return $this->endpoint('sendAudio', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#senddocument">sendDocument</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function sendDocument(array $content): array
    {
        return $this->endpoint('sendDocument', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#sendanimation">sendAnimation</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function sendAnimation(array $content): array
    {
        return $this->endpoint('sendAnimation', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#sendsticker">sendSticker</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function sendSticker(array $content): array
    {
        return $this->endpoint('sendSticker', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#sendvideo">sendVideo</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function sendVideo(array $content): array
    {
        return $this->endpoint('sendVideo', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#sendvoice">sendVoice</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function sendVoice(array $content): array
    {
        return $this->endpoint('sendVoice', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#sendlocation">sendLocation</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function sendLocation(array $content): array
    {
        return $this->endpoint('sendLocation', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#editmessageliveLocation">editMessageLiveLocation</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function editMessageLiveLocation(array $content): array
    {
        return $this->endpoint('editMessageLiveLocation', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#stopmessagelivelocation">stopMessageLiveLocation</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function stopMessageLiveLocation(array $content): array
    {
        return $this->endpoint('stopMessageLiveLocation', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#setchatstickerset">setChatStickerSet</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function setChatStickerSet(array $content): array
    {
        return $this->endpoint('setChatStickerSet', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#deletechatstickerset">deleteChatStickerSet</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function deleteChatStickerSet(array $content): array
    {
        return $this->endpoint('deleteChatStickerSet', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#sendmediagroup">sendMediaGroup</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function sendMediaGroup(array $content): array
    {
        return $this->endpoint('sendMediaGroup', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#sendvenue">sendVenue</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function sendVenue(array $content): array
    {
        return $this->endpoint('sendVenue', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#sendcontact">sendContact</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function sendContact(array $content): array
    {
        return $this->endpoint('sendContact', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#sendpoll">sendPoll</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function sendPoll(array $content): array
    {
        return $this->endpoint('sendPoll', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#senddice">sendDice</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function sendDice(array $content): array
    {
        return $this->endpoint('sendDice', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#sendchataction">sendChatAction</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function sendChatAction(array $content): array
    {
        return $this->endpoint('sendChatAction', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#getuserprofilephotos">getUserProfilePhotos</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function getUserProfilePhotos(array $content): array
    {
        return $this->endpoint('getUserProfilePhotos', $content);
    }

    /**
     *  Use this method to get basic info about a file and prepare it for downloading. For the moment, bots can download files of up to 20MB in size. On success, a File object is returned. The file can then be downloaded via the link https://api.telegram.org/file/bot<token>/<file_path>, where <file_path> is taken from the response. It is guaranteed that the link will be valid for at least 1 hour. When the link expires, a new one can be requested by calling getFile again.
     *
     * @param $file_id string File identifier to get info about
     *
     * @return array the JSON Telegram's reply.
     */
    public function getFile(string $file_id): array
    {
        $content = ['file_id' => $file_id];

        return $this->endpoint('getFile', $content);
    }

    /**
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function kickChatMember(array $content): array
    {
        return $this->endpoint('kickChatMember', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#leavechat">leaveChat</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function leaveChat(array $content): array
    {
        return $this->endpoint('leaveChat', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#banchatmember">banChatMember</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function banChatMember(array $content): array
    {
        return $this->endpoint('banChatMember', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#unbanchatmember">unbanChatMember</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function unbanChatMember(array $content): array
    {
        return $this->endpoint('unbanChatMember', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#getchat">getChat</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function getChat(array $content): array
    {
        return $this->endpoint('getChat', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#getchatadministrators">getChatAdministrators</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function getChatAdministrators(array $content): array
    {
        return $this->endpoint('getChatAdministrators', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#getchatmembercount">getChatMemberCount</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function getChatMemberCount(array $content): array
    {
        return $this->endpoint('getChatMemberCount', $content);
    }

    /**
     * For retro compatibility.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function getChatMembersCount(array $content): array
    {
        return $this->getChatMemberCount($content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#getchatmember">getChatMember</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function getChatMember(array $content): array
    {
        return $this->endpoint('getChatMember', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#answerinlinequery">answerInlineQuery</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function answerInlineQuery(array $content): array
    {
        return $this->endpoint('answerInlineQuery', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#setgamescore">setGameScore</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function setGameScore(array $content): array
    {
        return $this->endpoint('setGameScore', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#getgamehighscores">getGameHighScores</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function getGameHighScores(array $content): array
    {
        return $this->endpoint('getGameHighScores', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#answercallbackquery">answerCallbackQuery</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function answerCallbackQuery(array $content): array
    {
        return $this->endpoint('answerCallbackQuery', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#answerwebappquery</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function answerWebAppQuery(array $content): array
    {
        return $this->endpoint('answerWebAppQuery', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#setmycommands">setMyCommands</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function setMyCommands(array $content): array
    {
        return $this->endpoint('setMyCommands', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#getMyShortDescription">getMyShortDescription</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function getMyShortDescription(array $content): array
    {
        return $this->endpoint('getMyShortDescription', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#setMyShortDescription">setMyShortDescription</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function setMyShortDescription(array $content): array
    {
        return $this->endpoint('setMyShortDescription', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#getMyDescription">getMyDescription</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function getMyDescription(array $content): array
    {
        return $this->endpoint('getMyDescription', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#setMyDescription">setMyDescription</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function setMyDescription(array $content): array
    {
        return $this->endpoint('setMyDescription', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#getMyName">getMyName</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function getMyName(array $content): array
    {
        return $this->endpoint('getMyName', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#setMyName">setMyName</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function setMyName(array $content): array
    {
        return $this->endpoint('setMyName', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#deletemycommands">deleteMyCommands</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function deleteMyCommands(array $content): array
    {
        return $this->endpoint('deleteMyCommands', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#getmycommands">getMyCommands</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function getMyCommands(array $content): array
    {
        return $this->endpoint('getMyCommands', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#setchatmenubutton">setChatMenuButton</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function setChatMenuButton(array $content): array
    {
        return $this->endpoint('setChatMenuButton', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#getchatmenubutton">getChatMenuButton</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function getChatMenuButton(array $content): array
    {
        return $this->endpoint('getChatMenuButton', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#setmydefaultadministratorrights">setMyDefaultAdministratorRights</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function setMyDefaultAdministratorRights(array $content): array
    {
        return $this->endpoint('setMyDefaultAdministratorRights', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#getmydefaultadministratorrights">getMyDefaultAdministratorRights</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function getMyDefaultAdministratorRights(array $content): array
    {
        return $this->endpoint('getMyDefaultAdministratorRights', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#editmessagetext">editMessageText</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function editMessageText(array $content): array
    {
        return $this->endpoint('editMessageText', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#editmessagecaption">editMessageCaption</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function editMessageCaption(array $content): array
    {
        return $this->endpoint('editMessageCaption', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#editmessagemedia">editMessageMedia</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function editMessageMedia(array $content): array
    {
        return $this->endpoint('editMessageMedia', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#editmessagereplymarkup">editMessageReplyMarkup</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function editMessageReplyMarkup(array $content): array
    {
        return $this->endpoint('editMessageReplyMarkup', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#stoppoll">stopPoll</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function stopPoll(array $content): array
    {
        return $this->endpoint('stopPoll', $content);
    }

    /**
     *  Use this method to download a file from the Telegram servers.
     *
     * @param $telegram_file_path string File path on Telegram servers
     * @param $local_file_path string File path where save the file.
     */
    public function downloadFile(string $telegram_file_path, string $local_file_path)
    {
        $file_url = $this->api.'/file/bot'.$this->bot_token.'/'.$telegram_file_path;
        $in = fopen($file_url, 'rb');
        $out = fopen($local_file_path, 'wb');

        while ($chunk = fread($in, 8192)) {
            fwrite($out, $chunk, 8192);
        }
        fclose($in);
        fclose($out);
    }

    /**
     *  Use this method to specify an url and receive incoming updates via an outgoing webhook. Whenever there is an update for the bot, we will send an HTTPS POST request to the specified url, containing a JSON-serialized Update. In case of an unsuccessful request, we will give up after a reasonable amount of attempts.
     *
     * If you'd like to make sure that the Webhook request comes from Telegram, we recommend using a secret path in the URL, e.g. https://www.example.com/<token>. Since nobody else knows your bot token, you can be pretty sure it's us.
     *
     * @param $url string HTTPS url to send updates to. Use an empty string to remove webhook integration
     * @param $certificate string InputFile Upload your public key certificate so that the root certificate in use can be checked
     *
     * @return array the JSON Telegram's reply.
     */
    public function setWebhook(
        string $url,
        string $certificate = '',
        string $ip_address = '',
        int $max_connections = 0,
        array $allowed_updates = [],
        bool $drop_pending_updates = false,
        string $secret_token = ''
    ): array {
        $requestBody = ['url' => $url];
        if ($certificate != '') {
            $requestBody['certificate'] = "@$certificate";
        }
        if ($ip_address != '') {
            $requestBody['ip_address'] = $ip_address;
        }
        if ($max_connections != 0) {
            $requestBody['max_connections'] = $max_connections;
        }
        if (count($allowed_updates) > 0) {
            $requestBody['allowed_updates'] = json_encode($allowed_updates);
        }
        if ($drop_pending_updates) {
            $requestBody['drop_pending_updates'] = $drop_pending_updates;
        }
        if ($secret_token != '') {
            $requestBody['secret_token'] = $secret_token;
        }

        return $this->endpoint('setWebhook', $requestBody, true);
    }

    /**
     *  Use this method to remove webhook integration if you decide to switch back to <a href="https://core.telegram.org/bots/api#getupdates">getUpdates</a>. Returns True on success. Requires no parameters.
     *
     * @return array the JSON Telegram's reply.
     */
    public function deleteWebhook(): array
    {
        return $this->endpoint('deleteWebhook', [], false);
    }

    /** Get the POST request of a user in a Webhook or the message actually processed in a getUpdates() environment.
     * @return array the JSON Telegram's update.
     */
    public function getData(): array
    {
        if (empty($this->data)) {
            $rawData = file_get_contents('php://input');

            return json_decode($rawData, true);
        } else {
            return $this->data;
        }
    }

    public function setData(array $data)
    {
        $this->data = $data;
    }

    /**
     * @return string the String user's text.
     */
    public function Text(): ?string
    {
        $type = $this->getUpdateType();
        if ($type == self::CALLBACK_QUERY) {
            return @$this->data['callback_query']['data'];
        }
        if ($type == self::CHANNEL_POST) {
            return @$this->data['channel_post']['text'];
        }
        if ($type == self::EDITED_MESSAGE) {
            return @$this->data['edited_message']['text'];
        }

        return @$this->data['message']['text'];
    }

    public function Caption(): ?string
    {
        $type = $this->getUpdateType();
        if ($type == self::CHANNEL_POST) {
            return @$this->data['channel_post']['caption'];
        }

        return @$this->data['message']['caption'];
    }

    /**
     * @return int the user's chat_id.
     */
    public function ChatID(): int
    {
        $chat = $this->Chat();

        return $chat['id'];
    }

    /**
     * @return array the Array chat.
     */
    public function Chat(): array
    {
        $type = $this->getUpdateType();
        if ($type == self::CALLBACK_QUERY) {
            return @$this->data['callback_query']['message']['chat'];
        }
        if ($type == self::CHANNEL_POST) {
            return @$this->data['channel_post']['chat'];
        }
        if ($type == self::EDITED_MESSAGE) {
            return @$this->data['edited_message']['chat'];
        }
        if ($type == self::INLINE_QUERY) {
            return @$this->data['inline_query']['from'];
        }
        if ($type == self::MY_CHAT_MEMBER) {
            return @$this->data['my_chat_member']['chat'];
        }

        return $this->data['message']['chat'];
    }

    /**
     * @return int the message_id.
     */
    public function MessageID(): int
    {
        $type = $this->getUpdateType();
        if ($type == self::CALLBACK_QUERY) {
            return @$this->data['callback_query']['message']['message_id'];
        }
        if ($type == self::CHANNEL_POST) {
            return @$this->data['channel_post']['message_id'];
        }
        if ($type == self::EDITED_MESSAGE) {
            return @$this->data['edited_message']['message_id'];
        }

        return $this->data['message']['message_id'];
    }

    /**
     * @return int the String reply_to_message message_id.
     */
    public function ReplyToMessageID(): int
    {
        return $this->data['message']['reply_to_message']['message_id'];
    }

    /**
     * @return int the String reply_to_message forward_from user_id.
     */
    public function ReplyToMessageFromUserID(): int
    {
        return $this->data['message']['reply_to_message']['forward_from']['id'];
    }

    /**
     * @return array the Array inline_query.
     */
    public function Inline_Query(): array
    {
        return $this->data['inline_query'];
    }

    /**
     * @return array the String callback_query.
     */
    public function Callback_Query(): array
    {
        return $this->data['callback_query'];
    }

    /**
     * @return int the String callback_query id.
     */
    public function Callback_ID(): int
    {
        return $this->data['callback_query']['id'];
    }

    /**
     * @deprecated Use Text() instead
     *
     * @return string the String callback_data.
     */
    public function Callback_Data(): string
    {
        return $this->data['callback_query']['data'];
    }

    /**
     * @return array the Message.
     */
    public function Callback_Message(): array
    {
        return $this->data['callback_query']['message'];
    }

    /**
     * @deprecated Use ChatId() instead
     *
     * @return int the String callback_query.
     */
    public function Callback_ChatID(): int
    {
        return $this->data['callback_query']['message']['chat']['id'];
    }

    /**
     * @return int the String callback_query from_id.
     */
    public function Callback_FromID(): int
    {
        return $this->data['callback_query']['from']['id'];
    }

    /**
     * @return int the String message's date.
     */
    public function Date(): int
    {
        return $this->data['message']['date'];
    }

    /**
     * @return string the user's first name.
     */
    public function FirstName(): ?string
    {
        $type = $this->getUpdateType();
        if ($type == self::CALLBACK_QUERY) {
            return @$this->data['callback_query']['from']['first_name'];
        }
        if ($type == self::CHANNEL_POST) {
            return @$this->data['channel_post']['from']['first_name'];
        }
        if ($type == self::EDITED_MESSAGE) {
            return @$this->data['edited_message']['from']['first_name'];
        }

        return @$this->data['message']['from']['first_name'];
    }

    /**
     * @return string the user's last name.
     */
    public function LastName(): ?string
    {
        $type = $this->getUpdateType();
        if ($type == self::CALLBACK_QUERY) {
            return @$this->data['callback_query']['from']['last_name'];
        }
        if ($type == self::CHANNEL_POST) {
            return @$this->data['channel_post']['from']['last_name'];
        }
        if ($type == self::EDITED_MESSAGE) {
            return @$this->data['edited_message']['from']['last_name'];
        }
        if ($type == self::MESSAGE) {
            return @$this->data['message']['from']['last_name'];
        }

        return '';
    }

    /**
     * @return string the user's username.
     */
    public function Username(): ?string
    {
        $type = $this->getUpdateType();
        if ($type == self::CALLBACK_QUERY) {
            return @$this->data['callback_query']['from']['username'];
        }
        if ($type == self::CHANNEL_POST) {
            return @$this->data['channel_post']['from']['username'];
        }
        if ($type == self::EDITED_MESSAGE) {
            return @$this->data['edited_message']['from']['username'];
        }

        return @$this->data['message']['from']['username'];
    }

    public function Location()
    {
        return $this->data['message']['location'];
    }

    /**
     * @return int the update id.
     */
    public function UpdateID(): int
    {
        return $this->data['update_id'];
    }

    /**
     * @return int the number of updates.
     */
    public function UpdateCount(): int
    {
        return count($this->updates['result']);
    }

    /**
     * @return int the user's id.
     */
    public function UserID(): int
    {
        $type = $this->getUpdateType();
        if ($type == self::CALLBACK_QUERY) {
            return $this->data['callback_query']['from']['id'];
        }
        if ($type == self::CHANNEL_POST) {
            return $this->data['channel_post']['from']['id'];
        }
        if ($type == self::EDITED_MESSAGE) {
            return @$this->data['edited_message']['from']['id'];
        }
        if ($type == self::INLINE_QUERY) {
            return @$this->data['inline_query']['from']['id'];
        }

        return $this->data['message']['from']['id'];
    }

    /**
     * @return int the user's id of current forwarded message.
     */
    public function FromID(): int
    {
        return $this->data['message']['forward_from']['id'];
    }

    /**
     * @return int the chat's id where current message forwarded from.
     */
    public function FromChatID(): int
    {
        return $this->data['message']['forward_from_chat']['id'];
    }

    /**
     *  @return bool true if the message is from a Group chat, false otherwise.
     */
    public function messageFromGroup(): bool
    {
        return $this->data['message']['chat']['type'] != 'private';
    }

    /**
     *  @return string a String of the contact phone number.
     */
    public function getContactPhoneNumber(): string
    {
        if ($this->getUpdateType() == self::CONTACT) {
            return $this->data['message']['contact']['phone_number'];
        }

        return '';
    }

    /**
     *  @return string a String of the title chat.
     */
    public function messageFromGroupTitle(): string
    {
        if ($this->data['message']['chat']['type'] != 'private') {
            return $this->data['message']['chat']['title'];
        }

        return '';
    }

    /** This object represents a custom keyboard with reply options.
     * @param $options array Array of Array of String; Array of button rows, each represented by an Array of Strings
     * @param $onetime bool Requests clients to hide the keyboard as soon as it's been used. Defaults to false.
     * @param $resize bool Requests clients to resize the keyboard vertically for optimal fit (e.g., make the keyboard smaller if there are just two rows of buttons). Defaults to false, in which case the custom keyboard is always of the same height as the app's standard keyboard.
     * @param $selective bool Use this parameter if you want to show the keyboard to specific users only. Targets: 1) users that are @mentioned in the text of the Message object; 2) if the bot message is a reply (has reply_to_message_id), sender of the original message.
     *
     * @return string the requested keyboard as Json.
     */
    public function buildKeyBoard(array $options, bool $onetime = false, bool $resize = false, bool $selective = true): string
    {
        $replyMarkup = [
            'keyboard'          => $options,
            'one_time_keyboard' => $onetime,
            'resize_keyboard'   => $resize,
            'selective'         => $selective,
        ];

        return json_encode($replyMarkup, true);
    }

    /** This object represents an inline keyboard that appears right next to the message it belongs to.
     * @param $options array Array of Array of InlineKeyboardButton; Array of button rows, each represented by an Array of InlineKeyboardButton
     *
     * @return string the requested keyboard as Json.
     */
    public function buildInlineKeyBoard(array $options): string
    {
        $replyMarkup = [
            'inline_keyboard' => $options,
        ];

        return json_encode($replyMarkup, true);
    }

    /** This object represents one button of an inline keyboard. You must use exactly one of the optional fields.
     * @param $text string Array of button rows, each represented by an Array of Strings
     * @param $url string Optional. HTTP url to be opened when button is pressed
     * @param $callback_data string Optional. Data to be sent in a callback query to the bot when button is pressed
     * @param $switch_inline_query string Optional. If set, pressing the button will prompt the user to select one of their chats, open that chat and insert the bot username and the specified inline query in the input field. Can be empty, in which case just the bot’s username will be inserted.
     * @param $switch_inline_query_current_chat string Optional. Optional. If set, pressing the button will insert the bot username and the specified inline query in the current chat's input field. Can be empty, in which case only the bot’s username will be inserted.
     * @param $callback_game string Optional. Description of the game that will be launched when the user presses the button.
     * @param $pay bool Optional. Specify True, to send a <a href="https://core.telegram.org/bots/api#payments">Pay button</a>.
     *
     * @return array the requested button as Array.
     */
    public function buildInlineKeyboardButton(
        string $text,
        string $url = '',
        string $callback_data = '',
        string $switch_inline_query = '',
        string $switch_inline_query_current_chat = '',
        string $callback_game = '',
        bool $pay = false
    ): array {
        $replyMarkup = [
            'text' => $text,
        ];
        if ($url != '') {
            $replyMarkup['url'] = $url;
        } elseif ($callback_data != '') {
            $replyMarkup['callback_data'] = $callback_data;
        } elseif ($switch_inline_query != '') {
            $replyMarkup['switch_inline_query'] = $switch_inline_query;
        } elseif ($switch_inline_query_current_chat != '') {
            $replyMarkup['switch_inline_query_current_chat'] = $switch_inline_query_current_chat;
        } elseif ($callback_game != '') {
            $replyMarkup['callback_game'] = $callback_game;
        }
        if ($pay) {
            $replyMarkup['pay'] = $pay;
        }

        return $replyMarkup;
    }

    /** This object represents one button of an inline keyboard. You must use exactly one of the optional fields.
     * @param $text string Array of button rows, each represented by an Array of Strings
     * @param $request_contact bool Optional. If True, the user's phone number will be sent as a contact when the button is pressed. Available in private chats only
     * @param $request_location bool Optional. If True, the user's current location will be sent when the button is pressed. Available in private chats only
     *
     * @return array the requested button as Array.
     */
    public function buildKeyboardButton(string $text, bool $request_contact = false, bool $request_location = false): array
    {
        return [
            'text'             => $text,
            'request_contact'  => $request_contact,
            'request_location' => $request_location,
        ];
    }

    /** Upon receiving a message with this object, Telegram clients will hide the current custom keyboard and display the default letter-keyboard. By default, custom keyboards are displayed until a new keyboard is sent by a bot. An exception is made for one-time keyboards that are hidden immediately after the user presses a button.
     * @param $selective bool Use this parameter if you want to show the keyboard to specific users only. Targets: 1) users that are @mentioned in the text of the Message object; 2) if the bot message is a reply (has reply_to_message_id), sender of the original message.
     *
     * @return string the requested keyboard hide as Array.
     */
    public function buildKeyBoardHide(bool $selective = true): string
    {
        $replyMarkup = [
            'remove_keyboard' => true,
            'selective'       => $selective,
        ];

        return json_encode($replyMarkup, true);
    }

    /** Upon receiving a message with this object, Telegram clients will display a reply interface to the user (act as if the user has selected the bot message and tapped Reply). This can be extremely useful if you want to create user-friendly step-by-step interfaces without having to sacrifice privacy mode.
     * @param $selective bool Use this parameter if you want to show the keyboard to specific users only. Targets: 1) users that are @mentioned in the text of the Message object; 2) if the bot message is a reply (has reply_to_message_id), sender of the original message.
     *
     * @return string the requested force reply as Array
     */
    public function buildForceReply(bool $selective = true): string
    {
        $replyMarkup = [
            'force_reply' => true,
            'selective'   => $selective,
        ];

        return json_encode($replyMarkup, true);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#sendinvoice">sendInvoice</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function sendInvoice(array $content): array
    {
        return $this->endpoint('sendInvoice', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#createInvoiceLink">createInvoiceLink</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function createInvoiceLink(array $content): array
    {
        return $this->endpoint('createInvoiceLink', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#answershippingquery">answerShippingQuery</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function answerShippingQuery(array $content): array
    {
        return $this->endpoint('answerShippingQuery', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#answerprecheckoutquery">answerPreCheckoutQuery</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function answerPreCheckoutQuery(array $content): array
    {
        return $this->endpoint('answerPreCheckoutQuery', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#setpassportdataerrors">setPassportDataErrors</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function setPassportDataErrors(array $content): array
    {
        return $this->endpoint('setPassportDataErrors', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#sendgame">sendGame</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function sendGame(array $content): array
    {
        return $this->endpoint('sendGame', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#sendvideonote">sendVideoNote</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function sendVideoNote(array $content): array
    {
        return $this->endpoint('sendVideoNote', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#restrictchatmember">restrictChatMember</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function restrictChatMember(array $content): array
    {
        return $this->endpoint('restrictChatMember', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#promotechatmember">promoteChatMember</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function promoteChatMember(array $content): array
    {
        return $this->endpoint('promoteChatMember', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#setchatadministratorcustomtitle">setChatAdministratorCustomTitle</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function setChatAdministratorCustomTitle(array $content): array
    {
        return $this->endpoint('setChatAdministratorCustomTitle', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#banchatsenderchat">banChatSenderChat</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function banChatSenderChat(array $content): array
    {
        return $this->endpoint('banChatSenderChat', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#unbanchatsenderchat">unbanChatSenderChat</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function unbanChatSenderChat(array $content): array
    {
        return $this->endpoint('unbanChatSenderChat', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#setchatpermissions">setChatPermissions</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function setChatPermissions(array $content): array
    {
        return $this->endpoint('setChatPermissions', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#exportchatinvitelink">exportChatInviteLink</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function exportChatInviteLink(array $content): array
    {
        return $this->endpoint('exportChatInviteLink', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#createchatinvitelink">createChatInviteLink</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function createChatInviteLink(array $content): array
    {
        return $this->endpoint('createChatInviteLink', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#editchatinvitelink">editChatInviteLink</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function editChatInviteLink(array $content): array
    {
        return $this->endpoint('editChatInviteLink', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#revokechatinvitelink">revokeChatInviteLink</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function revokeChatInviteLink(array $content): array
    {
        return $this->endpoint('revokeChatInviteLink', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#approvechatjoinrequest">approveChatJoinRequest</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function approveChatJoinRequest(array $content): array
    {
        return $this->endpoint('approveChatJoinRequest', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#declinechatjoinrequest">declineChatJoinRequest</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function declineChatJoinRequest(array $content): array
    {
        return $this->endpoint('declineChatJoinRequest', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#setchatphoto">setChatPhoto</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function setChatPhoto(array $content): array
    {
        return $this->endpoint('setChatPhoto', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#deletechatphoto">deleteChatPhoto</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function deleteChatPhoto(array $content): array
    {
        return $this->endpoint('deleteChatPhoto', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#setchattitle">setChatTitle</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function setChatTitle(array $content): array
    {
        return $this->endpoint('setChatTitle', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#setchatdescription">setChatDescription</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function setChatDescription(array $content): array
    {
        return $this->endpoint('setChatDescription', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#pinchatmessage">pinChatMessage</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function pinChatMessage(array $content): array
    {
        return $this->endpoint('pinChatMessage', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#unpinchatmessage">unpinChatMessage</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function unpinChatMessage(array $content): array
    {
        return $this->endpoint('unpinChatMessage', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#unpinallchatmessages">unpinAllChatMessages</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function unpinAllChatMessages(array $content): array
    {
        return $this->endpoint('unpinAllChatMessages', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#createForumTopic">createForumTopic</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function createForumTopic(array $content): array
    {
        return $this->endpoint('createForumTopic', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#editForumTopic">editForumTopic</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function editForumTopic(array $content): array
    {
        return $this->endpoint('editForumTopic', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#closeForumTopic">closeForumTopic</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function closeForumTopic(array $content): array
    {
        return $this->endpoint('closeForumTopic', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#reopenForumTopic">reopenForumTopic</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function reopenForumTopic(array $content): array
    {
        return $this->endpoint('reopenForumTopic', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#deleteForumTopic">deleteForumTopic</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function deleteForumTopic(array $content): array
    {
        return $this->endpoint('deleteForumTopic', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#unpinAllForumTopicMessages">unpinAllForumTopicMessages</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function unpinAllForumTopicMessages(array $content): array
    {
        return $this->endpoint('unpinAllForumTopicMessages', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#editGeneralForumTopic">editGeneralForumTopic</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function editGeneralForumTopic(array $content): array
    {
        return $this->endpoint('editGeneralForumTopic', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#closeGeneralForumTopic">closeGeneralForumTopic</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function closeGeneralForumTopic(array $content): array
    {
        return $this->endpoint('closeGeneralForumTopic', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#reopenGeneralForumTopic">reopenGeneralForumTopic</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function reopenGeneralForumTopic(array $content): array
    {
        return $this->endpoint('reopenGeneralForumTopic', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#hideGeneralForumTopic">hideGeneralForumTopic</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function hideGeneralForumTopic(array $content): array
    {
        return $this->endpoint('hideGeneralForumTopic', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#unhideGeneralForumTopic">unhideGeneralForumTopic</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function unhideGeneralForumTopic(array $content): array
    {
        return $this->endpoint('unhideGeneralForumTopic', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#getForumTopicIconStickers ">getForumTopicIconStickers </a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function getForumTopicIconStickers(array $content): array
    {
        return $this->endpoint('getForumTopicIconStickers ', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#getstickerset">getStickerSet</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function getStickerSet(array $content): array
    {
        return $this->endpoint('getStickerSet', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#getstickerset">getCustomEmojiStickers</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function getCustomEmojiStickers(array $content): array
    {
        return $this->endpoint('getCustomEmojiStickers', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#uploadstickerfile">uploadStickerFile</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function uploadStickerFile(array $content): array
    {
        return $this->endpoint('uploadStickerFile', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#createnewstickerset">createNewStickerSet</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function createNewStickerSet(array $content): array
    {
        return $this->endpoint('createNewStickerSet', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#setStickerSetTitle">setStickerSetTitle</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function setStickerSetTitle(array $content): array
    {
        return $this->endpoint('setStickerSetTitle', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#deleteStickerSet">deleteStickerSet</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function deleteStickerSet(array $content): array
    {
        return $this->endpoint('deleteStickerSet', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#addstickertoset">addStickerToSet</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function addStickerToSet(array $content): array
    {
        return $this->endpoint('addStickerToSet', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#setstickerpositioninset">setStickerPositionInSet</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function setStickerPositionInSet(array $content): array
    {
        return $this->endpoint('setStickerPositionInSet', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#deletestickerfromset">deleteStickerFromSet</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function deleteStickerFromSet(array $content): array
    {
        return $this->endpoint('deleteStickerFromSet', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#setStickerEmojiList">setStickerEmojiList</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function setStickerEmojiList(array $content): array
    {
        return $this->endpoint('setStickerEmojiList', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#setStickerKeywords">setStickerKeywords</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function setStickerKeywords(array $content): array
    {
        return $this->endpoint('setStickerKeywords', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#setStickerMaskPosition">setStickerMaskPosition</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function setStickerMaskPosition(array $content): array
    {
        return $this->endpoint('setStickerMaskPosition', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#setCustomEmojiStickerSetThumbnail">setCustomEmojiStickerSetThumbnail</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function setCustomEmojiStickerSetThumbnail(array $content): array
    {
        return $this->endpoint('setCustomEmojiStickerSetThumbnail', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#setStickerSetThumbnail">setStickerSetThumbnail</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function setStickerSetThumbnail(array $content): array
    {
        return $this->endpoint('setStickerSetThumbnail', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#setStickerSetThumbnail">setStickerSetThumbnail</a> for the input values.
     *
     * @deprecated Use setStickerSetThumbnail() instead
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function setStickerSetThumb(array $content): array
    {
        return $this->endpoint('setStickerSetThumb', $content);
    }

    /**
     * See <a href="https://core.telegram.org/bots/api#deletemessage">deleteMessage</a> for the input values.
     *
     * @param $content array the request parameters as array
     *
     * @return array the JSON Telegram's reply.
     */
    public function deleteMessage(array $content): array
    {
        return $this->endpoint('deleteMessage', $content);
    }

    /** Use this method to receive incoming updates using long polling.
     * @param $offset int Identifier of the first update to be returned. Must be greater by one than the highest among the identifiers of previously received updates. By default, updates starting with the earliest unconfirmed update are returned. An update is considered confirmed as soon as getUpdates is called with an offset higher than its update_id.
     * @param $limit int Limits the number of updates to be retrieved. Values between 1—100 are accepted. Defaults to 100
     * @param $timeout int Timeout in seconds for long polling. Defaults to 0, i.e. usual short polling
     * @param $update bool If true updates the pending message list to the last update received. Default to true.
     *
     * @return array the updates as Array.
     */
    public function getUpdates(int $offset = 0, int $limit = 100, int $timeout = 0, bool $update = true): array
    {
        $content = ['offset' => $offset, 'limit' => $limit, 'timeout' => $timeout];
        $this->updates = $this->endpoint('getUpdates', $content);
        if ($update) {
            //for CLI working.
            if (array_key_exists('result', $this->updates) && is_array($this->updates['result']) && count($this->updates['result']) >= 1) {
                $last_element_id = $this->updates['result'][count($this->updates['result']) - 1]['update_id'] + 1;
                $content = ['offset' => $last_element_id, 'limit' => '1', 'timeout' => $timeout];
                $this->endpoint('getUpdates', $content);
            }
        }

        return $this->updates;
    }

    /** Use this method to use the builtin function like Text() or Username() on a specific update.
     * @param $update int The index of the update in the updates array.
     */
    public function serveUpdate(int $update)
    {
        $this->data = $this->updates['result'][$update];
    }

    /**
     * Return current update type `False` on failure.
     *
     * @return bool|string
     */
    public function getUpdateType()
    {
        if ($this->update_type) {
            return $this->update_type;
        }

        $update = $this->data;
        $this->update_type = false;
        if (isset($update['inline_query'])) {
            $this->update_type = self::INLINE_QUERY;
        }
        if (isset($update['callback_query'])) {
            $this->update_type = self::CALLBACK_QUERY;
        }
        if (isset($update['edited_message'])) {
            $this->update_type = self::EDITED_MESSAGE;
        }
        if (isset($update['message']['text'])) {
            $this->update_type = self::MESSAGE;
        }
        if (isset($update['message']['photo'])) {
            $this->update_type = self::PHOTO;
        }
        if (isset($update['message']['video'])) {
            $this->update_type = self::VIDEO;
        }
        if (isset($update['message']['audio'])) {
            $this->update_type = self::AUDIO;
        }
        if (isset($update['message']['voice'])) {
            $this->update_type = self::VOICE;
        }
        if (isset($update['message']['contact'])) {
            $this->update_type = self::CONTACT;
        }
        if (isset($update['message']['location'])) {
            $this->update_type = self::LOCATION;
        }
        if (isset($update['message']['reply_to_message'])) {
            $this->update_type = self::REPLY;
        }
        if (isset($update['message']['animation'])) {
            $this->update_type = self::ANIMATION;
        }
        if (isset($update['message']['sticker'])) {
            $this->update_type = self::STICKER;
        }
        if (isset($update['message']['document'])) {
            $this->update_type = self::DOCUMENT;
        }
        if (isset($update['message']['new_chat_member'])) {
            $this->update_type = self::NEW_CHAT_MEMBER;
        }
        if (isset($update['message']['left_chat_member'])) {
            $this->update_type = self::LEFT_CHAT_MEMBER;
        }
        if (isset($update['my_chat_member'])) {
            $this->update_type = self::MY_CHAT_MEMBER;
        }
        if (isset($update['channel_post'])) {
            $this->update_type = self::CHANNEL_POST;
        }

        return $this->update_type;
    }

    private function sendAPIRequest($url, array $content, $post = true)
    {
        if (isset($content['chat_id'])) {
            $url = $url.'?chat_id='.$content['chat_id'];
            unset($content['chat_id']);
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if ($post) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
        }
        if (!empty($this->proxy)) {
            if (array_key_exists('type', $this->proxy)) {
                curl_setopt($ch, CURLOPT_PROXYTYPE, $this->proxy['type']);
            }

            if (array_key_exists('auth', $this->proxy)) {
                curl_setopt($ch, CURLOPT_PROXYUSERPWD, $this->proxy['auth']);
            }

            if (array_key_exists('url', $this->proxy)) {
                curl_setopt($ch, CURLOPT_PROXY, $this->proxy['url']);
            }

            if (array_key_exists('port', $this->proxy)) {
                curl_setopt($ch, CURLOPT_PROXYPORT, $this->proxy['port']);
            }
        }
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);
        if ($result === false) {
            $result = json_encode(
                ['ok' => false, 'curl_error_code' => curl_errno($ch), 'curl_error' => curl_error($ch)]
            );
        }
        curl_close($ch);
        if ($this->log_errors) {
            if (class_exists('TelegramErrorLogger')) {
                $loggerArray = ($this->getData() == null) ? [$content] : [$this->getData(), $content];
                TelegramErrorLogger::log(json_decode($result, true), $loggerArray);
            }
        }

        return $result;
    }
}

// Helper for Uploading file using CURL
if (!function_exists('curl_file_create')) {
    function curl_file_create($filename, $mimetype = '', $postname = ''): string
    {
        return "@$filename;filename="
            .($postname ?: basename($filename))
            .($mimetype ? ";type=$mimetype" : '');
    }
}
