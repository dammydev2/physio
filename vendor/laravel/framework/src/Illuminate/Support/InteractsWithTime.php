on
     */
    public function sent($notifiable, $notification, $callback = null)
    {
        if (! $this->hasSent($notifiable, $notification)) {
            return collect();
        }

        $callback = $callback ?: function () {
            return true;
        };

        $notifications = collect($this->notificationsFor($notifiable, $notification));

        return $notifications->filter(function ($arguments) use ($callback) {
            return $callback(...array_values($arguments));
        })->pluck('notification');
    }

    /**
     * Determine if there are more notifications left to inspect.
     *
     * @param  mixed  $notifiable
     * @param  string  $notification
     * @return bool
     */
    public function hasSent($notifiable, $notification)
    {
        return ! empty($this->notificationsFor($notifiable, $notification));
    }

    /**
     * Get all of the notifications for a notifiable entity by type.
     *
     * @param  mixed  $notifiable
     * @param  string  $notification
     * @return array
     */
    protected function notificationsFor($notifiable, $notification)
    {
        return $this->notifications[get_class($notifiable)][$notifiable->getKey()][$notification] ?? [];
    }

    /**
     * Send the given notification to the given notifiable entities.
     *
     * @param  \Illuminate\Support\Collection|array|mixed  $notifiables
     * @param  mixed  $notification
     * @return void
     */
    public function send($notifiables, $notification)
    {
        return $this->sendNow($notifiables, $notification);
    }

    /