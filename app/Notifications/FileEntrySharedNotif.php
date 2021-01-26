<?php

namespace App\Notifications;

use App\FileEntry;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Collection;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\AndroidConfig;
use NotificationChannels\Fcm\Resources\AndroidFcmOptions;
use NotificationChannels\Fcm\Resources\AndroidNotification;
use NotificationChannels\Fcm\Resources\ApnsConfig;
use NotificationChannels\Fcm\Resources\ApnsFcmOptions;
use Str;

class FileEntrySharedNotif extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @var FileEntry[]|Collection
     */
    public $fileEntries;

    /**
     * @var User
     */
    public $sharer;

    /**
     * @param array[] $entryIds
     * @param User $sharer
     */
    public function __construct($entryIds, User $sharer)
    {
        $this->sharer = $sharer;
        $this->fileEntries = FileEntry::whereIn('id', $entryIds)->get();
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    /**
     * @param  mixed  $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        $message = (new MailMessage)
            ->subject(__('Files shared on :siteName', ['siteName' => config('app.name')]))
            ->line($this->getFirstLine());

            foreach ($this->getFileLines() as $line) {
                $message->line('- ' . $line['content']);
            }

            $message->action(__('View now'), url('drive/shares'));

            return $message;
    }

    public function toFcm($notifiable)
    {
        return FcmMessage::create()
            ->setData([
                'type' => 'fileShared',
                'multiple' => $this->fileEntries->count() > 1 ? 'true' : 'false',
                'entryHash' => $this->fileEntries->first()->hash
            ])
            ->setNotification(
                \NotificationChannels\Fcm\Resources\Notification::create()
                    ->setTitle(rtrim($this->getFirstLine(), ':'))
                    ->setBody($this->fileEntries->slice(0, 5)->map->name->implode(', '))
                    ->setImage('https://bedrive.vebto.com/client/assets/images/logo-dark.png')
            );
    }

    /**
     * @param User $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $data = [
            'image' => 'people',
            'mainAction' => [
                'action' => '',
            ],
            'lines' => [
                [
                    'content' => $this->getFirstLine(),
                ],
            ],
        ];

        $data['lines'] = array_merge($data['lines'], $this->getFileLines());

        return $data;
    }

    /**
     * @return array
     */
    private function getFileLines()
    {
        $lines = [];

        foreach ($this->fileEntries as $fileEntry) {
            $lines[] = [
                'icon' => Str::kebab($fileEntry->type),
                'content' => $fileEntry->name,
                'action' => ['action' => '/drive/shares'],
            ];
        }

        return $lines;
    }

    /**
     * @return string
     */
    private function getFirstLine()
    {
        $fileCount = $this->fileEntries->count();
        $username = $this->sharer->display_name;

        if ($this->fileEntries->count() === 1) {
            return __(':username shared a file with you:', ['username' => $username]);
        } else {
            return __(':username has shared :count files with you:', ['username' => $username, 'count' => $fileCount]);
        }
    }
}
