<?php

namespace App\Notifications;

use App\Models\Comment;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Broadcasting\PushNotificationChannel;

class OrderNeedsReview extends Notification implements ShouldQueue
{
    use Queueable;

    protected Order $order;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order->withoutRelations();
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', PushNotificationChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Precisamos da sua revisão (Practice Mural #' . $this->order->id . ')')
                    ->greeting('Olá, ' . $this->order->user->first_name)
                    ->line('Temos materiais prontos referentes à solicitação "*'.$this->order->title.'*" 🚀. Precisamos da sua revisão. Por favor, clique no botão abaixo para interagir:')
                    ->action('Revisar materiais', route('order.show', $this->order))
                    ->line('Você tem no máximo 72h para revisar, depois disso seguiremos nosso trabalho. Não deixe para revisar depois 😉! Sua interação garante que possamos finalizar sua solicitação, seguindo suas dicas, o mais rápido possível.')
                    ->line("Até mais,")
                    ->salutation("Equipe Practice ❤️");
    }

    public function toPushNotification($notifiable)
    {
        return [
            'title' => "Precisamos da sua revisão (Practice Mural #" . $this->order->id . ")",
            'body' => "Temos materiais prontos referentes à solicitação '".$this->order->title."'. Precisamos da sua revisão.",
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
