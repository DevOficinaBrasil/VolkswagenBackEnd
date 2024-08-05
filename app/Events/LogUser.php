<?php

namespace App\Events;

use App\Models\UserLog;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LogUser
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct($training, $user, $concessionaire, $presence = 'P')
    {
        $data = UserLog::firstOrCreate([
            'CadastroID'        => $user,
            'Treinamento'       => $training,
            'concessionaire_id' => $concessionaire,
        ],[
            'TreinamentoParticipou' => 'S',
            'Participou'            => $presence,
        ]);
        
        if(!$data->wasRecentlyCreated && $presence == 'P'){
            $data->TreinamentoParticipou = $data->TreinamentoParticipou == 'S' ? 'N' : 'S';

            $data->save();
        }
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
