<?php

namespace Database\Seeders;

use App\Models\Set;
use App\Models\Team;
use App\Models\User;
use App\Models\Player;
use App\Models\TeamStat;
use App\Models\MatchModel;
use App\Models\TeamLineup;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'Admin', 'password' => Hash::make('password')]
        );

        $a = Team::create([
            'name'=>'Gandaki Thunders','short_name'=>'GND','city'=>'Gandaki','coach_name'=>'Piyush','logo_url'=>null
        ]);
        $b = Team::create([
            'name'=>'Lalitpur Queens','short_name'=>'LLP','city'=>'Lalitpur','coach_name'=>'Piyush','logo_url'=>null
        ]);

        $pos = Config::get('volleyball.positions');

        // Players (7 per team incl. libero)
        foreach ([
            [1,'Manvendra',7,$pos[0],true],
            [1,'Player A',10,$pos[1],false],
            [1,'Player B',12,$pos[2],false],
            [1,'Player C',14,$pos[3],false],
            [1,'Player D',15,$pos[0],false],
            [1,'Player E',8,$pos[4],false],
            [1,'Libero A',6,$pos[5],false],
            [2,'Player F',1,$pos[0],true],
            [2,'Player G',3,$pos[1],false],
            [2,'Player H',5,$pos[2],false],
            [2,'Player I',9,$pos[3],false],
            [2,'Player J',11,$pos[0],false],
            [2,'Player K',13,$pos[4],false],
            [2,'Libero B',2,$pos[5],false],
        ] as [$teamId,$name,$jersey,$pos,$captain]) {
            Player::create([
                'team_id'=>$teamId,'name'=>$name,'jersey_number'=>$jersey,'position'=>$pos,'is_captain'=>$captain
            ]);
        }

        $match = MatchModel::create([
            'team_a_id'=>$a->id,
            'team_b_id'=>$b->id,
            'match_date'=>now()->addDay(),
            'venue'=>'Kathmandu Arena',
            'status'=>'live'
        ]);

        for ($i=1; $i<=5; $i++) {
            Set::create(['match_id'=>$match->id,'set_number'=>$i,'team_a_score'=>0,'team_b_score'=>0,'is_completed'=>false]);
        }

        // lineups (positions 1..6)
        $lineA = Player::where('team_id',$a->id)->take(6)->get();
        $lineB = Player::where('team_id',$b->id)->take(6)->get();
        foreach ($lineA as $idx=>$p) {
            TeamLineup::create(['match_id'=>$match->id,'team_id'=>$a->id,'player_id'=>$p->id,'position_number'=>$idx+1,'is_starter'=>true]);
        }
        foreach ($lineB as $idx=>$p) {
            TeamLineup::create(['match_id'=>$match->id,'team_id'=>$b->id,'player_id'=>$p->id,'position_number'=>$idx+1,'is_starter'=>true]);
        }

        // minimal stats
        foreach (Team::all() as $t) {
            TeamStat::create([
                'match_id'=>$match->id,
                'team_id'=>$t->id,
                'kills'=>rand(0,5),'digs'=>rand(0,5),'aces'=>rand(0,3),
                'service'=>rand(5,15),
                'assists'=>rand(0,5),'blocks'=>rand(0,5)
            ]);
        }
    }
}
